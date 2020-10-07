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

				



				// if (isset($save_worksheet_success) && empty($save_worksheet_success) === false) {

				// 	echo '<div class="alert alert-success">' . $save_worksheet_success . '</div>';

				// } else if (isset($delete_worksheet_success) && empty($delete_worksheet_success) === false) {

				// 	echo '<div class="alert alert-success">' . $delete_worksheet_success . '</div>';

				// } else if (isset($delete_worksheet_failure) && empty($delete_worksheet_failure) === false) {

				// 	echo '<div class="alert alert-danger">' . $delete_worksheet_failure . '</div>';

				// }

				// echo "<pre>";

				// print_r($this->session->all_userdata());

				// echo "</pre>";

			?>

			<div class="col-sm-3 col-md-3 col-lg3">

				<img src="<?php echo base_url(); ?>img/profile/<?php echo $profilePic;?>" class="img-responsive center-block profile-pic">

			</div>



			<div class="col-sm-9 col-md-9 col-lg-9">

				<!-- <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 text-center">

					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

						<div class="circle_div">

							<img src="<?php echo base_url(); ?>img/worksheet.jpg" class="img-responsive center-block">

						</div>

					</div>

					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

						<a href="#children_div" class="jump_to_div">My Children</a>

					</div>

				</div>

				<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 text-center">

					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

						<div class="circle_div">

							<a href="#student_div"><img src="<?php echo base_url(); ?>img/worksheet.jpg" class="img-responsive center-block"></a>

						</div>

						<div class="progress_circles"  data-text="<?php echo "3"; ?>" data-fontsize="36" data-percent="<?php echo (3.0/5)*100; ?>" data-fgcolor="#2ABB9B" data-bgcolor="#eee" data-width="15" data-bordersize="3" data-animationstep="2">

							<a href="#student_div" class="jump_to_div"></a>

						</div>

					</div>

					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

						<a href="#student_div" class="jump_to_div">My students</a>

					</div>

				</div>

				<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 text-center">

					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

						<div class="circle_div">

							<a href="#worksheet_div" class="jump_to_div"><img src="<?php echo base_url(); ?>img/worksheet.jpg" class="img-responsive center-block"></a>

						</div>

					</div>

					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

						<a href="#worksheet_div" class="jump_to_div">My worksheet</a>

					</div>

				</div> -->

				<div class="col-sm-6 col-md-6 col-lg-6 text-center">

					<a href="#children_div" class="jump_to_div btn btn-block btn-default"><i class="fa fa-user"></i> My Children</a>

				</div>

				<div class="col-sm-6 col-md-6 col-lg-6 text-center">

					<a href="#worksheet_div" class="jump_to_div btn btn-block btn-default"><i class="fa fa-file-text-o"></i> My Worksheet</a>

				</div>

			</div>

		</div>

	</div>

</div>

<span class="anchor" id="student_div"></span>
<div class="section">
	<div class="container">
		<div class="row">
			<div class="col-sm-12 col-md-12 col-lg-12">
				<div class="worksheet_div">
					<div class="worksheet_div_header">
						<i class="fa fa-minus pull-right fa_minimize_div"></i>
						<h4>My Children</h4>
						<!-- <a href="#" class="btn btn-custom-light" data-toggle="modal" data-target="#createStudentModal" id="create_student_modal_button">Create New Children</a> -->
						<!-- <a href="#" class="btn btn-custom-light" data-toggle="modal" data-target="#blastStudentModal" id="blast_student_modal_button">Tag Chilren</a> -->
						<!-- <a href="#" class="btn btn-custom-light" data-toggle="modal" data-target="#addStudentModal">Add existing student</a> -->
					</div>
					<div class="worksheet_div_body">
						<?php
						if (count($children) == 0) {
							echo '<div class="student-row clearfix">';
							echo '<div class="col-xs-offset-1 col-sm-offset-1 col-md-offset-1 col-lg-offset-1 col-xs-10 col-sm-10 col-md-10 col-lg-10">';
							echo '<div class="alert alert-danger margin-top-custom text-center">You don\'t have any student yet.</div>';
							echo '</div>';
							echo '</div>';
						} else {
							?>
							<div class="table-responsive">
								<table class="table profile_table datatable">
									<thead>
									<tr class="success studentHeader">
										<th>Student Name</th>
										<th>Level</th>
										<th>School</th>
										<th style="padding-bottom: 15px;">Action</th>
									</tr>
									</thead>
									<tbody>
									<?php
									$i = 0;
									foreach ($children AS $child) {
										echo '<tr class="showStudentRecord">';
										echo '<td class="text-center">' . $child->fullname . '</td>';
										echo '<td class="text-center">' . $child->level_name . '</td>';
										echo '<td class="text-center">' . $child->school_name . '</td>';
										?>
										<?php
										echo '<td class="text-center">';
										// echo '<button class="btn btn-danger untag_student_btn" data-toggle="modal" data-target="#untagStudentModal" id="untag_id_'.$child->student_id.'" style="margin:3.5px 7px;" title="Untag Student"><i class="fa fa-trash"></i></button>';
										echo '<a href="#" class="btn btn-no-margin-top showStudentRecordBtn" id="show_student_record_btn_'.$i.'" data-id="'.$child->student_id.'" title="Expand/Collapse"><i class="fa fa-caret-down"></i></a>';
										
										echo '</td></tr>';
										$i++;
									}
									?>
									</tbody>
								</table>
							</div>
							<?php
								}
							?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<!-- <span class="anchor" id="children_div"></span>

