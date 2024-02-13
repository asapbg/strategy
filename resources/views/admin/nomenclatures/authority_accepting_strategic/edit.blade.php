@extends('layouts.admin')

@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-body">
                    @php($storeRoute = route($storeRouteName, ['item' => $item]))
                    <form action="{{ $storeRoute }}" method="post" name="form" id="form">
                        @csrf
                        @if($item->id)
                            @method('PUT')
                        @endif
                        <input type="hidden" name="id" value="{{ $item->id ?? 0 }}">

                        <div class="row mb-4">
                            @include('admin.partial.edit_field_translate', ['field' => 'name', 'required' => true])
                        </div>

                        <div class="row">
                            <div class="col-md-6 col-12">
                                <div class="form-group">
                                    <label class="control-label" for="nomenclature_level_id">
                                        {{ trans_choice('custom.strategic_document_level', 1) }}
                                    </label>
                                    <div class="d-inline">
                                        <select id="nomenclature_level_id" name="nomenclature_level_id"
                                                class="form-control select2 form-control-sm @error('nomenclature_level_id'){{ 'is-invalid' }}@enderror"
                                        >
                                                <option value="" @if(old('nomenclature_level_id', '') == '') selected @endif></option>
                                            @foreach(\App\Enums\InstitutionCategoryLevelEnum::options() as $name => $value)
                                                <option value="{{ $value }}" @if(old('nomenclature_level_id', $item->id ? $item->nomenclature_level_id : '') == $value) selected @endif>
                                                    {{ __('custom.nomenclature_level.'.$name) }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('nomenclature_level_id')
                                        <div class="text-danger mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-6 col-md-offset-3">
                                <button id="save" type="submit" class="btn btn-success">{{ __('custom.save') }}</button>
                                <a href="{{ route($listRouteName) }}"
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
