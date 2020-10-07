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

<div class="section" ondragstart="return false;" ondrop="return false;" onselectstart="return false" onCopy="return false" onCut="return false" onDrag="return false" onDrop="return false" oncontextmenu="return false;">
	<div class="container">
		<div class="row">
			<div class="panel panel-success panel-success-custom-dark">
				<!-- Default panel contents -->
				<div class="panel-heading clearfix">
					Generated Question
					<form class="form-horizontal"
						action="<?php echo base_url(); ?>smartgen/regenerateAllQuestionTID/<?php echo $this->session->userdata('requirementId'); ?>"
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
								// array_debug($question); //exit;
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
								$dropdown = "<select name='question_".$quesNum."' class='question_order question_".$question->question_id."' data-id='".$question->id."' data-question_id='".$question->question_id."' data-number='".$quesNum."'>";
								
								for($i = 1; $i <= $num_of_question; $i++ ){
									$dropdown .= "<option value='".$i."' ".(($i == $quesNum)?'selected':'')."> Question ".$i."</option>";
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
									echo '<div class="question_number question_number_with_strategy"> '. $dropdown .'
											<span class="pull-right question_category">'.$this->model_worksheet->get_worksheet_topics_tid(array($question->tid_topic_id)) . $close_btn.'</span><br />
											<span class="question_strategy">'.$this->model_worksheet->get_worksheet_ability_tid(array($question->ability)).'</span>
											<br>
											<span class="pull-left question_difficulty">('.$question->difficulty.' Marks, '.$difficulty.')</span>											
									</div>';
								}

								echo '<div class="question_text">';

								$textArray = explode(" ", $question->question_text);
								$newTextArray = array();
								foreach($textArray as $text) {
									$test = strpos($text, '\\(');
									if($test === false){
										$newTextArray[] = '\\(' . $text . '\\)';
									} else {
										$newTextArray[] = $text;
									}
								}
								$newText = implode(" ", $newTextArray);

								if($question->question_content == 0){
									echo $newText;
									if ($question->graphical != "0") {
										$questionImage = $question->branch_image_url.'/questionImage/'.$question->graphical;
										$image_type_check = @exif_imagetype($questionImage);
										if (strpos($http_response_header[0], "403") || strpos($http_response_header[0], "404") || strpos($http_response_header[0], "302") || strpos($http_response_header[0], "301")) {
											$questionImage = "https://www.smartjen.com/img/questionImage/".$question->graphical;
										}
	
										echo '<div><img src="'.$questionImage.'" class="img-responsive" style="max-width:60%;" /></div>';
									}
								} else {
									if($question->content_type == 'text'){
										echo $newText;
									} else {
										echo '<div><img src="'.$question->branch_image_url.'/questionImage/'.$question->question_text.'" draggable="false" class="img-responsive" style="max-width:60%;"></div>';
									}

									$questionContentsArray = $questionContents[$quesNum-1]['question_content'];
		
									foreach ($questionContentsArray as $questionContent) {
										
										if($questionContent->content_type == 'text'){
											echo $questionContent->content_name;
											echo '<br>';
										} else {
											echo '<div><img src="'.$question->branch_image_url.'/questionImage/'.$questionContent->content_name.'" draggable="false" class="img-responsive" style="max-width:60%;"></div>';
											echo '<br>';
										}
									}


								}

								// echo $question->question_text;
								// if ($question->graphical != "0") {
								// 	$questionImage = $question->branch_image_url.'/questionImage/'.$question->graphical;
								// 	$image_type_check = @exif_imagetype($questionImage);
								// 	if (strpos($http_response_header[0], "403") || strpos($http_response_header[0], "404") || strpos($http_response_header[0], "302") || strpos($http_response_header[0], "301")) {
								// 	    $questionImage = "https://www.smartjen.com/img/questionImage/".$question->graphical;
								// 	}

								// 	echo '<div><img src="'.$questionImage.'" class="img-responsive" style="max-width:60%;" /></div>';
								// }

								echo '<div class="question_answer">';
								if ($question->question_type == 1) {
									$mcqCount = 1;
									$answerOption = $answerList[$quesNum-1]['answerOption'];

									foreach ($answerOption as $option) {
										$textArray = explode(" ", $option->answer_text);
										$newTextArray = array();
										foreach($textArray as $text) {
											$test = strpos($text, '\\(');
											if($test === false){
												$newTextArray[] = '\\(' . $text . '\\)';
											} else {
												$newTextArray[] = $text;
											}
										}
										$newAnswerText = implode(" ", $newTextArray);
										$correctAnswer = $answerList[$quesNum-1]['correctAnswer'];

										$class = "";

										$icon = "";

										if($correctAnswer == $option->answer_id) {
											$class .= "correctAnswer ";
											$icon .= "<i class='fa fa-check answeredCorrectly'></i>";
										}

										echo '<span class="'.$class.'">\\(' . $mcqCount . '\\) ) ' . $newAnswerText . '</span>' . $icon . '<br>';
		
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
											data-level_id="'.$question->tid_level.'" 
											data-subject="'.$question->subject_type.'" 
											data-type="'.$question->question_type.'" 
											data-ability="'.$question->ability.'" 
											data-topic="'.$question->tid_topic_id.'" 
											data-strategy="0" 
											data-section="'.$quesNum.'">
											<span data-toggle="tooltip" data-placement="top" title="Edit question">Edit</span></button>';
									if($subquestionList[$quesNum - 1] === 'B'){
										echo '<button class="btn btn-warning pull-right sub_question" id="subqid_'.$question->question_id.'"><span data-toggle="tooltip" data-placement="top" title="Add sub question, please">Sub Question</span></button>';
										echo '</li>';
										echo '</div>';
									}else {
										echo '</li>';
										echo '</div>';
									}	
								}else if($question->question_type == 2){

									$answerOption = $answerList[$quesNum-1]['answerOption'];
									foreach ($answerOption as $key=>$option) {
										$textArray = explode(" ", $option->answer_text);
										$newTextArray = array();
										foreach($textArray as $text) {
											$test = strpos($text, '\\(');
											if($test === false){
												$newTextArray[] = '\\(' . $text . '\\)';
											} else {
												$newTextArray[] = $text;
											}
										}
										$newAnswerText = implode(" ", $newTextArray);

										$correctAnswer = $answerList[$quesNum-1]['correctAnswer'];
										$class = "";
										$icon = "";
										if($correctAnswer == $option->answer_id) {
											$class .= "correctAnswer ";
											$icon .= "<i class='fa fa-check answeredCorrectly'></i>";
											echo '<br><span class="'.$class.'">Ans: ' . $newAnswerText . '</span>' . $icon;
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
									// echo '<button class="btn btn-primary pull-right edit_question" id="edit_question_'.$quesNum.'" data-toggle="modal" data-target="#form-edit" data-id="'.$question->id.'" data-requirement_id="'.$question->requirement_id.'" 
									// 		data-level_id="'.$question->level_id.'" 
									// 		data-subject="'.$question->subject_type.'" 
									// 		data-type="'.$question->question_type.'" 
									// 		data-substrands="'.$substrandList[$quesNum - 1].'" 
									// 		data-topic="'.$categoryList[$quesNum-1].'" 
									// 		data-strategy="'.$strategyList[$quesNum-1].'" 
									// 		data-section="'.$quesNum.'">
									// 		<span data-toggle="tooltip" data-placement="top" title="Edit question">Edit</span></button>';
									if($subquestionList[$quesNum - 1] === 'B'){
										echo '<button class="btn btn-warning pull-right sub_question" id="subqid_'.$question->question_id.'"><span data-toggle="tooltip" data-placement="top" title="Add sub question, please">Sub Question</span></button>';
										echo '</li>';
										echo '</div>';
									}else {
										echo '</li>';
										echo '</div>';
									}
								}							
								$quesNum++;
							}

						?>
					</ul>
				</div>

				<div id="questionTextPDF" class="hidden">
					<?php 
			    		$ansNum = 1;
			    		foreach ($questionList as $question) {
			    			# code...
							echo '<div id="question_text_pdf_' . $ansNum . '" class="question_text_pdf">';
							echo $question->question_text;
							echo '<div class="question_answer">';

								if ($question->question_type == 1) {
									$mcqCount = 1;
									$answerOption = $answerList[$ansNum-1]['answerOption'];

									foreach ($answerOption as $option) {
										$correctAnswer = $answerList[$ansNum-1]['correctAnswer'];

										$class = "";

										$icon = "";

										if($correctAnswer == $option->answer_id) {
											$class .= "correctAnswer ";
											$icon .= "<i class='fa fa-check answeredCorrectly'></i>";
										}

										echo '<span class="'.$class.'">' . $mcqCount . ') ' . $option->answer_text . '</span>' . $icon . '<br>';
		
										$mcqCount++;
									}
									echo '</div>';
								}else if($question->question_type == 2){

									$answerOption = $answerList[$ansNum-1]['answerOption'];
									foreach ($answerOption as $key=>$option) {
										$correctAnswer = $answerList[$ansNum-1]['correctAnswer'];
										$class = "";
										$icon = "";
										if($correctAnswer == $option->answer_id) {
											$class .= "correctAnswer ";
											$icon .= "<i class='fa fa-check answeredCorrectly'></i>";
											echo '<br><span class="'.$class.'">Ans: ' . $option->answer_text . '</span>' . $icon;
										}
									}
									echo '</div>';
								}
			    			echo '</div>';
			    			$ansNum++;
			    		}
			    	?>
				</div>

				<div id="correctAnswer" class="hidden">
					<?php 
			    		$ansNum = 1;
			    		foreach ($answerList as $answer) {
			    			# code...
							echo '<div id="correct_answer_' . $ansNum . '" class="correctAnswerText">';
							if($questionList[$ansNum-1]->question_type==1) { // MCQ
								// echo '('. $answer['correctAnswerOptionNum'] . ') ' . $answer['correctAnswerText'];
								if(isset($answer['correctAnswerText'])) echo $answer['correctAnswerText'];
							} else {
								if(isset($answer['correctAnswerText'])) echo $answer['correctAnswerText'];
							}
			    			echo '</div>';
			    			$ansNum++;
			    		}
			    	?>
				</div>
				<?php
						if ($isLoggedIn) {
							echo '<div class="text-center">';
							

							echo '<form target="_blank" method="post" action="' . base_url() . 'smartgen/outputPdf" id="outputPDF">';
							//provide save and save as option
							if (isset($worksheetId) && empty($worksheetId) === false) {
								//echo '<a href="'.base_url().'smartgen/saveExistingWorksheet/'.$worksheetId.'" class="btn btn-custom">Save</a>';
								echo '<input type="button" class="btn btn-custom" value="Save As New Worksheet" id="gen_button" data-toggle="modal" data-target="#saveWorksheetModal">';
								// echo '<form method="post" action="'.base_url().'smartgen/saveWorksheetAsPDF/'.$worksheetId.'" class="save_worksheet_form">';
							} else {
								echo '<input type="button" class="btn btn-custom" value="Save Worksheet to Profile" id="gen_button" data-toggle="modal" data-target="#saveWorksheetModal">';
								// echo '<form method="post" action="'.base_url().'smartgen/saveWorksheetAsPDF" class="save_worksheet_form">';
							}
							echo '<input type="hidden" name="pdfOutputString" id="pdfOutputString" value="">';
							if($this->session->userdata('worksheetSubject') != 7) {
								// echo '<input type="submit" value="Save as PDF" class="btn btn-custom">';
								echo '<input type="button" id="downloadPDFnoQR" value="Download PDF" class="btn btn-custom">';
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
						<?php echo ucfirst($worksheet_lvl) ;?>
					</span>
				</div>
				<div class="pull-left add_more_sub_top">
					<span class="label label-warning worksheet_substr">
						<?php echo $worksheet_topic ;?>
					</span>
				</div>
				<div class="pull-left add_more_sub_top">
					<span class="label label-warning worksheet_ability">
						
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
										<div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
											<select name="gen_topic" id="gen_topic" placeholder="Please select Topic">
											</select>
										</div>
									</div>

									<div class="form-group">
										<label for=""
											class="control-label col-xs-12 col-sm-4 col-md-4 col-lg-4">Ability :</label>
										<div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
											<select name="gen_ability" id="gen_ability" placeholder="Ability">
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
                                        <label class="switch">
                                            <input type="checkbox" id="cek_que_type" data-id="0" data-status="1" class="setActiveMCQ" checked>
                                            <div class="slider round"><span class="on">MCQ</span><span class="off">Non-MCQ</span></div>
                                        </label>
                                    </div>
                                    <input type="hidden" name="gen_que_type" id="gen_que_type" value="1" />
								</div>

								<div class="form-group">
                                    <label for="gen_operator_0" class="control-label col-xs-12 col-sm-4 col-md-4 col-lg-4">Ability x Difficulty :</label>
                                    <div class="col-sm-7 col-md-7 col-lg-7" style="padding-top: 5px; text-align: left;">
                                       	<label class="switch">
                                            <input type="checkbox" id="cek_operator" data-id="0" data-status="1" class="setActiveAND" checked>
                                            <div class="slider round"><span class="on">AND</span><span class="off" style="right: 25px;">OR</span></div>
                                        </label>
                                    </div>
                                    <input type="hidden" name="gen_operator" id="gen_operator" value="1" />
                                </div>

								<!-- difficulty -->
								<div class="form-group">
									<label for="gen_difficulties_0"
										class="control-label col-sm-4 col-md-4 col-lg-4">Difficulty Level
										:</label>
									<div class="col-sm-7 col-md-7 col-lg-7"
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
									</div>
								</div>
							</li>
							<div class="row">
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
			<form class="form-horizontal" action="<?php echo base_url(); ?>smartgen/saveWorksheetTID" method="post"
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
		$(document).keydown(function(e) {    
			if ((e.ctrlKey || e.metaKey) && e.keyCode == 65) {
				e.preventDefault();
			}
		});

		$("#downloadPDFnoQR").click( function () {
			$('#outputPDF').append("<input type='hidden' name='noQR2'  value='1' >").submit();
	});
	});
</script>