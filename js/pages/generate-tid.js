


var substrand_id_list = [];
var topic_id_list = [];
var heuristic_scid_list = [];
var heuristic_id_list = [];
var strategy_scid_list = [];
var strategy_id_list = [];
var generate_edit_question = false;
var edit_generate = false;
var generate = false;
var search = false;

function getHeuristicsList(subject_id) {
    $.ajax({
        url: base_url + 'smartgen/getHeuristicsList',
        method: 'GET',
        data: {
            subject_id: subject_id
        },
        dataType: 'json',
        success: function (data) {
            heuristic_scid_list = [];
            obj = {
                'heuristic_id': 'all',
                'heuristic_name': 'Any Heuristic',
                disable: false
            };
            heuristic_scid_list.push(obj);
            for (i = 0; i < data.length; i++) {
                obj = {
                    'heuristic_id': data[i].heuristic_id,
                    'heuristic_name': data[i].heuristic_name,
                    disable: true
                };
                heuristic_scid_list.push(obj);
            }
        }
    });
}


function getStrategyList(subject_id) {
    $.ajax({
        url: base_url + 'smartgen/getStrategyList',
        method: 'GET',
        data: {
            subject_id: subject_id
        },
        dataType: 'json',
        success: function (data) {
            strategy_scid_list = [];
            obj = {
                'strategy_id': 'all',
                'strategy_name': 'Any Strategy',
                disable: false
            };
            strategy_scid_list.push(obj);
            for (i = 0; i < data.length; i++) {
                obj = {
                    'strategy_id': data[i].id,
                    'strategy_name': data[i].name,
                    disable: true
                };
                strategy_scid_list.push(obj);
            }
        }
    });
}


function getWsSubstrandList(selector, subject_id, level_name) {
    var substrand_select = selector[0].selectize;
    substrand_select.settings.placeholder = 'Please select Strands';
    substrand_select.updatePlaceholder();
    $.ajax({
        url: base_url + 'smartgen/worksheetGetSubstr',
        method: 'GET',
        data: {
            subject_id: subject_id,
            level_name: level_name
        },
        dataType: 'json',
        success: function (data) {
            check = false;
            substrand_id_list = [];
            obj = {
                'substrand_id': 'all',
                'substrand_name': 'Any Strand'
            };
            substrand_id_list.push(obj);
            for (i = 0; i < data.length; i++) {
                if (data[i].level == 1) {
                    obj = {
                        'substrand_id': data[i].substrand_id,
                        'substrand_name': data[i].substrand_name,
                        disable: false
                    };
                } else if (data[i].level == 0) {
                    obj = {
                        'substrand_id': data[i].substrand_id,
                        'substrand_name': data[i].substrand_name,
                        disable: true
                    };
                } else {
                    obj = {
                        'substrand_id': data[i].substrand_id,
                        'substrand_name': data[i].substrand_name,
                        disable: false
                    };
                }
                substrand_id_list.push(obj);
                check = true;
            }

            substrand_select.clear();
            substrand_select.clearOptions();
            substrand_select.addOption(substrand_id_list);
            substrand_select.setValue(substrand_id_list[0].substrand_id);
        }
    });
}


function getWsTopicList(selector, substrand_id, level_name) {
    var topic_select = selector[0].selectize;
    topic_select.settings.placeholder = 'Please select Topic';
    topic_select.updatePlaceholder();
    $.ajax({
        url: base_url + 'smartgen/worksheetGetTopic',
        method: 'GET',
        data: {
            substrand_id: substrand_id,
            level_name: level_name
        },
        dataType: 'json',
        success: function (data) {
            check = false;
            topic_id_list = [];
            obj = {
                'topic_id': 'all',
                'topic_name': 'Any Topic'
            };
            topic_id_list.push(obj);
            for (i = 0; i < data.length; i++) {
                if (data[i].level == 1) {
                    obj = {
                        'topic_id': data[i].topic_id,
                        'topic_name': data[i].topic_name,
                        disable: false
                    };
                } else if (data[i].level == 0) {
                    obj = {
                        'topic_id': data[i].topic_id,
                        'topic_name': data[i].topic_name,
                        disable: true
                    };
                } else {
                    obj = {
                        'topic_id': data[i].topic_id,
                        'topic_name': data[i].topic_name,
                        disable: false
                    };
                }
                topic_id_list.push(obj);
                check = true;
            }

            topic_select.clear();
            topic_select.clearOptions();
            topic_select.addOption(topic_id_list);
            topic_select.enable();
            topic_select.setValue(topic_id_list[0].topic_id);
        }
    });
}


function getWsHeuristicList(selector, subject_id, substrand_id, topic_id, level_name) {
    var heuristic_select = selector[0].selectize;
    heuristic_select.clearOptions();
    heuristic_select.settings.placeholder = 'Any Heuristic';
    heuristic_select.updatePlaceholder();
    $.ajax({
        url: base_url + 'smartgen/worksheetGetHeuristic',
        method: 'GET',
        data: {
            subject_id: subject_id,
            substrand_id: substrand_id,
            topic_id: topic_id,
            level_name: level_name
        },
        dataType: 'json',
        success: function (data) {
            check = false;
            heuristic_id_list = [];
            if (subject_id !== '') {
                for (i = 0; i < heuristic_scid_list.length; i++) {
                    obj = {
                        'heuristic_id': heuristic_scid_list[i].heuristic_id,
                        'heuristic_name': heuristic_scid_list[i].heuristic_name,
                        disable: false
                    }
                    heuristic_id_list.push(obj);
                }
            } else {
                for (i = 0; i < heuristic_scid_list.length; i++) {
                    obj = {
                        'heuristic_id': heuristic_scid_list[i].heuristic_id,
                        'heuristic_name': heuristic_scid_list[i].heuristic_name,
                        disable: heuristic_scid_list[i].disable
                    }
                    heuristic_id_list.push(obj);
                }

                for (j = 0; j < data.length; j++) {
                    for (i = 0; i < heuristic_id_list.length; i++) {
                        if (data[j].heuristic_id == heuristic_scid_list[i].heuristic_id && data[j].level == 1) {
                            heuristic_id_list[i].disable = false;
                            check = true;
                            break;
                        }
                    }
                }
            }
            setTimeout(function () {
                heuristic_select.addOption(heuristic_id_list);
            }, 200);
            setTimeout(function () {
                heuristic_select.setValue('all');
            }, 400);

        }
    });
}


function getWsStrategyList(selector, subject_id, substrand_id, topic_id, heuristic_id, level_name) {
    var strategy_select = selector[0].selectize;
    strategy_select.settings.placeholder = 'Any Strategy';
    strategy_select.updatePlaceholder();
    $.ajax({
        url: base_url + 'smartgen/worksheetGetStrategy',
        method: 'GET',
        data: {
            subject_id: subject_id,
            substrand_id: substrand_id,
            topic_id: topic_id,
            heuristic_id: heuristic_id,
            level_name: level_name
        },
        dataType: 'json',
        success: function (data) {
            check = false;
            strategy_id_list = [];
            if (subject_id !== '') {
                for (i = 0; i < strategy_scid_list.length; i++) {
                    obj = {
                        'strategy_id': strategy_scid_list[i].strategy_id,
                        'strategy_name': strategy_scid_list[i].strategy_name,
                        disable: false
                    }
                    strategy_id_list.push(obj);
                }
            } else {
                for (i = 0; i < strategy_scid_list.length; i++) {
                    obj = {
                        'strategy_id': strategy_scid_list[i].strategy_id,
                        'strategy_name': strategy_scid_list[i].strategy_name,
                        disable: strategy_scid_list[i].disable
                    }
                    strategy_id_list.push(obj);
                }

                for (j = 0; j < data.length; j++) {
                    for (i = 0; i < strategy_scid_list.length; i++) {
                        if (data[j].strategy_id == strategy_scid_list[i].strategy_id && data[j].level == 1) {
                            strategy_id_list[i].disable = false;
                            check = true;
                            break;
                        }
                    }
                }

            }
            strategy_select.clear();
            strategy_select.clearOptions();
            strategy_select.addOption(strategy_id_list);
            strategy_select.setValue(strategy_id_list[0].strategy_id);
        }
    });
}


