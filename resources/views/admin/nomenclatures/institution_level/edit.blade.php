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

                        <div class="row mb-3">
                            @include('admin.partial.edit_field_translate', ['field' => 'name', 'required' => true])
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="col-sm-12 control-label" for="nomenclature_level">{{ __('validation.attributes.nomenclature_level') }}<span class="required">*</span></label>
                                    <div class="col-12">
                                        <select id="nomenclature_level" name="nomenclature_level" class="form-control form-control-sm select2-no-clear @error('nomenclature_level'){{ 'is-invalid' }}@enderror">
                                            <option value="" @if(old('nomenclature_level', $item->id ? $item->nomenclature_level : 0) == 0) selected @endif>---</option>
                                            @foreach(\App\Enums\InstitutionCategoryLevelEnum::options() as $name => $val)
                                                <option @if(old('nomenclature_level', $item->id ? $item->nomenclature_level : 0) == $val) selected @endif value="{{ $val }}" >{{ __('custom.nomenclature_level.'.\App\Enums\InstitutionCategoryLevelEnum::keyByValue($val)) }}</option>
                                            @endforeach
                                        </select>
                                        @error('nomenclature_level')
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
