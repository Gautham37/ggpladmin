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

    /**
  * @var UploadRepository
  */
    private $uploadRepository;

    public function __construct(ExpensesCategoryRepository $expensesCatRepo, UploadRepository $uploadRepo, PaymentModeRepository $paymentMRepo, ProductRepository $productRepo, PurchaseInvoiceRepository $purchaseInvoiceRepo, SalesInvoiceRepository $salesInvoiceRepo, PaymentInRepository $paymentInRepo, PaymentOutRepository $paymentOutRepo, MarketRepository $marketRepo, PurchaseReturnRepository $purchaseReturnRepo, SalesReturnRepository $salesReturnRepo, RoleRepository $roleRepo, TransactionRepository $transactionRepo, LoyalityPointsRepository $loyalityPointsRepo, LoyalityPointUsageRepository $loyalityPointUsageRepo, InventoryRepository $inventoryRepo)
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
                        } elseif($transaction->category=='online') {
                            return '<a href="'.route('orders.show',$transaction->order->id).'">'.$transaction->order->order_code.'</a>';
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

                            if($transaction->category=='online') {
                                $paid  = 0;
                                $due   = 0;
                                $total = $transaction->order->order_amount;    
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
                        }  elseif($transaction->category=='online') {
                            return '<a href="'.route('orders.show',$transaction->order->id).'">'.$transaction->order->order_code.'</a>';
                        }
                    })
                    ->addColumn('total', function($transaction){
                        return number_format($transaction->amount,'2','.','');
                    })
                    ->addColumn('credit', function($transaction){
                        return ($transaction->type=='credit') ? setting('default_currency').number_format($transaction->amount,'2','.','') : '' ;
                    })
                    ->addColumn('debit', function($transaction){
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
                    ->rawColumns(['date','transaction_no','status'])
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
                            }  elseif($inventory->category=='online') {
                                return '<a href="'.route('orders.show',$inventory->productorderitem->order->id).'">'.$inventory->productorderitem->order->order_code.'</a>';
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
                            } elseif($inventory->category=='online') {
                                return number_format($inventory->productorderitem->amount,'2','.','');
                            }
                        }) 
                        ->addColumn('quantity', function($inventory){
                            return number_format($inventory->quantity,3,'.','').' '.$inventory->uom->name;
                        }) 
                        ->rawColumns(['date','transaction_no'])
                    ->make(true);

            if(isset($type)) {

                $datas = $table->getData()->data;
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
            return view('reports.sales_summary_report')->with('report_type',$report_type);
        endif;

        if($report_type=='party-statement') : 
            $markets = $this->marketRepository->pluck('name','id');
            $markets->prepend("Select Party",0);                 
            return view('reports.party_statement_report')->with('report_type',$report_type)->with('markets',$markets);
        endif;

        if($report_type=='daybook') : 
            return view('reports.daybook')->with('report_type',$report_type);
        endif;
      

        //Item Reports

        if($report_type=='stock-summary-report') :
            return view('reports.stock_summary')->with('report_type',$report_type);
        endif;

        if($report_type=='rate-list') :
            $customer_groups = DB::table('customer_groups')->get();   
            return view('reports.rate_list')->with('report_type',$report_type)->with('customer_groups',$customer_groups);
        endif;

        if($report_type=='item-sales-summary') :
            return view('reports.item_sales_summary')->with('report_type',$report_type);
        endif;

        if($report_type=='low-stock-summary') :
            return view('reports.low_stock_summary')->with('report_type',$report_type);
        endif;

        if($report_type=='stock-detail-report') :
            $products = $this->productRepository->pluck('name','id');
            $products->prepend("Select Item",0); 
            return view('reports.stock_detail_report')->with('report_type',$report_type)->with('products',$products);
        endif;

        if($report_type=='item-report-by-party') :
            $markets = $this->marketRepository->pluck('name','id');
            $markets->prepend("Select Party",0); 
            return view('reports.item_report_by_party')->with('report_type',$report_type)->with('markets',$markets);
        endif;

        //Item Reports

        /*Transaction*/
        
        if($report_type=='bill-wise-profit') : 
            return view('reports.bill_wise_profit')->with('report_type',$report_type);
        endif;

        /*Transaction*/

        /*Expense Report*/
        
        if($report_type=='expenses-transaction-report') : 
            $expense_category  = $this->expensesCategoryRepository->pluck('name','id');
            $expense_category->prepend("Select Expense Category",0);
            return view('reports.expenses_transaction_report')->with('report_type',$report_type)->with('expense_category',$expense_category);
        endif;

        if($report_type=='expenses-category-report') : 
            return view('reports.expense_category_report')->with('report_type',$report_type);
        endif;
  
        /*Expense Report*/

        /*Party Report*/

        if($report_type=='party-wise-outstanding') : 
            return view('reports.party_wise_outstanding')->with('report_type',$report_type);
        endif;

        if($report_type=='party-report-by-item') :
            $products = $this->productRepository->pluck('name','id');
            $products->prepend("Select Item",0); 
            return view('reports.party_report_by_item')->with('report_type',$report_type)->with('products',$products);
        endif;

        /*Party Report*/ 
        
         /*Products Report*/

         if($report_type=='products-report') : 
            $products = $this->productRepository->pluck('name','id');
            $products->prepend("Select Product",0);     
            return view('reports.products_report')->with('report_type',$report_type)->with('products',$products);
        endif;
        
        if($report_type=='popular-products-report') : 
            
            return view('reports.popular_products_report')->with('report_type',$report_type);
        endif;

        if($report_type=='profitable-products-report') : 
            return view('reports.profitable_products_report')->with('report_type',$report_type);
        endif;

        /*Customers Report*/
        if($report_type=='customers-report') : 
            // $market = $this->marketRepository->where('type',1)->pluck('name','id');
            // $market->prepend("Please Select",0);     
            return view('reports.customers_report')->with('report_type',$report_type);
        endif;

          /*staff log Report*/

          if($report_type=='staff-login-report') : 
            $users = User::whereHas('roles', function($q){$q->where('name','!=','client');})->pluck('name','id');
            $users->prepend("Select User",0); 
            $role  = $this->roleRepository->where('name','!=','client')->pluck('name','name');
            $role->prepend("Select Role",''); 
            return view('reports.staff_login_report')->with('report_type',$report_type)->with('users',$users)->with('role',$role);
        endif;

        /*Stock purchase Report*/

        if($report_type=='stock-purchase-report') :
             return view('reports.stock_purchase_report')->with('report_type',$report_type);
        endif;
        
        /*Delivery Report*/

        if($report_type=='delivery-report') :
             return view('reports.delivery_report')->with('report_type',$report_type);
        endif;
        
         if($report_type=='wastage-report') :
             $products = $this->productRepository->pluck('name','id');
            $products->prepend("Select Item",0); 
            return view('reports.wastage_report')->with('report_type',$report_type)->with('products',$products);
        endif;


    }


    public function salesSummaryReport(Request $request) {
        $start_date    = $request->start_date;
        $end_date      = $request->end_date;
        
        $data    = DB::table('sales_invoice')
                            ->join('markets', 'sales_invoice.market_id','=','markets.id')
                            ->leftJoin('customer_groups', 'markets.customer_group','=','customer_groups.id')
                            ->leftJoin('user_markets', 'markets.id','=','user_markets.market_id')
                            ->leftJoin('users', 'user_markets.user_id','=','users.id')
                            ->leftJoin('customer_levels', 'users.level','=','customer_levels.id')
                            ->leftJoin('sales_invoice_detail', 'sales_invoice.id','=','sales_invoice_detail.sales_invoice_id')
                            ->leftJoin('payment_mode', 'sales_invoice.sales_payment_method','=','payment_mode.id')
                            ->select('sales_invoice.*','markets.name as market_id','customer_groups.name as party_group','customer_levels.name as reward_level',DB::raw('COUNT(sales_invoice_detail.sales_invoice_id) as no_of_items'),'payment_mode.name as payment_type')
                             ->groupBy('sales_invoice_detail.sales_invoice_id');

        // $data    = DB::table('sales_invoice')
        //                     ->join('markets', 'sales_invoice.market_id','=','markets.id')
        //                     ->select('sales_invoice.*','markets.name as market_id');
        if($start_date!='' & $end_date!='') {
            $data->whereBetween('sales_invoice.sales_date', [$start_date, $end_date]);
        }                    
        $sales_data = $data->get();
        
        foreach ($sales_data as $key => $value) {
              if($value->sales_cash_paid >= $value->sales_total_amount) {
                     $sales_data[$key]->payment_status  = 'Paid';
                } elseif($value->sales_cash_paid > 0) {
                     $sales_data[$key]->payment_status  = 'Partially Paid';
                } else {
                     $sales_data[$key]->payment_status  = 'Unpaid';
                }
            $sales_data[$key]->sales_total_amount = setting('default_currency').''.number_format($sales_data[$key]->sales_total_amount,2);
        }
        return Datatables::of($sales_data)->make(true);
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

    public function dayBook(Request $request) {
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
    }

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
                 ->leftJoin('payments', 'payments.id','=','orders.payment_id')
                 ->leftJoin('order_statuses', 'order_statuses.id','=','orders.order_status_id')
                ->select('delivery_addresses.*','users.name','orders.delivery_distance','orders.driver_id','orders.id as order_id','payments.method','payments.status as payment_status','order_statuses.status as order_status')
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


        return Datatables::of($datas)->make(true);
        }
    }
    
    public function wastageReport(Request $request) {

        $product_id          = $request->product;
        $start_date          = $request->start_date;
        $end_date            = $request->end_date;
        if($request->ajax()) {

            $data = InventoryTrack::select(
                'inventory_track.id',
                'inventory_track.inventory_track_date',
                'inventory_track.inventory_track_product_id',
                'inventory_track.inventory_track_category',
                'inventory_track.inventory_track_type',
                'inventory_track.inventory_track_product_quantity',
                'inventory_track.inventory_track_product_uom',
                'inventory_track.inventory_track_description'
            )->where('inventory_track_usage',2);
            if($product_id!='' && $product_id!=0) {
                $data->where('inventory_track_product_id',$product_id);
            }

            if($start_date!='' & $end_date!='') {
            $data->whereBetween('inventory_track.inventory_track_date', [$start_date, $end_date]);
        } 

            $datas = $data->get();
            $stocks[] = 0;
            for($i=0; $i<count($datas); $i++) {
               
               $product = $this->productRepository->findWithoutFail($datas[$i]->inventory_track_product_id);
               
               if($datas[$i]->inventory_track_type=='add') {
                    $stock = array_sum($stocks) + $datas[$i]->inventory_track_product_quantity;
               } else if($datas[$i]->inventory_track_type=='reduce') {
                    $stock = array_sum($stocks) - $datas[$i]->inventory_track_product_quantity;
               }
               $datas[$i]->inventory_closing_stock  = $stock.' '.$product->unit; 
               $datas[$i]->inventory_track_category = ucfirst(str_replace("_"," ",$datas[$i]->inventory_track_category));
               //($datas[$i]->inventory_track_type=='add') ? $operator='' : $operator='-';
               $datas[$i]->quantity                 = $datas[$i]->inventory_track_product_quantity.' '.$datas[$i]->inventory_track_product_uom;
               $datas[$i]->inventory_track_product  = $product->name;
                $datas[$i]->item_code  = $product->bar_code; 
                $datas[$i]->hsn_code  = $product->hsn_code; 
                 $datas[$i]->inventory_track_description  = $datas[$i]->inventory_track_description; 
               $datas[$i]->cost                     = setting('default_currency').number_format($datas[$i]->inventory_track_product_quantity * $product->purchase_price,'2','.','');
               unset($stocks);
               $stocks[] = $stock;
            }
            //dd($datas->reverse());
            return Datatables::of($datas->reverse())->make(true);

        }

    }


    public function exportReport(Request $request) {
        $type           = $request->type;
        $report_type    = $request->report_type;
        
        if($report_type=='sales-summary') :

            $start_date     = $request->start_date;
            $end_date       = $request->end_date;
            $sales_data     =  DB::table('sales_invoice')
                            ->join('markets', 'sales_invoice.market_id','=','markets.id')
                            ->leftJoin('customer_groups', 'markets.customer_group','=','customer_groups.id')
                            ->leftJoin('user_markets', 'markets.id','=','user_markets.market_id')
                            ->leftJoin('users', 'user_markets.user_id','=','users.id')
                            ->leftJoin('customer_levels', 'users.level','=','customer_levels.id')
                            ->leftJoin('sales_invoice_detail', 'sales_invoice.id','=','sales_invoice_detail.sales_invoice_id')
                            ->leftJoin('payment_mode', 'sales_invoice.sales_payment_method','=','payment_mode.id')
                            ->select('sales_invoice.*','markets.name as market_id','customer_groups.name as party_group','customer_levels.name as reward_level',DB::raw('COUNT(sales_invoice_detail.sales_invoice_id) as no_of_items'),'payment_mode.name as payment_type')
                            ->whereBetween('sales_invoice.sales_date', [$start_date, $end_date])
                            ->groupBy('sales_invoice_detail.sales_invoice_id')->get();
            $total_transaction = DB::table('sales_invoice')->whereBetween('sales_date', [$start_date, $end_date])->sum('sales_total_amount');

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

                   $datas[$i]->credit   = number_format($invoice->purchase_total_amount,2); 
                   $purchase_debit      = DB::table('transaction_track')
                                                ->where('transaction_number',$datas[$i]->transaction_number)
                                                ->where('transaction_track_category','purchase')
                                                ->where('transaction_track_type','debit')
                                                ->whereBetween('transaction_track.transaction_track_date', [$start_date, $end_date])
                                                ->sum('transaction_track_amount');
                   $datas[$i]->debit     = ($purchase_debit > 0) ? number_format($purchase_debit,2):'';

               }

               if($datas[$i]->transaction_track_category=='sales') {
                   $invoice = $this->salesInvoiceRepository->findWithoutFail($datas[$i]->transaction_number);
                   $datas[$i]->transaction_track_date  = date('Y-m-d',strtotime($invoice->sales_date));

                   $sales_credit            = DB::table('transaction_track')
                                                    ->where('transaction_number',$datas[$i]->transaction_number)
                                                    ->where('transaction_track_category','sales')
                                                    ->where('transaction_track_type','credit')
                                                    ->whereBetween('transaction_track.transaction_track_date', [$start_date, $end_date])
                                                    ->sum('transaction_track_amount'); 
                   $datas[$i]->credit       = ($sales_credit > 0) ? number_format($sales_credit,2) :  '' ; 
                   $datas[$i]->debit        = number_format($invoice->sales_total_amount,2);
               }

               if($datas[$i]->transaction_track_category=='payment_in') {
                   $invoice = $this->paymentInRepository->findWithoutFail($datas[$i]->transaction_number);
                   $datas[$i]->transaction_track_date  = date('Y-m-d',strtotime($invoice->payment_in_date)); 
                   $datas[$i]->credit                  = number_format($invoice->payment_in_amount,2); 
                   $datas[$i]->debit                   = '';
               }

               if($datas[$i]->transaction_track_category=='payment_out') {
                   $invoice = $this->paymentOutRepository->findWithoutFail($datas[$i]->transaction_number);
                   $datas[$i]->transaction_track_date  = date('Y-m-d',strtotime($invoice->payment_out_date));
                   $datas[$i]->credit                  = '';
                   $datas[$i]->debit                   = number_format($invoice->payment_out_amount,2); 
                   
               }

               $datas[$i]->transaction_track_category = ucfirst( str_replace('_', ' ', $datas[$i]->transaction_track_category) );
               $datas[$i]->party = $this->marketRepository->findWithoutFail($datas[$i]->transaction_track_market_id)->name;
            }

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

        endif;

        if($report_type=='stock-summary-report') :

            $datas = $this->productRepository->get();
            foreach ($datas as $key => $value) {
                $datas[$key]->purchasing_price = number_format($datas[$key]->purchase_price,2, '.', '');
                $datas[$key]->selling_price    = number_format($datas[$key]->price,2, '.', '');
                $datas[$key]->stock_quantity   = $datas[$key]->stock.' '.$datas[$key]->unit;
                $datas[$key]->stock_value      = number_format($datas[$key]->stock * $datas[$key]->purchase_price,2, '.', '');
                $stocks[]                      = $datas[$key]->stock_value;
                 $datas[$key]->created   = date("Y-m-d",strtotime($value->created_at));
                 $profit = $value->price - $value->purchase_price;
                 if($value->purchase_price>0){
                 $profit_percentage = ($profit*100)/$value->purchase_price;
                 }else{ $profit_percentage =100; }
                 if($profit_percentage>0){ $profit_percent = $profit_percentage; } else{ $profit_percent = 0; }
                  $datas[$key]->profit_percentage   = number_format($profit_percent,2).'%';
            }
            $stock_value                       = number_format(array_sum($stocks),2, '.', '');

            switch ($type) {

                case 'export':
                    
                    // $pdf = PDF::loadView('reports.export.stock_summary_report', compact('datas','stock_value'));
                    // $filename = setting('app_name').'.pdf';
                    // return $pdf->stream($filename);
                return Excel::download(new StockSummaryExport, 'stock_summary.xlsx');
                
                break;

                case 'print':
                    
                    $pdf = PDF::loadView('reports.export.stock_summary_report', compact('datas','stock_value'));
                    $filename = setting('app_name').'.pdf';
                    return $pdf->stream($filename);
                
                break;

                case 'download':
                    
                    $pdf = PDF::loadView('reports.export.stock_summary_report', compact('datas','stock_value'));
                    $filename = setting('app_name').'.pdf';
                    return $pdf->download($filename);
                
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
                    
                    // $pdf = PDF::loadView('reports.export.rate_list_report', compact('datas','customer_groups'));
                    // $filename = setting('app_name').'.pdf';
                    // return $pdf->stream($filename);
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

            switch ($type) {

                case 'export':
                    
                    // $pdf = PDF::loadView('reports.export.item_sales_summary_report', compact('datas','start_date','end_date'));
                    // $filename = setting('app_name').'.pdf';
                    // return $pdf->stream($filename);
                return Excel::download(new ItemSalesExport($start_date,$end_date), 'item_sales.xlsx');
                
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


        endif;


        if($report_type=='low-stock-summary') :

            $start_date    = $request->start_date;
            $end_date      = $request->end_date;
            $datas = $this->productRepository->whereRaw('low_stock_unit >= stock')->get();
            foreach ($datas as $key => $value) {
             // if($datas[$key]->low_stock_unit >= $datas[$key]->stock) {
                $datas[$key]->stock_quantity  = $datas[$key]->stock.' '.$datas[$key]->unit;
                $datas[$key]->low_stock_level = $datas[$key]->low_stock_unit.' '.$datas[$key]->unit;
                $datas[$key]->stock_value     = number_format($datas[$key]->stock * $datas[$key]->purchase_price,2,'.','');
                $stock_values[] = $datas[$key]->stock_value; 
            //   } else {
            //     unset($datas[$key]);
            //   }
            }
            $stock_value = number_format(array_sum($stock_values),2, '.', '');

            switch ($type) {

                case 'export':
                    
                    // $pdf = PDF::loadView('reports.export.low_stock_summary_report', compact('datas','start_date','end_date','stock_value'));
                    // $filename = setting('app_name').'.pdf';
                    // return $pdf->stream($filename);
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


        endif;

        if($report_type=='expenses-transaction-report') :

            $expense_category    = $request->expense_category;
            $start_date          = $request->start_date;
            $end_date            = $request->end_date;

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
              $datas[$key]->expense_total_amount = number_format($value->expense_total_amount,2,'.','');
              $total[] =   $datas[$key]->expense_total_amount;  
            }
            $total_expense = number_format(array_sum($total),2,'.','');

            switch ($type) {

                case 'export':
                    
                  // $pdf = PDF::loadView('reports.export.expense_transaction_report', compact('datas','start_date','end_date','total_expense'));
                  // $filename = setting('app_name').'.pdf';
                  // return $pdf->stream($filename);
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


        endif;

        if($report_type=='expenses-category-report') :

            $start_date          = $request->start_date;
            $end_date            = $request->end_date;

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
                $datas[$key]->expense_total_amount = number_format($value->expense_total_amount,2,'.','');
                $total[] = $datas[$key]->expense_total_amount;
              }
            }
            $total_expense = number_format(array_sum($total),2,'.','');

            switch ($type) {

                case 'export':
                    
                  // $pdf = PDF::loadView('reports.export.expense_category_report', compact('datas','start_date','end_date','total_expense'));
                  // $filename = setting('app_name').'.pdf';
                  // return $pdf->stream($filename);
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


        endif;

        if($report_type=='party-wise-outstanding') :

            $datas = DB::table('markets')
                                  ->leftJoin('transaction_track','markets.id','=','transaction_track.transaction_track_market_id')
                                  ->leftJoin('customer_groups','markets.customer_group','=','customer_groups.id')
                                  ->select('markets.*','customer_groups.name as party_group',\DB::raw('count(transaction_track.id) as total_no_transactions'),\DB::raw('max(transaction_track.transaction_track_date) as last_transaction'))
                                  ->groupBy('markets.id')->get();
            foreach ($datas as $key => $value) {
                $datas[$key]->balance = number_format($datas[$key]->balance,2,'.',''); 
            } 

            switch ($type) {

                case 'export':
                    
                  // $pdf = PDF::loadView('reports.export.party_wise_outstanding_report', compact('datas'));
                  // $filename = setting('app_name').'.pdf';
                  // return $pdf->stream($filename);
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


        endif;

        if($report_type=='party-report-by-item') :

            
            $start_date    = $request->start_date;
            $end_date      = $request->end_date;
            $product       = $request->product;
            $product1       = $request->product;

            $datas = $this->marketRepository->get();
            foreach ($datas as $key => $value) {

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

            switch ($type) {

                case 'export':
                    
                  // $pdf = PDF::loadView('reports.export.party_report_by_item_report', compact('datas','start_date','end_date','product'));
                  // $filename = setting('app_name').'.pdf';
                  // return $pdf->stream($filename);
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


        endif;


        if($report_type=='item-report-by-party') :

            $start_date    = $request->start_date;
            $end_date      = $request->end_date;
            $market        = $request->market;
            $market1        = $request->market;

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
                $datas[$key]->sales_amount      = number_format(array_sum($sales_invoice_amount),2,'.','');
                $datas[$key]->purchase_quantity = $purchase_quantity;
                $datas[$key]->purchase_amount   = number_format(array_sum($purchase_invoice_amount),2,'.','');
                unset($sales_invoice_amount);
                unset($purchase_invoice_amount);
            } 

            $market = $this->marketRepository->findWithoutFail($market)->name;

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
                $datas[$key]->purchase_amount    = number_format(array_sum($total_purchase),2,'.','');
                $datas[$key]->profit_amount      = number_format($value->sales_total_amount - array_sum($total_purchase),2,'.','');
                $overall_profit[] = $datas[$key]->profit_amount;
                $datas[$key]->sales_total_amount = number_format($value->sales_total_amount,2,'.','');
                unset($total_purchase);
            }
            $profit_amount = number_format(array_sum($overall_profit),2,'.','');

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


        endif;
        
        if($report_type=='products-report') :

            $start_date    = $request->start_date;
            $end_date      = $request->end_date;
            $product_id    = $request->product;
          
            $data    = DB::table('products')
                            ->leftJoin('product_orders', 'product_orders.product_id','=','products.id')
                            ->select('products.name','products.bar_code','products.hsn_code','products.purchase_price','products.price','products.stock','products.unit',DB::raw('(products.stock * products.purchase_price) as stockvalue'),'product_orders.quantity as order_quantity',\DB::raw('Date(product_orders.created_at) AS ordered_date'))
                            ->where('order_id','!=','NULL');
            if($product_id!=0) {
                $data->where('products.id',$product_id);
            }   
        
            if($start_date!='' & $end_date!='') {
                $data->whereDate('product_orders.created_at','>=',$start_date)->whereDate('product_orders.created_at','<=',$end_date);
            } 
            $items = $data->get();

            $data1    = DB::table('products')
                            ->leftJoin('sales_invoice_detail', 'sales_invoice_detail.sales_detail_product_id','=','products.id')
                            ->select('products.name','products.bar_code','products.hsn_code','products.purchase_price','products.price','products.stock','products.unit',DB::raw('(products.stock * products.purchase_price) as stockvalue'),'sales_invoice_detail.sales_detail_quantity as order_quantity',\DB::raw('Date(sales_invoice_detail.created_at) AS ordered_date'))
                            ->where('sales_invoice_id','!=','NULL');
        
            if($product_id!=0) {
                $data1->where('products.id',$product_id);
            } 
        
            if($start_date!='' & $end_date!='') {
                $data1->whereDate('sales_invoice_detail.created_at','>=',$start_date)->whereDate('sales_invoice_detail.created_at','<=',$end_date);
            }   
            $items1 = $data1->get();
                          
            $datas = array_merge($items->toArray(), $items1->toArray());
            
             if(count($datas)>0){
                foreach ($datas as $key => $value) {
                    $datas[$key]->purchasing_price = number_format($datas[$key]->purchase_price,2, '.', '');
                    $datas[$key]->selling_price    = number_format($datas[$key]->price,2, '.', '');
                    $datas[$key]->stock_quantity   = $datas[$key]->stock.' '.$datas[$key]->unit;
                    $datas[$key]->stock_value      = number_format($datas[$key]->stock * $datas[$key]->purchase_price,2, '.', '');
                    $stocks[]                      = $datas[$key]->stock_value;

                     if($datas[$key]->order_quantity==''){
                     $datas[$key]->ordered_quantity   = '';
                    }else{
                      $datas[$key]->ordered_quantity   = $datas[$key]->order_quantity.' '.$datas[$key]->unit;
                    }
                }
                $stock_value                       = number_format(array_sum($stocks),2, '.', '');
                
             }

            switch ($type) {

                case 'export':
                    
                    // $pdf = PDF::loadView('reports.export.stock_summary_report', compact('datas','stock_value'));
                    // $filename = setting('app_name').'.pdf';
                    // return $pdf->stream($filename);
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


        endif;


         if($report_type=='popular-products-report') :

           $start_date    = $request->start_date;
            $end_date      = $request->end_date;

            $datas = DB::select('SELECT id, name,bar_code,hsn_code,purchase_price,price,stock,unit,stockvalue, SUM(total_quantity) AS ordered_quantity FROM(SELECT a.id,a.name,a.bar_code,a.hsn_code,a.purchase_price,a.price,a.stock,a.unit,a.stock*a.purchase_price as stockvalue, SUM(b.quantity) AS total_quantity
FROM products a JOIN product_orders b ON b.product_id = a.id GROUP BY a.id, b.product_id UNION SELECT a.id,a.name,a.bar_code,a.hsn_code,a.purchase_price,a.price,a.stock,a.unit,a.stock*a.purchase_price as stockvalue, SUM(c.sales_detail_quantity) AS total_quantity
FROM products a JOIN sales_invoice_detail c ON c.sales_detail_product_id = a.id GROUP BY a.id, c.sales_detail_product_id
) temp GROUP BY id order by total_quantity desc');

        if(count($datas)>0){
            foreach ($datas as $key => $value) {
                $datas[$key]->purchasing_price = number_format($datas[$key]->purchase_price,2, '.', '');
                $datas[$key]->selling_price    = number_format($datas[$key]->price,2, '.', '');
                $datas[$key]->stock_quantity   = $datas[$key]->stock.' '.$datas[$key]->unit;
                $datas[$key]->stock_value      = number_format($datas[$key]->stock * $datas[$key]->purchase_price,2, '.', '');
                $stocks[]                      = $datas[$key]->stock_value;
            }
            $stock_value                       = number_format(array_sum($stocks),2, '.', '');
        }

            switch ($type) {

                case 'export':
                    
                    // $pdf = PDF::loadView('reports.export.stock_summary_report', compact('datas','stock_value'));
                    // $filename = setting('app_name').'.pdf';
                    // return $pdf->stream($filename);
                return Excel::download(new PopularProductsExport($start_date,$end_date), 'popular_products.xlsx');
                
                break;

                case 'print':
                    
                    $pdf = PDF::loadView('reports.export.popular_products_report', compact('datas','start_date','end_date'));
                    $filename = setting('app_name').'.pdf';
                    return $pdf->stream($filename);
                
                break;

                case 'download':
                    
                    $pdf = PDF::loadView('reports.export.popular_products_report', compact('datas','start_date','end_date'));
                    $filename = setting('app_name').'.pdf';
                    return $pdf->download($filename);
                
                break;
                
            }


        endif;

          if($report_type=='profitable-products-report') :

           $start_date    = $request->start_date;
            $end_date      = $request->end_date;

         $datas = DB::select('SELECT id, name,bar_code,hsn_code,purchase_price,price,stock,unit,stockvalue, SUM(total_quantity) AS ordered_quantity FROM(SELECT a.id,a.name,a.bar_code,a.hsn_code,a.purchase_price,a.price,a.stock,a.unit,a.stock*a.purchase_price as stockvalue, SUM(b.quantity) AS total_quantity
FROM products a JOIN product_orders b ON b.product_id = a.id GROUP BY a.id, b.product_id UNION SELECT a.id,a.name,a.bar_code,a.hsn_code,a.purchase_price,a.price,a.stock,a.unit,a.stock*a.purchase_price as stockvalue, SUM(c.sales_detail_quantity) AS total_quantity
FROM products a JOIN sales_invoice_detail c ON c.sales_detail_product_id = a.id GROUP BY a.id, c.sales_detail_product_id
) temp where purchase_price<price GROUP BY id order by total_quantity desc');

        if(count($datas)>0){
            foreach ($datas as $key => $value) {
                $datas[$key]->purchasing_price = number_format($datas[$key]->purchase_price,2, '.', '');
                $datas[$key]->selling_price    = number_format($datas[$key]->price,2, '.', '');
                $datas[$key]->stock_quantity   = $datas[$key]->stock.' '.$datas[$key]->unit;
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

            switch ($type) {

                case 'export':
                    
                    // $pdf = PDF::loadView('reports.export.stock_summary_report', compact('datas','stock_value'));
                    // $filename = setting('app_name').'.pdf';
                    // return $pdf->stream($filename);
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
                                  ->leftJoin('transaction_track','markets.id','=','transaction_track.transaction_track_market_id')
                                  ->leftJoin('customer_groups','markets.customer_group','=','customer_groups.id')
                                  ->leftJoin('user_markets', 'markets.id','=','user_markets.market_id')
                                  ->leftJoin('users', 'user_markets.user_id','=','users.id')
                                  ->leftJoin('customer_levels', 'users.level','=','customer_levels.id')
                                  ->select('markets.*','customer_groups.name as party_group',\DB::raw('count(transaction_track.id) as total_no_transactions'),'customer_levels.name as levels','customer_levels.monthly_spend as reward_points')
                                  ->groupBy('markets.id')->where('markets.type',1);

      
         if($start_date!='' & $end_date!='') {
           // $data->whereBetween('user_log.created_at', [$start_date, $end_date]);
            $data->whereDate('markets.created_at','>=',$start_date)->whereDate('markets.created_at','<=',$end_date);
        }   

           $datas = $data->get();
           
            foreach ($datas as $key => $value) {
                $datas[$key]->address = $datas[$key]->address_line_1.' '.$datas[$key]->address_line_2;
                $datas[$key]->reward_levels = $datas[$key]->levels.' - '.$datas[$key]->reward_points;
                $datas[$key]->created_at = date('d-m-Y', strtotime($datas[$key]->created_at));
            }
           

            switch ($type) {

                case 'export':
                    
                  // $pdf = PDF::loadView('reports.export.party_wise_outstanding_report', compact('datas'));
                  // $filename = setting('app_name').'.pdf';
                  // return $pdf->stream($filename);
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


        endif;


         if($report_type=='stock-purchase-report') :

           $start_date    = $request->start_date;
            $end_date      = $request->end_date;

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
     $datas = $data2;//array_slice($data2, 0, 10);
     
    
       
        if(count($datas)>0){
            foreach ($datas as $key => $value) {
                $datas[$key]->purchasing_price = number_format($datas[$key]->purchase_price,2, '.', '');
                $datas[$key]->selling_price    = number_format($datas[$key]->price,2, '.', '');
                $datas[$key]->stock_quantity   = $datas[$key]->stock.' '.$datas[$key]->unit;
                $datas[$key]->stock_value      = number_format($datas[$key]->stock * $datas[$key]->purchase_price,2, '.', '');
                $stocks[]                      = $datas[$key]->stock_value;

                 if($datas[$key]->ordered_quantity==''){
                 $datas[$key]->ordered_quantity   = '';
                }else{
                  $datas[$key]->ordered_quantity   = $datas[$key]->ordered_quantity.' '.$datas[$key]->unit;
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
                 ->leftJoin('payments', 'payments.id','=','orders.payment_id')
                 ->leftJoin('order_statuses', 'order_statuses.id','=','orders.order_status_id')
                ->select('delivery_addresses.*','users.name','orders.delivery_distance','orders.driver_id','orders.id as order_id','payments.method','payments.status as payment_status','order_statuses.status as order_status')
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
             

            switch ($type) {

                case 'export':
                    
                    // $pdf = PDF::loadView('reports.export.stock_summary_report', compact('datas','stock_value'));
                    // $filename = setting('app_name').'.pdf';
                    // return $pdf->stream($filename);
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


        endif;
        
        if($report_type=='wastage-report') :

            $start_date     = $request->start_date;
            $end_date       = $request->end_date;
            $product_id          = $request->product;

            $data = InventoryTrack::select(
                'inventory_track.id',
                'inventory_track.inventory_track_date',
                'inventory_track.inventory_track_product_id',
                'inventory_track.inventory_track_category',
                'inventory_track.inventory_track_type',
                'inventory_track.inventory_track_product_quantity',
                'inventory_track.inventory_track_product_uom',
                'inventory_track.inventory_track_description'
            )->where('inventory_track_usage',2);
            if($product_id!='' && $product_id!=0) {
                $data->where('inventory_track_product_id',$product_id);
            }

            if($start_date!='' & $end_date!='') {
                $data->whereBetween('inventory_track.inventory_track_date', [$start_date, $end_date]);
            } 

            $datas = $data->get();
            $stocks[] = 0;
            $cost[] = 0;
            for($i=0; $i<count($datas); $i++) {
               
               $product = $this->productRepository->findWithoutFail($datas[$i]->inventory_track_product_id);
               
               if($datas[$i]->inventory_track_type=='add') {
                    $stock = array_sum($stocks) + $datas[$i]->inventory_track_product_quantity;
               } else if($datas[$i]->inventory_track_type=='reduce') {
                    $stock = array_sum($stocks) - $datas[$i]->inventory_track_product_quantity;
               }
               $datas[$i]->inventory_closing_stock  = $stock.' '.$product->unit; 
               $datas[$i]->inventory_track_category = ucfirst(str_replace("_"," ",$datas[$i]->inventory_track_category));
               //($datas[$i]->inventory_track_type=='add') ? $operator='' : $operator='-';
               $datas[$i]->quantity                 = $datas[$i]->inventory_track_product_quantity.' '.$datas[$i]->inventory_track_product_uom;
               $datas[$i]->inventory_track_product  = $product->name;
               $datas[$i]->cost                     = $datas[$i]->inventory_track_product_quantity * $product->purchase_price;
                $datas[$i]->item_code  = $product->bar_code; 
                $datas[$i]->hsn_code  = $product->hsn_code; 
                 $datas[$i]->inventory_track_description  = $datas[$i]->inventory_track_description; 
               unset($stocks);
               $cost[] = $datas[$i]->cost;
               $stocks[] = $stock;
            }

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
