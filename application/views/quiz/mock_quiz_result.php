
<div class="section">
	<div class="container">
		<div class="row">
			<?php
				if (isset($viewError) && $viewError) {
					echo '<div class="alert alert-danger">';
					echo $viewErrorMessage;
					echo '</div>';
				} else {
			?>
                <div class="panel panel-success panel-success-custom">
                    <!-- Default panel contents -->
                    <div class="panel-heading clearfix">
                        <h1 class="pull-left">Quiz Results</h1>
                        <h2 class="pull-right">Score: <em><span id="total_marks_id"><?= $totalMarks ?></span></em> / <?= $totalFullMarks ?>, Time Taken: <?= $timeTaken; ?></h2>
                    </div>
                    <ul class="list-group">
                        <?php
                            $quesNum = 1;
                            foreach ($questions AS $key => $question) {
                                foreach ($question AS $subquestion) {
                                    $i = $quesNum - 1;
                                    echo '<li class="list-group-item clearfix" id=question_'.$quesNum.'>';
                                    $subQuestionOutput = (count($question)==1)?'':' ('.$subquestion->sub_question.')';
                                    $fullMarks = $questionList[$i]['fullMarks'];
                                    echo '<u><h3>Question '. ($key+1) . $subQuestionOutput .'</h3></u>';
                                    if($fullMarks <= 1) {
                                        echo '<h5>(' . $fullMarks . ' Mark)</h5>';
                                    } else {
                                        echo '<h5>(' . $fullMarks . ' Marks)</h5>';
                                    }

                                    echo '<div class="question_text">';
                                    echo $questionList[$i]['questionText'];
                                    if ($questionList[$i]['questionImg'] != "0") {
                                        echo '<div><img src="'.$questionList[$i]['questionImageUrl'].'/questionImage/'.$questionList[$i]['questionImg'] .'" class="img-responsive"></div>';
                                    }
                                    echo '<div class="question_answer">';
                                    if ($questionList[$i]['questionType'] == 1) {  //objective
                                        $mcqCount = 1;
                                        $answerOption = $questionList[$i]['answerOption'];
                                        foreach ($answerOption as $option) {
                                            $class = "";
                                            if ($questionList[$i]['correctAnswer'] == $option->answer_id) {
                                                $class .= "correctAnswer ";
                                            } 
    
                                            if ($questionList[$i]['userAnswer'] == $option->answer_id) {
                                                $class .= "wrongAnswer ";
                                            }
                                            echo '<span class="' . $class . '">' . $mcqCount . ') ' . $option->answer_text . '</span>';
    
                                            if ($questionList[$i]['userAnswer'] == $questionList[$i]['correctAnswer'] && $questionList[$i]['userAnswer'] == $option->answer_id) {
                                                echo '<i class="fa fa-check answeredCorrectly"></i>';
                                            } else if ($questionList[$i]['userAnswer'] != $questionList[$i]['correctAnswer'] && $questionList[$i]['userAnswer'] == $option->answer_id) {
                                                echo '<i class="fa fa-times answeredWrongly"></i>';
                                            }
    
                                            echo '<br>';
    
                                            $mcqCount++;
                                        }
                                        if ($questionList[$i]['userAnswer'] == 0) { // no answer
                                            echo '<span class="wrongAnswer">No answer</span><i class="fa fa-times answeredWrongly"></i>';
                                        }
                                    } else {   // subjective
                                        $class = '';
                                        if (!isset($questionList[$i]['userAnswer']) || empty($questionList[$i]['userAnswer'])) {
                                            //echo '<span class="wrongAnswer">Your answer: No Answer <i class="fa fa-times answeredWrongly"></i></span>';
                                            echo '<span class="wrongAnswer">Your answer: No Answer </span>';
                                            $class .= "wrongAnswer ";
                                        } else {
                                            if ($questionList[$i]['userScore'] == $questionList[$i]['fullMarks']) {
                                                $class .= "correctAnswer ";
                                            } else {
                                                $class .= "wrongAnswer ";
                                            }
                                            echo '<span class="' . $class . '">Your answer: ' . $questionList[$i]['userAnswer'] . '</span>';
                                        }
                                            echo '<span class="' . $class . '"> (Marks given: ';
    
                                            if ($isTutor) {
                                                echo '<select name="quiz_results_marks_id" class="quiz_results_marks_id" id="'.$attemptId.'_'.$quesNum.'">';
                                                    for($selecti=0; $selecti<=$questionList[$i]['fullMarks']; $selecti++) {
    
                                                        if($selecti == $questionList[$i]['userScore']) {
                                                            echo '<option value="' . ($selecti) . '" selected="selected">' . $selecti . '</option>';
                                                        } else {
                                                            echo '<option value="' . ($selecti) . '">' . $selecti . '</option>';
                                                        }
    
                                                        if($selecti == $questionList[$i]['fullMarks']-1) { // add one more option for fullmarks-0.5
                                                            if(($selecti+0.5) == $questionList[$i]['userScore']) {
                                                                echo '<option value="' . ($selecti + 0.5) . '" selected="selected">' . ($selecti+0.5) . '</option>';
                                                            } else {
                                                                echo '<option value="' . ($selecti + 0.5) . '">' . ($selecti+0.5) . '</option>';
                                                            }
                                                        }
                                                    }
                                                echo '</select>  ';
                                            } else {
                                                echo $questionList[$i]['userScore'];
                                            }
    
                                            echo ')</span>';
                                        echo '<br>';
                                        echo '<span class="correctAnswer">Correct answer: ' . $questionList[$i]['answerOption'][0]->answer_text . '</span>';
                                    }
                                    echo '</div>';
                                    echo '</div>';
                                    echo '</li>';
                                    $quesNum++;
                                }
                            }
                        ?>
                    </ul>
                </div>
			<?php 
				}
			?>
		</div>
	</div>
</div>