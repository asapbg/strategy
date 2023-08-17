@extends('layouts.site')

@section('pageTitle', trans_choice('custom.impact_assessment', 2))

@section('content')
<section class="public-page">
    <h5>
        <a href="{{ route('impact_assessment.form', ['form' => 'form1']) }}">
            {{ __('forms.form1') }}
        </a>
    </h5>
    <h5>
        Цялостна предварителна оценка на въздействието
    </h5>
    <ul>
        <li>
            <a href="{{ route('impact_assessment.form', ['form' => 'form2']) }}">
                {{ __('forms.form2') }}
            </a>
        </li>
        <li>
            <a href="{{ route('impact_assessment.form', ['form' => 'form3']) }}">
                {{ __('forms.form3') }}
            </a>
        </li>
    </ul>
    <h5>
    <a href="{{ route('impact_assessment.form', ['form' => 'form4']) }}">
        {{ __('forms.form4') }}
    </a>
    
</section>
@endsection