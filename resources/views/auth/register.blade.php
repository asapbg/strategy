@extends('layouts.site')

@section('pageTitle', __('custom.register'))

@section('content')
<div class="container" id="register">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <form method="POST" action="{{ route('register') }}">
                @csrf
                <div class="row mb-3">
                    <label for="is_org" class="col-md-4 col-form-label text-md-end">{{ __('validation.attributes.is_org') }} <span class="text-danger">*</span></label>

                    <div class="col-md-6">
                        <div class="btn-group user-select" role="group" aria-label="Basic radio toggle button group">
                            <input id="is_org1" class="btn-check form-control" type="radio" name="is_org" value="1" {{ old('is_org') == 1 ? 'checked' : '' }}>
                            <label class="btn btn-outline-primary" for="is_org1">Юридическо лице</label>

                            <input id="is_org2" class="btn-check form-control" type="radio" name="is_org" value="0" {{ old('is_org') == 0 ? 'checked' : '' }}>
                            <label class="btn btn-outline-primary" for="is_org2">Физическо лице</label>
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
                            <input id="org_name" type="text" class="form-control @error('org_name') is-invalid @enderror" name="org_name" value="{{ old('org_name') }}" autocomplete="org_name">
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
                                <input id="first_name" type="text" class="form-control @error('first_name') is-invalid @enderror" name="first_name" value="{{ old('first_name') }}" autocomplete="first_name">
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
                                <input id="last_name" type="text" class="form-control @error('last_name') is-invalid @enderror" name="last_name" value="{{ old('last_name') }}" autocomplete="last_name">
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
                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" autocomplete="email">
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
                    <div class="input-group">
                        <div class="flex-grow-1 form-floating">
                            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" autocomplete="new-password">
                            <label for="password">{{ __('validation.attributes.password') }} <span class="text-danger">*</span></label>
                        </div>
                        <span class="input-group-text"><i class="fas fa-lock"></i></span>
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
                            <input id="password-confirm" type="password" class="form-control" name="password_confirmation" autocomplete="new-password">
                            <label for="password-confirm" class="col-md-4 col-form-label text-md-end">{{ __('validation.attributes.password_confirm') }} <span class="text-danger">*</span></label>
                        </div>
                        <span class="input-group-text"><i class="fas fa-lock"></i></span>
                    </div>
                    @error('password_confirmation')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="mb-2">
                    <a href="{{ route('login') }}">
                        {{ __('auth.already_have_account') }}
                    </a>
                </div>

                <div class="row mb-0">
                    <div class="col-md-6 offset-md-4">
                        <button class="cstm-btn w-100 btn btn-lg rounded" type="submit">{{ __('custom.register') }}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function () {
        $('input[name="is_org"]').change(togglePersonal);
        togglePersonal();
    });
    function togglePersonal() {
        var show = $('input[name="is_org"]:checked').val() == 0;
        $('#part-org').toggle(!show);
        $('#part-person').toggle(show);
    }
</script>
@endpush
