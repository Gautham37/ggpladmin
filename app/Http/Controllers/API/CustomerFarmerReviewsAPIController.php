<?php

namespace App\Http\Controllers\API;


use App\Models\CustomerFarmerReviews;
use App\Repositories\CustomerFarmerReviewsRepository;
use App\Repositories\OrderRepository;
use App\Repositories\SupplierRequestRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use InfyOm\Generator\Criteria\LimitOffsetCriteria;
use Prettus\Repository\Criteria\RequestCriteria;
use Illuminate\Support\Facades\Response;
use Prettus\Repository\Exceptions\RepositoryException;
use Flash;
use Prettus\Validator\Exceptions\ValidatorException;
use App\Repositories\UploadRepository;
use App\Repositories\UserRepository;
use Illuminate\Support\Str;
use DB;

/**
 * Class CustomerFarmerReviewsController
 * @package App\Http\Controllers\API
 */

class CustomerFarmerReviewsAPIController extends Controller
{
    /** @var  CustomerFarmerReviewsRepository */
    private $customerFarmerReviewsRepository;
    /** @var UploadRepository */
    private $uploadRepository;
     /** @var  UserRepository */
    private $userRepository;
     /** @var  OrderRepository */
    private $orderRepository;
    /** @var  SupplierRequestRepository */
    private $supplierRequestRepository;

    public function __construct(CustomerFarmerReviewsRepository $customerFarmerReviewsRepo, UploadRepository $uploadRepo, UserRepository $userRepository, OrderRepository $orderRepo, SupplierRequestRepository $supplierRequestRepo)
    {
        $this->customerFarmerReviewsRepository = $customerFarmerReviewsRepo;
        $this->uploadRepository = $uploadRepo;
        $this->userRepository = $userRepository;
        $this->orderRepository = $orderRepo;
        $this->supplierRequestRepository  = $supplierRequestRepo;
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
            $this->customerFarmerReviewsRepository->pushCriteria(new RequestCriteria($request));
            $this->customerFarmerReviewsRepository->pushCriteria(new LimitOffsetCriteria($request));
        } catch (RepositoryException $e) {
            return $this->sendError($e->getMessage());
        }
        $customerFarmerReviews = $this->customerFarmerReviewsRepository->all();

        return $this->sendResponse($customerFarmerReviews->toArray(), 'Customer Farmer Reviews retrieved successfully');
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
        if (!empty($this->customerFarmerReviewsRepository)) {
            $customerFarmerReviews = $this->customerFarmerReviewsRepository->findWithoutFail($id);
        }

        if (empty($customerFarmerReviews)) {
            return $this->sendError('Customer Farmer Reviews not found');
        }

        return $this->sendResponse($customerFarmerReviews->toArray(), 'Customer Farmer Reviews retrieved successfully');
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
       
        $input = $request->all();
       
        try {
            
              $this->validate($request, [
                'api_token' => 'required',
                'rate' => 'required',
                'option_type' => 'required',
                'option_id' => 'required',
            ]);
            

           $user = $this->userRepository->findByField('api_token', $request->input('api_token'))->first();
           if (!$user) {
            return $this->sendError('User not found', 401);
           }
          
           if($request->input('option_type')=='1'){
           $order = $this->orderRepository->where('id', $request->input('option_id'))->first();
           $user_id = $order->user_id;
           }
           else
           {
           $supplier_request = $this->supplierRequestRepository->where('id', $request->input('option_id'))->first();
           $user_market  = DB::table('user_markets')->where('market_id',$supplier_request->market_id)->first();   
           $user_id = $user_market->user_id;
           }
           $input['driver_id'] = $user->id;
           $input['user_id'] = $user_id;
          
            $customerFarmerReviews = $this->customerFarmerReviewsRepository->create($input);
           
           /* if (array_key_exists("file", $input)) {

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
                         ->withCustomProperties(['uuid' => $upload_data['uuid'], 'user_id' => $input['user_id']])
                         ->toMediaCollection($upload_data['field']);
                
                $cacheUpload = $this->uploadRepository->getByUuid($upload_data['uuid']);
                $mediaItem = $cacheUpload->getMedia('file')->first();
                $mediaItem->copy($driverReview, 'file');         
            
            }*/
            
        } catch (ValidatorException $e) {
            return $this->sendError('Customer Farmer Reviews not found');
        }

        return $this->sendResponse($customerFarmerReviews->toArray(),__('lang.saved_successfully',['operator' => __('lang.customer_farmer_reviews')]));
    }
    
     //get particular customer farmer reviews given by driver
    public function getCustomerFarmerReviewsById(Request $request)
    {
       
        try{
            $user_id = $request->user_id;
            $customerFarmerReviews = $this->customerFarmerReviewsRepository->where('user_id',$user_id)->get();
            foreach($customerFarmerReviews as $key=>$val)
            {
                 $user = $this->userRepository->where('id',$val->driver_id)->first();
                 
                  $customerFarmerReviews[$key]->driver_name  = $user->name;
                  $customerFarmerReviews[$key]->driver_email  = $user->email;
                  if($val) {
                          $customerFarmerReviews[$key]->profile_image  = $user->getFirstMedia('avatar');
                        } else {
                           $customerFarmerReviews[$key]->profile_image = ''; 
                        }
                
            }
           
        } catch (RepositoryException $e) {
            return $this->sendError($e->getMessage());
        }
       
        return $this->sendResponse($customerFarmerReviews->toArray(), 'Customer Farmer Review retrieved successfully');
    }
   
}
