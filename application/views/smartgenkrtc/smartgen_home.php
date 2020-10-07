<div class="section">
    <div class="container">
        <div class="row">
            <div class="col-xs-12 col-sm-5 col-md-5 col-lg-5">
                <img src="<?php echo base_url(); ?>img/img2.png" class="center-block img-responsive margin-top-custom">
            </div>
            <div class="col-xs-12 col-sm-7 col-md-7 col-lg-7">
                <h1>SmartGen for KRTC</h1>
                <p>With the latest technology of SmartGen now, you can ‘smartly generate’ practice questions online
                    instead of traditional way. Do not worry about the quiz being too easy or too difficult because each
                    topic is preset with thousands of questions in different ranges of difficulty. Simply click on the
                    topic, level and adjust the scale of difficulty and you will find practice questions specifically
                    targeted at your levels of need. You can also save these questions, assign them to students and
                    generate progress reports later.</p>
            </div>
        </div>
    </div>
</div>

<div class="section">
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
</div>

<div class="section">
    <div class="container">
        <div class="row text-center">
            <?php
            $this->load->helper('form');
            ?>
            <div class="col-md-10 col-md-offset-1">
                <form
                    action="<?php echo base_url(); ?>smartgenkrtc/generateWorksheet/<?php echo (isset($worksheetId) && empty($worksheetId) === false) ? $worksheetId : ''; ?>"
                    method="post" accept-charset="utf-8" class="form-horizontal worksheet_form">
                    <div class="panel panel-success panel-success-custom-dark">
                        <div class="panel-heading">Start Generating Worksheet</div>
                        <ul class="list-group">
                            <li class="list-group-item">
                                <div class="form-group">
                                    <label for="" class="control-label col-xs-12 col-sm-4 col-md-4 col-lg-4">KRTC Tutor
                                        :</label>
                                    <div class="col-sm-7 col-md-7 col-lg-7">
                                        <?php
                                        if (isset($selectedTutor) && empty($selectedTutor) === false) {
                                            // TODO : check the session to set the relevant levels
                                            echo '<select name="gen_tutor" class="form-control">';
                                            foreach ($tutors AS $tutor) {
                                                if ($selectedTutor == $tutor->id) {
                                                    echo '<option value="' . $tutor->id . '" selected="selected">' . $tutor->fullname . '</option>';
                                                } else {
                                                    echo '<option value="' . $tutor->id . '">' . $tutor->fullname . '</option>';
                                                }
                                            }
                                            echo '</select>';
                                        } else {
                                            ?>
                                            <select name="gen_tutor" class="form-control">
                                                <?php
                                                foreach ($tutors AS $tutor) {
                                                    echo '<option value="' . $tutor->id . '">' . $tutor->fullname . '</option>';
                                                }
                                                ?>
                                            </select>

                                            <?php
                                        }
                                        ?>
                                    </div>
                            </li>

                            <li class="list-group-item">
                                <div class="form-group">
                                    <label for="" class="control-label col-xs-12 col-sm-4 col-md-4 col-lg-4">Mock Exams
                                        :</label>
                                    <div class="col-sm-7 col-md-7 col-lg-7">
                                        <?php
                                        if (isset($selectedMe) && empty($selectedMe) === false) {
                                            // TODO : check the session to set the relevant levels
                                            echo '<select name="gen_me" class="form-control">';
                                            foreach ($mock_exams AS $key => $value) {
                                                if ($selectedMe == $value) {
                                                    echo '<option value="' . $key . '" selected="selected">' . $value . '</option>';
                                                } else {
                                                    echo '<option value="' . $key . '">' . $value . '</option>';
                                                }
                                            }
                                            echo '</select>';
                                        } else {
                                            ?>
                                            <select name="gen_me" class="form-control">
                                                <?php
                                                foreach ($mock_exams AS $key => $value) {
                                                    echo '<option value="' . $key . '">' . $value . '</option>';
                                                }
                                                ?>
                                            </select>

                                            <?php
                                        }
                                        ?>
                                    </div>
                            </li>

                            <li class="list-group-item">
                                <div class="form-group">
                                    <label for="" class="control-label col-xs-12 col-sm-4 col-md-4 col-lg-4">Year
                                        :</label>
                                    <div class="col-sm-7 col-md-7 col-lg-7">
                                        <?php
                                        if (isset($selectedYear) && empty($selectedYear) === false) {
                                            echo '<select name="gen_year" class="form-control">';
                                            foreach ($years AS $key => $year) {
                                                if ($selectedYear == $key) {
                                                    echo '<option value="' . $key . '" selected="selected">' . $year . '</option>';
                                                } else {
                                                    echo '<option value="' . $key . '">' . $year . '</option>';
                                                }
                                            }
                                            echo '</select>';
                                        } else {
                                            ?>
                                            <select name="gen_year" class="form-control">
                                                <?php
                                                foreach ($years AS $key => $value) {
                                                    echo '<option value="' . $key . '">' . $value . '</option>';
                                                }
                                                ?>
                                            </select>

                                            <?php
                                        }
                                        ?>
                                    </div>
                            </li>

                            <li class="list-group-item">
                                <div class="form-group">
                                    <label for="" class="control-label col-xs-12 col-sm-4 col-md-4 col-lg-4">Level
                                        :</label>
                                    <div class="col-sm-7 col-md-7 col-lg-7">
                                        <?php
                                        if (isset($selectedLevel) && empty($selectedLevel) === false) {
                                            echo '<select name="gen_level" class="form-control">';
                                            foreach ($levels AS $level) {
                                                if ($selectedLevel == $level->level_id) {
                                                    echo '<option value="' . $level->level_id . '" selected="selected">' . $level->level_name . '</option>';
                                                } else {
                                                    echo '<option value="' . $level->level_id . '">' . $level->level_name . '</option>';
                                                }
                                            }
                                            echo '</select>';
                                        } else {
                                            ?>
                                            <select name="gen_level" class="form-control">
                                                <?php
                                                foreach ($levels AS $level) {
                                                    echo '<option value="' . $level->level_id . '">' . $level->level_name . '</option>';
                                                }
                                                ?>
                                            </select>

                                            <?php
                                        }
                                        ?>
                                    </div>
                            </li>

                            <!--<li class="list-group-item">
                                <div class="form-group">
                                    <label for="gen_randomize" class="control-label col-sm-4 col-md-4 col-lg-4">Randomize order
                                        :</label>
                                    <div class="col-sm-1 col-md-1 col-lg-1">
                                        <div class="checkbox">
                                            <?php
                                                if (isset($selectedRandomize) && $selectedRandomize == 1) {
                                                    echo '<input type="checkbox" name="gen_randomize" checked="checked" value="1">';
                                                } else {
                                                    echo '<input type="checkbox" name="gen_randomize" value="1">';
                                                }
                                            ?>
                                            
                                        </div>
                                    </div>
                                    <div class="col-sm-7 col-md-7 col-lg-7">
                                        <em class="pull-left control-label">* this only work for PDF output at the moment</em>
                                    </div>
                                </div>
                            </li>-->

                            <div class="row">
                                <div
                                    class="col-xs-4 col-sm-2 col-md-2 col-lg-2 col-xs-offset-2 col-sm-offset-4 col-md-offset-4 col-lg-offset-4">
                                    <input type="submit" class="btn btn-custom" value="Generate" id="gen_button">
                                </div>
                                <div class="col-xs-4 col-sm-2 col-md-2 col-lg-2">
                                    <input type="reset" class="btn btn-default" value="Reset" id="reset_button">
                                </div>
                            </div>
                        </ul>
                    </div>
                    <!-- <h3>Start Generating Worksheet</h3> -->
                </form>
            </div>

        </div>
    </div>
</div>

