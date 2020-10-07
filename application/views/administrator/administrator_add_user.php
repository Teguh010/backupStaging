<div class="section">
    <div class="container">
        <div class="row">
            <div class="col-sm-12 col-md-10 col-lg-8 col-md-push-1 col-lg-push-2">
                <h2>Add User</h2><hr>
                <?php

                    if (isset($register_error)) {

                        echo '<div class="alert alert-danger">'. $register_error . '</div>';

                    } elseif (isset($register_success)) {

                        echo '<div class="alert alert-success">' . $register_success . '</div>';

                    }

                    if ($this->session->flashdata('register_error')) {
                        echo '<div class="alert alert-danger">'. $this->session->flashdata('register_error') . '</div>';
                    } elseif ($this->session->flashdata('register_success')) {
                        echo '<div class="alert alert-success">' . $this->session->flashdata('register_success') . '</div>';
                    }
                ?>

                <div id="create_user_error_div" class="alert alert-danger">
                
                </div>

                <div class="row">
                    <div class="col-sm-9 col-md-9 col-lg-9 add_user">
                    
                        <?php
                        $this->load->helper('form');
                        echo form_open("administrator/add_user_process", array('id'=>'admin_create_user_form'));
                        ?>

                        <select name="register_type_btn" id="register_type_role" class="form-control" style="margin-bottom:20px">

                        <option id="role" value="tutor">Tutor</option>

                        <option id="role" value="student">Student (with Guardian Account)</option>

                        </select>
                        
                        <!-- <div class="col-xs-offset-0 col-sm-offset-0 col-md-offset-0 col-lg-offset-0 col-xs-6 col-sm-6 col-md-6 col-lg-6 form-group <?php echo form_error('register_type_btn')?'has-error':''?>">
                            <input type="radio" name="register_type_btn[]" id="register_type_btn" value="tutor" checked> Tutor
                        </div>
                        
                        <div class="col-xs-offset-0 col-sm-offset-0 col-md-offset-0 col-lg-offset-0 col-xs-6 col-sm-6 col-md-6 col-lg-6 form-group <?php echo form_error('register_type_btn')?'has-error':''?>">
                            <input type="radio" name="register_type_btn[]" id="register_type_btn" value="student"> Student (with Guardian Account)
                        </div> -->

                        <div class="form-group <?php echo (isset($registration['username']) && empty($registration['username']) == FALSE) ? 'has-error':''?>" id="create_user_username_div">
                            <label for="profile_username">Username:  </label><span class="required-field"></span>
                            <input type="text" name="register_username" id="register_username" placeholder="Username" class="form-control" value="<?php echo isset($registration) && count($registration) !== 0 ? $registration['register_username'] : '' ?>">
                        </div>

                        <div class="form-group <?php echo (isset($registration['fullname']) && empty($registration['fullname']) == FALSE) ? 'has-error':''?>" id="create_user_fullname_div">
                            <label for="profile_fullName">Fullname:  </label><span class="required-field"></span>
                            <input type="text" name="register_fullName" id="register_fullName" placeholder="Full Name" class="form-control" value="<?php echo isset($registration) && count($registration) !== 0 ? $registration['register_fullName'] : '' ?>">
                            
                        </div>
                        
                        <div class="form-group email <?php echo (isset($registration['email']) && empty($registration['email']) == FALSE) ?'has-error':''?>" id = "register_email_div">
                            <label class="label-email" for="profile_email">Email:  </label><span class="required-field email"></span>
                            <input type="email" name="register_email" id="register_email" placeholder="Email" class="form-control" value="<?php echo isset($registration) && count($registration) !== 0 ? $registration['register_email'] : '' ?>">
                        </div>
                        
                        <div class="form-group <?php echo (isset($registration['mobile']) && empty($registration['mobile']) == FALSE) ?'has-error':''?>" id = "register_mobile_div">
                            <label class="label-mobile" for="profile_mobile">Contact No:  </label><span class="required-field mobile"></span>
                            <span class="input-group-addon" style="position:absolute; width:50px; height:34px; vertical-align:top; padding-top:9px; font-size:14px;">+65</span>
                            <input type="text" name="register_mobile" id="register_mobile" placeholder="Mobile No" class="form-control" style="padding-left:55px;" value="<?php echo isset($registration) && count($registration) !== 0 ? $registration['register_mobile'] : '' ?>">
                        </div>

                        <div class="form-group email <?php echo (isset($registration['parent_email']) && empty($registration['parent_email']) == FALSE) ?'has-error':''?>" id = "register_parent_email_div">
                            <label class="label-email" for="profile_parent_email">Parent Email:  </label><span class="required-field email"></span>
                            <input type="email" name="register_parent_email" id="register_parent_email" placeholder="Parent Email" class="form-control" value="<?php echo isset($registration) && count($registration) !== 0 ? $registration['register_parent_email'] : '' ?>">
                        </div>
                        
                        <div class="form-group <?php echo (isset($registration['parent_mobile']) && empty($registration['parent_mobile']) == FALSE) ?'has-error':''?>" id = "register_parent_mobile_div">
                            <label class="label-mobile" for="profile_parent_mobile">Contact No:  </label><span class="required-field mobile"></span>
                            <span class="input-group-addon" style="position:absolute; width:50px; height:34px; vertical-align:top; padding-top:9px; font-size:14px;">+65</span>
                            <input type="text" name="register_parent_mobile" id="register_parent_mobile" placeholder="Parent Mobile No" class="form-control" style="padding-left:55px;" value="<?php echo isset($registration) && count($registration) !== 0 ? $registration['register_parent_mobile'] : '' ?>">
                        </div>
                        
                        <div class="form-group <?php echo form_error('register_password')?'has-error':''?>" id= "create_user_password_div">
                            <label for="profile_password">Password:  </label>
                            <input type="password" name="register_password" id="register_password" placeholder="Password" class="form-control">
                        </div>
                        
                        <div class="form-group <?php echo form_error('register_cpassword')?'has-error':''?>" id= "create_user_cpassword_div">
                            <label for="profile_cpassword">Confirm password:  </label>
                            <input type="password" name="register_cpassword" id="register_cpassword" placeholder="Confirm Password" class="form-control">
                        </div>
                        
                        <div class="form-group <?php echo form_error('register_level')?'has-error':''?>" id="level">
                            <label for="profile_level">Level:  </label>
								<select name="register_level" id="register_level" class="form-control">
									<?php
										foreach ($levels as $level) {
                                            echo '<option value="'.$level->level_id.'">'.$level->level_name.'</option>';
                                        }
									?>
								</select>
                        </div>
                        
                        <div class="form-group <?php echo form_error('register_school')?'has-error':''?>" id="school">
                            <label for="profile_school">School:  </label>
								<select name="register_school" id="register_school" class="form-control">
									<?php
										foreach ($schools as $school) {
                                            echo '<option value="'.$school->school_id.'">'.$school->school_name.'</option>';
                                        }
									?>
								</select>
                        </div>
                       
                        <div>
                            <a href="<?php echo base_url();?>administrator/add_user" class="btn btn-danger">Cancel</a>
                            <input type="submit" class="btn btn-custom" value="Add User" id="admin_register_btn" style="width:100px;">
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

