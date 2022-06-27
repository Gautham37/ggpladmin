@extends('layouts.settings.default')
@section('settings_title',trans('lang.custom_field_table'))
@section('settings_content')
    @include('flash::message')
    <div class="card">
        <div class="card-header">
            <ul class="nav nav-tabs align-items-end card-header-tabs w-100">
                <li class="nav-item">
                    <a class="nav-link active" href="{!! url()->current() !!}"><i class="fa fa-list mr-2"></i>{{trans('lang.custom_field_table')}}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{!! route('customFields.create') !!}"><i class="fa fa-plus mr-2"></i>{{trans('lang.custom_field_create')}}</a>
                </li>
                @include('layouts.right_toolbar_new', compact('dataTable'))
            </ul>
        </div>
        <div class="card-body">
            @include('settings.custom_fields.table')
            <div class="clearfix"></div>
        </div>
    </div>
    </div>
@endsection

