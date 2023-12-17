<div class="accordion-item">
    <h2 class="accordion-header" id="heading_{{ $loop->iteration }}">
        <button class="accordion-button text-dark fs-18 fw-600" type="button" data-toggle="collapse"
                data-target="#collapse_{{ $loop->iteration }}" aria-expanded="true" aria-controls="collapse_{{ $loop->iteration }}">
            {{ __('ogp.proposal_number', ['number' => $loop->iteration]) }}  <span class="ms-1 fs-18 fw-normal">{{ __('custom.from') }} {{ $item->author->fullName() }} {{ displayDate($item->created_at) }}</span>
        </button>
    </h2>
    <div id="collapse_{{ $loop->iteration }}" class="accordion-collapse collapse show" aria-labelledby="heading_{{ $loop->iteration }}" data-parent="#accordionExample">
        <div class="accordion-body">

            <div class="custom-card p-3 mb-2">
                <div class="row mb-3">
                    <div class="suggestion-content mb-2 ">
                        <div class="row br-30">
                            <div class="new-plan-author">
                                <div class="info mb-4">
                                    <div class="row">
                                        <div class="col-6">
                                            <p class="fw-600 fs-5 mb-1">{{ __('ogp.author_proposal') }}: </p>
                                        </div>
                                        <div class="col-6">
                                            @can('update', $item)
                                            <a href="{{ route('ogp.develop_new_action_plans.edit_offer', $item->id) }}">
                                                <i class="fas fa-pen-to-square float-end main-color fs-4" role="button" title="{{ __('custom.edit') }}"></i>
                                            </a>
                                            @endcan
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-6">
                                            <div class="text-decoration-none">
                                                <span class="obj-icon-info me-2 main-color fs-18 fw-600">
                                                    <i class="fa fa-solid fa-circle-user me-2 main-color" title="{{ __('custom.author') }}"></i>
                                                    {{ $item->author->fullName() }}
                                                </span>
                                                <span class="obj-icon-info me-2 text-muted">{{ displayDateTime($item->created_at) }}</span>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="text-end mb-0">
                                                <a href="#" class="me-2 text-decoration-none">
                                                    10 <i class="ms-1 fa fa-regular fa-thumbs-up main-color fs-18"></i>
                                                </a>
                                                <a href="#" class="text-decoration-none">
                                                    1 <i class="ms-1 fa fa-regular fa-thumbs-down main-color fs-18"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @include('site.ogp.develop_new_action_plan.commitments')
                            <div class="col-md-12">
                                <hr class="custom-hr">
                            </div>
                        </div>

                    </div>
                </div>

                {{--comments --}}
                <div id="offer-comments-{{ $item->id }}">
                    @each('site.ogp.develop_new_action_plan.comment_row', $item->comments, 'comment')
                </div>
                @include('site.ogp.develop_new_action_plan.add_comment', ['offer' => $item])
            </div>
        </div>
    </div>
</div>
