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
        <h1 class="m-0 text-dark">{{trans('lang.supplier_request_plural')}}<small class="ml-3 mr-3">|</small><small>{{trans('lang.supplier_request_desc')}}</small></h1>
      </div><!-- /.col -->
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="{{url('/dashboard')}}"><i class="fa fa-dashboard"></i> {{trans('lang.dashboard')}}</a></li>
          <li class="breadcrumb-item"><a href="{!! route('supplierRequest.index') !!}">{{trans('lang.supplier_request_plural')}}</a>
          </li>
          <li class="breadcrumb-item active">{{trans('lang.supplier_request_edit')}}</li>
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
        @can('supplierRequest.index')
        <li class="nav-item">
          <a class="nav-link" href="{!! route('supplierRequest.index') !!}"><i class="fa fa-list mr-2"></i>{{trans('lang.supplier_request_table')}}</a>
        </li>
        @endcan
        @can('supplierRequest.create')
        <li class="nav-item">
          <a class="nav-link" href="{!! route('supplierRequest.create') !!}"><i class="fa fa-plus mr-2"></i>{{trans('lang.supplier_request_create')}}</a>
        </li>
        @endcan
        <li class="nav-item">
          <a class="nav-link active" href="{!! url()->current() !!}"><i class="fa fa-pencil mr-2"></i>{{trans('lang.supplier_request_edit')}}</a>
        </li>
      </ul>
    </div>
    
    <div class="card-body">
      {!! Form::model($supplier_request, ['route' => ['supplierRequest.update', $supplier_request->id], 'method' => 'patch', 'id' => 'supplierRequestForm']) !!}
      <div class="row">
        @include('supplier_request.edit_fields')
      </div>
      {!! Form::close() !!}
      <div class="clearfix"></div>
    </div>

  </div>
</div>

