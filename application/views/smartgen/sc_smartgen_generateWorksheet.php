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
			  		<form class="form-horizontal" action="<?php echo base_url(); ?>smartgen/sc_generateWorksheet/<?php echo (isset($worksheetId))?$worksheetId:''; ?>" method="post" accept-charset="utf-8">
			  			<input name="regenerateWorksheet" type="submit" class="btn btn-custom btn-no-margin pull-right" value="Regenerate all">
			  		</form>
			  	</div>
			  	<div class="smartgen-pre-group">
					<ul class="list-group smartgen-list-group">
						<?php
							$quesNum = 1;
							foreach ($questionList AS $question) {
								echo '<div id="addNewSubQuestionDiv_'.$quesNum.'">';
								echo '<li class="list-group-item clearfix" id="question_'.$quesNum.'">';
								echo '<div class="question_number"> Question '. $quesNum .' <span class="pull-right question_category">[' . $substrandList[$quesNum - 1] . '] ' . $categoryList[$quesNum-1].'</span></div>';
								echo '<div class="question_text">';
								echo $question->question_text;
								if ($question->graphical != "0") {
									echo '<div><img src="'.base_url().'img/questionImage/'.$question->graphical.'" draggable="false" class="img-responsive"></div>';
								}
								echo '<div class="question_answer">';
								$mcqCount = 1;
								$answerOption = $answerList[$quesNum-1]['answerOption'];
								foreach ($answerOption as $option) {
									echo $mcqCount . ') ' . $option->answer_text . '<br>';
									$mcqCount++;
								}
								echo '</div>';
								echo '</div>';
								echo '<button class="btn btn-danger pull-right flag_question" data-toggle="modal" data-target="#flagQuestionModal" id="qid_'.$question->question_id.'"><span data-toggle="tooltip" data-placement="top" title="Error in question? Flag now!">Flag</span></button>';
								echo '<a href="'.base_url().'smartgen/regenerateQuestion" id="regen_'.$quesNum.'" class="btn btn-custom pull-right regen_question" data-toggle="tooltip" data-placement="top" title="Regenerate this question, please">Regenerate</a>';
								echo '<span class="btn btn-info pull-right que_type" data-toggle="tooltip" data-placement="top" title="This question is only available in MCQ">MCQ</span>';
								if($subquestionList[$quesNum - 1] === 'B'){
									echo '<button class="btn btn-success pull-right sub_question" id="subqid_'.$question->question_id.'"><span data-toggle="tooltip" data-placement="top" title="Add sub question, please">Sub Question</span></button>';
									echo '</li>';
									echo '</div>';
								}else {
									echo '</li>';
									echo '</div>';
								}
								/*echo '</li>';
								echo '</di>';*/
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
			    			echo '<div id="correct_answer_' . $ansNum . '" class="correctAnswer">';
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
								echo '<a href="'.base_url().'smartgen/saveExistingWorksheet/'.$worksheetId.'" class="btn btn-custom">Save</a>';
								echo '<input type="submit" class="btn btn-custom" value="Save as new worksheet" id="gen_button" data-toggle="modal" data-target="#saveWorksheetModal">';
							} else {
								echo '<input type="submit" class="btn btn-custom" value="Save worksheet to profile" id="gen_button" data-toggle="modal" data-target="#saveWorksheetModal">';
							}
							echo '<form target="_blank" method="post" action="' . base_url() . 'smartgen/outputPdf" id="outputPDF">';
							echo '<input type="hidden" name="pdfOutputString" id="pdfOutputString" value="">';
							echo '<input type="submit" value="Save as PDF" class="btn btn-custom">';
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

<div class="modal fade" id="saveWorksheetModal" role="dialog">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="worksheetModalLabel">Save worksheet as</h4>
			</div>
			<form class="form-horizontal" action="<?php echo base_url(); ?>smartgen/saveWorksheet" method="post" accept-charset="utf-8" id="save_worksheet_form">
				<div class="modal-body">
					<div class="form-group">
						<label for="worksheet_name" class="control-label col-sm-4 col-md-4 col-lg-4">Worksheet name:</label>
						<div class="col-sm-7 col-md-7 col-lg-7">
							<input name="worksheet_name" id="worksheet_name" class="form-control" placeholder="My worksheet" autofocus="autofocus">
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
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="worksheetModalLabel">Flag question</h4>
			</div>
			<form class="form-horizontal" action="<?php echo base_url(); ?>smartgen/flagQuestion" method="post" accept-charset="utf-8" id="save_worksheet_form">
				<div class="modal-body">
					<div id="flag_question_error" class="alert alert-danger">
						
					</div>
					<div id="flag_question_success" class="alert alert-success">
						Your feedback has been submitted. We appreciate it very much!
					</div>
					<p>Hi there! Thanks for bringing the issue to us. Appreciate if you can provide more information about this error on this question !</p>
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
							<textarea name="error_comment" id="error_comment" placeholder="What do you think we can do better ?" style="width: 100%; padding: 0.5em"></textarea>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<input type="button" class="btn btn-default" data-dismiss="modal" value="Close">
					<button type="button" class="btn btn-custom" data-loading-text="<i class='fa fa-spinner fa-spin'></i> Submitting Feedback" id="flag_question_button">Submit</button>
				</div>
			</form>
		</div>
	</div>
</div>

<script type="text/javascript">
$(document).ready(function (){
	$('body').bind('cut copy paste', function(e){
		e.preventDefault();
	});
	
	$('body').on('contextmenu', function(e){
		e.preventDefault();
	});
	
	if(typeof document.onselectstart != "undefined"){
		document.onselectstart = new Function ("return false");
	} else {
		document.onmousedown = new Function ("return false");
		document.onmouseup = new Function ("return false");
	}
});
</script>