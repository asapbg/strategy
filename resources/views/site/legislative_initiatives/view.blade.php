@extends('layouts.site', ['fullwidth' => true])

@section('content')
    <div class="row">
        @include('site.legislative_initiatives.side_menu')


        <div class="col-lg-10 py-5 right-side-content">
            @if(isset($pageTopContent) && !empty($pageTopContent->value))
                <div class="row">
                    <div class="col-12 mb-5">
                        {!! $pageTopContent->value !!}
                    </div>
                </div>
            @endif
            <div class="row">
                <div class="col-lg-10">
                    <h2 class="obj-title mb-4">{{ __('custom.change_f') }} {{ __('custom.in') }} {{ mb_strtolower($item->operationalProgram?->value) }}</h2>
                </div>

                <div class="col-lg-2">
                    <div class="col-md-12 d-flex col-md-12 d-flex justify-content-end">
                        <a href="#" class="text-decoration-none d-flex align-items-center">
                            <i class="fa fa-regular fa-thumbs-up main-color" style="font-size:34px;"></i>
                        </a>
                        <a href="#" class="text-decoration-none support-count-li d-flex align-items-center ms-3">
                            {{ $item->countLikes() }}
                        </a>
                    </div>
                </div>
            </div>

            <div class="row mt-2">
                <div class="col-md-8">
                    <a href="#" class="text-decoration-none">
                        <span class="obj-icon-info me-2">
                            <i class="far fa-calendar me-1 dark-blue" title="{{ __('custom.public_from') }}"></i>
                            {{ \Carbon\Carbon::parse($item->created_at)->format('d.m.Y') . ' ' . __('custom.year_short') }}
                        </span>
                    </a>
                </div>

                <div class="col-md-4 text-end">
                    @if(auth()->check() && auth()->user()->id === $item->author_id)
                        <a href="{{ route('legislative_initiatives.edit', $item) }}"
                           class="btn btn-sm btn-primary main-color">
                            <i class="fas fa-pen me-2 main-color"></i>
                            {{ __('custom.edit_of') . trans_choice('custom.legislative_initiatives_list', 1) }}
                        </a>
                    @endif
                </div>
            </div>

            <hr class="custom-hr my-4"/>

            <div class="row">
                <div class="edit-li">
                    <p class="mb-4">
                        {!! $item->description !!}
                    </p>

                    <hr class="custom-hr"/>
                </div>
            </div>

            <div class="row my-4">
                <div class="col-md-12">
                    <div class="custom-card p-3">
                        <h3 class="mb-3">{{ trans_choice('custom.comments', 2) }}</h3>
                        @if(isset($item->comments) && $item->comments->count() > 0)
                            @foreach($item->comments as $key => $comment)
                                <div class="obj-comment comment-background p-2 rounded mb-3">
                                    <div class="info">
                                        <span class="obj-icon-info me-2 main-color fs-18 fw-600">
                                            <i class="fa fa-solid fa-circle-user me-2 main-color"
                                               title="{{ __('custom.author') }}"></i>
                                            {{ $comment->user->fullName() }}
                                        </span>

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
                                                <a href="{{ route('legislative_initiatives.comments.stats.revert', $comment) }}"
                                                   class="me-2 text-decoration-none">
                                                    <i class="fa fa-thumbs-up fs-18" aria-hidden="true"></i>
                                                </a>
                                            @else
                                                <a href="{{ route('legislative_initiatives.comments.stats.store', [$comment, 'like']) }}"
                                                   class="me-2 text-decoration-none">
                                                    <i class="ms-1 fa fa-regular fa-thumbs-up main-color fs-18"></i>
                                                </a>
                                            @endif

                                            <!-- DISLIKES -->

                                            {{ $comment->countDislikes() }}
                                            @if($comment->userHasDislike())
                                                <a href="{{ route('legislative_initiatives.comments.stats.revert', [$comment]) }}"
                                                   class="text-decoration-none">
                                                    <i class="fa fa-thumbs-down fs-18"></i>
                                                </a>
                                            @else
                                                <a href="{{ route('legislative_initiatives.comments.stats.store', [$comment, 'dislike']) }}"
                                                   class="text-decoration-none">
                                                    <i class="ms-1 fa fa-regular fa-thumbs-down main-color fs-18"></i>
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endif

                        @if((int)$item->status === \App\Enums\LegislativeInitiativeStatusesEnum::STATUS_ACTIVE->value)
                            <div class="col-md-12 mt-4">
                                <div>
                                    <form class="mb-0" method="POST" action="{{ route('legislative_initiatives.comments.store') }}">
                                        @csrf

                                        <input type="hidden" name="legislative_initiative_id" value="{{ $item->id }}"/>

                                        <div class="form-group">
                                            <!--  <div class="summernote-wrapper mb-3">
                                                -- Вътре се се слага textarea с клас "summernote"
                                                  </div>   -->

                                            <textarea name="description" class="form-control mb-3 rounded"
                                            id="description" rows="2"
                                            placeholder="{{ __('custom.enter_comment') }}">
                                             </textarea>


                                        </div>

                                        <button type="submit"
                                                class="btn btn-primary">{{ __('custom.add_comment') }}</button>
                                    </form>
                                </div>
                            </div>
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
@endsection
