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
                    
                    <!-- Volunteer with us Field -->
                    <div class="form-group ">
                      {!! Form::label('app_website_volunteer', trans("lang.app_website_volunteer"), ['class' => ' control-label text-left']) !!}
                        {!! Form::textarea('app_website_volunteer', setting('app_website_volunteer'), ['class' => 'form-control','placeholder'=>
                         trans("lang.app_website_volunteer_placeholder")  ]) !!}
                        <!-- <div class="form-text text-muted">{{ trans("lang.app_website_volunteer_help") }}</div> -->
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
@endpush

