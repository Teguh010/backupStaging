<?php
$quesNo = 1;
    foreach ($questionList AS $question) 
    {
        

        $question_text = $question->question_text;
?>
    <div class="question">
         <?php echo $quesNo; ?> ) <?php echo $question_text; ?> 

        <?php
            if ($question->graphical != "0") {

                echo '<div style="margin-top: 15px;"><img src="'.$question->branch_image_url.'/questionImage/'.$question->graphical.'" class="img-responsive question_image"></div>';

            }
        ?>

        <div class="question_answer">
            <?php 
                if ($que_type == 1) {
                    $mcqCount = 1;
	
								$answerOption = $answerList[$quesNo-1]['answerOption'];
	
								foreach ($answerOption as $option) {
	
									echo $mcqCount . ') ' . $option->answer_text . '<br>';
	
									$mcqCount++;
	
								}
                } else{
                    echo '<br><br>';

                    // switch ($questionList[$i]->difficulty) {
                    //     case 2:
                    //         echo '<br><br><br><br><br>';
                    //         break;
                    //     case 3:
                    //         echo '<br><br><br><br><br><br><br><br><br>';
                    //         break;
                    //     case 4:
                    //         echo '<br><br><br><br><br><br><br><br><br><br><br><br><br><br>';
                    //         break;
                    //     case 5:
                    //         echo '<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>';
                    //         break;
                    //     default:
                    //         break;
                    // }

                    echo '<div class="pull-right">Ans: _____________________________ </div>';
                }
            ?>
        </div>
    </div>

<?php 
$quesNo++;
    }

?>


<div class="answer">
    <?php 
        $ansIndex = 1;

        foreach ($answerList as $answer) {

            echo '<div id="correct_answer_' . $ansIndex . '" class="correctAnswer">';

            echo $ansIndex.') ('. $answer['correctAnswerOptionNum'] . ') ' . $answer['correctAnswer'];

            echo '</div>';

            $ansIndex++;

        }
    ?>
</div>