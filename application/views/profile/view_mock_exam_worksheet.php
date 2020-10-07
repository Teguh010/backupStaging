<div class="container">
	<div class="row">
		<div class="panel panel-success panel-success-custom-dark">
		  <!-- Default panel contents -->
		  	<div class="panel-heading clearfix">
		  		<div class="col-md-6 col-lg-6">
			  		<h4 class="worksheet_name"><b><?= $worksheetName ?></b></h4>
			  	</div>
		  	</div>
			<ul class="list-group smartgen-list-group">
				<?php
					$quesNum = 1;
					foreach ($questionList AS $question) {
						$subquesNum = 0;
						foreach ($question as $subquestion) {
							echo '<li class="list-group-item clearfix" id=question_'.$quesNum.'_'.$subquesNum.'>';
							$subQuestionOutput = (count($question)==1)?'':' ('.$subquestion->sub_question.')';
							$mark_text = ($subquestion->difficulty == 1)?'mark':'marks';
							echo '<div class="question_number clearfix"> <span class="question_number_text">Question '. $quesNum . $subQuestionOutput .'</span> <span class="question_difficulty_text">('.$subquestion->difficulty . ' ' . $mark_text . ')</span></div>';
							echo '<div class="question_text clearfix">';
							echo $subquestion->question_text;
							if ($subquestion->graphical != "0") {
								echo '<div><img src="'.base_url().'img/questionImage/'.$subquestion->graphical.'" class="img-responsive"></div>';
							}
							echo '<div class="question_answer">';

							if ($subquestion->question_type_id == 1) {
								$mcqCount = 1;
								$answerOption = $answerList[$quesNum-1][$subquesNum]['answerOption'];
								foreach ($answerOption as $option) {
									echo $mcqCount . ') ' . $option->answer_text . '<br>';
									$mcqCount++;
								}
								echo '<div class="pull-right">Ans: (&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;) </div>';
							} else {
								switch ($subquestion->difficulty) {
									case 2:
										echo '<br><br><br><br><br>';
										break;
									case 3:
										echo '<br><br><br><br><br><br><br><br><br>';
										break;
									case 4:
										echo '<br><br><br><br><br><br><br><br><br><br><br><br><br><br>';
									case 5:
										echo '<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>';
										break;
									default:
										break;
								}
								echo '<div class="pull-right">Ans: _____________________________ </div>';
							}
							
							echo '</div>';
							echo '</div>';
							// echo '<button class="btn btn-danger pull-right flag_question" data-toggle="modal" data-target="#flagQuestionModal" id="qid_'.$question->question_id.'"><span data-toggle="tooltip" data-placement="top" title="Error in question? Flag now!">Flag</span></button>';
							// echo '<a href="'.base_url().'smartgen/regenerateQuestion" id="regen_'.$quesNum.'" class="btn btn-custom pull-right regen_question" data-toggle="tooltip" data-placement="top" title="Regenerate this question, please">Regenerate</a>';
							// echo '<span class="btn btn-info pull-right" data-toggle="tooltip" data-placement="top" title="This question is only available in MCQ">MCQ</span>';
							echo '</li>';
							$subquesNum++;
						}

						$quesNum++;

					}
				?>
		    </ul>
			<div id="correctAnswer" class="hidden">
				<?php 
					$ansNum = 1;
					foreach ($answerList as $answer) {
						$ansSubNum = 0;
						foreach ($answer as $subanswer) {
							$subQuestionOutput = (count($answer)==1)?'':' - '.$questionList[$ansNum-1][$ansSubNum]->sub_question;
							echo '<div id="correct_answer_' . $ansNum . '_' . $ansSubNum . '" class="correctAnswer">';
							if ($questionList[$ansNum - 1][$ansSubNum]->question_type_id == 1) {
								echo $ansNum . $subQuestionOutput . ') ['. $subanswer['correctAnswerOptionNum'] . '] ' . $subanswer['correctAnswer'];
							} else {
								echo $ansNum . $subQuestionOutput . ') ' . $subanswer['correctAnswer'];
							}
							# code...
							echo '</div>';
							$ansSubNum++;
						}
						$ansNum++;
					}
				?>
			</div>
		</div>
	</div>
	<div class="row">
		<?php
			echo '<form method="post" action="' . base_url() . 'smartgenkrtc/outputPdf" id="outputMockExamPDF">';
			echo '<input type="hidden" name="pdfOutputString" id="pdfOutputString" value="">';
			echo '<input type="hidden" name="pdfWorksheetName" id="pdfWorksheetName" value="'.$worksheetName.'">';
			echo '<input type="submit" value="Save as PDF" class="btn btn-custom">';
			echo '</form>';
		?>
	</div>
</div>
