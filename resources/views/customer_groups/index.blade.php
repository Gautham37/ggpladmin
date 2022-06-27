@extends('layouts.app')

@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0 text-dark">
          Party Groups
          <small class="ml-3 mr-3">|</small>
          <small>
            Party Groups Management  
            &nbsp;&nbsp;&nbsp;
            <span data-toggle="tooltip" data-placement="bottom" data-original-title="Used for group based product price variation"> 
              <i class="fa fa-info-circle"></i> 
            </span>
          </small>
      </h1>
      </div><!-- /.col -->
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="{{url('/dashboard')}}"><i class="fa fa-dashboard"></i> {{trans('lang.dashboard')}}</a></li>
          <li class="breadcrumb-item"><a href="{!! route('CustomerGroups.index') !!}">{{trans('lang.customer_group_plural')}}</a>
          </li>
          <li class="breadcrumb-item active">{{trans('lang.customer_group_table')}}</li>
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
          <a class="nav-link active" href="{!! url()->current() !!}"><i class="fa fa-list mr-2"></i>{{trans('lang.customer_group_table')}}</a>
        </li>
        @can('CustomerGroups.create')
        <li class="nav-item">
          <a class="nav-link" href="{!! route('CustomerGroups.create') !!}"><i class="fa fa-plus mr-2"></i>
            {{trans('lang.customer_group_create')}}
          </a>
        </li>
        @endcan
        @include('layouts.right_toolbar', compact('dataTable'))
      </ul>
    </div>
    <div class="card-body">
      @include('customer_groups.table')
      <div class="clearfix"></div>
    </div>
  </div>
</div>
@endsection

