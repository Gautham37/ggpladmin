@if($customFields)
    <h5 class="col-12 pb-4">{!! trans('lang.main_fields') !!}</h5>
@endif
<script src="https://maps.googleapis.com/maps/api/js?key={{ setting('google_api_key') }}&libraries=places&callback=initAutocomplete" async defer></script>
<div style="flex: 90%;max-width: 90%;padding: 30px  4px;" class="column">
    <!-- Name Field -->
    <div class="form-group row ">
        {!! Form::label('name', trans("lang.market_name"), ['class' => 'col-3 control-label text-right']) !!}
        <div class="col-9">
            {!! Form::text('name', null,  ['class' => 'form-control','placeholder'=>  trans("lang.market_name_placeholder"), 'required' => 'required']) !!}
            <div class="form-text text-muted">
                {{ trans("lang.market_name_help") }}
            </div>
        </div>
    </div>
    
    <!-- Customer Group Field -->
    <div class="form-group row ">
        {!! Form::label('customer_group', trans("lang.customer_group"),['class' => 'col-3 control-label text-right']) !!}
        <div class="col-9">
            {!! Form::select('customer_group', $customer_group, $customerGroupSelected, ['class' => 'select2 form-control', 'required' => 'required']) !!}
            <div class="form-text text-muted">{{ trans("lang.customer_group_help") }}</div>
        </div>
    </div>
    
    <?php /* ?>
    <!-- fields Field -->
    <div class="form-group row ">
        {!! Form::label('fields[]', trans("lang.market_fields"),['class' => 'col-3 control-label text-right']) !!}
        <div class="col-9">
            {!! Form::select('fields[]', $field, $fieldsSelected, ['class' => 'select2 form-control' , 'multiple'=>'multiple']) !!}
            <div class="form-text text-muted">{{ trans("lang.market_fields_help") }}</div>
        </div>
    </div>

    @hasanyrole('admin|manager')
    <!-- Users Field -->
    <div class="form-group row ">
        {!! Form::label('drivers[]', trans("lang.market_drivers"),['class' => 'col-3 control-label text-right']) !!}
        <div class="col-9">
            {!! Form::select('drivers[]', $drivers, $driversSelected, ['class' => 'select2 form-control' , 'multiple'=>'multiple']) !!}
            <div class="form-text text-muted">{{ trans("lang.market_drivers_help") }}</div>
        </div>
    </div>
    <!-- delivery_fee Field -->
    <div class="form-group row ">
        {!! Form::label('delivery_fee', trans("lang.market_delivery_fee"), ['class' => 'col-3 control-label text-right']) !!}
        <div class="col-9">
            {!! Form::number('delivery_fee', null,  ['class' => 'form-control','step'=>'any','placeholder'=>  trans("lang.market_delivery_fee_placeholder")]) !!}
            <div class="form-text text-muted">
                {{ trans("lang.market_delivery_fee_help") }}
            </div>
        </div>
    </div>

    <!-- delivery_range Field -->
    <div class="form-group row ">
        {!! Form::label('delivery_range', trans("lang.market_delivery_range"), ['class' => 'col-3 control-label text-right']) !!}
        <div class="col-9">
            {!! Form::number('delivery_range', null,  ['class' => 'form-control', 'step'=>'any','placeholder'=>  trans("lang.market_delivery_range_placeholder")]) !!}
            <div class="form-text text-muted">
                {{ trans("lang.market_delivery_range_help") }}
            </div>
        </div>
    </div>

    <!-- default_tax Field -->
    <div class="form-group row ">
        {!! Form::label('default_tax', trans("lang.market_default_tax"), ['class' => 'col-3 control-label text-right']) !!}
        <div class="col-9">
            {!! Form::number('default_tax', null,  ['class' => 'form-control', 'step'=>'any','placeholder'=>  trans("lang.market_default_tax_placeholder")]) !!}
            <div class="form-text text-muted">
                {{ trans("lang.market_default_tax_help") }}
            </div>
        </div>
    </div>

    @endhasanyrole

    <?php /*/ ?>

    <!-- Email Field -->
    <div class="form-group row ">
        {!! Form::label('email', trans("lang.market_email"), ['class' => 'col-3 control-label text-right']) !!}
        <div class="col-9">
            {!! Form::email('email', null,  ['class' => 'form-control','placeholder'=>  trans("lang.market_email_placeholder"), 'required' => 'required']) !!}
            <div class="form-text text-muted">
                {{ trans("lang.market_email_help") }}
            </div>
        </div>
    </div>

    <!-- Phone Field -->
    <div class="form-group row ">
        {!! Form::label('phone', trans("lang.market_phone"), ['class' => 'col-3 control-label text-right']) !!}
        <div class="col-9">
            {!! Form::text('phone', null,  ['class' => 'form-control','placeholder'=>  trans("lang.market_phone_placeholder")]) !!}
            <div class="form-text text-muted">
                {{ trans("lang.market_phone_help") }}
            </div>
        </div>
    </div>

    <!-- Mobile Field -->
    <div class="form-group row ">
        {!! Form::label('mobile', trans("lang.market_mobile"), ['class' => 'col-3 control-label text-right']) !!}
        <div class="col-9">
            {!! Form::text('mobile', null,  ['class' => 'form-control','placeholder'=>  trans("lang.market_mobile_placeholder"), 'required' => 'required']) !!}
            <div class="form-text text-muted">
                {{ trans("lang.market_mobile_help") }}
            </div>
        </div>
    </div>
    
    <!-- Gender Field -->
    <div class="form-group row ">
        {!! Form::label('gender', trans("lang.user_gender"), ['class' => 'col-3 control-label text-right']) !!}
        <div class="col-9">
            {!! Form::select('gender', [null => 'Please Select', 'male' => 'Male', 'female' => 'Female', 'others' => 'Others'], null, ['class' => 'select2 form-control']) !!}
            <div class="form-text text-muted">
                {{ trans("lang.user_gender_help") }}
            </div>
        </div>
    </div>
    
    <!-- Date of birth Field -->
    <div class="form-group row ">
        {!! Form::label('date_of_birth', trans("lang.date_of_birth"), ['class' => 'col-3 control-label text-right']) !!}
        <div class="col-9">
            {!! Form::date('date_of_birth', null,  ['class' => 'form-control datepicker','placeholder'=>  trans("lang.user_date_of_birth_placeholder")]) !!}
            <div class="form-text text-muted">
                {{ trans("lang.user_date_of_birth_help") }}
            </div>
        </div>
    </div>
    
    <hr>
    <!-- Address Field -->
    <div class="form-group row ">
        {!! Form::label('address_line_1', trans("lang.market_address_line_1"), ['class' => 'col-3 control-label text-right']) !!}
        <div class="col-9">
            {!! Form::text('address_line_1', null,  ['class' => 'form-control','placeholder'=>  trans("lang.market_address_line_1_placeholder"), 'required' => 'required']) !!}
            <div class="form-text text-muted">
                {{ trans("lang.market_address_line_1_help") }}
            </div>
        </div>
    </div>
    
    <!-- Address Field -->
    <div class="form-group row ">
        {!! Form::label('address_line_2', trans("lang.market_address_line_2"), ['class' => 'col-3 control-label text-right']) !!}
        <div class="col-9">
            {!! Form::text('address_line_2', null,  ['class' => 'form-control','placeholder'=>  trans("lang.market_address_line_2_placeholder")]) !!}
            <div class="form-text text-muted">
                {{ trans("lang.market_address_line_2_help") }}
            </div>
        </div>
    </div>
    
    <!-- City Field -->
    <div class="form-group row ">
        {!! Form::label('city', trans("lang.market_city"), ['class' => 'col-3 control-label text-right']) !!}
        <div class="col-9">
            {!! Form::text('city', null,  ['class' => 'form-control','placeholder'=>  trans("lang.market_city_placeholder"), 'required' => 'required']) !!}
            <div class="form-text text-muted">
                {{ trans("lang.market_city_help") }}
            </div>
        </div>
    </div>
    
    <!-- State Field -->
    <div class="form-group row ">
        {!! Form::label('state', trans("lang.market_state"), ['class' => 'col-3 control-label text-right']) !!}
        <div class="col-9">
            {!! Form::text('state', null,  ['class' => 'form-control','placeholder'=>  trans("lang.market_state_placeholder"), 'required' => 'required']) !!}
            <div class="form-text text-muted">
                {{ trans("lang.market_state_help") }}
            </div>
        </div>
    </div>
    
    <!-- Pincode Field -->
    <div class="form-group row ">
        {!! Form::label('pincode', trans("lang.market_pincode"), ['class' => 'col-3 control-label text-right']) !!}
        <div class="col-9">
            {!! Form::text('pincode', null,  ['class' => 'form-control','placeholder'=>  trans("lang.market_pincode_placeholder"), 'required' => 'required']) !!}
            <div class="form-text text-muted">
                {{ trans("lang.market_pincode_help") }}
            </div>
        </div>
    </div>
    
    <!-- Latitude Field -->
    <div class="form-group row ">
        {!! Form::label('latitude', trans("lang.market_latitude"), ['class' => 'col-3 control-label text-right']) !!}
        <div class="col-9">
            {!! Form::text('latitude', null,  ['class' => 'form-control','placeholder'=>  trans("lang.market_latitude_placeholder")]) !!}
            <div class="form-text text-muted">
                {{ trans("lang.market_latitude_help") }}
            </div>
        </div>
    </div>

    <!-- Longitude Field -->
    <div class="form-group row ">
        {!! Form::label('longitude', trans("lang.market_longitude"), ['class' => 'col-3 control-label text-right']) !!}
        <div class="col-9">
            {!! Form::text('longitude', null,  ['class' => 'form-control','placeholder'=>  trans("lang.market_longitude_placeholder")]) !!}
            <div class="form-text text-muted">
                {{ trans("lang.market_longitude_help") }}
            </div>
        </div>
    </div>
    
    <div class="form-group row ">
        {!! Form::label('active', trans("lang.market_active"),['class' => 'col-3 control-label text-right']) !!}
        <div class="checkbox icheck">
            <label class="col-9 ml-2 form-check-inline">
                {!! Form::hidden('active', 0) !!}
                {!! Form::checkbox('active', 1, 1) !!}
            </label>
        </div>
    </div>
        
    <?php /* ?>
    <hr>
    <!-- Party Type Field -->
    <div class="form-group row ">
        {!! Form::label('type', trans("lang.market_party_type"), ['class' => 'col-3 control-label text-right']) !!}
        <div class="col-9">
            {!! Form::select('type', ['1'=> 'Customer', '2' => 'Supplier', '3' => 'Farmer'], null, ['class' => 'select2 form-control']) !!}
            <div class="form-text text-muted">
                {{ trans("lang.market_party_type_help") }}
            </div>
        </div>
    </div>

    <!-- GSTIN Field -->
    <div class="form-group row ">
        {!! Form::label('gstin', trans("lang.market_gstin"), ['class' => 'col-3 control-label text-right']) !!}
        <div class="col-9">
            {!! Form::text('gstin', null,  ['class' => 'form-control','placeholder'=>  trans("lang.market_gstin_placeholder")]) !!}
            <div class="form-text text-muted">
                {{ trans("lang.market_gstin_help") }}
            </div>
        </div>
    </div>

    
    
    <!-- 'Boolean closed Field' -->
    <div class="form-group row ">
        {!! Form::label('closed', trans("lang.market_closed"),['class' => 'col-3 control-label text-right']) !!}
        <div class="checkbox icheck">
            <label class="col-9 ml-2 form-check-inline">
                {!! Form::hidden('closed', 0) !!}
                {!! Form::checkbox('closed', 1, null) !!}
            </label>
        </div>
    </div>

    <!-- 'Boolean available_for_delivery Field' -->
    <div class="form-group row ">
        {!! Form::label('available_for_delivery', trans("lang.market_available_for_delivery"),['class' => 'col-3 control-label text-right']) !!}
        <div class="checkbox icheck">
            <label class="col-9 ml-2 form-check-inline">
                {!! Form::hidden('available_for_delivery', 0) !!}
                {!! Form::checkbox('available_for_delivery', 1, null) !!}
            </label>
        </div>
    </div>
    

    <!-- Customer Group Field -->
    <div class="form-group row ">
        {!! Form::label('customer_group', trans("lang.customer_group"),['class' => 'col-3 control-label text-right']) !!}
        <div class="col-9">
            {!! Form::select('customer_group', $customer_group, $customerGroupSelected, ['class' => 'select2 form-control']) !!}
            <div class="form-text text-muted">{{ trans("lang.customer_group_help") }}</div>
        </div>
    </div>
    <?php /*/ ?>
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

