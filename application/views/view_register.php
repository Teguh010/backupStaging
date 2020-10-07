<style>
#asterisks {
    position:relative;
    margin-right:5px;
}

#asterisks div::after{
    position:absolute;
    content:'*';
    color:red;
    right:0px;
    top:0;
}
</style>

<?php

/**

 * *

 *  * Created by PhpStorm.

 *  * User: boonkhailim

 *  * Year: 2017

 *

 */



$this->load->helper('form');

	echo form_open("site/register_validation");

?>

	<h3 id="register_title" style="text-align:center;">User Registration</h3>




	<div class="col-sm-12 col-md-12 col-lg-12">
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
		
		<!-- <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6" style="margin-bottom:20px;">
			<select name="register_level" id="role_option" class="form-control">
				<option id="role" value="tutor">Tutor</option>
				<option id="role" value="student">Student / Parent</option>
			</select>
		</div> -->

		<select name="role" id="role_option" class="form-control" style="margin-top:20px; margin-bottom:15px" disabled>

			<option id="role" value="tutor">Tutor</option>

			<option id="role" value="student">Student (with Guardian Account)</option>
			
		</select>
		

		<!-- <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6" align="center" style="margin-bottom:20px;">
		  <input type="radio" name="role[]" id="role" value="student" <?php echo isset($tutorId)? 'checked' : '' ?> > Student (with Guardian Account)
		</div>
		
		<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6" align="center" style="margin-bottom:20px;">
		  <input type="radio" name="role[]" id="role" value="tutor" <?php echo isset($tutorId)? '' : 'checked' ?> > Tutor
		</div> -->
		
		<input type="hidden" name="tutorId" value="<?php echo isset($tutorId) ? $tutorId : '';?>" />
	
	</div>


<!-- <div class="col-sm-12 col-md-12 col-lg-12">

		<p>

			SmartJen is currently not available for public yet, 

			but if you would like to try it out, do get in touch with us by sending a request using the red Feedback button on the side of the screen! :)

		</p>

</div>



	<div class="col-sm-12 col-md-12 col-lg-12">
		<p>
			We would love to invite you to join our waiting list! Please kindly express your interest at: <a href="https://forms.gle/eEizwLR1vRJkWT8C6" target="_blank">https://forms.gle/eEizwLR1vRJkWT8C6</a>
		</p>
		<p>
			Thank you. We will come back to you with a test account.
		</p>
	</div> -->



	<div class="col-sm-12 col-md-12 col-lg-12" id="asterisks">
		<div class="form-group <?=form_error('register_username')?'has-error':''?>">
			<input type="text" name="register_username" id="register_username" placeholder="Username" class="form-control" value="<?php echo set_value('register_username') ?>" disabled>
		</div> 
	</div>



	<div class="col-sm-12 col-md-12 col-lg-12">
		<div class="form-group <?php echo form_error('register_fullName')?'has-error':''?>">
			<input type="text" name="register_fullName" id="register_fullName" placeholder="Full Name" class="form-control" value="<?php echo set_value('register_fullName') ?>" disabled>
		</div>
	</div>

	<div class="col-sm-12 col-md-12 col-lg-12" id="asterisks">
		<div class="form-group <?php echo form_error('register_email')?'has-error':''?>" id="register_email_div">
			<input type="email" name="register_email" id="register_email" placeholder="Email" class="form-control" value="<?php echo set_value('register_email') ?>" disabled>
		</div> 
	</div>
	
	<!-- <p id='new_words' style='text-align:center'>OR</p> -->
	
	<div class="col-sm-12 col-md-12 col-lg-12" id="asterisks">
		<div class="form-group <?php echo form_error('register_mobile')?'has-error':''?>" id="register_mobile_div">
		<span class="input-group-addon" style="position:absolute; width:50px; height:34px; vertical-align:top; padding-top:9px; font-size:14px;">+65</span>
		<input type="mobile" name="register_mobile" id="register_mobile" placeholder="Contact No" class="form-control" style="padding-left:55px;" value="<?php echo set_value('register_mobile') ?>" disabled>
		</div>
	</div>

	<div class="col-sm-12 col-md-12 col-lg-12">
		<div class="form-group <?php echo form_error('register_parent_email')?'has-error':''?>" id="register_parent_email_div">
			<input type="email" name="register_parent_email" id="register_parent_email" placeholder="Parent Email" class="form-control" value="<?php echo set_value('register_parent_email') ?>" disabled>
		</div> 
	</div>

	<div class="col-sm-12 col-md-12 col-lg-12">
		<div class="form-group <?php echo form_error('register_parent_mobile')?'has-error':''?>" id="register_parent_mobile_div">
		<span class="input-group-addon" style="position:absolute; width:50px; height:34px; vertical-align:top; padding-top:9px; font-size:14px;">+65</span>
		<input type="mobile" name="register_parent_mobile" id="register_parent_mobile" placeholder="Parent Mobile No" class="form-control" style="padding-left:55px;" value="<?php echo set_value('register_parent_mobile') ?>" disabled>
		</div>
	</div>

	<div class="col-sm-12 col-md-12 col-lg-12">
		<div class="form-group <?php echo form_error('register_password')?'has-error':''?>">
			<input type="password" name="register_password" id="register_password" placeholder="Password" class="form-control" disabled>
		</div>
	</div>



	<div class="col-sm-12 col-md-12 col-lg-12">
		<div class="form-group <?php echo form_error('register_cpassword')?'has-error':''?>">
			<input type="password" name="register_cpassword" id="register_cpassword" placeholder="Confirm Password" class="form-control" disabled>
		</div>
	</div>
	
	<div class="col-sm-12 col-md-12 col-lg-12">
		<div class="form-group <?php echo form_error('register_level')?'has-error':''?>" id="levels">
			<label for="profile_level">Level:  </label>
				<select name="register_level" id="register_level" class="form-control" disabled>
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
				<select name="register_school" id="register_school" class="form-control" disabled>
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
			<input type="submit" class="btn btn-custom" value="Register" id="register_btn" disabled>
		</div>
	</div>
<?php /*
	<div class="col-sm-12 col-md-12 col-lg-12">
		<a href="#" class="btn btn-primary facebook_login_btn" disabled><i class="fa fa-facebook"></i> | Sign up with Facebook</a>
	</div>
*/ ?>
	<br>




<?php

	echo form_close();

?>



