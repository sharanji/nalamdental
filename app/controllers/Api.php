<?php
defined('BASEPATH') OR exit('No direct script access allowed');
include_once('vendor/autoload.php');
class Api extends CI_Controller 
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
	
	public function oauthDetails()
	{
		$authQuery = "select * from api_authorization where default_auth=1";
		$getAuthDetails = $this->db->query($authQuery)->result_array();

		$response[] = array(	
			"httpCode" 		=> 200,
			"auth_name" 	=> $getAuthDetails[0]["auth_name"],
			"user_name" 	=> $getAuthDetails[0]["auth_user_name"],
			"password" 	    => $getAuthDetails[0]["auth_original_password"],
			"status"        => (int) 1,
			"message" 		=> "authorization Details"
		);
		
		header("Content-Type: application/json");
		echo json_encode($response);
		exit;
	}

	#Signup
	function signUp()
	{
		$authUserName = isset($_SERVER['PHP_AUTH_USER']) ? $_SERVER['PHP_AUTH_USER'] :"";
		$authPassword = isset($_SERVER['PHP_AUTH_PW']) ? $_SERVER['PHP_AUTH_PW'] :"";
		$checkAuth = checkAuthorization($authUserName,$authPassword);
		
		if ($checkAuth == 0) #Check Authorization
		{
			$response = array("httpCode" => 401,"message" => "Unauthorized");
			header('HTTP/1.0 401 Unauthorized'); 
			header("Content-Type: application/json");
			echo json_encode($response);
			exit; 
		}
		else
		{
			$json = @file_get_contents('php://input');
			$jsondata = @json_decode($json);
			
			if( !empty( $jsondata->mobile_number ) )
			{
				$postData['customer_name'] = $jsondata->customer_name;
				$postData['country_id'] = $jsondata->country_id;
				$mobile_number = $postData['mobile_number'] = $jsondata->mobile_number;

				$checkInterFaceQry1 = "select interface_id from cus_customers_interface as customer 
				where 
					customer.otp_status ='N'
					AND customer.mobile_number='".$postData['mobile_number']."' ";
				$chkMobileInterFaceExist1 = $this->db->query($checkInterFaceQry1)->result_array();

				if(count($chkMobileInterFaceExist1) > 0)
				{
					$this->db->where('mobile_number', $postData['mobile_number']);
					$this->db->delete('cus_customers_interface');
				}
					
				#mobile_number
				$checkInterFaceQry = "select interface_id from cus_customers_interface as customer 
					where customer.country_id ='".$postData['country_id']."'
					and	customer.mobile_number ='".$postData['mobile_number']."'
				";

				$chkMobileInterFaceExist = $this->db->query($checkInterFaceQry)->result_array();

				$checkQry = "select customer_id,deleted_flag from cus_consumers as customer 
					where 1=1
					and	customer.mobile_number ='".$postData['mobile_number']."'";

					//customer.country_id ='".$postData['country_id']."'

				$chkMobileExist = $this->db->query($checkQry)->result_array();
	
				if(count($chkMobileInterFaceExist) > 0)
				{
					$response = array("httpCode" => 409 , "message" => "Mobile Number already exist!");
					header('HTTP/1.1 409', TRUE, 409);
					header("Content-Type: application/json");
					echo json_encode($response);
					exit;
				}
				else if( count($chkMobileExist) > 0 )
				{
					$deletedFlag = isset($chkMobileExist[0]["deleted_flag"]) ? $chkMobileExist[0]["deleted_flag"] : NULL;
					$customer_id = isset($chkMobileExist[0]["customer_id"]) ? $chkMobileExist[0]["customer_id"] : NULL;

					if( $deletedFlag == 'Y' ) #IF YES => UPDATE DELETED FLAG (N)
					{
						$postData['active_flag'] = $this->active_flag;
						$postData['created_by'] = '-1';
						$postData['created_date'] = $this->date_time;
						$postData['last_updated_by'] = '-1';
						$postData['last_updated_date'] = $this->date_time;
						$postData['otp_number'] = $otp_number = otpNumber(4);
						
						$this->db->insert('cus_customers_interface', $postData);
						$id = $this->db->insert_id();
						
						if($id !="")
						{
							$otpMobileNumber = trim($postData['mobile_number']);
							$otpMessage = $otp_number.' is your Login OTP. - Thank You.  '.strtoupper(SITE_NAME);
							
							$sendSMS = sendSMS($otpMobileNumber,$otpMessage);

							$response = array(	
								"httpCode" 		=> 200,
								"status"        => (int) 1,
								"message" 		=> "OTP sent successfully!"
							);
							
							header("Content-Type: application/json");
							echo json_encode($response);
							exit;
						}

						/* $postData = array(
							"deleted_flag"       => 'N',
							"deleted_by"         => NULL,
							"deleted_date"       => NULL
						);
						
						$this->db->where('customer_id', $customer_id);
						$result = $this->db->update('cus_customers', $postData);

						#Per User Update
						$userQry = "select user_id,reference_id as customer_id from per_user where reference_id ='".$customer_id."' ";
						$getCustomer = $this->db->query($userQry)->result_array();

						$user_id = isset($getCustomer[0]["user_id"]) ? $getCustomer[0]["user_id"] : NULL;

						$postUserData = array(
							"active_flag"       => 'Y',
							"last_updated_by"   => $user_id,
							"last_updated_date" => $this->date_time,
						);
						
						$this->db->where('user_id', $user_id);
						$user_result = $this->db->update('per_user', $postUserData);
						#Per User Update

						$query = "select 
						cus_customers.customer_id,
						cus_customers.customer_name,
						cus_customers.mobile_number,
						cus_customers.email_address,
						cus_customers.address1,
						cus_customers.address2,
						cus_customers.address3,
						cus_customers.postal_code,
						geo_countries.country_code,
						geo_countries.country_name,
						geo_states.state_name,
						geo_cities.city_name,
						per_user.user_id

						from cus_customers 

						left join per_user on per_user.reference_id = cus_customers.customer_id

						left join geo_countries on geo_countries.country_id = cus_customers.country_id
						left join geo_states on geo_states.state_id = cus_customers.state_id
						left join geo_cities on geo_cities.city_id = cus_customers.city_id
						where mobile_number='".$mobile_number."' ";
						$resultData = $this->db->query($query)->result_array();

						$user_id = isset($resultData[0]['user_id']) ? $resultData[0]['user_id'] : NULL;

						if (file_exists('uploads/profile_image/'.$user_id.'.png'))
						{
							$profileImgUrl = base_url()."uploads/profile_image/".$user_id.'.png' ;			
						}
						else
						{
							$profileImgUrl = base_url().'uploads/no-image.png';
						}
						
						$response = array(
							"httpCode" 		=> 200,
							"status"        => (int) 1,
							"customerId"	=> (int) $user_id,
							"customerName"	=> $resultData[0]['customer_name'],
							"mobileNumber"	=> $resultData[0]['country_code']."-".$resultData[0]['mobile_number'],
							"email"			=> $resultData[0]['email_address'],	
							"countryName"	=> $resultData[0]['country_name'],						
							"stateName"	    => $resultData[0]['state_name'],						
							"cityName"	    => $resultData[0]['city_name'],						
							"address1"	    => $resultData[0]['address1'],						
							"address2"	    => $resultData[0]['address2'],						
							"address3"	    => $resultData[0]['address3'],						
							"postalCode"	=> $resultData[0]['postal_code'],	
							"profileImg"	=> $profileImgUrl,
							"message" 		=> "Account activated successfully!"
						);
						
						header("Content-Type: application/json");
						echo json_encode($response);
						exit; */
					}
					else
					{
						$response = array("httpCode" => 409 , "message" => "Mobile Number already exist!");
						header('HTTP/1.1 409', TRUE, 409);
						header("Content-Type: application/json");
						echo json_encode($response);
						exit;
					}	
				}
				else
				{
					$postData['active_flag'] = $this->active_flag;
					$postData['created_by'] = '-1';
					$postData['created_date'] = $this->date_time;
					$postData['last_updated_by'] = '-1';
					$postData['last_updated_date'] = $this->date_time;
					$postData['otp_number'] = $otp_number = otpNumber(4);
					
					$this->db->insert('cus_customers_interface', $postData);
					$id = $this->db->insert_id();
					
					if($id !="")
					{
						$otpMobileNumber = trim($postData['mobile_number']);
						$otpMessage = $otp_number.' is your Login OTP. - Thank You.  '.strtoupper(SITE_NAME);
						
						$sendSMS = sendSMS($otpMobileNumber,$otpMessage);

						$response = array(	
							"httpCode" 		=> 200,
							"status"        => (int) 1,
							"message" 		=> "OTP sent successfully!"
						);
						
						header("Content-Type: application/json");
						echo json_encode($response);
						exit;
					}
				}
			}
			else
			{
				$response = array("httpCode" => 400 ,"status"  => (int) 2, "Message" => "Bad Request" );
				header('HTTP/1.1 400', TRUE, 400);
				header("Content-Type: application/json");
				echo json_encode($response);
				exit;
			}
			exit;
		}
	}

	function signUpVerifyOtp()
	{
		$authUserName = isset($_SERVER['PHP_AUTH_USER']) ? $_SERVER['PHP_AUTH_USER'] :"";
		$authPassword = isset($_SERVER['PHP_AUTH_PW']) ? $_SERVER['PHP_AUTH_PW'] :"";
		$checkAuth = checkAuthorization($authUserName,$authPassword);
		
		if ($checkAuth == 0) #Check Authorization
		{
			$response = array("httpCode" => 401,"message" => "Unauthorized");
			header('HTTP/1.0 401 Unauthorized'); 
			header("Content-Type: application/json");
			echo json_encode($response);
			exit; 
		}
		else
		{
			$json = @file_get_contents('php://input');
			$jsondata = @json_decode($json);
			
			if( !empty( $jsondata->mobile_number ) )
			{
				$mobile_number = $postData['mobile_number'] = $jsondata->mobile_number;
				$postData['otp_number'] = $jsondata->otp_number;
					
				#mobile_number
				$checkInterFaceQry = "select interface_id,otp_status,customer_name,mobile_number,country_id from 
				cus_customers_interface as customer
					where 
						customer.mobile_number ='".$postData['mobile_number']."'
						and customer.otp_number ='".$postData['otp_number']."' ";

				$chkOTP = $this->db->query($checkInterFaceQry)->result_array();
				
			
				if( count($chkOTP) == 0 )
				{
					$response = array("httpCode" => 409 , "message" => "OTP not found!");
					header('HTTP/1.1 409', TRUE, 409);
					header("Content-Type: application/json");
					echo json_encode($response);
					exit;
				}
				else
				{
					$interface_id = isset($chkOTP[0]["interface_id"]) ? $chkOTP[0]["interface_id"] : NULL;
					$otp_status = isset($chkOTP[0]["otp_status"]) ? $chkOTP[0]["otp_status"] : NULL;
					$customer_name = isset($chkOTP[0]["customer_name"]) ? $chkOTP[0]["customer_name"] : NULL;
					$mobile_number = isset($chkOTP[0]["mobile_number"]) ? $chkOTP[0]["mobile_number"] : NULL;
					$country_id = isset($chkOTP[0]["country_id"]) ? $chkOTP[0]["country_id"] : 1;

					if($interface_id != NULL)
					{
						if($otp_status == 'N') #OTP Status not verified
						{
							$postData['otp_status'] = 'Y';
							$this->db->where('interface_id', $interface_id);
							$result = $this->db->update('cus_customers_interface', $postData);
							
							if($result)
							{
								#Delete Interface Table start 
								$this->db->where('interface_id', $interface_id);
								$this->db->delete('cus_customers_interface');
								#Delete Interface Table end

								$checkCustomerQry = "select customer_id from cus_consumers 
									where 
									mobile_number ='".$mobile_number."'";
								$checkCustomer = $this->db->query($checkCustomerQry)->result_array();
								
								if(count($checkCustomer) > 0)
								{
									$customer_id = isset($checkCustomer[0]["customer_id"]) ? $checkCustomer[0]["customer_id"] : NULL;
									
									$postData = array(
										"deleted_flag"       => 'N',
										"deleted_by"         => NULL,
										"deleted_date"       => NULL
									);
									
									$this->db->where('customer_id', $customer_id);
									$result = $this->db->update('cus_consumers', $postData);

									#Per User Update
									$userQry = "select user_id,reference_id as customer_id from per_user where reference_id ='".$customer_id."' ";
									$getCustomer = $this->db->query($userQry)->result_array();

									$user_id = isset($getCustomer[0]["user_id"]) ? $getCustomer[0]["user_id"] : NULL;

									$postUserData = array(
										"active_flag"       => 'Y',
										"last_updated_by"   => $user_id,
										"last_updated_date" => $this->date_time,
									);
									
									$this->db->where('user_id', $user_id);
									$user_result = $this->db->update('per_user', $postUserData);
									#Per User Update

									$query = "select 
									cus_customers.customer_id,
									cus_customers.customer_name,
									cus_customers.mobile_number,
									cus_customers.email_address,
									cus_customers.address1,
									cus_customers.address2,
									cus_customers.address3,
									cus_customers.postal_code,
									geo_countries.country_code,
									geo_countries.country_name,
									geo_states.state_name,
									geo_cities.city_name,
									per_user.user_id

									from cus_consumers as cus_customers 

									left join per_user on per_user.reference_id = cus_customers.customer_id

									left join geo_countries on geo_countries.country_id = cus_customers.country_id
									left join geo_states on geo_states.state_id = cus_customers.state_id
									left join geo_cities on geo_cities.city_id = cus_customers.city_id
									where mobile_number='".$mobile_number."' ";
									$resultData = $this->db->query($query)->result_array();

									$user_id = isset($resultData[0]['user_id']) ? $resultData[0]['user_id'] : NULL;

									if (file_exists('uploads/profile_image/'.$user_id.'.png'))
									{
										$profileImgUrl = base_url()."uploads/profile_image/".$user_id.'.png' ;			
									}
									else
									{
										$profileImgUrl = base_url().'uploads/no-image.png';
									}

									#Login Audit Records start here
									$userId = $user_id;

									$data = array(
										'user_id'       	=> $userId,
										'created_by'    	=> $userId,
										'created_date'  	=> $this->date_time,
										'last_updated_by'   => $userId,
										'last_updated_date' => $this->date_time,
										'login_type' 		=> "EXTERNAL-USER",
										'branch_id' 		=> NULL
									);
					
									$this->db->insert('org_login_audits', $data);
									$id = $this->db->insert_id();
									#Login Audit Records end here

									$response = array(
										"httpCode" 		=> 200,
										"status"        => (int) 1,
										"customerId"	=> (int) $user_id,
										"customerName"	=> $resultData[0]['customer_name'],
										"mobileNumber"	=> $resultData[0]['country_code']."-".$resultData[0]['mobile_number'],
										"email"			=> $resultData[0]['email_address'],	
										"countryName"	=> $resultData[0]['country_name'],						
										"stateName"	    => $resultData[0]['state_name'],						
										"cityName"	    => $resultData[0]['city_name'],						
										"address1"	    => $resultData[0]['address1'],						
										"address2"	    => $resultData[0]['address2'],						
										"address3"	    => $resultData[0]['address3'],						
										"postalCode"	=> $resultData[0]['postal_code'],	
										"profileImg"	=> $profileImgUrl,
										"message" 		=> "Account activated successfully!"
									);

									
									
									header("Content-Type: application/json");
									echo json_encode($response);
									exit; 
								}
								else
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
									#Document No End here
									
									$baseTableData = array(
										"customer_name"       => $customer_name,
										"country_id"          => $country_id,
										"mobile_number"       => $mobile_number,
										"active_flag"         => $this->active_flag,
										"created_date"        => $this->date_time,
										"created_by"          => '-1',
										"last_updated_date"   => $this->date_time,
										"last_updated_by"     => '-1',
										"mobile_num_verified" => 'Y',
										"reference_header_id" => $interface_id,
										"customer_number"     => $documentNumber,
									);

									$this->db->insert('cus_consumers', $baseTableData);
									$customer_id = $id = $this->db->insert_id();

									if($id)
									{
										#Per Users start
										$userData = array(
											"reference_id"        => $customer_id,
											"user_name" 	      => $mobile_number,
											"active_flag" 	      => $this->active_flag,
											"created_date"        => $this->date_time,
											"created_by"          => '-1',
											"last_updated_date"   => $this->date_time,
											"last_updated_by"     => '-1',
											"attribute2"          => 'Y', #Mobile Number Verified	
										);

										$this->db->insert('per_user', $userData);
										$user_id = $this->db->insert_id();
										#Per Users end

										#Update Next Val DOC Number tbl start
										$nextValue = $startingNumber + 1;
										$doc_num_id = isset($getDocumentData[0]['doc_num_id']) ? $getDocumentData[0]['doc_num_id']:"";
										
										$UpdateData['next_number'] = $nextValue;
										$this->db->where('doc_num_id', $doc_num_id);
										$resultUpdateData = $this->db->update('doc_document_numbering', $UpdateData);
										#Update Next Val DOC Number tbl end

										#Delete Interface Table start 
										$this->db->where('interface_id', $interface_id);
										$this->db->delete('cus_customers_interface');
										#Delete Interface Table end


										$query = "select 
										cus_customers.customer_id,
										cus_customers.customer_name,
										cus_customers.mobile_number,
										cus_customers.email_address,
										cus_customers.address1,
										cus_customers.address2,
										cus_customers.address3,
										cus_customers.postal_code,
										geo_countries.country_code,
										geo_countries.country_name,
										geo_states.state_name,
										geo_cities.city_name,
										per_user.user_id

										from cus_consumers as cus_customers 

										left join per_user on per_user.reference_id = cus_customers.customer_id

										left join geo_countries on geo_countries.country_id = cus_customers.country_id
										left join geo_states on geo_states.state_id = cus_customers.state_id
										left join geo_cities on geo_cities.city_id = cus_customers.city_id
										where mobile_number='".$mobile_number."' ";
										$resultData = $this->db->query($query)->result_array();

										$user_id = isset($resultData[0]['user_id']) ? $resultData[0]['user_id'] : NULL;

										if (file_exists('uploads/profile_image/'.$user_id.'.png'))
										{
											$profileImgUrl = base_url()."uploads/profile_image/".$user_id.'.png' ;			
										}
										else
										{
											$profileImgUrl = base_url().'uploads/no-image.png';
										}

										#Login Audit Records start here
										$userId = $user_id;

										$data = array(
											'user_id'       	=> $userId,
											'created_by'    	=> $userId,
											'created_date'  	=> $this->date_time,
											'last_updated_by'   => $userId,
											'last_updated_date' => $this->date_time,
											'login_type' 		=> "EXTERNAL-USER",
											'branch_id' 		=> NULL
										);
						
										$this->db->insert('org_login_audits', $data);
										$id = $this->db->insert_id();
										#Login Audit Records end here
										
										$response = array(	
											"httpCode" 		=> 200,
											"status"        => (int) 1,
											"customerId"	=> (int) $user_id,
											"customerName"	=> $resultData[0]['customer_name'],
											"mobileNumber"	=> $resultData[0]['country_code']."-".$resultData[0]['mobile_number'],
											"email"			=> $resultData[0]['email_address'],	
											"countryName"	=> $resultData[0]['country_name'],						
											"stateName"	    => $resultData[0]['state_name'],						
											"cityName"	    => $resultData[0]['city_name'],						
											"address1"	    => $resultData[0]['address1'],						
											"address2"	    => $resultData[0]['address2'],						
											"address3"	    => $resultData[0]['address3'],						
											"postalCode"	=> $resultData[0]['postal_code'],	
											"profileImg"	=> $profileImgUrl,
											"message" 		=> "OTP verified successfully!"
										);



										header("Content-Type: application/json");
										echo json_encode($response);
										exit;
									}
								}
							}
						}
						else if($otp_status == 'Y') #OTP is verified
						{
							$response = array(	
								"httpCode" 		=> 200,
								"status"        => (int) 2,
								"message" 		=> "OTP already verified!"
							);
							
							header("Content-Type: application/json");
							echo json_encode($response);
							exit;
						}
					}
				}
			}
			else
			{
				$response = array("httpCode" => 400 ,"status"  => (int) 2, "Message" => "Bad Request" );
				header('HTTP/1.1 400', TRUE, 400);
				header("Content-Type: application/json");
				echo json_encode($response);
				exit;
			}
			exit;
		}
	}
	
	#Login
	public function login()
	{
		$authUserName = isset($_SERVER['PHP_AUTH_USER']) ? $_SERVER['PHP_AUTH_USER'] :"";
		$authPassword = isset($_SERVER['PHP_AUTH_PW']) ? $_SERVER['PHP_AUTH_PW'] :"";
		$checkAuth = checkAuthorization($authUserName,$authPassword);
		
		if ( $checkAuth == 0 ) #Check Authorization
		{
			$response = array("httpCode" => 401,"message" => "Unauthorized");
			header('HTTP/1.0 401 Unauthorized'); 
			header("Content-Type: application/json");
			echo json_encode($response);
			exit; 
		}
		else
		{
			$json = @file_get_contents('php://input');
			$jsondata = @json_decode($json);
			
			if( !empty( $jsondata->mobile_number ) ) #Mobile Login
			{
				$country_id = $jsondata->country_id;
				$mobile_number = trim($jsondata->mobile_number);
				
				$chkCustomerQry = "select 
					customer.customer_id,
					customer.deleted_flag,
					per_user.active_flag

					from cus_consumers as customer
					left join per_user on 
						per_user.reference_id = customer.customer_id
					where 
						per_user.user_name ='".$mobile_number."'";

					/* customer.country_id ='".$country_id."'
					and customer.mobile_number ='".$mobile_number."'
					and */

				$chkCustomer = $this->db->query($chkCustomerQry)->result_array();

				if( count($chkCustomer) == 1 )
				{
					$this->db->where('mobile_number', $mobile_number);
					$this->db->delete('cus_customers_otp_validate');

					$deletedFlag = isset($chkCustomer[0]["deleted_flag"]) ? $chkCustomer[0]["deleted_flag"] : NULL;
					$activeFlag = isset($chkCustomer[0]["active_flag"]) ? $chkCustomer[0]["active_flag"] : NULL;

					if($deletedFlag == 'N' && $activeFlag == 'Y')
					{
						$otp_number = otpNumber(4);

						$customer_id = isset($chkCustomer[0]["customer_id"]) ? $chkCustomer[0]["customer_id"] : NULL;
						$otpMessage = $otp_number.' is your Login OTP. - Thank You.  '.strtoupper(SITE_NAME);
						
						$sendSMS = sendSMS($mobile_number,$otpMessage);

						$baseTableData = array(
							"customer_id"         => $customer_id,
							"mobile_number"       => $mobile_number,
							"otp_number"          => $otp_number,
							"otp_verified_status" => 'N',
							"created_date"        => $this->date_time,
							"created_by"          => '-1',
							"last_updated_date"   => $this->date_time,
							"last_updated_by"     => '-1',
						);

						$this->db->insert('cus_customers_otp_validate', $baseTableData);
						$id = $this->db->insert_id();

						$response = array(	
							"httpCode" 		=> 200,
							"status"        => (int) 1,
							"message" 		=> "OTP sent successfully!"
						);
						
						header("Content-Type: application/json");
						echo json_encode($response);
						exit;
					}
					else
					{
						$response = array(	
							"httpCode" 		=> 200,
							"status"        => (int) 2,
							"message" 		=> "Your account has been deleted!"
						);
						
						header("Content-Type: application/json");
						echo json_encode($response);
						exit;
					}
				}
				else
				{
					$response = array(	
						"httpCode" 		=> 200,
						"status"        => (int) 2,
						"message" 		=> "Customer not registered!"
					);
					
					header("Content-Type: application/json");
					echo json_encode($response);
					exit;
				}
			}
			else
			{
				$response = array("httpCode" => 400 , "status"  => (int) 6, "Message" => "Bad Request" );
				header('HTTP/1.1 400', TRUE, 400);
				header("Content-Type: application/json");
				echo json_encode($response);
				exit;
			}
			exit;
		}
	}

	#Login Verify Otp
	function loginVerifyOtp()
	{
		$authUserName = isset($_SERVER['PHP_AUTH_USER']) ? $_SERVER['PHP_AUTH_USER'] :"";
		$authPassword = isset($_SERVER['PHP_AUTH_PW']) ? $_SERVER['PHP_AUTH_PW'] :"";
		$checkAuth = checkAuthorization($authUserName,$authPassword);
		
		if ($checkAuth == 0) #Check Authorization
		{
			$response = array("httpCode" => 401,"message" => "Unauthorized");
			header('HTTP/1.0 401 Unauthorized'); 
			header("Content-Type: application/json");
			echo json_encode($response);
			exit; 
		}
		else
		{
			$json = @file_get_contents('php://input');
			$jsondata = @json_decode($json);
			
			if( !empty( $jsondata->mobile_number ) )
			{
				$mobile_number = $jsondata->mobile_number;
				$otp_number = $jsondata->otp_number;

				$checkOTPQry = "select otp_validate_id,otp_number from cus_customers_otp_validate where 
				mobile_number = '".$mobile_number."' 
				and (otp_number = '".$otp_number."' || default_otp_number = '".$otp_number."' ) 
				and otp_verified_status = 'N' ";
				$checkOTP = $this->db->query($checkOTPQry)->result_array();

				if(count($checkOTP) > 0)
				{
					$this->db->where('mobile_number', $mobile_number);
					$this->db->delete('cus_customers_otp_validate');

					$query = "select 
					cus_customers.customer_id,
					cus_customers.customer_name,
					cus_customers.mobile_number,
					cus_customers.email_address,
					cus_customers.address1,
					cus_customers.address2,
					cus_customers.address3,
					cus_customers.postal_code,
					geo_countries.country_code,
					geo_countries.country_name,
					geo_states.state_name,
					geo_cities.city_name,
					per_user.user_id

					from cus_consumers as cus_customers 

					left join per_user on per_user.reference_id = cus_customers.customer_id

					left join geo_countries on geo_countries.country_id = cus_customers.country_id
					left join geo_states on geo_states.state_id = cus_customers.state_id
					left join geo_cities on geo_cities.city_id = cus_customers.city_id
					where mobile_number='".$mobile_number."' ";
					$resultData = $this->db->query($query)->result_array();

					$user_id = isset($resultData[0]['user_id']) ? $resultData[0]['user_id'] : NULL;

					if (file_exists('uploads/profile_image/'.$user_id.'.png'))
					{
						$profileImgUrl = base_url()."uploads/profile_image/".$user_id.'.png' ;			
					}
					else
					{
						$profileImgUrl = base_url().'uploads/no-image.png';
					}

					#Login Audit Records start here
					$userId = $user_id;

					$data = array(
						'user_id'       	=> $userId,
						'created_by'    	=> $userId,
						'created_date'  	=> $this->date_time,
						'last_updated_by'   => $userId,
						'last_updated_date' => $this->date_time,
						'login_type' 		=> "EXTERNAL-USER",
						'branch_id' 		=> NULL
					);
	
					$this->db->insert('org_login_audits', $data);
					$id = $this->db->insert_id();
					#Login Audit Records end here
					
					$response = array(	
						"httpCode" 		=> 200,
						"status"        => (int) 1,
						"customerId"	=> (int) $user_id,
						"customerName"	=> $resultData[0]['customer_name'],
						"mobileNumber"	=> $resultData[0]['country_code']."-".$resultData[0]['mobile_number'],
						"email"			=> $resultData[0]['email_address'],	
						"countryName"	=> $resultData[0]['country_name'],						
						"stateName"	    => $resultData[0]['state_name'],						
						"cityName"	    => $resultData[0]['city_name'],						
						"address1"	    => $resultData[0]['address1'],						
						"address2"	    => $resultData[0]['address2'],						
						"address3"	    => $resultData[0]['address3'],						
						"postalCode"	=> $resultData[0]['postal_code'],	
						"profileImg"	=> $profileImgUrl,
						"message" 		=> "OTP verified successfully!"
					);

					header("Content-Type: application/json");
					echo json_encode($response);
					exit;
				}
				else
				{
					$response = array("httpCode" => 409 , "message" => "Invalid OTP!");
					header('HTTP/1.1 409', TRUE, 409);
					header("Content-Type: application/json");
					echo json_encode($response);
					exit;
				}
			}
			else
			{
				$response = array("httpCode" => 400 ,"status"  => (int) 2, "Message" => "Bad Request" );
				header('HTTP/1.1 400', TRUE, 400);
				header("Content-Type: application/json");
				echo json_encode($response);
				exit;
			}
			exit;
		}
	}
	
	#My Profile
	public function myProfile($user_id="")
	{
		$authUserName = isset($_SERVER['PHP_AUTH_USER']) ? $_SERVER['PHP_AUTH_USER'] :"";
		$authPassword = isset($_SERVER['PHP_AUTH_PW']) ? $_SERVER['PHP_AUTH_PW'] :"";
		$checkAuth = checkAuthorization($authUserName,$authPassword);
		
		if ($checkAuth == 0) #Check Authorization
		{
			$response = array("httpCode" => 401,"message" => "Unauthorized");
			header('HTTP/1.0 401 Unauthorized'); 
			header("Content-Type: application/json");
			echo json_encode($response);
			exit; 
		}
		else
		{
			if(!empty($user_id))
			{
				$query = "select 
					cus_customers.customer_id,
					cus_customers.customer_name,
					cus_customers.mobile_number,
					cus_customers.email_address,
					cus_customers.address1,
					cus_customers.address2,
					cus_customers.address3,
					cus_customers.postal_code,
					cus_customers.land_mark,
					geo_countries.country_code,
					geo_countries.country_name,
					geo_states.state_name,
					geo_cities.city_name,
					per_user.user_id

					from cus_consumers as cus_customers
				left join per_user on per_user.reference_id = cus_customers.customer_id

				left join geo_countries on geo_countries.country_id = cus_customers.country_id
				left join geo_states on geo_states.state_id = cus_customers.state_id
				left join geo_cities on geo_cities.city_id = cus_customers.city_id
				where per_user.user_id ='".$user_id."' ";
				$resultData = $this->db->query($query)->result_array();

				if (file_exists('uploads/customer_profiles/'.$user_id.'.png'))
				{
					$profileImgUrl = base_url()."uploads/customer_profiles/".$user_id.'.png' ;			
				}
				else
				{
					$profileImgUrl = base_url().'uploads/no-image.png';
				}
																
				$response = array(	
					"httpCode" 		=> 200 ,
					"customerId"	=> (int) $resultData[0]['user_id'],
					"customerName"	=> $resultData[0]['customer_name'],
					"mobileNumber"	=> $resultData[0]['country_code']."-".$resultData[0]['mobile_number'],
					"email"			=> $resultData[0]['email_address'],	
					"countryName"	=> $resultData[0]['country_name'],						
					"stateName"	    => $resultData[0]['state_name'],						
					"cityName"	    => $resultData[0]['city_name'],						
					"address1"	    => $resultData[0]['address1'],						
					"address2"	    => $resultData[0]['address2'],						
					"address3"	    => $resultData[0]['address3'],						
					"postalCode"	=> $resultData[0]['postal_code'],	
					"landMark"		=> $resultData[0]['land_mark'],	
					"profileImg"	=> $profileImgUrl					
				);
				
				header("Content-Type: application/json");
				echo json_encode($response);
				exit;
			}
			else
			{
				$response = array("httpCode" => 400 , "Message" => "Bad Request" );
				header('HTTP/1.1 400', TRUE, 400);
				header("Content-Type: application/json");
				echo json_encode($response);
				exit;
			}
			exit;
		}
	}
	
	#Edit Profile
	public function editProfile($user_id="")
	{
		$authUserName = isset($_SERVER['PHP_AUTH_USER']) ? $_SERVER['PHP_AUTH_USER'] :"";
		$authPassword = isset($_SERVER['PHP_AUTH_PW']) ? $_SERVER['PHP_AUTH_PW'] :"";
		$checkAuth = checkAuthorization($authUserName,$authPassword);
		
		if ($checkAuth == 0) #Check Authorization
		{
			$response = array("httpCode" => 401,"message" => "Unauthorized");
			header('HTTP/1.0 401 Unauthorized'); 
			header("Content-Type: application/json");
			echo json_encode($response);
			exit; 
		}
		else
		{
			if( !empty($user_id) )
			{
				$json = @file_get_contents('php://input');
				$jsondata = @json_decode($json);

				if( !empty( $jsondata->customer_name ) )
				{
					$userQry = "select user_id,reference_id as customer_id from per_user 
						where user_id ='".$user_id."' ";
					$getCustomer = $this->db->query($userQry)->result_array();

					$customer_id = isset($getCustomer[0]["customer_id"]) ? $getCustomer[0]["customer_id"] : NULL;
					
					$postData = array(
						"customer_name"      => isset($jsondata->customer_name) ? $jsondata->customer_name : NULL,
						"email_address"      => isset($jsondata->email_address) ? $jsondata->email_address : NULL,
						"country_id"         => isset($jsondata->country_id) ? $jsondata->country_id : NULL,
						"state_id"           => isset($jsondata->state_id) ? $jsondata->state_id : NULL,
						"city_id"            => isset($jsondata->city_id) ? $jsondata->city_id : NULL,
						"address1"           => isset($jsondata->address1) ? $jsondata->address1 : NULL,
						"address2"           => isset($jsondata->address2) ? $jsondata->address2 : NULL,
						"address3"           => isset($jsondata->address3) ? $jsondata->address3 : NULL,
						"postal_code"        => isset($jsondata->postal_code) ? $jsondata->postal_code : NULL,
						"land_mark"          => isset($jsondata->land_mark) ? $jsondata->land_mark : NULL,
						"last_updated_date"  => $this->date_time,
					);
					
					$this->db->where('customer_id', $customer_id);
					$result = $this->db->update('cus_consumers', $postData);
					
					if($result)
					{
						if( isset($_FILES['profile_image']['name']) && !empty($_FILES['profile_image']['name']) )
						{  
							move_uploaded_file($_FILES['profile_image']['tmp_name'], 'uploads/customer_profiles/'.$customer_id.'.png');
						}
						
						$response = array(	
							"httpCode" 		=> 200,
							"status"        => (int) 1,
							"message" 		=> "Profile updated successfully!"
						);
						
						header("Content-Type: application/json");
						echo json_encode($response);
						exit;
					}
				}
				else
				{
					$response = array("httpCode" => 400 ,"status"  => (int) 2, "Message" => "Bad Request" );
					header('HTTP/1.1 400', TRUE, 400);
					header("Content-Type: application/json");
					echo json_encode($response);
					exit;
				}
			}
			else
			{
				$response = array("httpCode" => 400 , "Message" => "Bad Request" );
				header('HTTP/1.1 400', TRUE, 400);
				header("Content-Type: application/json");
				echo json_encode($response);
				exit;
			}
			exit;
		}
	}

	#Edit Profile Image
	public function updateProfileImg($user_id="")
	{
		$authUserName = isset($_SERVER['PHP_AUTH_USER']) ? $_SERVER['PHP_AUTH_USER'] :"";
		$authPassword = isset($_SERVER['PHP_AUTH_PW']) ? $_SERVER['PHP_AUTH_PW'] :"";
		$checkAuth = checkAuthorization($authUserName,$authPassword);
		
		if ($checkAuth == 0) #Check Authorization
		{
			$response = array("httpCode" => 401,"message" => "Unauthorized");
			header('HTTP/1.0 401 Unauthorized'); 
			header("Content-Type: application/json");
			echo json_encode($response);
			exit; 
		}
		else
		{
			if( !empty($user_id) )
			{
				if( isset($_FILES))
				{
					if( isset($_FILES['profile_image']['name']) && !empty($_FILES['profile_image']['name']) )
					{  
						move_uploaded_file($_FILES['profile_image']['tmp_name'], 'uploads/customer_profiles/'.$user_id.'.png');
					}

					$query = "select 
						cus_customers.customer_id,
						cus_customers.customer_name,
						cus_customers.mobile_number,
						cus_customers.email_address,
						cus_customers.address1,
						cus_customers.address2,
						cus_customers.address3,
						cus_customers.postal_code,
						geo_countries.country_code,
						geo_countries.country_name,
						geo_states.state_name,
						geo_cities.city_name,
						per_user.user_id

						from cus_consumers as cus_customers
					left join per_user on per_user.reference_id = cus_customers.customer_id

					left join geo_countries on geo_countries.country_id = cus_customers.country_id
					left join geo_states on geo_states.state_id = cus_customers.state_id
					left join geo_cities on geo_cities.city_id = cus_customers.city_id
					where per_user.user_id='".$user_id."' ";
					$resultData = $this->db->query($query)->result_array();

					if (file_exists('uploads/customer_profiles/'.$user_id.'.png'))
					{
						$profileImgUrl = base_url()."uploads/customer_profiles/".$user_id.'.png' ;			
					}
					else
					{
						$profileImgUrl = base_url().'uploads/no-image.png';
					}
					
					$response = array(	
						"httpCode" 		=> 200,
						"status"        => (int) 1,
						"customerId"	=> (int) $resultData[0]['user_id'],
						"customerName"	=> $resultData[0]['customer_name'],
						"mobileNumber"	=> $resultData[0]['country_code']."-".$resultData[0]['mobile_number'],
						"email"			=> $resultData[0]['email_address'],	
						"countryName"	=> $resultData[0]['country_name'],						
						"stateName"	    => $resultData[0]['state_name'],						
						"cityName"	    => $resultData[0]['city_name'],						
						"address1"	    => $resultData[0]['address1'],						
						"address2"	    => $resultData[0]['address2'],						
						"address3"	    => $resultData[0]['address3'],						
						"postalCode"	=> $resultData[0]['postal_code'],	
						"profileImg"	=> $profileImgUrl,
						"message" 		=> "Profile updated successfully!"
					);
					
					header("Content-Type: application/json");
					echo json_encode($response);
					exit;
				}
			}
			else
			{
				$response = array("httpCode" => 400 , "Message" => "Bad Request" );
				header('HTTP/1.1 400', TRUE, 400);
				header("Content-Type: application/json");
				echo json_encode($response);
				exit;
			}
			exit;
		}
	}

	#Banner List 
	function bannerList()
	{
		$authUserName = isset($_SERVER['PHP_AUTH_USER']) ? $_SERVER['PHP_AUTH_USER'] :"";
		$authPassword = isset($_SERVER['PHP_AUTH_PW']) ? $_SERVER['PHP_AUTH_PW'] :"";
		$checkAuth = checkAuthorization($authUserName,$authPassword);
		
		if ($checkAuth == 0) #Check Authorization
		{
			$response = array("httpCode" => 401,"message" => "Unauthorized");
			header('HTTP/1.0 401 Unauthorized'); 
			header("Content-Type: application/json");
			echo json_encode($response);
			exit; 
		}
		else
		{
			$condition = " active_flag = 'Y' and deleted_flag = 'N' and banner_type='HOME-BANNER'";
			$query = "select banner_id, banner_title, banner_description from banner where $condition 
				order by banner.banner_id asc";
			$result = $this->db->query($query)->result_array();
			
			if( count($result) > 0 )
			{
				foreach($result as $row)
				{
					if(file_exists("uploads/banner/".$row['banner_id'].'.png') )
					{
						$photo_url = base_url().'uploads/banner/'.$row['banner_id'].'.png';
					}
					else
					{
						$photo_url = base_url().'uploads/no-image.png';
					}
			
					$response[] = array(
						"bannerId"           => (int) $row['banner_id'],
						"bannerName"         => ucfirst($row['banner_title']),
						"bannerDescription"  => ucfirst($row['banner_description']),
						"bannerImage"        => $photo_url
					);
				}
				header("Content-Type: application/json");	
				echo json_encode($response);
				exit;
			}
			else
			{
				header("Content-Type: application/json");
				$response[] = array("httpCode" => 200 , "status" => 2, "message" => "No data found.");
				echo json_encode($response);
				exit;
			}
		}
	}

	#Category List 
	function categoryList()
	{
		$authUserName = isset($_SERVER['PHP_AUTH_USER']) ? $_SERVER['PHP_AUTH_USER'] :"";
		$authPassword = isset($_SERVER['PHP_AUTH_PW']) ? $_SERVER['PHP_AUTH_PW'] :"";
		$checkAuth = checkAuthorization($authUserName,$authPassword);
		
		if ($checkAuth == 0) #Check Authorization
		{
			$response = array("httpCode" => 401,"message" => "Unauthorized");
			header('HTTP/1.0 401 Unauthorized'); 
			header("Content-Type: application/json");
			echo json_encode($response);
			exit; 
		}
		else
		{
			/*
			$condition = " categories.active_flag = 'Y' ";
			 $query = "select 
				categories.category_id,
				categories.category_name,
				cat1.list_code as category_level_code_1, 
				cat1.list_value as category_name_1,

				cat2.list_code as category_level_code_2, 
				cat2.list_value as category_name_2,

				cat3.list_code as category_level_code_3, 
				cat3.list_value as category_name_3
				
				from inv_categories as categories

				left join sm_list_type_values as cat1 on
					cat1.list_type_value_id = categories.cat_level_1
				
				left join sm_list_type_values as cat2 on
					cat2.list_type_value_id = categories.cat_level_2
				
				left join sm_list_type_values as cat3 on
					cat3.list_type_value_id = categories.cat_level_3

				where $condition 
					order by categories.disp_seq_num asc"; */
			
			
			$query = " select distinct ltv.list_code,ltv.list_value,
			(select category_id from inv_categories where cat_level_2 in
			(select list_type_value_id from sm_list_type_values where list_type_value_id = ltv.list_type_value_id) limit 1) category_id
			from sm_list_type_values ltv, inv_categories ics, inv_sys_items iss
			where 1 = 1
			and ltv.list_type_value_id = ics.cat_level_2
			and ics.category_id = iss.category_id
			and ics.active_flag='Y'
			";

			$result = $this->db->query($query)->result_array();
			
			if( count($result) > 0 )
			{
				foreach($result as $row)
				{
					if(file_exists("uploads/category_image/".$row['category_id'].'.png') )
					{
						$photo_url = base_url().'uploads/category_image/'.$row['category_id'].'.png';
					}
					else
					{
						$photo_url = base_url().'uploads/no-image.png';
					}
					
					$response[] = array(
						"categoryId"      		=> $row['list_code'],
						"categoryName"    		=> ucfirst($row['list_value']),
						"categoryImage"   		=> $photo_url
					);

					/* $response[] = array(
						"categoryId"      		=> (int) $row['category_id'],
						"categoryName"    		=> ucfirst($row['category_name']),

						#"categoryLevelCode1"    => ucfirst($row['category_level_code_1']),
						"categoryName1"    		=> ucfirst($row['category_name_1']),

						#"categoryLevelCode2"    => ucfirst($row['category_level_code_2']),
						"categoryName2"    		=> ucfirst($row['category_name_2']),

						#"categoryLevelCode3"    => ucfirst($row['category_level_code_3']),
						"categoryName3"    		=> ucfirst($row['category_name_3']),

						"categoryImage"   		=> $photo_url
					); */
				}
				header("Content-Type: application/json");	
				echo json_encode($response);
				exit;
			}
			else
			{
				header("Content-Type: application/json");
				$response[] = array("httpCode" => 200 , "status" => 2, "message" => "No data found.");
				echo json_encode($response);
				exit;
			}
		}
	}

	#Category Items 
	function categoryItems($category_id="")
	{
		$authUserName = isset($_SERVER['PHP_AUTH_USER']) ? $_SERVER['PHP_AUTH_USER'] :"";
		$authPassword = isset($_SERVER['PHP_AUTH_PW']) ? $_SERVER['PHP_AUTH_PW'] :"";
		$checkAuth = checkAuthorization($authUserName,$authPassword);
		
		if ($checkAuth == 0) #Check Authorization
		{
			$response = array("httpCode" => 401,"message" => "Unauthorized");
			header('HTTP/1.0 401 Unauthorized'); 
			header("Content-Type: application/json");
			echo json_encode($response);
			exit; 
		}
		else
		{
			$currentTime = date("H:i:s");
			$categoryCode = $category_id;

			/* $condition = " 1=1
				and cat2.list_code = '".$categoryCode."'
				and branch_items.active_flag = 'Y' 
				and items.active_flag = 'Y' 
				and categories.active_flag = 'Y'
				and branch.default_branch = 'Y' 
				and branch.active_flag = 'Y' 
				and (
						(
							branch_items.from_time_am <= '".$currentTime."'
							AND branch_items.to_time_am >= '".$currentTime."'
						)
						or
						(
							branch_items.from_time_pm <= '".$currentTime."'
							AND branch_items.to_time_pm >= '".$currentTime."'
						)
					)
				"; */

				$query = "select 
					branch.branch_id,
					branch.branch_name,
					branch.minimum_order_value,
					branch.break_fast_from,
					branch.break_fast_to,
					branch.lunch_from,
					branch.lunch_to,
					branch.dinner_from,
					branch.dinner_to,

					categories.category_id,
					categories.category_name,
					items.item_id,
					items.item_name,
					items.item_description,
					branch_items.item_price,
					branch_items.available_quantity,
					branch_items.minimum_order_quantity,
					coalesce(branch_items.breakfast_flag,'N') as breakfast_flag,
					coalesce(branch_items.lunch_flag,'N') as lunch_flag,
					coalesce(branch_items.dinner_flag,'N') as dinner_flag,

					organization.organization_id,
					organization.organization_code,
					organization.organization_name,
					cat1.list_value as category_name_1,
					cat2.list_value as category_name_2,
					cat3.list_value as category_name_3,
					offers.offer_percentage,
					(
						case
							when '".$currentTime."' between branch.break_fast_from AND branch.break_fast_to then 'BreakFast'
							when '".$currentTime."' between branch.lunch_from AND branch.lunch_to then 'Lunch'
							when '".$currentTime."' between branch.dinner_from AND branch.dinner_to then 'Dinner'
							else ''
						end 
					) food_time

					
					from inv_item_branch_assign as branch_items

				left join inv_sys_items as items on items.item_id = branch_items.item_id
				left join inv_categories as categories on categories.category_id = items.category_id
				left join branch on branch.branch_id = branch_items.branch_id
				left join inv_item_offers as offers on offers.branch_id = branch.branch_id
				left join org_organizations as organization on organization.organization_id = branch.organization_id
				
				left join sm_list_type_values as cat1 on
					cat1.list_type_value_id = categories.cat_level_1

				left join sm_list_type_values as cat2 on
					cat2.list_type_value_id = categories.cat_level_2

				left join sm_list_type_values as cat3 on
					cat3.list_type_value_id = categories.cat_level_3
					
				where
				1=1
				and cat2.list_code = '".$categoryCode."'
				and branch_items.active_flag = 'Y' 
				and items.active_flag = 'Y' 
				and categories.active_flag = 'Y'
				and branch.default_branch = 'Y' 
				and branch.active_flag = 'Y' 
				
				group by branch_id,category_id,item_id
				order by items.item_description asc
			";

			
			/* 	HAVING (
				branch_items.breakfast_flag = if (food_time = 'BreakFast', 'Y','') or
				branch_items.lunch_flag = if (food_time = 'Lunch', 'Y','') or
				branch_items.dinner_flag = if (food_time = 'Dinner', 'Y','') 
			) */
			
			$result = $this->db->query($query)->result_array();

			if( count($result) > 0 )
			{
				foreach($result as $row)
				{
					if(file_exists("uploads/products/".$row['item_id'].'.png') )
					{
						$photo_url = base_url().'uploads/products/'.$row['item_id'].'.png';
					}
					else
					{
						$photo_url = base_url().'uploads/no-image.png';
					}

					$ingredientsQry = "select line_tbl.* from inv_item_ingredient_line as line_tbl

						left join inv_item_ingredient_header as header_tbl on 
							header_tbl.ing_header_id = line_tbl.ing_header_id

							where 1=1
							and header_tbl.active_flag='Y'
							and line_tbl.active_flag='Y'
							and header_tbl.branch_id='".$row['branch_id']."'
							and line_tbl.item_id='".$row['item_id']."'
					";
					$getIngredients = $this->db->query($ingredientsQry)->result_array();

					$ingResult = [];

					foreach($getIngredients as $ing)
					{
						$ingResult[] = array(
							"ingLineId"               => (int) $ing['ing_line_id'],
							"ingredientName"          => $ing['ingredient_name'],
							"ingredientDescription"   => $ing['ingredient_description'],
							"ingredientCost"   		  => number_format($ing['ingredient_cost'],DECIMAL_VALUE,'.',''),
						);
					}

					$offerAmount = $row['offer_percentage'] / 100 * $row['item_price'];
					$itemOfferPrice = $row['item_price'] - $offerAmount;

					if(!empty($row['food_time']) && $row['food_time'] == 'BreakFast')
					{
						$itemAvailableFlag = $row['breakfast_flag'];
					}
					else if(!empty($row['food_time']) && $row['food_time'] == 'Lunch')
					{
						$itemAvailableFlag = $row['lunch_flag'];
					}
					else if(!empty($row['food_time']) && $row['food_time'] == 'Dinner')
					{
						$itemAvailableFlag = $row['dinner_flag'];
					}
					else
					{
						$itemAvailableFlag = 'N';
					}

					$response[] = array(
						"organizationId"   => (int) $row['organization_id'],
						"organizationCode" => ucfirst($row['organization_code']),
						"organizationName" => ucfirst($row['organization_name']),
						"branchId"         => (int) $row['branch_id'],
						"branchName"       => ucfirst($row['branch_name']),
						"categoryId"       => (int) $row['category_id'],
						"categoryName"     => ucfirst($row['category_name']),

						"categoryLevel1"     => ucfirst($row['category_name_1']),
						"categoryLevel2"     => ucfirst($row['category_name_2']),
						"categoryLevel3"     => ucfirst($row['category_name_3']),

						"itemId"           => (int) $row['item_id'],
						"itemName"         => ucfirst($row['item_name']),
						"itemDescription"  => ucfirst($row['item_description']),
						"itemPrice"        => number_format($row['item_price'],DECIMAL_VALUE,'.',''),
						"availableQty"     => (int) $row['available_quantity'],
						"minimumOrderQty"  => (int) $row['minimum_order_quantity'],
						"minimumOrderValue" => number_format($row['minimum_order_value'],DECIMAL_VALUE,'.',''),
						"offerPercentage"  => $row['offer_percentage'],
						"offerAmount"  	   => number_format($offerAmount,DECIMAL_VALUE,'.',''),
						"itemOfferPrice"   => number_format($itemOfferPrice,DECIMAL_VALUE,'.',''),

						"break_fast_from"  => $row['break_fast_from'],
						"break_fast_to"    => $row['break_fast_to'],
						"lunch_from"   	   => $row['lunch_from'],
						"lunch_to"   	   => $row['lunch_to'],
						"dinner_from"      => $row['dinner_from'],
						"dinner_to"   	   => $row['dinner_to'],

						"breakfastFlag"    => $row['breakfast_flag'],
						"lunchFlag"   	   => $row['lunch_flag'],
						"dinnerFlag"   	   => $row['dinner_flag'],

						"itemAvailableFlag"=> $itemAvailableFlag,
						"itemImage"        => $photo_url,
						"itemIngredients"  => $ingResult,
					);
				}
				header("Content-Type: application/json");	
				echo json_encode($response);
				exit;
			}
			else
			{
				header("Content-Type: application/json");
				$response[] = array("httpCode" => 200 , "status" => 2, "message" => "No data found.");
				echo json_encode($response);
				exit;
			}
		}
	}

	#Branch Offers
	function branchOffers()
	{
		$authUserName = isset($_SERVER['PHP_AUTH_USER']) ? $_SERVER['PHP_AUTH_USER'] :"";
		$authPassword = isset($_SERVER['PHP_AUTH_PW']) ? $_SERVER['PHP_AUTH_PW'] :"";
		$checkAuth = checkAuthorization($authUserName,$authPassword);
		
		if ($checkAuth == 0) #Check Authorization
		{
			$response = array("httpCode" => 401,"message" => "Unauthorized");
			header('HTTP/1.0 401 Unauthorized'); 
			header("Content-Type: application/json");
			echo json_encode($response);
			exit; 
		}
		else
		{
			$json = @file_get_contents('php://input');
			$jsondata = @json_decode($json);

			if( !empty( $jsondata->branch_id ) )
			{
				$condition = " active_flag = 'Y' and branch_id='".$jsondata->branch_id."' ";
				$query = "select offer_id,offer_percentage from inv_item_offers where $condition";
				$result = $this->db->query($query)->result_array();
				
				if( count($result) > 0 )
				{
					foreach($result as $row)
					{
						$response[] = array(
							"offerId"           => (int) $row['offer_id'],
							"offerPercentage"   => (double) $row['offer_percentage']
						);
					}
					header("Content-Type: application/json");	
					echo json_encode($response);
					exit;
				}
				else
				{
					header("Content-Type: application/json");
					$response[] = array("httpCode" => 200 , "status" => 2, "message" => "No data found.");
					echo json_encode($response);
					exit;
				}
			}
			else
			{
				$response = array("httpCode" => 400 ,"status"  => 2, "Message" => "Bad Request" );
				header('HTTP/1.1 400', TRUE, 400);
				header("Content-Type: application/json");
				echo json_encode($response);
				exit;
			}
			exit;
		}
	}

	#Add To Cart
	function addToCart()
	{
		$authUserName = isset($_SERVER['PHP_AUTH_USER']) ? $_SERVER['PHP_AUTH_USER'] :"";
		$authPassword = isset($_SERVER['PHP_AUTH_PW']) ? $_SERVER['PHP_AUTH_PW'] :"";
		$checkAuth = checkAuthorization($authUserName,$authPassword);
		
		if ($checkAuth == 0) #Check Authorization
		{
			$response = array("httpCode" => 401,"message" => "Unauthorized");
			header('HTTP/1.0 401 Unauthorized'); 
			header("Content-Type: application/json");
			echo json_encode($response);
			exit; 
		}
		else
		{
			$json = @file_get_contents('php://input');
			$jsondata = @json_decode($json);
			
			if( !empty( $jsondata->customer_id ) && !empty( $jsondata->item_id ) )
			{
				$postData['organization_id'] = $jsondata->organization_id;
				$postData['branch_id'] = $jsondata->branch_id;
				$postData['customer_id'] = $jsondata->customer_id;
				$postData['item_id'] = $jsondata->item_id;
				$postData['quantity'] = $jsondata->quantity;
					
				#Check Exist
				$checkExistQry = "select cart_items.cart_id from ord_cart_items as cart_items 
					where organization_id ='".$postData['organization_id']."'
					and	branch_id ='".$postData['branch_id']."'
					and	customer_id ='".$postData['customer_id']."'
					and	item_id ='".$postData['item_id']."'
				";

				$chkExist = $this->db->query($checkExistQry)->result_array();

				if( count($chkExist) == 0 )
				{
					$postData['active_flag'] = $this->active_flag;
					$postData['created_by'] = '-1';
					$postData['created_date'] = $this->date_time;
					$postData['last_updated_by'] = '-1';
					$postData['last_updated_date'] = $this->date_time;
					
					$this->db->insert('ord_cart_items', $postData);
					$id = $this->db->insert_id();
					
					if($id !="")
					{
						$response = array(	
							"httpCode" 		=> 200,
							"status"        => (int) 1,
							"message" 		=> "Item added to cart!"
						);
						
						header("Content-Type: application/json");
						echo json_encode($response);
						exit;
					}
				}
				else
				{
					$quantity = $jsondata->quantity;
					
					$updateQry = "update ord_cart_items
							SET quantity = quantity + $quantity, 
							last_updated_by = '-1',
							last_updated_date = '".$this->date_time."'
							where organization_id ='".$postData['organization_id']."'
							and	branch_id ='".$postData['branch_id']."'
							and	customer_id ='".$postData['customer_id']."'
							and	item_id ='".$postData['item_id']."'";
						
					$result = $this->db->query($updateQry);

					$response = array(	
						"httpCode" 		=> 200,
						"status"        => (int) 1,
						"message" 		=> "Item added to cart!"
					);
					
					header("Content-Type: application/json");
					echo json_encode($response);
					exit;
				}
			}
			else
			{
				$response = array("httpCode" => 400 ,"status"  => (int) 2, "Message" => "Bad Request" );
				header('HTTP/1.1 400', TRUE, 400);
				header("Content-Type: application/json");
				echo json_encode($response);
				exit;
			}
			exit;
		}
	}

	#Cart Items 
	function cartItems()
	{
		$authUserName = isset($_SERVER['PHP_AUTH_USER']) ? $_SERVER['PHP_AUTH_USER'] :"";
		$authPassword = isset($_SERVER['PHP_AUTH_PW']) ? $_SERVER['PHP_AUTH_PW'] :"";
		$checkAuth = checkAuthorization($authUserName,$authPassword);
		
		if ($checkAuth == 0) #Check Authorization
		{
			$response = array("httpCode" => 401,"message" => "Unauthorized");
			header('HTTP/1.0 401 Unauthorized'); 
			header("Content-Type: application/json");
			echo json_encode($response);
			exit; 
		}
		else
		{
			$json = @file_get_contents('php://input');
			$jsondata = @json_decode($json);

			$organization_id = $jsondata->organization_id;
			$branch_id = $jsondata->branch_id;
			$customer_id = $jsondata->customer_id;
			
			$currentTime = date("H:i:s");
			
			/* $condition = " 
				branch_items.active_flag = 'Y' 
				and items.active_flag = 'Y' 
				and cart_items.organization_id = '".$organization_id."'
				and cart_items.branch_id = '".$branch_id."'
				and cart_items.customer_id = '".$customer_id."'
				and branch.default_branch = 'Y' 
				and branch.active_flag = 'Y' 
				and (
						(
							branch_items.from_time_am <= '".$currentTime."'
							AND branch_items.to_time_am >= '".$currentTime."'
						)
						or
						(
							branch_items.from_time_pm <= '".$currentTime."'
							AND branch_items.to_time_pm >= '".$currentTime."'
						)
					)
				"; */
			
				$condition = " 
				branch_items.active_flag = 'Y' 
				and items.active_flag = 'Y' 
				and cart_items.organization_id = '".$organization_id."'
				and cart_items.branch_id = '".$branch_id."'
				and cart_items.customer_id = '".$customer_id."'
				and branch.default_branch = 'Y' 
				and branch.active_flag = 'Y' 
				";


				$query = "select 
					cart_items.cart_id,
					cart_items.quantity,
				
					branch.branch_id,
					branch.branch_name,
					branch.minimum_order_value,
					branch.break_fast_from,
					branch.break_fast_to,
					branch.lunch_from,
					branch.lunch_to,
					branch.dinner_from,
					branch.dinner_to,

					categories.category_id,
					categories.category_name,
					items.item_id,
					items.item_name,
					items.item_description,

					branch_items.item_price,
					branch_items.available_quantity,
					branch_items.minimum_order_quantity,
					
					coalesce(branch_items.breakfast_flag,'N') as breakfast_flag,
					coalesce(branch_items.lunch_flag,'N') as lunch_flag,
					coalesce(branch_items.dinner_flag,'N') as dinner_flag,

					organization.organization_id,
					organization.organization_code,
					organization.organization_name,
					offers.offer_percentage,
					(
						case
							when '".$currentTime."' between branch.break_fast_from AND branch.break_fast_to then 'BreakFast'
							when '".$currentTime."' between branch.lunch_from AND branch.lunch_to then 'Lunch'
							when '".$currentTime."' between branch.dinner_from AND branch.dinner_to then 'Dinner'
							else ''
						end 
					) food_time
					
					from ord_cart_items as cart_items 
					
				left join inv_item_branch_assign as branch_items on 
					branch_items.item_id = cart_items.item_id

				left join inv_sys_items as items on items.item_id = branch_items.item_id
				left join inv_categories as categories on categories.category_id = items.category_id
				left join branch on branch.branch_id = branch_items.branch_id
				left join inv_item_offers as offers on offers.branch_id = branch.branch_id
				left join org_organizations as organization on organization.organization_id = branch.organization_id
				
				left join sm_list_type_values as cat1 on
					cat1.list_type_value_id = categories.cat_level_1

				left join sm_list_type_values as cat2 on
					cat2.list_type_value_id = categories.cat_level_2

				left join sm_list_type_values as cat3 on
					cat3.list_type_value_id = categories.cat_level_3
				where
				$condition
				
				order by cart_items.cart_id asc";
				
			$result = $this->db->query($query)->result_array();

			if( count($result) > 0 )
			{
				foreach($result as $row)
				{
					if(file_exists("uploads/products/".$row['item_id'].'.png') )
					{
						$photo_url = base_url().'uploads/products/'.$row['item_id'].'.png';
					}
					else
					{
						$photo_url = base_url().'uploads/no-image.png';
					}

					$offerAmount = $row['offer_percentage'] / 100 * $row['item_price'];
					$itemOfferPrice = $row['item_price'] - $offerAmount;

					if(!empty($row['food_time']) && $row['food_time'] == 'BreakFast')
					{
						$itemAvailableFlag = $row['breakfast_flag'];
					}
					else if(!empty($row['food_time']) && $row['food_time'] == 'Lunch')
					{
						$itemAvailableFlag = $row['lunch_flag'];
					}
					else if(!empty($row['food_time']) && $row['food_time'] == 'Dinner')
					{
						$itemAvailableFlag = $row['dinner_flag'];
					}
					else
					{
						$itemAvailableFlag = 'N';
					}

					$response[] = array(
						"organizationId"   => (int) $row['organization_id'],
						"organizationCode" => ucfirst($row['organization_code']),
						"organizationName" => ucfirst($row['organization_name']),
						"branchId"         => (int) $row['branch_id'],
						"branchName"       => ucfirst($row['branch_name']),
						"categoryId"       => (int) $row['category_id'],
						"categoryName"     => ucfirst($row['category_name']),
						"itemId"           => (int) $row['item_id'],
						"itemName"         => ucfirst($row['item_name']),
						"itemDescription"  => ucfirst($row['item_description']),
						"itemPrice"        => number_format($row['item_price'],DECIMAL_VALUE,'.',''),
						"availableQty"     => (int) $row['available_quantity'],
						"minimumOrderQty"  => (int) $row['minimum_order_quantity'],
						"offerPercentage"  => $row['offer_percentage'],
						"offerAmount"  	   => number_format($offerAmount,DECIMAL_VALUE,'.',''),
						"itemOfferPrice"   => number_format($itemOfferPrice,DECIMAL_VALUE,'.',''),

						"break_fast_from"  => $row['break_fast_from'],
						"break_fast_to"    => $row['break_fast_to'],
						"lunch_from"   	   => $row['lunch_from'],
						"lunch_to"   	   => $row['lunch_to'],
						"dinner_from"      => $row['dinner_from'],
						"dinner_to"   	   => $row['dinner_to'],

						"breakfastFlag"    => $row['breakfast_flag'],
						"lunchFlag"   	   => $row['lunch_flag'],
						"dinnerFlag"   	   => $row['dinner_flag'],
						"itemAvailableFlag"  => $itemAvailableFlag,
						
						"itemImage"        => $photo_url,
						"itemQuantity"         => (int) $row['quantity'],
						"cartId"           => (int) $row['cart_id'],
					);

					/* $response[] = array(
						"organizationId"   => (int) $row['organization_id'],
						"organizationCode" => ucfirst($row['organization_code']),
						"organizationName" => ucfirst($row['organization_name']),
						"branchId"         => (int) $row['branch_id'],
						"branchName"       => ucfirst($row['branch_name']),
						"categoryId"       => (int) $row['category_id'],
						"categoryName"     => ucfirst($row['category_name']),
						"itemId"           => (int) $row['item_id'],
						"itemName"         => ucfirst($row['item_name']),
						"itemDescription"  => ucfirst($row['item_description']),
						"itemPrice"        => number_format($row['item_price'],DECIMAL_VALUE,'.',''),
						"availableQty"     => (int) $row['available_quantity'],
						"minimumOrderQty"  => (int) $row['minimum_order_quantity'],
						"fromTimeAm"       => $row['from_time_am'],
						"toTimeAm"         => $row['to_time_am'],
						"fromTimePm"       => $row['from_time_pm'],
						"toTimePm"         => $row['to_time_pm'],
						"itemImage"        => $photo_url,
						"itemQuantity"         => (int) $row['quantity'],
						"itemAvaialbleStatus"  => $itemAvaialbleFlag,
						"cartId"           => (int) $row['cart_id'],
					); */
				}
				header("Content-Type: application/json");	
				echo json_encode($response);
				exit;
			}
			else
			{
				header("Content-Type: application/json");
				$response[] = array("httpCode" => 200 , "status" => 2, "message" => "No data found.");
				echo json_encode($response);
				exit;
			}
		}
	}

	#Remove From Cart
	function removeFromCart()
	{
		$authUserName = isset($_SERVER['PHP_AUTH_USER']) ? $_SERVER['PHP_AUTH_USER'] :"";
		$authPassword = isset($_SERVER['PHP_AUTH_PW']) ? $_SERVER['PHP_AUTH_PW'] :"";
		$checkAuth = checkAuthorization($authUserName,$authPassword);

		if ($checkAuth == 0) #Check Authorization
		{
			$response = array("httpCode" => 401,"message" => "Unauthorized");
			header('HTTP/1.0 401 Unauthorized'); 
			header("Content-Type: application/json");
			echo json_encode($response);
			exit; 
		}
		else
		{
			$json = @file_get_contents('php://input');
			$jsondata = @json_decode($json);

			if( !empty( $jsondata->item_id ) && !empty( $jsondata->quantity ) )
			{
				$organization_id = $jsondata->organization_id;
				$branch_id = $jsondata->branch_id;
				$customer_id = $jsondata->customer_id;
				$item_id = $jsondata->item_id;
				$quantity = $jsondata->quantity;

				$checkExistQry = "select cart_items.cart_id,cart_items.quantity from ord_cart_items as cart_items 
					where 1=1
					and organization_id ='".$organization_id."'
					and branch_id ='".$branch_id."'
					and customer_id ='".$customer_id."'
					and item_id ='".$item_id."'
				";

				$chkExist = $this->db->query($checkExistQry)->result_array();

				$existQuantity = isset($chkExist[0]["quantity"]) ? $chkExist[0]["quantity"] : 0;

				if(count($chkExist) > 0)
				{
					$cart_id = isset($chkExist[0]["cart_id"]) ? $chkExist[0]["cart_id"] : NULL;

					if( $existQuantity == 0 )
					{	
						$this->db->where('cart_id', $cart_id);
						$this->db->delete('ord_cart_items');

						$response = array(	
							"httpCode" 		=> 200,
							"status"        => (int) 1,
							"message" 		=> "Item deleted from cart!"
						);
						
						header("Content-Type: application/json");
						echo json_encode($response);
						exit;	
					}
					else if( $existQuantity > 0 )
					{
						$updateQry = "update ord_cart_items
								SET quantity = quantity - $quantity, 
								last_updated_by = '-1',
								last_updated_date = '".$this->date_time."'
								where cart_id ='".$cart_id."'";
							
						$result = $this->db->query($updateQry);

						$checkExistQry = "select cart_items.cart_id,cart_items.quantity from ord_cart_items as cart_items 
							where cart_id ='".$cart_id."'
						";

						$chkExist = $this->db->query($checkExistQry)->result_array();

						$existQuantity = isset($chkExist[0]["quantity"]) ? $chkExist[0]["quantity"] : 0;

						if($existQuantity == 0)
						{
							$this->db->where('cart_id', $cart_id);
							$this->db->delete('ord_cart_items');

							$response = array(	
								"httpCode" 		=> 200,
								"status"        => (int) 1,
								"message" 		=> "Item deleted from cart!"
							);
							
							header("Content-Type: application/json");
							echo json_encode($response);
							exit;
						}
						else
						{
							$response = array(	
								"httpCode" 		=> 200,
								"status"        => (int) 1,
								"message" 		=> "Item removed from cart!"
							);
							
							header("Content-Type: application/json");
							echo json_encode($response);
							exit;
						}
					}
				}
				else
				{
					$response = array(	
						"httpCode" 		=> 200,
						"status"        => (int) 2,
						"message" 		=> "Cart Item not found!"
					);
					
					header("Content-Type: application/json");
					echo json_encode($response);
					exit;
				}	
			}
			else
			{
				$response = array("httpCode" => 400 ,"status"  => (int) 2, "Message" => "Bad Request" );
				header('HTTP/1.1 400', TRUE, 400);
				header("Content-Type: application/json");
				echo json_encode($response);
				exit;
			}
			exit;
		}
	}

	#Delete Cart
	function deleteCart($cart_id="")
	{
		$authUserName = isset($_SERVER['PHP_AUTH_USER']) ? $_SERVER['PHP_AUTH_USER'] :"";
		$authPassword = isset($_SERVER['PHP_AUTH_PW']) ? $_SERVER['PHP_AUTH_PW'] :"";
		$checkAuth = checkAuthorization($authUserName,$authPassword);
		
		if ($checkAuth == 0) #Check Authorization
		{
			$response = array("httpCode" => 401,"message" => "Unauthorized");
			header('HTTP/1.0 401 Unauthorized'); 
			header("Content-Type: application/json");
			echo json_encode($response);
			exit; 
		}
		else
		{
			if( !empty( $cart_id ) )
			{
				$checkExistQry = "select cart_items.cart_id from ord_cart_items as cart_items 
					where cart_id ='".$cart_id."'
				";

				$chkExist = $this->db->query($checkExistQry)->result_array();

				if(count($chkExist) > 0)
				{
					$this->db->where('cart_id', $cart_id);
					$this->db->delete('ord_cart_items');

					$response = array(	
						"httpCode" 		=> 200,
						"status"        => (int) 1,
						"message" 		=> "Cart deleted successfully!"
					);
					
					header("Content-Type: application/json");
					echo json_encode($response);
					exit;	
				}
				else
				{
					$response = array(	
						"httpCode" 		=> 200,
						"status"        => (int) 2,
						"message" 		=> "Cart Item not found!"
					);
					
					header("Content-Type: application/json");
					echo json_encode($response);
					exit;
				}	
			}
			else
			{
				$response = array("httpCode" => 400 ,"status"  => (int) 2, "Message" => "Bad Request" );
				header('HTTP/1.1 400', TRUE, 400);
				header("Content-Type: application/json");
				echo json_encode($response);
				exit;
			}
			exit;
		}
	}

	#Delete Cart - Cart ID Based
	function deleteCartNew()
	{
		$authUserName = isset($_SERVER['PHP_AUTH_USER']) ? $_SERVER['PHP_AUTH_USER'] :"";
		$authPassword = isset($_SERVER['PHP_AUTH_PW']) ? $_SERVER['PHP_AUTH_PW'] :"";
		$checkAuth = checkAuthorization($authUserName,$authPassword);
		
		if ($checkAuth == 0) #Check Authorization
		{
			$response = array("httpCode" => 401,"message" => "Unauthorized");
			header('HTTP/1.0 401 Unauthorized'); 
			header("Content-Type: application/json");
			echo json_encode($response);
			exit; 
		}
		else
		{
			$json = @file_get_contents('php://input');
			$jsondata = @json_decode($json);

			if( !empty( $jsondata->item_id ) && !empty( $jsondata->customer_id ) )
			{
				$item_id = $jsondata->item_id;
				$customer_id = $jsondata->customer_id;

				$checkExistQry = "select cart_items.item_id from ord_cart_items as cart_items 
					where 1=1
					and item_id ='".$item_id."'
					and customer_id ='".$customer_id."'
				";

				$chkExist = $this->db->query($checkExistQry)->result_array();

				if(count($chkExist) > 0)
				{
					$this->db->where('item_id', $item_id);
					$this->db->where('customer_id', $customer_id);
					$this->db->delete('ord_cart_items');

					$response = array(	
						"httpCode" 		=> 200,
						"status"        => (int) 1,
						"message" 		=> "Cart cleared successfully!"
					);
					
					header("Content-Type: application/json");
					echo json_encode($response);
					exit;	
				}
				else
				{
					$response = array(	
						"httpCode" 		=> 200,
						"status"        => (int) 2,
						"message" 		=> "Cart Item not found!"
					);
					
					header("Content-Type: application/json");
					echo json_encode($response);
					exit;
				}	
			}
			else
			{
				$response = array("httpCode" => 400 ,"status"  => (int) 2, "Message" => "Bad Request" );
				header('HTTP/1.1 400', TRUE, 400);
				header("Content-Type: application/json");
				echo json_encode($response);
				exit;
			}
			exit;
		}
	}

	#Clear All Cart
	function clearCart()
	{
		$authUserName = isset($_SERVER['PHP_AUTH_USER']) ? $_SERVER['PHP_AUTH_USER'] :"";
		$authPassword = isset($_SERVER['PHP_AUTH_PW']) ? $_SERVER['PHP_AUTH_PW'] :"";
		$checkAuth = checkAuthorization($authUserName,$authPassword);
		
		if ($checkAuth == 0) #Check Authorization
		{
			$response = array("httpCode" => 401,"message" => "Unauthorized");
			header('HTTP/1.0 401 Unauthorized'); 
			header("Content-Type: application/json");
			echo json_encode($response);
			exit; 
		}
		else
		{
			$json = @file_get_contents('php://input');
			$jsondata = @json_decode($json);

			if( !empty( $jsondata->customer_id ) )
			{
				$organization_id = $jsondata->organization_id;
				$branch_id = $jsondata->branch_id;
				$customer_id = $jsondata->customer_id;

				$checkExistQry = "select cart_items.cart_id from ord_cart_items as cart_items 
					where 1=1
					and organization_id ='".$organization_id."'
					and branch_id ='".$branch_id."'
					and customer_id ='".$customer_id."'
				";

				$chkExist = $this->db->query($checkExistQry)->result_array();

				if(count($chkExist) > 0)
				{
					$this->db->where('organization_id', $organization_id);
					$this->db->where('branch_id', $branch_id);
					$this->db->where('customer_id', $customer_id);
					$this->db->delete('ord_cart_items');

					$response = array(	
						"httpCode" 		=> 200,
						"status"        => (int) 1,
						"message" 		=> "Cart cleared successfully!"
					);
					
					header("Content-Type: application/json");
					echo json_encode($response);
					exit;	
				}
				else
				{
					$response = array(	
						"httpCode" 		=> 200,
						"status"        => (int) 2,
						"message" 		=> "Cart Item not found!"
					);
					
					header("Content-Type: application/json");
					echo json_encode($response);
					exit;
				}	
			}
			else
			{
				$response = array("httpCode" => 400 ,"status"  => (int) 2, "Message" => "Bad Request" );
				header('HTTP/1.1 400', TRUE, 400);
				header("Content-Type: application/json");
				echo json_encode($response);
				exit;
			}
			exit;
		}
	}

	#Add Favourite Items
	function addFavouriteItems()
	{
		$authUserName = isset($_SERVER['PHP_AUTH_USER']) ? $_SERVER['PHP_AUTH_USER'] :"";
		$authPassword = isset($_SERVER['PHP_AUTH_PW']) ? $_SERVER['PHP_AUTH_PW'] :"";
		$checkAuth = checkAuthorization($authUserName,$authPassword);
		
		if ($checkAuth == 0) #Check Authorization
		{
			$response = array("httpCode" => 401,"message" => "Unauthorized");
			header('HTTP/1.0 401 Unauthorized'); 
			header("Content-Type: application/json");
			echo json_encode($response);
			exit; 
		}
		else
		{
			$json = @file_get_contents('php://input');
			$jsondata = @json_decode($json);
			
			if( !empty( $jsondata->customer_id ) && !empty( $jsondata->item_id ) )
			{
				$postData['organization_id'] = $jsondata->organization_id;
				$postData['branch_id'] = $jsondata->branch_id;
				$postData['customer_id'] = $jsondata->customer_id;
				$postData['item_id'] = $jsondata->item_id;
					
				#Check Exist
				$checkExistQry = "select favourite_id from ord_favourite_items as favourite_items 
					where organization_id ='".$postData['organization_id']."'
					and	branch_id ='".$postData['branch_id']."'
					and	customer_id ='".$postData['customer_id']."'
					and	item_id ='".$postData['item_id']."'
				";

				$chkExist = $this->db->query($checkExistQry)->result_array();

				if( count($chkExist) == 0 )
				{
					$postData['created_by'] = '-1';
					$postData['created_date'] = $this->date_time;
					$postData['last_updated_by'] = '-1';
					$postData['last_updated_date'] = $this->date_time;
					
					$this->db->insert('ord_favourite_items', $postData);
					$id = $this->db->insert_id();
					
					if($id !="")
					{
						$response = array(	
							"httpCode" 		=> 200,
							"favouriteId"   => (int) $id,
							"status"        => (int) 1,
							"message" 		=> "Item added to favourite item!"
						);
						
						header("Content-Type: application/json");
						echo json_encode($response);
						exit;
					}
				}
				else
				{
					$response = array(	
						"httpCode" 		=> 200,
						"status"        => (int) 2,
						"message" 		=> "Item already added to favourite item!"
					);
					
					header("Content-Type: application/json");
					echo json_encode($response);
					exit;
				}
			}
			else
			{
				$response = array("httpCode" => 400 ,"status"  => (int) 2, "Message" => "Bad Request" );
				header('HTTP/1.1 400', TRUE, 400);
				header("Content-Type: application/json");
				echo json_encode($response);
				exit;
			}
			exit;
		}
	}

	#Remove Favourite Items
	function removeFavouriteItems($favourite_id="")
	{
		$authUserName = isset($_SERVER['PHP_AUTH_USER']) ? $_SERVER['PHP_AUTH_USER'] :"";
		$authPassword = isset($_SERVER['PHP_AUTH_PW']) ? $_SERVER['PHP_AUTH_PW'] :"";
		$checkAuth = checkAuthorization($authUserName,$authPassword);
		
		if ($checkAuth == 0) #Check Authorization
		{
			$response = array("httpCode" => 401,"message" => "Unauthorized");
			header('HTTP/1.0 401 Unauthorized'); 
			header("Content-Type: application/json");
			echo json_encode($response);
			exit; 
		}
		else
		{
			if( !empty( $favourite_id ) )
			{
				$checkExistQry = "select favourite_id from ord_favourite_items as favourite_items 
					where favourite_id ='".$favourite_id."'
				";

				$chkExist = $this->db->query($checkExistQry)->result_array();

				if(count($chkExist) > 0)
				{
					$this->db->where('favourite_id', $favourite_id);
					$this->db->delete('ord_favourite_items');

					$response = array(	
						"httpCode" 		=> 200,
						"status"        => (int) 1,
						"message" 		=> "Favourite item deleted successfully!"
					);
					
					header("Content-Type: application/json");
					echo json_encode($response);
					exit;	
				}
				else
				{
					$response = array(	
						"httpCode" 		=> 200,
						"status"        => (int) 2,
						"message" 		=> "Favourite item not found!"
					);
					
					header("Content-Type: application/json");
					echo json_encode($response);
					exit;
				}	
			}
			else
			{
				$response = array("httpCode" => 400 ,"status"  => (int) 2, "Message" => "Bad Request" );
				header('HTTP/1.1 400', TRUE, 400);
				header("Content-Type: application/json");
				echo json_encode($response);
				exit;
			}
			exit;
		}
	}

	#Favourite Items 
	function favouriteItems()
	{
		$authUserName = isset($_SERVER['PHP_AUTH_USER']) ? $_SERVER['PHP_AUTH_USER'] :"";
		$authPassword = isset($_SERVER['PHP_AUTH_PW']) ? $_SERVER['PHP_AUTH_PW'] :"";
		$checkAuth = checkAuthorization($authUserName,$authPassword);
		
		if ($checkAuth == 0) #Check Authorization
		{
			$response = array("httpCode" => 401,"message" => "Unauthorized");
			header('HTTP/1.0 401 Unauthorized'); 
			header("Content-Type: application/json");
			echo json_encode($response);
			exit; 
		}
		else
		{
			$json = @file_get_contents('php://input');
			$jsondata = @json_decode($json);

			$organization_id = $jsondata->organization_id;
			$branch_id = $jsondata->branch_id;
			$customer_id = $jsondata->customer_id;
			
			$currentTime = date("H:i:s");
			
			/* $condition = " 
				branch_items.active_flag = 'Y' 
				and items.active_flag = 'Y' 
				and cart_items.organization_id = '".$organization_id."'
				and cart_items.branch_id = '".$branch_id."'
				and cart_items.customer_id = '".$customer_id."'
				and branch.default_branch = 'Y' 
				and branch.active_flag = 'Y' 
				and (
						(
							branch_items.from_time_am <= '".$currentTime."'
							AND branch_items.to_time_am >= '".$currentTime."'
						)
						or
						(
							branch_items.from_time_pm <= '".$currentTime."'
							AND branch_items.to_time_pm >= '".$currentTime."'
						)
					)
				"; */
			
				/* $condition = " 
				branch_items.active_flag = 'Y' 
				and items.active_flag = 'Y' 
				and favourite_items.organization_id = '".$organization_id."'
				and favourite_items.branch_id = '".$branch_id."'
				and favourite_items.customer_id = '".$customer_id."'
				and branch.default_branch = 'Y' 
				and branch.active_flag = 'Y' 
				"; */

				$query = "select 
					favourite_items.*,

					branch.branch_id,
					branch.branch_name,
					branch.minimum_order_value,
					branch.break_fast_from,
					branch.break_fast_to,
					branch.lunch_from,
					branch.lunch_to,
					branch.dinner_from,
					branch.dinner_to,

					categories.category_id,
					categories.category_name,
					items.item_id,
					items.item_name,
					items.item_description,
					branch_items.item_price,
					branch_items.available_quantity,
					branch_items.minimum_order_quantity,
					
					coalesce(branch_items.breakfast_flag,'N') as breakfast_flag,
					coalesce(branch_items.lunch_flag,'N') as lunch_flag,
					coalesce(branch_items.dinner_flag,'N') as dinner_flag,

					organization.organization_id,
					organization.organization_code,
					organization.organization_name,
					offers.offer_percentage,
					(
						case
							when '".$currentTime."' between branch.break_fast_from AND branch.break_fast_to then 'BreakFast'
							when '".$currentTime."' between branch.lunch_from AND branch.lunch_to then 'Lunch'
							when '".$currentTime."' between branch.dinner_from AND branch.dinner_to then 'Dinner'
							else ''
						end 
					) food_time
					from ord_favourite_items as favourite_items 
					
				left join inv_item_branch_assign as branch_items on 
					branch_items.item_id = favourite_items.item_id

				left join inv_sys_items as items on items.item_id = branch_items.item_id
				left join inv_categories as categories on categories.category_id = items.category_id
				left join branch on branch.branch_id = branch_items.branch_id

				left join inv_item_offers as offers on offers.branch_id = branch.branch_id

				left join org_organizations as organization on organization.organization_id = branch.organization_id
				
				left join sm_list_type_values as cat1 on
					cat1.list_type_value_id = categories.cat_level_1

				left join sm_list_type_values as cat2 on
					cat2.list_type_value_id = categories.cat_level_2

				left join sm_list_type_values as cat3 on
					cat3.list_type_value_id = categories.cat_level_3
				where

				branch_items.active_flag = 'Y' 
				and items.active_flag = 'Y' 
				and favourite_items.organization_id = '".$organization_id."'
				and favourite_items.branch_id = '".$branch_id."'
				and favourite_items.customer_id = '".$customer_id."'
				and branch.default_branch = 'Y' 
				and branch.active_flag = 'Y' 
				
				group by branch_items.branch_id,category_id

				order by items.item_description asc
				";
				
			$result = $this->db->query($query)->result_array();

			if( count($result) > 0 )
			{
				foreach($result as $row)
				{
					if(file_exists("uploads/products/".$row['item_id'].'.png') )
					{
						$photo_url = base_url().'uploads/products/'.$row['item_id'].'.png';
					}
					else
					{
						$photo_url = base_url().'uploads/no-image.png';
					}

					$offerAmount = $row['offer_percentage'] / 100 * $row['item_price'];
					$itemOfferPrice = $row['item_price'] - $offerAmount;

					if(!empty($row['food_time']) && $row['food_time'] == 'BreakFast')
					{
						$itemAvailableFlag = $row['breakfast_flag'];
					}
					else if(!empty($row['food_time']) && $row['food_time'] == 'Lunch')
					{
						$itemAvailableFlag = $row['lunch_flag'];
					}
					else if(!empty($row['food_time']) && $row['food_time'] == 'Dinner')
					{
						$itemAvailableFlag = $row['dinner_flag'];
					}
					else
					{
						$itemAvailableFlag = 'N';
					}

					$response[] = array(
						"organizationId"   => (int) $row['organization_id'],
						"organizationCode" => ucfirst($row['organization_code']),
						"organizationName" => ucfirst($row['organization_name']),
						"branchId"         => (int) $row['branch_id'],
						"branchName"       => ucfirst($row['branch_name']),
						"categoryId"       => (int) $row['category_id'],
						"categoryName"     => ucfirst($row['category_name']),
						"itemId"           => (int) $row['item_id'],
						"itemName"         => ucfirst($row['item_name']),
						"itemDescription"  => ucfirst($row['item_description']),
						"itemPrice"        => number_format($row['item_price'],DECIMAL_VALUE,'.',''),
						"availableQty"     => (int) $row['available_quantity'],
						"minimumOrderQty"  => (int) $row['minimum_order_quantity'],
						"offerPercentage"  => $row['offer_percentage'],
						"offerAmount"  	   => number_format($offerAmount,DECIMAL_VALUE,'.',''),
						"itemOfferPrice"   => number_format($itemOfferPrice,DECIMAL_VALUE,'.',''),

						"break_fast_from"  => $row['break_fast_from'],
						"break_fast_to"    => $row['break_fast_to'],
						"lunch_from"   	   => $row['lunch_from'],
						"lunch_to"   	   => $row['lunch_to'],
						"dinner_from"      => $row['dinner_from'],
						"dinner_to"   	   => $row['dinner_to'],

						"breakfastFlag"    => $row['breakfast_flag'],
						"lunchFlag"   	   => $row['lunch_flag'],
						"dinnerFlag"   	   => $row['dinner_flag'],
						"itemAvailableFlag"  => $itemAvailableFlag,
						
						"itemImage"        => $photo_url,
						"favouriteId"      => (int) $row['favourite_id'],
					);
				}
				header("Content-Type: application/json");	
				echo json_encode($response);
				exit;
			}
			else
			{
				header("Content-Type: application/json");
				$response[] = array("httpCode" => 200 , "status" => 2, "message" => "No data found.");
				echo json_encode($response);
				exit;
			}
		}
	}

	#Payment Types
	function paymentTypes()
	{
		$authUserName = isset($_SERVER['PHP_AUTH_USER']) ? $_SERVER['PHP_AUTH_USER'] :"";
		$authPassword = isset($_SERVER['PHP_AUTH_PW']) ? $_SERVER['PHP_AUTH_PW'] :"";
		$checkAuth = checkAuthorization($authUserName,$authPassword);
		
		if ($checkAuth == 0) #Check Authorization
		{
			$response = array("httpCode" => 401,"message" => "Unauthorized");
			header('HTTP/1.0 401 Unauthorized'); 
			header("Content-Type: application/json");
			echo json_encode($response);
			exit; 
		}
		else
		{
			$condition = " 
				active_flag = 'Y' 
				and deleted_flag='N' 
				and payment_type_id IN(1,2)";
			$query = "select payment_type_id,payment_type,default_payment from pay_payment_types 
			where $condition 
				order by pay_payment_types.sequence_number asc";
			$result = $this->db->query($query)->result_array();
			
			if( count($result) > 0 )
			{
				foreach($result as $row)
				{
					if(file_exists("uploads/payments/".$row['payment_type_id'].'.png') )
					{
						$photo_url = base_url().'uploads/payments/'.$row['payment_type_id'].'.png';
					}
					else
					{
						$photo_url = base_url().'uploads/no-image.png';
					}
			
					$response[] = array(
						"paymentTypeId"    => (int) $row['payment_type_id'],
						"paymentType"      => $row['payment_type'],
						"defaultPayment"   => $row['default_payment'],
						"paymentIcon"      => $photo_url
					);
				}
				header("Content-Type: application/json");	
				echo json_encode($response);
				exit;
			}
			else
			{
				header("Content-Type: application/json");
				$response[] = array("httpCode" => 200 , "status" => 2, "message" => "No data found.");
				echo json_encode($response);
				exit;
			}
		}
	}
	
	#Delete Account
	function deleteAccount($user_id="")
	{
		$authUserName = isset($_SERVER['PHP_AUTH_USER']) ? $_SERVER['PHP_AUTH_USER'] :"";
		$authPassword = isset($_SERVER['PHP_AUTH_PW']) ? $_SERVER['PHP_AUTH_PW'] :"";
		$checkAuth = checkAuthorization($authUserName,$authPassword);
		
		if ($checkAuth == 0) #Check Authorization
		{
			$response = array("httpCode" => 401,"message" => "Unauthorized");
			header('HTTP/1.0 401 Unauthorized'); 
			header("Content-Type: application/json");
			echo json_encode($response);
			exit; 
		}
		else
		{
			$userQry = "select user_id,reference_id as customer_id from per_user where user_id ='".$user_id."' ";
			$getCustomer = $this->db->query($userQry)->result_array();

			$customer_id = isset($getCustomer[0]["customer_id"]) ? $getCustomer[0]["customer_id"] : NULL;
			 
			if($user_id !="")
			{
				$postData = array(
					'deleted_flag'          => 'Y',
					'deleted_by'            => $customer_id,
					'deleted_date' 			=> $this->date_time
				);
				
				$this->db->where('customer_id', $customer_id);
				$result = $this->db->update('cus_consumers', $postData);

				if($result)
				{
					$perUserData = array(
						"active_flag" 		=> 'N',
						"last_updated_by" 	=> $user_id,
						"last_updated_date"	=> $this->date_time,
					);

					$this->db->where('user_id', $user_id);
					$usr_result = $this->db->update('per_user', $perUserData);

					/* $this->db->where('customer_id', $customer_id);
					$this->db->delete('cus_customers_interface'); */

					$response = array("httpCode" => 200 ,"status" => 1 , "message" => "Your account has been deleted successfully!" );
					header("Content-Type: application/json");	
					echo json_encode($response);
					exit;
				}
				else
				{
					$response = array("httpCode" => 200 ,"status" => 0 , "message" => "Error!" );
					header("Content-Type: application/json");	
					echo json_encode($response);
					exit;
				}	
			}
			else
			{
				$response = array("httpCode" => 400 , "Message" => "Bad Request!" );
				header('HTTP/1.1 400', TRUE, 400);
				echo json_encode($response);
				exit;
			}
		}
	}

	#Category Sliders
	function categorySliders()
	{
		$authUserName = isset($_SERVER['PHP_AUTH_USER']) ? $_SERVER['PHP_AUTH_USER'] :"";
		$authPassword = isset($_SERVER['PHP_AUTH_PW']) ? $_SERVER['PHP_AUTH_PW'] :"";
		$checkAuth = checkAuthorization($authUserName,$authPassword);
		
		if ($checkAuth == 0) #Check Authorization
		{
			$response = array("httpCode" => 401,"message" => "Unauthorized");
			header('HTTP/1.0 401 Unauthorized'); 
			header("Content-Type: application/json");
			echo json_encode($response);
			exit; 
		}
		else
		{
			$json = @file_get_contents('php://input');
			$jsondata = @json_decode($json);

			$branch_id = $jsondata->branch_id;
			$category_id = $jsondata->category_id;
			
			$currentDate = $this->date;

			$condition = " 
				active_flag = 'Y' 
				and coalesce(category_banner.start_date,'".$this->date."') <= '".$this->date."' 
				and coalesce(category_banner.end_date,'".$this->date."') >= '".$this->date."'
				";

			$query = "select 
				banner_id,
				category_id,
				category_image from inv_category_banners as category_banner
			where $condition 
				order by category_banner.banner_id asc";
			$result = $this->db->query($query)->result_array();
			
			if( count($result) > 0 )
			{
				foreach($result as $row)
				{
					if(file_exists("uploads/category_sliders/".$row['category_image']) )
					{
						$photo_url = base_url().'uploads/category_sliders/'.$row['category_image'];
					}
					else
					{
						$photo_url = base_url().'uploads/no-image.png';
					}
			
					$response[] = array(
						"bannerId"        => (int) $row['banner_id'],
						"categoryId"      => $row['category_id'],
						"sliderImage"     => $photo_url
					);
				}
				header("Content-Type: application/json");	
				echo json_encode($response);
				exit;
			}
			else
			{
				header("Content-Type: application/json");
				$response[] = array("httpCode" => 200 , "status" => 2, "message" => "No data found.");
				echo json_encode($response);
				exit;
			}
		}
	}

	#Add Customer Address
	function addCustomerAddress()
	{
		$authUserName = isset($_SERVER['PHP_AUTH_USER']) ? $_SERVER['PHP_AUTH_USER'] :"";
		$authPassword = isset($_SERVER['PHP_AUTH_PW']) ? $_SERVER['PHP_AUTH_PW'] :"";
		$checkAuth = checkAuthorization($authUserName,$authPassword);
		
		if ($checkAuth == 0) #Check Authorization
		{
			$response = array("httpCode" => 401,"message" => "Unauthorized");
			header('HTTP/1.0 401 Unauthorized'); 
			header("Content-Type: application/json");
			echo json_encode($response);
			exit; 
		}
		else
		{
			$json = @file_get_contents('php://input');
			$jsondata = @json_decode($json);

			$customer_id = $jsondata->customer_id;

			if( !empty( $jsondata->customer_id ) )
			{
				$postDefaultAddressData['default_address'] = 'N';
				$this->db->where('customer_id', $customer_id);
				$result = $this->db->update('cus_customer_address', $postDefaultAddressData);

				$postData['customer_id'] = $jsondata->customer_id;
				$postData['customer_name'] = $jsondata->customer_name;
				$postData['phone_number'] = $jsondata->phone_number;
				$postData['floor_no'] = $jsondata->floor_no;
				$postData['street'] = $jsondata->street;
				$postData['land_mark'] = $jsondata->land_mark;
				$postData['address_type'] = $jsondata->address_type;
				$postData['postal_code'] = $jsondata->postal_code;
				$postData['address1'] = $jsondata->address1;

				$postData['country_id'] = $jsondata->country_id;
				$postData['state_name'] = $jsondata->state_name;
				$postData['city_name'] = $jsondata->city_name;

				$postData['latitude'] = $jsondata->latitude;
				$postData['longitude'] = $jsondata->longitude;
				
				$postData['created_by'] = '-1';
				$postData['created_date'] = $this->date_time;
				$postData['last_updated_by'] = '-1';
				$postData['last_updated_date'] = $this->date_time;
				$postData['default_address'] = 'Y';
				
				$this->db->insert('cus_customer_address', $postData);
				$id = $this->db->insert_id();
				
				if($id !="")
				{
					$response = array(	
						"httpCode" 		=> 200,
						"status"        => (int) 1,
						"message" 		=> "Address added successfully!"
					);
					
					header("Content-Type: application/json");
					echo json_encode($response);
					exit;
				}
			}
			else
			{
				$response = array("httpCode" => 400 ,"status"  => (int) 2, "Message" => "Bad Request" );
				header('HTTP/1.1 400', TRUE, 400);
				header("Content-Type: application/json");
				echo json_encode($response);
				exit;
			}
			exit;
		}
	}

	#Edit Customer Address
	function editCustomerAddress()
	{
		$authUserName = isset($_SERVER['PHP_AUTH_USER']) ? $_SERVER['PHP_AUTH_USER'] : "";
		$authPassword = isset($_SERVER['PHP_AUTH_PW']) ? $_SERVER['PHP_AUTH_PW'] : "";
		$checkAuth = checkAuthorization($authUserName,$authPassword);
		
		if ($checkAuth == 0) #Check Authorization
		{
			$response = array("httpCode" => 401,"message" => "Unauthorized");
			header('HTTP/1.0 401 Unauthorized'); 
			header("Content-Type: application/json");
			echo json_encode($response);
			exit; 
		}
		else
		{
			$json = @file_get_contents('php://input');
			$jsondata = @json_decode($json);

			if( !empty( $jsondata->customer_id ) && !empty( $jsondata->customer_address_id ) ) 
			{
				$customer_id =  $jsondata->customer_id;
				$customer_address_id =  $jsondata->customer_address_id;

				$postData['customer_id'] = $jsondata->customer_id;
				$postData['customer_name'] = $jsondata->customer_name;
				$postData['phone_number'] = $jsondata->phone_number;
				$postData['floor_no'] = $jsondata->floor_no;
				$postData['street'] = $jsondata->street;
				$postData['land_mark'] = $jsondata->land_mark;
				$postData['address_type'] = $jsondata->address_type;
				$postData['postal_code'] = $jsondata->postal_code;
				$postData['address1'] = $jsondata->address1;

				$postData['country_id'] = $jsondata->country_id;
				$postData['state_name'] = $jsondata->state_name;
				$postData['city_name'] = $jsondata->city_name;

				$postData['latitude'] = $jsondata->latitude;
				$postData['longitude'] = $jsondata->longitude;
				
				$postData['last_updated_by'] = '-1';
				$postData['last_updated_date'] = $this->date_time;
				
				$this->db->where('customer_id', $customer_id);
				$this->db->where('customer_address_id', $customer_address_id);
				$result = $this->db->update('cus_customer_address', $postData);

				if($result !="")
				{
					$response = array(	
						"httpCode" 		=> 200,
						"status"        => (int) 1,
						"message" 		=> "Address updated successfully!"
					);
					
					header("Content-Type: application/json");
					echo json_encode($response);
					exit;
				}
			}
			else
			{
				$response = array("httpCode" => 400 ,"status"  => (int) 2, "Message" => "Bad Request" );
				header('HTTP/1.1 400', TRUE, 400);
				header("Content-Type: application/json");
				echo json_encode($response);
				exit;
			}
			exit;
		}
	}

	#Delete Customer Address
	function deleteCustomerAddress()
	{
		$authUserName = isset($_SERVER['PHP_AUTH_USER']) ? $_SERVER['PHP_AUTH_USER'] :"";
		$authPassword = isset($_SERVER['PHP_AUTH_PW']) ? $_SERVER['PHP_AUTH_PW'] :"";
		$checkAuth = checkAuthorization($authUserName,$authPassword);
		
		if ($checkAuth == 0) #Check Authorization
		{
			$response = array("httpCode" => 401,"message" => "Unauthorized");
			header('HTTP/1.0 401 Unauthorized'); 
			header("Content-Type: application/json");
			echo json_encode($response);
			exit; 
		}
		else
		{
			$json = @file_get_contents('php://input');
			$jsondata = @json_decode($json);

			if( !empty( $jsondata->customer_id ) && !empty( $jsondata->customer_address_id ) ) 
			{
				$customer_id =  $jsondata->customer_id;
				$customer_address_id =  $jsondata->customer_address_id;

				$this->db->where('customer_id', $customer_id);
				$this->db->where('customer_address_id', $customer_address_id);
				$this->db->delete('cus_customer_address');

				$response = array(	
					"httpCode" 		=> 200,
					"status"        => (int) 1,
					"message" 		=> "Address deleted successfully!"
				);
				header("Content-Type: application/json");
				echo json_encode($response);
				exit;
			}	
			else
			{
				$response = array("httpCode" => 400 ,"status"  => (int) 2, "Message" => "Bad Request" );
				header('HTTP/1.1 400', TRUE, 400);
				header("Content-Type: application/json");
				echo json_encode($response);
				exit;
			}
			exit;
		}
	}

	#Customer Address Listing
	function customerAddress($user_id="")
	{
		$authUserName = isset($_SERVER['PHP_AUTH_USER']) ? $_SERVER['PHP_AUTH_USER'] :"";
		$authPassword = isset($_SERVER['PHP_AUTH_PW']) ? $_SERVER['PHP_AUTH_PW'] :"";
		$checkAuth = checkAuthorization($authUserName,$authPassword);
		
		if ($checkAuth == 0) #Check Authorization
		{
			$response = array("httpCode" => 401,"message" => "Unauthorized");
			header('HTTP/1.0 401 Unauthorized'); 
			header("Content-Type: application/json");
			echo json_encode($response);
			exit; 
		}
		else
		{
			$condition = " per_user.user_id = '".$user_id."'";

			$query = "select 
				cus_customers.customer_id,
				cus_customers.customer_name,
				customer_address.customer_address_id,
				customer_address.customer_name as address_customer_name,
				customer_address.phone_number,
				customer_address.floor_no,
				customer_address.street,
				customer_address.land_mark,
				customer_address.postal_code,
				customer_address.default_address,
				customer_address.address1,
				customer_address.address_type,
				customer_address.latitude,
				customer_address.longitude,
				country.country_name,
				customer_address.state_name,
				customer_address.city_name,
				per_user.user_id,
				sm_list_type_values.list_value as address_type_value

			from cus_customer_address as customer_address 

			left join per_user on 
				per_user.user_id = customer_address.customer_id

			left join cus_consumers as cus_customers on 
				cus_customers.customer_id = per_user.reference_id
			
			left join geo_countries as country on 
				country.country_id = customer_address.country_id

			left join sm_list_type_values on 
				sm_list_type_values.list_code = customer_address.address_type
			
			where $condition 
				order by customer_address.customer_address_id asc";
			$result = $this->db->query($query)->result_array();
			
			if( count($result) > 0 )
			{
				foreach($result as $row)
				{
					$response[] = array(
						"customerId"          => (int) $row['user_id'],
						"customerAddressId"   => (int) $row['customer_address_id'],
						"customerName"        => $row['customer_name'],
						"addressCustomerName" => $row['address_customer_name'],
						"phoneNumber"         => $row['phone_number'],
						"floorNo"             => $row['floor_no'],
						"street"              => $row['street'],
						"landMark"            => $row['land_mark'],
						"address1"            => $row['address1'],
						"postalCode"          => $row['postal_code'],
						"defaultAddress"      => $row['default_address'],
						"addressType"         => $row['address_type'],
						"countryName"         => $row['country_name'],
						"stateName"           => $row['state_name'],
						"cityName"            => $row['city_name'],
						"latitude"            => $row['latitude'],
						"longitude"           => $row['longitude'],
					);
				}
				header("Content-Type: application/json");	
				echo json_encode($response);
				exit;
			}
			else
			{
				header("Content-Type: application/json");
				$response[] = array("httpCode" => 200 , "status" => 2, "message" => "No data found.");
				echo json_encode($response);
				exit;
			}
		}
	}

	#Master Data
	public function apiMasterData($type="", $id="")
	{
		$authUserName = isset($_SERVER['PHP_AUTH_USER']) ? $_SERVER['PHP_AUTH_USER'] :"";
		$authPassword = isset($_SERVER['PHP_AUTH_PW']) ? $_SERVER['PHP_AUTH_PW'] :"";
		$checkAuth = checkAuthorization($authUserName,$authPassword);
		
		if ($checkAuth == 0) #Check Authorization
		{
			$response = array("httpCode" => 401,"message" => "Unauthorized");
			header('HTTP/1.0 401 Unauthorized'); 
			header("Content-Type: application/json");
			echo json_encode($response);
			exit; 
		}
		else
		{
			switch($type)
			{
				case "add": #Add
				break;

				case "masterData": #masterData
						
						$addressTypeQry = "select sm_list_type_values.list_code,sm_list_type_values.list_value,sm_list_type_values.list_type_value_id from sm_list_type_values 
						left join sm_list_types on sm_list_types.list_type_id = sm_list_type_values.list_type_id
						where 

						sm_list_types.active_flag='Y' and 
						coalesce(sm_list_types.start_date,'".$this->date."') <= '".$this->date."' and 
						coalesce(sm_list_types.end_date,'".$this->date."') >= '".$this->date."' and
						sm_list_types.deleted_flag='N' and

						sm_list_type_values.active_flag='Y' and 
						coalesce(sm_list_type_values.start_date,'".$this->date."') <= '".$this->date."' and 
						coalesce(sm_list_type_values.end_date,'".$this->date."') >= '".$this->date."' and
						sm_list_type_values.deleted_flag='N' and 

						sm_list_types.list_name = 'ADDRESSTYPE' ";

						$getAddressType = $this->db->query($addressTypeQry)->result_array();
						
						$addressTypeResponse = [];

						if(count($getAddressType) > 0)
						{	
							foreach($getAddressType as $row)
							{
								$addressTypeResponse[] = array(	
									"listCode"  => $row['list_code'],
									"listValue" => $row['list_value']
								);
							}
						}

						$lookingForQry = "select sm_list_type_values.list_code,sm_list_type_values.list_value,sm_list_type_values.list_type_value_id from sm_list_type_values 
						left join sm_list_types on sm_list_types.list_type_id = sm_list_type_values.list_type_id
						where 

						sm_list_types.active_flag='Y' and 
						coalesce(sm_list_types.start_date,'".$this->date."') <= '".$this->date."' and 
						coalesce(sm_list_types.end_date,'".$this->date."') >= '".$this->date."' and
						sm_list_types.deleted_flag='N' and

						sm_list_type_values.active_flag='Y' and 
						coalesce(sm_list_type_values.start_date,'".$this->date."') <= '".$this->date."' and 
						coalesce(sm_list_type_values.end_date,'".$this->date."') >= '".$this->date."' and
						sm_list_type_values.deleted_flag='N' and 

						sm_list_types.list_name = 'ORDERENQUIRYLOOKINGFOR' ";

						$getEnquiryLookingFor = $this->db->query($lookingForQry)->result_array();

						$EnquiryLookingForResponse = [];

						if(count($getEnquiryLookingFor) > 0)
						{	
							foreach($getEnquiryLookingFor as $row)
							{
								$EnquiryLookingForResponse[] = array(	
									"listCode"  => $row['list_code'],
									"listValue" => $row['list_value']
								);
							}
						}

						$masterData = array(
							"addressType"    	=> $addressTypeResponse,
							"EnquiryLookingFor" => $EnquiryLookingForResponse,
						);

						header("Content-Type: application/json");
						echo json_encode($masterData);
						exit;
				break;
			}	
		}
	}

	#Country List 
	function country_list()
	{
		$authUserName = isset($_SERVER['PHP_AUTH_USER']) ? $_SERVER['PHP_AUTH_USER'] :"";
		$authPassword = isset($_SERVER['PHP_AUTH_PW']) ? $_SERVER['PHP_AUTH_PW'] :"";
		$checkAuth = checkAuthorization($authUserName,$authPassword);
		
		if ($checkAuth == 0) #Check Authorization
		{
			$response = array("httpCode" => 401,"message" => "Unauthorized");
			header('HTTP/1.0 401 Unauthorized'); 
			header("Content-Type: application/json");
			echo json_encode($response);
			exit; 
		}
		else
		{
			$condition = " country.active_flag = 'Y' ";
			$query = "select country_id,country_name,country_code,currency_symbol,currency_code from geo_countries AS country where $condition order by country.country_name asc";
			$result = $this->db->query($query)->result_array();
			
			if( count($result) > 0 )
			{
				foreach($result as $row)
				{
					if(file_exists("uploads/country_icons/".$row['country_id'].'.png') )
					{
						$photo_url = base_url().'uploads/country_icons/'.$row['country_id'].'.png';
					}
					else
					{
						$photo_url = base_url().'uploads/no-image.png';
					}
					
					$response[] = array(
						"country_id"      => (int) $row['country_id'],
						"country_name"    => ucfirst($row['country_name']),
						"country_code"    => trim($row['country_code']),
						"currency_symbol" => $row['currency_symbol'],
						"currency_code"   => $row['currency_code'],
						"country_flag"    => $photo_url,
					);
				}
				header("Content-Type: application/json");	
				echo json_encode($response);
				exit;
			}
			else
			{
				header("Content-Type: application/json");
				$response[] = array("httpCode" => 200 , "status" => 2, "message" => "No data found.");
				echo json_encode($response);
				exit;
			}
		}
	}
	
	#State List 
	function state_list($country_id="")
	{
		$authUserName = isset($_SERVER['PHP_AUTH_USER']) ? $_SERVER['PHP_AUTH_USER'] :"";
		$authPassword = isset($_SERVER['PHP_AUTH_PW']) ? $_SERVER['PHP_AUTH_PW'] :"";
		$checkAuth = checkAuthorization($authUserName,$authPassword);
		
		if ($checkAuth == 0) #Check Authorization
		{
			$response = array("httpCode" => 401,"message" => "Unauthorized");
			header('HTTP/1.0 401 Unauthorized'); 
			header("Content-Type: application/json");
			echo json_encode($response);
			exit; 
		}
		else
		{
			$condition = " state.active_flag = 'Y' 
							and state.country_id ='".$country_id."' ";

			$query = "select state.state_id,state.state_name,state.country_id from geo_states as state
				left join geo_countries as country on country.country_id = state.country_id
			where $condition
				order by state.state_name asc";
			$result = $this->db->query($query)->result_array();
			
			if( count($result) > 0 )
			{
				foreach($result as $row)
				{
					$response[] = array(
							"state_id"   => (int) $row['state_id'],
							"state_name" => ucfirst($row['state_name'])
					);
				}
				header("Content-Type: application/json");
				echo json_encode($response);
				exit;
			}
			else
			{
				header("Content-Type: application/json");
				$response[] = array("httpCode" => 200 , "status" => 2, "message" => "No data found.");
				echo json_encode($response);
				exit;
			}
		}
	}
	
	# City List
	function city_list($state_id="")
	{
		$authUserName = isset($_SERVER['PHP_AUTH_USER']) ? $_SERVER['PHP_AUTH_USER'] :"";
		$authPassword = isset($_SERVER['PHP_AUTH_PW']) ? $_SERVER['PHP_AUTH_PW'] :"";
		$checkAuth = checkAuthorization($authUserName,$authPassword);
		
		if ($checkAuth == 0) #Check Authorization
		{
			$response = array("httpCode" => 401,"message" => "Unauthorized");
			header('HTTP/1.0 401 Unauthorized'); 
			header("Content-Type: application/json");
			echo json_encode($response);
			exit;
		}
		else
		{
			$condition = " city.active_flag = 'Y' and city.state_id='".$state_id."' ";
			$query = "select 
				city.city_id,
				city.city_name,
				city.country_id,
				city.state_id
				
				from geo_cities as city
			
				left join geo_countries as country on country.country_id = city.country_id
				left join geo_states as state on state.state_id = city.state_id
				
			where $condition
				order by city.city_name asc
			";
			$result = $this->db->query($query)->result_array();
			
			if( count($result) > 0 )
			{
				foreach($result as $row)
				{
					$response[] = array(
						"city_id"   => (int) $row['city_id'],
						"city_name" => ucfirst($row['city_name'])
					);
				}
				header("Content-Type: application/json");
				echo json_encode($response);
				exit;
			}
			else
			{
				header("Content-Type: application/json");
				$response[] = array("httpCode" => 200 , "status" => 2, "message" => "No data found.");
				echo json_encode($response);
				exit;
			}
		}
	}

	# branch List
	function branchList($state_id="")
	{
		$authUserName = isset($_SERVER['PHP_AUTH_USER']) ? $_SERVER['PHP_AUTH_USER'] :"";
		$authPassword = isset($_SERVER['PHP_AUTH_PW']) ? $_SERVER['PHP_AUTH_PW'] :"";
		$checkAuth = checkAuthorization($authUserName,$authPassword);
		
		if ($checkAuth == 0) #Check Authorization
		{
			$response = array("httpCode" => 401,"message" => "Unauthorized");
			header('HTTP/1.0 401 Unauthorized'); 
			header("Content-Type: application/json");
			echo json_encode($response);
			exit;
		}
		else
		{
			$condition = " branch.active_flag = 'Y'";
			$query = "select 
				branch.branch_id,
				branch.branch_code,
				branch.mobile_number,
				branch.branch_name
				
				
				from branch
			
			where $condition
				order by branch.branch_name asc
			";
			$result = $this->db->query($query)->result_array();
			
			if( count($result) > 0 )
			{
				foreach($result as $row)
				{
					$response[] = array(
						"branchId"      => (int) $row['branch_id'],
						"branchCode"    => $row['branch_code'],
						"branchName"    => ucfirst($row['branch_name']),
						"mobileNumber"  => ucfirst($row['mobile_number']),
					);
				}
				header("Content-Type: application/json");
				echo json_encode($response);
				exit;
			}
			else
			{
				header("Content-Type: application/json");
				$response[] = array("httpCode" => 200 , "status" => 2, "message" => "No data found.");
				echo json_encode($response);
				exit;
			}
		}
	}

	#Cash On Delivery
	function cashOnDelivery()
	{
		$authUserName = isset($_SERVER['PHP_AUTH_USER']) ? $_SERVER['PHP_AUTH_USER'] :"";
		$authPassword = isset($_SERVER['PHP_AUTH_PW']) ? $_SERVER['PHP_AUTH_PW'] :"";
		$checkAuth = checkAuthorization($authUserName,$authPassword);
		
		if ($checkAuth == 0) #Check Authorization
		{
			$response = array("httpCode" => 401,"message" => "Unauthorized");
			header('HTTP/1.0 401 Unauthorized'); 
			header("Content-Type: application/json");
			echo json_encode($response);
			exit; 
		}
		else
		{
			$json = @file_get_contents('php://input');
			$jsondata = @json_decode($json);

			$customer_id = $jsondata->customer_id;
			
			if( !empty( $jsondata->customer_id ) )
			{
				$wallet_amount = isset($jsondata->wallet_amount) ? $jsondata->wallet_amount : NULL;
				$branch_id = isset($jsondata->branch_id) ? $jsondata->branch_id : NULL;
				$order_status = "Booked";
				
				#Document No Start here
				$documentQry = "select doc_num_id,prefix_name,suffix_name,next_number 
				from doc_document_numbering as dm
				left join sm_list_type_values ltv on 
					ltv.list_type_value_id = dm.doc_type
				where 1=1
					and dm.doc_document_type = 'customers-orders'
					and dm.branch_id = '".$branch_id."'
					and ltv.list_code = 'CUS_ORD' 
					and dm.active_flag = 'Y'
					and coalesce(dm.from_date,CURDATE()) <= CURDATE() 
					and coalesce(dm.to_date,CURDATE()) >= CURDATE()
				";
				$getDocumentData = $this->db->query($documentQry)->result_array();
				
				if(count($getDocumentData) > 0)
				{
					$prefixName = isset($getDocumentData[0]['prefix_name']) ? $getDocumentData[0]['prefix_name'] : NULL;
					$startingNumber = isset($getDocumentData[0]['next_number']) ? $getDocumentData[0]['next_number'] : NULL;
					$suffixName = isset($getDocumentData[0]['suffix_name']) ? $getDocumentData[0]['suffix_name'] : NULL;
					$documentNumber = $prefixName.''.$startingNumber.''.$suffixName;
					#Document No End here

					if( (isset($jsondata->payment_method) && $jsondata->payment_method == 1) ) //COD
					{
						$payment_type = NULL;;
						$paid_status = 'N';
						$payment_transaction_status = NULL;
					}
					else if( (isset($jsondata->payment_method) && $jsondata->payment_method == 3) ) //WALLET
					{
						$payment_type = NULL;
						$paid_status = 'Y';
						$payment_transaction_status = 'SUCCESS';
					}

					$headerData= array(
						'order_number'                  => $documentNumber,
						'customer_id'                   => $customer_id, #USER ID
						'address_id'                    => isset($jsondata->address_id) ? $jsondata->address_id : NULL,
						'ordered_date'                  => $this->date_time,
						'organization_id'               => isset($jsondata->organization_id) ? $jsondata->organization_id : NULL,
						'branch_id'                     => $branch_id, 
						'order_status'                  => $order_status,
						'order_type'                    => isset($jsondata->order_type) ? $jsondata->order_type : NULL, #1=> TAKE AWAY, 2=>Delivery
						'payment_method'                => isset($jsondata->payment_method) ? $jsondata->payment_method : NULL,
						'delivery_instructions'         => isset($jsondata->delivery_instructions) ? $jsondata->delivery_instructions : NULL,
						'packing_instructions'          => NULL,
						'payment_type'                  => $payment_type,
						'card_number'                   => NULL,
						'payment_transaction_ref_1'     => NULL,
						'payment_transaction_status'    => $payment_transaction_status,
						'currency'                      => CURRENCY_CODE,
						'delivery_options'              => isset($jsondata->delivery_options) ? serialize($jsondata->delivery_options) : NULL, //Ex.Contactless Delivery, Please don’t righ the bell, Don’t send Cutlery
						'paid_status'                   => $paid_status,
						'order_source'                  => isset($jsondata->order_source) ? strtoupper($jsondata->order_source) : NULL,
						'coupon_code'                   => isset($jsondata->coupon_code) ? $jsondata->coupon_code : NULL,
						'coupon_amount'                 => isset($jsondata->coupon_amount) ? $jsondata->coupon_amount : NULL,
						'wallet_amount'                 => $wallet_amount,

						'created_by'                    => '-1',
						'created_date'                  => $this->date_time,
						'last_updated_by'               => '-1',
						'last_updated_date'             => $this->date_time,
					);
					
					$this->db->insert('ord_order_interface_headers',$headerData);
					$interface_header_id = $this->db->insert_id();
					
					if($interface_header_id !="")
					{
						$interfaceHeaderData = array(
							'reference_header_id'   => $interface_header_id,	
						);
						$baseTableHeaderData = $headerData + $interfaceHeaderData;
						$this->db->insert('ord_order_headers', $baseTableHeaderData);
						$header_id = $this->db->insert_id();


						#Update Next Val DOC Number tbl start
						$nextValue = $startingNumber + 1;
						$doc_num_id = isset($getDocumentData[0]['doc_num_id']) ? $getDocumentData[0]['doc_num_id']:"";
						
						$UpdateData['next_number'] = $nextValue;
						$this->db->where('doc_num_id', $doc_num_id);
						$this->db->where('branch_id', $branch_id);
						$resultUpdateData = $this->db->update('doc_document_numbering', $UpdateData);
						#Update Next Val DOC Number tbl end

						foreach ($jsondata->lineData as $key=>$lineRow) 
						{
							$lineData = array(
								'reference_header_id'=> $interface_header_id,
								'product_id'	     => isset($lineRow->product_id) ? $lineRow->product_id:NULL,
								'price'	             => isset($lineRow->price) ? $lineRow->price:NULL,
								'quantity'	         => isset($lineRow->quantity) ? $lineRow->quantity:NULL,
								'offer_percentage'	 => isset($lineRow->offer_percentage) ? $lineRow->offer_percentage:NULL,
								'offer_amount'	     => isset($lineRow->offer_amount) ? $lineRow->offer_amount:NULL,
								'tax_percentage'	 => isset($lineRow->tax_percentage) ? $lineRow->tax_percentage:NULL,
								'line_status'	 	 => $order_status,
								'created_by'         => '-1',
								'created_date'       => $this->date_time,
								'last_updated_by'    => '-1',
								'last_updated_date'  => $this->date_time,
							);
							$this->db->insert('ord_order_interface_lines', $lineData);
							$interface_line_id = $this->db->insert_id();

							$interfaceLineData = array(
								'header_id'            => $header_id,	
								'reference_line_id'    => $interface_line_id,	
							);

							$baseTableLineData = $lineData + $interfaceLineData;

							$this->db->insert('ord_order_lines', $baseTableLineData);
							$line_id = $this->db->insert_id();


							#Item Ingredients Start here
							if( isset($jsondata->lineData[$key]->itemIngredients) && count($jsondata->lineData[$key]->itemIngredients) )
							{
								foreach ($jsondata->lineData[$key]->itemIngredients as $ingRow) 
								{
									$itemIngData = array(
										'header_id'	         => $header_id,	
										'line_id'	         => $line_id,
										'ingredient_id'	     => isset($ingRow->ingredient_id) ? $ingRow->ingredient_id:NULL,
										'ingredient_amount'	 => isset($ingRow->ingredient_amount) ? $ingRow->ingredient_amount:NULL,

										'created_by'         => '-1',
										'created_date'       => $this->date_time,
										'last_updated_by'    => '-1',
										'last_updated_date'  => $this->date_time,
									);
									$item_ing_id = $this->db->insert('ord_order_lines_ingredients', $itemIngData);
								}
							}
							#Item Ingredients End here
						}

						#Update Wallet Amount -if select wallet method
						if( (isset($jsondata->payment_method) && $jsondata->payment_method == 3) )
						{
							$UpdateQuery = "update cus_customer_wallet set 
							wallet_amount = wallet_amount - $wallet_amount, 
							last_updated_by = '-1',
							last_updated_date = $this->date_time,
							where customer_id = '".$customer_id."' ";
							$this->db->query($UpdateQuery);
						}
						#Update Wallet Amount -if select wallet method

						#Sent Order message start here
						$orderQuery = "select 
							customer.mobile_number,
							country.country_code
						from cus_consumers as customer

						left join per_user on per_user.reference_id = customer.customer_id

						left join geo_countries as country on 
							country.country_id = customer.country_id
						where per_user.user_id = '".$customer_id."' ";

						$getOrderDetails = $this->db->query($orderQuery)->result_array();
						$country_code = !empty($getOrderDetails[0]['country_code']) ? $getOrderDetails[0]['country_code'] :"+971";
						$mobile_number = isset($getOrderDetails[0]['mobile_number']) ? $getOrderDetails[0]['mobile_number'] :NULL;

						if($mobile_number !=NULL)
						{
							$otpMobileNumber = $mobile_number;
							$otpMessage = '#'. $documentNumber.' Your order has been placed. - Thank You.  '.strtoupper(SITE_NAME);
							$sendSMS = sendSMS($mobile_number,$otpMessage);
						}
						#Sent Order message end here

						$this->generatePDF($header_id);

						$response[] = array(	
							"httpCode" 		=> 200,
							"status"        => (int) 1,
							"message" 		=> "Order placed successfully!"
						);
						
						header("Content-Type: application/json");
						echo json_encode($response);
						exit;
					}
				}
				else
				{
					$response[] = array(	
						"httpCode" 		=> 200,
						"status"        => (int) 2,
						"message" 		=> "Order sequence does not exist, Order generation failed. Please contact to admin!"
					);
					
					header("Content-Type: application/json");
					echo json_encode($response);
					exit;
				}
			}
			else
			{
				$response[] = array("httpCode" => 400 ,"status"  => (int) 2, "Message" => "Bad Request" );
				header('HTTP/1.1 400', TRUE, 400);
				header("Content-Type: application/json");
				echo json_encode($response);
				exit;
			}
			exit;
		}
	}

	/* function generatePDF($id="")
    {
		$page_data['id'] = $id;
		
		$page_data['data']  = $this->orders_model->getOrderDetails($id);
		$page_data['LineData'] = $this->orders_model->getOrderItemsPrint($id);
		
		ob_start();
		$html = ob_get_clean();
		$html = utf8_encode($html);

		$html = $this->load->view('backend/orders/printReceipt',$page_data,true);

        $mpdf = new \Mpdf\Mpdf();
        $mpdf->WriteHTML($html);
		$mpdf->Output('uploads/auto_generate_pdf/'.$id.'.pdf', 'F');
	} */

	function generatePDF($id="")
    {
		$page_data['id'] = $id;
		
		$page_data['data']  = $this->orders_model->getOrderDetails($id);
		$page_data['LineData'] = $this->orders_model->getOrderItemsPrint($id);
		
		ob_start();
		/* $html = ob_get_clean();
		$html = utf8_encode($html);

		$html = $this->load->view('backend/orders/printReceipt',$page_data,true);

        $mpdf = new \Mpdf\Mpdf();
        $mpdf->WriteHTML($html);
		$mpdf->Output('uploads/auto_generate_pdf/'.$id.'.pdf', 'F'); */

		#Print Receipt HTML Start
		$html = ob_get_clean();
		$html = utf8_encode($html);
		$html = $this->load->view('backend/orders/printReceipt',$page_data,true);
        
		$mpdf = new \Mpdf\Mpdf([		
			#'setAutoTopMargin' => 'stretch',
			#'setAutoBottomMargin' => 'stretch',
			'curlAllowUnsafeSslRequests' => true,
		]);

        $mpdf->WriteHTML($html);
		$mpdf->Output('uploads/auto_generate_pdf/'.$id.'.pdf', 'F');
		#Print Receipt HTML End

		#KOT Bill start start
		$kot_html = ob_get_clean();
		$kot_html = utf8_encode($kot_html);
		$kot_html = $this->load->view('backend/orders/kotPrint',$page_data,true);

		$kot_mpdf = new \Mpdf\Mpdf([		
			#'setAutoTopMargin' => 'stretch',
			#'setAutoBottomMargin' => 'stretch',
			'curlAllowUnsafeSslRequests' => true,
		]);
        $kot_mpdf->WriteHTML($kot_html);
		$kot_mpdf->Output('uploads/auto_generate_pdf/kot/'.$id.'.pdf', 'F');
		#KOT Bill start end
	}

	#Best Selling Items 
	/* function bestSellingItems()
	{
		$authUserName = isset($_SERVER['PHP_AUTH_USER']) ? $_SERVER['PHP_AUTH_USER'] :"";
		$authPassword = isset($_SERVER['PHP_AUTH_PW']) ? $_SERVER['PHP_AUTH_PW'] :"";
		$checkAuth = checkAuthorization($authUserName,$authPassword);
		
		if ($checkAuth == 0) #Check Authorization
		{
			$response = array("httpCode" => 401,"message" => "Unauthorized");
			header('HTTP/1.0 401 Unauthorized'); 
			header("Content-Type: application/json");
			echo json_encode($response);
			exit; 
		}
		else
		{
			$json = @file_get_contents('php://input');
			$jsondata = @json_decode($json);

			$branch_id = $jsondata->branch_id;
			
			$currentTime = date("H:i:s");

			$query = "select 
				line_tbl.product_id,
				count(line_tbl.product_id) as product_count,
				branch.branch_id,
				branch.branch_name,
				categories.category_id,
				categories.category_name,
				cat1.list_value as category_name_1,
				cat2.list_value as category_name_2,
				cat3.list_value as category_name_3,
				items.item_id,
				items.item_name,
				items.item_description,
				branch_items.item_price,
				branch_items.available_quantity,
				branch_items.minimum_order_quantity,
				branch_items.from_time_am,
				branch_items.to_time_am,
				branch_items.from_time_pm,
				branch_items.to_time_pm,
				organization.organization_id,
				organization.organization_code,
				organization.organization_name,
                offers.offer_percentage
				from ord_order_lines as line_tbl 
				
				left join ord_order_headers as header_tbl on
					header_tbl.header_id = line_tbl.header_id

				left join inv_item_branch_assign as branch_items on 
					branch_items.item_id = line_tbl.product_id

				left join inv_sys_items as items on items.item_id = branch_items.item_id
				left join inv_categories as categories on categories.category_id = items.category_id
				left join branch on branch.branch_id = branch_items.branch_id
				left join inv_item_offers as offers on offers.branch_id = branch.branch_id
				left join org_organizations as organization on organization.organization_id = branch.organization_id

				left join sm_list_type_values as cat1 on
					cat1.list_type_value_id = categories.cat_level_1

				left join sm_list_type_values as cat2 on
					cat2.list_type_value_id = categories.cat_level_2

				left join sm_list_type_values as cat3 on
					cat3.list_type_value_id = categories.cat_level_3

			where 1=1
				and header_tbl.branch_id = '".$branch_id."'
				group by line_tbl.product_id order by product_count desc limit 10";
				
			$result = $this->db->query($query)->result_array();

			if( count($result) > 0 )
			{
				foreach($result as $row)
				{
					if(file_exists("uploads/products/".$row['item_id'].'.png') )
					{
						$photo_url = base_url().'uploads/products/'.$row['item_id'].'.png';
					}
					else
					{
						$photo_url = base_url().'uploads/no-image.png';
					}

					if( 
						($row['from_time_am'] <= $currentTime && $row['to_time_am'] >= $currentTime) 
						|| ($row['from_time_pm'] <= $currentTime && $row['to_time_pm'] >= $currentTime) 
					)
					{
						$itemAvaialbleFlag = 'YES';
					}
					else
					{
						$itemAvaialbleFlag = 'NO';
					}

					$offerAmount = $row['offer_percentage'] / 100 * $row['item_price'];
               		$itemOfferPrice = $row['item_price'] - $offerAmount;

					$response[] = array(
						"productCount"   => (int) $row['product_count'],
						"organizationId"   => (int) $row['organization_id'],
						"organizationCode" => ucfirst($row['organization_code']),
						"organizationName" => ucfirst($row['organization_name']),
						"branchId"         => (int) $row['branch_id'],
						"branchName"       => ucfirst($row['branch_name']),
						"categoryId"       => (int) $row['category_id'],
						"categoryName"     => ucfirst($row['category_name']),
						"categoryLevel1"     => ucfirst($row['category_name_1']),
						"categoryLevel2"     => ucfirst($row['category_name_2']),
						"categoryLevel3"     => ucfirst($row['category_name_3']),
						"itemId"           => (int) $row['item_id'],
						"itemName"         => ucfirst($row['item_name']),
						"itemDescription"  => ucfirst($row['item_description']),
						"itemPrice"        => number_format($row['item_price'],DECIMAL_VALUE,'.',''),
						"availableQty"     => (int) $row['available_quantity'],
						"minimumOrderQty"  => (int) $row['minimum_order_quantity'],
						"fromTimeAm"       => $row['from_time_am'],
						"toTimeAm"         => $row['to_time_am'],
						"fromTimePm"       => $row['from_time_pm'],
						"toTimePm"         => $row['to_time_pm'],
						"offerPercentage"  => $row['offer_percentage'],
                    	"offerAmount"  	   => number_format($offerAmount,DECIMAL_VALUE,'.',''),
                   		"itemOfferPrice"   => number_format($itemOfferPrice,DECIMAL_VALUE,'.',''),
						"itemImage"        => $photo_url,
						//"itemAvaialbleStatus"  => $itemAvaialbleFlag,
					);
				}
				header("Content-Type: application/json");	
				echo json_encode($response);
				exit;
			}
			else
			{
				header("Content-Type: application/json");
				$response[] = array("httpCode" => 200 , "status" => 2 ,"message" => "No data found.");
				echo json_encode($response);
				exit;
			}
		}
	} */

	#best Selling Items
	function bestSellingItems($branch_id="")
	{
		$authUserName = isset($_SERVER['PHP_AUTH_USER']) ? $_SERVER['PHP_AUTH_USER'] :"";
		$authPassword = isset($_SERVER['PHP_AUTH_PW']) ? $_SERVER['PHP_AUTH_PW'] :"";
		$checkAuth = checkAuthorization($authUserName,$authPassword);
		
		if ($checkAuth == 0) #Check Authorization
		{
			$response = array("httpCode" => 401,"message" => "Unauthorized");
			header('HTTP/1.0 401 Unauthorized'); 
			header("Content-Type: application/json");
			echo json_encode($response);
			exit; 
		}
		else
		{
			$currentTime = date("H:i:s");
			$branch_id = $branch_id;

			/* $condition = " 1=1
				and branch_items.branch_id = '".$branch_id."'
				and branch_items.best_selling = 'Y'
				and branch_items.active_flag = 'Y' 
				and items.active_flag = 'Y' 
				and categories.active_flag = 'Y'
				and branch.default_branch = 'Y' 
				and branch.active_flag = 'Y' 
				"; */

				$query = "select 
					branch.branch_id,
					branch.branch_name,
					branch.minimum_order_value,
					branch.break_fast_from,
					branch.break_fast_to,
					branch.lunch_from,
					branch.lunch_to,
					branch.dinner_from,
					branch.dinner_to,

					categories.category_id,
					categories.category_name,
					items.item_id,
					items.item_name,
					items.item_description,
					branch_items.item_price,
					branch_items.available_quantity,
					branch_items.minimum_order_quantity,

					coalesce(branch_items.breakfast_flag,'N') as breakfast_flag,
					coalesce(branch_items.lunch_flag,'N') as lunch_flag,
					coalesce(branch_items.dinner_flag,'N') as dinner_flag,

					organization.organization_id,
					organization.organization_code,
					organization.organization_name,
					cat1.list_value as category_name_1,
					cat2.list_value as category_name_2,
					cat3.list_value as category_name_3,
					offers.offer_percentage,
					(
						case
							when '".$currentTime."' between branch.break_fast_from AND branch.break_fast_to then 'BreakFast'
							when '".$currentTime."' between branch.lunch_from AND branch.lunch_to then 'Lunch'
							when '".$currentTime."' between branch.dinner_from AND branch.dinner_to then 'Dinner'
							else ''
						end 
					) food_time

					
					from inv_item_branch_assign as branch_items

				left join inv_sys_items as items on items.item_id = branch_items.item_id
				left join inv_categories as categories on categories.category_id = items.category_id
				left join branch on branch.branch_id = branch_items.branch_id
				left join inv_item_offers as offers on offers.branch_id = branch.branch_id
				left join org_organizations as organization on organization.organization_id = branch.organization_id
				
				left join sm_list_type_values as cat1 on
					cat1.list_type_value_id = categories.cat_level_1

				left join sm_list_type_values as cat2 on
					cat2.list_type_value_id = categories.cat_level_2

				left join sm_list_type_values as cat3 on
					cat3.list_type_value_id = categories.cat_level_3
					
				where
				1=1
				and branch_items.branch_id = '".$branch_id."'
				and branch_items.best_selling = 'Y'
				and branch_items.active_flag = 'Y' 
				and items.active_flag = 'Y' 
				and categories.active_flag = 'Y'
				and branch.default_branch = 'Y' 
				and branch.active_flag = 'Y' 
				
				group by branch_id,category_id,item_id

				
				order by items.item_description asc
			";
			
			$result = $this->db->query($query)->result_array();

			if( count($result) > 0 )
			{
				foreach($result as $row)
				{
					if(file_exists("uploads/products/".$row['item_id'].'.png') )
					{
						$photo_url = base_url().'uploads/products/'.$row['item_id'].'.png';
					}
					else
					{
						$photo_url = base_url().'uploads/no-image.png';
					}

					$ingredientsQry = "select line_tbl.* from inv_item_ingredient_line as line_tbl

						left join inv_item_ingredient_header as header_tbl on 
							header_tbl.ing_header_id = line_tbl.ing_header_id

							where 1=1
							and header_tbl.active_flag='Y'
							and line_tbl.active_flag='Y'
							and header_tbl.branch_id='".$row['branch_id']."'
							and line_tbl.item_id='".$row['item_id']."'
					";
					$getIngredients = $this->db->query($ingredientsQry)->result_array();

					$ingResult = [];

					foreach($getIngredients as $ing)
					{
						$ingResult[] = array(
							"ingLineId"               => (int) $ing['ing_line_id'],
							"ingredientName"          => $ing['ingredient_name'],
							"ingredientDescription"   => $ing['ingredient_description'],
							"ingredientCost"   		  => number_format($ing['ingredient_cost'],DECIMAL_VALUE,'.',''),
						);
					}

					$offerAmount = $row['offer_percentage'] / 100 * $row['item_price'];
					$itemOfferPrice = $row['item_price'] - $offerAmount;

					if(!empty($row['food_time']) && $row['food_time'] == 'BreakFast')
					{
						$itemAvailableFlag = $row['breakfast_flag'];
					}
					else if(!empty($row['food_time']) && $row['food_time'] == 'Lunch')
					{
						$itemAvailableFlag = $row['lunch_flag'];
					}
					else if(!empty($row['food_time']) && $row['food_time'] == 'Dinner')
					{
						$itemAvailableFlag = $row['dinner_flag'];
					}
					else
					{
						$itemAvailableFlag = 'N';
					}


					$response[] = array(
						"organizationId"   => (int) $row['organization_id'],
						"organizationCode" => ucfirst($row['organization_code']),
						"organizationName" => ucfirst($row['organization_name']),
						"branchId"         => (int) $row['branch_id'],
						"branchName"       => ucfirst($row['branch_name']),
						"categoryId"       => (int) $row['category_id'],
						"categoryName"     => ucfirst($row['category_name']),

						"categoryLevel1"     => ucfirst($row['category_name_1']),
						"categoryLevel2"     => ucfirst($row['category_name_2']),
						"categoryLevel3"     => ucfirst($row['category_name_3']),

						"itemId"           => (int) $row['item_id'],
						"itemName"         => ucfirst($row['item_name']),
						"itemDescription"  => ucfirst($row['item_description']),
						"itemPrice"        => number_format($row['item_price'],DECIMAL_VALUE,'.',''),
						"availableQty"     => (int) $row['available_quantity'],
						"minimumOrderQty"  => (int) $row['minimum_order_quantity'],
						"minimumOrderValue" => number_format($row['minimum_order_value'],DECIMAL_VALUE,'.',''),
						
						"offerPercentage"  => $row['offer_percentage'],
						"offerAmount"  	   => number_format($offerAmount,DECIMAL_VALUE,'.',''),
						"itemOfferPrice"   => number_format($itemOfferPrice,DECIMAL_VALUE,'.',''),

						"break_fast_from"  => $row['break_fast_from'],
						"break_fast_to"    => $row['break_fast_to'],
						"lunch_from"   	   => $row['lunch_from'],
						"lunch_to"   	   => $row['lunch_to'],
						"dinner_from"      => $row['dinner_from'],
						"dinner_to"   	   => $row['dinner_to'],

						"breakfastFlag"    => $row['breakfast_flag'],
						"lunchFlag"   	   => $row['lunch_flag'],
						"dinnerFlag"   	   => $row['dinner_flag'],

						"itemAvailableFlag" => $itemAvailableFlag,
						"itemImage"        => $photo_url,
						"itemIngredients"  => $ingResult,
					);
				}
				header("Content-Type: application/json");	
				echo json_encode($response);
				exit;
			}
			else
			{
				header("Content-Type: application/json");
				$response[] = array("httpCode" => 200 , "status" => 2, "message" => "No data found.");
				echo json_encode($response);
				exit;
			}
		}
	}

	#custmerFeedback
	function custmerFeedback()
	{
		$authUserName = isset($_SERVER['PHP_AUTH_USER']) ? $_SERVER['PHP_AUTH_USER'] :"";
		$authPassword = isset($_SERVER['PHP_AUTH_PW']) ? $_SERVER['PHP_AUTH_PW'] :"";
		$checkAuth = checkAuthorization($authUserName,$authPassword);
		
		if ($checkAuth == 0) #Check Authorization
		{
			$response = array("httpCode" => 401,"message" => "Unauthorized");
			header('HTTP/1.0 401 Unauthorized'); 
			header("Content-Type: application/json");
			echo json_encode($response);
			exit; 
		}
		else
		{
			$json = @file_get_contents('php://input');
			$jsondata = @json_decode($json);
			
			if( !empty( $jsondata->customer_name ) )
			{
				$postData['customer_name'] = $jsondata->customer_name;
				$postData['email'] = $jsondata->email;
				$postData['mobile_number'] = $jsondata->mobile_number;
				$postData['branch_id'] = $jsondata->branch_id;
				$postData['message'] = $jsondata->message;
				
				$postData['created_by'] = '-1';
				$postData['created_date'] = $this->date_time;
				$postData['last_updated_by'] = '-1';
				$postData['last_updated_date'] = $this->date_time;
				
				$this->db->insert('cus_feedback', $postData);
				$id = $this->db->insert_id();
				
				if($id !="")
				{
					$response[] = array(	
						"httpCode" 		=> 200,
						"status"        => (int) 1,
						"message" 		=> "Thanks for your feedback!"
					);
					
					header("Content-Type: application/json");
					echo json_encode($response);
					exit;
				}
				}
			else
			{
				$response[] = array("httpCode" => 400 ,"status"  => (int) 2, "Message" => "Bad Request" );
				header('HTTP/1.1 400', TRUE, 400);
				header("Content-Type: application/json");
				echo json_encode($response);
				exit;
			}
			exit;
		}
	}

	#Re Order
	public function reOrder()
    {
		$authUserName = isset($_SERVER['PHP_AUTH_USER']) ? $_SERVER['PHP_AUTH_USER'] :"";
		$authPassword = isset($_SERVER['PHP_AUTH_PW']) ? $_SERVER['PHP_AUTH_PW'] :"";
		$checkAuth = checkAuthorization($authUserName,$authPassword);
		
		if ($checkAuth == 0) #Check Authorization
		{
			$response = array("httpCode" => 401,"message" => "Unauthorized");
			header('HTTP/1.0 401 Unauthorized'); 
			header("Content-Type: application/json");
			echo json_encode($response);
			exit; 
		}
		else
		{
			$json = @file_get_contents('php://input');
			$jsondata = @json_decode($json);

			$header_id = $jsondata->header_id;
			$branch_id = $jsondata->branch_id;
			
			$currentTime = date("H:i:s");

			$query = "select 
				header_tbl.customer_id,
				header_tbl.header_id,
				line_tbl.line_id,
				line_tbl.product_id,
				line_tbl.quantity,
				branch.branch_id,
				branch.branch_name,
				categories.category_id,
				categories.category_name,
				items.item_id,
				items.item_name,
				items.item_description,
				branch_items.item_price,
				branch_items.available_quantity,
				branch_items.minimum_order_quantity,
				branch_items.from_time_am,
				branch_items.to_time_am,
				branch_items.from_time_pm,
				branch_items.to_time_pm,
				organization.organization_id,
				organization.organization_code,
				organization.organization_name,
				offers.offer_percentage
				from ord_order_lines as line_tbl
				
				left join ord_order_headers as header_tbl on
					header_tbl.header_id = line_tbl.header_id

				left join inv_item_branch_assign as branch_items on 
					(branch_items.item_id = line_tbl.product_id and branch_items.branch_id = header_tbl.branch_id)

				left join inv_sys_items as items on items.item_id = branch_items.item_id
				left join inv_categories as categories on categories.category_id = items.category_id
				left join branch on branch.branch_id = branch_items.branch_id
				left join inv_item_offers as offers on offers.branch_id = branch.branch_id

				left join org_organizations as organization on organization.organization_id = branch.organization_id


			where 1=1
				and header_tbl.header_id = '".$header_id."'
				and header_tbl.branch_id = '".$branch_id."'
				";
				
			$result = $this->db->query($query)->result_array();

			if( count($result) > 0 )
			{
				foreach($result as $row)
				{
					if(file_exists("uploads/products/".$row['item_id'].'.png') )
					{
						$photo_url = base_url().'uploads/products/'.$row['item_id'].'.png';
					}
					else
					{
						$photo_url = base_url().'uploads/no-image.png';
					}

					if( 
						($row['from_time_am'] <= $currentTime && $row['to_time_am'] >= $currentTime) 
						|| ($row['from_time_pm'] <= $currentTime && $row['to_time_pm'] >= $currentTime) 
					)
					{
						$itemAvaialbleFlag = 'YES';
					}
					else
					{
						$itemAvaialbleFlag = 'NO';
					}

					$offerAmount = $row['offer_percentage'] / 100 * $row['item_price'];
					$itemOfferPrice = $row['item_price'] - $offerAmount;

					$response[] = array(
						"customerId"       => (int) $row['customer_id'],
						"orderHeaderId"    => (int) $row['header_id'],
						"orderLineId"      => (int) $row['line_id'],
						"organizationId"   => (int) $row['organization_id'],
						"organizationCode" => ucfirst($row['organization_code']),
						"organizationName" => ucfirst($row['organization_name']),
						"branchId"         => (int) $row['branch_id'],
						"branchName"       => ucfirst($row['branch_name']),
						"categoryId"       => (int) $row['category_id'],
						"categoryName"     => ucfirst($row['category_name']),
						"itemId"           => (int) $row['item_id'],
						"itemName"         => ucfirst($row['item_name']),
						"itemDescription"  => ucfirst($row['item_description']),
						"itemPrice"        => number_format($row['item_price'],DECIMAL_VALUE,'.',''),
						"availableQty"     => (int) $row['available_quantity'],
						"minimumOrderQty"  => (int) $row['minimum_order_quantity'],
						"quantity"  	   => (int) $row['quantity'],
						"fromTimeAm"       => $row['from_time_am'],
						"toTimeAm"         => $row['to_time_am'],
						"fromTimePm"       => $row['from_time_pm'],
						"toTimePm"         => $row['to_time_pm'],
						"itemImage"        => $photo_url,
						"itemAvaialbleStatus"  => $itemAvaialbleFlag,

						"offerPercentage"  => $row['offer_percentage'],
						"offerAmount"  	   => number_format($offerAmount,DECIMAL_VALUE,'.',''),
						"itemOfferPrice"   => number_format($itemOfferPrice,DECIMAL_VALUE,'.',''),
					);

					#Insert Cart Start
					$postData['organization_id'] = $row['organization_id'];
					$postData['branch_id'] = $row['branch_id'];
					$postData['customer_id'] = $row['customer_id'];
					$postData['item_id'] = $row['item_id'];
					$postData['quantity'] = $row['quantity'];

					/* $checkExistQry = "select cart_items.cart_id from ord_cart_items as cart_items 
						where organization_id ='".$row['organization_id']."'
						and	branch_id ='".$row['branch_id']."'
						and	customer_id ='".$row['customer_id']."'
						and	item_id ='".$row['item_id']."'
					";

					$chkExist = $this->db->query($checkExistQry)->result_array(); */

					$this->db->where('customer_id', $postData['customer_id']);
					$this->db->where('branch_id', $postData['branch_id']);
					$this->db->where('item_id', $postData['item_id']);
					$this->db->delete('ord_cart_items');

					//if( count($chkExist) == 0 )
					//{
						$postData['active_flag'] = $this->active_flag;
						$postData['created_by'] = '-1';
						$postData['created_date'] = $this->date_time;
						$postData['last_updated_by'] = '-1';
						$postData['last_updated_date'] = $this->date_time;
						
						$this->db->insert('ord_cart_items', $postData);
						$id = $this->db->insert_id();	
					//}
					#Insert Cart Start end
				}
				header("Content-Type: application/json");	
				echo json_encode($response);
				exit;
			}
			else
			{
				header("Content-Type: application/json");
				$response[] = array("httpCode" => 200 , "status" => 2, "message" => "No data found.");
				echo json_encode($response);
				exit;
			}

			$postData['organization_id'] = $jsondata->organization_id;
			$postData['branch_id'] = $jsondata->branch_id;
			$postData['customer_id'] = $jsondata->customer_id;
			$postData['item_id'] = $jsondata->item_id;
			$postData['quantity'] = $jsondata->quantity;
				
			#Check Exist
			$checkExistQry = "select cart_items.cart_id from ord_cart_items as cart_items 
				where organization_id ='".$postData['organization_id']."'
				and	branch_id ='".$postData['branch_id']."'
				and	customer_id ='".$postData['customer_id']."'
				and	item_id ='".$postData['item_id']."'
			";

			$chkExist = $this->db->query($checkExistQry)->result_array();

			if( count($chkExist) == 0 )
			{
				$postData['active_flag'] = $this->active_flag;
				$postData['created_by'] = '-1';
				$postData['created_date'] = $this->date_time;
				$postData['last_updated_by'] = '-1';
				$postData['last_updated_date'] = $this->date_time;
				
				$this->db->insert('ord_cart_items', $postData);
				$id = $this->db->insert_id();
				
				if($id !="")
				{
					$response[] = array(	
						"httpCode" 		=> 200,
						"status"        => (int) 1,
						"message" 		=> "Item added to cart!"
					);
					
					header("Content-Type: application/json");
					echo json_encode($response);
					exit;
				}
			}
			else
			{
				$quantity = $jsondata->quantity;
				
				$updateQry = "update ord_cart_items
						SET quantity = quantity + $quantity, 
						last_updated_by = '-1',
						last_updated_date = '".$this->date_time."'
						where organization_id ='".$postData['organization_id']."'
						and	branch_id ='".$postData['branch_id']."'
						and	customer_id ='".$postData['customer_id']."'
						and	item_id ='".$postData['item_id']."'";
					
				$result = $this->db->query($updateQry);

				$response[] = array(	
					"httpCode" 		=> 200,
					"status"        => (int) 1,
					"message" 		=> "Item added to cart!"
				);
				
				header("Content-Type: application/json");
				echo json_encode($response);
				exit;
			}
		}
    }

	#customer wallet
	public function customerWallet($customer_id="")
	{
		$authUserName = isset($_SERVER['PHP_AUTH_USER']) ? $_SERVER['PHP_AUTH_USER'] :"";
		$authPassword = isset($_SERVER['PHP_AUTH_PW']) ? $_SERVER['PHP_AUTH_PW'] :"";
		$checkAuth = checkAuthorization($authUserName,$authPassword);
		
		if ($checkAuth == 0) #Check Authorization
		{
			$response = array("httpCode" => 401,"message" => "Unauthorized");
			header('HTTP/1.0 401 Unauthorized'); 
			header("Content-Type: application/json");
			echo json_encode($response);
			exit; 
		}
		else
		{
			if(!empty($customer_id ))
			{
				$query = "select  cus_customer_wallet.wallet_amount from cus_customer_wallet
				
				where cus_customer_wallet.customer_id ='".$customer_id ."' ";
				$resultData = $this->db->query($query)->result_array();
									
				$customerWallet = isset($resultData[0]['wallet_amount']) ? $resultData[0]['wallet_amount'] : 0;

				$response[] = array(	
					"httpCode" 		  => 200 ,
					"wallet_amount"   => number_format($customerWallet,DECIMAL_VALUE,'.','')					
				);
				
				header("Content-Type: application/json");
				echo json_encode($response);
				exit;
			}
			else
			{
				$response[] = array("httpCode" => 400 , "Message" => "Bad Request" );
				header('HTTP/1.1 400', TRUE, 400);
				header("Content-Type: application/json");
				echo json_encode($response);
				exit;
			}
			exit;
		}
	}

	#My Orders
	function myOrders($user_id = '')
	{
		$authUserName = isset($_SERVER['PHP_AUTH_USER']) ? $_SERVER['PHP_AUTH_USER'] :"";
		$authPassword = isset($_SERVER['PHP_AUTH_PW']) ? $_SERVER['PHP_AUTH_PW'] :"";
		$checkAuth = checkAuthorization($authUserName,$authPassword);
		
		if ($checkAuth == 0) #Check Authorization
		{
			$response = array("httpCode" => 401,"message" => "Unauthorized");
			header('HTTP/1.0 401 Unauthorized'); 
			header("Content-Type: application/json");
			echo json_encode($response);
			exit; 
		}
		else
		{
			$query = "select 
			header_tbl.*,

			branch.branch_id,
			branch.branch_name,
			payment_type.payment_type,

			customer.customer_name,
			customer.mobile_number,
			country.country_code,
			customer_address.address_name,
			customer_address.address1,
			customer_address.address2,
			customer_address.address3,
			customer_address.land_mark,
			customer_address.address_type,
			customer_address.postal_code,
			line_tbl.cancel_status,
			sum(line_tbl.price) as price,
			sum(line_tbl.price * line_tbl.quantity) as bill_amount,

			round( sum((line_tbl.offer_percentage / 100) * (line_tbl.quantity * line_tbl.price)),2) as offer_amount,
			
			round( sum((line_tbl.quantity * line_tbl.price) - ((line_tbl.offer_percentage / 100) * (line_tbl.quantity * line_tbl.price))),2) as linetotal,
			round( sum( ((line_tbl.quantity * line_tbl.price) - ((line_tbl.offer_percentage / 100) * (line_tbl.quantity * line_tbl.price))) * (tax_percentage/100)),2) as tax_value
			
			from ord_order_headers as header_tbl

			left join per_user on per_user.user_id = header_tbl.customer_id
			left join cus_consumers as customer on customer.customer_id = per_user.reference_id

			left join ord_order_lines as line_tbl on line_tbl.header_id = header_tbl.header_id
			
			left join pay_payment_types as payment_type on payment_type.payment_type_id = header_tbl.payment_method
			left join branch on branch.branch_id = header_tbl.branch_id

			left join cus_customer_address as customer_address on 
				customer_address.customer_address_id = header_tbl.address_id

			left join geo_countries as country on 
				country.country_id = customer.country_id

				WHERE 1=1
				and header_tbl.customer_id = '".$user_id."'
				
				and (line_tbl.cancel_status = 'N' or header_tbl.cancel_status = 'Y')
				group by line_tbl.header_id,line_tbl.cancel_status
				order by header_tbl.header_id desc
			";
			
			$result = $this->db->query($query)->result_array();

			if( count($result) > 0 )
			{
				foreach($result as $row)
				{
					$LineQuery = "select 
							ord_order_lines.line_id,
							ord_order_lines.quantity,
							ord_order_lines.offer_percentage,
							
							ord_order_lines.tax_percentage,
							
							ord_order_lines.product_id,
							ord_order_lines.cancel_status,
							ord_order_lines.line_status,
							ord_order_headers.header_id,
							products.item_name,
							products.item_description,
							ord_order_lines.price,

							round((offer_percentage / 100) * (ord_order_lines.quantity * ord_order_lines.price),2) as offer_amount,
							round((ord_order_lines.quantity * ord_order_lines.price),2) as totalOrderAmount,
							round( (ord_order_lines.quantity * ord_order_lines.price) - ((offer_percentage / 100) * (ord_order_lines.quantity * ord_order_lines.price)),2) as linetotal,
							round(((ord_order_lines.quantity * ord_order_lines.price) - ((offer_percentage / 100) * (ord_order_lines.quantity * ord_order_lines.price))) * (tax_percentage/100),2) as tax_value
							
						from ord_order_lines
							
						left join ord_order_headers on 
							ord_order_headers.header_id = ord_order_lines.header_id
						
						left join inv_sys_items as products on 
							products.item_id = ord_order_lines.product_id

						where 
						ord_order_lines.header_id='".$row['header_id']."'";
					
					$LineData = $this->db->query($LineQuery)->result_array();

					$LineItemsResult = [];
					$totalTax = 0;
					$subTotal = 0;
					$offerAmount = 0;
					$totalOrderAmount = 0;

					foreach($LineData as $lineItems)
					{
						if($lineItems['cancel_status'] == 'Y')
						{
							$totalTax += 0;
							$subTotal += 0;
							$offerAmount += 0;
							$totalOrderAmount += 0;
						}
						else
						{
							$totalTax += $lineItems['tax_value'];
							$subTotal += $lineItems['linetotal'];
							$offerAmount += $lineItems['offer_amount'];
							$totalOrderAmount += $lineItems['totalOrderAmount'];
						}

						$ingredientsQry = "select 
								ord_ing_tbl.ingredient_amount,
								ing_line_tbl.ingredient_name,
								ing_line_tbl.ingredient_description from ord_order_lines_ingredients as ord_ing_tbl
							left join inv_item_ingredient_line as ing_line_tbl on 
								ing_line_tbl.ing_line_id = ord_ing_tbl.ingredient_id
							where 
							header_id='".$row['header_id']."'
							and line_id='".$lineItems['line_id']."'
							";
						$checkIngredients = $this->db->query($ingredientsQry)->result_array();

						$itemIngredients = [];

						if(count($checkIngredients) > 0)
						{	
							foreach($checkIngredients as $ingredient) 
							{
								$itemIngredients[] = array(
									"ingredientName"	    => $ingredient['ingredient_name'],
									"ingredientDescription"	=> $ingredient['ingredient_description'],
									"ingredientCost"		=> number_format($ingredient['ingredient_amount'],DECIMAL_VALUE,'.',''),
								);
							}
						}

						$LineItemsResult[] = array(
							"itemCode"				=> $lineItems['item_name'],
							"itemName"				=> $lineItems['item_description'],
							"quantity"				=> (int) $lineItems['quantity'],
							"price"					=> number_format($lineItems['price'],DECIMAL_VALUE,'.',''),
							"offerAmount"			=> number_format($lineItems['offer_amount'],DECIMAL_VALUE,'.',''),
							"taxValue"				=> number_format($lineItems['tax_value'],DECIMAL_VALUE,'.',''),
							"lineTotal"				=> number_format($lineItems['linetotal'],DECIMAL_VALUE,'.',''),
							
							#"ingredientAmount"		=> (double)$lineValue['ingredient_amount'],
							#"ingredients"			=> $ingredients_name,
							"cancelFlag"	        => $lineItems['cancel_status'],
							"lineStatus"	        => $lineItems['line_status'],
							"itemIngredients"	    => $itemIngredients,
						);
					}
					
					$totalAmount = $subTotal + $totalTax;
					$roundedAmount = round($totalAmount);
					$roundedValue = $roundedAmount - $totalAmount ;

					$isFavOrder = $this->db->query("select favourite_id from ord_favourite_orders 
					where header_id = ".$row['header_id']." and customer_id = ".$user_id."")->result_array();
					if (count($isFavOrder) == 1) {
						$isFavOrder = true;
					}
					else{
						$isFavOrder = false;
					}

					if($row['cancel_status'] == 'Y')
					{
						$bill_amount = 0;
					}
					else
					{
						#$totalTax += $lineItems['tax_value'];
						$bill_amount = round($row['linetotal'] + $row['tax_value']);
					}

					if($row['paid_status'] == 'Y')
					{
						$paid_status = 'Paid';
					}else{
						$paid_status = 'Unpaid';
					}

					if($row['cancel_status'] == 'Y')
					{
						$cancel_status = 'Cancelled';
					}else{
						$cancel_status = 'Not Cancelled';
					}

					if($row['order_type'] == 1)
					{
						$orderType = 'Take Away';
					}
					else if($row['order_type'] == 2)
					{
						$orderType = 'Deliver';
					}
		
					$payableAmount = $totalAmount + $roundedValue;

					$response[] = array(
						"headerId"	 				=> (int)$row['header_id'],
						"branchName"				=> $row['branch_name'],
						"branchId"					=> (int)$row['branch_id'],
						"orderNumber"	 			=> $row['order_number'],
						"orderStatus"				=> $row['order_status'],

						"acceptedDate"				=> $row['accepted_date'],
						"preparingDate"				=> $row['preparing_date'],
						"outForDeliveryDate"		=> $row['out_for_delivery_date'],
						"deliveredDate"				=> $row['delivered_date'],
						"walletAmount"				=> (double)$row['wallet_amount'],
						"orderedDate"				=> $row['ordered_date'],
						"cancelledDate"				=> $row['cancel_date'],
						"paymentMethod"				=> $row['payment_type'],
						"paymentStatus"				=> $row['payment_transaction_status'], #Payment => PENDING, SUCCESS
						"paidStatus"				=> $paid_status,#COD => 0-Unpaid, 1=>Paid
						
						"addressName"				=> $row['address_name'],
						"address1"					=> $row['address1'],
						"address2"			    	=> $row['address2'],
						"address3"					=> $row['address3'],
						"landMark"					=> $row['land_mark'],
						"addressType"				=> $row['address_type'],
						"postalCode"				=> $row['postal_code'],
						"orderSource"				=> $row['order_source'],
						"cancelStatus"				=> $cancel_status,
						"orderType"				    => $orderType,
						"isFavOrder"				=> $isFavOrder,
						"offerAmount"				=> number_format($offerAmount ,DECIMAL_VALUE,'.',''),
						"totalOrderAmount"			=> number_format($totalOrderAmount ,DECIMAL_VALUE,'.',''),
						"subTotal"					=> number_format($subTotal,DECIMAL_VALUE,'.',''),
						"cgst_value"				=> number_format($totalTax / 2,DECIMAL_VALUE,'.',''),
						"sgst_value"				=> number_format($totalTax / 2,DECIMAL_VALUE,'.',''),
						"roundedValue"				=> number_format($roundedValue,DECIMAL_VALUE,'.',''),
						"payableAmount"				=> number_format($payableAmount,DECIMAL_VALUE,'.',''),
						"orderItems"				=> $LineItemsResult,
					);
				}
				
				header("Content-Type: application/json");
				echo json_encode($response);
				exit;
			}
			else
			{
				header("Content-Type: application/json");
				$response[] = array("httpCode" => 200 , "status" => 2, "message" => "No data found." );
				echo json_encode($response);
				exit;
			}
		}
	}

	#updateCustomerDefaultAddress
	public function updateCustomerDefaultAddress()
	{
		$authUserName = isset($_SERVER['PHP_AUTH_USER']) ? $_SERVER['PHP_AUTH_USER'] : "";
		$authPassword = isset($_SERVER['PHP_AUTH_PW']) ? $_SERVER['PHP_AUTH_PW'] : "";
		$checkAuth = checkAuthorization($authUserName, $authPassword);

		if ($checkAuth == 0) 
		{
			$response = array("httpCode" => 401, "message" => "Unauthorized");
			header('HTTP/1.0 401 Unauthorized');
			header("Content-Type: application/json");
			echo json_encode($response);
			exit;
		} 
		else 
		{
			$json = @file_get_contents('php://input');
			$jsondata = @json_decode($json);

			$customer_id = $jsondata->customer_id;
			$customer_address_id = $jsondata->customer_address_id;

			if ( !empty($customer_id) && !empty($customer_address_id) ) 
			{
				$updateData['default_address'] = 'N';
				$this->db->where('customer_id', $customer_id);
				$result = $this->db->update('cus_customer_address', $updateData);

				$postData['default_address'] = 'Y';
				$postData['last_updated_by'] = '-1';
				$postData['last_updated_date'] = $this->date_time;
				
				$this->db->where('customer_address_id', $customer_address_id);
				$this->db->where('customer_id', $customer_id);
				$result = $this->db->update('cus_customer_address', $postData);

				$response[] = array(	
					"httpCode" 		=> 200,
					"status"        => (int) 1,
					"message" 		=> "Default address updated successfully!"
				);
				
				header("Content-Type: application/json");
				echo json_encode($response);
				exit;
			} 
			else 
			{
				$response = array("httpCode" => 400, "message" => "Bad Request");
				header('HTTP/1.1 400 Bad Request');
				header("Content-Type: application/json");
				echo json_encode($response);
				exit;
			}
		}
	}

	#Add Favourite Items
	function addFavouriteOrder()
	{
		$authUserName = isset($_SERVER['PHP_AUTH_USER']) ? $_SERVER['PHP_AUTH_USER'] : "";
		$authPassword = isset($_SERVER['PHP_AUTH_PW']) ? $_SERVER['PHP_AUTH_PW'] : "";
		$checkAuth = checkAuthorization($authUserName, $authPassword);

		if ($checkAuth == 0) 
		{
			$response = array("httpCode" => 401, "message" => "Unauthorized");
			header('HTTP/1.0 401 Unauthorized');
			header("Content-Type: application/json");
			echo json_encode($response);
			exit;
		} 
		else 
		{
			$json = @file_get_contents('php://input');
			$jsondata = @json_decode($json);

			if (!empty($jsondata->customer_id) && !empty($jsondata->header_id)) 
			{
				$postData['customer_id'] = $jsondata->customer_id;
				$postData['header_id'] = $jsondata->header_id;

				$checkExistQry = "select favourite_id from ord_favourite_orders as favourite_orders
					WHERE customer_id = '".$postData['customer_id']."'
					AND header_id = '".$postData['header_id']."'
				";

				$chkExist = $this->db->query($checkExistQry)->result_array();

				if (count($chkExist) == 0) 
				{
					$postData['created_by'] = '-1';
					$postData['created_date'] = $this->date_time;
					$postData['last_updated_by'] = '-1';
					$postData['last_updated_date'] = $this->date_time;

					$this->db->insert('ord_favourite_orders', $postData);
					$id = $this->db->insert_id();

					if ($id != "") 
					{
						$response[] = array(
							"httpCode" => 200,
							"status"   => (int) 1,
							"favouriteId"   => (int) $id,
							"message"  => "Order added to favourite!"
						);

						header("Content-Type: application/json");
						echo json_encode($response);
						exit;
					}
				} 
				else 
				{
					$response[] = array(
						"httpCode" => 200,
						"status" => (int) 2,
						"message" => "Item already added to favourite order!"
					);
					header("Content-Type: application/json");
					echo json_encode($response);
					exit;
				}
			} 
			else 
			{
				$response[] = array("httpCode" => 400, "status" => (int) 2, "message" => "Bad Request");
				header('HTTP/1.1 400', TRUE, 400);
				header("Content-Type: application/json");
				echo json_encode($response);
				exit;
			}
			exit;
		}
	}

	#Remove Favourite Items
	function removeFavouriteOrder($favourite_id="")
	{
		$authUserName = isset($_SERVER['PHP_AUTH_USER']) ? $_SERVER['PHP_AUTH_USER'] :"";
		$authPassword = isset($_SERVER['PHP_AUTH_PW']) ? $_SERVER['PHP_AUTH_PW'] :"";
		$checkAuth = checkAuthorization($authUserName,$authPassword);
		
		if ($checkAuth == 0) #Check Authorization
		{
			$response = array("httpCode" => 401,"message" => "Unauthorized");
			header('HTTP/1.0 401 Unauthorized'); 
			header("Content-Type: application/json");
			echo json_encode($response);
			exit; 
		}
		else
		{
			if( !empty( $favourite_id ) )
			{
				$checkExistQry = "select favourite_id from ord_favourite_orders as favourite_orders 
					where favourite_id ='".$favourite_id."'
				";

				$chkExist = $this->db->query($checkExistQry)->result_array();

				if(count($chkExist) > 0)
				{
					$this->db->where('favourite_id', $favourite_id);
					$this->db->delete('ord_favourite_orders');

					$response[] = array(	
						"httpCode" 		=> 200,
						"status"        => (int) 1,
						"message" 		=> "Favourite order removed successfully!"
					);
					
					header("Content-Type: application/json");
					echo json_encode($response);
					exit;	
				}
				else
				{
					$response[] = array(	
						"httpCode" 		=> 200,
						"status"        => (int) 2,
						"message" 		=> "Favourite order not found!"
					);
					
					header("Content-Type: application/json");
					echo json_encode($response);
					exit;
				}	
			}
			else
			{
				$response[] = array("httpCode" => 400 ,"status"  => (int) 2, "Message" => "Bad Request" );
				header('HTTP/1.1 400', TRUE, 400);
				header("Content-Type: application/json");
				echo json_encode($response);
				exit;
			}
			exit;
		}
	}

	#Favourite Order Listing
	function favouriteOrderListing($user_id = '')
	{
		$authUserName = isset($_SERVER['PHP_AUTH_USER']) ? $_SERVER['PHP_AUTH_USER'] :"";
		$authPassword = isset($_SERVER['PHP_AUTH_PW']) ? $_SERVER['PHP_AUTH_PW'] :"";
		$checkAuth = checkAuthorization($authUserName,$authPassword);
		
		if ($checkAuth == 0) #Check Authorization
		{
			$response = array("httpCode" => 401,"message" => "Unauthorized");
			header('HTTP/1.0 401 Unauthorized'); 
			header("Content-Type: application/json");
			echo json_encode($response);
			exit; 
		}
		else
		{
			$query = "select 
			header_tbl.*,

			branch.branch_id,
			branch.branch_name,
			payment_type.payment_type,

			customer.customer_name,
			customer.mobile_number,
			country.country_code,
			customer_address.address_name,
			customer_address.address1,
			customer_address.address2,
			customer_address.address3,
			customer_address.land_mark,
			customer_address.address_type,
			customer_address.postal_code,
			line_tbl.cancel_status,
			sum(line_tbl.price) as price,
			sum(line_tbl.price * line_tbl.quantity) as bill_amount,

			round( sum((line_tbl.offer_percentage / 100) * (line_tbl.quantity * line_tbl.price)),2) as offer_amount,
			round( sum((line_tbl.quantity * line_tbl.price) - ((line_tbl.offer_percentage / 100) * (line_tbl.quantity * line_tbl.price))),2) as linetotal,
			round( sum( ((line_tbl.quantity * line_tbl.price) - ((line_tbl.offer_percentage / 100) * (line_tbl.quantity * line_tbl.price))) * (tax_percentage/100)),2) as tax_value,
			fav_order.favourite_id
			
			from ord_favourite_orders as fav_order
			
			left join ord_order_headers as header_tbl on 
				header_tbl.header_id = fav_order.header_id

			left join ord_order_lines as line_tbl on line_tbl.header_id = header_tbl.header_id
			
			left join per_user on per_user.user_id = header_tbl.customer_id
			left join cus_consumers as customer on customer.customer_id = per_user.reference_id

			left join pay_payment_types as payment_type on payment_type.payment_type_id = header_tbl.payment_method
			left join branch on branch.branch_id = header_tbl.branch_id

			left join cus_customer_address as customer_address on 
				customer_address.customer_address_id = header_tbl.address_id

			left join geo_countries as country on 
				country.country_id = customer.country_id

				WHERE 1=1
				and header_tbl.customer_id = '".$user_id."'
				
				and (line_tbl.cancel_status = 'N' or header_tbl.cancel_status = 'Y')
				group by line_tbl.header_id,line_tbl.cancel_status
				order by header_tbl.header_id desc
			";
			
			$result = $this->db->query($query)->result_array();

			if( count($result) > 0 )
			{
				foreach($result as $row)
				{
					$LineQuery = "select 
							ord_order_lines.line_id,
							ord_order_lines.quantity,
							ord_order_lines.offer_percentage,
							
							ord_order_lines.tax_percentage,
							ord_order_lines.product_id,
							ord_order_lines.cancel_status,
							ord_order_lines.line_status,
							ord_order_headers.header_id,
							products.item_name,
							products.item_description,
							ord_order_lines.price,

							round((offer_percentage / 100) * (ord_order_lines.quantity * ord_order_lines.price),2) as offer_amount,
							round( (ord_order_lines.quantity * ord_order_lines.price) - ((offer_percentage / 100) * (ord_order_lines.quantity * ord_order_lines.price)),2) as linetotal,
							round(((ord_order_lines.quantity * ord_order_lines.price) - ((offer_percentage / 100) * (ord_order_lines.quantity * ord_order_lines.price))) * (tax_percentage/100),2) as tax_value
							
						from ord_order_lines
							
						left join ord_order_headers on 
							ord_order_headers.header_id = ord_order_lines.header_id
						
						left join inv_sys_items as products on 
							products.item_id = ord_order_lines.product_id

						where 
						ord_order_lines.header_id='".$row['header_id']."'";
					
					$LineData = $this->db->query($LineQuery)->result_array();

					$LineItemsResult = [];
					$totalTax = 0;
					$subTotal = 0;

					foreach($LineData as $lineItems)
					{
						if($lineItems['cancel_status'] == 'Y')
						{
							$totalTax += 0;
							$subTotal += 0;
						}
						else
						{
							$totalTax += $lineItems['tax_value'];
							$subTotal += $lineItems['linetotal'];
						}

						$LineItemsResult[] = array(
							"itemCode"				=> $lineItems['item_name'],
							"itemName"				=> $lineItems['item_description'],
							"quantity"				=> (int) $lineItems['quantity'],
							"price"					=>  number_format($lineItems['price'],DECIMAL_VALUE,'.',''),
							"offerAmount"			=>  number_format($lineItems['offer_amount'],DECIMAL_VALUE,'.',''),
							"taxValue"				=>  number_format($lineItems['tax_value'],DECIMAL_VALUE,'.',''),
							"lineTotal"				=>  number_format($lineItems['linetotal'],DECIMAL_VALUE,'.',''),
							
							#"ingredientAmount"		=> (double)$lineValue['ingredient_amount'],
							#"ingredients"			=> $ingredients_name,
							"cancelFlag"	        => $lineItems['cancel_status'],
							"lineStatus"	        => $lineItems['line_status'],
						);
					}
					
					$totalAmount = $subTotal + $totalTax;
					$roundedAmount = round($totalAmount);
					$roundedValue = $roundedAmount - $totalAmount ;

					$isFavOrder = $this->db->query("select favourite_id from ord_favourite_orders 
					where header_id = ".$row['header_id']." and customer_id = ".$user_id."")->result_array();
					if (count($isFavOrder) == 1) {
						$isFavOrder = true;
					}
					else{
						$isFavOrder = false;
					}

					if($row['cancel_status'] == 'Y')
					{
						$bill_amount = 0;
					}
					else
					{
						#$totalTax += $lineItems['tax_value'];
						$bill_amount = round($row['linetotal'] + $row['tax_value']);
					}

					if($row['paid_status'] == 'Y')
					{
						$paid_status = 'Paid';
					}else{
						$paid_status = 'Unpaid';
					}

					if($row['cancel_status'] == 'Y')
					{
						$cancel_status = 'Cancelled';
					}else{
						$cancel_status = 'Not Cancelled';
					}

					if($row['order_type'] == 1)
					{
						$orderType = 'Take Away';
					}
					else if($row['order_type'] == 2)
					{
						$orderType = 'Deliver';
					}
		
					$payableAmount = $totalAmount + $roundedValue;

					$response[] = array(
						"favouriteId"	 		    => (int)$row['favourite_id'],
						"headerId"	 				=> (int)$row['header_id'],
						"branchName"				=> $row['branch_name'],
						"branchId"					=> (int)$row['branch_id'],
						"orderNumber"	 			=> $row['order_number'],
						"orderStatus"				=> $row['order_status'],

						"acceptedDate"				=> $row['accepted_date'],
						"preparingDate"				=> $row['preparing_date'],
						"outForDeliveryDate"		=> $row['out_for_delivery_date'],
						"deliveredDate"				=> $row['delivered_date'],
						"walletAmount"				=> (double)$row['wallet_amount'],
						"orderedDate"				=> $row['ordered_date'],
						"cancelledDate"				=> $row['cancel_date'],
						"paymentMethod"				=> $row['payment_type'],
						"paymentStatus"				=> $row['payment_transaction_status'], #Payment => PENDING, SUCCESS
						"paidStatus"				=> $paid_status,#COD => 0-Unpaid, 1=>Paid
						
						"addressName"				=> $row['address_name'],
						"address1"					=> $row['address1'],
						"address2"			    	=> $row['address2'],
						"address3"					=> $row['address3'],
						"landMark"					=> $row['land_mark'],
						"addressType"				=> $row['address_type'],
						"postalCode"				=> $row['postal_code'],
						"orderSource"				=> $row['order_source'],
						"cancelStatus"				=> $cancel_status,
						"orderType"				    => $orderType,
						"isFavOrder"				=> $isFavOrder,
						"subTotal"					=> number_format($subTotal,DECIMAL_VALUE,'.',''),
						"cgst_value"				=> number_format($totalTax / 2,DECIMAL_VALUE,'.',''),
						"sgst_value"				=> number_format($totalTax / 2,DECIMAL_VALUE,'.',''),
						"roundedValue"				=> number_format($roundedValue,DECIMAL_VALUE,'.',''),
						"payableAmount"				=> number_format($payableAmount,DECIMAL_VALUE,'.',''),
						"orderItems"				=> $LineItemsResult,
					);
				}
				
				header("Content-Type: application/json");
				echo json_encode($response);
				exit;
			}
			else
			{
				header("Content-Type: application/json");
				$response[] = array("httpCode" => 200 , "status" => 2,"message" => "No data found." );
				echo json_encode($response);
				exit;
			}
		}
	}

	#Cancel Orders
	public function cancelOrders()
	{
		$authUserName = isset($_SERVER['PHP_AUTH_USER']) ? $_SERVER['PHP_AUTH_USER'] : "";
		$authPassword = isset($_SERVER['PHP_AUTH_PW']) ? $_SERVER['PHP_AUTH_PW'] : "";
		$checkAuth = checkAuthorization($authUserName, $authPassword);

		if ($checkAuth == 0) 
		{
			$response = array("httpCode" => 401, "message" => "Unauthorized");
			header('HTTP/1.0 401 Unauthorized');
			header("Content-Type: application/json");
			echo json_encode($response);
			exit;
		} 
		else 
		{
			$json = @file_get_contents('php://input');
			$jsondata = @json_decode($json);

			$customer_id = $jsondata->customer_id;
			$header_id = $jsondata->header_id;

			if ( !empty($customer_id) && !empty($header_id) ) 
			{
				$data["cancel_status"] = 'Y';
				$data["cancelled_by"] = $customer_id;
				$data["cancel_date"] = $this->date_time;
				$data['order_status'] = "Cancelled";
				$succ_msg = 'Order Cancelled successfully!';
				
				#Wallet start
				$orderQuery = "select 
					header_tbl.customer_id, 
					header_tbl.order_number, 
					header_tbl.branch_id,
				
					header_tbl.paid_status,
					header_tbl.payment_method,
					cus_customers.mobile_number, 
					country.country_code
					
				from ord_order_headers as header_tbl
				
				left join cus_consumers as cus_customers  on 
					cus_customers.customer_id = header_tbl.customer_id
					
				left join geo_countries as country on 
					country.country_id = cus_customers.country_id
					
				where 
				header_tbl.header_id='".$header_id."' ";
				$getOrderDetails = $this->db->query($orderQuery)->result_array();
				
				if(count($getOrderDetails) > 0)
				{
					$customerId = $getOrderDetails[0]['customer_id'];
					$branchID = $getOrderDetails[0]['branch_id'];
					
					$orderLineQuery = "select 
						line_tbl.price,
						line_tbl.quantity,
						line_tbl.offer_percentage
						from ord_order_lines as line_tbl
						
					where 
						line_tbl.header_id='".$header_id."' and 
						line_tbl.cancel_status != 'Y' 
					";
					$getOrderLineDetails = $this->db->query($orderLineQuery)->result_array();
					
					$sub_total = 0;
					if(count($getOrderLineDetails) > 0)	
					{
						foreach($getOrderLineDetails as $itemName)	
						{
							$offer_percentage = $itemName['offer_percentage'];

							$sub_total += $offer_percentage / 100  * ($itemName['price'] * $itemName['quantity']);
						}
					}
					
					$grandTotal = $sub_total;
				   
					$paid_status = $getOrderDetails[0]['paid_status'];
					$payment_method = $getOrderDetails[0]['payment_method'];
					
					if( $payment_method == 1 ) #COD
					{
						if($paid_status == 'Y') #if paid only added to wallet
						{
							/* $walletQuery = "select wallet_id from vb_customer_wallet 
							where user_id='".$userID."' ";
							$chkCustomerWallet = $this->db->query($walletQuery)->result_array();
							
							if(count($chkCustomerWallet) > 0) #Update Wallet Amount
							{
								$UpdateQuery = "update vb_customer_wallet set wallet_amount=wallet_amount + $grandTotal where user_id = '".$userID."' ";
								$this->db->query($UpdateQuery);
							}
							else #Insert Wallet Amount
							{
								$WalletData['user_id'] = $userID;
								$WalletData['branch_id'] = $branchID;
								$WalletData['wallet_amount'] = $grandTotal;
								
								$this->db->insert('vb_customer_wallet', $WalletData);
								$wallet_id = $this->db->insert_id();
							} */
						}
						else if($paid_status != 'Y') #if not paid no need add wallet
						{
							
						}
						
						#Order canceled sms start
						$country_code = $getOrderDetails[0]['country_code'];
						$mobile_number = $getOrderDetails[0]['mobile_number'];
						$order_number = $getOrderDetails[0]['order_number'];
						$otpMobileNumber = $mobile_number; //$country_code.
						
						if($paid_status == 'Y') #if COD - paid status => 1 send SMS
						{
							$otpMessage = '#'. $order_number.' Your order has been cancelled. Sorry for the inconvenience, amount has been refunded to wallet. '.strtoupper(SITE_NAME);
							//$sendSMS = globalSMS($otpMobileNumber,$otpMessage);
							$sendSMS = sendSMS($otpMobileNumber,$otpMessage);
						}
						else if($paid_status != 'Y')#if COD - paid status => 0 send SMS - Not PAID
						{
							$otpMessage = '#'. $order_number.' Your order has been cancelled. Sorry for the inconvenience caused. '.strtoupper(SITE_NAME);
							//$sendSMS = globalSMS($otpMobileNumber,$otpMessage);
							$sendSMS = sendSMS($otpMobileNumber,$otpMessage);
						}
						#Order canceled sms end
					}
					else  if( $payment_method == 2 || $payment_method == 3 ) #Card : 2 / Wallet : 3
					{
						$walletQuery = "select customer_wallet_id from cus_customer_wallet 
							where customer_id='".$customerId."' ";
						$chkCustomerWallet = $this->db->query($walletQuery)->result_array();
						
						if(count($chkCustomerWallet) > 0) #Update Wallet Amount
						{
							$UpdateQuery = "update cus_customer_wallet set wallet_amount=wallet_amount + $grandTotal where customer_id = '".$customerId."' ";
							$this->db->query($UpdateQuery);
						}
						else #Insert Wallet Amount
						{
							$WalletData['customer_id'] = $customerId;
							$WalletData['branch_id'] = $branchID;
							$WalletData['wallet_amount'] = $grandTotal;
							
							$this->db->insert('cus_customer_wallet', $WalletData);
							$wallet_id = $this->db->insert_id();
						}
						
						#Order canceled sms start
						$country_code = $getOrderDetails[0]['country_code'];
						$mobile_number = $getOrderDetails[0]['mobile_number'];
						$order_number = $getOrderDetails[0]['order_number'];
						$otpMobileNumber = $mobile_number;
						
						$otpMessage = '#'. $order_number.' Your order has been cancelled. Sorry for the inconvenience, amount has been refunded to wallet. '.strtoupper(SITE_NAME);
						$sendSMS = sendSMS($otpMobileNumber,$otpMessage);
						#Order canceled sms end
					}
					/* else #Card
					{
						$walletQuery = "select wallet_id from vb_customer_wallet 
							where user_id='".$userID."' ";
						$chkCustomerWallet = $this->db->query($walletQuery)->result_array();
						
						if(count($chkCustomerWallet) > 0) #Update Wallet Amount
						{
							$UpdateQuery = "update vb_customer_wallet set wallet_amount=wallet_amount + $grandTotal where user_id = '".$userID."' ";
							$this->db->query($UpdateQuery);
						}
						else #Insert Wallet Amount
						{
							$WalletData['user_id'] = $userID;
							$WalletData['branch_id'] = $branchID;
							$WalletData['wallet_amount'] = $grandTotal;
							
							$this->db->insert('vb_customer_wallet', $WalletData);
							$wallet_id = $this->db->insert_id();
						}
						
						#Order canceled sms start
						$country_code = $getOrderDetails[0]['country_code'];
						$mobile_number = $getOrderDetails[0]['mobile_number'];
						$order_number = $getOrderDetails[0]['order_number'];
						$otpMobileNumber = $country_code.$mobile_number;
						
						
						$otpMessage = '#'. $order_number.' Your order has been cancelled. Sorry for the inconvenience, amount has been refunded to wallet. '.strtoupper(SITE_NAME);
						$sendSMS = globalSMS($otpMobileNumber,$otpMessage);
						#Order canceled sms end
					} */
				}
				#Wallet end
				
				$this->db->where('header_id', $header_id);
				$this->db->update('ord_order_headers', $data);

				#Order Line start here - 21-04-2023
				$lineCancelData["cancel_status"] = 'Y';
				$lineCancelData["cancelled_by"] = $this->user_id;
				$lineCancelData["cancel_date"] = $this->date_time;
				$lineCancelData['line_status'] = "Cancelled";
				$this->db->where('header_id', $header_id);
				$this->db->update('ord_order_lines', $lineCancelData);
				#Order Line end here - 21-04-2023

				$response[] = array(	
					"httpCode" 		=> 200,
					"status"        => (int) 1,
					"message" 		=> "Order cancelled successfully!"
				);
				
				header("Content-Type: application/json");
				echo json_encode($response);
				exit;
			} 
			else 
			{
				$response[] = array("httpCode" => 400, "message" => "Bad Request");
				header('HTTP/1.1 400 Bad Request');
				header("Content-Type: application/json");
				echo json_encode($response);
				exit;
			}
		}
	}

	#Menu List 
	function menuList()
	{
		$authUserName = isset($_SERVER['PHP_AUTH_USER']) ? $_SERVER['PHP_AUTH_USER'] :"";
		$authPassword = isset($_SERVER['PHP_AUTH_PW']) ? $_SERVER['PHP_AUTH_PW'] :"";
		$checkAuth = checkAuthorization($authUserName,$authPassword);
		
		if ($checkAuth == 0) #Check Authorization
		{
			$response = array("httpCode" => 401,"message" => "Unauthorized");
			header('HTTP/1.0 401 Unauthorized'); 
			header("Content-Type: application/json");
			echo json_encode($response);
			exit; 
		}
		else
		{
			/*
			$condition = " categories.active_flag = 'Y' ";
			 $query = "select 
				categories.category_id,
				categories.category_name,
				cat1.list_code as category_level_code_1, 
				cat1.list_value as category_name_1,

				cat2.list_code as category_level_code_2, 
				cat2.list_value as category_name_2,

				cat3.list_code as category_level_code_3, 
				cat3.list_value as category_name_3
				
				from inv_categories as categories

				left join sm_list_type_values as cat1 on
					cat1.list_type_value_id = categories.cat_level_1
				
				left join sm_list_type_values as cat2 on
					cat2.list_type_value_id = categories.cat_level_2
				
				left join sm_list_type_values as cat3 on
					cat3.list_type_value_id = categories.cat_level_3

				where $condition 
					order by categories.disp_seq_num asc"; */
			
			
			$query = " select distinct ltv.list_code,ltv.list_value,
			(select category_id from inv_categories where cat_level_2 in
			(select list_type_value_id from sm_list_type_values where list_type_value_id = ltv.list_type_value_id) limit 1) category_id
			from sm_list_type_values ltv, inv_categories ics, inv_sys_items iss
			where 1 = 1
			and ltv.list_type_value_id = ics.cat_level_2
			and ics.category_id = iss.category_id
			and ics.active_flag='Y'
			";

			$result = $this->db->query($query)->result_array();
			$response=[];
			if( count($result) > 0 )
			{
				foreach($result as $menu_row)
				{
					if(file_exists("uploads/category_image/".$menu_row['category_id'].'.png') )
					{
						$photo_url = base_url().'uploads/category_image/'.$menu_row['category_id'].'.png';
					}
					else
					{
						$photo_url = base_url().'uploads/no-image.png';
					}

					$currentTime = date("H:i:s");
					$categoryCode = $menu_row['list_code'];

					$condition = " 1=1
						and cat2.list_code = '".$categoryCode."'
						and branch_items.active_flag = 'Y' 
						and items.active_flag = 'Y' 
						and categories.active_flag = 'Y'
						and branch.default_branch = 'Y' 
						and branch.active_flag = 'Y' 
						";

						$query = "select 
							branch.branch_id,
							branch.branch_name,

							branch.break_fast_from,
							branch.break_fast_to,
							branch.lunch_from,
							branch.lunch_to,
							branch.dinner_from,
							branch.dinner_to,

							
							categories.category_id,
							categories.category_name,
							items.item_id,
							items.item_name,
							items.item_description,

							coalesce(branch_items.breakfast_flag,'N') as breakfast_flag,
							coalesce(branch_items.lunch_flag,'N') as lunch_flag,
							coalesce(branch_items.dinner_flag,'N') as dinner_flag,
							
							branch_items.item_price,
							branch_items.available_quantity,
							branch_items.minimum_order_quantity,
							
							organization.organization_id,
							organization.organization_code,
							organization.organization_name,
							cat1.list_value as category_name_1,
							cat2.list_value as category_name_2,
							cat3.list_value as category_name_3,
							offers.offer_percentage,
							(
								case
									when '".$currentTime."' between branch.break_fast_from AND branch.break_fast_to then 'BreakFast'
									when '".$currentTime."' between branch.lunch_from AND branch.lunch_to then 'Lunch'
									when '".$currentTime."' between branch.dinner_from AND branch.dinner_to then 'Dinner'
									else ''
								end 
							) food_time
							
							from inv_item_branch_assign as branch_items

						left join inv_sys_items as items on items.item_id = branch_items.item_id
						left join inv_categories as categories on categories.category_id = items.category_id
						left join branch on branch.branch_id = branch_items.branch_id
						left join inv_item_offers as offers on offers.branch_id = branch.branch_id
						left join org_organizations as organization on organization.organization_id = branch.organization_id
						
						left join sm_list_type_values as cat1 on
							cat1.list_type_value_id = categories.cat_level_1

						left join sm_list_type_values as cat2 on
							cat2.list_type_value_id = categories.cat_level_2

						left join sm_list_type_values as cat3 on
							cat3.list_type_value_id = categories.cat_level_3
							
						where
						$condition

						group by branch_id,category_id,item_id

						
					";

					/* group by branch_id,category_id

						HAVING (
							branch_items.breakfast_flag = if (food_time = 'BreakFast', 'Y','') or
							branch_items.lunch_flag = if (food_time = 'Lunch', 'Y','') or
							branch_items.dinner_flag = if (food_time = 'Dinner', 'Y','') 
						)
						order by items.item_description asc */
					
					$menuItems = $this->db->query($query)->result_array();

					$responseItems=[]; 
					foreach($menuItems as $row)
					{
						if(file_exists("uploads/products/".$row['item_id'].'.png') )
						{
							$photo_url = base_url().'uploads/products/'.$row['item_id'].'.png';
						}
						else
						{
							$photo_url = base_url().'uploads/no-image.png';
						}

						$ingredientsQry = "select line_tbl.* from inv_item_ingredient_line as line_tbl

							left join inv_item_ingredient_header as header_tbl on 
								header_tbl.ing_header_id = line_tbl.ing_header_id

								where 1=1
								and header_tbl.active_flag='Y'
								and line_tbl.active_flag='Y'
								and header_tbl.branch_id='".$row['branch_id']."'
								and line_tbl.item_id='".$row['item_id']."'
						";
						$getIngredients = $this->db->query($ingredientsQry)->result_array();

						$ingResult = [];

						foreach($getIngredients as $ing)
						{
							$ingResult[] = array(
								"ingLineId"               => (int) $ing['ing_line_id'],
								"ingredientName"          => $ing['ingredient_name'],
								"ingredientDescription"   => $ing['ingredient_description'],
								"ingredientCost"   		  => number_format($ing['ingredient_cost'],DECIMAL_VALUE,'.',''),
							);
						}

						$offerAmount = $row['offer_percentage'] / 100 * $row['item_price'];
						$itemOfferPrice = $row['item_price'] - $offerAmount;

						if(!empty($row['food_time']) && $row['food_time'] == 'BreakFast')
						{
							$itemAvailableFlag = $row['breakfast_flag'];
						}
						else if(!empty($row['food_time']) && $row['food_time'] == 'Lunch')
						{
							$itemAvailableFlag = $row['lunch_flag'];
						}
						else if(!empty($row['food_time']) && $row['food_time'] == 'Dinner')
						{
							$itemAvailableFlag = $row['dinner_flag'];
						}
						else
						{
							$itemAvailableFlag = 'N';
						}

						$responseItems[] = array(
							"organizationId"   => (int) $row['organization_id'],
							"organizationCode" => ucfirst($row['organization_code']),
							"organizationName" => ucfirst($row['organization_name']),
							"branchId"         => (int) $row['branch_id'],
							"branchName"       => ucfirst($row['branch_name']),
							"categoryId"       => (int) $row['category_id'],
							"categoryName"     => ucfirst($row['category_name']),

							"categoryLevel1"     => ucfirst($row['category_name_1']),
							"categoryLevel2"     => ucfirst($row['category_name_2']),
							"categoryLevel3"     => ucfirst($row['category_name_3']),

							"itemId"           => (int) $row['item_id'],
							"itemName"         => ucfirst($row['item_name']),
							"itemDescription"  => ucfirst($row['item_description']),
							"itemPrice"        => number_format($row['item_price'],DECIMAL_VALUE,'.',''),
							"availableQty"     => (int) $row['available_quantity'],
							"minimumOrderQty"  => (int) $row['minimum_order_quantity'],
							"offerPercentage"  => $row['offer_percentage'],
							"offerAmount"  	   => number_format($offerAmount,DECIMAL_VALUE,'.',''),
							"itemOfferPrice"   => number_format($itemOfferPrice,DECIMAL_VALUE,'.',''),
							
							"break_fast_from"  => $row['break_fast_from'],
							"break_fast_to"    => $row['break_fast_to'],
							"lunch_from"   	   => $row['lunch_from'],
							"lunch_to"   	   => $row['lunch_to'],
							"dinner_from"      => $row['dinner_from'],
							"dinner_to"   	   => $row['dinner_to'],

							"breakfastFlag"    => $row['breakfast_flag'],
							"lunchFlag"   	   => $row['lunch_flag'],
							"dinnerFlag"   	   => $row['dinner_flag'],
							"itemAvailableFlag"=> $itemAvailableFlag,

							"itemImage"        => $photo_url,
							"itemIngredients"  => $ingResult,
						);
					}
							
					$response[$menu_row['list_value']] = array(
						"categoryId"      => $menu_row['list_code'],
						"categoryName"    => ucfirst($menu_row['list_value']),
						"categoryImage"   => $photo_url,
						"menuItems"   	  => $responseItems,
					);
				}
				header("Content-Type: application/json");	
				echo json_encode($response);
				exit;
			}
			else
			{
				header("Content-Type: application/json");
				$response[] = array("httpCode" => 200 , "status" => 2, "message" => "No data found.");
				echo json_encode($response);
				exit;
			}
		}
	}

	#Add Customer Enquiry
	function addCustomerEnquiry()
	{
		$authUserName = isset($_SERVER['PHP_AUTH_USER']) ? $_SERVER['PHP_AUTH_USER'] :"";
		$authPassword = isset($_SERVER['PHP_AUTH_PW']) ? $_SERVER['PHP_AUTH_PW'] :"";
		$checkAuth = checkAuthorization($authUserName,$authPassword);
		
		if ($checkAuth == 0) #Check Authorization
		{
			$response = array("httpCode" => 401,"message" => "Unauthorized");
			header('HTTP/1.0 401 Unauthorized'); 
			header("Content-Type: application/json");
			echo json_encode($response);
			exit; 
		}
		else
		{
			$json = @file_get_contents('php://input');
			$jsondata = @json_decode($json);

			$customer_name = $jsondata->customer_name;

			if( !empty( $jsondata->customer_name ) )
			{
				$postData['customer_name'] = $jsondata->customer_name;
				$postData['mobile_number'] = $jsondata->mobile_number;
				$postData['selected_date'] = date("Y-m-d",strtotime($jsondata->selected_date));
				$postData['from_time'] = $jsondata->from_time;
				$postData['to_time'] = $jsondata->to_time;
				$postData['event_detail'] = $jsondata->event_detail;
				$postData['looking_for'] = $jsondata->looking_for;
				
				$postData['created_by'] = '-1';
				$postData['created_date'] = $this->date_time;
				$postData['last_updated_by'] = '-1';
				$postData['last_updated_date'] = $this->date_time;
				
				$this->db->insert('ord_enquiry', $postData);
				$id = $this->db->insert_id();
				
				if($id !="")
				{
					$response = array(	
						"httpCode" 		=> 200,
						"status"        => (int) 1,
						"message" 		=> "Enquiry create successfully!"
					);
					
					header("Content-Type: application/json");
					echo json_encode($response);
					exit;
				}
			}
			else
			{
				$response = array("httpCode" => 400 ,"status"  => (int) 2, "Message" => "Bad Request" );
				header('HTTP/1.1 400', TRUE, 400);
				header("Content-Type: application/json");
				echo json_encode($response);
				exit;
			}
			exit;
		}
	}
	
	#Login Resent OTP
	function loginResentOtp()
	{
		$authUserName = isset($_SERVER['PHP_AUTH_USER']) ? $_SERVER['PHP_AUTH_USER'] :"";
		$authPassword = isset($_SERVER['PHP_AUTH_PW']) ? $_SERVER['PHP_AUTH_PW'] :"";
		$checkAuth = checkAuthorization($authUserName,$authPassword);
		
		if ($checkAuth == 0) #Check Authorization
		{
			$response = array("httpCode" => 401,"message" => "Unauthorized");
			header('HTTP/1.0 401 Unauthorized'); 
			header("Content-Type: application/json");
			echo json_encode($response);
			exit; 
		}
		else
		{
			$json = @file_get_contents('php://input');
			$jsondata = @json_decode($json);
			
			if( !empty( $jsondata->mobile_number ) )
			{
				$mobile_number = trim($jsondata->mobile_number);

				$otpValidateQry = "select otp_validate_id,mobile_number,customer_id from cus_customers_otp_validate 
				where 
					1=1
					AND cus_customers_otp_validate.mobile_number='".$mobile_number."' ";
				$reSentOtp = $this->db->query($otpValidateQry)->result_array();

				if(count($reSentOtp) > 0)
				{
					$otp_number = otpNumber(4);
					$otpMobileNumber = $mobile_number;
					$otpMessage = $otp_number.' is your Login OTP. - Thank You.  '.strtoupper(SITE_NAME);
					
					$sendSMS = sendSMS($otpMobileNumber,$otpMessage);

					$postData = array(
						"otp_number" 		=> $otp_number,
						"last_updated_by" 	=> '-1',
						"last_updated_date" => $this->date_time,
					);

					$this->db->where('mobile_number', $mobile_number);
					$result = $this->db->update('cus_customers_otp_validate', $postData);

					$response = array(	
						"httpCode" 		=> 200,
						"status"        => (int) 1,
						"message" 		=> "OTP re-sent successfully!"
					);
					
					header("Content-Type: application/json");
					echo json_encode($response);
					exit;
				}
				else
				{
					$response = array(	
						"httpCode" 		=> 200,
						"status"        => (int) 2,
						"message" 		=> "No Customer!"
					);
					
					header("Content-Type: application/json");
					echo json_encode($response);
					exit;
				}
			}
			else
			{
				$response = array("httpCode" => 400 ,"status"  => (int) 2, "Message" => "Bad Request" );
				header('HTTP/1.1 400', TRUE, 400);
				header("Content-Type: application/json");
				echo json_encode($response);
				exit;
			}
			exit;
		}
	}

	#Sign Up Resent OTP
	function signUpResentOtp()
	{
		$authUserName = isset($_SERVER['PHP_AUTH_USER']) ? $_SERVER['PHP_AUTH_USER'] :"";
		$authPassword = isset($_SERVER['PHP_AUTH_PW']) ? $_SERVER['PHP_AUTH_PW'] :"";
		$checkAuth = checkAuthorization($authUserName,$authPassword);
		
		if ($checkAuth == 0) #Check Authorization
		{
			$response = array("httpCode" => 401,"message" => "Unauthorized");
			header('HTTP/1.0 401 Unauthorized'); 
			header("Content-Type: application/json");
			echo json_encode($response);
			exit; 
		}
		else
		{
			$json = @file_get_contents('php://input');
			$jsondata = @json_decode($json);
			
			if( !empty( $jsondata->mobile_number ) )
			{
				$mobile_number = trim($jsondata->mobile_number);

				$otpValidateQry = "select interface_id,mobile_number from cus_customers_interface 
				where 
					1=1
					AND cus_customers_interface.mobile_number='".$mobile_number."' ";
				$reSentOtp = $this->db->query($otpValidateQry)->result_array();

				if(count($reSentOtp) > 0)
				{
					$otp_number = otpNumber(4);
					$otpMobileNumber = $mobile_number;
					$otpMessage = $otp_number.' is your Login OTP. - Thank You.  '.strtoupper(SITE_NAME);
					
					$sendSMS = sendSMS($otpMobileNumber,$otpMessage);

					$postData = array(
						"otp_number" 		=> $otp_number,
						"last_updated_by" 	=> '-1',
						"last_updated_date" => $this->date_time,
					);

					$this->db->where('mobile_number', $mobile_number);
					$result = $this->db->update('cus_customers_interface', $postData);

					$response = array(	
						"httpCode" 		=> 200,
						"status"        => (int) 1,
						"message" 		=> "OTP re-sent successfully!"
					);
					
					header("Content-Type: application/json");
					echo json_encode($response);
					exit;
				}
				else
				{
					$response = array(	
						"httpCode" 		=> 200,
						"status"        => (int) 2,
						"message" 		=> "No Customer!"
					);
					
					header("Content-Type: application/json");
					echo json_encode($response);
					exit;
				}
			}
			else
			{
				$response = array("httpCode" => 400 ,"status"  => (int) 2, "Message" => "Bad Request" );
				header('HTTP/1.1 400', TRUE, 400);
				header("Content-Type: application/json");
				echo json_encode($response);
				exit;
			}
			exit;
		}
	}

	#Tax List 
	function taxList()
	{
		$authUserName = isset($_SERVER['PHP_AUTH_USER']) ? $_SERVER['PHP_AUTH_USER'] :"";
		$authPassword = isset($_SERVER['PHP_AUTH_PW']) ? $_SERVER['PHP_AUTH_PW'] :"";
		$checkAuth = checkAuthorization($authUserName,$authPassword);
		
		if ($checkAuth == 0) #Check Authorization
		{
			$response = array("httpCode" => 401,"message" => "Unauthorized");
			header('HTTP/1.0 401 Unauthorized'); 
			header("Content-Type: application/json");
			echo json_encode($response);
			exit; 
		}
		else
		{
			$query = " select tax_id,tax_value,tax_name
			from gen_tax
			where 1 = 1
			and active_flag='Y'
			and default_tax= 1
			";

			$result = $this->db->query($query)->result_array();

			$response=[];
			if( count($result) > 0 )
			{
				foreach($result as $row)
				{
					$response[] = array(
						"taxId"     => (int) $row['tax_id'],
						"taxName"   => trim($row['tax_name']),
						"taxPercentage"  => $row['tax_value'],
						
					);
				}
				header("Content-Type: application/json");	
				echo json_encode($response);
				exit;
			}
			else
			{
				header("Content-Type: application/json");
				$response[] = array("httpCode" => 200 , "status" => 2, "message" => "No data found.");
				echo json_encode($response);
				exit;
			}
		}
	}

	public function deliveryLocation()
	{
		$json = @file_get_contents('php://input');
		$jsondata = @json_decode($json);

		if( !empty( $jsondata->latitude ) && !empty( $jsondata->longitude ) )
		{
			$latitude = $jsondata->latitude;
			$longitude = $jsondata->longitude;
			//$branch_id = $jsondata->branch_id;

			/* $branchQry = "select branch_id, delivery_distance,latitude,longitude from branch 
				where branch_id ='".$branch_id."' and branch.active_flag='Y' ";
			$getBranch = $this->db->query($branchQry)->result_array();

			$delivery_distance = isset($getBranch[0]["delivery_distance"]) ? $getBranch[0]["delivery_distance"] : NULL;
 		*/
			/* $distanceQry ="select 
			branch.branch_id,
			branch.branch_name,
			round(( 3959 * acos(cos( radians(".$latitude.") ) * cos( radians(latitude) ) * cos( radians(longitude) - radians(".$longitude.") ) + sin( radians(".$latitude.") )* sin( radians(latitude) ) ) ), 1) as distance
			from 
			branch 
			where 1=1
			and branch.active_flag='Y'
			having distance <= $delivery_distance order by distance ";
			echo $distanceQry;exit; */


			$distanceQry ="select t.branch_id,t.branch_name,t.delivery_distance,t.distance from (
				select
				branch.branch_id,
				branch.branch_name,delivery_distance,
				round(( 3959 * acos(cos( radians(".$latitude.") ) * cos( radians(latitude) ) * cos( radians(longitude) - radians(".$longitude.") ) + sin( radians(".$latitude.") )* sin( radians(latitude) ) ) ), 1) as distance
				from
				branch
				where 1=1
				and branch.active_flag='Y') t
				where t.distance <= t.delivery_distance";
				;
			$getBranches = $this->db->query($distanceQry)->result_array();

			if(count($getBranches) > 0)
			{ 
				$response = [];
				foreach($getBranches as $branch)
				{
					$response[] = array(	
						//"httpCode" 		=> 200,
						"branchId"      => (int) $branch["branch_id"],
						"branchName"    => $branch["branch_name"],
						"status"        => (int) 1,
						//"message" 		=> "Delivery Available"
					);
					
				}
				header("Content-Type: application/json");
				echo json_encode($response);
				exit;
			}
			else
			{
				$response[] = array(	
					"httpCode" 		=> 200,
					"status"        => (int) 2,
					"message" 		=> "Sorry! Currently we are note delivering for this location."
				);
				
				header("Content-Type: application/json");
				echo json_encode($response);
				exit;
			}
		}
		else
		{
			$response = array("httpCode" => 400 ,"status"  => (int) 2, "Message" => "Bad Request" );
			header('HTTP/1.1 400', TRUE, 400);
			header("Content-Type: application/json");
			echo json_encode($response);
			exit;
		}
	}

	#Party Banner List 
	function partyBannerList()
	{
		$authUserName = isset($_SERVER['PHP_AUTH_USER']) ? $_SERVER['PHP_AUTH_USER'] :"";
		$authPassword = isset($_SERVER['PHP_AUTH_PW']) ? $_SERVER['PHP_AUTH_PW'] :"";
		$checkAuth = checkAuthorization($authUserName,$authPassword);
		
		if ($checkAuth == 0) #Check Authorization
		{
			$response = array("httpCode" => 401,"message" => "Unauthorized");
			header('HTTP/1.0 401 Unauthorized'); 
			header("Content-Type: application/json");
			echo json_encode($response);
			exit; 
		}
		else
		{
			$condition = " active_flag = 'Y' and deleted_flag = 'N' and banner_type='PARTY-HALL-BANNER'";
			$query = "select banner_id, banner_title, banner_description from banner where $condition 
				order by banner.banner_id asc";
			$result = $this->db->query($query)->result_array();
			
			if( count($result) > 0 )
			{
				foreach($result as $row)
				{
					if(file_exists("uploads/banner/".$row['banner_id'].'.png') )
					{
						$photo_url = base_url().'uploads/banner/'.$row['banner_id'].'.png';
					}
					else
					{
						$photo_url = base_url().'uploads/no-image.png';
					}
			
					$response[] = array(
						"bannerId"           => (int) $row['banner_id'],
						"bannerName"         => ucfirst($row['banner_title']),
						"bannerDescription"  => ucfirst($row['banner_description']),
						"bannerImage"        => $photo_url
					);
				}
				header("Content-Type: application/json");	
				echo json_encode($response);
				exit;
			}
			else
			{
				header("Content-Type: application/json");
				$response[] = array("httpCode" => 200 , "status" => 2, "message" => "No data found.");
				echo json_encode($response);
				exit;
			}
		}
	}

	#Branch Details
	public function branchDetails($branch_id="")
	{
		$authUserName = isset($_SERVER['PHP_AUTH_USER']) ? $_SERVER['PHP_AUTH_USER'] :"";
		$authPassword = isset($_SERVER['PHP_AUTH_PW']) ? $_SERVER['PHP_AUTH_PW'] :"";
		$checkAuth = checkAuthorization($authUserName,$authPassword);
		
		if ($checkAuth == 0) #Check Authorization
		{
			$response = array("httpCode" => 401,"message" => "Unauthorized");
			header('HTTP/1.0 401 Unauthorized'); 
			header("Content-Type: application/json");
			echo json_encode($response);
			exit; 
		}
		else
		{
			if(!empty($branch_id))
			{
				$query = "select branch_id,branch_code,branch_name,mobile_number,email,minimum_order_value from branch where branch.branch_id ='".$branch_id."' ";
				$resultData = $this->db->query($query)->result_array();
																
				$response = array(	
					"httpCode" 		=> 200 ,
					"branchId"		=> (int) $resultData[0]['branch_id'],
					"branchCode"	=> $resultData[0]['branch_code'],
					"branchName"	=> $resultData[0]['branch_name'],
					"mobileNumber"	=> $resultData[0]['mobile_number'],
					"email"			=> $resultData[0]['email'],	
					"minOrderValue"	=> number_format($resultData[0]['minimum_order_value'],DECIMAL_VALUE,'.',''), 				
				);
				
				header("Content-Type: application/json");
				echo json_encode($response);
				exit;
			}
			else
			{
				$response = array("httpCode" => 400 , "Message" => "Bad Request" );
				header('HTTP/1.1 400', TRUE, 400);
				header("Content-Type: application/json");
				echo json_encode($response);
				exit;
			}
			exit;
		}
	}

	function timeBasedItems($branch_id="")
	{
		$authUserName = isset($_SERVER['PHP_AUTH_USER']) ? $_SERVER['PHP_AUTH_USER'] :"";
		$authPassword = isset($_SERVER['PHP_AUTH_PW']) ? $_SERVER['PHP_AUTH_PW'] :"";
		$checkAuth = checkAuthorization($authUserName,$authPassword);
		
		if ($checkAuth == 0) #Check Authorization
		{
			$response = array("httpCode" => 401,"message" => "Unauthorized");
			header('HTTP/1.0 401 Unauthorized'); 
			header("Content-Type: application/json");
			echo json_encode($response);
			exit; 
		}
		else
		{	
			$json = @file_get_contents('php://input');
			$data = @json_decode($json);
			$flag_type = isset($data->flag_type) ? strtolower($data->flag_type) : NULL;

			if( !empty($branch_id) && !empty($flag_type))
			{
				$currentTime = date("H:i:s");

				if($flag_type == "breakfast")
				{
					$flagCondition = "branch_items.breakfast_flag ='Y' and '".$currentTime."' between branch.break_fast_from AND branch.break_fast_to";
				}
				else if($flag_type == "lunch")
				{
					$flagCondition = "branch_items.lunch_flag ='Y' and '".$currentTime."' between branch.lunch_from AND branch.lunch_to";
				}
				else if($flag_type == "dinner")
				{
					$flagCondition = "branch_items.dinner_flag ='Y' and '".$currentTime."' between branch.dinner_from AND branch.dinner_to";
				}
				else
				{
					$flagCondition = "1=1";
				}

				$query = "select
				branch.branch_id,
				branch.branch_name,
				branch.minimum_order_value,
				branch.break_fast_from,
				branch.break_fast_to,
				branch.lunch_from,
				branch.lunch_to,
				branch.dinner_from,
				branch.dinner_to,

				categories.category_id,
				categories.category_name,
				items.item_id,
				items.item_name,
				items.item_description,
				branch_items.item_price,
				branch_items.available_quantity,
				branch_items.minimum_order_quantity,

				coalesce(branch_items.breakfast_flag,'N') as breakfast_flag,
				coalesce(branch_items.lunch_flag,'N') as lunch_flag,
				coalesce(branch_items.dinner_flag,'N') as dinner_flag,

				organization.organization_id,
				organization.organization_code,
				organization.organization_name,
				cat1.list_value as category_name_1,
				cat2.list_value as category_name_2,
				cat3.list_value as category_name_3,
				offers.offer_percentage,
				(
					case
						when '".$currentTime."' between branch.break_fast_from AND branch.break_fast_to then 'BreakFast'
						when '".$currentTime."' between branch.lunch_from AND branch.lunch_to then 'Lunch'
						when '".$currentTime."' between branch.dinner_from AND branch.dinner_to then 'Dinner'
						else ''
					end 
				) food_time
				
				from inv_item_branch_assign as branch_items
				
				left join inv_sys_items as items on items.item_id = branch_items.item_id
				left join inv_categories as categories on categories.category_id = items.category_id
				left join branch on branch.branch_id = branch_items.branch_id
				left join inv_item_offers as offers on offers.branch_id = branch.branch_id
				left join org_organizations as organization on organization.organization_id = branch.organization_id
				
				left join sm_list_type_values as cat1 on
				cat1.list_type_value_id = categories.cat_level_1
				
				left join sm_list_type_values as cat2 on
				cat2.list_type_value_id = categories.cat_level_2
				
				left join sm_list_type_values as cat3 on
				cat3.list_type_value_id = categories.cat_level_3
				
				where 1=1
				and branch_items.branch_id = '".$branch_id."'
				and branch_items.active_flag = 'Y'
				and items.active_flag = 'Y'
				and categories.active_flag = 'Y'
				and branch.default_branch = 'Y'
				and branch.active_flag = 'Y'
				and $flagCondition
				group by branch_id,Item_id
	
				order by items.item_description asc";
				
				$result = $this->db->query($query)->result_array();

				if( count($result) > 0 )
				{
					foreach($result as $row)
					{
						if(file_exists("uploads/products/".$row['item_id'].'.png') )
						{
							$photo_url = base_url().'uploads/products/'.$row['item_id'].'.png';
						}
						else
						{
							$photo_url = base_url().'uploads/no-image.png';
						}

						$ingredientsQry = "select line_tbl.* from inv_item_ingredient_line as line_tbl

							left join inv_item_ingredient_header as header_tbl on 
								header_tbl.ing_header_id = line_tbl.ing_header_id

								where 1=1
								and header_tbl.active_flag='Y'
								and line_tbl.active_flag='Y'
								and header_tbl.branch_id='".$row['branch_id']."'
								and line_tbl.item_id='".$row['item_id']."'
						";
						$getIngredients = $this->db->query($ingredientsQry)->result_array();

						$ingResult = [];

						foreach($getIngredients as $ing)
						{
							$ingResult[] = array(
								"ingLineId"               => (int) $ing['ing_line_id'],
								"ingredientName"          => $ing['ingredient_name'],
								"ingredientDescription"   => $ing['ingredient_description'],
								"ingredientCost"   		  => number_format($ing['ingredient_cost'],DECIMAL_VALUE,'.',''),
							);
						}

						$offerAmount = $row['offer_percentage'] / 100 * $row['item_price'];
						$itemOfferPrice = $row['item_price'] - $offerAmount;

						if(!empty($row['food_time']) && $row['food_time'] == 'BreakFast')
						{
							$itemAvailableFlag = $row['breakfast_flag'];
						}
						else if(!empty($row['food_time']) && $row['food_time'] == 'Lunch')
						{
							$itemAvailableFlag = $row['lunch_flag'];
						}
						else if(!empty($row['food_time']) && $row['food_time'] == 'Dinner')
						{
							$itemAvailableFlag = $row['dinner_flag'];
						}
						else
						{
							$itemAvailableFlag = 'N';
						}

						$response[] = array(
							"organizationId"   => (int) $row['organization_id'],
							"organizationCode" => ucfirst($row['organization_code']),
							"organizationName" => ucfirst($row['organization_name']),
							"branchId"         => (int) $row['branch_id'],
							"branchName"       => ucfirst($row['branch_name']),
							"categoryId"       => (int) $row['category_id'],
							"categoryName"     => ucfirst($row['category_name']),

							"categoryLevel1"     => ucfirst($row['category_name_1']),
							"categoryLevel2"     => ucfirst($row['category_name_2']),
							"categoryLevel3"     => ucfirst($row['category_name_3']),

							"itemId"           => (int) $row['item_id'],
							"itemName"         => ucfirst($row['item_name']),
							"itemDescription"  => ucfirst($row['item_description']),
							"itemPrice"        => number_format($row['item_price'],DECIMAL_VALUE,'.',''),
							"availableQty"     => (int) $row['available_quantity'],
							"minimumOrderQty"  => (int) $row['minimum_order_quantity'],
							"minimumOrderValue" => number_format($row['minimum_order_value'],DECIMAL_VALUE,'.',''),
							"offerPercentage"  => $row['offer_percentage'],
							"offerAmount"  	   => number_format($offerAmount,DECIMAL_VALUE,'.',''),
							"itemOfferPrice"   => number_format($itemOfferPrice,DECIMAL_VALUE,'.',''),

							"break_fast_from"  => $row['break_fast_from'],
							"break_fast_to"    => $row['break_fast_to'],
							"lunch_from"   	   => $row['lunch_from'],
							"lunch_to"   	   => $row['lunch_to'],
							"dinner_from"      => $row['dinner_from'],
							"dinner_to"   	   => $row['dinner_to'],

							"breakfastFlag"    => $row['breakfast_flag'],
							"lunchFlag"   	   => $row['lunch_flag'],
							"dinnerFlag"   	   => $row['dinner_flag'],
							#"foodTime"   	   => $row['food_time'],

							"itemAvailableFlag"=> $itemAvailableFlag,
							
							"itemImage"        => $photo_url,
							"itemIngredients"  => $ingResult,
						);
					}
					header("Content-Type: application/json");	
					echo json_encode($response);
					exit;
				}
				else
				{
					header("Content-Type: application/json");
					$response[] = array("httpCode" => 200 , "status" => 2, "message" => "No data found.");
					echo json_encode($response);
					exit;
				}
			}
			else
			{
				$response = array("httpCode" => 400 ,"status"  => (int) 2, "Message" => "Bad Request" );
				header('HTTP/1.1 400', TRUE, 400);
				header("Content-Type: application/json");
				echo json_encode($response);
				exit;
			}
			exit;
		}
	}

	#Add Device Token
	function deviceToken()
	{
		$json = @file_get_contents('php://input');
		$data = @json_decode($json);

		$deviceToken = isset($data->deviceToken) ? $data->deviceToken : NULL;
		$deviceType = isset($data->deviceType) ? $data->deviceType : NULL;
		$userId = isset($data->userId) ? $data->userId : NULL;

		if( !empty($deviceToken) || !empty($deviceType) || !empty($userId) )
		{
			$chkQry = "select token_id from org_device_tokens 
				where user_id='".$userId."' ";
			$chkDeviceToken = $this->db->query($chkQry)->result_array();
			
			if(count($chkDeviceToken) == 0)
			{
				$deviceData= array(
					"device_token"       => $deviceToken,
					"device_type"        => $deviceType, #1=> Android, 2=>iOS
					"user_id"       	 => $userId,

					"created_date"        => $this->date_time,
					"created_by"          => $userId,
					"last_updated_date"   => $this->date_time,
					"last_updated_by"     => $userId,
				);
				
				$this->db->insert('org_device_tokens', $deviceData);
				$insertID = $this->db->insert_id();

				if($insertID)
				{
					header("Content-Type: application/json");
					$response = array(
						"httpCode"      => 200,
						"statusCode"    => 1,
						"message"       => "Device token created successfully!" 
					);

					echo json_encode($response);
					exit;
				}
			}
			else
			{
				$deviceData= array(
					"device_token"       => $deviceToken,
					"device_type"        => $deviceType, #1=> Android, 2=>iOS
					"last_updated_date"   => $this->date_time,
					"last_updated_by"     => $userId,
				);
				
				$this->db->where('user_id', $userId);
				$this->db->where('device_type', $deviceType);
				$result = $this->db->update('org_device_tokens', $deviceData);

				header("Content-Type: application/json");
				$response = array(
					"httpCode"      => 200,
					"statusCode"    => 2 ,
					"message"       => "Device token updated successfully!" 
				);

				echo json_encode($response);
				exit;
			}
		}
		else
		{
			$response = array("httpCode" => 400 ,"status"  => (int) 2, "Message" => "Bad Request" );
			header('HTTP/1.1 400', TRUE, 400);
			header("Content-Type: application/json");
			echo json_encode($response);
			exit;
		}
		exit;
	}

	#Delete Device Token
	function deleteDeviceToken($user_id="")
	{
		$authUserName = isset($_SERVER['PHP_AUTH_USER']) ? $_SERVER['PHP_AUTH_USER'] :"";
		$authPassword = isset($_SERVER['PHP_AUTH_PW']) ? $_SERVER['PHP_AUTH_PW'] :"";
		$checkAuth = checkAuthorization($authUserName,$authPassword);
		
		if ($checkAuth == 0) #Check Authorization
		{
			$response = array("httpCode" => 401,"message" => "Unauthorized");
			header('HTTP/1.0 401 Unauthorized'); 
			header("Content-Type: application/json");
			echo json_encode($response);
			exit; 
		}
		else
		{
			if( !empty( $user_id ) )
			{
				$checkExistQry = "select token_id from org_device_tokens
					where user_id ='".$user_id."'
				";

				$chkExist = $this->db->query($checkExistQry)->result_array();

				if(count($chkExist) > 0)
				{
					$this->db->where('user_id', $user_id);
					$this->db->delete('org_device_tokens');

					$response[] = array(	
						"httpCode" 		=> 200,
						"status"        => (int) 1,
						"message" 		=> "Device token deleted successfully!"
					);
					
					header("Content-Type: application/json");
					echo json_encode($response);
					exit;	
				}
				else
				{
					$response[] = array(	
						"httpCode" 		=> 200,
						"status"        => (int) 2,
						"message" 		=> "Device token not found!"
					);
					
					header("Content-Type: application/json");
					echo json_encode($response);
					exit;
				}	
			}
			else
			{
				$response[] = array("httpCode" => 400 ,"status"  => (int) 2, "Message" => "Bad Request" );
				header('HTTP/1.1 400', TRUE, 400);
				header("Content-Type: application/json");
				echo json_encode($response);
				exit;
			}
			exit;
		}
	}

	#Add Mobile Version
	function addMobileVersion()
	{
		$authUserName = isset($_SERVER['PHP_AUTH_USER']) ? $_SERVER['PHP_AUTH_USER'] :"";
		$authPassword = isset($_SERVER['PHP_AUTH_PW']) ? $_SERVER['PHP_AUTH_PW'] :"";
		$checkAuth = checkAuthorization($authUserName,$authPassword);
		
		if ($checkAuth == 0) #Check Authorization
		{
			$response = array("httpCode" => 401,"message" => "Unauthorized");
			header('HTTP/1.0 401 Unauthorized'); 
			header("Content-Type: application/json");
			echo json_encode($response);
			exit; 
		}
		else
		{
			$json = @file_get_contents('php://input');
			$jsondata = @json_decode($json);
			
			if( !empty( $jsondata->version_name ) && !empty( $jsondata->os_type ) )
			{
				$postData['version_name'] = $jsondata->version_name;
				$postData['os_type'] = strtoupper($jsondata->os_type);
					
				#Check Exist
				$checkExistQry = "select version_id from mobile_versions
					where version_name ='".$postData['version_name']."'
					and	os_type ='".$postData['os_type']."'
				";

				$chkExist = $this->db->query($checkExistQry)->result_array();

				if( count($chkExist) == 0 )
				{
					$postData['created_by'] = '-1';
					$postData['created_date'] = $this->date_time;
					$postData['last_updated_by'] = '-1';
					$postData['last_updated_date'] = $this->date_time;
					
					$this->db->insert('mobile_versions', $postData);
					$id = $this->db->insert_id();
					
					if($id !="")
					{
						$response = array(	
							"httpCode" 		=> 200,
							"status"        => (int) 1,
							"message" 		=> "Mobile Version Added successfully"
						);
						
						header("Content-Type: application/json");
						echo json_encode($response);
						exit;
					}
				}
				else
				{
					$response = array(	
						"httpCode" 		=> 200,
						"status"        => (int) 2,
						"message" 		=> "Mobile Version already addded!"
					);
					
					header("Content-Type: application/json");
					echo json_encode($response);
					exit;
				}
			}
			else
			{
				$response = array("httpCode" => 400 ,"status"  => (int) 2, "Message" => "Bad Request" );
				header('HTTP/1.1 400', TRUE, 400);
				header("Content-Type: application/json");
				echo json_encode($response);
				exit;
			}
			exit;
		}
	}

	#Mobile Versions
    function mobileVersions()
	{
		$json = @file_get_contents('php://input');
		$data = @json_decode($json);

		$version_name = isset($data->version_name) ? $data->version_name : NULL;
		$os_type = isset($data->os_type) ? $data->os_type : NULL;

		if( !empty($version_name) || !empty($os_type) )
		{
			$version_name = $data->version_name;
			$os_type = strtoupper($data->os_type);

			$chkQuery = "select version_name from mobile_versions 
				where os_type = '".$os_type."' order by version_id desc limit 0,1";
			$checkMobileVersions = $this->db->query($chkQuery)->result_array();
			$versionName = isset($checkMobileVersions[0]["version_name"]) ? $checkMobileVersions[0]["version_name"] :"";
			
			if(!empty($version_name))
			{
				if( $version_name == $versionName )
				{
					$versionType = 0;
					$result = array(
						'hashUpdate'  => (int) $versionType,
					);
				}
				else
				{
					$versionType = 1;
					$result = array(
						'hashUpdate'  => (int) $versionType,
					);
				}
			}
			else
			{
				$result = array("httpCode" => 200 , "Message" => "Version error!");
			}
			#new start changes end here
			
			header("Content-Type: application/json");
			echo json_encode($result);
			exit;
		}
		else
		{
			$response = array("httpCode" => 400 ,"status"  => (int) 2, "Message" => "Bad Request" );
			header('HTTP/1.1 400', TRUE, 400);
			header("Content-Type: application/json");
			echo json_encode($response);
			exit;
		}
		exit;
	}

	#CMS Pages
	function cms()
	{
		$authUserName = isset($_SERVER['PHP_AUTH_USER']) ? $_SERVER['PHP_AUTH_USER'] :"";
		$authPassword = isset($_SERVER['PHP_AUTH_PW']) ? $_SERVER['PHP_AUTH_PW'] :"";
		$checkAuth = checkAuthorization($authUserName,$authPassword);
		
		if ($checkAuth == 0) #Check Authorization
		{
			$response = array("httpCode" => 401,"message" => "Unauthorized");
			header('HTTP/1.0 401 Unauthorized'); 
			header("Content-Type: application/json");
			echo json_encode($response);
			exit; 
		}
		else
		{

			$aboutUs = base_url()."about-us.html";
			$contactUs = base_url()."contact-us.html";
			$termsAndCondition = base_url()."terms-and-conditions.html";
			$privacyPolicy = base_url()."privacy-policy.html";
			
			$response[] = array(
				'aboutUs' 		    => $aboutUs ,
				'contactUs' 	    => $contactUs,
				'termsAndCondition' => $termsAndCondition,
				'privacyPolicy' => $privacyPolicy
			);
			
			header("Content-Type: application/json");
			echo json_encode($response);exit;	
		}
	}

	#CMS Pages
	function cmsPages()
	{
		$authUserName = isset($_SERVER['PHP_AUTH_USER']) ? $_SERVER['PHP_AUTH_USER'] :"";
		$authPassword = isset($_SERVER['PHP_AUTH_PW']) ? $_SERVER['PHP_AUTH_PW'] :"";
		$checkAuth = checkAuthorization($authUserName,$authPassword);
		
		if ($checkAuth == 0) #Check Authorization
		{
			$response = array("httpCode" => 401,"message" => "Unauthorized");
			header('HTTP/1.0 401 Unauthorized'); 
			header("Content-Type: application/json");
			echo json_encode($response);
			exit; 
		}
		else
		{
			$chkAboutUsQuery = "select cms_desc from cms 
				where cms_url = 'about-us'";
			$getAboutUs = $this->db->query($chkAboutUsQuery)->result_array();
			$aboutUs = isset($getAboutUs[0]["cms_desc"]) ? $getAboutUs[0]["cms_desc"] : NULL;
		
			$chkTermsQuery = "select cms_desc from cms 
				where cms_url = 'terms-conditions'";
			$getTermsAndCondition = $this->db->query($chkTermsQuery)->result_array();
			$terms_and_condition = isset($getTermsAndCondition[0]["cms_desc"]) ? $getTermsAndCondition[0]["cms_desc"] : NULL;
			
			$chkPrivacyPolicyQuery = "select cms_desc from cms 
				where cms_url = 'privacy-policy'";
			$getPrivayPolicy = $this->db->query($chkPrivacyPolicyQuery)->result_array();
			$privacy_policy = isset($getPrivayPolicy[0]["cms_desc"]) ? $getPrivayPolicy[0]["cms_desc"] : NULL;

			$contactUs = array(
				"contactEmail"     => CONTACT_EMAIL,
				"address1"         => ADDRESS1,
				"address2"         => ADDRESS2,
				"phone1"           => PHONE1,
				"phone2"           => PHONE2,
				"cin"              => CIN,
				"gst_number"       => GST_NUMBER,
				"fssi_number"      => FSSAI_NUMBER,
				"license_number"   => LICENSE_NUMBER,
				"company_account"  => COMPANY_ACCOUNT,
				"open_hours"       => OPENING_HOURS,
			);

			$socialMedia = array(
				"INSTAGRAM"    => INSTAGRAM,
				"YOUTUBE"      => YOUTUBE,
				"FACEBOOK"     => FACEBOOK,
			);

			$latLng = array(
				"LATITUDE"    => LATITUDE,
				"LONGITUDE"   => LONGITUDE,
			);

			$response[] = array(
				'aboutUs' 		     => $aboutUs,
				'termsAndCondition'  => $terms_and_condition,
				'privacyPolicy'  	 => $privacy_policy,
				'contactUs'  		 => $contactUs,
				'socialMedia'  		 => $socialMedia,
				'latLng'   			 => $latLng
			);
			
			header("Content-Type: application/json");
			echo json_encode($response);exit;	
		}
	}

	#Controller End here
}
?>
