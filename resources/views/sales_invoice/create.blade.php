@extends('layouts.app')
@push('css_lib')
<!-- iCheck -->
<link rel="stylesheet" href="{{asset('plugins/iCheck/flat/blue.css')}}">
<!-- select2 -->
<link rel="stylesheet" href="{{asset('plugins/select2/select2.min.css')}}">
<!-- bootstrap wysihtml5 - text editor -->
<link rel="stylesheet" href="{{asset('plugins/summernote/summernote-bs4.css')}}">
{{--dropzone--}}
<link rel="stylesheet" href="{{asset('plugins/dropzone/bootstrap.min.css')}}">
@endpush


@section('content')

<!-- Content Header (Page header) -->
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0 text-dark">Sales Invoice<small class="ml-3 mr-3">|</small><small>Sales Invoice Management</small></h1>
      </div><!-- /.col -->
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="{{url('/dashboard')}}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
          <li class="breadcrumb-item"><a href="{!! route('salesInvoice.index') !!}">Sales Invoices</a></li>
          <li class="breadcrumb-item active">Create Sales Invoice</li>
        </ol>
      </div><!-- /.col -->
    </div><!-- /.row -->
  </div><!-- /.container-fluid -->
</div>

<!-- /.content-header -->
<div class="content">
  <div class="clearfix"></div>
  @include('flash::message')
  @include('adminlte-templates::common.errors')
  <div class="clearfix"></div>
  <div class="card">
    <div class="card-header">
      <ul class="nav nav-tabs align-items-end card-header-tabs w-100">
        @can('salesInvoice.index')
        <li class="nav-item">
          <a class="nav-link" href="{!! route('salesInvoice.index') !!}"><i class="fa fa-list mr-2"></i>Sales Invoice List</a>
        </li>
        @endcan
        <li class="nav-item">
          <a class="nav-link active" href="{!! url()->current() !!}"><i class="fa fa-plus mr-2"></i>Create Sales Invoice</a>
        </li>
      </ul>
    </div>
    <div class="card-body">
        {!! Form::open(['route' => 'salesInvoice.store','class' => 'sales-invoice-form']) !!}
            <div class="row">
                @include('sales_invoice.fields')
            </div>
        {!! Form::close() !!}
      <div class="clearfix"></div>
    </div>
  </div>
</div>


<!--Cart Modal -->
<div id="cartModal" class="modal fade" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-lg">
      <div class="modal-content cart-items"></div>
  </div>
</div>

@include('layouts.modal.product_modal')
@include('layouts.modal.party_modal')


<?php /* ?>@include('layouts.products_modal')<?php */ ?>
@include('layouts.media_modal')
@endsection
@push('scripts_lib')
<!-- iCheck -->
<script src="{{asset('plugins/iCheck/icheck.min.js')}}"></script>
<!-- select2 -->
<script src="{{asset('plugins/select2/select2.min.js')}}"></script>
<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<script src="{{asset('plugins/summernote/summernote-bs4.min.js')}}"></script>
{{--dropzone--}}
<script src="{{asset('plugins/dropzone/dropzone.js')}}"></script>
<script type="text/javascript">
    Dropzone.autoDiscover = false;
    var dropzoneFields = [];
</script>

