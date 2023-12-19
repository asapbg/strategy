@extends('layouts.admin')

@section('content')

    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('admin.executors.update', $executor->id) }}" method="post" name="form" id="form">
                        @csrf

                        @php
                            $institution_id = (old('institution_id')) ? old('institution_id') : $executor->institution_id;
                        @endphp
                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label class="col-sm-12 control-label" for="institution_id">
                                        {{ __('Name of contractor') }}
                                    </label>
                                    <div class="col-md-12 col-sm-12 col-xs-12">
                                        <select class="form-control form-control-sm select2 @error('institution_id') is-invalid @enderror"
                                                name="institution_id" id="institution_id"
                                        >
                                            <option value="">---</option>
                                            @foreach($institutions as $institution)
                                                <option value="{{ $institution->id }}"
                                                        @if($institution_id == $institution->id) selected @endif
                                                >
                                                    {{ $institution->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            @foreach($languages as $lang)
                                @php
                                    $default = $lang['default'];
                                    $code = $lang['code'];
                                    $code_upper = mb_strtoupper($code);
                                    $translation = $executor->translations->where('locale', $code)->first();
                                @endphp
                                <div class="col-6">
                                    <div class="form-group">
                                        <label class="col-sm-12 control-label" for="executor_name_{{ $code }}">
                                            {{ __('Name of executor') }} ({{ $code_upper }})
                                            @if($default)<span class="required">*</span>@endif
                                        </label>
                                        <div class="col-md-12 col-sm-12 col-xs-12">
                                            <input type="text" name="executor_name_{{ $code }}" id="executor_name_{{ $code }}"
                                                   class="form-control @error("executor_name_$code"){{ 'is-invalid' }}@enderror"
                                                   value="{{ old("executor_name_$code") ?? $translation->executor_name }}">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-12 control-label" for="contract_subject_{{ $code }}">
                                            {{ __('custom.contract_subject') }} ({{ $code_upper }})
                                            @if($default)<span class="required">*</span>@endif
                                        </label>
                                        <div class="col-md-12 col-sm-12 col-xs-12">
                                            <textarea class="form-control summernote @error("contract_subject_$code"){{ 'is-invalid' }}@enderror"
                                                      name="contract_subject_{{ $code }}" id="contract_subject_{{ $code }}"
                                            >{{ old("contract_subject_$code") ?? $translation->contract_subject }}</textarea>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-12 control-label" for="services_description_{{ $code }}">
                                            {{ __('custom.services_description') }} ({{ $code_upper }})
                                            @if($default)<span class="required">*</span>@endif
                                        </label>
                                        <div class="col-md-12 col-sm-12 col-xs-12">
                                            <textarea class="form-control summernote @error("contract_subject_$code"){{ 'is-invalid' }}@enderror"
                                                      name="services_description_{{ $code }}" id="services_description_{{ $code }}"
                                            >{{ old("services_description_$code") ?? $translation->services_description }}</textarea>
                                            @error("services_description_$code")
                                            <div class="alert alert-danger mt-1">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-12 control-label" for="hyperlink_{{ $code }}">
                                            {{ __('Order information') }} ({{ $code_upper }})
                                            @if($default)<span class="required">*</span>@endif
                                        </label>
                                        <div class="col-md-12 col-sm-12 col-xs-12">
                                            <input type="text" name="hyperlink_{{ $code }}" id="hyperlink_{{ $code }}"
                                                   class="form-control @error("hyperlink_$code"){{ 'is-invalid' }}@enderror"
                                                   value="{{ old("hyperlink_$code") ?? $translation->hyperlink  }}">
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label class="col-sm-12 control-label" for="eik">
                                        {{ __('custom.eik') }}
                                    </label>
                                    <div class="col-md-12 col-sm-12 col-xs-12">
                                        <input type="text" name="eik" id="eik" class="form-control" value="{{ old('eik') ?? $executor->eik }}">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-12 control-label" for="contract_date">
                                        {{ __('Contract date') }}
                                    </label>
                                    <div class="col-md-12 col-sm-12 col-xs-12">
                                        <input type="text" name="contract_date" id="contract_date"
                                               class="form-control datepicker @error('contract_date'){{ 'is-invalid' }}@enderror"
                                               value="{{ old('contract_date') ?? displayDate($executor->contract_date )}}">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-12 control-label" for="price">
                                        {{ __('custom.price_with_vat') }}
                                    </label>
                                    <div class="col-md-12 col-sm-12 col-xs-12">
                                        <input type="text" name="price" id="price" class="form-control @error('price'){{ 'is-invalid' }}@enderror"
                                               value="{{ old('price') ?? $executor->price }}">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="col-md-6 col-md-offset-3">
                                        <button id="save" type="submit" class="btn btn-success">{{ __('custom.save') }}</button>
                                        <a href="{{ route('admin.executors.index') }}" class="btn btn-primary">{{ __('custom.cancel') }}</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection
