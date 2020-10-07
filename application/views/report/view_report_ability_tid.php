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

                <h1>Performance Ability Report for <?php echo $userFullName?></h1>
                <div class="row">

                    <div class="col-lg-12 tab-content">
                        <table class="table table-hover table-bordered" id="">

                            <thead>

                            <tr class="warning">

                                <th>Abilities</th>

                                <th>Score</th>

                                <th>Action</th>

                            </tr>

                            </thead>

                            <tbody>

                            <?php

                            foreach ($abilities_tid as $category) {

                                $category_num_attempt = $performance[$category['ability_name']]['total_attempt'];

                                $category_num_correct = $performance[$category['ability_name']]['total_correct'];

                                if ($category_num_attempt != 0) {

                                    $category_perc = $performance[$category['ability_name']]['percentage'];

                                    $category_progress_bar = $performance[$category['ability_name']]['progress_bar_type'];

                                }

                                ?>

                                <tr>

                                    <td><?php echo  $category['ability_name']; ?></td>

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

                                            <?php } ?>

                                    </td>

                                    <td style="white-space: nowrap;">
                                    
                                        <button class="btn btn-custom btn-no-margin-top smartgen_btn" id="student_id_<?php echo $category['ability_id'] ?>" title="Adaptive Generation" disabled><i class="fa fa-android"></i></button>
                                        
                                        <button class="btn btn-warning btn-no-margin-top smartgen_video_btn" id="student_id_<?php echo $category['ability_id'] ?>" title="Virtual Classroom" disabled><i class="fa fa-video-camera"></i></button>
                                        
                                        <button class="btn btn-danger btn-no-margin-top smartgen_booking_btn" id="student_id_<?php echo $category['ability_id'] ?>" title="Book for a Class" disabled><i class="fa fa-calendar"></i></button>
                                    
                                    </td>

                                </tr>
                                <?php } ?>

                            </tbody>

                        </table>

                    </div>

                </div>
                        
            </div>

        </div>

<?php /*

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

                    </div>

                </div>

            </div>

        </div>

<?php */

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