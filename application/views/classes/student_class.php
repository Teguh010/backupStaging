<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>css/card.css" />
<div class="section">
	<div class="container">
		<div class="row">
			<div class="col-sm-12 col-md-12 col-lg-12">
			<div class="card-body">
				<div class="card2">
			      <div class="card-image"></div>
				      <div class="card-text">
				        <span class="date"><?php echo $class->name?></span>
				        <h2><?php echo $class->class_name?></h2>
				        <p><button onclick="listClass('<?php echo $page;?>')" class="btn btn-custom btn-no-margin-top" title="Back to Class List"><i class="fa fa-arrow-left"></i></button></p>
				      </div>
				      <div class="card-stats">
				        <div class="stat">
				          <div class="value"><?php echo number_format(count($student_class))?></div>
				          <div class="type">Students</div>
				        </div>
				        <div class="stat border">
				          <div class="value"></div>
				          <div class="type"></div>
				        </div>
				        <div class="stat">
				          <div class="value"><?php echo number_format(count($tutor_class))?></div>
				          <div class="type">Tutors</div>
				        </div>
				      </div>
			    </div>
			</div>			
			
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
									<th>Tag</th>
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
												echo '<tr>';
												echo '<td>' . (is_numeric($this->uri->segment(4)) ? $i + $this->uri->segment(4) : $i) . '</td>';
												echo '<td>'. $temp_user->fullname .'</td>';
												echo '<td>'. $temp_user->username .'</td>';
												echo '<td>'. $temp_user->email . '</td>';
												if($temp_user->status == '1'){
													echo '<td class="status_'.$temp_user->user_id.'">Active</td>';
												} else {
													echo '<td class="status_'.$temp_user->user_id.'">Inactive</td>';
												}
												echo '<td id="td-student-'.$temp_user->user_id.'">';
												$check_tag = $this->model_admin->check_class_tag($class_id, $temp_user->user_id);
												if($check_tag==0)
													echo '<a id="student_'.$temp_user->user_id.'_'.$class_id.'" class="btn btn-custom btn-no-margin-top assign_class_btn" title="Tag Student to Class"><i class="fa fa-user"></i></a>
														';
												else
													echo '<a class="btn btn-primary btn-no-margin-top" title="Tagged"><i class="fa fa-check"></i></a> <a class="btn btn-danger btn-no-margin-top" title="Remove Tag" onclick=\'removeTagStudent("'.$temp_user->user_id.'","'.$class_id.'")\'><i class="fa fa-trash"></i></a>';
												echo '</td></tr>';
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
<script type="text/javascript">
	
	function listClass(page) {
		window.location.href = '<?php echo base_url() ?>administrator/class_list/' + page ;
	}

	function removeTagStudent(studentId,classId) {
		$.ajax({
			url:'<?php echo base_url()?>administrator/removeTagStudent',
			method: 'post',
			data: {
				class_id: classId,
				student_id: studentId,
			},
			dataType: 'json',
			success: function(response){
				$('#td-student-'+studentId).html('');
				var html = '<a id="student_'+studentId+'_'+classId+'" class="btn btn-custom btn-no-margin-top assign_class_btn" title="Tag Student to Class"><i class="fa fa-user"></i></a>';
				$('#td-student-'+studentId).append(html);
			}
		});
	}

	$(document).ready(function(){

		$(document).on('click', '.assign_class_btn', function(e) {
			e.preventDefault();
			var studentIdArray = $(this).attr('id').split('_');
			var studentId = studentIdArray[1];
			var classId = studentIdArray[2];
			$.ajax({
				url:'<?php echo base_url()?>administrator/tagStudent',
				method: 'POST',
				data: {
					class_id: classId,
					student_id: studentId,
				},
				dataType: 'json',
				success: function(response){
					$('#td-student-'+studentId).html('');
					var html = `
						<a class="btn btn-primary btn-no-margin-top" title="Tagged"><i class="fa fa-check"></i></a>
						<a class="btn btn-danger btn-no-margin-top" title="Remove Tag" onclick=removeTagStudent(`+ studentId +`,` + classId + `)><i class="fa fa-trash"></i></a>
					`;
					$('#td-student-'+studentId).append(html);
				}
			});
		});
		
	});
//	});
</script>