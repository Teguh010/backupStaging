<style>

.daterange{
	margin:11px;
}


.fstElement{
	width:100%;
	font-size: 0.7em !important;
	margin:1em;
}
.student-name{
	text-align:center;
	height: 20em;
	width: 5%;
  white-space: nowrap;
}
.student-name > div {
  transform: 
    /* Magic Numbers */
    translate(25px, 51px)
    /* 45 is really 360 - 45 */
    rotate(270deg);
  width: 30px;
}
.student-name > div > span {
	margin-left: -12em;
}

.student-data{
	text-align:center;
}
</style>
<div class="section">

	<div class="container">

		<div class="row">
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
				<h2 class="text-center">Group Report</h2>
			</div>

			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="padding-top: 2em;">

				<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
					<div id="view_daterange" class="daterange" style="background: #fff; cursor: pointer; padding: 10px; border: 1px solid #ccc; width: 100%;height: 45px;">
						<i class="fa fa-calendar"></i>&nbsp;
						<span></span> <i class="fa fa-caret-down"></i>
					</div>
				</div>

				<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
					<div id="view_daterange2" class="daterange" style="background: #fff; cursor: pointer; padding: 10px; border: 1px solid #ccc; width: 100%;height: 45px;">
						<i class="fa fa-calendar"></i>&nbsp;
						<span></span> <i class="fa fa-caret-down"></i>
					</div>
				</div>


				<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
					<select class="multipleSelect" multiple name="students" id="students" placeholder="Choose Students or Groups " required>
						<?php 	$selected = '';
								foreach($entered['selected_student'] as $item){
									if($item == "all_students"){
										$selected = 'selected';
									}
								} ?>
						<option value="all_students" <?php echo $selected; ?>>All Students</option>
						<?php 
							$get_tutor_group = $this->model_users->get_group($this->session->userdata('user_id'));
							foreach ($get_tutor_group as $key => $value) {
								$check = $this->model_users->get_student_assign_group($this->session->userdata('user_id'),$value['id'],"sum");
								if($check > 0) {
									$selected = '';
									foreach($entered['selected_student'] as $item){
										if($item == "group_".$value['id'] ){
											$selected = 'selected';
										}
									}
							?>
								<option value="group_<?php echo $value['id']?>" <?php echo $selected; ?>><?php echo $value['group_name']?></option>
							<?php } }?>
						<?php
							if(isset($student_list)){

								foreach($student_list as $student){
									$selected = '';
									foreach($entered['selected_student'] as $item){
										if($item == $student->student_id ){
											$selected = 'selected';
										}
									}
									?>
									<option value = "<?php echo $student->student_id; ?>" <?php echo $selected; ?>><?php echo $student->fullname?></option>
									<?php
								}
							}
						
						?>
					</select>
				</div>

				<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
					<select class="form-control" onchange="showWorksheetList(this.value)" name="select_type" id="select_type" style="height: 48px;padding: 5px;margin: 11px;">
						<option value="performance" <?php echo ($select_view=="performance") ? "selected" : ""; ?>>View Performance</option>
						<option value="worksheet" <?php echo ($select_view=="worksheet") ? "selected" : ""; ?>>View Worksheet</option>
					</select>
				</div>

				<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 clear_line">
					<select class="form-control" name="worksheet" id="worksheet" style="height: 48px;padding: 5px;margin: 11px;">
						<?php
							if(isset($worksheet_list)){

								foreach($worksheet_list as $worksheet){
									$selected = ($entered['selected_worksheet']==$worksheet->worksheet_id) ? 'selected' : '';
									
									?>
									<option value = "<?php echo $worksheet->worksheet_id; ?>" <?php echo $selected; ?>><?php echo $worksheet->worksheet_name?></option>
									<?php
								}
							}
						?>
					</select>
				</div>
				<div style="clear: both;" class="clear_line"></div>
			<?php /* 
				<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 clear_line" style="padding-bottom: 0.5em;">
					<a id="btnAnalytics" href="javascript:void(0)" class=" btn btn-block btn-custom" style="height: 45px;padding: 11px 0;margin: 11px;"> Analytics</a>
				</div>
			*/ ?>
				<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12" style="padding-bottom: 0.5em;">
					<a id="btnCompare" href="javascript:void(0)" class=" btn btn-block btn-custom" style="height: 45px;padding: 11px 0;margin: 11px;"> Compare Between Date Range</a>
				</div>

				<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
					<a id="btnPDF" href="javascript:void(0)" class=" btn btn-block btn-custom" style="height: 45px;padding: 11px 0;margin: 11px;"> Generate PDF</a>
				</div>

				
				<form id="compareForm" action="<?php echo base_url();?>profile/group_report" method="POST">
					<input type="hidden" name="dr_start" />
					<input type="hidden" name="dr_end" />

					<input type="hidden" name="dr2_start" />
					<input type="hidden" name="dr2_end" />


				</form>

			</div>


		

		</div>

	</div>

