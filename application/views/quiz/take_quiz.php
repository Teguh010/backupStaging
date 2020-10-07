<?php $this->load->view('include/site_header'); ?>
<link rel="stylesheet" href="<?php echo base_url()?>css/mathquill.min.css" />

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
<script src="<?php echo base_url()?>js/ColorPick.js"></script>
<!--************New Added Script End-->

<script src="<?php echo base_url(); ?>js/plugins/ckeditor-4.14-full/ckeditor.js"></script>
<script src="<?=base_url()?>js/pages/online-quiz/take-quiz.js"></script>

<script type="text/javascript">
	var quizName = "<?= $quizName ?>";
	var quizId = <?= $quizID ?>;
	var totalQuestion = <?= $quizNumOfQuestion ?>;
	var numTotalQuestion = <?= $totalQuestion ?>;
	var questionList = <?= $quizQuestionText ?>;
	var questionType = "<?= $question_type ?>";	
	var quizTime = <?= $quizTime ?>;
	var tutorId = <?= $quizOwnerId ?>;
</script>
<style type="text/css">
	#toast-container>.toast-success {
		background-color: #FFFFFF !important;
		border: 1px solid #23527C !important;
		color: #23527C !important;
	}
	#toast-container>.fa {
		color: #23527C !important;
	}
</style>
<div class="top-fixed bg-smartjen">
	<div style="width: 100%">
		<div class="pull-left ml-4">
			<h3 class="title-header quiz_title text-white"><?= $subjectName ?></h3>
		</div>
		<div class="float-right mr-3 quiz_icon_close">
			<a href="<?= site_url('profile') ?>" class="cursor_pointer text-white"><i
					class="picons-thin-icon-thin-0153_delete_exit_remove_close"></i></a>
		</div>
	</div>
</div>


<div class="section pb-10" style="margin-top: -30px;">
	<div class="container">

	<div class="panel_quiz_section pt-40">
		<div id="quizSection" class="row">
		</div>		
		<div class="row showWhenQuizStart">			
			<div class="col-lg-12 inputDrawing">

			</div>
		</div>
		<a class="fs-4x showWhenQuizStart cursor_pointer arrow_next" id="nextQuestion">
			<i class="fa fa-angle-double-right"></i>
		</a>
		<a class="fs-4x showWhenQuizStart cursor_pointer arrow_prev" id="prevQuestion">
			<i class="fa fa-angle-double-left"></i>
		</a>
	</div>
		
		
		<div class="clearfix">
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


		<div class="hideWhenQuizStart">
			<div class="row">				
				<div class="col-sm-12 col-md-12 col-lg-12">
					<img src="<?php echo base_url(); ?>img/img6.png"
						class="center-block img-responsive margin-top-custom" style="width: 20%">
				</div>
				<!-- <div class="col-sm-6 col-md-6 col-lg-6">
					<h1>Take Quiz</h1>
					<p style="text-align: justify">Not happy with your current results? Failed once in your test? Fret not, try again and again
						until
						you succeed. With customized questions framed in different difficulty levels, every student can
						repeatedly attempt their areas of weaknesses by just a few clicks. Endless questions will be
						generated to suit each one’s needs. With SmartJen easy-to-use online quiz, we make sure your
						child
						learn from their mistakes by keeping track of their progresses. Every single step taken is
						recorded
						and self-generated by our automated system until they reach perfect score. Nothing more than
						practices make perfect scorers.</p>
				</div> -->
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
					<br><br><br>
					<h3>When you are ready, press the button below to start the quiz</h3>
					<br><br><br>
					<button class="btn btn-lg btn-rounded bg-custom input_width_oq_200" id="startQuiz">Start Quiz</button>
				</div>
				<?php
					}
				?>
			</div>
		</div>

		<div class="panel_answer_button p-2 text-center d_none showWhenQuizStart">
			
		</div>
		
		<div class="box_question_number p-2 text-center showWhenQuizStart">
			<!-- <h5>Question</h5> -->
			<span class="fs20 question-number"></span>
		</div>

		<div class="box_question_bookmark showWhenQuizStart">
			<a class="btn-arrow btnBookmark fs14 cursor_pointer"><i class="fa fa-bookmark mr-2"></i>Bookmark</a>
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

<div class="bg-dark-light bottom-fixed d_none">
	<div class="container">
		<div class="row">
			<div class="col-lg-5 quiz_pagination">
				<ul class="pagination v_center1">					
					<?php 
						//for ($i = 0 ; $i < $quizNumOfQuestion; $i++ ) {
							//echo '<li><a href="'.$i.'" id="quesNo_'.$i.'" class="selectQuestion">'.($quizQuestion[$i]['showQuestionNoText']).'</a></li>';
						//}
						for ($i = 0 ; $i < $totalQuestion; $i++ ) {
							echo '<li><a href="'.$i.'" id="quesNo_'.$i.'" class="selectQuestion">'.($i+1).'</a></li>';
						}
					?>
				</ul>
			</div>
			<div class="col-lg-2 quiz_pagination text-center">	
				<button class="btn btn-icon btn-lg btn-outline-success v_center2 btnSaveContinue">
					<i class="batch-icon-stiffy"></i>Save & Continue</button>				
			</div>
			<div class="col-lg-5 quiz_pagination text-right">
				<button type="button" class="btn btn-lg btn-custom btn-w-160 cursor_pointer v_center2 btnSubmitQuiz" data-toggle="modal" data-target="#submitQuizModal">Submit Quiz</button>
			</div>
		</div>
	</div>
</div>

<?php $this->load->view('include/site_footer'); ?>