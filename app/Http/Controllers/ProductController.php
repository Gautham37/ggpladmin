<?php
/**
 * File name: ProductController.php
 * Last modified: 2020.04.30 at 08:21:08
 * Author: SmarterVision - https://codecanyon.net/user/smartervision
 * Copyright (c) 2020
 *
 */

namespace App\Http\Controllers;

use App\Criteria\Products\ProductsOfUserCriteria;
use App\DataTables\ProductDataTable;
use App\Http\Requests\CreateProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Repositories\CategoryRepository;
use App\Repositories\SubcategoryRepository;
use App\Repositories\DepartmentsRepository;
use App\Repositories\QualityGradeRepository;
use App\Repositories\ProductStatusRepository;
use App\Repositories\StockStatusRepository;
use App\Repositories\ValueAddedServiceAffiliatedRepository;
use App\Repositories\CustomFieldRepository;
use App\Repositories\MarketRepository;
use App\Repositories\ProductRepository;
use App\Repositories\InventoryRepository;
use App\Repositories\CustomerGroupsRepository;
use App\Repositories\ProductPriceVariationRepository;
use App\Repositories\UomRepository;
use App\Repositories\UploadRepository;
use App\Repositories\ProductDetailsRepository;
use App\Repositories\ProductSeasonsRepository;
use App\Repositories\ProductColorsRepository;
use App\Repositories\ProductNutritionsRepository;
use App\Repositories\ProductNutritionDetailRepository;
use App\Repositories\ProductTastesRepository;
use App\Repositories\ProductVasRepository;
use Flash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Prettus\Validator\Exceptions\ValidatorException;
use DB;
use App\Models\InventoryTrack;
use App\Models\Product;
use App\Models\Category;
use App\Models\ProductpriceVariation;
use DataTables;
use PDF;
use App\Mail\PriceDropMail;
// use App\Mail\CustomerPurchaseMail;
// use App\Mail\BirthdayDiscountMail;
use CustomHelper;
use App\Imports\ProductsImport;
use App\Imports\ProductsUpdateImport;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;

class ProductController extends Controller
{
    /** @var  ProductRepository */
    private $productRepository;

    /**
     * @var CustomFieldRepository
     */
    private $customFieldRepository;

    /**
     * @var UploadRepository
     */
    private $uploadRepository;
    /**
     * @var MarketRepository
     */
    private $marketRepository;
    /**
     * @var CategoryRepository
     */
    private $categoryRepository;
      /**
     * @var SubctegoryRepository
     */
    private $subcategoryRepository;
      /**
     * @var DepartmentsRepository
     */
    private $departmentsRepository;
    /**
     * @var CustomerGroupsRepository
     */
    private $customerGroupRepository;
    /**
     * @var ProductPriceVariationRepository
     */
    private $productPriceVariationRepository;
    /**
     * @var UomRepository
     */
    private $uomRepository;
     /**
     * @var QualityGradeRepository
     */
    private $qualityGradeRepository;
     /**
     * @var ProductStatusRepository
     */
    private $productStatusRepository;
     /**
     * @var StockStatusRepository
     */
    private $stockStatusRepository;
     /**
     * @var ValueAddedServiceAffiliatedRepository
     */
    private $valueAddedServiceAffiliatedRepository;
    /**
     * @var ProductDetailsRepository
     */
    private $productDetailsRepository;
    /**
     * @var ProductSeasonsRepository
     */
    private $productSeasonsRepository;
    /**
     * @var ProductColorsRepository
     */
    private $productColorsRepository;
    /**
     * @var ProductNutritionsRepository
     */
    private $productNutritionsRepository;
    /**
     * @var ProductNutritionDetailRepository
     */
    private $productNutritionDetailRepository;
    /**
     * @var ProductTastesRepository
     */
    private $productTastesRepository;
    /**
     * @var ProductVasRepository
     */
    private $productVasRepository;


