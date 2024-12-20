@extends('layouts.site')

@section('content')
    <div class="container">
        <div class="row mt-3">
            <div class="col">
                <h4 class="form-title py-4">{{ __('site.contacts.message') }}</h4>

                <div class="mt-3">
                    {!! $notification->data['message'] !!}
                </div>
            </div>
        </div>
    </div>
@endsection
