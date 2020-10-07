<div class="row row_subject">
    <input type="hidden" name="subject_id" id="subject_id">
    <input type="hidden" name="level_id" id="level_id">
    <div class="col-lg-3 mb-6 col-md-6 mb-30">
        <div class="list border1 rounded shadow-sm overflow-hidden cursor_pointer btnSubject"
            onclick="getLevel('Maths')">
            <div class="list-item">
                <div class="list-thumb avatar avatar60 shadow-sm">
                    <img src="<?= base_url('img/icon-subject-math-2.png') ?>" alt="" class="img-fluid">
                </div>
                <div class="list-body text-right">
                    <span class="list-title fs-1x font400">Maths</span>

                </div>
            </div>
        </div>
    </div>
    <!--col-->
    <div class="col-lg-3 mb-6 col-md-6 mb-30">
        <div class="list border1 rounded shadow-sm overflow-hidden cursor_pointer btnSubject"
            onclick="getLevel('English')">
            <div class="list-item">
                <div class="list-thumb avatar avatar60 shadow-sm">
                    <img src="<?= base_url('img/icon-subject-english.png') ?>" alt="" class="img-fluid">
                </div>
                <div class="list-body text-right">
                    <span class="list-title fs-1x font400">English</span>
                </div>
            </div>
        </div>
    </div>
    <!--col-->
    <div class="col-lg-3 mb-6 col-md-6 mb-30">
        <div class="list border1 rounded shadow-sm overflow-hidden cursor_pointer btnSubject"
            onclick="getLevel('Science')">
            <div class="list-item">
                <div class="list-thumb avatar avatar60 shadow-sm">
                    <img src="<?= base_url('img/icon-subject-science.png') ?>" alt="" class="img-fluid">
                </div>
                <div class="list-body text-right">
                    <span class="list-title fs-1x font400">Science</span>

                </div>
            </div>
        </div>
    </div>
    <!--col-->
</div>

<div class="row row_level b-t" style="display: none;">
    <div class="mt-10 content_level">
        <div class="col-lg-12 primary_level">
        
        </div>
        <div class="col-lg-12 secondary_level">
        
        </div>
    </div>
</div>