    public function __construct(ProductRepository $productRepo, CustomFieldRepository $customFieldRepo, UploadRepository $uploadRepo
        , MarketRepository $marketRepo
        , CategoryRepository $categoryRepo, SubcategoryRepository $subcategoryRepo, DepartmentsRepository $departmentsRepo, InventoryRepository $inventoryRepo, CustomerGroupsRepository $customerGroupRepo, UomRepository $uomRepo, ProductPriceVariationRepository $productPriceVariationRepo, QualityGradeRepository $qualityGradeRepo, ProductStatusRepository $productStatusRepo, StockStatusRepository $stockStatusRepo, ValueAddedServiceAffiliatedRepository $valueAddedServiceAffiliatedRepo, ProductDetailsRepository $productDetailsRepo, ProductSeasonsRepository $productSeasonsRepo, ProductColorsRepository $productColorsRepo, ProductNutritionsRepository $productNutritionsRepo, ProductTastesRepository $productTastesRepo, ProductVasRepository $productVasRepo, ProductNutritionDetailRepository $productNutritionDetailRepo)
    {
        parent::__construct();
        $this->productRepository                        = $productRepo;
        $this->customFieldRepository                    = $customFieldRepo;
        $this->uploadRepository                         = $uploadRepo;
        $this->marketRepository                         = $marketRepo;
        $this->categoryRepository                       = $categoryRepo;
        $this->subcategoryRepository                    = $subcategoryRepo;
        $this->departmentsRepository                    = $departmentsRepo;
        $this->inventoryRepository                      = $inventoryRepo;
        $this->customerGroupRepository                  = $customerGroupRepo;
        $this->productPriceVariationRepository          = $productPriceVariationRepo;
        $this->uomRepository                            = $uomRepo;
        $this->qualityGradeRepository                   = $qualityGradeRepo;
        $this->productStatusRepository                  = $productStatusRepo;
        $this->stockStatusRepository                    = $stockStatusRepo;
        $this->valueAddedServiceAffiliatedRepository    = $valueAddedServiceAffiliatedRepo;
        $this->productDetailsRepository                 = $productDetailsRepo;
        $this->productSeasonsRepository                 = $productSeasonsRepo;
        $this->productColorsRepository                  = $productColorsRepo;
        $this->productNutritionsRepository              = $productNutritionsRepo;
        $this->productNutritionDetailRepository         = $productNutritionDetailRepo;
        $this->productTastesRepository                  = $productTastesRepo;
        $this->productVasRepository                     = $productVasRepo;
    }

    /**
     * Display a listing of the Product.
     *
     * @param ProductDataTable $productDataTable
     * @return Response
     */
    public function index(Request $request, ProductDataTable $productDataTable)
    {
        if($request->ajax()) {
            if($request->type=='cart_products') {
                $market_id = $request->market_id;
                $section   = $request->section;
                $products  = $this->productRepository->get();
                $view      = view('products.cart_items', compact('products','market_id','section'))->render();
                return ['status' => 'success', 'data' => $view];
            } elseif($request->type=='product_list') {

                if($request->category && $request->category!='') {

                    $query  = $this->productRepository
                                  ->with('category')
                                  ->with('primaryunit')
                                  ->with('secondaryunit')
                                  ->with('tertiaryunit')
                                  ->with('customunit')
                                  ->with('bulkbuyunit')
                                  ->with('wholesalepurchaseunit')
                                  ->with('packweightunit')
                                  ->with('pricevaritations')
                                  ->with('vasservices')
                                  ->where('product_status','active');

                    if($request->category=='all') {

                        $data     = $query->get();

                    } elseif($request->category=='today') {

                        $products = $this->inventoryRepository
                                         ->where('type','reduce')
                                         ->whereDate('date',date('Y-m-d'))
                                         ->pluck('product_id')
                                         ->toArray();
                        $data     = $query->whereIn('id',$products)->get();

                    } elseif($request->category=='weekly') {

                        $products = $this->inventoryRepository->where('type','reduce')
                                         ->whereDate('date','>=',now()->subDays(7)->format('Y-m-d'))
                                         ->whereDate('date','<=',now()->format('Y-m-d'))
                                         ->pluck('product_id')
                                         ->toArray();
                        $data     = $query->whereIn('id',$products)->get();


                    } elseif($request->category=='monthly') {

                        $products = $this->inventoryRepository
                                         ->where('type','reduce')
                                         ->whereDate('date','>=',now()->subDays(30)->format('Y-m-d'))
                                         ->whereDate('date','<=',now()->format('Y-m-d'))
                                         ->pluck('product_id')
                                         ->toArray();
                        $data     = $query->whereIn('id',$products)->get();

                    }

                    return ['status' => 'success', 'data' => $data];

                } else {

                    $products  = $this->productRepository
                                  ->with('category')
                                  ->with('primaryunit')
                                  ->with('secondaryunit')
                                  ->with('tertiaryunit')
                                  ->with('customunit')
                                  ->with('bulkbuyunit')
                                  ->with('wholesalepurchaseunit')
                                  ->with('packweightunit')
                                  ->with('pricevaritations')
                                  ->with('vasservices')
                                  ->where('product_status','active')->get();
                    return ['status' => 'success', 'data' => $products];

                }
            }
        }
        return $productDataTable->render('products.index');
    }

