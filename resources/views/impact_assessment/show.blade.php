@extends('layouts.site')

@section('pageTitle', __("forms.$formName"))

@section('content')
<section class="public-page">
    <div class="container">
        @for($p=1; $p<=$steps; $p++)
        @include("form_partials.$formName.steps.step$p")
        @endfor
    </div>
</section>
@endsection