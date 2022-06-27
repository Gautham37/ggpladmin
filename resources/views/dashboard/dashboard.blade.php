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
            font-family: 'Lato', sans-serif;
            line-height: 150%;
            color: #ffffff;
        }
        .card {
            border-radius: 8px;
            font-family: 'Lato', sans-serif;
        }
        .parties-list td {
            padding: 3px 7px !important;
            font-size: 12px;
        }
        .product-list td {
            padding: 3px 7px !important;
            font-size: 15px;
        }
        .product-list {
            margin-top: 40px;
        }
    </style>

    <!-- Content Header (Page header) -->
    <section class="content-header content-header{{setting('fixed_header')}}">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>
                        Dashboard
                    </h1>
                    <div id="reportrange"  class="pull-right" style="background: #fff; cursor: pointer; padding: 5px 10px; margin-top: -25px; border: 1px solid #ccc;">
                        <i class="glyphicon glyphicon-calendar fa fa-calendar"></i> &nbsp; <span></span> 
                    </div>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                        <li class="breadcrumb-item active">Dashboard</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <div class="content">
        <!-- Small boxes (Stat box) -->
        <div class="row">
            
            <div class="col-lg-12">

                <div class="row gutters">
                 <div class="col-lg-3 col-md-3 col-sm-3 col-12">
                    
                    <div class="card gradient-teal-brown card-390">
                        <div class="gradient-vertical-strip-grey"></div>
                        <button type="button" class="download-icon download-reports">
                            <img src="{{asset('images/download-blue.svg')}}" alt="Download">
                        </button>
                        <div class="card-body">
                            <div class="card-title">Statistics</div>
                            <div id="recentOrders" class="mt-4"></div>
                        </div>
                    </div>

                    <div class="card gradient-orange2 card-210">
                       <div class="gradient-vertical-strip"></div>
                       <button type="button" class="download-icon download-reports">
                           <img src="{{asset('images/download-blue.svg')}}" alt="Download">
                       </button>
                       <div class="card-body">
                          <div class="card-title">Products</div>
                          <div class="subscribers mb-0">
                            <table class="product-list text-white">
                                <tr>
                                    <td><span>Total Products</span></td>
                                    <td><span class="total-products">0</span></td>
                                </tr>
                                <tr>
                                    <td><span>Deliverable products</span></td>
                                    <td><span class="total-deliverable-products">0</span></td>
                                </tr>
                                <tr>
                                    <td><span>Weight Loss Products</span></td>
                                    <td><span class="total-weight-loss-products">0</span></td>
                                </tr>
                                <tr>
                                    <td><span>Low Profit Products</span></td>
                                    <td><span class="total-low-profit-products">0</span></td>
                                </tr>
                            </table>
                            <!-- <div class="total-subscribers m-0">695</div> -->
                          </div>
                       </div>
                    </div>
                 </div>
                 <div class="col-lg-3 col-md-3 col-sm-3 col-12">
                    <div class="card gradient-violet-pink card-150">
                       <div class="card-body">
                          <div class="sales-tile3">
                             <div class="sales-tile3-block">
                                <div class="sales-tile3-icon peach">
                                    <i class="fa fa-users"></i>
                                </div>
                                <div class="sales-tile3-details">
                                    <table class="parties-list">
                                        <tr>
                                            <td><h5>Parties</h5></td>
                                            <td><h5><b class="total-parties">0</b></h6></td>
                                        </tr>
                                        <tr>
                                            <td><span>Back Office</span></td>
                                            <td class="total-admin-portal-parites">0</td>
                                        </tr>
                                        <tr>
                                            <td><span>Website</span></td>
                                            <td class="total-website-parites">0</td>
                                        </tr>
                                        <tr>
                                            <td><span>Mobile Apps</span></td>
                                            <td class="total-mobile-app-parties">0</td>
                                        </tr>
                                    </table>
                                </div>
                             </div>
                          </div>
                       </div>
                    </div>

                    <!-- <div class="card gradient-blue2 card-450">
                       <div class="gradient-vertical-strip"></div>
                       <button type="button" class="download-icon download-reports"><img src="img/download-blue.svg" alt="Download"></button>
                       <div class="card-body">
                          <div class="card-title">Daily<br />Orders</div>
                          <div id="revenueGraph"></div>
                          <div class="sales-tile4">
                             <h2>21500</h2>
                             <span>+5.9% Higher orders last month</span>
                          </div>
                       </div>
                    </div> -->

                    <div class="card gradient-teal card-450">
                        <div class="gradient-vertical-strip"></div>
                        <button type="button" class="download-icon download-reports">
                            <img src="{{asset('images/download-blue.svg')}}" alt="Download">
                        </button>
                        <div class="card-body">
                            <div class="card-title">Weekly<br />Sales</div>
                            <div id="salesGraph"></div>
                        </div>
                    </div>

                 </div>
                 <div class="col-lg-3 col-md-3 col-sm-3 col-12">
                    <div class="card gradient-green card-150">
                       <div class="card-body">
                          <div class="sales-tile3">
                             <div class="sales-tile3-block">
                                <div class="sales-tile3-icon green">
                                    <i class="fa fa-shopping-bag"></i>
                                </div>
                                <div class="sales-tile3-details">
                                   <table class="parties-list">
                                        <tr>
                                            <td><h5>Online Orders</h5></td>
                                            <td><h5><b class="total-orders">0</b></h6></td>
                                        </tr>
                                        <tr>
                                            <td><span>Website</span></td>
                                            <td class="total-website-orders">0</td>
                                        </tr>
                                        <tr>
                                            <td><span>Mobile Apps</span></td>
                                            <td class="total-mobile-app-orders">0</td>
                                        </tr>
                                    </table>
                                </div>
                             </div>
                          </div>
                       </div>
                    </div>

                    <div class="card gradient-orange2 card-450">
                        <div class="gradient-vertical-strip"></div>
                        <button type="button" class="download-icon download-reports">
                            <img src="{{asset('images/download-blue.svg')}}" alt="Download">
                        </button>
                        <div class="card-body">
                            <div class="card-title">Weekly<br />Purchase</div>
                            <div id="purchaseGraph"></div>
                        </div>
                    </div>

                 </div>
                 <div class="col-lg-3 col-md-3 col-sm-3 col-12">
                    <div class="card gradient-green-orange globe-bg card-390">

                       <div class="gradient-vertical-strip-grey"></div>
                        <button type="button" class="download-icon download-reports">
                            <img src="{{asset('images/download-blue.svg')}}" alt="Download">
                        </button>
                        <div class="card-body">
                            <div class="card-title">Staff Attendance</div>
                            <!-- <div id="staffsReport" class="mt-4"></div> -->
                            <div id="staffAttendanceGraph"></div>
                        </div>

                    </div>
                    <div class="card gradient-teal-brown card-200">
                       <div class="card-body">
                          <div class="row gutters">
                             <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                <div class="earnings">
                                   <div id="outofStockGraph"></div>
                                   <p>Out Of Stock</p>
                                   <h5 class="out-of-stock-items">0 Items</h5>
                                </div>
                             </div>
                             <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                <div class="earnings">
                                   <div id="wastageGraph"></div>
                                   <p>Wastage</p>
                                   <h5 class="wastage-items">0 Items</h5>
                                </div>
                             </div>
                          </div>
                       </div>
                    </div>
                 </div>
              </div>
              <div class="row gutters">
                 <!-- <div class="col-lg-3 col-md-3 col-sm-3 col-12">
                    <div class="card gradient-green card-170">
                       <div class="card-body">
                          <div class="uploading-container">
                             <a href="#" class="pause" id="play-pause"><i class="icon-pause_circle_outline"></i></a>
                             <div class="upload-progress-container">
                                <div class="upload-icon"><img src="img/upload.svg" alt="Download"></div>
                                <div class="upload-progress">
                                   <div class="upload-space-container">
                                      <div>45<span>%</span><span class="ms-2">Uploading...</span></div>
                                      <div>100<span>GB</span></div>
                                   </div>
                                   <div class="progress">
                                      <div class="progress-bar" role="progressbar" style="width: 45%" aria-valuenow="45" aria-valuemin="0" aria-valuemax="100"></div>
                                   </div>
                                </div>
                             </div>
                          </div>
                       </div>
                    </div>
                 </div> -->
                 <div class="col-lg-3 col-md-3 col-sm-3 col-12">
                    <div class="card gradient-teal-cream card-170">
                       <div class="card-body">
                          <div class="notifications-container">
                             <div class="notify-details-block">
                                <div class="notify-icon">
                                    <i class="fa fa-bell"></i>
                                </div>
                                <div class="notify-details">
                                   <h5>Notifications</h5>
                                   <h3 class="total-notifications">0</h3>
                                   <div class="notify-high-low">+7.8%</div>
                                </div>
                             </div>
                             <div id="notificationsGraph" class="apex-hide-lines"></div>
                          </div>
                       </div>
                    </div>
                 </div>

                 <div class="col-lg-3 col-md-3 col-sm-3 col-12">
                    <div class="card gradient-green card-170">
                       <div class="card-body">
                          <div class="active-users-container">
                             <div class="active-users-details">
                                <div class="details">
                                   <h6>Complaints</h6>
                                   <h3 class="total-complaints">0</h3>
                                   <span class="active-users-high-low">+21.5%</span>
                                </div>
                                <div id="complaintsGraph" class="apex-hide-lines"></div>
                                <div class="active-users-icon"><i class="fa fa-comments"></i></div>
                             </div>
                          </div>
                       </div>
                    </div>
                 </div>

                 <div class="col-lg-3 col-md-3 col-sm-3 col-12">
                    <div class="card gradient-yellow card-170">
                       <div class="card-body">
                          <div class="active-users-container">
                             <div class="active-users-details">
                                <div class="details">
                                   <h6>Rewards</h6>
                                   <h3 class="total-rewards">0</h3>
                                   <span class="active-users-high-low">+21.5%</span>
                                </div>
                                <div id="signupsGraph" class="apex-hide-lines"></div>
                                <div class="active-users-icon"><i class="fa fa-trophy"></i></div>
                             </div>
                          </div>
                       </div>
                    </div>
                 </div>

                 <div class="col-lg-3 col-md-3 col-sm-3 col-12">
                    <div class="card gradient-peach3 card-170">
                       <div class="card-body">
                          <div class="active-users-container">
                             <div class="active-users-details">
                                <div class="details">
                                   <h6>Driver Reviews</h6>
                                   <h3 class="total-driver-review">0</h3>
                                   <span class="active-users-high-low">+21.5%</span>
                                </div>
                                <div id="driverreviewGraph" class="apex-hide-lines"></div>
                                <div class="active-users-icon"><i class="fa fa-truck"></i></div>
                             </div>
                          </div>
                       </div>
                    </div>
                 </div>
                 
                 
                 
                   <div class="col-xxl-12 col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                      <div class="card gradient-violet-pink card-140">
                         <div class="card-body">
                            <div class="row gutters">
                                
                               <div class="col-lg-3 col-md-3 col-sm-3 col-12">
                                  <a href="{{route('coupons.index')}}">
                                  <div class="sales-tile2">
                                     <div class="sales-tile2-block">
                                        <div class="sales-tile2-icon">
                                            <div class="active-users-icon"><i style="color:#3b6fd1; font-size:25px;" class="fa fa-gift"></i></div>
                                        </div>
                                        <div class="sales-tile2-details">
                                           <h4 class="total-discount-coupons">0</h4>
                                           <h6>Discout Coupons</h6>
                                        </div>
                                     </div>
                                  </div>
                                  </a>
                               </div>
                               
                               <div class="col-lg-3 col-md-3 col-sm-3 col-12">
                                  <a href="{{route('charity.index')}}"> 
                                  <div class="sales-tile2">
                                     <div class="sales-tile2-block">
                                        <div class="sales-tile2-icon">
                                            <div class="active-users-icon"><i style="color:#6c71c2; font-size:25px;" class="fa fa-handshake-o"></i></div>
                                        </div>
                                        <div class="sales-tile2-details">
                                           <h4 class="total-charity">0</h4>
                                           <h6>Charity Organizations</h6>
                                        </div>
                                     </div>
                                  </div>
                                  </a>
                               </div>
                               
                               <div class="col-lg-3 col-md-3 col-sm-3 col-12">
                                  <a href="{{route('deliveryZones.index')}}"> 
                                  <div class="sales-tile2">
                                     <div class="sales-tile2-block">
                                        <div class="sales-tile2-icon">
                                            <div class="active-users-icon"><i style="color:#9e73b4; font-size:25px;" class="fa fa-map-marker"></i></div>
                                        </div>
                                        <div class="sales-tile2-details">
                                           <h4 class="total-delivery-zones">0</h4>
                                           <h6>Delivery Zones</h6>
                                        </div>
                                     </div>
                                  </div>
                                  </a>
                               </div>
                               
                               <div class="col-lg-3 col-md-3 col-sm-3 col-12">
                                  <a href="{{route('emailnotifications.index')}}"> 
                                  <div class="sales-tile2">
                                     <div class="sales-tile2-block">
                                        <div class="sales-tile2-icon">
                                            <div class="active-users-icon"><i style="color:#cd75a6; font-size:25px;" class="fa fa-envelope"></i></div>
                                        </div>
                                        <div class="sales-tile2-details">
                                           <h4 class="total-email-alerts">0</h4>
                                           <h6>Email Alerts</h6>
                                        </div>
                                     </div>
                                  </div>
                                  </a>
                               </div>
                               
                            </div>
                         </div>
                      </div>
                   </div>

              </div>

            </div>

        </div>
        
        <div class="row">
            
            <div class="col-lg-6 dashboard-card-column">

                <!--Sales Orders-->
                <div class="card dashboard-card">
                    <div class="card-header no-border">
                        <h3 class="card-title">Customer Orders</h3><br>
                        <div class="card-tools">
                            <a href="#" class="btn btn-tool btn-sm"><i class="fa fa-bars"></i> </a>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <table class="table table-striped table-valign-middle">
                            <thead>
                            <tr>
                               
                                <th>SO Source</th>
                                <th>Yesterday</th>
                                <th>Today</th>
                                <!-- <th>Tommorrow</th>
                                <th>Future</th> -->
                            </tr>
                            </thead>
                            <tbody>
                            @php
                                $sales_order_received_from_yest = DB::table('sales_invoice')
                                                                ->leftJoin('sales_invoice_items', 'sales_invoice.id','=','sales_invoice_items.sales_invoice_id')
                                                                ->select('sales_invoice.*', DB::raw('SUM(sales_invoice_items.quantity) as total_quantity'), DB::raw('SUM(sales_invoice_items.amount) as total_amount'))
                                                                ->where('sales_invoice.date', '=', date('Y-m-d',strtotime("-1 days")))
                                                                ->get();
                                $sales_order_received_from_today = DB::table('sales_invoice')
                                                                ->leftJoin('sales_invoice_items', 'sales_invoice.id','=','sales_invoice_items.sales_invoice_id')
                                                                ->select('sales_invoice.*', DB::raw('SUM(sales_invoice_items.quantity) as total_quantity'), DB::raw('SUM(sales_invoice_items.amount) as total_amount'))
                                                                ->where('sales_invoice.date', '=', date('Y-m-d'))
                                                                ->get();
                            @endphp
                            <tr>
                                <td>Website</td>
                                <td>{{number_format($sales_order_received_from_yest[0]->total_quantity,3)}} KGS <br> {{setting('default_currency').''.number_format($sales_order_received_from_yest[0]->total_amount,2)}}</td>
                                <td>{{number_format($sales_order_received_from_today[0]->total_quantity,3)}} KGS <br> {{setting('default_currency').''.number_format($sales_order_received_from_today[0]->total_amount,2)}}</td>
                                <!-- <td></td> -->
                            </tr>
                            <tr>
                                <td>Android</td>
                                <td></td>
                                <td></td>
                                <!-- <td></td> -->
                            </tr>
                            <tr>
                                <td>Apple</td>
                                <td></td>
                                <td></td>
                                <!-- <td></td> -->
                            </tr>
                            <tr>
                                <td>Phone</td>
                                <td></td>
                                <td></td>
                                <!-- <td></td> -->
                            </tr>
                            <tr>
                                <td>Total</td>
                                <td>{{number_format($sales_order_received_from_yest[0]->total_quantity,3)}} KGS <br> {{setting('default_currency').''.number_format($sales_order_received_from_yest[0]->total_amount,2)}}</td>
                                <td>{{number_format($sales_order_received_from_today[0]->total_quantity,3)}} KGS <br> {{setting('default_currency').''.number_format($sales_order_received_from_today[0]->total_amount,2)}}</td>
                                <!-- <td></td> -->
                            </tr>
                            
                            </tbody>
                        </table>
                    </div>
                </div>

                <!--Sales Order Status-->
                <div class="card dashboard-card">
                    <div class="card-header no-border">
                        <h3 class="card-title">Sales Order Status</h3><br>
                        <div class="card-tools">
                            <a href="#" class="btn btn-tool btn-sm"><i class="fa fa-bars"></i> </a>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <table class="table table-striped table-valign-middle">
                            <thead>
                            <tr>
                               
                                <th>SO Status</th>
                                <th>Yesterday</th>
                                <th>Today</th>
                                <!-- <th>Tommorrow</th>
                                <th>Future</th> -->
                            </tr>
                            </thead>
                            <tbody>
                                @php
                                    $sales_order_status_yest = DB::table('sales_invoice')
                                                            ->leftJoin('sales_invoice_items', 'sales_invoice.id','=','sales_invoice_items.sales_invoice_id')
                                                            ->select('sales_invoice.*', DB::raw('SUM(sales_invoice_items.quantity) as total_quantity'), DB::raw('SUM(sales_invoice_items.amount) as total_amount'))
                                                            ->where('sales_invoice.date', '=', date('Y-m-d',strtotime("-1 days")))
                                                            ->get();
                                    $sales_order_status_today = DB::table('sales_invoice')
                                                            ->leftJoin('sales_invoice_items', 'sales_invoice.id','=','sales_invoice_items.sales_invoice_id')
                                                            ->select('sales_invoice.*', DB::raw('SUM(sales_invoice_items.quantity) as total_quantity'), DB::raw('SUM(sales_invoice_items.amount) as total_amount'))
                                                            ->where('sales_invoice.date', '=', date('Y-m-d'))
                                                            ->get();
                                @endphp
                                <tr>
                                    <td>Received</td>
                                    <td>{{number_format($sales_order_status_yest[0]->total_quantity,3)}} KGS <br> {{setting('default_currency').''.number_format($sales_order_status_yest[0]->total_amount,2)}}</td>
                                    <td>{{number_format($sales_order_status_today[0]->total_quantity,3)}} KGS <br> {{setting('default_currency').''.number_format($sales_order_status_today[0]->total_amount,2)}}</td>
                                    <!-- <td></td> -->
                                </tr>
                                <tr>
                                    <td>Pending Processing</td>
                                    <td></td>
                                    <td></td>
                                    <!-- <td></td> -->
                                </tr>
                                <tr>
                                    <td>Pending QA Check</td>
                                    <td></td>
                                    <td></td>
                                    <!-- <td></td> -->
                                </tr>
                                <tr>
                                    <td>Pending Packaging</td>
                                    <td></td>
                                    <td></td>
                                    <!-- <td></td> -->
                                </tr>
                                <tr>
                                    <td>Pending Billing</td>
                                    <td></td>
                                    <td></td>
                                    <!-- <td></td> -->
                                </tr>
                                <tr>
                                    <td>Ready to Pickup</td>
                                    <td></td>
                                    <td></td>
                                    <!-- <td></td> -->
                                </tr>
                                <tr>
                                    <td>Ready for Delivery</td>
                                    <td></td>
                                    <td></td>
                                    <!-- <td></td> -->
                                </tr>
                                <tr>
                                    <td>Total</td>
                                    <td>{{number_format($sales_order_status_yest[0]->total_quantity,3)}} KGS <br> {{setting('default_currency').''.number_format($sales_order_status_yest[0]->total_amount,2)}}</td>
                                    <td>{{number_format($sales_order_status_today[0]->total_quantity,3)}} KGS <br> {{setting('default_currency').''.number_format($sales_order_status_today[0]->total_amount,2)}}</td>
                                    <!-- <td></td> -->
                                </tr>
                            
                            </tbody>
                        </table>
                    </div>
                </div>

                <!--Product Categories Orders-->
                <div class="card dashboard-card">
                    <div class="card-header no-border">
                        <h3 class="card-title">Sales Vs Purchase Category</h3><br>
                        <div class="card-tools">
                            <a href="#" class="btn btn-tool btn-sm"><i class="fa fa-bars"></i> </a>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <table class="table table-striped table-valign-middle">
                            <thead>
                            <tr>
                               
                                <th>SO | PO Category</th>
                                <th>Yesterday</th>
                                <th>Today</th>
                                <!-- <th>Tommorrow</th>
                                <th>Future</th> -->
                            </tr>
                            </thead>
                            <tbody>
                                @php 
                                    $total_quantity_pur_cat_yest = 0;
                                    $total_quantity_sal_cat_yest = 0;
                                    $total_amount_pur_cat_yest = 0;
                                    $total_amount_sal_cat_yest = 0;
                                    $total_quantity_pur_cat_today = 0;
                                    $total_quantity_sal_cat_today = 0;
                                    $total_amount_pur_cat_today = 0;
                                    $total_amount_sal_cat_today = 0;
                                @endphp
                                @foreach ($categories as $category)
                                @php
                                    $purchase_invoice_cat_yest = DB::table('purchase_invoice')
                                                                    ->leftJoin('purchase_invoice_items', 'purchase_invoice.id','=','purchase_invoice_items.purchase_invoice_id')
                                                                    ->leftJoin('products', 'purchase_invoice_items.product_id','=','products.id')
                                                                    ->leftJoin('categories', 'products.category_id','=','categories.id')
                                                                    ->select('purchase_invoice_items.*', DB::raw('SUM(purchase_invoice_items.quantity) as total_quantity'), DB::raw('SUM(purchase_invoice_items.amount) as total_amount'))
                                                                    ->where('categories.id','=',$category->id)
                                                                    ->where('purchase_invoice.date', '=', date('Y-m-d',strtotime("-1 days")))
                                                                    ->get();
                                    $sales_invoice_cat_yest = DB::table('sales_invoice')
                                                                    ->leftJoin('sales_invoice_items', 'sales_invoice.id','=','sales_invoice_items.sales_invoice_id')
                                                                    ->leftJoin('products', 'sales_invoice_items.product_id','=','products.id')
                                                                    ->leftJoin('categories', 'products.category_id','=','categories.id')
                                                                    ->select('sales_invoice_items.*', DB::raw('SUM(sales_invoice_items.quantity) as total_quantity'), DB::raw('SUM(sales_invoice_items.amount) as total_amount'))
                                                                    ->where('categories.id','=',$category->id)
                                                                    ->where('sales_invoice.date', '=', date('Y-m-d',strtotime("-1 days")))
                                                                    ->get();
                                    $purchase_invoice_cat_today = DB::table('purchase_invoice')
                                                                    ->leftJoin('purchase_invoice_items', 'purchase_invoice.id','=','purchase_invoice_items.purchase_invoice_id')
                                                                    ->leftJoin('products', 'purchase_invoice_items.product_id','=','products.id')
                                                                    ->leftJoin('categories', 'products.category_id','=','categories.id')
                                                                    ->select('purchase_invoice_items.*', DB::raw('SUM(purchase_invoice_items.quantity) as total_quantity'), DB::raw('SUM(purchase_invoice_items.amount) as total_amount'))
                                                                    ->where('categories.id','=',$category->id)
                                                                    ->where('purchase_invoice.date', '=', date('Y-m-d'))
                                                                    ->get();
                                    $sales_invoice_cat_today = DB::table('sales_invoice')
                                                                    ->leftJoin('sales_invoice_items', 'sales_invoice.id','=','sales_invoice_items.sales_invoice_id')
                                                                    ->leftJoin('products', 'sales_invoice_items.product_id','=','products.id')
                                                                    ->leftJoin('categories', 'products.category_id','=','categories.id')
                                                                    ->select('sales_invoice_items.*', DB::raw('SUM(sales_invoice_items.quantity) as total_quantity'), DB::raw('SUM(sales_invoice_items.amount) as total_amount'))
                                                                    ->where('categories.id','=',$category->id)
                                                                    ->where('sales_invoice.date', '=', date('Y-m-d'))
                                                                    ->get();
                                    $total_quantity_pur_cat_yest += $purchase_invoice_cat_yest[0]->total_quantity;
                                    $total_quantity_sal_cat_yest += $sales_invoice_cat_yest[0]->total_quantity;
                                    $total_amount_pur_cat_yest += $purchase_invoice_cat_yest[0]->total_amount;
                                    $total_amount_sal_cat_yest += $sales_invoice_cat_yest[0]->total_amount;
                                    $total_quantity_pur_cat_today += $purchase_invoice_cat_today[0]->total_quantity;
                                    $total_quantity_sal_cat_today += $sales_invoice_cat_today[0]->total_quantity;
                                    $total_amount_pur_cat_today += $purchase_invoice_cat_today[0]->total_amount;
                                    $total_amount_sal_cat_today += $sales_invoice_cat_today[0]->total_amount;
                                @endphp
                                <tr>
                                    <td>{{$category->name}}</td>
                                    <td>{{number_format($purchase_invoice_cat_yest[0]->total_quantity,3)}} KGS  {{setting('default_currency').''.number_format($purchase_invoice_cat_yest[0]->total_amount,2)}} <b>(PO)</b> <br> {{number_format($sales_invoice_cat_yest[0]->total_quantity,3)}} KGS  {{setting('default_currency').''.number_format($sales_invoice_cat_yest[0]->total_amount,2)}} <b>(SO)</b></td>
                                    <td>{{number_format($purchase_invoice_cat_today[0]->total_quantity,3)}} KGS  {{setting('default_currency').''.number_format($purchase_invoice_cat_today[0]->total_amount,2)}} <b>(PO)</b> <br> {{number_format($sales_invoice_cat_today[0]->total_quantity,3)}} KGS  {{setting('default_currency').''.number_format($sales_invoice_cat_today[0]->total_amount,2)}} <b>(SO)</b></td>
                                    <!-- <td></td> -->
                                </tr>
                                @endforeach
                                <tr>
                                    <td>Total</td>
                                    <td>{{number_format($total_quantity_pur_cat_yest,3)}} KGS  {{setting('default_currency').''.number_format($total_amount_pur_cat_yest,2)}} <b>(PO)</b> <br> {{number_format($total_quantity_sal_cat_yest,3)}} KGS   {{setting('default_currency').''.number_format($total_amount_sal_cat_yest,2)}} <b>(SO)</b> </td>
                                    <td>{{number_format($total_quantity_pur_cat_today,3)}} KGS  {{setting('default_currency').''.number_format($total_amount_pur_cat_today,2)}} <b>(PO)</b> <br> {{number_format($total_quantity_sal_cat_today,3)}} KGS   {{setting('default_currency').''.number_format($total_amount_sal_cat_today,2)}} <b>(SO)</b></td>
                                    <!-- <td></td> -->
                                </tr>
                            
                            </tbody>
                        </table>
                    </div>
                </div>
                
                <!--Product Subcategories Orders-->
                <div class="card dashboard-card">
                    <div class="card-header no-border">
                        <h3 class="card-title">Sales Vs Purchase Subcategory</h3><br>
                        <div class="card-tools">
                            <a href="#" class="btn btn-tool btn-sm"><i class="fa fa-bars"></i> </a>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <table class="table table-striped table-valign-middle">
                            <thead>
                            <tr>
                               
                                <th>SO | PO Subcategory</th>
                                <th>Yesterday</th>
                                <th>Today</th>
                                <!-- <th>Tommorrow</th>
                                <th>Future</th> -->
                            </tr>
                            </thead>
                            <tbody>
                                @php 
                                    $total_quantity_pur_subcat_yest = 0;
                                    $total_quantity_sal_subcat_yest = 0;
                                    $total_amount_pur_subcat_yest = 0;
                                    $total_amount_sal_subcat_yest = 0;
                                    $total_quantity_pur_subcat_today = 0;
                                    $total_quantity_sal_subcat_today = 0;
                                    $total_amount_pur_subcat_today = 0;
                                    $total_amount_sal_subcat_today = 0;
                                @endphp
                                @foreach ($subcategories as $subcategory)
                                @php
                                    $purchase_invoice_subcat_yest = DB::table('purchase_invoice')
                                                            ->leftJoin('purchase_invoice_items', 'purchase_invoice.id','=','purchase_invoice_items.purchase_invoice_id')
                                                            ->leftJoin('products', 'purchase_invoice_items.product_id','=','products.id')
                                                            ->leftJoin('subcategories', 'products.subcategory_id','=','subcategories.id')
                                                            ->select('purchase_invoice_items.*', DB::raw('SUM(purchase_invoice_items.quantity) as total_quantity'), DB::raw('SUM(purchase_invoice_items.amount) as total_amount'))
                                                            ->where('subcategories.id','=',$subcategory->id)
                                                            ->where('purchase_invoice.date', '=', date('Y-m-d',strtotime("-1 days")))
                                                            ->get();
                                    $sales_invoice_subcat_yest = DB::table('sales_invoice')
                                                            ->leftJoin('sales_invoice_items', 'sales_invoice.id','=','sales_invoice_items.sales_invoice_id')
                                                            ->leftJoin('products', 'sales_invoice_items.product_id','=','products.id')
                                                            ->leftJoin('subcategories', 'products.subcategory_id','=','subcategories.id')
                                                            ->select('sales_invoice_items.*', DB::raw('SUM(sales_invoice_items.quantity) as total_quantity'), DB::raw('SUM(sales_invoice_items.amount) as total_amount'))
                                                            ->where('subcategories.id','=',$subcategory->id)
                                                            ->where('sales_invoice.date', '=', date('Y-m-d',strtotime("-1 days")))
                                                            ->get();
                                    $purchase_invoice_subcat_today = DB::table('purchase_invoice')
                                                            ->leftJoin('purchase_invoice_items', 'purchase_invoice.id','=','purchase_invoice_items.purchase_invoice_id')
                                                            ->leftJoin('products', 'purchase_invoice_items.product_id','=','products.id')
                                                            ->leftJoin('subcategories', 'products.subcategory_id','=','subcategories.id')
                                                            ->select('purchase_invoice_items.*', DB::raw('SUM(purchase_invoice_items.quantity) as total_quantity'), DB::raw('SUM(purchase_invoice_items.amount) as total_amount'))
                                                            ->where('subcategories.id','=',$subcategory->id)
                                                            ->where('purchase_invoice.date', '=', date('Y-m-d'))
                                                            ->get();
                                    $sales_invoice_subcat_today = DB::table('sales_invoice')
                                                            ->leftJoin('sales_invoice_items', 'sales_invoice.id','=','sales_invoice_items.sales_invoice_id')
                                                            ->leftJoin('products', 'sales_invoice_items.product_id','=','products.id')
                                                            ->leftJoin('subcategories', 'products.subcategory_id','=','subcategories.id')
                                                            ->select('sales_invoice_items.*', DB::raw('SUM(sales_invoice_items.quantity) as total_quantity'), DB::raw('SUM(sales_invoice_items.amount) as total_amount'))
                                                            ->where('subcategories.id','=',$subcategory->id)
                                                            ->where('sales_invoice.date', '=', date('Y-m-d'))
                                                            ->get();
                                    $total_quantity_pur_subcat_yest += $purchase_invoice_subcat_yest[0]->total_quantity;
                                    $total_quantity_sal_subcat_yest += $sales_invoice_subcat_yest[0]->total_quantity;
                                    $total_amount_pur_subcat_yest += $purchase_invoice_subcat_yest[0]->total_amount;
                                    $total_amount_sal_subcat_yest += $sales_invoice_subcat_yest[0]->total_amount;
                                    $total_quantity_pur_subcat_today += $purchase_invoice_subcat_today[0]->total_quantity;
                                    $total_quantity_sal_subcat_today += $sales_invoice_subcat_today[0]->total_quantity;
                                    $total_amount_pur_subcat_today += $purchase_invoice_subcat_today[0]->total_amount;
                                    $total_amount_sal_subcat_today += $sales_invoice_subcat_today[0]->total_amount;
                                @endphp
                                <tr>
                                    <td>{{$subcategory->name}}</td>
                                    <td>{{number_format($purchase_invoice_subcat_yest[0]->total_quantity,3)}} KGS  {{setting('default_currency').''.number_format($purchase_invoice_subcat_yest[0]->total_amount,2)}} <b>(PO)</b> <br> {{number_format($sales_invoice_subcat_yest[0]->total_quantity,3)}} KGS  {{setting('default_currency').''.number_format($sales_invoice_subcat_yest[0]->total_amount,2)}} <b>(SO)</b></td>
                                    <td>{{number_format($purchase_invoice_subcat_today[0]->total_quantity,3)}} KGS  {{setting('default_currency').''.number_format($purchase_invoice_subcat_today[0]->total_amount,2)}} <b>(PO)</b> <br> {{number_format($sales_invoice_subcat_today[0]->total_quantity,3)}} KGS  {{setting('default_currency').''.number_format($sales_invoice_subcat_today[0]->total_amount,2)}} <b>(SO)</b></td>
                                    <!-- <td></td> -->
                                </tr>
                                @endforeach
                                <tr>
                                    <td>Total</td>
                                    <td>{{number_format($total_quantity_pur_subcat_yest,3)}} KGS {{setting('default_currency').''.number_format($total_amount_pur_subcat_yest,2)}} <b>(PO)</b> <br> {{number_format($total_quantity_sal_subcat_yest,3)}} KGS {{setting('default_currency').''.number_format($total_amount_sal_subcat_yest,2)}} <b>(SO)</b></td>
                                    <td>{{number_format($total_quantity_pur_subcat_today,3)}} KGS {{setting('default_currency').''.number_format($total_amount_pur_subcat_today,2)}} <b>(PO)</b> <br> {{number_format($total_quantity_sal_subcat_today,3)}} KGS {{setting('default_currency').''.number_format($total_amount_sal_subcat_today,2)}} <b>(SO)</b></td>
                                    <!-- <td></td> -->
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!--Party Reviews-->
                <div class="card dashboard-card">
                    <div class="card-header no-border">
                        <h3 class="card-title">Party Feedback</h3><br>
                        <div class="card-tools">
                            <a href="#" class="btn btn-tool btn-sm"><i class="fa fa-bars"></i> </a>
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
                                    <td>Total</td>
                                    <td>{!! DB::table('driver_reviews')->count('rate'); !!}</td>
                                    <td>{!! DB::table('customer_farmer_reviews')->count('rate'); !!}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!--Party Levels View-->
                <div class="card dashboard-card">
                    <div class="card-header no-border">
                        <h3 class="card-title">Party Levels View</h3><br>
                        <div class="card-tools">
                            <a href="#" class="btn btn-tool btn-sm"><i class="fa fa-bars"></i> </a>
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
                                      <td>{!! DB::table('users')->leftJoin('markets', 'markets.user_id', '=', 'users.id')->where('level',$val->id)->where('active',1)->count(); !!}</td>
                                      @endforeach
                                </tr>
                                <tr>
                                      <td>{{ trans('lang.inactive_customers') }}</td>
                                       @foreach($customer_levels  as $val)
                                      <td>{!! DB::table('users')->leftJoin('markets', 'markets.user_id', '=', 'users.id')->where('level',$val->id)->where('active',0)->count(); !!}</td>
                                      @endforeach
                                </tr>
                                <tr>
                                    <td>Total</td>
                                     @foreach($customer_levels  as $val)
                                      <td>{!! DB::table('users')->leftJoin('markets', 'markets.user_id', '=', 'users.id')->where('level',$val->id)->count(); !!}</td>
                                      @endforeach
                                    
                                </tr> 
                            </tbody>
                        </table>
                    </div>
                </div>

                <!--DRIVER'S PRIMARY PERFORMANCE INDICATORS-->
                <div class="card dashboard-card">
                    <div class="card-header no-border">
                        <h3 class="card-title">Driver's Primary Performance Indicators</h3><br>
                        <div class="card-tools">
                            <a href="#" class="btn btn-tool btn-sm"><i class="fa fa-bars"></i> </a>
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
                                $delivery_completed_number = DB::table('orders')->where('status','delivered')->count();
                                $delivery_completed_amount = DB::table('orders')->where('status','delivered')->sum('order_amount');
                                $delivery_completed_quantities = DB::table('orders')->leftJoin('product_orders', 'product_orders.order_id', '=', 'orders.id')->where('status','delivered')->sum('quantity');
                                
                                $delivery_pending_number = DB::table('orders')->whereNotIn('status', ['delivered','cancelled'])->count();
                                $delivery_pending_amount = DB::table('orders')->whereNotIn('status', ['delivered','cancelled'])->sum('order_amount');
                                $delivery_pending_quantities = DB::table('orders')->leftJoin('product_orders', 'product_orders.order_id', '=', 'orders.id')->whereNotIn('status', ['delivered','cancelled'])->sum('quantity');

                                $delivery_cancel_number = DB::table('orders')->where('status','cancelled')->count();
                                $delivery_cancel_amount = DB::table('orders')->where('status','cancelled')->sum('order_amount');
                                $delivery_cancel_quantities = DB::table('orders')->leftJoin('product_orders', 'product_orders.order_id', '=', 'orders.id')->where('status','cancelled')->sum('quantity');
                                
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
                                    <td>Disputed Deliveries</td>
                                    <td>{!! $delivery_cancel_number !!}</td>
                                    <td>{!! $delivery_cancel_amount !!}</td>
                                    <td>{!! $delivery_cancel_quantities !!}</td>
                                </tr>
                                
                                 <tr>
                                    <td>{{trans('lang.total')}}</td>
                                    <td>{!! $delivery_completed_number+$delivery_pending_number+$delivery_cancel_number !!}</td>
                                    <td>{!! $delivery_completed_amount+$delivery_pending_amount+$delivery_cancel_amount !!}</td>
                                    <td>{!! $delivery_completed_quantities+$delivery_pending_quantities+$delivery_cancel_quantities !!}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                
                <!--DRIVER'S OTHER PERFORMANCE INDICATORS-->
                <div class="card dashboard-card">
                    <div class="card-header no-border">
                        <h3 class="card-title">Driver's Other Performance Indicators</h3><br>
                        <div class="card-tools">
                            <a href="#" class="btn btn-tool btn-sm"><i class="fa fa-bars"></i> </a>
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
                                /*$delivery_completed_number = DB::table('orders')->where('order_status_id',5)->count();
                                $delivery_completed_amount = DB::table('orders')->where('order_status_id',5)->sum('order_amount');
                                $delivery_completed_quantities = DB::table('orders')->leftJoin('product_orders', 'product_orders.order_id', '=', 'orders.id')->where('order_status_id',5)->sum('quantity');
                                
                                $delivery_pending_number = DB::table('orders')->where('order_status_id','<',5)->count();
                                $delivery_pending_amount = DB::table('orders')->where('order_status_id','<',5)->sum('order_amount');
                                $delivery_pending_quantities = DB::table('orders')->leftJoin('product_orders', 'product_orders.order_id', '=', 'orders.id')->where('order_status_id','<',5)->sum('quantity');*/

                                $delivery_completed_number = DB::table('orders')->where('status','delivered')->count();
                                $delivery_completed_amount = DB::table('orders')->where('status','delivered')->sum('order_amount');
                                $delivery_completed_quantities = DB::table('orders')->leftJoin('product_orders', 'product_orders.order_id', '=', 'orders.id')->where('status','delivered')->sum('quantity');
                                
                                $delivery_pending_number = DB::table('orders')->whereNotIn('status', ['delivered','cancelled'])->count();
                                $delivery_pending_amount = DB::table('orders')->whereNotIn('status', ['delivered','cancelled'])->sum('order_amount');
                                $delivery_pending_quantities = DB::table('orders')->leftJoin('product_orders', 'product_orders.order_id', '=', 'orders.id')->whereNotIn('status', ['delivered','cancelled'])->sum('quantity');

                                $delivery_cancel_number = DB::table('orders')->where('status','cancelled')->count();
                                $delivery_cancel_amount = DB::table('orders')->where('status','cancelled')->sum('order_amount');
                                $delivery_cancel_quantities = DB::table('orders')->leftJoin('product_orders', 'product_orders.order_id', '=', 'orders.id')->where('status','cancelled')->sum('quantity');
                                
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
                                    <td>Average Delivery Time</td>
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
                                 <tr>
                                    <td>Total</td>
                                    @foreach($drivers as $val)
                                    <td>{!! DB::table('orders')->where('order_status_id',5)->where('driver_id',$val->id)->sum('delivery_distance'); !!}</td>
                                    @endforeach
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!--Staff Attendance-->
                <div class="card dashboard-card">
                    <div class="card-header no-border">
                        <h3 class="card-title">Staff Work Status</h3><br>
                        <div class="card-tools">
                            <a href="#" class="btn btn-tool btn-sm"><i class="fa fa-bars"></i> </a>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <table class="table table-striped table-valign-middle">
                            <thead>
                            <tr>
                              <th></th>
                              <th>Today</th>
                            </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    @php 
                                        $present = DB::table('attendances')->whereDate('clock_in_time','=',date('Y-m-d'))->groupBy('attendances.user_id')->count('attendances.clock_in_time');

                                        $log_out = DB::table('attendances')->whereDate('clock_out_time','=',date('Y-m-d'))->groupBy('attendances.user_id')->count('attendances.clock_in_time');

                                        $working = DB::table('attendances')->whereDate('clock_in_time','=',date('Y-m-d'))->whereDate('clock_out_time','=','')->groupBy('attendances.user_id')->count('attendances.clock_in_time');

                                        $break = DB::table('attendances')->leftJoin('attendance_punchin', 'attendances.id','=','attendance_punchin.attendance_id')->where('attendances.clock_out_time','=','')->whereDate('attendance_punchin.punch_time','=',date('Y-m-d'))->where('attendance_punchin.punch_type','=','punch_out')->groupBy('attendance_punchin.attendance_id')->count('attendance_punchin.attendance_id');
                                    @endphp
                                    <td>At Work</td>
                                    <td>{{$working}}</td>
                                </tr>
                                <tr>
                                    <td>On Break</td>
                                    <td>{{$break}}</td>
                                </tr>
                                <tr>
                                    <td>Logged Off</td>
                                    <td>{{$log_out}}</td>
                                </tr>
                                <tr>
                                    <td>On Leave</td>
                                    <td>{{$staffs_count-$present}}</td>
                                </tr>
                                <!-- <tr>
                                    <td>In Active</td>
                                    <td></td>
                                </tr> -->
                                <tr>
                                    <td>Total</td>
                                    <td>{{$staffs_count}}</td>
                                </tr>

                            </tbody>
                        </table>
                    </div>
                </div>
 
            </div>
            
            
            <div class="col-lg-6 dashboard-card-column">
                
                <!--Sales Quotes-->
                <div class="card dashboard-card">
                    <div class="card-header no-border">
                        <h3 class="card-title">Sales Quotes</h3><br>
                        <div class="card-tools">
                            <a href="#" class="btn btn-tool btn-sm"><i class="fa fa-bars"></i> </a>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <table class="table table-striped table-valign-middle">
                            <thead>
                            <tr>
                               
                                <th>SO Source</th>
                                <th>Yesterday</th>
                                <th>Today</th>
                                <!-- <th>Tommorrow</th>
                                <th>Future</th> -->
                            </tr>
                            </thead>
                            <tbody>
                                @php
                                    $quotes_received_from_yest = DB::table('quotes')
                                                                    ->leftJoin('quote_items', 'quotes.id','=','quote_items.quote_id')
                                                                    ->select('quotes.*', DB::raw('SUM(quote_items.quantity) as total_quantity'), DB::raw('SUM(quote_items.amount) as total_amount'))
                                                                    ->where('quotes.date', '=', date('Y-m-d',strtotime("-1 days")))
                                                                    ->get();
                                    $quotes_received_from_today = DB::table('quotes')
                                                                    ->leftJoin('quote_items', 'quotes.id','=','quote_items.quote_id')
                                                                    ->select('quotes.*', DB::raw('SUM(quote_items.quantity) as total_quantity'), DB::raw('SUM(quote_items.amount) as total_amount'))
                                                                    ->where('quotes.date', '=', date('Y-m-d'))
                                                                    ->get();
                                @endphp
                                <tr>
                                    <td>Website</td>
                                    <td>{{number_format($quotes_received_from_yest[0]->total_quantity,3)}} KGS <br> {{setting('default_currency').''.number_format($quotes_received_from_yest[0]->total_amount,2)}}</td>
                                    <td>{{number_format($quotes_received_from_today[0]->total_quantity,3)}} KGS <br> {{setting('default_currency').''.number_format($quotes_received_from_today[0]->total_amount,2)}}</td>
                                    <!-- <td></td> -->
                                </tr>
                                <tr>
                                    <td>Android</td>
                                    <td></td>
                                    <td></td>
                                    <!-- <td></td> -->
                                </tr>
                                <tr>
                                    <td>Apple</td>
                                    <td></td>
                                    <td></td>
                                    <!-- <td></td> -->
                                </tr>
                                <tr>
                                    <td>Phone</td>
                                    <td></td>
                                    <td></td>
                                    <!-- <td></td> -->
                                </tr>
                                <tr>
                                    <td>Total</td>
                                    <td>{{number_format($quotes_received_from_yest[0]->total_quantity,3)}} KGS <br> {{setting('default_currency').''.number_format($quotes_received_from_yest[0]->total_amount,2)}}</td>
                                    <td>{{number_format($quotes_received_from_today[0]->total_quantity,3)}} KGS <br> {{setting('default_currency').''.number_format($quotes_received_from_today[0]->total_amount,2)}}</td>
                                    <!-- <td></td> -->
                                </tr>
                            
                            </tbody>
                        </table>
                    </div>
                </div>
                
                <?php /* ?>
                <!--Sales Order Status-->
                <div class="card dashboard-card">
                    <div class="card-header no-border">
                        <h3 class="card-title">Sales Order Type</h3><br>
                        <div class="card-tools">
                            <a href="#" class="btn btn-tool btn-sm"><i class="fa fa-bars"></i> </a>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <table class="table table-striped table-valign-middle">
                            <thead>
                            <tr>
                               
                                <th>SO Status</th>
                                <th>Today</th>
                                <th>Tommorrow</th>
                                <th>Future</th>
                            </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Residential</td>
                                    <td>{{number_format($sales_order_type[0]->total_quantity,3)}} KGS <br> {{setting('default_currency').''.number_format($sales_order_type[0]->total_amount,2)}}</td>
                                    <td>{{number_format($sales_order_type[0]->total_quantity,3)}} KGS <br> {{setting('default_currency').''.number_format($sales_order_type[0]->total_amount,2)}}</td>
                                    <td>{{number_format($sales_order_type[0]->total_quantity,3)}} KGS <br> {{setting('default_currency').''.number_format($sales_order_type[0]->total_amount,2)}}</td>
                                </tr>
                                <tr>
                                    <td>Business</td>
                                    <td>{{number_format($sales_order_type[1]->total_quantity,3)}} KGS <br> {{setting('default_currency').''.number_format($sales_order_type[1]->total_amount,2)}}</td>
                                    <td>{{number_format($sales_order_type[1]->total_quantity,3)}} KGS <br> {{setting('default_currency').''.number_format($sales_order_type[1]->total_amount,2)}}</td>
                                    <td>{{number_format($sales_order_type[1]->total_quantity,3)}} KGS <br> {{setting('default_currency').''.number_format($sales_order_type[1]->total_amount,2)}}</td>
                                </tr>
                                <tr>
                                    <td>Commercial</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>Charity & NGO</td>
                                    <td>{{number_format($sales_order_type[2]->total_quantity,3)}} KGS <br> {{setting('default_currency').''.number_format($sales_order_type[2]->total_amount,2)}}</td>
                                    <td>{{number_format($sales_order_type[2]->total_quantity,3)}} KGS <br> {{setting('default_currency').''.number_format($sales_order_type[2]->total_amount,2)}}</td>
                                    <td>{{number_format($sales_order_type[2]->total_quantity,3)}} KGS <br> {{setting('default_currency').''.number_format($sales_order_type[2]->total_amount,2)}}</td>
                                </tr>
                                <tr>
                                    <td>Total</td>
                                    <td>{{number_format($sales_order_type[0]->total_quantity+$sales_order_type[1]->total_quantity+$sales_order_type[2]->total_quantity,3)}} KGS <br> {{setting('default_currency').''.number_format($sales_order_type[0]->total_amount+$sales_order_type[1]->total_amount+$sales_order_type[2]->total_amount,2)}}</td>
                                    <td></td>
                                    <td></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div> <?php */ ?>

                <!--Sales Order Status-->
                <div class="card dashboard-card">
                    <div class="card-header no-border">
                        <h3 class="card-title">Sales Order Type</h3><br>
                        <div class="card-tools">
                            <a href="#" class="btn btn-tool btn-sm"><i class="fa fa-bars"></i> </a>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <table class="table table-striped table-valign-middle">
                            <thead>
                            <tr>
                               
                                <th>SO Status</th>
                                <th>Yesterday</th>
                                <th>Today</th>
                                <!-- <th>Tommorrow</th> -->
                                <!-- <th>Future</th> -->
                            </tr>
                            </thead>
                            <tbody>
                            @php 
                                $total_quantity_sal_order_type_yest = 0;
                                $total_amount_sal_order_type_yest = 0;
                                $total_quantity_sal_order_type_today = 0;
                                $total_amount_sal_order_type_today = 0;
                            @endphp
                            @foreach ($party_sub_types_customer as $party_sub_type_customer)
                            @php
                                $sales_invoice_order_type_yest = DB::table('sales_invoice')
                                                        ->leftJoin('sales_invoice_items', 'sales_invoice.id','=','sales_invoice_items.sales_invoice_id')
                                                        ->leftJoin('markets', 'sales_invoice.market_id','=','markets.id')
                                                        ->leftJoin('party_sub_types', 'markets.type','=','party_sub_types.id')
                                                        ->select('sales_invoice.*', DB::raw('SUM(sales_invoice_items.quantity) as total_quantity'), DB::raw('SUM(sales_invoice_items.amount) as total_amount'))
                                                        ->where('party_sub_types.id','=',$party_sub_type_customer->id)
                                                        ->where('sales_invoice.date', '=', date('Y-m-d',strtotime("-1 days")))
                                                        ->get();
                                $sales_invoice_order_type_today = DB::table('sales_invoice')
                                                        ->leftJoin('sales_invoice_items', 'sales_invoice.id','=','sales_invoice_items.sales_invoice_id')
                                                        ->leftJoin('markets', 'sales_invoice.market_id','=','markets.id')
                                                        ->leftJoin('party_sub_types', 'markets.type','=','party_sub_types.id')
                                                        ->select('sales_invoice.*', DB::raw('SUM(sales_invoice_items.quantity) as total_quantity'), DB::raw('SUM(sales_invoice_items.amount) as total_amount'))
                                                        ->where('party_sub_types.id','=',$party_sub_type_customer->id)
                                                        ->where('sales_invoice.date', '=', date('Y-m-d'))
                                                        ->get();
                                $total_quantity_sal_order_type_yest += $sales_invoice_order_type_yest[0]->total_quantity;
                                $total_amount_sal_order_type_yest += $sales_invoice_order_type_yest[0]->total_amount;
                                $total_quantity_sal_order_type_today += $sales_invoice_order_type_today[0]->total_quantity;
                                $total_amount_sal_order_type_today += $sales_invoice_order_type_today[0]->total_amount;
                            @endphp

                            <tr>
                                <td>{{$party_sub_type_customer->name}}</td>
                                <td>{{number_format($sales_invoice_order_type_yest[0]->total_quantity,3)}} KGS <br> {{setting('default_currency').''.number_format($sales_invoice_order_type_yest[0]->total_amount,2)}}</td>
                                <td>{{number_format($sales_invoice_order_type_today[0]->total_quantity,3)}} KGS <br> {{setting('default_currency').''.number_format($sales_invoice_order_type_today[0]->total_amount,2)}}</td>
                                <!-- <td></td> -->
                            </tr>
                            @endforeach
                            
                            <tr>
                                <td>Total</td>
                                <td>{{number_format($total_quantity_sal_order_type_yest,3)}} KGS <br> {{setting('default_currency').''.number_format($total_amount_sal_order_type_yest,2)}}</td>
                                <td>{{number_format($total_quantity_sal_order_type_today,3)}} KGS <br> {{setting('default_currency').''.number_format($total_amount_sal_order_type_today,2)}}</td>
                                <!-- <td></td> -->
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <?php /* ?>
                 <!--Purchase Orders-->
                <div class="card dashboard-card">
                    <div class="card-header no-border">
                        <h3 class="card-title">Purchase Orders</h3><br>
                        <div class="card-tools">
                            <a href="#" class="btn btn-tool btn-sm"><i class="fa fa-bars"></i> </a>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <table class="table table-striped table-valign-middle">
                            <thead>
                            <tr>
                               
                                <th>PO Source</th>
                                <th>Today</th>
                                <th>Tommorrow</th>
                                <th>Future</th>
                            </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Farmers</td>
                                    <td>{{number_format($purchase_order_received_from[0]->total_quantity,3)}} KGS <br> {{setting('default_currency').''.number_format($purchase_order_received_from[0]->total_amount,2)}}</td>
                                    <td>{{number_format($purchase_order_received_from[0]->total_quantity,3)}} KGS <br> {{setting('default_currency').''.number_format($purchase_order_received_from[0]->total_amount,2)}}</td>
                                    <td>{{number_format($purchase_order_received_from[0]->total_quantity,3)}} KGS <br> {{setting('default_currency').''.number_format($purchase_order_received_from[0]->total_amount,2)}}</td>
                                </tr>
                                <tr>
                                    <td>Village Market</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>District Market</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>City Govt Market</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>City Retail Market</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>Total</td>
                                    <td>{{number_format($purchase_order_received_from[0]->total_quantity,3)}} KGS <br> {{setting('default_currency').''.number_format($purchase_order_received_from[0]->total_amount,2)}}</td>
                                    <td></td>
                                    <td></td>
                                </tr>
                            
                            </tbody>
                        </table>
                    </div>
                </div> <?php */ ?>

                <!--Purchase Orders-->
                <div class="card dashboard-card">
                    <div class="card-header no-border">
                        <h3 class="card-title">Purchase Orders</h3><br>
                        <div class="card-tools">
                            <a href="#" class="btn btn-tool btn-sm"><i class="fa fa-bars"></i> </a>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <table class="table table-striped table-valign-middle">
                            <thead>
                            <tr>
                               
                                <th>PO Source</th>
                                <th>Yesterday</th>
                                <th>Today</th>
                                <!-- <th>Future</th> -->
                            </tr>
                            </thead>
                            <tbody>
                                @php 
                                    $total_quantity_pur_poorder_yest = 0;
                                    $total_amount_pur_poorder_yest = 0;
                                    $total_quantity_pur_poorder_today = 0;
                                    $total_amount_pur_poorder_today = 0;
                                @endphp
                                @foreach ($party_types as $party_type)
                                @php
                                    $purchase_invoice_poorder_yest = DB::table('purchase_invoice')
                                                            ->leftJoin('purchase_invoice_items', 'purchase_invoice.id','=','purchase_invoice_items.purchase_invoice_id')
                                                            ->leftJoin('markets', 'purchase_invoice.market_id','=','markets.id')
                                                            ->leftJoin('party_types', 'markets.type','=','party_types.id')
                                                            ->select('purchase_invoice.*', DB::raw('SUM(purchase_invoice_items.quantity) as total_quantity'), DB::raw('SUM(purchase_invoice_items.amount) as total_amount'))
                                                            ->where('party_types.id','=',$party_type->id)
                                                            ->where('purchase_invoice.date', '=', date('Y-m-d',strtotime("-1 days")))
                                                            ->get();
                                    $purchase_invoice_poorder_today = DB::table('purchase_invoice')
                                                            ->leftJoin('purchase_invoice_items', 'purchase_invoice.id','=','purchase_invoice_items.purchase_invoice_id')
                                                            ->leftJoin('markets', 'purchase_invoice.market_id','=','markets.id')
                                                            ->leftJoin('party_types', 'markets.type','=','party_types.id')
                                                            ->select('purchase_invoice.*', DB::raw('SUM(purchase_invoice_items.quantity) as total_quantity'), DB::raw('SUM(purchase_invoice_items.amount) as total_amount'))
                                                            ->where('party_types.id','=',$party_type->id)
                                                            ->where('purchase_invoice.date', '=', date('Y-m-d'))
                                                            ->get();
                                    $total_quantity_pur_poorder_yest += $purchase_invoice_poorder_yest[0]->total_quantity;
                                    $total_amount_pur_poorder_yest += $purchase_invoice_poorder_yest[0]->total_amount;
                                    $total_quantity_pur_poorder_today += $purchase_invoice_poorder_today[0]->total_quantity;
                                    $total_amount_pur_poorder_today += $purchase_invoice_poorder_today[0]->total_amount;
                                @endphp

                                <tr>
                                    <td>{{$party_type->name}}</td>
                                    <td>{{number_format($purchase_invoice_poorder_yest[0]->total_quantity,3)}} KGS <br> {{setting('default_currency').''.number_format($purchase_invoice_poorder_yest[0]->total_amount,2)}}</td>
                                    <td>{{number_format($purchase_invoice_poorder_today[0]->total_quantity,3)}} KGS <br> {{setting('default_currency').''.number_format($purchase_invoice_poorder_today[0]->total_amount,2)}}</td>
                                    <!-- <td></td> -->
                                </tr>
                                @endforeach

                                <tr>
                                    <td>Total</td>
                                    <td>{{number_format($total_quantity_pur_poorder_yest,3)}} KGS <br> {{setting('default_currency').''.number_format($total_amount_pur_poorder_yest,2)}}</td>
                                    <td>{{number_format($total_quantity_pur_poorder_today,3)}} KGS <br> {{setting('default_currency').''.number_format($total_amount_pur_poorder_today,2)}}</td>
                                    <!-- <td></td> -->
                                </tr>
                            
                            </tbody>
                        </table>
                    </div>
                </div>

                <!--Party Stream View-->
                 <div class="card dashboard-card">
                    <div class="card-header no-border">
                        <h3 class="card-title">Party Stream View</h3><br>
                        <div class="card-tools">
                            <a href="#" class="btn btn-tool btn-sm"><i class="fa fa-bars"></i> </a>
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
                                 <td>Total</td>
                                 <td>{!! DB::table('markets')->where('active',1)->count('stream'); !!}</td>
                                 <td>{!! DB::table('markets')->where('active',0)->count('stream'); !!}</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!--Party Promo codes & Discounts-->
                 <div class="card dashboard-card">
                    <div class="card-header no-border">
                        <h3 class="card-title">Party Promo Codes & Discounts</h3><br>
                        <div class="card-tools">
                            <a href="#" class="btn btn-tool btn-sm"><i class="fa fa-bars"></i> </a>
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
                                 <td>Total</td>
                                 <td>{!! DB::table('coupons')->where('enabled',1)->count(); !!}</td>
                                 <td>{!! DB::table('coupons')->where('enabled',0)->count(); !!}</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!--Party Reward Points View-->
                <div class="card dashboard-card">
                    <div class="card-header no-border">
                        <h3 class="card-title">Party Rewards Points View</h3><br>
                        <div class="card-tools">
                            <a href="#" class="btn btn-tool btn-sm"><i class="fa fa-bars"></i> </a>
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
                                @php 
                                $earned = DB::table('loyality_points_tracker')->sum('points');
                                $redeemed = DB::table('loyality_point_usage')->sum('points');
                                
                                @endphp
                                <tr>
                                    <td>{{trans('lang.rewards_earned')}}</td>
                                    <td>{!! $earned !!}</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>{{trans('lang.rewards_redeemed')}}</td>
                                    <td>{!! $redeemed !!}</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>{{ trans('lang.balance_rewards') }}</td>
                                    <td>{!! $earned - $redeemed !!}</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>Total</td>
                                    <td>{!! $earned !!}</td>
                                    <td></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!--Party Reward Points View-->
                 <div class="card dashboard-card">
                    <div class="card-header no-border">
                        <h3 class="card-title">Party Rewards Points View</h3><br>
                        <div class="card-tools">
                            <a href="#" class="btn btn-tool btn-sm"><i class="fa fa-bars"></i> </a>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <table class="table table-striped table-valign-middle">
                            <thead>
                            <tr>
                              <th>{{trans('lang.indicative_charity_impact')}}</th>
                              <th>{!! DB::table('orders')->sum('contribution_amount') !!}</th>
                            </tr>
                            <tr>
                              <th>Actual Charity Impact</th>
                              <th>{!! DB::table('orders')->sum('contribution_amount') !!}</th>
                            </tr>
                            </thead>
                        </table>
                    </div>
                </div>

                <!--Party Complaints Vs Compliments-->
                <div class="card dashboard-card">
                    <div class="card-header no-border">
                        <h3 class="card-title">Party Complaints vs Compliments</h3><br>
                        <div class="card-tools">
                            <a href="#" class="btn btn-tool btn-sm"><i class="fa fa-bars"></i> </a>
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

                <!--Party Flow (Customers | Farmers)-->
                <div class="card dashboard-card">
                    <div class="card-header no-border">
                        <h3 class="card-title">Party Flow</h3><br>
                        <div class="card-tools">
                            <a href="#" class="btn btn-tool btn-sm"><i class="fa fa-bars"></i> </a>
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
                
                <!--Staff Attendance-->
                <div class="card dashboard-card">
                    <div class="card-header no-border">
                        <h3 class="card-title">Staff Attendance</h3><br>
                        <div class="card-tools">
                            <a href="#" class="btn btn-tool btn-sm"><i class="fa fa-bars"></i> </a>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <table class="table table-striped table-valign-middle">
                            <thead>
                            <tr>
                              <th></th>
                              <th>Present</th>
                              <th>Absent</th>
                              <th>Total</th>
                            </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    @php 
                                        $present = DB::table('attendances')->whereDate('clock_in_time','=',date('Y-m-d'))->groupBy('attendances.user_id')->count('attendances.clock_in_time');
                                    @endphp
                                    <td>Today</td>
                                    <td>{{$present}}</td>
                                    <td>{{$staffs_count-$present}}</td>
                                    <td>{{$staffs_count}}</td>
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
<script src="https://bootstrap.gallery/cliq/vendor/apex/apexcharts.min.js"></script>    
<script src="https://bootstrap.gallery/cliq/vendor/circliful/circliful.min.js"></script>
<script src="https://bootstrap.gallery/cliq/vendor/apex/apexcharts.min.js"></script>    
<script src="https://bootstrap.gallery/cliq/vendor/circliful/circliful.min.js"></script>
@endpush
@push('scripts')
<script type="text/javascript">


    function cb(start, end) {
        
        $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
        var start_date      = start.format('YYYY-MM-DD');
        var end_date        = end.format('YYYY-MM-DD');

        $.ajax({
            url: "{{url('dashboardDatas')}}",
            method:'POST',
            data: {
                '_token':"{{ csrf_token() }}",
                'start_date' : start_date,
                'end_date' : end_date
            },
            success: function (result) {

                $('.total-parties').html(result.data.total_markets);
                $('.total-admin-portal-parites').html(result.data.admin_markets);
                $('.total-website-parites').html(result.data.website_markets);
                $('.total-mobile-app-parties').html(result.data.mobile_markets);

                $('.total-orders').html(result.data.online_orders);
                $('.total-website-orders').html(result.data.website_orders);
                $('.total-mobile-app-orders').html(result.data.mobile_orders);
               
                $('.total-products').html(result.data.total_products);
                $('.total-deliverable-products').html(result.data.total_deliverable_products);
                $('.total-weight-loss-products').html(result.data.total_weight_loss_products);
                $('.total-low-profit-products').html();
                
                $('.out-of-stock-items').html(result.data.out_of_stock_items+' Items');
                $('.wastage-items').html(result.data.wastage_items+' Items');

                $('.total-rewards').html(result.data.filter_rewards);
                $('.total-driver-review').html(result.data.filter_driver_review);
                $('.total-complaints').html(result.data.filter_complaints);
                $('.total-notifications').html(result.data.total_notifications);
                
                $('.total-discount-coupons').html(result.data.discount_coupons);
                $('.total-charity').html(result.data.charity);
                $('.total-delivery-zones').html(result.data.delivery_zones);
                $('.total-email-alerts').html(result.data.email_alerts);

                $("#outofStockGraph").circliful({
                   animation: 1,
                   animationStep: 5,
                   foregroundBorderWidth: 15,
                   backgroundBorderWidth: 15,
                   percent: result.data.out_of_stock,
                   textStyle: "font-size: 12px;",
                   fontColor: "#ffffff",
                   foregroundColor: "#ffffff",
                   backgroundColor: "rgba(255, 255, 255, 0.1)",
                   multiPercentage: 1,
                   percentages: [10, 20, 30]
                });

                $("#wastageGraph").circliful({
                   animation: 1,
                   animationStep: 5,
                   foregroundBorderWidth: 15,
                   backgroundBorderWidth: 15,
                   percent: result.data.wastage,
                   textStyle: "font-size: 12px;",
                   fontColor: "#ffffff",
                   foregroundColor: "#ffffff",
                   backgroundColor: "rgba(255, 255, 255, 0.1)",
                   multiPercentage: 1,
                   percentages: [10, 20, 30]
                });

                var options = {
                    chart: {
                        height: 290,
                        type: "donut"
                    },
                    labels: ["Sales", "Purchase", "Expenses"],
                    series: [result.data.sales, result.data.purchase, result.data.expenses],
                    legend: {
                        position: "bottom"
                    },
                    dataLabels: {
                        enabled: !1
                    },
                    stroke: {
                        width: 0
                    },
                    colors: ["#0e3e26", "#3b5438", "#d9b44a"],
                    tooltip: {
                        y: {
                            formatter: function(e) {
                                return "{{setting('default_currency')}}" + e
                            }
                        }
                    }
                },
                chart = new ApexCharts(document.querySelector("#recentOrders"), options);
                chart.render();

                
                var options = {
                    chart: {
                        height: 325,
                        width: "100%",
                        type: "line",
                        stacked: !1,
                        toolbar: {
                            show: !1
                        }
                    },
                    dataLabels: {
                        enabled: !1
                    },
                    colors: ["#ffffff", "#0e3e26", "#ff514b"],
                    series: [{
                        name: "Total",
                        type: "bar",
                        data: result.data.total_staffs
                    }, {
                        name: "Present",
                        type: "bar",
                        data: result.data.total_present
                    }, {
                        name: "Absent",
                        type: "line",
                        data: result.data.total_absent
                    }],
                    stroke: {
                        width: [0, 7, 7]
                    },
                    plotOptions: {
                        bar: {
                            columnWidth: "60%",
                            borderRadius: 7
                        }
                    },
                    xaxis: {
                        categories: result.data.staff_categories
                    },
                    yaxis: [{
                        show: !1
                    }],
                    tooltip: {
                        /*shared: !1,
                        intersect: !0,
                        x: {
                            show: !1
                        }*/
                    },
                    legend: {
                        horizontalAlign: "center"
                    },
                    grid: {
                        borderColor: "#fea67f",
                        strokeDashArray: 0,
                        xaxis: {
                            lines: {
                                show: !1
                            }
                        },
                        yaxis: {
                            lines: {
                                show: !1
                            }
                        },
                        padding: {
                            top: 30,
                            right: 0,
                            bottom: 0,
                            left: 10
                        }
                    }
                },
                chart = new ApexCharts(document.querySelector("#staffAttendanceGraph"), options);
                chart.render();


                //Sales Graph

                var options = {
                    chart: {
                        height: 320,
                        width: "100%",
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
                        data: result.data.weekly_sales
                    }/*, {
                        name: "Revenue",
                        data: [20, 0, 0, 0, 0, 10,10]
                    }*/],
                    xaxis: {
                        categories: result.data.last_seven_days
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
                                return "{{setting('default_currency')}}" + e
                            }
                        }
                    },
                    colors: ["#50bfca", "#FFFFFF"]
                },
                chart = new ApexCharts(document.querySelector("#salesGraph"), options);
                chart.render();

                //Sales Graph

                //Purchase Graph

                var purchase_options = {
                    chart: {
                        height: 320,
                        width: "100%",
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
                        name: "Purchase",
                        data: result.data.weekly_purchase
                    }/*, {
                        name: "Revenue",
                        data: [20, 0, 0, 0, 0, 0]
                    }*/],
                    xaxis: {
                        categories: result.data.last_seven_days
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
                                return "{{setting('default_currency')}}" + e
                            }
                        }
                    },
                    colors: ["#326ed2", "#FFFFFF"]
                },
                purchase_chart = new ApexCharts(document.querySelector("#purchaseGraph"), purchase_options);
                purchase_chart.render();

                //Purchase Graph


            },
            error: function (err) {
                console.log(err);
            }
        });            
    }


    /*var staff_options = {
        chart: {
            height: 290,
            type: "donut"
        },
        labels: ["Manager", "Supervisor", "Driver", "Worker"],
        series: [30, 30, 30, 30],
        legend: {
            position: "bottom"
        },
        dataLabels: {
            enabled: !1
        },
        stroke: {
            width: 0
        },
        colors: ["#0e3e26", "#3b5438", "#d9b44a", "#d9b44a"],
        tooltip: {
            y: {
                formatter: function(e) {
                    return "$" + e
                }
            }
        }
    },
    staff_chart = new ApexCharts(document.querySelector("#staffsReport"), staff_options);
    staff_chart.render();*/


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
    
    
    function cb1() {}
    function cb2() {}
    

    var options = {
        chart: {
            height: 110,
            width: "85%",
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
            name: "Notifications",
            data: [80, 300, 300, 50, 150, 170, 550, 500]
        }],
        grid: {
            borderColor: "#bede68",
            strokeDashArray: 0,
            show: !1,
            xaxis: {
                lines: {
                    show: !1
                }
            },
            yaxis: {
                lines: {
                    show: !1
                }
            },
            padding: {
                top: 0,
                right: 10,
                bottom: 0,
                left: 10
            }
        },
        xaxis: {
            labels: {
                show: !1
            }
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
                opacityTo: 0,
                stops: [15, 100]
            }
        },
        colors: ["#ffffff"],
        markers: {
            size: 0,
            opacity: .2,
            colors: ["#ffffff"],
            strokeColor: "#49914E",
            strokeWidth: 2,
            hover: {
                size: 10
            }
        },
        tooltip: {
            y: {
                formatter: function(o) {
                    return o
                }
            }
        }
    },
    chart = new ApexCharts(document.querySelector("#notificationsGraph"), options);
    chart.render();


   

    var options = {
        chart: {
            height: 110,
            width: "55%",
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
            name: "Active Users",
            data: [80, 300, 90, 150]
        }],
        grid: {
            borderColor: "#bede68",
            strokeDashArray: 0,
            show: !1,
            xaxis: {
                lines: {
                    show: !1
                }
            },
            yaxis: {
                lines: {
                    show: !1
                }
            },
            padding: {
                top: 0,
                right: 0,
                bottom: 0,
                left: 0
            }
        },
        xaxis: {
            labels: {
                show: !1
            }
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
                opacityTo: 0,
                stops: [15, 100]
            }
        },
        colors: ["#ffffff"],
        markers: {
            size: 0,
            opacity: .2,
            colors: ["#ffffff"],
            strokeColor: "#49914E",
            strokeWidth: 2,
            hover: {
                size: 10
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
    chart = new ApexCharts(document.querySelector("#signupsGraph"), options);
    chart.render();


    var complaints_options = {
        chart: {
            height: 110,
            width: "55%",
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
            name: "Active Users",
            data: [80, 300, 90, 150]
        }],
        grid: {
            borderColor: "#bede68",
            strokeDashArray: 0,
            show: !1,
            xaxis: {
                lines: {
                    show: !1
                }
            },
            yaxis: {
                lines: {
                    show: !1
                }
            },
            padding: {
                top: 0,
                right: 0,
                bottom: 0,
                left: 0
            }
        },
        xaxis: {
            labels: {
                show: !1
            }
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
                opacityTo: 0,
                stops: [15, 100]
            }
        },
        colors: ["#ffffff"],
        markers: {
            size: 0,
            opacity: .2,
            colors: ["#ffffff"],
            strokeColor: "#49914E",
            strokeWidth: 2,
            hover: {
                size: 10
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
    complaints_chart = new ApexCharts(document.querySelector("#complaintsGraph"), complaints_options);
    complaints_chart.render();


    var driver_options = {
        chart: {
            height: 110,
            width: "55%",
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
            name: "Active Users",
            data: [80, 300, 90, 150]
        }],
        grid: {
            borderColor: "#bede68",
            strokeDashArray: 0,
            show: !1,
            xaxis: {
                lines: {
                    show: !1
                }
            },
            yaxis: {
                lines: {
                    show: !1
                }
            },
            padding: {
                top: 0,
                right: 0,
                bottom: 0,
                left: 0
            }
        },
        xaxis: {
            labels: {
                show: !1
            }
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
                opacityTo: 0,
                stops: [15, 100]
            }
        },
        colors: ["#ffffff"],
        markers: {
            size: 0,
            opacity: .2,
            colors: ["#ffffff"],
            strokeColor: "#49914E",
            strokeWidth: 2,
            hover: {
                size: 10
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
    driver_chart = new ApexCharts(document.querySelector("#driverreviewGraph"), driver_options);
    driver_chart.render();

    


    


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
