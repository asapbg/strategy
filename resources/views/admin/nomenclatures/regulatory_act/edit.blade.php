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

                        @include('admin.partial.edit_single_translatable', ['field' => 'name', 'required' => true])
                        
                        <div class="form-group">
                            <label class="col-sm-12 control-label" for="regulatory_act_type_id">{{ trans_choice('custom.regulatory_act_types', 1) }}<span class="required">*</span></label>
                            <div class="col-12">
                                <select id="consultation-category-select" name="regulatory_act_type_id" class="form-control form-control-sm select2 @error('institution_level'){{ 'is-invalid' }}@enderror">
                                    @if(isset($regulatoryActTypes) && $regulatoryActTypes->count())
                                    @foreach($regulatoryActTypes as $row)
                                    <option value="{{ $row->id }}" @if(old('regulatory_act_type_id', ($item->id ? $item->regulatory_act_type_id : 0)) == $row->id) selected @endif data-id="{{ $row->id }}">{{ $row->name }}</option>
                                    @endforeach
                                    @endif
                                </select>
                                @error('regulatory_act_type_id')
                                <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-12 control-label" for="number">{{ __('validation.attributes.number') }} <span class="required">*</span></label>
                            <input type="number" id="number" name="number" class="form-control form-control-sm"
                                value="{{ old('number', ($item->id ? $item->number : '')) }}">
                        </div>
                        
                        @include('admin.partial.edit_single_translatable', ['field' => 'institution', 'required' => true])
                        
                        <div class="form-group row">
                            <div class="col-md-6 col-md-offset-3">
                                <button id="save" type="submit" class="btn btn-success">{{ __('custom.save') }}</button>
                                <a href="{{ route($listRouteName) }}"
                                   class="btn btn-primary">{{ __('custom.cancel') }}</a>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </section>
@endsection
