<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Tax extends CI_Controller 
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
		
	#Manage Banner
    function manageTax($type = '', $id = '', $status = '')
    {
		if (empty($this->user_id))
        {
			redirect(base_url() . 'admin/adminLogin', 'refresh');
		}
		
		$page_data['type'] = $type;
		$page_data['id'] = $id;
		
		$page_data['setups'] = 1;
		
		$page_data['page_name']  = 'tax/manageTax';
		$page_data['page_title'] = 'TAX';
		
		switch($type)
		{
			case "add": #Add
				if($_POST)
				{
						
					$data['tax_name'] = $this->input->post('tax_name');
					$data['tax_value'] = $this->input->post('tax_value');
					$data['start_date'] = !empty($_POST['start_date']) ? date("Y-m-d",strtotime($_POST['start_date'])) : null;
					$data['end_date'] =!empty($_POST['end_date']) ? date("Y-m-d",strtotime($_POST['end_date'])) : null;
					$data['active_flag'] = $this->active_flag;
					$data['created_by'] = $this->user_id;
					$data['created_date'] = $this->date_time;
					$data['last_updated_by'] = $this->user_id;
					$data['last_updated_date'] = $this->date_time;
											
					$this->db->insert('gen_tax', $data);
					$id = $this->db->insert_id();
					
					if($id !="")
					{
						
						$this->session->set_flashdata('flash_message' ,'Tax  Added Successfully');
						redirect(base_url() . 'tax/ManageTax', 'refresh');
					}
					
				}
			break;
			
			case "edit": #Edit
				$page_data['edit_data'] = $this->db->get_where('gen_tax', array('tax_id' => $id))->result_array();
				if($_POST)
				{
					$data['tax_name'] = $this->input->post('tax_name');
					$data['tax_value'] = $this->input->post('tax_value');
					$data['start_date'] = !empty($_POST['start_date']) ? date("Y-m-d",strtotime($_POST['start_date'])) : null;
					$data['end_date'] =!empty($_POST['end_date']) ? date("Y-m-d",strtotime($_POST['end_date'])) : null;
					$data['last_updated_by'] = $this->user_id;
					$data['last_updated_date'] = $this->date_time;

					$this->db->where('tax_id', $id);
					$result = $this->db->update('gen_tax', $data);
					
					if($result > 0)
					{
						
						$this->session->set_flashdata('flash_message' ,'Tax Updated successfully');
						redirect(base_url() . 'tax/ManageTax', 'refresh');
					}
					
				}
			break;

			case "view": #Edit
				$page_data['edit_data'] = $this->db->get_where('gen_tax', array('tax_id' => $id))->result_array();
				if($_POST)
				{
					$data['tax_name'] = $this->input->post('tax_name');
					$data['tax_value'] = $this->input->post('tax_value');
					$data['start_date'] = !empty($_POST['start_date']) ? date("Y-m-d",strtotime($_POST['start_date'])) : null;
					$data['end_date'] =!empty($_POST['end_date']) ? date("Y-m-d",strtotime($_POST['end_date'])) : null;
					$data['last_updated_by'] = $this->user_id;
					$data['last_updated_date'] = $this->date_time;

					$this->db->where('tax_id', $id);
					$result = $this->db->update('gen_tax', $data);
					
					if($result > 0)
					{
						
						$this->session->set_flashdata('flash_message' ,'Tax Updated successfully');
						redirect(base_url() . 'tax/ManageTax', 'refresh');
					}
					
				}
			break;
			
			case "delete": #Delete
				$this->db->where('tax_id', $id);
				$this->db->delete('gen_tax');
				$this->session->set_flashdata('flash_message' ,'Tax Deleted successfully!');
				redirect(base_url() . 'tax/ManageTax/', 'refresh');
			break;
			
			case "status": #Block & Unblock
				if($status == 'Y'){
					$data['active_flag'] = 'Y';
					$succ_msg = 'Tax Active successfully!';
				}else{
					$data['active_flag'] = 'N';
					$succ_msg = 'Tax Inactive successfully!';
				}
				$this->db->where('tax_id', $id);
				$this->db->update('gen_tax', $data);
				$this->session->set_flashdata('flash_message' ,$succ_msg);
				redirect(base_url() . 'tax/ManageTax/', 'refresh');
			break;
			
			default : #Manage
			
				if(isset($_POST['default_submit']) && isset($_POST['default_tax']))
				{
					# Set Default banner
					$default_tax = $_POST["default_tax"];
					
					if($default_tax){
						$tax_update = $this->db->update("gen_tax", array("default_tax" => 0), array("tax_id >" => 0));
					}
					$result = $this->db->update("gen_tax", array("default_tax" => 1), array("tax_id" => $default_tax));
					
					$this->session->set_flashdata('flash_message' ,'Default Tax updated successfully!');
					redirect($_SERVER['HTTP_REFERER'], 'refresh');
				}
				
				$totalResult = $this->tax_model->getTax("","",$this->totalCount);
				$page_data["totalRows"] = $totalRows = count($totalResult);
				
				if(!empty($_SESSION['PAGE'])){
					$limit = $_SESSION['PAGE'];
				}else{$limit = 10;}
				
				$active_flag = isset($_GET['active_flag']) ? $_GET['active_flag'] :NULL;
				$redirectURL = 'tax/ManageTax?tax_name=&active_flag='.$active_flag;

				if (!empty($_GET['tax_name']) || !empty($_GET['active_flag'])) {
					$base_url = base_url('tax/ManageTax?tax_name='.$_GET['tax_name'].'&active_flag='.$_GET['active_flag']);
				} else {
					$base_url = base_url('tax/ManageTax?tax_name=&active_flag=');
				}
				
				$config = PaginationConfig($base_url,$totalRows,$limit);
				$this->pagination->initialize($config);

				$str_links = $this->pagination->create_links();
				$page_data['pagination'] = explode('&nbsp;', $str_links);
				$offset = 0;
				if (!empty($_GET['per_page'])) {
					$pageNo = $_GET['per_page'];
					$offset = ($pageNo - 1) * $limit;
				}
				
				if($offset == 1 || $offset== "" || $offset== 0){
					$page_data["first_item"] = 1;
				}else{
					$page_data["first_item"] = $offset + 1;
				}
				
				$page_data['resultData']  = $result= $data =$this->tax_model->getTax($limit, $offset,$this->pageCount);
				
				if(isset($_GET['per_page']) && $_GET['per_page'] > 1 && count($result) == 0 )
				{
					redirect(base_url().$redirectURL, 'refresh');
				}
#show start and ending Count
				
				$total_counts = $total_count= 0;
				$pages=$page_data["starting"] = $page_data["ending"]="";
				$pageno = isset($pageNo) ? $pageNo :"";
				
				if( $totalRows == 0 ){
					$page_data["starting"] = 0;
				}else if( $pageno==1 || $pageno=="" ){
					$page_data["starting"] = 1;
				}else{
					$pages = $pageno-1;
					$total_count = $pages * $config["per_page"];
					$page_data["starting"] = ( $config["per_page"] * $pages )+1;
				}
				$total_counts = $total_count + count($result);
				$page_data["ending"]  = $total_counts;
				#show start and ending Count end
			break;
		}
		$this->load->view($this->adminTemplate, $page_data);
	}

}
?>
