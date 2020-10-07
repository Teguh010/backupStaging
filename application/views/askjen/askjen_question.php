<div class="section">
	<div class="container">
		<?php
			if (isset($comment_error)) {
				echo '<div class="alert alert-danger fade_out_div">'. $comment_error . '</div>';
			} elseif (isset($comment_message)) {
				echo '<div class="alert alert-success fade_out_div">' . $comment_message . '</div>';
			}
		?>
		<div class="col-sm-12 col-md-12 col-lg-12">
			<div class="question clearfix">
				<div class="page-header">
					<h3>
						Question <?=$question_id?>
						<small>posted at <?php echo date('Y-m-d', strtotime($question->insert_time)); ?></small>
					</h3>
				</div>
				<div class="">
					<h4><?=$question->question_text?></h4>
					<?php
						if ($question->graphical != "0") {
							echo '<div><img src="'.base_url().'img/questionImage/'.$question->graphical.'" class="img-responsive"></div>';
						}
					?>
				</div>

				<div class="question-info">
					<div class="question-tag-group pull-left">
						<a href="<?=$question->category_url?>" class="askjen_category_url">
							<span class="question-tag"><?=$question->category_name?></span>
						</a>
					</div>
					<!-- <div class="question-user-info pull-right">
						asked 50 secs ago by <a href="">Jeff</a>
					</div> -->
				</div>
			</div>
			

			<div class="answers">
				<div class="page-header">
					<h3>
						Comments
					</h3>
				</div>
				<?php 
					if (count($comments) == 0) {
						echo '<div class="alert alert-danger alert-no-margin text-center">';
						echo 'No comment yet';
						echo '</div>';
					} else {
						foreach ($comments as $comment) {
						?>
							<div class="answer-detail clearfix">
								<?=$comment->comment?>

								<div class="answer-info">
									<div class="answer-user-info pull-right">
										commented by <a href=""><?=$comment->username?></a> at <?php echo date('Y-m-d', strtotime($comment->comment_date)); ?>
									</div>
								</div>
							</div>

						<?php
						}
					}
				?>
				
			</div>
			
		</div>


		
	</div>
</div>


<div class="section">
	<div class="container">
		<div class="col-sm-12 col-md-12 col-lg-12">
			<div class="page-header">
				<h3>
					Your comment
				</h3>
			</div>
			<?php 
				if ($isLoggedIn) {
					$this->load->helper('form');
				?>
					<form class="form-horizontal" action="<?php echo current_url();?>" method="post" accept-charset="utf-8">
						
						<div class="form-group <?=form_error('question_comment')?'has-error':''?>">
							<div class="col-sm-8 col-md-8 col-lg-8">
								<textarea name="question_comment" id="question_comment" placeholder="Comments?" class="form-control" rows="10" value="<?=validation_errors()?set_value('question_comment'):''?>"></textarea>
							</div>
						</div>
						<div>
							<input type="submit" class="btn btn-custom" value="Post comment" id="comment_btn">
						</div>
					</form>
				<?php
				} else {
					echo '<div class="alert alert-danger alert-no-margin text-center">';
					echo 'To comment, please <a href="'.base_url().'askjen/login/?url='.current_url().'">login</a> first';
					echo '</div>';
				}

			?>
		</div>
	</div>
</div>