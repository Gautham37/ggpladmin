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
          <a class="nav-link active" href="{!! url()->current() !!}"><i class="fa fa-list mr-2"></i>{{trans('lang.reports_plural')}}</a>
        </li>
      </ul>
    </div>
    
    <div class="card-body">
      

      <!-- Item Report Section -->

      <!--<div class="row">-->
        
      <!--  <div class="col-md-3">-->
      <!--     <a href="{{ route('reports.show') }}?report_type=sales-summary" class="btn report-btn text-left" style="width: 100%;">   -->
      <!--        <i class="nav-icon fa fa-sticky-note-o"></i> &nbsp;-->
      <!--        {{trans('lang.report_sales_summary')}} &nbsp; <i class="fa fa-chevron-right float-right"></i> -->
      <!--     </a>-->
      <!--  </div> -->

        <?php /* ?>
        <div class="col-md-3">
           <a href="{{ route('reports.show') }}?report_type=party-statement" class="btn report-btn text-left" style="width: 100%;">   
              <i class="nav-icon fa fa-user"></i> &nbsp;
              {{trans('lang.report_party_statement')}} &nbsp; <i class="fa fa-chevron-right float-right"></i> 
           </a>
        </div> <?php */ ?>

      <!--  <div class="col-md-3">-->
      <!--     <a href="{{ route('reports.show') }}?report_type=daybook" class="btn report-btn text-left" style="width: 100%;">   -->
      <!--        <i class="nav-icon fa fa-book"></i> &nbsp;-->
      <!--        {{trans('lang.report_daybook')}} &nbsp; <i class="fa fa-chevron-right float-right"></i> -->
      <!--     </a>-->
      <!--  </div>        -->

      <!--</div>-->

      <!-- Item Report Section -->
      
      <br>

      <!-- Transaction Section -->

      <button class="btn text-left" style="width: 100%;">
        <i class="nav-icon fa fa-sticky-note-o"></i> &nbsp;&nbsp; {{trans('lang.report_transactions')}}
      </button>
      <div class="row">
          
          <div class="col-md-3">
           <a href="{{ route('reports.show') }}?report_type=sales-summary" class="btn report-btn text-left" style="width: 100%;">   
              <i class="nav-icon fa fa-sticky-note-o"></i> &nbsp;
              {{trans('lang.report_sales_summary')}} &nbsp; <i class="fa fa-chevron-right float-right"></i> 
           </a>
        </div> 

        <?php /* ?>
        <div class="col-md-3">
           <a href="{{ route('reports.show') }}?report_type=party-statement" class="btn report-btn text-left" style="width: 100%;">   
              <i class="nav-icon fa fa-user"></i> &nbsp;
              {{trans('lang.report_party_statement')}} &nbsp; <i class="fa fa-chevron-right float-right"></i> 
           </a>
        </div> <?php */ ?>

        <div class="col-md-3">
           <a href="{{ route('reports.show') }}?report_type=daybook" class="btn report-btn text-left" style="width: 100%;">   
              <i class="nav-icon fa fa-book"></i> &nbsp;
              {{trans('lang.report_daybook')}} &nbsp; <i class="fa fa-chevron-right float-right"></i> 
           </a>
        </div>   
        
        <div class="col-md-3">
           <a href="{{ route('reports.show') }}?report_type=bill-wise-profit" class="btn report-btn text-left" style="width: 100%;">   
              {{trans('lang.report_bill_wise_profit')}} &nbsp; <i class="fa fa-chevron-right float-right"></i> 
           </a>
        </div> 
        
        <div class="col-md-3">
           <a href="{{ route('reports.show') }}?report_type=party-statement" class="btn report-btn text-left" style="width: 100%;">   
              <i class="nav-icon fa fa-user"></i> &nbsp;
              {{trans('lang.report_party_statement')}} &nbsp; <i class="fa fa-chevron-right float-right"></i> 
           </a>
        </div>

      </div>

      <!-- Transaction Report Section -->
      
      <br>
      
      <!-- Item Report Section -->

      <button class="btn text-left" style="width: 100%;">
        <i class="nav-icon fa fa-cubes"></i> &nbsp;&nbsp; {{trans('lang.report_item')}}
      </button>
      <div class="row">
        
        <div class="col-md-3">
           <a href="{{ route('reports.show') }}?report_type=stock-summary-report" class="btn report-btn text-left" style="width: 100%;"> {{trans('lang.report_stock_summary')}} &nbsp; <i class="fa fa-chevron-right float-right"></i> </a>
        </div>

        <div class="col-md-3">
           <a href="{{ route('reports.show') }}?report_type=rate-list" class="btn report-btn text-left" style="width: 100%;"> {{trans('lang.report_rate_list')}} &nbsp; <i class="fa fa-chevron-right float-right"></i> </a>
        </div>

        <div class="col-md-3">
           <a href="{{ route('reports.show') }}?report_type=item-sales-summary" class="btn report-btn text-left" style="width: 100%;"> {{trans('lang.report_item_sales_summary')}} &nbsp; <i class="fa fa-chevron-right float-right"></i> </a>
        </div>

        <div class="col-md-3">
           <a href="{{ route('reports.show') }}?report_type=low-stock-summary" class="btn report-btn text-left" style="width: 100%;"> {{trans('lang.report_low_stock_summary')}} &nbsp; <i class="fa fa-chevron-right float-right"></i> </a>
        </div>
         <?php /* ?> 
        <div class="col-md-3">
           <a href="{{ route('reports.show') }}?report_type=stock-detail-report" class="btn report-btn text-left" style="width: 100%;"> {{trans('lang.report_stock_detail_report')}} &nbsp; <i class="fa fa-chevron-right float-right"></i> </a>
        </div>
        <?php /*/ ?>
        <div class="col-md-3">
           <a href="{{ route('reports.show') }}?report_type=item-report-by-party" class="btn report-btn text-left" style="width: 100%;"> {{trans('lang.report_item_report_by_party')}} &nbsp; <i class="fa fa-chevron-right float-right"></i> </a>
        </div> 
        
        <?php /* ?>
          <div class="col-md-3">
           <a href="{{ route('reports.show') }}?report_type=stock-purchase-report" class="btn report-btn text-left" style="width: 100%;"> {{trans('lang.report_stock_purchase')}} &nbsp; <i class="fa fa-chevron-right float-right"></i> </a>
        </div> <?php */ ?> 
        
         <div class="col-md-3">
           <a href="{{ route('reports.show') }}?report_type=wastage-report" class="btn report-btn text-left" style="width: 100%;"> {{trans('lang.report_wastage')}} &nbsp; <i class="fa fa-chevron-right float-right"></i> </a>
        </div>
        
        <div class="col-md-3">
           <a href="{{ route('reports.show') }}?report_type=missing-report" class="btn report-btn text-left" style="width: 100%;"> Missing Report &nbsp; <i class="fa fa-chevron-right float-right"></i> </a>
        </div>

      </div>

      <!-- Item Report Section -->
      <br>
      <!-- Expenses Report Section -->

      <button class="btn text-left" style="width: 100%;">
        <i class="nav-icon fa fa-group"></i> &nbsp;&nbsp; Party
      </button>
      <div class="row">
        <div class="col-md-3">
           <a href="{{ route('reports.show') }}?report_type=party-wise-outstanding" class="btn report-btn text-left" style="width: 100%;"> Party Wise Outstanding &nbsp; <i class="fa fa-chevron-right float-right"></i> </a>
        </div>
        <div class="col-md-3">
           <a href="{{ route('reports.show') }}?report_type=party-report-by-item" class="btn report-btn text-left" style="width: 100%;"> Party Report by Item &nbsp; <i class="fa fa-chevron-right float-right"></i> </a>
        </div>
        <div class="col-md-3">
           <a href="{{ route('reports.show') }}?report_type=charity-wastage-report" class="btn report-btn text-left" style="width: 100%;"> Charity Wastage Report &nbsp; <i class="fa fa-chevron-right float-right"></i> </a>
        </div>
        <div class="col-md-3">
           <a href="{{ route('reports.show') }}?report_type=vendor-stocks-report" class="btn report-btn text-left" style="width: 100%;"> Vendor Stocks Report &nbsp; <i class="fa fa-chevron-right float-right"></i> </a>
        </div>
        <div class="col-md-3">
           <a href="{{ route('reports.show') }}?report_type=email-alerts-report" class="btn report-btn text-left" style="width: 100%;"> Email Alerts Report &nbsp; <i class="fa fa-chevron-right float-right"></i> </a>
        </div>
        <div class="col-md-3">
           <a href="{{ route('reports.show') }}?report_type=online-orders-report" class="btn report-btn text-left" style="width: 100%;"> Online Orders Report &nbsp; <i class="fa fa-chevron-right float-right"></i> </a>
        </div>
      </div>
  
      <!-- Expenses Report Section -->
      <br>
      <!-- Expenses Report Section -->

      <button class="btn text-left" style="width: 100%;">
        <i class="nav-icon fa fa-credit-card"></i> &nbsp;&nbsp; Expenses
      </button>
      <div class="row">
        <div class="col-md-3">
           <a href="{{ route('reports.show') }}?report_type=expenses-transaction-report" class="btn report-btn text-left" style="width: 100%;"> Expense Transaction Report &nbsp; <i class="fa fa-chevron-right float-right"></i> </a>
        </div>
        <div class="col-md-3">
           <a href="{{ route('reports.show') }}?report_type=expenses-category-report" class="btn report-btn text-left" style="width: 100%;"> Expense Category Report &nbsp; <i class="fa fa-chevron-right float-right"></i> </a>
        </div>
      </div>
  
      <!-- Expenses Report Section -->
       <br>
       <!-- Products Report Section -->

      <button class="btn text-left" style="width: 100%;">
        <i class="nav-icon fa fa-product-hunt"></i> &nbsp;&nbsp; Products
      </button>
      <div class="row">
        <div class="col-md-3">
           <a href="{{ route('reports.show') }}?report_type=products-report" class="btn report-btn text-left" style="width: 100%;"> Product Order History Report &nbsp; <i class="fa fa-chevron-right float-right"></i> </a>
        </div>

        <div class="col-md-3">
           <a href="{{ route('reports.show') }}?report_type=popular-products-report" class="btn report-btn text-left" style="width: 100%;"> Products Performance Report &nbsp; <i class="fa fa-chevron-right float-right"></i> </a>
        </div>
       
        <div class="col-md-3">
           <a href="{{ route('reports.show') }}?report_type=profitable-products-report" class="btn report-btn text-left" style="width: 100%;"> Profitable Products Report &nbsp; <i class="fa fa-chevron-right float-right"></i> </a>
        </div>
      </div>

  
      <!-- Products Report Section -->
     <br>
        <!-- Customer Report Section -->

      <button class="btn text-left" style="width: 100%;">
        <i class="nav-icon fa fa-sign-in"></i> &nbsp;&nbsp; Customers
      </button>
      <div class="row">
        <div class="col-md-3">
           <a href="{{ route('reports.show') }}?report_type=customers-report" class="btn report-btn text-left" style="width: 100%;"> Customers Report &nbsp; <i class="fa fa-chevron-right float-right"></i> </a>
        </div>
          </div>

      <!-- Customer Report Section -->
     <br>
        <!-- Log Report Section -->

      <?php /* ?><button class="btn text-left" style="width: 100%;">
        <i class="nav-icon fa fa-sign-in"></i> &nbsp;&nbsp; Logs
      </button>
      <div class="row">
        <div class="col-md-3">
           <a href="{{ route('reports.show') }}?report_type=staff-login-report" class="btn report-btn text-left" style="width: 100%;"> Staff Log In Report &nbsp; <i class="fa fa-chevron-right float-right"></i> </a>
        </div>
          </div> <?php */ ?>

      <!-- Log Report Section -->
      
       <!-- Delivery Report Section -->

      <button class="btn text-left" style="width: 100%;">
        <i class="nav-icon fa fa-truck"></i> &nbsp;&nbsp; Delivery 
      </button>
      <div class="row">
        <div class="col-md-3">
           <a href="{{ route('reports.show') }}?report_type=delivery-report" class="btn report-btn text-left" style="width: 100%;"> Delivery Report &nbsp; <i class="fa fa-chevron-right float-right"></i> </a>
        </div>
          </div>

      <!-- Delivery Report Section -->


      <div class="clearfix"></div>
    </div>

  </div>
</div>
@endsection

