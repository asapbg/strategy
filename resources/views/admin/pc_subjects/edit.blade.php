@extends('layouts.admin')

@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-body">
                    @php($storeRoute = route($storeRouteName, ['item' => $item]))
                    <form action="{{ $storeRoute }}" method="post" name="form" id="form">
                        @csrf
                        
                        <div class="form-group">
                            <label class="col-sm-12 control-label" for="type">{{ trans_choice('custom.type', 1) }}<span class="required">*</span></label>
                            <div class="col-12">
                                <select id="type" name="type" class="form-control form-control-sm select2 @error('type'){{ 'is-invalid' }}@enderror">
                                    @if(isset($types) && $types->count())
                                    @foreach($types as $key => $value)
                                    <option value="{{ $key }}" @if(old('type', ($item->id ? $item->type : 0)) == $key) selected @endif data-id="{{ $key }}">{{ $value }}</option>
                                        @endforeach
                                    @endif
                                </select>
                                @error('type')
                                <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        @include('admin.partial.edit_single_translatable', ['field' => 'contractor', 'required' => true])
                    
                        @include('admin.partial.edit_single_translatable', ['field' => 'executor', 'required' => true])

                        <div class="form-group">
                            <label class="col-sm-12 control-label" for="eik">{{ __('custom.eik') }} <span class="required">*</span></label>
                            <input type="text" id="eik" name="eik" class="form-control form-control-sm"
                                value="{{ old('eik', ($item->id ? $item->eik : '')) }}">
                        </div>

                        <div class="form-group">
                            <label class="col-sm-12 control-label" for="contract_date">{{ __('custom.contract_date') }} <span class="required">*</span></label>
                            <input type="text" id="contract_date" name="contract_date" data-provide="datepicker" class="form-control form-control-sm"
                                value="{{ old('contract_date', ($item->id ? $item->contract_date : '')) }}">
                        </div>

                        @include('admin.partial.edit_single_translatable', ['field' => 'objective', 'required' => true])
                        
                        @include('admin.partial.edit_single_translatable', ['field' => 'description', 'required' => true])

                        <div class="form-group">
                            <label class="col-sm-12 control-label" for="price">{{ __('custom.price_with_vat') }} <span class="required">*</span></label>
                            <input type="text" id="price" name="price" class="form-control form-control-sm"
                                value="{{ old('price', ($item->id ? $item->price : '')) }}">
                        </div>
                        
                        <div class="form-group row">
                            <div class="col-md-6 col-md-offset-3">
                                <button id="save" type="submit" class="btn btn-success">{{ __('custom.save') }}</button>
                                <a href="{{ route('admin.strategic_documents.index') }}"
                                class="btn btn-primary">{{ __('custom.cancel') }}</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection
