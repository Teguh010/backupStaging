
<!-- [WM] 2Jan18: This page for admin account with registration controls enabled -->

<?php
$this->load->helper('form');
echo form_open("site/register_validation");
?>
<h3>Tutor / Parent Register</h3>

<?php
if (isset($register_error)) {
    echo '<div class="alert alert-danger">'. $register_error . '</div>';
} elseif (isset($register_success)) {
    echo '<div class="alert alert-success">' . $register_success . '</div>';
}
?>

<br>

<!---->
<!--<div class="col-xs-offset-0 col-sm-offset-0 col-md-offset-0 col-lg-offset-0 col-xs-6 col-sm-6 col-md-6 col-lg-6" align="center">-->
<!--  <input type="radio" name="role" value="student"> Student-->
<!-- </div>-->
<!--<div class="col-xs-offset-0 col-sm-offset-0 col-md-offset-0 col-lg-offset-0 col-xs-6 col-sm-6 col-md-6 col-lg-6" align="center">-->
<!--  <input type="radio" name="role" value="tutor"> Tutor / Parent-->
<!-- </div>-->

<!-- [WM] 29Dec17: Reallow registration upon request from Oliver/Glenn, this should be temporary -->

<!--
<div class="col-sm-12 col-md-12 col-lg-12">
		<p>
			SmartJen is currently not available for public yet,
			but if you would like to try it out, do get in touch with us by sending a request using the red Feedback button on the side of the screen! :)
		</p>
</div>
-->

<div class="col-sm-12 col-md-5 col-lg-5">
    <div class="form-group <?=form_error('register_username')?'has-error':''?>">
        <input type="text" name="register_username" id="register_username" placeholder="Username" class="form-control" value="<?=validation_errors()?set_value('register_username'):''?>">
    </div>
</div>

<div class="col-sm-12 col-md-7 col-lg-7">
    <div class="form-group <?=form_error('register_fullName')?'has-error':''?>">
        <input type="text" name="register_fullName" id="register_fullName" placeholder="Full Name" class="form-control" value="<?=validation_errors()?set_value('register_fullName'):''?>">
    </div>
</div>

<div class="col-sm-12 col-md-12 col-lg-12">
    <div class="form-group <?=form_error('register_email')?'has-error':''?>">
        <input type="email" name="register_email" id="register_email" placeholder="Email" class="form-control" value="<?=validation_errors()?set_value('register_email'):''?>">
    </div>
</div>

<div class="col-sm-12 col-md-12 col-lg-12">
    <div class="form-group <?=form_error('register_password')?'has-error':''?>">
        <input type="password" name="register_password" id="register_password" placeholder="Password" class="form-control">
    </div>
</div>

<div class="col-sm-12 col-md-12 col-lg-12">
    <div class="form-group <?=form_error('register_cpassword')?'has-error':''?>">
        <input type="password" name="register_cpassword" id="register_cpassword" placeholder="Confirm Password" class="form-control">
    </div>
</div>

<div class="col-sm-12 col-md-12 col-lg-12">
    <div>
        <input type="submit" class="btn btn-custom" value="Register" id="register_btn">
    </div>
</div>
<div class="col-sm-12 col-md-12 col-lg-12">
    <a href="#" class="btn btn-primary facebook_login_btn"><i class="fa fa-facebook"></i> | Sign up with Facebook</a>
</div><br>


<?php
echo form_close();
?>