<!-- <script>

  var invoice_items = [];

  function cartarrayToTable() {
    var in_html = '';
    var i     = 0;
    $(".sales-invoice-items").html('');
    $.each(invoice_items, function(key,value) { 
      i++;

      if(parseFloat(value.quantity) > 0 && parseFloat(value.unit_price) > 0) {         
          if(value.tax_type == 'exclusive'){             
             total_amount   = parseFloat(value.unit_price) * parseFloat(value.quantity) - parseFloat(value.discount_amount);
             gst_amount     = parseFloat(total_amount) / 100 * parseFloat(value.tax);
             amount         = parseFloat(total_amount) + parseFloat(gst_amount); 
          } else {             
             amount     = parseFloat(value.unit_price) * parseFloat(value.quantity) - parseFloat(value.discount_amount);
             gst_amount = 0;
             value.tax  = 0;
          }
      } else {
          amount     = 0;
          gst_amount = 0;
      }

      value.amount      = parseFloat(amount).toFixed(2);
      value.tax_amount  = parseFloat(gst_amount).toFixed(2);
      $("#tax_amount_" + key).val(parseFloat(gst_amount).toFixed(2));
      $("#amount_" + key).val(parseFloat(amount).toFixed(2));


      in_html += '<tr>';
      in_html += '<td>'+i+'</td>';
      in_html += '<td>';
      in_html += '<input type="hidden" name="product_id[]" value="'+value.product_id+'">';
      in_html += '<input type="hidden" name="unit[]" value="'+value.unit+'">';
      in_html += '<input type="text" class="form-control" value="'+value.product_name+'">';
      in_html += '</td>';
      in_html += '<td>';
      in_html += '<input type="text" name="product_hsn_code[]" class="form-control" value="'+value.product_hsn_code+'">';
      in_html += '</td>';
      in_html += '<td>';
      in_html += '<input type="text" name="mrp[]" id="mrp_' + key + '" index="' + key + '" field="description" class="form-control change_info" value="' + value.mrp + '">';
      in_html += '</td>';
      in_html += '<td class="inline-flex">';
      in_html += '<input type="text" name="quantity[]" id="quantity_' + key + '" index="' + key + '" field="quantity" class="form-control change_info" value="'+value.quantity+'">';
      in_html += '<span class="item-unit">'+value.unit_name+'</span>';
      in_html += '</td>';
      in_html += '<td>';
      in_html += '<input type="text" name="unit_price[]" id="unit_price_' + key + '" index="' + key + '" field="unit_price" class="form-control change_info" value="'+value.unit_price+'">';
      in_html += '</td>';
      in_html += '<td>';
      in_html += '<div class="input-group">';
      in_html += '<span class="input-group-addon">%</span>';
      in_html += '<input type="text" name="discount[]" id="discount_' + key + '" index="' + key + '" field="discount" class="form-control change_info" value="'+value.discount+'">';
      in_html += '</div>';
      in_html += '<div class="input-group">';
      in_html += '<span class="input-group-addon">{{setting('default_currency')}}</span>';
      in_html += '<input type="text" name="discount_amount[]" id="discount_amount_' + key + '" index="' + key + '" field="discount_amount" class="form-control change_info discount_amount" value="'+value.discount_amount+'">';
      in_html += '</div>';
      in_html += '</td>';
      in_html += '<td>';
      in_html += '<div class="input-group">';
      in_html += '<span class="input-group-addon">%</span>';
      in_html += '<input type="text" name="tax[]" id="tax_' + key + '" index="' + key + '" field="tax" class="form-control change_info" value="'+value.tax+'">';
      in_html += '<input type="hidden" name="tax_amount[]" id="tax_amount_' + key + '" index="' + key + '" field="tax_amount" class="form-control change_info tax_amount" value="'+value.tax_amount+'">';
      in_html += '</div>';
      in_html += '</td>';
      in_html += '<td>';
      in_html += '<input type="text" name="amount[]" id="amount_' + key + '" index="' + key + '" field="amount" class="form-control change_info amount" value="'+value.amount+'">';
      in_html += '</td>';
      in_html += '<td>';
      in_html += '<a title="Delete" class="delete btn btn-sm btn-danger text-white" index="'+key+'"><i class="fa fa-remove"></i></a>';
      in_html += '</td>';
      in_html += '</tr>'; 
    });
    $(".sales-invoice-items").html(in_html);
    calculateInvoiceTotals();
  }

  $(document).on('keyup','.change_info',function (e) { 
    var field = $(this).attr('field');
    var index = $(this).attr('index');
    var val = $(this).val();
    
    if(field == 'quantity'){
        if(val != undefined && val!=''){
            invoice_items[index].quantity = val;
            $("#quantity_" + index).val(val);
        } else {
            invoice_items[index].quantity = '';
            $("#quantity_" + index).val('');
        }
    } else if(field == 'unit_price') {
        if(val != undefined && val!=''){
            invoice_items[index].unit_price = val;
            $("#unit_price_" + index).val(val);
        } else {
            invoice_items[index].unit_price = '';
            $("#unit_price_" + index).val('');
        }
    } else if(field == 'discount') {
        if(val != undefined && val!=''){
            invoice_items[index].discount = val;
            $("#discount_" + index).val(val);

            total           = parseFloat(invoice_items[index].unit_price) * parseFloat(invoice_items[index].quantity);
            discount_amount = parseFloat(total) / 100 * parseFloat(invoice_items[index].discount);
            invoice_items[index].discount_amount = (parseFloat(discount_amount)).toFixed(2); 

        } else {
            invoice_items[index].discount = '';
            $("#discount_" + index).val('');
        }
    } else if(field == 'discount_amount') {
        if(val != undefined && val!=''){
            invoice_items[index].discount_amount = val;
            $("#discount_amount_" + index).val(val);

            var total            = parseFloat(invoice_items[index].unit_price) * parseFloat(invoice_items[index].quantity);
            var discount_percent = (parseFloat(total) - (parseFloat(total) - parseFloat(val))) / parseFloat(total) * 100;
            invoice_items[index].discount = (parseFloat(discount_percent)).toFixed(2);  

        } else {
            invoice_items[index].discount_amount = '';
            $("#discount_amount_" + index).val('');
        }
    } else if(field == 'tax') {
        if(val != undefined && val!=''){
            invoice_items[index].tax = val;
            $("#tax_" + index).val(val);
        } else {
            invoice_items[index].tax = '';
            $("#tax_" + index).val('');
        }
    }

    setTimeout(function() {
        cartarrayToTable();
    },300);
    e.preventDefault();
  });

  $(document).ready(function() {
     var select = jQuery("select[id='market_id']");
     select.change();
  });

</script> -->
@endpush