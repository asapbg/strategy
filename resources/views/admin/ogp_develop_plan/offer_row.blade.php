<div class="card card-primary card-outline">
    <div class="card-header" id="heading{{ $iteration }}">
        <h2 class="mb-0 d-flex flex-row justify-content-between">
            <button class="btn btn-link btn-block text-left fw-bold @if($iteration == 1) collapsed @endif" type="button" data-toggle="collapse" data-target="#collapse{{ $iteration }}" aria-expanded="@if($iteration == 1){{ 'true' }}@else{{ 'false' }}@endif" aria-controls="collapse{{ $iteration }}">
                {{ $iteration }}. {{ trans_choice('ogp.proposals', 1) }}
                <span class="float-right">
                    <span class="me-2 main-color text-decoration-none">
                        {{ $item->likes_cnt }} <i class="ms-1 fa fa-regular fa-thumbs-up text-success fs-18"></i>
                    </span>
                    <span class="main-color text-decoration-none">
                        {{ $item->dislikes_cnt }} <i class="ms-1 fa fa-regular fa-thumbs-down text-danger fs-18"></i>
                    </span>
                </span>
            </button>
{{--                    @can('update', $item)--}}
{{--                    <a href="{{ route('admin.ogp.plan.arrangement.edit', ['id' => $item->id, 'ogpPlanArea' => $item->ogp_plan_area_id]) }}" class="btn btn-sm btn-info mr-1 float-end" title="Редакция">--}}
{{--                        <i class="fas fa-edit"></i>--}}
{{--                    </a>--}}
{{--                    @endcan--}}
        </h2>
            </div>
        <div id="collapse{{ $iteration }}" class="collapse @if($iteration == 1) show @endif" aria-labelledby="heading{{ $iteration }}" data-parent="#accordionExample">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="col-auto control-label"><i class="fas fa-user-alt text-primary me-1"></i> {{ __('ogp.author_proposal') }}: </label> {{ $item->author->fullName() }}
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="form-group">
                            <label class="col-auto control-label"><i class="fas fa-file-alt text-primary me-1"></i>{{ trans_choice('ogp.proposals', 1) }}: </label>
                        </div>
                    </div>
                    <div class="col-12 ps-3">
                        {!! $item->content !!}
                    </div>
                </div>
{{--                    @include('site.ogp.develop_new_action_plan.add_comment', ['offer' => $item])--}}
                @php($cntComments = $item->comments->count())
                @if($cntComments)
                    <div class="row mt-3" id="offer-comments-{{ $item->id }}">
                        <div class="col-12">
                            <div class="form-group mb-0">
                                <label class="col-auto control-label"><i class="fas fa-comment-alt text-primary me-1"></i>{{ trans_choice('custom.comment', 2) }} </label>
                            </div>
                            <hr class="custom-hr mt-1">
                        </div>
                        @foreach($item->comments()->orderBy('created_at', 'desc')->get() as $comment)
                            @include('admin.ogp_develop_plan.comment_row')
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
</div>
