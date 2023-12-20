@extends('layouts.site', ['fullwidth' => true])

@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                @include('impact_assessment.sidebar')
                <div class="col-lg-9  col-md-8 home-results home-results-two pris-list mt-5 mb-5">
                    <div class="row">
                        <div class="col-12">
                            <h2>{{ __('site.calc_of') }} {{ __('site.calc.'.$type.'.title') }}</h2>
                            <hr class="custom-hr mb-5">
                        </div>
                    </div>
                    @include('impact_assessment.calcs.'.$type)
                </div>
            </div>
        </div>
    </section>
@endsection
