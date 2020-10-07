<?php
	//echo "<script>var target_id='$target_id';</script>";	
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
		<div class="col-md-8 col-md-offset-2">
			<ol class="smartgen-progress">
				<li class="is-complete" data-step="1">
					<a href="<?php echo base_url(); ?>smartgen">Design</a>
				</li>
				<li class="is-active" data-step="2">
					<a href="#">Customize</a>
				</li>
				<li data-step="3" class="smartgen-progress__last">
					Assign
				</li>
			</ol>
		</div>
	</div>
</div>

<div class="section">
	<div class="container">
		<div class="row">
			<div class="panel panel-success panel-success-custom-dark">
				<!-- Default panel contents -->
				<div class="panel-heading clearfix">
					Generated Question
					<form class="form-horizontal"
						action="<?php echo base_url(); ?>ExamMode/regenerateAllQuestion/<?php echo $this->session->userdata('requirementId'); ?>"
						method="post" accept-charset="utf-8">
						<input name="regenerateWorksheet" type="submit" class="btn btn-custom btn-no-margin pull-right"
							value="Regenerate all">
					</form>
				</div>
				<div class="smartgen-pre-group">
					<ul class="list-group smartgen-list-group">
						<?php
							$subject_type = ''; $level_id = '';
							$num_of_question = count($questionList);
							$quesNum = 1;
							if(sizeof($questionList) == 0){
								echo '<div class="student-row clearfix">';
									echo '<div class="col-xs-offset-1 col-sm-offset-1 col-md-offset-1 col-lg-offset-1 col-xs-10 col-sm-10 col-md-10 col-lg-10">';
										echo '<div class="alert alert-danger margin-top-custom text-center">Don\'t have any question meet the criteria in the worksheet.</div>';
									echo '</div>';
								echo '</div>';
							}
							foreach ($questionList AS $question) {
								$subject_type = $question->subject_type;
								$level_id = $question->level_id;
								if($question->difficulty == 1) {
									$difficulty = 'Easy';
								} else if ($question->difficulty == 2) {
									$difficulty = 'Normal';
								} else if ($question->difficulty == 3) {
									$difficulty = 'Hard';
								} else {
									$difficulty = 'Genius';
								}
								$close_btn = '';
								$dropdown = "<select name='question_".$quesNum."' class='question_order question_".$question->question_id."' data-id='".$question->id."' data-question_id='".$question->question_id."' data-number='".$question->question_number."'>";
								
								for($i = 1; $i <= $num_of_question; $i++ ){
									$dropdown .= "<option value='".$i."' ".(($i == $question->question_number)?'selected':'')."> Question ".$i."</option>";
								}
								$dropdown .= "</select>";
								
								if($num_of_question> 1){
									//$close_btn = `<a href="#" class="question-remove" onClick="remove_question(`.$question->id.`, `.$question->question_number.`, 'addNewSubQuestionDiv_`.$quesNum.`')" title="Remove Question" data-id = "`.$question->question_id.`"><i class="fa fa-times"></i></a>`;
									$close_btn = '<a style="cursor: pointer;" class="question-remove" onClick="removeQuestion('.$question->id.', '.$question->question_number.', '.$quesNum.')" title="Remove Question"><i class="fa fa-times"></i></a>';
								}
								
								echo '<div id="addNewSubQuestionDiv_'.$quesNum.'">';
								echo '<li class="list-group-item clearfix" id="question_'.$quesNum.'">';

								if($this->session->userdata('worksheetSubject') == 7) {
									echo '<div class="question_number" style="height:auto;">Question '. $quesNum .' <span class="pull-right question_category"></span><br><span class="question_strategy">'.$strategyList[$quesNum-1].'</span></div>';
								} else {
									if(empty($strategyList[$quesNum-1])){
										echo '<div class="question_number question_number_with_strategy"> '. $dropdown .'
												<span class="pull-right question_category">[' . $substrandList[$quesNum - 1] . '] ' . $categoryList[$quesNum-1].$close_btn.'</span>
												<br>
												<span class="question_strategy"></span>
												<br>
												<span class="pull-left question_difficulty">('.$question->difficulty.' Marks)</span>											
										</div>';
									} else {
										echo '<div class="question_number question_number_with_strategy"> '. $dropdown .' 											
												<span class="pull-right question_category">[' . $substrandList[$quesNum - 1] . '] ' . $categoryList[$quesNum-1].$close_btn.'</span>
												<br>
												<span class="question_strategy">'.$strategyList[$quesNum-1].'</span>
												<br>
												<span class="pull-left question_difficulty question_difficulty_with_padding">('.$question->difficulty.' Marks)</span>											
										</div>';
									}
								}

								echo '<div class="question_text">';

								if($question->question_content == 0){
									echo $question->question_text;
									if ($question->graphical != "0") {
										echo '<div><img src="'.$question->branch_image_url.'/questionImage/'.$question->graphical.'" draggable="false" class="img-responsive" style="max-width:60%;"></div>';
									}
								} else {
									if($question->content_type == 'text'){
										echo $question->question_text;
									} else {
										echo '<div><img src="'.$question->branch_image_url.'/questionImage/'.$question->question_text.'" draggable="false" class="img-responsive" style="max-width:60%;"></div>';
									}

									$questionContents = $questionContents[$quesNum-1]['question_content'];
		
									foreach ($questionContents as $questionContent) {
										
										if($questionContent->content_type == 'text'){
											echo $questionContent->content_name;
											echo '<br>';
										} else {
											echo '<div><img src="'.$question->branch_image_url.'/questionImage/'.$questionContent->content_name.'" draggable="false" class="img-responsive" style="max-width:60%;"></div>';
											echo '<br>';
										}
									}


								}
								

								echo '<div class="question_answer">';
								if ($question->question_type == 1) {
									$mcqCount = 1;
									$answerOption = $answerList[$quesNum-1]['answerOption'];
		
									foreach ($answerOption as $option) {
										$correctAnswer = $answerList[$quesNum-1]['correctAnswer'];

										$class = "";

										$icon = "";

										if($correctAnswer == $option->answer_text) {
											$class .= "correctAnswer ";
											$icon .= "<i class='fa fa-check answeredCorrectly'></i>";
										}

										echo '<span class="'.$class.'">' . $mcqCount . ') ' . $option->answer_text . '</span>' . $icon . '<br>';
		
										$mcqCount++;
									}
									echo '</div>';
									echo '</div>';
									
									echo '<button class="btn btn-danger pull-right flag_question" data-toggle="modal" data-target="#flagQuestionModal" id="qid_'.$question->question_id.'"><span data-toggle="tooltip" data-placement="top" title="Error in question? Flag now!">Flag</span></button>';
									if($this->session->userdata('worksheetSubject') != 7) {
										echo '<button class="btn btn-custom pull-right regen_question" id="regen_'.$quesNum.'" data-id="'.$question->id.'" data-question_number="'.$question->question_number.'" data-type="'.$question->question_type.'" data-toggle="tooltip" data-placement="top" title="Regenerate this question, please">Regenerate</button>';
									} else {
										if($this->session->userdata('user_id') == 121) {
											echo '<a href="'.base_url().'smartgen/regenerateQuestion" id="regen_'.$quesNum.'" class="btn btn-custom pull-right regen_question" data-toggle="tooltip" data-placement="top" title="Regenerate this question, please">Regenerate</a>';
										}
									}
									echo '<span class="btn btn-info pull-right que_type mcq_type_'.$quesNum.'" data-toggle="tooltip" data-placement="top" title="This question is only available in MCQ">MCQ</span>';
									echo '<button class="btn btn-primary pull-right edit_question" id="edit_question_'.$quesNum.'" data-toggle="modal" data-target="#form-edit" data-id="'.$question->id.'" data-requirement_id="'.$question->requirement_id.'" 
											data-level_id="'.$question->level_id.'" 
											data-subject="'.$question->subject_type.'" 
											data-type="'.$question->question_type.'" 
											data-substrands="'.$substrandList[$quesNum - 1].'" 
											data-topic="'.$categoryList[$quesNum-1].'" 
											data-strategy="'.$strategyList[$quesNum-1].'" 
											data-section="'.$quesNum.'">
											<span data-toggle="tooltip" data-placement="top" title="Edit question">Edit</span></button>';
									$lineBreak = $question->difficulty * 2;
									if($subquestionList[$quesNum - 1] === 'B'){
										echo '<button class="btn btn-warning pull-right sub_question" id="subqid_'.$question->question_id.'"><span data-toggle="tooltip" data-placement="top" title="Add sub question, please">Sub Question</span></button>';
										echo '<input type="hidden" name="ques_difficulty" id="ques_difficulty" value="' . $lineBreak . '">';
										echo '<div style="padding-top: 1em; padding-left: 1em;" class="ques_line_break">';
										echo 'No of Line Break : <input name="ques_line_break" id="'.$question->question_id.'_line_break" type="number" value="' . $lineBreak . '" min="1" max="10" style="width: 5%;">';
										echo '</div>';
										echo '</li>';
										echo '</div>';
									}else {
										echo '<input type="hidden" name="ques_difficulty" id="ques_difficulty" value="' . $lineBreak . '">';
										echo '<div style="padding-top: 1em; padding-left: 1em;" class="ques_line_break">';
										echo 'No of Line Break : <input name="ques_line_break" id="'.$question->question_id.'_line_break" type="number" value="' . $lineBreak . '" min="1" max="10" style="width: 5%;">';
										echo '</div>';
										echo '</li>';
										echo '</div>';
									}	
								}else if($question->question_type == 2){

									$answerOption = $answerList[$quesNum-1]['answerOption'];
									foreach ($answerOption as $key=>$option) {
										$correctAnswer = $answerList[$quesNum-1]['correctAnswer'];
										$class = "";
										$icon = "";
										if($correctAnswer == $option->answer_text) {
											$class .= "correctAnswer ";
											$icon .= "<i class='fa fa-check answeredCorrectly'></i>";
											echo '<br><span class="'.$class.'">Ans: ' . $option->answer_text . '</span>' . $icon;
										}
									}
									// switch ($question->difficulty) {
									// 	case 2:
									// 		echo '<br><br><br><br><br>';
									// 		break;
									// 	case 3:
									// 		echo '<br><br><br><br><br><br><br><br><br>';
									// 		break;
									// 	case 4:
									// 		echo '<br><br><br><br><br><br><br><br><br><br><br><br><br><br>';
									// 		break;
									// 	case 5:
									// 		echo '<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>';
									// 		break;
									// 	default:
									// 		break;
									// }
									//echo '<div class="pull-right">Ans: _____________________________ </div>';
									echo '</div>';
									echo '</div>';
									echo '<button class="btn btn-danger pull-right flag_question" data-toggle="modal" data-target="#flagQuestionModal" id="qid_'.$question->question_id.'"><span data-toggle="tooltip" data-placement="top" title="Error in question? Flag now!">Flag</span></button>';
									echo '<button id="regen_'.$quesNum.'" data-id="'.$question->id.'" data-question_number="'.$question->question_number.'" data-type="'.$question->question_type.'" class="btn btn-custom pull-right regen_question" data-toggle="tooltip" data-placement="top" title="Regenerate this question, please">Regenerate</button>';
									echo '<span class="btn btn-info pull-right que_type mcq_type_'.$quesNum.'" data-toggle="tooltip" data-placement="top" title="This question is only available in Non-MCQ">Non-MCQ</span>';
									echo '<button class="btn btn-primary pull-right edit_question" id="edit_question_'.$quesNum.'" data-toggle="modal" data-target="#form-edit" data-id="'.$question->id.'" data-requirement_id="'.$question->requirement_id.'" 
											data-level_id="'.$question->level_id.'" 
											data-subject="'.$question->subject_type.'" 
											data-type="'.$question->question_type.'" 
											data-substrands="'.$substrandList[$quesNum - 1].'" 
											data-topic="'.$categoryList[$quesNum-1].'" 
											data-strategy="'.$strategyList[$quesNum-1].'" 
											data-section="'.$quesNum.'">
											<span data-toggle="tooltip" data-placement="top" title="Edit question">Edit</span></button>';
									$lineBreak = $question->difficulty * 2;
									if($subquestionList[$quesNum - 1] === 'B'){
										echo '<button class="btn btn-warning pull-right sub_question" id="subqid_'.$question->question_id.'"><span data-toggle="tooltip" data-placement="top" title="Add sub question, please">Sub Question</span></button>';
										echo '<input type="hidden" name="ques_difficulty" id="ques_difficulty" value="' . $lineBreak . '">';
										echo '<div style="padding-top: 1em; padding-left: 1em;" class="ques_line_break">';
										echo 'No of Line Break : <input name="ques_line_break" id="'.$question->question_id.'_line_break" type="number" value="' . $lineBreak . '" min="1" max="10" style="width: 5%;">';
										echo '</div>';
										echo '</li>';
										echo '</div>';
									}else {
										echo '<input type="hidden" name="ques_difficulty" id="ques_difficulty" value="' . $lineBreak . '">';
										echo '<div style="padding-top: 1em; padding-left: 1em;" class="ques_line_break">';
										echo 'No of Line Break : <input name="ques_line_break" id="'.$question->question_id.'_line_break" type="number" value="' . $lineBreak . '" min="1" max="10" style="width: 5%;">';
										echo '</div>';
										echo '</li>';
										echo '</div>';
									}
								}							
								$quesNum++;
							}

						?>
					</ul>
				</div>

				<div id="correctAnswer" class="hidden">
					<?php 
			    		$ansNum = 1;
			    		foreach ($answerList as $answer) {
			    			# code...
			    			echo '<div id="correct_answer_' . $ansNum . '" class="correctAnswerText">';
			    			echo '('. $answer['correctAnswerOptionNum'] . ') ' . $answer['correctAnswer'];
			    			echo '</div>';
			    			$ansNum++;
			    		}
			    	?>
				</div>
				<?php
						if ($isLoggedIn) {
							echo '<div class="text-center">';
							//provide save and save as option
							if (isset($worksheetId) && empty($worksheetId) === false) {
								//echo '<a href="'.base_url().'smartgen/saveExistingWorksheet/'.$worksheetId.'" class="btn btn-custom">Save</a>';
								echo '<input type="submit" class="btn btn-custom" value="Save As New Worksheet" id="gen_button" data-toggle="modal" data-target="#saveWorksheetModal">';
								// echo '<form method="post" action="'.base_url().'smartgen/saveWorksheetAsPDF/'.$worksheetId.'" class="save_worksheet_form">';
							} else {
								echo '<input type="submit" class="btn btn-custom" value="Save Worksheet to Profile" id="gen_button" data-toggle="modal" data-target="#saveWorksheetModal">';
								// echo '<form method="post" action="'.base_url().'smartgen/saveWorksheetAsPDF" class="save_worksheet_form">';
							}

							echo '<form target="_blank" method="post" action="' . base_url() . 'smartgen/outputPdf" id="outputPDF">';
							echo '<input type="hidden" name="pdfOutputString" id="pdfOutputString" value="">';
							if($this->session->userdata('worksheetSubject') != 7) {
								echo '<input type="hidden" name="noQR" id="noQR" value="1" >';
								echo '<input type="submit" value="Save as PDF" class="btn btn-custom">';
							}

							echo '</form>';
							echo '</div>';
						} else {
							echo '<div class="alert alert-danger alert-no-margin text-center">';
							echo 'To save this worksheet, please <a href="'.base_url().'smartgen/login">login</a> first';
							echo '</div>';
						}
					?>
			</div>
		</div>
	</div>
