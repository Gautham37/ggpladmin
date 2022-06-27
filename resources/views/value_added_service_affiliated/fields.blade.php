@if($customFields)
<h5 class="col-12 pb-4">{!! trans('lang.main_fields') !!}</h5>
@endif
<div class="col-md-4 column custom-from-css">
    <div class="row">
        
        <!-- Name Field -->
        <div class="col-md-12">
          <div class="form-group">
            {!! Form::label('name', trans("lang.value_added_service_name"), ['class' => 'col-3 control-label text-left required']) !!}
            {!! Form::text('name', null,  ['class' => 'form-control','placeholder'=>""]) !!}
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
  <button type="submit" class="btn btn-{{setting('theme_color')}}" ><i class="fa fa-save"></i> {{trans('lang.save')}} {{trans('lang.value_added_service_affiliated')}}</button>
  <a href="{!! route('valueAddedServiceAffiliated.index') !!}" class="btn btn-default"><i class="fa fa-undo"></i> {{trans('lang.cancel')}}</a>
</div>
