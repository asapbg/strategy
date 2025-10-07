@extends('layouts.site')

@section('content')

    <section id="slider" class="home-slider">
        <div id="carouselExampleSlidesOnly" class="carousel slide  bgr-main " data-ride="carousel">
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <img class="d-block w-100" src="{{ asset('/img/ms-w-2023.jpg') }}" alt="First slide">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="centered-heading w-100">
                                <h1 class="text-light text-center" style="background: unset !important;">
                                    {{ __('site.welcome') }}
                                </h1>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>

    <section id="second-links" class="home-page-section">
        <div class="container">
{{--            <div class="row">--}}
{{--                <div class="col-md-12">--}}
{{--                    <h2 class="mb-0">{{ __('site.self_inform') }}</h2>--}}
{{--                </div>--}}
{{--            </div>--}}

            <div class="row">
                <div class="col-md-4 position-relative mt-3">
                    <div class="service-item  position-relative">
                        <a href="{{ route('lp.index') }}" title="{{ trans_choice('custom.legislative_program', 2) }}">
                            <div class="icon">
                                <i class="bi bi-shield-check"></i>
                            </div>
                            <h3>{{ trans_choice('custom.legislative_program', 2) }}</h3>
                        </a>
                    </div>
                </div>

                <div class="col-md-4 position-relative mt-3">
                    <div class="service-item  position-relative">
                        <a href="{{ route('op.index') }}" title="{{ trans_choice('custom.operational_programs', 2) }}">
                            <div class="icon">
                                <i class="bi bi-arrow-up-right-circle"></i>
                            </div>
                            <h3>{{ trans_choice('custom.operational_programs', 2) }}</h3>
                        </a>
                    </div>
                </div>

                <div class="col-md-4 position-relative mt-3">
                    <div class="service-item  position-relative">
                        <a href="{{ route('ogp.info') }}" title="{{ __('custom.ogp') }}">
                            <img height="68" class="rounded-5" style="margin-bottom: 20px;" src="{{ asset('images/ogp_s_logo_2.png') }}" alt="{{ __('custom.ogp') }}">
{{--                            <div class="icon">--}}
{{--                                <i class="bi bi-bounding-box-circles"></i>--}}
{{--                            </div>--}}
                            <h3>{{ __('custom.ogp') }}</h3>
                        </a>
                    </div>
                </div>
{{--                <div class="col-md-4 position-relative mt-3">--}}
{{--                    <div class="service-item  position-relative">--}}
{{--                        <a href="{{ route('strategy-documents.index') }}" title="{{ trans_choice('custom.strategic_documents', 2) }}">--}}
{{--                            <div class="icon">--}}
{{--                                <i class="bi bi-files"></i>--}}

{{--                            </div>--}}
{{--                            <h3>{{ trans_choice('custom.strategic_documents', 2) }}</h3>--}}
{{--                        </a>--}}
{{--                    </div>--}}
{{--                </div>--}}
            </div>

{{--            <div class="row">--}}
{{--                <div class="col-md-4 position-relative mt-3">--}}
{{--                    <div class="service-item  position-relative">--}}
{{--                        <a href="{{ route('impact_assessment.index') }}" title="{{ trans_choice('custom.impact_assessment', 2) }}">--}}
{{--                            <div class="icon">--}}
{{--                                <i class="bi bi-journal-plus"></i>--}}
{{--                            </div>--}}
{{--                            <h3>{{ trans_choice('custom.impact_assessment', 2) }}</h3>--}}
{{--                        </a>--}}
{{--                    </div>--}}
{{--                </div>--}}

{{--                <div class="col-md-4 position-relative mt-3">--}}
{{--                    <div class="service-item  position-relative">--}}
{{--                        <a href="{{ route('pris.index') }}" title="{{ __('custom.pris') }}">--}}
{{--                            <div class="icon">--}}
{{--                                <i class="bi bi-file-earmark-medical"></i>--}}
{{--                            </div>--}}
{{--                            <h3>{{ __('custom.pris') }}</h3>--}}
{{--                        </a>--}}
{{--                    </div>--}}
{{--                </div>--}}

