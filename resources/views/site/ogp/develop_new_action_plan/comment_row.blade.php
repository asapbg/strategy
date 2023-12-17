<div class="row mb-3">
    <div class="col-md-12">
        <div class="obj-comment comment-background p-2 rounded mb-3">
            <div class="info">
                <span class="obj-icon-info me-2 main-color fs-18 fw-600">
                    <i class="fa fa-solid fa-circle-user me-2 main-color" title="{{ __('custom.author') }}"></i>
                    {{ $comment->author->fullname() }}
                </span>
                <span class="obj-icon-info me-2 text-muted">{{ displayDateTime($comment->created_at) }}</span>
                @can('delete', $comment)
                <a href="#">
                    <i class="fas fa-regular fa-trash-can float-end text-danger fs-4  ms-2" role="button" title="{{ __('custom.delete') }}"></i>
                </a>
                @endif
            </div>
            <div class="comment rounded py-2">
                <p class="mb-2">{!! $comment->content !!}</p>
            </div>
        </div>
    </div>
</div>