</div>
<?php if(isset($entered_new)){
	if($select_view=="performance") { ?>
<span class="anchor" id="worksheet_div"></span>
<div class="section">
	<div class="container">
		<div class="row">
			<div class="col-sm-12 col-md-12 col-lg-12">
				<div class="worksheet_div">
					<div class="worksheet_div_header">
						<i class="fa fa-minus pull-right fa_minimize_div"></i>
						<h4>My Group Report</h4>
						<!-- <a href="<?php echo base_url(); ?>smartgen" class="btn btn-custom-light">Create New Worksheet</a>
						<a href="<?php echo base_url(); ?>smartgen/archiveWorksheetList" class="btn btn-custom-light">Archived Worksheet</a> -->
					</div>
					<div class="worksheet_div_body">
						<div class="table-responsive">
							<table class="table table-responsive table-bordered">
								<tr>
									<th style="width:50%">Subjects</th>
									<?php 
										
										foreach($entered_new['selected_student'] as $sel_student){
											foreach($student_list as $student){
													if($student->student_id == $sel_student){
												?>
													<th class="student-name"><div><span><?php echo $student->fullname?></span></div></th>
												<?php
													}
											}
										?>
									<?php } ?>
								</tr>
								<?php foreach($analysis_structure as $structure){
										$substrand = $structure['substrand'];
										foreach($substrand as $sub_item){
											$category = $sub_item['category'];

											foreach($category as $cat_item){
									?>
									<tr>
										<td><b><?php echo $sub_item['name'].' </b><br/> '.$cat_item['name']; ?></td>

										<?php foreach($entered_new['selected_student'] as $sel_student){
												if(isset($performance1[$sel_student])){
													$myperformance = $performance1[$sel_student][$structure['name']][$sub_item['name']][$cat_item['name']];
													//array_debug($myperformance);exit;
												}

												if(isset($performance2[$sel_student])){
													$myperformance2 = $performance2[$sel_student][$structure['name']][$sub_item['name']][$cat_item['name']];
													//array_debug($myperformance);exit;
												}
											?>
											<td class="student-data"><?php echo $myperformance['percentage'].'%<br/>'. $myperformance2['percentage'].'%'; ?></td>
											<?php
												
										}?>
									</tr>
									<?php
											}
								}
								}?>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<?php } else { ?>
<span class="anchor" id="worksheet_div"></span>
<div class="section">
	<div class="container">
		<div class="row">
			<div class="col-sm-12 col-md-12 col-lg-12">
				<div class="worksheet_div">
					<div class="worksheet_div_header">
						<i class="fa fa-minus pull-right fa_minimize_div"></i>
						<h4>My Group Report | Worksheet: <?php echo $entered_new['worksheet_name'];?></h4>
					</div>
					<div class="worksheet_div_body">
						<div class="table-responsive">
							<table class="table table-responsive table-bordered">
								<?php 
									$questions = $this->model_worksheet->get_questions_id_from_worksheets_id($entered_new['selected_worksheet']);
									$questionsArray = $questions->result(); ?>
								<tr style="background-color: #DFF0D8;">
										<th >
											
										</th>
										<?php
										$quesNum = 1; $totQ = array();
										foreach ($questionsArray AS $question) { 
											$totQ[$quesNum] = 0;
											?>
											<th style="text-align: center;">
											<?php echo 'Q'.$quesNum; ?>
											</th>
										<?php $quesNum++;} ?>
										<th style="text-align: center;">Total</th>
								</tr>
								<?php	
									$worksheet_id = $entered_new['selected_worksheet'];
									$totStudent = 0; $i = 1;
									foreach($entered_new['selected_student'] as $sel_student){
										foreach($student_list as $student){
												if($student->student_id == $sel_student){
													$totStudent++;
													$tr_style = ($i%2==0) ? "style='background-color:#F5F5F5;'" : "";
											?>	
											<tr <?php echo $tr_style?>>
												<td >
													<b><?php echo $student->fullname?></b>
												</td>
												<?php
												$quesNum = 1; $total_score = 0;
												foreach ($questionsArray AS $question) { 
													$question_id = $question->question_id;
													$student_id = $student->student_id;
													$questionScore = $this->model_users->get_quiz_score($student_id,$worksheet_id,$quesNum);
													$total_score += $questionScore;
													$totQ[$quesNum] += $questionScore;
													$color = ($questionScore==0) ? "#E62A38" : "#12AD2A";
													$color = ($questionScore>0 && $questionScore<2) ? "#FFA135" : $color;
													?>
													<td style="text-align: center;background-color: <?php echo $color;?>;color: #FFFFFF;width: 65px;"><?php echo $questionScore;?></td>

											<?php $quesNum++; } ?>
												<td style="text-align: center;width: 65px;"><?php echo $total_score;?></td>
											</tr>
											<?php
												$i++; }
											}
									} ?>
								<tr style="background-color: #DFF0D8;">
									<th >
										Successful Attempts
									</th>
									<?php
									$quesNum = 1; $totQuestion = count($questionsArray);
									$total_percen = 0;
									foreach ($questionsArray AS $question) { 
										$percen = ($totQ[$quesNum]>0) ? (($totQ[$quesNum]/2) / $totStudent)*100 : 0 ;
										?>
										<th style="text-align: center;">
										<?php echo number_format($percen,2).'%'; ?>
										</th>
									<?php 
									$total_percen += $percen;
									$quesNum++;} ?>
									<th style="text-align: center;"><?php echo ($total_percen>0) ? number_format(($total_percen/$totQuestion),2) : 0;?>%</th>
								</tr>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<?php } } ?>





