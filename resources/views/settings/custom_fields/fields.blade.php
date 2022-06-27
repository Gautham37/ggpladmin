<div class="col-md-12 column custom-from-css">
    <div class="row">
        <!-- Name Field -->
        <div class="form-group col-md-6 ">
            {!! Form::label('name', trans("lang.custom_field_name"), ['class' => ' control-label text-left']) !!}
            {!! Form::text('name', null,  ['class' => 'form-control','placeholder'=>  trans("lang.custom_field_name_placeholder")]) !!}
            <!-- <div class="form-text text-muted">
                {{ trans("lang.custom_field_name_help") }}
            </div> -->
        </div>

        <!-- Type Field -->
        <div class="form-group col-md-6 ">
            {!! Form::label('type', trans("lang.custom_field_type"),['class' => ' control-label text-left']) !!}
            {!! Form::select('type', $customFieldsTypes, null, ['class' => 'select2 form-control']) !!}
            <!-- <div class="form-text text-muted">{{ trans("lang.custom_field_type_help") }}</div> -->
        </div>

        <!-- values Field -->
        <div class="form-group col-md-6 ">
            {!! Form::label('values[]', trans("lang.custom_field_values"),['class' => ' control-label text-left']) !!}
            {!! Form::select('values[]', array_combine($customFieldValues,$customFieldValues), $customFieldValues, ['data-tags'=>'true','multiple'=>'multiple', 'class' => 'select2 form-control']) !!}
            <!-- <div class="form-text text-muted">{{ trans("lang.custom_field_values_help") }}</div> -->
        </div>

        <!-- Bootstrap Column Field -->
        <div class="form-group col-md-6 ">
            {!! Form::label('bootstrap_column', trans("lang.custom_field_bootstrap_column"), ['class' => ' control-label text-left']) !!}
            {!! Form::number('bootstrap_column', null,  ['class' => 'form-control','placeholder'=>  trans("lang.custom_field_bootstrap_column_placeholder")]) !!}
            <!-- <div class="form-text text-muted">
                {{ trans("lang.custom_field_bootstrap_column_help") }}
            </div> -->
        </div>

        <!-- Order Field -->
        <div class="form-group col-md-6 ">
            {!! Form::label('order', trans("lang.custom_field_order"), ['class' => ' control-label text-left']) !!}
            {!! Form::number('order', null,  ['class' => 'form-control','placeholder'=>  trans("lang.custom_field_order_placeholder")]) !!}
            <!-- <div class="form-text text-muted">
                {{ trans("lang.custom_field_order_help") }}
            </div> -->
        </div>

        <!-- Custom Field Model Field -->
        <div class="form-group col-md-6 ">
            {!! Form::label('custom_field_model', trans("lang.custom_field_custom_field_model"),['class' => ' control-label text-left']) !!}
            {!! Form::select('custom_field_model', $customFieldModels, null, ['class' => 'select2 form-control']) !!}
            <!-- <div class="form-text text-muted">{{ trans("lang.custom_field_custom_field_model_help") }}</div> -->
        </div>
        
        <div class="col-md-12 custom-checkbx">
        <!-- 'Boolean Disabled Field' -->
        <div class="form-group col-md-4 ">
            <div class="row"> 
            {!! Form::label('disabled', trans("lang.custom_field_disabled"),['class' => 'col-md-5 control-label text-left']) !!}
            <div class="col-md-7 checkbox icheck">
                <label class=" form-check-inline">
                    {!! Form::hidden('disabled', 0) !!}
                    {!! Form::checkbox('disabled', 1, null) !!}
                </label>
            </div>
            </div>
        </div>

        <!-- 'Boolean Required Field' -->
        <div class="form-group col-md-4 ">
            <div class="row">
            {!! Form::label('required', trans("lang.custom_field_required"),['class' => 'col-md-5 control-label text-left']) !!}
            <div class="col-md-7 checkbox icheck">
                <label class=" form-check-inline">
                    {!! Form::hidden('required', 0) !!}
                    {!! Form::checkbox('required', 1, null) !!}
                </label>
            </div>
            </div>
        </div>

        <!-- 'Boolean In Table Field' -->
        <div class="form-group col-md-4 ">
            <div class="row">
            {!! Form::label('in_table', trans("lang.custom_field_in_table"),['class' => 'col-md-5 control-label text-left']) !!}
            <div class="col-md-7 checkbox icheck">
                <label class=" form-check-inline">
                    {!! Form::hidden('in_table', 0) !!}
                    {!! Form::checkbox('in_table', 1, null) !!}
                </label>
            </div>
            </div>
        </div>
        </div>

    </div>

    <div class="row">
        <!-- Submit Field -->
        <div class="form-group col-12 text-right">
            <button type="submit" class="btn btn-{{setting('theme_color','primary')}}"><i class="fa fa-save"></i> {{trans('lang.save')}} {{trans('lang.custom_field')}}</button>
            <a href="{!! route('customFields.index') !!}" class="btn btn-default"><i class="fa fa-undo"></i> {{trans('lang.cancel')}}</a>
        </div>
    </div>
</div>
