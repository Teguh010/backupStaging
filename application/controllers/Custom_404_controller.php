<?php
/**
 * *
 *  * Created by PhpStorm.
 *  * User: boonkhailim
 *  * Year: 2017
 *
 */

class custom_404_controller extends CI_Controller {
	public function __construct() 
	{
		parent::__construct();
	}

	public function index()
	{
		$this->output->set_status_header('404');
		$data['content'] = 'view_404';
		$this->load->view('include/master_view', $data);
	}
}