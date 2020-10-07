<?php

/**

 * *

 *  * Created by PhpStorm.

 *  * User: boonkhailim

 *  * Year: 2017

 *

 */



$this->load->helper('form');

	echo form_open("site/login_validation");

?>

	<h3 style="text-align:center;">Login</h3>

	<br>

	<?php

		if (isset($login_error)) {
			
			echo '<div class="alert alert-danger">'. $login_error . '</div>';

		} elseif (($this->session->userdata('login_error')!='')) {
			$login_error = $this->session->userdata('login_error');
			echo '<div class="alert alert-danger">'. $login_error . '</div>';
			$this->session->sess_destroy();

		} elseif (isset($login_message)) {

			echo '<div class="alert alert-success">' . $login_message . '</div>';

		}

	?>



	<div class="col-sm-12 col-md-12 col-lg-12">

	<div class="form-group <?=form_error('login_email')?'has-error':''?>">

		<!-- <label for="login_email" class="control-label col-sm-3 col-md-3 col-lg-3">Email:</label> -->

		<!-- <div class="col-sm-9 col-md-9 col-lg-9"> -->

			<input type="text" name="login_email" id="login_email" placeholder="Username / Email" class="form-control" value="<?=validation_errors()?set_value('login_email'):''?>">

		<!-- </div> -->

	</div>

	</div>



	<div class="col-sm-12 col-md-12 col-lg-12">

	<div class="form-group <?=form_error('login_password')?'has-error':''?>">

		<!-- <label for="email" class="control-label col-sm-3 col-md-3 col-lg-3">Password:</label> -->

		<!-- <div class="col-sm-9 col-md-9 col-lg-9"> -->

			<input type="password" name="login_password" id="login_password" placeholder="Password" class="form-control">

		<!-- </div> -->

	</div>

	</div>



	<div class="col-sm-12 col-md-12 col-lg-12">

	<div class="text-center">

		<input type="submit" class="btn btn-custom" value="Login" id="login_btn">
<!--
		<a href="#" class="btn btn-primary facebook_login_btn" disabled><i class="fa fa-facebook"></i> | Login with Facebook</a>
-->		
			<a href="<?php echo $google_login_url;?>" class="btn btn-primary google_login_btn"><i class="fa fa-google"></i> | Login with Google</a>
<!-- 		<input type="submit" class="btn btn-primary" value="Facebook Connect" id="twitter_login_btn"> -->

	</div>

	</div>



	<div class="col-sm-12 col-md-12 col-lg-12">

	<div class="form-group">

		<!-- <label for="email" class="control-label col-sm-3 col-md-3 col-lg-3">Password:</label> -->

		<!-- <div class="col-sm-9 col-md-9 col-lg-9"> -->

			<a href="#" data-toggle="modal" data-target="#forgotPasswordModal" id="forgot_password_a">Forgot your password?</a>

		<!-- </div> -->

	</div>

	</div>



	<!-- <fb:login-button scope="public_profile,email" onlogin="checkLoginState();">

	</fb:login-button> -->



	<div id="status">

	</div>

	

<?php

	echo form_close();

?>



<div class="modal fade" id="forgotPasswordModal" role="dialog">

	<div class="modal-dialog" role="document">

		<div class="modal-content">

			<div class="modal-header modal-header-custom-success">

				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>

				<h4 class="modal-title">Reset password</h4>

			</div>

			<form class="form-horizontal">

				<div class="modal-body">

					<div id="forgot_password_error" class="alert alert-danger">

						

					</div>

					<div id="forgot_password_success" class="alert alert-success">

						A new temporary password has been sent to your email. Please login and change your password in profile page. Thanks! 

					</div>

					<p>Please enter your registered email below. An email with temporary password will be sent to the email input below</p>

					<div class="form-group">

						<label for="worksheet_name" class="control-label col-sm-4 col-md-4 col-lg-4">Email:</label>

						<div class="col-sm-8 col-md-8 col-lg-8">

							<input type="email" class="form-control" placeholder="abc@example.com" id="reset_password_email">

						</div>

					</div>

				</div>

				<div class="modal-footer">

					<input type="button" class="btn btn-default" data-dismiss="modal" value="Close">

					<button type="button" class="btn btn-custom" data-loading-text="<i class='fa fa-spinner fa-spin'></i> Processing..." id="reset_password_button">Reset Password</button>

				</div>

			</form>

		</div>

	</div>

</div>