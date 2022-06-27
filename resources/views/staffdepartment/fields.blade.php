@if($customFields)
    <h5 class="col-12 pb-4">{!! trans('lang.main_fields') !!}</h5>
@endif
<div class="col-md-4 column custom-from-css">
    <div class="row">
        <!-- Name Field -->
        <div class="col-md-12">
          <div class="form-group">
            {!! Form::label('name', trans("lang.staffdepartment_name"), ['class' => 'col-3 control-label text-left required']) !!}
            {!! Form::text('name', null,  ['class' => 'form-control','placeholder'=>  trans("lang.staffdepartment_name_placeholder")]) !!}
            <!-- <div class="form-text text-muted">
              {{ trans("lang.category_name_help") }}
            </div> -->
          </div>
        </div>


<div class="col-md-12">
          <div class="form-group">
            {!! Form::label('prefix', trans("lang.staffdepartment_prefix"), ['class' => 'col-3 control-label text-left required']) !!}
            {!! Form::text('prefix', null,  ['class' => 'form-control','placeholder'=>  trans("lang.staffdepartment_prefix_placeholder")]) !!}
            <!-- <div class="form-text text-muted">
              {{ trans("lang.category_name_help") }}
            </div> -->
          </div>
        </div>
       

        <div class="col-md-12">
        <div class="form-group row">
            {!! Form::label('active', trans("lang.staffdepartment_active"),['class' => 'col-md-2 control-label text-left']) !!}
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
            {!! Form::label('description', trans("lang.staffdepartment_description"), ['class' => 'col-3 control-label text-left']) !!}
            {!! Form::textarea('description', null, ['class' => 'form-control','placeholder'=>
             trans("lang.staffdepartment_description_placeholder")  ]) !!}
            <!-- <div class="form-text text-muted">{{ trans("lang.category_description_help") }}</div> -->
          </div>
        </div>
</div>
</div>

<!-- Submit Field -->
<div class="form-group col-12 text-right">
    <button type="submit" class="btn btn-{{setting('theme_color')}}" ><i class="fa fa-save"></i> {{trans('lang.save')}} {{trans('lang.staffdesignation_plural')}}</button>
    <a href="{!! route('staffdesignation.index') !!}" class="btn btn-default"><i class="fa fa-undo"></i> {{trans('lang.cancel')}}</a>
</div>
