
<div class="col-md-12 column custom-from-css">
    <div class="row">
        
        <!-- Name Field -->
        <div class="col-md-6">
          <div class="form-group">
            {!! Form::label('name', trans("lang.staffdesignation_name"), ['class' => 'col-3 control-label text-left required']) !!}
            {!! Form::text('name', null,  ['class' => 'form-control','placeholder'=>  ""]) !!}
          </div>
        </div>

        <div class="col-md-6">
            <div class="form-group row mt-4">
                {!! Form::label('active', trans("lang.staffdesignation_active"),['class' => 'col-md-2 control-label text-left']) !!}
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


<!-- Submit Field -->
<div class="form-group col-12 text-right">
    <button type="submit" class="btn btn-{{setting('theme_color')}}" ><i class="fa fa-save"></i> {{trans('lang.save')}} {{trans('lang.staffdesignation_plural')}}</button>
    <a href="{!! route('staffdesignation.index') !!}" class="btn btn-default"><i class="fa fa-undo"></i> {{trans('lang.cancel')}}</a>
</div>
