<input type="hidden" name="table_report_show" id="table_report_show" value="report_student_ws" title="table_report_show" />
<input type="hidden" name="input_filter_ws" id="input_filter_ws" value="<?php echo $origin_ws?>" title="input_filter_ws" />
<input type="hidden" name="ws_name" id="ws_name" value="" />
<input type="hidden" name="ws_attempt" id="ws_attempt" value="" />
<input type="hidden" name="input_filter_question" id="input_filter_question" value="" title="input_filter_question" />
<input type="hidden" name="input_filter_subject" id="input_filter_subject" value="junior" title="input_filter_subject" />
<input type="hidden" name="input_filter_student" id="input_filter_student" value="all" title="input_filter_student" />
<input type="hidden" name="input_filter_worksheet" id="input_filter_worksheet" value="all" title="input_filter_worksheet" />
<input type="hidden" name="input_filter_date_start" id="input_filter_date_start" value="all" />
<input type="hidden" name="input_filter_date_end" id="input_filter_date_end" value="all" />
<!-- Duplicate -->
<input type="hidden" name="table_report_show2" id="table_report_show2" value="report_student_ws" />
<input type="hidden" name="input_filter_ws2" id="input_filter_ws2" value="" />
<input type="hidden" name="ws_name2" id="ws_name2" value="" />
<input type="hidden" name="ws_attempt2" id="ws_attempt2" value="" />
<input type="hidden" name="input_filter_question2" id="input_filter_question2" value="" />
<input type="hidden" name="input_filter_subject2" id="input_filter_subject2" value="junior" />
<input type="hidden" name="input_filter_student2" id="input_filter_student2" value="all" />
<input type="hidden" name="input_filter_worksheet2" id="input_filter_worksheet2" value="all" />
<input type="hidden" name="input_filter_date_start2" id="input_filter_date_start2" value="all" />
<input type="hidden" name="input_filter_date_end2" id="input_filter_date_end2" value="all" />
<input type="hidden" name="origin_ws" id="origin_ws" value="<?php echo $origin_ws?>" title="origin_ws" />
<input type="hidden" name="origin_ws_name" id="origin_ws_name" value="<?php echo $origin_ws_name?>" />

<input type="hidden" name="requestRunning" id="requestRunning" value="false" />

<link rel="stylesheet" href="<?php echo base_url()?>css/mathquill.min.css" />
<script src="<?php echo base_url()?>js/mathquill.js"></script>
<!--************New Added Script Start-->
<link rel="stylesheet" href="<?php echo base_url()?>/node_modules/myscript/dist/myscript.min.css"/>
<link rel="stylesheet" href="<?php echo base_url()?>/css/examples.css">
<script type="text/javascript" src="<?php echo base_url()?>node_modules/myscript/dist/myscript.min.js"></script>
<script src="<?php echo base_url()?>js/imgFunctions.js"></script>
<script src="<?php echo base_url()?>js/ColorPick.js"></script>
<style type="text/css"> 
    #filter-report{
        margin-bottom: 50px;
        margin-top: 5px;
    }
    .div-filter {
        padding-right: 25px;
        float: left;
    }
    .span-filter {
        color: #2abb9b;
        cursor: pointer;
    }
    .span-filter .fa {
        margin-top: 3px;
        position: absolute;
        margin-left: 5px;
    }
    .dropdown {
      position: relative;
      display: inline-block;
    }
    .dropdown-content {
      display: none;
      position: absolute;
      background-color: #fff;
      width: auto;
      box-shadow: 0px 8px 8px 0px rgba(0,0,0,0.5);
      z-index: 2;
      padding: 5px;
      right: -30px;
      border-radius: 5px;
      background-color: #fff;
      border: 1px solid #f3f3f3;
    }
    .dropdown-content a {
      color: black;
      padding: 5px;
      text-decoration: none;
      display: block;
    }
    .dropdown-content a i {
        font-size: 15px;
    }
    /*.dropdown-content a:hover {background-color: #f1f1f1}*/
    .dropdown:hover .dropdown-content {
      display: block;
    }
    .width14 { width: 14px; }
    .input_cb {
        right: 10px;
        position: absolute;
    }
    .main-button {
        margin-bottom: 10px;
    }
    .big_button {
        padding:5px 10px;
        border:2px solid #2abb9b;
        border-radius: 5px;
        width: 200px;
        float: left;
        margin: 0 10px 10px 0;
        cursor: pointer;
    }
    .big_button:hover, .big_button_active {
        background-color: #dff0d8;
    }
    .big_button img {
        float: left;
        width: 45px;
        margin-right: 10px;
    }
    .big_button span {
    }
    #loading {
        text-align: center;
    }
    .blank-dropdown {
        top: 58px !important;
        left: -173px !important;
    }
    .tr_group, .tr_class {
        border-bottom: 1px solid #ccc;
        cursor: pointer;
    }
    .tr_group:last-child, .tr_class:last-child {
        border-bottom: 0;
    }
    .tr_group td, .tr_class td  {
        font-weight: bold;
    }
    .tr_item {
        border: 0; 
        background-color: #F9F9F9;
        display: none;
        cursor: pointer;
    }
    .checkbox_filter {
        margin-left: 10px !important;
        cursor: pointer;
    }
    .checkbox_filter_ws {
        margin-left: 10px !important;
        cursor: pointer;
    }
    .dropdown-student {
        padding: 0 !important;
        border-radius: 10px;
        background-color: #fff;
        border: 1px solid #f3f3f3;
    }
</style>
<style>
/* The container */
.container-filter {
  display: block;
  position: relative;
  cursor: pointer;
  font-size: 12px;
  -webkit-user-select: none;
  -moz-user-select: none;
  -ms-user-select: none;
  user-select: none;
}

/* Hide the browser's default checkbox */
.container-filter input {
  position: absolute;
  opacity: 0;
  cursor: pointer;
  height: 0;
  width: 0;
}

/* Create a custom checkbox */
.checkmark {
    position: absolute;
    top: 0;left: 10px;
    height: 19px;
    width: 19px;
    background-color: #fff;
    border-radius: 3px;
    border: 1px solid #ccc;
}

/* On mouse-over, add a grey background color */
.container-filter:hover input ~ .checkmark {
  background-color: #fff;
}

/* When the checkbox is checked, add a blue background */
.container-filter input:checked ~ .checkmark {
  background-color: #2ABB9B;
  border: 0;
}

/* Create the checkmark/indicator (hidden when not checked) */
.checkmark:after {
  content: "";
  position: absolute;
  display: none;
}

/* Show the checkmark when checked */
.container-filter input:checked ~ .checkmark:after {
  display: block;
}

/* Style the checkmark/indicator */
.container-filter .checkmark:after {
    left: 6px;
    top: 1px;
    width: 7px;
    height: 14px;
  border: solid white;
  border-width: 0 3px 3px 0;
  -webkit-transform: rotate(45deg);
  -ms-transform: rotate(45deg);
  transform: rotate(45deg);
}
</style>
<link href="<?php echo base_url()?>css/circle.css" rel="stylesheet">
<?php

