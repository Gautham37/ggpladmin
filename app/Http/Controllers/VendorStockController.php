<?php

namespace App\Http\Controllers;

use App\Models\VendorStock;
use App\DataTables\VendorStockDataTable;
use App\Http\Requests;
use App\Http\Requests\CreateVendorStockRequest;
use App\Http\Requests\UpdateVendorStockRequest;
use App\Repositories\VendorStockRepository;
use App\Repositories\VendorStockItemsRepository;
use App\Repositories\InventoryRepository;
use App\Repositories\TransactionRepository;
use App\Repositories\ProductRepository;
use App\Repositories\CustomFieldRepository;
use App\Repositories\UploadRepository;
use App\Repositories\MarketRepository;
use App\Repositories\UserRepository;
use App\Repositories\DeliveryAddressRepository;
use App\Repositories\ShortLinkRepository;
use App\Repositories\PaymentModeRepository;
use App\Repositories\DeliveryZonesRepository;
use App\Repositories\PartyTypesRepository;
use App\Repositories\PartySubTypesRepository;
use App\Repositories\PartyStreamRepository;
use Flash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Prettus\Validator\Exceptions\ValidatorException;
use DB;
use PDF;
use CustomHelper;
use PaytmWallet;
use Illuminate\Support\Facades\Input;


class VendorStockController extends Controller
{   
    /** @var  MarketRepository */
    private $marketRepository;

    /** @var  ProductRepository */
    private $productRepository;

    /** @var  VendorStockRepository */
    private $vendorStockRepository;

    /** @var  VendorStockItemsRepository */
    private $vendorStockItemsRepository;

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
    
    /** @var  PaymentModeRepository */
    private $paymentModeRepository;
    
    /** @var  DeliveryZonesRepository */
    private $deliveryZonesRepository;

    /** @var  PartyTypesRepository */
    private $partyTypesRepository;
    
    /** @var  PartySubTypesRepository */
    private $partySubTypesRepository;
    
    /** @var  PartyStreamRepository */
    private $partyStreamRepository;

    /**
     * @var CustomFieldRepository
     */
    private $customFieldRepository;

    /**
  * @var UploadRepository
  */
    private $uploadRepository;

