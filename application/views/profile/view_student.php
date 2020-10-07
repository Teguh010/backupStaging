<style type="text/css">
	#loading {
	  width: 100%;
	  height: 100%;
	  top: 0;
	  left: 0;
	  position: fixed;
	  display: block;
	  opacity: 0.7;
	  background-color: #fff;
	  z-index: 999999999;
	  text-align: center;
	}
	#loading-image {
	  position: absolute;
	  top: 100px;
	  left: 50%;
	  z-index: 100;
	}
/* 
	table{
		margin: 0 auto;
		width: 100%;
		clear: both;
		border-collapse: collapse;
		table-layout: fixed; // ***********add this
		word-wrap:break-word; // ***********and this
	}
	
	##Device = Desktops
	##Screen = 1281px to higher resolution desktops
	*/

	.table thead select{
		text-align: center;
		text-align-last: center;
		-moz-text-align-last: center;
	}

	.table thead select option{
		text-align: center;
		text-align-last: center;
		-moz-text-align-last: center;
	}

	.div-center {
		display: table;
  		margin: 0 auto;
	}
	.actionButton td {
		padding: 0 !important;
	}
	
	@media (min-width: 1281px) {
		table .th1{
			width: 15%;
		}
		table .th2{
			width: 25%;
		}
		table .th3{
			width: 16%;
		}
		table .th4{
			width: 13%;
		}
		table .th5{
			width: 10%;
		}
		table .th6{
			width: 12%;
		}
	}

	/* 
	##Device = Laptops, Desktops
	##Screen = B/w 1025px to 1280px
	*/
	@media (min-width: 1025px) and (max-width: 1280px) {
		table .th1{
			width: 15%;
		}
		table .th2{
			width: 25%;
		}
		table .th3{
			width: 16%;
		}
		table .th4{
			width: 13%;
		}
		table .th5{
			width: 10%;
		}
		table .th6{
			width: 12%;
		}
	}	
	.performance_nav_tabs li {
		/* width: 25%; */
		border-bottom: 3px solid transparent;
	}
	.performance_nav_tabs li.active {
		border-bottom: 3px solid #2ABB9B;
	}
	.performance_nav_tabs li a {
		border: 0 !important;
		/* height: 66px; */
	}
	@media (max-width: 415px) {
		.performance_nav_tabs li {
			width: 100%;
		}
		.performance_nav_tabs li a {
			height: 33px;
		}
	}	
	@media (min-width: 416px) and (max-width: 700px) {
		.performance_nav_tabs li {
			width: 50%;
		}
		.performance_nav_tabs li a {
			height: 33px;
		}
	}
	.selectize-input {
		text-align: center;
	}
	.selectize-dropdown, .selectize-dropdown.form-control {
		text-align: center !important;
	}
	.div-center {
		display: table;
  		margin: 0 auto;
	}
	.actionButton td {
		padding: 0 !important;
	}
	.form-control {
		max-width: 150px !important;
	}
</style>

