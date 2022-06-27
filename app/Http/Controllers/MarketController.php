<?php

namespace App\Http\Controllers;

use App\Criteria\Markets\MarketsOfUserCriteria;
use App\Criteria\Users\AdminsCriteria;
use App\Criteria\Users\ClientsCriteria;
use App\Criteria\Users\DriversCriteria;
use App\Criteria\Users\ManagersClientsCriteria;
use App\Criteria\Users\ManagersCriteria;
use App\DataTables\MarketDataTable;
use App\DataTables\RequestedMarketDataTable;
use App\Events\MarketChangedEvent;
use App\Http\Requests\CreateMarketRequest;
use App\Http\Requests\UpdateMarketRequest;
use App\Repositories\CustomFieldRepository;
use App\Repositories\FieldRepository;
use App\Repositories\MarketRepository;
use App\Repositories\PartyTypesRepository;
use App\Repositories\PartySubTypesRepository;
use App\Repositories\PartyStreamRepository;
use App\Repositories\PurchaseInvoiceRepository;
use App\Repositories\SalesInvoiceRepository;
use App\Repositories\PurchaseReturnRepository;
use App\Repositories\SalesReturnRepository;
use App\Repositories\PaymentInRepository;
use App\Repositories\PaymentOutRepository;
use App\Repositories\UploadRepository;
use App\Repositories\UserRepository;
use App\Repositories\CustomerGroupsRepository;
use App\Repositories\CustomerLevelsRepository;
use App\Repositories\MarketNotesRepository;
use App\Repositories\SupplyPointsRepository;
use App\Repositories\DeliveryAddressRepository;
use App\Repositories\DeliveryZonesRepository;
use App\Repositories\StaffDesignationRepository;
use App\Repositories\RoleRepository;
use Flash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Prettus\Validator\Exceptions\ValidatorException;
use App\Models\TransactionTrack;
use DataTables;
use DB;
use CustomHelper;
use App\Mail\PartiesMail;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Str;

use App\Imports\MarketsImport;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;

class MarketController extends Controller
{
    /** @var  MarketRepository */
    private $marketRepository;

    /** @var  PurchaseInvoiceRepository */
    private $purchaseInvoiceRepository;
    
    /** @var  PartyTypesRepository */
    private $partyTypesRepository;
    
    /** @var  PartySubTypesRepository */
    private $partySubTypesRepository;
    
    /** @var  PartyStreamRepository */
    private $partyStreamRepository;

    /** @var  SalesInvoiceRepository */
    private $salesInvoiceRepository;

    /** @var  PurchaseReturnRepository */
    private $purchaseReturnRepository;

    /** @var  SalesReturnRepository */
    private $salesReturnRepository;

    /** @var  PaymentInRepository */
    private $paymentInRepository;

    /** @var  PaymentOutRepository */
    private $paymentOutRepository;
    
    /** @var  CustomerLevelsRepository */
    private $customerLevelsRepository;
    
    /** @var  MarketNotesRepository */
    private $marketNotesRepository;

    /** @var  SupplyPointsRepository */
    private $supplyPointsRepository;

    /** @var  DeliveryAddressRepository */
    private $deliveryAddressRepository;

    /** @var  StaffDesignationRepository */
    private $staffDesignationRepository;

    /** @var  RoleRepository */
    private $roleRepository;

    /**
     * @var CustomFieldRepository
     */
    private $customFieldRepository;

    /**
     * @var UploadRepository
     */
    private $uploadRepository;

    /**
     * @var CustomerGroupsRepository
     */
    private $customerGroupsRepository;

    /**
     * @var UserRepository
     */
    private $userRepository;
    /**
     * @var FieldRepository
     */
    private $fieldRepository;
    /**
     * @var DeliveryZonesRepository
     */
    private $deliveryZonesRepository;

