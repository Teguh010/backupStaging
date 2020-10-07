<?php defined('BASEPATH') OR exit('No direct script access allowed.');
/**
 * *
 *  * Created by PhpStorm.
 *  * User: boonkhailim
 *  * Year: 2017
 *
 */

$config['api_id'] = '364028880471243';
$config['app_secret'] = '6ef70b4a42b31c341af8eff50df14cdd';
$config['redirect_url'] = base_url(). 'site/login_with_facebook';
$config['permissions'] = array(
  'email',
  'public_profile'
);