if (isset($report_error) && $report_error) {
        echo '<div class="section">';
        echo '<div class="container">';
        echo '<div class="alert alert-danger">';
        echo $error_message;
        echo '</div>';
        echo '</div>';
        echo '</div>';

    } else {
        $userRole = $this->session->userdata('user_role');
        $userId = $this->session->userdata('user_id');
        $worksheets = $this->model_worksheet->get_worksheets($userId, NULL, NULL, $userRole);
        $students = $this->model_users->get_student_list($userId);
?>
<script type="text/javascript">
$(document).ready(function(){
    MathJax.Hub.Typeset();
    var wsOrigin = $('#origin_ws');
    var wsOriginName = $('#origin_ws_name');
    if(wsOrigin.val()!='') {
        // alert(wsOriginName.val());
        $('#filter-form').show();
        $('#filter-report').show();
        $('.big_button').removeClass('big_button_active');
        $('.p_by_ws').addClass('big_button_active');
        var wsDetail = $('#report_student_ws_detail');
        var wsReport = $('#report_student_ws');
        wsReport.hide();
        wsDetail.show();
        $('#label_filter_worksheet').hide();
        $('#table_report_show').val('report_student_ws_detail');
        var ws_id = wsOrigin.val();
        var ws_name = wsOriginName.val();
        $('#input_filter_ws').val(ws_id);
        $('#ws_name').val(ws_name);
        $('.title_ws').empty();
        $('.title_ws').html('<b>'+ws_name+'</b>');
        $('.title_ws_qs').empty();
        $('.title_ws_qs').html('<i class="fa fa-newspaper-o"></i> &nbsp;'+ws_name+'');
        document.getElementById('report_student_ws_detail').innerHTML = "";
        $('#loading').show();
        var worksheet_id = ws_id;
        if ( $('#requestRunning').val()=='true') {
            return;
        }
        $('#requestRunning').val('true');
        $.ajax({
            url:'<?php echo base_url()?>profile/analytics_by_worksheet',
            method: 'post',
            data: {
                filterWsId: worksheet_id,
                filterWsName: ws_name,
                filterStudent: "all"
            },
            dataType: 'json',
            success: function(response){
                var res = JSON.stringify(response);
                var rs = JSON.parse(res);
                if(rs.success==true) {
                    var html = rs.html;
                    $('#report_student_ws_detail').append(html);
                }
                $('#requestRunning').val('false');
                $('#loading').hide();
            }
        });
    }
});</script>
<div class="section" style="padding-top:0 !important;margin-top:0 !important;min-height: 300px;">
    <div class="container">
        <h1>Performance Report</h1>
        <div class="row">
            <div class="col-lg-12 tab-content">
                <div id="main-button">
                    <div class="big_button p_by_ws" onclick="showFilter('p_by_ws')">
                        <img src="<?php echo base_url() ?>img/performance.png" /> <span>Performance By Worksheet</span>
                    </div>
                    <?php if(BRANCH_TID==1) { 
                            $topics_tid = $this->model_question->get_topiclevel_list('junior');
                            $ability = $this->model_general->getAllData('sj_ability_tid','ability_id','asc'," where branch_id='".BRANCH_ID."' ");?>
                        <div class="big_button p_by_tp" onclick="showFilter('p_by_tp')">
                            <img src="<?php echo base_url() ?>img/progress.png" /> <span>Performance By Topic</span>
                        </div>
                        <div class="big_button p_by_ab" onclick="showFilter('p_by_ab')">
                            <img src="<?php echo base_url() ?>img/growth.png" /> <span>Performance By Ability</span>
                        </div>
                <?php } /* else { 
                    $heuristics = $this->model_general->getAllData('sj_heuristics','heuristic_id','asc'," where 1 "); ?>
                        <div class="big_button p_by_tp2" onclick="swal('Information','Coming soon','warning')">
                            <img src="<?php echo base_url() ?>img/progress.png" /> <span>Performance By Topic</span>
                        </div>
                        <div class="big_button p_by_st" onclick="showFilter('p_by_st')">
                            <img src="<?php echo base_url() ?>img/growth.png" /> <span>Performance By Heuristics</span>
                        </div>

                <?php } */ ?>
                </div>
            </div>
        </div>
        <script type="text/javascript">
            function showFilter(bigButton){
                $('.big_button').removeClass('big_button_active');
                $('.'+bigButton).addClass('big_button_active');
                $('#filter-form').show();
                $('#filter-report').show();
                $('#report_student_question').hide();
                if(bigButton=='p_by_ws'){
                    $('#label_filter_worksheet').show();
                    $('.table-report').hide();
                    $('#report_student_ws').show();
                    $('#table_report_show').val('report_student_ws');
                } else if(bigButton=='p_by_tp') {
                    $('#label_filter_worksheet').hide();
                    $('.table-report').hide();
                    $('#report_student_topic').show();
                    $('#table_report_show').val('report_student_topic');
                } else if(bigButton=='p_by_ab') {
                    $('#label_filter_worksheet').hide();
                    $('.table-report').hide();
                    $('#report_student_ability').show();
                    $('#table_report_show').val('report_student_ability');
                } else if(bigButton=='p_by_st') {
                    $('#label_filter_worksheet').hide();
                    $('.table-report').hide();
                    $('#report_student_heuristics').show();
                    $('#table_report_show').val('report_student_heuristics');
                }
            }
            function formatDate(date){
                var dd = date.getDate();
                var mm = date.getMonth()+1;
                var yyyy = date.getFullYear();
                if(dd<10) {dd='0'+dd}
                if(mm<10) {mm='0'+mm}
                date = yyyy+'-'+mm+'-'+dd;
                return date
            }
            function chooseFilter(val='',type='subject') {
                if(type=="subject") {

                } else if(type=="quest_number") { 
                    var filter_question = $('#filter_question');
                    filter_question.empty();
                    filter_question.html(' Q'+val+' <i class="fa fa-sort-down"></i>');
                    var input_filter_q = $('#input_filter_question');
                    input_filter_q.val(val);
                    $('.q-filter').removeClass('fa-check');
                    $('.q-filter').removeClass('width14');
                    $('.q'+val).addClass('fa-check');
                    $('.q-filter').addClass('width14');
                    $('.q'+val).removeClass('width14');
                } else if(type=="date") {
                    var filterDate = $('#filter_date');
                    var input_filter_date_start = $('#input_filter_date_start');
                    var input_filter_date_end = $('#input_filter_date_end');
                    var today = new Date();
                    filterDate.empty();
                    $('.time-filter').removeClass('fa-check');
                    $('.time-filter').removeClass('width14');
                    $('.'+val).addClass('fa-check');
                    $('.time-filter').addClass('width14');
                    $('.'+val).removeClass('width14');
                    if(val=='all_time') {
                        filterDate.html(' All time <i class="fa fa-sort-down"></i>');
                        input_filter_date_start.val('all');
                        input_filter_date_end.val('all');
                    } else if(val=='last7days') {
                        filterDate.html(' Last 7 days <i class="fa fa-sort-down"></i>');
                        var lastDate = new Date(today);
                        lastDate.setDate(today.getDate() - 7);
                        input_filter_date_start.val(formatDate(today));
                        input_filter_date_end.val(formatDate(lastDate));
                    } else if(val=='last14days') {
                        filterDate.html(' Last 14 days <i class="fa fa-sort-down"></i>');
                        var lastDate = new Date(today);
                        lastDate.setDate(today.getDate() - 14);
                        input_filter_date_start.val(formatDate(today));
                        input_filter_date_end.val(formatDate(lastDate));
                    } else if(val=='last30days') {
                        filterDate.html(' Last 30 days <i class="fa fa-sort-down"></i>');
                        var lastDate = new Date(today);
                        lastDate.setDate(today.getDate() - 30);
                        input_filter_date_start.val(formatDate(today));
                        input_filter_date_end.val(formatDate(lastDate));
                    } else if(val=='last60days') {
                        filterDate.html(' Last 60 days <i class="fa fa-sort-down"></i>');
                        var lastDate = new Date(today);
                        lastDate.setDate(today.getDate() - 60);
                        input_filter_date_start.val(formatDate(today));
                        input_filter_date_end.val(formatDate(lastDate));
                    } else if(val=='last90days') {
                        filterDate.html(' Last 90 days <i class="fa fa-sort-down"></i>');
                        var lastDate = new Date(today);
                        lastDate.setDate(today.getDate() - 90);
                        input_filter_date_start.val(formatDate(today));
                        input_filter_date_end.val(formatDate(lastDate));
                    } else if(val=='lasthalfyear') {
                        filterDate.html(' Last half year <i class="fa fa-sort-down"></i>');
                        var lastDate = new Date(today);
                        lastDate.setDate(today.getDate() - 180);
                        input_filter_date_start.val(formatDate(today));
                        input_filter_date_end.val(formatDate(lastDate));
                    } else if(val=='janjul') {
                        filterDate.html(' Jan 1 - Jul 31 <i class="fa fa-sort-down"></i>');
                        var nowYear = today.getFullYear();
                        input_filter_date_start.val(nowYear+'-01-01');
                        input_filter_date_end.val(nowYear+'-07-31');
                    } else if(val=='augdec') {
                        filterDate.html(' Aug 1 - Dec 31  <i class="fa fa-sort-down"></i>');
                        var nowYear = today.getFullYear();
                        input_filter_date_start.val(nowYear+'-08-01');
                        input_filter_date_end.val(nowYear+'-12-31');
                    } else if(val=='lastyear') {
                        filterDate.html('Last year <i class="fa fa-sort-down"></i>');
                        var n = today.getFullYear();
                        var m = parseInt(n);
                        var lastYear = m - 1;
                        input_filter_date_start.val(lastYear.toString()+'-01-01');
                        input_filter_date_end.val(lastYear.toString()+'-12-31');
                    } else if(val=='last2years') {
                        filterDate.html('Last 2 years <i class="fa fa-sort-down"></i>');
                        var n = today.getFullYear();
                        var m = parseInt(n);
                        var lastYear = m - 2;
                        input_filter_date_start.val(lastYear.toString()+'-01-01');
                        input_filter_date_end.val(lastYear.toString()+'-12-31');
                    }
                }
            }
        </script>
        <style>
        .btn-sm {    
            color: #333 !important;
            font-weight: bold;
            border-radius: 10px;
            margin-top: 13px;
            box-shadow: 0px 3px 3px rgba(0, 0, 0, 0.2);
        }
        .btn-outline-secondary:hover {
            background-color: #ddd !important;
        }
        .switch {
          position: relative;
          display: inline-block;
          width: 100px;
          height: 28px;
          margin: 0.8em 0.5em 0 0.5em;
        }

        .switch input {display:none;}

        .slider {
          position: absolute;
          cursor: pointer;
          top: 0;
          left: 0;
          right: 0;
          bottom: 0;
          background-color: #2ABB9B;
          -webkit-transition: .4s;
          transition: .4s;
        }

        .slider:before {
          position: absolute;
          content: "";
          height: 25px;
          width: 25px;
          left: 2px;
          bottom: 2px;
          background-color: white;
          -webkit-transition: .4s;
          transition: .4s;
        }

        input:checked + .slider {
          background-color: #2ABB9B;
        }

        input:focus + .slider {
          box-shadow: 0 0 1px #2196F3;
        }

        input:checked + .slider:before {
          -webkit-transform: translateX(71px);
          -ms-transform: translateX(71px);
          transform: translateX(71px);
        }

        /*------ ADDED CSS ---------*/
        .on
        {
          display: none;
        }

        .on
        {
          color: white;
          position: absolute;
          transform: translate(-50%,-50%);
          top: 50%;
          left: 37%;
          font-size: 0.8em;
          font-weight: 400 !important;
        }

        .off
        {
          color: white;
          position: absolute;
          transform: translate(-50%,-50%);
          top: 50%;
          right: -10%;
          font-size: 0.8em;
          font-weight: 400 !important;
        }

        input:checked+ .slider .on
        {display: block;}

        input:checked + .slider .off
        {display: none;}

        /*--------- END --------*/

        /* Rounded sliders */
        .slider.round {
          border-radius: 34px;
        }

        .slider.round:before {
          border-radius: 50%;}
        .btn {font-family: 'eligible', 'Lato', 'sans-serif';}
        </style>
        <div class="row" id="filter-form" style="display: none;">
            <div class="col-lg-12 tab-content">
               <div id="filter-report">
                    <div id="label_filter_subject" class="div-filter dropdown">
                        Subject Level: <span id="filter_subject" class="span-filter dropbtn">Secondary Maths <i class="fa fa-sort-down"></i></span>
                        <div class="dropdown-content dropdown-subject">
                            <?php
                            $getAllSubject = $this->model_general->getAllData('sj_subject','id','asc');
                            foreach ($getAllSubject as $key => $val) {
                                if(BRANCH_ID==13) {
                                    if($val['id']==5)
                                        echo '<a href="#" onclick="chooseFilter(\''.$val['id'].'\',\'subject\')"><i class="fa fa-check"></i> '.$val['name'].'</a>';
                                    else
                                        echo '<a href="#" style="color:#ccc;"><i class="fa width14"></i> '.$val['name'].'</a>';
                                } else {
                                    echo '<a href="#" onclick="chooseFilter(\''.$val['id'].'\',\'subject\')"><i class="fa width14"></i> '.$val['name'].'</a>';
                                }
                            }
                            ?>
                        </div>
                    </div>
                    <div id="label_filter_student" class="div-filter dropdown">
                        Student: <span id="filter_student" class="span-filter dropbtn">By Group <i class="fa fa-sort-down"></i></span>
                        <div class="dropdown-content dropdown-student" style="right: -60px;">
                            <table style="min-width: 225px;">
                            <tr><td width="50%">
                                <button class="btn btn-outline-secondary btn-sm" id="check_uncheck">Uncheck All</button>
                            </td><td>
                                <label class="switch">
                                    <input type="checkbox" id="student_filter" data-id="0" data-status="1" class="setFilterStudent" id="togBtn" checked>
                                    <div class="slider round"><span class="on">By Group</span><span class="off">By Class</span></div>
                                </label>
                                <input type="hidden" name="gen_student_filter" id="gen_student_filter" value="1" />
                            </td></tr>
                            </table>
                            <table style="min-width: 225px;" id="filter_by_group">
                            <?php /*   <tr class="tr_group"><td width="35">
                                    <input type="checkbox" class="checkbox_filter" id="cb_student_all" checked value="all_students" /> </td><td>
                                <a href="#" onclick="selectFilter('all_students','student')">All Student </a>
                                </td></tr> */ ?>
                            <?php 
                            $get_tutor_group = $this->model_users->get_group($this->session->userdata('user_id'));
                            $gx = 1;
                            $grouped_student = array();
                            foreach ($get_tutor_group as $key => $value) {
                                $check = $this->model_users->get_student_assign_group($this->session->userdata('user_id'),$value['id'],"sum");
                                if($check > 0) {
                                    echo '<tr class="tr_group"><td width="35">
                                    <label class="container-filter"> &nbsp;
                                      <input checked type="checkbox" id="' . $value['id'] .'" class="checkbox_filter student_group group_id_'.$value['id'].'" value="group_'.$value['id'].'" />
                                      <span class="checkmark"></span>
                                    </label></td><td class="td_group" data-group="'.$value['id'].'"><a>' . $value['group_name'] . ' </a></td></tr>'; 
                                    // <input checked type="checkbox" id="' . $value['id'] .'" class="checkbox_filter student_group group_id_'.$value['id'].'" value="group_'.$value['id'].'" />';
                                    $check_student = $this->model_users->get_student_assign_group($this->session->userdata('user_id'),$value['id'],"record");
                                    foreach ($check_student as $v) {
                                        $studentName = $this->model_users->get_username_from_id($v['student_id']);
                                        echo '<tr class="tr_item tr_item_'.$value['id'].'"><td>
                                         <label class="container-filter"> &nbsp;
                                          <input checked type="checkbox" id="cb_student_'.$v['student_id'].'" class="checkbox_filter student_item group_item_'.$value['id'].'" value="'.$v['student_id'].'" />
                                          <span class="checkmark" style="margin-left:5px;"></span>
                                        </label></td><td><a>' . $studentName . '</a></td></tr>';
                                        $grouped_student[] =  $v['student_id'];
                                    } 
                                    $gx++;
                                } 
                            }

                            $getAllStudent = $this->model_users->get_student_list($this->session->userdata('user_id'));
                            $ungroup_count = 1;
                            foreach ($getAllStudent AS $student) {
                                if(in_array($student->id, $grouped_student)) continue;
                                if($ungroup_count==1) {
                                    echo '<tr class="tr_group"><td width="35">
                                    <label class="container-filter"> &nbsp;
                                    <input checked type="checkbox" id="0" class="checkbox_filter student_group group_id_0" value="group_0" />
                                      <span class="checkmark"></span>
                                    </label>
                                    </td><td class="td_group" data-group="0"><a>Ungrouped</a></td></tr>';
                                }
                                $studentName = $this->model_users->get_username_from_id($student->id);
                                echo '<tr class="tr_item tr_item_0"><td>
                                <label class="container-filter"> &nbsp;
                                    <input checked type="checkbox" id="cb_student_'.$student->id.'" class="checkbox_filter student_item group_item_'.$student->id.'" value="'.$student->id.'" />
                                <span class="checkmark" style="margin-left:5px;"></span>
                                </label></td><td><a>' . $studentName . '</a></td></tr>';
                                $ungroup_count++;
                            } ?>
                            </table>

                            <table style="min-width: 225px;display: none;" id="filter_by_class">
                            <?php 
                            $get_tutor_group = $this->model_users->get_class($this->session->userdata('user_id'));
                            $gx = 1;
                            $grouped_student = array();
                            foreach ($get_tutor_group as $key => $value) {
                                $check = $this->model_users->get_student_assign_class($this->session->userdata('user_id'),$value['class_id'],"sum");
                                if($check > 0) {
                                    echo '<tr class="tr_class"><td width="35">
                                    <label class="container-filter"> &nbsp;
                                      <input checked type="checkbox" id="' . $value['class_id'] .'" class="checkbox_filter student_class class_id_'.$value['class_id'].'" value="class_'.$value['class_id'].'" />
                                      <span class="checkmark"></span>
                                    </label></td><td class="td_class" data-group="'.$value['class_id'].'"><a>' . $value['class_name'] . ' </a></td></tr>';
                                    $check_student = $this->model_users->get_student_assign_class($this->session->userdata('user_id'),$value['class_id'],"record");
                                    foreach ($check_student as $v) {
                                        $studentName = $this->model_users->get_username_from_id($v['user_id']);
                                        echo '<tr class="tr_item tr_item_'.$value['class_id'].'"><td>
                                         <label class="container-filter"> &nbsp;
                                          <input checked type="checkbox" id="cb_student_'.$v['user_id'].'" class="checkbox_filter student_item class_item_'.$value['class_id'].'" value="'.$v['user_id'].'" />
                                          <span class="checkmark" style="margin-left:5px;"></span>
                                        </label></td><td><a>' . $studentName . '</a></td></tr>';
                                        $grouped_student[] =  $v['user_id'];
                                    } 
                                    $gx++;
                                } 
                            }
                            $getAllStudent = $this->model_users->get_student_list($this->session->userdata('user_id'));
                            $ungroup_count = 1;
                            foreach ($getAllStudent AS $student) {
                                if(in_array($student->id, $grouped_student)) continue;
                                if($ungroup_count==1) {
                                    echo '<tr class="tr_class"><td width="35">
                                    <label class="container-filter"> &nbsp;
                                      <input checked type="checkbox" id="0" class="checkbox_filter student_class class_id_0" value="class_0" />
                                      <span class="checkmark"></span>
                                    </label></td><td class="td_class" data-group="0"><a>Ungrouped</a></td></tr>';
                                }
                                $studentName = $this->model_users->get_username_from_id($student->id);
                                echo '<tr class="tr_item tr_item_0"><td>
                                 <label class="container-filter"> &nbsp;
                                  <input checked type="checkbox" id="cb_student_'.$student->id.'" class="checkbox_filter student_item class_item_'.$student->id.'" value="'.$student->id.'" />
                                  <span class="checkmark" style="margin-left:5px;"></span>
                                </label></td><td><a>' . $studentName . '</a></td></tr>';
                                $ungroup_count++;
                            } ?>
                            </table>
                        </div>
                    </div>
                    <script type="text/javascript">
                        $('#check_uncheck').click(function () {
                            var current_text = $(this).text();
                            var filter_by = $('#gen_student_filter').val();
                            if(current_text=="Check All"){
                                $(this).text('Uncheck All');
                                $('.checkbox_filter').prop('checked', true);
                            } else {
                                $(this).text('Check All');
                                $('.checkbox_filter').prop('checked', false);
                            }
                            var sList = "";
                            var filterWs = $('#input_filter_student');
                            $('.student_item').each(function () {
                                if(this.checked) {
                                    sList += this.value+",";
                                }
                            });
                            filterWs.val(sList);
                        });
                        $('.setFilterStudent').click(function () {
                            var id = $(this).data('id');
                            var status = $('#gen_student_filter').val();
                            if (status == '1') {
                                active = '2';
                                $('#filter_by_class').show();
                                $('.student_class').prop('checked', true);
                                $('.class_item').prop('checked', true);
                                $('#filter_by_group').hide();
                                $('.student_group').prop('checked', false);
                                $('.group_item').prop('checked', false);
                            } else {
                                active = '1';
                                $('#filter_by_class').hide();
                                $('.student_class').prop('checked', false);
                                $('.class_item').prop('checked', false);
                                $('#filter_by_group').show();
                                $('.student_group').prop('checked', true);
                                $('.group_item').prop('checked', true);
                            }
                            $('#check_uncheck').text('Uncheck All');
                            $('#gen_student_filter').data('status', active);
                            $('#gen_student_filter').val(active);
                        });
                        $(".td_group").click(function(){
                            var group_id = $(this).attr('data-group');
                            // alert(group_id);
                            var item_group = $('.tr_item_'+group_id);
                            item_group.toggle();
                        });
                        $(".td_class").click(function(){
                            var group_id = $(this).attr('data-group');
                            // alert(group_id);
                            var item_group = $('.tr_item_'+group_id);
                            item_group.toggle();
                        });
                        $("#cb_student_all").click(function(){
                            $('.student_group').not(this).prop('checked', this.checked);
                            $('.student_item').not(this).prop('checked', this.checked);
                            var filterWs = $('#input_filter_student');
                            filterWs.val('all');
                            $('#cb_student_all').prop('checked', true);
                            $('.student_group').not(this).prop('checked', this.checked);
                            $('.student_item').not(this).prop('checked', this.checked);
                        });
                        $(".student_item").click(function(){
                            var sList = "";
                            var filterWs = $('#input_filter_student');
                            $('.student_item').each(function () {
                                if(this.checked) {
                                    sList += this.value+",";
                                }
                            });
                            filterWs.val(sList);
                        });
                        $(".student_group").click(function(){
                            var group_id = $(this).attr("id");
                            if($(".group_id_"+group_id).prop('checked') == true) {
                                $('.group_item_'+group_id).prop('checked', true);
                            } else {
                                $('.group_item_'+group_id).prop('checked', false);
                            }
                            var sList = "";
                            var filterWs = $('#input_filter_student');
                            $('.student_item').each(function () {
                                if(this.checked) {
                                    sList += this.value+",";
                                }
                            });
                            filterWs.val(sList);
                        });
                        $(".student_class").click(function(){
                            var group_id = $(this).attr("id");
                            if($(".class_id_"+group_id).prop('checked') == true) {
                                $('.class_item_'+group_id).prop('checked', true);
                            } else {
                                $('.class_item_'+group_id).prop('checked', false);
                            }
                            var sList = "";
                            var filterWs = $('#input_filter_student');
                            $('.student_item').each(function () {
                                if(this.checked) {
                                    sList += this.value+",";
                                }
                            });
                            filterWs.val(sList);
                        });
                        $("#filter-report").mouseenter(function() {
                            /// $(this).show();
                        }).mouseleave(function() {
                            var filterShow = $('#table_report_show');
                            var filterWsId = $('#input_filter_ws');
                            var filterWsName = $('#ws_name');
                            var filterQuestion = $('#input_filter_question');
                            var filterSubject = $('#input_filter_subject');
                            var filterStudent = $('#input_filter_student');
                            var filterWorksheet = $('#input_filter_worksheet');
                            var filterDateStart = $('#input_filter_date_start');
                            var filterDateEnd = $('#input_filter_date_end');

                            var filterShow2 = $('#table_report_show2');
                            var filterWsId2 = $('#input_filter_ws2');
                            var filterWsName2 = $('#ws_name2');
                            var filterQuestion2 = $('#input_filter_question2');
                            var filterSubject2 = $('#input_filter_subject2');
                            var filterStudent2 = $('#input_filter_student2');
                            var filterWorksheet2 = $('#input_filter_worksheet2');
                            var filterDateStart2 = $('#input_filter_date_start2');
                            var filterDateEnd2 = $('#input_filter_date_end2');

                            if(filterShow.val()!=filterShow2.val() || filterWsId.val()!=filterWsId2.val() || filterWsName.val()!=filterWsName2.val() || filterQuestion.val()!=filterQuestion2.val() || filterSubject.val()!=filterSubject.val() || filterStudent.val()!=filterStudent2.val() || filterWorksheet.val()!=filterWorksheet2.val() || filterDateStart.val()!=filterDateStart2.val() || filterDateEnd.val()!=filterDateEnd2.val()) {
                                var showVal = filterShow.val();
                                var function_call = (showVal=="report_student_ws") ? "performance_by_worksheet" : "";
                                function_call = (showVal=="report_student_ws_detail") ? "analytics_by_worksheet" : function_call;
                                function_call = (showVal=="report_student_ability") ? "performance_by_ability" : function_call;
                                function_call = (showVal=="report_student_topic") ? "performance_by_topic_tid" : function_call;
                                // alert(showVal + " <-> " + function_call);
                                if(function_call!='') {
                                    if ( $('#requestRunning').val()=='true') {
                                        return;
                                    }
                                    $('#requestRunning').val('true');
                                    $('#loading').show();
                                    $('#'+showVal).empty();
                                    $.ajax({
                                        url:'<?php echo base_url()?>profile/'+function_call,
                                        method: 'post',
                                        data: {
                                            filterShow: filterShow.val(),
                                            filterWsId: filterWsId.val(),
                                            filterWsName: filterWsName.val(),
                                            filterQuestion: filterQuestion.val(),
                                            filterSubject: filterSubject.val(),
                                            filterStudent: filterStudent.val(),
                                            filterWorksheet: filterWorksheet.val(),
                                            filterStudent: filterStudent.val(),
                                            filterDateStart: filterDateStart.val(),
                                            filterDateEnd: filterDateEnd.val()
                                        },
                                        dataType: 'json',
                                        success: function(response){
                                            var res = JSON.stringify(response);
                                            var rs = JSON.parse(res);
                                            if(rs.success==true) {
                                                var html = rs.html;
                                                $('#'+showVal).append(html);
                                            }
                                            $('#requestRunning').val('false');
                                            $('#loading').hide();
                                        }
                                    });
                                } else {
                                    // alert(showVal);
                                    // $('#loading').toggle();
                                }
                                filterShow2.val(filterShow.val());
                                filterWsId2.val(filterWsId.val());
                                filterWsName2.val(filterWsName.val());
                                filterQuestion2.val(filterQuestion.val());
                                filterSubject2.val(filterSubject.val());
                                filterStudent2.val(filterStudent.val());
                                filterWorksheet2.val(filterWorksheet.val());
                                filterStudent2.val(filterStudent.val());
                                filterDateStart2.val(filterDateStart.val());
                                filterDateEnd2.val(filterDateEnd.val());
                            }
                        });
                    </script>
                    <div id="label_filter_worksheet" class="div-filter dropdown">
                        Worksheet: <span id="filter_worksheet" class="span-filter dropbtn">All Worksheets <i class="fa fa-sort-down"></i></span>
                        <div class="dropdown-content dropdown-worksheet" style="right: -6px;">
                            <table style="min-width: 225px;">
                                <tr class="tr_group"><td width="35">
                                    <label class="container-filter"> &nbsp;
                                      <input checked type="checkbox" id="cb_worksheet_all"  class="checkbox_filter_ws ws_all" value="all_worksheets" />
                                      <span class="checkmark"></span>
                                    </label></td><td>
                                <a href="#" onclick="selectFilter('all_worksheets','worksheet')">All Worksheets </a>
                                </td></tr>
                                <?php 
                                foreach ($worksheets AS $worksheet) {
                                    echo '<tr><td>
                                    <label class="container-filter"> &nbsp;
                                      <input checked type="checkbox" id="cb_worksheet_' . $worksheet->worksheet_id .'" class="checkbox_filter_ws ws_item" value="'.$worksheet->worksheet_id.'"  />
                                      <span class="checkmark"></span>
                                    </label></td><td><a href="#" onclick="selectFilter(\'ws_'.$worksheet->worksheet_id.'\',\'worksheet\')">' . $worksheet->worksheet_name . ' </a></td></tr>';
                                } ?>
                            </table>
                            <!-- <ul style="list-style-type: none;margin-left: -31px;">
                            <li>
                            <a href="#" onclick="selectFilter('all_worksheets','worksheet')">All Worksheets <input type="checkbox" id="cb_worksheet_all"  class="input_cb ws_all" checked value="all_worksheets" /></a></li>
                            <?php 
                            // foreach ($worksheets AS $worksheet) {
                            //     echo '<li><a href="#" onclick="selectFilter(\'ws_'.$worksheet->worksheet_id.'\',\'worksheet\')">' . $worksheet->worksheet_name . ' <input checked type="checkbox" id="cb_worksheet_' . $worksheet->worksheet_id .'" class="input_cb ws_item" value="'.$worksheet->worksheet_id.'" /></a></li>';
                            // } ?>
                            </ul> -->
                        </div>
                    </div>
                    <script type="text/javascript">
                        $("#cb_worksheet_all").click(function(){
                            $('.ws_item').not(this).prop('checked', this.checked);
                            var filterWs = $('#input_filter_worksheet');
                            filterWs.val('all');
                            $('#cb_worksheet_all').prop('checked', true);
                            $('.ws_item').not(this).prop('checked', this.checked);
                        });
                        $(".ws_item").click(function(){
                            var sList = "";
                            var filterWs = $('#input_filter_worksheet');
                            $('.ws_item').each(function () {
                                if(this.checked) {
                                    sList += this.value+",";
                                }
                            });
                            filterWs.val(sList);
                        });
                    </script>
                    <div id="label_filter_date" class="div-filter dropdown">
                        Creation Date: <span id="filter_date" class="span-filter dropbtn">All times <i class="fa fa-sort-down"></i></span>
                        <div class="dropdown-content dropdown-subject">
                            <a href="#" onclick="chooseFilter('all_time','date')"><i class="time-filter all_time fa fa-check"></i> All times </a>
                            <a href="#" onclick="chooseFilter('last7days','date')"><i class="time-filter last7days fa width14"></i> Last 7 days </a>
                            <a href="#" onclick="chooseFilter('last14days','date')"><i class="time-filter last14days fa width14"></i> Last 14 days </a>
                            <a href="#" onclick="chooseFilter('last30days','date')"><i class="time-filter last30days fa width14"></i> Last 30 days </a>
                            <a href="#" onclick="chooseFilter('last60days','date')"><i class="time-filter last60days fa width14"></i> Last 60 days </a>
                            <a href="#" onclick="chooseFilter('last90days','date')"><i class="time-filter last90days fa width14"></i> Last 90 days </a>
                            <a href="#" onclick="chooseFilter('lasthalfyear','date')"><i class="time-filter lasthalfyear fa width14"></i> Last half year </a>
                            <a href="#" onclick="chooseFilter('janjul','date')"><i class="time-filter janjul fa width14"></i> Jan 1 - Jul 31 </a>
                            <a href="#" onclick="chooseFilter('augdec','date')"><i class="time-filter augdec fa width14"></i> Aug 1 - Dec 31 </a>
                            <a href="#" onclick="chooseFilter('lastyear','date')"><i class="time-filter lastyear fa width14"></i> Last year </a>
                            <a href="#" onclick="chooseFilter('last2years','date')"><i class="time-filter last2years fa width14"></i> Last 2 years </a>
                    </div>
                    </div>
                    <div id="label_export" class="div-filter" onclick="javascript:exportFromHTML();">
                        <i class="fa fa-download"></i> <span data-id="ws_id" class="span-filter">Export</span>
                    </div> 
                </div> 
    <div id="loading" style="display: none;">
      <img src="<?php echo base_url(); ?>img/loading.gif" alt="Loading..." /><br />
      <em>Please wait...</em>
    </div>
<style type="text/css">
    .table-header-rotated {
      width: 100%;
      border-bottom: 1px solid #ccc;
      margin-top: 35px;
    }

    .table-header-rotated th.row-header{
      min-width: 205px !important;
      border-left: 1px solid #ccc;
    }

    .table-header-rotated td{
      width: 40px;
      border-top: 1px solid #dddddd;
      border-left: 1px solid #dddddd;
      border-right: 1px solid #dddddd;
      vertical-align: middle;
      text-align: center;
    }

    .table-header-rotated th.rotate-45{
      height: 120px;
      width: 40px;
      min-width: 40px;
      max-width: 40px;
      position: relative;
      vertical-align: bottom;
      padding: 0;
      font-size: 14px;
      line-height: 0.8;
      border: 1px solid #dddddd;
    }

    .table-header-rotated th.rotate-45 > div{
      position: relative;
      top: -65px;
      left: -67px;
      width: 180px;
      -ms-transform:rotate(-90deg);
      -moz-transform:rotate(-90deg);
      -webkit-transform:rotate(-90deg);
      -o-transform:rotate(-90deg);
      transform:rotate(-90deg);
      overflow: hidden;
      white-space: normal; 
      height: 48px;
      padding: 5px;
      line-height: 1.1;
      border-right: 1px solid #ddd;
    }
    .loading-image {
        position: absolute;
        top: 90px;
        left: 90px;
    }
    .tooltip {
        position: fixed !important;
        z-index:99999999;
    }
    .worksheet_report,.question_report {
        cursor: pointer;
    }
    .worksheet_report:hover, .question_report:hover {
        background-color: #dff0d8;
    }
</style>
<script type="text/javascript">
    $(document).ready(function(){
        // $('#loading').toggle();
        $("body").on( "click", ".worksheet_report", function( event ) {
        // $(".worksheet_report").click(function(){
            var wsDetail = $('#report_student_ws_detail');
            var wsReport = $('#report_student_ws');
            wsReport.hide();
            wsDetail.show();
            $('#label_filter_worksheet').hide();
            $('#table_report_show').val('report_student_ws_detail');
            $('#table_report_show2').val('report_student_ws_detail');
            var ws_id = $(this).attr('data-id');
            var ws_attempt = $(this).attr('data-attempt');
            var ws_name = $(this).attr('title');
            $('#input_filter_ws').val(ws_id);
            $('#ws_name').val(ws_name);
            $('#input_filter_ws2').val(ws_id);
            $('#ws_name2').val(ws_name);
            $('#ws_attempt').val(ws_attempt);
            $('.title_ws').empty();
            $('.title_ws').html('<b>'+ws_name+'</b>');
            $('.title_ws_qs').empty();
            $('.title_ws_qs').html('<i class="fa fa-newspaper-o"></i> &nbsp;'+ws_name+'');
            document.getElementById('report_student_ws_detail').innerHTML = "";
            $('#loading').show();
            var worksheet_id = ws_id;
            if ( $('#requestRunning').val()=='true') {
                return;
            }
            $('#requestRunning').val('true');
            $.ajax({
                url:'<?php echo base_url()?>profile/analytics_by_worksheet',
                method: 'post',
                data: {
                    filterWsId: worksheet_id,
                    filterWsName: ws_name,
                    filterWsAttempt: ws_attempt,
                    filterStudent: "all"
                },
                dataType: 'json',
                success: function(response){
                    var res = JSON.stringify(response);
                    var rs = JSON.parse(res);
                    if(rs.success==true) {
                        var html = rs.html;
                        $('#report_student_ws_detail').append(html);
                    }
                    $('#requestRunning').val('false');
                    $('#loading').hide();
                }
            });
        });
        // $(".back_to_ws").click(function(){
        // $('.back_to_ws').on('click', function() {
        $("body").on( "click", ".back_to_ws", function( event ) {
            var wsDetail = $('#report_student_ws_detail');
            var wsReport = $('#report_student_ws');
            wsDetail.hide();
            wsReport.show();
            $('#label_filter_worksheet').show();
            $('#table_report_show').val('report_student_ws');
            $('#table_report_show2').val('report_student_ws');
            $('#input_filter_ws').val('');
            $('#ws_name').val('');
            $('#input_filter_ws2').val('');
            $('#ws_name2').val('');
        });
        // $(".question_report").click(function(){
        $("body").on( "click", ".question_report", function( event ) {
            var wsDetail = $('#report_student_question');
            var wsReport = $('#report_student_ws_detail');
            wsReport.hide();
            wsDetail.show();
            $('#table_report_show').val('report_student_question');
            $('#table_report_show2').val('report_student_question');
            var qs_id = $(this).attr('id');
            $('#input_filter_question').val(qs_id);
            $('#input_filter_question2').val(qs_id);
            $('#filter-report').hide();
            document.getElementById('question-content').innerHTML = "";
            document.getElementById('choose_question').innerHTML = "";
            document.getElementById('card_question').innerHTML = "";
            document.getElementById('filter_question').innerHTML = "";
            document.getElementById('filter_question').innerHTML = 'Q'+qs_id+' <i class="fa fa-sort-down"></i>';
            var ws_id = $('#input_filter_ws').val();
            var ws_name = $('#ws_name').val();
            $('.title_ws_qs').empty();
            $('.title_ws_qs').html('<i class="fa fa-newspaper-o"></i> &nbsp;'+ws_name+'');
            $('#loading2').show();
            var worksheet_id = ws_id;
            if ( $('#requestRunning').val()=='true') {
                return;
            }
            $('#requestRunning').val('true');
            $.ajax({
                url:'<?php echo base_url()?>profile/analytics_by_question',
                method: 'post',
                data: {
                    worksheet_id: worksheet_id,
                    filterStudent: "all",
                    worksheet_title: ws_name,
                    question_id: qs_id
                },
                dataType: 'json',
                success: function(response){
                    var res = JSON.stringify(response);
                    var rs = JSON.parse(res);
                    if(rs.success==true) {
                        var html = rs.html;
                        $('#card_question').append(html);
                        var ques_options = rs.ques_options;
                        $('#choose_question').append(ques_options);
                        var ques_content = rs.ques_content;
                        $('#question-content').append(ques_content);
                    }
                    MathJax.Hub.Typeset();
                    $('#requestRunning').val('false');
                    $('#loading2').hide();
                }
            });
        });
        $(".back_to_ws_detail").click(function(){
            var wsDetail = $('#report_student_question');
            var wsReport = $('#report_student_ws_detail');
            wsDetail.hide();
            wsReport.show();
            $('#table_report_show').val('report_student_ws_detail');
            $('#input_filter_question').val('');
            $('#table_report_show2').val('report_student_ws_detail');
            $('#input_filter_question2').val('');
            $('#filter-report').show();
        });
    });
</script>
<script>
    function exportFromHTML() {

        var filterShow = $('#table_report_show').val();
        var filterWsId = $('#input_filter_ws').val();
        var filterWsName = $('#ws_name').val();
        var filterQuestion = $('#input_filter_question').val();
        var filterSubject = $('#input_filter_subject').val();
        var filterStudent = $('#input_filter_student').val();
        var filterWorksheet = $('#input_filter_worksheet').val();
        var filterDateStart = $('#input_filter_date_start').val();
        var filterDateEnd = $('#input_filter_date_end').val();
        filterStudent = filterStudent.replace(",","-");
        filterWorksheet = filterWorksheet.replace(",","-");
        var url = "<?php echo base_url()?>profile/performance_by_worksheet/pdf/"+filterStudent+"/"+filterWorksheet+"/"+filterDateStart+"/"+filterDateEnd;
        window.open(url, '_blank');
    }
</script>
    <div id="report_student_ws" class="table-report" style="display: none">            
        <div class="scrollable-table">
          <table class="table table-striped table-header-rotated">
            <thead>
              <tr>
                <!-- First column header is not rotated -->
                <?php
                    $percen = 0 ;
                    $percen = ($percen>100) ? 100 : $percen;
                    $color = ($percen<=30) ? "red" : "green";
                    $color = ($percen>30 && $percen<=70) ? "orange" : $color;
                    $percen_p = ($percen==0) ? "100" : ceil($percen);
                ?>
                <th style="border: 1px solid #ccc;min-width:210px !important;padding: 49px 0 49px 63px;" id="score_all_worksheet">
                    <div class="c100 p<?php echo $percen_p?> big <?php echo $color;?>">
                        <span style="font-size: 30px;margin-top: 10px;"><?php echo ceil($percen)?><small>%</small></span>
                        <div class="slice">
                            <div class="bar"></div>
                            <div class="fill"></div>
                        </div>
                    </div>
                </th>
                <!-- Following headers are rotated -->
                <?php 
                $totalMarks = array(); $totScore = array();
                $min = array(); $max = array();
                foreach ($worksheets AS $worksheet) { 
                    // Calculation FullMark
                    $sumMark = $this->model_question->get_total_marks_from_worksheet_id($worksheet->worksheet_id);
                    $min[$worksheet->worksheet_id] = 0; $max[$worksheet->worksheet_id] = 0;
                    $totalMarks[$worksheet->worksheet_id] = $sumMark;
                    $totScore[$worksheet->worksheet_id] = 0;
                    if(strlen($worksheet->worksheet_name)> 30) {
                        $substr = substr($worksheet->worksheet_name, 0, 30);
                        $wsName = $substr . '...';
                    } else {
                        $wsName = $worksheet->worksheet_name;
                    } 
                    $worksheetAttempt = $this->model_general->getAllData('`sj_quiz_attempt` qa, sj_quiz qz, sj_worksheet ws', 'attemptDateTime','desc limit 1', " WHERE qa.quizId=qz.id and qz.worksheetId=ws.worksheet_id and ws.created_by=".$userId." and ws.worksheet_id=".$worksheet->worksheet_id." and qa.branch_code=".BRANCH_ID."","qa.id");
                    if($worksheetAttempt) {
                        $x = 1;
                        foreach ($worksheetAttempt as $key => $value) {
                            $totScore[$worksheet->worksheet_id."_".$x] = 0;
                            $min[$worksheet->worksheet_id."_".$x] = 0; $max[$worksheet->worksheet_id."_".$x] = 0;
                            $attemptX = (count($worksheetAttempt)>1) ? " [Attempt ".$x."]" : ""; 
                            $dataAttempt = (count($worksheetAttempt)>1) ? $x : "";?>
                            <th class="rotate-45 worksheet_report" data-attempt="<?php echo $dataAttempt;?>" data-id="<?php echo $worksheet->worksheet_id?>" title="<?php echo $worksheet->worksheet_name?>"><div><?php echo $wsName.$attemptX;?></div></th>
                    <?php   $x++;  
                        }
                    } else {
                ?>
                    <th class="rotate-45 worksheet_report" data-attempt="" data-id="<?php echo $worksheet->worksheet_id?>" title="<?php echo $worksheet->worksheet_name?>"><div><?php echo $wsName;?></div></th>
                <?php }
                } ?>
                <th style="width:auto"></th>
              </tr>
            </thead>
            <tbody>
            <?php 
            $totStudent = count($students);
            foreach ($students AS $key => $student) { 
                 ?>
              <tr>
                <th class="row-header"><i class="fa fa-user"></i> <?php echo $student->fullname;?></th>
                <?php 
                foreach ($worksheets AS $worksheet) { 
                    $getStudentWS = $this->model_general->getDataWhere('sj_quiz'," where worksheetId='".$worksheet->worksheet_id."' and assignedTo='".$student->id."' and status='1' and branch_code='".BRANCH_ID."' ");
                    if($getStudentWS) {
                        $worksheetAttempt = $this->model_general->getAllData('`sj_quiz_attempt` qa, sj_quiz qz, sj_worksheet ws', 'attemptDateTime','desc limit 1', " WHERE qa.quizId=qz.id and qz.worksheetId=ws.worksheet_id and ws.created_by=".$userId." and ws.worksheet_id=".$worksheet->worksheet_id." and qa.branch_code=".BRANCH_ID." and assignedTo='".$student->id."' ","qa.id,attemptScore");
                        if($worksheetAttempt) {
                            $x = 1;
                            foreach ($worksheetAttempt as $k => $value) {
                                $id_quiz_attempt = $value['id'];
                                $allStudentScore = $value['attemptScore'];
                                $score = ($allStudentScore/$totalMarks[$worksheet->worksheet_id])*100;
                                $min[$worksheet->worksheet_id] = ($min[$worksheet->worksheet_id]>=$score) ? $score : $min[$worksheet->worksheet_id];
                                $max[$worksheet->worksheet_id] = ($max[$worksheet->worksheet_id]>=$score) ? $max[$worksheet->worksheet_id] : $score;
                                $totScore[$worksheet->worksheet_id] += $score;
                                $totScore[$worksheet->worksheet_id."_".$x] += $score;
                                $min[$worksheet->worksheet_id."_".$x] = ($min[$worksheet->worksheet_id."_".$x]>=$score) ? $score : $min[$worksheet->worksheet_id."_".$x];
                                $max[$worksheet->worksheet_id."_".$x] = ($max[$worksheet->worksheet_id."_".$x]>=$score) ? $max[$worksheet->worksheet_id."_".$x] : $score;
                        ?>
                            <td>
                                <?php
                                    $percen =  ceil($score);
                                    $percen = ($percen>100) ? 100 : $percen;
                                    $color = ($percen<=30) ? "red" : "green";
                                    $color = ($percen>30 && $percen<=70) ? "orange" : $color;
                                    $percen_p = ($percen==0) ? "100" : ceil($percen);
                                ?>
                                <div class="c100 p<?php echo $percen_p?> small <?php echo $color?>">
                                    <span style="font-size: 11px !important;line-height: 2.75em;width: 2.85em;"><?php echo ceil($percen)?><small style="font-size: 9px;">%</small></span>
                                    <div class="slice">
                                        <div class="bar"></div>
                                        <div class="fill"></div>
                                    </div>
                                </div>
                            </td>
                        <?php $x++;
                            } ?>
                <?php 
                        }  else {
                            echo '<td><small>NR</small></td>';
                       /*     $worksheetAttempt = $this->model_general->getAllData('`sj_quiz_attempt` qa, sj_quiz qz, sj_worksheet ws', 'attemptDateTime','desc limit 1', " WHERE qa.quizId=qz.id and qz.worksheetId=ws.worksheet_id and ws.created_by=".$userId." and ws.worksheet_id=".$worksheet->worksheet_id." and qa.branch_code=".BRANCH_ID."","qa.id");
                            $x = 1;
                            foreach ($worksheetAttempt as $key => $value) {
                                if($x>1){ echo '<td><small>NR</small></td>'; } 
                                $x++;  
                            } */
                        }
                    } else {
                        echo '<td><small>NA</small></td>';
                    /*    $worksheetAttempt = $this->model_general->getAllData('`sj_quiz_attempt` qa, sj_quiz qz, sj_worksheet ws', 'attemptDateTime','desc limit 1', " WHERE qa.quizId=qz.id and qz.worksheetId=ws.worksheet_id and ws.created_by=".$userId." and ws.worksheet_id=".$worksheet->worksheet_id." and qa.branch_code=".BRANCH_ID."","qa.id");
                        $x = 1;
                        foreach ($worksheetAttempt as $key => $value) {
                            if($x>1){ echo '<td><small>NA</small></td>'; } 
                            $x++;  
                        } */
                    }
        } ?>
                <td style="width:auto"></td>
              </tr>
            <?php } ?>
            <tr>
                <td>Average</td>
                <?php $totAVG = 0;
                foreach ($worksheets AS $worksheet) {
                    $worksheetAttempt = $this->model_general->getAllData('`sj_quiz_attempt` qa, sj_quiz qz, sj_worksheet ws', 'attemptDateTime','desc limit 1', " WHERE qa.quizId=qz.id and qz.worksheetId=ws.worksheet_id and ws.created_by=".$userId." and ws.worksheet_id=".$worksheet->worksheet_id." and qa.branch_code=".BRANCH_ID."","qa.id");
                    if($worksheetAttempt) {
                        $x = 1;
                        foreach ($worksheetAttempt as $key => $value) {
                            $AVGscore = ($totScore[$worksheet->worksheet_id."_".$x]/ $totStudent );
                            $totAVG += $AVGscore;
                            $percen =  ceil($AVGscore);
                            $percen = ($percen>100) ? 100 : $percen;
                            $color = ($percen<=30) ? "red" : "green";
                            $color = ($percen>30 && $percen<=70) ? "orange" : $color;
                            $percen_p = ($percen==0) ? "100" : ceil($percen);
                            ?>
                            <td><div class="c100 p<?php echo $percen_p?> small <?php echo $color?>">
                                <span style="font-size: 11px !important;line-height: 2.75em;width: 2.85em;"><?php echo ceil($percen)?><small style="font-size: 9px;">%</small></span>
                                <div class="slice">
                                    <div class="bar"></div>
                                    <div class="fill"></div>
                                </div>
                            </div>
                        </td>
                    <?php   $x++;  
                        } 
                    } else {
                ?>
                    <td><?php 
                        $AVGscore = ($totScore[$worksheet->worksheet_id]/ $totStudent );
                        $totAVG += $AVGscore;
                        $percen =  ceil($AVGscore);
                        $percen = ($percen>100) ? 100 : $percen;
                        $color = ($percen<=30) ? "red" : "green";
                        $color = ($percen>30 && $percen<=70) ? "orange" : $color;
                        $percen_p = ($percen==0) ? "100" : ceil($percen);
                        ?>
                        <div class="c100 p<?php echo $percen_p?> small <?php echo $color?>">
                            <span style="font-size: 11px !important;line-height: 2.75em;width: 2.85em;"><?php echo ceil($percen)?><small style="font-size: 9px;">%</small></span>
                            <div class="slice">
                                <div class="bar"></div>
                                <div class="fill"></div>
                            </div>
                        </div>
                    </td>
                <?php }  
                }
                $totAVG = ($totAVG>0) ? ceil($totAVG / count($worksheets)) : 0;
                ?>
                <td style="width:auto"></td>
            </tr>
            <tr>
                <td>Minimum</td>
                <?php 
                foreach ($worksheets AS $worksheet) { 
                    $worksheetAttempt = $this->model_general->getAllData('`sj_quiz_attempt` qa, sj_quiz qz, sj_worksheet ws', 'attemptDateTime','desc limit 1', " WHERE qa.quizId=qz.id and qz.worksheetId=ws.worksheet_id and ws.created_by=".$userId." and ws.worksheet_id=".$worksheet->worksheet_id." and qa.branch_code=".BRANCH_ID."","qa.id");
                    if($worksheetAttempt) {
                        $x = 1;
                        foreach ($worksheetAttempt as $key => $value) {
                            $percen =  $min[$worksheet->worksheet_id."_".$x];
                            $percen = ($percen>100) ? 100 : $percen;
                            $color = ($percen<=30) ? "red" : "green";
                            $color = ($percen>30 && $percen<=70) ? "orange" : $color;
                            $percen_p = ($percen==0) ? "100" : ceil($percen);
                            ?>
                            <td><div class="c100 p<?php echo $percen_p?> small <?php echo $color?>">
                                <span style="font-size: 11px !important;line-height: 2.75em;width: 2.85em;"><?php echo ceil($percen)?><small style="font-size: 9px;">%</small></span>
                                <div class="slice">
                                    <div class="bar"></div>
                                    <div class="fill"></div>
                                </div>
                            </div>
                        </td>
                    <?php   $x++;  
                        } 
                    } else {
                ?>
                    <td><?php
                        $percen =  $min[$worksheet->worksheet_id];
                        $percen = ($percen>100) ? 100 : $percen;
                        $color = ($percen<=30) ? "red" : "green";
                        $color = ($percen>30 && $percen<=70) ? "orange" : $color;
                        $percen_p = ($percen==0) ? "100" : ceil($percen);
                        ?>
                        <div class="c100 p<?php echo $percen_p?> small <?php echo $color?>">
                            <span style="font-size: 11px !important;line-height: 2.75em;width: 2.85em;"><?php echo ceil($percen)?><small style="font-size: 9px;">%</small></span>
                            <div class="slice">
                                <div class="bar"></div>
                                <div class="fill"></div>
                            </div>
                        </div>
                    </td>
                <?php } }?>
                <td style="width:auto"></td>
            </tr>
            <tr>
                <td>Maximum</td>
                <?php 
                foreach ($worksheets AS $worksheet) { 
                    $worksheetAttempt = $this->model_general->getAllData('`sj_quiz_attempt` qa, sj_quiz qz, sj_worksheet ws', 'attemptDateTime','desc limit 1', " WHERE qa.quizId=qz.id and qz.worksheetId=ws.worksheet_id and ws.created_by=".$userId." and ws.worksheet_id=".$worksheet->worksheet_id." and qa.branch_code=".BRANCH_ID."","qa.id");
                    if($worksheetAttempt) {
                        $x = 1;
                        foreach ($worksheetAttempt as $key => $value) {
                            $percen =  $max[$worksheet->worksheet_id."_".$x];
                            $percen = ($percen>100) ? 100 : $percen;
                            $color = ($percen<=30) ? "red" : "green";
                            $color = ($percen>30 && $percen<=70) ? "orange" : $color;
                            $percen_p = ($percen==0) ? "100" : ceil($percen);
                            ?>
                            <td><div class="c100 p<?php echo $percen_p?> small <?php echo $color?>">
                                <span style="font-size: 11px !important;line-height: 2.75em;width: 2.85em;"><?php echo ceil($percen)?><small style="font-size: 9px;">%</small></span>
                                <div class="slice">
                                    <div class="bar"></div>
                                    <div class="fill"></div>
                                </div>
                            </div>
                        </td>
                    <?php   $x++;  
                        } 
                    } else {
                ?>
                    <td><?php
                        $percen =  $max[$worksheet->worksheet_id];
                        $percen = ($percen>100) ? 100 : $percen;
                        $color = ($percen<=30) ? "red" : "green";
                        $color = ($percen>30 && $percen<=70) ? "orange" : $color;
                        $percen_p = ($percen==0) ? "100" : ceil($percen);
                        ?>
                        <div class="c100 p<?php echo $percen_p?> small <?php echo $color?>">
                            <span style="font-size: 11px !important;line-height: 2.75em;width: 2.85em;"><?php echo ceil($percen)?><small style="font-size: 9px;">%</small></span>
                            <div class="slice">
                                <div class="bar"></div>
                                <div class="fill"></div>
                            </div>
                        </div>
                    </td>
                <?php } } ?>
                <td style="width:auto"></td>
            </tr>
            </tbody>
          </table>
        </div> 
    </div>
    <input type="hidden" name="all_ws_score" id="all_ws_score" value="<?php echo $totAVG;?>" />
    <script type="text/javascript">
        $(document).ready(function(){ 
            var all_ws_score = $('#all_ws_score');
            var score_all_worksheet = $('#score_all_worksheet');
            var score = all_ws_score.val();
            var color = (parseInt(score)<=30) ? "red" : "green";
            color = (parseInt(score)>30 && parseInt(score)<=70) ? "orange" : color;
            score_all_worksheet.empty();
            score_all_worksheet.html('<div class="c100 p'+score+' big '+color+' "><span style="font-size: 30px;margin-top: 10px;">'+score+'<small>%</small></span><div class="slice"><div class="bar"></div><div class="fill"></div></div></div>');
        });
    </script>
    <div id="report_student_ws_detail" class="table-report" style="display: none">            
        <div class="scrollable-table">
        </div> 
    </div>
    <div id="report_student_topic" class="table-report" style="display: none">            
        <div class="scrollable-table">
          <table class="table table-striped table-header-rotated">
            <thead>
                <tr>
                <!-- First column header is not rotated -->
                <?php
                    $date_range = array();
                    $percen = 25 ;
                    $percen = ($percen>100) ? 100 : $percen;
                    $color = ($percen<=30) ? "red" : "green";
                    $color = ($percen>30 && $percen<=70) ? "orange" : $color;
                    $percen_p = ($percen==0) ? "100" : ceil($percen);
                ?>
                <th style="border: 1px solid #ccc;width:200px !important;padding: 49px 0 49px 63px;" id="score_all_topic">
                    <div class="c100 p<?php echo $percen_p?> big <?php echo $color;?>">
                        <span><?php echo ceil($percen)?><small>%</small></span>
                        <div class="slice">
                            <div class="bar"></div>
                            <div class="fill"></div>
                        </div>
                    </div>
                </th>
                <!-- Following headers are rotated -->
                <?php 
                $totScore = array();
                $min = array(); $max = array();
                foreach ($topics_tid as $topic) { 
                    if(strlen($topic['topic_name'])> 40) {
                        $substr = substr($topic['topic_name'], 0, 40);
                        $wsName = $substr . '...';
                    } else {
                        $wsName = $topic['topic_name'];
                    }
                    $wsName = str_replace("/", "/ ", $wsName );
                    $totScore[$topic['topic_id']] = 0;
                    $min[$topic['topic_id']] = 0;
                    $max[$topic['topic_id']] = 0;
                ?>
                    <th class="rotate-45" data-tooltip="<?php echo $topic['topic_name']?>"><div><?php echo $wsName." (".$topic['topic_short_name'].")";?></div></th>
                <?php } ?>
                    <th style="width:auto"></th>
              </tr>
            </thead>
            <tbody>
            <?php foreach ($students AS $key => $student) { 
                $performance = $this->model_users->get_ep_performance(array($student->id), $date_range)[$student->id];?>
              <tr>
                <th class="row-header"><i class="fa fa-user"></i> <?php echo $student->fullname;?></th>
                <?php 
                foreach ($topics_tid as $topic) {   ?>
                    <td><?php
                            $percen = $performance[$topic['topic_name']]['percentage'] ;
                            $score = $percen;
                             $min[$topic['topic_id']] = ($min[$topic['topic_id']]>=$score) ? $score : $min[$topic['topic_id']];
                            $max[$topic['topic_id']] = ($max[$topic['topic_id']]>=$score) ? $max[$topic['topic_id']] : $score;
                            $totScore[$topic['topic_id']] += $score;
                            $percen = ($percen>100) ? 100 : $percen;
                            $color = ($percen<=30) ? "red" : "green";
                            $color = ($percen>30 && $percen<=70) ? "orange" : $color;
                            $percen_p = ($percen==0) ? "100" : ceil($percen);
                        ?>
                        <div class="c100 p<?php echo $percen_p?> small <?php echo $color?>">
                            <span style="font-size: 11px !important;line-height: 2.75em;width: 2.85em;"><?php echo ceil($percen)?><small style="font-size: 9px;">%</small></span>
                            <div class="slice">
                                <div class="bar"></div>
                                <div class="fill"></div>
                            </div>
                        </div>
                    </td>
                <?php } ?>
                <td style="width:auto"></td>
              </tr>
            <?php } ?>
            <tr>
                <td>Average</td>
                <?php $totStudent = count($students);
                $totAVG = 0;
                foreach ($topics_tid as $topic) { 
                    $AVGscore = ($totScore[$topic['topic_id']]>0) ? ($totScore[$topic['topic_id']]/ $totStudent ) : 0;
                    $totAVG += $AVGscore;
                    $percen = $AVGscore;
                    $percen = ($percen>100) ? 100 : $percen;
                    $color = ($percen<=30) ? "red" : "green";
                    $color = ($percen>30 && $percen<=70) ? "orange" : $color;
                    $percen_p = ($percen==0) ? "100" : ceil($percen);   
                ?>
                    <td>
                        <div class="c100 p<?php echo $percen_p?> small <?php echo $color?>">
                            <span style="font-size: 11px !important;line-height: 2.75em;width: 2.85em;"><?php echo ceil($percen)?><small style="font-size: 9px;">%</small></span>
                            <div class="slice">
                                <div class="bar"></div>
                                <div class="fill"></div>
                            </div>
                        </div></td>
                <?php } 
                $totAVG = ($totAVG>0) ? ceil($totAVG / count($topics_tid)) : 0;?>
                    <td style="width:auto"></td>
            </tr>
            <tr>
                <td>Minimum</td>
                <?php 
                foreach ($topics_tid as $topic) {
                ?>
                    <td><?php
                        $percen =  $min[$topic['topic_id']];
                        $percen = ($percen>100) ? 100 : $percen;
                        $color = ($percen<=30) ? "red" : "green";
                        $color = ($percen>30 && $percen<=70) ? "orange" : $color;
                        $percen_p = ($percen==0) ? "100" : ceil($percen);
                        ?>
                        <div class="c100 p<?php echo $percen_p?> small <?php echo $color?>">
                            <span style="font-size: 11px !important;line-height: 2.75em;width: 2.85em;"><?php echo ceil($percen)?><small style="font-size: 9px;">%</small></span>
                            <div class="slice">
                                <div class="bar"></div>
                                <div class="fill"></div>
                            </div>
                        </div></td>
                <?php } ?>
                    <td style="width:auto"></td>
            </tr>
            <tr>
                <td>Maximum</td>
                <?php 
                foreach ($topics_tid as $topic) { 
                ?>
                    <td><?php
                        $percen =  $max[$topic['topic_id']];
                        $percen = ($percen>100) ? 100 : $percen;
                        $color = ($percen<=30) ? "red" : "green";
                        $color = ($percen>30 && $percen<=70) ? "orange" : $color;
                        $percen_p = ($percen==0) ? "100" : ceil($percen);
                        ?>
                        <div class="c100 p<?php echo $percen_p?> small <?php echo $color?>">
                            <span style="font-size: 11px !important;line-height: 2.75em;width: 2.85em;"><?php echo ceil($percen)?><small style="font-size: 9px;">%</small></span>
                            <div class="slice">
                                <div class="bar"></div>
                                <div class="fill"></div>
                            </div>
                        </div></td>
                <?php } ?>
                    <td style="width:auto"></td>
            </tr>
            </tbody>
          </table>
        </div> 
    </div>
    <input type="hidden" name="all_topic_score" id="all_topic_score" value="<?php echo $totAVG;?>" />
    <script type="text/javascript">
        $(document).ready(function(){ 
            var all_topic_score = $('#all_topic_score');
            var score_all_topic = $('#score_all_topic');
            var score = all_topic_score.val();
            var color = (parseInt(score)<=30) ? "red" : "green";
            color = (parseInt(score)>30 && parseInt(score)<=70) ? "orange" : color;
            score_all_topic.empty();
            score_all_topic.html('<div class="c100 p'+score+' big '+color+' "><span>'+score+'<small>%</small></span><div class="slice"><div class="bar"></div><div class="fill"></div></div></div>');
        });
    </script>
    <div id="report_student_ability" class="table-report" style="display: none">            
        <div class="scrollable-table">
          <table class="table table-striped table-header-rotated">
            <thead>
                <tr>
                <!-- First column header is not rotated -->
                <?php
                    $percen = 65 ;
                    $percen = ($percen>100) ? 100 : $percen;
                    $color = ($percen<=30) ? "red" : "green";
                    $color = ($percen>30 && $percen<=70) ? "orange" : $color;
                    $percen_p = ($percen==0) ? "100" : ceil($percen);
                ?>
                <th style="border: 1px solid #ccc;width:200px !important;padding: 49px 0 49px 63px;" id="score_all_ability">
                    <div class="c100 p<?php echo $percen_p?> big <?php echo $color;?>">
                        <span><?php echo ceil($percen)?><small>%</small></span>
                        <div class="slice">
                            <div class="bar"></div>
                            <div class="fill"></div>
                        </div>
                    </div>
                </th>
                <!-- Following headers are rotated -->
                <?php 
                $totScore = array();
                $min = array(); $max = array();
                foreach ($ability as $r => $ab) { 
                    if(strlen($ab['ability_name'])> 40) {
                        $substr = substr($ab['ability_name'], 0, 40);
                        $wsName = $substr . '...';
                    } else {
                        $wsName = $ab['ability_name'];
                    }
                    $wsName = str_replace("/", "/ ", $wsName );
                    $totScore[$ab['ability_id']] = 0;
                    $min[$ab['ability_id']] = 0;
                    $max[$ab['ability_id']] = 0;
                ?>
                    <th class="rotate-45" data-tooltip="<?php echo $ab['ability_name']?>"><div><?php echo $wsName;?></div></th>
                <?php } ?>
                    <th style="width:auto"></th>
              </tr>
            </thead>
            <tbody>
            <?php foreach ($students AS $key => $student) { 
                $performance = $this->model_users->get_ep_performance_ability(array($student->id), $date_range)[$student->id];?>
              <tr>
                <th class="row-header"><i class="fa fa-user"></i> <?php echo $student->fullname;?></th>
                <?php 
                foreach ($ability as $r => $ab) {   ?>
                    <td>
                    <?php
                        $percen = $performance[$ab['ability_name']]['percentage'] ;
                        $score = $percen;
                         $min[$ab['ability_id']] = ($min[$ab['ability_id']]>=$score) ? $score : $min[$ab['ability_id']];
                        $max[$ab['ability_id']] = ($max[$ab['ability_id']]>=$score) ? $max[$ab['ability_id']] : $score;
                        $totScore[$ab['ability_id']] += $score;
                        $percen = ($percen>100) ? 100 : $percen;
                        $color = ($percen<=30) ? "red" : "green";
                        $color = ($percen>30 && $percen<=70) ? "orange" : $color;
                        $percen_p = ($percen==0) ? "100" : ceil($percen);
                    ?>
                        <div class="c100 p<?php echo $percen_p?> small <?php echo $color?>">
                            <span style="font-size: 11px !important;line-height: 2.75em;width: 2.85em;"><?php echo ceil($percen)?><small style="font-size: 9px;">%</small></span>
                            <div class="slice">
                                <div class="bar"></div>
                                <div class="fill"></div>
                            </div>
                        </div>
                    </td>
                <?php } ?>
                <td style="width:auto"></td>
              </tr>
            <?php } ?>
            <tr>
                <td>Average</td>
                <?php 
                $totAVG = 0;
                foreach ($ability as $r => $ab) { 
                 $AVGscore = ($totScore[$ab['ability_id']]>0) ? ($totScore[$ab['ability_id']]/ $totStudent ) : 0;
                    $totAVG += $AVGscore;
                    $percen = $AVGscore;
                    $percen = ($percen>100) ? 100 : $percen;
                    $color = ($percen<=30) ? "red" : "green";
                    $color = ($percen>30 && $percen<=70) ? "orange" : $color;
                    $percen_p = ($percen==0) ? "100" : ceil($percen);   
                ?>
                    <td>
                        <div class="c100 p<?php echo $percen_p?> small <?php echo $color?>">
                            <span style="font-size: 11px !important;line-height: 2.75em;width: 2.85em;"><?php echo ceil($percen)?><small style="font-size: 9px;">%</small></span>
                            <div class="slice">
                                <div class="bar"></div>
                                <div class="fill"></div>
                            </div>
                        </div></td>
                <?php } 
                $totAVG = ($totAVG>0) ? ceil($totAVG / count($ability)) : 0;?>
                    <td style="width:auto"></td>
            </tr>
            <tr>
                <td>Minimum</td>
                <?php 
                foreach ($ability as $r => $ab) { 
                ?>
                    <td><?php
                        $percen =  $min[$ab['ability_id']];
                        $percen = ($percen>100) ? 100 : $percen;
                        $color = ($percen<=30) ? "red" : "green";
                        $color = ($percen>30 && $percen<=70) ? "orange" : $color;
                        $percen_p = ($percen==0) ? "100" : ceil($percen);
                        ?>
                        <div class="c100 p<?php echo $percen_p?> small <?php echo $color?>">
                            <span style="font-size: 11px !important;line-height: 2.75em;width: 2.85em;"><?php echo ceil($percen)?><small style="font-size: 9px;">%</small></span>
                            <div class="slice">
                                <div class="bar"></div>
                                <div class="fill"></div>
                            </div>
                        </div></td>
                <?php } ?>
                    <td style="width:auto"></td>
            </tr>
            <tr>
                <td>Maximum</td>
                <?php 
                foreach ($ability as $r => $ab) { 
                ?><td>
                <?php
                    $percen =  $max[$ab['ability_id']];
                    $percen = ($percen>100) ? 100 : $percen;
                    $color = ($percen<=30) ? "red" : "green";
                    $color = ($percen>30 && $percen<=70) ? "orange" : $color;
                    $percen_p = ($percen==0) ? "100" : ceil($percen);
                    ?>
                    <div class="c100 p<?php echo $percen_p?> small <?php echo $color?>">
                        <span style="font-size: 11px !important;line-height: 2.75em;width: 2.85em;"><?php echo ceil($percen)?><small style="font-size: 9px;">%</small></span>
                        <div class="slice">
                            <div class="bar"></div>
                            <div class="fill"></div>
                        </div>
                    </div></td>
                <?php } ?>
                    <td style="width:auto"></td>
            </tr>
            </tbody>
          </table>
        </div> 
    </div>
    <input type="hidden" name="all_ability_score" id="all_ability_score" value="<?php echo $totAVG;?>" />
    <script type="text/javascript">
        $(document).ready(function(){ 
            var all_ability_score = $('#all_ability_score');
            var score_all_ability = $('#score_all_ability');
            var score = all_ability_score.val();
            var color = (parseInt(score)<=30) ? "red" : "green";
            color = (parseInt(score)>30 && parseInt(score)<=70) ? "orange" : color;
            score_all_ability.empty();
            score_all_ability.html('<div class="c100 p'+score+' big '+color+' "><span>'+score+'<small>%</small></span><div class="slice"><div class="bar"></div><div class="fill"></div></div></div>');
        });
    </script>

<?php /*
    <div id="report_student_heuristics" class="table-report" style="display: none">            
        <div class="scrollable-table">
          <table class="table table-striped table-header-rotated">
            <thead>
                <tr>
                <!-- First column header is not rotated -->
                <?php
                    $percen = 65 ;
                    $percen = ($percen>100) ? 100 : $percen;
                    $color = ($percen<=30) ? "red" : "green";
                    $color = ($percen>30 && $percen<=70) ? "orange" : $color;
                    $percen_p = ($percen==0) ? "100" : ceil($percen);
                ?>
                <th style="border: 1px solid #ccc;width:200px !important;padding: 49px 0 49px 63px;" id="score_all_heuristics">
                    <div class="c100 p<?php echo $percen_p?> big <?php echo $color;?>">
                        <span><?php echo ceil($percen)?><small>%</small></span>
                        <div class="slice">
                            <div class="bar"></div>
                            <div class="fill"></div>
                        </div>
                    </div>
                </th>
                <!-- Following headers are rotated -->
                <?php 
                $totScore = array();
                $min = array(); $max = array();
                foreach ($heuristics as $r => $ab) { 
                    if(strlen($ab['heuristic_name'])> 40) {
                        $substr = substr($ab['heuristic_name'], 0, 40);
                        $wsName = $substr . '...';
                    } else {
                        $wsName = $ab['heuristic_name'];
                    }
                    $wsName = str_replace("/", "/ ", $wsName );
                    $totScore[$ab['heuristic_id']] = 0;
                    $min[$ab['heuristic_id']] = 0;
                    $max[$ab['heuristic_id']] = 0;
                ?>
                    <th class="rotate-45" data-tooltip="<?php echo $ab['heuristic_name']?>"><div><?php echo $wsName;?></div></th>
                <?php } ?>
                    <th style="width:auto"></th>
              </tr>
            </thead>
            <tbody>
            <?php foreach ($students AS $key => $student) { 
                $performance = array(); //$this->model_users->get_ep_performance_ability(array($student->id), $date_range)[$student->id];?>
              <tr>
                <th class="row-header"><i class="fa fa-user"></i> <?php echo $student->fullname;?></th>
                <?php 
                foreach ($heuristics as $r => $ab) {   ?>
                    <td>
                    <?php
                        $percen = 0; //$performance[$ab['heuristic_name']]['percentage'] ;
                        $score = $percen;
                         $min[$ab['heuristic_id']] = ($min[$ab['heuristic_id']]>=$score) ? $score : $min[$ab['heuristic_id']];
                        $max[$ab['heuristic_id']] = ($max[$ab['heuristic_id']]>=$score) ? $max[$ab['heuristic_id']] : $score;
                        $totScore[$ab['heuristic_id']] += $score;
                        $percen = ($percen>100) ? 100 : $percen;
                        $color = ($percen<=30) ? "red" : "green";
                        $color = ($percen>30 && $percen<=70) ? "orange" : $color;
                        $percen_p = ($percen==0) ? "100" : ceil($percen);
                    ?>
                        <div class="c100 p<?php echo $percen_p?> small <?php echo $color?>">
                            <span style="font-size: 11px !important;line-height: 2.75em;width: 2.85em;"><?php echo ceil($percen)?><small style="font-size: 9px;">%</small></span>
                            <div class="slice">
                                <div class="bar"></div>
                                <div class="fill"></div>
                            </div>
                        </div>
                    </td>
                <?php } ?>
                <td style="width:auto"></td>
              </tr>
            <?php } ?>
            <tr>
                <td>Average</td>
                <?php 
                $totAVG = 0;
                foreach ($heuristics as $r => $ab) { 
                 $AVGscore = ($totScore[$ab['heuristic_id']]>0) ? ($totScore[$ab['heuristic_id']]/ $totStudent ) : 0;
                    $totAVG += $AVGscore;
                    $percen = $AVGscore;
                    $percen = ($percen>100) ? 100 : $percen;
                    $color = ($percen<=30) ? "red" : "green";
                    $color = ($percen>30 && $percen<=70) ? "orange" : $color;
                    $percen_p = ($percen==0) ? "100" : ceil($percen);   
                ?>
                    <td>
                        <div class="c100 p<?php echo $percen_p?> small <?php echo $color?>">
                            <span style="font-size: 11px !important;line-height: 2.75em;width: 2.85em;"><?php echo ceil($percen)?><small style="font-size: 9px;">%</small></span>
                            <div class="slice">
                                <div class="bar"></div>
                                <div class="fill"></div>
                            </div>
                        </div></td>
                <?php } 
                $totAVG = ($totAVG>0) ? ceil($totAVG / count($ability)) : 0;?>
                    <td style="width:auto"></td>
            </tr>
            <tr>
                <td>Minimum</td>
                <?php 
                foreach ($heuristics as $r => $ab) { 
                ?>
                    <td><?php
                        $percen =  $min[$ab['heuristic_id']];
                        $percen = ($percen>100) ? 100 : $percen;
                        $color = ($percen<=30) ? "red" : "green";
                        $color = ($percen>30 && $percen<=70) ? "orange" : $color;
                        $percen_p = ($percen==0) ? "100" : ceil($percen);
                        ?>
                        <div class="c100 p<?php echo $percen_p?> small <?php echo $color?>">
                            <span style="font-size: 11px !important;line-height: 2.75em;width: 2.85em;"><?php echo ceil($percen)?><small style="font-size: 9px;">%</small></span>
                            <div class="slice">
                                <div class="bar"></div>
                                <div class="fill"></div>
                            </div>
                        </div></td>
                <?php } ?>
                    <td style="width:auto"></td>
            </tr>
            <tr>
                <td>Maximum</td>
                <?php 
                foreach ($heuristics as $r => $ab) { 
                ?><td>
                <?php
                    $percen =  $max[$ab['heuristic_id']];
                    $percen = ($percen>100) ? 100 : $percen;
                    $color = ($percen<=30) ? "red" : "green";
                    $color = ($percen>30 && $percen<=70) ? "orange" : $color;
                    $percen_p = ($percen==0) ? "100" : ceil($percen);
                    ?>
                    <div class="c100 p<?php echo $percen_p?> small <?php echo $color?>">
                        <span style="font-size: 11px !important;line-height: 2.75em;width: 2.85em;"><?php echo ceil($percen)?><small style="font-size: 9px;">%</small></span>
                        <div class="slice">
                            <div class="bar"></div>
                            <div class="fill"></div>
                        </div>
                    </div></td>
                <?php } ?>
                    <td style="width:auto"></td>
            </tr>
            </tbody>
          </table>
        </div> 
    </div>
    <input type="hidden" name="all_heuristics_score" id="all_heuristics_score" value="<?php echo $totAVG;?>" />
    <script type="text/javascript">
        $(document).ready(function(){ 
            var all_heuristics_score = $('#all_heuristics_score');
            var score_all_heuristics = $('#score_all_heuristics');
            var score = all_heuristics_score.val();
            var color = (parseInt(score)<=30) ? "red" : "green";
            color = (parseInt(score)>30 && parseInt(score)<=70) ? "orange" : color;
            score_all_heuristics.empty();
            score_all_heuristics.html('<div class="c100 p'+score+' big '+color+' "><span>'+score+'<small>%</small></span><div class="slice"><div class="bar"></div><div class="fill"></div></div></div>');
        });
    </script>
*/ ?>
<style type="text/css">
    .btn-img{
        margin-left:0;
        color: #000;
        background-color: #fff;
        border: 1px solid #2abb9b;
        width: 22.95%;
        cursor: pointer;
        padding: 7px 2px;
        margin-right: 7.02px;
    }
    .btn-work:hover, .btn-active {
        background-color: #dff0d8;
    }
    .show_working {
        border:1px solid #ddd;
        border-radius: 5px;
        width: 98%;
        min-height: 200px;
        max-height: 200px;
        background-color: #FFF;
        margin-top: 1px;
        text-align: center;
    }
    .helper {
        display: inline-block;
        height: 100%;
        vertical-align: middle;
    }
    .show_working img {
        width: inherit;
        min-height: 200px;
    }
    .label-radio {
        border-color: #666 !important;;
    }
    .radioUi-success input:checked {
        border-color: #60ADA7 !important;;
    }
    .label-radio::after {
        background-color: #60ADA7 !important;
    }
    .icon_fa {
        font-size: 18px;
        color: #2ABB9B;
    }
    .icon_false {
        font-size: 18px;
        color: red;
    }
    .prev_attempt, .next_attempt {
        cursor: pointer;
    }
    .img_grey {
        filter: gray;
        -webkit-filter: grayscale(1);
        filter: grayscale(1);
        cursor: not-allowed;
    }
    .card {
        display: block !important;
        -webkit-column-break-inside: avoid;
        page-break-inside: avoid;
        break-inside: avoid;
    }
    .card_hide, .nav_hide {
        display: none;
    }
    .card_question_title {
        line-height: 1.3;
    }
    .btnExpand {
        line-height: 1.75;
    }
    .blankBg:focus {
        color: #000;
    }
    .card_question_text {
        min-height: 406px; 
    }
    .card-columns .card {
        max-width: 366px !important;
    }
</style>
<div class="panel_data" id="report_student_question" style="display: none">
    <div class="pb-40">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12" style="margin:15px 0 ;" id="question-filter">
                <h4 class="title_ws_qs"><i class="fa fa-newspaper-o"></i> &nbsp;WS Title</h4>
                <div id="label_back" class="div-filter">
                    <img src="<?php echo base_url()?>img/back.png" width="30" style="cursor: pointer;" class="back_to_ws_detail" /> Back
                </div>
                <div id="label_filter_question_number" class="div-filter run-filter dropdown">
                    Questions: <span id="filter_question" class="span-filter dropbtn">Q1 <i class="fa fa-sort-down"></i></span>
                    <div class="dropdown-content dropdown-question-number" id="choose_question">
                        <?php 
                        $i = 1; $q = 10;
                        while ($i<=$q) {
                            $active_filter = ($i=="1") ? " fa-check" : " width14";
                            echo '<a href="#" onclick="chooseFilter(\''.$i.'\',\'quest_number\')"><i class="q'.$i.' q-filter fa '.$active_filter.'"></i> Q'.$i.'</a>';
                        $i++; } ?>
                    </div>
                </div>
                <div id="label_filter_student" class="div-filter run-filter dropdown">
                        Student: <span id="filter_student" class="span-filter dropbtn">By Group <i class="fa fa-sort-down"></i></span>
                    <div class="dropdown-content dropdown-student" style="right: -60px;">
                        <table style="min-width: 225px;">
                        <tr><td width="50%">
                            <button class="btn btn-outline-secondary btn-sm" id="check_uncheck2">Uncheck All</button>
                        </td><td>
                            <label class="switch">
                                <input type="checkbox" id="student_filter" data-id="0" data-status="1" class="setFilterStudent2" id="togBtn" checked>
                                <div class="slider round"><span class="on">By Group</span><span class="off">By Class</span></div>
                            </label>
                            <input type="hidden" name="gen_student_filter2" id="gen_student_filter2" value="1" />
                        </td></tr>
                        </table>
                        <table style="min-width: 225px;" id="filter_by_group2">
                        <?php /*
                        <tr class="tr_group"><td width="35">
                                    <input type="checkbox" class="checkbox_filter" id="q_student_all" checked value="all_students" /> </td><td>
                                <a href="#" onclick="selectFilter('all_students','student')">All Student </a>
                                </td></tr> */ ?>
                            <?php 
                            $get_tutor_group = $this->model_users->get_group($this->session->userdata('user_id'));
                            $gx = 1;
                            $grouped_student = array();
                            foreach ($get_tutor_group as $key => $value) {
                                $check = $this->model_users->get_student_assign_group($this->session->userdata('user_id'),$value['id'],"sum");
                                if($check > 0) {
                                    $active_filter = ($value['id']=="") ? " fa-check" : " width14";
                                    echo '<tr class="tr_group"><td width="35">
                                    <label class="container-filter"> &nbsp;
                                      <input checked type="checkbox" id="' . $value['id'] .'" class="checkbox_filter qstudent_group qgroup_id_'.$value['id'].'" value="qgroup_'.$value['id'].'" />
                                      <span class="checkmark"></span>
                                    </label></td><td class="qtd_group" data-group="'.$value['id'].'"><a>' . $value['group_name'] . ' </a></td></tr>';
                                    $check_student = $this->model_users->get_student_assign_group($this->session->userdata('user_id'),$value['id'],"record");
                                    foreach ($check_student as $v) {
                                        $studentName = $this->model_users->get_username_from_id($v['student_id']);
                                        echo '<tr class="tr_item qtr_item_'.$value['id'].'"><td>
                                        <label class="container-filter"> &nbsp;
                                          <input checked type="checkbox" id="cb_student_'.$v['student_id'].'" class="checkbox_filter qstudent_item qgroup_item qgroup_item_'.$value['id'].'" value="'.$v['student_id'].'" />
                                          <span class="checkmark" style="margin-left:5px;"></span>
                                        </label></td><td><a>' . $studentName . '</a></td></tr>';
                                        $grouped_student[] =  $v['student_id'];
                                    } 
                                    $gx++;
                                } 
                            }

                            $getAllStudent = $this->model_users->get_student_list($this->session->userdata('user_id'));
                            $ungroup_count = 1;
                            foreach ($getAllStudent AS $student) {
                                if(in_array($student->id, $grouped_student)) continue;
                                if($ungroup_count==1) {
                                    echo '<tr class="tr_group"><td>
                                    <label class="container-filter"> &nbsp;
                                      <input checked type="checkbox" id="0" class="checkbox_filter qstudent_group qgroup_id_0" value="qgroup_0" />
                                      <span class="checkmark"></span>
                                    </label></td><td class="qtd_group" data-group="0"><a>Ungrouped</a></td></tr>';
                                }
                                $studentName = $this->model_users->get_username_from_id($student->id);
                                echo '<tr class="tr_item qtr_item_0"><td>
                                        <label class="container-filter"> &nbsp;
                                          <input checked type="checkbox" id="cb_student_'.$student->id.'" class="checkbox_filter qstudent_item qgroup_item qgroup_item_0" value="'.$student->id.'" />
                                          <span class="checkmark" style="margin-left:5px;"></span>
                                        </label</td><td><a>' . $studentName . '</a></td></tr>';
                                $ungroup_count++;
                            } ?>
                        </table>

                        <table style="min-width: 225px;display: none;" id="filter_by_class2">
                        <?php 
                        $get_tutor_group = $this->model_users->get_class($this->session->userdata('user_id'));
                        $gx = 1;
                        $grouped_student = array();
                        foreach ($get_tutor_group as $key => $value) {
                            $check = $this->model_users->get_student_assign_class($this->session->userdata('user_id'),$value['class_id'],"sum");
                            if($check > 0) {
                                echo '<tr class="tr_class"><td width="35">
                                <label class="container-filter"> &nbsp;
                                  <input checked type="checkbox" id="' . $value['class_id'] .'" class="checkbox_filter qstudent_class qclass_id_'.$value['class_id'].'" value="qclass_'.$value['class_id'].'" />
                                  <span class="checkmark"></span>
                                </label></td><td class="qtd_class" data-group="'.$value['class_id'].'"><a>' . $value['class_name'] . ' </a></td></tr>';
                                $check_student = $this->model_users->get_student_assign_class($this->session->userdata('user_id'),$value['class_id'],"record");
                                foreach ($check_student as $v) {
                                    $studentName = $this->model_users->get_username_from_id($v['user_id']);
                                    echo '<tr class="tr_item qtr_item_'.$value['class_id'].'"><td>
                                     <label class="container-filter"> &nbsp;
                                      <input checked type="checkbox" id="cb_student_'.$v['user_id'].'" class="checkbox_filter qstudent_item qclass_item qclass_item_'.$value['class_id'].'" value="'.$v['user_id'].'" />
                                      <span class="checkmark" style="margin-left:5px;"></span>
                                    </label></td><td><a>' . $studentName . '</a></td></tr>';
                                    $grouped_student[] =  $v['user_id'];
                                } 
                                $gx++;
                            } 
                        }
                        $getAllStudent = $this->model_users->get_student_list($this->session->userdata('user_id'));
                        $ungroup_count = 1;
                        foreach ($getAllStudent AS $student) {
                            if(in_array($student->id, $grouped_student)) continue;
                            if($ungroup_count==1) {
                                echo '<tr class="tr_class"><td width="35">
                                <label class="container-filter"> &nbsp;
                                  <input checked type="checkbox" id="0" class="checkbox_filter qstudent_class qclass_id_0" value="qclass_0" />
                                  <span class="checkmark"></span>
                                </label></td><td class="qtd_class" data-group="0"><a>Ungrouped</a></td></tr>';
                            }
                            $studentName = $this->model_users->get_username_from_id($student->id);
                            echo '<tr class="tr_item qtr_item_0"><td>
                             <label class="container-filter"> &nbsp;
                              <input checked type="checkbox" id="cb_student_'.$student->id.'" class="checkbox_filter qstudent_item qclass_item qclass_item_'.$student->id.'" value="'.$student->id.'" />
                              <span class="checkmark" style="margin-left:5px;"></span>
                            </label></td><td><a>' . $studentName . '</a></td></tr>';
                            $ungroup_count++;
                        } ?>
                        </table>
                    </div>
                </div>
                <script type="text/javascript">
                    $('#check_uncheck2').click(function () {
                        var current_text = $(this).text();
                        var filter_by = $('#gen_student_filter2').val();
                        if(current_text=="Check All"){
                            $(this).text('Uncheck All');
                            $('.checkbox_filter').prop('checked', true);
                        } else {
                            $(this).text('Check All');
                            $('.checkbox_filter').prop('checked', false);
                        }
                        var sList = "";
                        var filterWs = $('#input_filter_student');
                        $('.qstudent_item').each(function () {
                            if(this.checked) {
                                sList += this.value+",";
                            }
                        });
                        filterWs.val(sList);
                    });
                    $('.setFilterStudent2').click(function () {
                        var id = $(this).data('id');
                        var status = $('#gen_student_filter2').val();
                        if (status == '1') {
                            active = '2';
                            $('#filter_by_class2').show();
                            $('.qstudent_class').prop('checked', true);
                            $('.qclass_item').prop('checked', true);
                            $('#filter_by_group2').hide();
                            $('.qstudent_group').prop('checked', false);
                            $('.qgroup_item').prop('checked', false);
                        } else {
                            active = '1';
                            $('#filter_by_class2').hide();
                            $('.qstudent_class').prop('checked', false);
                            $('.qclass_item').prop('checked', false);
                            $('#filter_by_group2').show();
                            $('.qstudent_class').prop('checked', true);
                            $('.qclass_item').prop('checked', true);
                        }
                        $('#check_uncheck2').text('Uncheck All');
                        $('#gen_student_filter2').data('status', active);
                        $('#gen_student_filter2').val(active);
                    });
                    $(".qtd_group").click(function(){
                        var group_id = $(this).attr('data-group');
                        // alert(group_id);
                        var item_group = $('.qtr_item_'+group_id);
                        item_group.toggle();
                    });
                    $(".qtd_class").click(function(){
                        var group_id = $(this).attr('data-group');
                        // alert(group_id);
                        var item_group = $('.qtr_item_'+group_id);
                        item_group.toggle();
                    });
                    $("#q_student_all").click(function(){
                        $('.qstudent_group').not(this).prop('checked', this.checked);
                        $('.qstudent_item').not(this).prop('checked', this.checked);
                        var filterWs = $('#input_filter_student');
                        filterWs.val('all');
                        $('#q_student_all').prop('checked', true);
                        $('.qstudent_group').not(this).prop('checked', this.checked);
                        $('.qstudent_item').not(this).prop('checked', this.checked);
                    });
                    $(".qstudent_item").click(function(){
                        var sList = "";
                        var filterWs = $('#input_filter_student');
                        $('.qstudent_item').each(function () {
                            if(this.checked) {
                                sList += this.value+",";
                            }
                        });
                        filterWs.val(sList);
                    });
                    $(".qstudent_group").click(function(){
                        $('#q_student_all').prop('checked', false);
                        var group_id = $(this).attr("id");
                        if($(".qgroup_id_"+group_id).prop('checked') == true) {
                            $('.qgroup_item_'+group_id).prop('checked', true);
                        } else {
                            $('.qgroup_item_'+group_id).prop('checked', false);
                        }
                        var sList = "";
                        var filterWs = $('#input_filter_student');
                        $('.qstudent_item').each(function () {
                            if(this.checked) {
                                sList += this.value+",";
                            }
                        });
                        filterWs.val(sList);
                    });
                    $(".qstudent_class").click(function(){
                        var group_id = $(this).attr("id");
                        if($(".qclass_id_"+group_id).prop('checked') == true) {
                            $('.qclass_item_'+group_id).prop('checked', true);
                        } else {
                            $('.qclass_item_'+group_id).prop('checked', false);
                        }
                        var sList = "";
                        var filterWs = $('#input_filter_student');
                        $('.student_item').each(function () {
                            if(this.checked) {
                                sList += this.value+",";
                            }
                        });
                        filterWs.val(sList);
                    });
                </script>
            </div>
        </div> 
        <div id="loading2" style="display: none;width: 100%;text-align: center;">
          <img src="<?php echo base_url(); ?>img/loading.gif" alt="Loading..." /><br />
          <em>Please wait...</em>
        </div>
        <div id="question-content"></div>
        <div class="card-columns" id="card_question">
         
        </div>
    </div>
</div>



            </div> 
        </div>          
    </div>
</div>
<script type="text/javascript">
    $("#question-filter").mouseenter(function() {
        /// $(this).show();
    }).mouseleave(function() {
        var filterShow = $('#table_report_show');
        var filterWsId = $('#input_filter_ws');
        var filterWsName = $('#ws_name');
        var filterQuestion = $('#input_filter_question');
        var filterSubject = $('#input_filter_subject');
        var filterStudent = $('#input_filter_student');
        var filterWorksheet = $('#input_filter_worksheet');
        var filterDateStart = $('#input_filter_date_start');
        var filterDateEnd = $('#input_filter_date_end');

        var filterShow2 = $('#table_report_show2');
        var filterWsId2 = $('#input_filter_ws2');
        var filterWsName2 = $('#ws_name2');
        var filterQuestion2 = $('#input_filter_question2');
        var filterSubject2 = $('#input_filter_subject2');
        var filterStudent2 = $('#input_filter_student2');
        var filterWorksheet2 = $('#input_filter_worksheet2');
        var filterDateStart2 = $('#input_filter_date_start2');
        var filterDateEnd2 = $('#input_filter_date_end2');

        if(filterShow.val()!=filterShow2.val() || filterWsId.val()!=filterWsId2.val() || filterWsName.val()!=filterWsName2.val() || filterQuestion.val()!=filterQuestion2.val() || filterSubject.val()!=filterSubject.val() || filterStudent.val()!=filterStudent2.val() || filterWorksheet.val()!=filterWorksheet2.val() || filterDateStart.val()!=filterDateStart2.val() || filterDateEnd.val()!=filterDateEnd2.val()) {

            $('#loading2').show();
            var qs_id = filterQuestion.val();
            document.getElementById('choose_question').innerHTML = "";
            document.getElementById('card_question').innerHTML = "";
            document.getElementById('filter_question').innerHTML = "";
            document.getElementById('filter_question').innerHTML = 'Q'+qs_id+' <i class="fa fa-sort-down"></i>';
            if ( $('#requestRunning').val()=='true') {
                return;
            }
            $('#requestRunning').val('true');
            $.ajax({
                url:'<?php echo base_url()?>profile/analytics_by_question',
                method: 'post',
                data: {
                    worksheet_id: filterWsId.val(),
                    filterStudent: filterStudent.val(),
                    worksheet_title: filterWsName.val(),
                    question_id: filterQuestion.val()
                },
                dataType: 'json',
                success: function(response){
                    var res = JSON.stringify(response);
                    var rs = JSON.parse(res);
                    if(rs.success==true) {
                        var html = rs.html;
                        $('#card_question').append(html);
                        var ques_options = rs.ques_options;
                        $('#choose_question').append(ques_options);
                        var ques_content = rs.ques_content;
                        $('#question-content').append(ques_content);
                    }
                    MathJax.Hub.Typeset();
                    $('#requestRunning').val('false');
                    $('#loading2').hide();
                }
            });
            filterShow2.val(filterShow.val());
            filterWsId2.val(filterWsId.val());
            filterWsName2.val(filterWsName.val());
            filterQuestion2.val(filterQuestion.val());
            filterSubject2.val(filterSubject.val());
            filterStudent2.val(filterStudent.val());
            filterWorksheet2.val(filterWorksheet.val());
            filterStudent2.val(filterStudent.val());
            filterDateStart2.val(filterDateStart.val());
            filterDateEnd2.val(filterDateEnd.val());
        }
    });
    // next_attempt
    $("body").on( "click", ".next_attempt", function( event ) {
        var attemptDetail = $(this).attr('data-id');
        var student_id = $(this).attr('data-student');
        var card_id = $('#card_'+attemptDetail);
        $('.card_'+student_id).attr("style", "display: none !important"); //.css('display','none !important');
        card_id.attr("style", "display: block !important");
    });
    // next_attempt
    $("body").on( "click", ".prev_attempt", function( event ) {
        var attemptDetail = $(this).attr('data-id');
        var student_id = $(this).attr('data-student');
        var card_id = $('#card_'+attemptDetail);
        $('.card_'+student_id).attr("style", "display: none !important"); //.css('display','none !important');
        card_id.attr("style", "display: block !important");
    });
    $("body").on( "click", ".showDigital", function( event ) {
        var attemptId = $(this).attr('data-attempt');
        var qnum = $(this).attr('data-qnum');
        var file_doc = '<?php echo base_url() ?>onlinequiz/showSVG/' + attemptId + '/' + qnum;
        // alert(attemptDetail);
        var show_working = $('#show_working_'+attemptId+'_'+qnum);
        show_working.empty();
        show_working.html('<img src="'+file_doc+'" onerror="this.style.display=\'none\'" />');
        $('#imgUpload_'+attemptId+'_'+qnum).removeClass('btn-active');
        $('#showDigital_'+attemptId+'_'+qnum).addClass('btn-active');
        $('#blankBg_'+attemptId+'_'+qnum).removeClass('btn-active');
        $('#videoEx_'+attemptId+'_'+qnum).removeClass('btn-active');
        $('#show_working_'+attemptId+'_'+qnum).attr('data-type', 'digital');
        $('#show_working_'+attemptId+'_'+qnum).css('background-color', '#FFFFFF');
    });
    $("body").on( "click", ".imgUpload", function( event ) {
        var attemptId = $(this).attr('data-attempt');
        var qnum = $(this).attr('data-qnum');
        var file_doc = '<?php echo base_url() ?>onlinequiz/showUploaded/' + attemptId + '/' + qnum;
        // alert(attemptDetail);
        var show_working = $('#show_working_'+attemptId+'_'+qnum);
        show_working.empty();
        show_working.html('<img src="'+file_doc+'" onerror="this.style.display=\'none\'" />');
        $('#imgUpload_'+attemptId+'_'+qnum).addClass('btn-active');
        $('#showDigital_'+attemptId+'_'+qnum).removeClass('btn-active');
        $('#blankBg_'+attemptId+'_'+qnum).removeClass('btn-active');
        $('#videoEx_'+attemptId+'_'+qnum).removeClass('btn-active');
        $('#show_working_'+attemptId+'_'+qnum).attr('data-type', 'uploaded');
        $('#show_working_'+attemptId+'_'+qnum).css('background-color', '#FFFFFF');
    });
    $("body").on( "click", ".blankBg", function( event ) {
        var attemptId = $(this).attr('data-attempt');
        var qnum = $(this).attr('data-qnum');
        var student = $(this).attr('data-student');
        var show_working = $('#show_working_'+attemptId+'_'+qnum);
        show_working.empty();
        $('#imgUpload_'+attemptId+'_'+qnum).removeClass('btn-active');
        $('#showDigital_'+attemptId+'_'+qnum).removeClass('btn-active');
        $('#blankBg_'+attemptId+'_'+qnum).addClass('btn-active');
        $('#videoEx_'+student+'_'+attemptId+'_'+qnum).removeClass('btn-active');
        $('#show_working_'+attemptId+'_'+qnum).attr('data-type', 'blank');
        $('#show_working_'+attemptId+'_'+qnum).css('background-color', '#FFFFFF');
    });
    $("body").on( "click", ".videoEx", function( event ) {
        var attemptId = $(this).attr('data-attempt');
        var qnum = $(this).attr('data-qnum');
        var student = $(this).attr('data-student');
        var file_doc = '<?php echo base_url() ?>img/play_video.png';
        var show_working = $('#show_working_'+attemptId+'_'+qnum);
        show_working.empty();
        show_working.html('<img src="'+file_doc+'" style="width:100px;min-height:70px !important;margin-top:60px;" />');
        $('#imgUpload_'+attemptId+'_'+qnum).removeClass('btn-active');
        $('#showDigital_'+attemptId+'_'+qnum).removeClass('btn-active');
        $('#blankBg_'+attemptId+'_'+qnum).removeClass('btn-active');
        $('#videoEx_'+student+'_'+attemptId+'_'+qnum).addClass('btn-active');
        $('#show_working_'+attemptId+'_'+qnum).attr('data-type', 'video');
        $('#show_working_'+attemptId+'_'+qnum).css('background-color', '#000000');
    });
    $("body").on( "click", ".uploadVideoExplanation", function( event ) {          
        var attemptId = $(this).attr('data-attempt');
        var questionNo = $(this).attr('data-qnum');
        var student = $(this).attr('data-student');
        var is_group = $(this).attr('data-group');

        var entryContent = `<div class="form-inline" id="entryEmbedVideo_`+student+'_'+attemptId+'_'+questionNo+`" style="display: inline;">                               
            <div class="form-group mb-2" style="width: 100%">
                <input type="text" class="form-control input_style1_black subsection" id="embed_video_`+student+'_'+attemptId+'_'+questionNo+`"
                    placeholder="Copy link youtube or vimeo here..." style="width: 80%">
                <a style="cursor: pointer; text-decoration: none;"
                    class="text-success-active fs26 ml-2 mt-1 saveEmbedVideo_`+student+'_'+attemptId+'_'+questionNo+`"><i
                        class="fa fa-check-circle-o"></i></a>
                <a style="cursor: pointer; text-decoration: none;"
                    class="text-danger-active fs26 ml-2 mt-1 cancelEmbedVideo_`+student+'_'+attemptId+'_'+questionNo+`"><i
                        class="fa fa-times-circle-o"></i></a>
                <span class="text-danger fs20 ml-3 msg-embed"></span>
            </div>  
        </div>
        `;

        is_group = (is_group=="yes") ? "group" : "individual";
        // alert(is_group+'_'+student+'_'+attemptId+'_'+questionNo);
        var selector = $('#videoEx_'+student+'_'+attemptId+'_'+questionNo);
        $(entryContent).insertAfter(selector);
        $('.saveEmbedVideo_'+student+'_'+attemptId+'_'+questionNo).click(function(){
            if ($('#embed_video_'+student+'_'+attemptId+'_'+questionNo).val() == '') {                    
                swal("Please, fill the field!", {icon: "warning",});
            } else {
                var embed_video = $('#embed_video_'+student+'_'+attemptId+'_'+questionNo).val();
                // alert(embed_video);
                if ( $('#requestRunning').val()=='true') {
                    return;
                }
                $('#requestRunning').val('true');
                $.ajax({
                    type: 'POST',
                    url: '<?php echo base_url()?>profile/saveVideoExplanation/',
                    data: {
                        attemptId: attemptId,
                        questionNo: questionNo,
                        uploaded_video: embed_video,
                        typeSave: is_group                    
                    },
                    dataType: 'json',
                    success: function (res) {
                        if (res.msg == 'success') { 
                            console.log(questionNo);                            
                            swal("Link has been saved!", {icon: "success",});
                            if(is_group=="group")
                                $('.play-video-group').attr('data-href', embed_video);
                            else {
                                $('#play_video_' +attemptId+'_'+questionNo).attr('data-href', embed_video);
                                $('#video_' +attemptId+'_'+questionNo).val(embed_video);
                            }
                            $('#entryEmbedVideo_' +student+'_'+attemptId+'_'+questionNo).remove();
                        } else {
                            // alert(res.type + ' ' + res.attemptId + ' ' + res.questionNo);
                        }
                        $('#requestRunning').val('false');
                    }
                });
            }
        })
        $('.cancelEmbedVideo_'+student+'_'+attemptId+'_'+questionNo).click(function(){
            $('#entryEmbedVideo_' +student+'_'+attemptId+'_'+questionNo).remove();
        })
    });
    $("body").on( "click", ".play-video", function( event ) {
        var attemptId = $(this).attr('data-attempt');
        var qnum = $(this).attr('data-qnum');
        var student = $(this).attr('data-student');
        var videoLink = $(this).attr('data-href');

        $('#model-quiz-result-ocr').hide();
        $('#action-nav').hide();
        $('#action-toolbar').hide();
        $('#model-quiz-video').show();
        var urlVideo = videoLink;
        var videoID = getVideoId(urlVideo);
        urlVideo = "//www.youtube.com/embed/" + videoID; 

        $("#model-quiz-video").attr('src', urlVideo);
        var videoScreen = $('#model-quiz-video');
        videoScreen.css('background-color', '#000000');
        videoScreen.css('background-image', '');
        videoScreen.css('width', '100%');
        videoScreen.css('height', '100%');
    });
    $("body").on( "click", ".blank-marking", function( event ) {
        var editorElement = document.getElementById('model-quiz-result-ocr');
        var videoElement = document.getElementById('model-quiz-video');
        var attemptId = $(this).attr('data-attempt');
        var qnum = $(this).attr('data-qnum');
        var type = $(this).attr('data-type');
        $('#working_attempt_id').val(attemptId);
        $('#working_qnum').val(qnum);

        if($('#show_ocr_check').val()=="") {
            activateMyScript();
        }
        $('#show_ocr_check').val("loaded");
        if(type=="blank-group" || type=="blank") {
            $('#model-quiz-result-ocr').show();
            $('#action-nav').show();
            $('#action-toolbar').show();
            $('#model-quiz-video').hide();
            $('.loader').css('display','none');
            var ocrValue = (type=="blank") ? "ocr_svg_tutor_bg" : "ocr_svg_tutor_bg_group";
            var OCRrecorded = $('#'+ocrValue+'_'+attemptId+'_'+qnum).val();
           //  alert(OCRrecorded);
            editorElement.editor.clear();
            $('#show_ocr').val(OCRrecorded);
            if(OCRrecorded!='') {
                var OCR_split = JSON.parse(OCRrecorded);
                var OCR_item = OCRrecorded; //JSON.stringify(OCRrecorded);
                var OCR_1 = OCR_item.split('"items":');
                var OCR_top = OCR_1[0] + '"items": [ ';
                var OCR_each = "";
                // alert(OCR_top);
                var obj = OCR_split.expressions;
                for(var key in obj) {
                    if (obj.hasOwnProperty(key)) {
                        // alert(obj[key].label);
                        var itemObj = obj[key].items;
                        // console.log(itemObj);
                        var allItems = ""; var allItem_clear = "";
                        for (index = 0; index < itemObj.length; ++index) {
                            OCR_each = OCR_top;
                            // console.log(itemObj[index]);
                            // alert(itemObj[index]);
                            var arrayItems = itemObj[index];
                            var strItems = JSON.stringify(arrayItems);
                            allItems = allItems + strItems;
                            OCR_each = OCR_each + allItems;
                            var OCR_bottom = ' ],"class": "math","style": "color: #000000; -myscript-pen-width: 2"} ],"id": "MainBlock", "version": "2" }';
                            OCR_each = OCR_each + OCR_bottom;
                            editorElement.editor.import_(OCR_each,"application/vnd.myscript.jiix");
                            if(index!=itemObj.length - 1) {
                                allItems = allItems + ",";
                            }
                            //console.log(OCR_each);
                            OCR_each = "";
                        }
                    }
                }
            } else {
                editorElement.editor.import_(OCRrecorded,"application/vnd.myscript.jiix");
            }
            resizeEditor();
            editorElement.addEventListener('loaded', function(){
                resizeEditor();
            });
            editorElement.style.backgroundImage = "url('<?php echo base_url() ?>img/img/bg_grey.png')";
            editorElement.style.backgroundRepeat = "repeat";
            editorElement.style.backgroundSize = "unset";
            
            var videoScreen = $('#model-quiz-result-ocr');
            videoScreen.css('background-color', '#FFFFFF');
            var save_to = (type=="blank") ? "svg_tutor_bg" : "svg_tutor_bg_group";
            $('#save_to').val(save_to);
        }
    });
</script>
<style type="text/css">
    #model-quiz-result-ocr {
        height: 270px;
        width: 100%;
        float: left;
    }
      /* Pen color */
    .pen-default {
        color: #FFFFFF;
        font-size: 10px;
    }
    .pen-default::before {
        content: "";
        display: inline-block;
        width: 10px;
        height: 10px;
        background-color: #FFFFFF;
        border-radius: 50%;
        -moz-border-radius: 50%;
        -webkit-border-radius: 50%;
    }
    .pen-medium {
        color: #FFFFFF;
        font-size: 14px;
    }
    .pen-medium::before {
        content: "";
        display: inline-block;
        width: 15px;
        height: 15px;
        background-color: #FFFFFF;
        border-radius: 50%;
        -moz-border-radius: 50%;
        -webkit-border-radius: 50%;
        margin-top: 3px;
    }
    .pen-bold {
        color: #FFFFFF;
        font-size: 18px;
    }
    .pen-bold::before {
        content: "";
        display: inline-block;
        width: 20px;
        height: 20px;
        background-color: #FFFFFF;
        border-radius: 50%;
        -moz-border-radius: 50%;
        -webkit-border-radius: 50%;
        margin-top: 6px;
    }
    #colorPick * {
        -webkit-transition: all linear .2s;
        -moz-transition: all linear .2s;
        -ms-transition: all linear .2s;
        -o-transition: all linear .2s;
        transition: all linear .2s;
    }

    #colorPick {
        background: rgba(255, 255, 255, 0.85);
        -webkit-backdrop-filter: blur(15px);
        position: absolute;
        border-radius: 5px;
        box-shadow: 0px 3px 8px rgba(0, 0, 0, 0.2);
        padding: 5px;
        width: 60px;
        z-index: 10000000;
    }

    #colorPick span {
        font-size: 9pt;
        text-transform: uppercase;
        font-weight: bold;
        color: #bbb;
        margin-bottom: 5px;
        display: block;
        clear: both;
    }

    .colorPickButton {
        border-radius: 50%;
        width: 40px;
        height: 40px;
        margin: 1px 4px;
        cursor: pointer;
        display: inline-block;
    }

    .colorPickButton:hover {
        transform: scale(1.1);
    }

    .colorPickDummy {
        background: #fff;
        border: 1px dashed #bbb;
    }
    .colorPickSelector {
        cursor: pointer;
        display: inline-block;
        overflow: hidden;
        position: relative;
        margin: auto 12px auto auto;
        padding: 0;
        line-height: normal;
        border-radius: 50%;
        -webkit-box-shadow: 0 2px 5px 0 rgba(0, 0, 0, 0.225);
        box-shadow: 0 2px 5px 0 rgba(0, 0, 0, 0.225);
        -webkit-transition: all linear .2s;
        -moz-transition: all linear .2s;
        -ms-transition: all linear .2s;
        -o-transition: all linear .2s;
        transition: all linear .2s;
    }

    .colorPickSelector:hover { transform: scale(1.1); }

    #thicknessPick * {
        -webkit-transition: all linear .2s;
        -moz-transition: all linear .2s;
        -ms-transition: all linear .2s;
        -o-transition: all linear .2s;
        transition: all linear .2s;
    }

    #thicknessPick {
        background: rgba(255, 255, 255, 0.85);
        -webkit-backdrop-filter: blur(15px);
        position: absolute;
        border-radius: 5px;
        box-shadow: 0px 3px 8px rgba(0, 0, 0, 0.2);
        padding: 5px;
        width: 60px;
        z-index: 10000000;
        left: 415px;
        margin-top: 15px;
    }

    #thicknessPick button {
        border-radius: 50%;
        width: 40px;
        height: 40px;
        margin: 4px;
        cursor: pointer;
        display: inline-block;
    }

    #thicknessPick button:hover {
        transform: scale(1.1);
    }
