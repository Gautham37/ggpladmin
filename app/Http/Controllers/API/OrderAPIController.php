<?php

namespace App\Http\Controllers\API;


use App\Criteria\Orders\OrdersOfStatusesCriteria;
use App\Criteria\Orders\OrdersOfUserCriteria;
use App\Criteria\Orders\OrdersOfDateCriteriaCriteria;

use App\Events\OrderChangedEvent;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Notifications\AssignedOrder;
use App\Notifications\NewOrder;
use App\Notifications\StatusChangedOrder;
use App\Repositories\CartRepository;
use App\Repositories\NotificationRepository;
use App\Repositories\OrderRepository;
use App\Repositories\PaymentRepository;
use App\Repositories\ProductOrderRepository;
use App\Repositories\InventoryRepository;
use App\Repositories\UserRepository;
use App\Repositories\DeliveryZonesRepository;
use Flash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use InfyOm\Generator\Criteria\LimitOffsetCriteria;
use Prettus\Repository\Criteria\RequestCriteria;
use Prettus\Repository\Exceptions\RepositoryException;
use Prettus\Validator\Exceptions\ValidatorException;
use Illuminate\Support\Facades\Validator;
//use Stripe\Token;
use CustomHelper;
use App\Repositories\ProductRepository;
use App\Mail\OrdersMail;
use DB;

use Stripe\Token;
use Stripe\Stripe;
use Stripe\Charge;
use Stripe\Stripe_CardError;
use Stripe\Stripe_InvalidRequestError;

/**
 * Class OrderController
 * @package App\Http\Controllers\API
 */
class OrderAPIController extends Controller
{
    /** @var  OrderRepository */
    private $orderRepository;
    /** @var  ProductOrderRepository */
    private $productOrderRepository;
    /** @var  InventoryRepository */
    private $inventoryRepository;
    /** @var  CartRepository */
    private $cartRepository;
    /** @var  UserRepository */
    private $userRepository;
    /** @var  PaymentRepository */
    private $paymentRepository;
    /** @var  NotificationRepository */
    private $notificationRepository;
    private $productRepository;
    private $deliveryZonesRepository;

    /**
     * OrderAPIController constructor.
     * @param OrderRepository $orderRepo
     * @param ProductOrderRepository $productOrderRepository
     * @param CartRepository $cartRepo
     * @param PaymentRepository $paymentRepo
     * @param NotificationRepository $notificationRepo
     * @param UserRepository $userRepository
     */
    public function __construct(OrderRepository $orderRepo, ProductOrderRepository $productOrderRepository, CartRepository $cartRepo, PaymentRepository $paymentRepo, NotificationRepository $notificationRepo, UserRepository $userRepository, InventoryRepository $inventoryRepository, ProductRepository $productRepo, DeliveryZonesRepository $deliveryZonesRepo)
    {
        $this->orderRepository = $orderRepo;
        $this->productOrderRepository = $productOrderRepository;
        $this->cartRepository = $cartRepo;
        $this->userRepository = $userRepository;
        $this->paymentRepository = $paymentRepo;
        $this->notificationRepository = $notificationRepo;
        $this->inventoryRepository = $inventoryRepository;
        $this->productRepository = $productRepo;
        $this->deliveryZonesRepository = $deliveryZonesRepo;
    }

