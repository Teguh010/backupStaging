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

				?>
-->
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

							<img src="<?php echo base_url(); ?>img/profile/<?=$tutor_details->profile_pic;?>" class="img-responsive center-block profile-pic">

							<div class="overlay">

					           <!--<h2>Change Profile Picture</h2>-->

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

							echo form_open("administrator/tutor_profile/".$tutor_details->id);

						?>

						<div class="form-group">

							<label for="profile_email">Email:  </label>

							<input type="email" name="profile_email" id="profile_email" placeholder="Email" class="form-control" disabled="disabled" value="<?=$tutor_details->email;?>">

						</div>



						<div class="form-group">

							<label for="profile_username">Username:  </label>

							<input type="text" name="profile_username" id="profile_username" placeholder="Username" class="form-control" disabled="disabled" value="<?=$tutor_details->username;?>">

						</div>



						<div class="form-group <?=form_error('profile_fullName')?'has-error':''?>">

							<label for="profile_fullname">Fullname:  </label>

							<input type="text" name="profile_fullName" id="profile_fullName" placeholder="Fullname" class="form-control" value="<?=$tutor_details->fullname;?>">

						</div>



                        <div class="form-group">

                            <label>Link to agencies' website</label>

                            <input type="text" name="profile_agency_link" id="agency_link" placeholder="http://www.hometuitioncare.com.sg/?tutorid=12345" class="form-control" value="<?=$tutor_details->agency_link;?>">

                        </div>



                        <div class="form-group">

                            <label>Profession</label>

                            <select name="profile_profession" id="profession" class="form-control">

                                <option value="" selected="<?php echo ($profile_chosen_profession == '')?'selected':''; ?>">--- Please select ---</option>

                                <?php



                                    foreach ($profile_profession as $index => $profession) {



                                        if ($profession == $profile_chosen_profession) {

                                            echo '<option value="' . ($index+1) . '" selected="selected"> ' . $profession . ' </option>';

                                        } else {

                                            echo '<option value="' . ($index+1) . '"> ' . $profession . ' </option>';

                                        }



                                    }

                                ?>

                            </select>

                        </div>



						<h2 class="margin-top-custom">Specialization</h2><hr>

						<div class="form-group">

							<label>Maths</label>

							<div>

								<?php

									foreach ($profile_specialization as $index => $specialization) {

										echo '<label class="checkbox-inline">';

										if (in_array(($index+1), $profile_chosen_specialization)) {

											echo '<input type="checkbox" name="profile_specialization[]" value="' . ($index+1) . '" checked="checked">' . $specialization;

										} else {

											echo '<input type="checkbox" name="profile_specialization[]" value="' . ($index+1) . '">' . $specialization;

										}

										echo '</label>';

									}

								?>

							</div>

						</div>



						<div>

							<a href="<?=base_url();?>administrator/tutor_list" class="btn btn-danger">Cancel</a>

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