{{--                <div class="col-md-4 position-relative mt-3">--}}
{{--                    <div class="service-item  position-relative">--}}
{{--                        <a href="{{ route('legislative_initiatives.index') }}" title="{{ __('custom.ogp') }}">--}}
{{--                            <div class="icon">--}}
{{--                                <i class="bi bi-bounding-box-circles"></i>--}}
{{--                            </div>--}}
{{--                            <h3>{{ __('custom.ogp') }}</h3>--}}
{{--                        </a>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}

        </div>
    </section>

    <section class="home-page-section public-constultation">
        <div class="container">
{{--            <div class="row mb-2">--}}
{{--                <div class="col-md-12">--}}
{{--                    <h2 class="mb-2">{{ __('site.participate') }}</h2>--}}
{{--                </div>--}}
{{--            </div>--}}
            <div class="row ">

                <div class="col-lg-6 col-md-12">
                    <a href="{{ route('public_consultation.index') }}" class="box-link gr-color-bgr mb-2">
                        <div class="info-box">
                            <div class="icon-wrap">
                                <i class="bi bi-check2-square text-light"></i>
                            </div>
                            <div class="link-heading">
                            <span>
                                {{ __('site.participate_in_pc') }}
                            </span>
                            </div>
                        </div>
                    </a>
                    <div class="home-results mt-2">

                        <div class=" item-holder-one">
                            <div class="row mb-2">
                                <div class="col-md-12">
                                    <h2 class="mb-2" style="font-size: 24px;">{{ __('site.last_pc') }}</h2>
                                    <p>
                                        {{ __('site.home.pc_description') }}
                                    </p>

                                </div>
                            </div>

                            <form action="{{ route('get-consultations') }}" id="consultations_form" class="ajax_search_form" method="GET">
                                <input type="hidden" name="search" value="true">
                                <input type="hidden" name="page" class="current_page" value="{{ $consultations->currentPage() }}">
                                <div class="row filter-results mb-2">
                                    <h3 style="font-size: 20px;">
                                        {{ __('custom.searching') }}
                                    </h3>

                                    <div class="col-md-12 mb-2 mt-2">
                                        <input type="text" class="form-control" name="pc_search_title"
                                               placeholder="{{ __('site.enter_word') }}"
                                               aria-label="{{ __('site.enter_word') }}" aria-describedby="basic-addon2"
                                        >
                                    </div>
                                </div>

                                <div class="row mb-5">
                                    <div class="col-md-4">
                                        <button class="btn rss-sub main-color search-btn" data-id="consultations">
                                            <i class="fas fa-search main-color"></i>{{ __('custom.searching') }}
                                        </button>
                                    </div>

                                    <div class="col-md-8">
                                        <div class="info-consul">
                                            <h4>
                                                {{ __('custom.total') }} <span id="consultations_total">{{ $consultations->total() }}</span> {{ mb_strtolower(trans_choice('custom.results', 2)) }}
                                            </h4>
                                        </div>
                                    </div>
                                </div>
                            </form>

                            <div id="consultations_results">
                                @includeIf('site.home.consultations')
                            </div>

                            <div class="row">
                                <div class="col-md-8">
                                    <a href="{{ route('public_consultation.index') }}" class="btn rss-sub main-color">
                                        {{ __('site.all_pc') }} <i class="fas fa-long-arrow-right main-color"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
{{--                <div class="col-lg-6 col-md-12">--}}
{{--                    <a href="{{ route('public_consultation.index') }}" class="box-link gr-color-bgr mb-2">--}}
{{--                        <div class="info-box">--}}
{{--                            <div class="icon-wrap">--}}
{{--                                <i class="bi bi-check2-square text-light"></i>--}}
{{--                            </div>--}}
{{--                            <div class="link-heading">--}}
{{--                            <span>--}}
{{--                                {{ __('site.participate_in_pc') }}--}}
{{--                            </span>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </a>--}}
{{--                    <div class="home-results mt-2">--}}

{{--                        <div class=" item-holder-one">--}}
{{--                            <div class="row mb-2">--}}
{{--                                <div class="col-md-12">--}}
{{--                                    <h2 class="mb-2" style="font-size: 24px;">{{ __('site.last_pc') }}</h2>--}}
{{--                                    <p>--}}
{{--                                        {{ __('site.home.pc_description') }}--}}
{{--                                    </p>--}}

{{--                                </div>--}}
{{--                            </div>--}}

{{--                            <form action="{{ route('get-consultations') }}" id="consultations_form" class="ajax_search_form" method="GET">--}}
{{--                                <input type="hidden" name="search" value="true">--}}
{{--                                <input type="hidden" name="page" class="current_page" value="{{ $consultations->currentPage() }}">--}}
{{--                                <div class="row filter-results mb-2">--}}
{{--                                    <h3 style="font-size: 20px;">--}}
{{--                                        {{ __('custom.searching') }}--}}
{{--                                    </h3>--}}

{{--                                    <div class="col-md-12 mb-2 mt-2">--}}
{{--                                        <input type="text" class="form-control" name="pc_search_title"--}}
{{--                                               placeholder="{{ __('site.enter_word') }}"--}}
{{--                                               aria-label="{{ __('site.enter_word') }}" aria-describedby="basic-addon2"--}}
{{--                                        >--}}
{{--                                    </div>--}}
{{--                                </div>--}}

{{--                                <div class="row mb-5">--}}
{{--                                    <div class="col-md-6">--}}
{{--                                        <button class="btn rss-sub main-color search-btn" data-id="consultations">--}}
{{--                                            <i class="fas fa-search main-color"></i>{{ __('custom.searching') }}--}}
{{--                                        </button>--}}
{{--                                    </div>--}}

{{--                                    <div class="col-md-6">--}}
{{--                                        <div class="info-consul">--}}
{{--                                            <h4>--}}
{{--                                                {{ __('custom.total') }} <span id="consultations_total">{{ $consultations->total() }}</span> {{ mb_strtolower(trans_choice('custom.results', 2)) }}--}}
{{--                                            </h4>--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            </form>--}}

{{--                            <div id="consultations_results">--}}
{{--                                @includeIf('site.home.consultations')--}}
{{--                            </div>--}}

{{--                            <div class="row">--}}
{{--                                <div class="col-md-8">--}}
{{--                                    <a href="{{ route('public_consultation.index') }}" class="btn rss-sub main-color">--}}
{{--                                        {{ __('site.all_pc') }} <i class="fas fa-long-arrow-right main-color"></i>--}}
{{--                                    </a>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}

{{--                    <a href="@if($planAreas){{ route('ogp.develop_new_action_plans')  }}@else{{ '#' }}@endif" class="box-link light-blue-bgr my-4">--}}
{{--                        <div class="info-box">--}}
{{--                            <div class="icon-wrap">--}}
{{--                                <i class="bi bi bi-lightbulb text-light"></i>--}}
{{--                            </div>--}}
{{--                            <div class="link-heading">--}}
{{--                            <span>--}}
{{--                                {{ __('site.make_plan_proposal') }}--}}
{{--                            </span>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </a>--}}

{{--                    <div class="col-md-12 mt-4 custom-card p-3 mb-2">--}}
{{--                        <h3 class="mb-3" style="font-size: 24px;">{{ __('site.home.open_plans_title') }}</h3>--}}
{{--                        @if(isset($planAreas) && $planAreas->count())--}}
{{--                            <ul class="list-group questionnaire">--}}
{{--                                @php($cntPlan = 0)--}}
{{--                                @foreach($planAreas as $pa)--}}
{{--                                    @if($cntPlan > 5)--}}
{{--                                        @break--}}
{{--                                    @endif--}}
{{--                                    <li class="list-group-item">--}}
{{--                                        <a href="{{ route('ogp.develop_new_action_plans.area', ['plan' => $pa->ogp_plan_id, 'planArea' => $pa->id]) }}" class="text-decoration-none">{{ $pa->area?->name }}</a>--}}
{{--                                        <a href="{{ route('ogp.develop_new_action_plans.area', ['plan' => $pa->ogp_plan_id, 'planArea' => $pa->id]) }}"><span><i class="fa-solid fa-chevron-right"></i></span></a>--}}
{{--                                    </li>--}}
{{--                                    @php($cntPlan += 1)--}}
{{--                                @endforeach--}}
{{--                            </ul>--}}
{{--                            <a href="@if($planAreas){{ route('ogp.develop_new_action_plans')  }}@else{{ '#' }}@endif" class="btn btn-primary main-color mt-4">{{ __('site.home.to_open_plan') }} <i--}}
{{--                                    class="fas fa-long-arrow-right main-color"></i></a>--}}
{{--                        @else--}}
{{--                            <ul class="list-group questionnaire">--}}
{{--                                <li class="list-group-item main-color">--}}
{{--                                    {{ __('site.home.no_open_plans_found') }}--}}
{{--                                </li>--}}
{{--                            </ul>--}}
{{--                        @endif--}}
{{--                    </div>--}}


                </div>

                <div class="col-lg-6 col-md-12">
                    <a href="{{ route('strategy-documents.index') }}" class="box-link navy-marine-bgr  mb-2">
                        <div class="info-box">
                            <div class="icon-wrap">
                                <i class="bi bi-folder-check text-light"></i>
                            </div>
                            <div class="link-heading">
                            <span>
                                {{ trans_choice('custom.strategic_documents', 2) }}
                            </span>
                            </div>
                        </div>
                    </a>
                    <div class="home-results mt-2">

                        <div class=" item-holder-one">
                            <div class="row mb-2">
                                <div class="col-md-12">
                                    <h2 class="mb-2" style="font-size: 24px;">{{ __('site.strategic_documents_home_boc_title') }}</h2>
                                    <p>
                                        {{ __('site.home.sd_description') }}
                                    </p>

                                </div>
                            </div>

                            <form action="{{ route('get-strategic-documents') }}" id="sd_form" class="ajax_search_form" method="GET">
                                <input type="hidden" name="search" value="true">
                                <input type="hidden" name="page" class="current_page" value="{{ $strategicDocuments->currentPage() }}">
                                <div class="row filter-results mb-2">
                                    <h3 style="font-size: 20px;">
                                        {{ __('custom.searching') }}
                                    </h3>

                                    <div class="col-md-12 mb-2 mt-2">
                                        <input type="text" class="form-control" name="keywords"
                                               placeholder="{{ __('site.enter_word') }}"
                                               aria-label="{{ __('site.enter_word') }}" aria-describedby="basic-addon2">
                                    </div>
                                </div>

                                <div class="row mb-5">
                                    <div class="col-md-4">
                                        <button class="btn rss-sub main-color search-btn" data-id="sd">
                                            <i class="fas fa-search main-color"></i>{{ __('custom.searching') }}
                                        </button>
                                    </div>

                                    <div class="col-md-8">
                                        <div class="info-consul">
                                            <h4>
                                                {{ __('custom.total') }} <span id="sd_total">{{ $strategicDocuments->total() }}</span> {{ mb_strtolower(trans_choice('custom.results', 2)) }}
                                            </h4>
                                        </div>
                                    </div>
                                </div>
                            </form>

                            <div id="sd_results">
                                @includeIf('site.home.strategic_documents')
                            </div>


                            <div class="row">
                                <div class="col-md-8">
                                    <a href="{{ route('strategy-documents.index') }}" class="btn rss-sub main-color">
                                        {{ __('site.all_sd') }} <i class="fas fa-long-arrow-right main-color"></i>
                                    </a>
                                </div>
                            </div>

                        </div>

{{--                        <a href="{{ route('poll.index') }}" class="box-link dark-blue-bgr my-4">--}}
{{--                            <div class="info-box">--}}
{{--                                <div class="icon-wrap">--}}
{{--                                    <i class="bi bi-patch-question text-light"></i>--}}
{{--                                </div>--}}
{{--                                <div class="link-heading">--}}
{{--                                <span>--}}
{{--                                    {{ __('site.participate_in_poll') }}--}}
{{--                                </span>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        </a>--}}

{{--                        <div class="col-md-12 mt-4 custom-card p-3">--}}
{{--                            <h3 class="mb-3" style="font-size: 24px;">{{ __('site.all_open_poll') }}</h3>--}}
{{--                            @if(isset($polls) && sizeof($polls))--}}
{{--                                <ul class="list-group questionnaire">--}}
{{--                                    @foreach($polls as $poll)--}}
{{--                                        <li class="list-group-item">--}}
{{--                                            <a href="@if($poll->active_ord){{ route('poll.show', $poll->id) }}@else{{ route('poll.statistic', $poll->id) }}@endif" class="text-decoration-none">--}}
{{--                                                    {{ $poll->name }}--}}
{{--                                                @dd(auth()->user())--}}
{{--                                                @if($poll->active_ord)--}}
{{--                                                    @if(!auth()->user() && $poll->only_registered)--}}
{{--                                                        <i class="ms-1 fa fa-solid fa-circle-user light-blue" data-bs-toggle="tooltip" title="{{ __('messages.poll_only_registered') }}. {{ __('site.home.poll_end_at', ['date' => displayDate($poll->end_date)]) }}"></i>--}}
{{--                                                    @else--}}
{{--                                                        <i class="ms-1 fa fa-solid fa-hourglass-end light-blue" data-bs-toggle="tooltip" title="{{ __('site.home.poll_end_at', ['date' => displayDate($poll->end_date)]) }}"></i>--}}
{{--                                                    @endif--}}
{{--                                                @endif--}}
{{--                                            </a>--}}
{{--                                            <a href="@if($poll->active_ord){{ route('poll.show', $poll->id) }}@else{{ route('poll.statistic', $poll->id) }}@endif"><span><i class="fa-solid fa-chevron-right"></i></span></a>--}}
{{--                                        </li>--}}
{{--                                    @endforeach--}}
{{--                                </ul>--}}
{{--                                <a href="{{ route('poll.index') }}" class="btn btn-primary main-color mt-4">{{ __('site.all_poll') }} <i--}}
{{--                                        class="fas fa-long-arrow-right main-color" ></i></a>--}}
{{--                            @else--}}
{{--                                <ul class="list-group questionnaire">--}}
{{--                                    <li class="list-group-item main-color">--}}
{{--                                        {{ __('site.home.no_polls_found') }}--}}
{{--                                    </li>--}}
{{--                                </ul>--}}
{{--                            @endif--}}
{{--                        </div>--}}
                    </div>
                </div>
            </div>

        </div>

    </section>

    <section id="blog" class="home-page-section">
        <div class="container">

            <div class="row mb-2">
                <div class="col-md-12 ">
                    <h2 class="mb-2">{{ __('site.newest_in_platform') }}</h2>
                </div>

            </div>

            <div class="row">
                @foreach($publications as $publication)
                    @php($isAdvBoardNews = $publication->type == \App\Enums\PublicationTypesEnum::TYPE_ADVISORY_BOARD->value)
                    @php($isOgpNews = $publication->type == \App\Enums\PublicationTypesEnum::TYPE_OGP_NEWS->value)
                    <div class="col-lg-4 mb-2">
                        <div class="post-box">
                            <div class="post-img">
                                <img src="{{ $publication->thumbHomePageAsset }}" class="img-fluid col-md-5 float-md-start mb-2 me-md-4 news-single-img"
                                     alt="{{ $publication->translation->title }}"
                                >
                            </div>
                            <span class="post-date text-secondary">{{ displayDate($publication->published_at) }} г.</span>
                            <h3 class="post-title">
{{--                                {{ $publication->translation->title }}--}}
                                <a href="@if($isAdvBoardNews){{ route('advisory-boards.news.details', $publication) }}@elseif($isOgpNews){{ route('ogp.news.details', $publication) }}@else{{ route('library.details', [$publication->type, $publication->id]) }}@endif" class="text-decoration-none" title="{{ $publication->title }}">
                                    {{ $publication->translation->title }}
                                </a>
                            </h3>
                            <div class="row mb-2">
                                <div class="col-md-8">
                                    <span class="blog-category">{{ $isAdvBoardNews ? $publication->advCategory : $publication->category?->name }}</span>
                                </div>
                                <div class="col-md-4">
                                    <div class="consult-item-header-edit">
                                        @can(($isAdvBoardNews ? 'deleteAdvBoard' : 'delete'), $publication)
                                            <a href="javascript:;"
                                               class="fas fa-regular fa-trash-can float-end text-danger fs-4  ms-2 js-toggle-delete-resource-modal hidden text-decoration-none"
                                               data-target="#modal-delete-resource"
                                               data-title_singular="{{ trans_choice('custom.publications', 1) }}"
                                               data-resource-id="{{ $publication->id }}"
                                               data-resource-name="{{ $publication->title }}"
                                               data-resource-delete-url="{{ route('admin.publications.delete', $publication) }}"
                                               data-toggle="tooltip"
                                               title="{{ __('custom.delete') }}"><span class="d-none"></span>
{{--                                                <i class="fas fa-regular fa-trash-can float-end text-danger fs-4  ms-2" role="button" title="{{ __('custom.delete') }}"></i>--}}
                                            </a>
                                        @endcan
                                        @can(($isAdvBoardNews ? 'updateAdvBoard' : 'update'), $publication)
                                            <a href="{{ route('admin.publications.edit' , [$publication->id]) }}" data-toggle="tooltip" title="{{ __('custom.edit') }}">
                                                <i class="fas fa-pen-to-square float-end main-color fs-4" role="button" title="{{ __('custom.edit') }}"></i>
                                            </a>
                                        @endcan
                                    </div>
                                </div>
                            </div>
                            <!-- За описанието ще е хубаво да се сложи някакъв лимит на символи или думи -->
                            <p class="short-decription text-secondary">
                                {!! strip_tags($publication->translation?->short_content) ? strip_tags(Str::limit($publication->translation?->short_content, 200)) : "" !!}
                            </p>
                            @if($isAdvBoardNews)
                                <a href="{{ $publication->advisory_boards_id ? route('advisory-boards.view.news.details', [$publication->advisory_boards_id, $publication]) : route('advisory-boards.news.details', $publication) }}" class="readmore mt-1" title="{{ $publication->translation->title }}">
                                    {{ __('site.read_more_') }} <i class="fas fa-long-arrow-right"></i>
                                </a>
                            @else
                                <a href="{{ route('library.details', [$publication->type, $publication->id]) }}" class="readmore mt-1" title="{{ $publication->translation->title }}">
                                    {{ __('site.read_more_') }} <i class="fas fa-long-arrow-right"></i>
                                </a>
                            @endif
                        </div>
                    </div>
                @endforeach

            </div>

            <div class="row">
                <div class="col-md-12">
                    <a href="{{ route('library.news') }}" class="btn btn-primary mt-2">
                        {{ __('site.all_news') }} <i class="fas fa-long-arrow-right main-color"></i>
                    </a>
                </div>
            </div>
        </div>
    </section>
    @includeIf('modals.delete-resource', ['resource' => $title_singular])
@endsection
