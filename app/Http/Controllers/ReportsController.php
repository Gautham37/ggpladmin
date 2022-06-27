<?php

namespace App\Http\Controllers;

use App\DataTables\ExpensesReportDataTable;
use App\DataTables\ExpensesCategoryReportDataTable;
use App\DataTables\StockSummaryDataTable;
use App\DataTables\RateListDataTable;
use App\DataTables\ItemSalesSummaryDataTable;
use App\DataTables\LowStockSummaryDataTable;
use App\DataTables\StockDetailReportDataTable;
use App\Http\Requests;
use App\Repositories\ExpensesCategoryRepository;
use App\Repositories\ProductRepository;
use App\Repositories\PurchaseInvoiceRepository;
use App\Repositories\SalesInvoiceRepository;
use App\Repositories\PurchaseReturnRepository;
use App\Repositories\SalesReturnRepository;
use App\Repositories\PaymentInRepository;
use App\Repositories\PaymentOutRepository;
use App\Repositories\MarketRepository;
use App\Repositories\CustomFieldRepository;
use App\Repositories\UploadRepository;
use App\Repositories\PaymentModeRepository;
use App\Repositories\RoleRepository;
use App\Repositories\TransactionRepository;
use App\Repositories\LoyalityPointsRepository;
use App\Repositories\LoyalityPointUsageRepository;
use App\Repositories\InventoryRepository;
use App\Repositories\PartyTypesRepository;
use App\Repositories\PartySubTypesRepository;


use Flash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Prettus\Validator\Exceptions\ValidatorException;
use App\Models\Expenses;
use App\Models\TransactionTrack;
use App\Models\InventoryTrack;
use App\Models\User;
use DataTables;
use DB;
use PDF;
use Carbon\Carbon;

use App\Exports\SalesSummaryExport;
use App\Exports\PartyLedgerExport;
use App\Exports\DaybookExport;
use App\Exports\StockSummaryExport;
use App\Exports\RateListExport;
use App\Exports\ItemSalesExport;
use App\Exports\LowstockExport;
use App\Exports\PartywiseExport;
use App\Exports\ExpensecategoryExport;
use App\Exports\ExpensetransactionExport;
use App\Exports\PartyreportbyitemExport;
use App\Exports\ItemreportbypartyExport;
use App\Exports\BitwiseprofitExport;
use App\Exports\PopularProductsExport;
use App\Exports\ProfitableProductsExport;
use App\Exports\StaffLoginExport;
use App\Exports\ProductsExport;
use App\Exports\CustomersExport;
use App\Exports\StockPurchaseExport;
use App\Exports\DeliveryExport;
use App\Exports\WastageExport;
use Maatwebsite\Excel\Facades\Excel;


class ReportsController extends Controller
{
    /** @var ExpenseCategoryRepository */
    private $expensesCategoryRepository;

    /** @var ProductRepository */
    private $productRepository;

    /* @var PaymentModeRepository */
    private $paymentModeRepository;

    /** @var  PurchaseInvoiceRepository */
    private $purchaseInvoiceRepository;

    /** @var  SalesInvoiceRepository */
    private $salesInvoiceRepository;

    /** @var  PurchaseReturnRepository */
    private $purchaseReturnRepository;

    /** @var  SalesReturnRepository */
    private $salesReturnRepository;

    /** @var  PaymentInRepository */
    private $paymentInRepository;

    /** @var  PaymentOutRepository */
    private $paymentOutRepository;

    /* @var MarketRepository */
    private $marketRepository;
    
    /* @var RoleRepository */
    private $roleRepository;

    /* @var TransactionRepository */
    private $transactionRepository;

    /* @var LoyalityPointsRepository */
    private $loyalityPointsRepository;

    /* @var LoyalityPointUsageRepository */
    private $loyalityPointUsageRepository;

    /* @var InventoryRepository */
    private $inventoryRepository;
    
    /* @var PartyTypesRepository */
    private $partyTypesRepository;
    
    /* @var PartySubTypesRepository */
    private $partySubTypesRepository;

    /**
  * @var UploadRepository
  */
    private $uploadRepository;

    public function __construct(ExpensesCategoryRepository $expensesCatRepo, UploadRepository $uploadRepo, PaymentModeRepository $paymentMRepo, ProductRepository $productRepo, PurchaseInvoiceRepository $purchaseInvoiceRepo, SalesInvoiceRepository $salesInvoiceRepo, PaymentInRepository $paymentInRepo, PaymentOutRepository $paymentOutRepo, MarketRepository $marketRepo, PurchaseReturnRepository $purchaseReturnRepo, SalesReturnRepository $salesReturnRepo, RoleRepository $roleRepo, TransactionRepository $transactionRepo, LoyalityPointsRepository $loyalityPointsRepo, LoyalityPointUsageRepository $loyalityPointUsageRepo, InventoryRepository $inventoryRepo, PartyTypesRepository $partyTypesRepo, PartySubTypesRepository $partySubTypesRepo)
    {
        parent::__construct();
        $this->expensesCategoryRepository    = $expensesCatRepo;
        $this->productRepository             = $productRepo;
        $this->uploadRepository              = $uploadRepo;
        $this->paymentModeRepository         = $paymentMRepo;
        $this->purchaseInvoiceRepository     = $purchaseInvoiceRepo;
        $this->salesInvoiceRepository        = $salesInvoiceRepo;
        $this->purchaseReturnRepository      = $purchaseReturnRepo;
        $this->salesReturnRepository         = $salesReturnRepo;
        $this->paymentInRepository           = $paymentInRepo;
        $this->paymentOutRepository          = $paymentOutRepo;
        $this->marketRepository              = $marketRepo;
        $this->roleRepository                = $roleRepo;

        $this->transactionRepository         = $transactionRepo;
        $this->loyalityPointsRepository      = $loyalityPointsRepo;
        $this->loyalityPointUsageRepository  = $loyalityPointUsageRepo;
        $this->inventoryRepository           = $inventoryRepo;
        
        $this->partyTypesRepository           = $partyTypesRepo;
        $this->partySubTypesRepository        = $partySubTypesRepo;
    }

    public function index() {
        return view('reports.index');
    }