    public function __construct(MarketRepository $marketRepo, VendorStockRepository $vendorStockRepo, VendorStockItemsRepository $vendorStockItemsRepo,  CustomFieldRepository $customFieldRepo , UploadRepository $uploadRepo, ProductRepository $productRepo, InventoryRepository $inventoryRepo, UserRepository $userRepo, DeliveryAddressRepository $deliveryAddressRepo, TransactionRepository $transactionRepo, ShortLinkRepository $shortLinkRepo, PaymentModeRepository $paymentModeRepo, DeliveryZonesRepository $deliveryZonesRepo, PartyTypesRepository $partyTypesRepo, PartySubTypesRepository $partySubTypesRepo, PartyStreamRepository $partyStreamRepo)
    {
        parent::__construct();
        $this->marketRepository           = $marketRepo;
        $this->productRepository          = $productRepo;
        $this->vendorStockRepository      = $vendorStockRepo;
        $this->vendorStockItemsRepository = $vendorStockItemsRepo;
        $this->inventoryRepository        = $inventoryRepo;
        $this->transactionRepository      = $transactionRepo;
        $this->userRepository             = $userRepo;
        $this->deliveryAddressRepository  = $deliveryAddressRepo;
        $this->customFieldRepository      = $customFieldRepo;
        $this->uploadRepository           = $uploadRepo;
        $this->shortLinkRepository        = $shortLinkRepo;
        $this->paymentModeRepository      = $paymentModeRepo; 
        $this->deliveryZonesRepository    = $deliveryZonesRepo;
        $this->partyTypesRepository       = $partyTypesRepo;
        $this->partySubTypesRepository    = $partySubTypesRepo;
        $this->partyStreamRepository      = $partyStreamRepo;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(VendorStockDatatable $vendorStockDataTable)
    {
        $vendor_stock = $vendorStockDataTable;
        if(Request('start_date')!='' && Request('end_date')!='' ) {
           $vendor_stock->with('start_date',Request('start_date'))->with('end_date',Request('end_date'));  
        }
        return $vendor_stock->render('vendor_stock.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {   
        $users  = $this->marketRepository->select('id', DB::raw("concat(name, ' ', mobile, ' ', code) as name"))->pluck('name', 'id');
        $users->prepend("Please Select",null);
        $payment_types = $this->paymentModeRepository->pluck('name','id');
        
        $party_types  = $this->partyTypesRepository->pluck('name', 'id');
        $party_types->prepend("Please Select",null);
        
        $party_streams = $this->partyStreamRepository->pluck('name', 'id');
        $party_streams->prepend("Please Select",null);

        $party_sub_types  = [null => "Please Select"];

        $products        = $this->productRepository->get();
        $vendor_stock_no = setting('app_invoice_prefix').'-VS-'.(autoIncrementId('vendor_stock'));
        return view('vendor_stock.create',compact('users','products','vendor_stock_no','payment_types','party_types','party_streams','party_sub_types'));
    } 

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function paytmResult(Request $request) {
        
        dd($request);
        
    }
     
    public function store(CreateVendorStockRequest $request)
    {
        $input = $request->all();
        try {
            $input['created_by'] = auth()->user()->id;
            $vendor_stock        = $this->vendorStockRepository->create($input);
            
            if($vendor_stock) {
                for($i=0; $i<count($input['product_id']); $i++) :
                    $items = array(
                        'vendor_stock_id'   => $vendor_stock->id,
                        'product_id'        => $input['product_id'][$i],
                        'product_name'      => $input['product_name'][$i],
                        'product_hsn_code'  => $input['product_hsn_code'][$i],
                        'mrp'               => $input['mrp'][$i],
                        'quantity'          => $input['quantity'][$i],
                        'unit'              => $input['unit'][$i],
                        'unit_price'        => $input['unit_price'][$i],
                        //'discount'          => $input['discount'][$i],
                        //'discount_amount'   => $input['discount_amount'][$i],
                        //'tax'               => $input['tax'][$i],
                        //'tax_amount'        => $input['tax_amount'][$i],
                        'amount'            => $input['amount'][$i],
                        'created_by'        => auth()->user()->id,
                    );
                    $invoice_item = $this->vendorStockItemsRepository->create($items);    
                endfor;

            }
            if(isset($input['image'][0]) && $input['image'][0]){
                $cacheUpload = $this->uploadRepository->getByUuid($input['image'][0]);
                $mediaItem = $cacheUpload->getMedia('image')->first();
                $mediaItem->copy($vendor_stock, 'image');
            }

        } catch (ValidatorException $e) {
            Flash::error($e->getMessage());
        }
        Flash::success(__('Save successfully',['operator' => __('Vendor Stock')]));
        return redirect(route('vendorStock.index'));

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\VendorStock  $VendorStock
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        $vendor_stock = $this->vendorStockRepository->with('items')->with('items.uom')->with('items.product')->findWithoutFail($id);
        if (empty($vendor_stock)) {
            Flash::error(__('Not Found',['operator' => __('Vendor Invoice')]));
            return redirect(route('vendorStock.index'));
        }
        if($request->ajax()) {
            return ['status' => 'success', 'data' => $vendor_stock]; 
        }
        return view('vendor_stock.show',compact('vendor_stock'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\VendorStock  $VendorStock
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {   
        $vendor_stock = $this->vendorStockRepository->findWithoutFail($id);
        if (empty($vendor_stock)) {
            Flash::error(__('Not Found',['operator' => __('Vendor Invoice')]));
            return redirect(route('vendorStock.index'));
        }

        $users  = $this->marketRepository->select('id', DB::raw("concat(name, ' ', mobile, ' ', code) as name"))->pluck('name', 'id');
        $users->prepend("Please Select",null);
        $payment_types = $this->paymentModeRepository->pluck('name','id');
        
        $party_types  = $this->partyTypesRepository->pluck('name', 'id');
        $party_types->prepend("Please Select",null);
        
        $party_streams = $this->partyStreamRepository->pluck('name', 'id');
        $party_streams->prepend("Please Select",null);

        $party_sub_types  = [null => "Please Select"];
        $products   = $this->productRepository->get();

        return view('vendor_stock.edit',compact('users','products','payment_types','party_types','party_streams','party_sub_types', 'vendor_stock'));

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\VendorStock  $VendorStock
     * @return \Illuminate\Http\Response
     */
    public function update($id, Request $request)
    {  
        $vendor_stock_old = $this->vendorStockRepository->findWithoutFail($id);
        if (empty($vendor_stock_old)) {
            Flash::error(__('Not Found',['operator' => __('Vendor Invoice')]));
            return redirect(route('vendorStock.index'));
        }

        $input = $request->all();

        if($request->ajax()) {
            if($input['type']=='status-update') {
                $vendor_stock = $this->vendorStockRepository->findWithoutFail($id);
                if(!empty($vendor_stock)) {
                    $update = $this->vendorStockRepository->update(['status'=>$input['status']],$input['id']);
                    return $update;
                }
            }   
        }

        try {
            $input['updated_by'] = auth()->user()->id;
            $vendor_stock        = $this->vendorStockRepository->update($input,$id);
            
            if($vendor_stock) {
                for($i=0; $i<count($input['product_id']); $i++) :
                    $items = array(
                        'vendor_stock_id'   => $vendor_stock->id,
                        'product_id'        => $input['product_id'][$i],
                        'product_name'      => $input['product_name'][$i],
                        'product_hsn_code'  => $input['product_hsn_code'][$i],
                        'mrp'               => $input['mrp'][$i],
                        'quantity'          => $input['quantity'][$i],
                        'unit'              => $input['unit'][$i],
                        'unit_price'        => $input['unit_price'][$i],
                        //'discount'          => $input['discount'][$i],
                        //'discount_amount'   => $input['discount_amount'][$i],
                        //'tax'               => $input['tax'][$i],
                        //'tax_amount'        => $input['tax_amount'][$i],
                        'amount'            => $input['amount'][$i],
                        'created_by'        => auth()->user()->id,
                    );
                    if(isset($input['invoice_item_id'][$i]) && $input['invoice_item_id'][$i] > 0) {
                        $invoice_item = $this->vendorStockItemsRepository->update($items,$input['invoice_item_id'][$i]);
                    } else {
                        $invoice_item = $this->vendorStockItemsRepository->create($items);
                    }    
                endfor;

                if(isset($input['delete_item_id']) && count($input['delete_item_id']) > 0) {
                    foreach($input['delete_item_id'] as $deleteid) {
                        $this->vendorStockItemsRepository->delete($deleteid);
                    }
                }      

            }
            if(isset($input['image'][0]) && $input['image'][0]){
                $cacheUpload = $this->uploadRepository->getByUuid($input['image'][0]);
                $mediaItem = $cacheUpload->getMedia('image')->first();
                $mediaItem->copy($vendor_stock, 'image');
            }

        } catch (ValidatorException $e) {
            Flash::error($e->getMessage());
        }
        Flash::success(__('Save successfully',['operator' => __('Vendor Invoice')]));
        return redirect(route('vendorStock.index'));
    
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\VendorStock  $vendorStock
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $vendor_stock = $this->vendorStockRepository->with('market')->with('items')->findWithoutFail($id);
        if (empty($vendor_stock)) {
            Flash::error('Vendor Invoice not found');
            return redirect(route('vendorStock.index'));
        }

        $this->vendorStockRepository->delete($id);

        Flash::success(__('Deleted successfully',['operator' => __('Vendor Invoice')]));
        return redirect(route('vendorStock.index'));
    }


    public function print($id,$type,$view_type,Request $request)
    {   
        $vendor_stock = $this->vendorStockRepository->with('market')->with('items')->findWithoutFail($id);
        if (empty($vendor_stock)) {
            Flash::error('Vendor Stock not found');
            return redirect(route('vendorStock.index'));
        }
        $words    = $this->amounttoWords($vendor_stock->total);
        $currency = '<span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span>';
        $pdf      = PDF::loadView('vendor_stock.print', compact('vendor_stock','type','currency','words'));
        $filename = $vendor_stock->code.'-'.$vendor_stock->market->name.'.pdf';
        
        if($view_type=='print') {
            return $pdf->stream($filename);
        } elseif($view_type=='download') {
            return $pdf->download($filename);
        } else {
            return view('vendor_stock.thermal_print', compact('vendor_stock','type','currency','words'));
        }
    }

}
