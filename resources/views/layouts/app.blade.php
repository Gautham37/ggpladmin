<!DOCTYPE html>
<html lang="{{setting('language','en')}}" dir="ltr">
<head>
    <meta charset="UTF-8">
    <title>{{setting('app_name')}} | {{setting('app_short_description')}}</title>
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <link rel="icon" type="image/png" href="{{$app_logo}}"/>
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{asset('plugins/font-awesome/css/font-awesome.min.css')}}">
   

    <!-- Ionicons -->
{{--<link href="https://unpkg.com/ionicons@4.1.2/dist/css/ionicons.min.css" rel="stylesheet">--}}
{{--<!-- iCheck -->--}}
{{--<link rel="stylesheet" href="{{asset('plugins/iCheck/flat/blue.css')}}">--}}
{{--<!-- select2 -->--}}
{{--<link rel="stylesheet" href="{{asset('plugins/select2/select2.min.css')}}">--}}
<!-- Morris chart -->
{{--<link rel="stylesheet" href="{{asset('plugins/morris/morris.css')}}">--}}
<!-- jvectormap -->
{{--<link rel="stylesheet" href="{{asset('plugins/jvectormap/jquery-jvectormap-1.2.2.css')}}">--}}
<!-- Date Picker -->
<link rel="stylesheet" href="{{asset('plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css')}}">
<!-- Daterange picker -->
{{--<link rel="stylesheet" href="{{asset('plugins/daterangepicker/daterangepicker-bs3.css')}}">--}}
{{--<!-- bootstrap wysihtml5 - text editor -->--}}
{{--<link rel="stylesheet" href="{{asset('plugins/summernote/summernote-bs4.css')}}">--}}


@stack('css_lib')
<!-- Theme style -->
    <link rel="stylesheet" href="{{asset('dist/css/adminlte.css')}}">
    <link rel="stylesheet" href="{{asset('plugins/bootstrap-sweetalert/sweetalert.css')}}">
    {{--<!-- Bootstrap -->--}}
    {{--<link rel="stylesheet" href="{{asset('plugins/bootstrap/css/bootstrap.min.css')}}">--}}

    <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,600" rel="stylesheet">
    <link rel="stylesheet" href="{{asset('css/custom.css')}}">
    <link rel="stylesheet" href="{{asset('css/'.setting("theme_color","primary").'.css')}}">
    @yield('css_custom')
    
    <!-- Date Time Picker Style -->
     <link rel="stylesheet" href="{{asset('plugins/datetimepicker/css/bootstrap-datetimepicker.min.css')}}">
    <!-- Date Range Picker Style -->
    <link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.css" />
    <!-- Date Range Picker Style -->
    <!-- Toast Alert -->
    <link rel="stylesheet" type="text/css" href="https://unpkg.com/izitoast/dist/css/iziToast.min.css">
    <!-- Toast Alert -->
    <script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>

    <link href="https://cdn.jsdelivr.net/bootstrap.timepicker/0.2.6/css/bootstrap-timepicker.min.css" rel="stylesheet" />

</head>

<body style="height: 100%; background-color: #f9f9f9;" class="hold-transition sidebar-mini {{setting('theme_color')}}">
    <input type="hidden" name="_base_url" value="{{ url('') }}" />
    <input type="hidden" name="_route" value="{{ \Request::route()->getName() }}" />
