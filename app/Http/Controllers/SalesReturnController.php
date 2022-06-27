<?php

namespace App\Http\Controllers;

use App\Models\SalesReturn;
use App\DataTables\SalesReturnDataTable;
use App\Http\Requests;
use App\Http\Requests\CreateSalesReturnRequest;
use App\Http\Requests\UpdateSalesReturnRequest;
use App\Repositories\SalesReturnRepository;
use App\Repositories\SalesReturnItemsRepository;
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
use App\Notifications\SalesReturnNotification;

class SalesReturnController extends Controller
{   
    /** @var  MarketRepository */
    private $marketRepository;

    /** @var  ProductRepository */
    private $productRepository;

    /** @var  SalesReturnRepository */
    private $salesReturnRepository;

    /** @var  SalesReturnItemsRepository */
    private $salesReturnItemsRepository;

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

    public function __construct(MarketRepository $marketRepo, SalesReturnRepository $salesReturnRepo, SalesReturnItemsRepository $salesReturnItemsRepo,  CustomFieldRepository $customFieldRepo , UploadRepository $uploadRepo, ProductRepository $productRepo, InventoryRepository $inventoryRepo, UserRepository $userRepo, DeliveryAddressRepository $deliveryAddressRepo, TransactionRepository $transactionRepo, ShortLinkRepository $shortLinkRepo, PaymentModeRepository $paymentModeRepo, DeliveryZonesRepository $deliveryZonesRepo, PartyTypesRepository $partyTypesRepo, PartySubTypesRepository $partySubTypesRepo, PartyStreamRepository $partyStreamRepo)
    {
        parent::__construct();
        $this->marketRepository           = $marketRepo;
        $this->productRepository          = $productRepo;
        $this->salesReturnRepository      = $salesReturnRepo;
        $this->salesReturnItemsRepository= $salesReturnItemsRepo;
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
    public function index(SalesReturnDatatable $salesReturnDataTable)
    {
        $salesReturn = $salesReturnDataTable;
        if(Request('start_date')!='' && Request('end_date')!='' ) {
           $salesReturn->with('start_date',Request('start_date'))->with('end_date',Request('end_date'));  
        }
        return $salesReturn->render('sales_return.index');
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
        $return_no  = setting('app_invoice_prefix').'-SR-'.(autoIncrementId('sales_return'));
        return view('sales_return.create',compact('users','products','return_no','payment_types','party_types','party_streams','party_sub_types'));
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
     
    public function store(CreateSalesReturnRequest $request)
    {
        $input = $request->all();
        try {
            $input['created_by'] = auth()->user()->id;
            $sales_return        = $this->salesReturnRepository->create($input);
            
            if($sales_return) {
                for($i=0; $i<count($input['product_id']); $i++) :
                    $items = array(
                        'sales_return_id'   => $sales_return->id,
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
                    $return_item = $this->salesReturnItemsRepository->create($items);

                    //Inventory
                        $this->addInventory(
                            $return_item->product->id,
                            $sales_return->market_id,
                            'sales_return',
                            'add',
                            $return_item->quantity,
                            $return_item->unit,
                            $return_item->product_name,
                            'sales_return_item_id',
                            $return_item->id
                        );
                        $this->productStockupdate($return_item->product->id);
                    //Inventory    

                endfor;

                $sales_return->market->activity()->create([
                    'market_id'  => $sales_return->market->id,
                    'action'     => 'Sales Return Created',
                    'notes'      => 'Sales Return '.$sales_return->code.' for '.number_format($sales_return->total,'2','.','').' Created',
                    'status'     => 'completed',
                    'created_by' => auth()->user()->id
                ]);

                //Transaction
                   $this->addTransaction(
                        'sales_return',
                        'credit',
                        date('Y-m-d'),
                        $sales_return->market->id,
                        $sales_return->total,
                        'sales_return_id',
                        $sales_return->id
                    );     
                //Transaction

                //Transaction
                   if($sales_return->cash_paid > 0) {
                       $this->addTransaction(
                            'sales_return',
                            'debit',
                            date('Y-m-d'),
                            $sales_return->market->id,
                            $sales_return->cash_paid,
                            'sales_return_id',
                            $sales_return->id
                        );
                    }     
                //Transaction   

                //Update Balance
                    $this->partyBalanceUpdate($sales_return->market->id);
                //Update Balance 

                $this->emailtoparty($sales_return->id);    

            }
            if(isset($input['image'][0]) && $input['image'][0]){
                $cacheUpload = $this->uploadRepository->getByUuid($input['image'][0]);
                $mediaItem = $cacheUpload->getMedia('image')->first();
                $mediaItem->copy($sales_return, 'image');
            }

        } catch (ValidatorException $e) {
            Flash::error($e->getMessage());
        }
        Flash::success(__('Save successfully',['operator' => __('Sales Return')]));
        return redirect(route('salesReturn.index'));

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\SalesReturn  $SalesReturn
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        $sales_return = $this->salesReturnRepository->with('items')->with('items.uom')->with('items.product')->findWithoutFail($id);
        if (empty($sales_return)) {
            Flash::error(__('Not Found',['operator' => __('Sales Return')]));
            return redirect(route('salesReturn.index'));
        }
        if($request->ajax()) {
            return ['status' => 'success', 'data' => $sales_return]; 
        }
        return view('sales_return.show',compact('sales_return'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\SalesReturn  $SalesReturn
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {   
        $sales_return = $this->salesReturnRepository->findWithoutFail($id);
        if (empty($sales_return)) {
            Flash::error(__('Not Found',['operator' => __('Sales Return')]));
            return redirect(route('salesReturn.index'));
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

        return view('sales_return.edit',compact('users','products','payment_types','party_types','party_streams','party_sub_types', 'sales_return'));

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\SalesReturn  $SalesReturn
     * @return \Illuminate\Http\Response
     */
    public function update($id, Request $request)
    {  
        $sales_return_old = $this->salesReturnRepository->findWithoutFail($id);
        if (empty($sales_return_old)) {
            Flash::error(__('Not Found',['operator' => __('Sales Return')]));
            return redirect(route('salesReturn.index'));
        }

        $input = $request->all();
        try {
            $input['updated_by'] = auth()->user()->id;
            $sales_return        = $this->salesReturnRepository->update($input,$id);
            
            if($sales_return) {
                for($i=0; $i<count($input['product_id']); $i++) :
                    $items = array(
                        'sales_return_id'   => $sales_return->id,
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
                    if(isset($input['return_item_id'][$i]) && $input['return_item_id'][$i] > 0) {
                        $return_item = $this->salesReturnItemsRepository->update($items,$input['return_item_id'][$i]);
                        //Inventory
                            $this->updateInventory(
                                $return_item->product->id,
                                $sales_return->market_id,
                                'sales_return',
                                'add',
                                $return_item->quantity,
                                $return_item->unit,
                                $return_item->product_name,
                                'sales_return_item_id',
                                $return_item->id
                            );
                            $this->productStockupdate($return_item->product->id);
                        //Inventory
                    } else {
                        $return_item = $this->salesReturnItemsRepository->create($items);
                        //Inventory
                            $this->addInventory(
                                $return_item->product->id,
                                $sales_return->market_id,
                                'sales_return',
                                'add',
                                $return_item->quantity,
                                $return_item->unit,
                                $return_item->product_name,
                                'sales_return_item_id',
                                $return_item->id
                            );
                            $this->productStockupdate($return_item->product->id);
                        //Inventory
                    }    

                endfor;

                $sales_return->market->activity()->create([
                    'market_id'  => $sales_return->market->id,
                    'action'     => 'Sales Return Updated',
                    'notes'      => 'Sales Return '.$sales_return->code.' total updated from '.number_format($sales_return_old->total,'2','.','').' to '.number_format($sales_return->total,'2','.','').'',
                    'status'     => 'completed',
                    'created_by' => auth()->user()->id
                ]);

                //Transaction
                   $this->updateTransaction(
                        'sales_return',
                        'credit',
                        date('Y-m-d'),
                        $sales_return->market->id,
                        $sales_return->total,
                        'sales_return_id',
                        $sales_return->id
                    );     
                //Transaction

                //Transaction
                    if($sales_return_old->cash_paid > 0) {
                        $this->updateTransaction(
                            'sales',
                            'debit',
                            date('Y-m-d'),
                            $sales_return->market->id,
                            $sales_return->cash_paid,
                            'sales_return_id',
                            $sales_return->id
                        );
                    } else {
                        if($sales_return->cash_paid > 0) {
                           $this->addTransaction(
                                'sales_return',
                                'debit',
                                date('Y-m-d'),
                                $sales_return->market->id,
                                $sales_return->cash_paid,
                                'sales_return_id',
                                $sales_return->id
                            );
                        }
                    }    
                //Transaction   

                //Update Balance
                    $this->partyBalanceUpdate($sales_return->market->id);
                //Update Balance   

                if(isset($input['delete_item_id']) && count($input['delete_item_id']) > 0) {
                    foreach($input['delete_item_id'] as $deleteid) {
                        
                        $return_item = $this->salesReturnItemsRepository->findWithoutFail($deleteid);
                        $this->salesReturnItemsRepository->delete($deleteid);
                        $this->productStockupdate($return_item->product->id);

                    }
                } 

                //Update Rewards
                    /*$this->addUpdatePurchaseRewards(
                        $sales_return->market_id,
                        $sales_return->total,
                        'sales_return_id',
                        $sales_return->id,
                        'update'
                    );*/
                //Update Rewards     

            }
            if(isset($input['image'][0]) && $input['image'][0]){
                $cacheUpload = $this->uploadRepository->getByUuid($input['image'][0]);
                $mediaItem = $cacheUpload->getMedia('image')->first();
                $mediaItem->copy($sales_return, 'image');
            }

        } catch (ValidatorException $e) {
            Flash::error($e->getMessage());
        }
        Flash::success(__('Save successfully',['operator' => __('Sales Return')]));
        return redirect(route('salesReturn.index'));
    
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\SalesReturn  $SalesReturn
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $sales_return = $this->salesReturnRepository->with('market')->with('items')->findWithoutFail($id);
        if (empty($sales_return)) {
            Flash::error('Sales Return not found');
            return redirect(route('salesReturn.index'));
        }

        $this->salesReturnRepository->delete($id);

        if(count($sales_return->items) > 0) {
            foreach($sales_return->items as $item) {
                $this->productStockupdate($item->product->id);
            }
        }
        $this->partyBalanceUpdate($sales_return->market->id);
        $this->calculateRewardBalance($sales_return->market->id);

        $sales_return->market->activity()->create([
            'market_id'  => $sales_return->market->id,
            'action'     => 'Sales Return Deleted',
            'notes'      => 'Sales Return '.$sales_return->code.' Deleted',
            'status'     => 'completed',
            'created_by' => auth()->user()->id
        ]);

        Flash::success(__('Deleted successfully',['operator' => __('Sales Return')]));
        return redirect(route('salesReturn.index'));
    }


    public function print($id,$type,$view_type,Request $request)
    {   
        $sales_return = $this->salesReturnRepository->with('market')->with('items')->findWithoutFail($id);
        if (empty($sales_return)) {
            Flash::error('Sales Return not found');
            return redirect(route('salesReturn.index'));
        }
        $words    = $this->amounttoWords($sales_return->total);
        $currency = '<span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span>';
        $pdf      = PDF::loadView('sales_return.print', compact('sales_return','type','currency','words'));
        $filename = 'SI-'.$sales_return->code.'-'.$sales_return->market->name.'.pdf';
        
        if($view_type=='print') {
            return $pdf->stream($filename);
        } elseif($view_type=='download') {
            return $pdf->download($filename);
        } else {
            return view('sales_return.thermal_print', compact('sales_return','type','currency','words'));
        }
    }


    public function emailtoparty($id) {

        $sales_return = $this->salesReturnRepository->findWithoutFail($id);
        if (empty($sales_return)) {
            Flash::error('Sales Return not found');
            return redirect(route('salesReturn.index'));
        }
        $type       = 1;
        $words      = $this->amounttoWords($sales_return->total);
        $currency   = '<span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span>';
        $pdf        =   PDF::setOptions([
                            'isHtml5ParserEnabled' => true, 
                            'isRemoteEnabled' => true, 
                            'dpi' => 100
                        ])->loadView('sales_return.print',compact('sales_return','type','words','currency'));

        $invoice_url = url(base64_encode($sales_return->id).'/SalesReturn'); 
        $message     = "Hi ".$sales_return->market->name.",<br>

                        Here's Sales Return #".$sales_return->code." for ".number_format($sales_return->total,2,'.','').".<br>

                        View your bill online: ".url(base64_encode($sales_return->id).'/SalesReturn')."<br>

                        From your online bill you can print a PDF or create a free login and view your outstanding bills <br>.

                        If you have any questions, please let us know. <br>";

        //Notification
            $notification_data = [
                'greeting'    => "New Sales Return Generated!",
                'body'        => $message,
                'thanks'      => 'Thank you',
                'pdf_file'    => $pdf,
                'filename'    => 'Sales Return #'.$sales_return->code.'.pdf',
                'datas'       => array(
                    'title'   => 'Sales Return '.$sales_return->code.' from '.setting('app_name').' for '.$sales_return->market->name, //"New Sales Invoice Generated!",
                    'message' => $message
                )
            ];
            $notify = $sales_return->market->user->notify(new SalesReturnNotification($notification_data));
            $sales_return->market->activity()->create([
                'market_id'  => $sales_return->market->id,
                'action'     => 'Sales Return Sent',
                'notes'      => 'Sales Return '.$sales_return->code.' sent to '.$sales_return->market->user->email,
                'status'     => 'completed',
                'created_by' => auth()->user()->id
            ]);
        //Notification
            
    }


    public function frontView(Request $request, $id) {

        $id              = base64_decode($id);
        $sales_return    = $this->salesReturnRepository->with('market')->with('items')->findWithoutFail($id);
        if (empty($sales_return)) {
            Flash::error('Sales Return not found');
            return redirect(route('salesReturn.index'));
        }
        $words    = $this->amounttoWords($sales_return->total);
        $currency = '<span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span>';
        return view('sales_return.front_end_invoice', compact('sales_return','currency','words'));
    }


    public function DownloadPDF(Request $request, $id) {
        $id            = base64_decode($id);
        $type          = 1;
        $sales_return  = $this->salesReturnRepository->with('market')->with('items')->findWithoutFail($id);
        if (empty($sales_return)) {
            Flash::error('Sales Return not found');
            return redirect(route('salesReturn.index'));
        }
        $words    = $this->amounttoWords($sales_return->total);
        $currency = '<span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span>';
        $pdf      = PDF::loadView('sales_return.print', compact('sales_return','type','currency','words'));
        $filename = $sales_return->code.'-'.$sales_return->market->name.'.pdf';
        return $pdf->download($filename);
    }


}
