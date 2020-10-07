<!-- <div class="section">

    <div class="container">

        <div class="row">

            <div class="col-xs-12 col-sm-5 col-md-5 col-lg-5">

                <img src="<?php echo base_url(); ?>img/img2.png" class="center-block img-responsive margin-top-custom">

            </div>

            <div class="col-xs-12 col-sm-7 col-md-7 col-lg-7">

                <h1>SmartGen</h1>

                <p>With the latest technology of SmartGen now, you can ‘smartly generate’ practice questions online

                    instead of traditional way. Do not worry about the quiz being too easy or too difficult because each

                    topic is preset with thousands of questions in different ranges of difficulty. Simply click on the

                    topic, level and adjust the scale of difficulty and you will find practice questions specifically

                    targeted at your levels of need. You can also save these questions, assign them to students and

                    generate progress reports later.</p>

            </div>

        </div>

    </div>

</div> -->



<!-- <div class="section">

    <div class="container">

        <div class="row text-center">

            <div class="col-md-8 col-md-offset-2">

                <ol class="smartgen-progress">

                    <li class="is-active" data-step="1">

                        <a href="#">Design</a>

                    </li>

                    <li data-step="2">

                        Customize

                    </li>

                    <li data-step="3" class="smartgen-progress__last">

                        Assign

                    </li>

                </ol>

            </div>

        </div>

    </div>

</div> -->



<div class="section">

    <div class="container">

        <div class="row text-center">

            <?php

            $this->load->helper('form');

            ?>

            <div class="col-md-10 col-md-offset-1">

                <form action="<?php echo base_url(); ?>smartgen/worksheetMode_validation" method="post" accept-charset="utf-8" class="form-horizontal worksheet_form">

                    <div class="panel panel-success panel-success-custom-dark">

                        <div class="panel-heading">Choose Mode to Generate Worksheet</div>

                        <ul class="list-group">

                            <div class="col-sm-4 col-md-4 col-lg-4">
                                <a href="<?php echo base_url(); ?>smartgen/designExam" style="display: block;">
                                    <img src="<?php echo base_url(); ?>img/exam.png" class="center-block img-responsive padding-exam-mode-images">
                                </a>
                                <div class="form-group">
                                    <b> Exam Mode </b>
                                    <br>
                                    <!-- <p>Description...</p> -->
                                </div>
                            </div>

                            <div class="col-sm-4 col-md-4 col-lg-4">
                                <a href="<?php echo base_url(); ?>smartgen/designWorksheet" style="display: block;">
                                    <img src="<?php echo base_url(); ?>img/quiz.png" class="center-block img-responsive padding-exam-mode-images">
                                </a>
                                <div class="form-group">
                                    <b> Quiz Mode </b>
                                    <br>
                                    <!-- <p>Description...</p> -->
                                </div>
                            </div>

                            <div class="col-sm-4 col-md-4 col-lg-4">
                                <a href="#" style="display: block;">
                                    <img src="<?php echo base_url(); ?>img/practise.png" class="center-block img-responsive padding-exam-mode-images">
                                </a>
                                <div class="form-group">
                                    <b> Practice Mode </b>
                                    <br>
                                    <!-- <p>Description...</p> -->
                                </div>
                            </div>

                            <div class="row">

                                <!-- <div class="col-xs-4 col-sm-2 col-md-2 col-lg-2 col-xs-offset-2 col-sm-offset-4 col-md-offset-4 col-lg-offset-4">

                                    <input type="submit" class="btn btn-custom" value="Go" id="gen_button">

                                </div>

                                <div class="col-xs-4 col-sm-2 col-md-2 col-lg-2">

                                    <input type="reset" class="btn btn-default" value="Reset" id="reset_button">

                                </div> -->

                            </div>

                        </ul>

                    </div>

                </form>

            </div>



        </div>

    </div>

</div>

<script type="text/javascript">

$(document).ready(function(){

    

});
</script>