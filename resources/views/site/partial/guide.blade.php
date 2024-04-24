@extends('layouts.site')

@section('content')
    <section class="content container">
{{--        <div class="page-title mb-md-3 mb-2 px-5">--}}
{{--            <h3 class="b-1 text-center">{{ $pageTitle }}</h3>--}}
{{--        </div>--}}
        <div class="card card-light mb-4">
            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        <div class="row mt-3">
                            <embed src="{{ asset(DIRECTORY_SEPARATOR.'help'.DIRECTORY_SEPARATOR.$file) }}" width="800px" height="2100px" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
