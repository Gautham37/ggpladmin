<?php

namespace App\Http\Controllers\API;


use App\Http\Controllers\Controller;
use App\Models\DeliveryAddress;
use App\Repositories\DeliveryAddressRepository;
use Flash;
use Illuminate\Http\Request;
use InfyOm\Generator\Criteria\LimitOffsetCriteria;
use Prettus\Repository\Criteria\RequestCriteria;
use Prettus\Repository\Exceptions\RepositoryException;
use Prettus\Validator\Exceptions\ValidatorException;
use Illuminate\Support\Facades\Validator;
use App\Repositories\UserRepository;

/**
 * Class DeliveryAddressController
 * @package App\Http\Controllers\API
 */
class DeliveryAddressAPIController extends Controller
{
    /** @var  DeliveryAddressRepository */
    private $deliveryAddressRepository;
    private $userRepository;

    public function __construct(DeliveryAddressRepository $deliveryAddressRepo, UserRepository $userRepository)
    {
        $this->deliveryAddressRepository = $deliveryAddressRepo;
        $this->userRepository           = $userRepository;
    }

    /**
     * Display a listing of the DeliveryAddress.
     * GET|HEAD /deliveryAddresses
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        try {
            $this->deliveryAddressRepository->pushCriteria(new RequestCriteria($request));
        } catch (RepositoryException $e) {
            return $this->sendError($e->getMessage());
        }
        $deliveryAddresses = $this->deliveryAddressRepository->where('user_id',auth()->user()->id)->get();

        return $this->sendResponse($deliveryAddresses->toArray(), 'Delivery Addresses retrieved successfully');
    }

    /**
     * Display the specified DeliveryAddress.
     * GET|HEAD /deliveryAddresses/{id}
     *
     * @param int $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        if (!empty($this->deliveryAddressRepository)) {
            $deliveryAddress = $this->deliveryAddressRepository->findWithoutFail($id);
        }

        if (empty($deliveryAddress)) {
            return $this->sendError('Delivery Address not found');
        }

        return $this->sendResponse($deliveryAddress->toArray(), 'Delivery Address retrieved successfully');
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
            'user_id'        => 'required|exists:users,id',
            'address_line_1' => 'required',
            'town'           => 'required',
            'city'           => 'required',
            'state'          => 'required',
            'pincode'        => 'required',
            'latitude'       => 'required',
            'longitude'      => 'required',
        ]);
        if ($validator->fails()) {    
            return $this->sendError($validator->messages());
        }
        $input = $request->all();
        try {
            $deliveryAddress = $this->deliveryAddressRepository->create($input);
        } catch (ValidatorException $e) {
            return $this->sendError($e->getMessage());
        }
        return $this->sendResponse($deliveryAddress->toArray(), 'Delivery Address Saved successfully');
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
        $deliveryAddress = $this->deliveryAddressRepository->findWithoutFail($id);
        if (empty($deliveryAddress)) {
            return $this->sendError('Delivery Address not found');
        }

        $validator = Validator::make($request->toArray(),[
            'user_id'        => 'required|exists:users,id',
            'address_line_1' => 'required',
            'town'           => 'required',
            'city'           => 'required',
            'state'          => 'required',
            'pincode'        => 'required',
            'latitude'       => 'required',
            'longitude'      => 'required',
            'is_default'     => 'required'
        ]);
        if ($validator->fails()) {    
            return $this->sendError($validator->messages());
        }
        
        $input = $request->all();
        if ($input['is_default'] == true){
            $this->deliveryAddressRepository->initIsDefault($input['user_id']);
        }
        try {
            $deliveryAddress = $this->deliveryAddressRepository->update($input, $id);
        } catch (ValidatorException $e) {
            return $this->sendError($e->getMessage());
        }

        return $this->sendResponse($deliveryAddress->toArray(), 'Delivery Address Updated successfully');
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
        $deliveryAddress = $this->deliveryAddressRepository->findWithoutFail($id);
        if (empty($deliveryAddress)) {
            return $this->sendError('Delivery Address Not found');
        }
        $this->deliveryAddressRepository->delete($id);
        
        return $this->sendResponse($deliveryAddress->toArray(), 'Delivery Address Deleted successfully');

    }
    
    
    //get delivery address estimated time duration
    public function getDeliveryDuration(Request $request) {
        
        $user = $this->userRepository->findByField('api_token', $request->input('api_token'))->first();
        if (!$user) {
            return $this->sendError('User not found', 401);
        }
        try {
            $user_id = $user->id;
            $delivery_address_id = $request->input('delivery_address_id');
            
            $api = setting('google_api_key');
            $app_store_latitude = setting('app_store_latitude');
            $app_store_longitude = setting('app_store_longitude');
            
            $deliveryAddress = $this->deliveryAddressRepository->where('user_id', $user_id)->where('id', $delivery_address_id)->first();
            
            $delivery_address_latitude = $deliveryAddress->latitude;
            $delivery_address_longitude = $deliveryAddress->longitude;
            
            
            $api = file_get_contents("https://maps.googleapis.com/maps/api/distancematrix/json?units=imperial&origins=".$app_store_latitude.",".$app_store_longitude."&destinations=".$delivery_address_latitude.",".$delivery_address_longitude."&key=".$api);
            $data = json_decode($api);
         
            $deliveryAddress->distance = ((int)$data->rows[0]->elements[0]->distance->value / 1000).' Km';
            $deliveryAddress->duration = $data->rows[0]->elements[0]->duration->text;
            
        } catch (\Exception $e) {
            $this->sendError($e->getMessage(), 401);
        }
        return $this->sendResponse($deliveryAddress, 'Delivery details retrieved successfully');
    }
    
   
}