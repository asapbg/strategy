@extends('layouts.admin')

@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-body">
                    <p><b>{{ __('custom.content_in_language') }}</b></p>
                    @php($storeRoute = route($storeRouteName, ['item' => $item->id]))
                    <form action="{{ $storeRoute }}" method="post" name="form" id="form">
                        @csrf

                        @include('admin.partial.edit_single_translatable', ['field' => 'title', 'required' => true])

                        <div class="form-group">
                            <label class="col-sm-12 control-label" for="effective_from">{{ __('validation.attributes.effective_from') }} <span class="required">*</span></label>
                            <input type="text" id="effective_from" name="effective_from" data-provide="datepicker"
                                class="form-control form-control-sm @error('effective_from'){{ 'is-invalid' }}@enderror"
                                value="{{ old('effective_from', ($item->id ? $item->effective_from : '')) }}"
                                >
                        </div>
                        <div class="form-group">
                            <label class="col-sm-12 control-label" for="effective_to">{{ __('validation.attributes.effective_to') }} <span class="required">*</span></label>
                            <input type="text" id="effective_to" name="effective_to" data-provide="datepicker"
                                class="form-control form-control-sm @error('effective_to'){{ 'is-invalid' }}@enderror"
                                value="{{ old('effective_to', ($item->id ? $item->effective_to : '')) }}"
                                >
                        </div>

                        @include('admin.partial.edit_single_translatable', ['field' => 'description', 'required' => true])

                        <div class="form-group">
                            <label class="col-sm-12 control-label" for="active">
                                <input type="checkbox" id="active" name="active" value="1"
                                    @if ($item->active) checked @endif
                                    class="checkbox @error('active'){{ 'is-invalid' }}@enderror">
                                    {{ __('validation.attributes.active') }} <span class="required">*</span>
                            </label>
                        </div>
                        
                        <div class="form-group row">
                            <div class="col-md-6 col-md-offset-3">
                                <button id="save" type="submit" class="btn btn-success">{{ __('custom.save') }}</button>
                                <a href="{{ route('admin.consultations.operational_programs.index') }}"
                                class="btn btn-primary">{{ __('custom.cancel') }}</a>
                            </div>
                        </div>
                    </form>
                    
                    @include('admin.partial.attached_documents')
                </div>
            </div>
        </div>
    </section>
@endsection
