<div class="col-lg-2 side-menu pt-5 mt-1 pb-5" style="background:#f5f9fd;">
    <div class="left-nav-panel" style="background: #fff !important;">
        <div class="flex-shrink-0 p-2">
            <ul class="list-unstyled">
                <li class="mb-1">
                    <a class="btn-toggle pe-auto align-items-center rounded ps-2 text-decoration-none cursor-pointer fs-18 dark-text fw-600"
                       data-toggle="collapse" data-target="#home-collapse" aria-expanded="true">
                        <i class="fa-solid fa-bars me-2 mb-2"></i>{{ trans_choice('custom.advisory_boards', 2) }}
                    </a>
                    <hr class="custom-hr">
                    <div class="collapse show mt-3" id="home-collapse">
                        <ul class="btn-toggle-nav list-unstyled fw-normal px-2 pb-1 small">
                            <li class="mb-2">
                                <a href="{{ route('advisory-boards.index') }}" class="@if(request()->route()->getName() == 'advisory-boards.index') active-item-left text-white p-1 @else link-dark @endif text-decoration-none">
                                    {{ __('site.home_page') }}
                                </a>
                            </li>
                            <li class="mb-2">
                                <a href="{{ route('advisory-boards.view', ['item' => $item]) }}" class="@if(request()->route()->getName() == 'advisory-boards.view') active-item-left text-white p-1 @else link-dark @endif text-decoration-none">{{ __('custom.up_to_date_information') }}</a>
                            </li>
                            <li class="mb-2">
                                <a href="{{ route('advisory-boards.contacts', $item) }}" class="@if(str_contains(url()->current(),'contacts')) active-item-left text-white p-1 @else link-dark @endif text-decoration-none">
                                    {{ trans_choice('custom.contacts', 2) }}
                                </a>
                            </li>
                            <li class="mb-2 @if(str_contains(url()->current(),'view/archive')) active-item-left text-white p-1 @endif">{{ __('custom.archive') }}</li>
                            <ul class="btn-toggle-nav list-unstyled fw-normal px-2 pb-1 mb-2">
                                <ul class="list-unstyled ps-3">
                                    <hr class="custom-hr">
                                    <li class="my-2 @if(str_contains(url()->current(), 'view/archive/meetings')) active-item-left p-1 @endif">
                                        <a href="{{ route('advisory-boards.view.archive.meetings', ['item' => $item]) }}" class=" text-decoration-none link-dark">{{ __('custom.meetings_and_decisions') }}</a></li>
                                    <hr class="custom-hr">
                                    <li class="my-2 @if(str_contains(url()->current(), 'view/archive/work_programs')) active-item-left p-1 @endif">
                                        <a href="{{ route('advisory-boards.view.archive.work_programs', ['item' => $item]) }}" class=" p-1 text-decoration-none link-dark">{{ __('custom.work_programs') }}</a>
                                    </li>
                                    <hr class="custom-hr">
                                </ul>
                            </ul>
                            <li class="mb-2">
                                <a href="{{ route('advisory-boards.view.news', ['item' => $item]) }}" class="@if(in_array(request()->route()->getName(), ['advisory-boards.view.news', 'advisory-boards.view.news.details']) )) active-item-left text-white p-1 @else link-dark @endif text-decoration-none">
                                    {{ trans_choice('custom.news', 2) }}
                                </a>
                            </li>
                            @if(isset($customSections) && sizeof($customSections))
                                <hr class="custom-hr mb-2">
                                @foreach($customSections as $sectionId => $sectionName)
                                    <li class="mb-2">
                                        <a href="{{ route('advisory-boards.view.section', ['item' => $item, 'section' => $sectionId]) }}" class="@if(str_contains(url()->current(),'view/'.$sectionId.'/section')) active-item-left text-white p-1 @else link-dark @endif text-decoration-none">{{ $sectionName }}</a>
                                    </li>
                                @endforeach
                            @endif
                        </ul>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</div>
