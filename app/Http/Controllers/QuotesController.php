<?php

namespace App\Http\Controllers;

use App\Models\Quotes;
use App\DataTables\QuotesDataTable;
use App\Http\Requests;
use App\Http\Requests\CreateQuotesRequest;
use App\Http\Requests\UpdateQuotesRequest;
use App\Repositories\QuotesRepository;
use App\Repositories\QuoteItemsRepository;
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

use Notification;
use App\Notifications\QuoteNotification;


class QuotesController extends Controller
{   
    /** @var  MarketRepository */
    private $marketRepository;

    /** @var  ProductRepository */
    private $productRepository;

    /** @var  QuotesRepository */
    private $quotesRepository;

    /** @var  QuoteItemsRepository */
    private $quoteItemsRepository;

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

    public function __construct(MarketRepository $marketRepo, QuotesRepository $quotesRepo, QuoteItemsRepository $quoteItemsRepo,  CustomFieldRepository $customFieldRepo , UploadRepository $uploadRepo, ProductRepository $productRepo, InventoryRepository $inventoryRepo, UserRepository $userRepo, DeliveryAddressRepository $deliveryAddressRepo, TransactionRepository $transactionRepo, ShortLinkRepository $shortLinkRepo, PaymentModeRepository $paymentModeRepo, DeliveryZonesRepository $deliveryZonesRepo, PartyTypesRepository $partyTypesRepo, PartySubTypesRepository $partySubTypesRepo, PartyStreamRepository $partyStreamRepo)
    {
        parent::__construct();
        $this->marketRepository           = $marketRepo;
        $this->productRepository          = $productRepo;
        $this->quotesRepository           = $quotesRepo;
        $this->quoteItemsRepository       = $quoteItemsRepo;
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
    public function index(QuotesDatatable $quotesDataTable)
    {
        $quotes = $quotesDataTable;
        if(Request('start_date')!='' && Request('end_date')!='' ) {
           $quotes->with('start_date',Request('start_date'))->with('end_date',Request('end_date'));  
        }
        return $quotes->render('quotes.index');
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

        $party_types  = $this->partyTypesRepository->pluck('name', 'id');
        $party_types->prepend("Please Select",null);
        
        $party_streams = $this->partyStreamRepository->pluck('name', 'id');
        $party_streams->prepend("Please Select",null);

        $party_sub_types  = [null => "Please Select"];

        $products   = $this->productRepository->get();
        
        $quote_no = setting('app_invoice_prefix').'-QU-'.(autoIncrementId('quotes'));
        
        return view('quotes.create',compact('users','products','quote_no','party_types','party_streams','party_sub_types'));
    } 
     
    public function store(CreateQuotesRequest $request)
    {
        $input = $request->all();
        try {
            $input['created_by'] = auth()->user()->id;
            $quote               = $this->quotesRepository->create($input);
            
            if($quote) {
                for($i=0; $i<count($input['product_id']); $i++) :
                    $items = array(
                        'quote_id'          => $quote->id,
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
                    $invoice_item = $this->quoteItemsRepository->create($items);  

                endfor;

            }

            $quote->market->activity()->create([
                'market_id'  => $quote->market->id,
                'action'     => 'Quote Created',
                'notes'      => 'Quote '.$quote->code.' for '.number_format($quote->total,'2','.','').' Created',
                'status'     => 'completed',
                'created_by' => auth()->user()->id
            ]);

            $this->emailtoparty($quote->id);

            if(isset($input['image'][0]) && $input['image'][0]){
                $cacheUpload = $this->uploadRepository->getByUuid($input['image'][0]);
                $mediaItem = $cacheUpload->getMedia('image')->first();
                $mediaItem->copy($quote, 'image');
            }

        } catch (ValidatorException $e) {
            Flash::error($e->getMessage());
        }
        Flash::success(__('Save successfully',['operator' => __('Quotes')]));
        return redirect(route('quotes.index'));

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Quotes  $Quotes
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        $quote = $this->quotesRepository->with('items')->with('items.uom')->with('items.product')->findWithoutFail($id);
        if (empty($quote)) {
            Flash::error(__('Not Found',['operator' => __('Quotes')]));
            return redirect(route('quotes.index'));
        }
        if($request->ajax()) {
            return ['status' => 'success', 'data' => $quote]; 
        }
        return view('quotes.show',compact('quote'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\SalesInvoice  $SalesInvoice
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {   
        $quote = $this->quotesRepository->findWithoutFail($id);
        if (empty($quote)) {
            Flash::error(__('Not Found',['operator' => __('Quotes')]));
            return redirect(route('quotes.index'));
        }

        $users  = $this->marketRepository->select('id', DB::raw("concat(name, ' ', mobile, ' ', code) as name"))->pluck('name', 'id');
        $users->prepend("Please Select",null);
        
        $party_types  = $this->partyTypesRepository->pluck('name', 'id');
        $party_types->prepend("Please Select",null);
        
        $party_streams = $this->partyStreamRepository->pluck('name', 'id');
        $party_streams->prepend("Please Select",null);

        $party_sub_types  = [null => "Please Select"];
        $products   = $this->productRepository->get();

        return view('quotes.edit',compact('users','products','party_types','party_streams','party_sub_types', 'quote'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Quotes  $Quotes
     * @return \Illuminate\Http\Response
     */
    public function update($id, Request $request)
    {  
        $quote_old = $this->quotesRepository->findWithoutFail($id);
        if (empty($quote_old)) {
            Flash::error(__('Not Found',['operator' => __('Quotes')]));
            return redirect(route('quotes.index'));
        }

        $input = $request->all();

        if(isset($input['type']) && $input['type']=='status-update') {
            if($input['type']=='status-update') {
                $quote = $this->quotesRepository->findWithoutFail($id);
                if(!empty($quote)) {
                    $quote = $this->quotesRepository->update(['status'=>$input['status']],$input['id']);

                    $quote->market->activity()->create([
                        'market_id'  => $quote->market->id,
                        'action'     => 'Quote '.ucfirst($input['status']),
                        'notes'      => 'Quote '.$quote->code.' '.ucfirst($input['status']),
                        'status'     => 'completed',
                        'created_by' => auth()->user()->id
                    ]);

                    return $quote;
                }
            }   
        }

        try {
            $input['updated_by'] = auth()->user()->id;
            $quote               = $this->quotesRepository->update($input,$id);
            
            if($quote) {
                for($i=0; $i<count($input['product_id']); $i++) :
                    $items = array(
                        'quote_id'          => $quote->id,
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
                        $invoice_item = $this->quoteItemsRepository->update($items,$input['invoice_item_id'][$i]);
                    } else {
                        $invoice_item = $this->quoteItemsRepository->create($items);
                    }    

                endfor;   

            }

            $quote->market->activity()->create([
                'market_id'  => $quote->market->id,
                'action'     => 'Quote Updated',
                'notes'      => 'Quote '.$quote->code.' Amount updated into '.number_format($quote->total,'2','.',''),
                'status'     => 'completed',
                'created_by' => auth()->user()->id
            ]);

            if(isset($input['image'][0]) && $input['image'][0]){
                $cacheUpload = $this->uploadRepository->getByUuid($input['image'][0]);
                $mediaItem = $cacheUpload->getMedia('image')->first();
                $mediaItem->copy($quote, 'image');
            }

        } catch (ValidatorException $e) {
            Flash::error($e->getMessage());
        }
        Flash::success(__('Save successfully',['operator' => __('Quotes')]));
        return redirect(route('quotes.index'));
    
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Quotes  $Quotes
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $quote = $this->quotesRepository->with('market')->with('items')->findWithoutFail($id);
        if (empty($quote)) {
            Flash::error('Quote not found');
            return redirect(route('quotes.index'));
        }

        $this->quotesRepository->delete($id);

        if(count($quote->items) > 0) {
            foreach($quote->items as $item) {
                $this->productStockupdate($item->product->id);
            }
        }
        $this->partyBalanceUpdate($quote->market->id);
        $this->calculateRewardBalance($quote->market->id);

        $quote->market->activity()->create([
            'market_id'  => $quote->market->id,
            'action'     => 'Quote Deleted',
            'notes'      => 'Quote '.$quote->code.' Deleted',
            'status'     => 'completed',
            'created_by' => auth()->user()->id
        ]);

        Flash::success(__('Deleted successfully',['operator' => __('Quotes')]));
        return redirect(route('quotes.index'));
    }


    public function print($id,$type,$view_type,Request $request)
    {   
        $quote = $this->quotesRepository->with('market')->with('items')->findWithoutFail($id);
        if (empty($quote)) {
            Flash::error('Quotes not found');
            return redirect(route('quotes.index'));
        }
        $words    = $this->amounttoWords($quote->total);
        $currency = '<span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span>';
        $pdf      = PDF::loadView('quotes.print', compact('quote','type','currency','words'));
        $filename = $quote->code.'-'.$quote->market->name.'.pdf';
        
        if($view_type=='print') {
            return $pdf->stream($filename);
        } elseif($view_type=='download') {
            return $pdf->download($filename);
        } else {
            return view('quotes.thermal_print', compact('quote','type','currency','words'));
        }
    }


    public function emailtoparty($id) {

        $quote = $this->quotesRepository->findWithoutFail($id);
        if (empty($quote)) {
            Flash::error('Quotes not found');
            return redirect(route('quotes.index'));
        }
        $type       = 1;
        $words      = $this->amounttoWords($quote->total);
        $currency   = '<span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span>';
        $pdf        = PDF::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true, 'dpi' => 100])->loadView('quotes.print',compact('quote','type','words','currency'));

        $invoice_url = url(base64_encode($quote->id).'/Quotes'); 
        $message     = "Hi ".$quote->market->name.",<br>

                        Here's Quotation #".$quote->code." for ".number_format($quote->total,2,'.','').".<br>

                        View your bill online: ".url(base64_encode($quote->id).'/Quotes')."<br>

                        From your online bill you can print a PDF or create a free login and view your outstanding bills <br>.

                        If you have any questions, please let us know. <br>";

        //Notification
            $notification_data = [
                'greeting'    => "New Quotation Generated!",
                'body'        => $message,
                'thanks'      => 'Thank you',
                'pdf_file'    => $pdf,
                'filename'    => 'Quote #'.$quote->code.'.pdf',
                'datas'       => array(
                    'title'   => 'Quote '.$quote->code.' from '.setting('app_name').' for '.$quote->market->name, //"New Quotation Generated!",
                    'message' => $message
                )
            ];
            $notify = $quote->market->user->notify(new QuoteNotification($notification_data));  
            $update = $this->quotesRepository->update(['status'=>'sent'],$quote->id);

            $quote->market->activity()->create([
                'market_id'  => $quote->market->id,
                'action'     => 'Quote Sent',
                'notes'      => 'Quote '.$quote->code.' sent to '.$quote->market->user->email,
                'status'     => 'completed',
                'created_by' => auth()->user()->id
            ]);
            
        //Notification
            
    }


    public function frontView(Request $request, $id) {

        $id       = base64_decode($id);
        $quote    = $this->quotesRepository->with('market')->with('items')->findWithoutFail($id);
        if (empty($quote)) {
            Flash::error('Quotes not found');
            return redirect(route('quotes.index'));
        }
        $words    = $this->amounttoWords($quote->total);
        $currency = '<span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span>';
        return view('quotes.front_end_invoice_pdf', compact('quote','currency','words'));
    }


    public function DownloadPDF(Request $request, $id) {
        $id     = base64_decode($id);
        $type   = 1;
        $quote = $this->quotesRepository->with('market')->with('items')->findWithoutFail($id);
        if (empty($quote)) {
            Flash::error('Quotes not found');
            return redirect(route('quotes.index'));
        }
        $words    = $this->amounttoWords($quote->total);
        $currency = '<span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span>';
        $pdf      = PDF::loadView('quotes.print', compact('quote','type','currency','words'));
        $filename = $quote->code.'-'.$quote->market->name.'.pdf';
        return $pdf->download($filename);
    }

    public function statusUpdate(Request $request) {
        $id    = $request->id;
        $quote = $this->quotesRepository->findWithoutFail($id);
        if (empty($quote)) {
            Flash::error(__('Not Found',['operator' => __('Quote')]));
            return redirect()->back();
        }
        $quote = $this->quotesRepository->update([
            'status' => $request->status 
        ],$id);
        if ($request->ajax()) {
            return $quote;
        }
    }


}
