<style type="text/css">
	#loading {
	  width: 100%;
	  height: 100%;
	  top: 0;
	  left: 0;
	  position: fixed;
	  display: block;
	  opacity: 0.7;
	  background-color: #fff;
	  z-index: 999999999;
	  text-align: center;
	}
	#loading-image {
	  position: absolute;
	  top: 100px;
	  left: 50%;
	  z-index: 100;
	}
</style>
<div id="loading">
  <img id="loading-image" src="<?php echo base_url(); ?>img/loading.gif" alt="Loading..." />
</div>
<div class="section">

	<div class="container">

		<div class="col-md-8 col-md-offset-2">

			<ol class="smartgen-progress">

				<li class="is-complete" data-step="1">

					<a href="<?php echo base_url(); ?>smartgen">Design</a>

				</li>

				<li class="is-complete" data-step="2">

					<a href="<?php echo base_url().'profile/customizeWorksheet/'.$worksheet_id; ?>">Customize</a>

				</li>

				<li class="is-active" data-step="3" class="smartgen-progress__last">Assign</li>

			</ol>

		</div>

	</div>

</div>


<div class="section">

	<div class="container">

	<?php
		if (count($my_students) == 0) {
			echo '<div class="alert alert-danger">You have no students yet. Please add your student in <a href="'.base_url().'profile">profile</a> page first. Don\'t worry, you can visit this page again later!</div>';
		}
	?>

		<div class="row">

			<div class="col-lg-6 col-md-6 col-sm-6">

				<div class="panel panel-success panel-success-custom-dark">

					<div class="panel-heading clearfix">My student list

						<a href="#" class="assign_all_students btn btn-custom btn-no-margin pull-right">Assign all</a>

					</div>
					<div id="select-group" style="margin: 10px;">
						<select class="form-control sort_group">
							<option value="all-student">All Students</option>
							<?php 
							$get_tutor_group = $this->model_users->get_group($this->session->userdata('user_id'));
							foreach ($get_tutor_group as $key => $value) {
								$check = $this->model_users->get_student_assign_group($this->session->userdata('user_id'),$value['id'],"sum");
								if($check > 0) {
							?>
								<option value="<?php echo $value['group_name']?>"><?php echo $value['group_name']?></option>
							<?php } }?>
						</select>
						<script type="text/javascript">
							function filterGroup(selectedGroup) {
								if(selectedGroup=="all-student") {
									$(".all_deassign").css("display", "block");
								} else {
									$(".all_deassign").css("display", "none");
									$("li[title|='"+selectedGroup+"']").css("display", "block");
								}
							}
						</script>
					</div>
					<ul class="list-group" id="deassign_student_list">

						<!-- <?php

							if (count($not_assigned_students) == 0) {

								echo '<li class="list-group-item question_text helper_text">No students</li>';

							} else {
								foreach ($not_assigned_students as $student) {

									echo '<li class="list-group-item question_text student_li">';

									echo '<span>'.$student->fullname.'</span>';

									echo '<a href="#" id="'.$student->student_id.'" class="btn btn-custom btn-no-margin pull-right assign_student">Assign</a>';

									echo '</li>';

								}

							}

						?> -->

					</ul>

				</div>

			<div align="right" id="pagination"></div>

			</div>

			<div class="col-lg-6 col-md-6 col-sm-6">

				<div class="panel panel-success">

					<div class="panel-heading clearfix">Assigned student list

						<a href="#" class="deassign_all_students btn btn-danger btn-no-margin pull-right">Remove all</a>

					</div>

					<ul class="list-group" id="assigned_student_list">

						<?php

							if (count($assigned_students) == 0) {

								echo '<li class="list-group-item question_text helper_text">No assigned student yet</li>';

							} else {

								echo '';

								foreach ($assigned_students as $student) {

									echo '<li class="list-group-item question_text student_li">';

									echo '<span>'.$student->username.' ('.$student->level_name.')</span>';

									echo '<a href="#" id="'.$student->id.'" class="btn btn-danger btn-no-margin pull-right deassign_student">Remove</a>';

									echo '</li>';

								}

							}

						?>

					</ul>

				</div>

				<div align="right" id="paginations"></div>

			</div>

		</div>

	</div>

