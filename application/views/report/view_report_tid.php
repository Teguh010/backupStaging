<style type="text/css"> 
    .performance_nav_tabs li {
        width: max-content;
        border-bottom: 3px solid transparent;
    }
    .performance_nav_tabs li.active {
        border-bottom: 3px solid #2ABB9B;
    }
    .performance_nav_tabs li a {
        border: 0 !important;
        height: 40px;
    }
    @media (max-width: 415px) {
        .performance_nav_tabs li {
            width: 100%;
        }
        .performance_nav_tabs li a {
            height: 40px;
        }
    }   
    @media (min-width: 416px) and (max-width: 700px) {
        .performance_nav_tabs li {
            width: 50%;
        }
        .performance_nav_tabs li a {
            height: 40px;
        }
    } 
    .chart_wrapper {
        height: 450px !important;
    }
</style>
<?php

/**

 * *

 *  * Created by PhpStorm.

 *  * User: boonkhailim

 *  * Year: 2017

 *

 */



if (isset($report_error) && $report_error) {

        echo '<div class="section">';

        echo '<div class="container">';

        echo '<div class="alert alert-danger">';

        echo $error_message;

        echo '</div>';

        echo '</div>';

        echo '</div>';



    } else {

?>

        <div class="section">

            <div class="container">

                        <h1>Performance Report for <?php echo $userFullName?></h1>

                        <div class="row">

                            <div class="col-sm-4 col-md-5 col-lg-6" style="padding-top:20px;">

                                <!-- <img src="<?php echo base_url(); ?>img/profile/<?php echo $profilePic;?>" class="img-responsive center-block profile-pic"> -->

                                <?php if($this->uri->segment(4) != 6) {?>
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="padding-top:2em;">
                                        <form class="form-horizontal" action="<?php echo base_url() ?>report/user_tid/<?php echo $this->uri->segment(3) ?>" method="post" enctype="multipart/form-data" id="">
                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <div id="view_daterange" class="daterange text-center" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%">
                                                    <i class="fa fa-calendar"></i>&nbsp;
                                                    <span></span> <i class="fa fa-caret-down"></i>
                                                </div>
                                            </div>

                                            <!-- <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                                <div id="view_daterange2" class="daterange" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%">
                                                    <i class="fa fa-calendar"></i>&nbsp;
                                                    <span></span> <i class="fa fa-caret-down"></i>
                                                </div>
                                            </div>   -->

                                            <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12" style="padding-top:2em;">
                                                <select name="level_id" id="level_id" class="report_lvl_option" style="width: 100%; text-align-last:center; text-align: center;"> 
                                                    <?php
                                                        echo '<option value="'. 'All' .'" selected="selected">' .'All Level'. '</option>';

                                                        foreach ($levels as $level) {
                                                            $selected = ($this->session->userdata('level_id')==$level->level_code ) ? "selected" : '';
                                                            echo '<option value="' . $level->level_code . '" '.$selected.' >' . $level->level_name . '</option>';
                                                        }
                                                        

                                                    ?>
                                                </select>
                                            </div>

                                            <input type="hidden" name="dr_start" />
                                            <input type="hidden" name="dr_end" />

                                            <!-- <input type="hidden" name="dr2_start" />
                                            <input type="hidden" name="dr2_end" /> -->

                                            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12" style="padding-top:2em;">
                                                <input type="submit" class="btn btn-no-margin btn-default btn-block" value="Filter">
                                            </div>

                                            <!-- <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="padding-top:2em;">
                                                <div style="border:2px solid black;"></div>
                                            </div> -->

                                        </form>

                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="padding-top:2em;">
                                            <a id="btnBasicReport" data-href="<?php echo base_url()."report/basic_report_pdf/" . $this->uri->segment(3) . "/".$this->uri->segment(4);?>" class="btn btn-no-margin btn-block btn-default toggle_category_table text-center">My Report</a>
                                        </div>
                                    </div>

                                    
                                    
                                <?php } ?>

                            </div>

                            <div class="col-sm-8 col-md-7 col-lg-6">
                                <div class="chart_wrapper">
                                    <canvas class="chart_canvas" id="student_performance_strand" width="600" height="450"></canvas>
                                </div>
                                <?php

                                $chart_performance_data = array();

                                $chart_strand_label = array();

                                foreach ($topics_tid as $topic) {

                                    $chart_strand_label[] = $topic['topic_short_name'];

                                    $chart_performance_data[] = $performance[$topic['topic_name']]['percentage'];

                                }

                                ?>

                                <script type="text/javascript">

                                    var radarOptions = {

                                        responsive: true,

                                        legend: {

                                            labels: {

                                                fontSize: 15

                                            }

                                        },

                                        scale: {

                                            ticks: {

                                                beginAtZero: true,

                                                suggestedMin: 0,

                                                suggestedMax: 100,

                                                fontSize: 14,

                                                minTicksLimit: 5,

                                                maxTicksLimit: 10

                                            },

                                            pointLabels: {

                                                fontSize: 18,

                                                lineHeight: 2

                                            }

                                        }

                                    }



                                    var data = {

                                        labels: [<?php echo '"' . implode('", "', $chart_strand_label) . '"' ?>],

                                        datasets: [

                                            {

                                                label: "Topic Performance (%)",

                                                backgroundColor: "rgba(255,197,90,0.2)",

                                                borderColor: "rgba(255,197,90,0.8)",

                                                pointBackgroundColor: "rgba(255,197,90,0.8)",

                                                pointBorderColor: "rgba(255,197,90,0.5)",

                                                pointHoverBackgroundColor: "#fff",

                                                pointHoverBorderColor: "rgba(255,197,90,1)",

                                                data: [<?php echo implode(',', $chart_performance_data); ?>],

                                                borderWidth: "2"
                                            }

                                        ]

                                    };



                                    var ctx = $('#student_performance_strand');

                                    var myRadarChart = new Chart(ctx, {

                                        type: 'radar',

                                        data: data,

                                        options: radarOptions

                                    });

                                </script>

                            </div>

                        </div>





            </div>

        </div>



        <div class="section-askjen">

            <div class="container">

                <div class="row">

                    <div class="col-lg-12 tab-content">
                        <table class="table table-hover table-bordered" id="">

                            <thead>

                            <tr class="warning">

                                <th>Topics in <?php echo $level_name; ?></th>

                                <th>Score</th>

                                <th>Action</th>

                            </tr>

                            </thead>

                            <tbody>

                            <?php

                            foreach ($topics_tid as $category) {

                                $category_num_attempt = $performance[$category['topic_name']]['total_attempt'];

                                $category_num_correct = $performance[$category['topic_name']]['total_correct'];

                                if ($category_num_attempt != 0) {

                                    $category_perc = $performance[$category['topic_name']]['percentage'];

                                    $category_progress_bar = $performance[$category['topic_name']]['progress_bar_type'];

                                }

                                ?>

                                <tr>

                                    <td><?php echo  $category['topic_name']." (".$category['topic_short_name'].")"; ?></td>

                                    <td>

                                        <?php

                                        if ($category_num_attempt == 0) {

                                            echo 'No stats available';

                                        } else {

                                            $tooltip = $category_num_correct . ' mark(s) / ' . $category_num_attempt . ' total';

                                            ?>

                                            <div class="progress" data-toggle="tooltip" title="<?php echo $tooltip;?>">

                                                <div class="progress-bar <?php echo $category_progress_bar;?>" role="progressbar" aria-valuenow="40"

                                                     aria-valuemin="0" aria-valuemax="100" style="width:<?php echo $category_perc;?>%">

                                                    <?php echo $category_perc;?>%

                                                </div>

                                            </div>

                                            <?php

                                        }

                                        ?>

                                    </td>

                                    <td style="white-space: nowrap;">

                                    
                                        <button class="btn btn-custom btn-no-margin-top smartgen_btn" id="student_id_<?php echo $category['topic_id'] ?>" title="Adaptive Generation" disabled><i class="fa fa-android"></i></button>
                                        
                                        <button class="btn btn-warning btn-no-margin-top smartgen_video_btn" id="student_id_<?php echo $category['topic_id'] ?>" title="Virtual Classroom" disabled><i class="fa fa-video-camera"></i></button>
                                        
                                        <button class="btn btn-danger btn-no-margin-top smartgen_booking_btn" id="student_id_<?php echo $category['topic_id'] ?>" title="Book for a Class" disabled><i class="fa fa-calendar"></i></button>
                                    

                                        <!-- <button class="btn btn-custom smartgen_btn">Action</button> -->

                                    

                                    </td>

                                </tr>





                                <?php

                            }

                            ?>

                            </tbody>

                        </table>




                        <?php /*
                        <ul class="nav nav-tabs performance_nav_tabs">

                            <?php

                            $strand_idx = 0;

                            foreach ($analysis_structure as $strand) {

                                if(strlen($strand['name'])> 18) {
                                    $substr = substr($strand['name'], 0, 18);
                                    $strand_name = $substr . '....';
                                    $tab_strand_name = str_replace(' ', '_', $strand['name']);
                                } else {
                                    $strand_name = $strand['name'];
                                    $tab_strand_name = str_replace(' ', '_', $strand['name']);
                                }

                                ?>

                                <li class="<?php echo ($strand_idx==0)?'active':''; ?>" ><a href="#strand_tab_<?php echo $tab_strand_name; ?>" id="strand_tab_link_<?php echo $tab_strand_name; ?>" data-toggle="tab"><?php echo $strand_name?></a></li>

                                <?php

                                $strand_idx++;

                            }

                            ?>

                        </ul> */ ?>

                        <?php /*

                        $strand_idx = 0;

                        foreach ($analysis_structure as $strand) {

                            if(strlen($strand['name'])> 18) {
                                $substr = substr($strand['name'], 0, 18);
                                $strand_name = $substr . '....';
                                $tab_strand_name = str_replace(' ', '_', $strand['name']);
                            } else {
                                $strand_name = $strand['name'];
                                $tab_strand_name = str_replace(' ', '_', $strand['name']);
                            }

                            ?>

                            <div class="tab-pane fade <?php echo ($strand_idx == 0)?'in active':'' ;?>" role="tab-panel" id="strand_tab_<?php echo $tab_strand_name; ?>">

                                <!-- <div class="page-header">

                                                          <h2>Strand -  <?php echo $strand['name']?></h2>

                                                        </div> -->

                                <?php

                                $strand_idx++;

                                foreach ($strand['substrand'] as $substrand) {

                                    $substrand_href = str_replace(array(' ', '&'), '_', $substrand['name']) . "_table";

                                    $perc = $performance[$strand['name']][$substrand['name']]['percentage'];

                                    $color = $performance[$strand['name']][$substrand['name']]['progress_bar_type'];

                                    switch ($color) {

                                        case 'progress-bar-danger':

                                            $color = '#d9534f';

                                            break;

                                        case 'progress-bar-warning':

                                            $color = '#f0ad4e';

                                            break;

                                        case 'progress-bar-success':

                                            $color = '#2ABB9B';

                                            break;

                                    }



                                    ?>

                                    <div class="col-xs-6 col-sm-4 col-md-4 col-lg-4 text-center substrand_performance">

                                        <div class="progress_circles" data-percent="<?php echo $perc?>" data-color="<?php echo $color?>"></div>

                                        <a href="#<?php echo $substrand_href; ?>" class="btn btn-no-margin btn-block btn-custom toggle_category_table"><?php echo $substrand['name']?></a>

                                    </div>



                                    <?php

                                }



                                foreach ($strand['substrand'] as $substrand) {

                                    $substrand_href = str_replace(array(' ', '&'), '_', $substrand['name']) . "_table";

                                    ?>

                                    <table class="table table-hover performance_table table-bordered topics_score_table" id="<?php echo $substrand_href; ?>">

                                        <thead>

                                        <tr class="warning">

                                            <th>Topics in <?php echo $substrand['name']; ?></th>

                                            <th>Score</th>

                                            <th>Action</th>

                                        </tr>

                                        </thead>

                                        <tbody>

                                        <?php

                                        foreach ($substrand['category'] as $category) {

                                            $category_num_attempt = $performance[$strand['name']][$substrand['name']][$category['name']]['total_attempt'];

                                            $category_num_correct = $performance[$strand['name']][$substrand['name']][$category['name']]['total_correct'];

                                            if ($category_num_attempt != 0) {

                                                $category_perc = $performance[$strand['name']][$substrand['name']][$category['name']]['percentage'];

                                                $category_progress_bar = $performance[$strand['name']][$substrand['name']][$category['name']]['progress_bar_type'];

                                            }

                                            ?>

                                            <tr>

                                                <td><?php echo  $category['name']; ?></td>

                                                <td>

                                                    <?php

                                                    if ($category_num_attempt == 0) {

                                                        echo 'No stats available';

                                                    } else {

                                                        $tooltip = $category_num_correct . ' mark(s) / ' . $category_num_attempt . ' total';

                                                        ?>

                                                        <div class="progress" data-toggle="tooltip" title="<?php echo $tooltip;?>">

                                                            <div class="progress-bar <?php echo $category_progress_bar;?>" role="progressbar" aria-valuenow="40"

                                                                 aria-valuemin="0" aria-valuemax="100" style="width:<?php echo $category_perc;?>%">

                                                                <?php echo $category_perc;?>%

                                                            </div>

                                                        </div>

                                                        <?php

                                                    }

                                                    ?>

                                                </td>

                                                <td style="white-space: nowrap;">

                                                
                                                    <button class="btn btn-custom btn-no-margin-top smartgen_btn" id="student_id_<?php echo $category['id'] ?>" title="Adaptive Generation" disabled><i class="fa fa-android"></i></button>
                                                    
                                                    <button class="btn btn-warning btn-no-margin-top smartgen_video_btn" id="student_id_<?php echo $category['id'] ?>" title="Virtual Classroom" disabled><i class="fa fa-video-camera"></i></button>
                                                    
                                                    <button class="btn btn-danger btn-no-margin-top smartgen_booking_btn" id="student_id_<?php echo $category['id'] ?>" title="Book for a Class" disabled><i class="fa fa-calendar"></i></button>
                                                

                                                    <!-- <button class="btn btn-custom smartgen_btn">Action</button> -->

                                                

                                                </td>

                                            </tr>





                                            <?php

                                        }

                                        ?>

                                        </tbody>

                                    </table>

                                    <?php

                                }



                                ?>

                            </div>

                            <?php

                        } */

                        ?>



                    </div>

                </div>

            </div>

        </div>

<?php

    }

