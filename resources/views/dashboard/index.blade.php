@extends('layouts.app')
@section('content')

<style>
    .small-box h3{
        font-size: 32px;
    }
    .small-box
    {
        height: 153px;
    }
    .inner
    {
      height: 127px;
    }

    @import url('https://fonts.googleapis.com/css2?family=Merriweather:wght@300&display=swap');
    @import url('https://fonts.googleapis.com/css2?family=Lato:ital,wght@0,100;0,300;0,400;0,700;0,900;1,100;1,300;1,400;1,700;1,900&display=swap');

    .card .card-body .card-title {
        margin: 10px 0 0 0;
        font-size: 1.2rem !important;
        font-weight: 400;
        font-family: 'Merriweather', serif;
        line-height: 150%;
        color: #ffffff;
    }
    .card {
        border-radius: 8px;
        font-family: 'Lato', sans-serif;
    }
</style>
    
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">

    <!-- Content Header (Page header) -->
    <section class="content-header content-header{{setting('fixed_header')}}">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>{{trans('lang.dashboard')}}</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">{{trans('lang.dashboard')}}</a></li>
                        <li class="breadcrumb-item active">{{trans('lang.dashboard')}}</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <div class="content dashboard-top-section">
        <!-- Small boxes (Stat box) -->
        <div class="row">

            <div class="col-lg-12 col-12">
                <div class="row">
                  
                <div class="col-md-4 form-group">
                    <div id="reportrange"  class="pull-left" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc;">
                        <i class="glyphicon glyphicon-calendar fa fa-calendar"></i> &nbsp; <span></span> 
                    </div>  
                </div>

                </div>
            </div>
        </div>




        <div class="">
          <div class="row gutters">
             <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-12">
                <div class="card gradient-dark-grey2 card-380">
                   <div class="gradient-vertical-strip-grey"></div>
                   <button type="button" class="download-icon download-reports"><img src="{{asset('images/download-blue.svg')}}" alt="Download"></button>
                   <div class="card-body">
                      <div class="card-title">Monthly Orders</div>
                      <div id="recentOrders" class="mt-4"></div>
                   </div>
                </div>
                <div class="card gradient-orange2 card-210">
                   <div class="gradient-vertical-strip"></div>
                   <button type="button" class="download-icon download-reports"><img src="{{asset('images/download-blue.svg')}}" alt="Download"></button>
                   <div class="card-body">
                      <div class="card-title">Subscribers</div>
                      <div class="subscribers mb-0">
                         <div class="total-subscribers m-0">195</div>
                      </div>
                   </div>
                </div>
             </div>
             <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-12">
                <div class="card gradient-violet-pink card-150">
                   <div class="card-body">
                      <div class="sales-tile3">
                         <div class="sales-tile3-block">
                            <div class="sales-tile3-icon peach">
                                <i class="material-icons">supervisor_account</i>
                            </div>
                            <div class="sales-tile3-details">
                               <h5>Parties</h5>
                               <h2 class="partiesCount">{{$marketsCount}}</h2>
                               <span>+<span class="lastMonthParties"></span>% since last month</span>
                            </div>
                         </div>
                      </div>
                   </div>
                </div>
                <div class="card gradient-blue2 card-450">
                   <div class="gradient-vertical-strip"></div>
                   <button type="button" class="download-icon download-reports"><img src="{{asset('images/download-blue.svg')}}" alt="Download"></button>
                   <div class="card-body">
                      <div class="card-title">Top Selling<br />Products</div>
                      <div id="revenueGraph"></div>
                      <div class="sales-tile4">
                         <h2 class="totelSelling"></h2>
                         <span>+<span class="totelSelling"></span>% Higher orders last month</span>
                      </div>
                   </div>
                </div>
             </div>
             <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-12">
                <div class="card gradient-green card-150">
                   <div class="card-body">
                      <div class="sales-tile3">
                         <div class="sales-tile3-block">
                            <div class="sales-tile3-icon green">
                                <i class="material-icons animate__animated animate__swing animate__infinite">shopping_basket</i>
                            </div>
                            <div class="sales-tile3-details">
                               <h5>Orders</h5>
                               <h2 class="totalOrders"></h2>
                               <span>+<span class="lastMonthOrders"></span>% since last month</span>
                            </div>
                         </div>
                      </div>
                   </div>
                </div>
                <div class="card gradient-green-orange card-450">
                   <div class="gradient-vertical-strip"></div>
                   <button type="button" class="download-icon download-reports"><img src="{{asset('images/download-blue.svg')}}" alt="Download"></button>
                   <div class="card-body">
                      <div class="card-title">Weekly<br />Sales</div>
                      <!-- <div id="qtrTargetGraph"></div> -->
                      <div id="salesGraph"></div>
                   </div>
                </div>
             </div>
             <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-12">
                <div class="card gradient-violet3 globe-bg card-390">
                   <div class="card-body">
                      <div class="card-title">Products</div>
                      <ul class="statistics">
                            <li>
                                <span class="stat-icon"><i class="material-icons">store</i></span>Products
                                - &nbsp; <span class="allProducts"></span>
                            </li>
                            <li>
                                <span class="stat-icon"><i class="material-icons">trending_up</i></span>Profitable
                                - &nbsp; <span class="profitProducts">{{$profit_count}}</span>
                            </li>
                            <li>
                                <span class="stat-icon"><i class="material-icons">trending_down</i></span>Less Profitable 
                                - &nbsp; <span class="lossProducts">{{$loss_count}}</span>
                            </li>
                            <li>
                                <span class="stat-icon"><i class="material-icons">archive</i></span>Wastage
                                - &nbsp; <span class="wastageProducts">{{$wastage}}</span>
                            </li>
                      </ul>
                   </div>
                </div>
                <div class="card gradient-teal-brown card-210">
                   <div class="card-body">
                      <div class="row gutters">
                         <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                            <div class="earnings">
                               <div id="weeklyEarnings"></div>
                               <p>Online Earning</p>
                               <h3>{{setting('default_currency')}}<span class="totalEarning"></span></h3>
                            </div>
                         </div>
                         <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                            <div class="earnings">
                               <div id="monthlyEarnings"></div>
                               <p>Offline Earning</p>
                               <h3>{{setting('default_currency')}}<span class="salesEarning"></span></h3>
                            </div>
                         </div>
                      </div>
                   </div>
                </div>
             </div>
          </div>
          <div class="row gutters">
             <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-12">
                <div class="card gradient-green card-170">
                   <div class="card-body">
                      <div class="uploading-container">
                         <a href="#" class="pause" id="play-pause">
                            <i class="material-icons">group_add</i>
                         </a>
                         <div class="upload-progress-container">
                            <div class="upload-icon"><img src="{{asset('images/upload.svg')}}" alt="Download"></div>
                            <div class="upload-progress">
                               <div class="upload-space-container">
                                  <div>45<span>%</span><span class="ms-2"> &nbsp;&nbsp;Staff Attendance</span></div>
                                  <div>100</div>
                               </div>
                               <div class="progress">
                                  <div class="progress-bar" role="progressbar" style="width: 45%" aria-valuenow="45" aria-valuemin="0" aria-valuemax="100"></div>
                               </div>
                            </div>
                         </div>
                      </div>
                   </div>
                </div>
             </div>

             <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-12">
                <div class="card gradient-teal-cream card-170">
                   <div class="card-body">
                      <div class="notifications-container">
                         <div class="notify-details-block">
                            <div class="notify-icon" >
                                <i class="material-icons animate__animated animate__swing animate__infinite">notifications</i>
                            </div>
                            <div class="notify-details">
                               <h5>Notifications</h5>
                               <h3>0</h3>
                               <div class="notify-high-low"></div>
                            </div>
                         </div>
                         <div id="notificationsGraph" class="apex-hide-lines"></div>
                      </div>
                   </div>
                </div>
             </div>

             <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-12">
                <div class="card gradient-yellow card-170">
                   <div class="card-body">
                      <div class="active-users-container">
                         <div class="active-users-details">
                            <div class="details">
                               <h6>Purchases</h6>
                               <h3><span class="purchaseCount">{{$purchase_count}}</span></h3>
                               <span class="active-users-high-low">Today &nbsp; - &nbsp; {{$purchase_todaycount}}</span>
                            </div>
                            <div id="signupsGraph" class="apex-hide-lines"></div>
                            <div class="active-users-icon"><i class="material-icons">shopping_cart</i></div>
                         </div>
                      </div>
                   </div>
                </div>
             </div>

             <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-12">
                <div class="card gradient-peach3 card-170">
                   <div class="card-body">
                      <div class="active-users-container">
                         <div class="active-users-details">
                            <div class="details">
                                <h6>Drivers</h6>
                                <h3><span class="totalDrivers"></span> - <i class="material-icons">motorcycle</i></h3>
                                <span class="active-users-high-low">
                                    Delivered - <span class="orderDelivered">0</span>
                                </span>
                                <br><br>
                                <span class="active-users-high-low">
                                    On the way - <span class="orderOntheway">0</span>
                                </span>
                            </div>
                            
                            <div class="active-users-icon"><i class="material-icons">directions_bike</i></div>
                         </div>
                      </div>
                   </div>
                </div>
             </div>

             

          </div>
        </div>


        <?php /* ?>

        <div class="row dashboard-top-section-row1">

            <div class="col-md-6">
                <div class="row">

            <div class="col-lg-12 col-6 small-box-column"><a href="{!! route('orders.index') !!}" class="small-box-footer">
                <!-- small box -->
                <div class="small-box bg-info small-box1">
                    <div class="inner">
                        <h3>{{trans('lang.dashboard_online_orders')}}</h3>
                        <p>{{trans('lang.dashboard_today_orders')}} &nbsp; - &nbsp; {{$orderstodayCount}}
                        <br> {{trans('lang.dashboard_total_orders')}} &nbsp; - &nbsp; <span class="orderCount">{{$ordersCount}}</span></p> 
                    </div>
                    <div class="icon">
                        <i class="fa fa-shopping-bag"></i>
                    </div>
                    <!-- <a href="{!! route('orders.index') !!}" class="small-box-footer">
                        {{trans('lang.dashboard_more_info')}}
                        <i class="fa fa-arrow-circle-right"></i>
                    </a> -->
                </div>
            </a></div>

            <div class="col-lg-6 col-6 small-box-column"><a href="{!! route('purchaseInvoice.index') !!}" class="small-box-footer">
                <!-- small box -->
                <div class="small-box bg-secondary small-box4">
                    <div class="inner">
                        <h3>{{trans('lang.dashboard_purchase')}}</h3>
                        
                        <p>{{trans('lang.dashboard_today_purchase')}} &nbsp; - &nbsp; {{$purchase_todaycount}}
                        <br> {{trans('lang.dashboard_total_purchase')}} &nbsp; - &nbsp; <span class="purchaseCount">{{$purchase_count}}</span></p> 
                    </div>
                    <div class="icon">
                        <i class="fa fa-shopping-cart"></i>
                    </div>
                    <!-- <a href="{!! route('purchaseInvoice.index') !!}" class="small-box-footer">
                        {{trans('lang.dashboard_more_info')}}
                        <i class="fa fa-arrow-circle-right"></i>
                    </a> -->
                </div>
            </a></div>

            <div class="col-lg-6 col-6 small-box-column"><a href="{!! route('salesInvoice.index') !!}" class="small-box-footer">
                <!-- small box -->
                <div class="small-box bg-primary small-box2">
                    <div class="inner">
                        <h3>{{trans('lang.dashboard_sales')}}</h3>
                        
                        <p>{{trans('lang.dashboard_today_sales')}} &nbsp; - &nbsp; {{$sales_todaycount}}
                        <br> {{trans('lang.dashboard_total_sales')}} &nbsp; - &nbsp; <span class="salesCount">{{$sales_count}}</span></p> 
                    </div>
                    <div class="icon">
                        <i class="fa fa-shopping-basket"></i>
                    </div>
                    <!-- <a href="{!! route('salesInvoice.index') !!}" class="small-box-footer">
                        {{trans('lang.dashboard_more_info')}}
                        <i class="fa fa-arrow-circle-right"></i>
                    </a> -->
                </div>
            </a></div>

            <div class="col-lg-6 col-6 small-box-column"><a href="{{ route('reports.show') }}?report_type=wastage-report" class="small-box-footer">
                <!-- small box -->
                <div class="small-box bg-dark small-box3">
                    <div class="inner">
                        <h3>{{trans('lang.dashboard_total_sales')}}</h3>
                        <p>
                            Orders - <span class="totalOrders">{{$totalOrders}}</span>
                            <br>    
                            Earnings - {{setting('default_currency')}} <span class="totalEarnings">{{$totalEarnings}}</span>
                        </p>
                    </div>
                    <div class="icon">
                        <i class="fa fa-pie-chart"></i>
                    </div>
                    <!-- <a href="{{ route('reports.show') }}?report_type=wastage-report" class="small-box-footer">{{trans('lang.dashboard_more_info')}}
                        <i class="fa fa-arrow-circle-right"></i>
                    </a> -->
                </div>
            </a></div>

             <!-- ./col -->
            <div class="col-lg-6 col-6 small-box-column"><a href="{!! route('markets.index') !!}" class="small-box-footer">
                <!-- small box -->
                <div class="small-box bg-success small-box8">
                    <div class="inner">
                        <h3>{{trans('lang.market_plural')}}</h3>
                        <p class="partiesCount">{{$marketsCount}}</p>
                    </div>
                    <div class="icon">
                        <i class="fa fa-cutlery"></i>
                    </div>
                    <!-- <a href="{!! route('markets.index') !!}" class="small-box-footer">{{trans('lang.dashboard_more_info')}}
                        <i class="fa fa-arrow-circle-right"></i></a> -->
                </div>
            </a></div>

            

            </div>
        </div>

        <div class="col-md-6">
            <div class="row">

                 <!-- ./col -->
            <div class="col-lg-6 col-6 small-box-column"><a href="{!! route('payments.index') !!}" class="small-box-footer">
                <!-- small box -->
                <div class="small-box bg-danger small-box5">
                    <div class="inner">
                        <h3>
                            {{trans('lang.dashboard_online_earnings')}} 
                            <span style="font-size: 11px">({{trans('lang.dashboard_after taxes')}})</span>
                        </h3>

                        @if(setting('currency_right', false) != false)

                            <p>
                                {{trans('lang.dashboard_total_earnings')}} &nbsp; - &nbsp; 
                                <span class="totalEarning">{{$earning}}</span> {{setting('default_currency')}} 
                                <br> 
                                {{trans('lang.dashboard_total_purchase')}} &nbsp; - &nbsp; {{$today_earning}}{{setting('default_currency')}} 
                            </p>
                           
                        @else
                            <p>
                                {{trans('lang.dashboard_total_earnings')}} &nbsp; - &nbsp; {{setting('default_currency')}}
                                <span class="totalEarning">{{$earning}}</span> 
                                <br>
                                {{trans('lang.dashboard_today_earnings')}} &nbsp; - &nbsp; {{setting('default_currency')}}{{$today_earning}}
                            </p>
                        @endif
                        
                    </div>
                    <div class="icon">
                        <i class="fa fa-credit-card-alt"></i>
                    </div>
                    <!-- <a href="{!! route('payments.index') !!}" class="small-box-footer">
                        {{trans('lang.dashboard_more_info')}}
                        <i class="fa fa-arrow-circle-right"></i>
                    </a> -->
                </div>
            </a></div>

            <!-- ./col -->
            <div class="col-lg-6 col-6 small-box-column"><a href="{!! route('salesInvoice.index') !!}" class="small-box-footer">
                <!-- small box -->
                <div class="small-box bg-warning small-box6">
                    <div class="inner">
                         <h3>
                            {{trans('lang.dashboard_sales_earnings')}} 
                            <span style="font-size: 11px">({{trans('lang.dashboard_after taxes')}})</span>
                        </h3>

                        @if(setting('currency_right', false) != false)
                            <p>
                                {{trans('lang.dashboard_total_earnings')}} &nbsp; - &nbsp; 
                                <span class="salesEarning">{{$sales_earning}}</span> {{setting('default_currency')}} 
                                <br> 
                                {{trans('lang.dashboard_total_purchase')}} &nbsp; - &nbsp; {{$today_sales_earning}}{{setting('default_currency')}}
                            </p>
                        @else
                            <p>
                                {{trans('lang.dashboard_total_earnings')}} &nbsp; - &nbsp; {{setting('default_currency')}}
                                <span class="salesEarning">{{$sales_earning}}</span>
                                <br>
                                {{trans('lang.dashboard_today_earnings')}} &nbsp; - &nbsp; {{setting('default_currency')}}{{$today_sales_earning}}
                            </p>
                        @endif
                    </div>

                    <div class="icon">
                        <i class="fa fa-money"></i>
                    </div>
                    
                    <!-- <a href="{!! route('salesInvoice.index') !!}" class="small-box-footer">{{trans('lang.dashboard_more_info')}}
                    <i class="fa fa-arrow-circle-right"></i></a> -->

                </div>
            </a></div>


            <div class="col-lg-12 col-6 small-box-column"><a href="{!! route('products.index') !!}" class="small-box-footer">
                <!-- small box -->
                <div class="small-box bg-danger small-box7">
                    <div class="inner">
                        <h3>{{trans('lang.product_plural')}}</h3>
                        <p> 
                          {{trans('lang.dashboard_profit_products')}} - <span class="profitProducts">{{$profit_count}}</span>
                          <br>
                          {{trans('lang.dashboard_loss_products')}} - <span class="lossProducts">{{$loss_count}}</span>
                          <br>
                          {{trans('lang.dashboard_wastage_products')}} - <span class="wastageProducts">{{$wastage}}</span>
                        </p>
                    </div>
                    <div class="icon">
                        <i class="fa fa-cube"></i>
                    </div>
                    <!-- <a href="{!! route('products.index') !!}" class="small-box-footer">{{trans('lang.dashboard_more_info')}}
                        <i class="fa fa-arrow-circle-right"></i>
                    </a> -->
                </div>
            </a></div>

            
            
            </div>
        </div>
            
        <?php /*/ ?>           

        </div>
        <!-- /.row -->

         <div class="row dashboard-top-section-row2">
        

        </div>

        <div class="row">
            <div class="col-lg-6 dashboard-card-column">
                <div class="card dashboard-card" style="height: 448px;">
                    <div class="card-header no-border">
                        <div class="d-flex justify-content-between">
                            <h3 class="card-title">{{trans('lang.earning_plural')}}</h3>
                            <a href="{!! route('payments.index') !!}">{{trans('lang.dashboard_view_all_payments')}}</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="d-flex">
                            <p class="d-flex flex-column">
                                @if(setting('currency_right', false) != false)
                                    <span class="text-bold text-lg">
                                        <span class="totalEarning">{{$earning}}</span>{{setting('default_currency')}}
                                    </span>
                                @else
                                    <span class="text-bold text-lg">
                                        {{setting('default_currency')}}
                                        <span class="totalEarning">{{$earning}}</span>
                                    </span>
                                @endif
                                <span>{{trans('lang.dashboard_earning_over_time')}}</span>
                            </p>
                            <p class="ml-auto d-flex flex-column text-right">
                                <span class="text-success"> <span class="orderCount">{{$ordersCount}}</span></span></span>
                                <span class="text-muted">{{trans('lang.dashboard_total_orders')}}</span>
                            </p>
                        </div>
                        <!-- /.d-flex -->

                        <div class="position-relative mb-4">
                            <canvas id="sales-chart" height="200"></canvas>
                        </div>

                        <div class="d-flex flex-row justify-content-end">
                            <span class="mr-2"> <i class="fa fa-square text-primary"></i> {{trans('lang.dashboard_this_year')}} </span>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-6 dashboard-card-column">
                <div class="card dashboard-card">
                    <div class="card-header no-border">
                        <h3 class="card-title">{{trans('lang.dashboard_sales_online_report')}}</h3>
                        <div class="card-tools">
                            <a href="{{route('orders.index')}}" class="btn btn-tool btn-sm"><i class="fa fa-bars"></i> </a>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        
                        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.2/Chart.js"></script>
                        <canvas id="myChart"></canvas>

                        <script>
                            
                        </script>

                    </div>
                </div>
            </div>
            
            <div class="col-lg-6 dashboard-card-column">
                
                <!--Online Orders-->
                <div class="card dashboard-card">
                    <div class="card-header no-border">
                        <h3 class="card-title">{{trans('lang.order_plural')}}</h3>
                        <div class="card-tools">
                            <a href="{{route('orders.index')}}" class="btn btn-tool btn-sm"><i class="fa fa-bars"></i> </a>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <table class="table table-striped table-valign-middle">
                            <thead>
                            <tr>
                               
                                <th>{{trans('lang.order_id')}}</th>
                                <th>{{trans('lang.order_user_id')}}</th>
                                <th>{{trans('lang.order_total')}}</th>
                                <th>{{trans('lang.order_order_status_id')}}</th>
                                <th>{{trans('lang.actions')}}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($orders as $order)

                                <tr>
                                    <td>{!! $order->id !!}</td>
                                    <td><a href="{{route('markets.show',$order->market_id)}}">{!! $order->user->name !!}</a></td>
                                    <td>{!! getPriceColumn($order,'order_amount') !!}</td>
                                    <td>{!! $order->orderStatus->status !!}</td>
                                    <td class="text-center">
                                        <a href="{!! route('orders.show',$order->id) !!}" class="text-muted"> <i class="fa fa-eye"></i> </a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                
                 <!--Party Levels View-->
                <div class="card dashboard-card">
                    <div class="card-header no-border">
                        <h3 class="card-title">{{trans('lang.party_level_view')}}</h3><br>
                        <div class="card-tools">
                            <a href="{{route('markets.index')}}" class="btn btn-tool btn-sm"><i class="fa fa-bars"></i> </a>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <table class="table table-striped table-valign-middle">
                            <thead>
                            <tr>
                                 <th>{{ trans('lang.complaint_feedback') }}</th>
                                 @foreach($customer_levels  as $val)
                                  <th>{{ $val->name }}</th>
                                 @endforeach
                            </tr>
                            </thead>
                            <tbody>
                            
                                <tr>
                                      <td>{{ trans('lang.active_customers') }}</td>
                                      @foreach($customer_levels  as $val)
                                      <td>{!! DB::table('users')->leftJoin('user_markets', 'user_markets.user_id', '=', 'users.id')->leftJoin('markets', 'markets.id', '=', 'user_markets.market_id')->where('level',$val->id)->where('active',1)->count(); !!}</td>
                                      @endforeach
                                </tr>
                                <tr>
                                      <td>{{ trans('lang.inactive_customers') }}</td>
                                       @foreach($customer_levels  as $val)
                                      <td>{!! DB::table('users')->leftJoin('user_markets', 'user_markets.user_id', '=', 'users.id')->leftJoin('markets', 'markets.id', '=', 'user_markets.market_id')->where('level',$val->id)->where('active',0)->count(); !!}</td>
                                      @endforeach
                                </tr>
                                <tr>
                                    <td>{{ trans('lang.total_customers') }}</td>
                                     @foreach($customer_levels  as $val)
                                      <td>{!! DB::table('users')->leftJoin('user_markets', 'user_markets.user_id', '=', 'users.id')->leftJoin('markets', 'markets.id', '=', 'user_markets.market_id')->where('level',$val->id)->count(); !!}</td>
                                      @endforeach
                                    
                                </tr> 
                            </tbody>
                        </table>
                    </div>
                </div>
                
                <!--Party Stream View-->
                 <div class="card dashboard-card">
                    <div class="card-header no-border">
                        <h3 class="card-title">{{trans('lang.party_stream_view')}}</h3><br>
                        <div class="card-tools">
                            <a href="{{route('markets.index')}}" class="btn btn-tool btn-sm"><i class="fa fa-bars"></i> </a>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <table class="table table-striped table-valign-middle">
                            <thead>
                            <tr>
                                <th>{{trans('lang.report_customers')}}</th>
                                <th>{{trans('lang.active')}}</th>
                                <th>{{trans('lang.inactive')}}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if(count($stream)>0)
                            @foreach($stream  as $val)
                             
                                <tr>
                                    <td>{!! $val->name !!}</td>
                                    <td>{!! DB::table('markets')->where('stream',$val->id)->where('active',1)->count(); !!}</td>
                                    <td>{!! DB::table('markets')->where('stream',$val->id)->where('active',0)->count(); !!}</td>
                                </tr>
                            @endforeach
                            @endif
                             <tr>
                                 <td>{{ trans('lang.total_customers') }}</td>
                                 <td>{!! DB::table('markets')->where('active',1)->count('stream'); !!}</td>
                                 <td>{!! DB::table('markets')->where('active',0)->count('stream'); !!}</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                
                <!--Party Feedback View-->
                <div class="card dashboard-card">
                    <div class="card-header no-border">
                        <h3 class="card-title">{{trans('lang.party_feedback_view')}}</h3><br>
                        <div class="card-tools">
                            <a href="{{route('driverReviews.index')}}" class="btn btn-tool btn-sm"><i class="fa fa-bars"></i> </a>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <table class="table table-striped table-valign-middle">
                            <thead>
                            <tr>
                              <th>{{trans('lang.complaint_feedback')}}</th>
                                <th>{{trans('lang.driver_plural')}}</th>
                                <th>{{trans('lang.customers')}}</th>
                            </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    @php $arr = array(1,2,3,4,5); @endphp
                                    <td>1 Star</td>
                                    <td>{!! DB::table('driver_reviews')->where('rate',1)->count('rate'); !!}</td>
                                    <td>{!! DB::table('customer_farmer_reviews')->where('rate',1)->count('rate'); !!}</td>
                                </tr>
                                <tr>
                                    <td>2 Star</td>
                                    <td>{!! DB::table('driver_reviews')->where('rate',2)->count('rate'); !!}</td>
                                    <td>{!! DB::table('customer_farmer_reviews')->where('rate',2)->count('rate'); !!}</td>
                                 </tr>
                                <tr>
                                    <td>3 Star</td>
                                    <td>{!! DB::table('driver_reviews')->where('rate',3)->count('rate'); !!}</td>
                                    <td>{!! DB::table('customer_farmer_reviews')->where('rate',3)->count('rate'); !!}</td>
                                 </tr>
                                <tr>
                                    <td>4 Star</td>
                                    <td>{!! DB::table('driver_reviews')->where('rate',4)->count('rate'); !!}</td>
                                    <td>{!! DB::table('customer_farmer_reviews')->where('rate',4)->count('rate'); !!}</td>
                                 </tr>
                                <tr>
                                    <td>5 Star</td>
                                    <td>{!! DB::table('driver_reviews')->where('rate',5)->count('rate'); !!}</td>
                                    <td>{!! DB::table('customer_farmer_reviews')->where('rate',5)->count('rate'); !!}</td>
                                 </tr>
                                <tr>
                                    <td>{{ trans('lang.total') }}</td>
                                    <td>{!! DB::table('driver_reviews')->count('rate'); !!}</td>
                                    <td>{!! DB::table('customer_farmer_reviews')->count('rate'); !!}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                
                <!--Party Reward Points View-->
                 <div class="card dashboard-card">
                    <div class="card-header no-border">
                        <h3 class="card-title">{{trans('lang.party_reward_points')}}</h3><br>
                        <div class="card-tools">
                            <a href="{{route('orders.index')}}" class="btn btn-tool btn-sm"><i class="fa fa-bars"></i> </a>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <table class="table table-striped table-valign-middle">
                            <thead>
                             <tr>
                              <th>{{trans('lang.indicative_charity_impact')}}</th>
                                <th>{!! DB::table('orders')->sum('contribution_amount') !!}</th>
                            </tr>
                            </thead>
                        </table>
                    </div>
                </div>
                
                <!--Sales VS Purchase Category-->
                 <div class="card dashboard-card">
                    <div class="card-header no-border">
                        <h3 class="card-title">{{trans('lang.sales_purchase_category')}}</h3><br>
                        <div class="card-tools">
                            <a href="{{route('products.index')}}" class="btn btn-tool btn-sm"><i class="fa fa-bars"></i> </a>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <table class="table table-striped table-valign-middle">
                            <thead>
                            <tr>
                              <th>SO | PO - S-CAT</th>
                                <th>{{trans('lang.today')}}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if(count($subcategories)>0)
                            @foreach($subcategories  as $val)
                             
                                <tr>
                                    <td>{!! $val->name !!}</td>
                                    <td>{!! DB::table('products')->where('subcategory_id',$val->id)->whereRaw('Date(created_at) = CURDATE()')->count(); !!}</td>
                                </tr>
                            @endforeach
                            @endif
                                <tr>
                                    <td>{{trans('lang.total')}}</td>
                                    <td>{!! DB::table('products')->whereRaw('Date(created_at) = CURDATE()')->count('subcategory_id'); !!}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                
                 <!--Party Flow (Customers | Farmers)-->
                  <div class="card dashboard-card">
                    <div class="card-header no-border">
                        <h3 class="card-title">{{trans('lang.party_flow')}}</h3><br>
                        <div class="card-tools">
                            <a href="{{route('markets.index')}}" class="btn btn-tool btn-sm"><i class="fa fa-bars"></i> </a>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <table class="table table-striped table-valign-middle">
                            <thead>
                            <tr>
                              <th>{{trans('lang.party_registeration_status')}}</th>
                               <th>{{trans('lang.farmers')}}</th>
                               <th>{{trans('lang.customers')}}</th>
                            </tr>
                            </thead>
                            <tbody>
                                  @php
                                    $active_farmers_accounts = DB::table('markets')->where('type',2)->where('active',1)->count();
                                    $inactive_farmers_accounts = DB::table('markets')->where('type',2)->where('active',0)->count();
                                    
                                    $active_customers_accounts = DB::table('markets')->where('type','!=',2)->where('active',1)->count();
                                    $inactive_customers_accounts = DB::table('markets')->where('type','!=',2)->where('active',0)->count();
                                    
                                    @endphp
                                 <tr>
                                    <td>{{trans('lang.new_registerations')}}</td>
                                    <td>{!! DB::table('markets')->where('type',2)->whereRaw('Date(created_at) = CURDATE()')->where('active',1)->count(); !!}</td>
                                    <td>{!! DB::table('markets')->where('type','!=',2)->whereRaw('Date(created_at) = CURDATE()')->where('active',1)->count(); !!}</td>
                                </tr>
                                
                                 <tr>
                                    <td>{{trans('lang.deregisterations')}}</td>
                                    <td>{!! DB::table('markets')->where('type',2)->whereRaw('Date(created_at) = CURDATE()')->where('active',0)->count(); !!}</td>
                                    <td>{!! DB::table('markets')->where('type','!=',2)->whereRaw('Date(created_at) = CURDATE()')->where('active',0)->count(); !!}</td>
                                </tr>
                                
                                <tr>
                                    <td>{{trans('lang.total_active_accounts')}}</td>
                                    <td>{!! $active_farmers_accounts !!}</td>
                                    <td>{!! $active_customers_accounts !!}</td>
                                </tr>
                         
                                <tr>
                                    <td>{{trans('lang.total_inactive_accounts')}}</td>
                                    <td>{!! $inactive_farmers_accounts !!}</td>
                                    <td>{!! $inactive_customers_accounts !!}</td>
                                </tr>
                                
                                 <tr>
                                    <td>{{trans('lang.total')}}</td>
                                    <td>{!!  $active_farmers_accounts+$inactive_farmers_accounts !!}</td>
                                    <td>{!! $active_customers_accounts+$inactive_customers_accounts !!}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                
                <!--DRIVER'S PRIMARY PERFORMANCE INDICATORS-->
                <div class="card dashboard-card">
                    <div class="card-header no-border">
                        <h3 class="card-title">{{trans('lang.drivers_primary_performance')}}</h3><br>
                        <div class="card-tools">
                            <a href="{{route('drivers.index')}}" class="btn btn-tool btn-sm"><i class="fa fa-bars"></i> </a>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <table class="table table-striped table-valign-middle">
                            <thead>
                            <tr>
                              <th>P - INDICATORS - 1</th>
                               <th>{{trans('lang.number')}}</th>
                               <th>{{trans('lang.amount')}}</th>
                               <th>{{trans('lang.quantities')}}</th>
                            </tr>
                            </thead>
                            <tbody>
                                @php
                                $delivery_completed_number = DB::table('orders')->where('order_status_id',5)->count();
                                $delivery_completed_amount = DB::table('orders')->where('order_status_id',5)->sum('order_amount');
                                $delivery_completed_quantities = DB::table('orders')->leftJoin('product_orders', 'product_orders.order_id', '=', 'orders.id')->where('order_status_id',5)->sum('quantity');
                                
                                $delivery_pending_number = DB::table('orders')->where('order_status_id','<',5)->count();
                                $delivery_pending_amount = DB::table('orders')->where('order_status_id','<',5)->sum('order_amount');
                                $delivery_pending_quantities = DB::table('orders')->leftJoin('product_orders', 'product_orders.order_id', '=', 'orders.id')->where('order_status_id','<',5)->sum('quantity');
                                
                                @endphp
                                
                                 <tr>
                                    <td>{!! trans('lang.deliveries_completed') !!}</td>
                                    <td>{!! $delivery_completed_number !!}</td>
                                    <td>{!! $delivery_completed_amount !!}</td>
                                    <td>{!! $delivery_completed_quantities !!}</td>
                                </tr>
                                <tr>
                                    <td>{!! trans('lang.deliveries_pending') !!}</td>
                                    <td>{!! $delivery_pending_number !!}</td>
                                    <td>{!! $delivery_pending_amount !!}</td>
                                    <td>{!! $delivery_pending_quantities !!}</td>
                                </tr>
                                
                                 <tr>
                                    <td>{{trans('lang.total')}}</td>
                                    <td>{!!  $delivery_completed_number+$delivery_pending_number !!}</td>
                                    <td>{!! $delivery_completed_amount+$delivery_pending_amount !!}</td>
                                    <td>{!! $delivery_completed_quantities+$delivery_pending_quantities !!}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                
                <!--DRIVER'S OTHER PERFORMANCE INDICATORS-->
                <div class="card dashboard-card">
                    <div class="card-header no-border">
                        <h3 class="card-title">{{trans('lang.drivers_other_performance')}}</h3><br>
                        <div class="card-tools">
                            <a href="{{route('drivers.index')}}" class="btn btn-tool btn-sm"><i class="fa fa-bars"></i> </a>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <table class="table table-striped table-valign-middle">
                            <thead>
                            <tr>
                              <th>P - INDICATORS - 2</th>
                              
                              @foreach($drivers as $val)
                               <th>{{ $val->name }}</th>
                               @endforeach
                          
                            </tr>
                            </thead>
                            <tbody>
                                @php
                                $delivery_completed_number = DB::table('orders')->where('order_status_id',5)->count();
                                $delivery_completed_amount = DB::table('orders')->where('order_status_id',5)->sum('order_amount');
                                $delivery_completed_quantities = DB::table('orders')->leftJoin('product_orders', 'product_orders.order_id', '=', 'orders.id')->where('order_status_id',5)->sum('quantity');
                                
                                $delivery_pending_number = DB::table('orders')->where('order_status_id','<',5)->count();
                                $delivery_pending_amount = DB::table('orders')->where('order_status_id','<',5)->sum('order_amount');
                                $delivery_pending_quantities = DB::table('orders')->leftJoin('product_orders', 'product_orders.order_id', '=', 'orders.id')->where('order_status_id','<',5)->sum('quantity');
                                
                                @endphp
                                
                                 <tr>
                                    <td>{!! trans('lang.quantities_delivered') !!}</td>
                                    @foreach($drivers as $val)
                                    <td>{!! DB::table('orders')->leftJoin('product_orders', 'product_orders.order_id', '=', 'orders.id')->where('order_status_id',5)->where('driver_id',$val->id)->sum('quantity'); !!}</td>
                                    @endforeach
                                </tr>
                                 <tr>
                                    <td>{!! trans('lang.amount_collected') !!}</td>
                                     @foreach($drivers as $val)
                                    <td>{!! DB::table('orders')->leftJoin('payments', 'payments.id', '=', 'orders.payment_id')->where('driver_id',$val->id)->sum('price'); !!}</td>
                                    @endforeach
                                </tr>
                                  <tr>
                                    <td>{!! trans('lang.distance_travelled') !!}</td>
                                    @foreach($drivers as $val)
                                    <td>{!! DB::table('orders')->where('order_status_id',5)->where('driver_id',$val->id)->sum('delivery_distance'); !!}</td>
                                    @endforeach
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                
            </div>
            
            
            <div class="col-lg-6 dashboard-card-column">
                
                <!--Sales Orders Vs Purchase Orders-->
                <div class="card dashboard-card">
                    <div class="card-header no-border">
                        <h3 class="card-title">{{trans('lang.sales_order').' Vs '.trans('lang.purchase_order_plural')}}</h3><br>
                        <div class="card-tools">
                            <a href="{{route('orders.index')}}" class="btn btn-tool btn-sm"><i class="fa fa-bars"></i> </a>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <table class="table table-striped table-valign-middle">
                            <thead>
                            <tr>
                                <th>{{trans('lang.orders')}}</th>
                                <th>{{trans('lang.today')}}</th>
                            </tr>
                            </thead>
                            <tbody>
                                <tr>
                                     <td>{!! trans('lang.sales_order') !!}</td>
                                    <td>{!! $sales_order_count !!}</td>
                                </tr>
                                  <tr>
                                     <td>{!! trans('lang.purchase_order_plural') !!}</td>
                                    <td>{!! $purchase_order_count !!}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                
                <!--Sales Order Status-->
                <div class="card dashboard-card">
                    <div class="card-header no-border">
                        <h3 class="card-title">{{trans('lang.sales_order_status')}}</h3><br>
                        <div class="card-tools">
                            <a href="{{route('orders.index')}}" class="btn btn-tool btn-sm"><i class="fa fa-bars"></i> </a>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <table class="table table-striped table-valign-middle">
                            <thead>
                            <tr>
                                <th>SO - STATUS</th>
                                <th>{{trans('lang.today')}}</th>
                            </tr>
                            </thead>
                            <tbody>
                              @if(count($sales_order_status)>0)
                              @foreach($sales_order_status  as $val)
                              <tr>
                                 
                                    <td>{!! $val->status !!}</td>
                                   <td>{!! DB::table('orders')->where('order_status_id',$val->id)->count(); !!}</td>
                                </tr>
                            @endforeach
                            @endif
                             <tr>
                                 <td>{{ trans('lang.total') }}</td>
                                 <td>{!! DB::table('orders')->count(); !!}</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                
                 <!--Party Promo codes & Discounts-->
                 <div class="card dashboard-card">
                    <div class="card-header no-border">
                        <h3 class="card-title">{{trans('lang.party_promo_codes_discounts')}}</h3><br>
                        <div class="card-tools">
                            <a href="{{route('coupons.index')}}" class="btn btn-tool btn-sm"><i class="fa fa-bars"></i> </a>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <table class="table table-striped table-valign-middle">
                            <thead>
                            <tr>
                              <th>{{trans('lang.customers')}}</th>
                                <th>{{trans('lang.active')}}</th>
                                <th>{{trans('lang.inactive')}}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if(count($coupons)>0)
                            @foreach($coupons  as $val)
                             
                                <tr>
                                    <td>{!! $val->code !!}</td>
                                    <td>{!! DB::table('coupons')->where('id',$val->id)->where('enabled',1)->count(); !!}</td>
                                    <td>{!! DB::table('coupons')->where('id',$val->id)->where('enabled',0)->count(); !!}</td>
                                   
                                </tr>
                            @endforeach
                            @endif
                             <tr>
                                 <td>{{ trans('lang.total_customers') }}</td>
                                 <td>{!! DB::table('coupons')->where('enabled',1)->count(); !!}</td>
                                 <td>{!! DB::table('coupons')->where('enabled',0)->count(); !!}</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                
                <!--Party Complaints Vs Compliments-->
                    <div class="card dashboard-card">
                    <div class="card-header no-border">
                        <h3 class="card-title">{{trans('lang.party_complaints_compliments')}}</h3><br>
                        <div class="card-tools">
                            <a href="{{route('complaints.index')}}" class="btn btn-tool btn-sm"><i class="fa fa-bars"></i> </a>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <table class="table table-striped table-valign-middle">
                            <thead>
                            <tr>
                              <th>{{trans('lang.complaint_feedback')}}</th>
                            </tr>
                            </thead>
                            <tbody>
                                @php $driver_review = DB::table('driver_reviews')->count();
                                     $customer_farmer_reviews = DB::table('customer_farmer_reviews')->count();  
                                     $complaints =  DB::table('complaints')->count();
                                @endphp
                                
                                <tr>
                                    <td>{{trans('lang.compliments')}}</td>
                                    <td>{!! $driver_review + $customer_farmer_reviews !!}</td>
                                </tr>
                                <tr>
                                    <td>{{trans('lang.complaints')}}</td>
                                    <td>{!! $complaints !!}</td>
                                 </tr>
                                <tr>
                                    <td>{{ trans('lang.total') }}</td>
                                    <td>{{ $driver_review + $customer_farmer_reviews + $complaints }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                
                
                 <!--Party Reward Points View-->
                <div class="card dashboard-card">
                    <div class="card-header no-border">
                        <h3 class="card-title">{{trans('lang.party_reward_points')}}</h3><br>
                        <div class="card-tools">
                            <a href="{{route('rewards.index')}}" class="btn btn-tool btn-sm"><i class="fa fa-bars"></i> </a>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <table class="table table-striped table-valign-middle">
                            <thead>
                             <tr>
                              <th>{{trans('lang.customers')}}</th>
                              <th>{{trans('lang.active')}}</th>
                                <!--<th>{{trans('lang.active')}}</th>-->
                                <!--<th>{{trans('lang.inactive')}}</th>-->
                            </tr>
                            </thead>
                            <tbody>
                                @php 
                                $earned = DB::table('loyality_points_tracker')->sum('points');
                                $redeemed = DB::table('loyality_point_usage')->sum('usage_points');
                                
                                @endphp
                                <tr>
                                    <td>{{trans('lang.rewards_earned')}}</td>
                                     <td>{!! $earned !!}</td>
                                </tr>
                                <tr>
                                    <td>{{trans('lang.rewards_redeemed')}}</td>
                                     <td>{!! $redeemed !!}</td>
                                 </tr>
                                <tr>
                                    <td>{{ trans('lang.balance_rewards') }}</td>
                                     <td>{!! $earned - $redeemed !!}</td>
                                </tr>
                                <tr>
                                    <td>{{ trans('lang.total_reward_points') }}</td>
                                     <td>{!! $earned !!}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                
                 <!--Sales VS Purchase Type-->
                <div class="card dashboard-card">
                    <div class="card-header no-border">
                        <h3 class="card-title">{{trans('lang.sales_purchase_type')}}</h3><br>
                        <div class="card-tools">
                            <a href="{{route('products.index')}}" class="btn btn-tool btn-sm"><i class="fa fa-bars"></i> </a>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <table class="table table-striped table-valign-middle">
                            <thead>
                            <tr>
                              <th>SO | PO - CAT</th>
                                <th>{{trans('lang.today')}}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if(count($categories)>0)
                            @foreach($categories  as $val)
                             
                                <tr>
                                    <td>{!! $val->name !!}</td>
                                   <td>{!! DB::table('products')->where('category_id',$val->id)->whereRaw('Date(created_at) = CURDATE()')->count(); !!}</td>
                                </tr>
                            @endforeach
                            @endif
                                <tr>
                                    <td>{{trans('lang.total')}}</td>
                                    <td>{!! DB::table('products')->whereRaw('Date(created_at) = CURDATE()')->count('category_id'); !!}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                
                <!--Party Sub Type-->
                 <div class="card dashboard-card">
                    <div class="card-header no-border">
                        <h3 class="card-title">{{trans('lang.party_sub_type')}}</h3><br>
                        <div class="card-tools">
                            <a href="{{route('markets.index')}}" class="btn btn-tool btn-sm"><i class="fa fa-bars"></i> </a>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <table class="table table-striped table-valign-middle">
                            <thead>
                            <tr>
                              <th>Party - SUB TYPE</th>
                                <th>{{trans('lang.today')}}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if(count($party_sub_types)>0)
                            @foreach($party_sub_types  as $val)
                             
                                <tr>
                                    <td>{!! $val->name !!}</td>
                                    <td>{!! DB::table('markets')->where('sub_type',$val->id)->whereRaw('Date(created_at) = CURDATE()')->count(); !!}</td>
                                </tr>
                            @endforeach
                            @endif
                                <tr>
                                    <td>{{trans('lang.total')}}</td>
                                    <td>{!! DB::table('markets')->whereRaw('Date(created_at) = CURDATE()')->count('sub_type'); !!}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
           
            
        </div>
    </div>

@endsection
@push('scripts_lib')
    <script src="{{asset('plugins/chart.js/Chart.min.js')}}"></script>
@endpush
@push('scripts')
    <script type="text/javascript">
        var data = [1000, 2000, 3000, 2500, 2700, 2500, 3000];
        var labels = ['JUN', 'JUL', 'AUG', 'SEP', 'OCT', 'NOV', 'DEC'];

        function renderChart(chartNode, data, labels) {
            var ticksStyle = {
                fontColor: '#495057',
                fontStyle: 'bold'
            };

            var mode = 'index';
            var intersect = true;
            return new Chart(chartNode, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [
                        {
                            backgroundColor: '#007bff',
                            borderColor: '#007bff',
                            data: data
                        }
                    ]
                },
                options: {
                    maintainAspectRatio: false,
                    tooltips: {
                        mode: mode,
                        intersect: intersect
                    },
                    hover: {
                        mode: mode,
                        intersect: intersect
                    },
                    legend: {
                        display: false
                    },
                    scales: {
                        yAxes: [{
                            // display: false,
                            gridLines: {
                                display: true,
                                lineWidth: '4px',
                                color: 'rgba(0, 0, 0, .2)',
                                zeroLineColor: 'transparent'
                            },
                            ticks: $.extend({
                                beginAtZero: true,

                                // Include a dollar sign in the ticks
                                callback: function (value, index, values) {
                                    @if(setting('currency_right', '0') == '0')
                                        return "{{setting('default_currency')}} "+value;
                                    @else
                                        return value+" {{setting('default_currency')}}";
                                        @endif

                                }
                            }, ticksStyle)
                        }],
                        xAxes: [{
                            display: true,
                            gridLines: {
                                display: false
                            },
                            ticks: ticksStyle
                        }]
                    }
                }
            })
        }

        /*$(function () {
            'use strict'

            var $salesChart = $('#sales-chart')
            $.ajax({
                url: "{!! $ajaxEarningUrl !!}",
                success: function (result) {
                    $("#loadingMessage").html("");
                    var data = result.data[0];
                    var labels = result.data[1];
                    //renderChart($salesChart, data, labels)
                },
                error: function (err) {
                    $("#loadingMessage").html("Error");
                }
            });
            //var salesChart = renderChart($salesChart, data, labels);
        })*/

    </script>

