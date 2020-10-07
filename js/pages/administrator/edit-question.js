var subject_type;
var subject_name;
var level_id;
var question_type;
var form_section = 1;
var form_question = 0; // 0 => main question, 1..n => subquestion


$(document).ready(function () {

    $('#school_id').selectize();

    $('#level_id2').selectize({
        valueField: 'level_id',
        labelField: 'level_name',
        searchField: 'level_name',
        placeholder: 'Please select Level'
    });

    $('#level_id3').selectize({
        valueField: 'level_id',
        labelField: 'level_name',
        searchField: 'level_name',
        placeholder: 'Please select Level'
    });

    $('#topic_id_0').selectize({
        valueField: 'topic_id',
        labelField: 'topic_name',
        searchField: 'topic_name',
        placeholder: 'No Topic',
        maxItems: 4,
        plugins: ['remove_button'],
    });

    $('#substrategy_id_0').selectize({
        valueField: 'substrategy_id',
        labelField: 'substrategy_name',
        searchField: 'substrategy_name',
        placeholder: 'No Substrategy',
        maxItems: 4,
        plugins: ['remove_button'],
    });

    $('#key_topic_id_0').selectize({
        valueField: 'topic_id',
        labelField: 'topic_name',
        searchField: 'topic_name',
        placeholder: 'No Topic'
    });

    $('#key_substrategy_id_0').selectize({
        valueField: 'substrategy_id',
        labelField: 'substrategy_name',
        searchField: 'substrategy_name',
        placeholder: 'No Substrategy'
    });

    // $('.addInstructionContenButton').hide();
    // $('.addArticleContenButton').hide();

    // edit start - Shuq
    $('.form-question-0').show();

    // call getlevel() to display level inside question editor
    var level_id = $('#level_id').val();
    getLevel('English', level_id);

    // get topic value -> start
    
    var no = $('#question-page').val();
    var subject_type = $('#subject_id').val();
    getTopicList($('#topic_id_' + no), subject_type, 0);

    if (subject_type == 2) {
        $('#substrategy_id_' + no).prop('disabled', false);
        getSubStrategy($('#substrategy_id_' + no), subject_type, 0);
    } else {
        var substrategy_select = $('#substrategy_id_' + no)[0].selectize;
        substrategy_select.clear();
        substrategy_select.clearOptions();
        $('#substrategy_id_' + no).prop('disabled', true);
    }

    // get topic value -> end

    // label listed subject
    // $('.btnSubject').addClass('bg-light shadow-1');

    $('.btnNext').hide();
    $('.btnEditQuestion').show();
    $('.panel_nav_main_question').hide();
    $('.panel_nav_sub_question').hide();


    // edit end - Shuq 

    $(document).on('click', '.btnPrevious', function (e) {
        e.preventDefault();
        form_section--;
        if (form_section == 1) {
            $('.row_level, .row_school, .row_subject').show();
            $(this).hide();
            $('.btnSaveQuestion, .form-question').hide();
            $('.btnNext').show();

            $('.title-header').html('1. Select Subject');

            $('.panel_nav_sub_question').hide();
            $('.panel_nav_main_question').show();
        }
    })

    $(document).on('click', '.btnNext', function (e) {
        e.preventDefault();
        form_section++;
        if (form_section == 2) {
            // $('.row_subject').hide();
            // $('.row_level').hide();
            // $('.row_school').hide();
            $('.btnPrevious').show();

            $('.title-header').html('2. Main Question');
            // $('.form-question').hide();
            $('.form-question-0').show();
            $(this).hide();
            $('.btnSaveQuestion').show();
            $('.panel_nav_main_question').hide();
            $('.panel_nav_sub_question').show();
        } else if (form_section > 2) {
            $('.title-header').html('Sub Question ' + $('#question-page').val());
        }

    })


    $(document).on('click', '.btnSubject', function (e) {
        e.preventDefault();
        $('.btnSubject').removeClass('bg-light');
        $('.btnSubject').removeClass('shadow-1');
        $(this).addClass('bg-light');
        $(this).addClass('shadow-1');

    })


    $(document).on('click', '.btnLevel', function (e) {
        e.preventDefault();
        var no = $('#question-page').val();
        $('.btnLevel').removeClass('active');
        $(this).addClass('active');
        subject_type = $(this).data('subject');
        level_id = $(this).data('level');

        $('#subject_id').val(subject_type);
        $('#level_id').val(level_id);

        getTopic($('#topic_id_' + no), subject_type, 0);

        if (subject_type == 2) {
            $('#substrategy_id_' + no).prop('disabled', false);
            getSubStrategy($('#substrategy_id_' + no), subject_type, 0);
        } else {
            var substrategy_select = $('#substrategy_id_' + no)[0].selectize;
            substrategy_select.clear();
            substrategy_select.clearOptions();
            $('#substrategy_id_' + no).prop('disabled', true);
        }

        $('.row_school').show();
        $('.btnNext').show();

        // edit start - Shuq
        $('.form-question-0').show();

        $('.btnNext').hide();
        $('.btnSaveQuestion').show();
        $('.panel_nav_main_question').hide();
        $('.panel_nav_sub_question').show();

        // edit end - Shuq 
    })


    $(document).on('click', '#add_difficulty_level2nd', function () {

        if ($(this).is(':checked')) {
            $('.panel_difficulty_level2nd').show();
        } else {
            $('.panel_difficulty_level2nd').hide();
        }
    })


    $(document).on('click', '#add_difficulty_level3rd', function () {

        if ($(this).is(':checked')) {
            $('.panel_difficulty_level3rd').show();
        } else {
            $('.panel_difficulty_level3rd').hide();
        }
    })


    $(document).on('click', '.key_topic', function () {
        var no = $('#question-page').val();

        if ($(this).is(':checked')) {
            getTopic($('#key_topic_id_' + no), subject_type, 1);
        } else {
            var key_topic_select = $('#key_topic_id_' + no)[0].selectize;
            key_topic_select.clear();
            key_topic_select.clearOptions();
        }
    })


    $(document).on('click', '.key_strategy', function () {
        var no = $('#question-page').val();
        if ($(this).is(':checked')) {
            getSubStrategy($('#key_substrategy_id_' + no), subject_type, 1);
        } else {
            var key_substrategy_select = $('#key_substrategy_id_' + no)[0].selectize;
            key_substrategy_select.clear();
            key_substrategy_select.clearOptions();
        }
    })


    $(document).on('click', '.btnQuesType', function (e) {
        e.preventDefault();

        var no = $('#question-page').val();

        $('.form-question-' + no + ' .btnShowFITB').removeClass('active');
        $('.form-question-' + no + ' .btnShowFITB').removeClass('shadow-1');

        $('.form-question-' + no + ' .btnQuesType').removeClass('active');
        $('.form-question-' + no + ' .btnQuesType').removeClass('shadow-1');

        $(this).addClass('active');
        $(this).addClass('shadow-1');

        $('.form-question-' + no + ' .panel_answer_content').hide();

        $('.form-question-' + no + ' .panel_fitb').hide();

        $('html, body').animate({
            scrollTop: $('.form-question-' + no + ' .panel_question').offset().top
        }, 2000);

    })


    $(document).on('click', '.btnShowFITB', function (e) {
        e.preventDefault();

        var no = $('#question-page').val();
        $('.form-question-' + no + ' .panel_fitb').show();

        $('.form-question-' + no + ' .btnQuesType').removeClass('active');
        $('.form-question-' + no + ' .btnQuesType').removeClass('shadow-1');
        $(this).addClass('active');
        $(this).addClass('shadow-1');

    })


    $(document).on('click', '.btnAnswerType', function (e) {
        e.preventDefault();

        var no = $('#question-page').val();
        $('.form-question-' + no + ' .btnAnswerType').removeClass('active');
        $(this).addClass('active');

        $('.form-question-' + no + ' .panel_question_content, .panel_answer_content').show();

        var quesType = $('#question_type_id_' + no).val();

        var ansTypeID = $(this).data('id');
        $('.form-question-' + no + ' .input_answer_type_id').val(ansTypeID);

        if (quesType == 1 || quesType == 4) {
            $('.form-question-' + no + ' .correct_answer_multiple').hide();
            $('.form-question-' + no + ' .correct_answer_single').show();
        } else if (quesType == 2) {
            $('.form-question-' + no + ' .mcq_input_answers_div').hide();
            $('.form-question-' + no + ' .fill_blank_answers_div').hide();
            $('.form-question-' + no + ' .panel_answer_type_text_image').hide();
            $('.form-question-' + no + ' .true_false_input_answers_div').hide();
            $('.form-question-' + no + ' .open_ended_input_answers_div').show();
        } else if (quesType == 8) {
            $('.form-question-' + no + ' .correct_answer_single').hide();
            $('.form-question-' + no + ' .correct_answer_multiple').show();
        } else if (quesType == 5) {
            $('.form-question-' + no + ' .btnAddDistractorFITB').show();
        } else if (quesType == 6 || quesType == 7) {
            $('.form-question-' + no + ' .btnAddDistractorFITB').hide();
        }

        $('html, body').animate({
            scrollTop: $('.form-question-' + no + ' .panel_question').offset().top
        }, 2000);

    })


    $(document).on('click', '.btnAnswerTypeTextImage', function (e) {
        e.preventDefault();
        var no = $('#question-page').val();
        $('.form-question-' + no + ' .btnAnswerTypeTextImage').removeClass('active');
        $(this).addClass('active');

        var ansTypeTextImage = $(this).data('type');
        $('.form-question-' + no + ' .answer_type_mcq').val(ansTypeTextImage);

        // var ansType = $('.form-question-' + no + ' .input_answer_type').val();
        var ansType = $('.form-question-' + no + ' .input_answer_type_id').val();
        console.log('ansType: ' + ansType);
        if (ansTypeTextImage == "text") {
            if (ansType == 1 || ansType == 2) {
                $('.form-question-' + no + ' .mcq_input_answers_div .input_answer_text').show();
                $('.form-question-' + no + ' .mcq_input_answers_div .input_answer_image').hide();
            } else if (ansType == 4) {
                $('.form-question-' + no + ' .open_ended_input_answers_div .input_answer_text').show();
                $('.form-question-' + no + ' .open_ended_input_answers_div .input_answer_image').hide();
            }
        } else {
            if (ansType == 1 || ansType == 2) {
                $('.form-question-' + no + ' .mcq_input_answers_div .input_answer_text').hide();
                $('.form-question-' + no + ' .mcq_input_answers_div .input_answer_image').show();
            } else if (ansType == 4) {
                $('.form-question-' + no + ' .open_ended_input_answers_div .input_answer_text').hide();
                $('.form-question-' + no + ' .open_ended_input_answers_div .input_answer_image').show();
            }
        }

    })


    $(document).on('click', '.fa_topic_strategy', function (e) {
        e.preventDefault();
        var no = $('#question-page').val();

        var caret = $(this).find('i').attr('class');
        if (caret == 'fa fa-angle-double-down') {
            $(this).children().removeClass('fa fa-angle-double-down').addClass('fa fa-angle-double-up');
            $('.form-question-' + no + ' .panel_topic_strategy').fadeOut();
        } else {
            $(this).children().removeClass('fa fa-angle-double-up').addClass('fa fa-angle-double-down');
            $('.form-question-' + no + ' .panel_topic_strategy').fadeIn();
        }

    })


    $(document).on('click', '.fa_question_type', function (e) {
        e.preventDefault();
        var no = $('#question-page').val();

        var caret = $(this).find('i').attr('class');
        if (caret == 'fa fa-angle-double-down') {
            $(this).children().removeClass('fa fa-angle-double-down').addClass('fa fa-angle-double-up');
            $('.form-question-' + no + ' .panel_question_type').fadeOut();
        } else {
            $(this).children().removeClass('fa fa-angle-double-up').addClass('fa fa-angle-double-down');
            $('.form-question-' + no + ' .panel_question_type').fadeIn();
        }

    })


    $(document).on('click', '.fa_question_content', function (e) {
        e.preventDefault();
        var no = $('#question-page').val();

        var caret = $(this).find('i').attr('class');
        if (caret == 'fa fa-angle-double-down') {
            $(this).children().removeClass('fa fa-angle-double-down').addClass('fa fa-angle-double-up');
            $('.form-question-' + no + ' .paneldiv_question_content').fadeOut();
        } else {
            $(this).children().removeClass('fa fa-angle-double-up').addClass('fa fa-angle-double-down');
            $('.form-question-' + no + ' .paneldiv_question_content').fadeIn();
        }

    })


    $(document).on('click', '.fa_answer_content', function (e) {
        e.preventDefault();
        var no = $('#question-page').val();

        var caret = $(this).find('i').attr('class');
        if (caret == 'fa fa-angle-double-down') {
            $(this).children().removeClass('fa fa-angle-double-down').addClass('fa fa-angle-double-up');
            $('.form-question-' + no + ' .answer_content').fadeOut();
        } else {
            $(this).children().removeClass('fa fa-angle-double-up').addClass('fa fa-angle-double-down');
            $('.form-question-' + no + ' .answer_content').fadeIn();
        }

    })


    $(document).on('click', '.fa_marks', function (e) {
        e.preventDefault();
        var no = $('#question-page').val();

        var caret = $(this).find('i').attr('class');
        if (caret == 'fa fa-angle-double-down') {
            $(this).children().removeClass('fa fa-angle-double-down').addClass('fa fa-angle-double-up');
            $('.form-question-' + no + ' .panel_marks').fadeOut();
        } else {
            $(this).children().removeClass('fa fa-angle-double-up').addClass('fa fa-angle-double-down');
            $('.form-question-' + no + ' .panel_marks').fadeIn();
        }

    })


    $(document).on('click', '.fa_working_content', function (e) {
        e.preventDefault();
        var no = $('#question-page').val();

        var caret = $(this).find('i').attr('class');
        if (caret == 'fa fa-angle-double-down') {
            $(this).children().removeClass('fa fa-angle-double-down').addClass('fa fa-angle-double-up');
            $('.form-question-' + no + ' .panel_working_content').fadeOut();
        } else {
            $(this).children().removeClass('fa fa-angle-double-up').addClass('fa fa-angle-double-down');
            $('.form-question-' + no + ' .panel_working_content').fadeIn();
        }

    })


    $(document).on('click', '.fa_tag_label', function (e) {
        e.preventDefault();
        var no = $('#question-page').val();

        var caret = $(this).find('i').attr('class');
        if (caret == 'fa fa-angle-double-down') {
            $(this).children().removeClass('fa fa-angle-double-down').addClass('fa fa-angle-double-up');
            $('.form-question-' + no + ' .panel_tag_label').fadeOut();
        } else {
            $(this).children().removeClass('fa fa-angle-double-up').addClass('fa fa-angle-double-down');
            $('.form-question-' + no + ' .panel_tag_label').fadeIn();
        }

    })


    $(document).on('click', '#question_instruction', function () {

        if ($(this).is(':checked')) {
            $('.addInstructionContenButton').show();
            $('.panel_instruction_content').show();
        } else {
            $('.addInstructionContenButton').hide();
            $('.panel_instruction_content').hide();
        }

    })


    $(document).on('click', '#question_article', function () {

        if ($(this).is(':checked')) {
            $('.addArticleContenButton').show();
            $('.panel_article_content').show();
        } else {
            $('.addArticleContenButton').hide();
            $('.panel_article_content').hide();
        }

    })


    $(document).on('click', '.removeInstructionContent', function (e) {
        e.preventDefault();

        var instruction_content_id = $('.instruction_content_id').val();
        var instruction_counter = $('.instruction_counter').val();

        instruction_content_id--;
        instruction_counter--;

        $('.instruction_content_id').val(instruction_content_id);
        $('.instruction_counter').val(instruction_counter);

        if (instruction_counter < 6) {
            $('.addInstructionContenButton').show();
        }

        $(this).parent().parent().parent().remove();
    })


    $(document).on('click', '.removeArticleContent', function (e) {
        e.preventDefault();

        var article_content_id = $('.article_content_id').val();
        var article_counter = $('.article_counter').val();

        article_content_id--;
        article_counter--;

        $('.article_content_id').val(article_content_id);
        $('.article_counter').val(article_counter);

        if (article_counter < 6) {
            $('.addArticleContenButton').show();
        }

        $(this).parent().parent().remove();
    })


    $(document).on('click', '.removeQuestionContent', function (e) {
        e.preventDefault();

        var no = $('#question-page').val();

        var question_content_id = $('.form-question-' + no + ' .question_content_id').val();
        var content_counter = $('.form-question-' + no + ' .content_counter').val();

        question_content_id--;
        content_counter--;

        $('.form-question-' + no + ' .question_content_id').val(question_content_id);
        $('.form-question-' + no + ' .content_counter').val(content_counter);

        // Shuq -  addQuestionContentButton if content less than 6
        if (content_counter < 6) {
            $('.form-question-' + no + ' .addQuestionContenButton').show();
        }

        $(this).parent().parent().remove();
    })


    $(document).on('click', '.removeWorkingContent', function (e) {
        e.preventDefault();

        var no = $('#question-page').val();

        var working_content_id = $('.form-question-' + no + ' .working_content_id').val();
        var working_content_counter = $('.form-question-' + no + ' .working_content_counter').val();

        working_content_id--;
        working_content_counter--;

        $('.form-question-' + no + ' .working_content_id').val(working_content_id);
        $('.form-question-' + no + ' .working_content_counter').val(working_content_counter);

        // Shuq -  addWorkingContentButton if content less than 6
        if (working_content_counter < 6) {
            $('.form-question-' + no + ' .addWorkingContenButton').show();
        }

        $(this).parent().parent().parent().remove();
    })


    $(document).on('click', '.btnAddDistractorFITB', function (e) {
        e.preventDefault();
        var no = $('#question-page').val();
        var contentOption = `
                <div class="col-lg-3 mt-2">
                    <div class="form-inline">
                        <div class="form-group" style="width: 100%;text-align: center;">
                            <input type="text" class="form-control input_style1_black" name="input_answer_fb_distractor_`+ no + `[]" placeholder="Distractor" style="width: 100%;">                                
                        </div>
                    </div>
                </div>
        `;
        $('.form-question-' + no + ' .panel_answer_fb').append(contentOption);
    })


    var $elem;

    // $(document).on('focus', '.input_question_content_', function () {
    //     $elem = $(this);
    // })

    $('.math_text').each(function (index) {
        let span_id = $(this).attr('id');
        var mathFieldSpan = document.getElementById(span_id);
        var MQ = MathQuill.getInterface(2);
        var mathField = MQ.MathField(mathFieldSpan, {
            handlers: {
                edit: function () {
                    mathField.focus();
                }
            },
            autoOperatorNames: 'somelongrandomoperatortooverride'
        });
    })


    $(document).on('click', '.math_text', function () {
        $('.panel_math_quill').fadeIn('fast');
        $('#mathTarget').val($(this).attr('id'));
    }).mouseleave(function () {

    }).focusout(function () {
        $('.section').click(function () {
            $('.panel_math_quill').hide();
        })
    })


    $(document).on('keypress', '.bootstrap-tagsinput input', function (e) {
        if (e.keyCode == 13) {
            e.keyCode = 188;
            e.preventDefault();
        };
    });


    $(document).on('click', '.btnAddSubquestion', function () {
        form_question++;
        var content = `
            <div class="row form-question form-question-`+ form_question + `">
                <input type="hidden" id="question_type_id_`+ form_question + `" name="question_type_id_` + form_question + `" />
                <input type="hidden" class="input_answer_type_id" id="answer_type_id_` + form_question + `" name="answer_type_id_` + form_question + `" />        
                <input type="hidden" id="data_question_content_`+ form_question + `" name="data_question_content_` + form_question + `" />
                <input type="hidden" id="data_working_content_`+ form_question + `" name="data_working_content_` + form_question + `" />

                <div class="col-lg-12">        
                    <h5 class="label-topic-strategy pb-2">[1] Topic and Strategy <a class="fa_topic_strategy cursor_pointer"><i class="fa fa-angle-double-down"></i></a></h5>
                </div>
                <div class="panel_topic_strategy pb-20">

                    <div class="col-lg-6">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label for="topic_id_`+ form_question + `" class="fs14 font300">Topic</label>                    
                                    <select name="topic_id_`+ form_question + `[]" class="topic_id" id="topic_id_` + form_question + `">                        
                                    </select>                    
                                </div>
                            </div>            
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group row">                    
                                    <div class="col-sm-6 col-md-6 col-lg-4">
                                        <div class="customUi-checkbox checkboxUi-primary">
                                            <input id="key_topic_`+ form_question + `" type="checkbox" class="key_topic" name="key_topic_` + form_question + `" value="1">
                                            <label for="key_topic_`+ form_question + `">
                                                <span class="label-checkbox"></span>
                                                <span class="label-helper text-primary fs14 font400">Key Topic</span>
                                            </label>
                                        </div>
                                    </div>

                                    <div class="col-sm-6 col-md-6 col-lg-8">
                                        <select name="key_topic_id_`+ form_question + `" class="key_topic_id" id="key_topic_id_` + form_question + `">
                                        </select>
                                    </div>

                                </div>
                            </div>            
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label for="substrategy_id_`+ form_question + `" class="fs14 control-label font300">Sub Strategy</label>                    
                                    <select name="substrategy_id_`+ form_question + `[]" class="substrategy_id" id="substrategy_id_` + form_question + `">
                                    </select>
                                </div>                        
                            </div>            
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group row">                    
                                    <div class="col-sm-6 col-md-6 col-lg-4">
                                        <div class="customUi-checkbox checkboxUi-danger">
                                            <input id="key_strategy_`+ form_question + `" type="checkbox" class="key_strategy" name="key_strategy_` + form_question + `" value="1">
                                            <label for="key_strategy_`+ form_question + `">
                                                <span class="label-checkbox"></span>
                                                <span class="label-helper text-danger fs14 font400">Key Strategy</span>
                                            </label>
                                        </div>
                                    </div>

                                    <div class="col-sm-6 col-md-6 col-lg-8">
                                        <select name="key_substrategy_id_`+ form_question + `" class="key_substrategy_id" id="key_substrategy_id_` + form_question + `">                        
                                        </select>
                                    </div>

                                </div>
                            </div>            
                        </div>
                    </div>

                </div>    

                <div class="col-lg-12">
                    <hr>
                </div>

                <div class="col-lg-12">
                    <h5 class="label-question-type pb-2">[2] Question Type <a class="fa_question_type cursor_pointer"><i class="fa fa-angle-double-down"></i></a></h5>        
                </div>        
                <div class="col-lg-12 pb-20 panel_question_type">
                    <div class="row">
                        <input type="hidden" class="input_question_type" name="input_question_type">

                        <div class="col-lg-12">
                            <button class="btn btn-icon btn-xl btn-outline-teal mb-2 btnQuesType" onclick="getAnswerType('1')">
                                <i class="picons-thin-icon-thin-0209_radiobuttons_bullets_lines"></i>
                                MCQ - Single Choice
                            </button>
                            <button class="btn btn-icon btn-xl btn-outline-teal mb-2 btnQuesType" onclick="getAnswerType('8')">
                                <i class="picons-thin-icon-thin-0210_checkboxes_lines_check"></i>
                                MCQ - Multiple Choice
                            </button>                    
                            <button class="btn btn-icon btn-xl btn-outline-teal mb-2 btnQuesType" onclick="getAnswerType('3')">
                                <i class="picons-thin-icon-thin-0161_on_off_switch_toggle_settings_preferences"></i>
                                True & False
                            </button>
                            <button class="btn btn-icon btn-xl btn-outline-teal mb-2 btnQuesType" onclick="getAnswerType('2')">
                                <i class="picons-thin-icon-thin-0069a_menu_hambuger"></i>
                                Open
                            </button>
                            <button class="btn btn-icon btn-xl btn-outline-teal mb-2 btnQuesType btnShowFITB" onclick="getAnswerType('0', '0')">
                                <i class="picons-thin-icon-thin-0101_notes_text_notebook"></i>
                                Fill in The Blank
                            </button>

                        </div>

                        <div class="col-lg-12 panel_fitb pt-10 pb-20" style="display: none;">
                            <div class="row text-center">
                                <div class="col-sm-6 col-md-6 col-lg-2">
                                    <div class="customUi-checkbox checkboxUi-pink">
                                        <input id="ck_with_option_`+ form_question + `" type="radio" class="ck_fitb" name="ck_fitb_` + form_question + `" value="5" onclick="getAnswerType('5', '5')">
                                        <label for="ck_with_option_`+ form_question + `">
                                            <span class="label-checkbox"></span>
                                            <span class="label-helper text-pink fs14 font400">With Option</span>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-md-6 col-lg-2">
                                    <div class="customUi-checkbox checkboxUi-pink">
                                        <input id="ck_without_option_`+ form_question + `" type="radio" class="ck_fitb" name="ck_fitb_` + form_question + `" value="6" onclick="getAnswerType('6', '6')">
                                        <label for="ck_without_option_`+ form_question + `">
                                            <span class="label-checkbox"></span>
                                            <span class="label-helper text-pink fs14 font400">Without Option</span>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-md-6 col-lg-3">
                                    <div class="customUi-checkbox checkboxUi-pink">
                                        <input id="ck_withunique_option_` + form_question + `" type="radio" class="ck_fitb" name="ck_fitb_` + form_question + `" value="7" onclick="getAnswerType('7')">
                                        <label for="ck_withunique_option_` + form_question + `">
                                            <span class="label-checkbox"></span>
                                            <span class="label-helper text-pink fs14 font400">With Unique Option</span>
                                        </label>
                                    </div>
                                </div>
                            
                            </div>
                        </div>


                    </div>

                    <div class="panel-answer-type" style="display: none;">
                        <input type="hidden" class="input_answer_type" name="input_answer_type">
                        <input type="hidden" class="input_answer_type_id" name="input_answer_type_id">
                        <input type="hidden" class="answer_type_mcq" name="answer_type_mcq_`+ form_question + `" value="text">
                        <h5 class="label-answer-type">[3] Answer Type <a class="fa_answer_type cursor_pointer"><i class="fa fa-angle-double-down"></i></a></h5>
                        <div class="row">
                            <div class="col-lg-12 panel_answer_type">
                            </div>                            
                        </div>
                    </div>

                </div>

                <div class="col-lg-12">
                    <hr>
                </div>

                <div class="col-lg-12 panel_question">

                    <div class="panel_question_content pb-30" style="display: none;">

                        <h5 class="label-question-content pb-2">[4] Question Content <a class="fa_question_content cursor_pointer"><i class="fa fa-angle-double-down"></i></a></h5>                                
                        <div class="paneldiv_question_content">
                            <div class="row question_content"></div>
                            <input type="hidden" class="question_content_id" value="0">
                            <input type="hidden" class="content_counter" value="0">
                            <div class="dropdown addQuestionContenButton">
                                <button class="btn btn-icon-o radius100 btn-outline-primary dropdown-toggle" type="button"
                                    data-toggle="dropdown"><span class="fa fa-plus"></span></button>
                                <ul class="dropdown-menu">
                                    <li class="dropdown-header">Please choose text or image</li>
                                    <li>
                                        <a style="cursor: pointer;" onClick="addQuestionContent('text')"><i
                                                class="picons-thin-icon-thin-0262_remove_clear_text_style mr-2"></i> Text</a>
                                    </li>
                                    <li>
                                        <a style="cursor: pointer;" onClick="addQuestionContent('mathtext')"><i
                                                class="picons-thin-icon-thin-0261_unlink_url_unchain_hyperlink mr-2"></i> Math Text</a>
                                    </li>
                                    <li>
                                        <a style="cursor: pointer;" onClick="addQuestionContent('image')"><i
                                                class="picons-thin-icon-thin-0617_picture_image_photo mr-2"></i> Image</a>
                                    </li>
                                </ul>
                            </div>                
                        </div>
                    </div>

                    <div class="panel_answer_content" style="display: none;">
                        <h5 class="label-answer-content pb-2">[5] Answer <a class="fa_answer_content cursor_pointer"><i class="fa fa-angle-double-down"></i></a></h5>
                        <div class="answer_content pb-20">

                            <div class="row panel_answer_type_text_image pb-10" style="display: none;">
                                <div class="col-lg-12">
                                    <button class="btn btn-sm btn-rounded btn-outline-pink mr-1 fs14 active btnAnswerTypeTextImage"
                                        style="width: 80px;" data-type="text">Text</button>
                                    <button class="btn btn-sm btn-rounded btn-outline-pink mr-1 fs14 btnAnswerTypeTextImage"
                                        style="width: 80px;" data-type="image">Image</button>
                                </div>                    
                            </div>

                            <div class="mcq_input_answers_div">
            
                                <div class="form-group row">
                                    <label for="question_answers" class="col-sm-1 col-md-1 col-lg-1 control-label font400">1</label>
                                    <div class="col-sm-6 col-md-6 col-lg-6 input_answer_text">
                                        <span id="mcq_ans_1_`+ form_question + `" style="width: 100%; padding: 0.5em" class="math_text"></span>
                                        <input type="hidden" name="mcq_answers_`+ form_question + `[]">
                                    </div>
                                    <div class="col-sm-6 col-md-6 col-lg-6 input_answer_image" style="display: none;">
                                        <input type="file" class="form-control" class="mcq_answers_image" name="mcq_answers_image_`+ form_question + `[]"
                                            style="padding-top: 5px;" />
                                    </div>
                                    <div class="col-sm-5 col-md-5 col-lg-5">

                                        <div class="customUi-checkbox checkboxUi-success correct_answer_single">
                                            <input id="mcq_correct_answer1_`+ form_question + `" class="input_correct_answer_single" type="radio" name="mcq_correct_answer_` + form_question + `" value="1" checked>
                                            <label for="mcq_correct_answer1_`+ form_question + `">
                                                <span class="label-checkbox"></span>
                                                <span class="label-helper fs14 font300">Correct answer</span>
                                            </label>
                                        </div>

                                        <div class="customUi-checkbox checkboxUi-success correct_answer_multiple">
                                            <input id="mcq_correct_answer_checkbox1_`+ form_question + `" class="input_correct_answer_multiple" type="checkbox" name="mcq_correct_answer_multiple_` + form_question + `[]" value="1" checked>
                                            <label for="mcq_correct_answer_checkbox1_`+ form_question + `">
                                                <span class="label-checkbox"></span>
                                                <span class="label-helper fs14 font300">Correct answer</span>
                                            </label>
                                        </div>        

                                    </div>
                                </div>
            
                                <div class="form-group row">
                                    <label for="question_answers" class="col-sm-1 col-md-1 col-lg-1 control-label font400">2</label>
                                    <div class="col-sm-6 col-md-6 col-lg-6 input_answer_text">
                                        <span id="mcq_ans_2_`+ form_question + `" style="width: 100%; padding: 0.5em" class="math_text"></span>
                                        <input type="hidden" name="mcq_answers_`+ form_question + `[]">
                                    </div>
                                    <div class="col-sm-6 col-md-6 col-lg-6 input_answer_image" style="display: none;">
                                        <input type="file" class="form-control" class="mcq_answers_image" name="mcq_answers_image_`+ form_question + `[]"
                                            style="padding-top: 5px;" />
                                    </div>
                                    <div class="col-sm-5 col-md-5 col-lg-5">
                                        <div class="customUi-checkbox checkboxUi-success correct_answer_single">
                                            <input id="mcq_correct_answer2_`+ form_question + `" class="input_correct_answer_single" type="radio" name="mcq_correct_answer_` + form_question + `" value="2">
                                            <label for="mcq_correct_answer2_`+ form_question + `">
                                                <span class="label-checkbox"></span>
                                                <span class="label-helper fs14 font300">Correct answer</span>
                                            </label>
                                        </div>

                                        <div class="customUi-checkbox checkboxUi-success correct_answer_multiple">
                                            <input id="mcq_correct_answer_checkbox2_`+ form_question + `" class="input_correct_answer_multiple" type="checkbox" name="mcq_correct_answer_multiple_` + form_question + `[]" value="2">
                                            <label for="mcq_correct_answer_checkbox2_`+ form_question + `">
                                                <span class="label-checkbox"></span>
                                                <span class="label-helper fs14 font300">Correct answer</span>
                                            </label>
                                        </div>                            
                                    </div>
                                </div>
            
                                <div class="form-group row">
                                    <label for="question_answers" class="col-sm-1 col-md-1 col-lg-1 control-label font400">3</label>
                                    <div class="col-sm-6 col-md-6 col-lg-6 input_answer_text">
                                        <span id="mcq_ans_3_`+ form_question + `" style="width: 100%; padding: 0.5em" class="math_text"></span>
                                        <input type="hidden" name="mcq_answers_`+ form_question + `[]">
                                    </div>
                                    <div class="col-sm-6 col-md-6 col-lg-6 input_answer_image" style="display: none;">
                                        <input type="file" class="form-control" class="mcq_answers_image" name="mcq_answers_image_`+ form_question + `[]"
                                            style="padding-top: 5px;" />
                                    </div>
                                    <div class="col-sm-5 col-md-5 col-lg-5">
                                        <div class="customUi-checkbox checkboxUi-success correct_answer_single">
                                            <input id="mcq_correct_answer3_`+ form_question + `" class="input_correct_answer_single" type="radio" name="mcq_correct_answer_` + form_question + `" value="3">
                                            <label for="mcq_correct_answer3_`+ form_question + `">
                                                <span class="label-checkbox"></span>
                                                <span class="label-helper fs14 font300">Correct answer</span>
                                            </label>
                                        </div>

                                        <div class="customUi-checkbox checkboxUi-success correct_answer_multiple">
                                            <input id="mcq_correct_answer_checkbox3_`+ form_question + `" class="input_correct_answer_multiple" type="checkbox" name="mcq_correct_answer_multiple_` + form_question + `[]" value="3">
                                            <label for="mcq_correct_answer_checkbox3_`+ form_question + `">
                                                <span class="label-checkbox"></span>
                                                <span class="label-helper fs14 font300">Correct answer</span>
                                            </label>
                                        </div>                            
                                    </div>
                                </div>
            
                                <div class="form-group row">
                                    <label for="question_answers" class="col-sm-1 col-md-1 col-lg-1 control-label font400">4</label>
                                    <div class="col-sm-6 col-md-6 col-lg-6 input_answer_text">
                                        <span id="mcq_ans_4_`+ form_question + `" style="width: 100%; padding: 0.5em" class="math_text"></span>
                                        <input type="hidden" name="mcq_answers_`+ form_question + `[]">
                                    </div>
                                    <div class="col-sm-6 col-md-6 col-lg-6 input_answer_image" style="display: none;">
                                        <input type="file" class="form-control" class="mcq_answers_image" name="mcq_answers_image_`+ form_question + `[]"
                                            style="padding-top: 5px;" />
                                    </div>
                                    <div class="col-sm-5 col-md-5 col-lg-5">
                                        <div class="customUi-checkbox checkboxUi-success correct_answer_single">
                                            <input id="mcq_correct_answer4_`+ form_question + `" class="input_correct_answer_single" type="radio" name="mcq_correct_answer_` + form_question + `" value="4">
                                            <label for="mcq_correct_answer4_`+ form_question + `">
                                                <span class="label-checkbox"></span>
                                                <span class="label-helper fs14 font300">Correct answer</span>
                                            </label>
                                        </div>

                                        <div class="customUi-checkbox checkboxUi-success correct_answer_multiple">
                                            <input id="mcq_correct_answer_checkbox4_`+ form_question + `" class="input_correct_answer_multiple" type="checkbox" name="mcq_correct_answer_multiple_` + form_question + `[]" value="4">
                                            <label for="mcq_correct_answer_checkbox4_`+ form_question + `">
                                                <span class="label-checkbox"></span>
                                                <span class="label-helper fs14 font300">Correct answer</span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
            
                            </div>

                            <div class="row true_false_input_answers_div">                
                                <div class="col-sm-6 col-md-6 col-lg-1">
                                    <div class="customUi-checkbox checkboxUi-success">
                                        <input id="true_false_answer_0_`+ form_question + `" type="radio" name="true_false_answer_` + form_question + `" value="1" checked>
                                        <label for="true_false_answer_0_`+ form_question + `">
                                            <span class="label-checkbox"></span>
                                            <span class="label-helper fs14 font300">True</span>
                                        </label>
                                    </div>                            
                                </div>

                                <div class="col-sm-6 col-md-6 col-lg-1">
                                    <div class="customUi-checkbox checkboxUi-danger">
                                        <input id="true_false_answer_1_`+ form_question + `" type="radio" name="true_false_answer_` + form_question + `" value="0">
                                        <label for="true_false_answer_1_`+ form_question + `">
                                            <span class="label-checkbox"></span>
                                            <span class="label-helper fs14 font300">False</span>
                                        </label>
                                    </div>                            
                                </div>
                            </div>
            
                            <div class="open_ended_input_answers_div">
            
                                <div class="form-group row">                        
                                    <div class="col-sm-6 col-md-6 col-lg-6 input_answer_text">
                                        <span id="open_ended_answer_`+ form_question + `" style="width: 100%; padding: 0.5em" class="math_text"></span>
                                        <input type="hidden" name="open_ended_answer_`+ form_question + `" class="form-control">
                                    </div>
                                    <div class="col-sm-6 col-md-6 col-lg-6 input_answer_image" style="display: none;">
                                        <input type="file" class="form-control" class="nmcq_answers_image" name="nmcq_answers_image_`+ form_question + `[]"
                                            style="padding-top: 5px;" />
                                    </div>
                                </div>
                            </div>
            
                            <div class="fill_blank_answers_div">
                                <input type="hidden" class="count_answer_fb" name="count_answer_fb_`+ form_question + `" value="1">
                                <textarea style="display: none;" class="reset_question_fb"></textarea>
                                <div class="row p-1 nav-button">                                    
                                    <div class="col-lg-2">
                                        <a class="btn btn-sm btn-icon btn-danger btn-block cursor_pointer btnResetAnswerFb"><i class="picons-thin-icon-thin-0134_arrow_rotate_left_counter_clockwise"></i>Reset</a>
                                    </div>
                                    <div class="col-lg-2">                                
                                        <a class="btn btn-sm btn-icon btn-default btn-block cursor_pointer btnAddDistractorFITB"><i class="picons-thin-icon-thin-0151_plus_add_new"></i>Add Distractors</a>
                                    </div>
                                </div>

                                <div class="row panel_answer_fb" style="display: none;">
                                    
                                </div>                        
                                
                            </div>
                        </div>

                        <h5 class="label-working-content">[6] Marks <a class="fa_marks cursor_pointer"><i class="fa fa-angle-double-down"></i></a></h5>
                        <div class="panel_marks pb-20">
                            <div class="row">
                                <div class="col-sm-6 col-md-6 col-lg-1">
                                    <div class="customUi-checkbox checkboxUi-warning">
                                        <input id="marks_1_`+ form_question + `" type="radio" name="marks_` + form_question + `" value="1">
                                        <label for="marks_1_`+ form_question + `">
                                            <span class="label-checkbox"></span>
                                            <span class="label-helper fs14 font300">1</span>
                                        </label>
                                    </div>
                                </div>

                                <div class="col-sm-6 col-md-6 col-lg-1">
                                    <div class="customUi-checkbox checkboxUi-success">
                                        <input id="marks_2_`+ form_question + `" type="radio" name="marks_` + form_question + `" value="2" checked>
                                        <label for="marks_2_`+ form_question + `">
                                            <span class="label-checkbox"></span>
                                            <span class="label-helper fs14 font300">2</span>
                                        </label>
                                    </div>                            
                                </div>

                                <div class="col-sm-6 col-md-6 col-lg-1">
                                    <div class="customUi-checkbox checkboxUi-danger">
                                        <input id="marks_3_`+ form_question + `" type="radio" name="marks_` + form_question + `" value="3">
                                        <label for="marks_3_`+ form_question + `">
                                            <span class="label-checkbox"></span>
                                            <span class="label-helper fs14 font300">3</span>
                                        </label>
                                    </div>                            
                                </div>

                                <div class="col-sm-6 col-md-6 col-lg-1">
                                    <div class="customUi-checkbox checkboxUi-teal">
                                        <input id="marks_4_`+ form_question + `" type="radio" name="marks_` + form_question + `" value="4">
                                        <label for="marks_4_`+ form_question + `">
                                            <span class="label-checkbox"></span>
                                            <span class="label-helper fs14 font300">4</span>
                                        </label>
                                    </div>                            
                                </div>

                                <div class="col-sm-6 col-md-6 col-lg-1">
                                    <div class="customUi-checkbox checkboxUi-primary">
                                        <input id="marks_5_`+ form_question + `" type="radio" name="marks_` + form_question + `" value="5">
                                        <label for="marks_5_`+ form_question + `">
                                            <span class="label-checkbox"></span>
                                            <span class="label-helper fs14 font300">5</span>
                                        </label>
                                    </div>                            
                                </div>

                            </div>
                        </div>


                        <h5 class="label-working-content pb-2">[7] Workings <a class="fa_working_content cursor_pointer"><i class="fa fa-angle-double-down"></i></a></h5>
                        <div class="panel_working_content pb-20">
                            <div class="row working_content"></div>
                            <input type="hidden" class="working_content_id" value="0">                
                            <input type="hidden" class="working_content_counter" value="0">
                            <div class="dropdown addWorkingContenButton">
                                <button class="btn btn-icon-o radius100 btn-outline-primary dropdown-toggle" type="button"
                                    data-toggle="dropdown"><span class="fa fa-plus"></span></button>
                                <ul class="dropdown-menu">
                                    <li class="dropdown-header">Please choose text or image</li>
                                    <li>
                                        <a style="cursor: pointer;" onClick="addWorkingContent('text')"><i
                                                class="picons-thin-icon-thin-0262_remove_clear_text_style mr-2"></i> Text</a>
                                    </li>
                                    <li>
                                        <a style="cursor: pointer;" onClick="addWorkingContent('mathtext')"><i
                                                class="picons-thin-icon-thin-0261_unlink_url_unchain_hyperlink mr-2"></i> Math Text</a>
                                    </li>
                                    <li>
                                        <a style="cursor: pointer;" onClick="addWorkingContent('image')"><i
                                                class="picons-thin-icon-thin-0617_picture_image_photo mr-2"></i> Image</a>
                                    </li>
                                </ul>
                            </div>            
                        </div>


                        <h5 class="label-tags">[8] Type/Tags/Label (Max 5)</h5>
                        <div class="row">
                            <div class="col-sm-6 col-md-6 col-lg-12">
                                <input name="tagsinput_`+ form_question + `" class="tagsinput" data-role="tagsinput" placeholder="eg: CA1, Mock Exam 2" value="">                                
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        `;

        $('.form-question').hide();

        var no = $('#question-page').val();
        $(content).insertAfter('.form-question-' + no);
        $("input[data-role=tagsinput]").tagsinput();
        // $('.page-content').append(content);
        $('#question-page').val(form_question);
        $('.btnRemoveSubquestion').show();

        $('#topic_id_' + form_question).selectize({
            valueField: 'topic_id',
            labelField: 'topic_name',
            searchField: 'topic_name',
            placeholder: 'No Topic',
            maxItems: 4,
            plugins: ['remove_button'],
        });

        $('#substrategy_id_' + form_question).selectize({
            valueField: 'substrategy_id',
            labelField: 'substrategy_name',
            searchField: 'substrategy_name',
            placeholder: 'No Substrategy',
            maxItems: 4,
            plugins: ['remove_button'],
        });

        $('#key_topic_id_' + form_question).selectize({
            valueField: 'topic_id',
            labelField: 'topic_name',
            searchField: 'topic_name',
            placeholder: 'No Topic'
        });

        $('#key_substrategy_id_' + form_question).selectize({
            valueField: 'substrategy_id',
            labelField: 'substrategy_name',
            searchField: 'substrategy_name',
            placeholder: 'No Substrategy'
        });


        getTopic($('#topic_id_' + form_question), subject_type, 0);

        getSubStrategy($('#substrategy_id_' + form_question), subject_type, 0);

        $('.math_text').each(function (index) {
            let span_id = $(this).attr('id');
            var mathFieldSpan = document.getElementById(span_id);
            var MQ = MathQuill.getInterface(2);
            var mathField = MQ.MathField(mathFieldSpan, {
                handlers: {
                    edit: function () {
                        mathField.focus();
                    }
                },
                autoOperatorNames: 'somelongrandomoperatortooverride'
            });
        })


        $(document).on('click', '.math_text', function () {
            $('.panel_math_quill').fadeIn('fast');
            $('#mathTarget').val($(this).attr('id'));
        }).mouseleave(function () {

        }).focusout(function () {
            $('.section').click(function () {
                $('.panel_math_quill').hide();
            })
        })

        $('.title-header').html('Sub Question ' + $('#question-page').val());
    })


    $(document).on('click', '.btnRemoveSubquestion', function () {
        var no = $('#question-page').val();
        $('.form-question-' + no).remove();
        form_question--;
        $('.btnPrevSubquestion').click();

        if (form_question == 0) {
            $('.btnRemoveSubquestion').hide();
        }

    })


    $(document).on('click', '.btnPrevSubquestion', function () {
        var no = parseInt($('#question-page').val());
        var noPrev = no;
        if (noPrev != 0) {
            no--;
            $('.form-question').hide();
            $('.form-question-' + no).show();
            $('#question-page').val(no);
            $('.btnRemoveSubquestion').hide();

            if (no == form_question) {
                $('.btnRemoveSubquestion').show();
            }
        }

        if (no == 0) {
            $('.title-header').html('2. Main Question');
        } else {
            $('.title-header').html('Sub Question ' + $('#question-page').val());
        }

    })


    $(document).on('click', '.btnNextSubquestion', function () {
        var no = parseInt($('#question-page').val());
        var noNext = no;
        noNext++;
        if (noNext <= form_question) {
            no++;
            $('.form-question').hide();
            $('.form-question-' + no).show();
            $('#question-page').val(no);

            if (no == form_question) {
                $('.btnRemoveSubquestion').show();
            }

            $('.title-header').html('Sub Question ' + $('#question-page').val());
        }



    })

    $('#form-create').submit(function () {
        return false;
    })

    $(document).on('click', '.btnEditQuestion', function () {

        let totalSubQuestion = parseInt($('#question-page').val());
        iic = [];
        iac = [];
        iqc = [];
        iwc = [];

        $('.input_instruction_content').each(function () {
            var type = $(this).data('type');
            iic.push(type);
        })

        $('#data_instruction_content').val(iic);

        $('.input_article_content').each(function () {
            var type = $(this).data('type');
            iac.push(type);
        })

        $('#data_article_content').val(iac);

        $('.input_question_content_0').each(function () {
            var type = $(this).data('type');
            iqc.push(type);
        })

        $('#data_question_content_0').val(iqc);


        if (totalSubQuestion > 0) {

            for (i = 1; i <= totalSubQuestion; i++) {

                iqc = [];
                $('.input_question_content_' + i).each(function () {
                    var type = $(this).data('type');
                    iqc.push(type);
                })
                $('#data_question_content_' + i).val(iqc);

            }

        }


        $('.input_working_content_0').each(function () {
            var type = $(this).data('type');
            iwc.push(type);
        })

        $('#data_working_content_0').val(iwc);


        if (totalSubQuestion > 0) {

            for (i = 1; i <= totalSubQuestion; i++) {

                iwc = [];
                $('.input_working_content_' + i).each(function () {
                    var type = $(this).data('type');
                    iwc.push(type);
                })
                $('#data_working_content_' + i).val(iwc);

            }

        }

        console.log(iwc);

        $('.math_text').each(function () {
            let mathSpanId = $(this).attr('id');
            let spanTarget = document.getElementById(mathSpanId);
            let mathSpan = MQ(spanTarget);
            let latex = mathSpan.latex();
            latex = latex.replace('\\left', '');  // quick fix to prevent bracket becoming left and right
            latex = latex.replace('\\right', '');
            $(this).next().val(latex);
        });

        // var e = document.getElementById("school_id");
        // var strUser = e.options[e.selectedIndex].text;
        // if (strUser == "Not applicable") {
        //     toastr.error('Please select school name!');
        //     return false;
        // }

        // var e = document.getElementById("topic_id");
        // var strUser = e.options[e.selectedIndex].text;
        // if (strUser == "-") {
        //     toastr.error('Please select topic!');
        //     return false;
        // }


        edit_question();
    })


})

