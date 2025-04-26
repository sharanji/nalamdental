<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Dateformat extends CI_Controller 
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
	
	function ManageDateformat($type = '', $id = '', $status = '')
    {
		if (empty($this->user_id))
        {
			redirect(base_url() . 'admin/adminLogin', 'refresh');
		}
	
		$page_data['type'] = $type;
		$page_data['id'] = $id;
		
		$page_data['setups'] = 1;
		$page_data['page_name']  = 'dateformat/ManageDateformat';
		$page_data['page_title'] = 'Date Format';
		
		if(isset($_POST['default_submit']) && isset($_POST['default_date_format']))
		{
			$data['date_format_default'] = 0;
			$result = $this->db->update('org_date_formats', $data);
			
			if($result)
			{
				$date_format_id = $_POST['default_date_format'];
				
				$data_1['date_format_default'] = 1;
				$this->db->where('date_format_id', $date_format_id);
				$result1 = $this->db->update('org_date_formats', $data_1);
			}
			$this->session->set_flashdata('flash_message' , 'Default timeformat updated successfully.');
			redirect(base_url() . 'dateformat/ManageDateformat', 'refresh');
		}
		
		switch($type)
		{
			case "add": #View
				if($_POST)
				{	
					$data['date_format'] = $this->input->post('date_format');
					$data['date_format_description'] = $this->input->post('date_format_description');
					$data['date_format_status'] = 1;
					$data['created_by'] = $this->user_id;
					$data['created_date'] = $this->date_time;
                    $data['last_updated_by'] = $this->user_id;
                    $data['last_updated_date'] = $this->date_time;
					
					# level exist start here
					$chkExistDate = $this->db->query("select date_format from org_date_formats
						where 
							 date_format like '".serchFilter($data['date_format'])."' 
							")->result_array();

					if(count($chkExistDate) > 0)
					{
						foreach($chkExistDate as $existValue)
						{
							$date_format = $existValue["date_format"];

							if($date_format == $data['date_format'])
							{
								$this->session->set_flashdata('error_message' , " Date already exist!");
								redirect(base_url() . 'dateformat/ManageDateformat/add', 'refresh');
							}
						}
					}
					# level exist end here
					

					$this->db->insert('org_date_formats', $data);
					$id = $this->db->insert_id();
					
					if($id !="")
					{	
						$this->session->set_flashdata('flash_message' , "Date added Successfully!");
						redirect(base_url() . 'dateformat/ManageDateformat', 'refresh');
					}
				}
			break;
			
			case "edit": #edit
				$page_data['edit_data'] = $this->db->get_where('org_date_formats', array('date_format_id' => $id))
										->result_array();
				if($_POST)
				{
					$data['date_format'] = $this->input->post('date_format');
					$data['date_format_description'] = $this->input->post('date_format_description');
					$data['last_updated_by'] = $this->user_id;
                    $data['last_updated_date'] = $this->date_time;
					
					# level exist start here
					

					$chkExistDate = $this->db->query("select date_format from org_date_formats
						where 
							 date_format like '".serchFilter($data['date_format'])."' 
							 and date_format_id !='".$id."'
							")->result_array();

					if(count($chkExistDate) > 0)
					{	{
					
						foreach($chkExistDate as $existValue)
							$date_format = $existValue["date_format"];

							if($date_format == $data['date_format'])
							{
								$this->session->set_flashdata('error_message' , "Date already exist!");
								redirect(base_url() . 'dateformat/ManageDateformat/edit/'.$id, 'refresh');
							}
						}
					}

					# level exist end here
					
					$this->db->where('date_format_id', $id);
					$result = $this->db->update('org_date_formats', $data);
					
					if($result)
					{
						
						$this->session->set_flashdata('flash_message' , "Date updated Successfully!");
						redirect(base_url() . 'dateformat/ManageDateformat', 'refresh');
					}
				}
			break;
			
			/* case "delete": #Delete
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
					$succ_msg = 'Date Format Active successfully!';
				}else{
					$data['active_flag'] = 'N';
					$data['last_updated_by'] = $this->user_id;
                    $data['last_updated_date'] = $this->date_time;
					$data['inactive_date'] = $this->date_time;
					$succ_msg = 'Date Format Inactive successfully!';
				}
				$this->db->where('date_format_id', $id);
				$this->db->update('org_date_formats', $data);
				$this->session->set_flashdata('flash_message' , $succ_msg);
				redirect($_SERVER['HTTP_REFERER'], 'refresh');
			break;
			
			
			
			default : #Manage

				$totalResult = $this->dateformat_model->getManageDateformat("","",$this->totalCount);
				$page_data["totalRows"] = $totalRows = count($totalResult);
	
				if(!empty($_SESSION['PAGE']))
				{$limit = $_SESSION['PAGE'];
				}else{$limit = 10;}

				$active_flag = isset($_GET['active_flag']) ? $_GET['active_flag'] :NULL;
				$redirectURL = 'dateformat/ManageDateformat?keywords=&active_flag='.$active_flag;

				if (!empty($_GET['keywords']) || !empty($_GET['active_flag'])) {
					$base_url = base_url('dateformat/ManageDateformat?keywords='.$_GET['keywords'].'&active_flag='.$_GET['active_flag']);
				} else {
					$base_url = base_url('dateformat/ManageDateformat?keywords=&active_flag=');
				}
				
				//$base_url = base_url().'dateformat/ManageDateformat';
				
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
				
				$page_data['resultData']  = $result= $data =$this->dateformat_model->getManageDateformat($limit, $offset,$this->pageCount);
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
