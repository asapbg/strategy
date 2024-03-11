$(function() {

    $('.unlimited-time').click(function() {
        $(this).parent().parent().find('input[type="text"]').prop('disabled', $(this).is(':checked'));
    });

    $('select[name="commitment_id"]').change(function() {
        $('input[name="commitment_name"]').prop('disabled', $(this).val() > 0);
    });


    $('.submit-comment').on('click', function() {
        ShowLoadingSpinner();
        let lForm = $(this).closest('form')[0];
        let commentField = $(lForm).find('textarea');
        $($(lForm).find('.comment-error')[0]).html('');

        if(!commentField.val().length){
            $($(lForm).find('.comment-error')[0]).html('Не сте въвели коментар');
        }

        $.post($(lForm).attr('action'), {
            '_token': $(lForm).find('input[name="_token"]').val(),
            'content': commentField.val()
        }, function(response) {
            HideLoadingSpinner();
            if(response.error) {
                $($(lForm).find('.comment-error')[0]).html(response.message);
            } else {
                window.location.reload();
            }
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

    $(document).on('click', '.ogp-vote-ajax', function(e) {
        e.preventDefault();
        ShowLoadingSpinner();

        $.post($(this).attr('href'), {
            '_token': $('meta[name="csrf-token"]').attr('content'),
            'container': $(this).data('container')
        }, function(response) {
            HideLoadingSpinner();
            if(response.error) {
                alert(response.message)
            } else {
                $('#'+response.container).html(response.html);
            }
        });

    });

});
