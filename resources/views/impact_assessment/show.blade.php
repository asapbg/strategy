@extends('layouts.site')

@section('content')

<section class="slider">
    <div class="container">
      <div class="row">
        <div class="col-md-12">
          <div class="slider-content">
            <div class="breadcrumbs">
              <a href="#">Начало</a> » <a href="#">Оценка на въздействието</a>
            </div>
            <div class="page-heading">
              <h1>
                Оценка на въздействието
              </h1>
            </div>
          </div>
        </div>
      </div>
    </div>
</section>

<section class="public-constultation">
    <div class="container">
        @for($p=1; $p<=$steps; $p++)
        @include("form_partials.$formName.steps.step$p")
        @endfor
    </div>
  </section>
@endsection