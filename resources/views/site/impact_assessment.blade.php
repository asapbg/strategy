@extends('layouts.site', ['fullwidth' => true])

@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                @include('impact_assessment.sidebar')
                <div class="col-lg-10 col-md-8 right-side-content">
                    @include('impact_assessment.form')
                </div>
            </div>
        </div>
    </section>
@endsection
