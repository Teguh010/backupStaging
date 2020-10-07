<div class="table-responsive">
    <table class="table datatable table-lesson">
        <thead>
            <tr class="success">
                <th>No</th>
                <th>Level & Subject</th>
                <th>Lesson</th>
                <th>Date Created</th>
                <th class="text-center">Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
				if (count($lessons) == 0) {
				    echo '<div class="clearfix">';
	    			echo '<div class="col-xs-offset-1 col-sm-offset-1 col-md-offset-1 col-lg-offset-1 col-xs-10 col-sm-10 col-md-10 col-lg-10">';
					echo '<div class="alert alert-danger margin-top-custom text-center">Don\'t have any lesson in the listing.</div>';
					echo '</div>';
					echo '</div>';
				} else {
					$i = 1;
					foreach($lessons as $row){
                        $find = ["Primary", "Secondary"];
                        $subject = $row->subject_name;
                        $subject = str_replace($find, '', $subject);
                        $level_subject = $row->level_name.' '.$subject;
                        echo '<tr class="showSection contentLesson_'.$row->lesson_id.'" id="sectionRecord'.$row->lesson_id.'" data-id="'.$row->lesson_id.'">';
                        echo '<td class="text-center">'. $i++ .'</td>';
                        echo '<td class="text-left level_subject">'. $level_subject .'</td>';
                        echo '<td class="text-left title">'. $row->title . '</td>';
                        echo '<td>'. $row->date_created . '</td>';
                        echo '<td class="text-center">';
                        echo '<button class="btn btn-icon-o radius100 btn-custom btn-no-margin-top editAssignmentBtn'.$row->lesson_id.'" onClick="editAssignment('.$row->lesson_id.')" title="Assign"><i class="fa fa-user"></i></button>';
                        echo '<button class="btn btn-icon-o radius100 btn-warning btn-no-margin-top editLessonBtn'.$row->lesson_id.'" onClick="editLesson('.$row->lesson_id.')" title="Edit Lesson"><i class="fa fa-pencil-square-o"></i></button>';
                        echo '<button class="btn btn-icon-o radius100 btn-danger btn-no-margin-top" onClick="deleteLesson('.$row->lesson_id.')" title="Delete Lesson"><i class="fa fa-trash"></i></button>';
                        echo '<a class="btn-no-margin-top text-dark showSectionBtn" style="cursor: pointer; text-decoration: none" data-id="'.$row->lesson_id.'" title="Expand/Collapse"><i class="fa fa-caret-down"></i></a>';
                        echo '</td></tr>';
                    }
                }																			
		    ?>
        </tbody>
    </table>
</div>


                                            
                                
                                            