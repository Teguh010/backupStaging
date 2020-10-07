<link rel="stylesheet" href="<?=base_url()?>css/mathquill.min.css" />
<script src="<?=base_url()?>js/mathquill.min.js"></script>

<!--************New Added Script By Kok Liang on 21-12-2019-->
<script src="<?php echo base_url()?>js/imgFunctions.js"></script>
<!--************New Added Script End-->

<!--************New Added Script Start-->
<link rel="stylesheet" href="<?php echo base_url()?>/node_modules/myscript/dist/myscript.min.css"/>
<link rel="stylesheet" href="<?php echo base_url()?>/css/examples.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/KaTeX/0.9.0/katex.min.css" integrity="sha384-TEMocfGvRuD1rIAacqrknm5BQZ7W7uWitoih+jMNFXQIbNl16bO8OZmylH/Vi/Ei" crossorigin="anonymous">

<script src="https://cdnjs.cloudflare.com/ajax/libs/KaTeX/0.9.0/katex.min.js" integrity="sha384-jmxIlussZWB7qCuB+PgKG1uLjjxbVVIayPJwi6cG6Zb4YKq0JIw+OMnkkEC7kYCq" crossorigin="anonymous"></script>
<script src="https://code.jquery.com/pep/0.4.3/pep.js"></script>
<script type="text/javascript" src="<?php echo base_url()?>/node_modules/myscript/dist/myscript.min.js"></script>

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
		height: 350px;
		border: 1px solid #D7DDE3;
	}
	#myscriptAdd{
		float: right;
		margin-top: 8px;
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

	
	@media only screen and (max-width: 768px) {
		#imgUploadBtn{
			margin-top: 10px;
		}
		#myscript{
			height:300px;
		}

		#myscriptExpand{
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
</style>
<!--************New Added Script End-->