<div class="section">

	<div class="container">

		<div class="row">

			<div class="col-sm-12 col-md-12 col-lg-12">

				<div class="worksheet_div">

					<div class="worksheet_div_header">

						<h4>My children</h4>

						<a href="#" class="btn btn-custom-light" data-toggle="modal" data-target="#createStudentModal" id="create_children_modal_button">Create new children account</a>

					</div>

					<div class="worksheet_div_body">

						<?php

							// if (count($children) == 0) {

							// 	echo '<div class="student-row clearfix">';

							// 		echo '<div class="col-xs-offset-1 col-sm-offset-1 col-md-offset-1 col-lg-offset-1 col-xs-10 col-sm-10 col-md-10 col-lg-10">';

							// 			echo '<div class="alert alert-danger margin-top-custom text-center">You don\'t have any children account yet.</div>';

							// 		echo '</div>';

							// 	echo '</div>';

							// } else {

							// 	foreach ($children AS $child) {

							// 		echo '<div class="student-row clearfix">';

							// 			echo '<div class="col-xs-12 col-sm-3 col-md-3 col-lg-3">';

							// 				echo '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">';

							// 					echo '<img src="' . base_url() . 'img/profile/' . $child->profile_pic . '" class="img-responsive center-block img-circle student-pic">';

							// 				echo '</div>';

							// 				echo '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">';

							// 					echo $child->fullname;

							// 				echo '</div>';

							// 			echo '</div>';

							// 			echo '<div class="col-xs-12 col-sm-9 col-md-9 col-lg-9">

							// 					<div class="col-xs-4 col-sm-3 col-md-3 col-lg-3">

							// 						<div class="progress_circles" data-text='.'78'.'%" data-fontsize="20" data-percent="78" data-fgcolor="#2ABB9B" data-bgcolor="#eee" data-width="5" data-bordersize="3" data-animationstep="2"></div>

							// 					</div>

							// 					<div class="col-xs-4 col-sm-3 col-md-3 col-lg-3">

							// 						<div class="progress_circles" data-text="'.'78'.'%"; ?>" data-fontsize="20" data-percent="78" data-fgcolor="#2ABB9B" data-bgcolor="#eee" data-width="5" data-bordersize="3" data-animationstep="2"></div>

							// 					</div>

							// 					<div class="col-xs-4 col-sm-3 col-md-3 col-lg-3">

							// 						<div class="progress_circles" data-text="'.'78'.'%"; ?>" data-fontsize="20" data-percent="78" data-fgcolor="#2ABB9B" data-bgcolor="#eee" data-width="5" data-bordersize="3" data-animationstep="2"></div>

							// 					</div>

							// 					<div class="col-xs-12 col-sm-3 col-md-3 col-lg-3 text-center">

							// 						<a href="#worksheet_div" class="btn btn-custom">Assign worksheet</a>

							// 					</div>

							// 				</div>';

							// 		echo '</div>';

							// 	}

							// }

						?>

					</div>

				</div>

			</div>

		</div>

	</div>

</div> -->



<!-- <span class="anchor" id="student_div"></span>

