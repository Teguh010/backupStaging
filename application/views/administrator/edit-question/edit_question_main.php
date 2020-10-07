<?php $this->load->view('include/site_header'); ?>
<link href="<?php echo base_url('css/form-wizard.css'); ?>" rel="stylesheet" type="text/css">

<link rel="stylesheet" href="<?=base_url()?>css/mathquill.min.css" />
<link rel="stylesheet" href="<?=base_url()?>css/matheditor.css" />

<script src="<?=base_url()?>js/mathquill.min.js"></script>
<script src="<?=base_url()?>js/matheditor.js"></script>
<script src="<?=base_url()?>js/pages/administrator/edit-question.js"></script>
<script src="<?php echo base_url(); ?>js/plugins/ckeditor-4.14-full/ckeditor.js"></script>
<script src="<?=base_url()?>js/fileinput.js"></script>

<style>
	.mcq_img {
		display: block;
		width: 100%;
		max-width: 100%;
	}

	.thumbnail {
		/* /* position:relative; */
		/* transition: transform .4s; Animation  */
		/* top:-25px;
		left:-35px; */
		width:500px;
		height:auto;
		display:block;
		/* z-index:999; */
	}

	.thumbnail:hover {

		/* transform: scale(1.5); */
	}

	.mcq_img_correct_answer{
		border: 5px solid #2ABB9B;
	}
	.fstElement{
		width:100%;
		font-size: 0.7em !important;
		background-color: #fff;
		border: 1px solid #ccc;
		box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075);
		display: inline-block;
		color: #555;
		vertical-align: middle;
		border-radius: 4px;
		max-width: 100%;
		line-height: 22px;
		cursor: text;
	}
	.fstMultipleMode .fstControls {
		box-sizing: border-box;
		padding: 0.5em 0.5em 0em 0.5em;
		overflow: hidden;
		width: auto;
		cursor: text;
	}
	.fstChoiceRemove {
		right: 0 !important;
		left: auto;
	}
	.fstChoiceItem {
		padding: .33333em 1.5em .33333em .33333em;
		border: 1px solid #2abb9b;
		background-color: #2abb9b;
	}

	.btn-file {
	overflow: hidden;
	position: relative;
	vertical-align: middle;
	}
	.btn-file > input {
	position: absolute;
	top: 0;
	right: 0;
	margin: 0;
	opacity: 0;
	filter: alpha(opacity=0);
	transform: translate(-300px, 0) scale(4);
	-webkit-transform: translate(-300px, 0) scale(4);
	font-size: 23px;
	height: 100%;
	width: 100%;
	direction: ltr;
	cursor: pointer;
	}
	@-moz-document url-prefix() {
	.btn-file > input {
		transform: none;
	}
	}

	.fileinput {
	/* margin-bottom: 9px;
	display: inline-block; */
	}
	.fileinput .form-control {
	padding-top: 7px;
	padding-bottom: 5px;
	display: inline-block;
	margin-bottom: 0px;
	vertical-align: middle;
	cursor: text;
	}
	.fileinput .thumbnail {
	cursor: pointer;
	overflow: hidden;
	display: inline-block;
	margin-bottom: 5px;
	vertical-align: middle;
	text-align: center;
	}
	.fileinput .thumbnail > img {
	max-height: 200px;
	}
	.fileinput .btn {
	vertical-align: middle;
	}
	.fileinput-exists .fileinput-new,
	.fileinput-new .fileinput-exists {
	display: none;
	}
	.fileinput-inline .fileinput-controls {
	display: inline;
	}
	.fileinput-filename {
	vertical-align: middle;
	display: inline-block;
	overflow: hidden;
	}
	.form-control .fileinput-filename {
	vertical-align: bottom;
	}
	.fileinput.input-group {
	display: table;
	}
	.fileinput-new.input-group .btn-file,
	.fileinput-new .input-group .btn-file {
	border-radius: 0 1px 1px 0;
	}
	.fileinput-new.input-group .btn-file.btn-xs,
	.fileinput-new .input-group .btn-file.btn-xs,
	.fileinput-new.input-group .btn-file.btn-sm,
	.fileinput-new .input-group .btn-file.btn-sm {
	border-radius: 0 1px 1px 0;
	}
	.fileinput-new.input-group .btn-file.btn-lg,
	.fileinput-new .input-group .btn-file.btn-lg {
	border-radius: 0 1px 1px 0;
	}
	.form-group.has-warning .fileinput .fileinput-preview {
	color: #927608;
	}
	.form-group.has-warning .fileinput .thumbnail {
	border-color: #f7dc6f;
	}
	.form-group.has-error .fileinput .fileinput-preview {
	color: #a81515;
	}
	.form-group.has-error .fileinput .thumbnail {
	border-color: #f29797;
	}
	.form-group.has-success .fileinput .fileinput-preview {
	color: #527f26;
	}
	.form-group.has-success .fileinput .thumbnail {
	border-color: #b8df92;
	}
	.input-group-addon:not(:first-child) {
	border-left: 0;
	}
