<?php

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class M_google {

	function __construct()
    {
        // $path = $_SERVER['DOCUMENT_ROOT'];
        // include_once $path . '/smartjen/staging/application/vendor/Google/autoload.php';
        // $CI = & get_instance();
        // $CI->google = new \Mpdf\Google_Client();
    }

    function load($param=NULL)
    {

    }

    public function login_url() {
        return base_url()."google_login/login";
    }

 }