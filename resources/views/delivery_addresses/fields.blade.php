@if($customFields)
<h5 class="col-12 pb-4">{!! trans('lang.main_fields') !!}</h5>
@endif
<div class="col-md-12 column custom-from-css">
    <div class="row">
    
    <!-- Description Field -->
    <div class="form-group col-md-4 ">
        {!! Form::label('description', trans("lang.delivery_address_description"), ['class' => ' control-label text-right']) !!}
        {!! Form::text('description', null,  ['class' => 'form-control','placeholder'=>  trans("lang.delivery_address_description_placeholder")]) !!}
        <!--<div class="form-text text-muted">-->
        <!--  {{ trans("lang.delivery_address_description_help") }}-->
        <!--</div>-->
    </div>
    
    <!-- Address Field -->
    <div class="form-group col-md-4 ">
        {!! Form::label('address', trans("lang.delivery_address_address"), ['class' => ' control-label text-right']) !!}
        {!! Form::text('address', null,  ['class' => 'form-control','placeholder'=>  trans("lang.delivery_address_address_placeholder")]) !!}
        <!--<div class="form-text text-muted">-->
        <!--  {{ trans("lang.delivery_address_address_help") }}-->
        <!--</div>-->
    </div>
    
    
    <!-- Address Line 1 Field -->
    <div class="form-group col-md-4 ">
        {!! Form::label('address_line_1', trans("lang.market_address_line_1"), ['class' => ' control-label text-right']) !!}
        {!! Form::text('address_line_1', null,  ['class' => 'form-control','placeholder'=>  trans("lang.market_address_line_1_placeholder")]) !!}
        <!--<div class="form-text text-muted">-->
        <!--  {{ trans("lang.market_address_line_1_help") }}-->
        <!--</div>-->
    </div>
    
    <!-- Address Line 2 Field -->
    <div class="form-group col-md-4 ">
        {!! Form::label('address_line_2', trans("lang.market_address_line_2"), ['class' => ' control-label text-right']) !!}
        {!! Form::text('address_line_2', null,  ['class' => 'form-control','placeholder'=>  trans("lang.market_address_line_2_placeholder")]) !!}
        <!--<div class="form-text text-muted">-->
        <!--  {{ trans("lang.market_address_line_1_help") }}-->
        <!--</div>-->
    </div>
    
    <!-- City Field -->
    <div class="form-group col-md-4 ">
        {!! Form::label('city', trans("lang.market_city"), ['class' => ' control-label text-right']) !!}
        {!! Form::text('city', null,  ['class' => 'form-control','placeholder'=>  trans("lang.market_city_placeholder")]) !!}
        <!--<div class="form-text text-muted">-->
        <!--  {{ trans("lang.market_city_help") }}-->
        <!--</div>-->
    </div>
    
    <!-- City Field -->
    <div class="form-group col-md-4 ">
        {!! Form::label('state', trans("lang.market_state"), ['class' => ' control-label text-right']) !!}
        {!! Form::text('state', null,  ['class' => 'form-control','placeholder'=>  trans("lang.market_state_placeholder")]) !!}
        <!--<div class="form-text text-muted">-->
        <!--  {{ trans("lang.market_state_help") }}-->
        <!--</div>-->
    </div>
    
    <!-- Pincode Field -->
    <div class="form-group col-md-4 ">
        {!! Form::label('pincode', trans("lang.market_pincode"), ['class' => ' control-label text-right']) !!}
        {!! Form::text('pincode', null,  ['class' => 'form-control','placeholder'=>  trans("lang.market_pincode_placeholder")]) !!}
        <!--<div class="form-text text-muted">-->
        <!--  {{ trans("lang.market_pincode_help") }}-->
        <!--</div>-->
    </div>
    
    <!-- Latitude Field -->
    <div class="form-group col-md-4 ">
        {!! Form::label('latitude', trans("lang.delivery_address_latitude"), ['class' => ' control-label text-right']) !!}
        {!! Form::text('latitude', null,  ['class' => 'form-control','placeholder'=>  trans("lang.delivery_address_latitude_placeholder")]) !!}
        <!--<div class="form-text text-muted">-->
        <!--  {{ trans("lang.delivery_address_latitude_help") }}-->
        <!--</div>-->
    </div>
    
    <!-- Longitude Field -->
    <div class="form-group col-md-4 ">
        {!! Form::label('longitude', trans("lang.delivery_address_longitude"), ['class' => ' control-label text-right']) !!}
        {!! Form::text('longitude', null,  ['class' => 'form-control','placeholder'=>  trans("lang.delivery_address_longitude_placeholder")]) !!}
        <!--<div class="form-text text-muted">-->
        <!--  {{ trans("lang.delivery_address_longitude_help") }}-->
        <!--</div>-->
    </div
    
    <!-- User Id Field -->
    <div class="form-group col-md-4 ">
        {!! Form::label('user_id', trans("lang.delivery_address_user_id"),['class' => ' control-label text-right']) !!}
        {!! Form::select('user_id', $user, null, ['class' => 'select2 form-control']) !!}
        <!--<div class="form-text text-muted">{{ trans("lang.delivery_address_user_id_help") }}</div>-->
    </div>
    
    <!-- 'Boolean Is Default Field' -->
    <div class="form-group col-md-4 checkbox-cust1">
      <div class="row">
      {!! Form::label('is_default', trans("lang.delivery_address_is_default"),['class' => 'col-md-2 control-label text-left']) !!}
      <div class="col-md-10 checkbox icheck">
        <label class=" ml-2 form-check-inline">
          {!! Form::hidden('is_default', 0) !!}
          {!! Form::checkbox('is_default', 1, null) !!}
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
<!-- Submit Field -->
<div class="form-group col-12 text-right">
  <button type="submit" class="btn btn-{{setting('theme_color')}}" ><i class="fa fa-save"></i> {{trans('lang.save')}} {{trans('lang.delivery_address')}}</button>
  <a href="{!! route('deliveryAddresses.index') !!}" class="btn btn-default"><i class="fa fa-undo"></i> {{trans('lang.cancel')}}</a>
</div>
