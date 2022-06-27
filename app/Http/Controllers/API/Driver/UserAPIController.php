<?php
/**
 * File name: UserAPIController.php
 * Last modified: 2020.10.29 at 17:03:54
 * Author: SmarterVision - https://codecanyon.net/user/smartervision
 * Copyright (c) 2020
 *
 */

namespace App\Http\Controllers\API\Driver;

use App\Events\UserRoleChangedEvent;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Repositories\CustomFieldRepository;
use App\Repositories\RoleRepository;
use App\Repositories\UploadRepository;
use App\Repositories\DriverRepository;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Prettus\Validator\Exceptions\ValidatorException;
use DB;

class UserAPIController extends Controller
{
    private $userRepository;
    private $uploadRepository;
    private $roleRepository;
    private $customFieldRepository;
    private $driverRepository;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(UserRepository $userRepository, UploadRepository $uploadRepository, RoleRepository $roleRepository, CustomFieldRepository $customFieldRepo, DriverRepository $driverRepo)
    {
        $this->userRepository = $userRepository;
        $this->uploadRepository = $uploadRepository;
        $this->roleRepository = $roleRepository;
        $this->customFieldRepository = $customFieldRepo;
        $this->driverRepository = $driverRepo;
    }

   /* function login(Request $request)
    {
        try {
            $this->validate($request, [
                'email' => 'required|email',
                'password' => 'required',
            ]);
            if (auth()->attempt(['email' => $request->input('email'), 'password' => $request->input('password')])) {
                // Authentication passed...
                $user = auth()->user();
                if (!$user->hasRole('driver')) {
                    $this->sendError('User not driver', 401);
                }
                $user->device_token = $request->input('device_token', '');
                $user->save();
                return $this->sendResponse($user, 'User retrieved successfully');
            }
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), 401);
        }

    }*/
    
