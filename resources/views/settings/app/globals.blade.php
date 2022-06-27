@extends('layouts.settings.default')
@push('css_lib')
    <!-- iCheck -->
    <link rel="stylesheet" href="{{asset('plugins/iCheck/flat/blue.css')}}">
    <!-- select2 -->
    <link rel="stylesheet" href="{{asset('plugins/select2/select2.min.css')}}">
    <!-- bootstrap wysihtml5 - text editor -->
    <link rel="stylesheet" href="{{asset('plugins/summernote/summernote-bs4.css')}}">
    {{--dropzone--}}
    <link rel="stylesheet" href="{{asset('plugins/dropzone/bootstrap.min.css')}}">
@endpush
@section('settings_title',trans('lang.user_table'))
@section('settings_content')
    @include('flash::message')
    @include('adminlte-templates::common.errors')
    <div class="clearfix"></div>
    <div class="card">
        <div class="card-header">
            <ul class="nav nav-tabs align-items-end card-header-tabs w-100">
                <li class="nav-item">
                    <a class="nav-link active" href="{!! url()->current() !!}"><i class="fa fa-cog mr-2"></i>{{trans('lang.app_setting_'.$tab)}}</a>
                </li>
                @if(!env('APP_DEMO',false))
                    <div class="ml-auto d-inline-flex">
                        <li class="nav-item">
                            <a class="nav-link pt-1" href="{{url('settings/clear-cache')}}"><i class="fa fa-trash-o"></i> {{trans('lang.app_setting_clear_cache')}}
                            </a>
                        </li>
                        @if($containsUpdate)
                            <li class="nav-item">
                                <a class="nav-link pt-1" href="{{url('update/'.config('installer.currentVersion','v100'))}}"><i class="fa fa-refresh"></i> {{trans('lang.app_setting_check_for')}}
                                </a>
                            </li>
                        @endif
                    </div>
                @endif
            </ul>
        </div>
        <div class="card-body">
            {!! Form::open(['url' => ['settings/update'], 'method' => 'patch']) !!}
            <div class="row">

                <div class="col-md-12 column custom-from-css">
                    <div class="row">

                    <!-- app_name Field -->
                    <div class="form-group col-md-6">
                        {!! Form::label('app_name', trans("lang.app_setting_app_name"), ['class' => ' control-label text-left']) !!}
                        {!! Form::text('app_name', setting('app_name'),  ['class' => 'form-control','placeholder'=>  trans("lang.app_setting_app_name_placeholder")]) !!}
                        <!-- <div class="form-text text-muted">
                            {{ trans("lang.app_setting_app_name_help") }}
                        </div> -->
                    </div>

                    <!-- app_short_description Field -->
                    <div class="form-group col-md-6">
                        {!! Form::label('app_short_description', trans("lang.app_setting_app_short_description"), ['class' => ' control-label text-left']) !!}
                        {!! Form::text('app_short_description', setting('app_short_description'),  ['class' => 'form-control','placeholder'=>  trans("lang.app_setting_app_short_description_placeholder")]) !!}
                        <!-- <div class="form-text text-muted">
                            {{ trans("lang.app_setting_app_short_description_help") }}
                        </div> -->
                    </div>

                    <!-- Theme Contrast Field -->
                    <div class="form-group col-md-6">
                        {!! Form::label('theme_contrast', trans("lang.app_setting_theme_contrast"),['class' => ' control-label text-left']) !!}
                        {!! Form::select('theme_contrast', 
                        [
                        'dark' => trans('lang.app_setting_dark_theme'),
                        'light' => trans('lang.app_setting_light_theme'),
                        ]
                        , setting('theme_contrast'), ['class' => 'select2 form-control']) !!}
                        <!-- <div class="form-text text-muted">{{ trans("lang.app_setting_theme_contrast_help") }}</div> -->
                    </div>

                    <!-- Theme Color Field -->
                    <div class="form-group col-md-6">
                        {!! Form::label('theme_color', trans("lang.app_setting_theme_color"),['class' => ' control-label text-left']) !!}
                        {!! Form::select('theme_color', 
                        [
                        'primary' => trans('lang.app_setting_blue'),
                        'secondary' => trans('lang.app_setting_gray'),
                        'danger' => trans('lang.app_setting_red'),
                        'warning' => trans('lang.app_setting_orange'),
                        'info' => trans('lang.app_setting_sky_blue'),
                        'success' => trans('lang.app_setting_green'),
                        ]
                        , setting('theme_color'), ['class' => 'select2 form-control']) !!}
                        <!-- <div class="form-text text-muted">{{ trans("lang.app_setting_theme_color_help") }}</div> -->
                    </div>

                    <!-- Navbar Color Field -->
                    <div class="form-group col-md-6">
                        {!! Form::label('nav_color', trans("lang.app_setting_nav_color"),['class' => ' control-label text-left']) !!}
                        {!! Form::select('nav_color',
                        [
                        'navbar-dark bg-primary' => trans('lang.app_setting_blue'),
                        'navbar-light bg-gray-light' => trans('lang.app_setting_gray'),
                        'navbar-light bg-dark' => trans('lang.app_setting_dark'),
                        'navbar-light bg-white' => trans('lang.app_setting_white'),
                        'navbar-dark bg-danger' => trans('lang.app_setting_red'),
                        'navbar-light bg-warning' => trans('lang.app_setting_orange'),
                        'navbar-dark bg-info' => trans('lang.app_setting_sky_blue'),
                        'navbar-dark bg-success' => trans('lang.app_setting_green'),

                        ]
                        , setting('nav_color'), ['class' => 'select2 form-control']) !!}
                        <!-- <div class="form-text text-muted">{{ trans("lang.app_setting_nav_color_help") }}</div> -->
                    </div>


                    <!-- logo_bg Color Field -->
                    <div class="form-group col-md-6">
                        {!! Form::label('logo_bg_color', trans("lang.app_setting_logo_bg_color"),['class' => ' control-label text-left']) !!}
                        {!! Form::select('logo_bg_color',
                        [
                        '' => trans('lang.app_setting_clear'),
                        'bg-primary' => trans('lang.app_setting_blue'),
                        'bg-gray-light' => trans('lang.app_setting_gray'),
                        'bg-dark' => trans('lang.app_setting_dark'),
                        'bg-white' => trans('lang.app_setting_white'),
                        'bg-danger' => trans('lang.app_setting_red'),
                        'bg-warning' => trans('lang.app_setting_orange'),
                        'bg-info' => trans('lang.app_setting_sky_blue'),
                        'bg-success' => trans('lang.app_setting_green'),
                        ]
                        , setting('logo_bg_color'), ['class' => 'select2 form-control']) !!}
                        <!-- <div class="form-text text-muted">{{ trans("lang.app_setting_logo_bg_color_help") }}</div> -->
                    </div>
                    
                    <!-- custom_field_models Field -->
                    <div class="form-group col-md-6">
                        {!! Form::label('custom_field_models[]', trans("lang.app_setting_custom_field_models"),['class' => ' control-label text-left']) !!}
                        {!! Form::select('custom_field_models[]',$customFieldModels
                        , setting('custom_field_models',[]), ['multiple'=>'multiple', 'class' => 'select2 form-control']) !!}
                        <!-- <div class="form-text text-muted">{{ trans("lang.app_setting_custom_field_models_help") }}</div> -->
                    </div>

                    <!-- blocked_ips Field -->
                    <div class="form-group col-md-6">
                        {!! Form::label('blocked_ips[]', trans("lang.app_setting_blocked_ips"),['class' => ' control-label text-left']) !!}
                        {!! Form::select('blocked_ips[]',array_combine(setting('blocked_ips',[]),setting('blocked_ips',[])), setting('blocked_ips',[]), ['multiple'=>'multiple', 'data-tags'=>'true','class' => 'select2 form-control']) !!}
                        <!-- <div class="form-text text-muted">{{ trans("lang.app_setting_blocked_ips_help") }}</div> -->
                    </div>

                    <!-- app_invoice_prefix Field -->
                    <div class="form-group col-md-6">
                        {!! Form::label('app_invoice_prefix', trans("lang.app_setting_app_invoice_prefix"), ['class' => ' control-label text-left']) !!}
                        {!! Form::text('app_invoice_prefix', setting('app_invoice_prefix'),  ['class' => 'form-control','placeholder'=>  trans("lang.app_setting_app_invoice_prefix_placeholder")]) !!}
                        <!-- <div class="form-text text-muted">
                            {{ trans("lang.app_setting_app_invoice_prefix_help") }}
                        </div> -->
                    </div>
                    
                    <!-- app_store_latitude Field -->
                    <div class="form-group col-md-6">
                        {!! Form::label('app_store_latitude', "Store Latitude", ['class' => 'control-label text-right']) !!}
                        <div class="input-group-append">
                            {!! Form::text('app_store_latitude', setting('app_store_latitude'),  ['class' => 'form-control','placeholder'=>"Please enter the Store Lattitude"]) !!}
                            <span class="input-group-text" style="margin-left: -5px;border: none;background: #e3e9ec;border-left: 2px solid #d6d5d5;backface-visibility: visible;z-index: 1;border-radius: 0px 8px 8px 0px;"> 
                                <a data-toggle="modal" href="#myModal"><i class="fa fa-map-marker mr-1"></i></a>
                            </span>
                        </div>
                    </div>
                    
                    <!-- app_store_longitude Field -->
                    <div class="form-group col-md-6">
                        {!! Form::label('app_store_longitude', "Store Latitude", ['class' => 'control-label text-right']) !!}
                        <div class="input-group-append">
                            {!! Form::text('app_store_longitude', setting('app_store_longitude'),  ['class' => 'form-control','placeholder'=>"Please enter the Store Longitude"]) !!}
                            <span class="input-group-text" style="margin-left: -5px;border: none;background: #e3e9ec;border-left: 2px solid #d6d5d5;backface-visibility: visible;z-index: 1;border-radius: 0px 8px 8px 0px;"> 
                                <a data-toggle="modal" href="#myModal"><i class="fa fa-map-marker mr-1"></i></a>
                            </span>
                        </div>
                    </div>
                    
                    <?php /* ?>
                    
                    <!-- app_store_latitude Field -->
                    <div class="form-group col-md-6">
                        {!! Form::label('app_store_latitude', trans("lang.app_store_latitude"), ['class' => ' control-label text-left']) !!}
                        {!! Form::text('app_store_latitude', setting('app_store_latitude'),  ['class' => 'form-control','placeholder'=>  trans("lang.app_store_latitude_placeholder")]) !!}
                        <!-- <div class="form-text text-muted">
                            {{ trans("lang.app_store_latitude_help") }}
                        </div> -->
                    </div>
                    
                    <!-- app_store_longitude Field -->
                    <div class="form-group col-md-6">
                        {!! Form::label('app_store_longitude', trans("lang.app_store_longitude"), ['class' => ' control-label text-left']) !!}
                        {!! Form::text('app_store_longitude', setting('app_store_longitude'),  ['class' => 'form-control','placeholder'=>  trans("lang.app_store_longitude_placeholder")]) !!}
                        <!-- <div class="form-text text-muted">
                            {{ trans("lang.app_store_longitude_help") }}
                        </div> -->
                    </div>
                    
                    <?php /*/ ?>

                    <div class="form-group col-md-6">&nbsp;</div>

                    <!-- App Logo Field -->
                    <div class="form-group col-md-6">
                        {!! Form::label('app_logo', trans("lang.upload_app_logo"), ['class' => ' control-label text-left']) !!}
                        <div style="width: 100%" class="dropzone app_logo" id="app_logo" data-field="app_logo">
                            <input type="hidden" name="app_logo">
                        </div>
                        <a href="#loadMediaModal" data-dropzone="app_logo" data-toggle="modal" data-target="#mediaModal" class="btn btn-outline-{{setting('theme_color')}} btn-sm float-right mt-1">{{ trans('lang.media_select')}}</a>
                        <div class="form-text text-muted w-50">
                            {{ trans("lang.upload_app_logo_help") }}
                        </div>
                    </div>
                    @prepend('scripts')
                    <script type="text/javascript">
                        var dzAppLogo = '';
                        @if(isset($upload) && $upload->hasMedia('app_logo'))
                            dzAppLogo = {
                            name: "{!! $upload->getFirstMedia('app_logo')->name !!}",
                            size: "{!! $upload->getFirstMedia('app_logo')->size !!}",
                            type: "{!! $upload->getFirstMedia('app_logo')->mime_type !!}",
                            collection_name: "{!! $upload->getFirstMedia('app_logo')->collection_name !!}"
                        };
                                @endif
                        var dz_dzAppLogo = $(".dropzone.app_logo").dropzone({
                                url: "{!!url('uploads/store')!!}",
                                addRemoveLinks: true,
                                maxFiles: 1,
                                init: function () {
                                    @if(isset($upload) && $upload->hasMedia('app_logo'))
                                    dzInit(this, dzAppLogo, '{!! url($upload->getFirstMediaUrl('app_logo','thumb')) !!}')
                                    @endif
                                },
                                accept: function (file, done) {
                                    dzAccept(file, done, this.element, "{!!config('medialibrary.icons_folder')!!}");
                                },
                                sending: function (file, xhr, formData) {
                                    dzSending(this, file, formData, '{!! csrf_token() !!}');
                                },
                                maxfilesexceeded: function (file) {
                                    dz_dzAppLogo[0].mockFile = '';
                                    dzMaxfile(this, file);
                                },
                                complete: function (file) {
                                    dzComplete(this, file, dzAppLogo, dz_dzAppLogo[0].mockFile);
                                    dz_dzAppLogo[0].mockFile = file;
                                },
                                removedfile: function (file) {
                                    dzRemoveFile(
                                        file, dzAppLogo, '{!! url("uploads/remove-media") !!}',
                                        'app_logo', '{!! isset($upload) ? $upload->id : 0 !!}', '{!! url("uplaods/clear") !!}', '{!! csrf_token() !!}'
                                    );
                                }
                            });
                        dz_dzAppLogo[0].mockFile = dzAppLogo;
                        dropzoneFields['app_logo'] = dz_dzAppLogo;
                    </script>
                    @endprepend

                    <!-- App Signature Field -->
                    <div class="form-group col-md-6">
                        {!! Form::label('app_invoice_signature', trans("lang.upload_app_invoice_signature"), ['class' => ' control-label text-left']) !!}
                        <div style="width: 100%" class="dropzone app_invoice_signature" id="app_invoice_signature" data-field="app_invoice_signature">
                            <input type="hidden" name="app_invoice_signature">
                        </div>
                        <a href="#loadMediaModal" data-dropzone="app_invoice_signature" data-toggle="modal" data-target="#mediaModal" class="btn btn-outline-{{setting('theme_color')}} btn-sm float-right mt-1">{{ trans('lang.media_select')}}</a>
                        <div class="form-text text-muted w-50">
                            {{ trans("lang.upload_app_invoice_signature_help") }}
                        </div>
                    </div>
                    @prepend('scripts')
                    <script type="text/javascript">
                        var dzAppLogo = '';
                        @if(isset($signature) && $signature->hasMedia('app_invoice_signature'))
                            dzAppLogo = {
                            name: "{!! $signature->getFirstMedia('app_invoice_signature')->name !!}",
                            size: "{!! $signature->getFirstMedia('app_invoice_signature')->size !!}",
                            type: "{!! $signature->getFirstMedia('app_invoice_signature')->mime_type !!}",
                            collection_name: "{!! $signature->getFirstMedia('app_invoice_signature')->collection_name !!}"
                        };
                                @endif
                        var dz_dzAppLogo = $(".dropzone.app_invoice_signature").dropzone({
                                url: "{!!url('uploads/store')!!}",
                                addRemoveLinks: true,
                                maxFiles: 1,
                                init: function () {
                                    @if(isset($signature) && $signature->hasMedia('app_invoice_signature'))
                                    dzInit(this, dzAppLogo, '{!! url($signature->getFirstMediaUrl('app_invoice_signature','thumb')) !!}')
                                    @endif
                                },
                                accept: function (file, done) {
                                    dzAccept(file, done, this.element, "{!!config('medialibrary.icons_folder')!!}");
                                },
                                sending: function (file, xhr, formData) {
                                    dzSending(this, file, formData, '{!! csrf_token() !!}');
                                },
                                maxfilesexceeded: function (file) {
                                    dz_dzAppLogo[0].mockFile = '';
                                    dzMaxfile(this, file);
                                },
                                complete: function (file) {
                                    dzComplete(this, file, dzAppLogo, dz_dzAppLogo[0].mockFile);
                                    dz_dzAppLogo[0].mockFile = file;
                                },
                                removedfile: function (file) {
                                    dzRemoveFile(
                                        file, dzAppLogo, '{!! url("uploads/remove-media") !!}',
                                        'app_invoice_signature', '{!! isset($signature) ? $signature->id : 0 !!}', '{!! url("uplaods/clear") !!}', '{!! csrf_token() !!}'
                                    );
                                }
                            });
                        dz_dzAppLogo[0].mockFile = dzAppLogo;
                        dropzoneFields['app_invoice_signature'] = dz_dzAppLogo;
                    </script>
                    @endprepend

                    <!-- Terms & Conditions Field -->
                    <div class="form-group col-md-6">
                      {!! Form::label('app_invoice_terms_and_conditions', trans("lang.app_setting_terms_and_conditions"), ['class' => ' control-label text-left']) !!}
                        {!! Form::textarea('app_invoice_terms_and_conditions', setting('app_invoice_terms_and_conditions'), ['class' => 'form-control','placeholder'=>
                         trans("lang.app_setting_terms_and_conditions_placeholder")  ]) !!}
                        <!-- <div class="form-text text-muted">{{ trans("lang.app_setting_terms_and_conditions_help") }}</div> -->
                    </div>

                    <div class="col-md-6">

                        <!-- fixed_header Field -->
                        <div class="form-group row checkbox-cust1">
                            {!! Form::label('fixed_header', trans("lang.app_setting_fixed_header"),['class' => 'col-md-3 control-label text-left']) !!}
                            <div class="col-md-9 checkbox icheck">
                                <label class="w-100 ml-2 form-check-inline">
                                    {!! Form::hidden('fixed_header', null) !!}
                                    {!! Form::checkbox('fixed_header', 'fixed-top', setting('fixed_header')) !!}
                                    <span class="ml-2">{!! trans("lang.app_setting_fixed_header_help") !!}</span> </label>
                            </div>
                        </div>

                        <!-- fixed_footer Field -->
                        <div class="form-group row ">
                            {!! Form::label('fixed_footer', trans("lang.app_setting_fixed_footer"),['class' => 'col-md-3 control-label text-left']) !!}
                            <div class="col-md-9 checkbox icheck">
                                <label class="w-100 ml-2 form-check-inline">
                                    {!! Form::hidden('fixed_footer', null) !!}
                                    {!! Form::checkbox('fixed_footer', 'fixed-bottom', setting('fixed_footer')) !!}
                                    <span class="ml-2">{!! trans("lang.app_setting_fixed_footer_help") !!}</span> </label>
                            </div>
                        </div>
                        
                    </div>

                </div>
            </div>


                <!-- Submit Field -->
                <div class="form-group mt-4 col-12 text-right">
                    <button type="submit" class="btn btn-{{setting('theme_color')}}"><i class="fa fa-save"></i> {{trans('lang.save')}} {{trans('lang.app_setting_globals')}}
                    </button>
                    <a href="{!! route('users.index') !!}" class="btn btn-default"><i class="fa fa-undo"></i> {{trans('lang.cancel')}}</a>
                </div>
            </div>
            {!! Form::close() !!}
            
            <div class="modal" id="myModal">
                <div class="modal-dialog">
                    <div class="modal-content">
                       <div class="modal-header">
                          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                       </div>
                       <div class="modal-body">
                          <div id="us2" style="height: 400px;"></div>
                             <div class="row mt-3">
                                <div class="col-md-6">
                                    <label>Latitude : </label> 
                                    <input type="text" class="form-control" id="us2-lat" />
                                </div>
                                <div class="col-md-6">
                                    <label>Longitude : </label> 
                                    <input type="text" class="form-control" id="us2-lon" />
                                </div>
                             </div>
                       </div>
                       <div class="modal-footer"> 
                          <a href="#" data-dismiss="modal" class="btn btn-secondary">Close</a>
                          <a href="#" class="btn btn-primary" id="save-changes">Save changes</a>
                       </div>
                    </div>
                </div>
            </div>
            
            <div class="clearfix"></div>
        </div>
    </div>
    </div>
    @include('layouts.media_modal',['collection'=>'default'])
