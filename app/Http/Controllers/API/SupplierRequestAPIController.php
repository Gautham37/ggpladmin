<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
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
use App\Repositories\DeliveryTipsRepository;
use App\Repositories\MarketRepository;
use App\Repositories\ShortLinkRepository;
use App\Repositories\UserRepository;
use App\Repositories\DeliveryAddressRepository;
use Flash;

use App\Notifications\NewSupplierRequest;
use App\Notifications\StatusChangedSupplierRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Response;

use InfyOm\Generator\Criteria\LimitOffsetCriteria;
use Prettus\Repository\Criteria\RequestCriteria;
use Prettus\Repository\Exceptions\RepositoryException;
use Prettus\Validator\Exceptions\ValidatorException;
use DB;
use PDF;
use CustomHelper;
use Illuminate\Support\Str;

class SupplierRequestAPIController extends Controller
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
    
    /** @var  ShortLinkRepository */
    private $shortLinkRepository;
    
    private $deliveryTipsRepository;
    
    /**
     * @var CustomFieldRepository
     */
    private $customFieldRepository;

    /**
  * @var UploadRepository
  */
    private $uploadRepository;

    public function __construct(MarketRepository $marketRepo, SupplierRequestRepository $supplierRequestRepo, CustomFieldRepository $customFieldRepo , UploadRepository $uploadRepo, ProductRepository $productRepo, InventoryRepository $inventoryRepo, UserRepository $userRepo, DeliveryAddressRepository $deliveryAddressRepo, TransactionRepository $transactionRepo, ShortLinkRepository $shortLinkRepo, DeliveryTipsRepository $deliveryTipsRepo)
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
        $this->shortLinkRepository        = $shortLinkRepo;
        $this->deliveryTipsRepository     = $deliveryTipsRepo;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try{
            $api_token         = $request->api_token;
            $date = $request->date;
            $user              = DB::table('users')
                                    ->join('user_markets','users.id','=','user_markets.user_id')
                                    ->where('users.api_token',$api_token)
                                    ->first();
            if($user) {    
                 if($date!=''){
                    $supplier_requests = DB::table('supplier_request')->where('market_id',$user->market_id)->whereDate('created_at', $date)->where('is_deleted',0)->get();
                }else{
                    $supplier_requests = DB::table('supplier_request')->where('market_id',$user->market_id)->where('is_deleted',0)->get();
                  }
                
                foreach($supplier_requests as $key => $value) :
                    $supplier_requests[$key]->vendor_stock_detail = DB::table('supplier_request_detail')->where('supplier_request_id',$supplier_requests[$key]->id)->get();
                    
                    $short_link_check = $this->shortLinkRepository->where('link',url('').'/sr/'.$supplier_requests[$key]->id.'/1')->first();
                    if($short_link_check){
                        $short_link_code = $short_link_check->code;
                        $short_link = url('short/'.$short_link_code);
                        $supplier_requests[$key]->vendor_stock_invoice_url = $short_link;
                    } else {
                        $supplier_requests[$key]->vendor_stock_invoice_url = '';
                    }
                    $supplier_requests[$key]->address                  = $this->marketRepository->findWithoutFail($supplier_requests[$key]->market_id);
                    
                    foreach($supplier_requests[$key]->vendor_stock_detail as $k => $item) {
                        $product = $this->productRepository->where('id',$item->sr_detail_product_id)->first();
                        if($product) {
                            $product_image = $product->getMedia('image');
                        } else {
                            $product_image = ''; 
                        }
                        $supplier_requests[$key]->vendor_stock_detail[$k]->product_image = $product_image;
                    }
                    
                endforeach;
            } else {
                $supplier_requests = DB::table('supplier_request')->where('market_id','dummy')->get();
            }

        } catch (RepositoryException $e) {
            return $this->sendError($e->getMessage());
        }
        return $this->sendResponse($supplier_requests->toArray(), 'Supplier Request retrieved successfully');
    }


    /**
     * Display the specified resource.
     *
     * @param  \App\Models\supplierRequest  $supplierRequest
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    { 
        try{
            $api_token         = $request->api_token;
            $user              = DB::table('users')
                                    ->join('user_markets','users.id','=','user_markets.user_id')
                                    ->where('users.api_token',$api_token)
                                    ->first();
            if($user) { 
                $supplier_requests = DB::table('supplier_request')->where('id',$id)->get();
                if(count($supplier_requests) > 0) {
                    $supplier_requests[0]->vendor_stock_detail = DB::table('supplier_request_detail')->where('supplier_request_id',$supplier_requests[0]->id)->get();
                    
                    $short_link_check = $this->shortLinkRepository->where('link',url('').'/sr/'.$supplier_requests[0]->id.'/1')->first();
                    if($short_link_check){
                        $short_link_code = $short_link_check->code;
                        $short_link = url('short/'.$short_link_code);
                        $supplier_requests[0]->vendor_stock_invoice_url = $short_link;
                    } else {
                        $supplier_requests[0]->vendor_stock_invoice_url = '';
                    }
                    $supplier_requests[0]->address                  = $this->marketRepository->findWithoutFail($supplier_requests[0]->market_id);
                    
                    foreach($supplier_requests[0]->vendor_stock_detail as $k => $item) {
                        $product = $this->productRepository->where('id',$item->sr_detail_product_id)->first();
                        if($product) {
                            $product_image = $product->getMedia('image');
                        } else {
                            $product_image = ''; 
                        }
                        $supplier_requests[0]->vendor_stock_detail[$k]->product_image = $product_image;
                    }
                    
                } else {
                    $supplier_requests = DB::table('supplier_request')->where('market_id','dummy')->get();
                }
            } else {
                $supplier_requests = DB::table('supplier_request')->where('market_id','dummy')->get();
            }

        } catch (RepositoryException $e) {
            return $this->sendError($e->getMessage());
        }
        return $this->sendResponse($supplier_requests->toArray(), 'Supplier Request retrieved successfully');
    }
    
    
    
    /*public function store(Request $request)
    {   
        try{
            $api_token         = $request->api_token;
            $user              = DB::table('users')
                                    ->join('user_markets','users.id','=','user_markets.user_id')
                                    ->where('users.api_token',$api_token)
                                    ->first();
            if($user && $user->market_id > 0) {                        
                
                $sr_party              = $user->market_id;
                $sr_code               = setting('app_invoice_prefix').'-SR-'.(autoIncrementId('supplier_request'));
                $sr_date               = $request->sr_date;
                $valid_date            = $request->sr_valid_date;
                $description           = $request->sr_notes;
                //$taxable_amount        = 0;
                
                $supplir_request_data  = array(
                    'sr_code'                => $sr_code,
                    'sr_date'                => $sr_date,
                    'sr_valid_date'          => $valid_date,              
                    'market_id'              => $sr_party,
                    //'sr_taxable_amount'      => $taxable_amount,
                    'sr_notes'               => $description,
                    'created_at'             => date('Y-m-d H:i:s')
                );
                
                $count = 0;
                $products            = json_decode($request->products);
                if(isset($products) && count($products) > 0) {
                    $supplier_request_id = DB::table('supplier_request')->insertGetId($supplir_request_data);
                    if($supplier_request_id>0) {
    
                        $count++;
                        for($i=0; $i<count($products); $i++) {
                            $supplier_request_detail = array(
                                'supplier_request_id'         =>  $supplier_request_id,
                                'sr_detail_product_id'        =>  $products[$i]->product_id,
                                'sr_detail_product_name'      =>  $products[$i]->product_name,
                                'sr_detail_product_hsn_code'  =>  $products[$i]->product_hsn,
                                'sr_detail_mrp'               =>  $products[$i]->product_mrp,
                                'sr_detail_quantity'          =>  $products[$i]->product_quantity,
                                'sr_detail_unit'              =>  $products[$i]->product_unit,
                                //'sr_detail_price'           =>  $products[$i]->product_price[$i],
                                //'sr_amount'                 =>  $request->amount[$i],
                                'created_at'                  =>  date('Y-m-d H:i:s')
                            );
                            $insert_supplier_request_items = DB::table('supplier_request_detail')->insertGetId($supplier_request_detail);
                            if($insert_supplier_request_items > 0) {
                                $count++;
                            }
                        }
                        
                        //Notification
                            $supplier_request = $this->supplierRequestRepository->findWithoutFail($supplier_request_id);
                            Notification::send([$user], new NewSupplierRequest($supplier_request));
                        //Notification
                    
                        if($count > 0) {
                            $supplier_requests = DB::table('supplier_request')->where('market_id','dummy')->get();
                            $message = 'Successfully'; 
                        } else {
                            $supplier_requests = DB::table('supplier_request')->where('market_id','dummy')->get();
                            $message = 'Unsuccessfully'; 
                        }
    
                    } else {
                        $supplier_requests = DB::table('supplier_request')->where('market_id','dummy')->get();
                        $message = 'Unsuccessfully'; 
                    }
                } else {
                    $supplier_requests = DB::table('supplier_request')->where('market_id','dummy')->get();
                    $message = 'Unsuccessfully';    
                }    

            } else {
                $supplier_requests = DB::table('supplier_request')->where('market_id','dummy')->get();
                $message = 'Unsuccessfully';   
            }

        } catch (RepositoryException $e) {
            return $this->sendError($e->getMessage());
        }
        return $this->sendResponse($supplier_requests->toArray(), 'Vendor Supply Created'.$message);

    }*/
    
      public function store(Request $request)
    {   
      
        try{
            $input = $request->all();
            $api_token         = $request->api_token;
            
            $check_user        = DB::table('users')
                                    ->where('api_token',$api_token)
                                    ->count();

             if ($check_user==0) {
                return $this->sendError('User not found');
              }
            
            $user              = DB::table('users')
                                    ->join('user_markets','users.id','=','user_markets.user_id')
                                    ->where('users.api_token',$api_token)
                                    ->first();
       
            $check_market_user  = $this->marketRepository->where('id',$user->market_id)->where('type', 2)->count();
          
            if ($check_market_user==0) {
                return $this->sendError('Parties not found');
              }
          
            if($user && $user->market_id > 0) {                        
                
                $sr_party              = $user->market_id;
                $sr_code               = setting('app_invoice_prefix').'-SR-'.(autoIncrementId('supplier_request'));
                $sr_date               = $request->sr_date;
                $valid_date            = $request->sr_valid_date;
                $description           = $request->sr_notes;
                //$taxable_amount        = 0;
                
                $supplir_request_data  = array(
                    'sr_code'                => $sr_code,
                    'sr_date'                => $sr_date,
                    'sr_valid_date'          => $valid_date,              
                    'market_id'              => $sr_party,
                    //'sr_taxable_amount'      => $taxable_amount,
                    'sr_notes'               => $description,
                    'created_at'             => date('Y-m-d H:i:s')
                );
                
                $count = 0;
                $products            = json_decode($request->products);
           
                if(isset($products) && count($products) > 0) {
                    $supplier_request_id = DB::table('supplier_request')->insertGetId($supplir_request_data);
                    
                   
               /*if (array_key_exists("image", $input)) {
             $supplier_request = $this->supplierRequestRepository->findWithoutFail($supplier_request_id);
             
             if($supplier_request->hasMedia('image')){
                    $supplier_request->getFirstMedia('image')->delete();
                }
             
             $uuid = Str::uuid()->toString();
                $upload_data = array(
                    'field' => 'image',
                    'uuid'  => $uuid,
                    'file'  => $input['image']
                ); 
                $upload      = $this->uploadRepository->create($upload_data);
                $upload->addMedia($upload_data['file'])
                         ->withCustomProperties(['uuid' => $upload_data['uuid'], 'user_id' => $user->id])
                         ->toMediaCollection($upload_data['field']);
                
                $cacheUpload = $this->uploadRepository->getByUuid($upload_data['uuid']);
                $mediaItem = $cacheUpload->getMedia('image')->first();
                $mediaItem->copy($supplier_request, 'image');         
             }*/
                    
                    
                    if($supplier_request_id>0) {
    
                        $count++;
                        for($i=0; $i<count($products); $i++) {
                            $supplier_request_detail = array(
                                'supplier_request_id'         =>  $supplier_request_id,
                                'sr_detail_product_id'        =>  $products[$i]->product_id,
                                'sr_detail_product_name'      =>  $products[$i]->product_name,
                                'sr_detail_product_hsn_code'  =>  $products[$i]->product_hsn,
                                'sr_detail_mrp'               =>  $products[$i]->product_mrp,
                                'sr_detail_quantity'          =>  $products[$i]->product_quantity,
                                'sr_detail_unit'              =>  $products[$i]->product_unit,
                                //'sr_detail_price'           =>  $products[$i]->product_price[$i],
                                //'sr_amount'                 =>  $request->amount[$i],
                                'created_at'                  =>  date('Y-m-d H:i:s')
                            );
                            $insert_supplier_request_items = DB::table('supplier_request_detail')->insertGetId($supplier_request_detail);
                            if($insert_supplier_request_items > 0) {
                                $count++;
                            }
                        }
                        
                        //Notification
                            $supplier_request = $this->supplierRequestRepository->findWithoutFail($supplier_request_id);
                             $user_market      = DB::table('user_markets')->where('market_id',$supplier_request->market->id)->first();
                             $market_user      = $this->userRepository->findWithoutFail($user_market->user_id);
                            Notification::send([$market_user], new NewSupplierRequest($supplier_request));
                        //Notification
                    
                        if($count > 0) {
                            //$supplier_requests = DB::table('supplier_request')->where('market_id','dummy')->get();
                            $supplier_requests = $supplier_request;
                            $message = 'Successfully'; 
                        } else {
                            $supplier_requests = DB::table('supplier_request')->where('market_id','dummy')->get();
                            $message = 'Unsuccessfully'; 
                        }
    
                    } else {
                        $supplier_requests = DB::table('supplier_request')->where('market_id','dummy')->get();
                        $message = 'Unsuccessfully'; 
                    }
                } else {
                    $supplier_requests = DB::table('supplier_request')->where('market_id','dummy')->get();
                    $message = 'Unsuccessfully';    
                }    

            } else {
                $supplier_requests = DB::table('supplier_request')->where('market_id','dummy')->get();
                $message = 'Unsuccessfully';   
            }

        } catch (RepositoryException $e) {
            return $this->sendError($e->getMessage());
        }
        return $this->sendResponse($supplier_requests->toArray(), 'Vendor Supply Created '.$message);

    }
    
    
    public function update($id, Request $request)
    {   
        try{
            $api_token         = $request->api_token;
            $user              = DB::table('users')
                                    ->join('drivers','users.id','=','drivers.user_id')
                                    ->where('users.api_token',$api_token)
                                    ->first();
            if($user) {
                
                $supplier_request = $this->supplierRequestRepository->findWithoutFail($id);
                if($supplier_request) {
                    
                    $count = 0;
                    $products  = json_decode($request->products);
                    if(isset($products) && count($products) > 0) {
                        
                            $count++;
                            for($i=0; $i<count($products); $i++) {
                                
                                $total_amount = $products[$i]->product_price * $products[$i]->product_quantity;
                                
                                $supplier_request_detail = array(
                                    'sr_detail_quantity'=>  $products[$i]->product_quantity,
                                    'sr_detail_price'   =>  $products[$i]->product_price,
                                    'sr_amount'         =>  $total_amount,
                                    'sr_description'    =>  $products[$i]->product_description,
                                    'sr_product_image'  =>  $products[$i]->product_image,
                                    'updated_at'        =>  date('Y-m-d H:i:s')
                                );
                                $update_supplier_request_items = DB::table('supplier_request_detail')->where('id', $products[$i]->product_supply_id)->update($supplier_request_detail);
                                if($update_supplier_request_items > 0) {
                                    $count++;
                                }
                            }
                            
                            if($count > 0) {
                                
                                $supplier_requests = DB::table('supplier_request')->where('id',$id)->get();
                                $supplier_requests[0]->vendor_stock_detail = DB::table('supplier_request_detail')->where('supplier_request_id',$supplier_requests[0]->id)->get();
                    
                                $short_link_check = $this->shortLinkRepository->where('link',url('').'/sr/'.$supplier_requests[0]->id.'/1')->first();
                                if($short_link_check){
                                    $short_link_code = $short_link_check->code;
                                    $short_link = url('short/'.$short_link_code);
                                    $supplier_requests[0]->vendor_stock_invoice_url = $short_link;
                                } else {
                                    $supplier_requests[0]->vendor_stock_invoice_url = '';
                                }
                                $supplier_requests[0]->address                  = $this->marketRepository->findWithoutFail($supplier_requests[0]->market_id);
                                
                                foreach($supplier_requests[0]->vendor_stock_detail as $k => $item) {
                                    $product = $this->productRepository->where('id',$item->sr_detail_product_id)->first();
                                    if($product) {
                                        $product_image = $product->getMedia('image');
                                    } else {
                                        $product_image = ''; 
                                    }
                                    $supplier_requests[0]->vendor_stock_detail[$k]->product_image = $product_image;
                                }
                                
                                $message = 'Successfully'; 
                            } else {
                                $supplier_requests = DB::table('supplier_request')->where('market_id','dummy')->get();
                                $message = 'Unsuccessfully'; 
                            }
        
                        
                    } else {
                        $supplier_requests = DB::table('supplier_request')->where('market_id','dummy')->get();
                        $message = 'Unsuccessfully';    
                    } 
                    
                } else {
                    $supplier_requests = DB::table('supplier_request')->where('market_id','dummy')->get();
                    $message = 'Unsuccessfully';    
                }

            } else {
                $supplier_requests = DB::table('supplier_request')->where('market_id','dummy')->get();
                $message = 'Unsuccessfully';   
            }

        } catch (RepositoryException $e) {
            return $this->sendError($e->getMessage());
        }
        return $this->sendResponse($supplier_requests->toArray(), 'Vendor Supply Updated'.$message);

    }
    
    /*public function uploadVendorSupplyImage($id, Request $request) {
        
        $api_token         = $request->api_token;
        $user              = DB::table('users')
                                ->join('drivers','users.id','=','drivers.user_id')
                                ->where('users.api_token',$api_token)
                                ->first();

        $supplier_request = $this->supplierRequestRepository->findWithoutFail($id);

        if (empty($supplier_request)) {
            return $this->sendResponse([
                'error' => true,
                'code' => 404,
            ], 'Supplirer Request not found');
        }
        $input = $request->except(['password', 'api_token']);
        try {
            
            if($user) { 
               $uuid = Str::uuid()->toString();
             
                $upload_data = array(
                    'field' => 'vendor_supply_image',
                    'uuid'  => $uuid,
                    'file'  => $input['vendor_supply_image']
                ); 
                $upload      = $this->uploadRepository->create($upload_data);
                $upload->addMedia($upload_data['file'])
                         ->withCustomProperties(['uuid' => $upload_data['uuid'], 'user_id' => $user->user_id])
                         ->toMediaCollection($upload_data['field']);
                $result  = array('uuid' => $upload_data['uuid'],'upload' => $upload->getMedia('vendor_supply_image'));
                $message = 'Unsuccessfully';         
            } else {
                $result  = array();
                $message = 'Unsuccessfully';
            }
            //$cacheUpload = $this->uploadRepository->getByUuid($upload_data['uuid']);
            //$mediaItem = $cacheUpload->getMedia('avatar')->first();
            //$mediaItem->copy($user, 'avatar'); 
            
        } catch (ValidatorException $e) {
            return $this->sendError($e->getMessage(), 401);
        }

        return $this->sendResponse($result, __('lang.updated_successfully', ['operator' => __('lang.supplier_request')]));
    }*/


    public function uploadVendorSupplyImage($id, Request $request) {
        
        $api_token         = $request->api_token;
        $user              = DB::table('users')
                                ->join('drivers','users.id','=','drivers.user_id')
                                ->where('users.api_token',$api_token)
                                ->first();
        $product_id = $request->product_id;
        $supplier_request_id = $request->id;

        $supplier_request = DB::table('supplier_request_detail')->where('supplier_request_id',$supplier_request_id)->where('sr_detail_product_id',$product_id)->first();

        if (empty($supplier_request)) {
            return $this->sendResponse([
                'error' => true,
                'code' => 404,
            ], 'Supplirer Request not found');
        }
        $input = $request->except(['password', 'api_token']);
        try {
            
            if($user) { 
               $uuid = Str::uuid()->toString();
             
                $upload_data = array(
                    'field' => 'vendor_supply_image',
                    'uuid'  => $uuid,
                    'file'  => $input['vendor_supply_image']
                ); 
                $upload      = $this->uploadRepository->create($upload_data);
                $upload->addMedia($upload_data['file'])
                         ->withCustomProperties(['uuid' => $upload_data['uuid'], 'user_id' => $user->user_id])
                         ->toMediaCollection($upload_data['field']);
                $result  = array('uuid' => $upload_data['uuid'],'upload' => $upload->getMedia('vendor_supply_image'));
                //$cacheUpload = $this->uploadRepository->getByUuid($upload_data['uuid']);
                //$mediaItem = $cacheUpload->getMedia('vendor_supply_image')->first();
                //$mediaItem->copy($supplier_request, 'vendor_supply_image');    
                //$message = 'Unsuccessfully';         
            } else {
                $result  = array();
                $message = 'Unsuccessfully';
            }
            //$cacheUpload = $this->uploadRepository->getByUuid($upload_data['uuid']);
            //$mediaItem = $cacheUpload->getMedia('avatar')->first();
            //$mediaItem->copy($user, 'avatar'); 
            
        } catch (ValidatorException $e) {
            return $this->sendError($e->getMessage(), 401);
        }

        return $this->sendResponse($result, __('lang.updated_successfully', ['operator' => __('lang.supplier_request')]));
    }
    
    /**
     * Remove Media of Coupon
     * @param Request $request
     */
    public function removeMedia(Request $request)
    {
        $input  = $request->all();
        $supplier_request = $this->supplierRequestRepository->findWithoutFail($input['id']);
        if (empty($supplier_request)) {
            return $this->sendResponse([
                'error' => true,
                'code' => 404,
            ], 'Supplirer Request not found');
        }
        try {
            $removeMedia = $this->uploadRepository->clear($input['uuid']); 
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), 401);
        }
        return $this->sendResponse($removeMedia, 'Product Image Removed');
    }

    public function driverVendorStock(Request $request)
    {
        try{
            $api_token         = $request->api_token;
            $user              = DB::table('users')
                                    ->join('drivers','users.id','=','drivers.user_id')
                                    ->where('users.api_token',$api_token)
                                    ->first();
            if($user) {                        
                $supplier_requests = DB::table('supplier_request')->where('sr_driver',$user->user_id)->get();
                foreach($supplier_requests as $key => $value) :
                    $supplier_requests[$key]->vendor_stock_detail = DB::table('supplier_request_detail')->where('supplier_request_id',$supplier_requests[$key]->id)->get();
                    
                    $short_link_check = $this->shortLinkRepository->where('link',url('').'/sr/'.$supplier_requests[$key]->id.'/1')->first();
                    if($short_link_check){
                        $short_link_code = $short_link_check->code;
                        $short_link = url('short/'.$short_link_code);
                        $supplier_requests[$key]->vendor_stock_invoice_url = $short_link;
                    } else {
                        $supplier_requests[$key]->vendor_stock_invoice_url = '';
                    }
                    $supplier_requests[$key]->address                  = $this->marketRepository->findWithoutFail($supplier_requests[$key]->market_id);
                    
                    foreach($supplier_requests[$key]->vendor_stock_detail as $k => $item) {
                        $product = $this->productRepository->where('id',$item->sr_detail_product_id)->first();
                        if($product) {
                            $product_image = $product->getMedia('image');
                        } else {
                            $product_image = ''; 
                        }
                        $supplier_requests[$key]->vendor_stock_detail[$k]->product_image = $product_image;
                    }
                    
                endforeach;
            } else {
                $supplier_requests = DB::table('supplier_request')->where('market_id','dummy')->get();
            }

        } catch (RepositoryException $e) {
            return $this->sendError($e->getMessage());
        }
        return $this->sendResponse($supplier_requests->toArray(), 'Supplier Request retrieved successfully');
    }

    public function driverVendorStockShow(Request $request, $id) 
    { 
        try{
            $api_token         = $request->api_token;
            $user              = DB::table('users')
                                    ->join('drivers','users.id','=','drivers.user_id')
                                    ->where('users.api_token',$api_token)
                                    ->first();
            if($user) { 
                $supplier_requests = DB::table('supplier_request')->where('sr_driver',$user->user_id)->where('id',$id)->get();
                if(count($supplier_requests) > 0) {
                    $supplier_requests[0]->vendor_stock_detail = DB::table('supplier_request_detail')->where('supplier_request_id',$supplier_requests[0]->id)->get();
                    
                    $short_link_check = $this->shortLinkRepository->where('link',url('').'/sr/'.$supplier_requests[0]->id.'/1')->first();
                    if($short_link_check){
                        $short_link_code = $short_link_check->code;
                        $short_link = url('short/'.$short_link_code);
                        $supplier_requests[0]->vendor_stock_invoice_url = $short_link;
                    } else {
                        $supplier_requests[0]->vendor_stock_invoice_url = '';
                    }
                    $supplier_requests[0]->address                  = $this->marketRepository->findWithoutFail($supplier_requests[0]->market_id);
                    
                    foreach($supplier_requests[0]->vendor_stock_detail as $k => $item) {
                        $product = $this->productRepository->where('id',$item->sr_detail_product_id)->first();
                        if($product) {
                            $product_image = $product->getMedia('image');
                        } else {
                            $product_image = ''; 
                        }
                        $supplier_requests[0]->vendor_stock_detail[$k]->product_image = $product_image;
                    }
                    
                } else {
                    $supplier_requests = DB::table('supplier_request')->where('market_id','dummy')->get();
                }
            } else {
                $supplier_requests = DB::table('supplier_request')->where('market_id','dummy')->get();
            }

        } catch (RepositoryException $e) {
            return $this->sendError($e->getMessage());
        }
        return $this->sendResponse($supplier_requests->toArray(), 'Supplier Request retrieved successfully');
    }
    
    
    public function driverVendorStockUpdate(Request $request, $id) 
    { 
        try{
            $api_token         = $request->api_token;
            $user              = DB::table('users')
                                    ->join('drivers','users.id','=','drivers.user_id')
                                    ->where('users.api_token',$api_token)
                                    ->first();
            if($user) { 
                
                $validate = DB::table('supplier_request')->where('sr_driver',$user->user_id)->where('id',$id)->get();
                if(count($validate) > 0) {
                    $input = $request->all(); 
                    $supplier_requests  = $this->supplierRequestRepository->update($input,$id);
                    
                    if($supplier_requests) {
                        if ($input['sr_status']!='' && $input['sr_status'] != $validate[0]->sr_status) {
                            $user_market    = DB::table('user_markets')->where('market_id',$supplier_requests->market->id)->first();
                            $market_user    = $this->userRepository->findWithoutFail($user_market->user_id);
                            Notification::send([$market_user], new StatusChangedSupplierRequest($supplier_requests));
                        }
                    }
                }
                
            } else {
                $supplier_requests  = DB::table('supplier_request')->where('market_id','dummy')->get();
            }

        } catch (RepositoryException $e) {
            return $this->sendError($e->getMessage());
        }
        return $this->sendResponse($supplier_requests->toArray(), 'Supplier Request updated successfully');
    }
    
    public function vendorStockDelete(Request $request, $id) 
    {  
        try{
            
            $input['updated_by'] = auth()->user()->id;
            $input['is_deleted'] = 1;
            $result = $this->supplierRequestRepository->update($input, $id);
           
            //$result = $this->supplierRequestRepository->delete($id);
            
            if($result) {
            //$delete_sr_items = DB::table('supplier_request_detail')->where('supplier_request_id', '=', $id)->delete();
            $delete_sr_items =  DB::table('supplier_request_detail')->update($input, $id);
          }

        } catch (RepositoryException $e) {
            return $this->sendError($e->getMessage());
        }
        return $this->sendResponse(1, 'Supplier Request deleted successfully');
    }
    
    public function getCancelVendorStock(Request $request)
    {
         try {
            $supplier_requests = $this->supplierRequestRepository->where('is_deleted',1)->get();
            
            foreach($supplier_requests as $key => $value) :
                    $supplier_requests[$key]->vendor_stock_detail = DB::table('supplier_request_detail')->where('supplier_request_id',$supplier_requests[$key]->id)->get();
                    
                    $short_link_check = $this->shortLinkRepository->where('link',url('').'/sr/'.$supplier_requests[$key]->id.'/1')->first();
                    if($short_link_check){
                        $short_link_code = $short_link_check->code;
                        $short_link = url('short/'.$short_link_code);
                        $supplier_requests[$key]->vendor_stock_invoice_url = $short_link;
                    } else {
                        $supplier_requests[$key]->vendor_stock_invoice_url = '';
                    }
                    $supplier_requests[$key]->address                  = $this->marketRepository->findWithoutFail($supplier_requests[$key]->market_id);
                    
                    foreach($supplier_requests[$key]->vendor_stock_detail as $k => $item) {
                        $product = $this->productRepository->where('id',$item->sr_detail_product_id)->first();
                        if($product) {
                            $product_image = $product->getMedia('image');
                        } else {
                            $product_image = ''; 
                        }
                        $supplier_requests[$key]->vendor_stock_detail[$k]->product_image = $product_image;
                    }
                    
                endforeach;

            if (empty($supplier_requests)) {
                return $this->sendError(__('lang.not_found', ['operator' => __('lang.order')]));
            }

        } catch (ValidatorException $e) {
            return $this->sendError($e->getMessage());
        }
         return $this->sendResponse($supplier_requests,'Cancelled Vendor Stock retrieved successfully');
    }
    
     public function getDriverSupplies(Request $request) {
     
     
      $user = $this->userRepository->where('api_token', $request->input('api_token'))->first();
        if (!$user) {
            return $this->sendError('User not found', 401);
        }
        try {
          $driver_id = $user->id;

           $getSuppliers = DB::table('supplier_request')->join("user_markets", "supplier_request.market_id", "=", "user_markets.market_id")->where('sr_driver', $driver_id)->get();
           $distance_in_km = 0;
           foreach($getSuppliers as $val)
           {
               
              $supplier_requests_count  = DB::table('delivery_addresses')->where('user_id',$val->user_id)->count();
              
              if($supplier_requests_count>0){
              
              $supplier_requests  = DB::table('delivery_addresses')->where('user_id',$val->user_id)->get();
               
               foreach($supplier_requests as $vals)
               {
                  
               $latitude  = $vals->latitude;
               $longitude = $vals->longitude;
                
               $distance_data = file_get_contents('https://maps.googleapis.com/maps/api/distancematrix/json?origins='.setting('app_store_latitude').','.setting('app_store_longitude').'&destinations=side_of_road:'.$latitude.','.$longitude.'&mode=driving&key='.setting('google_api_key').'');
               $distance_arr = json_decode($distance_data);
                
                 $distance_result   = $distance_arr->rows[0]->elements[0];
                 $distance_in_meter = $distance_result->distance->value;
                 $distance_in_km    += $distance_in_meter / 1000;
               }
                 
              }
              
           }
           
           $total_delivery_tips = $this->deliveryTipsRepository->where('user_id', $driver_id)->sum('amount');
           
           $data['total_delivery_distance']=$distance_in_km;
           $data['total_delivery_tips']=$total_delivery_tips;
            
        } catch (\Exception $e) {
            $this->sendError($e->getMessage(), 401);
        }
        return $this->sendResponse($data , 'Driver suppliers details retrieved successfully');
    }
    
    

}
