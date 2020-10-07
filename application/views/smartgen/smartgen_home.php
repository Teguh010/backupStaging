<?php 
    // Create Session Form
    $sessionUserID = '';
    $sessionWsQuesBank = 'public'; $sessionWsQueType = 1; $sessionWsDifficulty = '';
    $sessionWsSubject = ''; $sessionWsLevel = ''; $sessionWsSubstrand = ''; 
    $sessionWsTopic = ''; $sessionWsStrategy = '';
    if(@$this->session->userdata('user_id') !== null) $sessionUserID = $this->session->userdata('user_id');
    if(@$this->session->userdata('worksheetQuesBank') !== null) $sessionWsQuesBank = $this->session->userdata('worksheetQuesBank');
    if(@$this->session->userdata('worksheetSubject') !== null) $sessionWsSubject = $this->session->userdata('worksheetSubject');    
    if(@$this->session->userdata('worksheetSubject') !== null) $sessionWsLevel = $this->session->userdata('worksheetLevel');    
    if(@$this->session->userdata('worksheetSubstrand')[0] !== null) $sessionWsSubstrand = $this->session->userdata('worksheetSubstrand')[0];    
    if(@$this->session->userdata('worksheetTopic')[0] !== null) $sessionWsTopic = $this->session->userdata('worksheetTopic')[0];    
    if(@$this->session->userdata('worksheetStrategy') !== null) $sessionWsStrategy = $this->session->userdata('worksheetStrategy');
    if(@$this->session->userdata('worksheetQueType') !== null) $sessionWsQueType = $this->session->userdata('worksheetQueType');
    if(@$this->session->userdata('worksheetDifficulty')[0] !== null) $sessionWsDifficulty = $this->session->userdata('worksheetDifficulty')[0];
    
    echo "<script>   
            var user_id = '$sessionUserID';
            var session_wg_quesbank = '$sessionWsQuesBank';
            var session_wg_subject = '$sessionWsSubject';
            var session_wg_level = '$sessionWsLevel';    
            var session_wg_substrand = '$sessionWsSubstrand';
            var session_wg_topic = '$sessionWsTopic';
            var session_wg_strategy = '$sessionWsStrategy';
            var session_wg_quetype = '$sessionWsQueType';
            var session_wg_difficulty = '$sessionWsDifficulty';
        </script>";

    // End Create;
