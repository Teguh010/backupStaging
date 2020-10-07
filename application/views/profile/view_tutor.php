<style type="text/css">
	.group_label {
		border-radius: 3px;
		padding: 0px 12px;
		height: 32px;
		line-height: 32px;
		margin: 0 8px 8px 0;
		float: left;
		font-weight: 590;
		font-size: 14px;
		white-space: nowrap; 
		text-overflow: ellipsis;
		overflow: hidden;
		width: 186px;
		color: #FFFFFF !important;
	}
	.group_label:hover {
		overflow: visible;
	/*	text-shadow: 2px 2px 4px #000000; 
		transform: translateX(calc(200px - 100%));*/
		z-index: 9999;
		position: relative;
		opacity: 0.8;
	}
	.popup-label {
		width: 48.5% !important;
	}
	@media only screen and (min-device-width : 320px) and (max-device-width : 480px) {
	    .popup-label {
			width: 48.5% !important;
		}
	}
	.card-label.mod-clickable {
	    cursor: pointer;
	}
	.card-label.mod-edit-label {
	    float: left;
	    height: 35px;
	    margin: 0 8px 8px 0;
	    padding: 0;
	    width: 55px;
	}
	.card-label {
	    background-color: #b3bac5;
	    border-radius: 4px;
	    color: #fff;
	    display: block;
	    margin-right: 4px;
	    max-width: 100%;
	    overflow: hidden;
	    padding: 4px 6px;
	    position: relative;
	    text-overflow: ellipsis;
	    white-space: nowrap;
	    -webkit-font-smoothing: antialiased;
	    -moz-osx-font-smoothing: grayscale;
	}
	.icon-sm.light {
		color: #fff;
		text-decoration: none;
	}
	.card-label-color-select-icon {
	    position: absolute;
		left: 18px;
	}
	.icon-sm {
	    height: 20px;
	    font-size: 17px;
	    line-height: 20px;
	    width: 20px;
	    -webkit-font-smoothing: antialiased;
		-moz-osx-font-smoothing: grayscale;
		display: inline-block;
		font-style: normal;
		font-weight: 400;
		line-height: 1;
		text-align: center;
		text-decoration: none;
		float: left;
		margin-top: 8px;
		margin-right: 5px;
	}
	.icon-edit {
	    height: 20px;
	    font-size: 17px;
	    line-height: 20px;
	    width: 20px;
	    -webkit-font-smoothing: antialiased;
		-moz-osx-font-smoothing: grayscale;
		display: inline-block;
		font-style: normal;
		font-weight: 400;
		line-height: 1;
		text-align: center;
		text-decoration: none;
		margin-top: 5px;
		margin-right: -5px;
		padding: 4px;
	}
	.icon-edit:hover {
		border-radius: 3px;
		padding: 4px;
	}
	.edit-mode {
		float: right !important;
	}
	.hide {
	    display: none !important;
	}
	.line_break {
		margin-bottom: 10px;
	}
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
	.div-center {
		display: table;
  		margin: 0 auto;
	}
	.actionButton td {
		padding: 0 !important;
	}
</style>
<link href="<?php echo base_url()?>css/circle.css" rel="stylesheet">

<div id="loading">
  <img id="loading-image" src="<?php echo base_url(); ?>img/loading.gif" alt="Loading..." />