<script type="text/javascript">
function showWorksheetList(type) {
	if(type=="worksheet") {
		$(".clear_line").show();
		$(".show_line").hide();
		$('#students').prop('selectedIndex',0);
		$('#students').data('fastselect').setSelectedOption($('.multipleSelect option[value=all_students]').get(0));
	} else {
		$(".clear_line").hide();
		$(".show_line").show();
	}
}

var fastselect ;

	$(document).ready(function(){
		var type = '<?php echo $select_view;?>';
		if(type=="worksheet") {
			$(".clear_line").show();
			$(".show_line").hide();
		} else {
			$(".clear_line").hide();
			$(".show_line").show();
		}
		var start = moment().subtract(29, 'days');
		var end = moment();

		
		function view_daterange_cb(start, end) {
			$('#view_daterange span').html(start.format('YYYY-MM-DD') + ' - ' + end.format('YYYY-MM-DD'));
			$('input[name="dr_start"]').val(start.format('YYYY-MM-DD'));
			$('input[name="dr_end"]').val(end.format('YYYY-MM-DD'));
		}

		function view_daterange2_cb(start, end) {
			$('#view_daterange2 span').html(start.format('YYYY-MM-DD') + ' - ' + end.format('YYYY-MM-DD'));
			$('input[name="dr2_start"]').val(start.format('YYYY-MM-DD'));
			$('input[name="dr2_end"]').val(end.format('YYYY-MM-DD'));
		}

		$('#view_daterange').daterangepicker({
			startDate: start,
			endDate: end,
			ranges: {
			'Today': [moment(), moment()],
			'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
			'Last 7 Days': [moment().subtract(6, 'days'), moment()],
			'Last 30 Days': [moment().subtract(29, 'days'), moment()],
			'This Month': [moment().startOf('month'), moment().endOf('month')],
			'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
			}
		}, view_daterange_cb);

		$('#view_daterange2').daterangepicker({
			startDate: start,
			endDate: end,
			ranges: {
			'Today': [moment(), moment()],
			'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
			'Last 7 Days': [moment().subtract(6, 'days'), moment()],
			'Last 30 Days': [moment().subtract(29, 'days'), moment()],
			'This Month': [moment().startOf('month'), moment().endOf('month')],
			'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
			}
		}, view_daterange2_cb);


		<?php if(isset($entered['date_range'])){
			?>
			view_daterange_cb(moment('<?php echo date('Y-m-d',strtotime($entered['date_range'][0])); ?>'),moment('<?php echo date('Y-m-d',strtotime($entered['date_range'][1])); ?>'));
			view_daterange2_cb(moment('<?php echo date('Y-m-d',strtotime($entered['date_range2'][0])); ?>'),moment('<?php echo date('Y-m-d',strtotime($entered['date_range2'][1])); ?>'));
			<?php
		}else {?>

		view_daterange_cb(start, end);
		view_daterange2_cb(start, end);
		<?php }?>

		$('#btnCompare').on('click',function(){
			var selectedValues = $("#worksheet").val();
			var selectedText = $("#worksheet option:selected").text();

			$('#compareForm').append('<input type="hidden" name="worksheet_selected" value = "'+selectedValues+'">');
			$('#compareForm').append('<input type="hidden" name="worksheet_name" value = "'+selectedText+'">');
				
			var typeValues = $("#select_type").val();
			$('#compareForm').append('<input type="hidden" name="select_view" value = "'+typeValues+'">');
			var selected = false;
			$(".multipleSelect").each(function(){
				var selectedValues = $(this).val();
				if(selectedValues.length==0){
					alert('Please select students or groups'); return false;
				}else{
					selected = true;
				}
				for(var i = 0;i < selectedValues.length; i ++){
					$('#compareForm').append('<input type="hidden" name="student_selected[]" value = "'+selectedValues[i]+'">');
				}

			});
			if(selected){
			$('#compareForm').submit();
			}
			
		});

		$('#btnAnalytics').on('click',function(){
			$('#btnCompare').trigger('click');
		});

		$('#btnPDF').on('click',function(){
			$('#compareForm').append('<input type="hidden" name="gen_pdf" value = "1">');
			$('#btnCompare').trigger('click');
		});

		fastselect = $('.multipleSelect').fastselect();
		// fastselect = $('.multipleSelect2').fastselect();

	});

</script>