<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Organization extends CI_Controller 
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
	
	function manageOrganization($type = '', $id = '', $status = '')
    {
		if (empty($this->user_id))
        {
			redirect(base_url() . 'admin/adminLogin', 'refresh');
		}
	
		$page_data['type'] = $type;
		$page_data['id'] = $id;
		
		$page_data['setups'] = 1;
		$page_data['page_name']  = 'organization/manageOrganization';
		$page_data['page_title'] = 'Organizations';
		
		switch(true)
		{
			case ($type == "add"): #Add
				if($_POST)
				{
					$data['organization_code'] = $this->input->post('organization_code');
					$data['organization_name'] = $this->input->post('organization_name');
					$data['organization_description'] = $this->input->post('organization_description');
					$data['location_id'] = $this->input->post('location_id');

					/* $data['country_id'] = $this->input->post('country_id');
					$data['state_id'] = $this->input->post('state_id');
					$data['city_id'] = $this->input->post('city_id');

					$data['address1'] = $this->input->post('address1');
					$data['address2'] = $this->input->post('address2');
					$data['address3'] = $this->input->post('address3');

					$data['postal_code'] = $this->input->post('postal_code'); */
				
					$data['active_flag'] = $this->active_flag;
					$data['start_date'] = !empty($_POST["start_date"]) ? date("Y-m-d",strtotime($_POST["start_date"])) : NULL;
					$data['end_date'] = !empty($_POST["end_date"]) ? date("Y-m-d",strtotime($_POST["end_date"])) : NULL;
					$data['created_by'] = $this->user_id;
					$data['created_date'] = $this->date_time;
                    $data['last_updated_by'] = $this->user_id;
                    $data['last_updated_date'] = $this->date_time;
					
					# exist start here
					$chkExist = $this->db->query("select organization_code from org_organizations 
						where organization_code like'".serchFilter($data['organization_code'])."'
							")->result_array();

					if(count($chkExist) > 0)
					{
						foreach($chkExist as $existValue)
						{
							$organization_code = $existValue["organization_code"];

							if($organization_code == $data['organization_code'])
							{
								$this->session->set_flashdata('error_message' , " Organization  already exist!");
								redirect(base_url() . 'organization/manageOrganization/add', 'refresh');
							}
						}
					}		
					
					
					# exist end here
					
					$this->db->insert('org_organizations', $data);
					$id = $this->db->insert_id();
					
					if($id !="")
					{
						if( !empty($_FILES['organization_logo']['name']) )
						{  
							$data_1['organization_logo'] = $productName = $_FILES['organization_logo']['name'];
							move_uploaded_file($_FILES['organization_logo']['tmp_name'], 'uploads/organization_logo/'.$productName);
						
							$this->db->where('organization_id', $id);
							$result = $this->db->update('org_organizations', $data_1);
						}
						
						$this->session->set_flashdata('flash_message' , "Organization added Successfully!");
						redirect(base_url() . 'organization/manageOrganization', 'refresh');
					}
				}
			break;
			
			case ($type == "edit" || $type == "view" ): #edit
				$page_data['edit_data'] = $this->db->get_where('org_organizations', array('organization_id' => $id))
										->result_array();
				if($_POST)
				{
					$data['organization_code'] = $this->input->post('organization_code');
					$data['organization_name'] = $this->input->post('organization_name');
					$data['organization_description'] = $this->input->post('organization_description');
					$data['location_id'] = $this->input->post('location_id');
					$data['active_flag'] = $this->active_flag;

					/* $data['country_id'] = $this->input->post('country_id');
					$data['state_id'] = $this->input->post('state_id');
					$data['city_id'] = $this->input->post('city_id');

					$data['address1'] = $this->input->post('address1');
					$data['address2'] = $this->input->post('address2');
					$data['address3'] = $this->input->post('address3');
					$data['postal_code'] = $this->input->post('postal_code');
				 */
					$data['start_date'] = !empty($_POST["start_date"]) ? date("Y-m-d",strtotime($_POST["start_date"])) : NULL;
					$data['end_date'] = !empty($_POST["end_date"]) ? date("Y-m-d",strtotime($_POST["end_date"])) : NULL;
					$data['last_updated_by'] = $this->user_id;
                    $data['last_updated_date'] = $this->date_time;
					
					# exist start here
					$chkExist = $this->db->query("select organization_code from org_organizations 
						where 
							organization_code like'".serchFilter($data['organization_code'])."' and
								organization_id !='".$id."' ")->result_array();
							

					if(count($chkExist) > 0)
					{	{
					
						foreach($chkExist as $existValue)
							$organization_code = $existValue["organization_code"];

							if($organization_code == $data['organization_code'])
							{
								$this->session->set_flashdata('error_message' , "Organization already exist!");
								redirect(base_url() . 'organization/manageOrganization/edit/'.$id, 'refresh');
							}
						}
					}		
					
					# exist end here

					$this->db->where('organization_id', $id);
					$result = $this->db->update('org_organizations', $data);
					
					if($result)
					{
						/* if( !empty($_FILES['product_image']['name']) )
						{  
							$data_1['product_image'] = $productName = $_FILES['product_image']['name'];
							move_uploaded_file($_FILES['product_image']['tmp_name'], 'uploads/products/'.$productName);
						
							$this->db->where('product_id', $id);
							$result = $this->db->update('products', $data_1);
						} */

						if( !empty($_FILES['organization_logo']['name']) )
						{  
							$data_1['organization_logo'] = $productName = $_FILES['organization_logo']['name'];
							move_uploaded_file($_FILES['organization_logo']['tmp_name'], 'uploads/organization_logo/'.$productName);
						
							$this->db->where('organization_id', $id);
							$result = $this->db->update('org_organizations', $data_1);
						}

						$this->session->set_flashdata('flash_message' , "Organization updated successfully!");
						redirect(base_url() . 'organization/manageOrganization', 'refresh');
					}
				}
			break;
			
			case ($type == "status"): #Block & Unblock
				if($status == 'Y')
				{
					$data['active_flag'] = 'Y';
					$data['last_updated_by'] = $this->user_id;
                    $data['last_updated_date'] = $this->date_time;
					$data['end_date'] = NULL;
					$data['inactive_date'] = NULL;
					$succ_msg = 'Organization active successfully!';
				}
				else
				{
					$data['active_flag'] = 'N';
					$data['last_updated_by'] = $this->user_id;
                    $data['last_updated_date'] = $this->date_time;
					$data['inactive_date'] = $this->date;
                    $data['end_date'] = $this->date;
					$succ_msg = 'Organization inctive successfully!';
				}

				$this->db->where('organization_id', $id);
				$this->db->update('org_organizations', $data);
				$this->session->set_flashdata('flash_message' , $succ_msg);
				redirect($_SERVER['HTTP_REFERER'], 'refresh');
			break;
			
			default : #Manage
				$totalResult = $this->organization_model->getOrganizations("","",$this->totalCount);
				$page_data["totalRows"] = $totalRows = count($totalResult);
	
				if(!empty($_SESSION['PAGE']))
				{$limit = $_SESSION['PAGE'];
				}else{$limit = 10;}

				
				$active_flag = isset($_GET['active_flag']) ? $_GET['active_flag'] :NULL;
				$redirectURL = 'organization/manageOrganization??keywords=&active_flag='.$active_flag;

				if (!empty($_GET['keywords']) || !empty($_GET['active_flag'])) {
					$base_url = base_url('organization/manageOrganization?keywords='.$_GET['keywords'].'&active_flag='.$_GET['active_flag']);
				} else {
					$base_url = base_url('organization/manageOrganization?keywords=&active_flag=');
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
				
				$page_data['resultData']  = $result= $this->organization_model->getOrganizations($limit, $offset,$this->pageCount);
				if(isset($_GET['per_page']) && $_GET['per_page'] > 1 && count($result) == 0 )
				{
					redirect(base_url().$redirectURL, 'refresh');
				}
				
				#Download CSV Start
				$export = isset($_GET['export']) ? $_GET['export']:"";

				if(!empty($export))
				{
					$date = date('d_M_Y');
					header("Content-type: application/csv");
					header("Content-Disposition: attachment; filename=\"organizations_".$date.".csv\"");
					header("Pragma: no-cache");
					header("Expires: 0");
					
					$handle = fopen('php://output', 'w');
					fputcsv($handle, array("S.No","Organization Code","Organization Name"));
					$cnt=1;
					foreach($totalResult as $row) 
					{
						$narray=array(
							$cnt,
							$row['organization_code'],
							ucfirst($row['organization_name'])
						);
						fputcsv($handle, $narray);
						$cnt++;
					}
					fclose($handle);
					exit;
				}
				#Download CSV End

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

	public function ajaxSelectCompleteAddress()
	{
		$location_id = isset($_POST["location_id"]) ? $_POST["location_id"] :0;
		$completeAddress = $totalRows = $this->organization_model->ajaxSelectCompleteAddress($location_id);
		echo $completeAddress;
		exit;
	}

}
?>
