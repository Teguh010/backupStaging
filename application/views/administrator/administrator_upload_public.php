<div class="section">
	<div class="container">
	<div class="col-sm-12 col-md-12 col-lg-12">
        <!-- <?php
            if ($this->session->userdata('is_admin_logged_in') == 1) {
                echo '<a href="' . base_url() . 'administrator" class="btn btn-warning pull-right">Back to admin home page</a>';
            } elseif ($this->session->userdata('is_logged_in') == 1) {
                echo '<a href="' . base_url() . 'profile" class="btn btn-warning pull-right">Back to profile page</a>';
            }
        ?> -->
	</div>
		<div class="row">
            <?php
                if (isset($message) && isset($message_status)) {
                    echo '<div class="col-sm-12 col-md-12 col-lg-12 text-center">';
                    echo '<div class="alert ' . $message_status . '">' . $message . '</div>';
                    echo '</div>';
                }
            ?>
			<div class="col-sm-12 col-md-12 col-lg-12">
				<form class="form-horizontal" action="<?php echo base_url() ?>administrator/upload_public" method="post" enctype="multipart/form-data">
					<h2 class="text-center" style="padding:20px;"><u>Upload Public Question</u></h2>
						<div class="form-group" style="margin-top:20px;">
						<label for="graphical" class="col-sm-4 col-md-4 col-lg-4 control-label">Upload Excel File</label>
						<div class="col-sm-6 col-md-6 col-lg-6">
							<input type="file" name="file" id="file" accept=".xls, .xlsx" />
						</div>
					</div>
					<div class="form-group" style="margin-top:20px;">
						<label for="question_text" class="col-sm-4 col-md-4 col-lg-4 control-label">Folder Name</label>
						<div class="col-sm-5 col-md-5 col-lg-5">
							<input type="text" id="text_box" name="text_box">
						</div>
					</div>
				
					<div class="col-sm-5 col-md-5 col-lg-5" style="margin-left:300px;">
						<input type="submit" class="btn btn-custom btn-block" value="Upload">
					</div>
				</form>
          
			</div>
		</div>
	</div>
</div>