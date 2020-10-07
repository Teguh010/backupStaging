<?php

$this->load->helper('form');
	echo form_open("site/register_validation");
?>
	<h3 id="register_title" style="text-align:center;">User Registration</h3>

	<?php
		if (isset($register_error)) {
			echo '<div class="alert alert-danger">'. $register_error . '</div>';
		} elseif (isset($register_success)) {
			echo '<div class="alert alert-success">' . $register_success . '</div>';
		}
	?>
	
	<?php
		if ($this->session->flashdata('register_error')) {
			echo '<div class="alert alert-danger">'. $this->session->flashdata('register_error') . '</div>';
		} elseif ($this->session->flashdata('register_success')) {
			echo '<div class="alert alert-success">' . $this->session->flashdata('register_success') . '</div>';
		}
	?>

<br>

	<!-- <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6" align="center" style="padding-bottom:15px;">
	  <input type="radio" name="role[]" id="role" value="student" <?php echo isset($tutorId)? 'checked' : '' ?> > Student  
	</div>
	
	<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6" align="center" style="padding-bottom:15px;">
	  <input type="radio" name="role[]" id="role" value="tutor" <?php echo isset($tutorId)? '' : 'checked' ?> disabled > Tutor / Parent
	</div> -->
	<input type="hidden" name="tutorId" value="<?php echo isset($tutorId) ? $tutorId : '';?>" />


	<!-- <div class="col-sm-12 col-md-12 col-lg-12">
		<div class="form-group <?php echo form_error('register_username')?'has-error':''?>">
			<input type="text" name="register_username" id="register_username" placeholder="Username" class="form-control" value="<?php echo set_value('register_username', $this->input->post('register_username')); ?>">
		</div> 
	</div>



	<div class="col-sm-12 col-md-12 col-lg-12">
		<div class="form-group <?php echo form_error('register_fullName')?'has-error':''?>">
			<input type="text" name="register_fullName" id="register_fullName" placeholder="Full Name" class="form-control" value="<?php echo set_value('register_fullName') ?>">
		</div>
	</div>



	<div class="col-sm-12 col-md-12 col-lg-12">
		<div class="form-group <?php echo form_error('register_email')?'has-error':''?>" id="register_email_div">
			<input type="email" name="register_email" id="register_email" placeholder="Email" class="form-control" value="<?php echo set_value('register_email', $email) ?>" disabled>
			<input type="hidden" name="register_email" value="<?php echo $email ?>">
		</div> 
	</div>
	
	<p id='new_words' style='text-align:center'>OR</p>
	
	<div class="col-sm-12 col-md-12 col-lg-12">
		<div class="form-group <?php echo form_error('register_mobile')?'has-error':''?>" id="register_mobile_div">
			<input type="mobile" name="register_mobile" id="register_mobile" placeholder="Contact No" class="form-control" value="<?php echo set_value('register_mobile') ?>">
		</div> 
	</div>

	<div class="col-sm-12 col-md-12 col-lg-12">
		<div class="form-group <?php echo form_error('register_password')?'has-error':''?>">
			<input type="password" name="register_password" id="register_password" placeholder="Password" class="form-control">
		</div>
	</div>


	<div class="col-sm-12 col-md-12 col-lg-12">
		<div class="form-group <?php echo form_error('register_cpassword')?'has-error':''?>">
			<input type="password" name="register_cpassword" id="register_cpassword" placeholder="Confirm Password" class="form-control">
		</div>
	</div>
	
	
	<div class="col-sm-12 col-md-12 col-lg-12">
		 <div class="form-group <?php echo form_error('register_level')?'has-error':''?>" id="levels">
			<label for="profile_level">Level:  </label>
				<select name="register_level" id="register_level" class="form-control">
					<?php
						foreach ($levels as $level) {
							echo '<option value="'.$level->level_id.'">'.$level->level_name.'</option>';
						}
					?>
				</select>
		</div>
	</div>
	
	
	<div class="col-sm-12 col-md-12 col-lg-12">
		<div class="form-group <?php echo form_error('register_school')?'has-error':''?>" id="schools">
		<label for="profile_school">School:  </label>
			<select name="register_school" id="register_school" class="form-control">
				<?php
					foreach ($schools as $school) {
						echo '<option value="'.$school->school_id.'">'.$school->school_name.'</option>';
					}
				?>
			</select>
		</div>
	</div>

	<div class="col-sm-12 col-md-12 col-lg-12">
		<div>
			<input type="submit" class="btn btn-custom" value="Register" id="register_btn">
		</div>
	</div>
	<div class="col-sm-12 col-md-12 col-lg-12">
		<a href="#" class="btn btn-primary facebook_login_btn"><i class="fa fa-facebook"></i> | Sign up with Facebook</a>
	</div><br> -->

<?php
	echo form_close();
?>