</style>
<div class="modal fade" id="showWorkings" role="dialog" tabindex="-1">
    <div class="modal-dialog modal-width80" role="document">
        <div class="modal-content">
            <div class="modal-header modal-header-custom-success">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Workings</h4>
            </div>
            <div class="modal-body" style="height: 400px;">
                <div id="modal-toolbar">
                    <input type="hidden" id="show_ocr" value="" /> 
                    <input type="hidden" id="show_ocr_check" value="" /> 
                    <input type="hidden" id="save_to" value="" /> 
                    <input type="hidden" id="svg_tutor_img" value="" /> 
                    <input type="hidden" id="svg_tutor" /> 
                    <input type="hidden" id="svg_tutor_bg" />
                    <input type="hidden" id="svg_tutor_bg_group" />
                    <input type="hidden" id="ocr_result" /> 
                    <input type="hidden" id="ocr_svg_tutor" value="" /> 
                    <input type="hidden" id="ocr_svg_tutor_img" value="" />  
                    <input type="hidden" id="ocr_svg_tutor_bg" value="" /> 
                    <input type="hidden" id="ocr_svg_tutor_bg_group" value="" />
                    <input type="hidden" id="style_color" value="#000000" /> 
                    <input type="hidden" id="style_thickness" value="2" /> 
                    <input type="hidden" id="jiix_export" value="" /> 
                    <input type="hidden" id="style_export" value="" />
                    <input type="hidden" id="working_attempt_id" value="" />
                    <input type="hidden" id="working_qnum" value="" />
                </div>
                <div style="border: 1px solid #D7DDE3;width:100%;" id="action-nav">
                    <nav class="nav-editor" style="width:fit-content;width:-moz-fit-content;border:0;">
                        <div class="button-div">
                            <button id="clear" class="nav-btn btn-fab-mini btn-lightBlue" disabled>
                                <img src="<?php echo base_url() ?>img/img/clear.svg">
                            </button>
                            <button id="undo" class="nav-btn btn-fab-mini btn-lightBlue" disabled>
                                <img src="<?php echo base_url() ?>img/img/undo.svg">
                            </button>
                            <button id="redo" class="nav-btn btn-fab-mini btn-lightBlue" disabled>
                                <img src="<?php echo base_url() ?>img/img/redo.svg">
                            </button>
                            <button id="convert" style="display:none;" class="nav-btn btn-fab-mini btn-lightBlue" disabled>
                                <img src="<?php echo base_url() ?>img/img/exchange-arrows.svg"></button>
                            <button id="myscriptAdd" class="nav-btn btn-fab-mini btn-lightBlue">+</button>
                            <button id="myscriptMinus" class="nav-btn btn-fab-mini btn-lightBlue">-</button>
                        </div>
                        <div class="button-div">
                            <button id="colorPickSelector" class="nav-btn btn-fab-mini">
                                <img src="<?php echo base_url()  ?>img/img/edit.svg">
                            </button>
                            <button id="penPickSelector" class="nav-btn btn-fab-mini btn-lightBlue pen-default">
                            </button>
                            <div id="thicknessPick" style="display:none;">
                            <button id="defPen" class="nav-btn btn-fab-mini btn-lightBlue pen-default">
                            </button>
                            <button id="medPen" class="nav-btn btn-fab-mini btn-lightBlue pen-medium">
                            </button>
                            <button id="boldPen" class="nav-btn btn-fab-mini btn-lightBlue pen-bold">
                            </button>
                        </div>
                    </nav>
                </div>
                <div id="model-quiz-result-ocr" class="model-quiz-working" style="border: 1px solid #D7DDE3;"></div>
                <div id="action-toolbar"></div>
                <iframe id="model-quiz-video" class="play_video" width="560" height="315" src="" frameborder="0" allowfullscreen allowscriptaccess="always"></iframe>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
