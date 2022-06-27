<?php

namespace App\Http\Controllers\API;


use App\Http\Controllers\Controller;
use App\Models\VendorStock;
use App\Repositories\VendorStockRepository;
use App\Repositories\VendorStockItemsRepository;
use Flash;
use Illuminate\Http\Request;
use InfyOm\Generator\Criteria\LimitOffsetCriteria;
use Prettus\Repository\Criteria\RequestCriteria;
use Prettus\Repository\Exceptions\RepositoryException;
use Prettus\Validator\Exceptions\ValidatorException;
use Illuminate\Support\Facades\Validator;
use App\Repositories\UserRepository;

/**
 * Class VendorStockAPIController
 * @package App\Http\Controllers\API
 */
class VendorStockAPIController extends Controller
{
    /** @var  VendorStockRepository */
    private $vendorStockRepository;
    private $vendorStockItemsRepository;
    private $userRepository;

    public function __construct(VendorStockRepository $vendorStockRepo, VendorStockItemsRepository $vendorStockItemsRepo, UserRepository $userRepository)
    {
        $this->vendorStockRepository        = $vendorStockRepo;
        $this->vendorStockItemsRepository   = $vendorStockItemsRepo;
        $this->userRepository               = $userRepository;
    }

    /**
     * Display a listing of the DeliveryAddress.
     * GET|HEAD /vendor_stocks
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        try {
            $this->vendorStockRepository->pushCriteria(new RequestCriteria($request));
        } catch (RepositoryException $e) {
            return $this->sendError($e->getMessage());
        }
        $vendor_stock = $this->vendorStockRepository
                             ->with('market')
                             ->with('items')
                             ->with('invoice')
                             ->where('market_id',auth()->user()->market->id)
                             ->get();

        return $this->sendResponse($vendor_stock->toArray(), 'Vendor Stock retrieved successfully');
    }

    /**
     * Display the specified DeliveryAddress.
     * GET|HEAD /vendor_stocks/{id}
     *
     * @param int $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        if (!empty($this->vendorStockRepository)) {
            $vendor_stock = $this->vendorStockRepository
                                 ->with('market')
                                 ->with('items')
                                 ->with('invoice')
                                 ->findWithoutFail($id);
        }

        if (empty($vendor_stock)) {
            return $this->sendError('Vendor Stock not found');
        }

        return $this->sendResponse($vendor_stock->toArray(), 'Vendor Stock retrieved successfully');
    }

    /**
     * Store a newly created DeliveryAddress in storage.
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        
        $validator = Validator::make($request->toArray(),[
            'date'          => 'required',
            'valid_date'    => 'required',
            'products'      => 'required|array|min:1',
            'products.*'    => 'required|min:1',
            'sub_total'     => 'required',
            'total'         => 'required',
            'pickup_charge' => 'required'
        ]);
        if ($validator->fails()) {    
            return $this->sendError($validator->messages());
        }
        $input = $request->all();

        try {
            $input['code']       = setting('app_invoice_prefix').'-VS-'.(autoIncrementId('vendor_stock'));
            $input['market_id']  = auth()->user()->market->id;  
            $input['created_by'] = auth()->user()->id;
            $vendor_stock = $this->vendorStockRepository->create($input);

            if($vendor_stock) {
                foreach($input['products'] as $product) :
                    $items = array(
                        'vendor_stock_id'   => $vendor_stock->id,
                        'product_id'        => $product['product_id'],
                        'product_name'      => $product['product_name'],
                        'product_hsn_code'  => $product['product_hsn_code'],
                        'mrp'               => $product['mrp'],
                        'quantity'          => $product['quantity'],
                        'unit'              => $product['unit'],
                        'unit_price'        => $product['unit_price'],
                        'amount'            => $product['amount'],
                        'created_by'        => auth()->user()->id,
                    );
                    $invoice_item = $this->vendorStockItemsRepository->create($items);    
                endforeach;

            }
            if(isset($input['image'][0]) && $input['image'][0]){
                $cacheUpload = $this->uploadRepository->getByUuid($input['image'][0]);
                $mediaItem = $cacheUpload->getMedia('image')->first();
                $mediaItem->copy($vendor_stock, 'image');
            }

        } catch (ValidatorException $e) {
            return $this->sendError($e->getMessage());
        }
        return $this->sendResponse($vendor_stock->toArray(), 'Delivery Address Saved successfully');
    }

    /**
     * Update the specified DeliveryAddress in storage.
     *
     * @param int $id
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update($id, Request $request)
    {
        $vendor_stock = $this->vendorStockRepository->findWithoutFail($id);
        if (empty($vendor_stock)) {
            return $this->sendError('Vendor Stock not found');
        }

        $validator = Validator::make($request->toArray(),[
            'date'          => 'required',
            'valid_date'    => 'required',
            'products'      => 'required|array|min:1',
            'products.*'    => 'required|min:1',
            'sub_total'     => 'required',
            'total'         => 'required',
            'pickup_charge' => 'required'
        ]);
        if ($validator->fails()) {    
            return $this->sendError($validator->messages());
        }
        $input = $request->all();
        try {
            $vendor_stock = $this->vendorStockRepository->update($input, $id);
        } catch (ValidatorException $e) {
            return $this->sendError($e->getMessage());
        }

        return $this->sendResponse($vendor_stock->toArray(), 'Vendor Stock Updated successfully');
    }

    /**
     * Remove the specified Address from storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $vendor_stock = $this->vendorStockRepository->findWithoutFail($id);
        if (empty($vendor_stock)) {
            return $this->sendError('Vendor Stock Not found');
        }
        $this->vendorStockRepository->delete($id);
        
        return $this->sendResponse($vendor_stock->toArray(), 'Vendor Stock Deleted successfully');

    }
    
}