    /**
     * Show the form for creating a new Product.
     *
     * @return Response
     */
    public function create()
    {   

        //$bar_code      = rand(111111,999999);
        $bar_code      = CustomHelper::unique_code_generate('products','ITM');
        
        $uom_list      = $this->uomRepository->get();
        foreach ($uom_list as $value) : $uom[$value->id] = $value->description.' ('.$value->name.')'; endforeach;

        $departments  = $this->departmentsRepository->pluck('name', 'id');
        $departments->prepend("Please Select",null);
        
        $quality_grade  = $this->qualityGradeRepository->pluck('name', 'id');
        $quality_grade->prepend("Please Select",null);
        
        //$product_status  = $this->productStatusRepository->pluck('status', 'id');
        //$product_status->prepend("Please Select",null);
        
        $stock_status  = $this->stockStatusRepository->pluck('status', 'id');
        $stock_status->prepend("Please Select",null);
        
        $value_added_service_affiliated  = $this->valueAddedServiceAffiliatedRepository->pluck('name', 'id');
        $value_added_service_affiliated->prepend("Please Select",null);

        $category_count  = $this->categoryRepository->max('id');
        
        $tax_rates = DB::table('tax_rates')->pluck('name', 'rate'); 
        $tax_rates->prepend("Please Select",null);

        $customer_groups = $this->customerGroupRepository->pluck('name', 'id');
        $CustomerGroupsSelected = [];
        $uomSelected            = '';
        $taxSelected            = '';

        $market = $this->marketRepository->pluck('name', 'id');

        $hasCustomField = in_array($this->productRepository->model(), setting('custom_field_models', []));
        if ($hasCustomField) {
            $customFields = $this->customFieldRepository->findByField('custom_field_model', $this->productRepository->model());
            $html = generateCustomField($customFields);
        }
        
         $category = $subcategory = [null=>"Please Select"];
         $categorySelected = $subcategorySelected = array();

        $seasons  = $this->productSeasonsRepository->pluck('name', 'id');
        $seasons->prepend("Please Select",null);

        $colors  = $this->productColorsRepository->pluck('name', 'id');
        $colors->prepend("Please Select",null);

        $nutritions  = $this->productNutritionsRepository->pluck('name', 'id');
        //$nutritions->prepend("Please Select",null);

        $tastes  = $this->productTastesRepository->pluck('name', 'id');
        $tastes->prepend("Please Select",null);
      
        
        return view('products.create',compact('seasons','colors','nutritions','tastes'))->with("customFields", isset($html) ? $html : false)->with("market", $market)->with("category", $category)->with("categorySelected", $categorySelected)->with("departments", $departments)->with("uom", $uom)->with('uomSelected',$uomSelected)->with("customer_groups", $customer_groups)->with("CustomerGroupsSelected", $CustomerGroupsSelected)->with('tax_rates',$tax_rates)->with('taxSelected',$taxSelected)->with('bar_code',$bar_code)->with('category_count',$category_count)->with("subcategory", $subcategory)->with('subcategorySelected',$subcategorySelected)->with('quality_grade',$quality_grade)->with('stock_status',$stock_status)->with('value_added_service_affiliated',$value_added_service_affiliated);
    }

