var subject_type = '';
var subject_name = '';
var level_id = '';
var level_name = '';
var topic_id = '';
var topic_name = '';
var strategy_id = '';
var strategy_name = '';
var substrategy_id = '';
var substrategy_name = '';
var marks = '';
var has_subquestion = '';
var question_type = '';

$(document).ready(function () {

    get_questions(1);

    $('html, body').animate({
        scrollTop: $('body').offset().top
    }, 2000);


    $('#subject_id').selectize({
        valueField: 'subject_id',
        labelField: 'subject_name',
        searchField: 'subject_name',
        placeholder: '-- Subject & Level --'
    });

    getSubject();


    $('#topic_id').selectize({
        valueField: 'topic_id',
        labelField: 'topic_name',
        searchField: 'topic_name',
        placeholder: '-- Topic --'
    });


    $('#strategy_id').selectize({
        valueField: 'strategy_id',
        labelField: 'strategy_name',
        searchField: 'strategy_name',
        placeholder: '-- Strategy --'
    });


    $('#substrategy_id').selectize({
        valueField: 'substrategy_id',
        labelField: 'substrategy_name',
        searchField: 'substrategy_name',
        placeholder: '-- Sub Strategy --'
    });


    // $(document).on('click', '.btnSubject', function (e) {
    //     e.preventDefault();
    //     $('.btnSubject').removeClass('bg-light');
    //     $('.btnSubject').removeClass('shadow-1');
    //     $(this).addClass('bg-light');
    //     $(this).addClass('shadow-1');

    //     subject_name = $(this).data('name');
    // })


    // $(document).on('click', '.btnLevel', function (e) {
    //     e.preventDefault();
    //     $('.btnLevel').removeClass('active');
    //     $(this).addClass('active');
    //     subject_type = $(this).data('subject');
    //     level_id = $(this).data('level');

    //     getTopic($('#topic_id'), subject_type);

    //     if (subject_type == 2) {
    //         $('#substrategy_id').prop('disabled', false);
    //         getSubStrategy($('#substrategy_id'), subject_type);
    //     } else {
    //         var substrategy_select = $('#substrategy_id')[0].selectize;
    //         substrategy_select.clear();
    //         substrategy_select.clearOptions();
    //         $('#substrategy_id').prop('disabled', true);
    //     }
    // })


    $(document).on('change', '#subject_id', function (e) {
        e.preventDefault();
        subject_type = $(this).val();
        subject_name = subject_id_list.find(x => x.subject_id === $(this).val()).subject_name;

        if (subject_type == 2) {
            getTopic($('#topic_id'), subject_type);

            $('#strategy_id').prop('disabled', false);
            $('#substrategy_id').prop('disabled', false);
            getStrategyList($('#strategy_id'), subject_type);
            getSubStrategy($('#substrategy_id'), subject_type);
        } else {
            var strategy_select = $('#strategy_id')[0].selectize;
            strategy_select.clear();
            strategy_select.clearOptions();
            $('#strategy_id').prop('disabled', true);

            var substrategy_select = $('#substrategy_id')[0].selectize;
            substrategy_select.clear();
            substrategy_select.clearOptions();
            $('#substrategy_id').prop('disabled', true);
        }

    })


    $(document).on('change', '#topic_id', function (e) {
        e.preventDefault();
        topic_id = topic_id_list.find(x => x.topic_id === $(this).val()).topic_id;
        topic_name = topic_id_list.find(x => x.topic_id === $(this).val()).topic_name;
    })


    $(document).on('change', '#strategy_id', function (e) {
        e.preventDefault();
        strategy_id = strategy_id_list.find(x => x.strategy_id === $(this).val()).strategy_id;
        strategy_name = strategy_id_list.find(x => x.strategy_id === $(this).val()).strategy_name;
    })


    $(document).on('change', '#substrategy_id', function (e) {
        e.preventDefault();
        substrategy_id = substrategy_id_list.find(x => x.substrategy_id === $(this).val()).substrategy_id;
        substrategy_name = substrategy_id_list.find(x => x.substrategy_id === $(this).val()).substrategy_name;
    })


    $(document).on('click', '#filter', function () {
        if ($(this).is(':checked')) {
            $('.panel_data').slideUp(250);
            $('.panel_filter').slideDown(500);

            $('#btnConfirm').html('<i class="fa fa-search"></i>Confirm');

            $('#searchKeyword').val('');

            // $('.btnSubject').removeClass('bg-light');
            // $('.btnSubject').removeClass('shadow-1');

            // $('.primary_level').html("");
            // $('.secondary_level').html("");

            // $('.btnQuesType, .btnShowFITB').removeClass('active');
            // $('.btnQuesType, .btnShowFITB').removeClass('shadow-1');

            // $('#subquestion').prop('checked', false);
            // $('.ck_fitb').prop('checked', false);

            // $('.panel_fitb').hide();

        } else {
            $('.panel_filter').slideUp(250);
            $('.panel_data').slideDown(500);
        }
    })


    $(document).on('click', '#subquestion', function () {
        if ($(this).is(':checked')) {
            has_subquestion = 1;
        } else {
            has_subquestion = 0;
        }
    })


    $(document).on('click', '.btnQuesType', function () {
        $('.panel_fitb').hide();
        $('.btnQuesType, .btnShowFITB').removeClass('active');
        $('.btnQuesType, .btnShowFITB').removeClass('shadow-1');
        $(this).addClass('active');
        $(this).addClass('shadow-1');
        question_type = $(this).data('id');
    })


    $(document).on('click', '.btnShowFITB', function () {
        $(this).addClass();
        $('.panel_fitb').show();
        $('.btnQuesType').removeClass('active');
        $('.btnQuesType').removeClass('shadow-1');
        $(this).addClass('active');
        $(this).addClass('shadow-1');
    })


    $(document).on('click', '.ck_fitb', function () {
        question_type = $(this).val();
    })


    $(document).on('click', '#pag-addMore a', function (e) {
        e.preventDefault();
        //console.log($(this).data("ci-pagination-page"));          
        get_questions($(this).data("ci-pagination-page"));

    })


    $(document).on('click', '.btnExpand', function (e) {
        e.preventDefault();
        var id = $(this).data('id');
        var caret = $(this).find('i').attr('class');
        if (caret == 'fa fa-caret-down') {
            $(this).children().removeClass('fa fa-caret-down').addClass('fa fa-caret-up');
            var label_subject = $(this).data('label');
            var label_class = $(this).data('class');
            getInformation(id, label_subject, label_class);
        } else {
            $(this).children().removeClass('fa fa-caret-up').addClass('fa fa-caret-down');
            $('#card_question_title_' + id).html($(this).data('title'));
            $('#card_question_title_' + id).html(`
                <span class="` + $(this).data('class') + ` mr-2">` + $(this).data('label') + `</span>
                <span class="label label-default">` + $(this).data('substrand') + `</span>`
            );
        }

    })


    $(document).on('click', '#btnConfirm', function () {

        // $('.panel_navigation').LoadingOverlay("show", {
        //     background: "rgba(0, 0, 0, 0.2)"
        // });
        $(this).html('<i class="fa fa-spinner"></i>Loading...');
        get_questions(1);
        // setTimeout(function () {
        // $('#filter').click();
        // }, 1000)

        var label_filter = '';

        if ($('#subject_id').val() !== '') {
            if (subject_name.indexOf('Maths') >= 0) {
                label_filter += `<span class="label label-warning mr-2">` + subject_name + `</span>`;
            } else if (subject_name.indexOf('English') >= 0) {
                label_filter += `<span class="label label-primary mr-2">` + subject_name + `</span>`;
            } else if (subject_name.indexOf('Science') >= 0) {
                label_filter += `<span class="label label-danger mr-2">` + subject_name + `</span>`;
            }

        }

        if ($('#topic_id').val() !== '') {
            label_filter += `<span class="label label-default mr-2">` + topic_name + `</span>`;
        }

        if ($('#strategy_id').val() !== '') {
            label_filter += `<span class="label label-default mr-2">` + strategy_name + `</span>`;
        }

        if ($('#substrategy_id').val() !== '') {
            label_filter += `<span class="label label-default mr-2">` + substrategy_name + `</span>`;
        }

        $("input[name='marks[]']:checked").each(function () {
            label_filter += `<span class="label label-default mr-2">Marks ` + $(this).val() + `</span>`;
        });

        $('.label_filter').html(label_filter).show();

    })


    $('#searchKeyword').keypress(function (e) {
        var key = e.which;
        // the enter key code
        if (key == 13) {
            $(".panel_data").LoadingOverlay("show", {
                background: "rgba(0, 0, 0, 0.2)"
            });
            get_questions(1);
            if ($('#filter').is(':checked')) {

            } else {
                $('.label_filter').html('').hide();
            }
            return false;
        }
    })


    $(document).on('click', '.next_question', function (e) {
        e.preventDefault();
        var question_id = $(this).data('id');
        var total_question = $(this).parent().find('.total_question').val();
        var page_question = $(this).parent().find('.page_question').val();

        if (page_question < total_question) {
            var get_question_id = parseInt(question_id) + parseInt(page_question);
            page_question++;
            $(this).parent().find('.label_total_question').val(page_question + '/' + total_question);
            $(this).parent().find('.page_question').val(page_question);
            getQuestionDetail(question_id, get_question_id);
        }
    })


    $(document).on('click', '.prev_question', function (e) {
        e.preventDefault();
        var question_id = $(this).data('id');
        var total_question = $(this).parent().find('.total_question').val();
        var page_question = $(this).parent().find('.page_question').val();

        if (page_question > 1) {
            page_question--;
            var get_question_id = parseInt(question_id) + parseInt(page_question) - 1;
            $(this).parent().find('.label_total_question').val(page_question + '/' + total_question);
            $(this).parent().find('.page_question').val(page_question);
            getQuestionDetail(question_id, get_question_id);
        }
    })


})


