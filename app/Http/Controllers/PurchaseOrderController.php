<?php

namespace App\Http\Controllers;

use App\Models\PurchaseOrder;
use App\DataTables\PurchaseOrderDataTable;
use App\Http\Requests;
use App\Http\Requests\CreatePurchaseOrderRequest;
use App\Http\Requests\UpdatePurchaseOrderRequest;
use App\Repositories\PurchaseOrderRepository;
use App\Repositories\PurchaseOrderItemsRepository;
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
use App\Mail\SalesInvoiceMail;
use PaytmWallet;
use Illuminate\Support\Facades\Input;

use Notification;
use App\Notifications\PurchaseOrderNotification;

class PurchaseOrderController extends Controller
{   
    /** @var  MarketRepository */
    private $marketRepository;

    /** @var  ProductRepository */
    private $productRepository;

    /** @var  PurchaseOrderRepository */
    private $purchaseOrderRepository;

    /** @var  purchaseOrderItemsRepository */
    private $purchaseOrderItemsRepository;

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

    public function __construct(MarketRepository $marketRepo, PurchaseOrderRepository $purchaseOrderRepo, PurchaseOrderItemsRepository $purchaseOrderItemsRepo,  CustomFieldRepository $customFieldRepo , UploadRepository $uploadRepo, ProductRepository $productRepo, InventoryRepository $inventoryRepo, UserRepository $userRepo, DeliveryAddressRepository $deliveryAddressRepo, TransactionRepository $transactionRepo, ShortLinkRepository $shortLinkRepo, PaymentModeRepository $paymentModeRepo, DeliveryZonesRepository $deliveryZonesRepo, PartyTypesRepository $partyTypesRepo, PartySubTypesRepository $partySubTypesRepo, PartyStreamRepository $partyStreamRepo)
    {
        parent::__construct();
        $this->marketRepository             = $marketRepo;
        $this->productRepository            = $productRepo;
        $this->purchaseOrderRepository      = $purchaseOrderRepo;
        $this->purchaseOrderItemsRepository = $purchaseOrderItemsRepo;
        $this->inventoryRepository          = $inventoryRepo;
        $this->transactionRepository        = $transactionRepo;
        $this->userRepository               = $userRepo;
        $this->deliveryAddressRepository    = $deliveryAddressRepo;
        $this->customFieldRepository        = $customFieldRepo;
        $this->uploadRepository             = $uploadRepo;
        $this->shortLinkRepository          = $shortLinkRepo;
        $this->paymentModeRepository        = $paymentModeRepo; 
        $this->deliveryZonesRepository      = $deliveryZonesRepo;
        $this->partyTypesRepository         = $partyTypesRepo;
        $this->partySubTypesRepository      = $partySubTypesRepo;
        $this->partyStreamRepository        = $partyStreamRepo;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(PurchaseOrderDatatable $purchaseOrderDataTable)
    {
        $purchase_order = $purchaseOrderDataTable;
        if(Request('start_date')!='' && Request('end_date')!='' ) {
           $purchase_order->with('start_date',Request('start_date'))->with('end_date',Request('end_date'));  
        }
        return $purchase_order->render('purchase_order.index');
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

        $products    = $this->productRepository->get();
        $purchase_no = setting('app_invoice_prefix').'-PO-'.(autoIncrementId('purchase_order'));
        return view('purchase_order.create',compact('users','products','purchase_no','payment_types','party_types','party_streams','party_sub_types'));
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
     
    public function store(CreatePurchaseOrderRequest $request)
    {
        $input = $request->all();
        try {
            $input['created_by'] = auth()->user()->id;
            $purchase_order      = $this->purchaseOrderRepository->create($input);
            
            if($purchase_order) {
                for($i=0; $i<count($input['product_id']); $i++) :
                    $items = array(
                        'purchase_order_id' => $purchase_order->id,
                        'product_id'        => $input['product_id'][$i],
                        'product_name'      => $input['product_name'][$i],
                        'product_hsn_code'  => $input['product_hsn_code'][$i],
                        'mrp'               => $input['mrp'][$i],
                        'quantity'          => $input['quantity'][$i],
                        'unit'              => $input['unit'][$i],
                        'unit_price'        => $input['unit_price'][$i],
                        'discount'          => $input['discount'][$i],
                        'discount_amount'   => $input['discount_amount'][$i],
                        'tax'               => $input['tax'][$i],
                        'tax_amount'        => $input['tax_amount'][$i],
                        'amount'            => $input['amount'][$i],
                        'created_by'        => auth()->user()->id,
                    );
                    $invoice_item = $this->purchaseOrderItemsRepository->create($items);    

                endfor;

                $this->emailtoparty($purchase_order->id);

            }
            if(isset($input['image'][0]) && $input['image'][0]){
                $cacheUpload = $this->uploadRepository->getByUuid($input['image'][0]);
                $mediaItem = $cacheUpload->getMedia('image')->first();
                $mediaItem->copy($purchase_order, 'image');
            }

        } catch (ValidatorException $e) {
            Flash::error($e->getMessage());
        }
        Flash::success(__('Save successfully',['operator' => __('Purchase Order')]));
        return redirect(route('purchaseOrder.index'));

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\PurchaseOrder  $PurchaseOrder
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        $purchase_order = $this->purchaseOrderRepository->with('items')->with('items.uom')->with('items.product')->findWithoutFail($id);
        if (empty($purchase_order)) {
            Flash::error(__('Not Found',['operator' => __('Purchase Order')]));
            return redirect(route('purchaseOrder.index'));
        }
        if($request->ajax()) {
            return ['status' => 'success', 'data' => $purchase_order]; 
        }
        return view('purchase_order.show',compact('purchase_order'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\PurchaseOrder  $PurchaseOrder
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {   
        $purchase_order = $this->purchaseOrderRepository->findWithoutFail($id);
        if (empty($purchase_order)) {
            Flash::error(__('Not Found',['operator' => __('Purchase Order')]));
            return redirect(route('purchaseOrder.index'));
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

        return view('purchase_order.edit',compact('users','products','payment_types','party_types','party_streams','party_sub_types', 'purchase_order'));

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\PurchaseOrder  $PurchaseOrder
     * @return \Illuminate\Http\Response
     */
    public function update($id, Request $request)
    {  
        $purchase_order_old = $this->purchaseOrderRepository->findWithoutFail($id);
        if (empty($purchase_order_old)) {
            Flash::error(__('Not Found',['operator' => __('Purchase Order')]));
            return redirect(route('purchaseOrder.index'));
        }

        $input = $request->all();

        if($request->ajax()) {
            if($input['type']=='status-update') {
                $purchase_order = $this->purchaseOrderRepository->findWithoutFail($id);
                if(!empty($purchase_order)) {
                    $update = $this->purchaseOrderRepository->update(['status'=>$input['status']],$input['id']);
                    return $update;
                }
            }   
        }
        
        try {
            $input['updated_by'] = auth()->user()->id;
            $purchase_order      = $this->purchaseOrderRepository->update($input,$id);
            
            if($purchase_order) {
                for($i=0; $i<count($input['product_id']); $i++) :
                    $items = array(
                        'purchase_order_id' => $purchase_order->id,
                        'product_id'        => $input['product_id'][$i],
                        'product_name'      => $input['product_name'][$i],
                        'product_hsn_code'  => $input['product_hsn_code'][$i],
                        'mrp'               => $input['mrp'][$i],
                        'quantity'          => $input['quantity'][$i],
                        'unit'              => $input['unit'][$i],
                        'unit_price'        => $input['unit_price'][$i],
                        'discount'          => $input['discount'][$i],
                        'discount_amount'   => $input['discount_amount'][$i],
                        'tax'               => $input['tax'][$i],
                        'tax_amount'        => $input['tax_amount'][$i],
                        'amount'            => $input['amount'][$i],
                        'created_by'        => auth()->user()->id,
                    );
                    if(isset($input['invoice_item_id'][$i]) && $input['invoice_item_id'][$i] > 0) {
                        $invoice_item = $this->purchaseOrderItemsRepository->update($items,$input['invoice_item_id'][$i]);
                    } else {
                        $invoice_item = $this->purchaseOrderItemsRepository->create($items);
                    }    

                endfor;   

                if(isset($input['delete_item_id']) && count($input['delete_item_id']) > 0) {
                    foreach($input['delete_item_id'] as $deleteid) {
                        $this->purchaseOrderItemsRepository->delete($deleteid);
                    }
                } 
            }
            if(isset($input['image'][0]) && $input['image'][0]){
                $cacheUpload = $this->uploadRepository->getByUuid($input['image'][0]);
                $mediaItem = $cacheUpload->getMedia('image')->first();
                $mediaItem->copy($purchase_order, 'image');
            }

        } catch (ValidatorException $e) {
            Flash::error($e->getMessage());
        }
        Flash::success(__('Save successfully',['operator' => __('Purchase Order')]));
        return redirect(route('purchaseOrder.index'));
    
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\PurchaseOrder  $PurchaseOrder
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $purchase_order = $this->purchaseOrderRepository->with('market')->with('items')->findWithoutFail($id);
        if (empty($purchase_order)) {
            Flash::error('Purchase Order not found');
            return redirect(route('purchaseOrder.index'));
        }

        $this->purchaseOrderRepository->delete($id);

        if(count($purchase_order->items) > 0) {
            foreach($purchase_order->items as $item) {
                $this->productStockupdate($item->product->id);
            }
        }
        $this->partyBalanceUpdate($purchase_order->market->id);
        $this->calculateRewardBalance($purchase_order->market->id);

        Flash::success(__('Deleted successfully',['operator' => __('Purchase Order')]));
        return redirect(route('purchaseOrder.index'));
    }


    public function print($id,$type,$view_type,Request $request)
    {   
        $purchase_order = $this->purchaseOrderRepository->with('market')->with('items')->findWithoutFail($id);
        if (empty($purchase_order)) {
            Flash::error('Purchase Order not found');
            return redirect(route('purchaseOrder.index'));
        }
        $words    = $this->amounttoWords($purchase_order->total);
        $currency = '<span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span>';
        $pdf      = PDF::loadView('purchase_order.print', compact('purchase_order','type','currency','words'));
        $filename = $purchase_order->code.'-'.$purchase_order->market->name.'.pdf';
        
        if($view_type=='print') {
            return $pdf->stream($filename);
        } elseif($view_type=='download') {
            return $pdf->download($filename);
        } else {
            return view('purchase_order.thermal_print', compact('purchase_order','type','currency','words'));
        }
    }


    public function emailtoparty($id) {

        $purchase_order = $this->purchaseOrderRepository->findWithoutFail($id);
        if (empty($purchase_order)) {
            Flash::error('Purchase Order not found');
            return redirect(route('purchaseOrder.index'));
        }
        $type       = 1;
        $words      = $this->amounttoWords($purchase_order->total);
        $currency   = '<span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span>';
        $pdf        = PDF::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true, 'dpi' => 100])->loadView('purchase_order.print',compact('purchase_order','type','words','currency'));

        $invoice_url = url(base64_encode($purchase_order->id).'/PurchaseOrder'); 
        $message     = "Hi ".$purchase_order->market->name.",<br>

                        Here's Purchase Order #".$purchase_order->code." for ".number_format($purchase_order->total,2,'.','').".<br>

                        View your bill online: ".url(base64_encode($purchase_order->id).'/PurchaseOrder')."<br>

                        From your online bill you can print a PDF or create a free login and view your outstanding bills <br>.

                        If you have any questions, please let us know. <br>";

        //Notification
            $notification_data = [
                'greeting'    => "New Purchase Order Generated!",
                'body'        => $message,
                'thanks'      => 'Thank you',
                'pdf_file'    => $pdf,
                'filename'    => 'Purchase Order #'.$purchase_order->code.'.pdf',
                'datas'       => array(
                    'title'   => 'Purchase Order '.$purchase_order->code.' from '.setting('app_name').' for '.$purchase_order->market->name, //"New PO Generated!",
                    'message' => $message
                )
            ];
            $notify = $purchase_order->market->user->notify(new PurchaseOrderNotification($notification_data));  
            $update = $this->purchaseOrderRepository->update(['status'=>'sent'],$purchase_order->id);

            $purchase_order->market->activity()->create([
                'market_id'  => $purchase_order->market->id,
                'action'     => 'Purchase Order Sent',
                'notes'      => 'Purchase Order '.$purchase_order->code.' sent to '.$purchase_order->market->user->email,
                'created_by' => auth()->user()->id
            ]);

        //Notification
            
    }


    public function frontView(Request $request, $id) {

        $id                = base64_decode($id);
        $purchase_order    = $this->purchaseOrderRepository->with('market')->with('items')->findWithoutFail($id);
        if (empty($purchase_order)) {
            Flash::error('Purchase Order not found');
            return redirect(route('purchaseOrder.index'));
        }
        $words    = $this->amounttoWords($purchase_order->total);
        $currency = '<span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span>';
        return view('purchase_order.front_end_invoice', compact('purchase_order','currency','words'));
    }


    public function DownloadPDF(Request $request, $id) {
        $id             = base64_decode($id);
        $type           = 1;
        $purchase_order = $this->purchaseOrderRepository->with('market')->with('items')->findWithoutFail($id);
        if (empty($purchase_order)) {
            Flash::error('Purchase Order not found');
            return redirect(route('purchaseOrder.index'));
        }
        $words    = $this->amounttoWords($purchase_order->total);
        $currency = '<span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span>';
        $pdf      = PDF::loadView('purchase_order.print', compact('purchase_order','type','currency','words'));
        $filename = $purchase_order->code.'-'.$purchase_order->market->name.'.pdf';
        return $pdf->download($filename);
    }

    public function statusUpdate(Request $request) {
        $id             = $request->id;
        $purchase_order = $this->purchaseOrderRepository->findWithoutFail($id);
        if (empty($purchase_order)) {
            Flash::error(__('Not Found',['operator' => __('Purchase Order')]));
            return redirect()->back();
        }
        $purchase_order = $this->purchaseOrderRepository->update([
            'status' => $request->status 
        ],$id);
        if ($request->ajax()) {
            return $purchase_order;
        }
    }

}