</div>


<div class="section">

	<div class="container">

		<div class="row">

			<div class="container">

				<div class="col-sm-offset-4 col-sm-4">

					<div class="text-center">

						<form action="<?php echo base_url().'smartgen/assignWorksheet/'.$worksheet_id; ?>" id="assign_worksheet_form" method="post">

							<?php

								foreach ($assigned_students as $student) {

									echo '<input type="hidden" class="assigned_student" name="assigned_students[]" value="' . $student->id . '">';

								}

							?>

							<input type="hidden" name="worksheet_id" value="<?php echo $worksheet_id; ?>">

							<input type="submit" class="btn btn-custom btn-no-margin btn-block" value="Finalize" id="assign_student_submit_button">

						</form>

					</div>

				</div>

			</div>

		</div>

	</div>

</div>


<script type='text/javascript'>
	$(document).ready(function(){
		$('#loading').hide();

		var userId = '<?php echo $this->session->userdata('user_id');?>';

		var assigned_student_list = $('#assigned_student_list');

		var deassigned_student_list = $('#deassign_student_list');

		var one;

		var pageno;

		loadPagination(1);

		// Load pagination
		function loadPagination(pageno, exclude){

			var exclude = [];

			// $('#assigned_student_list li a').each(function(){

			// 	exclude.push($(this).attr('id'));

			// });

			$('.deassign_student').each(function() {
			    exclude.push(this.id);
			});

			var group = $('.sort_group').val();
			group = (group=="" || group==" ") ? "all-student" : group;
			$('#loading').show();
			$.ajax({

				url: '<?php echo base_url()?>index.php/smartgen/pagination/'+pageno,

				data:{exclude:exclude, group:group},

				type: 'post',

				dataType: 'json',

				success: function(data){

					$('#pagination').html(data.pagination);

					createTable(data.result,data.row);
					$('#loading').hide();
				}

			});

		}

		function loadPaginations(pageno, exclude, group){

			var exclude = [];

			// $('#assigned_student_list li a').each(function(){

			// 	exclude.push('0');

			// });

			$('.deassign_student').each(function() {
			    exclude.push(this.id);
			});

			var group = $('.sort_group').val();
			group = (group=="" || group==" ") ? "all-student" : group;

			$.ajax({

				url: '<?php echo base_url()?>smartgen/paginations/'+pageno,

				data:{exclude:exclude, group:group},

				type: 'post',

				dataType: 'json',

				success: function(data){
					
					$('#paginations').html(data.paginations);
					
					createTables(data.result,data.row);

					var all_result = data.all_result;

					$('.hidden_assigned_student').remove();

					for(index in all_result) {
						var id = all_result[index].student_id;

						var name = all_result[index].username;

						var level = all_result[index].level_name;

						if($('#pagination').length) {

							$('#assign_worksheet_form').append('<input type="hidden" class="hidden_assigned_student" name="assigned_students[]" value="' + id + '" id="hidden_assigned_student_' + id + '">');

						}
					}

				}

			});

		}

		// Detect select Group
		$(document).on('change', '.sort_group', function(e) {
			e.preventDefault(); 
			if($('ul.pagination').text()){

				var pageno = $('ul.pagination li.page-item-active').text();

			} else {

				var pageno = 1;

			}
			var exclude = [];

			$('#assigned_student_list li a').each(function(){

				exclude.push($(this).attr('id'));

			});

			loadPagination(pageno, exclude);

		});

		$(document).on('click', '.assign_student', function(e) {
			e.preventDefault();

			var new_button = $(this).removeClass('assign_student btn-custom').addClass('deassign_student btn-danger').html('De-assign')[0];

			var new_row = '<li class="list-group-item question_text student_li"><span>' + $(this).prev().text() + '</span>'+

			'<a href="#" id="' + $(this).attr('id') + '" class="btn btn-danger btn-no-margin pull-right deassign_student">Remove</a>' + '</li>';

			$(this).parent().remove();

			if (assigned_student_list.children('.helper_text').length > 0) {

				assigned_student_list.children('.helper_text')[0].remove();

			}

			assigned_student_list.append(new_row);

			if (deassigned_student_list.children().length == 0) {

				deassigned_student_list.append('<li class="list-group-item question_text helper_text">No students</li>');

			}

			if($('ul.pagination').text()){

				var pageno = $('ul.pagination li.page-item-active').text();

			} else {

				var pageno = 1;

			}

			var exclude = [];

			$('#assigned_student_list li a').each(function(){

				exclude.push($(this).attr('id'));

			});

			$('#assign_worksheet_form').append('<input type="hidden" class="hidden_assigned_student" name="assigned_students[]" value="' + $(this).attr('id') + '" id="hidden_assigned_student_' + $(this).attr('id') + '">');

			loadPagination(pageno, exclude);

		});

		$(document).on('click', '.assign_all_students', function(e) {

			e.preventDefault();

			var pageno = 1;

		//	var exclude = 'NULL';

			var exclude = [];

			$('#assigned_student_list li a').each(function(){

				exclude.push($(this).attr('id'));

			});

			var group = $('.sort_group').val();

			group = (group=="" || group==" ") ? "all-student" : group;
			// alert(group);

			$('#pagination').empty();

			$('#deassign_student_list').empty();

			loadPaginations(pageno, exclude, group);

		});

		$(document).on('click', '.deassign_all_students', function(e) {

			e.preventDefault();

			var pageno = 1;

			var exclude = '';

			$('#paginations').empty();

			$('#assigned_student_list').empty();

			loadPagination(pageno, exclude);

		});

		// Detect pagination click
		$('#pagination').on('click','a',function(e){

			e.preventDefault(); 

			pageno = $(this).attr('data-ci-pagination-page');

			loadPagination(pageno);

		});

		// Detect pagination click
		$('#paginations').on('click','a',function(e){

			e.preventDefault(); 

			pageno = $(this).attr('data-ci-pagination-page');

			loadPaginations(pageno);

		});

		// Create table list
		function createTable(result,sno){

			sno = Number(sno);

			$('#deassign_student_list').empty();

			for(index in result){

				var id = result[index].student_id;

				var name = result[index].fullname;

				var level = result[index].level_name;
				
				var group_name = result[index].group_name;

				sno+=1;

				var tr = "<li class='list-group-item question_text student_li all_deassign' id='student_"+id+"' title='"+group_name+"'>";

				tr += "<span>"+ name +" ("+ level +")</span>";

				tr += "<a href='#' id='"+ id +"'  class='btn btn-custom btn-no-margin pull-right assign_student'>Assign</a>";

				tr += "</li>";

				$('#deassign_student_list').append(tr);

			}

		}

		// Create table list
		function createTables(result,sno){

			sno = Number(sno);

			$('#deassign_student_list').empty();

			$('#assigned_student_list').empty();

			for(index in result){

				var id = result[index].student_id;

				var name = result[index].fullname;

				var level = result[index].level_name;

				sno+=1;

				var tr = "<li class='list-group-item question_text student_li'>";

				tr += "<span>"+ name +" ("+ level +")</span>";

				tr += "<a href='#' id='"+ id +"'  class='btn btn-danger btn-no-margin pull-right deassign_student'>Remove</a>";

				tr += "</li>";

				$('#assigned_student_list').children('.helper_text').remove();

				$('#assigned_student_list').append(tr);

			}

		}

		 

	});

</script>