    /**
     * Store a newly created Product in storage.
     *
     * @param CreateProductRequest $request
     *
     * @return Response
     */
    public function store(CreateProductRequest $request)
    {   
        $input = $request->all();
        
        //validation for purchase price greater than sales price
        if($input['purchase_price'] >= $input['price']) {
          Flash::error(__('lang.purchase_price_greater_than_sales_price'));
          return back()->withInput();
        }
        
        $input['name_lang_1'] = translateWord(urlencode($input['name']),'hi');
        $input['name_lang_2'] = translateWord(urlencode($input['name']),'gu');
        $input['stock']       = ($request->stock > 0) ? $request->stock : 0 ;
        
        $customer_group_id  = isset($input['customer_group_id']) ? $input['customer_group_id'] : array() ;
        $product_price      = isset($input['product_price']) ? $input['product_price'] : array() ;

        try {
            
            $input['created_by']  = auth()->user()->id;
            $product              = $this->productRepository->create($input);
            
            //Price Variation Create
            if(isset($input['product_purchase_quantity_from']) && isset($input['product_purchase_quantity_to']) && isset($input['product_price_multiplier'])) :
                if(count($input['product_purchase_quantity_from']) > 0 && count($input['product_purchase_quantity_to']) > 0 && count($input['product_price_multiplier']) > 0) :
                    for($i=0; $i<count($input['product_purchase_quantity_from']); $i++) :
                       if($input['product_purchase_quantity_from'][$i]!='' && $input['product_purchase_quantity_to'][$i]!='' && $input['product_price_multiplier'][$i]!='') {
                          $variation_data   = array(
                            'product_id'             => $product->id,
                            'purchase_quantity_from' => $input['product_purchase_quantity_from'][$i],
                            'purchase_quantity_to'   => $input['product_purchase_quantity_to'][$i],
                            'price_multiplier'       => $input['product_price_multiplier'][$i],
                            'created_by'             => auth()->user()->id
                          );
                          $product_variation = $this->productPriceVariationRepository->create($variation_data);
                       }
                    endfor;    
                endif;
            endif;
            //Price Variation Create

            //Nutrition Create
            if(isset($input['nutrition_id']) && count($input['nutrition_id'])) :
                foreach($input['nutrition_id'] as $nutrition) {
                    $this->productNutritionDetailRepository->create([
                        'product_id'            => $product->id,
                        'product_nutrition_id'  => $nutrition,
                        'created_by'            => auth()->user()->id
                    ]);
                }
            endif;
            //Nutrition Create

            //Vas Create
            if(isset($input['vas_id']) && isset($input['vas_price'])) :
                if(count($input['vas_id']) > 0 && count($input['vas_price']) > 0) :
                    for($i=0; $i<count($input['vas_id']); $i++) :
                       if($input['vas_id'][$i]!='' && $input['vas_price'][$i]!='') {
                          $vas_data   = array(
                            'vas_id'      => $input['vas_id'][$i],
                            'product_id'  => $product->id,
                            'price'       => $input['vas_price'][$i],
                            'created_by'  => auth()->user()->id
                          );
                          $vas_services = $this->productVasRepository->create($vas_data);
                       }
                    endfor;    
                endif;
            endif;
            //Vas Create

            //Customer Group Product Price
            if(count($customer_group_id) > 0) {
                foreach($customer_group_id as $key => $value) {
                    $product_group_price = DB::table('product_group_price')->insert(
                        [
                            'customer_group_id' => $value,
                            'product_price'     => $product_price[$key],
                            'product_id'        => $product->id,
                            'created_by'        => auth()->user()->id
                        ]
                    );
                }
            }
            //Customer Group Product Price

            $inventory = $product->productInventory()->create([
                'product_id' => $product->id,
                'category'   => 'opening',
                'type'       => 'add',
                'date'       => Carbon::now()->format('Y-m-d H:i:s'),
                'quantity'   => $product->stock,
                'unit'       => $product->unit,
                'created_by' => auth()->user()->id 
            ]);
            
            if (isset($input['image']) && $input['image'] && !empty($input['image'][0]) && is_array($input['image'])) {
                foreach ($input['image'] as $fileUuid){
                    $cacheUpload = $this->uploadRepository->getByUuid($fileUuid);
                    $mediaItem = $cacheUpload->getMedia('image')->first();
                    $mediaItem->copy($product, 'image');
                }
            }

        } catch (ValidatorException $e) {
            Flash::error($e->getMessage());
        }

        Flash::success(__('lang.saved_successfully', ['operator' => __('lang.product')]));
        return redirect(route('products.index'));
    }

