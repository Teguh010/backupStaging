<link rel="stylesheet" href="<?=base_url()?>css/mathquill.min.css" />
<link rel="stylesheet" href="<?=base_url()?>css/matheditor.css" />

<script src="<?=base_url()?>js/mathquill.min.js"></script>
<script src="<?=base_url()?>js/matheditor.js"></script>
<script src="<?=base_url()?>js/fileinput.js"></script>

<style>
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

.btn-file {
  overflow: hidden;
  position: relative;
  vertical-align: middle;
}
.btn-file > input {
  position: absolute;
  top: 0;
  right: 0;
  margin: 0;
  opacity: 0;
  filter: alpha(opacity=0);
  transform: translate(-300px, 0) scale(4);
  -webkit-transform: translate(-300px, 0) scale(4);
  font-size: 23px;
  height: 100%;
  width: 100%;
  direction: ltr;
  cursor: pointer;
}
@-moz-document url-prefix() {
  .btn-file > input {
    transform: none;
  }
}

.fileinput {
  /* margin-bottom: 9px;
  display: inline-block; */
}
.fileinput .form-control {
  padding-top: 7px;
  padding-bottom: 5px;
  display: inline-block;
  margin-bottom: 0px;
  vertical-align: middle;
  cursor: text;
}
.fileinput .thumbnail {
  cursor: pointer;
  overflow: hidden;
  display: inline-block;
  margin-bottom: 5px;
  vertical-align: middle;
  text-align: center;
}
.fileinput .thumbnail > img {
  max-height: 100%;
}
.fileinput .btn {
  vertical-align: middle;
}
.fileinput-exists .fileinput-new,
.fileinput-new .fileinput-exists {
  display: none;
}
.fileinput-inline .fileinput-controls {
  display: inline;
}
.fileinput-filename {
  vertical-align: middle;
  display: inline-block;
  overflow: hidden;
}
.form-control .fileinput-filename {
  vertical-align: bottom;
}
.fileinput.input-group {
  display: table;
}
.fileinput-new.input-group .btn-file,
.fileinput-new .input-group .btn-file {
  border-radius: 0 1px 1px 0;
}
.fileinput-new.input-group .btn-file.btn-xs,
.fileinput-new .input-group .btn-file.btn-xs,
.fileinput-new.input-group .btn-file.btn-sm,
.fileinput-new .input-group .btn-file.btn-sm {
  border-radius: 0 1px 1px 0;
}
.fileinput-new.input-group .btn-file.btn-lg,
.fileinput-new .input-group .btn-file.btn-lg {
  border-radius: 0 1px 1px 0;
}
.form-group.has-warning .fileinput .fileinput-preview {
  color: #927608;
}
.form-group.has-warning .fileinput .thumbnail {
  border-color: #f7dc6f;
}
.form-group.has-error .fileinput .fileinput-preview {
  color: #a81515;
}
.form-group.has-error .fileinput .thumbnail {
  border-color: #f29797;
}
.form-group.has-success .fileinput .fileinput-preview {
  color: #527f26;
}
.form-group.has-success .fileinput .thumbnail {
  border-color: #b8df92;
}
.input-group-addon:not(:first-child) {
  border-left: 0;
}

</style>

<?php 

    function reverseApplyMathJaxFormat($text) {

		if (is_numeric($text)) {  // is answer ID

			return $text;

		} else {

			$text = str_replace('\\(', '', $text);

			$text = str_replace('\\)', '', $text);

			$text = str_replace(' ', '\\ ', $text);
			
			return $text;
		}

	}

?>

