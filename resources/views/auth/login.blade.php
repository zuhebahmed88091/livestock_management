@extends('layouts.blank')

@section('content')
    <div class="login-box">
        <div class="login-logo">
            <a href="{{ url('/') }}"><b>{{__('info.admin')}}</b>{{ config('settings.SITE_SHORT_NAME') }}</a>
        </div>

        <div class="login-box-body">
            <p class="login-box-msg">{{__('info.Sign_in_to_start_your_session')}}</p>

            <form action="{{ route('login') }}" method="POST">
                @csrf

                <div class="form-group has-feedback">
                    <input id="email" type="email"
                           class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}"
                           name="email" value="{{ old('email') }}" placeholder="Email" required autofocus>
                    <span class="glyphicon glyphicon-envelope form-control-feedback"></span>

                    @if ($errors->has('email'))
                        <span class="invalid-feedback" role="alert">
                        <strong>{{ $errors->first('email') }}</strong>
                    </span>
                    @endif
                </div>
                <div class="form-group has-feedback">
                    <input id="password" type="password"
                           class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password"
                           placeholder="Password">
                    <span class="glyphicon glyphicon-lock form-control-feedback"></span>

                    @if ($errors->has('password'))
                        <span class="invalid-feedback" role="alert">
                        <strong>{{ $errors->first('password') }}</strong>
                    </span>
                    @endif
                </div>
                <div class="row">
                    <div class="col-xs-8">
                        <div class="checkbox icheck">
                            <label>
                                <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                {{ __('Remember Me') }}
                            </label>
                        </div>
                    </div>
                    <div class="col-xs-4">
                        <button type="submit" class="btn btn-primary btn-block btn-flat">{{__('info.sign_in')}}</button>
                    </div>
                </div>
            </form>

            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}">{{__('info.i_forgot_my_password')}}</a><br>
            @endif

        </div>
    </div>
@endsection

<!-- iCheck -->
@section('javascript')
    <script>
        $(function () {
            "use strict";
            $('input').iCheck({
                checkboxClass: 'icheckbox_square-blue',
                radioClass: 'iradio_square-blue',
                increaseArea: '20%'
            });
        });
    </script>
@endsection

