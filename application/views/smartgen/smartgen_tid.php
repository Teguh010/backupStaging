<style>
.switch {
  position: relative;
  display: inline-block;
  width: 100px;
  height: 34px;
}

.switch input {display:none;}

.slider {
  position: absolute;
  cursor: pointer;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: #E3423D;
  -webkit-transition: .4s;
  transition: .4s;
}

.slider:before {
  position: absolute;
  content: "";
  height: 26px;
  width: 26px;
  left: 4px;
  bottom: 4px;
  background-color: white;
  -webkit-transition: .4s;
  transition: .4s;
}

input:checked + .slider {
  background-color: #7DC835;
}

input:focus + .slider {
  box-shadow: 0 0 1px #2196F3;
}

input:checked + .slider:before {
  -webkit-transform: translateX(66px);
  -ms-transform: translateX(66px);
  transform: translateX(66px);
}

/*------ ADDED CSS ---------*/
.on
{
  display: none;
}

.on
{
  color: white;
  position: absolute;
  transform: translate(-50%,-50%);
  top: 50%;
  left: 33%;
  font-size: 12px;
}

.off
{
  color: white;
  position: absolute;
  transform: translate(-50%,-50%);
  top: 50%;
  right: -19%;
  font-size: 12px;
}

input:checked+ .slider .on
{display: block;}

input:checked + .slider .off
{display: none;}

/*--------- END --------*/

/* Rounded sliders */
.slider.round {
  border-radius: 34px;
}

.slider.round:before {
  border-radius: 50%;}
</style>

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



