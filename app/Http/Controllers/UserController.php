<?php
/**
 * File name: UserController.php
 * Last modified: 2020.05.04 at 12:15:13
 * Author: SmarterVision - https://codecanyon.net/user/smartervision
 * Copyright (c) 2020
 *
 */

namespace App\Http\Controllers;

use App\DataTables\UserDataTable;
use App\DataTables\StaffsDataTable;
use App\Events\UserRoleChangedEvent;
use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Repositories\CustomFieldRepository;
use App\Repositories\RoleRepository;
use App\Repositories\UploadRepository;
use App\Repositories\UserRepository;
use App\Repositories\MarketRepository;
use App\Repositories\StaffDepartmentRepository;
use App\Repositories\StaffDesignationRepository;
use App\Repositories\StaffsRepository;
use App\Repositories\CustomerGroupsRepository;
use Flash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Prettus\Validator\Exceptions\ValidatorException;
use App\Mail\UserRegisterMail;
use DB;
use CustomHelper;

class UserController extends Controller
{
    /** @var  UserRepository */
    private $userRepository;
    
    /** @var  StaffDepartmentRepository */
    private $staffDepartmentRepository;
    
     /** @var  StaffDesignationRepository */
    private $staffDesignationRepository;
    
     /** @var  StaffsRepository */
    private $staffsRepository;
    
     /** @var  MarketRepository */
    private $marketRepository;
    
    /**
     * @var RoleRepository
     */
    private $roleRepository;

    private $CustomerGroupsRepository;

    private $uploadRepository;

    /**
     * @var CustomFieldRepository
     */
    private $customFieldRepository;

    public function __construct(UserRepository $userRepo, RoleRepository $roleRepo, UploadRepository $uploadRepo,
                                CustomFieldRepository $customFieldRepo, CustomerGroupsRepository $CustomerGroupsRepo, MarketRepository $marketRepo, StaffDepartmentRepository $staffDepartmentRepo, StaffDesignationRepository $staffDesignationRepo, StaffsRepository $staffsRepo)
    {
        parent::__construct();
        $this->userRepository = $userRepo;
        $this->marketRepository = $marketRepo;
        $this->roleRepository = $roleRepo;
        $this->CustomerGroupsRepository = $CustomerGroupsRepo;
        $this->uploadRepository = $uploadRepo;
        $this->customFieldRepository = $customFieldRepo;
        $this->staffDepartmentRepository = $staffDepartmentRepo;
        $this->staffDesignationRepository = $staffDesignationRepo;
        $this->staffsRepository = $staffsRepo;
    }

    /**
     * Display a listing of the User.
     *
     * @param UserDataTable $userDataTable
     * @return Response
     */
    public function index(UserDataTable $userDataTable)
    {
        return $userDataTable->render('settings.users.index');
    }

    /**
     * Display a user profile.
     *
     * @param
     * @return Response
     */
    public function profile()
    {
        $user = $this->userRepository->findWithoutFail(auth()->id());
        unset($user->password);
        $customFields = false;
        $role = $this->roleRepository->pluck('name', 'name');

        $customer_groups        = $this->CustomerGroupsRepository->pluck('name', 'id');
        $CustomerGroupsSelected = [];

        $rolesSelected = $user->getRoleNames()->toArray();
        $customFieldsValues = $user->customFieldsValues()->with('customField')->get();
        //dd($customFieldsValues);
        $hasCustomField = in_array($this->userRepository->model(), setting('custom_field_models', []));
        if ($hasCustomField) {
            $customFields = $this->customFieldRepository->findByField('custom_field_model', $this->userRepository->model());
            $customFields = generateCustomField($customFields, $customFieldsValues);
        }
        return view('settings.users.profile', compact(['user', 'role', 'rolesSelected', 'customFields', 'customFieldsValues','customer_groups','CustomerGroupsSelected']));
    }