function createSelectize(selector, valueField, labelField, searchField, placeholder) {
    selector.selectize({
        valueField: valueField,
        labelField: labelField,
        searchField: searchField,
        placeholder: placeholder,
        options: [],
        disabledField: 'disable',
        create: false
    });
}


function clearHeuristicStrategy(selector_heuristic, selector_strategy, clearOptions) {
    selector_heuristic[0].selectize.clear();
    selector_strategy[0].selectize.clear();
    selector_heuristic[0].selectize.settings.placeholder = 'No Heuristic';
    selector_heuristic[0].selectize.updatePlaceholder();

    selector_strategy[0].selectize.settings.placeholder = 'No Strategy';
    selector_strategy[0].selectize.updatePlaceholder();
    if (clearOptions == true) {
        selector_heuristic[0].selectize.clearOptions();
        selector_strategy[0].selectize.clearOptions();
    }
}


function getLevelName(level_id) {
    $.ajax({
        type: 'GET',
        url: base_url + 'ExamMode/getLevel/' + level_id,
        dataType: 'json',
        success: function (res) {
            level_name = res.data.level_name;
        }
    });
}


$(document).ready(function () {

    // createSelectize($('#gen_substrand'), 'substrand_id', 'substrand_name', 'substrand_name', 'Please select Strand');
    // createSelectize($('#gen_topic'), 'topic_id', 'topic_name', 'topic_name', 'Please select Topic');
    // createSelectize($('#gen_heuristic'), 'heuristic_id', 'heuristic_name', 'heuristic_name', 'Please select Heuristic');
    // createSelectize($('#gen_strategy'), 'strategy_id', 'strategy_name', 'strategy_name', 'Please select Strategy');

    // getLevelName(level_id);

    // if (subject_id == 5) {
    //     $('.non_mcq_type').prop('checked', true);
    //     $('.mcq_type').prop('disabled', true);
    // }
    // $('body').bind('cut copy paste', function (e) {
    //     e.preventDefault();
    // });

    // $('body').on('contextmenu', function (e) {
    //     e.preventDefault();
    // });

    // if (typeof document.onselectstart != "undefined") {
    //     document.onselectstart = new Function("return false");
    // } else {
    //     document.onmousedown = new Function("return false");
    //     document.onmouseup = new Function("return false");
    // }  

    $('.setActiveMCQ').click(function () {
        var status = $(this).data('status');
        if (status == 1) {
            active = 2;
        } else {
            active = 1;
        }
        $('#cek_que_type').data('status', active);
        $('#gen_que_type').val(active);
    });
    $('.setActiveAND').click(function () {
        var status = $(this).data('status');
        if (status == 1) {
            active = 2;
        } else {
            active = 1;
        }
        $('#cek_operator').data('status', active);
        $('#gen_operator').val(active);
    });

    function getTopicLevelList(level) {
        $.ajax({
            url: base_url + 'smartgen/getTIDList',
            method: 'POST',
            data: {
                level: level
            },
            dataType: 'json',
            success: function (data) {
                // alert($('#gen_topic_'+ses_num).val());
                $('#gen_topic')
                    .find('option')
                    .remove()
                    .end();
                $('#gen_topic')
                        .append('<option value="all" >Any topic</option>');
                for (i = 0; i < data.length; i++) {
                    $('#gen_topic')
                        .append('<option value="' + data[i].topic_id + '" >' + data[i].topic_name + '</option>');
                }
            }
        });
    }  

    function getAbilityList(ability="all") {
        $.ajax({
            url: base_url + 'smartgen/getTIDAbilityList',
            method: 'POST',
            data: {
            },
            dataType: 'json',
            success: function (data) {
                // alert($('#gen_topic_'+ses_num).val());
                $('#gen_ability')
                    .find('option')
                    .remove()
                    .end();
                $('#gen_ability')
                        .append('<option value="all" >Any ability</option>');
                for (i = 0; i < data.length; i++) {
                    var selected = (ability==data[i].ability_id) ? "selected" : "";
                    $('#gen_ability')
                        .append('<option value="' + data[i].ability_id + '" '+selected+'>' + data[i].ability_name + '</option>');
                }
            }
        });
    }

    $(function () {
        $.each(target_id, function (key, value) {
            var parent_id = $('#subqid_' + key);

            $(parent_id).trigger('click');
        });
    });

    $(document).on('change', '.question_order', function () {
        var id = $(this).data('id');
        var number = $(this).data('number');
        changeOrder(id, number, $(this).val());
    });


    $(document).on('click', '.regen_question', function (e) {
        e.preventDefault();
        var clickedStr = ($(this).attr('id')).split("_");
        var id = $(this).data('id');
        var question_number = $(this).data('question_number');
        var que_type = $(this).data('type');
        var quesNum = clickedStr[1];
        var nextQuesNum = parseInt(quesNum) + 1;
        var quesList = $(this).parents('#question_' + quesNum);
        var nextQuesList = quesList.next().attr('class');
        var selectedText = $('select[name=question_' + quesNum + '] option:selected').text();
        var lastChar = selectedText.substr(selectedText.length - 1);
        if (lastChar == 'a') {
            selectedText = selectedText.slice(0, -1);
            $('select[name=question_' + quesNum + '] option:selected').text(selectedText);
        }

        $.ajax({
            url: base_url + 'smartgen/regenerateQuestionTID/' + id + '/' + que_type,
            method: 'POST',
            dataType: 'json',
            success: function (data) {

                if(data['error_gen']!="") {
                    swal('Warning!', data['error_gen'], 'warning');
                } else {
                    if (nextQuesList === 'list-group-item clearfix') {
                        $(quesList).nextUntil('#addNewSubQuestionDiv_' + nextQuesNum).remove();
                    }
                    $('.sub_question_' + id).remove();

                    $('#question_' + quesNum + ' select[name=question_' + quesNum + ']').removeClass().addClass('question_order question_' + data['question'].question_id);

                    // $('#question_' + quesNum + " .question_category").animate({ opacity: 0 }, function () {
                    //     $(this).html('');
                    //     $(this).append('[' + data['question'].substrand_name + '] ' + data['question'].category_name + '<a style="cursor: pointer;" class="question-remove" onClick="removeQuestion(' + id + ', ' + question_number + ', ' + quesNum + ')" title="Remove Question" data-id="' + data['question'].question_id + '"><i class="fa fa-times"></i></a>').animate({ opacity: 1 });
                    // });

                    // $('#question_' + quesNum + " .question_strategy").animate({ opacity: 0 }, function () {
                    //     $(this).html('');
                    //     $(this).append(data['question'].strategy_name).animate({ opacity: 1 });
                    // });

                    $('#question_' + quesNum + ' .question_difficulty').animate({ opacity: 0 }, function () {
                        $(this).html('');
                        if (data['question'].difficulty == 1) {
                            showDifficulty = 'Easy';
                        } else if (data['question'].difficulty == 2) {
                            showDifficulty = 'Normal';
                        } else if (data['question'].difficulty == 3) {
                            showDifficulty = 'Hard';
                        } else if (data['question'].difficulty == 4 || data['question'].difficulty == 5) {
                            showDifficulty = 'Genius';
                        }
                        $(this).append(`(` + data['question'].difficulty + ` Marks, ` + showDifficulty + `)`).animate({ opacity: 1 });
                    });

                    $('#question_' + quesNum + " .question_text").animate({ opacity: 0 }, function () {
                        $(this).html('');
                        var correctAnswerOptionNum = '';
                        var correctAnswer = '';
                        var answerOptionHtml = "<br><br>";
                        if (que_type == 1) {
                            var answerOption = data['answer'];
                            var i = 1;
                            for (var answer in answerOption) {

                                var ansText = answerOption[answer].answer_text;
                                var splitAnsText = ansText.split(" ");
                                var arraySplitAnsText = new Array();
                                for (let a = 0, b = splitAnsText.length; a <= b; a++) {
                                    if(splitAnsText[a]) {
                                        var test = splitAnsText[a].indexOf('\\(');
                                        if(test === -1){
                                            arraySplitAnsText.push('\\(' + splitAnsText[a] + '\\)');
                                        } else {
                                            arraySplitAnsText.push(splitAnsText[a]);
                                        }
                                    }
                                }
                                var newAnsText = arraySplitAnsText.join(" ");

                                if (answerOption[answer].answer_id == answerOption[answer].correct_answer) {
                                    correctAnswerOptionNum = answerOption[answer].answer_id;
                                    correctAnswer = answerOption[answer].answer_text;
                                    answerOptionHtml += "<span class='correctAnswer'>\\(" + i + "\\) ) " + newAnsText + "</span> <i class='fa fa-check answeredCorrectly'></i><br>";
                                } else {
                                    answerOptionHtml += "\\(" + i + "\\) ) " + newAnsText + "<br>";
                                }
                                i++;
                            }
                        } else {
                            var answerOption = data['answer'];
                            var i = 1;
                            for (var answer in answerOption) {

                                var ansText = answerOption[answer].answer_text;
                                var splitAnsText = ansText.split(" ");
                                var arraySplitAnsText = new Array();
                                for (let a = 0, b = splitAnsText.length; a <= b; a++) {
                                    if(splitAnsText[a]) {
                                        var test = splitAnsText[a].indexOf('\\(');
                                        if(test === -1){
                                            arraySplitAnsText.push('\\(' + splitAnsText[a] + '\\)');
                                        } else {
                                            arraySplitAnsText.push(splitAnsText[a]);
                                        }
                                    }
                                }
                                var newAnsText = arraySplitAnsText.join(" ");

                                if (answerOption[answer].answer_id == answerOption[answer].correct_answer) {
                                    correctAnswerOptionNum = answerOption[answer].answer_id;
                                    correctAnswer = answerOption[answer].answer_text;
                                    answerOptionHtml += "<span class='correctAnswer'>Ans: " + newAnsText + "</span> <i class='fa fa-check answeredCorrectly'></i><br>";
                                }
                                i++;
                            }
                        }

                        if (lastChar == 'a') {
                            $('#correct_answer_' + quesNum + lastChar).nextUntil('#correct_answer_' + nextQuesNum).remove();
                            $('#correct_answer_' + quesNum + lastChar).attr("id", "correct_answer_" + quesNum);
                            $('#correct_answer_' + quesNum).html(correctAnswer);
                        } else {
                            $('#correct_answer_' + quesNum).html(correctAnswer);
                        }
                        // $('#correct_answer_' + quesNum).html('(' + correctAnswerOptionNum + ') ' + correctAnswer);

                        var queText = data['question'].question_text;
                        var splitQueText = queText.split(" ");
                        var arraySplitQueText = new Array();
                        for (let a = 0, b = splitQueText.length; a <= b; a++) {
                            if(splitQueText[a]) {
                                var test = splitQueText[a].indexOf('\\(');
                                if(test === -1){
                                    arraySplitQueText.push('\\(' + splitQueText[a] + '\\)');
                                } else {
                                    arraySplitQueText.push(splitQueText[a]);
                                }
                            }
                        }
                        var newText = arraySplitQueText.join(" ");

                        if (data['question'].graphical != "0") {
                            $(this).append(newText).append('<img src="' + data['question'].branch_image_url + '/questionImage/' + data['question'].graphical + '" class="img-responsive" style="max-width:60%;">').append(answerOptionHtml).animate({ opacity: 1 });
                        } else {
                            $(this).append(newText + answerOptionHtml).animate({ opacity: 1 });
                        }

                        if (data.subquestion.length > 1) {
                            var button = '<button class="btn btn-warning pull-right sub_question sub_question_' + id + '" id="subqid_' + data['question'].question_id + '" data-type="' + que_type + '" data-number="' + quesNum + '"><span data-toggle="tooltip" data-placement="top" data-original-title="Add sub question, please">Sub Question</span></button>';
                            $(button).insertBefore($(this).next().next().next().next().next());
                        }

                        if (que_type == 1) {
                            $(this).data('type', 1);
                            $(".mcq_type_" + quesNum).fadeOut(250).fadeIn(500).html('MCQ');
                        } else {
                            $(this).data('type', 2);
                            $(".mcq_type_" + quesNum).fadeOut(250).fadeIn(500).html('Non-MCQ');
                        }

                        $(this).next().attr('id', 'qid_' + data['question'].question_id);

                        MathJax.Hub.Typeset();

                    });
                }
            }

        });

        return false;
    });

    $(document).on('click', '.sub_question', function (e) {
        var clickedStr = ($(this).attr('id')).split("_");
        var subQueId = clickedStr[1];
        var quesType = $(this).data('type');
        var selectedText = $('.question_' + subQueId + ' option:selected').text();

        var ajax_url = base_url + 'smartgen/subQuestion';
        var newDiv = $(this).parent();
        var newDivSplit = (newDiv.attr('id')).split("_");
        var newDivId = newDivSplit[1];
        // var spanList = newDiv.children()[0].children[0].innerText;
        // var getSpanList = spanList.split("]");
        var substrandList = newDiv.children().find("span")[0].innerText;
        var categoryList = newDiv.children().find("span")[1].innerText;
        // var oldMainQuesNo = newDiv.children('.question_number').children('.question_order').children("option:selected").text();
        // var newMainQuesNo = oldMainQuesNo + 'a';
        // newDiv.children('.question_number').children('.question_order').children("option:selected").text(newMainQuesNo);
        $('.question_' + subQueId + ' option:selected').text(selectedText + 'a');
        // var DivId = newDivSplit.join('_') + 'a'; root cause subquestion
        // newDiv.attr("id", DivId);
        var mainQuesType = $(this).prev().prev().text();

        $.ajax({
            url: ajax_url,
            method: "post",
            dataType: 'json',
            data: {
                "sub_question_id": subQueId,
            },
            success: function (data) {
                let mcqCount = 1;
                var subQueTotal = 1;
                let questionImage = '';
                let alp = ['b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm'];
                var subQuestions = new Array();
                var correctAnswers = new Array();

                for (i = 0; i < data.questionList.length; i++) {
                    subQueTotal++;
                    let questionAnswer = '';
                    let questionAnswer1 = '';
                    let questionAnswer2 = '';
                    let questionAnswer3 = '';

                    subQuestions.push(data.questionList[i].question_id);
                    questionText = data.questionList[i].question_text;
                    questionId = data.questionList[i].question_id;
                    var newDivs = document.createElement("LI");
                    newDivs.className = "list-group-item clearfix";
                    newDivs.setAttribute("id", "question_" + newDivId + alp[i]);

                    if (data.questionList[i].graphical != 0) {
                        questionImage = '<img src="' + data.questionList[i].branch_image_url + '/questionImage/' + data.questionList[i].graphical + '" class="img-responsive" style="max-width:60%;">';
                    }


                    var correctAns = data.answerList[i].correctAnswer;
                    var correctAnsText = data.answerList[i].correctAnswerText;

                    var spanClass = '';
                    var icon = '';
                    var spanClass1 = '';
                    var icon1 = '';
                    var spanClass2 = '';
                    var icon2 = '';
                    var spanClass3 = '';
                    var icon3 = '';

                    if (mainQuesType == 'MCQ') {

                        if (correctAns == data.answerList[i].answerOption[0].answer_id) {
                            spanClass = 'correctAnswer ';
                            icon = '<i class="fa fa-check answeredCorrectly"></i>';
                        } else {
                            spanClass = '';
                            icon = '';
                        }

                        if (correctAns == data.answerList[i].answerOption[1].answer_id) {
                            spanClass1 = 'correctAnswer ';
                            icon1 = '<i class="fa fa-check answeredCorrectly"></i>';
                        } else {
                            spanClass1 = '';
                            icon1 = '';
                        }

                        if (correctAns == data.answerList[i].answerOption[2].answer_id) {
                            spanClass2 = 'correctAnswer ';
                            icon2 = '<i class="fa fa-check answeredCorrectly"></i>';
                        } else {
                            spanClass2 = '';
                            icon2 = '';
                        }

                        if (correctAns == data.answerList[i].answerOption[3].answer_id) {
                            spanClass3 = 'correctAnswer ';
                            icon3 = '<i class="fa fa-check answeredCorrectly"></i>';
                        } else {
                            spanClass3 = '';
                            icon3 = '';
                        }

                        questionAnswer = '<span class="' + spanClass + '"> 1 ) ' + data.answerList[i].answerOption[0].answer_text + '</span>' + icon + '<br >';
                        questionAnswer1 = '<span class="' + spanClass1 + '"> 2 ) ' + data.answerList[i].answerOption[1].answer_text + '</span>' + icon1 + '<br >';
                        questionAnswer2 = '<span class="' + spanClass2 + '"> 3 ) ' + data.answerList[i].answerOption[2].answer_text + '</span>' + icon2 + '<br >';
                        questionAnswer3 = '<span class="' + spanClass3 + '"> 4 ) ' + data.answerList[i].answerOption[3].answer_text + '</span>' + icon3 + '<br >';
                        var queTypeBtn = '<span class="btn btn-info pull-right que_type" data-toggle="tooltip" data-placement="top" title="" data-original-title="This question is only available in MCQ">MCQ</span>';
                    } else {
                        questionAnswer = '<br><span class="correctAnswer ">Ans: ' + correctAnsText + '</span><i class="fa fa-check answeredCorrectly"></i>';
                        questionAnswer1 = '';
                        questionAnswer2 = '';
                        questionAnswer3 = '';
                        var queTypeBtn = '<span class="btn btn-info pull-right que_type" data-toggle="tooltip" data-placement="top" title="" data-original-title="This question is only available in Non-MCQ">Non-MCQ</span>';
                    }



                    correctAnswer = '<div id="correct_answer_' + newDivId + alp[i] + '" class="correctAnswerText">' + correctAnsText + '</div>';

                    correctAnswers.push(correctAnswer);

                    var contentSubquestion = `
							<div class="question_number">Question ${newDivId}${alp[i]} 
							<span class="pull-right question_category">${substrandList}</span>
							<br>
							<span class="pull-right" style="padding-top:9px;">${categoryList}</span>
							</div>
							<div class="question_text">`+ questionText + `
							<div>`
                        + questionImage +
                        `</div>
							<div class="question_answer">`
                        // + questionAnswer +
                        + questionAnswer + questionAnswer1 + questionAnswer2 + questionAnswer3 +
                        `</div>
							</div>
                            <button class="btn btn-warning pull-right remove_sub_question remove_sub_question_${subQueId}" id="remove_sub_question_${questionId}"><span data-toggle="tooltip" data-placement="top" title="Remove this question, please.">Remove Sub Question</span></button>
                            ` + queTypeBtn + `
					`;


                    MathJax.Hub.Queue(['Typeset', MathJax.Hub, questionText]);
                    MathJax.Hub.Queue(['Typeset', MathJax.Hub, questionAnswer]);
                    MathJax.Hub.Queue(['Typeset', MathJax.Hub, questionAnswer1]);
                    MathJax.Hub.Queue(['Typeset', MathJax.Hub, questionAnswer2]);
                    MathJax.Hub.Queue(['Typeset', MathJax.Hub, questionAnswer3]);
                    // console.log(`newDivId: ` + newDivId);
                    // console.log(contentSubquestion);
                    $("#addNewSubQuestionDiv_" + newDivId).append(`<li class="list-group-item clearfix">` + contentSubquestion + `</li>`);
                }

                $('#correct_answer_' + newDivId).after(correctAnswers);
                var newAnswerDiv = 'correct_answer_' + newDivId + 'a'
                var answerDiv = $('#correct_answer_' + newDivId);
                answerDiv.attr('id', newAnswerDiv);


                // Add reference to parent question
                $('[data-id="' + subQueId + '"]').data('sub_question', subQuestions);

                $('.flag_question').on('click', function () {
                    $('#flag_question_error').hide();
                    $('#flag_question_success').hide();
                    $('#flagged_question_id').val($(this).attr('id').split('_')[1]);
                });

                $('.remove_sub_question_' + subQueId).on('click', function () {
                    var sub_que_id = $(this).attr('id').split('_')[3];
                    var parent_que_id = subQueId;
                    var correctAnswerNo = $(this).parent().find('.question_number').text().split(" ")[1];
                    correctAnswerAlp = correctAnswerNo.replace(newDivId, "");
                    $('#correct_answer_' + newDivId + correctAnswerAlp).remove();
                    var mainCorrectAnswer = "correct_answer_" + newDivId;

                    subQueTotal--;
                    if (subQueTotal == 1) {
                        console.log('selectedText: ' + selectedText);
                        $("#subqid_" + subQueId).show();
                        $('.question_' + parent_que_id + ' option:selected').text(selectedText);
                        $('#correct_answer_' + newDivId + 'a').attr("id", mainCorrectAnswer);
                    }
                    $(this).parent().remove();
                    $.ajax({
                        url: base_url + 'smartgen/removeSubQuestion',
                        type: 'POST',
                        dataType: 'json',
                        data: {
                            parent_question_id: parent_que_id,
                            sub_question_id: sub_que_id
                        },
                        success: function (result) {

                            if (typeof result[parent_que_id] == 'undefined') {

                            }
                        }
                    });

                });
            }
        });
        $(this).hide();
    });

    $(document).on('click', '.sub_question_admin', function (e) {
        var clickedStr = ($(this).attr('id')).split("_");
        var subQueId = clickedStr[1];
        var quesType = $(this).data('type');
        var selectedText = $('.question_' + subQueId + ' option:selected').text();
        var ajax_url = base_url + 'smartgen/subQuestion';
        var newDiv = $(this).parent();
        var newDivSplit = (newDiv.attr('id')).split("_");
        var newDivId = newDivSplit[1];
        var spanList = newDiv.children()[0].children[0].innerText;
        var getSpanList = spanList.split("]");
        var substrandList = newDiv.children().find("span")[0].innerText;
        var categoryList = newDiv.children().find("span")[1].innerText;
        var oldMainQuesNo = newDiv.children('.question_number').children('.question_order').children("option:selected").text();
        var newMainQuesNo = oldMainQuesNo + 'a';
        newDiv.children('.question_number').children('.question_order').children("option:selected").text(newMainQuesNo);
        // var DivId = newDivSplit.join('_') + 'a'; root cause subquestion
        // newDiv.attr("id", DivId);
        var mainQuesType = $(this).prev().prev().text();

        $.ajax({
            url: ajax_url,
            method: "post",
            dataType: 'json',
            data: {
                "sub_question_id": subQueId,
            },
            success: function (data) {
                let mcqCount = 1;
                let questionImage = '';
                let alp = ['a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm'];
                var subQuestions = new Array();
                var correctAnswers = new Array();
                for (i = 0; i < data.questionList.length; i++) {
                    let questionAnswer = '';
                    let questionAnswer1 = '';
                    let questionAnswer2 = '';
                    let questionAnswer3 = '';

                    subQuestions.push(data.questionList[i].question_id);
                    questionText = data.questionList[i].question_text;
                    questionId = data.questionList[i].question_id;
                    var newDivs = document.createElement("LI");
                    newDivs.className = "list-group-item clearfix";
                    newDivs.setAttribute("id", "question_" + newDivId + alp[i]);

                    if (data.questionList[i].graphical != 0) {
                        questionImage = '<img src="' + data.questionList[i].branch_image_url + '/questionImage/' + data.questionList[i].graphical + '" class="img-responsive" style="max-width:60%;">';
                    }


                    var correctAns = data.answerList[i].correctAnswer;

                    var spanClass = '';
                    var icon = '';
                    var spanClass1 = '';
                    var icon1 = '';
                    var spanClass2 = '';
                    var icon2 = '';
                    var spanClass3 = '';
                    var icon3 = '';

                    if (mainQuesType == 'MCQ') {

                        if (correctAns == data.answerList[i].answerOption[0].answer_id) {
                            spanClass = 'correctAnswer ';
                            icon = '<i class="fa fa-check answeredCorrectly"></i>';
                        } else {
                            spanClass = '';
                            icon = '';
                        }

                        if (correctAns == data.answerList[i].answerOption[1].answer_id) {
                            spanClass1 = 'correctAnswer ';
                            icon1 = '<i class="fa fa-check answeredCorrectly"></i>';
                        } else {
                            spanClass1 = '';
                            icon1 = '';
                        }

                        if (correctAns == data.answerList[i].answerOption[2].answer_id) {
                            spanClass2 = 'correctAnswer ';
                            icon2 = '<i class="fa fa-check answeredCorrectly"></i>';
                        } else {
                            spanClass2 = '';
                            icon2 = '';
                        }

                        if (correctAns == data.answerList[i].answerOption[3].answer_id) {
                            spanClass3 = 'correctAnswer ';
                            icon3 = '<i class="fa fa-check answeredCorrectly"></i>';
                        } else {
                            spanClass3 = '';
                            icon3 = '';
                        }

                        questionAnswer = '<span class="' + spanClass + '"> 1 ) ' + data.answerList[i].answerOption[0].answer_text + '</span>' + icon + '<br >';
                        questionAnswer1 = '<span class="' + spanClass1 + '"> 2 ) ' + data.answerList[i].answerOption[1].answer_text + '</span>' + icon1 + '<br >';
                        questionAnswer2 = '<span class="' + spanClass2 + '"> 3 ) ' + data.answerList[i].answerOption[2].answer_text + '</span>' + icon2 + '<br >';
                        questionAnswer3 = '<span class="' + spanClass3 + '"> 4 ) ' + data.answerList[i].answerOption[3].answer_text + '</span>' + icon3 + '<br >';
                    } else {
                        questionAnswer = '<br><span class="correctAnswer ">Ans: ' + correctAns + '</span><i class="fa fa-check answeredCorrectly"></i>';
                        questionAnswer1 = '';
                        questionAnswer2 = '';
                        questionAnswer3 = '';
                    }



                    correctAnswer = '<div id="correct_answer_' + newDivId + alp[i] + '" class="correctAnswerText">(' + data.answerList[i].correctAnswerOptionNum + ') ' + data.answerList[i].correctAnswer + '</div>';

                    correctAnswers.push(correctAnswer);

                    $(newDivs).append(`<input type="hidden" name="question_type[]" value="` + quesType + `" >
                            <div class="question_number">Question ${newDivId}${alp[i]} 
                            <span class="pull-right question_category">${substrandList}</span>
                            <br>
                            <span class="pull-right" style="padding-top:9px;">${categoryList}</span>
                            </div>
                            <div class="question_text">`+ questionText + `
                            <div>`
                        + questionImage +
                        `</div>
                            <div class="question_answer">`
                        // + questionAnswer +
                        + questionAnswer + questionAnswer1 + questionAnswer2 + questionAnswer3 +
                        `</div>
                            </div>
                            <button class="btn btn-warning pull-right remove_sub_question" id="remove_sub_question_${questionId}"><span data-toggle="tooltip" data-placement="top" title="Remove this question, please.">Remove Sub Question</span></button>
                    `);


                    MathJax.Hub.Queue(['Typeset', MathJax.Hub, questionText]);
                    MathJax.Hub.Queue(['Typeset', MathJax.Hub, questionAnswer]);
                    MathJax.Hub.Queue(['Typeset', MathJax.Hub, questionAnswer1]);
                    MathJax.Hub.Queue(['Typeset', MathJax.Hub, questionAnswer2]);
                    MathJax.Hub.Queue(['Typeset', MathJax.Hub, questionAnswer3]);
                    $("#addNewSubQuestionDiv_" + newDivId).append(newDivs);
                }

                $('#correct_answer_' + newDivId).after(correctAnswers);
                var newAnswerDiv = 'correct_answer_' + newDivId + 'a'
                var answerDiv = $('#correct_answer_' + newDivId);
                answerDiv.attr('id', newAnswerDiv);


                // Add reference to parent question
                $('[data-id="' + subQueId + '"]').data('sub_question', subQuestions);

                $('.flag_question').on('click', function () {
                    $('#flag_question_error').hide();
                    $('#flag_question_success').hide();
                    $('#flagged_question_id').val($(this).attr('id').split('_')[1]);
                });

                $('.remove_sub_question_' + subQueId).on('click', function () {
                    var sub_que_id = $(this).attr('id').split('_')[3];
                    var parent_que_id = subQueId;

                    subQueTotal--;
                    if (subQueTotal == 1) {
                        console.log('selectedText: ' + selectedText);
                        $("#subqid_" + subQueId).show();
                        $('.question_' + parent_que_id + ' option:selected').text(selectedText);
                    }
                    $(this).parent().remove();
                    $.ajax({
                        url: base_url + 'smartgen/removeSubQuestion',
                        type: 'POST',
                        dataType: 'json',
                        data: {
                            parent_question_id: parent_que_id,
                            sub_question_id: sub_que_id
                        },
                        success: function (result) {

                            if (typeof result[parent_que_id] == 'undefined') {

                            }
                        }
                    });

                });
            }
        });
        $(this).hide();
    });

    var array = new Array();

    $('.flag_question').on('click', function () {
        $('#flag_question_error').hide();
        $('#flag_question_success').hide();
        $('#flagged_question_id').val($(this).attr('id').split('_')[1]);
    });

    $('#flag_question_button').on('click', function (e) {
        e.preventDefault();
        if ($('#error_comment').val().length > 1024) {
            $('#flag_question_error').html('Comment exceeded 1024 characters').show('fast').delay(3000).hide('slow');
            return false;
        }

        var $this = $(this);
        $this.button('loading');

        var ajax_url = base_url + 'smartgen/flagQuestion';

        $.ajax({
            url: ajax_url,
            method: "post",
            dataType: 'json',
            data: {
                "user_id": $('#user_id').val(),
                "error_type": $('#error_type').val(),
                "error_comment": $('#error_comment').val(),
                "flagged_question_id": $('#flagged_question_id').val()
            },
            success: function (data) {
                if (data['success'] == true) {
                    $('#flag_question_success').show('fast').delay(3000).hide('slow');
                    $('#error_comment').val('');
                } else {
                    $('#flag_question_error').html('Some issue sending to Smartjen.. please try again later').show('fast').delay(5000).hide('slow');
                }
                $this.button('reset');
            }
        });
    });

    $(document).on('click', '.edit_question', function () {
        $('.panel_edit_generate_question').hide();
        var substrand = $(this).data('substrands');
        var topic = $(this).data('topic');
        var level = $(this).data('level_id');
        var ability = $(this).data('ability');
        var strategy = $(this).data('strategy');
        if (substrand == '') substrand = 'Any Strand';
        if (topic == '') topic = 'Any Topic';
        if (strategy == '') strategy = '';

        $('#generate_id').val($(this).data('id'));
        $('#generate_level').val($(this).data('tid_level'));
        $('#generate_subject').val($(this).data('subject'));
        $('#generate_quetype').val($(this).data('type'));
        $('#generate_section_number').val($(this).data('section'));
        $('input[name="gen_que_type"]').filter("[value=" + $(this).data('type') + "]").attr('checked', true);
        getTopicLevelList(level);
        getAbilityList(ability);
        generate_edit_question = false;
        generate = false;
        search = false;
        $('#searchKeyword').val('');
        ajax_more_questions(1);
        getHeaderQuestion($(this).data('id'));
    });

    $(document).on('change', '#gen_substrand', function (e) {
        e.preventDefault();
        var substrand_id = $(this).val();
        if (subject_id == 2) {
            getWsTopicList($('#gen_topic'), substrand_id, level_name);
            if (substrand_id != 'all' && substrand_id != '') {
                getWsHeuristicList($('#gen_heuristic'), '', substrand_id, '', level_name);
                getWsStrategyList($('#gen_strategy'), '', substrand_id, '', '', level_name);
            } else {
                getWsHeuristicList($('#gen_heuristic'), subject_id, '', '', level_name);
                getWsStrategyList($('#gen_strategy'), subject_id, '', '', '', level_name);
            }
        } else {
            getWsTopicList($('#gen_topic'), substrand_id, level_name);
        }

        return false;
    });


    $(document).on('change', '#gen_topic', function (e) {
        e.preventDefault();
        var substrand_id = $('#gen_substrand').val();
        var topic_id = $(this).val();
        if (subject_id == 2 && substrand_id != 'all' && topic_id != 'all') {
            getWsHeuristicList($('#gen_heuristic'), '', '', topic_id, level_name);
        } else if (subject_id == 2 && substrand_id != 'all' && topic_id == 'all') {
            getWsHeuristicList($('#gen_heuristic'), '', substrand_id, '', level_name);
        }

        return false;
    });


    $(document).on('change', '#gen_heuristic', function (e) {
        e.preventDefault();
        var substrand_id = $('#gen_substrand').val();
        var topic_id = $('#gen_topic').val();
        var heuristic_id = $(this).val();
        if (subject_id == 2 && substrand_id == 'all' && topic_id == 'all' && heuristic_id == 'all') {
            getWsStrategyList($(this).parent().closest('.session-group').find('.gen_strategy'), subject_id, '', '', '', level_name);
        } else if (subject_id == 2 && substrand_id != 'all' && topic_id == 'all' && heuristic_id == 'all') {
            getWsStrategyList($(this).parent().closest('.session-group').find('.gen_strategy'), '', substrand_id, '', '', level_name);
        } else if (subject_id == 2 && substrand_id != 'all' && topic_id != 'all' && heuristic_id == 'all') {
            getWsStrategyList($(this).parent().closest('.session-group').find('.gen_strategy'), '', substrand_id, topic_id, '', level_name);
        } else if (subject_id == 2 && substrand_id != 'all' && topic_id != 'all' && heuristic_id != 'all') {
            getWsStrategyList($(this).parent().closest('.session-group').find('.gen_strategy'), '', '', topic_id, heuristic_id, level_name);
        } else if (subject_id == 2 && substrand_id == 'all' && topic_id == 'all' && heuristic_id != 'all') {
            getWsStrategyList($(this).parent().closest('.session-group').find('.gen_strategy'), '', '', '', heuristic_id, level_name);
        } else if (subject_id == 2 && substrand_id != 'all' && topic_id == 'all' && heuristic_id != 'all') {
            getWsStrategyList($(this).parent().closest('.session-group').find('.gen_strategy'), '', substrand_id, '', heuristic_id, level_name);
        }

        return false;
    });


});

