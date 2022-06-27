<?php

namespace App\Http\Controllers;

use App\Repositories\OrderRepository;
use App\Repositories\PurchaseOrderRepository;
use App\Repositories\PaymentRepository;
use App\Repositories\MarketRepository;
use App\Repositories\UserRepository;
use App\Repositories\InventoryRepository;
use App\Repositories\DriverRepository;
use App\Repositories\ProductRepository;
use App\Repositories\SalesInvoiceRepository;
use App\Repositories\PurchaseInvoiceRepository;
use App\Repositories\ExpensesRepository;
use App\Repositories\AttendanceRepository;
use App\Repositories\LoyalityPointsRepository;
use App\Repositories\DriverReviewRepository;
use App\Repositories\ComplaintsRepository;
use App\Repositories\CouponRepository;
use App\Repositories\CharityRepository;
use App\Repositories\DeliveryZonesRepository;
use App\Repositories\DeliveryTrackRepository;
use App\Repositories\EmailnotificationsRepository;
use App\Models\Order;
use App\Models\PurchaseOrder;
use Illuminate\Http\Request;
use DB;
use Carbon\Carbon;
use DataTables;

class DashboardController extends Controller
{

    /** @var  OrderRepository */
    private $orderRepository;
    /** @var  PurchaseOrderRepository */
    private $purchaseOrderRepository;

    /**
     * @var UserRepository
     */
    private $userRepository;

    /** @var  MarketRepository */
    private $marketRepository;
    /** @var  PaymentRepository */
    private $paymentRepository;
    /** @var  InventoryRepository */
    private $inventoryRepository;
    /** @var  DriverRepository */
    private $driverRepository;
    
    /** @var  ProductRepository */
    private $productRepository;

    /** @var  PurchaseInvoiceRepository */
    private $purchaseInvoiceRepository;

    /** @var  ExpensesRepository */
    private $expensesRepository;

    /** @var  AttendanceRepository */
    private $attendanceRepository;

    /** @var  LoyalityPointsRepository */
    private $loyalityPointsRepository;

    /** @var  DriverReviewRepository */
    private $driverReviewRepository;

    /** @var  ComplaintsRepository */
    private $complaintsRepository;
    
    /** @var  CouponRepository */
    private $couponRepository;
    
    /** @var  CharityRepository */
    private $charityRepository;
    
    /** @var  DeliveryZonesRepository */
    private $deliveryZonesRepository;

    /** @var  DeliveryTrackRepository */
    private $deliveryTrackRepository;
    
    /** @var  EmailnotificationsRepository */
    private $emailnotificationsRepository;

