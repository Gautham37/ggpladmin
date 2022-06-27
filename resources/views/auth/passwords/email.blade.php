@extends('layouts.auth.default')
@section('content')
    <div class="card-body login-card-body">
    <div class="card-body login-card-body">
		<div class="login-logo">
			<a href="{{ url('/') }}"><img src="{{$app_logo}}" alt="{{setting('app_name')}}"></a>
		</div>
        <p class="login-box-msg">{{__('auth.reset_title')}}</p>

        @if (session('status'))
            <div class="alert alert-success" role="alert">
                {{ session('status') }}
            </div>
        @endif

        <form method="post" action="{{ url('password/email') }}">
            {!! csrf_field() !!}
			
            <div class="form-group mb-3 position-relative">
                <input value="{{ old('email') }}" type="email" class="form-control {{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" placeholder="{{__('auth.email')}}" aria-label="{{__('auth.email')}}">
                <i class="fa fa-envelope position-absolute"></i>
                @if ($errors->has('email'))
                    <div class="invalid-feedback">
                        {{ $errors->first('email') }}
                    </div>
                @endif
            </div>
            <div class="row mb-3 ">
                <!-- /.col -->
                <div class="col-12 col-sm-8 m-auto">
                    <button type="submit" class="btn btn-success btn-block">Submit</button>
                </div>
                <!-- /.col -->
            </div>
        </form>
        <p class="mb-0 text-center forgot">
            <a href="{{ url('/login') }}" class="text-center">Return to login..</a>
        </p>
    </div>
    </div>
@endsection