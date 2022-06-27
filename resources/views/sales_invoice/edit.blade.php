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
        <h1 class="m-0 text-dark">Sales Invoices<small class="ml-3 mr-3">|</small><small>Sales Invoice Management</small></h1>
      </div><!-- /.col -->
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="{{url('/dashboard')}}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
          <li class="breadcrumb-item"><a href="{!! route('salesInvoice.index') !!}"> Sales Invoice </a>
          </li>
          <li class="breadcrumb-item active">Edit Sales Invoice</li>
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
        @can('salesInvoice.create')
        <li class="nav-item">
          <a class="nav-link" href="{!! route('salesInvoice.create') !!}"><i class="fa fa-plus mr-2"></i>Create Sales Invoice</a>
        </li>
        @endcan
        <li class="nav-item">
          <a class="nav-link active" href="{!! url()->current() !!}"><i class="fa fa-pencil mr-2"></i>Edit Sales Invoice</a>
        </li>
      </ul>
    </div>
    
    <div class="card-body">
      {!! Form::model($sales_invoice, ['route' => ['salesInvoice.update', $sales_invoice->id], 'method' => 'patch', 'class' => 'sales-invoice-form']) !!}
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