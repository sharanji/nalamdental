<?php
defined('BASEPATH') OR exit('No direct script access allowed');
include_once('vendor/autoload.php');
class Home_delivery extends CI_Controller 
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
	
	function manageHomeDeliveryOrders($type = '', $id = '', $status = '', $status_1 = '')
    {
		if (empty($this->user_id))
        {
			redirect(base_url() . 'admin/adminLogin', 'refresh');
		}
		
		$page_data['type'] = $type;
		$page_data['id'] = $id;
		
		$page_data['home_delivery'] = $page_data['manage_home_delivery']  = 1;
		$page_data['page_name']  = 'home_delivery/manageHomeDeliveryOrders';
		$page_data['page_title'] = 'Home Delivery Orders';

		switch(true)
		{
			case ($type == "payment_update"): #Update Payment Status
				
				$data=array(
					'payment_due' 		=> 'Paid',
					'last_updated_by'	=> $this->user_id,
					'last_updated_date' => $this->date_time,
				);
				$succ_msg = 'Payment status update successfully!';
	
				$this->db->where('header_id', $id);
				$this->db->update('ord_order_headers', $data);
				$this->session->set_flashdata('flash_message' , $succ_msg);
				redirect($_SERVER['HTTP_REFERER'], 'refresh');
			break;

			default : #Manage
				$totalResult = $this->home_delivery_model->getManageHomeDeliveryOrders("","",$this->totalCount);
				$page_data["totalRows"] = $totalRows = count($totalResult['totalCount']);

				if(!empty($_SESSION['PAGE']))
				{$limit = $_SESSION['PAGE'];
				}else{$limit = 10;}
				
				$branch_id = isset($_GET['branch_id']) ? $_GET['branch_id'] : NULL;
				$order_number = isset($_GET['order_number']) ? $_GET['order_number'] : NULL;
				$from_date = isset($_GET['from_date']) ? $_GET['from_date'] : NULL;
				$to_date = isset($_GET['from_date']) ? $_GET['to_date'] : NULL;

				$this->redirectURL = 'home_delivery/manageHomeDeliveryOrders?branch_id='.$branch_id.'&order_number='.$order_number.'&from_date='.$from_date.'&to_date='.$to_date.'';
				
				if ( $branch_id !=NULL || $order_number !=NULL || $from_date !=NULL || $to_date !=NULL) {
					$base_url = base_url().$this->redirectURL;
				} else {
					$base_url = base_url().$this->redirectURL;
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
				
				$result = $this->home_delivery_model->getManageHomeDeliveryOrders($limit,$offset,$this->pageCount);
				$page_data['resultData'] = $result["listing"];

				#show start and ending Count

				if(isset($_GET['per_page']) && $_GET['per_page'] > 1 && count($result["listing"]) == 0 )
				{
					redirect(base_url().$this->redirectURL, 'refresh');
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
				
				$total_counts = $total_count + count($result["listing"]);
				$page_data["ending"]  = $total_counts;
				#show start and ending Count end
			break;
		}	
		$this->load->view($this->adminTemplate, $page_data);
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
				
					$output .= '<li onclick="return getNewConsumerDetails(\'' .$customer_id. '\',\'' .$mobile_number. '\',\'' .$customer_name. '\',\'' .$address. '\');">'.$mobile_number.'</li>';  
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
					$customer_id = NULL;
				}
			}

			$_SESSION["SEARCH_CUSTOMER_ID"] = $customer_id;
			echo $customer_id."@".$add_customer_name;
		}
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
			$mpdf = new \Mpdf\Mpdf();
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
			$kot_mpdf = new \Mpdf\Mpdf();
			$kot_html = ob_get_clean();
			$kot_html = utf8_encode($kot_html);
			$kot_html = $this->load->view('backend/orders/kotPrint',$page_data,true);
			$kot_mpdf->WriteHTML($kot_html);
			$kot_mpdf->Output('uploads/auto_generate_pdf/kot/'.$id.'.pdf', 'F');
			#KOT Bill start end
		}
	}

	function deleteLineItems($interface_line_id="")
    {
		$this->db->where('interface_line_id', $interface_line_id);
		$this->db->delete('ord_order_interface_lines');
		echo 1;exit;
	}
}


?>