var level_id_list = [];

function getLevel(subject_name) {
    $.ajax({
        type: 'GET',
        url: base_url + 'administrator/getlevelbysubject/' + subject_name,
        dataType: 'json',
        success: function (res) {
            topic_id_list = [];
            $('.primary_level, .secondary_level').html('');
            var content = ``;
            if (res.length > 0) {
                for (i = 0; i < res.length; i++) {
                    var str = res[i].level_name;
                    content = `
                        <button class="btn btn-square shadow-sm btn-outline-light-dark mr-2 mb-3 btnLevel" data-subject="`+ res[i].subject_type + `" data-level="` + res[i].level_id + `">
                            `+ res[i].level_name + `
                        </button>
                    `;

                    if (str.search('Primary') >= 0) {
                        $('.primary_level').append(content);
                    } else if (str.search('Secondary') >= 0) {
                        $('.secondary_level').append(content);
                    }

                }

            }
        }
    });
}


var subject_id_list = [];

function getSubject() {
    var subject_select = $('#subject_id')[0].selectize;
    $.ajax({
        url: base_url + 'administrator/getSubjectList',
        method: 'GET',
        dataType: 'json',
        success: function (data) {
            subject_id_list = [];
            for (x = 0; x < data.length; x++) {
                obj = {
                    subject_id: data[x].id,
                    subject_name: data[x].name,
                }
                subject_id_list.push(obj);
            }
            subject_select.clear();
            subject_select.clearOptions();
            subject_select.addOption(subject_id_list);
            subject_select.clear();
        }
    });
}


