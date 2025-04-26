<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Warehouse extends CI_Controller 
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
	
	function ManageWarehouse($type = '', $id = '', $status = '')
    {
		if (empty($this->user_id))
        {
			redirect(base_url() . 'admin/adminLogin', 'refresh');
		}
	
		$page_data['type'] = $type;
		$page_data['id'] = $id;
		
		$page_data['setups'] = 1;
		$page_data['page_name']  = 'warehouse/ManageWarehouse';
		$page_data['page_title'] = 'Warehouse';
		
		switch(true)
		{
			case ($type == "add"): #add
				if($_POST)
				{
					#Warehouse details
					$data['warehouse_code'] = $this->input->post('warehouse_code');
					$data['warehouse_name'] = $this->input->post('warehouse_name');
					# Warehouse exist start here
					$chkExistWarehouse = $this->db->query("select warehouse_id from warehouse
						where 
							warehouse_code='".$data['warehouse_code']."' or
								warehouse_name='".$data['warehouse_name']."'
							")->result_array();
							
					if(count($chkExistWarehouse) > 0)
					{
						$this->session->set_flashdata('error_message' , " Warehouse Code / name already exist!");
						redirect(base_url() . 'warehouse/ManageWarehouse/add', 'refresh');
					}
					# Warehouse exist end here
					
					$data['email'] = $this->input->post('email'); 
					$data['address_1'] = $this->input->post('address_1'); 
					$data['address_2'] = $this->input->post('address_2'); 
					$data['address_3'] = $this->input->post('address_3'); 
					$data['mobile_number'] = isset($_POST['mobile_number']) ? $_POST['mobile_number'] :"";
					$data['country_id'] = $this->input->post('country_id');
					$data['state_id'] = $this->input->post('state_id');
					$data['city_id'] = $this->input->post('city_id');
					$data['postal_code'] = $this->input->post('postal_code');
					//$data['location'] = $this->input->post('location');
					$data['branch_id'] = $this->input->post('branch_id');
					//$data['warehouse_status'] = 1;
					$data['created_by'] = $this->user_id;
					//$data['created_date'] = strtotime(date('d-m-Y h:i:s a',time()));
					$data['created_date'] = $this->date_time;
                    $data['last_updated_by'] = $this->user_id;
                    $data['last_updated_date'] = $this->date_time;
					//$data['active_flag'] = $this->input->post('active_flag');
					
					$this->db->insert('warehouse', $data);
					$id = $this->db->insert_id();
					
					if($id !="")
					{	
						$this->session->set_flashdata('flash_message' , "Warehouse added Successfully!");
						redirect(base_url() . 'warehouse/ManageWarehouse', 'refresh');
					}
				}
			break;
			
			case ($type == "edit" || $type == "view"): #editexit;
				$page_data['edit_data'] = $this->db->get_where('warehouse', array('warehouse_id' => $id))
										->result_array();
										
				if($_POST)
				{
					$data['warehouse_name'] = $this->input->post('warehouse_name');
					$data['warehouse_code'] = $this->input->post('warehouse_code');
					$data['country_id'] = $this->input->post('country_id');
					$data['state_id'] = $this->input->post('state_id');
					$data['city_id'] = $this->input->post('city_id');
					$data['last_updated_by'] = $this->user_id;
                    $data['last_updated_date'] = $this->date_time;
					//$data['active_flag'] = $this->input->post('active_flag');
					# Warehouse exist start here
					$chkExistWarehouse = $this->db->query("select warehouse_id,warehouse_code,warehouse_name from warehouse
						where 
							warehouse_id !='".$id."' and (
								warehouse_code='".$data['warehouse_code']."' or
									warehouse_name='".$data['warehouse_name']."' )
							")->result_array();
							
					if(count($chkExistWarehouse) > 0)
					{
						$this->session->set_flashdata('error_message' , " Warehouse Code / name  already exist!");
						redirect(base_url() . 'warehouse/ManageWarehouse/edit/'.$id, 'refresh');
					}
					# Warehouse exist end here
					
					$data['email'] = $this->input->post('email'); 
					$data['address_1'] = $this->input->post('address_1'); 
					$data['address_2'] = $this->input->post('address_2');
					$data['address_3'] = $this->input->post('address_3');  
					$data['mobile_number'] = isset($_POST['mobile_number']) ? $_POST['mobile_number'] :"";
					$data['postal_code'] = $this->input->post('postal_code');
					$data['branch_id'] = $this->input->post('branch_id');
					//$data['location'] = $this->input->post('location');
					
					$this->db->where('warehouse_id', $id);
					$result = $this->db->update('warehouse', $data);
					
					if($result)
					{
						$this->session->set_flashdata('flash_message' , "Warehouse updated Successfully!");
						redirect(base_url() . 'warehouse/ManageWarehouse', 'refresh');
					}
				}
			break;
			
			/* case "delete": #Delete
				$this->db->where('warehouse_id', $id);
				$this->db->delete('warehouse');
				
				$this->session->set_flashdata('flash_message' , "Warehouse deleted successfully!");
				redirect(base_url() . 'warehouse/ManageWarehouse', 'refresh');
			break; */
			
			case ($type == "status"):
				if($status == 'Y'){
					$data['active_flag'] = 'Y';
					$data['last_updated_by'] = $this->user_id;
					$data['last_updated_date'] = $this->date_time;
					$data['inactive_date'] = NULL;
					$succ_msg = 'Warehouse Active successfully!';
				}else{
					$data['active_flag'] = 'N';
					$data['last_updated_by'] = $this->user_id;
					$data['last_updated_date'] = $this->date_time;
					$data['inactive_date'] = $this->date_time;
					$succ_msg = 'Warehouse InActive successfully!';
				}
				$this->db->where('warehouse_id', $id);
				$this->db->update('warehouse', $data);
				$this->session->set_flashdata('flash_message' , $succ_msg);
				redirect($_SERVER['HTTP_REFERER'], 'refresh');
			break;
			
			case ($type == "export"):
				$data = $this->db->query("select
				sto_warehouses.warehouse_name,
				sto_warehouses.email,
				sto_warehouses.mobile_number,
				sto_warehouses.country_id,
				sto_warehouses.state_id,
				sto_warehouses.city_id,
				sto_warehouses.postal_code,
				sto_warehouses.address_1,
				sto_warehouses.address_2
			
				
				from warehouse as sto_warehouses
				order by sto_warehouses.warehouse_id desc")->result_array();
				
				#$data[] = array('f_name'=> "Nishit", 'l_name'=> "patel", 'mobile'=> "999999999", 'gender'=> "male");
				
				header("Content-type: application/csv");
				header("Content-Disposition: attachment; filename=\"Warehouse".".csv\"");
				header("Pragma: no-cache");
				header("Expires: 0");

				$handle = fopen('php://output', 'w');
				fputcsv($handle, array("S.No","Warehouse Name","Email","Mobile Number","Country","State","City","Postel Code","Address1","Address2","Status"));
				$cnt=1;
				
				foreach ($data as $row) 
				{
					$narray=array(
					
							$cnt,
							$row["warehouse_name"],
							$row["email"],
							$row["mobile_number"],
							$row["country_id"],
							$row["state_id"],
							$row["city_id"],
							$row["postal_code"],
							$row["address_1"],
							$row["address_2"],
							//$row["warehouse_status"]
						);
					fputcsv($handle, $narray);
					$cnt++;
				}
				fclose($handle);
				exit;
			break;
			
			default : #Manage
				$totalResult = $this->warehouse_model->getManageWarehouse("","",$this->totalCount);
				$page_data["totalRows"] = $totalRows = count($totalResult);

				if(!empty($_SESSION['PAGE']))
				{$limit = $_SESSION['PAGE'];
				}else{$limit = 10;}

				
				$active_flag = isset($_GET['active_flag']) ? $_GET['active_flag'] :NULL;
				$redirectURL = 'warehouse/ManageWarehouse?keywords=&active_flag='.$active_flag;



				if (!empty($_GET['keywords']) || !empty($_GET['active_flag'])) {
					$base_url = base_url('warehouse/ManageWarehouse?keywords='.$_GET['keywords'].'&active_flag=Y'.$_GET['active_flag']);
				} else {
					$base_url = base_url('warehouse/ManageWarehouse?keywords=&active_flag=Y');
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
				
				$page_data['resultData']  = $result= $data =$this->warehouse_model->getManageWarehouse($limit, $offset,$this->pageCount);
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
