<?php
defined('BASEPATH') OR exit('No direct script access allowed');
include_once('vendor/autoload.php');
class Data_access extends CI_Controller 
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
	
	function manage_data_access($type = '', $id = '', $status = '')
    {
		if (empty($this->user_id))
        {
			redirect(base_url() . 'admin/adminLogin', 'refresh');
		}
	
		$page_data['type'] = $type;
		$page_data['id'] = $id;
		
		$page_data['manage_data_access'] = 1;

		$page_data['page_name']  = 'data_access/manage_data_access';
		$page_data['page_title'] = 'Data Access Control';
		
		switch(true)
		{
			case ($type == "add"):
				if($_POST)
				{
					$user_id 			= $this->input->post('user_id');
					$organization_id 	= $this->input->post('organization_id');

					$getDataAccess = $this->data_access_model->checkDataAccessExist($user_id,$type,NULL);

					if(count($getDataAccess) > 0)
					{
						$this->session->set_flashdata('error_message' , "Data access already exist for the user!");
						redirect(base_url() . 'data_access/manage_data_access/add', 'refresh');
					}
					
					$headerData = array(
						"user_id" 	  		 	 	=>  $user_id,
						"organization_id" 			=>  $organization_id,
						"description" 				=>  $this->input->post('description'),
						"created_by" 	  		 	=>  $this->user_id,
						"created_date" 	  		 	=>  $this->date_time,
						"last_updated_by" 	  	 	=>  $this->user_id,
						"last_updated_date" 	 	=>  $this->date_time
					);

					$this->db->insert('org_data_access_headers',$headerData);
					$header_id = $this->db->insert_id();
					
					if($header_id)
					{
					
						#Line Data start
						if(isset($_POST['branch_id']))
						{
							$count = count(array_filter($_POST['branch_id']));

							for($dp=0;$dp<$count;$dp++)
							{
								// $organization_id 	= !empty($_POST['organization_id'][$dp]) ? $_POST['organization_id'][$dp] : NULL;

								$branch_id 			= !empty($_POST['branch_id'][$dp]) ? $_POST['branch_id'][$dp] : NULL;

								$role_status 		= !empty($_POST['role_status'][$dp]) ? $_POST['role_status'][$dp] : NULL;
								
								$lineData = array(
									"header_id" 		 	=> $header_id,
									"organization_id" 		=> $organization_id,
									"branch_id" 			=> $branch_id,
									"active_flag" 		    => $role_status,
									"created_by" 	  		=> $this->user_id,
									"created_date" 	  		=> $this->date_time,
									"last_updated_by" 	  	=> $this->user_id,
									"last_updated_date" 	=> $this->date_time
								);

								$this->db->insert('org_data_access_lines', $lineData);
								$line_id = $this->db->insert_id();
								

							}
						
							if(isset($_POST["save_btn"]))
							{
								$this->session->set_flashdata('flash_message' , "Date access saved successfully!");
								redirect(base_url() . 'data_access/manage_data_access/view/'.$header_id, 'refresh');
							}
							else if(isset($_POST["submit_btn"])) {
								$this->session->set_flashdata('flash_message' , "Date access saved Submitted Successfully!");
								redirect(base_url() . 'data_access/manage_data_access', 'refresh');
							}
						}
						#Line Data end
						
					}
				}
			break;

			case ($type == "edit" || $type == "view"):

				$result = $this->data_access_model->getViewData($id);
				$page_data['edit_data'] = $result['editData'];
				$page_data['lineData'] = $result['lineData'];

				if($_POST)
				{
					// print_r($_POST);exit;
					$user_id 			= $this->input->post('user_id');
					$organization_id 	= $this->input->post('organization_id');

					
					$getDataAccess = $this->data_access_model->checkDataAccessExist($user_id,$type,$id);

					if(count($getDataAccess) > 0)
					{
						$this->session->set_flashdata('error_message' , "Data access already exist for the user!");
						redirect(base_url() . 'data_access/manage_data_access/edit/'.$id, 'refresh');
					}

					$headerData = array(
						"user_id" 	  		 	 	=>  $user_id,
						"organization_id" 	  		=>  $organization_id,
						"description" 				=>  $this->input->post('description'),
						"last_updated_by" 	  	 	=>  $this->user_id,
						"last_updated_date" 	 	=>  $this->date_time
					);

					$this->db->where('header_id', $id);
					$result = $this->db->update('org_data_access_headers', $headerData);
					
					
					if($result)
					{
						if(isset($_POST['branch_id']))
						{
							$count = count(array_filter($_POST['branch_id']));

							for($dp=0;$dp<$count;$dp++)
							{
								$line_id 			= !empty($_POST['line_id'][$dp]) ? $_POST['line_id'][$dp] : 0;

								// $organization_id 	= !empty($_POST['organization_id'][$dp]) ? $_POST['organization_id'][$dp] : NULL;

								$branch_id 			= !empty($_POST['branch_id'][$dp]) ? $_POST['branch_id'][$dp] : NULL;

								$role_status 		= !empty($_POST['role_status'][$dp]) ? $_POST['role_status'][$dp] : NULL;
								
								if ($line_id == 0)
								{
									$lineData = array(
										"header_id" 		 	=> $id,
										"organization_id" 		=> $organization_id,
										"branch_id" 			=> $branch_id,
										"active_flag" 		    => $role_status,
										"created_by" 	  		=> $this->user_id,
										"created_date" 	  		=> $this->date_time,
										"last_updated_by" 	  	=> $this->user_id,
										"last_updated_date" 	=> $this->date_time
									);
	
									$this->db->insert('org_data_access_lines', $lineData);
									$line_id = $this->db->insert_id();
								}
								else{
									$lineData = array(
										"header_id" 		 	=> $id,
										"organization_id" 		=> $organization_id,
										"branch_id" 			=> $branch_id,
										"active_flag" 		    => $role_status,
										"last_updated_by" 	  	=> $this->user_id,
										"last_updated_date" 	=> $this->date_time
									);
	
									$this->db->where('header_id', $id);
									$this->db->where('line_id', $line_id);
									$line_id = $this->db->update('org_data_access_lines', $lineData);
								}
					
							}
							
						}
						
						if(isset($_POST["save_btn"]))
						{
							$this->session->set_flashdata('flash_message' , "Date access saved successfully!");
							redirect(base_url() . 'data_access/manage_data_access/view/'.$id, 'refresh');
						}
						else if(isset($_POST["submit_btn"])) {
							$this->session->set_flashdata('flash_message' , "Date access saved Submitted Successfully!");
							redirect(base_url() . 'data_access/manage_data_access', 'refresh');
						}
					}
				}

				
			break;

			case ($type == "status"): #Block & Unblock
				if($status == "Y")
				{
					$data=array(
						'active_flag' 		=> 'Y',
						'inactive_date' 	=> NULL,
						'last_updated_by'	=> $this->user_id,
						'last_updated_date' => $this->date_time,
					);
					$succ_msg = 'Date access active successfully!';
				}
				else
				{
					$data=array(
						'active_flag' 		=> 'N',
						'inactive_date' 	=> NULL,
						'last_updated_by'	=> $this->user_id,
						'last_updated_date' => $this->date_time
					);
					$succ_msg = 'Date access successfully!';
				}
				$this->db->where('header_id', $id);
				$this->db->update('org_data_access_headers', $data);
				$this->session->set_flashdata('flash_message' , $succ_msg);
				redirect($_SERVER['HTTP_REFERER'], 'refresh');
			break;
			
			default : #Manage
				$totalResult["header_data"] = $this->data_access_model->getDataAccess("","",$this->totalCount);
				$page_data["totalRows"] = $totalRows = count($totalResult);

				if(!empty($_SESSION['PAGE']))
				{$limit = $_SESSION['PAGE'];
				}else{$limit = 10;}

				$user_id 		= isset($_GET['user_id']) ? $_GET['user_id'] :NULL;
				$user_name		= isset($_GET['user_name']) ? $_GET['user_name'] :NULL;
				$active_flag 	= isset($_GET['active_flag']) ? $_GET['active_flag'] :NULL;

				$this->redirectURL = 'data_access/manage_data_access?user_id='.$user_id.'&user_name='.$user_name.'&active_flag='.$active_flag;
				
				if ($user_id != NULL || $user_name || $active_flag != NULL) {
					$base_url = base_url().$this->redirectURL;
				} else {
					$base_url = base_url().$this->redirectURL;
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
				
				$result = $this->data_access_model->getDataAccess($limit, $offset, $this->pageCount);
				
				$page_data['resultData'] = $result["header_data"];
			    $page_data['lineData']  = $lineData = $result["line_data"];

				if(isset($_GET['per_page']) && $_GET['per_page'] > 1 && count($result) == 0 )
				{
					redirect(base_url().$this->redirectURL, 'refresh');
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

	function getUserAll(){

		if(isset($_POST["query"]))  
		{  
			$output = '';  
			
			$person_name = $_POST['query'];

			$result = $this->users_model->getAjaxUsersAll($person_name);
			
			$output = '<ul class="list-unstyled-user_id">';  
			
			if( count($result) > 0 )  
			{  
				foreach($result as $row)  
				{	
					$user_id 			= $row["user_id"];
					$employee_number	= $row["employee_number"];
					$user_name 			= $row["first_name"];
					// $person_id 			= $row["person_id"];
					$output .= '<a><li onclick="return getUserNameList(\'' .$user_id. '\',\'' .$employee_number. '\',\'' .$user_name. '\');">' . $employee_number . ' - ' . $user_name . '</li></a>';  
				}  
			}  
			else  
			{  
				$user_id = "";
				$employee_number = "";
				$user_name = "";
				
				$output .= '<li onclick="return getUserNameList(\'' .$user_id. '\',\'' .$employee_number. '\',\'' .$user_name. '\');">Sorry! Person Name Not Found.</li>';  
			}
			$output .= '</ul>';  
			echo $output;  
		}
	
		
	}
}
?>
