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
        <h1 class="m-0 text-dark">{{trans('lang.sales_return_plural')}}<small class="ml-3 mr-3">|</small><small>{{trans('lang.sales_return_desc')}}</small></h1>
      </div><!-- /.col -->
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="{{url('/dashboard')}}"><i class="fa fa-dashboard"></i> {{trans('lang.dashboard')}}</a></li>
          <li class="breadcrumb-item"><a href="{!! route('salesReturn.index') !!}">{{trans('lang.sales_return_plural')}}</a>
          </li>
          <li class="breadcrumb-item active">{{trans('lang.sales_return_create')}}</li>
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
        @can('salesReturn.index')
        <li class="nav-item">
          <a class="nav-link" href="{!! route('salesReturn.index') !!}"><i class="fa fa-list mr-2"></i>{{trans('lang.sales_return_table')}}</a>
        </li>
        @endcan
        <li class="nav-item">
          <a class="nav-link active" href="{!! url()->current() !!}"><i class="fa fa-plus mr-2"></i>{{trans('lang.sales_return_create')}}</a>
        </li>
      </ul>
    </div>
    <div class="card-body">
      {!! Form::open(['route' => 'salesReturn.store','id' => 'salesReturnForm']) !!}
      <div class="row">
        @include('sales_return.fields')
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

<script>
  
  function addItem() {
    var party = $('#sales_party').val();
     if(party > 0) {
      $('#productModal').modal('show');
      $.ajax({
          url: "{{ url('/salesReturn/loadsalesProducts') }}",
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
      iziToast.warning({title: 'Warning', position: 'topRight', message: 'Please Select the Party'});
      $('#productModal').hide();
    }
  }

  function addItembyBarcode() {
    var party = $('#sales_party').val();
     if(party > 0) {
      $('#productModal').modal('show');
      $.ajax({
          url: "{{ url('/salesReturn/loadsalesProductsbyBarcode') }}",
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
      iziToast.warning({title: 'Warning', position: 'topRight', message: 'Please Select the Party'});
      $('#productModal').hide();
    }
  }

  $("document").ready(function(){
      $("#productForm").submit(function(e){
          e.preventDefault();
          $.ajax({
              url: "{{ url('/salesReturn/loadsalesItems') }}",
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

                  var total_amount = parseFloat(val.quantity) * parseFloat(val.price);
                  $('#salesItems tr:last').after('<tr> <input type="hidden" name="product_id[]" value="'+val.id+'"> <input type="hidden" class="form-control product_unit" name="product_unit[]" value="'+val.unit+'"><td>'+val.s_no+'</td><td><input type="text" name="product_name[]" class="form-control" value="'+val.name+'"></td><td><input type="text" name="product_hsn[]" class="form-control" value="'+val.hsn_code+'"></td><td><input type="text" name="product_mrp[]" class="form-control" value="'+(val.price).toFixed(2)+'"></td><td class="inline-flex"><input type="text" name="product_quantity[]" class="form-control product_quantity" value="'+val.quantity+'"> <span class="item-unit">'+val.unit+'</span></td><td><input type="text" name="product_price[]" class="form-control product_price" value="'+(val.price).toFixed(2)+'"></td><td><div class="input-group"><span class="input-group-addon">%</span> <input type="text" name="product_discount_percent[]" class="form-control product_discount_percent" value="0"></div><div class="input-group"><span class="input-group-addon">₹</span> <input type="text" name="product_discount_price[]" class="form-control product_discount_price" value="0"></div></td><td><div class="input-group"> <select name="product_tax[]" class="form-control product_tax">'+tax_options+'</select><input type="hidden" class="product_sgst_per" name="product_sgst_per[]"><input type="hidden" name="product_cgst_per[]" class="product_cgst_per"><input type="hidden" class="product_sgst_pri" name="product_sgst_pri[]"><input type="hidden" name="product_cgst_pri[]" class="product_cgst_pri"></div></td><td><input type="text" name="amount[]" class="form-control product_subtotal" value="'+total_amount+'" readonly="readonly"></td><td><a onclick="removesaleseRow(this);" title="Remove"><i class="fa fa-trash"></i></a></td></tr>');
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
     var select = jQuery("select[id='sales_party']");
     select.change();
  });

</script>
@endpush