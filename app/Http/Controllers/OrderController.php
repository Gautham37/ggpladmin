<?php

namespace App\Http\Controllers;

use App\Criteria\Orders\OrdersOfUserCriteria;
use App\Criteria\Users\ClientsCriteria;
use App\Criteria\Users\DriversCriteria;
use App\Criteria\Users\DriversOfMarketCriteria;
use App\DataTables\OrderDataTable;
use App\DataTables\ProductOrderDataTable;
use App\Events\OrderChangedEvent;
use App\Http\Requests\CreateOrderRequest;
use App\Http\Requests\UpdateOrderRequest;
use App\Notifications\AssignedOrder;
use App\Notifications\StatusChangedOrder;
use App\Repositories\CustomFieldRepository;
use App\Repositories\NotificationRepository;
use App\Repositories\OrderRepository;
use App\Repositories\OrderStatusRepository;
use App\Repositories\PaymentRepository;
use App\Repositories\PaymentModeRepository;
use App\Repositories\UserRepository;
use App\Repositories\DriverRepository;
use Flash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Response;
use Prettus\Validator\Exceptions\ValidatorException;
use App\Mail\DeliveryMail;
use DB;
use PDF;
use Illuminate\Support\Facades\Input;

class OrderController extends Controller
{
    /** @var  OrderRepository */
    private $orderRepository;

    /**
     * @var CustomFieldRepository
     */
    private $customFieldRepository;

    /**
     * @var UserRepository
     */
    private $userRepository;
    /**
     * @var OrderStatusRepository
     */
    private $orderStatusRepository;
    /** @var  NotificationRepository */
    private $notificationRepository;
    /** @var  PaymentRepository */
    private $paymentRepository;
    /** @var  PaymentModeRepository */
    private $paymentModeRepository;
    /** @var  DriverRepository */
    private $driverRepository;

    public function __construct(OrderRepository $orderRepo, CustomFieldRepository $customFieldRepo, UserRepository $userRepo
        , OrderStatusRepository $orderStatusRepo, NotificationRepository $notificationRepo, PaymentRepository $paymentRepo, PaymentModeRepository $paymentModeRepo, DriverRepository $driverRepo)
    {
        parent::__construct();
        $this->orderRepository = $orderRepo;
        $this->customFieldRepository = $customFieldRepo;
        $this->userRepository = $userRepo;
        $this->orderStatusRepository = $orderStatusRepo;
        $this->notificationRepository = $notificationRepo;
        $this->paymentRepository = $paymentRepo;
        $this->paymentModeRepository = $paymentModeRepo;
        $this->driverRepository = $driverRepo;
    }

    /**
     * Display a listing of the Order.
     *
     * @param OrderDataTable $orderDataTable
     * @return Response
     */
    public function index(OrderDataTable $orderDataTable)
    {
        $payment_methods = $this->paymentModeRepository->pluck('name', 'name');
        $payment_methods->prepend('Select Payment Method',null);
        $orders = $orderDataTable;
        if(Request('start_date')!='' && Request('end_date')!='' ) {
           $orders->with('start_date',Request('start_date'))->with('end_date',Request('end_date'));  
        }
        if(Request('payment_method')!='') {
           $orders->with('payment_method',Request('payment_method')); 
        }
        return $orders->render('orders.index',compact('payment_methods'));
    }

    /**
     * Show the form for creating a new Order.
     *
     * @return Response
     */
    public function create()
    {
        $user = $this->userRepository->getByCriteria(new ClientsCriteria())->pluck('name', 'id');
        $driver = $this->userRepository->getByCriteria(new DriversCriteria())->pluck('name', 'id');

        $orderStatus = $this->orderStatusRepository->pluck('status', 'id');

        $hasCustomField = in_array($this->orderRepository->model(), setting('custom_field_models', []));
        if ($hasCustomField) {
            $customFields = $this->customFieldRepository->findByField('custom_field_model', $this->orderRepository->model());
            $html = generateCustomField($customFields);
        }
        return view('orders.create')->with("customFields", isset($html) ? $html : false)->with("user", $user)->with("driver", $driver)->with("orderStatus", $orderStatus);
    }