<div class="section">

	<div class="container">

		<div class="row">

			<h2 class="text-center">Welcome <?php echo $userFullName; ?></h2>

			<?php 

				if (isset($profile_message_success)  && $profile_message_success) {

					echo '<div class="alert alert-success">';

					echo $profile_message;

					echo '</div>';

				} elseif (isset($profile_message_success) && !$profile_message_success) {

					echo '<div class="alert alert-danger">';

					echo $profile_message;

					echo '</div>';

				}

			?>

			<div class="col-xs-12 col-sm-3 col-md-3 col-lg-3">

				<div class="hovereffect">

					<img src="<?php echo base_url(); ?>img/profile/<?php echo $profilePic;?>" class="img-responsive center-block profile-pic">

					<div class="overlay">

						<a href="<?php echo base_url();?>profile/edit" class="btn btn-default"><i class="fa fa-pencil-square-o"></i> Edit Profile</a>

					</div>

				</div>

			</div>

			<div class="col-xs-12 col-sm-9 col-md-9 col-lg-9">

				<?php if(BRANCH_ID == 9) { ?>

				<div class="col-sm-6 col-md-6 col-lg-6 text-center">

					<a href="<?= site_url('profile/mylessons') ?>" class="btn btn-block btn-default"><i class="picons-thin-icon-thin-0398_presentation_powerpoint_keynote_meeting"></i> My Lessons (<?php echo count($lessons);?>)</a>

				</div>

				<?php } ?>

				<div class="col-sm-6 col-md-6 col-lg-6 text-center">

					<a href="#quizzes_div" class="jump_to_div btn btn-block btn-default"><i class="fa fa-file-text-o"></i> My Quizzes (<?php echo count($quizzes);?>)</a>

				</div>

				<div class="col-sm-6 col-md-6 col-lg-6 text-center">

					<?php

						$overall_statistics = 0;

						$denominator = 0;

						foreach ($analysis_structure as $strand) {

							if ($student_performance[$userId][$strand['name']]['total_attempt'] > 0) {

								$overall_statistics += $student_performance[$userId][$strand['name']]['percentage'];

								$denominator++;

							}



						}

						if ($denominator != 0) {

							$stats = round($overall_statistics / $denominator, 2);

						} else {

							$stats = 0;

						}



					?>

					<a href="#statistics_div" class="jump_to_div btn btn-block btn-default"><i class="fa fa-line-chart"></i> Overall Statistics (<?php echo $stats?>%)</a>

				</div>

				<!-- <div class="col-sm-6 col-md-6 col-lg-6 text-center">

					<a href="<?php echo base_url();?>profile/edit" class="btn btn-block btn-default"><i class="fa fa-pencil-square-o"></i> Edit Profile</a>

				</div>

				<div class="col-sm-6 col-md-6 col-lg-6 text-center">

					<a href="<?php echo base_url();?>profile/change_password" class="btn btn-block btn-default"><i class="fa fa-key"></i> Change Password</a>

				</div>

				<div class="col-sm-6 col-md-6 col-lg-6 text-center">
					<a href="<?php echo base_url();?>profile/tutor_parent_view" class="btn btn-block btn-default"><i class="fa fa-user"></i> Tutor/Parent List</a>
				</div> -->

				<?php

				if($this->session->userdata('user_id') == '479'){

				?>
					<div class = "col-xs-12 col-sm-9 col-md-9 col-lg-9" style="text-align: center; padding: 60px;">

						<p>This is a demo account. To find out more about the advanced features, please contact us at hello@smartjen.com</p>

					</div>

				<?php

				}

				?>
			</div>

		</div>

	</div>

</div>

