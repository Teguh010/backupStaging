<link rel="stylesheet" href="<?php echo base_url()?>css/mathquill.min.css" />
<script src="<?php echo base_url(); ?>js/plugins/ckeditor-4.14-full/ckeditor.js"></script>

<!--************New Added Script Start-->
<link rel="stylesheet" href="<?php echo base_url()?>/node_modules/myscript/dist/myscript.min.css"/>
<link rel="stylesheet" href="<?php echo base_url()?>/css/examples.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/KaTeX/0.9.0/katex.min.css" integrity="sha384-TEMocfGvRuD1rIAacqrknm5BQZ7W7uWitoih+jMNFXQIbNl16bO8OZmylH/Vi/Ei" crossorigin="anonymous">

<script src="https://cdnjs.cloudflare.com/ajax/libs/KaTeX/0.9.0/katex.min.js" integrity="sha384-jmxIlussZWB7qCuB+PgKG1uLjjxbVVIayPJwi6cG6Zb4YKq0JIw+OMnkkEC7kYCq" crossorigin="anonymous"></script>
<script src="https://code.jquery.com/pep/0.4.3/pep.js"></script>
<script type="text/javascript" src="<?php echo base_url()?>node_modules/myscript/dist/myscript.min.js"></script>

<!--************New Added Script By Kok Liang on 21-12-2019-->
<script src="<?php echo base_url()?>js/imgFunctions.js"></script>
<!--************New Added Script End-->

<script src="<?php echo base_url()?>js/mathquill.js"></script>

<style>
	#quizSection {
		margin-top: -3em;
	}

	.question_text {
		padding-left: 0px;
	}

	.alert {
		padding: 15px;
		margin-bottom: 20px;
		border: 1px solid transparent;
		border-radius: 4px;
	}

	#editor{
		background-image: url('<?php echo base_url() ?>img/img/bg_grey.png');
		background-repeat: repeat;
		height: 270px;
		width: 100%;
		float: left;
	}

	#myscriptResult{
        border-bottom: 1px solid grey;
		width: 25%; 
		float:left;
	}
	
	#myscript {
		margin-top: 20px;
		/*height: 350px;*/
		border: 1px solid #D7DDE3;
	}
	#myscriptAdd, #myscriptMinus{
		float: right;
	/*	margin-top: 8px; */
		display: flex;
		align-items: center;
		justify-content: center;
	}
	.controlButtonDiv{
		text-align: center;
	}
	#imgUpload{
		display:none;
	}
	.timer{
		background: rgba(42,187,155,0.9);
		color: white;
		position: fixed;
		padding: 8px 0px 8px 8px;
		border-radius: 4px;
		box-shadow: 0px 0px 3px rgba(0,0,0,0.5);
		top: 10%;
		right: 0px;
		z-index: 9999;
		display: flex;
    	align-items: center;
		visibility: collapse;

	}
	.timer-icon{
		font-size: 24px;
    	padding-right: 8px;
	}
	.timer-content{
		transition: all 0.3s;
		width: 150px;
	}
	.timer-hide{
		width: 0px !important;
	}
	#remainingTime{
		font-size: 24px;
	}
	
	@media only screen and (max-width: 768px) {
		#imgUploadBtn{
			margin-top: 10px;
		}
		#myscript{
			/*height:300px*/;
		}

		#myscriptExpand{
			margin-top: 10px;
		}

		#myscriptQuestionExpand{
			margin-top: 10px;
		}

		#editor{
			height:180px;
			width: 100%;
			float: left;
		}
		
		.button-div {
			display: flex;
			margin: 5px;
			padding-right:15px;
		}
		.nav-div{
			height:100px;
		}
		
		.controlButtonDiv{
			border-top: 0px solid white;
			text-align: center;
		}

		.answerOpenEndedQuestion {
			margin-top: 10px;
		}
      }
      /* Pen color */
	.pen-default {
	    color: #FFFFFF;
	    font-size: 10px;
	}
	.pen-default::before {
		content: "";
		display: inline-block;
		width: 10px;
		height: 10px;
		background-color: #FFFFFF;
		border-radius: 50%;
		-moz-border-radius: 50%;
		-webkit-border-radius: 50%;
	}
	.pen-medium {
	    color: #FFFFFF;
	    font-size: 14px;
	}
	.pen-medium::before {
		content: "";
		display: inline-block;
		width: 15px;
		height: 15px;
		background-color: #FFFFFF;
		border-radius: 50%;
		-moz-border-radius: 50%;
		-webkit-border-radius: 50%;
		margin-top: 3px;
	}
	.pen-bold {
	    color: #FFFFFF;
	    font-size: 18px;
	}
	.pen-bold::before {
		content: "";
		display: inline-block;
		width: 20px;
		height: 20px;
		background-color: #FFFFFF;
		border-radius: 50%;
		-moz-border-radius: 50%;
		-webkit-border-radius: 50%;
		margin-top: 6px;
	}
	#colorPick * {
		-webkit-transition: all linear .2s;
		-moz-transition: all linear .2s;
		-ms-transition: all linear .2s;
		-o-transition: all linear .2s;
		transition: all linear .2s;
	}

	#colorPick {
		background: rgba(255, 255, 255, 0.85);
		-webkit-backdrop-filter: blur(15px);
		position: absolute;
		border-radius: 5px;
		box-shadow: 0px 3px 8px rgba(0, 0, 0, 0.2);
		padding: 5px;
		width: 60px;
		z-index: 100;
	}

	#colorPick span {
		font-size: 9pt;
		text-transform: uppercase;
		font-weight: bold;
		color: #bbb;
		margin-bottom: 5px;
		display: block;
		clear: both;
	}

	.colorPickButton {
		border-radius: 50%;
		width: 40px;
		height: 40px;
		margin: 1px 4px;
		cursor: pointer;
		display: inline-block;
	}

	.colorPickButton:hover {
		transform: scale(1.1);
	}

	.colorPickDummy {
		background: #fff;
		border: 1px dashed #bbb;
	}
	.colorPickSelector {
	  	cursor: pointer;
		display: inline-block;
		overflow: hidden;
		position: relative;
		margin: auto 12px auto auto;
		padding: 0;
		line-height: normal;
		border-radius: 50%;
		-webkit-box-shadow: 0 2px 5px 0 rgba(0, 0, 0, 0.225);
		box-shadow: 0 2px 5px 0 rgba(0, 0, 0, 0.225);
		-webkit-transition: all linear .2s;
		-moz-transition: all linear .2s;
		-ms-transition: all linear .2s;
		-o-transition: all linear .2s;
		transition: all linear .2s;
	}

	.colorPickSelector:hover { transform: scale(1.1); }

	#svgPreview { 
		display: inline-block;
		position: relative;
		width: 100%;
		vertical-align: middle; 
		overflow: hidden; 
		margin: 10px 0;
		border: 1px solid #D7DDE3;
	}
</style>
<script src="<?php echo base_url()?>js/ColorPick.js"></script>
<!--************New Added Script End-->

