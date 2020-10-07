$(document).on('click', '.start_input_answer', function (e) {
    e.preventDefault();
    $(this).find('span').hide();
    $(this).find('input').attr('type', 'text').attr('style', 'text-align: center');
    $(this).find('input').focus();
})


$(document).on('click', '.start_select_answer', function (e) {
    e.preventDefault();
    $(this).find('span').hide();
    $(this).find('select').attr('style', 'display: inline !important; width: auto !important');
    $(this).find('select').show();
    $(this).find('select').focus();
})


$(document).on('click', '.start_select_option_answer', function (e) {
    e.preventDefault();
    $(this).find('span').hide();
    $(this).find('select').attr('style', 'display: inline !important; width: auto !important');
    $(this).find('select').show();
    $(this).find('select').focus();
})


$(document).ready(function () {

    $('.input_answer').keypress(function (e) {

        if (e.which == 13) {
            var answer = $(this).val();

            if (answer !== '') {
                $(this).hide();
                $(this).parent().find('span').html(answer + `<i class="picons-thin-icon-thin-0001_compose_write_pencil_new ml-2"></i>`);
                $(this).parent().find('span').removeClass('text-danger').addClass('text-white label label-default').attr('style', 'letter-spacing: 2px;');
                $(this).parent().find('span').show();
            } else {
                $(this).hide();
                $(this).parent().find('span').show();
            }
        }


    })

})