</div>

<script>
	var subject_id = '<?= $subject_type ?>';
	var level_id = '<?= $level_id ?>';
	var level_name = '';
</script>

<div class="modal fade" id="form-edit" role="dialog">
	<div class="modal-dialog modal-width80" role="document">
		<div class="modal-content">
			<div class="modal-header modal-header-custom-success">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
						aria-hidden="true">&times;</span></button>
				<h4 class="modal-title">Edit Question</h4>
			</div>
			<input type="hidden" id="generate_id" />
			<input type="hidden" id="generate_question_number" />
			<input type="hidden" id="generate_section_number" />
			<input type="hidden" id="generate_level" />
			<input type="hidden" id="generate_subject" />
			<input type="hidden" id="generate_quetype" />

			<div class="question_number_exam_mode p-1">
				<div class="pull-left add_more_lvl">
					<span class="label label-warning worksheet_lvl">
						<?php echo $worksheet_lvl ;?>
					</span>
				</div>
				<div class="pull-left add_more_sub_top">
					<span class="label label-warning worksheet_substr">
					</span>
				</div>
				<div class="pull-left add_more_str">
					<span class="label label-warning worksheet_strategy">
					</span>
				</div>
				<div class="pull-left main-search">
					<div class="form-group has-feedback has-search">
						<span class="fa fa-search form-control-feedback"></span>
						<input type="text" id="searchKeyword" class="form-control" placeholder="Search Question">
					</div>
				</div>
				<div class="pull-right"
					style="margin-top: 10px; margin-right: 10px; margin-bottom: 10px; cursor: pointer;">
					<span class="label label-danger" id="edit_generate_question">
						<i class="fa fa-caret-down"></i>
					</span>
				</div>
			</div>

			<div class="modal-body panel_edit_generate_question">
				<form id="form-generate-edit-question" class="form-horizontal worksheet_form" method="post">
					<div class="panel">
						<ul class="list-group">
							<li class="list-group-item">
								<div class="session-group">
									<div class="form-group">
										<label for="" class="control-label col-xs-12 col-sm-4 col-md-4 col-lg-4">Topics
											:</label>
										<div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
											<select name="gen_substrand" id="gen_substrand"
												placeholder="Please select Strands">
											</select>
										</div>
										<div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
											<select name="gen_topic" id="gen_topic" placeholder="Please select Topic">
											</select>
										</div>
									</div>

									<div class="form-group">
										<label for=""
											class="control-label col-xs-12 col-sm-4 col-md-4 col-lg-4">Learning
											Objectives :</label>
										<div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
											<select name="gen_heuristic" id="gen_heuristic" placeholder="Heuristic">
											</select>
										</div>
										<div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
											<select name="gen_strategy" id="gen_strategy" placeholder="Strategy">
											</select>
										</div>
									</div>
								</div>

								<!-- question type  -->
								<div class="form-group">
									<label for="gen_que_type_0"
										class="control-label col-xs-12 col-sm-4 col-md-4 col-lg-4">Question
										Types :</label>
									<div class="col-sm-7 col-md-7 col-lg-7" style="padding-top: 5px; text-align: left;">
										<div class="col-sm-3 col-md-3 col-lg-3">
											<label class="radio-inline"><input type="radio" name="gen_que_type"
													class="mcq_type gen_que_type" value="1" checked>MCQ</label>
										</div>
										<div class="col-sm-4 col-md-4 col-lg-4">
											<label class="radio-inline"><input type="radio" name="gen_que_type"
													class="non_mcq_type gen_que_type" value="2">Non-MCQ</label>
										</div>
									</div>
								</div>

								<!-- difficulty -->
								<div class="form-group">
									<label for="gen_difficulties_0"
										class="control-label col-sm-4 col-md-4 col-lg-4">Difficulty Level
										:</label>
									<!-- <div class="col-sm-7 col-md-7 col-lg-7"
										style="padding-top: 10px; text-align: left;">
										<div class="col-sm-3 col-md-3 col-lg-3">
											<label style="font-weight: initial;"><input type="checkbox"
													name="gen_difficulties[]" class="gen_difficulties" value="1">
												Easy</label>
										</div>

										<div class="col-sm-3 col-md-3 col-lg-3">
											<label style="font-weight: initial;"><input type="checkbox"
													name="gen_difficulties[]" class="gen_difficulties" value="2"
													checked> Normal</label>
										</div>

										<div class="col-sm-3 col-md-3 col-lg-3">
											<label style="font-weight: initial;"><input type="checkbox"
													name="gen_difficulties[]" class="gen_difficulties" value="3">
												Hard</label>
										</div>

										<div class="col-sm-3 col-md-3 col-lg-3">
											<label style="font-weight: initial;"><input type="checkbox"
													name="gen_difficulties[]" class="gen_difficulties" value="4">
												Genius</label>
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
							<div class="row" style="margin-top: 10px;">
								<div class="col-sm-12 col-md-12 col-lg-12 text-center">
									<button type="button" class="btn btn-custom" id="gen_button">Generate</button>
								</div>
							</div>
						</ul>
					</div>
				</form>
			</div>
			<div class="modal-body panel_generate_question wall-content">
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="saveWorksheetModal" role="dialog">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
						aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="worksheetModalLabel">Save worksheet as</h4>
			</div>
			<form class="form-horizontal" action="<?php echo base_url(); ?>smartgen/saveWorksheet" method="post"
				accept-charset="utf-8" id="save_worksheet_form">
				<div class="modal-body">
					<div class="form-group">
						<label for="worksheet_name" class="control-label col-sm-4 col-md-4 col-lg-4">Worksheet
							name:</label>
						<div class="col-sm-7 col-md-7 col-lg-7">
							<input name="worksheet_name" id="worksheet_name" class="form-control"
								placeholder="My worksheet" autofocus="autofocus">
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<input type="button" class="btn btn-default" data-dismiss="modal" value="Close">
					<input type="submit" class="btn btn-custom" id="save_worksheet_button" value="Save">
				</div>
			</form>
		</div>
	</div>
