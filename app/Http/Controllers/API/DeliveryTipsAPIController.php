<?php

namespace App\Http\Controllers\API;


use App\Models\DeliveryTips;
use App\Repositories\DeliveryTipsRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use InfyOm\Generator\Criteria\LimitOffsetCriteria;
use Prettus\Repository\Criteria\RequestCriteria;
use Illuminate\Support\Facades\Response;
use Prettus\Repository\Exceptions\RepositoryException;
use Flash;
use Prettus\Validator\Exceptions\ValidatorException;

/**
 * Class DeliveryTipsController
 * @package App\Http\Controllers\API
 */

class DeliveryTipsAPIController extends Controller
{
    /** @var  DeliveryTipsRepository */
    private $deliveryTipsRepository;
  

    public function __construct(DeliveryTipsRepository $deliveryTipsRepo)
    {
        $this->deliveryTipsRepository = $deliveryTipsRepo;
    }

    /**
     * Display a listing of the DeliveryTips.
     * GET|HEAD /deliveryTips
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        try{
            $this->deliveryTipsRepository->pushCriteria(new RequestCriteria($request));
            $this->deliveryTipsRepository->pushCriteria(new LimitOffsetCriteria($request));
        } catch (RepositoryException $e) {
            return $this->sendError($e->getMessage());
        }
        $deliveryTips = $this->deliveryTipsRepository->all();

        return $this->sendResponse($deliveryTips->toArray(), 'Delivery Tips retrieved successfully');
    }

    /**
     * Display the specified DeliveryTips.
     * GET|HEAD /deliveryTips/{id}
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        /** @var DeliveryTips $deliveryTips */
        if (!empty($this->deliveryTipsRepository)) {
            $deliveryTips = $this->deliveryTipsRepository->findWithoutFail($id);
        }

        if (empty($deliveryTips)) {
            return $this->sendError('Delivery Tips not found');
        }

        return $this->sendResponse($deliveryTips->toArray(), 'Delivery Tips retrieved successfully');
    }

    /**
     * Store a newly created DeliveryTips in storage.
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $input = $request->all();
        try {
            $deliveryTips = $this->deliveryTipsRepository->create($input);

        } catch (ValidatorException $e) {
            return $this->sendError($e->getMessage());
        }

        return $this->sendResponse($deliveryTips->toArray(), __('lang.saved_successfully', ['operator' => __('lang.delivery_tips')]));
    }
    
    public function getDriverTipsById(Request $request)
    {
        try{
            $user_id = $request->user_id;
            $deliveryTips = $this->deliveryTipsRepository->where('user_id',$user_id)->get();
        } catch (RepositoryException $e) {
            return $this->sendError($e->getMessage());
        }
       

        return $this->sendResponse($deliveryTips->toArray(), 'Delivery Tips retrieved successfully');
    }
}