    /**
     * Display the specified Product.
     *
     * @param int $id
     *
     * @return Response
     * @throws \Prettus\Repository\Exceptions\RepositoryException
     */
    public function show(Request $request, $id)
    {
        $product  = $this->productRepository
                         ->with('category')
                         ->with('primaryunit')
                         ->with('secondaryunit')
                         ->with('tertiaryunit')
                         ->with('customunit')
                         ->with('bulkbuyunit')
                         ->with('wholesalepurchaseunit')
                         ->with('packweightunit')
                         ->with('pricevaritations')
                         ->with('vasservices')
                         ->findWithoutFail($id);
        if (empty($product)) {
            Flash::error('Product not found');
            return redirect(route('products.index'));
        }
        if($request->ajax()) {
            if($request->type=='product_inventory') {
                $products  = $this->productRepository->pluck('name','id');
                $product   = $this->productRepository->findWithoutFail($id);
                $view      = view('products.inventory', compact('products','product','id'))->render();
                return ['status' => 'success', 'data' => $view];    
            } elseif($request->type=='product_wastage') {
                $products  = $this->productRepository->pluck('name','id');
                $product   = $this->productRepository->findWithoutFail($id);
                $view      = view('products.wastage', compact('products','product','id'))->render();
                return ['status' => 'success', 'data' => $view];    
            } else {
                return ['status' => 'success', 'data' => $product];
            }           
        }
        $category = $this->categoryRepository->findWithoutFail($product->category_id);
        return view('products.show')->with('product', $product)->with('category', $category);
    }

    public function view(ProductDataTable $productDataTable, $id)
    {   
        $product   = $this->productRepository->findWithoutFail($id);
        $products  = $this->productRepository->get();
        if (empty($product)) {
            Flash::error('Product not found');
            return redirect(route('products.index'));
        }
        return view('products.view')->with('products', $products)->with('product', $product);
    }

    /**
     * Show the form for editing the specified Product.
     *
     * @param int $id
     *
     * @return Response
     * @throws \Prettus\Repository\Exceptions\RepositoryException
     */
    public function edit($id)
    {
        //$this->productRepository->pushCriteria(new ProductsOfUserCriteria(auth()->id()));
        $product = $this->productRepository->findWithoutFail($id);
        
        //UOM List
        $uom_list      = $this->uomRepository->get();
        foreach ($uom_list as $value) : $uom[$value->id] = $value->description.' ('.$value->name.')'; endforeach;
        $uomSelected   = $product->unit;
        //UOM List

        $tax_rates = DB::table('tax_rates')->pluck('name', 'rate'); 
        $tax_rates->prepend("Please Select",0);
        $taxSelected = $product->tax;

        //for product_group_price
        $customer_groups = $this->customerGroupRepository->pluck('name', 'id'); 
        $CustomerGroupsSelected=array();
        $product_price=array();

        $get_product_group_price = DB::table('product_group_price')->leftJoin('customer_groups', 'customer_groups.id', '=', 'product_group_price.customer_group_id')->where('product_id',$id)->get();
        $product_group_price_details = $get_product_group_price->toArray();
     
        foreach ($product_group_price_details as $value){
            $CustomerGroupsSelected[] = $value->customer_group_id;
            $product_price[] = $value->product_price;
        }
        //for product_group_price

        if (empty($product)) {
            Flash::error(__('lang.not_found', ['operator' => __('lang.product')]));
            return redirect(route('products.index'));
        }
        $departments                    = $this->departmentsRepository->pluck('name', 'id');
        $quality_grade                  = $this->qualityGradeRepository->pluck('name', 'id');
        $stock_status                   = $this->stockStatusRepository->pluck('status', 'id');
        $value_added_service_affiliated = $this->valueAddedServiceAffiliatedRepository->pluck('name', 'id');

        //$category->push('Others', 'others');
        $category_count  = $this->categoryRepository->max('id');
         
        $category            = $this->categoryRepository->where('id',$product->category_id)->pluck('name', 'id');
        $categorySelected    = $product->category_id;
        $subcategory         = $this->subcategoryRepository->where('id',$product->subcategory_id)->pluck('name', 'id');
        $subcategorySelected = $product->subcategory_id;
         
        $customFieldsValues = $product->customFieldsValues()->with('customField')->get();
        $customFields = $this->customFieldRepository->findByField('custom_field_model', $this->productRepository->model());
        $hasCustomField = in_array($this->productRepository->model(), setting('custom_field_models', []));
        if ($hasCustomField) {
            $html = generateCustomField($customFields, $customFieldsValues);
        }

        $seasons  = $this->productSeasonsRepository->pluck('name', 'id');
        $seasons->prepend("Please Select",null);

        $colors  = $this->productColorsRepository->pluck('name', 'id');
        $colors->prepend("Please Select",null);

        $nutritions  = $this->productNutritionsRepository->pluck('name', 'id');
        //$nutritions->prepend("Please Select",null);

        $tastes  = $this->productTastesRepository->pluck('name', 'id');
        $tastes->prepend("Please Select",null);

        return view('products.edit',compact('seasons','colors','nutritions','tastes'))->with('product', $product)->with("customFields", isset($html) ? $html : false)->with("category", $category)->with("categorySelected", $categorySelected)->with("departments", $departments)->with("CustomerGroupsSelected", $CustomerGroupsSelected)->with("product_price", $product_price)->with("product_group_price_details", $product_group_price_details)->with('customer_groups',$customer_groups)->with('uom',$uom)->with('uomSelected',$uomSelected)->with('tax_rates',$tax_rates)->with('taxSelected',$taxSelected)->with('category_count',$category_count)->with("subcategory", $subcategory)->with('subcategorySelected',$subcategorySelected)->with('quality_grade',$quality_grade)->with('stock_status',$stock_status)->with('value_added_service_affiliated',$value_added_service_affiliated);
    }

