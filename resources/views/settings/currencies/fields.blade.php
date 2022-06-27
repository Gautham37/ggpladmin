@if($customFields)
<h5 class="col-12 pb-4">{!! trans('lang.main_fields') !!}</h5>
@endif
<div class="col-md-12 column custom-from-css">
  <div class="row">

  <!-- Name Field -->
  <div class="form-group col-md-6 ">
    {!! Form::label('name', trans("lang.currency_name"), ['class' => ' control-label text-left']) !!}
    {!! Form::text('name', null,  ['class' => 'form-control','placeholder'=>  trans("lang.currency_name_placeholder")]) !!}
    <!-- <div class="form-text text-muted">
      {{ trans("lang.currency_name_help") }}
    </div> -->
  </div>

  <!-- Symbol Field -->
  <div class="form-group col-md-6 ">
    {!! Form::label('symbol', trans("lang.currency_symbol"), ['class' => ' control-label text-left']) !!}
    {!! Form::text('symbol', null,  ['class' => 'form-control','placeholder'=>  trans("lang.currency_symbol_placeholder")]) !!}
    <!-- <div class="form-text text-muted">
      {{ trans("lang.currency_symbol_help") }}
    </div> -->
  </div>

  <!-- Code Field -->
  <div class="form-group col-md-6 ">
    {!! Form::label('code', trans("lang.currency_code"), ['class' => ' control-label text-left']) !!}
    {!! Form::text('code', null,  ['class' => 'form-control','placeholder'=>  trans("lang.currency_code_placeholder")]) !!}
    <!-- <div class="form-text text-muted">
      {{ trans("lang.currency_code_help") }}
    </div> -->
  </div>
  <!-- Decimal Digits Field -->
  <div class="form-group col-md-6 ">
    {!! Form::label('decimal_digits', trans("lang.currency_decimal_digits"), ['class' => ' control-label text-left']) !!}
    {!! Form::number('decimal_digits', null,  ['class' => 'form-control','placeholder'=>  trans("lang.currency_decimal_digits_placeholder")]) !!}
    <!-- <div class="form-text text-muted">
      {{ trans("lang.currency_decimal_digits_help") }}
    </div> -->
  </div>

  <!-- Rounding Field -->
  <div class="form-group col-md-6 ">
    {!! Form::label('rounding', trans("lang.currency_rounding"), ['class' => ' control-label text-left']) !!}
    {!! Form::number('rounding', null,  ['class' => 'form-control','placeholder'=>  trans("lang.currency_rounding_placeholder")]) !!}
    <!-- <div class="form-text text-muted">
      {{ trans("lang.currency_rounding_help") }}
    </div> -->
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
  <button type="submit" class="btn btn-{{setting('theme_color')}}" ><i class="fa fa-save"></i> {{trans('lang.save')}} {{trans('lang.currency')}}</button>
  <a href="{!! route('currencies.index') !!}" class="btn btn-default"><i class="fa fa-undo"></i> {{trans('lang.cancel')}}</a>
</div>