@if(auth()->check())
    <div class="wrapper">
        <!-- Main Header -->
        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand {{setting('fixed_header','')}} {{setting('nav_color','navbar-light bg-white')}} border-bottom">
            <!-- Left navbar links -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link new_model" data-widget="pushmenu" href="#"><i class="fa fa-bars fa-2x"></i></a>
                </li>
                <!--<li class="nav-item d-none d-sm-inline-block">
                    <a href="{{url('dashboard')}}" class="nav-link">{{trans('lang.dashboard')}}</a>
                </li>-->
            </ul>

            <!-- Right navbar links -->
            <ul class="navbar-nav ml-auto">
                @if(env('APP_CONSTRUCTION',false))
                    <li class="nav-item">
                        <a class="nav-link text-danger" href="#"><i class="fa fa-info-circle"></i>
                            {{env('APP_CONSTRUCTION','') }}</a>
                    </li>
                @endif
                @can('carts.index')
                    <li class="nav-item">
                        <a class="nav-link new_model bag {{ Request::is('carts*') ? 'active' : '' }}" href="{!! route('carts.index') !!}"><i class="fa fa-opencart fa-lg"></i></a>
                    </li>
                @endcan
                <li class="nav-item">
                    <a class="nav-link new_model bell {{ Request::is('notifications*') ? 'active' : '' }}" href="{!! route('notifications.index') !!}">
                        <i class="fa fa-bell-o fa-lg"></i>
                    </a>
                </li>
                <li class="nav-item dropdown">
                    <!--<a class="nav-link" data-toggle="dropdown" href="#">
                        <img src="{{auth()->user()->getFirstMediaUrl('avatar','icon')}}" class="brand-image mx-2 img-circle elevation-2" alt="User Image">
                        <i class="fa fa fa-angle-down"></i> {!! auth()->user()->name !!}

                    </a>-->
					<a class="nav-link new_model user position-relative" data-toggle="dropdown" href="#">
                        <!--<img src="{{auth()->user()->getFirstMediaUrl('avatar','icon')}}" class="brand-image mx-2 img-circle elevation-2" alt="User Image">-->
						<i class="fa fa-user-o fa-lg"></i>
						<i class="fa fa-angle-down position-absolute"></i>	

                    </a>
					
                    <div class="dropdown-menu dropdown-menu-right">
						<div class="drop-heading"> 
							<div class="text-center"> 
								<h5 class="text-dark mb-0">{!! auth()->user()->name !!}</h5> <!--<small class="text-muted">Administrator</small> -->
							</div> 
						</div>
						<div class="dropdown-divider"></div>
                        <a href="{{route('users.profile')}}" class="dropdown-item"> <i class="fa fa-user-o mr-2"></i> {{trans('lang.user_profile')}} </a>
                        <div class="dropdown-divider"></div>
                        <a href="{!! url('/logout') !!}" class="dropdown-item" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="fa fa-envelope-o mr-2"></i> {{__('auth.logout')}}
                        </a>
                        <form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;">
                            {{ csrf_field() }}
                        </form>
                    </div>
                </li>
            </ul>
        </nav>

        <!-- Left side column. contains the logo and sidebar -->
    @include('layouts.sidebar')
    <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            @yield('content')
        </div>

        <!-- Main Footer -->
        <footer class="main-footer {{setting('fixed_footer','')}}">
            <div class="float-right d-none d-sm-block">
                <b>Version</b> {{implode('.',str_split(substr(config('installer.currentVersion','v100'),1,3)))}}
            </div>
            <strong>Copyright © {{date('Y')}} <a href="{{url('/')}}">{{setting('app_name')}}</a>.</strong> All rights reserved.
        </footer>

    </div>
