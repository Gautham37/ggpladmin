<?php

namespace App\Http\Controllers;

use App\Criteria\Users\DriversCriteria;
use App\Models\SupplierRequest;
use App\DataTables\SupplierRequestDataTable;
use App\Http\Requests;
use App\Http\Requests\CreateSupplierRequestRequest;
use App\Http\Requests\UpdateSupplierRequestRequest;
use App\Repositories\SupplierRequestRepository;
use App\Repositories\InventoryRepository;
use App\Repositories\TransactionRepository;
use App\Repositories\ProductRepository;
use App\Repositories\CustomFieldRepository;
use App\Repositories\UploadRepository;
use App\Repositories\MarketRepository;
use App\Repositories\UserRepository;
use App\Repositories\DeliveryAddressRepository;
use App\Notifications\NewSupplierRequest;
use App\Notifications\StatusChangedSupplierRequest;
use App\Notifications\AssignedSupplierRequest;
use Flash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Response;
use Prettus\Validator\Exceptions\ValidatorException;
use DB;
use PDF;
use CustomHelper;
use Illuminate\Support\Facades\Input;

class SupplierRequestController extends Controller
{   
    /** @var  MarketRepository */
    private $marketRepository;

    /** @var  ProductRepository */
    private $productRepository;

    /** @var  SupplierRequestRepository */
    private $supplierRequestRepository;

    /** @var  InventoryRepository */
    private $inventoryRepository;

    /** @var  TransactionRepository */
    private $transactionRepository;

    /** @var  UserRepository */
    private $userRepository;

    /** @var  DeliveryAddressRepository */
    private $deliveryAddressRepository;

    /**
     * @var CustomFieldRepository
     */
    private $customFieldRepository;

    /**
  * @var UploadRepository
  */
    private $uploadRepository;

