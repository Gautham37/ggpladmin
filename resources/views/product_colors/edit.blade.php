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

<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0 text-dark">Product Colors<small class="ml-3 mr-3">|</small><small>Product Colors Management</small></h1>
      </div><!-- /.col -->
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="{{url('/dashboard')}}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
          <li class="breadcrumb-item"><a href="{!! route('productColors.index') !!}">Product Colors</a>
          </li>
          <li class="breadcrumb-item active">Product Colors List</li>
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
        <li class="nav-item">
          <a class="nav-link" href="{!! route('productColors.index') !!}"><i class="fa fa-list mr-2"></i>Product Colors List</a>
        </li>
        @can('productColors.create')
        <li class="nav-item">
          <a class="nav-link" href="{!! route('productColors.create') !!}"><i class="fa fa-plus mr-2"></i>Create Product Color</a>
        </li>
        @endcan
        @can('productColors.edit')
        <li class="nav-item">
          <a class="nav-link active" href="{!! url()->current() !!}"><i class="fa fa-edit mr-2"></i>Edit Product Color</a>
        </li>
        @endcan
      </ul>
    </div>

    <div class="card-body">
      {!! Form::model($product_color, ['route' => ['productColors.update', $product_color->id], 'method' => 'patch']) !!}
      <div class="row">
        @include('product_colors.fields')
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
</script>
@endpush