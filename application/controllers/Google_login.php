<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Google_login extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('model_google_login');
	}

	function login()
	{	
		$scopes = array(
	       'https://www.googleapis.com/auth/classroom.profile.emails',
	       'https://www.googleapis.com/auth/classroom.profile.photos',
	       'https://www.googleapis.com/auth/classroom.rosters.readonly'
       );
		$google_client = new Google_Client();
		$google_client->setClientId('600974188866-d7473159cmo2ofoeu49881t0ctslnhps.apps.googleusercontent.com'); //Define your ClientID
		$google_client->setClientSecret('jVizn0O75dEbgXOgOvrunkgG'); //Define your Client Secret Key
		$google_client->setRedirectUri(base_url().'google_login/login'); //Define your Redirect Uri
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

				$google_service = new Google_Service_Oauth2($google_client);

				$data = $google_service->userinfo->get();
				
				// echo print_r($data); echo "<br />";
				// echo "<br />".$data['given_name'];
				// echo "<br />".$data['family_name'];
				// echo "<br />".$data['email'];
				// echo "<br />".$data['id']; // exit;
				$current_datetime = date('Y-m-d H:i:s');

				if($this->model_google_login->Is_already_register($data['id']))
				{
					//update data
					$user_data = array(
					'first_name' => $data['given_name'],
					'last_name'  => $data['family_name'],
					'email_address' => $data['email'],
					'profile_picture'=> $data['picture'],
					'updated_at' => $current_datetime
					);

					$this->model_google_login->Update_user_data($user_data, $data['id']);
				} else {
					//insert data
					$user_data = array(
					'login_oauth_uid' => $data['id'],
					'first_name'  => $data['given_name'],
					'last_name'   => $data['family_name'],
					'email_address'  => $data['email'],
					'profile_picture' => $data['picture'],
					'created_at'  => $current_datetime
					);

					$this->model_google_login->Insert_user_data($user_data);
				}
				$this->session->set_userdata('user_data', $user_data);
			}
		}
		$login_button = '';
		if(!$this->session->userdata('access_token')) {
			$login_button = '<a href="'.$google_client->createAuthUrl().'">Login</a>';
			$data['login_button'] = $login_button;
			$this->load->view('view_google_login', $data);
		} else {
			$data['login_button'] = $login_button;
			$this->load->view('view_google_login', $data);
		}
	}

	function getClient()
	{
	    $client = new Google_Client();
	    if ($client->isAccessTokenExpired()) {
	        // Refresh the token if possible, else fetch a new one.
	        if ($client->getRefreshToken()) {
	            $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
	        } else {
	        	// redirect(base_url()."google_login/login");
	        }
	    }

	    if($this->session->userdata('access_token')) {
			$client->setAccessToken($this->session->userdata('access_token'));
		}
		//print_r($client);
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
		    print "<br />";
		    $response2 = $service->courses_students->listCoursesStudents($course->id);
		    $students = $response2->students;
		    // array_debug($students);
		    foreach ($students as $student) {
		    	printf(" - %s [%s] (%s)\n", $student->profile->name->fullName, $student->profile->emailAddress, $student->userId);
		    	print "<br />";
		    }
		    print "<br />";
		    print "<br />";
		  }
		}

	}

	function logout()
	{
		$this->session->unset_userdata('access_token');

		$this->session->unset_userdata('user_data');

		redirect('google_login/login');
	}

}
?>