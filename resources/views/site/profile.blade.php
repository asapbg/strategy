@extends('layouts.site', ['fullwidth' => true])

@section('content')
    <div class="row">
        @include('auth.profile.menu')
        <div class="col-lg-10 right-side-content py-5">
            <div class="px-md-5 mb-3">
                <h2 class="mb-4">{{ $secondTitle }}</h2>
            </div>
            @include("auth.profile.$tab")
        </div>
    </div>

@endsection