?>


<script>

$(document).ready(function() {
    var fastselect ;

    $("#btnBasicReport").on('click', function(){
        var level_id = $('#level_id').val();
        console.log(level_id);
        var href = $(this).data('href');
        console.log(href);
        window.open(href+ '/'+level_id, '_blank');
    });

    var start = moment('01/01/1970');
    var end = moment();

    
    function view_daterange_cb(start, end) {
        if(start.format('DD/MM/YYYY') == '01/01/1970') {
            $('#view_daterange span').html('All time');
            $('input[name="dr_start"]').val(start.format('YYYY-MM-DD'));
            $('input[name="dr_end"]').val(end.format('YYYY-MM-DD'));
        } else {
            $('#view_daterange span').html(start.format('YYYY-MM-DD') + ' - ' + end.format('YYYY-MM-DD'));
            $('input[name="dr_start"]').val(start.format('YYYY-MM-DD'));
            $('input[name="dr_end"]').val(end.format('YYYY-MM-DD'));
        }
    }

    $('#view_daterange').daterangepicker({
        startDate: start,
        endDate: end,
        ranges: {
        'Today': [moment(), moment()],
        'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
        'Last 7 Days': [moment().subtract(6, 'days'), moment()],
        'Last 30 Days': [moment().subtract(29, 'days'), moment()],
        'This Month': [moment().startOf('month'), moment().endOf('month')],
        'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
        'All Time': ['01/01/1970', moment()]
        }
    }, view_daterange_cb);

    <?php if($this->session->userdata('dr_start') && $this->session->userdata('dr_end')) { ?>
        view_daterange_cb(moment('<?php echo date('Y-m-d',strtotime($this->session->userdata('dr_start'))); ?>'),moment('<?php echo date('Y-m-d',strtotime($this->session->userdata('dr_end'))); ?>'));

    <?php } else if(isset($entered['date_range'])) { ?>
        view_daterange_cb(moment('<?php echo date('Y-m-d',strtotime($entered['date_range'][0])); ?>'),moment('<?php echo date('Y-m-d',strtotime($entered['date_range'][1])); ?>'));

    <?php } else { ?>

        view_daterange_cb(start, end);
    <?php }?>

    $('#btnCompare').on('click',function(){
        var selected = false;
        $(".multipleSelect").each(function(){

            
            var selectedValues = $(this).val();
            console.log(selectedValues);
            if(selectedValues == null){
                alert('Please select students'); return false;
            }else{
                selected = true;
            }
            for(var i = 0;i < selectedValues.length; i ++){
                $('#compareForm').append('<input type="hidden" name="student_selected[]" value = "'+selectedValues[i]+'">');
            }

        });
        if(selected){
        $('#compareForm').submit();
        }
        
    });

    $('#btnPDF').on('click',function(){
        $('#compareForm').append('<input type="hidden" name="gen_pdf" value = "1">');
        $('#btnCompare').trigger('click');
    });

    fastselect = $('.multipleSelect').fastselect();
	
});

</script>