<?php
defined('BASEPATH') OR exit('No direct script access allowed');
include_once('vendor/autoload.php');
class Web_fine_dine extends CI_Controller 
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
		unset($_SESSION["WebUserID"]);
		unset($_SESSION["CUSTOMER_DATA"]);
		unset($_SESSION["WAITER_LOGIN"]);
		redirect(base_url().'login.html', 'refresh');
		//redirect(base_url().'user-login.html', 'refresh');
	}

	function fineDine($waiter_user_id="")
    {
		#unset($_SESSION["SELECT_BRANCH"]);exit;
		#unset($_SESSION["SELECTED_TABLE_ID"]);

		/* if($waiter_user_id)
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
		} */
		
		$page_data['page_name']  = 'web_fine_dine/fineDine';
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
	}

	/* function posItemSearch()
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
					$item_description = ucfirst($row["item_name"]);
					
					#$output .= '<li onclick="return getappointmentuserId(\'' .$patinetID. '\',\'' .$phone_number. '\',\'' .$email. '\',\'' .$customer_name. '\',\'' .$random_user_id. '\');">'.$row["phone_number"].'</li>';  
					$output .= '<li data-toggle="modal" onclick="showCartModel('.$row["item_id"].')" >'.$item_description.'</li>';  
					#$output .= '<li data-toggle="modal" data-target="#exampleModal'.$row["item_id"].'" >'.$item_description.'</li>';  
					#$output .= '<a href="javascript:void();" data-toggle="modal" data-target="#exampleModal'.$row["item_id"].'">'.$item_description.'</a>'; 
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
	} */
	
	function insertPosOrderItems($type="",$button_type="",$direct_save_print="")
	{
		//$existInterfaceId = $_POST["existing_order_id"];
		
		/* if(isset($_POST["interface_header_id"]) && !empty($_POST["interface_header_id"])){
			$interface_header_id = isset($_POST["interface_header_id"]) ? $_POST["interface_header_id"] : NULL;
		}
		else  */

		/* if(isset($_POST["interface_header_id"]) && !empty($_POST["interface_header_id"])){
			$interface_header_id = isset($_POST["interface_header_id"]) ? $_POST["interface_header_id"] : NULL;
		}else{
			$interface_header_id = NULL;
		} */

		if(isset($_POST["existing_order_id"]) && !empty($_POST["existing_order_id"])){
			$existInterfaceId = $interface_header_id = isset($_POST["existing_order_id"]) ? $_POST["existing_order_id"] : NULL;
		}else{
			$existInterfaceId =  $interface_header_id = NULL;
		}
		
		if($this->user_id == 1)
		{
			$getBranch = $this->db->query("select branch_id from branch where default_branch='Y' and active_flag='Y' ")->result_array();
			$branch_id = isset($getBranch[0]["branch_id"]) ? $getBranch[0]["branch_id"] : NULL;
		}
		else
		{
			$branch_id = !empty($this->branch_id) ? $this->branch_id : 1;
		}
		
		if( isset($_POST['pos_dine_in_type']) && $_POST['pos_dine_in_type'] == "POS") #POS
		{
			$documentNumberCondition = "and dm.doc_document_type = 'pos-orders'";
		}
		else if( isset($_POST['pos_dine_in_type']) && $_POST['pos_dine_in_type'] == "HOME_DELIVERY") #HOME_DELIVERY
		{
			$documentNumberCondition = "and dm.doc_document_type = 'home-delivery-orders'";
		}
		
		$documentQry = "select doc_num_id,prefix_name,suffix_name,next_number 
		from doc_document_numbering as dm
		left join sm_list_type_values ltv on 
		ltv.list_type_value_id = dm.doc_type
		where 
		1=1
		$documentNumberCondition
		and dm.branch_id = '".$branch_id."'
		and ltv.list_code = 'CUS_ORD' 
		and dm.active_flag = 'Y'
		and coalesce(dm.from_date,CURDATE()) <= CURDATE() 
		and coalesce(dm.to_date,CURDATE()) >= CURDATE()
		";
		$getDocumentData = $this->db->query($documentQry)->result_array();
		
		$prefixName = isset($getDocumentData[0]['prefix_name']) ? $getDocumentData[0]['prefix_name'] : NULL;
		$startingNumber = isset($getDocumentData[0]['next_number']) ? $getDocumentData[0]['next_number'] : NULL;
		$suffixName = isset($getDocumentData[0]['suffix_name']) ? $getDocumentData[0]['suffix_name'] : NULL;
		$documentNumber = $prefixName.''.$startingNumber.''.$suffixName;

		#if( (($type == 'order_interface_tbl') && (!empty($button_type) && $button_type != 'SAVE_PRINT')) || ($direct_save_print == "direct_save_print") )
		if( ( ($type == 'order_interface_tbl') && (!empty($button_type)) ) || ($direct_save_print == "direct_save_print") )
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

					$payment_transaction_status = NULL;
					
					$payment_method = isset($_POST['payment_method']) ? $_POST['payment_method'] : NULL;
					//$sub_table = !empty($_POST['sub_table']) ? $_POST['sub_table'] : NULL;//===
					
						
					if( isset($_POST['pos_dine_in_type']) && $_POST['pos_dine_in_type'] == "POS") #POS
					{
						$waiter_id = NULL;
						$table_id  = NULL;

						$order_source = 'POS';
						$order_type = 1; #Take Away

						if($button_type == "SAVE_PRINT")
						{
							$order_status = $interface_status = "Printed";
						}
						else
						{
							$order_status = $interface_status = "Created";
							// $order_status = $interface_status = NULL;
						}
					}
					else if( isset($_POST['pos_dine_in_type']) && $_POST['pos_dine_in_type'] == "HOME_DELIVERY") #HOME_DELIVERY
					{
						$waiter_id = NULL;
						$table_id  = NULL;

						$order_source = 'HOME_DELIVERY';
						$order_type = 2; #Take Away
						
						if($button_type == "SAVE_PRINT")
						{
							$order_status = $interface_status = "Printed";
						}
						else
						{
							$order_status = $interface_status = "Created";
							//$order_status = $interface_status = NULL;
						}
					}
					else
					{
						
						$order_source = NULL;
						$waiter_id = NULL;
						$table_id  = NULL;
						$order_type  = NULL;
						$order_status = $interface_status = NULL;
					}
					
					if($interface_header_id !=NULL) 
					{
						$interfaceTblQry = "select interface_header_id,order_number from ord_order_interface_headers 
						where 1=1
						and interface_header_id ='".$existInterfaceId."'
						";
						$checkInterfaceTbl = $this->db->query($interfaceTblQry)->result_array();
						
					}
					else
					{
						$interfaceTblQry = "select interface_header_id,order_number from ord_order_interface_headers 
						where 1=1
						and interface_header_id ='".$existInterfaceId."'";
						$checkInterfaceTbl = $this->db->query($interfaceTblQry)->result_array();
						
					}
					
					if( count($checkInterfaceTbl) == 0 ) #Insert
					{
						if(
							isset($_POST["pos_dine_in_type"]) 
							&& (
								$_POST["pos_dine_in_type"] == "POS" 
								|| $_POST["pos_dine_in_type"] == "HOME_DELIVERY"
							)
						)
						{
							$dine_in_interface_header_id = isset($_POST["dine_in_interface_header_id"]) ? $_POST["dine_in_interface_header_id"] : NULL;

							$checkInterFaceHeaderQry = "select interface_header_id,order_number from ord_order_interface_headers 
							where 1=1
							and interface_header_id ='".$dine_in_interface_header_id."'";
							$checkInterfaceTbl1 = $this->db->query($checkInterFaceHeaderQry)->result_array();
						}
						else
						{
							$checkInterfaceTbl1 = array();
						}

						if( isset($_POST["payment_due"]) && !empty($_POST["payment_due"]) ){
							$payment_due = "Unpaid";
						}else{
							$payment_due = "Paid";
						}
						$discount_remarks = isset($_POST["discount_remarks"]) ? $_POST["discount_remarks"] : NULL;
						
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
							'interface_status'              => $interface_status,
							'coupon_code'                   => NULL,
							'coupon_amount'                 => NULL,
							'wallet_amount'                 => NULL,
							'payment_due'                   => $payment_due,
							'discount_remarks'              => $discount_remarks,

							'created_by'                    => $this->user_id,
							'created_date'                  => $this->date_time,
							'last_updated_by'               => $this->user_id,
							'last_updated_date'             => $this->date_time,
						);
						
						if(count($checkInterfaceTbl1) > 0)
						{
							$updateData = array(
								'created_by'                    => $this->user_id,
								'created_date'                  => $this->date_time,
								'last_updated_by'               => $this->user_id,
								'last_updated_date'             => $this->date_time,
								'order_status'                  => $order_status,
								'payment_due'                   => $payment_due,
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
									'product_id'	     => $product_id,
									'price'	             => $price,
									'quantity'	         => $quantity,
									'cooking_instructions'=> $cooking_instructions,

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
						$order_number = $checkInterfaceTbl[0]["order_number"];
						$interface_header_id = $checkInterfaceTbl[0]["interface_header_id"];

						if( isset($_POST["payment_due"]) && !empty($_POST["payment_due"]) ){
							$payment_due = "Unpaid";
						}else{
							$payment_due = "Paid";
						}

						$discount_remarks = isset($_POST["discount_remarks"]) ? $_POST["discount_remarks"] : NULL;
						
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
							'payment_due'                   => $payment_due,
							'discount_remarks'              => $discount_remarks,

							'coupon_code'                   => NULL,
							'coupon_amount'                 => NULL,
							'wallet_amount'                 => NULL,
							'last_updated_by'               => $this->user_id,
							'last_updated_date'             => $this->date_time,
						);


						$this->db->where('interface_header_id', $interface_header_id);
						$result = $this->db->update('ord_order_interface_headers', $headerData);

						if($result)
						{
							$count = count(array_filter($_POST['text_item_id']));
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
											'cooking_instructions'=> $cooking_instructions,
											'offer_amount'	     => $discount_amount,
											'tax_percentage'	 => $tax_percentage,
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
											'cooking_instructions'=> $cooking_instructions,
											'offer_percentage'	 => $discount_percentage,
											'offer_amount'	     => $discount_amount,
											'tax_percentage'	 => $tax_percentage,
											
											'last_updated_by'    => $this->user_id,
											'last_updated_date'  => $this->date_time,	
										);

										$this->db->where('reference_header_id', $interface_header_id);
										$this->db->where('interface_line_id', $interface_line_id);
										$update_result = $this->db->update('ord_order_interface_lines', $lineData);
									}
									else
									{
										$lineData = array(
											'offer_percentage'	 => $discount_percentage,
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
										'reference_header_id'=> $interface_header_id,
										'product_id'	     => $product_id,
										'price'	             => $price,
										'quantity'	         => $quantity,
										'attribute_1'	     => "test",

										'cooking_instructions'=> $cooking_instructions,

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
							}

							$response["pos_items"] = array(	
								"documentNumber"       => $documentNumber,
								"interface_header_id"  => $interface_header_id,
								"status"               => 1,
								"message"              => "Order Created Successfully!"
							);
						}
					}
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
		else if($type == 'pos_order_base_tbl') 
		{
			if( isset($_POST["pos_interface_header_id"] ) && !empty($_POST["pos_interface_header_id"]) )
			{
				$interface_header_id = $_POST["pos_interface_header_id"];
				
				/* if(isset($_POST["pos_dine_in_type"]) && $_POST["pos_dine_in_type"] == "DINE_IN") discount
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
					$lineResult = $this->db->update('ord_order_interface_lines', $updateLineInterFaceStatus);
				#}

				$InterFaceHeaderQry = "select * from ord_order_interface_headers 
					where 
						interface_header_id='".$interface_header_id."' ";
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

					if(isset($_POST['new_customer_id']) && $_POST['new_customer_id'] > 0)
					{
						$customer_id = isset($_POST['new_customer_id']) ? $_POST['new_customer_id'] : NULL;
					}
					/* if(isset($_POST['customer_id']) && $_POST['customer_id'] > 0)
					{
						$customer_id = isset($_POST['customer_id']) ? $_POST['customer_id'] : NULL;
					} */
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

					if( isset($_POST["payment_due"]) && !empty($_POST["payment_due"]) ){
						$payment_due = "Unpaid";
					}else{
						$payment_due = "Paid";
					}
					$discount_remarks = isset($_POST["discount_remarks"]) ? $_POST["discount_remarks"] : NULL;
						
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
						"payment_due" 	       => isset($payment_due) ? $payment_due : "Paid",
						"discount_remarks" 	   => isset($discount_remarks) ? $discount_remarks : NULL,
						
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
								'header_id'          => $header_id,
								'reference_header_id'=> $interface_header_id,
								'reference_line_id'  => isset($lineData["interface_line_id"]) ? $lineData["interface_line_id"] : NULL,
								'product_id'	     => isset($lineData["product_id"]) ? $lineData["product_id"] : NULL,
								'price'	             => isset($lineData["price"]) ? $lineData["price"] : NULL,
								'quantity'	         => isset($lineData["quantity"]) ? $lineData["quantity"] : NULL,
								'offer_percentage'	 => isset($lineData["offer_percentage"]) ? $lineData["offer_percentage"] : NULL,
								'offer_amount'	     => isset($lineData["offer_amount"]) ? $lineData["offer_amount"] : NULL,
								'tax_percentage'	 => isset($lineData["tax_percentage"]) ? $lineData["tax_percentage"] : NULL,
								'line_status'	 	 => isset($lineData["line_status"]) ? $lineData["line_status"] : NULL,

								'cooking_instructions'=> isset($lineData["cooking_instructions"]) ? $lineData["cooking_instructions"] : NULL,
								
								'created_by'         => isset($lineData["created_by"]) ? $lineData["created_by"] : NULL,
								'created_date'       => isset($lineData["created_date"]) ? $lineData["created_date"] : NULL,
								'last_updated_by'    => isset($lineData["last_updated_by"]) ? $lineData["last_updated_by"] : NULL,
								'last_updated_date'  => isset($lineData["last_updated_date"]) ? $lineData["last_updated_date"] : NULL,
							);

							$this->db->insert('ord_order_lines', $lineData);
							$line_id = $this->db->insert_id();
						}
					}

					$this->generatePDF($header_id);

					$response["postakehome_items"] = array(	
						"header_id"          => $header_id,
						"pos_dine_in_type"   => $_POST["pos_dine_in_type"],
						"status"             => 1,
						"message"            => "Order Completed successfully!"
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

		if(isset($_POST["shift_tbl_btn"]))
		{
			$from_table_id = $_POST["from_table_id"];
			$to_table_id = $_POST["to_table_id"];
			
			$postData = array(
				"table_id"           => $to_table_id,
				"last_updated_by"    => $this->web_user_id,
				"last_updated_date"  => $this->date_time,
			);
			
			$this->db->where('table_id', $from_table_id);
			$this->db->where('table_id !=', "Success");
			$updateResult = $this->db->update('ord_order_interface_headers',$postData);

			$this->session->set_flashdata('flash_message' ,'Table shifted successfully');
			redirect(base_url() . 'vieworders.html/'.$to_table_id, 'refresh');
		}

		#Dine In list start
		$dineInOrderQry = "select 
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
		#Dine In list end
		
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
	function posItemSearch()
    {
		if(isset($_POST["query"]))  
		{  
			if($this->branch_id){
				$branch_id = $this->branch_id;
				//$branch_id = 1;
			}else{
				$branch_id = 'NULL';
			}
			
			$output = '';  
			
			$keywords = "concat('%','".serchFilter($_POST["query"])."','%')";

			$itemQuery = "
				select 
				item_id,
				item_name,
				item_description,
				item_price,
				short_code,
				uom_id,
				uom_code,
				food_time,
				breakfast_flag,
				lunch_flag,
				dinner_flag
				from 
				(
					select 
						branch.branch_id,
						categories.category_id,
						items.item_id,
						items.item_name,
						items.item_description,
						items.short_code,
						uom.uom_id,
						uom.uom_code,
						branch_items.item_price,
						coalesce(branch_items.breakfast_flag,'N') as breakfast_flag, 
						coalesce(branch_items.lunch_flag,'N') as lunch_flag, 
						coalesce(branch_items.dinner_flag,'N') as dinner_flag,
						offers.offer_percentage,
						(
							case
								when '".$this->currentTime."' between branch.break_fast_from AND branch.break_fast_to then 'BreakFast'
								when '".$this->currentTime."' between branch.lunch_from AND branch.lunch_to then 'Lunch'
								when '".$this->currentTime."' between branch.dinner_from AND branch.dinner_to then 'Dinner'
								else ''
							end 
						) food_time
	
					from inv_item_branch_assign as branch_items
	
					left join inv_sys_items as items on items.item_id = branch_items.item_id
					left join uom on uom.uom_id = items.uom
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
					and (
						items.item_name like coalesce($keywords,items.item_name) or 
						items.item_description like coalesce($keywords,items.item_description) or 
						items.short_code like coalesce($keywords,items.short_code)
					)
					
					and (
						branch_items.branch_id = '".$branch_id."' 
						or (branch_items.branch_id = (select a.branch_id from branch a where a.default_branch = 'Y') and ".$this->user_id." = 1)
					)

					and branch_items.active_flag = 'Y' 
					and items.active_flag = 'Y' 
					and categories.active_flag = 'Y'
					
					and branch.active_flag = 'Y' 
					
					group by branch_id,category_id,item_id 
					order by items.item_description asc
				) t
				
				HAVING ( 
					breakfast_flag = if (food_time = 'BreakFast', 'Y','') or
					lunch_flag = if (food_time = 'Lunch', 'Y','') or
					dinner_flag = if (food_time = 'Dinner', 'Y','') 
				)
			";

			#and branch_items.branch_id = coalesce($branch_id,branch_items.branch_id)
			#and branch.default_branch = 'Y'
			#having coalesce(t.food_time,'') != ''
			$result = $this->db->query($itemQuery)->result_array();
			
			$output = '<ul class="list-unstyled-finedine">';  
			
			if( count($result) > 0 )  
			{  
				foreach($result as $row)  
				{	
					$item_id = $row["item_id"];
					$item_description = ucfirst($row["item_name"]);
					$item_price = CURRENCY_SYMBOL." ".number_format($row["item_price"],DECIMAL_VALUE,'.','');
					$short_code = $row["short_code"];

					if($item_description && $short_code){
						$searchDesc = $item_description." - ".$short_code;
					}else{
						$searchDesc = $item_description;
					}

					#$output .= '<li onclick="return getappointmentuserId(\'' .$patinetID. '\',\'' .$phone_number. '\',\'' .$email. '\',\'' .$customer_name. '\',\'' .$random_user_id. '\');">'.$row["phone_number"].'</li>';  
					$output .= '<li onclick="return selectSearchPosItems(\'' .$item_id. '\');">'.$searchDesc.'  <span class="proprice">'.$item_price.'</span></li>';  
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

	#Online Orders
	function ajaxLoadOnlineOrders()
	{
		$result = $this->web_fine_dine_model->getOrders();
		$page_data["getOnlineOrders"] = $result["listing"];
		
		$htmlData["bookedCount"] = $result["bookedCount"][0]["bookedCount"];
		$htmlData["confirmedCount"] = $result["confirmedCount"][0]["confirmedCount"];
		$htmlData["preparingCount"] = $result["preparingCount"][0]["preparingCount"];
		$htmlData["shippedCount"] = $result["shippedCount"][0]["shippedCount"];
		$htmlData["deliveredCount"] = $result["deliveredCount"][0]["deliveredCount"];
		
		$htmlData["onlineOrdersList"] = $this->load->view('themes/default/web_fine_dine/ajaxPages/onlineOrders',$page_data,true);
		echo json_encode($htmlData);
		exit;
	}

	#Filter Online Orders : Card
	function ajaxFilterCard()
	{
		$order_status = isset($_POST["order_status"]) ? $_POST["order_status"] : NULL;

		if($order_status != NULL)
		{
			$result = $this->web_fine_dine_model->getFilterCardOrders($order_status);
			$page_data["getOnlineOrders"] = $result["listing"];
			
			$htmlData["bookedCount"] = $result["bookedCount"][0]["bookedCount"];
			$htmlData["confirmedCount"] = $result["confirmedCount"][0]["confirmedCount"];
			$htmlData["preparingCount"] = $result["preparingCount"][0]["preparingCount"];
			$htmlData["shippedCount"] = $result["shippedCount"][0]["shippedCount"];
			$htmlData["deliveredCount"] = $result["deliveredCount"][0]["deliveredCount"];
			
			$htmlData["onlineOrdersList"] = $this->load->view('themes/default/web_fine_dine/ajaxPages/onlineOrders',$page_data,true);
			echo json_encode($htmlData);
			exit;
		}
	}

	function ajaxLoadPOSTakeawayOrders($order_type="")
	{
		$page_data["posTakeawayOrders"] = $result = $this->web_fine_dine_model->getPOStakeawayOrders($order_type);
		
		$htmlData["POSTakeawayOrdersList"] = $this->load->view('themes/default/web_fine_dine/ajaxPages/ajaxfineDinePOS',$page_data,true);
		
		echo json_encode($htmlData);
		exit;
	}

	#Dine In Orders
	function ajaxLoadDineInOrder()
	{
		$page_data["getDineInList"] = $this->web_dine_in_model->getOrderTables($this->branch_id,$this->user_id);

		$htmlData["dineInList"] = $this->load->view('themes/default/web_fine_dine/ajaxPages/ajaxDineInTables',$page_data,true);
		echo json_encode($htmlData);
		exit;
	}

	#Dine In Orders
	function ajaxLoadDineInOrderItems()
	{
		$branch_id = $_POST["branch_id"];
		$table_id = $_POST["table_id"];

		#$page_data["getDineInItems"] = $this->web_fine_dine_model->getDineInOrderItems($table_id,$branch_id);
		$page_data["getDineInItems"] = $getDineInItems = $this->web_fine_dine_model->getDineInSeqOrder($table_id,$branch_id);

		$interface_header_id = isset($getDineInItems[0]["interface_header_id"]) ? $getDineInItems[0]["interface_header_id"] : NULL;
		
		$page_data["getPOSOrderItems"] = $this->web_fine_dine_model->getPOSOrderItems($interface_header_id);
		
		$htmlData["dineInItems"] = $this->load->view('themes/default/web_fine_dine/ajaxPages/ajaxDineInItems',$page_data,true);
		$htmlData["dineInPayments"] = $this->load->view('themes/default/web_fine_dine/ajaxPages/ajaxDineInPayments',$page_data,true);
		echo json_encode($htmlData);
		exit;
	}


	function generateOpenOrdersPDF($button_type="",$id="")
    {
		$page_data['id'] = $header_id = $id;

		#Unlink PDF Start	
		if(file_exists("uploads/auto_generate_pdf/".$header_id.".pdf"))
		{
			unlink("uploads/auto_generate_pdf/".$header_id.".pdf");
		}
		/* if(file_exists("uploads/auto_generate_pdf/kot/".$header_id.".pdf"))
		{
			unlink("uploads/auto_generate_pdf/kot/".$header_id.".pdf");
		} */
		#Unlink PDF end
		
		$page_data['data'] = $this->orders_model->getOpenPrintOrderDetails($id);#Header Qry

		/* 
		if($button_type == "SAVE" || $button_type == "SAVE_PRINT") #KOT
		{
			$LineData = $page_data['LineData'] = $this->orders_model->getKOTOrderItems($id);
		}

		if($button_type == "SAVE_PRINT") #Bill Print
		{ */
			$LineData = $page_data['LineData'] = $this->orders_model->getOpenPrintOrderItems($id);
		//}
		
		if( count($LineData) > 0 )
		{
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

			/* #KOT Bill start start
			$kot_mpdf = new \Mpdf\Mpdf();
			$kot_html = ob_get_clean();
			$kot_html = utf8_encode($kot_html);
			$kot_html = $this->load->view('backend/orders/kotPrint',$page_data,true);
			$kot_mpdf->WriteHTML($kot_html);
			$kot_mpdf->Output('uploads/auto_generate_pdf/kot/'.$id.'.pdf', 'F'); */
			#KOT Bill start end
		}
	}

	function generateKOTPDF($button_type="",$id="")
    {
		$page_data['id'] = $header_id = $id;

		#Unlink PDF Start	
		/* if(file_exists("uploads/auto_generate_pdf/".$header_id.".pdf"))
		{
			unlink("uploads/auto_generate_pdf/".$header_id.".pdf");
		} */
		if(file_exists("uploads/auto_generate_pdf/kot/".$header_id.".pdf"))
		{
			unlink("uploads/auto_generate_pdf/kot/".$header_id.".pdf");
		}
		#Unlink PDF end
		
		$page_data['data']  = $this->orders_model->getOpenPrintOrderDetails($id);#Header Qry
		$LineData = $page_data['LineData'] = $this->orders_model->getKOTOrderItems($id);

		/* 
		if($button_type == "SAVE" || $button_type == "SAVE_PRINT") #KOT
		{
			$LineData = $page_data['LineData'] = $this->orders_model->getKOTOrderItems($id);
		}

		if($button_type == "SAVE_PRINT") #Bill Print
		{ */
			#$LineData = $page_data['LineData'] = $this->orders_model->getOpenPrintOrderItems($id);
		//}
		
		if( count($LineData) > 0 )
		{
			ob_start();

			#Print Receipt HTML Start
			/* $html = ob_get_clean();
			$html = utf8_encode($html);
			$html = $this->load->view('backend/orders/printReceipt',$page_data,true);
			$mpdf = new \Mpdf\Mpdf();
			$mpdf->WriteHTML($html);
			$mpdf->Output('uploads/auto_generate_pdf/'.$id.'.pdf', 'F'); */
			#Print Receipt HTML End

			#KOT Bill start start
			$kot_mpdf = new \Mpdf\Mpdf([		
				#'setAutoTopMargin' => 'stretch',
				#'setAutoBottomMargin' => 'stretch',
				'curlAllowUnsafeSslRequests' => true,
			]);
			$kot_html = ob_get_clean();
			$kot_html = utf8_encode($kot_html);
			$kot_html = $this->load->view('backend/orders/kotPrint',$page_data,true);
			$kot_mpdf->WriteHTML($kot_html);
			$kot_mpdf->Output('uploads/auto_generate_pdf/kot/'.$id.'.pdf', 'F');
			#KOT Bill start end
		}
	}

	function generateSaveKOTPDF($button_type="",$id="")
    {
		$page_data['id'] = $header_id = $id;

		#Unlink PDF Start	
		/* if(file_exists("uploads/auto_generate_pdf/".$header_id.".pdf"))
		{
			unlink("uploads/auto_generate_pdf/".$header_id.".pdf");
		} */
		if(file_exists("uploads/auto_generate_pdf/kot/".$header_id.".pdf"))
		{
			unlink("uploads/auto_generate_pdf/kot/".$header_id.".pdf");
		}
		#Unlink PDF end
		
		$page_data['data']  = $this->orders_model->getOpenPrintOrderDetails($id);#Header Qry
		$LineData = $page_data['LineData'] = $this->orders_model->getDineInKOTOrderItems($id);

		if( count($LineData) > 0 )
		{
			ob_start();

			#KOT Bill start start
			$kot_mpdf = new \Mpdf\Mpdf([		
				#'setAutoTopMargin' => 'stretch',
				#'setAutoBottomMargin' => 'stretch',
				'curlAllowUnsafeSslRequests' => true,
			]);
			$kot_html = ob_get_clean();
			$kot_html = utf8_encode($kot_html);
			$kot_html = $this->load->view('backend/orders/kotPrint',$page_data,true);
			$kot_mpdf->WriteHTML($kot_html);
			$kot_mpdf->Output('uploads/auto_generate_pdf/kot/'.$id.'.pdf', 'F');
			#KOT Bill start end
		}
	}

	public function getPOLineDatas($item_id='')
	{
		if($this->branch_id){
			$branch_id = $this->branch_id;
			//$branch_id = 1;
		}else{
			$branch_id = 'NULL';
		}
	
		$itemQuery = "
			select 
			item_id,
			item_name,
			item_description,
			item_price,
			uom_id,
			uom_code,
			food_time,
			breakfast_flag,
			lunch_flag,
			dinner_flag
			from 
			(
				select 
					branch.branch_id,
					categories.category_id,
					items.item_id,
					items.item_name,
					items.item_description,
					uom.uom_id,
					uom.uom_code,
					branch_items.item_price,
					coalesce(branch_items.breakfast_flag,'N') as breakfast_flag, 
					coalesce(branch_items.lunch_flag,'N') as lunch_flag, 
					coalesce(branch_items.dinner_flag,'N') as dinner_flag,
					offers.offer_percentage,
					(
						case
							when '".$this->currentTime."' between branch.break_fast_from AND branch.break_fast_to then 'BreakFast'
							when '".$this->currentTime."' between branch.lunch_from AND branch.lunch_to then 'Lunch'
							when '".$this->currentTime."' between branch.dinner_from AND branch.dinner_to then 'Dinner'
							else ''
						end 
					) food_time

					
					from inv_item_branch_assign as branch_items

				left join inv_sys_items as items on items.item_id = branch_items.item_id
				left join uom on uom.uom_id = items.uom
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
				and items.item_id ='".$item_id."'

				and (
					branch_items.branch_id = '".$branch_id."' 
					or (branch_items.branch_id = (select a.branch_id from branch a where a.default_branch = 'Y') and ".$this->user_id." = 1)
				)
				
				and branch_items.active_flag = 'Y' 
				and items.active_flag = 'Y' 
				and categories.active_flag = 'Y'
				
				and branch.active_flag = 'Y' 
				
				group by branch_id,category_id,item_id
				order by items.item_description asc
			) t

			HAVING ( 
				breakfast_flag = if (food_time = 'BreakFast', 'Y','') or
				lunch_flag = if (food_time = 'Lunch', 'Y','') or
				dinner_flag = if (food_time = 'Dinner', 'Y','') 
			)
			
		";

		#and branch_items.branch_id = coalesce($branch_id,branch_items.branch_id)

		//and branch.default_branch = 'Y' 
		#having coalesce(t.food_time,'') != ''

		$data['items'] = $this->db->query($itemQuery)->result_array();
	    echo json_encode($data);
		exit;
	}

	function insertDineOrderItems($type="",$button_type="",$direct_save_print="")
	{
		
		$selected_table_id = isset($_POST['table_id']) ? $_POST['table_id'] : NULL;

		$interFaceQry = "select interface_header_id from ord_order_interface_headers 
		where 1=1
		AND table_id='".$selected_table_id."'
		AND order_status='Created'
		";
		$getInterFaceDetails = $this->db->query($interFaceQry)->result_array();
		$interface_header_id = isset($getInterFaceDetails[0]["interface_header_id"]) ? $getInterFaceDetails[0]["interface_header_id"] : NULL;

		/* 	if(isset($_POST["dine_in_interface_header_id"]) && !empty($_POST["dine_in_interface_header_id"])){
			$interface_header_id = isset($_POST["dine_in_interface_header_id"]) ? $_POST["dine_in_interface_header_id"] : NULL;
		}else if(isset($_POST["interface_header_id"]) && !empty($_POST["interface_header_id"])){
			$interface_header_id = isset($_POST["interface_header_id"]) ? $_POST["interface_header_id"] : NULL;
		}else{
			$interface_header_id = NULL;
		} */

		$branch_id = !empty($this->selected_branch) ? $this->selected_branch : $this->branch_id;

		
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
		}else{
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
							$order_status = $interface_status = "Created";
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

						if( isset($_POST["payment_due"]) && !empty($_POST["payment_due"]) ){
							$payment_due = "Unpaid";
						}else{
							$payment_due = "Paid";
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
							'payment_due'             		=> $payment_due,
						);
						
						if(count($checkInterfaceTbl1) > 0)
						{
							$updateData = array(
								'created_by'                    => $this->user_id,
								'created_date'                  => $this->date_time,
								'last_updated_by'               => $this->user_id,
								'last_updated_date'             => $this->date_time,
								'order_status'                  => $order_status,
								'payment_due'             		=> $payment_due,
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

						$this->db->where('reference_header_id', $interface_header_id);
						$this->db->delete('ord_order_interface_lines');

						$discount_remarks = isset($_POST["discount_remarks"]) ? $_POST["discount_remarks"] : NULL;

						if( isset($_POST["payment_due"]) && !empty($_POST["payment_due"]) ){
							$payment_due = "Unpaid";
						}else{
							$payment_due = "Paid";
						}


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
							'payment_due'             		=> $payment_due,
							'discount_remarks'             	=> $discount_remarks,
							
						);
						
						$this->db->where('interface_header_id', $interface_header_id);
						$result = $this->db->update('ord_order_interface_headers', $headerData);
						
						if($result)
						{
							$count = count(array_filter($_POST['text_item_id']));

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
					else if(isset($_POST['customer_id']) && ($_POST['customer_id'] == 0 || $_POST['customer_id'] == ''))
					{
						$customer_id = isset($interFaceHeader[0]['customer_id']) ? $interFaceHeader[0]['customer_id'] : NULL;
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

					if( isset($_POST["payment_due"]) && !empty($_POST["payment_due"]) ){
						$payment_due = "Unpaid";
					}else{
						$payment_due = "Paid";
					}
					$discount_remarks = isset($_POST["discount_remarks"]) ? $_POST["discount_remarks"] : NULL;
						
					$headerData = array(
						"reference_header_id"  => $interface_header_id,
						"order_number" 		   => isset($interFaceHeader[0]['order_number']) ? $interFaceHeader[0]['order_number'] : NULL, #$documentNumber,
						"customer_id" 		   => isset($customer_id) ? $customer_id : NULL,
						//"customer_id" 		   => isset($interFaceHeader[0]['customer_id']) ? $interFaceHeader[0]['customer_id'] : NULL,
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
						"payment_due" 	       => isset($payment_due) ? $payment_due : $interFaceHeader[0]['payment_due'],
						"discount_remarks" 	   => $discount_remarks,
						"bill_print_status"    => isset($interFaceHeader[0]['bill_print_status']) ? $interFaceHeader[0]['bill_print_status'] : NULL,
						"bill_print_count" 	   => isset($interFaceHeader[0]['bill_print_count']) ? $interFaceHeader[0]['bill_print_count'] : 0,
						
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
								'order_seq_number'      => isset($lineData["order_seq_number"]) ? $lineData["order_seq_number"] : NULL,
								'reference_line_id'      => isset($lineData["interface_line_id"]) ? $lineData["interface_line_id"] : NULL,
								'product_id'	         => isset($lineData["product_id"]) ? $lineData["product_id"] : NULL,
								'price'	                 => isset($lineData["price"]) ? $lineData["price"] : NULL,
								'quantity'	             => isset($lineData["quantity"]) ? $lineData["quantity"] : NULL,
								// 'offer_percentage'	     => isset($lineData["offer_percentage"]) ? $lineData["offer_percentage"] : NULL,
								'offer_percentage'	     => isset($_POST["discount"]) ? $_POST["discount"] : NULL,
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

	#Mobile Number Serarch
	function ajaxSearchOnlinePOSCustomers()
    {
		if(isset($_POST["mobile_number"]))  
		{  
			$output = '';  

			$mobile_number = "concat('%','".serchFilter($_POST['mobile_number'])."','%')";
			
			$query = "select 
					per_user.user_id as customer_id,
					customer_name,
					mobile_number,
					address1,
					address2,
					address3 from cus_consumers 
					join per_user on per_user.reference_id = cus_consumers.customer_id
					where 1=1
					and ( cus_consumers.mobile_number like $mobile_number)";
			
			$result = $this->db->query($query)->result_array();
			
			if( count($result) > 0 )  
			{  
				$output = '<ul class="list-unstyled list-unstyled-new">';  
				foreach($result as $row)  
				{	
					$customer_id = $row["customer_id"];
					$mobile_number = $row["mobile_number"];
					$customer_name = $row["customer_name"];
					$address = $row["address1"];
				
					$output .= '<li onclick="return getConsumerDetails(\'' .$customer_id. '\',\'' .$mobile_number. '\',\'' .$customer_name. '\',\'' .$address. '\');">'.$mobile_number.'</li>';  
				}  
				$output .= '</ul>';  
				echo $output;  
			}
			else
			{
				echo "no_data";  
			}  
			exit;	
		}
	}

	function ajaxSaveCustomer()
	{ 
		if($_POST)
		{
			$customer_id = isset($_POST["add_customer_id"]) ? $_POST["add_customer_id"] : NULL;
			$add_mobile_number = isset($_POST["add_mobile_number"]) ? $_POST["add_mobile_number"] : NULL;
			$add_customer_name = isset($_POST["add_customer_name"]) ? $_POST["add_customer_name"] : NULL;
			$add_customer_address = isset($_POST["add_customer_address"]) ? $_POST["add_customer_address"] : NULL;

			if($customer_id != NULL && $customer_id > 0)
			{
				$customerData = array(
					#"mobile_number"        => $add_mobile_number,
					"customer_name"        => $add_customer_name,
					"address1"             => $add_customer_address,
					#"mobile_num_verified"  => 'Y',
					#'created_by'           => $this->user_id,
					#'created_date'         => $this->date_time,
					'last_updated_by'      => $this->user_id,
					'last_updated_date'    => $this->date_time,
				);

				$this->db->where('mobile_number',$add_mobile_number);
				$this->db->where('customer_id',$customer_id);
				$updateData = $this->db->update('cus_consumers', $customerData);

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
			}
			else
			{
				if( $add_mobile_number != NULL && !empty($add_mobile_number) )
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

					$customerData = array(
						"customer_number"      => $documentNumber,
						"mobile_number"        => $add_mobile_number,
						"customer_name"        => $add_customer_name,
						"address1"             => $add_customer_address,
						"mobile_num_verified"  => 'Y',
						'created_by'           => $this->user_id,
						'created_date'         => $this->date_time,
						'last_updated_by'      => $this->user_id,
						'last_updated_date'    => $this->date_time,
					);
					
					$this->db->insert('cus_consumers',$customerData);
					$customer_id = $this->db->insert_id();

					#Update Next Val DOC Number tbl start
					$nextValue = $startingNumber + 1;
					$doc_num_id = isset($getDocumentData[0]['doc_num_id']) ? $getDocumentData[0]['doc_num_id']:"";
					
					$UpdateData['next_number'] = $nextValue;
					$this->db->where('doc_num_id', $doc_num_id);
					$resultUpdateData = $this->db->update('doc_document_numbering', $UpdateData);
					#Update Next Val DOC Number tbl end

					#Per Users start
					$userData = array(
						"reference_id"        => $customer_id,
						"user_name" 	      => $add_mobile_number,
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
				}
				else
				{
					$user_id = NULL;
				}
			}
			$_SESSION["SEARCH_CUSTOMER_ID"] = $user_id;
			echo $user_id."@".$add_customer_name;
		}
		exit;
	}

	#CustomerMobile Number Serarch
	function ajaxSearchPOSDineInCustomers()
    {
		$mobileNumber = isset($_POST["mobile_number"]) ? $_POST["mobile_number"] : NULL;

		if( $mobileNumber != NULL )  
		{  
			$output = '';  

			$mobile_number = "concat('%','".serchFilter($mobileNumber)."','%')";
			
			$query = "select 
				per_user.user_id as customer_id,
				per_user.reference_id as consumer_id,
				customer_name,
				mobile_number,
				address1,
				address2,
				address3 from cus_consumers 
				join per_user on per_user.reference_id = cus_consumers.customer_id
				where 1=1
				and ( cus_consumers.mobile_number like $mobile_number)";
			
			$result = $this->db->query($query)->result_array();
			
			if( count($result) > 0 )  
			{  
				$output = '<ul class="list-unstyled list-unstyled-new">';  
				foreach($result as $row)  
				{	
					$consumer_id = $row["consumer_id"];
					$mobile_number = $row["mobile_number"];
					$customer_name = $row["customer_name"];
					$address = $row["address1"];
				
					$output .= '<li onclick="return getNewConsumerDetails(\'' .$consumer_id. '\',\'' .$mobile_number. '\',\'' .$customer_name. '\',\'' .$address. '\');">'.$mobile_number.'</li>';  
				}  
				$output .= '</ul>';  
				echo $output;  
			}
			else
			{
				echo "no_data";  
			}  
			exit;	
		}
	}

	#Dine In Orders
	function ajaxLoadPOSItems()
	{
		$page_data["order_id"] = $order_id = $_POST["order_id"];
		
		$page_data["getPOSItems"] = $this->web_fine_dine_model->getPOSOrderItems($order_id);
		
		$htmlData["printManualButtons"] = $this->load->view('themes/default/web_fine_dine/ajaxPages/printManualButtons',$page_data,true);
		
		$htmlData["posFooter"] = $this->load->view('themes/default/web_fine_dine/ajaxPages/posFooter',$page_data,true);
		
		$htmlData["posOrderItems"] = $this->load->view('themes/default/web_fine_dine/ajaxPages/ajaxPosItems',$page_data,true);
		echo json_encode($htmlData);
		exit;
	}

	function generatePOSOpenOrdersPDF($button_type="",$id="")
    {
		$page_data['id'] = $header_id = $id;

		if(file_exists("uploads/auto_generate_pdf/".$header_id.".pdf"))
		{
			unlink("uploads/auto_generate_pdf/".$header_id.".pdf");
		}
		$page_data['data'] = $this->orders_model->getOpenPrintOrderDetails($id);#Header Qry

		$LineData = $page_data['LineData'] = $this->orders_model->getOpenPrintOrderItems($id);
		
		if( count($LineData) > 0 )
		{
			ob_start();

			#Print Receipt HTML Start
			$html = ob_get_clean();
			$html = utf8_encode($html);
			$html = $this->load->view('backend/orders/printReceipt',$page_data,true);
			
			$mpdf = new \Mpdf\Mpdf([
				'curlAllowUnsafeSslRequests' => true,
			]);

			$mpdf->WriteHTML($html);
			$mpdf->Output('uploads/auto_generate_pdf/'.$id.'.pdf', 'F');
			#Print Receipt HTML End
		}
	}

	function ajaxLoadItemWiseReport()
	{
		$result = $this->web_fine_dine_model->getTodayItemWiseReport();
		$page_data["getItemWiseReport"] = $result["listing"];

		$htmlData["itemWiseReports"] = $this->load->view('themes/default/web_fine_dine/reports/ajaxPages/ajaxItemReports',$page_data,true);
		echo json_encode($htmlData);
		exit;
	}


	# Apply Order Discount
	public function applyDiscount() 
	{
        $order_id = $_POST["order_id"];		
        $discount = $_POST["discount"];		
        $discount_remarks = $_POST["discount_remarks"];	
		
		if( $order_id && $discount)
		{		
			#Header Table	
			$headerData = array(
				'discount_remarks'    => $_POST["discount_remarks"],
				'last_updated_by'    => $this->user_id,
				'last_updated_date'  => $this->date_time
			);

			$this->db->where('interface_header_id', $order_id);
			$headerResult = $this->db->update('ord_order_interface_headers', $headerData);

			#Line Table
			$lineData = array(
				'offer_percentage'   => $_POST["discount"],
				'last_updated_by'    => $this->user_id,
				'last_updated_date'  => $this->date_time
			);

			$this->db->where('reference_header_id', $order_id);
			$lineResult = $this->db->update('ord_order_interface_lines', $lineData);

			echo '1';
		}
		else
		{
			echo '2';
		}
		die;
    }
	
}
?>
