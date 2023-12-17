$(function() {

    $('.unlimited-time').click(function() {
        $(this).parent().parent().find('input[type="text"]').prop('disabled', $(this).is(':checked'));
    });

    $('select[name="commitment_id"]').change(function() {
        $('input[name="commitment_name"]').prop('disabled', $(this).val() > 0);
    });

    $('.ogp-offer-comments').submit(function(e) {
        e.preventDefault();
        ShowLoadingSpinner();

        let commentField = $(this).find('textarea');

        $.post($(this).attr('action'), {
            '_token': $(this).find('input[name="_token"]').val(),
            'content': commentField.val()
        }, function(response) {
            HideLoadingSpinner();
            if(response.error) {
                alert(response.message);
            } else {
                $('#offer-comments-'+response.offer_id).append(response.html);
            }
            commentField.html('');
            $('textarea[name="comment"]').summernote('reset')
        });
    });

    $(document).on('click', '.ajax-modal-delete', function() {
        ShowLoadingSpinner();

        $.post($(this).data('url'), {
            '_token': $('meta[name="csrf-token"]').attr('content'),
            'row_id': $(this).data('row-id')
        }, function(response) {
            HideLoadingSpinner();
            if(response.error) {
                alert(response.message)
            } else {
                $('#'+response.row_id).remove();
            }
        });
    });

});
