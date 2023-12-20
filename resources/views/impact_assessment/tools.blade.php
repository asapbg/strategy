@extends('layouts.site', ['fullwidth' => true])

@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                @include('impact_assessment.sidebar')
                <div class="col-lg-9  col-md-8 home-results home-results-two pris-list mt-5 mb-5">
                    @foreach(\App\Enums\CalcTypesEnum::values() as $v)
                        <div class="row mb-3 action-btn-wrapper">
                            <div class="col-12">
                                <h2>{{ __('site.calc.'.$v.'.title') }}</h2>
                                <hr class="custom-hr mb-5">
                            </div>
                            @if($loop->odd)
                                <div class="col-md-6">
                                    <div class="col-12">
                                        {!! __('site.calc.'.$v.'.description') !!}
                                    </div>
                                </div>
                                <div class="col-md-6 align-self-center">
                                    <a href="{{ route('impact_assessment.tools.calc', ['calc' => $v]) }}" class="box-link {{ \App\Enums\CalcTypesEnum::btnClass($v) }}  mb-4 px-4">
                                        <div class="info-box">
                                            <div class="icon-wrap">
                                                <i class="bi bi-folder-check text-light"></i>
                                            </div>
                                            <div class="link-heading">
                                            <span>
                                                {{ __('site.calc_of') }} {{ __('site.calc.'.$v.'.btn') }}
                                            </span>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            @else
                                <div class="col-md-6 align-self-center">
                                    <a href="{{ route('impact_assessment.tools.calc', ['calc' => $v]) }}" class="box-link {{ \App\Enums\CalcTypesEnum::btnClass($v) }}  mb-4 px-4">
                                        <div class="info-box">
                                            <div class="icon-wrap">
                                                <i class="bi bi-folder-check text-light"></i>
                                            </div>
                                            <div class="link-heading">
                                            <span>
                                                {{ __('site.calc_of') }} {{ __('site.calc.'.$v.'.btn') }}
                                            </span>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div class="col-md-6">
                                    <div class="col-12">
                                        {!! __('site.calc.'.$v.'.description') !!}
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>
@endsection
