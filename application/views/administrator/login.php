<div class="section">	
	<div class="container">
		<div class="row">
			<div class="col-sm-6 col-md-6 col-lg-6">
				<?php
					$this->load->helper('form');
					echo form_open("site/admin_loginValidation");
				?>
					<h3>Admin Login</h3>
					<br>
					<?php
						if (isset($login_error)) {
							echo '<div class="alert alert-danger">'. $login_error . '</div>';
						} elseif (isset($login_message)) {
							echo '<div class="alert alert-success">' . $login_message . '</div>';
						}
					?>
					<div class="form-group <?=form_error('login_username')?'has-error':''?>">
						<input type="text" name="login_username" id="login_username" placeholder="Admin username" class="form-control" value="<?=validation_errors()?set_value('login_username'):''?>">
					</div>

					<div class="form-group <?=form_error('login_password')?'has-error':''?>">
						<input type="password" name="login_password" id="login_password" placeholder="Password" class="form-control">
					</div>

					<div class="text-center">
						<input type="submit" class="btn btn-custom" value="Login" id="login_btn">
					</div>
					
				<?php
					echo form_close();
				?>



			</div>
		</div>	
	</div>
</div>
