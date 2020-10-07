<?php

/*********/
/*********/
/* Created by : Justin */
/* Date : 31/01/2019 */
/*********/
/*********/

defined('BASEPATH') or exit('No direct script access allowed');

class Maintenance extends CI_Controller{

	public function index()
	{
		$this->load->view('include/site_header');
		$this->load->view('view_maintenance');
		$this->load->view('include/site_footer');

	}
}
?>