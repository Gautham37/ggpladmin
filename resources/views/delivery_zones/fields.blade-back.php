@if($customFields)
<h5 class="col-12 pb-4">{!! trans('lang.main_fields') !!}</h5>
@endif
<div style="flex: 80%;max-width: 50%;padding: 0 4px;" class="column">

<!-- Name Field -->
<div class="form-group row ">
  {!! Form::label('zone', trans("lang.delivery_zones_zone"), ['class' => 'col-3 control-label text-right']) !!}
  <div class="col-9">
    {!! Form::text('zone', null,  ['class' => 'form-control','placeholder'=>  trans("lang.delivery_zones_zone_placeholder")]) !!}
    <div class="form-text text-muted">
      {{ trans("lang.delivery_zones_zone_help") }}
    </div>
  </div>
</div>

<!-- Distance Field -->
<div class="form-group row ">
  {!! Form::label('distance', trans("lang.delivery_zones_km"), ['class' => 'col-3 control-label text-right']) !!}
  <div class="col-9">
    {!! Form::number('distance', null,  ['class' => 'form-control',  'step' => 'any' ,'placeholder'=>  trans("lang.delivery_zones_km_placeholder")]) !!}
    <div class="form-text text-muted">
      {{ trans("lang.delivery_zones_km_help") }}
    </div>
  </div>
</div>

<!-- Delivery Charge Field -->
<div class="form-group row ">
  {!! Form::label('delivery_charge', trans("lang.delivery_zones_delivery_charge"), ['class' => 'col-3 control-label text-right']) !!}
  <div class="col-9">
    {!! Form::number('delivery_charge', null,  ['class' => 'form-control', 'step' => 'any' , 'placeholder'=>  trans("lang.delivery_zones_delivery_charge_placeholder")]) !!}
    <div class="form-text text-muted">
      {{ trans("lang.delivery_zones_delivery_charge_help") }}
    </div>
  </div>
</div>

<!-- Minimum Order Field -->
<div class="form-group row ">
  {!! Form::label('minimum_order', trans("lang.delivery_zones_minimum_order"), ['class' => 'col-3 control-label text-right']) !!}
  <div class="col-9">
    {!! Form::number('minimum_order', null,  ['class' => 'form-control','placeholder'=>  trans("lang.delivery_zones_minimum_order_placeholder")]) !!}
    <div class="form-text text-muted">
      {{ trans("lang.delivery_zones_minimum_order_help") }}
    </div>
  </div>
</div>

<!-- Description Field 
<div class="form-group row ">
  {!! Form::label('description', trans("lang.category_description"), ['class' => 'col-3 control-label text-right']) !!}
  <div class="col-9">
    {!! Form::textarea('description', null, ['class' => 'form-control','placeholder'=>
     trans("lang.category_description_placeholder")  ]) !!}
    <div class="form-text text-muted">{{ trans("lang.category_description_help") }}</div>
  </div>
</div>-->
</div>
<div style="flex: 50%;max-width: 50%;padding: 0 4px;" class="column">


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
  <button type="submit" class="btn btn-{{setting('theme_color')}}" ><i class="fa fa-save"></i> {{trans('lang.save')}} {{trans('lang.delivery_zones')}}</button>
  <a href="{!! route('categories.index') !!}" class="btn btn-default"><i class="fa fa-undo"></i> {{trans('lang.cancel')}}</a>
</div>
