@extends('layouts.admin')

@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-body">
                    <form
                        action="{{ route('admin.advisory-boards.nomenclature.field-of-actions.store') }}"
                        method="post" name="form" id="form">
                        @csrf
                        <input type="hidden" name="id" value="">

                        <div class="row mb-3">
                            <div class="col-4">
                                <label for="name_bg">{{ __('validation.attributes.name_bg') }}<span class="required">*</span></label>
                                <input type="text" id="name_bg" name="name_bg"
                                       class="form-control form-control-sm @error('name_bg'){{ 'is-invalid' }}@enderror"
                                       value="{{ old(request()->offsetGet('name_bg') ?? '', '') }}"/>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-4">
                                <label for="name_en">{{ __('validation.attributes.name_en') }}<span class="required">*</span></label>
                                <input type="text" id="name_en" name="name_en"
                                       class="form-control form-control-sm @error('name_en'){{ 'is-invalid' }}@enderror"
                                       value="{{ old(request()->offsetGet('name_en') ?? '', '') }}"/>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-4">
                                <label for="icon_class">{{ __('validation.attributes.icon_class') }}<span class="required">*</span></label>
                                <input type="text" id="icon_class" name="icon_class"
                                       class="form-control form-control-sm @error('icon_class'){{ 'is-invalid' }}@enderror"
                                       value="{{ old(request()->offsetGet('icon_class') ?? '', 'fas fa-certificate') }}"/>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-6 col-md-offset-3">
                                <button id="save" type="submit" class="btn btn-success">{{ __('custom.save') }}</button>
                                <a href="{{ route('admin.advisory-boards.nomenclature.field-of-actions.index') }}"
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