</style>

<?php 

    function reverseApplyMathJaxFormat($text) {

		if (is_numeric($text)) {  // is answer ID

			return $text;

		} else {

			$text = str_replace('\\(', '', $text);

			$text = str_replace('\\)', '', $text);

			$text = str_replace(' ', '\\ ', $text);
			
			return $text;
		}

	}

?>


<div class="top-fixed bg-custom">
    <div style="width: 100%">
        <div class="pull-left ml-4">
            <h3 class="title-header fs30 text-white">Edit Question: <span></span></h3>
        </div>
        <div class="float-right mr-3 mt-2 fs-3x">
            <a href="<?= site_url('administrator/questions') ?>" style="text-decoration: none;" class="cursor_pointer text-white"><i class="picons-thin-icon-thin-0153_delete_exit_remove_close"></i></a>
        </div>
    </div>
</div>

<form id="form-create" method="post">

<div class="section pb-60" style="margin-top: -20px;">
    <div class="container">

        <?php 
           $this->load->view('administrator/edit-question/edit_question_content');
        ?>

    </div>
</div>

<div class="bg-dark-light bottom-fixed">
    <div class="container text-right">
        <div class="float-left">            
            <div class="form-inline panel_nav_main_question">
                <div class="form-group">
                    <span class="text-white mr-2">Main Question: </span>
                    <span class="text-white"></span>
                </div>
            </div>
            <div class="form-inline panel_nav_sub_question" style="display: none;">
                <div class="form-group">
                    <span class="text-white mr-2">Sub Question: </span>
                    <button class="btn btn-icon-o radius100 btn-success btnAddSubquestion"><i class="fa fa-plus"></i></button>
                    <button class="btn btn-icon-o radius100 btn-danger mr-4 btnRemoveSubquestion" style="display: none;"><i class="fa fa-trash"></i></button>                    
                </div>
                <div class="form-group">
                    <a class="cursor_pointer fs20 text-white btnPrevSubquestion"><i class="fa fa-chevron-left"></i></a>
                    <input type="text" id="question-page" name="subQuestionNumber" class="fs20 ml-2 mr-2" style="width: 30px; background: transparent; text-align: center; border: 0;" value="0" readonly>
                    <a class="cursor_pointer fs20 text-white btnNextSubquestion"><i class="fa fa-chevron-right"></i></a>
                </div>
            </div>
        </div>
        <a class="btn btn-lg btn-outline-warning btn-w-150 cursor_pointer font500 btnPrevious"
            style="display: none;">Previous</a>
        <a class="btn btn-lg btn-outline-light btn-w-150 cursor_pointer font500 btnNext" style="display: none;">Next</a>
        <button type="button" class="btn btn-lg btn-custom btn-w-150 cursor_pointer font500 btnEditQuestion"
            style="display: none;">Edit Question</button>
    </div>
</div>

</form>

