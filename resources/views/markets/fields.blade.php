<div class="col-md-12 column custom-from-css">
   <div class="row">
      <!-- Name Field -->
      <div class="col-md-4">
         <div class="form-group">
            {!! Form::label('name', trans("lang.market_name"), ['class' => ' control-label text-right required']) !!}
            {!! Form::text('name', null,  ['class' => 'form-control','placeholder'=>  trans("lang.market_name_placeholder")]) !!}
            <div class="form-text text-muted">
               <!-- {{ trans("lang.market_name_help") }} -->
            </div>
         </div>
      </div>
      <!-- Email Field -->
      <div class="col-md-4">
         <div class="form-group">
            {!! Form::label('email', trans("lang.market_email"), ['class' => 'control-label text-right required']) !!}
            {!! Form::email('email', null,  ['class' => 'form-control','placeholder'=>  trans("lang.market_email_placeholder")]) !!}
            <div class="form-text text-muted">
               <!-- {{ trans("lang.market_email_help") }} -->
            </div>
         </div>
      </div>
      <!-- Phone Field -->
      <div class="col-md-4">
         <div class="form-group">
            {!! Form::label('phone', trans("lang.market_phone"), ['class' => 'control-label text-right']) !!}
            {!! Form::text('phone', null,  ['class' => 'form-control','placeholder'=>  trans("lang.market_phone_placeholder")]) !!}
            <div class="form-text text-muted">
               <!-- {{ trans("lang.market_phone_help") }} -->
            </div>
         </div>
      </div>
      <!-- Mobile Field -->
      <div class="col-md-4">
         <div class="form-group">
            {!! Form::label('mobile', trans("lang.market_mobile"), ['class' => 'control-label text-right']) !!}
            {!! Form::text('mobile', null,  ['class' => 'form-control','placeholder'=>  trans("lang.market_mobile_placeholder")]) !!}
            <div class="form-text text-muted">
               <!-- {{ trans("lang.market_mobile_help") }} -->
            </div>
         </div>
      </div>
      <!-- Gender Field -->
      <div class="col-md-4">
         <div class="form-group">
            {!! Form::label('gender', trans("lang.user_gender"), ['class' => 'control-label text-right']) !!}
            {!! Form::select('gender', [null => 'Please Select', 'male' => 'Male', 'female' => 'Female', 'others' => 'Others'], null, ['class' => 'select2 form-control']) !!}
            <div class="form-text text-muted">
               <!-- {{ trans("lang.user_gender_help") }} -->
            </div>
         </div>
      </div>
      <!-- Date of birth Field -->
      <div class="col-md-4">
         <div class="form-group">
            {!! Form::label('date_of_birth', 'Date of Birth', ['class' => 'control-label text-right']) !!}
            {!! Form::text('date_of_birth', isset($market->date_of_birth) ? $market->date_of_birth->format('d-m-Y') : null ,  ['class' => 'form-control dobdatepicker','placeholder'=>  "Please select the date"]) !!}
            <div class="form-text text-muted">
               <!-- {{ trans("lang.user_date_of_birth_help") }} -->
            </div>
         </div>
      </div>
      <hr>

      <!-- Street No Field -->
      <div class="col-md-4">
         <div class="form-group">
            {!! Form::label('street_no', 'Street No', ['class' => 'control-label text-right']) !!}
            {!! Form::text('street_no', null,  ['class' => 'form-control','placeholder'=>  "Insert Street No"]) !!}
            <div class="form-text text-muted">
            </div>
         </div>
      </div>

      <!-- Street Name Field -->
      <div class="col-md-4">
         <div class="form-group">
            {!! Form::label('street_name', 'Street Name', ['class' => 'control-label text-right']) !!}
            {!! Form::text('street_name', null,  ['class' => 'form-control','placeholder'=>  "Insert Street Name"]) !!}
            <div class="form-text text-muted">
            </div>
         </div>
      </div>

      <!-- Street Type Field -->
      <div class="col-md-4">
         <div class="form-group">
            {!! Form::label('street_type', 'Street Type', ['class' => 'control-label text-right']) !!}
            {!! Form::text('street_type', null,  ['class' => 'form-control','placeholder'=>  "Insert Street Type"]) !!}
            <div class="form-text text-muted">
            </div>
         </div>
      </div>

      <!-- Landmark 1 Field -->
      <div class="col-md-4">
         <div class="form-group">
            {!! Form::label('landmark_1', 'Landmark 1', ['class' => 'control-label text-right']) !!}
            {!! Form::text('landmark_1', null,  ['class' => 'form-control','placeholder'=>  "Insert Landmark 1"]) !!}
            <div class="form-text text-muted">
            </div>
         </div>
      </div>

      <!-- Landmark 2 Field -->
      <div class="col-md-4">
         <div class="form-group">
            {!! Form::label('landmark_2', 'Landmark 2', ['class' => 'control-label text-right']) !!}
            {!! Form::text('landmark_2', null,  ['class' => 'form-control','placeholder'=>  "Insert Landmark 2"]) !!}
            <div class="form-text text-muted">
            </div>
         </div>
      </div>

      <!-- Address Field -->
      <div class="col-md-4">
         <div class="form-group">
            {!! Form::label('address_line_1', trans("lang.market_address_line_1"), ['class' => 'control-label text-right']) !!}
            {!! Form::text('address_line_1', null,  ['class' => 'form-control','placeholder'=>  trans("lang.market_address_line_1_placeholder")]) !!}
            <div class="form-text text-muted">
               <!-- {{ trans("lang.market_address_line_1_help") }} -->
            </div>
         </div>
      </div>

      <!-- Address Field -->
      <div class="col-md-4">
         <div class="form-group">
            {!! Form::label('address_line_2', trans("lang.market_address_line_2"), ['class' => 'control-label text-right']) !!}
            {!! Form::text('address_line_2', null,  ['class' => 'form-control','placeholder'=>  trans("lang.market_address_line_2_placeholder")]) !!}
            <div class="form-text text-muted">
               <!-- {{ trans("lang.market_address_line_2_help") }} -->
            </div>
         </div>
      </div>

      <!-- Town Field -->
      <div class="col-md-4">
         <div class="form-group">
            {!! Form::label('town', 'Town', ['class' => 'control-label text-right']) !!}
            {!! Form::text('town', null,  ['class' => 'form-control','placeholder'=> "Insert Town"]) !!}
            <div class="form-text text-muted">
            </div>
         </div>
      </div>

      <!-- City Field -->
      <div class="col-md-4">
         <div class="form-group">
            {!! Form::label('city', trans("lang.market_city"), ['class' => 'control-label text-right']) !!}
            {!! Form::text('city', null,  ['class' => 'form-control','placeholder'=>  trans("lang.market_city_placeholder")]) !!}
            <div class="form-text text-muted">
               <!-- {{ trans("lang.market_city_help") }} -->
            </div>
         </div>
      </div>
      <!-- State Field -->
      <div class="col-md-4">
         <div class="form-group">
            {!! Form::label('state', trans("lang.market_state"), ['class' => 'control-label text-right']) !!}
            {!! Form::text('state', null,  ['class' => 'form-control','placeholder'=>  trans("lang.market_state_placeholder")]) !!}
            <div class="form-text text-muted">
               <!-- {{ trans("lang.market_state_help") }} -->
            </div>
         </div>
      </div>
      <!-- Pincode Field -->
      <div class="col-md-4">
         <div class="form-group">
            {!! Form::label('pincode', trans("lang.market_pincode"), ['class' => 'control-label text-right']) !!}
            {!! Form::text('pincode', null,  ['class' => 'form-control','placeholder'=>  trans("lang.market_pincode_placeholder")]) !!}
            <div class="form-text text-muted">
               <!-- {{ trans("lang.market_pincode_help") }} -->
            </div>
         </div>
      </div>
      <!-- GSTIN Field -->
      <div class="col-md-4">
         <div class="form-group">
            {!! Form::label('gstin', trans("lang.market_gstin"), ['class' => 'control-label text-right']) !!}
            {!! Form::text('gstin', null,  ['class' => 'form-control','placeholder'=>  trans("lang.market_gstin_placeholder")]) !!}
            <div class="form-text text-muted">
               <!-- {{ trans("lang.market_gstin_help") }} -->
            </div>
         </div>
      </div>

      <!-- Latitude Field -->
      <div class="col-md-4">
         <div class="form-group">
            {!! Form::label('latitude', trans("lang.market_latitude"), ['class' => 'control-label text-right']) !!}
            <div class="input-group-append">
               {!! Form::text('latitude', null,  ['class' => 'form-control','placeholder'=>  trans("lang.market_latitude_placeholder")]) !!}
               <span class="input-group-text" style="margin-left: -5px;border: none;background: #e3e9ec;border-left: 2px solid #d6d5d5;backface-visibility: visible;z-index: 1;border-radius: 0px 8px 8px 0px;"> <a data-toggle="modal" href="#myModal"><i class="fa fa-map-marker mr-1"></i></a></i></span>
            </div>
         </div>
      </div>

      <!-- Longitude Field -->
      <div class="col-md-4">
         <div class="form-group">
            {!! Form::label('longitude', trans("lang.market_longitude"), ['class' => 'control-label text-right']) !!}
            <div class="input-group-append">
               {!! Form::text('longitude', null,  ['class' => 'form-control','placeholder'=>  trans("lang.market_longitude_placeholder")]) !!}
               <span class="input-group-text" style="margin-left: -5px;border: none;background: #e3e9ec;border-left: 2px solid #d6d5d5;backface-visibility: visible;z-index: 1;border-radius: 0px 8px 8px 0px;"> <a data-toggle="modal" href="#myModal"><i class="fa fa-map-marker mr-1"></i></a></i></span>
            </div>
         </div>
      </div>

      <div class="modal" id="myModal">
         <div class="modal-dialog">
            <div class="modal-content">
               <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
               </div>
               <div class="modal-body">
                  <div id="us2" style="height: 400px;"></div>
                     <div class="row mt-3">
                        <div class="col-md-6">
                            <label>Latitude : </label> 
                            <input type="text" class="form-control" id="us2-lat" />
                        </div>
                        <div class="col-md-6">
                            <label>Longitude : </label> 
                            <input type="text" class="form-control" id="us2-lon" />
                        </div>
                     </div>
               </div>
               <div class="modal-footer"> 
                  <a href="#" data-dismiss="modal" class="btn btn-secondary">Close</a>
                  <a href="#" class="btn btn-primary" id="save-changes">Save changes</a>
               </div>
            </div>
         </div>
      </div>
      
      <!-- Party Stream Field -->
      <div class="col-md-4">
         <div class="form-group">
            {!! Form::label('stream', trans("lang.market_party_stream"),['class' => 'control-label text-right required']) !!}
            <div class="input-group-append">
               {!! Form::select('stream', $party_stream,null, ['class' => 'select2 form-control stream']) !!}
               @if(!isset($market))
               <span class="input-group-text" style="margin-left: -5px;border: none;background: #e3e9ec;border-left: 2px solid #d6d5d5;backface-visibility: visible;z-index: 1;border-radius: 0px 8px 8px 0px;"> <a  href="" data-toggle="modal" data-target="#partyStreamModal"><i class="fa fa-plus mr-1"></i></a></i></span>
               @endif
            </div>
            <div class="form-text text-muted">
               <!-- {{ trans("lang.market_party_sub_type_help") }} -->
            </div>
         </div>
      </div>
      <!-- Party Type Field -->
      <div class="col-md-4">
         <div class="form-group">
            {!! Form::label('type', trans("lang.market_party_type"), ['class' => 'control-label text-right required']) !!}
            <div class="input-group-append">
               {!! Form::select('type', $party_types, null, ['class' => 'select2 form-control type']) !!}
               @if(!isset($market))
               <span class="input-group-text" style="margin-left: -5px;border: none;background: #e3e9ec;border-left: 2px solid #d6d5d5;backface-visibility: visible;z-index: 1;border-radius: 0px 8px 8px 0px;"> <a  href="" data-toggle="modal" data-target="#partyTypeModal"><i class="fa fa-plus mr-1"></i></a></i></span>
               @endif
            </div>
            <div class="form-text text-muted">
               <!-- {{ trans("lang.market_party_type_help") }} -->
            </div>
         </div>
      </div>
      <!-- Party Sub Type Field -->
      <div class="col-md-4">
         <div class="form-group">
            {!! Form::label('sub_type', trans("lang.market_party_sub_type"),['class' => 'control-label text-right required']) !!}
            <div class="input-group-append">
               {!! Form::select('sub_type', $party_sub_types, $partySubTypesSelected, ['class' => 'select2 form-control sub_type','id'=>'sub_type']) !!}
               @if(!isset($market))
               <span class="input-group-text" style="margin-left: -5px;border: none;background: #e3e9ec;border-left: 2px solid #d6d5d5;backface-visibility: visible;z-index: 1;border-radius: 0px 8px 8px 0px;"> <a  href="" data-toggle="modal" data-target="#partySubtypeModal"><i class="fa fa-plus mr-1"></i></a></i></span>
               @endif
            </div>
            <div class="form-text text-muted">
               <!-- {{ trans("lang.market_party_sub_type_help") }} -->
            </div>
         </div>
      </div>
      
      <!-- Customer Group Field -->
      <!-- <div class="col-md-4">
         <div class="form-group">
            {!! Form::label('customer_group_id', trans("lang.customer_group"),['class' => 'control-label text-right']) !!}
            <div class="input-group-append">
               {!! Form::select('customer_group_id', $customer_group, $customerGroupSelected, ['class' => 'select2 form-control']) !!}
               @if(!isset($market))
               <span class="input-group-text" style="margin-left: -5px;border: none;background: #e3e9ec;border-left: 2px solid #d6d5d5;backface-visibility: visible;z-index: 1;border-radius: 0px 8px 8px 0px;"> <a  href="" data-toggle="modal" data-target="#customerGroupModal"><i class="fa fa-plus mr-1"></i></a></i></span>
               @endif
            </div>
         </div>
      </div> -->

      <!-- Customer Levels Field -->
      <div class="col-md-4">
         <div class="form-group">
            {!! Form::label('customer_level_id', 'Customer Level',['class' => 'control-label text-right']) !!}
            <div class="input-group-append">
               {!! Form::select('customer_level_id', $customer_levels, $customerLevelSelected, ['class'=>'select2 form-control customer_level_id']) !!}
            </div>
         </div>
      </div>

      <div class="col-md-4">
         <div class="form-group">
            {!! Form::label('hear_about_us', 'How did you hear about us?', ['class' => ' control-label text-right']) !!}
            {!! Form::text('hear_about_us', null,  ['class' => 'form-control','placeholder'=> 'Example : Facebook ads']) !!}
            <div class="form-text text-muted">
               <!-- {{ trans("lang.market_name_help") }} -->
            </div>
         </div>
      </div>

      <div class="col-md-4">
         <div class="form-group">
            {!! Form::label('verified_by', 'Verified By',['class' => 'control-label text-right']) !!}
            <div class="input-group-append">
               {!! Form::select('verified_by', $users, null, ['class' => 'select2 form-control']) !!}
            </div>
         </div>
      </div>

      <div class="col-md-4">
         <div class="form-group">
            {!! Form::label('party_alert', 'Party Alert',['class' => 'control-label text-right']) !!}
            <div class="input-group-append">
               {!! Form::select('party_alert', ['1'=>'Enable','0'=>'Disable'], 0, ['class' => 'select2 form-control', 'onchange' => 'partyAlert(this);']) !!}
            </div>
         </div>
      </div>

      <div class="col-md-4 alert-type-field" style="display:none">
         <div class="form-group">
            {!! Form::label('party_alert_type', 'Party Alert Type', ['class' => ' control-label text-right']) !!}
            {!! Form::text('party_alert_type', null,  ['class' => 'form-control','placeholder'=> "Insert Party Alert Type"]) !!}
         </div>
      </div>

      <div class="col-md-4 alert-end-date-field" style="display:none">
         <div class="form-group">
            {!! Form::label('party_alert_end_date', 'Party Alert End Date', ['class' => ' control-label text-right']) !!}
            {!! Form::text('party_alert_end_date', null,  ['class' => 'form-control datepicker','placeholder'=> "Insert Party Alert End Date"]) !!}
         </div>
      </div>

      <div class="col-md-4">
         <div class="form-group">
            {!! Form::label('preferred_language', 'Preferred Language',['class' => 'control-label text-right']) !!}
            <div class="input-group-append">
               {!! Form::select('preferred_language', ['english'=>'English','gujarati'=>'Gujarati','hindi'=>'Hindi','punjabi'=>'Punjabi','tamil'=>'Tamil'], 0, ['class' => 'select2 form-control']) !!}
            </div>
         </div>
      </div>

      <div class="col-md-4">
         <div class="form-group">
            {!! Form::label('party_size', 'Party Size', ['class' => ' control-label text-right']) !!}
            {!! Form::text('party_size', null,  ['class' => 'form-control','placeholder'=> '']) !!}
            <div class="form-text text-muted">
               <!-- {{ trans("lang.market_name_help") }} -->
            </div>
         </div>
      </div>

      <div class="col-md-4">
         <div class="form-group">
            {!! Form::label('supply_point', 'Supply Point',['class' => 'control-label text-right required']) !!}
            <div class="input-group-append">
               {!! Form::select('supply_point', $supply_points,null, ['class' => 'select2 form-control stream']) !!}
            </div>
         </div>
      </div>

      <div class="col-md-4">
         <div class="form-group">
            {!! Form::label('membership_type', 'Membership type', ['class' => ' control-label text-right']) !!}
            {!! Form::text('membership_type', null,  ['class' => 'form-control','placeholder'=> '']) !!}
            <div class="form-text text-muted">
               <!-- {{ trans("lang.market_name_help") }} -->
            </div>
         </div>
      </div>

      <div class="col-md-4">
         <div class="form-group">
            {!! Form::label('referred_by', 'Referred By', ['class' => ' control-label text-right']) !!}
            {!! Form::text('referred_by', null,  ['class' => 'form-control','placeholder'=> '']) !!}
            <div class="form-text text-muted">
               <!-- {{ trans("lang.market_name_help") }} -->
            </div>
         </div>
      </div>

      <div class="col-md-12"><hr></div>

      <div class="staff-information col-md-12" style="display:none;">
         <div class="row">

            <div class="col-md-4">
               <div class="form-group">
                  {!! Form::label('staff_designation_id', 'Designation',['class' => 'control-label text-right required']) !!}
                  <div class="input-group-append">
                     {!! Form::select('staff_designation_id', $staff_designations,null, ['class' => 'select2 form-control staff_designation_id']) !!}
                     @if(!isset($market))
                        <span class="input-group-text" style="margin-left: -5px;border: none;background: #e3e9ec;border-left: 2px solid #d6d5d5;backface-visibility: visible;z-index: 1;border-radius: 0px 8px 8px 0px;"> 
                           <a  href="" data-toggle="modal" data-target="#staffDesignationModal"><i class="fa fa-plus mr-1"></i></a>
                        </span>
                     @endif
                  </div>
               </div>
            </div>

            <div class="col-md-4">
               <div class="form-group">
                  {!! Form::label('date_of_joining', 'Date of Joining', ['class' => ' control-label text-right required']) !!}
                  {!! Form::text('date_of_joining', isset($market->date_of_joining) ? $market->date_of_joining->format('d-m-Y') : null,  ['class' => 'form-control datepicker','readonly'=> 'readonly']) !!}
               </div>
            </div>

            <div class="col-md-4">
               <div class="form-group">
                  {!! Form::label('probation_ended_on', 'Probation Ended On', ['class' => ' control-label text-right required']) !!}
                  {!! Form::text('probation_ended_on', isset($market->probation_ended_on) ? $market->probation_ended_on->format('d-m-Y') : null,  ['class' => 'form-control datepicker','readonly'=> 'readonly']) !!}
               </div>
            </div>
            
            <div class="col-md-4">
               <div class="form-group">
                  {!! Form::label('termination_date', 'Termination Date', ['class' => ' control-label text-right']) !!}
                  {!! Form::text('termination_date', isset($market->termination_date) ? $market->termination_date->format('d-m-Y') : null,  ['class' => 'form-control datepicker','readonly'=> 'readonly']) !!}
               </div>
            </div>

            <div class="col-md-4">
               <div class="form-group">
                  {!! Form::label('salary', 'Salary', ['class' => ' control-label text-right']) !!}
                  {!! Form::text('salary', null,  ['class' => 'form-control text-right']) !!}
               </div>
            </div>

            <div class="col-md-4">
               <div class="form-group">
                  {!! Form::label('salary_agreed', 'Salary Agreed', ['class' => ' control-label text-right']) !!}
                  {!! Form::select('salary_agreed', [null=>'Please Select','yes'=>'YES','no'=>'NO'], null, ['class'=>'select2 form-control']) !!}
               </div>
            </div>

            <div class="col-md-12"><hr></div>
         </div>
      </div>

      <div class="col-md-12 mb-4">
         <button type="button" class="btn btn-sm btn-info add-new-address-btn"> Add Additional Address </button>
         <div class="delivery-address-items row mt-4"></div>
      </div>

   </div>