<script>
$(document).ready(function() {

    var account_btn = $('#register_type_role').find('option:selected').val();

    if(account_btn == 'student'){

		$('#level').show();
		$('#school').show();
		$('#register_parent_email_div').show();
		$('#register_parent_mobile_div').show();
		
	} else if(account_btn == 'tutor'){
		$('#level').hide();
		$('#school').hide();
		$('#register_parent_email_div').hide();
		$('#register_parent_mobile_div').hide();
	}

    $('#register_type_role').on('change', function(e) {
        var account_type = $('#register_type_role').find('option:selected').val();

        if(account_type == 'student'){
			$('#level').show();
			$('#school').show();
			$('#register_parent_email_div').show();
			// $('#register_parent_email_div').parent().attr('id', "asterisks");
			$('#register_parent_mobile_div').show();
            // $('#register_parent_mobile_div').parent().attr('id', "asterisks");
            $('#register_email_div').find('span.required-field').removeClass('required-field');
            $('#register_mobile_div').find('span.required-field').removeClass('required-field');
			$('#register_username').attr("placeholder", "Student Username");
			$('#register_fullName').attr("placeholder", "Student Full Name");
			$('#register_email').attr("placeholder", "Student Email");
			$('#register_mobile').attr("placeholder", "Student Mobile No");
			
		} else if(account_type == 'tutor'){
			$('#level').hide();
            $('#school').hide();
            $('#register_email_div').find('span.email').addClass('required-field');
            $('#register_mobile_div').find('span.mobile').addClass('required-field');
			$('#register_parent_email_div').hide();
			$('#register_parent_mobile_div').hide();
			$('#register_username').attr("placeholder", "Username");
			$('#register_fullName').attr("placeholder", "Full Name");
			$('#register_email').attr("placeholder", "Email");
			$('#register_mobile').attr("placeholder", "Mobile No");
		}
    });

    // filter school list base on level id
    $('#register_level').change(function(){
        var level = $(this).val();
        // AJAX request
        $.ajax({
            url:'<?=base_url()?>administrator/get_school_list',
            method: 'post',
            data: {level: level},
            dataType: 'json',
            success: function(response){
                // Remove options
                $('#register_school').empty(); 

                // Add options
                $.each(response,function(index,data){
                    $('#register_school').append('<option value="'+data['school_id']+'">'+data['school_name']+'</option>');
                });
            }
        });
    });

    // end of school list filter

});
</script>