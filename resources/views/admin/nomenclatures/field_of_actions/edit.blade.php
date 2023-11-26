@extends('layouts.admin')

@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-body">
                    <form
                        action="{{ route('admin.nomenclatures.field_of_actions.update', $item) }}"
                        method="post" name="form" id="form">
                        @csrf
                        <input type="hidden" name="id" value="{{ $item->id ?? 0 }}">

                        <div class="row mb-4">
                            @include('admin.partial.edit_field_translate', ['field' => 'name', 'required' => true])
                        </div>

                        <div class="row mb-3">
                            <div class="col-4">
                                <label for="icon_class">{{ __('validation.attributes.icon_class') }}<span class="required">*</span></label>
                                <input type="text" id="icon_class" name="icon_class"
                                       class="form-control form-control-sm @error('icon_class'){{ 'is-invalid' }}@enderror"
                                       value="{{ old(request()->offsetGet('icon_class') ?? '', $item->icon_class) }}"/>
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