    public function show(Request $request) {

        $report_type         = $request->report_type;


        if($report_type=='party-transaction-report') 
        {

            $market_id           = $request->market_id;
            $start_date          = $request->start_date;
            $end_date            = $request->end_date;
            $transaction         = $request->transaction;
            $type                = $request->type;
            
            $query     = $this->transactionRepository->where('id','>',0);
            if(isset($market_id) && $market_id!='') {
                $query->where('market_id',$market_id);
            }
            if(isset($transaction) && $transaction!='') {
                $query->where('category',$transaction);
            }
            if(isset($start_date) && isset($end_date) && $start_date!='' && $end_date!='') {
                $query->whereBetween('date', [Carbon::parse($start_date)->format('Y-m-d'), Carbon::parse($end_date)->format('Y-m-d')]);
            }
            $data               = $query->get();
            $total_amount       = $query->sum('amount');


            $dataTable = Datatables::of($data);
            $dataTable->addIndexColumn();

            $table= $dataTable
                    ->addColumn('date', function($transaction){
                        return $transaction->date->format('d M Y');
                    })
                    ->addColumn('category', function($transaction){
                        return ucfirst($transaction->category);
                    })
                    ->addColumn('transaction_no', function($transaction){
                        if($transaction->category=='sales') {
                            return '<a href="'.route('salesInvoice.show',$transaction->salesinvoice->id).'">'.$transaction->salesinvoice->code.'</a>';
                        } elseif($transaction->category=='sales_return') {
                            return '<a href="'.route('salesReturn.show',$transaction->salesreturn->id).'">'.$transaction->salesreturn->code.'</a>';
                        } elseif($transaction->category=='purchase') {
                            return '<a href="'.route('purchaseInvoice.show',$transaction->purchaseinvoice->id).'">'.$transaction->purchaseinvoice->code.'</a>';
                        } elseif($transaction->category=='purchase_return') {
                            return '<a href="'.route('purchaseReturn.show',$transaction->purchasereturn->id).'">'.$transaction->purchasereturn->code.'</a>';
                        } elseif($transaction->category=='payment_in') {
                            return '<a href="'.route('paymentIn.show',$transaction->paymentin->id).'">'.$transaction->paymentin->code.'</a>';
                        } elseif($transaction->category=='payment_out') {
                            return '<a href="'.route('paymentOut.show',$transaction->paymentout->id).'">'.$transaction->paymentout->code.'</a>';
                        }
                    })
                    ->addColumn('amount', function($transaction){
                        return setting('default_currency').number_format($transaction->amount,'2','.','');
                    })
                    ->addColumn('total', function($transaction){
                        return number_format($transaction->amount,'2','.','');
                    })
                    ->addColumn('status', function($transaction) {

                        if($transaction->category=='sales' || $transaction->category=='sales_return' || $transaction->category=='purchase' || $transaction->category=='purchase_return') :

                            if($transaction->category=='sales') {
                                $paid  = $transaction->salesinvoice->cash_paid + $transaction->salesinvoice->totalsettle('sales','sales_invoice_id',$transaction->salesinvoice->id);
                                $due   = $transaction->salesinvoice->amount_due;
                                $total = $transaction->salesinvoice->total;
                            }
                            
                            if($transaction->category=='sales_return') {
                                $paid  = $transaction->salesreturn->cash_paid + $transaction->salesreturn->totalsettle('sales','sales_return_id',$transaction->salesreturn->id);
                                $due   = $transaction->salesreturn->amount_due;
                                $total = $transaction->salesreturn->total;
                            }

                            if($transaction->category=='purchase') {
                                $paid  = $transaction->purchaseinvoice->cash_paid + $transaction->purchaseinvoice->totalsettle('sales','purchase_invoice_id',$transaction->purchaseinvoice->id);
                                $due   = $transaction->purchaseinvoice->amount_due;
                                $total = $transaction->purchaseinvoice->total;
                            }

                            if($transaction->category=='purchase_return') {
                                $paid  = $transaction->purchasereturn->cash_paid + $transaction->purchasereturn->totalsettle('sales','purchase_return_id',$transaction->purchasereturn->id);
                                $due   = $transaction->purchasereturn->amount_due;
                                $total = $transaction->purchasereturn->total;    
                            }

                            if($due > 0 && $due < $total) {
                                return '<span class="btn btn-sm btn-warning">Partially Paid ('.setting('default_currency').' '.$paid.') </span>';
                            } elseif($due == 0) {
                                return '<span class="btn btn-sm btn-success">Paid</span>';
                            } elseif($due == $total) {
                                return '<span class="btn btn-sm btn-danger">Unpaid</span>';
                            }
                        endif;

                    })        
                    ->rawColumns(['date','transaction_no','status'])
                    ->make(true);

            if(isset($type)) {

                $datas = $table->getData()->data;
                switch ($type) {
                    case 'export':
                        //return Excel::download(new WastageExport($start_date,$end_date,$product_id), 'wastage_report.xlsx');
                    break;

                    case 'print':
                        $pdf = PDF::loadView('reports.export.transaction_report', compact('datas','start_date','end_date','transaction','total_amount'));
                        $filename = setting('app_name').'.pdf';
                        return $pdf->stream($filename);
                    break;

                    case 'download':
                        $pdf = PDF::loadView('reports.export.transaction_report', compact('datas','start_date','end_date','transaction','total_amount'));
                        $filename = setting('app_name').'.pdf';
                        return $pdf->download($filename);
                    break;                    
                }

            } else {
                return $table;
            }

        }


        if($report_type=='party-ledger-report') 
        {
            $market_id           = $request->market_id;
            $start_date          = $request->start_date;
            $end_date            = $request->end_date;
            $type                = $request->type;
            
            $market              = $this->marketRepository->findWithoutFail($market_id);
            $query               = $this->transactionRepository->where('id','>',0);
            if(isset($market_id) && $market_id!='') {
                $query->where('market_id',$market_id);
            }
            if(isset($start_date) && isset($end_date) && $start_date!='' && $end_date!='') {
                $query->whereBetween('date', [Carbon::parse($start_date)->format('Y-m-d'), Carbon::parse($end_date)->format('Y-m-d')]);
            }
            $data               = $query->get();
            $opening_balance[]  = $this->openingBalanceCalc($market_id,$start_date);
            
            foreach($data as $key => $value) {
                $data[$key]->closing_balance = $this->closingBalanceCalc(array_sum($opening_balance),$market_id,$value->type,$value->amount);
                unset($opening_balance);
                $opening_balance[] = $data[$key]->closing_balance;
            }

            $dataTable = Datatables::of($data);
            $dataTable->addIndexColumn();

            $table= $dataTable
                    ->addColumn('date', function($transaction){
                        ($transaction->type=='debit') ? $class = 'text-danger' : $class = 'text-success' ;
                        return '<span class="'.$class.'">'.$transaction->date->format('d M Y').'</span>';
                    })
                    ->addColumn('category', function($transaction){
                        ($transaction->type=='debit') ? $class = 'text-danger' : $class = 'text-success' ;
                        return '<span class="'.$class.'">'.ucfirst($transaction->category).'</span>';
                    })
                    ->addColumn('transaction_no', function($transaction){
                        if($transaction->category=='sales') {
                            return '<a href="'.route('salesInvoice.show',$transaction->salesinvoice->id).'">'.$transaction->salesinvoice->code.'</a>';
                        } elseif($transaction->category=='sales_return') {
                            return '<a href="'.route('salesReturn.show',$transaction->salesreturn->id).'">'.$transaction->salesreturn->code.'</a>';
                        } elseif($transaction->category=='purchase') {
                            return '<a href="'.route('purchaseInvoice.show',$transaction->purchaseinvoice->id).'">'.$transaction->purchaseinvoice->code.'</a>';
                        } elseif($transaction->category=='purchase_return') {
                            return '<a href="'.route('purchaseReturn.show',$transaction->purchasereturn->id).'">'.$transaction->purchasereturn->code.'</a>';
                        } elseif($transaction->category=='payment_in') {
                            return '<a href="'.route('paymentIn.show',$transaction->paymentin->id).'">'.$transaction->paymentin->code.'</a>';
                        } elseif($transaction->category=='payment_out') {
                            return '<a href="'.route('paymentOut.show',$transaction->paymentout->id).'">'.$transaction->paymentout->code.'</a>';
                        }
                    })
                    ->addColumn('total', function($transaction) {
                        ($transaction->type=='debit') ? $class = 'text-danger' : $class = 'text-success' ;
                        return '<span class="'.$class.'">'.number_format($transaction->amount,'2','.','').'</span>';
                    })
                    ->addColumn('credit', function($transaction){
                        ($transaction->type=='debit') ? $class = 'text-danger' : $class = 'text-success' ;
                        return ($transaction->type=='credit') ? '<span class="'.$class.'">'.setting('default_currency').number_format($transaction->amount,'2','.','').'</span>' : '' ;
                    })
                    ->addColumn('debit', function($transaction){
                        ($transaction->type=='debit') ? $class = 'text-danger' : $class = 'text-success' ;
                        return ($transaction->type=='debit') ? '<span class="'.$class.'">'.setting('default_currency').number_format($transaction->amount,'2','.','').'</span>' : '' ;
                    })
                    ->addColumn('closing_balance', function($transaction){
                        ($transaction->closing_balance < 0) ? $class='text-success' : '' ;
                        ($transaction->closing_balance > 0) ? $class='text-danger' : '' ;
                        ($transaction->closing_balance == 0) ? $class='text-dark' : '' ;
                        return '<span class="'.$class.'">'.setting('default_currency').number_format($transaction->closing_balance,2,'.','').'</span>';
                    })
                    ->addColumn('status', function($transaction) {

                        if($transaction->category=='sales' || $transaction->category=='sales_return' || $transaction->category=='purchase' || $transaction->category=='purchase_return') :

                            if($transaction->category=='sales') {
                                $paid  = $transaction->salesinvoice->cash_paid + $transaction->salesinvoice->totalsettle('sales','sales_invoice_id',$transaction->salesinvoice->id);
                                $due   = $transaction->salesinvoice->amount_due;
                                $total = $transaction->salesinvoice->total;
                            }
                            
                            if($transaction->category=='sales_return') {
                                $paid  = $transaction->salesreturn->cash_paid + $transaction->salesreturn->totalsettle('sales','sales_return_id',$transaction->salesreturn->id);
                                $due   = $transaction->salesreturn->amount_due;
                                $total = $transaction->salesreturn->total;
                            }

                            if($transaction->category=='purchase') {
                                $paid  = $transaction->purchaseinvoice->cash_paid + $transaction->purchaseinvoice->totalsettle('sales','purchase_invoice_id',$transaction->purchaseinvoice->id);
                                $due   = $transaction->purchaseinvoice->amount_due;
                                $total = $transaction->purchaseinvoice->total;
                            }

                            if($transaction->category=='purchase_return') {
                                $paid  = $transaction->purchasereturn->cash_paid + $transaction->purchasereturn->totalsettle('sales','purchase_return_id',$transaction->purchasereturn->id);
                                $due   = $transaction->purchasereturn->amount_due;
                                $total = $transaction->purchasereturn->total;    
                            }

                            if($due > 0 && $due < $total) {
                                return '<span class="btn btn-sm btn-warning">Partially Paid ('.setting('default_currency').' '.$paid.') </span>';
                            } elseif($due == 0) {
                                return '<span class="btn btn-sm btn-success">Paid</span>';
                            } elseif($due == $total) {
                                return '<span class="btn btn-sm btn-danger">Unpaid</span>';
                            }
                        endif;

                    })
                    ->addColumn('discount', function($transaction) {
                        if($transaction->category=='sales') {
                            return setting('default_currency').number_format($transaction->salesinvoice->discount_total,2,'.','');
                        } elseif($transaction->category=='sales_return') {
                            return setting('default_currency').number_format($transaction->salesreturn->discount_total,2,'.','');
                        } elseif($transaction->category=='purchase') {
                            return setting('default_currency').number_format($transaction->purchaseinvoice->discount_total,2,'.','');
                        } elseif($transaction->category=='purchase_return') {
                            return setting('default_currency').number_format($transaction->purchasereturn->discount_total,2,'.','');
                        } elseif($transaction->category=='payment_in') {
                            return setting('default_currency').number_format($transaction->paymentin->discount_total,2,'.','');
                        } elseif($transaction->category=='payment_out') {
                            return setting('default_currency').number_format($transaction->paymentout->discount_total,2,',','');
                        } elseif($transaction->category=='online') {
                            return setting('default_currency').number_format($transaction->order->coupon_amount,2,',','');
                        }
                    })        
                    ->rawColumns(['date','category','total','credit','debit','closing_balance','transaction_no','status'])
                    ->make(true);

            if(isset($type)) {

                $datas = $table->getData()->data;
                switch ($type) {
                    case 'export':
                        //return Excel::download(new WastageExport($start_date,$end_date,$product_id), 'wastage_report.xlsx');
                    break;

                    case 'print':
                        $pdf = PDF::loadView('reports.export.party_statement_report', compact('datas','start_date','end_date','market'));
                        $filename = setting('app_name').'.pdf';
                        return $pdf->stream($filename);
                    break;

                    case 'download':
                        $pdf = PDF::loadView('reports.export.party_statement_report', compact('datas','start_date','end_date','market'));
                        $filename = setting('app_name').'.pdf';
                        return $pdf->download($filename);
                    break;                    
                }

            } else {
                return $table;
            }
        }


        if($report_type=='party-reward-report') 
        {
            $market_id           = $request->market_id;
            $start_date          = $request->start_date;
            $end_date            = $request->end_date;
            $market              = $this->marketRepository->findWithoutFail($market_id);
            $type                = $request->type;

            $query               = $this->loyalityPointsRepository->where('id','>',0);
            if(isset($market->user) && $market->user->affiliate_id!='') {
                $query->where('affiliate_id',$market->user->affiliate_id);
            }
            if(isset($start_date) && isset($end_date) && $start_date!='' && $end_date!='') {
                $query->whereDate('created_at','>=',Carbon::parse($start_date)->format('Y-m-d'))->whereDate('created_at','<=',Carbon::parse($end_date)->format('Y-m-d'));
            }
            $data               = $query->get();

            $dataTable = Datatables::of($data);
            $dataTable->addIndexColumn();

            $table= $dataTable
                    ->addColumn('date', function($loyality){
                        return $loyality->created_at->format('d M Y');
                    })
                    ->addColumn('category', function($loyality){
                        return strtoupper($loyality->category);
                    })
                    ->addColumn('transaction_no', function($loyality){
                        if($loyality->category=='sales_invoice') {
                            return '<a href="'.route('salesInvoice.show',$loyality->salesinvoice->id).'">'.$loyality->salesinvoice->code.'</a>';
                        } elseif($loyality->category=='online_order') {
                            return '<a href="'.route('orders.show',$loyality->onlineorder->id).'">'.$loyality->onlineorder->order_code.'</a>';
                        } else {
                            return 'Referral';
                        }
                    })
                    ->addColumn('amount', function($loyality){
                        return setting('default_currency').number_format($loyality->amount,'2','.','');
                    })
                    ->addColumn('total', function($loyality){
                        return number_format($loyality->amount,'2','.','');
                    })
                    ->addColumn('status', function($loyality) {
                        return $loyality->points.' | '.strtoupper($loyality->type);
                    })        
                    ->rawColumns(['date','transaction_no','status'])
                    ->make(true);

            if(isset($type)) {

                $datas = $table->getData()->data;
                switch ($type) {
                    case 'export':
                        //return Excel::download(new WastageExport($start_date,$end_date,$product_id), 'wastage_report.xlsx');
                    break;

                    case 'print':
                        $pdf = PDF::loadView('reports.export.party_reward_report', compact('datas','start_date','end_date','market'));
                        $filename = setting('app_name').'.pdf';
                        return $pdf->stream($filename);
                    break;

                    case 'download':
                        $pdf = PDF::loadView('reports.export.party_reward_report', compact('datas','start_date','end_date','market'));
                        $filename = setting('app_name').'.pdf';
                        return $pdf->download($filename);
                    break;                    
                }

            } else {
                return $table;
            }
        }


        if($report_type=='product-stock-report') 
        {
            $product_id          = $request->product_id;
            $start_date          = $request->start_date;
            $end_date            = $request->end_date;
            $type                = $request->type;
            
            $product             = $this->productRepository->findWithoutFail($product_id);
            $query               = $this->inventoryRepository->where('product_id',$product_id);
            if(isset($start_date) && isset($end_date) && $start_date!='' && $end_date!='') {
                 $query->whereDate('date','>=',Carbon::parse($start_date)->format('Y-m-d'))->whereDate('date','<=',Carbon::parse($end_date)->format('Y-m-d'));
            }
            $data                = $query->orderBy('id','asc')->get();

            $opening_stock[]     = $this->openingStockCalc($product_id,$start_date);

            foreach($data as $key => $value) {
                $data[$key]->closing_stock = $this->closingStockCalc(array_sum($opening_stock),$product_id,$value->unit,$value->quantity,$value->type);
                unset($opening_stock);
                $opening_stock[] = $data[$key]->closing_stock;
            }

            $dataTable = Datatables::of($data);
            $dataTable->addIndexColumn();

            $stock  = [];

            $table  = $dataTable
                    ->addColumn('date', function($inventory){
                        return $inventory->date->format('d M Y');
                    })
                    ->addColumn('category', function($inventory){
                        return strtoupper($inventory->category);
                    })
                    ->addColumn('quantity', function($inventory) use ($stock) {
                        if($inventory->type == 'add') {
                            return $inventory->quantity.' '.$inventory->uom->name;
                        } elseif($inventory->type == 'reduce') {
                            return ' - '.$inventory->quantity.' '.$inventory->uom->name;
                        }
                    })
                    ->addColumn('closing_stock', function($inventory){
                        return $inventory->closing_stock.' '.$inventory->product->primaryunit->name;
                    })
                    ->addColumn('description', function($inventory){
                        return $inventory->description;
                    })  
                    //->rawColumns(['date','transaction_no','status'])
                    ->make(true);

            if(isset($type)) {

                $datas = $table->getData()->data;
                switch ($type) {
                    case 'export':
                        //return Excel::download(new WastageExport($start_date,$end_date,$product_id), 'wastage_report.xlsx');
                    break;

                    case 'print':
                        $pdf = PDF::loadView('reports.export.product_stock_report', compact('datas','start_date','end_date','product'));
                        $filename = setting('app_name').'.pdf';
                        return $pdf->stream($filename);
                    break;

                    case 'download':
                        $pdf = PDF::loadView('reports.export.product_stock_report', compact('datas','start_date','end_date','product'));
                        $filename = setting('app_name').'.pdf';
                        return $pdf->download($filename);
                    break;                    
                }

            } else {
                return $table;
            }
        }


        if($report_type=='product-party-stock-report') 
        {
            $product_id          = $request->product_id;
            $start_date          = $request->start_date;
            $end_date            = $request->end_date;
            $type                = $request->type;
            
            $product             = $this->productRepository->findWithoutFail($product_id);
            $query               = $this->marketRepository->where('id','>',0);

            $data                = $query->orderBy('id','asc')->get();
            foreach($data as $key => $value) {
                $data[$key]->sales_quantity    = $this->partyPurchaseQuantity($product->id,$value->id,$start_date,$end_date,'reduce','sales','online');
                $data[$key]->purchase_quantity = $this->partyPurchaseQuantity($product->id,$value->id,$start_date,$end_date,'add','purchase','');
                $data[$key]->sales_amount      = $this->partyPurchaseAmount($product->id,$value->id,$start_date,$end_date,'sales');
                $data[$key]->purchase_amount   = $this->partyPurchaseAmount($product->id,$value->id,$start_date,$end_date,'purchase');
            }

            $dataTable = Datatables::of($data);
            $dataTable->addIndexColumn();

            $table  = $dataTable    
                        ->addColumn('name', function($market){
                            return $market->name;
                        })
                        ->addColumn('sales_quantity', function($market) use ($product) {
                            return number_format($market->sales_quantity,'3','.','').' '.$product->primaryunit->name;
                        })
                        ->addColumn('purchase_quantity', function($market) use ($product) {
                            return number_format($market->purchase_quantity,'3','.','').' '.$product->primaryunit->name;
                        })  
                    //->rawColumns(['date','transaction_no','status'])
                    ->make(true);

            if(isset($type)) {

                $datas = $table->getData()->data;
                switch ($type) {
                    case 'export':
                        //return Excel::download(new WastageExport($start_date,$end_date,$product_id), 'wastage_report.xlsx');
                    break;

                    case 'print':
                        $pdf = PDF::loadView('reports.export.product_party_stock_report', compact('datas','start_date','end_date','product'));
                        $filename = setting('app_name').'.pdf';
                        return $pdf->stream($filename);
                    break;

                    case 'download':
                        $pdf = PDF::loadView('reports.export.product_party_stock_report', compact('datas','start_date','end_date','product'));
                        $filename = setting('app_name').'.pdf';
                        return $pdf->download($filename);
                    break;                    
                }

            } else {
                return $table;
            }
        }


        if($report_type=='product-purchase-history-report') 
        {

            $product_id          = $request->product_id;
            $start_date          = $request->start_date;
            $end_date            = $request->end_date;
            $type                = $request->type;
            
            $product             = $this->productRepository->findWithoutFail($product_id);
            $query               = $this->inventoryRepository->where('product_id',$product_id);
            if(isset($start_date) && isset($end_date) && $start_date!='' && $end_date!='') {
                 $query->whereDate('date','>=',Carbon::parse($start_date)->format('Y-m-d'))->whereDate('date','<=',Carbon::parse($end_date)->format('Y-m-d'));
            }
            $data                = $query->orderBy('id','asc')->get();
            $datas = $data;

            $dataTable = Datatables::of($data);
            $dataTable->addIndexColumn();

            $table  = $dataTable    
                        ->addColumn('date', function($inventory){
                            return $inventory->date->format('d M Y');
                        })
                        ->addColumn('transaction_no', function($inventory){
                            if($inventory->category=='opening' || $inventory->category=='added') {
                                return strtoupper($inventory->category);
                            } elseif($inventory->category=='purchase') {
                                return '<a href="'.route('purchaseInvoice.show',$inventory->purchaseinvoiceitem->purchaseinvoice->id).'">'.$inventory->purchaseinvoiceitem->purchaseinvoice->code.'</a>';
                            } elseif($inventory->category=='purchase_return') {
                                return '<a href="'.route('purchaseReturn.show',$inventory->purchasereturnitem->purchasereturn->id).'">'.$inventory->purchasereturnitem->purchasereturn->code.'</a>';
                            } elseif($inventory->category=='sales') {
                                return '<a href="'.route('salesInvoice.show',$inventory->salesinvoiceitem->salesinvoice->id).'">'.$inventory->salesinvoiceitem->salesinvoice->code.'</a>';
                            } elseif($inventory->category=='sales_return') {
                                return '<a href="'.route('salesReturn.show',$inventory->salesreturnitem->salesreturn->id).'">'.$inventory->salesreturnitem->salesreturn->code.'</a>';
                            }
                        })
                        ->addColumn('amount', function($inventory){
                            if($inventory->category=='opening' || $inventory->category=='added') {
                                return '';
                            } elseif($inventory->category=='purchase') {
                                return number_format($inventory->purchaseinvoiceitem->amount,'2','.','');
                            } elseif($inventory->category=='purchase_return') {
                                return number_format($inventory->purchasereturnitem->amount,'2','.','');
                            } elseif($inventory->category=='sales') {
                                return number_format($inventory->salesinvoiceitem->amount,'2','.','');
                            } elseif($inventory->category=='sales_return') {
                                return number_format($inventory->salesreturnitem->amount,'2','.','');
                            }
                        }) 
                        ->addColumn('quantity', function($inventory){
                            return number_format($inventory->quantity,3,'.','').' '.$inventory->uom->name;
                        }) 
                        ->rawColumns(['date','transaction_no'])
                    ->make(true);

            if(isset($type)) {

                //$datas = $table->getData()->data;
                switch ($type) {
                    case 'export':
                        //return Excel::download(new WastageExport($start_date,$end_date,$product_id), 'wastage_report.xlsx');
                    break;

                    case 'print':
                        $pdf = PDF::loadView('reports.export.product_purchase_history_report', compact('datas','start_date','end_date','product'));
                        $filename = setting('app_name').'.pdf';
                        return $pdf->stream($filename);
                    break;

                    case 'download':
                        $pdf = PDF::loadView('reports.export.product_purchase_history_report', compact('datas','start_date','end_date','product'));
                        $filename = setting('app_name').'.pdf';
                        return $pdf->download($filename);
                    break;                    
                }

            } else {
                return $table;
            }
        }

        if($report_type=='sales-summary') : 

            $start_date          = "";
            $end_date            = "";

            if($request->ajax()) {
                $start_date          = $request->start_date;
                $end_date            = $request->end_date;
            }
            // $sales_data    = DB::table('sales_invoice')
            //                 ->leftJoin('sales_invoice_items', 'sales_invoice.id','=','sales_invoice_items.sales_invoice_id')
            //                 ->groupBy('sales_invoice_items.sales_invoice_id')
            //                 ->orderBy('sales_invoice.id','asc')
            //                 ->get();
            
            $data    = DB::table('sales_invoice')
                            ->join('markets', 'sales_invoice.market_id','=','markets.id')
                            ->leftJoin('customer_groups', 'markets.customer_group_id','=','customer_groups.id')
                            //->leftJoin('user_markets', 'markets.id','=','user_markets.market_id')
                            ->leftJoin('users', 'markets.user_id','=','users.id')
                            ->leftJoin('customer_levels', 'users.level','=','customer_levels.id')
                            ->leftJoin('sales_invoice_items', 'sales_invoice.id','=','sales_invoice_items.sales_invoice_id')
                            ->leftJoin('payment_mode', 'sales_invoice.payment_method','=','payment_mode.id')
                            ->select('sales_invoice.*','markets.name as market_id','customer_groups.name as party_group','customer_levels.name as reward_level',DB::raw('COUNT(sales_invoice_items.sales_invoice_id) as no_of_items'),'payment_mode.name as payment_type')
                             ->groupBy('sales_invoice_items.sales_invoice_id');

            if($start_date!='' & $end_date!='') {
                $data->whereBetween('sales_invoice.date', [$start_date, $end_date]);
            }                    
            $sales_data = $data->get();
            
            foreach ($sales_data as $key => $value) {
                $sales_data[$key]->date  = date('d M Y',strtotime($value->date));
                  if($value->cash_paid >= $value->total) {
                         $sales_data[$key]->payment_status  = 'Paid';
                    } elseif($value->cash_paid > 0) {
                         $sales_data[$key]->payment_status  = 'Partially Paid';
                    } else {
                         $sales_data[$key]->payment_status  = 'Unpaid';
                    }
                $sales_data[$key]->total = setting('default_currency').''.number_format($sales_data[$key]->total,2);
            }

            
            if($request->ajax()) {

                $dataTable = Datatables::of($sales_data);
                $dataTable->addIndexColumn();

                $table  = $dataTable    
                            ->addColumn('date', function() {
                                return $sales_data->date;
                            })
                            ->addColumn('code', function() {
                                return $sales_data->code;
                            })
                            ->addColumn('market_id', function() {
                                return $sales_data->market_id;
                            })
                            ->addColumn('party_group', function(){
                                return $sales_data->party_group;
                            })
                            ->addColumn('reward_level', function() {
                                return $sales_data->reward_level;
                            })
                            ->addColumn('no_of_items', function() {
                                return $sales_data->no_of_items;
                            }) 
                            ->addColumn('total', function(){
                                return $sales_data->total;
                            })
                            ->addColumn('payment_type', function() {
                                return $sales_data->payment_type;
                            }) 
                            ->addColumn('payment_status', function(){
                                return $sales_data->payment_status;
                            }) 
                        ->make(true);

                return Datatables::of($sales_data)->make(true);
                
             } else {
                 return view('reports.sales_summary_report')->with('report_type',$report_type)->with('sales_data',$sales_data);
             }
            
        endif;

        if($report_type=='party-statement') : 
            
            $markets = $this->marketRepository->pluck('name','id');
            $markets->prepend("Select Party",0);
            
            $market_id           = $request->market_id;
            $start_date          = $request->start_date;
            $end_date            = $request->end_date;
            $type                = $request->type;
            
            $market              = $this->marketRepository->findWithoutFail($market_id);
            $query               = $this->transactionRepository->where('id','>',0);
            if(isset($market_id) && $market_id!='') {
                $query->where('market_id',$market_id);
            }
            if(isset($start_date) && isset($end_date) && $start_date!='' && $end_date!='') {
                $query->whereBetween('date', [Carbon::parse($start_date)->format('Y-m-d'), Carbon::parse($end_date)->format('Y-m-d')]);
            }
            $data               = $query->get();
            $opening_balance[]  = $this->openingBalanceCalc($market_id,$start_date);
            
            foreach($data as $key => $value) {
                $data[$key]->closing_balance = $this->closingBalanceCalc(array_sum($opening_balance),$market_id,$value->type,$value->amount);
                unset($opening_balance);
                $opening_balance[] = $data[$key]->closing_balance;
            }

            //dd($data);

            if($request->ajax()) {

            $dataTable = Datatables::of($data);
            $dataTable->addIndexColumn();

            $table  = $dataTable    
                        ->addColumn('date', function($transaction) {
                            return $transaction->date->format('d M Y');
                        })
                        ->addColumn('category', function($transaction) {
                            return ucfirst($transaction->category);
                        })
                        ->addColumn('transaction_no', function($transaction) {
                            if($transaction->category=='sales') {
                                return $transaction->salesinvoice->code;
                            } elseif($transaction->category=='sales_return') {
                                return $transaction->salesreturn->code;
                            } elseif($transaction->category=='purchase') {
                                return $transaction->purchaseinvoice->code;
                            } elseif($transaction->category=='purchase_return') {
                                return $transaction->purchasereturn->code;
                            } elseif($transaction->category=='payment_in') {
                                return $transaction->paymentin->code;
                            } elseif($transaction->category=='payment_out') {
                                return $transaction->paymentout->code;
                            }
                        })
                        ->addColumn('credit', function($transaction) {
                            return ($transaction->type=='credit') ? setting('default_currency').number_format($transaction->amount,'2','.','') : '' ;
                        })
                        ->addColumn('debit', function($transaction) {
                            return ($transaction->type=='debit') ? setting('default_currency').number_format($transaction->amount,'2','.','') : '' ;
                        }) 
                        ->addColumn('closing_balance', function($transaction){
                            return setting('default_currency').number_format($transaction->closing_balance,2,'.','');
                        })
                        ->addColumn('status', function($transaction) {
                            if($transaction->category=='sales' || $transaction->category=='sales_return' || $transaction->category=='purchase' || $transaction->category=='purchase_return') :

                            if($transaction->category=='sales') {
                                $paid  = $transaction->salesinvoice->cash_paid + $transaction->salesinvoice->totalsettle('sales','sales_invoice_id',$transaction->salesinvoice->id);
                                $due   = $transaction->salesinvoice->amount_due;
                                $total = $transaction->salesinvoice->total;
                            }
                            
                            if($transaction->category=='sales_return') {
                                $paid  = $transaction->salesreturn->cash_paid + $transaction->salesreturn->totalsettle('sales','sales_return_id',$transaction->salesreturn->id);
                                $due   = $transaction->salesreturn->amount_due;
                                $total = $transaction->salesreturn->total;
                            }

                            if($transaction->category=='purchase') {
                                $paid  = $transaction->purchaseinvoice->cash_paid + $transaction->purchaseinvoice->totalsettle('sales','purchase_invoice_id',$transaction->purchaseinvoice->id);
                                $due   = $transaction->purchaseinvoice->amount_due;
                                $total = $transaction->purchaseinvoice->total;
                            }

                            if($transaction->category=='purchase_return') {
                                $paid  = $transaction->purchasereturn->cash_paid + $transaction->purchasereturn->totalsettle('sales','purchase_return_id',$transaction->purchasereturn->id);
                                $due   = $transaction->purchasereturn->amount_due;
                                $total = $transaction->purchasereturn->total;    
                            }

                            if($due > 0 && $due < $total) {
                                return 'Partially Paid ('.setting('default_currency').' '.$paid.')';
                            } elseif($due == 0) {
                                return 'Paid';
                            } elseif($due == $total) {
                                return 'Unpaid';
                            }
                        endif;
                        })
                        // ->addColumn('balance', function($transaction){
                        //     return number_format($total,'2','.','');
                        // })
                    ->make(true);

                    return $table;
                } else {
                    return view('reports.party_statement_report')->with('report_type',$report_type)->with('markets',$markets)->with('trans_data',$data);
                }

                    

            /*
            if($request->ajax()) {

                $dataTable = Datatables::of($data);
                $dataTable->addIndexColumn();

                $table  = $dataTable    
                            ->addColumn('date', function($transaction) {
                                return $transaction->date->format('d M Y');
                            })
                            ->addColumn('category', function($transaction) {
                                return ucfirst($transaction->category);
                            })
                            ->addColumn('transaction_no', function($transaction) {
                                if($transaction->category=='sales') {
                                    return $transaction->salesinvoice->code;
                                } elseif($transaction->category=='sales_return') {
                                    return $transaction->salesreturn->code;
                                } elseif($transaction->category=='purchase') {
                                    return $transaction->purchaseinvoice->code;
                                } elseif($transaction->category=='purchase_return') {
                                    return $transaction->purchasereturn->code;
                                } elseif($transaction->category=='payment_in') {
                                    return $transaction->paymentin->code;
                                } elseif($transaction->category=='payment_out') {
                                    return $transaction->paymentout->code;
                                }
                            })
                            // ->addColumn('total', function($transaction){
                            //     return number_format($transaction->amount,'2','.','');
                            // })
                            ->addColumn('credit', function($transaction) {
                                return ($transaction->type=='credit') ? setting('default_currency').number_format($transaction->amount,'2','.','') : '' ;
                            })
                            ->addColumn('debit', function($transaction) {
                                return ($transaction->type=='debit') ? setting('default_currency').number_format($transaction->amount,'2','.','') : '' ;
                            }) 
                            ->addColumn('closing_balance', function($transaction){
                                return setting('default_currency').number_format($transaction->closing_balance,2,'.','');
                            })
                            ->addColumn('status', function($transaction) {
                                if($transaction->category=='sales' || $transaction->category=='sales_return' || $transaction->category=='purchase' || $transaction->category=='purchase_return') :

                                if($transaction->category=='sales') {
                                    $paid  = $transaction->salesinvoice->cash_paid + $transaction->salesinvoice->totalsettle('sales','sales_invoice_id',$transaction->salesinvoice->id);
                                    $due   = $transaction->salesinvoice->amount_due;
                                    $total = $transaction->salesinvoice->total;
                                }
                                
                                if($transaction->category=='sales_return') {
                                    $paid  = $transaction->salesreturn->cash_paid + $transaction->salesreturn->totalsettle('sales','sales_return_id',$transaction->salesreturn->id);
                                    $due   = $transaction->salesreturn->amount_due;
                                    $total = $transaction->salesreturn->total;
                                }

                                if($transaction->category=='purchase') {
                                    $paid  = $transaction->purchaseinvoice->cash_paid + $transaction->purchaseinvoice->totalsettle('sales','purchase_invoice_id',$transaction->purchaseinvoice->id);
                                    $due   = $transaction->purchaseinvoice->amount_due;
                                    $total = $transaction->purchaseinvoice->total;
                                }

                                if($transaction->category=='purchase_return') {
                                    $paid  = $transaction->purchasereturn->cash_paid + $transaction->purchasereturn->totalsettle('sales','purchase_return_id',$transaction->purchasereturn->id);
                                    $due   = $transaction->purchasereturn->amount_due;
                                    $total = $transaction->purchasereturn->total;    
                                }

                                if($due > 0 && $due < $total) {
                                    return '<span class="btn btn-sm btn-warning">Partially Paid ('.setting('default_currency').' '.$paid.') </span>';
                                } elseif($due == 0) {
                                    return '<span class="btn btn-sm btn-success">Paid</span>';
                                } elseif($due == $total) {
                                    return '<span class="btn btn-sm btn-danger">Unpaid</span>';
                                }
                            endif;
                            })
                        ->make(true);

                //return Datatables::of($data)->make(true);
                
             } else {
                 return view('reports.party_statement_report')->with('report_type',$report_type)->with('markets',$markets)->with('trans_data',$data);
             } */
        
            //return view('reports.party_statement_report')->with('report_type',$report_type)->with('markets',$markets)->with('trans_data',$data);
        endif;

        if($report_type=='daybook') : 

            $start_date          = $request->start_date;
            $end_date            = $request->end_date;
            $type                = $request->type;
            
            $query = TransactionTrack::where('id','>',0);
            if(isset($start_date) && isset($end_date) && $start_date!='' && $end_date!='') {
                 $query->whereDate('date','>=',Carbon::parse($start_date)->format('Y-m-d'))->whereDate('date','<=',Carbon::parse($end_date)->format('Y-m-d'));
            }
            $datas = $query->get();

            $dataTable  = Datatables::of($datas);
            $dataTable->addIndexColumn();

            $table      = $dataTable    
                        ->addColumn('date', function($transaction) {
                            return $transaction->date->format('M d, Y');
                        })
                        ->addColumn('market_id', function($transaction) {
                            return $transaction->market->name;
                        })
                        ->addColumn('category', function($transaction) {
                            return ucfirst($transaction->category);
                        })
                        ->addColumn('transaction_no', function($transaction){
                            if($transaction->category=='sales') {
                                return $transaction->salesinvoice->code;
                            } elseif($transaction->category=='sales_return') {
                                return $transaction->salesreturn->code;
                            } elseif($transaction->category=='purchase') {
                                return $transaction->purchaseinvoice->code;
                            } elseif($transaction->category=='purchase_return') {
                                return $transaction->purchasereturn->code;
                            } elseif($transaction->category=='payment_in') {
                                return $transaction->paymentin->code;
                            } elseif($transaction->category=='payment_out') {
                                return $transaction->paymentout->code;
                            }
                        })
                        ->addColumn('credit', function($transaction) {
                            return ($transaction->type=='credit') ? setting('default_currency').number_format($transaction->amount,'2','.','') : '' ;
                        })
                        ->addColumn('debit', function($transaction) {
                            return ($transaction->type=='debit') ? setting('default_currency').number_format($transaction->amount,'2','.','') : '' ;
                        })
                        ->make(true);

            if($request->ajax()) {

                return $table;
                
            } else {

                if(isset($type)) {

                    $datas = $table->getData()->data;
                    switch ($type) {
                        case 'export':
                            //return Excel::download(new WastageExport($start_date,$end_date,$product_id), 'wastage_report.xlsx');
                        break;

                        case 'print':
                            $pdf = PDF::loadView('reports.export.daybook_report', compact('datas','start_date','end_date'));
                            $filename = setting('app_name').'.pdf';
                            return $pdf->stream($filename);
                        break;

                        case 'download':
                            $pdf = PDF::loadView('reports.export.daybook_report', compact('datas','start_date','end_date'));
                            $filename = setting('app_name').'.pdf';
                            return $pdf->download($filename);
                        break;                    
                    }

                } else {

                    return view('reports.daybook')->with('report_type',$report_type)->with('datas',$datas);

                }

            }
            
        endif;
      

        //Item Reports

        if($report_type=='stock-summary-report') :


            $start_date     = $request->start_date;
            $end_date       = $request->end_date;
            $type           = $request->type;
            
            $query          = $this->productRepository->where('id','>',0);
            if(isset($start_date) && isset($end_date) && $start_date!='' && $end_date!='') {
                 $query->whereDate('created_at','>=',Carbon::parse($start_date)->format('Y-m-d'))->whereDate('created_at','<=',Carbon::parse($end_date)->format('Y-m-d'));
            }
            $datas = $query->get();

            $dataTable  = Datatables::of($datas);
            $dataTable->addIndexColumn();

            $table      = $dataTable    
                        ->addColumn('date', function($product) {
                            return $product->created_at->format('M d, Y');
                        })
                        ->addColumn('name', function($product) {
                            return $product->name;
                        })
                        ->addColumn('product_code', function($product) {
                            return $product->product_code;
                        })
                        ->addColumn('purchase_price', function($product) {
                            return number_format($product->purchase_price,2,'.','');
                        })
                        ->addColumn('price', function($product) {
                            return number_format($product->price,2,'.','');
                        })
                        ->addColumn('stock', function($product) {
                            return number_format($product->stock,3,'.','').' '.$product->primaryunit->name;
                        })
                        ->addColumn('stock_value', function($product) {
                            return number_format(($product->stock * $product->purchase_price),3,'.','');
                        })
                        ->addColumn('profit', function($product) {
                            
                            $profit = $product->price - $product->purchase_price;
                            if($product->purchase_price > 0){
                                $profit_percentage = ($profit * 100) / $product->purchase_price;
                            } else { 
                                $profit_percentage = 100; 
                            }
                            if($profit_percentage > 0) { 
                                $profit_percent = $profit_percentage; 
                            } else { 
                                $profit_percent = 0; 
                            }
                            return number_format($profit_percent,2,'.','').'%';

                        })
                        ->make(true);

            if($request->ajax()) {

                return $table;
                
            } else {

                if(isset($type)) {

                    $datas = $table->getData()->data;
                    switch ($type) {
                        case 'export':
                            //return Excel::download(new WastageExport($start_date,$end_date,$product_id), 'wastage_report.xlsx');
                        break;

                        case 'print':
                            $pdf = PDF::loadView('reports.export.stock_summary_report', compact('datas','start_date','end_date'));
                            $filename = setting('app_name').'.pdf';
                            return $pdf->stream($filename);
                        break;

                        case 'download':
                            $pdf = PDF::loadView('reports.export.stock_summary_report', compact('datas','start_date','end_date'));
                            $filename = setting('app_name').'.pdf';
                            return $pdf->download($filename);
                        break;                    
                    }

                } else {

                    return view('reports.stock_summary')->with('report_type',$report_type)->with('datas',$datas);

                }

            }

        endif;

        if($report_type=='rate-list') :

            $start_date          = "";
            $end_date            = "";

            if($request->ajax()) {
                $start_date          = $request->start_date;
                $end_date            = $request->end_date;
            }

            $customer_groups = DB::table('customer_groups')->get();   
            
            $datas = $this->productRepository->get();
            
            foreach ($datas as $key => $value) {

                foreach($customer_groups as $customer_group) {
                  $price_variation = DB::table('product_group_price')->where('customer_group_id',$customer_group->id)->where('product_id',$datas[$key]->id)->get();
                  if(count($price_variation) > 0) {
                    $datas[$key]->{strtolower($customer_group->name)} = number_format($price_variation[0]->product_price,2, '.', '');  
                  } else {
                    $datas[$key]->{strtolower($customer_group->name)} = "";
                  }
                }
                $datas[$key]->purchasing_price = number_format($datas[$key]->purchase_price,2, '.', '');
                $datas[$key]->selling_price    = number_format($datas[$key]->price,2, '.', '');
            }
            
            
            return view('reports.rate_list')->with('report_type',$report_type)->with('customer_groups',$customer_groups)->with('datas',$datas);

            /* if($request->ajax()) {

                $dataTable = Datatables::of($datas);
                $dataTable->addIndexColumn();

                $table  = $dataTable    
                            ->addColumn('name', function() {
                                return $datas->name;
                            })
                            ->addColumn('product_code', function() {
                                return $datas->product_code;
                            })
                            ->addColumn('purchasing_price', function() {
                                return $datas->purchasing_price;
                            })
                        // foreach($customer_groups as $customer_group){
                                    // if($datas->strtolower($customer_group->name) !='' ) {
                                    //     ->addColumn('purchasing_price', function(){
                                    //         return $datas->strtolower($customer_group->name);
                                    //     })
                                    // }
                        // }
                            ->addColumn('purchasing_price', function(){
                                return $datas->purchasing_price;
                            })
                            ->addColumn('selling_price', function() {
                                return $datas->selling_price;
                            })
                        ->make(true);

                return Datatables::of($datas)->make(true);
                
             } else {
                 return view('reports.rate_list')->with('report_type',$report_type)->with('customer_groups',$customer_groups)->with('datas',$datas);
             } */

        endif;

        if($report_type=='item-sales-summary') :

            

            $start_date     = $request->start_date;
            $end_date       = $request->end_date;
            $type           = $request->type;
            
            $query          = $this->productRepository->where('id','>',0);
            /*if(isset($start_date) && isset($end_date) && $start_date!='' && $end_date!='') {
                 $query->whereDate('created_at','>=',Carbon::parse($start_date)->format('Y-m-d'))->whereDate('created_at','<=',Carbon::parse($end_date)->format('Y-m-d'));
            }*/
            $datas = $query->get();

            $dataTable  = Datatables::of($datas);
            $dataTable->addIndexColumn();

            $table      = $dataTable
                        ->addColumn('name', function($product) {
                            return $product->name;
                        })
                        ->addColumn('total_sales', function($product) use($start_date,$end_date) {
                            $query =  $this->inventoryRepository->where('type','reduce')->where('category','sales');
                            if(isset($start_date) && isset($end_date) && $start_date!='' && $end_date!='') {
                                 $query->whereDate('created_at','>=',Carbon::parse($start_date)->format('Y-m-d'))
                                       ->whereDate('created_at','<=',Carbon::parse($end_date)->format('Y-m-d'));
                            }
                            $sales_list = $query->get();
                            
                        })
                        ->addColumn('total_sales_amount', function($product) {
                            return number_format($product->purchase_price,2,'.','');
                        })
                        ->make(true);

            if($request->ajax()) {

                return $table;
                
            } else {

                if(isset($type)) {

                    $datas = $table->getData()->data;
                    switch ($type) {
                        case 'export':
                            //return Excel::download(new WastageExport($start_date,$end_date,$product_id), 'wastage_report.xlsx');
                        break;

                        case 'print':
                            $pdf = PDF::loadView('reports.export.item_sales_summary_report', compact('datas','start_date','end_date'));
                            $filename = setting('app_name').'.pdf';
                            return $pdf->stream($filename);
                        break;

                        case 'download':
                            $pdf = PDF::loadView('reports.export.item_sales_summary_report', compact('datas','start_date','end_date'));
                            $filename = setting('app_name').'.pdf';
                            return $pdf->download($filename);
                        break;                    
                    }

                } else {

                    return view('reports.item_sales_summary')->with('report_type',$report_type)->with('datas',$datas);

                }

            }



            /*$start_date          = $request->start_date;
            $end_date            = $request->end_date;
            
            $data = DB::table('sales_invoice_items')
                      ->select('product_id','product_name','unit')
                      ->distinct();

            if($start_date!='' & $end_date!='') {
                $data->whereBetween('sales_invoice_items.created_at', [$start_date, $end_date]);
            }   

            $datas = $data->get();

            foreach ($datas as $key => $value) {
 
              $product            = $this->productRepository->findWithoutFail($datas[$key]->product_id);

              $units              = DB::table('uom')->where('id',$datas[$key]->unit)->first();
              $datas[$key]->name  = $product->name;
              $datas[$key]->unit  = $units->name;
              $datas[$key]->total_sales = number_format(DB::table('sales_invoice_items')->where('product_id',$datas[$key]->product_id)->sum('quantity'),3, '.', '')." ".$units->name;
              $datas[$key]->total_sales_amount = setting('default_currency').number_format(DB::table('sales_invoice_items')->where('product_id',$datas[$key]->product_id)->sum('amount'),2, '.', '');;
            }

            if($request->ajax()) {

            $dataTable = Datatables::of($datas);
            $dataTable->addIndexColumn();

            $table  = $dataTable    
                        ->addColumn('name', function() {
                            return $datas->name;
                        })
                        ->addColumn('quantity', function() {
                            return $datas->total_sales;
                        })
                        ->addColumn('total_sales', function() {
                            return $datas->total_sales_amount;
                        })
                    ->make(true);

            return Datatables::of($datas)->make(true);
            
            } else {
                return view('reports.item_sales_summary')->with('report_type',$report_type)->with('item_sales',$datas);
            }*/


        endif;

        if($report_type=='low-stock-summary') :

            $start_date     = $request->start_date;
            $end_date       = $request->end_date;

            $data = DB::table('products')->whereRaw('low_stock_unit >= stock');

            if($start_date!='' & $end_date!='') {
                $data->whereBetween('products.created_at', [$start_date, $end_date]);
            }   

            $datas = $data->get();

            foreach ($datas as $key => $value) {
              $units    = DB::table('uom')->where('id',$value->unit)->first();
              if($datas[$key]->low_stock_unit >= $value->stock) {
                $datas[$key]->stock_quantity  = number_format($value->stock,3, '.', '').' '.$units->name;
                $datas[$key]->low_stock_level = number_format($value->low_stock_unit,3, '.', '').' '.$units->name;
                $datas[$key]->stock_value     = setting('default_currency').number_format($value->stock * $value->purchase_price,2,'.','');
              } else {
                unset($datas[$key]);
              } 

            }
            
            if($request->ajax()) {

                $dataTable = Datatables::of($datas);
                $dataTable->addIndexColumn();

                $table  = $dataTable    
                            ->addColumn('name', function() {
                                return $datas->name;
                            })
                            ->addColumn('product_code', function() {
                                return $datas->product_code;
                            })
                            ->addColumn('stock_quantity', function() {
                                return $datas->stock_quantity;
                            })
                            ->addColumn('low_stock_level', function(){
                                return $datas->low_stock_level;
                            })
                            ->addColumn('stock_value', function() {
                                return $datas->stock_value;
                            })
                        ->make(true);

                return Datatables::of($datas)->make(true);
                
             } else {
                 return view('reports.low_stock_summary')->with('report_type',$report_type)->with('low_stocks',$datas);
             }

        endif;

        if($report_type=='stock-detail-report') :
            $products = $this->productRepository->pluck('name','id');
            $products->prepend("Select Item",0); 
            return view('reports.stock_detail_report')->with('report_type',$report_type)->with('products',$products);
        endif;

        if($report_type=='item-report-by-party') :

            $markets = $this->marketRepository->pluck('name','id');
            $markets->prepend("Select Party",0); 
            
            $datas = $this->productRepository->get();

            $market          = "";
            $start_date      = "";
            $end_date        = "";

            if($request->ajax()) {
                $market          = $request->market;
                $start_date      = $request->start_date;
                $end_date        = $request->end_date;
            }


            foreach ($datas as $key => $value) {

                $sales_quantity = DB::table('sales_invoice')
                                  ->join('sales_invoice_items','sales_invoice.id','=','sales_invoice_items.sales_invoice_id')
                                  ->where('sales_invoice_items.product_id',$value->id)
                                  ->sum('quantity');

                               // if($market!='' && $market!=0) {
                                //     $sales_quantity->where('sales_invoice.market_id',$market);
                                // }
                                // if($start_date!='' && $end_date!='') {
                                //     $sales_quantity->whereBetween('sales_invoice.date', [$start_date, $end_date]);
                                // }

                $sales_invoice   = DB::table('sales_invoice')
                                  ->join('sales_invoice_items','sales_invoice.id','=','sales_invoice_items.sales_invoice_id')
                                  ->where('sales_invoice_items.product_id',$value->id)
                                  ->get();

                                  // if($market!='' && $market!=0) {
                                    //     $sales_invoice->where('sales_invoice.market_id',$market);
                                    // }
                                    // if($start_date!='' && $end_date!='') {
                                    //     $sales_invoice->whereBetween('sales_invoice.date', [$start_date, $end_date]);
                                    // }

                $sales_invoice_amount[] = 0;

                //if(is_countable($sales_invoice)?$sales_invoice:""){
                    if(count($sales_invoice) > 0) {
                      foreach ($sales_invoice as $key1 => $value1) {
                        $sales_invoice_amount[]= $value1->quantity * $value1->unit_price;
                      }
                    }
                //}                                 

                $purchase_quantity = DB::table('purchase_invoice')
                                    ->join('purchase_invoice_items','purchase_invoice.id','=','purchase_invoice_items.purchase_invoice_id')
                                    ->where('purchase_invoice_items.product_id',$value->id)
                                    ->sum('quantity');

                                    // if($market!='' && $market!=0) {
                                    //     $purchase_quantity->where('purchase_invoice.market_id',$market);
                                    // }
                                    // if($start_date!='' && $end_date!='') {
                                    //     $purchase_quantity->whereBetween('purchase_invoice.date', [$start_date, $end_date]);
                                    // }

                $purchase_invoice   = DB::table('purchase_invoice')
                                    ->join('purchase_invoice_items','purchase_invoice.id','=','purchase_invoice_items.purchase_invoice_id')
                                    ->where('purchase_invoice_items.product_id',$value->id)
                                    ->get();

                                    // if($market!='' && $market!=0) {
                                    //     $purchase_invoice->where('purchase_invoice.market_id',$market);
                                    // }
                                    // if($start_date!='' && $end_date!='') {
                                    //     $purchase_invoice->whereBetween('purchase_invoice.date', [$start_date, $end_date]);
                                    // } 
                                  
                $purchase_invoice_amount[] = 0;
                //if(is_countable($purchase_invoice)?$purchase_invoice:""){                                 
                    if(count($purchase_invoice) > 0) {
                      foreach ($purchase_invoice as $key1 => $value1) {
                        $purchase_invoice_amount[]= $value1->quantity * $value1->unit_price;
                      }
                    }
                //}                  

                $units    = DB::table('uom')->where('id',$value->unit)->first();

                $datas[$key]->unit_name         = $units->name;
                $datas[$key]->sales_quantity    = number_format($sales_quantity,3,'.',''). " " .$datas[$key]->unit_name;
                $datas[$key]->sales_amount      = setting('default_currency').number_format(array_sum($sales_invoice_amount),2,'.','');
                $datas[$key]->purchase_quantity = number_format($purchase_quantity,3,'.','')." ".$datas[$key]->unit_name;
                $datas[$key]->purchase_amount   = setting('default_currency').number_format(array_sum($purchase_invoice_amount),2,'.','');
                unset($sales_invoice_amount);
                unset($purchase_invoice_amount);
            }

            if($request->ajax()) {

                $dataTable = Datatables::of($datas);
                $dataTable->addIndexColumn();

                $table  = $dataTable    
                            ->addColumn('name', function() {
                                return $datas->name;
                            })
                            ->addColumn('product_code', function() {
                                return $datas->product_code;
                            })
                            ->addColumn('sales_quantity', function() {
                                return $datas->sales_quantity;
                            })
                            ->addColumn('sales_amount', function(){
                                return $datas->sales_amount;
                            })
                            ->addColumn('purchase_quantity', function() {
                                return $datas->purchase_quantity;
                            })
                            ->addColumn('purchase_amount', function() {
                                return $datas->purchase_amount;
                            })  
                        ->make(true);

                return Datatables::of($datas)->make(true);
                
            } else {
                 return view('reports.item_report_by_party')->with('report_type',$report_type)->with('markets',$markets)->with('datas',$datas);
            }

        endif;

        //Item Reports

        /*Transaction*/
        
        if($report_type=='bill-wise-profit') : 


            $start_date     = $request->start_date;
            $end_date       = $request->end_date;
            $type           = $request->type;
            
            $query          = $this->transactionRepository->where('category','sales')->orWhere('category','online');
            if(isset($start_date) && isset($end_date) && $start_date!='' && $end_date!='') {
                 $query->whereDate('date','>=',Carbon::parse($start_date)->format('Y-m-d'))->whereDate('date','<=',Carbon::parse($end_date)->format('Y-m-d'));
            }
            $datas          = $query->get();

            $dataTable      = Datatables::of($datas);
            $dataTable->addIndexColumn();

            $table          = $dataTable    
                            ->addColumn('date', function($transaction) {
                                return $transaction->date->format('M d, Y');
                            })
                            ->addColumn('transaction_no', function($transaction){
                                if($transaction->category=='sales') {
                                    return $transaction->salesinvoice->code;
                                } elseif($transaction->category=='online') {
                                    return $transaction->order->order_code;
                                }
                            })
                            ->addColumn('market_id', function($transaction) {
                                return $transaction->market->name;
                            })
                            ->addColumn('amount', function($transaction) {
                                return number_format($transaction->amount,2,'.','');
                            })
                            ->addColumn('sales_amount', function($transaction) {
                                if($transaction->category=='sales') {
                                    $total = $transaction->salesinvoice->items->sum('amount') - $transaction->salesinvoice->items->sum('tax_amount');
                                    return number_format($total,2,'.','');
                                } elseif($transaction->category=='online') {
                                    $total = $transaction->order->productOrders->sum('amount') - $transaction->order->productOrders->sum('tax_amount');
                                    return number_format($total,2,'.','');
                                }
                            })
                            ->addColumn('purchase_amount', function($transaction) {
                                if($transaction->category=='sales') {
                                    
                                    $sales_purchase[]     = 0;
                                    foreach($transaction->salesinvoice->items as $item) {
                                        $units = $item->product->allunits($item->product_id);
                                        foreach($units as $unit) {
                                            if($item->unit == $unit['id']) {
                                                $sales_purchase[] = $item->quantity * ($item->product->purchase_price * $unit['quantity']);
                                            }
                                        }
                                    }
                                    return number_format(array_sum($sales_purchase),2,'.','');

                                } elseif($transaction->category=='online') {

                                    $online_purchase[]     = 0;
                                    foreach($transaction->order->productOrders as $item) {
                                        $units = $item->product->allunits($item->product_id);
                                        foreach($units as $unit) {
                                            if($item->unit == $unit['id']) {
                                                $online_purchase[] = $item->quantity * ($item->product->purchase_price * $unit['quantity']);
                                            }
                                        }
                                    }
                                    return number_format(array_sum($online_purchase),2,'.','');

                                }
                            })
                            ->addColumn('profit', function($transaction) {
                                
                                if($transaction->category=='sales') {
                                    $total = $transaction->salesinvoice->items->sum('amount') - $transaction->salesinvoice->items->sum('tax_amount');
                                    $sales = number_format($total,2,'.','');
                                } elseif($transaction->category=='online') {
                                    $total = $transaction->order->productOrders->sum('amount') - $transaction->order->productOrders->sum('tax_amount');
                                    $sales = number_format($total,2,'.','');
                                }


                                if($transaction->category=='sales') {
                                    
                                    $sales_purchase[]     = 0;
                                    foreach($transaction->salesinvoice->items as $item) {
                                        $units = $item->product->allunits($item->product_id);
                                        foreach($units as $unit) {
                                            if($item->unit == $unit['id']) {
                                                $sales_purchase[] = $item->quantity * ($item->product->purchase_price * $unit['quantity']);
                                            }
                                        }
                                    }
                                    $purchase = number_format(array_sum($sales_purchase),2,'.','');

                                } elseif($transaction->category=='online') {

                                    $online_purchase[]     = 0;
                                    foreach($transaction->order->productOrders as $item) {
                                        $units = $item->product->allunits($item->product_id);
                                        foreach($units as $unit) {
                                            if($item->unit == $unit['id']) {
                                                $online_purchase[] = $item->quantity * ($item->product->purchase_price * $unit['quantity']);
                                            }
                                        }
                                    }
                                    $purchase = number_format(array_sum($online_purchase),2,'.','');

                                }

                                return number_format(($sales - $purchase),2,'.','');


                            })
                            ->make(true);

            if($request->ajax()) {

                return $table;
                
            } else {

                if(isset($type)) {

                    $datas = $table->getData()->data;
                    switch ($type) {
                        case 'export':
                            //return Excel::download(new WastageExport($start_date,$end_date,$product_id), 'wastage_report.xlsx');
                        break;

                        case 'print':
                            $pdf = PDF::loadView('reports.export.bill_wise_profit_report', compact('datas','start_date','end_date'));
                            $filename = setting('app_name').'.pdf';
                            return $pdf->stream($filename);
                        break;

                        case 'download':
                            $pdf = PDF::loadView('reports.export.bill_wise_profit_report', compact('datas','start_date','end_date'));
                            $filename = setting('app_name').'.pdf';
                            return $pdf->download($filename);
                        break;                    
                    }

                } else {

                    return view('reports.bill_wise_profit')->with('report_type',$report_type)->with('datas',$datas);
                    
                }

            }


        endif;

        /*Transaction*/

        /*Expense Report*/
        
        if($report_type=='expenses-transaction-report') : 

            $category            = $request->expense_category;
            $start_date          = $request->start_date;
            $end_date            = $request->end_date;
            
            $expense_category  = $this->expensesCategoryRepository->pluck('name','id');
            $expense_category->prepend("Select Expense Category",0);
            
            $data = Expenses::select('expenses.id','expenses.total_amount','expenses.date','expenses.updated_at','expenses_categories.name','payment_mode.name as payment_mode')
                ->leftJoin('expenses_categories', 'expenses.expense_category_id', '=', 'expenses_categories.id')
                ->leftJoin('payment_mode', 'expenses.payment_mode', '=', 'payment_mode.id');

            if($start_date!='' & $end_date!='') {
                $data->whereBetween('expenses.date', [$start_date, $end_date]);
            }
            if($category > 0) {
                $data->where('expenses.expense_category_id', $category);
            }
            $datas = $data->get();

            foreach ($datas as $key => $value) {
              $datas[$key]->date_new  = date('d M Y',strtotime($datas[$key]->date));
              $datas[$key]->expense_total_amount = setting('default_currency').number_format($value->total_amount,2,'.','');
            }

            if($request->ajax()) {

                $dataTable = Datatables::of($datas);
                $dataTable->addIndexColumn();

                $table  = $dataTable    
                            ->addColumn('expense_date', function() {
                                return $datas->date_new;
                            })
                            ->addColumn('id', function() {
                                return $datas->id;
                            })
                            ->addColumn('name', function() {
                                return $datas->name;
                            })
                            ->addColumn('payment_mode', function(){
                                return $datas->payment_mode;
                            })
                            ->addColumn('expense_total_amount', function() {
                                return $datas->expense_total_amount;
                            })
                        ->make(true);

                return Datatables::of($datas)->make(true);
                
            } else {
                 return view('reports.expenses_transaction_report')->with('report_type',$report_type)->with('expense_category',$expense_category);
            }
            
        endif;

        if($report_type=='expenses-category-report') : 

            $start_date          = $request->start_date;
            $end_date            = $request->end_date;
            
            $selectExpense = Expenses::join('expenses_categories','expenses.expense_category_id','expenses_categories.id')
                                ->distinct()->select('expense_category_id');

            if($start_date!='' & $end_date!='') {
                $selectExpense->whereBetween('expenses.date', [$start_date, $end_date]);
            }   
            $selectExpenses = $selectExpense->get();

            $data           = Expenses::join('expenses_categories','expenses.expense_category_id','expenses_categories.id')->select('name',\DB::raw('SUM(total_amount) as expense_total_amount'))
                                ->whereIn('expense_category_id',$selectExpenses);

            if($start_date!='' & $end_date!='') {
                $data->whereBetween('expenses.date', [$start_date, $end_date]);
            }   
            $datas = $data->get();

            if(count($datas) > 0) {
              foreach ($datas as $key => $value) {
                $datas[$key]->expense_total_amount = setting('default_currency').number_format($value->expense_total_amount,2,'.','');
              }
            }

            if($request->ajax()) {

                $dataTable = Datatables::of($datas);
                $dataTable->addIndexColumn();

                $table  = $dataTable    
                            ->addColumn('name', function() {
                                return $datas->name;
                            })
                            ->addColumn('expense_total_amount', function() {
                                return $datas->expense_total_amount;
                            })
                        ->make(true);

                return Datatables::of($datas)->make(true);
                
             } else {
                 return view('reports.expense_category_report')->with('report_type',$report_type)->with('datas',$datas);
             }     
            
        endif;
  
        /*Expense Report*/

        /*Party Report*/

        if($report_type=='party-wise-outstanding') : 

            $start_date          = $request->start_date;
            $end_date            = $request->end_date;

            $data = DB::table('markets')
                                  ->leftJoin('transaction_track','markets.id','=','transaction_track.market_id')
                                  ->select('markets.*',\DB::raw('count(transaction_track.id) as total_no_transactions'),\DB::raw('max(transaction_track.date) as last_transaction'))
                                  ->groupBy('markets.id'); //,'customer_groups.name as party_group'

            if($start_date!='' & $end_date!='') {
                $data->whereBetween('transaction_track.date', [$start_date, $end_date]);
            }   

            $datas = $data->get();


            foreach ($datas as $key => $value) {
                $datas[$key]->balance = number_format($datas[$key]->balance,2,'.',''); 
                if(!empty($datas[$key]->last_transaction)){
                    $datas[$key]->last_transaction = date('d M Y',strtotime($datas[$key]->last_transaction)); 
                }   
            }

            if($request->ajax()) {

                $dataTable = Datatables::of($datas);
                $dataTable->addIndexColumn();

                $table  = $dataTable    
                            ->addColumn('name', function() {
                                return $datas->name;
                            })
                            // ->addColumn('party_group', function() {
                            //     return $datas->party_group;
                            // })
                            ->addColumn('total_no_transactions', function() {
                                return $datas->total_no_transactions;
                            })
                            // ->addColumn('phone', function(){
                            //     return $datas->phone;
                            // })
                            // ->addColumn('mobile', function() {
                            //     return $datas->mobile;
                            // })
                            ->addColumn('balance', function() {
                                return $datas->balance;
                            }) 
                            ->addColumn('last_transaction', function(){
                                return $datas->last_transaction;
                            })  
                        ->make(true);

                return Datatables::of($datas)->make(true);
                
             } else {
                 return view('reports.party_wise_outstanding')->with('report_type',$report_type)->with('datas',$datas);
             }

        endif;

        if($report_type=='party-report-by-item') :
            
            $products = $this->productRepository->pluck('name','id');
            $products->prepend("Select Item",0); 

            $product          = $request->product;
            $start_date       = $request->start_date;
            $end_date         = $request->end_date;

            $datas = $this->marketRepository->get();

            foreach ($datas as $key => $value) {

                $sales_quantity_query = DB::table('sales_invoice')
                                  ->join('sales_invoice_items','sales_invoice.id','=','sales_invoice_items.sales_invoice_id')
                                  ->where('sales_invoice.market_id',$value->id);

                if($product!='' && $product!=0) {
                    $sales_quantity_query->where('sales_invoice_items.product_id',$product);
                }
                if($start_date!='' & $end_date!='') {
                    $sales_quantity_query->whereBetween('sales_invoice.date', [$start_date, $end_date]);
                }   
                $sales_quantity = $sales_quantity_query->sum('quantity');

                $sales_invoice_query   = DB::table('sales_invoice')
                                  ->join('sales_invoice_items','sales_invoice.id','=','sales_invoice_items.sales_invoice_id')
                                  ->where('sales_invoice.market_id',$value->id);

                if($product!='' && $product!=0) {
                    $sales_invoice_query->where('sales_invoice_items.product_id',$product);
                }
                if($start_date!='' & $end_date!='') {
                    $sales_invoice_query->whereBetween('sales_invoice.date', [$start_date, $end_date]);
                }   
                $sales_invoice = $sales_invoice_query->get();
                

                $sales_invoice_amount[] = 0;                                 
                if(count($sales_invoice) > 0) {
                  foreach ($sales_invoice as $key1 => $value1) {
                    $sales_invoice_amount[]= $value1->quantity * $value1->unit_price;
                  }
                }

                $purchase_quantity_query = DB::table('purchase_invoice')
                                    ->join('purchase_invoice_items','purchase_invoice.id','=','purchase_invoice_items.purchase_invoice_id')
                                    ->where('purchase_invoice.market_id',$value->id);

                if($product!='' && $product!=0) {
                    $purchase_quantity_query->where('purchase_invoice_items.product_id',$product);
                }
                if($start_date!='' & $end_date!='') {
                    $purchase_quantity_query->whereBetween('purchase_invoice.date', [$start_date, $end_date]);
                }   
                $purchase_quantity = $purchase_quantity_query->sum('quantity');

                $purchase_invoice_query   = DB::table('purchase_invoice')
                                    ->join('purchase_invoice_items','purchase_invoice.id','=','purchase_invoice_items.purchase_invoice_id')
                                    ->where('purchase_invoice.market_id',$value->id);

                if($product!='' && $product!=0) {
                    $purchase_invoice_query->where('purchase_invoice_items.product_id',$product);
                }
                if($start_date!='' & $end_date!='') {
                    $purchase_invoice_query->whereBetween('purchase_invoice.date', [$start_date, $end_date]);
                }   
                $purchase_invoice = $purchase_invoice_query->get();

                                  
                $purchase_invoice_amount[] = 0;                                 
                if(count($purchase_invoice) > 0) {
                  foreach ($purchase_invoice as $key1 => $value1) {
                    $purchase_invoice_amount[]= $value1->quantity * $value1->unit_price;
                  }
                }


                $datas[$key]->sales_quantity    = number_format($sales_quantity,3,'.','')." KGS";
                $datas[$key]->sales_amount      = setting('default_currency').number_format(array_sum($sales_invoice_amount),2,'.','');
                $datas[$key]->purchase_quantity = number_format($purchase_quantity,3,'.','')." KGS";
                $datas[$key]->purchase_amount   = setting('default_currency').number_format(array_sum($purchase_invoice_amount),2,'.','');
                unset($sales_invoice_amount);
                unset($purchase_invoice_amount);
                if($datas[$key]->purchase_amount <= 0 && $datas[$key]->sales_amount <= 0) {
                    unset($datas[$key]);   
                }
            }

            //$product = $this->productRepository->findWithoutFail($product)->name; 

            if($request->ajax()) {

                $dataTable = Datatables::of($datas);
                $dataTable->addIndexColumn();

                $table  = $dataTable    
                            ->addColumn('name', function() {
                                return $datas->name;
                            })
                            ->addColumn('sales_quantity', function() {
                                return $datas->sales_quantity;
                            })
                            ->addColumn('sales_amount', function() {
                                return $datas->sales_amount;
                            })
                            ->addColumn('purchase_quantity', function(){
                                return $datas->purchase_quantity;
                            })
                            ->addColumn('purchase_amount', function() {
                                return $datas->purchase_amount;
                            })
                        ->make(true);

                return Datatables::of($datas)->make(true);
                
             } else {
                 return view('reports.party_report_by_item')->with('report_type',$report_type)->with('products',$products)->with('datas',$datas);
             }
            
        endif;

        /*Party Report*/ 
        
         /*Products Report*/

         if($report_type=='products-report') : 

            $product_id          = $request->product;
            $start_date          = $request->start_date;
            $end_date            = $request->end_date;

            $products = $this->productRepository->pluck('name','id');
            $products->prepend("Select Product",0);     
            
            //Online Orders
            $data    = DB::table('products')
                            ->leftJoin('product_orders', 'product_orders.product_id','=','products.id')
                            ->select(
                                'products.name',
                                'products.product_code',
                                'products.hsn_code',
                                'products.purchase_price',
                                'products.stock',
                                'products.unit',
                                DB::raw('(products.stock * products.purchase_price) as stockvalue'),
                                'product_orders.quantity as order_quantity',
                                'product_orders.order_id as order_id',
                                'product_orders.unit_price',
                                \DB::raw('Date(product_orders.created_at) AS ordered_date')
                            )->where('order_id','!=','NULL');
            if($product_id!='' & $product_id!=0) {
                $data->where('products.id',$product_id);
            }   
        
            if($start_date!='' & $end_date!='') {
                $data->whereDate('product_orders.created_at','>=',$start_date)->whereDate('product_orders.created_at','<=',$end_date);
            } 
            $items = $data->get();
            //Online Orders

            //Sales Invoices
            $data1    = DB::table('products')
                            ->leftJoin('sales_invoice_items', 'sales_invoice_items.product_id','=','products.id')
                            ->select(
                                'products.name',
                                'products.product_code',
                                'products.hsn_code',
                                'products.purchase_price',
                                'products.stock',
                                'products.unit',
                                DB::raw('(products.stock * products.purchase_price) as stockvalue'),
                                'sales_invoice_items.quantity as order_quantity',
                                'sales_invoice_items.sales_invoice_id as order_id',
                                'sales_invoice_items.amount as price',
                                'sales_invoice_items.unit as unit',
                                \DB::raw('Date(sales_invoice_items.created_at) AS ordered_date')
                            )->where('sales_invoice_id','!=','NULL');
            if($product_id!=0) {
                $data1->where('products.id',$product_id);
            } 
            if($start_date!='' & $end_date!='') {
                $data1->whereDate('sales_invoice_items.created_at','>=',$start_date)->whereDate('sales_invoice_items.created_at','<=',$end_date);
            }   
            $items1 = $data1->get();
            //Sales Invoices
                          
        $sales_data = array_merge($items->toArray(), $items1->toArray());
        
        if(count($sales_data)>0){
            foreach ($sales_data as $key => $value) {

                $units = DB::table('uom')->where('id',$value->unit)->first();

                $sales_data[$key]->unit             = $units->name;
                $sales_data[$key]->purchasing_price = setting('default_currency').''.number_format($sales_data[$key]->purchase_price,2, '.', '');
                $sales_data[$key]->selling_price    = setting('default_currency').''.number_format($sales_data[$key]->price,2, '.', '');
                $sales_data[$key]->stock_quantity   = number_format($sales_data[$key]->stock,3, '.', '').' '.$sales_data[$key]->unit;
                $sales_data[$key]->stock_value      = setting('default_currency').''.number_format($sales_data[$key]->stock * $sales_data[$key]->purchase_price,2, '.', '');
                $stocks[]                           = $sales_data[$key]->stock_value;
                $sales_data[$key]->price            = setting('default_currency').''.number_format($sales_data[$key]->price,2, '.', '');
                $sales_data[$key]->ordered_date     = date('d M Y',strtotime($sales_data[$key]->ordered_date));

                //$sales_data[$key]->order_id         =        
                if($sales_data[$key]->order_quantity==''){
                    $sales_data[$key]->ordered_quantity   = '';
                } else {
                    $sales_data[$key]->ordered_quantity   = number_format($sales_data[$key]->order_quantity,3, '.', '').' '.$sales_data[$key]->unit;
                }
            }  
            $stock_value = setting('default_currency').''.number_format(array_sum($stocks),2, '.', '');
        }

        if($request->ajax()) {

            $dataTable = Datatables::of($sales_data);
            $dataTable->addIndexColumn();

            $table  = $dataTable    
                        ->addColumn('name', function() {
                            return $sales_data->name;
                        })
                        ->addColumn('product_code', function() {
                            return $sales_data->product_code;
                        })
                        ->addColumn('hsn_code', function() {
                            return $sales_data->hsn_code;
                        })
                        ->addColumn('purchasing_price', function(){
                            return $datas->purchasing_price;
                        })
                        ->addColumn('selling_price', function() {
                            return $sales_data->selling_price;
                        })
                        ->addColumn('stock_quantity', function() {
                            return $sales_data->stock_quantity;
                        }) 
                        ->addColumn('stock_value', function(){
                            return $sales_data->stock_value;
                        })
                        ->addColumn('ordered_quantity', function() {
                            return $sales_data->ordered_quantity;
                        }) 
                        ->addColumn('ordered_date', function(){
                            return $sales_data->ordered_date;
                        })
                    ->make(true);

            return Datatables::of($sales_data)->make(true);
            
         } else {
             return view('reports.products_report')->with('report_type',$report_type)->with('products',$products)->with('datas',$sales_data);
         }
            
        endif;
        
        if($report_type=='popular-products-report') : 

            $start_date          = "";
            $end_date            = "";

            if($request->ajax()) {
                $start_date          = $request->start_date;
                $end_date            = $request->end_date;
            }
            
            $sales_data = DB::select('SELECT id,name,product_code,hsn_code,purchase_price,price,stock,unit,stockvalue, SUM(total_quantity) AS ordered_quantity FROM(SELECT a.id,a.name,a.product_code,a.hsn_code,a.purchase_price,a.price,a.stock,a.unit,a.stock*a.purchase_price as stockvalue, SUM(b.quantity) AS total_quantity
FROM products a JOIN product_orders b ON b.product_id = a.id GROUP BY a.id, b.product_id UNION SELECT a.id,a.name,a.product_code,a.hsn_code,a.purchase_price,a.price,a.stock,a.unit,a.stock*a.purchase_price as stockvalue, SUM(c.quantity) AS total_quantity
FROM products a JOIN sales_invoice_items c ON c.product_id = a.id GROUP BY a.id, c.product_id
) temp where purchase_price<price GROUP BY id order by total_quantity desc');

        if(count($sales_data)>0){
        foreach ($sales_data as $key => $value) {

                $units = DB::table('uom')->where('id',$sales_data[$key]->unit)->first();

                $sales_data[$key]->unit             = $units->name;
                $sales_data[$key]->purchasing_price = setting('default_currency').''.number_format($sales_data[$key]->purchase_price,2, '.', '');
                $sales_data[$key]->selling_price    = setting('default_currency').''.number_format($sales_data[$key]->price,2, '.', '');
                $sales_data[$key]->stock_quantity   = number_format($sales_data[$key]->stock,3, '.', '')." ".$sales_data[$key]->unit;
                $sales_data[$key]->stock_value      = setting('default_currency').''.number_format($sales_data[$key]->stock * $sales_data[$key]->purchase_price,2, '.', '');
                $stocks[]                      = $sales_data[$key]->stock_value;
                
                  $profit = $value->price - $value->purchase_price;
                 if($value->purchase_price>0){
                 $profit_percentage = ($profit*100)/$value->purchase_price;
                 }else{ $profit_percentage =100; }
                 if($profit_percentage>0){ $profit_percent = $profit_percentage; } else { $profit_percent = 0; }
                  $sales_data[$key]->profit_percentage   = number_format($profit_percent,2).'%';
            }
           
        $stock_value    = setting('default_currency').''.number_format(array_sum($stocks),2, '.', '');
        }

        if($request->ajax()) {

            $dataTable = Datatables::of($sales_data);
            $dataTable->addIndexColumn();

            $table  = $dataTable    
                        ->addColumn('name', function() {
                            return $sales_data->name;
                        })
                        ->addColumn('product_code', function() {
                            return $sales_data->product_code;
                        })
                        ->addColumn('hsn_code', function() {
                            return $sales_data->hsn_code;
                        })
                        ->addColumn('purchasing_price', function(){
                            return $sales_data->purchasing_price;
                        })
                        ->addColumn('selling_price', function() {
                            return $sales_data->selling_price;
                        })
                        ->addColumn('stock_quantity', function() {
                            return $sales_data->stock_quantity;
                        }) 
                        ->addColumn('profit_percentage', function(){
                            return $sales_data->profit_percentage;
                        })
                    ->make(true);

            return Datatables::of($sales_data)->make(true);
            
         } else {
             return view('reports.profitable_products_report')->with('report_type',$report_type)->with('datas',$sales_data);
         }
            
        endif;

        if($report_type=='profitable-products-report') : 

            $start_date          = "";
            $end_date            = "";

            if($request->ajax()) {
                $start_date          = $request->start_date;
                $end_date            = $request->end_date;
            }
            
            $sales_data = DB::select('SELECT id,name,product_code,hsn_code,purchase_price,price,stock,unit,stockvalue, SUM(total_quantity) AS ordered_quantity FROM(SELECT a.id,a.name,a.product_code,a.hsn_code,a.purchase_price,a.price,a.stock,a.unit,a.stock*a.purchase_price as stockvalue, SUM(b.quantity) AS total_quantity
FROM products a JOIN product_orders b ON b.product_id = a.id GROUP BY a.id, b.product_id UNION SELECT a.id,a.name,a.product_code,a.hsn_code,a.purchase_price,a.price,a.stock,a.unit,a.stock*a.purchase_price as stockvalue, SUM(c.quantity) AS total_quantity
FROM products a JOIN sales_invoice_items c ON c.product_id = a.id GROUP BY a.id, c.product_id
) temp where purchase_price<price GROUP BY id order by total_quantity desc');

        if(count($sales_data)>0){
        foreach ($sales_data as $key => $value) {

                $units = DB::table('uom')->where('id',$sales_data[$key]->unit)->first();

                $sales_data[$key]->unit             = $units->name;
                $sales_data[$key]->purchasing_price = setting('default_currency').''.number_format($sales_data[$key]->purchase_price,2, '.', '');
                $sales_data[$key]->selling_price    = setting('default_currency').''.number_format($sales_data[$key]->price,2, '.', '');
                $sales_data[$key]->stock_quantity   = number_format($sales_data[$key]->stock,3, '.', '')." ".$sales_data[$key]->unit;
                $sales_data[$key]->stock_value      = setting('default_currency').''.number_format($sales_data[$key]->stock * $sales_data[$key]->purchase_price,2, '.', '');
                $stocks[]                      = $sales_data[$key]->stock_value;
                
                  $profit = $value->price - $value->purchase_price;
                 if($value->purchase_price>0){
                 $profit_percentage = ($profit*100)/$value->purchase_price;
                 }else{ $profit_percentage =100; }
                 if($profit_percentage>0){ $profit_percent = $profit_percentage; } else { $profit_percent = 0; }
                  $sales_data[$key]->profit_percentage   = number_format($profit_percent,2).'%';
            }
           
        $stock_value    = setting('default_currency').''.number_format(array_sum($stocks),2, '.', '');
        }

        if($request->ajax()) {

            $dataTable = Datatables::of($sales_data);
            $dataTable->addIndexColumn();

            $table  = $dataTable    
                        ->addColumn('name', function() {
                            return $sales_data->name;
                        })
                        ->addColumn('product_code', function() {
                            return $sales_data->product_code;
                        })
                        ->addColumn('hsn_code', function() {
                            return $sales_data->hsn_code;
                        })
                        ->addColumn('purchasing_price', function(){
                            return $sales_data->purchasing_price;
                        })
                        ->addColumn('selling_price', function() {
                            return $sales_data->selling_price;
                        })
                        ->addColumn('stock_quantity', function() {
                            return $sales_data->stock_quantity;
                        }) 
                        ->addColumn('profit_percentage', function(){
                            return $sales_data->profit_percentage;
                        })
                    ->make(true);

            return Datatables::of($sales_data)->make(true);
            
         } else {
             return view('reports.profitable_products_report')->with('report_type',$report_type)->with('datas',$sales_data);
         }
            
        endif;

        /*Customers Report*/
        if($report_type=='customers-report') : 
            // $market = $this->marketRepository->where('type',1)->pluck('name','id');
            // $market->prepend("Please Select",0);     

            $start_date          = $request->start_date;
            $end_date            = $request->end_date;
            
            $data =  DB::table('markets')
                  ->leftJoin('transaction_track','markets.id','=','transaction_track.market_id')
                  ->leftJoin('customer_groups','markets.customer_group_id','=','customer_groups.id')
                  //->leftJoin('user_markets', 'markets.id','=','user_markets.market_id')
                  ->leftJoin('users', 'markets.user_id','=','users.id')
                  ->leftJoin('customer_levels', 'users.level','=','customer_levels.id')
                  ->select('markets.*','customer_groups.name as party_group',\DB::raw('count(transaction_track.id) as total_no_transactions'),'customer_levels.name as levels','customer_levels.group_points as reward_points')
                  ->groupBy('markets.id')->where('markets.type',1);
                         
            if($start_date!='' & $end_date!='') {
                $data->whereDate('markets.created_at','>=',$start_date)->whereDate('markets.created_at','<=',$end_date);
            }

            $datas = $data->get();
           
            foreach ($datas as $key => $value) {
                $datas[$key]->address = $datas[$key]->address_line_1.' '.$datas[$key]->address_line_2;
                $datas[$key]->reward_levels = $datas[$key]->levels.' - '.$datas[$key]->reward_points;
                $datas[$key]->created_at = date('d M Y',strtotime($datas[$key]->created_at));
            }

            if($request->ajax()) {

                $dataTable = Datatables::of($datas);
                $dataTable->addIndexColumn();

                $table  = $dataTable    
                            ->addColumn('name', function() {
                                return $datas->name;
                            })
                            ->addColumn('created_at', function() {
                                return $datas->created_at;
                            })
                            ->addColumn('email', function() {
                                return $datas->email;
                            })
                            ->addColumn('phone', function(){
                                return $datas->phone;
                            })
                            ->addColumn('mobile', function() {
                                return $datas->mobile;
                            })
                            ->addColumn('reward_levels', function() {
                                return $datas->reward_levels;
                            }) 
                            ->addColumn('total_no_transactions', function(){
                                return $datas->total_no_transactions;
                            })  
                            ->addColumn('party_group', function() {
                                return $datas->party_group;
                            })
                            ->addColumn('address', function() {
                                return $datas->address;
                            })
                            ->addColumn('city', function(){
                                return $datas->city;
                            })
                            ->addColumn('state', function() {
                                return $datas->state;
                            })
                            ->addColumn('pincode', function() {
                                return $datas->pincode;
                            })
                        ->make(true);

                return Datatables::of($datas)->make(true);
                
             } else {
                 return view('reports.customers_report')->with('report_type',$report_type)->with('customer_reports',$datas);
             }
 
        endif;

          /*staff log Report*/

        if($report_type=='staff-login-report') : 
            $users = User::whereHas('roles', function($q){$q->where('name','!=','client');})->pluck('name','id');
            $users->prepend("Select User",0); 
            $role  = $this->roleRepository->where('name','!=','client')->pluck('name','name');
            $role->prepend("Select Role",''); 
            
            // if($user_role!='')
            // {
            //     if($user_role=="admin")
            //     {
            //      $data    = User::whereHas('roles', function($q){$q->where('name','=','admin');})->join('user_log', 'users.id','=','user_log.user_id')->select('users.*','user_log.created_at as created','user_log.updated_at as updated');
            //     }
            //     else if($user_role=="manager")
            //     {
            //       $data    = User::whereHas('roles', function($q){$q->where('name','=','manager');})->join('user_log', 'users.id','=','user_log.user_id')->select('users.*','user_log.created_at as created','user_log.updated_at as updated');
            //     }
            //     else
            //     {
            //       $data    = User::whereHas('roles', function($q){$q->where('name','=','driver');})->join('user_log', 'users.id','=','user_log.user_id')->select('users.*','user_log.created_at as created','user_log.updated_at as updated');  
            //     }
            // }
            // else
            // {
                $data    = User::whereHas('roles', function($q){$q->where('name','!=','client');})->join('user_log', 'users.id','=','user_log.user_id')->select('users.*','user_log.created_at as created','user_log.updated_at as updated');   
            // }
            
            // if($user_id!=0) {
            //     $data->where('users.id',$user_id);
            // }
            
            $datas = $data->get();
            
            dd($datas);
        
            return view('reports.staff_login_report')->with('report_type',$report_type)->with('users',$users)->with('role',$role)->with('datas',$datas);
        endif;

        /*Stock purchase Report*/

        if($report_type=='stock-purchase-report') :
            
            $data = DB::select('SELECT id, name,product_code,hsn_code,purchase_price,price,stock,unit,stockvalue, SUM(total_quantity) AS ordered_quantity FROM(SELECT a.id,a.name,a.product_code,a.hsn_code,a.purchase_price,a.price,a.stock,a.unit,a.stock*a.purchase_price as stockvalue, SUM(b.quantity) AS total_quantity
FROM products a JOIN product_orders b ON b.product_id = a.id GROUP BY a.id, b.product_id UNION SELECT a.id,a.name,a.product_code,a.hsn_code,a.purchase_price,a.price,a.stock,a.unit,a.stock*a.purchase_price as stockvalue, SUM(c.quantity) AS total_quantity
FROM products a JOIN sales_invoice_items c ON c.product_id = a.id GROUP BY a.id, c.product_id
) temp GROUP BY id order by total_quantity asc');


       $arr=[];
       foreach ($data as $key => $value) {
          $items[] = $value;
           $arr[]=$value->id;
            unset($value->id);
       }

          $data1    =  DB::select('SELECT id, name,product_code,hsn_code,purchase_price,price,stock,unit,stock*purchase_price as stockvalue,0 AS ordered_quantity FROM products where id NOT IN (' . implode(',', $arr) . ')');
      
       if(count($data1)>0){
      foreach ($data1 as $key => $value1) {
           $item[] = $value1;
           
           unset($value->id);
      }
     $data2 =  array_merge($item,$items);
      }else
      {
        $data2 =   $items;
      }
     $sales_data = $data2;//array_slice($data2, 0, 10);
         if(count($sales_data)>0){
        foreach ($sales_data as $key => $value) {

                $units = DB::table('uom')->where('id',$value->unit)->first();
                
                $sales_data[$key]->unit_name        = $units->name;
                $sales_data[$key]->purchasing_price = setting('default_currency').''.number_format($sales_data[$key]->purchase_price,2, '.', '');
                $sales_data[$key]->selling_price    = setting('default_currency').''.number_format($sales_data[$key]->price,2, '.', '');
                $sales_data[$key]->stock_quantity   = number_format($sales_data[$key]->stock,3).' '.$sales_data[$key]->unit_name;
                $sales_data[$key]->stock_value      = setting('default_currency').''.number_format($sales_data[$key]->stock * $sales_data[$key]->purchase_price,2, '.', '');
                $stocks[]                      = $sales_data[$key]->stock_value;
                   
                if($sales_data[$key]->ordered_quantity==''){
                 $sales_data[$key]->ordered_quantity   = '';
                }else{
                  $sales_data[$key]->ordered_quantity   = number_format($sales_data[$key]->ordered_quantity,3).' '.$sales_data[$key]->unit_name;
                }
            }
           
        $stock_value    = setting('default_currency').''.number_format(array_sum($stocks),2, '.', '');
        
         }
         
             return view('reports.stock_purchase_report')->with('report_type',$report_type)->with('datas',$sales_data);
        endif;
        
        /*Delivery Report*/

        if($report_type=='delivery-report') :

            $start_date          = "";
            $end_date            = "";

            if($request->ajax()) {
                $start_date          = $request->start_date;
                $end_date            = $request->end_date;
            }
            
            $datas = DB::table('orders')
                ->leftJoin('delivery_addresses', 'delivery_addresses.id','=','orders.delivery_address_id')
                ->leftJoin('users', 'users.id','=','orders.user_id')
                // ->leftJoin('payments', 'payments.id','=','orders.payment_id')
                ->leftJoin('order_statuses', 'order_statuses.id','=','orders.order_status_id')
                ->select('delivery_addresses.*','users.name','orders.delivery_distance','orders.driver_id','orders.id as order_id','order_statuses.status as order_status') //'payments.method','payments.status as payment_status',
                ->get();

            foreach ($datas as $key => $value) {
               
               if($value->address_line_1!=''){
                    $datas[$key]->address = $value->address_line_1.', '.$value->address_line_2.', '.$value->city.', '.$value->state.'-'.$value->pincode;
               } else
               {
                    $datas[$key]->address = '';
               }
               
               if($value->driver_id!=''){
                    $get_driver = DB::table('users')->select('name')->where('id',$value->driver_id)->first();
                    $datas[$key]->delivered_by = $get_driver->name;
               } else
               {
                    $datas[$key]->delivered_by ='';
               }
               
               $datas[$key]->transaction_number ='';
               
            }

            if($request->ajax()) {

                $dataTable = Datatables::of($datas);
                $dataTable->addIndexColumn();

                $table  = $dataTable    
                            ->addColumn('name', function() {
                                return $datas->name;
                            })
                            ->addColumn('address', function() {
                                return $datas->address;
                            })
                            ->addColumn('description', function() {
                                return $datas->description;
                            })
                            ->addColumn('delivery_distance', function(){
                                return $datas->delivery_distance;
                            })
                            ->addColumn('order_id', function() {
                                return $datas->order_id;
                            })
                            ->addColumn('order_status', function() {
                                return $datas->order_status;
                            }) 
                            ->addColumn('delivered_by', function(){
                                return $datas->delivered_by;
                            }) 
                            ->addColumn('transaction_number', function(){
                                return $datas->transaction_number;
                            })  
                        ->make(true);

                return Datatables::of($datas)->make(true);
                
             } else {
                 return view('reports.delivery_report')->with('report_type',$report_type)->with('datas',$datas);
             }  
             
        endif;
        
         if($report_type=='wastage-report') :

                $products = $this->productRepository->pluck('name','id');
                $products->prepend("Select Item",0); 

                $product_id          = $request->product;
                $start_date          = $request->start_date;
                $end_date            = $request->end_date;

                $data = DB::table('inventory_track')
                        ->leftJoin('products', 'inventory_track.product_id','=','products.id')
                        ->leftJoin('uom', 'inventory_track.unit','=','uom.id')
                        ->select('inventory_track.*','uom.name as unit_name','products.name as product_name', 'products.product_code as product_code', 'products.hsn_code as hsn_code')
                        ->where('inventory_track.usage','wastage');

                if($product_id!='' && $product_id!=0) {
                    $data->where('inventory_track.product_id',$product_id);
                }

                if($start_date!='' & $end_date!='') {
                    $data->whereBetween('inventory_track.date', [$start_date, $end_date]);
                } 

                $datas = $data->get();

                foreach ($datas as $key => $value) {
                    $datas[$key]->date  = date('d M Y',strtotime($datas[$key]->date));
                    $datas[$key]->quantity   = number_format($datas[$key]->quantity,3, '.', '');
                    $datas[$key]->unit_name   = $datas[$key]->quantity.' '.$datas[$key]->unit_name;
                }

                $stocks[] = 0;
                $stock = "";
                    
                if($request->ajax()) {

                    $dataTable = Datatables::of($datas);
                    $dataTable->addIndexColumn();

                    $table  = $dataTable    
                                ->addColumn('date', function() {
                                    return $datas->date;
                                })
                                ->addColumn('product_name', function() {
                                    return $datas->products_name;
                                })
                                ->addColumn('product_code', function() {
                                    return $datas->product_code;
                                })
                                ->addColumn('hsn_code', function(){
                                    return $datas->hsn_code;
                                })
                                ->addColumn('type', function() {
                                    return $datas->type;
                                })
                                ->addColumn('quantity', function() {
                                    return $datas->quantity."".$datas->unit_name;
                                }) 
                                ->addColumn('description', function(){
                                    return $datas->description;
                                })  
                            //->rawColumns(['date','transaction_no','status'])
                            ->make(true);

                    return Datatables::of($datas)->make(true);
                    
                 } else {
                     return view('reports.wastage_report')->with('report_type',$report_type)->with('products',$products)->with('wastage',$datas);
                 }

        endif;

        if($report_type=='missing-report') :

                $products = $this->productRepository->pluck('name','id');
                $products->prepend("Select Item",0); 
             
                $product_id          = $request->product;
                $start_date          = $request->start_date;
                $end_date            = $request->end_date;

                $data = DB::table('inventory_track')
                        ->leftJoin('products', 'inventory_track.product_id','=','products.id')
                        ->leftJoin('uom', 'inventory_track.unit','=','uom.id')
                        ->select('inventory_track.*','uom.name as unit_name','products.name as product_name', 'products.product_code as product_code', 'products.hsn_code as hsn_code')
                        ->where('inventory_track.usage','missing');

                if($product_id!='' && $product_id!=0) {
                    $data->where('inventory_track.product_id',$product_id);
                }

                if($start_date!='' & $end_date!='') {
                    $data->whereBetween('inventory_track.date', [$start_date, $end_date]);
                } 

                $datas = $data->get();

                foreach ($datas as $key => $value) {
                    $datas[$key]->date  = date('d M Y',strtotime($datas[$key]->date));
                    $datas[$key]->quantity   = number_format($datas[$key]->quantity,3, '.', '');
                    $datas[$key]->unit_name   = $datas[$key]->quantity.' '.$datas[$key]->unit_name;
                }

                $stocks[] = 0;
                $stock = "";
                    
                if($request->ajax()) {

                    $dataTable = Datatables::of($datas);
                    $dataTable->addIndexColumn();

                    $table  = $dataTable    
                                ->addColumn('date', function() {
                                    return $datas->date;
                                })
                                ->addColumn('product_name', function() {
                                    return $datas->products_name;
                                })
                                ->addColumn('product_code', function() {
                                    return $datas->product_code;
                                })
                                ->addColumn('hsn_code', function(){
                                    return $datas->hsn_code;
                                })
                                ->addColumn('type', function() {
                                    return $datas->type;
                                })
                                ->addColumn('quantity', function() {
                                    return $datas->quantity."".$datas->unit_name;
                                }) 
                                ->addColumn('description', function(){
                                    return $datas->description;
                                })  
                            //->rawColumns(['date','transaction_no','status'])
                            ->make(true);

                    return Datatables::of($datas)->make(true);
                    
                 } else {
                     return view('reports.missing_report')->with('report_type',$report_type)->with('products',$products)->with('wastage',$datas);
                 }

        endif;

        if($report_type=='charity-wastage-report') :

                $products = $this->productRepository->pluck('name','id');
                $products->prepend("Select Item",0); 
         
                $product_id          = $request->product;
                $start_date          = $request->start_date;
                $end_date            = $request->end_date;

                $data = DB::table('inventory_track')
                        ->leftJoin('wastage_disposal', 'inventory_track.id','=','wastage_disposal.inventory_id')
                        ->leftJoin('products', 'wastage_disposal.product_id','=','products.id')
                        ->leftJoin('uom', 'wastage_disposal.unit','=','uom.id')
                        ->select('inventory_track.*','uom.name as unit_name','products.name as product_name', 'products.product_code as product_code', 'products.hsn_code as hsn_code', 'wastage_disposal.description as description_note')
                        ->where('inventory_track.usage','wastage')
                        ->where('wastage_disposal.type','charity');

                if($product_id!='' && $product_id!=0) {
                    $data->where('inventory_track.product_id',$product_id);
                }

                if($start_date!='' & $end_date!='') {
                    $data->whereBetween('inventory_track.date', [$start_date, $end_date]);
                } 

                $datas = $data->get();
                
                foreach ($datas as $key => $value) {
                    $datas[$key]->date  = date('d M Y',strtotime($datas[$key]->date));
                    $datas[$key]->quantity   = number_format($datas[$key]->quantity,3, '.', '');
                    $datas[$key]->unit_name   = $datas[$key]->quantity.' '.$datas[$key]->unit_name;
                }

                $stocks[] = 0;
                $stock = "";
                    
                if($request->ajax()) {

                    $dataTable = Datatables::of($datas);
                    $dataTable->addIndexColumn();

                    $table  = $dataTable    
                                ->addColumn('date', function() {
                                    return $datas->date;
                                })
                                ->addColumn('product_name', function() {
                                    return $datas->products_name;
                                })
                                ->addColumn('product_code', function() {
                                    return $datas->product_code;
                                })
                                ->addColumn('hsn_code', function(){
                                    return $datas->hsn_code;
                                })
                                // ->addColumn('type', function() {
                                //     return $datas->type;
                                // })
                                ->addColumn('quantity', function() {
                                    return $datas->unit_name;
                                }) 
                                ->addColumn('description', function(){
                                    return $datas->description_note;
                                })  
                            //->rawColumns(['date','transaction_no','status'])
                            ->make(true);

                    return Datatables::of($datas)->make(true);
                    
                 } else {
                     return view('reports.charity_wastage_report')->with('report_type',$report_type)->with('products',$products)->with('datas',$datas);
                 }

        endif;
        
        if($report_type=='vendor-stocks-report') :

                $products = $this->productRepository->pluck('name','id');
                $products->prepend("Select Item",0); 

                $product_id          = $request->product;
                $start_date          = $request->start_date;
                $end_date            = $request->end_date;

                $data = DB::table('vendor_stock_items')
                            // ->leftJoin('vendor_stock_items', 'vendor_stock.id','=','vendor_stock_items.vendor_stock_id')
                            ->leftJoin('products', 'vendor_stock_items.product_id','=','products.id')
                            ->leftJoin('uom', 'vendor_stock_items.unit','=','uom.id')
                            ->select(
                                'vendor_stock_items.*',
                                'uom.name as unit_name',
                                'products.name as product_name',
                                'products.product_code as product_code',
                                'products.hsn_code as hsn_code',
                                DB::raw('sum(vendor_stock_items.quantity) as toatl_quantity'),
                                DB::raw('sum(vendor_stock_items.amount) as total_amount')
                            )->groupBy('product_id');

                if($product_id!='' && $product_id!=0) {
                    $data->where('vendor_stock_items.product_id',$product_id);
                }

                if($start_date!='' & $end_date!='') {
                    $data->whereBetween('vendor_stock_items.created_at', [$start_date, $end_date]);
                } 

                $datas = $data->get();
                
                foreach ($datas as $key => $value) {
                    $datas[$key]->date  = date('d M Y',strtotime($datas[$key]->created_at));
                    $datas[$key]->quantity   = number_format($datas[$key]->toatl_quantity,3, '.', '');
                    $datas[$key]->unit_name   = $datas[$key]->quantity.' '.$datas[$key]->unit_name;
                    $datas[$key]->total   = setting('default_currency').''.number_format($datas[$key]->total_amount,2, '.', '');
                }

                $stocks[] = 0;
                $stock = "";
                    
                if($request->ajax()) {

                    $dataTable = Datatables::of($datas);
                    $dataTable->addIndexColumn();

                    $table  = $dataTable   
                                ->addColumn('product_name', function() {
                                    return $datas->product_name;
                                })
                                ->addColumn('product_code', function() {
                                    return $datas->product_code;
                                })
                                ->addColumn('hsn_code', function(){
                                    return $datas->hsn_code;
                                })
                                ->addColumn('quantity', function() {
                                    return $datas->unit_name;
                                }) 
                                ->addColumn('total', function(){
                                    return $datas->total;
                                })  
                            //->rawColumns(['date','transaction_no','status'])
                            ->make(true);

                    return Datatables::of($datas)->make(true);
                    
                 } else {
                     return view('reports.vendor_stocks_report')->with('report_type',$report_type)->with('products',$products)->with('datas',$datas);
                 }

        endif;
        
        if($report_type=='email-alerts-report') :

                $partyTypes  = $this->partyTypesRepository->pluck('name', 'id');
                $partyTypes->prepend("Select Item",0); 
             
                $party_id            = "";
                $start_date          = "";
                $end_date            = "";

                if($request->ajax()) {
                    $party_id            = $request->party;
                    $start_date          = $request->start_date;
                    $end_date            = $request->end_date;
                }

                $data = DB::table('email_notifications')
                            ->leftJoin('party_types', 'email_notifications.party_type_id','=','party_types.id')
                            ->leftJoin('party_sub_types', 'email_notifications.party_sub_type_id','=','party_sub_types.id')
                            ->select(
                                'email_notifications.*',
                                'party_types.name as party_type_name',
                                'party_sub_types.name as party_sub_type_name');

                if($party_id!='' && $party_id!=0) {
                    $data->where('email_notifications.party_type_id',$party_id);
                }

                if($start_date!='' & $end_date!='') {
                    $data->whereBetween('email_notifications.created_at', [$start_date, $end_date]);
                } 

                $datas = $data->get();
                
                //dd($datas);
                
                foreach ($datas as $key => $value) {
                    $datas[$key]->date  = date('d M Y',strtotime($datas[$key]->created_at));
                }
                    
                if($request->ajax()) {

                    $dataTable = Datatables::of($datas);
                    $dataTable->addIndexColumn();

                    $table  = $dataTable   
                                ->addColumn('date', function() {
                                    return $datas->date;
                                })
                                ->addColumn('subject', function() {
                                    return $datas->subject;
                                })
                                ->addColumn('type', function(){
                                    return $datas->type;
                                })
                                ->addColumn('party_type_name', function() {
                                    return $datas->party_type_name;
                                }) 
                                // ->addColumn('partyname', function() {
                                //     return $datas->partyname;
                                // })
                                ->addColumn('status', function(){
                                    return $datas->status;
                                })
                            ->make(true);

                    return Datatables::of($datas)->make(true);
                    
                 } else {
                     return view('reports.email_alerts_report')->with('report_type',$report_type)->with('partyTypes',$partyTypes)->with('datas',$datas);
                 }

        endif;

        if($report_type=='online-orders-report') :

                $products = $this->productRepository->pluck('name','id');
                $products->prepend("Select Item",0); 
             
                $product_id          = $request->product;
                $start_date          = $request->start_date;
                $end_date            = $request->end_date;

                $data = DB::table('inventory_track')
                            ->leftJoin('products', 'inventory_track.product_id','=','products.id')
                            // ->leftJoin('markets', 'inventory_track.market_id','=','markets.id')
                            ->leftJoin('uom', 'inventory_track.unit','=','uom.id')
                            ->select(
                                'inventory_track.*',
                                'products.name as product_name',
                                'products.product_code as product_code',
                                'products.hsn_code as hsn_code',
                                'uom.name as unit_name',
                                DB::raw('sum(inventory_track.quantity) as toatl_quantity')
                            )->groupBy('inventory_track.product_id');

                $data->where('inventory_track.category','online');

                if($product_id!='' && $product_id!=0) {
                    $data->where('inventory_track.product_id',$product_id);
                }

                if($start_date!='' & $end_date!='') {
                    $data->whereBetween('inventory_track.created_at', [$start_date, $end_date]);
                } 

                $datas = $data->get();
                
                //dd($datas);
                
                foreach ($datas as $key => $value) {
                    $datas[$key]->date  = date('d M Y',strtotime($datas[$key]->created_at));
                    $datas[$key]->quantity   = number_format($datas[$key]->toatl_quantity,3, '.', '').' '.$datas[$key]->unit_name;
                    // $datas[$key]->unit_name   = $datas[$key]->quantity.' '.$datas[$key]->unit_name;
                }
                    
                if($request->ajax()) {

                    $dataTable = Datatables::of($datas);
                    $dataTable->addIndexColumn();

                    $table  = $dataTable   
                                ->addColumn('date', function() {
                                    return $datas->date;
                                })
                                ->addColumn('product_name', function() {
                                    return $datas->product_name;
                                })
                                ->addColumn('product_code', function(){
                                    return $datas->product_code;
                                })
                                ->addColumn('hsn_code', function() {
                                    return $datas->hsn_code;
                                }) 
                                // ->addColumn('unit_name', function() {
                                //     return $datas->unit_name;
                                // })
                                ->addColumn('quantity', function(){
                                    return $datas->quantity;
                                })
                            ->make(true);

                    return Datatables::of($datas)->make(true);
                    
                 } else {
                     return view('reports.online_orders_report')->with('report_type',$report_type)->with('products',$products)->with('datas',$datas);
                 }

        endif;
    }


