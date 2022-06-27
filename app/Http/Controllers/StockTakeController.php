<?php

namespace App\Http\Controllers;

use App\DataTables\StockTakeDataTable;
use App\Http\Requests\CreateStockTakeRequest;
use App\Http\Requests\UpdateStockTakeRequest;
use App\Repositories\CategoryRepository;
use App\Repositories\MarketRepository;
use App\Repositories\ProductRepository;
use App\Repositories\InventoryRepository;
use App\Repositories\CustomerGroupsRepository;
use App\Repositories\ProductPriceVariationRepository;
use App\Repositories\UomRepository;
use App\Repositories\UploadRepository;
use App\Repositories\StockTakeRepository;
use App\Repositories\StockTakeItemsRepository;
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

class StockTakeController extends Controller
{
    
    /** @var  ProductRepository */
    private $productRepository;

    /*** @var UploadRepository*/
    private $uploadRepository;

    /*** @var MarketRepository*/
    private $marketRepository;

    /*** @var CategoryRepository*/
    private $categoryRepository;
    
    /*** @var CustomerGroupsRepository*/
    private $customerGroupRepository;
    
    /*** @var ProductPriceVariationRepository*/
    private $productPriceVariationRepository;
    
    /*** @var UomRepository*/
    private $uomRepository;

    /*** @var StockTakeRepository*/
    private $stockTakeRepository;

    /*** @var StockTakeItemsRepository*/
    private $stockTakeItemsRepository;


    public function __construct(ProductRepository $productRepo, UploadRepository $uploadRepo, MarketRepository $marketRepo, CategoryRepository $categoryRepo, InventoryRepository $inventoryRepo, CustomerGroupsRepository $customerGroupRepo, UomRepository $uomRepo, ProductPriceVariationRepository $productPriceVariationRepo, StockTakeRepository $stockTakeRepo, StockTakeItemsRepository $stockTakeItemsRepo)
    {
        parent::__construct();
        $this->productRepository = $productRepo;
        $this->uploadRepository = $uploadRepo;
        $this->marketRepository = $marketRepo;
        $this->categoryRepository = $categoryRepo;
        $this->inventoryRepository = $inventoryRepo;
        $this->customerGroupRepository = $customerGroupRepo;
        $this->productPriceVariationRepository = $productPriceVariationRepo;
        $this->uomRepository = $uomRepo;
        $this->stockTakeRepository = $stockTakeRepo;
        $this->stockTakeItemsRepository = $stockTakeItemsRepo;
    }

