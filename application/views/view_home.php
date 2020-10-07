<style type="text/css">

    body {

        padding-top: 0px;

    }

</style>

<header class="container">

    <div class="row">

        <div

            class="col-xs-offset-1 col-sm-offset-0 col-md-offset-0 col-lg-offset-0 col-xs-10 col-sm-3 col-md-3 col-lg-3">

            <a href="#" class="pull-left"><img src="<?php echo base_url(); ?>img/<?php echo BRANCH_LOGO;?>" id="logo-size"></a>

            <!-- 			<a href="#" class="pull-left"><img src="<?php echo base_url(); ?>img/smart-jen-logo-horizontal.jpg" class="img-responsive"></a> -->

        </div>

        <div

            class="col-xs-offset-0 col-sm-offset-0 col-md-offset-0 col-lg-offset-0 col-xs-12 col-sm-9 col-md-9 col-lg-9">

            <?php

            if ($this->session->userdata('is_logged_in') == 1) {

                echo '<a href="' . base_url() . 'site/logout" class="pull-right btn btn-custom">Logout</a>';

            } else {

                echo '<a href="' . base_url() . 'site/login" class="pull-right btn btn-custom">Login</a>';

                // echo '<a href="#login" data-toggle="modal">Login</a>';

            }

            ?>



            <?php

            if ($this->session->userdata('is_logged_in') == 1) {

                echo '<a href="' . base_url() . 'profile" class="pull-right btn btn-warning">Dashboard</a>';

            } else {

                echo '<a href="' . base_url() . 'site/login" class="pull-right btn btn-warning">Sign Up</a>';

                // echo '<a href="#login" data-toggle="modal">Login</a>';

            }

            ?>



        </div>

    </div>



</header>



<div class="jumbotron custom-jumbotron">

    <div class="container">

        <nav class="nav navbar navbar-default navbar-custom">

            <div class="navbar-header text-center">

                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-links"

                        aria-expanded="false">

                    <span class="sr-only">Toggle Navigation</span>

                    <span class="icon-bar"></span>

                    <span class="icon-bar"></span>

                    <span class="icon-bar"></span>

                </button>

            </div>



            <div class="collapse navbar-collapse" id="navbar-links">

                <ul class="nav navbar-nav">

                    <li><a href="#"><i class="fa fa-search"></i></a></li>

                    <li class="active"><a href="<?php echo base_url(); ?>site/home">Home</a></li>

                    <li><a href="#section-smartgen" class="jump_to_div">SmartGen</a></li>

                    <li><a href="#section-onlinequiz" class="jump_to_div">Online Quiz</a></li>

                    <li><a href="#section-askjen" class="jump_to_div">AskJen</a></li>

                </ul>

                <ul class="nav navbar-nav navbar-right">

                    <li><a href="#"><i class="fa fa-twitter"></i></a></li>

                    <li><a href="http://www.facebook.com/smartjensg"><i class="fa fa-facebook"></i></a></li>

                    <li><a href="#"><i class="fa fa-google-plus"></i></a></li>

                </ul>

            </div>



        </nav>

        <div class="row">

            <div class="col-sm-7 col-md-7 col-lg-6 custom-jumbotron-title">

                <p id="tagline-main">"Education made easy when you can prepare, generate and share worksheets in just a

                    few clicks"</p>

                <!-- 				<p id="tagline-url">www.SmartJen.com</p> -->

            </div>

            <div class="col-sm-5 col-md-5 col-lg-5 text-center">

                <div class="img-crop">

                    <img src="<?php echo base_url(); ?>img/mascot.png"/>

                </div>

            </div>

        </div>

    </div>

</div>



<div class="small-bg">

    <div class="container">

        <div class="col-sm-12 col-md-12 col-lg-12 text-center">

            <img src="<?php echo base_url(); ?>img/img1.png" class="img-responsive img-responsive-custom"><br>

            <!-- 			<p class="margin-top-custom">Use SmartJen in any type of device</p> -->

            <h3 class=>Access anywhere, anytime, with any devices.</h3>

        </div>

    </div>

</div>

