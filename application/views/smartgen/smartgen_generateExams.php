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
			  		<form class="form-horizontal" action="<?php echo base_url(); ?>smartgen/generateExam/<?php echo (isset($worksheetId))?$worksheetId:''; ?>" method="post" accept-charset="utf-8">
			  			<input name="regenerateWorksheet" type="submit" class="btn btn-custom btn-no-margin pull-right" value="Regenerate all">
			  		</form>
			  	</div>
			  	<div class="smartgen-pre-group">
					<ul class="list-group smartgen-list-group">
						<?php
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
								$close_btn = '';								
								$dropdown = "<select name='question_".$quesNum."' class='question_order' data-id = '".$question->question_id."'>";								
								for($i = 1; $i <= $num_of_question; $i++ ){
									$dropdown .= "<option value='".$i."' ".(($i == $quesNum)?'selected':'')."> Question ".$i."</option>";
								}
								
								$dropdown .= "</select>";								
								if($num_of_question> 1){									
									$close_btn = '<a href="#" class="question-remove" title="Remove Question" data-id = "'.$question->question_id.'" style="padding-left:10px;"><i class="fa fa-times"></i></a>';								
								}
								
								echo '<div id="addNewSubQuestionDiv_'.$quesNum.'">';
								echo '<li class="list-group-item clearfix" id=question_'.$quesNum.'>';
								echo '<div class="question_number"> '. $dropdown .' <span class="pull-right question_category">[' . $substrandList[$quesNum - 1] . '] ' . $categoryList[$quesNum-1].$close_btn.'</span><br><span class="pull-right question_strategy" style="margin-right:20px;">'.$strategyList[$quesNum-1].'</span></div>';
								echo '<div class="question_text">';
								echo $question->question_text;
								if ($question->graphical != "0") {
									echo '<div><img src="'.$question->branch_image_url.'/questionImage/'.$question->graphical.'" draggable="false" class="img-responsive" style="max-width:60%;"></div>';
								}
								echo '<div class="question_answer">';
								switch ($question->difficulty) {
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
									//echo '<div class="pull-right">Ans: _____________________________ </div>';
	
								echo '</div>';
								echo '</div>';
								echo '<button class="btn btn-danger pull-right flag_question" data-toggle="modal" data-target="#flagQuestionModal" id="qid_'.$question->question_id.'"><span data-toggle="tooltip" data-placement="top" title="Error in question? Flag now!">Flag</span></button>';
								echo '<a href="'.base_url().'smartgen/regenerateQuestion" id="regen_'.$quesNum.'" class="btn btn-custom pull-right regen_question" data-toggle="tooltip" data-placement="top" title="Regenerate this question, please">Regenerate</a>';
								echo '<span class="btn btn-info pull-right que_type" data-toggle="tooltip" data-placement="top" title="This question is only available in Non-MCQ">Non-MCQ</span>';
								if($subquestionList[$quesNum - 1] === 'B'){
									echo '<button class="btn btn-warning pull-right sub_question" id="subqid_'.$question->question_id.'"><span data-toggle="tooltip" data-placement="top" title="Add sub question, please">Sub Question</span></button>';
									echo '</li>';
									echo '</div>';
								}else {
									echo '</li>';
									echo '</div>';
								}
								/*echo '</li>';
								echo '</div>'; */
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
								echo '<input type="submit" class="btn btn-custom" value="Save as new worksheet" id="gen_button" data-toggle="modal" data-target="#saveWorksheetModal">';
								// echo '<form method="post" action="'.base_url().'smartgen/saveWorksheetAsPDF/'.$worksheetId.'" class="save_worksheet_form">';
							} else {
								echo '<input type="submit" class="btn btn-custom" value="Save worksheet to profile" id="gen_button" data-toggle="modal" data-target="#saveWorksheetModal">';
								// echo '<form method="post" action="'.base_url().'smartgen/saveWorksheetAsPDF" class="save_worksheet_form">';
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
	
	var target_id = JSON.parse("<?php echo $target_id ?>");
	
	$(function(){
		$.each( target_id, function (key, value) {
			$('#subqid_'+value).trigger('click');
		});
		
	});
	
	$(document).on('click', '.question-remove', function(e){
		
		e.preventDefault();
		var id = $(this).data('id');
		var obj = $(this);
		
		$.ajax({
			url: "<?php echo base_url(); ?>smartgen/ajax_remove_question",
			type: 'POST',
			dataType: 'text', // added data type
			data: {question_id:id},
			success: function(res) {
				
				if(res == '1'){
					
					//obj.parents('[id^=addNewSubQuestionDiv]').css('display','none');
					window.location.href = '<?php echo base_url();?>smartgen/generateWorksheet';
				}
				
			}
		});
	});
	
	$('.question_order').on('change',function(){
		
		var id = $(this).data('id');
		var obj = $(this);
		
		$.ajax({
			url: "<?php echo base_url(); ?>smartgen/ajax_order_question",
			type: 'POST',
			dataType: 'text', // added data type
			data: {question_id:id,position:obj.val(),sub_questions: obj.data('sub_question')},
			success: function(res) {
				
				if(res == '1'){
					
					//obj.parents('[id^=addNewSubQuestionDiv]').css('display','none');
					window.location.href = '<?php echo base_url();?>smartgen/generateWorksheet';
				}
				
			}
		});
		
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