$(document).on('show.bs.modal', '#showWorkings', function (e) {
    $("#ocr_result").val('');
    // $(".play_video").attr('src', '');
});
$('#showWorkings').on('hidden.bs.modal', function () {
    $(".play_video").attr('src', '');
    var editorElement = document.getElementById('model-quiz-result-ocr');
    var save_to = $('#save_to').val();
    save_to = (save_to=="") ? "svg_tutor" : save_to;
    editorElement.editor.export_();
    $('#'+save_to).val(editorElement.innerHTML);
    var attemptId = $("#working_attempt_id").val();
    var questionNo = $("#working_qnum").val();
    // var id = $(this).attr('data-id').split('_')[2];
    var svg_tutor = $('#'+save_to).val();
    // var svg_tutor_bg = $('#svg_tutor_bg').val();
    // toastr.success("Save id:" + $('#svg_tutor').val());
    var ocr_result = document.getElementById('ocr_result');
    if(ocr_result.value!="") {
        var ocr_target = document.getElementById('ocr_' + save_to);
        ocr_target.value = ocr_result.value;
        // questionData[id]["ocr_"+save_to] = ocr_result.value;
        var ocr_style = document.getElementById('style_export').value;
        var styles = '{"styles":[';
        ocr_style = ocr_style.substr(0, ocr_style.length - 1);
        styles = styles + ocr_style + ']';
        // Ajax for calling php function
        var ajax_url = base_url + 'onlinequiz/saveWork';
        if ( $('#requestRunning').val()=='true') {
            return;
        }
        $('#requestRunning').val('true');
        $.ajax({
            url: ajax_url,
            method: "post",
            dataType: 'json',
            data: {
                "ocr_tutor": ocr_result.value,
                "svg_tutor": svg_tutor,
                "save_to": save_to,
                "attemptId": attemptId,
                "questionNo": questionNo,
                "ocr_style": styles,
            },
            success: function (data) {
                if(save_to=="svg_tutor_bg_group") {
                    // if($('#'+save_to+'_'+attemptId+'_'+questionNo).val() != ocr_result.value){
                    //     toastr.success("Working is successfully saved");
                    // }
                    // $('.group_ocr_blank').val('asd'); //ocr_result.value);
                    var x = document.getElementsByClassName('group_ocr_blank');
                    for(i = 0; i < x.length; i++) {
                      x[i].value = ocr_result.value;
                    }
                } else {
                    // if($('#'+save_to+'_'+attemptId+'_'+questionNo).val() != ocr_result.value){
                    //     toastr.success("Working is successfully saved");
                    // }
                    $('#'+save_to+'_'+attemptId+'_'+questionNo).val(ocr_result.value);
                }
                $('#requestRunning').val('false');
                toastr.success("Working is successfully saved");               
            },
        });
    }
});
function activateMyScript(){
    var editorElement = document.getElementById('model-quiz-result-ocr');
    var undoElement = document.getElementById('undo');
    var redoElement = document.getElementById('redo');
    var clearElement = document.getElementById('clear');
    var convertElement = document.getElementById('convert');
    var defPenElement = document.getElementById('defPen');
    var medPenElement = document.getElementById('medPen');
    var boldPenElement = document.getElementById('boldPen');
    editorElement.addEventListener('changed', function (event) {
        undoElement.disabled = !event.detail.canUndo;
        redoElement.disabled = !event.detail.canRedo;
        clearElement.disabled = event.detail.isEmpty;
        convertElement.disabled = !event.detail.canConvert;
    });

    undoElement.addEventListener('click', function () {
        editorElement.editor.undo();
    });

    redoElement.addEventListener('click', function () {
        editorElement.editor.redo();
    });

    clearElement.addEventListener('click', function () {
        editorElement.editor.clear();
    });

    convertElement.addEventListener('click', function () {
        editorElement.editor.convert(); 
    });
    /**
    * Attach an editor to the document
    * @param {Element} The DOM element to attach the ink paper
    * @param {Object} The recognition parameters
    */
    var themes = [{
        name: 'Normal white theme',
        id: 'normal-white',
        theme: {
          ink: {
            color: '#000000',
            '-myscript-pen-width': 2
          },
          '.text': {
            'font-size': 12
          }
        }
    }];
    var defaultTheme = 'normal-white';
    function getTheme(themes, id) {
        return themes.filter(function (theme) {
            return theme.id === id;
        })[0].theme;
    }

    var editor = MyScript.register(editorElement, {
        recognitionParams: {
            type: 'MATH',
            protocol: 'WEBSOCKET',
            apiVersion: 'V4',
            server: {
                scheme: 'https',
                host: 'webdemoapi.myscript.com',
                applicationKey: 'b42908eb-d0e6-4d5f-ae43-592650f79ed1',
                hmacKey: '7b7a58f1-59b2-406c-8270-739cd32a9870'
            },
            v4: {
                alwaysConnected: false,
                math: {
                    mimeTypes: ['application/x-latex', 'application/vnd.myscript.jiix']
                },
                export: {
                    jiix: {
                        strokes: true,
                        style: true
                    }
                }
            }
        }
    },
    undefined, getTheme(themes, defaultTheme));
    
    function getStyle(penColor="#000000",size=2) {
        return {
            color: penColor,
            '-myscript-pen-width': size
        }
    }
    var currentColor = '#000000';
    var currentSize = 2;

    $('#colorPickSelector').colorPick({
        'onColorSelected': function() {
            this.element.css({'backgroundColor': this.color, 'color': this.color});
            editor.penStyle = getStyle(this.color, currentSize);
            currentColor = this.color;
            document.getElementById('style_color').value = currentColor;
        }
    });

    $('#penPickSelector').on('click', function(){
        var penSelector = document.getElementById('thicknessPick');
        penSelector.style.display = "block";
        $("#colorPick").remove();
    });

    // Color PEN Size event click
    defPenElement.addEventListener('click', function () {
        var size = 2;
        document.getElementById('style_thickness').value = size.toString();
        editor.penStyle = getStyle(currentColor,size);
        currentSize = size;
        var penSelector = document.getElementById('thicknessPick');
        penSelector.style.display = "none";
        document.getElementById("penPickSelector").className = "nav-btn btn-fab-mini btn-lightBlue pen-default";
    });
    medPenElement.addEventListener('click', function () {
        var size = 4;
        document.getElementById('style_thickness').value = size.toString();
        editor.penStyle = getStyle(currentColor,size);
        currentSize = size;
        var penSelector = document.getElementById('thicknessPick');
        penSelector.style.display = "none";
        document.getElementById("penPickSelector").className = "nav-btn btn-fab-mini btn-lightBlue pen-medium";
    });
    boldPenElement.addEventListener('click', function () {
        var size = 6;
        document.getElementById('style_thickness').value = size.toString();
        editor.penStyle = getStyle(currentColor,size);
        currentSize = size;
        var penSelector = document.getElementById('thicknessPick');
        penSelector.style.display = "none";
        document.getElementById("penPickSelector").className = "nav-btn btn-fab-mini btn-lightBlue pen-bold";
    });
    let toImport = null;
    editorElement.addEventListener('exported', function (evt) {
        const exports = event.detail.exports;
        if(exports && exports['application/vnd.myscript.jiix']) {
            toImport = exports['application/vnd.myscript.jiix'];
            $('#ocr_result').val(toImport);
        }
        // alert('Exported');
    });

    window.addEventListener('resize', function () {
        editorElement.editor.resize();
    });
}
function resizeEditor(){
    var editorElement = document.getElementById('model-quiz-result-ocr');
    window.setTimeout(function(){
        editorElement.editor.resize();
    },500);
}
$('#myscriptAdd').on('click', function(){
    var editorHeight = $('#model-quiz-result-ocr').height();
    editorHeight+= 300;
    $('#model-quiz-result-ocr').height(editorHeight);
    resizeEditor();
});
$('#myscriptMinus').on('click', function(){
    var editorHeight = $('#model-quiz-result-ocr').height();
    editorHeight -= 300;
    $('#model-quiz-result-ocr').height(editorHeight);
    resizeEditor();
});