$(function () {

    $(".wall-content").on('click', '#pag-addMore a', function (e) {
        e.preventDefault();
        //console.log($(this).data("ci-pagination-page"));          
        ajax_more_questions($(this).data("ci-pagination-page"));
    });

    $(".wall-content").on('click', '.card-columns .card_question_text', function (e) {
        e.preventDefault();
        //console.log('here');
        swal({
            title: "Are you sure?",
            text: "You will change the question!",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        })
            .then((Yes) => {
                if (Yes) {
                    swal("The question has been changed!", {
                        icon: "success",
                    });
                    update_selected_questions($(this).data('id'));
                }
            });

    });

    $(".wall-content").on('mouseenter', '.card-columns .card_question_text', function (e) {
        e.preventDefault();
        $(this).addClass('bg-success');
    });

    $(".wall-content").on('mouseout', '.card-columns .card_question_text', function (e) {
        e.preventDefault();
        $(this).removeClass('bg-success');
    });


    $(".wall-content").on('click', '.card-columns .fa-caret', function (e) {
        e.preventDefault();
        var id = $(this).data('id');
        var caret = $(this).find('i').attr('class');
        if (caret == 'fa fa-caret-down') {
            $(this).children().removeClass('fa fa-caret-down').addClass('fa fa-caret-up');
            // $(this).parent().parent().find('.card_question_information').slideDown(500);
            getInformation(id);
        } else {
            $(this).children().removeClass('fa fa-caret-up').addClass('fa fa-caret-down');
            // $(this).parent().parent().find('.card_question_information').slideUp(250);
            $('#card_question_title_' + id).html($(this).data('title'));
        }

    });

    $('.wall-content').on('click', '.card-columns .icon_close', function (e) {
        e.preventDefault();
        $(this).parent().parent().remove();
    });


    $('#edit_generate_question').click(function () {
        if (edit_generate == false) {
            edit_generate = true;
            $(this).find('.fa-caret-down').removeClass('fa-caret-down').addClass('fa-caret-up');
            $('.panel_edit_generate_question').fadeIn();
        } else {
            edit_generate = false;
            $(this).find('.fa-caret-up').removeClass('fa-caret-up').addClass('fa-caret-down');
            $('.panel_edit_generate_question').fadeOut();
        }
    });

    $(document).on('click', '#gen_button', function () {
        generate = true;
        search = false;
        ajax_more_questions(1);
        edit_generate = false;
        $('#edit_generate_question').find('.fa-caret-up').removeClass('fa-caret-up').addClass('fa-caret-down');
        $('#searchKeyword').val('');
        $(".panel_edit_generate_question").LoadingOverlay("show", {
            background: "rgba(0, 0, 0, 0.2)"
        });
    })

    $('#searchKeyword').keypress(function (e) {
        var key = e.which;
        // the enter key code
        if (key == 13) {
            $(".panel_generate_question").LoadingOverlay("show", {
                background: "rgba(0, 0, 0, 0.2)"
            });
            generate = false;
            search = true;
            ajax_more_questions(1);
            return false;
        }
    })

})


