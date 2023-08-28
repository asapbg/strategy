@extends('layouts.site')

@section('pageTitle', __('auth.login'))

@section('content')
<form method="POST" action="{{ route('login') }}">
    @csrf
    <section id="login">
        <div class="container">
            <div class="row">
                <div class="col-md-6 offset-md-3">

                    @error('username')
                        <div class="alert alert-danger mt-1">
                            {{ $message }}
                        </div>
                    @enderror

                    @error('error')
                        <div class="alert alert-danger mt-1">
                            {{ $message }}
                        </div>
                    @enderror

                    <div class="login-form p-4">
                        <div class="input-group">
                            <div class="flex-grow-1 form-floating">
                                <input type="text" name="username" class="form-control" required
                                    @if (old('username')) value="{{ old('username') }} @else placeholder="{{ __('validation.attributes.email') }}" @endif">
                                <label for="floatingInput">
                                    {{ __('validation.attributes.email') }}
                                </label>
                            </div>
                            <span class="input-group-text"><i class="fa-solid fa-envelope"></i></span>


                            <div class="input-group mt-3 mb-3">
                                <div class="flex-grow-1 form-floating">
                                    <input type="password" name="password" class="form-control" required autocomplete="current-password"
                                        @if (old('password')) value="{{ old('password') }} @else placeholder="{{ __('auth.password') }}" @endif">
                                    <label>
                                        {{ __('validation.attributes.password') }}
                                    </label>
                                </div>
                                <span class="input-group-text"><i class="fas fa-lock"></i></span>

                            </div>


                            <div class="login-remember w-100">

                                <div class="checkbox mb-3">
                                    <label class="d-flex">
                                        <input class="me-1" type="checkbox" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                        {{ __('validation.attributes.rememberme') }}
                                    </label>
                                </div>

{{--                                <div class="forgot-password mb-3">--}}
{{--                                    <a href="{{ url('/password/reset') }}">--}}
{{--                                        {{ __('auth.forgot_password') }}--}}
{{--                                    </a>--}}
{{--                                </div>--}}
                            </div>
                            <div class="mb-2">
                                <a class="d-inline-block w-100" href="{{ route('forgot_pass') }}">
                                    {{ __('auth.forgot_password') }}
                                </a>
                                <a href="{{ route('register') }}">
                                    {{ __('auth.do_not_have_account') }}
                                </a>
                            </div>

                        </div>
                        <div class="row">
                            <a class="col-12 cstm-btn btn btn-lg rounded" href="{{ route('eauth.login') }}">
                                <span class="btn-label"><i
                                        class="fa-solid fa-lock main-color"
                                        style="margin-right:10px"></i></span>
                                {{ __('eauth.with_e_auth') }}
                            </a>
                            <button class="col-12 cstm-btn btn btn-lg rounded" type="submit"><span class="btn-label"><i
                                        class="fa-solid fa-right-to-bracket main-color"
                                        style="margin-right:10px"></i></span>
                                {{ __('auth.login') }}
                            </button>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
</form>
@endsection
