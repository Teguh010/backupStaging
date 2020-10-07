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
			<div class="col-sm-12 col-md-12 col-lg-12"><h2>Import From Google Classroom </h2>
			</div>
			<div class="col-sm-12 col-md-12 col-lg-12">
			<form action="<?php echo base_url()?>administrator/import_gc" method="post">
				<table>
				<?php // array_debug($courses); // exit;
					foreach ($courses as $course) {
						echo '<tr><td><input type="checkbox" checked name="check_class[]" value="'.$course->id.'"> </td><td>&nbsp;&nbsp;</td><td><img src="'.base_url().'img/google-classroom.png" width="40" /> <b>'.$course->name.'</b>
						<input type="hidden" value="'.$course->name.'" name="class_name_'.$course->id.'" />
						<input type="hidden" value="'.$course->enrollmentCode.'" name="class_code_'.$course->id.'" />
						</td></tr>';
						// array_debug($students);
						echo '<tr><td></td><td>&nbsp;&nbsp;</td><td><b><u>Tutors</u></b></td></tr>';
						foreach ($teachers[$course->id] as $teacher) {
							// if($teacher->courseId==$course->id) {
								echo '<tr><td></td><td>&nbsp;&nbsp;</td><td><input type="checkbox" checked name="class_'.$teacher->courseId.'_tutor[]" value="'.$teacher->userId.'"> <b>'.$teacher->profile->name->fullName.'</b> ('.$teacher->profile->emailAddress.')
								<input type="hidden" value="'.$teacher->profile->emailAddress.'" name="class_'.$teacher->courseId.'_tutor_email_'.$teacher->userId.'" />
								<input type="hidden" value="'.$teacher->profile->name->fullName.'" name="class_'.$teacher->courseId.'_tutor_name_'.$teacher->userId.'" />
								</td></tr>';
							//}
						}
						echo '<tr><td><br /></td></tr>';
						echo '<tr><td></td><td>&nbsp;&nbsp;</td><td><b><u>Students</u></b></td></tr>';
						foreach ($students[$course->id] as $student) {
							//if($student->courseId==$course->id) {
								echo '<tr><td></td><td>&nbsp;&nbsp;</td><td><input type="checkbox" checked name="class_'.$student->courseId.'_student[]" value="'.$student->userId.'"> <b>'.$student->profile->name->fullName.'</b> ('.$student->profile->emailAddress.')
								<input type="hidden" value="'.$student->profile->emailAddress.'" name="class_'.$student->courseId.'_student_email_'.$student->userId.'" />
								<input type="hidden" value="'.$student->profile->name->fullName.'" name="class_'.$student->courseId.'_student_name_'.$student->userId.'" />
								</td></tr>';
							//}
						}
						if(count($students[$course->id])==0) {
							echo '<tr><td></td><td><i>There are no students yet.</i></td></tr>';
						}
						echo '<tr><td><br /></td></tr>';
					}
				?>
				</table><br /><br />
				<button type="submit" class="btn btn-custom" name="import" style="margin-top:0px; margin-right:10px; height:33px; padding-bottom:28px; font-size:16px;">Import Now</button> 
				<a href="<?php echo base_url()."administrator/class_list";?>" class="btn btn-danger" name="import" style="margin-top:0px; margin-right:10px; height:33px; padding-bottom:28px; font-size:16px;">Cancel</a> 
				</form>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">

</script>
