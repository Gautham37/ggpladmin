@if($customFields)
    <h5 class="col-12 pb-4">{!! trans('lang.main_fields') !!}</h5>
@endif
<script src="https://maps.googleapis.com/maps/api/js?key={{ setting('google_api_key') }}&libraries=places&callback=initAutocomplete" async defer></script>
<div class="col-md-12 column custom-from-css">
    <div class="row">

    <!-- Name Field -->
    <div class="form-group col-md-6 ">
        {!! Form::label('name', trans("lang.market_name"), ['class' => ' control-label text-right']) !!}
        {!! Form::text('name', null,  ['class' => 'form-control','placeholder'=>  trans("lang.market_name_placeholder"), 'required' => 'required']) !!}
        <!-- <div class="form-text text-muted">
            {{ trans("lang.market_name_help") }}
        </div> -->
    </div>
    
    <!-- Customer Group Field -->
    <div class="form-group col-md-6 ">
        {!! Form::label('customer_group', trans("lang.customer_group"),['class' => ' control-label text-right']) !!}
        {!! Form::select('customer_group', $customer_group, $customerGroupSelected, ['class' => 'model-select select2 form-control', 'required' => 'required']) !!}
        <!-- <div class="form-text text-muted">{{ trans("lang.customer_group_help") }}</div> -->
    </div>

    <!-- Email Field -->
    <div class="form-group col-md-6 ">
        {!! Form::label('email', trans("lang.market_email"), ['class' => ' control-label text-right']) !!}
        {!! Form::email('email', null,  ['class' => 'form-control','placeholder'=>  trans("lang.market_email_placeholder"), 'required' => 'required']) !!}
        <!-- <div class="form-text text-muted">
            {{ trans("lang.market_email_help") }}
        </div> -->
    </div>

    <!-- Phone Field -->
    <div class="form-group col-md-6 ">
        {!! Form::label('phone', trans("lang.market_phone"), ['class' => ' control-label text-right']) !!}
        {!! Form::text('phone', null,  ['class' => 'form-control','placeholder'=>  trans("lang.market_phone_placeholder")]) !!}
        <!-- <div class="form-text text-muted">
            {{ trans("lang.market_phone_help") }}
        </div> -->
    </div>

    <!-- Mobile Field -->
    <div class="form-group col-md-6 ">
        {!! Form::label('mobile', trans("lang.market_mobile"), ['class' => ' control-label text-right']) !!}
        {!! Form::text('mobile', null,  ['class' => 'form-control','placeholder'=>  trans("lang.market_mobile_placeholder"), 'required' => 'required']) !!}
        <!-- <div class="form-text text-muted">
            {{ trans("lang.market_mobile_help") }}
        </div> -->
    </div>
    
    <!-- Gender Field -->
    <div class="form-group col-md-6 ">
        {!! Form::label('gender', trans("lang.user_gender"), ['class' => ' control-label text-right']) !!}
        {!! Form::select('gender', [null => 'Please Select', 'male' => 'Male', 'female' => 'Female', 'others' => 'Others'], null, ['class' => 'model-select select2 form-control']) !!}
        <!-- <div class="form-text text-muted">
            {{ trans("lang.user_gender_help") }}
        </div> -->
    </div>
    
    <!-- Date of birth Field -->
    <div class="form-group col-md-6 ">
        {!! Form::label('date_of_birth', trans("lang.date_of_birth"), ['class' => ' control-label text-right']) !!}
        {!! Form::date('date_of_birth', null,  ['class' => 'form-control datepicker','placeholder'=>  trans("lang.user_date_of_birth_placeholder")]) !!}
        <!-- <div class="form-text text-muted">
            {{ trans("lang.user_date_of_birth_help") }}
        </div> -->
    </div>
    
    <hr>
    <!-- Address Field -->
    <div class="form-group col-md-6 ">
        {!! Form::label('address_line_1', trans("lang.market_address_line_1"), ['class' => ' control-label text-right']) !!}
        {!! Form::text('address_line_1', null,  ['class' => 'form-control','placeholder'=>  trans("lang.market_address_line_1_placeholder"), 'required' => 'required']) !!}
        <!-- <div class="form-text text-muted">
            {{ trans("lang.market_address_line_1_help") }}
        </div> -->
    </div>
    
    <!-- Address Field -->
    <div class="form-group col-md-6 ">
        {!! Form::label('address_line_2', trans("lang.market_address_line_2"), ['class' => ' control-label text-right']) !!}
        {!! Form::text('address_line_2', null,  ['class' => 'form-control','placeholder'=>  trans("lang.market_address_line_2_placeholder")]) !!}
        <!-- <div class="form-text text-muted">
            {{ trans("lang.market_address_line_2_help") }}
        </div> -->
    </div>
    
    <!-- City Field -->
    <div class="form-group col-md-6 ">
        {!! Form::label('city', trans("lang.market_city"), ['class' => ' control-label text-right']) !!}
        {!! Form::text('city', null,  ['class' => 'form-control','placeholder'=>  trans("lang.market_city_placeholder"), 'required' => 'required']) !!}
        <!-- <div class="form-text text-muted">
            {{ trans("lang.market_city_help") }}
        </div> -->
    </div>
    
    <!-- State Field -->
    <div class="form-group col-md-6 ">
        {!! Form::label('state', trans("lang.market_state"), ['class' => ' control-label text-right']) !!}
        {!! Form::text('state', null,  ['class' => 'form-control','placeholder'=>  trans("lang.market_state_placeholder"), 'required' => 'required']) !!}
        <!-- <div class="form-text text-muted">
            {{ trans("lang.market_state_help") }}
        </div> -->
    </div>
    
    <!-- Pincode Field -->
    <div class="form-group col-md-6 ">
        {!! Form::label('pincode', trans("lang.market_pincode"), ['class' => ' control-label text-right']) !!}
        {!! Form::text('pincode', null,  ['class' => 'form-control','placeholder'=>  trans("lang.market_pincode_placeholder"), 'required' => 'required']) !!}
        <!-- <div class="form-text text-muted">
            {{ trans("lang.market_pincode_help") }}
        </div> -->
    </div>
    
    <!-- Latitude Field -->
    <div class="form-group col-md-6 ">
        {!! Form::label('latitude', trans("lang.market_latitude"), ['class' => ' control-label text-right']) !!}
        {!! Form::text('latitude', null,  ['class' => 'form-control','placeholder'=>  trans("lang.market_latitude_placeholder")]) !!}
        <!-- <div class="form-text text-muted">
            {{ trans("lang.market_latitude_help") }}
        </div> -->
    </div>

    <!-- Longitude Field -->
    <div class="form-group col-md-6 ">
        {!! Form::label('longitude', trans("lang.market_longitude"), ['class' => ' control-label text-right']) !!}
        {!! Form::text('longitude', null,  ['class' => 'form-control','placeholder'=>  trans("lang.market_longitude_placeholder")]) !!}
        <!-- <div class="form-text text-muted">
            {{ trans("lang.market_longitude_help") }}
        </div> -->
    </div>
    
    <div class="form-group col-md-6 custom-checkbx">
        <div class="row"> 
        {!! Form::label('active', trans("lang.market_active"),['class' => 'col-md-6 control-label text-right']) !!}
        <div class="col-md-6 checkbox icheck">
            <label class=" ml-2 form-check-inline">
                {!! Form::hidden('active', 0) !!}
                {!! Form::checkbox('active', 1, 1) !!}
            </label>
        </div>
        </div>
    </div>

   </div>     
</div>

@if($customFields)
    <div class="clearfix"></div>
    <div class="col-12 custom-field-container">
        <h5 class="col-12 pb-4">{!! trans('lang.custom_field_plural') !!}</h5>
        {!! $customFields !!}
    </div>
@endif

<div class="modal-footer">
    <button type="submit" class="btn btn-primary btn-sm add-party">{{trans('lang.save')}} {{trans('lang.market')}}</button>
    <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Cancel</button>
</div>

