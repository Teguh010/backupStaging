<div class="section">
    <div class="container">
        <div class="row">
            <div class="col-xs-12 col-sm-5 col-md-5 col-lg-5">
                <img src="<?php echo base_url(); ?>img/img2.png" class="center-block img-responsive margin-top-custom">
            </div>
            <div class="col-xs-12 col-sm-7 col-md-7 col-lg-7">
                <h1>SmartGen</h1>
                <p>With the latest technology of SmartGen now, you can 'smartly generate' practice questions online
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
                    action="<?php echo base_url(); ?>smartgen/sc_generateWorksheet/<?php echo (isset($worksheetId) && empty($worksheetId) === false) ? $worksheetId : ''; ?>"
                    method="post" accept-charset="utf-8" class="form-horizontal worksheet_form">
                    <div class="panel panel-success panel-success-custom-dark">
                        <div class="panel-heading">Start Generating Worksheet</div>
                        <ul class="list-group">
                            <li class="list-group-item">
                                <div class="form-group <?= form_error('gen_num_of_question') ? 'has-error' : '' ?>">
                                    <label for="gen_num_of_question" class="control-label col-sm-4 col-md-4 col-lg-4">Number
                                        of question:</label>
                                    <div class="col-sm-7 col-md-7 col-lg-7">
                                        <select name="gen_num_of_question" id="gen_num_of_question"
                                                class="form-control">
                                            <?php
                                            if($is_logged_in == '1'){
	                                            if (isset($selectedNumOfQuestion)) {
	                                                $optionArray = array('1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12', '13', '14', '15', '16', '17', '18', '19', '20', '21', '22', '23', '24', '25', '26', '27', '28', '29', '30', '31', '32', '33', '34', '35', '36', '37', '38', '39', '40', '41', '42', '43', '44', '45', '46', '47', '48', '49', '50');
	                                                foreach ($optionArray AS $option) {
	                                                    if ($selectedNumOfQuestion == intval($option)) {
	                                                        echo '<option value="' . $option . '" selected="selected">' . $option . '</option>';
	                                                    } else {
	                                                        echo '<option value="' . $option . '">' . $option . '</option>';
	                                                    }
	                                                }
	                                            } else {
                                                ?>
                                                <option value="1">1</option>
                                                <option value="2">2</option>
                                                <option value="3">3</option>
                                                <option value="4">4</option>
                                                <option value="5">5</option>
                                                <option value="6">6</option>
                                                <option value="7">7</option>
                                                <option value="8">8</option>
                                                <option value="9">9</option>
                                                <option value="10" selected="selected">10</option>
                                                <option value="11">11</option>
                                                <option value="12">12</option>
                                                <option value="13">13</option>
                                                <option value="14">14</option>
                                                <option value="15">15</option>
                                                <option value="16">16</option>
                                                <option value="17">17</option>
                                                <option value="18">18</option>
                                                <option value="19">19</option>
                                                <option value="20">20</option>
                                                <option value="21">21</option>
                                                <option value="22">22</option>
                                                <option value="23">23</option>
                                                <option value="24">24</option>
                                                <option value="25">25</option>
                                                <option value="26">26</option>
                                                <option value="27">27</option>
                                                <option value="28">28</option>
                                                <option value="29">29</option>
                                                <option value="30">30</option>
                                                <option value="31">31</option>
                                                <option value="32">32</option>
                                                <option value="33">33</option>
                                                <option value="34">34</option>
                                                <option value="35">35</option>
                                                <option value="36">36</option>
                                                <option value="37">37</option>
                                                <option value="38">38</option>
                                                <option value="39">39</option>
                                                <option value="40">40</option>
                                                <option value="41">41</option>
                                                <option value="42">42</option>
                                                <option value="43">43</option>
                                                <option value="44">44</option>
                                                <option value="45">45</option>
                                                <option value="46">46</option>
                                                <option value="47">47</option>
                                                <option value="48">48</option>
                                                <option value="49">49</option>
                                                <option value="50">50</option>
                                                <?php
                                            	}
                                        	} else{
                                            ?>
												<option value="1">1</option>
												<option value="2">2</option>
												<option value="3">3</option>
												<option value="4">4</option>
												<option value="5" selected="selected">5</option>
												<option value="6" disabled>6</option>
												<option value="7" disabled>7</option>
												<option value="8" disabled>8</option>
												<option value="9" disabled>9</option>
												<option value="10" disabled>10</option>
												<option value="11" disabled>11</option>
												<option value="12" disabled>12</option>
												<option value="13" disabled>13</option>
												<option value="14" disabled>14</option>
												<option value="15" disabled>15</option>
												<option value="16" disabled>16</option>
												<option value="17" disabled>17</option>
												<option value="18" disabled>18</option>
												<option value="19" disabled>19</option>
												<option value="20" disabled>20</option>
												<option value="21" disabled>21</option>
												<option value="22" disabled>22</option>
												<option value="23" disabled>23</option>
												<option value="24" disabled>24</option>
												<option value="25" disabled>25</option>
												<option value="26" disabled>26</option>
												<option value="27" disabled>27</option>
												<option value="28" disabled>28</option>
												<option value="29" disabled>29</option>
												<option value="30" disabled>30</option>
												<option value="31" disabled>31</option>
												<option value="32" disabled>32</option>
												<option value="33" disabled>33</option>
												<option value="34" disabled>34</option>
												<option value="35" disabled>35</option>
												<option value="36" disabled>36</option>
												<option value="37" disabled>37</option>
												<option value="38" disabled>38</option>
												<option value="39" disabled>39</option>
												<option value="40" disabled>40</option>
												<option value="41" disabled>41</option>
												<option value="42" disabled>42</option>
												<option value="43" disabled>43</option>
												<option value="44" disabled>44</option>
												<option value="45" disabled>45</option>
												<option value="46" disabled>46</option>
												<option value="47" disabled>47</option>
												<option value="48" disabled>48</option>
												<option value="49" disabled>49</option>
												<option value="50" disabled>50</option>
											<?php
											}
											?>
                                        </select>
                                        <!-- set_value('num_of_question') -->
                                    </div>
                                </div>
                            </li>
                            <li class="list-group-item">
                                <div class="form-group">
                                    <label for="" class="control-label col-xs-12 col-sm-4 col-md-4 col-lg-4">Level
                                        :</label>
                                    <div class="col-sm-7 col-md-7 col-lg-7">
                                        <?php
                                        if (isset($selectedLevel) && empty($selectedLevel) === false) {
                                            // TODO : check the session to set the relevant levels
                                            echo '<select name="gen_level" class="form-control" id="gen_level">';
                                            foreach ($levels AS $level) {
                                                if ($selectedLevel == $level->level_id) {
                                                    echo '<option value="3" selected="selected">' . $level->level_name . '</option>';
                                                } else {
                                                    echo '<option value="3">' . $level->level_name . '</option>';
                                                }
                                            }
                                            echo '</select>';
                                        } else {
                                            ?>
                                            <select name="gen_level" class="form-control" id="gen_level">
                                                <?php
                                                foreach ($levels AS $level) {
                                                    echo '<option value="3">' . $level->level_name . '</option>';
                                                }
                                                ?>
                                            </select>
                                            <?php
                                        }
                                        ?>
                                    </div>
                            </li>
                            <li class="list-group-item">
                                <div class="form-group topic-group">
                                    <label for="" class="control-label col-xs-12 col-sm-4 col-md-4 col-lg-4">Strands -
                                        Topic :</label>
                                    <?php
                                    // TODO : modify to selected substrands and topics
                                    if (isset($selectedSubstrand) && isset($selectedTopic)) {
                                        for ($i = 0, $count = count($selectedSubstrand); $i < $count; $i++) {
                                            if ($i != 0) {
                                                echo '<div class="form-group">';
                                                echo '<div class="col-sm-offset-4 col-md-offset-4 col-lg-offset-4 col-xs-6 col-sm-3 col-md-3 col-lg-3">';
                                            } else {
                                                echo '<div class="col-xs-6 col-sm-3 col-md-3 col-lg-3">';
                                            }
                                            echo '<select name="gen_substrand[]" class="form-control substrand_selects">';
                                            // echo '<option value="all">All substrand</option>';
                                            foreach ($substrands AS $substrand) {
                                                if ($selectedSubstrand[$i] == $substrand->id) {
                                                    echo '<option value="' . $substrand->id . '" selected="selected">' . $substrand->name . '</option>';
                                                } else {
                                                    echo '<option value="' . $substrand->id . '">' . $substrand->name . '</option>';
                                                }
                                            }
                                            echo '</select>';
                                            echo '</div>';
                                            echo '<div class="col-xs-6 col-sm-3 col-md-3 col-lg-3">';
                                            echo '<select name="gen_topic[]" class="form-control topic_select">';
                                            echo '<option value="all" selected="selected">Any Topic</option>';
                                            foreach ($topics[$i] AS $topic) {
                                                if ($selectedTopic[$i] == $topic->id) {
                                                    echo '<option value="68" selected="selected">' . $topic->name . '</option>';
                                                } else {
                                                    echo '<option value="68">' . $topic->name . '</option>';
                                                }
                                            }
                                            echo '</select>';
                                            echo '</div>';
                                            if ($i == 0) {
                                                echo '<div class="col-xs-12 col-sm-1 col-md-1 col-lg-1 add_minus_btn_group">';
                                                if (($i + 1) == $count) {
                                                    echo '<a href="#" class="btn btn-custom btn-no-margin add_sc_level_topic"> + </a>';
                                                }
                                                echo '</div></div>';
                                            } else if ($i == 1) {
                                                echo '<div class="col-xs-12 col-sm-2 col-md-2 col-lg-2 add_minus_btn_group">';
                                                if (($i + 1) == $count) {
                                                    echo '  <a href="#" class="btn btn-custom btn-no-margin add_sc_level_topic"> + </a>
																	<a href="#" class="btn btn-danger btn-no-margin-top remove_sc_level_topic"> - </a>';
                                                }
                                                echo '</div></div>';
                                            } else if ($i == 2) {
                                                echo '<div class="col-xs-12 col-sm-1 col-md-1 col-lg-1 add_minus_btn_group">
																<a href="#" class="btn btn-danger btn-no-margin-top remove_sc_level_topic"> - </a>
															</div>
															</div>';
                                            }
                                        }
                                    } else {
                                        ?>
                                        <div class="col-xs-6 col-sm-3 col-md-3 col-lg-3">
                                            <select name="gen_substrand[]" class="form-control substrand_selects">
                                                <!-- <option value="all">Any substrand</option> -->
                                                <?php
                                                foreach ($substrands AS $substrand) {
                                                    echo '<option value="' . $substrand->id . '">' . $substrand->name . '</option>';
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        <div class="col-xs-6 col-sm-3 col-md-3 col-lg-3">
                                            <select name="gen_topic[]" class="form-control topic_select">
                                                <option value="all" selected="selected">Any Topic</option>
                                                <?php
                                                foreach ($topics AS $topic) {
                                                    echo '<option value="68">' . $topic->name . '</option>';
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        <div class="col-xs-12 col-sm-1 col-md-1 col-lg-1 add_minus_btn_group">
                                            <a href="#" class="btn btn-custom btn-no-margin add_sc_level_topic"> + </a>
                                        </div>
                                        <?php
                                    }
                                    ?>
                            </li>
                            <li class="list-group-item">
                                <div class="form-group">
                                    <label for="" class="control-label col-xs-12 col-sm-4 col-md-4 col-lg-4">Question Type :</label>
                                    <div class="col-sm-7 col-md-7 col-lg-7">
                                    	<div class="col-sm-5 col-md-5 col-lg-5">
                                    		<label class="radio-inline"><input type="radio" name="gen_que_type" class="mcq_type" checked="" value="1">MCQ Question</label>
                                    	</div>
                                    	<div class="col-sm-7 col-md-7 col-lg-7">
                                    		<label class="radio-inline" style="color:#B0C4DE;"><input type="radio" name="gen_que_type" class="non_mcq_type" value="2" disabled>Non-MCQ Question</label>
                                    	</div>
                                    </div>
                            </li>
                            <li class="list-group-item">
                                <div class="form-group <?= form_error('gen_difficulties') ? 'has-error' : '' ?>">
                                    <label for="gen_difficulties" class="control-label col-sm-4 col-md-4 col-lg-4">Difficulties
                                        %:</label>
                                    <div class="col-sm-7 col-md-7 col-lg-7">
                                        <output for="gen_difficulties"
                                                id="gen_difficulties_output"><?php echo (isset($selectedDifficultyOutput)) ? $selectedDifficultyOutput : "Normal"; ?></output>
                                        <input type="range" min="0" max="100" step="1"
                                               value="<?php echo (isset($selectedDifficulty)) ? intval($selectedDifficulty) : 50; ?>"
                                               name="gen_difficulties" id="gen_difficulties">
                                    </div>
                                </div>
                            </li>
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