    public function salesSummaryReport(Request $request) {
        $start_date    = $request->start_date;
        $end_date      = $request->end_date;
        
        // $data    = DB::table('sales_invoice')
        //                     ->leftJoin('sales_invoice_items', 'sales_invoice.id','=','sales_invoice_items.sales_invoice_id')
        //                     ->groupBy('sales_invoice_items.sales_invoice_id');
                            
        $data    = DB::table('sales_invoice')
                            ->join('markets', 'sales_invoice.market_id','=','markets.id')
                            ->leftJoin('customer_groups', 'markets.customer_group_id','=','customer_groups.id')
                            //->leftJoin('user_markets', 'markets.id','=','user_markets.market_id')
                            ->leftJoin('users', 'markets.user_id','=','users.id')
                            ->leftJoin('customer_levels', 'users.level','=','customer_levels.id')
                            ->leftJoin('sales_invoice_items', 'sales_invoice.id','=','sales_invoice_items.sales_invoice_id')
                            ->leftJoin('payment_mode', 'sales_invoice.payment_method','=','payment_mode.id')
                            ->select('sales_invoice.*','markets.name as market_id','customer_groups.name as party_group','customer_levels.name as reward_level',DB::raw('COUNT(sales_invoice_items.sales_invoice_id) as no_of_items'),'payment_mode.name as payment_type')
                             ->groupBy('sales_invoice_items.sales_invoice_id');

        $data    = DB::table('sales_invoice')
                            ->join('markets', 'sales_invoice.market_id','=','markets.id')
                            ->select('sales_invoice.*','markets.name as market_id');
        if($start_date!='' & $end_date!='') {
            $data->whereBetween('sales_invoice.date', [$start_date, $end_date]);
        }                    
        $sales_data = $data->get();
        
        foreach ($sales_data as $key => $value) {
              if($value->cash_paid >= $value->total) {
                     $sales_data[$key]->payment_status  = 'Paid';
                } elseif($value->cash_paid > 0) {
                     $sales_data[$key]->payment_status  = 'Partially Paid';
                } else {
                     $sales_data[$key]->payment_status  = 'Unpaid';
                }
            $sales_data[$key]->total = setting('default_currency').''.number_format($sales_data[$key]->total,2);
        }
        return $sales_data; //Datatables::of($sales_data)->make(true);
    }

