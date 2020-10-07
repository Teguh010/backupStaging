<style>

.question_number {
	height:70px;
}

.question_strategy {
	margin-right:0px;
}

@media only screen and (max-width: 768px) {
	.question_number {
		height:150px;
	}
}

</style>

<div class="container">

	<div class="row">

		<div class="panel panel-success panel-success-custom-dark">

		  <!-- Default panel contents -->

		  	<div class="panel-heading clearfix">

		  		<div class="col-md-6 col-lg-6">

			  		<h4 class="worksheet_name"><b><?= $worksheetName ?></b></h4>

			  	</div>

			  	<div class="col-md-6 col-lg-6">

<!--			  		<a href="#MCQ" class="btn btn-info btn-no-margin-top pull-right type-select">Reset all to MCQ</a>-->

<!--			  		<a href="#openEnded" class="btn btn-warning btn-no-margin-top pull-right type-select">Reset all to Open ended</a>-->

			  	</div>

		  	</div>

		  	<div class="smartgen-pre-group">
				<ul class="list-group smartgen-list-group">

					<?php

						$letters = range('a', 'z');
													
						$alp = '';

						$quesNo = 0;

						for ($i = 1, $count = count($questionList) ; $i <= $count; $i++) {

							if($questionList[$i]->difficulty == 1) {
								$difficulty = 'Easy';
							} else if ($questionList[$i]->difficulty == 2) {
								$difficulty = 'Normal';
							} else if ($questionList[$i]->difficulty == 3) {
								$difficulty = 'Hard';
							} else {
								$difficulty = 'Genius';
							}

							$index = 0;
							
							if($i != $count) {
								if($questionStatus[$i] == TRUE && $questionList[$i]->sub_question == 'A') {
									$quesNo++;
									$alp = '';
								} else {
									if($questionList[$i]->sub_question == 'A') {
										$quesNo = $quesNo + 1;
										$alp = strtolower($questionList[$i]->sub_question);
										if($questionList[$i]->question_id != $questionList[$i+1]->reference_id) {
											$alp = '';
										}
									} else {
										$alp = strtolower($questionList[$i]->sub_question);
									}
								}
							} else {
								if($questionStatus[$i] == TRUE && $questionList[$i]->sub_question == 'A') {
									$quesNo++;
									$alp = '';
								} else {
									if($questionList[$i]->sub_question == 'A') {
										$quesNo = $quesNo + 1;
										$alp = '';
									} else {
										$quesNo = $quesNo;
										$alp = strtolower($questionList[$i]->sub_question);
									}
								}
							}

							echo '<li class="list-group-item clearfix" id="question_'.$quesNo.$alp.'">';

							echo '<div class="question_number" style="height: 115px;"> Question ' . $quesNo.$alp . '<span class="pull-right question_category">[' . $substrandList[$i-1] . '] ' . $categoryList[$i-1].'</span><br><span class="question_strategy">'.$strategyList[$i-1].'</span><br><span class="pull-left question_difficulty">('.$questionList[$i]->difficulty.' Marks)</span></div>';

							echo '<div class="question_text">';

							echo $questionList[$i]->question_text;

							if ($questionList[$i]->graphical != "0") {

								echo '<div><img src="https://www.smartjen.com/img/questionImage/'.$questionList[$i]->graphical.'" class="img-responsive"></div>';

							}

							echo '<div class="question_answer">';
							$que_type = ($quetypeList[$i-1]==0) ? $que_type : $quetypeList[$i-1];
							if ($que_type == 1) {
									$mcqCount = 1;
									$answerOption = $answerList[$i-1]['answerOption'];
										foreach ($answerOption as $option) {
											$correctAnswer = $answerList[$i-1]['correctAnswer'];
											$class = "";
											$icon = "";
											if($correctAnswer == $option->answer_text) {
												$class .= "correctAnswer ";
												$icon .= "<i class='fa fa-check answeredCorrectly'></i>";
											}
											echo '<span class="'.$class.'">' . $mcqCount . ') ' . $option->answer_text . '</span>' . $icon . '<br>';
											$mcqCount++;
										}
									//echo '<div class="pull-right">Ans: (&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;) </div>';
								} else{
									$answerOption = $answerList[$i-1]['answerOption'];
									foreach ($answerOption as $key=>$option) {
										$correctAnswer = $answerList[$i-1]['correctAnswer'];
										$class = "";
										$icon = "";
										if($correctAnswer == $option->answer_text) {
											$class .= "correctAnswer ";
											$icon .= "<i class='fa fa-check answeredCorrectly'></i>";
											echo '<br><span class="'.$class.'">Ans: ' . $option->answer_text . '</span>' . $icon;
										}
									}
									// switch ($questionList[$i]->difficulty) {
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
									// echo '<div class="pull-right">Ans: _____________________________ </div>';
								}
							echo '</div>';

							echo '</div>';

							echo '<br>';

							echo '<button class="btn btn-danger pull-right flag_question" data-toggle="modal" data-target="#flagQuestionModal" id="qid_'.$questionList[$i]->question_id.'"><span data-toggle="tooltip" data-placement="top" title="Error in question? Flag now!">Flag</span></button>';

							$lineBreak = $questionList[$i]->difficulty * 2;

							echo '<input type="hidden" class="question_id" value="'.$questionList[$i]->question_id.'">';

							echo '<input type="hidden" name="ques_difficulty" id="ques_difficulty" value="' . $lineBreak . '">';

							echo '<div style="padding-top: 1em; padding-left: 1em;" class="ques_line_break">';

							echo 'No of Line Break : <input name="ques_line_break" class="ques_line_break" id="'.$questionList[$i]->question_id.'_line_break" type="number" value="' . $lineBreak . '" min="1" max="10" style="width: 5%;">';

							echo '</div>';

	//						echo '<input id="cmn-toggle-'.$i.'" class="cmn-toggle cmn-toggle-yes-no pull-right" type="checkbox">';

	//						echo '<label for="cmn-toggle-'.$i.'" data-on="OpenEnded" data-off="MCQ" class="pull-right"></label>';

							echo '</li>';

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

					echo $answer['correctAnswer'];

					echo '</div>';

					$ansNum++;

				}

				?>

			</div>


			<div class="row" style="margin: 1em;">

			<?php

				echo '<form target="_blank" method="post" action="' . base_url() . 'smartgen/outputPdf" id="outputPDF" name="outputPDF">';

				echo '<input type="hidden" name="pdfOutputString" id="pdfOutputString" value="">';

				echo '<input type="hidden" name="worksheet_id" id="worksheet_id"  value="'.$worksheetId.'">';

				echo '<input type="hidden" name="tutor_id" id="tutor_id"  value="'.$tutor_id.'">';

				if(isset($user_role)) {

					if($user_role == '2') { // 2 is student, only have 1 student ID 
						if (in_array($user_id, $student_id)) {
							echo '<input type="hidden" name="student_id[]" id="student_id"  value="'.$user_id.'">';
						}
						echo '<input type="hidden" name="noQR"  value="" >';
					}
					elseif($user_role == '1') { // 1 is tutor, may have > 1 student IDs
						if(isset($student_id)) {
							foreach($student_id as $id) {
								echo '<input type="hidden" name="student_id[]"  value="'.$id.'">';
							}
						} else {
							echo '<input type="hidden" name="noQR"  value="1" >';
						}
					} 
				}######## what about  parents ?
				
				if(isset($questionList)) {
					foreach($questionList as $qns_detail) {
						echo '<input type="hidden" name="question_difficulty[]" value="'.$qns_detail->difficulty.'">';
					}
				}

				echo '<input type="hidden" name="pdfWorksheetName" id="pdfWorksheetName" value="'.$worksheetName.'">';

				// echo '<a href="'.base_url().'profile/customizeWorksheet/'.$worksheetId.'" class="btn btn-custom btn-no-margin-top" title="Duplicate">Duplicate Worksheet</a>';
				
				if(BRANCH_ID == 9) {

					if($worksheetSubject != 7) {

						if(isset($user_role)) {
							if($user_role == '2') { // 2 is student
								echo '<input type="submit" id="downloadPDF" value="Download PDF" class="btn btn-custom">';
								echo "<script>
									$(document).ready(function() {
											setTimeout(() => {
												$('#downloadPDF').click();  
											}, 50);
											return false;
											window.location.replace('https://localhost/staging/profile', '_self');
									});
									
									</script>";
							} else {
								// echo '<button id="downloadPDF" class="btn btn-custom" onclick="getElementsByClassName("question_answer").innerHTML = "" ">Download PDF</button>';
								
								echo '<input type="submit" id="downloadPDF" value="Download PDF" class="btn btn-custom">';
	
								echo '<input type="button" id="downloadPDFnoQR" value="Download PDF (no QR)" class="btn btn-custom">';
							}	
						}
					}

				} else {

					echo '<input type="button" id="downloadPDFnoQR" value="Download PDF" class="btn btn-custom">';

				}
				

				echo '</form>';

				// # Button for 'Download PDF (no QR)'
				// echo '<form target="_blank" method="post" action="' . base_url() . 'smartgen/outputPdf" id="outputPDF">';
				// echo '<input type="hidden" name="pdfOutputString" id="pdfOutputString" value="">';
				
				// if(isset($questionList)) {
				// 	foreach($questionList as $qns_detail) {
				// 		echo '<input type="hidden" name="question_difficulty[]" value="'.$qns_detail->difficulty.'">';
				// 	}
				// }
				
				// if($worksheetSubject != 7) {
				// 	if(isset($user_role)) {
				// 		if($user_role == '1') { // 2 is student
				// 			echo '<input type="hidden" name="noQR"  value="1" id="noQR">';
				// 			echo '<button id="downloadPDF" class="btn btn-custom" onclick="getElementsByClassName("question_answer").innerHTML = "" ">Download PDF (no QR)</button>';
				// 		}	
				// 	}
				// }
				// echo '</form>';

				

			?>

	<!--		<a href="--><?php //echo base_url(); ?><!--profile/saveWorksheetAsPDF/--><?//= $worksheetId ?><!--" class="btn btn-custom disabled save_worksheet_pdf">Save as PDF</a>-->

	<!--		<a href="--><?php //echo base_url(); ?><!--profile/saveWorksheetAsPDF/--><?//= $worksheetId ?><!--/MCQ" class="btn btn-custom save_worksheet_pdf">Save as PDF (MCQ Question)</a>-->

	<!--		<a href="--><?php //echo base_url(); ?><!--profile/saveWorksheetAsPDF/--><?//= $worksheetId ?><!--/openEnded" class="btn btn-custom save_worksheet_pdf">Save as PDF (Open ended Question)</a>-->

	<!--		<form method="post" action="--><?php //echo base_url(); ?><!--profile/saveWorksheetAsPDF/--><?//= $worksheetId ?><!--/customized" class="save_worksheet_form"> -->

	<!--			--><?php //

	//				for ($i = 1, $count = count($questionList) ; $i <= $count; $i++) {

	//					echo '<input type="hidden" class="question_type_'.$i.'" name="question_type_'.$i.'" value="MCQ">';

	//				}

	//			?>

	<!--			<input type="submit" class="btn btn-custom" value="Save as PDF (Question type customized above)">-->

	<!--		</form>-->

			</div>

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

