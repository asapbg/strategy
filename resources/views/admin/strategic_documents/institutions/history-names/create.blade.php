@extends('layouts.admin')

@section('content')

    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-body">

                    <form action="{{ route('admin.strategic_documents.institutions.history-name.store', $institution->id) }}" method="post" name="form" id="form">
                        @csrf

                        <div class="row">
                            <div class="form-group">
                                <label class="col-sm-12 control-label" for="name">
                                    {{ __('validation.attributes.name') }} <span class="required">*</span>
                                </label>
                                <div class="col-md-6">
                                    <input type="text" id="name" name="name"
                                           class="form-control form-control-sm @error('name'){{ 'is-invalid' }}@enderror"
                                           value="{{ old('name') }}"
                                    >
                                    @error('name')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-12 control-label" for="valid_from">
                                    {{ __('custom.valid_from') }} <span class="required">*</span>
                                </label>
                                <div class="col-md-3">
                                    <input type="text" id="valid_from" name="valid_from"
                                           class="form-control datepicker form-control-sm @error('valid_from'){{ 'is-invalid' }}@enderror"
                                           value="{{ old('valid_from') }}"
                                    >
                                    @error('valid_from')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-12 control-label" for="valid_till">
                                    {{ __('custom.valid_to') }}
                                </label>
                                <div class="col-md-3">
                                    <input type="text" id="valid_till" name="valid_till"
                                           class="form-control datepicker form-control-sm @error('valid_till'){{ 'is-invalid' }}@enderror"
                                           value="{{ old('valid_till') }}"
                                    >
                                    @error('valid_till')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="icheck-primary col-12">
                                    <input class="form-check-input" type="checkbox" name="current" id="current" {{ old('current') ? 'checked' : '' }}>
                                    <label class="form-check-label control-label" for="current">
                                        Текущо наименование
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-3">
                                <button id="save" type="submit" class="btn btn-success">{{ __('custom.save') }}</button>
                                <a href="{{ route('admin.strategic_documents.institutions.edit', $institution->id) }}" class="btn btn-primary">
                                    {{ __('custom.cancel') }}
                                </a>
                            </div>
                        </div>
                        <br/>
                    </form>

                </div>
            </div>
        </div>
    </section>
@endsection
