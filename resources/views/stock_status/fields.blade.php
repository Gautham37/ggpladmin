@if($customFields)
<h5 class="col-12 pb-4">{!! trans('lang.main_fields') !!}</h5>
@endif
<div class="col-md-4 column custom-from-css">
    <div class="row">
        <!-- Name Field -->
        <div class="col-md-12">
          <div class="form-group">
            {!! Form::label('status', trans("lang.stock_status_status"), ['class' => 'col-3 control-label text-left required']) !!}
            {!! Form::text('status', null,  ['class' => 'form-control','placeholder'=>  trans("lang.stock_status_placeholder")]) !!}
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
  <button type="submit" class="btn btn-{{setting('theme_color')}}" ><i class="fa fa-save"></i> {{trans('lang.save')}} {{trans('lang.stock_status')}}</button>
  <a href="{!! route('stockStatus.index') !!}" class="btn btn-default"><i class="fa fa-undo"></i> {{trans('lang.cancel')}}</a>
</div>
