@extends('layouts.app')

@push('css_lib')
@include('layouts.datatables_css')
@endpush

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
        .table td {
            padding: 10px 5px !important;
        }
        h1 {
            font-size: 26px;
            text-align: center;
            margin-top: 40px;
            margin-bottom: 20px;
        }

        .panel-default>.panel-heading {
            /* Permalink - use to edit and share this gradient: http://colorzilla.com/gradient-editor/#2340ff+1,7c21f5+100 */
            background: #2340ff;
            /* Old browsers */
            background: -moz-linear-gradient(-45deg, #2340ff 1%, #7c21f5 100%);
            /* FF3.6-15 */
            background: -webkit-linear-gradient(-45deg, #2340ff 1%, #7c21f5 100%);
            /* Chrome10-25,Safari5.1-6 */
            /*background: linear-gradient(45deg,#0D95AD 0%,#FFD2C1 100%);*/
            background: #e8ebe6;
            /* W3C, IE10+, FF16+, Chrome26+, Opera12+, Safari7+ */
            filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#2340ff', endColorstr='#7c21f5', GradientType=1);
            /* IE6-9 fallback on horizontal gradient */
            color: #ffffff;
            padding: 18px;
            border-radius: 10px;
        }

        /*.panel {
            box-shadow: 0px 0px 60px 0px rgba(0, 0, 0, .40);
        }*/

        .panel-group .panel {
            border-radius: 10px;
            margin-bottom: 20px;
            /*border: 1px solid #2340ff;*/
            /* Permalink - use to edit and share this gradient: http://colorzilla.com/gradient-editor/#2340ff+1,7c21f5+100 */
            border-color: #2340ff;
            /* Old browsers */
            border-color: -moz-linear-gradient(-45deg, #2340ff 1%, #7c21f5 100%);
            /* FF3.6-15 */
            border-color: -webkit-linear-gradient(-45deg, #2340ff 1%, #7c21f5 100%);
            /* Chrome10-25,Safari5.1-6 */
            border-color: linear-gradient(135deg, #2340ff 1%, #7c21f5 100%);
            /* W3C, IE10+, FF16+, Chrome26+, Opera12+, Safari7+ */
            filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#2340ff', endColorstr='#7c21f5', GradientType=1);
            /* IE6-9 fallback on horizontal gradient */
            box-shadow: 0 3px 6px rgba(0,0,0,0.16), 0 3px 6px rgba(0,0,0,0.23);
        }

        .panel-title {
            font-size: 18px;
        }

        .panel-title a,
        .panel-title a:hover,
        .panel-title a:focus {
            text-decoration: none;
        }

        .panel-body p {
            font-size: 16px;
        }

        .panel-collapse {
            padding: 20px;
        }
        .details-button {
            color: #fff;
            font-weight: bold;
            background:#004e92;
        }
        body {
            font-size: 13px !important;
        }
        .delivery-btn-accept {
            padding: 5px 20px;
            margin: 0px 0px 0px 10px;
            background: #6aab46;
            color: #fff;
            border-radius: 20px;
        }
        .delivery-btn-reject {
            padding: 5px 20px;
            margin: 0px 0px 0px 10px;
            background: #f44336;
            color: #fff;
            border-radius: 20px;
        }


        #tile-1 .tab-pane
        {
          padding:15px;
          height:80px;
        }
        #tile-1 .nav-tabs
        {
          position:relative;
          border:none!important;
          background-color:#f4f6f9;
          border-radius:6px;
        }
        #tile-1 .nav-tabs li
        {
          margin:0px!important;
        }
        #tile-1 .nav-tabs li a
        {
          position:relative;
          margin-right:0px!important;
          padding: 15px 40px!important;
          font-size:16px;
          border:none!important;
          color:#333;
        }
        #tile-1 .nav-tabs a:hover
        {
          background-color:#f4f6f9 !important;
          border:none;
        }
        #tile-1 .slider
        {
          display:inline-block;
          width:30px;
          height:4px;
          border-radius:3px;
          background-color:#39bcd3;
          position:absolute;
          z-index:1200;
          bottom:0;
          transition:all .4s linear;
          
        }
        #tile-1 .nav-tabs .active
        {
          background:  linear-gradient(45deg, #4356137d 0%, #03422a52 100%) !important;
          border:none!important;
          color:#fff !important;
        }
        p {
            margin-bottom:  8px;
        }
        .blink_me {
          animation: blinker 1s linear infinite;
        }

        @keyframes blinker {
          50% {
            opacity: 0;
          }
        }
    </style>

    <!-- Content Header (Page header) -->
    <section class="content-header content-header{{setting('fixed_header')}}">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="float-left">
                        Dashboard
                    </h1>
                    <!-- <div id="reportrange"  class="pull-right" style="background: #fff; cursor: pointer; padding: 5px 10px; margin-top: -25px; border: 1px solid #ccc;">
                        <i class="glyphicon glyphicon-calendar fa fa-calendar"></i> &nbsp; <span></span> 
                    </div> -->
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

                    <div class="col-lg-9 col-md-9 col-sm-9 col-12">
                        <div class="card ">
                            <div class="card-body driver-data"></div>

                        </div>
                    </div>
                 
                    <div class="col-lg-3 col-md-3 col-sm-3 col-12">

                        <div class="card gradient-teal-brown card-390">

                            <div class="card-body">

                                <div class="card-title">Timesheet &nbsp;&nbsp;{{date('d M Y')}}</div>    
                                <br>
                                @if(auth()->user()->attendanceToday)
                                    <div class="punch-det text-center">
                                        <h6><b>Punch In at</b></h6>
                                        <p>{{date('D, d M Y h:i A',strtotime(auth()->user()->attendanceToday->clock_in_time))}}</p>
                                    </div>
                                @endif

                                <div class="punch-info">
                                    <div class="punch-hours">
                                        @if(auth()->user()->attendanceToday)
                                            @php
                                                $clkout      = auth()->user()->attendanceToday->clock_out_time;
                                                $time1       = new DateTime(date('H:i:s',strtotime(auth()->user()->attendanceToday->clock_in_time)));
                                                $time2       = new DateTime(($clkout!=null) ? $clkout : date('H:i:s') );
                                                $interval    = $time1->diff($time2);
                                                $total_hours = $interval->format('%H:%I:%S');
                                            @endphp
                                        <span>{{$interval->format('%H:%I:%S')}} Hrs</span>
                                        @else 
                                            @php $total_hours = '00:00:00' @endphp
                                            <span>0 hrs</span>  
                                        @endif  
                                    </div>
                                </div>

                                @php

                                    if(auth()->user()->attendanceToday) {  
                                        $breakPunches = auth()->user()->attendanceToday->punches->toArray();
                                        $breakTime = [];
                                        if(count($breakPunches) > 0) {
                                            foreach(array_chunk($breakPunches,2) as $punch) {
                                                if(isset($punch[0])) {
                                                    isset($punch[1]) ? '' : $punch[1]['punch_time'] = date('H:i:s') ;
                                                    $time1 = new DateTime(date('H:i:s',strtotime($punch[0]['punch_time'])));
                                                    $time2 = new DateTime(date('H:i:s',strtotime($punch[1]['punch_time'])));
                                                    $interval = $time1->diff($time2);
                                                    $breakTime[] = strtotime($interval->format('%H:%I:%S')) - strtotime("00:00:00");    
                                            }
                                        }
                                        $working_hours = date('H:i:s',strtotime('00:00:00') + array_sum($breakTime));
                                        }  else {
                                            $working_hours = '00:00:00';
                                        }
                                    }

                                @endphp

                                <div class="punch-btn-section">
                                    @if(auth()->user()->attendanceToday)
                                        @if(auth()->user()->attendanceToday->clock_out_time == null)  
                                            @if(auth()->user()->attendanceToday->lastPunch->punch_type=='punch_in')
                                                <select class="form-control mb-2" name="break_type" id="break_type">
                                                    <option value="break">Break</option>
                                                    <option value="eod">Day End</option>
                                                </select>
                                                <button type="button" data-type="punch-out" class="btn btn-primary punch-btn">Punch Out</button>
                                            @else
                                                <input type="hidden" name="break_type" id="break_type" value="break"> 
                                                <button type="button" data-type="punch-in" class="btn btn-primary punch-btn">Punch In</button>    
                                            @endif    
                                        @else 
                                            <div class="punch-det">
                                                <h6><b>Punch Out at</b></h6>
                                                <p>{{date('D, d M Y h:i A',strtotime(auth()->user()->attendanceToday->clock_out_time))}}</p>
                                            </div>
                                        @endif
                                    @else
                                        <input type="hidden" name="break_type" id="break_type" value="start">    
                                        <button type="button" data-type="punch-in" class="btn btn-primary punch-btn">Punch In</button>
                                    @endif 
                                </div>

                                <div class="statistics">
                                    <div class="row">
                                        <div class="col-md-6 col-6 text-center">
                                            <div class="stats-box">
                                                <p>Break</p>
                                                <h6>
                                                @if(auth()->user()->attendanceToday)
                                                    @php
                                                        $th = strtotime($total_hours) - strtotime('00:00:00');
                                                        $tw = strtotime($working_hours) - strtotime('00:00:00');
                                                    @endphp
                                                {{($th > 0 && $tw > 0) ? $break_time = date('H:i:s',strtotime('00:00:00') + ($th-$tw)) : $break_time = '00:00:00'}}
                                                @else
                                                    -
                                                @endif
                                                </h6> 
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-6 text-center">
                                            <div class="stats-box">
                                                <p>Overtime</p>
                                                <h6> - </h6>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>

                    </div>
                 
                </div>
                

              <div class="row gutters">
                 
                   <div class="col-xxl-12 col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                      <div class="card gradient-violet-pink card-140">
                         <div class="card-body">
                            <div class="row gutters">
                                
                               <div class="col-lg-3 col-md-3 col-sm-3 col-12">
                                  <a href="{{route('coupons.index')}}">
                                  <div class="sales-tile2">
                                     <div class="sales-tile2-block">
                                        <div class="sales-tile2-icon">
                                            <div class="active-users-icon"><i style="color:#3b6fd1; font-size:25px;" class="fa fa-tasks"></i></div>
                                        </div>
                                        <div class="sales-tile2-details">
                                           <h4 class="total-discount-coupons">0</h4>
                                           <h6>Assigned Orders</h6>
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
                                            <div class="active-users-icon"><i style="color:#6c71c2; font-size:25px;" class="fa fa-truck"></i></div>
                                        </div>
                                        <div class="sales-tile2-details">
                                           <h4 class="total-charity">0</h4>
                                           <h6>On Going Orders</h6>
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
                                            <div class="active-users-icon"><i style="color:#9e73b4; font-size:25px;" class="fa fa-handshake-o"></i></div>
                                        </div>
                                        <div class="sales-tile2-details">
                                           <h4 class="total-delivery-zones">0</h4>
                                           <h6>Delivered Orders</h6>
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
                                            <div class="active-users-icon"><i style="color:#cd75a6; font-size:25px;" class="fa fa-money"></i></div>
                                        </div>
                                        <div class="sales-tile2-details">
                                           <h4 class="total-email-alerts">0</h4>
                                           <h6>Earnings</h6>
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
    </div>

@endsection


@push('scripts_lib')
@include('layouts.datatables_js')
@endpush

<script src="{{asset('plugins/jquery/jquery.min.js')}}"></script>

@push('scripts')

<script>


    $('.punch-btn').click(function() {

        var type = $(this).data('type');

        if(type=='punch-in') {
         
            var break_type = $('#break_type').val();  
            $('.punch-btn').html('<i class="fa fa-spinner fa-spin"></i>');
            $('.punch-btn').attr("disabled", true);
            $.ajax({
                url: "{{route('attendance.punch')}}",
                type: 'POST',
                data: {
                    '_token':"{{ csrf_token() }}",
                    punchtype: type,
                    break_type: break_type
                }         
            }).done(function(results) {
                iziToast.success({
                     backgroundColor: 'linear-gradient(to right, #44a08d, #093637)',
                     messageColor: '#fff',
                     timeout: 3000, 
                     icon: 'fa fa-check', 
                     position: "bottomRight", 
                     iconColor:'#fff',
                     message: 'Punch In Succesfully'
                });
                setTimeout(function () { location.reload(); }, 3000);
            }).fail(function(results) {

               iziToast.success({
                   backgroundColor: 'linear-gradient(to right, #ff4b1f, #ff9068)', 
                   messageColor: '#fff', 
                   timeout: 3000, 
                   icon: 'fa fa-remove', 
                   position: "bottomRight", 
                   iconColor:'#fff', 
                   message: results.statusText
               });
               setTimeout(function () { location.reload(); }, 1000);
            });

       } else {

           var break_type = $('#break_type').val();

           iziToast.show({
           theme: 'dark',
           overlay: true,
           icon: 'fa fa-sign-out',
           title: "{{auth()->user()->name}}",
           message: 'Are you sure to Punch Out!',
           position: 'center', // bottomRight, bottomLeft, topRight, topLeft, topCenter, bottomCenter
           progressBarColor: 'rgb(0, 255, 184)',
           buttons: [
               ['<button>Yes</button>', function (instance, toast) {
                   

                 $('.punch-btn').html('<i class="fa fa-spinner fa-spin"></i>');
                 $('.punch-btn').attr("disabled", true);
                 $.ajax({
                     url: "{{route('attendance.punch')}}",
                     type: 'POST',
                     data: {
                       '_token':"{{ csrf_token() }}",
                       punchtype: type,
                       break_type: break_type
                     }         
                 }).done(function(results) {

                     iziToast.success({
                         backgroundColor: 'linear-gradient(to right, #44a08d, #093637)',
                         messageColor: '#fff',
                         timeout: 3000, 
                         icon: 'fa fa-check', 
                         position: "bottomRight", 
                         iconColor:'#fff',
                         message: 'Punch Out Succesfully'
                     });
                     setTimeout(function () { location.reload(); }, 1000);

                 }).fail(function(results) {

                       iziToast.success({
                           backgroundColor: 'linear-gradient(to right, #ff4b1f, #ff9068)', 
                           messageColor: '#fff', 
                           timeout: 3000, 
                           icon: 'fa fa-remove', 
                           position: "bottomRight", 
                           iconColor:'#fff', 
                           message: results.statusText
                       });
                       setTimeout(function () { location.reload(); }, 1000);
                 });


               }, true], // true to focus
               ['<button>Close</button>', function (instance, toast) {
                   instance.hide({
                       transitionOut: 'fadeOutUp',
                       onClosing: function(instance, toast, closedBy){
                           console.info('closedBy: ' + closedBy); // The return will be: 'closedBy: buttonName'
                       }
                   }, toast, 'buttonName');
               }]
           ],
           onOpening: function(instance, toast){
               console.info('callback abriu!');
           },
           onClosing: function(instance, toast, closedBy){
               console.info('closedBy: ' + closedBy); // tells if it was closed by 'drag' or 'button'
           }
       });

       }  

   });

</script>

<script type="text/javascript">
    
    var table = $('.driver-orders-table').DataTable({
       processing: true,
       serverSide: true,
       pageLength:20,
       ajax: "{{url('driverOrders')}}",
       lengthChange: false,
       searching: false, 
       paging: false, 
       info: false,
       columns: [
           {data: 'date', name: 'date', orderable: false, searchable: true, class: "text-left"},
           {data: 'order_code', name: 'order_code', orderable: false, searchable: true, class: "text-left"},
           {data: 'address', name: 'address', orderable: false, searchable: true, class: "text-left"},
           {data: 'collectable', name: 'collectable', orderable: false, searchable: true, class: "text-right"},
           {data: 'distance', name: 'distance', orderable: false, searchable: true, class: "text-right"},
           {data: 'distance', name: 'distance', orderable: false, searchable: true, class: "text-right"}
       ]
    });

    
    function cb(start, end) {
        $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
        var start_date      = start.format('YYYY-MM-DD');
        var end_date        = end.format('YYYY-MM-DD');
        table.ajax.url("{{url('driverOrders')}}").load(function(result) { });
    }

    function cb1() {}
    function cb2() {}

    function showTable() {
        var url   = "{{url('driverOrders')}}";
        var token = "{{ csrf_token() }}";
        $.ajax({
            type: 'GET',
            data: {
                '_token': token,
                'type': 'summarydata'
            },
            url: url,
            success: function (response) {
                $('.driver-data').html(response.data);
            }
        });
    }
    showTable();

</script>


<script src="https://maps.googleapis.com/maps/api/js?sensor=false&libraries=places&language=en-AU&key={{ setting('google_maps_key') }}"></script>


@endpush
