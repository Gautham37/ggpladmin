@extends('layouts.app')

@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0 text-dark">Inventory<small class="ml-3 mr-3">|</small><small>Inventory Management</small></h1>
      </div><!-- /.col -->
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="{{url('/dashboard')}}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
          <li class="breadcrumb-item"><a href="{!! route('inventory.index') !!}">Inventory</a>
          </li>
          <li class="breadcrumb-item active">Product Stock List</li>
        </ol>
      </div><!-- /.col -->
    </div><!-- /.row -->
  </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->

<div class="content">
  <div class="clearfix"></div>
  @include('flash::message')
  <div class="card">
    <div class="card-header">
      <ul class="nav nav-tabs align-items-end card-header-tabs w-100">
        <li class="nav-item">
          <a class="nav-link active" href="{!! url()->current() !!}"><i class="fa fa-list mr-2"></i>Product Stock List</a>
        </li>
        @can('inventory.list')
          <li class="nav-item">
            <a class="nav-link" href="{{route('inventory.list')}}"><i class="fa fa-list mr-2"></i>Inventory List</a>
          </li>
        @endcan
        @include('layouts.right_toolbar_new', compact('dataTable'))
      </ul>
    </div>
    <div class="card-body">
      @include('products.table')
      <div class="clearfix"></div>
    </div>
  </div>
</div>

<!--Inventory-->
<div class="modal fade" id="productInventoryModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content inventory-elements"></div>
  </div>
</div>


@endsection

@push('scripts_lib')

<script src="{{asset('plugins/dropzone/dropzone.js')}}"></script>
<script type="text/javascript">
    Dropzone.autoDiscover = false;
    var dropzoneFields = [];
</script>

<script>
  function adjustInventoryStock(elem) {
    $('#productInventoryModal').modal('show');
    var id        = $(elem).data('id');
    var url       = '{!!  route('products.show', [':productID']) !!}';
    url           = url.replace(':productID', id);
    var token     = "{{csrf_token()}}";
    $.ajax({
        type: 'GET',
        data: {
            '_token': token,
            'type': 'product_inventory',
            'id': id
        },
        url: url,
        success: function (response) {
           $('.inventory-elements').html(response.data);
        }
    });
  }
</script>

@endpush

