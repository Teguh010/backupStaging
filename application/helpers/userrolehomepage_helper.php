<?php defined('BASEPATH') OR exit('No direct script access allowed.');
/**
 * *
 *  * Created by PhpStorm.
 *  * User: boonkhailim
 *  * Year: 2017
 *
 */

if (!function_exists('get_user_role_home_page')) {
	function get_user_role_home_page($userRole) { 
		if ($userRole == 1) {
			return 'profile/view_tutor';
		} elseif ($userRole == 3)  {
			return 'profile/view_parent';
		} elseif ($userRole == 2) {
			return 'profile/view_student';
		}
	} 
}