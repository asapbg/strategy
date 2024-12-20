@extends('layouts.site')

@section('pageTitle', __("forms.$formName"))

@section('content')
<section class="public-page" style="min-height: 300px; margin-top: 200px;">
    <div class="container">
       <div class="text-center mt-5">
           <h4 class="mb-5">{{ __('custom.forms.success') }}</h4>
           <div class="mb-2">
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
           @if($formName == 'form2')
           <p>
            <a href="{{ route('impact_assessment.form', ['form' => 'form3']) }}" class="btn btn-primary">
                {{ __('forms.form3') }}
            </a>
           </p>
           @endif
       </div>
    </div>
  </section>
@endsection