    /**
     * Update the specified Product in storage.
     *
     * @param int $id
     * @param UpdateProductRequest $request
     *
     * @return Response
     * @throws \Prettus\Repository\Exceptions\RepositoryException
     */
    public function update($id, UpdateProductRequest $request)
    {   
        $input = $request->all();
        
        //Validation for purchase price greater than sales price
        if($input['purchase_price'] >= $input['price']){
            Flash::error(__('lang.purchase_price_greater_than_sales_price'));
            return back()->withInput();
        }
      
        $product = $this->productRepository->findWithoutFail($id);
        if (empty($product)) {
            Flash::error('Product not found');
            return redirect(route('products.index'));
        }
        
        try {

            $input['updated_by'] = auth()->user()->id;
            $product             = $this->productRepository->update($input, $id);
            
            //Price Variation Create
            if(isset($input['product_purchase_quantity_from']) && isset($input['product_purchase_quantity_to']) && isset($input['product_price_multiplier'])) :
                if(count($input['product_purchase_quantity_from']) > 0 && count($input['product_purchase_quantity_to']) > 0 && count($input['product_price_multiplier']) > 0) :
                    for($i=0; $i<count($input['product_purchase_quantity_from']); $i++) :
                       if($input['product_purchase_quantity_from'][$i]!='' && $input['product_purchase_quantity_to'][$i]!='' && $input['product_price_multiplier'][$i]!='') {
                            $variation_data   = array(
                                'product_id'                => $product->id,
                                'purchase_quantity_from'    => $input['product_purchase_quantity_from'][$i],
                                'purchase_quantity_to'      => $input['product_purchase_quantity_to'][$i],
                                'price_multiplier'          => $input['product_price_multiplier'][$i],
                                'created_by'                => auth()->user()->id
                            );
                            if(isset($input['price_variation_id'][$i]) && $input['price_variation_id'][$i] > 0) {
                                $product_variation = $this->productPriceVariationRepository->update($variation_data,$input['price_variation_id'][$i]);
                            } else {
                                $product_variation = $this->productPriceVariationRepository->create($variation_data);
                            }
                       }
                    endfor;    
                endif;
            endif;
            //Price Variation Create

            //Nutrition Update
            if(isset($input['nutrition_id']) && count($input['nutrition_id'])) :
                $this->productNutritionDetailRepository->where('product_id',$product->id)->delete();
                foreach($input['nutrition_id'] as $nutrition) {
                    $this->productNutritionDetailRepository->firstOrCreate([
                        'product_id'            => $product->id,
                        'product_nutrition_id'  => $nutrition
                    ]);
                }
            endif;
            //Nutrition Update

            //Vas Create
            if(isset($input['vas_id']) && isset($input['vas_price'])) :
                if(count($input['vas_id']) > 0 && count($input['vas_price']) > 0) :
                    for($i=0; $i<count($input['vas_id']); $i++) :
                       if($input['vas_id'][$i]!='' && $input['vas_price'][$i]!='') {
                            $vas_data   = array(
                                'vas_id'      => $input['vas_id'][$i],
                                'product_id'  => $product->id,
                                'price'       => $input['vas_price'][$i],
                                'created_by'  => auth()->user()->id
                            );
                            if(isset($input['vas_service_id'][$i]) && $input['vas_service_id'][$i] > 0) {
                                $vas_services = $this->productVasRepository->update($vas_data,$input['vas_service_id'][$i]);
                            } else {
                                $vas_services = $this->productVasRepository->create($vas_data);
                            }
                       }
                    endfor;    
                endif;
            endif;
            //Vas Create

            //Delete product_group_price
            
            DB::table('product_group_price')->where('product_id',$id)->delete();

            if(isset($input['customer_group_id'])) {
                $customer_group_id  = $input['customer_group_id'];
                $product_price      = $input['product_price'];  
                //Insert product_group_price
                foreach($customer_group_id as $key => $value){
                    $product_group_price = DB::table('product_group_price')->insert([
                        'customer_group_id' => $value,
                        'product_price'     => $product_price[$key],
                        'product_id'        => $id,
                        'created_by'        =>auth()->user()->id
                    ]);
                }
            }

            
            if (isset($input['image']) && $input['image'] && is_array($input['image'])) {
                foreach ($input['image'] as $fileUuid){
                    if($fileUuid!=''){
                        $cacheUpload = $this->uploadRepository->getByUuid($fileUuid);
                        $mediaItem = $cacheUpload->getMedia('image')->first();
                        $mediaItem->copy($product, 'image');
                    }
                }
            }
            

        } catch (ValidatorException $e) {
            Flash::error($e->getMessage());
        }

        Flash::success(__('lang.updated_successfully', ['operator' => __('lang.product')]));
        return redirect(route('products.index'));
    }

