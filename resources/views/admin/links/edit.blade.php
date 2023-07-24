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
                            <label class="col-sm-12 control-label" for="link_category_id">{{ trans_choice('custom.link_category', 1) }}<span class="required">*</span></label>
                            <div class="col-12">
                                <select id="link_category_id" name="link_category_id" class="form-control form-control-sm select2 @error('link_category_id'){{ 'is-invalid' }}@enderror">
                                    @if(isset($linkCategories) && $linkCategories->count())
                                    @foreach($linkCategories as $row)
                                    <option value="{{ $row->id }}" @if(old('link_category_id', ($item->id ? $item->link_category_id : 0)) == $row->id) selected @endif data-id="{{ $row->id }}">{{ $row->name }}</option>
                                    @endforeach
                                    @endif
                                </select>
                                @error('link_category_id')
                                <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        @include('admin.partial.edit_single_translatable', ['field' => 'title', 'required' => true])
                    
                        @include('admin.partial.edit_single_translatable', ['field' => 'text', 'required' => true])
                        
                        <div class="form-group">
                            <label class="col-sm-12 control-label" for="url">{{ __('validation.attributes.url') }} <span class="required">*</span></label>
                            <input type="text" id="url" name="url" class="form-control form-control-sm"
                                value="{{ old('url', ($item->id ? $item->document_number : '')) }}">
                        </div>

                        <div class="form-group">
                            <label class="col-sm-12 control-label" for="active">
                                <input type="checkbox" id="active" name="active" value="1"
                                    @if ($item->active) checked @endif
                                    class="checkbox @error('active'){{ 'is-invalid' }}@enderror">
                                    {{ __('validation.attributes.active') }} <span class="required">*</span>
                            </label>
                        </div>
                        @if ($item->id)
                        <div class="form-group">
                            <label class="col-sm-12 control-label" for="deleted">
                                <input type="checkbox" id="deleted" name="deleted" class="checkbox" value="1"
                                    @if ($item->deleted_at) checked @endif
                                >
                                {{ __('validation.attributes.deleted') }}
                            </label>
                        </div>
                        @endif
                        
                        <div class="form-group row">
                            <div class="col-md-6 col-md-offset-3">
                                <button id="save" type="submit" class="btn btn-success">{{ __('custom.save') }}</button>
                                <a href="{{ route('admin.links.index') }}"
                                class="btn btn-primary">{{ __('custom.cancel') }}</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection
