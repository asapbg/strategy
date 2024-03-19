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

                        <div class="row mb-4">
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label class="col-12 control-label" for="institution_id">
                                        {{ trans_choice('custom.importers', 1) }}
                                    </label>
                                    <div class=" col-12 d-flex flex-row">
                                        <div class="input-group">
                                            @php($itemInstitutions = $item && $item->id && $item->institutions->count() ? $item->institutions->pluck('id')->toArray() : [])
                                            <select class="form-control form-control-sm select2 @error('institution_id') is-invalid @enderror" name="institution_id[]" id="institution_id" multiple>
                                                <option >---</option>
                                                @if(isset($institutions) && $institutions->count())
                                                    @foreach($institutions as $option)
                                                        <option value="{{ $option->value }}" @if(in_array($option->value, old('institution_id', $itemInstitutions))) selected @endif
                                                        data-level="{{ $option->level }}" data-foa="{{ $option->foa }}">{{ $option->name }}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                        <button type="button" class="btn btn-primary ml-1 pick-institution"
                                                data-title="{{ trans_choice('custom.institutions',2) }}"
                                                data-url="{{ route('modal.institutions').'?select=1&multiple=1&admin=1&dom=institution_id' }}">
                                            <i class="fas fa-list"></i>
                                        </button>
                                    </div>
                                    @error('institution_id')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                    @enderror
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
