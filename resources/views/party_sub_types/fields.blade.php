@if($customFields)
<h5 class="col-12 pb-4">{!! trans('lang.main_fields') !!}</h5>
@endif
<div class="col-md-12 column custom-from-css">
    <div class="row">
        <div class="col-md-6">
            
         <!-- Category Id Field -->
        <div class="form-group ">
            {!! Form::label('party_type_id', trans("lang.party_type"),['class' => ' control-label text-left required']) !!}
            {!! Form::select('party_type_id', $partyTypes, null, ['class' => 'select2 form-control party_type_id']) !!}
            <!--<div class="form-text text-muted">{{ trans("lang.product_category_id_help") }}</div>-->
        </div>

        <div class="form-group ">
            {!! Form::label('role_id', 'Role',['class' => ' control-label text-left required']) !!}
            {!! Form::select('role_id', $roles, null, ['class' => 'select2 form-control role_id']) !!}
            <!--<div class="form-text text-muted">{{ trans("lang.product_category_id_help") }}</div>-->
        </div>

        <!-- Name Field -->
        <div class="form-group ">
          {!! Form::label('name', trans("lang.party_sub_type_name"), ['class' => ' control-label text-left required']) !!}
            {!! Form::text('name', null,  ['class' => 'form-control','placeholder'=>  trans("lang.party_sub_type_name_placeholder")]) !!}
        </div>
        
          <!-- Prefix Field -->
        <div class="form-group ">
          {!! Form::label('prefix_value', trans("lang.party_sub_type_prefix"), ['class' => ' control-label text-left required']) !!}
            {!! Form::text('prefix_value', null,  ['class' => 'form-control','placeholder'=>  trans("lang.party_sub_type_prefix_placeholder")]) !!}
        </div>
        
        

        <div class="form-group mt-15">
            <div class="row">
            {!! Form::label('active', trans("lang.party_sub_type_active"),['class' => 'col-md-1 control-label text-left']) !!}
        <div class="col-md-11 checkbox icheck">
            <label class=" ml-2 form-check-inline">
                {!! Form::hidden('active', 0) !!}
                {!! Form::checkbox('active', 1, 1) !!}
            </label>
        </div>
        </div>
        </div>
    
        </div>
        <div class="col-md-6">
            <!-- Description Field -->
            <div class="form-group ">
              {!! Form::label('description', trans("lang.party_sub_type_description"), ['class' => ' control-label text-left required']) !!}
                {!! Form::textarea('description', null, ['class' => 'form-control','placeholder'=>
                 trans("lang.party_sub_type_description_placeholder")  ]) !!}
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
  <button type="submit" class="btn btn-{{setting('theme_color')}}" ><i class="fa fa-save"></i> {{trans('lang.save')}} {{trans('lang.party_sub_type')}}</button>
  <a href="{!! route('partySubTypes.index') !!}" class="btn btn-default"><i class="fa fa-undo"></i> {{trans('lang.cancel')}}</a>
</div>