    public function __construct(MarketRepository $marketRepo, PartyTypesRepository $partyTypesRepo, PartySubTypesRepository $partySubTypesRepo, PartyStreamRepository $partyStreamRepo, CustomFieldRepository $customFieldRepo, UploadRepository $uploadRepo, UserRepository $userRepo, FieldRepository $fieldRepository, CustomerGroupsRepository $customerGroupsRepo, PurchaseInvoiceRepository $purchaseInvoiceRepo, SalesInvoiceRepository $salesInvoiceRepo, PaymentInRepository $paymentInRepo, PaymentOutRepository $paymentOutRepo, PurchaseReturnRepository $purchaseReturnRepo, SalesReturnRepository $salesReturnRepo, CustomerLevelsRepository $customerLevelsRepo, MarketNotesRepository $marketNotesRepo, SupplyPointsRepository $supplyPointsRepo, DeliveryAddressRepository $deliveryAddressRepo, StaffDesignationRepository $staffDesignationRepo, DeliveryZonesRepository $deliveryZonesRepo, RoleRepository $roleRepo)
    {
        parent::__construct();
        $this->marketRepository = $marketRepo;
        $this->customFieldRepository = $customFieldRepo;
        $this->uploadRepository = $uploadRepo;
        $this->userRepository = $userRepo;
        $this->customerGroupsRepository = $customerGroupsRepo;
        $this->fieldRepository = $fieldRepository;
        $this->purchaseInvoiceRepository = $purchaseInvoiceRepo;
        $this->salesInvoiceRepository = $salesInvoiceRepo;
        $this->paymentInRepository = $paymentInRepo;
        $this->paymentOutRepository = $paymentOutRepo;
        $this->purchaseReturnRepository = $purchaseReturnRepo;
        $this->salesReturnRepository = $salesReturnRepo;
        $this->customerLevelsRepository = $customerLevelsRepo;
        $this->marketNotesRepository = $marketNotesRepo;
        $this->partyTypesRepository = $partyTypesRepo;
        $this->partySubTypesRepository = $partySubTypesRepo;
        $this->partyStreamRepository = $partyStreamRepo;
        $this->supplyPointsRepository = $supplyPointsRepo;
        $this->deliveryAddressRepository = $deliveryAddressRepo;
        $this->staffDesignationRepository = $staffDesignationRepo;
        $this->deliveryZonesRepository = $deliveryZonesRepo;
        $this->roleRepository = $roleRepo;
    }

    /**
     * Display a listing of the Market.
     *
     * @param MarketDataTable $marketDataTable
     * @return Response
     */
    public function index(MarketDataTable $marketDataTable)
    {
        $markets = $marketDataTable;
        if(Request('p_name')!='') {
            $markets->with('p_name',Request('p_name'));  
        }
        if(Request('p_code')!='') {
            $markets->with('p_code',Request('p_code'));  
        }
        if(Request('p_town')!='') {
            $markets->with('p_town',Request('p_town'));  
        }
        if(Request('p_mobile')!='') {
            $markets->with('p_mobile',Request('p_mobile'));  
        }
        if(Request('p_phone')!='') {
            $markets->with('p_phone',Request('p_phone'));  
        }
        
        $party_types  = $this->partyTypesRepository->pluck('name', 'id');
        $party_types->prepend("Party Type",null);

        return $markets->render('markets.index',compact('party_types'));
        
    }

    /**
     * Display a listing of the Market.
     *
     * @param MarketDataTable $marketDataTable
     * @return Response
     */
    public function requestedMarkets(RequestedMarketDataTable $requestedMarketDataTable)
    {
        return $requestedMarketDataTable->render('markets.requested');
    }