<div class="section">
	<div class="container">

		<div class="col-sm-12 col-md-12 col-lg-12" id="quizSection">
		</div>
		
		<div class="controlButtonDiv col-sm-12 col-md-12 col-lg-12 margin-top-custom showWhenQuizStart">
			  <ul class="pagination">
			    <li>
			      <a href="#" aria-label="Previous" id="prevQuestion">
			        <span aria-hidden="true">&laquo;</span>
			      </a>
			    </li>
			    <?php 
			    	for ($i = 0 ; $i < $quizNumOfQuestion; $i++ ) {
			    		echo '<li><a href="'.$i.'" id="quesNo_'.$i.'" class="selectQuestion">'.($quizQuestion[$i]['showQuestionNoText']).'</a></li>';
			    	}
			    ?>
			    <li>
			      <a href="#" aria-label="Next" id="nextQuestion">
			        <span aria-hidden="true">&raquo;</span>
			      </a>
			    </li>
			  </ul>
		</div>
		
		<div class="clearfix">
			<div class="text-center">
				<button class="btn btn-custom showWhenQuizStart" data-toggle="modal" data-target="#submitQuizModal">Submit Quiz</button>
			</div>
			<div class="answerUpdateToast">Answer updated</div>

			<div class="modal fade" id="submitQuizModal" role="dialog" tabindex="-1">
				<div class="modal-dialog" role="document">
					<div class="modal-content">
						<div class="modal-header modal-header-custom-success">
							<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
							<h4 class="modal-title">Submit Quiz</h4>
						</div>
						<form class="form-horizontal" action="<?php echo base_url(); ?>onlinequiz/submitQuiz" method="post" accept-charset="utf-8">
							<input type="hidden" name="quizNumOfQuestion" value="<?php echo $quizNumOfQuestion ?>">
							<input type="hidden" name="quizID" value="<?php echo $quizID ?>">
							<input type="hidden" name="quizAttemptDateTime" value="<?php echo $quizAttemptDateTime ?>">
							<input type="hidden" name="save_to" id="save_to" value="" />
							<?php
								for ($i = 0; $i < $quizNumOfQuestion; $i++) {
									echo '<input type="hidden" name="ques_' . $i . '" id="ques_' . $i . '">';
									echo '<input type="hidden" name="ocr_' . $i . '" id="ocr_' . $i . '">';
									echo '<input type="hidden" name="ocr_question_' . $i . '" id="ocr_question_' . $i . '">';
									echo '<input type="hidden" name="ocr_digitize_question_' . $i . '" id="ocr_digitize_question_' . $i . '">';
									echo '<input type="hidden" name="img_' . $i . '" id="img_' . $i . '">';
									echo '<input type="hidden" name="ocr_digitize_' . $i . '" id="ocr_digitize_' . $i . '">';
									echo '<input type="hidden" name="ocr_multiLine_' . $i . '" id="ocr_multiLine_' . $i . '">';
									echo '<input type="hidden" name="svg_' . $i . '" id="svg_' . $i . '">';
									echo '<input type="hidden" name="svg_question_' . $i . '" id="svg_question_' . $i . '">';
									// echo '<input type="hidden" name="ocr_convert_' . $i . '" id="ocr_convert_' . $i . '">';
								}
							?>
							<div class="modal-body">
								You will not be able to modify your answer after this action. Confirm to submit quiz answer?
							</div>
							<div class="modal-footer">
								<input type="button" class="btn btn-default" data-dismiss="modal" value="Cancel">
								<input type="submit" class="btn btn-custom submitQuiz" value="Submit">
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>

		<!-- <div class="modal fade" id="writeOCR" role="dialog" tabindex="-1">
			<div class="modal-dialog" role="document">
			<div class="modal-content">
			<div class="modal-header modal-header-custom-success">
			<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			<h4 class="modal-title">Answer Image</h4>
			</div>
			<div class="modal-body">
			<div id="new-svg-tutor">
					<div>
						<div id="modal-toolbar"></div>
						<div style="border: 1px solid #D7DDE3;width:100%;margin-bottom:0px;">
						<nav class="nav-editor" style="width:fit-content;width:-moz-fit-content;border:0;">
							<div class="button-div">
								<button id="clear" class="nav-btn btn-fab-mini btn-lightBlue" disabled>
									<img src="<?php echo base_url() ?>img/img/clear.svg">
								</button>
								<button id="undo" class="nav-btn btn-fab-mini btn-lightBlue" disabled>
									<img src="<?php echo base_url() ?>img/img/undo.svg">
								</button>
								<button id="redo" class="nav-btn btn-fab-mini btn-lightBlue" disabled>
									<img src="<?php echo base_url() ?>img/img/redo.svg">
								</button>
								<button id="convert" class="nav-btn btn-fab-mini btn-lightBlue" disabled>
									<img src="<?php echo base_url() ?>img/img/exchange-arrows.svg"></button>
								<button id="myscriptAdd" class="nav-btn btn-fab-mini btn-lightBlue">+</button>
								<button id="myscriptMinus" class="nav-btn btn-fab-mini btn-lightBlue">-</button>
							</div>
							<div class="button-div">
								<button id="colorPickSelector" class="nav-btn btn-fab-mini">
									<img src="<?php echo base_url() ?>img/img/edit.svg">
								</button>
								<button id="penPickSelector" class="nav-btn btn-fab-mini btn-lightBlue pen-default">
								</button>
								<div id="thicknessPick" style="display:none;">
								<button id="defPen" class="nav-btn btn-fab-mini btn-lightBlue pen-default">
								</button>
								<button id="medPen" class="nav-btn btn-fab-mini btn-lightBlue pen-medium">
								</button>
								<button id="boldPen" class="nav-btn btn-fab-mini btn-lightBlue pen-bold">
								</button>
							</div>
						</nav></div>
					</div>
					<div id="model-quiz-result-ocr"></div>
				</div>
				<h4 class="modal-title" id="title-svg-tutor" style="padding-top:10px;display: none;">Working Record (Tutor)</h4>
				<fieldset style="border:1px solid #ccc"><div id="model-quiz-result-svg-tutor"></div></fieldset>
				<div id="action-toolbar"></div>
				
		<h4 class="modal-title" style="padding-top:10px;">Working Record (Student)</h4><fieldset style="border:1px solid #ccc"><div id="model-quiz-result-svg-record"></div></fieldset>';

			</div>
			</div>
			</div>
			</div> -->


		<div class="hideWhenQuizStart">
			<div class="row">
				<div class="col-md-1 col-lg-1"></div>
				<div class="col-sm-6 col-md-5 col-lg-5">
					<img src="<?php echo base_url(); ?>img/img6.png" class="center-block img-responsive margin-top-custom"> 
				</div>
				<div class="col-sm-6 col-md-5 col-lg-5">
					<h1>Take Quiz</h1>
					<p>Not happy with your current results? Failed once in your test? Fret not, try again and again until
					you succeed. With customized questions framed in different difficulty levels, every student can
					repeatedly attempt their areas of weaknesses by just a few clicks. Endless questions will be
					generated to suit each one’s needs. With SmartJen easy-to-use online quiz, we make sure your child
					learn from their mistakes by keeping track of their progresses. Every single step taken is recorded
					and self-generated by our automated system until they reach perfect score. Nothing more than
					practices make perfect scorers.</p>	
				</div>
			</div>

			<div class="row hideWhenQuizStart">
				<?php 
					if (isset($quizError) && $quizError) {
						echo $quizErrorMessage;
					} else {
				?>
					<div class="col-lg-l2 col-md-12 col-sm-12 text-center">
						<h2><?php echo $quizName ?></h2>
						<small>created by <?php echo $quizOwner?></small>
						<h3>When you are ready, press the button below to start the quiz</h3>
						<button class="btn btn-custom" id="startQuiz">Start Quiz</button>
					</div>
				<?php
					}
				?>
			</div>
		</div>
		<div class="timer">
			<div class="icon-sm fa fa-clock-o timer-icon">
			</div>
			<div class="timer-content ">
				<div class="timer-header">
					Remaining Time:
				</div>
				<div class="timer-title" id="remainingTime">

				</div>
			<div>
		</div>
		</div>
		</div>
	</div>
</div>




<script type="text/javascript">
var mathKeyboard = $('#mathExpressionKeyboard');
var MQ = MathQuill.getInterface(2);

var currentQuestion = 0;
var quizId = <?php echo $quizID ?>;
var totalQuestion = <?php echo $quizNumOfQuestion ?>;
var numTotalQuestion = <?php echo $totalQuestion ?>;
var questionList = <?php echo $quizQuestionText ?>;
var questionType = '<?php echo $question_type ?>';
var quizTime = <?php echo $quizTime ?>;
var tutorId = <?php echo $quizOwnerId ?>;
let hasTimeLapse = false;
let answerId;
let mathSpanId;
let spanTarget;
let mathSpan;

function input(str) {
	let spanId = $('#mathTarget').val();
	let spanTarget = document.getElementById(spanId);
	let mathSpan = MQ(spanTarget);
	if (mathSpan) {
		mathKeyboard.modal('hide');
		mathSpan.cmd(str);
		mathSpan.focus();
	}
}

