<?php

namespace App\Http\Controllers\API;


use App\Models\Complaints;
use App\Repositories\ComplaintsRepository;
use App\Repositories\UserRepository;
use App\Repositories\MarketRepository;
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

/**
 * Class ComplaintsController
 * @package App\Http\Controllers\API
 */

class ComplaintsAPIController extends Controller
{
    /** @var  ComplaintsRepository */
    private $complaintsRepository;
    
     /** @var  UserRepository */
    private $userRepository;
    
     /** @var  MarketRepository */
    private $marketRepository;
    
     /** @var UploadRepository */
    private $uploadRepository;

    public function __construct(ComplaintsRepository $complaintsRepo, UserRepository $userRepository, MarketRepository $marketRepository, UploadRepository $uploadRepo)
    {
        $this->complaintsRepository = $complaintsRepo;
        $this->userRepository       = $userRepository;
        $this->marketRepository     = $marketRepository;
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
            $this->complaintsRepository->pushCriteria(new RequestCriteria($request));
            $this->complaintsRepository->pushCriteria(new LimitOffsetCriteria($request));
        } catch (RepositoryException $e) {
            return $this->sendError($e->getMessage());
        }
        $complaints = $this->complaintsRepository->all();

        return $this->sendResponse($complaints->toArray(), 'Complaints retrieved successfully');
    }

   

    /**
     * Store a newly created Complaints in storage.
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        
        $input = $request->all();
        try {
           
            if($request->input('user_id')!='' && $request->input('complaints')!='')
            {
             
            $get_user = $this->userRepository->where('id',$request->input('user_id'))->first();
         
            $get_markets = $this->marketRepository->join("user_markets", "markets.id", "=", "user_markets.market_id")->where('user_id',$request->input('user_id'))->first();

            $input['user_id'] = $request->input('user_id');
            $input['order_id'] = $request->input('order_id');
            $input['name'] = $get_user->name;
            $input['email'] = $get_user->email;
            $input['phone'] = $get_markets->phone;
            
            
            $input['complaints'] = $request->input('complaints');
           
            $complaints = $this->complaintsRepository->create($input);
            
            
             if (array_key_exists("complaint_image", $input)) {
            if($complaints->hasMedia('complaint_image')){
                    $complaints->getFirstMedia('complaint_image')->delete();
                }
             
             $uuid = Str::uuid()->toString();
                $upload_data = array(
                    'field' => 'complaint_image',
                    'uuid'  => $uuid,
                    'file'  => $input['complaint_image']
                ); 
                $upload      = $this->uploadRepository->create($upload_data);
                $upload->addMedia($upload_data['file'])
                         ->withCustomProperties(['uuid' => $upload_data['uuid'], 'user_id' => $request->input('user_id')])
                         ->toMediaCollection($upload_data['field']);
                
                $cacheUpload = $this->uploadRepository->getByUuid($upload_data['uuid']);
                $mediaItem = $cacheUpload->getMedia('complaint_image')->first();
                $mediaItem->copy($complaints, 'complaint_image');     
                $complaints['upload_status'] = 'uploaded';
             }
            
            }
            else
            {
               return $this->sendError('The given data was invalid.', 401); 
            }
        } catch (ValidatorException $e) {
            return $this->sendError('Complaints not found');
        }

        return $this->sendResponse($complaints->toArray(),__('lang.saved_successfully',['operator' => __('lang.complaint')]));
    }
}
