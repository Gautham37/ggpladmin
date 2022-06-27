<?php

namespace App\Http\Controllers;

use App\Models\PurchaseReturn;
use App\DataTables\PurchaseReturnDataTable;
use App\Http\Requests;
use App\Http\Requests\CreatePurchaseReturnRequest;
use App\Http\Requests\UpdatePurchaseReturnRequest;
use App\Repositories\PurchaseReturnRepository;
use App\Repositories\PurchaseReturnItemsRepository;
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
use App\Notifications\PurchaseReturnNotification;

class PurchaseReturnController extends Controller
{   
    /** @var  MarketRepository */
    private $marketRepository;

    /** @var  ProductRepository */
    private $productRepository;

    /** @var  PurchaseReturnRepository */
    private $purchaseReturnRepository;

    /** @var  PurchaseReturnItemsRepository */
    private $purchaseReturnItemsRepository;

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

    public function __construct(MarketRepository $marketRepo, PurchaseReturnRepository $purchaseReturnRepo, PurchaseReturnItemsRepository $purchaseReturnItemsRepo,  CustomFieldRepository $customFieldRepo , UploadRepository $uploadRepo, ProductRepository $productRepo, InventoryRepository $inventoryRepo, UserRepository $userRepo, DeliveryAddressRepository $deliveryAddressRepo, TransactionRepository $transactionRepo, ShortLinkRepository $shortLinkRepo, PaymentModeRepository $paymentModeRepo, DeliveryZonesRepository $deliveryZonesRepo, PartyTypesRepository $partyTypesRepo, PartySubTypesRepository $partySubTypesRepo, PartyStreamRepository $partyStreamRepo)
    {
        parent::__construct();
        $this->marketRepository              = $marketRepo;
        $this->productRepository             = $productRepo;
        $this->purchaseReturnRepository      = $purchaseReturnRepo;
        $this->purchaseReturnItemsRepository = $purchaseReturnItemsRepo;
        $this->inventoryRepository           = $inventoryRepo;
        $this->transactionRepository         = $transactionRepo;
        $this->userRepository                = $userRepo;
        $this->deliveryAddressRepository     = $deliveryAddressRepo;
        $this->customFieldRepository         = $customFieldRepo;
        $this->uploadRepository              = $uploadRepo;
        $this->shortLinkRepository           = $shortLinkRepo;
        $this->paymentModeRepository         = $paymentModeRepo; 
        $this->deliveryZonesRepository       = $deliveryZonesRepo;
        $this->partyTypesRepository          = $partyTypesRepo;
        $this->partySubTypesRepository       = $partySubTypesRepo;
        $this->partyStreamRepository         = $partyStreamRepo;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(PurchaseReturnDatatable $purchaseReturnDataTable)
    {
        $purchaseReturn = $purchaseReturnDataTable;
        if(Request('start_date')!='' && Request('end_date')!='' ) {
           $purchaseReturn->with('start_date',Request('start_date'))->with('end_date',Request('end_date'));  
        }
        return $purchaseReturn->render('purchase_return.index');
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
        $return_no  = setting('app_invoice_prefix').'-PR-'.(autoIncrementId('purchase_return'));
        return view('purchase_return.create',compact('users','products','return_no','payment_types','party_types','party_streams','party_sub_types'));
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
     
    public function store(CreatePurchaseReturnRequest $request)
    {
        $input = $request->all();
        try {
            $input['created_by'] = auth()->user()->id;
            $purchase_return        = $this->purchaseReturnRepository->create($input);
            
            if($purchase_return) {
                for($i=0; $i<count($input['product_id']); $i++) :
                    $items = array(
                        'purchase_return_id'    => $purchase_return->id,
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
                    $return_item = $this->purchaseReturnItemsRepository->create($items);

                    //Inventory
                        $this->addInventory(
                            $return_item->product->id,
                            $purchase_return->market_id,
                            'purchase_return',
                            'reduce',
                            $return_item->quantity,
                            $return_item->unit,
                            $return_item->product_name,
                            'purchase_return_item_id',
                            $return_item->id
                        );
                        $this->productStockupdate($return_item->product->id);
                    //Inventory    

                endfor;

                //Transaction
                   $this->addTransaction(
                        'purchase_return',
                        'debit',
                        date('Y-m-d'),
                        $purchase_return->market->id,
                        $purchase_return->total,
                        'purchase_return_id',
                        $purchase_return->id
                    );     
                //Transaction

                //Transaction
                   if($purchase_return->cash_paid > 0) {
                       $this->addTransaction(
                            'purchase_return',
                            'credit',
                            date('Y-m-d'),
                            $purchase_return->market->id,
                            $purchase_return->cash_paid,
                            'purchase_return_id',
                            $purchase_return->id
                        );
                    }     
                //Transaction   

                //Update Balance
                    $this->partyBalanceUpdate($purchase_return->market->id);
                //Update Balance 

                $this->emailtoparty($purchase_return->id);    
                    
            }
            if(isset($input['image'][0]) && $input['image'][0]){
                $cacheUpload = $this->uploadRepository->getByUuid($input['image'][0]);
                $mediaItem = $cacheUpload->getMedia('image')->first();
                $mediaItem->copy($purchase_return, 'image');
            }

        } catch (ValidatorException $e) {
            Flash::error($e->getMessage());
        }
        Flash::success(__('Save successfully',['operator' => __('Purchase Return')]));
        return redirect(route('purchaseReturn.index'));

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\PurchaseReturn  $PurchaseReturn
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        $purchase_return = $this->purchaseReturnRepository->with('items')->with('items.uom')->with('items.product')->findWithoutFail($id);
        if (empty($purchase_return)) {
            Flash::error(__('Not Found',['operator' => __('Puchase Return')]));
            return redirect(route('purchaseReturn.index'));
        }
        if($request->ajax()) {
            return ['status' => 'success', 'data' => $purchase_return]; 
        }
        return view('purchase_return.show',compact('purchase_return'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\PurchaseReturn  $PurchaseReturn
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {   
        $purchase_return = $this->purchaseReturnRepository->findWithoutFail($id);
        if (empty($purchase_return)) {
            Flash::error(__('Not Found',['operator' => __('Purchase Return')]));
            return redirect(route('purchaseReturn.index'));
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

        return view('purchase_return.edit',compact('users','products','payment_types','party_types','party_streams','party_sub_types','purchase_return'));

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\PurchaseReturn  $PurchaseReturn
     * @return \Illuminate\Http\Response
     */
    public function update($id, Request $request)
    {  
        $purchase_return_old = $this->purchaseReturnRepository->findWithoutFail($id);
        if (empty($purchase_return_old)) {
            Flash::error(__('Not Found',['operator' => __('Purchase Return')]));
            return redirect(route('purchaseReturn.index'));
        }

        $input = $request->all();
        try {
            $input['updated_by'] = auth()->user()->id;
            $purchase_return     = $this->purchaseReturnRepository->update($input,$id);
            
            if($purchase_return) {
                for($i=0; $i<count($input['product_id']); $i++) :
                    $items = array(
                        'purchase_return_id'    => $purchase_return->id,
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
                    if(isset($input['return_item_id'][$i]) && $input['return_item_id'][$i] > 0) {
                        $return_item = $this->purchaseReturnItemsRepository->update($items,$input['return_item_id'][$i]);
                        //Inventory
                            $this->updateInventory(
                                $return_item->product->id,
                                $purchase_return->market_id,
                                'purchase_return',
                                'reduce',
                                $return_item->quantity,
                                $return_item->unit,
                                $return_item->product_name,
                                'purchase_return_item_id',
                                $return_item->id
                            );
                            $this->productStockupdate($return_item->product->id);
                        //Inventory
                    } else {
                        $return_item = $this->purchaseReturnItemsRepository->create($items);
                        //Inventory
                            $this->addInventory(
                                $return_item->product->id,
                                $purchase_return->market_id,
                                'purchase_return',
                                'reduce',
                                $return_item->quantity,
                                $return_item->unit,
                                $return_item->product_name,
                                'purchase_return_item_id',
                                $return_item->id
                            );
                            $this->productStockupdate($return_item->product->id);
                        //Inventory
                    }    

                endfor;

                //Transaction
                   $this->updateTransaction(
                        'purchase_return',
                        'debit',
                        date('Y-m-d'),
                        $purchase_return->market->id,
                        $purchase_return->total,
                        'purchase_return_id',
                        $purchase_return->id
                    );     
                //Transaction

                //Transaction
                    if($purchase_return_old->cash_paid > 0) {
                        $this->updateTransaction(
                            'purchase',
                            'credit',
                            date('Y-m-d'),
                            $purchase_return->market->id,
                            $purchase_return->cash_paid,
                            'purchase_return_id',
                            $purchase_return->id
                        );
                    } else {
                        if($purchase_return->cash_paid > 0) {
                           $this->addTransaction(
                                'purchase_return',
                                'credit',
                                date('Y-m-d'),
                                $purchase_return->market->id,
                                $purchase_return->cash_paid,
                                'purchase_return_id',
                                $purchase_return->id
                            );
                        }
                    }    
                //Transaction   

                //Update Balance
                    $this->partyBalanceUpdate($purchase_return->market->id);
                //Update Balance   

                if(isset($input['delete_item_id']) && count($input['delete_item_id']) > 0) {
                    foreach($input['delete_item_id'] as $deleteid) {
                        
                        $return_item = $this->purchaseReturnItemsRepository->findWithoutFail($deleteid);
                        $this->purchaseReturnItemsRepository->delete($deleteid);
                        $this->productStockupdate($return_item->product->id);

                    }
                } 

                //Update Rewards
                    $this->addUpdatePurchaseRewards(
                        $purchase_return->market_id,
                        $purchase_return->total,
                        'purchase_return_id',
                        $purchase_return->id,
                        'update'
                    );
                //Update Rewards     

            }
            if(isset($input['image'][0]) && $input['image'][0]){
                $cacheUpload = $this->uploadRepository->getByUuid($input['image'][0]);
                $mediaItem = $cacheUpload->getMedia('image')->first();
                $mediaItem->copy($purchase_return, 'image');
            }

        } catch (ValidatorException $e) {
            Flash::error($e->getMessage());
        }
        Flash::success(__('Save successfully',['operator' => __('Purchase Return')]));
        return redirect(route('purchaseReturn.index'));
    
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\PurchaseReturn  $PurchaseReturn
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $purchase_return = $this->purchaseReturnRepository->with('market')->with('items')->findWithoutFail($id);
        if (empty($purchase_return)) {
            Flash::error('Purchase Return not found');
            return redirect(route('purchaseReturn.index'));
        }

        $this->purchaseReturnRepository->delete($id);

        if(count($purchase_return->items) > 0) {
            foreach($purchase_return->items as $item) {
                $this->productStockupdate($item->product->id);
            }
        }
        $this->partyBalanceUpdate($purchase_return->market->id);
        $this->calculateRewardBalance($purchase_return->market->id);

        Flash::success(__('Deleted successfully',['operator' => __('Purchase Return')]));
        return redirect(route('purchaseReturn.index'));
    }


    public function print($id,$type,$view_type,Request $request)
    {   
        $purchase_return = $this->purchaseReturnRepository->with('market')->with('items')->findWithoutFail($id);
        if (empty($purchase_return)) {
            Flash::error('Purchase Return not found');
            return redirect(route('purchaseReturn.index'));
        }
        $words    = $this->amounttoWords($purchase_return->total);
        $currency = '<span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span>';
        $pdf      = PDF::loadView('purchase_return.print', compact('purchase_return','type','currency','words'));
        $filename = $purchase_return->code.'-'.$purchase_return->market->name.'.pdf';
        
        if($view_type=='print') {
            return $pdf->stream($filename);
        } elseif($view_type=='download') {
            return $pdf->download($filename);
        } else {
            return view('purchase_return.thermal_print', compact('purchase_return','type','currency','words'));
        }
    }

    public function emailtoparty($id) {

        $purchase_return = $this->purchaseReturnRepository->findWithoutFail($id);
        if (empty($purchase_return)) {
            Flash::error('Purchase Return not found');
            return redirect(route('purchaseReturn.index'));
        }
        $type       = 1;
        $words      = $this->amounttoWords($purchase_return->total);
        $currency   = '<span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span>';
        $pdf        =   PDF::setOptions([
                            'isHtml5ParserEnabled' => true, 
                            'isRemoteEnabled' => true, 
                            'dpi' => 100
                        ])->loadView('purchase_return.print',compact('purchase_return','type','words','currency'));

        $invoice_url = url(base64_encode($purchase_return->id).'/PurchaseReturn'); 
        $message     = "Hi ".$purchase_return->market->name.",<br>

                        Here's Purchase Return #".$purchase_return->code." for ".number_format($purchase_return->total,2,'.','').".<br>

                        View your bill online: ".url(base64_encode($purchase_return->id).'/PurchaseReturn')."<br>

                        From your online bill you can print a PDF or create a free login and view your outstanding bills <br>.

                        If you have any questions, please let us know. <br>";

        //Notification
            $notification_data = [
                'greeting'    => "New Purchase Return Generated!",
                'body'        => $message,
                'thanks'      => 'Thank you',
                'pdf_file'    => $pdf,
                'filename'    => 'Purchase Return #'.$purchase_return->code.'.pdf',
                'datas'       => array(
                    'title'   => 'Purchase Return '.$purchase_return->code.' from '.setting('app_name').' for '.$purchase_return->market->name, //"New Purchase Return Generated!",
                    'message' => $message
                )
            ];
            $notify = $purchase_return->market->user->notify(new PurchaseReturnNotification($notification_data));
            $purchase_return->market->activity()->create([
                'market_id'  => $purchase_return->market->id,
                'action'     => 'Purchase Return Sent',
                'notes'      => 'Purchase Return '.$purchase_return->code.' sent to '.$purchase_return->market->user->email,
                'created_by' => auth()->user()->id
            ]);
        //Notification
            
    }


    public function frontView(Request $request, $id) {

        $id                 = base64_decode($id);
        $purchase_return    = $this->purchaseReturnRepository->with('market')->with('items')->findWithoutFail($id);
        if (empty($purchase_return)) {
            Flash::error('Purchase Return not found');
            return redirect(route('purchaseReturn.index'));
        }
        $words    = $this->amounttoWords($purchase_return->total);
        $currency = '<span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span>';
        return view('purchase_return.front_end_invoice', compact('purchase_return','currency','words'));
    }


    public function DownloadPDF(Request $request, $id) {
        $id               = base64_decode($id);
        $type             = 1;
        $purchase_return  = $this->purchaseReturnRepository->with('market')->with('items')->findWithoutFail($id);
        if (empty($purchase_return)) {
            Flash::error('Purchase Return not found');
            return redirect(route('purchaseReturn.index'));
        }
        $words    = $this->amounttoWords($purchase_return->total);
        $currency = '<span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span>';
        $pdf      = PDF::loadView('purchase_return.print', compact('purchase_return','type','currency','words'));
        $filename = $purchase_return->code.'-'.$purchase_return->market->name.'.pdf';
        return $pdf->download($filename);
    }

}
