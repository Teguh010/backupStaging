<div class="section">

    <div class="container">

        <div class="row">

            <div class="col-sm-12 col-md-10 col-lg-8 col-md-push-1 col-lg-push-2">

                <h2>Edit Profile</h2><hr>



                <!-- <?php

                if (isset($update_error)) {

                    echo '<div class="alert alert-danger">'. $update_error . '</div>';

                } elseif (isset($update_success)) {

                    echo '<div class="alert alert-success">' . $update_success . '</div>';

                }

                ?> -->
                <?php
                if ($this->session->flashdata('update_error')) {
                    echo '<div class="alert alert-danger">'. $this->session->flashdata('update_error') . '</div>';
                } elseif ($this->session->flashdata('update_success')) {
                    echo '<div class="alert alert-success">' . $this->session->flashdata('update_success') . '</div>';
                }
                ?>
                <div class="row">

                    <div class="col-sm-3 col-md-3 col-lg-3 col-sm-push-9 col-md-push-9 col-lg-push-9">

                        <div class="hovereffect">

                            <img src="<?php echo base_url(); ?>img/profile/<?=$profile_picture;?>" class="img-responsive center-block profile-pic">

                            <div class="overlay">

                                <?php

                                $this->load->helper('form');

                                echo form_open_multipart("profile/upload_profile_pic");

                                ?>

                                <label for="userfile" class="btn btn-default">

                                    Change Profile Pic

                                </label>

                                <input type="file" id="userfile" name="userfile" size="20" onchange="javascript:this.form.submit();" style="display: none"/>



                                <?php

                                echo form_close();

                                ?>

                            </div>

                        </div>



                    </div>

                    <div class="col-sm-9 col-md-9 col-lg-9 col-sm-pull-3 col-md-pull-3 col-lg-pull-3">

                        <?php

                        $this->load->helper('form');

                        echo form_open("profile/edit");

                        ?>

                        <div class="form-group">

                            <label for="profile_email">Email:  </label>

                            <input type="email" name="profile_email" id="profile_email" placeholder="Email" class="form-control" value="<?=$profile_email;?>">

                        </div>



                        <div class="form-group">

                            <label for="profile_username">Username:  </label>

                            <input type="text" name="profile_username" id="profile_username" placeholder="Username" class="form-control" disabled="disabled" value="<?=$profile_username?>">

                        </div>



                        <div class="form-group <?=form_error('profile_fullName')?'has-error':''?>">

                            <label for="profile_fullname">Fullname:  </label>

                            <input type="text" name="profile_fullName" id="profile_fullName" placeholder="Fullname" class="form-control" value="<?=$profile_fullName;?>">

                        </div>


                        <div class="form-group">
                            <label>Level :</label>
                            <select name="student_level" id="student_level" class="form-control">
                                <?php
                                    foreach ($levels as $level) {
                                        if($stuLevel == $level->level_id) {
                                            echo '<option value="' . $level->level_id . '" selected> ' . $level->level_name . ' </option>';
                                        } else {
                                            echo '<option value="' . $level->level_id . '"> ' . $level->level_name . ' </option>';
                                        }
                                    }
                                ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>School :</label>
                            <select name="student_school" id="student_school" class="form-control">
                                <?php
                                    foreach ($schools as $school) {
                                        if($stuSchool == $school->school_id) {
                                            echo '<option value="' . $school->school_id . '" selected> ' . $school->school_name . ' </option>';
                                        } else {
                                            echo '<option value="' . $school->school_id . '"> ' . $school->school_name . ' </option>';
                                        }
                                    }
                                ?>
                            </select>
                        </div>

                        <div>

                            <a href="<?=base_url();?>profile" class="btn btn-danger">Cancel</a>

                            <input type="submit" class="btn btn-custom" value="Update" id="profile_btn">

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

<script type='text/javascript'>

    $(document).ready(function(){
        $('#student_level').change(function(){
            var level = $(this).val();  
            // AJAX request
            $.ajax({
                url:'<?=base_url()?>profile/get_school_list',
                method: 'post',
                data: {level: level},
                dataType: 'json',
                success: function(response){

                // Remove options
                $('#student_school').empty(); 

                    // Add options
                    $.each(response,function(index,data){
                        $('#student_school').append('<option value="'+data['school_id']+'">'+data['school_name']+'</option>');
                    });
                }
            });
        });
    });
</script>