<script type="text/javascript">
    
     
    function cb(start, end) {

        $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
        var start_date      = start.format('YYYY-MM-DD');
        var end_date        = end.format('YYYY-MM-DD');
        
        $.ajax({
            url: "{{url('dashboardDatas')}}",
            method:'POST',
            data: {
                '_token' : $('input[name="_token"]').attr('value'),
                'start_date' : start_date,
                'end_date' : end_date
            },
            success: function (result) {
                if(result.status=='success') {
                    $('.orderCount').html(result.result_data.ordersCount);
                    $('.salesCount').html(result.result_data.sales_count);
                    $('.purchaseCount').html(result.result_data.purchase_count);
                    $('.totalEarning').html((result.result_data.earning).toFixed(2));
                    $('.salesEarning').html((result.result_data.sales_earning).toFixed(2));
                    $('.profitProducts').html(result.result_data.profit_count);
                    $('.partiesCount').html(result.result_data.marketsCount);
                    $('.wastageProducts').html(result.result_data.wastage);    
                    $('.totalOrders').html(result.result_data.totalOrders);
                    $('.totalEarnings').html(result.result_data.totalEarnings); 
                    $('.lastMonthParties').html(result.result_data.lastMonthMarkets);   
                    $('.lastMonthOrders').html(result.result_data.lastMonthOrders);   
                    $('.total-subscribers').html(result.result_data.subscribers); 
                    $('.totalDrivers').html(result.result_data.drivers); 
                    $('.allProducts').html(result.result_data.products);
                    $('.totelSelling').html(result.result_data.totelSelling); 

                     var $salesChart = $('#sales-chart')
                     $.ajax({
                        url: "{!! $ajaxEarningUrl !!}" + "&start_date=" + start_date + "&end_date=" + end_date,
                        success: function (result) {
                            $("#loadingMessage").html("");
                            var data = result.data[0];
                            var labels = result.data[1];
                            var salesdata = result.data[2];
                            renderChart($salesChart, data, labels);
                            
                            var ctx = document.getElementById("myChart").getContext('2d');
                            var myChart = new Chart(ctx, {
                                type: 'line',
                                data: {
                                    labels: labels,
                                    datasets: [{
                                        label: 'Sales Orders', // Name the series
                                        data: salesdata, // Specify the data values array
                                        fill: false,
                                        borderColor: '#2196f3', // Add custom color border (Line)
                                        backgroundColor: '#2196f3', // Add custom color background (Points and Fill)
                                        borderWidth: 1 // Specify bar border width
                                    },
                                    {
                                        label: 'Online Orders', // Name the series
                                        data: data, // Specify the data values array
                                        fill: false,
                                        borderColor: '#4CAF50', // Add custom color border (Line)
                                        backgroundColor: '#4CAF50', // Add custom color background (Points and Fill)
                                        borderWidth: 1 // Specify bar border width
                                    }]
                                },
                                options: {
                                  responsive: true, // Instruct chart js to respond nicely.
                                  maintainAspectRatio: true, // Add to prevent default behaviour of full-width/height 
                                }
                            });
                            
                        },
                        error: function (err) {
                            $("#loadingMessage").html("Error");
                        }
                     });


                     //Orders
                        $.ajax({
                           url: "{!! $ajaxOrdersUrl !!}" + "&start_date=" + start_date + "&end_date=" + end_date,
                           success: function (result) {
                              
                              console.log(result); 
                              var options = {
                                    chart: {
                                       height: 290,
                                       type: "donut"
                                    },
                                    labels: ["Store", "Website", "Apps"],
                                    series: result.data,
                                    legend: {
                                       position: "bottom"
                                    },
                                    dataLabels: {
                                       enabled: !1
                                    },
                                    stroke: {
                                       width: 0
                                    },
                                    colors: ["#1090d6", "#ffaf2b", "#fe7f58"],
                                    tooltip: {
                                       y: {
                                           formatter: function(e) {
                                               return "$" + e
                                           }
                                       }
                                    }
                              },
                              chart = new ApexCharts(document.querySelector("#recentOrders"), options);
                              chart.render();

                           },
                           error: function (err) { }
                        });
                     //Orders


                     //Top Selling
                        $.ajax({
                           url: "{!! $ajaxTopSellingUrl !!}" + "&start_date=" + start_date + "&end_date=" + end_date,
                           success: function (result) {
                              
                                 $("#revenueGraph").html('');
                                 var options = {
                                   chart: {
                                       height: 225,
                                       type: "area",
                                       zoom: {
                                           enabled: !1
                                       },
                                       toolbar: {
                                           show: !1
                                       }
                                   },
                                   dataLabels: {
                                       enabled: !1
                                   },
                                   stroke: {
                                       curve: "smooth",
                                       width: 7
                                   },
                                   series: [{
                                       name: "Orders",
                                       data: result.sellings
                                   }],
                                   grid: {
                                       borderColor: "#69c3f7",
                                       strokeDashArray: 5,
                                       xaxis: {
                                           lines: {
                                               show: !0
                                           }
                                       },
                                       yaxis: {
                                           lines: {
                                               show: !1
                                           }
                                       },
                                       padding: {
                                           top: 0,
                                           right: 20,
                                           bottom: -10,
                                           left: 20
                                       }
                                   },
                                   xaxis: {
                                       categories: result.products
                                   },
                                   yaxis: {
                                       show: !1
                                   },
                                   fill: {
                                       type: "gradient",
                                       gradient: {
                                           type: "vertical",
                                           shadeIntensity: 1,
                                           inverseColors: !1,
                                           opacityFrom: .4,
                                           opacityTo: .2,
                                           stops: [15, 100]
                                       }
                                   },
                                   colors: ["#9ddcff"],
                                   markers: {
                                       size: 0,
                                       opacity: .2,
                                       colors: ["#9ddcff"],
                                       strokeColor: "#ffffff",
                                       strokeWidth: 2,
                                       hover: {
                                           size: 7
                                       }
                                   },
                                   tooltip: {
                                       y: {
                                           formatter: function(e) {
                                               return e
                                           }
                                       }
                                   }
                               },
                               chart = new ApexCharts(document.querySelector("#revenueGraph"), options);
                               chart.render();

                           },
                           error: function (err) { }
                        });
                     //Top Selling



                }
            },
            error: function (err) {
                /*$("#loadingMessage").html("Error");*/
            }
        });

        //location.href='?start_date='+start_date+'&end_date='+end_date;
    }
    function cb1() {}
    function cb2() {}
    
    /*@if(Request::get('start_date') && Request::get('end_date'))
        $('#reportrange span').html("{{date('F d, Y',strtotime(Request::get('start_date')))}} - {{date('F d, Y',strtotime(Request::get('end_date')))}}");
    @endif*/

