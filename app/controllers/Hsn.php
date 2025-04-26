<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Hsn extends CI_Controller 
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
	
	function manageHsnCode($type = '', $id = '', $status = '')
    {
		if (empty($this->user_id))
        {
			redirect(base_url() . 'admin/adminLogin', 'refresh');
		}
	
		$page_data['type'] = $type;
		$page_data['id'] = $id;
		$page_data['system_settings'] = 1;
		$page_data['setup_settings'] = 1;
		
		$page_data['setups'] = 1;
		
		$page_data['page_name']  = 'hsn/manageHsnCode';
		$page_data['page_title'] = 'HSN Code';
		
		switch($type)
		{
			case "add": #Add
				if($_POST)
				{
					$data['hsn_code'] = strtoupper($this->input->post('hsn_code'));
					
					# chk Exist start
					$chkExist = $this->db->query("select hsn_code_id,hsn_code from inv_hsn_codes 
						where 
							hsn_code like '".serchFilter($data['hsn_code'])."'
							")->result_array();
					
					if(count($chkExist) > 0)
					{
						foreach($chkExist as $existValue)
						{
							$hsn_code = $existValue["hsn_code"];

							if($hsn_code == $data['hsn_code'])
							{
								$this->session->set_flashdata('error_message' , "HSN Code already exist!");
								redirect(base_url() . 'hsn/manageHsnCode/add', 'refresh');
							}
						}
					}

					# chk Exist end
					
					$data['hsn_code_description'] = $this->input->post('hsn_code_description');
					$data['tax_id'] = $this->input->post('tax_id');

					$data['start_date'] = !empty($_POST['start_date']) ? date('Y-m-d',strtotime($_POST['start_date'])) :NULL;
					$data['end_date'] = !empty($_POST['end_date']) ? date('Y-m-d',strtotime($_POST['end_date'])) :NULL;
					
					$data['created_by'] = $this->user_id;
					$data['created_date'] = $this->date_time;
                    $data['last_updated_by'] = $this->user_id;
                    $data['last_updated_date'] = $this->date_time;
					
					$this->db->insert('inv_hsn_codes', $data);
					$id = $this->db->insert_id();
					
					if($id !="")
					{	
						$this->session->set_flashdata('flash_message' , "HSN Code added Successfully!");
						redirect(base_url() . 'hsn/manageHsnCode', 'refresh');
					}
				}
			break;
			
			case "edit": #edit
				$page_data['edit_data'] = $this->db->get_where('inv_hsn_codes', array('hsn_code_id' => $id))
									->result_array();
				if($_POST)
				{
					$data['hsn_code'] = strtoupper($this->input->post('hsn_code'));
					
					# chk Exist start
					$chkExist = $this->db->query("select hsn_code from inv_hsn_codes 
						where 
							hsn_code like '".serchFilter($data['hsn_code'])."'
							and hsn_code_id !='".$id."'
							")->result_array();

					if(count($chkExist) > 0)
					{
						foreach($chkExist as $existValue)
						{
							$hsn_code = $existValue["hsn_code"];

							if($hsn_code == $data['hsn_code'])
							{
								$this->session->set_flashdata('error_message' , "HSN Code already exist!");
								redirect(base_url() . 'hsn/manageHsnCode/edit/'.$id, 'refresh');
							}
						}
					}
					# chk Exist end
					
					$data['hsn_code_description'] = $this->input->post('hsn_code_description');
					#$data['hsn_tax_id'] = $this->input->post('hsn_tax_id');
					$data['start_date'] = !empty($_POST['start_date']) ? date('Y-m-d',strtotime($_POST['start_date'])) :NULL;
					$data['end_date'] = !empty($_POST['end_date']) ? date('Y-m-d',strtotime($_POST['end_date'])) :NULL;
					$data['last_updated_by'] = $this->user_id;
                    $data['last_updated_date'] = $this->date_time;
					
					$this->db->where('hsn_code_id', $id);
					$result = $this->db->update('inv_hsn_codes', $data);
					
					if($result)
					{
						$this->session->set_flashdata('flash_message' , "HSN Code updated Successfully!");
						redirect(base_url() . 'hsn/manageHsnCode', 'refresh');
					}
				}
			break;
			
			case "delete": #Delete
				$this->db->where('hsn_code_id', $id);
				$this->db->delete('inv_hsn_codes');
				
				$this->session->set_flashdata('flash_message' , "HSN Code deleted successfully!");
				redirect(base_url() . 'hsn/manageHsnCode', 'refresh');
			break;
			
			case "status": #Block & Unblock
				if($status == 'Y'){
					$data['active_flag'] = 'Y';
					$data['last_updated_by'] = $this->user_id;
                    $data['last_updated_date'] = $this->date_time;
					$data['inactive_date'] = NULL;
					$succ_msg = 'HSN Code active successfully!';
				}else{
					$data['active_flag'] = 'N';
					$data['last_updated_by'] = $this->user_id;
                    $data['last_updated_date'] = $this->date_time;
					$data['inactive_date'] = $this->date;
					$succ_msg = 'HSN Code inactive successfully!';
				}
				$this->db->where('hsn_code_id', $id);
				$this->db->update('inv_hsn_codes', $data);
				$this->session->set_flashdata('flash_message' , $succ_msg);
				redirect($_SERVER["HTTP_REFERER"], 'refresh');
			break;
			
			default : #Manage

				$totalResult = $this->hsn_model->getManageHSNCode("","",$this->totalCount);
				$page_data["totalRows"] = $totalRows = count($totalResult);
				
	
				if(!empty($_SESSION['PAGE']))
				{$limit = $_SESSION['PAGE'];
				}else{$limit = 10;}
				
				
				$active_flag = isset($_GET['active_flag']) ? $_GET['active_flag'] :NULL;
				$redirectURL = 'hsn/manageHsnCode?keywords=&active_flag='.$active_flag;


				if (!empty($_GET['keywords']) || !empty($_GET['active_flag'])) {
					$base_url = base_url('hsn/manageHsnCode?keywords='.$_GET['keywords'].'&active_flag='.$_GET['active_flag']);
				} else {
					$base_url = base_url('hsn/manageHsnCode?keywords=&active_flag=');
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
				
				$page_data['resultData']  = $result= $data =$this->hsn_model->getManageHSNCode($limit, $offset, $this->pageCount);
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
