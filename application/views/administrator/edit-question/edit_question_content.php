<div class="page-header">
    <h3>
        Question 
        <?php            
            if ($question_status === true && $question_detail->sub_question == 'A') {
                if ($question_detail->question_level == 1) {
                    echo $question_parent->question_counter;
                } else {
                    // echo $question_parent->question_id;
                    echo $question_parent->question_counter;
                }
                
            } else {
                if ($question_detail->question_level == 1) {
                    echo $question_parent->question_counter.$question_detail->sub_question;
                } else {
                    // echo $question_parent->reference_id.$question_detail->sub_question;
                    echo $question_parent->question_counter.$question_detail->sub_question;
                }
            }
        ?>

    </h3>
</div>

<div class="form-group">
    <?php 
        if($question_detail->question_content == 0){

            if($question_detail->content_type == 'text'){
                if($question_detail->question_type_id == 6){                    
                    $string = $question_detail->question_text;                    
                    $question = preg_replace('#<ans[^>]*>.*?</ans>#si', '<div class="start_input_answer" style="display: inline;"><span class="line_blank text-danger">_______________ <i class="picons-thin-icon-thin-0001_compose_write_pencil_new"></i></span><input type="hidden" class="input_answer input_style1_black" name="input_answer_'.$question_detail->question_id.'"></div>', $string);

                    echo '<div class="pb-40" style="line-height: 2em; height: auto;">                    
                        '.$question.'
                    </div>';
                } else {
                    echo $question_detail->question_text;
                    echo '<br>';
                }
                
            } else {
                echo '<div><img src="'.$question_detail->branch_image_url.'/questionImage/'.$question_detail->question_text.'" class="img-responsive"></div>';
            }
            

            if ($question_detail->graphical != "0") {
                echo '<div><img src="'.$question_detail->branch_image_url.'/questionImage/'.$question_detail->graphical.'" class="img-responsive"></div>';
            }
        } else {
            if($question_detail->content_type == 'text'){
                echo $question_detail->question_text;
                echo '<br>';
            } else {
                echo '<div><img src="'.$question_detail->branch_image_url.'/questionImage/'.$question_detail->question_text.'" class="img-responsive"></div>';
            }

            if(count($question_content) > 0){
                foreach($question_content as $row){
                    echo '<br>';

                    if($row->content_type == 'text'){
                        echo '<div>'. $row->content_name . '</div>';
                    } else {
                        echo '<div><img src="'.$question_detail->branch_image_url.'/questionImage/'. $row->content_name.'" class="img-responsive"></div>';
                    }
                }
            }
        }
            
    ?>

    <?php 
        if (isset($answerOptions)) {
            
            if($answerOptions_isImage == 1){
                echo '<div class="row">';

                for ($i = 1, $l = count($answerOptions); $i <= $l; $i++) {
                    
                    if($answerOptions[$i-1]->answer_type == 'image'){
                        echo '<div class="col-md-3 col-sm-6 col-xs-12">';

                        if($correctAnswer == $answerOptions[$i-1]->answer_id){
                            echo 'Option ' . $i .') <img class="mcq_img thumbnail mcq_img_correct_answer" src="'.$question_detail->branch_image_url.'/answerImage/'. $answerOptions[$i-1]->answer_text.'">';
                        } else {
                            echo 'Option ' . $i .') <img class="mcq_img thumbnail" src="'.$question_detail->branch_image_url.'/answerImage/'. $answerOptions[$i-1]->answer_text.'">';
                        }
                        
                        echo '</div>';
                    } else {
                        echo 'Option ' . $i .') ' . $answerOptions[$i-1]->answer_text;

                        if ($correctAnswer == $answerOptions[$i-1]->answer_id) {

                            echo ' <i class="fa fa-check"></i>';
                        }

                        echo '<br>';
                    }
                }

                echo '</div>';
            } else {
                for ($i = 1, $l = count($answerOptions); $i <= $l; $i++) {
                    echo 'Option ' . $i .') ' . $answerOptions[$i-1]->answer_text;

                    if ($correctAnswer == $answerOptions[$i-1]->answer_id) {

                        echo ' <i class="fa fa-check"></i>';
                    }

                    echo '<br>';
                }
            }

            if($mcq_working->working_type == 'text' && $mcq_working->working_text != ''){
                echo '<br>';
                echo '<div><b>Solution:</b> '. $mcq_working->working_text . '</div>';
            } else if ($mcq_working->working_type == 'image' && $mcq_working->working_text != '') {
                echo '<br>';
                echo '<div><b>Solution:</b><img src="'.$question_detail->branch_image_url.'/workingImage/'. $mcq_working->working_text.'" class="img-responsive"></div>';
            }
            

            if (isset($mcq_working_contents)) {
                foreach ($mcq_working_contents as $mcq_working_content) {

                    echo '<br>';

                    if($mcq_working_content->content_type == 'text'){
                        echo '<div>'. $mcq_working_content->content_name . '</div>';
                    } else {
                        echo '<div><img src="'.$question_detail->branch_image_url.'/workingImage/'. $mcq_working_content->content_name.'" class="img-responsive"></div>';
                    }
                }
            }

        } else if (isset($open_ended_answer)) {
            if($open_ended_answer_type == 'text'){
                echo '<br>';
                echo '<div><b>Correct Answer:</b> '. $open_ended_answer . '</div>';
                echo '<br>';
            } else {
                echo '<br>';
                echo '<div><b>Correct Answer:</b><img src="'.$question_detail->branch_image_url.'/answerImage/'. $open_ended_answer.'" class="img-responsive thumbnail mcq_img_correct_answer"></div>';
                echo '<br>';
            }

            if($open_ended_working->working_type == 'text' && $open_ended_working->working_text != ''){
                echo '<div><b>Solution:</b> '. $open_ended_working->working_text . '</div>';
            } else if ($open_ended_working->working_type == 'image' && $open_ended_working->working_text != '') {
                echo '<div><b>Solution:</b><img src="'.$question_detail->branch_image_url.'/workingImage/'. $open_ended_working->working_text.'" class="img-responsive"></div>';
            }
            

            if (isset($open_ended_working_contents)) {
                foreach ($open_ended_working_contents as $open_ended_working_content) {

                    echo '<br>';

                    if($open_ended_working_content->content_type == 'text'){
                        echo '<div>'. $open_ended_working_content->content_name . '</div>';
                    } else {
                        echo '<div><img src="'.$question_detail->branch_image_url.'/workingImage/'. $open_ended_working_content->content_name.'" class="img-responsive"></div>';
                    }
                }
            }

        }

    ?>

</div>

<hr>