    /**
     * Show the form for creating a new Market.
     *
     * @return Response
     */
    public function create()
    {

        $user = $this->userRepository->getByCriteria(new ManagersCriteria())->pluck('name', 'id');
        $drivers = $this->userRepository->getByCriteria(new DriversCriteria())->pluck('name', 'id');
        $field = $this->fieldRepository->pluck('name', 'id');
        
        $customer_levels = $this->customerLevelsRepository->pluck('name','id');
        $customer_levels->prepend("Please Select",null);

        $customer_group = $this->customerGroupsRepository->pluck('name','id');
        $customer_group->prepend("Please Select",null);
        
        $party_types  = $this->partyTypesRepository->pluck('name', 'id');
        $party_types->prepend("Please Select",null);
        
        $party_stream = $this->partyStreamRepository->pluck('name', 'id');
        $party_stream->prepend("Please Select",null);

        $supply_points = $this->supplyPointsRepository->pluck('name', 'id');
        $supply_points->prepend("Please Select",null);

        $users = $this->userRepository->with("roles")->whereHas('roles', function ($q) { $q->where('name', 'admin'); })->pluck('name', 'id');
        $users->prepend("Please Select",null);

        $staff_designations = $this->staffDesignationRepository->pluck('name', 'id');
        $staff_designations->prepend("Please Select",null);
        
        $party_sub_types  = [null => "Please Select"];
        $partySubTypesSelected = [];
        $usersSelected = [];
        $driversSelected = [];
        $fieldsSelected = [];
        $customerGroupSelected = [];
        $customerLevelSelected = [];
        
        $hasCustomField = in_array($this->marketRepository->model(), setting('custom_field_models', []));
        if ($hasCustomField) {
            $customFields = $this->customFieldRepository->findByField('custom_field_model', $this->marketRepository->model());
            $html = generateCustomField($customFields);
        }
        return view('markets.create', compact('supply_points'))->with("party_types", $party_types)->with("party_stream", $party_stream)->with("party_sub_types", $party_sub_types)->with("partySubTypesSelected", $partySubTypesSelected)->with("customFields", isset($html) ? $html : false)->with("user", $user)->with("drivers", $drivers)->with("usersSelected", $usersSelected)->with("driversSelected", $driversSelected)->with('field', $field)->with('fieldsSelected', $fieldsSelected)->with('customer_group',$customer_group)->with('customerGroupSelected',$customerGroupSelected)->with('customer_levels',$customer_levels)->with('customerLevelSelected',$customerLevelSelected)->with('users',$users)->with('staff_designations',$staff_designations);
    }

    /**
     * Store a newly created Market in storage.
     *
     * @param CreateMarketRequest $request
     *
     * @return Response
     */
    public function store(CreateMarketRequest $request)
    {
        $input = $request->all();

        $input['code'] = CustomHelper::unique_party_code_generate($this->partyCodeGen($input['type'],$input['sub_type']),
            'GGPL-'.$this->partyPrefixGen($input['type'],$input['sub_type']).'-'
        );
        
        $input['date_of_birth']     = (isset($input['date_of_birth']) && $input['date_of_birth']!='') ? Carbon::parse($input['date_of_birth'])->format('Y-m-d') : null ;
        $input['created_by']        = auth()->user()->id;
        $input['created_via']       = 'admin_portal';
        $input['code_count']        = $this->partyCodeGen($request->type,$request->sub_type);
        $input['password']          = Hash::make('123456');
        $input['api_token']         = Str::random(60);
        $input['referred_by']       = null;  
        $input['affiliate_id']      = Str::random(6);  

        try {

            $user = $this->userRepository->create($input);
            if($input['type'] == 4) {
               $sub_type = $this->partySubTypesRepository->findWithoutFail($input['sub_type']);
               $user->syncRoles($sub_type->role->name); 
            } else {
                $user->syncRoles('customer');
            }

            if($user->id > 0) {
                $input['user_id'] = $user->id;
                $market = $this->marketRepository->create($input);

                $market->activity()->create([
                    'market_id'  => $market->id,
                    'action'     => 'Created',
                    'notes'      => $market->name.' '.$market->code.' Created',
                    'status'     => 'completed',
                    'created_by' => auth()->user()->id
                ]);

                if(isset($input['d_street_no']) && count($input['d_street_no']) > 0) {
                    for($i=0; $i<count($input['d_street_no']); $i++){
                        $data = array(
                            'user_id'           => $user->id,
                            'street_no'         => $input['d_street_no'][$i],
                            'street_type'       => $input['d_street_type'][$i],
                            'landmark_1'        => $input['d_landmark_1'][$i],
                            'landmark_2'        => $input['d_landmark_2'][$i],
                            'address_line_1'    => $input['d_address_line_1'][$i],
                            'address_line_2'    => $input['d_address_line_2'][$i],
                            'town'              => $input['d_town'][$i],
                            'city'              => $input['d_city'][$i],
                            'state'             => $input['d_state'][$i],
                            'pincode'           => $input['d_pincode'][$i],
                            'latitude'          => $input['d_latitude'][$i],
                            'longitude'         => $input['d_longitude'][$i],
                            'created_by'        => auth()->user()->id
                        );
                        if($input['d_street_no'][$i]!='' && $input['d_street_type'][$i]!='') {
                            $address = $this->deliveryAddressRepository->create($data);
                        }
                    }
                }

            }

            if (isset($input['image']) && $input['image']) {
                $cacheUpload = $this->uploadRepository->getByUuid($input['image']);
                $mediaItem = $cacheUpload->getMedia('image')->first();
                $mediaItem->copy($market, 'image');
            }
        } catch (ValidatorException $e) {
            Flash::error($e->getMessage());
        }
        
         //Notification mail sent
            /*if($input['type']=1){
                $party_type = 'Customer';
            } else if($input['type']=2) {
                 $party_type = 'Supplier';
            } else {
                $party_type = 'Farmer';
            }

            $customer_mail = $input['email']; 
            $customer_name = $input['name']; 

             $details = ['title' => 'Party Created successfully','body' => 'Party Created successfully','customer_name' =>$customer_name,'customer_mail' =>$input['email'],'party_type' =>$party_type];

            \Mail::to($customer_mail)->send(new PartiesMail($details));*/

        if($request->ajax()) {
            return $market;
        }    

        Flash::success(__('lang.saved_successfully', ['operator' => 'Party']));
        return redirect(route('markets.index'));
    }

