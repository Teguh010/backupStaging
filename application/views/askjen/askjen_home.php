<div class="section-askjen-intro">
    <div class="container">
        <div class="row">
            <div class="col-xs-12 col-sm-5 col-md-5 col-lg-5">
                <div class="speech-bubble"><p>How can I help you? :-)</p></div>
                <img src="<?php echo base_url(); ?>img/img12.png" class="center-block img-responsive">
            </div>
            <div class="col-xs-12 col-sm-7 col-md-7 col-lg-7">
                <h1>Ask Jen</h1>
                <p>We understand your concern of ‘whom do I ask when the questions are impossible to solve?’. This is
                    where AskJen comes in. AskJen provides you a social interaction platform to share your questions
                    with others for feedbacks and answers. Find the questions too challenging? Don’t worry but just post
                    it on the forum for an intellectual discussion with other parents/students. You can even choose
                    similar topics for discussion, for example selecting ‘Questions Involving Speed’ to see how others
                    solve these questions or share your knowledge to help others improve.</p>
                <p>AskJen extends the cyber limit from helping yourself to helping others, creating a collectivistic
                    learning environment for your student/child.</p>
                <p>Wait no longer and use us now!</p>
            </div>
        </div>
    </div>
</div>

<div class="section-askjen-question">
    <div class="container">
        <h1>Latest question <?php echo isset($category_name) ? '- ' . $category_name : ''; ?></h1>
        <hr>
        <?php
        foreach ($questions as $question) {
            ?>
            <div class="row question-row">
                <div class="col-xs-12 col-sm-8 col-md-7 col-lg-8">
                    <a href="<?= $question->url ?>" class="askjen_question_url"><?= $question->question_text ?></a>
                    <div class="question-info">
                        <div class="question-tag-group">
                            <a href="<?= $question->category_url ?>" class="askjen_category_url">
                                <span class="question-tag"><?= $question->category_name ?></span>
                            </a>
                        </div>
                        <!-- <div class="question-user-info pull-right">
                            asked 50 secs ago by <a href="">Jeff</a>
                        </div> -->
                    </div>
                </div>
                <div class="col-xs-12 col-sm-4 col-md-5 col-lg-4">
                    <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4 ">
                        <div><?= $question->vote_count ?></div>
                        <div>Votes</div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4 ">
                        <div><?= $question->comment_count ?></div>
                        <div>Comments</div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4 ">
                        <div><?= $question->view_count ?></div>
                        <div>Views</div>
                    </div>
                </div>
            </div>
            <?php
        }
        ?>
    </div>
</div>