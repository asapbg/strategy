@extends('layouts.admin')

@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('admin.nomenclatures.regions.update', $region) }}" method="post" name="form" id="form">
                        @csrf

                        <div class="row mb-3">
                            <div class="col-4">
                                <div class="col-12">
                                    <label for="nuts2_code">{{ __('validation.attributes.nuts2_code') }}</label>
                                    <input type="text" id="nuts2_code" name="nuts2_code"
                                           class="form-control form-control-sm @error('nuts2_code'){{ 'is-invalid' }}@enderror"
                                           value="{{ old('nuts2_code', $region->code) }}"
                                    >
                                </div>
                            </div>
                        </div>

                        <div class="row mb-4">
                            @include('admin.partial.edit_field_translate', ['field' => 'name', 'required' => true, 'item' => $region])
                        </div>

                        <div class="form-group row">
                            <div class="col-md-6 col-md-offset-3">
                                <button id="save" type="submit" class="btn btn-success">{{ __('custom.save') }}</button>
                                <a href="{{ route('admin.nomenclatures.regions.index') }}" class="btn btn-primary">{{ __('custom.cancel') }}</a>
                            </div>
                        </div>
                        <br/>
                    </form>

                </div>
            </div>
        </div>
    </section>
@endsection
