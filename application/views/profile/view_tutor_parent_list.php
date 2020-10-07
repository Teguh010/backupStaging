<div class="section">
	<div class="container">
		<div class="row">
			<div class="col-sm-12 col-md-12 col-lg-12">
<?php 
	if (isset($untag_message_success)  && $untag_message_success) {
		echo '<div class="alert alert-success">';
		echo $untag_message;
		echo '</div>';
	} elseif (isset($untag_message_success) && !$untag_message_success) {
		echo '<div class="alert alert-danger">';
		echo $untag_message;
		echo '</div>';
	}
?>
				<div class="worksheet_div">
					<div class="worksheet_div_header">
						<i class="fa fa-minus pull-right fa_minimize_div"></i>
						<h4>Tutor List</h4>
					</div>
					<div class="worksheet_div_body">
						<div class="table-responsive">
							<table class="table worksheet_table">
								<thead><tr class="success">
									<th>Name</th>
									<th>Email</th>
									<th>Contact No</th>
									<th>Profession</th>
									<th>Action</th>
								</tr></thead>
								<tbody>
								<?php
									 if (count($tutor_list['tutor']->result()) === 0) {
										// echo '<div class="student-row clearfix">';
											echo '<div class="col-xs-offset-1 col-sm-offset-1 col-md-offset-1 col-lg-offset-1 col-xs-10 col-sm-10 col-md-10 col-lg-10">';
												echo '<div class="alert alert-danger margin-top-custom text-center">Don\'t have any tutors.</div>';
											echo '</div>';
										// echo '</div>';
									} else {
										foreach ($tutor_list['tutor']->result() as $tutor_lists) {
											echo '<tr>';
											echo '<td>' . $tutor_lists->fullname . '</td>';
											echo '<td>' . $tutor_lists->email. '</td>';
											if(is_numeric($tutor_lists->contact_no)){
												echo '<td>' . $tutor_lists->contact_no. '</td>';
											} else {
												echo '<td> - </td>';
											}
											if(is_numeric($tutor_lists->profession)){
												echo '<td>' . $tutor_list['profession'][$tutor_lists->profession] . '</td>';
											} else {
												echo '<td> - </td>';
											}
											echo '<td><button class="btn btn-danger btn-no-margin-top remove_tutor_btn" data-toggle="modal" data-target="#removeTutorModal" id="tutor_'.$tutor_lists->id.'">Request To Untag</button></td>';
											echo '</tr>';
										}
									}
								?>
								</tbody>
							</table>
						</div>
						<div class="pull-right">
							<h4><?php echo $links; ?></h4>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>



<div class="section">
	<div class="container">
		<div class="row">
			<div class="col-sm-12 col-md-12 col-lg-12">
				<div class="worksheet_div">
					<div class="worksheet_div_header">
						<i class="fa fa-minus pull-right fa_minimize_div"></i>
						<h4>Parent List</h4>
					</div>
					<div class="worksheet_div_body">
						<div class="table-responsive">
							<!-- <table class="table worksheet_table datatable"> -->
							<table class="table worksheet_table">
								<thead><tr class="success">
									<th>Name</th>
									<th>Email</th>
									<th>Contact No</th>
								</tr></thead>
								<tbody>
								<?php
									if (count($parent_list->result()) === 0) {
										// echo '<div class="student-row clearfix">';
											echo '<div class="col-xs-offset-1 col-sm-offset-1 col-md-offset-1 col-lg-offset-1 col-xs-10 col-sm-10 col-md-10 col-lg-10">';
												echo '<div class="alert alert-danger margin-top-custom text-center">Don\'t have any parent.</div>';
											echo '</div>';
										// echo '</div>';
									} else {
										foreach ($parent_list->result() as $parent_lists) {
											echo '<tr>';
												echo '<td>' . $parent_lists->fullname . '</td>';
												echo '<td>' . $parent_lists->email. '</td>';
												echo '<td>' . $parent_lists->contact_no. '</td>';
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

<div class="modal fade" id="removeTutorModal" role="dialog" tabindex="-1">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header modal-header-custom-alert">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="worksheetModalLabel">Remove Tutor</h4>
			</div>
			<form class="form-horizontal" action="<?php echo base_url(); ?>profile/untagTutor" method="post" accept-charset="utf-8" id="untag_tutor_form">
				<div class="modal-body">
					Confirm to untag tutor? 
				</div>
				<div class="modal-footer">
					<input type="hidden" name="tutor_id" id="untag_tutor_id">
					<input type="button" class="btn btn-default" data-dismiss="modal" value="Close">
					<input type="submit" class="btn btn-danger" id="confirm_delete_button" value="Untag">
				</div>
			</form>
		</div>
	</div>
</div>