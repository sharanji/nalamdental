<?php
defined('BASEPATH') OR exit('No direct script access allowed');
include_once('vendor/autoload.php');
class Captain_api extends CI_Controller 
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
		$getAuthDetails = $this->captain_app_model->getOauthDetails();

		if(count($getAuthDetails) > 0) 
		{
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
		else
		{
			header("Content-Type: application/json");
			$response[] = array("httpCode" => 200 , "status" => (int) 2, "message" => "No data found.");
			echo json_encode($response);
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

			if( !empty( $jsondata->user_name ) && !empty( $jsondata->password ) ) #Mobile Login
			{
				$user_name = $jsondata->user_name;
				$password = trim($jsondata->password);
				
				$login_status = $this->captain_app_model->adminLogin($user_name,$password);
				$status = isset($login_status["resturn_status"]) ? $login_status["resturn_status"] : NULL;
				$userDetails = isset($login_status["user_details"]) ? $login_status["user_details"] : NULL;

				$userId = isset($userDetails[0]["user_id"]) ? $userDetails[0]["user_id"] : NULL;
				$lastLoginDate = isset($userDetails[0]["last_login_date"]) ? date("d-M-Y h:i:s a",strtotime($userDetails[0]["last_login_date"])) : NULL;
				$employeeNumber = isset($userDetails[0]["employee_number"]) ? $userDetails[0]["employee_number"] : NULL;
				$firstName = isset($userDetails[0]["first_name"]) ? $userDetails[0]["first_name"] : NULL;
				$middleName = isset($userDetails[0]["middle_name"]) ? $userDetails[0]["middle_name"] : NULL;
				$lastName = isset($userDetails[0]["last_name"]) ? $userDetails[0]["last_name"] : NULL;
				$mobileNumber = isset($userDetails[0]["mobile_number"]) ? $userDetails[0]["mobile_number"] : NULL;
				$branchId = isset($userDetails[0]["branch_id"]) ? $userDetails[0]["branch_id"] : NULL;
				$branchName = isset($userDetails[0]["branch_name"]) ? $userDetails[0]["branch_name"] : NULL;
				
				if(!empty($firstName) && !empty($middleName) && !empty($lastName))
				{
					$fullName = $firstName." ".$middleName." ".$lastName;
				}
				else if(!empty($firstName) && !empty($middleName) && empty($lastName))
				{
					$fullName = $firstName." ".$middleName;
				}
				else if(!empty($firstName) && empty($middleName) && !empty($lastName))
				{
					$fullName = $firstName." ".$lastName;
				}
				else
				{
					$fullName = $firstName." ".$middleName." ".$lastName;
				}

				header("Content-Type: application/json");

				if($status == 10)
				{
					$response[] = array(
							"httpCode"       => 200, 
							"userId"         => (int) $userId, 
							"lastLoginDate"  => $lastLoginDate, 
							"employeeNumber" => $employeeNumber, 
							"firstName"      => $firstName, 
							"middleName"     => $middleName, 
							"lastName"       => $lastName, 
							"fullName"       => $fullName, 
							"mobileNumber"   => $mobileNumber, 
							"mobileNumber"   => $mobileNumber, 
							"branchId"       => (int) $branchId, 
							"branchName"     => $branchName, 
							"status"         => (int) 1, 
							"message"        => "Successfully logged!"
					);
				}
				else if($status == 9)
				{
					$response[] = array("httpCode" => 200 , "status" => (int) 2, "message" => "Your account has been Blocked!");
				}
				else if($status == 8)
				{
					$response[] = array("httpCode" => 200 , "status" => (int) 2, "message" => "Username does not exist!");
				}
				else if($status == 0)
				{
					$response[] = array("httpCode" => 200 , "status" => (int) 2, "message" => "Username or Password does not match!!");
				}
				else if($status == 1)
				{
					$response[] = array("httpCode" => 200 , "status" => (int) 2, "message" => "Sorry, already you Logged in some other system!");
				}
				else
				{
					$response[] = array("httpCode" => 200 , "status" => (int) 2, "message" => "Can't login this time!");
				}
				echo json_encode($response);
				exit;
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
				$userDetails = $this->captain_app_model->getUserDetails($user_id);

				$person_id = isset($userDetails[0]["person_id"]) ? $userDetails[0]["person_id"] : NULL;
				$userId = isset($userDetails[0]["user_id"]) ? $userDetails[0]["user_id"] : NULL;
				$lastLoginDate = isset($userDetails[0]["last_login_date"]) ? date("d-M-Y h:i:s a",strtotime($userDetails[0]["last_login_date"])) : NULL;
				$employeeNumber = isset($userDetails[0]["employee_number"]) ? $userDetails[0]["employee_number"] : NULL;
				$firstName = isset($userDetails[0]["first_name"]) ? $userDetails[0]["first_name"] : NULL;
				$middleName = isset($userDetails[0]["middle_name"]) ? $userDetails[0]["middle_name"] : NULL;
				$lastName = isset($userDetails[0]["last_name"]) ? $userDetails[0]["last_name"] : NULL;
				$mobileNumber = isset($userDetails[0]["mobile_number"]) ? $userDetails[0]["mobile_number"] : NULL;
				$branchId = isset($userDetails[0]["branch_id"]) ? $userDetails[0]["branch_id"] : NULL;
				$branchName = isset($userDetails[0]["branch_name"]) ? $userDetails[0]["branch_name"] : NULL;
				$emailAddress = isset($userDetails[0]["email_address"]) ? $userDetails[0]["email_address"] : NULL;
				$fatherName = isset($userDetails[0]["father_name"]) ? $userDetails[0]["father_name"] : NULL;
				$motherName = isset($userDetails[0]["mother_name"]) ? $userDetails[0]["mother_name"] : NULL;

				if(isset($userDetails[0]["date_of_birth"]) && !empty($userDetails[0]["date_of_birth"])){
					$dateofbirth = date("d-M-Y",strtotime($userDetails[0]["date_of_birth"]));
				}else{
					$dateofbirth = NULL;
				}

				$gender = isset($userDetails[0]["gender"]) ? $userDetails[0]["gender"] : NULL;
				
				if(!empty($firstName) && !empty($middleName) && !empty($lastName))
				{
					$fullName = $firstName." ".$middleName." ".$lastName;
				}
				else if(!empty($firstName) && !empty($middleName) && empty($lastName))
				{
					$fullName = $firstName." ".$middleName;
				}
				else if(!empty($firstName) && empty($middleName) && !empty($lastName))
				{
					$fullName = $firstName." ".$lastName;
				}
				else
				{
					$fullName = $firstName." ".$middleName." ".$lastName;
				}

				if (file_exists('uploads/profile_image/'.$person_id.'.png'))
				{
					$profileImgUrl = base_url()."uploads/profile_image/".$person_id.'.png';			
				}
				else
				{
					$profileImgUrl = base_url().'uploads/no-image.png';
				}
																
				$response = array(	
					"httpCode" 		 => 200 ,
					"employeeId"     => (int) $person_id, 
					"userId"         => (int) $userId, 
					"lastLoginDate"  => $lastLoginDate, 
					"employeeNumber" => $employeeNumber, 
					"firstName"      => $firstName, 
					"middleName"     => $middleName, 
					"lastName"       => $lastName, 
					"fullName"       => $fullName, 
					"mobileNumber"   => $mobileNumber, 
					"mobileNumber"   => $mobileNumber, 
					"branchId"       => (int) $branchId, 
					"branchName"     => $branchName,
					"emailAddress"   => $emailAddress,
					"fatherName"     => $fatherName,
					"motherName"     => $motherName,
					"dateofbirth"    => $dateofbirth,
					"gender"         => $gender,
					"profileImg"	 => $profileImgUrl					
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
	public function editProfile($employee_id="")
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
			if( !empty($employee_id) )
			{
				$json = @file_get_contents('php://input');
				$jsondata = @json_decode($json);

				if( !empty( $jsondata->first_name) && !empty( $jsondata->middle_name) && !empty( $jsondata->last_name) && !empty( $jsondata->mobile_number) && !empty( $jsondata->email_address) && !empty( $jsondata->father_name) && !empty( $jsondata->mother_name) && !empty( $jsondata->dateofbirth) )
				{
					if(isset($jsondata->dateofbirth) && !empty($jsondata->dateofbirth)){
						$date_of_birth = date("Y-m-d",strtotime($jsondata->dateofbirth));
					}else{
						$date_of_birth = NULL;
					}

					$postData = array(
						"first_name"         => isset($jsondata->first_name) ? $jsondata->first_name : NULL,
						"middle_name"        => isset($jsondata->middle_name) ? $jsondata->middle_name : NULL,
						"last_name"          => isset($jsondata->last_name) ? $jsondata->last_name : NULL,
						"mobile_number"      => isset($jsondata->mobile_number) ? $jsondata->mobile_number : NULL,
						"email_address"      => isset($jsondata->email_address) ? $jsondata->email_address : NULL,
						"father_name"        => isset($jsondata->father_name) ? $jsondata->father_name : NULL,
						"mother_name"        => isset($jsondata->mother_name) ? $jsondata->mother_name : NULL,
						"date_of_birth"      => $date_of_birth,
						"last_updated_by"    => $employee_id,
						"last_updated_date"  => $this->date_time,
					);
					
					$this->db->where('person_id', $employee_id);  
					$result = $this->db->update('per_people_all', $postData);
					
					if($result)
					{
						if( isset($_FILES['profile_image']['name']) && !empty($_FILES['profile_image']['name']) )
						{  
							move_uploaded_file($_FILES['profile_image']['tmp_name'], 'uploads/profile_image/'.$employee_id.'.png');
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
	public function updateProfileImg($employee_id="")
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
			if( !empty($employee_id) )
			{
				if( isset($_FILES))
				{
					if( isset($_FILES['profile_image']['name']) && !empty($_FILES['profile_image']['name']) )
					{  
						move_uploaded_file($_FILES['profile_image']['tmp_name'], 'uploads/profile_image/'.$employee_id.'.png');
					}

					$userDetails = $this->captain_app_model->getEmpUserDetails($employee_id);

					$person_id = isset($userDetails[0]["person_id"]) ? $userDetails[0]["person_id"] : NULL;
					$userId = isset($userDetails[0]["user_id"]) ? $userDetails[0]["user_id"] : NULL;
					$lastLoginDate = isset($userDetails[0]["last_login_date"]) ? date("d-M-Y h:i:s a",strtotime($userDetails[0]["last_login_date"])) : NULL;
					$employeeNumber = isset($userDetails[0]["employee_number"]) ? $userDetails[0]["employee_number"] : NULL;
					$firstName = isset($userDetails[0]["first_name"]) ? $userDetails[0]["first_name"] : NULL;
					$middleName = isset($userDetails[0]["middle_name"]) ? $userDetails[0]["middle_name"] : NULL;
					$lastName = isset($userDetails[0]["last_name"]) ? $userDetails[0]["last_name"] : NULL;
					$mobileNumber = isset($userDetails[0]["mobile_number"]) ? $userDetails[0]["mobile_number"] : NULL;
					$branchId = isset($userDetails[0]["branch_id"]) ? $userDetails[0]["branch_id"] : NULL;
					$branchName = isset($userDetails[0]["branch_name"]) ? $userDetails[0]["branch_name"] : NULL;
					$emailAddress = isset($userDetails[0]["email_address"]) ? $userDetails[0]["email_address"] : NULL;
					$fatherName = isset($userDetails[0]["father_name"]) ? $userDetails[0]["father_name"] : NULL;
					$motherName = isset($userDetails[0]["mother_name"]) ? $userDetails[0]["mother_name"] : NULL;

					if(isset($userDetails[0]["date_of_birth"]) && !empty($userDetails[0]["date_of_birth"])){
						$dateofbirth = date("d-M-Y",strtotime($userDetails[0]["date_of_birth"]));
					}else{
						$dateofbirth = NULL;
					}

					$gender = isset($userDetails[0]["gender"]) ? $userDetails[0]["gender"] : NULL;
					
					if(!empty($firstName) && !empty($middleName) && !empty($lastName))
					{
						$fullName = $firstName." ".$middleName." ".$lastName;
					}
					else if(!empty($firstName) && !empty($middleName) && empty($lastName))
					{
						$fullName = $firstName." ".$middleName;
					}
					else if(!empty($firstName) && empty($middleName) && !empty($lastName))
					{
						$fullName = $firstName." ".$lastName;
					}
					else
					{
						$fullName = $firstName." ".$middleName." ".$lastName;
					}

					if (file_exists('uploads/profile_image/'.$employee_id.'.png'))
					{
						$profileImgUrl = base_url()."uploads/profile_image/".$employee_id.'.png';			
					}
					else
					{
						$profileImgUrl = base_url().'uploads/no-image.png';
					}
																	
					$response = array(	
						"httpCode" 		 => 200,
						"status"         => (int) 1,
						"employeeId"     => (int) $person_id, 
						"userId"         => (int) $userId, 
						"lastLoginDate"  => $lastLoginDate, 
						"employeeNumber" => $employeeNumber, 
						"firstName"      => $firstName, 
						"middleName"     => $middleName, 
						"lastName"       => $lastName, 
						"fullName"       => $fullName, 
						"mobileNumber"   => $mobileNumber, 
						"mobileNumber"   => $mobileNumber, 
						"branchId"       => (int) $branchId, 
						"branchName"     => $branchName,
						"emailAddress"   => $emailAddress,
						"fatherName"     => $fatherName,
						"motherName"     => $motherName,
						"dateofbirth"    => $dateofbirth,
						"gender"         => $gender,
						"profileImg"	 => $profileImgUrl,
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

	#Change Password
	public function changePassword($user_id="")
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

				if( !empty( $jsondata->password) && !empty( $jsondata->new_password) && !empty( $jsondata->confirm_new_password))
				{
					$password = md5($jsondata->password);
					$new_password = md5($jsondata->new_password);
					$confirm_new_password = md5($jsondata->confirm_new_password);
					$userDetails = $this->captain_app_model->getUserDetails($user_id);
					$current_password = isset($userDetails[0]["password"]) ? $userDetails[0]["password"] : NULL;

					if ($current_password == $password && $new_password == $confirm_new_password) 
					{
						$postData = array(
							"password"           => $new_password,
							"attribute1"         => $jsondata->password,
							"last_updated_by"    => $user_id,
							"last_updated_date"  => $this->date_time,
						);
						
						$this->db->where('user_id', $user_id);  
						$result = $this->db->update('per_user', $postData);

						$response = array(	
							"httpCode" 		 => 200,
							"status"         => (int) 1,
							"message" 		=> "Password changed successfully!"					
						);
						
						header("Content-Type: application/json");
						echo json_encode($response);
						exit;
					} 
					else 
					{
						$response = array(	
							"httpCode" 		 => 200,
							"status"         => (int) 2,
							"message" 		=> "Pasword mismatch!"					
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

	#Table Listing
	function tableListing()
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
			$user_id = $jsondata->user_id;

			$getTables = $this->captain_app_model->getTables($branch_id,$user_id);

			if( count($getTables) > 0 )
			{
				foreach($getTables as $row)
				{	
					$getRunningTables = $this->captain_app_model->getRunningTables($row['line_id']);
					$getBillDetails = $this->captain_app_model->getPrintBillOrderDetails($row['line_id']);

					if(count($getBillDetails)> 0)
					{
						$tableStatusColor = "blue";
						$tableStatus = "bill_printed";
					}
					else if(count($getRunningTables)> 0)
					{
						$tableStatus = "running";
						$tableStatusColor = "red";
					}
					else
					{
						$tableStatus = "open";
						$tableStatusColor = "white";
					}

					$response[] = array(
						"tableLocationId"    => (int) $row['table_location_id'],
						"floorName"          => trim($row['floor_name']),
						"tableHeaderId"      => (int) $row['header_id'],
						"tableLineId"        => (int) $row['line_id'],
						"tableName"          => $row['table_name'],
						"tableCode"          => $row['table_code'],
						"tableNoOfPersons"   => $row['table_no_of_persons'],
						"tableStatus"        => $tableStatus,
						"tableStatusColor"   => $tableStatusColor,
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
			$result = $this->captain_app_model->getCategoryList();
			if( count($result) > 0 )
			{
				foreach($result as $row)
				{
					if(file_exists("uploads/category_image/".$row['list_type_value_id'].'.png') )
					{
						$photo_url = base_url().'uploads/category_image/'.$row['list_type_value_id'].'.png';
					}
					else
					{
						$photo_url = base_url().'uploads/no-image.png';
					}

					$response[] = array(
						"categoryId"      		=> $row['list_type_value_id'],
						"categoryCode"      	=> $row['list_code'],
						"categoryName"    		=> ucfirst($row['list_value']),
						"categoryImage"   		=> $photo_url
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

	#Category Items 
	function categoryItems($main_category_code="",$branch_id="")
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
			$result = $this->captain_app_model->getCategoryItemsList($main_category_code,$branch_id);
			$currentTime = date("H:i:s");
			
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

					// $offerAmount = $row['offer_percentage'] / 100 * $row['item_price'];
					// $itemOfferPrice = $row['item_price'] - $offerAmount;

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
						"categoryId"       => (int) $row['category_id'],
						"categoryName"     => ucfirst($row['category_name']),

						"categoryLevel1"     => ucfirst($row['category_name']),

						"itemId"           => (int) $row['item_id'],
						"itemName"         => ucfirst($row['item_name']),
						"itemDescription"  => ucfirst($row['item_description']),
						"itemPrice"        => number_format($row['item_price'],DECIMAL_VALUE,'.',''),
						
						/* 
						"branchId"         => (int) $row['branch_id'],
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
						"dinnerFlag"   	   => $row['dinner_flag'], */ 

						"itemAvailableFlag"=> $itemAvailableFlag,
						"itemImage"        => $photo_url,
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
			
			if( !empty( $jsondata->item_id ) && !empty( $jsondata->branch_id ) && !empty( $jsondata->user_id ) && !empty( $jsondata->quantity ) && !empty( $jsondata->table_id ) )
			{
				$user_id = $jsondata->user_id;
				$branch_id = $jsondata->branch_id;
				$item_price = isset($jsondata->item_price) ? $jsondata->item_price : NULL;
				$table_id = isset($jsondata->table_id) ? $jsondata->table_id : NULL;
				$cooking_instructions = isset($jsondata->cooking_instructions) ? $jsondata->cooking_instructions : NULL;
				$customer_id = isset($jsondata->customer_id) ? $jsondata->customer_id : NULL;
				$item_id = isset($jsondata->item_id) ? $jsondata->item_id : NULL;
				$quantity = isset($jsondata->quantity) ? $jsondata->quantity : NULL;

				$getOrganization = $this->captain_app_model->getBranchOrganization($branch_id);	
				$organization_id = isset($getOrganization[0]["organization_id"]) ? $getOrganization[0]["organization_id"] : NULL;
				
				$postData = array(
					'organization_id'      => $organization_id,
					'branch_id'            => $branch_id,
					'customer_id'          => $customer_id,
					'waiter_id'            => $user_id,
					'item_id'              => $item_id,
					'quantity'             => $quantity,
					'price'                => $item_price,
					'table_id'             => $table_id,
					'cooking_instructions' => $cooking_instructions,
					'active_flag'          => $this->active_flag,
					'created_by'           => $user_id,
					'created_date'         => $this->date_time,
					'last_updated_by'      => $user_id,
					'last_updated_date'    => $this->date_time,
				);
				
				$this->db->insert('ord_cart_items', $postData);
				$id = $this->db->insert_id();

				if($id)
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
			
			if( !empty( $jsondata->branch_id ) && !empty( $jsondata->user_id ) && !empty( $jsondata->table_id ) )
			{
				$branch_id = $jsondata->branch_id;
				$user_id = $jsondata->user_id;
				$table_id = $jsondata->table_id;

				$getOrganization = $this->captain_app_model->getBranchOrganization($branch_id);	
				$organization_id = isset($getOrganization[0]["organization_id"]) ? $getOrganization[0]["organization_id"] : NULL;
			
				$result = $this->captain_app_model->getCartItems($organization_id,$branch_id,$user_id,$table_id);

				if( count($result) > 0 )
				{
					foreach($result as $row)
					{
						$response[] = array(
							"cartId"               => (int) $row['cart_id'],
							"itemId"               => (int) $row['product_id'],
							"itemName"             => $row['item_name'],
							"itemDescription"      => $row['item_description'],
							"price"  	           => number_format($row['price'],DECIMAL_VALUE,'.',''),
							"quantity"             => (int) $row['quantity'],
							"lineTotal"            => number_format($row['line_total'],DECIMAL_VALUE,'.',''),
							"cookingInstructions"  => $row['cooking_instructions'],
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
					where cart_id ='".$cart_id."'";

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

			if( !empty( $jsondata->table_id ) && !empty($jsondata->branch_id) )
			{
				$branch_id = $jsondata->branch_id;
				$table_id = $jsondata->table_id;

				$checkExistQry = "select cart_items.cart_id from ord_cart_items as cart_items 
					where 1=1
					and branch_id ='".$branch_id."'
					and table_id ='".$table_id."'
					and cart_status IS NULL
				";

				$chkExist = $this->db->query($checkExistQry)->result_array();

				if(count($chkExist) > 0)
				{
					$this->db->where('branch_id', $branch_id);
					$this->db->where('table_id', $table_id);
					$this->db->where('cart_status',NULL);
					$this->db->delete('ord_cart_items');

					$response = array(	
						"httpCode" 		=> 200,
						"status"        => (int) 1,
						"message" 		=> "Cart items cleared successfully!"
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

	# branch List
	function branchList()
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

	#Update Branch Items - Active / Inactive Status
	function updateBranchItems()
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

			$user_id = $jsondata->user_id;
			$branch_id = $jsondata->branch_id;
			$item_id = $jsondata->item_id;
			$status = $jsondata->status;

			if( !empty( $item_id ) && !empty( $branch_id ) && !empty( $status ) )
			{
				if($status == 'Y')
				{
					$data['active_flag'] = 'Y';
					$data['inactive_date'] = NULL;
					$data['last_updated_by'] = $user_id;
					$data['last_updated_date'] = $this->date_time;
					$succ_msg = 'Item active successfully!';
				}
				else if($status == 'N')
				{
					$data['inactive_date'] = $this->date_time;
					$data['active_flag'] ='N';
					$data['last_updated_by'] = $user_id;
					$data['last_updated_date'] =$this->date_time;
					$succ_msg = 'Item inactive successfully!';
				}

				$this->db->where('item_id', $item_id);
				$this->db->where('branch_id', $branch_id);
				$this->db->update('inv_item_branch_assign', $data);

				$response = array(	
					"httpCode" 		=> 200,
					"status"        => (int) 1,
					"message" 		=> $succ_msg
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

	#Track Orders  -  My Table Orders  
	function trackOrders($branch_id="",$waiter_id="")
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
			$table_id = $jsondata->table_id;
			$employee_id = $jsondata->employee_id;

			$taxQry = "select tax_id,tax_value from gen_tax where active_flag='Y' AND default_tax=1";
			$getTax = $this->db->query($taxQry)->result_array();
			$tax_value = isset($getTax[0]["tax_value"]) ? $getTax[0]["tax_value"] : NULL;

			if( !empty( $branch_id ) && !empty( $table_id ) && !empty( $employee_id ) )
			{
				$result = $this->captain_app_model->getDineInSeqOrder($table_id,$branch_id,$employee_id);
				if( count($result) > 0 )
				{
					$tax_value = isset($result[0]["tax_percentage"]) ? $result[0]["tax_percentage"] : NULL; 
				
					$table_code = $result[0]['table_code'];
					$order_number = $result[0]['order_number'];
					$created_date = $result[0]['created_date'];
					$interface_header_id = $result[0]['interface_header_id'];
					
					$orderData = [];
					$totalLineTotal = 0;
					foreach($result as $row)
					{
						$table_id = $row["table_id"];
						$branch_id = $row["branch_id"];
						$order_seq_number = $row["order_seq_number"];

						$getDineInOrderItems = $this->web_fine_dine_model->getDineInOrderItems($table_id,$branch_id,$order_seq_number);
                        $order_created_date =  isset($getDineInOrderItems[0]["created_date"]) ? $getDineInOrderItems[0]["created_date"] : NULL;
                        
						$orderItems = [];
						
						foreach($getDineInOrderItems as $dineInOrders)
						{
							if($dineInOrders['cancel_status'] == 'Y')
							{
								$line_total = number_format(0,DECIMAL_VALUE,'.','');
								$price = number_format(0,DECIMAL_VALUE,'.','');
							}
							else
							{
								$price = number_format($dineInOrders["price"],DECIMAL_VALUE,'.','');
								$line_total = number_format($dineInOrders["line_total"],DECIMAL_VALUE,'.','');
							}
			
							$orderItems[] = array(
								"lineId"               => (int) $dineInOrders['line_id'],
								"cancelStatus"         => $dineInOrders['cancel_status'],
								"itemName"             => $dineInOrders['item_name'],
								"cookingInstructions"  => !empty($dineInOrders["cooking_instructions"]) ? $dineInOrders["cooking_instructions"] : NULL,
								"quantity"             => !empty($dineInOrders["quantity"]) ? (int) $dineInOrders["quantity"] : NULL,
								"price"                => $price,
								"totalAmount"          => $line_total,
							);
							
							if($dineInOrders['cancel_status'] == 'Y')
							{
								$totalLineTotal += 0;  
							}else{
								$totalLineTotal += $dineInOrders["line_total"];  
							}
						}

						$orderData[] = array(
							
							"orderNumber"       => $row['order_seq_number'],
							"timeAgo"           => timeElapsedString($order_created_date),
							"orderItems"        => $orderItems,
						);	
					}
					$discountAmount = 0;
					$taxAmount = $tax_value / 100 * $totalLineTotal;
					$netPay =  ($totalLineTotal-$discountAmount) + $taxAmount;

					$response[] = array(
						"headerId"     => (int) $interface_header_id,
						"tableCode"    => $table_code,
						"orderNumber"  => $order_number,
						"createdDate"  => $created_date,
						"orders"       => $orderData,
						"total"        => number_format($totalLineTotal,DECIMAL_VALUE,'.',''),
						"discount"     => number_format(0,DECIMAL_VALUE,'.',''),
						"tax"          => number_format($taxAmount,DECIMAL_VALUE,'.',''),
						"netPay"       => number_format($netPay,DECIMAL_VALUE,'.',''),
					);

					
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

	#Shift Table
	function shiftTable()
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
			$user_id = $jsondata->user_id;
			$from_table_id = $jsondata->from_table_id;
			$to_table_id = $jsondata->to_table_id;

			if( !empty( $from_table_id ) && !empty( $branch_id ) && !empty( $to_table_id ) )
			{
				$checkRunningTables = $this->captain_app_model->checkRunningTables($branch_id,$to_table_id);

				if(count($checkRunningTables) > 0)
				{
					$response = array(	
						"httpCode" 		=> 200,
						"status"        => (int) 2,
						"message" 		=> "Can't shift the running table!"
					);
				}
				else
				{
					$postData = array(
						"table_id"           => $to_table_id,
						"last_updated_by"    => $user_id,
						"last_updated_date"  => $this->date_time,
					);
					
					$this->db->where('table_id', $from_table_id);
					$this->db->where('branch_id', $branch_id);
					$updateResult = $this->db->update('ord_order_interface_headers',$postData);
		
					$response = array(	
						"httpCode" 		=> 200,
						"status"        => (int) 1,
						"message" 		=> 'Table shifted successfully'
					);
				}
				
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

	#Merge Table
	function mergeTable()
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
			$from_table_id = $jsondata->from_table_id;
			$to_table_id = $jsondata->to_table_id;
			$user_id = $jsondata->user_id;

			if( !empty( $from_table_id ) && !empty( $branch_id ) && !empty( $to_table_id ) )
			{
				$checkRunningTables = $this->captain_app_model->checkRunningTables($branch_id,$to_table_id);
				$maintableInterface = $this->captain_app_model->checkRunningTables($branch_id,$from_table_id);
				
				if(count($checkRunningTables) > 0)
				{
					$interface_header_id = isset($maintableInterface[0]["interface_header_id"]) ? $maintableInterface[0]["interface_header_id"] : NULL;
				
					$to_interface_header_id = isset($checkRunningTables[0]["interface_header_id"]) ? $checkRunningTables[0]["interface_header_id"] : NULL;
				
					$postData = array(
						"reference_header_id"  => $to_interface_header_id,
						"last_updated_by"      => $user_id,
						"last_updated_date"    => $this->date_time,
					);
					
					$this->db->where('reference_header_id', $interface_header_id);
					$updateResult = $this->db->update('ord_order_interface_lines',$postData);
	
					$this->db->where('interface_header_id', $interface_header_id);
					$this->db->delete('ord_order_interface_headers');

					$response = array(	
						"httpCode" 		=> 200,
						"status"        => (int) 1,
						"message" 		=> 'Table merged successfully'
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
						"message" 		=> 'No data found'
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

	#Place Order Items
	function cancelOrderItems()
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

			$user_id = $jsondata->user_id;
			$inter_header_id = $jsondata->inter_header_id;
			$lineData = $jsondata->lineData;

			if( !empty( $inter_header_id ) )
			{
				if( count($lineData) > 0 )
				{
					#Header Print header Update Status start
					$printUpdateData = array(
						"print_status" 	      => 'N',
						"last_updated_by"     => $user_id,
						"last_updated_date"   => $this->date_time,
					);
					
					$this->db->where('interface_header_id', $inter_header_id);
					$this->db->update('ord_order_interface_headers', $printUpdateData);
					#Header Print header Update Status End

					foreach($lineData as $lineItems)
					{
						$line_id = $lineItems->line_id;
						$cancel_remarks = $lineItems->cancel_remarks;

						if($cancel_remarks)
						{
							$lineCancelData["cancel_status"] = 'Y';
							$lineCancelData["cancelled_by"] = $user_id;
							$lineCancelData["cancel_date"] = $this->date_time;

							$lineCancelData["last_updated_by"] = $user_id;
							$lineCancelData["last_updated_date"] = $this->date_time;

							$lineCancelData['line_status'] = "Cancelled";
							$lineCancelData["cancel_remarks"] = $cancel_remarks;
							
							$this->db->where('interface_line_id', $line_id);
							$this->db->where('reference_header_id', $inter_header_id);
							$this->db->update('ord_order_interface_lines', $lineCancelData);
						}
					}
					
					$response = array(	
						"httpCode" 		=> 200,
						"status"        => (int) 1,
						"message" 		=> 'Items deleted successfully!'
					);
				}
				else
				{
					$response = array(	
						"httpCode" 		=> 200,
						"status"        => (int) 2,
						"message" 		=> 'Atleaset 1 line item is required!'
					);
				}

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

	#Place Dine-In Order
	function placeOrder()
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

			$table_id = isset($jsondata->table_id) ? $jsondata->table_id : NULL;
			
			if( !empty($table_id) && !empty($jsondata->branch_id) )
			{
				$lineData = $jsondata->lineData; #Line - Items
				
				if( count($lineData) > 0 )
				{
					$branch_id = $jsondata->branch_id;
					
					$getOrganization = $this->captain_app_model->getBranchOrganization($branch_id);	
					$organization_id = isset($getOrganization[0]["organization_id"]) ? $getOrganization[0]["organization_id"] : NULL;
					
					#Get document Numbering start here
					$getDocumentData = $this->captain_app_model->getDocumentData($branch_id);
					$prefixName = isset($getDocumentData[0]['prefix_name']) ? $getDocumentData[0]['prefix_name'] : NULL;
					$startingNumber = isset($getDocumentData[0]['next_number']) ? $getDocumentData[0]['next_number'] : NULL;
					$suffixName = isset($getDocumentData[0]['suffix_name']) ? $getDocumentData[0]['suffix_name'] : NULL;
					$documentNumber = $prefixName.''.$startingNumber.''.$suffixName;
					#Get document Numbering end here

					$order_status = 'Created';
					$order_source = 'DINE_IN';
					$table_id = $jsondata->table_id;
					$waiter_id = $user_id = $jsondata->user_id; #Waiter Id
					$interface_status = 'Created';
					$currency = CURRENCY_CODE;

					$headerData= array(
						'order_number'                  => $documentNumber,
						'customer_id'                   => isset($jsondata->customer_id) ? $jsondata->customer_id : NULL,
						'address_id'                    => '-1',
						'ordered_date'                  => $this->date_time,
						'organization_id'               => $organization_id,
						'branch_id'                     => $branch_id, 
						'order_status'                  => $order_status,
						#'order_type'                    => NULL,
						#'payment_method'                => NULL,
						#'delivery_instructions'         => NULL,
						#'packing_instructions'          => NULL,
						#'payment_type'                  => isset($payment_type) ? $payment_type : NULL,
						#'card_number'                   => NULL,
						#'payment_transaction_ref_1'     => NULL,
						#'payment_transaction_status'    => NULL,
						'currency'                      => CURRENCY_CODE,
						#'delivery_options'              => NULL,
						#'paid_status'                   => isset($paid_status) ? $paid_status : 'N',
						'order_source'                  => $order_source,
						'waiter_id'                     => $waiter_id,
						'table_id'                      => $table_id,
						'interface_status'              => $interface_status,
						#'sub_table'              		=> isset($_POST['sub_table']) ? $_POST['sub_table'] : NULL,
						#'coupon_code'                   => NULL,
						#'coupon_amount'                 => NULL,
						#'wallet_amount'                 => NULL,

						'created_by'                    => $user_id,
						'created_date'                  => $this->date_time,
						'last_updated_by'               => $user_id,
						'last_updated_date'             => $this->date_time,
						'print_status'                  => 'N',
					);
					
					$this->db->insert('ord_order_interface_headers',$headerData);
					$interface_header_id = $this->db->insert_id();

					if($interface_header_id)
					{
						#Line Data start here

						#get Order Seq Number start
						$getOrderData = $this->captain_app_model->getOrderData($table_id,$interface_header_id);	

						if(count($getOrderData) > 0){	
							$order_seq_number = $getOrderData[0]["order_seq_number"] + 1;
						}else{

							$order_seq_number = 1;
						}
						#get Order Seq Number end

						foreach($lineData as $lineItems)
						{
							$cooking_instructions = $lineItems->cooking_instructions;

							$postLineData = array(
								'reference_header_id'    => $interface_header_id,
								'order_seq_number'       => $order_seq_number,
								'product_id'	         => $lineItems->product_id,
								'price'	                 => $lineItems->price,
								'quantity'	             => $lineItems->quantity,
								'cooking_instructions'	 => $lineItems->cooking_instructions,
								'tax_percentage'	     => DEFAULT_TAX,
								'line_status'	 	     => $order_status,

								'created_by'             => $user_id,
								'created_date'           => $this->date_time,
								'last_updated_by'        => $user_id,
								'last_updated_date'      => $this->date_time,
							);

							$this->db->insert('ord_order_interface_lines', $postLineData);
							$interface_line_id = $this->db->insert_id();

							#Update Cart Item Status Start
							$cart_id = isset($lineItems->cart_id) ? $lineItems->cart_id : NULL;

							if($cart_id !=NULL)
							{
								$UpdateCartStatus['cart_status'] = "Closed";
								$this->db->where('cart_id', $cart_id);
								$resultUpdateData = $this->db->update('ord_cart_items', $UpdateCartStatus);
							}
							#Update Cart Item Status End
						}



						#Line Data end here
						
						$response = array(	
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
					$response = array(	
						"httpCode" 		=> 200,
						"status"        => (int) 2,
						"message" 		=> 'Atleaset 1 line item is required!'
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
					
					#cancelled remarks start here
					$getCancelledRemarks = $this->common_model->lov('CANCELLEDREMARKS');
					$cancelledRemarks = [];
					if(count($getCancelledRemarks) > 0)
					{	
						foreach($getCancelledRemarks as $row)
						{
							$cancelledRemarks[] = array(	
								"listCode"  => $row['list_code'],
								"listValue" => $row['list_value']
							);
						}
					}
					#cancelled remarks end here

					$masterData = array(
						"captainCancelItemStatus"  => CAPTAIN_CANCEL_ITEM_STATUS,
						"cancelledRemarks"         => $cancelledRemarks,
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
		/* $updateQry = "UPDATE users SET card_token = CONCAT(card_token, '".$card_token."', ',') 
		WHERE user_id IN ( SELECT userId FROM vb_order_payment_reference WHERE outletID = '".$merchant_order_id."' )";
		$this->db->query($updateQry); */



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

}
?>