<!-- <div class="small-bg">

    <div class="container">

        <div class="col-sm-offset-1 col-md-offset-1 col-lg-offset-1 col-xs-12 col-sm-4 col-md-4 col-lg-4 text-center"

             id="small-bg-left">

            <img src="<?php echo base_url(); ?>img/img1.png" class="img-responsive img-responsive-custom"><br>

            <h3 class=>Access anywhere, anytime, with any devices.</h3>

        </div>

        <div class="col-sm-offset-1 col-md-offset-1 col-lg-offset-1 col-sm-5 col-md-5 col-lg-5">

            <?php

            $this->load->helper('form');

            echo form_open("site/register_validation");

            ?>



            <div class="col-sm-12 col-md-6 col-lg-6">

                <div class="form-group">

                    <input type="text" name="register_username" id="register_username" placeholder="Username"

                           class="form-control">

                </div>

            </div>



            <div class="col-sm-12 col-md-6 col-lg-6">

                <div class="form-group">

                    <input type="text" name="register_fullName" id="register_fullName" placeholder="Fullname"

                           class="form-control">

                </div>

            </div>



            <div class="col-sm-12 col-md-12 col-lg-12">

                <div class="form-group">

                    <input type="email" name="register_email" id="register_email" placeholder="Email"

                           class="form-control">

                </div>

            </div>



            <div class="col-sm-12 col-md-12 col-lg-12">

                <div class="form-group">

                    <input type="password" name="register_password" id="register_password" placeholder="Password"

                           class="form-control">

                </div>

            </div>



            <div class="col-sm-12 col-md-12 col-lg-12">

                <div class="form-group">

                    <input type="password" name="register_cpassword" id="register_cpassword"

                           placeholder="Confirm Password" class="form-control">

                </div>

            </div>



            <div class="col-sm-12 col-md-12 col-lg-12">

                <div>

                    <input type="submit" class="btn btn-warning btn-signup" value="Sign up for free" id="register_btn">

                </div>

            </div>



            <div class="col-sm-12 col-md-12 col-lg-12">

                <div class="hr-label"><span class="hr-label__text"></span></div>

                <a href="#" class="btn btn-primary facebook_login_btn"><i class="fa fa-facebook"></i> | Sign up with

                    Facebook</a>

            </div>

            <?php

            echo form_close();

            ?>

        </div>

    </div>

</div> -->





<div class="section-smartgen" id="section-smartgen">

    <div class="container">

        <div class="row margin-top">

            <div

                class="col-xs-offset-1 col-sm-offset-3 col-md-offset-3 col-lg-offset-3 col-xs-10 col-sm-6 col-md-6 col-lg-6 text-center">

                <a href="/smartgen"><img src="<?php echo base_url(); ?>img/img2.png"></a>

                <h2 class="text-uppercase hoverslide"><a href="/smartgen"><span data-hover="Smart Gen">Smart Gen</span></a>

                </h2>





                <p class=" text-justify">Simple, yet sophisticated! Create worksheets of different academic levels,

                    topics and difficulties in a few easy steps. Customise them the way you want and share them with

                    your students.</p>

            </div>

        </div>



        <div class="row margin-top">

            <div

                class="col-xs-offset-1 col-sm-offset-3 col-md-offset-3 col-lg-offset-3 col-xs-10 col-sm-6 col-md-6 col-lg-6">

                <br>

                <div class="col-xs-5 col-sm-5 col-md-5 col-lg-5"><br>

                    <img src="<?php echo base_url(); ?>img/img3.png" class="img-responsive">

                </div>

                <div class="col-xs-7 col-sm-7 col-md-7 col-lg-7">

                    <h3>Organized Database</h3>

                    <p>Stay fresh. With the thousands of categorized questions to choose, you will never have to ask the

                        same question twice. </p>

                </div>

            </div>

        </div>



        <div class="row margin-top">

            <div

                class="col-xs-offset-1 col-sm-offset-3 col-md-offset-3 col-lg-offset-3 col-xs-10 col-sm-6 col-md-6 col-lg-6">

                <br>

                <div class="col-xs-5 col-sm-5 col-md-5 col-lg-5"><br>

                    <img src="<?php echo base_url(); ?>img/img4.png" class="img-responsive">

                </div>

                <div class="col-xs-7 col-sm-7 col-md-7 col-lg-7">

                    <h3>Access anywhere, anytime</h3>

                    <p>Left your worksheet behind? Create a new one or load a saved copy in just a few minutes from the

                        cloud. Access the worksheets and progress reports wherever and whenever you want.</p>

                </div>

            </div>

        </div>



        <div class="row margin-top">

            <div

                class="col-xs-offset-1 col-sm-offset-3 col-md-offset-3 col-lg-offset-3 col-xs-10 col-sm-6 col-md-6 col-lg-6">

                <br>

                <div class="col-xs-5 col-sm-5 col-md-5 col-lg-5"><br>

                    <img src="<?php echo base_url(); ?>img/img5.png" class="img-responsive">

                </div>

                <div class="col-xs-7 col-sm-7 col-md-7 col-lg-7">

                    <h3>Customized Learning</h3>

                    <p>Learning is personal. Utilise the tailored suggestions from the system to select the most

                        appropriate questions based on your student’s performance.</p>

                </div>

            </div>

        </div>



        <div class="col-xs-offset-1 col-sm-offset-3 col-md-offset-3 col-lg-offset-3 col-xs-10 col-sm-6 col-md-6 col-lg-6">

            <a href="<?php echo base_url(); ?>smartgen" class="btn btn-custom btn-block">Try Smartgen now </a>

        </div>



    </div>

