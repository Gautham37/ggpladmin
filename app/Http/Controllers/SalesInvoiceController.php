<?php

namespace App\Http\Controllers;

use App\Models\SalesInvoice;
use App\DataTables\SalesInvoiceDataTable;
use App\Http\Requests;
use App\Http\Requests\CreateSalesInvoiceRequest;
use App\Http\Requests\UpdateSalesInvoiceRequest;
use App\Repositories\SalesInvoiceRepository;
use App\Repositories\SalesInvoiceItemsRepository;
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
use App\Repositories\QuotesRepository;
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
use App\Notifications\SalesInvoiceNotification;


class SalesInvoiceController extends Controller
{   
    /** @var  MarketRepository */
    private $marketRepository;

    /** @var  ProductRepository */
    private $productRepository;

    /** @var  SalesInvoiceRepository */
    private $salesInvoiceRepository;

    /** @var  SalesInvoiceItemsRepository */
    private $salesInvoiceItemsRepository;

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

    /** @var  QuotesRepository */
    private $quotesRepository;

    /**
     * @var CustomFieldRepository
     */
    private $customFieldRepository;

    /**
  * @var UploadRepository
  */
    private $uploadRepository;

    public function __construct(MarketRepository $marketRepo, SalesInvoiceRepository $salesInvoiceRepo, SalesInvoiceItemsRepository $salesInvoiceItemsRepo,  CustomFieldRepository $customFieldRepo , UploadRepository $uploadRepo, ProductRepository $productRepo, InventoryRepository $inventoryRepo, UserRepository $userRepo, DeliveryAddressRepository $deliveryAddressRepo, TransactionRepository $transactionRepo, ShortLinkRepository $shortLinkRepo, PaymentModeRepository $paymentModeRepo, DeliveryZonesRepository $deliveryZonesRepo, PartyTypesRepository $partyTypesRepo, PartySubTypesRepository $partySubTypesRepo, PartyStreamRepository $partyStreamRepo, QuotesRepository $quotesRepo)
    {
        parent::__construct();
        $this->marketRepository           = $marketRepo;
        $this->productRepository          = $productRepo;
        $this->salesInvoiceRepository     = $salesInvoiceRepo;
        $this->salesInvoiceItemsRepository= $salesInvoiceItemsRepo;
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
        $this->quotesRepository           = $quotesRepo;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(SalesInvoiceDatatable $salesInvoiceDataTable)
    {
        $salesInvoice = $salesInvoiceDataTable;
        if(Request('start_date')!='' && Request('end_date')!='' ) {
           $salesInvoice->with('start_date',Request('start_date'))->with('end_date',Request('end_date'));  
        }
        return $salesInvoice->render('sales_invoice.index');
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
        $invoice_no = setting('app_invoice_prefix').'-SI-'.(autoIncrementId('sales_invoice'));
        return view('sales_invoice.create',compact('users','products','invoice_no','payment_types','party_types','party_streams','party_sub_types'));
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
     
    public function store(CreateSalesInvoiceRequest $request)
    {
        $input = $request->all();
        try {
            $input['created_by'] = auth()->user()->id;
            $sales_invoice       = $this->salesInvoiceRepository->create($input);
            
            if($sales_invoice) {
                for($i=0; $i<count($input['product_id']); $i++) :
                    $items = array(
                        'sales_invoice_id'  => $sales_invoice->id,
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
                    $invoice_item = $this->salesInvoiceItemsRepository->create($items);

                    //Inventory
                        $this->addInventory(
                            $invoice_item->product->id,
                            $sales_invoice->market_id,
                            'sales',
                            'reduce',
                            $invoice_item->quantity,
                            $invoice_item->unit,
                            $invoice_item->product_name,
                            'sales_invoice_item_id',
                            $invoice_item->id
                        );
                        $this->productStockupdate($invoice_item->product->id);
                    //Inventory    

                endfor;

                $sales_invoice->market->activity()->create([
                    'market_id'  => $sales_invoice->market->id,
                    'action'     => 'Sales Invoice Created',
                    'notes'      => 'Sales Invoice '.$sales_invoice->code.' for '.number_format($sales_invoice->total,'2','.','').' Created',
                    'status'     => 'completed',
                    'created_by' => auth()->user()->id
                ]);

                //Transaction
                   $this->addTransaction(
                        'sales',
                        'debit',
                        date('Y-m-d'),
                        $sales_invoice->market->id,
                        $sales_invoice->total,
                        'sales_invoice_id',
                        $sales_invoice->id
                    );     
                //Transaction

                //Transaction
                   if($sales_invoice->cash_paid > 0) {
                       $this->addTransaction(
                            'sales',
                            'credit',
                            date('Y-m-d'),
                            $sales_invoice->market->id,
                            $sales_invoice->cash_paid,
                            'sales_invoice_id',
                            $sales_invoice->id
                        );
                    }     
                //Transaction   

                //Update Balance
                    $this->partyBalanceUpdate($sales_invoice->market->id);
                //Update Balance 


                //Add Rewards Usage
                    if($input['redeem_points'] > 0 && $input['used_point_worth'] > 0) {
                        $this->addPointusage(
                            $sales_invoice->market_id,
                            $input['used_point_worth'],
                            $input['redeem_points'],
                            'sales_invoice_id',
                            $sales_invoice->id,
                            'sales_invoice',
                            'redeem'
                        );
                    }
                //Add Rewards Usage     

                //Update Rewards
                    $this->addUpdatePurchaseRewards(
                        $sales_invoice->market_id,
                        $sales_invoice->total,
                        'sales_invoice_id',
                        $sales_invoice->id,
                        'sales_invoice',
                        'earn',
                        'add'
                    );
                //Update Rewards
                
                if(isset($input['quote_id']) && $input['quote_id'] > 0) {
                    $quote = $this->quotesRepository->findWithoutFail($input['quote_id']);
                    if(!empty($quote)) {
                        $quote = $this->quotesRepository->update(['status'=>'invoiced'],$input['quote_id']); 

                        $quote->market->activity()->create([
                            'market_id'  => $quote->market->id,
                            'action'     => 'Quote Invoiced',
                            'notes'      => $quote->market->name.' '.$quote->market->code.' Quote '.$quote->code.' Invoice into '.$sales_invoice->code,
                            'status'     => 'completed',
                            'created_by' => auth()->user()->id
                        ]);
                    }
                }

                $this->emailtoparty($sales_invoice->id);

            }
            if(isset($input['image'][0]) && $input['image'][0]){
                $cacheUpload = $this->uploadRepository->getByUuid($input['image'][0]);
                $mediaItem = $cacheUpload->getMedia('image')->first();
                $mediaItem->copy($sales_invoice, 'image');
            }

        } catch (ValidatorException $e) {
            Flash::error($e->getMessage());
        }
        Flash::success(__('Save successfully',['operator' => __('Sales Invoice')]));
        return redirect(route('salesInvoice.index'));

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\SalesInvoice  $SalesInvoice
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        $sales_invoice = $this->salesInvoiceRepository->with('items')->with('items.uom')->with('items.product')->with('salesreturn')->findWithoutFail($id);
        if (empty($sales_invoice)) {
            Flash::error(__('Not Found',['operator' => __('Sales Invoice')]));
            return redirect(route('salesInvoice.index'));
        }
        if($request->ajax()) {
            return ['status' => 'success', 'data' => $sales_invoice]; 
        }
        return view('sales_invoice.show',compact('sales_invoice'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\SalesInvoice  $SalesInvoice
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {   
        $sales_invoice = $this->salesInvoiceRepository->findWithoutFail($id);
        if (empty($sales_invoice)) {
            Flash::error(__('Not Found',['operator' => __('Sales Invoice')]));
            return redirect(route('salesInvoice.index'));
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

        return view('sales_invoice.edit',compact('users','products','payment_types','party_types','party_streams','party_sub_types', 'sales_invoice'));

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
        $sales_invoice_old = $this->salesInvoiceRepository->findWithoutFail($id);
        if (empty($sales_invoice_old)) {
            Flash::error(__('Not Found',['operator' => __('Sales Invoice')]));
            return redirect(route('salesInvoice.index'));
        }

        $input = $request->all();
        try {
            $input['updated_by'] = auth()->user()->id;
            $sales_invoice       = $this->salesInvoiceRepository->update($input,$id);
            
            if($sales_invoice) {
                for($i=0; $i<count($input['product_id']); $i++) :
                    $items = array(
                        'sales_invoice_id'  => $sales_invoice->id,
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
                        $invoice_item = $this->salesInvoiceItemsRepository->update($items,$input['invoice_item_id'][$i]);
                        //Inventory
                            $this->updateInventory(
                                $invoice_item->product->id,
                                $sales_invoice->market_id,
                                'sales',
                                'reduce',
                                $invoice_item->quantity,
                                $invoice_item->unit,
                                $invoice_item->product_name,
                                'sales_invoice_item_id',
                                $invoice_item->id
                            );
                            $this->productStockupdate($invoice_item->product->id);
                        //Inventory
                    } else {
                        $invoice_item = $this->salesInvoiceItemsRepository->create($items);
                        //Inventory
                            $this->addInventory(
                                $invoice_item->product->id,
                                $sales_invoice->market_id,
                                'sales',
                                'reduce',
                                $invoice_item->quantity,
                                $invoice_item->unit,
                                $invoice_item->product_name,
                                'sales_invoice_item_id',
                                $invoice_item->id
                            );
                            $this->productStockupdate($invoice_item->product->id);
                        //Inventory
                    }    

                endfor;

                $sales_invoice->market->activity()->create([
                    'market_id'  => $sales_invoice->market->id,
                    'action'     => 'Sales Invoice Updated',
                    'notes'      => 'Sales Invoice '.$sales_invoice->code.' total has been updated from '.number_format($sales_invoice_old->total,'2','.','').' to '.number_format($sales_invoice->total,'2','.',''),
                    'status'     => 'completed',
                    'created_by' => auth()->user()->id
                ]);

                //Transaction
                   $this->updateTransaction(
                        'sales',
                        'debit',
                        date('Y-m-d'),
                        $sales_invoice->market->id,
                        $sales_invoice->total,
                        'sales_invoice_id',
                        $sales_invoice->id
                    );     
                //Transaction

                //Transaction
                    if($sales_invoice_old->cash_paid > 0) {
                        $this->updateTransaction(
                            'sales',
                            'credit',
                            date('Y-m-d'),
                            $sales_invoice->market->id,
                            $sales_invoice->cash_paid,
                            'sales_invoice_id',
                            $sales_invoice->id
                        ); 
                    } else {
                        if($sales_invoice->cash_paid > 0) {
                           $this->addTransaction(
                                'sales',
                                'credit',
                                date('Y-m-d'),
                                $sales_invoice->market->id,
                                $sales_invoice->cash_paid,
                                'sales_invoice_id',
                                $sales_invoice->id
                            );
                        }
                    }    
                //Transaction   

                //Update Balance
                    $this->partyBalanceUpdate($sales_invoice->market->id);
                //Update Balance   

                if(isset($input['delete_item_id']) && count($input['delete_item_id']) > 0) {
                    foreach($input['delete_item_id'] as $deleteid) {
                        
                        $invoice_item = $this->salesInvoiceItemsRepository->findWithoutFail($deleteid);
                        $this->salesInvoiceItemsRepository->delete($deleteid);
                        $this->productStockupdate($invoice_item->product->id);

                    }
                } 

                //Update Rewards
                    $this->addUpdatePurchaseRewards(
                        $sales_invoice->market_id,
                        $sales_invoice->total,
                        'sales_invoice_id',
                        $sales_invoice->id,
                        'sales_invoice',
                        'earn',
                        'update'
                    );
                //Update Rewards     

            }
            if(isset($input['image'][0]) && $input['image'][0]){
                $cacheUpload = $this->uploadRepository->getByUuid($input['image'][0]);
                $mediaItem = $cacheUpload->getMedia('image')->first();
                $mediaItem->copy($sales_invoice, 'image');
            }

        } catch (ValidatorException $e) {
            Flash::error($e->getMessage());
        }
        Flash::success(__('Save successfully',['operator' => __('Sales Invoice')]));
        return redirect(route('salesInvoice.index'));
    
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\SalesInvoice  $SalesInvoice
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $sales_invoice = $this->salesInvoiceRepository->with('market')->with('items')->findWithoutFail($id);
        if (empty($sales_invoice)) {
            Flash::error('Sales Invoice not found');
            return redirect(route('salesInvoice.index'));
        }

        $this->salesInvoiceRepository->delete($id);

        if(count($sales_invoice->items) > 0) {
            foreach($sales_invoice->items as $item) {
                $this->productStockupdate($item->product->id);
            }
        }
        $this->partyBalanceUpdate($sales_invoice->market->id);
        $this->calculateRewardBalance($sales_invoice->market->id);

        $sales_invoice->market->activity()->create([
            'market_id'  => $sales_invoice->market->id,
            'action'     => 'Sales Invoice Deleted',
            'notes'      => 'Sales Invoice '.$sales_invoice->code.'  Deleted',
            'status'     => 'completed',
            'created_by' => auth()->user()->id
        ]);

        Flash::success(__('Deleted successfully',['operator' => __('Sales Invoice')]));
        return redirect(route('salesInvoice.index'));
    }


    public function print($id,$type,$view_type,Request $request)
    {   
        $sales_invoice = $this->salesInvoiceRepository->with('market')->with('items')->findWithoutFail($id);
        if (empty($sales_invoice)) {
            Flash::error('Sales Invoice not found');
            return redirect(route('salesInvoice.index'));
        }
        $words    = $this->amounttoWords($sales_invoice->total);
        $currency = '<span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span>';
        $pdf      = PDF::loadView('sales_invoice.print', compact('sales_invoice','type','currency','words'));
        $filename = 'SI-'.$sales_invoice->code.'-'.$sales_invoice->market->name.'.pdf';
        
        if($view_type=='print') {
            return $pdf->stream($filename);
        } elseif($view_type=='download') {
            return $pdf->download($filename);
        } else {
            return view('sales_invoice.thermal_print', compact('sales_invoice','type','currency','words'));
        }
    }


    public function emailtoparty($id) {

        $sales_invoice = $this->salesInvoiceRepository->findWithoutFail($id);
        if (empty($sales_invoice)) {
            Flash::error('Sales Invoice not found');
            return redirect(route('salesInvoice.index'));
        }
        $type       = 1;
        $words      = $this->amounttoWords($sales_invoice->total);
        $currency   = '<span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span>';
        $pdf        =   PDF::setOptions([
                            'isHtml5ParserEnabled' => true, 
                            'isRemoteEnabled' => true, 
                            'dpi' => 100
                        ])->loadView('sales_invoice.print',compact('sales_invoice','type','words','currency'));

        $invoice_url = url(base64_encode($sales_invoice->id).'/SalesInvoice'); 
        $message     = "Hi ".$sales_invoice->market->name.",<br>

                        Here's Sales Invoice #".$sales_invoice->code." for ".number_format($sales_invoice->total,2,'.','').".<br>

                        View your bill online: ".url(base64_encode($sales_invoice->id).'/SalesInvoice')."<br>

                        From your online bill you can print a PDF or create a free login and view your outstanding bills <br>.

                        If you have any questions, please let us know. <br>";

        //Notification
            $notification_data = [
                'greeting'    => "New Sales Invoice Generated!",
                'body'        => $message,
                'thanks'      => 'Thank you',
                'pdf_file'    => $pdf,
                'filename'    => 'Sales Invoice #'.$sales_invoice->code.'.pdf',
                'datas'       => array(
                    'title'   => 'Sales Invoice '.$sales_invoice->code.' from '.setting('app_name').' for '.$sales_invoice->market->name, //"New Sales Invoice Generated!",
                    'message' => $message
                )
            ];
            $notify = $sales_invoice->market->user->notify(new SalesInvoiceNotification($notification_data));
            $sales_invoice->market->activity()->create([
                'market_id'  => $sales_invoice->market->id,
                'action'     => 'Sales Invoice Sent',
                'notes'      => 'Sales Invoice '.$sales_invoice->code.' sent to '.$sales_invoice->market->user->email,
                'status'     => 'completed',
                'created_by' => auth()->user()->id
            ]);
        //Notification
            
    }


    public function frontView(Request $request, $id) {

        $id               = base64_decode($id);
        $sales_invoice    = $this->salesInvoiceRepository->with('market')->with('items')->findWithoutFail($id);
        if (empty($sales_invoice)) {
            Flash::error('Sales Invoice not found');
            return redirect(route('salesInvoice.index'));
        }
        $words    = $this->amounttoWords($sales_invoice->total);
        $currency = '<span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span>';
        return view('sales_invoice.front_end_invoice', compact('sales_invoice','currency','words'));
    }


    public function DownloadPDF(Request $request, $id) {
        $id            = base64_decode($id);
        $type          = 1;
        $sales_invoice = $this->salesInvoiceRepository->with('market')->with('items')->findWithoutFail($id);
        if (empty($sales_invoice)) {
            Flash::error('Sales Invoice not found');
            return redirect(route('salesInvoice.index'));
        }
        $words    = $this->amounttoWords($sales_invoice->total);
        $currency = '<span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span>';
        $pdf      = PDF::loadView('sales_invoice.print', compact('sales_invoice','type','currency','words'));
        $filename = $sales_invoice->code.'-'.$sales_invoice->market->name.'.pdf';
        return $pdf->download($filename);
    }

}