<div class="section">

    <div class="container">

        <div class="row text-center">

            <?php $this->load->helper('form'); ?>

            <div class="col-md-10 col-md-offset-1">

                <form action="<?php echo base_url(); ?>smartgen/creategenerateTID/<?php echo (isset($worksheetId) && empty($worksheetId) === false) ? $worksheetId : ''; ?>"
                    method="post" accept-charset="utf-8" class="form-horizontal worksheet_form">
                    
                    <div class="panel panel-success panel-success-custom-dark">
                        <input type="hidden" id="gen_difficulties" name="gen_difficulties[]" />
                        <input type="hidden" id="sess_user_lvl" name="sess_user_lvl" value="<?php echo $sess_user_level ?>"/>
                        <div class="panel-heading">Start Generating Worksheet</div>
                        <ul class="list-group">
                            <?php /*
                            <li class="list-group-item">
                                <div class="form-group">
                                    <label for="" class="control-label col-xs-12 col-sm-4 col-md-4 col-lg-4">Question Bank :</label>
                                    <div class="col-sm-7 col-md-7 col-lg-7">
                                        <div class="col-sm-5 col-md-5 col-lg-5">
                                            <label class="radio-inline"><input type="radio" name="gen_que_bank" class="public_type" value="public" checked>Public Question</label>
                                        </div>
                                        <div class="col-sm-7 col-md-7 col-lg-7">
                                            <label class="radio-inline"><input type="radio" name="gen_que_bank" class="private_type" value="private" <?php echo (BRANCH_NAME == 'ProLearn') ? '' : 'disabled' ?>>Private Question</label>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            */ ?>
                            <li class="list-group-item">
                                <div class="form-group">
                                    <label for="" class="control-label col-xs-12 col-sm-4 col-md-4 col-lg-4">Level & Subject :</label>
                                    <div class="col-sm-7 col-md-7 col-lg-7"> 
                                    <select id="gen_level" name="gen_level" class="form-control" onchange="getTopicLevelList(this.value)">
                                        <?php 

                                            echo '<option value="">Select Level & Subject</option>';
                                            foreach($levels as $level) {
                                                echo '<option value="' . $level->level_code . '">' . $level->level_name . '</option>';
                                            }
                                        ?>
                                        <!-- <option value="">Select Level & Subject</option>
                                        <option value="junior">Junior</option>
                                        <option value="S4">S4</option>
                                        <option value="S5">S5</option>
                                        <option value="S6">S6</option> -->
                                    </select>  
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
                                        <div class="col-xs-6 col-sm-4 col-md-4 col-lg-4">
                                            <select name="gen_topic[]" id="gen_topic_0" class="form-control gen_topic" placeholder="Please select Topic">
                                                <option value="all">Any topic</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group">                                    
                                        <label for="" class="control-label col-xs-12 col-sm-4 col-md-4 col-lg-4">Ability :</label>
                                        <div class="col-xs-6 col-sm-4 col-md-3 col-lg-4">
                                            <select name="gen_ability[]" id="gen_ability_0" class="form-control gen_ability" placeholder="Ability"> 
                                            <option value="all">Any ability</option>
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
                                        </select>
                                    </div>

                                    <!-- end question -->
                                    <div class="col-xs-6 col-sm-3 col-md-3 col-lg-3">
                                        <select name="gen_end_of_question[]" class="form-control gen_end_of_question" id="gen_end_of_question_0" data-id='0'>
                                            <option value="10">10</option>
                                        </select>
                                    </div>
                                </div>

                                <!-- question type  -->
                                <div class="form-group">
                                    <label for="gen_que_type_0" class="control-label col-xs-12 col-sm-4 col-md-4 col-lg-4">Question type :</label>
                                    <div class="col-sm-7 col-md-7 col-lg-7" style="padding-top: 5px; text-align: left;">
                                        <label class="switch">
                                            <input type="checkbox" id="cek_que_type_0" data-id="0" data-status="1" class="setActiveMCQ" id="togBtn" checked>
                                            <div class="slider round"><span class="on">MCQ</span><span class="off">Non-MCQ</span></div>
                                        </label>
                                    </div>
                                    <input type="hidden" name="gen_que_type[]" id="gen_que_type_0" value="1" />
                                </div>

                                <!-- Operator type  -->
                                <div class="form-group">
                                    <label for="gen_operator_0" class="control-label col-xs-12 col-sm-4 col-md-4 col-lg-4">Ability x Difficulty :</label>
                                    <div class="col-sm-7 col-md-7 col-lg-7" style="padding-top: 5px; text-align: left;">
                                        <label class="switch">
                                            <input type="checkbox" id="cek_operator_0" data-id="0" data-status="1" class="setActiveAND" checked>
                                            <div class="slider round"><span class="on">AND</span><span class="off" style="right: 25px;">OR</span></div>
                                        </label>
                                    </div>
                                    <input type="hidden" name="gen_operator[]" id="gen_operator_0" value="1" />
                                </div>

                                <!-- difficulty -->
                                <div class="form-group">
                                    <label for="gen_difficulties_0" class="control-label col-sm-4 col-md-4 col-lg-4">Difficulty Level :</label>
                                    <div class="col-sm-7 col-md-7 col-lg-7" style="padding-top: 10px; text-align: left;">
                                        <div class="col-sm-3 col-md-3 col-lg-3">
                                            <label style="font-weight: initial;"><input type="checkbox" id="gen_difficulties" name="gen_difficulties_0[]" class="gen_difficulties" value="1">  Easy</label>
                                        </div>

                                        <div class="col-sm-3 col-md-3 col-lg-3">
                                            <label style="font-weight: initial;"><input type="checkbox" id="gen_difficulties" name="gen_difficulties_0[]" class="gen_difficulties" value="2" checked> Normal</label>
                                        </div>

                                        <div class="col-sm-3 col-md-3 col-lg-3">
                                            <label style="font-weight: initial;"><input type="checkbox" id="gen_difficulties" name="gen_difficulties_0[]" class="gen_difficulties" value="3"> Hard</label>
                                        </div>

                                        <div class="col-sm-3 col-md-3 col-lg-3">
                                            <label style="font-weight: initial;"><input type="checkbox" id="gen_difficulties" name="gen_difficulties_0[]" class="gen_difficulties" value="4"> Genius</label>
                                        </div>
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