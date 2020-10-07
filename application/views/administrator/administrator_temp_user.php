<div class="section">
	<div class="container">
		<div class="row">
			<div class="col-sm-12 col-md-12 col-lg-12">
				<div class="worksheet_div">
					<div class="worksheet_div_header">
						<i class="fa fa-minus pull-right fa_minimize_div"></i>
						<h4>Temporary User</h4>
					</div>
					<div class="worksheet_div_body">
						<div class="table-responsive">
							<table class="table worksheet_table">
								<thead><tr class="success">
									<th>ID</th>
									<th>Username</th>
									<th>Fullname</th>
									<th>Email Address</th>
									<th>Registered Date</th>
									<th>Action</th>
								</tr></thead>
								<tbody>
								<?php
									if (count($user) == 0) {
										echo '<div class="student-row clearfix">';
											echo '<div class="col-xs-offset-1 col-sm-offset-1 col-md-offset-1 col-lg-offset-1 col-xs-10 col-sm-10 col-md-10 col-lg-10">';
												echo '<div class="alert alert-danger margin-top-custom text-center">Don\'t have any outstanding question issues to be attended.</div>';
											echo '</div>';
										echo '</div>';
									} else {
										$i = 1;
										foreach ($user as $users) {
											foreach($users as $temp_user){
												echo '<tr>';
												echo '<td>' . $i. '</td>';
												echo '<td>' . $temp_user->username. '</td>';
												echo '<td>' . $temp_user->fullname. '</td>';
												echo '<td>' . $temp_user->email. '</td>';
												echo '<td>' . $temp_user->registered_date. '</td>';
												echo '<td><button class="btn btn-success approve_user" data-toggle="modal" data-target="#tempUserModal" id="user_'.$temp_user->key.'"><span data-toggle="tooltip" data-placement="top" title="User is waiting now! Approve it!">Approve User</span></button></td>';
												//echo '<td><a href="#" data-toggle="modal" data-target="#tempUserModal" class="btn btn-success" id="approve_user">Approve User</a>';
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
							<h4><?php echo $links; ?></h4>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="tempUserModal" role="dialog">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header modal-header-custom-success">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title">Approve User</h4>
			</div>
			<form class="form-horizontal" action="<?php echo base_url(); ?>administrator/add_temp_user" method="post" accept-charset="utf-8">
				<div class="modal-body">
					<div id="approve_user_error" class="alert alert-danger">
						
					</div>
					<div id="approve_user_success" class="alert alert-success">
						
					</div>
					<p>Please insert password for user confirmation.</p>
					<input type="hidden" name="approve_user_id" id="approve_user_id" value="">
					<div class="form-group">
						<label for="approve_user_pass" class="control-label col-sm-4 col-md-4 col-lg-4">Password:</label>
						<div class="col-sm-8 col-md-8 col-lg-8">
							<input type="password" name="approve_user_pass" id="approve_user_pass" placeholder="Password" class="form-control">
						</div>
					</div>
					<div class="form-group">
						<label for="approve_user_cpass" class="control-label col-sm-4 col-md-4 col-lg-4">Confirm Password:</label>
						<div class="col-sm-8 col-md-8 col-lg-8">
							<input type="password" name="approve_user_cpass" id="approve_user_cpass" placeholder="Confirm Password" class="form-control">
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<input type="button" class="btn btn-default" data-dismiss="modal" value="Close">
					<button type="button" class="btn btn-success" data-loading-text="<i class='fa fa-spinner fa-spin'></i> Deleting Image" id="approve_user_btn">Approve</button>
				</div>
			</form>
		</div>
	</div>
</div>
