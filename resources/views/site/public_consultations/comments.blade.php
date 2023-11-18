<div class="row mb-3 mt-4">
    <div class="col-md-12">
        <div class="custom-card py-4 px-3">
            <h3 class="mb-3">{{ trans_choice('custom.comments', 2) }}</h3>

            @if($item->comments->count())
                @foreach($item->comments as $c)
                    <div class="obj-comment comment-background p-2 rounded mb-3">
                        <div class="info">
                      <span class="obj-icon-info me-2 main-color fs-18 fw-600">
                        <i class="fa fa-solid fa-circle-user me-2 main-color" title="{{ __('custom.author') }}"></i>{{ $c->author ? $c->author->fullName() : __('custom.anonymous') }}</span>
                            <span class="obj-icon-info me-2 text-muted">{{ displayDateTime($c->created_at) }}</span>
                        </div>
                        <div class="comment rounded py-2">
                            {!! $c->content !!}
                        </div>
                    </div>
                @endforeach
            @endif

            @can('comment', $item)
                <div class="col-md-12 mt-4">
                    <form action="{{ route('public_consultation.comment.add') }}" method="post">
                        @csrf
                        <input type="hidden" value="{{ $item->id }}" name="id">
                        <textarea class="form-control summernote mb-3 rounded @error('content') is-invalid @enderror" id="content" name="content" rows="2" placeholder="{{ __('custom.enter_comment') }}">{{ old('content', '') }}</textarea>
                        @error('content')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                        <button class="cstm-btn btn btn-primary m-0 mt-2">{{ __('custom.add_comment') }}</button>
                    </form>
                </div>
            @endcan
        </div>
    </div>
</div>
