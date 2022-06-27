@if($customFields)
<h5 class="col-12 pb-4">{!! trans('lang.main_fields') !!}</h5>
@endif
<div class="col-md-12 column custom-from-css">
  <div class="row">

  <!-- Name Field -->
  <div class="col-md-4">
    <div class="form-group">
      {!! Form::label('name', trans("lang.option_group_name"), ['class' => 'control-label text-right required']) !!}
      {!! Form::text('name', null,  ['class' => 'form-control','placeholder'=>  trans("lang.option_group_name_placeholder")]) !!}
      <!-- <div class="form-text text-muted">
        {{ trans("lang.option_group_name_help") }}
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
  <button type="submit" class="btn btn-{{setting('theme_color')}}" ><i class="fa fa-save"></i> {{trans('lang.save')}} {{trans('lang.option_group')}}</button>
  <a href="{!! route('optionGroups.index') !!}" class="btn btn-default"><i class="fa fa-undo"></i> {{trans('lang.cancel')}}</a>
</div>