    /**
     * Display a listing of the Order.
     * GET|HEAD /orders
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        try {
            
            $date = $request->date;
            $this->orderRepository->pushCriteria(new RequestCriteria($request));
            $this->orderRepository->pushCriteria(new OrdersOfStatusesCriteria($request));
            $this->orderRepository->pushCriteria(new OrdersOfDateCriteriaCriteria($request));
            $this->orderRepository->pushCriteria(new OrdersOfUserCriteria(auth()->id()));

        } catch (RepositoryException $e) {
            return $this->sendError($e->getMessage());
        }

        $orders  = $this->orderRepository
                        ->with('productOrders')
                        ->with('deliveryAddress')
                        ->with('deliverytrack')
                        ->with('redeempoints')
                        ->with('activities')
                        ->with('payment')
                        ->where('is_deleted',0)
                        ->get();

        return $this->sendResponse($orders->toArray(), 'Orders retrieved successfully');
    }

    /**
     * Display the specified Order.
     * GET|HEAD /orders/{id}
     *
     * @param int $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Request $request, $id)
    {
        /** @var Order $order */
        if (!empty($this->orderRepository)) {
            try {
                
                $this->orderRepository->pushCriteria(new RequestCriteria($request));
                $this->orderRepository->pushCriteria(new OrdersOfUserCriteria(auth()->id()));

            } catch (RepositoryException $e) {
                return $this->sendError($e->getMessage());
            }
            $order  =  $this->orderRepository
                            ->with('productOrders')
                            ->with('deliveryAddress')
                            ->with('deliverytrack')
                            ->with('redeempoints')
                            ->with('activities')
                            ->with('payment') 
                            ->findWithoutFail($id);
        }
        if (empty($order)) {
            return $this->sendError('Order not found');
        }
        return $this->sendResponse($order->toArray(), 'Order retrieved successfully');

    }

    /**
     * Store a newly created Order in storage.
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        /*$payment = $request->only('payment');
        if (isset($payment['payment']) && $payment['payment']['method']) {
            if ($payment['payment']['method'] == "Credit Card (Stripe Gateway)") {
                return $this->stripPayment($request);
            } else {
                return $this->cashPayment($request);

            }
        }*/

        $validator = Validator::make($request->toArray(),[
            'delivery_address'      => 'required',
            'payment_type'          => 'required',
            "products"              => "required|array|min:1",
            "products.*"            => "required|min:1",
            "delivery_charge"       => "required",
            "delivery_distance"     => "required"
        ]);
        if ($validator->fails()) {    
            return $this->sendError($validator->messages());
        }

        if($validator) {

            $input = $request->all();

            //Stock Validation  
                $st = 0;  
                foreach($input['products'] as $cartItem) {  
                    $product = $this->productRepository->findWithoutFail($cartItem['product_id']); //Fetch Single Data;
                    //All Units
                    $units   = $product->allunits($product->id);
                    //Pre purchased Stock
                    $pre_puchase[] = '';
                    foreach($units as $unit) {
                        if($cartItem['product_unit'] == $unit['id']) {
                            $pre_puchase[] = $cartItem['quantity'] * $unit['quantity'];
                        }
                    }                
                    if(array_sum($pre_puchase) > $product->stock) {
                        $st++;
                    }
                    if($cartItem['quantity']==0) {
                        $st++;
                    }
                }
                if($st > 0) {
                    return $this->sendError('Some products are Sold Out. Please try again');   
                }
            //Stock Validation

            //Create Order

                //Amount Calcaulation
                $totalWithoutTax = [];
                $totalTaxPercent = [];
                foreach($input['products'] as $cart_item) {
                    $totalWithoutTax[] = $cart_item['sub_total'];
                    $totalTaxPercent[] = $cart_item['tax_percent'];
                }
                $tax_amount  = $input['cart_sub_total'] - array_sum($totalWithoutTax);
                $totalAmount = array_sum($totalWithoutTax);
                $taxPercent  = ($tax_amount / $totalAmount * 100);
                //Amount Calcaulation

                //Redeem values
                $redeemValue[] = isset($input['redeem']) ? $input['redeem'] : 0 ;
                //Redeem values
                
                //Coupon values
                $couponValue[] = isset($input['coupon']) ? $input['coupon'] : 0 ;
                //Coupon values
                
                //Contribution values
                $contributionValue[] = isset($input['contribution']) ? $input['contribution'] : 0 ;
                //Contribution values
                
                //Delivery charge
                $deliveryCharge[]   = isset($input['delivery_charge']) ? $input['delivery_charge'] : 0;
                $deliveryDistance[] = isset($input['delivery_distance']) ? $input['delivery_distance'] : 0 ;
                //Delivery charge
                
                $max_id = Order::max('id') + 1;
                $order_code_prefix = "GGPL" . date('y') . (date('y') + 1) . "ID";
                 $order_code = null;
                if ($max_id <= 9) {
                    $order_code = $order_code_prefix . "000000" . $max_id;
                } else if ($max_id <= 99) {
                    $order_code = $order_code_prefix . "00000" . $max_id;
                } else if ($max_id <= 999) {
                    $order_code = $order_code_prefix . "0000" . $max_id;
                } else if ($max_id <= 9999) {
                    $order_code = $order_code_prefix . "000" . $max_id;
                } else if ($max_id <= 99999) {
                    $order_code = $order_code_prefix . "00" . $max_id;
                } else if ($max_id <= 999999) {
                    $order_code = $order_code_prefix . "0" .$max_id;
                } else {
                    $order_code = $order_code_prefix . $max_id;
                }

                if($request->payment_type == 'CARD') {

                    $stripeToken = Token::create(array(
                        "card" => array(
                            "number"    => $input['stripe_number'],
                            "exp_month" => $input['stripe_exp_month'],
                            "exp_year"  => $input['stripe_exp_year'],
                            "cvc"       => $input['stripe_cvc'],
                            "name"      => auth()->user()->name,
                        )
                    ));

                    $token = $stripeToken->id;
                    
                    //Stripe Charge
                    Stripe::setApiKey(setting('stripe_secret'));
                    $charge = Charge::create([
                        'amount'        => number_format($input['cart_sub_total'],setting('app_price_format'),'.','')*100,
                        'currency'      => 'inr',
                        'description'   => config('app.name', 'Laravel'),
                        'source'        => $token,
                        'metadata'      => array('chargetype'=>'ORDERPAYMENT','chargeref'=>$order_code)
                    ]);
                    //Stripe Charge
                    $transactionId = 'Transaction ID : '.$charge->id;

                } elseif($request->payment_type == 'CASH') {

                    $charge = true;
                    $transactionId = '';

                }

                if($charge) {

                    //Insert Order Data
                    $order_data = array(
                        'order_code'            => $order_code,
                        'user_id'               => auth()->user()->id,
                        'order_status_id'       => 1,
                        'tax'                   => $taxPercent,
                        'delivery_fee'          => array_sum($deliveryCharge), 
                        'delivery_distance'     => array_sum($deliveryDistance),
                        'redeem_amount'         => array_sum($redeemValue),
                        'coupon_amount'         => array_sum($couponValue),
                        'contribution_amount'   => array_sum($contributionValue),
                        'order_amount'          => number_format($input['cart_sub_total'],setting('app_price_format'),'.',''),
                        'delivery_address_id'   => $input['delivery_address'],
                    );
                    $order = $this->orderRepository->create($order_data);
                    //Insert Order Data

                    //Insert Order Products
                    foreach ($input['products'] as $productOrder) {
                        
                        $product = $this->productRepository->findWithoutFail($productOrder['product_id']);
                        $poData  = array(
                            'order_id'          => $order->id,
                            'product_id'        => $product->id,
                            'product_name'      => $product->name,
                            'product_code'      => $product->product_code,
                            'quantity'          => $productOrder['quantity'],
                            'unit'              => $productOrder['product_unit'],
                            'unit_price'        => $productOrder['price'],
                            'discount'          => 0,
                            'discount_amount'   => 0,
                            'tax'               => $productOrder['tax_percent'],
                            'tax_amount'        => $productOrder['tax_amount'],
                            'amount'            => $productOrder['total']
                        );
                        $product_order       = $this->productOrderRepository->create($poData);

                        $this->addInventory(
                            $product_order->product->id,
                            $order->user->market->id,
                            'online',
                            'reduce',
                            $product_order->quantity,
                            $product_order->unit,
                            $product_order->product_name,
                            'product_order_item_id',
                            $product_order->id
                        );
                        $this->productStockupdate($product_order->product->id);

                    }
                    //Insert Order Products

                    //Create Payment  
                        $payment = $this->paymentRepository->create([
                          "order_id"    => $order->id,
                          "user_id"     => auth()->user()->id,
                          "price"       => $input['cart_sub_total'],
                          "description" => $transactionId,
                          "status"      => 'paid',
                          "method"      => $request->payment_type,
                        ]);
                    //Create Payment
                    
                    $order->activity()->create([
                        'order_id'  => $order->id,
                        'action'    => 'Received',
                        'notes'     => auth()->user()->market->name.' '.auth()->user()->market->code.' Order Received for '.number_format($order->order_amount,2,'.','')
                    ]);

                    //Transaction
                       $this->addTransaction(
                            'online',
                            'debit',
                            date('Y-m-d'),
                            $order->user->market->id,
                            $order->order_amount,
                            'order_id',
                            $order->id
                        );     
                    //Transaction

                    //Transaction
                        $this->addTransaction(
                            'online',
                            'credit',
                            date('Y-m-d'),
                            $order->user->market->id,
                            $order->order_amount,
                            'order_id',
                            $order->id
                        );
                    //Transaction

                    //Update Balance
                        $this->partyBalanceUpdate($order->user->market->id);
                    //Update Balance     

                    //Reward Details
                    if(isset($input['redeem']) && isset($input['redeem_points'])) {
                        //Add Rewards Usage
                            if($input['redeem_points'] > 0 && $input['redeem'] > 0) {
                                $this->addPointusage(
                                    $order->user->market->id,
                                    $input['redeem'],
                                    $input['redeem_points'],
                                    'order_id',
                                    $order->id,
                                    'online_order',
                                    'redeem'
                                );
                            }
                        //Add Rewards Usage
                    }
                    //Reward Details    

                    //Update Rewards
                        $this->addUpdatePurchaseRewards(
                            $order->user->market->id,
                            $order->order_amount,
                            'order_id',
                            $order->id,
                            'online_order',
                            'earn',
                            'add'
                        );
                    //Update Rewards

                    if($order) {
                        
                        //Notification
                            /*$notification_data = [
                                'greeting'    => 'Your Order '.$order->order_code.' has been successfully placed',
                                'body'        => 'Hi, '.$order->user->name.' Your order has been successfully placed.',
                                'order_code'  => "Order ID : ".$order->order_code,
                                'order_date'  => "Order placed on : ".$order->created_at->format('M d, Y'),
                                'order_items' => $order->productOrders,  
                                'thanks'      => 'Thank you',
                                'url'         => route('users.order',$order->id),
                                'datas'    => array(
                                    'title'   => 'Your Order '.$order->order_code.' has been successfully placed',
                                    'message' => 'Hi, '.$order->user->name.' Your order has been successfully placed.'
                                )
                            ];*/
                            //$order->user->notify(new NewOrderNotification($notification_data));
                        //Notification
                        
                        return $this->sendResponse($order->toArray(), 'Orders Placed Successfully');
                    }

                } else {
                    return $this->sendError('Payment Failed'); 
                }    

            //Create Order

        } else {
            return $this->sendError($validator->messages());
        }


    }

    /**
     * Update the specified Order in storage.
     *
     * @param int $id
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update($id, Request $request)
    {
        $oldOrder = $this->orderRepository->findWithoutFail($id);
        if (empty($oldOrder)) {
            return $this->sendError('Order not found');
        }
        $oldStatus = $oldOrder->payment->status;
        $input = $request->all();

        try {
            $order = $this->orderRepository->update($input, $id);
            if (isset($input['order_status_id']) && $input['order_status_id'] == 5 && !empty($order)) {
                $this->paymentRepository->update(['status' => 'Paid'], $order['payment_id']);
            }
            event(new OrderChangedEvent($oldStatus, $order));

            if (setting('enable_notifications', false)) {
                if (isset($input['order_status_id']) && $input['order_status_id'] != $oldOrder->order_status_id) {
                    Notification::send([$order->user], new StatusChangedOrder($order));
                }

                if (isset($input['driver_id']) && ($input['driver_id'] != $oldOrder['driver_id'])) {
                    $driver = $this->userRepository->findWithoutFail($input['driver_id']);
                    if (!empty($driver)) {
                        Notification::send([$driver], new AssignedOrder($order));
                    }
                }
            }

        } catch (ValidatorException $e) {
            return $this->sendError($e->getMessage());
        }

        return $this->sendResponse($order->toArray(), __('lang.saved_successfully', ['operator' => __('lang.order')]));
    }
    
       
    public function destroy($id, Request $request)
    {
         try {
            $order = $this->orderRepository->findWithoutFail($id);
            
            if (empty($order)) {
                return $this->sendError(__('lang.not_found', ['operator' => __('lang.order')]));
            }
            
            $input['updated_by'] = auth()->user()->id;
            $input['is_deleted'] = 1;
            $order = $this->orderRepository->update($input, $id);
            //$this->orderRepository->delete($id);

        } catch (ValidatorException $e) {
            return $this->sendError($e->getMessage());
        }
         return $this->sendResponse(1,'Order deleted successfully');
    }
    
    
    public function getCancelOrders(Request $request)
    {
         try {
            
            if($request->user_id!='')
            {
             $order = $order = $this->orderRepository->where('user_id',$request->user_id)->where('is_deleted',1)->get();
            }else
            {
             $order = $order = $this->orderRepository->where('is_deleted',1)->get();   
            }
            foreach($order as $key => $value) :
                    $order[$key]->product_orders = $this->productOrderRepository->where('order_id',$order[$key]->id)->get();
                    $order[$key]->user = DB::table('users')->where('id',$order[$key]->user_id)->first();
                    
                     foreach($order[$key]->product_orders as $k => $item) {
                        $product = $this->productRepository->where('id',$item->product_id)->first();
                        if($product) {
                            $product_image = $product->getMedia('image');
                        } else {
                            $product_image = ''; 
                        }
                        $order[$key]->product_orders[$k]->product_name = $product->name;
                        $order[$key]->product_orders[$k]->product_image = $product_image;
                    }
                    
            endforeach;        
            if (empty($order)) {
                return $this->sendError(__('lang.not_found', ['operator' => __('lang.order')]));
            }

        } catch (ValidatorException $e) {
            return $this->sendError($e->getMessage());
        }
         return $this->sendResponse($order,'Cancelled Order retrieved successfully');
    }
    
       
     public function repeatLastOrder(Request $request) {
      
        try {
            $this->validate($request, [
                'api_token' => 'required'
            ]);
            

           $user = $this->userRepository->findByField('api_token', $request->input('api_token'))->first();
           if (!$user) {
            return $this->sendError('User not found', 401);
           }
            $last_order = $this->orderRepository->where('user_id', $user->id)->orderBy('id', 'desc')->limit(1)->first();
            $last_order_id = $last_order->id;
            
            $last_payment = $this->paymentRepository->where('user_id', $user->id)->orderBy('id', 'desc')->limit(1)->first();
            $last_payment_id = $last_payment->id;
            
            $last_product_order = $this->productOrderRepository->where('order_id', $last_order_id)->orderBy('id', 'desc')->limit(1)->first();
            $last_product_order_id = $last_product_order->id;
            $product_order_quantity = $last_product_order->quantity;
            
            $last_inventory = $this->inventoryRepository->where('purchase_invoice_id', $last_product_order_id)->orderBy('id', 'desc')->limit(1)->first();
            $last_inventory_id = $last_inventory->id;
            
            $last_product = $this->productRepository->where('id',$last_product_order->product_id)->first();
            $product_stock = $last_product->stock;
           
            //for payment data copy
            $payment = $this->paymentRepository->where('id', $last_payment_id)->first();

            $newPayment = $payment->replicate();
            $newPayment->id = $payment->id+1; 
            $newPayment->save();
          
           //for order data copy
            $order = $this->orderRepository->where('id', $last_order_id)->first();

            $newOrder = $order->replicate();
            $newOrder->id = $max_id = $order->id+1; 
            $order_code_prefix = "GGPL" . date('y') . (date('y') + 1) . "ID";
            $order_code = null;
            if ($max_id <= 9) {
                $order_code = $order_code_prefix . "000000" . $max_id;
            } else if ($max_id <= 99) {
                $order_code = $order_code_prefix . "00000" . $max_id;
            } else if ($max_id <= 999) {
                $order_code = $order_code_prefix . "0000" . $max_id;
            } else if ($max_id <= 9999) {
                $order_code = $order_code_prefix . "000" . $max_id;
            } else if ($max_id <= 99999) {
                $order_code = $order_code_prefix . "00" . $max_id;
            } else if ($max_id <= 999999) {
                $order_code = $order_code_prefix . "0" .$max_id;
            } else {
                $order_code = $order_code_prefix . $max_id;
            }
            $newOrder->order_code = $order_code;
            $newOrder->payment_id = $payment->id+1; 
            $newOrder->redeem_amount = 0;
            $newOrder->coupon_amount = 0;
            $newOrder->save();
            
            //for product order data copy
            $product_order = $this->productOrderRepository->where('id',$last_product_order_id)->first();

            $newProductOrder = $product_order->replicate();
            $newProductOrder->id = $product_order->id+1; 
            $newProductOrder->order_id = $order->id+1; 
            $newProductOrder->save();
            
             //for inventory track data copy
            $inventory = $this->inventoryRepository->where('id', $last_inventory_id)->first();

            $newInventory = $inventory->replicate();
            $newInventory->id = $inventory->id+1; 
            $newInventory->purchase_invoice_id = $product_order->id+1; 
            $newInventory->inventory_track_date = date('Y-m-d'); 
            $newInventory->save();
             
            //for update product stock
            $remaining_stock = $product_stock - $product_order_quantity;
            $product = $this->productRepository->where('id',$last_product_order->product_id)->update(['stock' => $remaining_stock]);
            
            //Mail send
             $users = DB::table('users')->where('id',$user->id)->first();
             $customer_mail = $users->email;
             $customer_name = $users->name;

              $product_orders = DB::table('product_orders')->leftJoin('products', 'product_orders.product_id', '=', 'products.id')->where('order_id',$order->id+1)->get();
              $order_delivery_address = DB::table('delivery_addresses')->where('id',$order->delivery_address_id)->first();
             
             $details = ['title' => 'Order Placed Notification Mail','body' => 'Order has been placed.','product_orders' => $product_orders,'order' => $order,'order_delivery_address' => $order_delivery_address,'customer_name' =>  $customer_name];
            
            \Mail::to($customer_mail)->send(new OrdersMail($details));
          
            
        } catch (\Exception $e) {
            $this->sendError($e->getMessage(), 401);
        }
        return $this->sendResponse($newOrder, 'Order repeated successfully');
    }
    

    public function calculateDeliveryCharge(Request $request) {
        
        $distance_result   = $request->data['rows'][0]['elements'][0];
        $distance_in_meter = $distance_result['distance']['value'];
        $distance_duration = $distance_result['duration']['text'];
        $distance_in_km    = $distance_in_meter / 1000;
        //$delivery_charge   = $distance_in_km * 2; 
        $deliveryZones     = $this->deliveryZonesRepository->get();
        
        $delivery_charge   = 0;
        if(count($deliveryZones) > 0) {
            foreach ($deliveryZones as $key1 => $value) {
                if($distance_in_km >= $value->distance) {
                   $delivery_charge = $value->delivery_charge;
                }
            }                
        }
        if($distance_result) {
            if($distance_in_km > 0 && $distance_in_km <= 150) {
                
                $data    = array(
                            'delivery' => number_format($delivery_charge,'2','.',''),
                            'duration' => $distance_duration,
                            'distance' => $distance_in_km,
                        );
                $message = 'Delivery charge applied!';
                
            } else {
                
                $data    = 0;
                $message = 'Delivery Not Available on selected location. Please Select or Add the address below 150 KM'; 
                
            }
        } else {
            
            $data    = 0;
            $message = 'Delivery Not Available on selected location'; 

        }
        return response()->json(['status'=>'success', 'message'=>$message, 'data'=>$data]);
        
    }

}
