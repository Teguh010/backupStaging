<?php if($page == 'edit-student-profile'){ ?>

<div class="section">
    <div class="container">
        <div class="row">
            <div class="col-sm-12 col-md-10 col-lg-8 col-md-push-1 col-lg-push-2">
                <h2>Edit Student Profile</h2><hr>                
                <form id="student-profile" method="post" enctype="multipart/form-data">
                    <input type="hidden" id="user_id" name="user_id" value="<?= $user_id ?>" />
                    <input type="hidden" id="changed_picture" name="changed_picture" value="<?= $profile_picture ?>" />
                    <div class="row">
                        <div class="col-sm-3 col-md-3 col-lg-3 col-sm-push-9 col-md-push-9 col-lg-push-9">
                            <div class="hovereffect">
                                <img id="preview" src="<?= base_url('img/profile/'.$profile_picture) ?>" class="img-responsive center-block profile-pic">
                                <div class="overlay">
                                    <label for="userfile" class="btn btn-default">Change Profile Pic</label>
                                    <input type="file" id="userfile" name="userfile" size="20" onchange="previewImage(event)" style="display: none"/>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-9 col-md-9 col-lg-9 col-sm-pull-3 col-md-pull-3 col-lg-pull-3">

                            <div class="form-group">
                                <label class="lb-email" for="profile_email">Email :  </label>
                                <input type="email" name="profile_email" id="profile_email" placeholder="Email" class="form-control" value="<?=$profile_email;?>">
                            </div>

                            <div class="form-group">
                                <label class="lb-username" for="profile_username">Username :  </label>
                                <input type="text" name="profile_username" id="profile_username" placeholder="Username" class="form-control" disabled="disabled" value="<?=$profile_username?>">
                            </div>

                            <div class="form-group">
                                <label class="lb-fullname" for="profile_fullname">Fullname:  </label>
                                <input type="text" name="profile_fullName" id="profile_fullName" placeholder="Fullname" class="form-control" value="<?=$profile_fullName;?>">
                            </div>

                            <div class="form-group">
                                <label class="lb-leve">Level :</label>
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
                                <label class="lb-school" >School :</label>
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
                                <a href="<?= base_url('administrator/student_list') ?>" class="btn btn-danger">Cancel</a>
                                <button type="button" class="btn btn-custom" id="profile_btn">Update</button>
                            </div>

                        </div>

                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php }else if($page == 'change-student-password'){ ?>

    <div class="section">
    <div class="container">
        <div class="row">
            <div class="col-sm-12 col-md-10 col-lg-8 col-md-push-1 col-lg-push-2">
                <h2>Change Password</h2><hr>
                <?php
                    if (isset($update_error)) {
                        echo '<div class="alert alert-danger">'. $update_error . '</div>';
                    } elseif (isset($update_success)) {
                        echo '<div class="alert alert-success">' . $update_success . '</div>';
                    }
                ?>

                <div class="row">
                    <div class="col-sm-9 col-md-9 col-lg-9">
                        <?php
                            $this->load->helper('form');
                            echo form_open("administrator/change_student_password/".$user_id);
                        ?>

                        <div class="form-group <?=form_error('profile_password')?'has-error':''?>">
                            <label for="profile_password">Password:  </label>
                            <input type="password" name="profile_password" id="profile_password" placeholder="Password" class="form-control">
                        </div>

                        <div class="form-group <?=form_error('profile_cpassword')?'has-error':''?>">
                            <label for="profile_cpassword">Confirm password:  </label>
                            <input type="password" name="profile_cpassword" id="profile_cpassword" placeholder="Confirm Password" class="form-control">
                        </div>

                        <div>
                            <a href="<?=base_url();?>administrator/student_list" class="btn btn-danger">Cancel</a>
                            <input type="submit" class="btn btn-custom" value="Change password" id="profile_btn">
                        </div>

                    </div>
                    <?php echo form_close(); ?>
                </div>

            </div>
        </div>
    </div>
</div>

<?php } ?>