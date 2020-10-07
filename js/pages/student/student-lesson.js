var search = false;

$(document).ready(function () {

    $('.play-video').magnificPopup({
        type: 'iframe',
        midClick: true
    })


    $(document).on('click', '.icon_expand_plus', function (e) {
        e.preventDefault();
        var id = $(this).data('id');
        var caret = $(this).find('i').attr('class');
        if (caret == 'picons-thin-icon-thin-0151_plus_add_new') {
            $(this).children().removeClass('picons-thin-icon-thin-0151_plus_add_new').addClass('picons-thin-icon-thin-0152_minus_delete_remove');
            $('.lesson_body_' + id).slideDown();
        } else {
            $(this).children().removeClass('picons-thin-icon-thin-0152_minus_delete_remove').addClass('picons-thin-icon-thin-0151_plus_add_new');
            $('.lesson_body_' + id).slideUp();
        }

    })


    $('#searchKeyword').keypress(function (e) {
        var key = e.which;
        // the enter key code
        if (key == 13) {
            $(".data_lesson").LoadingOverlay("show", {
                background: "rgba(0, 0, 0, 0.2)"
            });

            ajax_more_lesson(1);
            return false;
        }
    })


    $(document).on('click', '#pag-addMore a', function (e) {
        e.preventDefault();
        ajax_more_lesson($(this).data("ci-pagination-page"));
    })


})


function ajax_more_lesson(page) {

    if (page == 'undefined') {
        page = 1;
    }

    $.ajax({
        url: base_url + 'lesson/searchLessonByStudent/' + page,
        type: 'GET',
        dataType: 'text',
        data: {
            searchKeyword: $('#searchKeyword').val()
        },
        success: function (res) {
            $(".data_lesson").LoadingOverlay("hide", true);

            $(".data_lesson").html(res);
        }
    });
}


function clickViewModue(lecture_id, module_type) {

    $.ajax({
        type: 'POST',
        url: base_url + 'lesson/clickViewModue',
        data: {
            lecture_id: lecture_id,
            module_type: module_type
        },
        dataType: 'json',
        success: function (res) {
            console.log(res.msg);
        }
    });

}