function inputMultiple(str1, str2) {
	let spanId = $('#mathTarget').val();
	let spanTarget = document.getElementById(spanId);
	let mathSpan = MQ(spanTarget);
	if (mathSpan) {
		mathKeyboard.modal('hide');
		mathSpan.cmd(str1);
		mathSpan.cmd(str2);
		mathSpan.focus();
	}
}
	
	
function ajaxQuestion(currentQuestion) {
	var data = questionList[currentQuestion];
	let numOpenEnded = 0;
	$('#quizSection').html('');  // clear quiz section div
	var questionDiv = $('<div class="questionDiv>"</div>');
	var questionNumber = '<h1 class="questionNumber">Question ' + data['showQuestionNoText'] + ' / ' + numTotalQuestion + '</h1>';
	var questionArea = '<div class="question_text col-sm-12 col-md-12 col-lg-12">';
	
	
	questionId = '<input type="hidden" value="' + data['questionId'] + '" name="current_question_id" id="current_question_id">';

	var instruction = data['questionInstruction'];
	if(instruction.length > 0){
		for (x=0 ; x<instruction.length ; x++) {
							
			if(instruction[x].content_type == 'text'){
				questionArea += instruction[x].header_content;
				questionArea += '<br>';
			} else {
				questionArea += '<img src="'+base_url+'img/instructionImage/'+instruction[x].header_content+'" draggable="false" class="img-responsive" style="display: block; width: 60%; ">';
				questionArea += '<br>';
			}
		}
	}

	var article = data['questionArticle'];
	if(article.length > 0){
		for (x=0 ; x<article.length ; x++) {
							
			if(article[x].content_type == 'text'){
				questionArea += article[x].header_content;
				questionArea += '<br>';
			} else {
				questionArea += '<img src="'+base_url+'img/articleImage/'+article[x].header_content+'" draggable="false" class="img-responsive" style="display: block; width: 60%; ">';
				questionArea += '<br>';
			}
		}
	}


	if (data['questionImg'] != "0") {
		questionArea += '<img src="' + data['questionImageUrl'] + '/questionImage/' + data['questionImg'] + '" class="img-responsive">';
	}

	questionType = (data['question_type']=='0') ? questionType : data['question_type'];
	
	if (questionType == 5){

		var $selectAnswers = '<select class="select_option_answer font400 input_select_rounded input_width_oq_150" name="select_option_answer_' + data['questionId'] + '[]" style="display: none;">';
        var $listAnswers = '<div class="p-1 mb-10 border2"><ul style="list-style-type:none; padding-left: 0;" class="list_four_column">';

		let answerOption = data['answerOption'];		
		for (let i = 0, l = answerOption.length; i < l; i++) {						
			$selectAnswers += '<option value="' + answerOption[i].answer_id + '">' + answerOption[i].answer_text + '</option>';
            $listAnswers += '<li>' + answerOption[i].answer_text + '</li>';	
		}
                    
        $selectAnswers += '</select> <a class="text-success cursor_pointer btnSaveAnswer font500" style="display: none;"><i class="picons-thin-icon-thin-0336_disc_floppy_save_software"></i></a>';
        $listAnswers += '</ul></div>';

		var _string = data['questionText'];
		var $question = _string.replace(/<p>|<p style="text-align:justify">|<p style="text-align:left">|<\/p>/g, '');
        $question = $question.replace(/<ans>/g, '<div class="start_select_answer input_width_oq_150" style="display: inline;">[___]<ans>');
        $question = $question.replace(/<\/ans>/g, '</ans></span>' + $selectAnswers + '</div>');
        var $array = [];
        $array = $question.split('[___]');
		$questions = "";

        for ($x = 0; $x < $array.length; $x++) {
			
            if ($x == ($array.length) - 1) {
                $questions += $array[$x];
            } else {				
            	$questions += $array[$x] + '('+($x+1)+') <span class="line_blank font500">___________ <i class="picons-thin-icon-thin-0001_compose_write_pencil_new"></i>';
			}

			$questions = $questions.replace(/<ans>.*<\/ans>/, '');

		}
		
		questionArea += $listAnswers + $questions + questionId + '</div>';
							
	} else if (questionType == 6){

		var _string = data['questionText'];
        var $question = _string.replace(/<p>|<p style="text-align:justify">|<p style="text-align:left">|<\/p>/g, '');
		$question = $question.replace(/<ans>/g, '<div class="start_input_answer input_width_oq_150" style="display: inline;">[___]<ans>');
        $question = $question.replace(/<\/ans>/g, '</ans></span><i class="btnEdit picons-thin-icon-thin-0001_compose_write_pencil_new"></i><input type="hidden" class="input_answer input_style2_rounded input_width_oq_150 shadow-sm" name="input_answer_' + data['questionId'] + '[]"> <a class="text-success cursor_pointer btnSaveAnswer font500" style="display: none;"><i class="picons-thin-icon-thin-0336_disc_floppy_save_software"></i></a></div>');
		var $array = [];		
		$array = $question.split('[___]');
		$questions = "";

        for ($x = 0; $x < $array.length; $x++) {
			
            if ($x == ($array.length) - 1) {
                $questions += $array[$x];
            } else {
				// var string = $array[$x].replace(/<ans>.*<\/ans>/, '');
				$questions += $array[$x] + '('+($x+1)+') <span class="line_blank font500">___________ ';				
			}

			$questions = $questions.replace(/<ans>.*<\/ans>/, '');

		}
		
		questionArea += $questions + questionId + '</div>';

	} else {
		questionArea += data['questionText'] + questionId + '</div>';
	}	
	
	if (questionType == 1) {  // mcq
		let answerOption = data['answerOption'];
		let answerOptionHtml = '';
		for (let i = 0, l = answerOption.length; i < l; i++) {
			if (answerOption[i].answer_id == $('#ques_' + currentQuestion).val()) {
				answerOptionHtml += '<a href="' + currentQuestion + '_' + answerOption[i].answer_id + '" class="btn btn-custom answerQuestion btn-selectedAnswer">' + answerOption[i].answer_text + '</a>';
			} else {
				answerOptionHtml += '<a href="' + currentQuestion + '_' + answerOption[i].answer_id + '" class="btn btn-custom answerQuestion">' + answerOption[i].answer_text + '</a>';
			}
		}

		answerOptionHtml += '<div style="clear: both;"></div>';
		numOpenEnded++;
		var answerValue = $('#ques_' + currentQuestion).val();

		answerOptionHtml += (
			'<div class="col-sm-6 col-md-6 col-lg-6" style="display: none;">'+
				'<span class="openEndedAnswer" style="width: 100%; padding: 0.5em" id="span_' + currentQuestion + '"></span>'+
			'</div>' +
			'<div style="clear: both;"></div><div style="padding-left: 0;" class="col-sm-6 col-md-6 col-lg-6">'+
				'<a id="myscriptExpand" class="btn btn-custom showScript btn-no-margin-top btn-selectedAnswer">'+
					'Show Writing Pad'+
				'</a>'+
				'<label id="imgUploadBtn" for="imgUpload" class="btn btn-no-margin-top btn-danger">'+
					(questionList[currentQuestion]["hasUpload"] ? 'Upload Successfully':'Upload Image')+
				'</label>'+
				'<input type="file" id="imgUpload" name="imgUpload" data-target="' + data['questionId'] + "_" + tutorId + "_" + quizId +'" accept="image/*"/>'+
			'</div>');
		// var answerOptionHtml = '<div class="col-sm-6 col-md-6 col-lg-6"><input class="form-control openEndedAnswer" type="text" placeholder="Type answer here and click the submit answer button"></div>' +
		// '<div class="col-sm-6 col-md-6 col-lg-6"><a href="' + currentQuestion + '" class="btn btn-custom answerOpenEndedQuestion btn-no-margin-top">Submit answer</a></div>';
		

		//************New Added Script Start*/
		answerOptionHtml += (
						'<div id="myscript" style="width: 100%; float:left;">'+
							'<div>'+
								'<nav>'+
									'<div class="button-div">'+
										'<button id="clear" class="nav-btn btn-fab-mini btn-lightBlue" disabled>'+
										'<img src="' + base_url + 'img/img/clear.svg">'+
										'</button>'+
										'<button id="undo" class="nav-btn btn-fab-mini btn-lightBlue" disabled>'+
										'<img src="' + base_url + 'img/img/undo.svg">'+
										'</button>'+
										'<button id="redo" class="nav-btn btn-fab-mini btn-lightBlue" disabled>'+
										'<img src="' + base_url + 'img/img/redo.svg">'+
										'</button>'+
										'<button id="convert" class="nav-btn btn-fab-mini btn-lightBlue" disabled>'+
										'<img src="' + base_url + 'img/img/exchange-arrows.svg">'+'</button>'+
										'<button id="myscriptAdd" class="nav-btn btn-fab-mini btn-lightBlue">+</button>'+
										'<button id="myscriptMinus" class="nav-btn btn-fab-mini btn-lightBlue">-</button>'+
									'</div>'+
									'<div class="spacer"></div>'+
									'<div class="button-div">'+
									'</div>'+
								'</nav>'+
								'<div id="editor" touch-action="none"></div>'+
								'<div id="myscriptResult" touch-action="none" style="width: 25%; float:left;"></div>'+
							'</div>'+
						'</div>'+
						'<div id="svgPreview"></div>');
		//************New Added Script End*/
		
		//var answerDiv = '<div class="answerOptions row form-group"> ' + answerOptionHtml + ' </div><div id="previewImage"></div>';

		var answerDiv = '<div class="answerOptions hideWhenNewQuesGen margin-bottom-custom"> ' + answerOptionHtml + '</div>';
	
	} else if (questionType == 2) {
		
		numOpenEnded++;
		var answerValue = $('#ques_' + currentQuestion).val();
		var answerOptionHtml = '';
		
		if (answerValue) {
			answerOptionHtml += (
				'<div class="col-sm-6 col-md-6 col-lg-6">'+
					'<span class="openEndedAnswer" style="width: 100%; padding: 0.5em" id="span_' + currentQuestion + '">' +
					 	answerValue + 
					'</span>'+
				'</div>' +
				'<div class="col-sm-6 col-md-6 col-lg-6">'+
					'<a id="myscriptExpand" class="btn btn-custom showScript btn-no-margin-top btn-selectedAnswer">'+
						'Show Writing Pad'+
					'</a>'+
					'<a href="' + currentQuestion + '" class="btn btn-custom answerOpenEndedQuestion btn-no-margin-top btn-selectedAnswer">'+
						'Submit answer'+
					'</a>'+
					//upload button
					//New Added Script By Kok Liang on 21-12-2019
					'<label id="imgUploadBtn" for="imgUpload" class="btn btn-no-margin-top btn-danger">'+
						(questionList[currentQuestion]["hasUpload"] ? 'Upload Successfully':'Upload Image')+
					'</label>'+
					'<input type="file" id="imgUpload" name="imgUpload" data-target="' + data['questionId'] + "_" + tutorId + "_" + quizId +'" accept="image/*"/>'+
					//New Added Script End By Kok Liang on 21-12-2019
				'</div>');
			// var answerOptionHtml = '<div class="col-sm-6 col-md-6 col-lg-6"><input class="form-control openEndedAnswer" type="text" placeholder="Type answer here and click the submit answer button" value="' + answerValue + '"></div>' +
			// '<div class="col-sm-6 col-md-6 col-lg-6"><a href="' + currentQuestion + '" class="btn btn-custom answerOpenEndedQuestion btn-no-margin-top">Submit answer</a></div>';
		} else {
			answerOptionHtml += (
				'<div class="col-sm-6 col-md-6 col-lg-6">'+
					'<span class="openEndedAnswer" style="width: 100%; padding: 0.5em" id="span_' + currentQuestion + '"></span>'+
				'</div>' +
				'<div class="col-sm-6 col-md-6 col-lg-6">'+
					'<a id="myscriptExpand" class="btn btn-custom showScript btn-no-margin-top btn-selectedAnswer">'+
						'Show Writing Pad'+
					'</a>'+
					(data['questionImg'] != "0" ? '<a id="myscriptQuestionExpand" class="btn btn-warning showScript btn-no-margin-top btn-selectedAnswer">'+
						'Question Image'+
					'</a>':'') +
					'<input type="hidden" value="' + data['questionImg'] + '" id="question_image" name="question_image">'+
					// '<a id="ocrWrite_' + currentQuestion +'" class="btn btn-warning ocrWrite btn-no-margin-top btn-selectedAnswer" data-toggle="modal" data-target="#writeOCR" data-id="'+ quizId +'_' + currentQuestion +'_' + quesNum +'">'+
					// 	'Answer Image'+
					// '</a>'+
					'</a>'+
					'<a href="' + currentQuestion + '" class="btn btn-custom answerOpenEndedQuestion btn-no-margin-top">'+
						'Submit answer'+
					'</a>'+
					'<label id="imgUploadBtn" for="imgUpload" class="btn btn-no-margin-top btn-danger">'+
						(questionList[currentQuestion]["hasUpload"] ? 'Upload Successfully':'Upload Image')+
					'</label>'+
					'<input type="file" id="imgUpload" name="imgUpload" data-target="' + data['questionId'] + "_" + tutorId + "_" + quizId +'" accept="image/*"/>'+
				'</div>');
			// var answerOptionHtml = '<div class="col-sm-6 col-md-6 col-lg-6"><input class="form-control openEndedAnswer" type="text" placeholder="Type answer here and click the submit answer button"></div>' +
			// '<div class="col-sm-6 col-md-6 col-lg-6"><a href="' + currentQuestion + '" class="btn btn-custom answerOpenEndedQuestion btn-no-margin-top">Submit answer</a></div>';
		}

		//************New Added Script Start*/
		answerOptionHtml += (
						'<div id="myscript" style="width: 100%; float:left;">'+
							'<div>'+
								'<nav>'+
									'<div class="button-div">'+
										'<button id="clear" class="nav-btn btn-fab-mini btn-lightBlue" disabled>'+
										'<img src="' + base_url + 'img/img/clear.svg">'+
										'</button>'+
										'<button id="undo" class="nav-btn btn-fab-mini btn-lightBlue" disabled>'+
										'<img src="' + base_url + 'img/img/undo.svg">'+
										'</button>'+
										'<button id="redo" class="nav-btn btn-fab-mini btn-lightBlue" disabled>'+
										'<img src="' + base_url + 'img/img/redo.svg">'+
										'</button>'+
										'<button id="convert" class="nav-btn btn-fab-mini btn-lightBlue" disabled>'+
										'<img src="' + base_url + 'img/img/exchange-arrows.svg">'+'</button>'+
										'<button id="myscriptAdd" class="nav-btn btn-fab-mini btn-lightBlue">+</button>'+
										'<button id="myscriptMinus" class="nav-btn btn-fab-mini btn-lightBlue">-</button>'+
									'</div>'+
									'<div class="spacer"></div>'+
									'<div class="button-div">'+
									'</div>'+
								'</nav>'+
								'<div id="editor" touch-action="none"></div>'+
								'<div id="myscriptResult" touch-action="none" style="width: 25%; float:left;"></div>'+
							'</div>'+
						'</div>'+
						'<div id="svgPreview"></div>');
		//************New Added Script End*/
		
		var answerDiv = '<div class="answerOptions row form-group"> ' + answerOptionHtml + ' </div><div id="previewImage"></div>';
	} else if (questionType == 3){
		let answerOption = data['answerOption'];
		let answerOptionHtml = '';

		if(answerOption[0].answer_text == 'True'){
			answerOptionHtml += '<a href="' + currentQuestion + '_' + answerOption[0].answer_id + '" class="btn btn-custom answerQuestion">' + answerOption[0].answer_text + '</a>';
			answerOptionHtml += '<a href="' + currentQuestion + '_0" class="btn btn-custom answerQuestion">False</a>';
		}else{
			answerOptionHtml += '<a href="' + currentQuestion + '_0" class="btn btn-custom answerQuestion">True</a>';
			answerOptionHtml += '<a href="' + currentQuestion + '_' + answerOption[0].answer_id + '" class="btn btn-custom answerQuestion">' + answerOption[0].answer_text + '</a>';			
		}

		answerOptionHtml += '<div style="clear: both;"></div>';
		numOpenEnded++;
		var answerValue = $('#ques_' + currentQuestion).val();

		answerOptionHtml += (
			'<div class="col-sm-6 col-md-6 col-lg-6" style="display: none;">'+
				'<span class="openEndedAnswer" style="width: 100%; padding: 0.5em" id="span_' + currentQuestion + '"></span>'+
			'</div>' +
			'<div style="clear: both;"></div><div style="padding-left: 0;" class="col-sm-6 col-md-6 col-lg-6">'+
				'<a id="myscriptExpand" class="btn btn-custom showScript btn-no-margin-top btn-selectedAnswer">'+
					'Show Writing Pad'+
				'</a>'+
				'<label id="imgUploadBtn" for="imgUpload" class="btn btn-no-margin-top btn-danger">'+
					(questionList[currentQuestion]["hasUpload"] ? 'Upload Successfully':'Upload Image')+
				'</label>'+
				'<input type="file" id="imgUpload" name="imgUpload" data-target="' + data['questionId'] + "_" + tutorId + "_" + quizId +'" accept="image/*"/>'+
			'</div>');		

		//************New Added Script Start*/
		answerOptionHtml += (
						'<div id="myscript" style="width: 100%; float:left;">'+
							'<div>'+
								'<nav>'+
									'<div class="button-div">'+
										'<button id="clear" class="nav-btn btn-fab-mini btn-lightBlue" disabled>'+
										'<img src="' + base_url + 'img/img/clear.svg">'+
										'</button>'+
										'<button id="undo" class="nav-btn btn-fab-mini btn-lightBlue" disabled>'+
										'<img src="' + base_url + 'img/img/undo.svg">'+
										'</button>'+
										'<button id="redo" class="nav-btn btn-fab-mini btn-lightBlue" disabled>'+
										'<img src="' + base_url + 'img/img/redo.svg">'+
										'</button>'+
										'<button id="convert" class="nav-btn btn-fab-mini btn-lightBlue" disabled>'+
										'<img src="' + base_url + 'img/img/exchange-arrows.svg">'+'</button>'+
										'<button id="myscriptAdd" class="nav-btn btn-fab-mini btn-lightBlue">+</button>'+
										'<button id="myscriptMinus" class="nav-btn btn-fab-mini btn-lightBlue">-</button>'+
									'</div>'+
									'<div class="spacer"></div>'+
									'<div class="button-div">'+
									'</div>'+
								'</nav>'+
								'<div id="editor" touch-action="none"></div>'+
								'<div id="myscriptResult" touch-action="none" style="width: 25%; float:left;"></div>'+
							'</div>'+
						'</div>'+
						'<div id="svgPreview"></div>');
		//************New Added Script End*/
		
		//var answerDiv = '<div class="answerOptions row form-group"> ' + answerOptionHtml + ' </div><div id="previewImage"></div>';

		var answerDiv = '<div class="answerOptions hideWhenNewQuesGen margin-bottom-custom"> ' + answerOptionHtml + '</div>';
	} else if (questionType == 5){
		let answerOptionHtml = '';
				
				answerOptionHtml += '<div style="clear: both;"></div>';
				numOpenEnded++;
				var answerValue = $('#ques_' + currentQuestion).val();
				
				//************New Added Script Start*/
				answerOptionHtml += (
								'<div id="myscript" style="width: 100%; float:left;">'+
									'<div>'+
										'<nav>'+
											'<div class="button-div">'+
												'<button id="clear" class="nav-btn btn-fab-mini btn-lightBlue" disabled>'+
												'<img src="' + base_url + 'img/img/clear.svg">'+
												'</button>'+
												'<button id="undo" class="nav-btn btn-fab-mini btn-lightBlue" disabled>'+
												'<img src="' + base_url + 'img/img/undo.svg">'+
												'</button>'+
												'<button id="redo" class="nav-btn btn-fab-mini btn-lightBlue" disabled>'+
												'<img src="' + base_url + 'img/img/redo.svg">'+
												'</button>'+
												'<button id="convert" class="nav-btn btn-fab-mini btn-lightBlue" disabled>'+
												'<img src="' + base_url + 'img/img/exchange-arrows.svg">'+'</button>'+
												'<button id="myscriptAdd" class="nav-btn btn-fab-mini btn-lightBlue">+</button>'+
												'<button id="myscriptMinus" class="nav-btn btn-fab-mini btn-lightBlue">-</button>'+
											'</div>'+
											'<div class="spacer"></div>'+
											'<div class="button-div">'+
											'</div>'+
										'</nav>'+
										'<div id="editor" touch-action="none"></div>'+
										'<div id="myscriptResult" touch-action="none" style="width: 25%; float:left;"></div>'+
									'</div>'+
								'</div>'+
								'<div id="svgPreview"></div>');
				//************New Added Script End*/
				
				//var answerDiv = '<div class="answerOptions row form-group"> ' + answerOptionHtml + ' </div><div id="previewImage"></div>';
		
				var answerDiv = '<div class="answerOptions hideWhenNewQuesGen margin-bottom-custom"> ' + answerOptionHtml + '</div>';
	} else if (questionType == 6){		
		let answerOptionHtml = '';
				
		answerOptionHtml += '<div style="clear: both;"></div>';
		numOpenEnded++;
		var answerValue = $('#ques_' + currentQuestion).val();
		
		//************New Added Script Start*/
		answerOptionHtml += (
						'<div id="myscript" style="width: 100%; float:left;">'+
							'<div>'+
								'<nav>'+
									'<div class="button-div">'+
										'<button id="clear" class="nav-btn btn-fab-mini btn-lightBlue" disabled>'+
										'<img src="' + base_url + 'img/img/clear.svg">'+
										'</button>'+
										'<button id="undo" class="nav-btn btn-fab-mini btn-lightBlue" disabled>'+
										'<img src="' + base_url + 'img/img/undo.svg">'+
										'</button>'+
										'<button id="redo" class="nav-btn btn-fab-mini btn-lightBlue" disabled>'+
										'<img src="' + base_url + 'img/img/redo.svg">'+
										'</button>'+
										'<button id="convert" class="nav-btn btn-fab-mini btn-lightBlue" disabled>'+
										'<img src="' + base_url + 'img/img/exchange-arrows.svg">'+'</button>'+
										'<button id="myscriptAdd" class="nav-btn btn-fab-mini btn-lightBlue">+</button>'+
										'<button id="myscriptMinus" class="nav-btn btn-fab-mini btn-lightBlue">-</button>'+
									'</div>'+
									'<div class="spacer"></div>'+
									'<div class="button-div">'+
									'</div>'+
								'</nav>'+
								'<div id="editor" touch-action="none"></div>'+
								'<div id="myscriptResult" touch-action="none" style="width: 25%; float:left;"></div>'+
							'</div>'+
						'</div>'+
						'<div id="svgPreview"></div>');
		//************New Added Script End*/
		
		//var answerDiv = '<div class="answerOptions row form-group"> ' + answerOptionHtml + ' </div><div id="previewImage"></div>';

		var answerDiv = '<div class="answerOptions hideWhenNewQuesGen margin-bottom-custom"> ' + answerOptionHtml + '</div>';
	} else if (questionType == 8){
		let answerOption = data['answerOption'];
		let answerOptionHtml = '';
		for (let i = 0, l = answerOption.length; i < l; i++) {
			if (answerOption[i].answer_id == $('#ques_' + currentQuestion).val()) {
				answerOptionHtml += '<a href="' + currentQuestion + '_' + answerOption[i].answer_id + '" class="btn btn-custom answerQuestion btn-selectedAnswer">' + answerOption[i].answer_text + '</a>';
			} else {
				answerOptionHtml += '<a href="' + currentQuestion + '_' + answerOption[i].answer_id + '" class="btn btn-custom answerQuestion">' + answerOption[i].answer_text + '</a>';
			}
		}

		answerOptionHtml += '<div style="clear: both;"></div>';
		numOpenEnded++;
		var answerValue = $('#ques_' + currentQuestion).val();

		answerOptionHtml += (
			'<div class="col-sm-6 col-md-6 col-lg-6" style="display: none;">'+
				'<span class="openEndedAnswer" style="width: 100%; padding: 0.5em" id="span_' + currentQuestion + '"></span>'+
			'</div>' +
			'<div style="clear: both;"></div><div style="padding-left: 0;" class="col-sm-6 col-md-6 col-lg-6">'+
				'<a id="myscriptExpand" class="btn btn-custom showScript btn-no-margin-top btn-selectedAnswer">'+
					'Show Writing Pad'+
				'</a>'+
				'<label id="imgUploadBtn" for="imgUpload" class="btn btn-no-margin-top btn-danger">'+
					(questionList[currentQuestion]["hasUpload"] ? 'Upload Successfully':'Upload Image')+
				'</label>'+
				'<input type="file" id="imgUpload" name="imgUpload" data-target="' + data['questionId'] + "_" + tutorId + "_" + quizId +'" accept="image/*"/>'+
			'</div>');
		// var answerOptionHtml = '<div class="col-sm-6 col-md-6 col-lg-6"><input class="form-control openEndedAnswer" type="text" placeholder="Type answer here and click the submit answer button"></div>' +
		// '<div class="col-sm-6 col-md-6 col-lg-6"><a href="' + currentQuestion + '" class="btn btn-custom answerOpenEndedQuestion btn-no-margin-top">Submit answer</a></div>';
		

		//************New Added Script Start*/
		answerOptionHtml += (
						'<div id="myscript" style="width: 100%; float:left;">'+
							'<div>'+
								'<nav>'+
									'<div class="button-div">'+
										'<button id="clear" class="nav-btn btn-fab-mini btn-lightBlue" disabled>'+
										'<img src="' + base_url + 'img/img/clear.svg">'+
										'</button>'+
										'<button id="undo" class="nav-btn btn-fab-mini btn-lightBlue" disabled>'+
										'<img src="' + base_url + 'img/img/undo.svg">'+
										'</button>'+
										'<button id="redo" class="nav-btn btn-fab-mini btn-lightBlue" disabled>'+
										'<img src="' + base_url + 'img/img/redo.svg">'+
										'</button>'+
										'<button id="convert" class="nav-btn btn-fab-mini btn-lightBlue" disabled>'+
										'<img src="' + base_url + 'img/img/exchange-arrows.svg">'+'</button>'+
										'<button id="myscriptAdd" class="nav-btn btn-fab-mini btn-lightBlue">+</button>'+
										'<button id="myscriptMinus" class="nav-btn btn-fab-mini btn-lightBlue">-</button>'+
									'</div>'+
									'<div class="spacer"></div>'+
									'<div class="button-div">'+
									'</div>'+
								'</nav>'+
								'<div id="editor" touch-action="none"></div>'+
								'<div id="myscriptResult" touch-action="none" style="width: 25%; float:left;"></div>'+
							'</div>'+
						'</div>'+
						'<div id="svgPreview"></div>');
		//************New Added Script End*/
		
		//var answerDiv = '<div class="answerOptions row form-group"> ' + answerOptionHtml + ' </div><div id="previewImage"></div>';

		var answerDiv = '<div class="answerOptions hideWhenNewQuesGen margin-bottom-custom"> ' + answerOptionHtml + '</div>';
	
	} else if (questionType == 9){
		var answerValue = $('#ques_' + currentQuestion).val();
		var answerOptionHtml = '';
		
		if (answerValue) {
			answerOptionHtml += (
				'<div class="col-sm-6 col-md-6 col-lg-6">'+
					'<textarea name="input_cktext_' + data['questionId'] + '" id="input_cktext_' + data['questionId'] + '" spellcheck="false">' + answerValue + '</textarea>'+
					'<textarea name="input_text_' + data['questionId'] + '" id="input_text_' + data['questionId'] + '" style="display: none;">' + answerValue + '</textarea>'+					 
				'</div>' +
				'<div class="col-sm-6 col-md-6 col-lg-6">'+
					'<a id="myscriptExpand" class="btn btn-custom showScript btn-no-margin-top btn-selectedAnswer">'+
						'Show Writing Pad'+
					'</a>'+
					'<a href="' + currentQuestion + '" class="btn btn-custom answerOpenEndedQuestion btn-no-margin-top btn-selectedAnswer">'+
						'Submit answer'+
					'</a>'+
					//upload button
					//New Added Script By Kok Liang on 21-12-2019
					'<label id="imgUploadBtn" for="imgUpload" class="btn btn-no-margin-top btn-danger">'+
						(questionList[currentQuestion]["hasUpload"] ? 'Upload Successfully':'Upload Image')+
					'</label>'+
					'<input type="file" id="imgUpload" name="imgUpload" data-target="' + data['questionId'] + "_" + tutorId + "_" + quizId +'" accept="image/*"/>'+
					//New Added Script End By Kok Liang on 21-12-2019
				'</div>');
			// var answerOptionHtml = '<div class="col-sm-6 col-md-6 col-lg-6"><input class="form-control openEndedAnswer" type="text" placeholder="Type answer here and click the submit answer button" value="' + answerValue + '"></div>' +
			// '<div class="col-sm-6 col-md-6 col-lg-6"><a href="' + currentQuestion + '" class="btn btn-custom answerOpenEndedQuestion btn-no-margin-top">Submit answer</a></div>';
		} else {
			answerOptionHtml += (
				'<div class="col-sm-6 col-md-6 col-lg-6">'+
					'<textarea name="input_cktext_' + data['questionId'] + '" id="input_cktext_' + data['questionId'] + '" spellcheck="false"></textarea>'+
					'<textarea name="input_text_' + data['questionId'] + '" id="input_text_' + data['questionId'] + '" style="display: none;"></textarea>'+					
				'</div>' +
				'<div class="col-sm-6 col-md-6 col-lg-6">'+
					'<a id="myscriptExpand" class="btn btn-custom showScript btn-no-margin-top btn-selectedAnswer">'+
						'Show Writing Pad'+
					'</a>'+
					(data['questionImg'] != "0" ? '<a id="myscriptQuestionExpand" class="btn btn-warning showScript btn-no-margin-top btn-selectedAnswer">'+
						'Question Image'+
					'</a>':'') +
					'<input type="hidden" value="' + data['questionImg'] + '" id="question_image" name="question_image">'+
					// '<a id="ocrWrite_' + currentQuestion +'" class="btn btn-warning ocrWrite btn-no-margin-top btn-selectedAnswer" data-toggle="modal" data-target="#writeOCR" data-id="'+ quizId +'_' + currentQuestion +'_' + quesNum +'">'+
					// 	'Answer Image'+
					// '</a>'+
					'</a>'+
					'<a href="' + currentQuestion + '" class="btn btn-custom answerOpenEndedQuestion btn-no-margin-top">'+
						'Submit answer'+
					'</a>'+
					'<label id="imgUploadBtn" for="imgUpload" class="btn btn-no-margin-top btn-danger">'+
						(questionList[currentQuestion]["hasUpload"] ? 'Upload Successfully':'Upload Image')+
					'</label>'+
					'<input type="file" id="imgUpload" name="imgUpload" data-target="' + data['questionId'] + "_" + tutorId + "_" + quizId +'" accept="image/*"/>'+
				'</div>');
			// var answerOptionHtml = '<div class="col-sm-6 col-md-6 col-lg-6"><input class="form-control openEndedAnswer" type="text" placeholder="Type answer here and click the submit answer button"></div>' +
			// '<div class="col-sm-6 col-md-6 col-lg-6"><a href="' + currentQuestion + '" class="btn btn-custom answerOpenEndedQuestion btn-no-margin-top">Submit answer</a></div>';
		}

		//************New Added Script Start*/
		answerOptionHtml += (
						'<div id="myscript" style="width: 100%; float:left;">'+
							'<div>'+
								'<nav>'+
									'<div class="button-div">'+
										'<button id="clear" class="nav-btn btn-fab-mini btn-lightBlue" disabled>'+
										'<img src="' + base_url + 'img/img/clear.svg">'+
										'</button>'+
										'<button id="undo" class="nav-btn btn-fab-mini btn-lightBlue" disabled>'+
										'<img src="' + base_url + 'img/img/undo.svg">'+
										'</button>'+
										'<button id="redo" class="nav-btn btn-fab-mini btn-lightBlue" disabled>'+
										'<img src="' + base_url + 'img/img/redo.svg">'+
										'</button>'+
										'<button id="convert" class="nav-btn btn-fab-mini btn-lightBlue" disabled>'+
										'<img src="' + base_url + 'img/img/exchange-arrows.svg">'+'</button>'+
										'<button id="myscriptAdd" class="nav-btn btn-fab-mini btn-lightBlue">+</button>'+
										'<button id="myscriptMinus" class="nav-btn btn-fab-mini btn-lightBlue">-</button>'+
									'</div>'+
									'<div class="spacer"></div>'+
									'<div class="button-div">'+
									'</div>'+
								'</nav>'+
								'<div id="editor" touch-action="none"></div>'+
								'<div id="myscriptResult" touch-action="none" style="width: 25%; float:left;"></div>'+
							'</div>'+
						'</div>'+
						'<div id="svgPreview"></div>');
		//************New Added Script End*/
		
		var answerDiv = '<div class="answerOptions row form-group"> ' + answerOptionHtml + ' </div><div id="previewImage"></div>';
	}

	$('#quizSection').append(questionNumber + questionArea + answerDiv);


	if (questionType == 9){

		var text_input = $('#input_text_' + data['questionId']);
	
		var editor = CKEDITOR.replace('input_cktext_' + data["questionId"] + '', {                
			height: 100,			
			toolbar: [								
				{ name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ], items: [ 'Bold', 'Italic', 'Underline', 'Strike', 'Subscript', 'Superscript', '-', 'RemoveFormat' ] },
				{ name: 'paragraph', groups: [ 'list', 'indent', 'blocks', 'align', 'bidi' ], items: [ 'NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'Blockquote', 'CreateDiv', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock', '-', 'BidiLtr', 'BidiRtl', 'Language' ] }												
			]
		});
	
		editor.on('contentDom', function () {
			this.document.on('click', function (event) {
				text_input.html(editor.getData().toString());
			});
	
		});
	
		editor.on('change', function (e) {
			text_input.html(editor.getData().toString());
		});

	}
	
	//************New Added Script Start*/
	if(true) { // questionType == 2){ <<< Writing Pad for Non-MCQ too by Cahyono
		//hide myscript
		$("#myscript").hide();
		activateMyScript(currentQuestion);
		//listen to "show writing pad button"
		$("#myscriptExpand").on('click', function() {
			var svg_answer = $('#svg_' + currentQuestion).val();
			// alert(svg_answer);
			// if(svg_answer=="") {
				$("#myscript").show();
				
				//import answer
				//*****Variable for handwriting data to be Defined by Justin */
				//import data back to handwriting script
				var editorElement = document.getElementById('editor');
				editorElement.editor.clear();

				editorElement.style.backgroundImage = "url('<?php echo base_url() ?>img/img/bg_grey.png')";
				editorElement.style.backgroundRepeat = "repeat";
				editorElement.style.backgroundSize = "unset";

				editorElement.editor.clear();
				if(questionList[currentQuestion]['OCRrecorded']){
					editorElement.editor.import_(questionList[currentQuestion]['OCRrecorded']["application/vnd.myscript.jiix"],"application/vnd.myscript.jiix");
				}
				$('#save_to').val('svg');
				$('.loader').css('display','none');
				if($("#myscript").is(":visible")){
					if(questionList[currentQuestion]['multiply']){
						updateHeight();
					}
					resizeEditor();
					editorElement.addEventListener('loaded', function(){
						resizeEditor();
					});
				}
			// } else {
			// 	var svgPreviewElement = document.getElementById('svgPreview');
			// 	var svg_file = svg_answer;
			// 	svg_file = svg_file.replace('<div class="loader" style="display: none;"></div><div class="error-msg" style="display: none;"></div><svg data-layer="BACKGROUND" x="0px" y="0px" width="1138px" height="270px" viewBox="0 0 1138, 270">','');
			// 	svg_file = svg_file.replace('</svg>','');
			// 	svg_file = svg_file + '</svg>';
			// 	svg_file = svg_file.replace('width="1138px" height="270px"','');
			// 	var div_button = '<div style="padding:10px 3px;"><button id="changeSvg" class="btn btn-custom btn-no-margin-top btn-selectedAnswer">Change</button></div>';
			// 	svgPreviewElement.innerHTML = svg_file + div_button;
			// 	bindingFunction();
			// }
			
		});

		$("#myscriptQuestionExpand").on('click', function() {
			// var svg_answer = $('#svg_' + currentQuestion).val();
			var question_image = $('#question_image').val();
			var OCRrecorded = document.getElementById('ocr_question_' + currentQuestion).value;
			// if(svg_answer=="") {
				$("#myscript").show();
				
				//import answer
				//*****Variable for handwriting data to be Defined by Justin */
				//import data back to handwriting script
				var editorElement = document.getElementById('editor');
				editorElement.editor.clear();

				var bg_question = 'https://smartjen.com/img/questionImage/' + question_image;
				editorElement.style.backgroundImage = "url(' "+ bg_question + " ')";
				editorElement.style.backgroundRepeat = "no-repeat";
				editorElement.style.backgroundSize = "contain";
				editorElement.editor.clear();
				//editorElement.editor.import_(OCRrecorded,"application/vnd.myscript.jiix");
				if(questionList[currentQuestion]['OCRrecorded_question']){
					editorElement.editor.import_(questionList[currentQuestion]['OCRrecorded_question']["application/vnd.myscript.jiix"],"application/vnd.myscript.jiix");
				}
				// $('#svg_tutor_bg').val(bg_uploaded);
				$('#save_to').val('svg_question');
				$('.loader').css('display','none');
				// if(questionList[currentQuestion]['OCR_question']){
				// 	editorElement.editor.import_(questionList[currentQuestion]['OCR_question']["application/vnd.myscript.jiix"],"application/vnd.myscript.jiix");
				// }
      
				if($("#myscript").is(":visible")){
					if(questionList[currentQuestion]['multiply']){
						updateHeight();
					}
					resizeEditor();
					editorElement.addEventListener('loaded', function(){
						resizeEditor();
					});
				}
				
			// } else {
			// 	var svgPreviewElement = document.getElementById('svgPreview');
			// 	var svg_file = svg_answer;
			// 	svg_file = svg_file.replace('<div class="loader" style="display: none;"></div><div class="error-msg" style="display: none;"></div><svg data-layer="BACKGROUND" x="0px" y="0px" width="1138px" height="270px" viewBox="0 0 1138, 270">','');
			// 	svg_file = svg_file.replace('</svg>','');
			// 	svg_file = svg_file + '</svg>';
			// 	svg_file = svg_file.replace('width="1138px" height="270px"','');
			// 	var div_button = '<div style="padding:10px 3px;"><button id="changeSvg" class="btn btn-custom btn-no-margin-top btn-selectedAnswer">Change</button></div>';
			// 	svgPreviewElement.innerHTML = svg_file + div_button;
			// 	bindingFunction();
			// }
			
		});

		function bindingFunction(){
		    document.getElementById('changeSvg').onclick = function() {
				$("#myscript").toggle();
				var editorElement = document.getElementById('editor');
				if(questionList[currentQuestion]['OCRrecorded']){
					editorElement.editor.import_(questionList[currentQuestion]['OCRrecorded']["application/vnd.myscript.jiix"],"application/vnd.myscript.jiix");
				}
				if($("#myscript").is(":visible")){
					if(questionList[currentQuestion]['multiply']){
						updateHeight();
					}
					resizeEditor();
					editorElement.addEventListener('loaded', function(){
						resizeEditor();
					});
				}
				var svgPreviewElement = document.getElementById('svgPreview');
				var div_button = '<div style="padding:10px 3px;"><button id="cancelSvg" class="btn btn-custom btn-no-margin-top btn-danger">Cancel</button></div>';
				svgPreviewElement.innerHTML = div_button;
				bindingFunction2();
		    }
		}

		function bindingFunction2(){	
		    document.getElementById('cancelSvg').onclick = function() {
		    	var svgPreviewElement = document.getElementById('svgPreview');
				svgPreviewElement.innerHTML = '';
		    	$("#myscript").hide();
		    }
		}
		
		function updateHeight(){
			var multiply = questionList[currentQuestion]['multiply'];
			var height = $('#myscript').height();
			var editorHeight = $('#editor').height();
			$('#myscript').height(height + questionList[currentQuestion]['multiply'] * 300);
			$('#editor').height(editorHeight + questionList[currentQuestion]['multiply'] * 300);
		}

		$('#myscriptAdd').on('click', function(){
			var myscriptEditor = document.getElementById('myscript');
			var height = $('#myscript').height();
			var editorHeight = $('#editor').height();
			height += 300;
			editorHeight+= 300;
			$('#myscript').height(height);
			$('#editor').height(editorHeight);
			if(questionList[currentQuestion]['multiply']){
				questionList[currentQuestion]['multiply']++;
			}else{
				questionList[currentQuestion]['multiply'] = 1;
			}
			$('#ocr_multiLine_' + currentQuestion).val(questionList[currentQuestion]['multiply']);
			resizeEditor();
		});
		$('#myscriptMinus').on('click', function(){
			var myscriptEditor = document.getElementById('myscript');
			var height = $('#myscript').height();
			var editorHeight = $('#editor').height();
			height -= 300;
			editorHeight -= 300;
			
			if(questionList[currentQuestion]['multiply'] >= 1){
				questionList[currentQuestion]['multiply']--;
				$('#myscript').height(height);
				$('#editor').height(editorHeight);
				$('#ocr_multiLine_' + currentQuestion).val(questionList[currentQuestion]['multiply']);
			}
			resizeEditor();
		});
	}

	function resizeEditor(){
		var editorElement = document.getElementById('editor');
		window.setTimeout(function(){
			editorElement.editor.resize();
		},500);
	}
	//************New Added Script End*/

	if (numOpenEnded > 0) {
		$('#mathExpressionBtn').show();
		$('#help-text').show();
		$('.openEndedAnswer').each(function(index) {
			let span_id = $(this).attr('id');
			if (index == 0) {
				$('#mathTarget').val(span_id);
			}
			var mathFieldSpan = document.getElementById(span_id);
			var MQ = MathQuill.getInterface(2);
			var mathField = MQ.MathField(mathFieldSpan, {
				handlers: {
					edit: function() {
						mathField.focus();
					}
				},
				autoOperatorNames: 'somelongrandomoperatortooverride'
			});
		});

		//New Added Script By Kok Liang on 21-12-2019
		
		$('#imgUpload').on('change', function(){
			var file_data = $('#imgUpload').prop('files')[0];
			
			//check if picture is image
			if(!checkFileExt(file_data)){
				alert('Only Image is allowed')
			}else{
				//resize data
				_previewImage(file_data,500).then(function(result){
					$('#img_' + currentQuestion).val(result.split(',')[1]); 
					$('#imgUploadBtn').text("Upload Successfully");
					questionList[currentQuestion]["hasUpload"] = true;
				});
			}
			
		});
	} else {
		$('#mathExpressionBtn').hide();
		$('#help-text').hide();
	}
		

	$('.showWhenQuizStart').removeClass("showWhenQuizStart");
	$('.currentQuestion').removeClass('currentQuestion');
	$('#quesNo_' + currentQuestion).addClass("currentQuestion");
	MathJax.Hub.Typeset();
}


	$(document).on('click', '.start_input_answer', function (e) {
		// e.preventDefault();
		$(this).find('span').hide();
		$(this).find('.btnEdit').hide();
		$(this).find('input').attr('type', 'text').attr('style', 'text-align: center');
		$(this).find('input').focus();
		$(this).find('.btnSaveAnswer').show();

		// $('.input_answer').keypress(function (e) {

			// if (e.which == 13) {
			// 	var answer = $(this).val();

			// 	if (answer !== '') {
			// 		$(this).hide();
			// 		$(this).parent().find('.line_blank').html(answer + ' ');
			// 		$(this).parent().find('.line_blank').removeClass('text-danger').addClass('text-danger').attr('style', 'display: inline-block; text-align: center; letter-spacing: 2px; border-bottom:1px solid; min-width: 130px; width: auto;');
			// 		$(this).parent().find('.line_blank').show();
			// 		$(this).parent().find('.btnEdit').show();
			// 		$(this).parent().find('.btnSaveAnswer').hide();
			// 	} else {
			// 		$(this).hide();
			// 		$(this).parent().find('.line_blank').show();
			// 		$(this).parent().find('.btnEdit').show();
			// 		$(this).parent().find('.btnSaveAnswer').hide();
			// 	}
			// }

		// })

		$('.btnSaveAnswer').click(function () {
			$(this).hide();
			var answer = $(this).parent().find('.input_answer').val();
			$(this).parent().find('.input_answer').hide();

			$('.line_blank').show();

			if (answer !== '') {
				$(this).hide();
				$(this).parent().find('.line_blank').html(answer + ' ');
				$(this).parent().find('.line_blank').removeClass('text-danger').addClass('text-danger').attr('style', 'display: inline-block; text-align: center; letter-spacing: 2px; border-bottom:1px solid; min-width: 130px; width: auto;');
				$(this).parent().find('.line_blank').show();
				$(this).parent().find('.btnEdit').show();
				$(this).parent().find('.btnSaveAnswer').hide();
			} else {
				$(this).hide();
				$(this).parent().find('.line_blank').show();
				$(this).parent().find('.btnEdit').show();
				$(this).parent().find('.btnSaveAnswer').hide();
			}

			


		})

	})


	$(document).on('click', '.start_select_answer', function (e) {
		e.preventDefault();
		$(this).find('span').hide();
		$(this).find('select').attr('style', 'display: inline !important; height: 2rem !important;');
		$(this).find('select').show();
		$(this).find('select').focus();
		$(this).find('.btnSaveAnswer').show();
	})


	$(document).on('click', '.start_select_option_answer', function (e) {
		e.preventDefault();
		$(this).find('span').hide();
		$(this).find('select').attr('style', 'display: inline !important; height: 2rem !important;');
		$(this).find('select').show();
		$(this).find('select').focus();
		$(this).find('.btnSaveAnswer').show();
	})


