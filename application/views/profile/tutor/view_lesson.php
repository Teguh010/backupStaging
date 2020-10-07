<?php
    // var_dump($this->session->userdata('lessonId'));
?>
<link href="<?php echo base_url('css/form-wizard.css'); ?>" rel="stylesheet" type="text/css">
<script src="<?= base_url('js/pages/lessons.js?'.date('YmdHis')) ?>"></script>


<div class="section" style="margin-top: -10px;">
    
    <div class="container form-lesson" style="display: none;">
        <section>
            <div class="wizard">
                <div class="wizard-inner">
                    <div class="connecting-line"></div>
                    <ul class="nav nav-tabs" role="tablist">

                        <li role="presentation" class="active">
                            <a href="#step1" data-toggle="tab" aria-controls="step1" role="tab" title="Step 1">
                                <span class="round-tab">
                                    <i class="fa fa-folder-open"></i>
                                </span>
                            </a>
                        </li>

                        <li role="presentation" class="disabled">
                            <a href="#step2" data-toggle="tab" aria-controls="step2" role="tab" title="Step 2">
                                <span class="round-tab">
                                    <i class="fa fa-pencil"></i>
                                </span>
                            </a>
                        </li>
                        <li role="presentation" class="disabled">
                            <a href="#step3" data-toggle="tab" aria-controls="step3" role="tab" title="Step 3">
                                <span class="round-tab">
                                    <i class="fa fa-file-o"></i>
                                </span>
                            </a>
                        </li>

                        <li role="presentation" class="disabled">
                            <a href="#complete" data-toggle="tab" aria-controls="complete" role="tab" title="Complete">
                                <span class="round-tab">
                                    <i class="fa fa-check"></i>
                                </span>
                            </a>
                        </li>
                    </ul>
                </div>

                <div role="form">
                    <div class="tab-content">

                        <div class="tab-pane active" role="tabpanel" id="step1">
                            <div class="p-3" style="margin-top: -40px;">
                                <form id="form-lesson" name="form-lesson" method="post">
                                    <div class="row p-1">
                                        <div class="col-lg-1"></div>                                    
                                        <label class="col-lg-3" for="level_id">Level and Subject <span class="text-danger">*</span></label>
                                        <div class="col-lg-7">
                                            <select class="form-control" id="level_id" name="level_id" style="padding-bottom: -1px;">
                                                <option value="">Select One</option>
                                            </select>
                                        </div>
                                        <div class="col-lg-1"></div>
                                    </div>
                                    <div class="row p-1">
                                        <div class="col-lg-1"></div>
                                        <label class="col-lg-3" for="title">Lesson Title <span class="text-danger">*</span></label>
                                        <div class="col-lg-7">
                                            <input type="text" class="form-control" id="lesson_title" name="lesson_title" placeholder="Enter Lesson Title">
                                        </div>
                                        <div class="col-lg-1"></div>
                                    </div>
                                    <div class="row p-1">
                                        <div class="col-lg-1"></div>                                    
                                        <label class="col-lg-3" for="title">Tags (e.g: tag1,tag2)</label>
                                        <div class="col-lg-7">
                                            <input type="text" class="form-control" id="tags" name="tags" placeholder="Enter Tags">
                                        </div>
                                        <div class="col-lg-1"></div>
                                    </div>
                                    <div class="row p-1">
                                        <div class="col-lg-1"></div>                                    
                                        <label class="col-lg-3" for="title">Description. <span class="text-danger">*</span></label>
                                        <div class="col-lg-7">
                                            <textarea class="form-control description" id="description" name="description"
                                                placeholder="Enter Description"></textarea>
                                        </div>
                                        <div class="col-lg-1"></div>
                                    </div>
                                    <div class="row p-1">
                                        <div class="col-lg-1"></div>
                                        <div class="col-lg-3"></div>
                                        <div class="col-lg-7 text-right">
                                            <button type="button" id="SaveLessonBtn" class="btn btn-rounded btn-outline-primary">Save and
                                                continue</button>
                                            <button type="button" class="btn btn-rounded btn-outline-primary next-step" id="next-step1" style="display: none; width: 125px;">Next</button>
                                        </div>
                                        <div class="col-lg-1"></div>
                                    </div>
                                </form>
                            </div>                            
                        </div>
                        
                        <div class="tab-pane" role="tabpanel" id="step2">
                            
                            <div class="p-3" style="margin-top: -40px;">
                            <form id="form-section" name="form-section" method="post">
                                <div class="row card-grid">
                                    <div class="col-lg-6 mt-3" id="card-section1">
                                        <div class="card">
                                            <div class="card-header-small">
                                                <input type="text" class="form-control input_style1_red section_title" style="width: 80%" id="section_title1" name="section_title[]" placeholder="Section Title" />
                                                <a class="fa-caret icon_expand2 ml-5">
                                                    <i class="fa fa-caret-down"></i>
                                                </a>        
                                                <a class="close_question icon_close">
                                                    <i class="fa fa-times"></i>
                                                </a>
                                            </div>                                        
                                            <div class="card-body-small">
                                                <div class="row">
                                                    <div class="col-lg-12 subsection1">
                                                        <div class="form-inline p-1">
                                                            <div class="form-group mb-2" style="width: 100%">
                                                                <input type="text" class="form-control input_style1_black subsection" name="subsection1[]"
                                                                    placeholder="Lecture" style="width: 80%" />
                                                                <a style="cursor: pointer; text-decoration: none;"
                                                                    class="removeLecture text-danger-active fs18 ml-2"><i
                                                                        class="fa fa-times"></i></a>     
                                                            </div>                                                                                                     
                                                        </div>
                                                    </div>

                                                    <div class="col-lg-12 col-sm-12 m-1 navNewLecture">
                                                        <a style="cursor: pointer; text-decoration: none;"
                                                            class="newLecture text-success-active fs14" data-id="1"><i
                                                                class="fa fa-plus mr-1"></i> Add
                                                            new lecture</a>
                                                    </div>
                                                </div>
                                            </div>                                        
                                        </div>
                                    </div>
                                </div>
                            </form>
                            </div>

                            <ul class="list-inline pull-left">
                                <li><button type="button" id="btnNewSection" class="btn btn-rounded btn-warning ml-4"
                                            style="margin-right:20px"><i class="fa fa-plus"></i> New Section</button></li>
                            </ul>

                            <ul class="list-inline pull-right">
                                <li><button type="button" class="btn btn-rounded btn-outline-info prev-step" style="width: 125px;">Previous</button></li>
                                <li>
                                    <button type="button" class="btn btn-rounded btn-outline-primary" id="SaveSectionBtn">Save and
                                        continue</button>
                                    <button type="button" class="btn btn-rounded btn-outline-primary next-step" id="next-step2" style="display: none; width: 125px;">Next</button>
                                </li>
                            </ul>
                        </div>

                        <div class="tab-pane" role="tabpanel" id="step3">
                            <div class="p-3" style="margin-top: -40px;">
                                <h3>Upload video and document </h3>
                                <p>You can skip this step</p>
                                <div class="row" style="display: none;">
                                    <div class="col-lg-12">
                                        <form id='form-upload' method='post' enctype="multipart/form-data">
                                            <input type="file" id="file_upload" name="file_upload" />                                            
                                        </form>
                                    </div>
                                </div>
                                <div class="row load-section-lecture"></div>
                                <ul class="list-inline pull-right mt-3">
                                    <li><button type="button" class="btn btn-rounded btn-outline-info prev-step" style="width: 125px;">Previous</button></li>                                
                                    <li><button type="button" class="btn btn-rounded btn-outline-primary next-step" onclick="addAssignment()" style="width: 125px;">Continue</button></li>
                                </ul>
                            </div>
                        </div>

                        <div class="tab-pane" role="tabpanel" id="complete">
                            <h3>Assignment</h3>
                            <p>Please, assign your student.</p>
                            
                            <div class="assign_lesson_new">
                                <?php $this->load->view('profile/tutor/assign_lesson'); ?>
                            </div>                            

                            <ul class="list-inline pull-right mt-3">                                                      
                                <li><button type="button" class="btn btn-icon btn-rounded btn-custom" id="OKBtn" style="width: 150px;"><i class="fa fa-check"></i>Finalize</button></li>
                            </ul>
                        </div>

                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <div class="container list-lesson">

        <div class="row">
            <div class="col-lg-6 col-sm-12 text-left">
                <button id="btnNewLesson" class="btn btn-icon btn-rounded btn-outline-primary"><i class="fa fa-plus"></i> Create New Lesson</button>
            </div>
            
        </div>
        
        <div class="worksheet_div" id="list-lesson">
            <div class="worksheet_div_header">
                <i class="fa fa-minus pull-right fa_minimize_div"></i>
                <h4>My Lessons</h4>
            </div>
            <div class="worksheet_div_body">
                <?php $this->load->view('profile/tutor/table_lesson'); ?>
            </div>
        </div>


    </div>

    <div class="container form-edit-assignment" style="margin-top: -20px; display: none;">
        <section>
            <h3>Assignment</h3>
            <p>Please, assign your student.</p>
                            
            <div class="assign_lesson_edit"></div>

            <div class="row text-center">                                                      
                <div class="col-12">
                    <button type="button" class="btn btn-icon btn-rounded btn-outline-warnig" id="cancelAssignmentBtn" style="width: 150px;"><i class="fa fa-reply"></i>Back</button>
                    <button type="button" class="btn btn-icon btn-rounded btn-custom" id="updateAssignmentBtn" style="width: 150px;"><i class="fa fa-check"></i>Finalize</button>
                </li>
            </div>                        
        </section>
    </div>

