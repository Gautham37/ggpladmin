<?php

namespace App\Http\Controllers;

use App\Criteria\Products\ProductsOfUserCriteria;
use App\DataTables\WastageDisposalDataTable;
use App\DataTables\WastageDisposalListDataTable;
use App\Http\Requests\CreateWastageDisposalRequest;
use App\Http\Requests\UpdateWastageDisposalRequest;
use App\Repositories\CategoryRepository;
use App\Repositories\CustomFieldRepository;
use App\Repositories\MarketRepository;
use App\Repositories\ProductRepository;
use App\Repositories\WastageDisposalRepository;
use App\Repositories\CustomerGroupsRepository;
use App\Repositories\ProductPriceVariationRepository;
use App\Repositories\UomRepository;
use App\Repositories\UploadRepository;
use Flash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Prettus\Validator\Exceptions\ValidatorException;
use DB;
use App\Models\WastageDisposal;
use App\Models\Product;
use App\Models\ProductpriceVariation;
use DataTables;
use PDF;
use App\Mail\PriceDropMail;
// use App\Mail\CustomerPurchaseMail;
// use App\Mail\BirthdayDiscountMail;
use CustomHelper;

class WastageDisposalController extends Controller
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


    public function __construct(ProductRepository $productRepo, CustomFieldRepository $customFieldRepo, UploadRepository $uploadRepo
        , MarketRepository $marketRepo
        , CategoryRepository $categoryRepo, wastageDisposalRepository $wastageDisposalRepo, CustomerGroupsRepository $customerGroupRepo, UomRepository $uomRepo, ProductPriceVariationRepository $productPriceVariationRepo)
    {
        parent::__construct();
        $this->productRepository = $productRepo;
        $this->customFieldRepository = $customFieldRepo;
        $this->uploadRepository = $uploadRepo;
        $this->marketRepository = $marketRepo;
        $this->categoryRepository = $categoryRepo;
        $this->wastageDisposalRepository = $wastageDisposalRepo;
        $this->customerGroupRepository = $customerGroupRepo;
        $this->productPriceVariationRepository = $productPriceVariationRepo;
        $this->uomRepository = $uomRepo;
    }

    /**
     * Display a listing of the Product.
     *
     * @param InventoryDataTable $productDataTable
     * @return Response
     */
    public function index(WastageDisposalDataTable $wastageDisposalDataTable)
    {
        return $wastageDisposalDataTable->render('wastageDisposal.index');
    }

    /**
     * Display a listing of the Product.
     *
     * @param WastageDisposalListDataTable $wastageDisposalListDataTable
     * @return Response
     */
    public function list(WastageDisposalListDataTable $wastageDisposalListDataTable)
    {
        return $wastageDisposalListDataTable->render('wastageDisposal.list');
    }

    public function store(CreateWastageDisposalRequest $request)
    {
        $input = $request->all();
        try {
            $input['created_by'] = auth()->user()->id;
            $wastage_disposal = $this->wastageDisposalRepository->create($input);

            if(isset($input['image']) && $input['image']){
                $cacheUpload = $this->uploadRepository->getByUuid($input['image']);
                $mediaItem   = $cacheUpload->getMedia('image')->first();
                $mediaItem->copy($wastage_disposal, 'image');
            }
            $this->productStockupdate($wastage_disposal->product_id);

        } catch (ValidatorException $e) {
            Flash::error($e->getMessage());
        }
        Flash::success(__('Save successfully',['operator' => __('Wastage Disposal')]));
        return redirect(route('wastageDisposal.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\SalesInvoice  $SalesInvoice
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        $wastage_disposal = $this->wastageDisposalRepository->findWithoutFail($id);
        if (empty($wastage_disposal)) {
            Flash::error(__('Not Found',['operator' => __('Wastage Disposal')]));
            return redirect(route('wastageDisposal.index'));
        }
        if($request->ajax()) {
            if($request->type=='edit') {
            
                $product = $wastage_disposal->product;
                $view = view('wastageDisposal.edit', compact('wastage_disposal','product'))->render();
                return ['status' => 'success', 'data' => $view];
            
            } else {
                return ['status' => 'success', 'data' => $wastage_disposal];
            } 
        }
        return view('wastageDisposal.show',compact('wastage_disposal'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\SalesInvoice  $SalesInvoice
     * @return \Illuminate\Http\Response
     */
    public function update($id, UpdateWastageDisposalRequest $request)
    {  
        $wastage_disposal_old = $this->wastageDisposalRepository->findWithoutFail($id);
        if (empty($wastage_disposal_old)) {
            Flash::error(__('Not Found',['operator' => __('Wastage Disposal')]));
            return redirect(route('wastageDisposal.index'));
        }
        $input = $request->all();
        try {
            $input['updated_by'] = auth()->user()->id;
            $wastage_disposal    = $this->wastageDisposalRepository->update($input,$id);

            if(isset($input['image']) && $input['image']){
                $cacheUpload = $this->uploadRepository->getByUuid($input['image']);
                $mediaItem   = $cacheUpload->getMedia('image')->first();
                $mediaItem->copy($wastage_disposal, 'image');
            }
            $this->productStockupdate($wastage_disposal->product_id);
        } catch (ValidatorException $e) {
            Flash::error($e->getMessage());
        }
        Flash::success(__('Save successfully',['operator' => __('Wastage Disposal')]));
        return redirect(route('wastageDisposal.index'));
    }

    public function destroy($id)
    {
        $wastage_disposal = $this->wastageDisposalRepository->findWithoutFail($id);
        if (empty($wastage_disposal)) {
            Flash::error('Wastage Disposal not found');
            return redirect(route('wastageDisposal.index'));
        }
        $this->wastageDisposalRepository->delete($id);
        $this->productStockupdate($wastage_disposal->product_id);
        Flash::success(__('Deleted successfully',['operator' => __('Wastage Disposal')]));
        return redirect(route('wastageDisposal.index'));
    }

    public function removeMedia(Request $request)
    {
        $input = $request->all();
        $wastage_disposal = $this->wastageDisposalRepository->findWithoutFail($input['id']);
        try {
            if ($wastage_disposal->hasMedia($input['collection'])) {
                $wastage_disposal->getFirstMedia($input['collection'])->delete();
            }
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }
    }
}