$("body").on( "click", ".show_working", function( event ) {
    var editorElement = document.getElementById('model-quiz-result-ocr');
    var videoElement = document.getElementById('model-quiz-video');
    var attemptId = $(this).attr('data-attempt');
    var qnum = $(this).attr('data-qnum');
    var type = $(this).attr('data-type');
    $('#working_attempt_id').val(attemptId);
    $('#working_qnum').val(qnum);

    if($('#show_ocr_check').val()=="") {
        activateMyScript();
    }
    $('#show_ocr_check').val("loaded");
    if(type=="digital" || type=="uploaded" || type=="blank") {
        $('#model-quiz-result-ocr').show();
        $('#action-nav').show();
        $('#action-toolbar').show();
        $('#model-quiz-video').hide();
        $('.loader').css('display','none');
        var ocrValue = (type=="digital") ? "ocr_svg_tutor" : "ocr_svg_tutor_img";
        ocrValue = (type=="blank") ? "ocr_svg_tutor_bg" : ocrValue;
        var OCRrecorded = $('#'+ocrValue+'_'+attemptId+'_'+qnum).val();
       //  alert(OCRrecorded);
        editorElement.editor.clear();
        $('#show_ocr').val(OCRrecorded);
        if(OCRrecorded!='') {
            var OCR_split = JSON.parse(OCRrecorded);
            var OCR_item = OCRrecorded; //JSON.stringify(OCRrecorded);
            var OCR_1 = OCR_item.split('"items":');
            var OCR_top = OCR_1[0] + '"items": [ ';
            var OCR_each = "";
            // alert(OCR_top);
            var obj = OCR_split.expressions;
            for(var key in obj) {
                if (obj.hasOwnProperty(key)) {
                    // alert(obj[key].label);
                    var itemObj = obj[key].items;
                    // console.log(itemObj);
                    var allItems = ""; var allItem_clear = "";
                    for (index = 0; index < itemObj.length; ++index) {
                        OCR_each = OCR_top;
                        // console.log(itemObj[index]);
                        // alert(itemObj[index]);
                        var arrayItems = itemObj[index];
                        var strItems = JSON.stringify(arrayItems);
                        allItems = allItems + strItems;
                        OCR_each = OCR_each + allItems;
                        var OCR_bottom = ' ],"class": "math","style": "color: #000000; -myscript-pen-width: 2"} ],"id": "MainBlock", "version": "2" }';
                        OCR_each = OCR_each + OCR_bottom;
                        editorElement.editor.import_(OCR_each,"application/vnd.myscript.jiix");
                        if(index!=itemObj.length - 1) {
                            allItems = allItems + ",";
                        }
                        //console.log(OCR_each);
                        OCR_each = "";
                    }
                }
            }
        } else {
            editorElement.editor.import_(OCRrecorded,"application/vnd.myscript.jiix");
        }
        resizeEditor();
        editorElement.addEventListener('loaded', function(){
            resizeEditor();
        });
        if(type!="blank") {
            var actionType = (type=="digital") ? "showSVG" : "showUploaded";
            var bg_uploaded = '<?php echo base_url() ?>onlinequiz/'+actionType+'/' + attemptId + '/' + qnum;
            editorElement.style.backgroundImage = "url(' "+ bg_uploaded + " ')";
            editorElement.style.backgroundRepeat = "no-repeat";
            editorElement.style.backgroundSize = "contain";
            editorElement.style.backgroundPosition = "center";
        } else {
            editorElement.style.backgroundImage = "url('<?php echo base_url() ?>img/img/bg_grey.png')";
            editorElement.style.backgroundRepeat = "repeat";
            editorElement.style.backgroundSize = "unset";
        }
        var videoScreen = $('#model-quiz-result-ocr');
        videoScreen.css('background-color', '#FFFFFF');
        var save_to = (type=="digital") ? "svg_tutor" : "svg_tutor_img";
        save_to = (type=="blank") ? "svg_tutor_bg" : save_to;
        $('#save_to').val(save_to);
    } else if(type=="video") {
        $('#model-quiz-result-ocr').hide();
        $('#action-nav').hide();
        $('#action-toolbar').hide();
        $('#model-quiz-video').show();
        var urlVideo = $('#video_'+attemptId+'_'+qnum).val();
        var videoID = getVideoId(urlVideo);
        urlVideo = "//www.youtube.com/embed/" + videoID; 

        $("#model-quiz-video").attr('src', urlVideo);
        var videoScreen = $('#model-quiz-video');
        videoScreen.css('background-color', '#000000');
        videoScreen.css('background-image', '');
        videoScreen.css('width', '100%');
        videoScreen.css('height', '100%');
    }
});
function getVideoId(url) {
    const regExp = /^.*(youtu.be\/|v\/|u\/\w\/|embed\/|watch\?v=|&v=)([^#&?]*).*/;
    const match = url.match(regExp);

    return (match && match[2].length === 11)
      ? match[2]
      : null;
}
</script>
<?php } /*?>

*/ ?>
<div class="modal fade" id="solutionAnswerModal" role="dialog">

    <div class="modal-dialog" role="document">

        <div class="modal-content">

            <div class="modal-header modal-header-custom-success">

                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>

                <h4 class="modal-title" id="worksheetModalLabel">Solution</h4>

            </div>

                <div class="modal-body" id="show_solution">

                </div>

                <div class="modal-footer">

                    <input type="button" class="btn btn-default" data-dismiss="modal" value="Close">

                </div>

        </div>

    </div>

</div>
<script type="text/javascript">
    // $('.solution-answer').on('click', function () {
    $("body").on( "click", ".solution-answer", function( event ) {
        $('#show_solution').html($("#solution_value").val());
    });

    $('#solutionAnswerModal').on('show.bs.modal', function () { 
        MathJax.Hub.Typeset();
    });
</script>