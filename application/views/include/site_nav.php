<div class="header">

	<nav class="navbar navbar-default navbar-custom navbar-fixed-top">

		<div class="container">

			<div class="navbar-header">

		      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse-1">

		        <span class="sr-only">Toggle navigation</span>

		        <span class="icon-bar"></span>

		        <span class="icon-bar"></span>

		        <span class="icon-bar"></span>

		      </button>

		      <?php
		      if($this->session->userdata('is_logged_in') == 1){
			      echo '<a class="navbar-brand navbar-brand-no-padding" href="' . base_url() . '">';
			      echo '<img src="' . base_url() . 'img/'.BRANCH_LOGO.'" class="img-responsive" id="nav-logo-size">';
			      echo '</a>';
		      } else
		      if($this->session->userdata('is_admin_logged_in') == 1){
				if($this->session->userdata('admin_username') == 'admindemo' || $this->session->userdata('admin_username') == 'smartjen_xm'){
			      echo '<img src="' . base_url() . 'img/'.BRANCH_LOGO.'" class="img-responsive" id="nav-logo-size">';
				} else {
				  echo '<a class="navbar-brand navbar-brand-no-padding" href="' . base_url() . 'administrator">';
			      echo '<img src="' . base_url() . 'img/'.BRANCH_LOGO.'" class="img-responsive" id="nav-logo-size">';
			      echo '</a>';
				}
		      } else {
			      echo '<a class="navbar-brand navbar-brand-no-padding" href="' . base_url() . '">';
			      echo '<img src="' . base_url() . 'img/'.BRANCH_LOGO.'" class="img-responsive" id="nav-logo-size">';
			      echo '</a>';
		      }
		      ?>
		      <!-- <a class="navbar-brand navbar-brand-no-padding" href="<?php echo base_url(); ?>">

				<img src="<?php echo base_url(); ?>img/smart-jen-logo-horizontal.jpg" class="img-responsive" id="nav-logo-size">

			  </a>
