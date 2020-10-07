<link rel="stylesheet" href="<?php echo base_url()?>css/mathquill.min.css" />
<script src="<?php echo base_url()?>js/mathquill.min.js"></script>


<!--************New Added Script Start-->
<link rel="stylesheet" href="<?php echo base_url()?>node_modules/myscript/dist/myscript.min.css"/>
<link rel="stylesheet" href="<?php echo base_url()?>css/examples.css">
<script type="text/javascript" src="<?php echo base_url()?>node_modules/myscript/dist/myscript.min.js"></script>
<script src="<?php echo base_url()?>js/imgFunctions.js"></script>
<!--************New Added Script End-->
<style>
	.btn-result-ocr-wrapper{
		margin-left: 0;
		padding-left: 0;
		margin-top: 20px;
		
	}
	.btn-ocr{
		margin-left:0;
		color: white;
		margin-right:10px;
	}
	.btn-img{
		margin-left:0;
		color: white;
		background-color:rgb(42, 187, 155);
		border-color: #3AB297;
	}
	.btn-img:focus {
		color: #000000;
	}
	#model-quiz-result-ocr{
		height: 270px;
	}

	.mcq_img {
		display: block;
		width: 100%;
		max-width: 100%;
	}

	.thumbnail {
		/* /* position:relative; */
		/* transition: transform .4s; Animation  */
		/* top:-25px;
		left:-35px; */
		width:500px;
		height:auto;
		display:block;
		/* z-index:999; */
	}

	.thumbnail:hover {

		/* transform: scale(1.5); */
	}

	.mcq_img_correct_answer{
		border: 5px solid #2ABB9B;
	}

	.mcq_img_wrong_answer{
		border: 5px solid red;
	}
</style>

<style>
	.alert {
		padding: 15px;
		margin-bottom: 20px;
		border: 1px solid transparent;
		border-radius: 4px;
	}

	#model-quiz-result-ocr, #model-quiz-result-ocr-uploaded, #model-quiz-result-ocr-blank, #editor {
	/*	background-image: url('<?php echo base_url() ?>img/img/bg_grey.png'); */
		background-repeat: repeat;
		height: 270px;
		width: 100%;
		float: left;
	}

	#model-quiz-result-svg-record{
		height: 270px;
		width: 100%;
		float: left;
	}

	#model-quiz-result-svg-record img{
		 min-width:auto;
		 max-width: 598px;
	}

	#myscriptResult{
        border-bottom: 1px solid grey;
		width: 25%; 
		float:left;
	}
	
	#model-quiz-result-ocr, #model-quiz-result-ocr-uploaded, #model-quiz-result-ocr-blank {
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
	
	@media only screen and (max-width: 768px) {
		#model-quiz-result-ocr, #model-quiz-result-ocr-uploaded, #model-quiz-result-ocr-blank {
			height:180px;
			width: 100%;
			float: left;
		}
		
		.button-div {
			display: flex;
			margin: 5px;
			padding-right: 0; /*15px;*/
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
		z-index: 10000000;
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

	#thicknessPick * {
		-webkit-transition: all linear .2s;
		-moz-transition: all linear .2s;
		-ms-transition: all linear .2s;
		-o-transition: all linear .2s;
		transition: all linear .2s;
	}

	#thicknessPick {
		background: rgba(255, 255, 255, 0.85);
		-webkit-backdrop-filter: blur(15px);
		position: absolute;
		border-radius: 5px;
		box-shadow: 0px 3px 8px rgba(0, 0, 0, 0.2);
		padding: 5px;
		width: 60px;
	    z-index: 10000000;
	    left: 415px;
		margin-top: 15px;
	}

	#thicknessPick button {
		border-radius: 50%;
		width: 40px;
		height: 40px;
		margin: 4px;
		cursor: pointer;
		display: inline-block;
	}

	#thicknessPick button:hover {
		transform: scale(1.1);
	}
