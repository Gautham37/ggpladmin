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
    <div class="card">
        <div class="card-header">
            <ul class="nav nav-tabs align-items-end card-header-tabs w-100">
                <li class="nav-item">
                    <a class="nav-link active" href="{!! url('settings/addressDetails') !!}"><i class="fa fa-list mr-2"></i>Store Address</a>
                </li>
            </ul>
        </div>
        <div class="card-body">
            {!! Form::open(['url' => ['settings/update'], 'method' => 'patch']) !!}
            <div class="row custom-from-css">

                <div class="form-group col-md-6">
                    {!! Form::label('app_store_address_line_1', trans("lang.app_store_address_line_1"), ['class' => 'control-label text-left']) !!}
                    {!! Form::text('app_store_address_line_1', setting('app_store_address_line_1'),  ['class' => 'form-control','placeholder'=>  trans("lang.app_store_address_line_1_placeholder")]) !!}
                    <!-- <div class="form-text text-muted">
                        {{ trans("lang.app_store_address_line_1_help") }}
                    </div> -->
                </div>

                <div class="form-group col-md-6">
                    {!! Form::label('app_store_address_line_2', trans("lang.app_store_address_line_2"), ['class' => 'control-label text-left']) !!}
                    {!! Form::text('app_store_address_line_2', setting('app_store_address_line_2'),  ['class' => 'form-control','placeholder'=>  trans("lang.app_store_address_line_2_placeholder")]) !!}
                    <!-- <div class="form-text text-muted">
                        {{ trans("lang.app_store_address_line_2_help") }}
                    </div> -->
                </div>

                <div class="form-group col-md-6">
                    {!! Form::label('app_store_city', trans("lang.app_store_city"), ['class' => 'control-label text-left']) !!}
                    {!! Form::text('app_store_city', setting('app_store_city'),  ['class' => 'form-control','placeholder'=>  trans("lang.app_store_city_placeholder")]) !!}
                    <!-- <div class="form-text text-muted">
                        {{ trans("lang.app_store_city_help") }}
                    </div> -->
                </div>

                <div class="form-group col-md-6">
                    {!! Form::label('app_store_state', trans("lang.app_store_state"), ['class' => 'control-label text-left']) !!}
                    {!! Form::text('app_store_state', setting('app_store_state'),  ['class' => 'form-control','placeholder'=>  trans("lang.app_store_state_placeholder")]) !!}
                    <!-- <div class="form-text text-muted">
                        {{ trans("lang.app_store_state_help") }}
                    </div> -->
                </div>

                <div class="form-group col-md-6">
                    {!! Form::label('app_store_country', trans("lang.app_store_country"), ['class' => 'control-label text-left']) !!}
                    {!! Form::text('app_store_country', setting('app_store_country'),  ['class' => 'form-control','placeholder'=>  trans("lang.app_store_country_placeholder")]) !!}
                    <!-- <div class="form-text text-muted">
                        {{ trans("lang.app_store_country_help") }}
                    </div> -->
                </div>

                <div class="form-group col-md-6">
                    {!! Form::label('app_store_pincode', trans("lang.app_store_pincode"), ['class' => 'control-label text-left']) !!}
                    {!! Form::text('app_store_pincode', setting('app_store_pincode'),  ['class' => 'form-control','placeholder'=>  trans("lang.app_store_pincode_placeholder")]) !!}
                    <!-- <div class="form-text text-muted">
                        {{ trans("lang.app_store_pincode_help") }}
                    </div> -->
                </div>


                <div class="form-group col-md-6">
                    {!! Form::label('app_store_phone_no', trans("lang.app_store_phone_no"), ['class' => 'control-label text-left']) !!}
                    {!! Form::text('app_store_phone_no', setting('app_store_phone_no'),  ['class' => 'form-control','placeholder'=>  trans("lang.app_store_phone_no_placeholder")]) !!}
                    <!-- <div class="form-text text-muted">
                        {{ trans("lang.app_store_phone_no_help") }}
                    </div> -->
                </div>

                <div class="form-group col-md-6">
                    {!! Form::label('app_store_gstin', trans("lang.app_store_gstin"), ['class' => 'control-label text-left']) !!}
                    {!! Form::text('app_store_gstin', setting('app_store_gstin'),  ['class' => 'form-control','placeholder'=>  trans("lang.app_store_gstin_placeholder")]) !!}
                    <!-- <div class="form-text text-muted">
                        {{ trans("lang.app_store_gstin_help") }}
                    </div> -->
                </div>

                <!-- Submit Field -->
                <div class="form-group mt-4 col-12 text-right">
                    <button type="submit" class="btn btn-{{setting('theme_color')}}"><i class="fa fa-save"></i> {{trans('lang.save')}} {{trans('lang.app_store_address')}}
                    </button>
                    <a href="{!! route('users.index') !!}" class="btn btn-default"><i class="fa fa-undo"></i> {{trans('lang.cancel')}}</a>
                </div>

            </div>
            {!! Form::close() !!}
            <div class="clearfix"></div>
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
@endpush