</div>
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

				<div class="col-sm-6 col-md-6 col-lg-6 text-center">

					<a href="#student_div" class="jump_to_div btn btn-block btn-default"><i class="fa fa-user"></i> My Students</a>

				</div>

				<div class="col-sm-6 col-md-6 col-lg-6 text-center">

					<a href="#worksheet_div" class="jump_to_div btn btn-block btn-default"><i class="fa fa-file-text-o"></i> My Worksheet</a>

				</div>

				<?php if(BRANCH_ID == 9) { ?>

				<div class="col-sm-6 col-md-6 col-lg-6 text-center">

					<a href="<?= site_url('profile/lessons') ?>" class="btn btn-block btn-default"><i class="picons-thin-icon-thin-0398_presentation_powerpoint_keynote_meeting"></i> My Lessons</a>

				</div>

				<div class="col-sm-6 col-md-6 col-lg-6 text-center">
					
				<a href="http://staging.smartjen.com/ocr/send_pdf" class="jump_to_div btn btn-block btn-default"><i class="fa fa-file-o"></i> Upload Worksheet</a>

				</div>

				<?php } ?>

				<div class="col-sm-6 col-md-6 col-lg-6 text-center">

					<a href="<?php echo base_url();?>report/performance" class="btn btn-block btn-default"><i class="fa fa-newspaper-o"></i> Group Report</a>

				</div>

				<!-- <div class="col-sm-6 col-md-6 col-lg-6 text-center">

					<a href="<?php echo base_url();?>profile/edit" class="btn btn-block btn-default"><i class="fa fa-pencil-square-o"></i> Edit Profile</a>

				</div>

				<div class="col-sm-6 col-md-6 col-lg-6 text-center">

					<a href="<?php echo base_url();?>profile/change_password" class="btn btn-block btn-default"><i class="fa fa-key"></i> Change Password</a>

				</div> -->

				<!-- <div class="col-sm-6 col-md-6 col-lg-6 text-center">

					<a href="<?php echo base_url();?>profile/create_question" class="btn btn-block btn-default"><i class="fa fa-upload"></i> Create own question</a>

				</div>

				<div class="col-sm-6 col-md-6 col-lg-6 text-center">

					<a href="<?php echo base_url()?>profile/question_list" class="btn btn-block btn-default"><i class="fa fa-question-circle-o"></i> My uploaded questions</a>

				</div> -->

			</div>

			<?php

			if($this->session->userdata('user_id') == '478'){

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



<!-- 

<span class="anchor" id="children_div"></span>

<div class="section">

	<div class="container">

		<div class="row">

			<div class="col-sm-12 col-md-12 col-lg-12">

				<div class="worksheet_div">

					<div class="worksheet_div_header">

						<i class="fa fa-minus pull-right fa_minimize_div"></i>

						<h4>My children</h4>

						<a href="#" class="btn btn-custom-light" data-toggle="modal" data-target="#createStudentModal" id="create_children_modal_button">Create new children account</a>

					</div>

					<div class="worksheet_div_body">

						<?php

							if (count($children) == 0) {

								echo '<div class="student-row clearfix">';

									echo '<div class="col-xs-offset-1 col-sm-offset-1 col-md-offset-1 col-lg-offset-1 col-xs-10 col-sm-10 col-md-10 col-lg-10">';

										echo '<div class="alert alert-danger margin-top-custom text-center">You don\'t have any children account yet.</div>';

									echo '</div>';

								echo '</div>';

							} else {

								foreach ($children AS $child) {

									echo '<div class="student-row clearfix">';

										echo '<div class="col-xs-12 col-sm-3 col-md-3 col-lg-3">';

											echo '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">';

												echo '<img src="' . base_url() . 'img/profile/' . $child->profile_pic . '" class="img-responsive center-block img-circle student-pic">';

											echo '</div>';

											echo '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">';

												echo $child->fullname;

											echo '</div>';

										echo '</div>';

										echo '<div class="col-xs-12 col-sm-9 col-md-9 col-lg-9">

												<div class="col-xs-4 col-sm-3 col-md-3 col-lg-3">

													<div class="progress_circles" data-text='.'78'.'%" data-fontsize="20" data-percent="78" data-fgcolor="#2ABB9B" data-bgcolor="#eee" data-width="5" data-bordersize="3" data-animationstep="2"></div>

												</div>

												<div class="col-xs-4 col-sm-3 col-md-3 col-lg-3">

													<div class="progress_circles" data-text="'.'78'.'%"; ?>" data-fontsize="20" data-percent="78" data-fgcolor="#2ABB9B" data-bgcolor="#eee" data-width="5" data-bordersize="3" data-animationstep="2"></div>

												</div>

												<div class="col-xs-4 col-sm-3 col-md-3 col-lg-3">

													<div class="progress_circles" data-text="'.'78'.'%"; ?>" data-fontsize="20" data-percent="78" data-fgcolor="#2ABB9B" data-bgcolor="#eee" data-width="5" data-bordersize="3" data-animationstep="2"></div>

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

</div>

 -->

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
										echo '<td><table class="actionButton"><tr>';
										if($worksheet->archived == 0){
											echo '<td><a onclick="assignWorksheet('.$worksheet->worksheet_id.')" class="btn btn-custom btn-no-margin-top assign_worksheet_btn" title="Assign"><i class="fa fa-user"></i></a></td>';
										} else {
											echo '<td><a onclick="assignWorksheet('.$worksheet->worksheet_id.')" class="btn btn-custom btn-no-margin-top assign_worksheet_btn" title="Assign" disabled><i class="fa fa-user"></i></a></td>';
										}
										echo '<td><a class="btn btn-warning btn-no-margin-top view_worksheet_btn" title="View/Edit Worksheet" onclick="viewWorksheet('.$worksheet->worksheet_id.')"><i class="fa fa-file"></i></a><td>';
										echo '<td><a class="btn btn-danger btn-no-margin-top delete_worksheet_btn" data-toggle="modal" data-target="#deleteWorksheetModal" id="worksheet_'.$worksheet->worksheet_id.'" title="Archive"><i class="fa fa-trash"></i></a><td>';
										// if(BRANCH_ID!=13) {
										// 	echo '<td><a class="btn btn-info btn-no-margin-top analytics_worksheet_btn" data-toggle="modal" data-target="#analyticsWorksheetModal" id="worksheet_'.$worksheet->worksheet_id.'" title="Analytics"><i class="fa fa-line-chart"></i></a><td>';
										// } else {
											echo '<td><button class="btn btn-info btn-no-margin-top" onclick="showPerformance(\''.$worksheet->worksheet_id.'\')" title="Analytics"><i class="fa fa-line-chart"></i></button></td>';
										// }
										echo '<td><button class="btn btn-no-margin-top showQuizRecordBtn" id="show_quiz_record_btn" title="Expand/Collapse" style="background-color: #FFF;"><span class="fa fa-caret-down"></span></button><td>';
										echo '</tr></table></td>';
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

<span class="anchor" id="student_div"></span>
<div class="section">
	<div class="container">
		<div class="row">
			<div class="col-sm-12 col-md-12 col-lg-12">
				<div class="worksheet_div">
					<div class="worksheet_div_header">
						<i class="fa fa-minus pull-right fa_minimize_div"></i>
						<h4>My Students</h4>						
						<!-- <a href="#" class="btn btn-custom-light" data-toggle="modal" data-target="#addStudentModal">Add existing student</a> -->
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
							?>
							<div class="table-responsive">
								<table class="table profile_table datatable" id="student_datatable">
									<thead>
									<tr class="success studentHeader">
										<th>Group</th>
										<th>Student Name</th>
										<th>Level</th>
										<th>School</th>
										<th>Class</th>
										<th style="padding-bottom: 15px;">Action</th>
									</tr>
									</thead>
									<tbody>
									<?php
									$i = 0;
									foreach ($students AS $student) {
										$group = $this->model_users->get_student_group($userId,$student->id);
										$text_color = ($group['group_id']=='0') ? $group['text_color']." !important" : $group['text_color'];
										echo '<tr class="showStudentRecord">';
										echo '<td class="text-center div-center td-group-'.$group['group_id'].'" id="td_group_'.$student->student_id.'"><div class="assign_group_btn" data-toggle="modal" data-target="#tagGroupModal" id="tag_group2_'.$student->student_id.'"  data-id="'.$group['group_id'].'" style="cursor:pointer;"><div id="group_'.$student->student_id.'" class="group_label" style="max-width: 100px !important;color:'. $text_color.';background-color:'. $group['color'] .'">' . $group['group_name'] . '</div></div></td>';
										echo '<td class="text-center">' . $student->fullname . '</td>';
										echo '<td class="text-center">' . $student->level_name . '</td>';
										echo '<td class="text-center">' . $student->school_name . '</td>';
										echo '<td class="text-center">-</td>';
										?>
										<?php
										echo '<td class="text-center" style="min-width: 178px !important;">';
										echo '<a class="btn btn-custom btn-no-margin-top assign_group_btn" data-toggle="modal" data-target="#tagGroupModal" id="tag_group_'.$student->student_id.'"  data-id="'.$group['group_id'].'" style="margin:3.5px 7px;" title="Tag Group Student"><i class="fa fa-group"></i></a>';										
										echo '<button class="btn btn-danger untag_student_btn" data-toggle="modal" data-target="#untagStudentModal" id="untag_id_'.$student->student_id.'" style="margin:3.5px 7px; display: none;" title="Untag Student"><i class="fa fa-trash"></i></button>';
										echo '<a href="#" class="btn btn-no-margin-top showStudentRecordBtn" id="show_student_record_btn_'.$i.'" title="Expand/Collapse"><i class="fa fa-caret-down"></i></a>';
										
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

<!-- 

<span class="anchor" id="mock_exam_worksheet_div"></span>

<div class="section">

	<div class="container">

		<div class="row">

			<div class="col-sm-12 col-md-12 col-lg-12">

				<div class="worksheet_div">

					<div class="worksheet_div_header">

						<i class="fa fa-minus pull-right fa_minimize_div"></i>

						<h4>My mock exams</h4>

						<a href="<?php echo base_url(); ?>smartgenkrtc" class="btn btn-custom-light">Create new mock exams</a>

					</div>

					<div class="worksheet_div_body">

						<div class="table-responsive">

							<table class="table worksheet_table datatable">

								<thead>

									<tr class="success">

										<th>Name</th>

										<th>Created date</th>

										<th>Action</th>

									</tr>

								</thead>

								<tbody>

								<?php

								foreach ($mock_exams AS $mock_exam) {

									echo '<tr>';

										echo '<td>'.$mock_exam->worksheet_name . '</td>';

										echo '<td>' . $mock_exam->created_date . '</td>';

										echo '<td>';

										echo '<a href="'.base_url().'profile/designMEWorksheet/'.$mock_exam->worksheet_id.'" class="btn btn-custom btn-no-margin-top">Design</a>';

										// echo '<a href="'.base_url().'profile/customizeMEWorksheet/'.$mock_exam->worksheet_id.'" class="btn btn-custom btn-no-margin-top">Customize</a>';

										echo '<a href="'.base_url().'smartgenkrtc/assignWorksheet/'.$mock_exam->worksheet_id.'" class="btn btn-custom btn-no-margin-top">Assign</a>';

										echo '<a href="'.base_url().'profile/worksheet/'.$mock_exam->worksheet_id.'" class="btn btn-warning btn-no-margin-top">View online/Download as PDF</a>';

										echo '<button class="btn btn-danger btn-no-margin-top delete_worksheet_btn" data-toggle="modal" data-target="#deleteWorksheetModal" id="worksheet_'.$mock_exam->worksheet_id.'">Delete</button>';

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

 -->


<div class="modal fade" id="deleteWorksheetModal" role="dialog" tabindex="-1">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header modal-header-custom-alert">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="worksheetModalLabel">Archive worksheet</h4>
			</div>
			<form class="form-horizontal" action="<?php echo base_url(); ?>profile/archiveWorksheet" method="post" accept-charset="utf-8" id="delete_worksheet_form">
				<div class="modal-body">
					Confirm to archive worksheet? 
				</div>
				<div class="modal-footer">
					<input type="hidden" name="worksheetId" id="delete_worksheet_id">
					<input type="button" class="btn btn-default" data-dismiss="modal" value="Close">
					<input type="submit" class="btn btn-danger" id="confirm_delete_button" value="Archive">
				</div>
			</form>
		</div>
	</div>
</div>

<div class="modal fade" id="untagStudentModal" role="dialog" tabindex="-1">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header modal-header-custom-alert">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="worksheetModalLabel">Untag Student</h4>
			</div>
			<form class="form-horizontal" action="<?php echo base_url(); ?>profile/untagStudent" method="post" accept-charset="utf-8" id="untag_student_form">
				<div class="modal-body">
					Confirm to untag this student? 
				</div>
				<div class="modal-footer">
					<input type="hidden" name="student_id" id="untag_student_id">
					<input type="button" class="btn btn-default" data-dismiss="modal" value="Close">
					<input type="submit" class="btn btn-danger" id="confirm_delete_button" value="Untag" <?php echo $this->session->userdata('user_id') == 478?'disabled':''; ?> >
				</div>
			</form>
		</div>
	</div>
</div>

<div class="modal fade" id="analyticsWorksheetModal" role="dialog" tabindex="-1">

	<div class="modal-dialog modal-width80" role="document">

		<div class="modal-content">

			<div class="modal-header modal-header-custom-success">

				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>

				<h4 class="modal-title" id="worksheetModalLabel">Worksheet Analytics</h4>

			</div>
			<div class="modal-body clearfix">
				<div id="analytics_report"></div>
			</div>

			<div class="modal-footer">
				<input type="hidden" name="worksheetId" id="analytics_worksheet_id">
				<input type="button" class="btn btn-default" data-dismiss="modal" value="Close">
			</div>
		</div>
		<script type="text/javascript">
			$('#analyticsWorksheetModal').on('show.bs.modal', function () {
				document.getElementById('analytics_report').innerHTML = "";
				var worksheet_id = $('#analytics_worksheet_id').val();
			   $('#loading').show();
			    $.ajax({
						url:'<?=base_url()?>profile/analytics_worksheet',
						method: 'post',
						data: {
							worksheet_id: worksheet_id
						},
						dataType: 'json',
						success: function(response){
							var res = JSON.stringify(response);
							var rs = JSON.parse(res);
							if(rs.success==true) {
								var html = rs.html;
								$('#analytics_report').append(html);
							}
							$('#loading').hide();
						}
					});
			});
		</script>

	</div>

</div>

<div class="modal fade" id="tagGroupModal" role="dialog" tabindex="-1">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header modal-header-custom-success">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="worksheetModalLabel">Select Group</h4>
			</div>
				<div class="modal-body clearfix">
					<input type="hidden" name="student_id" id="tag_group_student_id">
					<input type="hidden" name="exist_group" id="exist_group">
					Choose group: <br class="line_break" />
					<div>
						<div id="labels">
						<?php /*	<div class="group_label popup-label label-0" id="label-0" onclick="selectGroup('0', 'No group', '#000000', '#F0F0F0')" style="cursor:pointer;color:#fff;background-color: #b3bac5"><span class="icon-sm fa fa-check card-label-selectable-icon light group-0"></span> No group 
							</div> */?>
							<?php 
							if(isset($student_group)) {
								foreach ($student_group as $group) { 
							?>
							<div class="group_label popup-label label-<?php echo $group['id'];?>" id="label-<?php echo $group['id'];?>" onclick="selectGroup('<?php echo $group['id']?>', '<?php echo $group['group_name']?>', '<?php echo $group['text_color']?>', '<?php echo $group['color']?>')" style="cursor:pointer;color:#FFFFFF;background-color: <?php echo $group['color']; ?>">
								<span class="icon-sm fa fa-check card-label-selectable-icon light group-<?php echo $group['id'];?>"></span> 
								<?php echo $group['group_name']; ?> 
								<span onclick="editMode('<?php echo $group['id']?>', '<?php echo $group['group_name']?>', '<?php echo $group['text_color']?>', '<?php echo $group['color']?>')" class="icon-edit fa fa-edit edit-mode card-label-selectable-icon edit-group-<?php echo $group['id'];?>" id="edit-group-<?php echo $group['id'];?>"></span>
								
							</div>
							<?php } } ?>
						</div>
							<div class="group_label popup-label" onclick="newGroup()" style="cursor:pointer;color:#fff;background-color: #2abb9b"><span class="icon-sm fa fa-plus card-label-selectable-icon light"></span> Create a new group </div>

						<div style="clear:both;"></div>
					</div>
					<div class="clearfix new-group" style="display:none;padding: 10px;margin-top: 10px;border: 1px solid #F0F0F0">
						Group name:<br class="line_break" />
						<input style="max-width:496px;margin-bottom: 10px;" type="text" name="create_group_name" id="create_group_name" class="form-control" autofocus <?php echo in_array($this->session->userdata('user_id'), $tutorArray) ? 'disabled' : '' ?>>
						<div style="color:red;margin:-10px 0 0 0;padding:2px 0 0 0;font-size: 12px;" id="error_name"></div>
						<input type="hidden" name="created_by" id="created_by" value="<?php echo $this->session->userdata('user_id');?>" />
						Color:<br class="line_break" />
						<div>
							<span class="card-label mod-edit-label mod-clickable" onclick="selectColor('#DA5250','1')" style="background-color: #DA5250;">
								<span class="card-label-color-select-icon color-1 icon-sm fa fa-check"></span>
							</span>
							<span class="card-label mod-edit-label mod-clickable" onclick="selectColor('#F1AB4C','2')" style="background-color: #F1AB4C;">
								<span class="card-label-color-select-icon color-2 icon-sm fa fa-check hide"></span>
							</span>
							<span class="card-label mod-edit-label mod-clickable" onclick="selectColor('#35BCAA','3')" style="background-color: #35BCAA;">
								<span class="card-label-color-select-icon color-3 icon-sm fa fa-check hide"></span>
							</span>
							<span class="card-label mod-edit-label mod-clickable" onclick="selectColor('#3BB395','4')" style="background-color: #3BB395;">
								<span class="card-label-color-select-icon color-4 icon-sm fa fa-check hide"></span>
							</span>
							<span class="card-label mod-edit-label mod-clickable" onclick="selectColor('#D7E9E2','5')" style="background-color: #D7E9E2;">
								<span class="card-label-color-select-icon color-5 icon-sm fa fa-check hide"></span>
							</span>

							<?php /*
							<span class="card-label mod-edit-label mod-clickable" onclick="selectColor('#0079bf','6')" style="background-color: #0079bf;">
								<span class="card-label-color-select-icon color-6 icon-sm fa fa-check hide"></span>
							</span>
							<span class="card-label mod-edit-label mod-clickable" onclick="selectColor('#00c2e0','7')" style="background-color: #00c2e0;">
								<span class="card-label-color-select-icon color-7 icon-sm fa fa-check hide"></span>
							</span>
							<span class="card-label mod-edit-label mod-clickable" data-color="#51e898" style="background-color: #51e898;">
								<span class="card-label-color-select-icon color-8 icon-sm fa fa-check hide"></span>
							</span>
							<span class="card-label mod-edit-label mod-clickable" data-color="#ff78cb" style="background-color: #ff78cb;">
								<span class="card-label-color-select-icon color-9 icon-sm fa fa-check hide"></span>
							</span> 
							<span class="card-label mod-edit-label mod-clickable" onclick="selectColor('#344563','10')" style="background-color: #344563;">
								<span class="card-label-color-select-icon color-10 icon-sm fa fa-check hide"></span>
							</span> */ ?>
							<input type="hidden" name="select_color" id="select_color" value="#DA5250" />
							<input type="hidden" name="select_color_id" id="select_color_id" value="1" />
							<input type="hidden" name="group_id" id="group_id" value="0" />
							<input type="hidden" name="mode-form" id="mode-form" value="new" />
						</div>
						<div style="clear:both;margin-bottom: 10px;"></div>
						<input type="submit" onclick="addGroup()" class="btn btn-custom" style="margin:0 !important;" id="create_group_button" value="Create" <?php echo in_array($this->session->userdata('user_id'), $tutorArray) ? 'disabled' : '' ?>> <input type="submit" onclick="removeGroup()" class="btn btn-danger" style="margin:0 !important;" id="remove_group_button" value="Remove" <?php echo in_array($this->session->userdata('user_id'), $tutorArray) ? 'disabled' : '' ?>>
					</div>
				</div>
			<script type="text/javascript">
			function selectColor(color,id) {
				$('.card-label-color-select-icon').addClass('hide');
				$('.color-'+id).removeClass('hide');
				$('#select_color').val(color);
				$('#select_color_id').val(id);
			}
			function addGroup() {
				var mode = document.getElementById('mode-form').value;
				var adult_id = <?php echo $this->session->userdata('user_id');?>;
				var color = document.getElementById('select_color').value;
				var color_id = document.getElementById('select_color_id').value;
				var group_name = document.getElementById('create_group_name').value;
				$('#loading').show();
				if(mode=="new") {
					$.ajax({
						url:'<?=base_url()?>profile/newGroup',
						method: 'post',
						data: {
							adult_id: adult_id,
							color: color,
							group_name: group_name,
						},
						dataType: 'json',
						success: function(response){
							var res = JSON.stringify(response);
							var rs = JSON.parse(res);
							if(rs.success==true) {
								var group_id = rs.group_id;
								var student_id = $('#tag_group_student_id').val();
								var hide_edit = "";
								if(student_id=="") hide_edit = "";
								var html = '<div class="group_label popup-label label-'+group_id+'" id="label-'+group_id+'" onclick=\'selectGroup("'+group_id+'", "'+group_name+'", "#FFFFFF", "'+color+'")\' style="cursor:pointer;color:#fff;background-color: '+color+'"><span class="icon-sm fa fa-check card-label-selectable-icon light hide group-'+group_id+'"></span> '+group_name+'<span onclick=\'editMode("'+group_id+'", "'+group_name+'", "#FFFFFF", "'+color+'")\' class="icon-edit fa fa-edit edit-mode card-label-selectable-icon edit-group-'+group_id+' '+hide_edit+'" id="edit-group-'+group_id+'"></span></div>';
								$('#labels').append(html);
								$('#create_group_name').val('');
								$('.new-group').css('display','none');
							} else {
								$('#error_name').show();
								$('#error_name').empty().append('Group name already exist.');
								$('#create_group_name').css('border',"1px solid red");
							}
							$('#loading').hide();
						}
					});
				} else {
					var group_id = document.getElementById('group_id').value;
					var student_id = $('#tag_group_student_id').val();
					$('#loading').show();
					$.ajax({
						url:'<?=base_url()?>profile/editGroup',
						method: 'post',
						data: {
							adult_id: adult_id,
							color: color,
							group_name: group_name,
							group_id: group_id,
						},
						dataType: 'json',
						success: function(response){
							var res = JSON.stringify(response);
							var rs = JSON.parse(res);
							var group_id = rs.group_id;
							document.getElementById('label-'+group_id).setAttribute('onclick','selectGroup("'+group_id+'", "'+group_name+'", "#FFFFFF", "'+color+'")');
							document.getElementById('label-'+group_id).setAttribute('style','cursor:pointer;color:#FFFFFF;background-color:'+color+';');
							document.getElementById('label-'+group_id).innerHTML = '<span class="icon-sm fa fa-check card-label-selectable-icon light hide group-'+group_id+'"></span> '+group_name+'<span class="icon-edit fa fa-edit edit-mode card-label-selectable-icon edit-group-'+group_id+'" id="edit-group-'+group_id+'"></span>';
							document.getElementById('edit-group-'+group_id).setAttribute('onclick','editMode("'+group_id+'", "'+group_name+'", "#FFFFFF", "'+color+'")');
							$('#create_group_name').val('');
							$('.new-group').css('display','none');
							$('#loading').hide();
						}
					});
				}
			}
			function removeGroup() {
				var result = confirm("Are you sure you want to delete?");
				if(result) { 
					var group_id = document.getElementById('group_id').value;
					$('#loading').show();
					$.ajax({
						url:'<?=base_url()?>profile/deleteGroup',
						method: 'post',
						data: {
							group_id: group_id,
						},
						dataType: 'json',
						success: function(response){
							$('#label-'+group_id).hide();
							$('#create_group_name').val('');
							$('.new-group').css('display','none');
							$('#loading').hide();
						}
					});
				}
			}
			function newGroup() {
				// $('#select_color').val(color);
				$('.new-group').css('display','block');
				$('#mode-form').val('new');
				$('#create_group_name').val('');
				$('#select_color').val('#DA5250');
				$('#select_color_id').val('1');
				$('.card-label-color-select-icon').addClass('hide');
				$('.color-1').removeClass('hide');
				$('#create_group_button').val('Create');
				$('#remove_group_button').hide();
				$('#create_group_name').css('border',"1px solid #ccc");
				$('#error_name').hide();
			}
			$('#tagGroupModal').on('show.bs.modal', function () {
				$('.fa-check').addClass('hide');
				$('.fa-edit').removeClass('hide');
				var exist_group = $('#exist_group').val(); //document.getElementById('exist_group').value;
				if(exist_group!='0') $('.group-0').addClass('hide');
				else $('.group-0').removeClass('hide');
				<?php if(isset($student_group)) {
						foreach ($student_group as $group) { ?>
				if(exist_group!='<?php echo $group['id'];?>') $('.group-<?php echo $group['id'];?>').addClass('hide');
				else $('.group-<?php echo $group['id'];?>').removeClass('hide');
				<?php } } ?>
				// if(exist_group=='') { 
				// 	// $('.label-0').addClass('hide');
				// 	$('.edit-mode').removeClass('hide');
				// } else {
				// 	$('.edit-mode').addClass('hide');
				// }
			    // alert(exist_group);
			});
			function selectGroup(group_id, group_name, text_color, color) {
				if (!e) var e = window.event;
			    e.cancelBubble = true;
			    if (e.stopPropagation) e.stopPropagation();
				var student_id = $('#tag_group_student_id').val();
				$('#create_group_name').css('border',"1px solid #ccc");
				$('#error_name').hide();
				if(student_id!='') {
					$('.fa-check').addClass('hide');
					$('.group-'+group_id).removeClass('hide');
					var userId = <?php echo $this->session->userdata('user_id');?>;
					$('#loading').show();
					$.ajax({
						url:'<?=base_url()?>profile/save_student_group',
						method: 'post',
						data: {
							adult_id: userId,
							student_id: student_id,
							group_id: group_id,
						},
						dataType: 'json',
						success: function(response){
							document.getElementById('group_'+student_id).style.backgroundColor = color;
							document.getElementById('group_'+student_id).style.color = text_color;
							document.getElementById('group_'+student_id).innerHTML = group_name;
							document.getElementById('tag_group_'+student_id).setAttribute('data-id' , group_id);
							document.getElementById('tag_group2_'+student_id).setAttribute('data-id' , group_id);
							$('#tagGroupModal').modal('toggle');
							$('#loading').hide();
						}
					});
				} else {
					// alert('edit mode');
					$('.new-group').css('display','block');
					$('#mode-form').val('edit');
					$('#group_id').val(group_id);
					$('#create_group_name').val(group_name);
					$('#select_color').val(color);
					var id_color = 0;
					if(color=='#DA5250') id_color = '1';
					if(color=='#F1AB4C') id_color = '2';
					if(color=='#35BCAA') id_color = '3';
					if(color=='#3BB395') id_color = '4';
					if(color=='#D7E9E2') id_color = '5';
					// if(color=='#0079bf') id_color = '6';
					// if(color=='#00c2e0') id_color = '7';
					// if(color=='#61bd4f') id_color = '1';
					// if(color=='#61bd4f') id_color = '1';
					// if(color=='#344563') id_color = '10';
					$('.card-label-color-select-icon').addClass('hide');
					$('.color-'+id_color).removeClass('hide');
					$('#create_group_button').val('Edit');
					$('#remove_group_button').show();
				}
			}
			function editMode(group_id, group_name, text_color, color) {
				if (!e) var e = window.event;
			    e.cancelBubble = true;
			    if (e.stopPropagation) e.stopPropagation();
				$('.new-group').css('display','block');
				$('#mode-form').val('edit');
				$('#group_id').val(group_id);
				group_name = (group_name==" ") ? "" : group_name;
				$('#create_group_name').val(group_name);
				$('#select_color').val(color);
				var id_color = 0;
				if(color=='#DA5250') id_color = '1';
				if(color=='#F1AB4C') id_color = '2';
				if(color=='#35BCAA') id_color = '3';
				if(color=='#3BB395') id_color = '4';
				if(color=='#D7E9E2') id_color = '5';
				// if(color=='#0079bf') id_color = '6';
				// if(color=='#00c2e0') id_color = '7';
				// if(color=='#61bd4f') id_color = '1';
				// if(color=='#61bd4f') id_color = '1';
				// if(color=='#344563') id_color = '10';
				$('.card-label-color-select-icon').addClass('hide');
				$('.color-'+id_color).removeClass('hide');
				$('#create_group_button').val('Edit');
				$('#remove_group_button').show();
				$('#create_group_name').css('border',"1px solid #ccc");
				$('#error_name').hide();
			}
			</script>
			<br />
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

			<form class="form-horizontal create_student_form" action="<?php echo base_url(); ?>profile/createStudent" method="post" accept-charset="utf-8" id="create_student_form">

				<div class="modal-body clearfix">

					<div class="col-sm-offset-1 col-md-offset-1 col-lg-offset-1 col-sm-10 col-md-10 col-lg-10">

						<div id="create_student_error_div" class="alert alert-danger">

						
						</div>

						<div class="form-group asterisk" id="create_student_username_div">

							<div class="col-sm-12 col-md-12 col-lg-12">

								<input type="text" name="create_student_username" id="create_student_username" placeholder="Username" class="form-control" autofocus <?php echo in_array($this->session->userdata('user_id'), $tutorArray) ? 'disabled' : '' ?>>
								
							</div>

						</div>

						<div class="form-group asterisk" id="create_student_fullname_div">

							<div class="col-sm-12 col-md-12 col-lg-12">

								<input type="text" name="create_student_fullname" id="create_student_fullname" placeholder="Student's Fullname" class="form-control" <?php echo in_array($this->session->userdata('user_id'), $tutorArray) ? 'disabled' : '' ?>>

							</div>

						</div>

						<div class="form-group" id="create_student_email_div">
							<div class="col-sm-12 col-md-12 col-lg-12">
								<input type="email" name="create_student_email" id="create_student_email" placeholder="Student's Email" class="form-control" <?php echo in_array($this->session->userdata('user_id'), $tutorArray) ? 'disabled' : '' ?>>
							</div>
						</div>

						<!-- <div>
							<p style="text-align:center;">OR</p>
						</div> -->

						<div class="form-group" id="create_student_mobile_div">
							<div class="col-sm-12 col-md-12 col-lg-12">
								<span class="input-group-addon" style="position:absolute; width:50px; height:34px; vertical-align:top; padding-top:9px; font-size:14px;">+65</span>
								<input type="text" name="create_student_mobile" id="create_student_mobile" placeholder="Student's Mobile Number" class="form-control" maxlength="15" style="padding-left:55px;" <?php echo in_array($this->session->userdata('user_id'), $tutorArray) ? 'disabled' : '' ?>>
							</div>
						</div>

						<div class="form-group asterisk" id="create_parent_email_div">
							<div class="col-sm-12 col-md-12 col-lg-12">
								<input type="email" name="create_parent_email" id="create_parent_email" placeholder="Parent's Email" class="form-control" <?php echo in_array($this->session->userdata('user_id'), $tutorArray) ? 'disabled' : '' ?>>
								
							</div>
						</div>

						<div class="form-group asterisk" id="create_parent_mobile_div">
							<div class="col-sm-12 col-md-12 col-lg-12">
								<span class="input-group-addon" style="position:absolute; width:50px; height:34px; vertical-align:top; padding-top:9px; font-size:14px;">+65</span>
								<input type="text" name="create_parent_mobile" id="create_parent_mobile" placeholder="Parent's Mobile Number" class="form-control" style="padding-left:55px;" <?php echo in_array($this->session->userdata('user_id'), $tutorArray) ? 'disabled' : '' ?>>
							</div>
						</div>
						
						<div class="form-group" id="create_student_password_div">

							<div class="col-sm-12 col-md-12 col-lg-12">

								<input type="password" name="create_student_password" id="create_student_password" placeholder="Student's Password" class="form-control" <?php echo in_array($this->session->userdata('user_id'), $tutorArray) ? 'disabled' : '' ?>>

							</div>

						</div>

						<div class="form-group" id="create_student_cpassword_div">

							<div class="col-sm-12 col-md-12 col-lg-12">

								<input type="password" name="create_student_cpassword" id="create_student_cpassword" placeholder="Student's Confirm Password" class="form-control" <?php echo in_array($this->session->userdata('user_id'), $tutorArray) ? 'disabled' : '' ?>>

							</div>

						</div>

						<div class="form-group asterisks" id="create_student_level_id_div">

							<label for="create_student_level_id" class="col-sm-2 col-md-2 col-lg-2 control-label">Level</label>

							<div class="col-sm-10 col-md-10 col-lg-10">

								<select name="create_student_level_id" id="create_student_level_id" class="form-control" <?php echo in_array($this->session->userdata('user_id'), $tutorArray) ? 'disabled' : '' ?>>

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

								<select name="create_student_school_id" id="create_student_school_id" class="form-control" <?php in_array($this->session->userdata('user_id'), $tutorArray) ? 'disabled' : '' ?>>

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

					<input type="submit" class="btn btn-custom" id="create_student_button" value="Create" <?php echo in_array($this->session->userdata('user_id'), $tutorArray) ? 'disabled' : '' ?>>

				</div>

			</form>

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
			<form class="form-horizontal blast_student_form" action="<?php echo base_url() ?>profile/addStudent" method="post" accept-charset="utf-8" id="blast_student_form">
				<div class="modal-body clearfix">
					<div class="col-sm-offset-1 col-md-offset-1 col-lg-offset-1 col-sm-10 col-md-10 col-lg-10">
						<div id="blast_student_error_div" class="alert alert-danger">
						</div>
						<div class="col-sm-5 col-md-5 col-lg-5" style="font-size:90%">
							<input type="radio" name="blast_radio_button[]" id="blast_radio_button1" value="email" checked <?php echo in_array($this->session->userdata('user_id'), $tutorArray) ? 'disabled' : '' ?>> Student's Email
						</div>
						<div class="col-sm-5 col-md-5 col-lg-5" style="font-size:90%">
							<input type="radio" name="blast_radio_button[]" id="blast_radio_button2" value="username" <?php echo in_array($this->session->userdata('user_id'), $tutorArray) ? 'disabled' : '' ?>> Student's Username
						</div>
						<div class="form-group col-sm-12 col-md-12 col-lg-12" id="blast_student_div" style="padding-top:20px;">
							<input type="text" name="blast_student" id="blast_student" placeholder="Student's Email OR Username" class="form-control blast_student_<?php echo $userId ?>" autocomplete="off" <?php echo in_array($this->session->userdata('user_id'), $tutorArray) ? 'disabled' : '' ?>>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<input type="button" class="btn btn-default" data-dismiss="modal" value="Close">
					<input type="submit" class="btn btn-custom" id="blast_student_button" value="Send" <?php echo in_array($this->session->userdata('user_id'), $tutorArray) ? 'disabled' : '' ?>>
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

	function showPerformance(wsId) { 
		var worksheetId = wsId;
		var win = window.open('<?php echo base_url()?>report/performance/'+worksheetId, '_self');
		win.focus();
	}

	$('.assign_group_btn').on('click', function(e) {
		e.preventDefault();
		var studentIdArray = $(this).attr('id').split('_');
		var studentId = studentIdArray[2];
		var groupId = $(this).attr('data-id');
		$('#tag_group_student_id').val(studentId);
		$('#exist_group').val(groupId);
		$.ajax({
			url: '<?php echo base_url(); ?>profile/createGroup',
        	type: "GET",
       	 	dataType : 'html',
			success: function(data) {
				$('#labels').append(data);
			}
	    });
	});

	function viewWorksheet(worksheetId) {
		window.open('profile/worksheet/' + worksheetId , "_blank");
	}

	function assignWorksheet(worksheetId) {
		window.open('smartgen/assignWorksheet/' + worksheetId, "_blank") ;
	}

	function getGroup(student,tutor) {
		$.ajax({
			url: '<?php echo base_url(); ?>profile/getGroup/'+student+'/'+tutor,
        	type: "GET",
       	 	dataType : 'html',
			success: function(data) {
				$('.td_group_'+student).html(data);
			}
	    });
	}

	$(document).ready(function(){

		$('#loading').hide();
		$('.showQuizRecordBtn').on('click', function(e){
			e.preventDefault();
			$('#showQuizRecord').trigger('click');
		});
		$('.showQuizRecord').on('click', function(e){
			e.preventDefault();
			// alert(e.target.nodeName);
			if (e.target.nodeName=='DIV' || e.target.nodeName=='I' || e.target.nodeName=='A') {
		        return;
		    }

			var tbl_row = $(this);
			
			var nextRow = $(this).next().attr('class');

			if(nextRow === 'showWorksheetAttemptRecord' || nextRow == 'tbl_row_loading') {
				tbl_row.children().find('.fa.fa-caret-up').removeClass('fa-caret-up').addClass('fa-caret-down');
				$(this).nextUntil('.showQuizRecord').remove();
			} else {
				tbl_row.children().find('.fa.fa-caret-down').removeClass('fa-caret-down').addClass('fa-caret-up');
				var worksheet_id_split = $(this).find('.delete_worksheet_btn').attr('id');
				var worksheet_id = worksheet_id_split.split('_')[1];
				tbl_row.after('<tr class="tbl_row_loading"><td colspan="5"><i class="fa fa-spinner"></i></td></tr>');

				$.ajax({
					url: "<?php echo base_url(); ?>profile/get_quiz_attempt",
					type: 'POST',
					dataType: 'json', // added data type
					data: {worksheetId:worksheet_id},
					success: function(res) {

						$('.tbl_row_loading').remove();

						if(res.quizzes.length == '0') {
							$(tbl_row).after(`
								<tr class="showWorksheetAttemptRecord">
								<td colspan="5">
								<table class="table profile_table">
								<thead>
								<tr class="info">
								<th>Group</th>
								<th>Assign To</th>
								<th>Assign Date</th>
								<th>Attempt Records</th>
								</tr>
								</thead>
								<tbody>
								<tr>
								<td colspan="4">No Records</td>
								</tbody>
								</table>
								</td>
								</tr>
							`);
						} else {

							var attemptTbl;
							attemptTbl += '<tr class="showWorksheetAttemptRecord"><td colspan="5"><div class="table-responsive"><table class="table profile_table"><thead>';
							attemptTbl += '<tr class="info"><th>Group</th><th>Assign To</th><th>Assign Date</th><th>Attempt Records</th></tr></thead><tbody>';


							for(i=0; i< res.quizzes.length; i++) {

								var studentName = res.quizzes[i].studentName;
								var assignDate = res.quizzes[i].assignedDate;
								var attemptRec;

								if(res.quizzes[i].numOfAttempt === '0') {
									attemptRec = 'Not attempted yet.';
									attemptRec += '</td></tr>';
								} else {
									attemptRec = '<small>Attempted <em>' + res.quizzes[i].numOfAttempt + '</em> time(s). Last attempt : <em>' + res.quizzes[i].lastAttemptDate + '</em></small>';
									attemptRec += '<a href="#" class="showHistoricalAttempt_'+ worksheet_id +' btn btn-no-margin-top" id="show_attempt_history"><i class="fa fa-caret-down"></i></a>';
									attemptRec += '</td></tr>';
									attemptRec += '<tr class="attemptHistory"><td colspan="4"><div class="table-responsive"><table class="table profile_table">';
									attemptRec += '<thead><tr style="background-color: #f7ebd9; "><th>Attempt Date</th><th>Score</th><th>Action</th></tr></thead>';
									attemptRec += '<tbody>';

									var attempts = res.quizzes[i].attempts;

									for (j=0; j<attempts.length; j++ ) {
										attemptRec += '<tr class="attemptHistoryRow"><td>' + attempts[j].attemptDateTime + '</td><td>' + attempts[j].score;
										attemptRec += '<td><a href="' + base_url + 'onlinequiz/viewAttempt/' + attempts[j].id + '" class="btn btn-custom btn-no-margin-top">View Details</a></td>';
										attemptRec += '</td></tr>';
									}

									attemptRec += '</tbody></table></div>';

								}
								var assignedTo = res.quizzes[i].assignedTo;
								group = getGroup(assignedTo,userId);
								attemptTbl += '<tr class="showAssignRecord"><td class="td_group_'+assignedTo+' div-center" style="margin-top:5px !important;">' + group + '</td><td>' + studentName + '</td><td>' + assignDate + '</td><td>' + attemptRec;

							}
							attemptTbl += '</tbody></table></div></td></tr>';
							$(tbl_row).after(attemptTbl);
						}

						$('.showHistoricalAttempt_'+ worksheet_id).on('click', function(e){
							e.preventDefault();
							if ($(this).attr('id') == "show_attempt_history") {
								$(this).closest("tr").nextAll('.attemptHistory').first().show("slow");
								$(this).attr("id", "hide_attempt_history");
								$(this).find($(".fa")).removeClass('fa-caret-down').addClass('fa-caret-up');
							} else if ($(this).attr("id") == "hide_attempt_history") {
								$(this).closest("tr").nextAll('.attemptHistory').first().hide("slow");
								$(this).attr("id", "show_attempt_history");
								$(this).find($(".fa")).removeClass('fa-caret-up').addClass('fa-caret-down');
							}
						});

						$('.attemptHistoryRow').mouseenter(function() {
							$(this).css("background-color", "#f6f6f6");
						}).mouseleave(function() {
							$(this).css("background-color", "#ffffff");
						});

						$('.showAssignRecord').mouseenter(function() {
							$(this).css("background-color", "#f6f6f6");
						}).mouseleave(function() {
							$(this).css("background-color", "#ffffff");
						});

					}

				});

			}

		});

		$('.showStudentRecordBtn').on('click', function(e){
			e.preventDefault();
			// alert(e.target.nodeName);
			// if (e.target.nodeName=='DIV' || e.target.nodeName=='I' || e.target.nodeName=='A') {
		    //     return;
		    // }
		    if (e.target.nodeName=='SPAN' || e.target.nodeName=='B') {
		        return;
		    }
		    var branch_tid = <?php echo BRANCH_TID; ?>;
			
			var tbl_row = $(this).parent().parent();
			var nextRow = $(this).parent().parent().next().attr('class');

			var numb = $(this).attr('id');
			numb = numb.split('_')[4];

			if(nextRow === 'showStudentScoretRecord' || nextRow == 'tbl_row_loading') {
				tbl_row.children().find('.fa.fa-caret-up').removeClass('fa-caret-up').addClass('fa-caret-down');
				$(this).parent().parent().nextUntil('.showStudentRecord ').remove();
			} else {
				tbl_row.children().find('.fa.fa-caret-down').removeClass('fa-caret-down').addClass('fa-caret-up');
				var student_id_split = $(this).parent().find('.untag_student_btn').attr('id');
				var student_id = student_id_split.split('_')[2];
				tbl_row.after('<tr class="tbl_row_loading"><td colspan="6"><i class="fa fa-spinner"></i></td></tr>');

				$.ajax({
					url: "<?php echo base_url(); ?>profile/get_student_score",
					type: 'POST',
					dataType: 'json', // added data type
					data: {tutorId:userId, studentId:student_id},
					success: function(res) {
						$('.tbl_row_loading').remove();
						var studentRecord;
						studentRecord += '<tr class="showStudentScoretRecord"><td colspan="6"><div class="table-responsive"><table class="table profile_table_score"><thead>';
						if(branch_tid==1)
							studentRecord += '<tr class="info"><th>Overall Score</th><th>Action</th></tr></thead><tbody>';
						else
							studentRecord += '<tr class="info"><th>Subject</th><th>Overall Score</th><th>Action</th></tr></thead><tbody>';
						
						for(i = 0; i < res.subject.length; i++){
							var subject = res.subject[i][0].name;
							if(branch_tid==1)
								studentRecord += '<tr class="showStudentScore">';
							else
								studentRecord += '<tr class="showStudentScore"><td>' + subject + '</td>';

							if(i < 2 ) {
								var total_attempt = 0;
								var total_correct = 0;
								for(j=0; j < res.analysis_structure[0].length; j++) {
									var strand = res.analysis_structure[i][j].name;
									total_attempt += res.student_performance[i][student_id][strand].total_attempt;
									total_correct += res.student_performance[i][student_id][strand].total_correct;
								}
								var progress_bar_type;
								var percentage = 0;
								if(total_attempt != 0) {
									percentage = total_correct / total_attempt;
									percentage = +percentage.toFixed(2);
									percentage = percentage * 100;
									percentage = +percentage.toFixed(2);
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
								studentRecord += '<td><div class="progress" data-toggle="tooltip" title="' + tooltip + '"><div class="progress-bar ' + progress_bar_type + '" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width:' + percentage + '%">' + percentage + '%</div></div></td>';
								studentRecord += '<td><a href="' + base_url + 'report/user/' + studentname + '/' + res.subject[i][0].id + '" class="btn btn-custom" title="View Report"><i class="fa fa-file"></i></a></td>';
							} else {
								var rand = '<?php echo rand(0,100) ?>';
								var ran_tooltip = rand + ' mark(s) / 100 total';
								var rand_bar_type;
								if (rand <= 30) {
									rand_bar_type = "progress-bar-danger";
								} else if (rand >= 30 || rand < 70) {
									rand_bar_type = "progress-bar-warning";
								} else if (rand >= 70) {
									rand_bar_type = "progress-bar-success";
								}
								studentRecord += '<td><div class="progress" data-toggle="tooltip" title="' + ran_tooltip + '"><div class="progress-bar ' + rand_bar_type + '" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100"style="width:' + rand + '%">' + rand + '%</div></div></td>';	
								studentRecord += '<td><a href="' + base_url + 'report/user/' + studentname + '/' + res.subject[i][0].id + '" class="btn btn-custom" title="Comingsssssssss Soon"><i class="fa fa-file"></i></a></td>';
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

		// filter school list base on level id
		$('#create_student_level_id').change(function(){
    		var level = $(this).val();
			// AJAX request
			$.ajax({
				url:'<?=base_url()?>profile/get_school_list',
				method: 'post',
				data: {level: level},
				dataType: 'json',
				success: function(response){
					// Remove options
					$('#create_student_school_id').empty(); 

					// Add options
					$.each(response,function(index,data){
						$('#create_student_school_id').append('<option value="'+data['school_id']+'">'+data['school_name']+'</option>');
					});
				}
			});
		});

		// end of school list filter

	});

</script>