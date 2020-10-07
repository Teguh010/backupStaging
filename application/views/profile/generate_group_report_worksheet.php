<doctype! html>
<body>
<style>


.student-name{
	text-align:center;
	height: 20em;
	width:5%;
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
	font-size:10px;
}

@page {
           
		   footer: html_MyCustomFooter; /* display <htmlpagefooter name="MyCustomFooter"> on all pages */
	   }

</style>

<htmlpagefooter name="MyCustomFooter">
        <table style="vertical-align: bottom; font-family: serif; font-size: 8pt; color: #000000; font-style: italic;" width="100%">
            <tbody>
                <tr>
                    
                    <td style=" font-style: italic;" align="right" width="50%">{PAGENO}</td>
                    <td style="text-align: right;" width="50%">Worksheet Group Report</td>
                </tr>
            </tbody>
        </table>
    </htmlpagefooter>
<?php if(isset($entered_new)){?>
	<div class="worksheet_div_header">
		<i class="fa fa-minus pull-right fa_minimize_div"></i>
		<h3>My Group Report</h3>
		<h4>Worksheet: <?php echo $entered_new['worksheet_name'];?></h4>
		<!-- <a href="<?php echo base_url(); ?>smartgen" class="btn btn-custom-light">Create New Worksheet</a>
		<a href="<?php echo base_url(); ?>smartgen/archiveWorksheetList" class="btn btn-custom-light">Archived Worksheet</a> -->
	</div>
	<div class="worksheet_div_body">
		<div class="">
			<table class=""  style="overflow: wrap;width: 100%"  autosize="0">
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
<?php } ?>

<script type="text/javascript">

var fastselect ;

	$(document).ready(function(){

	
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
			var selected = false;
			$(".multipleSelect").each(function(){

				
				var selectedValues = $(this).val();
				console.log(selectedValues);
				if(selectedValues == null){
					alert('Please select students'); return false;
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

		$('#btnPDF').on('click',function(){
			$('#compareForm').append('<input type="hidden" name="gen_pdf" value = "1">');
			$('#btnCompare').trigger('click');
		});

		fastselect = $('.multipleSelect').fastselect();

	});

</script>
</body>
</html>