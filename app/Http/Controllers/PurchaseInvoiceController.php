<?php

namespace App\Http\Controllers;

use App\Models\PurchaseInvoice;
use App\DataTables\PurchaseInvoiceDataTable;
use App\Http\Requests;
use App\Http\Requests\CreatePurchaseInvoiceRequest;
use App\Http\Requests\UpdatePurchaseInvoiceRequest;
use App\Repositories\PurchaseInvoiceRepository;
use App\Repositories\PurchaseInvoiceItemsRepository;
use App\Repositories\PurchaseOrderRepository;
use App\Repositories\VendorStockRepository;
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
use App\Notifications\PurchaseInvoiceNotification;

class PurchaseInvoiceController extends Controller
{   
    /** @var  MarketRepository */
    private $marketRepository;

    /** @var  ProductRepository */
    private $productRepository;

    /** @var  PurchaseInvoiceRepository */
    private $purchaseInvoiceRepository;

    /** @var  PurchaseInvoiceItemsRepository */
    private $purchaseInvoiceItemsRepository;

    /** @var  PurchaseOrderRepository */
    private $purchaseOrderRepository;

    /** @var  VendorStockRepository */
    private $vendorStockRepository;

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

    public function __construct(MarketRepository $marketRepo, PurchaseInvoiceRepository $purchaseInvoiceRepo, PurchaseInvoiceItemsRepository $purchaseInvoiceItemsRepo,  CustomFieldRepository $customFieldRepo , UploadRepository $uploadRepo, ProductRepository $productRepo, InventoryRepository $inventoryRepo, UserRepository $userRepo, DeliveryAddressRepository $deliveryAddressRepo, TransactionRepository $transactionRepo, ShortLinkRepository $shortLinkRepo, PaymentModeRepository $paymentModeRepo, DeliveryZonesRepository $deliveryZonesRepo, PartyTypesRepository $partyTypesRepo, PartySubTypesRepository $partySubTypesRepo, PartyStreamRepository $partyStreamRepo, PurchaseOrderRepository $purchaseOrderRepo, VendorStockRepository $vendorStockRepo)
    {
        parent::__construct();
        $this->marketRepository                 = $marketRepo;
        $this->productRepository                = $productRepo;
        $this->purchaseInvoiceRepository        = $purchaseInvoiceRepo;
        $this->purchaseInvoiceItemsRepository   = $purchaseInvoiceItemsRepo;
        $this->inventoryRepository              = $inventoryRepo;
        $this->transactionRepository            = $transactionRepo;
        $this->userRepository                   = $userRepo;
        $this->deliveryAddressRepository        = $deliveryAddressRepo;
        $this->customFieldRepository            = $customFieldRepo;
        $this->uploadRepository                 = $uploadRepo;
        $this->shortLinkRepository              = $shortLinkRepo;
        $this->paymentModeRepository            = $paymentModeRepo; 
        $this->deliveryZonesRepository          = $deliveryZonesRepo;
        $this->partyTypesRepository             = $partyTypesRepo;
        $this->partySubTypesRepository          = $partySubTypesRepo;
        $this->partyStreamRepository            = $partyStreamRepo;
        $this->purchaseOrderRepository          = $purchaseOrderRepo;
        $this->vendorStockRepository            = $vendorStockRepo;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(PurchaseInvoiceDatatable $purchaseInvoiceDataTable)
    {
        $purchaseInvoice = $purchaseInvoiceDataTable;
        if(Request('start_date')!='' && Request('end_date')!='' ) {
           $purchaseInvoice->with('start_date',Request('start_date'))->with('end_date',Request('end_date'));  
        }
        return $purchaseInvoice->render('purchase_invoice.index');
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

        $products   = $this->productRepository->get();
        $purchase_no = setting('app_invoice_prefix').'-PI-'.(autoIncrementId('purchase_invoice'));
        return view('purchase_invoice.create',compact('users','products','purchase_no','payment_types','party_types','party_streams','party_sub_types'));
    } 
     
    public function store(CreatePurchaseInvoiceRequest $request)
    {
        $input = $request->all();
        try {
            $input['created_by'] = auth()->user()->id;
            $purchase_invoice    = $this->purchaseInvoiceRepository->create($input);
            
            if($purchase_invoice) {
                for($i=0; $i<count($input['product_id']); $i++) :
                    $items = array(
                        'purchase_invoice_id'   => $purchase_invoice->id,
                        'product_id'            => $input['product_id'][$i],
                        'product_name'          => $input['product_name'][$i],
                        'product_hsn_code'      => $input['product_hsn_code'][$i],
                        'mrp'                   => $input['mrp'][$i],
                        'quantity'              => $input['quantity'][$i],
                        'unit'                  => $input['unit'][$i],
                        'unit_price'            => $input['unit_price'][$i],
                        'discount'              => $input['discount'][$i],
                        'discount_amount'       => $input['discount_amount'][$i],
                        'tax'                   => $input['tax'][$i],
                        'tax_amount'            => $input['tax_amount'][$i],
                        'amount'                => $input['amount'][$i],
                        'created_by'            => auth()->user()->id,
                    );
                    $invoice_item = $this->purchaseInvoiceItemsRepository->create($items);

                    //Inventory
                        $this->addInventory(
                            $invoice_item->product->id,
                            $purchase_invoice->market_id,
                            'purchase',
                            'add',
                            $invoice_item->quantity,
                            $invoice_item->unit,
                            $invoice_item->product_name,
                            'purchase_invoice_item_id',
                            $invoice_item->id
                        );
                        $this->productStockupdate($invoice_item->product->id);
                    //Inventory    

                endfor;

                //Transaction
                   $this->addTransaction(
                        'purchase',
                        'credit',
                        date('Y-m-d'),
                        $purchase_invoice->market->id,
                        $purchase_invoice->total,
                        'purchase_invoice_id',
                        $purchase_invoice->id
                    );     
                //Transaction

                //Transaction
                   if($purchase_invoice->cash_paid > 0) {
                       $this->addTransaction(
                            'purchase',
                            'debit',
                            date('Y-m-d'),
                            $purchase_invoice->market->id,
                            $purchase_invoice->cash_paid,
                            'purchase_invoice_id',
                            $purchase_invoice->id
                        );
                    }     
                //Transaction   

                //Update Balance
                    $this->partyBalanceUpdate($purchase_invoice->market->id);
                //Update Balance 

                if(isset($input['purchase_order_id']) && $input['purchase_order_id'] > 0) {
                    $purchase_order = $this->purchaseOrderRepository->findWithoutFail($input['purchase_order_id']);
                    if(!empty($purchase_order)) {
                        $purchase_order = $this->purchaseOrderRepository->update(['status'=>'invoiced'],$input['purchase_order_id']); 
                    }
                }

                if(isset($input['vendor_stock_id']) && $input['vendor_stock_id'] > 0) {
                    $vendor_stock = $this->vendorStockRepository->findWithoutFail($input['vendor_stock_id']);
                    if(!empty($vendor_stock)) {
                        $vendor_stock = $this->vendorStockRepository->update(['status'=>'invoiced'],$input['vendor_stock_id']); 
                    }
                }

                $this->emailtoparty($purchase_invoice->id);    

            }
            if(isset($input['image'][0]) && $input['image'][0]){
                $cacheUpload = $this->uploadRepository->getByUuid($input['image'][0]);
                $mediaItem = $cacheUpload->getMedia('image')->first();
                $mediaItem->copy($purchase_invoice, 'image');
            }

        } catch (ValidatorException $e) {
            Flash::error($e->getMessage());
        }
        Flash::success(__('Save successfully',['operator' => __('Purchase Invoice')]));
        return redirect(route('purchaseInvoice.index'));

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\PurchaseInvoice  $PurchaseInvoice
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        $purchase_invoice = $this->purchaseInvoiceRepository->with('items')->with('items.uom')->with('items.product')->findWithoutFail($id);
        if (empty($purchase_invoice)) {
            Flash::error(__('Not Found',['operator' => __('Purchase Invoice')]));
            return redirect(route('purchaseInvoice.index'));
        }
        if($request->ajax()) {
            return ['status' => 'success', 'data' => $purchase_invoice]; 
        }
        return view('purchase_invoice.show',compact('purchase_invoice'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\PurchaseInvoice  $PurchaseInvoice
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {   
        $purchase_invoice = $this->purchaseInvoiceRepository->findWithoutFail($id);
        if (empty($purchase_invoice)) {
            Flash::error(__('Not Found',['operator' => __('Purchase Invoice')]));
            return redirect(route('purchaseInvoice.index'));
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

        return view('purchase_invoice.edit',compact('users','products','payment_types','party_types','party_streams','party_sub_types', 'purchase_invoice'));

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\SalesInvoice  $SalesInvoice
     * @return \Illuminate\Http\Response
     */
    public function update($id, Request $request)
    {  
        $purchase_invoice_old = $this->purchaseInvoiceRepository->findWithoutFail($id);
        if (empty($purchase_invoice_old)) {
            Flash::error(__('Not Found',['operator' => __('Purchase Invoice')]));
            return redirect(route('purchaseInvoice.index'));
        }

        $input = $request->all();
        try {
            $input['updated_by'] = auth()->user()->id;
            $purchase_invoice    = $this->purchaseInvoiceRepository->update($input,$id);
            
            if($purchase_invoice) {
                for($i=0; $i<count($input['product_id']); $i++) :
                    $items = array(
                        'purchase_invoice_id'   => $purchase_invoice->id,
                        'product_id'            => $input['product_id'][$i],
                        'product_name'          => $input['product_name'][$i],
                        'product_hsn_code'      => $input['product_hsn_code'][$i],
                        'mrp'                   => $input['mrp'][$i],
                        'quantity'              => $input['quantity'][$i],
                        'unit'                  => $input['unit'][$i],
                        'unit_price'            => $input['unit_price'][$i],
                        'discount'              => $input['discount'][$i],
                        'discount_amount'       => $input['discount_amount'][$i],
                        'tax'                   => $input['tax'][$i],
                        'tax_amount'            => $input['tax_amount'][$i],
                        'amount'                => $input['amount'][$i],
                        'created_by'            => auth()->user()->id,
                    );
                    if(isset($input['invoice_item_id'][$i]) && $input['invoice_item_id'][$i] > 0) {
                        $invoice_item = $this->purchaseInvoiceItemsRepository->update($items,$input['invoice_item_id'][$i]);
                        //Inventory
                            $this->updateInventory(
                                $invoice_item->product->id,
                                $purchase_invoice->market_id,
                                'purchase',
                                'add',
                                $invoice_item->quantity,
                                $invoice_item->unit,
                                $invoice_item->product_name,
                                'purchase_invoice_item_id',
                                $invoice_item->id
                            );
                            $this->productStockupdate($invoice_item->product->id);
                        //Inventory
                    } else {
                        $invoice_item = $this->purchaseInvoiceItemsRepository->create($items);
                        //Inventory
                            $this->addInventory(
                                $invoice_item->product->id,
                                $purchase_invoice->market_id,
                                'purchase',
                                'add',
                                $invoice_item->quantity,
                                $invoice_item->unit,
                                $invoice_item->product_name,
                                'purchase_invoice_item_id',
                                $invoice_item->id
                            );
                            $this->productStockupdate($invoice_item->product->id);
                        //Inventory
                    }    

                endfor;

                //Transaction
                   $this->updateTransaction(
                        'purchase',
                        'credit',
                        date('Y-m-d'),
                        $purchase_invoice->market->id,
                        $purchase_invoice->total,
                        'purchase_invoice_id',
                        $purchase_invoice->id
                    );     
                //Transaction

                //Transaction
                    if($purchase_invoice_old->cash_paid > 0) {
                        $this->updateTransaction(
                            'purchase',
                            'debit',
                            date('Y-m-d'),
                            $purchase_invoice->market->id,
                            $purchase_invoice->cash_paid,
                            'purchase_invoice_id',
                            $purchase_invoice->id
                        ); 
                    } else {
                        if($purchase_invoice->cash_paid > 0) {
                           $this->addTransaction(
                                'purchase',
                                'debit',
                                date('Y-m-d'),
                                $purchase_invoice->market->id,
                                $purchase_invoice->cash_paid,
                                'purchase_invoice_id',
                                $purchase_invoice->id
                            );
                        }
                    }    
                //Transaction   

                //Update Balance
                    $this->partyBalanceUpdate($purchase_invoice->market->id);
                //Update Balance   

                if(isset($input['delete_item_id']) && count($input['delete_item_id']) > 0) {
                    foreach($input['delete_item_id'] as $deleteid) {
                        
                        $invoice_item = $this->purchaseInvoiceItemsRepository->findWithoutFail($deleteid);
                        $this->purchaseInvoiceItemsRepository->delete($deleteid);
                        $this->productStockupdate($invoice_item->product->id);

                    }
                } 

            }
            if(isset($input['image'][0]) && $input['image'][0]){
                $cacheUpload = $this->uploadRepository->getByUuid($input['image'][0]);
                $mediaItem = $cacheUpload->getMedia('image')->first();
                $mediaItem->copy($purchase_invoice, 'image');
            }

        } catch (ValidatorException $e) {
            Flash::error($e->getMessage());
        }
        Flash::success(__('Save successfully',['operator' => __('Purchase Invoice')]));
        return redirect(route('purchaseInvoice.index'));
    
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\SalesInvoice  $SalesInvoice
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $purchase_invoice = $this->purchaseInvoiceRepository->with('market')->with('items')->findWithoutFail($id);
        if (empty($purchase_invoice)) {
            Flash::error('Purchase Invoice not found');
            return redirect(route('purchaseInvoice.index'));
        }

        $this->purchaseInvoiceRepository->delete($id);

        if(count($purchase_invoice->items) > 0) {
            foreach($purchase_invoice->items as $item) {
                $this->productStockupdate($item->product->id);
            }
        }
        $this->partyBalanceUpdate($purchase_invoice->market->id);
        $this->calculateRewardBalance($purchase_invoice->market->id);

        Flash::success(__('Deleted successfully',['operator' => __('Purchase Invoice')]));
        return redirect(route('purchaseInvoice.index'));
    }


    public function print($id,$type,$view_type,Request $request)
    {   
        $purchase_invoice = $this->purchaseInvoiceRepository->with('market')->with('items')->findWithoutFail($id);
        if (empty($purchase_invoice)) {
            Flash::error('Purchase Invoice not found');
            return redirect(route('purchaseInvoice.index'));
        }
        $words    = $this->amounttoWords($purchase_invoice->total);
        $currency = '<span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span>';
        $pdf      = PDF::loadView('purchase_invoice.print', compact('purchase_invoice','type','currency','words'));
        $filename = $purchase_invoice->code.'-'.$purchase_invoice->market->name.'.pdf';
        
        if($view_type=='print') {
            return $pdf->stream($filename);
        } elseif($view_type=='download') {
            return $pdf->download($filename);
        } else {
            return view('purchase_invoice.thermal_print', compact('purchase_invoice','type','currency','words'));
        }
    }


    public function emailtoparty($id) {

        $purchase_invoice = $this->purchaseInvoiceRepository->findWithoutFail($id);
        if (empty($purchase_invoice)) {
            Flash::error('Purchase Invoice not found');
            return redirect(route('purchaseInvoice.index'));
        }
        $type       = 1;
        $words      = $this->amounttoWords($purchase_invoice->total);
        $currency   = '<span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span>';
        $pdf        =   PDF::setOptions([
                            'isHtml5ParserEnabled' => true, 
                            'isRemoteEnabled' => true, 
                            'dpi' => 100
                        ])->loadView('purchase_invoice.print',compact('purchase_invoice','type','words','currency'));

        $invoice_url = url(base64_encode($purchase_invoice->id).'/PurchaseInvoice'); 
        $message     = "Hi ".$purchase_invoice->market->name.",<br>

                        Here's Purchase Invoice #".$purchase_invoice->code." for ".number_format($purchase_invoice->total,2,'.','').".<br>

                        View your bill online: ".url(base64_encode($purchase_invoice->id).'/PurchaseInvoice')."<br>

                        From your online bill you can print a PDF or create a free login and view your outstanding bills <br>.

                        If you have any questions, please let us know. <br>";

        //Notification
            $notification_data = [
                'greeting'    => "New Purchase Invoice Generated!",
                'body'        => $message,
                'thanks'      => 'Thank you',
                'pdf_file'    => $pdf,
                'filename'    => 'Purchase Invoice #'.$purchase_invoice->code.'.pdf',
                'datas'       => array(
                    'title'   => 'Purchase Invoice '.$purchase_invoice->code.' from '.setting('app_name').' for '.$purchase_invoice->market->name, //"New Purchase Invoice Generated!",
                    'message' => $message
                )
            ];
            $notify = $purchase_invoice->market->user->notify(new PurchaseInvoiceNotification($notification_data));
            $purchase_invoice->market->activity()->create([
                'market_id'  => $purchase_invoice->market->id,
                'action'     => 'Purchase Invoice Sent',
                'notes'      => 'Purchase Invoice '.$purchase_invoice->code.' sent to '.$purchase_invoice->market->user->email,
                'created_by' => auth()->user()->id
            ]);
        //Notification
            
    }


    public function frontView(Request $request, $id) {

        $id                  = base64_decode($id);
        $purchase_invoice    = $this->purchaseInvoiceRepository->with('market')->with('items')->findWithoutFail($id);
        if (empty($purchase_invoice)) {
            Flash::error('Purchase Invoice not found');
            return redirect(route('purchaseInvoice.index'));
        }
        $words    = $this->amounttoWords($purchase_invoice->total);
        $currency = '<span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span>';
        return view('purchase_invoice.front_end_invoice', compact('purchase_invoice','currency','words'));
    }


    public function DownloadPDF(Request $request, $id) {
        $id               = base64_decode($id);
        $type             = 1;
        $purchase_invoice = $this->purchaseInvoiceRepository->with('market')->with('items')->findWithoutFail($id);
        if (empty($purchase_invoice)) {
            Flash::error('Purchase Invoice not found');
            return redirect(route('purchaseInvoice.index'));
        }
        $words    = $this->amounttoWords($purchase_invoice->total);
        $currency = '<span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span>';
        $pdf      = PDF::loadView('purchase_invoice.print', compact('purchase_invoice','type','currency','words'));
        $filename = $purchase_invoice->code.'-'.$purchase_invoice->market->name.'.pdf';
        return $pdf->download($filename);
    }

}
