<div class="section">
    <div class="container">
        <div class="row">
            <div class="col-sm-12 col-md-10 col-lg-8 col-md-push-1 col-lg-push-2">
                <h2>Add User</h2><hr>
                <?php
                if ($this->session->flashdata('update_error')) {
                    echo '<div class="alert alert-danger">'. $this->session->flashdata('update_error') . '</div>';
                } elseif ($this->session->flashdata('update_success')) {
                    echo '<div class="alert alert-success">' . $this->session->flashdata('update_success') . '</div>';
                }
                
                if (isset($register_error)) {
	                echo '<div class="alert alert-danger">'. $register_error . '</div>';
	            } elseif (isset($register_success)) {
		            echo '<div class="alert alert-success">' . $register_success . '</div>';
		        }
                ?>
                <div class="row">
                    <div class="col-sm-9 col-md-9 col-lg-9 add_user">
                        <?php
                        $this->load->helper('form');
                        echo form_open("administrator/create_user_process");
                        ?>
                        
                        <div class="col-xs-offset-0 col-sm-offset-0 col-md-offset-0 col-lg-offset-0 col-xs-6 col-sm-6 col-md-6 col-lg-6 form-group <?=form_error('register_type_btn')?'has-error':''?>">
                            <input type="radio" name="register_type_btn[]" id="register_type_btn" value="tutor" checked> Tutor / Parent
                        </div>
                        
                        <div class="col-xs-offset-0 col-sm-offset-0 col-md-offset-0 col-lg-offset-0 col-xs-6 col-sm-6 col-md-6 col-lg-6 form-group <?=form_error('register_type_btn')?'has-error':''?>">
                            <input type="radio" name="register_type_btn[]" id="register_type_btn" value="student"> Student
                        </div>

                        <div class="form-group <?=form_error('register_username')?'has-error':''?>">
                            <label for="profile_username">Username:  </label><span class="required-field"></span>
                            <input type="text" name="register_username" id="register_username" placeholder="Username" class="form-control" value="<?=validation_errors()?set_value('register_username'):''?>">
                        </div>

                        <div class="form-group <?=form_error('register_fullName')?'has-error':''?>">
                            <label for="profile_fullName">Fullname:  </label><span class="required-field"></span>
                            <input type="text" name="register_fullName" id="register_fullName" placeholder="Full Name" class="form-control" value="<?=validation_errors()?set_value('register_fullName'):''?>">
                            
                        </div>
                        
                        <div class="form-group email <?=form_error('register_email')?'has-error':''?>">
                            <label class="label-email" for="profile_email">Email:  </label><span class="required-field email"></span>
                            <input type="email" name="register_email" id="register_email" placeholder="Email" class="form-control" value="<?=validation_errors()?set_value('register_email'):''?>">
                        </div>
						
						<p id='new_word' style='text-align:center'>OR</p>
                        
                        <div class="form-group <?=form_error('register_mobile')?'has-error':''?>">
                            <label class="label-mobile" for="profile_mobile">Contact No:  </label><span class="required-field mobile"></span>
                            <span class="input-group-addon" style="position:absolute; width:50px; height:34px; vertical-align:top; padding-top:9px; font-size:14px;">+65</span>
                            <input type="text" name="register_mobile" id="register_mobile" placeholder="Contact No" class="form-control" value="<?=validation_errors()?set_value('register_mobile'):''?>" style="padding-left:55px;">
                        </div>
                        
                        <div class="form-group <?=form_error('register_password')?'has-error':''?>">
                            <label for="profile_password">Password:  </label>
                            <input type="password" name="register_password" id="register_password" placeholder="Password" class="form-control">
                        </div>
                        
                        <div class="form-group <?=form_error('register_cpassword')?'has-error':''?>">
                            <label for="profile_cpassword">Confirm password:  </label>
                            <input type="password" name="register_cpassword" id="register_cpassword" placeholder="Confirm Password" class="form-control">
                        </div>
                        
                        <div class="form-group <?=form_error('register_level')?'has-error':''?>" id="level">
                            <label for="profile_level">Level:  </label><span class="required-field"></span>
								<select name="register_level" id="register_level" class="form-control">
									<?php
										foreach ($levels as $level) {
                                            echo '<option value="'.$level->level_id.'">'.$level->level_name.'</option>';
                                        }
									?>
								</select>
                        </div>
                        
                        <div class="form-group <?=form_error('register_school')?'has-error':''?>" id="school">
                            <label for="profile_school">School:  </label><span class="required-field"></span>
								<select name="register_school" id="register_school" class="form-control">
									<?php
										foreach ($schools as $school) {
                                            echo '<option value="'.$school->school_id.'">'.$school->school_name.'</option>';
                                        }
									?>
								</select>
                        </div>
                       
                        <div>
                            <a href="<?=base_url();?>administrator/add_user" class="btn btn-danger">Cancel</a>
                            <input type="submit" class="btn btn-custom" value="Add User" id="register_btn" style="width:100px;">
                        </div>
                    </div>
                    <?php
                    echo form_close();
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>