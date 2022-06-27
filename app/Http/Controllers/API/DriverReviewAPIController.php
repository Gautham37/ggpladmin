<?php

namespace App\Http\Controllers\API;


use App\Models\DriverReview;
use App\Repositories\DriverReviewRepository;
use App\Repositories\OrderRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use InfyOm\Generator\Criteria\LimitOffsetCriteria;
use Prettus\Repository\Criteria\RequestCriteria;
use Illuminate\Support\Facades\Response;
use Prettus\Repository\Exceptions\RepositoryException;
use Flash;
use Prettus\Validator\Exceptions\ValidatorException;
use Illuminate\Support\Facades\Validator;
use App\Repositories\UploadRepository;
use App\Repositories\UserRepository;
use Illuminate\Support\Str;
use DB;

/**
 * Class DriverReviewController
 * @package App\Http\Controllers\API
 */

class DriverReviewAPIController extends Controller
{
    /** @var  DriverReviewRepository */
    private $driverReviewRepository;
    /** @var UploadRepository */
    private $uploadRepository;
     /** @var  UserRepository */
    private $userRepository;
    /** @var  OrderRepository */
    private $orderRepository;

    public function __construct(DriverReviewRepository $driverReviewRepo, UploadRepository $uploadRepo, UserRepository $userRepository, OrderRepository $orderRepo)
    {
        $this->driverReviewRepository = $driverReviewRepo;
        $this->uploadRepository = $uploadRepo;
        $this->userRepository       = $userRepository;
        $this->orderRepository = $orderRepo;
    }

    /**
     * Display a listing of the DriverReview.
     * GET|HEAD /driverReview
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        try{
            $this->driverReviewRepository->pushCriteria(new RequestCriteria($request));
        } catch (RepositoryException $e) {
            return $this->sendError($e->getMessage());
        }
        $driverReviews = $this->driverReviewRepository->all();
        return $this->sendResponse($driverReviews->toArray(), 'Driver Reviews retrieved successfully');
    }

    /**
     * Display the specified DriverReview.
     * GET|HEAD /driverReview/{id}
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        /** @var DriverReview $driverReview */
        if (!empty($this->driverReviewRepository)) {
            $driverReview = $this->driverReviewRepository->findWithoutFail($id);
        }
        if (empty($driverReview)) {
            return $this->sendError('Driver Review not found');
        }
        return $this->sendResponse($driverReview->toArray(), 'Driver Review retrieved successfully');
    }

    /**
     * Store a newly created DriverReview in storage.
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {   
        $validator = Validator::make($request->toArray(),[
            'review'            => 'required',
            'rate'              => 'required',
            'driver_user_id'    => 'required',
            'user_id'           => 'required',
        ]);
        if ($validator->fails()) {    
            return $this->sendError($validator->messages());
        }

        $input = $request->all();
        
        try {

            $driverReview = $this->driverReviewRepository->create($input);
           
            if (array_key_exists("file", $input)) {

                if($driverReview->hasMedia('file')){
                    $driverReview->getFirstMedia('file')->delete();
                }
             
                $uuid = Str::uuid()->toString();
                $upload_data = array(
                    'field' => 'file',
                    'uuid'  => $uuid,
                    'file'  => $input['file']
                ); 
                $upload      = $this->uploadRepository->create($upload_data);
                $upload->addMedia($upload_data['file'])
                         ->withCustomProperties(['uuid' => $upload_data['uuid'], 'driver_id' => $input['driver_id']])
                         ->toMediaCollection($upload_data['field']);
                
                $cacheUpload = $this->uploadRepository->getByUuid($upload_data['uuid']);
                $mediaItem = $cacheUpload->getMedia('file')->first();
                $mediaItem->copy($driverReview, 'file');         
            
            }
            
        } catch (ValidatorException $e) {
            return $this->sendError('Driver Review not found');
        }

        return $this->sendResponse($driverReview->toArray(),'Driver Review saved successfully');
    }
    
    
    //get particular driver reviews given by customer or farmer
    public function getDriverReviewsById(Request $request)
    {
        try{
            $driver_id = $request->driver_id;
            $driverReview = $this->driverReviewRepository->where('driver_id',$driver_id)->get();
            foreach($driverReview as $key=>$val)
            {
                 $user = $this->userRepository->where('id',$val->user_id)->first();
                 
                  $driverReview[$key]->customer_name  = $user->name;
                  $driverReview[$key]->customer_email  = $user->email;
                  if($val) {
                          $driverReview[$key]->profile_image  = $user->getFirstMedia('avatar');
                        } else {
                           $driverReview[$key]->profile_image = ''; 
                        }
                
            }
           
        } catch (RepositoryException $e) {
            return $this->sendError($e->getMessage());
        }
       
        return $this->sendResponse($driverReview->toArray(), 'Driver Review retrieved successfully');
    }
}