    /**
     * Show the form for creating a new User.
     *
     * @return Response
     */
    public function create()
    {
        //$role = $this->roleRepository->whereNotIn("name", ["admin","manager","driver","staff"])->pluck('name', 'name');
        $role = $this->roleRepository->whereIn("name", ["client"])->pluck('name', 'name');
        //$role->prepend("Please Select the role",0);   
        $customer_groups = $this->CustomerGroupsRepository->pluck('name', 'id');

        $rolesSelected = ['client'];
        $CustomerGroupsSelected = [];
        $hasCustomField = in_array($this->userRepository->model(), setting('custom_field_models', []));
        if ($hasCustomField) {
            $customFields = $this->customFieldRepository->findByField('custom_field_model', $this->userRepository->model());
            $html = generateCustomField($customFields);
        }

        return view('settings.users.create')
            ->with("role", $role)
            ->with("customer_groups", $customer_groups)
            ->with("customFields", isset($html) ? $html : false)
            ->with("rolesSelected", $rolesSelected)
            ->with("CustomerGroupsSelected", $CustomerGroupsSelected);
    }

    /**
     * Store a newly created User in storage.
     *
     * @param CreateUserRequest $request
     *
     * @return Response
     */
    public function store(CreateUserRequest $request)
    {
        if (env('APP_DEMO', false)) {
            Flash::warning('This is only demo app you can\'t change this section ');
            return redirect(route('users.index'));
        }

        $input = $request->all();
        $customFields = $this->customFieldRepository->findByField('custom_field_model', $this->userRepository->model());

        $input['roles'] = isset($input['roles']) ? $input['roles'] : [];
        $input['password'] = Hash::make($input['password']);
        $input['api_token'] = str_random(60);
        $input['affiliate_id'] = str_random(6);
        $input['referred_by']  = null;

        try {
            $user = $this->userRepository->create($input);
            $user->syncRoles($input['roles']);
            $user->customFieldsValues()->createMany(getCustomFieldsValues($customFields, $request));
            
            if($user->id > 0 && $input['roles'][0] == 'client') {
                if($input['email']!=null && $input['email']!='') {
                    
                    $user_data['code']           = CustomHelper::unique_code_generate('markets','GGPLPY');
                    $user_data['name']           = $input['name'];
                    $user_data['address']        = $input['address'];
                    $user_data['email']          = $input['email'];
                    $user_data['phone']          = $input['phone'];
                    $user_data['mobile']         = $input['phone'];
                    $user_data['active']         = 1;
                    $user_data['customer_group'] = $input['customer_group_id'];

                    $market = $this->marketRepository->create($user_data);
                    //$user = $this->userRepository->create($user_data);
                    $user->syncRoles($input['roles'][0]);
                    if($user->id > 0) {
                        $market_user['users'] = array($user->id);
                        $update_user          = $this->marketRepository->update($market_user, $market->id);
                    }
                }
            }    
            
            if (isset($input['avatar']) && $input['avatar']) {
                $cacheUpload = $this->uploadRepository->getByUuid($input['avatar']);
                $mediaItem = $cacheUpload->getMedia('avatar')->first();
                $mediaItem->copy($user, 'avatar');
            }
            event(new UserRoleChangedEvent($user));
            
            //Notification mail sent
             $details = ['title' => 'Your '.setting('app_name').' account has been created!','body' => 'User Registeration has been created.','customer_name' =>$input['name'],'customer_mail' =>$input['email']];
            \Mail::to($input['email'])->send(new UserRegisterMail($details));    
            
        } catch (ValidatorException $e) {
            Flash::error($e->getMessage());
        }

        Flash::success('saved successfully.');

        return redirect(route('users.index'));
    }