</style>
<script src="<?php echo base_url()?>js/ColorPick.js"></script>
<div class="section">

	<div class="container">

		<div class="row">

			<?php

				if (isset($viewError) && $viewError) {

					echo '<div class="alert alert-danger">';

					echo $viewErrorMessage;

					echo '</div>';

				} else {

			?>

					<div class="panel panel-success panel-success-custom">

					  <!-- Default panel contents -->

					  	<div class="panel-heading clearfix">

					  		<h1 class="pull-left">Quiz Results</h1>

					  		<!-- <h2 class="pull-right">Score: <em><?= $numOfCorrectAnswer ?></em> / <?= $numOfQuestion ?>, Time Taken: <?= $timeTaken; ?></h2> -->

                            <h2 class="pull-right">Score: <em><span id="total_marks_id"><?= $totalMarks ?></span></em> / <?= $totalFullMarks ?>, Time Taken: <?= $timeTaken; ?></h2>

					  	</div>
					  	<div class="smartgen-pre-group">

							<ul class="list-group">

								<?php	

								// array_debug($questionList);die();

									$quesNum = 1;

									for ($i = 0; $i < count($questionList) ; $i++) {

										echo '<li class="list-group-item clearfix" id=question_'.$quesNum.'>';

										echo '<u><h3>Question ' . $questionList[$i]['showQuestionNoText'] . '</h3></u>';

										$fullMarks = $questionList[$i]['fullMarks'];

										if($fullMarks <= 1) {

	                                        echo '<h5>(' . $fullMarks . ' Mark)</h5>';

	                                    } else {

	                                        echo '<h5>(' . $fullMarks . ' Marks)</h5>';

	                                    }

	

										echo '<div class="question_text">';

										$textArray = explode(" ", $questionList[$i]['questionText']);
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

										if($questionList[$i]['question_content'] == 0){
											echo $newText;

											if ($questionList[$i]['questionImg'] != "0") {
	
												echo '<div><img src="'.$questionList[$i]['questionImageUrl'].'/questionImage/'.$questionList[$i]['questionImg'] .'" class="img-responsive"></div>';
	
											}
										} else {
											//display question text inside sj_questions
											if($questionList[$i]['question_content_type'] == 'text'){
												echo $newText;
											} else {
												echo '<div><img src="'.$questionList[$i]['questionImageUrl'].'/questionImage/'.$questionList[$i]['questionText'] .'" class="img-responsive"></div>';
											}

											// display question content
											$questionContents = $questionList[$i]['questionContents'];

											if(count($questionContents) > 0){
												foreach ($questionContents as $questionContent) {

													if ($questionContent->content_type == 'image') {
														echo '<br>';
														echo '<div><img src="'.$questionList[$i]['questionImageUrl'].'/questionImage/'.$questionContent->content_name .'" class="img-responsive"></div>';
													} else {
														echo '<br><br>';
														echo $questionContent->content_name;
													}
												}
											} 
										}
										

										echo '<div class="question_answer">';

										// $question_type = ($questionList[$i]['question_type']=='0') ? $questionList[$i]['questionType'] : $questionList[$i]['question_type'];

										if ($questionList[$i]['generated_question_type'] == 1) {  //objective

											$mcqCount = 1;

											$answerOption = $questionList[$i]['answerOption'];

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

												$class = "";

												if ($questionList[$i]['correctAnswer'] == $option->answer_id) {

													$class .= "correctAnswer ";

												} 

	

												if ($questionList[$i]['userAnswer'] == $option->answer_id) {

													$class .= "wrongAnswer ";

												}

												if($option->answer_type == 'text'){
													echo '<span class="' . $class . '">' . $mcqCount . ') ' . $newAnswerText . '</span>';
												} else {

													echo '<div class="col-md-3 col-sm-6 col-xs-12">';

													if ($questionList[$i]['correctAnswer'] == $option->answer_id) {
														echo '<span class="' . $class . '">Option ' . $mcqCount . ') <img src="'.$questionList[$i]['questionImageUrl'].'/answerImage/'.$option->answer_text .'" class="mcq_img mcq_img_correct_answer thumbnail img-responsive"></span>';
													} else if ($questionList[$i]['userAnswer'] == $option->answer_id) {
														echo '<span class="' . $class . '">Option ' . $mcqCount . ') <img src="'.$questionList[$i]['questionImageUrl'].'/answerImage/'.$option->answer_text .'" class="mcq_img mcq_img_wrong_answer thumbnail img-responsive"></span>';
													} else{
														echo '<span class="' . $class . '">Option ' . $mcqCount . ') <img src="'.$questionList[$i]['questionImageUrl'].'/answerImage/'.$option->answer_text .'" class="mcq_img thumbnail img-responsive"></span>';
													}

													echo '</div>';
													
												}
												// echo '<span class="' . $class . '">' . $mcqCount . ') ' . $option->answer_text . '</span>';

												if ($questionList[$i]['userAnswer'] == $questionList[$i]['correctAnswer'] && ($questionList[$i]['userAnswer'] == $option->answer_id && $option->answer_type == 'text')) {

													echo '<i class="fa fa-check answeredCorrectly"></i>';

												} else if ($questionList[$i]['userAnswer'] != $questionList[$i]['correctAnswer'] && ($questionList[$i]['userAnswer'] == $option->answer_id && $option->answer_type == 'text')) {

													echo '<i class="fa fa-times answeredWrongly"></i>';

												}

												if($option->answer_type == 'text'){
													echo '<br>';

												}
												
												$mcqCount++;

											}

											if ($questionList[$i]['userAnswer'] == 0) { // no answer

												echo '<span class="wrongAnswer pull-left">No answer</span><i class="fa fa-times answeredWrongly pull-left"></i>';

											}

											echo '<br>';

											// Add Writting for MCQ 
											echo '<div class="col-sm-6 col-md-6 col-lg-10 btn-result-ocr-wrapper">';
											if(!empty($questionList[$i]['ocr']) && !$isTutor){
												//display OCR Button
												echo '<a id="ocrResult_'.$i.'" class="btn btn-ocr ocrResult btn-selectedAnswer" data-toggle="modal" data-target="#displayOCR" data-id="'.$i.'">';
												echo 	'Show Writing';
												echo '</a>';
											}
											if($isTutor){

												if(empty($questionList[$i]['working'])){
													echo '<a id="ocrWrite_'.$i.'" class="btn btn-ocr ocrWrite btn-selectedAnswer" data-toggle="modal" data-target="#writeOCR" data-id="'.$attemptId.'_'.$quesNum.'_'.$i.'" onclick="getWorkingStatus('.$attemptId.', '.$quesNum.')">';
													echo 'Workings';
													echo '</a>';
												}else{
													echo '<a id="ocrWrite_'.$i.'" class="btn btn-icon btn-ocr ocrWrite btn-selectedAnswer" data-toggle="modal" data-target="#writeOCR" data-id="'.$attemptId.'_'.$quesNum.'_'.$i.'" onclick="getWorkingStatus('.$attemptId.', '.$quesNum.')"><i class="fa fa-exclamation-triangle"></i>';
													echo 'Workings';
													echo '</a>';
												}

												echo '<div class="dropdown" id="videoExplanation_'.$quesNum.'" style="display: inline">
													<button class="btn btn-icon btn-ocr btn-selectedAnswer dropdown-toggle" type="button"
														data-toggle="dropdown"><i class="fa fa-film"></i>Video Explanation</button>
													<ul class="dropdown-menu">														
														<li>
															<a style="cursor: pointer;" id="play_video_'.$quesNum.'" href="'.$questionList[$i]['videoExplanation'].'" class="play-video"><i class="fa fa-play mr-2"></i> Play</a>
														</li>
														<li class="divider"></li>
														<li>
															<a style="cursor: pointer;" class="uploadVideoExplanation" data-id="'.$attemptId.'_'.$quesNum.'"><i class="fa fa-upload mr-2"></i> Upload</a>
														</li>														
													</ul>
												</div>';
																								
											}else{
												if(empty($questionList[$i]['videoExplanation'])){
													$showVideo = "style='display: none;'";
												}else{
													$showVideo = "style='display: inline;'";
												}
												echo '<a id="video_explanation_'.$quesNum.'" href="'.$questionList[$i]['videoExplanation'].'" class="btn btn-icon btn-primary play-video" '.$showVideo.'><i class="fa fa-play"></i>';
												echo 'Play Video Explanation';
												echo '</a>';
											}																					

											if(!empty($questionList[$i]['img']) && !$isTutor){
												//display Button
												echo '<a id="imgResult_'.$i.'" class="btn btn-img imgResult btn-selectedAnswer" data-toggle="modal" data-target="#displayImage" data-id="'.$i.'">';
												echo 'Show Image';
												echo '</a>';
												//display
											}
											echo '</div>';

											echo '<button class="btn btn-danger pull-right flag_question" data-toggle="modal" data-target="#flagQuestionModal" id="qid_'.$questionList[$i]['questionId'].'"><span data-toggle="tooltip" data-placement="top" title="Error in question? Flag now!">Flag</span></button>';

										} else {   // subjective

											$class = '';

											if (!isset($questionList[$i]['userAnswer']) || empty($questionList[$i]['userAnswer'])) {

												//echo '<span class="wrongAnswer">Your answer: No Answer <i class="fa fa-times answeredWrongly"></i></span>';

	                                            echo '<span class="wrongAnswer">Your answer: No Answer </span>';

	                                            $class .= "wrongAnswer ";

											} else {

	                                            if ($questionList[$i]['userScore'] == $questionList[$i]['fullMarks']) {

	                                                $class .= "correctAnswer ";

	                                            } else {

	                                                $class .= "wrongAnswer ";

	                                            }

	                                            //echo '<span class="' . $class . '" id="answer_'.$i.'">Your answer: ' . $questionList[$i]['userAnswer'] . '</span>';
												echo '<span class="' . $class . '">Your answer: <span id="answer_'.$i.'">' . $questionList[$i]['userAnswer'] . '</span></span>';

	                                        }

											echo '<span class="' . $class . '"> (Marks given: ';



											if ($isTutor) {

												echo '<select name="quiz_results_marks_id" class="quiz_results_marks_id" id="'.$attemptId.'_'.$quesNum.'">';


												for($selecti=0; $selecti<=$questionList[$i]['fullMarks']; $selecti++) {

													if($selecti == $questionList[$i]['userScore']) {

														echo '<option value="' . ($selecti) . '" selected="selected">' . $selecti . '</option>';

													} else {

														echo '<option value="' . ($selecti) . '">' . $selecti . '</option>';

													}



													if($selecti == $questionList[$i]['fullMarks']-1) { // add one more option for fullmarks-0.5

														if(($selecti+0.5) == $questionList[$i]['userScore']) {

															echo '<option value="' . ($selecti + 0.5) . '" selected="selected">' . ($selecti+0.5) . '</option>';

														} else {

															echo '<option value="' . ($selecti + 0.5) . '">' . ($selecti+0.5) . '</option>';

														}

													}

												}

												echo '</select>  ';

											} else {

												echo $questionList[$i]['userScore'];

											}

											echo ')</span>';	
											echo '<br>';
											echo '<span class="correctAnswer">Correct answer: ' . $questionList[$i]['correctAnswerText'] . '</span>';
											echo '<br>';
											echo '<div style="padding-top:1em" class="quiz_results_remark">';
											if($isTutor){

												echo 'Remark: <input type="text" name="remark" class="remark_id" id="'.$attemptId.'_'.$quesNum.'" style="width:40%" value="'.$questionList[$i]['remark'].'">';
											} else {

												// echo 'Remark : '.$questionList[$i]['remark'];
												if($questionList[$i]['solutionAnswerType'] == 'image'){
													echo '<div><img src="'.$questionList[$i]['questionImageUrl'].'/workingImage/'.$questionList[$i]['solutionAnswerText'] .'" class="img-responsive"></div>';
													echo '<br>';
												} else {
													echo 'Workings : '.$questionList[$i]['solutionAnswerText'];
													echo '<br>';
												}

												if($questionList[$i]['question_content'] == 1){

													$workingContents = $questionList[$i]['solutionWorkingContents'];

													foreach ($workingContents as $workingContent) {

														if ($workingContent->content_type == 'image') {
															echo '<div><img src="'.$questionList[$i]['questionImageUrl'].'/workingImage/'.$workingContent->content_name .'" class="img-responsive"></div>';
															echo '<br>';

														} else {
															echo $workingContent->content_name;
															echo '<br>';
														}
													}

												}
												
											}
											echo '</div>';
											echo '<br>';

											echo '<div class="col-sm-6 col-md-6 col-lg-6 btn-result-ocr-wrapper">';
											if(!empty($questionList[$i]['ocr']) && !$isTutor){
												//display OCR Button
												echo '<a id="ocrResult_'.$i.'" class="btn btn-ocr ocrResult btn-selectedAnswer" data-toggle="modal" data-target="#displayOCR" data-id="'.$i.'">';
												echo 	'Show Writing';
												echo '</a>';
											}
											if($isTutor){

												if(empty($questionList[$i]['working'])){
													echo '<a id="ocrWrite_'.$i.'" class="btn btn-ocr ocrWrite btn-selectedAnswer" data-toggle="modal" data-target="#writeOCR" data-id="'.$attemptId.'_'.$quesNum.'_'.$i.'" onclick="getWorkingStatus('.$attemptId.', '.$quesNum.')">';
													echo 'Workings';
													echo '</a>';
												}else{
													echo '<a id="ocrWrite_'.$i.'" class="btn btn-icon btn-ocr ocrWrite btn-selectedAnswer" data-toggle="modal" data-target="#writeOCR" data-id="'.$attemptId.'_'.$quesNum.'_'.$i.'" onclick="getWorkingStatus('.$attemptId.', '.$quesNum.')"><i class="fa fa-exclamation-triangle"></i>';
													echo 'Workings';
													echo '</a>';
												}		
												
												echo '<div class="dropdown" id="videoExplanation_'.$quesNum.'" style="display: inline">
													<button class="btn btn-icon btn-ocr btn-selectedAnswer dropdown-toggle" type="button"
														data-toggle="dropdown"><i class="fa fa-film"></i>Video Explanation</button>
													<ul class="dropdown-menu">														
														<li>
															<a style="cursor: pointer;" id="play_video_'.$quesNum.'" href="'.$questionList[$i]['videoExplanation'].'" class="play-video"><i class="fa fa-play mr-2"></i> Play</a>
														</li>
														<li class="divider"></li>
														<li>
															<a style="cursor: pointer;" class="uploadVideoExplanation" data-id="'.$attemptId.'_'.$quesNum.'"><i class="fa fa-upload mr-2"></i> Upload</a>
														</li>														
													</ul>
												</div>';

											}else{

												if(empty($questionList[$i]['videoExplanation'])){
													$showVideo = "style='display: none;'";
												}else{
													$showVideo = "style='display: inline;'";
												}
												echo '<a id="video_explanation_'.$quesNum.'" href="'.$questionList[$i]['videoExplanation'].'" class="btn btn-icon btn-ocr btn-selectedAnswer play-video" '.$showVideo.'><i class="fa fa-play"></i>';
												echo 'Play Video Explanation';
												echo '</a>';

											}	
											if(!empty($questionList[$i]['img']) && !$isTutor){
												//display Button
												echo '<a id="imgResult_'.$i.'" class="btn btn-img imgResult btn-selectedAnswer" data-toggle="modal" data-target="#displayImage" data-id="'.$i.'">';
												echo 'Show Image';
												echo '</a>';
												//display
											}
											echo '</div>';

											echo '</div>';

											echo '</div>';

											echo '<button class="btn btn-danger pull-right flag_question" data-toggle="modal" data-target="#flagQuestionModal" id="qid_'.$questionList[$i]['questionId'].'"><span data-toggle="tooltip" data-placement="top" title="Error in question? Flag now!">Flag</span></button>';
											//display OCR if has
										}

										echo '</li>';

										$quesNum++;

									}
									//display SVG modal
									echo '<div class="modal fade" id="displayOCR" role="dialog" tabindex="-1">';
									echo '<div class="modal-dialog modal-width80" role="document">';
									echo '<div class="modal-content">';
									echo '<div class="modal-header modal-header-custom-success">';
									echo '<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
									echo '<h4 class="modal-title">Writing Record</h4>';
									echo '</div>';
											
									echo '<div class="modal-body">';
									echo '<div id="model-quiz-result-svg"></div>';
									echo '</div>';
									echo '</div>';
									echo '</div>';
									echo '</div>';
		
									//display ocr modal
									echo '<div class="modal fade" id="writeOCR" role="dialog" tabindex="-1">';
									echo '<div class="modal-dialog modal-width80" role="document">';
									echo '<div class="modal-content">';
									echo '<div class="modal-header modal-header-custom-success">';
									echo '<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
									echo '<h4 class="modal-title">Show Working</h4>';
									echo '</div>';
									echo '<div class="modal-body">';
									echo '<div id="new-svg-tutor">
											<div>
												<div id="modal-toolbar"></div>
												<div style="border: 1px solid #D7DDE3;width:100%;margin-bottom:-21px;">
												<nav class="nav-editor" style="width:fit-content;width:-moz-fit-content;border:0;">
													<div class="button-div">
														<button id="clear" class="nav-btn btn-fab-mini btn-lightBlue" disabled>
															<img src="' . base_url() . 'img/img/clear.svg">
														</button>
														<button id="undo" class="nav-btn btn-fab-mini btn-lightBlue" disabled>
															<img src="' . base_url() . 'img/img/undo.svg">
														</button>
														<button id="redo" class="nav-btn btn-fab-mini btn-lightBlue" disabled>
															<img src="' . base_url() . 'img/img/redo.svg">
														</button>
														<button id="convert" style="display:none;" class="nav-btn btn-fab-mini btn-lightBlue" disabled>
															<img src="' . base_url() . 'img/img/exchange-arrows.svg"></button>
														<button id="myscriptAdd" class="nav-btn btn-fab-mini btn-lightBlue">+</button>
														<button id="myscriptMinus" class="nav-btn btn-fab-mini btn-lightBlue">-</button>
													</div>
													<div class="button-div">
														<button id="colorPickSelector" class="nav-btn btn-fab-mini">
															<img src="' . base_url() . 'img/img/edit.svg">
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
										';
								//	echo '<h4 class="modal-title" style="padding-top:10px;">Working Record (Student)</h4><fieldset style="border:1px solid #ccc"><div id="model-quiz-result-svg-record"></div></fieldset>';

									echo '</div>';
									echo '</div>';
									echo '</div>';
									echo '</div>';
		
									//display image modal
									echo '<div class="modal fade" id="displayImage" role="dialog" tabindex="-1">';
									echo '<div class="modal-dialog modal-width80" role="document">';
									echo '<div class="modal-content">';
									echo '<div class="modal-header modal-header-custom-success">';
									echo '<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
									echo '<h4 class="modal-title">Uploaded Image</h4>';
									echo '</div>';
											
									echo '<div class="modal-body">';
									echo '<img id="modal-quiz-result-img" src="" width="100%"/>';
									echo '</div>';
									echo '</div>';
									echo '</div>';
									echo '</div>';

								?>
							</ul>
						</div>
					</div>

			<?php 

				}

			?>

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
	$(document).ready(function(){

		$('.play-video').magnificPopup({
			type: 'iframe',
			midClick: true
		});

		var questionData = []
		questionData = <?php echo json_encode($questionList) ?>;
		// var MQ = MathQuill.getInterface(2);

		// for(var i = 0 ; i < questionData.length; i++){
		// 	var elem = document.getElementById('answer_'+i);
			
		// 	if(elem != null){
		// 		var answer = $('#answer_'+i).html();
				
		// 		var mathField = MQ.StaticMath(elem);
		// 		mathField.latex(answer);
		// 	}
		// }
		
		$('.remark_id').on('change', function() {
			//remark value
			var remark = $(this).val();
			var attemptId = $(this).attr('id').split('_')[0];
			var questionNo = $(this).attr('id').split('_')[1];
			
			//Ajax for calling php function
			var ajax_url = base_url + 'onlinequiz/updateRemark';
			$.ajax({
				url: ajax_url,
				method: "post",
				dataType: 'json',
				data: {
					"remark": remark,
					"attemptId": attemptId,
					"questionNo": questionNo,
				},
				success: function (data) {
					toastr.success("Remark is successfully updated");
				},
			});
		});

		$('.imgResult').on('click', function(){	
			let id = $(this).attr('data-id');
			$('#modal-quiz-result-img').attr('src','<?=base_url()?>img/studentUpload/'+questionData[id]["img"]);

		});
		$('.ocrResult').on('click', function(){
			// let id = $(this).attr('data-id');
			// let data = JSON.parse(questionData[id]["ocr"]);
			// var editorElement = document.getElementById('model-quiz-result-ocr');
			// editorElement.editor.import_(data["application/vnd.myscript.jiix"],"application/vnd.myscript.jiix");
			
			// resizeEditor();
			// editorElement.addEventListener('loaded', function(){
			// 	resizeEditor();
			// });
			let id = $(this).attr('data-id');
			let data = questionData[id]["svg"];
			var editorElement = document.getElementById('model-quiz-result-svg');
			var svg_file = data;
			svg_file = svg_file.replace('<div class="loader" style="display: none;"></div><div class="error-msg" style="display: none;"></div><svg data-layer="BACKGROUND" x="0px" y="0px" width="1138px" height="270px" viewBox="0 0 1138, 270">','');
			svg_file = svg_file.replace('</svg>','');
			svg_file = svg_file + '</svg>';
			svg_file = svg_file.replace('1138','598');
			editorElement.innerHTML = svg_file;
		});

		$('.ocrWrite').on('click', function(){
			var attemptId = $(this).attr('data-id').split('_')[0];
			var questionNo = $(this).attr('data-id').split('_')[1];
			var id = $(this).attr('id').split('_')[1];
		//	let data = questionData[id]["ocr"]; //JSON.parse(questionData[id]["ocr"]);
			var editorElement = document.getElementById('model-quiz-result-ocr');
			var newsvgElement = document.getElementById('new-svg-tutor');
			var svgtutorElement = document.getElementById('model-quiz-result-svg-tutor');
		//	editorElement.editor.import_(data["application/vnd.myscript.jiix"],"application/vnd.myscript.jiix");
			var bg_digital = '<?php echo base_url() ?>onlinequiz/showSVG/' + attemptId + '/' + questionNo;
			$('#save_to').val('uploaded');
		//	if(questionData[id]["svg_tutor"]=="") {
				document.getElementById('title-svg-tutor').style.display = 'none';
				newsvgElement.style.display = 'block';
				svgtutorElement.style.display = 'none';
				var bg_default = 'digital';
				if(bg_default == 'digital') {
					document.getElementById('model-quiz-result-ocr').style.backgroundImage = "url(' "+ bg_digital + " ')";
					document.getElementById('model-quiz-result-ocr').style.backgroundRepeat = "no-repeat";
					document.getElementById('model-quiz-result-ocr').style.backgroundSize = "contain";
				}
				var OCRrecorded = questionData[id]["ocr_svg_tutor"];
				editorElement.editor.clear();
				editorElement.editor.import_(OCRrecorded,"application/vnd.myscript.jiix");
				resizeEditor();
				editorElement.addEventListener('loaded', function(){
					resizeEditor();
				});

				var toolbarElement = document.getElementById('modal-toolbar');
				var toolbar = '<a id="showDigital" class="btn btn-img imgResult btn-selectedAnswer" data-id="' + attemptId + '_' + questionNo+'">Digital</a> ';
				toolbar += '<a id="imgUpload" class="btn btn-img imgResult btn-selectedAnswer" data-id="' + attemptId + '_' + questionNo+'">Uploaded</a> ';
				// toolbar += '<label id="imgUploadBtn" for="imgUpload" class="btn btn-img imgResult btn-selectedAnswer">'+
				// 		(questionData[id]["img_tutor_bg"]!="" ? 'Use Image Uploaded':'Upload Image')+
				// 	'</label>'+
				// 	'<input type="file" id="imgUpload" name="imgUpload" data-id="' + attemptId + '_' + questionNo+'" accept="image/*" style="display:none" />';
				toolbar += '<a id="showBlank" class="btn btn-img imgResult btn-selectedAnswer" data-id="' + attemptId + '_' + questionNo+'">Blank</a> ';
				toolbar += '<a id="showQuestionImage" class="btn btn-img imgResult btn-selectedAnswer" data-id="' + attemptId + '_' + questionNo+'">Question Image</a> ';
				toolbarElement.innerHTML = toolbar;
				var actionElement = document.getElementById('action-toolbar');
				var toolbar = '<a id="saveWrite" class="btn btn-ocr btn-selectedAnswer" data-id="' + attemptId + '_' + questionNo+'_'+id+'">Save</a> ';

				toolbar += '<input type="hidden" id="save_to" value="" /> <input type="hidden" id="svg_tutor_img" value="" /> <input type="hidden" id="svg_tutor" /> <input type="hidden" id="svg_tutor_bg" />'; 
				toolbar += '<input type="hidden" id="ocr_result" data-id="' + attemptId + '_' + questionNo+'_'+id+'" /> <input type="hidden" id="ocr_svg_tutor" value=\''+questionData[id]["ocr_svg_tutor"]+'\' /> <input type="hidden" id="ocr_svg_tutor_img" value=\''+questionData[id]["ocr_svg_tutor_img"]+'\' />  <input type="hidden" id="ocr_svg_tutor_bg" value=\''+questionData[id]["ocr_svg_tutor_bg"]+'\' /> ';
			//	toolbar += '<input type="hidden" id="img_'+id+'" value="" /> <input type="hidden" id="svg_tutor" /> <input type="hidden" id="svg_tutor_bg" value="'+ bg_digital +'" />';
				actionElement.innerHTML = toolbar;
				$('.loader').css('display','none');
			//  } else {
			// 	document.getElementById('title-svg-tutor').style.display = 'block';
			// 	document.getElementById('title-svg-tutor').innerHTML = "<h4>Working Record (Tutor)<h4>";
			// 	$( "#model-quiz-result-svg-tutor" ).load('<?php echo base_url() ?>onlinequiz/showTutorSVG/'+ attemptId +'/'+ questionNo, function( response, status, xhr ) {
			// 	  if ( status == "error" ) {
			// 	    var msg = "Sorry but there was an error: ";
			// 	    $( "#error" ).html( msg + xhr.status + " " + xhr.statusText );
			// 	  }
			// 	});
			// 	newsvgElement.style.display = 'none';
			// 	svgtutorElement.style.display = 'block';
			// 	var actionElement = document.getElementById('action-toolbar');
			// 	var toolbar = '<a id="updateWrite" class="btn btn-ocr btn-selectedAnswer" data-id="' + attemptId + '_' + questionNo+'_'+id+'">Change</a> ';
			// 	actionElement.innerHTML = toolbar;
			// }
			// var svgElement = document.getElementById('model-quiz-result-svg-record');
			// svgElement.innerHTML = "<img src='"+ bg_digital + "'  />";
		});

		$("#writeOCR").on('click', '#imgUpload', function () {
			var editorElement = document.getElementById('model-quiz-result-ocr');
			var attemptId = $(this).attr('data-id').split('_')[0];
			var questionNo = $(this).attr('data-id').split('_')[1];
			var bg_uploaded = '<?php echo base_url() ?>onlinequiz/showUploaded/' + attemptId + '/' + questionNo;
			editorElement.style.backgroundImage = "url(' "+ bg_uploaded + " ')";
			editorElement.style.backgroundRepeat = "no-repeat";
			editorElement.style.backgroundSize = "450px 300px";
			editorElement.style.backgroundPosition = "center";
			var OCRrecorded = document.getElementById('ocr_svg_tutor_img').value;
			editorElement.editor.clear();
			editorElement.editor.import_(OCRrecorded,"application/vnd.myscript.jiix");
			// $('#svg_tutor_bg').val(bg_uploaded);
			$('#save_to').val('svg_tutor_img');
			$('.loader').css('display','none');
			$('#imgUpload').addClass('btn-active');
			$('#showBlank').removeClass('btn-active');
			$('#showDigital').removeClass('btn-active');
		});
			
		$("#writeOCR").on('click', '#showBlank', function () {
			var editorElement = document.getElementById('model-quiz-result-ocr');
			editorElement.style.backgroundImage = "url('<?php echo base_url() ?>img/img/bg_grey.png')";
			editorElement.style.backgroundRepeat = "repeat";
			editorElement.style.backgroundSize = "unset";
			var OCRrecorded = document.getElementById('ocr_svg_tutor_bg').value;
			editorElement.editor.clear();
			editorElement.editor.import_(OCRrecorded,"application/vnd.myscript.jiix");
			// $('#svg_tutor_bg').val('<?php echo base_url() ?>img/img/bg_grey.png');
			$('#save_to').val('svg_tutor_bg');
			$('.loader').css('display','none');
			$('#imgUpload').removeClass('btn-active');
			$('#showBlank').addClass('btn-active');
			$('#showDigital').removeClass('btn-active');
		});

		$("#writeOCR").on('click', '#showDigital', function () {
			var editorElement = document.getElementById('model-quiz-result-ocr');
			var attemptId = $(this).attr('data-id').split('_')[0];
			var questionNo = $(this).attr('data-id').split('_')[1];
			var bg_digital = '<?php echo base_url() ?>onlinequiz/showSVG/' + attemptId + '/' + questionNo;
			editorElement.style.backgroundImage = "url(' "+ bg_digital + " ')";
			editorElement.style.backgroundRepeat = "no-repeat";
			editorElement.style.backgroundSize = "contain";
			var OCRrecorded = document.getElementById('ocr_svg_tutor').value;
			editorElement.editor.clear();
			editorElement.editor.import_(OCRrecorded,"application/vnd.myscript.jiix");
			// $('#svg_tutor_bg').val(bg_digital);
			$('#save_to').val('svg_tutor');
			$('.loader').css('display','none');
			$('#imgUpload').removeClass('btn-active');
			$('#showBlank').removeClass('btn-active');
			$('#showDigital').addClass('btn-active');
		});

		$("#writeOCR").on('click', '#showQuestionImage', function () {
			var editorElement = document.getElementById('model-quiz-result-ocr');
			var attemptId = $(this).attr('data-id').split('_')[0];
			var questionNo = $(this).attr('data-id').split('_')[1];
			var bg_digital = '<?php echo base_url() ?>onlinequiz/showSVGQuestion/' + attemptId + '/' + questionNo;;
			editorElement.style.backgroundImage = "url(' "+ bg_digital + " ')";
			editorElement.style.backgroundRepeat = "no-repeat";
			editorElement.style.backgroundSize = "contain";
			// var OCRrecorded = document.getElementById('ocr_svg_tutor').value;
			// editorElement.editor.clear();
			// editorElement.editor.import_(OCRrecorded,"application/vnd.myscript.jiix");
			// $('#svg_tutor_bg').val(bg_digital);
			// $('#save_to').val('svg_tutor');
			$('.loader').css('display','none');
		});

		$(document).on('click', '.uploadVideoExplanation', function () {			
			var attemptId = $(this).attr('data-id').split('_')[0];
			var questionNo = $(this).attr('data-id').split('_')[1];					

			var entryContent = `<div class="form-inline" id="entryEmbedVideo_`+questionNo+`" style="display: inline;">                                    
									<div class="form-group mb-2" style="width: 100%">
										<input type="text" class="form-control input_style1_black subsection" id="embed_video_`+questionNo+`"
											placeholder="Copy link youtube or vimeo here..." style="width: 50%">
										<a style="cursor: pointer; text-decoration: none;"
											class="text-success-active fs26 ml-2 mt-1 saveEmbedVideo_`+questionNo+`"><i
												class="fa fa-check-circle-o"></i></a>
										<a style="cursor: pointer; text-decoration: none;"
											class="text-danger-active fs26 ml-2 mt-1 cancelEmbedVideo_`+questionNo+`"><i
												class="fa fa-times-circle-o"></i></a>
										<span class="text-danger fs20 ml-3 msg-embed"></span>
									</div>  
								</div>
            `;

			var selector = $('#videoExplanation_' + questionNo);

			$(entryContent).insertAfter(selector);

			$('.saveEmbedVideo_'+questionNo).click(function(){
				if ($('#embed_video_'+questionNo).val() == '') {					
					swal("Please, fill the field!", {icon: "warning",});
				} else {
					var embed_video = $('#embed_video_'+questionNo).val();
					$.ajax({
						type: 'POST',
						url: base_url + 'onlinequiz/saveVideoExplanation',
						data: {
							attemptId: attemptId,
							questionNo: questionNo,
							uploaded_video: embed_video							
						},
						dataType: 'json',
						success: function (res) {
							if (res.msg == 'success') {	
								console.log(questionNo);							
								swal("Link has been saved!", {icon: "success",});
								$('#play_video_' + questionNo).attr('href', embed_video);								

								$('.play-video').magnificPopup({
									type: 'iframe',
									midClick: true
								});

								$('#entryEmbedVideo_' + questionNo).remove();
							}
						}
					});
				}
			})


			$('.cancelEmbedVideo_'+questionNo).click(function(){
				$('#entryEmbedVideo_' + questionNo).remove();
			})

		});

		$("#writeOCR").on('click', '#saveWrite', function () {
			//remark value
			var editorElement = document.getElementById('model-quiz-result-ocr');
			var save_to = $('#save_to').val();
			save_to = (save_to=="") ? "svg_tutor" : save_to;
			editorElement.editor.export_();
			$('#'+save_to).val(editorElement.innerHTML);
			var attemptId = $(this).attr('data-id').split('_')[0];
			var questionNo = $(this).attr('data-id').split('_')[1];
			var id = $(this).attr('data-id').split('_')[2];
			var svg_tutor = $('#'+save_to).val();
			// var svg_tutor_bg = $('#svg_tutor_bg').val();
			// toastr.success("Save id:" + $('#svg_tutor').val());
			var ocr_result = document.getElementById('ocr_result');
			var ocr_target = document.getElementById('ocr_' + save_to);
			ocr_target.value = ocr_result.value;
			questionData[id]["ocr_"+save_to] = ocr_result.value;
			// Ajax for calling php function
			var ajax_url = base_url + 'onlinequiz/saveWork';
			$.ajax({
				url: ajax_url,
				method: "post",
				dataType: 'json',
				data: {
					"ocr_tutor": ocr_result.value,
					"svg_tutor": svg_tutor,
					"save_to": save_to,
					"attemptId": attemptId,
					"questionNo": questionNo,
				},
				success: function (data) {
					toastr.success("Working is successfully saved");
				},
			});
			// questionData[id]["svg_tutor"] = svg_tutor;
			// questionData[id]["svg_tutor_bg"] = svg_tutor_bg;
			// document.getElementById('model-quiz-result-svg-tutor').innerHTML = '';
			// $( "#model-quiz-result-svg-tutor" ).load('<?php echo base_url() ?>onlinequiz/showTutorSVG/'+ attemptId +'/'+ questionNo, function( response, status, xhr ) {
			// 	  if ( status == "error" ) {
			// 	    var msg = "Sorry but there was an error: ";
			// 	    $( "#error" ).html( msg + xhr.status + " " + xhr.statusText );
			// 	  }
			// });
			// $('#writeOCR').modal('hide');
		});

		function resizeEditor(){
			var editorElement = document.getElementById('model-quiz-result-ocr');
			window.setTimeout(function(){
				editorElement.editor.resize();
			},500);
		}

		$('#myscriptAdd').on('click', function(){
			var editorHeight = $('#model-quiz-result-ocr').height();
			editorHeight+= 300;
			$('#model-quiz-result-ocr').height(editorHeight);
			resizeEditor();
		});
		
		$('#myscriptMinus').on('click', function(){
			var editorHeight = $('#model-quiz-result-ocr').height();
			editorHeight -= 300;
			$('#model-quiz-result-ocr').height(editorHeight);
			resizeEditor();
		});

		var editorElement = document.getElementById('model-quiz-result-ocr');
		var undoElement = document.getElementById('undo');
		var redoElement = document.getElementById('redo');
		var clearElement = document.getElementById('clear');
		var convertElement = document.getElementById('convert');
		var defPenElement = document.getElementById('defPen');
		var medPenElement = document.getElementById('medPen');
		var boldPenElement = document.getElementById('boldPen');
		editorElement.addEventListener('changed', function (event) {
			undoElement.disabled = !event.detail.canUndo;
			redoElement.disabled = !event.detail.canRedo;
			clearElement.disabled = event.detail.isEmpty;
			convertElement.disabled = !event.detail.canConvert;
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
		//************New Added Script Start*/
		// function activateMyScript(currentQuestion){
			var editorElement = document.getElementById('model-quiz-result-ocr');
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
							mimeTypes: ['application/x-latex', 'application/vnd.myscript.jiix']
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

			$('#penPickSelector').on('click', function(){
				var penSelector = document.getElementById('thicknessPick');
				penSelector.style.display = "block";
				$("#colorPick").remove();
			});

			// Color PEN Size event click
			defPenElement.addEventListener('click', function () {
				var size = 2;
				editor.penStyle = getStyle(currentColor,size);
				currentSize = size;
				var penSelector = document.getElementById('thicknessPick');
				penSelector.style.display = "none";
				document.getElementById("penPickSelector").className = "nav-btn btn-fab-mini btn-lightBlue pen-default";
			});
			medPenElement.addEventListener('click', function () {
				var size = 4;
				editor.penStyle = getStyle(currentColor,size);
				currentSize = size;
				var penSelector = document.getElementById('thicknessPick');
				penSelector.style.display = "none";
				document.getElementById("penPickSelector").className = "nav-btn btn-fab-mini btn-lightBlue pen-medium";
			});
			boldPenElement.addEventListener('click', function () {
				var size = 6;
				editor.penStyle = getStyle(currentColor,size);
				currentSize = size;
				var penSelector = document.getElementById('thicknessPick');
				penSelector.style.display = "none";
				document.getElementById("penPickSelector").className = "nav-btn btn-fab-mini btn-lightBlue pen-bold";
			});
			let toImport = null;
			editorElement.addEventListener('exported', function (evt) {
				// const exports = evt.detail.exports;
				// if (evt.detail) {
				// 	$('#ocr_result').val(JSON.stringify(evt.detail.exports)); 
				// }
				const exports = event.detail.exports;
			    if(exports && exports['application/vnd.myscript.jiix']) {
					toImport = exports['application/vnd.myscript.jiix'];
					$('#ocr_result').val(toImport);
			    }
			});

			window.addEventListener('resize', function () {
				editorElement.editor.resize();
			});
			
		// }
	// 	activateMyScript();
	});
	$('body').click(function() {
		var penSelector = document.getElementById('thicknessPick');
		penSelector.style.display = "none";
	});

	$('#colorPickSelector').click(function() {
		var penSelector = document.getElementById('thicknessPick');
		penSelector.style.display = "none";
	});

	$('#penPickSelector').click(function(event){
	    event.stopPropagation();
	});

</script>