<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Printersettings extends CI_Controller 
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
	
	function managePrintersettings($type = '', $id = '', $status = '')
    {
		if (empty($this->user_id))
        {
			redirect(base_url() . 'admin/adminLogin', 'refresh');
		}
	
		$page_data['type'] = $type;
		$page_data['id'] = $id;
		
		$page_data['manage_settings'] = $page_data['Setups'] = 1;
		
		$page_data['page_name']  = 'printer_settings/managePrintersettings';
		$page_data['page_title'] = 'Printers Settings';
		
		switch($type)
		{
			case "add": #Add
				if($_POST)
				{
					$branch_id = $this->input->post('branch_id');
					
					$existBranchCode = $this->db->query("select header_id from org_print_count_header where branch_id='".$branch_id."' ")->result_array();
					if(count($existBranchCode) > 0 )
					{
						$this->session->set_flashdata('error_message' , "Sorry! Already exist this branch!");
						redirect(base_url() . 'printersettings/ManagePrintersettings/add', 'refresh');
					}

					$headerPostData = array(
						"branch_id" 	      => $branch_id,
						"active_flag" 		  => $this->active_flag,
						"created_by" 	      => $this->user_id,
						"created_date" 	      => $this->date_time,
						"last_updated_by" 	  => $this->user_id,
						"last_updated_date"   => $this->date_time,
					);

					$this->db->insert('org_print_count_header', $headerPostData);
					$header_id = $id = $this->db->insert_id();
					
					if($id !="")
					{
						# Add and Remove menus start
						if( isset($_POST['type_id']) && $_POST['type_id'] !="" )
						{
							$count=count($_POST['type_id']);
							for($dp=0;$dp<$count;$dp++)
							{	
								$lineData = array(
									"header_id"           => $id,
									"branch_id"           => $branch_id,
									"type_id"             => $_POST['type_id'][$dp],
									"printer_name"        => $_POST['printer_name'][$dp],
									#"printer_ip"          => $_POST['printer_ip'][$dp],
									"printer_count"       => $_POST['printer_count'][$dp],
									"active_flag" 		  => $this->active_flag,
									"created_by" 	      => $this->user_id,
									"created_date" 	      => $this->date_time,
									"last_updated_by" 	  => $this->user_id,
									"last_updated_date"   => $this->date_time,
								);
								
								$this->db->insert('org_print_count_line', $lineData);
								$line_id = $id_3 = $this->db->insert_id();
							}
						}
						#Add and Remove menus end
						$this->session->set_flashdata('flash_message' , "Prints saved successfully!");
						redirect(base_url() . 'printersettings/managePrintersettings/edit/'.$id, 'refresh');
					}
				}
			break;
			
			case "edit": #edit
				$page_data['data'] = $this->printersettings_model->getRecord($id);
				
				foreach ($page_data['data'] as $key) 
				{
					$header_id = $key->header_id;
					$page_data['menuitems'] = $this->printersettings_model->getMenuitems($header_id);	
				}

				$page_data['header_id'] = $id;
				
				if($_POST)
				{	
					$branch_id = $branchID = $this->input->post('branch_id');

					$existBranchCode = $this->db->query("select header_id from org_print_count_header where branch_id='".$branch_id."' and header_id !='".$id."'")->result_array();
					if(count($existBranchCode) > 0 )
					{
						$this->session->set_flashdata('error_message' , "Sorry! Already exist this branch!");
						redirect(base_url() . 'printersettings/ManagePrintersettings/edit/'.$id, 'refresh');
					}

					$headerPostData = array(
						"branch_id" =>  $branchID,
						"last_updated_by" 	  => $this->user_id,
						"last_updated_date"   => $this->date_time,
					);
					
					$this->db->where('header_id', $id);
					$result = $this->db->update('org_print_count_header', $headerPostData);
					
					if($result)
					{
						if(isset($_POST['type_id']) && $_POST['type_id'] !="")
						{								
							$count=count($_POST['type_id']);

							for($dp=0;$dp<$count;$dp++)
							{									
								$line_id = $_POST['line_id'][$dp];
								$type_id = $_POST['type_id'][$dp];

								$testCasesQry = "select line_id from org_print_count_line 
								where 
								header_id='".$id."' and 
								line_id='".$line_id."' ";
								$chkTestCases = $this->db->query($testCasesQry)->result_array();

								if( count($chkTestCases) > 0 )
								{
									$lineData = array(
										"type_id"            => $_POST['type_id'][$dp],
										"printer_name"       => $_POST['printer_name'][$dp],
										"printer_count"      => $_POST['printer_count'][$dp],
										"last_updated_by" 	 => $this->user_id,
										"last_updated_date"  => $this->date_time,
									);
									
									$this->db->where('header_id', $id);
									$this->db->where('line_id', $line_id);
									$result = $this->db->update('org_print_count_line', $lineData);
								}
								else
								{
									$lineData = array(
										"header_id"      => $id,
										"type_id"        => $_POST['type_id'][$dp],
										"printer_name"   => $_POST['printer_name'][$dp],
										"printer_count"  => $_POST['printer_count'][$dp],
										"branch_id"      => $branchID,
										"active_flag" 		  => $this->active_flag,
										"created_by" 	      => $this->user_id,
										"created_date" 	      => $this->date_time,
										"last_updated_by" 	  => $this->user_id,
										"last_updated_date"   => $this->date_time,
									);
									
									$this->db->insert('org_print_count_line', $lineData);
									$line_id = $id_3 = $this->db->insert_id();
								}
							}
						}
						#Add and Remove menus end
						
						$this->session->set_flashdata('flash_message' , "Printer saved successfully!");
						redirect(base_url() . 'printersettings/ManagePrintersettings/edit/'.$id, 'refresh');
					}
				}
			break;
			
			case "status": #Block & Unblock
				if($status == 1){
					$data['active_flag'] = 'Y';
					$data['inactive_date'] = NULL;
					$succ_msg = 'Printer active successfully!';
				}else{
					$data['active_flag'] = 'N';
					$data['inactive_date'] = $this->date_time;
					$succ_msg = 'Printer inactive successfully!';
				}

				$this->db->where('header_id', $id);
				$this->db->update('org_print_count_header', $data);
				echo ($succ_msg);
				exit;
			break;

			default : #Manage
				$page_data["totalRows"] = $totalRows = $this->printersettings_model->getManagePrintersettingsCount();
	
				if(!empty($_SESSION['PAGE']))
				{$limit = $_SESSION['PAGE'];
				}else{$limit = 10;}
				
				$base_url = base_url().'printersettings/ManagePrintersettings';
				
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
				
				$page_data['resultData']  = $result= $data =$this->printersettings_model->getManagePrintersettings($limit, $offset);
				
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
	
	public function getPrintsections()
	{
		$data =  $this->db->query("select org_print_section_types.* from org_print_section_types
					order by org_print_section_types.type_name asc
					")->result();
		echo json_encode($data);
	}

	function ajaxDeletePrinter()
	{
		$header_id = isset($_POST["header_id"]) ? $_POST["header_id"] : 0;
		$line_id = isset($_POST["line_id"]) ? $_POST["line_id"] : 0;
		
		if($header_id > 0 && $line_id > 0)
		{
			$this->db->where('header_id', $header_id);
			$this->db->where('line_id', $line_id);
			$this->db->delete('org_print_count_line');	
			echo "1";
		}
		exit;
	}

	function ajaxBranchPrinterStatus($type = '', $id = '', $status = '')
    {
		switch($type)
		{
			case "status": #Block & Unblock
			
				if($status == 1){
					$data['active_flag'] = 'Y';
					$data['inactive_date'] = NULL;
					$succ_msg = 'Printer status activated successfully!';
				}else{
					$data['active_flag'] = 'N';
					$data['inactive_date'] = $this->date_time;
					$succ_msg = 'Printer status inactivated successfully!';
				}

				$this->db->where('line_id', $id);
				$this->db->update('org_print_count_line', $data);

				echo ($succ_msg);
				exit;
			break;
		}
	}

    function viewPrintersettings($id = '')
    {
        if (empty($this->user_id)) {
            redirect(base_url() . 'admin/adminLogin', 'refresh');
        }
    
        $page_data['id'] = $id;
        
        $page_data['manage_settings'] = $page_data['Setups'] = 1;

		$page_data['data'] = $this->printersettings_model->getRecord($id);
				
		foreach ($page_data['data'] as $key) 
		{
			$header_id = $key->header_id;
			$page_data['menuitems'] = $this->printersettings_model->getMenuitems($header_id);	
		}
        
        $page_data['page_name']  = 'printer_settings/viewPrintersettings';
        $page_data['page_title'] = 'View Branch Printers';

		$this->load->view($this->adminTemplate, $page_data);
    }
}
?>
