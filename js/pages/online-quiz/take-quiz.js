var mathKeyboard = $('#mathExpressionKeyboard');
var MQ = MathQuill.getInterface(2);

var currentQuestion = 0;
let hasTimeLapse = false;
let answerId;
let mathSpanId;
let spanTarget;
let mathSpan;

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


function ajaxQuestion(currentQuestion) {
    console.log('currentQuestion: ' + currentQuestion);
    var data = questionList[currentQuestion];

    var splitQueText = data['questionText'].split(" ");
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

    let numOpenEnded = 0;
    $('#quizSection').html('');  // clear quiz section div
    var questionDiv = $('<div class="questionDiv>"</div>');
    // var questionNumber = data['showQuestionNoText'] + ' / ' + numTotalQuestion;
    var questionNumber = data['questionNumber'] + ' / ' + numTotalQuestion;
    var questionArea = '<div class="panel_question_text col-sm-12 col-md-12 col-lg-12">';


    questionId = '<input type="hidden" value="' + data['questionId'] + '" name="current_question_id" id="current_question_id">';

    var instruction = data['questionInstruction'];
    if (instruction.length > 0) {
        for (x = 0; x < instruction.length; x++) {

            if (instruction[x].content_type == 'text') {
                questionArea += instruction[x].header_content;
                questionArea += '<br>';
            } else {
                questionArea += '<img src="' + base_url + 'img/instructionImage/' + instruction[x].header_content + '" draggable="false" class="img-responsive" style="display: block; width: 60%; ">';
                questionArea += '<br>';
            }
        }
    }

    var article = data['questionArticle'];
    if (article.length > 0) {
        for (x = 0; x < article.length; x++) {

            if (article[x].content_type == 'text') {
                questionArea += article[x].header_content;
            } else {
                questionArea += '<img src="' + base_url + 'img/articleImage/' + article[x].header_content + '" draggable="false" class="img-responsive" style="display: block; width: 60%; ">';
            }
        }
    }

    questionType = (data['question_type'] == '0') ? questionType : data['question_type'];

    if (questionType == 5) {
        var answerValue = $('#ques_' + currentQuestion).val();
        var answerValue2 = '';
        var $selectAnswers = '<select class="answer_fitb_' + (data['questionNumber'] - 1) + ' font400 input_select_rounded input_width_oq_150" name="answer_fitb_' + data['questionId'] + '" style="height: 1.9rem !important;">';
        var $listAnswers = '<div class="p-1 mb-10 border2"><ul style="list-style-type:none; padding-left: 0;" class="list_four_column">';
        $selectAnswers += '<option value="">-- answer --</option>';
        let answerOption = data['answerOption'];
        for (let i = 0, l = answerOption.length; i < l; i++) {
            $selectAnswers += '<option value="' + answerOption[i].answer_text + '" ' + ((answerOption[i].answer_text == answerValue) ? 'selected' : '') + '>' + answerOption[i].answer_text + '</option>';
            $listAnswers += '<li>' + answerOption[i].answer_text + '</li>';
        }

        $selectAnswers += '</select>';
        $listAnswers += '</ul></div>';

        var _string = newText;
        var $question = _string.replace(/<p>|<p style="text-align:justify">|<p style="text-align:left">|<\/p>/g, '');
        $question = $question.replace(/<ans>/g, '<div class="start_select_answer input_width_oq_150" style="display: inline;">[___]<ans>');
        $question = $question.replace(/<\/ans>/g, '</ans></span></div>');
        var $array = [];
        $array = $question.split('[___]');
        $questions = "";

        var group_id = data['groupId'];
        var currentNumber = data['questionNumber'];
        var firstGroupIdNum = data['firstGroupIdNum'];

        for ($x = 0; $x < $array.length; $x++) {

            if ($x == ($array.length) - 1) {
                $questions += $array[$x];
            } else {
                if (($x + 1) == group_id) {
                    $questions += $array[$x] + '(' + currentNumber + ') <span class="line_blank font400">' + $selectAnswers + '';
                } else {
                    answerValue2 = $('#ques_' + (firstGroupIdNum - 1)).val();
                    console.log('answerValue2 ' + 'firstGroupIdNum: ' + answerValue2);
                    if (answerValue2 == '' || answerValue2 == undefined) {
                        $questions += $array[$x] + '(' + firstGroupIdNum + ') <span class="line_blank font500">____________ ';
                    } else {
                        $questions += $array[$x] + '<span class="text-danger-active font600">(' + firstGroupIdNum + ')</span> <span class="line_blank line_blank_fitb">' + answerValue2 + ' ';
                    }
                }
            }

            firstGroupIdNum++;

            $questions = $questions.replace(/<ans>.*<\/ans>/, '');

        }

        questionArea += $listAnswers + $questions + questionId + '</div>';

    } else if (questionType == 6) {
        var answerValue = $('#ques_' + currentQuestion).val();
        var answerValue2 = '';
        var group_id = data['groupId'];
        var _string = newText;
        var $question = _string.replace(/<p>|<p style="text-align:justify">|<p style="text-align:left">|<\/p>/g, '');
        $question = $question.replace(/<ans>/g, '<div class="start_input_answer input_width_oq_150" style="display: inline;">[___]<ans>');
        $question = $question.replace(/<\/ans>/g, '</ans></span></div>');
        var $array = [];
        $array = $question.split('[___]');
        $questions = "";

        var currentNumber = data['questionNumber'];
        var firstGroupIdNum = data['firstGroupIdNum'];

        for ($x = 0; $x < $array.length; $x++) {

            if ($x == ($array.length) - 1) {
                $questions += $array[$x];
            } else {
                if (($x + 1) == group_id) {
                    if (answerValue) {
                        $questions += $array[$x] + '(' + currentNumber + ') <span class="line_blank font400"><input type="text" class="answer_fitb_' + (data['questionNumber'] - 1) + ' input_style2_rounded input_width_oq_150 shadow-sm" name="answer_fitb_' + data['questionId'] + '" placeholder="Type answer here" value="' + answerValue + '" style="text-align: center !important;">';
                    } else {
                        $questions += $array[$x] + '(' + currentNumber + ') <span class="line_blank font400"><input type="text" class="answer_fitb_' + (data['questionNumber'] - 1) + ' input_style2_rounded input_width_oq_150 shadow-sm" name="answer_fitb_' + data['questionId'] + '" placeholder="Type answer here" style="text-align: center !important;">';
                    }
                } else {
                    answerValue2 = $('#ques_' + (firstGroupIdNum - 1)).val();
                    if (answerValue2 == '' || answerValue2 == undefined) {
                        $questions += $array[$x] + '(' + firstGroupIdNum + ') <span class="line_blank font500">____________ ';
                    } else {
                        $questions += $array[$x] + '<span class="text-danger-active font600">(' + firstGroupIdNum + ')</span> <span class="line_blank line_blank_fitb">' + answerValue2 + ' ';
                    }
                }

            }

            firstGroupIdNum++;

            $questions = $questions.replace(/<ans>.*<\/ans>/, '');

        }

        questionArea += $questions + questionId + '</div>';

    } else if (questionType == 7) {

        var answerValue = $('#ques_' + currentQuestion).val();
        var answerValue2 = '';
        var $selectAnswers = '<select class="answer_fitb_' + (data['questionNumber'] - 1) + ' font400 input_select_rounded input_width_oq_150" name="answer_fitb_' + data['questionId'] + '" style="height: 1.9rem !important;">';
        $selectAnswers += '<option value="">-- answer --</option>';
        let answerOption = data['answerOptionFITB'];
        for (let i = 0, l = answerOption.length; i < l; i++) {
            $selectAnswers += '<option value="' + answerOption[i].answer_text + '" ' + ((answerOption[i].answer_text == answerValue) ? 'selected' : '') + '>' + answerOption[i].answer_text + '</option>';
        }

        $selectAnswers += '</select>';

        var _string = newText;
        var $question = _string.replace(/<p>|<p style="text-align:justify">|<p style="text-align:left">|<\/p>/g, '');
        $question = $question.replace(/<ans>/g, '<div class="start_select_answer input_width_oq_150" style="display: inline;">[___]<ans>');
        $question = $question.replace(/<\/ans>/g, '</ans></span></div>');
        var $array = [];
        $array = $question.split('[___]');
        $questions = "";

        var group_id = data['groupId'];
        var currentNumber = data['questionNumber'];
        var firstGroupIdNum = data['firstGroupIdNum'];

        for ($x = 0; $x < $array.length; $x++) {

            if ($x == ($array.length) - 1) {
                $questions += $array[$x];
            } else {
                if (($x + 1) == group_id) {
                    $questions += $array[$x] + '(' + currentNumber + ') <span class="line_blank font400">' + $selectAnswers + '';
                } else {
                    answerValue2 = $('#ques_' + (firstGroupIdNum - 1)).val();
                    console.log('answerValue2 ' + 'firstGroupIdNum: ' + answerValue2);
                    if (answerValue2 == '' || answerValue2 == undefined) {
                        $questions += $array[$x] + '(' + firstGroupIdNum + ') <span class="line_blank font500">____________ ';
                    } else {
                        $questions += $array[$x] + '<span class="text-danger-active font600">(' + firstGroupIdNum + ')</span> <span class="line_blank line_blank_fitb">' + answerValue2 + ' ';
                    }
                }
            }

            firstGroupIdNum++;

            $questions = $questions.replace(/<ans>.*<\/ans>/, '');

        }

        questionArea += $questions + questionId + '</div>';

    } else {
        questionArea += newText + questionId + '</div>';
    }

    if (data['questionImg'] != "0") {
        questionArea += '<div class="panel_question_text col-sm-12 col-md-12 col-lg-12"><img src="' + data['questionImageUrl'] + '/questionImage/' + data['questionImg'] + '" class="img-responsive" style="display: block; margin: 0 auto;"></div>';
    }

    if (questionType == 1) {  // mcq
        let answerOption = data['answerOption'];
        let answerOptionHtml = '';
        questionArea += '<div class="panel_question_text col-sm-12 col-md-12 col-lg-12 pt-20"><div class="form-inputGroup">';
        for (let i = 0, l = answerOption.length; i < l; i++) {
            if (answerOption[i].answer_id == $('#ques_' + currentQuestion).val()) {
                questionArea += `<div class="inputGroup inputGroupRadio border1 active">
                                        <input id="radio_`+ i + `" class="answer_mcq" name="answer_mcq" type="radio" value="` + answerOption[i].answer_id + `" checked/>
                                        <label for="radio_`+ i + `">` + answerOption[i].answer_text + `</label>
                                    </div>`;
            } else {
                questionArea += `<div class="inputGroup inputGroupRadio border1">
                                        <input id="radio_`+ i + `" class="answer_mcq" name="answer_mcq" type="radio" value="` + answerOption[i].answer_id + `" />
                                        <label for="radio_`+ i + `">` + answerOption[i].answer_text + `</label>
                                    </div>`;
            }
        }

        questionArea += `</div></div>`;

        answerOptionHtml += '<div style="clear: both;"></div>';
        numOpenEnded++;
        var answerValue = $('#ques_' + currentQuestion).val();

        //************New Added Script Start*/
        answerOptionHtml += (
            '<div class="col-sm-12 col-md-12 col-lg-12"><div id="myscript" style="width: 100%; height: 300px; float:left;">' +
            '<div>' +
            '<nav>' +
            '<div class="button-div">' +
            '<button id="clear" class="nav-btn btn-fab-mini btn-lightBlue" disabled>' +
            '<img src="' + base_url + 'img/img/clear.svg">' +
            '</button>' +
            '<button id="undo" class="nav-btn btn-fab-mini btn-lightBlue" disabled>' +
            '<img src="' + base_url + 'img/img/undo.svg">' +
            '</button>' +
            '<button id="redo" class="nav-btn btn-fab-mini btn-lightBlue" disabled>' +
            '<img src="' + base_url + 'img/img/redo.svg">' +
            '</button>' +
            '<button id="convert" class="nav-btn btn-fab-mini btn-lightBlue" disabled>' +
            '<img src="' + base_url + 'img/img/exchange-arrows.svg">' + '</button>' +
            '<button id="myscriptAdd" class="nav-btn btn-fab-mini btn-lightBlue">+</button>' +
            '<button id="myscriptMinus" class="nav-btn btn-fab-mini btn-lightBlue">-</button>' +
            '</div>' +
            '<div class="spacer"></div>' +
            '<div class="button-div">' +
            '</div>' +
            '</nav>' +
            '<div id="editor" touch-action="none"></div>' +
            '<div id="myscriptResult" touch-action="none" style="width: 25%; float:left;"></div>' +
            '</div>' +
            '</div>' +
            '<div id="svgPreview"></div></div>');
        //************New Added Script End*/

        //var answerDiv = '<div class="answerOptions row form-group"> ' + answerOptionHtml + ' </div><div id="previewImage"></div>';

        var answerDiv = '<div class="answerOptions hideWhenNewQuesGen margin-bottom-custom"> ' + answerOptionHtml + '</div>';

    } else if (questionType == 2) {

        numOpenEnded++;
        var answerValue = $('#ques_' + currentQuestion).val();
        var answerOptionHtml = '';
        var answerButtonNav = '';

        questionArea += '<div class="panel_question_text col-sm-12 col-md-12 col-lg-12 pt-20">';

        if (answerValue) {
            questionArea += (
                '<span class="openEndedAnswer" style="width: 100%; padding: 0.5em" id="span_' + currentQuestion + '">' +
                answerValue +
                '</span>');

        } else {
            questionArea += ('<span class="openEndedAnswer" style="width: 100%; padding: 0.5em" id="span_' + currentQuestion + '"></span>');
        }

        questionArea += '</div>';

        //************New Added Script Start*/
        answerOptionHtml += (
            '<div class="col-sm-12 col-md-12 col-lg-12"><div id="myscript" style="width: 100%; height: 300px; float:left;">' +
            '<div>' +
            '<nav>' +
            '<div class="button-div">' +
            '<button id="clear" class="nav-btn btn-fab-mini btn-lightBlue" disabled>' +
            '<img src="' + base_url + 'img/img/clear.svg">' +
            '</button>' +
            '<button id="undo" class="nav-btn btn-fab-mini btn-lightBlue" disabled>' +
            '<img src="' + base_url + 'img/img/undo.svg">' +
            '</button>' +
            '<button id="redo" class="nav-btn btn-fab-mini btn-lightBlue" disabled>' +
            '<img src="' + base_url + 'img/img/redo.svg">' +
            '</button>' +
            '<button id="convert" class="nav-btn btn-fab-mini btn-lightBlue" disabled>' +
            '<img src="' + base_url + 'img/img/exchange-arrows.svg">' + '</button>' +
            '<button id="myscriptAdd" class="nav-btn btn-fab-mini btn-lightBlue">+</button>' +
            '<button id="myscriptMinus" class="nav-btn btn-fab-mini btn-lightBlue">-</button>' +
            '</div>' +
            '<div class="spacer"></div>' +
            '<div class="button-div">' +
            '</div>' +
            '</nav>' +
            '<div id="editor" touch-action="none"></div>' +
            '<div id="myscriptResult" touch-action="none" style="width: 25%; float:left;"></div>' +
            '</div>' +
            '</div>' +
            '<div id="svgPreview"></div></div>');
        //************New Added Script End*/

        var answerDiv = '<div class="answerOptions row form-group"> ' + answerOptionHtml + ' </div><div id="previewImage"></div>';
    } else if (questionType == 3) {
        let answerOptionHtml = '';

        questionArea += '<div class="panel_question_text col-sm-12 col-md-12 col-lg-12 pt-20">';

        if ($('#ques_' + currentQuestion).val() == 'True') {
            var activeTrue = 'active';
            var checkedTrue = 'checked';

            var activeFalse = '';
            var checkedFalse = '';
        } else if ($('#ques_' + currentQuestion).val() == 'False') {
            var activeTrue = '';
            var checkedTrue = '';

            var activeFalse = 'active';
            var checkedFalse = 'checked';
        } else {
            var activeTrue = '';
            var checkedTrue = '';

            var activeFalse = '';
            var checkedFalse = '';
        }

        questionArea += `<div class="inputGroup inputGroupRadio border1 ` + activeTrue + `">
                            <input id="answer_tf_1" class="answer_tf" name="answer_tf" type="radio" value="True" `+ checkedTrue + `/>
                            <label for="answer_tf_1">True</label>
                        </div>`;
        questionArea += `<div class="inputGroup inputGroupRadio border1 ` + activeFalse + `">
                            <input id="answer_tf_2" class="answer_tf" name="answer_tf" type="radio" value="False" `+ checkedFalse + `/>
                            <label for="answer_tf_2">False</label>
                        </div>`;


        questionArea += '</div>';

        answerOptionHtml += '<div style="clear: both;"></div>';
        numOpenEnded++;
        var answerValue = $('#ques_' + currentQuestion).val();

        //************New Added Script Start*/
        answerOptionHtml += (
            '<div class="col-sm-12 col-md-12 col-lg-12"><div id="myscript" style="width: 100%; height: 300px; float:left;">' +
            '<div>' +
            '<nav>' +
            '<div class="button-div">' +
            '<button id="clear" class="nav-btn btn-fab-mini btn-lightBlue" disabled>' +
            '<img src="' + base_url + 'img/img/clear.svg">' +
            '</button>' +
            '<button id="undo" class="nav-btn btn-fab-mini btn-lightBlue" disabled>' +
            '<img src="' + base_url + 'img/img/undo.svg">' +
            '</button>' +
            '<button id="redo" class="nav-btn btn-fab-mini btn-lightBlue" disabled>' +
            '<img src="' + base_url + 'img/img/redo.svg">' +
            '</button>' +
            '<button id="convert" class="nav-btn btn-fab-mini btn-lightBlue" disabled>' +
            '<img src="' + base_url + 'img/img/exchange-arrows.svg">' + '</button>' +
            '<button id="myscriptAdd" class="nav-btn btn-fab-mini btn-lightBlue">+</button>' +
            '<button id="myscriptMinus" class="nav-btn btn-fab-mini btn-lightBlue">-</button>' +
            '</div>' +
            '<div class="spacer"></div>' +
            '<div class="button-div">' +
            '</div>' +
            '</nav>' +
            '<div id="editor" touch-action="none"></div>' +
            '<div id="myscriptResult" touch-action="none" style="width: 25%; float:left;"></div>' +
            '</div>' +
            '</div>' +
            '<div id="svgPreview"></div></div>');
        //************New Added Script End*/

        //var answerDiv = '<div class="answerOptions row form-group"> ' + answerOptionHtml + ' </div><div id="previewImage"></div>';

        var answerDiv = '<div class="answerOptions hideWhenNewQuesGen margin-bottom-custom"> ' + answerOptionHtml + '</div>';
    } else if (questionType == 5) {
        let answerOptionHtml = '';

        answerOptionHtml += '<div style="clear: both;"></div>';
        numOpenEnded++;
        var answerValue = $('#ques_' + currentQuestion).val();

        //************New Added Script Start*/
        answerOptionHtml += (
            '<div class="col-sm-12 col-md-12 col-lg-12"><div id="myscript" style="width: 100%; height: 300px; float:left;">' +
            '<div>' +
            '<nav>' +
            '<div class="button-div">' +
            '<button id="clear" class="nav-btn btn-fab-mini btn-lightBlue" disabled>' +
            '<img src="' + base_url + 'img/img/clear.svg">' +
            '</button>' +
            '<button id="undo" class="nav-btn btn-fab-mini btn-lightBlue" disabled>' +
            '<img src="' + base_url + 'img/img/undo.svg">' +
            '</button>' +
            '<button id="redo" class="nav-btn btn-fab-mini btn-lightBlue" disabled>' +
            '<img src="' + base_url + 'img/img/redo.svg">' +
            '</button>' +
            '<button id="convert" class="nav-btn btn-fab-mini btn-lightBlue" disabled>' +
            '<img src="' + base_url + 'img/img/exchange-arrows.svg">' + '</button>' +
            '<button id="myscriptAdd" class="nav-btn btn-fab-mini btn-lightBlue">+</button>' +
            '<button id="myscriptMinus" class="nav-btn btn-fab-mini btn-lightBlue">-</button>' +
            '</div>' +
            '<div class="spacer"></div>' +
            '<div class="button-div">' +
            '</div>' +
            '</nav>' +
            '<div id="editor" touch-action="none"></div>' +
            '<div id="myscriptResult" touch-action="none" style="width: 25%; float:left;"></div>' +
            '</div>' +
            '</div>' +
            '<div id="svgPreview"></div></div>');
        //************New Added Script End*/

        //var answerDiv = '<div class="answerOptions row form-group"> ' + answerOptionHtml + ' </div><div id="previewImage"></div>';

        var answerDiv = '<div class="answerOptions hideWhenNewQuesGen margin-bottom-custom"> ' + answerOptionHtml + '</div>';
    } else if (questionType == 6) {
        let answerOptionHtml = '';

        answerOptionHtml += '<div style="clear: both;"></div>';
        numOpenEnded++;
        var answerValue = $('#ques_' + currentQuestion).val();

        //************New Added Script Start*/
        answerOptionHtml += (
            '<div class="col-sm-12 col-md-12 col-lg-12"><div id="myscript" style="width: 100%; height: 300px; float:left;">' +
            '<div>' +
            '<nav>' +
            '<div class="button-div">' +
            '<button id="clear" class="nav-btn btn-fab-mini btn-lightBlue" disabled>' +
            '<img src="' + base_url + 'img/img/clear.svg">' +
            '</button>' +
            '<button id="undo" class="nav-btn btn-fab-mini btn-lightBlue" disabled>' +
            '<img src="' + base_url + 'img/img/undo.svg">' +
            '</button>' +
            '<button id="redo" class="nav-btn btn-fab-mini btn-lightBlue" disabled>' +
            '<img src="' + base_url + 'img/img/redo.svg">' +
            '</button>' +
            '<button id="convert" class="nav-btn btn-fab-mini btn-lightBlue" disabled>' +
            '<img src="' + base_url + 'img/img/exchange-arrows.svg">' + '</button>' +
            '<button id="myscriptAdd" class="nav-btn btn-fab-mini btn-lightBlue">+</button>' +
            '<button id="myscriptMinus" class="nav-btn btn-fab-mini btn-lightBlue">-</button>' +
            '</div>' +
            '<div class="spacer"></div>' +
            '<div class="button-div">' +
            '</div>' +
            '</nav>' +
            '<div id="editor" touch-action="none"></div>' +
            '<div id="myscriptResult" touch-action="none" style="width: 25%; float:left;"></div>' +
            '</div>' +
            '</div>' +
            '<div id="svgPreview"></div></div>');
        //************New Added Script End*/

        //var answerDiv = '<div class="answerOptions row form-group"> ' + answerOptionHtml + ' </div><div id="previewImage"></div>';

        var answerDiv = '<div class="answerOptions hideWhenNewQuesGen margin-bottom-custom"> ' + answerOptionHtml + '</div>';
    } else if (questionType == 7) {
        let answerOptionHtml = '';

        answerOptionHtml += '<div style="clear: both;"></div>';
        numOpenEnded++;
        var answerValue = $('#ques_' + currentQuestion).val();

        //************New Added Script Start*/
        answerOptionHtml += (
            '<div class="col-sm-12 col-md-12 col-lg-12"><div id="myscript" style="width: 100%; height: 300px; float:left;">' +
            '<div>' +
            '<nav>' +
            '<div class="button-div">' +
            '<button id="clear" class="nav-btn btn-fab-mini btn-lightBlue" disabled>' +
            '<img src="' + base_url + 'img/img/clear.svg">' +
            '</button>' +
            '<button id="undo" class="nav-btn btn-fab-mini btn-lightBlue" disabled>' +
            '<img src="' + base_url + 'img/img/undo.svg">' +
            '</button>' +
            '<button id="redo" class="nav-btn btn-fab-mini btn-lightBlue" disabled>' +
            '<img src="' + base_url + 'img/img/redo.svg">' +
            '</button>' +
            '<button id="convert" class="nav-btn btn-fab-mini btn-lightBlue" disabled>' +
            '<img src="' + base_url + 'img/img/exchange-arrows.svg">' + '</button>' +
            '<button id="myscriptAdd" class="nav-btn btn-fab-mini btn-lightBlue">+</button>' +
            '<button id="myscriptMinus" class="nav-btn btn-fab-mini btn-lightBlue">-</button>' +
            '</div>' +
            '<div class="spacer"></div>' +
            '<div class="button-div">' +
            '</div>' +
            '</nav>' +
            '<div id="editor" touch-action="none"></div>' +
            '<div id="myscriptResult" touch-action="none" style="width: 25%; float:left;"></div>' +
            '</div>' +
            '</div>' +
            '<div id="svgPreview"></div></div>');
        //************New Added Script End*/

        //var answerDiv = '<div class="answerOptions row form-group"> ' + answerOptionHtml + ' </div><div id="previewImage"></div>';

        var answerDiv = '<div class="answerOptions hideWhenNewQuesGen margin-bottom-custom"> ' + answerOptionHtml + '</div>';
    } else if (questionType == 8) {
        let answerOption = data['answerOption'];
        let answerOptionHtml = '';
        questionArea += '<div class="panel_question_text col-sm-12 col-md-12 col-lg-12 pt-20"><div class="form-inputGroup">';
        for (let i = 0, l = answerOption.length; i < l; i++) {
            if ($('#ques_' + currentQuestion).val().indexOf(answerOption[i].answer_id) >= 0) {
                questionArea += `<div class="inputGroup inputGroupCheckbox shadow-1 active">
                                        <input id="checkbox_`+ i + `" class="answer_mcq" name="answer_mcq[]" type="checkbox" value="` + answerOption[i].answer_id + `" checked>
                                        <label for="checkbox_`+ i + `">` + answerOption[i].answer_text + `</label>
                                    </div>`;
            } else {
                questionArea += `<div class="inputGroup inputGroupCheckbox shadow-1">
                                        <input id="checkbox_`+ i + `" class="answer_mcq" name="answer_mcq[]" type="checkbox" value="` + answerOption[i].answer_id + `" >
                                        <label for="checkbox_`+ i + `">` + answerOption[i].answer_text + `</label>
                                    </div>`;
            }
        }

        questionArea += `</div></div>`;

        var answerValue = $('#ques_' + currentQuestion).val();

        //************New Added Script Start*/
        answerOptionHtml += (
            '<div class="col-sm-12 col-md-12 col-lg-12"><div id="myscript" style="width: 100%; height: 300px; float:left;">' +
            '<div>' +
            '<nav>' +
            '<div class="button-div">' +
            '<button id="clear" class="nav-btn btn-fab-mini btn-lightBlue" disabled>' +
            '<img src="' + base_url + 'img/img/clear.svg">' +
            '</button>' +
            '<button id="undo" class="nav-btn btn-fab-mini btn-lightBlue" disabled>' +
            '<img src="' + base_url + 'img/img/undo.svg">' +
            '</button>' +
            '<button id="redo" class="nav-btn btn-fab-mini btn-lightBlue" disabled>' +
            '<img src="' + base_url + 'img/img/redo.svg">' +
            '</button>' +
            '<button id="convert" class="nav-btn btn-fab-mini btn-lightBlue" disabled>' +
            '<img src="' + base_url + 'img/img/exchange-arrows.svg">' + '</button>' +
            '<button id="myscriptAdd" class="nav-btn btn-fab-mini btn-lightBlue">+</button>' +
            '<button id="myscriptMinus" class="nav-btn btn-fab-mini btn-lightBlue">-</button>' +
            '</div>' +
            '<div class="spacer"></div>' +
            '<div class="button-div">' +
            '</div>' +
            '</nav>' +
            '<div id="editor" touch-action="none"></div>' +
            '<div id="myscriptResult" touch-action="none" style="width: 25%; float:left;"></div>' +
            '</div>' +
            '</div>' +
            '<div id="svgPreview"></div></div>');
        //************New Added Script End*/

        //var answerDiv = '<div class="answerOptions row form-group"> ' + answerOptionHtml + ' </div><div id="previewImage"></div>';

        var answerDiv = '<div class="answerOptions hideWhenNewQuesGen margin-bottom-custom"> ' + answerOptionHtml + '</div>';

    } else if (questionType == 9) {
        var answerValue = $('#ques_' + currentQuestion).val();
        var answerOptionHtml = '';

        if (answerValue) {
            questionArea += (
                '<div class="panel_question_text col-sm-12 col-md-12 col-lg-12">' +
                '<textarea name="input_cktext_' + data['questionId'] + '" id="input_cktext_' + data['questionId'] + '" spellcheck="false">' + answerValue + '</textarea>' +
                '<textarea name="input_text_' + data['questionId'] + '" id="input_text_' + data['questionId'] + '" style="display: none;">' + answerValue + '</textarea>' +
                '</div>');
        } else {
            questionArea += (
                '<div class="panel_question_text col-sm-12 col-md-12 col-lg-12">' +
                '<textarea name="input_cktext_' + data['questionId'] + '" id="input_cktext_' + data['questionId'] + '" spellcheck="false"></textarea>' +
                '<textarea name="input_text_' + data['questionId'] + '" id="input_text_' + data['questionId'] + '" style="display: none;"></textarea>' +
                '</div>');
        }

        //************New Added Script Start*/
        answerOptionHtml += (
            '<div class="col-sm-12 col-md-12 col-lg-12"><div id="myscript" style="width: 100%; height: 300px; float:left;">' +
            '<div>' +
            '<nav>' +
            '<div class="button-div">' +
            '<button id="clear" class="nav-btn btn-fab-mini btn-lightBlue" disabled>' +
            '<img src="' + base_url + 'img/img/clear.svg">' +
            '</button>' +
            '<button id="undo" class="nav-btn btn-fab-mini btn-lightBlue" disabled>' +
            '<img src="' + base_url + 'img/img/undo.svg">' +
            '</button>' +
            '<button id="redo" class="nav-btn btn-fab-mini btn-lightBlue" disabled>' +
            '<img src="' + base_url + 'img/img/redo.svg">' +
            '</button>' +
            '<button id="convert" class="nav-btn btn-fab-mini btn-lightBlue" disabled>' +
            '<img src="' + base_url + 'img/img/exchange-arrows.svg">' + '</button>' +
            '<button id="myscriptAdd" class="nav-btn btn-fab-mini btn-lightBlue">+</button>' +
            '<button id="myscriptMinus" class="nav-btn btn-fab-mini btn-lightBlue">-</button>' +
            '</div>' +
            '<div class="spacer"></div>' +
            '<div class="button-div">' +
            '</div>' +
            '</nav>' +
            '<div id="editor" touch-action="none"></div>' +
            '<div id="myscriptResult" touch-action="none" style="width: 25%; float:left;"></div>' +
            '</div>' +
            '</div>' +
            '<div id="svgPreview"></div></div>');
        //************New Added Script End*/

        var answerDiv = '<div class="answerOptions row form-group"> ' + answerOptionHtml + ' </div><div id="previewImage"></div>';
    }

    answerButtonNav = `<div class="panel_question_text col-sm-12 col-md-12 col-lg-12 text-right pt-10">            
            <a id="myscriptExpand" class="btn btn-icon btn-rounded btn-default btn-w-150 my-2 showScript btn-no-margin-top">
                <i class="batch-icon-compose-3"></i>Writing Pad</a> `
        + (data['questionImg'] != "0" ? '<a id="myscriptQuestionExpand" class="btn btn-icon btn-rounded btn-default btn-w-150 my-2 showScript btn-no-margin-top"><i class="batch-icon-image"></i>Question Image</a>' : '') + `
            <input type="hidden" value="` + data['questionImg'] + `" id="question_image" name="question_image">            
            <label id="imgUploadBtn" for="imgUpload" class="btn btn-icon btn-rounded btn-default btn-w-150 my-2 btn-no-margin-top"><i class="batch-icon-cloud-upload"></i>` +
        (questionList[currentQuestion]["hasUpload"] ? 'Upload Success' : 'Upload Image') + `</label>
            <input type="file" id="imgUpload" name="imgUpload" data-target="` + data['questionId'] + `_` + tutorId + `_` + quizId + `" accept="image/*"/></div>
    `;


    // answerButtonNav = `<div class="col-sm-12 col-md-12 col-lg-12 text-right">
    //         <a class="btn btn-icon-o radius100 btn-bookmark my-2 btn-no-margin-top">
    //             <i class="batch-icon-paper-roll-ripped"></i></a>

    //         <a id="myscriptExpand" class="btn btn-icon-o radius100 btn-custom my-2 showScript btn-no-margin-top btn-selectedAnswer">
    //             <i class="batch-icon-compose-3"></i></a> `

    //     + (data['questionImg'] != "0" ? '<a id="myscriptQuestionExpand" class="btn btn-icon-o radius100 btn-default my-2 showScript btn-no-margin-top"><i class="batch-icon-image"></i></a>' : '') + `
    //         <input type="hidden" value="` + data['questionImg'] + `" id="question_image" name="question_image">            
    //         <label id="imgUploadBtn" for="imgUpload" class="btn btn-icon-o radius100 my-2 btn-no-margin-top btn-danger"><i class="batch-icon-cloud-upload"></i> ` +
    //     (questionList[currentQuestion]["hasUpload"] ? '' : '') + `</label>
    //         <input type="file" id="imgUpload" name="imgUpload" data-target="` + data['questionId'] + `_` + tutorId + `_` + quizId + `" accept="image/*"/>
    //         <a href="` + currentQuestion + `" class="btn btn-icon-o radius100 btn-custom my-2 answerOpenEndedQuestion btn-no-margin-top">
    //         <i class="batch-icon-stiffy"></i></a>
    // </div>`;


    questionArea += answerButtonNav;

    $('#quizSection').append(questionArea);
    $('.inputDrawing').html(answerDiv);

    $('.question-number').html(questionNumber);

    // $('.panel_answer_button').html(answerButtonNav);


    if (questionType == 9) {

        var text_input = $('#input_text_' + data['questionId']);

        var editor = CKEDITOR.replace('input_cktext_' + data["questionId"] + '', {
            height: 100,
            toolbar: [
                { name: 'basicstyles', groups: ['basicstyles', 'cleanup'], items: ['Bold', 'Italic', 'Underline', 'Strike', 'Subscript', 'Superscript', '-', 'RemoveFormat'] },
                { name: 'paragraph', groups: ['list', 'indent', 'blocks', 'align', 'bidi'], items: ['NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'Blockquote', 'CreateDiv', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock', '-', 'BidiLtr', 'BidiRtl', 'Language'] }
            ]
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

    //************New Added Script Start*/
    if (true) { // questionType == 2){ <<< Writing Pad for Non-MCQ too by Cahyono
        //hide myscript
        $("#myscript").hide();
        activateMyScript(currentQuestion);
        //listen to "show writing pad button"
        $("#myscriptExpand").on('click', function () {
            var svg_answer = $('#svg_' + currentQuestion).val();
            // alert(svg_answer);
            // if(svg_answer=="") {
            $("#myscript").show();

            //import answer
            //*****Variable for handwriting data to be Defined by Justin */
            //import data back to handwriting script
            var editorElement = document.getElementById('editor');
            editorElement.editor.clear();

            editorElement.style.backgroundImage = "url('<?php echo base_url() ?>img/img/bg_grey.png')";
            editorElement.style.backgroundRepeat = "repeat";
            editorElement.style.backgroundSize = "unset";

            editorElement.editor.clear();
            if (questionList[currentQuestion]['OCRrecorded']) {
                editorElement.editor.import_(questionList[currentQuestion]['OCRrecorded']["application/vnd.myscript.jiix"], "application/vnd.myscript.jiix");
            }
            $('#save_to').val('svg');
            $('.loader').css('display', 'none');
            if ($("#myscript").is(":visible")) {
                if (questionList[currentQuestion]['multiply']) {
                    updateHeight();
                }
                resizeEditor();
                editorElement.addEventListener('loaded', function () {
                    resizeEditor();
                });
            }

        });

        $("#myscriptQuestionExpand").on('click', function () {
            // var svg_answer = $('#svg_' + currentQuestion).val();
            var question_image = $('#question_image').val();
            var OCRrecorded = document.getElementById('ocr_question_' + currentQuestion).value;
            // if(svg_answer=="") {
            $("#myscript").show();

            //import answer
            //*****Variable for handwriting data to be Defined by Justin */
            //import data back to handwriting script
            var editorElement = document.getElementById('editor');
            editorElement.editor.clear();

            var bg_question = 'https://smartjen.com/img/questionImage/' + question_image;
            editorElement.style.backgroundImage = "url(' " + bg_question + " ')";
            editorElement.style.backgroundRepeat = "no-repeat";
            editorElement.style.backgroundSize = "contain";
            editorElement.editor.clear();
            //editorElement.editor.import_(OCRrecorded,"application/vnd.myscript.jiix");
            if (questionList[currentQuestion]['OCRrecorded_question']) {
                editorElement.editor.import_(questionList[currentQuestion]['OCRrecorded_question']["application/vnd.myscript.jiix"], "application/vnd.myscript.jiix");
            }
            // $('#svg_tutor_bg').val(bg_uploaded);
            $('#save_to').val('svg_question');
            $('.loader').css('display', 'none');
            // if(questionList[currentQuestion]['OCR_question']){
            // 	editorElement.editor.import_(questionList[currentQuestion]['OCR_question']["application/vnd.myscript.jiix"],"application/vnd.myscript.jiix");
            // }

            if ($("#myscript").is(":visible")) {
                if (questionList[currentQuestion]['multiply']) {
                    updateHeight();
                }
                resizeEditor();
                editorElement.addEventListener('loaded', function () {
                    resizeEditor();
                });
            }

        });

        function bindingFunction() {
            document.getElementById('changeSvg').onclick = function () {
                $("#myscript").toggle();
                var editorElement = document.getElementById('editor');
                if (questionList[currentQuestion]['OCRrecorded']) {
                    editorElement.editor.import_(questionList[currentQuestion]['OCRrecorded']["application/vnd.myscript.jiix"], "application/vnd.myscript.jiix");
                }
                if ($("#myscript").is(":visible")) {
                    if (questionList[currentQuestion]['multiply']) {
                        updateHeight();
                    }
                    resizeEditor();
                    editorElement.addEventListener('loaded', function () {
                        resizeEditor();
                    });
                }
                var svgPreviewElement = document.getElementById('svgPreview');
                var div_button = '<div style="padding:10px 3px;"><button id="cancelSvg" class="btn btn-custom btn-no-margin-top btn-danger">Cancel</button></div>';
                svgPreviewElement.innerHTML = div_button;
                bindingFunction2();
            }
        }

        function bindingFunction2() {
            document.getElementById('cancelSvg').onclick = function () {
                var svgPreviewElement = document.getElementById('svgPreview');
                svgPreviewElement.innerHTML = '';
                $("#myscript").hide();
            }
        }

        function updateHeight() {
            var multiply = questionList[currentQuestion]['multiply'];
            var height = $('#myscript').height();
            var editorHeight = $('#editor').height();
            $('#myscript').height(height + questionList[currentQuestion]['multiply'] * 300);
            $('#editor').height(editorHeight + questionList[currentQuestion]['multiply'] * 300);
        }

        $('#myscriptAdd').on('click', function () {
            var myscriptEditor = document.getElementById('myscript');
            var height = $('#myscript').height();
            var editorHeight = $('#editor').height();
            height += 300;
            editorHeight += 300;
            $('#myscript').height(height);
            $('#editor').height(editorHeight);
            if (questionList[currentQuestion]['multiply']) {
                questionList[currentQuestion]['multiply']++;
            } else {
                questionList[currentQuestion]['multiply'] = 1;
            }
            $('#ocr_multiLine_' + currentQuestion).val(questionList[currentQuestion]['multiply']);
            resizeEditor();
        });
        $('#myscriptMinus').on('click', function () {
            var myscriptEditor = document.getElementById('myscript');
            var height = $('#myscript').height();
            var editorHeight = $('#editor').height();
            height -= 300;
            editorHeight -= 300;

            if (questionList[currentQuestion]['multiply'] >= 1) {
                questionList[currentQuestion]['multiply']--;
                $('#myscript').height(height);
                $('#editor').height(editorHeight);
                $('#ocr_multiLine_' + currentQuestion).val(questionList[currentQuestion]['multiply']);
            }
            resizeEditor();
        });
    }

    function resizeEditor() {
        var editorElement = document.getElementById('editor');
        window.setTimeout(function () {
            editorElement.editor.resize();
        }, 500);
    }
    //************New Added Script End*/

    if (numOpenEnded > 0) {
        $('#mathExpressionBtn').show();
        $('#help-text').show();
        $('.openEndedAnswer').each(function (index) {
            let span_id = $(this).attr('id');
            if (index == 0) {
                $('#mathTarget').val(span_id);
            }
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

        //New Added Script By Kok Liang on 21-12-2019

        $('#imgUpload').on('change', function () {
            var file_data = $('#imgUpload').prop('files')[0];

            //check if picture is image
            if (!checkFileExt(file_data)) {
                alert('Only Image is allowed')
            } else {
                //resize data
                _previewImage(file_data, 500).then(function (result) {
                    $('#img_' + currentQuestion).val(result.split(',')[1]);
                    $('#imgUploadBtn').text("");
                    $('#imgUploadBtn').append("<i class='batch-icon-cloud-upload'></i>Upload Success");
                    questionList[currentQuestion]["hasUpload"] = true;
                });
            }

        });
    } else {
        $('#mathExpressionBtn').hide();
        $('#help-text').hide();
    }

    // $('.panel_answer_button').removeClass('d_none');
    $('.title-header').html('Quiz name : &nbsp;' + quizName);
    $('.showWhenQuizStart').removeClass("showWhenQuizStart");
    $('.currentQuestion').removeClass('currentQuestion');
    $('.bottom-fixed').removeClass('d_none');
    $('#quesNo_' + currentQuestion).addClass("currentQuestion");

    var check = $('#quesNo_' + currentQuestion).attr('class').toString();
    if (check.indexOf('bg-bookmark') >= 0) {
        $('.btnBookmark').addClass('active');
    } else {
        $('.btnBookmark').removeClass('active');
    }

    MathJax.Hub.Typeset();
}


$(document).ready(function () {


    toastr.options.timeOut = "1000";
    var ajaxUrl = base_url + 'onlinequiz/getQuizQuestion/';

    let isTimerToggle = false;
    // $('body').bind('cut copy paste', function(e){
    // 	e.preventDefault();
    // });

    // $('body').on('contextmenu', function(e){
    // 	e.preventDefault();
    // });
    $(".timer").hide();

    if (typeof document.onselectstart != "undefined") {
        document.onselectstart = new Function("return false");
    } else {
        document.onmousedown = new Function("return false");
        document.onmouseup = new Function("return false");
    }


    $("body").keydown(function (e) {
        if (e.keyCode == 37) { // left
            $('#prevQuestion').click();
        }
        else if (e.keyCode == 39) { // right
            $('#nextQuestion').click();
        }
    });


    $('#startQuiz').on('click', function () {
        $('.hideWhenQuizStart').fadeOut("slow", function () {
            $(this).remove();

            //start quiz by displaying first question and timer
            ajaxQuestion(currentQuestion);

        });
        var data = questionList[currentQuestion];
        var questionId = data['questionId'];

        answer_id = '-';
        logs_in(answer_id, currentQuestion, questionId);

        //start to count the time
        registerStartTime();
    });

    function registerStartTime() {
        let startTime = new Date().getTime();
        let duration = quizTime * 60 * 1000;
        // let isTimerToggle = false;
        if (duration == 0) {
            return;
        }
        let endTime = startTime + duration;
        window.localStorage.setItem("startTime", startTime);
        window.localStorage.setItem("endTime", endTime);
        $(".timer").show();
        $(".timer").css('visibility', 'visible');
        $(".timer").mouseenter(function () {
            $('.timer-content').removeClass("timer-hide");
        }).mouseleave(function () {
            if (!isTimerToggle) {
                $('.timer-content').addClass("timer-hide");
            }
        });
        $(".timer").click(function () {
            isTimerToggle = true;
            showTimerTemporary();
        });

        showTimerTemporary();
        startCountDown();
        updateTimer(duration);
    }

    function showTimerTemporary() {
        $('.timer-content').removeClass("timer-hide");
        window.setTimeout(function () {
            isTimerToggle = false;
            $('.timer-content').addClass("timer-hide");
        }, 3000);
    }

    function startCountDown() {
        window.setTimeout(function () {
            let endTime = window.localStorage.getItem('endTime');
            let remaining = endTime - new Date().getTime();
            if (hasTimeLapse) {
                //user has submitted before end time
            } else if (new Date().getTime() > endTime) {
                //time's up
                $('.submitQuiz').click();
                $('.submitQuiz').submit();
            } else {
                startCountDown();
                //prompt user if less than 5 minutes
                let min = Math.floor(remaining / 1000 / 60 - Math.floor(remaining / 1000 / 60 / 60) * 60) % 60;
                if (min <= 10 && min % 5 == 0) {
                    showTimerTemporary();
                }
            }
            updateTimer(remaining);
        }, 1000);
    }

    function updateTimer(remainingTime) {
        let time = "";
        let sec = (Math.round(remainingTime / 1000) - Math.floor(remainingTime / 1000 / 60) * 60) % 60;
        let min = Math.floor(remainingTime / 1000 / 60 - Math.floor(remainingTime / 1000 / 60 / 60) * 60) % 60;
        let hr = Math.floor(remainingTime / 1000 / 60 / 60 - Math.floor(remainingTime / 1000 / 60 / 60 / 24) * 24);
        time = (hr < 10 ? "0" + hr : hr) + ":" +
            (min < 10 ? "0" + min : min) + ":" +
            (sec < 10 ? "0" + sec : sec);

        $("#remainingTime").html(time);
    }

    $(document).on('click', '#prevQuestion', function (e) {
        e.preventDefault();
        var answerId = $('.btn.btn-custom.answerQuestion.btn-selectedAnswer').attr('href');
        mathSpanId = $('.openEndedAnswer').attr('id');

        if (answerId) {
            answer_id = $('.btn.btn-custom.answerQuestion.btn-selectedAnswer').text();
        } else {
            answer_id = 'NULL';
        }

        if (mathSpanId) {
            spanTarget = document.getElementById(mathSpanId);
            mathSpan = MQ(spanTarget);
            if (mathSpan) {
                answer_open = mathSpan.latex();
                answer_id = answer_open;
            }
        }

        if (currentQuestion != 0) {
            currentQuestion--;
            ajaxQuestion(currentQuestion);
            prevQuestion = currentQuestion - 1;
            var data = questionList[prevQuestion];
            var questionId = data['questionId'];
            logs_in(answer_id, currentQuestion, questionId);
        }
    });

    $(document).on('click', '#nextQuestion', function (e) {
        e.preventDefault();
        var answerId = $('.btn.btn-custom.answerQuestion.btn-selectedAnswer').attr('href');
        mathSpanId = $('.openEndedAnswer').attr('id');

        if (answerId) {
            answer_id = $('.btn.btn-custom.answerQuestion.btn-selectedAnswer').text();
        } else {
            answer_id = 'NULL';
        }

        if (mathSpanId) {
            spanTarget = document.getElementById(mathSpanId);
            mathSpan = MQ(spanTarget);
            if (mathSpan) {
                answer_open = mathSpan.latex();
                answer_id = answer_open;
            }
        }

        if (currentQuestion != totalQuestion - 1) {
            currentQuestion++;
            ajaxQuestion(currentQuestion);
            prevQuestion = currentQuestion - 1;
            var data = questionList[prevQuestion];
            var questionId = data['questionId'];
            logs_in(answer_id, currentQuestion, questionId);
        }
    });

    $(document).on('click', '.btnPrevious', function () {
        $('#prevQuestion').click();
    });


    $(document).on('click', '.btnBookmark', function (e) {
        e.preventDefault();
        var check = $(this).attr('class').toString();
        var clickedStr = ($('a.currentQuestion').attr('id')).split("_");
        var quesNum = clickedStr[1];
        if (check.indexOf('active') >= 0) {
            $(this).removeClass('active');
            $('#quesNo_' + quesNum).removeClass('bg-bookmark');
        } else {
            $(this).addClass('active');
            $('#quesNo_' + quesNum).addClass('bg-bookmark');
        }

    });


    $(document).on('click', '.selectQuestion', function (e) {
        e.preventDefault();

        currentQuestion = parseInt($(this).attr('href'));

        var questionId = $('#current_question_id').val();

        var answerId = $('.btn.btn-custom.answerQuestion.btn-selectedAnswer').attr('href');

        mathSpanId = $('.openEndedAnswer').attr('id');

        if (answerId) {
            answer_id = $('.btn.btn-custom.answerQuestion.btn-selectedAnswer').text();
        } else {
            answer_id = 'NULL';
        }

        if (mathSpanId) {
            spanTarget = document.getElementById(mathSpanId);
            mathSpan = MQ(spanTarget);
            if (mathSpan) {
                answer_open = mathSpan.latex();
                answer_id = answer_open;
            }
        }

        ajaxQuestion(currentQuestion);
        logs_in(answer_id, currentQuestion, questionId);
    });


    $(document).on('click', '.inputGroupRadio', function (e) {
        e.preventDefault();
        $('.inputGroupRadio').removeClass('active');
        $(this).addClass('active');
        $(this).find('input').prop('checked', true);
    });

    $(document).on('click', '.answer_mcq', function () {
        if ($(this).is(':checked')) {
            $(this).parent().addClass('active');
        } else {
            $(this).parent().removeClass('active');
        }
    });


    $(document).on('click', '.btnSaveContinue', function (e) {
        e.preventDefault();

        if (questionType == 1) { // mcq single choice

            var clickedAnswer = ($('a.currentQuestion').attr('id')).split("_");
            var quesNum = clickedAnswer[1];
            var answerId = '';
            answerId = $('.answer_mcq:checked').val();

            if (answerId == '' || answerId == undefined) {
                toastr.error('No Answer');
            } else {

                $('#ques_' + quesNum).val(answerId);

                toastr.success('Answer saved');

                $('#quesNo_' + currentQuestion).addClass('answeredQuestion');

                questionId = $('#current_question_id').val();
                answerText = $('.answer_mcq:checked').next().text();
                var svg_val = document.getElementById('editor').innerHTML;
                if (svg_val !== null)
                    $('#svg_' + currentQuestion).val(svg_val);
                logs_in(answerText, currentQuestion, questionId);

                if (currentQuestion != totalQuestion - 1) {
                    currentQuestion++;
                    ajaxQuestion(currentQuestion);
                }

            }


        } else if (questionType == 2) { // open

            var clickedAnswer = ($('a.currentQuestion').attr('id')).split("_");
            var quesNum = clickedAnswer[1];

            mathSpanId = $('#span_' + quesNum).attr('id');
            spanTarget = document.getElementById(mathSpanId);
            mathSpan = MQ(spanTarget);
            answer_id = mathSpan.latex();
            questionId = $('#current_question_id').val();

            if (answer_id === '') {
                toastr.error('No Answer');
                logs_in(answer_id, currentQuestion, questionId);
            } else {
                $('#ques_' + quesNum).val(answer_id);
                toastr.success('Answer saved');
                $('#quesNo_' + currentQuestion).addClass('answeredQuestion');

                $('#svg_' + currentQuestion).val(document.getElementById('editor').innerHTML);
                logs_in(answer_id, currentQuestion, questionId);

                if (currentQuestion != totalQuestion - 1) {
                    currentQuestion++;
                    ajaxQuestion(currentQuestion);
                }
            }

        } else if (questionType == 3) { // true & false

            var clickedAnswer = ($('a.currentQuestion').attr('id')).split("_");
            var quesNum = clickedAnswer[1];
            var answerId = '';
            answerId = $('.answer_tf:checked').val();

            if (answerId == '' || answerId == undefined) {
                toastr.error('No Answer');
            } else {

                $('#ques_' + quesNum).val(answerId);

                toastr.success('Answer saved');

                $('#quesNo_' + currentQuestion).addClass('answeredQuestion');

                questionId = $('#current_question_id').val();
                var svg_val = document.getElementById('editor').innerHTML;
                if (svg_val !== null)
                    $('#svg_' + currentQuestion).val(svg_val);
                logs_in(answerId, currentQuestion, questionId);

                if (currentQuestion != totalQuestion - 1) {
                    currentQuestion++;
                    ajaxQuestion(currentQuestion);
                }

            }

        } else if (questionType == 5 || questionType == 7) { // FITB with option

            var clickedAnswer = ($('a.currentQuestion').attr('id')).split("_");
            var quesNum = clickedAnswer[1];
            var answerId = '';
            answerId = $('.answer_fitb_' + quesNum).val();

            if (answerId == '' || answerId == undefined) {
                toastr.error('No Answer');
            } else {

                $('#ques_' + quesNum).val(answerId);

                toastr.success('Answer saved');

                $('#quesNo_' + currentQuestion).addClass('answeredQuestion');

                questionId = $('#current_question_id').val();
                var svg_val = document.getElementById('editor').innerHTML;
                if (svg_val !== null)
                    $('#svg_' + currentQuestion).val(svg_val);
                logs_in(answerId, currentQuestion, questionId);

                if (currentQuestion != totalQuestion - 1) {
                    currentQuestion++;
                    ajaxQuestion(currentQuestion);
                }

            }

        } else if (questionType == 6) { // FITB without option

            var clickedAnswer = ($('a.currentQuestion').attr('id')).split("_");
            var quesNum = clickedAnswer[1];
            var answerId = '';
            answerId = $('.answer_fitb_' + quesNum).val();

            if (answerId == '' || answerId == undefined) {
                toastr.error('No Answer');
            } else {

                $('#ques_' + quesNum).val(answerId);

                toastr.success('Answer saved');

                $('#quesNo_' + currentQuestion).addClass('answeredQuestion');

                questionId = $('#current_question_id').val();
                var svg_val = document.getElementById('editor').innerHTML;
                if (svg_val !== null)
                    $('#svg_' + currentQuestion).val(svg_val);
                logs_in(answerId, currentQuestion, questionId);

                if (currentQuestion != totalQuestion - 1) {
                    currentQuestion++;
                    ajaxQuestion(currentQuestion);
                }

            }

        } else if (questionType == 8) { // mcq multiple choice
            var clickedAnswer = ($('a.currentQuestion').attr('id')).split("_");
            var quesNum = clickedAnswer[1];
            var answerId = '';
            answerId = $('.answer_mcq:checked').val();

            if (answerId == '' || answerId == undefined) {
                toastr.error('No Answer');
            } else {

                $('.answer_mcq:checked').each(function () {
                    answerId += $(this).val() + ',';
                })

                answerId = answerId.slice(0, -1);

                $('#ques_' + quesNum).val(answerId);

                toastr.success('Answer saved');

                $('#quesNo_' + currentQuestion).addClass('answeredQuestion');

                questionId = $('#current_question_id').val();
                answerText = $('.answer_mcq:checked').next().text();
                var svg_val = document.getElementById('editor').innerHTML;
                if (svg_val !== null)
                    $('#svg_' + currentQuestion).val(svg_val);
                logs_in(answerText, currentQuestion, questionId);

                if (currentQuestion != totalQuestion - 1) {
                    currentQuestion++;
                    ajaxQuestion(currentQuestion);
                }

            }
        } else if (questionType == 9) { // composition

        }

    });


    $(document).on('mouseout', '.btnSaveContinue', function (e) {
        e.preventDefault();
        $(this).removeClass('active');
    });


    $(document).on('click', '.submitQuiz', function () {
        var data = questionList[currentQuestion];
        var questionId = $('#current_question_id').val();

        var answerId = $('.btn.btn-custom.answerQuestion.btn-selectedAnswer').attr('href');
        mathSpanId = $('.openEndedAnswer').attr('id');

        if (answerId) {
            answer_id = $('.btn.btn-custom.answerQuestion.btn-selectedAnswer').text();
        } else {
            answer_id = 'NULL';
        }

        if (mathSpanId) {
            spanTarget = document.getElementById(mathSpanId);
            mathSpan = MQ(spanTarget);
            if (mathSpan) {
                answer_open = mathSpan.latex();
                answer_id = answer_open;
            }
        }

        logs_in(answer_id, currentQuestion, questionId);

        hasTimeLapse = true;
    });


    $(document).on('focus', '.openEndedAnswer', function () {
        $('#mathTarget').val($(this).attr('id'));
    });

    function logs_in(answer_id = NULL, currentQuestion = NULL, questionId = NULL) {
        $.ajax({
            url: base_url + 'smartgen/student_log',
            method: "post",
            dataType: 'json',
            data: {
                question_id: questionId,
                quiz_id: quizId,
                answer_id: answer_id,
                tutor_id: tutorId
            },
            success: function (data) {

            }
        });
    }
});

//************New Added Script Start*/
function activateMyScript(currentQuestion) {
    var editorElement = document.getElementById('editor');
    var resultElement = document.getElementById('myscriptResult');
    var undoElement = document.getElementById('undo');
    var redoElement = document.getElementById('redo');
    var clearElement = document.getElementById('clear');
    var convertElement = document.getElementById('convert');
    // var defPenElement = document.getElementById('defPen');
    // var medPenElement = document.getElementById('medPen');
    // var boldPenElement = document.getElementById('boldPen');
    editorElement.addEventListener('changed', function (event) {
        undoElement.disabled = !event.detail.canUndo;
        redoElement.disabled = !event.detail.canRedo;
        clearElement.disabled = event.detail.isEmpty;
    });
    // defPenElement.style.backgroundColor = '#2abb9b';
    // medPenElement.style.backgroundColor = '#ececec';
    // boldPenElement.style.backgroundColor = '#ececec';
    function cleanLatex(latexExport) {
        if (latexExport.includes('\\\\')) {
            const steps = '\\begin{align*}' + latexExport + '\\end{align*}';
            return steps.replace("\\overrightarrow", "\\vec")
                .replace("\\begin{aligned}", "")
                .replace("\\end{aligned}", "")
                .replace("\\llbracket", "\\lbracket")
                .replace("\\rrbracket", "\\rbracket")
                .replace("\\widehat", "\\hat")
                .replace(new RegExp("(align.{1})", "g"), "aligned");
        }
        return latexExport
            .replace("\\overrightarrow", "\\vec")
            .replace("\\llbracket", "\\lbracket")
            .replace("\\rrbracket", "\\rbracket")
            .replace("\\widehat", "\\hat")
            .replace(new RegExp("(align.{1})", "g"), "aligned");
    }

    editorElement.addEventListener('exported', function (evt) {
        const exports = evt.detail.exports;
        var save_to = $('#save_to').val();
        if (save_to == "svg") {
            //*****Variable to store handwriting data, To be Defined by Justin, current Variable: OCRrecorded */
            questionList[currentQuestion]['OCRrecorded'] = evt.detail.exports;
            $('#ocr_' + currentQuestion).val(JSON.stringify(evt.detail.exports));
            $('#ocr_digitize_' + currentQuestion).val(JSON.stringify(evt.detail.exports['application/x-latex']));
            $('#svg_' + currentQuestion).val(document.getElementById('editor').innerHTML);
        } else {
            questionList[currentQuestion]['OCRrecorded_question'] = evt.detail.exports;
            $('#ocr_question_' + currentQuestion).val(JSON.stringify(evt.detail.exports));
            $('#ocr_digitize_question_' + currentQuestion).val(JSON.stringify(evt.detail.exports['application/x-latex']));
            $('#svg_question_' + currentQuestion).val(document.getElementById('editor').innerHTML);
        }
        //******May require to store other variable as below, to be discussed */
        if (exports && exports['application/x-latex']) {
            convertElement.disabled = false;
            //katex.render(cleanLatex(exports['application/x-latex']),  resultElement);
        } else if (exports && exports['application/mathml+xml']) {
            convertElement.disabled = false;
            resultElement.innerText = exports['application/mathml+xml'];
        } else if (exports && exports['application/mathofficeXML']) {
            convertElement.disabled = false;
            resultElement.innerText = exports['application/mathofficeXML'];
        } else {
            convertElement.disabled = true;
            resultElement.innerHTML = '';
        }
    });
    editorElement.addEventListener('error', (evt) => {
        if (evt.detail && evt.detail.type !== 'close') {
            resultElement.innerText = JSON.stringify(evt.detail);
        }
    });

    undoElement.addEventListener('click', function () {
        editorElement.editor.undo();
    });

    redoElement.addEventListener('click', function () {
        editorElement.editor.redo();
    });

    clearElement.addEventListener('click', function () {
        editorElement.editor.clear();
    });

    convertElement.addEventListener('click', function () {
        //editorElement1.innerHTML = resultElement.innerHTML;
        editorElement.editor.convert();
    });

    var themes = [{
        name: 'Normal white theme',
        id: 'normal-white',
        theme: {
            ink: {
                color: '#000000',
                '-myscript-pen-width': 2
            },
            '.text': {
                'font-size': 12
            }
        }
    }];
    var defaultTheme = 'normal-white';
    function getTheme(themes, id) {
        return themes.filter(function (theme) {
            return theme.id === id;
        })[0].theme;
    }
	/**
	* Attach an editor to the document
	* @param {Element} The DOM element to attach the ink paper
	* @param {Object} The recognition parameters
	*/
    var editor = MyScript.register(editorElement, {
        recognitionParams: {
            type: 'MATH',
            protocol: 'WEBSOCKET',
            apiVersion: 'V4',
            server: {
                scheme: 'https',
                host: 'webdemoapi.myscript.com',
                applicationKey: 'b42908eb-d0e6-4d5f-ae43-592650f79ed1',
                hmacKey: '7b7a58f1-59b2-406c-8270-739cd32a9870'
            },
            v4: {
                alwaysConnected: true,
                math: {
                    mimeTypes: ['application/x-latex', 'application/vnd.myscript.jiix', 'image/png', 'image/jpeg']
                },
                export: {
                    jiix: {
                        strokes: true,
                        style: true
                    }
                }
            }
        }
    },
        undefined, getTheme(themes, defaultTheme));

    function getStyle(penColor = "#000000", size = 2) {
        return {
            color: penColor,
            '-myscript-pen-width': size
        }
    }
    var currentColor = '#000000';
    var currentSize = 2;

    $('#colorPickSelector').colorPick({
        'onColorSelected': function () {
            this.element.css({ 'backgroundColor': this.color, 'color': this.color });
            editor.penStyle = getStyle(this.color, currentSize);
            currentColor = this.color;
        }
    });

    window.addEventListener('resize', function () {
        editorElement.editor.resize();
    });

}
