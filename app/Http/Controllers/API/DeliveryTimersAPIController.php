<?php

namespace App\Http\Controllers\API;


use App\Models\DriversTimers;
use App\Repositories\DriversTimersRepository;
use App\Repositories\UserRepository;
use App\Repositories\UploadRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use InfyOm\Generator\Criteria\LimitOffsetCriteria;
use Prettus\Repository\Criteria\RequestCriteria;
use Illuminate\Support\Facades\Response;
use Prettus\Repository\Exceptions\RepositoryException;
use Flash;
use Prettus\Validator\Exceptions\ValidatorException;
use Illuminate\Support\Str;
use DB;

/**
 * Class DriversTimersController
 * @package App\Http\Controllers\API
 */

class DeliveryTimersAPIController extends Controller
{
    /** @var  DriversTimersRepository */
    private $driversTimersRepository;
    
     /** @var  UserRepository */
    private $userRepository;
    
     /** @var UploadRepository */
    private $uploadRepository;
 

    public function __construct(DriversTimersRepository $driversTimersRepo, UserRepository $userRepository, UploadRepository $uploadRepo)
    {
        $this->driversTimersRepository = $driversTimersRepo;
        $this->userRepository       = $userRepository;
        $this->uploadRepository = $uploadRepo;
    }

    /**
     * Display a listing of the Complaints.
     * GET|HEAD /complaints
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        try{
            $this->driversTimersRepository->pushCriteria(new RequestCriteria($request));
            $this->driversTimersRepository->pushCriteria(new LimitOffsetCriteria($request));
        } catch (RepositoryException $e) {
            return $this->sendError($e->getMessage());
        }
        $driversTimers = $this->driversTimersRepository->all();

        return $this->sendResponse($driversTimers->toArray(), 'Drivers Timers retrieved successfully');
    }

   

    /**
     * Store a newly created Complaints in storage.
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function driverClockIn(Request $request)
    {
       
     
        try {
            
             $this->validate($request, [
                'api_token' => 'required'
            ]);
            

           $user = $this->userRepository->findByField('api_token', $request->input('api_token'))->first();
           if (!$user) {
            return $this->sendError('User not found', 401);
           }
           
            date_default_timezone_set('Asia/Kolkata');
           
            $input['user_id'] = $user->id;
            $input['clock_in'] = date('Y-m-d H:i:s');

            $image = $request->file('clockin_image');
           
            if($image!='')
            {
            $name = Str::uuid()->toString();
          
            $destinationPath = public_path('/storage/driverTimersImages/');
            $image->move($destinationPath, $name);
            }else
            {
                $name = '';
            }
            
            $input['clockin_image'] = $name;
            $driversTimers = $this->driversTimersRepository->create($input);
            
         /*   $input1 = $request->all();
             if (array_key_exists("driver_timers_image", $input1)) {
             if($driversTimers->hasMedia('driver_timers_image')){
                    $driversTimers->getFirstMedia('driver_timers_image')->delete();
                }
            
             $uuid = Str::uuid()->toString();
                $upload_data = array(
                    'field' => 'driver_timers_image',
                    'uuid'  => $uuid,
                    'file'  => $input1['driver_timers_image']
                ); 
                $upload      = $this->uploadRepository->create($upload_data);
                $upload->addMedia($upload_data['file'])
                         ->withCustomProperties(['uuid' => $upload_data['uuid'], 'user_id' => $user->id])
                         ->toMediaCollection($upload_data['field']);
                
                $cacheUpload = $this->uploadRepository->getByUuid($upload_data['uuid']);
                $mediaItem = $cacheUpload->getMedia('driver_timers_image')->first();
                $mediaItem->copy($driversTimers, 'driver_timers_image');     
                $complaints['upload_status'] = 'uploaded';
             }
            */
           
        } catch (ValidatorException $e) {
            return $this->sendError('Drivers Timers not found');
        }

        return $this->sendResponse($driversTimers->toArray(),__('lang.saved_successfully',['operator' => __('lang.drivers_timers')]));
    }
    
     /**
     * Store a newly created Complaints in storage.
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function driverClockOut(Request $request)
    {
       
        try {
            
             $this->validate($request, [
                'api_token' => 'required'
            ]);
            

           $user = $this->userRepository->findByField('api_token', $request->input('api_token'))->first();
           if (!$user) {
            return $this->sendError('User not found', 401);
           }
            date_default_timezone_set('Asia/Kolkata'); 
           
            $image = $request->file('clockout_image');
            
            if($image!='')
            {
            
            $name = Str::uuid()->toString();
            $destinationPath = public_path('/storage/driverTimersImages/');
            $image->move($destinationPath, $name);
            
            }else
            {
              $name = '';  
            }
           
          $countDriversTimers = DB::table('drivers_timers')->where('user_id',$user->id)->orderBy('id', 'DESC')->count();
          //$countDriversTimers = DB::table('drivers_timers')->where('user_id',$user->id)->whereNull('clock_out')->orderBy('id', 'DESC')->count();

          if($countDriversTimers>0){
            
           $getDriversTimers = DB::table('drivers_timers')->where('user_id',$user->id)->orderBy('id', 'DESC')->first();
           $updateDriversTimers = $this->driversTimersRepository->where('id',$getDriversTimers->id)->update(['clockout_image'=>$name,'clock_out' => date('Y-m-d H:i:s')]);
           
           $driversTimers = $this->driversTimersRepository->where('id',$getDriversTimers->id)->orderBy('id', 'desc')->first();
           }else
           {
                return $this->sendError('User not found', 401);
           }
           
        } catch (ValidatorException $e) {
            return $this->sendError('Drivers Timers not found');
        }

        return $this->sendResponse($driversTimers->toArray(),__('lang.saved_successfully',['operator' => __('lang.drivers_timers')]));
    }
    
  
    
}