    public function salesPartyStatement(Request $request) {
        $start_date    = $request->start_date;
        $end_date      = $request->end_date;
        $market_id     = $request->party;
        
        $opening_credit = DB::table('transaction_track')
                            ->where('transaction_track_date','<',$start_date)
                            ->where('transaction_track_market_id',$market_id)
                            ->where('transaction_track_type','credit')
                            ->sum('transaction_track_amount');
        
        $opening_debit  = DB::table('transaction_track')
                            ->where('transaction_track_date','<',$start_date)
                            ->where('transaction_track_market_id',$market_id)
                            ->where('transaction_track_type','debit')
                            ->sum('transaction_track_amount');                    
        $opening_balance= $opening_credit - $opening_debit;                     
       
        $opening_data = array(
            'transaction_track_category' => 'Opening Balance',
            'credit'  => number_format($opening_credit,'2','.',''),
            'debit'   => number_format($opening_debit,'2','.',''),
            'balance' => abs($opening_balance)
        );

       $data = TransactionTrack::select(
            'transaction_track.transaction_number',
            'transaction_track.transaction_track_category'
        )->where('transaction_track_market_id',$market_id)->distinct();
        if($start_date!='' & $end_date!='') {
            $data->whereBetween('transaction_track.transaction_track_date', [$start_date, $end_date]);
        }
        $datas = $data->orderBy('transaction_track.created_at','asc')->get();
        
        $balance[] = $opening_balance;
       
        for($i=0; $i<count($datas); $i++) {

           if($datas[$i]->transaction_track_category=='purchase') {
               $invoice = $this->purchaseInvoiceRepository->findWithoutFail($datas[$i]->transaction_number);
               $datas[$i]->transaction_no          = $invoice->purchase_code;
               $datas[$i]->transaction_track_date  = date('Y-m-d',strtotime($invoice->purchase_date));

               $datas[$i]->credit   = number_format($invoice->purchase_total_amount,2,'.',''); 
               $purchase_debit      = DB::table('transaction_track')
                                            ->where('transaction_number',$datas[$i]->transaction_number)
                                            ->where('transaction_track_category','purchase')
                                            ->where('transaction_track_type','debit')
                                            ->whereBetween('transaction_track.transaction_track_date', [$start_date, $end_date])
                                            ->sum('transaction_track_amount');
               $datas[$i]->debit     = ($purchase_debit > 0) ? number_format($purchase_debit,2,'.',''):'';
               $datas[$i]->transaction_url = route('purchaseInvoice.show',$datas[$i]->transaction_number); 
           }

           if($datas[$i]->transaction_track_category=='purchase_return') {
               $invoice = $this->purchaseReturnRepository->findWithoutFail($datas[$i]->transaction_number);
               $datas[$i]->transaction_no          = $invoice->purchase_code;
               $datas[$i]->transaction_track_date  = date('Y-m-d',strtotime($invoice->purchase_date));
               
               $purchase_credit      = DB::table('transaction_track')
                                            ->where('transaction_number',$datas[$i]->transaction_number)
                                            ->where('transaction_track_category','purchase_return')
                                            ->where('transaction_track_type','credit')
                                            ->whereBetween('transaction_track.transaction_track_date', [$start_date, $end_date])
                                            ->sum('transaction_track_amount');
               $datas[$i]->credit     = ($purchase_credit > 0) ? number_format($purchase_credit,2):'';
               $datas[$i]->debit      = number_format($invoice->purchase_total_amount,2);
               $datas[$i]->transaction_url = route('purchaseReturn.show',$datas[$i]->transaction_number);
           }

           if($datas[$i]->transaction_track_category=='sales') {
               $invoice = $this->salesInvoiceRepository->findWithoutFail($datas[$i]->transaction_number);
               $datas[$i]->transaction_no          = $invoice->sales_code;
               $datas[$i]->transaction_track_date  = date('Y-m-d',strtotime($invoice->sales_date));

               $sales_credit            = DB::table('transaction_track')
                                                ->where('transaction_number',$datas[$i]->transaction_number)
                                                ->where('transaction_track_category','sales')
                                                ->where('transaction_track_type','credit')
                                                ->whereBetween('transaction_track.transaction_track_date', [$start_date, $end_date])
                                                ->sum('transaction_track_amount'); 
               $datas[$i]->credit       = ($sales_credit > 0) ? number_format($sales_credit,2) :  '' ; 
               $datas[$i]->debit        = number_format($invoice->sales_total_amount,2);
               $datas[$i]->transaction_url = route('salesInvoice.show',$datas[$i]->transaction_number);
           }


           if($datas[$i]->transaction_track_category=='sales_return') {
               $invoice = $this->salesReturnRepository->findWithoutFail($datas[$i]->transaction_number);
               $datas[$i]->transaction_no          = $invoice->sales_code;
               $datas[$i]->transaction_track_date  = date('Y-m-d',strtotime($invoice->sales_date));
               
               $datas[$i]->credit      = number_format($invoice->sales_total_amount,2);
               $sales_debit            = DB::table('transaction_track')
                                                ->where('transaction_number',$datas[$i]->transaction_number)
                                                ->where('transaction_track_category','sales_return')
                                                ->where('transaction_track_type','debit')
                                                ->whereBetween('transaction_track.transaction_track_date', [$start_date, $end_date])
                                                ->sum('transaction_track_amount'); 
               $datas[$i]->debit       = ($sales_debit > 0) ? number_format($sales_debit,2) :  '' ; 
               $datas[$i]->transaction_url = route('salesReturn.show',$datas[$i]->transaction_number);
           }

           if($datas[$i]->transaction_track_category=='payment_in') {
               $invoice = $this->paymentInRepository->findWithoutFail($datas[$i]->transaction_number);
               $datas[$i]->transaction_no          = $invoice->payment_in_no;
               $datas[$i]->transaction_track_date  = date('Y-m-d',strtotime($invoice->payment_in_date)); 
               $datas[$i]->credit                  = number_format($invoice->payment_in_amount,2); 
               $datas[$i]->debit                   = '';
               $datas[$i]->transaction_url = route('paymentIn.show',$datas[$i]->transaction_number);
           }

           if($datas[$i]->transaction_track_category=='payment_out') {
               $invoice = $this->paymentOutRepository->findWithoutFail($datas[$i]->transaction_number);
               $datas[$i]->transaction_no          = $invoice->payment_out_no;
               $datas[$i]->transaction_track_date  = date('Y-m-d',strtotime($invoice->payment_out_date));
               $datas[$i]->credit                  = '';
               $datas[$i]->debit                   = number_format($invoice->payment_out_amount,2);
               $datas[$i]->transaction_url = route('paymentOut.show',$datas[$i]->transaction_number);
           }
           
           $credit = ($datas[$i]->credit) ? $datas[$i]->credit :  0 ;
           $debit  = ($datas[$i]->debit) ? $datas[$i]->debit :  0 ;
           
           $openingBalance     = array_sum($balance);
           $newBalance         = $openingBalance + ($credit - $debit);
           unset($balance);
           $balance[]          = $newBalance;
           $datas[$i]->balance = abs($newBalance);
           
           $datas[$i]->transaction_track_category = ucfirst( str_replace('_', ' ', $datas[$i]->transaction_track_category) );
           
        }
        
        $closing_data = array(
            'transaction_track_category' => 'Closing Balance',
            'credit'  => '',
            'debit'   => '',
            'balance' => abs(array_sum($balance))
        );
        $datas->prepend($opening_data);
        
        return Datatables::of($datas)->make(true);

    }

    /*public function dayBook(Request $request) {
        $start_date    = $request->start_date;
        $end_date      = $request->end_date;

        $data = TransactionTrack::select(
            'transaction_track.transaction_number',
            'transaction_track.transaction_track_category',
            'transaction_track.transaction_track_market_id'
        )->distinct();

        if($start_date!='' & $end_date!='') {
            $data->whereBetween('transaction_track.transaction_track_date', [$start_date, $end_date]);
        }
        $datas = $data->get();

        for($i=0; $i<count($datas); $i++) {

           if($datas[$i]->transaction_track_category=='purchase') {
               $invoice = $this->purchaseInvoiceRepository->findWithoutFail($datas[$i]->transaction_number);
               $datas[$i]->transaction_track_date  = date('Y-m-d',strtotime($invoice->purchase_date));
               $datas[$i]->transaction_code        = $invoice->purchase_code;    
               $datas[$i]->credit   = setting('default_currency').''.number_format($invoice->purchase_total_amount,2); 
               $purchase_debit      = DB::table('transaction_track')
                                            ->where('transaction_number',$datas[$i]->transaction_number)
                                            ->where('transaction_track_category','purchase')
                                            ->where('transaction_track_type','debit')
                                            ->whereBetween('transaction_track.transaction_track_date', [$start_date, $end_date])
                                            ->sum('transaction_track_amount');
               $datas[$i]->debit     = ($purchase_debit > 0) ? setting('default_currency').''.number_format($purchase_debit,2):'';

           }
           
           if($datas[$i]->transaction_track_category=='purchase_return') {
               $invoice = $this->purchaseReturnRepository->findWithoutFail($datas[$i]->transaction_number);
               $datas[$i]->transaction_track_date  = date('Y-m-d',strtotime($invoice->purchase_date));
               $datas[$i]->transaction_code        = $invoice->purchase_code;
               $purchase_credit      = DB::table('transaction_track')
                                            ->where('transaction_number',$datas[$i]->transaction_number)
                                            ->where('transaction_track_category','purchase_return')
                                            ->where('transaction_track_type','credit')
                                            ->whereBetween('transaction_track.transaction_track_date', [$start_date, $end_date])
                                            ->sum('transaction_track_amount');
               $datas[$i]->credit     = ($purchase_credit > 0) ? setting('default_currency').''.number_format($purchase_credit,2):'';
                $datas[$i]->debit   = setting('default_currency').''.number_format($invoice->purchase_total_amount,2); 

           }

           if($datas[$i]->transaction_track_category=='sales') {
               $invoice = $this->salesInvoiceRepository->findWithoutFail($datas[$i]->transaction_number);
               $datas[$i]->transaction_track_date  = date('Y-m-d',strtotime($invoice->sales_date));
               $datas[$i]->transaction_code        = $invoice->sales_code;
               $sales_credit            = DB::table('transaction_track')
                                                ->where('transaction_number',$datas[$i]->transaction_number)
                                                ->where('transaction_track_category','sales')
                                                ->where('transaction_track_type','credit')
                                                ->whereBetween('transaction_track.transaction_track_date', [$start_date, $end_date])
                                                ->sum('transaction_track_amount'); 
               $datas[$i]->credit       = ($sales_credit > 0) ? setting('default_currency').''.number_format($sales_credit,2) :  '' ; 
               $datas[$i]->debit        = setting('default_currency').''.number_format($invoice->sales_total_amount,2);
           }
           
           if($datas[$i]->transaction_track_category=='sales_return') {
               $invoice = $this->salesReturnRepository->findWithoutFail($datas[$i]->transaction_number);
               $datas[$i]->transaction_track_date  = date('Y-m-d',strtotime($invoice->sales_date));
               $datas[$i]->transaction_code        = $invoice->sales_code;
               $sales_debit            = DB::table('transaction_track')
                                                ->where('transaction_number',$datas[$i]->transaction_number)
                                                ->where('transaction_track_category','sales_return')
                                                ->where('transaction_track_type','debit')
                                                ->whereBetween('transaction_track.transaction_track_date', [$start_date, $end_date])
                                                ->sum('transaction_track_amount'); 
               $datas[$i]->credit      = setting('default_currency').''.number_format($invoice->sales_total_amount,2);
               $datas[$i]->debit       = ($sales_debit > 0) ? setting('default_currency').''.number_format($sales_debit,2) :  '' ; 
           }

           if($datas[$i]->transaction_track_category=='payment_in') {
               $invoice = $this->paymentInRepository->findWithoutFail($datas[$i]->transaction_number);
               $datas[$i]->transaction_code        = $invoice->payment_in_no;
               $datas[$i]->transaction_track_date  = date('Y-m-d',strtotime($invoice->payment_in_date)); 
               $datas[$i]->credit                  = setting('default_currency').''.number_format($invoice->payment_in_amount,2); 
               $datas[$i]->debit                   = '';
           }

           if($datas[$i]->transaction_track_category=='payment_out') {
               $invoice = $this->paymentOutRepository->findWithoutFail($datas[$i]->transaction_number);
               $datas[$i]->transaction_code        = $invoice->payment_out_no;
               $datas[$i]->transaction_track_date  = date('Y-m-d',strtotime($invoice->payment_out_date));
               $datas[$i]->credit                  = '';
               $datas[$i]->debit                   = setting('default_currency').''.number_format($invoice->payment_out_amount,2); 
               
           }

           $datas[$i]->transaction_track_category = ucfirst( str_replace('_', ' ', $datas[$i]->transaction_track_category) );
           $datas[$i]->party = $this->marketRepository->findWithoutFail($datas[$i]->transaction_track_market_id)->name;

        }
        return Datatables::of($datas->reverse())->make(true);
    }*/

    public function stockSummary(Request $request) {

        //$start_date    = $request->start_date;
        //$end_date      = $request->end_date;
        if($request->ajax()) {

            $datas = $this->productRepository->get();
            foreach ($datas as $key => $value) {
                $datas[$key]->purchasing_price = setting('default_currency').' '.number_format($datas[$key]->purchase_price,2, '.', '');
                $datas[$key]->selling_price    = setting('default_currency').' '.number_format($datas[$key]->price,2, '.', '');
                $datas[$key]->stock_quantity   = $datas[$key]->stock.' '.$datas[$key]->unit;
                $datas[$key]->stock_value      = setting('default_currency').' '.number_format($datas[$key]->stock * $datas[$key]->purchase_price,2, '.', '');
                 $datas[$key]->created   = date("Y-m-d",strtotime($value->created_at));
                 $profit = $value->price - $value->purchase_price;
                 if($value->purchase_price>0){
                 $profit_percentage = ($profit*100)/$value->purchase_price;
                 }else{ $profit_percentage =100; }
                 if($profit_percentage>0){ $profit_percent = $profit_percentage; } else{ $profit_percent = 0; }
                  $datas[$key]->profit_percentage   = number_format($profit_percent,2).'%';
            }
            return Datatables::of($datas)->make(true);
        } 

    }

    public function rateList(Request $request) {

        //$start_date    = $request->start_date;
        //$end_date      = $request->end_date;
        if($request->ajax()) {

            $datas = $this->productRepository->get();
            $customer_groups = DB::table('customer_groups')->get();
            foreach ($datas as $key => $value) {

                foreach($customer_groups as $customer_group) {
                  $price_variation = DB::table('product_group_price')->where('customer_group_id',$customer_group->id)->where('product_id',$datas[$key]->id)->get();
                  if(count($price_variation) > 0) {
                    $datas[$key]->{strtolower($customer_group->name)} = setting('default_currency').' '.number_format($price_variation[0]->product_price,2, '.', '');  
                  } else {
                    $datas[$key]->{strtolower($customer_group->name)} = '';
                  }
                }
                $datas[$key]->purchasing_price = setting('default_currency').' '.number_format($datas[$key]->purchase_price,2, '.', '');
                $datas[$key]->selling_price    = setting('default_currency').' '.number_format($datas[$key]->price,2, '.', '');
            }
            return Datatables::of($datas)->make(true);
        } 

    }

    public function itemSalesSummary(Request $request) {
        $start_date    = $request->start_date;
        $end_date      = $request->end_date;
        if($request->ajax()) {

            $datas = DB::table('sales_invoice_detail')
                      ->select('sales_detail_product_id','sales_detail_product_name')
                      ->distinct()
                      ->whereBetween('sales_invoice_detail.created_at', [$start_date, $end_date])
                      ->get();
            foreach ($datas as $key => $value) {
              $product            = $this->productRepository->findWithoutFail($datas[$key]->sales_detail_product_id);
              $datas[$key]->name  = $product->name;
              $datas[$key]->unit  = $product->unit;
              $datas[$key]->total_sales = DB::table('sales_invoice_detail')
                                            ->where('sales_detail_product_id',$datas[$key]->sales_detail_product_id)
                                            ->whereBetween('sales_invoice_detail.created_at', [$start_date, $end_date])
                                            ->sum('sales_detail_quantity');
            }
           
            return Datatables::of($datas)->make(true);
        } 
    }

    public function lowStockSummary(Request $request) {
        if($request->ajax()) {

            $datas = $this->productRepository->whereRaw('low_stock_unit >= stock')->get();
            foreach ($datas as $key => $value) {
              //if($datas[$key]->low_stock_unit >= $datas[$key]->stock) {
                $datas[$key]->stock_quantity  = $datas[$key]->stock.' '.$datas[$key]->unit;
                $datas[$key]->low_stock_level = $datas[$key]->low_stock_unit.' '.$datas[$key]->unit;
                $datas[$key]->stock_value     = setting('default_currency').number_format($datas[$key]->stock * $datas[$key]->purchase_price,2,'.','');
            //   } else {
            //     unset($datas[$key]);
            //   }
            }
            return Datatables::of($datas)->make(true);
        }
    }

    public function stockDetailReport(Request $request) {

        $product_id          = $request->product;
        $start_date          = $request->start_date;
        $end_date            = $request->end_date;
        if($request->ajax()) {

            $data = InventoryTrack::select(
                'inventory_track.id',
                'inventory_track.inventory_track_date',
                'inventory_track.inventory_track_category',
                'inventory_track.inventory_track_type',
                'inventory_track.inventory_track_product_quantity'
            )->where('inventory_track_product_id',$product_id);

            $datas = $data->get();
            $product = $this->productRepository->findWithoutFail($product_id);
            $stocks[] = 0;
            for($i=0; $i<count($datas); $i++) {
               
               if($datas[$i]->inventory_track_type=='add') {
                    $stock = array_sum($stocks) + $datas[$i]->inventory_track_product_quantity;
               } else if($datas[$i]->inventory_track_type=='reduce') {
                    $stock = array_sum($stocks) - $datas[$i]->inventory_track_product_quantity;
               }
               $datas[$i]->inventory_closing_stock  = $stock.' '.$product->unit; 
               $datas[$i]->inventory_track_category = ucfirst(str_replace("_"," ",$datas[$i]->inventory_track_category));
               ($datas[$i]->inventory_track_type=='add') ? $operator='' : $operator='-';
               $datas[$i]->quantity                 = $operator.$datas[$i]->inventory_track_product_quantity.' '.$product->unit;
               unset($stocks);
               $stocks[] = $stock;
            }
            //dd($datas->reverse());
            return Datatables::of($datas->reverse())->make(true);

        }

    }

    public function expenseTransactionReport(Request $request) {
        $expense_category    = $request->expense_category;
        $start_date          = $request->start_date;
        $end_date            = $request->end_date;
        if($request->ajax()) {

            $data = Expenses::select('expenses.id','expenses.expense_total_amount','expenses.expense_date','expenses.updated_at','expenses_categories.name','payment_mode.name as payment_mode')
                ->leftJoin('expenses_categories', 'expenses.expense_category', '=', 'expenses_categories.id')
                ->leftJoin('payment_mode', 'expenses.expense_payment_mode', '=', 'payment_mode.id');

            if($start_date!='' & $end_date!='') {
                $data->whereBetween('expenses.expense_date', [$start_date, $end_date]);
            }
            if($expense_category > 0) {
                $data->where('expenses.expense_category', $expense_category);
            }
            $datas = $data->get();
            foreach ($datas as $key => $value) {
              $datas[$key]->expense_total_amount = setting('default_currency').number_format($value->expense_total_amount,2,'.','');
            }    
            return Datatables::of($datas)->make(true);

        }
    }

    public function expenseCategoryReport(Request $request) {
        $start_date    = $request->start_date;
        $end_date      = $request->end_date;
        if($request->ajax()) {

            $selectExpenses = Expenses::join('expenses_categories','expenses.expense_category','expenses_categories.id')
                                ->distinct()->select('expense_category')
                                ->whereBetween('expenses.expense_date', [$start_date, $end_date])
                                ->get();

            $data           = Expenses::join('expenses_categories','expenses.expense_category','expenses_categories.id')->select('name',\DB::raw('SUM(expense_total_amount) as expense_total_amount'))
                                ->whereBetween('expenses.expense_date', [$start_date, $end_date])
                                ->whereIn('expense_category',$selectExpenses);
            $datas = $data->get();
            if(count($datas) > 0) {
              foreach ($datas as $key => $value) {
                $datas[$key]->expense_total_amount = setting('default_currency').number_format($value->expense_total_amount,2,'.','');
              }
            }                      
            return Datatables::of($datas)->make(true);
        }
    }