var level_id_list = [];

function getLevel(subject_name, level_id) {
    window.subject_name = subject_name;
    var level_select = $('#level_id2')[0].selectize;
    var level_select3 = $('#level_id3')[0].selectize;

    $.ajax({
        type: 'GET',
        url: base_url + 'administrator/getlevelbysubject/' + subject_name,
        dataType: 'json',
        success: function (res) {
            level_id_list = [];
            $('.primary_level, .secondary_level').html('');
            var content = ``;
            if (res.length > 0) {
                for (i = 0; i < res.length; i++) {
                    var str = res[i].level_name;
                    var level = res[i].level_id;

                    if(level == level_id){
                        content = `
                        <button class="btn btn-square shadow-sm btn-outline-light-dark mr-2 mb-3 btnLevel btnLevel_`+ res[i].level_id + ` active" data-subject="`+ res[i].subject_type + `" data-level="` + res[i].level_id + `">
                            `+ res[i].level_name + `
                        </button>
                    `;
                    } else {
                        content = `
                        <button class="btn btn-square shadow-sm btn-outline-light-dark mr-2 mb-3 btnLevel btnLevel_`+ res[i].level_id + `" data-subject="`+ res[i].subject_type + `" data-level="` + res[i].level_id + `">
                            `+ res[i].level_name + `
                        </button>
                    `;
                    }
                   

                    if (str.search('Primary') >= 0) {
                        $('.primary_level').append(content);
                    } else if (str.search('Secondary') >= 0) {
                        $('.secondary_level').append(content);
                    }

                    obj = {
                        'level_id': res[i].level_id,
                        'level_name': res[i].level_name
                    };

                    level_id_list.push(obj);

                }
                $('.row_level').show();

                level_select.clear();
                level_select.clearOptions();
                level_select.addOption(level_id_list);
                level_select.enable();
                level_select.clear();


                level_select3.clear();
                level_select3.clearOptions();
                level_select3.addOption(level_id_list);
                level_select3.enable();
                level_select3.clear();

            } else {
                $('.row_level').hide();
                $('.row_school').hide();
            }
        }
    });
}


