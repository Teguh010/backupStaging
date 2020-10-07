<style>
	.switch {
	position: relative;
	display: inline-block;
	width: 60px;
	height: 30px;
	}

	.switch input { 
	opacity: 0;
	width: 0;
	height: 0;
	}

	.slider {
	position: absolute;
	cursor: pointer;
	top: 0;
	left: 0;
	right: 0;
	bottom: 0;
	background-color: #ccc;
	-webkit-transition: .4s;
	transition: .4s;
	}

	.slider:before {
	position: absolute;
	content: "";
	height: 23px;
	width: 26px;
	left: 4px;
	bottom: 4px;
	background-color: white;
	-webkit-transition: .4s;
	transition: .4s;
	}

	input:checked + .slider {
	background-color: #94df4a;
	}

	input:focus + .slider {
	box-shadow: 0 0 1px #2196F3;
	}

	input:checked + .slider:before {
	-webkit-transform: translateX(26px);
	-ms-transform: translateX(26px);
	transform: translateX(26px);
	}

	/* Rounded sliders */
	.slider.round {
	border-radius: 34px;
	}

	.slider.round:before {
	border-radius: 50%;
	}
</style>
<div class="section">
	<div class="container">
		<div class="row">
			<div class="col-sm-12 col-md-12 col-lg-12">
			
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
			
			<form action="<?php echo base_url(); ?>administrator/add_branch_student" method="post">
				<div class="input-group" style="width: 100%;">
					<input type="text" placeholder="Add Student Email" name="add_branch_student" style="width:100%; padding:5px;">
					<span class="input-group-btn">
						<button type="submit" class="btn btn-primary pull-right" name="submit" style="margin-top:0px; margin-right:0px; height:33px; padding-bottom:28px; font-size:16px;"> Add</button>
					</span>
				</div>
			</form>
			
				<div class="worksheet_div">
					<div class="worksheet_div_header">
						<i class="fa fa-minus pull-right fa_minimize_div"></i>
						<h4>Student List</h4>
					</div>
					<div class="worksheet_div_body">
						<div class="table-responsive">
							<table class="table worksheet_table datatable">
								<thead><tr class="success">
									<th>No</th>
									<th>Name</th>
									<th>Username</th>
									<th>Email Address</th>
									<th>Status</th>
									<th>Action</th>
								</tr></thead>
								<tbody>
								<?php
									foreach ($user as $users) {
										if (count($users) == 0) {
											echo '<div class="student-row clearfix">';
												echo '<div class="col-xs-offset-1 col-sm-offset-1 col-md-offset-1 col-lg-offset-1 col-xs-10 col-sm-10 col-md-10 col-lg-10">';
													echo '<div class="alert alert-danger margin-top-custom text-center">Don\'t have any student in the listing.</div>';
												echo '</div>';
											echo '</div>';
										} else {
											$i = 1;
											foreach($users as $temp_user){
												echo '<tr class="showStudentClass">';
												echo '<td>' . (is_numeric($this->uri->segment(3)) ? $i + $this->uri->segment(3) : $i) . '</td>';
												echo '<td>'. $temp_user->fullname .'</td>';
												echo '<td>'. $temp_user->username .'</td>';
												echo '<td>'. $temp_user->email . '</td>';
												if($temp_user->status == '1'){
													echo '<td class="status_'.$temp_user->user_id.'">Active</td>';
												} else {
													echo '<td class="status_'.$temp_user->user_id.'">Inactive</td>';
												}
												echo '<td>';
												if($temp_user->status == '1'){
													echo '<label class="switch" for="'.$temp_user->user_id.'">
																<input type="checkbox" id="'.$temp_user->user_id.'" data-id="'.$temp_user->user_id.'" data-status="'.$temp_user->status.'" class="setActiveStudent">
																<span class="slider round"></span>
															</label>';
												} else {
													echo '<label class="switch" for="'.$temp_user->user_id.'">
																<input type="checkbox" id="'.$temp_user->user_id.'" data-id="'.$temp_user->user_id.'" data-status="'.$temp_user->status.'" class="setActiveStudent" checked>
																<span class="slider round"></span>
															</label>';
												}

													echo '<a class="btn btn-warning btn-no-margin-top" title="Edit Profile" onclick="editProfile('.$temp_user->user_id.')"><i class="fa fa-edit"></i></a>
															<a class="btn btn-primary btn-no-margin-top" title="Change Password" onclick="editPassword('.$temp_user->user_id.')"><i class="fa fa-key"></i></a>
															<a href="#" class="btn btn-no-margin-top showStudentClassBtn" title="Expand/Collapse"><i class="fa fa-caret-down"></i></a>
														</td>';
												echo '</tr>';
												$i++;
											}
										}
										
									}
								?>
								</tbody>
							</table>
						</div>
						<div class="pull-right">
							<h4><?php //echo $links; ?></h4>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>