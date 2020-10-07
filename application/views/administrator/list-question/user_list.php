

    <div class="container pt-40 pb-40">

<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 text-center" style="margin-bottom:5px;">
        <?php 
            if($total_rows == 0){
                echo "<span class='text-default total_result_question'>Question not found</span>
                        <br><br>
                        <div class='fs-3x'><i class='picons-thin-icon-thin-0039_smiley_sad_face_unhappy'></i></div>
                ";
            }else{
                //echo "<span class='text-default total_result_question'>Question $total_rows results</span>";
            }
            
            // if want to show execute time (".number_format($duration,2)." seconds)
        ?>
    </div>
</div> 

<!-- <div class="grid"> -->
<div class="card-columns">
<?php 
    $quesNum = 1; 
    foreach($data as $row) : ?>
    <div class="card card_question_<?php echo $row->user_id ?>">
        <div class="card-header-small">
            <h5 class="card-title card_question_title fs14" id="card_question_title_<?php echo $row->user_id ?>">
                <?php						

                    // $label_text = $row->level_name;						
                    // $subject_name = str_replace(['Primary ','Secondary '], ['',''], $row->subject_name);
                    // $label_text = $label_text.' '.$subject_name;

                    // if($subject_name == 'Maths'){
                    //     $label_class = 'label label-warning';
                    // }else if($subject_name == 'English'){
                    //     $label_class = 'label label-primary';
                    // }else if($subject_name == 'Science'){
                    //     $label_class = 'label label-danger';
                    // }

                    // $title = $label_text.$row->substrand_name.$row->topic_name;
                    // if(strlen($title) > 35){
                    //     $card_title = substr($row->substrand_name.' / '.$row->topic_name, 0, 34).'...';
                    // }else{
                    //     $card_title = $row->substrand_name.' / '.$row->topic_name;
                    // }
                    
                ?>
                <!-- <span class="<?php 
                    // echo $label_class;
                ?>
                mr-2"><?php
                //echo $label_text;
                ?>
                </span><span class="label label-default"><?php
                // echo $row->substrand_name;
                ?>
                </span> -->
                
            </h5>				
        </div>			
        <div class="card-body-small fs14 card_question_text">   
            <div class="card_question_body">
                <div class="row pt-10">

                    <div class="col-lg-12">
                        <div class="form-inline">
                            <div class="customUi-radio radioUi-success mr-1">
                                <input type="checkbox" readonly checked>
                                <label>
                                    <span class="label-radio"></span>												
                                </label>
                            </div>
                        </div>
                        <span class="fs14 font300"><?php echo empty($row->fullname) == FALSE?$row->fullname:'-'; ?></span>
                    </div>

                    <div class="col-lg-12">
                        <div class="form-inline">
                            <div class="customUi-radio radioUi-success mr-1">
                                <input type="checkbox" readonly checked>
                                <label>
                                    <span class="label-radio"></span>												
                                </label>
                            </div>
                        </div>
                        <span class="fs14 font300"><?php echo empty($row->username) == FALSE?$row->username:'-'; ?></span>
                    </div>

                    <div class="col-lg-12">
                        <div class="form-inline">
                            <div class="customUi-radio radioUi-success mr-1">
                                <input type="checkbox" readonly checked>
                                <label>
                                    <span class="label-radio"></span>												
                                </label>
                            </div>
                        </div>
                        <span class="fs14 font300"><?php echo empty($row->email) == FALSE?$row->email:'-'; ?></span>
                    </div>

                    <div class="col-lg-12">
                        <div class="form-inline">
                            <div class="customUi-radio radioUi-success mr-1">
                                <input type="checkbox" readonly checked>
                                <label>
                                    <span class="label-radio"></span>												
                                </label>
                            </div>
                        </div>
                        <span class="fs14 font300"><?php echo empty($row->status) == 1?'Active':'Inactive'; ?></span>
                    </div>

                </div>
            <?php 
                // Answer List

                // if ($row->question_type_id == 1 || $row->question_type_id == 4 || $row->question_type_id == 8) {	
                //     $answerOption = $answerList[$quesNum-1]['answerOption'];
                //     echo "<div class='row pt-10'>";
                //     foreach ($answerOption as $option) {
                        
                //         $correctAnswer = $answerList[$quesNum-1]['correctAnswer'];							
                //         $checked = "";
                //         if($correctAnswer == $option->answer_text) {
                //             $checked = "checked";
                //         }

                //         echo '	<div class="col-lg-12">
                //                     <div class="form-inline">
                //                         <div class="customUi-radio radioUi-success mr-1">
                //                             <input type="checkbox" readonly '.$checked.'>
                //                             <label>
                //                                 <span class="label-radio"></span>												
                //                             </label>
                //                         </div>
                //                         <span class="fs14 font300">'.$option->answer_text.'</span>
                //                     </div>
                //                 </div>';									
                //     }
                //     echo "</div>";
                    
                // }else if($row->question_type_id == 2){

                //     $answerOption = $answerList[$quesNum-1]['answerOption'];
                //     foreach ($answerOption as $key=>$option) {
                //         $correctAnswer = $answerList[$quesNum-1]['correctAnswer'];
                //         $class = "";
                //         $icon = "";
                //         if($correctAnswer == $option->answer_text) {
                //             $class .= "correctAnswer ";
                //             $icon .= "<i class='fa fa-check answeredCorrectly'></i>";
                //             echo '<br><span class="'.$class.' fs14">Ans: ' . $option->answer_text . '</span>';
                //         }
                //     }
                    
                // }else if($row->question_type_id == 3){
                //     $answerOption = $answerList[$quesNum-1]['answerOption'];						
                //     echo "<div class='row pt-10'>";						
                //     foreach ($answerOption as $option) {
                //         echo '<div class="col-lg-12">
                //                 <div class="form-inline">
                //                     <div class="customUi-radio radioUi-success mr-1">
                //                         <input type="radio" readonly '.(($option->answer_text==1)?'checked':'').'>
                //                         <label>
                //                             <span class="label-radio"></span>												
                //                         </label>
                //                     </div>
                //                     <span class="fs14 font300 mr-4">True</span>

                //                     <div class="customUi-radio radioUi-danger mr-1">
                //                         <input type="radio" readonly '.(($option->answer_text==0)?'checked':'').'>
                //                         <label>
                //                             <span class="label-radio"></span>												
                //                         </label>
                //                     </div>
                //                     <span class="fs14 font300">False</span>
                //                 </div>
                //             </div>';									
                    
                //     }
                //     echo "</div>";
                // }

                $quesNum++;
            ?>
            </div>
        </div>
        <div class="card-footer text-muted text-right">
            <div class="fs14 float-left">
                <?php 
                    // echo '<span class="marks mr-3">'.$row->difficulty.' Marks</span>'; 
                    
                    // if($row->total_question > 1){						
                    //     echo '<a class="cursor_pointer prev_question" data-id="'.$row->question_id.'"><i class="fa fa-angle-double-left"></i></a>';
                    //     echo '<input type="text" class="label_total_question" class="ml-2 mr-2" style="width: 30px; background: transparent; text-align: center; border: 0;" value="1/'.$row->total_question.'" readonly>';
                    //     echo '<input type="hidden" class="total_question" value="'.$row->total_question.'">';
                    //     echo '<input type="hidden" class="page_question" value="1">';
                    //     echo '<a class="cursor_pointer next_question" data-id="'.$row->question_id.'"><i class="fa fa-angle-double-right"></i></a>';
                    // }
                ?>				
            </div>

            <a class="cursor_pointer text-teal font500 mr-2" title="Preview"><i class="picons-thin-icon-thin-0043_eye_visibility_show_visible"></i></a>
            <a class="cursor_pointer text-warning font500 mr-2" title="Edit"><i class="picons-thin-icon-thin-0001_compose_write_pencil_new"></i></a>
            <!-- <a class="text-danger font500" title="Delete"><i class="picons-thin-icon-thin-0056_bin_trash_recycle_delete_garbage_empty"></i></a> -->
        </div>
    </div>			
<?php endforeach; ?>
</div>
<!-- </div> -->

<div class="pagination-container text-center">
<?php echo $pagination; ?>
</div>

</div>