    /**
     * Display the specified User.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $user = $this->userRepository->findWithoutFail($id);

        if (empty($user)) {
            Flash::error('User not found');

            return redirect(route('users.index'));
        }

        return view('settings.users.profile')->with('user', $user);
    }

    public function loginAsUser(Request $request, $id)
    {
        $user = $this->userRepository->findWithoutFail($id);
        if (empty($user)) {
            Flash::error('User not found');
            return redirect(route('users.index'));
        }
        auth()->login($user, true);
        if (auth()->id() !== $user->id) {
            Flash::error('User not found');
        }
        return redirect(route('users.profile'));
    }

    /**
     * Show the form for editing the specified User.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        if (!auth()->user()->hasRole('admin') && $id != auth()->id()) {
            Flash::error('Permission denied');
            return redirect(route('users.index'));
        }
        $user = $this->userRepository->findWithoutFail($id);
        unset($user->password);
        $html = false;
        //$role = $this->roleRepository->pluck('name', 'name');
        $role = $this->roleRepository->whereIn("name", ["client"])->pluck('name', 'name');
        $customer_groups = $this->CustomerGroupsRepository->pluck('name', 'id');
        $CustomerGroupsSelected = $user['customer_group_id'];
        $rolesSelected = $user->getRoleNames()->toArray();
        $customFieldsValues = $user->customFieldsValues()->with('customField')->get();
        $customFields = $this->customFieldRepository->findByField('custom_field_model', $this->userRepository->model());
        $hasCustomField = in_array($this->userRepository->model(), setting('custom_field_models', []));
        if ($hasCustomField) {
            $html = generateCustomField($customFields, $customFieldsValues);
        }

        if (empty($user)) {
            Flash::error('User not found');

            return redirect(route('users.index'));
        }
        return view('settings.users.edit')
            ->with('user', $user)->with("role", $role)
            ->with("rolesSelected", $rolesSelected)
            ->with("customer_groups", $customer_groups)
            ->with("CustomerGroupsSelected", $CustomerGroupsSelected)
            ->with("customFields", $html);
    }

    /**
     * Update the specified User in storage.
     *
     * @param int $id
     * @param UpdateUserRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateUserRequest $request)
    {
        if (env('APP_DEMO', false)) {
            Flash::warning('This is only demo app you can\'t change this section ');
            return redirect(route('users.profile'));
        }
        if (!auth()->user()->hasRole('superadmin') && $id != auth()->id()) {
            Flash::error('Permission denied');
            return redirect(route('users.profile'));
        }

        $user = $this->userRepository->findWithoutFail($id);


        if (empty($user)) {
            Flash::error('User not found');
            return redirect(route('users.profile'));
        }
        $customFields = $this->customFieldRepository->findByField('custom_field_model', $this->userRepository->model());

        $input = $request->all();
        if (!auth()->user()->can('permissions.index')) {
            unset($input['roles']);
        } else {
            $input['roles'] = isset($input['roles']) ? $input['roles'] : [];
        }
        if (empty($input['password'])) {
            unset($input['password']);
        } else {
            $input['password'] = Hash::make($input['password']);
        }
        try {
            $user = $this->userRepository->update($input, $id);

            $user_market = DB::table('user_markets')->where('user_id',$id)->first();
            if($user_market) {
               $market_data = array(
                    'name' => $input['name'],
                    'email' => $input['email'],
                    'gender' => $input['gender'],
                    'date_of_birth' => $input['date_of_birth'] 
               ); 
               $update_market = $this->marketRepository->update($market_data,$user_market->market_id);
            }   

            if (empty($user)) {
                Flash::error('User not found');
                return redirect(route('users.profile'));
            }
            if (isset($input['avatar']) && $input['avatar']) {
                $cacheUpload = $this->uploadRepository->getByUuid($input['avatar']);
                $mediaItem = $cacheUpload->getMedia('avatar')->first();
                $mediaItem->copy($user, 'avatar');
            }
            if (auth()->user()->can('permissions.index')) {
                $user->syncRoles($input['roles']);
            }
            foreach (getCustomFieldsValues($customFields, $request) as $value) {
                $user->customFieldsValues()
                    ->updateOrCreate(['custom_field_id' => $value['custom_field_id']], $value);
            }
            event(new UserRoleChangedEvent($user));
        } catch (ValidatorException $e) {
            Flash::error($e->getMessage());
        }


        Flash::success('User updated successfully.');

        return redirect()->back();

    }

    /**
     * Remove the specified User from storage.
     *
     * @param int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        if (env('APP_DEMO', false)) {
            Flash::warning('This is only demo app you can\'t change this section ');
            return redirect(route('users.index'));
        }
        $user        = $this->userRepository->findWithoutFail($id);
        $user_market = DB::table('user_markets')->where('user_id',$id)->first(); 

        if (empty($user)) {
            Flash::error('User not found');

            return redirect(route('users.index'));
        }

        $this->userRepository->delete($id);
        if($user_market) {
            $this->marketRepository->delete($user_market->market_id);
        }

        Flash::success('User deleted successfully.');

        return redirect(route('users.index'));
    }

    /**
     * Remove Media of User
     * @param Request $request
     */
    public function removeMedia(Request $request)
    {
        if (env('APP_DEMO', false)) {
            Flash::warning('This is only demo app you can\'t change this section ');
        } else {
            if (auth()->user()->can('medias.delete')) {
                $input = $request->all();
                $user = $this->userRepository->findWithoutFail($input['id']);
                try {
                    if ($user->hasMedia($input['collection'])) {
                        $user->getFirstMedia($input['collection'])->delete();
                    }
                } catch (\Exception $e) {
                    Log::error($e->getMessage());
                }
            }
        }
    }