var subjectlevel_id_list = [];

function getSubjectLevelList() {
    var subjectlevel_select = $('#subjectlevel')[0].selectize;
    $.ajax({
        url: base_url + 'smartgen/getSubjectLevelList',
        method: 'GET',
        dataType: 'json',
        success: function (data) {
            subjectlevel_id_list = [];
            for (x = 0; x < data.length; x++) {
                subject = data[x].subject_name.replace(/primary|secondary/gi, '');
                obj = {
                    id: data[x].id,
                    level_id: data[x].level_id,
                    subject_id: data[x].subject_id,
                    level_name: data[x].level_name,
                    subject_level: data[x].level_name + subject
                }
                subjectlevel_id_list.push(obj);
            }
            subjectlevel_select.clear();
            subjectlevel_select.clearOptions();
            subjectlevel_select.addOption(subjectlevel_id_list);
            subjectlevel_select.clear();
        }
    });
}

var topic_id_list = [];

function getTopic(selectID, subject) {
    var topic_select = selectID[0].selectize;

    $.ajax({
        url: base_url + 'administrator/get_topic_list',
        method: 'POST',
        data: { subject: subject },
        dataType: 'json',
        success: function (response) {

            topic_id_list = [];

            $.each(response, function (index, data) {
                obj = {
                    'topic_id': data['id'],
                    'topic_name': data['name']
                };

                topic_id_list.push(obj);
            });

            topic_select.clear();
            topic_select.clearOptions();
            topic_select.addOption(topic_id_list);
            topic_select.enable();
            topic_select.clear();

        }
    });
}

