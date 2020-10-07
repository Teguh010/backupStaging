<div class="section">
    <div class="container">
        <div class="row">
            <div class="col-xs-12 col-sm-5 col-md-5 col-lg-5">
                <img src="<?php echo base_url(); ?>img/img2.png" class="center-block img-responsive margin-top-custom">
            </div>
            <div class="col-xs-12 col-sm-7 col-md-7 col-lg-7">
                <h1>SmartGen</h1>
                <p>With the latest technology of SmartGen now, you can ‘smartly generate’ practice questions online
                    instead of traditional way. Do not worry about the quiz being too easy or too difficult because each
                    topic is preset with thousands of questions in different ranges of difficulty. Simply click on the
                    topic, level and adjust the scale of difficulty and you will find practice questions specifically
                    targeted at your levels of need. You can also save these questions, assign them to students and
                    generate progress reports later.</p>
            </div>
        </div>
    </div>
</div>

<div class="section">
    <div class="container">
        <div class="row text-center">
            <div class="col-md-8 col-md-offset-2">
                <ol class="smartgen-progress">
                    <li class="is-active" data-step="1">
                        <a href="#">Design</a>
                    </li>
                    <li data-step="2">
                        Customize
                    </li>
                    <li data-step="3" class="smartgen-progress__last">
                        Assign
                    </li>
                </ol>
            </div>
        </div>
    </div>
</div>

<style>

*:after {
  box-sizing: border-box;
}

input[type=range] {
  display: block;
  width: 100%;
  margin: 0;
  -webkit-appearance: none;
  outline: none;
  padding-top: 15px;
}

input[type=range]::-webkit-slider-runnable-track {
  position: relative;
  height: 12px;
  border: 1px solid #b2b2b2;
  border-radius: 5px;
  background-color: #e2e2e2;
  box-shadow: inset 0 1px 2px 0 rgba(0, 0, 0, 0.1);
}

input[type=range]::-webkit-slider-thumb {
  position: relative;
  top: 2px;
  width: 20px;
  height: 20px;
  border: 1px solid #999;
  -webkit-appearance: none;
  background-color: #2ABB9B;
  box-shadow: inset 0 -1px 2px 0 rgba(0, 0, 0, 0.25);
  border-radius: 100%;
  cursor: pointer;
}

output {
  background: #2ABB9B;
  display: none;
  color: white;
  padding: 4px 12px;
  position: absolute;
  margin-top: 5px;
  border-radius: 4px;
  left: 50%;
  transform: translateX(-50%);
}
output::after {
  content: "";
  position: absolute;
  width: 2px;
  height: 2px;
  background: #2ABB9B;
  top: -1px;
  left: 50%;
}

input[type=range]:active + output {
  display: block;
  -webkit-transform: translateX(-50%);
          transform: translateX(-50%);
}
</style>