    //Staff Details

    public function staffsIndex(StaffsDataTable $staffsDataTable)
    {
        //return $staffsDataTable->render('settings.staffs.index');
        return $staffsDataTable->render('staffs.index');
    }

    public function staffsProfile()
    {
        $user = $this->userRepository->findWithoutFail(auth()->id());
        unset($user->password);
        $customFields = false;
        $role = $this->roleRepository->whereNotIn("name", ["client"])->pluck('name', 'name');
        $rolesSelected = $user->getRoleNames()->toArray();
        $customFieldsValues = $user->customFieldsValues()->with('customField')->get();
        //dd($customFieldsValues);
        $hasCustomField = in_array($this->userRepository->model(), setting('custom_field_models', []));
        if ($hasCustomField) {
            $customFields = $this->customFieldRepository->findByField('custom_field_model', $this->userRepository->model());
            $customFields = generateCustomField($customFields, $customFieldsValues);
        }
        return view('settings.staffs.profile', compact(['user', 'role', 'rolesSelected', 'customFields', 'customFieldsValues']));
    }

    public function staffsCreate()
    {
        $role = $this->roleRepository->whereNotIn("name", ["client","admin"])->pluck('name', 'name');
        //$role->prepend("Please Select the role",0);
        $rolesSelected = ['staff'];
        $department  = $this->staffDepartmentRepository->pluck('name', 'id');
        $department->prepend("Please Select",0);
        $designation  = $this->staffDesignationRepository->pluck('name', 'id');
        $designation->prepend("Please Select",0);
        $hasCustomField = in_array($this->userRepository->model(), setting('custom_field_models', []));
        if ($hasCustomField) {
            $customFields = $this->customFieldRepository->findByField('custom_field_model', $this->userRepository->model());
            $html = generateCustomField($customFields);
        }
        
        $departmentSelected = $designationSelected = array();
        //  return view('settings.staffs.create')
        //     ->with("role", $role)
        //     ->with("customFields", isset($html) ? $html : false)
        //     ->with("rolesSelected", $rolesSelected);
        return view('staffs.create')
            ->with("role", $role)->with("department", $department)->with("designation", $designation)
            ->with("customFields", isset($html) ? $html : false)
            ->with("rolesSelected", $rolesSelected)->with("departmentSelected", $departmentSelected)->with("designationSelected", $designationSelected);
    }
    
