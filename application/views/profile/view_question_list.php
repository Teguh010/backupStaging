
<div class="section">
    <div class="container">
        <div class="row">
            <div class="col-sm-12 col-md-12 col-lg-12">
                <h1>Your uploaded question</h1>
                <hr>
                <?php
                if (count($questions) == 0) {
                    echo '<div class="col-xs-offset-1 col-sm-offset-1 col-md-offset-1 col-lg-offset-1 col-xs-10 col-sm-10 col-md-10 col-lg-10">';
                    echo '<div class="alert alert-danger margin-top-custom text-center">You don\'t have any uploaded question yet. Start <a href="'.base_url().'profile/create_question">creating one</a> now!</div>';					
                    echo '</div>';
                } else {
                ?>
                    <div class="table-responsive">
                        <table class="table table-hover datatable">
                            <thead>
                                <tr class="success">
                                    <th>Question</th>
                                    <th>Level</th>
                                    <th>Topic</th>
                                    <th>Tags</th>
                                    <th>Difficulty</th>
                                    <th>Insert time</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php
                            foreach ($questions AS $question) {
                                echo '<tr>';
                                echo '<td>' . $question->question_text . '</td>';
                                echo '<td>' . $question->level_name . '</td>';
                                echo '<td>' . $question->name . '</td>';
                                echo '<td>' . $question->tags . '</td>';
                                echo '<td>' . $question->difficulty . '</td>';
                                echo '<td>' . $question->insert_time . '</td>';
                                echo '<td><a href="' . base_url() . 'profile/edit_question/' . $question->question_id . '" class="btn btn-custom">Edit</a></td>';
                                // echo '<a href="#" class="btn btn-danger disable_question">Disable</a></td>';
                                echo '</tr>';
                            }
                            ?>
                            </tbody>
                        </table>
                    </div>
                <?php
                }
                ?>
            </div>
        </div>
    </div>
</div>