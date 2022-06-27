<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Repositories\OrderRepository;
use App\Repositories\SalesInvoiceRepository;
use App\Repositories\UserRepository;
use App\Repositories\ProductRepository;
use App\Repositories\InventoryRepository;
use Flash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use Prettus\Repository\Exceptions\RepositoryException;
use Prettus\Validator\Exceptions\ValidatorException;
use CustomHelper;
use DB;

/**
 * Class OrderController
 * @package App\Http\Controllers\API
 */
class ReportsAPIController extends Controller
{
    /** @var  OrderRepository */
    private $orderRepository;
    /** @var  SalesInvoiceRepository */
    private $salesInvoiceRepository;
    /** @var  ProductRepository */
    private $productRepository;
    /** @var  InventoryRepository */
    private $inventoryRepository;

    public function __construct(OrderRepository $orderRepo, SalesInvoiceRepository $salesInvoiceRepo, ProductRepository $productRepo, InventoryRepository $inventoryRepo)
    {
        $this->orderRepository        = $orderRepo;
        $this->salesInvoiceRepository = $salesInvoiceRepo;
        $this->productRepository      = $productRepo;
        $this->inventoryRepository    = $inventoryRepo;
    }

    public function byMonth(Request $request)
    {   
        $start_date = $request->start_date;
        $end_date   = $request->end_date;

        $orders = [];
        if (!empty($this->orderRepository)) {
            $orders[] = $this->orderRepository
                           ->whereDate('created_at', '>=', $start_date)
                           ->whereDate('created_at', '<=', $end_date)
                           ->orderBy("created_at",'asc')->count();
        }
        if (!empty($this->orderRepository)) {
            $orders[] = $this->orderRepository
                           ->whereDate('created_at', '>=', $start_date)
                           ->whereDate('created_at', '<=', $end_date)
                           ->orderBy("created_at",'asc')->count();
        }
        if (!empty($this->salesInvoiceRepository)) {
            $orders[] = $this->salesInvoiceRepository
                           ->whereDate('sales_date', '>=', $start_date)
                           ->whereDate('sales_date', '<=', $end_date)
                           ->orderBy("sales_date",'asc')->count();
        }
        return response()->json([
            'success' => true,
            'data'    => $orders,
            'message' =>'Orders retrieved successfully'
        ]);
    }

    public function topSellingProducts(Request $request)
    {   
        $start_date = $request->start_date;
        $end_date   = $request->end_date;

        $products = [];
        $sellings = [];
        
        if (!empty($this->productRepository)) {
            $products_list = $this->productRepository->get();
            if(count($products_list) > 0) {
                foreach($products_list as $product) {
                    $products[] = $product->name;
                    $sellings[] = $this->inventoryRepository
                                       ->where('inventory_track_product_id',$product->id)
                                       ->whereDate('created_at', '>=', $start_date)
                                       ->whereDate('created_at', '<=', $end_date)
                                       ->sum('inventory_track_product_quantity'); 
                }
            }                          

        }

        return response()->json([
            'success' => true,
            'products'    => $products,
            'sellings'    => $sellings,
            'message' =>'Products retrieved successfully'
        ]);
    }

}
