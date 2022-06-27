@extends('layouts.app')

@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0 text-dark">{{trans('lang.reports_plural')}}<small class="ml-3 mr-3">|</small><small>{{trans('lang.reports_desc')}}</small></h1>
      </div><!-- /.col -->
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="{{url('/dashboard')}}"><i class="fa fa-dashboard"></i> {{trans('lang.dashboard')}}</a></li>
          <li class="breadcrumb-item"><a href="{!! route('reports.index') !!}">{{trans('lang.reports_plural')}}</a>
          </li>
          <li class="breadcrumb-item active">{{trans('lang.reports_table')}}</li>
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
          <a class="nav-link active" href="{!! route('reports.index') !!}"><i class="fa fa-list mr-2"></i>{{trans('lang.reports_plural')}}</a>
        </li>
        @include('layouts.right_toolbar', compact('dataTable'))
      </ul>
    </div>
    
    <div class="card-body">

        




        @if($report_type=='expenses-transaction-report') 

          <div class="row">
            <!-- Expenses Category Field -->
            <div class="col-md-3 form-group">
              {!! Form::label('expense_category', trans("lang.expenses_categories"),['class' => ' control-label text-right']) !!}
              {!! Form::select('expense_category', $expenses_category, $expensesCatSelected, ['class' => 'select2 form-control']) !!}
            </div>
            
            <div class="col-md-3 form-group">
                {!! Form::label('expense_category', trans("lang.expenses_categories"),['class' => ' control-label text-right']) !!}
                <div id="reportrange"  class="pull-left" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc;">
                    <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>&nbsp;
                    <span></span> 
                </div>  
            </div>

          </div>

          @include('reports.expenses_transaction_report_table')

        @endif

        @if($report_type=='expenses-category-report') 

          <div class="row">
            <div class="col-md-3 form-group">
                <div id="reportrange"  class="pull-left" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc;">
                    <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>&nbsp;
                    <span></span> 
                </div>  
            </div>
          </div>

          @include('reports.expenses_category_report_table')

        @endif


        @if($report_type=='stock-summary-report') 

          <!-- <div class="row">
            <div class="col-md-3 form-group">
                <div id="reportrange"  class="pull-left" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc;">
                    <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>&nbsp;
                    <span></span> 
                </div>  
            </div>
          </div> -->

          @include('reports.stock_summary_report')

        @endif

        @if($report_type=='rate-list') 

          @include('reports.rate_list')

        @endif

        @if($report_type=='item-sales-summary') 
        
          @include('reports.item_sales_summary')

        @endif

        @if($report_type=='low-stock-summary') 
        
          @include('reports.low_stock_summary')

        @endif

        @if($report_type=='stock-detail-report') 

          <div class="row">
            <!-- Products Field -->
            <div class="col-md-3 form-group">
              {!! Form::select('products', $products, null, ['class' => 'select2 form-control', 'id' => 'product']) !!}
            </div>
            
            <div class="col-md-3 form-group">
                <div id="reportrange"  class="pull-left" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc;">
                    <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>&nbsp;
                    <span></span> 
                </div>  
            </div>

          </div>
        
          @include('reports.stock_detail_report')

        @endif

      <div class="clearfix"></div>
    </div>

  </div>
</div>
@endsection





