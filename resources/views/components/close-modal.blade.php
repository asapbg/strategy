@php
    /**
    *   In order this component to work, you need to place your delete form next to the element that is opening the
    *   modal.
    *
    *   Ex:
    *       <form class="d-none" method="POST" action="{{ route('legislative_initiatives.comments.delete', $comment)
    *       }}" name="DELETE_COMMENT_{{ $key }}">
    *
    *       </form>
    *
    *       <a href="#" class="open-delete_modal">
    *           <i class="fas fa-regular fa-trash-can float-end text-danger fs-4 ms-2" role="button" title="{{
    *           __('custom.delete') }}"></i>
    *       </a>
    */

    $cancel_btn_text = $cancel_btn_text ?? '';
    $continue_btn_text = $continue_btn_text ?? '';
    $title_text = $title_text ?? '';
    $file_change_warning_txt = $file_change_warning_txt ?? '';
@endphp

@push('scripts')
    <script type="text/javascript">
        $(document).ready(function () {
            let cancelBtnTxt = @json($cancel_btn_text);
            let continueTxt = @json($continue_btn_text);
            let titleTxt =  @json($title_text);
            let fileChangeWarningTxt = @json($file_change_warning_txt);

            $('.open-close-modal').on('click', function () {
                const form = $(this).parent().find('form').attr('name');

                new MyModal({
                    title: titleTxt,
                    footer: '<button class="btn btn-sm btn-success ms-3" onclick="' + form + '.submit()">' + continueTxt + '</button>' +
                        '<button class="btn btn-sm btn-danger closeModal ms-3" data-dismiss="modal" aria-label="' + cancelBtnTxt + '">' + cancelBtnTxt + '</button>',
                    body: '<div class="alert alert-danger">' + fileChangeWarningTxt + '</div>',
                });
            });
        });
    </script>
@endpush