function getHeaderQuestion(id) {
    $.ajax({
        url: base_url + 'smartgen/getHeaderEdit/' + id,
        type: 'GET',
        dataType: 'json',
        success: function (res) {
            $('.worksheet_substr').html(res.reqTopic);
            $('.worksheet_ability').html(res.reqAbility);
        }
    });
}


function getInformation(id) {
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
                content = `<p style="line-height: 1.3rem;">` + substrand_name + ` / ` + topic_name + ` / ` + difficulty + `</p>`;
            } else {
                content = `<p style="line-height: 1.3rem;">` + substrand_name + ` / ` + topic_name + ` / ` + strategy_name + ` / ` + difficulty + `</p>`;
            }

            // $('#card_question_title_' + id).html(substrand_name);
            $('#card_question_title_' + id).html(content);
        }
    });
}


function ajax_more_questions(page) {

    if (page == 'undefined') {
        page = 1;
    }
    var difficulties = $("input[name='gen_difficulties[]']:checked").map(function () { return $(this).val(); }).get();
    $.ajax({
        url: base_url + 'smartgen/getMoreQuestionsTID/' + page,
        type: 'GET',
        dataType: 'text', // added data type
        data: {
            id: $('#generate_id').val(),
            level: $('#generate_level').val(),
            queType: $('#generate_quetype').val(),
            generate: generate,
            topic: $('#gen_topic').val(),
            ability: $('#gen_ability').val(),
            operator: $('#gen_operator').val(),
            que_type: $('#gen_que_type').val(),
            difficulties: difficulties,
            search: search,
            searchKeyword: $('#searchKeyword').val()
        },
        success: function (res) {
            $(".wall-content").html(res);
            $('.card_question_information').hide();
            MathJax.Hub.Typeset();
            $('.grid').masonry({
                // options
                itemSelector: '.grid-item',
                columnWidth: '.grid-item',
                percentPosition: false,
                fitWidth: false,
                horizontalOrder: true
            });

            setInterval(() => {
                $('.grid').masonry('layout');
            }, 1000);

            MathJax.Hub.Queue(function () {
                $('.grid').masonry('layout');
            });

            $(".panel_generate_question").LoadingOverlay("hide", true);
            $(".panel_edit_generate_question").LoadingOverlay("hide", true);
            $('.panel_edit_generate_question').fadeOut();
        }
    });
}

