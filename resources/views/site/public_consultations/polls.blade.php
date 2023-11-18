<div class="row mb-0 mt-4">
    <div class="col-md-12">
        <div class="custom-card py-4 px-3">
            <h3 class="mb-3">{{ __('site.public_consultation.polls') }}</h3>
            @if($item->polls->count())
                <form class="row" action="">
                    @foreach($item->polls as $pool)
                        <p class="main-color fs-18"># {{ $pool->name }}</p>
                        @if($pool->questions->count())
                            @foreach($pool->questions as $q)
                                <div class="col-md-6 mb-4">
                                    <div class="comment-background p-2 rounded">
                                        <p class="fw-bold fs-18 mb-2">{{ $q->name }}</p>
                                        @if($q->answers->count())
                                            @foreach($q->answers as $a)
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                                                    <label class="form-check-label" for="flexCheckDefault">
                                                        {{ $a->name }}
                                                    </label>
                                                </div>
                                            @endforeach
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    @endforeach
                    <div class="col-md-12">
                        <button class="btn btn-primary">
                            {{ __('custom.send') }}
                        </button>
                    </div>
                </form>
            @else
                ---
            @endif
        </div>
    </div>
</div>