<script>
$(document).ready(function (){

	
	setTimeout(append_div_mathjax, 1000);

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

	$("#downloadPDFnoQR").click( function () {
			$('#outputPDF').append("<input type='hidden' name='noQR2'  value='1' >").submit();
	});

});

function append_div_mathjax() {
	$(".mj_element .question script, .answer script").each(function () {
		//eleHtml = '<h4 class="makan_tahi">' +  $(this).html() + '</h4>';
		//$(this).html(eleHtml);

		$(this).prev().children(":first-child").remove();

		var eleHtml = $(this).parent().children('.MathJax_SVG');
		var element = $(this).html();
		var replace;
		
		if($(this).html() == '\\underline{}\\underline{}\\underline{}'){
			eleHtml.html('___');
		}

		if($(this).html() == '\dots\dots\dots\dots\dots\dots'){
			eleHtml.html('___');
		}
		
		if(element.search('\\$') == 1 ){
			var num = element.split("$")[1];
			var frame = $(this).prev().attr('id');
			replace = '$' + num;
		}

		if(element.includes('cm^3') == 1) {
			var num = element;
			num = num.replace('cm\^3', 'cm<sup>3</sup>');
			var frame = $(this).prev().attr('id');
			replace = num;
		}

		if(element.includes('m^3') == 1) {
			var num = element;
			num = num.replace('m\^3', 'm<sup>3</sup>');
			var frame = $(this).prev().attr('id');
			replace = num;
		}

		if(element.includes('cm^2') == 1) {
			var num = element;
			num = num.replace('cm\^2', 'cm<sup>2</sup>');
			var frame = $(this).prev().attr('id');
			replace = num;
		}

		if(element.includes('m^2') == 1) {
			var num = element;
			num = num.replace('m\^2', 'm<sup>2</sup>');
			var frame = $(this).prev().attr('id');
			replace = num;
		}
		
		if(element.search('frac{') == 1 ){
			var num = element.replace('\\frac{', '');
			num = num.replace(new RegExp('}', 'g'), '');
			num = num.split('{');
			var frame = $(this).prev().attr('id');
			replace = '<sup>'+num[0]+'</sup>&frasl;<sub>'+num[1]+'</sub>';
		}

		if(element.search('\\angle') == 1 ){
			var num = element;
			
			num = num.replace(new RegExp('\\\\angle', 'g'), '<span style="font-family: Dejavu Sans; ">&#x2220;</span>');
			var frame = $(this).prev().attr('id');
			replace = num;
		}
		
		if(element.includes('^{\\circ}') == 1){
			var num = element;
			num = num.replace(new RegExp('([\\^])([{])([\\\\,])circ([}])', 'g'), '<span style="font-family: Dejavu Sans; ">&#176;</span>');
			var frame = $(this).prev().attr('id');
			replace = num;
		}

		if(element.includes('^\\circ') == 1){
			var num = element;
			var angle = num.split('=')[0]; 
			angle = angle.replace(new RegExp('\\\\angle', 'g'), '&#x2220;');
			num = num.split('=')[1];
			num = num.replace(new RegExp('([\\^])([\\\\,])circ', 'g'), '&#176;');
			var frame = $(this).prev().attr('id');
			replace = '<span style="font-family: Dejavu Sans; ">' + angle + '=' + num +'</span>';
		}

		$(this).parent().children('#'+frame).html(replace);

	});
}
</script>