    /**
     * Display the specified Market.
     *
     * @param int $id
     *
     * @return Response
     * @throws \Prettus\Repository\Exceptions\RepositoryException
     */
    public function show(Request $request, $id)
    {
        $market = $this->marketRepository->with('customer_level')->with('user')->with('user.deliveryaddress')->findWithoutFail($id);
        if (empty($market)) {
            Flash::error('Market not found');
            return redirect(route('markets.index'));
        }
        if($request->ajax()) {
            if($request->type == 'load_address') {

                //Delivery Fee Calculation
                    $distance_data = file_get_contents('https://maps.googleapis.com/maps/api/distancematrix/json?origins='.setting('app_store_latitude').','.setting('app_store_longitude').'&destinations=side_of_road:'.$market->latitude.','.$market->longitude.'&mode=driving&key='.setting('google_api_key').'');
                    $distance_arr  = json_decode($distance_data);
                    $status        = $distance_arr->rows[0]->elements[0]->status;
                    if($status=='OK') :
                        $distance_result   = $distance_arr->rows[0]->elements[0];
                        $distance_in_meter = $distance_result->distance->value;
                        $distance_duration = $distance_result->duration->text;
                        $distance_in_km    = $distance_in_meter / 1000;
                        $deliveryZones     = $this->deliveryZonesRepository->get();
                        
                        $delivery_charge   = 0;
                        if(count($deliveryZones) > 0) {
                            foreach ($deliveryZones as $key1 => $value) {
                                if($distance_in_km >= $value->distance) {
                                   $delivery_charge = $value->delivery_charge;
                                }
                            }                
                        }
                        
                        $market->delivery_distance = ($distance_in_km > 0 && $distance_in_km <= 150) ? number_format($distance_in_km,'2','.','') : '' ;
                        $market->delivery_duration = ($distance_in_km > 0 && $distance_in_km <= 150) ? $distance_duration : '' ;
                        $market->delivery_charge   = ($distance_in_km > 0 && $distance_in_km <= 150) ? $delivery_charge : '' ;
                    endif;    
                //Delivery Fee Calculation

                return ['status' => 'success', 'data' => $market];    

            } else {
                return ['status' => 'success', 'data' => $market];
            }
        }
        $notes = $this->marketNotesRepository->where('market_id',$id)->get();
        return view('markets.show')->with('market', $market)->with('notes',$notes);
    }

