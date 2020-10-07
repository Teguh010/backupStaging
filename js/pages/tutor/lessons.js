$(document).ready(function () {

    var editor = CKEDITOR.replace('description', {
        filebrowserUploadUrl: base_url + 'administrator/ck_upload_image/3',
        filebrowserUploadMethod: 'form',
        height: 100,
        toolbarCanCollapse: true
    });


    $('#list').click(function () {
        $(this).addClass('active');
        $('#grid').removeClass('active');
        setTimeout(function () {
            $('.panel_navigation').click();
        }, 300)
    })


    $('#grid').click(function () {
        $(this).addClass('active');
        $('#list').removeClass('active');
        setTimeout(function () {
            $('.panel_navigation').click();
        }, 300)
    })


    $('.btnNewLesson').click(function () {
        $('.panel_data').hide();
        $('.panel_navigation').slideUp(250);
        $('.section-form').show();
        $('.form-lesson').slideDown(500);
    })


    $('#CancelBtn').click(function () {
        $('.panel_data').show();
        $('.form-lesson').slideUp(250);
        $('.panel_navigation').slideDown(500);
        $('.section-form').hide();

        $('html, body').animate({
            scrollTop: $('body').offset().top
        }, 2000);
    })


})