var topic_id_list = [];

function getTopic(selectID, subject, isKey) {
    var topic_select = selectID[0].selectize;

    $.ajax({
        url: base_url + 'administrator/get_topic_list',
        method: 'POST',
        data: { subject: subject },
        dataType: 'json',
        success: function (response) {

            topic_id_list = [];

            if (isKey == 1) {

                var no = $('#question-page').val();
                var arrTopic = $('#topic_id_' + no).val();

                $.each(response, function (index, data) {

                    if (jQuery.inArray(data['id'], arrTopic) !== -1) {
                        obj = {
                            'topic_id': data['id'],
                            'topic_name': data['name']
                        };

                        topic_id_list.push(obj);
                    }

                });


            } else {

                $.each(response, function (index, data) {
                    obj = {
                        'topic_id': data['id'],
                        'topic_name': data['name']
                    };

                    topic_id_list.push(obj);
                });

            }

            topic_select.clear();
            topic_select.clearOptions();
            topic_select.addOption(topic_id_list);
            topic_select.enable();
            // topic_select.setValue(topic_id_list[0].topic_id);
            topic_select.clear();

        }
    });
}

function getTopicList(selectID, subject, isKey) {
    var topic_select = selectID[0].selectize;

    $.ajax({
        url: base_url + 'administrator/get_topic_list',
        method: 'POST',
        data: { subject: subject },
        dataType: 'json',
        success: function (response) {

            topic_id_list = [];

            if (isKey == 1) {

                var no = $('#question-page').val();
                var arrTopic = $('#topic_id_' + no).val();

                $.each(response, function (index, data) {

                    if (jQuery.inArray(data['id'], arrTopic) !== -1) {
                        obj = {
                            'topic_id': data['id'],
                            'topic_name': data['name']
                        };

                        topic_id_list.push(obj);
                    }

                });


            } else {

                $.each(response, function (index, data) {
                    obj = {
                        'topic_id': data['id'],
                        'topic_name': data['name']
                    };

                    topic_id_list.push(obj);
                });

            }

            // topic_select.clear();
            topic_select.clearOptions();
            topic_select.addOption(topic_id_list);
            topic_select.enable();
            // topic_select.setValue(topic_id_list[0].topic_id);
            // topic_select.clear();

        }
    });
}

