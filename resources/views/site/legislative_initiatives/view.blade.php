@extends('layouts.site', ['fullwidth' => true])

@section('content')
    <div class="row">
        @include('site.legislative_initiatives.side_menu')


        <div class="col-lg-10 py-2 right-side-content">
{{--            @if(isset($pageTopContent) && !empty($pageTopContent->value))--}}
{{--                <div class="row">--}}
{{--                    <div class="col-12 mb-5">--}}
{{--                        {!! $pageTopContent->value !!}--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            @endif--}}
            <div class="row">
                <div class="col-lg-10">
                    <h2 class="obj-title mb-4">{{ __('custom.change_f') }} {{ __('custom.in') }} {{ $item->law?->name }}</h2>
                </div>

                <div class="col-lg-2">
                    <div class="col-md-12 d-flex col-md-12 d-flex justify-content-end">
                        <span class="text-decoration-none d-flex align-items-center">
                            <i class="fa fa-regular fa-thumbs-up main-color" style="font-size:34px;"></i>
                        </span>
                        <span class="text-decoration-none support-count-li d-flex align-items-center ms-3 main-color">
                            {{ $item->countLikes() }}
                        </span>
                    </div>
                </div>
            </div>

            @if(auth()->user())
                <div class="row mb-4">
                    <div class="col-12">
                        <input type="hidden" id="subscribe_model" value="App\Models\LegislativeInitiative">
                        <input type="hidden" id="subscribe_model_id" value="{{ $item->id }}">
                        @includeIf('site.partial.subscribe-buttons', ['no_rss' => true])

                        @can('close', $item)
                            <form class="d-none"
                                  method="POST"
                                  action="{{ route('legislative_initiatives.close', $item) }}"
                                  name="CLOSE_ITEM_{{ $item->id }}"
                            >
                                @csrf
                            </form>

                            {{--                        <i class="open-close-modal fas fa-regular fa-times-circle float-end text-warning fs-4  ms-2"--}}
                            {{--                           role="button" title="{{ __('custom.close') }}"></i>--}}

                            <button href="{{ route('legislative_initiatives.edit', $item) }}"
                                    class="btn btn-primary open-close-modal">
                                <i class="fas fa-times-circle me-2"></i>
                                {{ __('custom.close') }}
                            </button>
                        @endcan
                        @can('delete', $item)
                            <form class="d-none"
                                  method="POST"
                                  action="{{ route('legislative_initiatives.delete', $item) }}"
                                  name="DELETE_ITEM_{{ $item->id }}"
                            >
                                @csrf
                            </form>

                            {{--                        <i class="open-delete-modal fas fa-regular fa-trash-can float-end text-danger fs-4  ms-2"--}}
                            {{--                           role="button" title="{{ __('custom.deletion') }}"></i>--}}

                            <button href="{{ route('legislative_initiatives.edit', $item) }}"
                                    class="btn btn-danger open-li-delete-modal">
                                <i class="fas fa-trash me-2"></i>
                                {{ __('custom.deletion') }}
                            </button>
                        @endcan
                    </div>
                </div>
            @endif

            @if($item->status == \App\Enums\LegislativeInitiativeStatusesEnum::STATUS_ACTIVE->value)
                <div class="row mt-2">
                    <div class="fst-italic text-danger mb-3">{{ __('custom.li_support_info', ['days' => $item->daysLeft, 'cap' => $item->cap]) }}</div>
                </div>
            @endif

            <div class="row mt-2">
                <div class="col-12">
                    <a href="#" class="text-decoration-none">
                        <span class="obj-icon-info">
                            <i class="far fa-calendar me-1 dark-blue" title="{{ __('custom.public_from') }}"></i>
                            {{ displayDate($item->created_at) . ' ' . __('custom.year_short') }}
                        </span>
                    </a>
                    @if($item->user)
                        <a href="{{ route('user.profile.li', $item->user) }}" class="text-decoration-none mx-2">
                            <span class="obj-icon-info dark-blue">
                                <i class="far fa-user me-1 dark-blue" title="{{ __('custom.author') }}"></i>
                                {{ $item->user->fullName() }}
                            </span>
                        </a>
                    @endif
                    <div class="mb-0 d-inline-block">
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
                        @if($item->endAfterDays)
                            <span class="fw-bold ms-2">{{ __('custom.end_after') }}:</span>
                            {{ $item->endAfterDays.' '.trans_choice('custom.days', $item->endAfterDays) }}
                        @endif


                        @if(!empty($item->active_support) && $item->daysLeft)
                            <span class="fw-bold ms-2">{{ __('custom.time_left') }}:</span>
                            {{ $item->daysLeft }} {{ trans_choice('custom.days', ($item->daysLeft > 1 ? 2 : 1)) }}
                        @endif
                        <!-- LIKES -->
                        <span class="fw-bold ms-2">{{ __('custom.supported_f') }}:</span>
                        {{ $item->countLikes() }}

                        @if($item->userHasLike())
                            <a href="@if($item->status == \App\Enums\LegislativeInitiativeStatusesEnum::STATUS_ACTIVE->value){{ route('legislative_initiatives.vote.revert', $item) }}@else{{ '#' }}@endif"
                               class="me-2 text-decoration-none">
                                <i class="fa fa-thumbs-up fs-18"
                                   aria-hidden="true"></i>
                            </a>
                        @else
                            <a href="@if($item->status == \App\Enums\LegislativeInitiativeStatusesEnum::STATUS_ACTIVE->value){{ route('legislative_initiatives.vote.store', [$item, 'like']) }}@else{{ '#' }}@endif"
                               class="me-2 text-decoration-none">
                                <i class="ms-1 fa fa-regular fa-thumbs-up main-color fs-18"></i>
                            </a>
                        @endif


                        <!-- DISLIKES -->

                        {{ $item->countDislikes() }}

                        @if($item->userHasDislike())
                            <a href="@if($item->status == \App\Enums\LegislativeInitiativeStatusesEnum::STATUS_ACTIVE->value){{ route('legislative_initiatives.vote.revert', $item) }}@else{{ '#' }}@endif"
                               class="text-decoration-none">
                                <i class="fa fa-thumbs-down fs-18"></i>
                            </a>
                        @else
                            <a href="@if($item->status == \App\Enums\LegislativeInitiativeStatusesEnum::STATUS_ACTIVE->value){{ route('legislative_initiatives.vote.store', [$item, 'dislike']) }}@else{{ '#' }}@endif"
                               class="text-decoration-none">
                                <i class="ms-1 fa fa-regular fa-thumbs-down main-color fs-18"></i>
                            </a>
                        @endif

                        @if($item->status == \App\Enums\LegislativeInitiativeStatusesEnum::STATUS_ACTIVE->value)
                            @if($needSupport > 0)
                                <span class="text-danger">(необходими са още {{ $needSupport }} {{ mb_strtolower(trans_choice('custom.votes', $needSupport)) }})</span>
                            @else
                                <span class="text-success fw-bold">(има необходимата подкрепа)</span>
                            @endif
                        @endif
                    </div>
                </div>

