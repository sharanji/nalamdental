<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Customer extends CI_Controller 
{
	function __construct()
	{
		parent::__construct();
		$this->load->database();
        $this->load->library('session');
		$this->load->model('customer_model');
        #Cache Control
		$this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
		$this->output->set_header('Pragma: no-cache');
	}

	function ManageCustomer($type = '', $id = '', $status = '')
    {
		$page_data['type'] = $type;
		$page_data['id'] = $id;
		
		$page_data['ManageCustomer'] = 1;
		$page_data['Customers'] = 1;
		$page_data['page_name']  = 'customer/ManageCustomer';
		$page_data['page_title'] = 'Customers';

		switch(true)
		{
			case ($type == "add"): #add
				if($_POST)
				{
					$data['mobile_number'] = $this->input->post('mobile_number');
					
					$ChkExist = $this->db->query("select customer_id from cus_customers where mobile_number='".$data['mobile_number']."'")->result_array();
					
					if( count($ChkExist) > 0)
					{
						$this->session->set_flashdata('error_message' , "Sorry! Already exist Mobile No.!");
						redirect($_SERVER['HTTP_REFERER'], 'refresh');
					}
					
					$data['customer_name'] = $this->input->post('customer_name');
					$data['contact_person'] = $this->input->post('contact_person');
					$data['mobile_number'] = $this->input->post('mobile_number');
					$data['alt_mobile_number'] = $this->input->post('alt_mobile_number');
					$data['email_address'] = $this->input->post('email_address');
					$data['gst_number'] = $this->input->post('gst_number');
					
					$data['country_id'] = $this->input->post('country_id');
					$data['state_id'] = $this->input->post('state_id');
					$data['city_id'] = $this->input->post('city_id');
					$data['address1'] = $this->input->post('address1');
					$data['address2'] = $this->input->post('address2');
					$data['address3'] = $this->input->post('address3');
					$data['postal_code'] = $this->input->post('postal_code');
					
					$data['active_flag'] = $this->active_flag;
					$data['created_by'] = $this->user_id;
					$data['created_date'] = $this->date_time;
                    $data['last_updated_by'] = $this->user_id;
                    $data['last_updated_date'] = $this->date_time;
					
					#Audit Trails Add Start here
					$tableName = table_cus_customers;
					$menuName = manage_customers;
					$description = "Customer created successsfully!";
					auditTrails(array_filter($_POST),$tableName,$type,$menuName,"",$description);
					#Audit Trails Add end here

					
					$this->db->insert('cus_customers', $data);
					$id = $this->db->insert_id();
					
					if($id !="")
					{	
						#Document No Start here
						$documentQry = "select doc_num_id,prefix_name,suffix_name,next_number 
						from doc_document_numbering as dm
						left join sm_list_type_values ltv on 
							ltv.list_type_value_id = dm.doc_type
						where 
							ltv.list_code = 'CUS' 
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
							"customer_number" 	  	 =>  $documentNumber,
							"last_updated_by" 	  	 =>  $this->user_id,
							"last_updated_date" 	 =>  $this->date_time
						);
						$this->db->where('customer_id', $id);
						$headerTbl1 = $this->db->update('cus_customers', $updateDocNum);

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

						$this->session->set_flashdata('flash_message' , "Customer created successfully!");
						redirect(base_url() . 'customer/ManageCustomer/edit/'.$id, 'refresh');
					}
				}
			break;
			
			case ($type == "edit" || $type == "view"):#edit
				$page_data['edit_data'] = $this->db->get_where('cus_customers', array('customer_id' => $id))
										->result_array();
										
				if($_POST)
				{
					$data['mobile_number'] = $this->input->post('mobile_number');
					
					$ChkExist = $this->db->query("select customer_id from cus_customers 
						where 
							mobile_number='".$data['mobile_number']."'
							AND customer_id != '".$id."'
							")->result_array();
					
					if( count($ChkExist) > 0)
					{
						$this->session->set_flashdata('error_message' , "Sorry! Already exist Mobile No.!");
						redirect($_SERVER['HTTP_REFERER'], 'refresh');
					}
					
					$data['customer_name'] = $this->input->post('customer_name');
					$data['contact_person'] = $this->input->post('contact_person');
					
					$data['alt_mobile_number'] = $this->input->post('alt_mobile_number');
					$data['email_address'] = $this->input->post('email_address');
					$data['gst_number'] = $this->input->post('gst_number');
					
					$data['country_id'] = $this->input->post('country_id');
					$data['state_id'] = $this->input->post('state_id');
					$data['city_id'] = $this->input->post('city_id');
					$data['address1'] = $this->input->post('address1');
					$data['address2'] = $this->input->post('address2');
					$data['address3'] = $this->input->post('address3');
					$data['postal_code'] = $this->input->post('postal_code');
					
                    $data['last_updated_by'] = $this->user_id;
                    $data['last_updated_date'] = $this->date_time;
					
					#Audit Trails Add Start here
					$tableName = table_cus_customers;
					$menuName = manage_customers;
					$description = "Customer updated successsfully!";
					auditTrails(array_filter($_POST),$tableName,$type,$menuName,$page_data['edit_data'],$description);
					#Audit Trails Edit end here

					$this->db->where('customer_id', $id);
					$result = $this->db->update('cus_customers', $data);
					
					if($result)
					{

						$this->session->set_flashdata('flash_message' , "Customer updated Successfully!");
						redirect(base_url() . 'customer/ManageCustomer/edit/'.$id, 'refresh');
					}
					
				}
			break;

			case ($type == "status") : #Block & Unblock
			    if($status == "Y")
				{
					$data['active_flag'] = "Y";
					$data['inactive_date'] = NULL;
					$data['last_updated_by'] = $this->user_id;
					$data['last_updated_date'] = $this->date_time;
					$succ_msg = 'customer active successfully!';
				}
				else
				{
					$data['active_flag'] = "N";
					$data['last_updated_by'] = $this->user_id;
					$data['last_updated_date'] = $this->date_time;
					$data['inactive_date'] = $this->date_time;
					$succ_msg = 'customer inactive successfully!';
				}

				#Audit Trails Start here
				$tableName = table_cus_customers;
				$menuName = manage_customers;
				$id = $id;
				auditTrails($id,$tableName,$type,$menuName,"",$succ_msg);
				#Audit Trails end here

				$this->db->where('customer_id', $id);
				$this->db->update('cus_customers', $data);
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
							
							$chkExistQry = "select customer_id from cus_customers where 
							upper(customer_name)='".strtoupper(trim($data[1]))."' ";
							$chkExist = $this->db->query($chkExistQry)->result_array();

							if( count($chkExist) == 0 && !empty($data[1]) ) #Create
							{
								#Country id Start
								$country = isset($data[6]) ? ucfirst(trim(strtolower(RemoveSpecialChar($data[6])))) : NULL;
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
								$state = isset($data[7]) ? ucfirst(trim(strtolower(RemoveSpecialChar($data[7])))) : NULL;
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
								$city = isset($data[8]) ? ucfirst(trim(strtolower(RemoveSpecialChar($data[8])))) : NULL;
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
								$postData['customer_name'] = trim($data[1]);
								$postData['mobile_number'] = trim($data[2]);
								$postData['email_address'] = trim($data[3]);
								$postData['gst_number'] = trim($data[4]);
								$postData['contact_person'] = trim($data[5]);
								$postData['country_id'] = trim($country_id);
								$postData['state_id'] = trim($state_id);
								$postData['city_id'] = trim($city_id);
								$postData['address1'] = trim($data[9]);
								$postData['address2'] = trim($data[10]);
								$postData['address3'] = trim($data[11]);
								$postData['postal_code'] = trim($data[12]);
								
								
								$postData['active_flag'] = $this->active_flag;
								$postData['created_by'] = $this->user_id;
								$postData['created_date'] = $this->date_time;
								$postData['last_updated_by'] = $this->user_id;
								$postData['last_updated_date'] = $this->date_time;
							
								$this->db->insert('cus_customers', $postData);
								$id = $this->db->insert_id();

								#Document No Start here
								$documentQry = "select doc_num_id,prefix_name,suffix_name,next_number 
								from doc_document_numbering as dm
								left join sm_list_type_values ltv on 
									ltv.list_type_value_id = dm.doc_type
								where 
									ltv.list_code = 'CUS' 
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
									"customer_number" 	  	 =>  $documentNumber,
									"last_updated_by" 	  	 =>  $this->user_id,
									"last_updated_date" 	 =>  $this->date_time
								);
								$this->db->where('customer_id', $id);
								$headerTbl1 = $this->db->update('cus_customers', $updateDocNum);

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

								#Customer Site Start

								#Customer Site billing start
								#Country id Start
								$billing_country = isset($data[14]) ? ucfirst(trim(strtolower(RemoveSpecialChar($data[14])))) : NULL;
								$countryQry = "select country_id from geo_countries 
								where replace(country_name,' ','') = '".RemoveWhiteSpace($billing_country)."'
								";
								$checkcountry = $this->db->query($countryQry)->result_array();
				
								if( count($checkcountry) > 0 )
								{
									$billing_country_id = isset($checkcountry[0]["country_id"]) ? $checkcountry[0]["country_id"] : NULL;
								}
								else
								{
									$insercountry["country_name"] = $billing_country; 
									$insercountry['active_flag'] = $this->active_flag;
									$insercountry['created_by'] = $this->user_id;
									$insercountry['created_date'] = $this->date_time;
									$insercountry['last_updated_by'] = $this->user_id;
									$insercountry['last_updated_date'] = $this->date_time;
									
									if(!empty($billing_country))
									{
										$this->db->insert('geo_countries', $insercountry);
										$billing_country_id = $this->db->insert_id();
									}
								}
								#Country id end
				
								#State id Start								
								$billing_state = isset($data[15]) ? ucfirst(trim(strtolower(RemoveSpecialChar($data[15])))) : NULL;
								$stateQry = "select state_id from geo_states 
								where replace(state_name,' ','') = '".RemoveWhiteSpace($billing_state)."' ";
								$checkstate = $this->db->query($stateQry)->result_array();
				
								if( count($checkstate) > 0 )
								{
									$billing_state_id = isset($checkstate[0]["state_id"]) ? $checkstate[0]["state_id"] : NULL;
								}
								else
								{
									$inserstate["state_name"] = $billing_state;
									$inserstate["country_id"] = $billing_country_id;
									$inserstate['active_flag'] = $this->active_flag;
									$inserstate['created_by'] = $this->user_id;
									$inserstate['created_date'] = $this->date_time;
									$inserstate['last_updated_by'] = $this->user_id;
									$inserstate['last_updated_date'] = $this->date_time;
				
									if(!empty($billing_state))
									{
										$this->db->insert('geo_states', $inserstate);
										$billing_state_id = $this->db->insert_id();
									}
								}
								#State id end
				
								#City id Start
								$billing_city = isset($data[16]) ? ucfirst(trim(strtolower(RemoveSpecialChar($data[16])))) : NULL;
								$cityQry = "select city_id from geo_cities where replace(city_name,' ','') = '".RemoveWhiteSpace($billing_city)."'
								";
								$checkcity = $this->db->query($cityQry)->result_array();
				
								if( count($checkcity) > 0 )
								{
									$billing_city_id = isset($checkcity[0]["city_id"]) ? $checkcity[0]["city_id"] : NULL;
								}
								else
								{
									$insercity["city_name"] = $billing_city;
				
									$insercity["state_id"] = $billing_state_id;
									$insercity["country_id"] = $billing_country_id;
				
									$insercity['active_flag'] = $this->active_flag;
									$insercity['created_by'] = $this->user_id;
									$insercity['created_date'] = $this->date_time;
									$insercity['last_updated_by'] = $this->user_id;
									$insercity['last_updated_date'] = $this->date_time;
									
									if(!empty($city))
									{
										$this->db->insert('geo_cities', $insercity);
										$billing_city_id = $this->db->insert_id();
									}
								}							
								#City id end

								$qry = "select max(customer_site_id) as customer_site_id from cus_customer_sites";
								$getCustomerSite = $this->db->query($qry)->result_array();

								$customerSiteID = !empty($getCustomerSite[0]['customer_site_id']) ? $getCustomerSite[0]['customer_site_id'] : 10000000;

								$customer_site_id = $customerSiteID + 1;
								
								$customerSiteBillingData = array(
									"customer_id"    =>  $id,
									"site_name"      =>  trim($data[13]),
									"country_id"     =>  $billing_country_id,
									"state_id"       =>  $billing_state_id,
									"city_id"        =>  $billing_city_id,
									"address1"       =>  trim($data[17]),
									"address2"       =>  trim($data[18]),
									"address3"       =>  trim($data[19]),
									"postal_code"    =>  trim($data[20]),
									"mobile_number"  =>  trim($data[21]),
									"email_address"  =>  trim($data[22]),
									"contact_person" =>  trim($data[23]),
									"site_type"      =>  "BILL_TO",
									"customer_site_id"  =>  $customer_site_id,
								);

								$this->db->insert('cus_customer_sites', $customerSiteBillingData);
								$customer_site_billing_id = $this->db->insert_id();
								#Customer Site billing End

								#Customer Site Shipping start
								#Country id Start
								$shipping_country = isset($data[24]) ? ucfirst(trim(strtolower(RemoveSpecialChar($data[24])))) : NULL;
								$countryQry = "select country_id from geo_countries 
								where replace(country_name,' ','') = '".RemoveWhiteSpace($shipping_country)."'
								";
								$checkcountry = $this->db->query($countryQry)->result_array();
				
								if( count($checkcountry) > 0 )
								{
									$shipping_country_id = isset($checkcountry[0]["country_id"]) ? $checkcountry[0]["country_id"] : NULL;
								}
								else
								{
									$insercountry["country_name"] = $shipping_country; 
									$insercountry['active_flag'] = $this->active_flag;
									$insercountry['created_by'] = $this->user_id;
									$insercountry['created_date'] = $this->date_time;
									$insercountry['last_updated_by'] = $this->user_id;
									$insercountry['last_updated_date'] = $this->date_time;
									
									if(!empty($shipping_country))
									{
										$this->db->insert('geo_countries', $insercountry);
										$billing_country_id = $this->db->insert_id();
									}
								}
								#Country id end
				
								#State id Start								
								$shipping_state = isset($data[25]) ? ucfirst(trim(strtolower(RemoveSpecialChar($data[25])))) : NULL;
								$stateQry = "select state_id from geo_states 
								where replace(state_name,' ','') = '".RemoveWhiteSpace($shipping_state)."' ";
								$checkstate = $this->db->query($stateQry)->result_array();
				
								if( count($checkstate) > 0 )
								{
									$shipping_state_id = isset($checkstate[0]["state_id"]) ? $checkstate[0]["state_id"] : NULL;
								}
								else
								{
									$inserstate["state_name"] = $shipping_state;
									$inserstate["country_id"] = $shipping_country_id;
									$inserstate['active_flag'] = $this->active_flag;
									$inserstate['created_by'] = $this->user_id;
									$inserstate['created_date'] = $this->date_time;
									$inserstate['last_updated_by'] = $this->user_id;
									$inserstate['last_updated_date'] = $this->date_time;
				
									if(!empty($billing_state))
									{
										$this->db->insert('geo_states', $inserstate);
										$billing_state_id = $this->db->insert_id();
									}
								}
								#State id end
				
								#City id Start
								$shipping_city = isset($data[26]) ? ucfirst(trim(strtolower(RemoveSpecialChar($data[26])))) : NULL;
								$cityQry = "select city_id from geo_cities where 
								replace(city_name,' ','') = '".RemoveWhiteSpace($shipping_city)."'";
								$checkcity = $this->db->query($cityQry)->result_array();
				
								if( count($checkcity) > 0 )
								{
									$shipping_city_id = isset($checkcity[0]["city_id"]) ? $checkcity[0]["city_id"] : NULL;
								}
								else
								{
									$insercity["city_name"] = $shipping_city;
									$insercity["state_id"] = $shipping_state_id;
									$insercity["country_id"] = $shipping_country_id;
				
									$insercity['active_flag'] = $this->active_flag;
									$insercity['created_by'] = $this->user_id;
									$insercity['created_date'] = $this->date_time;
									$insercity['last_updated_by'] = $this->user_id;
									$insercity['last_updated_date'] = $this->date_time;
									
									if(!empty($city))
									{
										$this->db->insert('geo_cities', $insercity);
										$billing_city_id = $this->db->insert_id();
									}
								}							
								#City id end

								$customerSiteShippingData = array(
									"customer_id"  =>  $id,
									"site_name"    =>  trim($data[13]),
									"country_id"   =>  $shipping_country_id,
									"state_id"     =>  $shipping_state_id,
									"city_id"      =>  $shipping_city_id,
									"address1"     =>  trim($data[27]),
									"address2"     =>  trim($data[28]),
									"address3"     =>  trim($data[29]),
									"postal_code"  =>  trim($data[30]),

									"mobile_number"   =>  trim($data[31]),
									"email_address"   =>  trim($data[32]),
									"contact_person"  =>  trim($data[33]),
									"customer_site_id"  =>  $customer_site_id,
									"site_type"    =>  "SHIP_TO",
								);

								$this->db->insert('cus_customer_sites', $customerSiteShippingData);
								$customer_site_shipping_id = $this->db->insert_id();
								#Customer Site billing End
							}
							else
							{
								#Country id Start
								$country = isset($data[6]) ? ucfirst(trim(strtolower(RemoveSpecialChar($data[6])))) : NULL;
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
								$state = isset($data[7]) ? ucfirst(trim(strtolower(RemoveSpecialChar($data[7])))) : NULL;
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
								$city = isset($data[8]) ? ucfirst(trim(strtolower(RemoveSpecialChar($data[8])))) : NULL;
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
								$postData['customer_name'] = trim($data[1]);
								$postData['mobile_number'] = trim($data[2]);
								$postData['email_address'] = trim($data[3]);
								$postData['gst_number'] = trim($data[4]);
								$postData['contact_person'] = trim($data[5]);
								$postData['country_id'] = trim($country_id);
								$postData['state_id'] = trim($state_id);
								$postData['city_id'] = trim($city_id);
								$postData['address1'] = trim($data[9]);
								$postData['address2'] = trim($data[10]);
								$postData['address3'] = trim($data[11]);
								$postData['postal_code'] = trim($data[12]);
								
								$postData['last_updated_by'] = $this->user_id;
								$postData['last_updated_date'] = $this->date_time;
								
								$this->db->where('customer_name', trim($data[1]));
								$this->db->update('cus_customers', $postData);

								#Customer Site Start

								#Customer Site billing start
								#Country id Start
								$billing_country = isset($data[14]) ? ucfirst(trim(strtolower(RemoveSpecialChar($data[14])))) : NULL;
								$countryQry = "select country_id from geo_countries 
								where replace(country_name,' ','') = '".RemoveWhiteSpace($billing_country)."'
								";
								$checkcountry = $this->db->query($countryQry)->result_array();
				
								if( count($checkcountry) > 0 )
								{
									$billing_country_id = isset($checkcountry[0]["country_id"]) ? $checkcountry[0]["country_id"] : NULL;
								}
								else
								{
									$insercountry["country_name"] = $billing_country; 
									$insercountry['active_flag'] = $this->active_flag;
									$insercountry['created_by'] = $this->user_id;
									$insercountry['created_date'] = $this->date_time;
									$insercountry['last_updated_by'] = $this->user_id;
									$insercountry['last_updated_date'] = $this->date_time;
									
									if(!empty($billing_country))
									{
										$this->db->insert('geo_countries', $insercountry);
										$billing_country_id = $this->db->insert_id();
									}
								}
								#Country id end
				
								#State id Start								
								$billing_state = isset($data[15]) ? ucfirst(trim(strtolower(RemoveSpecialChar($data[15])))) : NULL;
								$stateQry = "select state_id from geo_states 
								where replace(state_name,' ','') = '".RemoveWhiteSpace($billing_state)."' ";
								$checkstate = $this->db->query($stateQry)->result_array();
				
								if( count($checkstate) > 0 )
								{
									$billing_state_id = isset($checkstate[0]["state_id"]) ? $checkstate[0]["state_id"] : NULL;
								}
								else
								{
									$inserstate["state_name"] = $billing_state;
									$inserstate["country_id"] = $billing_country_id;
									$inserstate['active_flag'] = $this->active_flag;
									$inserstate['created_by'] = $this->user_id;
									$inserstate['created_date'] = $this->date_time;
									$inserstate['last_updated_by'] = $this->user_id;
									$inserstate['last_updated_date'] = $this->date_time;
				
									if(!empty($billing_state))
									{
										$this->db->insert('geo_states', $inserstate);
										$billing_state_id = $this->db->insert_id();
									}
								}
								#State id end
				
								#City id Start
								$billing_city = isset($data[16]) ? ucfirst(trim(strtolower(RemoveSpecialChar($data[16])))) : NULL;
								$cityQry = "select city_id from geo_cities where replace(city_name,' ','') = '".RemoveWhiteSpace($billing_city)."'
								";
								$checkcity = $this->db->query($cityQry)->result_array();
				
								if( count($checkcity) > 0 )
								{
									$billing_city_id = isset($checkcity[0]["city_id"]) ? $checkcity[0]["city_id"] : NULL;
								}
								else
								{
									$insercity["city_name"] = $billing_city;
				
									$insercity["state_id"] = $billing_state_id;
									$insercity["country_id"] = $billing_country_id;
				
									$insercity['active_flag'] = $this->active_flag;
									$insercity['created_by'] = $this->user_id;
									$insercity['created_date'] = $this->date_time;
									$insercity['last_updated_by'] = $this->user_id;
									$insercity['last_updated_date'] = $this->date_time;
									
									if(!empty($city))
									{
										$this->db->insert('geo_cities', $insercity);
										$billing_city_id = $this->db->insert_id();
									}
								}							
								#City id end

								$customerSiteBillingData = array(
									#"customer_id"    =>  $id,
									#"site_name"     =>  trim($data[13]),
									"country_id"     =>  $billing_country_id,
									"state_id"       =>  $billing_state_id,
									"city_id"        =>  $billing_city_id,
									"address1"       =>  trim($data[17]),
									"address2"       =>  trim($data[18]),
									"address3"       =>  trim($data[19]),
									"postal_code"    =>  trim($data[20]),
									"mobile_number"  =>  trim($data[21]),
									"email_address"  =>  trim($data[22]),
									"contact_person" =>  trim($data[23]),
									#"site_type"     =>  "BILL_TO",
								);

								#$this->db->insert('cus_customer_sites', $customerSiteBillingData);
								#$customer_site_billing_id = $this->db->insert_id();

								$this->db->where('site_name',trim($data[13]));
								$this->db->where('site_type',"BILL_TO");
								$this->db->update('cus_customer_sites', $customerSiteBillingData);

								#Customer Site billing End

								#Customer Site Shipping start
								#Country id Start
								$shipping_country = isset($data[24]) ? ucfirst(trim(strtolower(RemoveSpecialChar($data[24])))) : NULL;
								$countryQry = "select country_id from geo_countries 
								where replace(country_name,' ','') = '".RemoveWhiteSpace($shipping_country)."'
								";
								$checkcountry = $this->db->query($countryQry)->result_array();
				
								if( count($checkcountry) > 0 )
								{
									$shipping_country_id = isset($checkcountry[0]["country_id"]) ? $checkcountry[0]["country_id"] : NULL;
								}
								else
								{
									$insercountry["country_name"] = $shipping_country; 
									$insercountry['active_flag'] = $this->active_flag;
									$insercountry['created_by'] = $this->user_id;
									$insercountry['created_date'] = $this->date_time;
									$insercountry['last_updated_by'] = $this->user_id;
									$insercountry['last_updated_date'] = $this->date_time;
									
									if(!empty($shipping_country))
									{
										$this->db->insert('geo_countries', $insercountry);
										$billing_country_id = $this->db->insert_id();
									}
								}
								#Country id end
				
								#State id Start								
								$shipping_state = isset($data[25]) ? ucfirst(trim(strtolower(RemoveSpecialChar($data[25])))) : NULL;
								$stateQry = "select state_id from geo_states 
								where replace(state_name,' ','') = '".RemoveWhiteSpace($shipping_state)."' ";
								$checkstate = $this->db->query($stateQry)->result_array();
				
								if( count($checkstate) > 0 )
								{
									$shipping_state_id = isset($checkstate[0]["state_id"]) ? $checkstate[0]["state_id"] : NULL;
								}
								else
								{
									$inserstate["state_name"] = $shipping_state;
									$inserstate["country_id"] = $shipping_country_id;
									$inserstate['active_flag'] = $this->active_flag;
									$inserstate['created_by'] = $this->user_id;
									$inserstate['created_date'] = $this->date_time;
									$inserstate['last_updated_by'] = $this->user_id;
									$inserstate['last_updated_date'] = $this->date_time;
				
									if(!empty($billing_state))
									{
										$this->db->insert('geo_states', $inserstate);
										$billing_state_id = $this->db->insert_id();
									}
								}
								#State id end
				
								#City id Start
								$shipping_city = isset($data[26]) ? ucfirst(trim(strtolower(RemoveSpecialChar($data[26])))) : NULL;
								$cityQry = "select city_id from geo_cities where 
								replace(city_name,' ','') = '".RemoveWhiteSpace($shipping_city)."'";
								$checkcity = $this->db->query($cityQry)->result_array();
				
								if( count($checkcity) > 0 )
								{
									$shipping_city_id = isset($checkcity[0]["city_id"]) ? $checkcity[0]["city_id"] : NULL;
								}
								else
								{
									$insercity["city_name"] = $shipping_city;
									$insercity["state_id"] = $shipping_state_id;
									$insercity["country_id"] = $shipping_country_id;
				
									$insercity['active_flag'] = $this->active_flag;
									$insercity['created_by'] = $this->user_id;
									$insercity['created_date'] = $this->date_time;
									$insercity['last_updated_by'] = $this->user_id;
									$insercity['last_updated_date'] = $this->date_time;
									
									if(!empty($city))
									{
										$this->db->insert('geo_cities', $insercity);
										$billing_city_id = $this->db->insert_id();
									}
								}							
								#City id end

								$customerSiteShippingData = array(
									#"customer_id"  =>  $id,
									"site_name"    =>  trim($data[13]),
									"country_id"   =>  $shipping_country_id,
									"state_id"     =>  $shipping_state_id,
									"city_id"      =>  $shipping_city_id,
									"address1"     =>  trim($data[27]),
									"address2"     =>  trim($data[28]),
									"address3"     =>  trim($data[29]),
									"postal_code"  =>  trim($data[30]),

									"mobile_number"   =>  trim($data[31]),
									"email_address"   =>  trim($data[32]),
									"contact_person"  =>  trim($data[33]),

									"site_type"    =>  "SHIP_TO",
								);

								#$this->db->insert('cus_customer_sites', $customerSiteShippingData);
								#$customer_site_shipping_id = $this->db->insert_id();

								$this->db->where('site_name',trim($data[13]));
								$this->db->where('site_type',"SHIP_TO");
								$this->db->update('cus_customer_sites', $customerSiteBillingData);
								#Customer Site billing End
							}
						}
						fclose($file); 
					}
					else
					{
						$this->session->set_flashdata('error_message' , "Customer imported error!");
						redirect(base_url() . 'customer/ManageCustomer', 'refresh');
					}
					$this->session->set_flashdata('flash_message' , "Customer imported successfully!");
					redirect(base_url() . 'customer/ManageCustomer', 'refresh');
				}
			break;
			
			default : #Manage
				$totalResult = $this->customer_model->getManageCustomer("","",$this->totalCount);
				$page_data["totalRows"] = $totalRows = count($totalResult);

				if(!empty($_SESSION['PAGE']))
				{$limit = $_SESSION['PAGE'];
				}else{$limit = 10;}
				
				$customer_id = isset($_GET['customer_id']) ? $_GET['customer_id'] :NULL;
				$customer_name = isset($_GET['customer_name']) ? $_GET['customer_name'] :NULL;
				$mobile_number = isset($_GET['mobile_number']) ? $_GET['mobile_number'] :NULL;
				$active_flag = isset($_GET['active_flag']) ? $_GET['active_flag'] :NULL;

				$this->redirectURL = 'customer/ManageCustomer?customer_id='.$customer_id.'&customer_name='.$customer_name.'&mobile_number='.$mobile_number.'&active_flag='.$active_flag.'';
				
				if ($customer_id != NULL || $customer_name != NULL || $mobile_number != NULL || $active_flag != NULL ) {
					$base_url = base_url().$this->redirectURL ;
				} else {
					$base_url = base_url().$this->redirectURL ;
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
				
				$page_data['resultData']  = $result = $this->customer_model->getManageCustomer($limit, $offset,$this->pageCount);
				
				#show start and ending Count
				if(isset($_GET['per_page']) && $_GET['per_page'] > 1 && count($result) == 0 )
				{
					redirect(base_url().$this->redirectURL , 'refresh');
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

	#Customer Sites
	function ManageCustomerSites($type = '', $id = '', $status = '', $status1 = '', $status2 = '')
    {
		if (empty($this->user_id))
		{
			redirect(base_url() . 'admin/adminLogin', 'refresh');
		}
	
		$page_data['type'] = $type;
		$page_data['id'] = $id;
		$page_data['status'] = $status;
		$page_data['status1'] = $status1;
		
		$page_data['ManageCustomer'] = 1;
		$page_data['customerSites'] = 1;
		
		$page_data['page_name']  = 'customer/ManageCustomerSites';
		$page_data['page_title'] = 'Customer Sites';
		
		switch(true)
		{
			case ($type == "add"): #add
				if($_POST)
				{

					$qry = "select max(customer_site_id) as customer_site_id from cus_customer_sites";
					$getCustomerSite = $this->db->query($qry)->result_array();

					$customerSiteID = !empty($getCustomerSite[0]['customer_site_id']) ? $getCustomerSite[0]['customer_site_id'] : 10000000;

					$customer_site_id = $customerSiteID + 1;
					
					$site_type = $_POST['site_type'];
					
					if(count($site_type) > 0)
					{
						foreach($site_type as $key => $value)
						{
							$data['customer_id '] = $this->input->post('customer_id');
							$data['site_name'] = trim($this->input->post('site_name'));
							$data['contact_person'] = $this->input->post('contact_person');
							$data['mobile_number'] = $this->input->post('mobile_number');
							$data['email_address'] = $this->input->post('email_address');

							$data['country_id'] = $this->input->post('country_id');
							
							$data['state_id'] = $this->input->post('state_id');
							$data['city_id'] = $this->input->post('city_id');
							$data['address1'] = trim($this->input->post('address1'));
							$data['address2'] = trim($this->input->post('address2'));
							$data['address3'] = trim($this->input->post('address3'));
							$data['postal_code'] = trim($this->input->post('postal_code'));
							$data['site_type'] = trim($value);
							$data['active_flag'] = $this->active_flag;
							$data['created_by'] = $this->user_id;
							$data['created_date'] = $this->date_time;
							$data['last_updated_by'] = $this->user_id;
							$data['last_updated_date'] = $this->date_time;
							
							$data['customer_site_id'] = $customer_site_id;
							
							#Audit Trails Add Start here
							$tableName = table_cus_customer_sites;
							$menuName = customer_sites;
							$description = "Customer site created successsfully!";
							auditTrails(array_filter($data),$tableName,$type,$menuName,"",$description);
							#Audit Trails Add end here
							
							$this->db->insert(' cus_customer_sites', $data);
							$id = $this->db->insert_id();
						}

						$this->session->set_flashdata('flash_message' , "Customer site created Successfully!");
						redirect(base_url() . 'customer/ManageCustomerSites', 'refresh');
					}
					else
					{
						$this->session->set_flashdata('flash_message' , "Customer site created Successfully!");
						redirect(base_url() . 'customer/ManageCustomerSites', 'refresh');
					}
				}
			break;
			
			case ($type == "edit" || $type == "view"): #Edit / View
				$qry = "select * from cus_customer_sites where customer_site_id='".$id."' and customer_id ='".$status."' ";
				$page_data['edit_data'] = $this->db->query($qry)->result_array();

				if($_POST)
				{
					$site_type = $_POST['site_type'];

					if(count($site_type) > 0)
					{
						foreach($site_type as $key => $value)
						{
							$customer_id = $this->input->post('customer_id');

							$chkQry = "select customer_site_id,site_type,active_flag from cus_customer_sites 
								where 
								customer_site_id='".$id."'
								and customer_id='".$status."'
								and site_type='".$value."'
							";
							$chkResult = $this->db->query($chkQry)->result_array();

							if( count($chkResult) > 0 )
							{
								/* $updateQry = "update cus_customer_sites
								SET active_flag = 'N'
								WHERE 1=1
								and customer_id='".$customer_id."'  
								and customer_site_id ='".$id."'
								and site_type !='".$value."'
								";
								$this->db->query($updateQry); */
								
								$data['site_name'] = trim($this->input->post('site_name'));
								$data['contact_person'] = $this->input->post('contact_person');
								$data['mobile_number'] = $this->input->post('mobile_number');
								$data['email_address'] = $this->input->post('email_address');

								$data['country_id'] = $this->input->post('country_id');
								$data['state_id'] = $this->input->post('state_id');
								$data['city_id'] = $this->input->post('city_id');
								$data['address1'] = trim($this->input->post('address1'));
								$data['address2'] = trim($this->input->post('address2'));
								$data['address3'] = trim($this->input->post('address3'));
								$data['postal_code'] = trim($this->input->post('postal_code'));
								//$data['active_flag'] = 'Y';
								#$data['site_type'] = trim($value);
								
								$data['last_updated_by'] = $this->user_id;
								$data['last_updated_date'] = $this->date_time;
								
								#Audit Trails Add Start here
								$tableName = table_cus_customer_sites;
								$menuName = customer_sites;
								$description = "Customer site updated successsfully!";
								auditTrails(array_filter($data),$tableName,$type,$menuName,$page_data['edit_data'],$description);
								#Audit Trails Edit end here
								
								$this->db->where('customer_id', $status);
								$this->db->where('customer_site_id', $id);
								$this->db->where('site_type', $value);
								
								$result = $this->db->update('cus_customer_sites', $data);
							}
							else
							{
								$data['customer_id '] = $this->input->post('customer_id');
								$data['site_name'] = trim($this->input->post('site_name'));
								$data['country_id'] = $this->input->post('country_id');
								$data['state_id'] = $this->input->post('state_id');
								$data['city_id'] = $this->input->post('city_id');
								$data['address1'] = trim($this->input->post('address1'));
								$data['address2'] = trim($this->input->post('address2'));
								$data['address3'] = trim($this->input->post('address3'));
								$data['postal_code'] = trim($this->input->post('postal_code'));
								$data['site_type'] = trim($value);
								$data['customer_site_id'] = $id;

								$data['active_flag'] = $this->active_flag;
								$data['created_by'] = $this->user_id;
								$data['created_date'] = $this->date_time;
								$data['last_updated_by'] = $this->user_id;
								$data['last_updated_date'] = $this->date_time;
								
								
								#Audit Trails Add Start here
								$tableName = table_cus_customer_sites;
								$menuName = customer_sites;
								$description = "Customer site created successsfully!";
								auditTrails(array_filter($data),$tableName,$type,$menuName,"",$description);
								#Audit Trails Add end here
								
								$this->db->insert('cus_customer_sites', $data);
								$insert_id = $this->db->insert_id();
							}
						}

						$this->session->set_flashdata('flash_message' , "Customer site updated Successfully!");
						redirect(base_url() . 'customer/ManageCustomerSites/edit/'.$id.'/'.$status, 'refresh');
					}
					else
					{
						$this->session->set_flashdata('flash_message' , "Customer site updated Successfully!");
						redirect(base_url() . 'customer/ManageCustomerSites/edit/'.$id.'/'.$status, 'refresh');
					}
				}
			break;
						
			case ($type == "status"): #add
				if($status2 == "Y")
				{
					$data['active_flag'] = "Y";
					$data['last_updated_by'] = $this->user_id;
					$data['last_updated_date'] = $this->date_time;
					$data['inactive_date'] = $this->date_time;
					$succ_msg = 'customer active successfully!';
				}
				else
				{
					$data['active_flag'] = "N";
					$data['last_updated_by'] = $this->user_id;
					$data['last_updated_date'] = $this->date_time;
					$data['inactive_date'] = NULL;
					$succ_msg = 'customer inactive successfully!';
				}

				$this->db->where('customer_site_account_id', $id);
				$this->db->where('customer_site_id', $status);
				$this->db->where('customer_id', $status1);

				#Audit Trails Start here
				$tableName = table_cus_customer_sites;
				$menuName = customer_sites;
				$id = $id;
				auditTrails($id,$tableName,$type,$menuName,"",$succ_msg);
				#Audit Trails end here

				$this->db->update(' cus_customer_sites', $data);
				$this->session->set_flashdata('flash_message' , $succ_msg);
				redirect($_SERVER["HTTP_REFERER"], 'refresh');
			break;

			/* case ($type == "import"): #Import
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
							upper(supplier_name)='".strtoupper(trim($data[2]))."' ";
							$getSupplier = $this->db->query($supplierQry)->result_array();
							$supplier_id = isset($getSupplier[0]["supplier_id"]) ? $getSupplier[0]["supplier_id"] : NULL;

							$chkExistQry = "select supplier_site_id from sup_supplier_sites where 
							site_name='".trim($data[3])."' 
							and supplier_id = '".$supplier_id."' ";
							$chkExist = $this->db->query($chkExistQry)->result_array();

							if( count($chkExist) == 0 ) #Create
							{ 

								$qry = "select max(customer_site_id) as customer_site_id from cus_customer_sites";
								$getCustomerSite = $this->db->query($qry)->result_array();
								$customerSiteID = !empty($getCustomerSite[0]['customer_site_id']) ? $getCustomerSite[0]['customer_site_id'] : 10000000;
								$customer_site_id = $customerSiteID + 1;


								#Country id Start
								$country = isset($data[7]) ? $data[7] :"";
								$countryQry = "select country_id from country where country_name = '".$country."' ";
								$checkcountry = $this->db->query($countryQry)->result_array();

								if( count($checkcountry) > 0 )
								{
									$country_id = isset($checkcountry[0]["country_id"]) ? $checkcountry[0]["country_id"] : 0;
								}
								else
								{
									$insercountry["country_name"] = $country; 
									$insercountry["country_status"] = 1;
									
									$this->db->insert('country', $insercountry);
									$country_id = $this->db->insert_id();
								}
								#Country id end

								#State id Start
								
								$state = isset($data[8]) ? $data[8] :"";
								$stateQry = "select state_id from state where state_name = '".$state."' ";
								$checkstate = $this->db->query($stateQry)->result_array();

								if( count($checkstate) > 0 )
								{
									$state_id = isset($checkstate[0]["state_id"]) ? $checkstate[0]["state_id"] : 0;
								}
								else
								{
									$inserstate["state_name"] = $state;
									$inserstate["country_id"] = $country_id;
									$inserstate["state_status"] = 1;
									$this->db->insert('state', $inserstate);
									$state_id = $this->db->insert_id();
								}
								#State id end

								#City id Start
								$city = isset($data[9]) ? $data[9] :"";
								$cityQry = "select city_id from city where city_name = '".$city."' ";
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
									$insercity["city_status"] = 1;
									
									$this->db->insert('city', $insercity);
									$city_id = $this->db->insert_id();
								}							
								#City id end

								$postData['supplier_id'] = $supplier_id;
								$postData['header_reference'] = trim($data[0]);
								$postData['line_reference'] = trim($data[1]);
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
								$postData['postal_code'] = trim($data[13]);
								$postData['gst_number'] = trim($data[14]);
								
								$postData['active_flag'] = $this->active_flag;
								$postData['created_by'] = $this->user_id;
								$postData['created_date'] = $this->date_time;
								$postData['last_updated_by'] = $this->user_id;
								$postData['last_updated_date'] = $this->date_time;
								$postData['customer_site_id'] = $customer_site_id;
							
								$this->db->insert('sup_supplier_sites', $postData);
								$id = $this->db->insert_id();
							}
							else
							{
								#Country id Start
								$country = isset($data[7]) ? $data[7] :"";
								$countryQry = "select country_id from country where country_name = '".$country."' ";
								$checkcountry = $this->db->query($countryQry)->result_array();

								if( count($checkcountry) > 0 )
								{
									$country_id = isset($checkcountry[0]["country_id"]) ? $checkcountry[0]["country_id"] : 0;
								}
								else
								{
									$insercountry["country_name"] = $country; 
									$insercountry["country_status"] = 1;
									
									$this->db->insert('country', $insercountry);
									$country_id = $this->db->insert_id();
								}
								#Country id end

								#State id Start
								$state = isset($data[8]) ? $data[8] :"";
								$stateQry = "select state_id from state where state_name = '".$state."' ";
								$checkstate = $this->db->query($stateQry)->result_array();

								if( count($checkstate) > 0 )
								{
									$state_id = isset($checkstate[0]["state_id"]) ? $checkstate[0]["state_id"] : 0;
								}
								else
								{
									$inserstate["state_name"] = $state;
									$inserstate["country_id"] = $country_id;
									$inserstate["state_status"] = 1;
									$this->db->insert('state', $inserstate);
									$state_id = $this->db->insert_id();
								}
								#State id end

								#City id Start
								$city = isset($data[9]) ? $data[9] :"";
								$cityQry = "select city_id from city where city_name = '".$city."' ";
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
									$insercity["city_status"] = 1;
									
									$this->db->insert('city', $insercity);
									$city_id = $this->db->insert_id();
								}							
								#City id end

								$postData['supplier_id'] = $supplier_id;
								$postData['header_reference'] = trim($data[0]);
								$postData['line_reference'] = trim($data[1]);
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
								$postData['postal_code'] = trim($data[13]);
								$postData['gst_number'] = trim($data[14]);
								
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
						$this->session->set_flashdata('error_message' , "Customer sites imported error!");
						redirect(base_url() . 'customer/ManageCustomerSites', 'refresh');
					}
					$this->session->set_flashdata('flash_message' , "Customer sites imported successfully!");
					redirect(base_url() . 'customer/ManageCustomerSites', 'refresh');
				}
			break; */
			
			default : #Manage
				$totalResult = $this->customer_model->getManageCustomerSites("","",$this->totalCount);
				$page_data["totalRows"] = $totalRows = count($totalResult);

				if(!empty($_SESSION['PAGE']))
				{$limit = $_SESSION['PAGE'];
				}else{$limit = 10;}

				$site_type = isset($_GET['site_type']) ? $_GET['site_type'] :NULL;
				$active_flag = isset($_GET['active_flag']) ? $_GET['active_flag'] :NULL;
				$redirectURL = 'customer/ManageCustomerSites?customer_id=&site_type='.$site_type.'&active_flag='.$active_flag;

				if (!empty($_GET['keywords']) || !empty($_GET['site_type'])  || !empty($_GET['active_flag']) ) {
					$base_url = base_url('customer/ManageCustomerSites?customer_id='.$_GET['customer_id'].'&site_type='.$_GET['site_type'].'&active_flag='.$_GET['active_flag']);
				} else {
					$base_url = base_url('customer/ManageCustomerSites?customer_id=&site_type=All&active_flag=Y');
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
				
				$page_data['resultData']  = $result= $data =$this->customer_model->getManageCustomerSites($limit, $offset,$this->pageCount);
				
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

	function ManageCustomerType($type = '', $id = '', $status = '')
    {
		if (empty($this->customer_id))
        {
			redirect(base_url() . 'admin/adminLogin', 'refresh');
		}
	
		$page_data['type'] = $type;
		$page_data['id'] = $id;
		
		$page_data['system_settings'] = 1;
		$page_data['page_name']  = 'customer/ManageCustomerType';
		$page_data['page_title'] = 'Manage Customer Type';
		
		switch($type)
		{
			case "add": #View
				if($_POST)
				{
					$data['customer_type_name'] = $this->input->post('customer_type_name');
					$data['customer_type_status'] = 1;
					
					$this->db->insert('customer_type', $data);
					$id = $this->db->insert_id();
					
					if($id !="")
					{	

						
						$this->session->set_flashdata('flash_message' , "Customer Type added Successfully!");
						redirect(base_url() . 'customer/ManageCustomerType', 'refresh');
					}
				}
			break;
			
			case "edit": #edit
				$page_data['edit_data'] = $this->db->get_where('customer_type', array('customer_type_id' => $id))
										->result_array();
				if($_POST)
				{
					$data['customer_type_name'] = $this->input->post('customer_type_name');
					
					$this->db->where('customer_type_id', $id);
					$result = $this->db->update('customer_type', $data);
					
					if($result)
					{
						$this->session->set_flashdata('flash_message' , "Customer Type updated Successfully!");
						redirect(base_url() . 'customer/ManageCustomerType', 'refresh');
					}
				}
			break;
			
			case "delete": #Delete
				$this->db->where('customer_type_id', $id);
				$this->db->delete('customer_type');
				
				$this->session->set_flashdata('flash_message' , "Customer Type deleted successfully!");
				redirect(base_url() . 'customer/ManageCustomerType', 'refresh');
			break;
			
			case "status": #Block & Unblock
				if($status == 1){
					$data['customer_type_status'] = 1;
					$succ_msg = 'Customer Type InActive successfully!';
				}else{
					$data['customer_type_status'] = 0;
					$succ_msg = 'Customer Type Active successfully!';
				}
				$this->db->where('customer_type_id', $id);
				$this->db->update('customer_type', $data);
				$this->session->set_flashdata('flash_message' , $succ_msg);
				redirect(base_url() . 'customer/ManageCustomerType', 'refresh');
			break;
			
			case "export":
			
				$data = $this->db->query("select * from customer_type order by customer_type_id desc")->result_array();
				
				#$data[] = array('f_name'=> "Nishit", 'l_name'=> "patel", 'mobile'=> "999999999", 'gender'=> "male");
				
				header("Content-type: application/csv");
				header("Content-Disposition: attachment; filename=\"CustomerType".".csv\"");
				header("Pragma: no-cache");
				header("Expires: 0");

				$handle = fopen('php://output', 'w');
				fputcsv($handle, array("S.No","Customer Type"));
				$cnt=1;
				foreach ($data as $row) 
				{
					$narray=array(
							$cnt,
							$row["customer_type_name"]
						);
					fputcsv($handle, $narray);
					$cnt++;
				}
				fclose($handle);
				exit;
			break;
			
			default : #Manage
				$page_data["totalRows"] = $totalRows = $this->customer_model->getManageCustomerTypeCount();#
	
				if(!empty($_SESSION['PAGE']))
				{$limit = $_SESSION['PAGE'];
				}else{$limit = 10;}
				
				if (!empty($_GET['keywords'])) {
					$base_url = base_url('customer/ManageCustomerType?keywords='.$_GET['keywords']);
				} else {
					$base_url = base_url('customer/ManageCustomerType?keywords=');
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
				
				$page_data['resultData']  = $result= $this->customer_model->getManageCustomerType($limit, $offset);
				
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

	function userAjaxSearch()
    {
		if(isset($_POST["query"]))  
		{  
			$select_type_condition = "cus_customers.register_type = 1"; #Customer
			
			$output = '';  
			
			$condition = '
				cus_customers.user_status=1 and
				'.$select_type_condition.' and
				(
					cus_customers.first_name like "%'.($_POST["query"]).'%" or 
					cus_customers.last_name like "%'.($_POST["query"]).'%" or 
					cus_customers.random_user_id like "%'.($_POST["query"]).'%" or
					cus_customers.mobile_number like "%'.($_POST["query"]).'%" or
					cus_customers.phone_number like "%'.($_POST["query"]).'%"
				)';

			$query = "select 
						cus_customers.random_user_id,
						cus_customers.first_name,
						cus_customers.customer_id,
						cus_customers.phone_number,
						cus_customers.mobile_number,
						cus_customers.email
						
						from cus_customers
						
					where ".$condition." ";
			
			$result = $this->db->query($query)->result_array();
			
			$output = '<ul class="list-unstyled-new">';  
			#$output .= '<li onclick="getuserId(0);">All</li>'; 
			if( count($result) > 0 )  
			{  
				foreach($result as $row)  
				{	
					$patinetID=  $row["customer_id"];
					$output .= '<li onclick="getuserId('.$patinetID.');">'.$row["random_user_id"].' - '.ucfirst($row["first_name"]).''.'</li>';  
				}  
			}  
			else  
			{  
				$output .= '<li onclick="getuserId(0);">Sorry! No data found.</li>';  
			}  
			$output .= '</ul>';  
			echo $output;
		}
	}

	function ajaxCustomerList() 
	{
		if(isset($_POST["query"]))  
		{  
			$output = '';  
			
			$customer_name = $_POST['query'];

			$result = $this->customer_model->getAjaxCustomerAll($customer_name);
			
			$output = '<ul class="list-unstyled-customer_id">';  
			
			if( count($result) > 0 )  
			{  
				foreach($result as $row)  
				{	
					$customer_name = $row["customer_name"];
					$customer_id = $row["customer_id"];
					$output .= '<a><li onclick="return getCustomerList(\'' .$customer_id. '\',\'' .$customer_name. '\');">'.$customer_name.'</li></a>';  
				}  
			}  
			else  
			{  
				$customer_name = "";
				$customer_id = "";
				
				$output .= '<li onclick="return getCustomerList(\'' .$customer_id. '\',\'' .$customer_name. '\');">Sorry! Customer Not Found.</li>';  
			}
			$output .= '</ul>';  
			echo $output;  
		}
	}

}