<span class="anchor" id="quizzes_div"></span>
<div class="section">
	<div class="container">
		<div class="row">
			<div class="col-sm-12 col-md-12 col-lg-12">
				<div class="profile_div">
					<div class="profile_div_header">
						<i class="fa fa-minus pull-right fa_minimize_div"></i>
						<h4>My Quizzes</h4>
					</div>
					<div class="profile_div_body">
						<div class="table-responsive">
							<!-- <table class="table profile_table datatable">
								<thead>
									<tr class="success">
										<th>Subject</th>
										<th>Quiz Name</th>
										<th>Created By</th>
										<th>Created Date</th>
										<th>Status</th>
										<th class="text-center" style="padding-bottom: 15px;">Action</th>
									</tr>
								</thead>
								<tbody> -->
									<?php
										if (count($quizzes) == 0) {
											echo'<table class="table profile_table"><thead>
												<tr class="success">
													<th>Subject</th>
													<th>Quiz Name</th>
													<th>Created By</th>
													<th>Created Date</th>
													<th>Status</th>
													<th class="text-center" style="padding-bottom: 15px;">Action</th>
												</tr>
											</thead>
											<tbody>';
											echo '<tr>';
											echo '<td colspan="6"><div class="alert alert-danger margin-top-custom text-center">You don\'t have any quiz at the moment.</div></td>';
											echo '</tr>';
										} else {
											echo'<table class="table profile_table datatable"><thead>
												<tr class="success">
													<th>Subject</th>
													<th>Quiz Name</th>
													<th>Created By</th>
													<th>Created Date</th>
													<th>Status</th>
													<th class="text-center" style="padding-bottom: 15px;">Action</th>
												</tr>
											</thead>
											<tbody>';
											foreach ($quizzes AS $quiz) {
												echo '<tr class="showAttemptRecord">';
													echo '<td>' . $quiz->subject . '</td>';
													if(strlen($quiz->name)> 20) {
														$substr = substr($quiz->name, 0, 20);
														$quizName = $substr . '....';
													} else {
														$quizName = $quiz->name;
													}
													echo '<td title="' . $quiz->name . '">' . $quizName . '</td>';
													echo '<td>' . $quiz->createdBy . '</td>';
													echo '<td>' . $quiz->assignedDate . '</td>';
													if($quiz->archive == 0){
														if($quiz->status == 1) {
															if (intval($quiz->numOfAttempt) == 0) {
																echo '<td>Incomplete</td>';
																echo '<td class="attempt_quiz_btn" id="'.$quiz->id.'"><table class="actionButton"><tr>';
																// echo '<a class="btn btn-custom btn-no-margin-top" title="Attempt Quiz" onclick="attemptQuiz('.$quiz->id.')"><i class="fa fa-pencil"></i></a>';
																echo 
																'					
																	<td><a href="'.base_url().'onlinequiz/quiz/'.$quiz->id.'" class="btn btn-custom btn-no-margin-top" title="Attempt Quiz" onclick="attemptQuiz('.$quiz->id.')">
																		<i class="fa fa-pencil"></i>
																	</a></td>
																	<td><button class="btn btn-warning btn-no-margin-top view_worksheet_btn" title="Download Worksheet" onclick="viewWorksheet('.$quiz->worksheetId.')">
																		<i class="fa fa-file"></i>
																	</button></td>
																	<td><a href="#" class="btn btn-no-margin-top showAttemptQuizBtn" id="show_atttempt_quiz_btn" title="Expand/Collapse">
																		<i class="fa fa-caret-down"></i>
																	</a>
																</td></td></tr></table>';
															} else {
																echo '<td>Completed</td>';
																echo '<td class="attempt_quiz_btn" id="'.$quiz->id.'"><table class="actionButton"><tr>';
																echo '<td><a class="btn btn-custom btn-no-margin-top" title="Attempt Quiz" onclick="attemptQuiz('.$quiz->id.')"><i class="fa fa-pencil"></i></a></td>';
																echo '<td><button class="btn btn-warning btn-no-margin-top view_worksheet_btn" title="Download Worksheet" onclick="viewWorksheet('.$quiz->worksheetId.')">
																		<i class="fa fa-file"></i>
																	</button></td>';
																echo '<td><a href="#" class="btn btn-no-margin-top showAttemptQuizBtn" id="show_atttempt_quiz_btn" title="Expand/Collapse"><i class="fa fa-caret-down"></i></a>';
																echo '</td></td></tr></table>';
															}
														} else {
															echo '<td>Unassigned</td>';
															echo '<td class="attempt_quiz_btn" id="'.$quiz->id.'"><table class="actionButton"><tr>';
															echo '<td><a class="btn btn-custom btn-no-margin-top" title="Attempt Quiz" onclick="attemptQuiz('.$quiz->id.')" disabled><i class="fa fa-pencil"></i></a></td>';
															echo '<td><button class="btn btn-warning btn-no-margin-top view_worksheet_btn" title="Download Worksheet" onclick="viewWorksheet('.$quiz->worksheetId.')">
																		<i class="fa fa-file"></i>
																	</button></td>';
															echo '<td><a href="#" class="btn btn-no-margin-top showAttemptQuizBtn" id="show_atttempt_quiz_btn" title="Expand/Collapse"><i class="fa fa-caret-down"></i></a>';
															echo '</td></td></tr></table>';	
														}
													} else {
														echo '<td>Archived</td>';
														echo '<td class="attempt_quiz_btn" id="'.$quiz->id.'"><table class="actionButton"><tr>';
														echo '<td><a class="btn btn-custom btn-no-margin-top" title="Attempt Quiz" onclick="attemptQuiz('.$quiz->id.')" disabled><i class="fa fa-pencil"></i></a></td>';
														echo '<td><button class="btn btn-warning btn-no-margin-top view_worksheet_btn" title="Download Worksheet" onclick="viewWorksheet('.$quiz->worksheetId.')">
																		<i class="fa fa-file"></i>
																	</button></td>';
														echo '<td><a href="#" class="btn btn-no-margin-top showAttemptQuizBtn" id="show_atttempt_quiz_btn" title="Expand/Collapse"><i class="fa fa-caret-down"></i></a>';
														echo '</td></td></tr></table>';
													}
												echo '</tr>';
											}
										}
									
								?>
							</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<span class="anchor" id="statistics_div"></span>

