@extends('layouts.site')

@section('pageTitle', 'Оценка на въздействието')

@section('content')
<section class="public-page">
    <div class="container">
       <div class="text-center">
           <h4>Успешно запазен формуляр!</h4>
           <a href="{{ route('impact_assessment.pdf', ['form' => $formName, 'inputId' => $inputId]) }}" class="btn btn-primary">
               {{ __('forms.print_pdf') }}
           </a>
           <a href="{{ route('impact_assessment.show', ['form' => $formName, 'inputId' => $inputId]) }}" class="btn btn-primary">
               {{ __('forms.show_form_input') }}
           </a>
           <a href="{{ route('impact_assessment.form', ['form' => $formName]) }}" class="btn btn-primary">
               {{ __('forms.fill_again') }}
           </a>
       </div>
    </div>
  </section>
@endsection