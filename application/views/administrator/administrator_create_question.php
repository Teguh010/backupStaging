<link rel="stylesheet" href="<?=base_url()?>css/mathquill.min.css" />
<link rel="stylesheet" href="<?=base_url()?>css/matheditor.css" />

<script src="<?=base_url()?>js/mathquill.min.js"></script>
<script src="<?=base_url()?>js/matheditor.js"></script>

<div class="section">
	<div class="container">
	<!-- <?php
		if ($this->session->userdata('is_admin_logged_in') == 1) {
			echo '<a href="' . base_url() . 'administrator" class="btn btn-warning pull-right">Back to admin home page</a>';
		} elseif ($this->session->userdata('is_logged_in') == 1) {
			echo '<a href="' . base_url() . 'profile" class="btn btn-warning pull-right">Back to profile page</a>';
		}
	?> -->
		<div class="row">

		<?php
			if (isset($message) && isset($message_status)) {
				echo '<div class="col-sm-12 col-md-12 col-lg-12 text-center">';
				echo '<div class="alert ' . $message_status . '">' . $message . '</div>';
				echo '</div>';
			}
		?>

			<div class="col-sm-12 col-md-12 col-lg-12">

				<form class="form-horizontal" action="<?php echo base_url() ?>administrator/create_new_question" method="post" enctype='multipart/form-data' id="addNewQuestionForm">
					<h2 class="text-center"><u>Create new question</u></h2>
					<input type="hidden" id="input_question_content" name="input_question_content" />
					<input type="hidden" id="input_question_content0" name="input_question_content0" />
					<input type="hidden" id="input_question_content1" name="input_question_content1" />
					<input type="hidden" id="input_question_content2" name="input_question_content2" />
					<input type="hidden" id="input_question_content3" name="input_question_content3" />
					<input type="hidden" id="input_question_content4" name="input_question_content4" />
					<input type="hidden" id="input_question_content5" name="input_question_content5" />
					<input type="hidden" id="input_question_content6" name="input_question_content6" />
					<input type="hidden" id="input_question_content7" name="input_question_content7" />
					<input type="hidden" id="input_question_content8" name="input_question_content8" />
					<input type="hidden" id="input_question_content9" name="input_question_content9" />
					<input type="hidden" id="input_question_content10" name="input_question_content10" />

					<input type="hidden" id="input_working_content" name="input_working_content" />
					<input type="hidden" id="input_working_content0" name="input_working_content0" />
					<input type="hidden" id="input_working_content1" name="input_working_content1" />
					<input type="hidden" id="input_working_content2" name="input_working_content2" />
					<input type="hidden" id="input_working_content3" name="input_working_content3" />
					<input type="hidden" id="input_working_content4" name="input_working_content4" />
					<input type="hidden" id="input_working_content5" name="input_working_content5" />
					<input type="hidden" id="input_working_content6" name="input_working_content6" />
					<input type="hidden" id="input_working_content7" name="input_working_content7" />
					<input type="hidden" id="input_working_content8" name="input_working_content8" />
					<input type="hidden" id="input_working_content9" name="input_working_content9" />
					<input type="hidden" id="input_working_content10" name="input_working_content10" />

					<div id="addNewQuestionDiv">

						<div class="form-group">
							<label for="question_text" class="col-sm-4 col-md-4 col-lg-4 control-label">Question Content</label>

							<div class="col-sm-6 col-md-6 col-lg-6">
								<div class="panel panel-default">
									<div class="panel-body">
										<div class="row question_content"></div>
										
										<div class="dropdown" id="addQuestionContenButton">
											<button class="btn btn-icon-o radius100 text-muted btn-outline-default dropdown-toggle" type="button" data-toggle="dropdown">
											<span class="fa fa-plus"></span></button>
												<ul class="dropdown-menu">
													<li class="dropdown-header">Please choose text or image</li>
													<li>
														<a style="cursor: pointer;" onClick="addQuestionContent('text')"><i class="fa fa-text-height mr-2"></i> Text</a>
													</li>
													<li>
														<a style="cursor: pointer;" onClick="addQuestionContent('image')"><i class="fa fa-picture-o mr-2"></i> Image</a>
													</li>
												</ul>
										</div>
									</div>
								</div>
							</div>

							<div class="col-sm-6 col-md-6 col-lg-6">

								<!-- <span id="question_text" style="min-height: 120px; width: 100%; padding: 0.5em;" class="math_text1">
									<textarea style="box-shadow:none!important"></textarea>
								</span> -->
								<!-- <input name="question_text" id="question_text_hidden" type="hidden"> -->							

							</div>
								<!-- <div class="col-sm-5 col-md-5 col-lg-5">
									<textarea cols="30" rows="10" class="form-control question_text" name="question_text" id="question_text"></textarea>
									<span id="question_text" style="width: 100%; padding: 0.5em" class="math_text"></span>
									<input name="question_text" id="question_text_hidden" type="hidden">
									<a href="#" class="btn btn-custom btn-no-margin-side" data-toggle="modal" data-target="#mathExpressionKeyboard">Insert Math Expression </a>
								</div> -->
						</div>

						<div class="form-group">

							<label for="subject_id" class="col-sm-4 col-md-4 col-lg-4 control-label">Subject</label>

								<div class="col-sm-6 col-md-6 col-lg-6">

								<select name="subject_id" id="subject_id" class="form-control" style="width: 100%;">

									<?php
										echo '<option value="'. '0' .'" selected="selected">' .'-'. '</option>';

										foreach ($subjects as $subject) {
											echo '<option value="'.$subject->id.'">'.$subject->name.'</option>';
										}

									?>

								</select>

							</div>

						</div>

						<div class="form-group">
							<label for="level_id" class="col-sm-4 col-md-4 col-lg-4 control-label">School Level</label>
								<div class="col-sm-6 col-md-6 col-lg-6">
									<select name="level_id" id="level_id" class="form-control" style="width: 100%;">
									
									<?php
										echo '<option value="'. '0' .'" selected="selected">' .'-'. '</option>';
										if (isset($selected_level) && empty($selected_level) === false) {
											foreach ($levels as $level) {
												if ($selected_level == $level->level_id) {
													echo '<option value="'.$level->level_id.'" selected="selected">'.$level->level_name.'</option>';
												} else {
													echo '<option value="'.$level->level_id.'">'.$level->level_name.'</option>';
												}
											}
										} else {
											foreach ($levels as $level) {
												echo '<option value="'.$level->level_id.'">'.$level->level_name.'</option>';
											}
										}
									?>
									
									</select>
								</div>
						</div>

						<div class="form-group">
							<label for="school_id" class="col-sm-4 col-md-4 col-lg-4 control-label">School</label>
								<div class="col-sm-6 col-md-6 col-lg-6">
									<select name="school_id" id="school_id" class="form-control" style="width: 100%;">
										<option value="0">Not applicable</option>
										
										<?php
											if (isset($selected_school) && empty($selected_school) === false) {
												foreach ($schools as $school) {
													if ($selected_school == $school->school_id) {
														echo '<option value="'.$school->school_id.'" selected="selected">'.$school->school_name.'</option>';
													} else {
														echo '<option value="'.$school->school_id.'">'.$school->school_name.'</option>';
													}
												}
											} else {
												foreach ($schools as $school) {
													echo '<option value="'.$school->school_id.'">'.$school->school_name.'</option>';
												}
											}
										?>
										
									</select>
								</div>
						</div>

						<div class="form-group">

							<label for="topic_id" class="col-sm-4 col-md-4 col-lg-4 control-label">Topic 1</label>

							<div class="col-sm-6 col-md-6 col-lg-6">

								<select name="topic_id" id="topic_id" class="form-control" style="width: 100%;">

									<?php
										echo '<option value="0" selected="selected"> - </option>';

										foreach ($categories as $category) {
											echo '<option value="' . $category->id . '">' . $category->name . '</option>';
										}
										

									?>

								</select>

							</div>

						</div>

						<div class="form-group">

							<label for="topic_id2" class="col-sm-4 col-md-4 col-lg-4 control-label">Topic 2</label>

							<div class="col-sm-6 col-md-6 col-lg-6">

								<select name="topic_id2" id="topic_id2" class="form-control" style="width: 100%;">
									
									<?php
										echo '<option value="'. '0' .'" selected="selected">' .'-'. '</option>';

										foreach ($categories as $category) {
											echo '<option value="' . $category->id . '">' . $category->name . '</option>';
										}

									?>

								</select>

							</div>

						</div>

						<div class="form-group">

							<label for="topic_id3" class="col-sm-4 col-md-4 col-lg-4 control-label">Topic 3</label>

							<div class="col-sm-6 col-md-6 col-lg-6">

								<select name="topic_id3" id="topic_id3" class="form-control" style="width: 100%;">

									<?php
										echo '<option value="'. '0' .'" selected="selected">' .'-'. '</option>';

										foreach ($categories as $category) {
											echo '<option value="' . $category->id . '">' . $category->name . '</option>';
										}

									?>

								</select>

							</div>

						</div>

						<div class="form-group">

							<label for="topic_id4" class="col-sm-4 col-md-4 col-lg-4 control-label">Topic 4</label>

							<div class="col-sm-6 col-md-6 col-lg-6">

								<select name="topic_id4" id="topic_id4" class="form-control" style="width: 100%;">

									<?php
										echo '<option value="'. '0' .'" selected="selected">' .'-'. '</option>';

										foreach ($categories as $category) {
											echo '<option value="' . $category->id . '">' . $category->name . '</option>';
										}

									?>

								</select>

							</div>

						</div>

						<div class="form-group">

							<label for="key_topic" class="col-sm-4 col-md-4 col-lg-4 control-label">Key Topic</label>

							<div class="col-sm-6 col-md-6 col-lg-6">

								<select name="key_topic" id="key_topic" class="form-control" style="width: 12.5%;">
									
									<?php
										echo '<option value="0" selected="selected">0</option>';
										echo '<option value="1">1</option>';
									?>

								</select>

							</div>

						</div>

						<div class="form-group">

							<label for="key_strategy" class="col-sm-4 col-md-4 col-lg-4 control-label">Key Strategy</label>

							<div class="col-sm-6 col-md-6 col-lg-6">

								<select name="key_strategy" id="key_strategy" class="form-control" style="width: 12.5%;">
									
									<?php
										echo '<option value="0" selected="selected">0</option>';
										echo '<option value="1">1</option>';
									?>

								</select>

							</div>

						</div>


						<div class="form-group">

							<label for="substrategy_id" class="col-sm-4 col-md-4 col-lg-4 control-label">Sub Strategy 1</label>

							<div class="col-sm-6 col-md-6 col-lg-6">

								<select name="substrategy_id" id="substrategy_id" class="form-control" style="width: 100%;">

									<?php
										echo '<option value="'. '0' .'" selected="selected">' .'-'. '</option>';
										
										foreach ($substrategies as $substrategy) {
											echo '<option value="' . $substrategy->id . '">' . $substrategy->name . '</option>';
										}

									?>

								</select>

							</div>

						</div>

						<div class="form-group">

							<label for="substrategy_id2" class="col-sm-4 col-md-4 col-lg-4 control-label">Sub Strategy 2</label>

							<div class="col-sm-6 col-md-6 col-lg-6">

								<select name="substrategy_id2" id="substrategy_id2" class="form-control" style="width: 100%;">

									<?php
										echo '<option value="'. '0' .'" selected="selected">' .'-'. '</option>';
										
										foreach ($substrategies as $substrategy) {
											echo '<option value="' . $substrategy->id . '">' . $substrategy->name . '</option>';
										}

									?>

								</select>

							</div>

						</div>

						<div class="form-group">

							<label for="substrategy_id3" class="col-sm-4 col-md-4 col-lg-4 control-label">Sub Strategy 3</label>

							<div class="col-sm-6 col-md-6 col-lg-6">

								<select name="substrategy_id3" id="substrategy_id3" class="form-control" style="width: 100%;">

									<?php
										echo '<option value="'. '0' .'" selected="selected">' .'-'. '</option>';
										
										foreach ($substrategies as $substrategy) {
											echo '<option value="' . $substrategy->id . '">' . $substrategy->name . '</option>';
										}

									?>

								</select>

							</div>

						</div>

						<div class="form-group">

							<label for="substrategy_id4" class="col-sm-4 col-md-4 col-lg-4 control-label">Sub Strategy 4</label>

							<div class="col-sm-6 col-md-6 col-lg-6">

								<select name="substrategy_id4" id="substrategy_id4" class="form-control" style="width: 100%;">

									<?php
										echo '<option value="'. '0' .'" selected="selected">' .'-'. '</option>';
										
										foreach ($substrategies as $substrategy) {
											echo '<option value="' . $substrategy->id . '">' . $substrategy->name . '</option>';
										}

									?>

								</select>
								<p style="padding-top:1em;"><i>Strategy ID will be included by the system based on the Sub Strategy ID</i></p>

							</div>

						</div>

						<div class="form-group">

							<label for="strategy_id" class="col-sm-4 col-md-4 col-lg-4 control-label">Strategy 1</label>

							<div class="col-sm-6 col-md-6 col-lg-6">

								<select name="strategy_id" id="strategy_id" class="form-control" style="width: 100%;">

									<?php
										echo '<option value="'. '0' .'" selected="selected">' .'-'. '</option>';

										foreach ($strategies as $strategy) {
											echo '<option value="' . $strategy->id . '">' . $strategy->name . '</option>';
										}

									?>

								</select>

							</div>

						</div>

						<div class="form-group">

							<label for="strategy_id2" class="col-sm-4 col-md-4 col-lg-4 control-label">Strategy 2</label>

							<div class="col-sm-6 col-md-6 col-lg-6">

								<select name="strategy_id2" id="strategy_id2" class="form-control" style="width: 100%;">

									<?php
										echo '<option value="'. '0' .'" selected="selected">' .'-'. '</option>';

										foreach ($strategies as $strategy) {
											echo '<option value="' . $strategy->id . '">' . $strategy->name . '</option>';
										}

									?>

								</select>

							</div>

						</div>

						<div class="form-group">

							<label for="strategy_id3" class="col-sm-4 col-md-4 col-lg-4 control-label">Strategy 3</label>

							<div class="col-sm-6 col-md-6 col-lg-6">

								<select name="strategy_id3" id="strategy_id3" class="form-control" style="width: 100%;">

									<?php
										echo '<option value="'. '0' .'" selected="selected">' .'-'. '</option>';

										foreach ($strategies as $strategy) {
											echo '<option value="' . $strategy->id . '">' . $strategy->name . '</option>';
										}

									?>

								</select>

							</div>

						</div>

						<div class="form-group">

							<label for="strategy_id4" class="col-sm-4 col-md-4 col-lg-4 control-label">Strategy 4</label>

							<div class="col-sm-6 col-md-6 col-lg-6">

								<select name="strategy_id4" id="strategy_id4" class="form-control" style="width: 100%;">

									<?php
										echo '<option value="'. '0' .'" selected="selected">' .'-'. '</option>';

										foreach ($strategies as $strategy) {
											echo '<option value="' . $strategy->id . '">' . $strategy->name . '</option>';
										}

									?>

								</select>

							</div>

						</div>						

						<div class="form-group">
							<label for="casa" class="col-sm-4 col-md-4 col-lg-4 control-label">Type/Tags/Label (Max 5)</label>
								<div class="col-sm-6 col-md-6 col-lg-6">
								
								<?php 
									if (isset($selected_tags) && empty($selected_tags) === false) {
										echo '<input name="tagsinput" class="tagsinput" data-role="tagsinput" placeholder="eg: CA1, Mock Exam 2" value="' . $selected_tags . '" style="display: none;">';
									} else {
										echo '<input name="tagsinput" class="tagsinput" data-role="tagsinput" placeholder="eg: CA1, Mock Exam 2" value="" style="display: none;">';
									}
								?>
								
								</div>
						</div>

						<div class="form-group">
							<label for="year" class="col-sm-4 col-md-4 col-lg-4 control-label">Year</label>
								<div class="col-sm-5 col-md-5 col-lg-5">
									<input style="width: 25%;" name="year" id="year" type="number" class="form-control" value="<?=date('Y')?>">
								</div>
						</div>

						<div class="form-group">
							<label for="difficulty" class="col-sm-4 col-md-4 col-lg-4 control-label">Difficulty</label>
								<div class="col-sm-6 col-md-6 col-lg-6">
									<select name="difficulty" id="difficulty" class="form-control">
										<option value='1' <?php echo (isset($selected_difficulty) && empty($selected_difficulty) === false && $selected_difficulty == '1')?'selected="selected"':''; ?>>1 - Easy</option>
										<option value='2' <?php echo (isset($selected_difficulty) && empty($selected_difficulty) === false && $selected_difficulty == '2')?'selected="selected"':''; ?>>2 - Normal</option>
										<option value='3' <?php echo (isset($selected_difficulty) && empty($selected_difficulty) === false && $selected_difficulty == '3')?'selected="selected"':''; ?>>3 - Hard</option>
										<option value='4' <?php echo (isset($selected_difficulty) && empty($selected_difficulty) === false && $selected_difficulty == '4')?'selected="selected"':''; ?>>4 - Genius</option>
										<option value='5' <?php echo (isset($selected_difficulty) && empty($selected_difficulty) === false && $selected_difficulty == '5')?'selected="selected"':''; ?>>5 - Genius</option>
									</select>
								</div>
						</div>

						<div class="form-group">
							<label for="question_type_id" class="col-sm-4 col-md-4 col-lg-4 control-label">Question Type</label>
								<div class="col-sm-6 col-md-6 col-lg-6">
									<select name="question_type_id" id="" class="form-control question_type_id">
										<option value="1" <?php echo (isset($selected_question_type) && empty($selected_question_type) === false && $selected_question_type == '1')?'selected="selected"':''; ?>>MCQ (4 options)</option>
										<option value="2" <?php echo (isset($selected_question_type) && empty($selected_question_type) === false && $selected_question_type == '2')?'selected="selected"':''; ?>>Open ended</option>
									</select>
								</div>
						</div>

						<div class="form-group" id="answerType">
								<label for="answer_type_id" class="col-sm-4 col-md-4 col-lg-4 control-label">Answer Type</label>
									<div class="col-sm-6 col-md-6 col-lg-6">
										<select name="answer_type_id" id="answer_type_id" class="form-control">

											<?php

												foreach($answer_types as $answer_type) {													
														
													echo '<option value="' . $answer_type->answer_type_id . '">' . $answer_type->answer_type . '</option>';
													
												}

											?>

										</select>
									</div>
						</div>

						<div id="mcq_input_answers_div">							

							<div class="form-group">
								<label for="answer_type_mcq" class="col-sm-4 col-md-4 col-lg-4 control-label"></label>
								<div class="col-sm-6 col-md-6 col-lg-6">
									<div class="col-sm-3 col-md-3 col-lg-2">
										<label class="radio-inline"><input type="radio" class="answer_type_mcq" data-id="-1" name="answer_type_mcq" value="text" checked>Text</label>
									</div>
									<div class="col-sm-3 col-md-3 col-lg-2">
										<label class="radio-inline"><input type="radio" class="answer_type_mcq" data-id="-1" name="answer_type_mcq" value="image">Image</label>
									</div>								
								</div>
							</div>

							<div class="form-group">
								<label for="question_answers" class="col-sm-4 col-md-4 col-lg-4 control-label">Answer 1</label>
									<div class="col-sm-5 col-md-5 col-lg-5 input_answer_text">
										<span id="mcq_ans_1" style="width: 100%; padding: 0.5em" class="math_text"></span>
										<input type="hidden" name="mcq_answers[]">
									</div>
									<div class="col-sm-5 col-md-5 col-lg-5 input_answer_image" style="display: none;">									
										<input type="file" class="form-control" id="mcq_answers_image1" name="mcq_answers_image[]" style="padding-top: 5px;"/>
									</div>
									<div class="col-sm-3 col-md-3 col-lg-3">
										<label>
											<input type="radio" name="mcq_correct_answer" value="1" checked="checked">
											Select for correct ans
										</label>
									</div>
							</div>

							<div class="form-group">
								<label for="question_answers" class="col-sm-4 col-md-4 col-lg-4 control-label">Answer 2</label>
									<div class="col-sm-5 col-md-5 col-lg-5 input_answer_text">
										<span id="mcq_ans_2" style="width: 100%; padding: 0.5em" class="math_text"></span>
										<input type="hidden" name="mcq_answers[]">
									</div>
									<div class="col-sm-5 col-md-5 col-lg-5 input_answer_image" style="display: none;">									
										<input type="file" class="form-control" id="mcq_answers_image2" name="mcq_answers_image[]" style="padding-top: 5px;"/>
									</div>
									<div class="col-sm-3 col-md-3 col-lg-3">
										<label>
											<input type="radio" name="mcq_correct_answer"  value="2">
											Select for correct ans
										</label>
									</div>
							</div>

							<div class="form-group">
								<label for="question_answers" class="col-sm-4 col-md-4 col-lg-4 control-label">Answer 3</label>
									<div class="col-sm-5 col-md-5 col-lg-5 input_answer_text">
										<span id="mcq_ans_3" style="width: 100%; padding: 0.5em" class="math_text"></span>
										<input type="hidden" name="mcq_answers[]">
									</div>
									<div class="col-sm-5 col-md-5 col-lg-5 input_answer_image" style="display: none;">									
										<input type="file" class="form-control" id="mcq_answers_image3" name="mcq_answers_image[]" style="padding-top: 5px;"/>
									</div>
									<div class="col-sm-3 col-md-3 col-lg-3">
										<label>
											<input type="radio" name="mcq_correct_answer" value="3">
											Select for correct ans
										</label>
									</div>
							</div>

							<div class="form-group">
								<label for="question_answers" class="col-sm-4 col-md-4 col-lg-4 control-label">Answer 4</label>
									<div class="col-sm-5 col-md-5 col-lg-5 input_answer_text">
										<span id="mcq_ans_4" style="width: 100%; padding: 0.5em" class="math_text"></span>
										<input type="hidden" name="mcq_answers[]">
									</div>
									<div class="col-sm-5 col-md-5 col-lg-5 input_answer_image" style="display: none;">									
										<input type="file" class="form-control" id="mcq_answers_image4" name="mcq_answers_image[]" style="padding-top: 5px;"/>
									</div>
									<div class="col-sm-3 col-md-3 col-lg-3">
										<label>
											<input type="radio" name="mcq_correct_answer" value="4">
											Select for correct ans
										</label>
									</div>
							</div>
						
						</div>					

						<div id="open_ended_input_answers_div">		

							<div class="form-group">
								<label for="answer_type_nmcq" class="col-sm-4 col-md-4 col-lg-4 control-label"></label>
								<div class="col-sm-6 col-md-6 col-lg-6">
									<div class="col-sm-3 col-md-3 col-lg-2">
										<label class="radio-inline"><input type="radio" class="answer_type_nmcq" data-id="-1" name="answer_type_nmcq" value="text" checked>Text</label>
									</div>
									<div class="col-sm-3 col-md-3 col-lg-2">
										<label class="radio-inline"><input type="radio" class="answer_type_nmcq" data-id="-1" name="answer_type_nmcq" value="image">Image</label>
									</div>								
								</div>
							</div>					

							<div class="form-group">
								<label for="question_answers" class="col-sm-4 col-md-4 col-lg-4 control-label">Answer</label>
									<div class="col-sm-5 col-md-5 col-lg-5 input_answer_text">
										<span id="open_ended_answer" style="width: 100%; padding: 0.5em" class="math_text"></span>
										<input type="hidden" name="open_ended_answer" class="form-control">
									</div>
									<div class="col-sm-5 col-md-5 col-lg-5 input_answer_image" style="display: none;">									
										<input type="file" class="form-control" id="nmcq_answers_image" name="nmcq_answers_image[]" style="padding-top: 5px;"/>
									</div>
							</div>
						</div>

						<div class="form-group">
							<label for="question_text" class="col-sm-4 col-md-4 col-lg-4 control-label">Workings</label>

							<div class="col-sm-6 col-md-6 col-lg-6">
								<div class="panel panel-default">
									<div class="panel-body">
										<div class="row working_content"></div>
										
										<div class="dropdown" id="addWorkingContenButton">
											<button class="btn btn-icon-o radius100 text-muted btn-outline-default dropdown-toggle" type="button" data-toggle="dropdown">
											<span class="fa fa-plus"></span></button>
												<ul class="dropdown-menu">
													<li class="dropdown-header">Please choose text or image</li>
													<li>
														<a style="cursor: pointer;" onClick="addWorkingContent('text')"><i class="fa fa-text-height mr-2"></i> Text</a>
													</li>
													<li>
														<a style="cursor: pointer;" onClick="addWorkingContent('image')"><i class="fa fa-picture-o mr-2"></i> Image</a>
													</li>
												</ul>
										</div>
									</div>
								</div>
							</div>
						</div>



					</div> <!-- div addNewQuestionDiv -->

					<input type="hidden" name="subQuestionNumber" id="subQuestionNumber" value="0">
					<div class="col-sm-offset-4 col-sm-4 col-md-offset-4 col-md-4 col-lg-offset-4 col-lg-4">
						<button class="btn btn-danger btn-block" id="removeSubQuestion">Remove sub question</button>     
					</div>

					<div class="col-sm-offset-2 col-sm-4 col-md-offset-2 col-md-4 col-lg-offset-2 col-lg-4">
						<button class="btn btn-warning btn-block" id="addSubQuestion">Add sub question</button>
					</div>

					<div class="col-sm-4 col-md-4 col-lg-4">
						<input type="submit" class="btn btn-custom btn-block" value="Create new question">
					</div>

					<div class="col-sm-12 col-md-12 col-lg-12 panel_subquestion">
					</div>

				</form>

			</div>

		</div>
	</div>
