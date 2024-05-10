@extends('layouts.site', ['fullwidth' => true])

@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                @include('impact_assessment.sidebar')
                <div class="col-lg-9  py-5  col-md-8 home-results home-results-two pris-list mb-5">
                    <h2 class="mb-5">{{ __('site.impact_assessment.forms_and_templates') }}</h2>
{{--                    <div class="row mb-5 action-btn-wrapper">--}}
{{--                        <h3>Калкулатор</h3>--}}
{{--                        <hr class="custom-hr mb-5">--}}
{{--                        <div class="col-12">--}}
{{--                            <p>При оценката на въздействието задължително се изчислява административният товар. Административният товар са разходите, наложени върху бизнеса, когато се спазват информационните задължения, произлизащи от правителствена разпоредба. Използвайте този калкулатор, за да оцените административния товар за всяка опция, която се разглежда в оценката на въздействието.</p>--}}
{{--                        </div>--}}
{{--                        <div class="col-md-6">--}}
{{--                            <a href="№" class="box-link gr-color-bgr mb-4 px-4">--}}
{{--                                <div class="info-box">--}}
{{--                                    <div class="icon-wrap">--}}
{{--                                        <i class="bi bi-calculator text-light"></i>--}}
{{--                                    </div>--}}
{{--                                    <div class="link-heading">--}}
{{--                                        <span>--}}
{{--                                            Калкулатор--}}
{{--                                        </span>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            </a>--}}
{{--                        </div>--}}
{{--                    </div>--}}
                    <div class="row mb-5 action-btn-wrapper">
{{--                        <h3>{{ trans_choice('custom.impact_assessment', 2) }}</h3>--}}
{{--                        <hr class="custom-hr mb-5">--}}
                        <div class="col-md-6">
                            <p class="fw-bold">{{ __('site.impact_assessment.forms.part.title') }}</p>
                            {!! __('site.impact_assessment.forms.part.description') !!}
                        </div>
                        <div class="col-md-6 align-self-center">
                            <a href="{{ route('impact_assessment.form', ['form' => 'form1']) }}" class="box-link navy-marine-bgr  mb-4 px-4">
                                <div class="info-box">
                                    <div class="icon-wrap">
                                        <i class="bi bi-folder-check text-light"></i>
                                    </div>
                                    <div class="link-heading">
                                        <span>
                                            {{ __('forms.form1') }}
                                        </span>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                    <div class="row mb-3 action-btn-wrapper">
                        <div class="col-md-6 align-self-center">
                            <a href="{{ route('impact_assessment.form', ['form' => 'form2']) }}" class="box-link gr-color-bgr mb-4 px-4">
                                <div class="info-box">
                                    <div class="icon-wrap">
                                        <i class="bi bi-folder-check text-light"></i>
                                    </div>
                                    <div class="link-heading">
                                        <span>
                                            {{ __('forms.form2') }}
                                        </span>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-6">
                            <p class="fw-bold">{{ __('site.impact_assessment.forms.whole.title') }}</p>
                            {!! __('site.impact_assessment.forms.whole.description') !!}
                        </div>
                    </div>
                    <div class="row mb-3 action-btn-wrapper">
                        <div class="col-md-6">
                            <p class="fw-bold">{{ __('site.impact_assessment.forms.report_whole.title') }}</p>
                            {!! __('site.impact_assessment.forms.report_whole.description') !!}
                        </div>
                        <div class="col-md-6 align-self-center">
                            <a href="{{ route('impact_assessment.form', ['form' => 'form3']) }}" class="box-link light-blue-bgr  mb-4 px-4">
                                <div class="info-box">
                                    <div class="icon-wrap">
                                        <i class="bi bi-folder-check text-light"></i>
                                    </div>
                                    <div class="link-heading">
                                        <span>
                                            {{ __('forms.form3') }}
                                        </span>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                    <div class="row mb-3 action-btn-wrapper">
                        <div class="col-md-6 align-self-center">
                            <a href="{{ route('impact_assessment.form', ['form' => 'form4']) }}" class="box-link dark-blue-bgr mb-4 px-4">
                                <div class="info-box">
                                    <div class="icon-wrap">
                                        <i class="bi bi-folder-check text-light"></i>
                                    </div>
                                    <div class="link-heading">
                                        <span>
                                            {{ __('forms.form4') }}
                                        </span>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-6">
                            <p class="fw-bold">{{ __('site.impact_assessment.forms.report_after.title') }}</p>
                            {!! __('site.impact_assessment.forms.report_after.description') !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
