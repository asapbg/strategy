@extends('layouts.site')

@section('pageTitle', trans_choice('custom.profiles', 1))

@section('content')
<div class="custom-card p-3">
    <nav>
        <ul class="nav nav-pills justify-content-center mb-3 profile-tabs">
            <li class="nav-item">
                <a class="nav-link {{ !$tab || $tab == 'change_info' ? 'active' : '' }}" href="{{ route('profile') }}">
                {{ __('custom.change_info') }}
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ $tab == 'form_inputs' ? 'active' : '' }}" href="{{ route('profile', ['tab' => 'form_inputs']) }}">
                {{ __('custom.form_inputs') }}
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ $tab == 'subscriptions' ? 'active' : '' }}" href="{{ route('profile', ['tab' => 'subscriptions']) }}">
                {{ __('custom.subscriptions') }}
                </a>
            </li>
        </ul>
    </nav>
    @include("auth.profile.$tab")
</div>



@endsection