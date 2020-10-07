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
									echo '<tr class="showAdminQuizRecord">';
										echo '<td>'.$worksheet->subject_name . '</td>';
										// echo '<td></td>';
										if(strlen($worksheet->admin_worksheet_name)> 20) {
											$substr = substr($worksheet->admin_worksheet_name, 0, 20);
											$wsName = $substr . '....';
										} else {
											$wsName = $worksheet->admin_worksheet_name;
										}
										echo '<td title="' . $worksheet->admin_worksheet_name . '">' . $wsName . '</td>';
										echo '<td>' . $worksheet->admin_created_date . '</td>';
										if($worksheet->admin_archived == 0){
											echo '<td>Active</td>';
										} else {
											echo '<td>Archived</td>';
										}
										echo '<td>';
										if($worksheet->admin_archived == 0){
											echo '<button onclick="assignWorksheet('.$worksheet->admin_worksheet_id.')" class="btn btn-custom btn-no-margin-top assign_worksheet_btn" title="Assign"><i class="fa fa-user"></i></button>';
										} else {
											echo '<button onclick="assignWorksheet('.$worksheet->admin_worksheet_id.')" class="btn btn-custom btn-no-margin-top assign_worksheet_btn" title="Assign" disabled><i class="fa fa-user"></i></button>';
										}
										echo '<button class="btn btn-warning btn-no-margin-top view_worksheet_btn" title="View/Edit Worksheet" onclick="viewWorksheet('.$worksheet->admin_worksheet_id.')"><i class="fa fa-file"></i></button>';
										echo '<button class="btn btn-danger btn-no-margin-top delete_worksheet_btn" data-toggle="modal" data-target="#deleteWorksheetModal" id="worksheet_'.$worksheet->admin_worksheet_id.'" title="Archive"><i class="fa fa-trash"></i></button>';
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

<div class="modal fade" id="deleteWorksheetModal" role="dialog" tabindex="-1">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header modal-header-custom-alert">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="worksheetModalLabel">Archive worksheet</h4>
			</div>
			<form class="form-horizontal" action="<?php echo base_url(); ?>administrator/archiveWorksheet" method="post" accept-charset="utf-8" id="delete_worksheet_form">
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

<script type="text/javascript">

	var userId = <?php echo $this->session->userdata('admin_id'); ?>;

	function viewWorksheet(worksheetId) {
		window.open('worksheet/' + worksheetId , "_blank");
	}

	function assignWorksheet(worksheetId) {
		window.open('assignWorksheet/' + worksheetId, "_blank") ;
	}

	$(document).ready(function(){

		$('.showQuizRecord').on('click', function(e){
			e.preventDefault();

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
								<th>Assign To</th>
								<th>Assign Date</th>
								<th>Attempt Records</th>
								</tr>
								</thead>
								<tbody>
								<tr>
								<td colspan="3">No Records</td>
								</tbody>
								</table>
								</td>
								</tr>
							`);
						} else {

							var attemptTbl;
							attemptTbl += '<tr class="showWorksheetAttemptRecord"><td colspan="5"><div class="table-responsive"><table class="table profile_table"><thead>';
							attemptTbl += '<tr class="info"><th>Assign To</th><th>Assign Date</th><th>Attempt Records</th></tr></thead><tbody>';


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
									attemptRec += '<tr class="attemptHistory"><td colspan="3"><div class="table-responsive"><table class="table profile_table">';
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

								attemptTbl += '<tr class="showAssignRecord"><td>' + studentName + '</td><td>' + assignDate + '</td><td>' + attemptRec;

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
				var student_id_split = $(this).find('.untag_student_btn').attr('id');
				var student_id = student_id_split.split('_')[2];
				tbl_row.after('<tr class="tbl_row_loading"><td colspan="4"><i class="fa fa-spinner"></i></td></tr>');

				$.ajax({
					url: "<?php echo base_url(); ?>profile/get_student_score",
					type: 'POST',
					dataType: 'json', // added data type
					data: {tutorId:userId},
					success: function(res) {
						
						$('.tbl_row_loading').remove();
						var studentRecord;
						var studentname = res.students[0].username;
						studentRecord += '<tr class="showStudentScoretRecord"><td colspan="4"><div class="table-responsive"><table class="table profile_table"><thead>';
						studentRecord += '<tr class="info"><th>Subject</th><th>Overall Score</th><th>Action</th></tr></thead><tbody>';
						for(i = 0; i < res.subject.length; i++){
							var subject = res.subject[i].name;
							studentRecord += '<tr class="showStudentScore"><td>' + subject + '</td>';
							
							if(i < 2 ) {
								var total_attempt = 0;
								var total_correct = 0;
								for(j=0; j < res.analysis_structure.length; j++) {
									var strand = res.analysis_structure[j].name;
									total_attempt += res.student_performance[student_id][strand].total_attempt;
									total_correct += res.student_performance[student_id][strand].total_correct;
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
								studentRecord += '<td><div class="progress" data-toggle="tooltip" title="' + tooltip + '"><div class="progress-bar ' + progress_bar_type + '" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width:' + percentage + '%">' + percentage + '%</div></div></td>';
								studentRecord += '<td><a href="' + base_url + 'report/user/' + studentname + '/' + res.subject[i].id + '" class="btn btn-custom" title="View Report"><i class="fa fa-file"></i></a></td>';
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
								studentRecord += '<td><a href="' + base_url + 'report/user/' + studentname + '/' + res.subject[i].id + '" class="btn btn-custom" title="Comingsssssssss Soon"><i class="fa fa-file"></i></a></td>';
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