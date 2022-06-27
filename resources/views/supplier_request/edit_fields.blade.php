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
        
        <div class="col-md-12" style="margin-top:-25px;">
        
        {!! Form::label('sr_party', trans("lang.supplier_request_party"),['class' => 'control-label text-right']) !!}
        {!! Form::select('sr_party', $markets, $marketSelected, ['class' => 'select2 form-control', 'onchange' => 'getPartyDetail(this)']) !!}
        <!--<div class="form-text text-muted">{{ trans("lang.supplier_request_customer_help") }}</div>-->
        </div>
        
        <div class="col-md-12">
            <div class="party_details mt-15 mb-15 col-md-12"></div>
        </div>
        
        </div>
      </div><!-- /.col -->
      <div class="col-md-4 mt-15">

          <div class="form-group row">
            {!! Form::label('sr_code', trans("lang.supplier_request_no"), ['class' => 'label-left col-md-5 control-label text-left']) !!}
            {!! Form::text('sr_code', $supplier_request->sr_code,  ['class' => 'col-md-7 form-control']) !!}
            <!--<div class="form-text text-muted">{{ trans("lang.supplier_request_no_help") }}</div>-->
          </div>

          <div class="form-group row">
            {!! Form::label('sr_date', trans("lang.supplier_request_date"), ['class' => 'label-left col-md-5 control-label text-left']) !!}
            {!! Form::text('sr_date', $supplier_request->sr_date,  ['class' => 'col-md-7 form-control datepicker', 'readonly' => 'readonly']) !!}
            <!--<div class="form-text text-muted">{{ trans("lang.supplier_request_date_help") }}</div>-->
          </div>

          <div class="form-group row">
            {!! Form::label('sr_valid_date', trans("lang.supplier_request_valid_date"), ['class' => 'label-left col-md-5 control-label text-left']) !!}
            {!! Form::text('sr_valid_date', $supplier_request->sr_valid_date,  ['class' => 'col-md-7 form-control datepicker', 'readonly' => 'readonly']) !!}
            <!--<div class="form-text text-muted">{{ trans("lang.supplier_request_valid_date_help") }}</div>-->
          </div>
      
      </div><!-- /.col -->

      <div class="col-md-12">
          <table id="salesItems" class="table table-bordered tbl-product-details">
            <thead>
              <tr>
                <th>NO</th>
                <th>ITEMS</th>
                <th>HSN</th>
                <th>MRP</th>
                <th>QTY</th>
                
                <th>TOTAL</th>
                <th>ACTION</th>
              </tr>
            </thead>

            <tfoot class="text-center">
              <tr>

                <th colspan="7">
                  <button onclick="addItem();" data-toggle="tooltip" title="{{ trans('lang.shift_m') }}" type="button" data-toggle="tooltip" class="btn btn-primary text-center add-item-btn-custom">
                    <i class="fa fa-plus"></i> 
                    &nbsp; Add Item
                  </button>
                </th>

              </tr>
            </tfoot>
            
          </table>

      </div>

      <div class="col-md-7">
        
        {!! Form::label('description', trans("lang.po_description"), ['class' => 'control-label text-right']) !!}
        {!! Form::textarea('description', $supplier_request->sr_notes, ['class' => 'form-control','placeholder'=>
               trans("lang.po_description_placeholder")  ]) !!}
        <!--<div class="form-text text-muted">{{ trans("lang.po_description_help") }}</div>-->



      </div>  

      <div class="col-md-5">
          <table class="table table-bordered">
              <?php /* ?>
            <tr>
              <td class="text-left">Taxable Amount</td>
              <td class="text-right">
                
                <div class="input-group">
                  <span class="input-group-addon">₹</span>
                  {!! Form::text('taxable_amount', $supplier_request->sr_taxable_amount,  ['class' => 'form-control taxable_amount']) !!}
                </div>
                <div class="form-text text-muted">{{ trans("lang.po_taxable_amount") }}</div>

              </td>
            </tr><?php /*/ ?>

            <tr>
              <td class="text-right" style="width:30%;">Status</td>
              <td class="text-right">
                
                <div class="input-group">
                  <!--<span class="input-group-addon"></span>-->
                  {!! Form::select('sr_status', ['0' => 'Reject', '1' => 'Pending', '2' => 'Approve'], $supplier_request->sr_status, ['class' => 'form-control','id'=>'sr_status']) !!}
                </div>

              </td>
            </tr>
            
            <tr class="driver-section" style="display:none;">
              <td class="text-right">Driver</td>
              <td class="text-right">
                
                <div class="input-group">
                  <!--<span class="input-group-addon"></span>-->
                  {!! Form::select('sr_driver', $driver, $supplier_request->sr_driver, ['class' => ' form-control', 'id' => 'driver']) !!}
                </div>

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
  <button type="submit" class="btn btn-{{setting('theme_color')}}" ><i class="fa fa-save"></i> {{trans('lang.save')}} {{trans('lang.supplier_request')}}</button>
  <a href="{!! route('categories.index') !!}" class="btn btn-default"><i class="fa fa-undo"></i> {{trans('lang.cancel')}}</a>
</div>