    /**
     * Remove the specified Product from storage.
     *
     * @param int $id
     *
     * @return Response
     */
    public function destroy(Request $request, $id)
    {

        if($request->ajax()) {
            if($request->type=='delete_price_variation') {
                $this->productPriceVariationRepository->delete($request->id);
            } elseif($request->type=='delete_vas_service') {
                $this->productVasRepository->delete($request->id);
            }
        }

        $this->productRepository->pushCriteria(new ProductsOfUserCriteria(auth()->id()));
        $product = $this->productRepository->findWithoutFail($id);
        if (empty($product)) {
            Flash::error('Product not found');
            return redirect(route('products.index'));
        }

        $this->productRepository->delete($id);
        $this->inventoryRepository->where('inventory_track_product_id',$id)->delete();
        Flash::success(__('lang.deleted_successfully', ['operator' => __('lang.product')]));
        return redirect(route('products.index'));
    }

    /**
     * Remove Media of Product
     * @param Request $request
     */
    public function removeMedia(Request $request)
    {
        $input = $request->all();
        $product = $this->productRepository->findWithoutFail($input['id']);
        try {
            if ($product->hasMedia($input['collection'])) {
                $product->getFirstMedia($input['collection'],['uuid'=>$input['uuid']])->delete();
            }
            $result = $this->uploadRepository->clear($input['uuid']);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }
    }
    
    /**
     * Display a listing of the Product.
     *
     * @param ProductDataTable $productDataTable
     * @return Response
     */
    public function categoryProducts($id, ProductDataTable $productDataTable)
    {
        return $productDataTable->with('category_id',$id)->render('products.index');
    }
    
    public function getProductDetails(Request $request) {
        $id = $request->product_id;
        $product = $this->productRepository->where('bar_code',$id)->first();
        return json_encode($product);
    }

    public function getProductDetailsbyID(Request $request) {
        $id = $request->product_id;
        $product = $this->productRepository->where('id',$id)->first();
        return json_encode($product);
    }


    //Price Variations

    public function createProductPrice(Request $request)
    {
       $input = $request->all();
       $res = array();
       foreach ($input['customer_group_id'] as $key => $value) {
          $customer_groups = DB::table('customer_groups')->where('id', $value)->pluck('name');
          foreach ($customer_groups as $name => $customer_group_name) {
              
            $res[]= '<div class="form-group col-md-12">
                        <label for="product_price[]" class=" control-label text-left">'.$customer_group_name.' (Price)</label>
                        <input name="product_price[]" type="number" step="any" min="0" value="" class="form-control"/>
                    </div>';

           }
       }
       return response()->json(['success'=>$res]);
    }