{{--                <div class="col-md-3 text-end">--}}
{{--                    @can('close', $item)--}}
{{--                        <form class="d-none"--}}
{{--                              method="POST"--}}
{{--                              action="{{ route('legislative_initiatives.close', $item) }}"--}}
{{--                              name="CLOSE_ITEM_{{ $item->id }}"--}}
{{--                        >--}}
{{--                            @csrf--}}
{{--                        </form>--}}

{{--                        <i class="open-close-modal fas fa-regular fa-times-circle float-end text-warning fs-4  ms-2"--}}
{{--                           role="button" title="{{ __('custom.close') }}"></i>--}}

{{--                        <button href="{{ route('legislative_initiatives.edit', $item) }}"--}}
{{--                                class="btn btn-primary open-close-modal">--}}
{{--                            <i class="fas fa-times-circle me-2"></i>--}}
{{--                            {{ __('custom.close') }}--}}
{{--                        </button>--}}
{{--                    @endcan--}}
{{--                    @can('delete', $item)--}}
{{--                        <form class="d-none"--}}
{{--                              method="POST"--}}
{{--                              action="{{ route('legislative_initiatives.delete', $item) }}"--}}
{{--                              name="DELETE_ITEM_{{ $item->id }}"--}}
{{--                        >--}}
{{--                            @csrf--}}
{{--                        </form>--}}

{{--                        <i class="open-delete-modal fas fa-regular fa-trash-can float-end text-danger fs-4  ms-2"--}}
{{--                           role="button" title="{{ __('custom.deletion') }}"></i>--}}