<div class="section">

	<div class="container">

		<div class="row">

			<div class="col-sm-12 col-md-12 col-lg-12">

				<div class="profile_div">

					<div class="profile_div_header">

						<i class="fa fa-minus pull-right fa_minimize_div"></i>

						<h4>Performance Report</h4>

					</div>

					<div class="profile_div_body">

						<div class="overall_statistics">
						<!-- <div id="loading">
							<img id="loading-image" src="<?php echo base_url(); ?>img/loading.gif" alt="Loading..." />
						</div> -->
						<?php if(BRANCH_TID==1) { 
							$user_id = $this->session->userdata('user_id');
							$userLevel = $this->model_users->get_level_tid_by_student_id($user_id);
							$level_code = ($userLevel) ? $userLevel->level_code : 'junior';
							$topics_tid = $this->model_question->get_topiclevel_list();
							$level_name = $this->model_question->get_level_tid_by_id($level_code);
							?>
							<table class="table table-hover table-bordered" id="">

                            <thead>

                            <tr class="warning">

                                <th>Topics in <?php echo $level_name; ?></th>

                                <th>Score</th>

                            </tr>

                            </thead>

                            <tbody>

                            <?php
							foreach($student_performance as $performance) {	
								
                            foreach ($topics_tid as $category) {
								
                                $category_num_attempt = $performance[$category['topic_name']]['total_attempt'];

                                $category_num_correct = $performance[$category['topic_name']]['total_correct'];

                                if ($category_num_attempt != 0) {

                                    $category_perc = $performance[$category['topic_name']]['percentage'];

                                    $category_progress_bar = $performance[$category['topic_name']]['progress_bar_type'];

                                }

                                ?>

                                <tr>

                                    <td><?php echo  $category['topic_name']." (".$category['topic_short_name'].")"; ?></td>

                                    <td>

                                        <?php

                                        if ($category_num_attempt == 0) {

                                            echo 'No stats available';

                                        } else {

                                            $tooltip = $category_num_correct . ' mark(s) / ' . $category_num_attempt . ' total';

                                            ?>

                                            <div class="progress" data-toggle="tooltip" title="<?php echo $tooltip;?>">

                                                <div class="progress-bar <?php echo $category_progress_bar;?>" role="progressbar" aria-valuenow="40"

                                                     aria-valuemin="0" aria-valuemax="100" style="width:<?php echo $category_perc;?>%">

                                                    <?php echo $category_perc;?>%

                                                </div>

                                            </div>

                                            <?php

                                        }

                                        ?>

                                    </td>

                                </tr>





                                <?php

								}
							
							}

                            ?>

                            </tbody>

                        </table>
						<?php } else { ?>	
						<?php 

							foreach ($student_performance as $performance) {

						?>

							<div class="row">

								<div class="col-lg-5">
									<div class="row" style="padding-top:5px; padding-left:15px;">										
										<div class="form-group">
											<div class="col-lg-6 col-sm-12 col-md-12">
												<select id="subject_id_statistic">
													<?php 
														// $subject_id = $this->session->userdata('subject_id');
														foreach($subject_list as $row){
															if($subject_id == NULL){
																if(in_array($row->id, $subject)) {
																	if($row->id == $subject[0]) {
																		echo "<option value='".$row->id."' selected>".$row->name."</option>";
																	} else {
																		echo "<option value='".$row->id."'>".$row->name."</option>";
																	}
																} else {
																	echo "<option value='".$row->id."' disabled>".$row->name."</option>";
																}
															}else{
																if(in_array($row->id, $subject)) {
																	if($row->id == $subject_id){
																		echo "<option value='".$row->id."' selected>".$row->name."</option>";
																	} else {
																		echo "<option value='".$row->id."'>".$row->name."</option>";
																	}
																} else {
																	echo "<option value='".$row->id."' disabled>".$row->name."</option>";
																}
															}
																										
														}
													?>
												</select>
											</div>
											<div class="col-lg-6 col-sm-12 col-md-12">
												<a href="<?php echo base_url() . 'report/user/' . $userName . '/' . $subject_id; ?>" class="btn btn-block btn-default" style="margin: 0em;">View Full Report</a>
											</div>
										</div>										
									</div>

									<canvas class="chart_canvas" id="student_performance_strand" width="600" height="450" style="padding: 20px;"></canvas>

									<?php 

										$chart_performance_data = array();

										$chart_strand_label = array();

										foreach ($analysis_structure as $strand) {

											$chart_strand_label[] = $strand['name'];

											$chart_performance_data[] = $performance[$strand['name']]['percentage'];

										}

									?>

									<script type="text/javascript">

										var radarOptions = {

											responsive: true,

											legend: {

												labels: {

													fontSize: 15

												}

											},

											scale: {

												ticks: {

													beginAtZero: true,

													suggestedMin: 0,

													suggestedMax: 100,

													fontSize: 14,

													minTicksLimit: 5

												},

												pointLabels: {

													fontSize: 18,

													lineHeight: 2

												}

											}

										}



										var data = {

										    labels: [<?php echo '"' . implode('", "', $chart_strand_label) . '"' ?>],

										    datasets: [

										        {

										            label: "Strand Performance (%)",

										            backgroundColor: "rgba(255,197,90,0.2)",

										            borderColor: "rgba(255,197,90,0.8)",

										            pointBackgroundColor: "rgba(255,197,90,0.8)",

										            pointBorderColor: "rgba(255,197,90,0.5)",

										            pointHoverBackgroundColor: "#fff",

										            pointHoverBorderColor: "rgba(255,197,90,1)",

										            data: [<?php echo implode(',', $chart_performance_data); ?>],

										            borderWidth: "2"





										        }

										    ]

										};



										var ctx = $('#student_performance_strand');

										var myRadarChart = new Chart(ctx, {

										    type: 'radar',

										    data: data,

										    options: radarOptions

										});

									</script>

								</div>



								<div class="col-lg-7 tab-content performance-tab-content">

									<ul class="nav nav-tabs performance_nav_tabs">

										<?php 

											$strand_idx = 0;

											foreach ($analysis_structure as $strand) {

												if(strlen($strand['name'])> 18) {
													$substr = substr($strand['name'], 0, 18);
													$strand_name = $substr . '....';
													$tab_strand_name = str_replace(' ', '_', $strand['name']);
												} else {
													$strand_name = $strand['name'];
													$tab_strand_name = str_replace(' ', '_', $strand['name']);
												}
											?>

												<li class="<?php echo ($strand_idx==0)?'active':''; ?>" ><a href="#strand_tab_<?php echo $tab_strand_name; ?>" id="strand_tab_link_<?php echo $tab_strand_name; ?>" data-toggle="tab" title="<?php echo $strand['name']?>"><?php echo $strand_name?></a></li>

											<?php

												$strand_idx++;

											}

										?>

									</ul>

									<?php

										$strand_idx = 0;

										foreach ($analysis_structure as $strand) {

											if(strlen($strand['name'])> 18) {
												$substr = substr($strand['name'], 0, 18);
												$strand_name = $substr . '....';
												$tab_strand_name = str_replace(' ', '_', $strand['name']);
											} else {
												$strand_name = $strand['name'];
												$tab_strand_name = str_replace(' ', '_', $strand['name']);
											}
									?>

											<div class="tab-pane fade <?php echo ($strand_idx == 0)?'in active':'' ;?>" role="tab-panel" id="strand_tab_<?php echo $tab_strand_name; ?>">

												<!-- <div class="page-header">

												  <h2>Strand -  <?=$strand['name']?></h2>

												</div> -->

												<?php 

													$strand_idx++;

													foreach ($strand['substrand'] as $substrand) {

														$substrand_href = str_replace(array(' ', '&'), '_', $substrand['name']) . "_table";

														$perc = $performance[$strand['name']][$substrand['name']]['percentage'];

														$color = $performance[$strand['name']][$substrand['name']]['progress_bar_type'];

														switch ($color) {

															case 'progress-bar-danger': 

																$color = '#d9534f';

																break;

															case 'progress-bar-warning':

																$color = '#f0ad4e';

																break; 

															case 'progress-bar-success':

																$color = '#2ABB9B'; 

																break; 

														}



													?>

														<div class="col-xs-6 col-sm-4 col-md-4 col-lg-4 text-center substrand_performance">

															<div class="progress_circles" data-percent="<?php echo $perc?>" data-color="<?php echo $color?>"></div>

															<?php 
																if(strlen($substrand['name'])> 18) {
																	$substr = substr($substrand['name'], 0, 18);
																	$substrand_name = $substr . '....';
																} else {
																	$substrand_name = $substrand['name'];
																}
															?>

															<div href="#<?php echo $substrand_href; ?>" class="font-element fs14 btn btn-no-margin btn-block btn-custom toggle_category_table" style="padding: 10px; word-wrap: break-word; cursor: pointer;" title="<?php echo $substrand['name'] ?>">
															<?php 
																echo $substrand_name;
															?>
															</div>

														</div>



													<?php

													}



													foreach ($strand['substrand'] as $substrand) {

														$substrand_href = str_replace(array(' ', '&'), '_', $substrand['name']) . "_table";

														?>

															<table class="table table-hover performance_table table-bordered topics_score_table" id="<?php echo $substrand_href; ?>" style="width: 98%;">

															<thead>

																<tr class="warning">

																	<th>Topics in <?php echo $substrand['name']; ?></th>

																	<th>Score</th>

																<?php /*	<th>Action</th> */ ?>

																</tr>

															</thead>

															<tbody>

														<?php

															foreach ($substrand['category'] as $category) {

																$category_num_attempt = $performance[$strand['name']][$substrand['name']][$category['name']]['total_attempt'];

																$category_num_correct = $performance[$strand['name']][$substrand['name']][$category['name']]['total_correct'];

																if ($category_num_attempt != 0) {

																	$category_perc = $performance[$strand['name']][$substrand['name']][$category['name']]['percentage'];

																	$category_progress_bar = $performance[$strand['name']][$substrand['name']][$category['name']]['progress_bar_type'];

																}

															?>

																<tr>

																	<td><?php echo  $category['name']; ?></td>

																	<td>

																		<?php 

																			if ($category_num_attempt == 0) {

																				echo 'No stats available';

																			} else {

																				$tooltip = $category_num_correct . ' mark(s) / ' . $category_num_attempt . ' total';

																		?>

																			<div class="progress" data-toggle="tooltip" title="<?php echo $tooltip;?>" style="margin-bottom: 5px;">

																				<div class="progress-bar <?php echo $category_progress_bar;?>" role="progressbar" aria-valuenow="40"

																				aria-valuemin="0" aria-valuemax="100" style="width:<?php echo $category_perc;?>%">

																					<?php echo $category_perc;?>%

																				</div>

																			</div>

																		<?php

																			}

																		?>

																	</td>
																<?php /*
																	<td>

																		<button class="btn btn-custom smartgen_btn">Action</button>

																	</td>
																*/ ?>
																</tr>



																

														<?php

															}

														?>

															</tbody>

															</table>

														<?php

													}



												?>

											</div>

									<?php

										}

									?>

									

								</div>

						<?php

							}
						}
						?>
						</div>

						</div>

					</div>

				</div>

			</div>

		</div>

	</div>

