@extends('layouts.admin')

@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-body">
                    <form
                        action="{{ route('admin.nomenclatures.field-of-actions.update', ['action' => $action->id]) }}"
                        method="post" name="form" id="form">
                        @csrf
                        <input type="hidden" name="id" value="{{ $action->id ?? 0 }}">

                        <div class="row mb-3">
                            <div class="col-4">
                                <label for="name_bg">{{ __('validation.attributes.name_bg') }}<span class="required">*</span></label>
                                <input type="text" id="name_bg" name="name_bg"
                                       class="form-control form-control-sm @error('name_bg'){{ 'is-invalid' }}@enderror"
                                       value="{{ old(request()->offsetGet('name_bg') ?? '', $action->name_bg) }}"/>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-4">
                                <label for="name_en">{{ __('validation.attributes.name_en') }}<span class="required">*</span></label>
                                <input type="text" id="name_en" name="name_en"
                                       class="form-control form-control-sm @error('name_en'){{ 'is-invalid' }}@enderror"
                                       value="{{ old(request()->offsetGet('name_en') ?? '', $action->name_en) }}"/>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-6 col-md-offset-3">
                                <button id="save" type="submit" class="btn btn-success">{{ __('custom.save') }}</button>
                                <a href="{{ route('admin.nomenclature.field_of_actions.index') }}"
                                   class="btn btn-primary">{{ __('custom.cancel') }}</a>
                            </div>
                        </div>

                        <br/>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection
