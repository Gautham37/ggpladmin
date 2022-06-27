@if($customFields)
<h5 class="col-12 pb-4">{!! trans('lang.main_fields') !!}</h5>
@endif
<div class="col-md-12 column custom-from-css">
  <div class="row">

    <div class="col-md-4">
    <!-- Name Field -->
    <div class="form-group ">
        {!! Form::label('zone', trans("lang.delivery_zones_zone"), ['class' => ' control-label text-left']) !!}
        {!! Form::text('zone', null,  ['class' => 'form-control','placeholder'=>  trans("lang.delivery_zones_zone_placeholder")]) !!}
        <!-- <div class="form-text text-muted">
          {{ trans("lang.delivery_zones_zone_help") }}
        </div> -->
    </div>

    <!-- Distance Field -->
    <div class="form-group ">
        {!! Form::label('distance', trans("lang.delivery_zones_km"), ['class' => 'control-label text-left']) !!}
        {!! Form::number('distance', null,  ['class' => 'form-control',  'step' => 'any' ,'placeholder'=>  trans("lang.delivery_zones_km_placeholder")]) !!}
        <!-- <div class="form-text text-muted">
          {{ trans("lang.delivery_zones_km_help") }}
        </div> -->
    </div>
    </div>

    <div class="col-md-4">
    <!-- Delivery Charge Field -->
    <div class="form-group ">
        {!! Form::label('delivery_charge', trans("lang.delivery_zones_delivery_charge"), ['class' => ' control-label text-left']) !!}
        {!! Form::number('delivery_charge', null,  ['class' => 'form-control', 'step' => 'any' , 'placeholder'=>  trans("lang.delivery_zones_delivery_charge_placeholder")]) !!}
        <!-- <div class="form-text text-muted">
          {{ trans("lang.delivery_zones_delivery_charge_help") }}
        </div> -->
    </div>

    <!-- Minimum Order Field -->
    <div class="form-group ">
        {!! Form::label('minimum_order', trans("lang.delivery_zones_minimum_order"), ['class' => ' control-label text-left']) !!}
        {!! Form::number('minimum_order', null,  ['class' => 'form-control','placeholder'=>  trans("lang.delivery_zones_minimum_order_placeholder")]) !!}
        <!-- <div class="form-text text-muted">
          {{ trans("lang.delivery_zones_minimum_order_help") }}
        </div> -->
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
  <button type="submit" class="btn btn-{{setting('theme_color')}}" ><i class="fa fa-save"></i> {{trans('lang.save')}} {{trans('lang.delivery_zones')}}</button>
  <a href="{!! route('categories.index') !!}" class="btn btn-default"><i class="fa fa-undo"></i> {{trans('lang.cancel')}}</a>
</div>
