<?php

namespace App\Imports;

use App\Models\Market;
use App\Models\PartyStreams;
use App\Models\PartyTypes;
use App\Models\PartySubTypes;
use App\Models\StaffDesignation;
use App\Models\CustomerGroups;
use App\Models\User;
use Spatie\Permission\Models\Role;

use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Carbon\Carbon;
use CustomHelper;

class MarketsImport implements ToModel, WithValidation, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {   

        //Party Type Validate   
            $type                       = PartyTypes::firstOrCreate(['name'=>$row['type']]);
            $row['type']                = $type->id;

        //Roles Validate   
            $role                       = Role::firstOrCreate(['name'=>$row['sub_type_role']]);
            $row['role_id']             = $role->id;    

        //Sub Type Validate    
            $sub_type           = PartySubTypes::firstOrCreate([
                'party_type_id' =>  $row['type'],
                'role_id'       =>  $row['role_id'],
                'name'          =>  $row['sub_type'],
                'prefix_value'  =>  $row['sub_type_prefix']
            ]);
            $row['sub_type']    = $sub_type->id;    

        //Party Stream Validate   
        if($row['stream'] != '') {
            $stream                     = PartyStreams::firstOrCreate(['name'=>$row['stream']]);
            $row['stream']              = $stream->id;
        }    

        //Party Designation Validate   
        if($row['staff_designation'] != '') {
            $designation                 = StaffDesignation::firstOrCreate(['name'=>$row['staff_designation']]);
            $row['staff_designation'] = $designation->id;
        }

        //Customer Group Validate   
        if($row['customer_group'] != '') {
            $customer_group             = CustomerGroups::firstOrCreate(['name'=>$row['customer_group']]);
            $row['customer_group']      = $customer_group->id;        
        }

            $row['code']                = CustomHelper::unique_party_code_generate($this->partyCodeGen($row['type'],$row['sub_type']),
                                            'GGPL-'.$this->partyPrefixGen($row['type'],$row['sub_type']).'-'
                                          );
            $row['created_by']          = auth()->user()->id;
            $row['created_via']         = 'admin_portal';
            $row['code_count']          = $this->partyCodeGen($row['type'],$row['sub_type']);
            $row['password']            = Hash::make('123456');
            $row['api_token']           = Str::random(60);
            $row['referred_by']         = null;  
            $row['affiliate_id']        = Str::random(6); 


            $user = User::create([
                'name'              => $row['name'],
                'email'             => $row['email'],
                'password'          => $row['password'],
                'api_token'         => $row['api_token'],
                'customer_group_id' => $row['customer_group'],
                'gender'            => $row['gender'],
                'date_of_birth'     => $row['date_of_birth'],
                'is_staff'          => 0,
                'device_token'      => null,
                'card_brand'        => null,
                'card_last_four'    => null,
                'braintree_id'      => null,
                'paypal_email'      => null,
                'remember_token'    => null,
                'points'            => 0,
                'level'             => null,
                'referred_by'       => null,
                'affiliate_id'      => $row['affiliate_id'],
                'social_login_id'   => null,
                'created_at'        => null,
                'updated_at'        => null,
                'stripe_id'         => null,
                'pm_type'           => null,
                'pm_last_four'      => null,
                'trial_ends_at'     => null
            ]);
            
            if($row['type'] == 4) {
               $sub_type = PartySubTypes::where('id',$row['sub_type'])->first();
               $user->syncRoles($sub_type->role->name); 
            } else {
                $user->syncRoles('customer');
            } 

