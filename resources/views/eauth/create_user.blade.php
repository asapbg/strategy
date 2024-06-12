@extends('layouts.site')

@section('content')
    <section class="content container w-25 pt-md-5 pt-2">
        <div class="card card-light mb-1">
            <div class="card-header app-card-header py-1 pb-0">
                <h4 class="fs-5">{{ __('eauth.with_e_auth') }}</h4>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('eauth.user.create') }}">
                    @csrf
{{--                    <input type="hidden" name="person_identity" value="{{ $userInfo['person_identity'] ?? '' }}">--}}
                    <input type="hidden" name="company_identity" value="{{ $userInfo['company_identity'] ?? '' }}">
                    <input type="hidden" name="org_name" value="{{ $userInfo['org_name'] ?? '' }}">
{{--                    <input type="hidden" name="first_name" value="{{ $userInfo['first_name'] ?? '' }}">--}}
{{--                    <input type="hidden" name="middle_name" value="{{ $userInfo['middle_name'] ?? '' }}">--}}
{{--                    <input type="hidden" name="last_name" value="{{ $userInfo['last_name'] ?? '' }}">--}}
{{--                    <input type="hidden" name="phone" value="{{ $userInfo['phone'] ?? '' }}">--}}
                    <div class="row">
                        @if(isset($validationErrors) && sizeof($validationErrors))
                            <p class="text-danger fs-6">
                                @foreach($validationErrors as $err)
                                    <span class="d-inline-block mb-2">{{ $err[0] }}</span>
                                @endforeach
                            </p>
                        @endif
                    </div>
                    @foreach(['first_name', 'middle_name', 'last_name', 'phone', 'person_identity', 'email'] as $v)
                        @php
                            $defaultValue = old($v, $userInfo[$v] ?? '');
                            if($v == 'email') {
                              $type = 'email';
                            } else {
                              $type = 'text';
                            }
                        @endphp
                        <div class="row mb-3">
                            <input type="hidden" name="{{ $v }}" value="{{ $defaultValue }}">
                            <label for="email" class="col-md-4 col-form-label text-md-end">{{ __('custom.'.$v) }}</label>
                            <div class="col-md-6">
                                <input id="{{ $v }}" type="{{ $type }}" class="form-control @error(isset($validationErrors) && isset($validationErrors[$v])) is-invalid @enderror" @if($v == 'email') name="{{ $v }}" @endif value="{{ $defaultValue }}" @if($v != 'email') readonly disabled @endif>
                            </div>
                        </div>
                    @endforeach
                    <div class="row">
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary mt-3">{{ __('eauth.register_with_this_mail') }}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>
@endsection
