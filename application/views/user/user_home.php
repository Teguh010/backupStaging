<div class="section">
    <div class="container">
        <div class="row">
            <div class="col-sm-3 col-md-3 col-lg-3">
                <img src="<?php echo base_url(); ?>img/profile/<?=$user_content['profile_pic']?>" class="img-responsive center-block profile-pic">
            </div>
            <div class="col-sm-9 col-md-9 col-lg-6">
                <h1><?=$user_content['fullname']?> <small><?=$user_content['profession']?></small></h1>
                <div class="row">
                    <div class="col-sm-4 col-md-4 col-lg-4">
                        <span>
                            <a href="#user_posts" class="jump_to_div"><i class="fa fa-comment-o"></i>
                                <?php
                                    if ($user_content['no_of_comments'] == 0) {
                                        echo ' 0 post';
                                    } elseif ($user_content['no_of_comments'] == 1) {
                                        echo ' 1 post';
                                    } else {
                                        echo $user_content['no_of_comments'] . ' posts';
                                    }
                                ?>
                            </a>
                        </span>
                    </div>
                    <div class="col-sm-4 col-md-4 col-lg-4">
                        <span><i class="fa fa-thumbs-o-up"></i> 0 upvotes</span>
                    </div>
                </div>


                <?php
                    if ($is_tutor) {
                        echo '<h2 class="margin-top-custom">Specialization</h2><hr>';
                        foreach ($user_content['specialization'] as $specialize) {
                            echo '<p>' . $specialize . '</p>';
                        }
                    }
                ?>

            </div>
        </div>

        <div class="row">
            <div class="col-sm-12 col-md-12 col-lg-12">
                <h2 id="user_posts">Recent Comments</h2><hr>
                <?php
                    if ($user_content['no_of_comments'] > 0) {
                        foreach ($user_content['comments'] as $comment) {
                ?>
                            <div class="row question-row">
                                <div class="col-sm-12 col-md-12 col-lg-12">
                                    <p><a href="<?=$comment['url']?>" class="askjen_question_url"><b><?=$comment['question_text']?></b></a></p>
                                    <p><i class="fa fa-comment-o"></i> <?=$comment['comment']?></p>
                                    <p><i class="fa fa-clock-o"></i> <?=$comment['comment_date']?></p>
                                </div>
                            </div>
                <?php
                        }
                    } else {
                        echo '<p>No recent activity</p>';
                    }
                ?>
            </div>
        </div>
    </div>
</div>