function update_selected_questions(question_id) {
    var que_type = $('#gen_que_type').val();
    var operator_ab = $('#gen_operator').val();
    var quesNum = $('#generate_section_number').val();
    var nextQuesNum = quesNum + 1;
    var quesList = $('#edit_question_' + quesNum).parents('#question_' + quesNum);
    var nextQuesList = quesList.next().attr('class');
    var newQuesNo = " Question " + quesNum;
    var id = $('#generate_id').val();
    $.ajax({
        type: 'POST',
        url: base_url + 'smartgen/updateQuestion',
        data: {
            id: $('#generate_id').val(),
            question_id: question_id,
            question_type: que_type,
            operator_ab: operator_ab,
            quesNum: quesNum
        },
        dataType: "json",
        success: function (data) {
            if (nextQuesList === 'list-group-item clearfix') {
                $(quesList).nextUntil('#addNewSubQuestionDiv_' + nextQuesNum).remove();
            }
            $('.sub_question_' + id).remove();

            $('#question_' + quesNum + ' select[name=question_' + quesNum + ']').removeClass().addClass('question_order question_' + data['question'].question_id);

            $('.question_' + data['question'].question_id + ' option:selected').text(newQuesNo);

            // $('#question_' + quesNum + " .question_category").animate({ opacity: 0 }, function () {
            //     $(this).html('');
            //     $(this).append('[' + data['question'].substrand_name + '] ' + data['question'].category_name + '<a href="#" class="question-remove" title="Remove Question" data-id="' + data['question'].question_id + '"><i class="fa fa-times"></i></a>').animate({ opacity: 1 });
            // });
            // $('#question_' + quesNum + " .question_strategy").animate({ opacity: 0 }, function () {
            //     $(this).html('');
            //     $(this).append(data['question'].strategy_name).animate({ opacity: 1 });
            // });
            $('#question_' + quesNum + " .question_difficulty").animate({ opacity: 0 }, function () {
                $(this).html('');
                if (data['question'].difficulty == 1) {
                    showDifficulty = 'Easy';
                } else if (data['question'].difficulty == 2) {
                    showDifficulty = 'Normal';
                } else if (data['question'].difficulty == 3) {
                    showDifficulty = 'Hard';
                } else if (data['question'].difficulty == 4 || data['question'].difficulty == 5) {
                    showDifficulty = 'Genius';
                }
                $(this).append(`(` + data['question'].difficulty + ` Marks, ` + showDifficulty + `)`).animate({ opacity: 1 });
            });
            $('#question_' + quesNum + " .question_text").animate({ opacity: 0 }, function () {
                $(this).html('');
                var correctAnswerOptionNum = '';
                var correctAnswer = '';
                var answerOptionHtml = "<br><br>";
                if (que_type == 1) {
                    var answerOption = data['answer'];
                    var i = 1;
                    for (var answer in answerOption) {
                        if (answerOption[answer].answer_id == answerOption[answer].correct_answer) {
                            correctAnswerOptionNum = answerOption[answer].answer_id;
                            correctAnswer = answerOption[answer].answer_text;
                            answerOptionHtml += "<span class='correctAnswer'>" + i + ") " + answerOption[answer].answer_text + "</span> <i class='fa fa-check answeredCorrectly'></i><br>";
                        } else {
                            answerOptionHtml += i + ") " + answerOption[answer].answer_text + "<br>";
                        }

                        i++;
                    }
                } else {
                    var answerOption = data['answer'];
                    var i = 1;
                    for (var answer in answerOption) {
                        if (answerOption[answer].answer_id == answerOption[answer].correct_answer) {
                            correctAnswerOptionNum = answerOption[answer].answer_id;
                            correctAnswer = answerOption[answer].answer_text;
                            answerOptionHtml += "<span class='correctAnswer'>Ans: " + answerOption[answer].answer_text + "</span> <i class='fa fa-check answeredCorrectly'></i><br>";
                        }
                        i++;
                    }
                }

                $('#correct_answer_' + quesNum).html('(' + correctAnswerOptionNum + ') ' + correctAnswer);

                if (data['question'].graphical != "0") {
                    $(this).append(data['question'].question_text).append('<img src="' + data['question'].branch_image_url + '/questionImage/' + data['question'].graphical + '" class="img-responsive" style="max-width:60%;">').append(answerOptionHtml).animate({ opacity: 1 });
                } else {
                    $(this).append(data['question'].question_text + answerOptionHtml).animate({ opacity: 1 });
                }

                if (data.subquestion.length > 1) {
                    var button = '<button class="btn btn-warning pull-right sub_question sub_question_' + id + '" id="subqid_' + data['question'].question_id + '" data-type="' + que_type + '"><span data-toggle="tooltip" data-placement="top" data-original-title="Add sub question, please">Sub Question</span></button>';
                    $(button).insertBefore($(this).parent());
                }

                // if (data['question'].sub_question == "B") {  
                //     $(this).parent().append('<button class="btn btn-warning pull-right sub_question" id="subqid_"' + data['question'].question_id + '><span data-toggle="tooltip" data-placement="top" data-original-title="Add sub question, please">Sub Question</span></button>');                  
                // }

                if (que_type == 1) {
                    $("#regen_" + quesNum).data('type', 1);
                    $(".mcq_type_" + quesNum).fadeOut(250).fadeIn(500).html('MCQ');
                } else {
                    $("#regen_" + quesNum).data('type', 2);
                    $(".mcq_type_" + quesNum).fadeOut(250).fadeIn(500).html('Non-MCQ');
                }

                $(this).next().attr('id', 'qid_' + data['question'].question_id);

                MathJax.Hub.Typeset();
            });
        }

    });
}

