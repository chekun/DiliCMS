<?php if ( ! defined('IN_DILICMS')) exit('No direct script access allowed');

class Install extends CI_Controller 
{

	public function index()
	{
        date_default_timezone_set('PRC');
		$this->load->view('install');
	}

    

}
