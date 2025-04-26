<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Consumers extends CI_Controller 
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

	function ManageCustomer($type = '', $id = '', $status = '', $status1 = '', $status2 = '', $status3 = '')
    {
		if (empty($this->user_id))
        {
			redirect(base_url() . 'admin/adminLogin', 'refresh');
		}
	
		$page_data['type'] = $type;
		$page_data['id'] = $id;
		
		$page_data['manageCustomers'] = 1;
		$page_data['page_name']  = 'consumers/ManageCustomer';
		$page_data['page_title'] = 'Customers';
		
		if(isset($_POST['delete']) && isset($_POST['checkbox']))
		{
			$cnt=array();
			$cnt=count($_POST['checkbox']);
			
			for($i=0;$i<$cnt;$i++)
			{
				$del_id=$_POST['checkbox'][$i];
				 
				$this->db->where('user_id', $del_id);
				$this->db->delete('users');
			}
			$this->session->set_flashdata('flash_message' , "Data deleted successfully!");
			redirect($_SERVER['HTTP_REFERER'], 'refresh');
		}
		
		switch($type)
		{
			case "view": #view
				$query = "
					select cus_customers.*,
					cus_customer_address.address_name,
					cus_customer_address.address1,
					cus_customer_address.postal_code,
					cus_customer_address.land_mark
					
					from cus_consumers as cus_customers
					
					left join cus_customer_address on cus_customer_address.customer_id = cus_customers.customer_id
					where cus_customers.customer_id='".$id."' 
				";
				
				$page_data['edit_data'] = $this->db->query($query)->result_array();
				
			break;
			
			case "addresslist": #addresslist
				
				$query = "
					select 
					cus_customers.customer_id,
					cus_customers.customer_name,
					cus_customers.email_address,
					cus_customers.mobile_number,
					cus_customer_address.address1,
					cus_customer_address.address_name,
					
					
					cus_customer_address.land_mark,
					
					cus_customer_address.postal_code
					
					from  cus_consumers as cus_customers
					
					left join cus_customer_address on cus_customer_address.customer_id = cus_customers.customer_id
					
					where cus_customers.customer_id='".$id."' 
				";
				
				$page_data['edit_data'] = $this->db->query($query)->result_array();
				//print_r($page_data['edit_data']);exit;
				
				$source_type_id = 2;
				$page_data["totalRows"] = $totalRows = $this->consumer_model->getManageAddressCount($id);
	
				if(!empty($_SESSION['PAGE']))
				{$limit = $_SESSION['PAGE'];
				}else{$limit = 10;}
				
				if (!empty($_GET['keywords'])) {
					$base_url = base_url('customer/ManageCustomer/addresslist/'.$id.'?keywords='.$_GET['keywords']);
				} else {
					$base_url = base_url('customer/ManageCustomer/addresslist/'.$id.'?keywords=');
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
				
				$page_data['resultData'] = $result = $this->consumer_model->getManageAddress($limit, $offset, $id);
				
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
			
			case "bookmark": #bookmark
				
				$source_type_id = 2;
				$page_data["totalRows"] = $totalRows = $this->customer_model->getBookmarkCount($id);
	
				if(!empty($_SESSION['PAGE']))
				{$limit = $_SESSION['PAGE'];
				}else{$limit = 10;}
				
				if (!empty($_GET['keywords'])) {
					$base_url = base_url('customer/ManageCustomer/bookmark/'.$id.'?keywords='.$_GET['keywords']);
				} else {
					$base_url = base_url('customer/ManageCustomer/bookmark/'.$id.'?keywords=');
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
				
				$page_data['edit_data']  = $result = $this->customer_model->getBookmark($limit, $offset,$id);
				
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
			
			case "wallet": #wallet
				$query = 'select cus_customer_wallet.wallet_amount
				
						from cus_customer_wallet 
						
						left join branch on
							branch.branch_id = cus_customer_wallet.branch_id

						where  cus_customer_wallet.customer_id = '.$id;
		
				$result = $this->db->query($query)->result_array();
				$page_data["wallet_amount"] = isset($result[0]["wallet_amount"]) ? $result[0]["wallet_amount"] : 0;
			
			break;
			
			case "ordersHistory": #ordersHistory
				$page_data["totalRows"] = $totalRows = $this->consumer_model->getOrdersCount($id);#
		
				if(!empty($_SESSION['PAGE']))
				{
					$limit = $_SESSION['PAGE'];
				}else{
					$limit = 10;
				}
				
				if (!empty($_GET['keywords'])) {
					$base_url = base_url('customer/ManageCustomer/ordersHistory/'.$id.'?keywords='.$_GET['keywords']);
				} else {
					$base_url = base_url('customer/ManageCustomer/ordersHistory/'.$id.'?keywords=');
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
				
				$page_data['edit_data']  = $result= $this->consumer_model->getOrdersHistory($limit, $offset,$id);
				
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
            
            case "loginHistory": #ordersHistory
				$page_data["totalRows"] = $totalRows = $this->consumer_model->getloginCount($id);#
		
				if(!empty($_SESSION['PAGE']))
				{
					$limit = $_SESSION['PAGE'];
				}else{
					$limit = 10;
				}
				
				if (!empty($_GET['keywords'])) {
					$base_url = base_url('customer/ManageCustomer/ordersHistory/'.$id.'?keywords='.$_GET['keywords']);
				} else {
					$base_url = base_url('customer/ManageCustomer/ordersHistory/'.$id.'?keywords=');
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
				
				$page_data['edit_data']  = $result= $this->customer_model->getloginHistory($limit, $offset,$id);
				
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
			
			case "favourite": #addresslist
				
				$query = "
						select 
						ord_favourite_orders.*,
						ord_order_headers.header_id,
						ord_order_headers.customer_id,
						ord_order_headers.order_number,
						cus_customers.customer_name,
						cus_customers.mobile_number,
						ord_order_headers.ordered_date,
						(ord_order_lines.quantity * ord_order_lines.price) as linetotal,
						sum(ord_order_lines.price * ord_order_lines.quantity) as bill_amount
						
						from ord_favourite_orders
						
					left join ord_order_headers on 
						ord_order_headers.header_id = ord_favourite_orders.header_id
					
					left join ord_order_lines on 
						ord_order_lines.header_id = ord_favourite_orders.header_id
						
					left join cus_consumers as cus_customers on 
						cus_customers.customer_id = ord_favourite_orders.customer_id
					
					where cus_customers.customer_id='".$id."' 
				";
				
				$page_data['edit_data'] = $this->db->query($query)->result_array();
				//print_r($page_data['edit_data']);exit;
				
				$source_type_id = 2;
				$page_data["totalRows"] = $totalRows = $this->consumer_model->getManageFavouriteCount($id);
	
				if(!empty($_SESSION['PAGE']))
				{$limit = $_SESSION['PAGE'];
				}else{$limit = 10;}
				
				if (!empty($_GET['keywords'])) {
					$base_url = base_url('customer/ManageCustomer/favourite/'.$id.'?keywords='.$_GET['keywords']);
				} else {
					$base_url = base_url('customer/ManageCustomer/favourite/'.$id.'?keywords=');
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
				
				$page_data['resultData'] = $result = $this->consumer_model->getManageFavourite($limit, $offset, $id);
				
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
			
			case "status": #Block & Unblock
				if($status == "Y"){
					$data['active_flag'] = "Y";
					$succ_msg = 'Customer active successfully!';
				}else{
					$data['active_flag'] = "N";
					$succ_msg = 'Customer inactive successfully!';
				}
				$this->db->where('customer_id', $id);
				$this->db->update('cus_consumers', $data);
				$this->session->set_flashdata('flash_message' , $succ_msg);
				redirect(base_url() . 'consumers/ManageCustomer', 'refresh');
			break;
			
			case "export":
				$data = $this->db->query("select 
						random_user_id,
						pic_number,
						first_name,
						phone_number,
						email,
						gender,
						age,
						address1,
						pin_code,
						blood_group
						from users where register_type =1")->result_array();
						
						
				
				#$data[] = array('f_name'=> "Nishit", 'l_name'=> "patel", 'mobile'=> "999999999", 'gender'=> "male");
				
				header("Content-type: application/csv");
				header("Content-Disposition: attachment; filename=\"Patient".".csv\"");
				header("Pragma: no-cache");
				header("Expires: 0");

				$handle = fopen('php://output', 'w');
				fputcsv($handle, array("S.No","Reg Number","PIC Number","Patient Name","Mobile Number","Email","Gender","Age","Blood Group","Pin Code","Address"));
				$cnt=1;
				foreach ($data as $row) 
				{
					$gender ="";
					foreach($this->gender as $key=>$value)
					{
						if($row['gender'] == $key)
						{
							$gender .=$value;
						}
					}
					$narray=array(
							$cnt,
							$row["random_user_id"],
							$row["pic_number"],
							ucfirst($row["first_name"]),
							$row["phone_number"],
							$row["email"],
							$gender,
							$row["age"],
							$row["blood_group"],
							$row["pin_code"],
							$row["address1"]
						);
					fputcsv($handle, $narray);
					$cnt++;
				}
				fclose($handle);
				exit;
			break;
			
			case "import":
				if($_FILES)
				{
					$filename = $_FILES["csv"]["tmp_name"];      
					
					if($_FILES["csv"]["size"] > 0)
					{
						$file = fopen($filename, "r");
						
						for ($lines = 0; $data = fgetcsv($file,1000,",",'"'); $lines++) 
						{
							if ($lines == 0) continue;
							
							$joined_date = strtotime(date('d-m-Y h:i:s a',time()));
							$user_status = 1;
							$register_type = 1;#Patient
							
							$sql = "INSERT INTO `users`(`random_user_id`, `first_name`,`phone_number`,`email`,`joined_date`,`user_status`,`register_type`) 
									VALUES 
								('".$data[0]."','".$data[1]."','".$data[2]."','".$data[3]."','".$joined_date."','".$user_status."','".$register_type."')";
							$this->db->query($sql);
						}
						fclose($file); 
					}
					else
					{
						$this->session->set_flashdata('error_message' , "Patient import error!");
						redirect(base_url() . 'patient/ManagePatient', 'refresh');
					}
					$this->session->set_flashdata('flash_message' , "Patient import successfully!");
					redirect(base_url() . 'patient/ManagePatient', 'refresh');
				}
			break;
			
			default : #Manage
			
				$totalResult = $this->consumer_model->getManageCustomer("","",$this->totalCount);
				$page_data["totalRows"] = $totalRows = count($totalResult);

				if(!empty($_SESSION['PAGE']))
				{$limit = $_SESSION['PAGE'];
				}else{$limit = 10;}
				
				$redirectURL = 'consumers/ManageCustomer';
				
				if (!empty($_GET['customer_id']) || !empty($_GET['active_flag']) || !empty($_GET['mobile_number'])) 
				{
					$base_url = base_url('consumers/ManageCustomer?customer_id='.$_GET['customer_id'].'&active_flag='.$_GET['active_flag'].'&mobile_number='.$_GET['mobile_number']);
				} else {
					$base_url = base_url('consumers/ManageCustomer?customer_id=&active_flag=Y&mobile_number=');
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
				
				$page_data['resultData']  = $result = $this->consumer_model->getManageCustomer($limit, $offset,$this->pageCount);
				
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

	function viewCustomerDetails($id = '')
	{
		if (empty($this->customer_id))
		{
			redirect(base_url() . 'admin/adminLogin', 'refresh');
		}
	
		$page_data['id'] = $id;
		
		$page_data['ManageCustomer'] = 1;
		$page_data['page_name']  = 'customer/viewCustomerDetails';
		$page_data['page_title'] = 'View Customer ';
		
		#View
		$page_data["totalRows"] = $totalRows = $this->customer_model->customerOrderHistoryCount($id);

		if(!empty($_SESSION['PAGE']))
		{$limit = $_SESSION['PAGE'];
		}else{$limit = 10;}
		
		if (!empty($_GET['keywords'])) {
			$base_url = base_url('customer/ManageCustomer?keywords='.$_GET['keywords']);
		} else {
			$base_url = base_url('customer/ManageCustomer?keywords=');
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
		
		$page_data['order_history'] 	= $this->customer_model->customerOrderHistory($id,$limit, $offset);
		$page_data['customer_details'] 	=  $this->db->query("select * from customer 
											where customer.customer_id='".$id."' ")->result_array();;
	
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
	
	
	function viewCustomer($id = '')
	{		
		if (empty($this->user_id))
        {
			redirect(base_url() . 'admin/adminLogin', 'refresh');
		}
		$page_data['id'] = $id;
		$page_data['ManageCustomer'] = 1;
		$page_data['page_name']  = 'customer/viewCustomer';
		$page_data['page_title'] = 'View Customer';
		$this->load->view($this->adminTemplate, $page_data);
	}

	#Customer Sites
	function ManageCustomerSites($type = '', $id = '', $status = '')
    {
		if (empty($this->user_id))
        {
			redirect(base_url() . 'admin/adminLogin', 'refresh');
		}
	
		$page_data['type'] = $type;
		$page_data['id'] = $id;
		
		$page_data['ManageCustomer'] = 1;
		$page_data['page_name']  = 'customer/ManageCustomerSites';
		$page_data['page_title'] = 'Customer Sites';
		
		switch($type)
		{
			case "add": #add
				if($_POST)
				{
					$data['customer_id'] = $this->input->post('new_user_id');
					$data['site_name'] = $this->input->post('site_name');
					$data['email'] = $this->input->post('email');
					$data['phone_number'] = $this->input->post('phone_number');
					$data['gst_number'] = $this->input->post('gst_number');
					$data['contact_person'] = $this->input->post('contact_person');
					/* $data['country_id'] = $this->input->post('country_id');
					$data['state_id'] = $this->input->post('state_id');
					$data['city_id'] = $this->input->post('city_id');
					$data['address'] = $this->input->post('address'); */
					$data['site_status'] = 1;


					#Billing Details
					$data['billing_country_id'] = $this->input->post('billing_country_id');
					$data['billing_state_id'] = $this->input->post('billing_state_id');
					$data['billing_city_id'] = $this->input->post('billing_city_id');
					$data['billing_zip_code'] = $this->input->post('billing_postal_code');
					$data['billing_address'] = $this->input->post('billing_address');
					$data['chk_billing_address'] = isset($_POST['chk_billing_address']) ? $_POST['chk_billing_address'] : 0 ;
					
					#Shipping Details
					$data['shipping_country_id'] = $this->input->post('shipping_country_id');
					$data['shipping_state_id'] = $this->input->post('shipping_state_id');
					$data['shipping_city_id'] = $this->input->post('shipping_city_id');
					$data['shipping_zip_code'] = $this->input->post('shipping_postal_code');
					$data['shipping_address'] = $this->input->post('shipping_address');
					$data['chk_shipping_address'] = isset($_POST['chk_shipping_address']) ? $_POST['chk_shipping_address'] : 0 ;
					
					$this->db->insert('customer_sites', $data);
					$id = $this->db->insert_id();
					
					if($id !="")
					{	
						$this->session->set_flashdata('flash_message' , "Customer Site added Successfully!");
						redirect(base_url() . 'customer/ManageCustomerSites', 'refresh');
					}
				}
			break;
			
			case "edit": #edit
				$page_data['edit_data'] = $this->db->get_where('customer_sites', array('customer_site_id' => $id))
										->result_array();
				if($_POST)
				{
					$data['customer_id'] = $this->input->post('new_user_id');
					$data['site_name'] = $this->input->post('site_name');
					$data['email'] = $this->input->post('email');
					$data['phone_number'] = $this->input->post('phone_number');
					$data['gst_number'] = $this->input->post('gst_number');
					$data['contact_person'] = $this->input->post('contact_person');
					/* $data['country_id'] = $this->input->post('country_id');
					$data['state_id'] = $this->input->post('state_id');
					$data['city_id'] = $this->input->post('city_id');
					$data['address'] = $this->input->post('address'); */
					

					#Billing Details
					$data['billing_country_id'] = $this->input->post('billing_country_id');
					$data['billing_state_id'] = $this->input->post('billing_state_id');
					$data['billing_city_id'] = $this->input->post('billing_city_id');
					$data['billing_zip_code'] = $this->input->post('billing_postal_code');
					$data['billing_address'] = $this->input->post('billing_address');
					$data['chk_billing_address'] = isset($_POST['chk_billing_address']) ? $_POST['chk_billing_address'] : 0 ;
					
					#Shipping Details
					$data['shipping_country_id'] = $this->input->post('shipping_country_id');
					$data['shipping_state_id'] = $this->input->post('shipping_state_id');
					$data['shipping_city_id'] = $this->input->post('shipping_city_id');
					$data['shipping_zip_code'] = $this->input->post('shipping_postal_code');
					$data['shipping_address'] = $this->input->post('shipping_address');
					$data['chk_shipping_address'] = isset($_POST['chk_shipping_address']) ? $_POST['chk_shipping_address'] : 0 ;
					
					
					$this->db->where('customer_site_id', $id);
					$result = $this->db->update('customer_sites', $data);
					
					if($result)
					{
						
						$this->session->set_flashdata('flash_message' , "Customer Site updated Successfully!");
						redirect(base_url() . 'customer/ManageCustomerSites', 'refresh');
					}
				}
			break;
						
			case "status": #Block & Unblock
				if($status == 1){
					$data['site_status'] = 1;
					$succ_msg = 'Customer Site unblocked successfully!';
				}else{
					$data['site_status'] = 0;
					$succ_msg = 'Customer Site blocked successfully!';
				}
				$this->db->where('customer_site_id', $id);
				$this->db->update('customer_sites', $data);
				$this->session->set_flashdata('flash_message' , $succ_msg);
				redirect(base_url() . 'customer/ManageCustomerSites', 'refresh');
			break;
			
			default : #Manage
				$page_data["totalRows"] = $totalRows = $this->customer_model->getManageCustomerSitesCount();
	
				if(!empty($_SESSION['PAGE']))
				{$limit = $_SESSION['PAGE'];
				}else{$limit = 10;}
				
				$base_url = base_url().'customer/ManageCustomerSites';
				
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
				
				$page_data['resultData'] = $result = $data =$this->customer_model->getManageCustomerSites($limit, $offset);
				
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
		if (empty($this->user_id))
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
			$select_type_condition = "users.register_type = 1"; #Customer
			
			$output = '';  
			
			$condition = '
				users.user_status=1 and
				'.$select_type_condition.' and
				(
					users.first_name like "%'.($_POST["query"]).'%" or 
					users.last_name like "%'.($_POST["query"]).'%" or 
					users.random_user_id like "%'.($_POST["query"]).'%" or
					users.mobile_number like "%'.($_POST["query"]).'%" or
					users.phone_number like "%'.($_POST["query"]).'%"
				)';

			$query = "select 
						users.random_user_id,
						users.first_name,
						users.user_id,
						users.phone_number,
						users.mobile_number,
						users.email
						
						from users
						
					where ".$condition." ";
			
			$result = $this->db->query($query)->result_array();
			
			$output = '<ul class="list-unstyled-new">';  
			#$output .= '<li onclick="getuserId(0);">All</li>'; 
			if( count($result) > 0 )  
			{  
				foreach($result as $row)  
				{	
					$patinetID=  $row["user_id"];
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

}