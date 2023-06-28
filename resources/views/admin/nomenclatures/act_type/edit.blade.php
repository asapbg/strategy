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

                        <div class="form-group row">
                            <div class="col-md-6 col-md-offset-3">
                                <button id="save" type="submit" class="btn btn-success">{{ __('custom.save') }}</button>
                                <a href="{{ route($listRouteName) }}"
                                   class="btn btn-primary">{{ __('custom.cancel') }}</a>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-12 control-label" for="adm_level">{{ trans_choice('custom.institution_level', 1) }}<span class="required">*</span></label>
                            <div class="col-12">
                                <select id="institution-level-select" name="institution_level_id" class="form-control form-control-sm select2 @error('institution_level'){{ 'is-invalid' }}@enderror">
                                    @if(isset($institutionLevels) && $institutionLevels->count())
                                        @foreach($institutionLevels as $row)
                                            <option value="{{ $row->id }}" @if(old('region', ($item->id ? $item->institution_level : 0)) == $row->id) selected @endif data-id="{{ $row->id }}">{{ $row->name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                                @error('institution_level')
                                <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </section>
@endsection
