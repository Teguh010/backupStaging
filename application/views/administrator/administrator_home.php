<div class="section">

	<div class="container">

		<div class="row">

			<div class="col-sm-12 col-md-12 col-lg-12">

				<!-- <a href="<?=base_url()?>administrator/create_new_question" class="btn btn-custom">Create new question</a>-->

				<div class="worksheet_div">

					<div class="worksheet_div_header">

						<i class="fa fa-minus pull-right fa_minimize_div"></i>

						<h4>Question issue</h4>

					</div>

					<div class="worksheet_div_body">

						<div class="table-responsive">

							<table class="table worksheet_table">

								<thead><tr class="success">

									<th>Feedback date</th>

									<th>User Id</th>

									<th>Question Id</th>

									<th>Error Type</th>

									<th>Error Comment</th>

									<th>Action</th>

								</tr></thead>


								<tbody>

								<?php

									 if (empty($question_issues)) {
										echo '<div class="student-row clearfix">';

											echo '<div class="col-xs-offset-1 col-sm-offset-1 col-md-offset-1 col-lg-offset-1 col-xs-10 col-sm-10 col-md-10 col-lg-10">';

												echo '<div class="alert alert-danger margin-top-custom text-center">Don\'t have any outstanding question issues to be attended.</div>';

											echo '</div>';

										echo '</div>';

									} else {

										foreach ($question_issues as $question_issue) {

											echo '<tr>';

											echo '<td>' . $question_issue->created . '</td>';

											echo '<td>' . $question_issue->user_id. '</td>';

											echo '<td>' . $question_issue->question_id. '</td>';

											echo '<td>' . $question_issue->error_type. '</td>';

											echo '<td>' . $question_issue->error_comment. '</td>';

											echo '<td><a href="'. base_url().'administrator/question/'.$question_issue->question_id.'" target="_blank" class="btn btn-warning">Go to question</a>';

											echo '<button id="'. $question_issue->id . '" class="btn btn-success resolved_btn" data-loading-text="<i class=\'fa fa-spinner fa-spin\'></i> Updating...">Resolved</button></td>';

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

						<h4>Feedback</h4>

					</div>

					<div class="worksheet_div_body">

						<div class="table-responsive">

							<table class="table worksheet_table datatable">

								<thead><tr class="success">

									<th>Feedback date</th>

									<th>Feedback type</th>

									<th>Feedback comment</th>

									<th>Action</th>

								</tr></thead>


								<tbody>
								<?php

									if (count($feedbacks) == 0) {
										echo '<div class="student-row clearfix">';

											echo '<div class="col-xs-offset-1 col-sm-offset-1 col-md-offset-1 col-lg-offset-1 col-xs-10 col-sm-10 col-md-10 col-lg-10">';

												echo '<div class="alert alert-danger margin-top-custom text-center">Don\'t have any unread feedback.</div>';

											echo '</div>';

										echo '</div>';
									} else {

										foreach ($feedbacks as $feedback) {

											echo '<tr>';

												echo '<td>' . $feedback->feedback_date . '</td>';

												echo '<td>' . $feedback->feedback_type. '</td>';

												echo '<td>' . $feedback->feedback_comment. '</td>';

												echo '<td><button id="feedback_' . $feedback->id . '" class="btn btn-warning read_btn" data-loading-text="<i class=\'fa fa-spinner fa-spin\'></i> Updating...">Mark as read</button>';

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