    public function partyWiseOutstanding(Request $request) {
        $start_date    = $request->start_date;
        $end_date      = $request->end_date;
        if($request->ajax()) {

            $datas = DB::table('markets')
                                  ->leftJoin('transaction_track','markets.id','=','transaction_track.transaction_track_market_id')
                                  ->leftJoin('customer_groups','markets.customer_group','=','customer_groups.id')
                                  ->select('markets.*','customer_groups.name as party_group',\DB::raw('count(transaction_track.id) as total_no_transactions'),\DB::raw('max(transaction_track.transaction_track_date) as last_transaction'))
                                  ->groupBy('markets.id')->get();
            foreach ($datas as $key => $value) {
                $datas[$key]->balance = setting('default_currency').number_format($datas[$key]->balance,2,'.',''); 
            }                      
            return Datatables::of($datas)->make(true);
        }
    }

    public function partyReportbyItem(Request $request) {
        $start_date    = $request->start_date;
        $end_date      = $request->end_date;
        $product       = $request->product;
        if($request->ajax()) {

            $datas = $this->marketRepository->get();
            foreach ($datas as $key => $value) {
                $datas[$key]->market_url = route('markets.view',$datas[$key]->id);    
                $sales_quantity = DB::table('sales_invoice')
                                  ->join('sales_invoice_detail','sales_invoice.id','=','sales_invoice_detail.sales_invoice_id')
                                  ->where('sales_invoice.market_id',$value->id)
                                  ->where('sales_invoice_detail.sales_detail_product_id',$product)
                                  ->whereBetween('sales_invoice.sales_date', [$start_date, $end_date])
                                  ->sum('sales_detail_quantity');

                $sales_invoice   = DB::table('sales_invoice')
                                  ->join('sales_invoice_detail','sales_invoice.id','=','sales_invoice_detail.sales_invoice_id')
                                  ->where('sales_invoice.market_id',$value->id)
                                  ->where('sales_invoice_detail.sales_detail_product_id',$product)
                                  ->whereBetween('sales_invoice.sales_date', [$start_date, $end_date])
                                  ->get();

                $sales_invoice_amount[] = 0;                                 
                if(count($sales_invoice) > 0) {
                  foreach ($sales_invoice as $key1 => $value1) {
                    $sales_invoice_amount[]= $value1->sales_detail_quantity * $value1->sales_detail_price;
                  }
                }

                $purchase_quantity = DB::table('purchase_invoice')
                                    ->join('purchase_invoice_detail','purchase_invoice.id','=','purchase_invoice_detail.purchase_invoice_id')
                                    ->where('purchase_invoice.market_id',$value->id)
                                    ->where('purchase_invoice_detail.purchase_detail_product_id',$product)
                                    ->whereBetween('purchase_invoice.purchase_date', [$start_date, $end_date])
                                    ->sum('purchase_detail_quantity');

                $purchase_invoice   = DB::table('purchase_invoice')
                                    ->join('purchase_invoice_detail','purchase_invoice.id','=','purchase_invoice_detail.purchase_invoice_id')
                                    ->where('purchase_invoice.market_id',$value->id)
                                    ->where('purchase_invoice_detail.purchase_detail_product_id',$product)
                                    ->whereBetween('purchase_invoice.purchase_date', [$start_date, $end_date])
                                    ->get();
                                  
                $purchase_invoice_amount[] = 0;                                 
                if(count($purchase_invoice) > 0) {
                  foreach ($purchase_invoice as $key1 => $value1) {
                    $purchase_invoice_amount[]= $value1->purchase_detail_quantity * $value1->purchase_detail_price;
                  }
                }                  

                $datas[$key]->sales_quantity    = $sales_quantity;
                $datas[$key]->sales_amount      = number_format(array_sum($sales_invoice_amount),2,'.','');
                $datas[$key]->purchase_quantity = $purchase_quantity;
                $datas[$key]->purchase_amount   = number_format(array_sum($purchase_invoice_amount),2,'.','');
                unset($sales_invoice_amount);
                unset($purchase_invoice_amount);
                if($datas[$key]->purchase_amount <= 0 && $datas[$key]->sales_amount <= 0) {
                    unset($datas[$key]);   
                }
            }
            $product = $this->productRepository->findWithoutFail($product)->name;

            return Datatables::of($datas)->make(true);
        }
    }

    public function billWiseProfit(Request $request) {
        $start_date    = $request->start_date;
        $end_date      = $request->end_date;
        if($request->ajax()) {

            $sales_invoice = DB::table('sales_invoice')
                             ->whereBetween('sales_invoice.sales_date', [$start_date, $end_date]);
            $datas         = $sales_invoice->get();
            foreach ($datas as $key => $value) {
                $datas[$key]->market_id          = $this->marketRepository->findWithoutFail($value->market_id)->name;
                

                $sales_detail     = DB::table('sales_invoice_detail')->where('sales_invoice_id',$value->id)->get();
                $total_purchase[] = 0;
                if(count($sales_detail) > 0) {
                    foreach ($sales_detail as $key1 => $value1) {
                        $product          = $this->productRepository->findWithoutFail($value1->sales_detail_product_id);
                        $total_purchase[] = $product->purchase_price * $value1->sales_detail_quantity;
                    }
                }
                $datas[$key]->purchase_amount    = setting('default_currency').number_format(array_sum($total_purchase),2,'.','');
                $datas[$key]->profit_amount      = setting('default_currency').number_format($value->sales_total_amount - array_sum($total_purchase),2,'.','');
                $datas[$key]->sales_total_amount = setting('default_currency').number_format($value->sales_total_amount,2,'.','');
                unset($total_purchase);
            }                   

            return Datatables::of($datas)->make(true);
        }
    }

    public function itemReportbyParty(Request $request) {
        $start_date    = $request->start_date;
        $end_date      = $request->end_date;
        $market        = $request->market;

        if($request->ajax()) {

            $datas = $this->productRepository->get();
            foreach ($datas as $key => $value) {

                $sales_quantity = DB::table('sales_invoice')
                                  ->join('sales_invoice_detail','sales_invoice.id','=','sales_invoice_detail.sales_invoice_id')
                                  ->where('sales_invoice_detail.sales_detail_product_id',$value->id)
                                  ->where('sales_invoice.market_id',$market)
                                  ->whereBetween('sales_invoice.sales_date', [$start_date, $end_date])
                                  ->sum('sales_detail_quantity');

                $sales_invoice   = DB::table('sales_invoice')
                                  ->join('sales_invoice_detail','sales_invoice.id','=','sales_invoice_detail.sales_invoice_id')
                                  ->where('sales_invoice_detail.sales_detail_product_id',$value->id)
                                  ->where('sales_invoice.market_id',$market)
                                  ->whereBetween('sales_invoice.sales_date', [$start_date, $end_date])
                                  ->get();

                $sales_invoice_amount[] = 0;                                 
                if(count($sales_invoice) > 0) {
                  foreach ($sales_invoice as $key1 => $value1) {
                    $sales_invoice_amount[]= $value1->sales_detail_quantity * $value1->sales_detail_price;
                  }
                }

                $purchase_quantity = DB::table('purchase_invoice')
                                    ->join('purchase_invoice_detail','purchase_invoice.id','=','purchase_invoice_detail.purchase_invoice_id')
                                    ->where('purchase_invoice.market_id',$market)
                                    ->where('purchase_invoice_detail.purchase_detail_product_id',$value->id)
                                    ->whereBetween('purchase_invoice.purchase_date', [$start_date, $end_date])
                                    ->sum('purchase_detail_quantity');

                $purchase_invoice   = DB::table('purchase_invoice')
                                    ->join('purchase_invoice_detail','purchase_invoice.id','=','purchase_invoice_detail.purchase_invoice_id')
                                    ->where('purchase_invoice.market_id',$market)
                                    ->where('purchase_invoice_detail.purchase_detail_product_id',$value->id)
                                    ->whereBetween('purchase_invoice.purchase_date', [$start_date, $end_date])
                                    ->get();
                                  
                $purchase_invoice_amount[] = 0;                                 
                if(count($purchase_invoice) > 0) {
                  foreach ($purchase_invoice as $key1 => $value1) {
                    $purchase_invoice_amount[]= $value1->purchase_detail_quantity * $value1->purchase_detail_price;
                  }
                }                  

                $datas[$key]->sales_quantity    = $sales_quantity;
                $datas[$key]->sales_amount      = setting('default_currency').number_format(array_sum($sales_invoice_amount),2,'.','');
                $datas[$key]->purchase_quantity = $purchase_quantity;
                $datas[$key]->purchase_amount   = setting('default_currency').number_format(array_sum($purchase_invoice_amount),2,'.','');
                unset($sales_invoice_amount);
                unset($purchase_invoice_amount);
            }

            return Datatables::of($datas)->make(true);
        }  

    }
    
    public function productsReport(Request $request) {


        $start_date          = $request->start_date;
        $end_date            = $request->end_date;
        $product_id            = $request->product;
        
         if($request->ajax()) {

            //Online Orders
            $data    = DB::table('products')
                            ->leftJoin('product_orders', 'product_orders.product_id','=','products.id')
                            ->select(
                                'products.name',
                                'products.bar_code',
                                'products.hsn_code',
                                'products.purchase_price',
                                'products.stock',
                                'products.unit',
                                DB::raw('(products.stock * products.purchase_price) as stockvalue'),
                                'product_orders.quantity as order_quantity',
                                'product_orders.order_id as order_id',
                                'product_orders.price',
                                \DB::raw('Date(product_orders.created_at) AS ordered_date')
                            )->where('order_id','!=','NULL');
            if($product_id!=0) {
                $data->where('products.id',$product_id);
            }   
        
            if($start_date!='' & $end_date!='') {
                $data->whereDate('product_orders.created_at','>=',$start_date)->whereDate('product_orders.created_at','<=',$end_date);
            } 
            $items = $data->get();
            //Online Orders

            //Sales Invoices
            $data1    = DB::table('products')
                            ->leftJoin('sales_invoice_detail', 'sales_invoice_detail.sales_detail_product_id','=','products.id')
                            ->select(
                                'products.name',
                                'products.bar_code',
                                'products.hsn_code',
                                'products.purchase_price',
                                'products.stock',
                                'products.unit',
                                DB::raw('(products.stock * products.purchase_price) as stockvalue'),
                                'sales_invoice_detail.sales_detail_quantity as order_quantity',
                                'sales_invoice_detail.sales_invoice_id as order_id',
                                'sales_invoice_detail.sales_detail_amount as price',
                                'sales_invoice_detail.sales_detail_unit as unit',
                                \DB::raw('Date(sales_invoice_detail.created_at) AS ordered_date')
                            )->where('sales_invoice_id','!=','NULL');
            if($product_id!=0) {
                $data1->where('products.id',$product_id);
            } 
            if($start_date!='' & $end_date!='') {
                $data1->whereDate('sales_invoice_detail.created_at','>=',$start_date)->whereDate('sales_invoice_detail.created_at','<=',$end_date);
            }   
            $items1 = $data1->get();
            //Sales Invoices
                          
        $sales_data = array_merge($items->toArray(), $items1->toArray());
        
        if(count($sales_data)>0){
            foreach ($sales_data as $key => $value) {

                $sales_data[$key]->purchasing_price = setting('default_currency').''.number_format($sales_data[$key]->purchase_price,2, '.', '');
                $sales_data[$key]->selling_price    = setting('default_currency').''.number_format($sales_data[$key]->price,2, '.', '');
                $sales_data[$key]->stock_quantity   = $sales_data[$key]->stock.' '.$sales_data[$key]->unit;
                $sales_data[$key]->stock_value      = setting('default_currency').''.number_format($sales_data[$key]->stock * $sales_data[$key]->purchase_price,2, '.', '');
                $stocks[]                           = $sales_data[$key]->stock_value;
                $sales_data[$key]->price            = setting('default_currency').''.number_format($sales_data[$key]->price,2, '.', '');

                //$sales_data[$key]->order_id         =        
                if($sales_data[$key]->order_quantity==''){
                    $sales_data[$key]->ordered_quantity   = '';
                } else {
                    $sales_data[$key]->ordered_quantity   = $sales_data[$key]->order_quantity.' '.$sales_data[$key]->unit;
                }
            }  
            $stock_value = setting('default_currency').''.number_format(array_sum($stocks),2, '.', '');
        }

        return Datatables::of($sales_data)->make(true);
        }
    }
    
    public function productsHistoryReport(Request $request) {
        $product_id          = $request->product;
        $start_date          = $request->start_date;
        $end_date            = $request->end_date;
        
        if($request->ajax()) {
            $data = InventoryTrack::where('inventory_track_product_id',$product_id);
            if($start_date!='' & $end_date!='') {
                $data->whereDate('inventory_track_date','>=',$start_date)->whereDate('inventory_track_date','<=',$end_date);
            }
            $datas = $data->get();
            $product = $this->productRepository->findWithoutFail($product_id);
            $stocks[] = 0;
            for($i=0; $i<count($datas); $i++) {
               
               switch($datas[$i]->inventory_track_category){
                   
                   case 'opening_stock':
                       
                       $datas[$i]->purchase_no     = 'Opening Stock';
                       $datas[$i]->purchase_amount = '';
                       $datas[$i]->transaction_url = '';
                       
                   break;
                   
                   case 'added_stock':
                       
                       $datas[$i]->purchase_no     = 'Manualy Added Stock';
                       $datas[$i]->purchase_amount = '';
                       $datas[$i]->transaction_url = '';
                       
                   break;
                   
                   case 'sales_stock':
                       
                       $sales_details = DB::table('sales_invoice_detail')
                                                ->join('sales_invoice','sales_invoice.id','=','sales_invoice_detail.sales_invoice_id')
                                                ->where('sales_invoice_detail.id',$datas[$i]->purchase_invoice_id)->first();
                       $datas[$i]->purchase_no     = $sales_details->sales_code;
                       $datas[$i]->purchase_amount = $sales_details->sales_detail_amount;
                       $datas[$i]->transaction_url = route('salesInvoice.show',$sales_details->id);
                       
                   break;
                   
                   case 'purchase_stock':
                       
                       $purchase_details = DB::table('purchase_invoice_detail')
                                                ->join('purchase_invoice','purchase_invoice.id','=','purchase_invoice_detail.purchase_invoice_id')
                                                ->where('purchase_invoice_detail.id',$datas[$i]->purchase_invoice_id)->first();
                       $datas[$i]->purchase_no     = $purchase_details->purchase_code;
                       $datas[$i]->purchase_amount = $purchase_details->purchase_detail_amount;
                       $datas[$i]->transaction_url = route('purchaseInvoice.show',$purchase_details->id);
                       
                   break;
                   
                   case 'sales_return_stock':
                       
                       $sales_details = DB::table('sales_return_detail')
                                                ->join('sales_return','sales_return.id','=','sales_return_detail.sales_return_id')
                                                ->where('sales_return_detail.id',$datas[$i]->purchase_invoice_id)->first();
                       $datas[$i]->purchase_no     = $sales_details->sales_code;
                       $datas[$i]->purchase_amount = $sales_details->sales_detail_amount;
                       $datas[$i]->transaction_url = route('salesReturn.show',$sales_details->id);
                       
                   break;
                   
                   case 'purchase_return_stock':
                       
                       $purchase_details = DB::table('purchase_return_detail')
                                                ->join('purchase_return','purchase_return.id','=','purchase_return_detail.purchase_return_id')
                                                ->where('purchase_return_detail.id',$datas[$i]->purchase_invoice_id)->first();
                       $datas[$i]->purchase_no     = $purchase_details->purchase_code;
                       $datas[$i]->purchase_amount = $purchase_details->purchase_detail_amount;
                       $datas[$i]->transaction_url = route('purchaseReturn.show',$purchase_details->id);
                       
                   break;
                   
                   case 'online_stock':
                       
                       $online_order = DB::table('product_orders')
                                                ->join('orders','orders.id','=','product_orders.order_id')
                                                ->where('orders.id',$datas[$i]->purchase_invoice_id)->first();
                       $datas[$i]->purchase_no     = $online_order->id;
                       $datas[$i]->purchase_amount = $online_order->order_amount;
                       $datas[$i]->transaction_url = route('orders.show',$online_order->id);
                       
                   break;
                   
                   case 'online_return_stock':
                       
                   break;
                   
               }
               
            }
            return Datatables::of($datas)->make(true);
        }
    }

    public function popularProductsReport(Request $request) {


        $start_date          = $request->start_date;
        $end_date            = $request->end_date;
         if($request->ajax()) {

          $sales_data = DB::select('SELECT id, name,bar_code,hsn_code,purchase_price,price,stock,unit,stockvalue, SUM(total_quantity) AS ordered_quantity FROM(SELECT a.id,a.name,a.bar_code,a.hsn_code,a.purchase_price,a.price,a.stock,a.unit,a.stock*a.purchase_price as stockvalue, SUM(b.quantity) AS total_quantity
FROM products a JOIN product_orders b ON b.product_id = a.id GROUP BY a.id, b.product_id UNION SELECT a.id,a.name,a.bar_code,a.hsn_code,a.purchase_price,a.price,a.stock,a.unit,a.stock*a.purchase_price as stockvalue, SUM(c.sales_detail_quantity) AS total_quantity
FROM products a JOIN sales_invoice_detail c ON c.sales_detail_product_id = a.id GROUP BY a.id, c.sales_detail_product_id
) temp GROUP BY id order by total_quantity desc');

        if(count($sales_data)>0){
        foreach ($sales_data as $key => $value) {
                $sales_data[$key]->purchasing_price = setting('default_currency').''.number_format($sales_data[$key]->purchase_price,2, '.', '');
                $sales_data[$key]->selling_price    = setting('default_currency').''.number_format($sales_data[$key]->price,2, '.', '');
                $sales_data[$key]->stock_quantity   = $sales_data[$key]->stock.' '.$sales_data[$key]->unit;
                $sales_data[$key]->stock_value      = setting('default_currency').''.number_format($sales_data[$key]->stock * $sales_data[$key]->purchase_price,2, '.', '');
                $stocks[]                      = $sales_data[$key]->stock_value;
            }
           
        $stock_value                       = setting('default_currency').''.number_format(array_sum($stocks),2, '.', '');
        }

        return Datatables::of($sales_data)->make(true);
        }
    }


    public function profitableProductsReport(Request $request) {


        $start_date          = $request->start_date;
        $end_date            = $request->end_date;
         if($request->ajax()) {

            $sales_data = DB::select('SELECT id,name,bar_code,hsn_code,purchase_price,price,stock,unit,stockvalue, SUM(total_quantity) AS ordered_quantity FROM(SELECT a.id,a.name,a.bar_code,a.hsn_code,a.purchase_price,a.price,a.stock,a.unit,a.stock*a.purchase_price as stockvalue, SUM(b.quantity) AS total_quantity
FROM products a JOIN product_orders b ON b.product_id = a.id GROUP BY a.id, b.product_id UNION SELECT a.id,a.name,a.bar_code,a.hsn_code,a.purchase_price,a.price,a.stock,a.unit,a.stock*a.purchase_price as stockvalue, SUM(c.sales_detail_quantity) AS total_quantity
FROM products a JOIN sales_invoice_detail c ON c.sales_detail_product_id = a.id GROUP BY a.id, c.sales_detail_product_id
) temp where purchase_price<price GROUP BY id order by total_quantity desc');

        if(count($sales_data)>0){
        foreach ($sales_data as $key => $value) {
                $sales_data[$key]->purchasing_price = setting('default_currency').''.number_format($sales_data[$key]->purchase_price,2, '.', '');
                $sales_data[$key]->selling_price    = setting('default_currency').''.number_format($sales_data[$key]->price,2, '.', '');
                $sales_data[$key]->stock_quantity   = $sales_data[$key]->stock.' '.$sales_data[$key]->unit;
                $sales_data[$key]->stock_value      = setting('default_currency').''.number_format($sales_data[$key]->stock * $sales_data[$key]->purchase_price,2, '.', '');
                $stocks[]                      = $sales_data[$key]->stock_value;
                
                  $profit = $value->price - $value->purchase_price;
                 if($value->purchase_price>0){
                 $profit_percentage = ($profit*100)/$value->purchase_price;
                 }else{ $profit_percentage =100; }
                 if($profit_percentage>0){ $profit_percent = $profit_percentage; } else{ $profit_percent = 0; }
                  $sales_data[$key]->profit_percentage   = number_format($profit_percent,2).'%';
            }
           
        $stock_value                       = setting('default_currency').''.number_format(array_sum($stocks),2, '.', '');
        }

        return Datatables::of($sales_data)->make(true);
        }
    }
    
     public function customersReport(Request $request) {
        $start_date    = $request->start_date;
        $end_date      = $request->end_date;
      
        if($request->ajax()) {
            
                            $data =  DB::table('markets')
                                  ->leftJoin('transaction_track','markets.id','=','transaction_track.transaction_track_market_id')
                                  ->leftJoin('customer_groups','markets.customer_group','=','customer_groups.id')
                                  ->leftJoin('user_markets', 'markets.id','=','user_markets.market_id')
                                  ->leftJoin('users', 'user_markets.user_id','=','users.id')
                                  ->leftJoin('customer_levels', 'users.level','=','customer_levels.id')
                                  ->select('markets.*','customer_groups.name as party_group',\DB::raw('count(transaction_track.id) as total_no_transactions'),'customer_levels.name as levels','customer_levels.monthly_spend as reward_points')
                                  ->groupBy('markets.id')->where('markets.type',1);
                         
              if($start_date!='' & $end_date!='') {
            $data->whereDate('markets.created_at','>=',$start_date)->whereDate('markets.created_at','<=',$end_date);
              }

           $datas = $data->get();
           
            foreach ($datas as $key => $value) {
                $datas[$key]->address = $datas[$key]->address_line_1.' '.$datas[$key]->address_line_2;
                $datas[$key]->reward_levels = $datas[$key]->levels.' - '.$datas[$key]->reward_points;
                $datas[$key]->created_at = date('d-m-Y',strtotime($datas[$key]->created_at));
            }
                             
            return Datatables::of($datas)->make(true);
        }
    }

     public function staffLoginReport(Request $request) {

        $start_date          = $request->start_date;
        $end_date            = $request->end_date;
        $user_id             = $request->users;
        $user_role           = $request->roles;
        
         if($request->ajax()) {
             
            if($user_role!='')
            {
                if($user_role=="admin")
                {
                 $data    = User::whereHas('roles', function($q){$q->where('name','=','admin');})->join('user_log', 'users.id','=','user_log.user_id')->select('users.*','user_log.created_at as created','user_log.updated_at as updated');
                }
                else if($user_role=="manager")
                {
                   $data    = User::whereHas('roles', function($q){$q->where('name','=','manager');})->join('user_log', 'users.id','=','user_log.user_id')->select('users.*','user_log.created_at as created','user_log.updated_at as updated');
                }
                else
                {
                   $data    = User::whereHas('roles', function($q){$q->where('name','=','driver');})->join('user_log', 'users.id','=','user_log.user_id')->select('users.*','user_log.created_at as created','user_log.updated_at as updated');  
                }
            }
            else
            {
                $data    = User::whereHas('roles', function($q){$q->where('name','!=','client');})->join('user_log', 'users.id','=','user_log.user_id')->select('users.*','user_log.created_at as created','user_log.updated_at as updated');   
            }
            
        if($start_date!='' & $end_date!='') {
           // $data->whereBetween('user_log.created_at', [$start_date, $end_date]);
            $data->whereDate('user_log.created_at','>=',$start_date)->whereDate('user_log.created_at','<=',$end_date);
        }  
        if($user_id!=0) {
            $data->where('users.id',$user_id);
        }  
        $datas = $data->get();
           
        return Datatables::of($datas)->make(true);
        }
    }

    public function stockPurchaseReport(Request $request) {
      
        $start_date          = $request->start_date;
        $end_date            = $request->end_date;
         if($request->ajax()) {

           $data = DB::select('SELECT id, name,bar_code,hsn_code,purchase_price,price,stock,unit,stockvalue, SUM(total_quantity) AS ordered_quantity FROM(SELECT a.id,a.name,a.bar_code,a.hsn_code,a.purchase_price,a.price,a.stock,a.unit,a.stock*a.purchase_price as stockvalue, SUM(b.quantity) AS total_quantity
FROM products a JOIN product_orders b ON b.product_id = a.id GROUP BY a.id, b.product_id UNION SELECT a.id,a.name,a.bar_code,a.hsn_code,a.purchase_price,a.price,a.stock,a.unit,a.stock*a.purchase_price as stockvalue, SUM(c.sales_detail_quantity) AS total_quantity
FROM products a JOIN sales_invoice_detail c ON c.sales_detail_product_id = a.id GROUP BY a.id, c.sales_detail_product_id
) temp GROUP BY id order by total_quantity asc');



       $arr=[];
       foreach ($data as $key => $value) {
          $items[] = $value;
           $arr[]=$value->id;
            unset($value->id);
       }

          $data1    =  DB::select('SELECT id, name,bar_code,hsn_code,purchase_price,price,stock,unit,stock*purchase_price as stockvalue,0 AS ordered_quantity FROM products where id NOT IN (' . implode(',', $arr) . ')');
      
       if(count($data1)>0){
      foreach ($data1 as $key => $value1) {
           $item[] = $value1;
           
           unset($value->id);
      }
     $data2 =  array_merge($item,$items);
      }else
      {
        $data2 =   $items;
      }
     $sales_data = $data2;//array_slice($data2, 0, 10);
         if(count($sales_data)>0){
        foreach ($sales_data as $key => $value) {
                $sales_data[$key]->purchasing_price = setting('default_currency').''.number_format($sales_data[$key]->purchase_price,2, '.', '');
                $sales_data[$key]->selling_price    = setting('default_currency').''.number_format($sales_data[$key]->price,2, '.', '');
                $sales_data[$key]->stock_quantity   = number_format($sales_data[$key]->stock,2).' '.$sales_data[$key]->unit;
                $sales_data[$key]->stock_value      = setting('default_currency').''.number_format($sales_data[$key]->stock * $sales_data[$key]->purchase_price,2, '.', '');
                $stocks[]                      = $sales_data[$key]->stock_value;
                   
                if($sales_data[$key]->ordered_quantity==''){
                 $sales_data[$key]->ordered_quantity   = '';
                }else{
                  $sales_data[$key]->ordered_quantity   = $sales_data[$key]->ordered_quantity.' '.$sales_data[$key]->unit;
                }
            }
           
        $stock_value                       = setting('default_currency').''.number_format(array_sum($stocks),2, '.', '');
        
         }

        return Datatables::of(collect($sales_data))->make(true);
        }
    }
    
     public function deliveryReport(Request $request) {
       
        if($request->ajax()) {

            $datas = DB::table('orders')
                ->leftJoin('delivery_addresses', 'delivery_addresses.id','=','orders.delivery_address_id')
                ->leftJoin('users', 'users.id','=','orders.user_id')
                //->leftJoin('payments', 'payments.id','=','orders.payment_id')
                ->leftJoin('order_statuses', 'order_statuses.id','=','orders.order_status_id')
                ->select('delivery_addresses.*','users.name','orders.delivery_distance','orders.driver_id','orders.id as order_id','payments.method','payments.status as payment_status','order_statuses.status as order_status')
                ->get();

            foreach ($datas as $key => $value) {
               
               if($value->address_line_1!=''){
                    $datas[$key]->address = $value->address_line_1.', '.$value->address_line_2.', '.$value->city.', '.$value->state.'-'.$value->pincode;
               } else
               {
                    $datas[$key]->address = '';
               }
               
               if($value->driver_id!=''){
                    $get_driver = DB::table('users')->select('name')->where('id',$value->driver_id)->first();
                    $datas[$key]->delivered_by = $get_driver->name;
               } else
               {
                    $datas[$key]->delivered_by ='';
               }
               
               $datas[$key]->transaction_number ='';
               
            }
        
        return response()->json(['data' => $datas]);

        //return Datatables::of($datas)->make(true);
        
        }
    }
    
    public function wastageReport(Request $request) {

        $product_id          = $request->product;
        $start_date          = $request->start_date;
        $end_date            = $request->end_date;

        if($request->ajax()) {

            $data = InventoryTrack::where('usage','wastage');
            if($product_id!='' && $product_id!=0) {
                $data->where('product_id',$product_id);
            }

            if($start_date!='' & $end_date!='') {
                $data->whereBetween('inventory_track.date', [$start_date, $end_date]);
            } 

            $datas = $data->get();
            $stocks[] = 0;
             for($i=0; $i<count($datas); $i++) {
               
               $product = $this->productRepository->findWithoutFail($datas[$i]->product_id);
               
               if($datas[$i]->inventory_track_type=='add') {
                    $stock = array_sum($stocks) + $datas[$i]->quantity;
               } else if($datas[$i]->inventory_track_type=='reduce') {
                    $stock = array_sum($stocks) - $datas[$i]->quantity;
               }
               $datas[$i]->inventory_closing_stock  = $stock.' '.$product->unit; 
               $datas[$i]->inventory_track_category = ucfirst(str_replace("_"," ",$datas[$i]->category));
               //($datas[$i]->inventory_track_type=='add') ? $operator='' : $operator='-';
               $datas[$i]->quantity                 = $datas[$i]->quantity.' '.$datas[$i]->unit;
               $datas[$i]->inventory_track_product  = $product->name;
                $datas[$i]->item_code  = $product->bar_code; 
                $datas[$i]->hsn_code  = $product->hsn_code; 
                 $datas[$i]->inventory_track_description  = $datas[$i]->description; 
               $datas[$i]->cost                     = setting('default_currency').number_format($datas[$i]->quantity * $product->purchase_price,'2','.','');
               unset($stocks);
               $stocks[] = $stock;
            } 

            dd($datas->reverse());
            
            return Datatables::of($datas)->make(true);

        }




    }


