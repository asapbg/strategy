@extends('layouts.site', ['fullwidth' => true])
<style>
    .public-page {
        padding: 0px 0px !important;
    }

</style>

@section('pageTitle', 'Законодателна инициатива')

@section('content')
    <div class="row">
        <div class="col-lg-2 side-menu pt-5 mt-1 pb-5" style="background:#f5f9fd;">
            <div class="left-nav-panel" style="background: #fff !important;">
                <div class="flex-shrink-0 p-2">
                    <ul class="list-unstyled">
                        <li class="mb-1">
                            <a class="btn-toggle pe-auto align-items-center rounded ps-2 text-decoration-none cursor-pointer fs-5 dark-text fw-600"
                               data-toggle="collapse" data-target="#home-collapse" aria-expanded="true">
                                <i class="fa-solid fa-bars me-2 mb-2"></i>Гражданско участие
                            </a>
                            <hr class="custom-hr">
                            <div class="collapse show mt-3" id="home-collapse">
                                <ul class="btn-toggle-nav list-unstyled fw-normal px-2 pb-1 small">

                                    <li class="mb-2  active-item-left p-1"><a href="#"
                                                                              class="link-dark text-decoration-none">Законодателни
                                            инициативи</a>
                                    </li>
                                    <li class="mb-2"><a href="#" class="link-dark text-decoration-none">Отворено
                                            управление</a>
                                    </li>
                                    <ul class="btn-toggle-nav list-unstyled fw-normal px-2 pb-1 mb-2">
                                        <ul class="list-unstyled ps-3">
                                            <hr class="custom-hr">
                                            <li class="my-2"><a href="#" class="link-dark  text-decoration-none">Планове
                                                </a></li>
                                            <hr class="custom-hr">
                                            <li class="my-2"><a href="#"
                                                                class="link-dark  text-decoration-none">Отчети</a>
                                            </li>
                                            <hr class="custom-hr">
                                        </ul>
                                    </ul>

                                    <li class="mb-2"><a href="#" class="link-dark text-decoration-none">Анкети</a>
                                    </li>


                                </ul>
                            </div>
                        </li>
                        <hr class="custom-hr">
                    </ul>
                </div>
            </div>

        </div>


        <div class="col-lg-10 py-5">
            <div class="row filter-results mb-2">
                <h2 class="mb-4">
                    Търсене
                </h2>
                <form id="filter" class="row" action="{{ route('legislative_initiatives.index') }}" method="GET">
                    <div class="col-md-4">
                        <div class="input-group">
                            <div class="mb-3 d-flex flex-column w-100">
                                <label for="keywords" class="form-label">{{ trans_choice('custom.keyword', 2) }}</label>
                                <input id="keywords" class="form-control" name="keywords" type="text"
                                       value="{{ request()->get('keywords', '') }}">
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="input-group ">
                            <div class="mb-3 d-flex flex-column  w-100">
                                <label for="institution"
                                       class="form-label">{{ trans_choice('custom.institutions', 1) }}</label>
                                <select id="institution" class="institution form-select select2" name="institution"
                                        multiple>
                                    <option value="" disabled>--</option>
                                    @foreach($institutions as $institution)
                                        @php $selected = request()->get('institution', '') == $institution->id ? 'selected' : '' @endphp
                                        <option
                                            value="{{ $institution->id }}" {{ $selected }}>{{ $institution->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="input-group ">
                            <div class="mb-3 d-flex flex-column  w-100">
                                <label for="count_results"
                                       class="form-label">{{ __('custom.count') . ' ' . mb_strtolower(trans_choice('custom.results', 2)) }}
                                    :</label>
                                <select id="count_results" class="form-select" name="count_results">
                                    <option value="10">10</option>
                                    @php $selected = request()->get('count_results', '') == 20 ? 'selected' : '' @endphp
                                    <option value="20" {{ $selected }}>20</option>
                                    @php $selected = request()->get('count_results', '') == 30 ? 'selected' : '' @endphp
                                    <option value="30" {{ $selected }}>30</option>
                                    @php $selected = request()->get('count_results', '') == 40 ? 'selected' : '' @endphp
                                    <option value="40" {{ $selected }}>40</option>
                                    @php $selected = request()->get('count_results', '') == 50 ? 'selected' : '' @endphp
                                    <option value="50" {{ $selected }}>50</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-5 mt-5">
                        <div class="col-md-6">
                            <button class="btn rss-sub main-color" type="submit"><i
                                    class="fas fa-search main-color"></i>Търсене
                            </button>
                        </div>

                        <div class="col-md-6 text-end">
                            <button class="btn rss-sub main-color"><i class="fas fa-square-rss text-warning"></i>RSS
                            </button>
                            <button class="btn rss-sub main-color"><i class="fas fa-envelope"></i>Абониране</button>

                            @if(auth()->check())
                                <a href="{{ route('legislative_initiatives.create') }}"
                                   class="btn btn-success text-success">
                                    <i class="fas fa-circle-plus text-success me-1"></i>
                                    {{ __('custom.add') . ' ' . trans_choice('custom.legislative_initiatives_list', 1) }}
                                </a>
                            @endif
                        </div>
                    </div>
                </form>
            </div>

            <div class="row sort-row fw-600 main-color-light-bgr align-items-center rounded py-2 px-2 m-0">
                <div class="col-md-4">
                    @include('components.sortable-link', ['sort_by' => 'keywords', 'translation' => trans_choice('custom.keyword', 1)])
                </div>

                <div class="col-md-4 cursor-pointer ">
                    @include('components.sortable-link', ['sort_by' => 'institutions', 'translation' => trans_choice('custom.institutions', 1)])
                </div>

                <div class="col-md-4">
                    @include('components.sortable-link', ['sort_by' => 'date', 'translation' => trans_choice('validation.attributes.date', 1)])
                </div>
            </div>

            <div class="row mb-2">
                <div class="col-12 mt-2">
                    <div class="info-consul text-start">
                        <p class="fw-600">
                            {{ __('custom.total') }} {{ $items->count() }} {{ $items->count() == 1 ? mb_strtolower(trans_choice('custom.results', 1)) : mb_strtolower(trans_choice('custom.results', 2)) }}
                        </p>
                    </div>
                </div>
            </div>

            @if(isset($items) && $items->count() > 0)
                @foreach($items as $item)
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <div class="consul-wrapper">
                                <div class="single-consultation d-flex">
                                    <div class="consult-img-holder">
                                        <i class="fa-solid fa-hospital light-blue"></i>
                                    </div>
                                    <div class="consult-body">
                                        <div href="#" class="consul-item">
                                            <div class="consult-item-header d-flex justify-content-between">
                                                <div class="consult-item-header-link">
                                                    <a href="{{ route('legislative_initiatives.view', $item) }}"
                                                       class="text-decoration-none"
                                                       title="{{ __('custom.change_f') }} {{ __('custom.in') }} {{ $item->operationalProgram?->value }}">
                                                        <h3>{{ __('custom.change_f') }} {{ __('custom.in') }}
                                                            {{ mb_strtolower($item->operationalProgram?->value) }}</h3>
                                                    </a>
                                                </div>
                                                <div class="consult-item-header-edit">
                                                    @if(
                                                        auth()->check() &&
                                                        auth()->user()->id === $item->author_id
                                                        && $item->getStatus($item->status)->value === \App\Enums\LegislativeInitiativeStatusesEnum::STATUS_ACTIVE->value
                                                    )
                                                        <form class="d-none"
                                                              method="POST"
                                                              action="{{ route('legislative_initiatives.delete', $item) }}"
                                                              name="DELETE_ITEM_{{ $item->id }}"
                                                        >
                                                            @csrf
                                                        </form>

                                                        <a href="#" class="open-delete-modal">
                                                            <i class="fas fa-regular fa-trash-can float-end text-danger fs-4  ms-2"
                                                               role="button" title="{{ __('custom.deletion') }}"></i>
                                                        </a>

                                                        <a href="{{ route('legislative_initiatives.edit', $item) }}">
                                                            <i class="fas fa-pen-to-square float-end main-color fs-4"
                                                               role="button" title="{{ __('custom.edit') }}">
                                                            </i>
                                                        </a>
                                                    @endif
                                                </div>
                                            </div>

                                            <a href="#" title="{{ $item->operationalProgram?->institution }}"
                                               class="text-decoration-none text-capitalize mb-3">
                                                {{ $item->operationalProgram?->institution }}
                                            </a>

                                            <div class="status mt-2">
                                                <div>
                                                    <span>{{ __('validation.attributes.status') }}:
                                                        @php
                                                            $status_class = 'active-li';

                                                            switch ($item->getStatus($item->status)->name) {
                                                                case 'STATUS_CLOSED':
                                                                    $status_class = 'closed-li';
                                                                    break;

                                                                case 'STATUS_SEND':
                                                                    $status_class = 'send-li';
                                                                    break;
                                                            }
                                                        @endphp
                                                        <span
                                                            class="{{ $status_class }}">{{ __('custom.legislative_' . \Illuminate\Support\Str::lower($item->getStatus($item->status)->name)) }}</span>
                                                    </span>

                                                    <span class="mx-1">|</span>

                                                    <span>
                                                        {{ __('custom.supported_f') }}:
                                                        <span
                                                            class="voted-li">{{ $item->countLikes() }}
                                                            @if($item->countLikes() == 1)
                                                                <span>{{ __('custom.once_count') }}</span>
                                                            @else
                                                                <span>{{ __('custom.times_count') }}</span>
                                                            @endif
                                                        </span>
                                                    </span>
                                                </div>
                                            </div>

                                            <div class="row mt-3 justify-content-between">
                                                <div class="col-auto">
                                                    <div class="row">
                                                        <div class="col-auto">
                                                            <span class="text-secondary">
                                                                <i class="far fa-calendar text-secondary me-1"></i> {{ $item->created_at->format('d.m.Y') }}{{ __('custom.year_short') }}
                                                            </span>
                                                        </div>

                                                        <div class="col-auto">
                                                            <div class="mb-0">
                                                                <!-- LIKES -->

                                                                {{ $item->countLikes() }}

                                                                @if($item->userHasLike())
                                                                    <a href="{{ route('legislative_initiatives.vote.revert', $item) }}"
                                                                       class="me-2 text-decoration-none">
                                                                        <i class="fa fa-thumbs-up fs-18"
                                                                           aria-hidden="true"></i>
                                                                    </a>
                                                                @else
                                                                    <a href="{{ route('legislative_initiatives.vote.store', [$item, 'like']) }}"
                                                                       class="me-2 text-decoration-none">
                                                                        <i class="ms-1 fa fa-regular fa-thumbs-up main-color fs-18"></i>
                                                                    </a>
                                                                @endif


                                                                <!-- DISLIKES -->

                                                                {{ $item->countDislikes() }}

                                                                @if($item->userHasDislike())
                                                                    <a href="{{ route('legislative_initiatives.vote.revert', $item) }}"
                                                                       class="text-decoration-none">
                                                                        <i class="fa fa-thumbs-down fs-18"></i>
                                                                    </a>
                                                                @else
                                                                    <a href="{{ route('legislative_initiatives.vote.store', [$item, 'dislike']) }}"
                                                                       class="text-decoration-none">
                                                                        <i class="ms-1 fa fa-regular fa-thumbs-down main-color fs-18"></i>
                                                                    </a>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-auto">
                                                    <a href="{{ route('legislative_initiatives.view', $item) }}"
                                                       title="Проект на Решение на Министерския съвет за приемане на Национален план за развитие на биологичното производство до 2030 г.">
                                                        <i class="fas fa-arrow-right read-more">
                                                            <span class="d-none"></span>
                                                        </i>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif

            <div class="row">
                <nav aria-label="Page navigation example">
                    @if(isset($items) && $items->count() > 0)
                        {{ $items->appends(request()->query())->links() }}
                    @endif
                </nav>
            </div>
        </div>
    </div>

    @include('components.delete-modal', [
        'cancel_btn_text'           => __('custom.cancel'),
        'continue_btn_text'         => __('custom.continue'),
        'title_text'                => __('custom.deletion') . ' ' . __('custom.of') . ' ' . trans_choice('custom.legislative_initiatives', 1),
        'file_change_warning_txt'   => __('custom.are_you_sure_to_delete') . ' ' . Str::lower(trans_choice('custom.legislative_initiatives_list', 1)) . '?',
    ])
@endsection