<div class="section">
	<div class="container">
		
		<div class="col-sm-12 col-md-12 col-lg-12" id="quizSection">

		</div>
		<div class="controlButtonDiv col-sm-12 col-md-12 col-lg-12 margin-top-custom showWhenQuizStart text-center">
		<p class="help-block pull-left" id="help-text"><em>* Remember to click the submit answer button for non-MCQ question</em></p>
		<a href="#" id="mathExpressionBtn" class="btn btn-custom btn-no-margin-side pull-right" data-toggle="modal" data-target="#mathExpressionKeyboard">Insert Math Expression </a>
			<nav>
			  <ul class="pagination">
			    <li>
			      <a href="#" aria-label="Previous" id="prevQuestion">
			        <span aria-hidden="true">&laquo;</span>
			      </a>
			    </li>
			    <?php 
			    	for ($i = 0 ; $i < $quizNumOfQuestion; $i++ ) {
			    		echo '<li><a href="'.$i.'" id="quesNo_'.$i.'" class="selectQuestion">'.($i + 1).'</a></li>';
			    	}
			    ?>
			    <li>
			      <a href="#" aria-label="Next" id="nextQuestion">
			        <span aria-hidden="true">&raquo;</span>
			      </a>
			    </li>
			  </ul>
			</nav>
		</div>
		<div class="clearfix">
			<div class="text-center">
				<button class="btn btn-custom showWhenQuizStart" data-toggle="modal" data-target="#submitQuizModal">Submit Quiz</button>
			</div>
			<!-- <div class="answerUpdateToast">Answer updated</div> -->

			<div class="modal fade" id="submitQuizModal" role="dialog" tabindex="-1">
				<div class="modal-dialog" role="document">
					<div class="modal-content">
						<div class="modal-header modal-header-custom-success">
							<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
							<h4 class="modal-title">Submit Quiz</h4>
						</div>
						<form class="form-horizontal" action="<?php echo base_url(); ?>onlinequiz/submitMockQuiz" method="post" accept-charset="utf-8">
							<input type="hidden" name="quizNumOfQuestion" value="<?= $quizNumOfQuestion ?>">
							<input type="hidden" name="quizID" value="<?= $quizID ?>">
							<input type="hidden" name="quizAttemptDateTime" value="<?= $quizAttemptDateTime ?>">
							<?php
								for ($i = 0; $i < $quizNumOfQuestion; $i++) {
									foreach ($quizQuestion[$i] as $subquestion) {
										echo '<input type="hidden" name="ques_' . $i . '_' . $subquestion->sub_question . '" id="ques_' . $i . '_' . $subquestion->sub_question . '">';
									}
							
								for ($i = 0; $i < $quizNumOfQuestion; $i++) {
									echo '<input type="hidden" name="ocr_' . $i . '" id="ocr_' . $i . '">';
									echo '<input type="hidden" name="img_' . $i . '" id="img_' . $i . '">';
									echo '<input type="hidden" name="ocr_digitize_' . $i . '" id="ocr_digitize_' . $i . '">';
								}
							?>
							<div class="modal-body">
								You will not be able to modify your answer after this action. Confirm to submit quiz answer?
							</div>
							<div class="modal-footer">
								<input type="button" class="btn btn-default" data-dismiss="modal" value="Cancel">
								<input type="submit" class="btn btn-custom" value="Submit">
							</div>
						</form>
					</div>
				</div>
			</div>

		</div>	
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

			<div class="row">
				<?php 
					if (isset($quizError) && $quizError) {
						echo $quizErrorMessage;
					} else {
						// foreach ($quizQuestion AS $question) {
						// 	echo $question['questionText'] . '<br>';
						// 	echo '<pre>';
						// 	print_r($question['answerOptions']);
						// 	echo '</pre>';
						// }

						// echo '<div id="something"></div>';
					
				?>
					<div class="col-lg-l2 col-md-12 col-sm-12 text-center">
						<h2><?= $quizName ?></h2>
						<small>created by <?= $quizOwner?></small>
						<h3>When you are ready, press the button below to start the quiz</h3>
						<button class="btn btn-custom" id="startQuiz">Start Quiz</button>
					</div>
				<?php
					}
				?>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="mathExpressionKeyboard" role="dialog">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header modal-header-custom-success">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title">Mathematic Expression</h4>
			</div>
            <div class="modal-body">
                <p>Insert mathematical expression</p>
                <input type="hidden" id="mathTarget" value="">
                <div id="keyboard">
                    <div role="group" aria-label="math functions">
                        <button type="button" class="btn btn-default" onClick='input("\\frac")'>Fraction \(\frac{x}{y}\)</button>
                        <button type="button" class="btn btn-default" onClick='inputMultiple("^", "\\circ")'>Degree \(^\circ\)</button>
                        <button type="button" class="btn btn-default" onClick='input("\\pi")'>Pi \(\pi\)</button>
                        <button type="button" class="btn btn-default" onClick='input("\\angle")'>Angle \(\angle\)</button>
                        <button type="button" class="btn btn-default" onClick='inputMultiple("^", "2")'>Power \(x^2\)</button>
                        <button type="button" class="btn btn-default" onClick='inputMultiple("^", "3")'>Power \(x^3\)</button>
                        <button type="button" class="btn btn-default" onClick='input("ml")'>Millilitre ml</button>
                        <button type="button" class="btn btn-default" onClick='input("l")'>Litre l</button>
												<button type="button" class="btn btn-default" onClick='input("\\div")'>Divide \(\div\)</button>
                    </div>
                </div>
                <div class="form-group">
                    <span id="math-field" style="width: 100%; padding: 0.5em"></span>
                </div>
            </div>
            <div class="modal-footer">
                <input type="button" class="btn btn-default" data-dismiss="modal" value="Close">
            </div>
		</div>
	</div>
</div>


<script type="text/javascript">
	var mathKeyboard = $('#mathExpressionKeyboard');
	var MQ = MathQuill.getInterface(2);

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
	var currentQuestion = 0;
	var totalQuestion = <?= $quizNumOfQuestion?>;
	var questionList = <?= $quizQuestionText ?>;
	$(document).ready(function() {
		toastr.options.timeOut = "1000";
		var ajaxUrl = base_url + 'onlinequiz/getQuizQuestion/';
		function ajaxQuestion(currentQuestion) {
			var data = questionList[currentQuestion];
			let numOpenEnded = 0;
			$('#quizSection').html('');  // clear quiz section div
			for (let question of data) {
				let subQuestionOutput = (data.length==1)?'':' (' + question['sub_question'] + ')';
				let markText = (question['difficulty'] == 1)?'mark':'marks';
				var questionDiv = $('<div class="questionDiv></div>');
				var questionNumber = '<u><h1 class="questionNumber">Question ' + (currentQuestion + 1) + subQuestionOutput + ' (' + question['difficulty'] + ' ' + markText + ')</h1></u>';
				var questionArea = '<div class="question_text col-sm-12 col-md-12 col-lg-12">';
				if (question['graphical'] != "0") {
					questionArea += '<img src="' + question['branch_image_url'] + '/questionImage/' + question['graphical'] + '" class="img-responsive">';
				}
				questionArea += question['question_text'] + '</div>';
				if (question['question_type_id'] == 1) {  // mcq
					let answerOption = question['answerOption'];
					let answerOptionHtml = '';
					for (let i = 0, l = answerOption.length; i < l; i++) {
						if (answerOption[i].answer_id == $('#ques_' + currentQuestion + '_' + question['sub_question']).val()) {
							answerOptionHtml += '<a href="' + currentQuestion + '_' + question['sub_question'] + '_' + answerOption[i].answer_id + '" class="btn btn-custom answerQuestion btn-selectedAnswer">' + answerOption[i].answer_text + '</a>';
						} else {
							answerOptionHtml += '<a href="' + currentQuestion + '_' + question['sub_question'] + '_' + answerOption[i].answer_id + '" class="btn btn-custom answerQuestion">' + answerOption[i].answer_text + '</a>';
						}
					}
					var answerDiv = '<div class="answerOptions hideWhenNewQuesGen margin-bottom-custom"> ' + answerOptionHtml + '</div>';
				
				} else {
					numOpenEnded++;
					var answerValue = $('#ques_' + currentQuestion + '_' + question['sub_question']).val();
					//************New Added Script Start*/
					var answerOptionHtml = (
									'<div id="myscript" style="width: 100%; float:left;">'+
										'<div>'+
											'<nav>'+
											'<div class="button-div">'+
												'<button id="clear" class="nav-btn btn-fab-mini btn-lightBlue" disabled>'+
												'<img src="assets/img/clear.svg">'+
												'</button>'+
												'<button id="undo" class="nav-btn btn-fab-mini btn-lightBlue" disabled>'+
												'<img src="assets/img/undo.svg">'+
												'</button>'+
												'<button id="redo" class="nav-btn btn-fab-mini btn-lightBlue" disabled>'+
												'<img src="assets/img/redo.svg">'+
												'</button>'+
											'</div>'+
											'<div class="spacer"></div>'+
											//'<button class="classic-btn" id="convert" disabled>Convert</button>'+
											'</nav>'+
											'<div id="editor" touch-action="none" style="width: 75%;float:left;"></div>'+
											'<div id="myscriptResult" touch-action="none" style="width: 25%; float:left;"></div>'+
											'<div id="myscriptAdd" class="nav-btn btn-fab-mini btn-lightBlue">+</div>'+
										'</div>'+
									'</div>');
					//************New Added Script End*/
					if (answerValue) {
						answerOptionHtml += (
							'<div class="col-sm-6 col-md-6 col-lg-6">'+
								'<span class="openEndedAnswer" style="width: 100%; padding: 0.5em" id="span_' + currentQuestion + '">' +
									answerValue + 
								'</span>'+
							'</div>' +
							'<div class="col-sm-6 col-md-6 col-lg-6">'+
								'<a href="" id="myscriptExpand" class="btn btn-custom answerOpenEndedQuestion btn-no-margin-top btn-selectedAnswer">'+
									'Show Writing Pad'+
								'</a>'+
								'<a href="' + currentQuestion + '" class="btn btn-custom answerOpenEndedQuestion btn-no-margin-top btn-selectedAnswer">'+
									'Submit answer'+
								'</a>'+
								//upload button
								//New Added Script By Kok Liang on 21-12-2019
								'<label id="imgUploadBtn" for="imgUpload" class="btn answerOpenEndedQuestion btn-no-margin-top btn-danger">'+
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
								'<a href="" id="myscriptExpand" class="btn btn-custom answerOpenEndedQuestion btn-no-margin-top btn-selectedAnswer">'+
									'Show Writing Pad'+
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
					var answerDiv = '<div class="answerOptions row form-group"> ' + answerOptionHtml + ' </div>';
				}

				$('#quizSection').append(questionNumber + questionArea + answerDiv);	
				
				//************New Added Script Start*/
				if(questionType == 2){
					//hide myscript
					$("#myscript").hide();
					activateMyScript(currentQuestion);
					//listen to "show writing pad button"
					$("#myscriptExpand").on('click', function() {
						$("#myscript").toggle();
						
						//import answer
						//*****Variable for handwriting data to be Defined by Justin */
						//import data back to handwriting script
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
					});

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
			}

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




		$('#startQuiz').on('click', function() {
			$('.hideWhenQuizStart').fadeOut("slow", function() {
				$(this).remove();

				//start quiz by displaying first question and timer
				ajaxQuestion(currentQuestion);
			});
			
		});

		$(document).on('click', '#prevQuestion', function(e) {
			e.preventDefault();
			if (currentQuestion != 0) {
				currentQuestion--;
				ajaxQuestion(currentQuestion);
			} 
		});

		$(document).on('click', '#nextQuestion', function(e) {
			e.preventDefault();
			if (currentQuestion != totalQuestion - 1) {
				currentQuestion++;
				ajaxQuestion(currentQuestion);
			} 
		});

		$(document).on('click', '.selectQuestion', function(e) {
			e.preventDefault();
			currentQuestion = parseInt($(this).attr('href'));
			ajaxQuestion(currentQuestion);
		});


		$(document).on('click', '.answerQuestion', function(e) {
			e.preventDefault();
			var clickedAnswer = $(this).attr('href').split("_");
			var quesNum = clickedAnswer[0];
			var subQuesNum = clickedAnswer[1];
			var answerId = clickedAnswer[2];

			$('#ques_' + quesNum + '_' + subQuesNum).val(answerId);
			toastr.success('Answer saved');
			// $('.answerUpdateToast').fadeIn(200).delay(1000).fadeOut(200); //fade out after 3 seconds
			$('.answerQuestion').each(function() {
				$(this).removeClass("btn-selectedAnswer");
			});
			$(this).addClass("btn-selectedAnswer");
			$('#quesNo_' + currentQuestion).addClass('answeredQuestion');
		});

		$(document).on('click', '.answerOpenEndedQuestion', function(e) {
			e.preventDefault();
			var clickedAnswer = $(this).attr('href').split("_");
			var quesNum = clickedAnswer[0];
			var subQuesNum = clickedAnswer[1];
			let mathSpanId = $(this).parent().prev().find('.openEndedAnswer').attr('id');
			let spanTarget = document.getElementById(mathSpanId);
			let mathSpan = MQ(spanTarget);
			$('#ques_' + quesNum + '_' + subQuesNum).val(mathSpan.latex());
			toastr.success('Answer saved');
			// $('.answerUpdateToast').fadeIn(200).delay(1000).fadeOut(200); //fade out after 3 seconds
			$('#quesNo_' + currentQuestion).addClass('answeredQuestion');
			$(this).addClass('btn-selectedAnswer');
		});

		$(document).on('focus', '.openEndedAnswer', function() {
			$('#mathTarget').val($(this).attr('id'));
		});

	
	});

	//************New Added Script Start*/
	function activateMyScript(currentQuestion){
		var editorElement = document.getElementById('editor');
		var resultElement = document.getElementById('result');
		var undoElement = document.getElementById('undo');
		var redoElement = document.getElementById('redo');
		var clearElement = document.getElementById('clear');
		var convertElement = document.getElementById('convert');
		editorElement.addEventListener('changed', function (event) {
			undoElement.disabled = !event.detail.canUndo;
			redoElement.disabled = !event.detail.canRedo;
			clearElement.disabled = event.detail.isEmpty;
		});

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
			
			//*****Variable to store handwriting data, To be Defined by Justin, current Variable: OCRrecorded */
			questionList[currentQuestion]['OCRrecorded'] = evt.detail.exports;
			$('#ocr_digitize_' + currentQuestion).val(JSON.stringify(evt.detail.exports['application/x-latex'])); 
			$('#ocr_' + currentQuestion).val(JSON.stringify(evt.detail.exports)); 
			
			//******May require to store other variable as below, to be discussed */
			if (exports && exports['application/x-latex']) {
				//convertElement.disabled = false;
				//katex.render(cleanLatex(exports['application/x-latex']),  resultElement);
			} else if (exports && exports['application/mathml+xml']) {
				//convertElement.disabled = false;
				resultElement.innerText = exports['application/mathml+xml'];
			} else if (exports && exports['application/mathofficeXML']) {
				//convertElement.disabled = false;
				resultElement.innerText = exports['application/mathofficeXML'];
			} else {
				//convertElement.disabled = true;
				resultElement.innerHTML = '';
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

		//convertElement.addEventListener('click', function () {
			//editorElement1.innerHTML = resultElement.innerHTML;
			//editorElement.editor.convert();
			
		//});
		/**
		* Attach an editor to the document
		* @param {Element} The DOM element to attach the ink paper
		* @param {Object} The recognition parameters
		*/
		MyScript.register(editorElement, {
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
				math: {
				mimeTypes: ['application/x-latex', 'application/vnd.myscript.jiix']
				},
				export: {
				jiix: {
					strokes: true
				}
				}
			}
			}
		});
		
		window.addEventListener('resize', function () {
			editorElement.editor.resize();
		});
		
	}
	//************New Added Script End*/
	
</script>