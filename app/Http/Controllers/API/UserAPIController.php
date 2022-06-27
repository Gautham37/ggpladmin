<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Repositories\RoleRepository;
use App\Repositories\UploadRepository;
use Flash;
use App\Repositories\UserRepository;
use App\Repositories\MarketRepository;
use App\Repositories\LoyalityPointsRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Prettus\Validator\Exceptions\ValidatorException;
use Cookie;
use CustomHelper;
use DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\sendOtpEmail;
use App\Repositories\DeliveryAddressRepository;
use App\Repositories\VendorStockRepository;
use App\Repositories\PurchaseInvoiceRepository;
use App\Repositories\TransactionRepository;
use App\Repositories\RequestCallbackRepository;


class UserAPIController extends Controller
{
    
    private $userRepository;
    private $uploadRepository;
    private $roleRepository;
    private $loyalityPointsRepository;
    private $marketRepository;
    private $deliveryAddressRepository;
    private $vendorStockRepository;
    private $purchaseInvoiceRepository;
    private $transactionRepository;
    private $requestCallbackRepository;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(UserRepository $userRepository, UploadRepository $uploadRepository, RoleRepository $roleRepository, LoyalityPointsRepository $loyalityPointsRepository, MarketRepository $marketRepository, DeliveryAddressRepository $deliveryAddressRepo, VendorStockRepository $vendorStockRepo, PurchaseInvoiceRepository $purchaseInvoiceRepo, TransactionRepository $transactionRepo, RequestCallbackRepository $requestCallbackRepo)
    {
        $this->userRepository            = $userRepository;
        $this->uploadRepository          = $uploadRepository;
        $this->roleRepository            = $roleRepository;
        $this->loyalityPointsRepository  = $loyalityPointsRepository;
        $this->marketRepository          = $marketRepository;
        $this->deliveryAddressRepository = $deliveryAddressRepo;
        $this->vendorStockRepository     = $vendorStockRepo;
        $this->purchaseInvoiceRepository = $purchaseInvoiceRepo;
        $this->transactionRepository     = $transactionRepo;
        $this->requestCallbackRepository = $requestCallbackRepo;
    }