</div>

<script type="text/javascript">

function attemptQuiz(quizId) {
	window.open('onlinequiz/quiz/' + quizId, "_self");
}

/**
 * Redirects users to view_worksheet view, opens in a new window
 * 
 * @param		string		$worksheetId		Worksheet ID
 */
function viewWorksheet(worksheetId) {
	window.location.replace('profile/worksheet/' + worksheetId, "_self");
}

$(document).ready(function(){

	$("#loading").hide();
	$('#subject_id_statistic').selectize();

	$('#subject_id_statistic').on('change', function(){
		var subject_id = $(this).val();
		$.ajax({
			url: base_url + 'Profile/updateStatistic',
			type: 'GET',
			dataType: 'json',
			data: {
				subject_id: subject_id
			},
			success: function (res) {
				if(res.msg == 'success'){					
					window.location.replace(base_url + 'profile');					
				}				
			}
		});
	});

	$('.showAttemptRecord').on('click', function(e){
			e.preventDefault();

			var tbl_row = $(this);
			
			var nextRow = $(this).next().attr('class');

			var userId = <?php echo $this->session->userdata('user_id'); ?>;

			if(nextRow === 'showStudentAttemptRecord' || nextRow === 'tbl_row_loading') {
				tbl_row.children().find('.fa.fa-caret-up').removeClass('fa-caret-up').addClass('fa-caret-down');
				$(this).nextUntil('.showAttemptRecord ').remove();
			} else {
				tbl_row.children().find('.fa.fa-caret-down').removeClass('fa-caret-down').addClass('fa-caret-up');
				var quiz_id = $(this).find('.attempt_quiz_btn').attr('id');
				tbl_row.after('<tr class="tbl_row_loading"><td colspan="6"><i class="fa fa-spinner"></i></td></tr>');
				
				$.ajax({
					url: "<?php echo base_url(); ?>profile/get_student_quiz",
					type: 'POST',
					dataType: 'json', // added data type
					data: {quizId:quiz_id,userId:userId},
					success: function(res) {

						$('.tbl_row_loading').remove();
						if(res.lastAttemptDate == '0') {
							$(tbl_row).after(`
								<tr class="showStudentAttemptRecord">
								<td colspan="6">
								<table class="table profile_table">
								<thead>
								<tr class="info">
								<th>Attempt Date</th>
								<th>Score</th>
								<th>Action</th>
								</tr>
								</thead>
								<tbody>
								<tr>
								<td colspan="3">Not attempted yet.</td>
								</tbody>
								</table>
								</td>
								</tr>
							`);
						} else {

							var attemptTbl;
							attemptTbl += '<tr class="showStudentAttemptRecord"><td colspan="6"><div class="table-responsive"><table class="table profile_table"><thead>';
							attemptTbl += '<tr class="info"><th>Attempt Date</th><th>Score</th><th>Action</th></tr></thead><tbody>';
							console.log(res.attempts);

							for(i=0; i< res.attempts.length; i++) {
								var attemptRec;

								attemptTbl += '<tr class="showAssignRecord"><td>' + res.attempts[i].attemptDateTime + '</td>';
								
								attemptTbl += '<td>' + res.attempts[i].question_no + '/'+ res.attempts[i].total_question_no + ' (' + res.attempts[i].scorePercentage + '%) </td>';
								
								attemptTbl += '<td><a href="' + base_url +  'onlinequiz/viewAttempt/' + res.attempts[i].id + '" class="btn btn-custom btn-no-margin-top">View Details</a></td></tr>';

							}
							attemptTbl += '</tbody></table></div></td></tr>';
							$(tbl_row).after(attemptTbl);
						}

						$('.showAssignRecord').mouseenter(function() {
							$(this).css("background-color", "#f6f6f6");
						}).mouseleave(function() {
							$(this).css("background-color", "#ffffff");
						});

					}

				});

			}

		});
});
</script>