<div class="card custom-card mb-2">
    <div class="card-header" id="headingcat{{ $loop->iteration }}">
        <div class="row">
            <span class="text-dark fw-bold fs-18 me-3">{{ __('ogp.proposal_number', ['number' => $loop->iteration]) }}</span>
        </div>
    </div>
    <div class="card-body">
        <div class="row">
            <p class="fw-600 fs-5 mb-1">{{ __('ogp.author_proposal') }}: </p>
        </div>
        <div class="text-decoration-none row mb-2">
            <p class="col-md-8">
                <span class="obj-icon-info me-2 main-color fs-18 fw-600">
                    <i class="fa fa-solid fa-circle-user me-2 main-color" title="{{ __('custom.author') }}"></i>
                    {{ $item->author->fullName() }}
                </span>
                <span class="obj-icon-info me-2 text-muted">{{ displayDateTime($item->created_at) }}</span>
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
                    @break($loop->iteration > 3)
                    @include('site.ogp.develop_new_action_plan.comment_row')
                @endforeach
                @if($cntComments > 3)
                    <div class="col-12">
                        <a href="#">{{ __('custom.to_all_comments') }}</a>
                    </div>
                @endif
            @endif
        </div>

    </div>
</div>
