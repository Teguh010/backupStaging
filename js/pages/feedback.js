/* feedback logic start */

$('#smartjen_feedback_btn').on('click', function (e) {
    $('#submit_feedback_error').hide();
    $('#submit_feedback_success').hide();
});

$('#submit_feedback_btn').on('click', function (e) {
    e.preventDefault();
    $('#submit_feedback_error').hide('fast');
    $('#submit_feedback_success').hide('fast');

    var $this = $(this);
    $this.button('loading');
    var ajax_url = base_url + 'site/submit_feedback';

    $.ajax({
        url: ajax_url,
        method: "post",
        dataType: 'json',
        data: {
            "feedback_sender_name": $('#feedback_sender_name').val(),
            "feedback_sender_email": $('#feedback_sender_email').val(),
            "feedback_type": $('#feedback_type').val(),
            "feedback_comment": $('#feedback_comment').val(),
        },
        success: function (data) {
            if (data['success'] == true) {
                $('#submit_feedback_success').html(data['message']).show('fast').delay(3000).hide('slow');
                $('#feedback_sender_name').val('');
                $('#feedback_sender_email').val('');
                $('#feedback_comment').val('');
            } else {
                $('#submit_feedback_error').html(data['message']).show('fast').delay(5000).hide('slow');
            }
            $this.button('reset');
        }

    });
});

/* feedback logic end */