@else
    <nav class="nmain-header navbar navbar-expand {{setting('nav_color','navbar-light bg-white')}} border-bottom">
        <div class="container">
            <!-- Left navbar links -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="{!! url('/') !!}">{{setting('app_name')}}</a>
                </li>
                @include('layouts.menu',['icons'=>false])
            </ul>

            <!-- Right navbar links -->
            <ul class="navbar-nav ml-auto">
                <li class="nav-item dropdown">
                    <a class="nav-link" data-toggle="dropdown" href="#">
                        {!! Auth::user()->name !!}
                    </a>
                    <div class="dropdown-menu dropdown-menu-right">
                        <a href="{{route('users.profile')}}" class="dropdown-item"> <i class="fa fa-user mr-2"></i> Profile </a>
                        <div class="dropdown-divider"></div>
                        <a href="{!! url('/logout') !!}" class="dropdown-item" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="fa fa-envelope mr-2"></i> {{__('auth.logout')}}
                        </a>
                        <form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;">
                            {{ csrf_field() }}
                        </form>
                    </div>
                </li>
            </ul>
        </div>
    </nav>

    <div id="page-content-wrapper">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    @yield('content')
                </div>
            </div>
            <!-- Main Footer -->
            <footer class="{{setting('fixed_footer','')}}">
                <div class="float-right d-none d-sm-block">
                    <b>Version</b> {{implode('.',str_split(substr(config('installer.currentVersion','v100'),1,3)))}}
                </div>
                <strong>Copyright © {{date('Y')}} <a href="{{url('/')}}">{{setting('app_name')}}</a>.</strong> All rights reserved.
            </footer>
        </div>
    </div>

    @endrole


    <!-- jQuery -->
    <script src="{{asset('plugins/jquery/jquery.min.js')}}"></script>
    <!-- jQuery UI 1.11.4 -->
    {{--<script src="{{asset('https://code.jquery.com/ui/1.12.1/jquery-ui.min.js')}}"></script>--}}
    <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
    {{--<script>--}}
    {{--$.widget.bridge('uibutton', $.ui.button)--}}
    {{--</script>--}}
    <!-- Bootstrap 4 -->
    <script src="{{asset('plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>

    <!-- The core Firebase JS SDK is always required and must be listed first -->
    <script src="{{asset('https://www.gstatic.com/firebasejs/7.2.0/firebase-app.js')}}"></script>

    <script src="{{asset('https://www.gstatic.com/firebasejs/7.2.0/firebase-messaging.js')}}"></script>

    <script type="text/javascript">@include('vendor.notifications.init_firebase')</script>

    <script type="text/javascript">
        const messaging = firebase.messaging();
        navigator.serviceWorker.register("{{url('firebase/sw-js')}}")
            .then((registration) => {
                messaging.useServiceWorker(registration);
                messaging.requestPermission()
                    .then(function() {
                        console.log('Notification permission granted.');
                        getRegToken();

                    })
                    .catch(function(err) {
                        console.log('Unable to get permission to notify.', err);
                    });
                messaging.onMessage(function(payload) {
                    console.log("Message received. ", payload);
                    notificationTitle = payload.data.title;
                    notificationOptions = {
                        body: payload.data.body,
                        icon: payload.data.icon,
                        image:  payload.data.image
                    };
                    var notification = new Notification(notificationTitle,notificationOptions);
                });
            });

        function getRegToken(argument) {
            messaging.getToken().then(function(currentToken) {
                if (currentToken) {
                    saveToken(currentToken);
                    console.log(currentToken);
                } else {
                    console.log('No Instance ID token available. Request permission to generate one.');
                }
            })
                .catch(function(err) {
                    console.log('An error occurred while retrieving token. ', err);
                });
        }


        function saveToken(currentToken) {
            $.ajax({
                type: "POST",
                data: {'device_token': currentToken, 'api_token': '{!! auth()->user()->api_token !!}'},
                url: '{!! url('api/users',['id'=>auth()->id()]) !!}',
                success: function (data) {

                },
                error: function (err) {
                    console.log(err);
                }
            });
        }
    </script>

    <!-- Sparkline -->
    {{--<script src="{{asset('plugins/sparkline/jquery.sparkline.min.js')}}"></script>--}}
    {{--<!-- iCheck -->--}}
    {{--<script src="{{asset('plugins/iCheck/icheck.min.js')}}"></script>--}}
    {{--<!-- select2 -->--}}
    {{--<script src="{{asset('plugins/select2/select2.min.js')}}"></script>--}}
    <!-- jQuery Knob Chart -->
    {{--<script src="{{asset('plugins/knob/jquery.knob.js')}}"></script>--}}
    <!-- daterangepicker -->
    {{--<script src="{{asset('https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.10.2/moment.min.js')}}"></script>--}}
    {{--<script src="{{asset('plugins/daterangepicker/daterangepicker.js')}}"></script>--}}
    <!-- datepicker -->
    <script src="{{asset('plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js')}}"></script>
    <!-- Bootstrap WYSIHTML5 -->
    {{--<script src="{{asset('plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js')}}"></script>--}}
    <!-- Slimscroll -->
    <script src="{{asset('plugins/slimScroll/jquery.slimscroll.min.js')}}"></script>
    <script src="{{asset('plugins/bootstrap-sweetalert/sweetalert.min.js')}}"></script>
    <!-- FastClick -->
    {{--<script src="{{asset('plugins/fastclick/fastclick.js')}}"></script>--}}
    <!-- Date Time Picker -->
    <script src="{{asset('plugins/datetimepicker/bootstrap-datetimepicker.min.js')}}"></script>
    
    @stack('scripts_lib')
    <!-- AdminLTE App -->
    <script src="{{asset('dist/js/adminlte.js')}}"></script>
    {{--<!-- AdminLTE dashboard demo (This is only for demo purposes) -->--}}
    {{--<script src="{{asset('plugins/summernote/summernote-bs4.min.js')}}"></script>--}}
    <!-- AdminLTE for demo purposes -->
    <script src="{{asset('dist/js/demo.js')}}"></script>

    <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery.validation/1.15.0/jquery.validate.min.js"></script>
    <script src="{{asset('js/main.js')}}"></script>

    <!--Print Invoice-->
    <script src="{{asset('js/jquery.printPage.js')}}"></script>
    <!--<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBO_gULxLbNl0_4Bo-SBCjszpdb_6Ig6QM&libraries=places&callback=initAutocomplete" async defer></script>-->
    <script src="{{asset('js/scripts.js')}}"></script>
    
    @stack('scripts')

    <script src="https://cdn.jsdelivr.net/bootstrap.timepicker/0.2.6/js/bootstrap-timepicker.min.js"></script>

    <!-- Date Range Picker Script -->
    <!-- Include Required Prerequisites -->
    <script type="text/javascript" src="//cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>   
    <!-- Include Date Range Picker -->
    <script type="text/javascript" src="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.js"></script>
    <script>

        var start = moment().subtract(29, 'days');
        var end = moment();
        
        $('#reportrange').daterangepicker({
            startDate: start,
            endDate: end,
            ranges: {
               'Today': [moment(), moment()],
               'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
               'Last 7 Days': [moment().subtract(6, 'days'), moment()],
               'Last 30 Days': [moment().subtract(29, 'days'), moment()],
               'This Month': [moment().startOf('month'), moment().endOf('month')],
               'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
            }
        }, cb);

        $('#reportrange1').daterangepicker({
            startDate: start,
            endDate: end,
            ranges: {
               'Today': [moment(), moment()],
               'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
               'Last 7 Days': [moment().subtract(6, 'days'), moment()],
               'Last 30 Days': [moment().subtract(29, 'days'), moment()],
               'This Month': [moment().startOf('month'), moment().endOf('month')],
               'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
            }
        }, cb1);
        
          $('#reportrange2').daterangepicker({
            startDate: start,
            endDate: end,
            ranges: {
               'Today': [moment(), moment()],
               'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
               'Last 7 Days': [moment().subtract(6, 'days'), moment()],
               'Last 30 Days': [moment().subtract(29, 'days'), moment()],
               'This Month': [moment().startOf('month'), moment().endOf('month')],
               'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
            }
        }, cb2);

        cb(start, end);
        cb1(start, end);
        cb2(start, end);

        function confrimDelete() {
           iziToast.question({
                timeout: 20000,
                close: false,
                overlay: true,
                displayMode: 'once',
                id: 'question',
                zindex: 999,
                title: 'Hey',
                message: 'Are you sure about that?',
                position: 'center',
                buttons: [
                    ['<button><b>YES</b></button>', function (instance, toast) {
             
                        instance.hide({ transitionOut: 'fadeOut' }, toast, 'button');
                        return true;
             
                    }, true],
                    ['<button>NO</button>', function (instance, toast) {
             
                        instance.hide({ transitionOut: 'fadeOut' }, toast, 'button');
                        return true;
             
                    }],
                ],
                onClosing: function(instance, toast, closedBy){
                    console.info('Closing | closedBy: ' + closedBy);
                    return true;
                },
                onClosed: function(instance, toast, closedBy){
                    console.info('Closed | closedBy: ' + closedBy);
                    return true;
                }
            });
           
      }


        function deleteItemLoad(elem) {

            var id  = $(elem).data('id');
            var url = $(elem).data('url');

            iziToast.show({
                theme: 'dark',
                icon: 'fa fa-trash',
                overlay: true,
                title: 'Delete',
                message: 'Are you sure?',
                position: 'center', // bottomRight, bottomLeft, topRight, topLeft, topCenter, bottomCenter
                progressBarColor: 'yellow',
                backgroundColor: 'linear-gradient(to right, #ff4b1f, #ff9068)', 
                messageColor: '#fff', 
                buttons: [
                    ['<button>Yes</button>', function (instance, toast) {
                       
                        var token = "{{ csrf_token() }}";
                        $.ajax({
                            type: 'POST',
                            url: url,
                            data: {'_token': token, 'id': id, '_method': 'DELETE'},
                            success: function (response) {
                                iziToast.success({
                                    backgroundColor: 'linear-gradient(to right, #44a08d, #093637)',
                                    messageColor: '#fff',
                                    timeout: 3000, 
                                    icon: 'fa fa-check', 
                                    position: "topRight", 
                                    iconColor:'#fff',
                                    message: 'Deleted Sucessfully'
                                });
                              instance.hide({transitionOut: 'fadeOutUp'}, toast, 'buttonName');  
                              setTimeout(function () { location.reload(); }, 1000);
                            }
                        }); 

                    }, true], // true to focus
                    ['<button>No</button>', function (instance, toast) {
                        instance.hide({
                            transitionOut: 'fadeOutUp',
                            onClosing: function(instance, toast, closedBy){
                                console.info('closedBy: ' + closedBy); // The return will be: 'closedBy: buttonName'
                            }
                        }, toast, 'buttonName');
                    }]
                ]
            });

        }

    </script>
    <!-- Date Range Picker Script -->
    <!-- Toast Alert -->
    <script src="https://unpkg.com/izitoast/dist/js/iziToast.min.js"></script>
    <!-- Toast Alert -->
</body>
</html>