</div>
<!-- <div id="smartjen_insert_btn">
	<a href="#" id="mathExpressionBtn" class="btn btn-custom btn-no-margin-side pull-right" data-toggle="modal" data-target="#mathExpressionKeyboard"><i class="fa fa-calculator"></i> Math Expression</a>
</div> -->

<div class="panel_math_quill" style="display: none;">
	<div class="card-columns">
		<div class="card" style="width: 100vw;">
			<div class="card-header-small">
				<h5 class="card-title fs14">
					Math Expression
				</h5>	
				
				<a class="close_panel icon_close fs14">
                    <i class="fa fa-times"></i>
                </a>
			</div>
			<div class="card-body-small">
				<input type="hidden" id="mathTarget" value="">
				<div id="keyboard">
					<div role="group" aria-label="math functions">
						<button type="button" class="btn btn-default" onClick='input("\\frac")'>Fraction \(\frac{x}{y}\)</button>
						<button type="button" class="btn btn-default" onClick='inputMultiple("^", "\\circ")'>Degree \(^\circ\)</button>
						<button type="button" class="btn btn-default" onClick='input("\\pi")'>Pi \(\pi\)</button>
						<button type="button" class="btn btn-default" onClick='input("\\angle")'>Angle \(\angle\)</button>
						<button type="button" class="btn btn-default" onClick='inputMultiple("^", "2")'>Power \(x^2\)</button>
						<button type="button" class="btn btn-default" onClick='inputMultiple("^", "3")'>Power \(x^3\)</button>
						<button type="button" class="btn btn-default" onClick='input("mℓ")'>Millilitre mℓ</button>
						<button type="button" class="btn btn-default" onClick='input("ℓ")'>Litre ℓ</button>
						<button type="button" class="btn btn-default" onClick='input("\\times")'>Times \(\times\)</button>
						<button type="button" class="btn btn-default" onClick='input("\\div")'>Divide \(\div\)</button>
						<button type="button" class="btn btn-default" onClick='inputMultipleMultiple("<", "br", ">")'>Linebreak</button>
						<a href="#" class="btn btn-default" data-toggle="modal" data-target="#addQuestionModal"><i class="fa fa-copy"></i> Add Question Text </a>
					</div>
				</div>

				<div class="form-group">
					<span id="math-field" style="width: 100%; padding: 0.5em"></span>
				</div>
				<div class="form-group">
					<a href="<?php echo base_url() ?>administrator/latex" target="_blank"><h5>Click Here For More Math Symbol</h5></a>
				</div>
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
				<input type="hidden" id="mathTargetxxx" value="">
				<div id="keyboard">
					<div role="group" aria-label="math functions">
						<button type="button" class="btn btn-default" onClick='input("\\frac")'>Fraction \(\frac{x}{y}\)</button>
						<button type="button" class="btn btn-default" onClick='inputMultiple("^", "\\circ")'>Degree \(^\circ\)</button>
						<button type="button" class="btn btn-default" onClick='input("\\pi")'>Pi \(\pi\)</button>
						<button type="button" class="btn btn-default" onClick='input("\\angle")'>Angle \(\angle\)</button>
						<button type="button" class="btn btn-default" onClick='inputMultiple("^", "2")'>Power \(x^2\)</button>
						<button type="button" class="btn btn-default" onClick='inputMultiple("^", "3")'>Power \(x^3\)</button>
						<button type="button" class="btn btn-default" onClick='input("mℓ")'>Millilitre mℓ</button>
						<button type="button" class="btn btn-default" onClick='input("ℓ")'>Litre ℓ</button>
						<button type="button" class="btn btn-default" onClick='input("\\times")'>Times \(\times\)</button>
						<button type="button" class="btn btn-default" onClick='input("\\div")'>Divide \(\div\)</button>
						<button type="button" class="btn btn-default" onClick='inputMultipleMultiple("<", "br", ">")'>Linebreak</button>
					</div>
				</div>

				<div class="form-group">
					<span id="math-field" style="width: 100%; padding: 0.5em"></span>
				</div>
				<div class="form-group">
					<a href="<?php echo base_url() ?>administrator/latex" target="_blank"><h5>Click Here For More Math Symbol</h5></a>
				</div>
			</div>

			<div class="modal-footer">
				<input type="button" class="btn btn-default" data-dismiss="modal" value="Close">
			</div>
			
		</div>
	</div>