    public function exportReport(Request $request) {
        $type           = $request->type;
        $report_type    = $request->report_type;

        if($report_type=='product-party-stock-report'): 
    
            $product_id          = $request->product_id;
            $start_date          = $request->start_date;
            $end_date            = $request->end_date;
            $type                = $request->type;
            
            $product             = $this->productRepository->findWithoutFail($product_id);
            $query               = $this->marketRepository->where('id','>',0);

            $data                = $query->orderBy('id','asc')->get();
            foreach($data as $key => $value) {
                $data[$key]->sales_quantity    = $this->partyPurchaseQuantity($product->id,$value->id,$start_date,$end_date,'reduce','sales','online');
                $data[$key]->purchase_quantity = $this->partyPurchaseQuantity($product->id,$value->id,$start_date,$end_date,'add','purchase','');
                $data[$key]->sales_amount      = $this->partyPurchaseAmount($product->id,$value->id,$start_date,$end_date,'sales');
                $data[$key]->purchase_amount   = $this->partyPurchaseAmount($product->id,$value->id,$start_date,$end_date,'purchase');
            }

            $dataTable = Datatables::of($data);
            $dataTable->addIndexColumn();

            $table  = $dataTable    
                        ->addColumn('name', function($market){
                            return $market->name;
                        })
                        ->addColumn('sales_quantity', function($market) use ($product) {
                            return number_format($market->sales_quantity,'3','.','').' '.$product->primaryunit->name;
                        })
                        ->addColumn('purchase_quantity', function($market) use ($product) {
                            return number_format($market->purchase_quantity,'3','.','').' '.$product->primaryunit->name;
                        })  
                    //->rawColumns(['date','transaction_no','status'])
                    ->make(true);

            if(isset($type)) {

                $datas = $table->getData()->data;
                switch ($type) {
                    case 'export':
                        //return Excel::download(new WastageExport($start_date,$end_date,$product_id), 'wastage_report.xlsx');
                    break;

                    case 'print':
                        $pdf = PDF::loadView('reports.export.product_party_stock_report', compact('datas','start_date','end_date','product'));
                        $filename = setting('app_name').'.pdf';
                        return $pdf->stream($filename);
                    break;

                    case 'download':
                        $pdf = PDF::loadView('reports.export.product_party_stock_report', compact('datas','start_date','end_date','product'));
                        $filename = setting('app_name').'.pdf';
                        return $pdf->download($filename);
                    break;                    
                }

            } else {
                return $table;
            }

        endif;

        
        if($report_type=='sales-summary') :

            $start_date     = $request->start_date;
            $end_date       = $request->end_date;

            $data    = DB::table('sales_invoice')
                            ->join('markets', 'sales_invoice.market_id','=','markets.id')
                            ->leftJoin('customer_groups', 'markets.customer_group_id','=','customer_groups.id')
                            //->leftJoin('user_markets', 'markets.id','=','user_markets.market_id')
                            ->leftJoin('users', 'markets.user_id','=','users.id')
                            ->leftJoin('customer_levels', 'users.level','=','customer_levels.id')
                            ->leftJoin('sales_invoice_items', 'sales_invoice.id','=','sales_invoice_items.sales_invoice_id')
                            ->leftJoin('payment_mode', 'sales_invoice.payment_method','=','payment_mode.id')
                            ->select('sales_invoice.*','markets.name as market_id','customer_groups.name as party_group','customer_levels.name as reward_level',DB::raw('COUNT(sales_invoice_items.sales_invoice_id) as no_of_items'),'payment_mode.name as payment_type')
                             ->groupBy('sales_invoice_items.sales_invoice_id');

            if($start_date!='' & $end_date!='') {
                $data->whereBetween('sales_invoice.date', [$start_date, $end_date]);
            }                    
            $sales_data = $data->get();
            $total_transaction = DB::table('sales_invoice')->whereBetween('sales_invoice.date', [$start_date, $end_date])->sum('total');
            
            foreach ($sales_data as $key => $value) {
                $sales_data[$key]->date  = date('d M Y',strtotime($sales_data[$key]->date));
                  if($value->cash_paid >= $value->total) {
                         $sales_data[$key]->payment_status  = 'Paid';
                    } elseif($value->cash_paid > 0) {
                         $sales_data[$key]->payment_status  = 'Partially Paid';
                    } else {
                         $sales_data[$key]->payment_status  = 'Unpaid';
                    }
                $sales_data[$key]->total = number_format($sales_data[$key]->total,2);
            }

            //dd($sales_data);

            $dataTable = Datatables::of($sales_data);
            $dataTable->addIndexColumn();

            $table  = $dataTable    
                        ->addColumn('date', function() {
                            return $sales_data->date;
                        })
                        ->addColumn('code', function() {
                            return $sales_data->code;
                        })
                        ->addColumn('market_id', function() {
                            return $sales_data->market_id;
                        })
                        ->addColumn('party_group', function(){
                            return $sales_data->party_group;
                        })
                        ->addColumn('reward_level', function() {
                            return $sales_data->reward_level;
                        })
                        ->addColumn('no_of_items', function() {
                            return $sales_data->no_of_items;
                        }) 
                        ->addColumn('total', function(){
                            return $sales_data->total;
                        })
                        ->addColumn('payment_type', function() {
                            return $sales_data->payment_type;
                        }) 
                        ->addColumn('payment_status', function(){
                            return $sales_data->payment_status;
                        }) 
                    ->make(true);

            if(isset($type)) {

                $datas = $table->getData()->data;
                switch ($type) {
                    case 'export':
                    
                    // $pdf = PDF::loadView('reports.export.sales_summary_report', compact('sales_data','start_date','end_date','total_transaction'));
                    // $filename = setting('app_name').'.pdf';
                    // return $pdf->stream($filename);
                    return Excel::download(new SalesSummaryExport($start_date,$end_date), 'sales_summary.xlsx');
                    
                    break;

                    case 'print':
                        
                        $pdf = PDF::loadView('reports.export.sales_summary_report', compact('sales_data','start_date','end_date','total_transaction'));
                        $filename = setting('app_name').'.pdf';
                        return $pdf->stream($filename);
                    
                    break;

                    case 'download':
                        
                        $pdf = PDF::loadView('reports.export.sales_summary_report', compact('sales_data','start_date','end_date','total_transaction'));
                        $filename = setting('app_name').'.pdf';
                        return $pdf->download($filename);
                    
                    break;                   
                }

            } else {
                return $table;
            }

        endif;


        if($report_type=='party-statement') :

            $market_id      = $request->party;
            $start_date     = $request->start_date;
            $end_date       = $request->end_date;
            $market         = $this->marketRepository->findWithoutFail($market_id);

            $opening_credit = DB::table('transaction_track')
                            ->where('transaction_track_date','<',$start_date)
                            ->where('transaction_track_market_id',$market_id)
                            ->where('transaction_track_type','credit')
                            ->sum('transaction_track_amount');
        
            $opening_debit  = DB::table('transaction_track')
                                ->where('transaction_track_date','<',$start_date)
                                ->where('transaction_track_market_id',$market_id)
                                ->where('transaction_track_type','debit')
                                ->sum('transaction_track_amount');                    
            $opening_balance= $opening_credit - $opening_debit;                     
           
            $opening_data = array(
                'transaction_track_category' => 'Opening Balance',
                'credit'  => number_format($opening_credit,'2','.',''),
                'debit'   => number_format($opening_debit,'2','.',''),
                'balance' => abs($opening_balance)
            );
    
           $data = TransactionTrack::select(
                'transaction_track.transaction_number',
                'transaction_track.transaction_track_category'
            )->where('transaction_track_market_id',$market_id)->distinct();
            if($start_date!='' & $end_date!='') {
                $data->whereBetween('transaction_track.transaction_track_date', [$start_date, $end_date]);
            }
            $datas = $data->orderBy('transaction_track.created_at','asc')->get();
            
            $balance[] = $opening_balance;
           
            for($i=0; $i<count($datas); $i++) {
    
               if($datas[$i]->transaction_track_category=='purchase') {
                   $invoice = $this->purchaseInvoiceRepository->findWithoutFail($datas[$i]->transaction_number);
                   $datas[$i]->transaction_no          = $invoice->purchase_code;
                   $datas[$i]->transaction_track_date  = date('Y-m-d',strtotime($invoice->purchase_date));
    
                   $datas[$i]->credit   = number_format($invoice->purchase_total_amount,2,'.',''); 
                   $purchase_debit      = DB::table('transaction_track')
                                                ->where('transaction_number',$datas[$i]->transaction_number)
                                                ->where('transaction_track_category','purchase')
                                                ->where('transaction_track_type','debit')
                                                ->whereBetween('transaction_track.transaction_track_date', [$start_date, $end_date])
                                                ->sum('transaction_track_amount');
                   $datas[$i]->debit     = ($purchase_debit > 0) ? number_format($purchase_debit,2,'.',''):'';
                   $datas[$i]->transaction_url = route('purchaseInvoice.show',$datas[$i]->transaction_number); 
               }
    
               if($datas[$i]->transaction_track_category=='purchase_return') {
                   $invoice = $this->purchaseReturnRepository->findWithoutFail($datas[$i]->transaction_number);
                   $datas[$i]->transaction_no          = $invoice->purchase_code;
                   $datas[$i]->transaction_track_date  = date('Y-m-d',strtotime($invoice->purchase_date));
                   
                   $purchase_credit      = DB::table('transaction_track')
                                                ->where('transaction_number',$datas[$i]->transaction_number)
                                                ->where('transaction_track_category','purchase_return')
                                                ->where('transaction_track_type','credit')
                                                ->whereBetween('transaction_track.transaction_track_date', [$start_date, $end_date])
                                                ->sum('transaction_track_amount');
                   $datas[$i]->credit     = ($purchase_credit > 0) ? number_format($purchase_credit,2):'';
                   $datas[$i]->debit      = number_format($invoice->purchase_total_amount,2);
                   $datas[$i]->transaction_url = route('purchaseReturn.show',$datas[$i]->transaction_number);
               }
    
               if($datas[$i]->transaction_track_category=='sales') {
                   $invoice = $this->salesInvoiceRepository->findWithoutFail($datas[$i]->transaction_number);
                   $datas[$i]->transaction_no          = $invoice->sales_code;
                   $datas[$i]->transaction_track_date  = date('Y-m-d',strtotime($invoice->sales_date));
    
                   $sales_credit            = DB::table('transaction_track')
                                                    ->where('transaction_number',$datas[$i]->transaction_number)
                                                    ->where('transaction_track_category','sales')
                                                    ->where('transaction_track_type','credit')
                                                    ->whereBetween('transaction_track.transaction_track_date', [$start_date, $end_date])
                                                    ->sum('transaction_track_amount'); 
                   $datas[$i]->credit       = ($sales_credit > 0) ? number_format($sales_credit,2) :  '' ; 
                   $datas[$i]->debit        = number_format($invoice->sales_total_amount,2);
                   $datas[$i]->transaction_url = route('salesInvoice.show',$datas[$i]->transaction_number);
               }
    
    
               if($datas[$i]->transaction_track_category=='sales_return') {
                   $invoice = $this->salesReturnRepository->findWithoutFail($datas[$i]->transaction_number);
                   $datas[$i]->transaction_no          = $invoice->sales_code;
                   $datas[$i]->transaction_track_date  = date('Y-m-d',strtotime($invoice->sales_date));
                   
                   $datas[$i]->credit      = number_format($invoice->sales_total_amount,2);
                   $sales_debit            = DB::table('transaction_track')
                                                    ->where('transaction_number',$datas[$i]->transaction_number)
                                                    ->where('transaction_track_category','sales_return')
                                                    ->where('transaction_track_type','debit')
                                                    ->whereBetween('transaction_track.transaction_track_date', [$start_date, $end_date])
                                                    ->sum('transaction_track_amount'); 
                   $datas[$i]->debit       = ($sales_debit > 0) ? number_format($sales_debit,2) :  '' ; 
                   $datas[$i]->transaction_url = route('salesReturn.show',$datas[$i]->transaction_number);
               }
    
               if($datas[$i]->transaction_track_category=='payment_in') {
                   $invoice = $this->paymentInRepository->findWithoutFail($datas[$i]->transaction_number);
                   $datas[$i]->transaction_no          = $invoice->payment_in_no;
                   $datas[$i]->transaction_track_date  = date('Y-m-d',strtotime($invoice->payment_in_date)); 
                   $datas[$i]->credit                  = number_format($invoice->payment_in_amount,2); 
                   $datas[$i]->debit                   = '';
                   $datas[$i]->transaction_url = route('paymentIn.show',$datas[$i]->transaction_number);
               }
    
               if($datas[$i]->transaction_track_category=='payment_out') {
                   $invoice = $this->paymentOutRepository->findWithoutFail($datas[$i]->transaction_number);
                   $datas[$i]->transaction_no          = $invoice->payment_out_no;
                   $datas[$i]->transaction_track_date  = date('Y-m-d',strtotime($invoice->payment_out_date));
                   $datas[$i]->credit                  = '';
                   $datas[$i]->debit                   = number_format($invoice->payment_out_amount,2);
                   $datas[$i]->transaction_url = route('paymentOut.show',$datas[$i]->transaction_number);
               }
               
               $credit = ($datas[$i]->credit) ? $datas[$i]->credit :  0 ;
               $debit  = ($datas[$i]->debit) ? $datas[$i]->debit :  0 ;
               
               $openingBalance     = array_sum($balance);
               $newBalance         = $openingBalance + ((int)$credit - (int)$debit);
               unset($balance);
               $balance[]          = $newBalance;
               $datas[$i]->balance = abs($newBalance);
               
               $datas[$i]->transaction_track_category = ucfirst( str_replace('_', ' ', $datas[$i]->transaction_track_category) );
               
            }
            
            $closing_data = array(
                'transaction_track_category' => 'Closing Balance',
                'credit'  => '',
                'debit'   => '',
                'balance' => abs(array_sum($balance))
            );
            
            switch ($type) {

                case 'export':
                    
                    // $pdf = PDF::loadView('reports.export.party_statement_report', compact('datas','start_date','end_date','market'));
                    // $filename = setting('app_name').'.pdf';
                    // return $pdf->stream($filename);
                 return Excel::download(new PartyLedgerExport($start_date,$end_date,$market_id), 'party_ledger.xlsx');
                
                break;

                case 'print':
                    
                    $pdf = PDF::loadView('reports.export.party_statement_report', compact('datas','start_date','end_date','market','opening_data','closing_data'));
                    $filename = setting('app_name').'.pdf';
                    return $pdf->stream($filename);
                
                break;

                case 'download':
                    
                    $pdf = PDF::loadView('reports.export.party_statement_report', compact('datas','start_date','end_date','market','opening_data','closing_data'));
                    $filename = setting('app_name').'.pdf';
                    return $pdf->download($filename);
                
                break;
                
            }

        endif;


        if($report_type=='daybook') :

            $start_date     = $request->start_date;
            $end_date       = $request->end_date;

            $data = TransactionTrack::select(
                'transaction_track.id',
                'transaction_track.category',
                'transaction_track.market_id'
            )->distinct();
            if($start_date!='' & $end_date!='') {
                $data->whereBetween('transaction_track.date', [$start_date, $end_date]);
            }
            $datas = $data->get();

            for($i=0; $i<count($datas); $i++) {

           if($datas[$i]->transaction_track_category=='purchase') {
               $invoice = $this->purchaseInvoiceRepository->findWithoutFail($datas[$i]->id);
               $datas[$i]->transaction_track_date  = date('Y-m-d',strtotime($invoice->date));
               $datas[$i]->transaction_code        = $invoice->code;    
               $datas[$i]->credit   = setting('default_currency').''.number_format($invoice->total,2); 
               $purchase_debit      = DB::table('transaction_track')
                                            ->where('id',$datas[$i]->id)
                                            ->where('category','purchase')
                                            ->where('type','debit')
                                            ->whereBetween('transaction_track.date', [$start_date, $end_date])
                                            ->sum('transaction_track_amount');
               $datas[$i]->debit     = ($purchase_debit > 0) ? setting('default_currency').''.number_format($purchase_debit,2):'';

           }
           
           if($datas[$i]->transaction_track_category=='purchase_return') {
               $invoice = $this->purchaseReturnRepository->findWithoutFail($datas[$i]->id);
               $datas[$i]->transaction_track_date  = date('Y-m-d',strtotime($invoice->purchase_date));
               $datas[$i]->transaction_code        = $invoice->purchase_code;
               $purchase_credit      = DB::table('transaction_track')
                                            ->where('id',$datas[$i]->transaction_number)
                                            ->where('category','purchase_return')
                                            ->where('type','credit')
                                            ->whereBetween('transaction_track.date', [$start_date, $end_date])
                                            ->sum('transaction_track_amount');
               $datas[$i]->credit     = ($purchase_credit > 0) ? setting('default_currency').''.number_format($purchase_credit,2):'';
                $datas[$i]->debit   = setting('default_currency').''.number_format($invoice->purchase_total_amount,2); 

           }

           if($datas[$i]->transaction_track_category=='sales') {
               $invoice = $this->salesInvoiceRepository->findWithoutFail($datas[$i]->id);
               $datas[$i]->transaction_track_date  = date('Y-m-d',strtotime($invoice->date));
               $datas[$i]->transaction_code        = $invoice->sales_code;
               $sales_credit            = DB::table('transaction_track')
                                                ->where('id',$datas[$i]->id)
                                                ->where('category','sales')
                                                ->where('type','credit')
                                                // ->whereBetween('transaction_track.date', [$start_date, $end_date])
                                                ->sum('transaction_track_amount'); 
               $datas[$i]->credit       = ($sales_credit > 0) ? setting('default_currency').''.number_format($sales_credit,2) :  '' ; 
               $datas[$i]->debit        = setting('default_currency').''.number_format($invoice->sales_total_amount,2);
           }
           
           if($datas[$i]->transaction_track_category=='sales_return') {
               $invoice = $this->salesReturnRepository->findWithoutFail($datas[$i]->id);
               $datas[$i]->transaction_track_date  = date('Y-m-d',strtotime($invoice->date));
               $datas[$i]->transaction_code        = $invoice->sales_code;
               $sales_debit            = DB::table('transaction_track')
                                                ->where('id',$datas[$i]->id)
                                                ->where('category','sales_return')
                                                ->where('type','debit')
                                                // ->whereBetween('transaction_track.date', [$start_date, $end_date])
                                                ->sum('transaction_track_amount'); 
               $datas[$i]->credit      = setting('default_currency').''.number_format($invoice->sales_total_amount,2);
               $datas[$i]->debit       = ($sales_debit > 0) ? setting('default_currency').''.number_format($sales_debit,2) :  '' ; 
           }

           if($datas[$i]->transaction_track_category=='payment_in') {
               $invoice = $this->paymentInRepository->findWithoutFail($datas[$i]->id);
               $datas[$i]->transaction_code        = $invoice->payment_in_no;
               $datas[$i]->transaction_track_date  = date('Y-m-d',strtotime($invoice->created_at)); 
               $datas[$i]->credit                  = setting('default_currency').''.number_format($invoice->payment_in_amount,2); 
               $datas[$i]->debit                   = '';
           }

           if($datas[$i]->transaction_track_category=='payment_out') {
               $invoice = $this->paymentOutRepository->findWithoutFail($datas[$i]->id);
               $datas[$i]->transaction_code        = $invoice->payment_out_no;
               $datas[$i]->transaction_track_date  = date('Y-m-d',strtotime($invoice->created_at));
               $datas[$i]->credit                  = '';
               $datas[$i]->debit                   = setting('default_currency').''.number_format($invoice->payment_out_amount,2); 
               
           }

           $datas[$i]->transaction_track_category = ucfirst( str_replace('_', ' ', $datas[$i]->category) );
           $datas[$i]->party = $this->marketRepository->findWithoutFail($datas[$i]->market_id)->name;

        }

        //dd($datas);

        $dataTable = Datatables::of($datas);
        $dataTable->addIndexColumn();

        $table  = $dataTable    
                    ->addColumn('transaction_track_date', function() {
                        return $datas->transaction_track_date;
                    })
                    ->addColumn('party', function() {
                        return $datas->party;
                    })
                    ->addColumn('transaction_track_category', function() {
                        return $datas->transaction_track_category;
                    })
                    ->addColumn('transaction_code', function(){
                        return $datas->transaction_code;
                    })
                    ->addColumn('credit', function() {
                        return $datas->credit;
                    })
                    ->addColumn('debit', function() {
                        return $datas->debit;
                    }) 
                ->make(true);

        if(isset($type)) {

            //$datas = $table->getData()->data;
            switch ($type) {
                case 'export':
                    // $pdf = PDF::loadView('reports.export.daybook_report', compact('datas','start_date','end_date'));
                    // $filename = setting('app_name').'.pdf';
                    // return $pdf->stream($filename);
                    return Excel::download(new DaybookExport($start_date,$end_date), 'daybook.xlsx');
                break;

                case 'print':
                    $pdf = PDF::loadView('reports.export.daybook_report', compact('datas','start_date','end_date'));
                    $filename = setting('app_name').'.pdf';
                    return $pdf->stream($filename);
                break;

                case 'download':
                    $pdf = PDF::loadView('reports.export.daybook_report', compact('datas','start_date','end_date'));
                    $filename = setting('app_name').'.pdf';
                    return $pdf->download($filename);
                break;                    
            }

        } else {
            return $table;
        }

        endif;

        if($report_type=='stock-summary-report') :

            $start_date          = $request->start_date;
            $end_date            = $request->end_date;

            $data = DB::table('products'); //$this->productRepository->get();

            if($start_date!='' & $end_date!='') {
                $data->whereBetween('products.created_at', [$start_date, $end_date]);
            }                    
            $datas = $data->get();

            $stock_value  = 0;
            $stocks[] = 0;
            foreach ($datas as $key => $value) {
                $units = DB::table('uom')->where('id',$value->unit)->first();
                
                $datas[$key]->unit_name   = $units->name;
                $datas[$key]->purchasing_price = number_format($datas[$key]->purchase_price,2, '.', '');
                $datas[$key]->selling_price    = number_format($datas[$key]->price,2, '.', '');
                $datas[$key]->stock_quantity   = number_format($datas[$key]->stock,3, '.', '').' '.$datas[$key]->unit_name;
                $datas[$key]->stock_value      = number_format($datas[$key]->stock * $datas[$key]->purchase_price,2, '.', '');
                $stocks[]                      = $datas[$key]->stock_value;
                 $datas[$key]->created   = date("d M Y",strtotime($value->created_at));
                 $profit = $value->price - $value->purchase_price;
                 if($value->purchase_price>0){
                 $profit_percentage = ($profit*100)/$value->purchase_price;
                 }else{ $profit_percentage =100; }
                 if($profit_percentage>0){ $profit_percent = $profit_percentage; } else{ $profit_percent = 0; }
                  $datas[$key]->profit_percentage   = number_format($profit_percent,2).'%';
            }

            $stock_value  = number_format(array_sum($stocks),2, '.', '');

            $dataTable = Datatables::of($datas);
            $dataTable->addIndexColumn();

                $table  = $dataTable    
                            ->addColumn('created', function() {
                                return $datas->created;
                            })
                            ->addColumn('name', function() {
                                return $datas->name;
                            })
                            ->addColumn('product_code', function() {
                                return $datas->product_code;
                            })
                            ->addColumn('hsn_code', function(){
                                return $datas->hsn_code;
                            })
                            ->addColumn('purchasing_price', function() {
                                return $datas->purchasing_price;
                            })
                            ->addColumn('selling_price', function() {
                                return $datas->selling_price;
                            })
                            // ->addColumn('unit_name', function() {
                            //     return $datas->unit_name;
                            // })
                            ->addColumn('stock_quantity', function() {
                                return $datas->stock_quantity;
                            })
                            ->addColumn('stock_value', function(){
                                return $datas->stock_value;
                            })
                            ->addColumn('profit_percentage', function() {
                                return $datas->profit_percentage;
                            })
                        ->make(true);

            switch ($type) {

                case 'export':
                        //return Excel::download(new WastageExport($start_date,$end_date,$product_id), 'wastage_report.xlsx');
                    break;

                    case 'print':
                        $pdf = PDF::loadView('reports.export.stock_summary_report', compact('datas','start_date','end_date','stock_value'));
                        $filename = setting('app_name').'.pdf'; //reports.export.product_stock_report
                        return $pdf->stream($filename);
                    break;

                    case 'download':
                        $pdf = PDF::loadView('reports.export.stock_summary_report', compact('datas','start_date','end_date','stock_value'));
                        $filename = setting('app_name').'.pdf';
                        return $pdf->download($filename); //reports.export.product_stock_report
                    break; 
            }

        endif;


        if($report_type=='rate-list') :

            $datas = $this->productRepository->get();
            $customer_groups = DB::table('customer_groups')->get();
            foreach ($datas as $key => $value) {

                foreach($customer_groups as $customer_group) {
                  $price_variation = DB::table('product_group_price')->where('customer_group_id',$customer_group->id)->where('product_id',$datas[$key]->id)->get();
                  if(count($price_variation) > 0) {
                    $datas[$key]->{strtolower($customer_group->name)} = number_format($price_variation[0]->product_price,2, '.', '');  
                  } else {
                    $datas[$key]->{strtolower($customer_group->name)} = '';
                  }
                }
                $datas[$key]->purchasing_price = number_format($datas[$key]->purchase_price,2, '.', '');
                $datas[$key]->selling_price    = number_format($datas[$key]->price,2, '.', '');
            }

            switch ($type) {

                case 'export':
                    
                    return Excel::download(new RateListExport, 'rate_list.xlsx');
                
                break;

                case 'print':
                    
                    $pdf = PDF::loadView('reports.export.rate_list_report', compact('datas','customer_groups'));
                    $filename = setting('app_name').'.pdf';
                    return $pdf->stream($filename);
                
                break;

                case 'download':
                    
                    $pdf = PDF::loadView('reports.export.rate_list_report', compact('datas','customer_groups'));
                    $filename = setting('app_name').'.pdf';
                    return $pdf->download($filename);
                
                break;
                
            }

        endif;

        if($report_type=='item-sales-summary') :

            $start_date    = $request->start_date;
            $end_date      = $request->end_date;

            $data = DB::table('sales_invoice_items')
                      ->select('product_id','product_name','unit')
                      ->distinct();

            if($start_date!='' & $end_date!='') {
                $data->whereBetween('sales_invoice_items.created_at', [$start_date, $end_date]);
            }   

            $datas = $data->get();

            $total_sales_quantities = 0;
            $total_sales_amounts = 0;
            foreach ($datas as $key => $value) {
              $product            = $this->productRepository->findWithoutFail($datas[$key]->product_id);
              $units              = DB::table('uom')->where('id',$datas[$key]->unit)->first();
              $datas[$key]->name  = $product->name;
              $datas[$key]->unit  = $product->unit;
              $datas[$key]->total_sales = number_format(DB::table('sales_invoice_items')->where('product_id',$datas[$key]->product_id)->whereDate('sales_invoice_items.created_at', '>=', $start_date)->whereDate('sales_invoice_items.created_at', '<=', $end_date)->sum('quantity'),3, '.', '')." ".$units->name;  //->whereBetween('sales_invoice_items.created_at', [$start_date, $end_date])
              $datas[$key]->total_sales_amount = number_format(DB::table('sales_invoice_items')->where('product_id',$datas[$key]->product_id)->whereDate('sales_invoice_items.created_at', '>=', $start_date)->whereDate('sales_invoice_items.created_at', '<=', $end_date)->sum('amount'),2, '.', ''); //->whereBetween('sales_invoice_items.created_at', [$start_date, $end_date])

              $total_sales_quantities = number_format($total_sales_quantities+DB::table('sales_invoice_items')->where('product_id',$datas[$key]->product_id)->whereDate('sales_invoice_items.created_at', '>=', $start_date)->whereDate('sales_invoice_items.created_at', '<=', $end_date)->sum('quantity'),3, '.', '');  //->whereBetween('sales_invoice_items.created_at', [$start_date, $end_date])
              $total_sales_amounts = number_format($total_sales_amounts+DB::table('sales_invoice_items')->where('product_id',$datas[$key]->product_id)->whereDate('sales_invoice_items.created_at', '>=', $start_date)->whereDate('sales_invoice_items.created_at', '<=', $end_date)->sum('amount'),2, '.', ''); //->whereBetween('sales_invoice_items.created_at', [$start_date, $end_date])
            }

            $dataTable = Datatables::of($datas);
            $dataTable->addIndexColumn();

            $table  = $dataTable    
                        ->addColumn('name', function() {
                            return $datas->name;
                        })
                        ->addColumn('quantity', function() {
                            return $datas->total_sales;
                        })
                        ->addColumn('total_sales', function() {
                            return $datas->total_sales_amount;
                        })
                    ->make(true);

            if(isset($type)) {

                //$datas = $table->getData()->data;
                switch ($type) {
                    case 'export':
                    
                        return Excel::download(new ItemSalesExport($start_date,$end_date), 'item_sales.xlsx');
                    
                    break;

                    case 'print':
                        
                        $pdf = PDF::loadView('reports.export.item_sales_summary_report', compact('datas','start_date','end_date','total_sales_quantities','total_sales_amounts'));
                        $filename = setting('app_name').'.pdf';
                        return $pdf->stream($filename);
                    
                    break;

                    case 'download':
                        
                        $pdf = PDF::loadView('reports.export.item_sales_summary_report', compact('datas','start_date','end_date','total_sales_quantities','total_sales_amounts'));
                        $filename = setting('app_name').'.pdf';
                        return $pdf->download($filename);
                    
                    break;                   
                }

            } else {
                return $table;
            }

        endif;


        if($report_type=='low-stock-summary') :

            $start_date    = $request->start_date;
            $end_date      = $request->end_date;

             $data = DB::table('products')->whereRaw('low_stock_unit >= stock');

            if($start_date!='' & $end_date!='') {
                $data->whereBetween('products.created_at', [$start_date, $end_date]);
            }   

            $datas = $data->get(); 

            foreach ($datas as $key => $value) {
              $units    = DB::table('uom')->where('id',$value->unit)->first();
              if($datas[$key]->low_stock_unit >= $value->stock) {
                $datas[$key]->stock_quantity  = number_format($value->stock,3, '.', '').' '.$units->name;
                $datas[$key]->low_stock_level = number_format($value->low_stock_unit,3, '.', '').' '.$units->name;
                $datas[$key]->stock_value     = number_format($value->stock * $value->purchase_price,2,'.','');
                $stock_values[] = $datas[$key]->stock_value; 
              } else {
                unset($datas[$key]);
              } 
            }

            $stock_value = number_format(array_sum($stock_values),2, '.', '');

            $dataTable = Datatables::of($datas);
            $dataTable->addIndexColumn();

            $table  = $dataTable    
                        ->addColumn('name', function() {
                            return $datas->name;
                        })
                        ->addColumn('product_code', function() {
                            return $datas->product_code;
                        })
                        ->addColumn('stock_quantity', function() {
                            return $datas->stock_quantity;
                        })
                        ->addColumn('low_stock_level', function(){
                            return $datas->low_stock_level;
                        })
                        ->addColumn('stock_value', function() {
                            return $datas->stock_value;
                        })
                    ->make(true);

            if(isset($type)) {

                //$datas = $table->getData()->data;
                switch ($type) {
                    case 'export':
                   
                        return Excel::download(new LowstockExport($start_date,$end_date), 'lowstock.xlsx');
                    
                    break;

                    case 'print':
                        
                        $pdf = PDF::loadView('reports.export.low_stock_summary_report', compact('datas','start_date','end_date','stock_value'));
                        $filename = setting('app_name').'.pdf';
                        return $pdf->stream($filename);
                    
                    break;

                    case 'download':
                        
                        $pdf = PDF::loadView('reports.export.low_stock_summary_report', compact('datas','start_date','end_date','stock_value'));
                        $filename = setting('app_name').'.pdf';
                        return $pdf->download($filename);
                    
                    break;                   
                }

            } else {
                return $table;
            }

        endif;

        if($report_type=='expenses-transaction-report') :

            $category    = $request->expense_category;
            $start_date  = $request->start_date;
            $end_date    = $request->end_date;

            $data = Expenses::select('expenses.id','expenses.total_amount','expenses.date','expenses.updated_at','expenses_categories.name','payment_mode.name as payment_mode')
                ->leftJoin('expenses_categories', 'expenses.expense_category_id', '=', 'expenses_categories.id')
                ->leftJoin('payment_mode', 'expenses.payment_mode', '=', 'payment_mode.id');

            if($start_date!='' & $end_date!='') {
                $data->whereBetween('expenses.date', [$start_date, $end_date]);
            }
            if($category > 0) {
                $data->where('expenses.expense_category_id', $category);
            }
            $datas = $data->get();

            $total[] = 0;
            foreach ($datas as $key => $value) {
              $datas[$key]->date_new = date('d M Y',strtotime($value->date));
              $datas[$key]->expense_total_amount = number_format($value->total_amount,2,'.','');
              $total[] =   $datas[$key]->total_amount;  
            }
            $total_expense = number_format(array_sum($total),2,'.','');

            $dataTable = Datatables::of($datas);
            $dataTable->addIndexColumn();

            $table  = $dataTable    
                        ->addColumn('date', function() {
                            return $datas->date_new;
                        })
                        ->addColumn('id', function() {
                            return $datas->id;
                        })
                        ->addColumn('name', function() {
                            return $datas->name;
                        })
                        ->addColumn('payment_mode', function(){
                            return $datas->payment_mode;
                        })
                        ->addColumn('expense_total_amount', function() {
                            return $datas->expense_total_amount;
                        })
                    ->make(true);

            if(isset($type)) {

                //$datas = $table->getData()->data;
                switch ($type) {
                    case 'export':

                        return Excel::download(new ExpensetransactionExport($start_date,$end_date,$expense_category), 'expense_transaction.xlsx');
                    
                    break;

                    case 'print':
                        
                      $pdf = PDF::loadView('reports.export.expense_transaction_report', compact('datas','start_date','end_date','total_expense'));
                      $filename = setting('app_name').'.pdf';
                      return $pdf->stream($filename);
                    
                    break;

                    case 'download':
                        
                      $pdf = PDF::loadView('reports.export.expense_transaction_report', compact('datas','start_date','end_date','total_expense'));
                      $filename = setting('app_name').'.pdf';
                      return $pdf->download($filename);
                    
                    break;                   
                }

            } else {
                return $table;
            }

        endif;

        if($report_type=='expenses-category-report') :

            $start_date          = $request->start_date;
            $end_date            = $request->end_date;

            $selectExpenses = Expenses::join('expenses_categories','expenses.expense_category_id','expenses_categories.id')
                                ->distinct()->select('expense_category_id')
                                ->whereBetween('expenses.date', [$start_date, $end_date])
                                ->get();

            $data           = Expenses::join('expenses_categories','expenses.expense_category_id','expenses_categories.id')->select('name',\DB::raw('SUM(total_amount) as expense_total_amount'))
                                ->whereBetween('expenses.date', [$start_date, $end_date])
                                ->whereIn('expense_category_id',$selectExpenses);
            $datas = $data->get();

            if(count($datas) > 0) {
              foreach ($datas as $key => $value) {
                $datas[$key]->expense_total_amount = number_format($value->expense_total_amount,2,'.','');
                $total[] = $datas[$key]->expense_total_amount;
              }
            }
            $total_expense = number_format(array_sum($total),2,'.','');

            $dataTable = Datatables::of($datas);
            $dataTable->addIndexColumn();

            $table  = $dataTable    
                        ->addColumn('name', function() {
                            return $datas->name;
                        })
                        ->addColumn('expense_total_amount', function() {
                            return $datas->expense_total_amount;
                        })
                    ->make(true);

            if(isset($type)) {

                //$datas = $table->getData()->data;
                switch ($type) {
                    case 'export':
                    
                     return Excel::download(new ExpensecategoryExport($start_date,$end_date), 'expense_category.xlsx');
                    
                    break;

                    case 'print':
                        
                      $pdf = PDF::loadView('reports.export.expense_category_report', compact('datas','start_date','end_date','total_expense'));
                      $filename = setting('app_name').'.pdf';
                      return $pdf->stream($filename);
                    
                    break;

                    case 'download':
                        
                      $pdf = PDF::loadView('reports.export.expense_category_report', compact('datas','start_date','end_date','total_expense'));
                      $filename = setting('app_name').'.pdf';
                      return $pdf->download($filename);
                    
                    break;                   
                }

            } else {
                return $table;
            }

        endif;

        if($report_type=='party-wise-outstanding') : 

            $start_date     = $request->start_date;
            $end_date       = $request->end_date;

            $data = DB::table('markets')
                                  ->leftJoin('transaction_track','markets.id','=','transaction_track.market_id')
                                  ->select('markets.*',\DB::raw('count(transaction_track.id) as total_no_transactions'),\DB::raw('max(transaction_track.date) as last_transaction'))
                                  ->groupBy('markets.id'); //,'customer_groups.name as party_group'

            if($start_date!='' & $end_date!='') {
                $data->whereBetween('transaction_track.date', [$start_date, $end_date]);
            }   

            $datas = $data->get();

            foreach ($datas as $key => $value) {
                $datas[$key]->balance = number_format($datas[$key]->balance,2,'.',''); 
                if(!empty($datas[$key]->last_transaction)){
                    $datas[$key]->last_transaction = date('d M Y',strtotime($datas[$key]->last_transaction)); 
                } 
            }

            $dataTable = Datatables::of($datas);
            $dataTable->addIndexColumn();

            $table  = $dataTable    
                            ->addColumn('name', function() {
                                return $datas->name;
                            })
                            ->addColumn('total_no_transactions', function() {
                                return $datas->total_no_transactions;
                            })
                            ->addColumn('balance', function() {
                                return $datas->balance;
                            }) 
                            ->addColumn('last_transaction', function(){
                                return $datas->last_transaction;
                            })  
                        ->make(true);

            if(isset($type)) {

                //$datas = $table->getData()->data;
                switch ($type) {
                    case 'export':
                    
                        return Excel::download(new PartywiseExport, 'partywise.xlsx');
                    
                    break;

                    case 'print':
                        
                      $pdf = PDF::loadView('reports.export.party_wise_outstanding_report', compact('datas'));
                      $filename = setting('app_name').'.pdf';
                      return $pdf->stream($filename);
                    
                    break;

                    case 'download':
                        
                      $pdf = PDF::loadView('reports.export.party_wise_outstanding_report', compact('datas'));
                      $filename = setting('app_name').'.pdf';
                      return $pdf->download($filename);
                    
                    break;                    
                }

            } else {
                return $table;
            }

        endif;

        if($report_type=='party-report-by-item') :

            $start_date    = $request->start_date;
            $end_date      = $request->end_date;
            $product       = $request->product;
            //$product1       = $request->product;

            $datas = $this->marketRepository->get();
            foreach ($datas as $key => $value) {

                $sales_quantity_query = DB::table('sales_invoice')
                                            ->join('sales_invoice_items','sales_invoice.id','=','sales_invoice_items.sales_invoice_id')
                                            ->where('sales_invoice.market_id',$value->id);
                if($product!='' && $product!=0) {
                    $sales_quantity_query->where('sales_invoice_items.product_id',$product);
                }
                if($start_date!='' & $end_date!='') {
                    $sales_quantity_query->whereBetween('sales_invoice.date', [$start_date, $end_date]);
                }   
                $sales_quantity = $sales_quantity_query->sum('quantity');

                $sales_invoice_query   = DB::table('sales_invoice')
                                                ->join('sales_invoice_items','sales_invoice.id','=','sales_invoice_items.sales_invoice_id')
                                                ->where('sales_invoice.market_id',$value->id);
                if($product!='' && $product!=0) {
                    $sales_invoice_query->where('sales_invoice_items.product_id',$product);
                }
                if($start_date!='' & $end_date!='') {
                    $sales_invoice_query->whereBetween('sales_invoice.date', [$start_date, $end_date]);
                }   
                $sales_invoice = $sales_invoice_query->get();

                $sales_invoice_amount[] = 0;                                 
                if(count($sales_invoice) > 0) {
                  foreach ($sales_invoice as $key1 => $value1) {
                    $sales_invoice_amount[]= $value1->quantity * $value1->unit_price;
                  }
                }

                $purchase_quantity_query = DB::table('purchase_invoice')
                                                ->join('purchase_invoice_items','purchase_invoice.id','=','purchase_invoice_items.purchase_invoice_id')
                                                ->where('purchase_invoice.market_id',$value->id);
                if($product!='' && $product!=0) {
                    $purchase_quantity_query->where('purchase_invoice_items.product_id',$product);
                }
                if($start_date!='' & $end_date!='') {
                    $purchase_quantity_query->whereBetween('purchase_invoice.date', [$start_date, $end_date]);
                }   
                $purchase_quantity = $purchase_quantity_query->sum('quantity');

                $purchase_invoice_query   = DB::table('purchase_invoice')
                                                    ->join('purchase_invoice_items','purchase_invoice.id','=','purchase_invoice_items.purchase_invoice_id')
                                                    ->where('purchase_invoice.market_id',$value->id);
                if($product!='' && $product!=0) {
                    $purchase_invoice_query->where('purchase_invoice_items.product_id',$product);
                }
                if($start_date!='' & $end_date!='') {
                    $purchase_invoice_query->whereBetween('purchase_invoice.date', [$start_date, $end_date]);
                }   
                $purchase_invoice = $purchase_invoice_query->get();
                                  
                $purchase_invoice_amount[] = 0;                                 
                if(count($purchase_invoice) > 0) {
                  foreach ($purchase_invoice as $key1 => $value1) {
                    $purchase_invoice_amount[]= $value1->quantity * $value1->unit_price;
                  }
                }                  

                $datas[$key]->sales_quantity    = number_format($sales_quantity,3,'.','')." KGS";
                $datas[$key]->sales_amount      = number_format(array_sum($sales_invoice_amount),2,'.','');
                $datas[$key]->purchase_quantity = number_format($purchase_quantity,3,'.','')." KGS";
                $datas[$key]->purchase_amount   = number_format(array_sum($purchase_invoice_amount),2,'.','');
                unset($sales_invoice_amount);
                unset($purchase_invoice_amount);
                if($datas[$key]->purchase_amount <= 0 && $datas[$key]->sales_amount <= 0) {
                    unset($datas[$key]);   
                }
            }
            if($product!=0) {
                $product = $this->productRepository->findWithoutFail($product)->name; 
            }
            

            $dataTable = Datatables::of($datas);
            $dataTable->addIndexColumn();

            $table  = $dataTable    
                        ->addColumn('name', function() {
                            return $datas->name;
                        })
                        ->addColumn('sales_quantity', function() {
                            return $datas->sales_quantity;
                        })
                        ->addColumn('sales_amount', function() {
                            return $datas->sales_amount;
                        })
                        ->addColumn('purchase_quantity', function(){
                            return $datas->purchase_quantity;
                        })
                        ->addColumn('purchase_amount', function() {
                            return $datas->purchase_amount;
                        })
                    ->make(true); 

            if(isset($type)) {

                //$datas = $table->getData()->data;
                switch ($type) {
                    case 'export':
                    
                        return Excel::download(new PartyreportbyitemExport($start_date,$end_date,$product1), 'partyreportbyitem.xlsx');
                    
                    break;

                    case 'print':
                        
                      $pdf = PDF::loadView('reports.export.party_report_by_item_report', compact('datas','start_date','end_date','product'));
                      $filename = setting('app_name').'.pdf';
                      return $pdf->stream($filename);
                    
                    break;

                    case 'download':
                        
                      $pdf = PDF::loadView('reports.export.party_report_by_item_report', compact('datas','start_date','end_date','product'));
                      $filename = setting('app_name').'.pdf';
                      return $pdf->download($filename);
                    
                    break;                    
                }

            } else {
                return $table;
            }

        endif;


        if($report_type=='item-report-by-party') :

            $start_date    = $request->start_date;
            $end_date      = $request->end_date;
            $market        = $request->market;
            // $market1        = $request->market;

            $datas = $this->productRepository->get();

            foreach ($datas as $key => $value) {

                $sales_quantity = DB::table('sales_invoice')
                                  ->join('sales_invoice_items','sales_invoice.id','=','sales_invoice_items.sales_invoice_id')
                                  ->where('sales_invoice_items.product_id',$value->id)
                                  // ->where('sales_invoice.market_id',$market)
                                  ->whereDate('sales_invoice.date', '>=', $start_date)
                                  ->whereDate('sales_invoice.date', '<=', $end_date)
                                  // ->whereBetween('sales_invoice.date', [$start_date, $end_date])
                                  ->sum('quantity');

                $sales_invoice   = DB::table('sales_invoice')
                                  ->join('sales_invoice_items','sales_invoice.id','=','sales_invoice_items.sales_invoice_id')
                                  ->where('sales_invoice_items.product_id',$value->id)
                                  // ->where('sales_invoice.market_id',$market)
                                  ->whereDate('sales_invoice.date', '>=', $start_date)
                                  ->whereDate('sales_invoice.date', '<=', $end_date)
                                  // ->whereBetween('sales_invoice.date', [$start_date, $end_date])
                                  ->get();

                $sales_invoice_amount[] = 0;                                 
                if(count($sales_invoice) > 0) {
                  foreach ($sales_invoice as $key1 => $value1) {
                    $sales_invoice_amount[]= $value1->quantity * $value1->unit_price;
                  }
                }

                $purchase_quantity = DB::table('purchase_invoice')
                                    ->join('purchase_invoice_items','purchase_invoice.id','=','purchase_invoice_items.purchase_invoice_id')
                                    // ->where('purchase_invoice.market_id',$market)
                                    ->where('purchase_invoice_items.product_id',$value->id)
                                    ->whereDate('purchase_invoice.date', '>=', $start_date)
                                    ->whereDate('purchase_invoice.date', '<=', $end_date)
                                    // ->whereBetween('purchase_invoice.date', [$start_date, $end_date])
                                    ->sum('quantity');

                $purchase_invoice   = DB::table('purchase_invoice')
                                    ->join('purchase_invoice_items','purchase_invoice.id','=','purchase_invoice_items.purchase_invoice_id')
                                    // ->where('purchase_invoice.market_id',$market)
                                    ->where('purchase_invoice_items.product_id',$value->id)
                                    ->whereDate('purchase_invoice.date', '>=', $start_date)
                                    ->whereDate('purchase_invoice.date', '<=', $end_date)
                                    // ->whereBetween('purchase_invoice.date', [$start_date, $end_date])
                                    ->get();
                                  
                $purchase_invoice_amount[] = 0;                                 
                if(count($purchase_invoice) > 0) {
                  foreach ($purchase_invoice as $key1 => $value1) {
                    $purchase_invoice_amount[]= $value1->quantity * $value1->unit_price;
                  }
                } 

                $units    = DB::table('uom')->where('id',$value->unit)->first();                 

                $datas[$key]->unit_name         = $units->name;
                $datas[$key]->sales_quantity    = number_format($sales_quantity,3,'.','')." ".$datas[$key]->unit_name;
                $datas[$key]->sales_amount      = number_format(array_sum($sales_invoice_amount),2,'.','');
                $datas[$key]->purchase_quantity = number_format($purchase_quantity,3,'.','')." ".$datas[$key]->unit_name;
                $datas[$key]->purchase_amount   = number_format(array_sum($purchase_invoice_amount),2,'.','');
                unset($sales_invoice_amount);
                unset($purchase_invoice_amount);
            } 

            // $market = $this->marketRepository->findWithoutFail($market)->name;

            switch ($type) {

                case 'export':
                    
                  // $pdf = PDF::loadView('reports.export.item_report_by_party', compact('datas','start_date','end_date','market'));
                  // $filename = setting('app_name').'.pdf';
                  // return $pdf->stream($filename);
                return Excel::download(new ItemreportbypartyExport($start_date,$end_date,$market1), 'itemreportbyparty.xlsx');
                
                break;

                case 'print':
                    
                  $pdf = PDF::loadView('reports.export.item_report_by_party', compact('datas','start_date','end_date','market'));
                  $filename = setting('app_name').'.pdf';
                  return $pdf->stream($filename);
                
                break;

                case 'download':
                    
                  $pdf = PDF::loadView('reports.export.item_report_by_party', compact('datas','start_date','end_date','market'));
                  $filename = setting('app_name').'.pdf';
                  return $pdf->download($filename);
                
                break;
                
            }


        endif;
        
        if($report_type=='bill-wise-profit') :

            $start_date    = $request->start_date;
            $end_date      = $request->end_date;

            $datas = DB::table('sales_invoice')
                            ->whereBetween('sales_invoice.date', [$start_date, $end_date])
                            ->get();
            $overall_profit[] = 0;
            foreach ($datas as $key => $value) {
                $datas[$key]->market_id          = $this->marketRepository->findWithoutFail($value->market_id)->name;
                

                $sales_detail     = DB::table('sales_invoice_items')->where('sales_invoice_id',$value->id)->get();
                $total_purchase[] = 0;
                if(count($sales_detail) > 0) {
                    foreach ($sales_detail as $key1 => $value1) {
                        $product          = $this->productRepository->findWithoutFail($value1->product_id);
                        $total_purchase[] = $product->price * $value1->quantity;
                    }
                }
                $datas[$key]->purchase_amount    = number_format(array_sum($total_purchase),2,'.','');
                $datas[$key]->profit_amount      = number_format($value->total - array_sum($total_purchase),2,'.','');
                $overall_profit[] = $datas[$key]->profit_amount;
                $datas[$key]->sales_total_amount = number_format($value->total,2,'.','');
                $datas[$key]->date = date('d M Y',strtotime($value->date));
                unset($total_purchase);
            }
            $profit_amount = number_format(array_sum($overall_profit),2,'.','');

            $dataTable = Datatables::of($datas);
                $dataTable->addIndexColumn();

                $table  = $dataTable    
                            ->addColumn('date', function() {
                                return $datas->date;
                            })
                            ->addColumn('code', function() {
                                return $datas->code;
                            })
                            ->addColumn('market_id', function() {
                                return $datas->market_id;
                            })
                            ->addColumn('sales_total_amount', function(){
                                return $datas->sales_total_amount;
                            })
                            ->addColumn('sales_total_amount', function() {
                                return $datas->sales_total_amount;
                            })
                            ->addColumn('purchase_amount', function() {
                                return $datas->purchase_amount;
                            }) 
                            ->addColumn('profit_amount', function() {
                                return $datas->profit_amount;
                            })   
                        ->make(true);

            if(isset($type)) {

                //$datas = $table->getData()->data;
                switch ($type) {
                    case 'export':
                    
                    //   $pdf = PDF::loadView('reports.export.bill_wise_profit_report', compact('datas','start_date','end_date','profit_amount'));
                    //   $filename = setting('app_name').'.pdf';
                    //   return $pdf->stream($filename);
                     return Excel::download(new BitwiseprofitExport($start_date,$end_date), 'bitwiseprofit.xlsx');
                    
                    break;

                    case 'print':
                        
                      $pdf = PDF::loadView('reports.export.bill_wise_profit_report', compact('datas','start_date','end_date','profit_amount'));
                      $filename = setting('app_name').'.pdf';
                      return $pdf->stream($filename);
                    
                    break;

                    case 'download':
                        
                      $pdf = PDF::loadView('reports.export.bill_wise_profit_report', compact('datas','start_date','end_date','profit_amount'));
                      $filename = setting('app_name').'.pdf';
                      return $pdf->download($filename);
                    
                    break;                  
                }

            } else {
                return $table;
            }

        endif;
        
        if($report_type=='products-report') :

            $start_date    = $request->start_date;
            $end_date      = $request->end_date;
            $product_id    = $request->product;
          
            $data    = DB::table('products')
                            ->leftJoin('product_orders', 'product_orders.product_id','=','products.id')
                            ->select(
                                'products.name',
                                'products.product_code',
                                'products.hsn_code',
                                'products.purchase_price',
                                'products.stock',
                                'products.unit',
                                DB::raw('(products.stock * products.purchase_price) as stockvalue'),
                                'product_orders.quantity as order_quantity',
                                'product_orders.order_id as order_id',
                                'product_orders.unit_price',
                                \DB::raw('Date(product_orders.created_at) AS ordered_date')
                            )->where('order_id','!=','NULL');

            if($product_id!=0) {
                $data->where('products.id',$product_id);
            }   
        
            if($start_date!='' & $end_date!='') {
                $data->whereDate('product_orders.created_at','>=',$start_date)->whereDate('product_orders.created_at','<=',$end_date);
            } 
            $items = $data->get();

            $data1    = DB::table('products')
                            ->leftJoin('sales_invoice_items', 'sales_invoice_items.product_id','=','products.id')
                            ->select(
                                'products.name',
                                'products.product_code',
                                'products.hsn_code',
                                'products.purchase_price',
                                'products.stock',
                                'products.unit',
                                DB::raw('(products.stock * products.purchase_price) as stockvalue'),
                                'sales_invoice_items.quantity as order_quantity',
                                'sales_invoice_items.sales_invoice_id as order_id',
                                'sales_invoice_items.amount as price',
                                'sales_invoice_items.unit as unit',
                                \DB::raw('Date(sales_invoice_items.created_at) AS ordered_date')
                            )->where('sales_invoice_id','!=','NULL');
        
            if($product_id!=0) {
                $data1->where('products.id',$product_id);
            } 
        
            if($start_date!='' & $end_date!='') {
                $data1->whereDate('sales_invoice_items.created_at','>=',$start_date)->whereDate('sales_invoice_items.created_at','<=',$end_date);
            }   
            $items1 = $data1->get();
                          
            $datas = array_merge($items->toArray(), $items1->toArray());
            
             if(count($datas)>0){
                foreach ($datas as $key => $value) {

                    $units = DB::table('uom')->where('id',$value->unit)->first();

                    $datas[$key]->unit             = $units->name;
                    $datas[$key]->purchasing_price = number_format($datas[$key]->purchase_price,2, '.', '');
                    $datas[$key]->selling_price    = number_format($datas[$key]->price,2, '.', '');
                    $datas[$key]->stock_quantity   = number_format($datas[$key]->stock,3, '.', '').' '.$datas[$key]->unit;
                    $datas[$key]->stock_value      = number_format($datas[$key]->stock * $datas[$key]->purchase_price,2, '.', '');
                    $stocks[]                      = $datas[$key]->stock_value;
                    $datas[$key]->ordered_date     = date('d M Y',strtotime($datas[$key]->ordered_date));

                    $datas[$key]->price            = setting('default_currency').''.number_format($datas[$key]->price,2, '.', '');

                     if($datas[$key]->order_quantity==''){
                     $datas[$key]->ordered_quantity   = '';
                    }else{
                      $datas[$key]->ordered_quantity   = number_format($datas[$key]->order_quantity,3, '.', '').' '.$datas[$key]->unit;
                    }
                }
                $stock_value                       = number_format(array_sum($stocks),2, '.', '');
                
             }

            $dataTable = Datatables::of($datas);
            $dataTable->addIndexColumn();

            $table  = $dataTable    
                        ->addColumn('name', function() {
                            return $datas->name;
                        })
                        ->addColumn('product_code', function() {
                            return $datas->product_code;
                        })
                        ->addColumn('hsn_code', function() {
                            return $datas->hsn_code;
                        })
                        ->addColumn('purchasing_price', function(){
                            return $datas->purchasing_price;
                        })
                        ->addColumn('selling_price', function() {
                            return $datas->selling_price;
                        })
                        ->addColumn('stock_quantity', function() {
                            return $datas->stock_quantity;
                        }) 
                        ->addColumn('stock_value', function(){
                            return $datas->stock_value;
                        })
                        ->addColumn('ordered_quantity', function() {
                            return $datas->ordered_quantity;
                        }) 
                        ->addColumn('ordered_date', function(){
                            return $datas->ordered_date;
                        })
                    ->make(true);

            if(isset($type)) {

                //$datas = $table->getData()->data;
                switch ($type) {
                    case 'export':
                    
                        return Excel::download(new ProductsExport($start_date,$end_date,$product_id), 'products.xlsx');
                    
                    break;

                    case 'print':
                        
                        $pdf = PDF::loadView('reports.export.products_report', compact('datas','start_date','end_date'));
                        $filename = setting('app_name').'.pdf';
                        return $pdf->stream($filename);
                    
                    break;

                    case 'download':
                        
                        $pdf = PDF::loadView('reports.export.products_report', compact('datas','start_date','end_date'));
                        $filename = setting('app_name').'.pdf';
                        return $pdf->download($filename);
                    
                    break;                    
                }

            } else {
                return $table;
            }

        endif;


         if($report_type=='popular-products-report') :

           $start_date    = $request->start_date;
            $end_date      = $request->end_date;

         $datas = DB::select('SELECT id, name,product_code,hsn_code,purchase_price,price,stock,unit,stockvalue, SUM(total_quantity) AS ordered_quantity FROM(SELECT a.id,a.name,a.product_code,a.hsn_code,a.purchase_price,a.price,a.stock,a.unit,a.stock*a.purchase_price as stockvalue, SUM(b.quantity) AS total_quantity
FROM products a JOIN product_orders b ON b.product_id = a.id GROUP BY a.id, b.product_id UNION SELECT a.id,a.name,a.product_code,a.hsn_code,a.purchase_price,a.price,a.stock,a.unit,a.stock*a.purchase_price as stockvalue, SUM(c.quantity) AS total_quantity
FROM products a JOIN sales_invoice_items c ON c.product_id = a.id GROUP BY a.id, c.product_id
) temp where purchase_price<price GROUP BY id order by total_quantity desc');

        if(count($datas)>0){
            foreach ($datas as $key => $value) {

                $units = DB::table('uom')->where('id',$datas[$key]->unit)->first();

                $datas[$key]->unit             = $units->name;
                $datas[$key]->purchasing_price = number_format($datas[$key]->purchase_price,2, '.', '');
                $datas[$key]->selling_price    = number_format($datas[$key]->price,2, '.', '');
                $datas[$key]->stock_quantity   = number_format($datas[$key]->stock,3, '.', '')." ".$datas[$key]->unit;
                $datas[$key]->stock_value      = number_format($datas[$key]->stock * $datas[$key]->purchase_price,2, '.', '');
                $stocks[]                      = $datas[$key]->stock_value;
                
                  $profit = $value->price - $value->purchase_price;
                 if($value->purchase_price>0){
                 $profit_percentage = ($profit*100)/$value->purchase_price;
                 }else{ $profit_percentage =100; }
                 if($profit_percentage>0){ $profit_percent = $profit_percentage; } else{ $profit_percent = 0; }
                  $datas[$key]->profit_percentage   = number_format($profit_percent,2).'%';
            }
            $stock_value                       = number_format(array_sum($stocks),2, '.', '');
        }

        $dataTable = Datatables::of($datas);
        $dataTable->addIndexColumn();

        $table  = $dataTable    
                    ->addColumn('name', function() {
                        return $datas->name;
                    })
                    ->addColumn('product_code', function() {
                        return $datas->product_code;
                    })
                    ->addColumn('hsn_code', function() {
                        return $datas->hsn_code;
                    })
                    ->addColumn('purchasing_price', function(){
                        return $datas->purchasing_price;
                    })
                    ->addColumn('selling_price', function() {
                        return $datas->selling_price;
                    })
                    ->addColumn('stock_quantity', function() {
                        return $datas->stock_quantity;
                    }) 
                    ->addColumn('profit_percentage', function(){
                        return $datas->profit_percentage;
                    })
                ->make(true);

        if(isset($type)) {

            //$datas = $table->getData()->data;
            switch ($type) {
                case 'export':
                    
                    return Excel::download(new ProfitableProductsExport($start_date,$end_date), 'profitable_products.xlsx');
                
                break;

                case 'print':
                    
                    $pdf = PDF::loadView('reports.export.profitable_products_report', compact('datas','start_date','end_date'));
                    $filename = setting('app_name').'.pdf';
                    return $pdf->stream($filename);
                
                break;

                case 'download':
                    
                    $pdf = PDF::loadView('reports.export.profitable_products_report', compact('datas','start_date','end_date'));
                    $filename = setting('app_name').'.pdf';
                    return $pdf->download($filename);
                
                break;                    
            }

        } else {
            return $table;
        }

        endif;

          if($report_type=='profitable-products-report') :

           $start_date    = $request->start_date;
            $end_date      = $request->end_date;

         $datas = DB::select('SELECT id, name,product_code,hsn_code,purchase_price,price,stock,unit,stockvalue, SUM(total_quantity) AS ordered_quantity FROM(SELECT a.id,a.name,a.product_code,a.hsn_code,a.purchase_price,a.price,a.stock,a.unit,a.stock*a.purchase_price as stockvalue, SUM(b.quantity) AS total_quantity
FROM products a JOIN product_orders b ON b.product_id = a.id GROUP BY a.id, b.product_id UNION SELECT a.id,a.name,a.product_code,a.hsn_code,a.purchase_price,a.price,a.stock,a.unit,a.stock*a.purchase_price as stockvalue, SUM(c.quantity) AS total_quantity
FROM products a JOIN sales_invoice_items c ON c.product_id = a.id GROUP BY a.id, c.product_id
) temp where purchase_price<price GROUP BY id order by total_quantity desc');

        if(count($datas)>0){
            foreach ($datas as $key => $value) {

                $units = DB::table('uom')->where('id',$datas[$key]->unit)->first();

                $datas[$key]->unit             = $units->name;
                $datas[$key]->purchasing_price = number_format($datas[$key]->purchase_price,2, '.', '');
                $datas[$key]->selling_price    = number_format($datas[$key]->price,2, '.', '');
                $datas[$key]->stock_quantity   = number_format($datas[$key]->stock,3, '.', '')." ".$datas[$key]->unit;
                $datas[$key]->stock_value      = number_format($datas[$key]->stock * $datas[$key]->purchase_price,2, '.', '');
                $stocks[]                      = $datas[$key]->stock_value;
                
                  $profit = $value->price - $value->purchase_price;
                 if($value->purchase_price>0){
                 $profit_percentage = ($profit*100)/$value->purchase_price;
                 }else{ $profit_percentage =100; }
                 if($profit_percentage>0){ $profit_percent = $profit_percentage; } else{ $profit_percent = 0; }
                  $datas[$key]->profit_percentage   = number_format($profit_percent,2).'%';
            }
            $stock_value                       = number_format(array_sum($stocks),2, '.', '');
        }

        $dataTable = Datatables::of($datas);
        $dataTable->addIndexColumn();

        $table  = $dataTable    
                    ->addColumn('name', function() {
                        return $datas->name;
                    })
                    ->addColumn('product_code', function() {
                        return $datas->product_code;
                    })
                    ->addColumn('hsn_code', function() {
                        return $datas->hsn_code;
                    })
                    ->addColumn('purchasing_price', function(){
                        return $datas->purchasing_price;
                    })
                    ->addColumn('selling_price', function() {
                        return $datas->selling_price;
                    })
                    ->addColumn('stock_quantity', function() {
                        return $datas->stock_quantity;
                    }) 
                    ->addColumn('profit_percentage', function(){
                        return $datas->profit_percentage;
                    })
                ->make(true);

        if(isset($type)) {

            //$datas = $table->getData()->data;
            switch ($type) {
                case 'export':
                    
                    return Excel::download(new ProfitableProductsExport($start_date,$end_date), 'profitable_products.xlsx');
                
                break;

                case 'print':
                    
                    $pdf = PDF::loadView('reports.export.profitable_products_report', compact('datas','start_date','end_date'));
                    $filename = setting('app_name').'.pdf';
                    return $pdf->stream($filename);
                
                break;

                case 'download':
                    
                    $pdf = PDF::loadView('reports.export.profitable_products_report', compact('datas','start_date','end_date'));
                    $filename = setting('app_name').'.pdf';
                    return $pdf->download($filename);
                
                break;                    
            }

        } else {
            return $table;
        }

        endif;

             if($report_type=='staff-login-report') :

           $start_date    = $request->start_date;
            $end_date      = $request->end_date;
             $user_id      = $request->users;
             $user_role     = $request->roles;
          
           if($user_role!='')
            {
                if($user_role=='admin'){
                 $data    = User::whereHas('roles', function($q){$q->where('name','=','admin');})->join('user_log', 'users.id','=','user_log.user_id')->select('users.*','user_log.created_at as created','user_log.updated_at as updated');
                }
                else if($user_role=='manager'){
                 $data    = User::whereHas('roles', function($q){$q->where('name','=','manager');})->join('user_log', 'users.id','=','user_log.user_id')->select('users.*','user_log.created_at as created','user_log.updated_at as updated');   
                }else{
                 $data    = User::whereHas('roles', function($q){$q->where('name','=','driver');})->join('user_log', 'users.id','=','user_log.user_id')->select('users.*','user_log.created_at as created','user_log.updated_at as updated');   
                }
            }
            else
            {
                $data    = User::whereHas('roles', function($q){$q->where('name','!=','client');})->join('user_log', 'users.id','=','user_log.user_id')->select('users.*','user_log.created_at as created','user_log.updated_at as updated');  
            }
            
        if($start_date!='' & $end_date!='') {
           // $data->whereBetween('user_log.created_at', [$start_date, $end_date]);
            $data->whereDate('user_log.created_at','>=',$start_date)->whereDate('user_log.created_at','<=',$end_date);
        }   
        if($user_id!=0) {
         
            $data->where('users.id',$user_id);
        }  
        $datas = $data->get();
           
            switch ($type) {

                case 'export':
                    
                    // $pdf = PDF::loadView('reports.export.stock_summary_report', compact('datas','stock_value'));
                    // $filename = setting('app_name').'.pdf';
                    // return $pdf->stream($filename);
                return Excel::download(new StaffLoginExport($start_date,$end_date,$user_id,$user_role), 'staff_login.xlsx');
                
                break;

                case 'print':
                    
                    $pdf = PDF::loadView('reports.export.staff_login_report', compact('datas','start_date','end_date'));
                    $filename = setting('app_name').'.pdf';
                    return $pdf->stream($filename);
                
                break;

                case 'download':
                    
                    $pdf = PDF::loadView('reports.export.staff_login_report', compact('datas','start_date','end_date'));
                    $filename = setting('app_name').'.pdf';
                    return $pdf->download($filename);
                
                break;
                
            }


        endif;



        if($report_type=='customers-report') :
            
            $start_date    = $request->start_date;
            $end_date      = $request->end_date;

             $data =  DB::table('markets')
                          ->leftJoin('transaction_track','markets.id','=','transaction_track.market_id')
                          ->leftJoin('customer_groups','markets.customer_group_id','=','customer_groups.id')
                          ->leftJoin('users', 'markets.user_id','=','users.id')
                          ->leftJoin('customer_levels', 'users.level','=','customer_levels.id')
                          ->select('markets.*','customer_groups.name as party_group',\DB::raw('count(transaction_track.id) as total_no_transactions'),'customer_levels.name as levels','customer_levels.group_points as reward_points')
                          ->groupBy('markets.id')->where('markets.type',1);

          
             if($start_date!='' & $end_date!='') {
                $data->whereDate('markets.created_at','>=',$start_date)->whereDate('markets.created_at','<=',$end_date);
            }   

           $datas = $data->get();
           
            foreach ($datas as $key => $value) {
                $datas[$key]->address = $datas[$key]->address_line_1.' '.$datas[$key]->address_line_2;
                $datas[$key]->reward_levels = $datas[$key]->levels.' - '.$datas[$key]->reward_points;
                $datas[$key]->created_at = date('d M Y', strtotime($datas[$key]->created_at));
            }

            $dataTable = Datatables::of($datas);
            $dataTable->addIndexColumn();

            $table  = $dataTable    
                        ->addColumn('name', function() {
                            return $datas->name;
                        })
                        ->addColumn('created_at', function() {
                            return $datas->created_at;
                        })
                        ->addColumn('email', function() {
                            return $datas->email;
                        })
                        ->addColumn('phone', function(){
                            return $datas->phone;
                        })
                        ->addColumn('mobile', function() {
                            return $datas->mobile;
                        })
                        ->addColumn('reward_levels', function() {
                            return $datas->reward_levels;
                        }) 
                        ->addColumn('total_no_transactions', function(){
                            return $datas->total_no_transactions;
                        })  
                        ->addColumn('party_group', function() {
                            return $datas->party_group;
                        })
                        ->addColumn('address', function() {
                            return $datas->address;
                        })
                        ->addColumn('city', function(){
                            return $datas->city;
                        })
                        ->addColumn('state', function() {
                            return $datas->state;
                        })
                        ->addColumn('pincode', function() {
                            return $datas->pincode;
                        })
                    ->make(true);
           
            if(isset($type)) {

                //$datas = $table->getData()->data;
                switch ($type) {
                    case 'export':
                    
                    return Excel::download(new CustomersExport($start_date,$end_date), 'customers_report.xlsx');
                    
                    break;

                    case 'print':
                        
                      $pdf = PDF::loadView('reports.export.customers_report', compact('datas','start_date','end_date'));
                      $filename = setting('app_name').'.pdf';
                      return $pdf->stream($filename);
                    
                    break;

                    case 'download':
                        
                      $pdf = PDF::loadView('reports.export.customers_report', compact('datas','start_date','end_date'));
                      $filename = setting('app_name').'.pdf';
                      return $pdf->download($filename);
                    
                    break;                    
                }

            } else {
                return $table;
            }

        endif;


         if($report_type=='stock-purchase-report') :

           $start_date    = $request->start_date;
            $end_date      = $request->end_date;

          $data = DB::select('SELECT id, name,product_code,hsn_code,purchase_price,price,stock,unit,stockvalue, SUM(total_quantity) AS ordered_quantity FROM(SELECT a.id,a.name,a.product_code,a.hsn_code,a.purchase_price,a.price,a.stock,a.unit,a.stock*a.purchase_price as stockvalue, SUM(b.quantity) AS total_quantity
FROM products a JOIN product_orders b ON b.product_id = a.id GROUP BY a.id, b.product_id UNION SELECT a.id,a.name,a.product_code,a.hsn_code,a.purchase_price,a.price,a.stock,a.unit,a.stock*a.purchase_price as stockvalue, SUM(c.quantity) AS total_quantity
FROM products a JOIN sales_invoice_items c ON c.product_id = a.id GROUP BY a.id, c.product_id
) temp GROUP BY id order by total_quantity asc');

       $arr=[];
       foreach ($data as $key => $value) {
          $items[] = $value;
           $arr[]=$value->id;
           
           unset($value->id);
           
       } 
       
          $data1    =  DB::select('SELECT id, name,product_code,hsn_code,purchase_price,price,stock,unit,stock*purchase_price as stockvalue,0 AS ordered_quantity FROM products where id NOT IN (' . implode(',', $arr) . ')');
      
      if(count($data1)>0){
      foreach ($data1 as $key => $value1) {
           $item[] = $value1;
           
           unset($value->id);
      }
     $data2 =  array_merge($item,$items);
      }else
      {
        $data2 =   $items;
      }
     $datas = $data2;//array_slice($data2, 0, 10);
     
    
       
        if(count($datas)>0){
            foreach ($datas as $key => $value) {
                $units = DB::table('uom')->where('id',$value->unit)->first();
                
                $datas[$key]->unit_name        = $units->name;
                $datas[$key]->purchasing_price = number_format($datas[$key]->purchase_price,2, '.', '');
                $datas[$key]->selling_price    = number_format($datas[$key]->price,2, '.', '');
                $datas[$key]->stock_quantity   = number_format($datas[$key]->stock,3).' '.$datas[$key]->unit_name;
                $datas[$key]->stock_value      = number_format($datas[$key]->stock * $datas[$key]->purchase_price,2, '.', '');
                $stocks[]                      = $datas[$key]->stock_value;

                 if($datas[$key]->ordered_quantity==''){
                 $datas[$key]->ordered_quantity   = '';
                }else{
                  $datas[$key]->ordered_quantity   = number_format($datas[$key]->ordered_quantity,3).' '.$datas[$key]->unit_name;
                }
            }
            $stock_value                       = number_format(array_sum($stocks),2, '.', '');
        }

            switch ($type) {

                case 'export':
                    
                    // $pdf = PDF::loadView('reports.export.stock_summary_report', compact('datas','stock_value'));
                    // $filename = setting('app_name').'.pdf';
                    // return $pdf->stream($filename);
                return Excel::download(new StockPurchaseExport($start_date,$end_date), 'stock_purchase.xlsx');
                
                break;

                case 'print':
                    
                    $pdf = PDF::loadView('reports.export.stock_purchase_report', compact('datas','start_date','end_date'));
                    $filename = setting('app_name').'.pdf';
                    return $pdf->stream($filename);
                
                break;

                case 'download':
                    
                    $pdf = PDF::loadView('reports.export.stock_purchase_report', compact('datas','start_date','end_date'));
                    $filename = setting('app_name').'.pdf';
                    return $pdf->download($filename);
                
                break;
                
            }


        endif;
        
         if($report_type=='delivery-report') :

            $datas = DB::table('orders')
                ->leftJoin('delivery_addresses', 'delivery_addresses.id','=','orders.delivery_address_id')
                ->leftJoin('users', 'users.id','=','orders.user_id')
                // ->leftJoin('payments', 'payments.id','=','orders.payment_id')
                ->leftJoin('order_statuses', 'order_statuses.id','=','orders.order_status_id')
                ->select('delivery_addresses.*','users.name','orders.delivery_distance','orders.driver_id','orders.id as order_id','order_statuses.status as order_status') //'payments.method','payments.status as payment_status',
                ->get();

                foreach ($datas as $key => $value) {
               
               if($value->address_line_1!=''){
                $datas[$key]->address = $value->address_line_1.', '.$value->address_line_2.', '.$value->city.', '.$value->state.'-'.$value->pincode;
               }else
               {
                $datas[$key]->address = '';
               }
               
               if($value->driver_id!=''){
                    $get_driver = DB::table('users')->select('name')->where('id',$value->driver_id)->first();
                    $datas[$key]->delivered_by = $get_driver->name;
               }else
               {
                    $datas[$key]->delivered_by ='';
               }
               
               $datas[$key]->transaction_number ='';
               
               }

            $dataTable = Datatables::of($datas);
            $dataTable->addIndexColumn();

            $table  = $dataTable    
                        ->addColumn('name', function() {
                            return $datas->name;
                        })
                        ->addColumn('address', function() {
                            return $datas->address;
                        })
                        ->addColumn('description', function() {
                            return $datas->description;
                        })
                        ->addColumn('delivery_distance', function(){
                            return $datas->delivery_distance;
                        })
                        ->addColumn('order_id', function() {
                            return $datas->order_id;
                        })
                        ->addColumn('order_status', function() {
                            return $datas->order_status;
                        }) 
                        ->addColumn('delivered_by', function(){
                            return $datas->delivered_by;
                        }) 
                        ->addColumn('transaction_number', function(){
                            return $datas->transaction_number;
                        })  
                    ->make(true);
             
            if(isset($type)) {

                $datas = $table->getData()->data;
                switch ($type) {
                     case 'export':
                    
                        return Excel::download(new DeliveryExport(), 'delivery_report.xlsx');
                    
                    break;

                    case 'print':
                        
                        $pdf = PDF::loadView('reports.export.delivery_report', compact('datas'));
                        $filename = setting('app_name').'.pdf';
                        return $pdf->stream($filename);
                    
                    break;

                    case 'download':
                        
                        $pdf = PDF::loadView('reports.export.delivery_report', compact('datas'));
                        $filename = setting('app_name').'.pdf';
                        return $pdf->download($filename);
                    
                    break;                 
                }

            } else {
                return $table;
            }

        endif;
        
        if($report_type=='wastage-report') :

            $start_date     = $request->start_date;
            $end_date       = $request->end_date;
            $product_id     = $request->product;

            $data = DB::table('inventory_track')
                        ->leftJoin('products', 'inventory_track.product_id','=','products.id')
                        ->leftJoin('uom', 'inventory_track.unit','=','uom.id')
                        ->select('inventory_track.*','uom.name as unit_name','products.name as product_name', 'products.product_code as product_code', 'products.hsn_code as hsn_code')
                        ->where('inventory_track.usage','wastage');

            if($product_id!='' && $product_id!=0) {
                $data->where('inventory_track.product_id',$product_id);
            }

            if($start_date!='' & $end_date!='') {
                $data->whereBetween('inventory_track.date', [$start_date, $end_date]);
            } 

            $datas = $data->get();

            foreach ($datas as $key => $value) {
                $datas[$key]->date  = date('d M Y',strtotime($datas[$key]->date));
                $datas[$key]->quantity   = number_format($datas[$key]->quantity,3, '.', '');
                $datas[$key]->unit_name   = $datas[$key]->quantity.' '.$datas[$key]->unit_name;
            }

            $stocks[] = 0;
            $stock = "";

            $cost[] = 0;

            $dataTable = Datatables::of($datas);
            $dataTable->addIndexColumn();

            $table  = $dataTable    
                        ->addColumn('date', function() {
                            return date('d-m-Y',strtotime($datas->date));
                        })
                        ->addColumn('product_name', function() {
                            return $datas->products_name;
                        })
                        ->addColumn('product_code', function() {
                            return $datas->product_code;
                        })
                        ->addColumn('hsn_code', function(){
                            return $datas->hsn_code;
                        })
                        ->addColumn('type', function() {
                            return $datas->type;
                        })
                        ->addColumn('quantity', function() {
                            return $datas->quantity."".$datas->unit_name;
                        }) 
                        ->addColumn('description', function(){
                            return $datas->description;
                        })  
                    ->make(true);

            if(isset($type)) {

                //$datas = $table->getData()->data;
                
                switch ($type) {

                    case 'export':
                        
                    return Excel::download(new WastageExport($start_date,$end_date,$product_id), 'wastage_report.xlsx');
                    
                    break;

                    case 'print':
                        
                        $pdf = PDF::loadView('reports.export.wastage_report', compact('datas','start_date','end_date','cost'));
                        $filename = setting('app_name').'.pdf';
                        return $pdf->stream($filename);
                    
                    break;

                    case 'download':
                        
                        $pdf = PDF::loadView('reports.export.wastage_report', compact('datas','start_date','end_date','cost'));
                        $filename = setting('app_name').'.pdf';
                        return $pdf->download($filename);
                    
                    break;
                    
                }

            }

        endif;

        if($report_type=='missing-report') :

            $start_date     = $request->start_date;
            $end_date       = $request->end_date;
            $product_id     = $request->product;

            $data = DB::table('inventory_track')
                        ->leftJoin('products', 'inventory_track.product_id','=','products.id')
                        ->leftJoin('uom', 'inventory_track.unit','=','uom.id')
                        ->select('inventory_track.*','uom.name as unit_name','products.name as product_name', 'products.product_code as product_code', 'products.hsn_code as hsn_code')
                        ->where('inventory_track.usage','missing');

            if($product_id!='' && $product_id!=0) {
                $data->where('inventory_track.product_id',$product_id);
            }

            if($start_date!='' & $end_date!='') {
                $data->whereBetween('inventory_track.date', [$start_date, $end_date]);
            } 

            $datas = $data->get();

            foreach ($datas as $key => $value) {
                $datas[$key]->date  = date('d M Y',strtotime($datas[$key]->date));
                $datas[$key]->quantity   = number_format($datas[$key]->quantity,3, '.', '');
                $datas[$key]->unit_name   = $datas[$key]->quantity.' '.$datas[$key]->unit_name;
            }

            $stocks[] = 0;
            $stock = "";

            $cost[] = 0;

            $dataTable = Datatables::of($datas);
            $dataTable->addIndexColumn();

            $table  = $dataTable    
                        ->addColumn('date', function() {
                            return $datas->date;
                        })
                        ->addColumn('product_name', function() {
                            return $datas->products_name;
                        })
                        ->addColumn('product_code', function() {
                            return $datas->product_code;
                        })
                        ->addColumn('hsn_code', function(){
                            return $datas->hsn_code;
                        })
                        ->addColumn('type', function() {
                            return $datas->type;
                        })
                        ->addColumn('quantity', function() {
                            return $datas->quantity."".$datas->unit_name;
                        }) 
                        ->addColumn('description', function(){
                            return $datas->description;
                        })  
                    //->rawColumns(['date','transaction_no','status'])
                    ->make(true);

            if(isset($type)) {

                //$datas = $table->getData()->data;
                switch ($type) {

                    case 'export':
                        
                    return Excel::download(new WastageExport($start_date,$end_date,$product_id), 'missing_report.xlsx');
                    
                    break;

                    case 'print':
                        
                        $pdf = PDF::loadView('reports.export.missing_report', compact('datas','start_date','end_date','cost'));
                        $filename = setting('app_name').'.pdf';
                        return $pdf->stream($filename);
                    
                    break;

                    case 'download':
                        
                        $pdf = PDF::loadView('reports.export.missing_report', compact('datas','start_date','end_date','cost'));
                        $filename = setting('app_name').'.pdf';
                        return $pdf->download($filename);
                    
                    break;
                    
                }

            }

        endif;

        if($report_type=='charity-wastage-report') :

            $start_date     = $request->start_date;
            $end_date       = $request->end_date;
            $product_id     = $request->product;

            $data = DB::table('inventory_track')
                        ->leftJoin('wastage_disposal', 'inventory_track.id','=','wastage_disposal.inventory_id')
                        ->leftJoin('products', 'wastage_disposal.product_id','=','products.id')
                        ->leftJoin('uom', 'wastage_disposal.unit','=','uom.id')
                        ->select('inventory_track.*','uom.name as unit_name','products.name as product_name', 'products.product_code as product_code', 'products.hsn_code as hsn_code', 'wastage_disposal.description as description_note')
                        ->where('inventory_track.usage','wastage')
                        ->where('wastage_disposal.type','charity');

            if($product_id!='' && $product_id!=0) {
                $data->where('inventory_track.product_id',$product_id);
            }

            if($start_date!='' & $end_date!='') {
                $data->whereBetween('inventory_track.date', [$start_date, $end_date]);
            } 

            $datas = $data->get();

            foreach ($datas as $key => $value) {
                $datas[$key]->date  = date('d M Y',strtotime($datas[$key]->date));
                $datas[$key]->quantity   = number_format($datas[$key]->quantity,3, '.', '');
                $datas[$key]->unit_name   = $datas[$key]->quantity.' '.$datas[$key]->unit_name;
            }

            $stocks[] = 0;
            $stock = "";

            $cost[] = 0;

            $dataTable = Datatables::of($datas);
            $dataTable->addIndexColumn();

            $table  = $dataTable    
                        ->addColumn('date', function() {
                            return $datas->date;
                        })
                        ->addColumn('product_name', function() {
                            return $datas->products_name;
                        })
                        ->addColumn('product_code', function() {
                            return $datas->product_code;
                        })
                        ->addColumn('hsn_code', function(){
                            return $datas->hsn_code;
                        })
                        // ->addColumn('type', function() {
                        //     return $datas->type;
                        // })
                        ->addColumn('quantity', function() {
                            return $datas->unit_name;
                        }) 
                        ->addColumn('description', function(){
                            return $datas->description_note;
                        })  
                    //->rawColumns(['date','transaction_no','status'])
                    ->make(true);

            if(isset($type)) {

                //$datas = $table->getData()->data;
                switch ($type) {

                    case 'export':
                        
                    return Excel::download(new WastageExport($start_date,$end_date,$product_id), 'charity_wastage_report.xlsx');
                    
                    break;

                    case 'print':
                        
                        $pdf = PDF::loadView('reports.export.charity_wastage_report', compact('datas','start_date','end_date','cost'));
                        $filename = setting('app_name').'.pdf';
                        return $pdf->stream($filename);
                    
                    break;

                    case 'download':
                        
                        $pdf = PDF::loadView('reports.export.charity_wastage_report', compact('datas','start_date','end_date','cost'));
                        $filename = setting('app_name').'.pdf';
                        return $pdf->download($filename);
                    
                    break;
                    
                }

            }

        endif;
        
        if($report_type=='vendor-stocks-report') :

            $start_date     = $request->start_date;
            $end_date       = $request->end_date;
            $product_id     = $request->product;

            $data = DB::table('vendor_stock_items')
                        // ->leftJoin('vendor_stock_items', 'vendor_stock.id','=','vendor_stock_items.vendor_stock_id')
                        ->leftJoin('products', 'vendor_stock_items.product_id','=','products.id')
                        ->leftJoin('uom', 'vendor_stock_items.unit','=','uom.id')
                        ->select(
                            'vendor_stock_items.*',
                            'uom.name as unit_name',
                            'products.name as product_name',
                            'products.product_code as product_code',
                            'products.hsn_code as hsn_code',
                            DB::raw('sum(vendor_stock_items.quantity) as toatl_quantity'),
                            DB::raw('sum(vendor_stock_items.amount) as total_amount')
                        )->groupBy('product_id');

            if($product_id!='' && $product_id!=0) {
                $data->where('vendor_stock_items.product_id',$product_id);
            }

            if($start_date!='' & $end_date!='') {
                $data->whereBetween('vendor_stock_items.created_at', [$start_date, $end_date]);
            } 

            $datas = $data->get();

            foreach ($datas as $key => $value) {
                $datas[$key]->date  = date('d M Y',strtotime($datas[$key]->created_at));
                $datas[$key]->quantity   = number_format($datas[$key]->toatl_quantity,3, '.', '');
                $datas[$key]->unit_name   = $datas[$key]->quantity.' '.$datas[$key]->unit_name;
                $datas[$key]->total   = number_format($datas[$key]->total_amount,2, '.', '');
            }

            $stocks[] = 0;
            $stock = "";

            $cost[] = 0;

            $dataTable = Datatables::of($datas);
            $dataTable->addIndexColumn();

            $table  = $dataTable    
                        ->addColumn('product_name', function() {
                            return $datas->product_name;
                        })
                        ->addColumn('product_code', function() {
                            return $datas->product_code;
                        })
                        ->addColumn('hsn_code', function(){
                            return $datas->hsn_code;
                        })
                        ->addColumn('quantity', function() {
                            return $datas->unit_name;
                        }) 
                        ->addColumn('total', function(){
                            return $datas->total;
                        })  
                    //->rawColumns(['date','transaction_no','status'])
                    ->make(true);

            if(isset($type)) {

                //$datas = $table->getData()->data;
                switch ($type) {

                    case 'export':
                        
                        return Excel::download(new WastageExport($start_date,$end_date,$product_id), 'vendor_stocks_report.xlsx');
                    
                    break;

                    case 'print':
                        
                        $pdf = PDF::loadView('reports.export.vendor_stocks_report', compact('datas','start_date','end_date','cost'));
                        $filename = setting('app_name').'.pdf';
                        return $pdf->stream($filename);
                    
                    break;

                    case 'download':
                        
                        $pdf = PDF::loadView('reports.export.vendor_stocks_report', compact('datas','start_date','end_date','cost'));
                        $filename = setting('app_name').'.pdf';
                        return $pdf->download($filename);
                    
                    break;
                    
                }

            }

        endif;
        
        if($report_type=='email-alerts-report') :

            $start_date     = $request->start_date;
            $end_date       = $request->end_date;
            $party_id     = $request->party;

            $data = DB::table('email_notifications')
                        ->leftJoin('party_types', 'email_notifications.party_type_id','=','party_types.id')
                        ->leftJoin('party_sub_types', 'email_notifications.party_sub_type_id','=','party_sub_types.id')
                        ->select(
                            'email_notifications.*',
                            'party_types.name as party_type_name',
                            'party_sub_types.name as party_sub_type_name');

            if($party_id!='' && $party_id!=0) {
                $data->where('email_notifications.party_type_id',$party_id);
            }

            if($start_date!='' & $end_date!='') {
                $data->whereBetween('email_notifications.created_at', [$start_date, $end_date]);
            }  

            $datas = $data->get();

            foreach ($datas as $key => $value) {
                $datas[$key]->date  = date('d M Y',strtotime($datas[$key]->created_at));
            }

            $dataTable = Datatables::of($datas);
            $dataTable->addIndexColumn();

            $table  = $dataTable    
                        ->addColumn('date', function() {
                            return $datas->date;
                        })
                        ->addColumn('subject', function() {
                            return $datas->subject;
                        })
                        ->addColumn('type', function(){
                            return $datas->type;
                        })
                        ->addColumn('party_type_name', function() {
                            return $datas->party_type_name;
                        }) 
                        // ->addColumn('partyname', function() {
                        //     return $datas->partyname;
                        // })
                        ->addColumn('status', function(){
                            return $datas->status;
                        })
                    ->make(true);

            if(isset($type)) {

                //$datas = $table->getData()->data;
                switch ($type) {

                    case 'export':
                        
                    return Excel::download(new WastageExport($start_date,$end_date,$product_id), 'email_alerts_report.xlsx');
                    
                    break;

                    case 'print':
                        
                        $pdf = PDF::loadView('reports.export.email_alerts_report', compact('datas','start_date','end_date'));
                        $filename = setting('app_name').'.pdf';
                        return $pdf->stream($filename);
                    
                    break;

                    case 'download':
                        
                        $pdf = PDF::loadView('reports.export.email_alerts_report', compact('datas','start_date','end_date'));
                        $filename = setting('app_name').'.pdf';
                        return $pdf->download($filename);
                    
                    break;
                    
                }

            }

        endif;

        if($report_type=='online-orders-report') :

            $start_date     = $request->start_date;
            $end_date       = $request->end_date;
            $product_id     = $request->product;

            $data = DB::table('inventory_track')
                        ->leftJoin('products', 'inventory_track.product_id','=','products.id')
                        // ->leftJoin('markets', 'inventory_track.market_id','=','markets.id')
                        ->leftJoin('uom', 'inventory_track.unit','=','uom.id')
                        ->select(
                            'inventory_track.*',
                            'products.name as product_name',
                            'products.product_code as product_code',
                            'products.hsn_code as hsn_code',
                            'uom.name as unit_name',
                            DB::raw('sum(inventory_track.quantity) as toatl_quantity')
                        )->groupBy('inventory_track.product_id');
                        
            $data->where('inventory_track.category','online');

            if($product_id!='' && $product_id!=0) {
                $data->where('inventory_track.product_id',$product_id);
            }

            if($start_date!='' & $end_date!='') {
                $data->whereBetween('inventory_track.created_at', [$start_date, $end_date]);
            }   

            $datas = $data->get();

            foreach ($datas as $key => $value) {
                $datas[$key]->date  = date('d M Y',strtotime($datas[$key]->created_at));
                $datas[$key]->quantity   = number_format($datas[$key]->toatl_quantity,3, '.', '').' '.$datas[$key]->unit_name;
                // $datas[$key]->unit_name   = $datas[$key]->quantity.' '.$datas[$key]->unit_name;
            }

            $dataTable = Datatables::of($datas);
            $dataTable->addIndexColumn();

            $table  = $dataTable    
                        ->addColumn('date', function() {
                            return $datas->date;
                        })
                        ->addColumn('product_name', function() {
                            return $datas->product_name;
                        })
                        ->addColumn('product_code', function(){
                            return $datas->product_code;
                        })
                        ->addColumn('hsn_code', function() {
                            return $datas->hsn_code;
                        }) 
                        // ->addColumn('unit_name', function() {
                        //     return $datas->unit_name;
                        // })
                        ->addColumn('quantity', function(){
                            return $datas->quantity;
                        })
                    ->make(true);

            if(isset($type)) {

                //$datas = $table->getData()->data;
                switch ($type) {

                    case 'export':
                        
                    return Excel::download(new WastageExport($start_date,$end_date,$product_id), 'online_orders_report.xlsx');
                    
                    break;

                    case 'print':
                        
                        $pdf = PDF::loadView('reports.export.online_orders_report', compact('datas','start_date','end_date'));
                        $filename = setting('app_name').'.pdf';
                        return $pdf->stream($filename);
                    
                    break;

                    case 'download':
                        
                        $pdf = PDF::loadView('reports.export.online_orders_report', compact('datas','start_date','end_date'));
                        $filename = setting('app_name').'.pdf';
                        return $pdf->download($filename);
                    
                    break;
                    
                }

            }

        endif;

        if($report_type=='party-transaction-report') :

            $start_date          = $request->start_date;
            $end_date            = $request->end_date;
            $transaction         = $request->transaction;
            
            $query     = $this->transactionRepository->where('id','>',0);
            if(isset($request->market_id) && $request->market_id!='') {
                $query->where('market_id',$request->market_id);
            }
            if(isset($request->transaction) && $request->transaction!='') {
                $query->where('category',$request->transaction);
            }
            if(isset($request->start_date) && isset($request->end_date) && $request->start_date!='' && $request->end_date!='') {
                $query->whereBetween('date', [Carbon::parse($request->start_date)->format('Y-m-d'), Carbon::parse($request->end_date)->format('Y-m-d')]);
            }
            $data         = $query->get();           
            $total_amount = $query->sum('amount');

            switch ($type) {

                case 'export':
                    //return Excel::download(new WastageExport($start_date,$end_date,$product_id), 'wastage_report.xlsx');
                break;

                case 'print':
                    $pdf = PDF::loadView('reports.export.transaction_report', compact('datas','start_date','end_date','transaction','total_amount'));
                    $filename = setting('app_name').'.pdf';
                    return $pdf->stream($filename);
                break;

                case 'download':
                    $pdf = PDF::loadView('reports.export.transaction_report', compact('datas','start_date','end_date','transaction','total_amount'));
                    $filename = setting('app_name').'.pdf';
                    return $pdf->download($filename);
                break;
                
            }

        endif;
        
        
        if($report_type=='party-reward') :
            
            $market_id           = $request->party;
            $start_date          = $request->start_date;
            $end_date            = $request->end_date;
        
            $user         = DB::table('user_markets')
                                ->join('users','users.id','=','user_markets.user_id')
                                ->where('user_markets.market_id',$market_id)
                                ->first();
            $affiliate_id = $user->affiliate_id;

            $t_datas      = DB::table('loyality_points_tracker')
                            ->whereDate('created_at', '>=', $start_date)
                            ->whereDate('created_at', '<=', $end_date)
                            ->where('affiliate_id',$affiliate_id)
                            ->select(
                                'user_id',
                                'point_type',
                                'points',
                                'purchase_id as sales_code',
                                'purchase_id',
                                'created_at'
                            );
                            
            $datas_u      = DB::table('loyality_point_usage')
                            ->whereDate('created_at', '>=', $start_date)
                            ->whereDate('created_at', '<=', $end_date)
                            ->where('user_id',$user->user_id)
                            ->select(
                                'user_id',
                                'point_type',
                                'usage_points as points',
                                'order_id as sales_code',
                                'order_type',
                                'created_at'
                            );                
            $datas        = $datas_u->union($t_datas)->orderBy('created_at','asc')->get();
            for($i=0; $i<count($datas); $i++) {

                $datas[$i]->reward_date = date('d M Y',strtotime($datas[$i]->created_at));
                
                if($datas[$i]->point_type=='referral') {
                    $user                   = $this->userRepository->findWithoutFail($datas[$i]->user_id)->name;
                    $datas[$i]->point_type  = ucfirst($datas[$i]->point_type).' Points for '.$user;
                    $datas[$i]->purchase_no = '';
                    $datas[$i]->purchase_amount = '';
                    $datas[$i]->points      = 'E - '.$datas[$i]->points;
                    $datas[$i]->trans_url   = '#';

                } elseif($datas[$i]->point_type=='purchase') {
                    
                    $sales_invoice                  = DB::table('sales_invoice')->where('id',$datas[$i]->sales_code)->first();
                    if($sales_invoice) {
                        $datas[$i]->purchase_no     = $sales_invoice->sales_code;
                        $datas[$i]->purchase_amount = number_format($sales_invoice->sales_total_amount,2,'.','');
                    }
                    $datas[$i]->points              = 'E - '.$datas[$i]->points;
                    $datas[$i]->trans_url           = route('salesInvoice.show',$datas[$i]->sales_code);
                    
                } elseif($datas[$i]->point_type=='usage') {
                    
                    if($datas[$i]->order_type=='sales_invoice')
                    {
                        $sales_invoice                  = DB::table('sales_invoice')->where('id',$datas[$i]->sales_code)->first();
                        if($sales_invoice) {
                            $datas[$i]->purchase_no     = $sales_invoice->sales_code;
                            $datas[$i]->purchase_amount = number_format($sales_invoice->sales_total_amount,2,'.','');
                        }
                        $datas[$i]->points              = 'U - '.$datas[$i]->points;
                        $datas[$i]->trans_url           = route('salesInvoice.show',$datas[$i]->sales_code);
                    } 
                    else if($datas[$i]->order_type=='online_order')
                    {
                        $online_order               = DB::table('orders')->where('id',$datas[$i]->sales_code)->first(); 
                        $datas[$i]->purchase_no     = $online_order->id;
                        $datas[$i]->purchase_amount = number_format($online_order->order_amount,'2','.','');
                        $datas[$i]->points          = 'U - '.$datas[$i]->points;
                        $datas[$i]->trans_url       = route('orders.show',$datas[$i]->sales_code);
                    }
                    
                    
                } elseif($datas[$i]->point_type=='online_order') {
                    
                    $online_order               = DB::table('orders')->where('id',$datas[$i]->sales_code)->first(); 
                    $datas[$i]->purchase_no     = $online_order->id;
                    $datas[$i]->purchase_amount = number_format($online_order->order_amount,'2','.','');
                    $datas[$i]->points          = 'E - '.$datas[$i]->points;
                    $datas[$i]->trans_url       = route('orders.show',$datas[$i]->sales_code);
                }
                
                
                $datas[$i]->point_type      = ucfirst($datas[$i]->point_type);
                
            }
            $market       = $this->marketRepository->findWithoutFail($market_id); 
            
            switch ($type) {

                case 'export':
                    
                    //return Excel::download(new WastageExport($start_date,$end_date,$product_id), 'wastage_report.xlsx');
                
                break;

                case 'print':
                    
                    $pdf = PDF::loadView('reports.export.party_reward_report', compact('datas','start_date','end_date','market'));
                    $filename = setting('app_name').'.pdf';
                    return $pdf->stream($filename);
                
                break;

                case 'download':
                    
                    $pdf = PDF::loadView('reports.export.party_reward_report', compact('datas','start_date','end_date','market'));
                    $filename = setting('app_name').'.pdf';
                    return $pdf->download($filename);
                
                break;
                
            }
            
        endif;
        
        
        if($report_type=='product-stock-report-by-item') :
            
                $product_id          = $request->product;
                $start_date          = $request->start_date;
                $end_date            = $request->end_date;
            
                $data = InventoryTrack::select(
                    'inventory_track.id',
                    'inventory_track.inventory_track_date',
                    'inventory_track.inventory_track_category',
                    'inventory_track.inventory_track_type',
                    'inventory_track.inventory_track_product_quantity',
                    'inventory_track.inventory_track_product_uom',
                    'inventory_track.inventory_track_description'
                )->where('inventory_track_product_id',$product_id);
                /*if($start_date!='' & $end_date!='') {
                    $data->whereBetween('inventory_track.inventory_track_date', [$start_date, $end_date]);
                }*/
                $datas = $data->get();
                $product = $this->productRepository->findWithoutFail($product_id);
                $stocks[] = 0;
                for($i=0; $i<count($datas); $i++) {
                   
                   if($datas[$i]->inventory_track_type=='add') {
                        if($datas[$i]->inventory_track_product_uom==$product->unit) {
                            $stock = array_sum($stocks) + $datas[$i]->inventory_track_product_quantity;    
                        } elseif($datas[$i]->inventory_track_product_uom==$product->secondary_unit) {
                            $stock = array_sum($stocks) + ($datas[$i]->inventory_track_product_quantity / $product->conversion_rate);
                        }
                   } else if($datas[$i]->inventory_track_type=='reduce') {
                        if($datas[$i]->inventory_track_product_uom==$product->unit) {
                            $stock = array_sum($stocks) - $datas[$i]->inventory_track_product_quantity;    
                        } elseif($datas[$i]->inventory_track_product_uom==$product->secondary_unit) {
                            $stock = array_sum($stocks) - ($datas[$i]->inventory_track_product_quantity / $product->conversion_rate);
                        }
                        //$stock = array_sum($stocks) - $datas[$i]->inventory_track_product_quantity;
                   }
                   //dd($datas);
                   $datas[$i]->inventory_closing_stock  = $stock.' '.$product->unit; 
                   $datas[$i]->inventory_track_category = ucfirst(str_replace("_"," ",$datas[$i]->inventory_track_category));
                   ($datas[$i]->inventory_track_type=='add') ? $operator='' : $operator='-';
                   $datas[$i]->quantity                 = $operator.$datas[$i]->inventory_track_product_quantity.' '.$datas[$i]->inventory_track_product_uom;
                   unset($stocks);
                   $stocks[] = $stock;
                }
            
            switch ($type) {

                case 'export':
                    
                    //return Excel::download(new WastageExport($start_date,$end_date,$product_id), 'wastage_report.xlsx');
                
                break;

                case 'print':
                    
                    $pdf = PDF::loadView('reports.export.product_stock_report_by_item', compact('datas','start_date','end_date','product'));
                    $filename = setting('app_name').'.pdf';
                    return $pdf->stream($filename);
                
                break;

                case 'download':
                    
                    $pdf = PDF::loadView('reports.export.product_stock_report_by_item', compact('datas','start_date','end_date','product'));
                    $filename = setting('app_name').'.pdf';
                    return $pdf->download($filename);
                
                break;
                
            }
            
        endif; 
        
        if($report_type=='product-purchase-history') :
            
            $product_id          = $request->product;
            $start_date          = $request->start_date;
            $end_date            = $request->end_date;
            
            $data = InventoryTrack::where('inventory_track_product_id',$product_id);
            
            if($start_date!='' & $end_date!='') {
                $data->whereDate('inventory_track_date','>=',$start_date)->whereDate('inventory_track_date','<=',$end_date);
            }

            $datas = $data->get();
            $product = $this->productRepository->findWithoutFail($product_id);
            $stocks[] = 0;
            for($i=0; $i<count($datas); $i++) {
               
               switch($datas[$i]->inventory_track_category){
                   
                   case 'opening_stock':
                       
                       $datas[$i]->purchase_no     = 'Opening Stock';
                       $datas[$i]->purchase_amount = '';
                       
                   break;
                   
                   case 'added_stock':
                       
                       $datas[$i]->purchase_no     = 'Manualy Added Stock';
                       $datas[$i]->purchase_amount = '';
                       
                   break;
                   
                   case 'sales_stock':
                       
                       $sales_details = DB::table('sales_invoice_detail')
                                                ->join('sales_invoice','sales_invoice.id','=','sales_invoice_detail.sales_invoice_id')
                                                ->where('sales_invoice_detail.id',$datas[$i]->purchase_invoice_id)->first();
                       $datas[$i]->purchase_no     = $sales_details->sales_code;
                       $datas[$i]->purchase_amount = $sales_details->sales_detail_amount;
                       $datas[$i]->transaction_url = route('salesInvoice.show',$sales_details->id);
                       
                   break;
                   
                   case 'purchase_stock':
                       
                       $purchase_details = DB::table('purchase_invoice_detail')
                                                ->join('purchase_invoice','purchase_invoice.id','=','purchase_invoice_detail.purchase_invoice_id')
                                                ->where('purchase_invoice_detail.id',$datas[$i]->purchase_invoice_id)->first();
                       $datas[$i]->purchase_no     = $purchase_details->purchase_code;
                       $datas[$i]->purchase_amount = $purchase_details->purchase_detail_amount;
                       $datas[$i]->transaction_url = route('purchaseInvoice.show',$purchase_details->id);
                       
                   break;
                   
                   case 'sales_return_stock':
                       
                       $sales_details = DB::table('sales_return_detail')
                                                ->join('sales_return','sales_return.id','=','sales_return_detail.sales_return_id')
                                                ->where('sales_return_detail.id',$datas[$i]->purchase_invoice_id)->first();
                       $datas[$i]->purchase_no     = $sales_details->sales_code;
                       $datas[$i]->purchase_amount = $sales_details->sales_detail_amount;
                       $datas[$i]->transaction_url = route('salesReturn.show',$sales_details->id);
                       
                   break;
                   
                   case 'purchase_return_stock':
                       
                       $purchase_details = DB::table('purchase_return_detail')
                                                ->join('purchase_return','purchase_return.id','=','purchase_return_detail.purchase_return_id')
                                                ->where('purchase_return_detail.id',$datas[$i]->purchase_invoice_id)->first();
                       $datas[$i]->purchase_no     = $purchase_details->purchase_code;
                       $datas[$i]->purchase_amount = $purchase_details->purchase_detail_amount;
                       $datas[$i]->transaction_url = route('purchaseReturn.show',$purchase_details->id);
                       
                   break;
                   
                   case 'online_stock':
                       
                       $online_order = DB::table('product_orders')
                                                ->join('orders','orders.id','=','product_orders.order_id')
                                                ->where('orders.id',$datas[$i]->purchase_invoice_id)->first();
                       $datas[$i]->purchase_no     = $online_order->id;
                       $datas[$i]->purchase_amount = $online_order->order_amount;
                       $datas[$i]->transaction_url = route('orders.show',$online_order->id);
                       
                   break;
                   
                   case 'online_return_stock':
                       
                   break;
                   
               }
               
            }
            
           switch ($type) {

                case 'export':
                    
                    //return Excel::download(new WastageExport($start_date,$end_date,$product_id), 'wastage_report.xlsx');
                
                break;

                case 'print':
                    
                    $pdf = PDF::loadView('reports.export.product_purchase_history', compact('datas','start_date','end_date','product'));
                    $filename = setting('app_name').'.pdf';
                    return $pdf->stream($filename);
                
                break;

                case 'download':
                    
                    $pdf = PDF::loadView('reports.export.product_purchase_history', compact('datas','start_date','end_date','product'));
                    $filename = setting('app_name').'.pdf';
                    return $pdf->download($filename);
                
                break;
                
            } 
        
        endif;    
    }
}
