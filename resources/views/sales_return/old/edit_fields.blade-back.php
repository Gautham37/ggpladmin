@if($customFields)
<h5 class="col-12 pb-4">{!! trans('lang.main_fields') !!}</h5>
@endif
  
  <style type="text/css">

    .form-control {
      font-size: 12px !important;
      padding: 2px 5px !important;
      background: #eceaef;
    }
    span.input-group-addon {
        border: 2px solid #dce4ec;
        background: #eceaef;
        text-align: center;
    }
    select.form-control:not([size]):not([multiple]) {
        height: calc(1.25rem + 3px);
        width: auto;
    }
  </style>

  <div class="container">
    <div class="row">
      @include('layouts.invoice_header')
      <div class="col-md-8" data-toggle="tooltip" title="{{trans('lang.shift_y')}}">
        
        {!! Form::label('sales_party', trans("lang.sales_return_party"),['class' => 'control-label text-right']) !!}
        {!! Form::select('sales_party', $markets, $marketSelected, ['class' => 'select2 form-control', 'onchange' => 'getPartyDetail(this)']) !!}
        <div class="form-text text-muted">{{ trans("lang.sales_return_customer_help") }}</div>

        <div class="party_details"></div>

      </div><!-- /.col -->
      <div class="col-md-4 row">

          <div class="col-md-6">
            {!! Form::label('sales_code', trans("lang.sales_return_no"), ['class' => 'control-label text-right']) !!}
            {!! Form::text('sales_code', $sales_return->sales_code,  ['class' => 'form-control']) !!}
            <div class="form-text text-muted">{{ trans("lang.sales_return_no_help") }}</div>
          </div>

          <div class="col-md-6">
            {!! Form::label('sales_date', trans("lang.sales_return_date"), ['class' => 'control-label text-right']) !!}
            {!! Form::text('sales_date', $sales_return->sales_date,  ['class' => 'form-control datepicker', 'readonly' => 'readonly']) !!}
            <div class="form-text text-muted">{{ trans("lang.sales_return_date_help") }}</div>
          </div>

          <div class="col-md-6">
            {!! Form::label('valid_date', trans("lang.sales_return_valid_date"), ['class' => 'control-label text-right']) !!}
            {!! Form::text('valid_date', $sales_return->sales_valid_date,  ['class' => 'form-control datepicker', 'readonly' => 'readonly']) !!}
            <div class="form-text text-muted">{{ trans("lang.sales_return_valid_date_help") }}</div>
          </div>
      
      </div><!-- /.col -->

      <div class="col-md-12">
          <table id="salesItems" class="table table-bordered">
            <thead>
              <tr>
                <th>NO</th>
                <th>ITEMS</th>
                <th>HSN</th>
                <th>MRP</th>
                <th>QTY</th>
                <th>PRICE/ITEM</th>
                <th>DISCOUNT</th>
                <th>TAX</th>
                <th>AMOUNT</th>
                <th></th>
              </tr>
            </thead>
            <tfoot>
              <th colspan="6">Subtotal</th>
              <th>
                <div class="input-group">
                  <span class="input-group-addon">₹</span>
                  {!! Form::text('total_discount', $sales_return->sales_discount_amount,  ['class' => 'form-control total_discount', 'readonly' => 'readonly']) !!}
                </div>
              </th>
              <th>
                <div class="input-group">
                  <span class="input-group-addon">₹</span>
                  {!! Form::text('total_tax', $sales_return->sales_igst_amount,  ['class' => 'form-control total_tax', 'readonly' => 'readonly']) !!}
                </div>
              </th>
              <th>
                <div class="input-group">
                  <span class="input-group-addon">₹</span>
                  {!! Form::text('total_amount', $sales_return->sales_total_amount,  ['class' => 'form-control total_amount', 'readonly' => 'readonly']) !!}
                </div>
              </th>
              <th></th>
            </tfoot>

            <tfoot class="text-center">
              <tr>
                <th colspan="8">
                  <button onclick="addItem();" data-toggle="tooltip" title="{{ trans('lang.shift_m') }}" type="button" data-toggle="tooltip" class="btn btn-primary text-center">
                    <i class="fa fa-plus"></i> 
                    &nbsp; Add Item
                  </button>
                </th>
                <th colspan="2">
                  
                  
                  <div data-toggle="tooltip" title="{{ trans('lang.shift_b') }}" onclick="addItembyBarcode();">
                    <img src="{{ url('images/scanner.png') }}" width="40" Title="Scan Barcode" alt="Scan Barcode" />
                    <br>
                    {{ trans('lang.scan_bar_code') }}
                  </div>

                </th>
              </tr>
            </tfoot>
            
          </table>

      </div>
      <div class="col-md-6">
        
        {!! Form::label('description', trans("lang.po_description"), ['class' => 'control-label text-right']) !!}
        {!! Form::textarea('description', $sales_return->sales_notes, ['class' => 'form-control','placeholder'=>
               trans("lang.po_description_placeholder")  ]) !!}
        <div class="form-text text-muted">{{ trans("lang.po_description_help") }}</div>

        <br>

        {!! Form::label('terms_and_conditions', trans("lang.po_terms_and_conditions"), ['class' => 'control-label text-right']) !!}
        {!! Form::textarea('terms_and_conditions', $sales_return->sales_terms_and_conditions, ['class' => 'form-control','placeholder'=>
               trans("lang.po_terms_and_conditions_placeholder")  ]) !!}
        <div class="form-text text-muted">{{ trans("lang.po_terms_and_conditions_help") }}</div>

      </div>

      <div class="col-md-6">
          <table class="table table-bordered">
            <tr>
              <td class="text-left">Taxable Amount</td>
              <td class="text-right">
                
                <div class="input-group">
                  <span class="input-group-addon">₹</span>
                  {!! Form::text('taxable_amount', $sales_return->sales_taxable_amount,  ['class' => 'form-control taxable_amount']) !!}
                </div>
                <div class="form-text text-muted">{{ trans("lang.po_taxable_amount") }}</div>

              </td>
            </tr>

            <tr>
              <td class="text-left">SGST <span class="sgst_per"></span></td>
              <td class="text-right">
                <div class="input-group">
                  <span class="input-group-addon">₹</span>
                  {!! Form::text('sgst_amount', $sales_return->sales_sgst_amount,  ['class' => 'form-control sgst_amount']) !!}
                </div>
                <div class="form-text text-muted">{{ trans("lang.po_sgst_amount") }}</div>
              </td>
            </tr>

            <tr>
              <td class="text-left">CGST <span class="cgst_per"></span></td>
              <td class="text-right">
                <div class="input-group">
                  <span class="input-group-addon">₹</span>
                  {!! Form::text('cgst_amount', $sales_return->sales_cgst_amount,  ['class' => 'form-control cgst_amount']) !!}
                </div>
                <div class="form-text text-muted">{{ trans("lang.po_cgst_amount") }}</div>
              </td>
            </tr>


            <tr>
              <td class="text-left">
                  Additional Charges
                  {!! Form::text('additional_charge_detail', $sales_return->sales_additional_charge_description,  ['class' => 'form-control additional_charge_detail']) !!}
                  <div class="form-text text-muted">{{ trans("lang.po_additional_charge_detail_help") }}</div>
              </td>
              <td class="text-right">
                  <div class="input-group">
                    <span class="input-group-addon">₹</span>
                    {!! Form::text('additional_charges', $sales_return->sales_additional_charge,  ['class' => 'form-control additional_charges', 'id' => 'additional_charges']) !!}
                  </div>
                  <div class="form-text text-muted">{{ trans("lang.po_additional_charge_help") }}</div>
              </td>
            </tr>
            <tr>
              <td class="text-left">Total Amount</td>
              <td class="text-right">
                  <div class="input-group">
                    <span class="input-group-addon">₹</span>
                      {!! Form::text('total_sales_amount', $sales_return->sales_total_amount,  ['class' => 'form-control', 'id' => 'total_purchase_amount']) !!}
                  </div>  
                  <div class="form-text text-muted">{{ trans("lang.po_total_amount_help") }}</div>
              </td>
            </tr>
            <tr>
              <td class="text-left">Cash Paid</td>
              <td class="text-right">
                  <span> Mark as Fully Paid  <input type="checkbox" name="mark_as_paid" id="mark_as_paid" value="1"></span>
                  <div class="input-group">
                    <span class="input-group-addon">₹</span>
                    {!! Form::text('cash_paid', $sales_return->sales_cash_paid,  ['class' => 'form-control cash_paid', 'id' => 'cash_paid']) !!}
                  </div>
                  <div class="form-text text-muted">{{ trans("lang.po_cash_paid_help") }}</div>
              </td>
            </tr>
            <tr>
              <td class="text-left">Balance Amount</td>
              <td class="text-right">
                  <div class="input-group">
                    <span class="input-group-addon">₹</span>
                    {!! Form::text('balance_amount', $sales_return->sales_balance_amount,  ['class' => 'form-control balance_amount', 'id' => 'balance_amount']) !!}
                  </div>
                  <div class="form-text text-muted">{{ trans("lang.po_balance_amount_help") }}</div>
              </td>
            </tr>
          </table>
      </div>

    </div><!-- /.row -->
  </div><!-- /.container-fluid -->
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
  <button type="submit" class="btn btn-{{setting('theme_color')}}" ><i class="fa fa-save"></i> {{trans('lang.save')}} {{trans('lang.sales_return')}}</button>
  <a href="{!! route('categories.index') !!}" class="btn btn-default"><i class="fa fa-undo"></i> {{trans('lang.cancel')}}</a>
</div>