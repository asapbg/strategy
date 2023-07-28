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
                        
                        <input type="hidden" name="type" value="{{ $pageType }}">

                        @include('admin.partial.edit_single_translatable', ['field' => 'title', 'required' => true])

                        @include('admin.partial.edit_single_translatable', ['field' => 'content', 'required' => true])
                        
                        <div class="form-group">
                            <label class="col-sm-12 control-label" for="highlighted">
                                <input type="checkbox" id="highlighted" name="highlighted" value="1"
                                @if ($item->highlighted) checked @endif
                                class="checkbox @error('highlighted'){{ 'is-invalid' }}@enderror">
                                {{ __('validation.attributes.highlighted_page') }}
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
                                <a href="{{ route('admin.pages.index') }}"
                                class="btn btn-primary">{{ __('custom.cancel') }}</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection
