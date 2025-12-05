@extends('layouts.site', ['fullwidth' => true])

@section('content')
    <div class="row py-5">
        <h2 class="mb-4">
            {{ $title }}
        </h2>
        @if(isset($links) && sizeof($links))
            @foreach($links as $row)
                <div class="col-md-6 mb-4">
                    <div class="custom-shadow p-3 d-flex align-items-center">
                        <img class="img-thumbnail me-4" style="width: auto; height: 50px;" src="{{ asset('images/'.$row['logo']) }}">
                        <a class="main-color d-block" href="{{ $row['url'] }}" target="_blank">
                            {{ $row['name'] }}
                        </a>
                    </div>
                </div>
            @endforeach
        @endif
    </div>
@endsection