</div>


<div class="modal fade" id="modalStudentList" role="dialog">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
						aria-hidden="true">&times;</span></button>
				<h4 class="modal-title lecture-title"></h4>
			</div>			

			<div class="modal-body">
				<table class="table table-student">
                    <thead>
                        <tr>
                            <th width="5%"></th>
                            <th width="10%"></th>
                            <th width="35%">Student Name</th>
                            <th width="50%">Last Seen</th>
                        </tr>
                    </thead>
                    <tbody>
                    
                    </tbody>
                </table>
			</div>
			
		</div>
	</div>
</div>


<div class="modal fade" id="modalAssessment" role="dialog">
	<div class="modal-dialog modal-sm" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
						aria-hidden="true">&times;</span></button>
				<h4 class="modal-title">Assessment</h4>
			</div>			

			<div class="modal-body">
				<div class="row">
                    <div class="col-12 p-2">
                        <input type="hidden" id="lecture_id" />
                        <input type="hidden" id="assessment_status" />
                        <select class="form-control" id="worksheet_id"></select>
                    </div>
                    <div class="col-12 text-center">
                        <button class="btn btn-icon btn-rounded btn-sm btn-success" id="saveAssessmentBtn" style="width: 100px"><i class="fa fa-floppy-o"></i>Save</button>                        
                    </div>
                </div>
			</div>
			
		</div>
	</div>
</div>