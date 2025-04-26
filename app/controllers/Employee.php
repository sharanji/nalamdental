<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Employee extends CI_Controller 
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
	
	function ManageEmployee($type = '', $id = '', $status = '', $status1 = '')
    {
		if (empty($this->user_id))
        {
			redirect(base_url() . 'admin/adminLogin', 'refresh');
		}
		
		$page_data['emp_type'] = $type;
		$page_data['type'] = $type;
		$page_data['id'] = $id;
		$page_data['status'] = $status;
		
		$page_data['ManageEmployees'] = 1;
		#$page_data['system_settings'] = 1;
		$page_data['page_name']  = 'employee/ManageEmployee';
		$page_data['page_title'] = 'Employees';
		
		switch(true)
		{
			case ($type == "add"): #add
			{
				if($id == "basic-info")
				{ 
					if($_POST)
					{
						#Document No Start here
						$documentQry = "select doc_num_id,prefix_name,suffix_name,next_number 
						from doc_document_numbering as dm
						left join sm_list_type_values ltv on 
							ltv.list_type_value_id = dm.doc_type
						where 
							ltv.list_code = 'EMP' 
							and dm.active_flag = 'Y'
							and coalesce(dm.from_date,CURDATE()) <= CURDATE() 
							and coalesce(dm.to_date,CURDATE()) >= CURDATE()
						";
						$getDocumentData=$this->db->query($documentQry)->result_array();
							
						$prefixName = isset($getDocumentData[0]['prefix_name']) ? $getDocumentData[0]['prefix_name'] : NULL;
						$startingNumber = isset($getDocumentData[0]['next_number']) ? $getDocumentData[0]['next_number'] : NULL;
						$suffixName = isset($getDocumentData[0]['suffix_name']) ? $getDocumentData[0]['suffix_name'] : NULL;
						$documentNumber = $prefixName.''.$startingNumber.''.$suffixName;

						
						$data['first_name'] 		= !empty($_POST['first_name']) ? $_POST['first_name'] : NULL;
						$data['middle_name'] 		= !empty($_POST['middle_name']) ? $_POST['middle_name'] : NULL;
						$data['last_name'] 			= !empty($_POST['last_name']) ? $_POST['last_name'] : NULL;
						$data['father_name'] 		= !empty($_POST['father_name']) ? $_POST['father_name'] : NULL;
						$data['mother_name'] 		= !empty($_POST['mother_name']) ? $_POST['mother_name'] : NULL;
						$data['date_of_birth'] 		= !empty($_POST['date_of_birth']) ? date("Y-m-d",strtotime($_POST['date_of_birth'])) : NULL;
						$data['mob_ctry_code'] 		= !empty($_POST['mob_ctry_code']) ? $_POST['mob_ctry_code'] : NULL;
						$data['mobile_number'] 		= !empty($_POST['mobile_number']) ? $_POST['mobile_number'] : NULL;
						$data['email_address'] 		= !empty($_POST['email_address']) ? $_POST['email_address'] : NULL;
						$data['gender'] 			= !empty($_POST['gender']) ? $_POST['gender'] : NULL;
						$data['blood_group_id'] 	= !empty($_POST['blood_group_id']) ? $_POST['blood_group_id'] : NULL;
						$data['alt_mob_number']     = !empty($_POST['alt_mob_number']) ? $_POST['alt_mob_number'] : NULL;
						$data['alt_mob_ctry_code']  = !empty($_POST['alt_mob_ctry_code']) ? $_POST['alt_mob_ctry_code'] : NULL;
						$data['alt_email_address'] 	= !empty($_POST['alt_email_address']) ? $_POST['alt_email_address'] : NULL; 
						$data['employment_type'] 	= !empty($_POST['employment_type']) ? $_POST['employment_type'] : NULL;					
						$data['employee_number']    = isset($documentNumber) ? $documentNumber : NULL;
						$data['created_by'] 		= $this->user_id;
						$data['created_date'] 		= $this->date_time;
						$data['last_updated_by'] 	= $this->user_id;
						$data['last_updated_date'] 	= $this->date_time;
						
						#Audit Trails Add Start here
						$tableName = table_per_people_all;
						$menuName = employee;
						$description = "Employee created successsfully!";
						auditTrails(array_filter($data),$tableName,$type,$menuName,"",$description);
						#Audit Trails Add end here

						$this->db->insert('per_people_all', $data);
						$id = $this->db->insert_id();
						
						if($id)
						{
							$updateDocNum = array(
								"employee_number" 	     =>  $documentNumber,
								"last_updated_by" 	  	 =>  $this->user_id,
								"last_updated_date" 	 =>  $this->date_time
							);
							$this->db->where('person_id', $id);
							$headerTbl1 = $this->db->update('per_people_all',$updateDocNum);

							#Update Next Val DOC Number tbl start
							$str_len = strlen($startingNumber);
							$nextValue1 = $startingNumber + 1;
							$nextValue = str_pad($nextValue1,$str_len,"0",STR_PAD_LEFT);
							$doc_num_id = isset($getDocumentData[0]['doc_num_id']) ? $getDocumentData[0]['doc_num_id']:"";
							
							$UpdateData['next_number'] = $nextValue;
							$this->db->where('doc_num_id', $doc_num_id);
							$resultUpdateData = $this->db->update('doc_document_numbering', $UpdateData);
							#Update Next Val DOC Number tbl end
							#Document No End here

							if(!empty($_FILES['profile_image']['name']) )
							{  
								move_uploaded_file($_FILES['profile_image']['tmp_name'], 'uploads/profile_image/'.$id.'.png');
							}
						}
						
						$this->session->set_flashdata('flash_message' , "Employee basic details added successfully!");
						
						if( isset($_POST["save_close"]))
						{
							redirect(base_url() . 'employee/ManageEmployee', 'refresh');
						}
						else if( isset($_POST["save_only"]))
						{
							redirect(base_url() . 'employee/ManageEmployee/edit/career-info/'.$id, 'refresh');
						}
					}	
				}	
			}
			break;
			
			case ($type == "edit" || $type == "view"): #edit
					
				$page_data['edit_data'] = $this->db->query("select * from per_people_all 
				where per_people_all.person_id='".$status."' ")->result_array();

				if($id == "basic-info")
				{
					if($_POST)
					{
						$data['first_name'] 		= isset($_POST['first_name']) ? $_POST['first_name'] : NULL;
						$data['middle_name'] 		= isset($_POST['middle_name']) ? $_POST['middle_name'] :NULL;
						$data['last_name'] 			= isset($_POST['last_name']) ? $_POST['last_name'] : NULL;
						$data['father_name'] 		= isset($_POST['father_name']) ? $_POST['father_name'] : NULL;
						$data['mother_name'] 		= isset($_POST['mother_name']) ? $_POST['mother_name'] :NULL;
						$data['date_of_birth'] 		= !empty($_POST['date_of_birth']) ? date("Y-m-d",strtotime($_POST['date_of_birth'])) : NULL;
						$data['mob_ctry_code'] 		= isset($_POST['mob_ctry_code']) ? $_POST['mob_ctry_code'] :NULL;
						$data['mobile_number'] 		= isset($_POST['mobile_number']) ? $_POST['mobile_number'] :NULL;
						$data['email_address'] 		= isset($_POST['email_address']) ? $_POST['email_address'] :NULL;
						$data['gender'] 			= isset($_POST['gender']) ? $_POST['gender'] :NULL;
						$data['blood_group_id'] 	= isset($_POST['blood_group_id']) ? $_POST['blood_group_id'] :NULL;
						$data['alt_mob_number']  	= isset($_POST['alt_mob_number']) ? $_POST['alt_mob_number'] :NULL;
						$data['alt_mob_ctry_code']  = isset($_POST['alt_mob_ctry_code']) ? $_POST['alt_mob_ctry_code'] :NULL;
						$data['alt_email_address'] 	= isset($_POST['alt_email_address']) ? $_POST['alt_email_address'] :NULL;
						$data['employment_type'] 	= isset($_POST['employment_type']) ? $_POST['employment_type'] :NULL;
						$data['last_updated_by'] 	= $this->user_id;
                    	$data['last_updated_date'] 	= $this->date_time;
						
						#Audit Trails Add Start here
						$tableName = table_per_people_all;
						$menuName = employee;
						$description = "Employee updated successsfully!";
						auditTrails(array_filter($data),$tableName,$type,$menuName,$page_data['edit_data'],$description);
						#Audit Trails Edit end here

						$this->db->where('person_id', $status);
						$result = $this->db->update('per_people_all', $data);
						
						if($result)
						{
							if(!empty($_FILES['profile_image']['name']) )
							{  
								move_uploaded_file($_FILES['profile_image']['tmp_name'], 'uploads/profile_image/'.$status.'.png');
							}
						}
						
						$this->session->set_flashdata('flash_message' , "Basic details updated successfully!");
						#redirect(base_url() . 'employee/ManageEmployee/edit/career-info/'.$status, 'refresh');

						if( isset($_POST["save_close"]))
						{
							redirect(base_url() . 'employee/ManageEmployee', 'refresh');
						}
						else if( isset($_POST["save_only"]))
						{
							redirect(base_url() . 'employee/ManageEmployee/edit/career-info/'.$status, 'refresh');
						}
					}
				}
				else if($id == "career-info")
				{
					if($_POST)
					{
						$data['location_id'] 		= !empty($_POST['location_id']) ? $_POST['location_id'] : NULL;
						$data['branch_id'] 			= !empty($_POST['branch_id']) ? $_POST['branch_id'] : NULL;
						$data['organization_id'] 	= !empty($_POST['organization_id']) ? $_POST['organization_id'] : NULL;
						$data['location_id'] 		= !empty($_POST['location_id']) ? $_POST['location_id'] : NULL;
						$data['designation_id'] 	= !empty($_POST['designation_id']) ? $_POST['designation_id'] : NULL;
						$data['department_id'] 		= !empty($_POST['department_id']) ? $_POST['department_id'] : NULL;
						$data['date_of_joining'] 	= !empty($_POST['date_of_joining']) ? date("Y-m-d",strtotime($_POST['date_of_joining'])) : NULL;
						
						$data['date_of_releaving'] 	= !empty($_POST['date_of_releaving']) ? date("Y-m-d",strtotime($_POST['date_of_releaving'])) : NULL;
						$data['previous_experience'] = !empty($_POST['previous_experience']) ? $_POST['previous_experience'] : NULL;
						
						$data['rate_per_hour'] 		= !empty($_POST['rate_per_hour']) ? $_POST['rate_per_hour'] : NULL; 
						$data['rate_per_day'] 		= !empty($_POST['rate_per_day']) ? $_POST['rate_per_day'] : NULL;
						$data['pay_frequency'] 		=  !empty($_POST['pay_frequency']) ? $_POST['pay_frequency'] : NULL;
						$data['last_updated_by'] 	= $this->user_id;
                    	$data['last_updated_date'] 	= $this->date_time;
								
						#Audit Trails Add Start here
						$tableName = table_per_people_all;
						$menuName = employee;
						$description = "Employee updated successsfully!";
						auditTrails(array_filter($data),$tableName,$type,$menuName,$page_data['edit_data'],$description);
						#Audit Trails Edit end here


						$this->db->where('person_id', $status);
						$result = $this->db->update('per_people_all', $data);

						$this->session->set_flashdata('flash_message' , "Career Information Added Successfully!");
						
						if( isset($_POST["save_close"]) )
						{
							redirect(base_url() . 'employee/ManageEmployee', 'refresh');
						}
						else if( isset($_POST["save_only"]))
						{
							redirect(base_url() . 'employee/ManageEmployee/edit/id-info/'.$status, 'refresh');
						}
					}
				}
				else if($id == "id-info")
				{
					if($_POST)
					{
						
						#PAN Validation
						$data['pan_number'] = $this->input->post('pan_number'); 
						$chk_pan_number = $this->db->query("select person_id from per_people_all where pan_number='".$data['pan_number']."' and 
							per_people_all.person_id !='".$status."'
							")->result_array();
						if( count($chk_pan_number) > 0 && !empty($data['pan_number']))
						{
							$this->session->set_flashdata('error_message' , "Sorry! PAN Number Already exist!");
							redirect($_SERVER['HTTP_REFERER'], 'refresh');
						}
						
						#Aadhaar Validation
						$data['aadhaar_number'] = $this->input->post('aadhaar_number'); 
						$chk_aadhaar_number = $this->db->query("select person_id from per_people_all where 
							aadhaar_number='".$data['aadhaar_number']."' and
								per_people_all.person_id !='".$status."'
							")->result_array();

						if( count($chk_aadhaar_number) > 0 && !empty($data['aadhaar_number']))
						{
							$this->session->set_flashdata('error_message' , "Sorry! Aadhaar Number Already exist!");
							redirect($_SERVER['HTTP_REFERER'], 'refresh');
						}
						$data['passport_number'] 		= $this->input->post('passport_number'); 
						$data['passport_issue_date'] 	= !empty($_POST['passport_issue_date']) ? date("Y-m-d",strtotime($_POST['passport_issue_date'])) : NULL;
						$data['passport_exp_date'] 		= !empty($_POST['passport_exp_date']) ? date("Y-m-d",strtotime($_POST['passport_exp_date'])) : NULL;
						$data['driving_licence'] 		= $this->input->post('driving_licence'); 						
						$data['pf_number'] 				= $this->input->post('pf_number');
						$data['esi_number'] 			= $this->input->post('esi_number');
						$data['uan_number'] 			= $this->input->post('uan_number');
						$data['last_updated_by'] 		= $this->user_id;
                    	$data['last_updated_date'] 		= $this->date_time;

						#Audit Trails Add Start here
						$tableName = table_per_people_all;
						$menuName = employee;
						$description = "Employee updated successsfully!";
						auditTrails(array_filter($data),$tableName,$type,$menuName,$page_data['edit_data'],$description);
						#Audit Trails Edit end here


						$this->db->where('person_id', $status);
						$result = $this->db->update('per_people_all', $data);

						$this->session->set_flashdata('flash_message' , "ID Information Updated Successfully!");
						#redirect(base_url() . 'employee/ManageEmployee/edit/address-info/'.$status, 'refresh');
						if( isset($_POST["save_close"]))
						{
							redirect(base_url() . 'employee/ManageEmployee', 'refresh');
						}
						else if( isset($_POST["save_only"]))
						{
							redirect(base_url() . 'employee/ManageEmployee/edit/address-info/'.$status, 'refresh');
						}
					}
				}
				else if($id == "address-info")
				{
					if($_POST)
					{
						$data['address1'] 				= $_POST['address']; 
						$data['address2'] 				= $this->input->post('address2'); 
						$data['address3'] 				= $this->input->post('address3'); 
						$data['country_id'] 			= $this->input->post('country_id'); 
						$data['district_id'] 			= $this->input->post('district_id'); 
						$data['state_id'] 				= $this->input->post('state_id'); 
						$data['city_id'] 				= $this->input->post('city_id');
						$data['postal_code'] 			= $this->input->post('postal_code');

						$data['permenant_country_id'] 	= $this->input->post('permenant_country_id');
						$data['permenant_state_id'] 	= $this->input->post('permenant_state_id');
						$data['permenant_district_id'] 	= $this->input->post('permenant_district_id');
						$data['permenant_city_id'] 		= $this->input->post('permenant_city_id');
						$data['permenant_address1'] 	= $this->input->post('permenant_address1');
						$data['permenant_address2'] 	= $this->input->post('permenant_address2');
						$data['permenant_address3'] 	= $this->input->post('permenant_address3');
						$data['permenant_postal_code'] 	= $this->input->post('permenant_postal_code');
						$data['chk_shipping_address'] 	= isset($_POST['chk_shipping_address']) ? $_POST['chk_shipping_address'] : 0 ;
						$data['last_updated_by'] 		= $this->user_id;
                    	$data['last_updated_date'] 		= $this->date_time;

						#Audit Trails Add Start here
						$tableName = table_per_people_all;
						$menuName = employee;
						$description = "Employee updated successsfully!";
						auditTrails(array_filter($data),$tableName,$type,$menuName,$page_data['edit_data'],$description);
						#Audit Trails Edit end here


						$this->db->where('person_id', $status);
						$result = $this->db->update('per_people_all', $data);

						$this->session->set_flashdata('flash_message' , "Address information updated successfully!");
						#redirect(base_url() . 'employee/ManageEmployee/edit/bank-info/'.$status, 'refresh');
						
						if( isset($_POST["save_close"]))
						{
							redirect(base_url() . 'employee/ManageEmployee', 'refresh');
						}
						else if( isset($_POST["save_only"]))
						{
							redirect(base_url() . 'employee/ManageEmployee/edit/bank-info/'.$status, 'refresh');
						} 
					}
				}
				else if($id == "bank-info")
				{
					if($_POST)
					{
						$data['person_id'] 			= $status;
						$data['account_number'] 	= $this->input->post('account_number'); 
						$data['bank_name'] 			= $this->input->post('bank_name'); 
						$data['branch_name'] 		= $this->input->post('branch_name'); 
						$data['ifsc_code'] 			= $this->input->post('ifsc_code'); 
						$data['micr_code'] 			= $this->input->post('micr_code');
						$data['account_name'] 		= $this->input->post('account_name'); 
						$data['bank_address'] 		= $this->input->post('address'); 
						$data['last_updated_by'] 	= $this->user_id;
                    	$data['last_updated_date'] 	= $this->date_time;
						
						#Audit Trails Add Start here
						$tableName = table_per_people_all;
						$menuName = employee;
						$description = "Employee updated successsfully!";
						auditTrails(array_filter($data),$tableName,$type,$menuName,$page_data['edit_data'],$description);
						#Audit Trails Edit end here

						$this->db->where('person_id', $status);
						$result = $this->db->update('per_people_all', $data);

						$this->session->set_flashdata('flash_message' , "Bank details updated successfully!");
						redirect(base_url() . 'employee/ManageEmployee', 'refresh');
						#redirect(base_url() . 'employee/ManageEmployee/grid_view', 'refresh');
					}
				}
			break;
			
			case ($type == "status"): #status
				if($status == "Y")
				{
					$data['active_flag'] = "Y";
					$data['inactive_date'] = NULL;
					$data['last_updated_by'] = $this->user_id;
					$data['last_updated_date'] = $this->date_time;
					#$data['end_date'] = NULL;
					$succ_msg = 'Employee active successfully!';
				}
				else
				{
					$data['active_flag'] = "N";
					$data['inactive_date'] = $this->date_time;
					$data['last_updated_by'] = $this->user_id;
					$data['last_updated_date'] = $this->date_time;
					#$data['end_date'] = $this->date;
					$succ_msg = 'Employee inactive successfully!';
				}

				#Audit Trails Start here
				$tableName = table_per_people_all;
				$menuName = employee;
				$id = $id;
				auditTrails($id,$tableName,$type,$menuName,"",$succ_msg);
				#Audit Trails end here

				$this->db->where('person_id', $id);
				$this->db->update('per_people_all', $data);
				$this->session->set_flashdata('flash_message' , $succ_msg);
				redirect($_SERVER["HTTP_REFERER"], 'refresh');
			break;
			
			default : #Manage
				$totalResult = $this->employee_model->getManageEmployee("","",$this->totalCount);
				$page_data["totalRows"] = $totalRows = count($totalResult);
	
				if(!empty($_SESSION['PAGE']))
				{$limit = $_SESSION['PAGE'];
				}else{$limit = 10;}
				
				$redirectURL = 'employee/ManageEmployee';

				if (!empty($_GET['keywords'])) {
					$base_url = base_url('employee/ManageEmployee/?keywords='.$_GET['keywords']);
				} else {
					$base_url = base_url('employee/ManageEmployee/?keywords=');
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
				
				$page_data['resultData']  = $result = $this->employee_model->getManageEmployee($limit, $offset, $this->pageCount);
			
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

	function ManageEmployeeBanks($type = '', $id = '', $status = '')
	{
		if (empty($this->user_id))
        {
			redirect(base_url() . 'admin/adminLogin', 'refresh');
		}
		
		$page_data['type'] = $type;
		$page_data['id'] = $id;
		
		$page_data['ManageEmployeeBank'] = 1;
		$page_data['page_name']  = 'employee/ManageEmployeeBank';
		$page_data['page_title'] = 'Employee Banks';
		
		switch ($type)
		{
			case "export": #Export
				
				$condition = "1 = 1 and primary_bank = 1";
				
				$query = "select emp_bank_details.* , users.first_name ,users.last_name,users.random_user_id
					from emp_bank_details
					left join users on users.user_id  = emp_bank_details.user_id
					where $condition ";
			
				$result = $this->db->query($query)->result_array();
				
				header("Content-type: application/csv");
				header("Content-Disposition: attachment; filename=\"EmployeeBanksDetails".".csv\"");
				header("Pragma: no-cache");
				header("Expires: 0");

				$handle = fopen('php://output', 'w');
				
				fputcsv($handle, array(
					"S.No",
					"Employee Name",
					"Employee No.",
					"Bank Name",
					"Branch Name",	
					"Bank A/c No.",	
					"IFSC Code",	
					"MICR Code",				
					
				));
				
				$cnt=1;
				foreach ($result as $row) 
				{
					$narray=array(
						$cnt,
						ucfirst($row['first_name']." ".$row['last_name']),
						$row['random_user_id'],
						ucfirst($row['bank_name']),
						$row['branch_name'],
						$row['account_number'],
						$row['ifsc_code'],
						$row['micr_code'],
					);
					
					fputcsv($handle, $narray);
					$cnt++;
				}
				fclose($handle);
				exit;
			break;

			default : #Manage
				if(isset($_POST['edit'])) 
				{
					$bank_id= $_POST['bank_id'];
					
					#Check Duplicate
					$chkQry = "select bank_id from emp_bank_details
								where 
								user_id = '".$id."' and 
									(account_number = '".$_POST['account_number']."' or
										ifsc_code = '".$_POST['ifsc_code']."') and
											bank_id != '".$bank_id."'
								";
					$chkDuplicate = $this->db->query($chkQry)->result_array();

					if (count($chkDuplicate) == 0)
					{
						
						$data = [
							'bank_name'	     => $_POST['bank_name'],
							'account_number' => $_POST['account_number'],
							'ifsc_code'      => $_POST['ifsc_code'],
							'micr_code'      => $_POST['micr_code'],
							'branch_name'    => $_POST['branch_name'],
						];
			
						$this->db->where('bank_id', $_POST['bank_id']);
						$result = $this->db->update('emp_bank_details', $data);
					
						if($result) 
						{
							$this->session->set_flashdata('flash_message', 'Bank details updated successfully!');
							redirect(base_url() . 'employee/ManageEmployeeBanks', 'refresh');	
						}
					}
					else
					{
						$this->session->set_flashdata('error_message', 'This bank details already exist for this employee.');
						redirect(base_url() . 'employee/ManageEmployeeBanks', 'refresh');
					}
				}
				$page_data["totalRows"] = $totalRows = $this->employee_model->getManageEmployeeBanksCount();#
	
				if(!empty($_SESSION['PAGE']))
				{$limit = $_SESSION['PAGE'];
				}else{$limit = 10;}
				
				if (!empty($_GET['keywords'])) {
					$base_url = base_url('employee/ManageEmployee?keywords='.$_GET['keywords']);
				} else {
					$base_url = base_url('employee/ManageEmployee?keywords=');
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
				
				$page_data['resultData']  = $result = $this->employee_model->getManageEmployeeBanks($limit, $offset);
			
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
		// echo json_encode($page_data);exit;
		$this->load->view($this->adminTemplate, $page_data);
	}

	public function AjaxGetCategorys() 
	{
        $id = $_POST["category_type_id"];		
		if($id)
		{			
			$data =  $this->db->query('select category_name,category_id from hr_payslip_categories where category_type = '.$id)->result_array();
			
			if( count($data) > 0)
			{
				echo '<option value="">- Select Category -</option>';
				foreach($data as $val)
				{
					echo '<option value="'.$val['category_id'].'">'.ucfirst($val['category_name']).'</option>';
				}
			}
			else
			{
				echo '<option value="">No category under this category type!</option>';
			}
		}
		die;
    }

	function AjaxDeleteAssign()
	{
		if (isset($_POST['assign_id']) && !empty($_POST['assign_id'])) {
			
			$assign_id = $_POST['assign_id'] ;

			$this->db->where('emp_assign_id',$assign_id);
			$this->db->delete('hr_emp_assign_payslip_categories');

			echo 'Payslip assignment Deleted Successfully';
		}
	}

	function ManageEmpRelations($type = '', $id = '', $status = '')
	{
		if (empty($this->user_id))
        {
			redirect(base_url() . 'admin/adminLogin', 'refresh');
		}
	
		$page_data['type'] = $type;
		$page_data['id'] = $id;
		
		$page_data['setup_settings'] = 1;
		$page_data['system_settings'] = 1;
		$page_data['page_name']  = 'employee/ManageEmployeeRelations';
		$page_data['page_title'] = 'Manage Relations';
		
		switch($type)
		{
			case "add": #add
				if($_POST)
				{
					#Personal details
					$data['relationship_name'] = $this->input->post('relationship_name');
					$data['relationship_status'] = $this->input->post('relationship_name');
										
					$this->db->insert('emp_relationships', $data);
					$id = $this->db->insert_id();
					
					if($id !="")
					{
						$this->session->set_flashdata('flash_message' , "Relation added Successfully!");
						redirect(base_url() . 'employee/ManageEmpRelations', 'refresh');
					}
				}
			break;
			
			case "edit": #edit
					
				$page_data['edit_data'] = $this->db->query("select * from emp_relationships 
				where emp_relationships.relationship_id='".$id."' ")->result_array();
				
				if($_POST)
				{
					#details
					$data['relationship_name'] = $this->input->post('relationship_name');
				
					$this->db->where('relationship_id', $id);
					$result = $this->db->update('emp_relationships', $data);
					
					if($result)
					{
						$this->session->set_flashdata('flash_message' , "Relation updated Successfully!");
						redirect(base_url() . 'employee/ManageEmpRelations', 'refresh');
					}
				}
			break;
						
			case "status": #Block & Unblock
				if($status == 1){
					$data['relationship_status'] = 1;
					$succ_msg = 'Relation activated successfully!';
				}else{
					$data['relationship_status'] = 0;
					$succ_msg = 'Relation inactivated successfully!';
				}
				$this->db->where('relationship_id', $id);
				$this->db->update('emp_relationships', $data);
				$this->session->set_flashdata('flash_message' , $succ_msg);
				redirect(base_url() . 'employee/ManageEmpRelations', 'refresh');
			break;
			
			default : #Manage
				$page_data["totalRows"] = $totalRows = $this->employee_model->empRelationCount();#
	
				if(!empty($_SESSION['PAGE']))
				{$limit = $_SESSION['PAGE'];
				}else{$limit = 10;}
				
				if (!empty($_GET['keywords'])) {
					$base_url = base_url('employee/ManageEmpRelatons?keywords='.$_GET['keywords']);
				} else {
					$base_url = base_url('employee/ManageEmpRelatons?keywords=');
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
				
				$page_data['resultData']  = $result = $this->employee_model->empRelation($limit, $offset);
			
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
	
	#Employee_Mailexist
	public function EmailExist()
	{
		if ( isset($_POST['email']) && $_POST['email']) 
		{
			$email = $_POST['email'];

			if ( isset($_POST['id']) && $_POST['id'] !=0 ) 
			{ #add
				$results = $this->db->query("select user_id from users WHERE email='".$email."' and user_id != '".$_POST['id']."' and register_type = 1")->result_array(); #and register_type='".$register_type."'
			}
			else 
			{ #edit
				$results = $this->db->query("select user_id from users WHERE email='".$email."' and register_type = 1")->result_array(); #and register_type='".$register_type."'
			}

			if ( count($results) > 0 ) {
				echo "taken";	
			}else{
				echo 'not_taken';
			}
			exit();
		}
	}
	
	public function MobileExist()
	{
		if ( isset($_POST['mobile_number']) && $_POST['mobile_number'] ) 
		{
			$mobile_number = $_POST['mobile_number'];
			
			if (isset($_POST['id'])) 
			{
				$results = $this->db->query("select user_id from users WHERE mobile_number='".$mobile_number."' and user_id = '".$_POST['id']."' and register_type = 1")->result_array(); #and register_type='".$register_type."'

			}
			else {
				$results = $this->db->query("select user_id from users WHERE mobile_number='".$mobile_number."' and register_type = 1")->result_array(); #and register_type='".$register_type."'
			}
			
			if ( count($results) > 0 ) {
				echo "taken";	
			}else{
				echo 'not_taken';
			}
			exit();
		}
	}


	// Pan unique validation
	function panUnique()
	{
		$pan_no = $_POST['pan_number'];
		if (isset($_POST['id']))
		{
			$query = "select pan_number from per_people_all where pan_number = '".$pan_no."' and user_id != '".$_POST['id']."' ";
			$ChkExist = $this->db->query($query)->result_array();

			if (count($ChkExist) > 0) {
				echo 'already_taken';die;
			}
			else {
				echo 'not_taken';die;
			}
		}
		else
		{
			$query = "select pan_number from per_people_all where pan_number = '".$pan_no."' ";
			$ChkExist = $this->db->query($query)->result_array();

			if (count($ChkExist) > 0) {
				echo 'already_taken';die;
			}
			else {
				echo 'not_taken';die;
			}
		}
		
	}

	function aadhaarUnique()
	{
		$aadhaar_no = $_POST['aadhaar_number'];
		if (isset($_POST['id']))
		{
			$query = "select aadhaar_number from users where aadhaar_number = '".$aadhaar_no."'and user_id != '".$_POST['id']."' and register_type = 1";
			$ChkExist = $this->db->query($query)->result_array();

			if (count($ChkExist) > 0) {
				echo 'already_taken';die;
			}
			else {
				echo 'not_taken';die;
			}
		}
		else
		{
			$query = "select aadhaar_number from users where aadhaar_number = '".$aadhaar_no."' and 
				user_id != '".$_POST['id']."' and
			register_type = 1";
			$ChkExist = $this->db->query($query)->result_array();

			if (count($ChkExist) > 0) {
				echo 'already_taken';die;
			}
			else {
				echo 'not_taken';die;
			}
		}
			
	}

	#Manage Salary Slip Starts
	function ManageEmployeeSalaryslip($type = '', $id = '')
	{
		if (empty($this->user_id))
		{
			redirect(base_url() . 'admin/adminLogin', 'refresh');
		}
	
		$page_data['type'] = $type;
		$page_data['id'] = $id;
		
		$page_data['ManageEmployee'] = 1;
		$page_data['page_name']  = 'employee/ManageEmployeeSalaryslip';
		$page_data['page_title'] = 'Manage Employee Salaryslip';
		
		switch($type)
		{
			
			case "add": #View
				if($_POST)
				{
					$data['employee_id'] = $this->input->post('employee_id');
					$data['num_of_days'] = $this->input->post('num_of_days');
					$data['salary'] = $this->input->post('salary');
					$data['monthly_wages'] = $this->input->post('monthly_wages');
					$data['basic_salary'] = $this->input->post('basic_salary');
					$data['allowance'] = $this->input->post('allowance');
					$data['other_benefits'] = $this->input->post('other_benefits');
					$data['gross_wages'] = $this->input->post('gross_wages');
					$data['pf_deduction'] = $this->input->post('pf_deduction');
					$data['esi_deduction'] = $this->input->post('esi_deduction');
					$data['pf_employer_deduction'] = $this->input->post('pf_employer_deduction');
					$data['esi_employer_deduction'] = $this->input->post('esi_employer_deduction');
					$data['total_deduction'] = $this->input->post('total_deduction');
					$data['employer_contribution'] = $this->input->post('employer_contribution');
					$data['net_salary'] = $this->input->post('net_salary');
					$data['monthly_salary'] = $this->input->post('monthly_salary');
					
					$data['created_date'] = time();
					
					$data['paid_days'] = $this->input->post('paid_days');
					$data['lop_days'] = $this->input->post('lop_days');
					$data['pay_period'] = $this->input->post('pay_period');
					$data['pay_date'] = $this->input->post('pay_date');
					$data['esi_no'] = $this->input->post('esi_no');
					$data['reimbursement_1'] = $this->input->post('reimbursement_1');
					$data['reimbursement_2'] = $this->input->post('reimbursement_2');
					$data['tds'] = $this->input->post('tds');
					
					#print_r ($_POST);exit;
					$this->db->insert('emp_salaryslip', $data);
					$id = $this->db->insert_id();
					
					if($id !="")
					{
						$this->session->set_flashdata('flash_message' , "Salary Slip added Successfully!");
						redirect(base_url() . 'employee/ManageEmployeeSalaryslip', 'refresh');
					}
				}
			break;
			
			case "edit": #edit
					
				$page_data['edit_data'] = $this->db->query("select * from emp_salaryslip 
				where emp_salaryslip_id='".$id."' ")->result_array();
				
				if($_POST)
				{
					#Personal details
					$data['employee_id'] = $this->input->post('employee_id');
					$data['num_of_days'] = $this->input->post('num_of_days');
					$data['salary'] = $this->input->post('salary');
					$data['monthly_wages'] = $this->input->post('monthly_wages');
					$data['basic_salary'] = $this->input->post('basic_salary');
					$data['allowance'] = $this->input->post('allowance');
					$data['other_benefits'] = $this->input->post('other_benefits');
					$data['gross_wages'] = $this->input->post('gross_wages');
					$data['pf_deduction'] = $this->input->post('pf_deduction');
					$data['esi_deduction'] = $this->input->post('esi_deduction');
					$data['pf_employer_deduction'] = $this->input->post('pf_employer_deduction');
					$data['esi_employer_deduction'] = $this->input->post('esi_employer_deduction');
					$data['total_deduction'] = $this->input->post('total_deduction');
					$data['employer_contribution'] = $this->input->post('employer_contribution');
					$data['net_salary'] = $this->input->post('net_salary');
					$data['monthly_salary'] = $this->input->post('monthly_salary');
					
					$data['paid_days'] = $this->input->post('paid_days');
					$data['lop_days'] = $this->input->post('lop_days');
					$data['pay_period'] = $this->input->post('pay_period');
					$data['pay_date'] = $this->input->post('pay_date');
					$data['esi_no'] = $this->input->post('esi_no');
					$data['reimbursement_1'] = $this->input->post('reimbursement_1');
					$data['reimbursement_2'] = $this->input->post('reimbursement_2');
					$data['tds'] = $this->input->post('tds');
					
					$this->db->where('emp_salaryslip_id', $id);
					$result = $this->db->update('emp_salaryslip', $data);
					
					if($result)
					{
						$this->session->set_flashdata('flash_message' , "Salary Slip updated Successfully!");
						redirect(base_url() . 'employee/ManageEmployeeSalaryslip', 'refresh');
					}
				}
			break;
			case "view":
				
				$query = "select 
					emp_salaryslip.*,
					users.first_name,
					users.date_of_joining,
					users.uan_number,
					users.bank_account_no,
					emp_designations.designation_name
					
					from emp_salaryslip
					
				Left join users on users.user_id = emp_salaryslip.employee_id
				Left join emp_designations on emp_designations.designation_id = users.designation_id
				where emp_salaryslip.emp_salaryslip_id='".$id."' 
				";
				#echo $query;exit;
				$page_data["edit_data"] = $this->db->query($query)->result_array();
			break;
			
			/* case "delete": #Delete
				$this->db->where('expenses_id', $id);
				$this->db->delete('users');
				
				$this->session->set_flashdata('flash_message' , "Customer deleted successfully!");
				redirect(base_url() . 'employee/ManageEmployee', 'refresh');
			break; */
			
			
			default : #Manage
				$page_data["totalRows"] = $totalRows = $this->employee_model->getManageEmployeeSalaryslipCount();#
	
				if(!empty($_SESSION['PAGE']))
				{$limit = $_SESSION['PAGE'];
				}else{$limit = 10;}
				
				if (!empty($_GET['keywords'])) {
					$base_url = base_url('employee/ManageEmployeeSalaryslip?keywords='.$_GET['keywords']);
				} else {
					$base_url = base_url('employee/ManageEmployeeSalaryslip?keywords=');
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
				
				$page_data['resultData']  = $result = $this->employee_model->getManageEmployeeSalaryslip($limit, $offset);
			
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
		
	function ajaxDeductionPayslip()
	{
		if (isset($_POST['id'])) 
		{
			$emp_assign_id = $_POST['id'];
			
			$getQry="select deduction_status from hr_emp_assign_payslip_categories where emp_assign_id='".$emp_assign_id."'";
			$getDeduction = $this->db->query($getQry)->result_array();
			
			$deduction_status = isset($getDeduction[0]['deduction_status']) ? $getDeduction[0]['deduction_status'] : 0;
			
			if($deduction_status == 0)
			{
				$status = 1;
			}
			else if($deduction_status == 0)
			{
				$status = 0;
			}
			
			/* $this->db->where('emp_assign_id',$emp_assign_id);
			$this->db->update('hr_emp_assign_payslip_categories',['deduction_status'=>$status]);	
			 */
			$data['deduction_status'] = $status;
			$this->db->where('emp_assign_id', $emp_assign_id);
			$result = $this->db->update('hr_emp_assign_payslip_categories', $data);
			
			echo 'ok';exit;
		}
	}
	
	function ManageFixedExpences($type = '', $id = '', $status = '')
    {
		if (empty($this->user_id))
        {
			redirect(base_url() . 'admin/adminLogin', 'refresh');
		}
	
		$page_data['type'] = $type;
		$page_data['id'] = $id;
		
		$page_data['system_settings'] = 1;
		$page_data['setup_settings'] = 1;
		$page_data['page_name']  = 'employee/ManageFixedExpences';
		$page_data['page_title'] = 'Manage Fixed Expences';
		
		switch($type)
		{
			case "add": #View
				if($_POST)
				{
					$data['expense_name'] = $this->input->post('expense_name');
					
					$ChkExist = $this->db->query("select expenses_id from emp_fixed_expenses_types where expense_name='".$data['expense_name']."' ")->result_array();
					
					if( count($ChkExist) > 0)
					{
						$this->session->set_flashdata('error_message' , "Sorry! Expense Name Already exist!");
						redirect($_SERVER['HTTP_REFERER'], 'refresh');
					}
					
					$data['expense_description'] = $this->input->post('expense_description');
					$data['expense_status'] = 1;
					
					$this->db->insert('emp_fixed_expenses_types', $data);
					$id = $this->db->insert_id();
					
					if($id !="")
					{
						$this->session->set_flashdata('flash_message' , "Fixed expences added Successfully!");
						redirect(base_url() . 'employee/ManageFixedExpences', 'refresh');
					}
				}
			break;
			
			case "edit": #edit
					
				$page_data['edit_data'] = $this->db->query("select * from emp_fixed_expenses_types 
				where expenses_id='".$id."' ")->result_array();
				
				if($_POST)
				{
					#Personal details
					$data['expense_name'] = $this->input->post('expense_name');
					
					$ChkExist = $this->db->query("select expenses_id from emp_fixed_expenses_types where expense_name='".$data['expense_name']."' AND expenses_id !='".$id."' ")->result_array();
					
					if( count($ChkExist) > 0)
					{
						$this->session->set_flashdata('error_message' , "Sorry! Expense Name Already exist!");
						redirect($_SERVER['HTTP_REFERER'], 'refresh');
					}
					$data['expense_description'] = $this->input->post('expense_description');
					
					$this->db->where('expenses_id', $id);
					$result = $this->db->update('emp_fixed_expenses_types', $data);
					
					if($result)
					{
						$this->session->set_flashdata('flash_message' , "Fixed expences updated Successfully!");
						redirect(base_url() . 'employee/ManageFixedExpences', 'refresh');
					}
				}
			break;
			
			/* case "delete": #Delete
				$this->db->where('expenses_id', $id);
				$this->db->delete('users');
				
				$this->session->set_flashdata('flash_message' , "Customer deleted successfully!");
				redirect(base_url() . 'employee/ManageEmployee', 'refresh');
			break; */
			
			case "status": #Block & Unblock
				if($status == 1){
					$data['expense_status'] = 1;
					$succ_msg = 'Fixed Expences unblocked successfully!';
				}else{
					$data['expense_status'] = 0;
					$succ_msg = 'Fixed Expences blocked successfully!';
				}
				$this->db->where('expenses_id', $id);
				$this->db->update('emp_fixed_expenses_types', $data);
				$this->session->set_flashdata('flash_message' , $succ_msg);
				redirect(base_url() . 'employee/ManageFixedExpences', 'refresh');
			break;
			
			default : #Manage
				$page_data["totalRows"] = $totalRows = $this->employee_model->getManageFixedExpCount();#
	
				if(!empty($_SESSION['PAGE']))
				{$limit = $_SESSION['PAGE'];
				}else{$limit = 10;}
				
				if (!empty($_GET['keywords'])) {
					$base_url = base_url('employee/ManageFixedExpences?keywords='.$_GET['keywords']);
				} else {
					$base_url = base_url('employee/ManageFixedExpences?keywords=');
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
				
				$page_data['resultData']  = $result = $this->employee_model->getManageFixedExp($limit, $offset);
			
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
	
	function ManageDesignation($type = '', $id = '', $status = '')
    {
		if (empty($this->user_id))
        {
			redirect(base_url() . 'admin/adminLogin', 'refresh');
		}
	
		$page_data['type'] = $type;
		$page_data['id'] = $id;
		
		$page_data['system_settings'] = 1;
		$page_data['setup_settings'] = 1;
		$page_data['page_name']  = 'employee/ManageDesignation';
		$page_data['page_title'] = 'Manage Designations';
		
		switch($type)
		{
			case "add": #View
				if($_POST)
				{
					$data['designation_name'] = $this->input->post('designation_name');
					
					$ChkExist = $this->db->query("select designation_id from emp_designations where designation_name='".$data['designation_name']."' ")->result_array();
					
					if( count($ChkExist) > 0)
					{
						$this->session->set_flashdata('error_message' , "Sorry! Designation Already exist!");
						redirect($_SERVER['HTTP_REFERER'], 'refresh');
					}
					
					$data['designation_description'] = $this->input->post('designation_description');
					$data['designation_status'] = 1;
					
					$this->db->insert('emp_designations', $data);
					$id = $this->db->insert_id();
					
					if($id !="")
					{
						$this->session->set_flashdata('flash_message' , "Designation added Successfully!");
						redirect(base_url() . 'employee/ManageDesignation', 'refresh');
					}
				}
			break;
			
			case "edit": #edit
					
				$page_data['edit_data'] = $this->db->query("select * from emp_designations 
				where designation_id='".$id."' ")->result_array();
				
				if($_POST)
				{
					$data['designation_name'] = $this->input->post('designation_name');
					
					$ChkExist = $this->db->query("select designation_id from emp_designations where designation_name='".$data['designation_name']."' AND designation_id !='".$id."' ")->result_array();
					
					if( count($ChkExist) > 0)
					{
						$this->session->set_flashdata('error_message' , "Sorry! Designation Already exist!");
						redirect($_SERVER['HTTP_REFERER'], 'refresh');
					}
					$data['designation_description'] = $this->input->post('designation_description');
					
					$this->db->where('designation_id', $id);
					$result = $this->db->update('emp_designations', $data);
					
					if($result)
					{
						$this->session->set_flashdata('flash_message' , "Designation updated Successfully!");
						redirect(base_url() . 'employee/ManageDesignation', 'refresh');
					}
				}
			break;
			
			/* case "delete": #Delete
				$this->db->where('expenses_id', $id);
				$this->db->delete('users');
				
				$this->session->set_flashdata('flash_message' , "Customer deleted successfully!");
				redirect(base_url() . 'employee/ManageEmployee', 'refresh');
			break; */
			
			case "status": #Block & Unblock
				if($status == 1){
					$data['designation_status'] = 1;
					$succ_msg = 'Designation unblocked successfully!';
				}else{
					$data['designation_status'] = 0;
					$succ_msg = 'Designation blocked successfully!';
				}
				$this->db->where('designation_id', $id);
				$this->db->update('emp_designations', $data);
				$this->session->set_flashdata('flash_message' , $succ_msg);
				redirect(base_url() . 'employee/ManageDesignation', 'refresh');
			break;
			
			default : #Manage
				$page_data["totalRows"] = $totalRows = $this->employee_model->getManageDesinationsCount();
	
				if(!empty($_SESSION['PAGE']))
				{$limit = $_SESSION['PAGE'];
				}else{$limit = 10;}
				
				if (!empty($_GET['keywords'])) {
					$base_url = base_url('employee/ManageDesignation?keywords='.$_GET['keywords']);
				} else {
					$base_url = base_url('employee/ManageDesignation?keywords=');
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
				
				$page_data['resultData']  = $result = $this->employee_model->getManageDesinations($limit, $offset);
			
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
	
	
	public function UsernameExist()
	{
		if ( isset($_POST['user_name_check']) && $_POST['user_name_check'] == 1) 
		{
			$user_name = $_POST['user_name'];
			#$register_type = $_POST['register_type'];
			
			$results = $this->db->query("select user_id from users WHERE user_name='".$user_name."' or email='".$user_name."' ")->result_array(); #and register_type='".$register_type."'
			if ( count($results) > 0 ) {
				echo "taken";	
			}else{
				echo 'not_taken';
			}
			exit();
		}
	}
	
	public function salaryslipDetails($id="")
	{
		if (empty($this->user_id))
		{
			redirect(base_url() . 'admin/adminLogin', 'refresh');
		}
		$page_data['ManageEmployee'] = 1;
		$page_data['id'] = $id;
		
		$page_data['page_name']  = 'employee/salaryslipDetails';
		$page_data['page_title'] = 'Salary Slip Details';

		$this->load->view($this->adminTemplate, $page_data);
	}
			
	#Manage Salary Slip Ends
	function salaryslipDetailsPDF($id="")
    {
		if (empty($this->user_id))
        {
			redirect(base_url() . 'admin/adminLogin', 'refresh');
		}
		
		$page_data['id'] = $id;
		$page_data['ManageEmployee'] = 1;
		$page_data['page_title'] = 'Save As PDF';
		
		ob_start();
		$html = ob_get_clean();
		$html = utf8_encode($html);

		$html = $this->load->view('backend/employee/salaryslipDetailsPDF',$page_data,true);
		
		#echo $html;exit;
		
		include(APPPATH.'third_party/mpdf/mpdf.php');
		
        $mpdf = new mPDF();
        $mpdf->allow_charset_conversion = true;
        $mpdf->charset_in = 'UTF-8';
        $mpdf->WriteHTML($html);
		
        $mpdf->Output('project_invoice.pdf','I');
        #$mpdf->Output($upload_dir,'patient.pdf','I');
		#$mpdf->Output('uploads/generate_pdf/patient.pdf', 'F');
		#$this->load->view($this->adminTemplate, $page_data);
	}
	
	public function getAttachedDocuments($category_id="")
	{
		$data = $this->db->select('
				document.category_id,
				document.category_name
			')
		->from('user_document_categories as document')
		->where('document.category_id',$category_id)
		->get()
		->result();
		
		$documentType = "<select class='form-control' id='document_type' name='document_type[]'><option value=''>- Select Document Type -</option>";
		foreach($this->document_type as $key => $value)
		{
			$documentType .="<option value='".$key."'>".$value."</option>";
		}
		$documentType .="</select>";
		$data['documentType'] = $documentType;
		#$data['discount'] = $this->db->get_where('discount',array('discount_status'=>1))->result();
		#$data['tax'] = $this->db->get_where('tax',array('tax_status'=>1))->result();
		echo json_encode($data);
	}

	#Manage Position Starts
	function ManagePositions($type = '', $id = '', $status = '')
    {
		if (empty($this->user_id))
        {
			redirect(base_url() . 'admin/adminLogin', 'refresh');
		}
	
		$page_data['type'] = $type;
		$page_data['id'] = $id;
		
		$page_data['MasterData'] = 1;
		$page_data['page_name']  = 'employee/ManagePositions';
		$page_data['page_title'] = 'Positions';
		
		switch($type)
		{
			case "add": #View
				if($_POST)
				{
					$data['position_name'] = $this->input->post('position_name');
					$data['position_status'] = 1;
					$this->db->insert('hr_positions', $data);
					$id = $this->db->insert_id();
					
					if($id !="")
					{
						$this->session->set_flashdata('flash_message' , "Position added Successfully!");
						redirect(base_url() . 'employee/ManagePositions', 'refresh');
					}
				}
			break;
			
			case "edit": #edit
				
				$page_data['edit_data'] = $this->db->query("select * from hr_positions where position_id='".$id."' ")
								->result_array();
				if($_POST)
				{
					$data['position_name'] = $this->input->post('position_name');
					
					$this->db->where('position_id', $id);
					$result = $this->db->update('hr_positions', $data);
					
					if($result)
					{
						$this->session->set_flashdata('flash_message' , "Position updated Successfully!");
						redirect(base_url() . 'employee/ManagePositions', 'refresh');
					}
				}
			break;
			
			case "delete": #Delete
				$this->db->where('position_id', $id);
				$this->db->delete('hr_positions');
				
				$this->session->set_flashdata('flash_message' , "Position deleted successfully!");
				redirect(base_url() . 'employee/ManagePositions', 'refresh');
			break;
			
			case "status": #Block & Unblock
				if($status == 1){
					$data['position_status'] = 1;
					$succ_msg = 'Position unblocked successfully!';
				}else{
					$data['position_status'] = 0;
					$succ_msg = 'Position blocked successfully!';
				}
				$this->db->where('position_id', $id);
				$this->db->update('hr_positions', $data);
				$this->session->set_flashdata('flash_message' , $succ_msg);
				redirect(base_url() . 'employee/ManagePositions', 'refresh');
			break;
			
			default : #Manage
				$page_data["totalRows"] = $totalRows = $this->employee_model->getManagePositionsCount();#
	
				if(!empty($_SESSION['PAGE']))
				{$limit = $_SESSION['PAGE'];
				}else{$limit = 10;}
				
				if (!empty($_GET['keywords'])) {
					$base_url = base_url('employee/ManagePositions?keywords='.$_GET['keywords']);
				} else {
					$base_url = base_url('employee/ManagePositions?keywords=');
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
				
				$page_data['resultData']  = $result = $this->employee_model->getManagePositions($limit, $offset);
			
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
	#Manage Position Ends
	
	#Manage Qualification
    function manage_qualification($type = '', $id = '', $status = '')
    {
		if (empty($this->user_id))
        {
			redirect(base_url() . 'admin/login', 'refresh');
		}
		
		$page_data['type'] = $type;
		$page_data['id'] = $id;
		
		$page_data['system_settings']  = 1;
		$page_data['setup_settings'] = 1;
		$page_data['page_name']  = 'employee/manage_qualification';
		$page_data['page_title'] = 'Manage Qualification';
		
		switch($type)
		{
			case "add": #Add
				if($_POST)
				{
					$data['qualification_name'] = $this->input->post('qualification_name');
					$data['qualification_status'] = 1;
					
					# Qualification exist start here
						$chkExistQualification = $this->db->query("select qualification_id from qualification
							where 
								qualification_name='".$data['qualification_name']."'
								")->result_array();
								
						if(count($chkExistQualification) > 0)
						{
							$this->session->set_flashdata('error_message' , " Qualification Name already exist!");
							redirect(base_url() . 'employee/manage_qualification/add', 'refresh');
						}
					# Qualification exist end here
					
					$this->db->insert('qualification', $data);
					$id = $this->db->insert_id();
					if($id !="")
					{
						$this->session->set_flashdata('flash_message' ,'Qualification Added Successfully!');
						redirect(base_url() . 'employee/manage_qualification', 'refresh');
					}
				}
			break;
			
			case "edit": #Edit
				$page_data['edit_data'] = $this->db->get_where('qualification', array('qualification_id' => $id))
										->result_array();
				if($_POST)
				{
					$data['qualification_name'] = $this->input->post('qualification_name');
					
					# Qualification exist start here
						$chkExistQualification = $this->db->query("select qualification_id from qualification
							where 
								qualification_id !='".$id."' and
								
									(qualification_name='".$data['qualification_name']."')
										")->result_array();
								
						if(count($chkExistQualification) > 0)
						{
							$this->session->set_flashdata('error_message' , " Qualification already exist!");
							redirect(base_url() . 'employee/manage_qualification/edit/'.$id, 'refresh');
						}
					# Qualification exist end here
					
					$this->db->where('qualification_id', $id);
					$result = $this->db->update('qualification', $data);
					if($result > 0)
					{
						$this->session->set_flashdata('flash_message' , 'Qualification Updated Successfully!');
						redirect(base_url() . 'employee/manage_qualification', 'refresh');
					}
				}
			break;
			
			case "delete": #Delete
				$this->db->where('qualification_id', $id);
				$this->db->delete('qualification');
				$this->session->set_flashdata('flash_message' , get_phrase('qualification_deleted_successfully!'));
				redirect(base_url() . 'employee/manage_qualification', 'refresh');
			break;
			
			case "status": #Block & Unblock
				if($status == 1){
					$data['qualification_status'] = 1;
					$succ_msg = 'Qualification unblocked successfully!';
				}else{
					$data['qualification_status'] = 0;
					$succ_msg = 'Qualification blocked successfully!';
				}
				$this->db->where('qualification_id', $id);
				$this->db->update('qualification', $data);
				$this->session->set_flashdata('flash_message' , $succ_msg);
				redirect(base_url() . 'employee/manage_qualification', 'refresh');
			break;
			
			default : #Manage
				$page_data["totalRows"] = $totalRows = $this->employee_model->getManageQualificationCount();#

				if(!empty($_SESSION['PAGE']))
				{$limit = $_SESSION['PAGE'];
				}else{$limit = 10;}
				
				if (!empty($_GET['keywords'])) {
					$base_url = base_url('employee/manage_qualification?keywords='.$_GET['keywords']);
				} else {
					$base_url = base_url('employee/manage_qualification?keywords=');
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
				
				$page_data['qualification']  = $result = $this->employee_model->getManageQualification($limit, $offset);
			
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
			/* default : #Manage
				$page_data['qualification'] = $result = $this->db->query("select qualification.* from qualification
				")->result_array();
			break;
		} */
		$this->load->view($this->adminTemplate, $page_data);
	}
	
	function ManageDepartment($type = '', $id = '', $status = '')
    {
		if (empty($this->user_id))
        {
			redirect(base_url() . 'admin/adminLogin', 'refresh');
		}
	
		$page_data['type'] = $type;
		$page_data['id'] = $id;
		
		$page_data['system_settings'] = 1;
		$page_data['setup_settings'] = 1;
		$page_data['page_name']  = 'employee/ManageDepartment';
		$page_data['page_title'] = 'Departments';
		
		switch($type)
		{
			case "add": #View
				if($_POST)
				{
					$data['department_name'] = $this->input->post('department_name');
					
					$ChkExist = $this->db->query("select department_id from emp_departments where department_name='".$data['department_name']."' ")->result_array();
					
					if( count($ChkExist) > 0)
					{
						$this->session->set_flashdata('error_message' , "Sorry! Department Already exist!");
						redirect($_SERVER['HTTP_REFERER'], 'refresh');
					}
					
					$data['department_description'] = $this->input->post('department_description');
					$data['department_status'] = 1;
					
					$this->db->insert('emp_departments', $data);
					$id = $this->db->insert_id();
					
					if($id !="")
					{
						$this->session->set_flashdata('flash_message' , "Department added Successfully!");
						redirect(base_url() . 'employee/ManageDepartment', 'refresh');
					}
				}
			break;
			
			case "edit": #edit
					
				$page_data['edit_data'] = $this->db->query("select * from emp_departments 
				where department_id='".$id."' ")->result_array();
				
				if($_POST)
				{
					$data['department_name'] = $this->input->post('department_name');
					
					$ChkExist = $this->db->query("select department_id from emp_departments where department_name='".$data['department_name']."' AND department_id !='".$id."' ")->result_array();
					
					if( count($ChkExist) > 0)
					{
						$this->session->set_flashdata('error_message' , "Sorry! Department Already exist!");
						redirect($_SERVER['HTTP_REFERER'], 'refresh');
					}
					$data['department_description'] = $this->input->post('department_description');
					
					$this->db->where('department_id', $id);
					$result = $this->db->update('emp_departments', $data);
					
					if($result)
					{
						$this->session->set_flashdata('flash_message' , "Department updated Successfully!");
						redirect(base_url() . 'employee/ManageDepartment', 'refresh');
					}
				}
			break;
			
			/* case "delete": #Delete
				$this->db->where('expenses_id', $id);
				$this->db->delete('users');
				
				$this->session->set_flashdata('flash_message' , "Customer deleted successfully!");
				redirect(base_url() . 'employee/ManageEmployee', 'refresh');
			break; */
			
			case "status": #Block & Unblock
				if($status == 1){
					$data['department_status'] = 1;
					$succ_msg = 'Department unblocked successfully!';
				}else{
					$data['department_status'] = 0;
					$succ_msg = 'Department blocked successfully!';
				}
				$this->db->where('department_id', $id);
				$this->db->update('emp_departments', $data);
				$this->session->set_flashdata('flash_message' , $succ_msg);
				redirect(base_url() . 'employee/ManageDepartment', 'refresh');
			break;
			
			default : #Manage
				$page_data["totalRows"] = $totalRows = $this->employee_model->getManageDepartmentCount();
	
				if(!empty($_SESSION['PAGE']))
				{$limit = $_SESSION['PAGE'];
				}else{$limit = 10;}
				
				if (!empty($_GET['keywords'])) {
					$base_url = base_url('employee/ManageDepartment?keywords='.$_GET['keywords']);
				} else {
					$base_url = base_url('employee/ManageDepartment?keywords=');
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
				
				$page_data['resultData']  = $result = $this->employee_model->getManageDepartment($limit, $offset);
			
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
	
	# Employee Ratings
	function ManageRating($type = '', $id = '', $status = '')
    {
		if (empty($this->user_id))
        {
			redirect(base_url() . 'admin/adminLogin', 'refresh');
		}
	
		$page_data['type'] = $type;
		$page_data['id'] = $id;
		$page_data['status'] = $status;
		
		$page_data['ManageEmployee'] = 1;
		#$page_data['system_settings'] = 1;
		$page_data['page_name']  = 'employee/ManageRating';
		$page_data['page_title'] = 'Manage Rating';

		#Rating Import start here
		if( isset($_POST["importRating"]) )
		{
			if( isset($_POST["user_id"]) && $_POST["user_id"] == 0 )
			{
				$condition ="";
			}
			else
			{
				$condition =" and user_id='".$_POST["user_id"]."'";
			}

			$financial_year_id = $_POST["financial_year_id"];
			$period_id = $_POST["period_id"];

			$periodDetailsQry = "select month,year from emp_periods 
				where 
					financial_year_id='".$financial_year_id."' and 
						period_id='".$period_id."' ";
			$getPeriodDetails = $this->db->query($periodDetailsQry)->result_array();

			$periodMonth = isset($getPeriodDetails[0]["month"]) ? $getPeriodDetails[0]["month"] : 0;
			$periodYear = isset($getPeriodDetails[0]["year"]) ? $getPeriodDetails[0]["year"] : 0;

			//$periodRatingsQry = "select user_id,management_final_rating from emp_apprisal_header 
			$periodRatingsQry = "select user_id,management_final_rating from emp_apprisal_header 
				where 
					period_month='".$periodMonth."' and 
						period_year='".$periodYear."' and 
							management_final_rating !=''

							$condition
					";
			$getPeriodRatings = $this->db2->query($periodRatingsQry)->result_array(); #DB 2
			
			//echo $getPeriodRatings[0]["user_id"];exit;


			if(count($getPeriodRatings) > 0)
			{
				foreach( $getPeriodRatings as $rating )
				{
					$user_id = $rating["user_id"];
					$management_final_rating = $rating["management_final_rating"];

					$checkExist = $this->db->query("select line_id from emp_rating_line 
								where 
									period_id = '".$period_id."' and 
										financial_year_id = '".$financial_year_id."' and 
											user_id = '".$user_id."' 
								")->result_array();
									
                    if (count($checkExist) == 0) 
					{
                        $RatingData = array(
                            "user_id"            => $rating["user_id"],
                            "emp_rating"         => round($rating["management_final_rating"],1),
                            "financial_year_id"  => $financial_year_id,
                            "period_id"          => $period_id,
                            "created_date"       => time(),
                        );
                        
                        $this->db->insert('emp_rating_line', $RatingData);
                        $id_3 = $this->db->insert_id();
                    }
					else
					{
						$RatingData = array(
                            "user_id"            => $rating["user_id"],
                            "emp_rating"         => round($rating["management_final_rating"],1),
                            "financial_year_id"  => $financial_year_id,
                            "period_id"          => $period_id
                        );

						$this->db->where('financial_year_id', $financial_year_id);
						$this->db->where('period_id',  $period_id);
						$this->db->where('user_id',  $user_id);
						$result = $this->db->update('emp_rating_line', $RatingData);
					}
				}

				$this->session->set_flashdata('flash_message' , "Rating imported successfully!");
				redirect(base_url() . 'employee/ManageRating/grid_view', 'refresh');
			}
			else
			{
				$this->session->set_flashdata('error_message' , "No employee rating!");
				redirect(base_url() . 'employee/ManageRating/grid_view', 'refresh');
			}
		}
		#Rating Import end here
	
		switch($type)
		{
			case "add": #View
			
				if($_POST)
				{
					/*$data['period_id'] = $this->input->post('period_id');
					#$data['user_id'] = $this->input->post('user_id');
					$data['created_date'] = strtotime(date('d-m-Y h:i:s a',time()));
										
					$this->db->insert('emp_rating_header', $data);
					$id = $this->db->insert_id();
					
					if($id !="")
					{ */
				
					# Add Rating for employee
					if( isset($_POST['rating']) && $_POST['rating'] !="" )
					{
						$count=count(array_filter($_POST['user_id']));
						
						for($dp=0;$dp<$count;$dp++)
						{																	
							$period_id = $data_2['period_id'] = $this->input->post('period_id');
							$financial_year_id = $data_2['financial_year_id'] = $this->input->post('financial_year_id');
							$user_id = $data_2['user_id'] =  isset($_POST['user_id'][$dp]) ? $_POST['user_id'][$dp] :"";
							$data_2['emp_rating'] = isset($_POST['rating'][$dp]) ? $_POST['rating'][$dp] :"";
							
							# Exist start here
							$checkExist = $this->db->query("select line_id from emp_rating_line 
								where 
									period_id = '".$period_id."' and 
										financial_year_id = '".$financial_year_id."' and 
											user_id = '".$user_id."' 
								")->result_array();
									
							if( count($checkExist) == 0 )
							{
								$data_2['created_date'] = time();

								$this->db->insert('emp_rating_line', $data_2);
								$id_3 = $this->db->insert_id();
							}
							else
							{
								$this->db->where('financial_year_id',  $financial_year_id);
								$this->db->where('period_id',  $period_id);
								$this->db->where('user_id',  $user_id);
								$result = $this->db->update('emp_rating_line', $data_2);
							}
							# Exist end here


							#Check exist calculateed pay amount after creation variable pay Update Start
							$checkExistQry = "select line_id from emp_variable_pay_calc_line
								where 
									user_id='".$user_id."' and
										period_id='".$period_id."' and
											financial_year_id='".$financial_year_id."'
									";
							$checkExist = $this->db->query($checkExistQry)->result_array();
							
							if ( count($checkExist) > 0 ) 
							{ 
								$getVariablePayQry = "select 

									users.user_id,
									users.first_name,
									users.random_user_id,
									emp_salary_structure_line.per_month_inr,
									emp_rating_line.emp_rating,
									emp_rating_line.period_id,
									emp_rating_line.financial_year_id,
									emp_variable_pay_qualifier.percentage as qualifier_percentage
									
									from emp_salary_structure_line 
							
								left join emp_salary_structure_header on 
									emp_salary_structure_header.header_id = emp_salary_structure_line.header_id
								
								left join users on 
									users.user_id = emp_salary_structure_header.user_id
								
								left join emp_rating_line on 
										emp_rating_line.user_id = users.user_id	
									
									left join emp_variable_pay_qualifier on 
										emp_variable_pay_qualifier.rating = emp_rating_line.emp_rating
								
								where 
									users.user_status=1 and 
										users.register_type=1 and 
											emp_salary_structure_header.user_id ='".$user_id."' and 
												emp_salary_structure_line.element_id = 4
									"; #element_id -  Variable Pay
								$getVariablePay = $this->db->query($getVariablePayQry)->result_array();
								
								$ratingperiod = "select emp_rating_line.*, emp_variable_pay_qualifier.percentage from emp_rating_line
								left join emp_variable_pay_qualifier on emp_variable_pay_qualifier.rating  = emp_rating_line.emp_rating
									where 
										user_id='".$user_id."' and
											period_id='".$period_id."' and
												financial_year_id='".$financial_year_id."' 
										";
								$empratingPeriod = $this->db->query($ratingperiod)->result_array();
									
								$per_month_inr = isset($getVariablePay[0]["per_month_inr"]) ? $getVariablePay[0]["per_month_inr"] : 0;
								$qualifier_percentage = isset($empratingPeriod[0]["percentage"]) ? $empratingPeriod[0]["percentage"] : 0;
								#$qualifier_percentage = isset($getVariablePay[0]["qualifier_percentage"]) ? $getVariablePay[0]["qualifier_percentage"] : 0;
								
								$total_variable_pay = $per_month_inr;
								$calculated_pay = $qualifier_percentage / 100 * $per_month_inr;
								
								$lineData = array(
									#"user_id"             => $row['user_id'],
									#"period_id"           => $_POST['period_id'],
									#"created_date"        => time(),
									#"total_variable_pay"  => $total_variable_pay,
									"calculated_pay"      => $calculated_pay,
								);
								
								$this->db->where('financial_year_id',  $financial_year_id);
								$this->db->where('period_id',  $period_id);
								$this->db->where('user_id',  $user_id);
								$result = $this->db->update('emp_variable_pay_calc_line', $lineData);
							}
							#Check exist calculateed pay amount after creation variable pay Update Start
						}
					}
					#Add Rating for employee
			
					$this->session->set_flashdata('flash_message' , "Rating added Successfully!");
					redirect(base_url() . 'employee/ManageRating', 'refresh');
					#}
				}
			break;
			
			case "edit": #edit
					
				$page_data['edit_data'] = $this->db->get_where( 'emp_rating_line', array('line_id' => $id))
										->result_array();
				if($_POST)
				{
					/* $data['period_id'] = $this->input->post('period_id');
					$data['created_date'] = strtotime(date('d-m-Y h:i:s a',time()));
					
					$this->db->where('header_id',  $id);
					$result = $this->db->update('emp_rating_header', $data); 
					
					if($result)
					{*/
						# Add and Remove Rating start
						if( isset($_POST['rating']) && $_POST['rating'] !="" )
						{
							$checkParticipant = $this->db->query("
							select line_id  from emp_rating_line 
							where header_id ='".$id."'
							")->result_array();
							
							/*if(count($checkParticipant) > 0)
							{
								$this->db->where('header_id', $id);
								$this->db->delete('emp_rating_line');
							} */
								
							$count=count(array_filter($_POST['user_id']));
							
							for($dp=0;$dp<$count;$dp++)
							{	
								$line_id =  isset($_POST['line_id'][$dp]) ? $_POST['line_id'][$dp] :"";						
                                $user_id =  isset($_POST['user_id'][$dp]) ? $_POST['user_id'][$dp] :"";	
								$period_id = $this->input->post('period_id');
								$financial_year_id = $this->input->post('financial_year_id');
								$checkExitQuery = "select line_id from emp_rating_line 
									where 
										user_id='".$user_id."' and
											line_id='".$line_id."' 
										
										";
								$queryResult = $this->db->query($checkExitQuery)->result_array();
								
								if(count($queryResult) > 0)
								{
									$data_2['period_id '] = $this->input->post('period_id');
									$data_2['financial_year_id '] = $this->input->post('financial_year_id');
									
									$data_2['emp_rating'] = isset($_POST['rating'][$dp]) ? $_POST['rating'][$dp] :"";
									$this->db->where('line_id',  $line_id);
									$this->db->where('user_id',  $user_id);
									$result = $this->db->update('emp_rating_line', $data_2);
								}
								else
								{
									
									$data_2['financial_year_id '] = $this->input->post('financial_year_id');
									$data_2['period_id '] = $this->input->post('period_id');
									$data_2['user_id'] =  isset($_POST['user_id'][$dp]) ? $_POST['user_id'][$dp] :"";
									$data_2['emp_rating'] = isset($_POST['rating'][$dp]) ? $_POST['rating'][$dp] :"";
									$this->db->insert('emp_rating_line', $data_2);
									$id_3 = $this->db->insert_id();
								}
								
								#Check exist calculateed pay amount after creation variable pay Update Start
									$checkExistQry = "select line_id from emp_variable_pay_calc_line
									where 
										user_id='".$user_id."' and
											period_id='".$period_id."' and
												financial_year_id='".$financial_year_id."'
										";
								$checkExist = $this->db->query($checkExistQry)->result_array();
								
								if ( count($checkExist) > 0 ) 
								{ 
									$getVariablePayQry = "select 

										users.user_id,
										users.first_name,
										users.random_user_id,
										emp_salary_structure_line.per_month_inr,
										emp_rating_line.emp_rating,
										emp_rating_line.period_id,
										emp_rating_line.financial_year_id,
										emp_variable_pay_qualifier.percentage as qualifier_percentage
										
										from emp_salary_structure_line 
								
									left join emp_salary_structure_header on 
										emp_salary_structure_header.header_id = emp_salary_structure_line.header_id
									
									left join users on 
										users.user_id = emp_salary_structure_header.user_id
									
									left join emp_rating_line on 
											emp_rating_line.user_id = users.user_id	
										
										left join emp_variable_pay_qualifier on 
											emp_variable_pay_qualifier.rating = emp_rating_line.emp_rating
									
									where 
										users.user_status=1 and 
											users.register_type=1 and 
												emp_salary_structure_header.user_id ='".$user_id."' and 
													emp_salary_structure_line.element_id = 4
										"; #element_id -  Variable Pay
									$getVariablePay = $this->db->query($getVariablePayQry)->result_array();
									
									$ratingperiod = "select emp_rating_line.*, emp_variable_pay_qualifier.percentage from emp_rating_line
									left join emp_variable_pay_qualifier on emp_variable_pay_qualifier.rating  = emp_rating_line.emp_rating
										where 
											user_id='".$user_id."' and
												period_id='".$period_id."' and
													financial_year_id='".$financial_year_id."'
											";	
									$empratingPeriod = $this->db->query($ratingperiod)->result_array();
										
									$per_month_inr = isset($getVariablePay[0]["per_month_inr"]) ? $getVariablePay[0]["per_month_inr"] : 0;
									$qualifier_percentage = isset($empratingPeriod[0]["percentage"]) ? $empratingPeriod[0]["percentage"] : 0;
									#$qualifier_percentage = isset($getVariablePay[0]["qualifier_percentage"]) ? $getVariablePay[0]["qualifier_percentage"] : 0;
									
									$total_variable_pay = $per_month_inr;
									$calculated_pay = $qualifier_percentage / 100 * $per_month_inr;
									
									$lineData = array(
										#"user_id"             => $row['user_id'],
										#"period_id"           => $_POST['period_id'],
										#"created_date"        => time(),
										#"total_variable_pay"  => $total_variable_pay,
										"calculated_pay"      => $calculated_pay,
									);
									
									$this->db->where('financial_year_id',  $financial_year_id);
									$this->db->where('period_id',  $period_id);
									$this->db->where('user_id',  $user_id);
									$result = $this->db->update('emp_variable_pay_calc_line', $lineData);
								}
								#Check exist calculateed pay amount after creation variable pay Update Start
							}
						}
						#Add and Remove Criteria and Weightage end
						
						
						$this->session->set_flashdata('flash_message' , "Rating updated Successfully!");
						redirect(base_url() . 'employee/ManageRating', 'refresh');
					//}
				}
			break;
			
			/* case "delete": #Delete
				$this->db->where('expenses_id', $id);
				$this->db->delete('users');
				
				$this->session->set_flashdata('flash_message' , "Customer deleted successfully!");
				redirect(base_url() . 'employee/ManageEmployee', 'refresh');
			break; */
			
			case "status": #Block & Unblock
				if($status == 1){
					$data['department_status'] = 1;
					$succ_msg = 'Department unblocked successfully!';
				}else{
					$data['department_status'] = 0;
					$succ_msg = 'Department blocked successfully!';
				}
				$this->db->where('rating_id', $id);
				$this->db->update('emp_ratings', $data);
				$this->session->set_flashdata('flash_message' , $succ_msg);
				redirect(base_url() . 'employee/ManageRating', 'refresh');
			break;
			
			default : #Manage
				$page_data["totalRows"] = $totalRows = $this->employee_model->getManageRatingCount();
	
				if(!empty($_SESSION['PAGE']))
				{$limit = $_SESSION['PAGE'];
				}else{$limit = 10;}
				
				if (!empty($_GET['keywords'])) {
					$base_url = base_url('employee/ManageRating?keywords='.$_GET['keywords']);
				} else {
					$base_url = base_url('employee/ManageRating?keywords=');
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
				
				$page_data['resultData']  = $result = $this->employee_model->getManageRating($limit, $offset);
			
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

	# Employee Leave
	function ManageLeave($type = '', $id = '', $status = '')
    {
		if (empty($this->user_id))
        {
			redirect(base_url() . 'admin/adminLogin', 'refresh');
		}
	
		$page_data['type'] = $type;
		$page_data['id'] = $id;
		$page_data['status'] = $status;
		
		$page_data['ManageEmployee'] = 1;
		#$page_data['system_settings'] = 1;
		$page_data['page_name']  = 'employee/ManageLeave';
		$page_data['page_title'] = 'Manage Leave';
		
		switch($type)
		{	
			case "add": #View
			
				if($_POST)
				{
					/*$data['period_id'] = $this->input->post('period_id');
					$data['created_date'] = strtotime(date('d-m-Y h:i:s a',time()));
					
					$this->db->insert('emp_leave_header', $data);
					$id = $this->db->insert_id();
					
					if($id !="")
					{*/ 
						# Add and Remove Criteria and Weightage start
						if( isset($_POST['leave_days']) && $_POST['leave_days'] !="" )
						{
							$count=count(array_filter($_POST['user_id']));
						
							for($dp=0;$dp<$count;$dp++)
							{	
								//$data_2['header_id '] = $id;
								$period_id = $data_2['period_id '] = $this->input->post('period_id');
								$data_2['financial_year_id '] = $this->input->post('financial_year_id');
								$user_id = $data_2['user_id '] =  isset($_POST['user_id'][$dp]) ? $_POST['user_id'][$dp] :"";
								$data_2['emp_leave'] = isset($_POST['leave_days'][$dp]) ? $_POST['leave_days'][$dp] : '0';
								$data_2['created_date'] = time();
								
								#$data_2['created_date'] = time();
								
								$checkExist = $this->db->query("select line_id from emp_leaves_line 
								where 
									period_id = '".$period_id."' and 
										user_id = '".$user_id."' 
								")->result_array();
									
								if( count($checkExist) == 0 )
								{
									$this->db->insert('emp_leaves_line', $data_2);
									$id_3 = $this->db->insert_id();
								}
								else
								{
									
								}
								# Exist end here
							}
						}
						#Add and Remove Criteria and Weightage end
				
						$this->session->set_flashdata('flash_message' , "Leave added Successfully!");
						redirect(base_url() . 'employee/ManageLeave', 'refresh');
					//}
				}
			break;
			
			case "edit": #edit
					
				$page_data['edit_data'] = $this->db->get_where( 'emp_leaves_line', array('line_id ' => $id))
										->result_array();
				if($_POST)
				{
					
					/* $data['period_id'] = $this->input->post('period_id');
					$data['created_date'] = strtotime(date('d-m-Y h:i:s a',time()));
					
					$this->db->where('header_id',  $id);
					$result = $this->db->update('emp_leave_header', $data);
					
					if($result)
					{ */ 
						
						# Add and Remove Rating start
						if( isset($_POST['leave_days']) && $_POST['leave_days'] !="" )
						{
							$checkParticipant = $this->db->query("
							select line_id  from emp_leaves_line 
							where header_id ='".$id."'
							")->result_array();
							
							/* if(count($checkParticipant) > 0)
							{
								$this->db->where('header_id', $id);
								$this->db->delete('emp_leaves_line');
							} */
								
							$count=count(array_filter($_POST['user_id']));
							
							for($dp=0;$dp<$count;$dp++)
							{		
                                $line_id =  isset($_POST['line_id'][$dp]) ? $_POST['line_id'][$dp] :"";						
                                $user_id =  isset($_POST['user_id'][$dp]) ? $_POST['user_id'][$dp] :"";	

								$checkExitQuery = "select line_id from emp_leaves_line 
									where 
										user_id='".$user_id."' and
											line_id='".$line_id."'
										
										";
								$queryResult = $this->db->query($checkExitQuery)->result_array();

								if(count($queryResult) > 0)
								{
									#$data_2['header_id '] = $id;
									$data_2['period_id '] = $this->input->post('period_id');
									$data_2['financial_year_id '] = $this->input->post('financial_year_id');
									#$data_2['user_id'] =  isset($_POST['user_id'][$dp]) ? $_POST['user_id'][$dp] :"";
									
									$data_2['emp_leave'] = isset($_POST['leave_days'][$dp]) ? $_POST['leave_days'][$dp] :'0';
									
									//$this->db->insert('emp_leaves_line', $data_2);
									//$id_3 = $this->db->insert_id();
									
									//$this->db->where('header_id',  $id);
									$this->db->where('line_id',  $line_id);
									$this->db->where('user_id',  $user_id);
									$result = $this->db->update('emp_leaves_line', $data_2);
								}
								else
								{
									//$data_2['header_id '] = $id;
									$data_2['period_id '] = $this->input->post('period_id');
									$data_2['user_id'] =  isset($_POST['user_id'][$dp]) ? $_POST['user_id'][$dp] :"";
									
									$data_2['emp_leave'] = isset($_POST['leave_days'][$dp]) ? $_POST['leave_days'][$dp] :"";
									
									
									#$data_2['created_date'] = time();
																	
									$this->db->insert('emp_leaves_line', $data_2);
									$id_3 = $this->db->insert_id();
								}
							}
						}
						#Add and Remove Criteria and Weightage end
						
						
						$this->session->set_flashdata('flash_message' , "Leave updated Successfully!");
						redirect(base_url() . 'employee/ManageLeave', 'refresh');
					//}
				}
			break;
			 
			case "delete": #Delete
				$this->db->where('expenses_id', $id);
				$this->db->delete('users');
				
				$this->session->set_flashdata('flash_message' , "deleted successfully!");
				redirect(base_url() . 'employee/ManageEmployee', 'refresh');
			break; 
			
			case "status": #Block & Unblock
				if($status == 1){
					$data['department_status'] = 1;
					$succ_msg = 'Leave unblocked successfully!';
				}else{
					$data['department_status'] = 0;
					$succ_msg = 'Leave blocked successfully!';
				}
				$this->db->where('rating_id', $id);
				$this->db->update('emp_ratings', $data);
				$this->session->set_flashdata('flash_message' , $succ_msg);
				redirect(base_url() . 'employee/ManageRating', 'refresh');
			break;
			
			default : #Manage
				$page_data["totalRows"] = $totalRows = $this->employee_model->getManageLeaveCount();
	
				if(!empty($_SESSION['PAGE']))
				{$limit = $_SESSION['PAGE'];
				}else{$limit = 10;}
				
				if (!empty($_GET['keywords'])) {
					$base_url = base_url('employee/ManageLeave?keywords='.$_GET['keywords']);
				} else {
					$base_url = base_url('employee/ManageLeave?keywords=');
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
				
				$page_data['resultData']  = $result = $this->employee_model->getManageLeave($limit, $offset);
			
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

	# Employee Incentive
	function ManageIncentive($type = '', $id = '', $status = '')
    {
		if (empty($this->user_id))
        {
			redirect(base_url() . 'admin/adminLogin', 'refresh');
		}
	
		$page_data['type'] = $type;
		$page_data['id'] = $id;
		
		$page_data['ManagePayroll'] = 1;
		#$page_data['system_settings'] = 1;
		$page_data['page_name']  = 'employee/ManageIncentive';
		$page_data['page_title'] = 'Revenue Adjustment';
		
		switch($type)
		{
			case "add": #View
				if($_POST)
				{
					
					/* $string_from_date = strtotime($_POST['string_from_date']);
					$string_to_date = strtotime($_POST['string_to_date']);

					if( isset($_POST['string_from_date']) && !empty($_POST['string_from_date']) ){
						$from_date = date("Y-m-d",strtotime($_POST['string_from_date']))	;
					}else{
						$from_date = NULL	;
					}

					if( isset($_POST['string_to_date']) && !empty($_POST['string_to_date']) ){
						$to_date = date("Y-m-d",strtotime($_POST['string_to_date']))	;
					}else{
						$to_date = NULL	;
					}
					
					$data = array(
						#"project_code" 		        =>  $this->input->post('project_code'),
						"user_id" 	          =>  $this->input->post('user_id'),
						"element_id" 	  	  =>  $this->input->post('element_id'),
						"element_category_id" =>  $this->input->post('element_category_id'),
						"remarks" 			  =>  $this->input->post('remarks'),
						"amount" 			  =>  $this->input->post('amount'),
						"financial_year_id"   =>  $this->input->post('financial_year_id'),
						"period_id" 		  =>  $this->input->post('period_id'),
						"string_from_date" 	  =>  $string_from_date,
						"string_to_date" 	  =>  $string_to_date,
						"from_date" 	      =>  $from_date,
						"to_date" 	          =>  $to_date,
						
					);

					$ChkExist = $this->db->query("select incentive_id,from_date,to_date,string_from_date,string_to_date from emp_incentive 
						where user_id='".$data['user_id']."'
							and period_id='".$data['period_id']."'
							and element_id='".$data['element_id']."'
								")->result_array();
					
					if( count($ChkExist) > 0)
					{
						$this->session->set_flashdata('error_message' , "Sorry! Incentive Already exist!");
						redirect($_SERVER['HTTP_REFERER'], 'refresh');
					}
					
					$this->db->insert('emp_incentive', $data);
					$id = $this->db->insert_id();
					
					if($id !="")
					{
						#Update recovery elements after creation payroll start
						$chkQuery = "select line_id from emp_payroll_line 
						left join emp_payroll_header on
							emp_payroll_header.header_id = emp_payroll_line.header_id
						where  
						emp_payroll_line.user_id='".$data['user_id']."' and 
							emp_payroll_header.period_id='".$data['period_id']."' and 
								emp_payroll_line.element_id='".$data['element_id']."' ";
						$chkPayrollEleRecovery = $this->db->query($chkQuery)->result_array();

						if( count($chkPayrollEleRecovery) > 0 )
						{
							$ctc_reference = $this->input->post('amount');
							$ytd = $payrollAmount = $this->input->post('amount');

							$getPrevQry = "select ytd,amount from emp_payroll_line 
								where
									emp_payroll_line.user_id = '".$data['user_id']."' and 
											emp_payroll_line.element_id = '".$data['element_id']."'
									";
							$getPreviousYTD = $this->db->query($getPrevQry)->result_array();

							$previousYTD = 0;

							foreach($getPreviousYTD as $prevousAmt)
							{
								$previousYTD += $prevousAmt["amount"];
							}

							$totalYTDAmount = $ytd + $previousYTD;

							$lineData = array(
								"ctc_reference" => $ctc_reference,
								"amount"        => $payrollAmount,
								"ytd"           => $totalYTDAmount
							);

							$this->db->where('user_id',$data['user_id']);
							$this->db->where('period_id',$data['period_id']);
							$this->db->where('element_id',$data['element_id']);
							$result = $this->db->update('emp_payroll_line', $lineData);
						}
						#Update recovery elements after creation payroll end

						#Update recovery elements after creation Payslip start
						$chkQuery = "select line_id from emp_payslip_line 
						left join emp_payslip_header on
							emp_payslip_header.header_id = emp_payslip_line.header_id
						where  
						emp_payslip_line.user_id='".$data['user_id']."' and 
							emp_payslip_header.period_id='".$data['period_id']."' and 
							emp_payslip_line.element_id='".$data['element_id']."' ";
						$chkPayrollEleRecovery = $this->db->query($chkQuery)->result_array();

						if( count($chkPayrollEleRecovery) > 0 )
						{
							$ctc_reference = $this->input->post('amount');
							$ytd = $payrollAmount = $this->input->post('amount');

							$getPrevQry = "select ytd,amount from emp_payslip_line 
								where
									emp_payslip_line.user_id = '".$data['user_id']."' and 
										emp_payslip_line.element_id = '".$data['element_id']."'
									";
							$getPreviousYTD = $this->db->query($getPrevQry)->result_array();

							$previousYTD = 0;

							foreach($getPreviousYTD as $prevousAmt)
							{
								$previousYTD += $prevousAmt["amount"];
							}

							$totalYTDAmount = $ytd + $previousYTD;

							$lineData = array(
								"ctc_reference" => $ctc_reference,
								"amount"        => $payrollAmount,
								"ytd"           => $totalYTDAmount
							);

							$this->db->where('user_id',$data['user_id']);
							$this->db->where('period_id',$data['period_id']);
							$this->db->where('element_id',$data['element_id']);
							$result = $this->db->update('emp_payslip_line', $lineData);
						}
						#Update recovery elements after creation Payslip end

						$this->session->set_flashdata('flash_message' , "Incentive added Successfully!");
						redirect(base_url() . 'employee/ManageIncentive', 'refresh');
					} */

					if($_POST)
					{
						if( isset($_POST['amount']) && $_POST['amount'] !="" )
						{
							$count=count(array_filter($_POST['user_id']));
							
							for($dp=0;$dp<$count;$dp++)
							{	
								$string_from_date = strtotime($_POST['string_from_date']);
								$string_to_date = strtotime($_POST['string_to_date']);

								if( isset($_POST['string_from_date']) && !empty($_POST['string_from_date']) ){
									$from_date = date("Y-m-d",strtotime($_POST['string_from_date']))	;
								}else{
									$from_date = NULL	;
								}

								if( isset($_POST['string_to_date']) && !empty($_POST['string_to_date']) ){
									$to_date = date("Y-m-d",strtotime($_POST['string_to_date']))	;
								}else{
									$to_date = NULL	;
								}

								$user_id = isset($_POST['user_id'][$dp]) ? $_POST['user_id'][$dp] :"";
								$amount = isset($_POST['amount'][$dp]) ? $_POST['amount'][$dp] : 0;

								$chkExistQuery = "select incentive_id from emp_incentive 
									where 
										element_category_id='".$_POST["element_category_id"]."' and 
											element_id='".$_POST["element_id"]."' and 
												financial_year_id='".$_POST["financial_year_id"]."' and 
													period_id='".$_POST["period_id"]."' and 
														user_id='".$user_id."' ";

								$chkExist = $this->db->query($chkExistQuery)->result_array();
								
								if( count($chkExist) == 0 )
								{
									$data = array(
										"user_id" 	          =>  $user_id,
										"element_id" 	  	  =>  $this->input->post('element_id'),
										"element_category_id" =>  $this->input->post('element_category_id'),
										"remarks" 			  =>  $this->input->post('remarks'),
										"financial_year_id"   =>  $this->input->post('financial_year_id'),
										"period_id" 		  =>  $this->input->post('period_id'),
										"amount" 			  =>  $amount,
										"string_from_date" 	  =>  $string_from_date,
										"string_to_date" 	  =>  $string_to_date,
										"from_date" 	      =>  $from_date,
										"to_date" 	          =>  $to_date,
									);

									$this->db->insert('emp_incentive', $data);
									$id = $this->db->insert_id();
								}
								else
								{
									$data = array(
										"user_id" 	          =>  $user_id,
										"element_id" 	  	  =>  $this->input->post('element_id'),
										"element_category_id" =>  $this->input->post('element_category_id'),
										"remarks" 			  =>  $this->input->post('remarks'),
										"financial_year_id"   =>  $this->input->post('financial_year_id'),
										"period_id" 		  =>  $this->input->post('period_id'),
										"amount" 			  =>  $amount,
										"string_from_date" 	  =>  $string_from_date,
										"string_to_date" 	  =>  $string_to_date,
										"from_date" 	      =>  $from_date,
										"to_date" 	          =>  $to_date,
									);

									$this->db->where('user_id',$user_id);
									$this->db->where('element_id',$_POST["element_id"]);
									$this->db->where('financial_year_id',$_POST['financial_year_id']);
									$this->db->where('period_id',$_POST['period_id']);
									$this->db->where('element_category_id',$_POST['element_category_id']);

									$result = $this->db->update('emp_incentive', $data);

									$id = isset($chkExist[0]["incentive_id"]) ? $chkExist[0]["incentive_id"] : 0;
								}
								
								if($id !="")
								{
									#Update recovery elements after creation payroll start
									$chkQuery = "select line_id from emp_payroll_line 
									left join emp_payroll_header on
										emp_payroll_header.header_id = emp_payroll_line.header_id
									where  
									emp_payroll_line.user_id='".$user_id."' and 
										emp_payroll_header.period_id='".$data['period_id']."' and 
											emp_payroll_line.element_id='".$data['element_id']."' ";
									$chkPayrollEleRecovery = $this->db->query($chkQuery)->result_array();

									if( count($chkPayrollEleRecovery) > 0 )
									{
										$ctc_reference = $amount;
										$ytd = $payrollAmount = $amount;

										$getPrevQry = "select ytd,amount from emp_payroll_line 
											where
												emp_payroll_line.user_id = '".$user_id."' and 
														emp_payroll_line.element_id = '".$data['element_id']."'
												";
										$getPreviousYTD = $this->db->query($getPrevQry)->result_array();

										$previousYTD = 0;

										foreach($getPreviousYTD as $prevousAmt)
										{
											$previousYTD += $prevousAmt["amount"];
										}

										$totalYTDAmount = $ytd + $previousYTD;

										$lineData = array(
											"ctc_reference" => $ctc_reference,
											"amount"        => $payrollAmount,
											"ytd"           => $totalYTDAmount
										);

										$this->db->where('user_id',$user_id);
										$this->db->where('period_id',$data['period_id']);
										$this->db->where('element_id',$data['element_id']);
										$result = $this->db->update('emp_payroll_line', $lineData);
									}
									#Update recovery elements after creation payroll end
								}
							}
						}

						$this->session->set_flashdata('flash_message' , "Recovery added Successfully!");
						redirect(base_url() . 'employee/ManageIncentive', 'refresh');
					}
				}
			break;
			
			case "edit": #edit
					
				$page_data['edit_data'] = $this->db->query("select * from emp_incentive 
				where incentive_id='".$id."' ")->result_array();
				
				if($_POST)
				{
					$string_from_date = strtotime($_POST['string_from_date']);
					$string_to_date = strtotime($_POST['string_to_date']);

					if( isset($_POST['string_from_date']) && !empty($_POST['string_from_date']) ){
						$from_date = date("Y-m-d",strtotime($_POST['string_from_date']))	;
					}else{
						$from_date = NULL	;
					}

					if( isset($_POST['string_to_date']) && !empty($_POST['string_to_date']) ){
						$to_date = date("Y-m-d",strtotime($_POST['string_to_date']))	;
					}else{
						$to_date = NULL	;
					}

					$data = array(
						#"project_code" 	  =>  $this->input->post('project_code'),
						"user_id" 	          =>  $this->input->post('user_id'),
						"element_id" 	  	  =>  $this->input->post('element_id'),
						"element_category_id" =>  $this->input->post('element_category_id'),
						"remarks" 			  =>  $this->input->post('remarks'),
						"amount" 			  =>  $this->input->post('amount'),
						"financial_year_id"   =>  $this->input->post('financial_year_id'),
						"period_id" 		  =>  $this->input->post('period_id'),
						"string_from_date"  =>  $string_from_date,
						"string_to_date" 	=>  $string_to_date,
						"from_date" 	    =>  $from_date,
						"to_date" 	        =>  $to_date,
					);
					
					$ChkExist = $this->db->query("select incentive_id,from_date,to_date,string_from_date,string_to_date from emp_incentive 
					where period_id='".$data['period_id']."' AND
						element_id='".$data['element_id']."' AND
							user_id='".$data['user_id']."' 
								AND incentive_id !='".$id."' ")->result_array();
					
					if( count($ChkExist) > 0)
					{
						$this->session->set_flashdata('error_message' , "Sorry! Incentive Already exist for this month!");
						redirect($_SERVER['HTTP_REFERER'], 'refresh');
					}
					
					$this->db->where('incentive_id', $id);
					$result = $this->db->update('emp_incentive', $data);
					
					if($result)
					{
						#Update recovery elements after creation payroll start
						$chkQuery = "select line_id from emp_payroll_line 
						left join emp_payroll_header on
							emp_payroll_header.header_id = emp_payroll_line.header_id
						where  
						emp_payroll_line.user_id='".$data['user_id']."' and 
							emp_payroll_header.period_id='".$data['period_id']."' and 
								emp_payroll_line.element_id='".$data['element_id']."' ";
						$chkPayrollEleRecovery = $this->db->query($chkQuery)->result_array();

						if( count($chkPayrollEleRecovery) > 0 )
						{
							$ctc_reference = $this->input->post('amount');
							$ytd = $payrollAmount = $this->input->post('amount');

							$getPrevQry = "select ytd,amount from emp_payroll_line 
								where
									emp_payroll_line.user_id = '".$data['user_id']."' and 
										emp_payroll_line.period_id != '".$_POST['period_id']."' and 
											emp_payroll_line.element_id = '".$data['element_id']."'
									";
							$getPreviousYTD = $this->db->query($getPrevQry)->result_array();

							$previousYTD = 0;

							foreach($getPreviousYTD as $prevousAmt)
							{
								$previousYTD += $prevousAmt["amount"];
							}

							$totalYTDAmount = $ytd + $previousYTD;

							$lineData = array(
								"ctc_reference" => $ctc_reference,
								"amount"        => $payrollAmount,
								"ytd"           => $totalYTDAmount
							);

							$this->db->where('user_id',$data['user_id']);
							$this->db->where('period_id',$data['period_id']);
							$this->db->where('element_id',$data['element_id']);
							$result = $this->db->update('emp_payroll_line', $lineData);
						}
						#Update recovery elements after creation payroll end


						#Update recovery elements after creation Payslip start
						$chkQuery = "select line_id from emp_payslip_line 
						left join emp_payslip_header on
							emp_payslip_header.header_id = emp_payslip_line.header_id
						where  
						emp_payslip_line.user_id='".$data['user_id']."' and 
							emp_payslip_header.period_id='".$data['period_id']."' and 
								emp_payslip_line.element_id='".$data['element_id']."' ";
						$chkPayrollEleRecovery = $this->db->query($chkQuery)->result_array();

						if( count($chkPayrollEleRecovery) > 0 )
						{
							$ctc_reference = $this->input->post('amount');
							$ytd = $payrollAmount = $this->input->post('amount');

							$getPrevQry = "select ytd,amount from emp_payslip_line 
								where
								emp_payslip_line.user_id = '".$data['user_id']."' and 
									emp_payslip_line.period_id != '".$_POST['period_id']."' and 
										emp_payslip_line.element_id = '".$data['element_id']."'
									";
							$getPreviousYTD = $this->db->query($getPrevQry)->result_array();

							$previousYTD = 0;

							foreach($getPreviousYTD as $prevousAmt)
							{
								$previousYTD += $prevousAmt["amount"];
							}

							$totalYTDAmount = $ytd + $previousYTD;

							$lineData = array(
								"ctc_reference" => $ctc_reference,
								"amount"        => $payrollAmount,
								"ytd"           => $totalYTDAmount
							);

							$this->db->where('user_id',$data['user_id']);
							$this->db->where('period_id',$data['period_id']);
							$this->db->where('element_id',$data['element_id']);
							$result = $this->db->update('emp_payslip_line', $lineData);
						}
						#Update recovery elements after creation Payslip end

						$this->session->set_flashdata('flash_message' , "Incentive updated Successfully!");
						redirect(base_url() . 'employee/ManageIncentive', 'refresh');
					}
				}
			break;
			
			/* case "delete": #Delete
				$this->db->where('expenses_id', $id);
				$this->db->delete('users');
				
				$this->session->set_flashdata('flash_message' , "Customer deleted successfully!");
				redirect(base_url() . 'employee/ManageEmployee', 'refresh');
			break;
			
			case "status": #Block & Unblock
				if($status == 1){
					$data['department_status'] = 1;
					$succ_msg = 'Incentive unblocked successfully!';
				}else{
					$data['department_status'] = 0;
					$succ_msg = 'Incentive blocked successfully!';
				}
				$this->db->where('rating_id', $id);
				$this->db->update('emp_ratings', $data);
				$this->session->set_flashdata('flash_message' , $succ_msg);
				redirect(base_url() . 'employee/ManageIncentive', 'refresh');
			break; */
			
			default : #Manage
				$page_data["totalRows"] = $totalRows = $this->employee_model->getManageIncentiveCount();
	
				if(!empty($_SESSION['PAGE']))
				{$limit = $_SESSION['PAGE'];
				}else{$limit = 10;}
				
				if (!empty($_GET['keywords'])) {
					$base_url = base_url('employee/ManageIncentive?keywords='.$_GET['keywords']);
				} else {
					$base_url = base_url('employee/ManageIncentive?keywords=');
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
				
				$page_data['resultData']  = $result = $this->employee_model->getManageIncentive($limit, $offset);
			
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

	# Employee emp_recovery
	function ManageRecovery($type = '', $id = '', $status = '')
    {
		if (empty($this->user_id))
        {
			redirect(base_url() . 'admin/adminLogin', 'refresh');
		}
	
		$page_data['type'] = $type;
		$page_data['id'] = $id;
		
		$page_data['ManagePayroll'] = 1;
		#$page_data['system_settings'] = 1;
		$page_data['page_name']  = 'employee/ManageRecovery';
		$page_data['page_title'] = 'Recovery Adjustment';
		
		switch($type)
		{
			case "add": #View
				if($_POST)
				{
					/* $string_from_date = strtotime($_POST['string_from_date']);
					$string_to_date = strtotime($_POST['string_to_date']);

					if( isset($_POST['string_from_date']) && !empty($_POST['string_from_date']) ){
						$from_date = date("Y-m-d",strtotime($_POST['string_from_date']))	;
					}else{
						$from_date = NULL	;
					}

					if( isset($_POST['string_to_date']) && !empty($_POST['string_to_date']) ){
						$to_date = date("Y-m-d",strtotime($_POST['string_to_date']))	;
					}else{
						$to_date = NULL	;
					}

					$data = array(
						"user_id" 	          =>  $this->input->post('user_id'),
						"element_id" 	  	  =>  $this->input->post('element_id'),
						"element_category_id" =>  $this->input->post('element_category_id'),
						"remarks" 			  =>  $this->input->post('remarks'),
						"financial_year_id"   =>  $this->input->post('financial_year_id'),
						"period_id" 		  =>  $this->input->post('period_id'),
						"amount" 			  =>  $this->input->post('amount'),
						"string_from_date" 	  =>  $string_from_date,
						"string_to_date" 	  =>  $string_to_date,
						"from_date" 	      =>  $from_date,
						"to_date" 	          =>  $to_date,
					);

					$ChkExist = $this->db->query("select recovery_id,from_date,to_date,element_id,string_from_date,string_to_date,period_id from emp_recovery 
						where user_id='".$data['user_id']."'
							and period_id='".$data['period_id']."'
								and element_id='".$data['element_id']."'
								")->result_array();
					
					if( count($ChkExist) > 0)
					{
						$this->session->set_flashdata('error_message' , "Sorry! Recovery Already exist!");
						redirect($_SERVER['HTTP_REFERER'], 'refresh');
					}
					
					$this->db->insert('emp_recovery', $data);
					$id = $this->db->insert_id();
					
					if($id !="")
					{
						#Update recovery elements after creation payroll start
						$chkQuery = "select line_id from emp_payroll_line 
						left join emp_payroll_header on
							emp_payroll_header.header_id = emp_payroll_line.header_id
						where  
						emp_payroll_line.user_id='".$data['user_id']."' and 
							emp_payroll_header.period_id='".$data['period_id']."' and 
								emp_payroll_line.element_id='".$data['element_id']."' ";
						$chkPayrollEleRecovery = $this->db->query($chkQuery)->result_array();

						if( count($chkPayrollEleRecovery) > 0 )
						{
							$ctc_reference = $this->input->post('amount');
							$ytd = $payrollAmount = $this->input->post('amount');

							$getPrevQry = "select ytd,amount from emp_payroll_line 
								where
									emp_payroll_line.user_id = '".$data['user_id']."' and 
											emp_payroll_line.element_id = '".$data['element_id']."'
									";
							$getPreviousYTD = $this->db->query($getPrevQry)->result_array();

							$previousYTD = 0;

							foreach($getPreviousYTD as $prevousAmt)
							{
								$previousYTD += $prevousAmt["amount"];
							}

							$totalYTDAmount = $ytd + $previousYTD;

							$lineData = array(
								"ctc_reference" => $ctc_reference,
								"amount"        => $payrollAmount,
								"ytd"           => $totalYTDAmount
							);

							$this->db->where('user_id',$data['user_id']);
							$this->db->where('period_id',$data['period_id']);
							$this->db->where('element_id',$data['element_id']);
							$result = $this->db->update('emp_payroll_line', $lineData);
						}
						#Update recovery elements after creation payroll end

						$this->session->set_flashdata('flash_message' , "Recovery added Successfully!");
						redirect(base_url() . 'employee/ManageRecovery', 'refresh');
					} */

					if($_POST)
					{
						if($_POST["element_id"] == 21) #Employee PF
						{
							$new_user_id = $_POST["new_user_id"];

							if($new_user_id == 0)
							{
								$condition = 'user_type !=1 and register_type=1 & user_status=1';
								$usersQry = "select users.user_id,emp_salary_structure_header.to_date from users 
								join emp_salary_structure_header on 
									emp_salary_structure_header.user_id = users.user_id
										where emp_salary_structure_header.default_annexure = 1 and ".$condition." 
											group by emp_salary_structure_header.user_id
								";
							}
							else
							{
								$condition = 'user_type !=1 and  register_type=1 & user_status=1 and users.user_id="'.$new_user_id.'" ';

								$usersQry = "select users.random_user_id, users.first_name,users.user_id,emp_salary_structure_header.to_date from users 
								join emp_salary_structure_header on 
									emp_salary_structure_header.user_id = users.user_id
										where emp_salary_structure_header.default_annexure = 1 and
											".$condition." group by emp_salary_structure_header.user_id";
							}

							$getUsers = $this->db->query($usersQry)->result_array();

							if(count($getUsers) > 0)
							{
								foreach($getUsers as $userData)
								{
									$user_id = $userData["user_id"];
									$currentDate = date("Y-m-d",time());
									
									$getEmployer = 'select 
										emp_salary_structure_header.user_id, 
										element_id, 
										per_month_inr 
										from emp_salary_structure_line

									left join emp_salary_structure_header on 
										emp_salary_structure_header.header_id = emp_salary_structure_line.header_id
									where 
										emp_salary_structure_header.default_annexure = 1 and 
											emp_salary_structure_line.element_id = 5 and 
												emp_salary_structure_header.user_id ="'.$user_id.'" and 
													emp_salary_structure_header.to_date >= "'.$currentDate.'"
										';
									$getEmployerAmount = $this->db->query($getEmployer)->result_array();
									//print_r (count($getEmployerAmount));exit;



									$totlEmployerAmount = 0;
									foreach($getEmployerAmount as $Employer)
									{
										$totlEmployerAmount = $Employer['per_month_inr'];
									}
									
									$amount = $employeeAmount = $totlEmployerAmount;

									$string_from_date = strtotime($_POST['string_from_date']);
									$string_to_date = strtotime($_POST['string_to_date']);

									if( isset($_POST['string_from_date']) && !empty($_POST['string_from_date']) ){
										$from_date = date("Y-m-d",strtotime($_POST['string_from_date']))	;
									}else{
										$from_date = NULL	;
									}

									if( isset($_POST['string_to_date']) && !empty($_POST['string_to_date']) ){
										$to_date = date("Y-m-d",strtotime($_POST['string_to_date']))	;
									}else{
										$to_date = NULL	;
									}

									$chkExistQuery = "select recovery_id from emp_recovery 
										where 
											element_category_id='".$_POST["element_category_id"]."' and 
												element_id='".$_POST["element_id"]."' and 
													financial_year_id='".$_POST["financial_year_id"]."' and 
														period_id='".$_POST["period_id"]."' and 
															user_id='".$user_id."' ";

									$chkExist = $this->db->query($chkExistQuery)->result_array();
									
									if( count($chkExist) == 0  && count($getEmployerAmount) > 0)
									{
										$data = array(
											"user_id" 	          =>  $user_id,
											"element_id" 	  	  =>  $this->input->post('element_id'),
											"element_category_id" =>  $this->input->post('element_category_id'),
											"remarks" 			  =>  $this->input->post('remarks'),
											"financial_year_id"   =>  $this->input->post('financial_year_id'),
											"period_id" 		  =>  $this->input->post('period_id'),
											"amount" 			  =>  $amount,
											"string_from_date" 	  =>  $string_from_date,
											"string_to_date" 	  =>  $string_to_date,
											"from_date" 	      =>  $from_date,
											"to_date" 	          =>  $to_date,
										);
										
										$this->db->insert('emp_recovery', $data);
										$id = $this->db->insert_id();
									}
									else
									{
										$data = array(
											"user_id" 	          =>  $user_id,
											"element_id" 	  	  =>  $this->input->post('element_id'),
											"element_category_id" =>  $this->input->post('element_category_id'),
											"remarks" 			  =>  $this->input->post('remarks'),
											"financial_year_id"   =>  $this->input->post('financial_year_id'),
											"period_id" 		  =>  $this->input->post('period_id'),
											"amount" 			  =>  $amount,
											"string_from_date" 	  =>  $string_from_date,
											"string_to_date" 	  =>  $string_to_date,
											"from_date" 	      =>  $from_date,
											"to_date" 	          =>  $to_date,
										);

										$this->db->where('user_id',$user_id);
										$this->db->where('element_id',$_POST["element_id"]);
										$this->db->where('financial_year_id',$_POST['financial_year_id']);
										$this->db->where('period_id',$_POST['period_id']);
										$this->db->where('element_category_id',$_POST['element_category_id']);

										$result = $this->db->update('emp_recovery', $data);

										$id = isset($chkExist[0]["recovery_id"]) ? $chkExist[0]["recovery_id"] : 0;
									}
									
									if($id !="")
									{
										#Update recovery elements after creation payroll start
										$chkQuery = "select line_id from emp_payroll_line 
										left join emp_payroll_header on
											emp_payroll_header.header_id = emp_payroll_line.header_id
										where  
										emp_payroll_line.user_id='".$user_id."' and 
											emp_payroll_header.period_id='".$data['period_id']."' and 
												emp_payroll_line.element_id='".$data['element_id']."' ";
										$chkPayrollEleRecovery = $this->db->query($chkQuery)->result_array();

										if( count($chkPayrollEleRecovery) > 0 )
										{
											$ctc_reference = $employeeAmount;
											$ytd = $payrollAmount = $employeeAmount;

											$getPrevQry = "select ytd,amount from emp_payroll_line 
												where
													emp_payroll_line.user_id = '".$data['user_id']."' and 
															emp_payroll_line.element_id = '".$data['element_id']."'
													";
											$getPreviousYTD = $this->db->query($getPrevQry)->result_array();

											$previousYTD = 0;

											foreach($getPreviousYTD as $prevousAmt)
											{
												$previousYTD += $prevousAmt["amount"];
											}

											$totalYTDAmount = $ytd + $previousYTD;

											$lineData = array(
												"ctc_reference" => $ctc_reference,
												"amount"        => $payrollAmount,
												"ytd"           => $totalYTDAmount
											);

											$this->db->where('user_id',$data['user_id']);
											$this->db->where('period_id',$data['period_id']);
											$this->db->where('element_id',$data['element_id']);
											$result = $this->db->update('emp_payroll_line', $lineData);
										}
										#Update recovery elements after creation payroll end

										/* $this->session->set_flashdata('flash_message' , "Recovery added Successfully!");
										redirect(base_url() . 'employee/ManageRecovery', 'refresh'); */
									}
								}
							}
						}
						else
						{
							if( isset($_POST['amount']) && $_POST['amount'] !="" )
							{
								$count=count(array_filter($_POST['user_id']));
								
								for($dp=0;$dp<$count;$dp++)
								{	
									$string_from_date = strtotime($_POST['string_from_date']);
									$string_to_date = strtotime($_POST['string_to_date']);

									if( isset($_POST['string_from_date']) && !empty($_POST['string_from_date']) ){
										$from_date = date("Y-m-d",strtotime($_POST['string_from_date']))	;
									}else{
										$from_date = NULL	;
									}

									if( isset($_POST['string_to_date']) && !empty($_POST['string_to_date']) ){
										$to_date = date("Y-m-d",strtotime($_POST['string_to_date']))	;
									}else{
										$to_date = NULL	;
									}

									$user_id = isset($_POST['user_id'][$dp]) ? $_POST['user_id'][$dp] :"";
									$amount = isset($_POST['amount'][$dp]) ? $_POST['amount'][$dp] : 0;

									$chkExistQuery = "select recovery_id from emp_recovery 
										where 
											element_category_id='".$_POST["element_category_id"]."' and 
												element_id='".$_POST["element_id"]."' and 
													financial_year_id='".$_POST["financial_year_id"]."' and 
														period_id='".$_POST["period_id"]."' and 
															user_id='".$user_id."' ";

									$chkExist = $this->db->query($chkExistQuery)->result_array();
									
									if( count($chkExist) == 0 )
									{
										$data = array(
											"user_id" 	          =>  $user_id,
											"element_id" 	  	  =>  $this->input->post('element_id'),
											"element_category_id" =>  $this->input->post('element_category_id'),
											"remarks" 			  =>  $this->input->post('remarks'),
											"financial_year_id"   =>  $this->input->post('financial_year_id'),
											"period_id" 		  =>  $this->input->post('period_id'),
											"amount" 			  =>  $amount,
											"string_from_date" 	  =>  $string_from_date,
											"string_to_date" 	  =>  $string_to_date,
											"from_date" 	      =>  $from_date,
											"to_date" 	          =>  $to_date,
										);

										$this->db->insert('emp_recovery', $data);
										$id = $this->db->insert_id();
									}
									else
									{
										$data = array(
											"user_id" 	          =>  $user_id,
											"element_id" 	  	  =>  $this->input->post('element_id'),
											"element_category_id" =>  $this->input->post('element_category_id'),
											"remarks" 			  =>  $this->input->post('remarks'),
											"financial_year_id"   =>  $this->input->post('financial_year_id'),
											"period_id" 		  =>  $this->input->post('period_id'),
											"amount" 			  =>  $amount,
											"string_from_date" 	  =>  $string_from_date,
											"string_to_date" 	  =>  $string_to_date,
											"from_date" 	      =>  $from_date,
											"to_date" 	          =>  $to_date,
										);

										$this->db->where('user_id',$user_id);
										$this->db->where('element_id',$_POST["element_id"]);
										$this->db->where('financial_year_id',$_POST['financial_year_id']);
										$this->db->where('period_id',$_POST['period_id']);
										$this->db->where('element_category_id',$_POST['element_category_id']);

										$result = $this->db->update('emp_recovery', $data);

										$id = isset($chkExist[0]["recovery_id"]) ? $chkExist[0]["recovery_id"] : 0;
									}
									
									if($id !="")
									{
										#Update recovery elements after creation payroll start
										$chkQuery = "select line_id from emp_payroll_line 
										left join emp_payroll_header on
											emp_payroll_header.header_id = emp_payroll_line.header_id
										where  
										emp_payroll_line.user_id='".$user_id."' and 
											emp_payroll_header.period_id='".$data['period_id']."' and 
												emp_payroll_line.element_id='".$data['element_id']."' ";
										$chkPayrollEleRecovery = $this->db->query($chkQuery)->result_array();

										if( count($chkPayrollEleRecovery) > 0 )
										{
											$ctc_reference = $amount;
											$ytd = $payrollAmount = $amount;

											$getPrevQry = "select ytd,amount from emp_payroll_line 
												where
													emp_payroll_line.user_id = '".$user_id."' and 
															emp_payroll_line.element_id = '".$data['element_id']."'
													";
											$getPreviousYTD = $this->db->query($getPrevQry)->result_array();

											$previousYTD = 0;

											foreach($getPreviousYTD as $prevousAmt)
											{
												$previousYTD += $prevousAmt["amount"];
											}

											$totalYTDAmount = $ytd + $previousYTD;

											$lineData = array(
												"ctc_reference" => $ctc_reference,
												"amount"        => $payrollAmount,
												"ytd"           => $totalYTDAmount
											);

											$this->db->where('user_id',$user_id);
											$this->db->where('period_id',$data['period_id']);
											$this->db->where('element_id',$data['element_id']);
											$result = $this->db->update('emp_payroll_line', $lineData);
										}
										#Update recovery elements after creation payroll end
									}
								}
							}
						}

						$this->session->set_flashdata('flash_message' , "Recovery added Successfully!");
						redirect(base_url() . 'employee/ManageRecovery', 'refresh');
					}
				}
			break;
			
			case "edit": #edit
					
				$page_data['edit_data'] = $this->db->query("select * from emp_recovery 
				where recovery_id='".$id."' ")->result_array();
				
				if($_POST)
				{
					$string_from_date = strtotime($_POST['string_from_date']);
					$string_to_date = strtotime($_POST['string_to_date']);

					if( isset($_POST['string_from_date']) && !empty($_POST['string_from_date']) ){
						$from_date = date("Y-m-d",strtotime($_POST['string_from_date']))	;
					}else{
						$from_date = NULL	;
					}

					if( isset($_POST['string_to_date']) && !empty($_POST['string_to_date']) ){
						$to_date = date("Y-m-d",strtotime($_POST['string_to_date']))	;
					}else{
						$to_date = NULL	;
					}

					$data = array(
						#"project_code" 	  =>  $this->input->post('project_code'),
						"user_id" 	          =>  $this->input->post('user_id'),
						"element_id" 	  	  =>  $this->input->post('element_id'),
						"element_category_id" =>  $this->input->post('element_category_id'),
						"remarks" 			  =>  $this->input->post('remarks'),
						"financial_year_id"   =>  $this->input->post('financial_year_id'),
						"period_id" 	      =>  $this->input->post('period_id'),
						"amount" 			  =>  $this->input->post('amount'),
						"string_from_date"    =>  $string_from_date,
						"string_to_date" 	  =>  $string_to_date,
						"from_date" 	      =>  $from_date,
						"to_date" 	          =>  $to_date,
					);
					
					$ChkExist = $this->db->query("select recovery_id,from_date,element_id,to_date,string_from_date,string_to_date,period_id from emp_recovery 
					where period_id='".$data['period_id']."' AND
							user_id='".$data['user_id']."' AND
								element_id='".$data['element_id']."' 
								AND recovery_id !='".$id."' ")->result_array();
					
					if( count($ChkExist) > 0)
					{
						$this->session->set_flashdata('error_message' , "Sorry! Recovery Already exist!");
						redirect($_SERVER['HTTP_REFERER'], 'refresh');
					}
					
					$this->db->where('recovery_id', $id);
					$result = $this->db->update('emp_recovery', $data);
					
					if($result)
					{
						#Update recovery elements after creation payroll start
						$chkQuery = "select line_id from emp_payroll_line 
						left join emp_payroll_header on
							emp_payroll_header.header_id = emp_payroll_line.header_id
						where  
						emp_payroll_line.user_id='".$data['user_id']."' and 
							emp_payroll_header.period_id='".$data['period_id']."' and 
								emp_payroll_line.element_id='".$data['element_id']."' ";
						$chkPayrollEleRecovery = $this->db->query($chkQuery)->result_array();
						
						if( count($chkPayrollEleRecovery) > 0 )
						{
							$ctc_reference = $this->input->post('amount');
							$ytd = $payrollAmount = $this->input->post('amount');

							$getPrevQry = "select ytd,amount,line_id from emp_payroll_line 
								where
									emp_payroll_line.user_id = '".$data['user_id']."' and 
										emp_payroll_line.period_id != '".$_POST['period_id']."' and 
											emp_payroll_line.element_id = '".$data['element_id']."'
									";
							$getPreviousYTD = $this->db->query($getPrevQry)->result_array();

							$previousYTD = 0;

							foreach($getPreviousYTD as $prevousAmt)
							{
								$previousYTD += $prevousAmt["amount"];
							}

							$totalYTDAmount = $ytd + $previousYTD;
							
							$lineData = array(
								"ctc_reference" => $ctc_reference,
								"amount"        => $payrollAmount,
								"ytd"           => $totalYTDAmount
							);

							$this->db->where('user_id',$data['user_id']);
							$this->db->where('period_id',$data['period_id']);
							$this->db->where('element_id',$data['element_id']);
							$result = $this->db->update('emp_payroll_line', $lineData);
						}
						#Update recovery elements after creation payroll end
						$this->session->set_flashdata('flash_message' , "Recovery updated Successfully!");
						redirect(base_url() . 'employee/ManageRecovery', 'refresh');
					}
				}
			break;
			
			/* case "delete": #Delete
				$this->db->where('expenses_id', $id);
				$this->db->delete('users');
				
				$this->session->set_flashdata('flash_message' , "Customer deleted successfully!");
				redirect(base_url() . 'employee/ManageEmployee', 'refresh');
			break; */
			
			case "status": #Block & Unblock
				if($status == 1){
					$data['department_status'] = 1;
					$succ_msg = 'Leave unblocked successfully!';
				}else{
					$data['department_status'] = 0;
					$succ_msg = 'Leave blocked successfully!';
				}
				$this->db->where('recovery_id', $id);
				$this->db->update('emp_recovery', $data);
				$this->session->set_flashdata('flash_message' , $succ_msg);
				redirect(base_url() . 'employee/ManageRecovery', 'refresh');
			break;
			
			default : #Manage
				$page_data["totalRows"] = $totalRows = $this->employee_model->getManageRecoveryCount();
	
				if(!empty($_SESSION['PAGE']))
				{$limit = $_SESSION['PAGE'];
				}else{$limit = 10;}
				
				if (!empty($_GET['keywords'])) {
					$base_url = base_url('employee/ManageRecovery?keywords='.$_GET['keywords']);
				} else {
					$base_url = base_url('employee/ManageRecovery?keywords=');
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
				
				$page_data['resultData']  = $result = $this->employee_model->getManageRecovery($limit, $offset);
			
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

	#Employee Ratings
	function ManageSalary($type = '', $id = '', $status = '')
	{
		if (empty($this->user_id))
		{
			redirect(base_url() . 'admin/adminLogin', 'refresh');
		}

		$page_data['type'] = $type;
		$page_data['id'] = $id;
		
		$page_data['ManagePayroll'] = 1;
		#$page_data['system_settings'] = 1;
		$page_data['page_name']  = 'employee/ManageSalary';
		$page_data['page_title'] = 'Employee Annexure';
		
		switch($type)
		{
			case "add": #Add
				if($_POST)
				{
					$user_id = $this->input->post('user_id');

                    if (
							isset($_POST['string_from_date']) && !empty($_POST['string_from_date']) &&
							isset($_POST['string_to_date']) && !empty($_POST['string_to_date']) 
						) 
					{
						$from_date = date("Y-m-d",strtotime($_POST['string_from_date']));
						$to_date = date("Y-m-d",strtotime($_POST['string_to_date']));

						$condition = "from_date='".$from_date."' and to_date='".$to_date."' and user_id='".$user_id."' ";

						$chkExistQry = "select header_id from emp_salary_structure_header where $condition";
						$chkExist = $this->db->query($chkExistQry)->result_array();
                    }
					else if (isset($_POST['string_from_date']) && !empty($_POST['string_from_date'])) 
					{
						$from_date = date("Y-m-d",strtotime($_POST['string_from_date']));
						$condition = "from_date='".$from_date."' and user_id='".$user_id."'";
						$chkExistQry = "select header_id from emp_salary_structure_header where $condition";
						$chkExist = $this->db->query($chkExistQry)->result_array();
					}
					
					if( count($chkExist) > 0 )
					{
						$this->session->set_flashdata('error_message' , "Employee salary structure already created this Effective date!");
						redirect(base_url() . 'employee/ManageSalary/add', 'refresh');
					}

					if( isset($_POST['string_from_date']) && !empty($_POST['string_from_date']) )
					{
						$from_date = date("Y-m-d",strtotime($_POST['string_from_date']));
						$string_from_date = strtotime($_POST['string_from_date']);
					}
					else
					{
						$from_date = NULL;
						$string_from_date ="";
					}

					if( isset($_POST['string_to_date']) && !empty($_POST['string_to_date']) )
					{
						$to_date = date("Y-m-d",strtotime($_POST['string_to_date']));
						$string_to_date = strtotime($_POST['string_to_date']);
					}
					else
					{
						$to_date = "";
						$string_to_date ="";
					}
					$checkExistAnnexure = $this->db->query("select user_id from emp_salary_structure_header
						where user_id = '".$user_id."' 
						")->result_array();
					if(count($checkExistAnnexure) > 0)
					{
						$default_annexure = 0;
					}
					else
					{
						$default_annexure = 1;
					}

					$from_month = date("m",$string_from_date);
					$from_year = date("Y",$string_from_date);
					
					$to_month = date("m",$string_to_date);
					$to_year = date("Y",$string_to_date);
					
					$headerData = array(
						"user_id" 	          =>  $this->input->post('user_id'),
						"ctc" 	              =>  $this->input->post('ctc'),
						"place" 	          =>  $this->input->post('place'),
						"from_date" 	      =>  $from_date,
						"to_date" 	          =>  $to_date,
						"string_from_date"    =>  $string_from_date,
						"string_to_date"      =>  $string_to_date,
						"created_date"        =>  time(),
						"living_city"  			 =>  $this->input->post('living_city'),
						"emp_per_annum_ctc"   =>  $this->input->post('grand_total'),
						"default_annexure" 	 =>  $default_annexure,

						"from_month" 	 =>  $from_month,
						"from_year" 	 =>  $from_year,

						"to_month" 	 =>  $to_month,
						"to_year" 	 =>  $to_year,

					);
					
					$this->db->insert('emp_salary_structure_header', $headerData);
					$id = $this->db->insert_id();

					

					if($id !="")
					{
						# Add Rating for employee
						if( isset($_POST['element_id']) && $_POST['element_id'] !="" )
						{
							$count=count(array_filter($_POST['element_id']));
							
							for($dp=0;$dp<$count;$dp++)
							{																	
								$LineData= array(
									"header_id"          => $id,
									"element_id"         => isset($_POST['element_id'][$dp]) ? $_POST['element_id'][$dp] :"",
									"element_percentage" => isset($_POST['element_percentage_id'][$dp]) ? $_POST['element_percentage_id'][$dp] :"0",
									"per_month_inr"      => isset($_POST['per_month_inr'][$dp]) ? $_POST['per_month_inr'][$dp] :"",
									"per_annum_inr"      => isset($_POST['per_annum_inr'][$dp]) ? $_POST['per_annum_inr'][$dp] :"",
									"created_date"       => time(),
								);		
								
								# Exist start here
								/* $checkExist = $this->db->query("select line_id from emp_salary_structure_line 
									where 
										period_id = '".$period_id."' and 
											user_id = '".$user_id."' 
									")->result_array();
										
								if( count($checkExist) == 0 )
								{*/

								$this->db->insert('emp_salary_structure_line', $LineData);
								$LineDataID = $this->db->insert_id();

								/* }
								else
								{
									
								}*/
								# Exist end here
							}
						}
						#Add Rating for employee

						/*
						$tableData = $this->input->post('table_data');
						$js_data = json_decode($tableData);
						
						foreach (array_filter($js_data) as $key => $value) 
						{
							if($value==null)
							{
								
							}
							else
							{
								$element_id = $value->element_id;
									
								$lineData = array(
									"header_id"          => $id,
									"element_id"         => $element_id,
									"element_percentage" => $value->element_percentage,
									"per_month_inr"      => $value->per_month_inr,
									"per_annum_inr"      => $value->per_annum_inr,
									"created_date"       => time(),
									
								);
								
								$this->db->insert('emp_salary_structure_line', $lineData);
								$lineItems  = $this->db->insert_id();
							}
						}
						*/
				
						$this->session->set_flashdata('flash_message' , "Employee salary structure created successfully!");
						redirect(base_url() . 'employee/ManageSalary', 'refresh');
					}
				}
			break;
			
			case "edit": #edit	
				$page_data['edit_data'] = $this->db->query("select * from emp_salary_structure_header 
				where header_id='".$id."' ")->result_array();

				$itemQuery = "select 
					emp_salary_structure_line.*, 
					hr_payslip_categories.category_name
					
					from 
					emp_salary_structure_line 
				
					left join hr_payslip_categories on
						hr_payslip_categories.category_id = emp_salary_structure_line.element_id

					where header_id='".$id."' ";
				$page_data['items'] = $this->db->query($itemQuery)->result_array();
				
				if($_POST)
				{
					$user_id = $this->input->post('user_id');
					
					if (
						isset($_POST['string_from_date']) && !empty($_POST['string_from_date']) &&
						isset($_POST['string_to_date']) && !empty($_POST['string_to_date']) 
					) 
					{
						$from_date = date("Y-m-d",strtotime($_POST['string_from_date']));
						$to_date = date("Y-m-d",strtotime($_POST['string_to_date']));

						$condition = "from_date='".$from_date."' and to_date='".$to_date."' and user_id='".$user_id."' and header_id !='".$id."' ";

						$chkExistQry = "select header_id from emp_salary_structure_header where $condition";
						$chkExist = $this->db->query($chkExistQry)->result_array();
					}
					else if (isset($_POST['string_from_date']) && !empty($_POST['string_from_date'])) 
					{
						$from_date = date("Y-m-d",strtotime($_POST['string_from_date']));
						$condition = "from_date='".$from_date."' and user_id='".$user_id."' and header_id !='".$id."' ";
						$chkExistQry = "select header_id from emp_salary_structure_header where $condition";
						$chkExist = $this->db->query($chkExistQry)->result_array();
					}
					
					if( count($chkExist) > 0 )
					{
						$this->session->set_flashdata('error_message' , "Employee salary structure already created this Effective date!");
						redirect(base_url() . 'employee/ManageSalary/edit/'.$id, 'refresh');
					}

					if( isset($_POST['string_from_date']) && !empty($_POST['string_from_date']) )
					{
						$from_date = date("Y-m-d",strtotime($_POST['string_from_date']));
						$string_from_date = strtotime($_POST['string_from_date']);
					}
					else
					{
						$from_date = NULL;
						$string_from_date ="";
					}

					if( isset($_POST['string_to_date']) && !empty($_POST['string_to_date']) )
					{
						$to_date = date("Y-m-d",strtotime($_POST['string_to_date']));
						$string_to_date = strtotime($_POST['string_to_date']);
					}
					else
					{
						$to_date = "";
						$string_to_date ="";
					}

					
					$from_month = date("m",$string_from_date);
					$from_year = date("Y",$string_from_date);
					
					$to_month = date("m",$string_to_date);
					$to_year = date("Y",$string_to_date);

					$headerData = array(
						"user_id" 	           =>  $this->input->post('user_id'),
						"ctc" 	               =>  $this->input->post('ctc'),
						"place" 	           =>  $this->input->post('place'),
						"from_date" 	       =>  $from_date,
						"to_date" 	           =>  $to_date,
						"string_from_date"     =>  $string_from_date,
						"string_to_date"       =>  $string_to_date,
						"living_city"          =>  $this->input->post('living_city'),
						"emp_per_annum_ctc"    =>  $this->input->post('grand_total'),

						"from_month" 	 =>  $from_month,
						"from_year" 	 =>  $from_year,

						"to_month" 	 =>  $to_month,
						"to_year" 	 =>  $to_year,
						
					);
					
					$this->db->where('header_id', $id);
					$result = $this->db->update('emp_salary_structure_header', $headerData);
					
					if($result)
					{
						# Add Rating for employee
						if( isset($_POST['element_id']) && $_POST['element_id'] !="" )
						{
							$count=count(array_filter($_POST['element_id']));
							
							for($dp=0;$dp<$count;$dp++)
							{		
								
								$element_id = isset($_POST['element_id'][$dp]) ? $_POST['element_id'][$dp] :"";

								$LineData= array(
									"header_id"          => $id,
									"element_id"         => isset($_POST['element_id'][$dp]) ? $_POST['element_id'][$dp] :"",
									"element_percentage" => isset($_POST['element_percentage_id'][$dp]) ? $_POST['element_percentage_id'][$dp] :"0",
									"per_month_inr"      => isset($_POST['per_month_inr'][$dp]) ? $_POST['per_month_inr'][$dp] :"",
									"per_annum_inr"      => isset($_POST['per_annum_inr'][$dp]) ? $_POST['per_annum_inr'][$dp] :"",
								);		
								
								# Exist start here
								$sql = "select line_id from emp_salary_structure_line 
									where 
										header_id = ? and 
											element_id = ?";
											
								$result = $this->db->query($sql,array($id,$element_id));
								
								if( $result->num_rows() > 0 )
								{
									$where = "header_id = $id AND element_id = $element_id";
									$this->db->where($where);
									$this->db->update('emp_salary_structure_line',$LineData);
								}
								else
								{
									$lineData["created_date"] = time();
									$this->db->insert('emp_salary_structure_line', $LineData);
									$lineItems  = $this->db->insert_id();
								}
								# Exist end here
							}
						}
						#Add Rating for employee

						/*
						$js_data = json_decode($this->input->post('table_data1'));			
						$php_data = json_decode($this->input->post('table_data'));
						
						if(!empty($js_data))
						{
							foreach (array_filter($js_data) as $key => $value) 
							{
								if( $value == 'delete' )
								{
									$product_id =  $php_data[$key];

									if($this->employee_model->deleteSalaryElementItems($id,$product_id))
									{
										
									}
								}
								else if($value==null)
								{
									
								}
								else
								{
									$element_id = $value->element_id;
									
									$lineData = array(
										"header_id"          => $id,
										"element_id"         => $element_id,
										"element_percentage" => $value->element_percentage,
										"per_month_inr"      => $value->per_month_inr,
										"per_annum_inr"      => $value->per_annum_inr,
									);
									
									$sql = "select line_id from emp_salary_structure_line 
										where 
											header_id = ? and 
												element_id = ?";
												
									$result = $this->db->query($sql,array($id,$element_id));
									
									if( $result->num_rows() > 0 )
									{
										$where = "header_id = $id AND element_id = $element_id";
										$this->db->where($where);
										$this->db->update('emp_salary_structure_line',$lineData);
									}
									else
									{
										$lineData["created_date"] = time();
										$this->db->insert('emp_salary_structure_line', $lineData);
										$lineItems  = $this->db->insert_id();
									}
								}
							}
						}
						*/
						$this->session->set_flashdata('flash_message' , "Employee salary structure updated successfully!");
						redirect(base_url() . 'employee/ManageSalary/edit/'.$id, 'refresh');
					}
				}
			break;
			
			/* case "delete": #Delete
				$this->db->where('expenses_id', $id);
				$this->db->delete('users');
				
				$this->session->set_flashdata('flash_message' , "Customer deleted successfully!");
				redirect(base_url() . 'employee/ManageEmployee', 'refresh');
			break; */
			
			case "status": #Block & Unblock
				if($status == 1){
					$data['department_status'] = 1;
					$succ_msg = 'Department unblocked successfully!';
				}else{
					$data['department_status'] = 0;
					$succ_msg = 'Department blocked successfully!';
				}
				$this->db->where('rating_id', $id);
				$this->db->update('emp_ratings', $data);
				$this->session->set_flashdata('flash_message' , $succ_msg);
				redirect(base_url() . 'employee/ManageRating', 'refresh');
			break;
			
			default : #Manage
				$page_data["totalRows"] = $totalRows = $this->employee_model->getManageSalaryCount();

				if(!empty($_SESSION['PAGE']))
				{$limit = $_SESSION['PAGE'];
				}else{$limit = 10;}
				
				if (!empty($_GET['keywords'])) {
					$base_url = base_url('employee/ManageSalary?keywords='.$_GET['keywords']);
				} else {
					$base_url = base_url('employee/ManageSalary?keywords=');
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
				
				$page_data['resultData']  = $result = $this->employee_model->getManageSalary($limit, $offset);
			
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
	
	#Manage Blood Group Starts
	function ManageBloodGroup($type = '', $id = '', $status = '')
    {
		if (empty($this->user_id))
        {
			redirect(base_url() . 'admin/adminLogin', 'refresh');
		}
	
		$page_data['type'] = $type;
		$page_data['id'] = $id;
		
		$page_data['MasterData'] = 1;
		$page_data['page_name']  = 'employee/ManageBloodGroup';
		$page_data['page_title'] = 'Blood Groups';
		
		switch($type)
		{
			case "add": #View
				if($_POST)
				{
					$data['blood_group_name'] = $this->input->post('blood_group_name');
					$data['created_by'] = $this->user_id;
					$data['created_date'] = $this->date_time;
                    $data['last_updated_by'] = $this->user_id;
                    $data['last_updated_date'] = $this->date_time;


					$this->db->insert('emp_blood_group', $data);
					$id = $this->db->insert_id();
					
					if($id !="")
					{
						$this->session->set_flashdata('flash_message' , "Blood Group added Successfully!");
						redirect(base_url() . 'employee/ManageBloodGroup', 'refresh');
					}
				}
			break;
			
			case "edit": #edit
				
				$page_data['edit_data'] = $this->db->query("select * from emp_blood_group where blood_group_id ='".$id."' ")
								->result_array();
				if($_POST)
				{
					$data['blood_group_name'] = $this->input->post('blood_group_name');
					
                    $data['last_updated_by'] = $this->user_id;
                    $data['last_updated_date'] = $this->date_time;
					$this->db->where('blood_group_id', $id);
					$result = $this->db->update('emp_blood_group', $data);
					
					if($result)
					{
						$this->session->set_flashdata('flash_message' , "Blood Group updated Successfully!");
						redirect(base_url() . 'employee/ManageBloodGroup', 'refresh');
					}
				}
			break;
			
			case "delete": #Delete
				$this->db->where('blood_group_id', $id);
				$this->db->delete('emp_blood_group');
				
				$this->session->set_flashdata('flash_message' , "Blood Group deleted successfully!");
				redirect(base_url() . 'employee/ManageBloodGroup', 'refresh');
			break;
			
			case "status": #Block & Unblock
				/*if($status == 1){
					$data['blood_group_status'] = 1;
					$succ_msg = 'Blood Group active successfully!';
				}else{
					$data['blood_group_status'] = 0;
					$succ_msg = 'Blood Group inactive successfully!';
				}*/
				if($status == 'Y'){
					$data['active_flag'] = 'Y';
					$data['last_updated_by'] = $this->user_id;
                    $data['last_updated_date'] = $this->date_time;
					$data['inactive_date'] = NULL;
					$succ_msg = 'Blood Group active successfully!';
				}else{
					$data['active_flag'] = 'N';
					$data['last_updated_by'] = $this->user_id;
                    $data['last_updated_date'] = $this->date_time;
					$data['inactive_date'] = $this->date_time;
					$succ_msg = 'Blood Group inctive successfully!';
				}
				$this->db->where('blood_group_id', $id);
				$this->db->update('emp_blood_group', $data);
				$this->session->set_flashdata('flash_message' , $succ_msg);
				redirect($_SERVER["HTTP_REFERER"], 'refresh');
			break;
			
			default : #Manage
				#$page_data["totalRows"] = $totalRows = $this->employee_model->getManageBloodGroupCount();#
				$totalResult = $this->employee_model->getManageBloodGroup("","",$this->totalCount);
				$page_data["totalRows"] = $totalRows = count($totalResult);
	
				if(!empty($_SESSION['PAGE']))
				{$limit = $_SESSION['PAGE'];
				}else{$limit = 10;}
				
				/*$redirectURL = 'employee/ManageBloodGroup/'.$type;
				if (!empty($_GET['keywords'])) {
					$base_url = base_url('employee/ManageBloodGroup?keywords='.$_GET['keywords']);
				} else {
					$base_url = base_url('employee/ManageBloodGroup?keywords=');
				}*/
				$active_flag = isset($_GET['active_flag']) ? $_GET['active_flag'] :NULL;
				$redirectURL = 'employee/ManageBloodGroup?keywords=&active_flag='.$active_flag;

				if (!empty($_GET['keywords']) || !empty($_GET['active_flag'])) {
					$base_url = base_url('employee/ManageBloodGroup?keywords='.$_GET['keywords'].'&active_flag='.$_GET['active_flag']);
				} else {
					$base_url = base_url('employee/ManageBloodGroup?keywords=&active_flag=');
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
				
				$page_data['resultData']  = $result = $this->employee_model->getManageBloodGroup($limit, $offset);
			
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
	
	public function viewSalary($id="")
	{
		if (empty($this->user_id))
        {
			redirect(base_url() . 'admin/adminLogin', 'refresh');
		}
		$page_data['ManagePayroll'] = 1;
		$page_data['id'] = $id;
		
		$page_data['page_name']  = 'employee/viewSalary';
		$page_data['page_title'] = 'View Salary Structure';

		$this->load->view($this->adminTemplate, $page_data);
	}
	
	function employee_nameAjaxSearch()
    {
		if(isset($_POST["query"]))  
		{  
			$output = '';  
			
			$condition = 'user_type !=1  and user_status = 1 and 
							register_type = 4 and 
							
							(
								users.first_name like "%'.($_POST["query"]).'%" or 
								users.random_user_id like "%'.($_POST["query"]).'%" or
								users.email like "%'.($_POST["query"]).'%" or
								users.mobile_number like "%'.($_POST["query"]).'%"
							)
							';
			$query = "select 
						users.random_user_id,
						users.first_name,
						users.user_id

						from users 
					where ".$condition." ";
			
			$result = $this->db->query($query)->result_array();
			
			$output = '<ul class="list-unstyled">';  
			$output .= '<li onclick="getuserId(0);">All</li>'; 
			
			if( count($result) > 0 )  
			{  
				foreach($result as $row)  
				{	
					$patinetID=  $row["user_id"];
					$output .= '<li onclick="getuserId('.$patinetID.');">'.$row["random_user_id"].''.'</li>';  
				}  
			}  
			else  
			{  
				$output .= '<li onclick="getuserId(0);">Sorry! Employee Not Found.</li>';  
			}  
			$output .= '</ul>';  
			echo $output;  
		} 
	}
	
	public function employeeList($user_id="",$financial_year_id='',$period_id='')
	{
		if($user_id == 0)
		{
			$financialYear = "select 
				financial_from_month,
				financial_to_month,
				financial_from_year,
				financial_to_year 
						from org_financial_years where financial_status=1 and financial_year_id='".$financial_year_id."' ";
			$getfinancialYear = $this->db->query($financialYear)->result_array();

			$Period = "select month,year from emp_periods where 
							period_status=1 and 
								financial_year_id = '".$financial_year_id."' and
									period_id = '".$period_id."' 
						";
			$getPeriod = $this->db->query($Period)->result_array();

			#Financial Year & Month
			$financial_from_month = isset($getfinancialYear[0]["financial_from_month"]) ? $getfinancialYear[0]["financial_from_month"] :"";
			$financial_to_month = isset($getfinancialYear[0]["financial_to_month"]) ? $getfinancialYear[0]["financial_to_month"] :"";
			
			$financial_from_year = isset($getfinancialYear[0]["financial_from_year"]) ? $getfinancialYear[0]["financial_from_year"] :"";
			$financial_to_year = isset($getfinancialYear[0]["financial_to_year"]) ? $getfinancialYear[0]["financial_to_year"] :"";

			#Period
			$period_month = isset($getPeriod[0]["month"]) ? $getPeriod[0]["month"] :"";
			$period_year = isset($getPeriod[0]["year"]) ? $getPeriod[0]["year"] :"";

			/* $condition = '
						emp_salary_structure_header.default_annexure =1 and 
						users.user_status=1 and 
						users.user_type !=1 and 
						users.register_type=4 and 

						(users.date_of_joining_year BETWEEN "'.$financial_from_year.'" AND "'.$financial_to_year.'") and 
						
						(emp_salary_structure_header.from_year >= "'.$financial_from_year.'" and emp_salary_structure_header.to_year <= "'.$financial_to_year.'")
						

						 '; */

			 $condition = '
						emp_salary_structure_header.default_annexure =1 and 
						users.user_status=1 and 
						users.user_type !=1 and 
						users.register_type=4 and 


						 ';

			$getAllEmployeeQry = "select 
					users.user_id,
					users.first_name,
					users.random_user_id
					
					from users

				join emp_salary_structure_header on
					emp_salary_structure_header.user_id = users.user_id
				 
					where $condition";

				//echo $getAllEmployeeQry;
		}
		else
		{
			$getAllEmployeeQry = "select 
					
					users.user_id,
					users.first_name,
					users.random_user_id
					
					from users 

				join emp_salary_structure_header on
					emp_salary_structure_header.user_id = users.user_id

				where 
					emp_salary_structure_header.default_annexure =1 and 
						users.user_type !=1 and 
							user_status=1 and 
								users.register_type=4 and 
									users.user_id='".$user_id."'";
		}
		
		$data['empData'] = $this->db->query($getAllEmployeeQry)->result();
		
		echo json_encode($data);
		exit;
	}
	
	public function getElements($category_id="")
	{
		$data = $this->db->select('
					p.category_id,
					p.category_name,
					p.category_description,
					p.category_status,
					p.order_number')
				->from('hr_payslip_categories p')
				->where('p.category_id',$category_id)
				->get()
				->result();

		// $currentDate = date("Y-m-d",time()); #start >= '2013-07-22' AND end <= '2013-06-13'
		
		// $condition = ' (org_projects.project_status=1 or org_projects.project_status=8 ) and ';
		// $condition .= " (org_projects.actual_valid_from <= '".$currentDate."' and
		// 					org_projects.actual_valid_to >= '".$currentDate."') and";
		
		// $condition .= " (org_projects_task_items.valid_from_date <= '".$currentDate."' and
		// 			org_projects_task_items.valid_to_date >= '".$currentDate."') ";

		// $projectQuery = "select org_projects.project_id,org_projects.project_code from org_projects 

		// join org_projects_task_items on
		// 	org_projects.project_id = org_projects_task_items.project_id

		// 	where $condition
		// 	group by org_projects_task_items.project_id";

		// $data['project'] = $this->db->query($projectQuery)->result();


		//$data['items'] = $this->db->query('select product_id,product_code from products where product_status=1')->result();
		$data['category'] = $this->db->query('select category_id,category_name from hr_payslip_categories 
			where category_id !=1 and 
					category_id != 2 and  
						category_status=1')->result();
		//$data['discount'] = $this->db->get_where('discount',array('discount_status'=>1))->result();
		//$data['tax'] = $this->db->get_where('tax',array('tax_status'=>1))->result();
		
		echo json_encode($data);
	}

	function EmployeeAjaxSearch()
    {
		if(isset($_POST["query"]))  
		{  
			$financial_year_id = isset($_POST["financial_year_id"]) ? $_POST["financial_year_id"] : 0;
			$period_id = isset($_POST["period_id"]) ? $_POST["period_id"] : 0;

			$financialYear = "select 
				financial_from_month,
				financial_to_month,
				financial_from_year,
				financial_to_year 
						from org_financial_years where financial_status=1 and financial_year_id='".$financial_year_id."' ";
			$getfinancialYear = $this->db->query($financialYear)->result_array();

			$Period = "select month,year from emp_periods where 
							period_status=1 and 
								financial_year_id = '".$financial_year_id."' and
									period_id = '".$period_id."' 
						";
			$getPeriod = $this->db->query($Period)->result_array();


			#Financial Year & Month
			$financial_from_month = isset($getfinancialYear[0]["financial_from_month"]) ? $getfinancialYear[0]["financial_from_month"] :"";
			$financial_to_month = isset($getfinancialYear[0]["financial_to_month"]) ? $getfinancialYear[0]["financial_to_month"] :"";
			
			$financial_from_year = isset($getfinancialYear[0]["financial_from_year"]) ? $getfinancialYear[0]["financial_from_year"] :"";
			$financial_to_year = isset($getfinancialYear[0]["financial_to_year"]) ? $getfinancialYear[0]["financial_to_year"] :"";

			#Period
			$period_month = isset($getPeriod[0]["month"]) ? $getPeriod[0]["month"] :"";
			$period_year = isset($getPeriod[0]["year"]) ? $getPeriod[0]["year"] :"";

			$output = '';  
			/* $condition = '
							emp_salary_structure_header.default_annexure =1 and 
							users.user_type !=1 and 
							users.register_type=4 and 

							(users.date_of_joining_year BETWEEN "'.$financial_from_year.'" AND "'.$financial_to_year.'") and 
							
							(emp_salary_structure_header.from_year >= "'.$financial_from_year.'" and emp_salary_structure_header.to_year <= "'.$financial_to_year.'") and
							emp_salary_structure_header.to_month >= '.$period_month.' and 

							users.date_of_joining_month <= '.$period_month.' and 
							users.date_of_joining_year <= '.$period_year.' 
							and 
							(
								users.first_name like "%'.($_POST["query"]).'%" or 
								users.random_user_id like "%'.($_POST["query"]).'%" or
								users.mobile_number like "%'.($_POST["query"]).'%" or
								users.phone_number like "%'.($_POST["query"]).'%"
							)
							'; */
							$condition = '
							emp_salary_structure_header.default_annexure =1 and 
							users.user_type !=1 and 
							users.register_type=4 and 

							(
								users.first_name like "%'.($_POST["query"]).'%" or 
								users.random_user_id like "%'.($_POST["query"]).'%" or
								users.mobile_number like "%'.($_POST["query"]).'%" or
								users.phone_number like "%'.($_POST["query"]).'%"
							)
							';
			$query = "select 
						users.random_user_id,
						users.first_name,
						users.user_id,
						users.phone_number,
						users.email

						from users
						
					join emp_salary_structure_header on 
						emp_salary_structure_header.user_id = users.user_id

					where ".$condition." ";
			
		
			
			$result = $this->db->query($query)->result_array();
			
			/* $output = '<ul class="list-unstyled-new">';  
			$output .= '<li onclick="getappointmentuserId(0);">All</li>'; 
			if( count($result) > 0 )  
			{  
				foreach($result as $row)  
				{	
					$employeeID = $row["user_id"];
					$first_name = $row["first_name"];
					
					$output .= '<li onclick="return getemployeeuserId(\'' .$employeeID. '\',\'' .$first_name. '\');">'.$row["random_user_id"].''.'</li>';  
				}  
			}  
			else  
			{  
				$employeeID =0;
				$first_name ='';
				$output .= '<li onclick="return getappointmentuserId(\'' .$employeeID. '\',\'' .$first_name. '\');">'.$_POST["query"].'</li>';  
			}
			$output .= '</ul>';  
			echo $output;   */

			$output = '<ul class="list-unstyled-new">';  
			$output .= '<li onclick="getuserId(0);">All</li>'; 
			
			if( count($result) > 0 )  
			{  
				foreach($result as $row)  
				{	
					$patinetID=  $row["user_id"];
					$output .= '<li onclick="getuserId('.$patinetID.');">'.$row["random_user_id"].''.'</li>';  
				}  
			}  
			else  
			{  
				$output .= '<li onclick="getuserId(0);">Sorry! Employee Not Found.</li>';  
			}  
			$output .= '</ul>';  
			echo $output;
		}
	}

	function EmployeeNewAjaxSearch()
    {
		if(isset($_POST["query"]))  
		{  
			$output = '';  
			
			$condition = 'user_type !=1 and register_type = 4 
							and 
							(
								users.first_name like "%'.($_POST["query"]).'%" or 
								users.random_user_id like "%'.($_POST["query"]).'%"
							)
							';
			$query = "select 
						random_user_id,
						first_name,
						user_id,
						phone_number,
						email

						from users 
					where ".$condition." ";
			
			$result = $this->db->query($query)->result_array();
			
			$output = '<ul class="list-unstyled-new">';  
			
			if( count($result) > 0 )  
			{  
				foreach($result as $row)  
				{	
					$employeeID = $row["user_id"];
					$first_name = $row["first_name"];
					
					$output .= '<li onclick="return getemployeeuserId(\'' .$employeeID. '\',\'' .$first_name. '\');">'.$row["random_user_id"].''.'</li>';  
				}  
			}  
			else  
			{  
				$employeeID =0;
				$first_name ='';
				$output .= '<li onclick="return getappointmentuserId(\'' .$employeeID. '\',\'' .$first_name. '\');">'.$_POST["query"].'</li>';  
			}
			$output .= '</ul>';  
			echo $output;  
		}
	}

	function EmployeeAjaxnameSearch()
    {
		if(isset($_POST["query"]))  
		{  
			$output = '';  
			
			$condition = 'user_type !=1 and register_type=1 
							and 
							(
								users.first_name like "%'.($_POST["query"]).'%" or 
								users.random_user_id like "%'.($_POST["query"]).'%" or
								users.email like "%'.($_POST["query"]).'%" or
								users.mobile_number like "%'.($_POST["query"]).'%"
							)
							';
			$query = "select 
						random_user_id,
						first_name,
						user_id,
						phone_number,
						email

						from users 
					where ".$condition." ";
			
			$result = $this->db->query($query)->result_array();
			
			$output  = '<ul class="list-unstyled-new">';
			$output .= '<li onclick="getemployeeuserId(0);">All</li>';  
			
			if( count($result) > 0 )  
			{  
				foreach($result as $row)  
				{	
					$employeeID = $row["user_id"];
					$first_name = $row["first_name"];
					
					$output .= '<li onclick="return getemployeeuserId(\'' .$employeeID. '\',\'' .$first_name. '\');">'.$row["random_user_id"].''.'</li>';  
				}  
			}  
			else  
			{  
				$employeeID =0;
				$first_name ='';
				$output .= '<li onclick="return getemployeeuserId(\'' .$employeeID. '\',\'' .$first_name. '\');">'.$_POST["query"].'</li>';  
			}
			$output .= '</ul>';  
			echo $output;  
		}
	}

	# Ajax  Change
	public function ajaxSelectEmployeePecenatge() 
	{
        $id = $_POST["id"];	

		if($id)
		{			
			$data =  $this->db->query("select org_elements_percentage.* from org_elements_percentage
					where 
						org_elements_percentage.element_id='".$id."' and 
							org_elements_percentage.percentage_status= 1 
								order by org_elements_percentage.element_percentage asc
					")->result_array();
			if( count($data) > 0)
			{
				echo '<option value="">- Select -</option>';
				foreach($data as $val)
				{
					echo '<option value="'.$val['element_percentage'].'">'.ucfirst($val['element_percentage']).'</option>';
				}
			}
			else
			{
				echo '<option value="">No percentage under this element!</option>';
			}
		}
		die;
    }

	# Ajax  Change
	public function ajaxSelectLivingCityPercentage() 
	{
        $id = $_POST["id"];	

		if($id)
		{			
			$data =  $this->db->query("select org_elements_percentage.* from org_elements_percentage
					where 
						org_elements_percentage.element_id= 2 and 
						org_elements_percentage.living_city_id='".$id."' and 
							org_elements_percentage.percentage_status= 1 
								order by org_elements_percentage.element_percentage asc
					")->result_array();
			if( count($data) > 0)
			{
				#echo '<option value="">- Select -</option>';
				$percentage = '';
				foreach($data as $val)
				{
					$percentage .= $val['element_percentage'];
					$data =  '<option value="'.$val['element_percentage'].'">'.$val['element_percentage'].'</option>';
				}
			}
			else
			{
				$percentage = "0";
				$data = '<option value="">No percentage under this element!</option>';
			}

			echo $data."@".$percentage;
		}
		die;
    }

	public function ajaxDeleteSalaryAnnexure() 
	{
        $header_id = $_POST["id"];	
        $line_id = $_POST["lineID"];	
		
		$this->db->where('line_id', $line_id);
		$this->db->where('header_id', $header_id);
		$this->db->delete('emp_salary_structure_line');

		echo 1;exit;
	}
	
	
	public function EmployeeCodeExist()
	{
		if ( isset($_POST['random_user_id']) && $_POST['random_user_id'] ) 
		{
			$random_user_id = $_POST['random_user_id'];
			if (isset($_POST['id'])) 
			{
				$results = $this->db->query("select user_id from users 
					WHERE 
						random_user_id='".$random_user_id."' and 
							user_id != '".$_POST['id']."'
						")->result_array();
			}
			else 
			{
				$results = $this->db->query("select user_id from users 
					WHERE 
						random_user_id='".$random_user_id."' 
								")->result_array();
			}
			
			if ( count($results) > 0 ) 
			{
				echo "taken";	
			}
			else
			{
				echo 'not_taken';
			}
			exit();
		}
	}
	
	#Employee_Mailexist
	public function AnnexureExist()
	{
		if ( isset($_POST['user_id']) && $_POST['user_id']) 
		{
			$user_id = $_POST['user_id'];

			if ( isset($_POST['id']) && $_POST['id'] !=0 ) 
			{ #edit
				$results = $this->db->query("select user_id, from emp_salary_structure_header WHERE emp_salary_structure_header.default_annexure = 1 and user_id='".$user_id."' and incentive_id != '".$_POST['id']."'")->result_array(); #and register_type='".$register_type."'
			}
			else 
			{ #add
				$results = $this->db->query("select user_id from emp_salary_structure_header WHERE emp_salary_structure_header.default_annexure = 1 and user_id='".$user_id."' ")->result_array(); #and register_type='".$register_type."'
			}

			if ( count($results) > 0 ) {
				echo "taken";	
			}else{
				echo 'not_taken';
			}
			exit();
		}
	} 

	public function getProjectCost()
	{
		if ( isset($_POST["id"]) && $_POST["id"] == 21 ) 
		{
			$id = $_POST["id"];	#Element ID	
			$user_id = $_POST['user_id'];

			if($id)
			{	
				$getEmployer = 'select 
					emp_salary_structure_header.user_id, 
					element_id, 
					per_month_inr 
					from emp_salary_structure_line

				left join emp_salary_structure_header on 
					emp_salary_structure_header.header_id = emp_salary_structure_line.header_id
				 where 
					emp_salary_structure_header.default_annexure = 1 and 
					 	emp_salary_structure_line.element_id = 5 and 
							 emp_salary_structure_header.user_id ="'.$user_id.'"
				 ';
				$getEmployerAmount = $this->db->query($getEmployer)->result_array();
				
				$totlEmployerAmount = 0;
				foreach($getEmployerAmount as $Employer)
				{
					$totlEmployerAmount = $Employer['per_month_inr'];
				}
				
				$employeeAmount = $totlEmployerAmount;
				echo $employeeAmount;
			}
			die;
		}
	}

	public function getAjaxAnnexureDate()
	{
		if ( isset($_POST["user_id"]) && $_POST["user_id"] > 0 ) 
		{
			$user_id = $_POST['user_id'];

			$annexureQry = "select string_from_date,string_to_date,from_date,to_date from emp_salary_structure_header
				where user_id='".$user_id."' order by header_id desc limit 0,1";
			$getEmpAnnexure = $this->db->query($annexureQry)->result_array();

			if( count($getEmpAnnexure) > 0 )
			{	
				if( isset($getEmpAnnexure[0]["string_from_date"]) && !empty($getEmpAnnexure[0]["string_from_date"]) )
				{
					$string_from_date =  date("d-M-Y",$getEmpAnnexure[0]["string_from_date"]);
				}
				else
				{
					$string_from_date = "";
				}

				if( isset($getEmpAnnexure[0]["string_to_date"]) && !empty($getEmpAnnexure[0]["string_to_date"]) )
				{
					$string_to_date =  date("d-M-Y",$getEmpAnnexure[0]["string_to_date"]);
				}
				else
				{
					$string_to_date = date("d-M-Y",time());
				}
				
				$type = 1;
			}
			else
			{
				$string_from_date = date("d-M-Y",time());
				$string_to_date = "";	
				$type = 2;
			}
			echo $string_from_date."@".$string_to_date."@".$type;;
		}
	}

	public function annexureDetails($id="")
	{
		if (empty($this->user_id))
        {
			redirect(base_url() . 'admin/adminLogin', 'refresh');
		}
		$page_data['ManagePayroll'] = 1;
		$page_data['id'] = $id;
		
		$page_data['page_name']  = 'employee/annexureDetails';
		$page_data['page_title'] = 'Annexure Details';

		if(isset($_POST['default_submit']))
		{
			$data['default_annexure'] = 0;
			$this->db->where('user_id', $id);
			$result = $this->db->update('emp_salary_structure_header', $data);
			
			if($result)
			{
				$default_annexure = $_POST['default_annexure'];

				$data_1['default_annexure'] = 1;
				$this->db->where('header_id', $default_annexure);
				
				$result1 = $this->db->update('emp_salary_structure_header', $data_1);
			}

			$this->session->set_flashdata('flash_message' , 'Default annexure updated successfully.');
			redirect(base_url() . 'employee/annexureDetails/'.$id, 'refresh');
		}

		$query = "
			select 
			users.*,
			org_roles.role_name,
			country.country_code,
			country.country_name,
			state.state_name,
			city.city_name,
			branch.branch_name,
			emp_blood_group.blood_group_name,
			emp_designations.designation_name,
			hr_positions.position_name,
			emp_departments.department_name
			
			from users
			
			left join country on
				country.country_id = users.country_id
			
			left join state on
				state.state_id = users.state_id
			
			left join city on
				city.city_id = users.city_id
			
			left join org_roles on org_roles.role_id = users.role_id
			left join branch on branch.branch_id = users.branch_id
			left join emp_blood_group on emp_blood_group.blood_group_id = users.blood_group_id
			left join emp_designations on emp_designations.designation_id = users.designation_id
			left join hr_positions on hr_positions.position_id = users.position_id
			left join emp_departments on emp_departments.department_id = users.department_id
			where users.user_id='".$id."' ";
		$page_data['edit_data'] = $this->db->query($query)->result_array();

		$page_data["totalRows"] = $totalRows = $this->employee_model->getEmployeeAnnexureCount($id);

		if(!empty($_SESSION['PAGE']))
		{$limit = $_SESSION['PAGE'];
		}else{$limit = 10;}
		
		if (!empty($_GET['keywords'])) {
			$base_url = base_url('employee/ManageSalary?keywords='.$_GET['keywords']);
		} else {
			$base_url = base_url('employee/ManageSalary?keywords=');
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
		
		$page_data['resultData']  = $result = $this->employee_model->getEmployeeAnnexure($limit, $offset,$id);
	
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

		$this->load->view($this->adminTemplate, $page_data);
	}
	
	
	
}
?>