@endsection
@push('scripts_lib')
    <!-- iCheck -->
    <script src="{{asset('plugins/iCheck/icheck.min.js')}}"></script>
    <!-- select2 -->
    <script src="{{asset('plugins/select2/select2.min.js')}}"></script>
    <!-- AdminLTE dashboard demo (This is only for demo purposes) -->
    <script src="{{asset('plugins/summernote/summernote-bs4.min.js')}}"></script>
    {{--dropzone--}}
    <script src="{{asset('plugins/dropzone/dropzone.js')}}"></script>
    <script type="text/javascript">
        Dropzone.autoDiscover = false;
        var dropzoneFields = [];
    </script>
    
    <script src="https://maps.googleapis.com/maps/api/js?key={{ setting('google_api_key') }}&libraries=places&callback=initAutocomplete" async defer></script>
    <script>
      
    var stillPresent = false;
    var googleMapsLoaded = false;
    
    function initialize() {
        if (stillPresent == false) {
            $('#us2').locationpicker({
                location: {
                    latitude: $('#app_store_latitude').val(),
                    longitude: $('#app_store_longitude').val()
                },
                radius: 300,
                inputBinding: {
                    latitudeInput: $('#us2-lat'),
                    longitudeInput: $('#us2-lon'),
                    radiusInput: $('#us2-radius'),
                    locationNameInput: $('#us2-address')
                }
            });
            stillPresent = true;
            $("#save-changes").click(function() {
            $("#app_store_latitude").val($('#us2-lat').val());
            $("#app_store_longitude").val($('#us2-lon').val());        
            $('#myModal').modal('hide');
        });
        }
    }
    
    function loadScript() {
        if (!googleMapsLoaded) {
            var script = document.createElement('script');
            script.type = 'text/javascript';
            script.src = "https://maps.google.com/maps/api/js?key={{ setting('google_api_key') }}&sensor=false&libraries=places&callback=initialize";
            document.body.appendChild(script);
            googleMapsLoaded = true;
        }
    }
    
    $(function() {
        $('#myModal').on('shown.bs.modal', function (e) {
            loadScript();
            var map = $('#us2').locationpicker('map').map;
            var currentCenter = map.getCenter();
            google.maps.event.trigger(map, "resize");
            map.setCenter(currentCenter); 
        });
    });
    
    // JQuery locationpicker plugin included below:
    
    (function ( $ ) {
    
        /**
         * Holds google map object and related utility entities.
         * @constructor
         */
        function GMapContext(domElement, options) {
            var _map = new google.maps.Map(domElement, options);
            var _marker = new google.maps.Marker({
                position: new google.maps.LatLng(54.19335, -3.92695),
                map: _map,
                title: "Drag Me",
                draggable: options.draggable
            });
            return {
                map: _map,
                marker: _marker,
                circle: null,
                location: _marker.position,
                radius: options.radius,
                locationName: options.locationName,
                addressComponents: {
                    formatted_address: null,
                    addressLine1: null,
                    addressLine2: null,
                    streetName: null,
                    streetNumber: null,
                    city: null,
                    state: null,
                    stateOrProvince: null
                },
                settings: options.settings,
                domContainer: domElement,
                geodecoder: new google.maps.Geocoder()
            }
        }
    
        // Utility functions for Google Map Manipulations
        var GmUtility = {
            /**
             * Draw a circle over the the map. Returns circle object.
             * Also writes new circle object in gmapContext.
             *
             * @param center - LatLng of the center of the circle
             * @param radius - radius in meters
             * @param gmapContext - context
             * @param options
             */
            drawCircle: function(gmapContext, center, radius, options) {
                if (gmapContext.circle != null) {
                    gmapContext.circle.setMap(null);
                }
                if (radius > 0) {
                    radius *= 1;
                    options = $.extend({
                        strokeColor: "#0000FF",
                        strokeOpacity: 0.35,
                        strokeWeight: 2,
                        fillColor: "#0000FF",
                        fillOpacity: 0.20
                    }, options);
                    options.map = gmapContext.map;
                    options.radius = radius;
                    options.center = center;
                    gmapContext.circle = new google.maps.Circle(options);
                    return gmapContext.circle;
                }
                return null;
            },
            /**
             *
             * @param gMapContext
             * @param location
             * @param callback
             */
            setPosition: function(gMapContext, location, callback) {
                gMapContext.location = location;
                gMapContext.marker.setPosition(location);
                gMapContext.map.panTo(location);
                this.drawCircle(gMapContext, location, gMapContext.radius, {});
                if (gMapContext.settings.enableReverseGeocode) {
                    gMapContext.geodecoder.geocode({latLng: gMapContext.location}, function(results, status){
                        if (status == google.maps.GeocoderStatus.OK && results.length > 0){
                            gMapContext.locationName = results[0].formatted_address;
                            gMapContext.addressComponents =
                                GmUtility.address_component_from_google_geocode(results[0].address_components);
                        }
                        if (callback) {
                            callback.call(this, gMapContext);
                        }
                    });
                } else {
                    if (callback) {
                        callback.call(this, gMapContext);
                    }
                }
    
            },
            locationFromLatLng: function(lnlg) {
                return {latitude: lnlg.lat(), longitude: lnlg.lng()}
            },
            address_component_from_google_geocode: function(address_components) {
                var result = {};
                for (var i = address_components.length-1; i>=0; i--) {
                    var component = address_components[i];
                    // Postal code
                    if (component.types.indexOf('postal_code') >= 0) {
                        result.postalCode = component.short_name;
                    }
                    // Street number
                    else if (component.types.indexOf('street_number') >= 0) {
                        result.streetNumber = component.short_name;
                    }
                    // Street name
                    else if (component.types.indexOf('route') >= 0) {
                        result.streetName = component.short_name;
                    }
                    // City
                    else if (component.types.indexOf('sublocality') >= 0) {
                        result.city = component.short_name;
                    }
                    // State \ Province
                    else if (component.types.indexOf('administrative_area_level_1') >= 0) {
                        result.stateOrProvince = component.short_name;
                    }
                    // State \ Province
                    else if (component.types.indexOf('country') >= 0) {
                        result.country = component.short_name;
                    }
                }
                result.addressLine1 = [result.streetNumber, result.streetName].join(' ').trim();
                result.addressLine2 = '';
                return result;
            }
        };
    
        function isPluginApplied(domObj) {
            return getContextForElement(domObj) != undefined;
        }
    
        function getContextForElement(domObj) {
            return $(domObj).data("locationpicker");
        }
    
        function updateInputValues(inputBinding, gmapContext){
            if (!inputBinding) return;
            var currentLocation = GmUtility.locationFromLatLng(gmapContext.location);
            if (inputBinding.latitudeInput) {
                inputBinding.latitudeInput.val(currentLocation.latitude);
            }
            if (inputBinding.longitudeInput) {
                inputBinding.longitudeInput.val(currentLocation.longitude);
            }
            if (inputBinding.radiusInput) {
                inputBinding.radiusInput.val(gmapContext.radius);
            }
            if (inputBinding.locationNameInput) {
                inputBinding.locationNameInput.val(gmapContext.locationName);
            }
        }
    
        function setupInputListenersInput(inputBinding, gmapContext) {
            if (inputBinding) {
                if (inputBinding.radiusInput){
                    inputBinding.radiusInput.on("change", function() {
                        gmapContext.radius = $(this).val();
                        GmUtility.setPosition(gmapContext, gmapContext.location, function(context){
                            context.settings.onchanged.apply(gmapContext.domContainer,
                                [GmUtility.locationFromLatLng(context.location), context.radius, false]);
                        });
                    });
                }
                if (inputBinding.locationNameInput && gmapContext.settings.enableAutocomplete) {
                    gmapContext.autocomplete = new google.maps.places.Autocomplete(inputBinding.locationNameInput.get(0));
                    google.maps.event.addListener(gmapContext.autocomplete, 'place_changed', function() {
                        var place = gmapContext.autocomplete.getPlace();
                        if (!place.geometry) {
                            gmapContext.settings.onlocationnotfound(place.name);
                            return;
                        }
                        GmUtility.setPosition(gmapContext, place.geometry.location, function(context) {
                            updateInputValues(inputBinding, context);
                            context.settings.onchanged.apply(gmapContext.domContainer,
                                [GmUtility.locationFromLatLng(context.location), context.radius, false]);
                        });
                    });
                }
                if (inputBinding.latitudeInput) {
                    inputBinding.latitudeInput.on("change", function() {
                        GmUtility.setPosition(gmapContext, new google.maps.LatLng($(this).val(), gmapContext.location.lng()), function(context){
                            context.settings.onchanged.apply(gmapContext.domContainer,
                                [GmUtility.locationFromLatLng(context.location), context.radius, false]);
                        });
                    });
                }
                if (inputBinding.longitudeInput) {
                    inputBinding.longitudeInput.on("change", function() {
                        GmUtility.setPosition(gmapContext, new google.maps.LatLng(gmapContext.location.lat(), $(this).val()), function(context){
                            context.settings.onchanged.apply(gmapContext.domContainer,
                                [GmUtility.locationFromLatLng(context.location), context.radius, false]);
                        });
                    });
                }
            }
        }
    
        /**
         * Initialization:
         *  $("#myMap").locationpicker(options);
         * @param options
         * @param params
         * @returns {*}
         */
        $.fn.locationpicker = function( options, params ) {
            if (typeof options == 'string') { // Command provided
                var _targetDomElement = this.get(0);
                // Plug-in is not applied - nothing to do.
                if (!isPluginApplied(_targetDomElement)) return;
                var gmapContext = getContextForElement(_targetDomElement);
                switch (options) {
                    case "location":
                        if (params == undefined) { // Getter
                            var location = GmUtility.locationFromLatLng(gmapContext.location);
                            location.radius = gmapContext.radius;
                            location.name = gmapContext.locationName;
                            return location;
                        } else { // Setter
                            if (params.radius) {
                                gmapContext.radius = params.radius;
                            }
                            GmUtility.setPosition(gmapContext, new google.maps.LatLng(params.latitude, params.longitude), function(gmapContext) {
                                updateInputValues(gmapContext.settings.inputBinding, gmapContext);
                            });
                        }
                        break;
                    case "subscribe":
                        /**
                         * Provides interface for subscribing for GoogleMap events.
                         * See Google API documentation for details.
                         * Parameters:
                         * - event: string, name of the event
                         * - callback: function, callback function to be invoked
                         */
                        if (params == undefined) { // Getter is not available
                            return null;
                        } else {
                            var event = params.event;
                            var callback = params.callback;
                            if (!event || ! callback) {
                                console.error("LocationPicker: Invalid arguments for method \"subscribe\"")
                                return null;
                            }
                            google.maps.event.addListener(gmapContext.map, event, callback);
                        }
                        break;
                    case "map":
                        /**
                         * Returns object which allows access actual google widget and marker paced on it.
                         * Structure: {
                         *  map: Instance of the google map widget
                         *  marker: marker placed on map
                         * }
                         */
                        if (params == undefined) { // Getter is not available
                            var locationObj = GmUtility.locationFromLatLng(gmapContext.location);
                            locationObj.formattedAddress = gmapContext.locationName;
                            locationObj.addressComponents = gmapContext.addressComponents;
                            return {
                                map: gmapContext.map,
                                marker: gmapContext.marker,
                                location: locationObj
                            }
                        } else {
                            return null;
                        }
                }
                return null;
            }
            return this.each(function() {
                var $target = $(this);
                // If plug-in hasn't been applied before - initialize, otherwise - skip
                if (isPluginApplied(this)) return;
                // Plug-in initialization is required
                // Defaults
                var settings = $.extend({}, $.fn.locationpicker.defaults, options );
                // Initialize
                var gmapContext = new GMapContext(this, {
                    zoom: settings.zoom,
                    center: new google.maps.LatLng(settings.location.latitude, settings.location.longitude),
                    mapTypeId: google.maps.MapTypeId.ROADMAP,
                    mapTypeControl: false,
                    disableDoubleClickZoom: false,
                    scrollwheel: settings.scrollwheel,
                    streetViewControl: false,
                    radius: settings.radius,
                    locationName: settings.locationName,
                    settings: settings,
                    draggable: settings.draggable
                });
                $target.data("locationpicker", gmapContext);
                // Subscribe GMap events
                google.maps.event.addListener(gmapContext.marker, "dragend", function(event) {
                    GmUtility.setPosition(gmapContext, gmapContext.marker.position, function(context){
                        var currentLocation = GmUtility.locationFromLatLng(gmapContext.location);
                        context.settings.onchanged.apply(gmapContext.domContainer, [currentLocation, context.radius, true]);
                        updateInputValues(gmapContext.settings.inputBinding, gmapContext);
                    });
                });
                GmUtility.setPosition(gmapContext, new google.maps.LatLng(settings.location.latitude, settings.location.longitude), function(context){
                    updateInputValues(settings.inputBinding, gmapContext);
                    context.settings.oninitialized($target);
                    var currentLocation = GmUtility.locationFromLatLng(gmapContext.location);
                    settings.onchanged.apply(gmapContext.domContainer, [currentLocation, context.radius, false]);
                });
                // Set up input bindings if needed
                setupInputListenersInput(settings.inputBinding, gmapContext);
            });
        };
        $.fn.locationpicker.defaults = {
            location: {latitude: 40.7324319, longitude: -73.82480799999996},
            locationName: "",
            radius: 500,
            zoom: 15,
            scrollwheel: true,
            inputBinding: {
                latitudeInput: null,
                longitudeInput: null,
                radiusInput: null,
                locationNameInput: null
            },
            enableAutocomplete: false,
            enableReverseGeocode: true,
            draggable: true,
            onchanged: function(currentLocation, radius, isMarkerDropped) {},
            onlocationnotfound: function(locationName) {},
            oninitialized: function (component) {}
    
        }
    
    }( jQuery ));
    
    </script>
@endpush