</div>
<div class="col-md-12 column custom-from-css">
   <div class="row">
      <!-- Description Field -->
      <div class="col-md-4">
         {!! Form::label('description', trans("lang.market_description"), ['class' => 'col-3 control-label text-left']) !!}
         <div class="form-group">
            {!! Form::textarea('description', null, ['class' => 'form-control','placeholder'=>
            trans("lang.market_description_placeholder")  ]) !!}
            <!-- <div class="form-text text-muted">{{ trans("lang.market_description_help") }}</div> -->
         </div>
      </div>
      <!-- Image Field -->
      <div class="col-md-4">
         <div class="form-group">
            {!! Form::label('image', trans("lang.market_image"), ['class' => 'col-3 control-label text-left']) !!}
            <div style="width: 100%" class="dropzone image" id="image" data-field="image">
               <input type="hidden" name="image">
            </div>
            <a href="#loadMediaModal" data-dropzone="image" data-toggle="modal" data-target="#mediaModal" class="btn btn-outline-{{setting('theme_color','primary')}} btn-sm float-right mt-1">{{ trans('lang.media_select')}}</a>
            <!-- <div class="form-text text-muted w-50">
               {{ trans("lang.market_image_help") }}
               </div> -->
         </div>
      </div>
      
      <div class="col-md-4">   
         @hasrole('superadmin')
         <div class="col-12 ">
            <!-- <h5 class="col-12 pb-4">{!! trans('lang.admin_area') !!}</h5> -->
            <div class="column" style="margin-top:30px;">
               
               <div class="form-group row ">
                  {!! Form::label('active', trans("lang.market_active"),['class' => 'col-7 control-label text-left']) !!}
                  <div class="checkbox icheck">
                     <label class="col-5 ml-5 form-check-inline">
                     {!! Form::hidden('active', 0) !!}
                     {!! Form::checkbox('active', 1, 1) !!}
                     </label>
                  </div>
               </div>

               <div class="form-group row ">
                  {!! Form::label('email_subscription', "Email Subscription",['class' => 'col-7 control-label text-left']) !!}
                  <div class="checkbox icheck">
                     <label class="col-5 ml-5 form-check-inline">
                     {!! Form::hidden('email_subscription', 0) !!}
                     {!! Form::checkbox('email_subscription', 1, 1) !!}
                     </label>
                  </div>
               </div>

               <div class="form-group row ">
                  {!! Form::label('sms_subscription', "SMS Subscription",['class' => 'col-7 control-label text-left']) !!}
                  <div class="checkbox icheck">
                     <label class="col-5 ml-5 form-check-inline">
                     {!! Form::hidden('sms_subscription', 0) !!}
                     {!! Form::checkbox('sms_subscription', 1, 1) !!}
                     </label>
                  </div>
               </div>

               <div class="form-group row ">
                  {!! Form::label('policy_and_terms', "Terms of service",['class' => 'col-7 control-label text-left']) !!}
                  <div class="checkbox icheck">
                     <label class="col-5 ml-5 form-check-inline">
                     {!! Form::hidden('policy_and_terms', 0) !!}
                     {!! Form::checkbox('policy_and_terms', 1, 1) !!}
                     </label>
                  </div>
               </div>

            </div>
         </div>
         @endhasrole
      </div>   

      </div>
      @prepend('scripts')
      <script type="text/javascript">
         var var15671147011688676454ble = '';
         @if(isset($market) && $market->hasMedia('image'))
             var15671147011688676454ble = {
             name: "{!! $market->getFirstMedia('image')->name !!}",
             size: "{!! $market->getFirstMedia('image')->size !!}",
             type: "{!! $market->getFirstMedia('image')->mime_type !!}",
             collection_name: "{!! $market->getFirstMedia('image')->collection_name !!}"
         };
                 @endif
         var dz_var15671147011688676454ble = $(".dropzone.image").dropzone({
                 url: "{!!url('uploads/store')!!}",
                 addRemoveLinks: true,
                 maxFiles: 1,
                 init: function () {
                     @if(isset($market) && $market->hasMedia('image'))
                     dzInit(this, var15671147011688676454ble, '{!! url($market->getFirstMediaUrl('image','thumb')) !!}')
                     @endif
                 },
                 accept: function (file, done) {
                     dzAccept(file, done, this.element, "{!!config('medialibrary.icons_folder')!!}");
                 },
                 sending: function (file, xhr, formData) {
                     dzSending(this, file, formData, '{!! csrf_token() !!}');
                 },
                 maxfilesexceeded: function (file) {
                     dz_var15671147011688676454ble[0].mockFile = '';
                     dzMaxfile(this, file);
                 },
                 complete: function (file) {
                     dzComplete(this, file, var15671147011688676454ble, dz_var15671147011688676454ble[0].mockFile);
                     dz_var15671147011688676454ble[0].mockFile = file;
                 },
                 removedfile: function (file) {
                     dzRemoveFile(
                         file, var15671147011688676454ble, '{!! url("markets/remove-media") !!}',
                         'image', '{!! isset($market) ? $market->id : 0 !!}', '{!! url("uplaods/clear") !!}', '{!! csrf_token() !!}'
                     );
                 }
             });
         dz_var15671147011688676454ble[0].mockFile = var15671147011688676454ble;
         dropzoneFields['image'] = dz_var15671147011688676454ble;
      </script>
      @endprepend
   </div>
