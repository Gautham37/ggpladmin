@if($customFields)
<h5 class="col-12 pb-4">{!! trans('lang.main_fields') !!}</h5>
@endif
<div style="flex: 50%;max-width: 50%;padding: 0 4px;" class="column">

<!-- Market Id Field -->
<div class="form-group row ">
  {!! Form::label('payment_out_party', trans("lang.payment_out_party"),['class' => 'col-3 control-label text-right', 'id' => 'payment_out_party']) !!}
  <div class="col-9">
    {!! Form::select('payment_out_party', $market, null, ['class' => 'select2 form-control payment_out_party']) !!}
    <div class="form-text text-muted">{{ trans("lang.markets_payout_market_id_help") }}</div>
  </div>
</div>


<!-- Payment Date Field -->
<div class="form-group row ">
  {!! Form::label('payment_out_date', trans("lang.payment_out_date"),['class' => 'col-3 control-label text-right']) !!}
  <div class="col-9">
      {!! Form::text('payment_out_date', date('Y-m-d'),  ['class' => 'datepicker form-control', 'placeholder'=>  trans("lang.payment_out_date_placeholder")]) !!}
    <div class="form-text text-muted">
      {{ trans("lang.payment_out_date_help") }}
    </div>
  </div>
</div>

<!-- Market Id Field -->
<div class="form-group row ">
  {!! Form::label('payment_out_payment_type', trans("lang.payment_out_payment_type"),['class' => 'col-3 control-label text-right']) !!}
  <div class="col-9">
    {!! Form::select('payment_out_payment_type', $payment_mode, null, ['class' => 'select2 form-control']) !!}
    <div class="form-text text-muted">{{ trans("lang.payment_out_payment_type_help") }}</div>
  </div>
</div>

<!-- Payment No Field -->
<div class="form-group row ">
  {!! Form::label('payment_out_no', trans("lang.payment_out_no"),['class' => 'col-3 control-label text-right']) !!}
  <div class="col-9">
      {!! Form::text('payment_out_no', isset($payment_out_no) ? $payment_out_no : null ,  ['class' => 'form-control', 'placeholder'=>  trans("lang.payment_out_no_placeholder")]) !!}
    <div class="form-text text-muted">
      {{ trans("lang.payment_out_no_help") }}
    </div>
  </div>
</div>

<!-- Amount Field -->
<div class="form-group row ">
  {!! Form::label('payment_out_amount', trans("lang.payment_out_amount").' '.setting('default_currency'), ['class' => 'col-3 control-label text-right']) !!}
  <div class="col-9">
      {!! Form::number('payment_out_amount', null,  ['class' => 'form-control','step'=>"any",'placeholder'=>  trans("lang.payment_out_amount_placeholder")]) !!}
    <div class="form-text text-muted">
      {{ trans("lang.payment_out_amount_help") }}
    </div>
  </div>
</div>
</div>
<div style="flex: 50%;max-width: 50%;padding: 0 4px;" class="column">

<!-- Note Field -->
<div class="form-group row ">
  {!! Form::label('payment_out_notes', trans("lang.markets_payout_note"), ['class' => 'col-3 control-label text-right']) !!}
  <div class="col-9">
    {!! Form::textarea('payment_out_notes', null, ['class' => 'form-control','placeholder'=>
     trans("lang.markets_payout_note_placeholder")  ]) !!}
    <div class="form-text text-muted">{{ trans("lang.markets_payout_note_help") }}</div>
  </div>
</div>

</div>



<!-- Note Field -->
<div class="row">
  <div class="col-md-12  ">
     <p class="text-right text-success"><b>{{trans('lang.payment_out_balance_amount')}} : <span class="balance_amount">0</span></b></p> 
  </div>
</div>

@if($customFields)
<div class="clearfix"></div>
<div class="col-12 custom-field-container">
  <h5 class="col-12 pb-4">{!! trans('lang.custom_field_plural') !!}</h5>
  {!! $customFields !!}
</div>
@endif

<div class="row" style="width: 100%;">
  <div class="col-md-12">

     <h6><b>{{trans('lang.settle_invoice_with_payment')}}</b></h6>
     
     <br>

     <table id="payoutInvoices" class="table table-bordered">
       <thead >
          <tr>
           <th>{{strtoupper(trans('lang.payment_out_settle_invoice'))}}</th>
           <th>{{strtoupper(trans('lang.payment_out_invoice_date'))}}</th>
           <th>{{strtoupper(trans('lang.payment_out_invoice_number'))}}</th>
           <th>{{strtoupper(trans('lang.payment_out_invoice_amount'))}}</th>
           <th>{{strtoupper(trans('lang.payment_out_invoice_amount_paid'))}}</th>
         </tr>
       </thead>
       <tbody id="payoutInvoicerows">
       </tbody>
     </table>
  </div>
</div>

<!-- Submit Field -->
<div class="form-group col-12 text-right">
  <button type="submit" class="btn btn-{{setting('theme_color')}}" ><i class="fa fa-save"></i> {{trans('lang.save')}} {{trans('lang.payment_out')}}</button>
  <a href="{!! route('paymentOut.index') !!}" class="btn btn-default"><i class="fa fa-undo"></i> {{trans('lang.cancel')}}</a>
</div>
