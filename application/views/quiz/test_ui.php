
<?php $this->load->view('include/site_header'); ?>
<link href="<?php echo base_url('css/form-wizard.css'); ?>" rel="stylesheet" type="text/css">

<link rel="stylesheet" href="<?=base_url()?>css/mathquill.min.css" />
<link rel="stylesheet" href="<?=base_url()?>css/matheditor.css" />

<script src="<?=base_url()?>js/mathquill.min.js"></script>
<script src="<?=base_url()?>js/matheditor.js"></script>
<script src="<?=base_url()?>js/pages/test-ui.js"></script>

<?php

    function replace_between($str, $needle_start, $needle_end, $replacement) {
        $pos = strpos($str, $needle_start);
        $start = $pos === false ? 0 : $pos + strlen($needle_start);
    
        $pos = strpos($str, $needle_end, $start);
        $end = $start === false ? strlen($str) : $pos;
     
        return substr_replace($str,$replacement,  $start, $end - $start);
    }


    function toNum($data) {
        $alphabet = array( 'A', 'B', 'C', 'D', 'E',
                           'F', 'G', 'H', 'I', 'J',
                           'K', 'L', 'M', 'N', 'O',
                           'P', 'Q', 'R', 'S', 'T',
                           'U', 'V', 'W', 'X', 'Y',
                           'Z'
                           );
        $alpha_flip = array_flip($alphabet);
        $return_value = -1;
        $length = strlen($data);
        for ($i = 0; $i < $length; $i++) {
            $return_value += ($alpha_flip[$data[$i]] + 1) * pow(26, ($length - $i - 1));
        }
        return $return_value;
    }


    function toAlpha($data){
        $alphabet =   array( 'A', 'B', 'C', 'D', 'E',
        'F', 'G', 'H', 'I', 'J',
        'K', 'L', 'M', 'N', 'O',
        'P', 'Q', 'R', 'S', 'T',
        'U', 'V', 'W', 'X', 'Y',
        'Z'
        );
        $alpha_flip = array_flip($alphabet);
        if($data <= 25){
          return $alphabet[$data];
        }
        elseif($data > 25){
          $dividend = ($data + 1);
          $alpha = '';
          $modulo;
          while ($dividend > 0){
            $modulo = ($dividend - 1) % 26;
            $alpha = $alphabet[$modulo] . $alpha;
            $dividend = floor((($dividend - $modulo) / 26));
          } 
          return $alpha;
        }
    }

?>


<div class="top-fixed bg-custom">
    <div style="width: 100%">
        <div class="pull-left ml-4">
            <h3 class="title-header fs30 text-white">TEST UI: OQ PE LAYOUT</h3>            
        </div>
        <div class="float-right mr-3 mt-2 fs-3x">
            <a href="<?= site_url('administrator/list_public_question') ?>" style="text-decoration: none;" class="cursor_pointer text-white"><i class="picons-thin-icon-thin-0153_delete_exit_remove_close"></i></a>
        </div>
    </div>
</div>

<div class="section pb-60" style="margin-top: -20px;">
    <div class="container">

        <div class="row">

            <h3 class="">[1] PE Without Option</h3>
        
            <?php 
                foreach($question_open as $qo){                     
                    $string = $qo->question_text;                    
                    $question = preg_replace('#<ans[^>]*>.*?</ans>#si', '<div class="start_input_answer" style="display: inline;"><span class="line_blank text-danger">_______________ <i class="picons-thin-icon-thin-0001_compose_write_pencil_new"></i></span><input type="hidden" class="input_answer input_style1_black" name="input_answer_'.$qo->question_id.'"></div>', $string);
            ?>
                <?= $qo->question_direction ?><br><br>

                <div class="pb-40" style="line-height: 2em; height: auto;">                    
                    <?= $question ?>
                </div>
            <?php } ?>

        
            <h3 class="">[2] PE With Option</h3>                         
            
            <?php                                 

                foreach($question_option as $qopt){ 
                    $selectAnswers = '<select class="form-control rounded select_option_answer" name="select_option_answer_'.$qopt->question_id.'[]" style="display: none;">';
                    $listAnswers = '<ol type="A" class="list_four_column">';
                    $x = 0;
                    foreach($answers_option as $ans){                        
                        $selectAnswers .= '<option value="'.$ans->answer_id.'">'.toAlpha($x).'</option>';
                        $listAnswers .= '<li>'.$ans->answer_text.'</li>';
                        $x++;
                    }
                    
                    $selectAnswers .= '</select>';
                    $listAnswers .= '</ol>';

                    $string = $qopt->question_text;                    
                    $question = preg_replace('#<ans[^>]*>.*?</ans>#si', '_____', $string);
                    $array = explode('_____', $question);
                    $questions = "";

                    for($x=0 ; $x<count($array) ; $x++){

                        if($x == count($array)-1){
                            $questions .= $array[$x];
                        }else{
                            $questions .= $array[$x].'<div class="start_select_option_answer" style="display: inline;">('.($x+1).') <span class="line_blank text-danger">_______________ <i class="picons-thin-icon-thin-0001_compose_write_pencil_new"></i></span>'.$selectAnswers.'</div>';
                        }

                    }
            ?>
                <?= $qopt->question_direction ?><br><br>
                <?= $listAnswers ?><br><br>

                <div style="line-height: 2em">                    
                    <?= $questions ?>
                </div>
            <?php } ?>


            <h3 class="">[3] PE With Unique Option</h3>         
            
            <?php                 

                foreach($question_list as $ql){ 
                    $string = $ql->question_text;                    
                    $question = preg_replace('#<ans[^>]*>.*?</ans>#si', '_____', $string);
                    $array = explode('_____', $question);
                    $questions = "";

                    for($x=0 ; $x<count($array) ; $x++){

                        $answers = $this->Model_quiz->getAnswerList($ql->question_id, $x+1);
                        
                        $listAnswers = '<select class="form-control rounded select_answer" name="select_answer_'.$ql->question_id.'" style="display: none;">';

                        foreach($answers as $ans){
                            $listAnswers .= '<option value="'.$ans->answer_id.'">'.$ans->answer_text.'</option>';
                        }

                        $listAnswers .= '</select>';

                        if($x == count($array)-1){
                            $questions .= $array[$x];
                        }else{
                            $questions .= $array[$x].'<div class="start_select_answer" style="display: inline;">('.($x+1).') <span class="line_blank text-danger">_______________ <i class="picons-thin-icon-thin-0001_compose_write_pencil_new"></i></span>'.$listAnswers.'</div>';
                        }

                    }
            ?>
                <?php //$ql->question_direction ?><br><br>

                <div style="line-height: 2em">                    
                    <?= $questions ?>
                </div>
            <?php } ?>

        </div>

    </div>
</div>

<div class="bg-dark-light bottom-fixed">
    <div class="container text-right">
        <div class="float-left">            
            <div class="form-inline panel_nav">
                <div class="form-group">
                    <span class="text-white mr-2"></span>
                    <span class="text-white"></span>
                </div>
            </div>            
        </div>
        <a class="btn btn-lg btn-outline-light btn-w-150 cursor_pointer font500 btnNext">Next</a>
        
    </div>
</div>


<?php $this->load->view('include/site_footer'); ?>