<div class="row row_subject">
    <input type="hidden" name="subject_id" id="subject_id" value="<?php echo $question_detail->subject_type; ?>">
    <input type="hidden" name="level_id" id="level_id" value="<?php echo $question_detail->level_id; ?>">
    <input type="hidden" name="question_counter" id="question_counter" value="<?php echo $question_detail->question_counter; ?>">
    <div class="col-lg-3 mb-6 col-md-6 mb-30">
        <div class="list border1 rounded shadow-sm overflow-hidden cursor_pointer btnSubject btnSubject_2  btnSubject_5"
            onclick="getLevel('Maths')">
            <div class="list-item">
                <div class="list-thumb avatar avatar60 shadow-sm">
                    <img src="<?= base_url('img/icon-subject-math-2.png') ?>" alt="" class="img-fluid">
                </div>
                <div class="list-body text-right">
                    <span class="list-title fs-1x font400">Maths</span>

                </div>
            </div>
        </div>
    </div>
    <!--col-->
    <div class="col-lg-3 mb-6 col-md-6 mb-30">
        <div class="list border1 rounded shadow-sm overflow-hidden cursor_pointer btnSubject btnSubject_1 btnSubject_4"
            onclick="getLevel('English')">
            <div class="list-item">
                <div class="list-thumb avatar avatar60 shadow-sm">
                    <img src="<?= base_url('img/icon-subject-english.png') ?>" alt="" class="img-fluid">
                </div>
                <div class="list-body text-right">
                    <span class="list-title fs-1x font400">English</span>
                </div>
            </div>
        </div>
    </div>
    <!--col-->
    <div class="col-lg-3 mb-6 col-md-6 mb-30">
        <div class="list border1 rounded shadow-sm overflow-hidden cursor_pointer btnSubject btnSubject_3 btnSubject_6"
            onclick="getLevel('Science')">
            <div class="list-item">
                <div class="list-thumb avatar avatar60 shadow-sm">
                    <img src="<?= base_url('img/icon-subject-science.png') ?>" alt="" class="img-fluid">
                </div>
                <div class="list-body text-right">
                    <span class="list-title fs-1x font400">Science</span>

                </div>
            </div>
        </div>
    </div>
    <!--col-->
</div>

<div class="row row_level b-t">
    <div class="mt-10 content_level">
        <div class="col-lg-12 primary_level">
        
        </div>
        <div class="col-lg-12 secondary_level">
        
        </div>
    </div>
</div>

<div class="row row_school">
    <div class="mt-20 content_school">
        <div class="col-lg-12">
            <div class="form-group">
                <label for="school_id" class="col-sm-4 col-md-4 col-lg-2 control-label font300">School<?php echo $question_detail->school_id; ?></label>
                <div class="col-sm-7 col-md-7 col-lg-7">
                    <select name="school_id" id="school_id">
                        <option value="0">Not Applicable</option>
                        <?php
                            foreach ($schools as $school) {
                                if ($question_detail->school_id == $school->school_id) {
                                    echo '<option value="'.$school->school_id.'" selected="selected">'.$school->school_name.'</option>';
                                } else {
                                    echo '<option value="'.$school->school_id.'">'.$school->school_name.'</option>';
                                }
                            }
                        ?>
                    </select>
                </div>
            </div>
        </div>

        <div class="col-lg-12">
            <div class="form-group">
                <label for="year" class="col-sm-4 col-md-4 col-lg-2 control-label font300">Year</label>
                <div class="col-sm-6 col-md-6 col-lg-7">
                    <input style="width: 40%;" name="year" id="year" type="number" class="form-control"
                    value="<?=$question_detail->year?>">
                </div>
            </div>
        </div>

        <div class="col-lg-12 pt-10">
                <div class="form-group">
                    <label for="difficulty1" class="col-sm-4 col-md-4 col-lg-2 control-label font300">Difficulty Level</label>
                    <div class="col-sm-6 col-md-6 col-lg-7">
                        <div class="customUi-checkbox checkboxUi-success mr-5">
                            <input id="difficulty0_1" type="radio" name="difficulty_level" value="1" disabled>
                            <label for="difficulty0_1">
                            <span class="label-checkbox"></span>
                            <span class="label-helper fs14 font300">Easy</span>
                            </label>
                        </div>
                        
                        <div class="customUi-checkbox checkboxUi-danger mr-5">
                            <input id="difficulty0_2" type="radio" name="difficulty_level" value="2" disabled>
                            <label for="difficulty0_2">
                                <span class="label-checkbox"></span>
                                <span class="label-helper fs14 font300">Normal</span>
                            </label>
                        </div>                    

                        <div class="customUi-checkbox checkboxUi-teal">
                            <input id="difficulty0_3" type="radio" name="difficulty_level" value="3" disabled>
                            <label for="difficulty0_3">
                                <span class="label-checkbox"></span>
                                <span class="label-helper fs14 font300">Hard</span>
                            </label>
                        </div>

                    </div>
                </div>
                        
        </div>

        <div class="col-lg-12 pt-20">
            <div class="form-group">
                <div class="customUi-checkbox checkboxUi-dark">
                    <input id="add_difficulty_level2nd" type="checkbox" name="add_difficulty_level2nd" value="1">
                    <label for="add_difficulty_level2nd">
                        <span class="label-checkbox"></span>
                        <span class="label-helper fs14 font300">Add Difficulty & Level (2nd)</span>
                    </label>
                </div>                        
            </div>
        </div>

        <div class="panel_difficulty_level2nd" style="display:none;">
            <div class="col-lg-12">
                <div class="form-group">
                    <label for="difficulty" class="col-sm-4 col-md-4 col-lg-2 control-label font300">Difficulty</label>
                    <div class="col-sm-6 col-md-6 col-lg-7">
                        <div class="customUi-checkbox checkboxUi-success mr-5">
                            <input id="difficulty1" type="radio" name="difficulty_level2" value="1">
                            <label for="difficulty1">
                            <span class="label-checkbox"></span>
                            <span class="label-helper fs14 font300">Easy</span>
                            </label>
                        </div>
                        
                        <div class="customUi-checkbox checkboxUi-danger mr-5">
                            <input id="difficulty2" type="radio" name="difficulty_level2" value="2">
                            <label for="difficulty2">
                                <span class="label-checkbox"></span>
                                <span class="label-helper fs14 font300">Normal</span>
                            </label>
                        </div>                    

                        <div class="customUi-checkbox checkboxUi-teal">
                            <input id="difficulty3" type="radio" name="difficulty_level2" value="3">
                            <label for="difficulty3">
                                <span class="label-checkbox"></span>
                                <span class="label-helper fs14 font300">Hard</span>
                            </label>
                        </div>

                    </div>
                </div>
                        
            </div>

            <div class="col-lg-12 pt-10">
                <div class="form-group">
                    <label for="level_id_2nd" class="col-sm-4 col-md-4 col-lg-2 control-label font300">Level</label>
                    <div class="col-sm-6 col-md-6 col-lg-7">
                        <select name="level_id2" id="level_id2" style="width: 270px;"></select>
                    </div>
                </div>
                        
            </div>
        </div>

        <div class="col-lg-12 pt-20">
            <div class="form-group">
                <div class="customUi-checkbox checkboxUi-dark">
                    <input id="add_difficulty_level3rd" type="checkbox" name="add_difficulty_level3rd" value="1">
                    <label for="add_difficulty_level3rd">
                        <span class="label-checkbox"></span>
                        <span class="label-helper fs14 font300">Add Difficulty & Level (3rd)</span>
                    </label>
                </div>                        
            </div>
        </div>

        <div class="panel_difficulty_level3rd" style="display:none;">
            <div class="col-lg-12">
                <div class="form-group">
                    <label for="difficulty" class="col-sm-4 col-md-4 col-lg-2 control-label font300">Difficulty</label>
                    <div class="col-sm-6 col-md-6 col-lg-7">
                        <div class="customUi-checkbox checkboxUi-success mr-5">
                            <input id="difficulty1_level3" type="radio" name="difficulty_level3" value="1">
                            <label for="difficulty1_level3">
                            <span class="label-checkbox"></span>
                            <span class="label-helper fs14 font300">Easy</span>
                            </label>
                        </div>
                        
                        <div class="customUi-checkbox checkboxUi-danger mr-5">
                            <input id="difficulty2_level3" type="radio" name="difficulty_level3" value="2">
                            <label for="difficulty2_level3">
                                <span class="label-checkbox"></span>
                                <span class="label-helper fs14 font300">Normal</span>
                            </label>
                        </div>                    

                        <div class="customUi-checkbox checkboxUi-teal">
                            <input id="difficulty3_level3" type="radio" name="difficulty_level3" value="3">
                            <label for="difficulty3_level3">
                                <span class="label-checkbox"></span>
                                <span class="label-helper fs14 font300">Hard</span>
                            </label>
                        </div>

                    </div>
                </div>
                        
            </div>

            <div class="col-lg-12 pt-10">
                <div class="form-group">
                    <label for="level_id3" class="col-sm-4 col-md-4 col-lg-2 control-label font300">Level</label>
                    <div class="col-sm-6 col-md-6 col-lg-7">
                        <select name="level_id3" id="level_id3" style="width: 270px;"></select>
                    </div>
                </div>
                        
            </div>
        </div>

    </div>
