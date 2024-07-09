@extends('layouts.admin')

@section('content')

    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-body">

                <form action="{{ route('admin.users.profile.update', $user->id) }}" method="post" name="form" id="form">
                    @csrf
                    <input type="hidden" name="id" value="{{ $user->id }}">
                    <input type="hidden" name="user_type" value="{{ \App\Models\User::USER_TYPE_INTERNAL }}">
                    <input type="hidden" name="roles[]" value="{{ $user->roles()->first()->id }}">
{{--                    <div class="form-group">--}}
{{--                        <div class="col-12 pt-2">--}}
{{--                            <label class="control-label" for="username">--}}
{{--                                {{ __('validation.attributes.username') }}:--}}
{{--                            </label>--}}
{{--                            <b>{{ $user->username }}</b>--}}
{{--                            <input type="hidden" name="username" value="{{ $user->username}}">--}}
{{--                        </div>--}}
{{--                    </div>--}}

                    <div class="form-group">
                        <label class="col-sm-12 control-label" for="first_name">
                            {{ __('validation.attributes.first_name') }}<span class="required">*</span>
                        </label>
                        <div class="col-12">
                            <input type="text" id="first_name" name="first_name" class="form-control" value="{{ old('first_name', $user->first_name) }}">
                            @error('first_name')
                            <div class="alert alert-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-12 control-label" for="middle_name">
                            {{ __('validation.attributes.middle_name') }}
                        </label>
                        <div class="col-12">
                            <input type="text" id="middle_name" name="middle_name" class="form-control" value="{{ old('middle_name', $user->middle_name) }}">
                            @error('middle_name')
                            <div class="alert alert-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-12 control-label" for="last_name">
                            {{ __('validation.attributes.last_name') }}<span class="required">*</span>
                        </label>
                        <div class="col-12">
                            <input type="text" id="last_name" name="last_name" class="form-control" value="{{ old('last_name', $user->last_name) }}">
                            @error('last_name')
                            <div class="alert alert-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-12 control-label" for="email">
                            {{ __('validation.attributes.email') }}<span class="required">*</span>
                        </label>
                        <div class="col-12">
                            <input type="email" id="email" name="email" class="form-control" value="{{ old('email', $user->email) }}">
                            @error('email')
                            <div class="alert alert-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-12 control-label" for="password">{{ __('validation.attributes.password') }}</label>
                        <div class="col-12">
                            <input type="password" name="password" class="form-control">
                            <i>{{ __('auth.password_format') }}</i>
                            @error('password')
                            <div class="alert alert-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-12 control-label" for="password_confirmation">{{ __('validation.attributes.password_confirm') }}</label>
                        <div class="col-12">
                            <input type="password" name="password_confirmation" class="form-control">
                            @error('password_confirmation')
                            <div class="alert alert-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-md-6 col-md-offset-3">
                            <button id="save" type="submit" class="btn btn-success">{{ __('custom.save') }}</button>
                            <a href="{{ route('admin.users') }}"  class="btn btn-primary">{{ __('custom.cancel') }}</a>
                        </div>
                    </div>
                    <br/>
                </form>

                </div>
            </div>
        </div>
    </section>
@endsection