$(document).ready(function() {		

	toastr.options.timeOut = "1000";
	var ajaxUrl = base_url + 'onlinequiz/getQuizQuestion/';

	let isTimerToggle = false;
	// $('body').bind('cut copy paste', function(e){
	// 	e.preventDefault();
	// });
	
	// $('body').on('contextmenu', function(e){
	// 	e.preventDefault();
	// });
	$(".timer").hide();

	if(typeof document.onselectstart != "undefined"){
		document.onselectstart = new Function ("return false");
	} else {
		document.onmousedown = new Function ("return false");
		document.onmouseup = new Function ("return false");
	}
	
	$('#startQuiz').on('click', function() {
		$('.hideWhenQuizStart').fadeOut("slow", function() {
			$(this).remove();
			
			//start quiz by displaying first question and timer
			ajaxQuestion(currentQuestion);
			
		});
		var data = questionList[currentQuestion];
		var questionId = data['questionId'];
		
		answer_id = '-';
		logs_in(answer_id, currentQuestion, questionId);

		//start to count the time
		registerStartTime();
	});

	function registerStartTime(){
		let startTime = new Date().getTime();
		let duration = quizTime*60*1000;
		// let isTimerToggle = false;
		if(duration == 0){
			return;
		}
		let endTime = startTime + duration;
		window.localStorage.setItem("startTime", startTime);
		window.localStorage.setItem("endTime", endTime);
		$(".timer").show();
		$(".timer").css('visibility', 'visible');
		$(".timer").mouseenter(function(){
			$('.timer-content').removeClass("timer-hide");
		}).mouseleave( function(){
			if(!isTimerToggle){
				$('.timer-content').addClass("timer-hide");
			}
		});
		$(".timer").click(function(){
			isTimerToggle = true;
			showTimerTemporary();
		});

		showTimerTemporary();
		startCountDown();
		updateTimer(duration);
	}
	function showTimerTemporary(){
		$('.timer-content').removeClass("timer-hide");
		window.setTimeout(function(){
			isTimerToggle = false;
			$('.timer-content').addClass("timer-hide");
		},3000);
	}
	function startCountDown(){
		window.setTimeout(function(){
			let endTime = window.localStorage.getItem('endTime');
			let remaining = endTime - new Date().getTime();
			if(hasTimeLapse){
				//user has submitted before end time
			}else if(new Date().getTime() > endTime){
				//time's up
				$('.submitQuiz').click();
				$('.submitQuiz').submit();
			}else{
				startCountDown();
				//prompt user if less than 5 minutes
				let min = Math.floor(remaining/1000/60 - Math.floor(remaining/1000/60/60)*60)% 60;
				if( min <= 10 && min % 5 == 0){
					showTimerTemporary();
				}
			}
			updateTimer(remaining);
		},1000);
	}
	function updateTimer(remainingTime){
		let time = "";
		let sec = (Math.round(remainingTime/1000) - Math.floor(remainingTime/1000/60)*60) % 60;
		let min = Math.floor(remainingTime/1000/60 - Math.floor(remainingTime/1000/60/60)*60)% 60;
		let hr = Math.floor(remainingTime/1000/60/60 - Math.floor(remainingTime/1000/60/60/24)*24);
		time = (hr < 10 ? "0"+hr: hr)+":"+
			(min < 10 ? "0"+min: min)+":"+
			(sec < 10 ? "0"+sec: sec);

		$("#remainingTime").html(time);
	}

	$(document).on('click', '#prevQuestion', function(e) {
		e.preventDefault();
		var answerId = $('.btn.btn-custom.answerQuestion.btn-selectedAnswer').attr('href');
		mathSpanId = $('.openEndedAnswer').attr('id');
		
		if(answerId){
			answer_id = $('.btn.btn-custom.answerQuestion.btn-selectedAnswer').text();
		}else{
			answer_id = 'NULL';
		}
		
		if (mathSpanId){
			spanTarget = document.getElementById(mathSpanId);
			mathSpan = MQ(spanTarget);
			if(mathSpan) {
				answer_open = mathSpan.latex();
				answer_id = answer_open;
			}
		}
		
		if (currentQuestion != 0) {
			currentQuestion--;
			ajaxQuestion(currentQuestion);
			prevQuestion = currentQuestion - 1;
			var data = questionList[prevQuestion];
			var questionId = data['questionId'];
			logs_in(answer_id, currentQuestion, questionId);
		} 
	});

	$(document).on('click', '#nextQuestion', function(e) {
		e.preventDefault();
		var answerId = $('.btn.btn-custom.answerQuestion.btn-selectedAnswer').attr('href');
		mathSpanId = $('.openEndedAnswer').attr('id');
		
		if(answerId){
			answer_id = $('.btn.btn-custom.answerQuestion.btn-selectedAnswer').text();
		}else{
			answer_id = 'NULL';
		}
		
		if (mathSpanId){
			spanTarget = document.getElementById(mathSpanId);
			mathSpan = MQ(spanTarget);
			if(mathSpan) {
				answer_open = mathSpan.latex();
				answer_id = answer_open;
			}
		}
		
		if (currentQuestion != totalQuestion - 1) {
			currentQuestion++;
			ajaxQuestion(currentQuestion);
			prevQuestion = currentQuestion - 1;
			var data = questionList[prevQuestion];
			var questionId = data['questionId'];
			logs_in(answer_id, currentQuestion, questionId);
		} 
	});

	$(document).on('click', '.selectQuestion', function(e) {
		e.preventDefault();
		
		currentQuestion = parseInt($(this).attr('href'));
		
		var questionId = $('#current_question_id').val();
		
		var answerId = $('.btn.btn-custom.answerQuestion.btn-selectedAnswer').attr('href');
		
		mathSpanId = $('.openEndedAnswer').attr('id');
		
		if(answerId){
			answer_id = $('.btn.btn-custom.answerQuestion.btn-selectedAnswer').text();
		}else{
			answer_id = 'NULL';
		}
		
		if (mathSpanId){
			spanTarget = document.getElementById(mathSpanId);
			mathSpan = MQ(spanTarget);
			if(mathSpan) {
				answer_open = mathSpan.latex();
				answer_id = answer_open;
			}
		}
		
		ajaxQuestion(currentQuestion);
		logs_in(answer_id, currentQuestion, questionId);
	});


	$(document).on('click', '.submitQuiz', function() {
		var data = questionList[currentQuestion];
		var questionId = $('#current_question_id').val();
		
		var answerId = $('.btn.btn-custom.answerQuestion.btn-selectedAnswer').attr('href');
		mathSpanId = $('.openEndedAnswer').attr('id');
		
		if(answerId){
			answer_id = $('.btn.btn-custom.answerQuestion.btn-selectedAnswer').text();
		}else{
			answer_id = 'NULL';
		}
		
		if (mathSpanId){
			spanTarget = document.getElementById(mathSpanId);
			mathSpan = MQ(spanTarget);
			if(mathSpan) {
				answer_open = mathSpan.latex();
				answer_id = answer_open;
			}
		}
		
		logs_in(answer_id, currentQuestion, questionId);

		hasTimeLapse = true;
	});


	$(document).on('click', '.answerQuestion', function(e) {
		e.preventDefault();
		var clickedAnswer = $(this).attr('href').split("_");
		var quesNum = clickedAnswer[0];
		answerId = clickedAnswer[1];

		$('#ques_' + quesNum).val(answerId);
		
		toastr.success('Answer saved');
		// $('.answerUpdateToast').fadeIn(200).delay(1000).fadeOut(200); //fade out after 3 seconds
		$('.answerQuestion').each(function() {
			$(this).removeClass("btn-selectedAnswer");
		});
		$(this).addClass("btn-selectedAnswer");
		$('#quesNo_' + currentQuestion).addClass('answeredQuestion');
		
		questionId = $('#current_question_id').val();
		answerText = $(this).text();
		var svg_val = document.getElementById('editor').innerHTML;
		if(svg_val !== null)
			$('#svg_' + currentQuestion).val(svg_val);
		logs_in(answerText, currentQuestion, questionId);

		if (currentQuestion != totalQuestion - 1) {
			currentQuestion++;
			ajaxQuestion(currentQuestion);
		}
	});

	$(document).on('click', '.answerOpenEndedQuestion', function(e) {
		e.preventDefault();
		var clickedAnswer = $(this).attr('href').split("_");
		var quesNum = clickedAnswer[0];
		mathSpanId = $(this).parent().prev().find('.openEndedAnswer').attr('id');
		spanTarget = document.getElementById(mathSpanId);
		mathSpan = MQ(spanTarget);
		answer_id = mathSpan.latex();
		questionId = $('#current_question_id').val();
		

		if(answer_id === '') {
			toastr.error('No Answer');
			logs_in(answer_id, currentQuestion, questionId);
		} else {
			$('#ques_' + quesNum).val(answer_id);
			toastr.success('Answer saved');
			$('#quesNo_' + currentQuestion).addClass('answeredQuestion');
			$(this).addClass('btn-selectedAnswer');
			
			$('#svg_' + currentQuestion).val(document.getElementById('editor').innerHTML);
			logs_in(answer_id, currentQuestion, questionId);

			if (currentQuestion != totalQuestion - 1) {
				currentQuestion++;
				ajaxQuestion(currentQuestion);
			} 
		}
	});

	$(document).on('focus', '.openEndedAnswer', function() {
		$('#mathTarget').val($(this).attr('id'));
	});
	
	function logs_in(answer_id = NULL, currentQuestion = NULL, questionId = NULL) {
		$.ajax({
			url: base_url + 'smartgen/student_log',
			method: "post",
			dataType: 'json',
			data: {
				question_id : questionId,
				quiz_id : quizId,
				answer_id : answer_id,
				tutor_id : tutorId
			},
			success: function(data) {
				
			}
		});
	}
});

