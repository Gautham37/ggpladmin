@extends('layouts.app')

@section('content')

<!-- Content Header (Page header) -->
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0 text-dark">Quotes<small class="ml-3 mr-3">|</small><small>Quotes Management</small></h1>
      </div><!-- /.col -->
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="{{url('/dashboard')}}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
          <li class="breadcrumb-item"><a href="{!! route('quotes.index') !!}">Quotes</a>
          </li>
          <li class="breadcrumb-item active">Quotes List</li>
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
          <a class="nav-link active" href="{!! url()->current() !!}"><i class="fa fa-list mr-2"></i>Quotes List</a>
        </li>
        @can('quotes.create')
        <li class="nav-item">
          <a class="nav-link" id="new-invoice" href="{!! route('quotes.create') !!}">
            <i class="fa fa-plus mr-2"></i> Create Quote 
          </a>
        </li>
        @endcan
        @include('layouts.right_toolbar', compact('dataTable'))
      </ul>
    </div>
    <div class="card-body">
      <div class="row">
          <div class="col-md-4 form-group">
              <div id="reportrange"  class="pull-left" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc;">
                  <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>&nbsp;
                  <span></span> 
              </div>  
          </div>
      </div>
      @include('quotes.table')
      <div class="clearfix"></div>
    </div>
  </div>
</div>
@endsection

@push('scripts_lib')
<script type="text/javascript">
    
    function cb(start, end) {

        $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
        var start_date      = start.format('YYYY-MM-DD');
        var end_date        = end.format('YYYY-MM-DD');
        location.href='?start_date='+start_date+'&end_date='+end_date;
    }
    
    //function cb1() {}
    //function cb2() {}

    @if(Request::get('start_date') && Request::get('end_date'))
        $('#reportrange span').html("{{date('F d, Y',strtotime(Request::get('start_date')))}} - {{date('F d, Y',strtotime(Request::get('end_date')))}}");
    @endif
    
</script>
@endpush

