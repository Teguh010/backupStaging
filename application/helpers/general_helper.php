<?php

// array debug
if(!function_exists("array_debug")) {
	function array_debug($data, $var_dump=false) {
		echo '<pre>';
		if($var_dump === false) {
			print_r($data);
		} else {
			var_dump($data);
		}
		echo '</pre>';
	}
}

// Front End Admin
if(!function_exists("get_menu_item")) {
	function get_menu_item($menu_id=0) {
		$CI =& get_instance();
		$CI->load->model("menu_m");
		$menu_query = $CI->menu_m->menu_item_list($menu_id);
		$menu = array();
		foreach($menu_query->result() as $row) {
			if($row->parent > 0) {
				if(!isset($menu[$row->parent]["row"])) {
					$menu[$row->parent]["row"] = false;
					$menu[$row->parent]["sub"] = array();
				}
				$menu[$row->parent]["sub"][] = $row;
			} else {
				if(!isset($menu[$row->id]["row"])) {
					$menu[$row->id]["row"] = false;
					$menu[$row->id]["sub"] = array();
				}
				
				$menu[$row->id]["row"] = $row;
			}
		}
		return $menu;
	}
}


// API return all in json format
if(!function_exists("api_return")) {
	function api_return($data=array(), $response_code=null) {
		$CI =& get_instance();
		$CI->api->__destruct();
		
		$error_code = array(
			401 => "Unautorized",
			404 => "Not Found",
			400 => "Bad Request",
			500 => "Internal Server Error",
			502 => "Bad Gateway"
		);
		
		if(!is_array($data) && !is_object($data) && isset($error_code[$response_code])) {
			$CI->load->model("recsys/object/Error_o_m");
			$CI->Error_o_m->set(array("ErrorCode" => $response_code, "ErrorMessage" => $error_code[$response_code], "ErrorDescription" => $data));
			$data = $CI->Error_o_m;
		}
		
		if($response_code != null) {
			header("HTTP/1.0 " . $response_code);
		} else {
			header("HTTP/1.0 200 OK");
		}

		header('Content-Type: application/json');
		echo json_encode($data);
		exit();
	}
}

// API required authorization
if(!function_exists("api_auth")) {
	function api_auth($login=true) {
		$header = get_header();
		$CI =& get_instance();
		if(isset($header["authorization"])) {
			$CI->Access_m->verify(trim($header["authorization"]));
		}
		if($login === true && $CI->Access_m->login !== true) {
			$CI->load->model("api/object/Error_o_m");
			api_return("Unauthorized", 401);
		}
	}
}

// Get All header Value
if(!function_exists("get_header")) {
	function get_header() {
		$headers=array();
		foreach (getallheaders() as $name => $value) {
			$headers[strtolower($name)] = $value;
		}
		return $headers;
	}
}

// Message setting
if(!function_exists("set_msg_f")) {
	function set_msg_f($msg, $type="alert-danger") {
		$CI =& get_instance();
		$CI->phpsession->save("sys_msg", $msg);
		$CI->phpsession->save("sys_msg_type", $type);
	}
}

if(!function_exists("set_msg")) {
	function set_msg($msg, $type="alert-danger") {
		$CI =& get_instance();
		$CI->phpsession->save("sys_msg", $msg);
		$CI->phpsession->save("sys_msg_type", $type);
	}
}

// Always Round Up
if(!function_exists("round_up")) {
	function round_up ( $value, $precision ) { 
		$pow = pow ( 10, $precision ); 
		return ( ceil ( $pow * $value ) + ceil ( $pow * $value - ceil ( $pow * $value ) ) ) / $pow; 
	} 
}

if(!function_exists("get_msg")) {
	function get_msg($dismissable=true) {
		$CI =& get_instance();
		$show = "";
		if($CI->phpsession->get("sys_msg")) {
			$show = '
				<div class="alert ' . ($dismissable === true ? 'alert-dismissable alert-block' : '') . ' ' . ($CI->phpsession->get("sys_msg_type") ? "alert-danger" : "alert-info") . '">' . $CI->phpsession->get("sys_msg") . ($dismissable === true ? '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button>' : '') . '</div>
			';
			$CI->phpsession->clear("sys_msg");
			$CI->phpsession->clear("sys_msg_type");
		}
		echo $show;
	}
}

if(!function_exists("get_msg_f")) {
	function get_msg_f($dismissable=true) {
		$CI =& get_instance();
		$show = "";
		if($CI->phpsession->get("sys_msg")) {
			$show = '
				<div class="alert ' . ($dismissable === true ? 'alert-dismissable mb30 alert-block' : '') . ' ' . ($CI->phpsession->get("sys_msg_type") ? "alert-danger" : "alert-info") . '">' . $CI->phpsession->get("sys_msg") . ($dismissable === true ? '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button>' : '') . '</div>
			';
			$CI->phpsession->clear("sys_msg");
			$CI->phpsession->clear("sys_msg_type");
		}
		echo $show;
	}
}

