@if($customFields)
<h5 class="col-12 pb-4">{!! trans('lang.main_fields') !!}</h5>
@endif
<div class="col-md-12 column custom-from-css">
  <div class="row">

    <div class="col-md-5">
    <!-- Name Field -->
    <div class="form-group">
      {!! Form::label('name', trans("lang.customer_levels_name"), ['class' => ' control-label text-left']) !!}
        {!! Form::text('name', null,  ['class' => 'form-control','placeholder'=>  trans("lang.customer_levels_name_placeholder")]) !!}
        <!-- <div class="form-text text-muted">
          {{ trans("lang.customer_levels_name_help") }}
        </div> -->
    </div>
    <!-- Group Points Field -->
    <div class="form-group ">
      {!! Form::label('group_points', trans("lang.customer_levels_group_points"), ['class' => ' control-label text-left']) !!}
        {!! Form::text('group_points', null,  ['class' => 'form-control','placeholder'=>  trans("lang.customer_levels_group_points_placeholder")]) !!}
        <!-- <div class="form-text text-muted">
          {{ trans("lang.customer_levels_group_points_help") }}
        </div> -->
    </div>

    <!-- Monthly Spend Field -->
    <div class="form-group ">
        {!! Form::label('monthly_spend_from', 'Monthly Spend From', ['class' => ' control-label text-left']) !!}
        {!! Form::number('monthly_spend_from', null,  ['class' => 'form-control','placeholder'=>  'Monthly Spend', 'step' => 'any']) !!}
    </div>

    <!-- Monthly Spend Field -->
    <div class="form-group ">
        {!! Form::label('monthly_spend_to', 'Monthly Spend To', ['class' => ' control-label text-left']) !!}
        {!! Form::number('monthly_spend_to', null,  ['class' => 'form-control','placeholder'=>  'Monthly Spend', 'step' => 'any']) !!}
    </div>

    </div>

    <div class="col-md-5">
      <!-- Description Field -->
      <div class="form-group ">
        {!! Form::label('description', trans("lang.customer_levels_description"), ['class' => 'col-3 control-label text-left']) !!}
          {!! Form::textarea('description', null, ['class' => 'form-control','placeholder'=>
           trans("lang.customer_levels_description_placeholder")  ]) !!}
          <!-- <div class="form-text text-muted">{{ trans("lang.customer_levels_description_help") }}</div> -->
      </div>
    </div>

  </div>
</div>

<!-- Submit Field -->
<div class="form-group col-12 text-right">
  <button type="submit" class="btn btn-{{setting('theme_color')}}" ><i class="fa fa-save"></i> {{trans('lang.save')}} {{trans('lang.customer_levels_plural')}}</button>
  <a href="{!! route('CustomerLevels.index') !!}" class="btn btn-default"><i class="fa fa-undo"></i> {{trans('lang.cancel')}}</a>
</div>
