<?php
                
                if ($total_rows == 0) {
                    echo '<div class="clearfix text-center">';
                    echo '<div class="col-xs-offset-1 col-sm-offset-1 col-md-offset-1 col-lg-offset-1 col-xs-10 col-sm-10 col-md-10 col-lg-10">';
                    echo '<div style="font-size: 156px;"><i class="picons-thin-icon-thin-0623_not_available_broken_missing_picture_image_photo"></i></div>';
                    echo '<div class="fs24 margin-top-custom text-center">No result for lesson.</div>';
                    echo '</div>';
                    echo '</div>';
                } else {
                    $userId = $this->session->userdata('user_id');   
                    $i = 1;
                    $lesson_id = "";
					foreach($data as $lesson){                        
                        echo '<div class="row">';
            ?>
                <div class="col-lg-12 mt-4" id="card-lesson-<?= $lesson['lesson_id'] ?>">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="mt-1"><?= $lesson['title'] ?></h3>
                            <a class="fa-caret fs-2x icon_expand_plus" data-id="<?= $lesson['lesson_id'] ?>">
                                <i class="picons-thin-icon-thin-0151_plus_add_new"></i>
                            </a>
                        </div>
                        <ul class="list-group list-group-flush lesson_body_<?= $lesson['lesson_id'] ?>" style="display: none">                            

                            <?php
                                $sections = $this->M_lesson->get_section($lesson['lesson_id']);

                                $noSection = 1;

                                foreach($sections as $section){

                                    echo '
                                        <div class="bg-success">
                                        <li class="list-group-item p-3 fs20">
                                            ['.$noSection++.'] '.$section['section_title'].'                   
                                        </li></div>
                                    ';

                                    $lectures = $this->M_lesson->get_lecture($section['section_id']);                                                                    
                                    
                                    foreach($lectures as $lecture){

                                            $moduleContent = '';

                                            if ($lecture['uploaded_video'] != '' && $lecture['uploaded_video_type'] == 'local') {                                                
                                                $moduleContent .= '<a href="'.base_url().'/uploaded_file/video/'.$lecture['uploaded_video'].'" class="btn play-video btn-shadow btn-icon btn-rounded btn-outline-teal" onClick="clickViewModue('.$lecture['id'].', '."'video'".')" style="width: 120px;"><i class="fa fa-play"></i>Video</a>';
                                            } else if ($lecture['uploaded_video'] != '' && $lecture['uploaded_video_type'] == 'embed') {
                                                $moduleContent .= '<a href="'.$lecture['uploaded_video'].'" class="btn play-video btn-shadow btn-icon btn-rounded btn-outline-teal" onClick="clickViewModue('.$lecture['id'].', '."'video'".')" style="width: 120px;"><i class="fa fa-play"></i>Video</a>';
                                            } else if ($lecture['uploaded_video'] != '' && $lecture['uploaded_video_type'] == 'gdrive') {
                                                $moduleContent .= '<a href="https://drive.google.com/file/d/'.$lecture['uploaded_video'].'/preview" class="btn play-video btn-shadow btn-icon btn-rounded btn-outline-teal" onClick="clickViewModue('.$lecture['id'].', '."'video'".')" style="width: 120px;"><i class="fa fa-play"></i>Video</a>';
                                            }


                                            if ($lecture['uploaded_doc'] != '') {
                                                if ($lecture['uploaded_doc_type'] == 'gdrive') {
                                                    $moduleContent .= '<div class="dropdown uploaded_doc" style="display: inline;">
                                                                            <button class="btn btn-shadow btn-icon btn-rounded btn-outline-danger dropdown-toggle" style="width: 120px;" type="button" data-toggle="dropdown">
                                                                            <i class="fa fa-file-text-o"></i> Document</button>
                                                                            <ul class="dropdown-menu">
                                                                                <li class="dropdown-header">Open document in...</li>
                                                                                <li>
                                                                                    <a style="cursor: pointer;" href="https://drive.google.com/file/d/'.$lecture['uploaded_doc'].'/preview" onClick="clickViewModue('.$lecture['id'].', '."'document'".')" target="_blank"><i class="fa fa-google mr-2"></i> Google Doc</a>
                                                                                </li>                                                                
                                                                                <li class="divider"></li>                                                                
                                                                                <li>
                                                                                    <a style="cursor: pointer;" href="https://drive.google.com/file/d/'.$lecture['uploaded_doc'].'/edit" target="_blank"><i class="fa fa-download mr-2"></i> Download</a>
                                                                                </li>                                                                                             
                                                                            </ul>
                                                                        </div>';
                                                } else {
                                                    $moduleContent .= '<div class="dropdown" style="display: inline;">
                                                                            <button class="btn btn-shadow btn-icon btn-rounded btn-outline-danger dropdown-toggle" style="width: 120px;" type="button" data-toggle="dropdown">
                                                                            <span class="fa fa-file-text-o"></span> Document</button>
                                                                            <ul class="dropdown-menu">
                                                                                <li class="dropdown-header">Open document in...</li>
                                                                                <li>
                                                                                    <a style="cursor: pointer;" href="https://docs.google.com/gview?url='.base_url().'uploaded_file/doc/'.$lecture['uploaded_doc'].'&embedded=true" onClick="clickViewModue('.$lecture['id'].', '."'document'".')" target="_blank"><i class="fa fa-google mr-2"></i> Google Doc</a>
                                                                                </li>
                                                                                <li>
                                                                                    <a style="cursor: pointer;" href="https://view.officeapps.live.com/op/view.aspx?src='.base_url().'uploaded_file/doc/'.$lecture['uploaded_doc'].'" onClick="clickViewModue('.$lecture['id'].', '."'document'".')" target="_blank"><i class="fa fa-windows mr-2"></i> Ms. Office Online</a>
                                                                                </li>                                                                
                                                                                <li class="divider"></li>                                                                
                                                                                <li>
                                                                                    <a style="cursor: pointer;" href="'.base_url().'uploaded_file/doc/'.$lecture['uploaded_doc'].'" target="_blank"><i class="fa fa-download mr-2"></i> Download</a>
                                                                                </li>                                                                                             
                                                                            </ul>
                                                                        </div>';
                                                }
                                
                                            }


                                            if ($lecture['worksheet_id'] > 0) {
                                                $quiz_id = $this->M_lesson->get_quiz_by_worksheet($lecture['worksheet_id'], $userId);
                                                if($quiz_id > 0){
                                                    $moduleContent .= '<a href="'.base_url().'onlinequiz/quiz/'.$quiz_id.'" target="_blank" class="btn btn-shadow btn-icon btn-rounded btn-outline-success" title="View Assessment" style="width: 120px;"><i class="fa fa-clipboard"></i>Quiz</a>';
                                                }      
                                            }


                                            echo '
                                                <li class="list-group-item">
                                                    <div class="qc-card question-bar-padding">   
                                                        <div>
                                                            <div class="bk-icon-container">
                                                                <i class="fa fa-check"></i>
                                                            </div>   
                                                            
                                                            <span class="question-title">
                                                                '.$lecture['lecture_title'].'
                                                            </span> 
                                                            
                                                            <div class="right-el">
                                                                '.$moduleContent.'
                                                            </div> 
                                                        </div>
                                                    </div>
                                                </li>
                                            ';
                                    }

                                }
                            ?>
                        </ul>
                        
                    </div>
                </div>

            <?php 
                        echo '</div>';
                        $lesson_id = $lesson['lesson_id'];
                    }
                } 
            ?>        

<div class="pagination-container text-center">
    <?= $pagination; ?>
</div>