    /**
     * Show the form for editing the specified Market.
     *
     * @param int $id
     *
     * @return Response
     * @throws \Prettus\Repository\Exceptions\RepositoryException
     */
    public function edit($id)
    {
        $market = $this->marketRepository->findWithoutFail($id);
        if (empty($market)) {
            Flash::error(__('Not found', ['operator' => 'Party']));
            return redirect(route('markets.index'));
        }
        if($market['active'] == 0){
            $user = $this->userRepository->getByCriteria(new ManagersClientsCriteria())->pluck('name', 'id');
        } else {
            $user = $this->userRepository->getByCriteria(new ManagersCriteria())->pluck('name', 'id');
        }
        //$user = $market->users();
        //$drivers = $this->userRepository->getByCriteria(new DriversCriteria())->pluck('name', 'id');
        $field          = $this->fieldRepository->pluck('name', 'id');

        $customer_group = $this->customerGroupsRepository->pluck('name','id');
        $customer_group->prepend("Please Select",null);

        $customer_levels = $this->customerLevelsRepository->pluck('name','id');
        $customer_levels->prepend("Please Select",null);
        
        $party_types     = $this->partyTypesRepository->pluck('name', 'id');
        $party_stream    = $this->partyStreamRepository->pluck('name', 'id');
        $party_sub_types = $this->partySubTypesRepository->where('party_type_id',$market->type)->pluck('name', 'id');

        $users = $this->userRepository->with("roles")->whereHas('roles', function ($q) { $q->where('name', 'admin'); })->pluck('name', 'id');
        $users->prepend("Please Select",null);

        $staff_designations = $this->staffDesignationRepository->pluck('name', 'id');
        $staff_designations->prepend("Please Select",null);

        $partySubTypesSelected = $market->sub_type;

        //$usersSelected = $market->users()->pluck('users.id')->toArray();
        //$driversSelected = $market->drivers()->pluck('users.id')->toArray();
        //$fieldsSelected  = $market->fields()->pluck('fields.id')->toArray();
        $customerGroupSelected = $market->customer_group_id;
        $customerLevelSelected = $market->customer_level_id;

        $customFieldsValues = $market->customFieldsValues()->with('customField')->get();
        $customFields = $this->customFieldRepository->findByField('custom_field_model', $this->marketRepository->model());
        $hasCustomField = in_array($this->marketRepository->model(), setting('custom_field_models', []));
        if ($hasCustomField) {
            $html = generateCustomField($customFields, $customFieldsValues);
        }

        $supply_points = $this->supplyPointsRepository->pluck('name', 'id');
        $supply_points->prepend("Please Select",null);

        return view('markets.edit',compact('supply_points'))->with("party_types", $party_types)->with("party_sub_types", $party_sub_types)->with("party_stream", $party_stream)->with("partySubTypesSelected", $partySubTypesSelected)->with('market', $market)->with("customFields", isset($html) ? $html : false)->with("user", $user)->with('customer_group',$customer_group)->with('customerGroupSelected',$customerGroupSelected)->with('customer_levels',$customer_levels)->with('customerLevelSelected',$customerLevelSelected)->with('users',$users)->with('staff_designations',$staff_designations);
    }

    /**
     * Update the specified Market in storage.
     *
     * @param int $id
     * @param UpdateMarketRequest $request
     *
     * @return Response
     * @throws \Prettus\Repository\Exceptions\RepositoryException
     */
    public function update($id, UpdateMarketRequest $request)
    {
        $oldMarket = $this->marketRepository->findWithoutFail($id);
        if (empty($oldMarket)) {
            Flash::error('Party not found');
            return redirect(route('markets.index'));
        }
        $input = $request->all();

        try {
            
            $input['date_of_birth'] = ($input['date_of_birth']!='') ? Carbon::parse($input['date_of_birth'])->format('Y-m-d') : null ;
            $input['updated_by']    = auth()->user()->id;
            
            $market = $this->marketRepository->update($input, $id);
            $user   = $this->userRepository->update($input,$market->user->id);

            $market->activity()->create([
                'market_id'  => $market->id,
                'action'     => 'Updated',
                'notes'      => $market->name.' '.$market->code.' Updated',
                'status'     => 'completed',
                'created_by' => auth()->user()->id
            ]);

            if(isset($input['d_street_no']) && count($input['d_street_no']) > 0) {
                for($i=0; $i<count($input['d_street_no']); $i++){
                    $data = array(
                        'user_id'           => $user->id,
                        'street_no'         => $input['d_street_no'][$i],
                        'street_type'       => $input['d_street_type'][$i],
                        'landmark_1'        => $input['d_landmark_1'][$i],
                        'landmark_2'        => $input['d_landmark_2'][$i],
                        'address_line_1'    => $input['d_address_line_1'][$i],
                        'address_line_2'    => $input['d_address_line_2'][$i],
                        'town'              => $input['d_town'][$i],
                        'city'              => $input['d_city'][$i],
                        'state'             => $input['d_state'][$i],
                        'pincode'           => $input['d_pincode'][$i],
                        'latitude'          => $input['d_latitude'][$i],
                        'longitude'         => $input['d_longitude'][$i],
                        'created_by'        => auth()->user()->id
                    );
                    if($input['d_street_no'][$i]!='' && $input['d_street_type'][$i]!='') {
                        if(isset($input['delivery_address_id'][$i]) && $input['delivery_address_id'][$i] > 0) {
                            $address = $this->deliveryAddressRepository->update($data,$input['delivery_address_id'][$i]);
                        } else {
                            $address = $this->deliveryAddressRepository->create($data);
                        }
                    }
                }
            }
            
            if (isset($input['image']) && $input['image']) {
                $cacheUpload = $this->uploadRepository->getByUuid($input['image']);
                $mediaItem = $cacheUpload->getMedia('image')->first();
                $mediaItem->copy($market, 'image');
            }

        } catch (ValidatorException $e) {
            Flash::error($e->getMessage());
        }
        
        //Update Balance
            //CustomHelper::updatePartyBalance($id);
        //Update Balance

        Flash::success(__('Updated Successfully', ['operator' => 'Party']));
        return redirect(route('markets.index'));
    }

