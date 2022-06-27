<?php

namespace App\Http\Controllers\API;


use App\Http\Controllers\Controller;
use App\Models\RequestCallback;
use App\Repositories\RequestCallbackRepository;
use Flash;
use Illuminate\Http\Request;
use InfyOm\Generator\Criteria\LimitOffsetCriteria;
use Prettus\Repository\Criteria\RequestCriteria;
use Prettus\Repository\Exceptions\RepositoryException;
use Prettus\Validator\Exceptions\ValidatorException;
use Illuminate\Support\Facades\Validator;
use App\Repositories\UserRepository;

/**
 * Class RequestCallbackAPIController
 * @package App\Http\Controllers\API
 */
class RequestCallbackAPIController extends Controller
{
    /** @var  RequestCallbackRepository */
    private $requestCallbackRepository;
    private $userRepository;

    public function __construct(RequestCallbackRepository $requestCallbackRepo, UserRepository $userRepository)
    {
        $this->requestCallbackRepository = $requestCallbackRepo;
        $this->userRepository            = $userRepository;
    }

    /**
     * Display a listing of the RequestCallback.
     * GET|HEAD /request_callback
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        try {
            $this->requestCallbackRepository->pushCriteria(new RequestCriteria($request));
        } catch (RepositoryException $e) {
            return $this->sendError($e->getMessage());
        }
        $request_callback = $this->requestCallbackRepository->where('user_id',auth()->user()->id)->get();

        return $this->sendResponse($request_callback->toArray(), 'Request Callback retrieved successfully');
    }

    /**
     * Display the specified RequestCallback.
     * GET|HEAD /request_callback/{id}
     *
     * @param int $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        if (!empty($this->requestCallbackRepository)) {
            $request_callback = $this->requestCallbackRepository->findWithoutFail($id);
        }

        if (empty($request_callback)) {
            return $this->sendError('Request Callback not found');
        }

        return $this->sendResponse($request_callback->toArray(), 'Request Callback retrieved successfully');
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
            'name'           => 'required',
            'mobile'         => 'required',
            'email'          => 'required',
            'message'        => 'required'
        ]);
        if ($validator->fails()) {    
            return $this->sendError($validator->messages());
        }
        $input = $request->all();
        try {
            $input['user_id'] = auth()->user()->id;
            $request_callback = $this->requestCallbackRepository->create($input);
        } catch (ValidatorException $e) {
            return $this->sendError($e->getMessage());
        }
        return $this->sendResponse($request_callback->toArray(), 'Request Callback Saved successfully');
    }

    /**
     * Update the specified RequestCallback in storage.
     *
     * @param int $id
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update($id, Request $request)
    {
        $request_callback = $this->requestCallbackRepository->findWithoutFail($id);
        if (empty($request_callback)) {
            return $this->sendError('Request Callback not found');
        }

        $validator = Validator::make($request->toArray(),[
            'name'           => 'required',
            'mobile'         => 'required',
            'email'          => 'required',
            'message'        => 'required'
        ]);
        if ($validator->fails()) {    
            return $this->sendError($validator->messages());
        }
        
        $input = $request->all();
        try {
            $input['user_id'] = auth()->user()->id;
            $request_callback = $this->requestCallbackRepository->update($input, $id);
        } catch (ValidatorException $e) {
            return $this->sendError($e->getMessage());
        }

        return $this->sendResponse($request_callback->toArray(), 'Request Callback Updated successfully');
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
        $request_callback = $this->requestCallbackRepository->findWithoutFail($id);
        if (empty($request_callback)) {
            return $this->sendError('Request Callback Not found');
        }
        $this->requestCallbackRepository->delete($id);
        
        return $this->sendResponse($request_callback->toArray(), 'Request Callback Deleted successfully');

    }

}