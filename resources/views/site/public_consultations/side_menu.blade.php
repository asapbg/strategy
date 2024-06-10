<div class="col-lg-2 side-menu pt-2 mt-1 pb-2" style="background:#f5f9fd;">
    <div class="left-nav-panel" style="background: #fff !important;">
        <div class="flex-shrink-0 p-2">
            <ul class="list-unstyled">
                <li class="mb-1">
                    <a class="btn-toggle pe-auto align-items-center rounded ps-2 text-decoration-none cursor-pointer fs-18 dark-text fw-600"
                       data-toggle="collapse" data-target="#home-collapse" aria-expanded="true">
                        <i class="fa-solid fa-bars me-2 mb-2"></i>{{ trans_choice('custom.public_consultations', 2) }}
                    </a>
                    <hr class="custom-hr">
                    <div class="collapse show mt-3" id="home-collapse">
                        <ul class="btn-toggle-nav list-unstyled fw-normal px-2 pb-1 small">
                            <li class="mb-2">
                                <a href="{{ route('public_consultation.index') }}" class="@if(str_contains(url()->current(),'public-consultations') && !str_contains(url()->current(),'public-consultations/reports')) active-item-left text-white p-1 @else link-dark @endif text-decoration-none">
                                    {{ __('site.pc_all') }}
                                </a>
                            </li>
                            <li class="mb-2 @if(str_contains(url()->current(),'public-consultations/reports')) active-item-left text-white p-1 @endif">{{ __('site.strategic_document.all_documents_report') }}</li>
                            <ul class="btn-toggle-nav list-unstyled fw-normal px-2 pb-1 mb-2">
                                <ul class="list-unstyled ps-3">
                                    <hr class="custom-hr">
                                    <li class="my-2 @if(request()->route()->getName() == 'public_consultation.report.simple') active-item-left p-1 @endif">
                                        <a href="{{ route('public_consultation.report.simple') }}" class=" text-decoration-none link-dark">{{ __('custom.pc_reports.standard') }}</a></li>
                                    <hr class="custom-hr">
                                    <li class="my-2 @if(request()->route()->getName() == 'public_consultation.report.field_of_actions') active-item-left p-1 @endif">
                                        <a href="{{ route('public_consultation.report.field_of_actions') }}" class=" text-decoration-none link-dark">{{ __('custom.pc_reports.field_of_action') }}</a></li>
                                    <hr class="custom-hr">
                                    <li class="my-2 @if(request()->route()->getName() == 'public_consultation.report.field_of_actions.institution') active-item-left p-1 @endif">
                                        <a href="{{ route('public_consultation.report.field_of_actions.institution') }}" class=" text-decoration-none link-dark">{{ __('custom.pc_reports.field_of_action_institution') }}</a></li>
                                    <hr class="custom-hr">
                                    <li class="my-2 @if(request()->route()->getName() == 'public_consultation.report.institutions') active-item-left p-1 @endif">
                                        <a href="{{ route('public_consultation.report.institutions') }}" class=" text-decoration-none link-dark">{{ __('custom.pc_reports.institutions') }}</a></li>
                                    <hr class="custom-hr">
                                </ul>
                            </ul>
                        </ul>
                    </div>
                </li>
                <hr class="custom-hr">
            </ul>
        </div>
    </div>
</div>