            if($user->id > 0) { 

                $market = Market::create([
                    'user_id'                   => $user->id,
                    'name'                      => $row['name'],
                    'code'                      => $row['code'],
                    'code_count'                => $row['code_count'],
                    'description'               => $row['description'],
                    'street_no'                 => $row['street_no'],
                    'street_name'               => $row['street_name'],
                    'street_type'               => $row['street_type'],
                    'landmark_1'                => $row['landmark_1'],
                    'landmark_2'                => $row['landmark_2'],
                    'address_line_1'            => $row['address_line_1'],
                    'address_line_2'            => $row['address_line_2'],
                    'town'                      => $row['town'],
                    'city'                      => $row['city'],
                    'state'                     => $row['state'],
                    'pincode'                   => $row['pincode'],
                    'manual_address'            => $row['manual_address'],
                    'current_location_address'  => $row['current_location_address'],
                    'gender'                    => $row['gender'],
                    'date_of_birth'             => $row['date_of_birth'],
                    'latitude'                  => $row['latitude'],
                    'longitude'                 => $row['longitude'],
                    'email'                     => $row['email'],
                    'phone'                     => $row['phone'],
                    'mobile'                    => $row['mobile'],
                    'information'               => $row['information'],
                    'type'                      => $row['type'],
                    'sub_type'                  => $row['sub_type'],
                    'stream'                    => $row['stream'],
                    'gstin'                     => $row['gstin'],
                    //'balance'                   => $row['balance'],
                    'customer_group_id'         => $row['customer_group'],
                    //'customer_level_id'         => $row['customer_level'],
                    'hear_about_us'             => $row['hear_about_us'],
                    'email_subscription'        => $row['email_subscription'],
                    'sms_subscription'          => $row['sms_subscription'],
                    'party_alert'               => $row['party_alert'],
                    'party_alert_type'          => $row['party_alert_type'],
                    'party_alert_end_date'      => $row['party_alert_end_date'],
                    'policy_and_terms'          => $row['policy_and_terms'],
                    'verified_by'               => $row['verified_by'],
                    'party_size'                => $row['party_size'],
                    'supply_point'              => $row['supply_point'],
                    'membership_type'           => $row['membership_type'],
                    'referred_by'               => $row['referred_by'],
                    'staff_designation_id'      => $row['staff_designation'],
                    'date_of_joining'           => $row['date_of_joining'],
                    'probation_ended_on'        => $row['probation_ended_on'],
                    'termination_date'          => $row['termination_date'],
                    'salary'                    => $row['salary'],
                    'salary_agreed'             => $row['salary_agreed'],
                    'preferred_language'        => $row['preferred_language'],
                    'created_via'               => 'admin_portal',
                    'active'                    => $row['active'],
                    'created_by'                => auth()->user()->id,
                ]);

                return $market;
            } else {
                return false;
            }
    
    }

    public function partyCodeGen($party_type, $party_sub_type) 
    {
        $get_party       = Market::where('sub_type',$party_sub_type)->orderBy('id', 'desc')->get();
        $get_party_count = count($get_party);   
        if($get_party_count > 0) {
            $code_count = $get_party[0]->code_count+1;
        } else {
            $code_count = 1; 
        }
        return $code_count;
    }

    public function partyPrefixGen($party_type, $party_sub_type) 
    {
        //get prefix value
        $sub_type       = PartySubTypes::where('id',$party_sub_type)->where('party_type_id',$party_type)->first();
        $prefix_value   = ($sub_type) ? $sub_type->prefix_value : '' ;
        return $prefix_value;
    }

    public function rules(): array
    {
        $rules = [
            'name' => 'required|string',
            'description' => 'nullable|string',
            'street_no' => 'nullable|integer',
            'street_name' => 'nullable|string',
            'street_type' => 'nullable|string',
            'landmark_1' => 'nullable|string',
            'landmark_2' => 'nullable|string',
            'address_line_1' => 'nullable|string',
            'address_line_2' => 'nullable|string',
            'town' => 'nullable|string',
            'city' => 'nullable|string',
            'state' => 'nullable|string',
            'pincode' => 'nullable|integer',
            'manual_address' => 'nullable|string',
            'current_location_address' => 'nullable|string',
            'gender' => 'required|string',
            'date_of_birth' => 'required|date',
            'latitude' => 'nullable|string',
            'longitude' => 'nullable|string',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'nullable|numeric|digits:11',
            'mobile' => 'required|numeric|digits:10',
            'information' => 'nullable|string',
            'type' => 'required|string',
            'sub_type' => 'required|string',
            'sub_type_prefix' => 'required|string',
            'sub_type_role' => 'required|in:customer,vendor,manager,supervisor,worker,driver',
            'stream' => 'nullable|string',
            'gstin' => 'nullable|string',
            'customer_group' => 'nullable|string',
            'hear_about_us' => 'nullable|string',
            'email_subscription' => 'nullable|boolean',
            'sms_subscription' => 'nullable|boolean',
            'party_alert' => 'nullable|boolean',
            'party_alert_type' => 'nullable|string',
            'party_alert_end_date' => 'nullable|date',
            'policy_and_terms' => 'nullable|integer',
            'verified_by' => 'nullable|integer',
            'party_size' => 'nullable|string',
            'supply_point' => 'nullable|string',
            'membership_type' => 'nullable|string',
            'referred_by' => 'nullable|string',
            'staff_designation' => 'required_if:type,Staff',
            'date_of_joining' => 'required_if:type,Staff',
            'termination_date' => 'nullable|date',
            'salary' => 'required_if:type,Staff',
            'salary_agreed' => 'required_if:type,Staff',
            'preferred_language' => 'required|string',
            'active' => 'required|boolean'
        ];
        return $rules;
    }

}
