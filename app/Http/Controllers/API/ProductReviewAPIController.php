<?php

namespace App\Http\Controllers\API;


use App\Models\ProductReview;
use App\Repositories\ProductReviewRepository;
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
 * Class ProductReviewController
 * @package App\Http\Controllers\API
 */

class ProductReviewAPIController extends Controller
{
    /** @var  ProductReviewRepository */
    private $productReviewRepository;
    /** @var UploadRepository */
    private $uploadRepository;

    public function __construct(ProductReviewRepository $productReviewRepo, UploadRepository $uploadRepo)
    {
        $this->productReviewRepository = $productReviewRepo;
        $this->uploadRepository = $uploadRepo;
    }

    /**
     * Display a listing of the ProductReview.
     * GET|HEAD /productReviews
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        try{
            $this->productReviewRepository->pushCriteria(new RequestCriteria($request));
        } catch (RepositoryException $e) {
            return $this->sendError($e->getMessage());
        }
        $productReviews = $this->productReviewRepository->all();

        return $this->sendResponse($productReviews->toArray(), 'Product Reviews retrieved successfully');
    }

    /**
     * Display the specified ProductReview.
     * GET|HEAD /productReviews/{id}
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        /** @var ProductReview $productReview */
        if (!empty($this->productReviewRepository)) {
            $productReview = $this->productReviewRepository->findWithoutFail($id);
        }

        if (empty($productReview)) {
            return $this->sendError('Product Review not found');
        }

        return $this->sendResponse($productReview->toArray(), 'Product Review retrieved successfully');
    }

    /**
     * Store a newly created ProductReview in storage.
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
            
            $productReview = $this->productReviewRepository->updateOrCreate($uniqueInput,$otherInput);
            
            if (array_key_exists("file", $input)) {
            
            if($productReview->hasMedia('file')){
                    $productReview->getFirstMedia('file')->delete();
                }

                $uuid = Str::uuid()->toString();
                $upload_data = array(
                    'field' => 'file',
                    'uuid'  => $uuid,
                    'file'  => $input['file']
                ); 
                $upload      = $this->uploadRepository->create($upload_data);
                $upload->addMedia($upload_data['file'])
                         ->withCustomProperties(['uuid' => $upload_data['uuid'], 'user_id' => $input['user_id']])
                         ->toMediaCollection($upload_data['field']);
                
                $cacheUpload = $this->uploadRepository->getByUuid($upload_data['uuid']);
                $mediaItem = $cacheUpload->getMedia('file')->first();
                $mediaItem->copy($productReview, 'file');     
                
            }
            
            
        } catch (ValidatorException $e) {
            return $this->sendError('Product Review not found');
        }

        return $this->sendResponse($productReview->toArray(),__('lang.saved_successfully',['operator' => __('lang.product_review')]));
    }
}
