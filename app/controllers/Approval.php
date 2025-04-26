<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Approval extends CI_Controller 
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
	
	function manageApproval($type = '', $id = '', $status = '')
    {
		if (empty($this->user_id))
        {
			redirect(base_url() . 'admin/adminLogin', 'refresh');
		}
	
		$page_data['type'] = $type;
		$page_data['id'] = $id;
		
		$page_data['manageApproval'] = $page_data['Setups'] = 1;
		
		$page_data['page_name']  = 'approval/manageApproval';
		$page_data['page_title'] = 'Approvals';
		
		switch(true)
		{
			case ($type == "add"):
				if($_POST)
				{	
					$data['approval_type'] = $this->input->post('approval_type');
					$data['created_by'] = $this->user_id;
					$data['created_date'] = $this->date_time;
					$data['last_updated_by'] = $this->user_id;
					$data['last_updated_date'] = $this->date_time;

					#Exist start here
					$chkExistKra = $this->db->query("select header_id from org_approval_header
						where 
							approval_type='".$data['approval_type']."' 
							")->result_array();
							
					if(count($chkExistKra) > 0)
					{
						$this->session->set_flashdata('error_message' , "This approval type already exist!");
						redirect(base_url() . 'approval/manageApproval/add', 'refresh');
					}
					#Exist end here
					
					$this->db->insert('org_approval_header', $data);
					$id = $this->db->insert_id();
					
					if($id !="")
					{	
						if( isset($_POST['user_id']) && $_POST['user_id'] !="" )
						{
							$count=count(array_filter($_POST['user_id']));
							
							for($dp=0;$dp<$count;$dp++)
							{	
								$data_2['header_id '] = $id;
								$data_2['user_id'] = isset($_POST['user_id'][$dp]) ? $_POST['user_id'][$dp] :"";
								$data_2['level_id'] = isset($_POST['level_id'][$dp]) ? $_POST['level_id'][$dp] :"";
								$data_2['from_amount'] = isset($_POST['from_amount'][$dp]) ? $_POST['from_amount'][$dp] :"";
								$data_2['to_amount'] = isset($_POST['to_amount'][$dp]) ? $_POST['to_amount'][$dp] :"";
								$data_2['created_by'] = $this->user_id;
								$data_2['created_date'] = $this->date_time;
								$data_2['last_updated_by'] = $this->user_id;
								$data_2['last_updated_date'] = $this->date_time;
								$data_2['approver_type'] = $data['approval_type'];
								
								$this->db->insert('org_approval_line', $data_2);
								$id_3 = $this->db->insert_id();
							}
						}
						
						$this->session->set_flashdata('flash_message' , "Approver created successfully!");
						redirect(base_url() . 'approval/manageApproval/edit/'.$id, 'refresh');
					}
				}
			break;
			
			case ($type == "edit" || $type == "view" ):
			
				$page_data['edit_data'] = $this->db->get_where('org_approval_header', array('header_id ' => $id))
										->result_array();
				if($_POST)
				{
					$data['approval_type'] = $this->input->post('approval_type');
					$data['last_updated_by'] = $this->user_id;
					$data['last_updated_date'] = $this->date_time;

					#Exist start here
					$chkExistKra = $this->db->query("select header_id from org_approval_header
						where 
							approval_type='".$data['approval_type']."' and
								header_id !='".$id."' 
							")->result_array();
							
					if(count($chkExistKra) > 0)
					{
						$this->session->set_flashdata('error_message' , "This approval type already exist!");
						redirect(base_url() . 'approval/manageApproval/edit/'.$id, 'refresh');
					}
					#Exist end here

					$this->db->where('header_id', $id);
					$result = $this->db->update('org_approval_header', $data);
					
					if($id !="")
					{	
						if( isset($_POST['user_id']) && $_POST['user_id'] !="" )
						{
							$count=count(array_filter($_POST['user_id']));
							
							for($dp=0;$dp<$count;$dp++)
							{	
								$user_id = isset($_POST['user_id'][$dp]) ? $_POST['user_id'][$dp] :"";
								$level_id = isset($_POST['level_id'][$dp]) ? $_POST['level_id'][$dp] :"";
								$line_id = isset($_POST['line_id'][$dp]) ? $_POST['line_id'][$dp] :"";
								
								$chkApprovalQry = "select line_id from org_approval_line 
									where 
										header_id='".$id."' and 
												line_id='".$line_id."'
										";
								
								$chkApprovalUsers = $this->db->query($chkApprovalQry)->result_array();

								if( count($chkApprovalUsers) == 0 ) #Insert
								{
									$lineData = array(
										"user_id"       		 => isset($_POST['user_id'][$dp]) ? $_POST['user_id'][$dp] :"",
										"level_id"     	 		 => isset($_POST['level_id'][$dp]) ? $_POST['level_id'][$dp] :"",
										"from_amount"  	 		 => isset($_POST['from_amount'][$dp]) ? $_POST['from_amount'][$dp] :"",
										"to_amount"     		 => isset($_POST['to_amount'][$dp]) ? $_POST['to_amount'][$dp] :"",
										"header_id"     		 => $id,
										"created_by" 	  		 => $this->user_id,
										"created_date" 	  		 => $this->date_time,
										"last_updated_by" 	  	 => $this->user_id,
										"last_updated_date" 	 => $this->date_time,
										"approver_type" 		 => $data['approval_type'],
									);
									
									$this->db->insert('org_approval_line', $lineData);
									$id_3 = $this->db->insert_id();
								}
								else #Update
								{
									$lineData = array(
										"user_id"      => isset($_POST['user_id'][$dp]) ? $_POST['user_id'][$dp] :"",
										"level_id"     => isset($_POST['level_id'][$dp]) ? $_POST['level_id'][$dp] :"",
										"from_amount"  => isset($_POST['from_amount'][$dp]) ? $_POST['from_amount'][$dp] :"",
										"to_amount"    => isset($_POST['to_amount'][$dp]) ? $_POST['to_amount'][$dp] :"",
										"last_updated_by" 	  	 => $this->user_id,
										"last_updated_date" 	 => $this->date_time,
									);
									
									$this->db->where('header_id', $id);
									$this->db->where('line_id', $line_id);
									$this->db->update('org_approval_line', $lineData);
								}
							}
						}
						
						$this->session->set_flashdata('flash_message' , "Approver Updated Successfully!");
						redirect(base_url() . 'approval/manageApproval/edit/'.$id, 'refresh');
					}
				}
			break;
			
			default : #Manage	
				$totalResult = $this->approval_model->getManageApproval("","",$this->totalCount);
				$page_data["totalRows"] = $totalRows = count($totalResult);

				if(!empty($_SESSION['PAGE']))
				{$limit = $_SESSION['PAGE'];
				}else{$limit = 10;}
				
				$approval_type = isset($_GET['approval_type']) ? $_GET['approval_type'] :NULL;

				$redirectURL = 'approval/manageApproval?approval_type='.$approval_type;
				
				if (!empty($_GET['approval_type'])) {
					$base_url = base_url().$redirectURL.'?approval_type='.$_GET['approval_type'];
				}else{
					$base_url = base_url().$redirectURL.'?approval_type=';
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
				
				$page_data['resultData']  = $result= $data =$this->approval_model->getManageApproval($limit, $offset, $this->pageCount);
				
				if(isset($_GET['per_page']) && $_GET['per_page'] > 1 && count($result) == 0 )
				{
					redirect(base_url().$redirectURL, 'refresh');
				}

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
	
	function ajaxDeleteApprover()
    {
		$line_id =  isset($_POST["line_id"]) ? $_POST["line_id"] : 0;

        if ($line_id > 0) 
		{
            $this->db->where('line_id', $line_id);
            $this->db->delete('org_approval_line');
        }
		echo "1";exit;
    }
}
?>
