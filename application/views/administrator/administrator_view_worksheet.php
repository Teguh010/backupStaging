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

			  		<h4 class="worksheet_name"><b><?php echo $worksheetName ?></b></h4>

			  	</div>

			  	<div class="col-md-6 col-lg-6">

<!--			  		<a href="#MCQ" class="btn btn-info btn-no-margin-top pull-right type-select">Reset all to MCQ</a>-->

<!--			  		<a href="#openEnded" class="btn btn-warning btn-no-margin-top pull-right type-select">Reset all to Open ended</a>-->

			  	</div>

		  	</div>

		  	<div class="smartgen-pre-group">
				<ul class="list-group smartgen-list-group">

					<?php
						for ($i = 1, $count = count($questionList) ; $i <= $count; $i++) {

							echo '<li class="list-group-item clearfix">';

							echo '<div class="question_number"> Question ' . $i . '<span class="pull-right question_category">[' . $substrandList[$i-1] . '] ' . $categoryList[$i-1].'</span><br><span class="question_strategy">'.$strategyList[$i-1].'</span></div>';

							echo '<div class="question_text">';

							echo $questionList[$i]->question_text;

							if ($questionList[$i]->graphical != "0") {

								echo '<div><img src="'.base_url().'questionImage/'.$questionList[$i]->graphical.'" class="img-responsive"></div>';

							}

							echo '<div class="question_answer">';

							if ($que_type == 1) {
									$mcqCount = 1;
									$answerOption = $answerList[$i-1]['answerOption'];
										foreach ($answerOption as $option) {
											echo $mcqCount . ') ' . $option->answer_text . '<br>';
											$mcqCount++;
										}
									//echo '<div class="pull-right">Ans: (&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;) </div>';
								} else{
									switch ($questionList[$i]->difficulty) {
										case 2:
											echo '<br><br><br><br><br>';
											break;
										case 3:
											echo '<br><br><br><br><br><br><br><br><br>';
											break;
										case 4:
											echo '<br><br><br><br><br><br><br><br><br><br><br><br><br><br>';
											break;
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

							echo '<button class="btn btn-danger pull-right flag_question" data-toggle="modal" data-target="#flagQuestionModal" id="qid_'.$questionList[$i]->question_id.'"><span data-toggle="tooltip" data-placement="top" title="Error in question? Flag now!">Flag</span></button>';

							echo '<input type="hidden" class="question_id" value="'.$questionList[$i]->question_id.'">';

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

					echo '('. $answer['correctAnswerOptionNum'] . ') ' . $answer['correctAnswer'];

					echo '</div>';

					$ansNum++;

				}

				?>

			</div>


			<div class="row" style="margin: 1em;">

			<?php

				echo '<form target="_blank" method="post" action="' . base_url() . 'smartgen/outputPdf" id="outputPDF">';

				echo '<input type="hidden" name="pdfOutputString" id="pdfOutputString" value="">';

				echo '<input type="hidden" name="pdfWorksheetName" id="pdfWorksheetName" value="'.$worksheetName.'">';

				// echo '<a href="'.base_url().'profile/customizeWorksheet/'.$worksheetId.'" class="btn btn-custom btn-no-margin-top" title="Duplicate">Duplicate Worksheet</a>';
				
				if($worksheetSubject != 7) {
					echo '<input type="submit" value="Save as PDF" class="btn btn-custom">';
				}

				//echo '<a href="' . base_url() . 'smartgen/outputPdf" target="_blank" class="btn btn-custom">Save as PDF</a>';
				echo '</form>';

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

						<input type="hidden" name="user_id" id="user_id" value="<?php echo $admin_id; ?>">

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