var strategy_id_list = [];

function getStrategyList(selectID, subject_id) {
    var strategy_select = selectID[0].selectize;
    $.ajax({
        url: base_url + 'smartgen/getStrategyList',
        method: 'GET',
        data: {
            subject_id: subject_id
        },
        dataType: 'json',
        success: function (data) {
            strategy_id_list = [];
            for (i = 0; i < data.length; i++) {
                obj = {
                    'strategy_id': data[i].id,
                    'strategy_name': data[i].name
                };
                strategy_id_list.push(obj);
            }

            strategy_select.clear();
            strategy_select.clearOptions();
            strategy_select.addOption(strategy_id_list);
            strategy_select.enable();
            strategy_select.clear();
        }
    });
}

var substrategy_id_list = [];

function getSubStrategy(selectID, subject) {
    var substrategy_select = selectID[0].selectize;

    $.ajax({
        url: base_url + 'administrator/get_substrategy_list',
        method: 'POST',
        data: { subject: subject },
        dataType: 'json',
        success: function (response) {

            substrategy_id_list = [];

            $.each(response, function (index, data) {
                obj = {
                    'substrategy_id': data['id'],
                    'substrategy_name': data['name']
                };

                substrategy_id_list.push(obj);
            });

            substrategy_select.clear();
            substrategy_select.clearOptions();
            substrategy_select.addOption(substrategy_id_list);
            substrategy_select.enable();
            substrategy_select.clear();

        }
    });
}


function getInformation(id, label_subject, label_class) {
    $.ajax({
        url: base_url + 'ExamMode/getInformation/' + id,
        type: 'GET',
        dataType: 'json',
        success: function (res) {
            var difficulty = res.difficulty;
            var substrand_name = res.substrand_name;
            var topic_name = res.topic_name;
            var strategy_name = res.strategy_name;
            var content = '';
            if (difficulty == '1') {
                difficulty = 'Easy';
            } else if (difficulty == '2') {
                difficulty = 'Normal';
            } else if (difficulty == '3') {
                difficulty = 'Hard';
            } else if (difficulty == '4' || difficulty == '5') {
                difficulty = 'Genius';
            }

            if (strategy_name == '' || strategy_name == 'null' || strategy_name == null) {
                // content = `<p style="line-height: 1.3rem;">` + substrand_name + ` / ` + topic_name + `</p>`;
                content = `<p style="line-height: 1.75rem;">
                            <span class="`+ label_class + ` mr-1">` + label_subject + `</span>
                            <span class="label label-default mr-1">` + substrand_name + `</span>
                            <span class="label label-default mr-1">` + topic_name + `</span>
                        </p>`;
            } else {
                // content = `<p style="line-height: 1.3rem;">` + substrand_name + ` / ` + topic_name + ` / ` + strategy_name + `</p>`;
                content = `<p style="line-height: 1.75rem;">
                            <span class="`+ label_class + ` mr-1">` + label_subject + `</span>
                            <span class="label label-default mr-1">` + substrand_name + `</span>
                            <span class="label label-default mr-1">` + topic_name + `</span>
                            <span class="label label-default mr-1">` + strategy_name + `</span>
                        </p>`;
            }

            // $('#card_question_title_' + id).html(substrand_name);
            $('#card_question_title_' + id).html(content);
        }
    });
}