function removeQuestion(id, number, selector) {
    $.ajax({
        url: base_url + 'ExamMode/deleteQuestion',
        type: 'POST',
        dataType: 'json',
        data: { id: id },
        success: function (res) {
            if (res.msg == 'success') {
                swal("Success!", "Question number " + number + " has been removed!", "success");
                $('#addNewSubQuestionDiv_' + selector).remove();
                setTimeout(function () {
                    window.location.replace(base_url + 'smartgen/generateExam');
                }, 300);
            }
        }
    });
}


function changeOrder(id, number, new_number) {
    $.ajax({
        url: base_url + 'ExamMode/updateNumberOfQuestion',
        type: 'POST',
        dataType: 'json',
        data: {
            id: id,
            old_number: number,
            question_number: new_number
        },
        success: function (res) {
            if (res.msg == 'success') {
                swal("Success!", "Question number " + number + " has been changed to number " + new_number, "success");
                setTimeout(function () {
                    window.location.replace(base_url + 'smartgen/generateExam');
                }, 100);
            }
        }
    });
}


function checkRefresh() {
    if (document.refreshForm.visited.value == "") {
        // This is a fresh page load
        document.refreshForm.visited.value = "1";
        window.location.href = '<?php echo base_url();?>smartgen/generateWorksheet';
        // You may want to add code here special for
        // fresh page loads
    }
}


