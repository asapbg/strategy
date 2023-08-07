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
       <div class="text-center">
           <h4>Успешно запазен формуляр!</h4>
           <a href="{{ route('impact_assessment.pdf', ['form' => $formName, 'inputId' => $inputId]) }}" class="btn btn-default">
               {{ __('forms.print_pdf') }}
           </a>
       </div>
    </div>
  </section>
@endsection