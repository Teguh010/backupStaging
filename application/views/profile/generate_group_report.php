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
                    <td style="text-align: right;" width="50%">Group Report</td>
                </tr>
            </tbody>
        </table>
    </htmlpagefooter>
<?php if(isset($entered_new)){?>

					<div class="worksheet_div_header">
						<i class="fa fa-minus pull-right fa_minimize_div"></i>
						<h4>My Group Report</h4>
						<!-- <a href="<?php echo base_url(); ?>smartgen" class="btn btn-custom-light">Create New Worksheet</a>
						<a href="<?php echo base_url(); ?>smartgen/archiveWorksheetList" class="btn btn-custom-light">Archived Worksheet</a> -->
					</div>
					<div class="worksheet_div_body">
						<div class="">
							<table class=""  style="overflow: wrap;"  autosize="0">
								<tr>
									<td style="padding-top:1em;font-size:12px;" ><b>Subjects</b></td>
									<?php 
										
										foreach($entered_new['selected_student'] as $sel_student){
											foreach($student_list as $student){
													if($student->student_id == $sel_student){
												?>
													<td style="padding-top:1em;font-size:12px;text-rotate: 90;padding-left:1em;" ><b><?php echo $student->fullname?></b></td>
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
										<td style="padding-top:1em;font-size:10px;"><b><?php echo $sub_item['name'].' </b><br/> '.$cat_item['name']; ?></td>

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