</div>



<div class="section-onlinequiz" id="section-onlinequiz">

    <div class="container">

        <div class="row margin-top">

            <div

                class="col-xs-offset-1 col-sm-offset-3 col-md-offset-3 col-lg-offset-3 col-xs-10 col-sm-6 col-md-6 col-lg-6 text-center">

                <a href="/onlinequiz"><img src="<?php echo base_url(); ?>img/img6.png"></a>

                <h2 class="text-uppercase hoverslide"><a href="/onlinequiz"><span

                            data-hover="Online Quiz">Online Quiz</span></a></h2>

                <p class=" text-justify"></p>

            </div>

        </div>



        <div class="row margin-top">

            <div

                class="col-xs-offset-1 col-sm-offset-3 col-md-offset-3 col-lg-offset-3 col-xs-10 col-sm-6 col-md-6 col-lg-6">

                <br>

                <div class="col-xs-5 col-sm-5 col-md-5 col-lg-5"><br>

                    <img src="<?php echo base_url(); ?>img/img7.png" class="img-responsive">

                </div>

                <div class="col-xs-7 col-sm-7 col-md-7 col-lg-7">

                    <h3>Performance analysis</h3>

                    <p>Attempt the customized worksheet with the easy-to-use online quiz!</p>

                    <p>With the automated marking system, get all the questions marked instantly and automatically. All

                        the attempted history are being tracked and available in the detailed reports for performance

                        analysis.</p>

                </div>

            </div>

        </div>



        <div class="col-xs-offset-1 col-sm-offset-3 col-md-offset-3 col-lg-offset-3 col-xs-10 col-sm-6 col-md-6 col-lg-6">

            <a href="<?php echo base_url(); ?>onlinequiz" class="btn btn-custom btn-block">Try Online Quiz now </a>

        </div>

    </div>

</div>





<div class="section-askjen" id="section-askjen">

    <div class="container">

        <div class="row margin-top">

            <div

                class="col-xs-offset-1 col-sm-offset-3 col-md-offset-3 col-lg-offset-3 col-xs-10 col-sm-6 col-md-6 col-lg-6 text-center">

                <!-- <a href="#"> -->
                    <img src="<?php echo base_url(); ?>img/img8.png">
                <!-- </a> -->

                <h2 class="text-uppercase hoverslide">
                    <!-- <a href=""> -->
                     <span data-hover="Ask Jen">Ask Jen</span>
                    <!-- </a> -->

                </h2><br>

                <p class=" text-justify">Have doubts? Post the questions in the community and discuss with the members!

                    Interact with the members and share your solutions with the community. </p>

            </div>

        </div>



        <!-- <div class="col-xs-offset-1 col-sm-offset-3 col-md-offset-3 col-lg-offset-3 col-xs-10 col-sm-6 col-md-6 col-lg-6">

            <a href="<?php echo base_url(); ?>askjen" class="btn btn-custom btn-block">Try Askjen now </a>

        </div> -->

    </div>

</div>



