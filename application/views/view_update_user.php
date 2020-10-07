<style>
.tag_user {
	padding:20px;
	margin:0 auto;
	width:500px;
	height:350px;
}

#insert_btn, #reset_btn {
    margin: 5px 0;
    width: 100%;
}

@media only screen and (max-width: 1024px) {
	.tag_user {
		padding:20px;
		margin:0 auto;
		width:350px;
		height:auto;
	}
}

@media only screen and (max-width: 600px) {
	.tag_user {
		padding:20px;
		width:300px;
		height:auto;
	}
}
</style>

<div class="container">
	<div class="row">
<?php 

if ($this->session->flashdata('register_error')) {
    echo '<div class="alert alert-danger">'. $this->session->flashdata('register_error') . '</div>';
} elseif ($this->session->flashdata('register_success')) {
    echo '<div class="alert alert-success">' . $this->session->flashdata('register_success') . '</div>';
}

?>
		<div class="tag_user">
			<form action="<?php echo base_url() ?>site/update_parent_user" method="post" class="form-horizontal">
				<h3 style="text-align:center;">Parent Account User Detail</h3>
				<br>
				<div class="col-sm-12 col-md-12 col-lg-12" style="margin-left:15px;">
					<p>Please fill up the field before proceed to the dashboard. Thank you. </p>

                    <div class="form-group">
                        <input type="text" name="register_username" id="register_username" placeholder="Parent Username" class="form-control" value="">
                    </div> 

                    <div class="form-group">
                        <input type="text" name="register_fullName" id="register_fullName" placeholder="Parent Full Name" class="form-control" value="">
                    </div>

                    <div class="form-group">
                        <input type="password" name="register_password" id="register_password" placeholder="Password" class="form-control">
                    </div>

                    <div class="form-group">
                        <input type="password" name="register_cpassword" id="register_cpassword" placeholder="Confirm Password" class="form-control">
                    </div>

                    <input type="hidden" id="pemail" name="pemail" value="<?php echo $email ?>">
				    <input type="hidden" id="parkey" name="parkey" value="<?php echo $parkey ?>">
                    <input type="hidden" id="key" name="key" value="<?php echo $key ?>">
                    <input type="hidden" id="school_id" name="school_id" value="<?php echo $school_id ?>">
                    <input type="hidden" id="level_id" name="level_id" value="<?php echo $level_id ?>">
                    <input type="hidden" id="tutor_id" name="tutor_id" value="<?php echo $tutorId ?>">

                    <div class="form-group">
                        <input type="submit" class="btn btn-custom" value="Register" id="insert_btn">
                        <input type="reset" class="btn btn-default" value="Reset" id="reset_btn">
                    </div>
				</div>

                <!-- <div class="col-sm-12 col-md-12 col-lg-12">
                    <div class="form-group">
                        <input type="text" name="register_username" id="register_username" placeholder="Username" class="form-control" value="">
                    </div> 
                </div>

                <div class="col-sm-12 col-md-12 col-lg-12">
                    <div class="form-group">
                        <input type="text" name="register_fullName" id="register_fullName" placeholder="Full Name" class="form-control" value="">
                    </div>
                </div>

                <div class="col-sm-12 col-md-12 col-lg-12">
                    <div class="form-group">
                        <input type="password" name="register_password" id="register_password" placeholder="Password" class="form-control">
                    </div>
                </div> 

                <div class="col-sm-12 col-md-12 col-lg-12">
                    <div class="form-group">
                        <input type="password" name="register_cpassword" id="register_cpassword" placeholder="Confirm Password" class="form-control">
                    </div>
                </div> 
				
				<input type="hidden" id="userid" name="userid" value="">
				<input type="hidden" id="tutorId" name="tutorId" value="">
				
				<div class="col-sm-12 col-md-12 col-lg-12 text-center">
					<a href="<?php echo base_url(); ?>site/user_tag_branch/" class="btn btn-custom btn-block">Ok</a>
					<a href="<?php echo base_url(); ?>site/login" class="btn btn-default btn-block">Cancel</a>
				</div> -->
			</form>
		</div>
	</div>
</div>



