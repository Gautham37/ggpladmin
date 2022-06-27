@extends('layouts.app')

@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0 text-dark">{{trans('lang.complaint_plural')}}<small class="ml-3 mr-3">|</small><small>{{trans('lang.complaint_desc')}}</small></h1>
      </div><!-- /.col -->
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="{{url('/dashboard')}}"><i class="fa fa-dashboard"></i> {{trans('lang.dashboard')}}</a></li>
          <li class="breadcrumb-item"><a href="{!! route('complaints.index') !!}">{{trans('lang.complaint_plural')}}</a>
          </li>
          <li class="breadcrumb-item active">{{trans('lang.complaint_table')}}</li>
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
          <a class="nav-link active" href="{!! url()->current() !!}"><i class="fa fa-list mr-2"></i>{{trans('lang.complaint_table')}}</a>
        </li>
        @include('layouts.right_toolbar', compact('dataTable'))
      </ul>
    </div>
    <div class="card-body">
      @include('complaints.table')
      <div class="clearfix"></div>
    </div>
  </div>
</div>
<!-- Modal -->
<!--<div id="complaintsCommentsModal" class="modal fade" role="dialog">
  <div class="modal-dialog modal-md">
    {!! Form::open([ 'url' => 'complaints/addComplaintsComments', 'id' => 'complaintsCommentsForm']) !!}  
    
    <div class="modal-content product-stock-modal">

      <div class="modal-header align-items-stretch">
          <h5 class="modal-title flex-grow-1">{!! trans('lang.complaint_add_comments') !!}</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
          </button>
      </div>
      <div class="modal-body">
        <input type="hidden" name="complaints_id" id="complaints_id" >
        
        {!! Form::label('staff_members', trans("lang.complaint_members"), ['class' => 'col-3 control-label text-left']) !!}
        <span id="staff_members"></span>
        
        
        <div class="form-group">
            {!! Form::label('comments', trans("lang.complaint_comments"), ['class' => 'col-3 control-label text-left']) !!}
               {!! Form::textarea('comments', null,  ['class' => 'form-control', 'placeholder'=>  trans("lang.complaint_comments_placeholder")]) !!} 
        </div>

          
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Cancel</button>
        <button type="submit" class="btn btn-primary btn-sm">Save</button>
      </div>
    </div>
    {!! Form::close() !!}
  </div>
</div>-->
@endsection

