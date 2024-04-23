@extends('layouts.site', ['fullwidth' => true])

@section('content')
    <div class="row">
        @php
            $user = auth()->user()
        @endphp
        @include('site.public_profiles.institution_menu')
        <div class="col-lg-10 right-side-content py-5">
            <div class="row mb-2">
                <h2 class="mb-4">
                    {{ __('site.institution.li.title', ['name' => $item->name]) }}
                </h2>
                @if($li->count())
                    @foreach($li as $row)
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
                                                        <a href="{{ route('legislative_initiatives.view', $row) }}"
                                                           class="text-decoration-none"
                                                           title="{{ __('custom.change_f') }} {{ __('custom.in') }} {{ $row->law?->name }}">
                                                            <h3>{{ __('custom.change_f') }} {{ __('custom.in') }}
                                                                "{{ $row->law?->name }}"</h3>
                                                        </a>
                                                    </div>
                                                    <div class="consult-item-header-edit">
                                                        @can('close', $row)
                                                            <form class="d-none"
                                                                  method="POST"
                                                                  action="{{ route('legislative_initiatives.close', $row) }}"
                                                                  name="CLOSE_ITEM_{{ $row->id }}"
                                                            >
                                                                @csrf
                                                            </form>

                                                            <i class="open-close-modal fas fa-regular fa-times-circle float-end text-warning fs-4  ms-2"
                                                               role="button" title="{{ __('custom.close') }}"></i>
                                                        @endcan
                                                        @can('delete', $row)
                                                            <form class="d-none"
                                                                  method="POST"
                                                                  action="{{ route('legislative_initiatives.delete', $row) }}"
                                                                  name="DELETE_ITEM_{{ $row->id }}"
                                                            >
                                                                @csrf
                                                            </form>

                                                            <i class="open-delete-modal fas fa-regular fa-trash-can float-end text-danger fs-4  ms-2"
                                                               role="button" title="{{ __('custom.deletion') }}"></i>
                                                        @endcan
                                                    </div>
                                                </div>

                                                <a href="#" title="{{ $row->operationalProgram?->institution }}"
                                                   class="text-decoration-none text-capitalize mb-3">
                                                    {{ $row->operationalProgram?->institution }}
                                                </a>

                                                <div class="status">
                                                    <div class="meta-consul justify-content-start">
                                                    <span class="me-2 mb-2"><strong>{{ __('validation.attributes.status') }}:</strong>
                                                        @php
                                                            $status_class = 'active-li';
                                                            switch ($row->getStatus($row->status)->name) {
                                                                case 'STATUS_CLOSED':
                                                                    $status_class = 'closed-li';
                                                                    break;

                                                                case 'STATUS_SEND':
                                                                    $status_class = 'send-li';
                                                                    break;
                                                            }
                                                    @endphp
                                                    <span class="{{ $status_class }}">{{ __('custom.legislative_' . \Illuminate\Support\Str::lower($row->getStatus($row->status)->name)) }}</span>
                                                </span>

                                                    @if($row->endAfterDays)
                                                        <span class="item-separator mb-2">|</span>
                                                        <span class="ms-2 mb-2">
                                                        <strong>  {{ __('custom.end_after') }}:</strong>
                                                        <span class="voted-li">
                                                        {{ $row->endAfterDays.' '.trans_choice('custom.days', $row->endAfterDays) }}
                                                        </span>
                                                    </span>
                                                    @endif

                                                    <span class="item-separator mb-2">|</span>
                                                    <span class="ms-2 mb-2">
                                                    <strong>  {{ __('custom.supported_f') }}:</strong>
                                                    <span class="voted-li">
                                                        {{ $row->countLikes() }}
                                                        @if($row->countLikes() == 1)
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
                                                            <i class="far fa-calendar text-secondary" title="{{ __('custom.creation_of') }}"></i> {{ $row->created_at->format('d.m.Y') }}{{ __('custom.year_short') }}
                                                        </span>
                                                        </div>

                                                        <div class="col-auto">
                                                            <span class="text-secondary">
                                                                <i class="far fa-user text-secondary" title="{{ __('custom.author') }}"></i> {{ $row->user->fullName() }}
                                                            </span>
                                                        </div>

                                                        @if(!empty($row->active_support) && $row->daysLeft)
                                                            <div class="col-auto">
                                                            <span class="text-secondary">
                                                                <i class="far fa-hourglass text-secondary" title="{{ __('custom.time_left') }}"></i> {{ $row->daysLeft }} {{ trans_choice('custom.days', ($row->daysLeft > 1 ? 2 : 1)) }}
                                                            </span>
                                                            </div>
                                                        @endif

                                                        <div class="col-auto">
                                                            <span class="text-secondary">
                                                                <i class="far fa-comments text-secondary" title="{{ trans_choice('custom.comment', 2) }}"></i> {{ $row->comments->count() }}
                                                            </span>
                                                        </div>

                                                        <div class="col-auto">
                                                            <div class="mb-0">
                                                                <!-- LIKES -->

                                                                {{ $row->countLikes() }}

                                                                @if($row->userHasLike())
                                                                    <a href="@if($row->status == \App\Enums\LegislativeInitiativeStatusesEnum::STATUS_ACTIVE->value){{ route('legislative_initiatives.vote.revert', $row) }}@else{{ '#' }}@endif"
                                                                       @if(!$user) data-bs-toggle="tooltip" title="{{ __('messages.action_only_registered') }}" @endif
                                                                       class="me-2 text-decoration-none">
                                                                        <i class="fa fa-thumbs-up fs-18"
                                                                           aria-hidden="true"></i>
                                                                    </a>
                                                                @else
                                                                    <a href="@if($row->status == \App\Enums\LegislativeInitiativeStatusesEnum::STATUS_ACTIVE->value){{ route('legislative_initiatives.vote.store', [$row, 'like']) }}@else{{ '#' }}@endif"
                                                                       @if(!$user) data-bs-toggle="tooltip" title="{{ __('messages.action_only_registered') }}" @endif
                                                                       class="me-2 text-decoration-none">
                                                                        <i class="ms-1 fa fa-regular fa-thumbs-up main-color fs-18"></i>
                                                                    </a>
                                                                @endif


                                                                <!-- DISLIKES -->

                                                                {{ $row->countDislikes() }}

                                                                @if($row->userHasDislike())
                                                                    <a href="@if($row->status == \App\Enums\LegislativeInitiativeStatusesEnum::STATUS_ACTIVE->value){{ route('legislative_initiatives.vote.revert', $row) }}@else{{ '#' }}@endif"
                                                                       @if(!$user) data-bs-toggle="tooltip" title="{{ __('messages.action_only_registered') }}" @endif
                                                                       class="text-decoration-none">
                                                                        <i class="fa fa-thumbs-down fs-18"></i>
                                                                    </a>
                                                                @else
                                                                    <a href="@if($row->status == \App\Enums\LegislativeInitiativeStatusesEnum::STATUS_ACTIVE->value){{ route('legislative_initiatives.vote.store', [$row, 'dislike']) }}@else{{ '#' }}@endif"
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
                                                    <a href="{{ route('legislative_initiatives.view', $row) }}"
                                                       title="{{ __('custom.change_f') }} {{ __('custom.in') }} {{ $row->law?->name }}">
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
                @else
                    <div class="col-lg-6 mb-4 ">
                        <p class="main-color">{{ __('messages.records_not_found') }}</p>
                    </div>
                @endif
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
