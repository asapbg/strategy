<div class="col-lg-2 side-menu pt-5 mt-1 pb-5" style="background:#f5f9fd;">
    <div class="left-nav-panel" style="background: #fff !important;">
        <div class="flex-shrink-0 p-2">
            <ul class="list-unstyled">
                <li class="mb-1">
                    <a class="btn-toggle pe-auto align-items-center rounded ps-2 text-decoration-none cursor-pointer fs-18 dark-text fw-600"
                       data-bs-toggle="collapse" data-bs-target="#home-collapse" aria-expanded="true">
                        <i class="fa-solid fa-bars me-2 mb-2"></i>{{ __('custom.open_government_partnership') }}
                    </a>
                    <hr class="custom-hr">
                    <div class="collapse show mt-3" id="home-collapse">
                        <ul class="btn-toggle-nav list-unstyled fw-normal px-2 pb-1 small">
                            <li @class(['mb-2', 'p-1', 'active-item-left' => strstr(url()->current(), 'national-action-plans')])>
                                <a href="{{ route('ogp.national_action_plans') }}" class="link-dark text-decoration-none">{{ __('custom.national_action_plans') }}</a>
                            </li>
                            <li @class(['mb-2', 'p-1', 'active-item-left' => false])>
                                <a href="#" class="link-dark text-decoration-none">{{ __('custom.evaluation_implementation_action_plans_monitoring') }}</a>
                            </li>
                            <li @class(['mb-2', 'p-1', 'active-item-left' => strstr(url()->current(), 'develop-a-new-action-plans') ])>
                                {{ __('custom.develop_new_action_plan') }}
{{--                                <a href="{{ route('ogp.develop_new_action_plans') }}" class="link-dark text-decoration-none">{{ __('custom.develop_new_action_plan') }}</a>--}}
                            </li>
                            <li @class(['mb-2', 'p-1', 'active-item-left' => false])>
                                <a href="#" class="link-dark text-decoration-none">{{ __('custom.ogp_forum') }}</a>
                            </li>
                            <li @class(['mb-2', 'p-1', 'active-item-left' => false])>
                                <a href="#" class="link-dark text-decoration-none">{{ __('custom.news_events') }}</a>
                            </li>
                        </ul>
                    </div>
                </li>
                <hr class="custom-hr">
                <img src="/img/ogp-img.png" class="img-fluid rounded mt-2" alt="OGP">
            </ul>
        </div>
    </div>
</div>
