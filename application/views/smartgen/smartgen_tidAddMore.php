<div class="row">
	<div class="col-lg-12 col-md-12 col-sm-12 text-center" style="margin-bottom:5px;">
		<?php 
			if($total_rows == 0){
				echo "<span class='text-default total_result_question'>Question not found</span>";
			}else{
				echo "<span class='text-default total_result_question'>Question $total_rows results</span>";
			}
			
			// if want to show execute time (".number_format($duration,2)." seconds)
		?>
	</div>
</div>  

<!-- <div class="grid"> -->
	<div class="card-columns">
	<?php if($total_rows > 0) foreach($data as $row) : ?>
		<div class="card card_question_<?= $row->question_id ?>">
			<div class="card-header-small">
				<h5 class="card-title card_question_title fs14" id="card_question_title_<?= $row->question_id ?>">
					<?php 
						// $title = $row->substrand_name.' / '.$row->topic_name;
						// if(strlen($title) > 35){
						// 	$card_title = substr($row->substrand_name.' / '.$row->topic_name, 0, 34).'...';
						// 	echo substr($row->substrand_name.' / '.$row->topic_name, 0, 34).'...';
						// }else{
						// 	$card_title = $row->substrand_name.' / '.$row->topic_name;
						// 	echo $row->substrand_name.' / '.$row->topic_name;
						// } 
						echo $title = $this->model_worksheet->get_worksheet_topics_tid(array($row->tid_topic_id)) .' / '.$this->model_worksheet->get_worksheet_ability_tid(array($row->ability));
						$card_title = $title;
					?>
					
				</h5>				
				<a class="fa-caret icon_expand" data-id="<?= $row->question_id ?>" data-title="<?= $card_title ?>">
					<i class="fa fa-caret-down"></i>
				</a>				
			</div>
			<div class="card-body-small card_question_information fs14" id="card_question_information<?= $row->question_id; ?>">
				
			</div>
			<div class="card-body-small card_question_text" data-id="<?= $row->question_id; ?>" style="cursor: pointer;">      			
				<?= $row->question_text ?>			
			</div>			
			<?php
				if ($row->graphical != "0") {
					echo '<div class="card-img-bottom text-center"><img src="'.$row->branch_image_url.'/questionImage/'.$row->graphical.'" draggable="false" class="img-responsive" style="display: block; margin: 0 auto;"></div>';
				}
			?>
		</div>			
	<?php endforeach; ?>
	</div>
<!-- </div> -->

<div class="pagination-container text-center">
	<?= $pagination; ?>
</div>