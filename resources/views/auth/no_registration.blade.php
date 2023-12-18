@extends('layouts.site')

@section('pageTitle', __('custom.register'))

@section('content')
<div class="container" id="register">
    <div class="row justify-content-center">
        <div class="col-md-6 register-form p-4" style="min-height: 300px;">
            <div class="d-flex flex-column text-center align-items-center justify-content-center" style="min-height: 300px;">
                <h2 class="fs-3 mb-3 text-center">{{ __('site.registration_in_system') }}</h2>
                {{ __('site.registration_disabled') }}
            </div>
        </div>
    </div>
</div>
@endsection

