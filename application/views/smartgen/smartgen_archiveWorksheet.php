<div class="section" style="height:500px;">
<div class="container">
    <div class="row">
		<div style="margin-bottom: 10px;">
			<a href="<?php echo base_url(); ?>profile" class="btn btn-custom btn-no-margin-top" style="margin-left: 0px;">Back</a>
		</div>
            <div class="profile_div">
                <div class="profile_div_header">
                    <h4>Archived Worksheets</h4>
                </div>
                <div class="profile_div_body">
                    <div class="table-responsive">
                        <table class="table profile_table">
                            <tr class="success">
                                <th>No</th>
                                <th>Worksheet Name</th>
                                <th>Archived Date</th>
                                <th>Created Date</th>
                                <th>Action</th>
                            </tr>
                            <?php
                            if (count($archiveList['archive']->result()) == 0) {
                                echo '<tr>';
                                echo '<td colspan="5"><div class="alert alert-danger margin-top-custom text-center">You don\'t have any archived worksheet at the moment.</div></td>';
                                echo '</tr>';
                            } else {
	                            $key = $this->uri->segment(3) + 1;
								foreach ($archiveList['archive']->result() as $archive) {
									echo '<tr>';
									echo '<td>'.$key++.'</td>';
									echo '<td>' . $archive->worksheet_name. '</td>';
									echo '<td>' . $archive->archived_date. '</td>';
									echo '<td>' . $archive->created_date. '</td>';
									echo '<td>';
									echo '<a href="'.base_url().'profile/worksheet/'.$archive->worksheet_id.'" class="btn btn-warning btn-no-margin-top">View online/Download as PDF</a>';
									echo '<button class="btn btn-custom btn-no-margin-top unarchive_worksheet_btn" data-toggle="modal" data-target="#unarchiveWorksheetModal" id="worksheet_'.$archive->worksheet_id.'">Unarchive</button>';
									echo '</td>';
									echo '</tr>';
								}
                            }
                            ?>
                        </table>
                    </div>
                    <div class="pull-right">
						<h4><?php echo $link; ?></h4>
					</div>
                </div>
            </div>
    </div>
</div>
</div>

<div class="modal fade" id="unarchiveWorksheetModal" role="dialog" tabindex="-1">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header modal-header-custom-alert">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="worksheetModalLabel">Unarchive worksheet</h4>
			</div>
			<form class="form-horizontal" action="<?php echo base_url(); ?>profile/unarchiveWorksheet" method="post" accept-charset="utf-8" id="delete_worksheet_form">
				<div class="modal-body">
					Confirm to unarchive worksheet? 
				</div>
				<div class="modal-footer">
					<input type="hidden" name="worksheetId" id="archive_worksheet_id">
					<input type="button" class="btn btn-default" data-dismiss="modal" value="Close">
					<input type="submit" class="btn btn-danger" id="confirm_delete_button" value="Unarchive">
				</div>
			</form>
		</div>
	</div>
</div>