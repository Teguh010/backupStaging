<style  type="text/css">
	.datatable_list_questions th>select {
		max-width: 150px !important;
	}

	.input-group-addon {
		padding: 0px !important;
		border: 0px !important;
	}
</style>
<!-- <script src="<?=base_url()?>js/pages/administrator/new-question.js"></script> -->

<div class="section">
	<div class="container">
		<div class="row">
			<div class="col-sm-12 col-md-12 col-lg-12">
			<form action="<?php echo base_url(); ?>administrator/search_public_question" method="post" class="search" role="form">

				<div class="container">
					<div class="row row_subject">
						<div class="col-lg-3 mb-6 col-md-6 mb-30">
							<div class="list border1 rounded shadow-sm overflow-hidden cursor_pointer btnSubject"
								onclick="getLevel('Maths',2)">
								<div class="list-item">
									<div class="list-thumb avatar avatar60 shadow-sm">
										<img src="<?= base_url('img/icon-subject-math-2.png') ?>" alt="" class="img-fluid">
									</div>
									<div class="list-body text-right">
										<span class="list-title fs-1x font400">Maths</span>

									</div>
								</div>
							</div>
						</div>
						<!--col-->
						<div class="col-lg-3 mb-6 col-md-6 mb-30">
							<div class="list border1 rounded shadow-sm overflow-hidden cursor_pointer btnSubject"
								onclick="getLevel('English',1)">
								<div class="list-item">
									<div class="list-thumb avatar avatar60 shadow-sm">
										<img src="<?= base_url('img/icon-subject-english.png') ?>" alt="" class="img-fluid">
									</div>
									<div class="list-body text-right">
										<span class="list-title fs-1x font400">English</span>
									</div>
								</div>
							</div>
						</div>
						<!--col-->
						<div class="col-lg-3 mb-6 col-md-6 mb-30">
							<div class="list border1 rounded shadow-sm overflow-hidden cursor_pointer btnSubject"
								onclick="getLevel('Science',3)">
								<div class="list-item">
									<div class="list-thumb avatar avatar60 shadow-sm">
										<img src="<?= base_url('img/icon-subject-science.png') ?>" alt="" class="img-fluid">
									</div>
									<div class="list-body text-right">
										<span class="list-title fs-1x font400">Science</span>

									</div>
								</div>
							</div>
						</div>
						<!--col-->
						<!-- <div class="col-lg-3 mb-6 col-md-6 mb-30">
							<div class="list border1 rounded shadow-sm overflow-hidden cursor_pointer btnSubject"
								onclick="getLevel('Math',2)">
								<div class="list-item">
									<div class="list-thumb avatar avatar60 shadow-sm">
										<img src="<?= base_url('img/icon-subject-math-2.png') ?>" alt="" class="img-fluid">
									</div>
									<div class="list-body text-right">
										<span class="list-title fs-1x font400">Secondary Maths</span>

									</div>
								</div>
							</div>
						</div> -->
						<!--col-->
					</div>

					<div class="row row_level b-t" style="display: none;">
						<div class="mt-20 content_level">
							<div class="col-lg-12 primary_level">
							
							</div>
							<div class="col-lg-12 secondary_level">
							
							</div>
						</div>
					</div>
				</div>
				<!-- <div class="input-group" style="width: 100%;">
					<input type="text" placeholder="Question Text..." name="search" style="width:100%; padding:5px;">
					<span class="input-group-btn">
						<button type="submit" class="btn btn-primary pull-right" name="submit" style="margin-top:0px; margin-right:0px; height:33px; padding-bottom:28px; font-size:16px;"><i class="fa fa-search"></i> Search</button>
				</span>
				</div> -->

				<!-- <div class="input-group" style="width: 100%;">
					<input type="text" placeholder="Question Text..." name="search" style="width:75%; padding:5px;" >

					<select name="subject_id" id="subject_id" style="width:25%; padding:3.4px;">
						<option selected>Subject...</option>
						<?php
							foreach ($subjects as $subject) {
								echo '<option value="'.$subject->id.'">'.$subject->name.'</option>';
							}
						?>
					</select>			
					
					<span class="input-group-btn">
						<button type="submit" class="btn btn-primary pull-right" name="submit" style="margin-top:0px; margin-right:0px; height:33px; padding-bottom:28px; font-size:16px;"><i class="fa fa-search"></i> Search</button>
					</span>
				</div> -->

				<div class="row">
					<div class="col-lg-12" style="margin-bottom:20px;margin-top:20px;">
						<div class="input-group">
							<input type="text" class="form-control" name="search" placeholder="Question Text / Topics / Tag">
							<span class="input-group-addon">
								<select name="search_type" class="selectpicker form-control" id="search_type" style="min-width: 175px;">
									<option value="question_text" selected>Question Text</option>
									<option value="topic">Topics</option>
									<option value="tag">Tags</option>
								</select>	
							</span>
							<span class="input-group-btn">
								<button type="submit" class="btn btn-no-margin btn-default pull-right" name="submit"><i class="fa fa-search"></i> Search</button>
							</span>
						</div><!-- /input-group -->

						<!-- <div class="input-group">
							<input type="text" class="form-control" placeholder="Question Text / Topics / Tag">
							<span class="input-group-btn">
								<button type="submit" class="btn btn-no-margin btn-primary pull-right" name="submit"><i class="fa fa-search"></i> Search</button>
							</span>
						</div> -->
					</div><!-- /.col-lg-6 -->
				</div><!-- /.row -->

				<input type="hidden" name="subject_id" id="subject_id" value="0">
				<input type="hidden" name="level_id" id="level_id" value="0">
			</form>
				<div class="worksheet_div">
					<div class="worksheet_div_header">
						<i class="fa fa-minus pull-right fa_minimize_div"></i>
						<h4>Public Question</h4>
					</div>
					<div class="worksheet_div_body">
						<div class="table-responsive">
							<table class="table worksheet_table">
								<thead><tr class="success">
									<th>No</th>
									<th>Subject</th>
									<th>Question Text</th>
									<th>Tags</th>
									<th>Level</th>
									<th>Category</th>
									<th>Difficulty</th>
									<th>Action</th>
								</tr></thead>
								<tbody>
								<?php
									$count = 0;
									if (count($question) == 0) {
										echo '<div class="student-row clearfix">';
											echo '<div class="col-xs-offset-1 col-sm-offset-1 col-md-offset-1 col-lg-offset-1 col-xs-10 col-sm-10 col-md-10 col-lg-10">';
												echo '<div class="alert alert-danger margin-top-custom text-center">Don\'t have any outstanding question issues to be attended.</div>';
											echo '</div>';
										echo '</div>';
									} else {
										if($this->uri->segment(7)){
											$i = $this->uri->segment(7) + 1;
										} else if ($this->uri->segment(6)) {
											$i = 1;
										} else {
											$i = $this->uri->segment(3) + 1;
										}
										
										foreach ($question['question']->result() as $question_issue) {

											switch ($question_issue->subject_type) {
												case "1":
													$subject = "primary-english";
													$subject_text =  "Primary English";
													break;
												case "2":
													$subject = "primary-math";
													$subject_text =  "Primary Math";
													break;
												case "3":
													$subject = "primary-science";
													$subject_text =  "Primary Science";
													break;
												case "4":
													$subject = "secondary-english";
													$subject_text =  "Secondary English";
													break;
												case "5":
													$subject = "secondary-math";
													$subject_text =  "Secondary Math";
													break;
												case "6":
													$subject = "secondary-science";
													$subject_text =  "Secondary Science";
													break;
												}

											if($question_issue->subject_type == 1 && (BRANCH_NAME == 'SmartJen' || BRANCH_NAME == 'Prototype')){
												$question_page = 'edit_question';
											} else {
												$question_page = 'question';
											}
												
											echo '<tr>';
											// echo '<td>' . $question_issue->question_id . '</td>';
											echo '<td class="text-center">'. $i .'</td>';
											echo '<td class="text-center">' . $subject_text . '</td>';
											echo '<td>' . $question_issue->question_text. '</td>';
											echo '<td>' . $question['question_tag'][$count]. '</td>';
											echo '<td class="text-center">' . $question['level'][$question_issue->level_id]. '</td>';
											echo '<td>' . $question['category'][$question_issue->topic_id]. '</td>';
											echo '<td class="text-center">' . $question['difficulty'][$question_issue->difficulty] . '</td>';
											echo '<td><a href="'. base_url().'administrator/'.$question_page.'/'.$subject.'/'.$question_issue->question_counter.'" target="_blank" class="btn btn-warning">Edit</a>';
											echo '</tr>';
											$i++;
											$count++;
										}
										// echo $test;
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

<script>

	$(document).on('click', '.btnSubject', function (e) {
        e.preventDefault();
        $('.btnSubject').removeClass('bg-light');
        $('.btnSubject').removeClass('shadow-1');
        $(this).addClass('bg-light');
		$(this).addClass('shadow-1');
    })

	// function setSubject(subject_id) {
	// 	window.subject_id = subject_id;
	// 	document.getElementById("subject_id").value = subject_id;
	// }

    $(document).on('click', '.btnLevel', function (e) {
        e.preventDefault();
        $('.btnLevel').removeClass('active');
        $(this).addClass('active');
        subject_type = $(this).data('subject');
		level_id = $(this).data('level');

		document.getElementById("subject_id").value = subject_type;
		document.getElementById("level_id").value = level_id;
        // $('.row_topic_strategy').show();
	})	

	function getLevel(subject_name, subject_id) {
		// window.subject_id = subject_id;
		// document.getElementById("subject_id").value = subject_id;

		// reset level_id
		document.getElementById("level_id").value = 0;

		window.subject_name = subject_name;
		$.ajax({
			type: 'GET',
			url: base_url + 'administrator/getlevelbysubject/' + subject_name,
			dataType: 'json',
			success: function (res) {
				$('.primary_level, .secondary_level').html('');
				var content = ``;
				if (res.length > 0) {
					for (i = 0; i < res.length; i++) {
						var str = res[i].level_name;
						content = `
							<button class="btn btn-square shadow-sm btn-outline-light-dark mr-2 mb-3 btnLevel" data-subject="`+ res[i].subject_type + `" data-level="` + res[i].level_id + `">
								`+ res[i].level_name + `
							</button>
						`;

						if (str.search('Primary') >= 0) {
							$('.primary_level').append(content);
						} else if (str.search('Secondary') >= 0) {
							$('.secondary_level').append(content);
						}

					}
					$('.row_level').show();
					// $('.row_school').show();
				} else {
					$('.row_level').hide();
					// $('.row_school').hide();
				}
			}
		});
	}
</script>
