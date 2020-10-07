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
        
        <!-- <div class="container">
        
			<div class="row">
				
				<form>

                    <div class="col-sm-12 col-md-12 col-lg-12" style="margin-bottom: 10px;">
						
						<div class="col-sm-5 col-md-5 col-lg-5" style="margin-right: 0px; padding-right: 0px;">

                            <div class="col-sm-8 col-md-8 col-lg-8 pull-right">
						
							    <input type="text" id="datepicker_start" class="form-control"> 

                            </div>
						
						</div>

                        <div class="col-sm-2 col-md-2 col-lg-2" style="margin: auto; width: 0px;">

                                <h4 style="text-align: center;">To</h4>

                        </div>
						
						<div class="col-sm-5 col-md-5 col-lg-5" style="margin-left: 20px; padding-left: 0px;">

                            <div class="col-sm-8 col-md-8 col-lg-8 pull-left">
						
							    <input type="text" id="datepicker_end" class="form-control">
                            
                            </div>
						
						</div>
					
					</div>
				
				</form>
			
			</div>
		</div> -->

            <div class="container">

                        <!-- <h1>Detail report for <?php echo $userFullName?></h1> -->

                        <h1>Overall Statistics</h1>

                        <div class="row" style="margin-top: 25px;">

                            <div class="col-sm-4 col-md-5 col-lg-6">

                                <img src="<?php echo base_url(); ?>img/profile/<?php echo $profilePic;?>" class="img-responsive center-block profile-pic">

                                <?php if($this->uri->segment(4) != 5) {?>
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="padding-top:2em;">
                                    <a id="btnBasicReport" data-href="<?php echo base_url()."report/basic_report_pdf/".$this->uri->segment(3);?>" class="btn btn-no-margin btn-block btn-custom toggle_category_table">Basic Report</a>
                                    </div>
                                <?php } ?>

                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="padding-top:2em;">
                                    <a id="btnRecsysApi" class="btn btn-no-margin btn-block btn-custom smartgen_btn">Adaptive Generation</a>
                                    <!-- <button class="btn btn-custom btn-no-margin-top smartgen_btn" id="student_id_<?php echo $category['id'] ?>" title="Adaptive Generation"><i class="fa fa-android"></i></button> -->
                                </div>


                            </div>

                            <div class="col-sm-8 col-md-7 col-lg-6">

                                <canvas class="chart_canvas" id="student_performance_strand" width="600" height="450"></canvas>

                                <?php

                                $chart_performance_data = array();

                                $chart_strand_label = array();

                                foreach ($analysis_structure as $strand) {

                                    $chart_strand_label[] = $strand['name'];

                                    $chart_performance_data[] = $performance[$strand['name']]['percentage'];

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

                                                maxTicksLimit: 5

                                            },

                                            pointLabels: {

                                                fontSize: 18

                                            }

                                        }

                                    }



                                    var data = {

                                        labels: [<?php echo '"' . implode('", "', $chart_strand_label) . '"' ?>],

                                        datasets: [

                                            {

                                                label: "Strand Performance (%)",

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

                        <ul class="nav nav-tabs performance_nav_tabs">

                            <?php

                            $strand_idx = 0;

                            foreach ($analysis_structure as $strand) {

                                ?>

                                <li class="<?php echo ($strand_idx==0)?'active':''; ?>" ><a href="#strand_tab_<?php echo $strand['name']; ?>" id="strand_tab_link_<?php echo $strand['name']; ?>" data-toggle="tab"><?php echo $strand['name']?></a></li>

                                <?php

                                $strand_idx++;

                            }

                            ?>

                        </ul>

                        <?php

                        $strand_idx = 0;

                        foreach ($analysis_structure as $strand) {

                            ?>

                            <div class="tab-pane fade <?php echo ($strand_idx == 0)?'in active':'' ;?>" role="tab-panel" id="strand_tab_<?php echo $strand['name']; ?>">

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

                                        <div class="progress_circles" data-percent="<?php echo $perc; ?>" data-color="<?php echo $color; ?>"></div>

                                        <a href="#<?php echo $substrand_href; ?>" class="btn btn-no-margin btn-block btn-custom toggle_category_table"><?php echo $substrand['name']; ?></a>

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

                                            <tr class="table_<?php echo $category['name']; ?>" id="category_table">

                                                <td><?php echo $category['name']; ?></td>

                                                <td>

                                                    <?php

                                                    if ($category_num_attempt == 0) {
                                                        $sec_student = range(492, 512);
                                                        if($this->uri->segment(4) != 5 && in_array($student_id, $sec_student) == FALSE) {
                                                            echo 'No stats available';
                                                        } else {
                                                            $rand = rand(0,100);
                                                            $tooltip = $rand . ' mark(s) / 100 total';
                                                            if ($rand <= 30) {
                                                                $rand_progress_bar = 'progress-bar-danger';
                                                            } elseif ($rand >= 30 && $rand < 70) {
                                                                $rand_progress_bar = 'progress-bar-warning';
                                                            } elseif ($rand >= 70) {
                                                                $rand_progress_bar = 'progress-bar-success';
                                                            }
                                                            ?>
                                                        <div class="progress" data-toggle="tooltip" title="<?php echo $tooltip;?>">

                                                            <div class="progress-bar <?php echo $rand_progress_bar;?>" role="progressbar" aria-valuenow="40"

                                                                aria-valuemin="0" aria-valuemax="100" style="width:<?php echo $rand;?>%">

                                                                <?php echo $rand;?>%

                                                            </div>

                                                        </div>
                                                    <?php 
                                                        }

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
                                                <?php $sec_student = range(492, 512); if($this->uri->segment(4) != 5 && in_array($student_id, $sec_student) == FALSE) { ?>
                                                    <button class="btn btn-custom btn-no-margin-top smartgen_btn" id="student_id_<?php echo $category['id'] ?>" title="Adaptive Generation" disabled><i class="fa fa-android"></i></button>
                                                    <button class="btn btn-warning btn-no-margin-top smartgen_video_btn" id="student_id_<?php echo $category['id'] ?>" title="Virtual Classroom" disabled><i class="fa fa-video-camera"></i></button>
                                                    <button class="btn btn-danger btn-no-margin-top smartgen_booking_btn" id="student_id_<?php echo $category['id'] ?>" title="Book for a Class" disabled><i class="fa fa-calendar"></i></button>
                                                <?php } else { ?>
                                                    <button class="btn btn-custom smartgen_btn">Action</button>
                                                <?php } ?>
                                                </td>

                                            </tr>

                                            <?php if($this->uri->segment(4) != 5){ ?>
                                                <tr class="success heuristic_tables" style="border-top: 1px solid black; border-left: 1px solid black; border-right: 1px solid black;">
                                                    <td>Strategy In <?php echo $category['name'] ?></td>
                                                    <td colspan="2">Score</td>
                                                </tr>

                                            <?php 
                                                $cat = $performance[$strand['name']][$substrand['name']][$category['name']];
                                                $cat_id = $cat['category_id'];
                                                $rand = rand(0,100);
                                                foreach($cat['heuristic'] as $heuristic){ 
                                            ?>

                                                
                                                <tr class="heuristic_table" id="table_<?php echo $category['name'] ?>"style="background-color: #dbecec; border-color: #dbecec;">
                                                    <td>
                                                        <?php echo $heuristic['strategy_name'];

                                                            $str_num_attempt = $performance[$strand['name']][$substrand['name']][$category['name']]['heuristic'][$heuristic['strategy_id']]['total_attempt'];

                                                            $str_num_correct = $performance[$strand['name']][$substrand['name']][$category['name']]['heuristic'][$heuristic['strategy_id']]['total_correct'];
                                                        
                                                            $str_perc = 0;

                                                            if($heuristic['total_attempt'] !== 0){
                                                                //$score_percent = $heuristic['total_correct']/$heuristic['total_attempt'];

                                                                $str_perc = $performance[$strand['name']][$substrand['name']][$category['name']]['heuristic'][$heuristic['strategy_id']]['percentage'];
                    
                                                                $str_progress_bar = $performance[$strand['name']][$substrand['name']][$category['name']]['heuristic'][$heuristic['strategy_id']]['progress_bar_type'];
                                                            }
                                                             
                                                            $tooltip = $str_num_correct . ' mark(s) / ' . $str_num_attempt . ' total';
                                                        ?>
                                                    </td>
                                                    <td colspan="2">
                                                        <div class="progress" data-toggle="tooltip" title="<?php echo $tooltip;?>">

                                                            <div class="progress-bar <?php echo $str_progress_bar;?>" role="progressbar" aria-valuenow="40"

                                                                aria-valuemin="0" aria-valuemax="100" style="width:<?php echo $str_perc; ?>%">

                                                                <?php echo $str_perc; ?>%

                                                            </div>

                                                        </div>
                                                    </td>
                                                </tr>

                                            <?php

                                                    }
                                                }
                                            ?>

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

                        }

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

    $('.heuristic_table').hide();
    $('.heuristic_tables').hide();

    // $('.topics_score_table').on('click', '#category_table', function(e) {
    //     e.preventDefault();
        
    //     var table_heuristic = $(this).nextUntil('#category_table');
    //     table_heuristic.toggle('slow');
    // });

    $('.smartgen_video_btn').on('click', function(e) {
        e.preventDefault();
        e.stopPropagation(); 

        window.open('https://heyhi.sg/meet/996715594', '_blank');
    });

    $('.smartgen_booking_btn').on('click', function(e) {
        e.preventDefault();
        e.stopPropagation(); 

        window.open('https://heyhi.sg/glenn', '_blank');
    });
    
    $('.smartgen_btn').on('click', function(e) {
        e.preventDefault();
        e.stopPropagation(); 
        
        var cat_full = $(this).attr('id');
        var cat_id = cat_full.split('_')[2];

        var username = '<?php echo $this->uri->segment(3) ?>';

        window.open(base_url + 'smartgen/recsysApi/' + username, '_self');

    });
    

    $("#btnBasicReport").on('click', function(){
        var href = $(this).data('href');
        window.open(href, '_blank');
    });
	
});

</script>