//************New Added Script Start*/
function activateMyScript(currentQuestion){
	var editorElement = document.getElementById('editor');
	var resultElement = document.getElementById('myscriptResult');
	var undoElement = document.getElementById('undo');
	var redoElement = document.getElementById('redo');
	var clearElement = document.getElementById('clear');
	var convertElement = document.getElementById('convert');
	// var defPenElement = document.getElementById('defPen');
	// var medPenElement = document.getElementById('medPen');
	// var boldPenElement = document.getElementById('boldPen');
	editorElement.addEventListener('changed', function (event) {
		undoElement.disabled = !event.detail.canUndo;
		redoElement.disabled = !event.detail.canRedo;
		clearElement.disabled = event.detail.isEmpty;
	});
	// defPenElement.style.backgroundColor = '#2abb9b';
	// medPenElement.style.backgroundColor = '#ececec';
	// boldPenElement.style.backgroundColor = '#ececec';
	function cleanLatex(latexExport) {
		if (latexExport.includes('\\\\')) {
		const steps = '\\begin{align*}' + latexExport + '\\end{align*}';
		return steps.replace("\\overrightarrow", "\\vec")
			.replace("\\begin{aligned}", "")
			.replace("\\end{aligned}", "")
			.replace("\\llbracket", "\\lbracket")
			.replace("\\rrbracket", "\\rbracket")
			.replace("\\widehat", "\\hat")
			.replace(new RegExp("(align.{1})", "g"), "aligned");
		}
		return latexExport
		.replace("\\overrightarrow", "\\vec")
		.replace("\\llbracket", "\\lbracket")
		.replace("\\rrbracket", "\\rbracket")
		.replace("\\widehat", "\\hat")
		.replace(new RegExp("(align.{1})", "g"), "aligned");
	}

	editorElement.addEventListener('exported', function (evt) {
		const exports = evt.detail.exports;
		var save_to = $('#save_to').val();
		if(save_to=="svg") {
			//*****Variable to store handwriting data, To be Defined by Justin, current Variable: OCRrecorded */
			questionList[currentQuestion]['OCRrecorded'] = evt.detail.exports;
			$('#ocr_' + currentQuestion).val(JSON.stringify(evt.detail.exports)); 
			$('#ocr_digitize_' + currentQuestion).val(JSON.stringify(evt.detail.exports['application/x-latex'])); 
			$('#svg_' + currentQuestion).val(document.getElementById('editor').innerHTML);
		} else {
			questionList[currentQuestion]['OCRrecorded_question'] = evt.detail.exports;
			$('#ocr_question_' + currentQuestion).val(JSON.stringify(evt.detail.exports)); 
			$('#ocr_digitize_question_' + currentQuestion).val(JSON.stringify(evt.detail.exports['application/x-latex']));
			$('#svg_question_' + currentQuestion).val(document.getElementById('editor').innerHTML);
		}
		//******May require to store other variable as below, to be discussed */
		if (exports && exports['application/x-latex']) {
			convertElement.disabled = false;
			//katex.render(cleanLatex(exports['application/x-latex']),  resultElement);
		} else if (exports && exports['application/mathml+xml']) {
			convertElement.disabled = false;
			resultElement.innerText = exports['application/mathml+xml'];
		} else if (exports && exports['application/mathofficeXML']) {
			convertElement.disabled = false;
			resultElement.innerText = exports['application/mathofficeXML'];
		} else {
			convertElement.disabled = true;
			resultElement.innerHTML = '';
		}
	});
	editorElement.addEventListener('error', (evt) => {
        if(evt.detail && evt.detail.type !== 'close') {
          	resultElement.innerText = JSON.stringify(evt.detail);
        }
    });

	undoElement.addEventListener('click', function () {
		editorElement.editor.undo();
	});

	redoElement.addEventListener('click', function () {
		editorElement.editor.redo();
	});

	clearElement.addEventListener('click', function () {
		editorElement.editor.clear();
	});

	convertElement.addEventListener('click', function () {
		//editorElement1.innerHTML = resultElement.innerHTML;
		editorElement.editor.convert();	
	});

	var themes = [{
        name: 'Normal white theme',
        id: 'normal-white',
        theme: {
          ink: {
            color: '#000000',
            '-myscript-pen-width': 2
          },
          '.text': {
            'font-size': 12
          }
        }
    }];
	var defaultTheme = 'normal-white';
	function getTheme(themes, id) {
		return themes.filter(function (theme) {
	  		return theme.id === id;
		})[0].theme;
	}
	/**
	* Attach an editor to the document
	* @param {Element} The DOM element to attach the ink paper
	* @param {Object} The recognition parameters
	*/
	var editor = MyScript.register(editorElement, {
		recognitionParams: {
			type: 'MATH',
			protocol: 'WEBSOCKET',
			apiVersion: 'V4',
			server: {
				scheme: 'https',
				host: 'webdemoapi.myscript.com',
				applicationKey: 'b42908eb-d0e6-4d5f-ae43-592650f79ed1',
				hmacKey: '7b7a58f1-59b2-406c-8270-739cd32a9870'
			},
			v4: {
      			alwaysConnected: true,
				math: {
					mimeTypes: ['application/x-latex', 'application/vnd.myscript.jiix','image/png','image/jpeg']
				},
				export: {
					jiix: {
						strokes: true,
						style: true
					}
				}
			}
		}
	},
  	undefined, getTheme(themes, defaultTheme));
	
	function getStyle(penColor="#000000",size=2) {
        return {
			color: penColor,
			'-myscript-pen-width': size
        }
    }
    var currentColor = '#000000';
    var currentSize = 2;

	$('#colorPickSelector').colorPick({
		'onColorSelected': function() {
			this.element.css({'backgroundColor': this.color, 'color': this.color});
			editor.penStyle = getStyle(this.color, currentSize);
			currentColor = this.color;
		}
	});

	// Color PEN Size event click
	// defPenElement.addEventListener('click', function () {
	// 	var size = 2;
	// 	editor.penStyle = getStyle(currentColor,size);
	// 	defPenElement.style.backgroundColor = '#2abb9b';
	// 	medPenElement.style.backgroundColor = '#ececec';
	// 	boldPenElement.style.backgroundColor = '#ececec';
	// 	currentSize = 2;
	// });
	// medPenElement.addEventListener('click', function () {
	// 	var size = 4;
	// 	editor.penStyle = getStyle(currentColor,size);
	// 	medPenElement.style.backgroundColor = '#2abb9b';
	// 	defPenElement.style.backgroundColor = '#ececec';
	// 	boldPenElement.style.backgroundColor = '#ececec';
	// 	currentSize = 4;
	// });
	// boldPenElement.addEventListener('click', function () {
	// 	var size = 6;
	// 	editor.penStyle = getStyle(currentColor,size);
	// 	boldPenElement.style.backgroundColor = '#2abb9b';
	// 	medPenElement.style.backgroundColor = '#ececec';
	// 	defPenElement.style.backgroundColor = '#ececec';
	// 	currentSize = 6;
	// });

	window.addEventListener('resize', function () {
		editorElement.editor.resize();
	});
	
}

</script>