{{--                        <button href="{{ route('legislative_initiatives.edit', $item) }}"--}}
{{--                           class="btn btn-danger open-li-delete-modal">--}}
{{--                            <i class="fas fa-trash me-2"></i>--}}
{{--                            {{ __('custom.deletion') }}--}}
{{--                        </button>--}}
{{--                    @endcan--}}
{{--                </div>--}}
{{--                <div class="col-md-4 text-end">--}}
{{--                    @can('update', $item)--}}
{{--                        <a href="{{ route('legislative_initiatives.edit', $item) }}"--}}
{{--                           class="btn btn-sm btn-primary main-color">--}}
{{--                            <i class="fas fa-pen me-2 main-color"></i>--}}
{{--                            {{ __('custom.edit_of') . trans_choice('custom.legislative_initiatives_list', 1) }}--}}
{{--                        </a>--}}
{{--                    @endif--}}
{{--                </div>--}}
            </div>

            @if($item->receivers->count())
                <div class="row mt-4">
                    <div class="col-md-auto fw-bold">{{ __('custom.send_to_administrations_of') }}:</div>
                    <div class="col-md-9">
                        @foreach($item->receivers as $r)
                            <span class="d-block">
                                <a class="main-color text-decoration-none" href="{{ route('institution.profile', $r) }}" target="_blank">{{ $r->name }}</a>
                                ({{ displayDateTime($r->pivot->created_at) }})
                            </span>
                        @endforeach
                    </div>
                </div>
            @else
                @if($item->institutions->count())
                    <div class="row mt-4">
                        <div class="col-md-auto fw-bold">{{ __('custom.to_administrations_of') }}:</div>
                        <div class="col-md-9">
                            @foreach($item->institutions as $r)
                                <span class="d-block">
                                    <a class="main-color text-decoration-none" href="{{ route('institution.profile', $r) }}" target="_blank">{{ $r->name }}</a>
                                </span>
                            @endforeach
                        </div>
                    </div>
                @endif
            @endif

            <div class="row mt-2">
            </div>

            <hr class="custom-hr my-4"/>

            <div class="row">
                <div class="col-md-12 fw-bold py-2">
                    <span class="custom-left-border">{{ __('validation.attributes.law_paragraph') }}:</span>
                </div>
                <div class="col-12">{!! $item->law_paragraph !!}</div>
            </div>
            <div class="row">
                <div class="col-12 fw-bold py-2">
                    <span class="custom-left-border">
                        {{ __('validation.attributes.law_text') }}:
                    </span>
                </div>
                <div class="col-12">{!! $item->law_text !!}</div>
            </div>

            <div class="row">
                <div class="col-12 fw-bold py-2">
                    <span class="custom-left-border">
                        {{ __('custom.description_of_suggested_change') }}:
                    </span>
                </div>
                <div class="col-12">{!! $item->description !!}</div>
{{--                <div class="edit-li">--}}
{{--                    <p class="mb-4">--}}
{{--                        {!! $item->description !!}--}}
{{--                    </p>--}}

