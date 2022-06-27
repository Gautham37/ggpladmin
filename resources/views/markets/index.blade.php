@extends('layouts.app')

@section('content')

<style type="text/css">
  hr {
    margin-top: 10px !important;
    margin-bottom: 10px !important;
  }
</style>

<!-- Content Header (Page header) -->
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0 text-dark">{{trans('lang.market_plural')}}<small class="ml-3 mr-3">|</small><small>{{trans('lang.market_desc')}}</small></h1>
      </div><!-- /.col -->
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="{{url('/dashboard')}}"><i class="fa fa-dashboard"></i> {{trans('lang.dashboard')}}</a></li>
          <li class="breadcrumb-item"><a href="{!! route('markets.index') !!}">{{trans('lang.market_plural')}}</a>
          </li>
          <li class="breadcrumb-item active">{{trans('lang.market_table')}}</li>
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
          <a class="nav-link active" href="{!! url()->current() !!}"><i class="fa fa-list mr-2"></i>{{trans('lang.market_table')}}</a>
        </li>
        @can('markets.create')
        <li class="nav-item">
          <a class="nav-link" href="{!! route('markets.create') !!}"><i class="fa fa-plus mr-2"></i>{{trans('lang.market_create')}}</a>
        </li>
        @endcan
        @can('markets.import')
          <li class="nav-item">
            <a class="btn btn-primary" id="import_markets" href="{{ route('markets.import') }}"> Import Parties </a>
          </li>
        @endcan
        @include('layouts.right_toolbar', compact('dataTable'))
      </ul>
    </div>
    <div class="card-body">
        {!! Form::open(['route' => 'markets.index']) !!}
        <div class="row custom-from-css">
            <div class="col-md-2 form-group">
                {!! Form::select('party_type', $party_types, null, ['class' => 'select2 form-control party_type','id'=>'party_type']) !!}
                <!-- {!! Form::text('party_name', null, ['class' => 'form-control','id'=>'party_name', 'placeholder'=>'Party Name']) !!} -->
            </div>
             <div class="col-md-2 form-group">
                {!! Form::text('party_code', null, ['class' => 'form-control','id'=>'party_code', 'placeholder'=>'Party Code']) !!}
            </div>
             <div class="col-md-2 form-group">
                {!! Form::text('party_town', null, ['class' => 'form-control','id'=>'party_town', 'placeholder'=>'Town']) !!}
            </div>
            <div class="col-md-2 form-group">
                {!! Form::text('party_phone', null, ['class' => 'form-control','id'=>'party_phone', 'placeholder'=>'Phone']) !!}
            </div>
            <div class="col-md-2 form-group">
                {!! Form::text('party_mobile', null, ['class' => 'form-control','id'=>'party_mobile', 'placeholder'=>'Mobile']) !!}
            </div>
            
            <div class="col-md-2 form-group">
              <button type="submit" style="width:100%;" class="btn btn-{{setting('theme_color')}}" id="search">
                <i class="fa fa-search"></i> Search
              </button>
            </div>
        </div>

         {!! Form::close() !!}
        <hr/>
        @include('markets.table')
        <div class="clearfix"></div>
    </div>
  </div>
</div>
@endsection

@push('scripts_lib')

<script>

  $(document).ready(function(){
        $('#search').click(function(event){
          event.preventDefault();

          var party_type = $('#party_type').val(); 
         var party_name = $('#party_name').val();
         var party_code = $('#party_code').val();
         var party_town = $('#party_town').val();
         var party_phone = $('#party_phone').val();
         var party_mobile = $('#party_mobile').val();
         
          location.href="?p_code='+ party_code+'&p_town='+ party_town+'&p_phone='+ party_phone+'&p_mobile='+ party_mobile+'&party_type='+ party_type;
       
});

});

    @if(Request::get('p_name')) 
        $('#party_name').val("{{Request::get('p_name')}}");
    @endif
    @if(Request::get('party_type')) 
        $('#party_type').val("{{Request::get('party_type')}}");
    @endif
     @if(Request::get('p_code')) 
        $('#party_code').val("{{Request::get('p_code')}}");
    @endif
     @if(Request::get('p_town')) 
        $('#party_town').val("{{Request::get('p_town')}}");
    @endif
     @if(Request::get('p_phone')) 
        $('#party_phone').val("{{Request::get('p_phone')}}");
    @endif
     @if(Request::get('p_mobile')) 
        $('#party_mobile').val("{{Request::get('p_mobile')}}");
    @endif
</script>

@endpush