<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Locations extends CI_Controller 
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
	
	function manageLocations($type = '', $id = '', $status = '')
    {
		if (empty($this->user_id))
        {
			redirect(base_url() . 'admin/adminLogin', 'refresh');
		}
	
		$page_data['type'] = $type;
		$page_data['id'] = $id;
		
		$page_data['setups'] = 1;
		$page_data['page_name']  = 'locations/manageLocations';
		$page_data['page_title'] = 'Locations';
		
		switch(true)
		{
			case ($type == "add"): #Add
				if($_POST)
				{
					$data['location_name'] = $this->input->post('location_name');
					
					$data['country_id'] = $this->input->post('country_id');
					$data['state_id'] = $this->input->post('state_id');
					$data['city_id'] = $this->input->post('city_id');

					$data['address1'] = $this->input->post('address1');
					$data['address2'] = $this->input->post('address2');
					$data['address3'] = $this->input->post('address3');

					$data['postal_code'] = $this->input->post('postal_code');
				
					$data['active_flag'] = $this->active_flag;
					$data['start_date'] = !empty($_POST["start_date"]) ? date("Y-m-d",strtotime($_POST["start_date"])) : NULL;
					$data['end_date'] = !empty($_POST["end_date"]) ? date("Y-m-d",strtotime($_POST["end_date"])) : NULL;
					$data['created_by'] = $this->user_id;
					$data['created_date'] = $this->date_time;
                    $data['last_updated_by'] = $this->user_id;
                    $data['last_updated_date'] = $this->date_time;
					
					# exist start here
					$chkExist = $this->db->query("select location_name from loc_location_all 
						where location_name like '".serchFilter($data['location_name'])."'
							")->result_array();

					if(count($chkExist) > 0)
					{
						foreach($chkExist as $existValue)
						{
							$location_name = $existValue["location_name"];

							if($location_name == $data['location_name'])
							{
								$this->session->set_flashdata('error_message' , " Location name already exist!");
								redirect(base_url() . 'locations/manageLocations/add', 'refresh');
							}
						}
					}		
					# exist end here
					
					$this->db->insert('loc_location_all', $data);
					$id = $this->db->insert_id();
					
					if($id !="")
					{
						$this->session->set_flashdata('flash_message' , "Location added Successfully!");
						redirect(base_url() . 'locations/manageLocations', 'refresh');
					}
				}
			break;
			
			case ($type == "edit" || $type == "view" ): #edit
				$page_data['edit_data'] = $this->db->get_where('loc_location_all', array('location_id' => $id))
										->result_array();
				if($_POST)
				{
					$data['location_name'] = $this->input->post('location_name');
					
					$data['country_id'] = $this->input->post('country_id');
					$data['state_id'] = $this->input->post('state_id');
					$data['city_id'] = $this->input->post('city_id');

					$data['address1'] = $this->input->post('address1');
					$data['address2'] = $this->input->post('address2');
					$data['address3'] = $this->input->post('address3');

					$data['postal_code'] = $this->input->post('postal_code');
				
					$data['start_date'] = !empty($_POST["start_date"]) ? date("Y-m-d",strtotime($_POST["start_date"])) : NULL;
					$data['end_date'] = !empty($_POST["end_date"]) ? date("Y-m-d",strtotime($_POST["end_date"])) : NULL;
					$data['last_updated_by'] = $this->user_id;
                    $data['last_updated_date'] = $this->date_time;
					
					# exist start here
					$chkExist = $this->db->query("select location_name from loc_location_all 
						where 
							location_name like '".serchFilter($data['location_name'])."' and
								location_id !='".$id."'
							")->result_array();


					if(count($chkExist) > 0)
					{	{
					
						foreach($chkExist as $existValue)
							$location_name = $existValue["location_name"];

							if($location_name == $data['location_name'])
							{
								$this->session->set_flashdata('error_message' , "Location name already exist!");
								redirect(base_url() . 'locations/manageLocations/edit/'.$id, 'refresh');
							}
						}
					}		
							
					
					# exist end here

					//print_r($data);exit;

					$this->db->where('location_id', $id);
					$result = $this->db->update('loc_location_all', $data);
					
					if($result)
					{
						$this->session->set_flashdata('flash_message' , "Location updated successfully!");
						redirect(base_url() . 'locations/manageLocations', 'refresh');
					}
				}
			break;
			
			case ($type == "status"): #Block & Unblock
				if($status == "Y")
				{
					$data['active_flag'] = "Y";
					$data['last_updated_by'] = $this->user_id;
                    $data['last_updated_date'] = $this->date_time;
					$data['inactive_date'] = NULL;
					$data['end_date'] = NULL;
					$succ_msg = 'Location InActive successfully!';
				}
				else
				{
					$data['active_flag'] = "N";
					$data['last_updated_by'] = $this->user_id;
                    $data['last_updated_date'] = $this->date_time;
					$data['inactive_date'] = $this->date_time;
                    $data['end_date'] = $this->date;
					$succ_msg = 'Location Active successfully!';
				}

				$this->db->where('location_id', $id);
				$this->db->update('loc_location_all', $data);

				$this->session->set_flashdata('flash_message' , $succ_msg);
				redirect($_SERVER['HTTP_REFERER'], 'refresh');
			break;
			
			default : #Manage
				$totalResult = $this->locations_model->getLocations("","",$this->totalCount);
				$page_data["totalRows"] = $totalRows = count($totalResult);
	
				if(!empty($_SESSION['PAGE']))
				{$limit = $_SESSION['PAGE'];
				}else{$limit = 10;}
				$active_flag = isset($_GET['active_flag']) ? $_GET['active_flag'] :NULL;
				$redirectURL = 'locations/manageLocations?keywords=&active_flag='.$active_flag;

				if (!empty($_GET['keywords']) || !empty($_GET['active_flag']) ) 
				{
					$base_url = base_url('locations/manageLocations?keywords='.$_GET['keywords'].'&active_flag='.$_GET['active_flag']);
				} else {
					$base_url = base_url('locations/manageLocations?keywords=&active_flag=');
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
				
				$page_data['resultData']  = $result = $this->locations_model->getLocations($limit, $offset,$this->pageCount);
				
				#show start and ending Count
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

}
?>