     public function StaffsStore(CreateUserRequest $request)
    {
        if (env('APP_DEMO', false)) {
            Flash::warning('This is only demo app you can\'t change this section ');
            return redirect(route('users.index'));
        }

        $input = $request->all();
       
        $customFields = $this->customFieldRepository->findByField('custom_field_model', $this->userRepository->model());
        
        $getRole = $this->staffDesignationRepository->where('id',$request->designation_id)->first();
        $role = $getRole->name;

        $input['roles'] = $role;
        //$input['roles'] = isset($input['roles']) ? $input['roles'] : [];
        $input['password'] = Hash::make($input['password']);
        $input['api_token'] = str_random(60);
        $input['affiliate_id'] = str_random(6);
        $input['referred_by']  = null;
        $input['date_of_birth'] = date("Y-m-d",strtotime($input['date_of_birth']));
        $input['is_staff']  = 1;
     
        try {
            $user = $this->userRepository->create($input);
            $user_id = $user->id;
            
            if($user_id!=''){
               
                         $staff_data   = array(
                            'user_id'        => $user_id,
                            'department_id' => $request->department_id,
                            'designation_id'  => $request->designation_id,
                            'address_line_1'  => $request->address_line_1,
                            'address_line_2'  => $request->address_line_2,
                            'city'  => $request->city,
                            'state'  => $request->state,
                            'pincode'  => $request->pincode,
                            'description'  => $request->description,
                            'active'  => $request->active,
                            'phone'  => $request->phone,
                            'created_by' => auth()->user()->id
                          );
                           if($input['date_of_joining']!='') {
                                $staff_data['date_of_joining'] = date('Y-m-d',strtotime($input['date_of_joining']));
                             }
                            if($input['date_of_relieving']!='') {
                                $staff_data['date_of_relieving'] = date('Y-m-d',strtotime($input['date_of_relieving']));
                            }
             $staff_create = $this->staffsRepository->create($staff_data);
            
            }
            //$user->syncRoles($input['roles']);
            $user->syncRoles($role);
            $user->customFieldsValues()->createMany(getCustomFieldsValues($customFields, $request));
            
            if (isset($input['avatar']) && $input['avatar']) {
               
                $cacheUpload = $this->uploadRepository->getByUuid($input['avatar']);
                $mediaItem = $cacheUpload->getMedia('avatar')->first();
                $mediaItem->copy($user, 'avatar');
            }
            event(new UserRoleChangedEvent($user));
            
            //Notification mail sent
             $details = ['title' => 'Your '.setting('app_name').' account has been created!','body' => 'User Registeration has been created.','customer_name' =>$input['name'],'customer_mail' =>$input['email']];
            \Mail::to($input['email'])->send(new UserRegisterMail($details));    
            
        } catch (ValidatorException $e) {
            Flash::error($e->getMessage());
        }

          Flash::success(__('lang.saved_successfully',['operator' => __('lang.staff')]));

        return redirect(route('staffs.index'));
    }


    public function staffsEdit($id)
    {
        if (!auth()->user()->hasRole('admin') && $id != auth()->id()) {
            Flash::error('Permission denied');
            return redirect(route('users.index'));
        }
        $user = $this->userRepository->findWithoutFail($id);
        unset($user->password);
        $html = false;
        $role = $this->roleRepository->whereNotIn("name", ["client"])->pluck('name', 'name');
        $rolesSelected = $user->getRoleNames()->toArray();
        
        $staff = $this->staffsRepository->where('user_id',$user->id)->first();
        $departmentSelected  = ($staff) ? $staff->department_id : null ;
        $designationSelected = ($staff) ? $staff->designation_id : null ;
        
        $designation = $this->staffDesignationRepository->where('department_id',$staff->department_id)->pluck('name', 'id');
        $designationSelected = $staff->designation_id;

    
        $department  = $this->staffDepartmentRepository->pluck('name', 'id');
        //$designation  = $this->staffDesignationRepository->pluck('name', 'id');
        //$departmentSelected = $this->staffDepartmentRepository->where('id',$market->type)->pluck('name', 'id');
        $customFieldsValues = $user->customFieldsValues()->with('customField')->get();
        $customFields = $this->customFieldRepository->findByField('custom_field_model', $this->userRepository->model());
        $hasCustomField = in_array($this->userRepository->model(), setting('custom_field_models', []));
        if ($hasCustomField) {
            $html = generateCustomField($customFields, $customFieldsValues);
        }

        if (empty($user)) {
            Flash::error('User not found');

            return redirect(route('users.index'));
        }
        
         return view('staffs.edit')
            ->with('user', $user)->with("role", $role)->with("department", $department)->with("designation", $designation)->with("staff", $staff)
            ->with("rolesSelected", $rolesSelected)->with("departmentSelected", $departmentSelected)->with("designationSelected", $designationSelected)
            ->with("customFields", $html);
        // return view('settings.staffs.edit')
        //     ->with('user', $user)->with("role", $role)
        //     ->with("rolesSelected", $rolesSelected)
        //     ->with("customFields", $html);
    }
    