</div>

<!-- Submit Field -->
<div class="form-group col-12 text-right">
   <button type="submit" class="btn btn-{{setting('theme_color')}} party-form-submit"><i class="fa fa-save"></i> Save Party</button>
   <a href="{!! route('markets.index') !!}" class="btn btn-default"><i class="fa fa-undo"></i> Cancel</a>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.0/jquery.min.js"></script>
<script type="text/javascript">
   $(document).ready(function() {
        $('.type').on('change', function(e) {
            var type = e.target.value;
            if(type=='4') {
               $('.staff-information').show();
            } else {
               $('.staff-information').hide();
            }
            $.ajax({
                url: "{{ route('markets.showPartySubTypes') }}",
                type: "POST",
                data: {
                    type: type,
                    "_token": "{{ csrf_token() }}",
                },
                success: function(data) {
                    $('.sub_type').empty();
                    $('.sub_type').append('<option value="">Please Select</option>');
                    $.each(data.party_sub_types[0].party_sub_types, function(index, party_sub_type) {
                        $('.sub_type').append('<option value="' + party_sub_type.id + '">' + party_sub_type.name + '</option>');
                    })
                }
            })
        });
        $('.type').trigger('change');
   });

   /*$('#type').change(function() {
      var type = $(this).val();
      if(type=='4') {
         $('.staff-information').show();
      } else {
         $('.staff-information').hide();
      }
   }) */

   function partyAlert(elem) {
      var type = $(elem).val();
      if(type == 0) {
         $('.alert-type-field').hide();
         $('.alert-end-date-field').hide();
      } else {
         $('.alert-type-field').show();
         $('.alert-end-date-field').show();
      }
   }

   var delivery_address = [];

   function deliveryAddressArrayToTable() {
      var html     = '';
      $(".delivery-address-items").html('');
      var i = 0;
      $.each(delivery_address, function(key,value) { 
         i++;
         if(value.delivery_address_id != undefined && value.delivery_address_id != ''){
             html += '<input type="hidden" name="delivery_address_id[]" value="'+value.delivery_address_id+'" />';   
         }
         html += '<div class="col-md-12">';
         html += '<h5><b>Additional Address '+i+'</b></h5>';
         html += '<a title="Delete" class="delete btn btn-sm btn-danger float-right text-white" index="'+key+'"><i class="fa fa-remove"></i></a>';
         html += '</div>';
         html += '<div class="col-md-2">';
         html += '<div class="form-group">';
         html += '<label for="d_street_no" class="control-label text-right">Street No</label>';
         html += '<input class="form-control change_info" name="d_street_no[]" field="street_no" index="'+key+'" type="text" id="street_no_'+key+'"  value="'+value.street_no+'">';
         html += '</div>';
         html += '</div>';
         html += '<div class="col-md-2">';
         html += '<div class="form-group">';
         html += '<label for="d_street_type" class="control-label text-right">Street Type</label>';
         html += '<input class="form-control change_info" name="d_street_type[]" field="street_type" index="'+key+'" type="text" id="street_no_'+key+'" value="'+value.street_type+'">';
         html += '</div>';
         html += '</div>';
         html += '<div class="col-md-2">';
         html += '<div class="form-group">';
         html += '<label for="d_landmark_1" class="control-label text-right">Landmark 1</label>';
         html += '<input class="form-control change_info" name="d_landmark_1[]" field="landmark_1" index="'+key+'" type="text" id="landmark_1_'+key+'" value="'+value.landmark_1+'">';
         html += '</div>';
         html += '</div>';
         html += '<div class="col-md-2">';
         html += '<div class="form-group">';
         html += '<label for="d_landmark_2" class="control-label text-right">Landmark 2</label>';
         html += '<input class="form-control change_info" name="d_landmark_2[]" field="landmark_2" index="'+key+'" type="text" id="landmark_2_'+key+'" value="'+value.landmark_2+'">';
         html += '</div>';
         html += '</div>';
         html += '<div class="col-md-2">';
         html += '<div class="form-group">';
         html += '<label for="d_address_line_1" class="control-label text-right">Address Line 1</label>';
         html += '<input class="form-control change_info" name="d_address_line_1[]" field="address_line_1" index="'+key+'" type="text" id="address_line_1_'+key+'" value="'+value.address_line_1+'">';
         html += '</div>';
         html += '</div>';
         html += '<div class="col-md-2">';
         html += '<div class="form-group">';
         html += '<label for="d_address_line_2" class="control-label text-right">Address Line 2</label>';
         html += '<input class="form-control change_info" name="d_address_line_2[]" field="address_line_2" index="'+key+'" type="text" id="address_line_2_'+key+'" value="'+value.address_line_2+'">';
         html += '</div>';
         html += '</div>';
         html += '<div class="col-md-2">';
         html += '<div class="form-group">';
         html += '<label for="d_town" class="control-label text-right">Town</label>';
         html += '<input class="form-control change_info" name="d_town[]" field="town" index="'+key+'" type="text" id="town_'+key+'" value="'+value.town+'">';
         html += '</div>';
         html += '</div>';
         html += '<div class="col-md-2">';
         html += '<div class="form-group">';
         html += '<label for="d_city" class="control-label text-right">City</label>';
         html += '<input class="form-control change_info" name="d_city[]" field="city" index="'+key+'" type="text" id="city_'+key+'" value="'+value.city+'">';
         html += '</div>';
         html += '</div>';
         html += '<div class="col-md-2">';
         html += '<div class="form-group">';
         html += '<label for="d_state" class="control-label text-right">State</label>';
         html += '<input class="form-control change_info" name="d_state[]" field="state" index="'+key+'" type="text" id="state_'+key+'" value="'+value.state+'">';
         html += '</div>';
         html += '</div>';
         html += '<div class="col-md-2">';
         html += '<div class="form-group">';
         html += '<label for="d_pincode" class="control-label text-right">Pincode</label>';
         html += '<input class="form-control change_info" name="d_pincode[]" field="pincode" index="'+key+'" type="text" id="pincode_'+key+'" value="'+value.pincode+'">';
         html += '</div>';
         html += '</div>';
         html += '<div class="col-md-2">';
         html += '<div class="form-group">';
         html += '<label for="d_latitude" class="control-label text-right">Latitude</label>';
         html += '<input class="form-control change_info" name="d_latitude[]" field="latitude" index="'+key+'" type="text" id="latitude_'+key+'" value="'+value.latitude+'">';
         html += '</div>';
         html += '</div>';
         html += '<div class="col-md-2">';
         html += '<div class="form-group">';
         html += '<label for="d_longitude" class="control-label text-right">Longitude</label>';
         html += '<input class="form-control change_info" name="d_longitude[]" field="longitude" index="'+key+'" type="text" id="longitude_'+key+'" value="'+value.longitude+'">';
         html += '</div>';
         html += '</div><hr>';
      });
      $(".delivery-address-items").html(html);
   }


   $('.add-new-address-btn').click(function() {
         var delivery_address_obj = {};
         delivery_address_obj.user_id = '';
         delivery_address_obj.street_no = '';
         delivery_address_obj.street_type = '';
         delivery_address_obj.landmark_1 = '';
         delivery_address_obj.landmark_2 = '';
         delivery_address_obj.address_line_1 = '';
         delivery_address_obj.address_line_2 = '';
         delivery_address_obj.town = '';
         delivery_address_obj.city = '';
         delivery_address_obj.state = '';
         delivery_address_obj.pincode = '';
         delivery_address_obj.latitude = '';
         delivery_address_obj.longitude = '';
         delivery_address_obj.notes = '';
         delivery_address.push(delivery_address_obj);
         deliveryAddressArrayToTable();
   });

   $(document).on('blur','.change_info',function (e) { 
        var field = $(this).attr('field');
        var index = $(this).attr('index');
        var val = $(this).val();
        
        if(field == 'street_no'){
            if(val != undefined && val!=''){
                delivery_address[index].street_no = val;
                $("#street_no_" + index).val(val);
            } else {
                delivery_address[index].street_no = '';
                $("#street_no_" + index).val('');
            }
        } else if(field == 'street_type'){
            if(val != undefined && val!=''){
                delivery_address[index].street_type = val;
                $("#street_type_" + index).val(val);
            } else {
                delivery_address[index].street_type = '';
                $("#street_type_" + index).val('');
            }
        } else if(field == 'landmark_1'){
            if(val != undefined && val!=''){
                delivery_address[index].landmark_1 = val;
                $("#landmark_1_" + index).val(val);
            } else {
                delivery_address[index].landmark_1 = '';
                $("#landmark_1_" + index).val('');
            }
        } else if(field == 'landmark_2'){
            if(val != undefined && val!=''){
                delivery_address[index].landmark_2 = val;
                $("#landmark_2_" + index).val(val);
            } else {
                delivery_address[index].landmark_2 = '';
                $("#landmark_2_" + index).val('');
            }
        } else if(field == 'address_line_1'){
            if(val != undefined && val!=''){
                delivery_address[index].address_line_1 = val;
                $("#address_line_1_" + index).val(val);
            } else {
                delivery_address[index].address_line_1 = '';
                $("#address_line_1_" + index).val('');
            }
        } else if(field == 'address_line_2'){
            if(val != undefined && val!=''){
                delivery_address[index].address_line_2 = val;
                $("#address_line_2_" + index).val(val);
            } else {
                delivery_address[index].address_line_2 = '';
                $("#address_line_2_" + index).val('');
            }
        } else if(field == 'town'){
            if(val != undefined && val!=''){
                delivery_address[index].town = val;
                $("#town_" + index).val(val);
            } else {
                delivery_address[index].town = '';
                $("#town_" + index).val('');
            }
        } else if(field == 'city'){
            if(val != undefined && val!=''){
                delivery_address[index].city = val;
                $("#city_" + index).val(val);
            } else {
                delivery_address[index].city = '';
                $("#city_" + index).val('');
            }
        } else if(field == 'state'){
            if(val != undefined && val!=''){
                delivery_address[index].state = val;
                $("#state_" + index).val(val);
            } else {
                delivery_address[index].state = '';
                $("#state_" + index).val('');
            }
        } else if(field == 'pincode'){
            if(val != undefined && val!=''){
                delivery_address[index].pincode = val;
                $("#pincode_" + index).val(val);
            } else {
                delivery_address[index].pincode = '';
                $("#pincode_" + index).val('');
            }
        } else if(field == 'latitude'){
            if(val != undefined && val!=''){
                delivery_address[index].latitude = val;
                $("#latitude_" + index).val(val);
            } else {
                delivery_address[index].latitude = '';
                $("#latitude_" + index).val('');
            }
        } else if(field == 'longitude'){
            if(val != undefined && val!=''){
                delivery_address[index].longitude = val;
                $("#longitude_" + index).val(val);
            } else {
                delivery_address[index].longitude = '';
                $("#longitude_" + index).val('');
            }
        } 

        setTimeout(function() {
            deliveryAddressArrayToTable();
        },300);
        e.preventDefault();
    });

   $(document).on('click', '.delete', function(e){
        var index = $(this).attr('index');
        iziToast.show({
            theme: 'dark',
            icon: 'fa fa-trash',
            overlay: true,
            title: 'Delete',
            message: 'Are you sure?',
            position: 'center', // bottomRight, bottomLeft, topRight, topLeft, topCenter, bottomCenter
            progressBarColor: 'yellow',
            backgroundColor: 'linear-gradient(to right, #ff4b1f, #ff9068)', 
            messageColor: '#fff', 
            buttons: [ 
                 ['<button>Yes</button>', function (instance, toast) {

                  if(delivery_address[index].delivery_address_id == undefined || delivery_address[index].delivery_address_id == ''){
                     
                     instance.hide({transitionOut: 'fadeOutUp'}, toast, 'buttonName');
                     delivery_address.splice(index,1);
                     deliveryAddressArrayToTable();

                  } else {

                     var token = "{{ csrf_token() }}";
                     var url   = '{!!  route('deliveryAddresses.destroy', [':itemID']) !!}';
                     url       = url.replace(':itemID', delivery_address[index].delivery_address_id);
                     $.ajax({
                         type: 'POST',
                         url: url,
                         data: {'_token': token, 'id': delivery_address[index].delivery_address_id, '_method': 'DELETE'},
                         success: function (response) {
                             iziToast.success({
                                 backgroundColor: 'linear-gradient(to right, #44a08d, #093637)',
                                 messageColor: '#fff',
                                 timeout: 3000, 
                                 icon: 'fa fa-check', 
                                 position: "topRight", 
                                 iconColor:'#fff',
                                 message: 'Deleted Sucessfully'
                             });
                             
                             instance.hide({transitionOut: 'fadeOutUp'}, toast, 'buttonName');
                             delivery_address.splice(index,1);
                             deliveryAddressArrayToTable();
                         }
                     });

                  }

                 }, true], // true to focus
                 ['<button>No</button>', function (instance, toast) {
                    instance.hide({
                        transitionOut: 'fadeOutUp',
                        onClosing: function(instance, toast, closedBy){
                             console.info('closedBy: ' + closedBy); // The return will be: 'closedBy: buttonName'
                        }
                    }, toast, 'buttonName');
                 }]
            ]
        });
   });

   @if(isset($market))

        $(document).ready(function() {

            var url   = '{!!  route('markets.view', [':marketID']) !!}';
            url       = url.replace(':marketID', "{{$market->id}}");
            var token = "{{ csrf_token() }}";
            $.ajax({
                 type: 'GET',
                 data: {
                     '_token': token,
                     'id': "{{$market->id}}"
                 },
                 url: url,
                 success: function (response) {
                   
                    if(response.data.user.deliveryaddress.length > 0){
                        $.each(response.data.user.deliveryaddress, function(key,value) {

                           var delivery_address_obj = {};
                           delivery_address_obj = value;
                           delivery_address_obj.delivery_address_id = value.id;
                           delivery_address.push(delivery_address_obj);
                           deliveryAddressArrayToTable();

                        });
                    }

                 }
            });        

        });

   @endif

</script>