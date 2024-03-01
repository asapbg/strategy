<div class="col-12" id="comment-row-{{ $comment->id }}">
    <div class="obj-comment comment-background p-2 rounded">
        <div class="info">
            <span class="obj-icon-info me-2 main-color fs-18 fw-600 custom-left-border">
{{--                <i class="fas fa-user me-1 main-color" title="{{ __('custom.author') }}"></i>--}}
                {{ $comment->author->fullname() }}
            </span>
            <span class="obj-icon-info me-2 text-muted">{{ displayDateTime($comment->created_at) }}</span>
{{--            @can('delete', $comment)--}}
{{--                @php--}}
{{--                    $modalId = 'modal_comment_'. $comment->id;--}}
{{--                    $deleteUrl = route('ogp.develop_new_action_plans.delete_comment', $comment->id);--}}
{{--                    $rowId = 'comment-row-'. $comment->id;--}}
{{--                    $warningTitle = __('ogp.comment_delete_title');--}}
{{--                    $warningMessage = __('ogp.comment_delete_warning');--}}
{{--                @endphp--}}
{{--                <x-modal.delete :modal_id="$modalId" :url="$deleteUrl" :row_id="$rowId" :title="$warningTitle" :warning_message="$warningMessage">--}}
{{--                    <a href="javascript:;" class="show-delete-modal" data-id="{{ $modalId }}" data-toggle="modal" data-target="#{{ $modalId }}">--}}
{{--                        <i class="fas fa-regular fa-trash-can float-end text-danger fs-4  ms-2" role="button" title="{{ __('custom.delete') }}"></i>--}}
{{--                    </a>--}}
{{--                </x-modal.delete>--}}
{{--            @endcan--}}
        </div>
        <div class="comment rounded py-2">
            <p class="mb-2">{!! $comment->content !!}</p>
        </div>
    </div>
</div>