    public function __construct(OrderRepository $orderRepo, PurchaseOrderRepository $purchaseOrderRepo, UserRepository $userRepo, PaymentRepository $paymentRepo, MarketRepository $marketRepo, InventoryRepository $inventoryRepo, DriverRepository $driverRepo, ProductRepository $productRepo, SalesInvoiceRepository $salesInvoiceRepo, PurchaseInvoiceRepository $purchaseInvoiceRepo, ExpensesRepository $expensesRepo, AttendanceRepository $attendanceRepo, LoyalityPointsRepository $loyalityPointsRepo, DriverReviewRepository $driverReviewRepo, ComplaintsRepository $complaintsRepo, CouponRepository $couponRepo, CharityRepository $charityRepo, DeliveryZonesRepository $deliveryZonesRepo, DeliveryTrackRepository $deliveryTrackRepo, EmailnotificationsRepository $emailnotificationsRepo)
    {
        parent::__construct();
        $this->orderRepository = $orderRepo;
        $this->purchaseOrderRepository = $purchaseOrderRepo;
        $this->userRepository = $userRepo;
        $this->marketRepository = $marketRepo;
        $this->paymentRepository = $paymentRepo;
        $this->inventoryRepository = $inventoryRepo;
        $this->driverRepository    = $driverRepo;
        $this->productRepository = $productRepo;
        $this->salesInvoiceRepository = $salesInvoiceRepo;
        $this->purchaseInvoiceRepository = $purchaseInvoiceRepo;
        $this->expensesRepository = $expensesRepo;
        $this->attendanceRepository = $attendanceRepo;
        $this->loyalityPointsRepository = $loyalityPointsRepo;
        $this->driverReviewRepository = $driverReviewRepo;
        $this->complaintsRepository = $complaintsRepo;
        $this->couponRepository = $couponRepo;
        $this->charityRepository = $charityRepo;
        $this->deliveryZonesRepository = $deliveryZonesRepo;
        $this->deliveryTrackRepository = $deliveryTrackRepo;
        $this->emailnotificationsRepository = $emailnotificationsRepo;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {   

        //party level view
        $customer_levels = DB::table('customer_levels')->get();

        //party type
        $party_types = DB::table('party_types')->get();

        //party sub type
        $party_sub_types = DB::table('party_sub_types')->get();

        //product categories
        $categories = DB::table('categories')->get();

        //product sub categories
        $subcategories = DB::table('subcategories')->get();

        //party sub type
        $party_sub_types_customer = DB::table('party_sub_types')->where('party_type_id','1')->get();

        //party stream view
        $stream = DB::table('party_streams')->get();

        //party Promocodes & discounts
        $coupons = DB::table('coupons')->get();

        //drivers
        $drivers = DB::table('drivers')->leftJoin('users', 'users.id', '=', 'drivers.user_id')->get();

        //staffs count
        $staffs_count = $this->userRepository->with("roles")->whereHas('roles', function ($q) { $q->where('name', 'manager')->orWhere('name', 'supervisor')->orWhere('name', 'worker')->orWhere('name', 'driver'); })->count();

        //sales order received from
        $sales_order_received_from = DB::table('sales_invoice')
                            ->leftJoin('sales_invoice_items', 'sales_invoice.id','=','sales_invoice_items.sales_invoice_id')
                            ->select('sales_invoice.*', DB::raw('SUM(sales_invoice_items.quantity) as total_quantity'), DB::raw('SUM(sales_invoice_items.amount) as total_amount'))
                            ->get();

        //purchase order received from
        $purchase_order_received_from = DB::table('purchase_invoice')
                            ->leftJoin('purchase_invoice_items', 'purchase_invoice.id','=','purchase_invoice_items.purchase_invoice_id')
                            ->leftJoin('markets', 'purchase_invoice.market_id','=','markets.id')
                            ->leftJoin('party_types', 'markets.type','=','party_types.id')
                            ->select('purchase_invoice.*', DB::raw('SUM(purchase_invoice_items.quantity) as total_quantity'), DB::raw('SUM(purchase_invoice_items.amount) as total_amount'))
                            ->where('party_types.name','=','Farmer')
                            ->get();

        //quotes received from
        $quotes_received_from = DB::table('quotes')
                            ->leftJoin('quote_items', 'quotes.id','=','quote_items.quote_id')
                            ->select('quotes.*', DB::raw('SUM(quote_items.quantity) as total_quantity'), DB::raw('SUM(quote_items.amount) as total_amount'))
                            ->get();

        //sales order status
        $sales_order_status = DB::table('sales_invoice')
                            ->leftJoin('sales_invoice_items', 'sales_invoice.id','=','sales_invoice_items.sales_invoice_id')
                            ->select('sales_invoice.*', DB::raw('SUM(sales_invoice_items.quantity) as total_quantity'), DB::raw('SUM(sales_invoice_items.amount) as total_amount'))
                            ->get();

        //sales order type
        $sales_order_type = DB::table('sales_invoice')
                            ->leftJoin('sales_invoice_items', 'sales_invoice.id','=','sales_invoice_items.sales_invoice_id')
                            ->leftJoin('markets', 'sales_invoice.market_id','=','markets.id')
                            ->leftJoin('party_sub_types', 'markets.sub_type','=','party_sub_types.id')
                            ->select('sales_invoice.*', 'markets.*', 'party_sub_types.*', DB::raw('SUM(sales_invoice_items.quantity) as total_quantity'), DB::raw('SUM(sales_invoice_items.amount) as total_amount'))
                            ->where('party_sub_types.name','=','Residential Customer')
                            ->get();
            $sales_order_type_b = DB::table('sales_invoice')
                            ->leftJoin('sales_invoice_items', 'sales_invoice.id','=','sales_invoice_items.sales_invoice_id')
                            ->leftJoin('markets', 'sales_invoice.market_id','=','markets.id')
                            ->leftJoin('party_sub_types', 'markets.sub_type','=','party_sub_types.id')
                            ->select('sales_invoice.*', 'markets.*', 'party_sub_types.*', DB::raw('SUM(sales_invoice_items.quantity) as total_quantity'), DB::raw('SUM(sales_invoice_items.amount) as total_amount'))
                            ->where('party_sub_types.name','=','Business Customer')
                            ->get();
            $sales_order_type_cn = DB::table('sales_invoice')
                            ->leftJoin('sales_invoice_items', 'sales_invoice.id','=','sales_invoice_items.sales_invoice_id')
                            ->leftJoin('markets', 'sales_invoice.market_id','=','markets.id')
                            ->leftJoin('party_sub_types', 'markets.sub_type','=','party_sub_types.id')
                            ->select('sales_invoice.*', 'markets.*', 'party_sub_types.*', DB::raw('SUM(sales_invoice_items.quantity) as total_quantity'), DB::raw('SUM(sales_invoice_items.amount) as total_amount'))
                            ->where('party_sub_types.name','=','Charity Organisation Customer')
                            ->orWhere('party_sub_types.name', '=','Non Govt Organisation Customer')
                            ->get();
                $sales_order_type[1] = $sales_order_type_b[0];
                $sales_order_type[2] = $sales_order_type_cn[0];
                
        if (auth()->user()->hasRole('superadmin')) { 
            
            return view('dashboard.dashboard')
                    ->with('customer_levels',$customer_levels)
                    ->with('categories',$categories)
                    ->with('subcategories',$subcategories)
                    ->with('stream',$stream)
                    ->with('coupons',$coupons)
                    ->with('drivers',$drivers)
                    ->with('party_types',$party_types)
                    ->with('party_sub_types',$party_sub_types)
                    ->with('staffs_count',$staffs_count)
                    ->with('party_sub_types_customer',$party_sub_types_customer)
                    ->with('sales_order_received_from',$sales_order_received_from)
                    ->with('purchase_order_received_from',$purchase_order_received_from)
                    ->with('quotes_received_from',$quotes_received_from)
                    ->with('sales_order_status',$sales_order_status)
                    ->with('sales_order_type',$sales_order_type);
                         
        } elseif (auth()->user()->hasRole('admin')) { 
            return view('dashboard.admin_dashboard');        
        } elseif (auth()->user()->hasRole('manager')) { 
            return view('dashboard.manager_dashboard');        
        } elseif (auth()->user()->hasRole('supervisor')) { 
            return view('dashboard.supervisor_dashboard');        
        } elseif (auth()->user()->hasRole('worker')) { 
            return view('dashboard.worker_dashboard');        
        } elseif (auth()->user()->hasRole('driver')) { 
            return view('dashboard.driver.dashboard');        
        } else {
            return '';
        }        
    }


    public function dashboardDatas(Request $request) {

        $this->start_date       = $request->start_date;
        $this->end_date         = $request->end_date;

        $this->sales            = $this->salesInvoiceRepository
                                   ->whereDate('date','>=',Carbon::parse($this->start_date)->format('Y-m-d'))
                                   ->whereDate('date','<=',Carbon::parse($this->end_date)->format('Y-m-d'))
                                   ->sum('total');

        $this->purchase         = $this->purchaseInvoiceRepository
                                   ->whereDate('date','>=',Carbon::parse($this->start_date)->format('Y-m-d'))
                                   ->whereDate('date','<=',Carbon::parse($this->end_date)->format('Y-m-d'))
                                   ->sum('total');
        
        $this->expenses         = $this->expensesRepository
                                   ->whereDate('date','>=',Carbon::parse($this->start_date)->format('Y-m-d'))
                                   ->whereDate('date','<=',Carbon::parse($this->end_date)->format('Y-m-d'))
                                   ->sum('total_amount');

        $this->total_markets    = $this->marketRepository->count();
        $this->admin_markets    = $this->marketRepository->where('created_via','admin_portal')->count();
        $this->website_markets  = $this->marketRepository->where('created_via','website')->count();
        $this->mobile_markets   = $this->marketRepository->where('created_via','mobile_app')->count();

        $this->online_orders    = $this->orderRepository->count();
        $this->website_orders   = $this->orderRepository->count();
        $this->mobile_orders    = $this->orderRepository->count();

        $this->staff_categories = ['Manager','Supervisor','Worker','Driver','Staff'];
        for($i=0; $i<count($this->staff_categories); $i++) {
            $this->total_staffs[$i]     =   $this->userRepository
                                                 ->with("roles")
                                                 ->whereHas('roles', function($q) use($i) { 
                                                    $q->where('name',$this->staff_categories[$i]);
                                                 })->count();
            $this->total_present[$i]    =   $this->attendanceRepository
                                                    ->join('users', 'users.id', '=', 'attendances.user_id')
                                                    ->join('model_has_roles', 'users.id', '=', 'model_has_roles.model_id')
                                                    ->join('roles', 'model_has_roles.role_id', '=', 'roles.id')
                                                    ->where('roles.name',strtolower($this->staff_categories[$i]))
                                                    ->whereDate('attendances.clock_in_time',date('Y-m-d'))
                                                    ->count();
            $this->total_absent[$i]     = $this->total_staffs[$i] - $this->total_present[$i];                                                                                                    
        }
        
        $this->last_seven_days  = $this->get7DaysDates(7, 'D');
        foreach($this->get7DaysDates(7, 'Y-m-d') as $key => $value) {
            $sales_invoice              = $this->salesInvoiceRepository->where('date',$value)->sum('total');
            $online_orders              = $this->orderRepository->whereDate('created_at',$value)->sum('order_amount');
            $this->weekly_sales[$key]   = $sales_invoice + $online_orders;
        }

        foreach($this->get7DaysDates(7, 'Y-m-d') as $key => $value) {
            $this->weekly_purchase[$key]= $this->purchaseInvoiceRepository->where('date',$value)->sum('total');
        }

        
        $this->total_products           = $this->productRepository->count();
        $this->total_deliverable_products = $this->productRepository->where('deliverable','1')->count();
        $this->total_weight_loss_products = $this->productRepository->where('weight_loss','1')->count();


        $this->out_of_stock_items       = $this->productRepository->where('stock','<=',0)->count();
        $this->out_of_stock             = ($this->total_products > 0) ? number_format((100 / $this->total_products) * $this->out_of_stock_items,2,'.','') : 0 ;

        $this->wastage_items            = $this->inventoryRepository
                                                ->where('usage','wastage')
                                                ->whereDate('date','>=',Carbon::parse($this->start_date)->format('Y-m-d'))
                                                ->whereDate('date','<=',Carbon::parse($this->end_date)->format('Y-m-d'))
                                                ->count();
        $this->inventory_count          = $this->inventoryRepository
                                               ->where('type','add')
                                               ->whereDate('date','>=',Carbon::parse($this->start_date)->format('Y-m-d'))
                                               ->whereDate('date','<=',Carbon::parse($this->end_date)->format('Y-m-d'))
                                               ->count();

        $this->wastage                  = ($this->inventory_count > 0) ? number_format((100 / $this->inventory_count) * $this->wastage_items,2,'.','') : 0 ;

        $this->filter_rewards           = $this->loyalityPointsRepository
                                               ->whereDate('created_at','>=',Carbon::parse($this->start_date)->format('Y-m-d'))
                                               ->whereDate('created_at','<=',Carbon::parse($this->end_date)->format('Y-m-d'))
                                               ->count();
        
        $this->filter_driver_review     = $this->driverReviewRepository
                                               ->whereDate('created_at','>=',Carbon::parse($this->start_date)->format('Y-m-d'))
                                               ->whereDate('created_at','<=',Carbon::parse($this->end_date)->format('Y-m-d'))
                                               ->count();
        
        $this->filter_complaints        = $this->complaintsRepository
                                               ->whereDate('created_at','>=',Carbon::parse($this->start_date)->format('Y-m-d'))
                                               ->whereDate('created_at','<=',Carbon::parse($this->end_date)->format('Y-m-d'))
                                               ->count(); 
        
        $this->total_notifications      = auth()->user()->unreadNotifications()->groupBy('notifiable_type')->count();
        
        $this->discount_coupons         = $this->couponRepository->count();
        $this->charity                  = $this->charityRepository->count();
        $this->delivery_zones           = $this->deliveryZonesRepository->count();
        $this->email_alerts             = $this->emailnotificationsRepository ->count();

        return response()->json(['status'=>'success', 'message'=>'Dashboard datas Unsuccessfully', 'data'=>$this]);

    } 


    public function driverOrders(Request $request) {

        $query     = $this->deliveryTrackRepository->where('user_id',auth()->user()->id);
        $data      = $query->orderBy('created_at','desc')->get();

        if($request->type=='datatable') {
            
            $dataTable = Datatables::of($data);
            $dataTable->addIndexColumn();

            $table= $dataTable
                    ->addColumn('date', function($delivery){
                        return $delivery->created_at->format('d M Y h:i A');                
                    })
                    ->addColumn('order_code', function($delivery){
                        if($delivery->category=='sales_invoice') {
                            return $delivery->salesinvoice->code;
                        } elseif($delivery->category=='online_order') {
                            return $delivery->order->order_code;
                        }
                    })
                    ->addColumn('address', function($delivery){
                       if($delivery->category=='sales_invoice') {
                            return  $delivery->salesinvoice->market->address_line_1.','.
                                    $delivery->salesinvoice->market->address_line_2.','.
                                    $delivery->salesinvoice->market->town.','.
                                    $delivery->salesinvoice->market->city.','.
                                    $delivery->salesinvoice->market->state.'-'.
                                    $delivery->salesinvoice->market->pincode;
                        } elseif($delivery->category=='online_order') {
                            return $delivery->order->deliveryAddress->address_line_1.','.
                                   $delivery->order->deliveryAddress->address_line_2.','.
                                   $delivery->order->deliveryAddress->town.','.
                                   $delivery->order->deliveryAddress->city.','.
                                   $delivery->order->deliveryAddress->state.'-'.
                                   $delivery->order->deliveryAddress->pincode;
                        }
                    })
                    ->addColumn('collectable', function($delivery){
                        if(isset($delivery->order->payment) && $delivery->order->payment->status == 'paid') {
                            return setting('default_currency').'0.00';
                        } else {
                            return setting('default_currency').number_format($delivery->order->order_amount,2,'.','');
                        }
                    })
                    ->addColumn('distance', function($delivery){
                        return number_format($delivery->order->delivery_distance,2,'.','').' KM';
                    })
                    ->make(true);

            return $table;
        
        } elseif($request->type=='summarydata') {

            $active_delivery = $this->deliveryTrackRepository->where('active',1)->where('user_id',auth()->user()->id)->first();
            $view            =  view('dashboard.driver.summary_data', compact('data','active_delivery'))->render();  
            return ['status' => 'success', 'data' => $view];

        }

    }


}
