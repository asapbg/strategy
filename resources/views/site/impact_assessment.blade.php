@extends('layouts.site', ['fullwidth' => true])

@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                @include('impact_assessment.sidebar')
                <div class="col-lg-10  home-results home-results-two pris-list mb-5">
                    @include('impact_assessment.form')
                </div>
            </div>
        </div>
    </section>
@endsection