<div class="section-icon">

    <div class="container">

        <div class="row">

            <div

                class="col-xs-offset-0 col-sm-offset-0 col-md-offset-0 col-lg-offset-0 col-xs-12 col-sm-4 col-md-4 col-lg-4 margin-top">

                <div

                    class="col-xs-offset-0 col-sm-offset-0 col-md-offset-0 col-lg-offset-0 col-xs-12 col-sm-12 col-md-12 col-lg-12">

                    <img src="<?php echo base_url(); ?>img/img9.png" class="img-responsive" id="boticons">

                </div>

                <div

                    class="col-xs-offset-0 col-sm-offset-0 col-md-offset-0 col-lg-offset-0 col-xs-12 col-sm-12 col-md-12 col-lg-12">

                    <h4 class="bold text-uppercase hidden-xs" id="boticons">Mobile Friendly</h4>

                    <h4 class="bold text-uppercase text-left hidden-sm hidden-md hidden-lg">Mobile Friendly</h4>

                    <p class="text-left"></p>

                </div>

            </div>



            <div

                class="col-xs-offset-0 col-sm-offset-0 col-md-offset-0 col-lg-offset-0 col-xs-12 col-sm-4 col-md-4 col-lg-4 margin-top">

                <div

                    class="col-xs-offset-0 col-sm-offset-0 col-md-offset-0 col-lg-offset-0 col-xs-12 col-sm-12 col-md-12 col-lg-12">

                    <img src="<?php echo base_url(); ?>img/img10.png" class="img-responsive" id="boticons">

                </div>

                <div

                    class="col-xs-offset-0 col-sm-offset-0 col-md-offset-0 col-lg-offset-0 col-xs-12 col-sm-12 col-md-12 col-lg-12">

                    <h4 class="bold text-uppercase hidden-xs" id="boticons">Cloud Based</h4>

                    <h4 class="bold text-uppercase text-left hidden-sm hidden-md hidden-lg">Cloud Based</h4>

                    <p class="text-left"></p>

                </div>



            </div>



            <div

                class="col-xs-offset-0 col-sm-offset-0 col-md-offset-0 col-lg-offset-0 col-xs-12 col-sm-4 col-md-4 col-lg-4 margin-top">

                <div

                    class="col-xs-offset-0 col-sm-offset-0 col-md-offset-0 col-lg-offset-0 col-xs-12 col-sm-12 col-md-12 col-lg-12">

                    <img src="<?php echo base_url(); ?>img/img11.png" class="img-responsive" id="boticons">

                </div>

                <div

                    class="col-xs-offset-0 col-sm-offset-0 col-md-offset-0 col-lg-offset-0 col-xs-12 col-sm-12 col-md-12 col-lg-12">

                    <h4 class="bold text-uppercase hidden-xs" id="boticons">Social Centric</h4>

                    <h4 class="bold text-uppercase text-left hidden-sm hidden-md hidden-lg">Social Centric</h4>

                    <p class="text-left"></p>

                </div>

            </div>



        </div>

    </div>

</div>



<div class="section-registration">

    <div class="container">

        <div class="row margin-top">

            <div class="col-sm-12 col-md-12 col-lg-12 text-center">

            <img src="<?php echo base_url(); ?>img/img12.png" class="margin-bottom-custom">

            </div>

        </div>

    </div>

</div>

<!-- <div class="section-registration">

    <div class="container">

        <div class="row margin-top">

            <div class="col-sm-offset-1 col-md-offset-1 col-lg-offset-2 col-sm-11 col-md-10 col-lg-8 text-center">

                <div class="col-sm-4 col-md-4 col-lg-4">

                    <img src="<?php echo base_url(); ?>img/img12.png" class="margin-bottom-custom">

                </div>

                <div class="col-sm-8 col-md-8 col-lg-8">

                    <?php

                    $this->load->helper('form');

                    echo form_open("site/register_validation");

                    ?>

                    <div class="form-group">

                        <input type="text" name="register_username" id="register_username" placeholder="Username"

                               class="form-control">

                    </div>



                    <div class="form-group">

                        <input type="text" name="register_fullName" id="register_fullName" placeholder="Fullname"

                               class="form-control">

                    </div>



                    <div class="form-group">

                        <input type="email" name="register_email" id="register_email" placeholder="Email"

                               class="form-control">

                    </div>



                    <div class="form-group">

                        <input type="password" name="register_password" id="register_password" placeholder="Password"

                               class="form-control">

                    </div>



                    <div class="form-group">

                        <input type="password" name="register_cpassword" id="register_cpassword"

                               placeholder="Confirm Password" class="form-control">

                    </div>



                    <div>

                        <input type="submit" class="btn btn-warning btn-signup" value="Sign up for free"

                               id="register_btn">

                    </div>



                    <div class="hr-label"><span class="hr-label__text"></span></div>

                    <a href="#" class="btn btn-primary facebook_login_btn"><i class="fa fa-facebook"></i> | Sign up with

                        Facebook</a>

                    <?php

                    echo form_close();

                    ?>

                </div>

            </div>

        </div>

    </div>

</div> -->