    public function updateProductPrice(Request $request)
    {
        $input = $request->all();
        $res = array();

        if(isset($input['customer_group_id'])) {
        $product_id = $input['product_id'];
      
            foreach ($input['customer_group_id'] as $key => $id_val) {
              $customer_groups = DB::table('customer_groups')->where('id', $id_val)->pluck('name');
                foreach ($customer_groups as $name => $customer_group_name) {
               
                    $product_price = DB::table('product_group_price')->where('customer_group_id', $id_val)->where('product_id', $product_id)->pluck('product_price')->first();
                    $res[]= '<div class="form-group row ">
                                <label for="product_price[]" class="col-3 control-label text-right">'.$customer_group_name.'</label>
                                <div class="col-9">
                                    <input name="product_price[]" type="number" step="any" min="0" value="'.$product_price.'" class="form-control"/>
                                </div>
                            </div>';
                }
            }                
        }
    
        return response()->json(['success'=>$res]);
    }
    //Price Variations

    //Print Bar Codes
    public function printBarCodes($id) {
        $product   = $this->productRepository->findWithoutFail($id);
        $pdf = PDF::loadView('products.product_bar_codes', compact('product'));
        $filename = $id.'-'.$product->bar_code.'-'.$product->name.'-barcodes.pdf';
        return $pdf->stream($filename);
    }
    //Print Bar Codes


    public function getPriceVariations(Request $request) {
        $product_id       = $request->product_id;
        $price_variations = DB::table('product_price_variation')->where('product_id',$product_id)->get();
        if(count($price_variations) > 0) {
            $output = array('status' => 'success', 'message' => 'Data Fetched Successfully', 'result_data' => $price_variations);
        } else {
            $output = array('status' => 'success', 'message' => 'Data Fetched Successfully', 'result_data' => array());
        }
        echo json_encode($output);
    }
    
    public function getPriceVariationsbyquantity(Request $request) {
        $product_id     = $request->product_id;
        $product_unit   = $request->product_unit;
        $product_qty    = $request->product_qty;
        $product        = $this->productRepository->findWithoutFail($product_id);
        
        $price_variations = DB::table('product_price_variation')->where('product_id',$product_id)->where('purchase_quantity','<=',$product_qty)->get();
        if(count($price_variations) > 0) {
            $output = array('status' => 'success', 'message' => 'Data Fetched Successfully', 'result_data' => $price_variations, 'products' => $product);
        } else {
            $output = array('status' => 'success', 'message' => 'Data Fetched Successfully', 'result_data' => array(), 'products' => array());
        }
        echo json_encode($output);
        
    }
    
    public function getUnitList() {
        $uom_list = $this->uomRepository->get();
        return $this->sendResponse($uom_list, 'Category retrieved successfully');
    }

    /**
     * Display a listing of the Product.
     *
     * @param ProductDataTable $productDataTable
     * @return Response
     */
    public function subcategoryProducts($id, ProductDataTable $productDataTable)
    {
        return $productDataTable->with('subcategory_id',$id)->render('products.index');
    }

     public function showSubcategory(Request $request){
  
        $parent_id     = $request->cat_id;
        $subcategories = $this->categoryRepository->where('id',$parent_id)
                              ->with('subcategories')
                              ->get();
        return response()->json([
            'subcategories' => $subcategories
        ]);
     }
     
    public function departmentsProducts($id, ProductDataTable $productDataTable) {
        return $productDataTable->with('department_id',$id)->render('products.index');
    }
    
    public function showDepartments(Request $request){
  
        $depart_id   = $request->depart_id;
        $departments = $this->departmentsRepository->where('id',$depart_id)
                              ->with('category')
                              ->get();
        return response()->json([
            'departments' => $departments
        ]);
    }

    public function import() {
        return view('products.import');
    }
    
    public function importProducts(Request $request) {

        $file = $request->file('file');
        if (empty($file)) {
            Flash::error('Please select the import file');
            return redirect(route('products.import'));
        }

        try {
            if($request->import_type=='new') {
                Excel::import(new ProductsImport,$file);
                Flash::success('Imported successfully', ['operator' => 'Products']);
            } elseif($request->import_type=='update') {
                Excel::import(new ProductsUpdateImport,$file);
                Flash::success('Imported successfully', ['operator' => 'Products']);
            }
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
             $failures = $e->failures();
             foreach ($failures as $failure) {
                 Flash::error($failure->errors()[0]);
             }
        }
        return redirect(route('products.import'));
    }
    
}
