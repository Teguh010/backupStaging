
<?php $this->load->view('include/site_header'); ?>
<link href="<?php echo base_url('css/form-wizard.css'); ?>" rel="stylesheet" type="text/css">

<link rel="stylesheet" href="<?=base_url()?>css/mathquill.min.css" />
<link rel="stylesheet" href="<?=base_url()?>css/matheditor.css" />

<script src="<?=base_url()?>js/mathquill.min.js"></script>
<script src="<?=base_url()?>js/matheditor.js"></script>
<script src="<?=base_url()?>js/pages/administrator/new-question.js"></script>
<!-- <script src="https://cdn.ckeditor.com/4.14.1/standard-all/ckeditor.js"></script> -->
<script src="<?php echo base_url(); ?>js/plugins/ckeditor-4.14-full/ckeditor.js"></script>


<div class="top-fixed bg-custom">
    <div style="width: 100%">
        <div class="pull-left ml-4">
            <h3 class="title-header fs30 text-white">1. Select Subject</h3>            
        </div>
        <div class="float-right mr-3 mt-2 fs-3x">
            <a href="<?= site_url('administrator/questions') ?>" style="text-decoration: none;" class="cursor_pointer text-white"><i class="picons-thin-icon-thin-0153_delete_exit_remove_close"></i></a>
        </div>
    </div>
</div>

<form id="form-create" method="post">

<div class="section pb-60" style="margin-top: -20px;">
    <div class="container">

        <?php 
            $this->load->view('administrator/create-new-question/page1-subject');
            $this->load->view('administrator/create-new-question/page2-content');
            $this->load->view('administrator/create-new-question/page3-preview');
        ?>

    </div>
</div>

<div class="bg-dark-light bottom-fixed">
    <div class="container text-right">
        <div class="float-left">            
            <div class="form-inline panel_nav_main_question">
                <div class="form-group">
                    <span class="text-white mr-2">Main Question: </span>
                    <span class="text-white"></span>
                </div>
            </div>
            <div class="form-inline panel_nav_sub_question" style="display: none;">
                <div class="form-group">
                    <span class="text-white mr-2">Sub Question: </span>
                    <button class="btn btn-icon-o radius100 btn-success btnAddSubquestion"><i class="fa fa-plus"></i></button>
                    <button class="btn btn-icon-o radius100 btn-danger mr-4 btnRemoveSubquestion" style="display: none;"><i class="fa fa-trash"></i></button>                    
                </div>
                <div class="form-group">
                    <a class="cursor_pointer fs20 text-white btnPrevSubquestion"><i class="fa fa-chevron-left"></i></a>
                    <input type="text" id="question-page" name="subQuestionNumber" class="fs20 ml-2 mr-2" style="width: 30px; background: transparent; text-align: center; border: 0;" value="0" readonly>
                    <a class="cursor_pointer fs20 text-white btnNextSubquestion"><i class="fa fa-chevron-right"></i></a>
                </div>
            </div>
        </div>
        <a class="btn btn-lg btn-outline-warning btn-w-150 cursor_pointer font500 btnPrevious"
            style="display: none;">Previous</a>
        <a class="btn btn-lg btn-outline-light btn-w-150 cursor_pointer font500 btnNext" style="display: none;">Next</a>
        <button type="button" class="btn btn-lg btn-custom btn-w-150 cursor_pointer font500 btnSaveQuestion"
            style="display: none;">Save Question</button>
    </div>
</div>

</form>

<div class="panel_math_quill" style="display: none;">
    <div class="card-columns">
        <div class="card shadow-1" style="width: 100vw;">
            <!-- <div class="card-header-small">
                <h5 class="card-title fs14">
                    Math Expression
                </h5>

                <a class="close_panel icon_close fs14">
                    <i class="fa fa-times"></i>
                </a>
            </div> -->
            <div class="card-body-small">
                <input type="hidden" id="mathTarget" value="">
                <div id="keyboard">
                    <div role="group" aria-label="math functions">
                        <button type="button" class="btn btn-default" onClick='input("\\frac")'>Fraction
                            \(\frac{x}{y}\)</button>
                        <button type="button" class="btn btn-default" onClick='inputMultiple("^", "\\circ")'>Degree
                            \(^\circ\)</button>
                        <button type="button" class="btn btn-default" onClick='input("\\pi")'>Pi \(\pi\)</button>
                        <button type="button" class="btn btn-default" onClick='input("\\angle")'>Angle
                            \(\angle\)</button>
                        <button type="button" class="btn btn-default" onClick='inputMultiple("^", "2")'>Power
                            \(x^2\)</button>
                        <button type="button" class="btn btn-default" onClick='inputMultiple("^", "3")'>Power
                            \(x^3\)</button>
                        <button type="button" class="btn btn-default" onClick='input("mℓ")'>Millilitre mℓ</button>
                        <button type="button" class="btn btn-default" onClick='input("ℓ")'>Litre ℓ</button>
                        <button type="button" class="btn btn-default" onClick='input("\\times")'>Times
                            \(\times\)</button>
                        <button type="button" class="btn btn-default" onClick='input("\\div")'>Divide \(\div\)</button>
                        <button type="button" class="btn btn-default"
                            onClick='inputMultipleMultiple("<", "br", ">")'>Linebreak</button>
                        <a href="#" class="btn btn-default" data-toggle="modal" data-target="#addQuestionModal"><i
                                class="fa fa-copy"></i> Add Question Text </a>
                    </div>
                </div>

                <div class="form-group">
                    <span id="math-field" style="width: 100%; padding: 0.5em"></span>
                </div>
                <div class="form-group">
                    <a href="<?php echo base_url() ?>administrator/latex" target="_blank">
                        <h5>Click Here For More Math Symbol</h5>
                    </a>
                </div>
            </div>
        </div>

    </div>
</div>


<?php $this->load->view('include/site_footer'); ?>