<div class="section">

    <div class="container">

        <div class="row text-center">

            <?php $this->load->helper('form'); ?>

            <div class="col-md-10 col-md-offset-1">

                <form action="<?php echo base_url(); ?>smartgen/creategenerateExam/<?php echo (isset($worksheetId) && empty($worksheetId) === false) ? $worksheetId : ''; ?>"
                    method="post" accept-charset="utf-8" class="form-horizontal worksheet_form">
                    
                    <div class="panel panel-success panel-success-custom-dark">
                        <input type="hidden" id="gen_subject" name="gen_subject" />
                        <input type="hidden" id="gen_level" name="gen_level" />
                        <input type="hidden" id="gen_que_type" name="gen_que_type[]" />
                        <input type="hidden" id="gen_difficulties" name="gen_difficulties[]" />
                        <div class="panel-heading">Start Generating Worksheet</div>
                        <ul class="list-group">

                            <li class="list-group-item">
                                <div class="form-group">
                                    <label for="" class="control-label col-xs-12 col-sm-4 col-md-4 col-lg-4">Question Bank :</label>
                                    <div class="col-sm-7 col-md-7 col-lg-7">
                                    	<div class="col-sm-5 col-md-5 col-lg-5">
                                    		<label class="radio-inline"><input type="radio" name="gen_que_bank" class="public_type" value="public" checked>Public Question</label>
                                    	</div>
                                    	<div class="col-sm-7 col-md-7 col-lg-7">
                                    		<label class="radio-inline"><input type="radio" name="gen_que_bank" class="private_type" value="private">Private Question</label>
                                    	</div>
                                    </div>
                                </div>
                            </li>

                            <li class="list-group-item">
                                <div class="form-group">
                                    <label for="" class="control-label col-xs-12 col-sm-4 col-md-4 col-lg-4">Level & Subject :</label>
                                    <div class="col-sm-7 col-md-7 col-lg-7">
                                        <select id="gen_subjectlevel" name="gen_subjectlevel" placeholder="Please type or select Level - Subject"></select>
                                    </div>
                                </div>
                            </li>

                            <!-- <li class="list-group-item">
                                <div class="form-group">
                                    <label for="" class="control-label col-xs-12 col-sm-4 col-md-4 col-lg-4">Worksheets :</label>
                                    <div class="col-sm-7 col-md-7 col-lg-7">                                        
                                        <select name="gen_tag" class="form-control" id="gen_tag">
                                        <option value="all" selected="selected">Not Selected</option>
                                        <?php
                                            // foreach ($question_tags AS $question_tag) {
                                            //     echo '<option value="' . $question_tag->CASA . '">' . $question_tag->CASA . '</option>';
                                            // }
                                        ?>
                                        </select>                                            
                                    </div>
                                </div>
                            </li> -->

                            <li class="list-group-item">
                                <div class="form-group">
                                    <label for="gen_num_of_question" class="control-label col-xs-12 col-sm-4 col-md-4 col-lg-4">Number of Questions :</label>
                                    <div class="col-sm-7 col-md-7 col-lg-7">                                        
                                        <select name="gen_num_of_question" class="form-control" id="gen_num_of_question">
                                            <?php
                                                for($i=1 ; $i<=50 ; $i++){
                                                    if($i == 10){
                                                        echo "<option value='10' selected>10</option>";
                                                    }else{
                                                        echo "<option value='$i'>$i</option>";
                                                    }
                                                }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                            </li>

                            <!-- <li class="list-group-item">
                                <div class="form-group">
                                    <label for="" class="control-label col-xs-12 col-sm-4 col-md-4 col-lg-4">Number of Attempts :</label>
                                    <div class="col-sm-7 col-md-7 col-lg-7">                                        
                                        <select name="gen_num_of_attempt" class="form-control" id="gen_num_of_attempt">
                                            <option value="1" selected="selected">1</option>
                                            <option value="2">2</option>
                                            <option value="3">3</option>
                                            <option value="4">4</option>
                                            <option value="5">5</option>
                                        </select>
                                    </div>
                                </div>
                            </li> -->

                            <li class="list-group-item">
                                <div class="form-group">
                                    <label for="" class="control-label col-xs-12 col-sm-4 col-md-4 col-lg-4">Quiz Time :</label>
                                    <div class="col-sm-7 col-md-7 col-lg-7">
                                        <select name="gen_quiz_time" class="form-control" id="gen_quiz_time">
                                            <option value="30" selected="selected">30 min</option>
                                            <option value="45">45 min</option>
                                            <option value="60">1 hour</option>
                                            <option value="90">1.5 hour</option>
                                            <option value="120">2 hour</option>
                                        </select>
                                    </div>
                                </div>
                            </li>

                            <li class="list-group-item add_session_group" id="add_session_group_0">
                                <div class="session-group">
                                    <div class="form-group">
                                        <label for="" class="control-label col-xs-12 col-sm-4 col-md-4 col-lg-4">Topics :</label>                                
                                        <div class="col-xs-6 col-sm-3 col-md-3 col-lg-3">
                                            <select name="gen_substrand[]" class="gen_substrand" placeholder="Please select Strands">                                                
                                            </select>
                                        </div>
                                        <div class="col-xs-6 col-sm-4 col-md-4 col-lg-4">
                                            <select name="gen_topic[]" class="gen_topic" placeholder="Please select Topic">                                                                                        
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group learning_objective">                                    
                                        <label for="" class="control-label col-xs-12 col-sm-4 col-md-4 col-lg-4">Learning Objectives :</label>
                                        <div class="col-xs-6 col-sm-3 col-md-3 col-lg-3">
                                            <select name="gen_heuristic[]" class="gen_heuristic" placeholder="Heuristic">
                                            </select>                                          
                                        </div>
                                        <div class="col-xs-6 col-sm-4 col-md-4 col-lg-4">
                                            <select name="gen_strategy[]" class="gen_strategy" placeholder="Strategy">
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <!-- no of question -->
                                <div class="form-group">
                                    <label for="" class="control-label col-xs-12 col-sm-4 col-md-4 col-lg-4">Start - End of Question :</label>
                                    <!-- start question -->
                                    <div class="col-xs-6 col-sm-3 col-md-3 col-lg-3">
                                        <select name="gen_start_of_question[]" class="form-control gen_start_of_question" id="gen_start_of_question_0">
                                            <option value="1">1</option>
                                            <?php 
                                                // $numberArray = range(1, 20);
                                                // foreach($numberArray as $number) {
                                                //     if($number == 1) {
                                                //         echo '<option value="' . $number . '" selected="selected">' . $number . '</option>';
                                                //     } else {
                                                //         echo '<option value="' . $number . '" disabled>' . $number . '</option>';
                                                //     }
                                                // }
                                            ?>
                                        </select>
                                    </div>

                                    <!-- end question -->
                                    <div class="col-xs-6 col-sm-3 col-md-3 col-lg-3">
                                        <select name="gen_end_of_question[]" class="form-control gen_end_of_question" id="gen_end_of_question_0" data-id='0'>
                                            <option value="10">10</option>
                                            <?php 
                                                // $numberArray = range(1, 20);
                                                // foreach($numberArray as $number) {
                                                //     if($number == 5) {
                                                //         echo '<option value="' . $number . '" selected="selected">' . $number . '</option>';
                                                //     } else {
                                                //         echo '<option value="' . $number . '">' . $number . '</option>';
                                                //     }
                                                // }
                                            ?>
                                        </select>
                                    </div>
                                </div>

                                <!-- question type  -->
                                <div class="form-group question_types">
                                    <label for="gen_que_type_0" class="control-label col-xs-12 col-sm-4 col-md-4 col-lg-4">Question Types :</label>
                                    <div class="col-sm-7 col-md-7 col-lg-7" style="padding-top: 5px; text-align: left;">
                                        <div class="col-sm-3 col-md-3 col-lg-3">
                                    		<label class="radio-inline"><input type="radio" name="gen_que_type_0[]" class="mcq_type gen_que_type" value="1" checked>MCQ</label>
                                    	</div>
                                    	<div class="col-sm-3 col-md-3 col-lg-3">
                                    		<label class="radio-inline"><input type="radio" name="gen_que_type_0[]" class="non_mcq_type gen_que_type" value="2">Non-MCQ</label>
                                    	</div>
                                    </div>
                                </div>

                                <!-- difficulty -->
                                <div class="form-group difficulty_level">
                                    <label for="gen_difficulties_0" class="control-label col-sm-4 col-md-4 col-lg-4">Difficulty Level :</label>
                                    <!-- <div class="col-sm-7 col-md-7 col-lg-7" style="padding-top: 10px; text-align: left;">
                                        <div class="col-sm-3 col-md-3 col-lg-3">
                                            <label style="font-weight: initial;"><input type="checkbox" name="gen_difficulties_0[]" class="gen_difficulties" value="1">  Easy</label>
                                        </div>

                                        <div class="col-sm-3 col-md-3 col-lg-3">
                                            <label style="font-weight: initial;"><input type="checkbox" name="gen_difficulties_0[]" class="gen_difficulties" value="2" checked> Normal</label>
                                        </div>

                                        <div class="col-sm-3 col-md-3 col-lg-3">
                                            <label style="font-weight: initial;"><input type="checkbox" name="gen_difficulties_0[]" class="gen_difficulties" value="3"> Hard</label>
                                        </div>

                                        <div class="col-sm-3 col-md-3 col-lg-3">
                                            <label style="font-weight: initial;"><input type="checkbox" name="gen_difficulties_0[]" class="gen_difficulties" value="4"> Genius</label>
                                        </div>
                                    </div> -->

                                    <div class="col-sm-7 col-md-7 col-lg-7">
                                        <input type="range" min="0" max="100" step="1" data-thumbwidth="20"
                                               value="<?php echo (isset($selectedDifficulty)) ? intval($selectedDifficulty) : 50; ?>"
                                               name="gen_difficulties_0" id="gen_difficulties_form">
                                        <output for="gen_difficulties" id="gen_difficulties_output"><?php echo (isset($selectedDifficultyOutput)) ? $selectedDifficultyOutput : "Intermediate"; ?></output>
                                    </div>
                                </div>   

                                <div class="form-group">                                
                                    <div class="col-sm-12 col-md-12 col-lg-12 text-left">
                                        <a style="cursor: pointer;" class="add_session text-success-active fs14"><i class="fa fa-plus mr-1"></i> Add Section</a>
                                    </div>
                                </div>                             

                            </li>                            

                            <div class="row">
                                <div class="col-sm-12 col-md-12 col-lg-12 text-center">
                                    <input type="submit" class="btn btn-custom" value="Generate" id="gen_button">
                                    <input type="reset" class="btn btn-default" value="Reset" id="reset_button">
                                </div>
                            </div>

                        </ul>

                    </div>

                    <!-- <h3>Start Generating Worksheet</h3> -->

                </form>

            </div>



        </div>

    </div>

</div>