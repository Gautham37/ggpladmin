@extends('layouts.app')

@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0 text-dark">Notifications<small class="ml-3 mr-3">|</small><small>Notifications Management</small></h1>
      </div><!-- /.col -->
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="{{url('/dashboard')}}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
          <li class="breadcrumb-item"><a href="{!! route('notifications.index') !!}">Notifications</a>
          </li>
          <li class="breadcrumb-item active">Notification List</li>
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
          <a class="nav-link active" href="{!! url()->current() !!}"><i class="fa fa-list mr-2"></i>Notifications List</a>
        </li>

      </ul>
    </div>
    <div class="card-body">



      @if(auth()->user()->unreadNotifications->count() > 0)
        @foreach(auth()->user()->unreadNotifications as $notification)
          
          <div class="alert alert-success" role="alert">
              <i class="fa fa-bell"></i> &nbsp;{{ $notification->data['data']['title'] }}
              <a href="#" class="float-right mark-as-read" data-id="{{ $notification->id }}">
                  Mark as read
              </a>
              <br>
              {{ $notification->data['data']['message'] }}
          </div>

        @endforeach
      @else
        <p class="text-left"> There are no new notifications </p>
      @endif

      <div class="clearfix"></div>
    </div>
  </div>
</div>
@endsection