var substrategy_id_list = [];

function getSubStrategy(selectID, subject, isKey) {
    var substrategy_select = selectID[0].selectize;

    $.ajax({
        url: base_url + 'administrator/get_substrategy_list',
        method: 'POST',
        data: { subject: subject },
        dataType: 'json',
        success: function (response) {

            substrategy_id_list = [];

            if (isKey == 1) {

                var no = $('#question-page').val();
                var arrSubstratgy = $('#substrategy_id_' + no).val();

                $.each(response, function (index, data) {

                    if (jQuery.inArray(data['id'], arrSubstratgy) !== -1) {
                        obj = {
                            'substrategy_id': data['id'],
                            'substrategy_name': data['name']
                        };

                        substrategy_id_list.push(obj);
                    }

                });

            } else {

                $.each(response, function (index, data) {
                    obj = {
                        'substrategy_id': data['id'],
                        'substrategy_name': data['name']
                    };

                    substrategy_id_list.push(obj);
                });

            }

            substrategy_select.clear();
            substrategy_select.clearOptions();
            substrategy_select.addOption(substrategy_id_list);
            substrategy_select.enable();
            // substrategy_select.setValue(substrategy_id_list[0].substrategy_id);
            substrategy_select.clear();

        }
    });
}


function getAnswerType(question_type_id) {

    var no = $('#question-page').val();

    if (question_type_id == 0) {
        $('.form-question-' + no + ' .panel-answer-type').hide();
    }

    $('#question_type_id_' + no).val(question_type_id);

    $.ajax({
        type: 'GET',
        url: base_url + 'administrator/getAnswerType/' + question_type_id,
        dataType: 'json',
        success: function (res) {

            var content = ``;
            for (i = 0; i < res.length; i++) {
                content += `<button class="btn btn-square btn-outline-info font400 mr-1 mb-2 btnAnswerType" data-type="` + question_type_id + `" data-id="` + res[i].answer_type_id + `">` + res[i].answer_type + `</button>`;
            }

            $('.form-question-' + no + ' .input_question_type').val(question_type_id);


            if (question_type_id == 1 || question_type_id == 8 || question_type_id == 4) {
                $('.form-question-' + no + ' .panel-answer-type').show();
                $('.form-question-' + no + ' .mcq_input_answers_div').show();
                if (question_type_id == 1 || question_type_id == 4) {
                    $('.form-question-' + no + ' .panel_answer_type').html(`<button class="btn btn-square btn-outline-info font400 mr-1 mb-2 btnAnswerType active" data-type="` + question_type_id + `" data-id="1">Single Correct Answer</button>`);
                    $('.form-question-' + no + ' .correct_answer_multiple').hide();
                    $('.form-question-' + no + ' .correct_answer_single').show();
                } else if (question_type_id == 8) {
                    $('.form-question-' + no + ' .panel_answer_type').html(`<button class="btn btn-square btn-outline-info font400 mr-1 mb-2 btnAnswerType" data-type="` + question_type_id + `" data-id="1">Multiple Correct Answer</button>`);
                    $('.form-question-' + no + ' .correct_answer_single').hide();
                    $('.form-question-' + no + ' .correct_answer_multiple').show();
                }

                $('.form-question-' + no + ' .open_ended_input_answers_div').hide();
                $('.form-question-' + no + ' .fill_blank_answers_div').hide();
                $('.form-question-' + no + ' .true_false_input_answers_div').hide();
                $('.form-question-' + no + ' .panel_answer_type_text_image').show();
            } else if (question_type_id == 2) {
                $('.form-question-' + no + ' .panel-answer-type').show();
                $('.form-question-' + no + ' .panel_answer_type').html(content);
                $('.form-question-' + no + ' .mcq_input_answers_div').hide();
                $('.form-question-' + no + ' .fill_blank_answers_div').hide();
                $('.form-question-' + no + ' .panel_answer_type_text_image').hide();
                $('.form-question-' + no + ' .true_false_input_answers_div').hide();
                $('.form-question-' + no + ' .open_ended_input_answers_div').show();
            } else if (question_type_id == 3) {
                $('.form-question-' + no + ' .panel-answer-type').show();
                $('.form-question-' + no + ' .panel_answer_type').html(`<button class="btn btn-square btn-outline-info font400 mr-1 mb-2 btnAnswerType" data-type="` + question_type_id + `" data-id="1">True or False</button>`);
                $('.form-question-' + no + ' .mcq_input_answers_div').hide();
                $('.form-question-' + no + ' .open_ended_input_answers_div').hide();
                $('.form-question-' + no + ' .fill_blank_answers_div').hide();
                $('.form-question-' + no + ' .panel_answer_type_text_image').hide();
                $('.form-question-' + no + ' .true_false_input_answers_div').show();
            } else if (question_type_id == 5 || question_type_id == 7) {
                $('.form-question-' + no + ' .panel-answer-type').show();
                $('.form-question-' + no + ' .panel_answer_type').html(`<button class="btn btn-square btn-outline-info font400 mr-1 mb-2 btnAnswerType" data-type="` + question_type_id + `" data-id="1">Multiple Choice</button>`);
                $('.form-question-' + no + ' .mcq_input_answers_div').hide();
                $('.form-question-' + no + ' .open_ended_input_answers_div').hide();
                $('.form-question-' + no + ' .true_false_input_answers_div').hide();
                $('.form-question-' + no + ' .fill_blank_answers_div').show();
                $('.form-question-' + no + ' .panel_answer_type_text_image').hide();
            } else if (question_type_id == 6) {
                $('.form-question-' + no + ' .panel-answer-type').show();
                $('.form-question-' + no + ' .panel_answer_type').html(`<button class="btn btn-square btn-outline-info font400 mr-1 mb-2 btnAnswerType" data-type="` + question_type_id + `" data-id="4">Exact Match</button>`);
                $('.form-question-' + no + ' .mcq_input_answers_div').hide();
                $('.form-question-' + no + ' .open_ended_input_answers_div').hide();
                $('.form-question-' + no + ' .true_false_input_answers_div').hide();
                $('.form-question-' + no + ' .fill_blank_answers_div').show();
                $('.form-question-' + no + ' .panel_answer_type_text_image').hide();
            }

        }
    });
}


