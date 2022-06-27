@if($customFields)
<h5 class="col-12 pb-4">{!! trans('lang.main_fields') !!}</h5>
@endif
<div style="flex: 50%;max-width: 50%;padding: 0 4px;" class="column">
<!-- Name Field -->
<div class="form-group row ">
  {!! Form::label('name', trans("lang.customer_levels_name"), ['class' => 'col-3 control-label text-right']) !!}
  <div class="col-9">
    {!! Form::text('name', null,  ['class' => 'form-control','placeholder'=>  trans("lang.customer_levels_name_placeholder")]) !!}
    <div class="form-text text-muted">
      {{ trans("lang.customer_levels_name_help") }}
    </div>
  </div>
</div>

<!-- Description Field -->
<div class="form-group row ">
  {!! Form::label('description', trans("lang.customer_levels_description"), ['class' => 'col-3 control-label text-right']) !!}
  <div class="col-9">
    {!! Form::textarea('description', null, ['class' => 'form-control','placeholder'=>
     trans("lang.customer_levels_description_placeholder")  ]) !!}
    <div class="form-text text-muted">{{ trans("lang.customer_levels_description_help") }}</div>
  </div>
</div>

<!-- Group Points Field -->
<div class="form-group row ">
  {!! Form::label('group_points', trans("lang.customer_levels_group_points"), ['class' => 'col-3 control-label text-right']) !!}
  <div class="col-9">
    {!! Form::text('group_points', null,  ['class' => 'form-control','placeholder'=>  trans("lang.customer_levels_group_points_placeholder")]) !!}
    <div class="form-text text-muted">
      {{ trans("lang.customer_levels_group_points_help") }}
    </div>
  </div>
</div>

<!-- Monthly Spend Field -->
<div class="form-group row ">
  {!! Form::label('monthly_spend', trans("lang.customer_levels_monthly_spend"), ['class' => 'col-3 control-label text-right']) !!}
  <div class="col-9">
    {!! Form::number('monthly_spend', null,  ['class' => 'form-control','placeholder'=>  trans("lang.customer_levels_monthly_spend_placeholder"), 'step' => 'any']) !!}
    <div class="form-text text-muted">
      {{ trans("lang.customer_levels_monthly_spend_help") }}
    </div>
  </div>
</div>

</div>


<!-- Submit Field -->
<div class="form-group col-12 text-right">
  <button type="submit" class="btn btn-{{setting('theme_color')}}" ><i class="fa fa-save"></i> {{trans('lang.save')}} {{trans('lang.customer_levels_plural')}}</button>
  <a href="{!! route('CustomerLevels.index') !!}" class="btn btn-default"><i class="fa fa-undo"></i> {{trans('lang.cancel')}}</a>
</div>