    function settings(Request $request)
    {
        $settings = setting()->all();
        $upload   = $this->uploadRepository->findByField('uuid', setting('app_logo', ''))->first();
        $appLogo  = asset('images/logo_default.png');
        if ($upload && $upload->hasMedia('app_logo')) {
            array_push($settings,array('app_logo'=>$upload->getFirstMediaUrl('app_logo')));
        }
        if (!$settings) {
            return $this->sendError('Settings not found', 401);
        }
        return $this->sendResponse($settings, 'Settings retrieved successfully');
    }

    
    function login(Request $request)
    {
        try {

            $this->validate($request, [
                'email'     =>  'required_if:mobile,=,null',
                'mobile'    =>  'required_if:email,=,null',
                'password'  =>  'required',
            ]);

            $email = $request->input('email');
            
            if($request->input('mobile') && $request->input('mobile') != '') {
                $market = $this->marketRepository->where('mobile',$request->input('mobile'))->first();
                if($market) {
                    $email = $market->email;
                } else {
                    return $this->sendError('User not found', 401);
                }
            }
             
            if (auth()->attempt( ['email' => $email, 'password' => $request->input('password')] ) ) {
                
                // Authentication passed...
                $user                 = $this->userRepository->with('roles')->findWithoutFail(auth()->user()->id);
                $user->referral_link  = env('APP_SITE_URL').'invite/'.$user->affiliate_id;
                $user                 = $user->toArray() + $user->market->toArray();

                if (!$user) {
                    return $this->sendError('User not found', 401);
                }
                return $this->sendResponse($user, 'User retrieved successfully');

            } else {
                return $this->sendError('Invalid Credential', 401);
            }
           
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), 401);
        }
    }

   
    /*Google / Facebook Login*/
    function socialLogin(Request $request)
    {
        try {
            
            $this->validate($request, [
                'email'            => 'required|email',
                'name'             => 'required',
                'social_login_id'  => 'required',
                'type'             => 'required',
                'sub_type'         => 'required'
            ]);

            // Check if they're an existing user
            $existingUser = $this->userRepository->where('email', $request->input('email'))->count();
            if($existingUser > 0){
                
                // Log them in  
                $user                 = $this->userRepository->with('roles')->where('email', $request->input('email'))->first();
                $user->referral_link  = env('APP_SITE_URL').'invite/'.$user->affiliate_id;
                $user                 = $user->toArray() + $user->market->toArray();
                return $this->sendResponse($user, 'User retrieved successfully');

            } else {
                
                // Create a new user
                $input                      = $request->all();
                $input['code']              = CustomHelper::unique_party_code_generate(
                                                    $this->partyCodeGen($input['type'],$input['sub_type']),
                                                    'GGPL-'.$this->partyPrefixGen($input['type'],$input['sub_type']).'-'
                                              );
                $input['created_via']       = 'mobile_app';
                $input['code_count']        = $this->partyCodeGen($input['type'],$input['sub_type']);
                $input['password']          = Hash::make('123456');
                $input['api_token']         = Str::random(60);
                $input['referred_by']       = null;  
                $input['affiliate_id']      = Str::random(6);
                $input['social_login_id']   = $input['social_login_id'];

                $user = $this->userRepository->create($input);
                if($input['type'] == 4) {
                   $sub_type = $this->partySubTypesRepository->findWithoutFail($input['sub_type']);
                   $user->syncRoles($sub_type->role->name); 
                } else {
                    $user->syncRoles('customer');
                }

                if($user->id > 0) {
                    $input['user_id'] = $user->id;
                    $market           = $this->marketRepository->create($input);

                    $market->activity()->create([
                        'market_id'  => $market->id,
                        'action'     => 'Register',
                        'notes'      => $market->name.' '.$market->code.' Registered Via Mobile APP',
                        'status'     => 'completed'
                    ]);

                }
                return $this->sendResponse($user, 'User retrieved successfully');   
            }
       
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), 401);
        }

    }


    /**
     * Create a new user instance after a valid registration.
     *
     * @param array $data
     * @return
     */
    function register(Request $request)
    {
        try {
            
            $this->validate($request, [
                'name'          => 'required',
                'email'         => 'required|unique:users|email',
                'password'      => 'required',
                'type'          => 'required|min:1',
                'sub_type'      => 'required|min:1',
            ]);

            $input                      = $request->all();
            $input['code']              = CustomHelper::unique_party_code_generate(
                                                $this->partyCodeGen($input['type'],$input['sub_type']),
                                                'GGPL-'.$this->partyPrefixGen($input['type'],$input['sub_type']).'-'
                                          );
            $input['created_via']       = 'mobile_app';
            $input['code_count']        = $this->partyCodeGen($input['type'],$input['sub_type']);
            $input['password']          = Hash::make($input['password']);
            $input['api_token']         = Str::random(60);
            $input['referred_by']       = null;  
            $input['affiliate_id']      = Str::random(6);

            $user = $this->userRepository->create($input);
            if($input['type'] == 4) {
               $sub_type = $this->partySubTypesRepository->findWithoutFail($input['sub_type']);
               $user->syncRoles($sub_type->role->name); 
            } else {
                $user->syncRoles('customer');
            }

            if($user->id > 0) {
                $input['user_id'] = $user->id;
                $market           = $this->marketRepository->create($input);

                $market->activity()->create([
                    'market_id'  => $market->id,
                    'action'     => 'Register',
                    'notes'      => $market->name.' '.$market->code.' Registered Via Mobile APP',
                    'status'     => 'completed'
                ]);
            }

            if (copy(public_path('images/avatar_default.png'), public_path('images/avatar_default_temp.png'))) {
                $user->addMedia(public_path('images/avatar_default_temp.png'))
                    ->withCustomProperties(['uuid' => bcrypt(Str::random())])
                    ->toMediaCollection('avatar');
            }

        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), 401);
        }

        return $this->sendResponse($user, 'User retrieved successfully');
    }


    function logout(Request $request)
    {
        $user = $this->userRepository->findByField('api_token', $request->input('api_token'))->first();
        if (!$user) {
            return $this->sendError('User not found', 401);
        }
        try {
            auth()->logout();
        } catch (\Exception $e) {
            $this->sendError($e->getMessage(), 401);
        }
        return $this->sendResponse($user['name'], 'User logout successfully');

    }

    function sendResetLinkEmail(Request $request)
    {   

        try {
            
            $this->validate($request, [
                'email' => 'required|unique:users|email'
            ]);

            $response = Password::broker()->sendResetLink(
                $request->only('email')
            );

            if ($response == Password::RESET_LINK_SENT) {
                return $this->sendResponse(true, 'Reset link was sent successfully');
            } else {
                return $this->sendError('Reset link not sent', 401);
            }

        } catch (\Exception $e) {
            $this->sendError($e->getMessage(), 401);
        }

    }

    function user(Request $request)
    {
        $user                 = $this->userRepository->findByField('api_token', $request->input('api_token'))->first();
        $user->referral_link  = env('APP_SITE_URL').'invite/'.$user->affiliate_id;
        $user                 = $user->toArray() + $user->market->toArray();                      
        if (!$user) {
            return $this->sendError('User not found', 401);
        }
        return $this->sendResponse($user, 'User retrieved successfully');
    }


    public function userProfilePicture($id, Request $request) {
        
        $user  = $this->userRepository->findWithoutFail($id);
        if (empty($user)) {
            return $this->sendResponse([
                'error' => true,
                'code' => 404,
            ], 'User not found');
        }
        $input = $request->except(['password', 'api_token']);
        try {
            if ($request->has('device_token')) {
                $user = $this->userRepository->update($request->only('device_token'), $id);
            } else {
               
                if($user->hasMedia('avatar')){
                    $user->getFirstMedia('avatar')->delete();
                }
                
                $uuid = Str::uuid()->toString();
                $upload_data = array(
                    'field' => 'avatar',
                    'uuid'  => $uuid,
                    'file'  => $input['avatar']
                ); 
                $upload      = $this->uploadRepository->create($upload_data);
                $upload->addMedia($upload_data['file'])
                         ->withCustomProperties(['uuid' => $upload_data['uuid'], 'user_id' => $user->id])
                         ->toMediaCollection($upload_data['field']);
                
                $cacheUpload = $this->uploadRepository->getByUuid($upload_data['uuid']);
                $mediaItem = $cacheUpload->getMedia('avatar')->first();
                $mediaItem->copy($user, 'avatar');         
                
                /*if (isset($input['avatar']) && $input['avatar']) {
                    $cacheUpload = $this->uploadRepository->getByUuid($input['avatar']);
                    $mediaItem = $cacheUpload->getMedia('avatar')->first();
                    $mediaItem->copy($user, 'avatar');
                }*/
            }
        } catch (ValidatorException $e) {
            return $this->sendError($e->getMessage(), 401);
        }

        return $this->sendResponse($user, __('lang.updated_successfully', ['operator' => __('lang.user')]));
    }




    
     /*emailOtp login*/
    function emailOtpGeneration(Request $request)
    {
        try {
            $this->validate($request, [
                'email' => 'required|email',
            ]);
            
                $email =  $request->input('email');
                $otp = rand(100000, 999999);
                
                  $details = ['title' => 'OTP sent successfully','body' => 'OTP sent successfully','otp_number'=>$otp];
               
                 \Mail::to($email)->send(new sendOtpEmail($details));
                 
                 if (Mail::failures()) {
                        return $this->sendError('mail not send', 401);
                  }

               
                 return response(["otp" => $otp, "message" => "OTP sent successfully"]);


        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), 401);
        }

    }

    

    /**
     * Update the specified User in storage.
     *
     * @param int $id
     * @param Request $request
     *
     */
    public function update($id, Request $request)
    {
        $user = $this->userRepository->findWithoutFail($id);

        if (empty($user)) {
            return $this->sendResponse([
                'error' => true,
                'code' => 404,
            ], 'User not found');
        }
        $input = $request->except(['password', 'api_token']);
        try {
            if ($request->has('device_token')) {
                $user = $this->userRepository->update($request->only('device_token'), $id);
            } else {
                $customFields = $this->customFieldRepository->findByField('custom_field_model', $this->userRepository->model());
                $user = $this->userRepository->update($input, $id);
                
                $user_market = DB::table('user_markets')->where('user_id',$id)->first();
                if($user_market) {
                   /*$market_data = array(
                        'name'              => $input['name'],
                        'email'             => $input['email'],
                        'gender'            => $input['gender'],
                        'date_of_birth'     => $input['date_of_birth'],
                        'address_line_1'    => $input['address_line_1'],
                        'address_line_2'    => $input['address_line_2'],
                        'city'              => $input['city'],
                        'state'             => $input['state'],
                        'pincode'           => $input['pincode'],
                        'mobile'            => $input['mobile']
                   );*/ 
                   $update_market = $this->marketRepository->update($input,$user_market->market_id);
                   if($update_market) {
                       $user->market = $update_market;
                   }
                }

                foreach (getCustomFieldsValues($customFields, $request) as $value) {
                    $user->customFieldsValues()
                        ->updateOrCreate(['custom_field_id' => $value['custom_field_id']], $value);
                }
            }
        } catch (ValidatorException $e) {
            return $this->sendError($e->getMessage(), 401);
        }

        return $this->sendResponse($user, __('lang.updated_successfully', ['operator' => __('lang.user')]));
    }

    
    
   /* public function userRewards(Request $request) {
        $user = $this->userRepository->findByField('api_token', $request->input('api_token'))->first();
        
        $user_markets = DB::table('user_markets')
                            ->join('markets','user_markets.market_id','=','markets.id')
                            ->where('user_markets.user_id',$user->id)
                            ->first();
        $affiliate_id = $user->affiliate_id;

        $t_datas  = DB::table('loyality_points_tracker')
                        ->where('affiliate_id',$affiliate_id)
                        ->select(
                            'user_id',
                            'point_type',
                            'points',
                            'purchase_id as sales_code',
                            'created_at'
                        );
        $datas = DB::table('loyality_point_usage')
                        ->where('user_id',$user->user_id)
                        ->select(
                            'user_id',
                            'point_type',
                            'usage_points as points',
                            'order_id as sales_code',
                            'created_at'
                        )
                        ->union($t_datas)
                        ->orderBy('created_at','asc')
                        ->get();
        
        //dd($datas);

        for($i=0; $i<count($datas); $i++) {

            $datas[$i]->reward_date = date('d M Y',strtotime($datas[$i]->created_at));
            
            if($datas[$i]->point_type=='referral') {
                $user                   = $this->userRepository->findWithoutFail($datas[$i]->user_id)->name;
                $datas[$i]->point_type  = ucfirst($datas[$i]->point_type).' Points for '.$user;
                $datas[$i]->purchase_no = '';
                $datas[$i]->purchase_amount = '';
                
                $datas[$i]->point_type  = 'Earning';

            } elseif($datas[$i]->point_type=='purchase' || $datas[$i]->point_type=='usage') {
                $datas[$i]->point_type      = ucfirst($datas[$i]->point_type);
                $sales_invoice              = DB::table('sales_invoice')->where('id',$datas[$i]->sales_code)->first(); 
                $datas[$i]->purchase_no     = $sales_invoice->sales_code;
                $datas[$i]->purchase_amount = setting('default_currency').' '.$sales_invoice->sales_total_amount;
                $datas[$i]->reward_date     = date('d M Y',strtotime($sales_invoice->created_at));
                
                if($datas[$i]->point_type=='purchase') {
                    $datas[$i]->point_type  = 'Earning';
                } elseif($datas[$i]->point_type=='usage') {
                    $datas[$i]->point_type  = 'Used';
                }
                
            }
            
        }    
        
        return $this->sendResponse($datas, __('lang.updated_successfully', ['operator' => __('lang.rewards')]));
                            
    }*/
    
    public function userRewards(Request $request) {
        
        $datas = $this->loyalityPointsRepository->where('affiliate_id',Auth()->user()->affiliate_id)->get();
        return $this->sendResponse($datas, 'Reward details retrieved successfully');
                            
    }  
    
    
    /*8.12.2021*/
    
    //get total reward points
    public function totalRewardPoints(Request $request) {
      $user = $this->userRepository->findByField('api_token', $request->input('api_token'))->first();
        if (!$user) {
            return $this->sendError('User not found', 401);
        }
        try {
            $users->points   = $user->points;
        } catch (\Exception $e) {
            $this->sendError($e->getMessage(), 401);
        }
        return $this->sendResponse($users, 'User reward points successfully');
    }
    
    //get remaining reward points
    public function remainingRewardPoints(Request $request) {
      
        try {
            $this->validate($request, [
                'redeem_points' => 'required',
                'api_token' => 'required'
            ]);
            
            $redeem_points   = $request->input('redeem_points');
            
           $user = $this->userRepository->findByField('api_token', $request->input('api_token'))->first();
           if (!$user) {
            return $this->sendError('User not found', 401);
           }
            $total_points   = $user->points;
            $id   = $user->id;
            $remaining_points = $total_points - $redeem_points;
            
           $update = $this->userRepository->where('id', $id)->update(['points' => $remaining_points]);
           $user_data['points']           = $remaining_points;
            
        } catch (\Exception $e) {
            $this->sendError($e->getMessage(), 401);
        }
        return $this->sendResponse($user_data, 'User reward points updated successfully');
    }
    
    //update user delivery address
     public function updateUserDeliveryAddress(Request $request) {
      
        try {
            $this->validate($request, [
                'api_token' => 'required',
                'address_line_1' => 'required',
                'latitude' => 'required',
                'longitude' => 'required',
            ]);
            
            
           $user = $this->userRepository->findByField('api_token', $request->input('api_token'))->first();
           if (!$user) {
            return $this->sendError('User not found', 401);
           }
            $id   = $user->id;
            
            $get_delivery_address = $this->deliveryAddressRepository->where('user_id', $id)->first();
            
            if($request->input('address_line_2')!=''){ 
                $address_line_2 = $request->input('address_line_2');
            }else
            {
                $address_line_2 = $get_delivery_address->address_line_2;
            }
            
            if($request->input('city')!=''){ 
                $city = $request->input('city');
            }else
            {
                $city = $get_delivery_address->city;
            }
            
            if($request->input('pincode')!=''){ 
                $pincode = $request->input('pincode');
            }else
            {
                $pincode = $get_delivery_address->pincode;
            }
            
            if($request->input('state')!=''){ 
                $state = $request->input('state');
            }else
            {
                $state = $get_delivery_address->state;
            }
          
           $update = $this->deliveryAddressRepository->where('user_id', $id)->update(['address_line_1' => $request->input('address_line_1'),'address_line_2' => $address_line_2,'city' => $city,'pincode' => $pincode,'state' => $state,'latitude' => $request->input('latitude'),'longitude' => $request->input('longitude')]);
           $updated_delivery_address = $this->deliveryAddressRepository->where('user_id', $id)->get();
           
             return $this->sendResponse($updated_delivery_address, 'User delivery address updated successfully');
             
        } catch (\Exception $e) {
            $this->sendError($e->getMessage(), 401);
        }
        
        return $this->sendError('The given data was invalid.', 401);
       
    }  
    
   
    /**
     * Get Farmer Dashboard Datas.
     *
     * @param array $data
     * @return
     */
    function farmerDashboard(Request $request)
    {   
        try {

            $datas = [];
            $datas['vendor_supplies']    = $this->vendorStockRepository->where('market_id',auth()->user()->market->id)->count();
            $datas['purchase_invoices']  = $this->purchaseInvoiceRepository->where('market_id',auth()->user()->market->id)->count();
            $datas['collectable_amount'] = $this->transactionRepository
                                               ->where('market_id',auth()->user()->market->id)
                                               ->where('category','purchase')
                                               ->where('type','credit')
                                               ->sum('amount');
            $datas['received_amount']    = $this->transactionRepository
                                               ->where('market_id',auth()->user()->market->id)
                                               ->where('category','purchase')
                                               ->where('type','debit')
                                               ->sum('amount');

            $datas['callback_request']   = $this->requestCallbackRepository
                                                ->where('user_id',auth()->user()->id)
                                                ->count();

            return $this->sendResponse($datas, 'Farmer Dashboard Retrived successfully');
             
        } catch (\Exception $e) {
            $this->sendError($e->getMessage(), 401);
        }
    }
    
}