var iic = [];

function addInstructionContent(type) {

    var instruction_content_id = $('.instruction_content_id').val();
    var instruction_counter = $('.instruction_counter').val();

    if (type == 'text') {

        var content = `
                    <div class="col-lg-12 mb-2">
                        <div class="form-inline">
                            <div class="form-group" style="width: 100%;text-align: center;">
                                <textarea name="input_instruction_text[]" id="instruction_text_` + instruction_content_id + `" class="form-control input_instruction_content" style="height: auto; resize: vertical; width: 90%; line-height: 1.75em; text-align:left;" data-type="text" contenteditable="true" spellcheck="false"></textarea>
                                <a style="cursor: pointer; text-decoration: none;" class="removeInstructionContent text-danger-active fs18 ml-2" data-index="`+ instruction_content_id + `" data-id="text"><i class="fa fa-times"></i></a>
                            </div>
                        </div>
                    </div>
        `;

    } else if (type == 'mathtext') {

        var content = `
                    <div class="col-lg-12 mb-2">
                        <div class="form-inline">
                            <div class="form-group" style="width: 100%;text-align: center;">
                                <span id="instruction_text_` + instruction_content_id + `" style="min-height: 50px; height: auto; width: 90%; text-align:left;" class="form-control math_text"></span>
                                <input name="input_instruction_text[]" id="question_text_hidden` + instruction_content_id + `" type="hidden" class="input_instruction_content" data-type="text">
                                    <a style="cursor: pointer; text-decoration: none;"
                                        class="removeInstructionContent text-danger-active fs18 ml-2" data-index="`+ instruction_content_id + `" data-id="text"><i
                                            class="fa fa-times"></i></a>
                            </div>
                        </div>
                    </div>
        `;

    } else if (type == 'image') {

        var content = `
                    <div class="col-lg-12 mb-2">                  
                        <div class="form-inline">
                            <div class="form-group" style="width: 100%;text-align: center;">
                                <div class="fileinput fileinput-new" data-provides="fileinput">							
                                    <div class="fileinput-preview thumbnail" data-trigger="fileinput" style="height: auto; width: 90%; padding: 0.5em;text-align: left;" class="form-control">
                                    <img src="https://smartjen.com/img/upload_file.png" class="img-responsive">
                                    </div>

                                    <a style="cursor: pointer; text-decoration: none;" class="removeInstructionContent text-danger-active fs18 ml-2" data-index="`+ instruction_content_id + `" data-id="image">
                                        <i class="fa fa-times"></i>
                                    </a>   

                                    <div>
                                        <span class="btn btn-default btn-file" style="display:none">
                                            <span class="fileinput-new">Select image</span>
                                            <span class="fileinput-exists">Change</span>
                                            <input type="file" class="form-control input_instruction_content" data-type="image" name="input_instruction_image[]" style="width: 90%; height: 40px; padding-top: 6px;" multiple="multiple" required />
                                        </span>
                                            
                                        <a href="#" class="btn btn-default fileinput-exists" data-dismiss="fileinput" style="display:none">Remove</a>
                                    </div>									
                                </div>  
                            </div>
                        </div>
                    </div>
        `;

    }

    $('.panel_instruction_content').append(content);


    if (type == 'mathtext') {
        $('.math_text').each(function (index) {
            let span_id = $(this).attr('id');
            var mathFieldSpan = document.getElementById(span_id);
            var MQ = MathQuill.getInterface(2);
            var mathField = MQ.MathField(mathFieldSpan, {
                handlers: {
                    edit: function () {
                        mathField.focus();
                    }
                },
                autoOperatorNames: 'somelongrandomoperatortooverride'
            });
        });


        $(document).on('click', '.math_text', function () {
            $('.panel_math_quill').fadeIn('fast');
            $('#mathTarget').val($(this).attr('id'));
        }).mouseleave(function () {

        }).focusout(function () {
            $('.section').click(function () {
                $('.panel_math_quill').hide();
            })
        })

    }

    instruction_content_id++;
    instruction_counter++;

    $('.instruction_content_id').val(instruction_content_id);
    $('.instruction_counter').val(instruction_counter);

    if (instruction_counter == 6) {
        $('.addInstructionContenButton').hide();
    }

}