<div class="panel_math_quill" style="display: none;">
    <div class="card-columns">
        <div class="card shadow-1" style="width: 100vw;">
            <!-- <div class="card-header-small">
                <h5 class="card-title fs14">
                    Math Expression
                </h5>

                <a class="close_panel icon_close fs14">
                    <i class="fa fa-times"></i>
                </a>
            </div> -->
            <div class="card-body-small">
                <input type="hidden" id="mathTarget" value="">
                <div id="keyboard">
                    <div role="group" aria-label="math functions">
                        <button type="button" class="btn btn-default" onClick='input("\\frac")'>Fraction
                            \(\frac{x}{y}\)</button>
                        <button type="button" class="btn btn-default" onClick='inputMultiple("^", "\\circ")'>Degree
                            \(^\circ\)</button>
                        <button type="button" class="btn btn-default" onClick='input("\\pi")'>Pi \(\pi\)</button>
                        <button type="button" class="btn btn-default" onClick='input("\\angle")'>Angle
                            \(\angle\)</button>
                        <button type="button" class="btn btn-default" onClick='inputMultiple("^", "2")'>Power
                            \(x^2\)</button>
                        <button type="button" class="btn btn-default" onClick='inputMultiple("^", "3")'>Power
                            \(x^3\)</button>
                        <button type="button" class="btn btn-default" onClick='input("mℓ")'>Millilitre mℓ</button>
                        <button type="button" class="btn btn-default" onClick='input("ℓ")'>Litre ℓ</button>
                        <button type="button" class="btn btn-default" onClick='input("\\times")'>Times
                            \(\times\)</button>
                        <button type="button" class="btn btn-default" onClick='input("\\div")'>Divide \(\div\)</button>
                        <button type="button" class="btn btn-default"
                            onClick='inputMultipleMultiple("<", "br", ">")'>Linebreak</button>
                        <a href="#" class="btn btn-default" data-toggle="modal" data-target="#addQuestionModal"><i
                                class="fa fa-copy"></i> Add Question Text </a>
                    </div>
                </div>

                <div class="form-group">
                    <span id="math-field" style="width: 100%; padding: 0.5em"></span>
                </div>
                <div class="form-group">
                    <a href="<?php echo base_url() ?>administrator/latex" target="_blank">
                        <h5>Click Here For More Math Symbol</h5>
                    </a>
                </div>
            </div>
        </div>

    </div>
</div>


<?php $this->load->view('include/site_footer'); ?>

