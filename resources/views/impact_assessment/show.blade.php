@extends('layouts.site', ['fullwidth' => true])

@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                @include('impact_assessment.sidebar')
                <div class="col-lg-9 col-md-8  home-results home-results-two pris-list mt-5">
                    @for($p=1; $p<=$steps; $p++)
                    @include("form_partials.$formName.steps.step$p")
                    @endfor
                </div>
            </div>
        </div>
    </section>
@endsection
