<?php
defined('BASEPATH') OR exit('No direct script access allowed');
include_once('vendor/autoload.php');
class Web_dine_in extends CI_Controller 
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

	function login()
    {
		if (!empty($this->web_user_id))
        {
			redirect(base_url() . 'items.html', 'refresh');
		}

		$page_data['page_name']  = 'web_dine_in/login';
		$page_data['page_title'] = 'Login';

		if($_POST)
		{
			$mobile_number = $_POST["mobile_number"];
			$otp_number = otpNumber(6);

			$_SESSION['otp_number'] = $otp_number;

			$postData = array(
				'mobile_number'      => $_POST["mobile_number"],
				'active_flag'        => $this->active_flag,
				'created_by'         => '-1',
				'created_date'       => $this->date_time,
				'last_updated_by'    => '-1',
				'last_updated_date'  => $this->date_time,
			);

        	$otpMobileNumber = $mobile_number;
			$otpMessage = ('Your login OTP is : '.$otp_number);
			$sendSMS = sendSMS($otpMobileNumber,$otpMessage);
        
			if($sendSMS == 1)
			{
				$_SESSION['CUSTOMER_DATA'] = $postData;
				$this->session->set_flashdata('success_message', 'OTP sent successfully!');
				redirect(base_url()."user-verification-otp.html", 'refresh');
			}
			else
			{
				$this->session->set_flashdata('error_message' , 'Problem in SMS. Try again later!');
				redirect($_SERVER['HTTP_REFERER'], 'refresh');
			}
		}
		$this->load->view($this->template, $page_data);
	}

	public function verificationOtp()
	{
		$page_data['page_title'] = ' Verify OTP'; #Page Title
		$page_data['page_name']  = 'web_dine_in/verificationOtp';
		
		if($_POST)
		{
			$otp_number = "";
			if( isset($_SESSION['otp_number']) && !empty($_SESSION['otp_number']) )
			{
				$otp_number = $_SESSION['otp_number'];
			}
			
			$digit_1 = isset($_POST['digit_1']) ? $_POST['digit_1'] :"";
			$digit_2 = isset($_POST['digit_2']) ? $_POST['digit_2'] :"";
			$digit_3 = isset($_POST['digit_3']) ? $_POST['digit_3'] :"";
			$digit_4 = isset($_POST['digit_4']) ? $_POST['digit_4'] :"";
			$digit_5 = isset($_POST['digit_5']) ? $_POST['digit_5'] :"";
			$digit_6 = isset($_POST['digit_6']) ? $_POST['digit_6'] :"";
			
			$postOTP = $digit_1."".$digit_2."".$digit_3."".$digit_4."".$digit_5."".$digit_6;
			
			if( $otp_number  == $postOTP )
			{
				$CustomerData = $_SESSION['CUSTOMER_DATA'];
				
				$chkExist = $this->db->query("select customer_id from cus_consumers
				where mobile_number='".$CustomerData['mobile_number']."'")->result_array();

				if(count($chkExist) > 0)
				{
					$customer_id = isset($chkExist[0]['customer_id']) ? $chkExist[0]['customer_id'] : NULL;
				}
				else
				{
					$this->db->insert('cus_consumers', $CustomerData);
					$customer_id = $this->db->insert_id();	
				}

            	if($customer_id)
				{
					$chkuserIdExist = $this->db->query("select user_id, reference_id,user_name from per_user
					where user_name='".$CustomerData['mobile_number']."' ")->result_array();
					
					$userNameNew = $userName = isset($CustomerData['mobile_number']) ? $CustomerData['mobile_number'] : NULL;
					
					if(count($chkuserIdExist) == 0)
					{
						$userData['reference_id'] = $customer_id; 
						$userData['user_name'] = $userNameNew; 
                    
                  		$userData['created_by'] = -1; 
                   		$userData['created_date'] = $this->date_time;
                    	$userData['last_updated_by'] = -1; 
                    	$userData['last_updated_date'] = $this->date_time;
						
						$this->db->insert('per_user', $userData);
						$userID = $this->db->insert_id();
						
					}
					else
					{
						$userID = isset($chkuserIdExist[0]['user_id']) ? $chkuserIdExist[0]['user_id'] : '-1';
					
						$userData['last_updated_date'] = $this->date_time; 
						$userData['last_updated_by'] = $userID; 
						$this->db->where('user_id', $userID);
						$updateResult = $this->db->update('per_user', $userData);
					}
				}
        
				$_SESSION["SEARCH_CUSTOMER_ID"] = $userID;
				$this->session->set_userdata('WebUserID',$userID);
				unset($_SESSION["CUSTOMER_DATA"]);

				$this->session->set_flashdata('success_message' , 'OTP verified!');
				redirect(base_url().'items.html', 'refresh');
			}
			else
			{
				$this->session->set_flashdata('error_message' , 'Invalid OTP!');
				redirect(base_url()."user-verification-otp.html", 'refresh');
			}
		}
		
		$this->load->view($this->template, $page_data);
	}

	public function cancelOtp()
	{
		unset($_SESSION["CUSTOMER_DATA"]);
		redirect(base_url().'user-login.html', 'refresh');
	}

	public function logout()
	{
		$userid = $_SESSION["WebUserID"];
		
		$lastUpdatedDate = array(
			'last_login_status'  => 'N',
			'logout_date'        => $this->date_time,
			'last_updated_by'    => $userid,
			'last_updated_date'  => $this->date_time,
		);

		$this->db->where('user_id', $userid);
		$result = $this->db->update('per_user', $lastUpdatedDate);

		unset($_SESSION["WebUserID"]);
		unset($_SESSION["CUSTOMER_DATA"]);
		unset($_SESSION["WAITER_LOGIN"]);

		$this->session->sess_destroy();

		redirect(base_url().'login.html', 'refresh');
		//redirect(base_url().'user-login.html', 'refresh');
	}

	function items($waiter_user_id="")
    {
		#unset($_SESSION["SELECT_BRANCH"]);exit;
		#unset($_SESSION["SELECTED_TABLE_ID"]);

		if($waiter_user_id)
		{
			unset($_SESSION["SELECTED_TABLE_ID"]);
			unset($_SESSION["SEARCH_CUSTOMER_ID"]);

			if( $waiter_user_id == 1 ) #Admin
			{
				$this->session->set_userdata('WAITER_LOGIN','Y');
				$this->session->set_userdata('WebUserID',$waiter_user_id);
				redirect(base_url() . 'items.html', 'refresh');
			}
			else
			{
				$getUserRoles = $this->db->query("select role_id from per_user_roles where user_id = '".$waiter_user_id."'")->result_array();
				$userRoles = isset($getUserRoles[0]) ? $getUserRoles[0] : NULL;

				$role_id = isset($getUserRoles[0]["role_id"]) ? $getUserRoles[0]["role_id"] : NULL;
				
				$_SESSION["DINE_IN_ROLE_ID"] = $role_id;

				if($userRoles != NULL)
				{
					if (in_array(5, $getUserRoles[0])) #5 => Waiter
					{
						$this->session->set_userdata('WAITER_LOGIN','Y');
						$this->session->set_userdata('WebUserID',$waiter_user_id);
						redirect(base_url() . 'items.html', 'refresh');
					}
					else if ( in_array(3, $getUserRoles[0]) || in_array(3, $userRoles) ) #3 => if Cashier login send to mail admin
					{
						$this->session->set_userdata('WAITER_LOGIN','Y');
						$this->session->set_userdata('WebUserID',$waiter_user_id);
						redirect(base_url() . 'items.html', 'refresh');
					}
					else
					{
						redirect(base_url() . 'admin/home', 'refresh');
					}
				}
				else
				{
					redirect(base_url() . 'admin/home', 'refresh');
				}
			}
		}
		else
		{
			if (empty($this->web_user_id))
			{
				redirect(base_url() . 'user-login.html', 'refresh');
			}
		}
		
		$page_data['page_name']  = 'web_dine_in/items';
		$page_data['page_title'] = 'Items';
		$this->load->view($this->template, $page_data);
	}

	public function selectBranch($branch_id="")
	{
		$_SESSION["SELECT_BRANCH"] = $branch_id;
		redirect($_SERVER["HTTP_REFERER"], 'refresh');
	}

	#Add To Cart
	/* function addToCart($item_id="", $quantity="", $waiter_id=NULL, $table_id=NULL)
	{
		$cooking_instructions = !empty($_POST["cooking_instructions"]) ? $_POST["cooking_instructions"] : NULL;

		$branch_id = $_POST["branch_id"];
		$customer_id = $_POST["web_user_id"];
		$item_price = $_POST["item_price"];
		$item_id = $item_id;
		$quantity = $quantity;

		$branchQry = "select organization_id from branch where branch_id='".$branch_id."' ";
		$getOrganization = $this->db->query($branchQry)->result_array();

		$organization_id = isset($getOrganization[0]["organization_id"]) ? $getOrganization[0]["organization_id"] : NULL;

		if( $waiter_id != NULL && $table_id != NULL && !empty( $item_id )  )
		{
			$postData['organization_id'] = $organization_id;
			$postData['branch_id'] = $branch_id;
			#$postData['customer_id'] = $customer_id;
			$postData['item_id'] = $item_id;
			$postData['quantity'] = $quantity;
			$postData['price'] = $item_price;
			$postData['waiter_id'] = $waiter_id;
			$postData['table_id'] = $table_id;
				
			#Check Exist
			$checkExistQry = "select cart_items.cart_id from ord_cart_items as cart_items 
				where organization_id ='".$postData['organization_id']."'
				and	branch_id ='".$postData['branch_id']."'
				and	item_id ='".$postData['item_id']."'
				and	waiter_id ='".$waiter_id."'
				and	table_id ='".$table_id."'
			";

			$chkExist = $this->db->query($checkExistQry)->result_array();

			if( count($chkExist) == 0 )
			{
				$postData['active_flag'] = $this->active_flag;
				$postData['created_by'] = $customer_id;
				$postData['created_date'] = $this->date_time;
				$postData['last_updated_by'] = $customer_id;
				$postData['last_updated_date'] = $this->date_time;
				$postData['cooking_instructions'] = $cooking_instructions;
				
				$this->db->insert('ord_cart_items', $postData);
				$id = $this->db->insert_id();
			}
			else
			{
				$updateQry = "update ord_cart_items
					SET 
					quantity = $quantity, 
					last_updated_by = '".$customer_id."',
					last_updated_date = '".$this->date_time."',
					cooking_instructions = '".$cooking_instructions."',
					price = '".$item_price."'

					where 
					organization_id ='".$postData['organization_id']."'
					and	branch_id ='".$postData['branch_id']."'
					and	waiter_id ='".$waiter_id."'
					and	table_id ='".$table_id."'
					and	item_id ='".$postData['item_id']."'";
					
				$result = $this->db->query($updateQry);
			}

			$page_data['dineInOrders'] = $cartItems = $this->web_dine_in_model->getCartItems($organization_id,$branch_id,$customer_id,$waiter_id,$table_id);
			$totalCartItems = count($cartItems);

			$cartItemesPage = $this->load->view("themes/default/web_dine_in/cart_items.php", $page_data, true);

			$response = array(	
				"httpCode" 		  => 200,
				"status"          => (int) 1,
				"totalCartItems"  => $totalCartItems,
				"cartItemesPage"  => $cartItemesPage,
				"message" 		  => "Item added to cart!"
			);
			
			echo json_encode($response);
			exit;
			
		}
		else
		{
			if( !empty( $customer_id ) && !empty( $item_id ) )
			{
				$postData['organization_id'] = $organization_id;
				$postData['branch_id'] = $branch_id;
				$postData['customer_id'] = $customer_id;
				$postData['item_id'] = $item_id;
				$postData['quantity'] = $quantity;
				$postData['price'] = $item_price;
					
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
					$postData['created_by'] = $customer_id;
					$postData['created_date'] = $this->date_time;
					$postData['last_updated_by'] = $customer_id;
					$postData['last_updated_date'] = $this->date_time;
					$postData['cooking_instructions'] = $cooking_instructions;
					
					$this->db->insert('ord_cart_items', $postData);
					$id = $this->db->insert_id();
				}
				else
				{
					$updateQry = "update ord_cart_items
						SET 
						quantity = $quantity, 
						last_updated_by = '".$customer_id."',
						last_updated_date = '".$this->date_time."',
						cooking_instructions = '".$cooking_instructions."',
						price = '".$item_price."'
	
						where 
							organization_id ='".$postData['organization_id']."'
							and	branch_id ='".$postData['branch_id']."'
							and	customer_id ='".$postData['customer_id']."'
							and	item_id ='".$postData['item_id']."'";
						
					$result = $this->db->query($updateQry);
				}
	
				$page_data['dineInOrders'] = $cartItems = $this->web_dine_in_model->getCartItems($organization_id,$branch_id,$customer_id,$this->waiter_id,$this->selected_table_id);
				$totalCartItems = count($cartItems);
	
				$cartItemesPage = $this->load->view("themes/default/web_dine_in/cart_items.php", $page_data, true);
	
				$response = array(	
					"httpCode" 		  => 200,
					"status"          => (int) 1,
					"totalCartItems"  => $totalCartItems,
					"cartItemesPage"  => $cartItemesPage,
					"message" 		  => "Item added to cart!"
				);
				
				echo json_encode($response);
				exit;
			}
		}
	} */


	#Add To Cart
	function addToCart() //$item_id="", $quantity="", $waiter_id=NULL, $table_id=NULL, $branchID=NULL
	{
		$cooking_instructions = !empty($_POST["cooking_instructions"]) ? $_POST["cooking_instructions"] : NULL;

		if(isset($_POST["branch_id"]) && $_POST["branch_id"] != NULL) {
			$branch_id = $_POST["branch_id"];
		}
		else{
			$branch_id = $_POST["branchId"];
		}

		$customer_id = isset($_POST["web_user_id"]) ? $_POST["web_user_id"] : NULL;
		$item_price = $_POST["item_price"];
		$item_id = $_POST["item_id"];
		$quantity = $_POST["quantity"];
		$waiter_id = $_POST["waiter_id"];
		$table_id = $_POST["table_id"];

		$branchQry = "select organization_id from branch where branch_id='".$branch_id."' ";
		$getOrganization = $this->db->query($branchQry)->result_array();

		$organization_id = isset($getOrganization[0]["organization_id"]) ? $getOrganization[0]["organization_id"] : NULL;

		if( $waiter_id != NULL && $table_id != NULL && !empty( $item_id )  )
		{
			$postData['organization_id'] = $organization_id;
			$postData['branch_id'] = $branch_id;
			#$postData['customer_id'] = $customer_id;
			$postData['item_id'] = $item_id;
			$postData['quantity'] = $quantity;
			$postData['price'] = $item_price;
			$postData['waiter_id'] = $waiter_id;
			$postData['table_id'] = $table_id;
				
			#Check Exist
			$checkExistQry = "select cart_items.cart_id from ord_cart_items as cart_items 
				where organization_id ='".$postData['organization_id']."'
				and	branch_id ='".$postData['branch_id']."'
				and	item_id ='".$postData['item_id']."'
				and	waiter_id ='".$waiter_id."'
				and	table_id ='".$table_id."'
			";

			$chkExist = $this->db->query($checkExistQry)->result_array();

			if( count($chkExist) == 0 )
			{
				$postData['active_flag'] = $this->active_flag;
				$postData['created_by'] = $customer_id;
				$postData['created_date'] = $this->date_time;
				$postData['last_updated_by'] = $customer_id;
				$postData['last_updated_date'] = $this->date_time;
				$postData['cooking_instructions'] = $cooking_instructions;
				
				$this->db->insert('ord_cart_items', $postData);
				$id = $this->db->insert_id();
			}
			else
			{
				
				/* $updateQry = "update ord_cart_items
					SET 
					quantity = $quantity, 
					last_updated_by = '".$customer_id."',
					last_updated_date = '".$this->date_time."',
					cooking_instructions = '".$cooking_instructions."',
					price = '".$item_price."'

					where 
					organization_id ='".$postData['organization_id']."'
					and	branch_id ='".$postData['branch_id']."'
					and	waiter_id ='".$waiter_id."'
					and	table_id ='".$table_id."'
					and	item_id ='".$postData['item_id']."'";
					
				$result = $this->db->query($updateQry); */

				$postData['active_flag'] = $this->active_flag;
				$postData['created_by'] = $customer_id;
				$postData['created_date'] = $this->date_time;
				$postData['last_updated_by'] = $customer_id;
				$postData['last_updated_date'] = $this->date_time;
				$postData['cooking_instructions'] = $cooking_instructions;
				
				$this->db->insert('ord_cart_items', $postData);
				$id = $this->db->insert_id();
			}

			$page_data['dineInOrders'] = $cartItems = $this->web_dine_in_model->getCartItems($organization_id,$branch_id,$customer_id,$waiter_id,$table_id);
			$totalCartItems = count($cartItems);

			$cartItemesPage = $this->load->view("themes/default/web_dine_in/cart_items.php", $page_data, true);

			$response = array(	
				"httpCode" 		  => 200,
				"status"          => (int) 1,
				"totalCartItems"  => $totalCartItems,
				"cartItemesPage"  => $cartItemesPage,
				"message" 		  => "Item added to cart!"
			);
			
			echo json_encode($response);
			exit;
			
		}
		else
		{
			if( !empty( $customer_id ) && !empty( $item_id ) )
			{
				$postData['organization_id'] = $organization_id;
				$postData['branch_id'] = $branch_id;
				$postData['customer_id'] = $customer_id;
				$postData['item_id'] = $item_id;
				$postData['quantity'] = $quantity;
				$postData['price'] = $item_price;
					
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
					$postData['created_by'] = $customer_id;
					$postData['created_date'] = $this->date_time;
					$postData['last_updated_by'] = $customer_id;
					$postData['last_updated_date'] = $this->date_time;
					$postData['cooking_instructions'] = $cooking_instructions;
					
					$this->db->insert('ord_cart_items', $postData);
					$id = $this->db->insert_id();
				}
				else
				{
					$updateQry = "update ord_cart_items
						SET 
						quantity = $quantity, 
						last_updated_by = '".$customer_id."',
						last_updated_date = '".$this->date_time."',
						cooking_instructions = '".$cooking_instructions."',
						price = '".$item_price."'
	
						where 
						organization_id ='".$postData['organization_id']."'
						and	branch_id ='".$postData['branch_id']."'
						and	customer_id ='".$postData['customer_id']."'
						and	item_id ='".$postData['item_id']."'";
						
					$result = $this->db->query($updateQry);
				}
	
				$page_data['dineInOrders'] = $cartItems = $this->web_dine_in_model->getCartItems($organization_id,$branch_id,$customer_id,$this->waiter_id,$this->selected_table_id);
				$totalCartItems = count($cartItems);
	
				$cartItemesPage = $this->load->view("themes/default/web_dine_in/cart_items.php", $page_data, true);
	
				$response = array(	
					"httpCode" 		  => 200,
					"status"          => (int) 1,
					"totalCartItems"  => $totalCartItems,
					"cartItemesPage"  => $cartItemesPage,
					"message" 		  => "Item added to cart!"
				);
				
				echo json_encode($response);
				exit;
			}
		}
	}

	function posItemSearch()
    {
		if(isset($_POST["query"]))  
		{  
			$branch_id = $this->selected_branch;
			$keywords = "concat('%','".serchFilter($_POST["query"])."','%')";
			$admin_user_id = isset($this->web_user_id) ? $this->web_user_id : 1;
			$item_list = $this->web_dine_in_model->getPosItemSearch($keywords,$branch_id,$admin_user_id);
			
			$output = '<ul class="list-unstyled-pos">';  
			
			if( count($item_list) > 0 )  
			{  
				foreach($item_list as $row)  
				{	
					$item_id = $row["item_id"];
					#$item_description = ucfirst($row["item_name"]);

					if($row["item_name"] && $row["short_code"]){
						$itemName = $row["item_name"]." - ".$row["short_code"];
					}else{
						$itemName = $row["item_name"];
					}
					
					$output .= '<li data-toggle="modal" onclick="showCartModel('.$item_id.')" >'.$itemName.'</li>';  
				}  
			}  
			else  
			{  
				$item_id = NULL;
				$output .= '<li onclick="return selectSearchPosItems(\'' .$item_id. '\');">No Items</li>';  
			}
			$output .= '</ul>';  
			echo $output;  
		}
	}
	
	function insertPosOrderItems($type="",$button_type="",$direct_save_print="")
	{
		$selected_table_id = isset($_POST['table_id']) ? $_POST['table_id'] : NULL;
		$branch_id = !empty($this->selected_branch) ? $this->selected_branch : $default_branch_id;

		$getRoleDetails = $this->web_fine_dine_model->getCaptainRoleDetails($branch_id,$this->dine_in_role_id);
        $role_code = isset($getRoleDetails[0]["role_code"]) ? $getRoleDetails[0]["role_code"] : NULL;
		
		$interFaceQry = "select interface_header_id from ord_order_interface_headers 
		where 1=1
		AND table_id='".$selected_table_id."'
		AND branch_id='".$branch_id."'
		AND order_status='Created'
		";
		$getInterFaceDetails = $this->db->query($interFaceQry)->result_array();
		$interface_header_id = isset($getInterFaceDetails[0]["interface_header_id"]) ? $getInterFaceDetails[0]["interface_header_id"] : NULL;

		if($role_code == "cashier")
		{
			$printStatusData = array(
				'last_updated_by'      => $this->user_id,
				'last_updated_date'    => $this->date_time,
				'bill_print_status'    => 'Pending',
			);
			$this->db->where('interface_header_id', $interface_header_id);
			$updateBillPrintStatus = $this->db->update('ord_order_interface_headers', $printStatusData);	
		} 

		
		/* 	if(isset($_POST["dine_in_interface_header_id"]) && !empty($_POST["dine_in_interface_header_id"])){
			$interface_header_id = isset($_POST["dine_in_interface_header_id"]) ? $_POST["dine_in_interface_header_id"] : NULL;
		}else if(isset($_POST["interface_header_id"]) && !empty($_POST["interface_header_id"])){
			$interface_header_id = isset($_POST["interface_header_id"]) ? $_POST["interface_header_id"] : NULL;
		}else{
			$interface_header_id = NULL;
		} */

		
		if( isset($_POST['pos_dine_in_type']) && $_POST['pos_dine_in_type'] == "TAKEAWAY") #POS
		{
			$documentQry = "select doc_num_id,prefix_name,suffix_name,next_number 
			from doc_document_numbering as dm
			left join sm_list_type_values ltv on 
			ltv.list_type_value_id = dm.doc_type
			where 1=1
			and dm.doc_document_type = 'pos-orders'
			and dm.branch_id = '".$branch_id."'
			and ltv.list_code = 'CUS_ORD' 
			and dm.active_flag = 'Y'
			and coalesce(dm.from_date,CURDATE()) <= CURDATE() 
			and coalesce(dm.to_date,CURDATE()) >= CURDATE()
			";
		}
		else if( isset($_POST['pos_dine_in_type']) && $_POST['pos_dine_in_type'] == "DINE_IN") #POS
		{
			$documentQry = "select doc_num_id,prefix_name,suffix_name,next_number 
			from doc_document_numbering as dm
			left join sm_list_type_values ltv on 
				ltv.list_type_value_id = dm.doc_type
			where 1=1
				and dm.doc_document_type = 'dine-in-orders'
				and dm.branch_id = '".$branch_id."'
				and ltv.list_code = 'CUS_ORD' 
				and dm.active_flag = 'Y'
				and coalesce(dm.from_date,CURDATE()) <= CURDATE() 
				and coalesce(dm.to_date,CURDATE()) >= CURDATE()
			";
		}

		$getDocumentData = $this->db->query($documentQry)->result_array();
		
		$prefixName = isset($getDocumentData[0]['prefix_name']) ? $getDocumentData[0]['prefix_name'] : NULL;
		$startingNumber = isset($getDocumentData[0]['next_number']) ? $getDocumentData[0]['next_number'] : NULL;
		$suffixName = isset($getDocumentData[0]['suffix_name']) ? $getDocumentData[0]['suffix_name'] : NULL;
		$documentNumber = $prefixName.''.$startingNumber.''.$suffixName;

		#if( (($type == 'order_interface_tbl') && (!empty($button_type) && $button_type != 'SAVE_PRINT')) || ($direct_save_print == "direct_save_print") )
		if( ( ($type == 'order_interface_tbl') && (!empty($button_type)) ) || ( $direct_save_print == "direct_save_print" ) )
		{	
			if($_POST)
			{
				$orgQry = "select organization_id from branch where branch_id='".$branch_id."' ";
				$getOrganization = $this->db->query($orgQry)->result_array();
				$organization_id = isset($getOrganization[0]['organization_id']) ? $getOrganization[0]['organization_id'] : NULL;
				
				if(count($getDocumentData) > 0)
				{
					#Update Next Val DOC Number tbl start
					$nextValue = $startingNumber + 1;
					$doc_num_id = isset($getDocumentData[0]['doc_num_id']) ? $getDocumentData[0]['doc_num_id']:"";
					
					$UpdateData['next_number'] = $nextValue;
					$this->db->where('doc_num_id', $doc_num_id);
					$this->db->where('branch_id', $branch_id);
					$resultUpdateData = $this->db->update('doc_document_numbering', $UpdateData);
					#Update Next Val DOC Number tbl end

					if( isset($_POST['payment_method']) && $_POST['payment_method'] == 5) //Cash
					{
						$payment_type = 'Cash';
						$paid_status = 'Y';	
					}
					else if(isset($_POST['payment_method']) && $_POST['payment_method'] == 6) //Card
					{
						$payment_type = 'Card';
						$paid_status = 'Y';	
					}
					else if(isset($_POST['payment_method']) && $_POST['payment_method'] == 7) //UPI
					{
						$payment_type = 'UPI';
						$paid_status = 'Y';
					}
					else
					{
						$payment_type = NULL;
						$paid_status =  NULL;
					}

					$payment_transaction_status = NULL;
					
					$payment_method = isset($_POST['payment_method']) ? $_POST['payment_method'] : NULL;
					$sub_table = !empty($_POST['sub_table']) ? $_POST['sub_table'] : NULL;

					$_SESSION['pos_dine_in_type'] = isset($_POST['pos_dine_in_type']) ? $_POST['pos_dine_in_type'] : NULL;

					if( isset($_POST['pos_dine_in_type']) && $_POST['pos_dine_in_type'] == "TAKEAWAY") #POS
					{
						$order_source = 'POS';
						$waiter_id = NULL;
						$table_id  = NULL;	
						$order_type = 1; #Take Away

						#$interface_status = NULL;
						#$order_status = "Delivered";

						if( $button_type == "SAVE" && $direct_save_print == "" )
						{
							$order_status = $interface_status = "Printed";
							// $order_status = $interface_status = "Created";
						}
						#else if($button_type == "SAVE" &&  $direct_save_print == "direct_save_print")
						else if( $button_type == "SAVE_PRINT" ||  $direct_save_print == "direct_save_print" )
						{
							$order_status = $interface_status = "Printed";
						}
						else
						{
							$order_status = $interface_status = NULL;
						}
					}
					else if( isset($_POST['pos_dine_in_type']) && $_POST['pos_dine_in_type'] == "DINE_IN") #DINE_IN
					{					
						$order_source = 'DINE_IN';
						$waiter_id = isset($_POST['waiter_id']) ? $_POST['waiter_id'] : NULL;
						$table_id  = isset($_POST['table_id']) ? $_POST['table_id'] : NULL;
						$order_type = NULL;

						if($button_type == "SAVE" && $direct_save_print == "")
						{
							$order_status = $interface_status = "Created";
						}
						//else if($button_type == "SAVE" &&  $direct_save_print == "direct_save_print")
						else if($button_type == "SAVE_PRINT" ||  $direct_save_print == "direct_save_print")
						{
							$order_status = $interface_status = "Printed";
						}
						else
						{
							$order_status = $interface_status = NULL;
						}
					}
					else
					{
						$order_source = NULL;
						$waiter_id = NULL;
						$table_id  = NULL;
						$order_status = $interface_status = NULL;
					}

					if($interface_header_id !=NULL && $sub_table !=NULL)
					{
						$interfaceTblQry = "select interface_header_id,order_number from ord_order_interface_headers 
						where 1=1
						and interface_header_id ='".$interface_header_id."'
						and sub_table ='".$sub_table."'
						";
						$checkInterfaceTbl = $this->db->query($interfaceTblQry)->result_array();
					}
					else
					{
						$interfaceTblQry = "select interface_header_id,order_number from ord_order_interface_headers 
						where 1=1
						and interface_header_id ='".$interface_header_id."'";
						$checkInterfaceTbl = $this->db->query($interfaceTblQry)->result_array();
					}
					
					$new_customer_id = isset($_POST["new_customer_id"]) ? $_POST["new_customer_id"] : '-1';
					
					if( count($checkInterfaceTbl) == 0 ) #Insert
					{
						if(
							isset($_POST["pos_dine_in_type"]) 
							&& $sub_table == NULL
							&& ($_POST["pos_dine_in_type"] == "DINE_IN" || $_POST["pos_dine_in_type"] == "POS")
						)
						{
							$dine_in_interface_header_id = isset($_POST["dine_in_interface_header_id"]) ? $_POST["dine_in_interface_header_id"] : NULL;

							$checkInterFaceHeaderQry = "select interface_header_id,order_number from ord_order_interface_headers 
								where 1=1
								and interface_header_id ='".$dine_in_interface_header_id."'";
							$checkInterfaceTbl1 = $this->db->query($checkInterFaceHeaderQry)->result_array();
							#$this->db->where('reference_header_id', $dine_in_interface_header_id);
							#$this->db->delete('ord_order_interface_lines');
						}
						else
						{
							$checkInterfaceTbl1 = array();
						}

						$headerData= array(
							'order_number'                  => $documentNumber,
							'customer_id'                   => isset($_POST["new_customer_id"]) ? $_POST["new_customer_id"] : '-1',
							'address_id'                    => '-1',
							'ordered_date'                  => $this->date_time,
							'organization_id'               => $organization_id,
							'branch_id'                     => $branch_id, 
							'order_status'                  => $order_status,
							'order_type'                    => $order_type, #1=> TAKE AWAY, 2=>Delivery
							'payment_method'                => $payment_method,
							'delivery_instructions'         => NULL,
							'packing_instructions'          => NULL,
							'payment_type'                  => isset($payment_type) ? $payment_type : NULL,
							'card_number'                   => NULL,
							'payment_transaction_ref_1'     => NULL,
							'payment_transaction_status'    => NULL,
							'currency'                      => CURRENCY_CODE,
							'delivery_options'              => NULL,
							'paid_status'                   => isset($paid_status) ? $paid_status : 'N',
							'order_source'                  => $order_source,
							'waiter_id'                     => $waiter_id,
							'table_id'                      => $table_id,
							'interface_status'              => $interface_status,
							'sub_table'              		=> !empty($_POST['sub_table']) ? $_POST['sub_table'] : NULL,
							'coupon_code'                   => NULL,
							'coupon_amount'                 => NULL,
							'wallet_amount'                 => NULL,

							'created_by'                    => $this->user_id,
							'created_date'                  => $this->date_time,
							'last_updated_by'               => $this->user_id,
							'last_updated_date'             => $this->date_time,
							'print_status'                  => 'N',
						);
						
						if(count($checkInterfaceTbl1) > 0)
						{
							$updateData = array(
								'created_by'                    => $this->user_id,
								'created_date'                  => $this->date_time,
								'last_updated_by'               => $this->user_id,
								'last_updated_date'             => $this->date_time,
								'order_status'                  => $order_status,
								'print_status'                  => 'N',
							);
							$this->db->where('interface_header_id', $dine_in_interface_header_id);
							$update_result = $this->db->update('ord_order_interface_headers', $updateData);
							$this->db->where('reference_header_id', $dine_in_interface_header_id);
							$this->db->delete('ord_order_interface_lines');

							$interface_header_id = $dine_in_interface_header_id;
						}
						else
						{
							$this->db->insert('ord_order_interface_headers',$headerData);
							$interface_header_id = $this->db->insert_id();
						}

						if($interface_header_id)
						{
							
							$count = count(array_filter($_POST['text_item_id']));

							#get Order Seq Number start
							$getOrderQuery = "
							select 
							line_tbl.interface_line_id,
							line_tbl.order_seq_number 
							from ord_order_interface_lines as line_tbl
							left join ord_order_interface_headers as header_tbl on
							header_tbl.interface_header_id = line_tbl.reference_header_id
							where 1=1 
							and header_tbl.table_id= '".$table_id."'
							and line_tbl.reference_header_id='".$interface_header_id."'
							order by line_tbl.order_seq_number desc
							limit 0,1";
							$getOrderData = $this->db->query($getOrderQuery)->result_array();

							if(count($getOrderData) > 0)
							{	
								$order_seq_number = $getOrderData[0]["order_seq_number"] + 1;
							}else{

								$order_seq_number = 1;
							}
							#get Order Seq Number end

							for($dp=0;$dp<$count;$dp++)
							{
								$product_id = isset($_POST['text_item_id'][$dp]) ? $_POST['text_item_id'][$dp]:NULL;
								$price = isset($_POST['rate'][$dp]) ? $_POST['rate'][$dp]:NULL;
								$quantity = isset($_POST['quantity'][$dp]) ? $_POST['quantity'][$dp]:NULL;
								$cooking_instructions = isset($_POST['cooking_instructions'][$dp]) ? $_POST['cooking_instructions'][$dp]:NULL;
								

								$discountType = isset($_POST['discount_type']) ? $_POST['discount_type'] : NULL;
								$discount = isset($_POST['discount']) ? $_POST['discount'] : NULL;

								if( ($discountType != NULL && !empty($discountType)) && ($discount != NULL && !empty($discount)) )
								{
									if($discountType == 1) #Percentage
									{
										$discount_percentage = $discount; 
										$discount_amount = NULL;
									}
									else if($discountType == 2) #Amount
									{
										$discount_percentage = NULL; 
										$discount_amount = $discount;
									}
								}
								else
								{
									$discount_percentage = NULL;
									$discount_amount = NULL;
								}

								$tax_percentage = isset($_POST['tax_value']) ? $_POST['tax_value'] : NULL;	

								

								$lineData = array(
									'reference_header_id'=> $interface_header_id,
									'order_seq_number'=> $order_seq_number,
									'product_id'	     => $product_id,
									'price'	             => $price,
									'quantity'	             => $quantity,
									'cooking_instructions'	 => $cooking_instructions,

									'offer_percentage'	 => $discount_percentage,
									'offer_amount'	     => $discount_amount,
									'tax_percentage'	 => $tax_percentage,
									'line_status'	 	 => $order_status,
									'created_by'         => $this->user_id,
									'created_date'       => $this->date_time,
									'last_updated_by'    => $this->user_id,
									'last_updated_date'  => $this->date_time,
								);
								$this->db->insert('ord_order_interface_lines', $lineData);
								$interface_line_id = $this->db->insert_id();

								#Update Cart Items start here
								$cart_id = isset($_POST['cart_id'][$dp]) ? $_POST['cart_id'][$dp]:NULL;
								if($cart_id != NULL)
								{
									$updateCartItems = array(
										'quantity'	         	 => $quantity,
										'cooking_instructions'	 => $cooking_instructions,
										'last_updated_by'        => $this->user_id,
										'last_updated_date'      => $this->date_time,
									);	
									$this->db->where('cart_id', $cart_id);
									$update_result = $this->db->update('ord_cart_items', $updateCartItems);
								}
								#Update Cart Items end here	
							}

							$response["pos_items"] = array(	
								"documentNumber"       => $documentNumber,
								"interface_header_id"  => $interface_header_id,
								"status"               => 1,
								"message"              => "Order Created Successfully!"
							);
						}
					}
					else if( count($checkInterfaceTbl) > 0 ) #Update
					{
						
						/* $order_number = $checkInterfaceTbl[0]["order_number"];
						$interface_header_id = $checkInterfaceTbl[0]["interface_header_id"]; */

						#Suresh New Changes start here
						/* $this->db->where('reference_header_id', $interface_header_id);
						$this->db->delete('ord_order_interface_lines'); */
						#Suresh New Changes end here

						$headerData= array(
							#'order_number'                  => $documentNumber,
							'customer_id'                   => isset($_POST["new_customer_id"]) ? $_POST["new_customer_id"] : '-1',
							'address_id'                    => '-1',
							'ordered_date'                  => $this->date_time,
							'organization_id'               => $organization_id,
							'branch_id'                     => $branch_id, 
							'order_status'                  => $order_status,
							'interface_status'              => $interface_status,
							'order_type'                    => $order_type, #1=> TAKE AWAY, 2=>Delivery
							'payment_method'                => $payment_method,
							'delivery_instructions'         => NULL,
							'packing_instructions'          => NULL,
							'payment_type'                  => isset($payment_type) ? $payment_type : NULL,
							'card_number'                   => NULL,
							'payment_transaction_ref_1'     => NULL,
							'payment_transaction_status'    => NULL,
							'currency'                      => CURRENCY_CODE,
							'delivery_options'              => NULL,
							'paid_status'                   => isset($paid_status) ? $paid_status : 'N',
							
							'order_source'                  => $order_source,
							'waiter_id'                     => $waiter_id,
							'table_id'                      => $table_id,

							'coupon_code'                   => NULL,
							'coupon_amount'                 => NULL,
							'wallet_amount'                 => NULL,

							#'created_by'                    => $this->user_id,
							#'created_date'                  => $this->date_time,
							'last_updated_by'               => $this->user_id,
							'last_updated_date'             => $this->date_time,
							'print_status'             	    => 'N',
						);

						$this->db->where('interface_header_id', $interface_header_id);
						$result = $this->db->update('ord_order_interface_headers', $headerData);
						
						if($result)
						{
							$count = count(array_filter($_POST['text_item_id']));


							#get Order Seq Number start
							$getOrderQuery = "
							select 
							line_tbl.interface_line_id,
							line_tbl.order_seq_number 
							from ord_order_interface_lines as line_tbl
							left join ord_order_interface_headers as header_tbl on
							header_tbl.interface_header_id = line_tbl.reference_header_id
							where 1=1 
							and header_tbl.table_id= '".$table_id."'
							and line_tbl.reference_header_id='".$interface_header_id."'
							order by line_tbl.order_seq_number desc
							limit 0,1";
							
							$getOrderData = $this->db->query($getOrderQuery)->result_array();

							if( count($getOrderData) > 0)
							{	
								$order_seq_number = $getOrderData[0]["order_seq_number"] + 1;
							}
							else
							{

								$order_seq_number = 1;
							}
							#get Order Seq Number end

							for($dp=0;$dp<$count;$dp++)
							{
								$interface_line_id = isset($_POST['interface_line_id'][$dp]) ? $_POST['interface_line_id'][$dp]:NULL;
								
								$previous_quantity = isset($_POST['exist_quantity'][$dp]) ? $_POST['exist_quantity'][$dp]:NULL; #Previous Qty
								$quantity = isset($_POST['quantity'][$dp]) ? $_POST['quantity'][$dp]:NULL; #Current Qty
								$cooking_instructions = isset($_POST['cooking_instructions'][$dp]) ? $_POST['cooking_instructions'][$dp]:NULL; #Current Qty

								$product_id = isset($_POST['text_item_id'][$dp]) ? $_POST['text_item_id'][$dp] : NULL;

								$lineDataQry = "select interface_line_id from ord_order_interface_lines
								where 1=1
								and reference_header_id ='".$interface_header_id."' 
								and interface_line_id ='".$interface_line_id."' 
								";
								$checkLineData = $this->db->query($lineDataQry)->result_array();

								$price = isset($_POST['rate'][$dp]) ? $_POST['rate'][$dp]:NULL;
								
								$discountType = isset($_POST['discount_type']) ? $_POST['discount_type'] : NULL;
								$discount = isset($_POST['discount']) ? $_POST['discount'] : NULL; 

								if( ($discountType != NULL && !empty($discountType)) && ($discount != NULL && !empty($discount)) )
								{
									if($discountType == 1) #Percentage
									{
										$discount_percentage = $discount; 
										$discount_amount = NULL;
									}
									else if($discountType == 2) #Amount
									{
										$discount_percentage = NULL; 
										$discount_amount = $discount;
									}
								}
								else
								{
									$discount_percentage = NULL;
									$discount_amount = NULL;
								}

								$tax_percentage = isset($_POST['tax_value']) ? $_POST['tax_value'] : NULL;

								if(count($checkLineData) > 0) #Update
								{
									if( $quantity > $previous_quantity)
									{
										$lineData = array(
											'quantity'	         => $quantity,
											'previous_quantity'	 => $previous_quantity,
											'offer_percentage'	 => $discount_percentage,
											'offer_amount'	     => $discount_amount,
											'tax_percentage'	     => $tax_percentage,
											'cooking_instructions'	 => $cooking_instructions,
											'last_updated_by'    => $this->user_id,
											'last_updated_date'  => $this->date_time,
											'kot_print_status'   => 'N',
										);	

										$this->db->where('reference_header_id', $interface_header_id);
										$this->db->where('interface_line_id', $interface_line_id);
										$update_result = $this->db->update('ord_order_interface_lines', $lineData);
									}
									else if($quantity < $previous_quantity)
									{
										$lineData = array(
											'quantity'	         => $quantity,
											'previous_quantity'	 => $previous_quantity,
											'offer_percentage'	 => $discount_percentage,
											'offer_amount'	     => $discount_amount,
											'tax_percentage'	 => $tax_percentage,
											'cooking_instructions'	 => $cooking_instructions,
											
											'last_updated_by'    => $this->user_id,
											'last_updated_date'  => $this->date_time,	
										);

										$this->db->where('reference_header_id', $interface_header_id);
										$this->db->where('interface_line_id', $interface_line_id);
										$update_result = $this->db->update('ord_order_interface_lines', $lineData);
									}	
								}
								else if( count($checkLineData) == 0 )#Insert
								{ 
									$lineData = array(
										'order_seq_number'=> $order_seq_number,
										'reference_header_id'=> $interface_header_id,
										'product_id'	     => $product_id,
										'price'	             => $price,
										'quantity'	         => $quantity,
										'attribute_1'	     => "test",
										'cooking_instructions'	 => $cooking_instructions,
										'offer_percentage'	 => $discount_percentage,
										'offer_amount'	     => $discount_amount,
										'tax_percentage'	 => $tax_percentage,
										'line_status'	 	 => $order_status,

										'created_by'         => $this->user_id,
										'created_date'       => $this->date_time,
										'last_updated_by'    => $this->user_id,
										'last_updated_date'  => $this->date_time,
									);

									$this->db->insert('ord_order_interface_lines', $lineData);
									$interface_line_id = $this->db->insert_id();
								}

								#Update Cart Items start here
								$cart_id = isset($_POST['cart_id'][$dp]) ? $_POST['cart_id'][$dp]:NULL;
								if($cart_id != NULL)
								{
									$updateCartItems = array(
										'quantity'	         	 => $quantity,
										'cooking_instructions'	 => $cooking_instructions,
										'last_updated_by'        => $this->user_id,
										'last_updated_date'      => $this->date_time,
									);	
									$this->db->where('cart_id', $cart_id);
									$update_result = $this->db->update('ord_cart_items', $updateCartItems);
								}
								#Update Cart Items end here	
							}

							$response["pos_items"] = array(	
								"documentNumber"       => $documentNumber,
								"interface_header_id"  => $interface_header_id,
								"status"               => 1,
								"message"              => "Order Created Successfully!"
							);
						}
					}


					if( count($_POST["cart_id"]) > 0)
					{
						foreach($_POST["cart_id"] as $key => $value)
						{
							$UpdateCartStatus['cart_status'] = "Closed";
							
							$this->db->where('cart_id', $value);
							$resultUpdateData = $this->db->update('ord_cart_items', $UpdateCartStatus);
						}
					}

					
					unset($_SESSION["SELECTED_TABLE_ID"]);
					unset($_SESSION["SEARCH_CUSTOMER_ID"]);
				}
				else
				{
					$response["pos_items"] = array(	
						"status"       => 2,
						"message"      => "Order sequence does not exist, Order generation failed. Please contact to admin!"
					);
				}

				echo json_encode($response);
				exit;
			}
		}
		else if( $type == 'order_base_tbl' ) 
		{
			if( isset($_POST["interface_header_id"] ) && !empty($_POST["interface_header_id"]) )
			{
				$interface_header_id = $_POST["interface_header_id"];
				
				/* if(isset($_POST["pos_dine_in_type"]) && $_POST["pos_dine_in_type"] == "DINE_IN")
				{ */
					$updateHeaerInterFaceStatus = array(
						"interface_status" 	   => "Success",
						"order_status" 		   => "Delivered",
						'last_updated_by'      => $this->user_id,
						'last_updated_date'    => $this->date_time,
					);
					$this->db->where('interface_header_id', $interface_header_id);
					$headerResult = $this->db->update('ord_order_interface_headers', $updateHeaerInterFaceStatus);

					$updateLineInterFaceStatus = array(
						"line_status" 		   => "Delivered",
						'last_updated_by'      => $this->user_id,
						'last_updated_date'    => $this->date_time,
					);
					$this->db->where('reference_header_id', $interface_header_id);
					$this->db->where('line_status !=', "Cancelled");
					$lineResult = $this->db->update('ord_order_interface_lines', $updateLineInterFaceStatus);
				#}

				$InterFaceHeaderQry = "select * from ord_order_interface_headers 
					where interface_header_id='".$interface_header_id."' ";
				$interFaceHeader = $this->db->query($InterFaceHeaderQry)->result_array();

				if(count($interFaceHeader) > 0)
				{
					if( isset($_POST['payment_method']) && $_POST['payment_method'] == 5) //Cash
					{
						$payment_type = 'Cash';
						$paid_status = 'Y';	
					}
					else if(isset($_POST['payment_method']) && $_POST['payment_method'] == 6) //Card
					{
						$payment_type = 'Card';
						$paid_status = 'Y';
						
					}
					else if(isset($_POST['payment_method']) && $_POST['payment_method'] == 7) //UPI
					{
						$payment_type = 'UPI';
						$paid_status = 'Y';
					}

					$payment_method = isset($_POST['payment_method']) ? $_POST['payment_method'] : NULL;

					if(isset($_POST['customer_id']) && $_POST['customer_id'] > 0)
					{
						$customer_id = isset($_POST['customer_id']) ? $_POST['customer_id'] : NULL;
					}
					else
					{
						if(isset($_POST['mobile_number']) && !empty($_POST['mobile_number']))
						{
							$customerData = array(
								"mobile_number"        => $_POST['mobile_number'],
								"customer_name"        => !empty($_POST['customer_name']) ? $_POST['customer_name'] : NULL,
								"address1"             => !empty($_POST['customer_address']) ? $_POST['customer_address'] : NULL,
								"mobile_num_verified"  => 'Y',
								'created_by'           => $this->user_id,
								'created_date'         => $this->date_time,
								'last_updated_by'      => $this->user_id,
								'last_updated_date'    => $this->date_time,
							);

							$this->db->insert('cus_consumers',$customerData);
							$customer_id = $this->db->insert_id();
						}else{
							$customer_id = NULL;
						}
					}

					$waiter_id = isset($interFaceHeader[0]['waiter_id']) ? $interFaceHeader[0]['waiter_id'] : NULL;
					$table_id = isset($interFaceHeader[0]['table_id']) ? $interFaceHeader[0]['table_id'] : NULL;
					$new_customer_id = isset($customer_id) ? $customer_id : NULL;

					$headerData = array(
						"reference_header_id"  => $interface_header_id,
						"order_number" 		   => isset($interFaceHeader[0]['order_number']) ? $interFaceHeader[0]['order_number'] : NULL, #$documentNumber,
						"customer_id" 		   => isset($customer_id) ? $customer_id : NULL,
						"address_id" 		   => isset($interFaceHeader[0]['address_id']) ? $interFaceHeader[0]['address_id'] : NULL,
						"ordered_date" 		   => isset($interFaceHeader[0]['ordered_date']) ? $interFaceHeader[0]['ordered_date'] : NULL,
						"organization_id" 	   => isset($interFaceHeader[0]['organization_id']) ? $interFaceHeader[0]['organization_id'] : NULL,
						"branch_id" 	       => isset($interFaceHeader[0]['branch_id']) ? $interFaceHeader[0]['branch_id'] : NULL,
						"order_status" 	       => isset($interFaceHeader[0]['order_status']) ? $interFaceHeader[0]['order_status'] : NULL,
						"order_type" 	       => isset($interFaceHeader[0]['order_type']) ? $interFaceHeader[0]['order_type'] : NULL,
						"payment_method" 	   => $payment_method,
						"paid_status" 	       => isset($paid_status) ? $paid_status : 'N',
						"order_source" 	       => isset($interFaceHeader[0]['order_source']) ? $interFaceHeader[0]['order_source'] : NULL,
						"table_id" 	           => isset($interFaceHeader[0]['table_id']) ? $interFaceHeader[0]['table_id'] : NULL,
						"waiter_id" 	       => isset($interFaceHeader[0]['waiter_id']) ? $interFaceHeader[0]['waiter_id'] : NULL,
						"sub_table" 	       => isset($interFaceHeader[0]['sub_table']) ? $interFaceHeader[0]['sub_table'] : NULL,
						"print_status" 	       => isset($interFaceHeader[0]['print_status']) ? $interFaceHeader[0]['print_status'] : 'N',
						
						'created_by'           => isset($interFaceHeader[0]['created_by']) ? $interFaceHeader[0]['created_by'] : NULL,
						'created_date'         => isset($interFaceHeader[0]['created_date']) ? $interFaceHeader[0]['created_date'] : NULL,
						'last_updated_by'      => isset($interFaceHeader[0]['last_updated_by']) ? $interFaceHeader[0]['last_updated_by'] : NULL,
						'last_updated_date'    => isset($interFaceHeader[0]['last_updated_date']) ? $interFaceHeader[0]['last_updated_date'] : NULL,
					);

					$this->db->insert('ord_order_headers',$headerData);
					$header_id = $this->db->insert_id();

					if($header_id)
					{
						#Update Next Val DOC Number tbl start
						$nextValue = $startingNumber + 1;
						$doc_num_id = isset($getDocumentData[0]['doc_num_id']) ? $getDocumentData[0]['doc_num_id']:"";
						
						$UpdateData['next_number'] = $nextValue;
						$this->db->where('doc_num_id', $doc_num_id);
						$this->db->where('branch_id', $branch_id);
						$resultUpdateData = $this->db->update('doc_document_numbering', $UpdateData);
						#Update Next Val DOC Number tbl end

						#Update Qry
						$interFaceHeaderStatus = array(
							"int_header_status" => "Processed"
						);

						$this->db->where('interface_header_id', $interface_header_id);
						$updateResult = $this->db->update('ord_order_interface_headers', $interFaceHeaderStatus);
						#Update Qry

						
						$InterFaceLineQry = "select * from ord_order_interface_lines 
							where 1=1 
							and reference_header_id='".$interface_header_id."' ";
						$interFaceLines = $this->db->query($InterFaceLineQry)->result_array();

						foreach($interFaceLines as $lineData)
						{
							$lineData = array(
								'header_id'              => $header_id,
								'reference_header_id'    => $interface_header_id,
								'reference_line_id'      => isset($lineData["interface_line_id"]) ? $lineData["interface_line_id"] : NULL,
								'product_id'	         => isset($lineData["product_id"]) ? $lineData["product_id"] : NULL,
								'price'	                 => isset($lineData["price"]) ? $lineData["price"] : NULL,
								'quantity'	             => isset($lineData["quantity"]) ? $lineData["quantity"] : NULL,
								'offer_percentage'	     => isset($lineData["offer_percentage"]) ? $lineData["offer_percentage"] : NULL,
								'offer_amount'	         => isset($lineData["offer_amount"]) ? $lineData["offer_amount"] : NULL,
								'tax_percentage'	     => isset($lineData["tax_percentage"]) ? $lineData["tax_percentage"] : NULL,
								'line_status'	 	     => isset($lineData["line_status"]) ? $lineData["line_status"] : NULL,
								'cancel_status'	 	     => isset($lineData["cancel_status"]) ? $lineData["cancel_status"] : NULL,
								'cancelled_by'	 	     => isset($lineData["cancelled_by"]) ? $lineData["cancelled_by"] : NULL,
								'cooking_instructions'	 => isset($lineData["cooking_instructions"]) ? $lineData["cooking_instructions"] : NULL,
								'cancel_remarks'		 => isset($lineData["cancel_remarks"]) ? $lineData["cancel_remarks"] : NULL,
								
								'created_by'             => isset($lineData["created_by"]) ? $lineData["created_by"] : NULL,
								'created_date'           => isset($lineData["created_date"]) ? $lineData["created_date"] : NULL,
								'last_updated_by'        => isset($lineData["last_updated_by"]) ? $lineData["last_updated_by"] : NULL,
								'last_updated_date'      => isset($lineData["last_updated_date"]) ? $lineData["last_updated_date"] : NULL,
							);

							$this->db->insert('ord_order_lines', $lineData);
							$line_id = $this->db->insert_id();
						}
					}

					$this->generatePDF($header_id);

					if( $waiter_id != NULL && $table_id != NULL)
					{
						$this->db->where('table_id', $table_id);
						$this->db->where('waiter_id', $waiter_id);
						$this->db->delete('ord_cart_items');
					}else{
						$this->db->where('customer_id', $new_customer_id);
						$this->db->delete('ord_cart_items');
					}

					unset($_SESSION["SELECTED_TABLE_ID"]);
					unset($_SESSION["SEARCH_CUSTOMER_ID"]);

					$response["pos_items"] = array(	
						"header_id"          => $header_id,
						"pos_dine_in_type"   => $_POST["pos_dine_in_type"],
						"status"             => 1,
						"message"            => "Order created successfully!"
					);
					
					echo json_encode($response);
					exit;
				}
			}
		}
		exit;
	}

	function generatePDF($id="")
    {
		$page_data['id'] = $id;
		
		$page_data['data']  = $this->orders_model->getOrderDetails($id);
		$page_data['LineData'] = $this->orders_model->getOrderItemsPrint($id);
		
		ob_start();

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

	function selectTables($branch_id ="",$table_header_id="",$table_line_id ="",$user_id="")
	{
		$_SESSION["SELECTED_TABLE_ID"] = $table_line_id;

		#Check Exist
		/* $checkExistQry = "select tbl_selected_id from din_selected_tables
			where 
			table_header_id ='".$table_header_id."'
			and	table_line_id ='".$table_line_id."'
			and	user_id ='".$user_id."'
			and	branch_id ='".$branch_id."'
		";

		$chkExist = $this->db->query($checkExistQry)->result_array();

		if(count($chkExist) == 0)
		{
			$postData = array(
				'branch_id'  		=> $branch_id,
				'table_header_id'   => $table_header_id,
				'table_line_id'     => $table_line_id,
				'user_id'     		=> $user_id,
				'active_flag'       => $this->active_flag,
				'created_by'        => $this->web_user_id,
				'created_date'      => $this->date_time,
				'last_updated_by'   => $this->web_user_id,
				'last_updated_date' => $this->date_time,
			);
		
			$this->db->insert('din_selected_tables', $postData);
			$id = $this->db->insert_id();
		}
		else
		{
			$postData = array(
				'branch_id'  		=> $branch_id,
				'table_header_id'   => $table_header_id,
				'table_line_id'     => $table_line_id,
				'user_id'     		=> $user_id,
				#'active_flag'       => $this->active_flag,
				#'created_by'        => $this->web_user_id,
				#'created_date'      => $this->date_time,
				'last_updated_by'   => $this->web_user_id,
				'last_updated_date' => $this->date_time,
			);
		
			$this->db->where('branch_id', $branch_id);
			$this->db->where('table_header_id', $table_header_id);
			$this->db->where('table_line_id', $table_line_id);
			$this->db->where('user_id', $user_id);
			$result = $this->db->update('din_selected_tables', $postData);
		} */

		#$this->session->set_flashdata('flash_message' ,'Table selected successfully!');
	    redirect(base_url() . 'items.html', 'refresh');
	}

	function vieworders($table_id="")
    {
		if (empty($this->web_user_id))
        {
			redirect(base_url() . 'user-login.html', 'refresh');
		}

		$this->table_id = $table_id;

		#Table Shift start here
		if(isset($_POST["shift_tbl_btn"]))
		{
			$branch_id = $_POST["branch_id"];
			$interface_header_id = $_POST["interface_header_id"];
			$from_table_id = $_POST["from_table_id"];	
			$to_table_id = $_POST["to_table_id"];

			$orderQry = "select interface_header_id from ord_order_interface_headers 
				where 1=1
				and branch_id='".$branch_id."' 
				and table_id='".$to_table_id."' 
				and order_status='Created' 
			";
			$getPOSOrderItems = $this->db->query($orderQry)->result_array();

			if(count($getPOSOrderItems) > 0)
			{
				$this->session->set_flashdata('error_message' ,"Can't shift the running table!");
				redirect($_SERVER["HTTP_REFERER"], 'refresh');
			}
			else
			{
				$postData = array(
					"table_id"           => $to_table_id,
					"last_updated_by"    => $this->web_user_id,
					"last_updated_date"  => $this->date_time,
				);
				
				$this->db->where('table_id', $from_table_id);
				$this->db->where('branch_id', $branch_id);
				$updateResult = $this->db->update('ord_order_interface_headers',$postData);
	
				$this->session->set_flashdata('flash_message' ,'Table shifted successfully');
				redirect(base_url() . 'vieworders.html/'.$to_table_id, 'refresh');
			}
			
			
		}
		#Table Shift end here

		#Table Merge start here
		if(isset($_POST["merge_tbl_btn"]))
		{
			$branch_id = $_POST["branch_id"];
			$interface_header_id = $_POST["interface_header_id"];  #From
			$from_table_id = $_POST["from_table_id"];	
			$to_table_id = $_POST["to_table_id"];

			$orderQry = "select interface_header_id from ord_order_interface_headers 
				where 1=1
				and branch_id='".$branch_id."' 
				and table_id='".$to_table_id."' 
				and order_status='Created' 
			";
			$getPOSOrderItems = $this->db->query($orderQry)->result_array();

			if(count($getPOSOrderItems) > 0)
			{
				$to_interface_header_id = isset($getPOSOrderItems[0]["interface_header_id"]) ? $getPOSOrderItems[0]["interface_header_id"] : NULL;
				
				$postData = array(
					"reference_header_id"  => $to_interface_header_id,
					"last_updated_by"      => $this->web_user_id,
					"last_updated_date"    => $this->date_time,
				);
				
				$this->db->where('reference_header_id', $interface_header_id);
				$updateResult = $this->db->update('ord_order_interface_lines',$postData);


				$this->db->where('interface_header_id', $interface_header_id);
				$this->db->delete('ord_order_interface_headers');
	
				$this->session->set_flashdata('flash_message' ,'Table merged successfully');
				redirect(base_url() . 'vieworders.html/'.$to_table_id, 'refresh');
			}
			else
			{
				
			}
		}
		#Table Merge end here

		#Dine In list start
		/* $dineInOrderQry = "select 
		header_tbl.*,
		line_tbl.*,
		line_tbl.product_id as item_id,
		(line_tbl.price * line_tbl.quantity) as line_total,
		item.item_name,
		item.item_description
		
		from ord_order_interface_lines as line_tbl 

		left join ord_order_interface_headers as header_tbl on
		header_tbl.interface_header_id = line_tbl.reference_header_id

		left join inv_sys_items as item on
		item.item_id = line_tbl.product_id

		where 1=1 
		and header_tbl.table_id='".$table_id."' 
		and header_tbl.order_status='Created' 
		";

		$page_data['dineInOrders'] = $dineInOrders = $this->db->query($dineInOrderQry)->result_array();
		#Dine In list end */

		$branch_id = $this->selected_branch;

		$page_data["getDineInItems"] = $this->web_fine_dine_model->getDineInSeqOrder($table_id,$branch_id);

		$page_data['page_name']  = 'web_dine_in/vieworders';
		$page_data['page_title'] = 'vieworders';
		$this->load->view($this->template, $page_data);
	}
	#Web Dine-in End here

	function deleteLineItems($cart_id="",$branch_id="")
    {
		$this->db->where('cart_id', $cart_id);
		$this->db->delete('ord_cart_items');

		$branchQry = "select organization_id from branch where branch_id='".$branch_id."' ";
		$getOrganization = $this->db->query($branchQry)->result_array();

		$organization_id = isset($getOrganization[0]["organization_id"]) ? $getOrganization[0]["organization_id"] : NULL;

		$page_data['dineInOrders'] = $cartItems = $this->web_dine_in_model->getCartItems($organization_id,$branch_id,$this->customer_id,$this->waiter_id,$this->selected_table_id);
		$totalCartItems = count($cartItems);

		echo $totalCartItems;exit;
	}

	function updateCartQty()
	{
		$item_id = $_POST["item_id"];
		$cart_id = $_POST["cart_id"];
		$quantity = $_POST["quantity"];

		$postData = array(
			"quantity"           => $quantity,
			"last_updated_by"    => $this->web_user_id,
			"last_updated_date"  => $this->date_time,
		);
		
		$this->db->where('cart_id', $cart_id);
		$this->db->where('item_id', $item_id);
		$updateResult = $this->db->update('ord_cart_items',$postData);
		exit;
	}

	function cancelOrderItems()
	{
		$elements = $opts = isset($_POST['checkbox']) ? array_filter($_POST['checkbox']) : NULL;

		$inter_header_id = isset($_POST['inter_header_id']) ? $_POST['inter_header_id'] : NULL;
	
		if( (count($elements) > 0) && ($elements != NULL) )
		{
			#Header Print header Update Status start
			$printUpdateData = array(
				"print_status" 	      => 'N',
				"last_updated_by"     => $this->web_user_id,
				"last_updated_date"   => $this->date_time,
			);
			
			$this->db->where('interface_header_id', $inter_header_id);
			$this->db->update('ord_order_interface_headers', $printUpdateData);
			#Header Print header Update Status End

			foreach($elements as $key => $value)
			{
				//$line_id = $value;
				$implodeValue = explode("_",$value);
				
				$line_id = $implodeValue[0];
				$cancel_remarks = isset($implodeValue[1]) ? $implodeValue[1] : NULL;

				if($cancel_remarks)
				{
					#Order Line start here - 21-04-2023
					$lineCancelData["cancel_status"] = 'Y';
					$lineCancelData["cancelled_by"] = $this->web_user_id;
					$lineCancelData["cancel_date"] = $this->date_time;
					$lineCancelData['line_status'] = "Cancelled";
					$lineCancelData["cancel_remarks"] = $cancel_remarks;
					
					$this->db->where('interface_line_id', $line_id);
					$this->db->where('reference_header_id', $inter_header_id);
					$this->db->update('ord_order_interface_lines', $lineCancelData);
					#Order Line end here - 21-04-2023
				}
			}
			
			$orderQry = "select interface_line_id from ord_order_interface_lines as line_tbl
			where 1 =1 
			and line_tbl.reference_header_id = '".$inter_header_id."'
			and line_tbl.line_status != 'Cancelled'
			";
			$checkOrdItems = $this->db->query($orderQry)->result_array();

			if(count($checkOrdItems) == 0)
			{
				$data["cancel_status"] = 'Y';
				$data["cancelled_by"] = $this->web_user_id;
				$data["cancel_date"] = $this->date_time;
				$data['order_status'] = "Cancelled";
				$succ_msg = 'Order Cancelled successfully!';
				
				$this->db->where('interface_header_id', $inter_header_id);
				$this->db->update('ord_order_interface_headers', $data);
			}
		}
		else
		{
			echo "";exit;
		}
	}

	/* function webcancelOrderItems()
	{
		$elements = $opts = isset($_POST['checkbox']) ? array_filter($_POST['checkbox']) : NULL;
		$header_id = $opts = isset($_POST['order_id']) ? $_POST['order_id'] : NULL;
		
		if( (count($elements) > 0) && ($elements != NULL) )
		{
			#Header Print header Update Status start
			$printUpdateData = array(
				"print_status" 	      => 'N',
				"last_updated_by"     => $this->user_id,
				"last_updated_date"   => $this->date_time,
			);
			
			$this->db->where('header_id', $header_id);
			$this->db->update('ord_order_interface_lines', $printUpdateData);
			#Header Print header Update Status start

			foreach($elements as $key => $value)
			{
				$implodeValue = explode("_",$value);

				$line_id = $implodeValue[0];
				$cancel_remarks = isset($implodeValue[1]) ? $implodeValue[1] : NULL;
				
				#Order Line start here - 21-04-2023
				$lineCancelData["cancel_status"] = 'Y';
				$lineCancelData["cancel_remarks"] = $cancel_remarks;
				$lineCancelData["cancelled_by"] = $this->user_id;
				$lineCancelData["cancel_date"] = $this->date_time;
				$lineCancelData['line_status'] = "Cancelled";
				
				$this->db->where('line_id', $line_id);
				$this->db->where('header_id', $header_id);
				$this->db->update('ord_order_interface_lines', $lineCancelData);
				#Order Line end here - 21-04-2023
			}
			

			$orderQry = "select line_id from ord_order_lines as line_tbl
			where 1 =1 
			and line_tbl.header_id = '".$header_id."'
			and line_tbl.line_status != 'Cancelled'
			";
			$checkOrdItems = $this->db->query($orderQry)->result_array();

			if(count($checkOrdItems) == 0)
			{
				$data["cancel_status"] = 'Y';
				$data["cancelled_by"] = $this->user_id;
				$data["cancel_date"] = $this->date_time;
				$data['order_status'] = "Cancelled";
				$succ_msg = 'Order Cancelled successfully!';
				
				$this->db->where('header_id', $header_id);
				$this->db->update('ord_order_headers', $data);
			}

		}
		else
		{
			echo "";exit;
		}
	} */

	function updateInterfaceQty()
	{
		$item_id = $_POST["item_id"];
		$interface_header_id = $_POST["interface_header_id"];
		$interface_line_id = $_POST["interface_line_id"];
		$quantity = $_POST["quantity"];
		$previous_quantity = $_POST["exist_quantity"];

		/* $postData = array(
			"quantity"           => $quantity,
			"kot_print_status"   => 'N',
			"last_updated_by"    => $this->web_user_id,
			"last_updated_date"  => $this->date_time,
		);
		
		$this->db->where('reference_header_id', $interface_header_id);
		$this->db->where('interface_line_id', $interface_line_id);
		$this->db->where('product_id', $item_id);
		$updateResult = $this->db->update('ord_order_interface_lines',$postData);
 		*/

		if( $quantity > $previous_quantity)
		{
			$lineData = array(
				'quantity'	            => $quantity,
				'previous_quantity'	    => $previous_quantity,
				'last_updated_by'       => $this->user_id,
				'last_updated_date'     => $this->date_time,
				'kot_print_status'      => 'N',
			);	

			$this->db->where('reference_header_id', $interface_header_id);
			$this->db->where('interface_line_id', $interface_line_id);
			$update_result = $this->db->update('ord_order_interface_lines', $lineData);
		}
		else if($quantity < $previous_quantity)
		{
			$lineData = array(
				'quantity'	         => $quantity,
				'previous_quantity'	 => $previous_quantity, 
				'last_updated_by'    => $this->user_id,
				'last_updated_date'  => $this->date_time,	
			);

			$this->db->where('reference_header_id', $interface_header_id);
			$this->db->where('interface_line_id', $interface_line_id);
			$update_result = $this->db->update('ord_order_interface_lines', $lineData);
		}
		exit;
	}

	function selectDineInTable()
	{
		$table_line_id = $table_id = isset($_POST["table_id"]) ? $_POST["table_id"] : NULL;
		$branch_id = isset($_POST["branch_id"]) ? $_POST["branch_id"] : NULL;
		$waiter_id = isset($_POST["waiter_id"]) ? $_POST["waiter_id"] : NULL;
		$customer_id = isset($_POST["web_user_id"]) ? $_POST["web_user_id"] : NULL;

		$branchQry = "select organization_id from branch where branch_id='".$branch_id."' ";
		$getOrganization = $this->db->query($branchQry)->result_array();

		$organization_id = isset($getOrganization[0]["organization_id"]) ? $getOrganization[0]["organization_id"] : NULL;

		
		$_SESSION["SELECTED_TABLE_ID"] = $table_line_id;	
		
		$page_data['dineInOrders'] = $cartItems = $this->web_dine_in_model->getCartItems($organization_id,$branch_id,$customer_id,$waiter_id,$table_id);
		$totalCartItems = count($cartItems);

		$page_data['waiter_login'] = $this->waiter_login;
		$page_data['selected_table_id'] = $table_line_id;

		$cartItemesPage = $this->load->view("themes/default/web_dine_in/cart_items.php", $page_data, true);

		$response = array(	
			"httpCode" 		  => 200,
			"status"          => (int) 1,
			"totalCartItems"  => $totalCartItems,
			"cartItemesPage"  => $cartItemesPage,
			"status"          => 1
		);
		echo json_encode($response);
		exit;
	}

}
?>
