

    <div class="container pt-40 pb-40">

	<div class="row">
		<div class="col-lg-12 col-md-12 col-sm-12 text-center" style="margin-bottom:5px;">
			<?php 
				if($total_rows == 0){
					echo "<span class='text-default total_result_question'>Question not found</span>
							<br><br>
							<div class='fs-3x'><i class='picons-thin-icon-thin-0039_smiley_sad_face_unhappy'></i></div>
					";
				}else{
					//echo "<span class='text-default total_result_question'>Question $total_rows results</span>";
				}
				
				// if want to show execute time (".number_format($duration,2)." seconds)
			?>
		</div>
	</div> 

    <!-- <div class="grid"> -->
	<div class="card-columns">
	<?php 
		$quesNum = 1; 
		foreach($data as $row) : ?>
		<div class="card card_question_<?= $row->question_id ?>">
			<div class="card-header-small">
				<h5 class="card-title card_question_title fs14" id="card_question_title_<?= $row->question_id ?>">
					<?php						

						$label_text = $row->level_name;						
						$subject_name = str_replace(['Primary ','Secondary '], ['',''], $row->subject_name);
						$label_text = $label_text.' '.$subject_name;

						if($subject_name == 'Maths'){
							$label_class = 'label label-warning';
						}else if($subject_name == 'English'){
							$label_class = 'label label-primary';
						}else if($subject_name == 'Science'){
							$label_class = 'label label-danger';
						}

						$title = $label_text.$row->substrand_name.$row->topic_name;
						if(strlen($title) > 35){
							$card_title = substr($row->substrand_name.' / '.$row->topic_name, 0, 34).'...';
						}else{
							$card_title = $row->substrand_name.' / '.$row->topic_name;
						}
						
					?>
					<span class="<?= $label_class ?> mr-2"><?= $label_text ?></span><span class="label label-default"><?= $row->substrand_name ?></span>
					
				</h5>								
				<a class="btnExpand icon_expand" data-id="<?= $row->question_id ?>" data-title="<?= $card_title ?>" data-class="<?= $label_class ?>" data-label="<?= $label_text ?>" data-substrand="<?= $row->substrand_name ?>">
					<i class="fa fa-caret-down"></i>
				</a>				
			</div>			
			<div class="card-body-small fs14 card_question_text">   
				<?php
										
					if(count($row->has_instruction) > 0){
						foreach ($row->has_instruction as $questionInstruction) {
							
							if($questionInstruction->content_type == 'text'){
								echo $questionInstruction->header_content;
								echo '<br>';
							} else {
								echo '<img src="'.base_url().'img/instructionImage/'.$questionInstruction->header_content.'" draggable="false" class="img-responsive" style="display: block; margin: 0 auto;">';
								echo '<br>';
							}
						}
					}
					
					
					if(count($row->has_article) > 0){
						foreach ($row->has_article as $questionArticle) {
							
							if($questionArticle->content_type == 'text'){
								echo $questionArticle->header_content;
								echo '<br>';
							} else {
								echo '<img src="'.base_url().'img/articleImage/'.$questionArticle->header_content.'" draggable="false" class="img-responsive" style="display: block; margin: 0 auto;">';
								echo '<br>';
							}
						}
					}					

				?>
				<div class="card_question_body">
				<?php 

					// Answer List FITB
					if($row->question_type_id == 5){
						$listAnswers = '<div class="p-1 border2"><ul style="list-style-type:none; padding-left: 0;" class="list_four_column">';
						$answerOption = $answerList[$quesNum-1]['answerOption'];
						foreach ($answerOption as $option) {							
							$listAnswers .= '<li>'.$option->answer_text.'</li>';									
						}
						$listAnswers .= '</ul></div>';
						echo $listAnswers.'<br>';
					}

					// Question List
					if($row->question_content == 0){
						if($row->question_type_id == 5 || $row->question_type_id == 6){
							$string = $row->question_text; 
							$question = str_replace('<ans>', '[___]<span>', $string);
							$question = str_replace('</ans>', '</span></span>', $question);
							$array = explode('[___]', $question);

							$questions = "";

							for($x=0 ; $x<count($array) ; $x++){

								if($x == count($array)-1){
									$questions .= $array[$x];
								}else{
									$questions .= $array[$x].'<span style="display: inline; border-bottom:1px solid;">('.($x+1).') ';
								}

							}

							echo $questions;

						}else{
							echo $row->question_text;
						}						
										
						if ($row->graphical != "0") {
							echo '<img src="'.$row->branch_image_url.'/questionImage/'.$row->graphical.'" draggable="false" class="img-responsive" style="display: block; margin: 0 auto;">';
						}
					}else{
						if($row->content_type == 'text'){
							if($row->question_type_id == 5 || $row->question_type_id == 6){
								$string = $row->question_text; 								
							}else{
								$string = $row->question_text;
							}
						} else {
							echo '<img src="'.$row->branch_image_url.'/questionImage/'.$row->question_text.'" draggable="false" class="img-responsive" style="display: block; margin: 0 auto;">';
						}

						$questionContents = $questionContents[$quesNum-1]['question_content'];

						foreach ($questionContents as $questionContent) {
							
							if($questionContent->content_type == 'text'){
								if($row->question_type_id == 5 || $row->question_type_id == 6){
									$string .= $questionContent->content_name; 
									$question = str_replace('<ans>', '_____<span>', $string);
									$question = str_replace('</ans>', '</span></span>', $question);
									$array = explode('_____', $question);
		
									$questions = "";
		
									for($x=0 ; $x<count($array) ; $x++){
		
										if($x == count($array)-1){
											$questions .= $array[$x];
										}else{
											$questions .= $array[$x].'<span style="display: inline; border-bottom:1px solid;">('.($x+1).') ';
										}
		
									}
		
									echo $questions;
		
								}else{
									echo $questionContent->content_name;
								}								
								echo '<br>';
							} else {
								echo '<img src="'.$row->branch_image_url.'/questionImage/'.$questionContent->content_name.'" draggable="false" class="img-responsive" style="display: block; margin: 0 auto;">';
								echo '<br>';
							}
						}
					}

					// Answer List

					if ($row->question_type_id == 1 || $row->question_type_id == 4 || $row->question_type_id == 8) {	
						$answerOption = $answerList[$quesNum-1]['answerOption'];
						echo "<div class='row pt-10'>";
						foreach ($answerOption as $option) {
							
							$correctAnswer = $answerList[$quesNum-1]['correctAnswer'];							
							$checked = "";
							if($correctAnswer == $option->answer_text) {
								$checked = "checked";
							}

							echo '	<div class="col-lg-12">
										<div class="form-inline">
											<div class="customUi-radio radioUi-success mr-1">
												<input type="checkbox" readonly '.$checked.'>
												<label>
													<span class="label-radio"></span>												
												</label>
											</div>
											<span class="fs14 font300">'.$option->answer_text.'</span>
										</div>
									</div>';									
						}
						echo "</div>";
						
					}else if($row->question_type_id == 2){

						$answerOption = $answerList[$quesNum-1]['answerOption'];
						foreach ($answerOption as $key=>$option) {
							$correctAnswer = $answerList[$quesNum-1]['correctAnswer'];
							$class = "";
							$icon = "";
							if($correctAnswer == $option->answer_text) {
								$class .= "correctAnswer ";
								$icon .= "<i class='fa fa-check answeredCorrectly'></i>";
								echo '<br><span class="'.$class.' fs14">Ans: ' . $option->answer_text . '</span>';
							}
						}
						
					}else if($row->question_type_id == 3){
						$answerOption = $answerList[$quesNum-1]['answerOption'];						
						echo "<div class='row pt-10'>";						
						foreach ($answerOption as $option) {
							echo '<div class="col-lg-12">
									<div class="form-inline">
										<div class="customUi-radio radioUi-success mr-1">
											<input type="radio" readonly '.(($option->answer_text==1)?'checked':'').'>
											<label>
												<span class="label-radio"></span>												
											</label>
										</div>
										<span class="fs14 font300 mr-4">True</span>

										<div class="customUi-radio radioUi-danger mr-1">
											<input type="radio" readonly '.(($option->answer_text==0)?'checked':'').'>
											<label>
												<span class="label-radio"></span>												
											</label>
										</div>
										<span class="fs14 font300">False</span>
									</div>
								</div>';									
						
						}
						echo "</div>";
					}

					$quesNum++;
				?>
				</div>
			</div>
			<div class="card-footer text-muted text-right">
				<div class="fs14 float-left">
					<?php 
						echo '<span class="marks mr-3">'.$row->difficulty.' Marks</span>'; 
						
						if($row->total_question > 1){						
							echo '<a class="cursor_pointer prev_question" data-id="'.$row->question_id.'"><i class="fa fa-angle-double-left"></i></a>';
							echo '<input type="text" class="label_total_question" class="ml-2 mr-2" style="width: 30px; background: transparent; text-align: center; border: 0;" value="1/'.$row->total_question.'" readonly>';
							echo '<input type="hidden" class="total_question" value="'.$row->total_question.'">';
							echo '<input type="hidden" class="page_question" value="1">';
							echo '<a class="cursor_pointer next_question" data-id="'.$row->question_id.'"><i class="fa fa-angle-double-right"></i></a>';
						}
					?>				
				</div>

				<a class="cursor_pointer text-teal font500 mr-2" title="Preview"><i class="picons-thin-icon-thin-0043_eye_visibility_show_visible"></i></a>
                <a class="cursor_pointer text-warning font500 mr-2" title="Edit"><i class="picons-thin-icon-thin-0001_compose_write_pencil_new"></i></a>
                <!-- <a class="text-danger font500" title="Delete"><i class="picons-thin-icon-thin-0056_bin_trash_recycle_delete_garbage_empty"></i></a> -->
            </div>
		</div>			
	<?php endforeach; ?>
	</div>
<!-- </div> -->

<div class="pagination-container text-center">
	<?= $pagination; ?>
</div>

</div>

