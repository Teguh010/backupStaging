<input type="hidden" id="data_instruction_content" name="data_instruction_content" />
<input type="hidden" id="data_article_content" name="data_article_content" />

    <div class="row form-question form-question-0" style="display: none;">
        <input type="hidden" id="question_type_id_0" name="question_type_id_0" />
        <input type="hidden" class="input_answer_type_id" id="answer_type_id_0" name="answer_type_id_0" />        
        <input type="hidden" id="data_question_content_0" name="data_question_content_0" />
        <input type="hidden" id="data_working_content_0" name="data_working_content_0" />

        <div class="col-lg-12">        
            <h5 class="label-topic-strategy pb-2">[1] Topic and Strategy <a class="fa_topic_strategy cursor_pointer"><i class="fa fa-angle-double-down"></i></a></h5>
        </div>
        <div class="panel_topic_strategy pb-20">

            <div class="col-lg-6">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="form-group">
                            <label for="topic_id_0" class="fs14 font300">Topic</label>                    
                            <select name="topic_id_0[]" class="topic_id" id="topic_id_0">                        
                            </select>                    
                        </div>
                    </div>            
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="form-group row">                    
                            <div class="col-sm-6 col-md-6 col-lg-4">
                                <div class="customUi-checkbox checkboxUi-primary">
                                    <input id="key_topic_0" type="checkbox" class="key_topic" name="key_topic_0" value="1">
                                    <label for="key_topic_0">
                                        <span class="label-checkbox"></span>
                                        <span class="label-helper text-primary fs14 font400">Key Topic</span>
                                    </label>
                                </div>
                            </div>

                            <div class="col-sm-6 col-md-6 col-lg-8">
                                <select name="key_topic_id_0" class="key_topic_id" id="key_topic_id_0">
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
                            <label for="substrategy_id_0" class="fs14 control-label font300">Sub Strategy</label>                    
                            <select name="substrategy_id_0[]" class="substrategy_id" id="substrategy_id_0">
                            </select>
                        </div>                        
                    </div>            
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="form-group row">                    
                            <div class="col-sm-6 col-md-6 col-lg-4">
                                <div class="customUi-checkbox checkboxUi-danger">
                                    <input id="key_strategy_0" type="checkbox" class="key_strategy" name="key_strategy_0" value="1">
                                    <label for="key_strategy_0">
                                        <span class="label-checkbox"></span>
                                        <span class="label-helper text-danger fs14 font400">Key Strategy</span>
                                    </label>
                                </div>
                            </div>

                            <div class="col-sm-6 col-md-6 col-lg-8">
                                <select name="key_substrategy_id_0" class="key_substrategy_id" id="key_substrategy_id_0">                        
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
                    <button class="btn btn-icon btn-xl btn-outline-teal mb-2 btnShowFITB" onclick="getAnswerType('0', '0')">
                        <i class="picons-thin-icon-thin-0101_notes_text_notebook"></i>
                        Fill in The Blank
                    </button>

                </div>

                <div class="col-lg-12 panel_fitb pt-10 pb-20" style="display: none;">
                    <div class="row text-center">
                        <div class="col-sm-6 col-md-6 col-lg-2">
                            <div class="customUi-checkbox checkboxUi-pink">
                                <input id="ck_with_option_0" type="radio" class="ck_fitb" name="ck_fitb_0" value="5" onclick="getAnswerType('5')">
                                <label for="ck_with_option_0">
                                    <span class="label-checkbox"></span>
                                    <span class="label-helper text-pink fs14 font400">With Option</span>
                                </label>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-6 col-lg-2">
                            <div class="customUi-checkbox checkboxUi-pink">
                                <input id="ck_without_option_0" type="radio" class="ck_fitb" name="ck_fitb_0" value="6" onclick="getAnswerType('6')">
                                <label for="ck_without_option_0">
                                    <span class="label-checkbox"></span>
                                    <span class="label-helper text-pink fs14 font400">Without Option</span>
                                </label>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-6 col-lg-3">
                            <div class="customUi-checkbox checkboxUi-pink">
                                <input id="ck_withunique_option_0" type="radio" class="ck_fitb" name="ck_fitb_0" value="7" onclick="getAnswerType('7')">
                                <label for="ck_withunique_option_0">
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
                <input type="hidden" class="answer_type_mcq" name="answer_type_mcq_0" value="text">
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

                <div class="row">
                    <div class="col-lg-12">
                        <div class="form-group">
                            <div class="customUi-checkbox checkboxUi-danger">
                                <input id="question_instruction" class="question_instruction" type="checkbox" name="question_instruction" value="1">
                                <label for="question_instruction">
                                    <span class="label-checkbox"></span>
                                    <span class="label-helper fs14 font300">Add Instruction</span>
                                </label>
                            </div>                        
                        </div>
                    </div>
                </div>
                <div class="row panel_instruction_content"></div>
                <input type="hidden" class="instruction_content_id" value="0">
                <input type="hidden" class="instruction_counter" value="0">
                <div class="dropdown addInstructionContenButton mb-2">
                    <button class="btn btn-icon-o radius100 btn-outline-danger dropdown-toggle" type="button"
                        data-toggle="dropdown"><span class="fa fa-plus"></span></button>
                    <ul class="dropdown-menu">
                        <li class="dropdown-instruction">Please choose text or image</li>
                        <li>
                            <a style="cursor: pointer;" onClick="addInstructionContent('text')"><i
                                    class="picons-thin-icon-thin-0262_remove_clear_text_style mr-2"></i> Text</a>
                        </li>
                        <li>
                            <a style="cursor: pointer;" onClick="addInstructionContent('mathtext')"><i
                                    class="picons-thin-icon-thin-0261_unlink_url_unchain_hyperlink mr-2"></i> Math Text</a>
                        </li>
                        <li>
                            <a style="cursor: pointer;" onClick="addInstructionContent('image')"><i
                                    class="picons-thin-icon-thin-0617_picture_image_photo mr-2"></i> Image</a>
                        </li>
                    </ul>
                </div>

                <div class="row">
                    <div class="col-lg-12 pt-20">
                        <div class="form-group">
                            <div class="customUi-checkbox checkboxUi-dark">
                                <input id="question_article" class="question_article" type="checkbox" name="question_article" value="1">
                                <label for="question_article">
                                    <span class="label-checkbox"></span>
                                    <span class="label-helper fs14 font300">Add Article</span>
                                </label>
                            </div>                        
                        </div>
                    </div>
                </div>

                <div class="row panel_article_content"></div>
                <input type="hidden" class="article_content_id" value="0">
                <input type="hidden" class="article_counter" value="0">
                <div class="dropdown addArticleContenButton mb-2">
                    <button class="btn btn-icon-o radius100 btn-outline-dark dropdown-toggle" type="button"
                        data-toggle="dropdown"><span class="fa fa-plus"></span></button>
                    <ul class="dropdown-menu">
                        <li class="dropdown-article">Please choose text or image</li>
                        <li>
                            <a style="cursor: pointer;" onClick="addArticleContent('text')"><i
                                    class="picons-thin-icon-thin-0262_remove_clear_text_style mr-2"></i> Text</a>
                        </li>
                        <li>
                            <a style="cursor: pointer;" onClick="addArticleContent('mathtext')"><i
                                    class="picons-thin-icon-thin-0261_unlink_url_unchain_hyperlink mr-2"></i> Math Text</a>
                        </li>
                        <li>
                            <a style="cursor: pointer;" onClick="addArticleContent('image')"><i
                                    class="picons-thin-icon-thin-0617_picture_image_photo mr-2"></i> Image</a>
                        </li>
                    </ul>
                </div>


                <hr>

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
                            <label for="question_answers" class="col-sm-1 col-md-1 col-lg-1 control-label font400" style="width: .5% !important">1</label>
                            <div class="col-sm-6 col-md-6 col-lg-6 input_answer_text">
                                <span id="mcq_ans_1_0" style="width: 100%; padding: 0.5em" class="math_text"></span>
                                <input type="hidden" name="mcq_answers_0[]">
                            </div>
                            <div class="col-sm-6 col-md-6 col-lg-6 input_answer_image" style="display: none;">
                                <input type="file" class="form-control" class="mcq_answers_image" name="mcq_answers_image_0[]"
                                    style="padding-top: 5px;" />
                            </div>
                            <div class="col-sm-5 col-md-5 col-lg-5">

                                <div class="customUi-checkbox checkboxUi-success correct_answer_single">
                                    <input id="mcq_correct_answer1_0" class="input_correct_answer_single" type="radio" name="mcq_correct_answer_0" value="1" checked>
                                    <label for="mcq_correct_answer1_0">
                                        <span class="label-checkbox"></span>
                                        <span class="label-helper fs14 font300">Correct answer</span>
                                    </label>
                                </div>

                                <div class="customUi-checkbox checkboxUi-success correct_answer_multiple">
                                    <input id="mcq_correct_answer_checkbox1_0" class="input_correct_answer_multiple" type="checkbox" name="mcq_correct_answer_multiple_0[]" value="1" checked>
                                    <label for="mcq_correct_answer_checkbox1_0">
                                        <span class="label-checkbox"></span>
                                        <span class="label-helper fs14 font300">Correct answer</span>
                                    </label>
                                </div>        

                            </div>
                        </div>
    
                        <div class="form-group row">
                            <label for="question_answers" class="col-sm-1 col-md-1 col-lg-1 control-label font400" style="width: .5% !important">2</label>
                            <div class="col-sm-6 col-md-6 col-lg-6 input_answer_text">
                                <span id="mcq_ans_2_0" style="width: 100%; padding: 0.5em" class="math_text"></span>
                                <input type="hidden" name="mcq_answers_0[]">
                            </div>
                            <div class="col-sm-6 col-md-6 col-lg-6 input_answer_image" style="display: none;">
                                <input type="file" class="form-control" class="mcq_answers_image" name="mcq_answers_image_0[]"
                                    style="padding-top: 5px;" />
                            </div>
                            <div class="col-sm-5 col-md-5 col-lg-5">
                                <div class="customUi-checkbox checkboxUi-success correct_answer_single">
                                    <input id="mcq_correct_answer2_0" class="input_correct_answer_single" type="radio" name="mcq_correct_answer_0" value="2">
                                    <label for="mcq_correct_answer2_0">
                                        <span class="label-checkbox"></span>
                                        <span class="label-helper fs14 font300">Correct answer</span>
                                    </label>
                                </div>

                                <div class="customUi-checkbox checkboxUi-success correct_answer_multiple">
                                    <input id="mcq_correct_answer_checkbox2_0" class="input_correct_answer_multiple" type="checkbox" name="mcq_correct_answer_multiple_0[]" value="2">
                                    <label for="mcq_correct_answer_checkbox2_0">
                                        <span class="label-checkbox"></span>
                                        <span class="label-helper fs14 font300">Correct answer</span>
                                    </label>
                                </div>                            
                            </div>
                        </div>
    
                        <div class="form-group row">
                            <label for="question_answers" class="col-sm-1 col-md-1 col-lg-1 control-label font400" style="width: .5% !important">3</label>
                            <div class="col-sm-6 col-md-6 col-lg-6 input_answer_text">
                                <span id="mcq_ans_3_0" style="width: 100%; padding: 0.5em" class="math_text"></span>
                                <input type="hidden" name="mcq_answers_0[]">
                            </div>
                            <div class="col-sm-6 col-md-6 col-lg-6 input_answer_image" style="display: none;">
                                <input type="file" class="form-control" class="mcq_answers_image" name="mcq_answers_image_0[]"
                                    style="padding-top: 5px;" />
                            </div>
                            <div class="col-sm-5 col-md-5 col-lg-5">
                                <div class="customUi-checkbox checkboxUi-success correct_answer_single">
                                    <input id="mcq_correct_answer3_0" class="input_correct_answer_single" type="radio" name="mcq_correct_answer_0" value="3">
                                    <label for="mcq_correct_answer3_0">
                                        <span class="label-checkbox"></span>
                                        <span class="label-helper fs14 font300">Correct answer</span>
                                    </label>
                                </div>

                                <div class="customUi-checkbox checkboxUi-success correct_answer_multiple">
                                    <input id="mcq_correct_answer_checkbox3_0" class="input_correct_answer_multiple" type="checkbox" name="mcq_correct_answer_multiple_0[]" value="3">
                                    <label for="mcq_correct_answer_checkbox3_0">
                                        <span class="label-checkbox"></span>
                                        <span class="label-helper fs14 font300">Correct answer</span>
                                    </label>
                                </div>                            
                            </div>
                        </div>
    
                        <div class="form-group row">
                            <label for="question_answers" class="col-sm-1 col-md-1 col-lg-1 control-label font400" style="width: .5% !important">4</label>
                            <div class="col-sm-6 col-md-6 col-lg-6 input_answer_text">
                                <span id="mcq_ans_4_0" style="width: 100%; padding: 0.5em" class="math_text"></span>
                                <input type="hidden" name="mcq_answers_0[]">
                            </div>
                            <div class="col-sm-6 col-md-6 col-lg-6 input_answer_image" style="display: none;">
                                <input type="file" class="form-control" class="mcq_answers_image" name="mcq_answers_image_0[]"
                                    style="padding-top: 5px;" />
                            </div>
                            <div class="col-sm-5 col-md-5 col-lg-5">
                                <div class="customUi-checkbox checkboxUi-success correct_answer_single">
                                    <input id="mcq_correct_answer4_0" class="input_correct_answer_single" type="radio" name="mcq_correct_answer_0" value="4">
                                    <label for="mcq_correct_answer4_0">
                                        <span class="label-checkbox"></span>
                                        <span class="label-helper fs14 font300">Correct answer</span>
                                    </label>
                                </div>

                                <div class="customUi-checkbox checkboxUi-success correct_answer_multiple">
                                    <input id="mcq_correct_answer_checkbox4_0" class="input_correct_answer_multiple" type="checkbox" name="mcq_correct_answer_multiple_0[]" value="4">
                                    <label for="mcq_correct_answer_checkbox4_0">
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
                                <input id="true_false_answer_0_0" type="radio" name="true_false_answer_0" value="1" checked>
                                <label for="true_false_answer_0_0">
                                    <span class="label-checkbox"></span>
                                    <span class="label-helper fs14 font300">True</span>
                                </label>
                            </div>                            
                        </div>

                        <div class="col-sm-6 col-md-6 col-lg-1">
                            <div class="customUi-checkbox checkboxUi-danger">
                                <input id="true_false_answer_1_0" type="radio" name="true_false_answer_0" value="0">
                                <label for="true_false_answer_1_0">
                                    <span class="label-checkbox"></span>
                                    <span class="label-helper fs14 font300">False</span>
                                </label>
                            </div>                            
                        </div>
                    </div>
    
                    <div class="open_ended_input_answers_div">
    
                        <div class="form-group row">                        
                            <div class="col-sm-6 col-md-6 col-lg-6 input_answer_text">
                                <span id="open_ended_answer_0" style="width: 100%; padding: 0.5em" class="math_text"></span>
                                <input type="hidden" name="open_ended_answer_0" class="form-control">
                            </div>
                            <div class="col-sm-6 col-md-6 col-lg-6 input_answer_image" style="display: none;">
                                <input type="file" class="form-control" class="nmcq_answers_image" name="nmcq_answers_image_0[]"
                                    style="padding-top: 5px;" />
                            </div>
                        </div>
                    </div>
    
                    <div class="fill_blank_answers_div">
                        <input type="hidden" class="count_answer_fb" name="count_answer_fb_0" value="1">
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
                                <input id="marks_1_0" type="radio" name="marks_0" value="1">
                                <label for="marks_1_0">
                                    <span class="label-checkbox"></span>
                                    <span class="label-helper fs14 font300">1</span>
                                </label>
                            </div>
                        </div>

                        <div class="col-sm-6 col-md-6 col-lg-1">
                            <div class="customUi-checkbox checkboxUi-success">
                                <input id="marks_2_0" type="radio" name="marks_0" value="2" checked>
                                <label for="marks_2_0">
                                    <span class="label-checkbox"></span>
                                    <span class="label-helper fs14 font300">2</span>
                                </label>
                            </div>                            
                        </div>

                        <div class="col-sm-6 col-md-6 col-lg-1">
                            <div class="customUi-checkbox checkboxUi-danger">
                                <input id="marks_3_0" type="radio" name="marks_0" value="3">
                                <label for="marks_3_0">
                                    <span class="label-checkbox"></span>
                                    <span class="label-helper fs14 font300">3</span>
                                </label>
                            </div>                            
                        </div>

                        <div class="col-sm-6 col-md-6 col-lg-1">
                            <div class="customUi-checkbox checkboxUi-teal">
                                <input id="marks_4_0" type="radio" name="marks_0" value="4">
                                <label for="marks_4_0">
                                    <span class="label-checkbox"></span>
                                    <span class="label-helper fs14 font300">4</span>
                                </label>
                            </div>                            
                        </div>

                        <div class="col-sm-6 col-md-6 col-lg-1">
                            <div class="customUi-checkbox checkboxUi-primary">
                                <input id="marks_5_0" type="radio" name="marks_0" value="5">
                                <label for="marks_5_0">
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


                <h5 class="label-tags pb-2">[8] Type/Tags/Label (Max 5) <a class="fa_tag_label cursor_pointer"><i class="fa fa-angle-double-down"></i></a></h5>
                <div class="row panel_tag_label">
                    <div class="col-sm-6 col-md-6 col-lg-12">
                        <?php 
                            if (isset($selected_tags) && empty($selected_tags) === false) {
                                echo '<input name="tagsinput_0" class="tagsinput" data-role="tagsinput" placeholder="eg: CA1, Mock Exam 2" value="' . $selected_tags . '" style="display: none;">';
                            } else {
                                echo '<input name="tagsinput_0" class="tagsinput" data-role="tagsinput" placeholder="eg: CA1, Mock Exam 2" value="" style="display: none;">';
                            }
                        ?>
                    </div>
                </div>

            </div>


        </div>

    </div>

<div class="pb-60"></div>

<div class="pb-60"></div>