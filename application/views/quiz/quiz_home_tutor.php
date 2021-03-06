<div class="section">

<div class="container">

    <div class="row">



        <div class="col-sm-6 col-md-5 col-lg-5">

            <img src="<?php echo base_url(); ?>img/img6.png" class="center-block img-responsive margin-top-custom">

        </div>

        <div class="col-sm-6 col-md-7 col-lg-7">

            <h1>Online Quiz</h1>

            <p>Not happy with your current results? Failed once in your test? Fret not, try again and again until

                you succeed. With customized questions framed in different difficulty levels, every student can

                repeatedly attempt their areas of weaknesses by just a few clicks. Endless questions will be

                generated to suit each one’s needs. With SmartJen easy-to-use online quiz, we make sure your child

                learn from their mistakes by keeping track of their progresses. Every single step taken is recorded

                and self-generated by our automated system until they reach perfect score. Nothing more than

                practices make perfect scorers.</p>

        </div>

    </div>

</div>

</div>



<div class="section">

<div class="container">

    <div class="row">

        <?php

        if (isset($quizError) && $quizError) {

            echo '<div class="alert alert-danger">';

            echo $quizErrorMessage;

            echo '</div>';



        } else {

            ?>

            <div class="profile_div">

                <div class="profile_div_header">

                    <h4>Assigned quizzes</h4>

                </div>

                <div class="profile_div_body">

                    <div class="table-responsive">

                        <table class="table profile_table">

                            <tr class="success">

                                <th>Quiz Name</th>

                                <th>Assigned to</th>

                                <th>Date</th>

                                <th>Last Attempt Date</th>

                            </tr>

                            <?php

                            if (count($quizzes) == 0) {

                                echo '<tr>';

                                echo '<td colspan="4"><div class="alert alert-danger margin-top-custom text-center">You don\'t have any quiz at the moment.</div></td>';

                                echo '</tr>';

                            } else {

                                foreach ($quizzes AS $quiz) {

                                    echo '<tr>';

                                    echo '<td>' . $quiz->name . '</td>';

                                    echo '<td>' . $quiz->studentName . '</td>';

                                    echo '<td>' . $quiz->assignedDate . '</td>';

                                    echo '<td>';

                                    if (intval($quiz->numOfAttempt) == 0) {

                                        echo 'Not attempted yet.';

                                        echo '</td></tr>';

                                    } else {

                                        echo '<small>Attempted <em>' . $quiz->numOfAttempt . '</em> time(s). Last attempt : <em>' . $quiz->lastAttemptDate . '</em></small>';

                                        echo '<a href="#" class="showHistoricalAttempt btn btn-custom btn-no-margin-top">Show Attempt History</a>';

                                        echo '</td></tr>';



                                        echo '<tr class="attemptHistory">';

                                        echo '<td colspan="4">';

                                        echo '<div class="table-responsive">';

                                        echo '<table class="table profile_table">';

                                        echo '<thead>';

                                        echo '<tr class="info">';

                                        echo '<th>Attempt Date</th>';

                                        echo '<th>Score</th>';

                                        echo '<th>Action</th>';

                                        echo '</tr>';

                                        echo '</thead>';

                                        echo '<tbody>';



                                        foreach ($quiz->attempts as $attempt) {

                                            echo '<tr>';

                                            echo '<td>' . $attempt->attemptDateTime . '</td>';

                                            echo '<td>' . $attempt->score . '</td>';

                                            echo '<td><a href="' . base_url() . 'onlinequiz/viewAttempt/' . $attempt->id . '" class="btn btn-custom btn-no-margin-top">View Details</a></td>';

                                            echo '</tr>';

                                        }



                                        echo '</tbody>';

                                        echo '</table>';

                                        echo '</div>';

                                        echo '</td>';

                                        echo '</tr>';

                                    }



                                }

                            }



                            ?>

                        </table>

                    </div>

                </div>

            </div>



            <?php

        }

        ?>

    </div>

</div>

</div>