</div>

<div class="modal fade" id="addQuestionModal" role="dialog">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header modal-header-custom-success">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title">Insert Questions</h4>
			</div>

			<form class="form-horizontal" action="" method="post" accept-charset="utf-8">
				<div class="modal-body">
					<textarea name="inputWord" id="inputWord" placeholder="Paste text from word / excel here" class="form-control" style="width: 100%; height: 150px; padding: 0.5em"></textarea>
				</div>

				<div class="modal-footer">
					<input type="button" class="btn btn-default" data-dismiss="modal" value="Close">
					<button type="button" class="btn btn-custom" onClick='inputText()'>Insert</button>
				</div>
			</form>
		</div>
	</div>
</div>

<script>
	var mathKeyboard = $('#mathExpressionKeyboard');
	var inputWordKeyboard = $('#addQuestionModal');
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

	function inputText() {

		let str2 = document.getElementById("inputWord").value;

		let str_array = str2.split("");

		for(var i = 0; i < str_array.length; i++) {

			let spanId = $('#mathTarget').val();

			let spanTarget = document.getElementById(spanId);

			let mathSpan = MQ(spanTarget);

			if (mathSpan) {

				inputWordKeyboard.modal('hide');
				
				mathSpan.cmd(str_array[i]);

				mathSpan.focus();

			}
		}

		document.getElementById("inputWord").value = '';

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

	function inputMultipleMultiple(str1, str2, str3) {

		let spanId = $('#mathTarget').val();

		let spanTarget = document.getElementById(spanId);

		let mathSpan = MQ(spanTarget);

		if (mathSpan) {

			mathKeyboard.modal('hide');

			mathSpan.cmd(str1);

			mathSpan.cmd(str2);

			mathSpan.cmd(str3);

			mathSpan.focus();

		}
	}
	

	var iqc = [];

	var question_content_id = 0;
	var content_counter = 0;

	function addQuestionContent(type){
		
		if(type == 'text'){
			
			content = `<div class="col-lg-12">
							<div class="p-1 border rounded">
								<div class="form-inline">
									<div class="form-group" style="width: 100%;text-align: center;">
										<span id="question_text`+question_content_id+`" style="height: 70px; width: 90%; padding: 0.5em;text-align:left;" class="form-control math_text math_text_`+question_content_id+`">										
										</span>		
										<input name="input_question_text[]" id="question_text_hidden`+question_content_id+`" type="hidden" class="input_question_content" data-type="text">																
										<a style="cursor: pointer; text-decoration: none;"
											class="removeQuestionContent text-danger-active fs18 ml-2" data-index="`+question_content_id+`" data-id="text"><i
												class="fa fa-times"></i></a>
									</div>                                                                                                     
								</div>
							</div>
						</div>`;						
			
		}else if(type == 'image'){
			content = `<div class="col-lg-12">
							<div class="p-1 border rounded">
								<div class="form-inline">
									<div class="form-group" style="width: 100%;text-align: center;">
										<input type="file" class="form-control input_question_content" data-type="image" name="input_question_image[]" style="width: 90%; height: 40px; padding-top: 6px;" multiple="multiple" required/>
										<a style="cursor: pointer; text-decoration: none;"
											class="removeQuestionContent text-danger-active fs18 ml-2" data-index="`+question_content_id+`" data-id="image"><i
												class="fa fa-times"></i></a>     
									</div>                                                                                                     
								</div>
							</div>
						</div>`;
			
		}

		$('.panel-body .question_content').append(content);

		if(type == 'text'){
			$('.math_text').each(function(index) {
					let span_id = $(this).attr('id');
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

			$(document).on('focusin', '.math_text_'+question_content_id, function() {
				$('.panel_math_quill').fadeIn('fast');
				$('#mathTarget').val($(this).attr('id'));
			});
			
		}

		question_content_id++;
		content_counter++;

		//hide addQuestionContentButton when there 6 fields append 
		if(content_counter == 6){
			$('#addQuestionContenButton').hide();
		}

	}

	var iwc = [];

	var working_content_id = 0;
	var working_content_counter = 0;

	function addWorkingContent(type){
		
		if(type == 'text'){
			
			content = `<div class="col-lg-12">
							<div class="p-1 border rounded">
								<div class="form-inline">
									<div class="form-group" style="width: 100%;text-align: center;">
										<span id="working_text`+working_content_id+`" style="height: 70px; width: 90%; padding: 0.5em;text-align:left;" class="form-control math_text math_text_`+working_content_id+`">										
										</span>		
										<input name="input_working_text[]" id="question_text_hidden`+working_content_id+`" type="hidden" class="input_working_content" data-type="text">																
										<a style="cursor: pointer; text-decoration: none;"
											class="removeQuestionContent text-danger-active fs18 ml-2" data-index="`+working_content_id+`" data-id="text"><i
												class="fa fa-times"></i></a>
									</div>                                                                                                     
								</div>
							</div>
						</div>`;						
			
		}else if(type == 'image'){
			content = `<div class="col-lg-12">
							<div class="p-1 border rounded">
								<div class="form-inline">
									<div class="form-group" style="width: 100%;text-align: center;">
										<input type="file" class="form-control input_working_content" data-type="image" name="input_working_image[]" style="width: 90%; height: 40px; padding-top: 6px;" multiple="multiple" required/>
										<a style="cursor: pointer; text-decoration: none;"
											class="removeQuestionContent text-danger-active fs18 ml-2" data-index="`+working_content_id+`" data-id="image"><i
												class="fa fa-times"></i></a>     
									</div>                                                                                                     
								</div>
							</div>
						</div>`;
			
		}

		$('.panel-body .working_content').append(content);

		if(type == 'text'){
			$('.math_text').each(function(index) {
					let span_id = $(this).attr('id');
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

			$(document).on('focusin', '.math_text_'+working_content_id, function() {
				$('.panel_math_quill').fadeIn('fast');
				$('#mathTarget').val($(this).attr('id'));
			});
			
		}

		working_content_id++;
		working_content_counter++;

		//hide addQuestionContentButton when there 6 fields append 
		if(working_content_counter == 6){
			$('#addWorkingContenButton').hide();
		}

	}


	var subquestion_content_id0 = 0, subquestion_content_id1 = 0, subquestion_content_id2 = 0, subquestion_content_id3 = 0, subquestion_content_id4 = 0,
	subquestion_content_id5 = 0, subquestion_content_id6 = 0, subquestion_content_id7 = 0, subquestion_content_id8 = 0, subquestion_content_id9 = 0, subquestion_content_id10 = 0;

	var subcontent_counter0 = 0, subcontent_counter1 = 0, subcontent_counter2 = 0, subcontent_counter3 = 0, subcontent_counter4 = 0, subcontent_counter5 = 0,
	subcontent_counter6 = 0, subcontent_counter7 = 0, subcontent_counter8 = 0, subcontent_counter9 = 0, subcontent_counter10 = 0;	

	function addSubQuestionContent(type, numSubquestion){

		if(numSubquestion == 0) var lastIndex = numSubquestion + '_' + subquestion_content_id0;
		if(numSubquestion == 1) var lastIndex = numSubquestion + '_' + subquestion_content_id1;
		if(numSubquestion == 2) var lastIndex = numSubquestion + '_' + subquestion_content_id2;
		if(numSubquestion == 3) var lastIndex = numSubquestion + '_' + subquestion_content_id3;
		if(numSubquestion == 4) var lastIndex = numSubquestion + '_' + subquestion_content_id4;
		if(numSubquestion == 5) var lastIndex = numSubquestion + '_' + subquestion_content_id5;
		if(numSubquestion == 6) var lastIndex = numSubquestion + '_' + subquestion_content_id6;
		if(numSubquestion == 7) var lastIndex = numSubquestion + '_' + subquestion_content_id7;
		if(numSubquestion == 8) var lastIndex = numSubquestion + '_' + subquestion_content_id8;
		if(numSubquestion == 9) var lastIndex = numSubquestion + '_' + subquestion_content_id9;
		if(numSubquestion == 10) var lastIndex = numSubquestion + '_' + subquestion_content_id10;
		
				
		if(type == 'text'){
						
			var content = `<div class="col-lg-12">
							<div class="p-1 border rounded">
								<div class="form-inline">
									<div class="form-group" style="width: 100%;text-align: center;">
										<span id="subquestion_text`+lastIndex+`" style="height: 70px; width: 90%; padding: 0.5em;text-align:left;" class="form-control math_text">										
										</span>		
										<input name="input_subquestion_text_`+numSubquestion+`_[]" id="subquestion_text_hidden`+lastIndex+`" type="hidden" class="input_question_content`+numSubquestion+`" data-type="text">
										<a style="cursor: pointer; text-decoration: none;"
											class="removeSubQuestionContent text-danger-active fs18 ml-2" data-id="text" data-sqnumber="`+numSubquestion+`"><i
												class="fa fa-times"></i></a>
									</div>                                                                                                     
								</div>
							</div>
						</div>`;			
			
		}else if(type == 'image'){
			var content = `<div class="col-lg-12">
							<div class="p-1 border rounded">
								<div class="form-inline">
									<div class="form-group" style="width: 100%;text-align: center;">
										<input type="file" class="form-control input_question_content`+numSubquestion+`" name="input_subquestion_image_`+numSubquestion+`_[]" multiple="multiple" data-type="image" style="width: 90%; height: 40px; padding-top: 6px;" required/>
										<a style="cursor: pointer; text-decoration: none;"
											class="removeSubQuestionContent text-danger-active fs18 ml-2" data-id="image" data-sqnumber="`+numSubquestion+`"><i
												class="fa fa-times"></i></a>     
									</div>                                                                                                     
								</div>
							</div>
						</div>`;
		}


		$('.panel-body .question_content'+numSubquestion).append(content);

		if(type == 'text'){
			$('.math_text').each(function(index) {
					let span_id = $(this).attr('id');
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

			$(document).on('focusin', '.math_subtext_'+lastIndex, function() {
				$('.panel_math_quill').fadeIn('fast');
				$('#mathTarget').val($(this).attr('id'));
			});
		}

		if(numSubquestion == 0) {subquestion_content_id0++; subcontent_counter0++; if(subcontent_counter0 == 6){ $('#addQuestionContenButton'+numSubquestion).hide(); }}
		if(numSubquestion == 1) {subquestion_content_id1++; subcontent_counter1++; if(subcontent_counter1 == 6){ $('#addQuestionContenButton'+numSubquestion).hide(); }}
		if(numSubquestion == 2) {subquestion_content_id2++; subcontent_counter2++; if(subcontent_counter2 == 6){ $('#addQuestionContenButton'+numSubquestion).hide(); }}
		if(numSubquestion == 3) {subquestion_content_id3++; subcontent_counter3++; if(subcontent_counter3 == 6){ $('#addQuestionContenButton'+numSubquestion).hide(); }}
		if(numSubquestion == 4) {subquestion_content_id4++; subcontent_counter4++; if(subcontent_counter4 == 6){ $('#addQuestionContenButton'+numSubquestion).hide(); }}
		if(numSubquestion == 5) {subquestion_content_id5++; subcontent_counter5++; if(subcontent_counter5 == 6){ $('#addQuestionContenButton'+numSubquestion).hide(); }}
		if(numSubquestion == 6) {subquestion_content_id6++; subcontent_counter6++; if(subcontent_counter6 == 6){ $('#addQuestionContenButton'+numSubquestion).hide(); }}
		if(numSubquestion == 7) {subquestion_content_id7++; subcontent_counter7++; if(subcontent_counter7 == 6){ $('#addQuestionContenButton'+numSubquestion).hide(); }}
		if(numSubquestion == 8) {subquestion_content_id8++; subcontent_counter8++; if(subcontent_counter8 == 6){ $('#addQuestionContenButton'+numSubquestion).hide(); }}
		if(numSubquestion == 9) {subquestion_content_id9++; subcontent_counter9++; if(subcontent_counter9 == 6){ $('#addQuestionContenButton'+numSubquestion).hide(); }}
		if(numSubquestion == 10) {subquestion_content_id10++; subcontent_counter10++; if(subcontent_counter10 == 6){ $('#addQuestionContenButton'+numSubquestion).hide(); }}

	}

	var subworking_content_id0 = 0, subworking_content_id1 = 0, subworking_content_id2 = 0, subworking_content_id3 = 0, subworking_content_id4 = 0,
	subworking_content_id5 = 0, subworking_content_id6 = 0, subworking_content_id7 = 0, subworking_content_id8 = 0, subworking_content_id9 = 0, subquestion_content_id10 = 0;

	var subcontent_counter0 = 0, subcontent_counter1 = 0, subcontent_counter2 = 0, subcontent_counter3 = 0, subcontent_counter4 = 0, subcontent_counter5 = 0,
	subcontent_counter6 = 0, subcontent_counter7 = 0, subcontent_counter8 = 0, subcontent_counter9 = 0, subcontent_counter10 = 0;	

	function addSubWorkingContent(type, numSubworking){

		if(numSubworking == 0) var lastIndex = numSubworking + '_' + subworking_content_id0;
		if(numSubworking == 1) var lastIndex = numSubworking + '_' + subworking_content_id1;
		if(numSubworking == 2) var lastIndex = numSubworking + '_' + subworking_content_id2;
		if(numSubworking == 3) var lastIndex = numSubworking + '_' + subworking_content_id3;
		if(numSubworking == 4) var lastIndex = numSubworking + '_' + subworking_content_id4;
		if(numSubworking == 5) var lastIndex = numSubworking + '_' + subworking_content_id5;
		if(numSubworking == 6) var lastIndex = numSubworking + '_' + subworking_content_id6;
		if(numSubworking == 7) var lastIndex = numSubworking + '_' + subworking_content_id7;
		if(numSubworking == 8) var lastIndex = numSubworking + '_' + subworking_content_id8;
		if(numSubworking == 9) var lastIndex = numSubworking + '_' + subworking_content_id9;
		if(numSubworking == 10) var lastIndex = numSubworking + '_' + subworking_content_id10;

				
		if(type == 'text'){
						
			var content = `<div class="col-lg-12">
							<div class="p-1 border rounded">
								<div class="form-inline">
									<div class="form-group" style="width: 100%;text-align: center;">
										<span id="subworking_text`+lastIndex+`" style="height: 70px; width: 90%; padding: 0.5em;text-align:left;" class="form-control math_text">										
										</span>		
										<input name="input_subworking_text_`+numSubworking+`_[]" id="subworking_text_hidden`+lastIndex+`" type="hidden" class="input_working_content`+numSubworking+`" data-type="text">
										<a style="cursor: pointer; text-decoration: none;"
											class="removeSubQuestionContent text-danger-active fs18 ml-2" data-id="text" data-sqnumber="`+numSubworking+`"><i
												class="fa fa-times"></i></a>
									</div>                                                                                                     
								</div>
							</div>
						</div>`;			
			
		}else if(type == 'image'){
			var content = `<div class="col-lg-12">
							<div class="p-1 border rounded">
								<div class="form-inline">
									<div class="form-group" style="width: 100%;text-align: center;">
									<input type="file" class="form-control input_working_content`+numSubworking+`" name="input_subworking_image_`+numSubworking+`_[]" multiple="multiple" data-type="image" style="width: 90%; height: 40px; padding-top: 6px;" required/>
										<a style="cursor: pointer; text-decoration: none;"
											class="removeSubQuestionContent text-danger-active fs18 ml-2" data-id="image" data-sqnumber="`+numSubworking+`"><i
												class="fa fa-times"></i></a>     
									</div>                                                                                                     
								</div>
							</div>
						</div>`;
		}


		$('.panel-body .working_content'+numSubworking).append(content);

		if(type == 'text'){
			$('.math_text').each(function(index) {
					let span_id = $(this).attr('id');
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

			$(document).on('focusin', '.math_subtext_'+lastIndex, function() {
				$('.panel_math_quill').fadeIn('fast');
				$('#mathTarget').val($(this).attr('id'));
			});
		}

		if(numSubworking == 0) {subworking_content_id0++; subcontent_counter0++; if(subcontent_counter0 == 6){ $('#addWorkingContenButton'+numSubworking).hide(); }}
		if(numSubworking == 1) {subworking_content_id1++; subcontent_counter1++; if(subcontent_counter1 == 6){ $('#addWorkingContenButton'+numSubworking).hide(); }}
		if(numSubworking == 2) {subworking_content_id2++; subcontent_counter2++; if(subcontent_counter2 == 6){ $('#addWorkingContenButton'+numSubworking).hide(); }}
		if(numSubworking == 3) {subworking_content_id3++; subcontent_counter3++; if(subcontent_counter3 == 6){ $('#addWorkingContenButton'+numSubworking).hide(); }}
		if(numSubworking == 4) {subworking_content_id4++; subcontent_counter4++; if(subcontent_counter4 == 6){ $('#addWorkingContenButton'+numSubworking).hide(); }}
		if(numSubworking == 5) {subworking_content_id5++; subcontent_counter5++; if(subcontent_counter5 == 6){ $('#addWorkingContenButton'+numSubworking).hide(); }}
		if(numSubworking == 6) {subworking_content_id6++; subcontent_counter6++; if(subcontent_counter6 == 6){ $('#addWorkingContenButton'+numSubworking).hide(); }}
		if(numSubworking == 7) {subworking_content_id7++; subcontent_counter7++; if(subcontent_counter7 == 6){ $('#addWorkingContenButton'+numSubworking).hide(); }}
		if(numSubworking == 8) {subworking_content_id8++; subcontent_counter8++; if(subcontent_counter8 == 6){ $('#addWorkingContenButton'+numSubworking).hide(); }}
		if(numSubworking == 9) {subworking_content_id9++; subcontent_counter9++; if(subcontent_counter9 == 6){ $('#addWorkingContenButton'+numSubworking).hide(); }}
		if(numSubworking == 10) {subworking_content_id10++; subcontent_counter10++; if(subcontent_counter10 == 6){ $('#addWorkingContenButton'+numSubworking).hide(); }}

	}


	$(document).ready(function() {

		$('.panel_math_quill').hide();

		$(document).on('click', '.close_panel', function(){
			$('.panel_math_quill').fadeOut('fast');
		})

		$(document).on('click', '.removeQuestionContent', function (e){
			e.preventDefault();
			question_content_id--;
			content_counter--;

			// Shuq -  addQuestionContentButton if content less than 6
			if(content_counter < 6){
				$('#addWorkingContenButton').show();
			}
			$(this).parent().parent().parent().parent().remove();
		})

		//Answer text image option for mcq
		$(document).on('click', '.answer_type_mcq', function (){
			
			var type = $(this).val();
			var id = $(this).data('id');
				
			if(type == 'text'){
				if(id == '-1'){
					$('#mcq_input_answers_div .input_answer_image').hide();
					$('#mcq_input_answers_div .input_answer_text').show();

					document.getElementById("nmcq_answers_image").required = false;
					document.getElementById("mcq_answers_image1").required = false;
					document.getElementById("mcq_answers_image2").required = false;
					document.getElementById("mcq_answers_image3").required = false;
					document.getElementById("mcq_answers_image4").required = false;
				}else{
					$('#mcq_input_answers_div_' + id + ' .input_answer_image').hide();
					$('#mcq_input_answers_div_' + id + ' .input_answer_text').show();

					document.getElementById("nmcq_answers_image_"+id).required = false;
					document.getElementById("mcq_answers_image_"+id+"_1").required = false;
					document.getElementById("mcq_answers_image_"+id+"_2").required = false;
					document.getElementById("mcq_answers_image_"+id+"_3").required = false;
					document.getElementById("mcq_answers_image_"+id+"_4").required = false;
				}				
			}else{
				if(id == '-1'){
					$('#mcq_input_answers_div .input_answer_text').hide();
					$('#mcq_input_answers_div .input_answer_image').show();

					document.getElementById("nmcq_answers_image").required = false;
					document.getElementById("mcq_answers_image1").required = true;
					document.getElementById("mcq_answers_image2").required = true;
					document.getElementById("mcq_answers_image3").required = true;
					document.getElementById("mcq_answers_image4").required = true;
				}else{
					$('#mcq_input_answers_div_' + id + ' .input_answer_text').hide();
					$('#mcq_input_answers_div_' + id + ' .input_answer_image').show();

					document.getElementById("nmcq_answers_image_"+id).required = false;
					document.getElementById("mcq_answers_image_"+id+"_1").required = true;
					document.getElementById("mcq_answers_image_"+id+"_2").required = true;
					document.getElementById("mcq_answers_image_"+id+"_3").required = true;
					document.getElementById("mcq_answers_image_"+id+"_4").required = true;
				}			
				
			}

		})

		//Answer text image option for nmcq
		$(document).on('click', '.answer_type_nmcq', function (){
			
			var type = $(this).val();
			var id = $(this).data('id');
				
			if(type == 'text'){
				if(id == '-1'){
					$('#open_ended_input_answers_div .input_answer_image').hide();
					$('#open_ended_input_answers_div .input_answer_text').show();

					document.getElementById("nmcq_answers_image").required = false;
					document.getElementById("mcq_answers_image1").required = false;
					document.getElementById("mcq_answers_image2").required = false;
					document.getElementById("mcq_answers_image3").required = false;
					document.getElementById("mcq_answers_image4").required = false;
				}else{
					$('#open_ended_input_answers_div_' + id + ' .input_answer_image').hide();
					$('#open_ended_input_answers_div_' + id + ' .input_answer_text').show();

					document.getElementById("nmcq_answers_image_"+id).required = false;
					document.getElementById("mcq_answers_image_"+id+"_1").required = false;
					document.getElementById("mcq_answers_image_"+id+"_2").required = false;
					document.getElementById("mcq_answers_image_"+id+"_3").required = false;
					document.getElementById("mcq_answers_image_"+id+"_4").required = false;
				}				
			}else{
				if(id == '-1'){
					$('#open_ended_input_answers_div .input_answer_text').hide();
					$('#open_ended_input_answers_div .input_answer_image').show();

					document.getElementById("nmcq_answers_image").required = true;
					document.getElementById("mcq_answers_image1").required = false;
					document.getElementById("mcq_answers_image2").required = false;
					document.getElementById("mcq_answers_image3").required = false;
					document.getElementById("mcq_answers_image4").required = false;
				}else{
					$('#open_ended_input_answers_div_' + id + ' .input_answer_text').hide();
					$('#open_ended_input_answers_div_' + id + ' .input_answer_image').show();

					document.getElementById("nmcq_answers_image_"+id).required = true;
					document.getElementById("mcq_answers_image_"+id+"_1").required = false;
					document.getElementById("mcq_answers_image_"+id+"_2").required = false;
					document.getElementById("mcq_answers_image_"+id+"_3").required = false;
					document.getElementById("mcq_answers_image_"+id+"_4").required = false;
				}			
				
			}

		})

		$(document).on('click', '.removeSubQuestionContent', function (e){
			e.preventDefault();	
			
			var numSubquestion = parseInt($(this).data('sqnumber'));
			console.log(numSubquestion);
			if(numSubquestion == 0) {subquestion_content_id0--; subcontent_counter0--; if(subcontent_counter0 < 6){ $('#addQuestionContenButton'+numSubquestion).show(); }}
			if(numSubquestion == 1) {subquestion_content_id1--; subcontent_counter1--; if(subcontent_counter1 < 6){ $('#addQuestionContenButton'+numSubquestion).show(); }}
			if(numSubquestion == 2) {subquestion_content_id2--; subcontent_counter2--; if(subcontent_counter2 < 6){ $('#addQuestionContenButton'+numSubquestion).show(); }}
			if(numSubquestion == 3) {subquestion_content_id3--; subcontent_counter3--; if(subcontent_counter3 < 6){ $('#addQuestionContenButton'+numSubquestion).show(); }}
			if(numSubquestion == 4) {subquestion_content_id4--; subcontent_counter4--; if(subcontent_counter4 < 6){ $('#addQuestionContenButton'+numSubquestion).show(); }}
			if(numSubquestion == 5) {subquestion_content_id5--; subcontent_counter5--; if(subcontent_counter5 < 6){ $('#addQuestionContenButton'+numSubquestion).show(); }}
			if(numSubquestion == 6) {subquestion_content_id6--; subcontent_counter6--; if(subcontent_counter6 < 6){ $('#addQuestionContenButton'+numSubquestion).show(); }}
			if(numSubquestion == 7) {subquestion_content_id7--; subcontent_counter7--; if(subcontent_counter7 < 6){ $('#addQuestionContenButton'+numSubquestion).show(); }}
			if(numSubquestion == 8) {subquestion_content_id8--; subcontent_counter8--; if(subcontent_counter8 < 6){ $('#addQuestionContenButton'+numSubquestion).show(); }}
			if(numSubquestion == 9) {subquestion_content_id9--; subcontent_counter9--; if(subcontent_counter9 < 6){ $('#addQuestionContenButton'+numSubquestion).show(); }}
			if(numSubquestion == 10) {subquestion_content_id10--; subcontent_counter10--; if(subcontent_counter10 < 6){ $('#addQuestionContenButton'+numSubquestion).show(); }}
			
			$(this).parent().parent().parent().parent().remove();
		})

		$(document).on('focusin', '.math_text', function() {
			$('.panel_math_quill').fadeIn('fast');
			$('#mathTarget').val($(this).attr('id'));
		});
		
		$(document).on('paste', '#question_text', function(){
			let mathSpanId = $(this).attr('id');
			let spanTarget = document.getElementById(mathSpanId);
			let mathSpan = MQ(spanTarget);
			let latex = mathSpan.latex();
			latex = latex.replace(/ /g, '');  // quick fix to prevent bracket becoming left and right
			$(this).next().val(latex);
		});
		
		$('.math_text').each(function(index) {
			let span_id = $(this).attr('id');
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

		// filter school level base on subject type
		$('#subject_id').change(function(){
			var subject = $(this).val();

			$('#substrategy_id').empty(); 
			$('#substrategy_id2').empty(); 
			$('#substrategy_id3').empty(); 
			$('#substrategy_id4').empty(); 
			$('#strategy_id').empty();
			$('#strategy_id2').empty(); 
			$('#strategy_id3').empty(); 
			$('#strategy_id4').empty();  

			$('#substrategy_id').append('<option value="0">-</option>');
			$('#substrategy_id2').append('<option value="0">-</option>');
			$('#substrategy_id3').append('<option value="0">-</option>');
			$('#substrategy_id4').append('<option value="0">-</option>');
			$('#strategy_id').append('<option value="0">-</option>');
			$('#strategy_id2').append('<option value="0">-</option>');
			$('#strategy_id3').append('<option value="0">-</option>');
			$('#strategy_id4').append('<option value="0">-</option>');

			if(subject == 2) {
				
				$('#substrategy_id option').prop('disabled', false);
				$('#substrategy_id2 option').prop('disabled', false);
				$('#substrategy_id3 option').prop('disabled', false);
				$('#substrategy_id4 option').prop('disabled', false);
				$('#strategy_id option').prop('disabled', true);
				$('#strategy_id2 option').prop('disabled', true);
				$('#strategy_id3 option').prop('disabled', true);
				$('#strategy_id4 option').prop('disabled', true);

				// AJAX request for topic name according to subject change
				$.ajax({
					url:'<?=base_url()?>administrator/get_substrategy_list',
					method: 'post',
					data: {subject: subject},
					dataType: 'json',
					success: function(response){

						// Add options
						$.each(response,function(index,data){
							$('#substrategy_id').append('<option value="'+data['id']+'">'+data['name']+'</option>');
							$('#substrategy_id2').append('<option value="'+data['id']+'">'+data['name']+'</option>');
							$('#substrategy_id3').append('<option value="'+data['id']+'">'+data['name']+'</option>');
							$('#substrategy_id4').append('<option value="'+data['id']+'">'+data['name']+'</option>');
						});

						var maxLength = 54;
						$('#substrategy_id > option').text(function(i, text) {
						if (text.length > maxLength) {
							return text.substr(0, maxLength) + '...';
						}
						});

						$('#substrategy_id2 > option').text(function(i, text) {
							if (text.length > maxLength) {
								return text.substr(0, maxLength) + '...';
							}
						});

						$('#substrategy_id3 > option').text(function(i, text) {
							if (text.length > maxLength) {
								return text.substr(0, maxLength) + '...';
							}
						});

						$('#substrategy_id4 > option').text(function(i, text) {
							if (text.length > maxLength) {
								return text.substr(0, maxLength) + '...';
							}
						});
					}
				});
			}

			// AJAX request for subject level
			$.ajax({
				url:'<?=base_url()?>administrator/get_level_list',
				method: 'post',
				data: {subject: subject},
				dataType: 'json',
				success: function(response){
					// Remove options
					$('#level_id').empty(); 

					// Add options
					$.each(response,function(index,data){
						$('#level_id').append('<option value="'+data['level_id']+'">'+data['level_name']+'</option>');
					});
				}
			});

			// AJAX request for topic name according to subject change
			$.ajax({
				url:'<?=base_url()?>administrator/get_topic_list',
				method: 'post',
				data: {subject: subject},
				dataType: 'json',
				success: function(response){
					// Remove options
					$('#topic_id').empty(); 
					$('#topic_id2').empty(); 
					$('#topic_id3').empty(); 
					$('#topic_id4').empty(); 

					$('#topic_id').append('<option value="0">-</option>');
					$('#topic_id2').append('<option value="0">-</option>');
					$('#topic_id3').append('<option value="0">-</option>');
					$('#topic_id4').append('<option value="0">-</option>');

					// Add options
					$.each(response,function(index,data){
						$('#topic_id').append('<option value="'+data['id']+'">'+data['name']+'</option>');
						$('#topic_id2').append('<option value="'+data['id']+'">'+data['name']+'</option>');
						$('#topic_id3').append('<option value="'+data['id']+'">'+data['name']+'</option>');
						$('#topic_id4').append('<option value="'+data['id']+'">'+data['name']+'</option>');
					});
				}
			});
		});
    	// end of school level filter

		// filter school list base on level id
		$('#level_id').change(function(){
			var level = $(this).val();
			// AJAX request
			$.ajax({
				url:'<?=base_url()?>administrator/get_school_list',
				method: 'post',
				data: {level: level},
				dataType: 'json',
				success: function(response){
					// Remove options
					$('#school_id').empty(); 

					// Add options
					$.each(response,function(index,data){
						$('#school_id').append('<option value="'+data['school_id']+'">'+data['school_name']+'</option>');
					});
				}
			});
		});
		// end of school list filter
		
		var maxLength = 54;
		$('#substrategy_id > option').text(function(i, text) {
			if (text.length > maxLength) {
				return text.substr(0, maxLength) + '...';
			}
		});

		$('#substrategy_id2 > option').text(function(i, text) {
			if (text.length > maxLength) {
				return text.substr(0, maxLength) + '...';
			}
		});

		$('#substrategy_id3 > option').text(function(i, text) {
			if (text.length > maxLength) {
				return text.substr(0, maxLength) + '...';
			}
		});

		$('#substrategy_id4 > option').text(function(i, text) {
			if (text.length > maxLength) {
				return text.substr(0, maxLength) + '...';
			}
		});
	});
</script>
