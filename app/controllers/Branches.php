<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Branches extends CI_Controller 
{
	function __construct()
	{
		parent::__construct();
		$this->load->database();
        $this->load->library('session');
      
        #Cache Control
		$this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
		$this->output->set_header('Pragma: no-cache');
		$this->output->set_header('Pragma: no-cache');
	}

	function ManageBranches($type = '', $id = '', $status = '', $status1 = '', $status2 = '')
    {
		if (empty($this->user_id))
        {
			redirect(base_url() . 'admin/adminLogin', 'refresh');
		}
	
		$page_data['type'] = $type;
		$page_data['id'] = $id;
		$page_data['status'] = $status;
		$page_data['status1'] = $status1;
		
		$page_data['ManageBranches'] = $page_data['Setups'] = 1;
		
		$page_data['page_name']  = 'branches/ManageBranches';
		$page_data['page_title'] = 'Manage Branch';
		
		switch(true)
		{
			case ($type == "add"): #add
				if($_POST)
				{
					$data['branch_code'] = $this->input->post('branch_code');
					$existBranchCode = $this->db->query("select branch_id from branch where branch_code='".$data['branch_code']."' ")->result_array();
					if(count($existBranchCode) > 0 )
					{
						$this->session->set_flashdata('error_message' , "Sorry! Already exist branch code!");
						redirect(base_url() . 'branches/ManageBranches', 'refresh');
					}

					$data['organization_id']	= $this->input->post('organization_id');
					$data['branch_code'] = $this->input->post('branch_code');
					$data['branch_name'] = $this->input->post('branch_name');
					$data['mobile_number'] = $this->input->post('mobile_number');
					$data['alter_mobile_number'] = $this->input->post('alter_mobile_number');
					$data['email'] = $this->input->post('email');
					$data['location_id'] = $this->input->post('location_id');
					$data['map_location'] = $this->input->post('map_location');
					$latLong = getGeoLatLong($data['map_location']);
					$data['latitude'] = $latLong['latitude'];
					$data['longitude'] = $latLong['longitude'];
					$data['minimum_order_value'] = $this->input->post('minimum_order_value');

					$data['break_fast_from'] = $this->input->post('break_fast_from');
					$data['break_fast_to'] = $this->input->post('break_fast_to');
					$data['lunch_from'] = $this->input->post('lunch_from');
					$data['lunch_to'] = $this->input->post('lunch_to');
					$data['dinner_from'] = $this->input->post('dinner_from');
					$data['dinner_to'] = $this->input->post('dinner_to');

					$data['delivery_distance'] = $this->input->post('delivery_distance');
					$data['auto_print_status'] = $this->input->post('auto_print_status');
					$data['order_confirm_print_status'] = $this->input->post('order_confirm_print_status');
					$data['captain_canel_item_status'] = $this->input->post('captain_canel_item_status');
					//$data['zone_status'] = $this->input->post('zone_status');
					$data['active_flag'] = $this->active_flag;
					$data['created_by'] = $this->user_id;
					$data['created_date'] = $this->date_time;
                    $data['last_updated_by'] = $this->user_id;
                    $data['last_updated_date'] = $this->date_time;
					
					$this->db->insert('branch', $data);
					$id = $this->db->insert_id();
					

					if($id !="")
					{
						if( !empty($_FILES['branch_image']['name']) )
						{  
							move_uploaded_file($_FILES['branch_image']['tmp_name'], 'uploads/branches/'.$id.'.png');
						}
						
						if( isset($_FILES['branch_image_mobile']['name']) && !empty($_FILES['branch_image_mobile']['name']) )
						{  
							move_uploaded_file($_FILES['branch_image_mobile']['tmp_name'], 'uploads/branches/mobile_branches/'.$id.'.png');
						}
					
						$this->session->set_flashdata('flash_message' , "Branch created Successfully!");
						redirect(base_url() . 'branches/ManageBranches/edit/'.$id, 'refresh');
					}
				}
			break;
			
			case ($type == "edit" || $type == "view"): #edit
				$page_data['edit_data'] = $this->db->get_where('branch', array('branch_id' => $id))
										->result_array();
										
				$data['branch_code'] = $this->input->post('branch_code');
				
				$existBranchCode = $this->db->query("select branch_id from branch where branch_code='".$data['branch_code']."' and branch_id !='".$id."'")->result_array();
				
				if(count($existBranchCode) >0 )
				{
					$this->session->set_flashdata('error_message' , "Sorry! Already exist branch code!");
					redirect(base_url() . 'branches/ManageBranches/edit/'.$id, 'refresh');
				}
				if($_POST)
				{
					$data['organization_id']	= $this->input->post('organization_id');
					$data['branch_code'] = $this->input->post('branch_code');
					$data['branch_name'] = $this->input->post('branch_name');
					$data['mobile_number'] = $this->input->post('mobile_number');
					$data['alter_mobile_number'] = $this->input->post('alter_mobile_number');
					$data['email'] = $this->input->post('email');
					$data['location_id'] = $this->input->post('location_id');
					$data['map_location'] = $this->input->post('map_location');
					$latLong = getGeoLatLong($data['map_location']);
					$data['latitude'] = $latLong['latitude'];
					$data['longitude'] = $latLong['longitude'];
					$data['minimum_order_value'] = $this->input->post('minimum_order_value');
					
					$data['break_fast_from'] = $this->input->post('break_fast_from');
					$data['break_fast_to'] = $this->input->post('break_fast_to');
					$data['lunch_from'] = $this->input->post('lunch_from');
					$data['lunch_to'] = $this->input->post('lunch_to');
					$data['dinner_from'] = $this->input->post('dinner_from');
					$data['dinner_to'] = $this->input->post('dinner_to');

					$data['delivery_distance'] = $this->input->post('delivery_distance');
					$data['auto_print_status'] = $this->input->post('auto_print_status');
					$data['order_confirm_print_status'] = $this->input->post('order_confirm_print_status');
					$data['captain_canel_item_status'] = $this->input->post('captain_canel_item_status');
                    $data['last_updated_by'] = $this->user_id;
                    $data['last_updated_date'] = $this->date_time;
					
					$this->db->where('branch_id', $id);
					$result = $this->db->update('branch', $data);
					
					if($result)
					{
						if( isset($_FILES['branch_image']['name']) && !empty($_FILES['branch_image']['name']) )
						{  
							move_uploaded_file($_FILES['branch_image']['tmp_name'], 'uploads/branches/'.$id.'.png');
						}

						if( isset($_FILES['branch_image_mobile']['name']) && !empty($_FILES['branch_image_mobile']['name']) )
						{  
							move_uploaded_file($_FILES['branch_image_mobile']['tmp_name'], 'uploads/branches/mobile_branches/'.$id.'.png');
						}

						$this->session->set_flashdata('flash_message' , "Branch updated successfully!");
						redirect(base_url() . 'branches/ManageBranches/edit/'.$id, 'refresh');
					}
				}
			break;
			
			/* case "view": #view
				$page_data['edit_data'] = $this->db->get_where('branch', array('branch_id' => $id))
										->result_array();
			break; */
			
			/* case "delete": #Delete
				$this->db->where('branch_id', $id);
				$this->db->delete('branch');
				$this->session->set_flashdata('flash_message' , "Branch deleted successfully!");
				redirect(base_url() . 'branches/ManageBranches', 'refresh');
			break; */
			
			case ($type == "status"): #Block & Unblock
				if($status == "Y")
				{
					$data['active_flag'] = "Y";
					$data['last_updated_by'] = $this->user_id;
					$data['last_updated_date'] = $this->date_time;
					$data['inactive_date'] = NULL;
					$succ_msg = 'Branches active successfully!';
				}
				else
				{
					$data['active_flag'] = "N";
					$data['last_updated_by'] = $this->user_id;
					$data['last_updated_date'] = $this->date_time;
					$data['inactive_date'] = $this->date_time;
					$succ_msg = 'Branches  inactive successfully!';
				}
				$this->db->where('branch_id', $id);
				$this->db->update('branch', $data);
				$this->session->set_flashdata('flash_message' , $succ_msg);
				redirect($_SERVER['HTTP_REFERER'], 'refresh');
			break;
			
			#zone starts
			case ($type == "zone"): #Block & Unblock
				if(isset($_POST['add']))
				{
					$data['branch_id'] = $id;
					$data['zone_name'] = ucfirst($this->input->post('zone_name'));

					$zonelatLong = getGeoLatLong($data['zone_name']);
					$data['latitude'] = $zonelatLong['latitude'];
					$data['longitude'] = $zonelatLong['longitude'];
					//$data['zone_status'] = 1;

					$this->db->insert('branch_zones', $data);
					$zoneID = $this->db->insert_id();

					if($zoneID)
					{
						$this->session->set_flashdata('flash_message' , "Zone created successfully!");
						redirect(base_url() . 'branches/ManageBranches/zone/'.$id, 'refresh');
					}
				}
				// else if(isset($_POST['update']))
				// {
				// 	$zone_id = $this->input->post('zone_id');
					
				// 	$data['zone_name'] = ucfirst($this->input->post('zone_name'));

				// 	$zonelatLong = getGeoLatLong($data['zone_name']);
				// 	$data['latitude'] = $zonelatLong['latitude'];
				// 	$data['longitude'] = $zonelatLong['longitude'];

				// 	$this->db->where('zone_id', $zone_id);
				// 	$this->db->update('vb_branch_zones', $data);

				// 	$this->session->set_flashdata('flash_message' , "Zone updated successfully!");
				// 	redirect($_SERVER['HTTP_REFERER'], 'refresh');
				// }
				
				$totalResult = $this->branches_model->getBranchZones("", "",$id,$this->totalCount);
				$page_data["totalRows"] = $totalRows = count($totalResult);

				if(!empty($_SESSION['PAGE']))
				{$limit = $_SESSION['PAGE'];
				}else{$limit = 10;}
				
				if (!empty($_GET['keywords'])) {
					$base_url = base_url('branches/ManageBranches/zone/'.$id.'?keywords='.$_GET['keywords']);
				} else {
					$base_url = base_url('branches/ManageBranches/zone/'.$id.'?keywords=');
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
				
				$page_data['resultData']  = $result= $this->branches_model->getBranchZones($limit, $offset,$id,$this->pageCount);
				
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
			
			case ($type == "zonedelete"):#Delete
				$this->db->where('zone_id', $id);
				$this->db->delete('vb_branch_zones');
				
				$this->session->set_flashdata('flash_message' , "Zone deleted successfully!");
				redirect($_SERVER['HTTP_REFERER'], 'refresh');
			break;

			case ($type == "zone_status"):#zone_status
				if($status == "Y")
				{
					$data['active_flag'] = "Y";
					$data['last_updated_by'] = $this->user_id;
					$data['last_updated_date'] = $this->date_time;
					$data['inactive_date'] = NULL;
					$succ_msg = 'Zone active successfully!';
				}
				else
				{
					$data['active_flag'] = "N";
					$data['last_updated_by'] = $this->user_id;
					$data['last_updated_date'] = $this->date_time;
					$data['inactive_date'] = $this->date_time;
					$succ_msg = 'Zone inactive successfully!';
				}

				$this->db->where('zone_id', $id);
				$this->db->update('branch_zones', $data);
				$this->session->set_flashdata('flash_message',$succ_msg);
				redirect($_SERVER['HTTP_REFERER'], 'refresh');
			break;
			#zone Ends
			
			#Contains Location start
			case ($type == "containsLocation"):#Contains Location
				if(isset($_POST['add']))
				{
					$data['zone_id'] = $id;
					$data['branch_id'] = $status;
				
					$data['latitude'] = trim($_POST['latitude']);
					$data['longitude'] = trim($_POST['longitude']);
					$data['contains_location_status'] = 1;

					$this->db->insert('vb_branch_zones_contains_location', $data);
					$zoneID = $this->db->insert_id();

					if($zoneID)
					{
						$this->session->set_flashdata('flash_message' , "Contains Location Added successfully!");
						redirect(base_url() . 'branches/ManageBranches/containsLocation/'.$id.'/'.$status, 'refresh');
					}
				}

				if(isset($_POST['update']))
				{
					$data['zone_id'] = $id;
					$data['branch_id'] = $status;
					$contains_location_id = $_POST['contains_location_id'];

					$data['latitude'] = trim($_POST['latitude']);
					$data['longitude'] = trim($_POST['longitude']);
					
					$this->db->where('zone_id', $id);
					$this->db->where('branch_id', $status);
					$this->db->where('contains_location_id', $contains_location_id);
					$this->db->update('vb_branch_zones_contains_location', $data);

					$this->session->set_flashdata('flash_message',"Contains Location updated successfully!");
					redirect(base_url() . 'branches/ManageBranches/containsLocation/'.$id.'/'.$status, 'refresh');
				}
				
				$page_data["totalRows"] = $totalRows = $this->branches_model->getBranchZonesContainsLocationCount($id,$status);

				if(!empty($_SESSION['PAGE']))
				{$limit = $_SESSION['PAGE'];
				}else{$limit = 10;}
				
				if (!empty($_GET['keywords'])) {
					$base_url = base_url('branches/ManageBranches/containsLocation/'.$id.'/'.$status.'?keywords='.$_GET['keywords']);
				} else {
					$base_url = base_url('branches/ManageBranches/containsLocation/'.$id.'/'.$status.'?keywords=');
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
				
				$page_data['resultData']  = $result= $this->branches_model->getBranchZonesContainsLocation($limit, $offset,$id,$status);
				
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
			
			case ($type == "zoneDeleteContinsLocation"):#Contains Location
				$this->db->where('contains_location_id', $status1);
				$this->db->delete('vb_branch_zones_contains_location');
				
				$this->session->set_flashdata('flash_message' , "Contains location deleted successfully!");
				redirect($_SERVER['HTTP_REFERER'], 'refresh');
			break;
			
			case ($type == "zone_status_ContinsLocation"):#Contains Location
				if($status2 == 1){
					$data['contains_location_status'] = 1;
					$succ_msg = 'Contains location active successfully!';
				}else{
					$data['contains_location_status'] = 0;
					$succ_msg = 'Contains location inactive successfully!';
				}
				$this->db->where('contains_location_id', $status1);
				$this->db->update('vb_branch_zones_contains_location', $data);
				$this->session->set_flashdata('flash_message' , $succ_msg);
				redirect($_SERVER['HTTP_REFERER'], 'refresh');
			break;

			case ($type == "importContainsLocation"):
				if($_FILES)
				{
					$filename = $_FILES["csv"]["tmp_name"];      
					
					if($_FILES["csv"]["size"] > 0)
					{
						$file = fopen($filename, "r");
						
						for ($lines = 0; $data = fgetcsv($file,1000,",",'"'); $lines++) 
						{
							if ($lines == 0) continue;
							
							/* $contains_location_status = 1;
							
							$sql = "INSERT INTO `vb_branch_zones_contains_location`(`latitude`, `longitude`,`zone_id`,`branch_id`,`contains_location_status`) 
									VALUES 
								('".$data[0]."','".$data[1]."','".$id."','".$status."','".$contains_location_status."')";
							$this->db->query($sql); */

							$checkQry = "select contains_location_id from vb_branch_zones_contains_location 
								where 
									zone_id ='".$id."' and 
										branch_id ='".$status."' and 
											latitude ='".trim($data[0])."' and 
												longitude ='".trim($data[1])."' ";
							$checkExist = $this->db->query($checkQry)->result_array();

                            if (count($checkExist) == 0) #Insert
							{
                                $postData =  array(
                                    "zone_id"                  => $id,
                                    "branch_id"                => $status,
                                    "latitude"                 => trim($data[0]),
                                    "longitude"                => trim($data[1]),
                                    "contains_location_status" => 1,
                                );

								$this->db->insert('vb_branch_zones_contains_location', $postData);
								$zoneID = $this->db->insert_id();
                            }
							else #Update
							{
								$postData =  array(
                                    "latitude"                 => trim($data[0]),
                                    "longitude"                => trim($data[1])
                                );

								$this->db->where('zone_id', $id);
								$this->db->where('branch_id', $status);
								$this->db->where('latitude', trim($data[0]));
								$this->db->where('longitude', trim($data[1]));
								$this->db->update('vb_branch_zones_contains_location', $postData);
							}
						}
						fclose($file); 
					}
					else
					{
						$this->session->set_flashdata('error_message' , "Contains location imported error!");
						redirect($_SERVER['HTTP_REFERER'], 'refresh');
					}
					$this->session->set_flashdata('flash_message' , "Contains location Imported successfully!");
					redirect($_SERVER['HTTP_REFERER'], 'refresh');
				}
			break;
			#Containg location end
			
			default : #Manage Branch

				/* if($_POST)
				{
					# Set Default banner
					$default_branch = $_POST["default_branch"];
					
					if($default_branch){
						$branch_update = $this->db->update("branch", array("default_branch" => 0), array("branch_id >" => 0));
					}
					$result = $this->db->update("branch", array("default_branch" => 1), array("branch_id" => $default_branch));
					
					$this->session->set_flashdata('flash_message' ,'Default banner updated successfully!');
					redirect($_SERVER['HTTP_REFERER'], 'refresh');
				} */

				if(isset($_POST['default_submit']) && isset($_POST['default_branch']))
				{
					$data['default_branch'] = 'N';
					$result = $this->db->update('branch', $data);
					
					if($result)
					{
						$branch_id = $_POST['default_branch'];
						$data_1['default_branch'] = 'Y';
						$this->db->where('branch_id', $branch_id);
						$result1 = $this->db->update('branch', $data_1);
					}
					$this->session->set_flashdata('flash_message' , 'Default branch updated successfully!');
					redirect($_SERVER['HTTP_REFERER'], 'refresh');
				}


				$totalResult = $this->branches_model->getManageBranch("","",$this->totalCount);
				$page_data["totalRows"] = $totalRows = count($totalResult);
	
				if(!empty($_SESSION['PAGE']))
				{
					$limit = $_SESSION['PAGE'];
				}else{
					$limit = 10;
				}

				
				$active_flag = isset($_GET['active_flag']) ? $_GET['active_flag'] :NULL;
				$redirectURL = 'branches/ManageBranches?keywords=&active_flag='.$active_flag;

				if (!empty($_GET['keywords']) || !empty($_GET['active_flag'])) {
					$base_url = base_url('branches/ManageBranches?keywords='.$_GET['keywords'].'&active_flag='.$_GET['active_flag']);
				} else {
					$base_url = base_url('branches/ManageBranches?keywords=&active_flag=');
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
				
				$page_data['resultData']  = $result = $this->branches_model->getManageBranch($limit, $offset, $this->pageCount);
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

	function ManagePosters($type = '', $id = '', $status = '')
    {
		if (empty($this->user_id))
        {
			redirect(base_url() . 'admin/adminLogin', 'refresh');
		}
	
		$page_data['type'] = $type;
		$page_data['id'] = $id;
		
		$page_data['ManageBranches'] = $page_data['Setups'] = 1;
		
		$page_data['page_name']  = 'branches/ManagePosters';
		$page_data['page_title'] = 'Manage Branch';
		
		switch($type)
		{
			case "add": #Add
				/*if($_POST)
				{
					$data['poster_title'] = $this->input->post('poster_title');
					$data['branch_id'] = $this->input->post('branch_id');

					$data['description'] = $this->input->post('description');
					$data['poster_status'] = 1;
					
					$this->db->insert('vb_posters', $data);
					$id = $this->db->insert_id();
					
					if($id > 0)
					{
						if (count(array_filter($_FILES['images']['name'])) > 0) 
						{
							foreach ($_FILES['images']['name'] as  $key => $img_names) 
							{
								$line_data['poster_header_id'] 	= $id;
								$line_data['poster_image'] 		= $img_names;

								$this->db->insert('vb_posters_line', $line_data);
								move_uploaded_file($_FILES['images']['tmp_name'][$key], 'uploads/posters/' . $img_names);
							}	
						}
						
						if (count(array_filter($_FILES['mobile_images']['name'])) > 0) 
						{
							foreach ($_FILES['mobile_images']['name'] as  $key => $img_names) 
							{
								$line_data['poster_header_id'] 	= $id;
								$line_data['poster_image_mobile']  = $img_names;
								$line_data['poster_image_type']  = 1;

								$this->db->insert('vb_posters_line', $line_data);
								move_uploaded_file($_FILES['mobile_images']['tmp_name'][$key], 'uploads/posters/mobile_posters/' . $img_names);
							}	
						}
						
						$this->session->set_flashdata('flash_message' , 'Poster added successfully');
						redirect(base_url() . 'branches/ManagePosters/add', 'refresh');
					}
				}*/

				if ($_POST) 
				{
					$branch_id = $this->input->post('branch_id');
					
					# exist start here
					$existChk = $this->db->query("select poster_id from vb_posters 
						where 
							branch_id='".$branch_id."'
					")->result_array();
					
					if(count($existChk) > 0)
					{
						$this->session->set_flashdata('error_message' , "Sorry! Branch Posters already exist!");
						redirect(base_url() . 'branches/ManagePosters/add', 'refresh');
					}
					# exist end here
					
					
					$poster_header = [
						'branch_id' 	=> $_POST['branch_id'],
						'poster_status' => 1,
					];

					$this->db->insert('vb_posters',$poster_header);
					$poster_id = $this->db->insert_id();

					if ($poster_id)
					{
						# Add and Remove Attendies start
						$product_id = isset($_POST['product_id']) ? count(array_filter($_POST['product_id'])) : 0;
					
						if( isset($_POST['product_id']) && $product_id > 0 )
						{
							$count=count($_POST['product_id']);
							
							for($dp=0;$dp<$count;$dp++)
							{	
								$LineData['poster_header_id'] = $poster_id;
								$LineData['product_id'] = isset($_POST['product_id'][$dp]) ? $_POST['product_id'][$dp] :"";
								
								#Web Poster Image
								$poster_image = isset($_FILES['poster_image']['name']) ? count(array_filter($_FILES['poster_image']['name'])) : 0;
								if( $poster_image > 0 )
								{
									$upload_dir = 'uploads/posters/'; 
									#Loop through each file in files[] array 
									
									$filesNameArr = array();
									#$filterName=array_filter($_FILES['upload_document']["name"]);
									
									#foreach($filterName as $key => $value) 
									#{ 
										$random_code = rand();
										$file_parts = pathinfo($_FILES['poster_image']['name'][$dp]);
										$file_name = $_FILES['poster_image']['name'][$dp];
										$ext = $file_parts['extension'];
										
										$file_tmpname1 = $_FILES['poster_image']['tmp_name'][$dp]; 
										$image['name'] = preg_replace('/\s+/', '', $_FILES['poster_image']['name'][$dp]);
										$image['type'] = $_FILES['poster_image']['type'][$dp];
										$image['tmp_name'] = $_FILES['poster_image']['tmp_name'][$dp];
										$image['error'] = $_FILES['poster_image']['error'][$dp];
										$image['size'] = $_FILES['poster_image']['size'][$dp];							
										#$filesNameArr[] = $filesName = trim($random_code.'.'.$ext);
										$filesNameArr[] = $filesName = trim($random_code.'@'.$file_name);
										
										$filepath = $upload_dir.$filesName; #Set upload file path 
										move_uploaded_file($file_tmpname1, $filepath);
										
										$LineData['poster_image'] = trim($filesName);
										
										/* $this->db->insert('user_document_attachments', $data1);
										$gallery_id1 = $this->db->insert_id(); */
									#} 
								}
								else
								{
									$LineData['poster_image'] = "";
								}
								#Web Poster Image
								
								#Mobile Poster Image
								$poster_image_mobile = isset($_FILES['poster_image_mobile']['name']) ? count(array_filter($_FILES['poster_image_mobile']['name'])) : 0;
								if( $poster_image_mobile > 0 )
								{
									$mobile_upload_dir = 'uploads/posters/mobile_posters/'; 
									#Loop through each file in files[] array 
									
									$filesNameArr = array();
									#$filterName=array_filter($_FILES['upload_document']["name"]);
									
									#foreach($filterName as $key => $value) 
									#{ 
										$mobile_random_code = rand();
										$file_parts = pathinfo($_FILES['poster_image_mobile']['name'][$dp]);
										$mobile_file_name = $_FILES['poster_image_mobile']['name'][$dp];
										$ext = $file_parts['extension'];
										
										$file_tmpname1 = $_FILES['poster_image_mobile']['tmp_name'][$dp]; 
										$image['name'] = preg_replace('/\s+/', '', $_FILES['poster_image_mobile']['name'][$dp]);
										$image['type'] = $_FILES['poster_image_mobile']['type'][$dp];
										$image['tmp_name'] = $_FILES['poster_image_mobile']['tmp_name'][$dp];
										$image['error'] = $_FILES['poster_image_mobile']['error'][$dp];
										$image['size'] = $_FILES['poster_image_mobile']['size'][$dp];							
										#$filesNameArr[] = $filesName = trim($random_code.'.'.$ext);
										$filesNameArr[] = $mobilefilesName = trim($mobile_random_code.'@'.$mobile_file_name);
										
										$mobile_filepath = $mobile_upload_dir.$mobilefilesName; #Set upload file path 
										move_uploaded_file($file_tmpname1, $mobile_filepath);
										
										$LineData['poster_image_mobile'] = trim($mobilefilesName);
										
										/* $this->db->insert('user_document_attachments', $data1);
										$gallery_id1 = $this->db->insert_id(); */
									#} 
								}
								else
								{
									$LineData['poster_image_mobile'] = "";
								}
								#Mobile Poster Image
								
								$this->db->insert('vb_posters_line', $LineData);
								$id_1 = $this->db->insert_id();
							}
						}
						#Add and Remove Attendies end
						
						$this->session->set_flashdata('flash_message' , 'Poster added successfully');
						redirect(base_url() . 'branches/ManagePosters', 'refresh');
					}
				}
			break;
			
			case "edit": #Edit
				$page_data['edit_data'] = $this->db->query("select * from vb_posters where poster_id = $id")->result_array();
				//	$page_data['line_edit_data'] = $this->db->query("select vb_posters_line.* , products.product_name where vb_posters_line.poster_header_id = $id")->result_array();
				if($_POST)
				{
					#$data['poster_title'] = $this->input->post('poster_title');
					$data['branch_id'] = $this->input->post('branch_id');
					#$data['description'] = $this->input->post('description');
						
					$this->db->where('poster_id', $id);
					$result = $this->db->update('vb_posters', $data);
					
					if($result > 0)
					{
						# Add and Remove Attendies start
						$product_id = isset($_POST['product_id']) ? count(array_filter($_POST['product_id'])) : 0;
					
						#if( isset($_POST['category_id']) && $category_id > 0 )
						if(
							(
								( isset($_FILES['poster_image']) &&
									count(array_filter($_FILES['poster_image']['name']) ) >0 
								)
								||
								( isset($_FILES['poster_image_mobile']) &&
									count(array_filter($_FILES['poster_image_mobile']['name']) ) >0 
								)
							)
							and 
							(isset($_POST['product_id']) && $product_id > 0 )
						)
						{
							
							$this->db->where('poster_header_id', $id);
							$this->db->delete('vb_posters_line');
								
							$count=count($_POST['product_id']);
							
							for($dp=0;$dp<$count;$dp++)
							{	
								$LineData['poster_header_id'] = $id;
								$LineData['product_id'] = isset($_POST['product_id'][$dp]) ? $_POST['product_id'][$dp] :"";
								$LineData['poster_image'] = $image_2 = isset($_POST['poster_image'][$dp]) ? $_POST['poster_image'][$dp] :"";
								$LineData['poster_image_mobile'] = $image_3 = isset($_POST['poster_image_mobile'][$dp]) ? $_POST['poster_image_mobile'][$dp] :"";
								
								#Web Poster
								$poster_image = isset($_FILES['poster_image']['name']) ? count(array_filter($_FILES['poster_image']['name'])) : 0;
								if( $poster_image > 0 )
								{
									$upload_dir = 'uploads/posters/'; 
									#Loop through each file in files[] array 
									
									$filesNameArr = array();
									#$filterName=array_filter($_FILES['upload_document']["name"]);
									
									#foreach($filterName as $key => $value) 
									#{ 
										$random_code = rand();
										$file_parts = pathinfo($_FILES['poster_image']['name'][$dp]);
										$file_name = $_FILES['poster_image']['name'][$dp];
										$ext = isset($file_parts['extension']) ? $file_parts['extension'] :"";
										
										$file_tmpname1 = $_FILES['poster_image']['tmp_name'][$dp]; 
										$image['name'] = preg_replace('/\s+/', '', $_FILES['poster_image']['name'][$dp]);
										$image['type'] = $_FILES['poster_image']['type'][$dp];
										$image['tmp_name'] = $_FILES['poster_image']['tmp_name'][$dp];
										$image['error'] = $_FILES['poster_image']['error'][$dp];
										$image['size'] = $_FILES['poster_image']['size'][$dp];							
										#$filesNameArr[] = $filesName = trim($random_code.'.'.$ext);
										$filesNameArr[] = $filesName = trim($random_code.'@'.$file_name);
										
										$filepath = $upload_dir.$filesName; #Set upload file path 
										move_uploaded_file($file_tmpname1, $filepath);
										
										if(!empty($file_name))
										{
											$LineData['poster_image'] = trim($filesName);
										}else{
											$LineData['poster_image'] = trim($image_2);
										}
										/* $this->db->insert('user_document_attachments', $data1);
										$gallery_id1 = $this->db->insert_id(); */
									#} 
								}
								else
								{
									$LineData['poster_image'] = $image_2;
								}
								#Web Poster end
								
								#Mobile Poster Image
								$poster_image_mobile = isset($_FILES['poster_image_mobile']['name']) ? count(array_filter($_FILES['poster_image_mobile']['name'])) : 0;
								if( $poster_image_mobile > 0 )
								{
									$mobile_upload_dir = 'uploads/posters/mobile_posters/'; 
									#Loop through each file in files[] array 
									
									$filesNameArr = array();
									#$filterName=array_filter($_FILES['upload_document']["name"]);
									
									#foreach($filterName as $key => $value) 
									#{ 
										$mobile_random_code = rand();
										$mobile_file_parts = pathinfo($_FILES['poster_image_mobile']['name'][$dp]);
										$mobile_file_name = $_FILES['poster_image_mobile']['name'][$dp];
										$ext = isset($mobile_file_parts['extension']) ? $mobile_file_parts['extension'] : "";
										
										$file_tmpname1 = $_FILES['poster_image_mobile']['tmp_name'][$dp]; 
										$image['name'] = preg_replace('/\s+/', '', $_FILES['poster_image_mobile']['name'][$dp]);
										$image['type'] = $_FILES['poster_image_mobile']['type'][$dp];
										$image['tmp_name'] = $_FILES['poster_image_mobile']['tmp_name'][$dp];
										$image['error'] = $_FILES['poster_image_mobile']['error'][$dp];
										$image['size'] = $_FILES['poster_image_mobile']['size'][$dp];							
										#$filesNameArr[] = $filesName = trim($random_code.'.'.$ext);
										$filesNameArr[] = $mobilefilesName = trim($mobile_random_code.'@'.$mobile_file_name);
										
										$mobile_filepath = $mobile_upload_dir.$mobilefilesName; #Set upload file path 
										move_uploaded_file($file_tmpname1, $mobile_filepath);
										
										#$LineData['poster_image_mobile'] = trim($mobilefilesName);
										
										if(!empty($mobile_file_name))
										{
											$LineData['poster_image_mobile'] = trim($mobilefilesName);
										}else{
											$LineData['poster_image_mobile'] = trim($image_3);
										}
										
										/* $this->db->insert('user_document_attachments', $data1);
										$gallery_id1 = $this->db->insert_id(); */
									#} 
								}
								else
								{
									$LineData['poster_image_mobile'] = $image_3;
								}
								#Mobile Poster Image
								
								$this->db->insert('vb_posters_line', $LineData);
								$id_1 = $this->db->insert_id();
							}
						}
						else
						{
							$this->db->where('poster_header_id', $id);
							$this->db->delete('vb_posters_line');
							
							# Add and Remove Attendies start
							$product_id = isset($_POST['product_id']) ? count(array_filter($_POST['product_id'])) : 0;
						
							if( isset($_POST['product_id']) && $product_id > 0 )
							{
								$count=count($_POST['product_id']);
								
								for($dp=0;$dp<$count;$dp++)
								{	
									$LineData['poster_header_id'] = $id;
									$LineData['product_id'] = isset($_POST['product_id'][$dp]) ? $_POST['product_id'][$dp] :"";
									$LineData['poster_image'] = $image_2 = isset($_POST['poster_image'][$dp]) ? $_POST['poster_image'][$dp] :"";
									$LineData['poster_image_mobile'] = $image_3 = isset($_POST['poster_image_mobile'][$dp]) ? $_POST['poster_image_mobile'][$dp] :"";
									
									#Web Poster Image
									$poster_image = isset($_FILES['poster_image']['name']) ? count(array_filter($_FILES['poster_image']['name'])) : 0;
									if( $poster_image > 0 )
									{
										$upload_dir = 'uploads/posters/'; 
										#Loop through each file in files[] array 
										
										$filesNameArr = array();
										#$filterName=array_filter($_FILES['upload_document']["name"]);
										
										#foreach($filterName as $key => $value) 
										#{ 
											$random_code = rand();
											$file_parts = pathinfo($_FILES['poster_image']['name'][$dp]);
											$file_name = $_FILES['poster_image']['name'][$dp];
											$ext = $file_parts['extension'];
											
											$file_tmpname1 = $_FILES['poster_image']['tmp_name'][$dp]; 
											$image['name'] = preg_replace('/\s+/', '', $_FILES['poster_image']['name'][$dp]);
											$image['type'] = $_FILES['poster_image']['type'][$dp];
											$image['tmp_name'] = $_FILES['poster_image']['tmp_name'][$dp];
											$image['error'] = $_FILES['poster_image']['error'][$dp];
											$image['size'] = $_FILES['poster_image']['size'][$dp];							
											#$filesNameArr[] = $filesName = trim($random_code.'.'.$ext);
											$filesNameArr[] = $filesName = trim($random_code.'@'.$file_name);
											
											$filepath = $upload_dir.$filesName; #Set upload file path 
											move_uploaded_file($file_tmpname1, $filepath);
											
											$LineData['poster_image'] = trim($filesName);
											
											/* $this->db->insert('user_document_attachments', $data1);
											$gallery_id1 = $this->db->insert_id(); */
										#} 
									}
									/* else
									{
										$LineData['poster_image'] = "";
									} */
									#Web Poster Image
									
									#Mobile Poster Image
									$poster_image_mobile = isset($_FILES['poster_image_mobile']['name']) ? count(array_filter($_FILES['poster_image_mobile']['name'])) : 0;
									if( $poster_image_mobile > 0 )
									{
										$mobile_upload_dir = 'uploads/posters/mobile_posters/'; 
										#Loop through each file in files[] array 
										
										$filesNameArr = array();
										#$filterName=array_filter($_FILES['upload_document']["name"]);
										
										#foreach($filterName as $key => $value) 
										#{ 
											$mobile_random_code = rand();
											$file_parts = pathinfo($_FILES['poster_image_mobile']['name'][$dp]);
											$mobile_file_name = $_FILES['poster_image_mobile']['name'][$dp];
											$ext = $file_parts['extension'];
											
											$file_tmpname1 = $_FILES['poster_image_mobile']['tmp_name'][$dp]; 
											$image['name'] = preg_replace('/\s+/', '', $_FILES['poster_image_mobile']['name'][$dp]);
											$image['type'] = $_FILES['poster_image_mobile']['type'][$dp];
											$image['tmp_name'] = $_FILES['poster_image_mobile']['tmp_name'][$dp];
											$image['error'] = $_FILES['poster_image_mobile']['error'][$dp];
											$image['size'] = $_FILES['poster_image_mobile']['size'][$dp];							
											#$filesNameArr[] = $filesName = trim($random_code.'.'.$ext);
											$filesNameArr[] = $mobilefilesName = trim($mobile_random_code.'@'.$mobile_file_name);
											
											$mobile_filepath = $mobile_upload_dir.$mobilefilesName; #Set upload file path 
											move_uploaded_file($file_tmpname1, $mobile_filepath);
											
											$LineData['poster_image_mobile'] = trim($mobilefilesName);
											
											/* $this->db->insert('user_document_attachments', $data1);
											$gallery_id1 = $this->db->insert_id(); */
										#} 
									}
									/* else
									{
										$LineData['poster_image_mobile'] = "";
									} */
									#Mobile Poster Image
									
									$this->db->insert('vb_posters_line', $LineData);
									$id_1 = $this->db->insert_id();
								}
							}
							#Add and Remove Attendies end
						}
						
						$this->session->set_flashdata('flash_message' , 'Poster updated successfully');
						redirect(base_url() . 'branches/ManagePosters/edit/'.$id, 'refresh');
					}
				}
			break;

			case "delete": #Delete
				$this->db->where('poster_id', $id);
				$this->db->delete('vb_posters');
				
				$this->session->set_flashdata('flash_message' , "Poster deleted successfully!");
				redirect(base_url() . 'branches/ManagePosters', 'refresh');
			break;
			
			case "status": #Block & Unblock
				if($status == 1){
					$data['poster_status'] = 1;
					$succ_msg = 'Poster unblocked successfully!';
				}else{
					$data['poster_status'] = 0;
					$succ_msg = 'Poster blocked successfully!';
				}
				$this->db->where('poster_id', $id);
				$this->db->update('vb_posters', $data);
				$this->session->set_flashdata('flash_message' , $succ_msg);
				redirect(base_url() . 'branches/ManagePosters', 'refresh');
			break;

			case 'view':
				$query = "select * from vb_posters_line
							
							left join vb_posters on vb_posters.poster_id = vb_posters_line.poster_header_id 
							
							where vb_posters.poster_id = $id";

				$result = $this->db->query($query)->result_array();
				$page_data['poster_images'] = $result;	
			break;
			
			default : #Manage
				
				$page_data["totalRows"] = $totalRows = $this->branches_model->posterCount();#
	
				if(!empty($_SESSION['PAGE']))
				{
					$limit = $_SESSION['PAGE'];
				}else{
					$limit = 10;
				}
				
				if (!empty($_GET['keywords'])) {
					$base_url = base_url('branches/ManagePosters?keywords='.$_GET['keywords']);
				} else {
					$base_url = base_url('branches/ManagePosters?keywords=');
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
				
				$page_data['resultData']  = $result= $this->branches_model->getposters($limit, $offset);
				
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
				
				// print_r($page_data);exit;
			break;
		}	
		$this->load->view($this->adminTemplate, $page_data);
	}

	function ajax_productName($id = '')
	{
		if ($id != '') 
		{
			$product = $this->db->query('select product_name from products where product_id = '.$id.'')->result_array(); 
			echo $product[0]['product_name'];
		}
	}

	function ajaxRemovePoster()
	{
		if ($_POST)
		{
			$postersQry = "select poster_line_id,poster_image from vb_posters_line 
			where poster_line_id='".$_POST['line_id']."' ";
			$getPosters = $this->db->query($postersQry)->result_array();
			
			$posterImage = isset($getPosters[0]["poster_image"]) ? $getPosters[0]["poster_image"] :"";
			
			if(!empty($posterImage))
			{
				unlink('uploads/posters/'.$posterImage);
			}
			
			$this->db->where('poster_line_id',$_POST['line_id']);
			$this->db->delete('vb_posters_line');
			echo 'Poster Deleted Sucessfully.!';die;
		}
	}
	
	function ajaxRemovePosterMobile()
	{
		if ($_POST)
		{
			$postersQry = "select poster_line_id,poster_image,poster_image_mobile from vb_posters_line 
			where poster_line_id='".$_POST['line_id']."' ";
			$getPosters = $this->db->query($postersQry)->result_array();
			
			$posterImage = isset($getPosters[0]["poster_image_mobile"]) ? $getPosters[0]["poster_image_mobile"] :"";
			
			if(!empty($posterImage))
			{
				unlink('uploads/posters/mobile_posters/'.$posterImage);
			}
			
			$this->db->where('poster_line_id',$_POST['line_id']);
			$this->db->delete('vb_posters_line');
			echo 'Poster Deleted Sucessfully.!';die;
		}
	}
	
	#get Ajax Products
	public function getAjaxProducts($product_id="")
	{
		$data = $this->db->select('
				products.product_id,
				products.product_name,
				products.product_code
			')
		->from('products')
		->where('products.product_id',$product_id)
		->get()
		->result();
		
		echo json_encode($data);
	}
	
	
	# Ajax  Change
	public function ajaxselectProducts() 
	{
        $id = $_POST["id"];		
		if($id)
		{			
			$condition = ' 
				branch.branch_id ="'.$id.'" and
				products.product_status =1 and 
				vb_branch_items_line.item_status =1
			';
			
			$query = "select 
				products.product_id,
				products.product_code,
				products.product_name
				
				
				from vb_branch_items_line
				
				left join vb_branch_items_header on 
					vb_branch_items_header.branch_item_header_id = vb_branch_items_line.branch_item_header_id
				
				left join products on 
					products.product_id = vb_branch_items_line.product_id
				
				left join category on 
					category.category_id = products.category_id
				
				left join branch on 
					branch.branch_id = vb_branch_items_header.branch_id
				
			where $condition order by products.product_name asc";
		
			$data = $this->db->query($query)->result_array();
		
			if( count($data) > 0)
			{
				echo '<option value="">- Select Product ('.count($data).') Items -</option>';
				foreach($data as $val)
				{
					echo '<option value="'.$val['product_id'].'">'.$val['product_code'].'-'.ucfirst($val['product_name']).'</option>';
				}
			}
			else
			{
				echo '<option value="">No states under this country!</option>';
			}
		}
		die;
    }
	
	function getOrgBranches(){
	
		$organization_id=$_POST['organization_id'];

		$result = $this->branches_model->getOrgBranch($organization_id);;

	
		if( count($result) > 0)
		{
			echo '<option value="0">- Select -</option>';
			foreach($result as $val)
			{
				echo '<option value="'.$val['branch_id'].'">'.$val['branch_name'].'</option>';
			}
		}
		else
		{
			echo '<option value="">- Select -</option>';
		}
	}
}
?>