function append_div_mathjax() {
    $(".mj_element .question script, .answer script").each(function () {
        console.log($(this).prev().find());

        // $(this).prev().children(":first-child").remove();

        var eleHtml = $(this).parent().children('.MathJax_SVG');
        var element = $(this).html();
        var replace;

        if ($(this).html() == '\\underline{}\\underline{}\\underline{}') {
            eleHtml.html('___');
        }

        if (element.search('\\$') == 1) {
            var num = element.split("$")[1];
            var frame = $(this).prev().attr('id');
            replace = '$' + num;
        }

        if (element.includes('cm^3') == 1) {
            var num = element;
            num = num.replace('cm\^3', 'cm<sup>3</sup>');
            var frame = $(this).prev().attr('id');
            replace = num;
        }

        if (element.includes('m^3') == 1) {
            var num = element;
            num = num.replace('m\^3', 'm<sup>3</sup>');
            var frame = $(this).prev().attr('id');
            replace = num;
        }

        if (element.includes('cm^2') == 1) {
            var num = element;
            num = num.replace('cm\^2', 'cm<sup>2</sup>');
            var frame = $(this).prev().attr('id');
            replace = num;
        }

        if (element.includes('m^2') == 1) {
            var num = element;
            num = num.replace('m\^2', 'm<sup>2</sup>');
            var frame = $(this).prev().attr('id');
            replace = num;
        }

        if (element.search('frac{') == 1) {
            var num = element.replace('\\frac{', '');
            num = num.replace(new RegExp('}', 'g'), '');
            num = num.split('{');
            var frame = $(this).prev().attr('id');
            replace = '<sup>' + num[0] + '</sup>&frasl;<sub>' + num[1] + '</sub>';
        }

        if (element.search('\\angle') == 1) {
            var num = element;

            num = num.replace(new RegExp('\\\\angle', 'g'), '<span style="font-family: Dejavu Sans; ">&#x2220;</span>');
            var frame = $(this).prev().attr('id');
            replace = num;
        }

        if (element.includes('^{\\circ}') == 1) {
            var num = element;
            num = num.replace(new RegExp('([\\^])([{])([\\\\,])circ([}])', 'g'), '<span style="font-family: Dejavu Sans; ">&#176;</span>');
            var frame = $(this).prev().attr('id');
            replace = num;
        }

        if (element.includes('^\\circ') == 1) {
            var num = element;
            var angle = num.split('=')[0];
            angle = angle.replace(new RegExp('\\\\angle', 'g'), '&#x2220;');
            num = num.split('=')[1];
            num = num.replace(new RegExp('([\\^])([\\\\,])circ', 'g'), '&#176;');
            var frame = $(this).prev().attr('id');
            replace = '<span style="font-family: Dejavu Sans; ">' + angle + '=' + num + '</span>';
        }

        $(this).parent().children('#' + frame).html(replace);

    });
}