<script>
   
    // edit start - Shuq
    // $('.form-question-0').show();

    // // call getlevel() to display level inside question editor
    // getLevel('English');

    var no = $('#question-page').val();

    // // label listed subject
    var subject_id = '.btnSubject_<?= $question_detail->subject_type ?>';
    $(subject_id).addClass('bg-light shadow-1');

    // label key topic
    var key_topic = '<?= $question_detail->key_topic ?>';
    if(key_topic == 1){
        $('#key_topic_' + no).attr("checked", true);
    }

    // label disable question
    var disabled = '<?= $question_detail->disabled ?>';
    if(disabled == 1){
        $('#disable_question_' + no).attr("checked", true);
    }

    var question_type = '<?= $question_detail->question_type_id ?>';

    // FITB Without options -> QTID 6
    if(question_type == 6){
        var no = $('#question-page').val();
        $('.form-question-' + no + ' .panel_fitb').show();

        $('.btnShowFITB').addClass('active');
        $('.btnShowFITB').addClass('shadow-1');

        $('#ck_without_option_' + no).attr("checked", true);

        getAnswerType(question_type);

        // $('.form-question-' + no + ' .btnAnswerType').removeClass('active');
        // $(this).addClass('active');

        $('.form-question-' + no + ' .panel_question_content, .panel_answer_content').show();

        // show FITB answers
        $('.form-question-' + no + ' .panel_answer_fb').show();

        $('.btnResetAnswerFb').click(function (e) {
            e.preventDefault();

            var no = $('#question-page').val();
            var question_content_id = $('.form-question-' + no + ' .question_content_id').val();
            var text_input = $('textarea#input_question_text_' + no + '_' + question_content_id);
            var text_input_reset = $('textarea#input_question_text_' + no + '_' + question_content_id + ' ans');
            var editor_name = 'input_ckquestion_text_' + no + '_' + question_content_id;

            var editor = CKEDITOR.instances['input_ckquestion_text_' + no + '_' + question_content_id + ''];

            var resetQuestion = $('.form-question-' + no + ' .reset_question_fb').val();
            $('.form-question-' + no + ' .input_question_content').val(resetQuestion);
            $('.form-question-' + no + ' .fill_blank_answers_div .panel_answer_fb').html('');
            $('.form-question-' + no + ' .count_answer_fb').val('1');

            text_input_reset.replaceWith('');

            editor.setData(text_input.text());
        });
        
        var count_answer_fb = $('.form-question-' + no + ' .count_answer_fb').val();
        

        $('.addAnswerFITB_6').click(function () {
            var indexID = $(this).parent().data('id');
            var quesTypeID = $('#question_type_id_' + no).val();

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
        });
    } else if(question_type == 1 || question_type == 4) {

        // MCQ
        var no = $('#question-page').val();

        $('.btnMcq').addClass('active');
        $('.btnMcq').addClass('shadow-1');

        $('.form-question-' + no + ' .panel-answer-type').show();
        $('.form-question-' + no + ' .mcq_input_answers_div').show();

        $('.form-question-' + no + ' .panel_answer_type').html(`<button class="btn btn-square btn-outline-info font400 mr-1 mb-2 btnAnswerType" data-type="` + question_type + `" data-id="1">Single Correct Answer</button>`);
        $('.form-question-' + no + ' .correct_answer_multiple').hide();
        $('.form-question-' + no + ' .correct_answer_single').show();

        $('.form-question-' + no + ' .btnAnswerType').removeClass('active');
        $('.form-question-' + no + ' .btnAnswerType').addClass('active');

        getAnswerType(question_type);

        $('.form-question-' + no + ' .panel_question_content, .panel_answer_content').show();

        // show FITB answers
        $('.form-question-' + no + ' .panel_answer_fb').show();

        var answer_type_mcq = '<?php $answerOptions_isImage ?>';
        if(answer_type_mcq == 1){
            ansTypeTextImage = 'image';
        } else {
            ansTypeTextImage = 'text';
        }

        // var ansType = $('.form-question-' + no + ' .input_answer_type').val();
        var ansType = '<?= $question_detail->answer_type_id ?>';
        // console.log('ansType: ' + ansType);
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
    } else if (question_type == 2) {

        // MCQ
        var no = $('#question-page').val();
        var question_type_id = question_type;
        var ansType = '<?= $question_detail->answer_type_id ?>';

        $('.btnNMcq').addClass('active');
        $('.btnNMcq').addClass('shadow-1');

        $('#question_type_id_' + no).val(question_type);

        $.ajax({
            type: 'GET',
            url: base_url + 'administrator/getAnswerType/' + question_type_id,
            dataType: 'json',
            success: function (res) {

                var content = ``;
                for (i = 0; i < res.length; i++) {
                    if(res[i].answer_type_id == ansType){
                        content += `<button class="btn btn-square btn-outline-info font400 mr-1 mb-2 btnAnswerType active" data-type="` + question_type_id + `" data-id="` + res[i].answer_type_id + `">` + res[i].answer_type + `</button>`;
                    } else {
                        content += `<button class="btn btn-square btn-outline-info font400 mr-1 mb-2 btnAnswerType" data-type="` + question_type_id + `" data-id="` + res[i].answer_type_id + `">` + res[i].answer_type + `</button>`;
                    }
                }

                // $('.form-question-' + no + ' .input_question_type').val(question_type_id);

                $('.form-question-' + no + ' .panel-answer-type').show();
                $('.form-question-' + no + ' .panel_answer_type').html(content);
                $('.form-question-' + no + ' .mcq_input_answers_div').hide();
                $('.form-question-' + no + ' .fill_blank_answers_div').hide();
                $('.form-question-' + no + ' .panel_answer_type_text_image').hide();
                $('.form-question-' + no + ' .true_false_input_answers_div').hide();
                $('.form-question-' + no + ' .open_ended_input_answers_div').show();
            }
        });

        $('.form-question-' + no + ' .panel_question_content, .panel_answer_content').show();
    }

    var quesType = $('#question_type_id_' + no).val();

    // var ansTypeID = $(this).data('id');
    var ansTypeID = '<?= $question_detail->answer_type_id ?>';
    
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
    
    var question_instruction = '<?= count($question_instruction) ?>';
    if(question_instruction >= 1){
        $('#question_instruction').attr("checked", true);
        $('.addInstructionContenButton').show();
        $('.panel_instruction_content').show();
    } else {
        $('.addInstructionContenButton').hide();
        $('.panel_instruction_content').hide();
    }

    var question_article = '<?= count($question_article) ?>';
    if(question_article >= 1){
        $('#question_article').attr("checked", true);
        $('.addArticleContenButton').show();
        $('.panel_article_content').show();
    } else {
        $('.addArticleContenButton').hide();
        $('.panel_article_content').hide();
    }

    // label listed level
    // var level_id = '.btnLevel_<?=$question_detail->level_id ?>';
    // $('.btnLevel').addClass('active');

    // $('.btnNext').hide();
    // $('.btnSaveQuestion').show();
    // $('.panel_nav_main_question').hide();
    // $('.panel_nav_sub_question').show();

</script>