?>

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

                <form
                    action="<?php echo base_url(); ?>smartgen/generateWorksheet/<?php echo (isset($worksheetId) && empty($worksheetId) === false) ? $worksheetId : ''; ?>"
                    method="post" accept-charset="utf-8" class="form-horizontal worksheet_form">
                    <div class="panel panel-success panel-success-custom-dark">
                        <input type="hidden" id="gen_subject" name="gen_subject" /> 
                        <input type="hidden" id="gen_level" name="gen_level" />                        
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
                                <div class="form-group <?= form_error('gen_num_of_question') ? 'has-error' : '' ?>">
                                    <label for="gen_num_of_question" class="control-label col-sm-4 col-md-4 col-lg-4">Number of question:</label>
                                    <div class="col-sm-7 col-md-7 col-lg-7">
                                        <select name="gen_num_of_question" id="gen_num_of_question" class="form-control">
                                            <?php
                                                if($is_logged_in == '1'){
                                                    if (isset($selectedNumOfQuestion)) {
                                                        $optionArray = array('1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12', '13', '14', '15', '16', '17', '18', '19', '20');
                                                        foreach ($optionArray AS $option) {
                                                            if ($selectedNumOfQuestion == intval($option)) {
                                                                echo '<option value="' . $option . '" selected="selected">' . $option . '</option>';
                                                            } else {
                                                                echo '<option value="' . $option . '">' . $option . '</option>';
                                                            }
                                                        }
                                                    } else {
                                                ?>
                                                <option value="1">1</option>
                                                <option value="2">2</option>
                                                <option value="3">3</option>
                                                <option value="4">4</option>
                                                <option value="5">5</option>
                                                <option value="6">6</option>
                                                <option value="7">7</option>
                                                <option value="8">8</option>
                                                <option value="9">9</option>
                                                <option value="10" selected="selected">10</option>
                                                <option value="11">11</option>
                                                <option value="12">12</option>
                                                <option value="13">13</option>
                                                <option value="14">14</option>
                                                <option value="15">15</option>
                                                <option value="16">16</option>
                                                <option value="17">17</option>
                                                <option value="18">18</option>
                                                <option value="19">19</option>
                                                <option value="20">20</option>
                                                <?php
                                            	}
                                        	} else{
                                            ?>
												<option value="1">1</option>
												<option value="2">2</option>
												<option value="3">3</option>
												<option value="4">4</option>
												<option value="5">5</option>
												<option value="6">6</option>
												<option value="7">7</option>
												<option value="8">8</option>
												<option value="9">9</option>
												<option value="10" selected>10</option>
												<option value="11">11</option>
												<option value="12">12</option>
												<option value="13">13</option>
												<option value="14">14</option>
												<option value="15">15</option>
												<option value="16">16</option>
												<option value="17">17</option>
												<option value="18">18</option>
												<option value="19">19</option>
												<option value="20">20</option>
											<?php
											}
											?>
                                        </select>
                                        <!-- set_value('num_of_question') -->
                                    </div>
                                </div>
                            </li>

                            <li class="list-group-item">
                                <div class="form-group">
                                    <label for="" class="control-label col-xs-12 col-sm-4 col-md-4 col-lg-4">Worksheets :</label>
                                    <div class="col-sm-7 col-md-7 col-lg-7">
                                        <?php
                                        if (isset($selectedLevel) && empty($selectedLevel) === false) {
                                            // TODO : check the session to set the relevant levels
                                            echo '<select name="gen_tag" class="form-control" id="gen_tag">';
                                            echo '<option value="all" selected="selected">Not Selected</option>';
                                            foreach ($question_tags AS $question_tag) {
                                                if ($selectedTags == $question_tag->CASA) {
                                                    echo '<option value="' . $question_tag->admin_worksheet_id . '" >' . $question_tag->admin_worksheet_name . '</option>';
                                                } else {
                                                    echo '<option value="' . $question_tag->admin_worksheet_id . '">' . $question_tag->admin_worksheet_name . '</option>';
                                                }
                                            }
                                            echo '</select>';
                                        } else {
                                            ?>
                                            <select name="gen_tag" class="form-control" id="gen_tag">
                                            <option value="all" selected="selected">Not Selected</option>
                                                <?php
                                                foreach ($question_tags AS $question_tag) {
                                                    echo '<option value="' . $question_tag->admin_worksheet_id . '">' . $question_tag->admin_worksheet_name . '</option>';
                                                }
                                                ?>
                                            </select>
                                            <?php
                                        }
                                        ?>
                                    </div>
                                </div>
                            </li>                                                    

                            <li class="list-group-item">
                                <div class="form-group">
                                    <label for="" class="control-label col-xs-12 col-sm-4 col-md-4 col-lg-4">Level & Subject:</label>
                                    <div class="col-sm-7 col-md-7 col-lg-7">
                                        <select id="gen_subjectlevel" name="gen_subjectlevel" placeholder="Please type or select Level - Subject"></select>                                        
                                    </div>
                                </div>
                            </li>

                            <li class="list-group-item">
                                <div class="form-group topic-group">
                                    <label for="" class="control-label col-xs-12 col-sm-4 col-md-4 col-lg-4">Topics :</label>                                
                                    <div class="col-xs-6 col-sm-3 col-md-3 col-lg-3">
                                        <select name="gen_substrand[]" class="substrand_select" id="substrand_select" placeholder="Please select Strands">                                                
                                        </select>
                                    </div>
                                    <div class="col-xs-6 col-sm-4 col-md-4 col-lg-4">
                                        <select name="gen_topic[]" class="topic_select" id="topic_select" placeholder="Please select Topic">                                                                                        
                                        </select>
                                    </div>
                                </div>
                            </li>
                            
                            <li class="list-group-item">
                                <div class="form-group">
                                    <label for="" class="control-label col-xs-12 col-sm-4 col-md-4 col-lg-4">Learning Objectives :</label>
                                    <div class="col-xs-6 col-sm-3 col-md-3 col-lg-3">                                        
                                        <select name="gen_heuristic" id="gen_heuristic" placeholder="Please select Heuristic">  
                                        </select>                                          
                                    </div>
                                    <div class="col-xs-6 col-sm-4 col-md-4 col-lg-4">
                                        <select name="gen_strategy" id="gen_strategy" placeholder="Please select Strategy">                                                                                        
                                        </select>
                                    </div>
                                </div>
                            </li>

                            <li class="list-group-item">
                                <div class="form-group">
                                    <label for="" class="control-label col-xs-12 col-sm-4 col-md-4 col-lg-4">Question Types :</label>
                                    <div class="col-sm-7 col-md-7 col-lg-7" style="padding-top: 10px;">
                                    	<div class="col-sm-5 col-md-5 col-lg-5">
                                    		<label class="radio-inline"><input type="radio" name="gen_que_type" class="mcq_type" value="1" checked="">MCQ Question</label>
                                    	</div>
                                    	<div class="col-sm-7 col-md-7 col-lg-7">
                                    		<label class="radio-inline"><input type="radio" name="gen_que_type" class="non_mcq_type" value="2">Non-MCQ Question</label>
                                    	</div>
                                    </div>
                            </li>                                                    

                            <li class="list-group-item">
                                <div class="form-group <?php echo form_error('gen_difficulties') ? 'has-error' : '' ?>">
                                    <label for="gen_difficulties" class="control-label col-sm-4 col-md-4 col-lg-4">Difficulty Level :</label>
                                    <!-- <div class="col-sm-7 col-md-7 col-lg-7" style = "padding-top: 10px;">
                                        <div class="col-sm-3 col-md-3 col-lg-3">
                                            <label style="font-weight: initial;"><input type="checkbox" name="gen_difficulties[]" id="gen_difficulties[]" value="1">  Easy</label>
                                        </div>

                                        <div class="col-sm-3 col-md-3 col-lg-3">
                                            <label style="font-weight: initial;"><input type="checkbox" name="gen_difficulties[]" id="gen_difficulties[]" value="2" checked> Normal</label>
                                        </div>

                                        <div class="col-sm-3 col-md-3 col-lg-3">
                                            <label style="font-weight: initial;"><input type="checkbox" name="gen_difficulties[]" id="gen_difficulties[]" value="3"> Hard</label>
                                        </div>

                                        <div class="col-sm-3 col-md-3 col-lg-3">
                                            <label style="font-weight: initial;"><input type="checkbox" name="gen_difficulties[]" id="gen_difficulties[]" value="4"> Genius</label>
                                        </div>

                                    </div> -->

                                    <div class="col-sm-7 col-md-7 col-lg-7">
                                        <input type="range" min="0" max="100" step="1" data-thumbwidth="20"
                                               value="<?php echo (isset($selectedDifficulty)) ? intval($selectedDifficulty) : 50; ?>"
                                               name="gen_difficulties" id="gen_difficulties_form">
                                        <output for="gen_difficulties" id="gen_difficulties_output"><?php echo (isset($selectedDifficultyOutput)) ? $selectedDifficultyOutput : "Intermediate"; ?></output>
                                    </div>

                                </div>
                            </li>

                            <div class="row">
                                <div
                                    class="col-xs-4 col-sm-2 col-md-2 col-lg-2 col-xs-offset-2 col-sm-offset-4 col-md-offset-4 col-lg-offset-4">
                                    <input type="submit" class="btn btn-custom" value="Generate" id="gen_button">
                                </div>

                                <div class="col-xs-4 col-sm-2 col-md-2 col-lg-2">
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