$('#outputPDF').on('submit', function (e) {
    var defs = '<svg>' + $('#MathJax_SVG_Hidden').next().html() + '</svg>';
    // var new_svg = $('.mj_element').html();

    // $('#pdfOutputString').val(window.btoa(encodeURIComponent(new_svg)));

    var svg = "";
    var index = 1;
    var index_split;
    var index_array;
    $('.question_text_pdf').each(function () {
        var ques_no = $(this).attr('id');
        ques_no = ques_no.split("_");
        ques_no = ques_no[3];

        var text_html = $(this).html();
        
        svg += "<div class='question'><p>" + ques_no + ") " + text_html + "</p></div>";
        var ques_difficulty = $(this).parent().find('#ques_difficulty').val();
        
        if (ques_difficulty == 1) {
            svg += "<br><br>";
        }

        if (ques_difficulty == 2) {
            svg += "<br><br>";
        }

        if (ques_difficulty == 3) {
            svg += "<br><br><br>";
        }

        if (ques_difficulty == 4) {
            svg += "<br><br><br><br>";
        }

        if (ques_difficulty == 5) {
            svg += "<br><br><br><br><br>";
        }

        if (ques_difficulty == 6) {
            svg += "<br><br><br><br><br><br>";
        }

        if (ques_difficulty == 7) {
            svg += "<br><br><br><br><br><br><br>";
        }

        if (ques_difficulty == 8) {
            svg += "<br><br><br><br><br><br><br><br>";
        }

        if (ques_difficulty == 9) {
            svg += "<br><br><br><br><br><br><br><br><br>";
        }

        if (ques_difficulty == 10) {
            svg += "<br><br><br><br><br><br><br><br><br><br>";
        }
        // svg += "<br><br>";
    });


    var ans = "";
    index = 1;
    $('.correctAnswerText').each(function () {
        index_split = $(this).attr('id');
        index_array = index_split.split("_");
        index = index_array[2];

        ans += "<p>" + index + ") " + $(this).html() + "</p>";
    });

    ans = '<div class="answer">' + ans + '</div>';

    // console.log(ans);
    $('#pdfOutputString').val(window.btoa(encodeURIComponent(svg + ans + defs)));
    return true;
});