    public function __construct(MarketRepository $marketRepo, SupplierRequestRepository $supplierRequestRepo, CustomFieldRepository $customFieldRepo , UploadRepository $uploadRepo, ProductRepository $productRepo, InventoryRepository $inventoryRepo, UserRepository $userRepo, DeliveryAddressRepository $deliveryAddressRepo, TransactionRepository $transactionRepo)
    {
        parent::__construct();
        $this->marketRepository           = $marketRepo;
        $this->productRepository          = $productRepo;
        $this->supplierRequestRepository  = $supplierRequestRepo;
        $this->inventoryRepository        = $inventoryRepo;
        $this->transactionRepository      = $transactionRepo;
        $this->userRepository             = $userRepo;
        $this->deliveryAddressRepository  = $deliveryAddressRepo;
        $this->customFieldRepository      = $customFieldRepo;
        $this->uploadRepository           = $uploadRepo;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(SupplierRequestDatatable $supplierRequestDataTable)
    {
        $supplierRequest = $supplierRequestDataTable;
        if(Request('start_date')!='' && Request('end_date')!='' ) {
           $supplierRequest->with('start_date',Request('start_date'))->with('end_date',Request('end_date'));  
        }
        return $supplierRequest->render('supplier_request.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {   
        
        //$markets  = $this->marketRepository->select('id', DB::raw("concat(name, ' ', mobile, ' ', code) as name"))->whereIn('type', [2, 3])->pluck('name', 'id');
        //$markets  = $this->marketRepository->select('id', DB::raw("concat(name, ' ', mobile, ' ', code) as name"))->where('type', 2)->pluck('name', 'id');
        $markets  = $this->marketRepository->select('id', DB::raw("concat(COALESCE(`name`,''), ' ', COALESCE(`mobile`,''), ' ', COALESCE(`code`,'')) as name"))->where('type', 2)->pluck('name', 'id');
        $markets->prepend("Please Select",0);                         
        
        $products = $this->productRepository->get();
        $supplier_request_no = setting('app_invoice_prefix').'-SR-'.(autoIncrementId('supplier_request'));
        $hasCustomField = in_array($this->supplierRequestRepository->model(),setting('custom_field_models',[]));
            if($hasCustomField){
                $customFields = $this->customFieldRepository->findByField('custom_field_model', $this->supplierRequestRepository->model());
                $html = generateCustomField($customFields);
            }
        return view('supplier_request.create')
                ->with("customFields", isset($html) ? $html : false)
                ->with('users',$markets)
                ->with('products',$products)
                ->with('userSelected','')
                ->with('supplier_request_no',$supplier_request_no);
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
        $sr_party              = $request->sr_party;
        $sr_code               = $request->sr_code;
        $sr_date               = $request->sr_date;
        $valid_date            = $request->sr_valid_date;
        $description           = $request->description;
        //$taxable_amount        = $request->taxable_amount;

        $supplir_request_data  = array(
            'sr_code'                => $sr_code,
            'sr_date'                => $sr_date,
            'sr_valid_date'          => $valid_date,              
            'market_id'              => $sr_party,
            //'sr_taxable_amount'      => $taxable_amount,
            'sr_notes'               => $description,
            'created_at'             => date('Y-m-d H:i:s'),
            'created_by'             => auth()->user()->id
        );
        $count = 0;
        if(isset($request->product_id) && count($request->product_id) > 0) {
            $supplier_request_id = DB::table('supplier_request')->insertGetId($supplir_request_data);
            if($supplier_request_id>0) {

                $count++;
                for($i=0; $i<count($request->product_id); $i++) {
                    $supplier_request_detail = array(
                        'supplier_request_id'         =>  $supplier_request_id,
                        'sr_detail_product_id'        =>  $request->product_id[$i],
                        'sr_detail_product_name'      =>  $request->product_name[$i],
                        'sr_detail_product_hsn_code'  =>  $request->product_hsn[$i],
                        'sr_detail_mrp'               =>  $request->product_mrp[$i],
                        'sr_detail_quantity'          =>  $request->product_quantity[$i],
                        'sr_detail_unit'              =>  $request->product_unit[$i],
                        //'sr_detail_price'           =>  $request->product_price[$i],
                        'sr_amount'                   =>  $request->amount[$i],
                        'created_at'                  =>  date('Y-m-d H:i:s'),
                        'created_by'                  => auth()->user()->id
                    );
                    $insert_supplier_request_items = DB::table('supplier_request_detail')->insertGetId($supplier_request_detail);
                    if($insert_supplier_request_items > 0) {
                        $count++;
                    }
                }
                if($count > 0) {
                    
                    //Notification
                        $supplier_request = $this->supplierRequestRepository->findWithoutFail($supplier_request_id);
                        $user_market      = DB::table('user_markets')->where('market_id',$supplier_request->market->id)->first();
                        $market_user      = $this->userRepository->findWithoutFail($user_market->user_id);
                        Notification::send([$market_user], new NewSupplierRequest($supplier_request));
                    //Notification
                    
                    Flash::success(__('lang.saved_successfully', ['operator' => __('lang.supplier_request')]));
                    return redirect(route('supplierRequest.show',$supplier_request_id));
                } else {
                    Flash::error('Saved Unsuccessfully');
                    return redirect(route('supplierRequest.index'));
                }

            } else {
                Flash::error('Saved Unsuccessfully');
                return redirect(route('supplierRequest.index'));
            }
        } else {
            Flash::error('Request item is Empty. Please Add the Products');
            return redirect(route('supplierRequest.index'));
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\supplierRequest  $supplierRequest
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $supplierRequest  = DB::table('supplier_request')->where('id',$id)->first();
        $requestDetails = DB::table('supplier_request_detail')->where('supplier_request_id',$id)->get();
        $requestParty       = DB::table('markets')->where('id',$supplierRequest->market_id)->first();                          
        return view('supplier_request.show')->with('supplier_request',$supplierRequest)->with('requestDetails',$requestDetails)->with('requestParty',$requestParty);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\supplierRequest  $supplierRequest
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {   

        //$markets  = $this->marketRepository->select('id', DB::raw("concat(COALESCE(`name`,''), ' ', COALESCE(`mobile`,''), ' ', COALESCE(`code`,'')) as name"))->whereIn('type', [2, 3])->pluck('name', 'id');
        $markets  = $this->marketRepository->select('id', DB::raw("concat(COALESCE(`name`,''), ' ', COALESCE(`mobile`,''), ' ', COALESCE(`code`,'')) as name"))->where('type', 2)->pluck('name', 'id');
        $markets->prepend("Please Select",0);  

        $products = $this->productRepository->get(); 
        $hasCustomField = in_array($this->supplierRequestRepository->model(),setting('custom_field_models',[]));
            if($hasCustomField){
                $customFields = $this->customFieldRepository->findByField('custom_field_model', $this->supplierRequestRepository->model());
                $html = generateCustomField($customFields);
            }
        $supplierRequest   = DB::table('supplier_request')->where('id',$id)->first();
        $requestDetails    = DB::table('supplier_request_detail')->where('supplier_request_id',$id)->get();
        
        $driver = $this->userRepository->whereHas("roles", function($q){ $q->where("name", "driver"); })->pluck('name','id');
        $driver->prepend("Please Select Driver",0);
        
        
        $requestParty     = DB::table('markets')->where('id',$supplierRequest->market_id)->first();                 
        return view('supplier_request.edit')
                    ->with('supplier_request',$supplierRequest)
                    ->with('request_detail',$requestDetails)
                    ->with('request_party',$requestParty)
                    ->with("customFields", isset($html) ? $html : false)
                    ->with('markets',$markets)
                    ->with('products',$products)
                    ->with('driver',$driver)
                    ->with('marketSelected',$supplierRequest->market_id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\supplierRequest  $supplierRequest
     * @return \Illuminate\Http\Response
     */
    public function update($id, Request $request)
    {   
        $sr_request_id         = $id;
        $sr_party              = $request->sr_party;
        $sr_code               = $request->sr_code;
        $sr_date               = $request->sr_date;
        $valid_date            = $request->sr_valid_date;
        $description           = $request->description;
        //$taxable_amount        = $request->taxable_amount;
        $sr_status             = $request->sr_status;
        $sr_driver             = isset($request->sr_driver) ? $request->sr_driver : null ;

        $supplir_request_data  = array(
            'sr_code'                => $sr_code,
            'sr_date'                => $sr_date,
            'sr_valid_date'          => $valid_date,              
            'market_id'              => $sr_party,
            //'sr_taxable_amount'      => $taxable_amount,
            'sr_notes'               => $description,
            'sr_status'              => $sr_status,
            'sr_driver'              => $sr_driver,
            'created_at'             => date('Y-m-d H:i:s'),
            'updated_by'             => auth()->user()->id
        );
        $count = 0;
        
        //Delete Supplier Request Item
        if(isset($request->deleteItem) && count($request->deleteItem) > 0) {
            foreach($request->deleteItem as $item) {
                $delete_item = DB::table('supplier_request_detail')->where('id',$item)->delete();
            }
        }
        //Delete Supplier Request Item
        
        if(isset($request->product_id) && count($request->product_id) > 0) {
            $oldSupplierRequest  = $this->supplierRequestRepository->findWithoutFail($sr_request_id);
            $supplier_request_id = DB::table('supplier_request')->where('id',$sr_request_id)->update($supplir_request_data);
            if($supplier_request_id) {

                $count++;
                for($i=0; $i<count($request->product_id); $i++) {
                    $supplier_request_detail = array(
                        'supplier_request_id'         =>  $sr_request_id,
                        'sr_detail_product_id'        =>  $request->product_id[$i],
                        'sr_detail_product_name'      =>  $request->product_name[$i],
                        'sr_detail_product_hsn_code'  =>  $request->product_hsn[$i],
                        'sr_detail_mrp'               =>  $request->product_mrp[$i],
                        'sr_detail_quantity'          =>  $request->product_quantity[$i],
                        'sr_detail_unit'              =>  $request->product_unit[$i],
                        //'sr_detail_price'           =>  $request->product_price[$i],
                        'sr_amount'                   =>  $request->amount[$i],
                        'created_at'                  =>  date('Y-m-d H:i:s'),
                    );
                    if(isset($request->sr_detail_id[$i]) && $request->sr_detail_id[$i]!='' && $request->sr_detail_id[$i] > 0) {
                         $supplier_request_detail['updated_by'] = auth()->user()->id;
                        $update_supplier_request_items = DB::table('supplier_request_detail')
                                                            ->where('id',$request->sr_detail_id[$i])
                                                            ->update($supplier_request_detail);
                        if($update_supplier_request_items > 0) { 
                            $count++;
                        }
                    } else {
                        $supplier_request_detail['created_by']  = auth()->user()->id;
                        $insert_supplier_request_items = DB::table('supplier_request_detail')->insertGetId($supplier_request_detail);
                        if($insert_supplier_request_items > 0) {
                            $count++;
                        }
                    }
                }
                
                
                //Notification
                    if (setting('enable_notifications', false)) {
                        $supplier_request = $this->supplierRequestRepository->findWithoutFail($sr_request_id);
                        
                        if ($sr_status!='' && $sr_status != $oldSupplierRequest->sr_status) {
                            $user_market    = DB::table('user_markets')->where('market_id',$supplier_request->market->id)->first();
                            $market_user    = $this->userRepository->findWithoutFail($user_market->user_id);
                            Notification::send([$market_user], new StatusChangedSupplierRequest($supplier_request));
                        }
        
                        if ($sr_driver!=null && ($sr_driver != $oldSupplierRequest->sr_driver)) {
                            $driver = $this->userRepository->findWithoutFail($sr_driver);
                            if (!empty($driver)) {
                                Notification::send([$driver], new AssignedSupplierRequest($supplier_request));
                            }
                        }
                    }
                //Notification
                
                
                if($count > 0) {
                    Flash::success(__('lang.saved_successfully', ['operator' => __('lang.supplier_request')]));
                    return redirect(route('supplierRequest.show',$sr_request_id));
                } else {
                    Flash::error('Saved Unsuccessfully');
                    return redirect(route('supplierRequest.index'));
                }

            } else {
                Flash::error('Saved Unsuccessfully');
                return redirect(route('supplierRequest.index'));
            }
        } else {
            Flash::error('Sales item is Empty. Please Add the Products');
            return redirect(route('supplierRequest.index'));
        }    
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\supplierRequest  $supplierRequest
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $supplierRequest = $this->supplierRequestRepository->findWithoutFail($id);

        if (empty($supplierRequest)) {
            Flash::error('Sales Invoice not found');

            return redirect(route('supplierRequest.index'));
        }
            $input['updated_by'] = auth()->user()->id;
            $input['is_deleted'] = 1;
            $result = $this->supplierRequestRepository->update($input, $id);
        //$result = $this->supplierRequestRepository->delete($id);
        if($result) {
            $delete_sr_items =  DB::table('supplier_request_detail')->update($input, $id);
            //$delete_sr_items = DB::table('supplier_request_detail')->where('supplier_request_id', '=', $id)->delete();
        }
        Flash::success(__('lang.deleted_successfully',['operator' => __('lang.supplier_request')]));

        return redirect(route('supplierRequest.index'));
    }

    public function loadSupplierRequestItems(Request $request) {
        $party    = $request->party;
        if($party > 0) {
            $party_details  = $this->marketRepository->where('id',$party)->first();
            $customer_group = $party_details->customer_group;  
        }
        $items    = $request->itemId;
        $quantity = $request->itemQuantity;
        $unit     = $request->itemUnit;
        $output=array();
        $count = 0; 
        foreach($items as $key => $item) :
            $products = DB::table('products')->where('id',$item)->first();
            $products->s_no = ++$count;
            $products->quantity = $quantity[$key];
            $products->tax_rates= DB::table('tax_rates')->get();
            $products->p_price = $products->price;
            
            if($unit[$key]==$products->unit) {
                $products->unit = $products->unit;
                $products->purchase_price = $products->purchase_price; 
            } elseif($unit[$key]==$products->secondary_unit) {
                $products->unit = $products->secondary_unit;
                $products->purchase_price = $products->purchase_price / $products->conversion_rate;                
            }
            
            /*$price_variations = DB::table('product_price_variation')->where('product_id',$item)->get();
            if(count($price_variations) > 0) {
                foreach ($price_variations as $key => $value) {
                    if($products->quantity >= $value->purchase_quantity) {
                       $calculation     = $products->price * $value->price_multiplier / 100;
                       $products->p_price = $calculation;
                    }
                }                
            }*/


            /*if(isset($customer_group) && $customer_group > 0) {
                $group_price = DB::table('product_group_price')->where('customer_group_id',$customer_group)->where('product_id',$item)->get();
                if(count($group_price) > 0) {
                    if($group_price[0]->product_price > 0) {    
                        $products->price = $group_price[0]->product_price;               
                    }
                }
            }*/    

            array_push($output,$products);
        endforeach;

        echo json_encode($output);
    }

    public function loadSupplierRequestProducts(Request $request) {
        $party          = $request->party;
        $party_details  = $this->marketRepository->where('id',$party)->first();
        $customer_group = $party_details->customer_group;    
        $products = $this->productRepository->get();
        return view('layouts.products_modal')->with('products',$products)->with('party',$party);
    }

    public function loadSupplierRequestProductsbyBarcode(Request $request) {
        $party          = $request->party;
        $party_details  = $this->marketRepository->where('id',$party)->first();
        $customer_group = $party_details->customer_group;    
        $products = $this->productRepository->get();
        return view('layouts.products_barcode_modal')->with('products',$products)->with('party',$party);
    }

    public function loadParty(Request $request) {
        $party  = $request->party;
        $result = $this->marketRepository->where('id',$party)->get();
        echo json_encode($result[0]);     
    }

    public function loadSupplierRequestDetailItems(Request $request) {
        $sales_id    = $request->sales_id;
        $sales_items = DB::table('supplier_request_detail')->where('supplier_request_id',$sales_id)->get();
        $count = 0;
        foreach ($sales_items as $key => $value) {
            if($sales_items[$key]->sr_product_image!='') {
                $upload = $this->uploadRepository->getByUuid($sales_items[$key]->sr_product_image);
                if($upload) {
                    $uploaded_image = $upload->getMedia('vendor_supply_image')[0]->getUrl(); 
                } else {
                    $uploaded_image = '';        
                }
            } else {
                $uploaded_image = '';    
            }
            
            $sales_items[$key]->s_no = ++$count;
            $sales_items[$key]->tax_rates = DB::table('tax_rates')->get();
            $sales_items[$key]->farmer_product_image = $uploaded_image;
        }
        echo json_encode($sales_items); 
    }

    
    public function downloadsupplierRequest($id,$type,Request $request)
    {   
        $supplier_request          = DB::table('supplier_request')->where('id',$id)->first();
        $supplier_request_detail   = DB::table('supplier_request_detail')->leftJoin('products', 'supplier_request_detail.sr_detail_product_id', '=', 'products.id')->where('supplier_request_id',$id)->get();
        $supplier_request_party    = DB::table('markets')->where('id',$supplier_request->market_id)->first();

        $pdf = PDF::loadView('supplier_request.supplier_request_pdf', compact('supplier_request','supplier_request_detail','supplier_request_party','type'));
        $filename = 'SI-'.$supplier_request->sr_code.'-'.$supplier_request_party->name.'.pdf';
        return $pdf->download($filename);
    }

    public function printsupplierRequest($id,$type,Request $request)
    {   
        $supplier_request          = DB::table('supplier_request')->where('id',$id)->first();
        $supplier_request_detail   = DB::table('supplier_request_detail')->leftJoin('products', 'supplier_request_detail.sr_detail_product_id', '=', 'products.id')->where('supplier_request_id',$id)->get();
        $supplier_request_party    = DB::table('markets')->where('id',$supplier_request->market_id)->first();

        $pdf = PDF::loadView('supplier_request.supplier_request_pdf', compact('supplier_request','supplier_request_detail','supplier_request_party','type'));
        $filename = 'SI-'.$supplier_request->sr_code.'-'.$supplier_request_party->name.'.pdf';
        return $pdf->stream($filename);
    }

    public function thermalprintsupplierRequest($id,Request $request)
    {   
        $supplier_request           = DB::table('supplier_request')->where('id',$id)->first();
        $supplier_request_detail    = DB::table('supplier_request_detail')->where('supplier_request_id',$id)->get();
        $supplier_request_party     = DB::table('markets')->where('id',$supplier_request->market_id)->first();                 
        return view('supplier_request.supplier_request_thermalprint', compact('supplier_request','supplier_request_detail','supplier_request_party'));
    
    }

}
