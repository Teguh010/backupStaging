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
                        echo form_open("profile/change_password");
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
                            <a href="<?=base_url();?>profile" class="btn btn-danger">Cancel</a>
                            <input type="submit" class="btn btn-custom" value="Change password" id="profile_btn">
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