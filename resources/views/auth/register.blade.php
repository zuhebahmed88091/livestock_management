@extends('layouts.blank')

@section('content')
    <div class="login-box">
        <div class="login-logo">
            <a href="{{ url('/') }}"><b>{{__('info.admin')}}</b>{{ config('settings.SITE_SHORT_NAME') }}</a>
        </div>

        <div class="login-box-body">
            <p class="login-box-msg">{{__('info.Sign_up_to_start_your_session')}}</p>

            <form method="POST" action="{{ route('register') }}">
                @csrf

                <div class="form-group has-feedback">
                    <input id="name" type="text"
                           class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}"
                           placeholder="{{ __('Name') }}"
                           name="name" value="{{ old('name') }}" required autofocus>

                    <span class="glyphicon glyphicon-user form-control-feedback"></span>

                    @if ($errors->has('name'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('name') }}</strong>
                        </span>
                    @endif

                </div>

                <div class="form-group has-feedback">

                    <input id="email" type="email"
                           class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}"
                           placeholder="{{ __('E-Mail Address') }}"
                           name="email" value="{{ old('email') }}" required>

                    <span class="glyphicon glyphicon-envelope form-control-feedback"></span>

                    @if ($errors->has('email'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('email') }}</strong>
                        </span>
                    @endif

                </div>

                <div class="form-group has-feedback">

                    <input id="password" type="password"
                           class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}"
                           placeholder="{{ __('Password') }}"
                           name="password" required>

                    <span class="glyphicon glyphicon-lock form-control-feedback"></span>

                    @if ($errors->has('password'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('password') }}</strong>
                        </span>
                    @endif
                </div>

                <div class="form-group has-feedback">

                    <input id="password-confirm" type="password" class="form-control"
                           placeholder="{{ __('Confirm Password') }}"
                           name="password_confirmation" required>

                    <span class="glyphicon glyphicon-lock form-control-feedback"></span>

                </div>

                <div class="form-group has-feedback">
                    <a href="{{ route('login') }}">{{__('info.already_a_members_Sign_In')}}</a><br>
                    <button type="submit" class="btn btn-primary btn-flat pull-right">
                        {{ __('Register') }}
                    </button>
                </div>
                <div class="clearfix"></div>
            </form>

        </div>
    </div>
@endsection
