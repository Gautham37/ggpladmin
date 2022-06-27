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

  <div class="container custom-from-css">
    <div class="row" style="margin-top:20px;">
      @include('layouts.invoice_header')
      <div class="col-md-8 mt-15" data-toggle="tooltip" title="{{trans('lang.shift_y')}}">
        <div class="row"> 

          <div class="col-md-12">
            
          </div>
          <div class="col-md-12" style="margin-top:-35px;">
        {!! Form::label('purchase_party', trans("lang.purchase_order_party"),['class' => 'label-left control-label text-left']) !!}
        {!! Form::select('purchase_party', $markets, $marketSelected, ['class' => 'width-100 select2 form-control', 'onchange' => 'getPartyDetail(this)']) !!}
        <!-- <div class="form-text text-muted">{{ trans("lang.purchase_order_party_help") }}</div> -->
        </div>

        <div class="col-md-12">
          <div class="party_details mt-15 mb-15 col-md-12"></div>
        </div>
        
        </div>
      </div><!-- /.col -->
      
      <div class="col-md-4 mt-15">

          <div class="form-group row">
            {!! Form::label('purchase_code', trans("lang.purchase_order_po_no"), ['class' => 'label-left col-md-4 control-label text-left']) !!}
            {!! Form::text('purchase_code', $purchase_order_no,  ['class' => 'col-md-8 form-control']) !!}
            <!-- <div class="form-text text-muted">{{ trans("lang.purchase_order_po_no_help") }}</div> -->
          </div>

          <div class="form-group row">
            {!! Form::label('purchase_date', trans("lang.purchase_order_po_date"), ['class' => 'label-left col-md-4 control-label text-left']) !!}
            {!! Form::text('purchase_date', date('Y-m-d'),  ['class' => 'col-md-8 form-control datepicker', 'readonly' => 'readonly']) !!}
            <!-- <div class="form-text text-muted">{{ trans("lang.purchase_order_po_date_help") }}</div> -->
          </div>

          <div class="form-group row">
            {!! Form::label('valid_date', trans("lang.po_valid_date"), ['class' => 'label-left col-md-4 control-label text-left']) !!}
            {!! Form::text('valid_date', date('Y-m-d', time() + (86400 * 15)),  ['class' => 'col-md-8 form-control datepicker', 'readonly' => 'readonly']) !!}
            <!-- <div class="form-text text-muted">{{ trans("lang.po_valid_date_help") }}</div> -->
          </div>
      
      </div><!-- /.col -->

      <div class="col-md-12">
          <table id="purchaseItems" class="table table-bordered tbl-product-details">
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
                  {!! Form::text('total_discount', null,  ['class' => 'form-control total_discount', 'readonly' => 'readonly']) !!}
                </div>
              </th>
              <th>
                <div class="input-group">
                  <span class="input-group-addon">₹</span>
                  {!! Form::text('total_tax', null,  ['class' => 'form-control total_tax', 'readonly' => 'readonly']) !!}
                </div>
              </th>
              <th>
                <div class="input-group">
                  <span class="input-group-addon">₹</span>
                  {!! Form::text('total_amount', null,  ['class' => 'form-control total_amount', 'readonly' => 'readonly']) !!}
                </div>
              </th>
              <th></th>
            </tfoot>

            <tfoot class="text-center">
              <tr>
                <th colspan="8">
                  <button onclick="addItem();" data-toggle="tooltip" title="{{ trans('lang.shift_m') }}" type="button" data-toggle="tooltip" class="btn btn-primary text-center add-item-btn-custom">
                    <i class="fa fa-plus"></i> 
                    &nbsp; Add Purchase Item
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
      <div class="col-md-4">

        {!! Form::label('description', trans("lang.po_description"), ['class' => 'control-label text-right']) !!}
        {!! Form::textarea('description', null, ['class' => 'form-control','placeholder'=>
               trans("lang.po_description_placeholder")  ]) !!}
        <!-- <div class="form-text text-muted">{{ trans("lang.po_description_help") }}</div> -->

      </div>
      <div class="col-md-4"> 

        {!! Form::label('terms_and_conditions', trans("lang.po_terms_and_conditions"), ['class' => 'control-label text-right']) !!}
        {!! Form::textarea('terms_and_conditions', null, ['class' => 'form-control','placeholder'=>
               trans("lang.po_terms_and_conditions_placeholder")  ]) !!}
        <!-- <div class="form-text text-muted">{{ trans("lang.po_terms_and_conditions_help") }}</div> -->

      </div>



      <div class="col-md-4">
          <table class="table table-bordered">
            <tr>
              <td class="text-right" style="width:50%;">Taxable Amount</td>
              <td class="text-right">
                
                <div class="input-group">
                  <span class="input-group-addon">₹</span>
                  {!! Form::text('taxable_amount', null,  ['class' => 'form-control taxable_amount']) !!}
                </div>
                <!-- <div class="form-text text-muted">{{ trans("lang.po_taxable_amount") }}</div> -->

              </td>
            </tr>

            <tr>
              <td class="text-right">SGST <span class="sgst_per"></span></td>
              <td class="text-right">
                <div class="input-group">
                  <span class="input-group-addon">₹</span>
                  {!! Form::text('sgst_amount', null,  ['class' => 'form-control sgst_amount']) !!}
                </div>
                <!-- <div class="form-text text-muted">{{ trans("lang.po_sgst_amount") }}</div> -->
              </td>
            </tr>

            <tr>
              <td class="text-right">CGST <span class="cgst_per"></span></td>
              <td class="text-right">
                <div class="input-group">
                  <span class="input-group-addon">₹</span>
                  {!! Form::text('cgst_amount', null,  ['class' => 'form-control cgst_amount']) !!}
                </div>
                <!-- <div class="form-text text-muted">{{ trans("lang.po_cgst_amount") }}</div> -->
              </td>
            </tr>


            <tr>
              <tr>
                <td class="text-right">
                  Additional Charges
                  <!-- <div class="form-text text-muted">{{ trans("lang.po_additional_charge_detail_help") }}</div> -->
                </td>
                <td class="text-right">
                    <div class="input-group">
                      <span class="input-group-addon">₹</span>
                      {!! Form::text('additional_charges', null,  ['class' => 'col-md-12 form-control additional_charges', 'id' => 'additional_charges']) !!}
                    </div>
                    <!-- <div class="form-text text-muted">{{ trans("lang.po_additional_charge_help") }}</div> -->
                </td>
              </tr>
              <tr>
                <td class="text-right">
                  Additional Charges Description
                  <!-- <div class="form-text text-muted">{{ trans("lang.po_additional_charge_detail_help") }}</div> -->
                </td>
                <td class="text-right">
                    <div class="input-group">
                      {!! Form::text('additional_charge_detail', null,  ['class' => 'col-md-12 form-control additional_charge_detail']) !!}
                    </div>
                    <!-- <div class="form-text text-muted">{{ trans("lang.po_additional_charge_detail_help") }}</div> -->
                </td>
              </tr>
              
            </tr>
            <tr>
              <td class="text-right">Total Amount</td>
              <td class="text-right">
                  <div class="input-group">
                    <span class="input-group-addon">₹</span>
                      {!! Form::text('total_purchase_amount', null,  ['class' => 'form-control', 'id' => 'total_purchase_amount']) !!}
                  </div>  
                  <!-- <div class="form-text text-muted">{{ trans("lang.po_total_amount_help") }}</div> -->
              </td>
            </tr>
            <!-- <tr>
              <td class="text-left">Cash Paid</td>
              <td class="text-right">
                  <span> Mark as Fully Paid  <input type="checkbox" name="mark_as_paid" id="mark_as_paid" value="1"></span>
                  <div class="input-group">
                    <span class="input-group-addon">₹</span>
                        {!! Form::text('cash_paid', null,  ['class' => 'form-control cash_paid', 'id' => 'cash_paid']) !!}
                  </div>
                  <div class="form-text text-muted">{{ trans("lang.po_cash_paid_help") }}</div>
              </td>
            </tr>
            <tr>
              <td class="text-left">Balance Amount</td>
              <td class="text-right">
                  <div class="input-group">
                    <span class="input-group-addon">₹</span>
                    {!! Form::text('balance_amount', null,  ['class' => 'form-control balance_amount', 'id' => 'balance_amount']) !!}
                  </div>
                  <div class="form-text text-muted">{{ trans("lang.po_balance_amount_help") }}</div>
              </td>
            </tr> -->
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
  <button type="submit" data-toggle="tooltip" title="{{ trans('lang.alt_enter') }}" class="btn btn-{{setting('theme_color')}} save-order" ><i class="fa fa-save"></i> {{trans('lang.save')}} {{trans('lang.purchase_order')}}</button>
  <a href="{!! route('categories.index') !!}" class="btn btn-default"><i class="fa fa-undo"></i> {{trans('lang.cancel')}}</a>
</div>