var iac = [];

function addArticleContent(type) {

    var article_content_id = $('.article_content_id').val();
    var article_counter = $('.article_counter').val();

    if (type == 'text') {

        var content = `
                <div class="panel_input_article">
                    <div class="col-lg-11 mb-2">
                        <textarea name="input_ckarticle_text[]" id="article_cktext_` + article_content_id + `" spellcheck="false"></textarea>                        
                        <textarea name="input_article_text[]" id="input_article_text` + article_content_id + `" class="input_article_content" data-type="text" style="display: none;"></textarea>
                    </div>
                    <div class="col-lg-1 mb-2">
                        <a style="cursor: pointer; text-decoration: none;" class="removeArticleContent text-danger-active fs18 ml-2" data-index="`+ article_content_id + `" data-id="text"><i class="fa fa-times"></i></a>                        
                    </div>
                </div>
        `;

    } else if (type == 'mathtext') {

        var content = `
                <div class="panel_input_article">
                    <div class="col-lg-12 mb-2">
                        <div class="form-inline">
                            <div class="form-group" style="width: 100%;text-align: center;">
                                <span id="article_text_` + article_content_id + `" style="min-height: 50px; height: auto; width: 90%; text-align:left;" class="form-control math_text"></span>
                                <input name="input_article_text[]" id="question_text_hidden` + article_content_id + `" type="hidden" class="input_article_content" data-type="text">    
                                <a style="cursor: pointer; text-decoration: none;"
                                            class="removeArticleContent text-danger-active fs18 ml-2" data-index="`+ article_content_id + `" data-id="text"><i
                                                class="fa fa-times"></i></a>    
                            </div>
                        </div>                        
                    </div>
                </div>
        `;

    } else if (type == 'image') {

        var content = `
                <div class="panel_input_article">
                    <div class="col-lg-12 mb-2">                  
                        <div class="form-inline">
                            <div class="form-group" style="width: 100%;text-align: center;">
                                <div class="fileinput fileinput-new" data-provides="fileinput">							
                                    <div class="fileinput-preview thumbnail" data-trigger="fileinput" style="height: auto; width: 90%; padding: 0.5em;text-align: left;" class="form-control">
                                    <img src="https://smartjen.com/img/upload_file.png" class="img-responsive">
                                    </div>

                                    <a style="cursor: pointer; text-decoration: none;" class="removeArticleContent text-danger-active fs18 ml-2" data-index="`+ article_content_id + `" data-id="image">
                                        <i class="fa fa-times"></i>
                                    </a>   

                                    <div>
                                        <span class="btn btn-default btn-file" style="display:none">
                                            <span class="fileinput-new">Select image</span>
                                            <span class="fileinput-exists">Change</span>
                                            <input type="file" class="form-control input_article_content" data-type="image" name="input_article_image[]" style="width: 90%; height: 40px; padding-top: 6px;" multiple="multiple" required />   
                                        </span>
                                            
                                        <a href="#" class="btn btn-default fileinput-exists" data-dismiss="fileinput" style="display:none">Remove</a>
                                    </div>									
                                </div>  
                            </div>
                        </div>
                    </div>
                </div>
        `;

    }

    $('.panel_article_content').append(content);

    if (type == 'text') {

        var text_input = $('#input_article_text' + article_content_id);

        var editor = CKEDITOR.replace('article_cktext_' + article_content_id + '', {
            filebrowserUploadUrl: base_url + 'administrator/ck_upload_image/1',
            filebrowserUploadMethod: 'form',
            height: 150,
            toolbarCanCollapse: true
        });

        editor.on('contentDom', function () {
            this.document.on('click', function (event) {
                text_input.html(editor.getData().toString());
            });

        });

        editor.on('change', function (e) {
            text_input.html(editor.getData().toString());
        });

    }

    if (type == 'mathtext') {
        $('.math_text').each(function (index) {
            let span_id = $(this).attr('id');
            var mathFieldSpan = document.getElementById(span_id);
            var MQ = MathQuill.getInterface(2);
            var mathField = MQ.MathField(mathFieldSpan, {
                handlers: {
                    edit: function () {
                        mathField.focus();
                    }
                },
                autoOperatorNames: 'somelongrandomoperatortooverride'
            });
        });


        $(document).on('click', '.math_text', function () {
            $('.panel_math_quill').fadeIn('fast');
            $('#mathTarget').val($(this).attr('id'));
        }).mouseleave(function () {

        }).focusout(function () {
            $('.section').click(function () {
                $('.panel_math_quill').hide();
            })
        })

    }

    article_content_id++;
    article_counter++;

    $('.article_content_id').val(article_content_id);
    $('.article_counter').val(article_counter);

    if (article_counter == 6) {
        $('.addArticleContenButton').hide();
    }

}


var iqc = [];

function addQuestionContent(type) {

    var no = $('#question-page').val();
    var question_content_id = $('.form-question-' + no + ' .question_content_id').val();
    var content_counter = $('.form-question-' + no + ' .content_counter').val();

    if (type == 'text') {

        var question_type = $('#question_type_id_' + no).val();
        if (question_type == 5 || question_type == 6 || question_type == 7) {
            var buttonAdd = `<div class="col-lg-2 pb-10">
                                <button class="btn btn-sm btn-icon btn-teal btn-block cursor_pointer btnAddAnswerFITB_`+ no + `_` + question_content_id + `"><i class="picons-thin-icon-thin-0151_plus_add_new"></i>Add Answer</button>
                            </div>`;
        } else {
            var buttonAdd = '';
        }

        var content = `
                <div class="panel_question_content_` + no + `_` + question_content_id + `">
                    <div class="col-lg-11 mb-2">
                        <textarea name="input_ckquestion_text_`+ no + `[]" id="input_ckquestion_text_` + no + `_` + question_content_id + `" spellcheck="false"></textarea>
                        <textarea name="input_question_text_`+ no + `[]" id="input_question_text_` + no + `_` + question_content_id + `" class="input_question_content_` + no + `" data-type="text" style="display: none;"></textarea>
                    </div>
                    <div class="col-lg-1 mb-2">
                        <a style="cursor: pointer; text-decoration: none;" class="removeQuestionContent text-danger-active fs18 ml-2" data-index="`+ question_content_id + `" data-id="text"><i class="fa fa-times"></i></a>                        
                    </div>`+ buttonAdd + `
                </div>
        `;

    } else if (type == 'mathtext') {

        var content = `
                <div class="panel_question_content_` + no + `_` + question_content_id + `">
                    <div class="col-lg-12 mb-2">
                        <div class="form-inline">
                            <div class="form-group" style="width: 100%;text-align: center;">                   
                                <span id="question_text_`+ no + `_` + question_content_id + `" style="min-height: 50px; height: auto; width: 90%; text-align:left;" class="form-control math_text math_text_` + question_content_id + `"></span>
                                <input name="input_question_text_`+ no + `[]" id="question_text_hidden` + question_content_id + `" type="hidden" class="input_question_content_` + no + `" data-type="text">
                                <a style="cursor: pointer; text-decoration: none;" class="removeQuestionContent text-danger-active fs18 ml-2" data-index="`+ question_content_id + `" data-id="text">
                                <i class="fa fa-times"></i></a> 
                            </div>
                        </div>                               					
                    </div>                                            
                        	
                </div>
        `;

    } else if (type == 'image') {
        var content = `
                <div class="panel_question_content_` + no + `_` + question_content_id + `">
                    <div class="col-lg-12 mb-2">                  
                        <div class="form-inline">
                            <div class="form-group" style="width: 100%;text-align: center;">
                                <div class="fileinput fileinput-new" data-provides="fileinput">							
                                    <div class="fileinput-preview thumbnail" data-trigger="fileinput" style="height: auto; width: 90%; padding: 0.5em;text-align: left;" class="form-control">
                                    <img src="https://smartjen.com/img/upload_file.png" class="img-responsive">
                                    </div>

                                    <a style="cursor: pointer; text-decoration: none;" class="removeQuestionContent text-danger-active fs18 ml-2" data-index="`+ question_content_id + `" data-id="image">
                                        <i class="fa fa-times"></i>
                                    </a>   

                                    <div>
                                        <span class="btn btn-default btn-file" style="display:none">
                                            <span class="fileinput-new">Select image</span>
                                            <span class="fileinput-exists">Change</span>
                                            <input type="file" class="input_question_content_`+ no + `" data-type="image" name="input_question_image_` + no + `[]" style="width: 90%; height: 40px; padding-top: 6px;" multiple="multiple" required />
                                        </span>
                                            
                                        <a href="#" class="btn btn-default fileinput-exists" data-dismiss="fileinput" style="display:none">Remove</a>
                                    </div>									
                                </div>  
                            </div>
                        </div>
                    </div>
                </div>
        `;
    }

    $('.form-question-' + no + ' .panel_question_content .question_content').append(content);

    if (type == 'mathtext') {
        $('.math_text').each(function (index) {
            let span_id = $(this).attr('id');
            var mathFieldSpan = document.getElementById(span_id);
            var MQ = MathQuill.getInterface(2);
            var mathField = MQ.MathField(mathFieldSpan, {
                handlers: {
                    edit: function () {
                        mathField.focus();
                    }
                },
                autoOperatorNames: 'somelongrandomoperatortooverride'
            });
        });


        $(document).on('click', '.math_text', function () {
            $('.panel_math_quill').fadeIn('fast');
            $('#mathTarget').val($(this).attr('id'));
        }).mouseleave(function () {

        }).focusout(function () {
            $('.section').click(function () {
                $('.panel_math_quill').hide();
            })
        })

    }

    if (type == 'text') {
        var mathElements = [
            'math',
            'maction',
            'maligngroup',
            'malignmark',
            'menclose',
            'merror',
            'mfenced',
            'mfrac',
            'mglyph',
            'mi',
            'mlabeledtr',
            'mlongdiv',
            'mmultiscripts',
            'mn',
            'mo',
            'mover',
            'mpadded',
            'mphantom',
            'mroot',
            'mrow',
            'ms',
            'mscarries',
            'mscarry',
            'msgroup',
            'msline',
            'mspace',
            'msqrt',
            'msrow',
            'mstack',
            'mstyle',
            'msub',
            'msup',
            'msubsup',
            'mtable',
            'mtd',
            'mtext',
            'mtr',
            'munder',
            'munderover',
            'semantics',
            'annotation',
            'annotation-xml'
        ];

        CKEDITOR.plugins.addExternal('ckeditor_wiris',
            'https://ckeditor.com/docs/ckeditor4/4.14.1/examples/assets/plugins/ckeditor_wiris/',
            'plugin.js');

        var text_input = $('textarea#input_question_text_' + no + '_' + question_content_id);
        var text_input_reset = $('textarea#input_question_text_' + no + '_' + question_content_id + ' ans');

        var editor = CKEDITOR.replace('input_ckquestion_text_' + no + '_' + question_content_id + '', {
            extraPlugins: 'ckeditor_wiris',
            filebrowserUploadUrl: base_url + 'administrator/ck_upload_image/2',
            filebrowserUploadMethod: 'form',
            height: 150,
            toolbarCanCollapse: true,
            extraAllowedContent: 'strong[onclick]'
        });

        editor.on('contentDom', function () {
            this.document.on('click', function () {
                $elem = no + '_' + question_content_id;
                console.log($elem);
                // console.log(editor.getData());
                text_input.html(editor.getData().toString());
            });

        });

        editor.on('click', function () {
            $elem = no + '_' + question_content_id;
            console.log($elem);
            text_input.html(editor.getData().toString());
        });

        editor.on('change', function () {
            $elem = no + '_' + question_content_id;
            console.log($elem);
            text_input.html(editor.getData().toString());
        });

        $('.btnAddAnswerFITB_' + no + '_' + question_content_id).click(function (e) {
            e.preventDefault();

            var no = $('#question-page').val();
            var count_answer_fb = $('.form-question-' + no + ' .count_answer_fb').val();

            if (count_answer_fb == 1) {
                var resetQuestion = $('.form-question-' + no + ' .input_question_content').val();
                $('.form-question-' + no + ' .reset_question_fb').val(resetQuestion);
            }

            var quesTypeID = $('#question_type_id_' + no).val();

            var selection = editor.getSelection();
            var selectedContent = '';
            if (selection.getType() == CKEDITOR.SELECTION_ELEMENT) {
                selectedContent = selection.getSelectedElement().$.outerHTML;
            } else if (selection.getType() == CKEDITOR.SELECTION_TEXT) {
                if (CKEDITOR.env.ie) {
                    selection.unlock(true);
                    selectedContent = selection.getNative().createRange().text;
                } else {
                    selectedContent = selection.getNative().toString();
                    console.log("The selectedContent is: " + selectedContent);
                    editor.insertText('<ans>' + selectedContent + '</ans>');
                }
            }

            if (quesTypeID == 5) {

                var contentAnswer = `
                        <div class="col-lg-3 mt-2">
                            <div class="form-inline">
                                <div class="form-group" style="width: 100%;text-align: center;">
                                    <span class="number mr-1">`+ count_answer_fb + `</span>
                                    <input type="text" class="form-control input_style1_red" name="input_answer_fb_open_`+ no + `[]" value="` + selectedContent + `" style="width: 90%;">                                
                                </div>
                            </div>
                        </div>
                `;

            } else if (quesTypeID == 6 || quesTypeID == 7) {
                var labelAdd = '';
                if (quesTypeID == 6) {
                    labelAdd = 'Add Alternatives';
                } else if (quesTypeID == 7) {
                    labelAdd = 'Add Options';
                }

                var contentAnswer = `
                        <div class="col-lg-3">                        
                            <div class="card-header-small">
                                <div class="form-inline">
                                    <div class="form-group" style="width: 100%;">
                                        <span class="number mr-1">`+ count_answer_fb + `</span>
                                        <input type="text" class="form-control input_style1_black" style="width: 80%" name="input_answer_fb_open_`+ no + `_` + count_answer_fb + `[]" value="` + selectedContent + `">
                                        <a class="close_question text-success icon_close mr-2">
                                            <i class="fa fa-check"></i>
                                        </a>
                                    </div>
                                </div>                            
                            </div>
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item text-center panel_add_answer_fb" data-id="`+ no + `_` + count_answer_fb + `">
                                    <button class="btn btn-icon btn-default btn-block btn-sm addAnswerFB_`+ no + `_` + count_answer_fb + `"><i class="fa fa-plus"></i>` + labelAdd + `</button>
                                </li>                                
                            </ul>
                        </div>
                    `;

            }

            $('.form-question-' + no + ' .panel_answer_fb').show();

            $('.form-question-' + no + ' .panel_answer_fb').append(contentAnswer);

            if (quesTypeID == 6 || quesTypeID == 7) {
                $('.form-question-' + no + ' .btnAddDistractorFITB').hide();

                $('.addAnswerFB_' + no + '_' + count_answer_fb).click(function () {
                    var indexID = $(this).parent().data('id');
                    if (quesTypeID == 6) {
                        var addAnswer = `<li class="list-group-item form-inline">
                                            <input type="text" class="form-control input_style1_red mr-2" name="input_answer_fb_open_`+ indexID + `[]" placeholder="Alternative" style="width: 80%"/>
                                            <span class="text-success"><i class="fa fa-check"></i></span>
                                        </li>`;
                    } else if (quesTypeID == 7) {
                        var addAnswer = `<li class="list-group-item form-inline">
                                            <input type="text" class="form-control input_style1_red mr-2" name="input_answer_fb_open_`+ indexID + `[]" placeholder="Option" style="width: 80%" />
                                            <span class="text-danger"><i class="fa fa-times"></i></span>
                                        </li>`;
                    }

                    $(addAnswer).insertBefore($(this).parent());
                })
            } else if (quesTypeID == 5) {
                $('.form-question-' + no + ' .btnAddDistractorFITB').show();
            }

            // $elem.fieldSelection(`<ans>` + $elem.fieldSelection().text + `</ans>`);
            count_answer_fb++;
            $('.form-question-' + no + ' .count_answer_fb').val(count_answer_fb);

        })


        $('.btnResetAnswerFb').click(function (e) {
            e.preventDefault();
            var no = $('#question-page').val();
            var resetQuestion = $('.form-question-' + no + ' .reset_question_fb').val();
            $('.form-question-' + no + ' .input_question_content').val(resetQuestion);
            $('.form-question-' + no + ' .fill_blank_answers_div .panel_answer_fb').html('');
            $('.form-question-' + no + ' .count_answer_fb').val('1');

            text_input_reset.replaceWith('');

            editor.setData(text_input.text());
        })

    }

    question_content_id++;
    content_counter++;

    $('.form-question-' + no + ' .question_content_id').val(question_content_id);
    $('.form-question-' + no + ' .content_counter').val(content_counter);

    //hide addQuestionContentButton when there 6 fields append 
    if (content_counter == 6) {
        $('.form-question-' + no + ' .addQuestionContenButton').hide();
    }


}