<div class="section">

	<div class="container">

		<div class="row">

			<div class="col-sm-12 col-md-12 col-lg-12">

				<div class="worksheet_div">

					<div class="worksheet_div_header">

						<h4>My students</h4>

						<a href="#" class="btn btn-custom-light" data-toggle="modal" data-target="#createStudentModal" id="create_student_modal_button">Create new student</a>

						<a href="#" class="btn btn-custom-light" data-toggle="modal" data-target="#addStudentModal">Add existing student</a>

					</div>

					<div class="worksheet_div_body">

						<?php

							if (count($students) == 0) {

								echo '<div class="student-row clearfix">';

									echo '<div class="col-xs-offset-1 col-sm-offset-1 col-md-offset-1 col-lg-offset-1 col-xs-10 col-sm-10 col-md-10 col-lg-10">';

										echo '<div class="alert alert-danger margin-top-custom text-center">You don\'t have any student yet.</div>';

									echo '</div>';

								echo '</div>';

							} else {

								foreach ($students AS $student) {

									echo '<div class="student-row clearfix">';

										echo '<div class="col-xs-12 col-sm-3 col-md-3 col-lg-3">';

											echo '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">';

												echo '<img src="' . base_url() . 'img/profile/' . $student->profile_pic . '" class="img-responsive center-block img-circle student-pic">';

											echo '</div>';

											echo '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">';

												echo $student->fullname;

											echo '</div>';

										echo '</div>';

										

										echo '<div class="col-xs-12 col-sm-9 col-md-9 col-lg-9">

												<div class="col-xs-4 col-sm-3 col-md-3 col-lg-3">

													<div class="progress_circles" data-text="78%" data-fontsize="20" data-percent="78" data-fgcolor="#2ABB9B" data-bgcolor="#eee" data-width="5" data-bordersize="3" data-animationstep="2"></div>

												</div>

												<div class="col-xs-4 col-sm-3 col-md-3 col-lg-3">

													<div class="progress_circles" data-text="78%" data-fontsize="20" data-percent="78" data-fgcolor="#2ABB9B" data-bgcolor="#eee" data-width="5" data-bordersize="3" data-animationstep="2"></div>

												</div>

												<div class="col-xs-4 col-sm-3 col-md-3 col-lg-3">

													<div class="progress_circles" data-text="78%" data-fontsize="20" data-percent="78" data-fgcolor="#2ABB9B" data-bgcolor="#eee" data-width="5" data-bordersize="3" data-animationstep="2"></div>

												</div>

												<div class="col-xs-12 col-sm-3 col-md-3 col-lg-3 text-center">

													<a href="#worksheet_div" class="btn btn-custom">Assign worksheet</a>

												</div>

											</div>';

									echo '</div>';

								}

							}

						?>

					</div>

				</div>

			</div>

		</div>

	</div>

</div> -->







