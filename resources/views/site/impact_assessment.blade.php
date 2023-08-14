@extends('layouts.site')

@section('pageTitle', trans_choice('custom.impact_assessment', 2))

@section('content')
  @include('impact_assessment.form')
@endsection