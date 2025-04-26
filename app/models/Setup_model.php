<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Setup_model extends CI_Model 
{
	function __construct()
    {
        parent::__construct();
		$this->load->library('session');
    }

	
	
}