function get_questions(page) {

    if (page == 'undefined') {
        page = 1;
    }

    topic_id = $('#topic_id').val();
    strategy_id = $('#strategy_id').val();
    substrategy_id = $('#substrategy_id').val();
    var user_type = $('#user_grid').val();

    var checked = []
    $("input[name='marks[]']:checked").each(function () {
        checked.push($(this).val());
    })

    if ($('#filter').is(':checked')) {
        var filter = 1;
    } else {
        var filter = 0;
    }


    var search = $('#searchKeyword').val();

    $.ajax({
        url: base_url + 'administrator/getUser/' + user_type + '/' + page,
        type: 'GET',
        dataType: 'text',
        data: {
            filter: filter,
            search: search
        },
        success: function (res) {
            $(".panel_data").html(res);
            // $('.card_question_information').hide();

            MathJax.Hub.Typeset();

            $(".panel_data").LoadingOverlay("hide", true);

            $('.panel_filter').slideUp(250);
            $('.panel_data').slideDown(500);

            $('html, body').animate({
                scrollTop: $('body').offset().top
            }, 2000);


            if (search !== '') {
                // if (page > 1) {
                //     setTimeout(function () {
                //         history.replaceState('', '', base_url + 'administrator/questions/search=' + search.toLowerCase().replace(/\ /g, '+') + '&page=' + page);
                //     }, 500)
                // } else {
                //     setTimeout(function () {
                //         history.replaceState('', '', base_url + 'administrator/questions/search=' + search.toLowerCase().replace(/\ /g, '+'));
                //     }, 500)
                // }
            } else {
                // var url = '';
                // if (subject_name !== '' || subject_type !== '') {
                //     url += subject_name.toLowerCase(); + '';
                // } else if (level_id !== '') {
                //     url += subject_name.toLowerCase(); + '-' + level_id + '';
                // }

                // if (page > 1) {
                //     setTimeout(function () {
                //         history.replaceState('', '', base_url + 'administrator/questions/' + url.replace(/\ /g, '+') + '&page=' + page);
                //     }, 500)
                // } else {
                //     setTimeout(function () {
                //         history.replaceState('', '', base_url + 'administrator/questions/' + search.replace(/\ /g, '+'));
                //     }, 500)
                // }

            }

            // $(".panel_edit_generate_question").LoadingOverlay("hide", true);
            // $('.panel_edit_generate_question').fadeOut();
        }
    });
}