    /**
     * Remove the specified Market from storage.
     *
     * @param int $id
     *
     * @return Response
     * @throws \Prettus\Repository\Exceptions\RepositoryException
     */
    public function destroy($id)
    {
        if (!env('APP_DEMO', false)) {
            $market      = $this->marketRepository->findWithoutFail($id);
            if (empty($market)) {
                Flash::error('Market not found');
                return redirect(route('markets.index'));
            }
            $this->userRepository->delete($market->user->id);
            Flash::success(__('lang.deleted_successfully', ['operator' => __('lang.market')]));
        } else {
            Flash::warning('This is only demo app you can\'t change this section ');
        }
        return redirect(route('markets.index'));


        /*if (!env('APP_DEMO', false)) {
            $this->marketRepository->pushCriteria(new MarketsOfUserCriteria(auth()->id()));
            $market      = $this->marketRepository->findWithoutFail($id);
            $user_market = DB::table('user_markets')->where('market_id',$id)->first();  
            if (empty($market)) {
                Flash::error('Market not found');

                return redirect(route('markets.index'));
            }
            
            //Destroy Customer Datas
                CustomHelper::destroyCustomerdatas($id);
            //Destroy Customer Datas
            
            $this->marketRepository->delete($id);
            if($user_market){
                $this->userRepository->delete($user_market->user_id);
            }

            Flash::success(__('lang.deleted_successfully', ['operator' => __('lang.market')]));
        } else {
            Flash::warning('This is only demo app you can\'t change this section ');
        }
        return redirect(route('markets.index'));*/
    }

    /**
     * Remove Media of Market
     * @param Request $request
     */
    public function removeMedia(Request $request)
    {
        $input = $request->all();
        $market = $this->marketRepository->findWithoutFail($input['id']);
        try {
            if ($market->hasMedia($input['collection'])) {
                $market->getFirstMedia($input['collection'])->delete();
            }
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }
    }


    public function view(Request $request, $id)
    {
        $market  = $this->marketRepository
                        ->with('customer_level')
                        ->with('user')
                        ->with('pendingsalesinvoices')
                        ->with('pendingsalesreturns')
                        ->with('pendingpurchaseinvoices')
                        ->with('pendingpurchasereturns')
                        ->with('user.deliveryaddress')
                        ->findWithoutFail($id);
        
        if($request->ajax()) {
            if($request->type == 'load_address') {

                //Delivery Fee Calculation
                    $distance_data = file_get_contents('https://maps.googleapis.com/maps/api/distancematrix/json?origins='.setting('app_store_latitude').','.setting('app_store_longitude').'&destinations=side_of_road:'.$market->latitude.','.$market->longitude.'&mode=driving&key='.setting('google_api_key').'');
                    $distance_arr  = json_decode($distance_data);
                    $status        = $distance_arr->rows[0]->elements[0]->status;
                    if($status=='OK') :
                        $distance_result   = $distance_arr->rows[0]->elements[0];
                        $distance_in_meter = $distance_result->distance->value;
                        $distance_duration = $distance_result->duration->text;
                        $distance_in_km    = $distance_in_meter / 1000;
                        $deliveryZones     = $this->deliveryZonesRepository->get();
                        
                        $delivery_charge   = 0;
                        if(count($deliveryZones) > 0) {
                            foreach ($deliveryZones as $key1 => $value) {
                                if($distance_in_km >= $value->distance) {
                                   $delivery_charge = $value->delivery_charge;
                                }
                            }                
                        }
                        
                        $market->delivery_distance = ($distance_in_km > 0 && $distance_in_km <= 150) ? number_format($distance_in_km,'2','.','') : '' ;
                        $market->delivery_duration = ($distance_in_km > 0 && $distance_in_km <= 150) ? $distance_duration : '' ;
                        $market->delivery_charge   = ($distance_in_km > 0 && $distance_in_km <= 150) ? $delivery_charge : '' ;
                    endif;    
                //Delivery Fee Calculation

                return ['status' => 'success', 'data' => $market];    

            } elseif($request->type == 'load_pending_invoices') {

                return ['status' => 'success', 'data' => $market];
            } else {
                return ['status' => 'success', 'data' => $market];
            }
        }
        $markets = $this->marketRepository->get();
        
        $staffs  = $this->userRepository
                        ->with("roles")
                        ->whereHas('roles', function ($q) { 
                            $q->where('name', 'manager')
                              ->orWhere('name', 'supervisor')
                              ->orWhere('name', 'worker')
                              ->orWhere('name', 'driver'); 
                        })->pluck('name','id');
        $staffs->prepend('Please select', null);

        $transactions = array(
            null                => 'All Transactions',
            'sales'             => 'Sales',
            'sales_return'      => 'Sales Return',
            'purchase'          => 'Purchase',
            'purchase_return'   => 'Purchase Return',
            'payment_in'        => 'Payment In',
            'payment_out'       => 'Payment Out',
        ); 
        if(empty($market)) {
            Flash::error('Party not found');
            return redirect(route('markets.index'));
        }
        return view('markets.show')->with('market', $market)->with('markets', $markets)->with('transactions', $transactions)->with('notes',$market->notes)->with('staffs',$staffs);
    }