<div class="row row_school" style="display: none;">
    <div class="mt-20 content_school">
        <div class="col-lg-12">
            <div class="form-group">
                <label for="school_id" class="col-sm-4 col-md-4 col-lg-2 control-label font300">School</label>
                <div class="col-sm-7 col-md-7 col-lg-7">
                    <select name="school_id" id="school_id">
                        <option value="0">Not Applicable</option>
                        <?php
                            if (isset($selected_school) && empty($selected_school) === false) {
                                foreach ($schools as $school) {
                                    if ($selected_school == $school->school_id) {
                                        echo '<option value="'.$school->school_id.'" selected="selected">'.$school->school_name.'</option>';
                                    } else {
                                        echo '<option value="'.$school->school_id.'">'.$school->school_name.'</option>';
                                    }
                                }
                            } else {
                                foreach ($schools as $school) {
                                    echo '<option value="'.$school->school_id.'">'.$school->school_name.'</option>';
                                }
                            }
                        ?>
                    </select>
                </div>
            </div>
        </div>

        <div class="col-lg-12">
            <div class="form-group">
                <label for="year" class="col-sm-4 col-md-4 col-lg-2 control-label font300">Year</label>
                <div class="col-sm-6 col-md-6 col-lg-7">
                    <input style="width: 40%;" name="year" id="year" type="number" class="form-control"
                        value="<?=date('Y')?>">
                </div>
            </div>
        </div>

        <div class="col-lg-12 pt-10">
                <div class="form-group">
                    <label for="difficulty1" class="col-sm-4 col-md-4 col-lg-2 control-label font300">Difficulty Level</label>
                    <div class="col-sm-6 col-md-6 col-lg-7">
                        <div class="customUi-checkbox checkboxUi-success mr-5">
                            <input id="difficulty0_1" type="radio" name="difficulty_level" value="1" checked>
                            <label for="difficulty0_1">
                            <span class="label-checkbox"></span>
                            <span class="label-helper fs14 font300">Easy</span>
                            </label>
                        </div>
                        
                        <div class="customUi-checkbox checkboxUi-danger mr-5">
                            <input id="difficulty0_2" type="radio" name="difficulty_level" value="2">
                            <label for="difficulty0_2">
                                <span class="label-checkbox"></span>
                                <span class="label-helper fs14 font300">Normal</span>
                            </label>
                        </div>                    

                        <div class="customUi-checkbox checkboxUi-teal">
                            <input id="difficulty0_3" type="radio" name="difficulty_level" value="3">
                            <label for="difficulty0_3">
                                <span class="label-checkbox"></span>
                                <span class="label-helper fs14 font300">Hard</span>
                            </label>
                        </div>

                    </div>
                </div>
                        
        </div>

        <div class="col-lg-12 pt-20">
            <div class="form-group">
                <div class="customUi-checkbox checkboxUi-dark">
                    <input id="add_difficulty_level2nd" type="checkbox" name="add_difficulty_level2nd" value="1">
                    <label for="add_difficulty_level2nd">
                        <span class="label-checkbox"></span>
                        <span class="label-helper fs14 font300">Add Difficulty & Level (2nd)</span>
                    </label>
                </div>                        
            </div>
        </div>

        <div class="panel_difficulty_level2nd" style="display:none;">
            <div class="col-lg-12">
                <div class="form-group">
                    <label for="difficulty" class="col-sm-4 col-md-4 col-lg-2 control-label font300">Difficulty</label>
                    <div class="col-sm-6 col-md-6 col-lg-7">
                        <div class="customUi-checkbox checkboxUi-success mr-5">
                            <input id="difficulty1" type="radio" name="difficulty_level2" value="1">
                            <label for="difficulty1">
                            <span class="label-checkbox"></span>
                            <span class="label-helper fs14 font300">Easy</span>
                            </label>
                        </div>
                        
                        <div class="customUi-checkbox checkboxUi-danger mr-5">
                            <input id="difficulty2" type="radio" name="difficulty_level2" value="2">
                            <label for="difficulty2">
                                <span class="label-checkbox"></span>
                                <span class="label-helper fs14 font300">Normal</span>
                            </label>
                        </div>                    

                        <div class="customUi-checkbox checkboxUi-teal">
                            <input id="difficulty3" type="radio" name="difficulty_level2" value="3">
                            <label for="difficulty3">
                                <span class="label-checkbox"></span>
                                <span class="label-helper fs14 font300">Hard</span>
                            </label>
                        </div>

                    </div>
                </div>
                        
            </div>

            <div class="col-lg-12 pt-10">
                <div class="form-group">
                    <label for="level_id_2nd" class="col-sm-4 col-md-4 col-lg-2 control-label font300">Level</label>
                    <div class="col-sm-6 col-md-6 col-lg-7">
                        <select name="level_id2" id="level_id2" style="width: 270px;"></select>
                    </div>
                </div>
                        
            </div>
        </div>

        <div class="col-lg-12 pt-20">
            <div class="form-group">
                <div class="customUi-checkbox checkboxUi-dark">
                    <input id="add_difficulty_level3rd" type="checkbox" name="add_difficulty_level3rd" value="1">
                    <label for="add_difficulty_level3rd">
                        <span class="label-checkbox"></span>
                        <span class="label-helper fs14 font300">Add Difficulty & Level (3rd)</span>
                    </label>
                </div>                        
            </div>
        </div>

        <div class="panel_difficulty_level3rd" style="display:none;">
            <div class="col-lg-12">
                <div class="form-group">
                    <label for="difficulty" class="col-sm-4 col-md-4 col-lg-2 control-label font300">Difficulty</label>
                    <div class="col-sm-6 col-md-6 col-lg-7">
                        <div class="customUi-checkbox checkboxUi-success mr-5">
                            <input id="difficulty1_level3" type="radio" name="difficulty_level3" value="1">
                            <label for="difficulty1_level3">
                            <span class="label-checkbox"></span>
                            <span class="label-helper fs14 font300">Easy</span>
                            </label>
                        </div>
                        
                        <div class="customUi-checkbox checkboxUi-danger mr-5">
                            <input id="difficulty2_level3" type="radio" name="difficulty_level3" value="2">
                            <label for="difficulty2_level3">
                                <span class="label-checkbox"></span>
                                <span class="label-helper fs14 font300">Normal</span>
                            </label>
                        </div>                    

                        <div class="customUi-checkbox checkboxUi-teal">
                            <input id="difficulty3_level3" type="radio" name="difficulty_level3" value="3">
                            <label for="difficulty3_level3">
                                <span class="label-checkbox"></span>
                                <span class="label-helper fs14 font300">Hard</span>
                            </label>
                        </div>

                    </div>
                </div>
                        
            </div>

            <div class="col-lg-12 pt-10">
                <div class="form-group">
                    <label for="level_id3" class="col-sm-4 col-md-4 col-lg-2 control-label font300">Level</label>
                    <div class="col-sm-6 col-md-6 col-lg-7">
                        <select name="level_id3" id="level_id3" style="width: 270px;"></select>
                    </div>
                </div>
                        
            </div>
        </div>

    </div>
</div>