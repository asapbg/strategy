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
                        <div class="row">
                        @include('admin.partial.edit_field_translate', ['field' => 'name', 'required' => true])
                        </div>
                        <div class="form-group row">
                            <div class="col-md-6 col-md-offset-3">
                                <button id="save" type="submit" class="btn btn-success">{{ __('custom.save') }}</button>
                                <a href="{{ route('admin.strategic_documents.institutions.index') }}"
                                class="btn btn-primary">{{ __('custom.cancel') }}</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection
