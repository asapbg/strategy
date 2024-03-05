<div class="card custom-card mb-2">
    <div class="card-header" id="headingcat{{ $item->id }}">
        <div class="row">
            <span class="text-dark fw-bold fs-18 me-3">{{ __('ogp.proposal_number', ['number' => $item->id]) }}</span>
        </div>
    </div>
    <div class="card-body">
        <div class="text-decoration-none row mb-2">
            <p class="col-md-8 fw-600 fs-5 mb-1">
                <span class="obj-icon-info me-2 main-color fs-18">
                    <i class="fa fa-solid fa-circle-user me-1 main-color" data-bs-toggle="tooltip" title="{{ __('ogp.author_proposal') }}"></i>
                    {{ $item->author->fullName() }}
                </span>

                <i class="fa-regular fa-calendar ms-2 me-1 main-color" data-bs-toggle="tooltip" title="{{ __('custom.from') }}"></i>
                <span class="obj-icon-info me-1 text-muted fs-18">{{ displayDateTime($item->created_at) }}</span>

                <i class="fa-solid fa-hourglass-half ms-2 me-1 main-color" data-bs-toggle="tooltip" title="{{ __('custom.proposal_votes_period') }}"></i>
                <span class="obj-icon-info me-1 text-muted fs-18">{{ displayDate($item->planArea?->plan?->from_date_develop) }} - {{ displayDate($item->planArea?->plan?->to_date_develop) }}</span>
            </p>
            <div class="col-md-4">
                <div class="text-end mb-0" id="offer-vote-{{ $item->id }}">
                    @include('site.ogp.partial.vote', ['item' => $item, 'route' => 'ogp.develop_new_action_plans.vote', 'container' => 'offer-vote-'.$item->id])
                </div>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-12">
                {!! $item->content !!}
            </div>
        </div>
        <div class="row" id="offer-comments-{{ $item->id }}">
            @include('site.ogp.develop_new_action_plan.add_comment', ['offer' => $item])
            @php($cntComments = $item->comments->count())
            @if($cntComments)
                @foreach($item->comments()->orderBy('created_at', 'desc')->get() as $comment)
                    @include('site.ogp.develop_new_action_plan.comment_row')
                @endforeach
            @endif
        </div>

    </div>
</div>