// function test2(){

//     CKEDITOR.plugins.addExternal('ckeditor_wiris',
//             'https://ckeditor.com/docs/ckeditor4/4.14.1/examples/assets/plugins/ckeditor_wiris/',
//             'plugin.js');

//     var editor = CKEDITOR.replace('input_ckquestion_text_0_0', {
//         extraPlugins: 'ckeditor_wiris',
//         filebrowserUploadUrl: base_url + 'administrator/ck_upload_image/2',
//         filebrowserUploadMethod: 'form',
//         height: 150,
//         toolbarCanCollapse: true,
//         extraAllowedContent: 'strong[onclick]',
        
//     });

//     var t = 'test je ni';

//     // CKEDITOR.instances.editor.setData(t);
//     editor.setData(t);
// }

function showQuestionContent(type, question_type) {

    var no = $('#question-page').val();
    if(typeof no === 'undefined'){
        no = 0;
    }

    // var question_content_id = $('.form-question-' + no + ' .question_content_id').val();
    // var content_counter = $('.form-question-' + no + ' .content_counter').val();

    var question_content_id = 0;
    var content_counter = 0;

    if (type == 'text') {

        // var question_type = $('#question_type_id_' + no).val();
        var question_type = question_type;

        // format question text
        var question_text_hidden = $('textarea#input_question_fitb_' + no + '_' + question_content_id).val();
        var question_text_hidden = question_text_hidden.replace(/</g,'&lt;'); 
        var question_text_hidden = question_text_hidden.replace(/>/g,'&gt;'); 

        if(question_type == null){
            question_type = 6;
        }
        if (question_type == 5 || question_type == 6 || question_type == 7) {
            var buttonAdd = `<div class="col-lg-2 pb-10">
                                <button class="btn btn-sm btn-icon btn-teal btn-block cursor_pointer btnAddAnswerFITB_`+ no + `_` + question_content_id + `"><i class="picons-thin-icon-thin-0151_plus_add_new"></i>Add Answer</button>
                            </div>`;
        } else {
            var buttonAdd = '';
        }

        var content = `
                <div class="panel_question_content_` + no + `_` + question_content_id + `">
                    <div class="col-lg-11 mb-2">
                        <textarea name="input_ckquestion_text_`+ no + `[]" id="input_ckquestion_text_` + no + `_` + question_content_id + `" spellcheck="false"></textarea>
                        <textarea name="input_question_text_`+ no + `[]" id="input_question_text_` + no + `_` + question_content_id + `" class="input_question_content_` + no + `" data-type="text" style="display: none;">` + question_text_hidden + `</textarea>
                    </div>
                    <div class="col-lg-1 mb-2">
                        <a style="cursor: pointer; text-decoration: none;" class="removeQuestionContent text-danger-active fs18 ml-2" data-index="`+ question_content_id + `" data-id="text"><i class="fa fa-times"></i></a>                        
                    </div>`+ buttonAdd + `
                </div>
        `;

    } 
    

    $('.form-question-' + no + ' .panel_question_content .question_content').append(content);

    if (type == 'mathtext') {
        $('.math_text').each(function (index) {
            let span_id = $(this).attr('id');
            var mathFieldSpan = document.getElementById(span_id);
            var MQ = MathQuill.getInterface(2);
            var mathField = MQ.MathField(mathFieldSpan, {
                handlers: {
                    edit: function () {
                        mathField.focus();
                    }
                },
                autoOperatorNames: 'somelongrandomoperatortooverride'
            });
        });


        $(document).on('click', '.math_text', function () {
            $('.panel_math_quill').fadeIn('fast');
            $('#mathTarget').val($(this).attr('id'));
        }).mouseleave(function () {

        }).focusout(function () {
            $('.section').click(function () {
                $('.panel_math_quill').hide();
            })
        })

    }

    if (type == 'text') {
        var mathElements = [
            'math',
            'maction',
            'maligngroup',
            'malignmark',
            'menclose',
            'merror',
            'mfenced',
            'mfrac',
            'mglyph',
            'mi',
            'mlabeledtr',
            'mlongdiv',
            'mmultiscripts',
            'mn',
            'mo',
            'mover',
            'mpadded',
            'mphantom',
            'mroot',
            'mrow',
            'ms',
            'mscarries',
            'mscarry',
            'msgroup',
            'msline',
            'mspace',
            'msqrt',
            'msrow',
            'mstack',
            'mstyle',
            'msub',
            'msup',
            'msubsup',
            'mtable',
            'mtd',
            'mtext',
            'mtr',
            'munder',
            'munderover',
            'semantics',
            'annotation',
            'annotation-xml'
        ];

        CKEDITOR.plugins.addExternal('ckeditor_wiris',
            'https://ckeditor.com/docs/ckeditor4/4.14.1/examples/assets/plugins/ckeditor_wiris/',
            'plugin.js');

        var text_input = $('textarea#input_question_text_' + no + '_' + question_content_id);
        var text_input_reset = $('textarea#input_question_text_' + no + '_' + question_content_id + ' ans');

        var editor = CKEDITOR.replace('input_ckquestion_text_' + no + '_' + question_content_id + '', {
            extraPlugins: 'ckeditor_wiris',
            filebrowserUploadUrl: base_url + 'administrator/ck_upload_image/2',
            filebrowserUploadMethod: 'form',
            height: 150,
            toolbarCanCollapse: true,
            extraAllowedContent: 'strong[onclick]',
            
        });

        // display question text inside ckeditor    
        editor.setData(question_text_hidden);

        // end display data inside ckeditor

        editor.on('contentDom', function () {
            this.document.on('click', function () {
                $elem = no + '_' + question_content_id;
                console.log($elem);
                // console.log(editor.getData());
                text_input.html(editor.getData().toString());
            });

        });

        editor.on('click', function () {
            $elem = no + '_' + question_content_id;
            console.log($elem);
            text_input.html(editor.getData().toString());
        });

        editor.on('change', function () {
            $elem = no + '_' + question_content_id;
            console.log($elem);
            text_input.html(editor.getData().toString());
        });

        $('.btnAddAnswerFITB_' + no + '_' + question_content_id).click(function (e) {
            e.preventDefault();

            // var no = $('#question-page').val();
            var no = 0;

            var count_answer_fb = $('.form-question-' + no + ' .count_answer_fb').val();

            if (count_answer_fb == 1) {
                var resetQuestion = $('.form-question-' + no + ' .input_question_content').val();
                $('.form-question-' + no + ' .reset_question_fb').val(resetQuestion);
            }

            // var quesTypeID = $('#question_type_id_' + no).val();
            var quesTypeID = question_type;

            var selection = editor.getSelection();
            var selectedContent = '';
            if (selection.getType() == CKEDITOR.SELECTION_ELEMENT) {
                selectedContent = selection.getSelectedElement().$.outerHTML;
            } else if (selection.getType() == CKEDITOR.SELECTION_TEXT) {
                if (CKEDITOR.env.ie) {
                    selection.unlock(true);
                    selectedContent = selection.getNative().createRange().text;
                } else {
                    selectedContent = selection.getNative().toString();
                    console.log("The selectedContent is: " + selectedContent);
                    editor.insertText('<ans>' + selectedContent + '</ans>');
                }
            }

            if (quesTypeID == 5) {

                var contentAnswer = `
                        <div class="col-lg-3 mt-2">
                            <div class="form-inline">
                                <div class="form-group" style="width: 100%;text-align: center;">
                                    <span class="number mr-1">`+ count_answer_fb + `</span>
                                    <input type="text" class="form-control input_style1_red" name="input_answer_fb_open_`+ no + `[]" value="` + selectedContent + `" style="width: 90%;">                                
                                </div>
                            </div>
                        </div>
                `;

            } else if (quesTypeID == 6 || quesTypeID == 7) {
                var labelAdd = '';
                if (quesTypeID == 6) {
                    labelAdd = 'Add Alternatives';
                } else if (quesTypeID == 7) {
                    labelAdd = 'Add Options';
                }

                var contentAnswer = `
                        <div class="col-lg-3">                        
                            <div class="card-header-small">
                                <div class="form-inline">
                                    <div class="form-group" style="width: 100%;">
                                        <span class="number mr-1">`+ count_answer_fb + `</span>
                                        <input type="text" class="form-control input_style1_black" style="width: 80%" name="input_answer_fb_open_`+ no + `_` + count_answer_fb + `[]" value="` + selectedContent + `">
                                        <a class="close_question text-success icon_close mr-2">
                                            <i class="fa fa-check"></i>
                                        </a>
                                    </div>
                                </div>                            
                            </div>
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item text-center panel_add_answer_fb" data-id="`+ no + `_` + count_answer_fb + `">
                                    <button class="btn btn-icon btn-default btn-block btn-sm addAnswerFB_`+ no + `_` + count_answer_fb + `"><i class="fa fa-plus"></i>` + labelAdd + `</button>
                                </li>                                
                            </ul>
                        </div>
                    `;

            }

            $('.form-question-' + no + ' .panel_answer_fb').show();

            $('.form-question-' + no + ' .panel_answer_fb').append(contentAnswer);

            if (quesTypeID == 6 || quesTypeID == 7) {
                $('.form-question-' + no + ' .btnAddDistractorFITB').hide();

                $('.addAnswerFB_' + no + '_' + count_answer_fb).click(function () {
                    var indexID = $(this).parent().data('id');
                    if (quesTypeID == 6) {
                        var addAnswer = `<li class="list-group-item form-inline">
                                            <input type="text" class="form-control input_style1_red mr-2" name="input_answer_fb_open_`+ indexID + `[]" placeholder="Alternative" style="width: 80%"/>
                                            <span class="text-success"><i class="fa fa-check"></i></span>
                                        </li>`;
                    } else if (quesTypeID == 7) {
                        var addAnswer = `<li class="list-group-item form-inline">
                                            <input type="text" class="form-control input_style1_red mr-2" name="input_answer_fb_open_`+ indexID + `[]" placeholder="Option" style="width: 80%" />
                                            <span class="text-danger"><i class="fa fa-times"></i></span>
                                        </li>`;
                    }

                    $(addAnswer).insertBefore($(this).parent());
                })
            } else if (quesTypeID == 5) {
                $('.form-question-' + no + ' .btnAddDistractorFITB').show();
            }

            // $elem.fieldSelection(`<ans>` + $elem.fieldSelection().text + `</ans>`);
            count_answer_fb++;
            $('.form-question-' + no + ' .count_answer_fb').val(count_answer_fb);

        })

        $('.btnResetAnswerFb').click(function (e) {
            e.preventDefault();

            var no = 0;
            var no = $('#question-page').val();
            alert(no);
            var resetQuestion = $('.form-question-' + no + ' .reset_question_fb').val();
            $('.form-question-' + no + ' .input_question_content').val(resetQuestion);
            $('.form-question-' + no + ' .fill_blank_answers_div .panel_answer_fb').html('');
            $('.form-question-' + no + ' .count_answer_fb').val('1');

            text_input_reset.replaceWith('');

            editor.setData(text_input.text());
        })

    }

    question_content_id++;
    content_counter++;

    $('.form-question-' + no + ' .question_content_id').val(question_content_id);
    $('.form-question-' + no + ' .content_counter').val(content_counter);

    //hide addQuestionContentButton when there 6 fields append 
    if (content_counter == 6) {
        $('.form-question-' + no + ' .addQuestionContenButton').hide();
    }


}


