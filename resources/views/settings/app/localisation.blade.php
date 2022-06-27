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
            </ul>
        </div>
        <div class="card-body">
            {!! Form::open(['url' => ['settings/update'], 'method' => 'patch']) !!}
            <div class="row">
                <div class="col-md-12 column custom-from-css">
                    <div class="row">

                    <!-- date_format Field -->
                    <div class="form-group col-md-6 ">
                        {!! Form::label('date_format', trans("lang.app_setting_date_format"), ['class' => ' control-label text-left']) !!}
                        {!! Form::text('date_format', setting('date_format'),  ['class' => 'form-control','placeholder'=>  trans("lang.app_setting_date_format_placeholder")]) !!}
                        <!-- <div class="form-text text-muted">
                            {!! trans("lang.app_setting_date_format_help") !!}
                        </div> -->
                    </div>

                    <!-- Lang Field -->
                    <div class="form-group col-md-6 ">
                        {!! Form::label('language', trans("lang.app_setting_language"),['class' => ' control-label text-left']) !!}
                        {!! Form::select('language', $languages, setting('language'), ['class' => 'select2 form-control']) !!}
                        <!-- <div class="form-text text-muted">{{ trans("lang.app_setting_language_help") }}</div> -->
                    </div>

                    <!-- timezone Field -->
                    <div class="form-group col-md-6 ">
                        {!! Form::label('timezone', trans("lang.app_setting_timezone"),['class' => ' control-label text-left']) !!}
                        {!! Form::select('timezone', $timezones, setting('timezone'), ['class' => 'select2 form-control']) !!}
                        <!-- <div class="form-text text-muted">{{ trans("lang.app_setting_timezone_help") }}</div> -->
                    </div>

                    <!-- 'Boolean is_human_date_format Field' -->
                    <div class="form-group col-md-6 checkbox-cust1">
                        <div class="row">
                        {!! Form::label('is_human_date_format', trans("lang.app_setting_is_human_date_format"),['class' => 'col-md-5 control-label text-left']) !!}
                        <div class="col-md-7 checkbox icheck">
                            <label class=" form-check-inline">
                                {!! Form::hidden('is_human_date_format', null) !!}
                                {!! Form::checkbox('is_human_date_format', 1, setting('is_human_date_format', false)) !!}
                            </label>
                        </div>
                        </div>
                    </div>

                    </div>
                </div>

                <!-- Submit Field -->
                <div class="form-group mt-4 col-12 text-right">
                    <button type="submit" class="btn btn-{{setting('theme_color')}}"><i class="fa fa-save"></i> {{trans('lang.save')}} {{trans('lang.app_setting_localisation')}}
                    </button>
                    <a href="{!! route('users.index') !!}" class="btn btn-default"><i class="fa fa-undo"></i> {{trans('lang.cancel')}}</a>
                </div>
            </div>
            {!! Form::close() !!}
            <div class="clearfix"></div>
        </div>
    </div>
    </div>
    @include('layouts.media_modal',['collection'=>null])
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
