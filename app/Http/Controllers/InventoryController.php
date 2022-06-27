<?php

namespace App\Http\Controllers;

use App\Criteria\Products\ProductsOfUserCriteria;
use App\DataTables\InventoryDataTable;
use App\DataTables\InventoryListDataTable;
use App\Http\Requests\CreateInventoryTrackRequest;
use App\Http\Requests\UpdateInventoryTrackRequest;
use App\Repositories\CategoryRepository;
use App\Repositories\CustomFieldRepository;
use App\Repositories\MarketRepository;
use App\Repositories\ProductRepository;
use App\Repositories\InventoryRepository;
use App\Repositories\CustomerGroupsRepository;
use App\Repositories\ProductPriceVariationRepository;
use App\Repositories\CharityRepository;
use App\Repositories\UomRepository;
use App\Repositories\UploadRepository;
use Flash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Prettus\Validator\Exceptions\ValidatorException;
use DB;
use App\Models\InventoryTrack;
use App\Models\Product;
use App\Models\ProductpriceVariation;
use DataTables;
use PDF;
use App\Mail\PriceDropMail;
// use App\Mail\CustomerPurchaseMail;
// use App\Mail\BirthdayDiscountMail;
use CustomHelper;

class InventoryController extends Controller
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
     * @var CharityRepository
     */
    private $charityRepository;


    public function __construct(ProductRepository $productRepo, CustomFieldRepository $customFieldRepo, UploadRepository $uploadRepo
        , MarketRepository $marketRepo
        , CategoryRepository $categoryRepo, InventoryRepository $inventoryRepo, CustomerGroupsRepository $customerGroupRepo, UomRepository $uomRepo, ProductPriceVariationRepository $productPriceVariationRepo, CharityRepository $charityRepo)
    {
        parent::__construct();
        $this->productRepository = $productRepo;
        $this->customFieldRepository = $customFieldRepo;
        $this->uploadRepository = $uploadRepo;
        $this->marketRepository = $marketRepo;
        $this->categoryRepository = $categoryRepo;
        $this->inventoryRepository = $inventoryRepo;
        $this->customerGroupRepository = $customerGroupRepo;
        $this->productPriceVariationRepository = $productPriceVariationRepo;
        $this->charityRepository = $charityRepo;
        $this->uomRepository = $uomRepo;
    }

    /**
     * Display a listing of the Product.
     *
     * @param InventoryDataTable $productDataTable
     * @return Response
     */
    public function index(InventoryDataTable $inventoryDataTable)
    {   
        return $inventoryDataTable->render('inventory.index');
    }

    /**
     * Display a listing of the Inventory.
     *
     * @param InventoryListDataTable $productDataTable
     * @return Response
     */
    public function list(InventoryListDataTable $inventoryListDataTable)
    {   
        return $inventoryListDataTable->render('inventory.list');
    }

    public function store(CreateInventoryTrackRequest $request)
    {
        $input = $request->all();
        try {
            $input['created_by'] = auth()->user()->id;
            $inventory = $this->inventoryRepository->create($input);

            if(isset($input['image']) && $input['image']){
                $cacheUpload = $this->uploadRepository->getByUuid($input['image']);
                $mediaItem   = $cacheUpload->getMedia('image')->first();
                $mediaItem->copy($inventory, 'image');
            }

            $this->productStockupdate($inventory->product_id);
        } catch (ValidatorException $e) {
            Flash::error($e->getMessage());
        }
        Flash::success(__('Save successfully',['operator' => __('Product Inventory')]));
        return redirect(route('inventory.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\SalesInvoice  $SalesInvoice
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        $inventory = $this->inventoryRepository->findWithoutFail($id);
        if (empty($inventory)) {
            Flash::error(__('Not Found',['operator' => __('Inventory')]));
            return redirect(route('inventory.index'));
        }
        if($request->ajax()) {
            if($request->type=='edit') {
            
                $product = $inventory->product;
                $view = view('inventory.edit', compact('inventory','product'))->render();
                return ['status' => 'success', 'data' => $view];
            
            } elseif($request->type=='dispose') {

                $product = $inventory->product;
                $charity = $this->charityRepository->pluck('name','id');
                $charity->prepend('Please Select',null);
                $markets = $this->marketRepository->where('sub_type',4)->pluck('name','id');
                $markets->prepend('Please Select',null);
                $view = view('wastageDisposal.create', compact('inventory','product','charity','markets'))->render();
                return ['status' => 'success', 'data' => $view];
            
            } else {
                return ['status' => 'success', 'data' => $inventory];
            } 
        }
        return view('inventory.show',compact('inventory'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\SalesInvoice  $SalesInvoice
     * @return \Illuminate\Http\Response
     */
    public function update($id, UpdateInventoryTrackRequest $request)
    {  
        $inventory_old = $this->inventoryRepository->findWithoutFail($id);
        if (empty($inventory_old)) {
            Flash::error(__('Not Found',['operator' => __('Inventory')]));
            return redirect(route('inventory.index'));
        }
        $input = $request->all();
        try {
            $input['updated_by'] = auth()->user()->id;
            $inventory           = $this->inventoryRepository->update($input,$id);

            if(isset($input['image']) && $input['image']){
                $cacheUpload = $this->uploadRepository->getByUuid($input['image']);
                $mediaItem   = $cacheUpload->getMedia('image')->first();
                $mediaItem->copy($inventory, 'image');
            }
            $this->productStockupdate($inventory->product_id);
        } catch (ValidatorException $e) {
            Flash::error($e->getMessage());
        }
        Flash::success(__('Save successfully',['operator' => __('Inventory')]));
        return redirect(route('inventory.index'));
    }

    public function destroy($id)
    {
        $inventory = $this->inventoryRepository->findWithoutFail($id);
        if (empty($inventory)) {
            Flash::error('Inventory not found');
            return redirect(route('inventory.index'));
        }
        $this->inventoryRepository->delete($id);
        $this->productStockupdate($inventory->product_id);
        Flash::success(__('Deleted successfully',['operator' => __('Inventory')]));
        return redirect(route('inventory.index'));
    }

    public function removeMedia(Request $request)
    {
        $input = $request->all();
        $inventory = $this->inventoryRepository->findWithoutFail($input['id']);
        try {
            if ($inventory->hasMedia($input['collection'])) {
                $inventory->getFirstMedia($input['collection'])->delete();
            }
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }
    }
    
}
