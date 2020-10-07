<script type="text/javascript" src="<?php echo base_url(); ?>js/custom.js?20170823"></script>
<div class="profile_div_body">
	<div class="table-responsive">
		<table class="table profile_table" width="100%">
			<tr class="success">
				<th width="15%">Quiz Name</th>
				<th width="15%">Created By</th>
				<th width="15%">Date</th>
				<th width="55%">Action</th>
			</tr>
				<?php
					if (count($quizzes) == 0) {
						echo '<tr>';	
						echo '<td colspan="4"><div class="alert alert-danger margin-top-custom text-center">You don\'t have any quiz at the moment.</div></td>';
						echo '</tr>';
					} else {
						foreach ($quizzes AS $quiz) {
							echo '<tr>';
								echo '<td style="text-align:center;">' . $quiz->name . '</td>';
								echo '<td style="text-align:center;">' . $quiz->createdBy . '</td>';
								echo '<td style="text-align:center;">' . $quiz->assignedDate . '</td>';
								echo '<td style="text-align:center;">';
								if($quiz->archive == 0){
									if (intval($quiz->numOfAttempt) == 0) {
										echo '<div class="quiz_attempt_btn"><a href="'.base_url().'onlinequiz/quiz/'.$quiz->id.'" class="btn btn-custom btn-no-margin-top">Attempt</a></div>';
										echo '</td></tr>';
									} else {
										echo '<small class="quiz_attempt_text">You have attempted this quiz <em>' . $quiz->numOfAttempt . '</em> time(s). Last attempt : <em>' . $quiz->lastAttemptDate . '</em></small><br>';
										echo '<div class="quiz_attempt_btn">';
										echo '<a href="'.base_url().'onlinequiz/quiz/'.$quiz->id.'" class="btn btn-custom btn-no-margin-top">Re-attempt</a>';
										echo '<a href="#" class="showHistoricalAttempt btn btn-custom btn-no-margin-top">Show Attempt History</a>';
										echo '</div>';
										echo '<tr class="attemptHistory">';
										echo '<td colspan="4">';
										echo '<div class="table-responsive">';
										echo '<table class="table profile_table">';
										echo '<thead>';
										echo '<tr class="info">';
										echo '<th>Attempt Date</th>';
										echo '<th>Score</th>';
										echo '<th>Action</th>';
										echo '</tr>';
										echo '</thead>';
										echo '<tbody>';
										foreach ($quiz->attempts as $attempt) {
											echo '<tr>';
											echo '<td>'. $attempt->attemptDateTime .'</td>';
											echo '<td>' . $attempt->question_no . '/'.$attempt->total_question_no.' ('.$attempt->scorePercentage.'%) </td>';
											echo '<td><a href="'.base_url().'onlinequiz/viewAttempt/'.$attempt->id.'" class="btn btn-custom btn-no-margin-top">View Details</a></td>';
											echo '</tr>';
										}
										echo '</tbody>';
										echo '</table>';
										echo '</div>';
										echo '</td></tr>';
									}
								} else {
									if (intval($quiz->numOfAttempt) == 0) {
										echo '<small class="quiz_attempt_text">The worksheet is archived by tutor, so this quiz cannot be viewed or attempted.</small>';
										echo '<div class="quiz_attempt_btn"><a href="'.base_url().'onlinequiz/quiz/'.$quiz->id.'" class="btn btn-custom btn-no-margin-top" disabled>Attempt</a></div>';
										echo '</td></tr>';
									} else {
										echo '<small class="quiz_attempt_text">The worksheet is archived by tutor. Last attempt : <em>' . $quiz->lastAttemptDate . '</em></small><br>';
										echo '<div class="quiz_attempt_btn">';
										echo '<a href="'.base_url().'onlinequiz/quiz/'.$quiz->id.'" class="btn btn-custom btn-no-margin-top" disabled>Re-attempt</a>';
										echo '<a href="#" class="showHistoricalAttempt btn btn-custom btn-no-margin-top">Show Attempt History</a>';
										echo '</div>';
										echo '<tr class="attemptHistory">';
										echo '<td colspan="4">';
										echo '<div class="table-responsive">';
										echo '<table class="table profile_table">';
										echo '<thead>';
										echo '<tr class="info">';
										echo '<th>Attempt Date</th>';
										echo '<th>Score</th>';
										echo '<th>Action</th>';
										echo '</tr>';
										echo '</thead>';
										echo '<tbody>';
										foreach ($quiz->attempts as $attempt) {
											echo '<tr>';
											echo '<td>'. $attempt->attemptDateTime .'</td>';
											echo '<td>' . $attempt->question_no . '/'.$attempt->total_question_no.' ('.$attempt->scorePercentage.'%) </td>';
											echo '<td><a href="'.base_url().'onlinequiz/viewAttempt/'.$attempt->id.'" class="btn btn-custom btn-no-margin-top">View Details</a></td>';
											echo '</tr>';
										}
										echo '</tbody>';
										echo '</table>';
										echo '</div>';
										echo '</td></tr>';
									}
								}
						}
					}
				?>
			
			
		</table>
	</div>
</div>

<div align="right" class="pagination_tab" style="padding-right:20px;"><?php echo $pagination ?></div>