{{--                    <hr class="custom-hr"/>--}}
{{--                </div>--}}
            </div>
            @if($item->motivation)
                <div class="row">
                    <div class="col-12 fw-bold py-2">
                        <span class="custom-left-border">
                            {{ __('custom.change_motivations') }}:
                        </span>
                    </div>
                    <div class="col-12">{!! $item->motivation !!}</div>
                </div>
            @endif
            <div class="row my-4">
                <div class="col-md-12">
                    <div class="custom-card p-3">
                        <h3 class="mb-3">{{ trans_choice('custom.comments', 2) }}</h3>
                        @can('comment', $item)
                            @if((int)$item->status === \App\Enums\LegislativeInitiativeStatusesEnum::STATUS_ACTIVE->value)
                                <div class="col-md-12 my-4">
                                    <div>
                                        <form class="mb-0" method="POST" action="{{ route('legislative_initiatives.comments.store') }}">
                                            @csrf

                                            <input type="hidden" name="legislative_initiative_id" value="{{ $item->id }}"/>

                                            <div class="form-group">
                                                <!--  <div class="summernote-wrapper mb-3">
                                                    -- Вътре се се слага textarea с клас "summernote"
                                                      </div>   -->
                                                <textarea name="description" class="form-control mb-3 rounded summernote"
                                                          id="description" rows="2"
                                                          placeholder="{{ __('custom.enter_comment') }}">
                                                 </textarea>
                                            </div>

                                            <button type="submit"
                                                    class="btn btn-primary mt-3">{{ __('custom.add_comment') }}</button>
                                        </form>
                                    </div>
                                </div>
                            @endif
                        @endcan
                        @if(isset($item->comments) && $item->comments->count() > 0)
                            @if(!auth()->user())
                                <div class="row">
                                    <div class="col-12 mb-3">
                                        <div class="main-color fw-bold">{!! __('site.vote_li_actions_info') !!}</div>
                                    </div>
                                </div>
                            @endif
                            @foreach($item->comments as $key => $comment)
                                <div class="obj-comment comment-background p-2 rounded mb-3">
                                    <div class="info">
                                        <a class="obj-icon-info me-2 main-color fs-18 fw-600 text-decoration-none" @if($comment->user) href="{{ route('user.profile.pc', $comment->user) }}" @endif>
                                            <i class="fa fa-solid fa-circle-user me-2 main-color" title="{{ __('custom.author') }}"></i>{{ $comment->user ? $comment->user->fullName() : __('custom.anonymous') }}
                                        </a>
{{--                                        <span class="obj-icon-info me-2 main-color fs-18 fw-600">--}}
{{--                                            <i class="fa fa-solid fa-circle-user me-2 main-color"--}}
{{--                                               title="{{ __('custom.author') }}"></i>--}}
{{--                                            {{ $comment->user->fullName() }}--}}
{{--                                        </span>--}}

                                        <span class="obj-icon-info me-2 text-muted">
                                            {{ \Carbon\Carbon::parse($comment->created_at)->format('d.m.Y h:i') }}
                                        </span>

                                        @if(auth()->check() && $comment->user_id === auth()->user()->id)
                                            <form class="d-none"
                                                  method="POST"
                                                  action="{{ route('legislative_initiatives.comments.delete', $comment) }}"
                                                  name="DELETE_COMMENT_{{ $key }}"
                                            >
                                                @csrf
                                            </form>

                                            <a href="#" class="open-delete-modal">
                                                <i class="fas fa-regular fa-trash-can float-end text-danger fs-4 ms-2"
                                                   role="button" title="{{ __('custom.delete') }}"></i>
                                            </a>
                                        @endif
                                    </div>

                                    <div class="comment rounded py-2">
                                        <p class="mb-2">
                                            {{ $comment->description }}
                                        </p>

                                        <div class="mb-0">
                                            <!-- LIKES -->

                                            {{ $comment->countLikes() }}

                                            @if($comment->userHasLike())
                                                <a href="@can('comment', $item){{ route('legislative_initiatives.comments.stats.revert', $comment) }}@else{{ '#' }}@endcan"
                                                   class="me-2 text-decoration-none">
                                                    <i class="fa fa-thumbs-up fs-18" aria-hidden="true"></i>
                                                </a>
                                            @else
                                                <a href="@can('comment', $item){{ route('legislative_initiatives.comments.stats.store', [$comment, 'like']) }}@else{{ '#' }}@endcan"
                                                   class="me-2 text-decoration-none">
                                                    <i class="ms-1 fa fa-regular fa-thumbs-up main-color fs-18"></i>
                                                </a>
                                            @endif

                                            <!-- DISLIKES -->

                                            {{ $comment->countDislikes() }}
                                            @if($comment->userHasDislike())
                                                <a href="@can('comment', $item){{ route('legislative_initiatives.comments.stats.revert', [$comment]) }}@else{{ '#' }}@endcan"
                                                   class="text-decoration-none">
                                                    <i class="fa fa-thumbs-down fs-18"></i>
                                                </a>
                                            @else
                                                <a href="@can('comment', $item){{ route('legislative_initiatives.comments.stats.store', [$comment, 'dislike']) }}@else{{ '#' }}@endcan"
                                                   class="text-decoration-none">
                                                    <i class="ms-1 fa fa-regular fa-thumbs-down main-color fs-18"></i>
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('components.delete-modal', [
        'cancel_btn_text'           => __('custom.cancel'),
        'continue_btn_text'         => __('custom.continue'),
        'title_text'                => __('custom.deletion') . ' ' . __('custom.of') . ' ' . trans_choice('custom.comments', 1),
        'file_change_warning_txt'   => __('custom.legislative_comment_delete_warning'),
    ])

    @include('components.close-modal', [
        'cancel_btn_text'           => __('custom.cancel'),
        'continue_btn_text'         => __('custom.continue'),
        'title_text'                => __('custom.closing') . ' ' . __('custom.of') . ' ' . trans_choice('custom.legislative_initiatives', 1),
        'file_change_warning_txt'   => __('custom.are_you_sure_to_close') . ' ' . Str::lower(trans_choice('custom.legislative_initiatives_list', 1)) . '?',
    ])
@endsection

@push('scripts')
    <script type="text/javascript">
        $(document).ready(function (){
            $('.open-li-delete-modal').on('click', function () {
                const form = $(this).parent().find('form').attr('name');

                new MyModal({
                    title: @json(__('custom.deletion') . ' ' . __('custom.of') . ' ' . trans_choice('custom.legislative_initiatives', 1)),
                    footer: '<button class="btn btn-sm btn-success ms-3" onclick="' + form + '.submit()">' + @json(__('custom.continue')) + '</button>' +
                        '<button class="btn btn-sm btn-danger closeModal ms-3" data-dismiss="modal" aria-label="' + @json(__('custom.cancel')) + '">' + @json(__('custom.cancel')) + '</button>',
                    body: '<div class="alert alert-danger">' + @json(__('custom.are_you_sure_to_delete') . ' ' . Str::lower(trans_choice('custom.legislative_initiatives_list', 1)) . '?') + '</div>',
                });
            });
        });
    </script>

@endpush
