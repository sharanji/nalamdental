<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Uom extends CI_Controller 
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
	
	function manageUom($type = '', $id = '', $status = '')
    {
		if (empty($this->user_id))
        {
			redirect(base_url() . 'admin/adminLogin', 'refresh');
		}
	
		$page_data['type'] = $type;
		$page_data['id'] = $id;
		
		$page_data['setups'] = 1;
		$page_data['page_name']  = 'uom/manageUom';
		$page_data['page_title'] = 'UOM';
		
		switch($type)
		{
			case "add": #View
				if($_POST)
				{
					$data['uom_code'] = $this->input->post('uom_code');
					$data['uom_description'] = $this->input->post('uom_description');

					# exist start here
					$chkExist = $this->db->query("select uom_id from uom 
						where uom_code='".$data['uom_code']."'
							")->result_array();
							
					if(count($chkExist) > 0)
					{
						$this->session->set_flashdata('error_message' , "UOM already exist!");
						redirect(base_url() . 'uom/ManageUom/add', 'refresh');
					}
					# exist end here
					
					$data['active_flag'] = $this->active_flag;
					$data['created_by'] = $this->user_id;
					$data['created_date'] = $this->date_time;
                    $data['last_updated_by'] = $this->user_id;
                    $data['last_updated_date'] = $this->date_time;
					
					$this->db->insert('uom', $data);
					$id = $this->db->insert_id();
					
					if($id !="")
					{	
						$this->session->set_flashdata('flash_message' , "UOM added Successfully!");
						redirect(base_url() . 'uom/ManageUom', 'refresh');
					}
				}
			break;
			
			case "edit": #edit
				$page_data['edit_data'] = $this->db->get_where('uom', array('uom_id' => $id))
										->result_array();
				if($_POST)
				{
					$data['uom_code'] = $this->input->post('uom_code');
					$data['uom_description'] = $this->input->post('uom_description');

					# exist start here
					$chkExist = $this->db->query("select uom_id from uom 
						where 
							uom_id !='".$id."' and
								uom_code='".$data['uom_code']."'
							")->result_array();
							
					if(count($chkExist) > 0)
					{
						$this->session->set_flashdata('error_message' , "UOM already exist!");
						redirect(base_url() . 'uom/ManageUom/edit/'.$id, 'refresh');
					}
					# exist end here
					
					$data['last_updated_by'] = $this->user_id;
                    $data['last_updated_date'] = $this->date_time;
					
					$this->db->where('uom_id', $id);
					$result = $this->db->update('uom', $data);
					
					if($result)
					{
						$this->session->set_flashdata('flash_message' , "Uom updated Successfully!");
						redirect(base_url() . 'uom/ManageUom', 'refresh');
					}
				}
			break;
			/* 
			case "delete": #Delete
				$this->db->where('uom_id', $id);
				$this->db->delete('uom');
				
				$this->session->set_flashdata('flash_message' , "Uom deleted successfully!");
				redirect(base_url() . 'uom/ManageUom', 'refresh');
			break; */
			
			case "status": #Block & Unblock
				if($status == 'Y'){
					$data['active_flag'] = 'Y';
					$data['last_updated_by'] = $this->user_id;
                    $data['last_updated_date'] = $this->date_time;
					$data['inactive_date'] = NULL;
					$succ_msg = 'UOM active successfully!';
				}else{
					$data['active_flag'] = 'N';
					$data['last_updated_by'] = $this->user_id;
                    $data['last_updated_date'] = $this->date_time;
					$data['inactive_date'] = $this->date_time;
					$succ_msg = 'UOM inctive successfully!';
				}
				$this->db->where('uom_id', $id);
				$this->db->update('uom', $data);
				$this->session->set_flashdata('flash_message' , $succ_msg);
				redirect($_SERVER['HTTP_REFERER'], 'refresh');
			break;
			
			default : #Manage
				
				$totalResult = $this->uom_model->getManageUom("","",$this->totalCount);
				$page_data["totalRows"] = $totalRows = count($totalResult);
	
				if(!empty($_SESSION['PAGE']))
				{$limit = $_SESSION['PAGE'];
				}else{$limit = 10;}
				
			
				$active_flag = isset($_GET['active_flag']) ? $_GET['active_flag'] :NULL;
				$redirectURL = 'uom/ManageUom?keywords=&active_flag='.$active_flag;

				if (!empty($_GET['keywords']) || !empty($_GET['active_flag'])) {
					$base_url = base_url('uom/ManageUom?keywords='.$_GET['keywords'].'&active_flag='.$_GET['active_flag']);
				} else {
					$base_url = base_url('uom/ManageUom?keywords=&active_flag=');
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
				
				$page_data['resultData']  = $result= $data =$this->uom_model->getManageUom($limit, $offset, $this->pageCount);
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
