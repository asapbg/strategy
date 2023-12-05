@extends('layouts.admin')

@section('content')

    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('admin.executors.store') }}" method="post" name="form" id="form">
                        @csrf
                        <div class="form-group">

                            <div class="row">
                                @foreach($languages as $lang)
                                    @php
                                        $code_upper = mb_strtoupper($lang['code']);
                                        $contractor_name = isset(old('contractor_name')[$lang['code']]) ? old('contractor_name')[$lang['code']] : "";
                                        $executor_name = isset(old('executor_name')[$lang['code']]) ? old('executor_name')[$lang['code']] : "";
                                        $contract_subject = isset(old('contract_subject')[$lang['code']]) ? old('contract_subject')[$lang['code']] : "";
                                        $services_description = isset(old('services_description')[$lang['code']]) ? old('services_description')[$lang['code']] : "";
                                    @endphp
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label class="col-sm-12 control-label" for="contractor_name_{{ $lang['code'] }}">
                                                {{ __('Name of contractor') }} ({{ $code_upper }}) <span class="required">*</span>
                                            </label>
                                            <div class="col-md-12 col-sm-12 col-xs-12">
                                                <input type="text" name="contractor_name[{{ $lang['code'] }}]" id="contractor_name_{{ $lang['code'] }}"
                                                       class="form-control" value="{{ $contractor_name }}">
                                                @error('contractor_name.'.$lang['code'])
                                                <div class="alert alert-danger mt-1">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-sm-12 control-label" for="executor_name_{{ $lang['code'] }}">
                                                {{ __('Name of executor') }} ({{ $code_upper }}) <span class="required">*</span>
                                            </label>
                                            <div class="col-md-12 col-sm-12 col-xs-12">
                                                <input type="text" name="executor_name[{{ $lang['code'] }}]" id="executor_name_{{ $lang['code'] }}"
                                                       class="form-control" value="{{ $executor_name }}">
                                                @error('executor_name.'.$lang['code'])
                                                <div class="alert alert-danger mt-1">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-sm-12 control-label" for="contract_subject_{{ $lang['code'] }}">
                                                {{ __('custom.contract_subject') }} ({{ $code_upper }}) <span class="required">*</span>
                                            </label>
                                            <div class="col-md-12 col-sm-12 col-xs-12">
                                                <textarea class="summernote" name="contract_subject[{{ $lang['code'] }}]" id="contract_subject_{{ $lang['code'] }}"
                                                          class="form-control">{{ $contract_subject }}</textarea>
                                                @error('contract_subject.'.$lang['code'])
                                                <div class="alert alert-danger mt-1">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-sm-12 control-label" for="services_description_{{ $lang['code'] }}">
                                                {{ __('custom.services_description') }} ({{ $code_upper }}) <span class="required">*</span>
                                            </label>
                                            <div class="col-md-12 col-sm-12 col-xs-12">
                                                <textarea class="summernote" name="services_description[{{ $lang['code'] }}]" id="services_description_{{ $lang['code'] }}"
                                                          class="form-control" rows="10" style="height: auto">{{ $services_description }}</textarea>
                                                @error('services_description.'.$lang['code'])
                                                <div class="alert alert-danger mt-1">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <div class="row">
                                <div class="form-group">
                                    <label class="col-sm-12 control-label" for="eik">
                                        {{ __('custom.eik') }}
                                    </label>
                                    <div class="col-md-12 col-sm-12 col-xs-12">
                                        <input type="text" name="eik" id="eik" class="form-control" value="{{ old('eik') }}">
                                        @error('eik')
                                        <div class="alert alert-danger mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-12 control-label" for="contract_date">
                                        {{ __('Contract date') }}
                                    </label>
                                    <div class="col-md-12 col-sm-12 col-xs-12">
                                        <input type="text" name="contract_date" id="contract_date" class="form-control datepicker" value="{{ old('contract_date') }}">
                                        @error('contract_date')
                                        <div class="alert alert-danger mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-12 control-label" for="price">
                                        {{ __('custom.price_with_vat') }}
                                    </label>
                                    <div class="col-md-12 col-sm-12 col-xs-12">
                                        <input type="text" name="price" id="price" class="form-control" value="{{ old('price') }}">
                                        @error('price')
                                        <div class="alert alert-danger mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-3">
                                <button id="save" type="submit" class="btn btn-success">{{ __('custom.save') }}</button>
                                <a href="{{ route('admin.executors.index') }}" class="btn btn-primary">{{ __('custom.cancel') }}</a>
                            </div>
                        </div>
                        <br/>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection
