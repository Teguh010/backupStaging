<?php $this->load->view('include/site_header'); ?>
<link href="<?php echo base_url('css/form-wizard.css'); ?>" rel="stylesheet" type="text/css">

<link rel="stylesheet" href="<?=base_url()?>css/mathquill.min.css" />
<link rel="stylesheet" href="<?=base_url()?>css/matheditor.css" />

<script src="<?=base_url()?>js/mathquill.min.js"></script>
<script src="<?=base_url()?>js/matheditor.js"></script>
<script src="<?=base_url()?>js/pages/administrator/edit-question.js"></script>


<div class="top-fixed bg-custom">
    <div style="width: 100%">
        <div class="pull-left ml-4">
            <h3 class="title-header fs30 text-white">Edit Question: <span></span></h3>
        </div>
        <div class="float-right mr-3 mt-2 fs-3x">
            <a style="text-decoration: none;" class="cursor_pointer text-white"><i
                    class="picons-thin-icon-thin-0153_delete_exit_remove_close"></i></a>
        </div>
    </div>
</div>

<div class="section pb-60" style="margin-top: -20px;">
    <div class="container">

        <div class="panel_question_content mb-3">
            <h5 class="label-question-content">[1] Question Content</h5>
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


        <div class="panel_answer_content">
            <h5 class="label-answer-content">[2] Answer</h5>
            <div class="answer_content mb-4">
                <div class="mcq_input_answers_div">

                    <div class="form-group row">
                        <label for="question_answers" class="col-sm-1 col-md-1 col-lg-1 control-label font400">1</label>
                        <div class="col-sm-6 col-md-6 col-lg-6 input_answer_text">
                            <span id="mcq_ans_1" style="width: 100%; padding: 0.5em" class="math_text"></span>
                            <input type="hidden" name="mcq_answers[]">
                        </div>
                        <div class="col-sm-6 col-md-6 col-lg-6 input_answer_image">
                            <input type="file" class="form-control" id="mcq_answers_image1" name="mcq_answers_image[]"
                                style="padding-top: 5px;" />
                        </div>
                        <div class="col-sm-5 col-md-5 col-lg-5">

                            <div class="customUi-checkbox checkboxUi-success correct_answer_single">
                                <input id="mcq_correct_answer1" class="input_correct_answer_single" type="radio"
                                    name="mcq_correct_answer" value="1">
                                <label for="mcq_correct_answer1">
                                    <span class="label-checkbox"></span>
                                    <span class="label-helper font300">Correct answer</span>
                                </label>
                            </div>

                            <div class="customUi-checkbox checkboxUi-success correct_answer_multiple">
                                <input id="mcq_correct_answer_checkbox1" class="input_correct_answer_multiple"
                                    type="checkbox" name="mcq_correct_answer_multiple" value="1">
                                <label for="mcq_correct_answer_checkbox1">
                                    <span class="label-checkbox"></span>
                                    <span class="label-helper font300">Correct answer</span>
                                </label>
                            </div>

                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="question_answers" class="col-sm-1 col-md-1 col-lg-1 control-label font400">2</label>
                        <div class="col-sm-6 col-md-6 col-lg-6 input_answer_text">
                            <span id="mcq_ans_2" style="width: 100%; padding: 0.5em" class="math_text"></span>
                            <input type="hidden" name="mcq_answers[]">
                        </div>
                        <div class="col-sm-6 col-md-6 col-lg-6 input_answer_image">
                            <input type="file" class="form-control" id="mcq_answers_image2" name="mcq_answers_image[]"
                                style="padding-top: 5px;" />
                        </div>
                        <div class="col-sm-5 col-md-5 col-lg-5">
                            <div class="customUi-checkbox checkboxUi-success correct_answer_single">
                                <input id="mcq_correct_answer2" class="input_correct_answer_single" type="radio"
                                    name="mcq_correct_answer" value="2">
                                <label for="mcq_correct_answer2">
                                    <span class="label-checkbox"></span>
                                    <span class="label-helper font300">Correct answer</span>
                                </label>
                            </div>

                            <div class="customUi-checkbox checkboxUi-success correct_answer_multiple">
                                <input id="mcq_correct_answer_checkbox2" class="input_correct_answer_multiple"
                                    type="checkbox" name="mcq_correct_answer_multiple" value="2">
                                <label for="mcq_correct_answer_checkbox2">
                                    <span class="label-checkbox"></span>
                                    <span class="label-helper font300">Correct answer</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="question_answers" class="col-sm-1 col-md-1 col-lg-1 control-label font400">3</label>
                        <div class="col-sm-6 col-md-6 col-lg-6 input_answer_text">
                            <span id="mcq_ans_3" style="width: 100%; padding: 0.5em" class="math_text"></span>
                            <input type="hidden" name="mcq_answers[]">
                        </div>
                        <div class="col-sm-6 col-md-6 col-lg-6 input_answer_image">
                            <input type="file" class="form-control" id="mcq_answers_image3" name="mcq_answers_image[]"
                                style="padding-top: 5px;" />
                        </div>
                        <div class="col-sm-5 col-md-5 col-lg-5">
                            <div class="customUi-checkbox checkboxUi-success correct_answer_single">
                                <input id="mcq_correct_answer3" class="input_correct_answer_single" type="radio"
                                    name="mcq_correct_answer" value="3">
                                <label for="mcq_correct_answer3">
                                    <span class="label-checkbox"></span>
                                    <span class="label-helper font300">Correct answer</span>
                                </label>
                            </div>

                            <div class="customUi-checkbox checkboxUi-success correct_answer_multiple">
                                <input id="mcq_correct_answer_checkbox3" class="input_correct_answer_multiple"
                                    type="checkbox" name="mcq_correct_answer_multiple" value="3">
                                <label for="mcq_correct_answer_checkbox3">
                                    <span class="label-checkbox"></span>
                                    <span class="label-helper font300">Correct answer</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="question_answers" class="col-sm-1 col-md-1 col-lg-1 control-label font400">4</label>
                        <div class="col-sm-6 col-md-6 col-lg-6 input_answer_text">
                            <span id="mcq_ans_4" style="width: 100%; padding: 0.5em" class="math_text"></span>
                            <input type="hidden" name="mcq_answers[]">
                        </div>
                        <div class="col-sm-6 col-md-6 col-lg-6 input_answer_image">
                            <input type="file" class="form-control" id="mcq_answers_image4" name="mcq_answers_image[]"
                                style="padding-top: 5px;" />
                        </div>
                        <div class="col-sm-5 col-md-5 col-lg-5">
                            <div class="customUi-checkbox checkboxUi-success correct_answer_single">
                                <input id="mcq_correct_answer4" class="input_correct_answer_single" type="radio"
                                    name="mcq_correct_answer" value="4">
                                <label for="mcq_correct_answer4">
                                    <span class="label-checkbox"></span>
                                    <span class="label-helper font300">Correct answer</span>
                                </label>
                            </div>

                            <div class="customUi-checkbox checkboxUi-success correct_answer_multiple">
                                <input id="mcq_correct_answer_checkbox4" class="input_correct_answer_multiple"
                                    type="checkbox" name="mcq_correct_answer_multiple" value="4">
                                <label for="mcq_correct_answer_checkbox4">
                                    <span class="label-checkbox"></span>
                                    <span class="label-helper font300">Correct answer</span>
                                </label>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="row true_false_input_answers_div">
                    <div class="col-sm-6 col-md-6 col-lg-3">
                        <div class="customUi-checkbox checkboxUi-success">
                            <input id="true_false_answer_0" type="radio" name="true_false_answer">
                            <label for="true_false_answer_0">
                                <span class="label-checkbox"></span>
                                <span class="label-helper font300">True</span>
                            </label>
                        </div>
                    </div>

                    <div class="col-sm-6 col-md-6 col-lg-3">
                        <div class="customUi-checkbox checkboxUi-danger">
                            <input id="true_false_answer_1" type="radio" name="true_false_answer">
                            <label for="true_false_answer_1">
                                <span class="label-checkbox"></span>
                                <span class="label-helper font300">False</span>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="open_ended_input_answers_div">

                    <div class="form-group row">
                        <div class="col-sm-6 col-md-6 col-lg-6 input_answer_text">
                            <span id="open_ended_answer" style="width: 100%; padding: 0.5em" class="math_text"></span>
                            <input type="hidden" name="open_ended_answer" class="form-control">
                        </div>
                        <div class="col-sm-6 col-md-6 col-lg-6 input_answer_image">
                            <input type="file" class="form-control" id="nmcq_answers_image" name="nmcq_answers_image[]"
                                style="padding-top: 5px;" />
                        </div>
                    </div>
                </div>

                <div class="fill_blank_answers_div">
                    <div class="row p-1 nav-button">
                        <div class="col-lg-4">
                            <a class="btn btn-sm btn-icon btn-teal btn-block cursor_pointer btnAddAnswerFb"
                                data-number="1"><i class="picons-thin-icon-thin-0151_plus_add_new"></i> Add Answer</a>
                        </div>
                        <div class="col-lg-4">
                            <a class="btn btn-sm btn-icon btn-danger btn-block cursor_pointer btnResetAnswerFb"><i
                                    class="picons-thin-icon-thin-0134_arrow_rotate_left_counter_clockwise"></i>
                                Reset</a>
                        </div>
                    </div>

                    <div class="row panel_answer_fb">

                    </div>

                </div>
            </div>

            <h5 class="label-working-content">[3] Difficulty</h5>
            <div class="row mb-4">
                <div class="col-sm-6 col-md-6 col-lg-3">
                    <div class="customUi-checkbox checkboxUi-warning">
                        <input id="difficulty_easy_0" type="radio" name="difficulty">
                        <label for="difficulty_easy_0">
                            <span class="label-checkbox"></span>
                            <span class="label-helper font300">Easy</span>
                        </label>
                    </div>
                </div>

                <div class="col-sm-6 col-md-6 col-lg-3">
                    <div class="customUi-checkbox checkboxUi-success">
                        <input id="difficulty_normal_0" type="radio" name="difficulty">
                        <label for="difficulty_normal_0">
                            <span class="label-checkbox"></span>
                            <span class="label-helper font300">Normal</span>
                        </label>
                    </div>
                </div>

                <div class="col-sm-6 col-md-6 col-lg-3">
                    <div class="customUi-checkbox checkboxUi-danger">
                        <input id="difficulty_hard_0" type="radio" name="difficulty">
                        <label for="difficulty_hard_0">
                            <span class="label-checkbox"></span>
                            <span class="label-helper font300">Hard</span>
                        </label>
                    </div>
                </div>

                <div class="col-sm-6 col-md-6 col-lg-3">
                    <div class="customUi-checkbox checkboxUi-teal">
                        <input id="difficulty_genius_0" type="radio" name="difficulty">
                        <label for="difficulty_genius_0">
                            <span class="label-checkbox"></span>
                            <span class="label-helper font300">Genius</span>
                        </label>
                    </div>
                </div>
            </div>


            <h5 class="label-working-content">[4] Workings</h5>
            <div class="row working_content"></div>
            <input type="hidden" class="working_content_id" value="0">
            <div class="dropdown" class="addWorkingContenButton">
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


    </div>
</div>

<div class="bg-dark-light bottom-fixed">
    <div class="container text-right">
        <button class="btn btn-lg btn-custom btn-w-150 cursor_pointer font500 btnSaveQuestion">Save Question</button>
    </div>
</div>

<div class="panel_math_quill" style="display: none;">
    <div class="card-columns">
        <div class="card shadow-1" style="width: 100vw;">
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