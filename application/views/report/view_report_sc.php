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
                        <h1>Detail report for <?=$userFullName?></h1>
                        <div class="row">
                            <div class="col-sm-4 col-md-5 col-lg-6">
                                <img src="<?php echo base_url(); ?>img/profile/<?=$profilePic;?>" class="img-responsive center-block profile-pic">
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
                                                fontSize: 12
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
                                                fontSize: 12
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
	                            $strand_href = str_replace(array(' ', '&'), '_', $strand['name']);
                                ?>
                                <li class="<?php echo ($strand_idx==0)?'active':''; ?>" ><a href="#strand_tab_<?php echo $strand_href; ?>" id="strand_tab_link_<?php echo $strand['name']; ?>" data-toggle="tab"><?=$strand['name']?></a></li>
                                <?php
                                $strand_idx++;
                            }
                            ?>
                        </ul>
                        <?php
                        $strand_idx = 0;
                        foreach ($analysis_structure as $strand) {
	                        $strand_href = str_replace(array(' ', '&'), '_', $strand['name']);
                            ?>
                            <div class="tab-pane fade <?php echo ($strand_idx == 0)?'in active':'' ;?>" role="tab-panel" id="strand_tab_<?php echo $strand_href; ?>">
                                <!-- <div class="page-header">
                                                          <h2>Strand -  <?=$strand['name']?></h2>
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
                                        <div class="progress_circles" data-percent="<?=$perc?>" data-color="<?=$color?>"></div>
                                        <a href="#<?php echo $substrand_href; ?>" class="btn btn-no-margin btn-block btn-custom toggle_category_table"><?=$substrand['name']?></a>
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
                                                <td><?= $category['name']; ?></td>
                                                <td>
                                                    <?php
                                                    if ($category_num_attempt == 0) {
                                                        echo 'No stats available';
                                                    } else {
                                                        $tooltip = $category_num_correct . ' mark(s) / ' . $category_num_attempt . ' total';
                                                        ?>
                                                        <div class="progress" data-toggle="tooltip" title="<?=$tooltip;?>">
                                                            <div class="progress-bar <?=$category_progress_bar;?>" role="progressbar" aria-valuenow="40"
                                                                 aria-valuemin="0" aria-valuemax="100" style="width:<?=$category_perc;?>%">
                                                                <?=$category_perc;?>%
                                                            </div>
                                                        </div>
                                                        <?php
                                                    }
                                                    ?>
                                                </td>
                                                <td>
                                                    <button class="btn btn-custom smartgen_btn">Action</button>
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
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
<?php
    }
?>