     public function StaffsUpdate($id, Request $request)
    {
       
        if (env('APP_DEMO', false)) {
            Flash::warning('This is only demo app you can\'t change this section ');
            return redirect(route('users.profile'));
        }
        if (!auth()->user()->hasRole('admin') && $id != auth()->id()) {
            Flash::error('Permission denied');
            return redirect(route('users.profile'));
        }

        $user = $this->userRepository->findWithoutFail($id);

        if (empty($user)) {
            Flash::error('User not found');
            return redirect(route('users.profile'));
        }
        
        $getstaff = $this->staffsRepository->where('user_id',$user->id)->first();
        $old_designation_id = $getstaff->designation_id;
        
        $getRole = $this->staffDesignationRepository->where('id',$old_designation_id)->first();
        $role = $getRole->name;
        
        $customFields = $this->customFieldRepository->findByField('custom_field_model', $this->userRepository->model());

        $input = $request->all();
        if (!auth()->user()->can('permissions.index')) {
            //unset($input['roles']);
            unset($role);
        } else {
            //$input['roles'] = isset($input['roles']) ? $input['roles'] : [];
            $input['roles'] = $role;
        }
        if (empty($input['password'])) {
            unset($input['password']);
        } else {
            $input['password'] = Hash::make($input['password']);
        }
        
          $input['date_of_birth'] = date("Y-m-d",strtotime($input['date_of_birth']));
          
          
        try {
            $user = $this->userRepository->update($input, $id);

            if (empty($user)) {
                Flash::error('User not found');
                return redirect(route('users.profile'));
            }
          
            $staff = $this->staffsRepository->where('user_id',$user->id)->first();
            $staff_id = $staff->id;
            
             if($staff_id!=''){
                 
                         $staff_data   = array(
                            'department_id' => $request->department_id,
                            'designation_id'  => $request->designation_id,
                            'address_line_1'  => $request->address_line_1,
                            'address_line_2'  => $request->address_line_2,
                            'city'  => $request->city,
                            'state'  => $request->state,
                            'pincode'  => $request->pincode,
                            'description'  => $request->description,
                            'active'  => $request->active,
                            'phone'  => $request->phone,
                            'updated_by' => auth()->user()->id
                          );
                         if($input['date_of_joining']!='') {
                                $staff_data['date_of_joining'] = date('Y-m-d',strtotime($input['date_of_joining']));
                             }
                        if($input['date_of_relieving']!='') {
                            $staff_data['date_of_relieving'] = date('Y-m-d',strtotime($input['date_of_relieving']));
                        }
                 
                
             $staff_create = $this->staffsRepository->update($staff_data, $staff_id);
            
            }
            
            if (isset($input['avatar']) && $input['avatar']) {
                $cacheUpload = $this->uploadRepository->getByUuid($input['avatar']);
                $mediaItem = $cacheUpload->getMedia('avatar')->first();
                $mediaItem->copy($user, 'avatar');
            }
            if (auth()->user()->can('permissions.index')) {
                //$user->syncRoles($input['roles']);
                $user->syncRoles($role);
            }
            foreach (getCustomFieldsValues($customFields, $request) as $value) {
                $user->customFieldsValues()
                    ->updateOrCreate(['custom_field_id' => $value['custom_field_id']], $value);
            }
            event(new UserRoleChangedEvent($user));
        } catch (ValidatorException $e) {
            Flash::error($e->getMessage());
        }


       Flash::success(__('lang.updated_successfully',['operator' => __('lang.staff')]));

        return redirect(route('staffs.index'));

    }
    
    /**
     * Remove the specified User from storage.
     *
     * @param int $id
     *
     * @return Response
     */
    public function Staffsdestroy($id)
    {
        if (env('APP_DEMO', false)) {
            Flash::warning('This is only demo app you can\'t change this section ');
            return redirect(route('users.index'));
        }
        $user   = $this->userRepository->findWithoutFail($id);
        $staffs = $this->staffsRepository->where('user_id',$id)->first(); 

        if (empty($user)) {
            Flash::error('User not found');

            return redirect(route('staffs.index'));
        }

        $this->userRepository->delete($id);
        if($staffs) {
            $this->staffsRepository->delete($staffs->id);
        }

        Flash::success(__('lang.deleted_successfully',['operator' => __('lang.staff')]));

        return redirect(route('staffs.index'));
    }
    
    public function showStaffDepartments(Request $request){
  
       $depart_id = $request->depart_id;

       $departments = $this->staffDepartmentRepository->where('id',$depart_id)
                              ->with('staffdesignations')
                              ->get();
        return response()->json([
            'departments' => $departments
        ]);
     }


    //Staff Details
    
    public function validateEmail(Request $request) {
        $validator = $request->validate([
            'email'      => 'required|string|email|max:255|unique:users'
        ]);
        return $this->sendResponse($validator, 'Validate successfully');
    }

}
