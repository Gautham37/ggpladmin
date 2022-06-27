<?php

namespace App\Http\Controllers\API;

use App\Criteria\Products\NearCriteria;
use App\Criteria\Products\ProductsOfCategoriesCriteria;
use App\Criteria\Products\ProductsOfFieldsCriteria;
use App\Criteria\Products\TrendingWeekCriteria;
use App\Criteria\Products\SpecialOfferCriteria;
use App\Criteria\Products\ProductsOfPriceCriteria;
use App\Criteria\Products\ProductNameSearchCriteria;
use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Repositories\CustomFieldRepository;
use App\Repositories\ProductRepository;
use App\Repositories\ProductPriceVariationRepository;
use App\Repositories\UploadRepository;
use Flash;
use Illuminate\Http\Request;
use InfyOm\Generator\Criteria\LimitOffsetCriteria;
use Prettus\Repository\Criteria\RequestCriteria;
use Prettus\Repository\Exceptions\RepositoryException;
use Prettus\Validator\Exceptions\ValidatorException;
use DB;
/**
 * Class ProductController
 * @package App\Http\Controllers\API
 */
class ProductAPIController extends Controller
{
    /** @var  ProductRepository */
    private $productRepository;

    /** @var  ProductPriceVariationRepository */
    private $productPriceVariationRepository;
    /**
     * @var CustomFieldRepository
     */
    private $customFieldRepository;
    /**
     * @var UploadRepository
     */
    private $uploadRepository;


    public function __construct(ProductRepository $productRepo, CustomFieldRepository $customFieldRepo, ProductPriceVariationRepository $productPriceVariationRepo, UploadRepository $uploadRepo)
    {
        parent::__construct();
        $this->productRepository = $productRepo;
        $this->productPriceVariationRepository = $productPriceVariationRepo;
        $this->customFieldRepository = $customFieldRepo;
        $this->uploadRepository = $uploadRepo;
    }

    /**
     * Display a listing of the Product.
     * GET|HEAD /products
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        try{
            
            $this->productRepository->pushCriteria(new RequestCriteria($request));
            if ($request->get('trending', null) == 'week') {
                $this->productRepository->pushCriteria(new TrendingWeekCriteria($request));
            } elseif ($request->get('special_offer', null) == 'all') {
                $this->productRepository->pushCriteria(new SpecialOfferCriteria($request));
            }
            $this->productRepository->pushCriteria(new ProductsOfPriceCriteria($request));
            $this->productRepository->pushCriteria(new ProductNameSearchCriteria($request));

            $products = $this->productRepository
                             ->with('category')
                             ->with('subcategory')
                             ->with('department')
                             ->with('qualitygrade')
                             ->with('stockstatus')
                             ->with('season')
                             ->with('color')
                             ->with('nutritions')
                             ->with('taste')
                             ->with('pricevaritations')
                             ->with('vasservices')
                             ->with('primaryunit')
                             ->with('secondaryunit')
                             ->with('tertiaryunit')
                             ->with('customunit')
                             ->with('bulkbuyunit')
                             ->with('wholesalepurchaseunit')
                             ->with('packweightunit')
                             ->get();  

        } catch (RepositoryException $e) {
            return $this->sendError($e->getMessage());
        }

        return $this->sendResponse($products->toArray(), 'Products retrieved successfully');
    }

    /**
     * Display a listing of the Product.
     * GET|HEAD /products/categories
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    /*public function categories(Request $request)
    {
        try{
            $this->productRepository->pushCriteria(new RequestCriteria($request));
            $this->productRepository->pushCriteria(new LimitOffsetCriteria($request));
            $this->productRepository->pushCriteria(new ProductsOfFieldsCriteria($request));
            $this->productRepository->pushCriteria(new ProductsOfCategoriesCriteria($request));

            $products = $this->productRepository->all();

        } catch (RepositoryException $e) {
            return $this->sendError($e->getMessage());
        }

        return $this->sendResponse($products->toArray(), 'Products retrieved successfully');
    }*/

    /**
     * Display the specified Product.
     * GET|HEAD /products/{id}
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Request $request, $id)
    {
        /** @var Product $product */
        if (!empty($this->productRepository)) {
            try{
                $this->productRepository->pushCriteria(new RequestCriteria($request));
            } catch (RepositoryException $e) {
                return $this->sendError($e->getMessage());
            }
            $product = $this->productRepository
                             ->with('category')
                             ->with('subcategory')
                             ->with('department')
                             ->with('qualitygrade')
                             ->with('stockstatus')
                             ->with('season')
                             ->with('color')
                             ->with('nutritions')
                             ->with('taste')
                             ->with('pricevaritations')
                             ->with('vasservices')
                             ->with('primaryunit')
                             ->with('secondaryunit')
                             ->with('tertiaryunit')
                             ->with('customunit')
                             ->with('bulkbuyunit')
                             ->with('wholesalepurchaseunit')
                             ->with('packweightunit')
                             ->findWithoutFail($id);
        }

        if (empty($product)) {
            return $this->sendError('Product not found');
        }
        return $this->sendResponse($product->toArray(), 'Product retrieved successfully');
    }


    public function productUnits(Request $request, $id)
    {
        if (!empty($this->productRepository)) {
            try{
                $this->productRepository->pushCriteria(new RequestCriteria($request));
            } catch (RepositoryException $e) {
                return $this->sendError($e->getMessage());
            }
            $product_units = $this->productRepository->allunits($id);
        }
        if (empty($product_units)) {
            return $this->sendError('Product Units not found');
        }
        return $this->sendResponse($product_units, 'Product Units retrieved successfully');
    }

    public function productUnitPrice(Request $request, $unit_id, $product_id)
    {
        if (!empty($this->productRepository)) {
            try{
                $this->productRepository->pushCriteria(new RequestCriteria($request));

                $product = $this->productRepository->findWithoutFail($product_id);
                $units   = $product->allunits($product->id);

                foreach($units as $unit) {
                    if($unit['id'] == $unit_id) {
                        $price          = $product->price;
                        $discount_price = ($product->discount_price > 0) ? $product->discount_price : $product->price ;

                        $datas['price']          = number_format(($price * $unit['quantity']),2,'.','');
                        $datas['discount_price'] = number_format(($discount_price * $unit['quantity']),2,'.','');
                    }
                }

            } catch (RepositoryException $e) {
                return $this->sendError($e->getMessage());
            }
              
        }
        if (empty($datas)) {
            return $this->sendError('Product not found');
        }
        return $this->sendResponse($datas, 'Product Unit Price retrieved successfully');
    }

    
    public function getHighVolumeProducts(Request $request) {
      
        $sales_products = DB::select('SELECT id, name,bar_code,hsn_code,purchase_price,price,stock,unit,stockvalue, SUM(total_quantity) AS ordered_quantity FROM(SELECT a.id,a.name,a.bar_code,a.hsn_code,a.purchase_price,a.price,a.stock,a.unit,a.stock*a.purchase_price as stockvalue, SUM(b.quantity) AS total_quantity
FROM products a JOIN product_orders b ON b.product_id = a.id GROUP BY a.id, b.product_id UNION SELECT a.id,a.name,a.bar_code,a.hsn_code,a.purchase_price,a.price,a.stock,a.unit,a.stock*a.purchase_price as stockvalue, SUM(c.sales_detail_quantity) AS total_quantity
FROM products a JOIN sales_invoice_detail c ON c.sales_detail_product_id = a.id GROUP BY a.id, c.sales_detail_product_id
) temp GROUP BY id order by total_quantity desc limit 15');


        return $this->sendResponse($sales_products, 'Products retrieved successfully');
    }

}
