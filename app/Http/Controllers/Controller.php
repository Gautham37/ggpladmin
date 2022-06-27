<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
//use InfyOm\Generator\Utils\ResponseUtil;
use Illuminate\Support\Facades\Response;
use App\Models\PartySubTypes;
use App\Models\User;
use App\Models\Market;
use App\Models\InventoryTrack;
use App\Models\Product;
use App\Models\TransactionTrack;
use App\Models\SalesInvoice;
use App\Models\SalesReturn;
use App\Models\Order;
use App\Models\CustomerLevels;
use App\Models\LoyalityPoints;
use App\Models\LoyalityPointUsage;
use App\Models\PurchaseInvoice;
use Carbon\Carbon;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    public function __construct(){
        // config(['app.timezone' => setting('timezone')]);
    }

    /**
     * @param $result
     * @param $message
     * @return mixed
     */
    public function sendResponse($result, $message)
    {   
        //return Response::json(ResponseUtil::makeResponse($message, $result));
        return response()->json(['status' => 'success', 'data'=>$result, 'message'=>$message]);
    }

    /**
     * @param $error
     * @param int $code
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendError($error, $code = 404)
    {
        //return Response::json(ResponseUtil::makeError($error), $code);
        return response()->json(['error' => $code, 'message'=>$error]);
    }

    public function partyPrefixGen($party_type, $party_sub_type) 
    {
        //get prefix value
        $sub_type       = PartySubTypes::where('id',$party_sub_type)->where('party_type_id',$party_type)->first();
        $prefix_value   = ($sub_type) ? $sub_type->prefix_value : '' ;
        return $prefix_value;
    }

    public function partyCodeGen($party_type, $party_sub_type) 
    {
        $get_party       = Market::where('sub_type',$party_sub_type)->orderBy('id', 'desc')->get();
        $get_party_count = count($get_party);   
        if($get_party_count > 0) {
            $code_count = $get_party[0]->code_count+1;
        } else {
            $code_count = 1; 
        }
        return $code_count;
    }

    public function addInventory($product_id,$market_id,$category,$type,$quantity,$unit,$description,$column_name,$column_id) {
        $inventory = InventoryTrack::create([
            'product_id' => $product_id,
            'market_id'  => $market_id,
            'category'   => $category,
            'type'       => $type,
            'date'       => date('Y-m-d H:i:s'),
            'quantity'   => $quantity,
            'unit'       => $unit,
            'description'=> $description,
            $column_name => $column_id,
            'created_by' => auth()->user()->id
        ]);
        return $inventory;  
    }

    public function updateInventory($product_id,$market_id,$category,$type,$quantity,$unit,$description,$column_name,$column_id) {
        $inventory = InventoryTrack::where($column_name,$column_id)->update([
            'product_id' => $product_id,
            'market_id'  => $market_id,
            'category'   => $category,
            'type'       => $type,
            'date'       => date('Y-m-d H:i:s'),
            'quantity'   => $quantity,
            'unit'       => $unit,
            'description'=> $description,
            'updated_by' => auth()->user()->id
        ]);
        return $inventory;  
    }

    public function productStockupdate($product_id) {
        
        $add = [];
        $reduce = [];
        $product      = Product::where('id',$product_id)->first();



        if($product->primaryunit) {
            $primary_add  = InventoryTrack::where('product_id',$product_id)
                                          ->where('unit',$product->unit)->where('type','add')->sum('quantity');
            $add[] = $primary_add;
            $primary_reduce     = InventoryTrack::where('product_id',$product_id)
                                                ->where('unit',$product->unit)->where('type','reduce')->sum('quantity');
            $reduce[] = $primary_reduce;
        }

        if($product->secondaryunit && $product->secondary_unit_quantity > 0) { 
            
            $secondary_add    = InventoryTrack::where('product_id',$product_id)
                                              ->where('unit',$product->secondary_unit)->where('type','add')->sum('quantity');
            $add[]            = $secondary_add * $product->secondary_unit_quantity;

            $secondary_reduce = InventoryTrack::where('product_id',$product_id)
                                              ->where('unit',$product->secondary_unit)->where('type','reduce')->sum('quantity');
            $reduce[]         = $secondary_reduce * $product->secondary_unit_quantity;

        }

        if($product->tertiaryunit && $product->tertiary_unit_quantity > 0) { 
            
            $tertiary_add     = InventoryTrack::where('product_id',$product_id)
                                              ->where('unit',$product->tertiary_unit)->where('type','add')->sum('quantity');
            $add[]            = $tertiary_add * $product->tertiary_unit_quantity;

            $tertiary_reduce  = InventoryTrack::where('product_id',$product_id)
                                              ->where('unit',$product->tertiary_unit)->where('type','reduce')->sum('quantity');
            $reduce[]         = $tertiary_reduce * $product->tertiary_unit_quantity;

        }

        if($product->customunit  && $product->custom_unit_quantity > 0) { 
            
            $custom_add     = InventoryTrack::where('product_id',$product_id)
                                              ->where('unit',$product->custom_unit)->where('type','add')->sum('quantity');
            $add[]          = $custom_add * $product->custom_unit_quantity;

            $custom_reduce  = InventoryTrack::where('product_id',$product_id)
                                              ->where('unit',$product->custom_unit)->where('type','reduce')->sum('quantity');
            $reduce[]       = $custom_reduce * $product->custom_unit_quantity;

        }

        if($product->bulkbuyunit && $product->bulk_buy_unit_quantity > 0) { 
            
            $bulkbuy_add    = InventoryTrack::where('product_id',$product_id)
                                              ->where('unit',$product->bulk_buy_unit)->where('type','add')->sum('quantity');
            $add[]          = $bulkbuy_add * $product->bulk_buy_unit_quantity;

            $bulkbuy_reduce = InventoryTrack::where('product_id',$product_id)
                                              ->where('unit',$product->bulk_buy_unit)->where('type','reduce')->sum('quantity');
            $reduce[]       = $bulkbuy_reduce * $product->bulk_buy_unit_quantity;

        }

        if($product->wholesalepurchaseunit  && $product->wholesale_purchase_unit_quantity > 0) { 
            
            $wholesalepurchase_add    = InventoryTrack::where('product_id',$product_id)
                                              ->where('unit',$product->wholesale_purchase_unit)->where('type','add')->sum('quantity');
            $add[]                    = $wholesalepurchase_add * $product->wholesale_purchase_unit_quantity;

            $wholesalepurchase_reduce = InventoryTrack::where('product_id',$product_id)
                                              ->where('unit',$product->wholesale_purchase_unit)->where('type','reduce')->sum('quantity');
            $reduce[]                 = $wholesalepurchase_reduce * $product->wholesale_purchase_unit_quantity;

        }

        if($product->packweightunit  && $product->pack_weight_unit_quantity > 0) { 
            
            $packweight_add    = InventoryTrack::where('product_id',$product_id)
                                              ->where('unit',$product->pack_weight_unit)->where('type','add')->sum('quantity');
            $add[]             = $wholesalepurchase_add * $product->pack_weight_unit_quantity;

            $packweight_reduce = InventoryTrack::where('product_id',$product_id)
                                              ->where('unit',$product->pack_weight_unit)->where('type','reduce')->sum('quantity');
            $reduce[]          = $packweight_reduce * $product->pack_weight_unit_quantity;

        }

        $available  = number_format((array_sum($add) - array_sum($reduce)),3,'.','');
        $product    = Product::where('id',$product_id)->update(['stock'=>$available]);
        return $product;
    }


    public function addTransaction($category,$type,$date,$market_id,$amount,$column_name,$column_id) {
        $transaction = TransactionTrack::create([
            'category'   => $category,
            'type'       => $type,
            'date'       => date('Y-m-d H:i:s'),
            'market_id'  => $market_id,
            'amount'     => $amount,
            $column_name => $column_id,
            'created_by' => auth()->user()->id
        ]);
        return $transaction;  
    }

    public function updateTransaction($category,$type,$date,$market_id,$amount,$column_name,$column_id) {
        $transaction = TransactionTrack::where($column_name,$column_id)->where('type',$type)->update([
            'category'   => $category,
            'type'       => $type,
            'date'       => date('Y-m-d H:i:s'),
            'market_id'  => $market_id,
            'amount'     => $amount,
            'updated_by' => auth()->user()->id
        ]);
        return $transaction;
    }

    public function partyBalanceUpdate($market_id) {
        $total_credit = TransactionTrack::where('market_id',$market_id)->where('type','credit')->sum('amount');
        $total_debit  = TransactionTrack::where('market_id',$market_id)->where('type','debit')->sum('amount');
        $balance      = $total_credit - $total_debit;
        $market       = Market::where('id',$market_id)->update(['balance' => $balance]);
        return $market;
    }

    public function addUpdatePurchaseRewards($market_id,$total,$column_name,$column_id,$category,$type,$method) {

        $market        = Market::where('id',$market_id)->first();

        $online_order  = Order::where('user_id',$market->user->id)->whereMonth('created_at', date('m'))->whereYear('created_at', date('Y'))->sum('order_amount');
        $sales         = SalesInvoice::where('market_id',$market_id)->whereMonth('date', date('m'))->whereYear('date', date('Y'))->sum('total');
        $sales_return  = SalesReturn::where('market_id',$market_id)->whereMonth('date', date('m'))->whereYear('date', date('Y'))->sum('total');

        $total_spend   = ($online_order + $sales) - $sales_return;

        if($total_spend > 0) {

            $customer_levels  = CustomerLevels::orderBy('monthly_spend_from','asc')->get();
            if(count($customer_levels) > 0) {
                foreach ($customer_levels as $customer_level) {
                    if($total_spend >= $customer_level->monthly_spend_from && $total_spend <= $customer_level->monthly_spend_to) { 
                        
                        $reward = [
                            'affiliate_id'  => $market->user->affiliate_id,
                            'category'      => $category,
                            'type'          => $type,
                            'points'        => $total * $customer_level->group_points,
                            'amount'        => $total * $customer_level->group_points / 100,  
                            $column_name    => $column_id,
                            'created_by'    => auth()->user()->id,
                            'created_at'    => date('Y-m-d H:i:s')
                        ];
                        if($method=='add') {
                            LoyalityPoints::create($reward);
                        } elseif($method=='update') {
                            LoyalityPoints::where($column_name,$column_id)->where('type',$type)->update($reward);
                        }
                        $market = Market::where('id',$market->id)->update(['customer_level_id'=>$customer_level->id]);
                        
                    }
                }
            }
        }
        $this->calculateRewardBalance($market_id);
        return $market;
    }

    public function addPointusage($market_id,$used_point_worth,$redeem_points,$column_name,$column_id,$category,$type) {
        $market  = Market::where('id',$market_id)->first();
        $usage   = LoyalityPoints::create([
            'affiliate_id'  => $market->user->affiliate_id,
            'category'      => $category,
            'type'          => $type,
            'points'        => $redeem_points,
            'amount'        => $used_point_worth,
            $column_name    => $column_id,
            'created_by'    => auth()->user()->id,
            'created_at'    => date('Y-m-d H:i:s')
        ]);
        $this->calculateRewardBalance($market->id);
        return $usage;
    }

    public function calculateRewardBalance($market_id) {
        $market       = Market::where('id',$market_id)->first();

        $total_points = LoyalityPoints::where('affiliate_id',$market->user->affiliate_id)->where('type','earn')->sum('points');
        $point_usage  = LoyalityPoints::where('affiliate_id',$market->user->affiliate_id)->where('type','redeem')->sum('points');
        
        $balance      = $total_points - $point_usage;
        $market       = User::where('id',$market->user->id)->update(['points'=>$balance]);
        return $market;
    }

    public function openingStockCalc($product_id,$start_date) {
        $product = Product::where('id',$product_id)->first();
        if($product) {
                
            if($product->primaryunit) {
                $primary_add    = InventoryTrack::where('product_id',$product->id)
                                    ->whereDate('date','<',Carbon::parse($start_date)->format('Y-m-d'))
                                    ->where('unit',$product->unit)->where('type','add')->sum('quantity');
                $primary_reduce = InventoryTrack::where('product_id',$product->id)
                                    ->whereDate('date','<',Carbon::parse($start_date)->format('Y-m-d'))
                                    ->where('unit',$product->unit)->where('type','reduce')->sum('quantity');
                $add[]          = $primary_add;
                $reduce[]       = $primary_reduce;
            }

            if($product->secondaryunit && $product->secondary_unit_quantity > 0) { 
                
                $secondary_add    = InventoryTrack::where('product_id',$product_id)
                                        ->whereDate('date','<',Carbon::parse($start_date)->format('Y-m-d'))
                                        ->where('unit',$product->secondary_unit)->where('type','add')->sum('quantity');

                $secondary_reduce = InventoryTrack::where('product_id',$product_id)
                                        ->whereDate('date','<',Carbon::parse($start_date)->format('Y-m-d'))
                                        ->where('unit',$product->secondary_unit)->where('type','reduce')->sum('quantity');
                $add[]            = $secondary_add * $product->secondary_unit_quantity;
                $reduce[]         = $secondary_reduce * $product->secondary_unit_quantity;
            }

            if($product->tertiaryunit && $product->tertiary_unit_quantity > 0) { 
                
                $tertiary_add     = InventoryTrack::where('product_id',$product_id)
                                        ->whereDate('date','<',Carbon::parse($start_date)->format('Y-m-d'))
                                        ->where('unit',$product->tertiary_unit)->where('type','add')->sum('quantity');
                $tertiary_reduce  = InventoryTrack::where('product_id',$product_id)
                                        ->whereDate('date','<',Carbon::parse($start_date)->format('Y-m-d'))
                                        ->where('unit',$product->tertiary_unit)->where('type','reduce')->sum('quantity');
                $add[]            = $tertiary_add * $product->tertiary_unit_quantity;                                            
                $reduce[]         = $tertiary_reduce * $product->tertiary_unit_quantity;
            }

            if($product->customunit && $product->custom_unit_quantity > 0) { 
                
                $custom_add     =   InventoryTrack::where('product_id',$product_id)
                                        ->whereDate('date','<',Carbon::parse($start_date)->format('Y-m-d'))
                                        ->where('unit',$product->custom_unit)->where('type','add')->sum('quantity');
                $custom_reduce  =   InventoryTrack::where('product_id',$product_id)
                                        ->whereDate('date','<',Carbon::parse($start_date)->format('Y-m-d'))
                                        ->where('unit',$product->custom_unit)->where('type','reduce')->sum('quantity');
                $add[]          = $custom_add * $product->custom_unit_quantity;                                            
                $reduce[]       = $custom_reduce * $product->custom_unit_quantity;
            }

            if($product->bulkbuyunit && $product->bulk_buy_unit_quantity > 0) { 
                
                $bulkbuy_add    =   InventoryTrack::where('product_id',$product_id)
                                        ->whereDate('date','<',Carbon::parse($start_date)->format('Y-m-d'))
                                        ->where('unit',$product->bulk_buy_unit)->where('type','add')->sum('quantity');
                $bulkbuy_reduce =   InventoryTrack::where('product_id',$product_id)
                                        ->whereDate('date','<',Carbon::parse($start_date)->format('Y-m-d'))
                                        ->where('unit',$product->bulk_buy_unit)->where('type','reduce')->sum('quantity');
                $add[]          = $bulkbuy_add * $product->bulk_buy_unit_quantity;                                            
                $reduce[]       = $bulkbuy_reduce * $product->bulk_buy_unit_quantity;
            }

            if($product->wholesalepurchaseunit && $product->wholesale_purchase_unit_quantity > 0) { 
                
                $wholesalepurchase_add      =   InventoryTrack::where('product_id',$product_id)
                                                ->whereDate('date','<',Carbon::parse($start_date)->format('Y-m-d'))        
                                                ->where('unit',$product->wholesale_purchase_unit)->where('type','add')->sum('quantity');
                $wholesalepurchase_reduce   =   InventoryTrack::where('product_id',$product_id)
                                                ->whereDate('date','<',Carbon::parse($start_date)->format('Y-m-d'))
                                                ->where('unit',$product->wholesale_purchase_unit)->where('type','reduce')->sum('quantity');
                $add[]                    = $wholesalepurchase_add * $product->wholesale_purchase_unit_quantity; 
                $reduce[]                 = $wholesalepurchase_reduce * $product->wholesale_purchase_unit_quantity;

            }

            if($product->packweightunit && $product->pack_weight_unit_quantity > 0) { 
                
                $packweight_add     =   InventoryTrack::where('product_id',$product_id)
                                            ->whereDate('date','<',Carbon::parse($start_date)->format('Y-m-d'))
                                            ->where('unit',$product->pack_weight_unit)->where('type','add')->sum('quantity');
                $packweight_reduce  =   InventoryTrack::where('product_id',$product_id)
                                            ->whereDate('date','<',Carbon::parse($start_date)->format('Y-m-d'))
                                            ->where('unit',$product->pack_weight_unit)->where('type','reduce')->sum('quantity');
                $add[]             = $wholesalepurchase_add * $product->pack_weight_unit_quantity;
                $reduce[]          = $packweight_reduce * $product->pack_weight_unit_quantity;
            }    

            return number_format((array_sum($add) - array_sum($reduce)),3,'.','');                                

        } else {
            return 0;
        }
    }


    public function closingStockCalc($opening_stock,$product_id,$unit,$quantity,$type) {
        $product = Product::where('id',$product_id)->first();
        if($product) {

            if($product->primaryunit->id == $unit) {
                $stock = ($type == 'add') ?  $opening_stock + $quantity : $opening_stock - $quantity ;
            }

            elseif($product->secondaryunit && $product->secondary_unit_quantity > 0 && $product->secondaryunit->id == $unit) { 
                $stock = ($type == 'add') ?  $opening_stock + ($quantity * $product->secondary_unit_quantity) : $opening_stock - ($quantity * $product->secondary_unit_quantity) ;
            }

            elseif($product->tertiaryunit && $product->tertiary_unit_quantity > 0 && $product->tertiaryunit->id == $unit) { 
                $stock = ($type == 'add') ?  $opening_stock + ($quantity * $product->tertiary_unit_quantity) : $opening_stock - ($quantity * $product->tertiary_unit_quantity) ;
            }

            elseif($product->customunit && $product->custom_unit_quantity > 0 && $product->customunit->id == $unit) { 
                $stock = ($type == 'add') ?  $opening_stock + ($quantity * $product->custom_unit_quantity) : $opening_stock - ($quantity * $product->custom_unit_quantity) ;
            }

            elseif($product->bulkbuyunit && $product->bulk_buy_unit_quantity > 0 && $product->bulkbuyunit->id == $unit) { 
                $stock = ($type == 'add') ?  $opening_stock + ($quantity * $product->bulk_buy_unit_quantity) : $opening_stock - ($quantity * $product->bulk_buy_unit_quantity) ;
            }

            elseif($product->wholesalepurchaseunit && $product->wholesale_purchase_unit_quantity > 0 && $product->wholesalepurchaseunit == $unit) { 
               $stock = ($type == 'add') ?  $opening_stock + ($quantity * $product->wholesale_purchase_unit_quantity) : $opening_stock - ($quantity * $product->wholesale_purchase_unit_quantity) ;
            }

            elseif($product->packweightunit && $product->pack_weight_unit_quantity > 0 && $product->packweightunit == $unit) { 
                $stock = ($type == 'add') ?  $opening_stock + ($quantity * $product->pack_weight_unit_quantity) : $opening_stock - ($quantity * $product->pack_weight_unit_quantity) ;
            }    

            return number_format($stock,3,'.','');                                

        } else {
            return 0;
        }
    }

    public function partyPurchaseQuantity($product_id,$market_id,$start_date,$end_date,$type,$category_1,$category_2) {
        $product = Product::where('id',$product_id)->first();
        $stock   = [];
        if($product) {

            if($product->primaryunit) {
                $stock[] =  InventoryTrack::where('product_id',$product->id)->where('market_id',$market_id)->where('type',$type)->where('unit',$product->unit)
                                ->whereDate('date','>=',Carbon::parse($start_date)->format('Y-m-d'))->whereDate('date','<=',Carbon::parse($end_date)->format('Y-m-d'))
                                ->where('category',$category_1)->orWhere('category', $category_2)
                                ->sum('quantity'); 
            }

            if($product->secondaryunit && $product->secondary_unit_quantity > 0) { 
                $stock[] =  InventoryTrack::where('product_id',$product->id)->where('market_id',$market_id)->where('type',$type)
                                ->where('unit',$product->secondaryunit->id)
                                ->whereDate('date','>=',Carbon::parse($start_date)->format('Y-m-d'))->whereDate('date','<=',Carbon::parse($end_date)->format('Y-m-d'))
                                ->where('category',$category_1)->orWhere('category',$category_2)
                                ->sum('quantity') * $product->secondary_unit_quantity;
            }

            if($product->tertiaryunit && $product->tertiary_unit_quantity > 0) { 
                $stock[] =  InventoryTrack::where('product_id',$product->id)->where('market_id',$market_id)->where('type',$type)
                                ->where('unit',$product->tertiaryunit->id)
                                ->whereDate('date','>=',Carbon::parse($start_date)->format('Y-m-d'))->whereDate('date','<=',Carbon::parse($end_date)->format('Y-m-d'))
                                ->where('category',$category_1)->orWhere('category',$category_2)
                                ->sum('quantity') * $product->tertiary_unit_quantity;
            }

            if($product->customunit && $product->custom_unit_quantity > 0) { 
                $stock[] =  InventoryTrack::where('product_id',$product->id)->where('market_id',$market_id)->where('type',$type)
                                ->where('unit',$product->customunit->id)
                                ->whereDate('date','>=',Carbon::parse($start_date)->format('Y-m-d'))->whereDate('date','<=',Carbon::parse($end_date)->format('Y-m-d'))
                                ->where('category',$category_1)->orWhere('category', $category_2)
                                ->sum('quantity') * $product->custom_unit_quantity;
            }

            if($product->bulkbuyunit && $product->bulk_buy_unit_quantity > 0) { 
                $stock[] =  InventoryTrack::where('product_id',$product->id)->where('market_id',$market_id)->where('type',$type)
                                ->where('unit',$product->bulkbuyunit->id)
                                ->whereDate('date','>=',Carbon::parse($start_date)->format('Y-m-d'))->whereDate('date','<=',Carbon::parse($end_date)->format('Y-m-d'))
                                ->where('category',$category_1)->orWhere('category',$category_2)
                                ->sum('quantity') * $product->bulk_buy_unit_quantity;
            }

            if($product->wholesalepurchaseunit && $product->wholesale_purchase_unit_quantity > 0) { 
                $stock[] =  InventoryTrack::where('product_id',$product->id)->where('market_id',$market_id)->where('type',$type)
                                ->where('unit',$product->wholesalepurchaseunit->id)
                                ->whereDate('date','>=',Carbon::parse($start_date)->format('Y-m-d'))->whereDate('date','<=',Carbon::parse($end_date)->format('Y-m-d'))
                                ->where('category',$category_1)->orWhere('category',$category_2)
                                ->sum('quantity') * $product->wholesale_purchase_unit_quantity;
            }

            if($product->packweightunit && $product->pack_weight_unit_quantity > 0) { 
                $stock[] =  InventoryTrack::where('product_id',$product->id)->where('market_id',$market_id)->where('type',$type)
                                ->where('unit',$product->packweightunit->id)
                                ->whereDate('date','>=',Carbon::parse($start_date)->format('Y-m-d'))->whereDate('date','<=',Carbon::parse($end_date)->format('Y-m-d'))
                                ->where('category',$category_1)->orWhere('category',$category_2)
                                ->sum('quantity') * $product->pack_weight_unit_quantity;
            }    

            return number_format(array_sum($stock),3,'.','');                                

        } else {
            return 0;
        }
    }

    public function partyPurchaseAmount($product_id,$market_id,$start_date,$end_date,$category) {
        $product = Product::where('id',$product_id)->first();
        $market  = Market::where('id',$market_id)->first();
        if($product) {
            if($category=='sales') {

                $sales = SalesInvoice::join('sales_invoice_items','sales_invoice.id','=','sales_invoice_items.sales_invoice_id')
                                     ->where('market_id',$market_id)
                                     ->where('product_id',$product_id)
                                     ->whereDate('date','>=',Carbon::parse($start_date)->format('Y-m-d'))->whereDate('date','<=',Carbon::parse($end_date)->format('Y-m-d'))
                                     ->sum('amount');

                $online = Order::join('product_orders','orders.id','=','product_orders.order_id')
                                     ->where('user_id',$market->user->id)
                                     ->where('product_id',$product_id)
                                     ->whereDate('orders.created_at','>=',Carbon::parse($start_date)->format('Y-m-d'))->whereDate('orders.created_at','<=',Carbon::parse($end_date)->format('Y-m-d'))
                                     ->sum('amount');

                return number_format(($sales + $online),2,'.','');                              

            } else {

                $purchase = PurchaseInvoice::join('purchase_invoice_items','purchase_invoice.id','=','purchase_invoice_items.purchase_invoice_id')
                                     ->where('market_id',$market_id)
                                     ->where('product_id',$product_id)
                                     ->whereDate('date','>=',Carbon::parse($start_date)->format('Y-m-d'))->whereDate('date','<=',Carbon::parse($end_date)->format('Y-m-d'))
                                     ->sum('amount');
                return number_format($purchase,2,'.','');

            }
        } else {
            return 0;
        }
    }


    public function openingBalanceCalc($market_id,$start_date) {
        $market = Market::where('id',$market_id)->first();
        if($market) {
                
            $credit = TransactionTrack::where('market_id',$market_id)->whereDate('date','<',Carbon::parse($start_date)->format('Y-m-d'))
                                      ->where('type','credit')->sum('amount');
            $debit  = TransactionTrack::where('market_id',$market_id)->whereDate('date','<',Carbon::parse($start_date)->format('Y-m-d'))
                                      ->where('type','debit')->sum('amount');
            $balance = $credit - $debit;                                                                                  
            return number_format($balance,2,'.','');                                

        } else {
            return 0;
        }
    }

    public function closingBalanceCalc($opening_balance,$market_id,$type,$amount) {
        $market = Market::where('id',$market_id)->first();
        if($market) {

            $balance = ($type == 'credit') ?  $opening_balance + $amount : $opening_balance - $amount ;
            return number_format($balance,2,'.','');

        } else {
            return 0;
        }
    }

    public function get7DaysDates($days, $format = 'd/m'){
        $m = date("m"); $de= date("d"); $y= date("Y");
        $dateArray = array();
        for($i=0; $i<=$days-1; $i++){
            $dateArray[] = date($format, mktime(0,0,0,$m,($de-$i),$y)); 
        }
        return array_reverse($dateArray);
    }


    public function amounttoWords($total) {
        $number     = $total;
        $no         = floor($number);
        $point      = round($number - $no, 2) * 100;
        $hundred    = null;
        $digits_1   = strlen($no);
        $i = 0;
        $str = array();
        $words = array(
            '0' => '',
            '1' => 'One', 
            '2' => 'Two',
            '3' => 'Three', 
            '4' => 'Four', 
            '5' => 'Five', 
            '6' => 'Six',
            '7' => 'Seven', 
            '8' => 'Eight', 
            '9' => 'Nine',
            '10' => 'Ten', 
            '11' => 'Eleven', 
            '12' => 'Twelve',
            '13' => 'Thirteen', 
            '14' => 'Fourteen',
            '15' => 'Fifteen', 
            '16' => 'Sixteen', 
            '17' => 'Seventeen',
            '18' => 'Eighteen', 
            '19' =>'Nineteen', 
            '20' => 'Twenty',
            '30' => 'Thirty', 
            '40' => 'Forty', 
            '50' => 'Fifty',
            '60' => 'Sixty', 
            '70' => 'Seventy',
            '80' => 'Eighty', 
            '90' => 'Ninety'
        );
        $digits = array('', 'Hundred', 'Thousand', 'Lakh', 'Crore');
        while ($i < $digits_1) {
           $divider = ($i == 2) ? 10 : 100;
           $number = floor($no % $divider);
           $no = floor($no / $divider);
           $i += ($divider == 10) ? 1 : 2;
           if ($number) {
              $plural = (($counter = count($str)) && $number > 9) ? 's' : null;
              $hundred = ($counter == 1 && $str[0]) ? ' And ' : null;
              $str [] = ($number < 21) ? $words[$number] .
                  " " . $digits[$counter] . $plural . " " . $hundred
                  :
                  $words[floor($number / 10) * 10]
                  . " " . $words[$number % 10] . " "
                  . $digits[$counter] . $plural . " " . $hundred;
           } else $str[] = null;
        }
        $str          = array_reverse($str);
        $result       = implode('', $str);
        $points       = ($point) ? "And " . $words[$point / 10] . " " . $words[$point = $point % 10] : '' ;
        $amount_words = $result . "Rupees  ";
        return $amount_words;
    }

}