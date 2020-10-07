<?php
/**
 * *
 *  * Created by PhpStorm.
 *  * User: boonkhailim
 *  * Year: 2017
 *
 */

defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Controller extends CI_Controller {

    function __construct()
    {
        parent::__construct();
        if ($this->session->userdata('is_logged_in') !== 1)
        { 
            redirect('site/login');
        }
    }
}