     function login(Request $request)
    {
        try {
            $this->validate($request, [
                //'email' => 'required|email',
                'password' => 'required',
            ]);
            
            if($request->input('mobile')!='' && $request->input('email')!='')
            {
                $check_drivers = $this->driverRepository->where('mobile',$request->input('mobile'))->count();
                $check_user = $this->userRepository->where('email',$request->input('email'))->count();
                
                 if($check_drivers>0 && $check_user>0){
                
                $get_drivers = $this->driverRepository->where('mobile',$request->input('mobile'))->first();
                $user_id = $get_drivers->user_id;
                
                $get_user = $this->userRepository->where('email',$request->input('email'))->first();
                $id = $get_user->id;
                
                if($user_id == $id)
                {
                  $email = $request->input('email');
                }else
                {
                     return $this->sendError('User not found', 401);
                }
               }else
                {
                     return $this->sendError('User not found', 401);
                }
            }
            else if($request->input('mobile')!='')
            {
               
                $check_drivers = $this->driverRepository->where('mobile',$request->input('mobile'))->count();
                
                if($check_drivers>0){
                
                $get_drivers = $this->driverRepository->where('mobile',$request->input('mobile'))->first();
                $user_id = $get_drivers->user_id;
                
                $get_user = $this->userRepository->where('id',$user_id)->first();
                $email = $get_user->email;

                }else
                {
                     return $this->sendError('User not found', 401);
                }
            }
            else if($request->input('email')!='')
            {
                $check_user = $this->userRepository->where('email',$request->input('email'))->count();
                 if($check_user>0){
                     
                     $email = $request->input('email');
                     
                }else
                {
                     return $this->sendError('User not found', 401);
                }
            }
            else
            {
                 return $this->sendError('The given data was invalid.', 401);
            }
            
            if (auth()->attempt(['email' => $email, 'password' => $request->input('password')])) {
                // Authentication passed...
                $user = auth()->user();
                if (!$user->hasRole('driver')) {
                    $this->sendError('User not driver', 401);
                }
                $user->device_token = $request->input('device_token', '');
                $user->save();
                return $this->sendResponse($user, 'User retrieved successfully');
            }
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), 401);
        }

    }
    
      /*google/facebook login*/
    function socialLogin(Request $request)
    {
        try {
            $this->validate($request, [
                'email' => 'required|email',
                'name' => 'required',
                'id' => 'required',
            ]);
            
       // check if they're an existing user
        $existingUser = $this->userRepository->where('email', $request->input('email'))->count();
        
         if($existingUser>0){
           // log them in  
           $user =  $this->userRepository->where('email',$request->input('email'))->get();
           return $this->sendResponse($user, 'User retrieved successfully');
        } else {
            // create a new user
            
            $user = new User;
            $user->name = $request->input('name');
            $user->email = $request->input('email');
            $user->device_token = $request->input('device_token', '');
            $user->api_token = str_random(60);
            $user->affiliate_id = str_random(6);
            $user->social_login_id = $request->input('id');
            $user->save();
            
            $user->assignRole('driver');
            event(new UserRoleChangedEvent($user));
            
                 if($user->id > 0) {
                if($request->input('email')!=null && $request->input('email')!='') {

                      if($request->mobile!=''){
                       $update_drivers =  DB::table('drivers')->where('user_id', $user->id)->update(['mobile' => $request->mobile]);
                       }

                }
            }
           
        }
       
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), 401);
        }
        
          return $this->sendResponse($user, 'User retrieved successfully');
          
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
                'name' => 'required',
                'email' => 'required|unique:users|email',
                'password' => 'required',
            ]);
            $user = new User;
            $user->name = $request->input('name');
            $user->email = $request->input('email');
            $user->device_token = $request->input('device_token', '');
            $user->password = Hash::make($request->input('password'));
            $user->api_token = str_random(60);
            $user->save();
            $user->assignRole('driver');

            event(new UserRoleChangedEvent($user));
          
          $update_drivers =  DB::table('drivers')->where('user_id', $user->id)->update(['address_line_1' => $request->address_line_1,'address_line_2' => $request->address_line_2,'city' => $request->city,'state' => $request->state,'pincode' => $request->pincode,'gender' => $request->gender,'date_of_birth' => date('Y-m-d',strtotime($request->input('date_of_birth'))),'mobile' => $request->mobile,'manual_address' => $request->manual_address,'current_location_address' => $request->current_location_address]);
          $user['address_line_1'] = $request->address_line_1;
          $user['address_line_2'] = $request->address_line_2;
          $user['city'] = $request->city;
          $user['state'] = $request->state;
          $user['pincode'] = $request->pincode;
          $user['gender'] = $request->gender;
          $user['date_of_birth'] = date('Y-m-d',strtotime($request->input('date_of_birth')));
          $user['mobile'] = $request->mobile;
          $user['manual_address'] = $request->manual_address;
          $user['current_location_address'] = $request->current_location_address;
            
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

    function user(Request $request)
    {
        $user = $this->userRepository->findByField('api_token', $request->input('api_token'))->first();

        if (!$user) {
            return $this->sendError('User not found', 401);
        }

        return $this->sendResponse($user, 'User retrieved successfully');
    }

    function settings(Request $request)
    {
        $settings = setting()->all();
        $settings = array_intersect_key($settings,
            [
                'default_tax' => '',
                'default_currency' => '',
                'default_currency_decimal_digits' => '',
                'app_name' => '',
                'currency_right' => '',
                'enable_paypal' => '',
                'enable_stripe' => '',
                'enable_razorpay' => '',
                'main_color' => '',
                'main_dark_color' => '',
                'second_color' => '',
                'second_dark_color' => '',
                'accent_color' => '',
                'accent_dark_color' => '',
                'scaffold_dark_color' => '',
                'scaffold_color' => '',
                'google_maps_key' => '',
                'fcm_key' => '',
                'mobile_language' => '',
                'app_version' => '',
                'enable_version' => '',
                'distance_unit' => '',
            ]
        );

        if (!$settings) {
            return $this->sendError('Settings not found', 401);
        }

        return $this->sendResponse($settings, 'Settings retrieved successfully');
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

    function sendResetLinkEmail(Request $request)
    {
        $this->validate($request, ['email' => 'required|email']);

        $response = Password::broker()->sendResetLink(
            $request->only('email')
        );

        if ($response == Password::RESET_LINK_SENT) {
            return $this->sendResponse(true, 'Reset link was sent successfully');
        } else {
            return $this->sendError([
                'error' => 'Reset link not sent',
                'code' => 401,
            ], 'Reset link not sent');
        }

    }
}
