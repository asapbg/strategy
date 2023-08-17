@extends('layouts.site')

@section('pageTitle', $formName ? __("forms.$formName") : trans_choice('custom.impact_assessment', 2))

@section('content')
  @include('impact_assessment.form')
@endsection