<?php
if (!defined('BASEPATH'))exit('No direct script access allowed');

class Admin_location extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->database();
        $this->load->library('session');
        
        #cache control
		$this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
		$this->output->set_header('Pragma: no-cache');
		
		if(empty($this->user_id)){
			 redirect(base_url() . 'admin/login', 'refresh');
		}
    }

    #Manage Country
    function manage_country($type = '', $id = '', $status = '')
    {
		if (empty($this->user_id))
        {
			redirect(base_url() . 'admin/login', 'refresh');
		}
		
		$page_data['activeMenu'] = 1;
		$page_data['type'] = $type;
		$page_data['id'] = $id;
		$page_data['manage_settings'] = 1;
		$page_data['page_name']  = 'admin_location/manage_country';
		$page_data['page_title'] = 'Countries';
		
		if(isset($_POST['default_submit']) && isset($_POST['default_country']))
		{
			$data['default_country'] = 0;
			$result = $this->db->update('geo_countries', $data);
			
			if($result)
			{
				$country_id = $_POST['default_country'];
				$data_1['default_country'] = 1;
				$this->db->where('country_id', $country_id);
				$result1 = $this->db->update('geo_countries', $data_1);
			}
			$this->session->set_flashdata('flash_message' , 'Default country updated successfully.');
			redirect($_SERVER["HTTP_REFERER"], 'refresh');
		}
		
		switch($type)
		{
			case "add": #Add
				if($_POST)
				{
					$data['country_name'] = $this->input->post('country_name');
					$data['country_code'] = $this->input->post('country_code');
					$data['currency_symbol'] = $this->input->post('currency_symbol');
					$data['currency_code'] = $this->input->post('currency_code');
					$data['active_flag'] = $this->active_flag;
					$data['created_by'] = $this->user_id;
					$data['created_date'] = $this->date_time;
                    $data['last_updated_by'] = $this->user_id;
                    $data['last_updated_date'] = $this->date_time;
					
					# Country exist start here
					$chkExistCountry = $this->db->query("select country_id from geo_countries 
						where 
							country_name='".$data['country_name']."' or
							country_code='".$data['country_code']."' or
							currency_code='".$data['currency_code']."' 
							")->result_array();
							
					if(count($chkExistCountry) > 0)
					{
						$this->session->set_flashdata('error_message' , "country Name or Code already exist!");
						redirect(base_url() . 'admin_location/manage_country/add', 'refresh');
					}
					# Country exist end here
					
					$this->db->insert('geo_countries', $data);
					$id = $this->db->insert_id();

					if($id !="")
					{
						if( !empty($_FILES['country_icon']['name']) )
						{  
							move_uploaded_file($_FILES['country_icon']['tmp_name'], 'uploads/country_icons/'.$id.'.png');
						}

						$this->session->set_flashdata('flash_message' ,'Country created successfully!');
						redirect(base_url() . 'admin_location/manage_country', 'refresh');
					}
				}
			break;
			
			case "edit": #Edit
				$page_data['edit_data'] = $this->db->get_where('geo_countries', array('country_id' => $id))
										->result_array();
				if($_POST)
				{
					$data['country_name'] = $this->input->post('country_name');
					$data['country_code'] = $this->input->post('country_code');
					$data['currency_symbol'] = $this->input->post('currency_symbol');
					$data['currency_code'] = $this->input->post('currency_code');
                    $data['last_updated_by'] = $this->user_id;
                    $data['last_updated_date'] = $this->date_time;
					
					# Country exist start here
					$chkExistCountry = $this->db->query("select country_id from geo_countries 
						where 
							country_id !='".$id."' and 
							
						(	country_name='".$data['country_name']."' or
							country_code='".$data['country_code']."' or
							currency_code='".$data['currency_code']."' )
							")->result_array();
							
					if(count($chkExistCountry) > 0)
					{
						$this->session->set_flashdata('error_message' , "State Name already exist!");
						redirect(base_url() . 'admin_location/manage_country/edit/'.$id, 'refresh');
					}
					# Country exist end here
					
					$this->db->where('country_id', $id);
					$result = $this->db->update('geo_countries', $data);
					
					if($result > 0)
					{
						if( !empty($_FILES['country_icon']['name']) )
						{  
							move_uploaded_file($_FILES['country_icon']['tmp_name'], 'uploads/country_icons/'.$id.'.png');
						}
						
						$this->session->set_flashdata('flash_message' , 'Country updated successfully!');
						redirect(base_url() . 'admin_location/manage_country', 'refresh');
					}
				}
			break;
			
			case "delete": #Delete
				$this->db->where('country_id', $id);
				$this->db->delete('geo_countries');
				$this->session->set_flashdata('flash_message' , get_phrase('country_deleted_successfully!'));
				redirect(base_url() . 'admin_location/manage_country', 'refresh');
			break;
			
			case "status": #Block & Unblock
				if($status == "Y")
				{
					$data['active_flag'] = "Y";
					$data['inactive_date'] = NULL;
					$data['last_updated_by'] = $this->user_id;
					$data['last_updated_date'] = $this->date_time;
					$succ_msg = 'Country active successfully!';
				}
				else
				{
					$data['active_flag'] = "N";
					$data['last_updated_by'] = $this->user_id;
					$data['last_updated_date'] = $this->date_time;
					$data['inactive_date'] = $this->date_time;
					$succ_msg = 'Country inactive successfully!';
				}

				$this->db->where('country_id', $id);
				$this->db->update('geo_countries', $data);
				$this->session->set_flashdata('flash_message' ,$succ_msg);
				redirect($_SERVER['HTTP_REFERER'], 'refresh');
			break;
			
			case "import": #Import
				if($_FILES)
				{
					$filename = $_FILES["csv"]["tmp_name"];      
					
					if($_FILES["csv"]["size"] > 0)
					{
						$file = fopen($filename, "r");
						
						for ($lines = 0; $data = fgetcsv($file,1000,",",'"'); $lines++) 
						{
							if ($lines == 0) continue;
							
							$country_name = trim($data[0]);
							$country_code = trim($data[1]);
							$currency_symbol = trim($data[2]);
							$currency_code = trim($data[3]);
							$country_status = 1;
							
							$start_date = strtotime(date('d-m-Y h:i:s a',strtotime($data[3])));;
					
							$sql = "INSERT INTO `country`
							(
							`country_name`,
							`country_code`,
							`currency_symbol`,
							`currency_code`,
							`country_status`
							) 
									 
							VALUES 
								('".$country_name."','".$country_code ."','".$currency_symbol."','".$currency_code."','".$country_status."')";
							$this->db->query($sql);
						}
						fclose($file); 
					}
					else
					{
						$this->session->set_flashdata('error_message' , "Country imported error!");
						redirect(base_url() . 'admin_location/manage_country', 'refresh');
					}
					$this->session->set_flashdata('flash_message' , "Country imported successfully!");
					redirect(base_url() . 'admin_location/manage_country', 'refresh');
				}
			break;
			
			case "export": #Export
						
				$data = $this->db->query("select
				
				country.country_name,
				country.country_code,
				country.currency_symbol,
				country.currency_code,
				country.country_status
				
				from country
			
				order by country_id desc")->result_array();
				
				#$data[] = array('f_name'=> "Nishit", 'l_name'=> "patel", 'mobile'=> "999999999", 'gender'=> "male");
				
				header("Content-type: application/csv");
				header("Content-Disposition: attachment; filename=\"Country".".csv\"");
				header("Pragma: no-cache");
				header("Expires: 0");

				$handle = fopen('php://output', 'w');
				fputcsv($handle, array("S.No","Country Name","Country Code","Currency Symbol","Country Code","Country Status"));
				$cnt=1;
				
				foreach ($data as $row) 
				{
					if($row['country_status'] == 1)
					{
						$status = 'Active';
					}
					else
					{
						$status =  'Inactive';
					}
					$narray=array(
					
							$cnt,
							$row["country_name"],
							$row["country_code"],
							$row["currency_symbol"],
							$row["currency_code"],
							$status
						);
					fputcsv($handle, $narray);
					$cnt++;
				}
				fclose($handle);
				exit;
			break;
			
			default : #Manage
				$totalResult = $this->adminlocation_model->get_country("","",$this->totalCount);
				$page_data["totalRows"] = $totalRows = count($totalResult);
	
				if(!empty($_SESSION['PAGE']))
				{$limit = $_SESSION['PAGE'];
				}else{$limit = 10;}
 	
				$country_id = isset($_GET['country_id']) ? $_GET['country_id'] :NULL;
				$active_flag = isset($_GET['active_flag']) ? $_GET['active_flag'] :NULL;

				$redirectURL = 'admin_location/manage_country?country_id='.$country_id.'&active_flag='.$active_flag.'';
				
				if (  $country_id != NULL || $active_flag != NULL ) {
					$base_url = base_url().$redirectURL;
				} else {
					$base_url = base_url().$redirectURL;
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
				
				$page_data['resultData']  = $result= $data =$this->adminlocation_model->get_country($limit, $offset, $this->pageCount);
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
	
	# Manage State
    function manage_state($type = '', $id = '', $status = '')
    {
		if (empty($this->user_id))
        {
			redirect(base_url() . 'admin/login', 'refresh');
		}
		
		$page_data['type'] = $type;
		$page_data['id'] = $id;
		$page_data['system_settings'] = 1;
		$page_data['page_name']  = 'admin_location/manage_state';
		$page_data['page_title'] = "States";
		
		switch($type)
		{
			case "add": #Add
				if($_POST)
				{
					$data['country_id'] = $this->input->post('country_id');
					$data['state_name'] = $this->input->post('state_name');
					
					$data['state_code'] = strtoupper($this->input->post('state_code'));
					$data['state_number'] = $this->input->post('state_number');
					$data['active_flag'] = $this->active_flag;
					$data['created_by'] = $this->user_id;
					$data['created_date'] = $this->date_time;
                    $data['last_updated_by'] = $this->user_id;
                    $data['last_updated_date'] = $this->date_time;
					
					# state exist start here
					$chkExistState = $this->db->query("select state_id from geo_states 
						where 
							state_name='".$data['state_name']."'
							")->result_array();
							
					if(count($chkExistState) > 0)
					{
						$this->session->set_flashdata('error_message' , "State Name already exist!");
						redirect(base_url() . 'admin_location/manage_state/add', 'refresh');
					}
					# state exist end here
					
					$this->db->insert('geo_states', $data);
					$id = $this->db->insert_id();
					if($id !="")
					{
						$this->session->set_flashdata('flash_message' , 'State added successfully');
						redirect(base_url() . 'admin_location/manage_state', 'refresh');
					}
				}
			break;
			
			case "edit": #Edit
				$page_data['edit_data'] = $this->db->get_where('geo_states', array('state_id' => $id))
										->result_array();
				if($_POST)
				{
					$data['country_id'] = $this->input->post('country_id');
					$data['state_name'] = $this->input->post('state_name');
					
					$data['state_code'] = strtoupper($this->input->post('state_code'));
					$data['state_number'] = $this->input->post('state_number');

                    $data['last_updated_by'] = $this->user_id;
                    $data['last_updated_date'] = $this->date_time;
					
					# state exist start here
					$chkExistState = $this->db->query("select state_id from geo_states 
					where 
						state_id !='".$id."' and 
						( 
							state_name ='".$data['state_name']."'
						)
						")->result_array();
							
					if(count($chkExistState) > 0)
					{
						$this->session->set_flashdata('error_message' , "State Name already exist!");
						redirect(base_url() . 'admin_location/manage_state/edit'.$id, 'refresh');
					}
					# state exist end here
					
					$this->db->where('state_id', $id);
					$result = $this->db->update('geo_states', $data);
					if($result > 0)
					{
						$this->session->set_flashdata('flash_message' , 'State updated successfully');
						redirect(base_url() . 'admin_location/manage_state/', 'refresh');
					}
				}
			break;
			
			case "delete": #Delete
				$this->db->where('state_id', $id);
				$this->db->delete('state');
				$this->session->set_flashdata('flash_message' , get_phrase('state_deleted_successfully!'));
				redirect(base_url() . 'admin_location/manage_state/', 'refresh');
			break;
			
			case "status": #Block & Unblock
				if($status == "Y")
				{
					$data['active_flag'] = "Y";
					$data['inactive_date'] = NULL;
					$data['last_updated_by'] = $this->user_id;
					$data['last_updated_date'] = $this->date_time;
					$succ_msg = 'State active successfully!';
				}
				else
				{
					$data['active_flag'] = "N";
					$data['last_updated_by'] = $this->user_id;
					$data['last_updated_date'] = $this->date_time;
					$data['inactive_date'] = $this->date_time;
					$succ_msg = 'State inactive successfully!';
				}

				$this->db->where('state_id', $id);
				$this->db->update('geo_states', $data);
				$this->session->set_flashdata('flash_message' ,$succ_msg);
				redirect($_SERVER['HTTP_REFERER'], 'refresh');
			break;
		
			case "import":#Import
				if($_FILES)
				{
					$filename = $_FILES["csv"]["tmp_name"];      
					
					if($_FILES["csv"]["size"] > 0)
					{
						$file = fopen($filename, "r");
						
						for ($lines = 0; $data = fgetcsv($file,1000,",",'"'); $lines++) 
						{
							if ($lines == 0) continue;
							
							$countryID ='';
							if(isset($data[0]))
							{ 
								$country_name = $data[0]; 
								$query  = "select country.country_id from country where country_status=1 AND country_name ='".trim($country_name)."' ";
								$getCountryID = $this->db->query($query)->result_array();
								$countryID = isset($getCountryID[0]['country_id']) ? $getCountryID[0]['country_id'] : 0;
							}
								
							$country_id = $countryID;
							$state_name = trim($data[1]);
							$state_status = 1;
							
							$sql = "INSERT INTO `state`
							(
							`country_id`,
							`state_name`,
							`state_status`
							) 
							
							VALUES 
							('".$country_id."', '".$state_name ."', '".$state_status."')";
							$this->db->query($sql);
						}
						fclose($file); 
					}
					else
					{
						$this->session->set_flashdata('error_message' , "States imported error!");
						redirect(base_url() . 'admin_location/manage_state', 'refresh');
					}
					$this->session->set_flashdata('flash_message' , "States imported successfully!");
					redirect(base_url() . 'admin_location/manage_state', 'refresh');
				}
			break;
			#Import end here
			
			case "export":
				$data = $this->db->query("select
				state.state_name,
				country.country_name,
				state.state_status
				from state
				
				left join country on country.country_id = state.country_id
				order by state_id desc")->result_array();
				
				#$data[] = array('f_name'=> "Nishit", 'l_name'=> "patel", 'mobile'=> "999999999", 'gender'=> "male");
				
				header("Content-type: application/csv");
				header("Content-Disposition: attachment; filename=\"Country".".csv\"");
				header("Pragma: no-cache");
				header("Expires: 0");

				$handle = fopen('php://output', 'w');
				fputcsv($handle, array("S.No","Country Name","State Name"));
				$cnt=1;
				
				foreach ($data as $row) 
				{
					if($row['state_status'] == 1)
					{
						$status = 'Active';
					}
					else
					{
						$status =  'Inactive';
					}
					$narray=array(
					
							$cnt,
							$row["country_name"],
							$row["state_name"],
							$status
						);
					fputcsv($handle, $narray);
					$cnt++;
				}
				fclose($handle);
				exit;
			break;
			
			default : #Manage
				$totalResult = $this->adminlocation_model->get_state("","",$this->totalCount);
				$page_data["totalRows"] = $totalRows = count($totalResult);

				if(!empty($_SESSION['PAGE']))
				{$limit = $_SESSION['PAGE'];
				}else{$limit = 10;}

				$country_id = isset($_GET['country_id']) ? $_GET['country_id'] :NULL;
				$state_id = isset($_GET['state_id']) ? $_GET['state_id'] :NULL;
				$active_flag = isset($_GET['active_flag']) ? $_GET['active_flag'] :NULL;

				$redirectURL = 'admin_location/manage_state?country_id='.$country_id.'&state_id='.$state_id.'&active_flag='.$active_flag.'';
				
				if ( $country_id != NULL || $state_id != NULL || $active_flag != NULL ) {
					$base_url = base_url().$redirectURL;
				} else {
					$base_url = base_url().$redirectURL;
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
				
				$page_data['projects']  = $result = $this->adminlocation_model->get_state($limit, $offset,$this->pageCount);
				
				if(isset($_GET['per_page']) && $_GET['per_page'] > 1 && count($result) == 0 )
				{
					redirect(base_url().$redirectURL, 'refresh');
				}
				
				#show start and ending Count
				if($offset == 1 || $offset== "" || $offset== 0){
					$page_data["first_item"] = 1;
				}else{
					$page_data["first_item"] = $offset + 1;
				}

				$total_counts = $total_count= 0;
				$pages = $page_data["starting"] = $page_data["ending"]="";
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
	
	# MANAGE DISTRICT
    function manage_district($type = '', $id = '', $status = '')
    {
		if (empty($this->user_id))
        {
			redirect(base_url() . 'admin/login', 'refresh');
		}
		
		$page_data['type'] = $type;
		$page_data['id'] = $id;
		$page_data['manage_settings'] = 1;
		$page_data['page_name']  = 'admin_location/manage_district';
		$page_data['page_title'] = "Districts";
		
		
		#Import start here
		if( isset($_POST['import']) && $_POST['import'] !="")
		{
			include(APPPATH.'import/excel_reader2.php'); #Import xls
			include(APPPATH.'import/SpreadsheetReader.php'); #Import xls
			$allowedFileType = ['application/vnd.ms-excel','text/xls','text/xlsx','application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'];
	  
			if(in_array($_FILES["file"]["type"],$allowedFileType))
			{
				$targetPath = 'uploads/import/'.$_FILES['file']['name'];
				move_uploaded_file($_FILES['file']['tmp_name'], $targetPath);
				
				$Reader = new SpreadsheetReader($targetPath);
				$sheetCount = count($Reader->sheets());
				for($i=0;$i<$sheetCount;$i++)
				{
					$Reader->ChangeSheet($i);
					foreach ($Reader as $Row)
					{
						$country_name = $state_name = $district_name =  "";
						$country_id = $state_id = 0;
						
						if(isset($Row[0]))
						{ 
							$country_name = $Row[0]; 
							$query  = "select country.country_id from country where country_status=1 AND country_name ='".trim($country_name)."' ";
							$getCountryID = $this->db->query($query)->result_array();
							$country_id = isset($getCountryID[0]['country_id']) ? $getCountryID[0]['country_id'] : 0;
						}
						
						if(isset($Row[1]))
						{ 
							$state_name = $Row[1]; 
							$query  = "select state.state_id from state where state_status=1 AND state_name ='".trim($state_name)."' ";
							$getStateID = $this->db->query($query)->result_array();
							$state_id = isset($getStateID[0]['state_id']) ? $getStateID[0]['state_id'] : 0;
						}
						
						if(isset($Row[2])) { $district_name = $Row[2]; }
						
						if ( !empty($country_id) || !empty($state_id) || !empty($district_name) ) 
						{
							$data['country_id'] = trim($country_id);
							$data['state_id'] = trim($state_id);
							$data['district_name'] = trim($district_name);
							$data['district_status'] = 1;
							$this->db->insert('district', $data);
							$id = $this->db->insert_id();
						}
					 }
				}
				
				$this->session->set_flashdata('flash_message' , get_phrase('district_imported_successfully'));
				redirect(base_url() . 'admin_location/manage_district', 'refresh');
			 }
			 else
			 { 
				$this->session->set_flashdata('error_message' , 'Invalid File Type. Upload Excel File.');
				redirect(base_url() . 'admin_location/manage_district', 'refresh');
			 }
		}
		#Import end here
		
		switch($type)
		{
			case "add": #Add
				if($_POST)
				{
					$data['country_id'] = $this->input->post('country_id');
					$data['state_id'] = $this->input->post('state_id');
					$data['district_name'] = $this->input->post('district_name');
					$data['district_status'] = 1;
					$this->db->insert('district', $data);
					$id = $this->db->insert_id();
					if($id !="")
					{
						$this->session->set_flashdata('flash_message' , get_phrase('district_added_successfully'));
						redirect(base_url() . 'admin_location/manage_district/', 'refresh');
					}
				}
			break;
			
			case "edit": #Edit
				$page_data['edit_data'] = $this->db->get_where('district', array('district_id' => $id))
										->result_array();
				if($_POST)
				{
					$data['country_id'] = $this->input->post('country_id');
					$data['state_id'] = $this->input->post('state_id');
					$data['district_name'] = $this->input->post('district_name');
					
					$this->db->where('district_id', $id);
					$result = $this->db->update('district', $data);
					if($result > 0)
					{
						$this->session->set_flashdata('flash_message' , get_phrase('district_updated_successfully'));
						redirect(base_url() . 'admin_location/manage_district/', 'refresh');
					}
				}
			break;
			
			case "delete": #Delete
				$this->db->where('district_id', $id);
				$this->db->delete('district');
				$this->session->set_flashdata('flash_message' , get_phrase('district_deleted_successfully!'));
				redirect(base_url() . 'admin_location/manage_district/', 'refresh');
			break;
			
			case "status": #Block & Unblock
				if($status == 1){
					$data['district_status'] = 1;
					$succ_msg = 'district_unblocked_successfully!';
				}else{
					$data['district_status'] = 0;
					$succ_msg = 'district_blocked_successfully!';
				}
				$this->db->where('district_id', $id);
				$this->db->update('district', $data);
				$this->session->set_flashdata('flash_message' , get_phrase($succ_msg));
				redirect(base_url() . 'admin_location/manage_district/', 'refresh');
			break;
			
			default : #Manage
				#$page_data['projects'] = $this->adminlocation_model->get_district();
			
				$page_data["totalRows"] = $totalRows = $this->adminlocation_model->getDistrictCount();#
				
				if(!empty($_SESSION['PAGE']))
				{$limit = $_SESSION['PAGE'];
				}else{$limit = 10;}
				
				if (!empty($_GET['keywords'])) {
					$base_url = base_url('admin_location/manage_district?keywords='.$_GET['keywords']);
				} else {
					$base_url = base_url('admin_location/manage_district?keywords=');
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
				
				$page_data['projects']  = $result= $this->adminlocation_model->get_district($limit, $offset);
				
				#show start and ending Count
				$total_counts = $total_count= 0;
				$pages=$page_data["starting"] = $page_data["ending"]="";
				$pageno = isset($pageNo) ? $pageNo :"";
				if($pageno==1 || $pageno==""){
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
	
	# MANAGE CITY
    function manage_city($type = '', $id = '', $status = '')
    {
		if (empty($this->user_id))
        {
			redirect(base_url() . 'admin/login', 'refresh');
		}
		$pageno=$type;
		$page_data['type'] = $type;
		$page_data['id'] = $id;
		$page_data['manage_settings'] = 1;
		$page_data['page_name']  = 'admin_location/manage_city';
		$page_data['page_title'] = "Cities";
		
		switch($type)
		{
			case "add": #Add
				if($_POST)
				{
					$data['country_id'] = $this->input->post('country_id');
					$data['state_id'] = $this->input->post('state_id');
					#$data['district_id'] = $this->input->post('district_id');
					$data['city_name'] = $this->input->post('city_name');

					$data['active_flag'] = $this->active_flag;
					$data['created_by'] = $this->user_id;
					$data['created_date'] = $this->date_time;
                    $data['last_updated_by'] = $this->user_id;
                    $data['last_updated_date'] = $this->date_time;
					
					# City exist start here
					$chkExistCity = $this->db->query("select city_id from geo_cities 
						where 
							city_name='".$data['city_name']."'
							")->result_array();
							
					if(count($chkExistCity) > 0)
					{
						$this->session->set_flashdata('error_message' , "City Name already exist!");
						redirect(base_url() . 'admin_location/manage_city/add', 'refresh');
					}
					# City exist end here
					
					$this->db->insert('geo_cities', $data);
					$id = $this->db->insert_id();
					if($id !="")
					{
						$this->session->set_flashdata('flash_message' , 'City created successfully!');
						redirect(base_url() . 'admin_location/manage_city', 'refresh');
					}
				}
			break;
			
			case "edit": #Edit
				$page_data['edit_data'] = $this->db->get_where('geo_cities', array('city_id' => $id))
										->result_array();
				if($_POST)
				{
					$data['country_id'] = $this->input->post('country_id');
					$data['state_id'] = $this->input->post('state_id');
					#$data['district_id'] = $this->input->post('district_id');
					$data['city_name'] = $this->input->post('city_name');

                    $data['last_updated_by'] = $this->user_id;
                    $data['last_updated_date'] = $this->date_time;
					
					# City exist start here
					$chkExistCity = $this->db->query("select city_id from geo_cities 
						where 
							city_id !='".$id."' and 
								( city_name='".$data['city_name']."' )
							")->result_array();
							
					if(count($chkExistCity) > 0)
					{
						$this->session->set_flashdata('error_message' , "City Name already exist!");
						redirect(base_url() . 'admin_location/manage_city/edit/'.$id, 'refresh');
					}
					# City exist end here
					
					$this->db->where('city_id', $id);
					$result = $this->db->update('geo_cities', $data);
					if($result > 0)
					{
						$this->session->set_flashdata('flash_message' , 'City updated successfully!');
						redirect(base_url() . 'admin_location/manage_city/', 'refresh');
					}
				}
			break;
			
			case "delete": #Delete
				$this->db->where('city_id', $id);
				$this->db->delete('city');
				$this->session->set_flashdata('flash_message' , get_phrase('city_deleted_successfully!'));
				redirect(base_url() . 'admin_location/manage_city/', 'refresh');
			break;
			
			case "status": #Block & Unblock
				if($status == "Y")
				{
					$data['active_flag'] = "Y";
					$data['last_updated_by'] = $this->user_id;
					$data['last_updated_date'] = $this->date_time;
					$succ_msg = 'City inctive successfully!';
				}
				else
				{
					$data['active_flag'] = "N";
					$data['last_updated_by'] = $this->user_id;
					$data['last_updated_date'] = $this->date_time;
					$succ_msg = 'City active successfully!';
				}

				$this->db->where('city_id', $id);
				$this->db->update('geo_cities', $data);
				$this->session->set_flashdata('flash_message', $succ_msg);
				redirect($_SERVER['HTTP_REFERER'], 'refresh');
			break;
			
			case "import":#Import
				if($_FILES)
				{
					$filename = $_FILES["csv"]["tmp_name"];      
					
					if($_FILES["csv"]["size"] > 0)
					{
						$file = fopen($filename, "r");
						
						for ($lines = 0; $data = fgetcsv($file,1000,",",'"'); $lines++) 
						{
							if ($lines == 0) continue;
							
							$countryID ='';
							if(isset($data[0]))
							{ 
								$country_name = $data[0]; 
								$query  = "select country.country_id from country where country_status=1 AND country_name ='".trim($country_name)."' ";
								$getCountryID = $this->db->query($query)->result_array();
								$countryID = isset($getCountryID[0]['country_id']) ? $getCountryID[0]['country_id'] : 0;
							}
							
							$stateID ='';
							if(isset($data[1]))
							{ 
								$state_name = $data[1]; 
								$query  = "select state.state_id from state where state_status=1 AND state_name ='".trim($state_name)."' ";
								$getStateID = $this->db->query($query)->result_array();
								$stateID = isset($getStateID[1]['state_id']) ? $getStateID[1]['state_id'] : 1;
							}
								
							$country_id = $countryID;
							$state_id = $stateID;
							$city_name = trim($data[2]);
							$city_status = 1;
							
							$sql = "INSERT INTO `city`
							(
								`country_id`,
								`state_id`,
								`city_name`,
								`city_status`
							) 
							
							VALUES 
							('".$country_id."','".$state_id ."', '".$city_name ."', '".$city_status."')";
							$this->db->query($sql);
						}
						fclose($file); 
					}
					else
					{
						$this->session->set_flashdata('error_message' , "City imported error!");
						redirect(base_url() . 'admin_location/manage_city', 'refresh');
					}
					$this->session->set_flashdata('flash_message' , "City imported successfully!");
					redirect(base_url() . 'admin_location/manage_city', 'refresh');
				}
			break;
			#Import end here
			
			case "export":
				$data = $this->db->query("select
				
				state.state_name,
				city.city_name,
				country.country_name,
				city.city_status
				from city
				
				left join country on country.country_id = city.country_id
				left join state on state.state_id = city.state_id
				order by city_id desc")->result_array();
				
				#$data[] = array('f_name'=> "Nishit", 'l_name'=> "patel", 'mobile'=> "999999999", 'gender'=> "male");
				
				header("Content-type: application/csv");
				header("Content-Disposition: attachment; filename=\"City".".csv\"");
				header("Pragma: no-cache");
				header("Expires: 0");

				$handle = fopen('php://output', 'w');
				fputcsv($handle, array("S.No","Country Name","State Name","City Name","Status"));
				$cnt=1;
				
				foreach ($data as $row) 
				{
					if($row['city_status'] == 1)
					{
						$status = 'Active';
					}
					else
					{
						$status =  'Inactive';
					}
					$narray=array(
					
							$cnt,
							$row["country_name"],
							$row["state_name"],
							$row["city_name"],
							$status
						);
					fputcsv($handle, $narray);
					$cnt++;
				}
				fclose($handle);
				exit;
			break;
			
			default : #Manage
				$totalResult = $this->adminlocation_model->get_city("","",$this->totalCount);
				$page_data["totalRows"] = $totalRows = count($totalResult);

				if(!empty($_SESSION['PAGE']))
				{$limit = $_SESSION['PAGE'];
				}else{$limit = 10;}
				
				$country_id = isset($_GET['country_id']) ? $_GET['country_id'] :NULL;
				$state_id = isset($_GET['state_id']) ? $_GET['state_id'] :NULL;
				$city_id = isset($_GET['city_id']) ? $_GET['city_id'] :NULL;
				$active_flag = isset($_GET['active_flag']) ? $_GET['active_flag'] :NULL;

				$redirectURL = 'admin_location/manage_city?country_id='.$country_id.'&state_id='.$state_id.'&city_id='.$city_id.'&active_flag='.$active_flag.'';
				
				if ( $country_id != NULL || $state_id != NULL || $city_id != NULL || $active_flag != NULL ) {
					$base_url = base_url().$redirectURL;
				} else {
					$base_url = base_url().$redirectURL;
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
				
				$page_data['projects']  = $result= $this->adminlocation_model->get_city($limit, $offset,$this->pageCount);

				if(isset($_GET['per_page']) && $_GET['per_page'] > 1 && count($result) == 0 )
				{
					redirect(base_url().$redirectURL, 'refresh');
				}
				
				#show start and ending Count
				$total_counts = $total_count= 0;
				$pages=$page_data["starting"] = $page_data["ending"]="";
				$pageno = isset($pageNo) ? $pageNo :"";
				if($pageno==1 || $pageno==""){
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
	
	# Manage Location
	
    function manage_location($type = '', $id = '', $status = '')
    {
		if (empty($this->user_id))
        {
			redirect(base_url() . 'admin/login', 'refresh');
		}
		
		$page_data['type'] = $type;
		$page_data['id'] = $id;
		
		$page_data['page_name']  = 'admin_location/manage_location';
		$page_data['page_title'] = "Locations";
		
		switch($type)
		{
			case "add": #Add
				if($_POST)
				{
					if(isset($_POST['location_name']) && $_POST['location_name'] !="")
					{
						$count=count($_POST['location_name']);
						
						for($dp1=0;$dp1<$count;$dp1++)
						{	
							$data['country_id'] = $this->input->post('country_id');
							$data['state_id'] = $this->input->post('state_id');
							$data['city_id'] = $this->input->post('city_id');
							$data['location_status'] = 1;
							$data['location_name']=$_POST['location_name'][$dp1];
							
							$this->db->insert('location', $data);
							$id = $this->db->insert_id();
						}
						$this->session->set_flashdata('flash_message' , get_phrase('location_added_successfully'));
						redirect(base_url() . 'admin_location/manage_location/', 'refresh');
					}
				}
			break;
			
			case "edit": #Edit
				$page_data['edit_data'] = $this->db->get_where('location', array('location_id' => $id))
										->result_array();
				
				if($_POST)
				{
					if(isset($_POST['location_name']) && $_POST['location_name'] !="")
					{
						$count=count($_POST['location_name']);
						
						for($dp1=0;$dp1<$count;$dp1++)
						{	
							$data['country_id'] = $this->input->post('country_id');
							$data['state_id'] = $this->input->post('state_id');
							$data['city_id'] = $this->input->post('city_id');
							$data['location_name']=$_POST['location_name'][$dp1];
							
							$this->db->where('location_id', $id);
							$result = $this->db->update('location', $data);
						}
						
						$this->session->set_flashdata('flash_message' , get_phrase('location_added_successfully'));
						redirect(base_url() . 'admin_location/manage_location/', 'refresh');
					}
				}
			break;
			
			case "delete": #Delete
				$this->db->where('location_id', $id);
				$this->db->delete('location');
				$this->session->set_flashdata('flash_message' , get_phrase('location_deleted_successfully!'));
				redirect(base_url() . 'admin_location/manage_location/', 'refresh');
			break;
			
			case "status": #Block & Unblock
				if($status == 1){
					$data['location_status'] = 1;
					$succ_msg = 'location_unblocked_successfully!';
				}else{
					$data['location_status'] = 0;
					$succ_msg = 'location_blocked_successfully!';
				}
				$this->db->where('location_id', $id);
				$this->db->update('location', $data);
				$this->session->set_flashdata('flash_message' , get_phrase($succ_msg));
				redirect(base_url() . 'admin_location/manage_location/', 'refresh');
			break;
			
			default : #Manage
				##$page_data['projects'] = $this->adminlocation_model->get_location();
				
				#Pagination starts
				$totalRows = $this->adminlocation_model->getLocationCount();#
				$url = base_url()."admin_location/manage_city"; #
				$itemPerPage = 10;
				$Segment = 2;
				$numLinks = 5;
				$suffix = '?' . http_build_query($_GET, '', "&");
				$config = pagination_configuration($url,$totalRows, $itemPerPage, $Segment, $numLinks, true,$suffix);
				$this->pagination->initialize($config);
				$page_num = $pageno-1;
				$page_num1 = ( $page_num < 0 )? 0 : $page_num;
				$page1 = $page_num1 * $config["per_page"];
				$page_data["pagination"] = $this->pagination->create_links();
				$offset = $page1;
				$record = $config["per_page"];
				#Pagination ends
				
				$page_data["totalRows"] = $totalRows;
				$result = $page_data['projects']  = $this->adminlocation_model->get_location($offset, $record);
				
				#starting to end record start
				if($offset == 1 || $offset=="")
				{
					$page_data["first_item"] = 1;
				}
				else
				{
					$page_data["first_item"] = $offset + 1;
				}
				
				$total_counts = $total_count= 0;
				$pages=$page_data["starting"] = $page_data["ending"]="";
				
				if($pageno==1 || $pageno=="")
				{
					$page_data["starting"] = 1;
				}
				else
				{
					$pages = $pageno-1;
					$total_count = $pages * $config["per_page"];
					$page_data["starting"] = ( $config["per_page"] * $pages )+1;
				}
				$total_counts = $total_count + count($result);
				$page_data["ending"]  = $total_counts;
				#starting to end record end
			
				
			break;
		}
		$this->load->view($this->adminTemplate, $page_data);
	}
	
	#Ajax State Change
	public function ajaxSelectState() 
	{
		if (isset($this->user_id) && $this->user_id == '')
            redirect(base_url() . 'admin/login', 'refresh');
			
        $id = $_POST["id"];		
		if($id)
		{			
			$data = $this->db->query("select state_id,state_name from geo_states 
			where active_flag= 'Y' AND country_id='".$id."' ")->result_array();
			if( count($data) > 0)
			{
				echo '<option value="">Select State</option>';
				foreach($data as $val)
				{
					echo '<option value="'.$val['state_id'].'">'.ucfirst($val['state_name']).'</option>';
				}
			}else
			{
				echo '<option value="">No states under this country</option>';
			}
		}
		die;
    }

	# Ajax City Change	
	public function ajaxSelectStateCity() 
	{
		if (isset($this->user_id) && $this->user_id == '')
            redirect(base_url() . 'admin/login', 'refresh');
			
        $id = $_POST["id"];		
		if($id)
		{
			$data = $this->db->query("select city_id,city_name from geo_cities where active_flag='Y' && state_id='".$id."' ")->result_array();
			#$data = $this->adminlocation_model->getCityData($id);
			if( count($data) > 0)
			{
				echo '<option value="">- Select City -</option>';
				foreach($data as $val)
				{
					echo '<option value="'.$val['city_id'].'">'.ucfirst($val['city_name']).'</option>';
				}
			}else
			{
				echo '<option value="">No cities under this state</option>';
			}
		}
		die;
    }
	
	# Ajax City Change	
	public function ajaxSelectCity() 
	{
		if (isset($this->user_id) && $this->user_id == '')
            redirect(base_url() . 'admin/login', 'refresh');
			
        $id = $_POST["id"];		
		if($id)
		{			
			$data = $this->adminlocation_model->getCityData($id);
			if( count($data) > 0)
			{
				echo '<option value="">Select City</option>';
				foreach($data as $val)
				{
					echo '<option value="'.$val['city_id'].'">'.ucfirst($val['city_name']).'</option>';
				}
			}else
			{
				echo '<option value="">No cities under this state</option>';
			}
		}
		die;
    }
	
	# Ajax City Change
	public function ajaxSelectLocation() 
	{
		if (isset($this->user_id) && $this->user_id == '')
            redirect(base_url() . 'admin/login', 'refresh');
			
        $id = $_POST["id"];		
		if($id)
		{			
			$data = $this->adminlocation_model->getLocationData($id);
			if( count($data) > 0)
			{
				echo '<option value="">Select Location</option>';
				foreach($data as $val)
				{
					echo '<option value="'.$val['location_id'].'">'.ucfirst($val['location_name']).'</option>';
				}
			}else
			{
				echo '<option value="">No locations under this city</option>';
			}
		}
		die;
    }
	
	public function ajaxSelectCountrydetails() 
	{
		if (isset($this->user_id) && $this->user_id == '')
            redirect(base_url() . 'admin/login', 'refresh');
			
        $id = $_POST["id"];		
		if($id)
		{			
			$data = $this->adminlocation_model->getCountryData($id);
			if( count($data) > 0)
			{
				echo $data[0]['country_code']."--".$data[0]['currency_symbol']."--".$data[0]['currency_code'];
			}
		}
		die;
    }
}
