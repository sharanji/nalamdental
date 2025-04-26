<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Printsection extends CI_Controller 
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
	
	function managePrintsection($type = '', $id = '', $status = '')
    {
		if (empty($this->user_id))
        {
			redirect(base_url() . 'admin/adminLogin', 'refresh');
		}
	
		$page_data['type'] = $type;
		$page_data['id'] = $id;
		
		$page_data['manage_settings'] = $page_data['Setups'] = 1;
		
		$page_data['page_name']  = 'printsection/managePrintsection';
		$page_data['page_title'] = 'Printer Section';
		
		switch($type)
		{
			case "add": #View
				if($_POST)
				{
					$data['print_type'] = $this->input->post('print_type');
					$data['type_name'] = $this->input->post('type_name');
					
					#exist start here
					$chkExistUom = $this->db->query("select type_id from org_print_section_types
					where 
					type_name='".$data['type_name']."' 
					")->result_array();
							
					if(count($chkExistUom) > 0)
					{
						$this->session->set_flashdata('error_message' , " Section already exist!");
						redirect(base_url() . 'printsection/ManagePrintsection/add', 'refresh');
					}
					#exist end here

					$data['active_flag'] = $this->active_flag;
					$data['created_by'] = $this->user_id;
					$data['created_date'] = $this->date_time;
                    $data['last_updated_by'] = $this->user_id;
                    $data['last_updated_date'] = $this->date_time;
					
					$this->db->insert('org_print_section_types', $data);
					$id = $this->db->insert_id();
					
					if($id !="")
					{	
						$this->session->set_flashdata('flash_message' , "Print section saved successfully!");
						redirect(base_url() . 'printsection/ManagePrintsection/edit/'.$id, 'refresh');
					}
				}
			break;
			
			case "edit": #edit
				$page_data['edit_data'] = $this->db->get_where('org_print_section_types', array('type_id' => $id))
										->result_array();
				if($_POST)
				{
					$data['print_type'] = $this->input->post('print_type');
					$data['type_name'] = $this->input->post('type_name');
					$data['start_date'] = !empty($_POST["start_date"]) ? date("Y-m-d",strtotime($_POST["start_date"])) : NULL;
					$data['end_date'] = !empty($_POST["end_date"]) ? date("Y-m-d",strtotime($_POST["end_date"])) : NULL;
					$data['last_updated_by'] = $this->user_id;
                    $data['last_updated_date'] = $this->date_time;
					
					$chkExistSection = $this->db->query("select type_id from org_print_section_types
					where 
						1=1
						and type_id !='".$id."' 
						and type_name='".$data['type_name']."'
						and print_type='".$data['print_type']."'
					")->result_array();

					if(count($chkExistSection) > 0)
					{
						$this->session->set_flashdata('error_message' , " Print Section already exist!");
						redirect(base_url() . 'printsection/ManagePrintsection/edit/'.$id, 'refresh');
					}
					
					$data['last_updated_by'] = $this->user_id;
                    $data['last_updated_date'] = $this->date_time;

					$this->db->where('type_id', $id);
					$result = $this->db->update('org_print_section_types', $data);
					
					if($result)
					{
						$this->session->set_flashdata('flash_message' , "Print section saved successfully!");
						redirect(base_url() . 'printsection/ManagePrintsection/edit/'.$id, 'refresh');
					}
				}
			break;
			
			case "delete": #Delete
				$this->db->where('type_id', $id);
				$this->db->delete('org_print_section_types');
				
				$this->session->set_flashdata('flash_message' , "Print Section deleted successfully!");
				redirect(base_url() . 'printsection/ManagePrintsection', 'refresh');
			break;
			
			case "status": #Block & Unblock
				if($status == 'Y'){
					$data['active_flag'] = 'Y';
					$data['last_updated_by'] = $this->user_id;
					$data['last_updated_date'] = $this->date_time;
					$data['inactive_date'] = NULL;
					$succ_msg = 'Printsection active successfully!';
				}else{
					$data['active_flag'] = 'N';
					$data['last_updated_by'] = $this->user_id;
					$data['last_updated_date'] = $this->date_time;
					$data['inactive_date'] = $this->date_time;
					$succ_msg = 'Printsection inctive successfully!';
				}
			
				$this->db->where('type_id', $id);
				$this->db->update('org_print_section_types', $data);
				$this->session->set_flashdata('flash_message' , $succ_msg);
				redirect(base_url() . 'printsection/ManagePrintsection', 'refresh');
			break;
			
			case "export":
						
				$data = $this->db->query("select
				org_print_section_types.type_name,
				
				
				from org_print_section_types 
				order by type_id desc")->result_array();
				
				#$data[] = array('f_name'=> "Nishit", 'l_name'=> "patel", 'mobile'=> "999999999", 'gender'=> "male");
				
				header("Content-type: application/csv");
				header("Content-Disposition: attachment; filename=\"Printsection".".csv\"");
				header("Pragma: no-cache");
				header("Expires: 0");

				$handle = fopen('php://output', 'w');
				fputcsv($handle, array("S.No","type_name"));
				$cnt=1;
				
				foreach ($data as $row) 
				{
					$narray=array(
					
							$cnt,
							$row["type_name"],
						);
					fputcsv($handle, $narray);
					$cnt++;
				}
				fclose($handle);
				exit;
			break;
			
			default : #Manage
				$page_data["totalRows"] = $totalRows = $this->printsection_model->getManagePrintsectionCount();#
	
				if(!empty($_SESSION['PAGE']))
				{$limit = $_SESSION['PAGE'];
				}else{$limit = 10;}
				
				$base_url = base_url().'printsection/ManagePrintsection';
				
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
				
				$page_data['resultData']  = $result= $data =$this->printsection_model->getManagePrintsection($limit, $offset);
				
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
