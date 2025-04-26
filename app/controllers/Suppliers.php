<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Suppliers extends CI_Controller 
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
	
	function manageSuppliers($type = '', $id = '', $status = '')
    {
		$page_data['type'] = $type;
		$page_data['id'] = $id;
		
		$page_data['ManageSuppliers'] = 1;
		$page_data['page_name']  = 'suppliers/manageSuppliers';
		$page_data['page_title'] = 'Supplier';
		
		switch(true)
		{
			case ($type == "add"): #add
				if($_POST)
				{

					$data['supplier_name'] = trim($this->input->post('supplier_name'));
					$data['contact_person'] = trim($this->input->post('contact_person'));
					$data['mobile_number'] = trim($this->input->post('mobile_number'));
					$data['email_address'] = trim($this->input->post('email'));
					$data['gst_number'] = trim($this->input->post('gst_number'));
					$data['cin_number'] = trim($this->input->post('cin_number'));
					$data['country_id'] = $this->input->post('country_id');
					$data['state_id'] = $this->input->post('state_id');
					$data['city_id'] = $this->input->post('city_id');
					$data['address1'] = trim($this->input->post('address1'));
					$data['address2'] = trim($this->input->post('address2'));
					$data['address3'] = trim($this->input->post('address3'));
					$data['postal_code'] = trim($this->input->post('postal_code'));
					$data['pan_number'] = trim($this->input->post('pan_number'));
					$data['account_number'] = trim($this->input->post('account_number'));
					$data['account_holder_name'] = trim($this->input->post('account_holder_name'));
					$data['ifsc_code'] = trim($this->input->post('ifsc_code'));
					$data['branch_name'] = trim($this->input->post('branch_name'));
					$data['bank_name'] = trim($this->input->post('bank_name'));
					$data['micr_code'] = trim($this->input->post('micr_code'));
					$data['swift_code'] = trim($this->input->post('swift_code'));

					$data['active_flag'] = $this->active_flag;
					$data['created_by'] = $this->user_id;
					$data['created_date'] = $this->date_time;
                    $data['last_updated_by'] = $this->user_id;
                    $data['last_updated_date'] = $this->date_time;

					#Audit Trails Add Start here
					$tableName = table_sup_suppliers;
					$menuName = manage_suppliers;
					$description = "Supplier created successsfully!";
					auditTrails(array_filter($_POST),$tableName,$type,$menuName,"",$description);
					#Audit Trails Add end here

					$this->db->insert('sup_suppliers', $data);
					$id = $this->db->insert_id();
					
					if($id !="")
					{	
						#Document No Start here
						$documentQry = "select doc_num_id,prefix_name,suffix_name,next_number 
						from doc_document_numbering as dm
						left join sm_list_type_values ltv on 
							ltv.list_type_value_id = dm.doc_type
						where 
							ltv.list_code = 'SUP' 
							and dm.active_flag = 'Y'
							and coalesce(dm.from_date,CURDATE()) <= CURDATE() 
							and coalesce(dm.to_date,CURDATE()) >= CURDATE()
						";
						$getDocumentData=$this->db->query($documentQry)->result_array();
							
						$prefixName = isset($getDocumentData[0]['prefix_name']) ? $getDocumentData[0]['prefix_name'] : NULL;
						$startingNumber = isset($getDocumentData[0]['next_number']) ? $getDocumentData[0]['next_number'] : NULL;
						$suffixName = isset($getDocumentData[0]['suffix_name']) ? $getDocumentData[0]['suffix_name'] : NULL;
						$documentNumber = $prefixName.''.$startingNumber.''.$suffixName;
						$updateDocNum = array(
							"supplier_number" 	  	 =>  $documentNumber,
							"last_updated_by" 	  	 =>  $this->user_id,
							"last_updated_date" 	 =>  $this->date_time
						);
						$this->db->where('supplier_id', $id);
						$headerTbl1 = $this->db->update('sup_suppliers', $updateDocNum);

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

						if(isset($_POST["save_btn"]))
						{
							$this->session->set_flashdata('flash_message' , "Supplier saved successfully!");
							redirect(base_url() . 'suppliers/ManageSuppliers/edit/'.$id, 'refresh');
						}
						else if(isset($_POST["submit_btn"]))
						{
							$this->session->set_flashdata('flash_message' , "Supplier submitted successfully!");
							redirect(base_url() . 'suppliers/ManageSuppliers', 'refresh');
						}
					}
				}
			break;
			
			case ($type == "edit" || $type == "view"): #edit
				$page_data['edit_data'] = $this->db->get_where('sup_suppliers', array('supplier_id' => $id))
										->result_array();
				if($_POST)
				{
					$data['supplier_name'] = trim($this->input->post('supplier_name'));
					$data['contact_person'] = trim($this->input->post('contact_person'));
					$data['mobile_number'] = trim($this->input->post('mobile_number'));
					$data['email_address'] = trim($this->input->post('email'));
					$data['gst_number'] = trim($this->input->post('gst_number'));
					$data['cin_number'] = trim($this->input->post('cin_number'));
					$data['country_id'] = $this->input->post('country_id');
					$data['state_id'] = $this->input->post('state_id');
					$data['city_id'] = $this->input->post('city_id');
					$data['address1'] = trim($this->input->post('address1'));
					$data['address2'] = trim($this->input->post('address2'));
					$data['address3'] = trim($this->input->post('address3'));
					$data['postal_code'] = trim($this->input->post('postal_code'));
					$data['pan_number'] = trim($this->input->post('pan_number'));
					$data['account_number'] = trim($this->input->post('account_number'));
					$data['account_holder_name'] = trim($this->input->post('account_holder_name'));
					$data['ifsc_code'] = trim($this->input->post('ifsc_code'));
					$data['branch_name'] = trim($this->input->post('branch_name'));
					$data['bank_name'] = trim($this->input->post('bank_name'));
					$data['micr_code'] = trim($this->input->post('micr_code'));
					$data['swift_code'] = trim($this->input->post('swift_code'));
                    $data['last_updated_by'] = $this->user_id;
                    $data['last_updated_date'] = $this->date_time;

					#Audit Trails Edit Start here
					$tableName = table_sup_suppliers;
					$menuName = manage_suppliers;
					$description = "Supplier updated successsfully!";
					auditTrails(array_filter($_POST),$tableName,$type,$menuName,$page_data['edit_data'],$description);
					#Audit Trails Edit end here


					$this->db->where('supplier_id', $id);
					$result = $this->db->update('sup_suppliers', $data);
					
					if($result)
					{
						if(isset($_POST["save_btn"]))
						{
							$this->session->set_flashdata('flash_message' , "Supplier saved successfully!");
							redirect(base_url() . 'suppliers/ManageSuppliers/edit/'.$id, 'refresh');
						}
						else if(isset($_POST["submit_btn"]))
						{
							$this->session->set_flashdata('flash_message' , "Supplier submitted successfully!");
							redirect(base_url() . 'suppliers/ManageSuppliers', 'refresh');
						}
					}
				}
			break;
			
			case ($type == "status"): #Block & Unblock
				if($status == "Y")
				{
					$data['active_flag'] = "Y";
					$data['inactive_date'] = NULL;
					$data['last_updated_by'] = $this->user_id;
					$data['last_updated_date'] = $this->date_time;
					$succ_msg = 'Supplier active successfully!';
				}
				else
				{
					$data['active_flag'] = "N";
					$data['last_updated_by'] = $this->user_id;
					$data['last_updated_date'] = $this->date_time;
					$data['inactive_date'] = $this->date_time;
					$succ_msg = 'Supplier inactive successfully!';
				}

				#Audit Trails Start here
				$tableName = table_sup_suppliers;
				$menuName = manage_suppliers;
				$id = $id;
				auditTrails($id,$tableName,$type,$menuName,"",$succ_msg);
				#Audit Trails end here

				$this->db->where('supplier_id', $id);
				$this->db->update('sup_suppliers', $data);
				$this->session->set_flashdata('flash_message' , $succ_msg);
				redirect($_SERVER['HTTP_REFERER'], 'refresh');
			break;

			case ($type == "import"): #Import
				if($_FILES)
				{
					$filename = $_FILES["csv"]["tmp_name"];     

					/*$headerColomns = array(
						"0" => "Reference No",
						"1" => "Supplier Name *",
						"2" => "Mobile Number *",
						"3" => "Contact Person",
						"4" => "Email",
						"5" => "Country",
						"6" => "State",
						"7" => "City",
						"8" => "Address 1",
						"9" => "Address 2",
						"10" => "Address 3",
						"11" => "Postal Code",
						"12" => "GST Number",
						"13" => "Pan Number",
					); */
					#print_r($headerColomns);exit;
					
					if($_FILES["csv"]["size"] > 0)
					{
						$file = fopen($filename, "r");

						for ($lines = 0; $data = fgetcsv($file,1000,",",'"'); $lines++)
						{
							#print_r($data);exit;

							if ($lines == 0) continue;
							
							$chkExistQry = "select supplier_id from sup_suppliers where 
							upper(supplier_name)='".strtoupper(trim($data[1]))."' ";
							$chkExist = $this->db->query($chkExistQry)->result_array();

							if( count($chkExist) == 0 && !empty($data[1])) #Create
							{
								#Country id Start
								$country = isset($data[5]) ? ucfirst(trim(strtolower(RemoveSpecialChar($data[5])))) : NULL;
								$countryQry = "select country_id from geo_countries 
								where replace(country_name,' ','') = '".RemoveWhiteSpace($country)."'
								";
								$checkcountry = $this->db->query($countryQry)->result_array();

								if( count($checkcountry) > 0 )
								{
									$country_id = isset($checkcountry[0]["country_id"]) ? $checkcountry[0]["country_id"] : NULL;
								}
								else
								{
									$insercountry["country_name"] = $country; 
									$insercountry['active_flag'] = $this->active_flag;
									$insercountry['created_by'] = $this->user_id;
									$insercountry['created_date'] = $this->date_time;
									$insercountry['last_updated_by'] = $this->user_id;
									$insercountry['last_updated_date'] = $this->date_time;
									
									if(!empty($country))
									{
										$this->db->insert('geo_countries', $insercountry);
										$country_id = $this->db->insert_id();
									}
								}
								#Country id end

								#State id Start								
								$state = isset($data[6]) ? ucfirst(trim(strtolower(RemoveSpecialChar($data[6])))) : NULL;
								$stateQry = "select state_id from geo_states 
								where replace(state_name,' ','') = '".RemoveWhiteSpace($state)."' ";
								$checkstate = $this->db->query($stateQry)->result_array();

								if( count($checkstate) > 0 )
								{
									$state_id = isset($checkstate[0]["state_id"]) ? $checkstate[0]["state_id"] : NULL;
								}
								else
								{
									$inserstate["state_name"] = $state;
									$inserstate["country_id"] = $country_id;
									$inserstate['active_flag'] = $this->active_flag;
									$inserstate['created_by'] = $this->user_id;
									$inserstate['created_date'] = $this->date_time;
									$inserstate['last_updated_by'] = $this->user_id;
									$inserstate['last_updated_date'] = $this->date_time;

									if(!empty($state))
									{
										$this->db->insert('geo_states', $inserstate);
										$state_id = $this->db->insert_id();
									}
								}
								#State id end

								#City id Start
								$city = isset($data[7]) ? ucfirst(trim(strtolower(RemoveSpecialChar($data[7])))) : NULL;
								$cityQry = "select city_id from geo_cities where replace(city_name,' ','') = '".RemoveWhiteSpace($city)."'
								";
								$checkcity = $this->db->query($cityQry)->result_array();

								if( count($checkcity) > 0 )
								{
									$city_id = isset($checkcity[0]["city_id"]) ? $checkcity[0]["city_id"] : NULL;
								}
								else
								{
									$insercity["city_name"] = $city;

									$insercity["state_id"] = $state_id;
									$insercity["country_id"] = $country_id;

									$insercity['active_flag'] = $this->active_flag;
									$insercity['created_by'] = $this->user_id;
									$insercity['created_date'] = $this->date_time;
									$insercity['last_updated_by'] = $this->user_id;
									$insercity['last_updated_date'] = $this->date_time;
									
									if(!empty($city))
									{
										$this->db->insert('geo_cities', $insercity);
										$city_id = $this->db->insert_id();
									}
								}							
								#City id end

								$postData['header_reference'] = trim($data[0]);
								$postData['supplier_name'] = trim($data[1]);
								$postData['mobile_number'] = trim($data[2]);
								$postData['contact_person'] = trim($data[3]);
								$postData['email_address'] = trim($data[4]);
								$postData['country_id'] = trim($country_id);
								$postData['state_id'] = trim($state_id);
								$postData['city_id'] = trim($city_id);
								$postData['address1'] = trim($data[8]);
								$postData['address2'] = trim($data[9]);
								$postData['address3'] = trim($data[10]);
								$postData['postal_code'] = substr(trim($data[11]), 0, 6);
								$postData['gst_number'] = trim($data[12]);
								$postData['cin_number'] = trim($data[13]);
								$postData['pan_number'] = trim($data[14]);
								$postData['account_number'] = trim($data[15]);
								$postData['account_holder_name'] = trim($data[16]);
								$postData['ifsc_code'] = trim($data[17]);
								$postData['branch_name'] = trim($data[18]);
								$postData['bank_name'] = trim($data[19]);
								$postData['micr_code'] = trim($data[20]);
								$postData['swift_code'] = trim($data[21]);
											
								$postData['active_flag'] = $this->active_flag;
								$postData['created_by'] = $this->user_id;
								$postData['created_date'] = $this->date_time;
								$postData['last_updated_by'] = $this->user_id;
								$postData['last_updated_date'] = $this->date_time;
							
								$this->db->insert('sup_suppliers', $postData);
								$id = $this->db->insert_id();

								#Document No Start here
								$documentQry = "select doc_num_id,prefix_name,suffix_name,next_number 
								from doc_document_numbering as dm
								left join sm_list_type_values ltv on 
									ltv.list_type_value_id = dm.doc_type
								where 
									ltv.list_code = 'SUP' 
									and dm.active_flag = 'Y'
									and coalesce(dm.from_date,CURDATE()) <= CURDATE() 
									and coalesce(dm.to_date,CURDATE()) >= CURDATE()
								";
								$getDocumentData=$this->db->query($documentQry)->result_array();
									
								$prefixName = isset($getDocumentData[0]['prefix_name']) ? $getDocumentData[0]['prefix_name'] : NULL;
								$startingNumber = isset($getDocumentData[0]['next_number']) ? $getDocumentData[0]['next_number'] : NULL;
								$suffixName = isset($getDocumentData[0]['suffix_name']) ? $getDocumentData[0]['suffix_name'] : NULL;
								$documentNumber = $prefixName.''.$startingNumber.''.$suffixName;
								$updateDocNum = array(
									"supplier_number" 	  	 =>  $documentNumber,
									"last_updated_by" 	  	 =>  $this->user_id,
									"last_updated_date" 	 =>  $this->date_time
								);
								$this->db->where('supplier_id', $id);
								$headerTbl1 = $this->db->update('sup_suppliers', $updateDocNum);

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
							}
							else
							{
								#Country id Start
								$country = isset($data[5]) ? ucfirst(trim(strtolower(RemoveSpecialChar($data[5])))) : NULL;
								$countryQry = "select country_id from geo_countries 
								where replace(country_name,' ','') = '".RemoveWhiteSpace($country)."'";

								$checkcountry = $this->db->query($countryQry)->result_array();

								if( count($checkcountry) > 0 )
								{
									$country_id = isset($checkcountry[0]["country_id"]) ? $checkcountry[0]["country_id"] : NULL;
								}
								else
								{
									$insercountry["country_name"] = $country; 
									
									$insercountry['active_flag'] = $this->active_flag;
									$insercountry['created_by'] = $this->user_id;
									$insercountry['created_date'] = $this->date_time;
									$insercountry['last_updated_by'] = $this->user_id;
									$insercountry['last_updated_date'] = $this->date_time;
									
									$this->db->insert('geo_countries', $insercountry);
									$country_id = $this->db->insert_id();
								}
								#Country id end

								#State id Start
								$state = isset($data[6]) ? ucfirst(trim(strtolower(RemoveSpecialChar($data[6])))) : NULL;
								$stateQry = "select state_id from geo_states 
								where replace(state_name,' ','') = '".RemoveWhiteSpace($state)."' ";
								$checkstate = $this->db->query($stateQry)->result_array();

								if( count($checkstate) > 0 )
								{
									$state_id = isset($checkstate[0]["state_id"]) ? $checkstate[0]["state_id"] : NULL;
								}
								else
								{
									$inserstate["state_name"] = $state;
									$inserstate["country_id"] = $country_id;

									$inserstate['active_flag'] = $this->active_flag;
									$inserstate['created_by'] = $this->user_id;
									$inserstate['created_date'] = $this->date_time;
									$inserstate['last_updated_by'] = $this->user_id;
									$inserstate['last_updated_date'] = $this->date_time;

									$this->db->insert('geo_states', $inserstate);
									$state_id = $this->db->insert_id();
								}
								#State id end

								#City id Start
								$city = isset($data[7]) ? ucfirst(trim(strtolower(RemoveSpecialChar($data[7])))) : NULL;
								$cityQry = "select city_id from geo_cities where replace(city_name,' ','') = '".RemoveWhiteSpace($city)."'
								";
								$checkcity = $this->db->query($cityQry)->result_array();

								if( count($checkcity) > 0 )
								{
									$city_id = isset($checkcity[0]["city_id"]) ? $checkcity[0]["city_id"] : 0;
								}
								else
								{
									$insercity["city_name"] = $city;
									$insercity["state_id"] = $state_id;
									$insercity["country_id"] = $country_id;

									$inserstate['active_flag'] = $this->active_flag;
									$inserstate['created_by'] = $this->user_id;
									$inserstate['created_date'] = $this->date_time;
									$inserstate['last_updated_by'] = $this->user_id;
									$inserstate['last_updated_date'] = $this->date_time;

									$this->db->insert('geo_cities', $insercity);
									$city_id = $this->db->insert_id();
								}							
								#City id end

								$postData['header_reference'] = trim($data[0]);
								$postData['supplier_name'] = trim($data[1]);
								$postData['mobile_number'] = trim($data[2]);
								$postData['contact_person'] = trim($data[3]);
								$postData['email_address'] = trim($data[4]);
								$postData['country_id'] = trim($country_id);
								$postData['state_id'] = trim($state_id);
								$postData['city_id'] = trim($city_id);
								$postData['address1'] = trim($data[8]);
								$postData['address2'] = trim($data[9]);
								$postData['address3'] = trim($data[10]);
								$postData['postal_code'] = trim($data[11]);
								$postData['gst_number'] = trim($data[12]);
								$postData['cin_number'] = trim($data[13]);
								$postData['pan_number'] = trim($data[14]);
								$postData['account_number'] = trim($data[15]);
								$postData['account_holder_name'] = trim($data[16]);
								$postData['ifsc_code'] = trim($data[17]);
								$postData['branch_name'] = trim($data[18]);
								$postData['bank_name'] = trim($data[19]);
								$postData['micr_code'] = trim($data[20]);
								$postData['swift_code'] = trim($data[21]);
								
								$postData['last_updated_by'] = $this->user_id;
								$postData['last_updated_date'] = $this->date_time;
								
								$this->db->where('supplier_name', trim($data[0]));
								$this->db->update('sup_suppliers', $postData);
							}
						}
						fclose($file); 
					}
					else
					{
						$this->session->set_flashdata('error_message' , "Supplier imported error!");
						redirect(base_url() . 'suppliers/ManageSuppliers', 'refresh');
					}
					$this->session->set_flashdata('flash_message' , "Supplier imported successfully!");
					redirect(base_url() . 'suppliers/ManageSuppliers', 'refresh');
				}
			break;
			
			default : #Manage
				$totalResult = $this->suppliers_model->getManageSuppliers("","",$this->totalCount);
				$page_data["totalRows"] = $totalRows = count($totalResult);

				if(!empty($_SESSION['PAGE']))
				{$limit = $_SESSION['PAGE'];
				}else{$limit = 10;}

				//$redirectURL = 'suppliers/ManageSuppliers';
				$supplier_id = isset($_GET['supplier_id']) ? $_GET['supplier_id'] :NULL;
				$active_flag = isset($_GET['active_flag']) ? $_GET['active_flag'] :NULL;
				$redirectURL = 'suppliers/ManageSuppliers?supplier_id='.$supplier_id.'&active_flag='.$active_flag.'';
				
				if (!empty($_GET['supplier_id']) || !empty($_GET['active_flag'])) {
					$base_url = base_url('suppliers/ManageSuppliers?supplier_id='.$_GET['supplier_id'].'&active_flag='.$_GET['active_flag'].'');
				} else {
					$base_url = base_url('suppliers/ManageSuppliers?supplier_id=&active_flag=Y');
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
				
				$page_data['resultData']  = $result= $data =$this->suppliers_model->getManageSuppliers($limit, $offset,$this->pageCount);
				
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
	
	#Supplier Sites
	function manageSupplierSites($type = '', $id = '', $status = '')
    {
		if (empty($this->user_id))
        {
			redirect(base_url() . 'admin/adminLogin', 'refresh');
		}
	
		$page_data['type'] = $type;
		$page_data['id'] = $id;
		
		$page_data['ManageSuppliers'] = 1;
		$page_data['page_name']  = 'suppliers/manageSupplierSites';
		$page_data['page_title'] = 'Supplier Sites';
		
		switch(true)
		{
			case ($type == "add"): #add
				if($_POST)
				{
					$data['supplier_id'] = $this->input->post('supplier_id');
					$data['site_name'] = trim($this->input->post('site_name'));
					$data['contact_person'] = trim($this->input->post('contact_person'));
					$data['mobile_number'] = trim($this->input->post('mobile_number'));
					$data['email_address'] = trim($this->input->post('email_address'));
					$data['gst_number'] = trim($this->input->post('gst_number'));
					$data['cin_number'] = trim($this->input->post('cin_number'));
					$data['country_id'] = $this->input->post('country_id');
					$data['state_id'] = $this->input->post('state_id');
					$data['city_id'] = $this->input->post('city_id');
					$data['address1'] = trim($this->input->post('address1'));
					$data['address2'] = trim($this->input->post('address2'));
					$data['address3'] = trim($this->input->post('address3'));
					$data['postal_code'] = trim($this->input->post('postal_code'));
					$data['pan_number'] = trim($this->input->post('pan_number'));
					$data['account_number'] = trim($this->input->post('account_number'));
					$data['account_holder_name'] = trim($this->input->post('account_holder_name'));
					$data['ifsc_code'] = trim($this->input->post('ifsc_code'));
					$data['bank_name'] = trim($this->input->post('bank_name'));
					$data['branch_name'] = trim($this->input->post('branch_name'));
					$data['micr_code'] = trim($this->input->post('micr_code'));
					$data['swift_code'] = trim($this->input->post('swift_code'));
					$data['active_flag'] = $this->active_flag;
					$data['created_by'] = $this->user_id;
					$data['created_date'] = $this->date_time;
                    $data['last_updated_by'] = $this->user_id;
                    $data['last_updated_date'] = $this->date_time;
					

					#Audit Trails Add Start here
					$tableName = table_sup_supplier_sites;
					$menuName = supplier_sites;
					$description = "Supplier site created successsfully!";
					auditTrails(array_filter($_POST),$tableName,$type,$menuName,"",$description);
					#Audit Trails Add end here

					$this->db->insert('sup_supplier_sites', $data);
					$id = $this->db->insert_id();
					
					if($id !="")
					{	
						$this->session->set_flashdata('flash_message' , "Supplier site created successfully!");
						redirect(base_url() . 'suppliers/manageSupplierSites', 'refresh');
					}
				}
			break;
			
			case ($type == "edit" || $type == "view"): #edit
				$page_data['edit_data'] = $this->db->get_where('sup_supplier_sites', array('supplier_site_id' => $id))
										->result_array();
				
				if($_POST)
				{
					$data['supplier_id'] = $this->input->post('supplier_id');
					$data['site_name'] = trim($this->input->post('site_name'));
					$data['contact_person'] = trim($this->input->post('contact_person'));
					$data['mobile_number'] = trim($this->input->post('mobile_number'));
					$data['email_address'] = trim($this->input->post('email_address'));
					$data['gst_number'] = trim($this->input->post('gst_number'));
					$data['cin_number'] = trim($this->input->post('cin_number'));
					$data['country_id'] = $this->input->post('country_id');
					$data['state_id'] = $this->input->post('state_id');
					$data['city_id'] = $this->input->post('city_id');
					$data['address1'] = trim($this->input->post('address1'));
					$data['address2'] = trim($this->input->post('address2'));
					$data['address3'] = trim($this->input->post('address3'));
					$data['postal_code'] = trim($this->input->post('postal_code'));
					$data['pan_number'] = trim($this->input->post('pan_number'));
					$data['account_number'] = trim($this->input->post('account_number'));
					$data['account_holder_name'] = trim($this->input->post('account_holder_name'));
					$data['ifsc_code'] = trim($this->input->post('ifsc_code'));
					$data['bank_name'] = trim($this->input->post('bank_name'));
					$data['branch_name'] = trim($this->input->post('branch_name'));
					$data['micr_code'] = trim($this->input->post('micr_code'));
					$data['swift_code'] = trim($this->input->post('swift_code'));
                    $data['last_updated_by'] = $this->user_id;
                    $data['last_updated_date'] = $this->date_time;
					
					#Audit Trails Add Start here
					$tableName = table_sup_supplier_sites;
					$menuName = supplier_sites;
					$description = "Supplier site updated successsfully!";
					auditTrails(array_filter($_POST),$tableName,$type,$menuName,$page_data['edit_data'],$description);
					#Audit Trails Edit end here

					$this->db->where('supplier_site_id', $id);
					$result = $this->db->update('sup_supplier_sites', $data);
					
					if($result)
					{
						
						$this->session->set_flashdata('flash_message' , "Supplier site updated successfully!");
						redirect(base_url() . 'suppliers/manageSupplierSites/edit/'.$id, 'refresh');
					}
				}
			break;
			
			case ($type == "status"): #Block & Unblock
				if($status == "Y")
				{
					$data['active_flag'] = "Y";
					$data['inactive_date'] = NULL;
					$data['last_updated_by'] = $this->user_id;
					$data['last_updated_date'] = $this->date_time;
					$succ_msg = 'Supplier Site active successfully!';
				}
				else
				{
					$data['active_flag'] = "N";
					$data['inactive_date'] = $this->date_time;
					$data['last_updated_by'] = $this->user_id;
					$data['last_updated_date'] = $this->date_time;
					$succ_msg = 'Supplier Site inctive successfully!';

				}

				#Audit Trails Start here
				$tableName = table_sup_supplier_sites;
				$menuName = supplier_sites;
				$id = $id;
				auditTrails($id,$tableName,$type,$menuName,"",$succ_msg);
				#Audit Trails end here

				$this->db->where('supplier_site_id', $id);
				$this->db->update('sup_supplier_sites', $data);
				$this->session->set_flashdata('flash_message' , $succ_msg);
				redirect($_SERVER['HTTP_REFERER'], 'refresh');
			break;
			
			case ($type == "import"): #Import
				if($_FILES)
				{
					$filename = $_FILES["csv"]["tmp_name"];      
					
					if($_FILES["csv"]["size"] > 0)
					{
						$file = fopen($filename, "r");
						
						for ($lines = 0; $data = fgetcsv($file,1000,",",'"'); $lines++) 
						{
							if ($lines == 0) continue;
							
							$supplierQry = "select supplier_id from sup_suppliers where 
							upper(replace(supplier_name,' ',''))='".strtoupper(trim(RemoveWhiteSpace($data[2])))."' 
							";
							$getSupplier = $this->db->query($supplierQry)->result_array();

							
							$supplier_id = isset($getSupplier[0]["supplier_id"]) ? $getSupplier[0]["supplier_id"] : NULL;

							$chkExistQry = "select supplier_site_id from sup_supplier_sites where 
							site_name='".trim($data[3])."' 
							and supplier_id = '".$supplier_id."' ";
							$chkExist = $this->db->query($chkExistQry)->result_array();

							if( count($chkExist) == 0 && count($getSupplier) > 0 && !empty($data[3]) ) #Create
							{ 
								#Country id Start
								$country = isset($data[7]) ? ucfirst(trim(strtolower(RemoveSpecialChar($data[7])))) : NULL;
								$countryQry = "select country_id from geo_countries 
								where replace(country_name,' ','') = '".RemoveWhiteSpace($country)."'
								";
								$checkcountry = $this->db->query($countryQry)->result_array();

								if( count($checkcountry) > 0 )
								{
									$country_id = isset($checkcountry[0]["country_id"]) ? $checkcountry[0]["country_id"] : 0;
								}
								else
								{
									$insercountry["country_name"] = $country; 
									$insercountry['active_flag'] = $this->active_flag;
									$insercountry['created_by'] = $this->user_id;
									$insercountry['created_date'] = $this->date_time;
									$insercountry['last_updated_by'] = $this->user_id;
									$insercountry['last_updated_date'] = $this->date_time;
									
									if(!empty($country))
									{
										$this->db->insert('geo_countries', $insercountry);
										$country_id = $this->db->insert_id();
									}
								}
								#Country id end

								#State id Start
								
								$state = isset($data[8]) ? ucfirst(trim(strtolower(RemoveSpecialChar($data[8])))) : NULL;
								$stateQry = "select state_id from geo_states 
								where replace(state_name,' ','') = '".RemoveWhiteSpace($state)."' ";
								$checkstate = $this->db->query($stateQry)->result_array();

								if( count($checkstate) > 0 )
								{
									$state_id = isset($checkstate[0]["state_id"]) ? $checkstate[0]["state_id"] : 0;
								}
								else
								{
									$inserstate["state_name"] = $state;
									$inserstate["country_id"] = $country_id;
									$inserstate['active_flag'] = $this->active_flag;
									$inserstate['created_by'] = $this->user_id;
									$inserstate['created_date'] = $this->date_time;
									$inserstate['last_updated_by'] = $this->user_id;
									$inserstate['last_updated_date'] = $this->date_time;
			
									if(!empty($state))
									{
										$this->db->insert('geo_states', $inserstate);
										$state_id = $this->db->insert_id();
									}
								}
								#State id end

								#City id Start
								$city = isset($data[9]) ? ucfirst(trim(strtolower(RemoveSpecialChar($data[9])))) : NULL;
								$cityQry = "select city_id from geo_cities where replace(city_name,' ','') = '".RemoveWhiteSpace($city)."'
								";
								$checkcity = $this->db->query($cityQry)->result_array();

								if( count($checkcity) > 0 )
								{
									$city_id = isset($checkcity[0]["city_id"]) ? $checkcity[0]["city_id"] : 0;
								}
								else
								{
									$insercity["city_name"]  = $city;
									$insercity["state_id"]   = $state_id;
									$insercity["country_id"] = $country_id;

									$insercity['active_flag'] = $this->active_flag;
									$insercity['created_by'] = $this->user_id;
									$insercity['created_date'] = $this->date_time;
									$insercity['last_updated_by'] = $this->user_id;
									$insercity['last_updated_date'] = $this->date_time;
									
									if(!empty($city))
									{
										$this->db->insert('geo_cities', $insercity);
										$city_id = $this->db->insert_id();
									}
								}							
								#City id end
								$postData['header_reference'] = trim($data[0]);
								$postData['line_reference'] = trim($data[1]);
								$postData['supplier_id'] = $supplier_id;
								$postData['site_name'] = trim($data[3]);
								$postData['mobile_number'] = trim($data[4]);

								$postData['contact_person'] = trim($data[5]);
								$postData['email_address'] = trim($data[6]);
								$postData['country_id'] = trim($country_id);
								$postData['state_id'] = trim($state_id);
								$postData['city_id'] = trim($city_id);

								$postData['address1'] = trim($data[10]);
								$postData['address2'] = trim($data[11]);
								$postData['address3'] = trim($data[12]);
								$postData['postal_code'] = substr(trim($data[13]), 0, 6);
								$postData['gst_number'] = trim($data[14]);
								$postData['cin_number'] = trim($data[15]);
								$postData['pan_number'] = trim($data[16]);
								$postData['account_number'] = trim($data[17]);
								$postData['account_holder_name'] = trim($data[18]);
								$postData['ifsc_code'] = trim($data[19]);
								$postData['bank_name'] = trim($data[21]);
								$postData['branch_name'] = trim($data[20]);
								$postData['micr_code'] = trim($data[22]);
								$postData['swift_code'] = trim($data[23]);
								
								$postData['active_flag'] = $this->active_flag;
								$postData['created_by'] = $this->user_id;
								$postData['created_date'] = $this->date_time;
								$postData['last_updated_by'] = $this->user_id;
								$postData['last_updated_date'] = $this->date_time;
							
								$this->db->insert('sup_supplier_sites', $postData);
								$id = $this->db->insert_id();
							}
							else
							{
								#Country id Start
								$country = isset($data[5]) ? ucfirst(trim(strtolower(RemoveSpecialChar($data[5])))) : NULL;
								$countryQry = "select country_id from geo_countries 
								where replace(country_name,' ','') = '".RemoveWhiteSpace($country)."'
								";
								$checkcountry = $this->db->query($countryQry)->result_array();

								if( count($checkcountry) > 0 )
								{
									$country_id = isset($checkcountry[0]["country_id"]) ? $checkcountry[0]["country_id"] : NULL;
								}
								else
								{
									$insercountry["country_name"] = $country; 
									$insercountry['active_flag'] = $this->active_flag;
									$insercountry['created_by'] = $this->user_id;
									$insercountry['created_date'] = $this->date_time;
									$insercountry['last_updated_by'] = $this->user_id;
									$insercountry['last_updated_date'] = $this->date_time;
									
									if(!empty($country))
									{
										$this->db->insert('geo_countries', $insercountry);
										$country_id = $this->db->insert_id();
									}
								}
								#Country id end

								#State id Start								
								$state = isset($data[6]) ? ucfirst(trim(strtolower(RemoveSpecialChar($data[6])))) : NULL;
								$stateQry = "select state_id from geo_states 
								where replace(state_name,' ','') = '".RemoveWhiteSpace($state)."' ";
								$checkstate = $this->db->query($stateQry)->result_array();

								if( count($checkstate) > 0 )
								{
									$state_id = isset($checkstate[0]["state_id"]) ? $checkstate[0]["state_id"] : NULL;
								}
								else
								{
									$inserstate["state_name"] = $state;
									$inserstate["country_id"] = $country_id;
									$inserstate['active_flag'] = $this->active_flag;
									$inserstate['created_by'] = $this->user_id;
									$inserstate['created_date'] = $this->date_time;
									$inserstate['last_updated_by'] = $this->user_id;
									$inserstate['last_updated_date'] = $this->date_time;

									if(!empty($state))
									{
										$this->db->insert('geo_states', $inserstate);
										$state_id = $this->db->insert_id();
									}
								}
								#State id end

								#City id Start
								$city = isset($data[7]) ? ucfirst(trim(strtolower(RemoveSpecialChar($data[7])))) : NULL;
								$cityQry = "select city_id from geo_cities where replace(city_name,' ','') = '".RemoveWhiteSpace($city)."'
								";
								$checkcity = $this->db->query($cityQry)->result_array();

								if( count($checkcity) > 0 )
								{
									$city_id = isset($checkcity[0]["city_id"]) ? $checkcity[0]["city_id"] : NULL;
								}
								else
								{
									$insercity["city_name"] = $city;

									$insercity["state_id"] = $state_id;
									$insercity["country_id"] = $country_id;

									$insercity['active_flag'] = $this->active_flag;
									$insercity['created_by'] = $this->user_id;
									$insercity['created_date'] = $this->date_time;
									$insercity['last_updated_by'] = $this->user_id;
									$insercity['last_updated_date'] = $this->date_time;
									
									if(!empty($city))
									{
										$this->db->insert('geo_cities', $insercity);
										$city_id = $this->db->insert_id();
									}
								}							
								#City id end

								$postData['header_reference'] = trim($data[0]);
								$postData['line_reference'] = trim($data[1]);
								$postData['supplier_id'] = $supplier_id;
								$postData['site_name'] = trim($data[3]);
								$postData['mobile_number'] = trim($data[4]);

								$postData['contact_person'] = trim($data[5]);
								$postData['email_address'] = trim($data[6]);
								$postData['country_id'] = trim($country_id);
								$postData['state_id'] = trim($state_id);
								$postData['city_id'] = trim($city_id);

								$postData['address1'] = trim($data[10]);
								$postData['address2'] = trim($data[11]);
								$postData['address3'] = trim($data[12]);
								$postData['postal_code'] = substr(trim($data[13]), 0, 6);
								$postData['gst_number'] = trim($data[14]);
								$postData['cin_number'] = trim($data[15]);
								$postData['pan_number'] = trim($data[16]);
								$postData['account_number'] = trim($data[17]);
								$postData['account_holder_name'] = trim($data[18]);
								$postData['ifsc_code'] = trim($data[19]);
								$postData['bank_name'] = trim($data[21]);
								$postData['branch_name'] = trim($data[20]);
								$postData['micr_code'] = trim($data[22]);
								$postData['swift_code'] = trim($data[23]);
								$postData['last_updated_by'] = $this->user_id;
								$postData['last_updated_date'] = $this->date_time;

								$this->db->where('site_name', trim($data[3]));
								$this->db->where('supplier_id', trim($supplier_id));
								$this->db->update('sup_supplier_sites',$postData);
							}
						}
						fclose($file); 
					}
					else
					{
						$this->session->set_flashdata('error_message' , "Supplier sites imported error!");
						redirect(base_url() . 'suppliers/ManageSupplierSites', 'refresh');
					}
					$this->session->set_flashdata('flash_message' , "Supplier sites imported successfully!");
					redirect(base_url() . 'suppliers/ManageSupplierSites', 'refresh');
				}
			break;

			default : #Manage
				$totalResult = $this->suppliers_model->getManageSupplierSites("","",$this->totalCount);
				$page_data["totalRows"] = $totalRows = count($totalResult);

				if(!empty($_SESSION['PAGE']))
				{$limit = $_SESSION['PAGE'];
				}else{$limit = 10;}
				 
				$supplier_id = isset($_GET['supplier_id']) ? $_GET['supplier_id'] :NULL;
				$supplier_site_id = isset($_GET['supplier_site_id']) ? $_GET['supplier_site_id'] :NULL;
				$active_flag = isset($_GET['active_flag']) ? $_GET['active_flag'] :NULL;


				$redirectURL = 'suppliers/ManageSupplierSites?supplier_id='.$supplier_id.'&supplier_site_id='.$supplier_site_id.'&active_flag='.$active_flag.'';
				
				if (!empty($_GET['supplier_id']) || !empty($_GET['supplier_site_id']) || !empty($_GET['active_flag'])) {
					$base_url = base_url('suppliers/ManageSupplierSites?supplier_id='.$_GET['supplier_id'].'&supplier_site_id='.$_GET['supplier_site_id'].'&active_flag='.$_GET['active_flag'].'');
				} else {
					$base_url = base_url('suppliers/ManageSupplierSites?supplier_id=&supplier_site_id=&active_flag=Y');
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
				
				$page_data['resultData']  = $result= $data =$this->suppliers_model->getManageSupplierSites($limit, $offset,$this->pageCount);
				
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

	function ajaxSupplierList() 
	{
		
		if(isset($_POST["query"]))  
		{  
			$output = '';  
			
			$supplier_name = $_POST['query'];

			$result = $this->suppliers_model->getAjaxSupplierAll($supplier_name);
			
			$output = '<ul class="list-unstyled-supplier_id">';  
			
			if( count($result) > 0 )  
			{  
				foreach($result as $row)  
				{	
					$supplier_name = $row["supplier_name"];
					$supplier_id = $row["supplier_id"];
					$output .= '<a><li onclick="return getSupplierList(\'' .$supplier_id. '\',\'' .$supplier_name. '\');">'.$supplier_name.'</li></a>';  
				}  
			}  
			else  
			{  
				$supplier_name = "";
				$supplier_id = "";
				
				$output .= '<li onclick="return getSUpplierList(\'' .$supplier_id. '\',\'' .$supplier_name. '\');">Sorry! Supplier Not Found.</li>';  
			}
			$output .= '</ul>';  
			echo $output;  
		}
	}

	function ajaxSupplierSiteList() 
	{
		if(isset($_POST["query"]))  
		{  
			$supplier_id = $_POST['query'];

			$result = $this->suppliers_model->getAjaxSupplierSites($supplier_id);

			if( count($result) > 0)
			{
				echo '<option value="">- Select -</option>';
				foreach($result as $val)
				{
					echo '<option value="'.$val['supplier_site_id'].'">'.ucfirst($val['site_name']).'</option>';
				}
			}
			else
			{
				echo '<option value="">- Select -</option>';
			}
		}
		die; // This line might need to be removed if it's causing issues
	}

}
?>
