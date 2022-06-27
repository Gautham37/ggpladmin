@if($customFields)
<h5 class="col-12 pb-4">{!! trans('lang.main_fields') !!}</h5>
@endif
<div class="col-md-12 column custom-from-css">
  <div class="row">
    <div class="col-md-4">

      <!-- Code Field -->
      <div class="form-group ">
        {!! Form::label('code', trans("lang.coupon_code"), ['class' => ' control-label text-left']) !!}
          @if(isset($coupon['code']))
            <p>{!! $coupon->code !!}</p>
          @else
            {!! Form::text('code', null,  ['class' => 'form-control','placeholder'=>  trans("lang.coupon_code_placeholder")]) !!}
            <!-- <div class="form-text text-muted">
              {{ trans("lang.coupon_code_help") }}
            </div> -->
          @endif
      </div>
      <!-- Discount Type Field -->
      <div class="form-group ">
        {!! Form::label('discount_type', trans("lang.coupon_discount_type"),['class' => ' control-label text-left']) !!}
          {!! Form::select('discount_type', ['percent' => trans('lang.coupon_percent'),'fixed' => trans('lang.coupon_fixed')], null, ['class' => 'select2 form-control']) !!}
          <!-- <div class="form-text text-muted">{{ trans("lang.coupon_discount_type_help") }}</div> -->
      </div>
      <!-- Discount Field -->
      <div class="form-group ">
        {!! Form::label('discount', trans("lang.coupon_discount"), ['class' => ' control-label text-left']) !!}
          {!! Form::number('discount', null,  ['class' => 'form-control','placeholder'=>  trans("lang.coupon_discount_placeholder"),'step'=>"any", 'min'=>"0"]) !!}
          <!-- <div class="form-text text-muted">
            {!! trans("lang.coupon_discount_help")   !!}
          </div> -->
      </div>
      <!-- Product Id Field -->
      <div class="form-group ">
        {!! Form::label('products[]', trans("lang.coupon_product_id"),['class' => ' control-label text-left']) !!}
          {!! Form::select('products[]', $product, $productsSelected, ['class' => 'select2 form-control', 'multiple'=>'multiple']) !!}
          <!-- <div class="form-text text-muted">{{ trans("lang.coupon_product_id_help") }}</div> -->
      </div>
      

  </div>

  <div class="col-md-4">
    
    <!-- Category Id Field -->
    <div class="form-group ">
      {!! Form::label('categories[]', trans("lang.coupon_category_id"),['class' => ' control-label text-left']) !!}
        {!! Form::select('categories[]', $category, $categoriesSelected, ['class' => 'select2 form-control', 'multiple'=>'multiple']) !!}
        <!-- <div class="form-text text-muted">{{ trans("lang.coupon_category_id_help") }}</div> -->
    </div>
    <!-- Usage Limit Field -->
    <div class="form-group ">
      {!! Form::label('usage_limit', trans("lang.coupon_usage_limit"), ['class' => ' control-label text-left']) !!}
        {!! Form::number('usage_limit', null,  ['class' => 'form-control','placeholder'=>  trans("lang.coupon_usage_limit_placeholder"),'step'=>"any", 'min'=>"0"]) !!}
        <!-- <div class="form-text text-muted">
          {!! trans("lang.coupon_usage_limit_help")   !!}
        </div> -->
    </div>
    <!-- Expires At Field -->
    <div class="form-group">
      {!! Form::label('expires_at', trans("lang.coupon_expires_at"), ['class' => ' control-label text-left']) !!}
          {!! Form::text('expires_at', null,  ['class' => 'form-control datepicker','autocomplete'=>'off','placeholder'=>  trans("lang.coupon_expires_at_placeholder")  ]) !!}
        <!-- <div class="form-text text-muted">
          {{ trans("lang.coupon_expires_at_help") }}
        </div> -->
    </div>

    <!-- 'Boolean Enabled Field' -->
    <div class="form-group row " style="margin-top:50px;">
      {!! Form::label('enabled', trans("lang.coupon_enabled"),['class' => 'col-3 control-label text-left']) !!}
      {!! Form::hidden('enabled', 0, ['id'=>"hidden_enabled"]) !!}
      <div class=" icheck-{{setting('theme_color')}}" style="margin-top:0px !important;">
          {!! Form::checkbox('enabled', 1, null) !!}
          <label for="enabled"></label>
      </div>
    </div>
    
  </div>

  <div class="col-md-4">
    <!-- Description Field -->
      <div class="form-group">
        {!! Form::label('description', trans("lang.coupon_description"), ['class' => ' control-label text-left']) !!}
          {!! Form::textarea('description', null, ['class' => 'form-control','placeholder'=>
           trans("lang.coupon_description_placeholder")  ]) !!}
          <!-- <div class="form-text text-muted">{{ trans("lang.coupon_description_help") }}</div> -->
      </div>
  </div>

</div>
</div>









</div>
<div style="flex: 50%;max-width: 50%;padding: 0 4px;" class="column">

  

<?php /* ?>
<!-- Market Id Field -->
<div class="form-group row ">
  {!! Form::label('markets', trans("lang.coupon_market_id"),['class' => 'col-3 control-label text-right']) !!}
  <div class="col-9">
    {!! Form::select('markets[]', $market, $marketsSelected, ['class' => 'select2 form-control', 'multiple'=>'multiple']) !!}
    <div class="form-text text-muted">{{ trans("lang.coupon_market_id_help") }}</div>
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
<!-- Submit Field -->
<div class="form-group col-12 text-right">
  <button type="submit" class="btn btn-{{setting('theme_color')}}" ><i class="fa fa-save"></i> {{trans('lang.save')}} {{trans('lang.coupon')}}</button>
  <a href="{!! route('coupons.index') !!}" class="btn btn-default"><i class="fa fa-undo"></i> {{trans('lang.cancel')}}</a>
</div>
