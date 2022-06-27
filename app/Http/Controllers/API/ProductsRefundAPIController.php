<?php

namespace App\Http\Controllers\API;


use App\Models\ProductsRefund;
use App\Repositories\ProductsRefundRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use InfyOm\Generator\Criteria\LimitOffsetCriteria;
use Prettus\Repository\Criteria\RequestCriteria;
use Illuminate\Support\Facades\Response;
use Prettus\Repository\Exceptions\RepositoryException;
use Flash;
use Prettus\Validator\Exceptions\ValidatorException;
use App\Repositories\UploadRepository;
use Illuminate\Support\Str;

/**
 * Class ProductsRefundController
 * @package App\Http\Controllers\API
 */

class ProductsRefundAPIController extends Controller
{
    /** @var  ProductsRefundRepository */
    private $productsRefundRepository;
    /** @var UploadRepository */
    private $uploadRepository;

    public function __construct(ProductsRefundRepository $productsRefundRepo, UploadRepository $uploadRepo)
    {
        $this->productsRefundRepository = $productsRefundRepo;
        $this->uploadRepository = $uploadRepo;
    }

    /**
     * Display a listing of the ProductsRefund.
     * GET|HEAD /productsRefund
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        try{
            $this->productsRefundRepository->pushCriteria(new RequestCriteria($request));
            $this->productsRefundRepository->pushCriteria(new LimitOffsetCriteria($request));
        } catch (RepositoryException $e) {
            return $this->sendError($e->getMessage());
        }
        $productsrefund = $this->productsRefundRepository->all();

        return $this->sendResponse($productsrefund->toArray(), 'Products Refund retrieved successfully');
    }

    /**
     * Display the specified ProductsRefund.
     * GET|HEAD /productsRefund/{id}
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        /** @var ProductReview $productReview */
        if (!empty($this->productsRefundRepository)) {
            $productsRefund = $this->productsRefundRepository->findWithoutFail($id);
        }

        if (empty($productsRefund)) {
            return $this->sendError('Products Refund not found');
        }

        return $this->sendResponse($productsRefund->toArray(), 'Products Refund retrieved successfully');
    }

    /**
     * Store a newly created ProductsRefund in storage.
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $input = $request->all();
        $uniqueInput = $request->only("user_id","product_id");
        $otherInput = $request->except("user_id","product_id");
        
       
        try {
            
            
            $productsRefund = $this->productsRefundRepository->updateOrCreate($uniqueInput,$otherInput);
            
             $user_id = $request->input('user_id');
            
             if (array_key_exists("file", $input)) {
            if($productsRefund->hasMedia('file')){
                    $productsRefund->getFirstMedia('file')->delete();
                }
             
             $uuid = Str::uuid()->toString();
                $upload_data = array(
                    'field' => 'file',
                    'uuid'  => $uuid,
                    'file'  => $input['file']
                ); 
                $upload      = $this->uploadRepository->create($upload_data);
                $upload->addMedia($upload_data['file'])
                         ->withCustomProperties(['uuid' => $upload_data['uuid'], 'user_id' => $user_id])
                         ->toMediaCollection($upload_data['field']);
                
                $cacheUpload = $this->uploadRepository->getByUuid($upload_data['uuid']);
                $mediaItem = $cacheUpload->getMedia('file')->first();
                $mediaItem->copy($productsRefund, 'file');     
                $productsRefund['upload_status'] = 'uploaded';
             }
            
              $productsRefund['pickup'] = 'Your product refund will be within 7 days.';
            
        } catch (ValidatorException $e) {
            return $this->sendError('Products Refund not found');
        }

        return $this->sendResponse($productsRefund->toArray(),__('lang.saved_successfully',['operator' => __('lang.product_refund')]));
    }
}
