@extends('layouts.site', ['fullwidth' => true])

@section('pageTitle', 'Законодателна инициатива')

@section('content')
    <div class="row">
        @include('site.legislative_initiatives.side_menu')
        @php
            $user = auth()->user()
        @endphp

        <div class="col-lg-10 right-side-content py-2">
{{--            @if(isset($pageTopContent) && !empty($pageTopContent->value))--}}
{{--                <div class="col-12 mb-2">--}}
{{--                    {!! $pageTopContent->value !!}--}}
{{--                </div>--}}
{{--            @endif--}}
            <div class="filter-results mb-2">
                <h2 class="mb-4">
                    {{ __('custom.search') }}
                </h2>
                <form id="filter" class="row" action="{{ route('legislative_initiatives.index') }}" method="GET">
                    <div class="col-md-4">
                        <div class="input-group">
                            <div class="mb-3 d-flex flex-column w-100">
                                <label for="keywords" class="form-label">{{ __('custom.content_author') }}</label>
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
                                <select id="institution" class="institution form-select select2" name="institution[]"
                                        multiple>
                                    <option value="" disabled>--</option>
                                    @foreach($institutions as $institution)
                                        @php $selected = in_array($institution->id, request()->get('institution', []))  ? 'selected' : '' @endphp
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
                                <label for="law"
                                       class="form-label">{{ trans_choice('custom.laws', 1) }}</label>
                                <select id="law" class=" form-select select2" name="law[]"
                                        multiple>
                                    <option value="" disabled>--</option>
                                    @foreach($laws as $law)
                                        @php $selected = in_array($law->id, request()->get('law', []))  ? 'selected' : '' @endphp
                                        <option
                                            value="{{ $law->id }}" {{ $selected }}>{{ $law->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

{{--                    <div class="col-md-4">--}}
{{--                        <div class="input-group ">--}}
{{--                            <div class="mb-3 d-flex flex-column  w-100">--}}
{{--                                <label for="count_results"--}}
{{--                                       class="form-label">{{ __('custom.count') . ' ' . mb_strtolower(trans_choice('custom.results', 2)) }}--}}
{{--                                    :</label>--}}
{{--                                <select id="count_results" class="form-select" name="count_results">--}}
{{--                                    <option value="10">10</option>--}}
{{--                                    @php $selected = request()->get('count_results', '') == 20 ? 'selected' : '' @endphp--}}
{{--                                    <option value="20" {{ $selected }}>20</option>--}}
{{--                                    @php $selected = request()->get('count_results', '') == 30 ? 'selected' : '' @endphp--}}
{{--                                    <option value="30" {{ $selected }}>30</option>--}}
{{--                                    @php $selected = request()->get('count_results', '') == 40 ? 'selected' : '' @endphp--}}
{{--                                    <option value="40" {{ $selected }}>40</option>--}}
{{--                                    @php $selected = request()->get('count_results', '') == 50 ? 'selected' : '' @endphp--}}
{{--                                    <option value="50" {{ $selected }}>50</option>--}}
{{--                                </select>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}

                    <div class="row m-0 mb-5 action-btn-wrapper ">
                        <div class="col-md-6 px-0">
                            <button class="btn rss-sub main-color" type="submit"><i
                                    class="fas fa-search main-color"></i>Търсене
                            </button>
                            <a class="btn rss-sub main-color" href="{{ url()->current() }}">
                                <i class="fas fa-eraser"></i> {{ __('custom.clearing') }}
                            </a>
                        </div>

                        <div class="col-md-6 text-end col-md-6 px-0">
                            <input type="hidden" id="subscribe_model" value="App\Models\LegislativeInitiative">
                            <input type="hidden" id="subscribe_route_name" value="{{ request()->route()->getName() }}">
                            @includeIf('site.partial.subscribe-buttons', ['subscribe_params' => $requestFilter ?? [], 'hasSubscribeEmail' => $hasSubscribeEmail ?? false, 'hasSubscribeRss' => false, 'subscribe_list' => true])

                            @can('create', \App\Models\LegislativeInitiative::class)
                                <a href="{{ route('legislative_initiatives.create') }}"
                                   class="btn btn-success text-success mt-1">
                                    <i class="fas fa-circle-plus text-success me-1"></i>
                                    {{ __('custom.add') . ' ' . trans_choice('custom.legislative_initiatives_list', 1) }}
                                </a>
                            @endif
                        </div>
                    </div>
                </form>
            </div>
            @if(!$user || ($user && !$user->eauth))
                <div class="row">
                    <div class="col-12 mb-3">
                        @if(!$user)
                            <div class="main-color fw-bold">{!! __('site.new_li_actions_info') !!}</div>
                            <div class="main-color fw-bold">{!! __('site.vote_li_actions_info') !!}</div>
                        @elseif(!$user->eauth)
                            <div class="main-color fw-bold">{!! __('site.new_li_actions_info') !!}</div>
                        @endif
                    </div>
                </div>
            @endif
            <div class="row sort-row fw-600 main-color-light-bgr align-items-center rounded py-2 px-2 m-0">
                <div class="text-start col-md-1">
                    <i class="fas fa-info-circle text-primary " style="font-size: 20px" title="{{ __('site.sort_info_legislative_initiative') }}" data-html="true" data-bs-placement="top" data-bs-toggle="tooltip"><span class="d-none">.</span></i>
                </div>
{{--                <div class="col-md-4 cursor-pointer ">--}}
{{--                    @include('components.sortable-link', ['sort_by' => 'institutions', 'translation' => trans_choice('custom.institutions', 1)])--}}
{{--                </div>--}}

                <div class="col-md-4 cursor-pointer">
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
                                                       title="{{ __('custom.change_f') }} {{ __('custom.in') }} {{ $item->law?->name }}">
                                                        <h3>{{ __('custom.change_f') }} {{ __('custom.in') }}
                                                            "{{ $item->law?->name }}"</h3>
                                                    </a>
                                                </div>
                                                <div class="consult-item-header-edit">
                                                    @can('close', $item)
                                                        <form class="d-none"
                                                              method="POST"
                                                              action="{{ route('legislative_initiatives.close', $item) }}"
                                                              name="CLOSE_ITEM_{{ $item->id }}"
                                                        >
                                                            @csrf
                                                        </form>

                                                        <i class="open-close-modal fas fa-regular fa-times-circle float-end text-warning fs-4  ms-2"
                                                           role="button" title="{{ __('custom.close') }}"></i>
                                                    @endcan
                                                    @can('delete', $item)
                                                        <form class="d-none"
                                                              method="POST"
                                                              action="{{ route('legislative_initiatives.delete', $item) }}"
                                                              name="DELETE_ITEM_{{ $item->id }}"
                                                        >
                                                            @csrf
                                                        </form>

                                                            <i class="open-delete-modal fas fa-regular fa-trash-can float-end text-danger fs-4  ms-2"
                                                               role="button" title="{{ __('custom.deletion') }}"></i>
                                                    @endcan
{{--                                                    @can('update', $item)--}}
{{--                                                        <a href="{{ route('legislative_initiatives.edit', $item) }}">--}}
{{--                                                            <i class="fas fa-pen-to-square float-end main-color fs-4"--}}
{{--                                                               role="button" title="{{ __('custom.edit') }}">--}}
{{--                                                            </i>--}}
{{--                                                        </a>--}}
{{--                                                    @endcan--}}
                                                </div>
                                            </div>

                                            <a href="#" title="{{ $item->operationalProgram?->institution }}"
                                               class="text-decoration-none text-capitalize mb-3">
                                                {{ $item->operationalProgram?->institution }}
                                            </a>

                                            <div class="status">
                                                <div class="meta-consul justify-content-start">
                                                    <span class="me-2 mb-2"><strong>{{ __('validation.attributes.status') }}:</strong>
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
                                                        <span class="{{ $status_class }}">{{ __('custom.legislative_' . \Illuminate\Support\Str::lower($item->getStatus($item->status)->name)) }}</span>
                                                    </span>

                                                    @if($item->endAfterDays)
                                                        <span class="item-separator mb-2">|</span>
                                                        <span class="ms-2 mb-2">
                                                            <strong>  {{ __('custom.end_after') }}:</strong>
                                                            <span class="voted-li">
                                                            {{ $item->endAfterDays.' '.trans_choice('custom.days', $item->endAfterDays) }}
                                                            </span>
                                                        </span>
                                                    @endif

                                                    <span class="item-separator mb-2 ms-2">|</span>
                                                    <span class="ms-2 mb-2">
                                                        <strong>  {{ __('custom.supported_f') }}:</strong>
                                                        <span class="voted-li">
                                                            {{ $item->countLikes() }}
                                                            @if($item->countLikes() == 1)
                                                               {{ __('custom.once_count') }}
                                                            @else
                                                               {{ __('custom.times_count') }}
                                                            @endif
                                                        </span>
                                                    </span>
                                                </div>
                                            </div>

                                            <div class="row justify-content-between align-items-center">
                                                <div class="col-auto">
                                                    <div class="row">
                                                        <div class="col-auto">
                                                            <span class="text-secondary">
                                                                <i class="far fa-calendar text-secondary" title="{{ __('custom.creation_of') }}"></i> {{ $item->created_at->format('d.m.Y') }}{{ __('custom.year_short') }}
                                                            </span>
                                                        </div>

                                                        <div class="col-auto">
                                                                <span class="text-secondary">
                                                                    <i class="far fa-user text-secondary" title="{{ __('custom.author') }}"></i> {{ $item->user->fullName() }}
                                                                </span>
                                                        </div>

                                                        @if(!empty($item->active_support) && $item->daysLeft)
                                                            <div class="col-auto">
                                                                <span class="text-secondary">
                                                                    <i class="far fa-hourglass text-secondary" title="{{ __('custom.time_left') }}"></i> {{ $item->daysLeft }} {{ trans_choice('custom.days', ($item->daysLeft > 1 ? 2 : 1)) }}
                                                                </span>
                                                            </div>
                                                        @endif

                                                        <div class="col-auto">
                                                                <span class="text-secondary">
                                                                    <i class="far fa-comments text-secondary" title="{{ trans_choice('custom.comment', 2) }}"></i> {{ $item->comments->count() }}
                                                                </span>
                                                        </div>

                                                        <div class="col-auto">
                                                            <div class="mb-0">
                                                                <!-- LIKES -->

                                                                {{ $item->countLikes() }}

                                                                @if($item->userHasLike())
                                                                    <a href="@if($item->status == \App\Enums\LegislativeInitiativeStatusesEnum::STATUS_ACTIVE->value){{ route('legislative_initiatives.vote.revert', $item) }}@else{{ '#' }}@endif"
                                                                       @if(!$user) data-bs-toggle="tooltip" title="{{ __('messages.action_only_registered') }}" @endif
                                                                       class="me-2 text-decoration-none">
                                                                        <i class="fa fa-thumbs-up fs-18"
                                                                           aria-hidden="true"></i>
                                                                    </a>
                                                                @else
                                                                    <a href="@if($item->status == \App\Enums\LegislativeInitiativeStatusesEnum::STATUS_ACTIVE->value){{ route('legislative_initiatives.vote.store', [$item, 'like']) }}@else{{ '#' }}@endif"
                                                                        @if(!$user) data-bs-toggle="tooltip" title="{{ __('messages.action_only_registered') }}" @endif
                                                                       class="me-2 text-decoration-none">
                                                                        <i class="ms-1 fa fa-regular fa-thumbs-up main-color fs-18"></i>
                                                                    </a>
                                                                @endif


                                                                <!-- DISLIKES -->

                                                                {{ $item->countDislikes() }}

                                                                @if($item->userHasDislike())
                                                                    <a href="@if($item->status == \App\Enums\LegislativeInitiativeStatusesEnum::STATUS_ACTIVE->value){{ route('legislative_initiatives.vote.revert', $item) }}@else{{ '#' }}@endif"
                                                                       @if(!$user) data-bs-toggle="tooltip" title="{{ __('messages.action_only_registered') }}" @endif
                                                                       class="text-decoration-none">
                                                                        <i class="fa fa-thumbs-down fs-18"></i>
                                                                    </a>
                                                                @else
                                                                    <a href="@if($item->status == \App\Enums\LegislativeInitiativeStatusesEnum::STATUS_ACTIVE->value){{ route('legislative_initiatives.vote.store', [$item, 'dislike']) }}@else{{ '#' }}@endif"
                                                                       @if(!$user) data-bs-toggle="tooltip" title="{{ __('messages.action_only_registered') }}" @endif
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
                                                       title="{{ __('custom.change_f') }} {{ __('custom.in') }} {{ $item->law?->name }}">
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
                        {{ $items->onEachSide(0)->appends(request()->query())->links() }}
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

    @include('components.close-modal', [
        'cancel_btn_text'           => __('custom.cancel'),
        'continue_btn_text'         => __('custom.continue'),
        'title_text'                => __('custom.closing') . ' ' . __('custom.of') . ' ' . trans_choice('custom.legislative_initiatives', 1),
        'file_change_warning_txt'   => __('custom.are_you_sure_to_close') . ' ' . Str::lower(trans_choice('custom.legislative_initiatives_list', 1)) . '?',
    ])
@endsection
