<div class="col-lg-2 side-menu pt-2 mt-1 pb-2" style="background:#f5f9fd;">
    <div class="left-nav-panel" style="background: #fff !important;">
        <div class="flex-shrink-0 p-2">
            <ul class="list-unstyled">
                <li class="mb-1">
                    <a class="btn-toggle pe-auto align-items-center rounded ps-2 text-decoration-none cursor-pointer fs-5 dark-text fw-600"
                       data-toggle="collapse" data-target="#home-collapse" aria-expanded="true">
                        <i class="fa-solid fa-bars me-2 mb-2"></i>{{ __('custom.home') }}
                    </a>
                    <hr class="custom-hr">
                    <div class="collapse show mt-3" id="home-collapse">
                        <ul class="btn-toggle-nav list-unstyled fw-normal px-2 pb-1 small">
                            <li class="mb-2 @if(str_contains(url()->current(),'legislative-initiatives')) active-item-left text-white p-1 @endif">
                                {{ __('custom.legislative_initiatives') }}
                            </li>
                            <ul class="btn-toggle-nav list-unstyled fw-normal px-2 pb-1 mb-2">
                                <ul class="list-unstyled ps-3">
                                    <hr class="custom-hr">
                                    <li class="my-2 @if(request()->route()->getName() == 'legislative_initiatives.info')) active-item-left p-1 @endif">
                                        <a href="{{ route('legislative_initiatives.info') }}" class="link-dark  text-decoration-none">{{ __('site.base_info') }}</a>
                                    </li>
                                    <hr class="custom-hr">
                                    <li class="my-2 @if(str_contains(url()->current(),'legislative-initiatives') && request()->route()->getName() != 'legislative_initiatives.info')) active-item-left p-1 @endif">
                                        <a href="{{ route('legislative_initiatives.index') }}" class="link-dark  text-decoration-none">{{ __('site.all_legislative_initiative') }}</a>
                                    </li>
                                </ul>
                            </ul>
                            <li class="mb-2 @if(str_contains(url()->current(),'polls') || str_contains(url()->current(),'poll')) active-item-left p-1 @endif"><a href="{{ route('poll.index') }}" class="link-dark text-decoration-none" title="{{ trans_choice('custom.polls', 2) }}">{{ trans_choice('custom.polls', 2) }}</a></li>
                            <li class="mb-2 @if(str_contains(url()->current(),'ogp')) active-item-left text-white p-1 @endif">
                                {{ __('custom.open_government_partnership') }}
                            </li>
                            <ul class="btn-toggle-nav list-unstyled fw-normal px-2 pb-1 mb-2">
                                <ul class="list-unstyled ps-3">
                                    <hr class="custom-hr">
                                    <li class="my-2 @if(str_contains(url()->current(),'ogp/information')) active-item-left p-1 @endif">
                                        <a href="{{ route('ogp.info') }}" class="link-dark  text-decoration-none">{{ __('site.base_info') }}</a>
                                    </li>
                                    <hr class="custom-hr">


                                    <li class="my-2 @if(str_contains(url()->current(),'national-action-plans')) active-item-left p-1 @endif">
                                        <a href="{{ route('ogp.national_action_plans') }}" class="link-dark  text-decoration-none">{{ __('custom.national_action_plans') }}</a>
                                    </li>
                                    @if(isset($nationalPlans) && sizeof($nationalPlans))
                                        <ul class="btn-toggle-nav list-unstyled fw-normal px-2 pb-1 mb-2">
                                            <ul class="list-unstyled ps-3">
                                                <hr class="custom-hr">
                                                @foreach($nationalPlans as $plan)
                                                    <li class="@if((!$plan['old'] && str_contains(url()->current(), 'national-action-plans/'.($plan['id'] ?? 0))) || ($plan['old'] && str_contains(url()->current(), 'national-action-plans/old/'.($plan['id'] ?? 0)))) active-item-left p-1 @else my-2 @endif">
                                                        <a href="{{ $plan['url'] }}" class=" text-decoration-none link-dark">{{ $plan['label'] }}</a></li>
                                                @endforeach
                                            </ul>
                                        </ul>
                                    @else
                                        <hr class="custom-hr">
                                    @endif
                                    @if(isset($developPlan) && $developPlan)
                                        <li class="my-2 @if(str_contains(url()->current(),'ogp/develop-a-new-action-plan') || str_contains(url()->current(),'ogp/develop-a-new-action-plans')) active-item-left p-1 @endif">
                                            <a href="{{ route('ogp.develop_new_action_plans') }}" class="link-dark  text-decoration-none">{{ __('custom.develop_new_action_plan') }}</a>
                                        </li>
                                    @endif
                                    <hr class="custom-hr">
                                    <li class="my-2">
                                        <a href="{{ route('ogp.forum') }}" class="@if(str_contains(url()->current(),'forum')) active-item-left text-white p-1 @else link-dark @endif text-decoration-none">{{ __('custom.ogp_forum') }}</a>
                                    </li>
                                    <hr class="custom-hr">
                                    @if(isset($library) && $library->count())
                                        <li class="my-2 @if(str_contains(url()->current(), 'ogp/library')) active-item-left text-white p-1 @endif">
                                            {{ __('custom.library') }}
                                        </li>
                                        <ul class="btn-toggle-nav list-unstyled fw-normal px-2 pb-1 mb-2">
                                            <ul class="list-unstyled ps-3">
                                                <hr class="custom-hr">
                                                @foreach($library as $page)
                                                    <li class="my-2 @if(str_contains(url()->current(), 'ogp/library/'.$page->slug)) active-item-left p-1 @endif">
                                                        <a href="{{ route('ogp.library.view', ['slug' => $page->slug]) }}" class=" text-decoration-none link-dark">{{ $page->name }}</a></li>
                                                @endforeach
                                            </ul>
                                        </ul>
                                    @endif
                                    <li class="mb-2">
                                        <a href="{{ route('ogp.news') }}" class="@if(str_contains(url()->current(),'news')) active-item-left text-white p-1 @else link-dark @endif text-decoration-none">
                                            {{ trans_choice('custom.news', 2) }}
                                        </a>
                                    </li>
                                    <hr class="custom-hr">
                                    <li class="my-2">
                                        <a href="{{ route('ogp.events') }}" class="@if(str_contains(url()->current(),'events')) active-item-left text-white p-1 @else link-dark @endif text-decoration-none">{{ trans_choice('custom.events', 2) }}</a>
                                    </li>
                                    <hr class="custom-hr">
                                    <li class="my-2 @if(request()->route()->getName() == 'ogp.contacts') active-item-left p-1 @endif">
                                        <a href="{{ route('ogp.contacts') }}" class="link-dark text-decoration-none">{{ trans_choice('custom.contacts', 2) }}</a>
                                    </li>
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
