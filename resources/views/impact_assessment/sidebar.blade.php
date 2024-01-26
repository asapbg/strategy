<div class="col-lg-2 col-md-4 side-menu py-5 mt-1" style="background:#f5f9fd;">
    <div class="left-nav-panel" style="background: #fff !important;">
        <div class="flex-shrink-0 p-2">
            <ul class="list-unstyled">
                <ul class="list-unstyled mb-1">
                    <a class="btn-toggle pe-auto align-items-center rounded ps-2 text-decoration-none cursor-pointer fs-18 dark-text fw-600"
                       data-bs-toggle="collapse" data-bs-target="#home-collapse" aria-expanded="true">
                        <i class="fa-solid fa-bars me-2 mb-2"></i> {{ __('custom.home') }}
                    </a>
                    <hr class="custom-hr">
                    <div class="collapse show mt-3" id="home-collapse">
                        <ul class="btn-toggle-nav list-unstyled fw-normal px-2 pb-1 small">
                            <li class="mb-2">
                                <a href="{{ route('impact_assessment.index') }}" class="@if(request()->route()->getName() == 'impact_assessment.index') active-item-left text-white p-1 @else link-dark @endif text-decoration-none">
                                    {{ __('custom.base_information') }}
                                </a>
                            </li>
                            <li class="mb-2 @if(request()->route()->getName() == 'impact_assessment.forms') active-item-left p-1 @endif">
                                <a href="{{ route('impact_assessment.forms') }}" class="link-dark text-decoration-none">{{ __('site.impact_assessments_forms_and_docs') }}</a>
                            </li>
                            <ul class="btn-toggle-nav list-unstyled fw-normal px-2 pb-1 mb-2">
                                <ul class="list-unstyled ps-3">
                                    <hr class="custom-hr">
                                    <li class="my-2 @if(str_contains(url()->current(), 'impact_assessments/form1')) active-item-left p-1 @endif">
                                        <a href="{{ route('impact_assessment.form', ['form' => 'form1']) }}" class=" text-decoration-none link-dark">{{ __('custom.form1_short') }}</a></li>
                                    <hr class="custom-hr">
                                    <li class="my-2 @if(str_contains(url()->current(), 'impact_assessments/form2')) active-item-left p-1 @endif">
                                        <a href="{{ route('impact_assessment.form', ['form' => 'form2']) }}" class=" p-1 text-decoration-none link-dark">{{ __('custom.form2_short') }}</a>
                                    </li>
                                    <hr class="custom-hr">
                                    <li class="my-2 @if(str_contains(url()->current(), 'impact_assessments/form3')) active-item-left p-1 @endif">
                                        <a href="{{ route('impact_assessment.form', ['form' => 'form3']) }}" class=" p-1 text-decoration-none link-dark">{{ __('custom.form3_short') }}</a>
                                    </li>
                                    <hr class="custom-hr">
                                    <li class="my-2 @if(str_contains(url()->current(), 'impact_assessments/form4')) active-item-left p-1 @endif">
                                        <a href="{{ route('impact_assessment.form', ['form' => 'form4']) }}" class=" p-1 text-decoration-none link-dark">{{ __('custom.form4_short') }}</a>
                                    </li>
                                    <hr class="custom-hr">
                                </ul>
                            </ul>

                            <li class="mb-2 @if(request()->route()->getName() == 'impact_assessment.tools' || str_contains(url()->current(), 'impact_assessments/tools')) active-item-left p-1 @endif">
                                <a href="{{ route('impact_assessment.tools') }}" class="link-dark text-decoration-none">{{ __('site.impact_assessment.methods') }}</a>
                            </li>
                            <ul class="btn-toggle-nav list-unstyled fw-normal px-2 pb-1 mb-2">
                                <ul class="list-unstyled ps-3">
                                    <hr class="custom-hr">
                                    <li class="my-2 @if(str_contains(url()->current(), 'impact_assessments/tools/'.\App\Enums\CalcTypesEnum::STANDARD_COST->value)) active-item-left p-1 @endif">
                                        <a href="{{ route('impact_assessment.tools.calc', ['calc' => \App\Enums\CalcTypesEnum::STANDARD_COST->value]) }}" class=" text-decoration-none link-dark">{{ __('site.calc.'.\App\Enums\CalcTypesEnum::STANDARD_COST->value.'.title') }}</a></li>
                                    <hr class="custom-hr">
                                    <li class="my-2 @if(str_contains(url()->current(), 'impact_assessments/tools/'.\App\Enums\CalcTypesEnum::COSTS_AND_BENEFITS->value)) active-item-left p-1 @endif">
                                        <a href="{{ route('impact_assessment.tools.calc', ['calc' => \App\Enums\CalcTypesEnum::COSTS_AND_BENEFITS->value]) }}" class=" text-decoration-none link-dark">{{ __('site.calc.'.\App\Enums\CalcTypesEnum::COSTS_AND_BENEFITS->value.'.title') }}</a></li>
                                    <hr class="custom-hr">
                                    <li class="my-2 @if(str_contains(url()->current(), 'impact_assessments/tools/'.\App\Enums\CalcTypesEnum::COST_EFFECTIVENESS->value)) active-item-left p-1 @endif">
                                        <a href="{{ route('impact_assessment.tools.calc', ['calc' => \App\Enums\CalcTypesEnum::COST_EFFECTIVENESS->value]) }}" class=" text-decoration-none link-dark">{{ __('site.calc.'.\App\Enums\CalcTypesEnum::COST_EFFECTIVENESS->value.'.title') }}</a></li>
                                    <hr class="custom-hr">
                                    <li class="my-2 @if(str_contains(url()->current(), 'impact_assessments/tools/'.\App\Enums\CalcTypesEnum::MULTICRITERIA->value)) active-item-left p-1 @endif">
                                        <a href="{{ route('impact_assessment.tools.calc', ['calc' => \App\Enums\CalcTypesEnum::MULTICRITERIA->value]) }}" class=" text-decoration-none link-dark">{{ __('site.calc.'.\App\Enums\CalcTypesEnum::MULTICRITERIA->value.'.title') }}</a></li>
                                    <hr class="custom-hr">
                                </ul>
                            </ul>
                            <li class="mb-2">
                                <a href="" class="@if(request()->route()->getName() == 'sdfsfsd') active-item-left text-white p-1 @else link-dark @endif text-decoration-none">
                                    {{ __('custom.library') }}
                                </a>
                            </li>
{{--                            <li class="mb-2 @if(request()->route()->getName() == 'impact_assessment.tools' || str_contains(url()->current(), 'impact_assessments/tools')) active-item-left p-1 @endif"><a href="{{ route('impact_assessment.tools') }}" class="link-dark text-decoration-none">{{ __('site.impact_assessment.methods') }}</a></li>--}}
{{--                            <ul class="btn-toggle-nav list-unstyled fw-normal px-2 pb-1">--}}
{{--                                <ul class="list-unstyled ps-3">--}}
{{--                                    <hr class="custom-hr">--}}
{{--                                    <li class="my-2"><a href="#" class="link-dark  text-decoration-none">Калкулатор</a></li>--}}
{{--                                    <hr class="custom-hr">--}}
{{--                                </ul>--}}
{{--                            </ul>--}}

                            <li class="mb-2 @if(str_contains(url()->current(), '/executors')) active-item-left p-1 @endif">
                                <a href="{{ route('impact_assessment.executors') }}" class="link-dark text-decoration-none">
                                    {{ __('List of individuals and legal entities') }}
                                </a>
                            </li>
                        </ul>
                    </div>
                </ul>
            </ul>
        </div>
    </div>
</div>
