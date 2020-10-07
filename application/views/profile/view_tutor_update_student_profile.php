<div class="section">
    <div class="container">
        <div class="row">
            <div class="col-sm-12 col-md-10 col-lg-8 col-md-push-1 col-lg-push-2">
                <h2>Edit Student Profile</h2><hr>

                <?php
                if (isset($update_error)) {
                    echo '<div class="alert alert-danger">'. $update_error . '</div>';
                } elseif (isset($update_success)) {
                    echo '<div class="alert alert-success">' . $update_success . '</div>';
                }
                ?>
                <div class="row">
                    <div class="col-sm-12 col-md-12 col-lg-12">
                        <?php
                        $this->load->helper('form');
                        echo form_open("profile/edit/" . $student_id);
                        ?>
                        <div class="form-group">
                            <label for="profile_level">Level:  </label>
                        </div>

                        <div class="form-group">
                            <label for="profile_school">School:  </label>
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