<div class="section">

	<div class="container">

		<!-- <?php

			if ($this->session->userdata('is_admin_logged_in') == 1) {

				echo '<a href="' . base_url() . 'administrator" class="btn btn-warning pull-right">Back to admin home page</a>';

			} elseif ($this->session->userdata('is_logged_in') == 1) {

				echo '<a href="' . base_url() . 'profile" class="btn btn-warning pull-right">Back to profile page</a>';

			}

		?>
 -->
		<div class="row">

			<div class="col-sm-12 col-md-12 col-lg-12">

				<?php
					switch ($question_detail->subject_type) {
						case "1":
							$question_subject = "primary-english";
							break;
						case "2":
							$question_subject = "primary-math";
							break;	
						case "3":
							$question_subject = "primary-science";
							break;
						case "4":
							$question_subject = "secondary-english";
							break;
						case "5":
							$question_subject = "secondary-math";
							break;
						case "6":
							$question_subject = "secondary-science";
							break;
						}
						
					if($question_detail->question_level == 1){
				?>
					<form class="form-horizontal" action="<?php echo base_url() ?>administrator/privateQuestion/<?php echo $question_subject; ?>/<?php echo $question_detail->question_counter; ?>" method="post" enctype="multipart/form-data" id="updateQuestionForm">
				<?php } else { ?>
					<form class="form-horizontal" action="<?php echo base_url() ?>administrator/question/<?php echo $question_subject; ?>/<?php echo $question_detail->question_counter; ?>" method="post" enctype="multipart/form-data" id="updateQuestionForm">
				<?php } ?>

				<input type="hidden" id="input_question_content" name="input_question_content" />
				
				<div class="question clearfix">

				<?php

					if($this->session->flashdata('update_success')) {

						$update_success = $this->session->flashdata('update_success');

					} else if($this->session->flashdata('update_error')) {

						$update_error = $this->session->flashdata('update_error');

					}


					if (isset($update_error)) {

					echo '<div class="alert alert-danger" id="admin_update_question_error">'. $update_error . '</div>';

					} else if (isset($update_success)) {

					echo '<div class="alert alert-success" id="admin_update_question_success">' . $update_success . '</div>';

					}

					if (isset($message_success) && $message_success == false) {

						echo '<div class="alert alert-danger" id="admin_update_question_error">'.$message.'</div>';

					} else if (isset($message_success) && $message_success == true) {

						echo '<div class="alert alert-success" id="admin_update_question_success">'.$message.'</div>';

					}

				?>

					<div class="page-header">

						<h3>
							Question 
							<?php
							
								if ($question_status === true && $question_detail->sub_question == 'A') {
									if ($question_detail->question_level == 1) {
										echo $question_parent->question_counter;
									} else {
										// echo $question_parent->question_id;
										echo $question_parent->question_counter;
									}
									
								} else {
									if ($question_detail->question_level == 1) {
										echo $question_parent->question_counter.$question_detail->sub_question;
									} else {
										// echo $question_parent->reference_id.$question_detail->sub_question;
										echo $question_parent->question_counter.$question_detail->sub_question;
									}
								}
							?>

							<div class="pull-right">
								<div class="btn-group">
									<?php if ($question_previous !== false && $question_detail->question_level == 1) { ?>
										<a href="<?php echo base_url().'administrator/privateQuestion/'.$question_subject.'/'.$question_previous->question_counter; ?>" class="btn btn-secondary" >&laquo;</a>
									<?php } ?>
									<?php if ($question_previous !== false && $question_detail->question_level == 0) { ?>
										<a href="<?php echo base_url().'administrator/question/'.$question_subject.'/'.$question_previous->question_counter; ?>" class="btn btn-secondary" >&laquo;</a>
									<?php } ?>
								</div>
								<div class="btn-group">
									<?php if ($question_next !== false && $question_detail->question_level == 1) { ?>
										<a href="<?php echo base_url().'administrator/privateQuestion/'.$question_subject.'/'.$question_next->question_counter; ?>" class="btn btn-secondary" >&raquo;</a>
									<?php } ?>
									<?php if ($question_next !== false && $question_detail->question_level == 0) { ?>
										<a href="<?php echo base_url().'administrator/question/'.$question_subject.'/'.$question_next->question_counter; ?>" class="btn btn-secondary" >&raquo;</a>
									<?php } ?>
								</div>
							</div>
						</h3>

					</div>
					<div class="form-group">
						<?php 
							if($question_detail->question_content == 0){

								if($question_detail->content_type == 'text'){
									echo $question_detail->question_text;
									echo '<br>';
								} else {
									echo '<div><img src="'.$question_detail->branch_image_url.'/questionImage/'.$question_detail->question_text.'" class="img-responsive"></div>';
								}

								if ($question_detail->graphical != "0") {
									echo '<div><img src="'.$question_detail->branch_image_url.'/questionImage/'.$question_detail->graphical.'" class="img-responsive"></div>';
								}
							} else {
								if($question_detail->content_type == 'text'){
									echo $question_detail->question_text;
									echo '<br>';
								} else {
									echo '<div><img src="'.$question_detail->branch_image_url.'/questionImage/'.$question_detail->question_text.'" class="img-responsive"></div>';
								}

								if(count($question_content) > 0){
									foreach($question_content as $row){
										echo '<br>';

										if($row->content_type == 'text'){
											echo '<div>'. $row->content_name . '</div>';
										} else {
											echo '<div><img src="'.$question_detail->branch_image_url.'/questionImage/'. $row->content_name.'" class="img-responsive"></div>';
										}
									}
								}
							}
								
						?>

						<?php 
							if (isset($answerOptions)) {
								
								if($answerOptions_isImage == 1){
									echo '<div class="row">';

									for ($i = 1, $l = count($answerOptions); $i <= $l; $i++) {
										
										if($answerOptions[$i-1]->answer_type == 'image'){
											echo '<div class="col-md-3 col-sm-6 col-xs-12">';

											if($correctAnswer == $answerOptions[$i-1]->answer_id){
												echo 'Option ' . $i .') <img class="mcq_img thumbnail mcq_img_correct_answer" src="'.$question_detail->branch_image_url.'/answerImage/'. $answerOptions[$i-1]->answer_text.'">';
											} else {
												echo 'Option ' . $i .') <img class="mcq_img thumbnail" src="'.$question_detail->branch_image_url.'/answerImage/'. $answerOptions[$i-1]->answer_text.'">';
											}
											
											echo '</div>';
										} else {
											echo 'Option ' . $i .') ' . $answerOptions[$i-1]->answer_text;

											if ($correctAnswer == $answerOptions[$i-1]->answer_id) {

												echo ' <i class="fa fa-check"></i>';
											}

											echo '<br>';
										}
									}

									echo '</div>';
								} else {
									for ($i = 1, $l = count($answerOptions); $i <= $l; $i++) {
										echo 'Option ' . $i .') ' . $answerOptions[$i-1]->answer_text;

										if ($correctAnswer == $answerOptions[$i-1]->answer_id) {

											echo ' <i class="fa fa-check"></i>';
										}

										echo '<br>';
									}
								}

							} else if (isset($open_ended_answer)) {
								if($open_ended_answer_type == 'text'){
									echo '<br>';
									echo '<div><b>Correct Answer:</b> '. $open_ended_answer . '</div>';
									echo '<br>';
								} else {
									echo '<br>';
									echo '<div><b>Correct Answer:</b><img src="'.$question_detail->branch_image_url.'/answerImage/'. $open_ended_answer.'" class="img-responsive thumbnail mcq_img_correct_answer"></div>';
									echo '<br>';
								}

								if($open_ended_working->working_type == 'text'){
									echo '<div><b>Solution:</b> '. $open_ended_working->working_text . '</div>';
								} else {
									echo '<div><b>Solution:</b><img src="'.$question_detail->branch_image_url.'/workingImage/'. $open_ended_working->working_text.'" class="img-responsive"></div>';
								}
								

								if (isset($open_ended_working_contents)) {
									foreach ($open_ended_working_contents as $open_ended_working_content) {
	
										echo '<br>';

										if($open_ended_working_content->content_type == 'text'){
											echo '<div>'. $open_ended_working_content->content_name . '</div>';
										} else {
											echo '<div><img src="'.$question_detail->branch_image_url.'/workingImage/'. $open_ended_working_content->content_name.'" class="img-responsive"></div>';
										}
									}
								}

							}

						?>

					</div>
					
					<hr>
					<h3>Edit question</h3>

					
					<div class="form-group">
						<label for="question_text" class="col-sm-4 col-md-4 col-lg-4 control-label">Question text</label>

						<div class="col-sm-6 col-md-6 col-lg-6">
							<div class="panel panel-default">
								<div class="panel-body">
									<div class="row question_content">
										<?php
											$question_content_id = 1;
											$content_counter = 1;
											if($question_detail->content_type == 'text'){
												echo '<div class="col-lg-12">
															<div class="p-1 border rounded">
																<div class="form-inline">
																	<div class="form-group" style="width: 100%;text-align: center;">
																		<span id="question_text0" style="height: 70px; width: 90%; padding: 0.5em;text-align: left;" class="form-control math_text">										
																		<textarea style="box-shadow:none!important">'.reverseApplyMathJaxFormat($question_detail->question_text).'</textarea>
																		</span>		
																		<input name="input_question_text[]" id="question_text_hidden0" type="hidden" class="input_question_content" data-type="text">																
																		<a style="cursor: pointer; text-decoration: none;"
																			class="removeQuestionContent text-danger-active fs18 ml-2" data-index="0" data-id="text"><i
																				class="fa fa-times"></i></a>
																	</div>                                                                                                     
																</div>
															</div>
														</div>';
											}else{
												echo '<div class="col-lg-12">
															<div class="p-1 border rounded">
																<div class="form-inline">
																	<div class="form-group" style="width: 100%;text-align: center;">
																		<div class="fileinput fileinput-exists" data-provides="fileinput">
																			<div class="fileinput-preview thumbnail" data-trigger="fileinput" style="height: auto; width: 90%; padding: 0.5em;text-align: left;" class="form-control">
																				<img src="'.$question_detail->branch_image_url.'/questionImage/'. $question_detail->question_text.'" class="img-responsive">
																			</div>

																			<a style="cursor: pointer; text-decoration: none;" class="removeQuestionContent text-danger-active fs18 ml-2" data-index="0" data-id="image">
																				<i class="fa fa-times"></i>
																			</a>   

																			<div>
																				<span class="btn btn-default btn-file" style="display:none">
																					<span class="fileinput-new">Select image</span>
																					<span class="fileinput-exists">Change</span>
																					<input type="file" class="form-control input_question_content" data-type="image" name="input_question_image[]">
																				</span>
																					
																				<a href="#" class="btn btn-default fileinput-exists" data-dismiss="fileinput" style="display:none">Remove</a>
																			</div>
																		</div>	
																	</div>                                                                                                       
																</div>
															</div>
														</div>';
											}

											if(count($question_content) > 0){
												foreach($question_content as $row){												

													if($row->content_type == 'text'){
														echo '<div class="col-lg-12">
																	<div class="p-1 border rounded">
																		<div class="form-inline">
																			<div class="form-group" style="width: 100%;text-align: center;">
																				<span id="question_text'.$question_content_id.'" style="height: 70px; width: 90%; padding: 0.5em;text-align: left;" class="form-control math_text math_text_'.$question_content_id.'">										
																				<textarea style="box-shadow:none!important">'.reverseApplyMathJaxFormat($row->content_name).'</textarea>
																				</span>		
																				<input name="input_question_text[]" id="question_text_hidden'.$question_content_id.'" type="hidden" class="input_question_content" data-type="text">																
																				<a style="cursor: pointer; text-decoration: none;"
																					class="removeQuestionContent text-danger-active fs18 ml-2" data-index="'.$question_content_id.'" data-id="text"><i
																						class="fa fa-times"></i></a>
																			</div>                                                                                                     
																		</div>
																	</div>
																</div>';
													}else{
														echo '<div class="col-lg-12">
																	<div class="p-1 border rounded">
																		<div class="form-inline">
																			<div class="form-group" style="width: 100%;text-align: center;">
																				<div class="fileinput fileinput-exists" data-provides="fileinput">
																					<div class="fileinput-preview thumbnail" data-trigger="fileinput" style="height: auto; width: 90%; padding: 0.5em;text-align: left;" class="form-control">
																						<img src="'.$question_detail->branch_image_url.'/questionImage/'. $row->content_name.'" class="img-responsive">
																					</div>

																					<a style="cursor: pointer; text-decoration: none;" class="removeQuestionContent text-danger-active fs18 ml-2" data-index="0" data-id="image">
																						<i class="fa fa-times"></i>
																					</a>   

																					<div>
																						<span class="btn btn-default btn-file" style="display:none">
																							<span class="fileinput-new">Select image</span>
																							<span class="fileinput-exists">Change</span>
																							<input type="file" class="form-control input_question_content" data-type="image" name="input_question_image[]">
																						</span>
																							
																						<a href="#" class="btn btn-default fileinput-exists" data-dismiss="fileinput" style="display:none">Remove</a>
																					</div>
																				</div>	   
																			</div>                                                                                                     
																		</div>
																	</div>
																</div>';
													}
	
													$question_content_id++;
													$content_counter++;
	
												}
											}
											
										
										?>
									</div>
										
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

					</div>

					<div class="form-group">

						<label for="subject_id" class="col-sm-4 col-md-4 col-lg-4 control-label">Subject</label>

							<div class="col-sm-6 col-md-6 col-lg-6">

							<select name="subject_id" id="subject_id" style="width: 100%;" class="form-control">

								<?php

									foreach ($subjects as $subject) {

										if ($question_detail->subject_type == $subject->id) {
											echo '<option value="'.$subject->id.'" selected="selected">'.$subject->name.'</option>';
										} else {
											echo '<option value="'.$subject->id.'">'.$subject->name.'</option>';
										}

									}

								?>

							</select>

						</div>

					</div>

					<div class="form-group">

						<label for="level_id" class="col-sm-4 col-md-4 col-lg-4 control-label">School Level</label>

						<div class="col-sm-6 col-md-6 col-lg-6">

							<select name="level_id" id="level_id" style="width: 100%;" class="form-control">

								<?php

									foreach ($levels as $level) {

										if ($question_detail->level_id == $level->level_id) {

											echo '<option value="'.$level->level_id.'" selected="selected">'.$level->level_name.'</option>';

										} else {

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

							<select name="school_id" id="school_id" style="width: 100%;" class="form-control">

								<?php

									foreach ($schools as $school) {

										if ($question_detail->school_id == $school->school_id) {

											echo '<option value="'.$school->school_id.'" selected="selected">'.$school->school_name.'</option>';

										} else {

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

							<select name="topic_id" id="topic_id" style="width: 100%;" class="form-control">

								<?php
									//echo '<option value="0" selected="selected"> - </option>';

									foreach ($categories as $category) {
										
										if ($question_detail->topic_id == $category->id) {

											echo '<option value="' . $category->id . '" selected="selected">' . $category->name . '</option>';

										} else {

											echo '<option value="' . $category->id . '">' . $category->name . '</option>';

										}

									}

								?>

							</select>

						</div>

					</div>

					<div class="form-group">

						<label for="topic_id2" class="col-sm-4 col-md-4 col-lg-4 control-label">Topic 2</label>

						<div class="col-sm-6 col-md-6 col-lg-6">

							<select name="topic_id2" id="topic_id2" style="width: 100%;" class="form-control">
								
								<?php
									echo '<option value="'. '0' .'" selected="selected">' .'-'. '</option>';

									foreach ($categories as $category) {

										if ($question_detail->topic_id2 == $category->id) {

											echo '<option value="' . $category->id . '" selected="selected">' . $category->name . '</option>';

										} else {

											echo '<option value="' . $category->id . '">' . $category->name . '</option>';

										}

									}

								?>

							</select>

						</div>

					</div>

					<div class="form-group">

						<label for="topic_id3" class="col-sm-4 col-md-4 col-lg-4 control-label">Topic 3</label>

						<div class="col-sm-6 col-md-6 col-lg-6">

							<select name="topic_id3" id="topic_id3" style="width: 100%;" class="form-control">

								<?php
									echo '<option value="'. '0' .'" selected="selected">' .'-'. '</option>';

									foreach ($categories as $category) {

										if ($question_detail->topic_id3 == $category->id) {

											echo '<option value="' . $category->id . '" selected="selected">' . $category->name . '</option>';

										} else {

											echo '<option value="' . $category->id . '">' . $category->name . '</option>';

										}

									}

								?>

							</select>

						</div>

					</div>

					<div class="form-group">

						<label for="topic_id4" class="col-sm-4 col-md-4 col-lg-4 control-label">Topic 4</label>

						<div class="col-sm-6 col-md-6 col-lg-6">

							<select name="topic_id4" id="topic_id4" style="width: 100%;" class="form-control">

								<?php
									echo '<option value="'. '0' .'" selected="selected">' .'-'. '</option>';

									foreach ($categories as $category) {

										if ($question_detail->topic_id4 == $category->id) {

											echo '<option value="' . $category->id . '" selected="selected">' . $category->name . '</option>';

										} else {

											echo '<option value="' . $category->id . '">' . $category->name . '</option>';

										}

									}

								?>

							</select>

						</div>

					</div>

					<div class="form-group">

						<label for="key_topic" class="col-sm-4 col-md-4 col-lg-4 control-label">Key Topic</label>

						<div class="col-sm-6 col-md-6 col-lg-6">

							<select name="key_topic" id="key_topic" style="width: 12.5%;" class="form-control">

								<?php
									if ($question_detail->key_topic == '0') {

										echo '<option value="0" selected="selected">0</option>';
										echo '<option value="1">1</option>';

									} else {

										echo '<option value="0">0</option>';
										echo '<option value="1" selected="selected">1</option>';

									}
								?>

							</select>

						</div>

					</div>

					<div class="form-group">

						<label for="key_strategy" class="col-sm-4 col-md-4 col-lg-4 control-label">Key Strategy</label>

						<div class="col-sm-6 col-md-6 col-lg-6">

							<select name="key_strategy" id="key_strategy" style="width: 12.5%;" class="form-control">
								
								<?php
									if ($question_detail->key_strategy == '0') {

										echo '<option value="0" selected="selected">0</option>';
										echo '<option value="1">1</option>';

									} else {

										echo '<option value="0">0</option>';
										echo '<option value="1" selected="selected">1</option>';

									}
								?>

							</select>

						</div>

					</div>


					<div class="form-group">

						<label for="substrategy_id" class="col-sm-4 col-md-4 col-lg-4 control-label">Sub Strategy 1</label>

						<div class="col-sm-6 col-md-6 col-lg-6">

							<select name="substrategy_id" id="substrategy_id" style="width: 100%;" class="form-control">

								<?php
									echo '<option value="'. '0' .'" selected="selected">' .'-'. '</option>';
									
									foreach ($substrategies as $substrategy) {

										if ($question_detail->substrategy_id == $substrategy->id) {

											echo '<option value="' . $substrategy->id . '" selected="selected" >' . $substrategy->name . '</option>';

										} else {
											if ($question_detail->subject_type != '2') {
												echo '<option value="' . $substrategy->id . '" disabled>' . $substrategy->name . '</option>';
											} else {
												echo '<option value="' . $substrategy->id . '">' . $substrategy->name . '</option>';
											}
										}

									}

								?>

							</select>

						</div>

					</div>

					<div class="form-group">

						<label for="substrategy_id2" class="col-sm-4 col-md-4 col-lg-4 control-label">Sub Strategy 2</label>

						<div class="col-sm-6 col-md-6 col-lg-6">

							<select name="substrategy_id2" id="substrategy_id2" style="width: 100%;" class="form-control"> 

								<?php
									echo '<option value="'. '0' .'" selected="selected">' .'-'. '</option>';
									
									foreach ($substrategies as $substrategy) {

										if ($question_detail->substrategy_id2 == $substrategy->id) {

											echo '<option value="' . $substrategy->id . '" selected="selected" >' . $substrategy->name . '</option>';

										} else {
											if ($question_detail->subject_type != '2') {
												echo '<option value="' . $substrategy->id . '" disabled>' . $substrategy->name . '</option>';
											} else {
												echo '<option value="' . $substrategy->id . '">' . $substrategy->name . '</option>';
											}
										}

									}

								?>

							</select>

						</div>

					</div>

					<div class="form-group">

						<label for="substrategy_id3" class="col-sm-4 col-md-4 col-lg-4 control-label">Sub Strategy 3</label>

						<div class="col-sm-6 col-md-6 col-lg-6">

							<select name="substrategy_id3" id="substrategy_id3" style="width: 100%;" class="form-control">

								<?php
									echo '<option value="'. '0' .'" selected="selected">' .'-'. '</option>';
									
									foreach ($substrategies as $substrategy) {

										if ($question_detail->substrategy_id3 == $substrategy->id) {

											echo '<option value="' . $substrategy->id . '" selected="selected" >' . $substrategy->name . '</option>';

										} else {
											if ($question_detail->subject_type != '2') {
												echo '<option value="' . $substrategy->id . '" disabled>' . $substrategy->name . '</option>';
											} else {
												echo '<option value="' . $substrategy->id . '">' . $substrategy->name . '</option>';
											}
										}

									}

								?>

							</select>

						</div>

					</div>

					<div class="form-group">

						<label for="substrategy_id4" class="col-sm-4 col-md-4 col-lg-4 control-label">Sub Strategy 4</label>

						<div class="col-sm-6 col-md-6 col-lg-6">

							<select name="substrategy_id4" id="substrategy_id4" style="width: 100%;" class="form-control">

								<?php
									echo '<option value="'. '0' .'" selected="selected">' .'-'. '</option>';
									
									foreach ($substrategies as $substrategy) {

										if ($question_detail->substrategy_id4 == $substrategy->id) {

											echo '<option value="' . $substrategy->id . '" selected="selected" >' . $substrategy->name . '</option>';

										} else {
											if ($question_detail->subject_type != '2') {
												echo '<option value="' . $substrategy->id . '" disabled>' . $substrategy->name . '</option>';
											} else {
												echo '<option value="' . $substrategy->id . '">' . $substrategy->name . '</option>';
											}
										}

									}

								?>

							</select>
							<p style="padding-top:1em;"><i>Strategy ID will be included by the system based on the Sub Strategy ID</i></p>

						</div>

					</div>

					<div class="form-group">

						<label for="strategy_id" class="col-sm-4 col-md-4 col-lg-4 control-label">Strategy 1</label>

						<div class="col-sm-4 col-md-4 col-lg-4">

							<select name="strategy_id" id="strategy_id" style="width: 100%;" class="form-control">

								<?php
									echo '<option value="'. '0' .'" selected="selected">' .'-'. '</option>';

									foreach ($strategies as $strategy) {

										if ($question_detail->strategy_id == $strategy->id) {

											echo '<option value="' . $strategy->id . '" selected="selected">' . $strategy->name . '</option>';

										} else {
											// disabled if primary math
											echo '<option value="' . $strategy->id . '" disabled>' . $strategy->name . '</option>';
										}
									}

								?>

							</select>

						</div>

					</div>

					<div class="form-group">

						<label for="strategy_id2" class="col-sm-4 col-md-4 col-lg-4 control-label">Strategy 2</label>

						<div class="col-sm-4 col-md-4 col-lg-4">

							<select name="strategy_id2" id="strategy_id2" style="width: 100%;" class="form-control">

								<?php
									echo '<option value="'. '0' .'" selected="selected">' .'-'. '</option>';

									foreach ($strategies as $strategy) {

										if ($question_detail->strategy_id2 == $strategy->id) {

											echo '<option value="' . $strategy->id . '" selected="selected">' . $strategy->name . '</option>';

										} else {
											echo '<option value="' . $strategy->id . '" disabled>' . $strategy->name . '</option>';
										}

									}

								?>

							</select>

						</div>

					</div>

					<div class="form-group">

						<label for="strategy_id3" class="col-sm-4 col-md-4 col-lg-4 control-label">Strategy 3</label>

						<div class="col-sm-4 col-md-4 col-lg-4">

							<select name="strategy_id3" id="strategy_id3" style="width: 100%;" class="form-control">

								<?php
									echo '<option value="'. '0' .'" selected="selected">' .'-'. '</option>';

									foreach ($strategies as $strategy) {

										if ($question_detail->strategy_id3 == $strategy->id) {

											echo '<option value="' . $strategy->id . '" selected="selected">' . $strategy->name . '</option>';

										} else {
											echo '<option value="' . $strategy->id . '" disabled>' . $strategy->name . '</option>';
										}

									}

								?>

							</select>

						</div>

					</div>

					<div class="form-group">

						<label for="strategy_id4" class="col-sm-4 col-md-4 col-lg-4 control-label">Strategy 4</label>

						<div class="col-sm-4 col-md-4 col-lg-4">

							<select name="strategy_id4" id="strategy_id4" style="width: 100%;" class="form-control">

								<?php
									echo '<option value="'. '0' .'" selected="selected">' .'-'. '</option>';

									foreach ($strategies as $strategy) {

										if ($question_detail->strategy_id4 == $strategy->id) {

											echo '<option value="' . $strategy->id . '" selected="selected">' . $strategy->name . '</option>';

										} else {
											echo '<option value="' . $strategy->id . '" disabled>' . $strategy->name . '</option>';
										}

									}

								?>

							</select>

						</div>

					</div>
					
					<div class="form-group">

						<label for="casa" class="col-sm-4 col-md-4 col-lg-4 control-label">Type/Tags/Label (Max 5)</label>
						
						<div class="col-sm-6 col-md-6 col-lg-6">

							<input name="tagsinput" class="tagsinput" data-role="tagsinput" placeholder="eg: CA1, Mock Exam 2" value="<?=$question_tags?>" style="display: none; width: 50%;">

						</div>

					</div>

					<div class="form-group">

						<label for="year" class="col-sm-4 col-md-4 col-lg-4 control-label">Year</label>

						<div class="col-sm-6 col-md-6 col-lg-6">

							<input style="width: 25%;" name="year" id="year" type="number" class="form-control" value="<?=$question_detail->year?>">

						</div>

					</div>


					<div class="form-group">

						<label for="difficulty" class="col-sm-4 col-md-4 col-lg-4 control-label">Difficulty</label>

						<div class="col-sm-6 col-md-6 col-lg-6">

							<select name="difficulty" id="difficulty" style="width: 12.5%;" class="form-control">

								<?php

									for ($i = 1; $i <= 5; $i++) {

										if ($question_detail->difficulty == $i) {

											echo '<option value="' . $i . '" selected="selected">' . $i . '</option>';

										} else {

											echo '<option value="' . $i . '">' . $i . '</option>';

										}	

									}

								?>

							</select>

						</div>

					</div>

					
					<div class="form-group">

						<label for="question_type_id" class="col-sm-4 col-md-4 col-lg-4 control-label">Question Type</label>

						<div class="col-sm-6 col-md-6 col-lg-6">

							<select name="question_type_id" class="question_type_ids form-control" style="width: 100%;">

							<?php
								// echo '<option value="'. '0' .'" selected="selected">' .'-'. '</option>';

								$qtidArray = array(3);

								foreach ($question_types as $question_type) {

									if(in_array($question_type->question_type_id, $qtidArray)) {

										echo '<option value="' . $question_type->question_type_id . '" disabled>' . $question_type->question_type . '</option>';

									} else if ($question_detail->question_type_id == $question_type->question_type_id) {

										echo '<option value="' . $question_type->question_type_id . '" selected="selected">' . $question_type->question_type . '</option>';

									} else {
										// disabled if primary math
										echo '<option value="' . $question_type->question_type_id . '">' . $question_type->question_type . '</option>';
									}
								}

							?>

							</select>

						</div>

					</div>

					<div class="form-group" id="answerTypes">
						<label for="answer_type_id" class="col-sm-4 col-md-4 col-lg-4 control-label">Answer Type</label>
							<div class="col-sm-6 col-md-6 col-lg-6">
								<select name="answer_type_id" id="answer_type_id" class="form-control">

								<?php

									foreach($answer_types as $answer_type) {
										if ($question_detail->answer_type_id == $answer_type->answer_type_id) {

											echo '<option value="' . $answer_type->answer_type_id . '" selected="selected">' . $answer_type->answer_type . '</option>';

										} else {
											
											echo '<option value="' . $answer_type->answer_type_id . '">' . $answer_type->answer_type . '</option>';
										}
									}

								?>

								</select>
							</div>
					</div>

                    

					<?php

						if ($question_detail->question_type_id == 1 || $question_detail->question_type_id == 4) {

					?>

						<div id="mcq_input_answers_divs">

							<?php
								if(isset($answerOptions_isImage)){
									if($answerOptions_isImage == 0){
										$checkedText = 'checked';
										$checkedImage = '';
										$displayText = '';
										$displayImage = 'display: none;';
									}else{
										$checkedText = '';
										$checkedImage = 'checked';
										$displayText = 'display: none;';
										$displayImage = '';
									}
								}else{
									$checkedText = '';
									$checkedImage = 'checked';
									$displayText = 'display: none;';
									$displayImage = '';
								}
							?>
							<div class="form-group">
								<label for="answer_type_mcq" class="col-sm-4 col-md-4 col-lg-4 control-label"></label>
								<div class="col-sm-6 col-md-6 col-lg-6">
									<div class="col-sm-3 col-md-3 col-lg-2">
										<label class="radio-inline"><input type="radio" class="answer_type_mcq" data-id="-1" name="answer_type_mcq" value="text" <?= $checkedText ?>>Text</label>
									</div>
									<div class="col-sm-3 col-md-3 col-lg-2">
										<label class="radio-inline"><input type="radio" class="answer_type_mcq" data-id="-1" name="answer_type_mcq" value="image" <?= $checkedImage ?>>Image</label>
									</div>								
								</div>
							</div>

							<div class="form-group">

								<label for="question_answers" class="col-sm-4 col-md-4 col-lg-4 control-label">Answer 1</label>

								<div class="col-sm-5 col-md-5 col-lg-5 input_answer_text" style="<?= $displayText ?>">

									<span id="mcq_ans_1" style="width: 100%; padding: 0.5em" class="math_text"><?=isset($answerOptions) && empty($answerOptions) === false?reverseApplyMathJaxFormat($answerOptions[0]->answer_text):''?></span>

									<input type="hidden" name="mcq_answers[]">

								</div>

								<div class="col-sm-5 col-md-5 col-lg-5 input_answer_image" style="<?= $displayImage ?>">									
									<input type="file" class="form-control" name="mcq_answers_image[]" style="padding-top: 5px;"/>
								</div>

								<div class="col-sm-3 col-md-3 col-lg-3">

									<label>

										<input type="radio" name="mcq_correct_answer" value="1" <?=(empty($answerOptions) === false && $answerOptions[0]->answer_id == $correctAnswer)?'checked':''?>>

										Select for correct ans

									</label>

								</div>

							</div>


							<div class="form-group">

								<label for="question_answers" class="col-sm-4 col-md-4 col-lg-4 control-label">Answer 2</label>

								<div class="col-sm-5 col-md-5 col-lg-5 input_answer_text" style="<?= $displayText ?>">

									<span id="mcq_ans_2" style="width: 100%; padding: 0.5em" class="math_text">

										<?=isset($answerOptions) && empty($answerOptions) === false?reverseApplyMathJaxFormat($answerOptions[1]->answer_text):''?>

									</span>

									<input type="hidden" name="mcq_answers[]">

								</div>

								<div class="col-sm-5 col-md-5 col-lg-5 input_answer_image" style="<?= $displayImage ?>">									
									<input type="file" class="form-control" name="mcq_answers_image[]" style="padding-top: 5px;"/>
								</div>

								<div class="col-sm-3 col-md-3 col-lg-3">

									<label>

										<input type="radio" name="mcq_correct_answer" value="2" <?=(empty($answerOptions) === false && $answerOptions[1]->answer_id == $correctAnswer)?'checked':''?>>

										Select for correct ans

									</label>

								</div>

							</div>



							<div class="form-group">

								<label for="question_answers" class="col-sm-4 col-md-4 col-lg-4 control-label">Answer 3</label>

								<div class="col-sm-5 col-md-5 col-lg-5 input_answer_text" style="<?= $displayText ?>">

									<span id="mcq_ans_3" style="width: 100%; padding: 0.5em" class="math_text">

										<?=isset($answerOptions) && empty($answerOptions) === false?reverseApplyMathJaxFormat($answerOptions[2]->answer_text):''?>

									</span>

									<input type="hidden" name="mcq_answers[]">

								</div>

								<div class="col-sm-5 col-md-5 col-lg-5 input_answer_image" style="<?= $displayImage ?>">									
									<input type="file" class="form-control" name="mcq_answers_image[]" style="padding-top: 5px;"/>
								</div>

								<div class="col-sm-3 col-md-3 col-lg-3">

									<label>

										<input type="radio" name="mcq_correct_answer" value="3" <?=(empty($answerOptions) === false && $answerOptions[2]->answer_id == $correctAnswer)?'checked':''?>>

										Select for correct ans

									</label>

								</div>

							</div>



							<div class="form-group">

								<label for="question_answers" class="col-sm-4 col-md-4 col-lg-4 control-label">Answer 4</label>

								<div class="col-sm-5 col-md-5 col-lg-5 input_answer_text" style="<?= $displayText ?>">

									<span id="mcq_ans_4" style="width: 100%; padding: 0.5em" class="math_text">

										<?=isset($answerOptions) && empty($answerOptions) === false?reverseApplyMathJaxFormat($answerOptions[3]->answer_text):''?>

									</span>

									<input type="hidden" name="mcq_answers[]">

								</div>

								<div class="col-sm-5 col-md-5 col-lg-5 input_answer_image" style="<?= $displayImage ?>">									
									<input type="file" class="form-control" name="mcq_answers_image[]" style="padding-top: 5px;"/>
								</div>

								<div class="col-sm-3 col-md-3 col-lg-3">

									<label>

										<input type="radio" name="mcq_correct_answer" value="4" <?=(empty($answerOptions) === false && $answerOptions[3]->answer_id == $correctAnswer)?'checked':''?>>

										Select for correct ans

									</label>

								</div>

							</div>

						</div>

						<div id="open_ended_input_answers_divs">

							<?php
								if(isset($open_ended_answer_type)){
									if($open_ended_answer_type == 'text'){
										$checkedText = 'checked';
										$checkedImage = '';
										$displayText = '';
										$displayImage = 'display: none;';
									}else{
										$checkedText = '';
										$checkedImage = 'checked';
										$displayText = 'display: none;';
										$displayImage = '';
									}
								} else {
									$checkedText = '';
									$checkedImage = 'checked';
									$displayText = 'display: none;';
									$displayImage = '';
								}
							?>

							<div class="form-group">
								<label for="answer_type_nmcq" class="col-sm-4 col-md-4 col-lg-4 control-label"></label>
								<div class="col-sm-6 col-md-6 col-lg-6">
									<div class="col-sm-3 col-md-3 col-lg-2">
										<label class="radio-inline"><input type="radio" class="answer_type_nmcq" data-id="-1" name="answer_type_nmcq" value="text" <?= $checkedText ?>>Text</label>
									</div>
									<div class="col-sm-3 col-md-3 col-lg-2">
										<label class="radio-inline"><input type="radio" class="answer_type_nmcq" data-id="-1" name="answer_type_nmcq" value="image" <?= $checkedImage ?>>Image</label>
									</div>								
								</div>
							</div>

							<div class="form-group">

								<label for="question_answers" class="col-sm-4 col-md-4 col-lg-4 control-label">Answer</label>

								<div class="col-sm-6 col-md-6 col-lg-6 input_answer_text" style="<?= $displayText ?>">

									<span id="open_ended_answer" style="width: 100%; padding: 0.5em" class="math_text">

									<?=isset($answerOptions) && empty($answerOptions) === false?reverseApplyMathJaxFormat($answerOptions[0]->answer_text):''?>

									</span>

									<input type="hidden" name="open_ended_answer" class="form-control">

								</div>

								<div class="col-sm-6 col-md-6 col-lg-6 input_answer_image" style="<?= $displayImage ?>">									
									<input type="file" class="form-control" name="nmcq_answers_image[]" style="padding-top: 5px;"/>
								</div>

							</div>

						</div>

					<?php

						// } else if ($question_detail->question_type_id == 2) {
						} else {
					?>

					<div id="mcq_input_answers_divs">

							<?php
								if(isset($answerOptions_isImage)){
									if($answerOptions_isImage == 0){
										$checkedText = 'checked';
										$checkedImage = '';
										$displayText = '';
										$displayImage = 'display: none;';
									}else{
										$checkedText = '';
										$checkedImage = 'checked';
										$displayText = 'display: none;';
										$displayImage = '';
									}
								}else{
									$checkedText = '';
									$checkedImage = 'checked';
									$displayText = 'display: none;';
									$displayImage = '';
								}
							?>
							
						<div class="form-group">
							<label for="answer_type_mcq" class="col-sm-4 col-md-4 col-lg-4 control-label"></label>
							<div class="col-sm-6 col-md-6 col-lg-6">
								<div class="col-sm-3 col-md-3 col-lg-2">
									<label class="radio-inline"><input type="radio" class="answer_type_mcq" data-id="-1" name="answer_type_mcq" value="text" <?= $checkedText ?>>Text</label>
								</div>
								<div class="col-sm-3 col-md-3 col-lg-2">
									<label class="radio-inline"><input type="radio" class="answer_type_mcq" data-id="-1" name="answer_type_mcq" value="image" <?= $checkedImage ?>>Image</label>
								</div>								
							</div>
						</div>
							
						<div class="form-group">

							<label for="question_answers" class="col-sm-4 col-md-4 col-lg-4 control-label">Answer 1</label>

							<div class="col-sm-5 col-md-5 col-lg-5 input_answer_text" style="<?= $displayText ?>">

								<span id="mcq_ans_1" style="width: 100%; padding: 0.5em" class="math_text">
								
									<?=isset($open_ended_answer)?reverseApplyMathJaxFormat($open_ended_answer):''?>
								
								</span>

								<input type="hidden" name="mcq_answers[]">

							</div>

							<div class="col-sm-5 col-md-5 col-lg-5 input_answer_image" style="<?= $displayImage ?>">									
								<input type="file" class="form-control" name="mcq_answers_image[]" style="padding-top: 5px;"/>
							</div>

							<div class="col-sm-3 col-md-3 col-lg-3">

								<label>

									<input type="radio" name="mcq_correct_answer" value="1" checked>

									Select for correct ans

								</label>

							</div>

						</div>


						<div class="form-group">

							<label for="question_answers" class="col-sm-4 col-md-4 col-lg-4 control-label">Answer 2</label>

							<div class="col-sm-5 col-md-5 col-lg-5 input_answer_text" style="<?= $displayText ?>">

								<span id="mcq_ans_2" style="width: 100%; padding: 0.5em" class="math_text">

									<!-- <?=isset($answerOptions) && empty($answerOptions) === false?reverseApplyMathJaxFormat($answerOptions[1]->answer_text):''?> -->

								</span>

								<input type="hidden" name="mcq_answers[]">

							</div>

							<div class="col-sm-5 col-md-5 col-lg-5 input_answer_image" style="<?= $displayImage ?>">									
								<input type="file" class="form-control" name="mcq_answers_image[]" style="padding-top: 5px;"/>
							</div>

							<div class="col-sm-3 col-md-3 col-lg-3">

								<label>

									<input type="radio" name="mcq_correct_answer" value="2" <?=(empty($answerOptions) === false && $answerOptions[1]->answer_id == $correctAnswer)?'checked':''?>>

									Select for correct ans

								</label>

							</div>

						</div>



						<div class="form-group">

							<label for="question_answers" class="col-sm-4 col-md-4 col-lg-4 control-label">Answer 3</label>

							<div class="col-sm-5 col-md-5 col-lg-5 input_answer_text" style="<?= $displayText ?>">

								<span id="mcq_ans_3" style="width: 100%; padding: 0.5em" class="math_text">

									<!-- <?=isset($answerOptions) && empty($answerOptions) === false?reverseApplyMathJaxFormat($answerOptions[2]->answer_text):''?> -->

								</span>

								<input type="hidden" name="mcq_answers[]">

							</div>

							<div class="col-sm-5 col-md-5 col-lg-5 input_answer_image" style="<?= $displayImage ?>">									
								<input type="file" class="form-control" name="mcq_answers_image[]" style="padding-top: 5px;"/>
							</div>

							<div class="col-sm-3 col-md-3 col-lg-3">

								<label>

									<input type="radio" name="mcq_correct_answer" value="3" <?=(empty($answerOptions) === false && $answerOptions[2]->answer_id == $correctAnswer)?'checked':''?>>

									Select for correct ans

								</label>

							</div>

						</div>



						<div class="form-group">

							<label for="question_answers" class="col-sm-4 col-md-4 col-lg-4 control-label">Answer 4</label>

							<div class="col-sm-5 col-md-5 col-lg-5 input_answer_text" style="<?= $displayText ?>">

								<span id="mcq_ans_4" style="width: 100%; padding: 0.5em" class="math_text">

									<!-- <?=isset($answerOptions) && empty($answerOptions) === false?reverseApplyMathJaxFormat($answerOptions[3]->answer_text):''?> -->

								</span>

								<input type="hidden" name="mcq_answers[]">

							</div>

							<div class="col-sm-5 col-md-5 col-lg-5 input_answer_image" style="<?= $displayImage ?>">									
								<input type="file" class="form-control" name="mcq_answers_image[]" style="padding-top: 5px;"/>
							</div>

							<div class="col-sm-3 col-md-3 col-lg-3">

								<label>

									<input type="radio" name="mcq_correct_answer" value="4" <?=(empty($answerOptions) === false && $answerOptions[3]->answer_id == $correctAnswer)?'checked':''?>>

									Select for correct ans

								</label>

							</div>

						</div>

					</div>


					<div id="open_ended_input_answers_divs">

						<?php
							if(isset($open_ended_answer_type)){
								if($open_ended_answer_type == 'text'){
									$checkedText = 'checked';
									$checkedImage = '';
									$displayText = '';
									$displayImage = 'display: none;';
								}else{
									$checkedText = '';
									$checkedImage = 'checked';
									$displayText = 'display: none;';
									$displayImage = '';
								}
							} else {
								$checkedText = '';
								$checkedImage = 'checked';
								$displayText = 'display: none;';
								$displayImage = '';
							}
						?>

						<div class="form-group">
							<label for="answer_type_nmcq" class="col-sm-4 col-md-4 col-lg-4 control-label"></label>
							<div class="col-sm-6 col-md-6 col-lg-6">
								<div class="col-sm-3 col-md-3 col-lg-2">
									<label class="radio-inline"><input type="radio" class="answer_type_nmcq" data-id="-1" name="answer_type_nmcq" value="text" <?= $checkedText ?>>Text</label>
								</div>
								<div class="col-sm-3 col-md-3 col-lg-2">
									<label class="radio-inline"><input type="radio" class="answer_type_nmcq" data-id="-1" name="answer_type_nmcq" value="image" <?= $checkedImage ?>>Image</label>
								</div>								
							</div>
						</div>

						<div class="form-group">

							<label for="question_answers" class="col-sm-4 col-md-4 col-lg-4 control-label">Answer</label>

							<div class="col-sm-6 col-md-6 col-lg-6 input_answer_text" style="<?= $displayText ?>">

								<span id="open_ended_answer" style="width: 100%; padding: 0.5em" class="math_text">

									<?=isset($open_ended_answer)?reverseApplyMathJaxFormat($open_ended_answer):''?>

								</span>

								<input type="hidden" name="open_ended_answer" class="form-control">

							</div>

							<div class="col-sm-6 col-md-6 col-lg-6 input_answer_image" style="<?= $displayImage ?>">									
								<input type="file" class="form-control" name="nmcq_answers_image[]" style="padding-top: 5px;"/>
							</div>

						</div>
					</div>


					<?php

						}

					?>					

					<div class="form-group">
						<label for="working" class="col-sm-4 col-md-4 col-lg-4 control-label">Working</label>

						<div class="col-sm-6 col-md-6 col-lg-6">
							<div class="panel panel-default">
								<div class="panel-body">
								<div class="row working_content">
									<?php if($question_detail->question_type_id == 2){ 
										$working_content_id = 0;
										$working_counter = 0;
									?>
										<?php if($open_ended_working->working_text != '' && $open_ended_working->working_type=='text') { ?>
											<div class="col-lg-12">
												<div class="p-1 border rounded">
													<div class="form-inline">
														<div class="form-group" style="width: 100%;text-align: center;">
															<span id="working_field_0" style="min-height: 70px; width: 90%; padding: 0.5em;text-align: left;" class="math_text">
																<textarea style="box-shadow:none!important">
																	<?=isset($open_ended_working)?reverseApplyMathJaxFormat($open_ended_working->working_text):''?>
																</textarea>								
															</span>		
															<input name="working_field_0" id="working_field_hidden1" type="hidden">																
															<a style="cursor: pointer; text-decoration: none;"
																class="removeQuestionContent text-danger-active fs18 ml-2" data-id="text"><i
																	class="fa fa-times"></i></a>
														</div>                                                                                                     
													</div>
												</div>
											</div>
										<?php 
											$working_content_id = 1;
											$working_counter = 1;
											}
										?>

										<?php if($open_ended_working->working_text != '' && $open_ended_working->working_type=='image') { ?>
											<div class="col-lg-12">
												<div class="p-1 border rounded">
													<div class="form-inline"> 
														<div class="form-group" style="width: 100%;text-align: center;">
															<div class="fileinput fileinput-exists" data-provides="fileinput">
																<div class="fileinput-preview thumbnail" data-trigger="fileinput" style="height: auto; width: 90%; padding: 0.5em;text-align: left;" class="form-control">
																	<img src="<?php echo $question_detail->branch_image_url.'/workingImage/'. $open_ended_working->working_text; ?>" class="img-responsive">
																</div>

																<a style="cursor: pointer; text-decoration: none;" class="removeWorkingContent text-danger-active fs18 ml-2" data-index="0" data-id="image">
																	<i class="fa fa-times"></i>
																</a>   

																<div>
																	<span class="btn btn-default btn-file" style="display:none">
																		<span class="fileinput-new">Select image</span>
																		<span class="fileinput-exists">Change</span>
																		<input type="file" class="form-control input_working_content" data-type="image" name="input_working_image[]">
																	</span>
																		
																	<a href="#" class="btn btn-default fileinput-exists" data-dismiss="fileinput" style="display:none">Remove</a>
																</div>
															</div>	
														</div>                                                                                                 
													</div>
												</div>
											</div>
										<?php 
											$working_content_id = 1;
											$working_counter = 1;
											}
										?>

										<?php 
											if(isset($open_ended_working_contents) && count($open_ended_working_contents) > 0){
												foreach ($open_ended_working_contents as $open_ended_working_content) {
													$count = $open_ended_working_content->content_order - 1;

													if($open_ended_working_content->content_type == 'text'){
														echo '<div class="col-lg-12">
																<div class="p-1 border rounded">
																	<div class="form-inline">
																		<div class="form-group" style="width: 100%;text-align: center;">
																			<span id="working_field_'.$count.'" style="min-height: 70px; width: 90%; padding: 0.5em;text-align: left;" class="math_text">
																				<textarea style="box-shadow:none!important">
																					'. reverseApplyMathJaxFormat($open_ended_working_content->content_name).'
																				</textarea>								
																			</span>		
																			<input name="working_field_'.$count.'" id="working_field_hidden'.$open_ended_working_content->content_order.'" type="hidden">																
																			<a style="cursor: pointer; text-decoration: none;"
																				class="removeQuestionContent text-danger-active fs18 ml-2" data-id="text"><i
																					class="fa fa-times"></i></a>
																		</div>                                                                                                     
																	</div>
																</div>
															</div>';
														$working_content_id++;
														$working_counter++;
													} else {
														echo '<div class="col-lg-12">
																<div class="p-1 border rounded">
																	<div class="form-inline">
																		<div class="form-group" style="width: 100%;text-align: center;">
																			<div class="fileinput fileinput-exists" data-provides="fileinput">
																				<div class="fileinput-preview thumbnail" data-trigger="fileinput" style="height: auto; width: 90%; padding: 0.5em;text-align: left;" class="form-control">
																					<img src="'.$question_detail->branch_image_url.'/workingImage/'. $open_ended_working_content->content_name.'" class="img-responsive">
																				</div>
				
																				<a style="cursor: pointer; text-decoration: none;" class="removeWorkingContent text-danger-active fs18 ml-2" data-index="0" data-id="image">
																					<i class="fa fa-times"></i>
																				</a>   
				
																				<div>
																					<span class="btn btn-default btn-file" style="display:none">
																						<span class="fileinput-new">Select image</span>
																						<span class="fileinput-exists">Change</span>
																						<input type="file" class="form-control input_working_content" data-type="image" name="input_working_image[]">
																					</span>
																						
																					<a href="#" class="btn btn-default fileinput-exists" data-dismiss="fileinput" style="display:none">Remove</a>
																				</div>
																			</div>	
																		</div>                                                                                                      
																	</div>
																</div>
															</div>';
														$working_content_id++;
														$working_counter++;
													}
												}
											}												

											// set index counter for working field
											// if($open_ended_working->working_text == '' || $open_ended_working->working_text == ' '){
											// 	echo '<input name="working_index" id="working_index" type="hidden" value="1">';
											// } else if (isset($open_ended_working_contents)) {
											// 	echo '<input name="working_index" id="working_index" type="hidden" value="'. $working_index_count.'">';
											// } else {
											// 	echo '<input name="working_index" id="working_index" type="hidden" value="1">';
											// }
											
										?>
									<?php } else {
										//mcq
										$working_content_id = 0;
										$working_counter = 0;
									} ?>
									

								</div>
								
								<div class="dropdown" id="addWorkingContentButton">
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


					<!-- <div class="form-group">  
						<form name="add_name" id="add_name">  
							<div class="table-responsive">  
								<table class="table table-bordered" id="dynamic_field">  
										<tr>  
											<td><input type="text" name="name[]" placeholder="Enter your Name" class="form-control name_list" /></td>  
											<td><button type="button" name="add" id="add" class="btn btn-success">Add More</button></td>  
										</tr>  
								</table>  
								<input type="button" name="submit" id="submit" class="btn btn-info" value="Submit" />  
							</div>  
						</form>  
                	</div>  -->
					
					<div class="form-group">
						<label for="" class="col-sm-4 col-md-4 col-lg-4 control-label">Disable Question</label>
						<div class="col-sm-6 col-md-6 col-lg-6">
						<?php
							if ($question_detail->disabled == 0){
								echo '<input type="checkbox" name="disabled" class="disable">';
							}else {
								echo '<input type="checkbox" name="disabled" class="disable" checked>';
							}
						?>
							<!-- <input type="checkbox" name="disabled" id="disabled" class="disable" value="1"> -->
						</div>
					</div>
				</div>

				<input type="hidden" name="subQuestionNumber" id="subQuestionNumber" value="0">
				<input name="subject_type" type="hidden" value="<?php echo $question_detail->subject_type; ?>">

				<?php
					if ($question_detail->graphical != 0){
						echo '<div class="col-sm-offset-2 col-sm-4 col-md-offset-2 col-md-4 col-lg-offset-2 col-lg-4" id="smartjen_delete_image">';
						echo '<a href="#" class="btn btn-danger btn-block" data-toggle="modal" data-target="#deleteImageModal">Delete Question Image</a>';
						echo '</div>';
						echo '<div class="col-sm-4 col-md-4 col-lg-4">';
						echo '<input type="submit" class="btn btn-custom btn-block" value="Update">';
						echo '</div>';
					} else {
						echo '<div class="col-sm-offset-4 col-sm-4 col-md-offset-4 col-md-4 col-lg-offset-4 col-lg-4">';
						echo '<input type="submit" class="btn btn-custom btn-block" value="Update">';
						echo '</div>';
					}
				?>
			</div>

			</form>


			</div>

		</div>

	</div>

</div>


<style>
    .panel_math_quill{
        display: block;
        text-align: center;
        font-size: 15px;
        font-family: calibri;
        border-radius: 50%;
        -webkit-border-radius: 50%;
		z-index: 2400;
        text-decoration: none;
        transition: ease all 0.3s;
		position:fixed;
		width:100%;
		height:300px;
		bottom:20px;
		right:25%;
		left:50%;
		margin-left:-300px;
    }
</style>

<div class="panel_math_quill" style="display: none;">
	<div class="card-columns">
		<div class="card" style="width: 700px;">
			<div class="card-header-small">
				<h5 class="card-title fs14">
					Math Expression
				</h5>	
				
				<a class="close_panel icon_close fs14">
                    <i class="fa fa-times"></i>
                </a>
			</div>
			<div class="card-body-small">
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


<div class="modal fade" id="deleteImageModal" role="dialog">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header modal-header-custom-alert">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title">Delete Question Image</h4>
			</div>
			<form class="form-horizontal" action="<?php echo base_url(); ?>site/feedback" method="post" accept-charset="utf-8">
				<div class="modal-body">
					<div id="submit_delete_error" class="alert alert-danger">
						
					</div>
					<div id="submit_delete_success" class="alert alert-success">
						
					</div>
					<p>Are you sure to delete this question image?</p>
				</div>
				<div class="modal-footer">
					<input type="button" class="btn btn-default" data-dismiss="modal" value="Close">
					<button type="button" class="btn btn-danger" data-loading-text="<i class='fa fa-spinner fa-spin'></i> Deleting Image" id="submit_delete_btn">Delete</button>
				</div>
			</form>
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

	var question_content_id = <?= $question_content_id ?>;
	var content_counter = <?= $content_counter ?>;

	function addQuestionContent(type){
		
		if(type == 'text'){
			
			content = `<div class="col-lg-12">
							<div class="p-1 border rounded">
								<div class="form-inline">
									<div class="form-group" style="width: 100%;text-align: center;">
										<span id="question_text`+question_content_id+`" style="height: 70px; width: 90%; padding: 0.5em;text-align: left;" class="form-control math_text math_text_`+question_content_id+`">										
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
									<div class="fileinput fileinput-new" data-provides="fileinput">							
										<div class="fileinput-preview thumbnail" data-trigger="fileinput" style="height: auto; width: 90%; padding: 0.5em;text-align: left;" class="form-control">
										<img src="https://smartjen.com/img/upload_file.png" class="img-responsive">
										</div>

										<a style="cursor: pointer; text-decoration: none;" class="removeQuestionContent text-danger-active fs18 ml-2" data-index="0" data-id="image">
											<i class="fa fa-times"></i>
										</a>   

										<div>
											<span class="btn btn-default btn-file" style="display:none">
												<span class="fileinput-new">Select image</span>
												<span class="fileinput-exists">Change</span>
												<input type="file" class="input_question_content" data-type="image" name="input_question_image[]">
											</span>
												
											<a href="#" class="btn btn-default fileinput-exists" data-dismiss="fileinput" style="display:none">Remove</a>
										</div>									
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

	var working_content_id = <?= $working_content_id ?>;
	var working_counter = <?= $working_counter ?>;

	function addWorkingContent(type){

		if(type == 'text'){
			working_content_id++;
			content = `<div class="col-lg-12">
							<div class="p-1 border rounded">
								<div class="form-inline">
									<div class="form-group" style="width: 100%;text-align: center;">
										<span id="working_field_`+working_counter+`" style="min-height: 70px; width: 90%; padding: 0.5em;text-align: left;" class="math_text">
										<textarea style="box-shadow:none!important"></textarea>								
										</span>		
										<input name="working_field_`+working_counter+`" id="working_field_hidden`+working_content_id+`" type="hidden">																
										<a style="cursor: pointer; text-decoration: none;"
											class="removeWorkingContent text-danger-active fs18 ml-2" data-id="text"><i
												class="fa fa-times"></i></a>
									</div>                                                                                                     
								</div>
							</div>
						</div>`;	

			working_counter++;		
			
		}else if(type == 'image'){
			content = `<div class="col-lg-12">
							<div class="p-1 border rounded">
							<div class="form-inline">
									<div class="fileinput fileinput-new" data-provides="fileinput">							
										<div class="fileinput-preview thumbnail" data-trigger="fileinput" style="height: auto; width: 90%; padding: 0.5em;text-align: left;" class="form-control">
										<img src="https://smartjen.com/img/upload_file.png" class="img-responsive">
										</div>

										<a style="cursor: pointer; text-decoration: none;" class="removeWorkingContent text-danger-active fs18 ml-2" data-index="0" data-id="image">
											<i class="fa fa-times"></i>
										</a>   

										<div>
											<span class="btn btn-default btn-file" style="display:none">
												<span class="fileinput-new">Select image</span>
												<span class="fileinput-exists">Change</span>
												<input type="file" class="input_working_content" data-type="image" name="input_working_image[]">
											</span>
												
											<a href="#" class="btn btn-default fileinput-exists" data-dismiss="fileinput" style="display:none">Remove</a>
										</div>									
									</div>
                                                                                                    
								</div>
							</div>
						</div>`;
			working_counter++;	
		}

		$('.panel-body .working_content').append(content);

		if(type == 'text'){			

			$(document).on('focus', '.math_text', function() {
				$('#mathTarget').val($(this).attr('id'));
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
			
		}

		//hide addQuestionContentButton when there 6 fields append 
		if(working_counter >= 6){
			$('#addWorkingContentButton').hide();
		}
	}

	$(document).ready(function() {
		var maxLength = 54;
		$('#substrategy_id > option').text(function(i, text) {
			if (text.length > maxLength) {
				return text.substr(0, maxLength) + '...';
			}
		});
	});

	$(document).ready(function() {
		var maxLength = 54;
		$('#substrategy_id2 > option').text(function(i, text) {
			if (text.length > maxLength) {
				return text.substr(0, maxLength) + '...';
			}
		});
	});

	$(document).ready(function() {
		var maxLength = 54;
		$('#substrategy_id3 > option').text(function(i, text) {
			if (text.length > maxLength) {
				return text.substr(0, maxLength) + '...';
			}
		});
	});

	$(document).ready(function() {
		var maxLength = 54;
		$('#substrategy_id4 > option').text(function(i, text) {
			if (text.length > maxLength) {
				return text.substr(0, maxLength) + '...';
			}
		});
	});

		
	$(document).ready(function() {

		// new question content - shuq
		$('.panel_math_quill').hide();

		$(document).on('click', '.close_panel', function(){
			$('.panel_math_quill').fadeOut('fast');
		})

		$(document).on('click', '.answer_type_mcq', function (){
			
			var type = $(this).val();
				
			if(type == 'text'){				
				$('.input_answer_image').hide();
				$('.input_answer_text').show();					
			}else{
				$('.input_answer_text').hide();
				$('.input_answer_image').show();			
			}

		})

		$(document).on('click', '.answer_type_nmcq', function (){
			
			var type = $(this).val();
				
			if(type == 'text'){				
				$('.input_answer_image').hide();
				$('.input_answer_text').show();					
			}else{
				$('.input_answer_text').hide();
				$('.input_answer_image').show();			
			}

		})

		$(document).on('click', '.removeQuestionContent', function (e){
			e.preventDefault();
			var id = $(this).data('id');
			if(id == 'text'){
				question_content_id--;
				
			}

			content_counter--;

			// Shuq -  addQuestionContentButton if content less than 6
			if(content_counter != 6){
				$('#addQuestionContenButton').show();
			}
			$(this).parent().parent().parent().parent().remove();
		})


		$(document).on('click', '.removeWorkingContent', function (e){
			e.preventDefault();
			var id = $(this).data('id');
			if(id == 'text'){
				working_content_id--;
				
			}

			working_counter--;

			// Shuq -  addQuestionContentButton if content less than 6
			if(working_counter < 6){
				$('#addWorkingContentButton').show();
			}
			$(this).parent().parent().parent().parent().remove();
		})


		$(document).on('paste', '#question_text', function(){
			let mathSpanId = $(this).attr('id');
			let spanTarget = document.getElementById(mathSpanId);
			let mathSpan = MQ(spanTarget);
			let latex = mathSpan.latex();
			latex = latex.replace(/ /g, '');  // quick fix to prevent bracket becoming left and right
			$(this).next().val(latex);
		});

		// end new question content - shuq

		var quesType = $('#question_type_id').val();
		if ($(this).val() == 1 && $(this).val() == 4) {
			$('#open_ended_input_answers_divs').hide();
			$('#mcq_input_answers_divs').show();
		} else {
			$('#open_ended_input_answers_divs').show();
			$('#mcq_input_answers_divs').hide();
		}

		$(document).on('change', '.question_type_id', function(e) {
			var quesType = $('#question_type_id').val();
			var ids = $(this).attr('id');
			if (quesType == 1 || quesType == 4) {
				$('#open_ended_input_answers_divs').hide();
				$('#mcq_input_answers_divs').show();
			} else {
				$('#open_ended_input_answers_divs').show();
				$('#mcq_input_answers_divs').hide();
			}
		});

		$(document).on('focus', '.math_text', function() {
			$('.panel_math_quill').fadeIn('fast');
			$('#mathTarget').val($(this).attr('id'));
		});

		// $('.MathJax_SVG').each(function(index) {
		// 	let span_id = $(this).attr('id');
		// 	var mathFieldSpan = document.getElementById(span_id);
		// 	var MQ = MathQuill.getInterface(2);
		// 	var mathField = MQ.MathField(mathFieldSpan, {
		// 		handlers: {
		// 			edit: function() {
		// 				mathField.focus();
		// 			}
		// 		},
		// 		autoOperatorNames: 'somelongrandomoperatortooverride'
		// 	});
		// });
	
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
		
		$('form').submit(function() {
			$(this).find('input[type=checkbox]').each(function (i, el) {
				if(!el.checked) {
					var hidden_el = $(el).clone();
					hidden_el[0].checked = true;
					hidden_el[0].value = '0';
					hidden_el[0].type = 'hidden'
					hidden_el.insertAfter($(el));
				}
			})
		});
		
		/* delete image logic start */
		$('#smartjen_delete_image').on('click', function(e) {
			$('#submit_delete_error').hide();
			$('#submit_delete_success').hide();
		});
	
		$('#submit_delete_btn').on('click', function(e) {
			e.preventDefault();
			$('#submit_delete_error').hide('fast');
			$('#submit_delete_success').hide('fast');
	
			var $this = $(this);
			$this.button('loading');
			var ajax_url = base_url + 'administrator/update_question_image';
			var delete_question_id = <?php echo $question_detail->question_id ?>;

			$.ajax({
				url: ajax_url,
				method: "post",
				dataType: 'json',
				data: {
					"delete_question_id": delete_question_id,
					"delete_image_value": 0,
				},
				success: function(data) {
					if (data['success'] == true) {
						$('#submit_delete_success').html(data['message']).show('fast').delay(3000).hide('slow');
					} else {
						$('#submit_delete_error').html(data['message']).show('fast').delay(5000).hide('slow');
					}
					$this.button('reset');
				}
			});	
		});
		/* delete image logic end */

		$('#subject_id').ready(function(){
			var subject = document.getElementById("subject_id").value;

			if(subject != 2) {
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
			} else {
				$('#substrategy_id option').prop('disabled', false);
				$('#substrategy_id2 option').prop('disabled', false);
				$('#substrategy_id3 option').prop('disabled', false);
				$('#substrategy_id4 option').prop('disabled', false);
				$('#strategy_id option').prop('disabled', true);
				$('#strategy_id2 option').prop('disabled', true);
				$('#strategy_id3 option').prop('disabled', true);
				$('#strategy_id4 option').prop('disabled', true);
			}
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
	});
</script>