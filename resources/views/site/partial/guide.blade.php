@extends('layouts.site')

@section('content')
    <section class="content container">
        @php
            $pdfUrl = asset(DIRECTORY_SEPARATOR.'help'.DIRECTORY_SEPARATOR.$file);
        @endphp
        @if(is_android())
            <a class="main-color text-decoration-none d-block my-5" href="{{ $pdfUrl }}">
                {!! fileIcon('application/pdf') !!} {{ __('site.user_guide') }}
            </a>
        @else
            <div class="card card-light mb-4">
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="row mt-3">
                                <embed src="{{ $pdfUrl }}" width="800px" height="2100px" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </section>
@endsection