    public function partyFields() {
        $user = $this->userRepository->getByCriteria(new ManagersCriteria())->pluck('name', 'id');
        $drivers = $this->userRepository->getByCriteria(new DriversCriteria())->pluck('name', 'id');
        $field = $this->fieldRepository->pluck('name', 'id');
        $customer_group = $this->customerGroupsRepository->pluck('name','id');
        $customer_group->prepend("Please Select",null);
        $usersSelected = [];
        $driversSelected = [];
        $fieldsSelected = [];
        $customerGroupSelected = [];
        $hasCustomField = in_array($this->marketRepository->model(), setting('custom_field_models', []));
        if ($hasCustomField) {
            $customFields = $this->customFieldRepository->findByField('custom_field_model', $this->marketRepository->model());
            $html = generateCustomField($customFields);
        }
        return view('markets.simple-fields')->with("customFields", isset($html) ? $html : false)->with("user", $user)->with("drivers", $drivers)->with("usersSelected", $usersSelected)->with("driversSelected", $driversSelected)->with('field', $field)->with('fieldsSelected', $fieldsSelected)->with('customer_group',$customer_group)->with('customerGroupSelected',$customerGroupSelected);
    }
    
    public function storeNotes($id,Request $request) {
        
        $validator = $request->validate([
            'notes' => 'required',
        ]); 
        if($validator) {
        
            $data['market_id']  = $id;
            $data['notes']      = $request->notes;
            $data['created_by'] = auth()->user()->id;
            $notes = $this->marketNotesRepository->create($data);
            Flash::success(__('lang.saved_successfully', ['operator' => __('lang.market')]));
            return redirect()->back();
            
        } else {
            Flash::error($e->getMessage());
            return redirect()->back();
        }
    }
    
     public function showPartySubTypes(Request $request){
  
        $type = $request->type;
        $subtypes = $this->partyTypesRepository->where('id',$type)
                              ->with('party_sub_types')
                              ->get();
        return response()->json([
            'party_sub_types' => $subtypes
        ]);
     }

    public function import() {
        
        $party_types  = $this->partyTypesRepository->pluck('name')->toJson();
        $party_sub_types_roles  = $this->roleRepository->pluck('name')->toJson();
        return view('markets.import',compact('party_types','party_sub_types_roles'));
    }
    
    public function importMarkets(Request $request) {

        $file = $request->file('file');
        if (empty($file)) {
            Flash::error('Please select the import file');
            return redirect(route('markets.import'));
        }

        try {
            
            Excel::import(new MarketsImport,$file);
            Flash::success('Imported successfully', ['operator' => 'Markets']);
            
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
             $failures = $e->failures();
             foreach ($failures as $failure) {
                 Flash::error($failure->errors()[0]);
             }
        }
        return redirect(route('markets.import'));
    } 
}
