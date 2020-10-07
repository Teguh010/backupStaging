<div class="grid">
		<?php 
			if(isset($questionList)){
					if(isset($selected_questions)){
						$sel_questions = $selected_questions;
					}else{
						$sel_questions  = array();
					}
					
					if(sizeof($questionList) == 0){
						echo 'There is no more questions available.';
					}
					
					foreach($questionList as $question){
						
						$selected = "";
						
						if(in_array($question->question_id,$sel_questions)){
							$selected = "selected";
						}
		?>
					<div class="grid-item <?php echo $selected; ?>" data-id="<?php echo $question->question_id; ?>">
						<?php 
							echo $question->question_text; 
						?>
					</div>
		<?php 
					}
			}
		?>
</div>

<div class="pagination-container text-center">
	<?= $pagination; ?>
</div>
