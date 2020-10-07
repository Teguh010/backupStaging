<div class="section">
	<div class="container">
		<div class="col-md-8 col-md-offset-2">
				<ol class="smartgen-progress">
				  <li class="is-complete" data-step="1">
				    <a href="<?php echo base_url(); ?>smartgenkrtc">Design</a>
				  </li>
				  <li class="is-complete" data-step="2">
				    <a href="#">Customize</a>
				  </li>
				  <li class="is-active" data-step="3" class="smartgen-progress__last">
				    Assign
				  </li>
				</ol>
			</div>
	</div>
</div>

<div class="section">
	<div class="container">
		<?php
			if (count($my_students) == 0) {
				echo '<div class="alert alert-danger">You have no students yet. Please add your student in <a href="'.base_url().'profile">profile</a> page first. Don\'t worry, you can visit this page again later!</div>';
			}
		?>
		<div class="row">
			<div class="col-lg-6 col-md-6 col-sm-6">
				<div class="panel panel-success panel-success-custom-dark">
					<div class="panel-heading clearfix">
						My student list
						<a href="#" class="assign_all_students btn btn-custom btn-no-margin pull-right">Assign all</a>
					</div>
					<ul class="list-group" id="deassign_student_list">
						<?php
							if (count($not_assigned_students) == 0) {
								echo '<li class="list-group-item question_text helper_text">No students</li>';
							} else {
								foreach ($not_assigned_students as $student) {
									echo '<li class="list-group-item question_text student_li">';
									echo '<span>'.$student->fullname.'</span>';
									echo '<a href="#" id="'.$student->student_id.'" class="btn btn-custom btn-no-margin pull-right assign_student">Assign</a>';
									echo '</li>';
								}
							}
						?>
					</ul>
				</div>
			</div>
			<div class="col-lg-6 col-md-6 col-sm-6">
				<div class="panel panel-success">
					<div class="panel-heading clearfix">
						Assigned student list
						<a href="#" class="deassign_all_students btn btn-danger btn-no-margin pull-right">Remove all</a>
					</div>
					<ul class="list-group" id="assigned_student_list">
						<?php
							if (count($assigned_students) == 0) {
								echo '<li class="list-group-item question_text helper_text">No assigned student yet</li>';
							} else {
								echo '';
								foreach ($assigned_students as $student) {
									echo '<li class="list-group-item question_text student_li">';
									echo '<span>'.$student->fullname.'</span>';
									echo '<a href="#" id="'.$student->id.'" class="btn btn-danger btn-no-margin pull-right deassign_student">Remove</a>';
									echo '</li>';
								}
							}
						?>
					</ul>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="section">
	<div class="container">
		<div class="row">
			<div class="container">
				<div class="col-sm-offset-4 col-sm-4">
					<div class="text-center">
						<form action="<?php echo base_url().'smartgenkrtc/assignWorksheet/'.$worksheet_id; ?>" id="assign_worksheet_form" method="post">
							<?php
								foreach ($assigned_students as $student) {
									echo '<input type="hidden" class="assigned_student" name="assigned_students[]" value="' . $student->id . '">';
								}
							?>
							<input type="hidden" name="worksheet_id" value="<?php echo $worksheet_id; ?>">
							<input type="submit" class="btn btn-custom btn-no-margin btn-block" value="Finalize" id="assign_student_submit_button">
						</form>

					</div>
				</div>
			</div>
		</div>
	</div>
</div>