function getQuestionDetail(question_id, new_question_id) {
    $.ajax({
        type: 'GET',
        url: base_url + 'administrator/getQuestionDetail/' + new_question_id,
        dataType: 'json',
        success: function (res) {

            var data = res.data;

            if (data.length > 0) {

                for (i = 0; i < data.length; i++) {

                    var label_text = data[i].level_name;
                    var subject_name = data[i].subject_name.replace(/Primary|Secondary/gi, '');
                    var label_text = label_text + ' ' + subject_name;
                    var label_class = '';

                    if (subject_name == ' Maths') {
                        label_class = 'label label-warning';
                    } else if (subject_name == ' English') {
                        label_class = 'label label-primary';
                    } else if (subject_name == ' Science') {
                        label_class = 'label label-danger';
                    }

                    var questionTitle = ``;
                    questionTitle += `<h5 class="card-title card_question_title fs14" id="card_question_title_` + new_question_id + `">`;


                    questionTitle += `<span class="` + label_class + ` mr-2">` + label_text + `</span><span class="label label-default">` + data[i].substrand_name + `</span>`;
                    questionTitle += `</h5>`;
                    questionTitle += `<a class="btnExpand icon_expand" data-id="` + data[i].question_id + `" data-class="` + label_class + `" data-label="` + label_text + `" data-substrand="` + data[i].substrand_name + `">
                                            <i class="fa fa-caret-down"></i>
                                        </a>`;

                    var questionContent = ``;

                    // Answer List FITB
                    if (data[i].question_type_id == 5) {
                        var $listAnswers = '<div class="p-1 border2"><ul style="list-style-type:none; padding-left: 0;" class="list_four_column">';
                        var answerOption = res.answerList[i]['answerOption'];
                        for (j = 0; j < answerOption.length; j++) {
                            $listAnswers += '<li>' + answerOption[j].answer_text + '</li>';
                        }
                        $listAnswers += '</ul></div>';
                        questionContent += $listAnswers + '<br>';
                    }


                    // Question List                    

                    if (data[i].question_content == 0) {
                        if (data[i].question_type_id == 5 || data[i].question_type_id == 6) {

                            var _string = data[i].question_text;
                            var $question = _string.replace(/<ans>/g, '[___]<span>');
                            $question = $question.replace(/<\/ans>/g, '</span></span>');
                            var $array = [];
                            $array = $question.split('[___]');
                            $questions = "";

                            for ($x = 0; $x < $array.length; $x++) {

                                if ($x == ($array.length) - 1) {
                                    $questions += $array[$x];
                                } else {
                                    $questions += $array[$x] + '<span style="display: inline; border-bottom:1px solid;">(' + ($x + 1) + ') ';
                                }

                            }

                            questionContent += $questions;

                        } else {

                            questionContent += data[i].question_text;
                            if (data[i].graphical != 0) {
                                questionContent += '<img src="' + data[i].branch_image_url + '/questionImage/' + data[i].graphical + '" draggable="false" class="img-responsive" style="display: block; margin: 0 auto;">';
                            }

                        }


                    } else {

                        if (data[i].content_type == 'text') {
                            questionContent += data[i].question_text;
                        } else {
                            questionContent += '<img src="' + data[i].branch_image_url + '/questionImage/' + data[i].question_text + '" draggable="false" class="img-responsive" style="display: block; margin: 0 auto;">';
                        }

                        var content_detail = res.content_detail[i]['question_content'];

                        for (j = 0; j < content_detail.length; j++) {
                            if (content_detail[j].content_type == 'text') {
                                questionContent += content_detail[j].content_name;
                                questionContent += '<br>';
                            } else {
                                questionContent += '<img src="'.data[i].branch_image_url + '/questionImage/' + content_detail[j].content_name + '" draggable="false" class="img-responsive" style="display: block; margin: 0 auto;">';
                                questionContent += '<br>';
                            }
                        }



                    }


                    // Answer List
                    if (data[i].question_type_id == 1 || data[i].question_type_id == 4 || data[i].question_type_id == 8) {

                        var answerOption = res.answerList[i]['answerOption'];
                        questionContent += "<div class='row pt-10'>";
                        for (j = 0; j < answerOption.length; j++) {
                            var correctAnswer = res.answerList[i]['correctAnswer'];
                            var checked = "";
                            if (correctAnswer[0].answer_id == answerOption[j].answer_id) {
                                checked = "checked";
                            }

                            questionContent += `<div class="col-lg-12">
                                                    <div class="form-inline" >
                                                        <div class="customUi-radio radioUi-success mr-1">
                                                            <input type="checkbox" readonly ` + checked + `>
                                                            <label>
                                                                <span class="label-radio"></span>
                                                            </label>
                                                        </div>
                                                        <span class="fs14 font300">` + answerOption[j].answer_text + `</span>
                                                    </div>
                                                </div >`;

                        }
                        questionContent += "</div>";

                    } else if (data[i].question_type_id == 2) {

                        var answerOption = res.answerList[i]['answerOption'];
                        for (j = 0; j < answerOption.length; j++) {
                            var correctAnswer = res.answerList[i]['correctAnswer'];

                            if (correctAnswer[0].answer_id == answerOption[j].answer_id) {
                                questionContent += '<br><span class="correctAnswer fs14">Ans: ' + answerOption[j].answer_text + '</span>';

                            }
                        }

                    } else if (data[i].question_type_id == 3) {

                        var answerOption = res.answerList[i]['answerOption'];
                        questionContent += "<div class='row pt-10'>";
                        for (j = 0; j < answerOption.length; j++) {
                            questionContent += `<div class="col-lg-12">
                                                    <div class="form-inline">
                                                        <div class="customUi-radio radioUi-success mr-1">
                                                            <input type="radio" readonly `+ ((answerOption[j].answer_text == 1) ? 'checked' : '') + `>
                                                            <label>
                                                                <span class="label-radio"></span>												
                                                            </label>
                                                        </div>
                                                        <span class="fs14 font300 mr-4">True</span>

                                                        <div class="customUi-radio radioUi-danger mr-1">
                                                            <input type="radio" readonly `+ ((answerOption[j].answer_text == 0) ? 'checked' : '') + `>
                                                            <label>
                                                                <span class="label-radio"></span>												
                                                            </label>
                                                        </div>
                                                        <span class="fs14 font300">False</span>
                                                    </div>
                                                </div>`;

                        }
                        questionContent += "</div>";

                    }

                    $('.card_question_' + question_id + ' .marks').html(data[i].difficulty + ' Marks');
                }

                $('.card_question_' + question_id + ' .card-header-small').html(questionTitle);
                $('.card_question_' + question_id + ' .card_question_body').html(questionContent);

                MathJax.Hub.Typeset();

            }

        }
    });
}


// var pathname = window.location.pathname; // Returns path only (/path/example.html)
// var url      = window.location.href;     // Returns full URL (https://example.com/path/example.html)
// var origin   = window.location.origin;   // Returns base URL (https://example.com)