    /**
     * Display a listing of the Product.
     *
     * @param StockTakeDataTable $stockTakeDataTable
     * @return Response
     */
    public function index(StockTakeDataTable $stockTakeDataTable)
    {   
        return $stockTakeDataTable->render('stock_take.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {   
        $products      = $this->productRepository->get();
        $stock_take_no = setting('app_invoice_prefix').'-STK-'.(autoIncrementId('stock_take'));
        return view('stock_take.create',compact('products','stock_take_no'));
    }

    public function store(CreateStockTakeRequest $request)
    {
        $input = $request->all();
        if(count(array_filter($input['counted'])) <= 0) {
            Flash::error(__('Please add stock quantity',['operator' => __('Stock Take')]));
            return redirect(route('stockTake.create'));
        }
        try {
            $input['created_by'] = auth()->user()->id;
            $stock_take      = $this->stockTakeRepository->create($input);

            if($stock_take) {
                for($i=0; $i<count($input['product_id']); $i++) :
                    $items = array(

                        'stock_take_id' => $stock_take->id,
                        'product_id'    => $input['product_id'][$i],
                        'product_name'  => $input['product_name'][$i],
                        'product_code'  => $input['product_code'][$i],
                        'current'       => $input['current'][$i],
                        'current_unit'  => $input['current_unit'][$i],
                        'counted'       => $input['counted'][$i],
                        'counted_unit'  => $input['counted_unit'][$i],
                        'missing'       => $input['missing'][$i],
                        'missing_unit'  => $input['missing_unit'][$i],
                        'wastage'       => $input['wastage'][$i],
                        'wastage_unit'  => $input['wastage_unit'][$i],
                        'notes'         => $input['item_notes'][$i],
                        'created_by'    => auth()->user()->id
                    );
                    if($input['counted'][$i] > 0 || $input['missing'][$i] > 0 || $input['wastage'][$i] > 0) {
                        $stock_take_item = $this->stockTakeItemsRepository->create($items);
                    }    
                endfor;
            }
            if(isset($input['image'][0]) && $input['image'][0]){
                $cacheUpload = $this->uploadRepository->getByUuid($input['image'][0]);
                $mediaItem = $cacheUpload->getMedia('image')->first();
                $mediaItem->copy($stock_take, 'image');
            }
        } catch (ValidatorException $e) {
            Flash::error($e->getMessage());
        }
        Flash::success(__('Save successfully',['operator' => __('Stock Take')]));
        return redirect(route('stockTake.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\PurchaseOrder  $PurchaseOrder
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        $stock_take = $this->stockTakeRepository->with('items')->with('items.currentunit')->with('items.product')->findWithoutFail($id);
        if (empty($stock_take)) {
            Flash::error(__('Not Found',['operator' => __('Stock Take')]));
            return redirect(route('stockTake.index'));
        }
        if($request->ajax()) {
            return ['status' => 'success', 'data' => $stock_take]; 
        }
        return view('stock_take.show',compact('stock_take'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\PurchaseOrder  $PurchaseOrder
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {   
        $stock_take = $this->stockTakeRepository->findWithoutFail($id);
        if (empty($stock_take)) {
            Flash::error(__('Not Found',['operator' => __('Stock Take')]));
            return redirect(route('stockTake.index'));
        }
        return view('stock_take.edit',compact('stock_take'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\PurchaseOrder  $PurchaseOrder
     * @return \Illuminate\Http\Response
     */
    public function update($id, Request $request)
    {  
        $stock_take_old = $this->stockTakeRepository->findWithoutFail($id);
        if (empty($stock_take_old)) {
            Flash::error(__('Not Found',['operator' => __('Stock Take')]));
            return redirect(route('stockTake.index'));
        }

        $input = $request->all();

        if($request->ajax()) {
            if($input['type']=='status-update') {
                $stock_take = $this->stockTakeRepository->findWithoutFail($id);
                if(!empty($stock_take)) {
                    $update = $this->stockTakeRepository->update(['status'=>$input['status']],$input['id']);
                    if($update) {
                        foreach($stock_take->items as $item) {

                            if($item->wastage > 0) {
                                //Inventory
                                    $this->addInventory(
                                        $item->product->id,
                                        null,
                                        'added',
                                        'reduce',
                                        $item->wastage,
                                        $item->wastage_unit,
                                        $item->product_name,
                                        'usage',
                                        'wastage'
                                    );
                                    $this->productStockupdate($item->product->id);
                                //Inventory
                            }

                            if($item->missing > 0) {
                                //Inventory
                                    $this->addInventory(
                                        $item->product->id,
                                        null,
                                        'added',
                                        'reduce',
                                        $item->missing,
                                        $item->missing_unit,
                                        $item->product_name,
                                        'usage',
                                        'missing'
                                    );
                                    $this->productStockupdate($item->product->id);
                                //Inventory
                            }

                        }
                    }
                    return $update;
                }
            }   
        }

        try {
            $input['updated_by'] = auth()->user()->id;
            $stock_take          = $this->stockTakeRepository->update($input,$id);
            
            if($stock_take) {
                for($i=0; $i<count($input['product_id']); $i++) :
                    $items = array(
                        'stock_take_id' => $stock_take->id,
                        'product_id'    => $input['product_id'][$i],
                        'product_name'  => $input['product_name'][$i],
                        'product_code'  => $input['product_code'][$i],
                        'current'       => $input['current'][$i],
                        'current_unit'  => $input['current_unit'][$i],
                        'counted'       => $input['counted'][$i],
                        'counted_unit'  => $input['counted_unit'][$i],
                        'missing'       => $input['missing'][$i],
                        'missing_unit'  => $input['missing_unit'][$i],
                        'wastage'       => $input['wastage'][$i],
                        'wastage_unit'  => $input['wastage_unit'][$i],
                        'notes'         => $input['item_notes'][$i],
                        'updated_by'    => auth()->user()->id
                    );
                    if(isset($input['stock_take_item_id'][$i]) && $input['stock_take_item_id'][$i] > 0) {
                        $stock_take_item = $this->stockTakeItemsRepository->update($items,$input['stock_take_item_id'][$i]);
                    } else {
                        $stock_take_item = $this->stockTakeItemsRepository->create($items);
                    }    
                endfor;   
            }

            if(isset($input['image'][0]) && $input['image'][0]){
                $cacheUpload = $this->uploadRepository->getByUuid($input['image'][0]);
                $mediaItem = $cacheUpload->getMedia('image')->first();
                $mediaItem->copy($stock_take, 'image');
            }
        } catch (ValidatorException $e) {
            Flash::error($e->getMessage());
        }
        Flash::success(__('Save successfully',['operator' => __('Stock Take')]));
        return redirect(route('stockTake.index'));
    
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\PurchaseOrder  $PurchaseOrder
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $stock_take = $this->stockTakeRepository->with('items')->findWithoutFail($id);
        if (empty($stock_take)) {
            Flash::error('Stock Take not found');
            return redirect(route('stockTake.index'));
        }
        $this->stockTakeRepository->delete($id);
        Flash::success(__('Deleted successfully',['operator' => __('Stock Take')]));
        return redirect(route('stockTake.index'));
    }


    public function print($id,$type,$view_type,Request $request)
    {   
        $stock_take = $this->stockTakeRepository->with('items')->findWithoutFail($id);
        if (empty($stock_take)) {
            Flash::error('Stock Take not found');
            return redirect(route('stockTake.index'));
        }
        $pdf      = PDF::loadView('stock_take.print', compact('stock_take','type'));
        $filename = $stock_take->code.'.pdf';
        
        if($view_type=='print') {
            return $pdf->stream($filename);
        } elseif($view_type=='download') {
            return $pdf->download($filename);
        }
    }

    
}
