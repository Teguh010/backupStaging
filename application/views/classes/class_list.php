<style>
	.switch {
	position: relative;
	display: inline-block;
	width: 60px;
	height: 30px;
	}

	.switch input { 
	opacity: 0;
	width: 0;
	height: 0;
	}

	.slider {
	position: absolute;
	cursor: pointer;
	top: 0;
	left: 0;
	right: 0;
	bottom: 0;
	background-color: #ccc;
	-webkit-transition: .4s;
	transition: .4s;
	}

	.slider:before {
	position: absolute;
	content: "";
	height: 23px;
	width: 26px;
	left: 4px;
	bottom: 4px;
	background-color: white;
	-webkit-transition: .4s;
	transition: .4s;
	}

	input:checked + .slider {
	background-color: #94df4a;
	}

	input:focus + .slider {
	box-shadow: 0 0 1px #2196F3;
	}

	input:checked + .slider:before {
	-webkit-transform: translateX(26px);
	-ms-transform: translateX(26px);
	transform: translateX(26px);
	}

	/* Rounded sliders */
	.slider.round {
	border-radius: 34px;
	}

	.slider.round:before {
	border-radius: 50%;
	}

	.class_name {
		margin: 5px 0;
		height: 80px;
		display: table-cell;
		vertical-align: middle;
		width: 250px;
	}
</style>
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>css/card.css" />
<div class="section">
	<div class="container">
		<div class="row">
			<div class="col-sm-12 col-md-12 col-lg-12">
				<?php 
					if (isset($profile_message_success)  && $profile_message_success) {
						echo '<div class="alert alert-success">';
						echo $profile_message;
						echo '</div>';
					} elseif (isset($profile_message_success) && !$profile_message_success) {
						echo '<div class="alert alert-danger">';
						echo $profile_message;
						echo '</div>';
					}
					
				?>
			</div>
			<div class="col-sm-12 col-md-12 col-lg-12">
				<button type="submit" class="btn btn-custom" name="addnew" style="margin-top:0px; margin-right:10px; height:33px; padding-bottom:28px; font-size:16px;">Add New Class</button> 
				<a href="<?php echo $google_login_url;?>" class="btn btn-custom" name="import_gc" style="margin-top:0px; margin-right:10px; height:33px; padding-bottom:28px; font-size:16px;"><i class="fa fa-google"></i> Import from Google Classroom</a>
			</div>
			<div class="col-sm-12 col-md-12 col-lg-12">
			<form action="<?php echo base_url(); ?>administrator/class_list" method="post">
				<div class="input-group" style="width: 100%;margin-top: 5px;">
					<input type="text" placeholder="Search Class Name" name="keyword_class" id="keyword_class" style="width:100%; padding:2px 5px 3px 5px;margin-left: 5px;" onkeypress="return runSearch(event)" value="<?php echo $keyword;?>" />
					<span class="input-group-btn">
						<button type="submit" class="btn btn-primary pull-right" name="search" id="search" style="margin-top:0px; margin-right:0px; height:33px; padding-bottom:28px; font-size:16px;"> Search</button>
					</span>
				</div>
			</form>
			</div>
			<div class="card-body">
				<?php
					foreach ($classes as $class) {
						if (count($class) == 0) {
							echo '<div class="student-row clearfix">';
								echo '<div class="col-xs-offset-1 col-sm-offset-1 col-md-offset-1 col-lg-offset-1 col-xs-10 col-sm-10 col-md-10 col-lg-10">';
									echo '<div class="alert alert-danger margin-top-custom text-center">Don\'t have any class in the listing.</div>';
								echo '</div>';
							echo '</div>';
						} else {
							$i = 1;
							foreach($class as $temp_class) { 

								$student_class = $this->model_classes->get_class_relation($temp_class->class_id,'student');
								$tutor_class = $this->model_classes->get_class_relation($temp_class->class_id,'tutor');
								$subject_name = str_replace("Primary ","", $temp_class->name);
								$subject_name = str_replace("Secondary ","", $subject_name);
									?>
								<div class="card">
							      <div class="card-image"></div>
							      <div class="card-text">
							        <span class="date"><?php echo $temp_class->level_name." ".$subject_name?></span>
									<h3 class="class_name">
										<?php echo $temp_class->class_name;?>
									</h3>
							        <p>
							        	<button onclick="editClass('<?php echo $temp_class->class_id ?>','<?php echo $page;?>')" class="btn btn-custom btn-no-margin-top" title="Edit Profile"><i class="fa fa-edit"></i></button>
							        	<button onclick="studentClass('<?php echo $temp_class->class_id ?>','<?php echo $page;?>')" class="btn btn-primary btn-no-margin-top" title="Assign Student" ><i class="fa fa-group"></i></button>
							        	<button onclick="tutorClass('<?php echo $temp_class->class_id ?>','<?php echo $page;?>')" class="btn btn-warning btn-no-margin-top" title="Assign Tutor" ><i class="fa fa-group"></i></button>
							        	<br /><br />
							        	<?php $checked = ($temp_class->status == '0') ? "" : "checked"; ?>
							        	<label class="switch">
											<input onclick="changeStatus('<?php echo $temp_class->class_id ?>',this.value)" type="checkbox" <?php echo $checked ?> value="<?php echo $temp_class->status ?>" id="status-<?php echo $temp_class->class_id ?>">
											<span class="slider round"></span>
										</label>
							        </p>
							      </div>
							      <div class="card-stats">
							        <div class="stat">
							          <div class="value"><?php echo number_format(count($student_class))?></div>
							          <div class="type">Students</div>
							        </div>
							        <div class="stat border">
							          <div class="value"></div>
							          <div class="type"></div>
							        </div>
							        <div class="stat">
							          <div class="value"><?php echo number_format(count($tutor_class))?></div>
							          <div class="type">Tutors</div>
							        </div>
							      </div>
							    </div>
								
						<?php 
								//if($i%3==0 && count($class)!=$i) echo '</div><div class="card-body">';
								$i++;
							}
						}
						
					}
				?>
			</div>
			<div class="pull-right">
				<h4><?php echo $links; ?></h4>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">

	function editClass(classId, page) {
		window.location.href = '<?php echo base_url() ?>classes/edit_class/' + classId + '/' + page ;
	}

	function runSearch(e) {
	    if (e.keyCode == 13) {
	        document.getElementById("search").click();
	        return false;
	    }
	}

	// function getClassName(id){
	// 	$.ajax({
	// 		type: 'GET',
	// 		url: base_url + 'administrator/getClassName/' + id,			
	// 		dataType: 'json',
	// 		success: function (res) {
	// 			$('.class_name_'+id).html(res.class_name);
	// 		}
	// 	});
	// }

	function changeStatus(classId,status) {
		$.ajax({
			url:'<?=base_url()?>classes/edit_status_class',
			method: 'post',
			data: {
				class_id: classId,
				status: status,
			},
			dataType: 'json',
			success: function(response){
				var status_req = (status=='1') ? '0' : '1';
				$('#status-'+classId).val(status_req);
				//alert(status_req);
			}
		});
	}

	function studentClass(classId, page) {
		window.location.href = '<?php echo base_url() ?>classes/student_class/' + classId + '/' + page ;
	}

	function tutorClass(classId, page) {
		window.location.href = '<?php echo base_url() ?>classes/tutor_class/' + classId + '/' + page ;
	}
</script>
