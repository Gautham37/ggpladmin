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
        <h1 class="m-0 text-dark">{{trans('lang.purchase_return_plural')}}<small class="ml-3 mr-3">|</small><small>{{trans('lang.purchase_return_desc')}}</small></h1>
      </div><!-- /.col -->
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="{{url('/dashboard')}}"><i class="fa fa-dashboard"></i> {{trans('lang.dashboard')}}</a></li>
          <li class="breadcrumb-item"><a href="{!! route('purchaseReturn.index') !!}">{{trans('lang.purchase_return_plural')}}</a>
          </li>
          <li class="breadcrumb-item active">{{trans('lang.purchase_return_edit')}}</li>
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
        @can('purchaseReturn.index')
        <li class="nav-item">
          <a class="nav-link" href="{!! route('purchaseReturn.index') !!}"><i class="fa fa-list mr-2"></i>{{trans('lang.purchase_return_table')}}</a>
        </li>
        @endcan
        @can('purchaseReturn.create')
        <li class="nav-item">
          <a class="nav-link" id="new-invoice" href="{!! route('purchaseReturn.create') !!}"><i class="fa fa-plus mr-2"></i>{{trans('lang.purchase_return_create')}}</a>
        </li>
        @endcan
        <li class="nav-item">
          <a class="nav-link active" href="{!! url()->current() !!}"><i class="fa fa-pencil mr-2"></i>{{trans('lang.purchase_return_edit')}}</a>
        </li>
      </ul>
    </div>
    
    <div class="card-body">
      {!! Form::model($purchase_return, ['route' => ['purchaseReturn.update', $purchase_return->id], 'method' => 'patch', 'id' => 'purchaseReturnForm']) !!}
      <div class="row">
        @include('purchase_return.edit_fields')
      </div>
      {!! Form::close() !!}
      <div class="clearfix"></div>
    </div>

  </div>
</div>

<!-- Modal -->
<div id="productModal" class="modal fade" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-lg">
    {!! Form::open([ 'id' => 'productForm']) !!}  
    <!-- Modal content-->
    <div class="modal-content products-modal">

    </div>
    {!! Form::close() !!}
  </div>
</div> 


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

<script>