// API General Time Format
if(!function_exists("api_utc")) {
	function api_utc($date_time="0000-00-00 00:00:00") {
		$date_time = strtotime($date_time);
		return gmdate("Y-m-d\TH:i:s\Z", $date_time);
	}
	
}

function is_model_loaded($model)
{
	$ci =& get_instance();      
	$load_arr = (array) $ci->load;
	
	$mod_arr = array();
	foreach ($load_arr as $key => $value)
	{
		if (substr(trim($key), 2, 50) == "_ci_models")
			$mod_arr = $value;
	}
	//print_r($mod_arr);die;
	if (in_array($model, $mod_arr))
		return TRUE;
	return FALSE;
}


function prettyPrint( $json )
{
	$result = '';
	$level = 0;
	$in_quotes = false;
	$in_escape = false;
	$ends_line_level = NULL;
	$json_length = strlen( $json );
	
	for( $i = 0; $i < $json_length; $i++ ) {
		$char = $json[$i];
		$new_line_level = NULL;
		$post = "";
		if( $ends_line_level !== NULL ) {
			$new_line_level = $ends_line_level;
			$ends_line_level = NULL;
		}
		if ( $in_escape ) {
			$in_escape = false;
		} else if( $char === '"' ) {
			$in_quotes = !$in_quotes;
		} else if( ! $in_quotes ) {
			switch( $char ) {
				case '}': case ']':
					$level--;
					$ends_line_level = NULL;
					$new_line_level = $level;
					break;
				case '{': case '[':
					$level++;
				case ',':
					$ends_line_level = $level;
					break;
				case ':':
					$post = " ";
					break;
				case " ": case "\t": case "\n": case "\r":
					$char = "";
					$ends_line_level = $new_line_level;
					$new_line_level = NULL;
					break;
			}
		} else if ( $char === '\\' ) {
		    $in_escape = true;
		}
		if( $new_line_level !== NULL ) {
		    $result .= "\n".str_repeat( "\t", $new_line_level );
		}
		$result .= $char.$post;
	}
	
	return $result;
}

if( !function_exists('apache_request_headers') ) {
///
function apache_request_headers() {
  $arh = array();
  $rx_http = '/\AHTTP_/';
  foreach($_SERVER as $key => $val) {
    if( preg_match($rx_http, $key) ) {
      $arh_key = preg_replace($rx_http, '', $key);
      $rx_matches = array();
      // do some nasty string manipulations to restore the original letter case
      // this should work in most cases
      $rx_matches = explode('_', $arh_key);
      if( count($rx_matches) > 0 and strlen($arh_key) > 2 ) {
        foreach($rx_matches as $ak_key => $ak_val) $rx_matches[$ak_key] = ucfirst($ak_val);
        $arh_key = implode('-', $rx_matches);
      }
      $arh[$arh_key] = $val;
    }
  }
  return( $arh );
}
}
///

if (!function_exists('getallheaders')) {
    /**
     * Get all HTTP header key/values as an associative array for the current request.
     *
     * @return string[string] The HTTP header key/value pairs.
     */
    function getallheaders()
    {
        $headers = array();
        $copy_server = array(
            'CONTENT_TYPE'   => 'Content-Type',
            'CONTENT_LENGTH' => 'Content-Length',
            'CONTENT_MD5'    => 'Content-Md5',
        );
        foreach ($_SERVER as $key => $value) {
            if (substr($key, 0, 5) === 'HTTP_') {
                $key = substr($key, 5);
                if (!isset($copy_server[$key]) || !isset($_SERVER[$key])) {
                    $key = str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', $key))));
                    $headers[$key] = $value;
                }
            } elseif (isset($copy_server[$key])) {
                $headers[$copy_server[$key]] = $value;
            }
        }
        if (!isset($headers['Authorization'])) {
            if (isset($_SERVER['REDIRECT_HTTP_AUTHORIZATION'])) {
                $headers['Authorization'] = $_SERVER['REDIRECT_HTTP_AUTHORIZATION'];
            } elseif (isset($_SERVER['PHP_AUTH_USER'])) {
                $basic_pass = isset($_SERVER['PHP_AUTH_PW']) ? $_SERVER['PHP_AUTH_PW'] : '';
                $headers['Authorization'] = 'Basic ' . base64_encode($_SERVER['PHP_AUTH_USER'] . ':' . $basic_pass);
            } elseif (isset($_SERVER['PHP_AUTH_DIGEST'])) {
                $headers['Authorization'] = $_SERVER['PHP_AUTH_DIGEST'];
            }
        }
        return $headers;
    }
}

