@if($customFields)
<h5 class="col-12 pb-4">{!! trans('lang.main_fields') !!}</h5>
@endif
<div class="col-md-4 column custom-from-css">
    <div class="row">
        <!-- Name Field -->
        <div class="col-md-12">
          <div class="form-group">
            {!! Form::label('name', trans("lang.quality_grade_name"), ['class' => 'col-3 control-label text-left required']) !!}
            {!! Form::text('name', null,  ['class' => 'form-control','placeholder'=>  trans("lang.quality_grade_placeholder")]) !!}
          </div>
        </div>
        
          <div class="col-md-12">
        <div class="form-group row">
            {!! Form::label('active', trans("lang.quality_grade_active"),['class' => 'col-md-2 control-label text-left']) !!}
            <div class="col-md-4 checkbox icheck">
                <label class="form-check-inline">
                    {!! Form::hidden('active', 0) !!}
                    {!! Form::checkbox('active', 1, 1) !!}
                </label>
            </div>
        </div>
        </div>


</div>  
</div>

<div class="col-md-8 column custom-from-css">
    <div class="row">
        <!-- Description Field -->
        <div class="col-md-12">
          <div class="form-group">
            {!! Form::label('description', trans("lang.quality_grade_description"), ['class' => 'col-3 control-label text-left required ']) !!}
            {!! Form::textarea('description', null, ['class' => 'form-control'  ]) !!}
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
  <button type="submit" class="btn btn-{{setting('theme_color')}}" ><i class="fa fa-save"></i> {{trans('lang.save')}} {{trans('lang.quality_grade')}}</button>
  <a href="{!! route('qualityGrade.index') !!}" class="btn btn-default"><i class="fa fa-undo"></i> {{trans('lang.cancel')}}</a>
</div>