function addItem() {
    var party = $('#purchase_party').val();
    if(party > 0) {
      $('#productModal').modal('show');
      $.ajax({
          url: "{{ url('/purchaseReturn/loadPurchaseProducts') }}",
          type: 'post',
          data: {
            'party'  : party,
            '_token' : "{{ csrf_token() }}"
          }
      })
      .done(function(response) {
          $('.products-modal').html(response);
      })
      .fail(function(response) {
          console.log(response);
      });
    } else {
      /*swal("Please Select Customer");*/
      iziToast.warning({title: 'Warning', position: 'topRight', message: 'Please Select the Party'});
      $('#productModal').hide();  
      //$('#productModal').addClass('hide');
    }
  }

  function addItembyBarcode() {
    var party = $('#purchase_party').val();
    if(party > 0) {
      $('#productModal').modal('show');
      $.ajax({
          url: "{{ url('/purchaseReturn/loadPurchaseProductsbyBarcode') }}",
          type: 'post',
          data: {
            'party'  : party,
            '_token' : "{{ csrf_token() }}"
          }
      })
      .done(function(response) {
          $('.products-modal').html(response);
      })
      .fail(function(response) {
          console.log(response);
      });
    } else {
      /*swal("Please Select Customer");*/
      iziToast.warning({title: 'Warning', position: 'topRight', message: 'Please Select the Party'});
      $('#productModal').hide();  
      //$('#productModal').addClass('hide');
    }
  }

  $("document").ready(function(){
      $.ajax({
          url: "{{ url('/purchaseReturn/loadPurchaseDetailItems') }}",
          type: 'post',
          dataType: 'JSON',
          data: {
            '_token' : "{{ csrf_token() }}",
            'purchase_id' : "{{$purchase_return->id}}"
          }
      })
      .done(function(results) {

          $.each(results, function (key,val) { 

              var tax_options = '<option selected="selected" disabled="disabled">Choose</option> <option value="0">None</option>';
              $.each(val.tax_rates, function (keys,vals) {
                  if(val.purchase_detail_tax_percent == vals.rate) { var status='selected'; } else { var status=''; }
                  tax_options+='<option '+status+' value="'+vals.rate+'">'+vals.name+'</option>';
              });

              $('#purchaseItems tr:last').after('<tr> <input type="hidden" name="purchase_detail_id[]" value="'+val.id+'"> <input type="hidden" name="product_id[]" value="'+val.purchase_detail_product_id+'"> <input type="hidden" name="product_unit[]" value="'+val.purchase_detail_unit+'"><td>'+val.s_no+'</td><td><input type="text" name="product_name[]" class="form-control" value="'+val.purchase_detail_product_name+'"></td><td><input type="text" name="product_hsn[]" class="form-control" value="'+val.purchase_detail_product_hsn_code+'"></td><td><input type="text" name="product_mrp[]" class="form-control" value="'+(val.purchase_detail_mrp).toFixed(2)+'"></td><td class="inline-flex"><input type="text" name="product_quantity[]" class="form-control product_quantity" value="'+val.purchase_detail_quantity+'"><span class="item-unit">'+val.purchase_detail_unit+'</span></td><td><input type="text" name="product_price[]" class="form-control product_price" value="'+(val.purchase_detail_price).toFixed(2)+'"></td><td><div class="input-group"><span class="input-group-addon">%</span> <input type="text" name="product_discount_percent[]" class="form-control product_discount_percent" value="'+val.purchase_detail_discount_percent+'"></div><div class="input-group"><span class="input-group-addon">₹</span> <input type="text" name="product_discount_price[]" class="form-control product_discount_price" value="'+val.purchase_detail_discount_amount+'"></div></td><td><div class="input-group"> <select name="product_tax[]" class="form-control product_tax"> '+tax_options+' </select><input type="hidden" class="product_sgst_per" name="product_sgst_per[]" value="'+val.purchase_detail_sgst_percent+'"><input type="hidden" name="product_cgst_per[]" class="product_cgst_per" value="'+val.purchase_detail_cgst_percent+'"><input type="hidden" class="product_sgst_pri" name="product_sgst_pri[]" value="'+val.purchase_detail_sgst+'"><input type="hidden" name="product_cgst_pri[]" class="product_cgst_pri" value="'+val.purchase_detail_cgst+'"></div></td><td><input type="text" name="amount[]" class="form-control product_subtotal" value="'+val.purchase_detail_amount+'"></td><td><a onclick="removeEditRow('+val.id+',this);" title="Remove"><i class="fa fa-trash"></i></a></td></tr>');
          });
          totalProducts();

      })
      .fail(function(results) {
          console.log(results);
      });
  });



  $("document").ready(function(){
      $("#productForm").submit(function(e){
          $('.add-items').attr("disabled", true);
          $('.add-items').html('Adding...');
          e.preventDefault();
          $.ajax({
              url: "{{ url('/purchaseReturn/loadPurchaseItems') }}",
              type: 'post',
              dataType: 'JSON',
              data: $('#productForm').serialize()
          })
          .done(function(results) {

              $.each(results, function (key,val) { 

                  var tax_options = '<option selected="selected" disabled="disabled">Choose</option> <option value="0">None</option>';
                  $.each(val.tax_rates, function (keys,vals) {
                      if(val.tax == vals.rate) { var status='selected'; } else { var status=''; }
                      tax_options+='<option '+status+' value="'+vals.rate+'">'+vals.name+'</option>';
                  });

                  var total_amount = parseFloat(val.quantity) * parseFloat(val.purchase_price);
                  $('#purchaseItems tr:last').after('<tr> <input type="hidden" name="purchase_detail_id[]" value="0"> <input type="hidden" name="product_id[]" value="'+val.id+'"> <input type="hidden" name="product_unit[]" value="'+val.unit+'"><td>'+val.s_no+'</td><td><input type="text" name="product_name[]" class="form-control" value="'+val.name+'"></td><td><input type="text" name="product_hsn[]" class="form-control" value="'+val.hsn_code+'"></td><td><input type="text" name="product_mrp[]" class="form-control" value="'+(val.price).toFixed(2)+'"></td><td class="inline-flex"><input type="text" name="product_quantity[]" class="form-control product_quantity" value="'+val.quantity+'"> <span class="item-unit">'+val.unit+'</span></td><td><input type="text" name="product_price[]" class="form-control product_price" value="'+(val.purchase_price).toFixed(2)+'"></td><td><div class="input-group"><span class="input-group-addon">%</span> <input type="text" name="product_discount_percent[]" class="form-control product_discount_percent" value="0"></div><div class="input-group"><span class="input-group-addon">₹</span> <input type="text" name="product_discount_price[]" class="form-control product_discount_price" value="0"></div></td><td><div class="input-group"> <select name="product_tax[]" class="form-control product_tax"> '+tax_options+' </select><input type="hidden" class="product_sgst_per" name="product_sgst_per[]"><input type="hidden" name="product_cgst_per[]" class="product_cgst_per"><input type="hidden" class="product_sgst_pri" name="product_sgst_pri[]"><input type="hidden" name="product_cgst_pri[]" class="product_cgst_pri"></div></td><td><input type="text" name="amount[]" class="form-control product_subtotal" value="'+total_amount+'"></td><td><a onclick="removePurchaseeRow(this);" title="Remove"><i class="fa fa-trash"></i></a></td></tr>');
                  $('.product_tax').trigger('change');
              });
              $('#productModal').modal('hide');
              $('.products-modal').html('');
              totalProducts();

          })
          .fail(function(results) {
              console.log(results);
          });
      });
  }); 

  $(document).ready(function() {
     var select = jQuery("select[id='purchase_party']");
     select.change();
  });

</script>

@endpush