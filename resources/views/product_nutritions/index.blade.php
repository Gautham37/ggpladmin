@extends('layouts.app')

@section('content')
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0 text-dark">Product Nutritions<small class="ml-3 mr-3">|</small><small>Product Nutritions Management</small></h1>
      </div><!-- /.col -->
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="{{url('/dashboard')}}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
          <li class="breadcrumb-item"><a href="{!! route('productNutritions.index') !!}">Product Nutritions</a>
          </li>
          <li class="breadcrumb-item active">Product Nutritions List</li>
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
          <a class="nav-link active" href="{!! url()->current() !!}"><i class="fa fa-list mr-2"></i>Product Nutritions List</a>
        </li>
        @can('productNutritions.create')
        <li class="nav-item">
          <a class="nav-link" href="{!! route('productNutritions.create') !!}"><i class="fa fa-plus mr-2"></i>Create Product Nutrition</a>
        </li>
        @endcan
        @include('layouts.right_toolbar', compact('dataTable'))
      </ul>
    </div>
    <div class="card-body">
      @include('product_nutritions.table')
      <div class="clearfix"></div>
    </div>
  </div>
</div>
@endsection