    /**
     * Store a newly created Order in storage.
     *
     * @param CreateOrderRequest $request
     *
     * @return Response
     */
    public function store(CreateOrderRequest $request)
    {
        $input = $request->all();
        $customFields = $this->customFieldRepository->findByField('custom_field_model', $this->orderRepository->model());
        try {
            $input['created_by']  = auth()->user()->id;
            $order = $this->orderRepository->create($input);
            $order->customFieldsValues()->createMany(getCustomFieldsValues($customFields, $request));

        } catch (ValidatorException $e) {
            Flash::error($e->getMessage());
        }

        Flash::success(__('lang.saved_successfully', ['operator' => __('lang.order')]));

        return redirect(route('orders.index'));
    }

    /**
     * Display the specified Order.
     *
     * @param int $id
     * @param ProductOrderDataTable $productOrderDataTable
     *
     * @return Response
     * @throws \Prettus\Repository\Exceptions\RepositoryException
     */

    public function show(Request $request, $id)
    {

        $order = $this->orderRepository->findWithoutFail($id);
        if (empty($order)) {
            Flash::error('Order not found');
            return redirect(route('orders.index'));
        }
        return view('orders.show',compact('order'));
    }

    /**
     * Show the form for editing the specified Order.
     *
     * @param int $id
     *
     * @return Response
     * @throws \Prettus\Repository\Exceptions\RepositoryException
     */
    public function edit($id)
    {
        $this->orderRepository->pushCriteria(new OrdersOfUserCriteria(auth()->id()));
        $order = $this->orderRepository->findWithoutFail($id);
        if (empty($order)) {
            Flash::error(__('lang.not_found', ['operator' => __('lang.order')]));

            return redirect(route('orders.index'));
        }

        $market = $order->productOrders()->first();
        $market = isset($market) ? $market->product['market_id'] : 0;

        $user = $this->userRepository->getByCriteria(new ClientsCriteria())->pluck('name', 'id');
        //$driver = $this->userRepository->getByCriteria(new DriversOfMarketCriteria($market))->pluck('name', 'id');
        $driver = $this->userRepository->getByCriteria(new DriversCriteria($market))->pluck('name', 'id');
        $orderStatus = $this->orderStatusRepository->pluck('status', 'id');


        $customFieldsValues = $order->customFieldsValues()->with('customField')->get();
        $customFields = $this->customFieldRepository->findByField('custom_field_model', $this->orderRepository->model());
        $hasCustomField = in_array($this->orderRepository->model(), setting('custom_field_models', []));
        if ($hasCustomField) {
            $html = generateCustomField($customFields, $customFieldsValues);
        }

        return view('orders.edit')->with('order', $order)->with("customFields", isset($html) ? $html : false)->with("user", $user)->with("driver", $driver)->with("orderStatus", $orderStatus);
    }

