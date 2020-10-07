<footer>
	<div class="container">
		<div class="row">
			<div class="col-sm-3 col-md-3 col-lg-3">
				<ul class="list-unstyled">
					<li class="list-title">Company</li>
					<li><a href="#">About Us</a></li>
					<li><a href="#">Blog</a></li>
					<li><a href="#">Contact</a></li>
				</ul>
			</div>

			<div class="col-sm-3 col-md-3 col-lg-3">
				<ul class="list-unstyled">
					<li class="list-title">Social</li>
					<li><a href="#">Facebook</a></li>
					<li><a href="#">Twitter</a></li>
					<li><a href="#">Google +</a></li>
				</ul>

			</div>
			<div class="col-sm-3 col-md-3 col-lg-3">
				<ul class="list-unstyled">
					<li class="list-title">Manage</li>
					<li><a href="#">My Account</a></li>
					<li><a href="#">Community</a></li>
					<li><a href="#">Help</a></li>
				</ul>
			</div>

			<div class="col-sm-3 col-md-3 col-lg-3 hidden-xs">
				<img src="<?php echo base_url(); ?>img/Smart-Jen-Logo-inverse.png" class="img-responsive">
			</div>
		</div>

		<div class="row">
			<div class="copyright text-center">
				Copyright &copy; SmartJen <?php echo date('Y') ?>. All rights reserved
			</div>
		</div>
	</div>

</footer>

<div id="smartjen_feedback_btn">
    <a href="#" class="btn btn-danger" data-toggle="modal" data-target="#feedbackModal"><i class="fa fa-comment-o"></i> Feedback </a>
</div>

<?php if($this->uri->segment(1) == 'smartgen' && in_array($this->uri->segment(2),array('generateWorksheet','generateWorksheets')) && $this->session->userdata('worksheetSubject') != 7){ if(isset($is_admin) && $is_admin == FALSE){ ?>
	<div class="smartjen_left_btn">
		<a href="#" class="btn btn-custom" data-toggle="modal" data-target="#addMoreModal"><i class="fa fa-plus-square"></i> More Questions </a>
	</div>
<?php } } else { if($this->session->userdata('user_id') == 121 && $this->uri->segment(1) == 'smartgen' && in_array($this->uri->segment(2),array('generateWorksheet','generateWorksheets'))) {?>
	<div class="smartjen_left_btn">
		<a href="#" class="btn btn-custom" data-toggle="modal" data-target="#addMoreModal"><i class="fa fa-plus-square"></i> More Questions </a>
	</div>
<?php } }?>

<style>
	.question_number.add_more {
		height:0px; 
		padding-top:0px;
		background-color: #fff;
	}
	
	.pull-left.add_more_lvl{
		margin-top:10px;
		margin-left:30px;
		margin-bottom:10px;
	}
	
	.pull-left.add_more_sub_top{
		margin-top:10px;
		margin-left:10px;
		margin-bottom:10px;
	}
	
	.pull-left.add_more_str {
		margin-top:10px;
		margin-left:10px;
		margin-bottom:10px;
	}

	.pull-left.add_more_difficulty {
		margin-top:10px;
		margin-left:10px;
		margin-bottom:10px;
	}
	
	.label {
		font-weight:500;
	}
	
	@media only screen and (max-width: 768px) {
		
		.question_number.add_more {
			height:60px; 
		}
	
		.pull-left.add_more_lvl{
			margin-bottom:5px;
			margin-left: 35px;
		}
	}

	@media only screen and (max-width: 576px) {
		
		.question_number.add_more {
			height:60px; 
		}
	
		.pull-left.add_more_lvl{
			margin-top:10px;
			margin-bottom:5px;
			margin-left: 10px;
		}
		
		
		.pull-left.add_more_sub_top{
			margin-top:10px;
			margin-left: 35px;
		}
		
		.pull-left.add_more_str {
			margin-top: 10px;
			margin-left: 35px;
		}

		.pull-left.add_more_difficulty {
			margin-top: 0px;
			margin-left: 35px;
		}

		.main-search {
			width: 80%
		}
	}

	@media only screen and (max-width: 600px) {
		
		.question_number.add_more {
			height:50px; 
		}
	
		.pull-left .add_more_lvl{
			margin-top:5px;
			margin-bottom:5px;
			margin-left: 10px;
			margin-right : 10px;
		}
		
		.pull-left.add_more_sub_top{			
			margin-left: 10px;
			margin-right : 10px;
			/* display: none; */
		}
		
		.pull-left.add_more_str {			
			margin-left: 10px;
			margin-right : 10px;
			/* display: none; */
		}

		.pull-left.add_more_difficulty {
			margin-top: 0px;
			margin-left: 100px;
			margin-right : 50px;
		}

		.main-search {
			width: 80%
		}
		
	}
	
</style>

<div class="modal fade" id="feedbackModal" role="dialog">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header modal-header-custom-alert">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title">Feedback for SmartJen</h4>
			</div>

			<form class="form-horizontal" action="<?php echo base_url(); ?>site/feedback" method="post" accept-charset="utf-8">
				<div class="modal-body">
					<div id="submit_feedback_error" class="alert alert-danger">
					</div>
					<div id="submit_feedback_success" class="alert alert-success">						
					</div>

					<p>Hi there! Thank you for using SmartJen. Please fill up the form below if you wish to request for a demo & collaboration, or if you have any feedbacks for us.</p>
					<div class="form-group">
						<label for="feedback_sender_name" class="control-label col-sm-4 col-md-4 col-lg-4">Your name:</label>
						<div class="col-sm-8 col-md-8 col-lg-8">
							<input type="text" name="feedback_sender_name" id="feedback_sender_name" placeholder="How can we address you?" class="form-control">
						</div>
					</div>

					<div class="form-group">
						<label for="feedback_sender_email" class="control-label col-sm-4 col-md-4 col-lg-4">Your email:</label>
						<div class="col-sm-8 col-md-8 col-lg-8">
							<input type="text" name="feedback_sender_email" id="feedback_sender_email" placeholder="abc@example.com" class="form-control">
						</div>
					</div>

					<div class="form-group">
						<label for="feedback_type" class="control-label col-sm-4 col-md-4 col-lg-4">Feedback Category:</label>
						<div class="col-sm-8 col-md-8 col-lg-8">
							<select name="feedback_type" id="feedback_type" class="form-control">
								<option value="General">General</option>
								<option value="Smartgen">Smartgen</option>
								<option value="Online Quiz">Online Quiz</option>
								<option value="Askjen">Askjen</option>
							</select>
						</div>
					</div>

					<div class="form-group">
						<label for="feedback_comment" class="control-label col-sm-4 col-md-4 col-lg-4">Comments:</label>
						<div class="col-sm-8 col-md-8 col-lg-8">
							<textarea name="feedback_comment" id="feedback_comment" placeholder="What do you think we can do better ?" class="form-control" style="width: 100%; padding: 0.5em"></textarea>
						</div>
					</div>
				</div>

				<div class="modal-footer">
					<input type="button" class="btn btn-default" data-dismiss="modal" value="Close">
					<button type="button" class="btn btn-custom" data-loading-text="<i class='fa fa-spinner fa-spin'></i> Submitting Feedback" id="submit_feedback_btn">Submit</button>
				</div>
			</form>
		</div>
	</div>
</div>

<!-- 
<div class="footer">
	<div class="container text-center">
		<p>Copyright &copy; by SmartJen <?php echo date('Y') ?>. All rights reserved</p>
	</div>
</div> -->
