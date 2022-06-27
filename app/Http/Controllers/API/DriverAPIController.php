<?php

namespace App\Http\Controllers\API;


use App\Models\Driver;
use App\Repositories\DriverRepository;
use App\Repositories\DeliveryZonesRepository;
use App\Repositories\OrderRepository;
use App\Repositories\UserRepository;
use App\Repositories\DeliveryTipsRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use InfyOm\Generator\Criteria\LimitOffsetCriteria;
use Prettus\Repository\Criteria\RequestCriteria;
use Illuminate\Support\Facades\Response;
use Prettus\Repository\Exceptions\RepositoryException;
use Flash;

/**
 * Class DriverController
 * @package App\Http\Controllers\API
 */

class DriverAPIController extends Controller
{
    /** @var  DriverRepository */
    private $driverRepository;
    private $deliveryZonesRepository;
    private $orderRepository;
    private $userRepository;
    private $deliveryTipsRepository;
    

    public function __construct(DriverRepository $driverRepo, DeliveryZonesRepository $deliveryZonesRepo, OrderRepository $orderRepo, UserRepository $userRepository, DeliveryTipsRepository $deliveryTipsRepo)
    {
        $this->driverRepository = $driverRepo;
        $this->deliveryZonesRepository = $deliveryZonesRepo;
        $this->orderRepository = $orderRepo;
        $this->userRepository = $userRepository;
        $this->deliveryTipsRepository = $deliveryTipsRepo;
    }

    /**
     * Display a listing of the Driver.
     * GET|HEAD /drivers
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        try{
            $this->driverRepository->pushCriteria(new RequestCriteria($request));
            $this->driverRepository->pushCriteria(new LimitOffsetCriteria($request));
        } catch (RepositoryException $e) {
            return $this->sendError($e->getMessage());
        }
        $drivers = $this->driverRepository->all();

        return $this->sendResponse($drivers->toArray(), 'Drivers retrieved successfully');
    }

    /**
     * Display the specified Driver.
     * GET|HEAD /drivers/{id}
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        /** @var Driver $driver */
        if (!empty($this->driverRepository)) {
            $driver = $this->driverRepository->findWithoutFail($id);
        }

        if (empty($driver)) {
            return $this->sendError('Driver not found');
        }

        return $this->sendResponse($driver->toArray(), 'Driver retrieved successfully');
    }
    
    public function deliveryZones() {
        try{
            $this->deliveryZonesRepository->get();
        } catch (RepositoryException $e) {
            return $this->sendError($e->getMessage());
        }
        $deliveryZones = $this->deliveryZonesRepository->all();

        return $this->sendResponse($deliveryZones->toArray(), 'Delivery Zones retrieved successfully');
    }
    
    public function deliveryFees(Request $request) {
        
        $latitude  = $request->latitude;
        $longitude = $request->longitude;
        
        $distance_data = file_get_contents('https://maps.googleapis.com/maps/api/distancematrix/json?origins='.setting('app_store_latitude').','.setting('app_store_longitude').'&destinations=side_of_road:'.$latitude.','.$longitude.'&mode=driving&key='.setting('google_api_key').'');
        $distance_arr = json_decode($distance_data);
        
        
        $distance_result   = $distance_arr->rows[0]->elements[0];
        $distance_in_meter = $distance_result->distance->value;
        $distance_duration = $distance_result->duration->text;
        $distance_in_km    = $distance_in_meter / 1000;
        //$delivery_charge   = $distance_in_km * 2; 
        $deliveryZones     = $this->deliveryZonesRepository->get();
        
        $delivery_charge   = 0;
        if(count($deliveryZones) > 0) {
            foreach ($deliveryZones as $key1 => $value) {
                if($distance_in_km >= $value->distance) {
                   $delivery_charge = $value->delivery_charge;
                }
            }                
        }
        
        if($distance_in_km > 0 && $distance_in_km <= 150) {
            
            $data['delivery_distance'] = number_format($distance_in_km,'2','.','');
            $data['delivery_duration'] = $distance_duration;
            $data['delivery_charge']   = $delivery_charge;
            $message = 'Delivery charge applied!';
            
        } else {
            
            $data    = 0;
            $message = 'Delivery Not Available on selected location'; 
            
        }
        
        return $this->sendResponse($data, $message);
        
        //return $this->sendResponse($distance_arr, 'Delivery Fee retrieved successfully');
    }
    
    public function getDriversDelivery(Request $request) {
      
      $user = $this->userRepository->where('api_token', $request->input('api_token'))->first();
        if (!$user) {
            return $this->sendError('User not found', 401);
        }
        try {
            $user_id = $user->id;

           $total_order_amount = $this->orderRepository->where('driver_id', $user_id)->sum('order_amount');
           $total_delivery_distance = $this->orderRepository->where('driver_id', $user_id)->sum('delivery_distance');
           $total_delivery_tips = $this->deliveryTipsRepository->where('user_id', $user_id)->sum('amount');
           
           $data['total_order_amount']=$total_order_amount;
           $data['total_delivery_distance']=$total_delivery_distance;
           $data['total_delivery_tips']=$total_delivery_tips;
            
        } catch (\Exception $e) {
            $this->sendError($e->getMessage(), 401);
        }
        return $this->sendResponse($data, 'Driver Delivery details retrieved successfully');
    }
    
    
}
