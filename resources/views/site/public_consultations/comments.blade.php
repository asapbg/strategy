<div class="row mb-3 mt-4">
    <div class="col-md-12">
        <div class="custom-card py-4 px-3">
            <h3 class="mb-3">
                {{ trans_choice('custom.comments', 2) }}
                @php($fPdf = $item->commentsDocumentPdf())
                @if($fPdf)
                    | <a class="mr-3" style="font-size: 16px" href="{{ route('download.file', $fPdf) }}" target="_blank" title="{{ __('custom.download') }}">
                        {!! fileIcon($fPdf->content_type) !!} {{ $fPdf->{'description_'.$fPdf->locale} }}
                    </a>
                @endif
                @php($fCsv = $item->commentsDocumentCsv())
                @if($fCsv)
                    | <a class="mr-3" style="font-size: 16px" href="{{ route('download.file', $fCsv) }}" target="_blank" title="{{ __('custom.download') }}">
                        {!! fileIcon($fCsv->content_type) !!} {{ $fCsv->{'description_'.$fCsv->locale} }}
                    </a>
                @endif
            </h3>
            @if($item->comments->count())
                @foreach($item->comments as $c)
                    <div class="obj-comment comment-background p-2 rounded mb-3">
                        <div class="info">
                            <a class="obj-icon-info me-2 main-color fs-18 fw-600 text-decoration-none"
                               @if($c->author) href="{{ route('user.profile.pc', $c->author) }}" @endif
                            >
                                <i class="fa fa-solid fa-circle-user me-2 main-color" title="{{ __('custom.author') }}">
                                </i>{{ $c->author ? $c->author->fullName() : __('custom.anonymous') }}
                            </a>
                            <span class="obj-icon-info me-2 text-muted">{{ displayDateTime($c->created_at) }}</span>
                        </div>
                        <div class="comment rounded py-2 limit-length">
                            {!! $c->content !!}
                        </div>
                        <div class="comment rounded py-2 full-length d-none">
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
                        <div class="summernote-wrapper">
                            <textarea class="form-control summernote mb-3 rounded @error('content') is-invalid @enderror" id="content" name="content" rows="2" placeholder="{{ __('custom.enter_comment') }}">{{ old('content', '') }}</textarea>
                        </div>
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
