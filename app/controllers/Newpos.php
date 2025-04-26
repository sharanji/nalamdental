<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Newpos extends CI_Controller 
{
	function __construct()
	{
		parent::__construct();
		$this->load->database();
        $this->load->library('session');
      
        #Cache Control
		$this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
		$this->output->set_header('Pragma: no-cache');
	}
	
	function Newpos($id = '')
    {
		if (empty($this->user_id))
        {
			redirect(base_url() . 'admin/adminLogin', 'refresh');
		}
		$page_data['id'] = $id;
		
		$page_data['Newpos'] = 1;
		$page_data['page_name']  = 'newpos/managenewpos';
		$page_data['page_title'] = 'Newpos';
		$this->load->view($this->adminTemplate, $page_data);
	}
	
	

}


?>