-->
		    </div>

		    <!-- Collect the nav links, forms, and other content for toggling -->

		    <div class="collapse navbar-collapse navbar-right" id="navbar-collapse-1">

		      <ul class="nav navbar-nav">
		        <!--<li <?php echo (strcmp($content, "view_home")==0)?'class="active"':''; ?>><a href="<?php echo base_url();?>">Home</a></li>

		        <li <?php echo (strcmp($content, "smartgen/smartgen_home")==0)?'class="active"':''; ?>><a href="<?php echo base_url(); ?>smartgen">SmartGen</a></li>

		        <!-- <li <?php echo (strcmp($content, "view_askjen")==0)?'class="active"':''; ?>><a href="<?php echo base_url(); ?>askjen">AskJen</a></li> -->
		        <!--<li <?php echo (strcmp($content, "quiz/quiz_home")==0)?'class="active"':''; ?>><a href="<?php echo base_url(); ?>onlinequiz">OnlineQuiz</a></li>

		        <li <?php echo (strcmp($content, "view_members")==0)?'class="active"':''; ?>><a href="<?php echo base_url(); ?>profile">My Profile</a></li>

		        <li>

		        	<?php

		        		if ($this->session->userdata('is_logged_in') == 1) {

		        			echo '<a href="'.base_url().'site/logout">Logout</a>';

		        		} else {

		        			echo '<a href="'.base_url().'site/login">Login/Register</a>';

		        			 // echo '<a href="#login" data-toggle="modal">Login</a>';

		        		}

		        	?>

		        </li>-->
		        <?php
		        	if($this->session->userdata('is_logged_in') == 1){
						echo '<li '.((strcmp($content, "view_home")==0)? 'class="active"':' ').'><a href="'.base_url().'">Home</a></li>';
						if($this->session->userdata('user_role') == 1) {
							echo '<li '.((strcmp($content, "smartgen/smartgen_home")==0)? 'class="active"':' ').'><a href="'.base_url().'smartgen">Worksheet Generator</a></li>';
							echo '<li '.((strcmp($content, "smartgen/profile/lessons")==0)? 'class="active"':' ').'><a href="'.base_url().'profile/lessons">Lessons</a></li>';
						//	echo '<li><a href="'.base_url().'classes/list">Classes</a></li>';
						//	echo '<li><a href="https://heyhi.sg/loginbylink/229/qfeFmzAuaOtOdFsYnYB7wwdIypjwS3DBxz9bWIop">Virtual Classroom</a></li>';
						}
						echo '<li class="dropdown">';
			        	echo '<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">FAQ<span class="caret"></span></a>';
			        	echo '<ul class="dropdown-menu" role="menu">';
			        	echo '<li><a href="https://docs.google.com/document/d/1VQk09HvGlbjX9T_vw3In6I7flooe4kYktguEGZQgKMA/edit?usp=sharing" target="_blank">Tutor\'s Walkthrough</a></li>';
			        	echo '<li><a href="https://docs.google.com/document/d/1QwsbxAs092eYSAkinxCog9yH-wupM_hdCK8m1hm_W74/edit?usp=sharing" target="_blank">Student\'s Walkthrough</a></li>';
			        	echo '</ul>';
			        	echo '</li>';
			        	// echo '<li '.((strcmp($content, "quiz/quiz_home")==0)? 'class="active"':' ').'><a href="'.base_url().'onlinequiz">OnlineQuiz</a></li>';
			        	echo '<li '.((strcmp($content, "view_members")==0)? 'class="active"':' ').'><a href="'.base_url().'profile">Dashboard</a></li>';
						// echo '<li><a href="'.base_url().'site/logout">Logout</a></li>';
						echo '<li class="dropdown">';
						if(empty($this->session->userdata('profile_pic')) === false) {
							$profilePic = $this->session->userdata('profile_pic');
						} else {
							$profilePic = 'user_placeholder.jpg';
						}
						echo '<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" style="background-image: url(' . base_url() . 'img/profile/' . $profilePic . '); height: 50px; width: 50px; background-position: center; background-size: cover; margin-left: 4px; border-radius: 30px; border: solid 2px; border-color: #DFDFDF;"></a>';
						echo '<ul class="dropdown-menu" role="menu" style="margin-top:5px;">';
						if($this->session->userdata('user_role') == 2) {
							echo '<li><a href="'.base_url().'profile/tutor_parent_view"><i class="fa fa-user"></i> Tutor/ Parent List</a></li>';
						}
						if($this->session->userdata('check_user_role') == 1){
							if ($this->session->userdata('user_role') == 1) {
								echo '<li><a href="'.base_url().'profile/switch_account"><i class="fa fa-user"></i> Switch to Parent Account</a></li>';
							} elseif ($this->session->userdata('user_role') == 3) {
								echo '<li><a href="'.base_url().'profile/switch_account"><i class="fa fa-user"></i> Switch to Tutor Account</a></li>';
							}
						}
						echo '<li><a href="'.base_url().'profile/edit"><i class="fa fa-pencil-square-o"></i> Edit Profile</a></li>';
						echo '<li><a href="'.base_url().'profile/change_password"><i class="fa fa-key"></i> Change Password</a></li>';
						echo '<li><a href="'.base_url().'site/logout"><i class="fa fa-sign-out"></i> Logout</a></li>';
						echo '</ul>';
						echo '</li>';
		        	} else
		        	if($this->session->userdata('is_admin_logged_in') == 1){
						if($this->session->userdata('admin_username') == 'admindemo'){
							echo '<li class="dropdown">';
							echo '<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Private Question<span class="caret"></span></a>';
							echo '<ul class="dropdown-menu" role="menu">';
							echo '<li><a href="'.base_url().'administrator/list_private_question">Listing</a></li>';
							echo '<li><a href="'.base_url().'administrator/create_new_question">Create New Question</a></li>';
							echo '<li><a href="'.base_url().'administrator/questions">Questions</a></li>';
							echo '</ul>';
							echo '</li>';
							echo '<li><a href="'.base_url().'administrator/logout">Logout</a></li>';
						} else if($this->session->userdata('admin_username') == 'smartjen_xm'){
							echo '<li class="dropdown">';
							echo '<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Public Question<span class="caret"></span></a>';
							echo '<ul class="dropdown-menu" role="menu">';
							echo '<li><a href="'.base_url().'administrator/list_public_question">Listing</a></li>';
							echo '<li><a href="'.base_url().'administrator/create_new_question">Create New Question</a></li>';
							echo '<li><a href="'.base_url().'administrator/questions">Questions</a></li>';
							echo '</ul>';
							echo '</li>';
							echo '<li><a href="'.base_url().'administrator/logout">Logout</a></li>';
						} else{
							echo '<li class="dropdown">';
							echo '<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Users<span class="caret"></span></a>';
							echo '<ul class="dropdown-menu" role="menu">';
							echo '<li><a href="'.base_url().'administrator/add_user">Add User</a></li>';
							echo '<li><a href="'.base_url().'administrator/tutor_list">Tutor Listing</a></li>';
							echo '<li><a href="'.base_url().'administrator/student_list">Student Listing</a></li>';
						//	echo '<li><a href="'.base_url().'administrator/class_list">Class Listing</a></li>';
							echo '</ul>';
							echo '</li>';
							echo '<li class="dropdown">';
							echo '<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Class<span class="caret"></span></a>';
							echo '<ul class="dropdown-menu" role="menu">';
							echo '<li><a href="'.base_url().'administrator/class_list/list">Class List</a></li>';
							echo '<li><a href="'.base_url().'administrator/add_class">Create New Class</a></li>';
							echo '<li><a href="'.base_url().'classes/google_login">Import from GC</a></li>';
							echo '</ul>';
							echo '</li>';
							echo '<li class="dropdown">';
							echo '<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Worksheets<span class="caret"></span></a>';
							echo '<ul class="dropdown-menu" role="menu">';
							echo '<li><a href="'.base_url().'administrator/worksheet_list">Worksheet List</a></li>';
							echo '<li><a href="'.base_url().'administrator/designWorksheet">Worksheet Generator</a></li>';
							echo '</ul>';
							echo '</li>';
	// 			        	echo '<li><a href="'.base_url().'administrator/add_user">Create User</a></li>';
							echo '<li class="dropdown">';
							echo '<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Public Question<span class="caret"></span></a>';
							echo '<ul class="dropdown-menu" role="menu">';
							echo '<li><a href="'.base_url().'administrator/list_public_question">Listing</a></li>';
							echo '<li><a href="'.base_url().'administrator/create_new_question">Create New Question</a></li>';
							echo '<li><a href="'.base_url().'administrator/questions">Questions</a></li>';
							echo '</ul>';
							echo '</li>';
							echo '<li class="dropdown">';
							echo '<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Private Question<span class="caret"></span></a>';
							echo '<ul class="dropdown-menu" role="menu">';
							echo '<li><a href="'.base_url().'administrator/list_private_question">Listing</a></li>';
							// echo '<li><a href="">Create New Question</a></li>';
							echo '</ul>';
							echo '</li>';
							echo '<li class="dropdown">';
							echo '<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Error Reported<span class="caret"></span></a>';
							echo '<ul class="dropdown-menu" role="menu">';
							echo '<li><a href="'.base_url().'administrator">Public Question</a></li>';
							echo '<li><a href="#">Private Question</a></li>';
							echo '</ul>';
							echo '</li>';
							echo '<li class="dropdown">';
							echo '<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Upload in Bulk<span class="caret"></span></a>';
							echo '<ul class="dropdown-menu" role="menu">';
							echo '<li><a href="'.base_url().'administrator/upload_bulk">Public Question</a></li>';
							echo '<li><a href="#">Private Question</a></li>';
							echo '</ul>';
							echo '</li>';
							echo '<li><a href="'.base_url().'administrator/logout">Logout</a></li>';
						}
			        	
		        	}else{
			        	echo '<li '.((strcmp($content, "view_home")==0)? 'class="active"':' ').'><a href="'.base_url().'">Home</a></li>';
						echo '<li '.((strcmp($content, "smartgen/smartgen_home")==0)? 'class="active"':' ').'><a href="'.base_url().'smartgen">Worksheet Generator</a></li>';
						echo '<li class="dropdown">';
						echo '<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">FAQ<span class="caret"></span></a>';
						echo '<ul class="dropdown-menu" role="menu">';
						echo '<li><a href="https://docs.google.com/document/d/1VQk09HvGlbjX9T_vw3In6I7flooe4kYktguEGZQgKMA/edit?usp=sharing" target="_blank">Tutor\'s Walkthrough</a></li>';
						echo '<li><a href="https://docs.google.com/document/d/1QwsbxAs092eYSAkinxCog9yH-wupM_hdCK8m1hm_W74/edit?usp=sharing" target="_blank">Student\'s Walkthrough</a></li>';
						echo '</ul>';
						echo '</li>';
			        	//echo '<li '.((strcmp($content, "quiz/quiz_home")==0)? 'class="active"':' ').'><a href="'.base_url().'onlinequiz">OnlineQuiz</a></li>';
			        	echo '<li '.((strcmp($content, "view_members")==0)? 'class="active"':' ').'><a href="'.base_url().'profile">Dashboard</a></li>';
			        	echo '<li><a href="'.base_url().'site/login">Login/Register</a></li>';
		        	}
		        ?>
		        <!--<li class="dropdown">

		          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Dropdown <span class="caret"></span></a>

		          <ul class="dropdown-menu" role="menu">

		            <li><a href="#">Action</a></li>

		            <li><a href="#">Another action</a></li>

		            <li><a href="#">Something else here</a></li>

		            <li class="divider"></li>

		            <li><a href="#">Separated link</a></li>

		            <li class="divider"></li>

		            <li><a href="#">One more separated link</a></li>

		          </ul>

		        </li>-->
		      </ul>

		    </div><!-- /.navbar-collapse -->

		</div>

	</nav>

</div>