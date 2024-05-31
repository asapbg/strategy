@extends('layouts.site')

@section('pageTitle', __('auth.login'))

@section('content')
<form method="POST" action="{{ route('login') }}">
    @csrf
    <section class="home-page-section">
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
                        <h2 class="fs-3 mb-3 text-center">Вход в системата</h2>
                        <div class="input-group">
                            <div class="flex-grow-1 form-floating">

                                <!--<input type="text" name="username" class="form-control" @if (old('username')) value="{{ old('username') }}" @else placeholder="{{ __('validation.attributes.email') }}" @endif>-->
                                <input type="text" name="username" class="form-control" value="{{ old('username') }}"  placeholder="">


                                <label for="floatingInput">
                                    {{ __('validation.attributes.email') }}
                                </label>
                            </div>
                            <span class="input-group-text"><i class="fa-solid fa-envelope"></i></span>


                            <div class="input-group mt-3 mb-3">
                                <div class="flex-grow-1 form-floating">
                                    <input id="password" name="password" class="form-control" autocomplete="current-password" type="password"
                                        @if (old('password')) value="{{ old('password') }}" @else placeholder="" @endif>
                                    <label>
                                        {{ __('validation.attributes.password') }}
                                    </label>
                                </div>
                                <span class="input-group-text" onclick="password_show_hide();">
                                    <i class="fas fa-eye" id="show_eye"></i>
                                    <i class="fas fa-eye-slash d-none" id="hide_eye"></i>
                                  </span>

                            </div>


                            <div class="login-remember w-100">

                                <div class="checkbox mb-3 d-none">
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
                            <div class="mb-4">
                                <a class="d-inline-block w-100" href="{{ route('forgot_pass') }}">
                                    {{ __('auth.forgot_password') }}
                                </a>
                                <a href="{{ route('register') }}">
                                    {{ __('auth.do_not_have_account') }}
                                </a>
                            </div>

                        </div>
                        <div class="row">
                            <button class="col-md-6 offset-md-3 cstm-btn btn btn-lg  btn-primary mb-2" type="submit"><span class="btn-label"><i
                                        class="fa-solid fa-right-to-bracket main-color"
                                        ></i></span>
                                {{ __('auth.login') }}
                            </div>
                            </button>
                            <div class="row">
                            <a class="col-md-6 offset-md-3 cstm-btn btn btn-primary btn-lg" href="{{ route('eauth.login') }}">
                                <span class="btn-label"><i
                                        class="fa-solid fa-signature main-color "
                                        ></i></span>
                                {{ __('eauth.with_e_auth') }}
                            </a>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
</form>
@endsection

@push('scripts')
<script>
  function password_show_hide() {
    var x = document.getElementById("password");
    var show_eye = document.getElementById("show_eye");
    var hide_eye = document.getElementById("hide_eye");
    hide_eye.classList.remove("d-none");
    if (x.type === "password") {
      x.type = "text";
      show_eye.style.display = "none";
      hide_eye.style.display = "block";
    } else {
      x.type = "password";
      show_eye.style.display = "block";
      hide_eye.style.display = "none";
    }
  }

</script>
@endpush