var iwc = [];

function addWorkingContent(type) {

    var no = $('#question-page').val();
    var working_content_id = $('.form-question-' + no + ' .working_content_id').val();
    var working_content_counter = $('.form-question-' + no + ' .working_content_counter').val();

    if (type == 'text') {

        var content = `
                    <div class="col-lg-12 mb-2" >
                        <div class="form-inline">
                            <div class="form-group" style="width: 100%;text-align: center;">
                                <textarea name="input_working_text_`+ no + `_[]" class="form-control input_working_content_` + no + `" style="height: auto; resize: vertical; width: 90%; line-height: 1.75em; text-align:left;" data-type="text" contenteditable="true" spellcheck="false"></textarea>
                                <a style="cursor: pointer; text-decoration: none;" class="removeWorkingContent text-danger-active fs18 ml-2" data-index="`+ working_content_id + `" data-id="text"><i class="fa fa-times"></i></a>
                            </div>
                        </div>
                    </div>
        `;

    } else if (type == 'mathtext') {

        var content = `
                    <div class="col-lg-12 mb-2" >
                        <div class="form-inline">
                            <div class="form-group" style="width: 100%;text-align: center;">
                                <span id="working_text_`+ no + `_` + working_content_id + `" style="height: auto; width: 90%; text-align:left;" class="form-control math_text math_text_` + working_content_id + `"></span>
                                <input name="input_working_text_`+ no + `_[]" id="working_text_hidden` + working_content_id + `" type="hidden" class="input_working_content_` + no + `" data-type="text">
                                    <a style="cursor: pointer; text-decoration: none;"
                                        class="removeWorkingContent text-danger-active fs18 ml-2" data-index="`+ working_content_id + `" data-id="text"><i
                                            class="fa fa-times"></i></a>
                            </div>
                        </div>
                    </div>
        `;

    } else if (type == 'image') {

        var content = `
                    <div class="col-lg-12 mb-2">                  
                        <div class="form-inline">
                            <div class="form-group" style="width: 100%;text-align: center;">
                                <div class="fileinput fileinput-new" data-provides="fileinput">							
                                    <div class="fileinput-preview thumbnail" data-trigger="fileinput" style="height: auto; width: 90%; padding: 0.5em;text-align: left;" class="form-control">
                                    <img src="https://smartjen.com/img/upload_file.png" class="img-responsive">
                                    </div>

                                    <a style="cursor: pointer; text-decoration: none;" class="removeWorkingContent text-danger-active fs18 ml-2" data-index="`+ working_content_id + `" data-id="image">
                                        <i class="fa fa-times"></i>
                                    </a>   

                                    <div>
                                        <span class="btn btn-default btn-file" style="display:none">
                                            <span class="fileinput-new">Select image</span>
                                            <span class="fileinput-exists">Change</span>
                                            <input type="file" class="form-control input_working_content_`+ no + `" data-type="image" name="input_working_image_` + no + `_[]" style="width: 90%; height: 40px; padding-top: 6px;" multiple="multiple" required />
                                        </span>
                                            
                                        <a href="#" class="btn btn-default fileinput-exists" data-dismiss="fileinput" style="display:none">Remove</a>
                                    </div>									
                                </div>  
                            </div>
                        </div>
                    </div>
        `;

    }

    $('.form-question-' + no + ' .working_content').append(content);

    if (type == 'mathtext') {
        $('.math_text').each(function (index) {
            let span_id = $(this).attr('id');
            var mathFieldSpan = document.getElementById(span_id);
            var MQ = MathQuill.getInterface(2);
            var mathField = MQ.MathField(mathFieldSpan, {
                handlers: {
                    edit: function () {
                        mathField.focus();
                    }
                },
                autoOperatorNames: 'somelongrandomoperatortooverride'
            });
        });


        $(document).on('click', '.math_text', function () {
            $('.panel_math_quill').fadeIn('fast');
            $('#mathTarget').val($(this).attr('id'));
        }).mouseleave(function () {

        }).focusout(function () {
            $('.section').click(function () {
                $('.panel_math_quill').hide();
            })
        })

    }

    working_content_id++;
    working_content_counter++;

    $('.form-question-' + no + ' .working_content_id').val(working_content_id);
    $('.form-question-' + no + ' .working_content_counter').val(working_content_counter);

    //hide addWorkingContenButton when there 6 fields append 
    if (working_content_counter == 6) {
        $('.form-question-' + no + ' .addWorkingContenButton').hide();
    }

}


function edit_question() {

    $.ajax({
        type: 'POST',
        url: base_url + 'administrator/update_question',
        data: new FormData($('#form-create')[0]),
        processData: false,
        contentType: false,
        cache: false,
        async: false,
        dataType: 'json',
        beforeSend: function (e) {
            if (e && e.overrideMimeType) {
                e.overrideMimeType("application/json;charset=UTF-8");
            }
        },
        success: function (res) {
            console.log(res);

            if (res.msg == 'success') {

                swal({
                    text: "Question has been updated!",
                    icon: 'success',
                    button: 'OK'
                })
                    .then((OK) => {
                        if (OK) {
                            window.location.reload();
                        }
                    });
            }
        }
    });
}


// Case Study
// var answerNumber = 1;

// $(document).on('mouseup', 'body', function () {
//     if (getSelectionText()) {
//         $('#answer' + answerNumber).val(getSelectionText());
//         answerNumber++;
//     }
// });

// function getSelectionText() {
//     if (window.getSelection) {
//         try {
//             var tarea = $('textarea').get(0);

//             return (tarea.selectionStart != tarea.selectionEnd) ? tarea.value.substring(tarea.selectionStart, tarea.selectionEnd) : '';
//         } catch (e) {
//             console.log('Cant get selection text')
//         }
//     }

//     // For IE
//     // if (document.selection && document.selection.type != 'Control')
//     //     return document.selection.createRange().text;
// }


var mathKeyboard = $('#mathExpressionKeyboard');
var inputWordKeyboard = $('#addQuestionModal');
var MQ = MathQuill.getInterface(2);

function input(str) {
    let spanId = $('#mathTarget').val();
    let spanTarget = document.getElementById(spanId);
    let mathSpan = MQ(spanTarget);
    if (mathSpan) {
        mathKeyboard.modal('hide');
        mathSpan.cmd(str);
        mathSpan.focus();
    }
}

function inputText() {

    let str2 = document.getElementById("inputWord").value;
    let str_array = str2.split("");

    for (var i = 0; i < str_array.length; i++) {
        let spanId = $('#mathTarget').val();
        let spanTarget = document.getElementById(spanId);
        let mathSpan = MQ(spanTarget);

        if (mathSpan) {
            inputWordKeyboard.modal('hide');
            mathSpan.cmd(str_array[i]);
            mathSpan.focus();
        }
    }

    document.getElementById("inputWord").value = '';

}

function inputMultiple(str1, str2) {
    let spanId = $('#mathTarget').val();
    let spanTarget = document.getElementById(spanId);
    let mathSpan = MQ(spanTarget);
    if (mathSpan) {
        mathKeyboard.modal('hide');
        mathSpan.cmd(str1);
        mathSpan.cmd(str2);
        mathSpan.focus();
    }
}

function inputMultipleMultiple(str1, str2, str3) {

    let spanId = $('#mathTarget').val();
    let spanTarget = document.getElementById(spanId);
    let mathSpan = MQ(spanTarget);

    if (mathSpan) {
        mathKeyboard.modal('hide');
        mathSpan.cmd(str1);
        mathSpan.cmd(str2);
        mathSpan.cmd(str3);
        mathSpan.focus();
    }
}