</div>

<div class="modal fade" id="flagQuestionModal" role="dialog">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header modal-header-custom-alert">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
						aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="worksheetModalLabel">Flag question</h4>
			</div>
			<form class="form-horizontal" action="<?php echo base_url(); ?>smartgen/flagQuestion" method="post"
				accept-charset="utf-8" id="save_worksheet_form">
				<div class="modal-body">
					<div id="flag_question_error" class="alert alert-danger">
					</div>
					<div id="flag_question_success" class="alert alert-success">
						Your feedback has been submitted. We appreciate it very much!
					</div>
					<p>Hi there! Thanks for bringing the issue to us. Appreciate if you can provide more information
						about this error on this question !</p>
					<div class="form-group">
						<input type="hidden" name="flagged_question_id" id="flagged_question_id" value="">
						<input type="hidden" name="user_id" id="user_id" value="<?php echo $user_id; ?>">
						<label for="worksheet_name" class="control-label col-sm-4 col-md-4 col-lg-4">Issue:</label>
						<div class="col-sm-8 col-md-8 col-lg-8">
							<select name="error_type" id="error_type" class="form-control">
								<option value="Unclear question">Unclear question</option>
								<option value="Invalid answer options">Invalid answer options</option>
								<option value="Invalid question category">Invalid question category</option>
								<option value="Invalid question image">Invalid question image</option>
								<option value="Unclear question image">Unclear question image</option>
								<option value="Others">Others</option>
							</select>
						</div>
					</div>
					<div class="form-group">
						<label for="worksheet_name" class="control-label col-sm-4 col-md-4 col-lg-4">Comments:</label>
						<div class="col-sm-8 col-md-8 col-lg-8">
							<textarea name="error_comment" id="error_comment"
								placeholder="What do you think we can do better ?"
								style="width: 100%; padding: 0.5em"></textarea>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<input type="button" class="btn btn-default" data-dismiss="modal" value="Close">
					<button type="button" class="btn btn-custom"
						data-loading-text="<i class='fa fa-spinner fa-spin'></i> Submitting Feedback"
						id="flag_question_button">Submit</button>
				</div>
			</form>
		</div>
	</div>
</div>

<script type="text/javascript">
	$(function(){ 

		$('[type=number]').on('input', function(){
			var ques_difficulty = $(this).parent().prev();
			var diff_val = $(this).val();
			if(diff_val < 10) {
				$(ques_difficulty).val(diff_val);
			}
		});

		$('[type=number]').on('change', function() {

			var value = $(this).val();
			var ques_difficulty = $(this).parent().prev();
			$(ques_difficulty).val(value);
			if(value > 10) {
				toastr.error('No of line break cannot more than 10.');
				$(this).val(10);
				return false;
			}

		});

	});
</script>