<span class="anchor" id="worksheet_div"></span>
<div class="section">
	<div class="container">
		<div class="row">
			<div class="col-sm-12 col-md-12 col-lg-12">
				<div class="worksheet_div">
					<div class="worksheet_div_header">
						<i class="fa fa-minus pull-right fa_minimize_div"></i>
						<h4>My Worksheets</h4>
						<!-- <a href="<?php echo base_url(); ?>smartgen" class="btn btn-custom-light">Create New Worksheet</a>
						<a href="<?php echo base_url(); ?>smartgen/archiveWorksheetList" class="btn btn-custom-light">Archived Worksheet</a> -->
					</div>
					<div class="worksheet_div_body">
						<div class="table-responsive">
							<table class="table worksheet_table datatable">
								<thead>
									<tr class="success">
										<th>Subject</th>
										<th>Quiz Name</th>
										<th>Created Date</th>
										<th>Status</th>
										<th style="padding-bottom: 15px;">Action</th>
									</tr>
								</thead>
								<tbody>
								<?php
								foreach ($worksheets AS $worksheet) {
									echo '<tr class="showQuizRecord">';
										echo '<td>'.$worksheet->subject_name . '</td>';
										if(strlen($worksheet->worksheet_name)> 20) {
											$substr = substr($worksheet->worksheet_name, 0, 20);
											$wsName = $substr . '....';
										} else {
											$wsName = $worksheet->worksheet_name;
										}
										echo '<td title="' . $worksheet->worksheet_name . '">' . $wsName . '</td>';
										echo '<td>' . $worksheet->created_date . '</td>';
										if($worksheet->archived == 0){
											echo '<td>Active</td>';
										} else {
											echo '<td>Archived</td>';
										}
										echo '<td>';
										if($worksheet->archived == 0){
											echo '<button onclick="assignWorksheet('.$worksheet->worksheet_id.')" class="btn btn-custom btn-no-margin-top assign_worksheet_btn" title="Assign"><i class="fa fa-user"></i></button>';
										} else {
											echo '<button onclick="assignWorksheet('.$worksheet->worksheet_id.')" class="btn btn-custom btn-no-margin-top assign_worksheet_btn" title="Assign" disabled><i class="fa fa-user"></i></button>';
										}
										echo '<button class="btn btn-warning btn-no-margin-top view_worksheet_btn" title="View/Edit Worksheet" onclick="viewWorksheet('.$worksheet->worksheet_id.')"><i class="fa fa-file"></i></button>';
										echo '<button class="btn btn-danger btn-no-margin-top delete_worksheet_btn" data-toggle="modal" data-target="#deleteWorksheetModal" id="worksheet_'.$worksheet->worksheet_id.'" title="Archive"><i class="fa fa-trash"></i></button>';
										echo '<a href="#" class="btn btn-no-margin-top showQuizRecordBtn" id="show_quiz_record_btn" title="Expand/Collapse"><i class="fa fa-caret-down"></i></a>';
										echo '</td>';
									echo '</tr>';
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

<div class="modal fade" id="blastStudentModal" role="dialog" tabindex="-1">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header modal-header-custom-success" id="blast_form">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="blastModalLabel">Tag Student</h4>
			</div>
			<form class="form-horizontal blast_student_form" action="<?php echo base_url() ?>profile/addChild" method="post" accept-charset="utf-8" id="blast_student_form">
				<div class="modal-body clearfix">
					<div class="col-sm-offset-1 col-md-offset-1 col-lg-offset-1 col-sm-10 col-md-10 col-lg-10">
						<div id="blast_student_error_div" class="alert alert-danger">
						</div>
						<div class="col-sm-5 col-md-5 col-lg-5" style="font-size:90%">
							<input type="radio" name="blast_radio_button[]" id="blast_radio_button1" value="email" checked <?php echo $this->session->userdata('user_id') == '121' || $this->session->userdata('user_id') == '453' || $this->session->userdata('user_id') == '478' ? 'disabled' : '' ?>> Student's Email
						</div>
						<div class="col-sm-5 col-md-5 col-lg-5" style="font-size:90%">
							<input type="radio" name="blast_radio_button[]" id="blast_radio_button2" value="username" <?php echo $this->session->userdata('user_id') == '121' || $this->session->userdata('user_id') == '453' || $this->session->userdata('user_id') == '478' ? 'disabled' : '' ?>> Student's Username
						</div>
						<div class="form-group col-sm-12 col-md-12 col-lg-12" id="blast_student_div" style="padding-top:20px;">
							<input type="text" name="blast_student" id="blast_student" placeholder="Student's Email OR Username" class="form-control blast_student_<?php echo $userId ?>" autocomplete="off" <?php echo $this->session->userdata('user_id') == '121' || $this->session->userdata('user_id') == '453' || $this->session->userdata('user_id') == '478' ? 'disabled' : '' ?>>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<input type="button" class="btn btn-default" data-dismiss="modal" value="Close">
					<input type="submit" class="btn btn-custom" id="blast_student_button" value="Send" <?php echo $this->session->userdata('user_id') == '121' || $this->session->userdata('user_id') == '453' || $this->session->userdata('user_id') == '478' ? 'disabled' : '' ?>>
				</div>
			</form>
		</div>
	</div>
</div>





<div class="modal fade" id="deleteWorksheetModal" role="dialog" tabindex="-1">

	<div class="modal-dialog" role="document">

		<div class="modal-content">

			<div class="modal-header modal-header-custom-alert">

				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>

				<h4 class="modal-title" id="worksheetModalLabel">Delete worksheet</h4>

			</div>

			<form class="form-horizontal" action="<?php echo base_url(); ?>profile/deleteWorksheet" method="post" accept-charset="utf-8" id="delete_worksheet_form">

				<div class="modal-body">

					Confirm to delete worksheet? 

				</div>

				<div class="modal-footer">

					<input type="hidden" name="worksheetId" id="delete_worksheet_id">

					<input type="button" class="btn btn-default" data-dismiss="modal" value="Close">

					<input type="submit" class="btn btn-danger" id="confirm_delete_button" value="Delete">

				</div>

			</form>

		</div>

	</div>

</div>





<div class="modal fade" id="createStudentModal" role="dialog" tabindex="-1">

	<div class="modal-dialog" role="document">

		<div class="modal-content">

			<div class="modal-header modal-header-custom-success">

				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>

				<h4 class="modal-title" id="worksheetModalLabel">Create student</h4>

			</div>

			<form class="form-horizontal create_student_form" action="<?php echo base_url(); ?>profile/createChild" method="post" accept-charset="utf-8" id="create_student_form">

				<div class="modal-body clearfix">

					<div class="col-sm-offset-1 col-md-offset-1 col-lg-offset-1 col-sm-10 col-md-10 col-lg-10">

						<div id="create_student_error_div" class="alert alert-danger">

						
						</div>

						<div class="form-group asterisk" id="create_student_username_div">

							<div class="col-sm-12 col-md-12 col-lg-12">

								<input type="text" name="create_student_username" id="create_student_username" placeholder="Username" class="form-control" autofocus <?php echo $this->session->userdata('user_id') == '121' || $this->session->userdata('user_id') == '453' || $this->session->userdata('user_id') == '478' ? 'disabled' : '' ?>>
								
							</div>

						</div>

						<div class="form-group asterisk" id="create_parent_email_div">
							<div class="col-sm-12 col-md-12 col-lg-12">
								<input type="email" name="create_parent_email" id="create_parent_email" placeholder="Parent's Email" class="form-control" value="<?php echo $userEmail; ?>"  disabled>
								<input type="hidden" name="create_parent_email" id="create_parent_email" value="<?php echo $userEmail; ?>">
							</div>
						</div>

						<div class="form-group" id="create_student_email_div">
							<div class="col-sm-12 col-md-12 col-lg-12">
								<input type="email" name="create_student_email" id="create_student_email" placeholder="Student's Email" class="form-control" <?php echo $this->session->userdata('user_id') == '121' || $this->session->userdata('user_id') == '453' || $this->session->userdata('user_id') == '478' ? 'disabled' : '' ?>>
							</div>
						</div>

						<div>
							<p style="text-align:center;">OR</p>
						</div>

						<div class="form-group" id="create_student_mobile_div">
							<div class="col-sm-12 col-md-12 col-lg-12">
								<span class="input-group-addon" style="position:absolute; width:50px; height:34px; vertical-align:top; padding-top:9px; font-size:14px;">+65</span>
								<input type="text" name="create_student_mobile" id="create_student_mobile" placeholder="Student's Mobile Number" class="form-control" maxlength="15" style="padding-left:55px;" <?php echo $this->session->userdata('user_id') == '121' || $this->session->userdata('user_id') == '453' || $this->session->userdata('user_id') == '478' ? 'disabled' : '' ?>>
							</div>
						</div>

						<div class="form-group asterisk" id="create_student_fullname_div">

							<div class="col-sm-12 col-md-12 col-lg-12">

								<input type="text" name="create_student_fullname" id="create_student_fullname" placeholder="Student's Fullname" class="form-control" <?php echo $this->session->userdata('user_id') == '121' || $this->session->userdata('user_id') == '453' || $this->session->userdata('user_id') == '478' ? 'disabled' : '' ?>>

							</div>

						</div>
						
						<div class="form-group" id="create_student_password_div">

							<div class="col-sm-12 col-md-12 col-lg-12">

								<input type="password" name="create_student_password" id="create_student_password" placeholder="Password" class="form-control" <?php echo $this->session->userdata('user_id') == '121' || $this->session->userdata('user_id') == '453' || $this->session->userdata('user_id') == '478' ? 'disabled' : '' ?>>

							</div>

						</div>

						<div class="form-group" id="create_student_cpassword_div">

							<div class="col-sm-12 col-md-12 col-lg-12">

								<input type="password" name="create_student_cpassword" id="create_student_cpassword" placeholder="Confirm Password" class="form-control" <?php echo $this->session->userdata('user_id') == '121' || $this->session->userdata('user_id') == '453' || $this->session->userdata('user_id') == '478' ? 'disabled' : '' ?>>

							</div>

						</div>

						<div class="form-group asterisks" id="create_student_level_id_div">

							<label for="create_student_level_id" class="col-sm-2 col-md-2 col-lg-2 control-label">Level</label>

							<div class="col-sm-10 col-md-10 col-lg-10">

								<select name="create_student_level_id" id="create_student_level_id" class="form-control" <?php echo $this->session->userdata('user_id') == '121' || $this->session->userdata('user_id') == '453' || $this->session->userdata('user_id') == '478' ? 'disabled' : '' ?>>

									<?php

										foreach ($levels as $level) {

                                            echo '<option value="'.$level->level_id.'">'.$level->level_name.'</option>';

                                        }

									?>

								</select>

							</div>

						</div>

						<div class="form-group asterisks" id="create_student_school_id_div">

							<label for="create_student_school_id" class="col-sm-2 col-md-2 col-lg-2 control-label">School</label>

							<div class="col-sm-10 col-md-10 col-lg-10">

								<select name="create_student_school_id" id="create_student_school_id" class="form-control" <?php echo $this->session->userdata('user_id') == '121' || $this->session->userdata('user_id') == '453' || $this->session->userdata('user_id') == '478' ? 'disabled' : '' ?>>

									<?php

										foreach ($schools as $school) {

                                            echo '<option value="'.$school->school_id.'">'.$school->school_name.'</option>';

                                        }

									?>

								</select>

							</div>

						</div>

					</div>

				</div>

				<div class="modal-footer">

					<input type="button" class="btn btn-default" data-dismiss="modal" value="Close">

					<input type="submit" class="btn btn-custom" id="create_student_button" value="Create" <?php echo $this->session->userdata('user_id') == '121' || $this->session->userdata('user_id') == '453' || $this->session->userdata('user_id') == '478' ? 'disabled' : '' ?>>

				</div>

			</form>

		</div>

	</div>

</div>



<div class="modal fade" id="addStudentModal" role="dialog" tabindex="-1">

	<div class="modal-dialog" role="document">

		<div class="modal-content">

			<div class="modal-header modal-header-custom-success">

				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>

				<h4 class="modal-title" id="worksheetModalLabel">Add student</h4>

			</div>

			<form class="form-horizontal" action="<?php echo base_url(); ?>profile/addStudent" method="post" accept-charset="utf-8" id="add_student_form">

				<div class="modal-body clearfix">

					<div class="col-sm-offset-1 col-md-offset-1 col-lg-offset-1 col-sm-10 col-md-10 col-lg-10">

						<div class="form-group" id="add_student_username_div">

							<div class="input-group">

								<input type="text" name="add_student_username" id="search_student_username" placeholder="Search student" class="form-control">

								<span class="input-group-btn">

									<button class="btn btn-custom btn-no-margin-all" type="button" id="search_student_button">Search</button>

								</span>

							</div>

							

						</div>

						<div class="search_student_results" id="search_student_results">

							<!-- <div class="row student-row">

								<div class="col-xs-4 col-sm-2 col-md-2 col-lg-2">

									<img src="<?php echo base_url(); ?>img/profile/student_placeholder.jpg" class="img-responsive center-block img-circle student-pic">

								</div>

								<div class="col-xs-8 col-sm-10 col-md-10 col-lg-10">

									<div class="col-xs-12 col-sm-9 col-md-9 col-lg-9">

										<h3>[ Put name here ]</h3>

									</div>

									<div class="col-xs-12 col-sm-3 col-md-3 col-lg-3">

										<a href="#" class="btn btn-custom"> + </a>

									</div>

								</div>

							</div>

							<div class="row student-row">

								<div class="col-xs-4 col-sm-2 col-md-2 col-lg-2">

									<img src="<?php echo base_url(); ?>img/profile/student_placeholder.jpg" class="img-responsive center-block img-circle student-pic">

								</div>

								<div class="col-xs-8 col-sm-10 col-md-10 col-lg-10">

									<div class="col-xs-12 col-sm-9 col-md-9 col-lg-9">

										<h3>[ Put name here ]</h3>

									</div>

									<div class="col-xs-12 col-sm-3 col-md-3 col-lg-3">

										<a href="#" class="btn btn-custom"> + </a>

									</div>

								</div>

							</div> -->

						</div>

					</div>

				</div>

				<div class="modal-footer">

					<input type="button" class="btn btn-default" data-dismiss="modal" value="Close">

				</div>

			</form>

		</div>

	</div>

</div>

<script type="text/javascript">

	var userId = <?php echo $this->session->userdata('user_id'); ?>;

	function viewWorksheet(worksheetId) {
		window.open('profile/worksheet/' + worksheetId , "_blank");
	}

	function assignWorksheet(worksheetId) {
		window.open('smartgen/assignWorksheet/' + worksheetId, "_blank") ;
	}

	$(document).ready(function() {

		$('.showStudentRecord').on('click', function(e){
			e.preventDefault();

			var tbl_row = $(this);
			var nextRow = $(this).next().attr('class');

			var numb = $(this).find('.showStudentRecordBtn').attr('id');
			numb = numb.split('_')[4];

			var keys = $(document).find('.showStudentRecord').next().attr('class');

			if(nextRow === 'showStudentScoretRecord' || nextRow == 'tbl_row_loading') {
				tbl_row.children().find('.fa.fa-caret-up').removeClass('fa-caret-up').addClass('fa-caret-down');
				$(this).nextUntil('.showStudentRecord ').remove();
			} else {
				tbl_row.children().find('.fa.fa-caret-down').removeClass('fa-caret-down').addClass('fa-caret-up');
				var child_id = $(this).find('.showStudentRecordBtn').attr('data-id');
				tbl_row.after('<tr class="tbl_row_loading"><td colspan="4"><i class="fa fa-spinner"></i></td></tr>');

				$.ajax({
					url: "<?php echo base_url(); ?>profile/get_child_score",
					type: 'POST',
					dataType: 'json', // added data type
					data: {tutorId:userId},
					success: function(res) {
						$('.tbl_row_loading').remove();
						var studentRecord;
						studentRecord += '<tr class="showStudentScoretRecord"><td colspan="4"><div class="table-responsive"><table class="table profile_table"><thead>';
						studentRecord += '<tr class="info"><th>Subject</th><th>Overall Score</th><th>Action</th></tr></thead><tbody>';
						for(i = 0; i < res.subject.length; i++){
							var subject = res.subject[i].name;
							studentRecord += '<tr class="showStudentScore"><td>' + subject + '</td>';
							
							if(i == 0) {
								var total_attempt = 0;
								var total_correct = 0;
								for(j=0; j < res.analysis_structure.length; j++) {
									var strand = res.analysis_structure[j].name;
									total_attempt += res.student_performance[child_id][strand].total_attempt;
									total_correct += res.student_performance[child_id][strand].total_correct;
								}
								var progress_bar_type;
								var percentage = 0;
								if(total_attempt != 0) {
									percentage = total_correct / total_attempt;
									percentage = +percentage.toFixed(2);
									percentage = percentage * 100;
								} else {
									percentage = 0;
								}
								var tooltip = total_correct + ' mark(s) / ' + total_attempt + ' total';
								if (percentage <= 30) {
									progress_bar_type = "progress-bar-danger";
								} else if (percentage >= 30 || percentage < 70) {
									progress_bar_type = "progress-bar-warning";
								} else if (percentage >= 70) {
									progress_bar_type = "progress-bar-success";
								}
								var studentname = res.students[numb].username;
								studentRecord += '<td><div class="progress" data-toggle="tooltip" title="' + tooltip + '"><div class="progress-bar ' + progress_bar_type + '" role="progressbar"aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width:' + percentage + '%">' + percentage + '%</div></div></td>';
								studentRecord += '<td><a href="' + base_url + 'report/user/' + studentname + '/' + res.subject[i].id + '" class="btn btn-custom" title="View Report"><i class="fa fa-file"></i></a></td>';
							}else {
								studentRecord += '<td><div class="progress" data-toggle="tooltip" title><div class="progress-bar" role="progressbar"aria-valuenow="40" aria-valuemin="0" aria-valuemax="100">0%</div></div></td>';	
								studentRecord += '<td><a href="' + base_url + 'report/user/' + studentname + '/' + res.subject[i].id + '" class="btn btn-custom" title="Coming Soon"><i class="fa fa-file"></i></a></td>';
							}
							
							studentRecord += '</tr>';
						}

						studentRecord += '</tbody></table></div></td></tr>';

						$(tbl_row).after(studentRecord);

						$('.showStudentScore').mouseenter(function() {
							$(this).css("background-color", "#f6f6f6");
						}).mouseleave(function() {
							$(this).css("background-color", "#ffffff");
						});
					}
				});
			}

			
		});

		$('#btnGroupReport').on('click', function(){
			var href = $(this).data('href');
			window.location.href = href;
		});

	});

</script>