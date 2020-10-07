<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Classes extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		// if ($this->session->userdata('is_logged_in') !== 1){ 
		// 	redirect('site/login');
		// }
		$this->load->model('model_google_login');
		$this->load->model('model_classes');
	}

	function google_login()
	{	
		$google_client = new Google_Client();
		$google_client->setClientId('600974188866-d7473159cmo2ofoeu49881t0ctslnhps.apps.googleusercontent.com'); //Define your ClientID
		$google_client->setClientSecret('jVizn0O75dEbgXOgOvrunkgG'); //Define your Client Secret Key
		$google_client->setRedirectUri(base_url().'classes/google_login'); //Define your Redirect Uri
	    $google_client->setScopes(array(
	    		Google_Service_Classroom::CLASSROOM_COURSES_READONLY, 
	    		Google_Service_Classroom::CLASSROOM_ROSTERS_READONLY,
	    		Google_Service_Classroom::CLASSROOM_PROFILE_EMAILS
	    ));
		$google_client->addScope('email');
		$google_client->addScope('profile');
		if(isset($_GET["code"])) {
			$token = $google_client->fetchAccessTokenWithAuthCode($_GET["code"]);
			$this->session->set_userdata('code', $_GET["code"]);
			// echo print_r($token); // exit;
			if(!isset($token["error"])) {
				$google_client->setAccessToken($token['access_token']);
				$this->session->set_userdata('access_token', $token['access_token']);
				redirect('classes/getStudentList');
			}
		} else {
			redirect($google_client->createAuthUrl());
		}
		return $google_client;
	}

	function google_login_user()
	{	
		$google_client = new Google_Client();
		$google_client->setClientId('600974188866-d7473159cmo2ofoeu49881t0ctslnhps.apps.googleusercontent.com'); //Define your ClientID
		$google_client->setClientSecret('jVizn0O75dEbgXOgOvrunkgG'); //Define your Client Secret Key
		$google_client->setRedirectUri(base_url().'classes/google_login'); //Define your Redirect Uri
	    $google_client->setScopes(array(
	    		Google_Service_Classroom::CLASSROOM_COURSES_READONLY, 
	    		Google_Service_Classroom::CLASSROOM_ROSTERS_READONLY,
	    		Google_Service_Classroom::CLASSROOM_PROFILE_EMAILS
	    ));
		$google_client->addScope('email');
		$google_client->addScope('profile');
		if(isset($_GET["code"])) {
			$token = $google_client->fetchAccessTokenWithAuthCode($_GET["code"]);
			$this->session->set_userdata('code', $_GET["code"]);
			// echo print_r($token); // exit;
			if(!isset($token["error"])) {
				$google_client->setAccessToken($token['access_token']);
				$this->session->set_userdata('access_token', $token['access_token']);
				redirect('classes/getStudentList');
			}
		} else {
			redirect($google_client->createAuthUrl());
		}
		return $google_client;
	}

	function list($page=0,$keyword="") {
		$userId = $this->session->userdata('user_id');
		$userRole = $this->session->userdata('user_role');
		$client = $this->google_login();
		$data['google_login_url'] = $client->createAuthUrl();
		if(isset($_POST['search'])) {
			$keyword = $this->input->post('keyword_class');
			$keyword = str_replace(" ", "-", $keyword);
			$keyword = str_replace('[^a-zA-Z0-9_]', "", $keyword) ;
			redirect(base_url()."classes/list/0/".$keyword);
		}

		if(isset($_POST['addnew'])) {
			redirect(base_url()."classes/add_class");
		}

		$this->load->library('pagination');
		$config = array ();
		$config['base_url'] = base_url() . 'classes/list';
		$config['total_rows'] = $this->model_classes->count_class($userId, $keyword);
		$config['per_page'] =6;
		$config['uri_segment'] =3;

		$config['full_tag_open'] = '<ul class="pagination">';
		$config['full_tag_close'] = '</ul>';
		$config['first_link'] = 'First';
		$config['last_link'] = 'Last';
		$config['first_tag_open'] = '<li>';
		$config['first_tag_close'] = '</li>';
		$config['prev_link'] = '&laquo';
		$config['prev_tag_open'] = '<li class="prev">';
		$config['prev_tag_close'] = '</li>';
		$config['next_link'] = '&raquo';
		$config['next_tag_open'] = '<li>';
		$config['next_tag_close'] = '</li>';
		$config['last_tag_open'] = '<li>';
		$config['last_tag_close'] = '</li>';
		$config['cur_tag_open'] = '<li class="active"><a href="#">';
		$config['cur_tag_close'] = '</a></li>';
		$config['num_tag_open'] = '<li>';
		$config['num_tag_close'] = '</li>';

		$this->pagination->initialize($config);
		$page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
		$data['classes'] = $this->model_classes->get_classes($userId, $config['per_page'], $page, $keyword);
		$data['links'] = $this->pagination->create_links();
		$data['page'] = $page;
		$data['keyword'] = $this->splitString($keyword);
		$data['content'] = 'classes/class_list';
		$this->load->view('include/master_view', $data);
	}

	public function splitString($str)
	{
		$splitString = array();
		$splitString = explode("-",$str);
		$splitString = implode(" ",$splitString);
		return $splitString;
	}

	function getClient()
	{
	    $client = new Google_Client();
	    if ($client->isAccessTokenExpired()) {
	        // Refresh the token if possible, else fetch a new one.
	        if ($client->getRefreshToken()) {
	            $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
	        }
	    }
	    if($this->session->userdata('access_token')) {
			$client->setAccessToken($this->session->userdata('access_token'));
		}
	    return $client;
	}

	function getCourse() {
		$client = $this->getClient();
		$service = new Google_Service_Classroom($client);
		$pageToken = NULL;
		$courses = array();

		do {
		  $params = array(
		    'pageSize' => 100,
		    'pageToken' => $pageToken
		  );
		  $response = $service->courses->listCourses($params);
		  $courses = array_merge($courses, $response->courses);
		  $pageToken = $response->nextPageToken;
		} while (!empty($pageToken));

		if (count($courses) == 0) {
		  print "No courses found.\n";
		} else {
		  print "Courses:\n";
		  foreach ($courses as $course) {
		    printf("%s (%s)\n", $course->name, $course->id);
		  }
		}

	}

	function getStudentList() {
		$client = $this->getClient();
		$service = new Google_Service_Classroom($client);
		//exit;
		$pageToken = NULL;
		$courses = array();
		$students = array();
		$teachers = array();
		$error = '';
		try {
		  	do {
			  $params = array(
			    'pageSize' => 100,
			    'pageToken' => $pageToken
			  );
			  $response = $service->courses->listCourses($params);
			  $courses = array_merge($courses, $response->courses);
			  $pageToken = $response->nextPageToken;
			} while (!empty($pageToken));
		} catch (\Google_Service_Exception $e) {
		  redirect(base_url()."classes/logout");
		}
		

		if (count($courses) == 0) {
		  // print "No courses found.\n";
			$error .= "No Classes found";
		} else {
		  // print "Courses:\n";
			foreach ($courses as $course) {
				// printf("%s (%s)\n", $course->name, $course->id);
				// print "<br />";
				$response2 = $service->courses_students->listCoursesStudents($course->id);
				$students[$course->id] = $response2->students;
				$response3 = $service->courses_teachers->listCoursesTeachers($course->id);
				$teachers[$course->id] = $response3->teachers;
				// array_debug($teachers);
				// $i = 0;
				// foreach ($students as $student) {
				// 	// printf(" - %s [%s] (%s)\n", $student->profile->name->fullName, $student->profile->emailAddress, $student->userId);
				// 	// print "<br />";
				// 	$i++;
				// }
				// print "<br />";
				// print "<br />";
			}
		}
		$data['courses'] = $courses;
		$data['students'] = $students;
		$data['teachers'] = $teachers;
		$data['content'] = 'classes/import_gc';
		$this->load->view('include/master_view', $data);
	}

	function logout()
	{	
		$client = $this->getClient();
		$client->revokeToken();
		redirect('classes/list');
	}

}
?>