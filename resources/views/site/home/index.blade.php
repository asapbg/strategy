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
                                    Добре дошли в Портала за обществени консултации
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
            <div class="row mb-4">
                <div class="col-md-12">
                    <h2 class="mb-2">Информирай се</h2>
                </div>
            </div>

            <div class="row">
                <div class="col-md-4 position-relative">
                    <div class="service-item  position-relative">
                        <a href="{{ route('lp.index') }}" title="Законодателна програма">
                            <div class="icon">
                                <i class="bi bi-shield-check"></i>
                            </div>
                            <h3>Законодателна програма</h3>
                        </a>
                    </div>
                </div>

                <div class="col-md-4 position-relative">
                    <div class="service-item  position-relative">
                        <a href="{{ route('op.index') }}" title="Оперативна програма">
                            <div class="icon">
                                <i class="bi bi-arrow-up-right-circle"></i>
                            </div>
                            <h3>Оперативна програма</h3>
                        </a>
                    </div>
                </div>

                <div class="col-md-4 position-relative">
                    <div class="service-item  position-relative">
                        <a href="{{ route('strategy-documents.index') }}" title="Стратегически документи">
                            <div class="icon">
                                <i class="bi bi-files"></i>

                            </div>
                            <h3>Стратегически документи</h3>
                        </a>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-4 position-relative">
                    <div class="service-item  position-relative">
                        <a href="{{ route('impact_assessment.index') }}" title="Оценки на въздействието">
                            <div class="icon">
                                <i class="bi bi-journal-plus"></i>
                            </div>
                            <h3>Оценки на въздействието</h3>
                        </a>
                    </div>
                </div>

                <div class="col-md-4 position-relative">
                    <div class="service-item  position-relative">
                        <a href="{{ route('pris.index') }}" title="Актове на Министерски съвет">
                            <div class="icon">
                                <i class="bi bi-file-earmark-medical"></i>
                            </div>
                            <h3>Актове на Министерски съвет</h3>
                        </a>
                    </div>
                </div>

                <div class="col-md-4 position-relative">
                    <div class="service-item  position-relative">
                        <a href="{{ route('legislative_initiatives.index') }}" title="Отворено управление">
                            <div class="icon">
                                <i class="bi bi-bounding-box-circles"></i>
                            </div>
                            <h3>Отворено управление</h3>
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </section>

    <section class="home-page-section public-constultation pb-5">
        <div class="container">
            <div class="row mb-4">
                <div class="col-md-12">
                    <h2 class="mb-2">Участвай</h2>
                </div>
            </div>
            <div class="row ">

                <div class="col-lg-6 col-md-12">
                    <a href="{{ route('public_consultation.index') }}" class="box-link gr-color-bgr mb-4">
                        <div class="info-box">
                            <div class="icon-wrap">
                                <i class="bi bi-check2-square text-light"></i>
                            </div>
                            <div class="link-heading">
                            <span>
                                Включи се в обществени консултации
                            </span>
                            </div>
                        </div>
                    </a>
                    <div class="home-results mt-2">

                        <div class=" item-holder-one">
                            <div class="row mb-2">
                                <div class="col-md-12">
                                    <h2 class="mb-2" style="font-size: 24px;">Последни обществени консултации</h2>
                                    <p>
                                        Този раздел е предназначен за граждани, които желаят да се включат в обществения
                                        дебат и да споделят мнението си.
                                    </p>

                                </div>
                            </div>

                            <form action="{{ route('get-consultations') }}" id="consultations_form" class="ajax_search_form" method="GET">
                                <input type="hidden" name="search" value="true">
                                <input type="hidden" name="page" class="current_page" value="{{ $consultations->currentPage() }}">
                                <div class="row filter-results mb-2">
                                    <h3 style="font-size: 20px;">
                                        Търсене
                                    </h3>

                                    <div class="col-md-12 mb-2 mt-2">
                                        <input type="text" class="form-control" name="pc_search_title"
                                               placeholder="Въведете дума или израз"
                                               aria-label="Въведете дума или израз" aria-describedby="basic-addon2"
                                        >
                                    </div>
                                </div>

                                <div class="row mb-5">
                                    <div class="col-md-6">
                                        <button class="btn rss-sub main-color search-btn" data-id="consultations">
                                            <i class="fas fa-search main-color"></i>Търсене
                                        </button>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="info-consul">
                                            <h4>
                                                Общо <span id="consultations_total">{{ $consultations->total() }}</span> резултата
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
                                        Всички консултации <i class="fas fa-long-arrow-right main-color"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <a href="#" class="box-link light-blue-bgr my-4">
                        <div class="info-box">
                            <div class="icon-wrap">
                                <i class="bi bi bi-lightbulb text-light"></i>
                            </div>
                            <div class="link-heading">
                            <span>
                                Предложи мерки за добро управление
                            </span>
                            </div>
                        </div>
                    </a>

                    <div class="col-md-12 mt-4 custom-card p-3 mb-4">
                        <h3 class="mb-3" style="font-size: 24px;">Списък отворени планове</h3>
                        <ul class="list-group questionnaire">
                            <li class="list-group-item">
                                <a href="#" class="text-decoration-none">Финанси и данъчна политика</a>
                                <a href="#"><span><i class="fa-solid fa-chevron-right"></i></span></a>
                            </li>
                            <li class="list-group-item">
                                <a href="#" class="text-decoration-none">Национална сигурност</a>
                                <a href="#"><span><i class="fa-solid fa-chevron-right"></i></span></a>
                            </li>
                            <li class="list-group-item">
                                <a href="#" class="text-decoration-none">Земеделие и селски райони</a>
                                <a href="#"><span><i class="fa-solid fa-chevron-right"></i></span></a>
                            </li>
                            <li class="list-group-item">
                                <a href="#" class="text-decoration-none">Околна среда</a>
                                <a href="#"><span><i class="fa-solid fa-chevron-right"></i></span></a>
                            </li>
                            <li class="list-group-item">
                                <a href="#" class="text-decoration-none">Бизнес среда</a>
                                <a href="#"><span><i class="fa-solid fa-chevron-right"></i></span></a>
                            </li>
                        </ul>
                        <button class="btn btn-primary main-color mt-4">Всички планове <i
                                class="fas fa-long-arrow-right main-color"></i></button>
                    </div>


                </div>

                <div class="col-lg-6 col-md-12">
                    <a href="{{ route('legislative_initiatives.index') }}" class="box-link navy-marine-bgr  mb-4">
                        <div class="info-box">
                            <div class="icon-wrap">
                                <i class="bi bi-folder-check text-light"></i>
                            </div>
                            <div class="link-heading">
                            <span>
                                Предложи/Подкрепи законодателна инициатива
                            </span>
                            </div>
                        </div>
                    </a>
                    <div class="home-results mt-2">

                        <div class=" item-holder-one">
                            <div class="row mb-2">
                                <div class="col-md-12">
                                    <h2 class="mb-2" style="font-size: 24px;">Списък отворени законодателни инициативи</h2>
                                    <p>
                                        Този раздел е предназначен за граждани, които желаят да се включат в обществения
                                        дебат и да споделят мнението си.
                                    </p>

                                </div>
                            </div>

                            <form action="{{ route('get-initiatives') }}" id="initiatives_form" class="ajax_search_form" method="GET">
                                <input type="hidden" name="search" value="true">
                                <input type="hidden" name="page" class="current_page" value="{{ $initiatives->currentPage() }}">
                                <div class="row filter-results mb-2">
                                    <h3 style="font-size: 20px;">
                                        Търсене
                                    </h3>

                                    <div class="col-md-12 mb-2 mt-2">
                                        <input type="text" class="form-control" name="keywords"
                                               placeholder="Въведете дума или израз"
                                               aria-label="Въведете дума или израз" aria-describedby="basic-addon2">
                                    </div>
                                </div>

                                <div class="row mb-5">
                                    <div class="col-md-8">
                                        <button class="btn rss-sub main-color search-btn" data-id="initiatives">
                                            <i class="fas fa-search main-color"></i>Търсене
                                        </button>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="info-consul">
                                            <h4>
                                                Общо <span id="initiatives_total">{{ $initiatives->total() }}</span> резултата
                                            </h4>
                                        </div>
                                    </div>
                                </div>
                            </form>

                            <div id="initiatives_results">
                                @includeIf('site.home.initiatives')
                            </div>


                            <div class="row">
                                <div class="col-md-8">
                                    <a href="{{ route('legislative_initiatives.index') }}" class="btn rss-sub main-color">
                                        Всички инициативи <i class="fas fa-long-arrow-right main-color"></i>
                                    </a>
                                </div>
                            </div>

                        </div>

                        <a href="{{ route('poll.index') }}" class="box-link dark-blue-bgr my-4">
                            <div class="info-box">
                                <div class="icon-wrap">
                                    <i class="bi bi-patch-question text-light"></i>
                                </div>
                                <div class="link-heading">
                                <span>
                                    Участвай в анкета
                                </span>
                                </div>
                            </div>
                        </a>

                        <div class="col-md-12 mt-4 custom-card p-3">
                            <h3 class="mb-3" style="font-size: 24px;">Списък отворени анкети</h3>
                            <ul class="list-group questionnaire">
                                <li class="list-group-item">
                                    <a href="#" class="text-decoration-none">Финанси и данъчна политика</a>
                                    <a href="#"><span><i class="fa-solid fa-chevron-right"></i></span></a>
                                </li>
                                <li class="list-group-item">
                                    <a href="#" class="text-decoration-none">Национална сигурност</a>
                                    <a href="#"><span><i class="fa-solid fa-chevron-right"></i></span></a>
                                </li>
                                <li class="list-group-item">
                                    <a href="#" class="text-decoration-none">Земеделие и селски райони</a>
                                    <a href="#"><span><i class="fa-solid fa-chevron-right"></i></span></a>
                                </li>
                                <li class="list-group-item">
                                    <a href="#" class="text-decoration-none">Околна среда</a>
                                    <a href="#"><span><i class="fa-solid fa-chevron-right"></i></span></a>
                                </li>
                                <li class="list-group-item">
                                    <a href="#" class="text-decoration-none">Бизнес среда</a>
                                    <a href="#"><span><i class="fa-solid fa-chevron-right"></i></span></a>
                                </li>
                            </ul>
                            <button class="btn btn-primary main-color mt-4">Всички анкети <i
                                    class="fas fa-long-arrow-right main-color"></i></button>

                        </div>
                    </div>
                </div>
            </div>

        </div>

    </section>

    <section id="blog" class="home-page-section">
        <div class="container">

            <div class="row mb-4">
                <div class="col-md-12 ">
                    <h2 class="mb-2">Най-новото</h2>
                </div>

            </div>

            <div class="row">
                @foreach($publications as $publication)
                    @php($isAdvBoardNews = $publication->type == \App\Enums\PublicationTypesEnum::TYPE_ADVISORY_BOARD->value)
                    <div class="col-lg-4 mb-4">
                        <div class="post-box">
                            <div class="post-img">
                                <img src="{{ asset($publication->mainImg?->path ?? $default_img) }}" class="img-fluid col-md-5 float-md-start mb-4 me-md-4 news-single-img"
                                     alt="{{ $publication->translation->title }}"
                                >
                            </div>
                            <span class="post-date text-secondary">{{ displayDate($publication->published_at) }} г.</span>
                            <h3 class="post-title">{{ $publication->translation->title }}</h3>
                            <div class="row mb-2">
                                <div class="col-md-8">
                                    <span class="blog-category">{{ $isAdvBoardNews ? $publication->advCategory : $publication->category?->name }}</span>
                                </div>
                                <div class="col-md-4">
                                    <div class="consult-item-header-edit">
                                        @can(($isAdvBoardNews ? 'deleteAdvBoard' : 'delete'), $publication)
                                            <a href="javascript:;"
                                               data-target="#modal-delete-resource"
                                               data-resource-id="{{ $publication->id }}"
                                               data-resource-name="{{ $publication->title }}"
                                               data-resource-delete-url="{{ route('admin.publications.delete', $publication) }}"
                                               data-toggle="tooltip"
                                               title="{{ __('custom.delete') }}">
                                                <i class="fas fa-regular fa-trash-can float-end text-danger fs-4  ms-2" role="button" title="Изтриване"></i>
                                            </a>
                                        @endcan
                                        @can(($isAdvBoardNews ? 'updateAdvBoard' : 'update'), $publication)
                                            <a href="{{ route('admin.publications.edit' , [$publication->id]) }}" data-toggle="tooltip" title="{{ __('custom.edit') }}">
                                                <i class="fas fa-pen-to-square float-end main-color fs-4" role="button" title="Редакция"></i>
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
                                    Прочетете още <i class="fas fa-long-arrow-right"></i>
                                </a>
                            @else
                                <a href="{{ route('library.details', [$publication->type, $publication->id]) }}" class="readmore mt-1" title="{{ $publication->translation->title }}">
                                    Прочетете още <i class="fas fa-long-arrow-right"></i>
                                </a>
                            @endif
                        </div>
                    </div>
                @endforeach

            </div>

            <div class="row">
                <div class="col-md-12">
                    <a href="{{ route('library.news') }}" class="btn btn-primary">
                        Всички новини <i class="fas fa-long-arrow-right main-color"></i>
                    </a>
                </div>
            </div>
        </div>
    </section>

@endsection
