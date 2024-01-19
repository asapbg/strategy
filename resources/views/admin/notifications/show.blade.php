@extends('layouts.admin')

@section('content')
<section class="content">
    <div class="container-fluid">
        <div class="card">
            @if(isset($itemUrl) && !empty($itemUrl))
                <div class="card-header with-border">
                    <h3 class="card-title">{{ $itemUrl['name'] }}</h3>
                </div>
            @endif
            <div class="card-body">
                {!! $notification->data['message'] !!}
                @if(isset($itemUrl) && !empty($itemUrl))
                    <a href="{{ $itemUrl['route'] }}" class="main-color d-block mt-2 "><i class="fas fa-link main-color fs-14 mr-1"></i>{{ $itemUrl['name'] }}</a>
                @endif
            </div>
            <div class="card-footer">
                <a href="{{ route('admin.user.notifications') }}" class="btn btn-primary">{{ __('custom.back') }}</a>
            </div>
        </div>
    </div>
</section>
@endsection