    /**
     * Update the specified Order in storage.
     *
     * @param int $id
     * @param UpdateOrderRequest $request
     *
     * @return Response
     * @throws \Prettus\Repository\Exceptions\RepositoryException
     */
    public function update($id, UpdateOrderRequest $request)
    {
       $get_previous_order_val = DB::table('orders')->select('order_status_id','user_id')->where('id',$id)->first(); 
       $prev_order_status      = $get_previous_order_val->order_status_id;
       $prev_order_user        = $get_previous_order_val->user_id;

       $input = $request->all();

       $order_status_id = $input['order_status_id'];

       if($order_status_id!=$prev_order_status) {

          $delivery_process = DB::table('order_statuses')->select('status')->where('id',$order_status_id)->first(); 
          $delivery_status = $delivery_process->status;
          $get_order = DB::table('users')
                    ->select('email','name')
                    ->where('id',$prev_order_user)
                    ->get();
          foreach($get_order as $val) {
            $customer_mail = $val->email;
            $customer_name = $val->name;
            $details = ['title' => 'Delivery Notification Mail','body' => 'Delivery Status','customer_name' =>$customer_name,'delivery_status' =>$delivery_status];
            \Mail::to($customer_mail)->send(new DeliveryMail($details));
          }
          
        }

        
        $this->orderRepository->pushCriteria(new OrdersOfUserCriteria(auth()->id()));
        $oldOrder = $this->orderRepository->findWithoutFail($id);
        if (empty($oldOrder)) {
            Flash::error(__('lang.not_found', ['operator' => __('lang.order')]));
            return redirect(route('orders.index'));
        }
        $oldStatus = $oldOrder->payment->status;
        $input = $request->all();
        $old_delivery_fee = $oldOrder->delivery_fee ;
        $order_amount = $oldOrder->order_amount;
        $input['order_amount'] = $order_amount - $old_delivery_fee + $request->delivery_fee;
        
        $customFields = $this->customFieldRepository->findByField('custom_field_model', $this->orderRepository->model());
        try {
            $input['updated_by'] = auth()->user()->id;
            $order = $this->orderRepository->update($input, $id);

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

            $this->paymentRepository->update([
                "status" => $input['status'],
                'updated_by' => auth()->user()->id
            ], $order['payment_id']);
            //dd($input['status']);

            event(new OrderChangedEvent($oldStatus, $order));

            foreach (getCustomFieldsValues($customFields, $request) as $value) {
                $order->customFieldsValues()
                    ->updateOrCreate(['custom_field_id' => $value['custom_field_id']], $value);
            }
        } catch (ValidatorException $e) {
            Flash::error($e->getMessage());
        }

        Flash::success(__('lang.updated_successfully', ['operator' => __('lang.order')]));

        return redirect(route('orders.index'));
    }

    /**
     * Remove the specified Order from storage.
     *
     * @param int $id
     *
     * @return Response
     * @throws \Prettus\Repository\Exceptions\RepositoryException
     */
    public function destroy($id)
    {
        if (!env('APP_DEMO', false)) {
            $this->orderRepository->pushCriteria(new OrdersOfUserCriteria(auth()->id()));
            $order = $this->orderRepository->findWithoutFail($id);

            if (empty($order)) {
                Flash::error(__('lang.not_found', ['operator' => __('lang.order')]));

                return redirect(route('orders.index'));
            }
            
             $input['updated_by'] = auth()->user()->id;
             $input['is_deleted'] = 1;
             $order = $this->orderRepository->update($input, $id);
            //$this->orderRepository->delete($id);

            Flash::success(__('lang.deleted_successfully', ['operator' => __('lang.order')]));


        } else {
            Flash::warning('This is only demo app you can\'t change this section ');
        }
        return redirect(route('orders.index'));
    }

    /**
     * Remove Media of Order
     * @param Request $request
     */
    public function removeMedia(Request $request)
    {
        $input = $request->all();
        $order = $this->orderRepository->findWithoutFail($input['id']);
        try {
            if ($order->hasMedia($input['collection'])) {
                $order->getFirstMedia($input['collection'])->delete();
            }
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }
    }
    
    public function print($id,$type,$view_type,Request $request)
    {   
        $order = $this->orderRepository->with('user')->with('productOrders')->findWithoutFail($id);
        if (empty($order)) {
            Flash::error('Order not found');
            return redirect(route('orders.index'));
        }
        $words    = $this->amounttoWords($order->order_amount);
        $currency = '<span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span>';
        $pdf      = PDF::loadView('orders.print', compact('order','type','currency','words'));
        $filename = $order->order_code.'-'.$order->user->market->name.'.pdf';
        
        if($view_type=='print') {
            return $pdf->stream($filename);
        } elseif($view_type=='download') {
            return $pdf->download($filename);
        } else {
            return view('orders.thermal_print', compact('order','type','currency','words'));
        }
    }
     
}
