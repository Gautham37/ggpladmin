<?php

namespace App\Http\Controllers;

use App\Models\DeliveryChallan;
use App\DataTables\DeliveryChallanDataTable;
use App\Http\Requests;
use App\Http\Requests\CreateDeliveryChallanRequest;
use App\Http\Requests\UpdateDeliveryChallanRequest;
use App\Repositories\DeliveryChallanRepository;
use App\Repositories\CustomFieldRepository;
use App\Repositories\UploadRepository;
use App\Repositories\MarketRepository;
use App\Repositories\ProductRepository;
use App\Repositories\ShortLinkRepository;
use Flash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Prettus\Validator\Exceptions\ValidatorException;
use DB;
use PDF;
use App\Mail\DeliveryChallanMail;
use Illuminate\Support\Facades\Input;

class DeliveryChallanController extends Controller
{   
    /** @var  MarketRepository */
    private $marketRepository;

    /** @var  ProductRepository */
    private $productRepository;

    /** @var  CategoryRepository */
    private $deliveryChallanRepository;
    
    /** @var  ShortLinkRepository */
    private $shortLinkRepository;

    /**
     * @var CustomFieldRepository
     */
    private $customFieldRepository;

    /**
  * @var UploadRepository
  */
    private $uploadRepository;

    public function __construct(MarketRepository $marketRepo, DeliveryChallanRepository $deliveryChallanRepo, CustomFieldRepository $customFieldRepo , UploadRepository $uploadRepo, ProductRepository $productRepo, ShortLinkRepository $shortLinkRepo)
    {
        parent::__construct();
        $this->marketRepository           = $marketRepo;
        $this->productRepository          = $productRepo;
        $this->deliveryChallanRepository  = $deliveryChallanRepo;
        $this->customFieldRepository      = $customFieldRepo;
        $this->uploadRepository           = $uploadRepo;
        $this->shortLinkRepository      = $shortLinkRepo;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(DeliveryChallanDataTable $deliveryChallanDataTable)
    {
        $deliveryChallan = $deliveryChallanDataTable;
        if(Request('start_date')!='' && Request('end_date')!='' ) {
           $deliveryChallan->with('start_date',Request('start_date'))->with('end_date',Request('end_date'));  
        }
        return $deliveryChallan->render('delivery_challan.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {   
        $markets  = $this->marketRepository->select('id', DB::raw("concat(name, ' ', mobile, ' ', code) as name"))->whereIn('type', [1, 4])->pluck('name', 'id');
        $markets->prepend("Please Select",0);
        $products = $this->productRepository->get();
        $delivery_challan_no = setting('app_invoice_prefix').'-DC-'.(autoIncrementId('delivery_challan'));
        /*dd($products);*/  
        $hasCustomField = in_array($this->deliveryChallanRepository->model(),setting('custom_field_models',[]));
            if($hasCustomField){
                $customFields = $this->customFieldRepository->findByField('custom_field_model', $this->deliveryChallanRepository->model());
                $html = generateCustomField($customFields);
            }
        return view('delivery_challan.create')
                ->with("customFields", isset($html) ? $html : false)
                ->with('markets',$markets)
                ->with('products',$products)
                ->with('marketSelected','')
                ->with('delivery_challan_no',$delivery_challan_no);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //dd($request);
        $delivery_party           = $request->delivery_party;
        $delivery_code            = $request->delivery_code;
        $delivery_date            = $request->delivery_date;
        $valid_date               = $request->valid_date;
        $description              = $request->description;
        $terms_and_conditions     = $request->terms_and_conditions;
        $taxable_amount           = $request->taxable_amount;
        $sgst_amount              = $request->sgst_amount;
        $cgst_amount              = $request->cgst_amount;
        $igst_amount              = $sgst_amount + $cgst_amount;
        $additional_charge_detail = $request->additional_charge_detail;
        $additional_charges       = ($request->additional_charges > 0) ? $request->additional_charges : 0.00 ;
        $total_delivery_amount    = $request->total_delivery_amount;
        //$cash_paid                = $request->cash_paid;
        //$balance_amount           = $request->balance_amount;
        $round_off                = round($total_delivery_amount) - $total_delivery_amount;
        $total_discount           = $request->total_discount;

        $delivery_data = array(
            'delivery_code'                             => $delivery_code,
            'delivery_date'                             => $delivery_date,
            'delivery_valid_date'                       => $valid_date,              
            'market_id'                                 => $delivery_party,
            'delivery_taxable_amount'                   => $taxable_amount,
            'delivery_additional_charge_description'    => $additional_charge_detail,
            'delivery_additional_charge'                => $additional_charges,
            'delivery_discount_amount'                  => $total_discount,
            'delivery_sgst_amount'                      => $sgst_amount,
            'delivery_cgst_amount'                      => $cgst_amount,
            'delivery_igst_amount'                      => $igst_amount,
            'delivery_round_off'                        => $round_off,
            'delivery_total_amount'                     => $total_delivery_amount,
            //'delivery_cash_paid'                        => $cash_paid,
            //'delivery_balance_amount'                   => $balance_amount,
            'delivery_notes'                            => $description,
            'delivery_terms_and_conditions'             => $terms_and_conditions,
            'created_at'                                => date('Y-m-d H:i:s'),
            'created_by'                                => auth()->user()->id
        );
        $count = 0;
        if(isset($request->product_id) && count($request->product_id) > 0) {
            $delivery_challan_id = DB::table('delivery_challan')->insertGetId($delivery_data);
            //Create Shortlink
                $shortLink = $this->shortLinkRepository->create([
                        'code' => str_random(6),
                        'link' => url('').'/dc/'.$delivery_challan_id.'/1', 
                        'expiry_date' => date('Y-m-d', time() + 864000),
                        'created_by' => auth()->user()->id
                ]);
            //Create Shortlink
            if($delivery_challan_id>0) {
                $count++;
                for($i=0; $i<count($request->product_id); $i++) {
                    $delivery_detail = array(
                        'delivery_challan_id'                 =>  $delivery_challan_id,
                        'delivery_detail_product_id'        =>  $request->product_id[$i],
                        'delivery_detail_product_name'      =>  $request->product_name[$i],
                        'delivery_detail_product_hsn_code'  =>  $request->product_hsn[$i],
                        'delivery_detail_mrp'               =>  $request->product_mrp[$i],
                        'delivery_detail_quantity'          =>  $request->product_quantity[$i],
                        'delivery_detail_unit'              =>  $request->product_unit[$i],
                        'delivery_detail_price'             =>  $request->product_price[$i],
                        'delivery_detail_discount_percent'  =>  $request->product_discount_percent[$i],
                        'delivery_detail_discount_amount'   =>  $request->product_discount_price[$i],
                        'delivery_detail_tax_percent'       =>  $request->product_tax[$i],
                        'delivery_detail_sgst_percent'      =>  ($request->product_sgst_per[$i] > 0) ? $request->product_sgst_per[$i] : 0,
                        'delivery_detail_cgst_percent'      =>  ($request->product_cgst_per[$i] > 0) ? $request->product_cgst_per[$i] : 0,
                        'delivery_detail_igst_percent'      =>  ($request->product_sgst_per[$i] + $request->product_cgst_per[$i]),
                        'delivery_detail_sgst'              =>  ($request->product_sgst_pri[$i] > 0) ? $request->product_sgst_pri[$i] : 0,
                        'delivery_detail_cgst'              =>  ($request->product_cgst_pri[$i] > 0) ? $request->product_cgst_pri[$i] : 0,
                        'delivery_detail_igst'              =>  ($request->product_sgst_pri[$i] + $request->product_cgst_pri[$i]),
                        'delivery_detail_amount'            =>  $request->amount[$i],
                        'created_at'                        =>  date('Y-m-d H:i:s'),
                        'created_by'                        => auth()->user()->id
                    );
                    $insert_delivery_items = DB::table('delivery_challan_detail')->insertGetId($delivery_detail);
                    if($insert_delivery_items > 0) {
                        $count++;
                    }
                }
                if($count > 0) {
                    
                    //Notification mail sent
                    $customer_details   = DB::table('markets')->where('id',$delivery_party)->first();
                    $customer_mail = $customer_details->email; 
                    $customer_name = $customer_details->name; 
                     
                    $details = ['title' => 'Delivery Challan Notification Mail','body' => 'Delivery Challan has been created.','customer_name' =>$customer_name,'delivery_code' =>$delivery_code,'delivery_date' =>$delivery_date,'total_delivery_amount' =>$total_delivery_amount];
                    //Send notification
                    \Mail::to($customer_mail)->send(new DeliveryChallanMail($details));

                    
                    Flash::success(__('lang.saved_successfully', ['operator' => __('lang.delivery_challan')]));
                    return redirect(route('deliveryChallan.show',$delivery_challan_id));
                } else {
                    Flash::error('Saved Unsuccessfully');
                    return redirect(route('deliveryChallan.index'));
                }

            } else {
                Flash::error('Saved Unsuccessfully');
                return redirect(route('deliveryChallan.index'));
            }
        } else {
            Flash::error('delivery item is Empty. Please Add the Products');
            return redirect(route('deliveryChallan.index'));
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\DeliveryChallan  $DeliveryChallan
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $DeliveryChallan   = DB::table('delivery_challan')->where('id',$id)->first();
        $deliveryDetails = DB::table('delivery_challan_detail')->where('delivery_challan_id',$id)->get();
        $deliveryParty   = DB::table('markets')->where('id',$DeliveryChallan->market_id)->first();
        
        $short_link_code = $this->shortLinkRepository
                            ->where('link',url('').'/dc/'.$id.'/1')
                            ->first()->code;
        $short_link = url('short/'.$short_link_code);   
        
        //Whatsapp message compose
        $wp='Details of Delivery Challan (number: '.$DeliveryChallan->delivery_code.') recorded on 
            '.date('d-F-Y',strtotime($DeliveryChallan->delivery_date)).''."%0A%0A";
        $wp.="Delivery Challan amount: Rs.".$DeliveryChallan->delivery_total_amount."%0A%0A";
        $wp.="Delivery Challan balance: Rs.".$DeliveryChallan->delivery_balance_amount."%0A%0A";
        $wp.="Outstanding balance:"."%0A%0A";
        $wp.="Outstanding balance: Rs.".$DeliveryChallan->delivery_balance_amount."%0A%0A";
        $wp.="Download here $short_link %0A%0A";
        $wp.="Sent via ".setting('app_name');
        
        return view('delivery_challan.show')->with('delivery_challan',$DeliveryChallan)->with('delivery_detail',$deliveryDetails)->with('delivery_party',$deliveryParty)->with('wp',$wp);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\DeliveryChallan  $DeliveryChallan
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {   

        /*$result = DeliveryChallan::join('markets', 'delivery_challan.market_id', '=', 'markets.id')
                  ->select('delivery_challan.id','delivery_challan.delivery_date','delivery_challan.delivery_total_amount','delivery_challan.delivery_valid_date','delivery_challan.updated_at','markets.name as market_name')  ->get();
        dd($result);*/

        $markets  = $this->marketRepository->select('id', DB::raw("concat(name, ' ', mobile, ' ', code) as name"))->whereIn('type', [1, 4])->pluck('name', 'id');
        $markets->prepend("Please Select",0);
        $products = $this->productRepository->get(); 
        $hasCustomField = in_array($this->deliveryChallanRepository->model(),setting('custom_field_models',[]));
            if($hasCustomField){
                $customFields = $this->customFieldRepository->findByField('custom_field_model', $this->deliveryChallanRepository->model());
                $html = generateCustomField($customFields);
            }
        $DeliveryChallan   = DB::table('delivery_challan')->where('id',$id)->first();
        $deliveryDetails = DB::table('delivery_challan_detail')->where('delivery_challan_id',$id)->get();
        $deliveryParty   = DB::table('markets')->where('id',$DeliveryChallan->market_id)->first();
        return view('delivery_challan.edit')
                    ->with('delivery_challan',$DeliveryChallan)
                    ->with('delivery_detail',$deliveryDetails)
                    ->with('delivery_party',$deliveryParty)
                    ->with("customFields", isset($html) ? $html : false)
                    ->with('markets',$markets)
                    ->with('products',$products)
                    ->with('marketSelected',$DeliveryChallan->market_id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\DeliveryChallan  $DeliveryChallan
     * @return \Illuminate\Http\Response
     */
    public function update($id, Request $request)
    {   
        $delivery_id              = $id;
        $delivery_party           = $request->delivery_party;
        $delivery_code            = $request->delivery_code;
        $delivery_date            = $request->delivery_date;
        $valid_date               = $request->valid_date;
        $description              = $request->description;
        $terms_and_conditions     = $request->terms_and_conditions;
        $taxable_amount           = $request->taxable_amount;
        $sgst_amount              = $request->sgst_amount;
        $cgst_amount              = $request->cgst_amount;
        $igst_amount              = $sgst_amount + $cgst_amount;
        $additional_charge_detail = $request->additional_charge_detail;
        $additional_charges       = ($request->additional_charges > 0) ? $request->additional_charges : 0.00 ;
        $total_delivery_amount    = $request->total_delivery_amount;
        //$cash_paid                = $request->cash_paid;
        //$balance_amount           = $request->balance_amount;
        $round_off                = round($total_delivery_amount) - $total_delivery_amount;
        $total_discount           = $request->total_discount;

        $delivery_data = array(
            'delivery_code'                             => $delivery_code,
            'delivery_date'                             => $delivery_date,
            'delivery_valid_date'                       => $valid_date,              
            'market_id'                                 => $delivery_party,
            'delivery_taxable_amount'                   => $taxable_amount,
            'delivery_additional_charge_description'    => $additional_charge_detail,
            'delivery_additional_charge'                => $additional_charges,
            'delivery_discount_amount'                  => $total_discount,
            'delivery_sgst_amount'                      => $sgst_amount,
            'delivery_cgst_amount'                      => $cgst_amount,
            'delivery_igst_amount'                      => $igst_amount,
            'delivery_round_off'                        => $round_off,
            'delivery_total_amount'                     => $total_delivery_amount,
            //'delivery_cash_paid'                        => $cash_paid,
            //'delivery_balance_amount'                   => $balance_amount,
            'delivery_notes'                            => $description,
            'delivery_terms_and_conditions'             => $terms_and_conditions,
            'updated_at'                                => date('Y-m-d H:i:s'),
            'updated_by'                                => auth()->user()->id
        );
        $count = 0;
        if(isset($request->product_id) && count($request->product_id) > 0) {
            $delivery_challan_id = DB::table('delivery_challan')->where('id',$delivery_id)->update($delivery_data);
            
            //Delete Delivery Challan Item
            if(isset($request->deleteItem) && count($request->deleteItem) > 0) {
                foreach($request->deleteItem as $item) {
                    $delete_item = DB::table('delivery_challan_detail')->where('id',$item)->delete();
                }
            }
            //Delete Delivery Challan Item            
            
            if($delivery_challan_id) {
                $count++;
                for($i=0; $i<count($request->product_id); $i++) {
                    $delivery_detail = array(
                        'delivery_challan_id'                 =>  $delivery_id,
                        'delivery_detail_product_id'        =>  $request->product_id[$i],
                        'delivery_detail_product_name'      =>  $request->product_name[$i],
                        'delivery_detail_product_hsn_code'  =>  $request->product_hsn[$i],
                        'delivery_detail_mrp'               =>  $request->product_mrp[$i],
                        'delivery_detail_quantity'          =>  $request->product_quantity[$i],
                        'delivery_detail_unit'              =>  $request->product_unit[$i],
                        'delivery_detail_price'             =>  $request->product_price[$i],
                        'delivery_detail_discount_percent'  =>  $request->product_discount_percent[$i],
                        'delivery_detail_discount_amount'   =>  $request->product_discount_price[$i],
                        'delivery_detail_tax_percent'       =>  $request->product_tax[$i],
                        'delivery_detail_sgst_percent'      =>  ($request->product_sgst_per[$i] > 0) ? $request->product_sgst_per[$i] : 0,
                        'delivery_detail_cgst_percent'      =>  ($request->product_cgst_per[$i] > 0) ? $request->product_cgst_per[$i] : 0,
                        'delivery_detail_igst_percent'      =>  ($request->product_sgst_per[$i] + $request->product_cgst_per[$i]),
                        'delivery_detail_sgst'              =>  ($request->product_sgst_pri[$i] > 0) ? $request->product_sgst_pri[$i] : 0,
                        'delivery_detail_cgst'              =>  ($request->product_cgst_pri[$i] > 0) ? $request->product_cgst_pri[$i] : 0,
                        'delivery_detail_igst'              =>  ($request->product_sgst_pri[$i] + $request->product_cgst_pri[$i]),
                        'delivery_detail_amount'            =>  $request->amount[$i],
                        'updated_at'                        =>  date('Y-m-d H:i:s'),
                        'updated_by'                        => auth()->user()->id
                    );
                    if($request->delivery_detail_id[$i]!='' && $request->delivery_detail_id[$i] > 0) {
                        $update_delivery_items = DB::table('delivery_challan_detail')->where('id',$request->delivery_detail_id[$i])->update($delivery_detail);
                        if($update_delivery_items > 0) {
                            $count++;
                        }
                    } else {
                        $insert_delivery_items = DB::table('delivery_challan_detail')->insertGetId($delivery_detail);
                        $count++;
                    }
                }
                if($count > 0) {
                    Flash::success(__('lang.saved_successfully', ['operator' => __('lang.delivery_challan')]));
                    return redirect(route('deliveryChallan.show',$delivery_id));
                } else {
                    Flash::error('Saved Unsuccessfully');
                    return redirect(route('deliveryChallan.index'));
                }

            } else {
                Flash::error('Saved Unsuccessfully');
                return redirect(route('deliveryChallan.index'));
            }
        } else {
            Flash::error('delivery item is Empty. Please Add the Products');
            return redirect(route('deliveryChallan.index'));
        }    
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\DeliveryChallan  $DeliveryChallan
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $DeliveryChallan = $this->deliveryChallanRepository->findWithoutFail($id);

        if (empty($DeliveryChallan)) {
            Flash::error('delivery Invoice not found');

            return redirect(route('deliveryChallan.index'));
        }

        $result = $this->deliveryChallanRepository->delete($id);
        if($result) {
            $delete_delivery_items = DB::table('delivery_challan_detail')->where('delivery_challan_id', '=', $id)->delete();
        }
        Flash::success(__('lang.deleted_successfully',['operator' => __('lang.delivery_challan')]));

        return redirect(route('deliveryChallan.index'));
    }

    public function loaddeliveryItems(Request $request) {
        $items    = $request->itemId;
        $quantity = $request->itemQuantity;
        $unit     = $request->itemUnit;
        $output=array();
        $count = 0; 
        foreach($items as $key => $item) :
            $products = DB::table('products')->where('id',$item)->first();
            $products->s_no      = ++$count;
            $products->quantity  = $quantity[$key];
            $products->tax_rates = DB::table('tax_rates')->get();
            $products->p_price   = $products->price;
            
            if($unit[$key]==$products->unit) {
                $products->unit = $products->unit;
                $products->p_price = $products->p_price; 
            } elseif($unit[$key]==$products->secondary_unit) {
                $products->unit = $products->secondary_unit;
                $products->p_price = $products->p_price / $products->conversion_rate;                
            }    
            
            array_push($output,$products);
        endforeach;

        echo json_encode($output);
    }

    public function loaddeliveryProducts(Request $request) {
        $party          = $request->party;
        $party_details  = $this->marketRepository->where('id',$party)->first();
        $customer_group = $party_details->customer_group;    
        $products = $this->productRepository->get();
        foreach ($products as $key => $value) {
            if($customer_group > 0) {
                $group_price = DB::table('product_group_price')->where('customer_group_id',$customer_group)->where('product_id',$value->id)->get();
                if(count($group_price) > 0) {
                    if($group_price[0]->product_price > 0) {    
                        $products[$key]->price = $group_price[0]->product_price;               
                    }
                }
            }   
        }
        return view('layouts.products_modal')->with('products',$products)->with('party',$party);
    }

    public function loadDeliveryProductsbyBarcode(Request $request) {
        $party          = $request->party;
        $party_details  = $this->marketRepository->where('id',$party)->first();
        $customer_group = $party_details->customer_group;    
        $products = $this->productRepository->get();
        foreach ($products as $key => $value) {
            if($customer_group > 0) {
                $group_price = DB::table('product_group_price')->where('customer_group_id',$customer_group)->where('product_id',$value->id)->get();
                if(count($group_price) > 0) {
                    if($group_price[0]->product_price > 0) {    
                        $products[$key]->price = $group_price[0]->product_price;               
                    }
                }
            }   
        }
        return view('layouts.products_barcode_modal')->with('products',$products)->with('party',$party);
    }

    public function loadParty(Request $request) {
        $party  = $request->party;
        $result = $this->marketRepository->where('id',$party)->get();
        echo json_encode($result[0]);    
    }

    public function loaddeliveryDetailItems(Request $request) {
        $delivery_id    = $request->delivery_id;
        $delivery_items = DB::table('delivery_challan_detail')->where('delivery_challan_id',$delivery_id)->get();
        $count = 0;
        foreach ($delivery_items as $key => $value) {
            $delivery_items[$key]->s_no = ++$count;
            $delivery_items[$key]->tax_rates = DB::table('tax_rates')->get();
        }
        echo json_encode($delivery_items); 
    }

    
    public function downloaddeliveryChallan($id,$type,Request $request)
    {   
        $delivery_challan = DB::table('delivery_challan')->where('id',$id)->first();
        $delivery_detail  = DB::table('delivery_challan_detail')->leftJoin('products', 'delivery_challan_detail.delivery_detail_product_id', '=', 'products.id')->where('delivery_challan_id',$id)->get();
        $delivery_party   = DB::table('markets')->where('id',$delivery_challan->market_id)->first();

        $pdf = PDF::loadView('delivery_challan.delivery_challan_pdf', compact('delivery_challan','delivery_detail','delivery_party'));
        $filename = 'PO-'.$delivery_challan->delivery_code.'-'.$delivery_party->name.'.pdf';
        return $pdf->download($filename);
    }

    public function printdeliveryChallan($id,$type,Request $request)
    {   
        $delivery_challan = DB::table('delivery_challan')->where('id',$id)->first();
        $delivery_detail  = DB::table('delivery_challan_detail')->leftJoin('products', 'delivery_challan_detail.delivery_detail_product_id', '=', 'products.id')->where('delivery_challan_id',$id)->get();
        $delivery_party   = DB::table('markets')->where('id',$delivery_challan->market_id)->first();

        $pdf = PDF::loadView('delivery_challan.delivery_challan_pdf', compact('delivery_challan','delivery_detail','delivery_party'));
        $filename = 'PO-'.$delivery_challan->delivery_code.'-'.$delivery_party->name.'.pdf';
        return $pdf->stream($filename);
    }

    public function thermalprintdeliveryChallan($id,Request $request) {
        $delivery_challan   = DB::table('delivery_challan')->where('id',$id)->first();
        $delivery_detail  = DB::table('delivery_challan_detail')->where('delivery_challan_id',$id)->get();
        $delivery_party   = DB::table('markets')->where('id',$delivery_challan->market_id)->first();
        return view('delivery_challan.delivery_challan_thermalprint', compact('delivery_challan','delivery_detail','delivery_party'));
    }

}