if (!function_exists('need_login'))
{
	// Require Login or not
	function need_login($login=true)
	{
		$is_logged_in = false;
		$CI =& get_instance(); 
		
		if ($CI->session->userdata('login_status') === true) 
		{
			// Have Login Session
			$is_logged_in = true; 
		}
		
		if ($login === $is_logged_in) 
		{
			if ($is_logged_in === true)
			{
				// If the condition is required Login
				// Verify the login user again and set the Current login user model (object)
				$CI->load->model("login_m"); //load model
				$login_status = $CI->login_m->status_check($CI->session->userdata('login_id'));
				
				if($login_status === false) 
				{
					
					// when the user is deleted or deactivated
					$sessdata = array('login_id', 'login_status'); //set session data to empty(to clear data)
					$CI->session->unset_userdata($sessdata);
					redirect('login');
				}
			}
		}
		else
		{
			// Condition (Login or Not login) is NOT the function needed 
			
			if($is_logged_in === true) 
			{
				// if is login already
				redirect('dashboard');
			}
			else
			{
				// if is not login 
				redirect('login');
			}
		}
	}
}

if (!function_exists('need_login_f'))
{
	// Require Login or not
	function need_login_f($login=true)
	{
		$is_logged_in = false;
		$CI =& get_instance(); 
		
		if ($CI->session->userdata('login_status') === true) 
		{
			// Have Login Session
			$is_logged_in = true; 
		}
		
		if ($login === $is_logged_in) 
		{
			// Condition (Login or Not login) is the function needed 
			if($is_logged_in === true)
			{
				// If the condition is required Login
				// Verify the login user again and set the Current login user model (object)
				$CI->load->model("c_user_m"); //load model
				$login_status = $CI->c_user_m->current_user($CI->session->userdata('login_id')); 
				
				if($login_status === false) 
				{
					// when the user is deleted or deactivated
					$sessdata = array('login_id', 'login_status'); //set session data to empty(to clear data)
					$CI->session->unset_userdata($sessdata);
					redirect('login');
				}
			}
		}
		else
		{
			// Condition (Login or Not login) is NOT the function needed 
			
			if($is_logged_in === true) 
			{
				// if is login already
				redirect('home');
			}
			else
			{
				// if is not login 
				redirect('login');
			}
		}
	}
}


if(!function_exists("save_routes")) {
	function save_routes() {
		$CI =& get_instance(); 
		$CI->load->helper('file');
		$CI->load->model("admin/routes_m");
		$routes = $CI->routes_m->get_all_routes();
		$data = array();
		
// 		if (!empty($routes )) {
			$data[] = '<?php if ( ! defined(\'BASEPATH\')) exit(\'No direct script access allowed\');';
			foreach ($routes as $route) {
				$data[] = '$route[\'' . $route['uri'] . '\'] = \'' . $route['controller'] . '/' . $route['action'] . '\';';
			}
			foreach ($routes as $route) {
				if($route['controller'] === 'articles' && $route['action'] === 'category') {
					$data[] = '$route[\'' . $route['uri'] . '/(:num)\'] = \'' . $route['controller'] . '/' . $route['action'] . '/$1\';';
				}
			}
			$output = implode("\n", $data);
			write_file(APPPATH . 'cache/routes.php', $output);
// 		}
	}
}

if(!function_exists("front_general")) {
	function front_general($key="") {
		$CI =& get_instance(); 
		if(!is_model_loaded("front_general_m")) {
			$CI->load->model("sgeneral_m", "front_general_m");
		}
		
		return $CI->front_general_m->data($key);
	}
}

if(!function_exists("parse_size")) {
	function parse_size($size) {
		$unit = preg_replace('/[^bkmgtpezy]/i', '', $size); // Remove the non-unit characters from the size.
		$size = preg_replace('/[^0-9\.]/', '', $size); // Remove the non-numeric characters from the size.
		if ($unit) {
			// Find the position of the unit in the ordered string which is the power of magnitude to multiply a kilobyte by.
			return round($size * pow(1024, stripos('bkmgtpezy', $unit[0])));
		}
		else {
			return round($size);
		}
	}
}

/* For API Token  */
if(!function_exists("gen_api_token")) {
	function gen_api_token($payload) {
		$CI =& get_instance(); 
		$key = "Sm@rtJen2@!9!@#";
		$CI->load->library('jwt');

		return $CI->jwt->encode($payload, $key, 'HS256');
	}
}

if(!function_exists("verify_api_token")) {
	function verify_api_token($jwt) {
		$CI =& get_instance(); 
		$key = "Sm@rtJen2@!9!@#";
		$CI->load->library('jwt');

		return $CI->jwt->decode($jwt, $key, true);
	}
}

if(!function_exists("isJSON")) {
	function isJSON($string){
		return is_string($string) && is_array(json_decode($string, true)) && (json_last_error() == JSON_ERROR_NONE) ? true : false;
	}
}


?>