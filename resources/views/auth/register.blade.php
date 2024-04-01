@extends('layouts.site')

@section('pageTitle', __('custom.register'))

@section('content')
<div class="container" id="register">
    <div class="row justify-content-center">
        <div class="col-md-6 register-form p-4">
            <h2 class="fs-3 mb-3 text-center">Регистрация в системата</h2>
            <form method="POST" action="{{ route('register') }}" id="reg_form">
                @csrf
                <div class="row mb-3">
                    <label for="is_org" class="col-md-12 col-form-label text-left mb-2 fs-5">{{ __('validation.attributes.is_org') }} <span class="text-danger">*</span></label>

                    <div class="col-md-12">
                        <div class="btn-group user-select" role="group" aria-label="Basic radio toggle button group">

                            <input id="is_org2" class="btn-check form-control" type="radio" name="is_org" value="0" {{ old('is_org') == 0 ? 'checked' : '' }}>
                            <label class="btn reg-btn" for="is_org2">Физическо лице</label>

                            <input id="is_org1" class="btn-check form-control" type="radio" name="is_org" value="1" {{ old('is_org') == 1 ? 'checked' : '' }}>
                            <label class="btn reg-btn" for="is_org1">Юридическо лице</label>

                        </div>

                        @error('is_org')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>

                <div id="part-org" class="mb-3">
                    <div class="input-group">
                        <div class="flex-grow-1 form-floating">
                            <input id="org_name" type="text" class="form-control @error('org_name') is-invalid @enderror" name="org_name" value="{{ old('org_name') }}" autocomplete="org_name" placeholder="">
                            <label for="org_name">{{ __('validation.attributes.org_name') }} <span class="text-danger">*</span></label>
                        </div>
                        <span class="input-group-text"><i class="fa-solid fa-user"></i></span>
                    </div>
                    @error('org_name')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div id="part-person">
                    <div class="mb-3">
                        <div class="input-group">
                            <div class="flex-grow-1 form-floating">
                                <input id="first_name" type="text" class="form-control @error('first_name') is-invalid @enderror" name="first_name" value="{{ old('first_name') }}" autocomplete="first_name" placeholder="">
                                <label for="first_name">{{ __('validation.attributes.first_name') }} <span class="text-danger">*</span></label>
                            </div>
                            <span class="input-group-text"><i class="fa-solid fa-user"></i></span>
                        </div>
                        @error('first_name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <div class="input-group">
                            <div class="flex-grow-1 form-floating">
                                <input id="last_name" type="text" class="form-control @error('last_name') is-invalid @enderror" name="last_name" value="{{ old('last_name') }}" autocomplete="last_name" placeholder="">
                                <label for="last_name">{{ __('validation.attributes.last_name') }} <span class="text-danger">*</span></label>
                            </div>
                            <span class="input-group-text"><i class="fa-solid fa-user"></i></span>
                        </div>
                        @error('last_name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="input-group">
                        <div class="flex-grow-1 form-floating">
                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" autocomplete="email" placeholder="">
                            <label for="email">{{ __('validation.attributes.email') }} <span class="text-danger">*</span></label>
                        </div>
                        <span class="input-group-text"><i class="fa-solid fa-envelope"></i></span>
                    </div>
                    @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="mb-3">
                    <div class="main-color"><p>{{ __('auth.password_format') }}</p></div>
                    <div class="input-group">
                        <div class="flex-grow-1 form-floating">
                            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" autocomplete="new-password" placeholder="">
                            <label for="password">{{ __('validation.attributes.password') }} <span class="text-danger">*</span></label>
                        </div>
{{--                        <span class="input-group-text" onclick="password_show_hide();">--}}
                        <span class="input-group-text">
                            <i class="fas fa-eye" id="show_eye"></i>
                            <i class="fas fa-eye-slash d-none" id="hide_eye"></i>
                          </span>
                    </div>
                    @error('password')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="mb-3">
                    <div class="input-group">
                        <div class="flex-grow-1 form-floating">
                            <input id="password-confirm" type="password" class="form-control" name="password_confirmation" autocomplete="new-password" placeholder="">
                            <label for="password-confirm" class="">{{ __('validation.attributes.password_confirm') }} <span class="text-danger">*</span></label>
                        </div>
{{--                            <span class="input-group-text" onclick="password_show_hide_confirm();">--}}
                            <span class="input-group-text">
                              <i class="fas fa-eye" id="show_eye_c"></i>
                              <i class="fas fa-eye-slash d-none" id="hide_eye_c"></i>
                            </span>
                    </div>
                    @error('password_confirmation')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="mb-4">
                    <a href="{{ route('login') }}">
                        {{ __('auth.already_have_account') }}
                    </a>
                </div>

                <div class="row mb-0 ">
                        <button class="col-md-6 offset-md-3 cstm-btn btn btn-lg  btn-primary mb-2" id="reg_btn" type="button"><i class="fa-solid fa-right-to-bracket main-color me-1"></i>{{ __('custom.register') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <script src="{{ asset('jquery-validation/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('jquery-validation/localization/messages_' . app()->getLocale() . '.js') }}"></script>
    <script>
        var validateObject = null;

        function togglePersonal(init) {
            var show = $('input[name="is_org"]:checked').val() == 0;
            $('#part-org').toggle(!show);
            $('#part-person').toggle(show);
            if(typeof init != 'undefined' && validateObject){
                validateObject.resetForm();
                $('#reg_form').removeData('validator');
            }
        }

        function validateRegistration(){
            //validate form
            let lForm = $('#reg_form');
            validateObject = $(lForm).validate({
                // errorClass: 'is_invalid',
                errorClass: 'text-danger mt-1',
                errorElement: 'div',
                rules: formRules($('#is_org2').is(':checked') ? 'person' : 'company'),
                errorPlacement: function (error, element) {
                    error.insertAfter(element.parent().parent());
                },
                invalidHandler: function (e, validation) {
                    console.log("invalidHandler : event", e);
                    console.log("invalidHandler : validation", validation);
                }
            });
            if ($(lForm).valid()) {
                $(lForm).submit();
            }
        }

        function formRules(formType) {
            let rules = {
                person: {
                    first_name: {
                        required: true,
                        maxlength: 255
                    },
                    last_name: {
                        required: true,
                        maxlength: 255
                    },
                    email: {
                        required: true,
                        email: true,
                        maxlength: 255
                    },
                    password: {
                        required: true,
                        strong_password: true,
                    },
                    password_confirmation: {
                        required: true,
                        minlength: 8,
                        maxlength: 16,
                    }
                },
                company: {
                    org_name: {
                        required: true,
                        maxlength: 255
                    },
                    email: {
                        required: true,
                        email: true,
                        maxlength: 255
                    },
                    password: {
                        required: true,
                        strong_password: true,
                    },
                    password_confirmation: {
                        required: true,
                        minlength: 8,
                        maxlength: 16,
                    }
                }
            }
            return rules[formType];
        }

        $(document).ready(function () {
            $.validator.addMethod("strong_password", function (value, element) {
                let password = value;
                if (!(/^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[@#$%&])(.{8,20}$)/.test(password))) {
                    return false;
                }
                return true;
            }, function (value, element) {
                let password = $(element).val();
                if (!(/^(.{8,16}$)/.test(password))) {
                    return jQuery.validator.messages.betweenLength;
                }
                else if (!(/^(?=.*[A-Z])/.test(password))) {
                    return jQuery.validator.messages.oneUpperLetter;
                }
                else if (!(/^(?=.*[a-z])/.test(password))) {
                    return jQuery.validator.messages.oneLowerLetter;
                }
                else if (!(/^(?=.*[0-9])/.test(password))) {
                    return jQuery.validator.messages.oneDigit;
                }
                else if (!(/^(?=.*[@#$%&!_=*-])/.test(password))) {
                    return jQuery.validator.messages.oneSpecialCharacter;
                }
                return false;
            });

            $('#reg_btn').on('click', function (){
                validateRegistration();
            });

            $('input[name="is_org"]').change(togglePersonal);
            togglePersonal(1);
        });
    </script>
@endpush