<!-- Modal -->
<div id="productModal" class="modal fade" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-lg">
    {!! Form::open([ 'id' => 'productForm', 'class' => 'po-modal-form']) !!}  
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
    var party = $('#sr_party').val();
     if(party > 0) {
      $('#productModal').modal('show');
      $.ajax({
          url: "{{ url('/supplierRequest/loadSupplierRequestProducts') }}",
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
          $('.add-items').attr("disabled", true);
          $('.add-items').html('Adding...');
          e.preventDefault();
          $.ajax({
              url: "{{ url('/supplierRequest/loadSupplierRequestItems') }}",
              type: 'post',
              dataType: 'JSON',
              data: $('#productForm').serialize()
          })
          .done(function(results) {

              $.each(results, function (key,val) { 

                  var total_amount = parseFloat(val.quantity) * parseFloat(val.purchase_price);
                  $('#salesItems tr:last').after('<tr> <input type="hidden" name="product_id[]" value="'+val.id+'"><input type="hidden" name="product_unit[]" value="'+val.unit+'"><td>'+val.s_no+'</td><td><input type="text" name="product_name[]" class="form-control" value="'+val.name+'"></td><td><input type="text" name="product_hsn[]" class="form-control" value="'+val.hsn_code+'"></td><td><input type="text" name="product_mrp[]" class="form-control" value="'+(val.purchase_price).toFixed(2)+'"></td><td class="inline-flex"><input type="text" name="product_quantity[]" class="form-control product_quantity" value="'+val.quantity+'"> <span class="item-unit">'+val.unit+'</span></td><td><input type="text" name="amount[]" class="form-control product_subtotal" value="'+total_amount+'"></td><td><a onclick="removesaleseRow(this);" title="Remove"><i class="fa fa-trash"></i></a></td></tr>');
                /*  $('#salesItems tr:last').after('<tr> <input type="hidden" name="product_id[]" value="'+val.id+'"><input type="hidden" name="product_unit[]" value="'+val.unit+'"><td>'+val.s_no+'</td><td><input type="text" name="product_name[]" class="form-control" value="'+val.name+'"></td><td><input type="text" name="product_hsn[]" class="form-control" value="'+val.hsn_code+'"></td><td><input type="text" name="product_mrp[]" class="form-control" value="'+(val.purchase_price).toFixed(2)+'"></td><td class="inline-flex"><input type="text" name="product_quantity[]" class="form-control product_quantity" value="'+val.quantity+'"> <span class="item-unit">'+val.unit+'</span></td><td><a onclick="removesaleseRow(this);" title="Remove"><i class="fa fa-trash"></i></a></td></tr>');*/
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

  $("document").ready(function(){
      $.ajax({
          url: "{{ url('/supplierRequest/loadSupplierRequestDetailItems') }}",
          type: 'post',
          dataType: 'JSON',
          data: {
            '_token' : "{{ csrf_token() }}",
            'sales_id' : "{{$supplier_request->id}}"
          }
      })
      .done(function(results) {

          $.each(results, function (key,val) { 

              /*$('#salesItems tr:last').after('<tr> <input type="hidden" name="sr_detail_id[]" value="'+val.id+'"> <input type="hidden" name="product_id[]" value="'+val.sr_detail_product_id+'"><input type="hidden" name="product_unit[]" value="'+val.sr_detail_unit+'"><td>'+val.s_no+'</td><td><input type="text" name="product_name[]" class="form-control" value="'+val.sr_detail_product_name+'"></td><td><input type="text" name="product_hsn[]" class="form-control" value="'+val.sr_detail_product_hsn_code+'"></td><td><input type="text" name="product_mrp[]" class="form-control" value="'+(val.sr_detail_mrp).toFixed(2)+'"></td><td class="inline-flex"><input type="text" name="product_quantity[]" class="form-control product_quantity" value="'+val.sr_detail_quantity+'"><span class="item-unit">'+val.sr_detail_unit+'</span></td><td><input type="text" name="product_price[]" class="form-control product_price" value="'+(val.sr_detail_price).toFixed(2)+'"></td><td><input type="text" name="amount[]" class="form-control product_subtotal" value="'+(val.sr_detail_price * val.sr_detail_quantity).toFixed(2) +'"></td><td><a onclick="removesaleseRow(this);" title="Remove"><i class="fa fa-trash"></i></a></td></tr>');*/
              var pro_image = (val.farmer_product_image!='') ? '<a target="_blank" href="'+val.farmer_product_image+'" title="View Uploaded Image"><i class="fa fa-eye"></i></a>' : '' ;
              $('#salesItems tr:last').after('<tr> <input type="hidden" name="sr_detail_id[]" value="'+val.id+'"> <input type="hidden" name="product_id[]" value="'+val.sr_detail_product_id+'"><input type="hidden" name="product_unit[]" value="'+val.sr_detail_unit+'"><td>'+val.s_no+'</td><td><input type="text" name="product_name[]" class="form-control" value="'+val.sr_detail_product_name+'"></td><td><input type="text" name="product_hsn[]" class="form-control" value="'+val.sr_detail_product_hsn_code+'"></td><td><input type="text" name="product_mrp[]" class="form-control" value="'+(val.sr_detail_mrp).toFixed(2)+'"></td><td class="inline-flex"><input type="text" name="product_quantity[]" class="form-control product_quantity" value="'+val.sr_detail_quantity+'"><span class="item-unit">'+val.sr_detail_unit+'</span></td><td><input type="text" name="amount[]" class="form-control product_subtotal" value="'+(val.sr_amount).toFixed(2) +'"></td><td><a onclick="removeEditRow('+val.id+',this);" title="Remove"><i class="fa fa-trash"></i></a></td></tr>');
               $('.product_quantity').trigger('change');
          });
          totalProducts();

      })
      .fail(function(results) {
          console.log(results);
      });
  });
  
  $(document).ready(function() {
     var select = jQuery("select[id='sr_party']");
     select.change();
     $('#sr_status').trigger('change');
  });
  
  $('#sr_status').change(function() {
      var status = this.value;
      if(status==2) {
          $('.driver-section').show();
          $('#driver').attr("required", "true");
      } else {
          $('.driver-section').hide();
          $('#driver').attr("required", "false");
      }
  })

</script>

@endpush