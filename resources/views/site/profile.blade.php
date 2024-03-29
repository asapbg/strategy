@extends('layouts.site', ['fullwidth' => true])

@section('content')
    <div class="row">
        @include('auth.profile.menu')
{{--    <div class="custom-card p-3 my-5">--}}
{{--        <nav>--}}
{{--            <ul class="nav nav-pills justify-content-center mb-3 profile-tabs">--}}
{{--                <li class="nav-item">--}}
{{--                    <a class="nav-link {{ !$tab || $tab == 'change_info' ? 'active' : '' }}" href="{{ route('profile') }}">--}}
{{--                    {{ __('custom.change_info') }}--}}
{{--                    </a>--}}
{{--                </li>--}}
{{--                <li class="nav-item">--}}
{{--                    <a class="nav-link {{ $tab == 'pc' ? 'active' : '' }}" href="{{ route('profile', ['tab' => 'pc']) }}">--}}
{{--                        {{ trans_choice('custom.public_consultations', 2) }}--}}
{{--                    </a>--}}
{{--                </li>--}}
{{--                <li class="nav-item">--}}
{{--                    <a class="nav-link {{ $tab == 'li' ? 'active' : '' }}" href="{{ route('profile', ['tab' => 'li']) }}">--}}
{{--                        {{ trans_choice('custom.legislative_initiatives', 2) }}--}}
{{--                    </a>--}}
{{--                </li>--}}
{{--                <li class="nav-item">--}}
{{--                    <a class="nav-link {{ $tab == 'form_inputs' ? 'active' : '' }}" href="{{ route('profile', ['tab' => 'form_inputs']) }}">--}}
{{--                    {{ __('custom.form_inputs') }}--}}
{{--                    </a>--}}
{{--                </li>--}}
{{--                <li class="nav-item">--}}
{{--                    <a class="nav-link {{ $tab == 'subscriptions' ? 'active' : '' }}" href="{{ route('profile', ['tab' => 'subscriptions']) }}">--}}
{{--                    {{ __('custom.subscriptions') }}--}}
{{--                    </a>--}}
{{--                </li>--}}
{{--            </ul>--}}
{{--        </nav>--}}
{{--        @include("auth.profile.$tab")--}}
{{--    </div>--}}
        <div class="col-lg-10 right-side-content py-5">
            <div class="px-md-5 mb-3">
                <h2>{{ $secondTitle }}</h2>
            </div>
            @include("auth.profile.$tab")
        </div>
    </div>

@endsection
