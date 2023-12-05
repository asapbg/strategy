<div class="col-lg-2 col-md-4 side-menu py-5 mt-1" style="background:#f5f9fd;">
    <div class="left-nav-panel" style="background: #fff !important;">
        <div class="flex-shrink-0 p-2">
            <ul class="list-unstyled">
                <ul class="list-unstyled mb-1">
                    <a class="btn-toggle pe-auto align-items-center rounded ps-2 text-decoration-none cursor-pointer fs-5 dark-text fw-600"
                       data-bs-toggle="collapse" data-bs-target="#home-collapse" aria-expanded="true">
                        <i class="fa-solid fa-bars me-2 mb-2"></i> {{ __('custom.home') }}
                    </a>
                    <hr class="custom-hr">
                    <div class="collapse show mt-3" id="home-collapse">
                        <ul class="btn-toggle-nav list-unstyled fw-normal px-2 pb-1 small">
                            <li class="mb-2 @if(request()->route()->getName() == 'impact_assessment.index') active-item-left p-1 @endif"><a href="{{ route('impact_assessment.index') }}" class="link-dark text-decoration-none">Оценки</a></li>
                            <li class="mb-2"><a href="#" class="link-dark text-decoration-none">Инструменти</a></li>
                            <ul class="btn-toggle-nav list-unstyled fw-normal px-2 pb-1">
                                <ul class="list-unstyled ps-3">
                                    <hr class="custom-hr">
                                    <li class="my-2"><a href="#" class="link-dark  text-decoration-none">Калкулатор</a></li>
                                    <hr class="custom-hr">
                                </ul>
                            </ul>
                            <li class="mb-2 @if(request()->route()->getName() == 'impact_assessment.forms') active-item-left p-1 @endif">
                                <a href="{{ route('impact_assessment.forms') }}" class="link-dark text-decoration-none">Образци и форми</a></li>
                            <ul class="btn-toggle-nav list-unstyled fw-normal px-2 pb-1 mb-2">
                                <ul class="list-unstyled ps-3">
                                    <hr class="custom-hr">
                                    <li class="my-2 @if(str_contains(url()->current(), 'impact_assessments/form1')) active-item-left p-1 @endif">
                                        <a href="{{ route('impact_assessment.form', ['form' => 'form1']) }}" class=" text-decoration-none link-dark">Частична предварителна</a></li>
                                    <hr class="custom-hr">
                                    <li class="my-2 @if(str_contains(url()->current(), 'impact_assessments/form2')) active-item-left p-1 @endif">
                                        <a href="{{ route('impact_assessment.form', ['form' => 'form2']) }}" class=" p-1 text-decoration-none link-dark">Цялостна предварителна-резюме</a>
                                    </li>
                                    <hr class="custom-hr">
                                    <li class="my-2 @if(str_contains(url()->current(), 'impact_assessments/form3')) active-item-left p-1 @endif">
                                        <a href="{{ route('impact_assessment.form', ['form' => 'form3']) }}" class=" p-1 text-decoration-none link-dark">Цялостна предварителна-доклад</a>
                                    </li>
                                    <hr class="custom-hr">
                                    <li class="my-2 @if(str_contains(url()->current(), 'impact_assessments/form4')) active-item-left p-1 @endif">
                                        <a href="{{ route('impact_assessment.form', ['form' => 'form4']) }}" class=" p-1 text-decoration-none link-dark">Последваща</a>
                                    </li>
                                    <hr class="custom-hr">
                                </ul>
                            </ul>
                            <li class="mb-2 @if(str_contains(url()->current(), '/executors')) active-item-left p-1 @endif">
                                <a href="{{ route('executors.index') }}" class="link-dark text-decoration-none">
                                    {{ __('List of the preparers of evaluations under the ZNA') }}
                                </a>
                            </li>
                        </ul>
                    </div>
                </ul>
            </ul>
        </div>
    </div>
</div>
