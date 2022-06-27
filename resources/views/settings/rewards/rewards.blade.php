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
                    <a class="nav-link active" href="{!! url('settings/bankDetails') !!}"><i class="fa fa-list mr-2"></i>{{trans('lang.app_setting_rewards')}}</a>
                </li>
            </ul>
        </div>
        <div class="card-body">
            {!! Form::open(['url' => ['settings/update'], 'method' => 'patch']) !!}
            <div class="row">

                <div class="form-group col-md-6 custom-from-css">
                    <div class="row custom-checkbx"> 
                    {!! Form::label('global_reward_status', trans("lang.app_setting_global_reward_status"),['class' => 'col-md-3 control-label text-left']) !!}
                    <div class="col-md-9 checkbox icheck">
                        <label class="w-100 ml-2 form-check-inline">
                            {!! Form::hidden('global_reward_status', null) !!}
                            {!! Form::checkbox('global_reward_status', '1', setting('global_reward_status')) !!}
                            <span class="ml-2">{!! trans("lang.app_setting_global_reward_status_help") !!}</span> </label>
                    </div>
                    </div>
                </div>
                
                <div class="form-group col-md-6 custom-from-css">
                    <div class="row custom-checkbx"> 
                            
                            
                        
                    </div>
                </div>
                
                <div class="form-group col-md-6">
                    {!! Form::label('app_referal_reward_points', trans("lang.app_referal_reward_points"), ['class' => 'control-label text-left']) !!}
                    {!! Form::number('app_referal_reward_points', setting('app_referal_reward_points'),  ['min'=>'1','class' => 'form-control','placeholder'=>  trans("lang.app_referal_reward_points_placeholder")]) !!}
                    <!-- <div class="form-text text-muted">
                        {{ trans("lang.app_bank_account_number_help") }}
                    </div> -->
                </div>

                <!-- Submit Field -->
                <div class="form-group mt-4 col-12 text-right">
                    <button type="submit" class="btn btn-{{setting('theme_color')}}"><i class="fa fa-save"></i> {{trans('lang.save')}} {{trans('lang.app_setting_rewards')}}
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