</div>

<!-- separate start here -->


<input type="hidden" id="data_instruction_content" name="data_instruction_content" />
<input type="hidden" id="data_article_content" name="data_article_content" />
    
    <!-- <div class="row form-question form-question-0" style="display: none;"> -->
    <div class="row form-question form-question-0">
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
                            <select name="topic_id_0[]" class="topic_id selectized" id="topic_id_0" multiple="multiple" tabindex="-1" style="display: none;">
                            <?php
                                $array_selected = array($question_detail->topic_id, $question_detail->topic_id2, $question_detail->topic_id3, $question_detail->topic_id4);
                                foreach ($categories as $category) {
                                    if (in_array($category->id, $array_selected)) {
                                        echo '<option value="' . $category->id . '" selected="selected">' . $category->name . '</option>';
                                    } else {
                                        echo '<option value="' . $category->id . '">' . $category->name . '</option>';
                                    }
                                }
							?>       
                            </select>
                                               
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
                    <button class="btn btn-icon btn-xl btn-outline-teal mb-2 btnQuesType btnMcq" onclick="getAnswerType('1')">
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
                    <button class="btn btn-icon btn-xl btn-outline-teal mb-2 btnQuesType btnNMcq" onclick="getAnswerType('2')">
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
                <?php if($question_detail->question_type_id == 1 || $question_detail->question_type_id == 4){
                        if($answerOptions_isImage == 1){
                            $answer_isImage = 'image';
                        } else {
                            $answer_isImage = 'text';
                        }
                        echo '<input type="hidden" class="answer_type_mcq" name="answer_type_mcq_0" value="'.$answer_isImage.'">';
                    } else if($question_detail->question_type_id == 2){
                        echo '<input type="hidden" class="answer_type_nmcq" name="answer_type_nmcq_0" value="'.$open_ended_answer_type.'">';
                    }
                ?>
                
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
                <div class="row panel_instruction_content">
                    <?php 
                        $instruction_content_id = 0;
                        $instruction_counter = 0;

                        if(count($question_instruction) > 0){
                            foreach($question_instruction as $row){
                                if($row->content_type == 'text'){
                                    echo '<div class="col-lg-12 mb-2">
                                            <div class="form-inline">
                                                <div class="form-group" style="width: 100%;text-align: center;">
                                                    <span id="instruction_text_'.$instruction_content_id.'" style="min-height: 50px; height: auto; width: 90%; text-align:left;" class="form-control math_text">
                                                        <textarea style="box-shadow:none!important">'.reverseApplyMathJaxFormat($row->header_content).'</textarea>
                                                    </span>
                                                    <input name="input_instruction_text[]" id="question_text_hidden'.$instruction_content_id.'" type="hidden" class="input_instruction_content" data-type="text">
                                                        <a style="cursor: pointer; text-decoration: none;"
                                                            class="removeInstructionContent text-danger-active fs18 ml-2" data-index="'.$instruction_content_id.'" data-id="text"><i
                                                                class="fa fa-times"></i></a>
                                                </div>
                                            </div>
                                          </div>';
                                } else {
                                    echo '<div class="col-lg-12">
                                            <div class="form-inline">
                                                <div class="form-group" style="width: 100%;text-align: center;">
                                                    <div class="fileinput fileinput-exists" data-provides="fileinput">
                                                        <div class="fileinput-preview thumbnail" data-trigger="fileinput" style="height: auto; width: 90%; padding: 0.5em;text-align: left;" class="form-control">
                                                            <img src="'.$question_detail->branch_image_url.'/articleImage/'. $row->header_content.'" class="img-responsive">
                                                        </div>

                                                        <a style="cursor: pointer; text-decoration: none;" class="removeInstructionContent text-danger-active fs18 ml-2" data-index="0" data-id="image">
                                                            <i class="fa fa-times"></i>
                                                        </a>   

                                                        <div>
                                                            <span class="btn btn-default btn-file" style="display:none">
                                                                <span class="fileinput-new">Select image</span>
                                                                <span class="fileinput-exists">Change</span>
                                                                <input type="file" class="form-control input_instruction_content" data-type="image" name="input_instruction_image[]" required>
                                                            </span>
                                                                
                                                            <a href="#" class="btn btn-default fileinput-exists" data-dismiss="fileinput" style="display:none">Remove</a>
                                                        </div>
                                                    </div>	  
                                                </div>                                                                                                     
                                            </div>
                                        </div>';
                                }	
                                $instruction_content_id++;
                                $instruction_counter++; 
                            }
                        }                 
                    ?>                     
                </div>

                <input type="hidden" class="instruction_content_id" value="<?php echo count($question_instruction); ?>">
                <input type="hidden" class="instruction_counter" value="<?php echo count($question_instruction); ?>">
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

                <div class="row panel_article_content">
                    <?php 
                        $article_content_id = 0;
                        $article_content = 0;

                        if(count($question_article) > 0){
                            foreach($question_article as $row){
                                if($row->content_type == 'text'){
                                    echo '<div class="panel_input_article">
                                            <div class="col-lg-12 mb-2">
                                                <div class="form-inline">
                                                    <div class="form-group" style="width: 100%;text-align: center;">
                                                        <span id="article_text_'.$article_content_id.'" style="min-height: 50px; height: auto; width: 90%; text-align:left;" class="form-control math_text">
                                                            <textarea style="box-shadow:none!important">'.reverseApplyMathJaxFormat($row->header_content).'</textarea>
                                                        </span>
                                                        <input name="input_article_text[]" id="question_text_hidden'.$article_content_id.'" type="hidden" class="input_article_content" data-type="text">    
                                                        <a style="cursor: pointer; text-decoration: none;"
                                                                    class="removeArticleContent text-danger-active fs18 ml-2" data-index="'.$article_content_id.'" data-id="text"><i
                                                                        class="fa fa-times"></i></a>    
                                                    </div>
                                                </div>                        
                                            </div>
                                        </div>';
                                } else {

                                        echo '<div class="panel_input_article">
                                            <div class="col-lg-12 mb-2">
                                                <div class="form-inline">
                                                    <div class="form-group" style="width: 100%;text-align: center;">
                                                        <div class="fileinput fileinput-exists" data-provides="fileinput">
                                                            <div class="fileinput-preview thumbnail" data-trigger="fileinput" style="height: auto; width: 90%; padding: 0.5em;text-align: left;" class="form-control">
                                                                <img src="'.$question_detail->branch_image_url.'/articleImage/'. $row->header_content.'" class="img-responsive">
                                                            </div>

                                                            <a style="cursor: pointer; text-decoration: none;" class="removeArticleContent text-danger-active fs18 ml-2" data-index="0" data-id="image">
                                                                <i class="fa fa-times"></i>
                                                            </a>   

                                                            <div>
                                                                <span class="btn btn-default btn-file" style="display:none">
                                                                    <span class="fileinput-new">Select image</span>
                                                                    <span class="fileinput-exists">Change</span>
                                                                    <input type="file" class="form-control input_article_content" data-type="image" name="input_article_image[]" required>
                                                                </span>
                                                                    
                                                                <a href="#" class="btn btn-default fileinput-exists" data-dismiss="fileinput" style="display:none">Remove</a>
                                                            </div>
                                                        </div>	  
                                                    </div>                                                                                                     
                                                </div>
                                            </div>
                                        </div>';
                                }
                                $article_content_id++;
                                $article_content++;	 
                            }
                        }                 
                    ?>
                </div>
                <input type="hidden" class="article_content_id" value="<?php echo count($question_article); ?>">
                <input type="hidden" class="article_counter" value="<?php echo count($question_article); ?>">
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
                    <div class="row question_content">
                        <?php 
                            $question_content_id = 0;
                            $content_counter = 0;

                            $no = 0;
                            if($question_detail->question_type_id == 6){
                                if($question_detail->content_type == 'text'){
                                    echo '<textarea name="input_question_fitb_'.$no.'_'.$question_content_id.'" id="input_question_fitb_'.$no.'_'.$question_content_id.'"  data-type="text" style="display: none;">'.$question_detail->question_text.'</textarea>';
                                    // echo '<textarea name="input_question_fitb_'.$no.'_'.$question_content_id.'" id="input_question_fitb_'.$no.'_'.$question_content_id.'" class="input_question_content_'.$no.'" data-type="text" style="display: none;">'.$question_detail->question_text.'</textarea>';
                                    echo "<script type='text/javascript'> showQuestionContent('text', $question_detail->question_type_id); </script>";
                                    // echo '<textarea name="input_ckquestion_text_'.$no.'[]" id="input_ckquestion_text_'.$no.'_'.$question_content_id.'" spellcheck="false"></textarea>';
                                    
                                    
                                } else {
                                    echo '<div class="panel_question_content_'.$no.'_'.$question_content_id.'">
                                            <div class="col-lg-11 mb-2">                    
                                            <input type="file" class="form-control input_question_content_'.$no.'" data-type="image" name="input_question_image_'.$no.'[]" style="width: 90%; height: 40px; padding-top: 6px;" multiple="multiple" required />
                                            </div>
                                            <div class="col-lg-1 mb-2">                                                
                                                <a style="cursor: pointer; text-decoration: none;" class="removeQuestionContent text-danger-active fs18 ml-2" data-index="`+ question_content_id + `" data-id="image">
                                                <i class="fa fa-times"></i></a>
                                            </div>
                                          </div>';
                                }	
                                
                                // $question_content_id++;
                                // $content_counter++;
                            } else if ($question_detail->question_type_id == 1 || $question_detail->question_type_id == 2 || $question_detail->question_type_id == 4){
                                if($question_detail->content_type == 'text'){
                                    echo '<div class="panel_question_content_'.$no.'_'.$question_content_id.'">
                                            <div class="col-lg-12 mb-2">
                                                <div class="form-inline">
                                                    <div class="form-group" style="width: 100%;text-align: center;">
                                                        <span id="question_text_'.$no.'_'.$question_content_id.'" style="min-height: 50px; height: auto; width: 90%; text-align:left;" class="form-control math_text math_text_'.$question_content_id.'">
                                                            <textarea style="box-shadow:none!important">'.reverseApplyMathJaxFormat($question_detail->question_text).'</textarea>
                                                        </span>
                                                            <input name="input_question_text_'.$no.'[]" id="question_text_hidden` + question_content_id + `" type="hidden" class="input_question_content_'.$no.'" data-type="text">  
                                                            <a style="cursor: pointer; text-decoration: none;" class="removeQuestionContent text-danger-active fs18 ml-2" data-index="'.$question_content_id.'" data-id="text">
                                                            <i class="fa fa-times"></i></a>		
                                                    </div>
                                                </div>
                                            </div>
                                        </div>';
                                } else {

                                    echo '<div class="panel_question_content_'.$no.'_'.$question_content_id.'">
                                            <div class="col-lg-12">
                                                <div class="form-inline">
                                                    <div class="form-group" style="width: 100%;text-align: center;">
                                                        <div class="fileinput fileinput-exists" data-provides="fileinput">
                                                            <div class="fileinput-preview thumbnail" data-trigger="fileinput" style="height: auto; width: 90%; padding: 0.5em;text-align: left;" class="form-control">
                                                                <img src="'.$question_detail->branch_image_url.'/questionImage/'. $question_detail->question_text.'" class="img-responsive">
                                                            </div>

                                                            <a style="cursor: pointer; text-decoration: none;" class="removeQuestionContent text-danger-active fs18 ml-2" data-index="0" data-id="image">
                                                                <i class="fa fa-times"></i>
                                                            </a>   

                                                            <div>
                                                                <span class="btn btn-default btn-file" style="display:none">
                                                                    <span class="fileinput-new">Select image</span>
                                                                    <span class="fileinput-exists">Change</span>
                                                                    <input type="file" class="form-control input_question_content_'.$no.'" data-type="image" name="input_question_image_'.$no.'[]" required>
                                                                </span>
                                                                    
                                                                <a href="#" class="btn btn-default fileinput-exists" data-dismiss="fileinput" style="display:none">Remove</a>
                                                            </div>
                                                        </div>	  
                                                    </div>                                                                                                     
                                                </div>
                                            </div>
                                        </div>';
                                }
                                $question_content_id++;
                                $content_counter++;

                                if(count($question_content) > 0){
                                    foreach($question_content as $row){	
                                        if($row->content_type == 'text'){
                                            echo '<div class="panel_question_content_'.$no.'_'.$question_content_id.'">
                                                <div class="col-lg-12 mb-2">
                                                    <div class="form-inline">
                                                        <div class="form-group" style="width: 100%;text-align: center;">
                                                            <span id="question_text_'.$no.'_'.$question_content_id.'" style="min-height: 50px; height: auto; width: 90%; text-align:left;" class="form-control math_text math_text_'.$question_content_id.'">
                                                                <textarea style="box-shadow:none!important">'.reverseApplyMathJaxFormat($row->content_name).'</textarea>
                                                            </span>
                                                                <input name="input_question_text_'.$no.'[]" id="question_text_hidden` + question_content_id + `" type="hidden" class="input_question_content_'.$no.'" data-type="text">  
                                                                <a style="cursor: pointer; text-decoration: none;" class="removeQuestionContent text-danger-active fs18 ml-2" data-index="'.$question_content_id.'" data-id="text">
                                                                <i class="fa fa-times"></i></a>		
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>';
                                        } else {

                                            echo '<div class="panel_question_content_'.$no.'_'.$question_content_id.'">
                                                    <div class="col-lg-12">
                                                        <div class="form-inline">
                                                            <div class="form-group" style="width: 100%;text-align: center;">
                                                                <div class="fileinput fileinput-exists" data-provides="fileinput">
                                                                    <div class="fileinput-preview thumbnail" data-trigger="fileinput" style="height: auto; width: 90%; padding: 0.5em;text-align: left;" class="form-control">
                                                                        <img src="'.$question_detail->branch_image_url.'/questionImage/'. $row->content_name.'" class="img-responsive">
                                                                    </div>

                                                                    <a style="cursor: pointer; text-decoration: none;" class="removeQuestionContent text-danger-active fs18 ml-2" data-index="0" data-id="image">
                                                                        <i class="fa fa-times"></i>
                                                                    </a>   

                                                                    <div>
                                                                        <span class="btn btn-default btn-file" style="display:none">
                                                                            <span class="fileinput-new">Select image</span>
                                                                            <span class="fileinput-exists">Change</span>
                                                                            <input type="file" class="form-control input_question_content_'.$no.'" data-type="image" name="input_question_image_'.$no.'[]" required>
                                                                        </span>
                                                                            
                                                                        <a href="#" class="btn btn-default fileinput-exists" data-dismiss="fileinput" style="display:none">Remove</a>
                                                                    </div>
                                                                </div>    
                                                            </div>                                                                                                     
                                                        </div>
                                                    </div>
                                                </div>';
                                        }
                                        $question_content_id++;
                                        $content_counter++;
                                    }
                                }
                            }
                                          
                        ?>
                    </div>
                
                    <input type="hidden" class="question_content_id" value="<?php echo $question_content_id; ?>">
                    <input type="hidden" class="content_counter" value="<?php echo $content_counter; ?>">
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
                        <?php if($answerOptions_isImage == '1'){
                            echo '<button class="btn btn-sm btn-rounded btn-outline-pink mr-1 fs14 btnAnswerTypeTextImage" style="width: 80px;" data-type="text">Text</button>';
                            echo '<button class="btn btn-sm btn-rounded btn-outline-pink mr-1 fs14 btnAnswerTypeTextImage active" style="width: 80px;" data-type="image">Image</button>';
                        } else {
                            echo '<button class="btn btn-sm btn-rounded btn-outline-pink mr-1 fs14 btnAnswerTypeTextImage active" style="width: 80px;" data-type="text">Text</button>';
                            echo '<button class="btn btn-sm btn-rounded btn-outline-pink mr-1 fs14 btnAnswerTypeTextImage" style="width: 80px;" data-type="image">Image</button>';
                        }
                        ?>
                        </div>                    
                    </div>

                    <div class="mcq_input_answers_div">
    
                        <div class="form-group row">
                            <label for="question_answers" class="col-sm-1 col-md-1 col-lg-1 control-label font400" style="width: .5% !important">1</label>
                            <div class="col-sm-6 col-md-6 col-lg-6 input_answer_text">
                                <span id="mcq_ans_1_0" style="width: 100%; padding: 0.5em" class="math_text">
                                    <?=isset($answerOptions) && empty($answerOptions) === false?reverseApplyMathJaxFormat(htmlentities($answerOptions[0]->answer_text)):''?>
                                </span>
                                <input type="hidden" name="mcq_answers_0[]">
                            </div>
                            <div class="col-sm-6 col-md-6 col-lg-6 input_answer_image" style="display: none;">
                                <input type="file" class="form-control" class="mcq_answers_image" name="mcq_answers_image_0[]"
                                    style="padding-top: 5px;" />
                            </div>
                            <div class="col-sm-5 col-md-5 col-lg-5">

                                <div class="customUi-checkbox checkboxUi-success correct_answer_single">
                                    <input id="mcq_correct_answer1_0" class="input_correct_answer_single" type="radio" name="mcq_correct_answer_0" value="1" <?=(empty($answerOptions) === false && $answerOptions[0]->answer_id == $correctAnswer)?'checked':''?>>
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
                                <span id="mcq_ans_2_0" style="width: 100%; padding: 0.5em" class="math_text">
                                    <?=isset($answerOptions) && empty($answerOptions) === false?reverseApplyMathJaxFormat(htmlentities($answerOptions[1]->answer_text)):''?>
                                </span>
                                <input type="hidden" name="mcq_answers_0[]">
                            </div>
                            <div class="col-sm-6 col-md-6 col-lg-6 input_answer_image" style="display: none;">
                                <input type="file" class="form-control" class="mcq_answers_image" name="mcq_answers_image_0[]"
                                    style="padding-top: 5px;" />
                            </div>
                            <div class="col-sm-5 col-md-5 col-lg-5">
                                <div class="customUi-checkbox checkboxUi-success correct_answer_single">
                                    <input id="mcq_correct_answer2_0" class="input_correct_answer_single" type="radio" name="mcq_correct_answer_0" value="2" <?=(empty($answerOptions) === false && $answerOptions[1]->answer_id == $correctAnswer)?'checked':''?>>
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
                                <span id="mcq_ans_3_0" style="width: 100%; padding: 0.5em" class="math_text">
                                    <?=isset($answerOptions) && empty($answerOptions) === false?reverseApplyMathJaxFormat(htmlentities($answerOptions[2]->answer_text)):''?>
                                </span>
                                <input type="hidden" name="mcq_answers_0[]">
                            </div>
                            <div class="col-sm-6 col-md-6 col-lg-6 input_answer_image" style="display: none;">
                                <input type="file" class="form-control" class="mcq_answers_image" name="mcq_answers_image_0[]"
                                    style="padding-top: 5px;" />
                            </div>
                            <div class="col-sm-5 col-md-5 col-lg-5">
                                <div class="customUi-checkbox checkboxUi-success correct_answer_single">
                                    <input id="mcq_correct_answer3_0" class="input_correct_answer_single" type="radio" name="mcq_correct_answer_0" value="3" <?=(empty($answerOptions) === false && $answerOptions[2]->answer_id == $correctAnswer)?'checked':''?>>
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
                                <span id="mcq_ans_4_0" style="width: 100%; padding: 0.5em" class="math_text">
                                    <?=isset($answerOptions) && empty($answerOptions) === false?reverseApplyMathJaxFormat(htmlentities($answerOptions[3]->answer_text)):''?>
                                </span>
                                <input type="hidden" name="mcq_answers_0[]">
                            </div>
                            <div class="col-sm-6 col-md-6 col-lg-6 input_answer_image" style="display: none;">
                                <input type="file" class="form-control" class="mcq_answers_image" name="mcq_answers_image_0[]"
                                    style="padding-top: 5px;" />
                            </div>
                            <div class="col-sm-5 col-md-5 col-lg-5">
                                <div class="customUi-checkbox checkboxUi-success correct_answer_single">
                                    <input id="mcq_correct_answer4_0" class="input_correct_answer_single" type="radio" name="mcq_correct_answer_0" value="4" <?=(empty($answerOptions) === false && $answerOptions[3]->answer_id == $correctAnswer)?'checked':''?>>
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
                                <span id="open_ended_answer_0" style="width: 100%; padding: 0.5em" class="math_text">
                                    <?=isset($open_ended_answer) && empty($open_ended_answer) === false?reverseApplyMathJaxFormat(htmlentities($open_ended_answer)):''?>
                                </span>
                                <input type="hidden" name="open_ended_answer_0" class="form-control">
                            </div>
                            <div class="col-sm-6 col-md-6 col-lg-6 input_answer_image" style="display: none;">
                                <input type="file" class="form-control" class="nmcq_answers_image" name="nmcq_answers_image_0[]"
                                    style="padding-top: 5px;" />
                            </div>
                        </div>
                    </div>
    
                    <div class="fill_blank_answers_div">
                        <input type="hidden" class="count_answer_fb" name="count_answer_fb_0" value="<?php isset($answerOptions)? $max_answer_group + 1 :'' ?>">
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

                            <?php 
                                $count_answer_fb = 1;
                                $no = 0;

                                if($question_detail->question_type_id == 6){
                                    if(count($answerOptions) > 0){
                                        for($count=1 ; $count<=$max_answer_group; $count++){
                                            $count_skip = 0;
                                            foreach($answerOptions as $row){
                                                if($row->answer_group == $count && $count_skip == 0){
                                                    echo '<div class="col-lg-3">                        
                                                            <div class="card-header-small">
                                                                <div class="form-inline">
                                                                    <div class="form-group" style="width: 100%;">
                                                                        <span class="number mr-1">'.$count_answer_fb.'</span>
                                                                        <input type="text" class="form-control input_style1_black" style="width: 80%" name="input_answer_fb_open_'.$no.'_'.$count_answer_fb.'[]" value="'.$row->answer_text.'">
                                                                        <a class="close_question text-success icon_close mr-2">
                                                                            <i class="fa fa-check"></i>
                                                                        </a>
                                                                    </div>
                                                                </div>                            
                                                            </div>
                                                            <ul class="list-group list-group-flush">';
                                                            foreach($answerOptions as $options_row){
                                                                if($options_row->answer_group == $count && $row->answer_id != $options_row->answer_id){
                                                                    echo '<li class="list-group-item form-inline">
                                                                            <input type="text" class="form-control input_style1_red mr-2" name="input_answer_fb_open_'.$no.'_'.$count_answer_fb.'[]" value="'.$options_row->answer_text.'" style="width: 80%"/>
                                                                            <span class="text-success"><i class="fa fa-check"></i></span>
                                                                        </li>';
                                                                }
                                                            }
                                                                
                                                            echo'       <li class="list-group-item text-center panel_add_answer_fb" data-id="'.$no.'_'.$count_answer_fb.'">
                                                                            <button class="btn btn-icon btn-default btn-block btn-sm addAnswerFITB_6"><i class="fa fa-plus"></i>Add Alternatives</button>
                                                                        </li>                                
                                                            </ul>
                                                        </div>';

                                                    $count_answer_fb++;
                                                    $count_skip = 1;
                                                }	                                                
                                            }
                                        }
                                    }
                                }

                            ?>

                        </div>                        
                        
                    </div>
                </div>

                <!-- <li class="list-group-item form-inline">
                    <input type="text" class="form-control input_style1_red mr-2" name="input_answer_fb_open_'.$no.'_'.$count_answer_fb.'[]" placeholder="Alternative" style="width: 80%"/>
                    <span class="text-success"><i class="fa fa-check"></i></span>
                </li> -->

                <h5 class="label-working-content">[6] Marks <a class="fa_marks cursor_pointer"><i class="fa fa-angle-double-down"></i></a></h5>
                <div class="panel_marks pb-20">
                    <div class="row">
                        <div class="col-sm-6 col-md-6 col-lg-1">
                            <div class="customUi-checkbox checkboxUi-warning">
                                <input id="marks_1_0" type="radio" name="marks_0" value="1" <?=($question_detail->difficulty == 1)?'checked':''?>>
                                <label for="marks_1_0">
                                    <span class="label-checkbox"></span>
                                    <span class="label-helper fs14 font300">1</span>
                                </label>
                            </div>
                        </div>

                        <div class="col-sm-6 col-md-6 col-lg-1">
                            <div class="customUi-checkbox checkboxUi-success">
                                <input id="marks_2_0" type="radio" name="marks_0" value="2" <?=($question_detail->difficulty == 2)?'checked':''?>>
                                <label for="marks_2_0">
                                    <span class="label-checkbox"></span>
                                    <span class="label-helper fs14 font300">2</span>
                                </label>
                            </div>                            
                        </div>

                        <div class="col-sm-6 col-md-6 col-lg-1">
                            <div class="customUi-checkbox checkboxUi-danger">
                                <input id="marks_3_0" type="radio" name="marks_0" value="3" <?=($question_detail->difficulty == 3)?'checked':''?>>
                                <label for="marks_3_0">
                                    <span class="label-checkbox"></span>
                                    <span class="label-helper fs14 font300">3</span>
                                </label>
                            </div>                            
                        </div>

                        <div class="col-sm-6 col-md-6 col-lg-1">
                            <div class="customUi-checkbox checkboxUi-teal">
                                <input id="marks_4_0" type="radio" name="marks_0" value="4" <?=($question_detail->difficulty == 4)?'checked':''?>>
                                <label for="marks_4_0">
                                    <span class="label-checkbox"></span>
                                    <span class="label-helper fs14 font300">4</span>
                                </label>
                            </div>                            
                        </div>

                        <div class="col-sm-6 col-md-6 col-lg-1">
                            <div class="customUi-checkbox checkboxUi-primary">
                                <input id="marks_5_0" type="radio" name="marks_0" value="5" <?=($question_detail->difficulty == 5)?'checked':''?>>
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
                    <div class="row working_content">

                        <?php 
                            if($question_detail->question_type_id == 1 || $question_detail->question_type_id == 6 || $question_detail->question_type_id == 4){
                                $working_content_id = 0;
                                $working_content = 0;
                                $no = 0;
                                
                                if($mcq_working->working_text != '' && $mcq_working->working_type=='text') {
                                
                                    echo '<div class="panel_input_working">
                                                    <div class="col-lg-12 mb-2">
                                                        <div class="form-inline">
                                                            <div class="form-group" style="width: 100%;text-align: center;">
                                                                <span id="working_text_'.$working_content_id.'" style="min-height: 50px; height: auto; width: 90%; text-align:left;" class="form-control math_text">
                                                                    <textarea style="box-shadow:none!important">'.reverseApplyMathJaxFormat($mcq_working->working_text).'</textarea>
                                                                </span>
                                                                <input name="input_working_text_'.$no.'_[]" id="working_text_hidden'.$working_content_id.'" type="hidden" class="input_working_content_'.$no.'" data-type="text">    
                                                                <a style="cursor: pointer; text-decoration: none;"
                                                                            class="removeWorkingContent text-danger-active fs18 ml-2" data-index="'.$working_content_id.'" data-id="text"><i
                                                                                class="fa fa-times"></i></a>    
                                                            </div>
                                                        </div>                        
                                                    </div>
                                                </div>';         
    
                                    $working_content_id = 1;
                                    $working_content = 1;
                                }
    
                                if($mcq_working->working_text != '' && $mcq_working->working_type=='image') {

                                    echo '<div class="panel_input_working">
                                            <div class="col-lg-12">
                                                <div class="form-inline">
                                                    <div class="form-group" style="width: 100%;text-align: center;">
                                                        <div class="fileinput fileinput-exists" data-provides="fileinput">
                                                            <div class="fileinput-preview thumbnail" data-trigger="fileinput" style="height: auto; width: 90%; padding: 0.5em;text-align: left;" class="form-control">
                                                                <img src="'.$question_detail->branch_image_url.'/workingImage/'. $mcq_working->working_text.'" class="img-responsive">
                                                            </div>

                                                            <a style="cursor: pointer; text-decoration: none;" class="removeWorkingContent text-danger-active fs18 ml-2" data-index="0" data-id="image">
                                                                <i class="fa fa-times"></i>
                                                            </a>   

                                                            <div>
                                                                <span class="btn btn-default btn-file" style="display:none">
                                                                    <span class="fileinput-new">Select image</span>
                                                                    <span class="fileinput-exists">Change</span>
                                                                    <input type="file" class="form-control input_working_content_'.$no.'" data-type="image" name="input_working_image_'.$no.'_[]" required>
                                                                </span>
                                                                    
                                                                <a href="#" class="btn btn-default fileinput-exists" data-dismiss="fileinput" style="display:none">Remove</a>
                                                            </div>
                                                        </div>	  
                                                    </div>                                                                                                     
                                                </div>
                                            </div>
                                        </div>';
    
                                    $working_content_id = 1;
                                    $working_content = 1;
                                }
    
                                if(isset($mcq_working_contents) && count($mcq_working_contents) > 0){
                                    foreach ($mcq_working_contents as $mcq_working_content) {
    
                                        if($mcq_working_content->content_type == 'text'){
    
                                            echo '<div class="panel_input_working">
                                                    <div class="col-lg-12 mb-2">
                                                        <div class="form-inline">
                                                            <div class="form-group" style="width: 100%;text-align: center;">
                                                                <span id="working_text_'.$working_content_id.'" style="min-height: 50px; height: auto; width: 90%; text-align:left;" class="form-control math_text">
                                                                    <textarea style="box-shadow:none!important">'.reverseApplyMathJaxFormat($mcq_working_content->content_name).'</textarea>
                                                                </span>
                                                                <input name="input_working_text_'.$no.'_[]" id="working_text_hidden'.$working_content_id.'" type="hidden" class="input_working_content_'.$no.'" data-type="text">        
                                                                <a style="cursor: pointer; text-decoration: none;"
                                                                            class="removeWorkingContent text-danger-active fs18 ml-2" data-index="'.$working_content_id.'" data-id="text"><i
                                                                                class="fa fa-times"></i></a>    
                                                            </div>
                                                        </div>                        
                                                    </div>
                                                </div>';
    
                                            $working_content_id++;
                                            $working_content++;
                                        } else {

                                            echo '<div class="panel_input_working">
                                                <div class="col-lg-12">
                                                    <div class="form-inline">
                                                        <div class="form-group" style="width: 100%;text-align: center;">
                                                            <div class="fileinput fileinput-exists" data-provides="fileinput">
                                                                <div class="fileinput-preview thumbnail" data-trigger="fileinput" style="height: auto; width: 90%; padding: 0.5em;text-align: left;" class="form-control">
                                                                    <img src="'.$question_detail->branch_image_url.'/workingImage/'. $mcq_working_content->content_name.'" class="img-responsive">
                                                                </div>

                                                                <a style="cursor: pointer; text-decoration: none;" class="removeWorkingContent text-danger-active fs18 ml-2" data-index="0" data-id="image">
                                                                    <i class="fa fa-times"></i>
                                                                </a>   

                                                                <div>
                                                                    <span class="btn btn-default btn-file" style="display:none">
                                                                        <span class="fileinput-new">Select image</span>
                                                                        <span class="fileinput-exists">Change</span>
                                                                        <input type="file" class="form-control input_working_content_'.$no.'" data-type="image" name="input_working_image_'.$no.'_[]" required>
                                                                    </span>
                                                                        
                                                                    <a href="#" class="btn btn-default fileinput-exists" data-dismiss="fileinput" style="display:none">Remove</a>
                                                                </div>
                                                            </div>	  
                                                        </div>                                                                                                     
                                                    </div>
                                                </div>
                                            </div>';
    
                                            $working_content_id++;
                                            $working_content++;
                                        }
                                    }
                                }

                                echo '<input type="hidden" class="working_content_id" value="'.$working_content_id.'">';           
                                echo '<input type="hidden" class="working_content_counter" value="'.$working_content_id.'">';

                            } else if($question_detail->question_type_id == 2){
                                $working_content_id = 0;
                                $working_content = 0;
                                $no = 0;
                                
                                if($open_ended_working->working_text != '' && $open_ended_working->working_type=='text') {
                                
                                    echo '<div class="panel_input_working">
                                                    <div class="col-lg-12 mb-2">
                                                        <div class="form-inline">
                                                            <div class="form-group" style="width: 100%;text-align: center;">
                                                                <span id="working_text_'.$working_content_id.'" style="min-height: 50px; height: auto; width: 90%; text-align:left;" class="form-control math_text">
                                                                    <textarea style="box-shadow:none!important">'.reverseApplyMathJaxFormat($open_ended_working->working_text).'</textarea>
                                                                </span>
                                                                <input name="input_working_text_'.$no.'_[]" id="working_text_hidden'.$working_content_id.'" type="hidden" class="input_working_content_'.$no.'" data-type="text">    
                                                                <a style="cursor: pointer; text-decoration: none;"
                                                                            class="removeWorkingContent text-danger-active fs18 ml-2" data-index="'.$working_content_id.'" data-id="text"><i
                                                                                class="fa fa-times"></i></a>    
                                                            </div>
                                                        </div>                        
                                                    </div>
                                                </div>';         
    
                                    $working_content_id = 1;
                                    $working_counter = 1;
                                }
    
                                if($open_ended_working->working_text != '' && $open_ended_working->working_type=='image') {
                                    echo '<div class="panel_input_working">
                                                    <div class="col-lg-12 mb-2">
                                                        <div class="form-inline">
                                                            <div class="form-group" style="width: 100%;text-align: center;">
                                                            <input type="file" class="form-control input_working_content_'.$no.'" data-type="image" name="input_working_image_'.$no.'_[]" style="width: 90%; height: 40px; padding-top: 6px;" multiple="multiple" required />
                                                                <a style="cursor: pointer; text-decoration: none;"
                                                                            class="removeWorkingContent text-danger-active fs18 ml-2" data-index="'.$working_content_id.'" data-id="image"><i
                                                                                class="fa fa-times"></i></a>    
                                                            </div>
                                                        </div>                   
                                                    </div>
                                                </div>';
    
                                    $working_content_id = 1;
                                    $working_counter = 1;
                                }
    
                                if(isset($open_ended_working_contents) && count($open_ended_working_contents) > 0){
                                    foreach ($open_ended_working_contents as $open_ended_working_content) {
    
                                        if($open_ended_working_content->content_type == 'text'){
    
                                            echo '<div class="panel_input_working">
                                                    <div class="col-lg-12 mb-2">
                                                        <div class="form-inline">
                                                            <div class="form-group" style="width: 100%;text-align: center;">
                                                                <span id="working_text_'.$working_content_id.'" style="min-height: 50px; height: auto; width: 90%; text-align:left;" class="form-control math_text">
                                                                    <textarea style="box-shadow:none!important">'.reverseApplyMathJaxFormat($open_ended_working_content->content_name).'</textarea>
                                                                </span>
                                                                <input name="input_working_text_'.$no.'_[]" id="working_text_hidden'.$working_content_id.'" type="hidden" class="input_working_content_'.$no.'" data-type="text">        
                                                                <a style="cursor: pointer; text-decoration: none;"
                                                                            class="removeWorkingContent text-danger-active fs18 ml-2" data-index="'.$working_content_id.'" data-id="text"><i
                                                                                class="fa fa-times"></i></a>    
                                                            </div>
                                                        </div>                        
                                                    </div>
                                                </div>';
    
                                            $working_content_id++;
                                            $working_counter++;
                                        } else {
    
                                            echo '<div class="panel_input_working">
                                                    <div class="col-lg-12 mb-2">
                                                        <div class="form-inline">
                                                            <div class="form-group" style="width: 100%;text-align: center;">
                                                            <input type="file" class="form-control input_working_content_'.$no.'" data-type="image" name="input_working_image_'.$no.'_[]" style="width: 90%; height: 40px; padding-top: 6px;" multiple="multiple" required />
                                                                <a style="cursor: pointer; text-decoration: none;"
                                                                            class="removeWorkingContent text-danger-active fs18 ml-2" data-index="'.$working_content_id.'" data-id="image"><i
                                                                                class="fa fa-times"></i></a>    
                                                            </div>
                                                        </div>                   
                                                    </div>
                                                </div>';
    
                                            $working_content_id++;
                                            $working_counter++;
                                        }
                                    }
                                }

                                echo '<input type="hidden" class="working_content_id" value="'.$working_content_id.'">';           
                                echo '<input type="hidden" class="working_content_counter" value="'.$working_content_id.'">';
                            }
                            
                        ?>
                    
                    </div>
                    <!-- <input type="hidden" class="working_content_id" value="">                
                    <input type="hidden" class="working_content_counter" value=""> -->
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
                <div class="row panel_tag_label pb-20">
                    <div class="col-sm-6 col-md-6 col-lg-12">
                        <?php 
                            if (isset($question_tags) && $question_tags != ''){
                                echo '<input name="tagsinput_0" class="tagsinput" data-role="tagsinput" placeholder="eg: CA1, Mock Exam 2" value="' . $question_tags . '" style="display: none;">';
                            } else if (isset($selected_tags) && empty($selected_tags) === false) {
                                echo '<input name="tagsinput_0" class="tagsinput" data-role="tagsinput" placeholder="eg: CA1, Mock Exam 2" value="' . $selected_tags . '" style="display: none;">';
                            } else {
                                echo '<input name="tagsinput_0" class="tagsinput" data-role="tagsinput" placeholder="eg: CA1, Mock Exam 2" value="" style="display: none;">';
                            }
                        ?>
                    </div>
                </div>

                <h5 class="label-tags pb-2">[9] Disable Question <a class="fa_tag_label cursor_pointer"><i class="fa fa-angle-double-down"></i></a></h5>
                <div class="row panel_disable_question">
                    <div class="col-sm-6 col-md-6 col-lg-1">
                        <!-- <div class="customUi-checkbox checkboxUi-primary">
                            <input id="disable_question_0" type="checkbox" name="disable_question_0" value="<?php echo $question_detail->disabled; ?>" <?=($question_detail->disabled == 1)?'checked':''?> >
                            <label for="disable_question_0">
                                <span class="label-checkbox"></span>
                                <span class="label-helper fs14 font300">5</span>
                            </label>
                        </div>    -->

                        <div class="customUi-checkbox checkboxUi-primary">
                            <input id="disable_question_0" type="checkbox" class="disable_question" name="disable_question_0" value="1">
                            <label for="disable_question_0">
                                <span class="label-checkbox"></span>
                            </label>
                        </div>                           
                    </div>
                </div>

            </div>


        </div>

    </div>

<div class="pb-60"></div>

<div class="pb-60"></div>
