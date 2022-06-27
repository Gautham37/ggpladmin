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
                    <a class="nav-link active" href="{!! url('settings/bankDetails') !!}"><i class="fa fa-list mr-2"></i>{{trans('lang.app_bank_detail')}}</a>
                </li>
            </ul>
        </div>
        <div class="card-body">
            {!! Form::open(['url' => ['settings/update'], 'method' => 'patch']) !!}
            <div class="row">

                <div class="form-group col-md-12">
                    {!! Form::label('app_bank_bankname', trans("lang.app_bank_bankname"), ['class' => 'control-label text-right']) !!}
                    <div class="col-8">
                        {!! Form::text('app_bank_bankname', setting('app_bank_bankname'),  ['class' => 'form-control','placeholder'=>  trans("lang.app_bank_bankname_placeholder")]) !!}
                        <div class="form-text text-muted">
                            {{ trans("lang.app_bank_bankname_help") }}
                        </div>
                    </div>
                </div>

                <div class="form-group col-md-12">
                    {!! Form::label('app_bank_account_number', trans("lang.app_bank_account_number"), ['class' => 'control-label text-right']) !!}
                    <div class="col-8">
                        {!! Form::text('app_bank_account_number', setting('app_bank_account_number'),  ['class' => 'form-control','placeholder'=>  trans("lang.app_bank_account_number_placeholder")]) !!}
                        <div class="form-text text-muted">
                            {{ trans("lang.app_bank_account_number_help") }}
                        </div>
                    </div>
                </div>

                <div class="form-group col-md-12">
                    {!! Form::label('app_bank_ifsc_code', trans("lang.app_bank_ifsc_code"), ['class' => 'control-label text-right']) !!}
                    <div class="col-8">
                        {!! Form::text('app_bank_ifsc_code', setting('app_bank_ifsc_code'),  ['class' => 'form-control','placeholder'=>  trans("lang.app_bank_ifsc_code_placeholder")]) !!}
                        <div class="form-text text-muted">
                            {{ trans("lang.app_bank_ifsc_code_help") }}
                        </div>
                    </div>
                </div>

                <div class="form-group col-md-12">
                    {!! Form::label('app_bank_bankbranch', trans("lang.app_bank_bankbranch"), ['class' => 'control-label text-right']) !!}
                    <div class="col-8">
                        {!! Form::text('app_bank_bankbranch', setting('app_bank_bankbranch'),  ['class' => 'form-control','placeholder'=>  trans("lang.app_bank_bankbranch_placeholder")]) !!}
                        <div class="form-text text-muted">
                            {{ trans("lang.app_bank_bankbranch_help") }}
                        </div>
                    </div>
                </div>


                <div class="form-group col-md-12">
                    {!! Form::label('app_upi_id', trans("lang.app_upi_id"), ['class' => 'control-label text-right']) !!}
                    <div class="col-8">
                        {!! Form::text('app_upi_id', setting('app_upi_id'),  ['class' => 'form-control','placeholder'=>  trans("lang.app_upi_id_placeholder")]) !!}
                        <div class="form-text text-muted">
                            {{ trans("lang.app_upi_id_help") }}
                        </div>
                    </div>
                </div>


                <!-- App Logo Field -->
                <div class="form-group row">
                    {!! Form::label('app_upi_code', trans("lang.app_upload_upi_code"), ['class' => 'control-label text-right']) !!}
                    <div class="col-12">
                        <div style="width: 100%" class="dropzone app_upi_code" id="app_upi_code" data-field="app_upi_code">
                            <input type="hidden" name="app_upi_code">
                        </div>
                        <a href="#loadMediaModal" data-dropzone="app_upi_code" data-toggle="modal" data-target="#mediaModal" class="btn btn-outline-{{setting('theme_color')}} btn-sm float-right mt-1">{{ trans('lang.media_select')}}</a>
                        <div class="form-text text-muted w-50">
                            {{ trans("lang.app_upload_upi_code_help") }}
                        </div>
                    </div>
                </div>
                @prepend('scripts')
                <script type="text/javascript">
                    var dzAppUpicode = '';
                    @if(isset($upload) && $upload->hasMedia('app_upi_code'))
                        dzAppUpicode = {
                        name: "{!! $upload->getFirstMedia('app_upi_code')->name !!}",
                        size: "{!! $upload->getFirstMedia('app_upi_code')->size !!}",
                        type: "{!! $upload->getFirstMedia('app_upi_code')->mime_type !!}",
                        collection_name: "{!! $upload->getFirstMedia('app_upi_code')->collection_name !!}"
                    };
                            @endif
                    var dz_dzAppUpicode = $(".dropzone.app_upi_code").dropzone({
                            url: "{!!url('uploads/store')!!}",
                            addRemoveLinks: true,
                            maxFiles: 1,
                            init: function () {
                                @if(isset($upload) && $upload->hasMedia('app_upi_code'))
                                dzInit(this, dzAppUpicode, '{!! url($upload->getFirstMediaUrl('app_upi_code','thumb')) !!}')
                                @endif
                            },
                            accept: function (file, done) {
                                dzAccept(file, done, this.element, "{!!config('medialibrary.icons_folder')!!}");
                            },
                            sending: function (file, xhr, formData) {
                                dzSending(this, file, formData, '{!! csrf_token() !!}');
                            },
                            maxfilesexceeded: function (file) {
                                dz_dzAppUpicode[0].mockFile = '';
                                dzMaxfile(this, file);
                            },
                            complete: function (file) {
                                dzComplete(this, file, dzAppUpicode, dz_dzAppUpicode[0].mockFile);
                                dz_dzAppUpicode[0].mockFile = file;
                            },
                            removedfile: function (file) {
                                dzRemoveFile(
                                    file, dzAppUpicode, '{!! url("uploads/remove-media") !!}',
                                    'app_upi_code', '{!! isset($upload) ? $upload->id : 0 !!}', '{!! url("uplaods/clear") !!}', '{!! csrf_token() !!}'
                                );
                            }
                        });
                    dz_dzAppUpicode[0].mockFile = dzAppUpicode;
                    dropzoneFields['app_upi_code'] = dz_dzAppUpicode;
                </script>
                @endprepend


                <!-- Submit Field -->
                <div class="form-group mt-4 col-12 text-right">
                    <button type="submit" class="btn btn-{{setting('theme_color')}}"><i class="fa fa-save"></i> {{trans('lang.save')}} {{trans('lang.app_bank_detail')}}
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