</script>

<script src="https://bootstrap.gallery/cliq/vendor/apex/apexcharts.min.js"></script>    
<script src="https://bootstrap.gallery/cliq/vendor/circliful/circliful.min.js"></script>    
<script>

    var options={chart:{height:110,width:"85%",type:"area",zoom:{enabled:!1},toolbar:{show:!1}},dataLabels:{enabled:!1},stroke:{curve:"smooth",width:7},series:[{name:"Notifications",data:[80,300,300,50,150,170,550,500]}],grid:{borderColor:"#bede68",strokeDashArray:0,show:!1,xaxis:{lines:{show:!1}},yaxis:{lines:{show:!1}},padding:{top:0,right:10,bottom:0,left:10}},xaxis:{labels:{show:!1}},yaxis:{show:!1},fill:{type:"gradient",gradient:{type:"vertical",shadeIntensity:1,inverseColors:!1,opacityFrom:.4,opacityTo:0,stops:[15,100]}},colors:["#ffffff"],markers:{size:0,opacity:.2,colors:["#ffffff"],strokeColor:"#49914E",strokeWidth:2,hover:{size:10}},tooltip:{y:{formatter:function(o){return o}}}},chart=new ApexCharts(document.querySelector("#notificationsGraph"),options);chart.render();



    var options={chart:{height:325,width:"85%",type:"line",stacked:!1,toolbar:{show:!1}},dataLabels:{enabled:!1},colors:["#ffa77e","#ffffff"],series:[{name:"Target",type:"column",data:[30,50,70,90]},{name:"Achieived",type:"line",data:[25,35,65,55]}],stroke:{width:[0,7]},plotOptions:{bar:{columnWidth:"60%",borderRadius:7}},xaxis:{categories:["Q1","Q2","Q3","Q4"]},yaxis:[{show:!1}],tooltip:{shared:!1,intersect:!0,x:{show:!1}},legend:{horizontalAlign:"center"},grid:{borderColor:"#fea67f",strokeDashArray:0,xaxis:{lines:{show:!1}},yaxis:{lines:{show:!1}},padding:{top:-30,right:0,bottom:0,left:10}}},chart=new ApexCharts(document.querySelector("#qtrTargetGraph"),options);chart.render();
   

    var options={chart:{height:110,width:"55%",type:"area",zoom:{enabled:!1},toolbar:{show:!1}},dataLabels:{enabled:!1},stroke:{curve:"smooth",width:7},series:[{name:"Active Users",data:[80,300,90,150]}],grid:{borderColor:"#bede68",strokeDashArray:0,show:!1,xaxis:{lines:{show:!1}},yaxis:{lines:{show:!1}},padding:{top:0,right:0,bottom:0,left:0}},xaxis:{labels:{show:!1}},yaxis:{show:!1},fill:{type:"gradient",gradient:{type:"vertical",shadeIntensity:1,inverseColors:!1,opacityFrom:.4,opacityTo:0,stops:[15,100]}},colors:["#ffffff"],markers:{size:0,opacity:.2,colors:["#ffffff"],strokeColor:"#49914E",strokeWidth:2,hover:{size:10}},tooltip:{y:{formatter:function(e){return e}}}},chart=new ApexCharts(document.querySelector("#signupsGraph"),options);chart.render();

    var options = {
        chart: {
            height: 270,
            width: "90%",
            type: "bar",
            stacked: !0,
            toolbar: {
                show: !1
            },
            zoom: {
                enabled: !0
            }
        },
        plotOptions: {
            bar: {
                horizontal: !1,
                borderRadius: 7
            }
        },
        dataLabels: {
            enabled: !0
        },
        series: [{
            name: "Sales",
            data: [0, 0, 0, 0, 0, 0]
        }, {
            name: "Revenue",
            data: [0, 0, 0, 0, 0, 0]
        }],
        xaxis: {
            categories: ["Mon", "Tue", "Wed", "Thu", "Fri", "Sat", "Sun"]
        },
        legend: {
            show: !1
        },
        grid: {
            borderColor: "#43c3d0",
            strokeDashArray: 5,
            xaxis: {
                lines: {
                    show: !0
                }
            },
            yaxis: {
                lines: {
                    show: !1
                }
            },
            padding: {
                top: 20,
                right: 0,
                bottom: 0,
                left: 20
            }
        },
        yaxis: {
            show: !1
        },
        fill: {
            opacity: 1
        },
        tooltip: {
            y: {
                formatter: function(e) {
                    return "$" + e
                }
            }
        },
        colors: ["#50bfca", "#FFFFFF"]
    },
    chart = new ApexCharts(document.querySelector("#salesGraph"), options);
    chart.render();


    $(document).ready((function() {
       $("#carLocan").circliful({
           animation: 1,
           animationStep: 5,
           foregroundBorderWidth: 15,
           backgroundBorderWidth: 15,
           percent: 70,
           textStyle: "font-size: 12px;",
           fontColor: "#ffffff",
           foregroundColor: "#ffffff",
           backgroundColor: "rgba(255, 255, 255, 0.1)"
       }), $("#weeklyEarnings").circliful({
           animation: 1,
           animationStep: 5,
           foregroundBorderWidth: 15,
           backgroundBorderWidth: 15,
           percent: 0,
           textStyle: "font-size: 12px;",
           fontColor: "#ffffff",
           foregroundColor: "#ffffff",
           backgroundColor: "rgba(255, 255, 255, 0.1)",
           multiPercentage: 1,
           percentages: [10, 20, 30]
       }), $("#monthlyEarnings").circliful({
           animation: 1,
           animationStep: 5,
           foregroundBorderWidth: 15,
           backgroundBorderWidth: 15,
           percent: 0,
           fontColor: "#ffffff",
           foregroundColor: "#ffffff",
           backgroundColor: "rgba(255, 255, 255, 0.1)",
           multiPercentage: 1,
           percentages: [10, 20, 30]
       }), $("#withIcon").circliful({
           animationStep: 5,
           foregroundBorderWidth: 12,
           backgroundBorderWidth: 7,
           percent: 3,
           fontColor: "#000000",
           foregroundColor: "#8796af",
           backgroundColor: "rgba(0, 0, 0, 0.1)",
           icon: "ea71",
           iconColor: "#8796af",
           iconPosition: "middle",
           textBelow: !0,
           animation: 1,
           animationStep: 1,
           start: 2,
           showPercent: 1
       })
   }));

</script>

@endpush