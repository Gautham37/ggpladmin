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
        <h1 class="m-0 text-dark">{{trans('lang.product_plural')}}<small class="ml-3 mr-3">|</small><small>{{trans('lang.product_desc')}}</small></h1>
      </div><!-- /.col -->
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="{{url('/dashboard')}}"><i class="fa fa-dashboard"></i> {{trans('lang.dashboard')}}</a></li>
          <li class="breadcrumb-item"><a href="{!! route('products.index') !!}">{{trans('lang.product_plural')}}</a>
          </li>
          <li class="breadcrumb-item active">{{trans('lang.product_edit')}}</li>
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
        @can('products.index')
        <li class="nav-item">
          <a class="nav-link" href="{!! route('products.index') !!}"><i class="fa fa-list mr-2"></i>{{trans('lang.product_table')}}</a>
        </li>
        @endcan
        @can('products.create')
        <li class="nav-item">
          <a class="nav-link" href="{!! route('products.create') !!}"><i class="fa fa-plus mr-2"></i>{{trans('lang.product_create')}}</a>
        </li>
        @endcan
        <li class="nav-item">
          <a class="nav-link active" href="{!! url()->current() !!}"><i class="fa fa-pencil mr-2"></i>{{trans('lang.product_edit')}}</a>
        </li>
      </ul>
    </div>
    <div class="card-body">
      {!! Form::model($product, ['route' => ['products.update', $product->id], 'method' => 'patch', 'id' => 'productForm']) !!}
      <div class="row">
        @include('products.fields')
      </div>
      {!! Form::close() !!}
      <div class="clearfix"></div>
    </div>
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

    $(document).ready(function() {
        $("#dropdownss").change(function() {
            var product_id = '<?=$product->id?>';
            var selVal = $(this).val();
          
           // var drop_count = $("#dropdownss :selected").length;
            $("#textboxDiv").html('');
             $.ajax({
               type:'POST',
               url:"{{ route('products.updateProductPrice') }}",
               data:{customer_group_id:selVal,product_id:product_id,"_token": "{{ csrf_token() }}",},
               success:function(data){
                 // alert(data.success);
                 $("#textboxDiv").append(data.success);
                 $(".show_price").remove();

               }
            });
        });
    });

    $(document).ready(function() {
        var product_id = '<?=$product->id?>';
        var selVal = $('#dropdownss').val();
      
       // var drop_count = $("#dropdownss :selected").length;
        $("#textboxDiv").html('');
         $.ajax({
           type:'POST',
           url:"{{ route('products.updateProductPrice') }}",
           data:{customer_group_id:selVal,product_id:product_id,"_token": "{{ csrf_token() }}",},
           success:function(data){
             // alert(data.success);
             $("#textboxDiv").append(data.success);
             $(".show_price").remove();

           }
        });
    });

    /*$(document).ready(function() {
      var product_id = '<?=$product->id?>';
      $.ajax({
         type:'POST',
         url:"{{ url('products/getPriceVariations') }}",
         data:{product_id:product_id,"_token": "{{ csrf_token() }}",},
         success:function(data) {
           var datas = JSON.parse(data);
           if(datas.status=='success') {
                $.each(datas.result_data, function (keys,vals) {
                    $('#price-variation-table tr:last').after('<tr><input type="hidden" name="variation_id[]" value="'+vals.id+'" /> <td><input type="text" name="product_purchase_quantity[]" class="form-control" value="'+vals.purchase_quantity+'"></td><td><input type="number" name="product_price_multiplier[]" class="form-control" value="'+vals.price_multiplier+'"></td></tr>');
                    //console.log(vals.price_multiplier);
                });
           } 
           //$("#textboxDiv").append(data.success);
         }
       });
    });*/
    
    function cb() { }
    function cb1() { }
    
    $(document).ready(function() {
       $('#alternative_unit').trigger('change'); 
    });
    
</script>
@endpush