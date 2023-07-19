@extends('layouts.admin')

@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-body">
                    <p><b>{{ __('custom.content_in_language') }}</b></p>
                    @php($storeRoute = route($storeRouteName, ['item' => $item]))
                    <form action="{{ $storeRoute }}" method="post" name="form" id="form">
                        @csrf
                        
                        <input type="hidden" name="type" value="{{ $publicationType }}">
                        @include('admin.partial.edit_single_translatable', ['field' => 'title', 'required' => true])
                    
                        <div class="form-group">
                            <label class="col-sm-12 control-label" for="publication_category_id">{{ trans_choice('custom.publication_category', 1) }}<span class="required">*</span></label>
                            <div class="col-12">
                                <select id="publication_category_id" name="publication_category_id" class="form-control form-control-sm select2 @error('publication_category_id'){{ 'is-invalid' }}@enderror">
                                    @if(isset($publicationCategories) && $publicationCategories->count())
                                    @foreach($publicationCategories as $row)
                                    <option value="{{ $row->id }}" @if(old('publication_category_id', ($item->id ? $item->publication_category_id : 0)) == $row->id) selected @endif data-id="{{ $row->id }}">{{ $row->name }}</option>
                                    @endforeach
                                    @endif
                                </select>
                                @error('publication_category_id')
                                <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-12 control-label" for="event_date">{{ __('validation.attributes.event_date') }} <span class="required">*</span></label>
                            <input type="text" id="event_date" name="event_date" data-provide="datepicker" class="form-control form-control-sm @error('event_date'){{ 'is-invalid' }}@enderror"
                            value="{{ old('event_date', ($item->id ? $item->event_date : '')) }}">
                        </div>

                        @include('admin.partial.edit_single_translatable', ['field' => 'content', 'required' => true])
                        
                        <div class="form-group">
                            <label class="col-sm-12 control-label" for="highlighted">
                                <input type="checkbox" id="highlighted" name="highlighted" value="1"
                                @if ($item->highlighted) checked @endif
                                class="checkbox @error('highlighted'){{ 'is-invalid' }}@enderror">
                                {{ __('validation.attributes.highlighted_publication') }}
                            </label>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-12 control-label" for="active">
                                <input type="checkbox" id="active" name="active" value="1"
                                @if ($item->active) checked @endif
                                class="checkbox @error('active'){{ 'is-invalid' }}@enderror">
                                {{ __('validation.attributes.active') }}
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
                                <a href="{{ route('admin.publications.index') }}"
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
