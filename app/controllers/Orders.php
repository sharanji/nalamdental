<?php
defined('BASEPATH') OR exit('No direct script access allowed');
include_once('vendor/autoload.php');
class Orders extends CI_Controller 
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
	
	function openOrders($type = '', $id = '', $status = '', $status_1 = '')
    {
		if (empty($this->user_id))
        {
			redirect(base_url() . 'admin/adminLogin', 'refresh');
		}
		
		$page_data['type'] = $type;
		$page_data['id'] = $id;
		
		$page_data['ManageOrders'] = 1;
		$page_data['page_name']  = 'orders/openOrders';
		$page_data['page_title'] = 'Open Orders';

		/* if($this->user_id==1) #Admin
		{
			$condition = " 1=1 ";
			$joinQuery ="";
		}
		else #Branch Admins
		{
			$condition = " 1=1 
			";
		}
		$query = "select 
				ord_order_headers.header_id
			from ord_order_headers

		left join customer on customer.customer_id = ord_order_headers.customer_id
		
		left join expense_payment_type on expense_payment_type.payment_type_id = ord_order_headers.payment_method
		
		left join cus_customer_address on 
			cus_customer_address.customer_address_id = ord_order_headers.address_id
		
		left join country on 
			country.country_id = customer.country_id

		$joinQuery
		
		where $condition
		";
		
		$getNewOrdersCount = $this->db->query($query)->result_array();
		$page_data['page_title'] = 'Orders'; */
	
		if(isset($_POST['confirmOrder']) && isset($_POST['confirmOrder']))
		{
			$cnt=array();
			$cnt=count($_POST['checkbox']);
			
			for($i=0;$i<$cnt;$i++)
			{
				$OrderStatus = "Confirmed";
				$header_id = $_POST['checkbox'][$i];
				$data['order_status'] = $OrderStatus;
				$data['accepted_date'] = $this->date_time;
				$data['last_updated_by'] = $this->user_id;
				$data['last_updated_date'] = $this->date_time;
				
				$line_data['accepted_date'] = $this->date_time;
				$line_data['line_status'] = $OrderStatus;
				$line_data['last_updated_by'] = $this->user_id;
				$line_data['last_updated_date'] = $this->date_time;
				

				$orderQuery = "select 
					ord_order_headers.customer_id, 
					ord_order_headers.order_number,							
					customer.mobile_number

				from ord_order_headers 
				
				left join cus_customers as customer on 
					customer.customer_id = ord_order_headers.customer_id
				
				where 
					ord_order_headers.header_id='".$header_id."' ";
				$getOrderDetails = $this->db->query($orderQuery)->result_array();

				$order_number = $getOrderDetails[0]['order_number'];
				$mobile_number = $getOrderDetails[0]['mobile_number'];
				$otpMobileNumber = $mobile_number;
		
				$otpMessage = '#'. $order_number.' Your order has been confirmed. - Thank You.  '.strtoupper(SITE_NAME);
				$sendSMS = sendSMS($otpMobileNumber,$otpMessage);

				$this->db->where('header_id', $header_id);
				$this->db->update('ord_order_headers', $data);

				$this->db->where('header_id', $header_id);
				$this->db->where('cancel_status','N');
				$this->db->where('line_status!=','Cancelled');
				$this->db->update('ord_order_lines', $line_data);
			}
			
			$this->session->set_flashdata('flash_message' , "Orders confirmed successfully!");

			redirect($_SERVER['HTTP_REFERER'], 'refresh');
		}
		else if(isset($_POST['preparingOrder']) && isset($_POST['checkbox']))
		{
			$cnt=array();
			$cnt=count($_POST['checkbox']);
			
			for($i=0;$i<$cnt;$i++)
			{
				$OrderStatus = "Preparing";
				$header_id = $_POST['checkbox'][$i];
				$data['order_status'] = $OrderStatus;
				$data['preparing_date'] = $this->date_time;
				$data['last_updated_by'] = $this->user_id;
				$data['last_updated_date'] = $this->date_time;
				
				$line_data['preparing_date'] = $this->date_time;
				$line_data['line_status'] = $OrderStatus;
				$line_data['last_updated_by'] = $this->user_id;
				$line_data['last_updated_date'] = $this->date_time;

				$orderQuery = "select 
					ord_order_headers.customer_id, 
					ord_order_headers.order_number,							
					customer.mobile_number

				from ord_order_headers 
				
				left join cus_customers as customer on 
					customer.customer_id = ord_order_headers.customer_id
				
				where 
					ord_order_headers.header_id='".$header_id."' ";
				$getOrderDetails = $this->db->query($orderQuery)->result_array();

				$order_number = $getOrderDetails[0]['order_number'];
				$mobile_number = $getOrderDetails[0]['mobile_number'];
				$otpMobileNumber = $mobile_number;
		
				$otpMessage = '#'. $order_number.' Your order has been confirmed. - Thank You.  '.strtoupper(SITE_NAME);
				$sendSMS = sendSMS($otpMobileNumber,$otpMessage);

				$this->db->where('header_id', $header_id);
				$this->db->update('ord_order_headers', $data);

				$this->db->where('header_id', $header_id);
				$this->db->where('cancel_status','N');
				$this->db->where('line_status!=','Cancelled');
				$this->db->update('ord_order_lines', $line_data);
			}
			
			$this->session->set_flashdata('flash_message' , "Orders preparing status updated successfully!");
			redirect($_SERVER['HTTP_REFERER'], 'refresh');
		}
		else if(isset($_POST['shippedOrder']) && isset($_POST['checkbox']))
		{
			$cnt=array();
			$cnt=count($_POST['checkbox']);
			
			for($i=0;$i<$cnt;$i++)
			{
				$OrderStatus = "Shipped";
				$header_id = $_POST['checkbox'][$i];
				$data['order_status'] = $OrderStatus;
				$data['out_for_delivery_date'] = $this->date_time;
				$data['last_updated_by'] = $this->user_id;
				$data['last_updated_date'] = $this->date_time;
				
				$line_data['out_for_delivery_date'] = $this->date_time;
				$line_data['line_status'] = $OrderStatus;
				$line_data['last_updated_by'] = $this->user_id;
				$line_data['last_updated_date'] = $this->date_time;

				$orderQuery = "select 
					ord_order_headers.customer_id, 
					ord_order_headers.order_number,							
					customer.mobile_number

				from ord_order_headers 
				
				left join cus_customers as customer on 
					customer.customer_id = ord_order_headers.customer_id
				
				where 
					ord_order_headers.header_id='".$header_id."' ";
				$getOrderDetails = $this->db->query($orderQuery)->result_array();

				$order_number = $getOrderDetails[0]['order_number'];
				$mobile_number = $getOrderDetails[0]['mobile_number'];
				$otpMobileNumber = $mobile_number;
		
				$otpMessage = '#'. $order_number.' Your order has been confirmed. - Thank You.  '.strtoupper(SITE_NAME);
				$sendSMS = sendSMS($otpMobileNumber,$otpMessage);

				$this->db->where('header_id', $header_id);
				$this->db->update('ord_order_headers', $data);

				$this->db->where('header_id', $header_id);
				$this->db->where('cancel_status','N');
				$this->db->where('line_status!=','Cancelled');
				$this->db->update('ord_order_lines', $line_data);
			}
			
			$this->session->set_flashdata('flash_message' , "Orders shipped successfully!");
			redirect($_SERVER['HTTP_REFERER'], 'refresh');
		}
		else if(isset($_POST['deliverOrder']) && isset($_POST['checkbox']))
		{
			$cnt=array();
			
			$cnt=count($_POST['checkbox']);
			
			for($i=0;$i<$cnt;$i++)
			{
				$OrderStatus = "Delivered";
				$header_id = $_POST['checkbox'][$i];
				$data['order_status'] = $OrderStatus;
				$data['delivered_date'] = $this->date_time;
				$data['last_updated_by'] = $this->user_id;
				$data['last_updated_date'] = $this->date_time;
				
				$line_data['delivered_date'] = $this->date_time;
				$line_data['line_status'] = $OrderStatus;
				$line_data['last_updated_by'] = $this->user_id;
				$line_data['last_updated_date'] = $this->date_time;

				$orderQuery = "select 
					ord_order_headers.customer_id, 
					ord_order_headers.order_number,							
					customer.mobile_number

				from ord_order_headers 
				
				left join cus_customers as customer on 
					customer.customer_id = ord_order_headers.customer_id
				
				where 
					ord_order_headers.header_id='".$header_id."' ";
				$getOrderDetails = $this->db->query($orderQuery)->result_array();

				$order_number = $getOrderDetails[0]['order_number'];
				$mobile_number = $getOrderDetails[0]['mobile_number'];
				$otpMobileNumber = $mobile_number;
		
				$otpMessage = '#'. $order_number.' Your order has been delivered. - Thank You.  '.strtoupper(SITE_NAME);
				$sendSMS = sendSMS($otpMobileNumber,$otpMessage);

				$this->db->where('header_id', $header_id);
				$this->db->update('ord_order_headers', $data);

				$this->db->where('header_id', $header_id);
				$this->db->where('cancel_status','N');
				$this->db->where('line_status!=','Cancelled');
				$this->db->update('ord_order_lines', $line_data);
			}

			$this->session->set_flashdata('flash_message' , "Orders delivered status updated successfully!");
			redirect($_SERVER['HTTP_REFERER'], 'refresh');
		}
		else if(isset($_POST['closedOrder']) && isset($_POST['checkbox']))
		{
			$cnt=array();
			
			$cnt=count($_POST['checkbox']);
			
			for($i=0;$i<$cnt;$i++)
			{
				$OrderStatus = "Closed";
				$header_id = $_POST['checkbox'][$i];
				$data['order_status'] = $OrderStatus;
				$data['closed_date'] = $this->date_time;
				$data['last_updated_by'] = $this->user_id;
				$data['last_updated_date'] = $this->date_time;
				
				$line_data['closed_date'] = $this->date_time;
				$line_data['line_status'] = $OrderStatus;
				$line_data['last_updated_by'] = $this->user_id;
				$line_data['last_updated_date'] = $this->date_time;

				$this->db->where('header_id', $header_id);
				$this->db->update('ord_order_headers', $data);

				$this->db->where('header_id', $header_id);
				$this->db->where('cancel_status','N');
				$this->db->where('line_status!=','Cancelled');
				$this->db->update('ord_order_lines', $line_data);
			}

			$this->session->set_flashdata('flash_message' , "Orders delivered status updated successfully!");
			redirect($_SERVER['HTTP_REFERER'], 'refresh');
		}
		else if(isset($_POST['cancelOrder']) && isset($_POST['checkbox']))
		{
			$cnt=array();
			$cnt=count($_POST['checkbox']);
			
			for($i=0;$i<$cnt;$i++)
			{
				$header_id = $_POST['checkbox'][$i];

				$data["cancel_status"] = 'Y';
				$data["cancelled_by"] = $this->user_id;
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
				
				left join cus_customers on 
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
						line_tbl.header_id='".$id."' and 
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
			}
			
			$this->session->set_flashdata('flash_message' , "Orders cancelled successfully!");
			redirect($_SERVER['HTTP_REFERER'], 'refresh');
		}
		
		switch($type)
		{
			case 'status':
				switch ($status) 
				{
					case "Confirmed": #Accepted
						$data['order_status'] = $status;
						$data['accepted_date'] = $this->date_time;
						$data['last_updated_by'] = $this->user_id;
						$data['last_updated_date'] = $this->date_time;
						
						$line_data['accepted_date'] = $this->date_time;
						$line_data['line_status'] = $status;
						$line_data['last_updated_by'] = $this->user_id;
						$line_data['last_updated_date'] = $this->date_time;

						$succ_msg = 'Order confirmed successfully!';

						$orderQuery = "select 
							ord_order_headers.customer_id, 
							ord_order_headers.order_number,							
							customer.mobile_number

						from ord_order_headers 
						
						left join cus_customers as customer on 
							customer.customer_id = ord_order_headers.customer_id
						
						where 
							ord_order_headers.header_id='".$id."' ";
						$getOrderDetails = $this->db->query($orderQuery)->result_array();

						$order_number = $getOrderDetails[0]['order_number'];
						$mobile_number = $getOrderDetails[0]['mobile_number'];
						$otpMobileNumber = $mobile_number;
				
						$otpMessage = '#'. $order_number.' Your order has been confirmed. - Thank You.  '.strtoupper(SITE_NAME);
						$sendSMS = sendSMS($otpMobileNumber,$otpMessage);
					break;
					
					case "Preparing": #Preparing
						$data['order_status'] = $status;
						$data['preparing_date'] = $this->date_time;
						$data['last_updated_by'] = $this->user_id;
						$data['last_updated_date'] = $this->date_time;

						$line_data['preparing_date'] = $this->date_time;
						$line_data['line_status'] = $status;
						$line_data['last_updated_by'] = $this->user_id;
						$line_data['last_updated_date'] = $this->date_time;

						$succ_msg = 'Order prepared successfully!';

						$orderQuery = "select 
							ord_order_headers.customer_id, 
							ord_order_headers.order_number,							
							customer.mobile_number

						from ord_order_headers 
						
						left join cus_customers as customer on 
							customer.customer_id = ord_order_headers.customer_id
						
						where 
							ord_order_headers.header_id='".$id."' ";
						$getOrderDetails = $this->db->query($orderQuery)->result_array();
						
						$order_number = $getOrderDetails[0]['order_number'];
						$mobile_number = $getOrderDetails[0]['mobile_number'];
						$otpMobileNumber = $mobile_number;
				
						$otpMessage = '#'. $order_number.' Your order has been prepared. - Thank You.  '.strtoupper(SITE_NAME);
						$sendSMS = sendSMS($otpMobileNumber,$otpMessage);
					break;
					
					case 'Shipped': #Shipped
						$data['order_status'] = $status;
						$data['out_for_delivery_date'] = $this->date_time;
						$data['last_updated_by'] = $this->user_id;
						$data['last_updated_date'] = $this->date_time;

						$line_data['out_for_delivery_date'] = $this->date_time;
						$line_data['line_status'] = $status;
						$line_data['last_updated_by'] = $this->user_id;
						$line_data['last_updated_date'] = $this->date_time;
						$succ_msg = 'Order shipped successfully!';

						$orderQuery = "select 
							ord_order_headers.customer_id, 
							ord_order_headers.order_number,							
							customer.mobile_number

						from ord_order_headers 
						
						left join cus_customers as customer on 
							customer.customer_id = ord_order_headers.customer_id
						
						where 
							ord_order_headers.header_id='".$id."' ";
						$getOrderDetails = $this->db->query($orderQuery)->result_array();
						
						$order_number = $getOrderDetails[0]['order_number'];
						$mobile_number = $getOrderDetails[0]['mobile_number'];
						$otpMobileNumber = $mobile_number;
				
						$otpMessage = '#'. $order_number.' Your order has been shipped. - Thank You.  '.strtoupper(SITE_NAME);
						$sendSMS = sendSMS($otpMobileNumber,$otpMessage);
					break;

					case 'Delivered': #Delivered
						$data['order_status'] = $status;
						$data['delivered_date'] = $this->date_time;
						$data['last_updated_by'] = $this->user_id;
						$data['last_updated_date'] = $this->date_time;
						
						$line_data['delivered_date'] = $this->date_time;
						$line_data['line_status'] = $status;
						$line_data['last_updated_by'] = $this->user_id;
						$line_data['last_updated_date'] = $this->date_time;

						$succ_msg = 'Order delivered successfully!';
						$orderQuery = "select 
							ord_order_headers.customer_id, 
							ord_order_headers.order_number,							
							customer.mobile_number

						from ord_order_headers 
						
						left join cus_customers as customer on 
							customer.customer_id = ord_order_headers.customer_id
						
						where 
							ord_order_headers.header_id='".$id."' ";
						$getOrderDetails = $this->db->query($orderQuery)->result_array();
						
						$order_number = $getOrderDetails[0]['order_number'];
						$mobile_number = $getOrderDetails[0]['mobile_number'];
						$otpMobileNumber = $mobile_number;
				
						$otpMessage = '#'. $order_number.' Your order has been delivered. - Thank You.  '.strtoupper(SITE_NAME);
						$sendSMS = sendSMS($otpMobileNumber,$otpMessage);
					break;

					case 'Closed': #Closed
						$data['order_status'] = $status;
						$data['closed_date'] = $this->date_time;
						$data['last_updated_by'] = $this->user_id;
						$data['last_updated_date'] = $this->date_time;
						

						$line_data['closed_date'] = $this->date_time;
						$line_data['line_status'] = $status;
						$line_data['last_updated_by'] = $this->user_id;
						$line_data['last_updated_date'] = $this->date_time;

						$succ_msg = 'Order closed successfully!';
					break;
					
				}
				
				$this->db->where('header_id', $id);
				$this->db->update('ord_order_headers', $data);


				$this->db->where('header_id', $id);
				$this->db->where('cancel_status','N');
				$this->db->where('line_status!=','Cancelled');
				$this->db->update('ord_order_lines', $line_data);

				$this->session->set_flashdata('flash_message' , $succ_msg);
				redirect($_SERVER["HTTP_REFERER"], 'refresh');
			break;
			
			case 'paid_status':
				$data['payment_transaction_status'] = 'SUCCESS';
				$data['paid_status'] = 'Y';
				$data['last_updated_by'] = $this->user_id;
				$data['last_updated_date'] = $this->date_time;

				$this->db->where('header_id', $id);
				$this->db->update('ord_order_headers', $data);
				
				$this->session->set_flashdata('flash_message' , "Payment paid successfully!");
				redirect($_SERVER["HTTP_REFERER"], 'refresh');
			break;
			
			case "readOrders":		
				$updateData["notification_read_status"] = 'Y';
				$this->db->where('header_id',$id);
				$this->db->update('ord_order_headers',$updateData);		
				redirect($_SERVER["HTTP_REFERER"], 'refresh');				
			break;
			
			default : #Manage
				$totalResult = $this->orders_model->getOrders("","",$this->totalCount);
				$page_data["totalRows"] = $totalRows = count($totalResult['totalCount']);

				if(!empty($_SESSION['PAGE']))
				{$limit = $_SESSION['PAGE'];
				}else{$limit = 10;}
				
				if(!empty($_SESSION['PAGE']))
				{$limit = $_SESSION['PAGE'];
				}else{$limit = 10;}

				$redirectURL = 'orders/openOrders?order_number=&mobile_number=&payment_type_id=&from_date=&to_date=&order_status=Total_Orders';
				
				$OrderStatus = isset($_GET['order_status']) ? $_GET['order_status'] : "";
				if (!empty($_GET['order_number']) || !empty($_GET['mobile_number']) || !empty($_GET['payment_type_id']) || !empty($_GET['from_date']) || !empty($_GET['to_date']) || !empty($OrderStatus) ) {
					$base_url = base_url('orders/openOrders?order_number='.$_GET['order_number'].'&mobile_number='.$_GET['mobile_number'].'&payment_type_id='.$_GET['payment_type_id'].'&from_date='.$_GET['from_date'].'&to_date='.$_GET['to_date'].'&order_status='.$OrderStatus.'');
				} else {
					$base_url = base_url('orders/openOrders?order_number=&mobile_number=&payment_type_id=2&from_date=&to_date=&order_status=');
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
				
				$result = $this->orders_model->getOrders($limit,$offset,$this->pageCount);
				$page_data['resultData'] = $result["listing"];

				$page_data['bookedCount'] = isset($result["bookedCount"]) ? $result["bookedCount"] : array();
				$page_data['confirmedCount'] = isset($result["confirmedCount"]) ? $result["confirmedCount"] : array();
				$page_data['preparingCount'] = isset($result["preparingCount"]) ? $result["preparingCount"] : array();
				$page_data['shippedCount'] = isset($result["shippedCount"]) ? $result["shippedCount"] : array();
				$page_data['deliveredCount'] = isset($result["deliveredCount"]) ? $result["deliveredCount"] : array();
				$page_data['totalOrdersCount'] = isset($result["totalOrdersCount"]) ? $result["totalOrdersCount"] : array();
			
				#show start and ending Count
				if(isset($_GET['per_page']) && $_GET['per_page'] > 1 && count($result["listing"]) == 0 )
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
				
				$total_counts = $total_count + count($result["listing"]);
				$page_data["ending"]  = $total_counts;
				#show start and ending Count end
			break;
		}	
		$this->load->view($this->adminTemplate, $page_data);
	}

	function manageOrders($type = '', $id = '', $status = '', $status_1 = '')
    {
		if (empty($this->user_id))
        {
			redirect(base_url() . 'admin/adminLogin', 'refresh');
		}
		
		$page_data['type'] = $type;
		$page_data['id'] = $id;
		
		$page_data['ManageOrders'] = 1;
		$page_data['page_name']  = 'orders/ManageOrders';
		$page_data['page_title'] = 'Manage Orders';

		switch($type)
		{
			default : #Manage
				$totalResult = $this->orders_model->getManageOrders("","",$this->totalCount);
				$page_data["totalRows"] = $totalRows = count($totalResult['totalCount']);

				if(!empty($_SESSION['PAGE']))
				{$limit = $_SESSION['PAGE'];
				}else{$limit = 10;}
				
				if(!empty($_SESSION['PAGE']))
				{$limit = $_SESSION['PAGE'];
				}else{$limit = 10;}

				$order_number = isset($_GET['order_number']) ? $_GET['order_number'] : NULL;
				$mobile_number = isset($_GET['mobile_number']) ? $_GET['mobile_number'] : NULL;
				$payment_type_id = isset($_GET['payment_type_id']) ? $_GET['payment_type_id'] : NULL;
				$order_status = isset($_GET['order_status']) ? $_GET['order_status'] : NULL;
				$from_date = isset($_GET['from_date']) ? $_GET['from_date'] : NULL;
				$to_date = isset($_GET['from_date']) ? $_GET['to_date'] : NULL;

				$redirectURL = 'orders/manageOrders?order_number='.$order_number.'&mobile_number='.$mobile_number.'&payment_type_id='.$payment_type_id.'&order_status='.$order_status.'&from_date='.$from_date.'&to_date='.$to_date.'';
				
				if (!empty($_GET['order_number']) || !empty($_GET['mobile_number']) || !empty($_GET['payment_type_id']) || !empty($_GET['from_date']) || !empty($_GET['to_date']) || !empty($_GET['order_status'])) {
					$base_url = base_url('orders/manageOrders?order_number='.$_GET['order_number'].'&mobile_number='.$_GET['mobile_number'].'&payment_type_id='.$_GET['payment_type_id'].'&order_status='.$_GET['order_status'].'&from_date='.$_GET['from_date'].'&to_date='.$_GET['to_date'].'');
				} else {
					$base_url = base_url('orders/manageOrders?order_number=&mobile_number=&payment_type_id=&order_status=&from_date=&to_date=');
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
				
				$result = $this->orders_model->getManageOrders($limit,$offset,$this->pageCount);
				$page_data['resultData'] = $result["listing"];

				#show start and ending Count
				if(isset($_GET['per_page']) && $_GET['per_page'] > 1 && count($result["listing"]) == 0 )
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
				
				$total_counts = $total_count + count($result["listing"]);
				$page_data["ending"]  = $total_counts;
				#show start and ending Count end
			break;
		}	
		$this->load->view($this->adminTemplate, $page_data);
	}

	public function printReceipt($id="")
	{
		if (empty($this->user_id))
        {
			redirect(base_url() . 'admin/adminLogin', 'refresh');
		}
		
		$page_data['id'] = $id;

		$page_data['Orders'] = 1;
		$page_data['page_name']  = 'orders/printReceipt';
		$page_data['page_title'] = 'Orders Details';
		
		$page_data['data']  = $this->orders_model->getOrderDetails($id);
		$page_data['LineData'] = $this->orders_model->getOrderItemsPrint($id);
		$html = $this->load->view('backend/orders/printReceipt',$page_data,true);
		echo $html;exit;
	}

	public function readorderNotification($id="")
	{
		if (empty($this->user_id))
        {
			redirect(base_url() . 'admin/adminLogin', 'refresh');
		}
		$page_data['id'] = $id;
		$data['notification_read_status'] = 'Y';
		$this->db->where('header_id', $id);
		$this->db->update('ord_order_headers', $data);
		redirect(base_url() . 'orders/openOrders/', 'refresh');
	}
	
	function viewOderDetails($id="")
	{
		if (empty($this->user_id))
        {
			redirect(base_url() . 'admin/adminLogin', 'refresh');
		}
		
		$page_data['id'] = $id;
		$page_data['ManageOrders'] = 1;
    
		if(isset($_POST['delete']) && isset($_POST['checkbox']))
		{
			$cnt=array();
			$cnt=count($_POST['checkbox']);
			
			for($i=0;$i<$cnt;$i++)
			{
				$del_id=$_POST['checkbox'][$i];
				
				$lineData['cancel_status'] = 'Y';
				$lineData['cancelled_by'] = $this->user_id;
				$lineData['cancel_date'] = $this->date_time;
				$lineData['line_status'] = "Cancelled";

				$lineData['last_updated_by'] = $this->user_id;
				$lineData['last_updated_date'] = $this->date_time;

				$this->db->where('header_id', $id);
				$this->db->where('line_id', $del_id);
				$this->db->update('ord_order_lines', $lineData);

				$chkQry = "select line_id from ord_order_lines where cancel_status='N' and header_id ='".$id."' ";
				$chkHeader = $this->db->query($chkQry)->result_array();

				if(count($chkHeader) == 0)
				{
					$headerData["cancel_status"] = 'Y';
					$headerData["cancelled_by"] = $this->user_id;
					$headerData["cancel_date"] = $this->date_time;
					$headerData['order_status'] = "Cancelled";

					$headerData['last_updated_by'] = $this->user_id;
					$headerData['last_updated_date'] = $this->date_time;

					$this->db->where('header_id', $id);
					$this->db->update('ord_order_headers', $headerData);
				}
			}
						
			$this->session->set_flashdata('success_message' , "Ordered Item Cancelled Successfully!");
			redirect($_SERVER['HTTP_REFERER'], 'refresh');
		}

		if(isset($_POST['add_gst_btn']))
		{
			$updateData = array("attribute_1" => $_POST["gst_number"]);
			$this->db->where('header_id', $id);
			$this->db->update('ord_order_headers', $updateData);

			$this->session->set_flashdata('flash_message' , "Customer GST number saved successfully!");
			redirect($_SERVER["HTTP_REFERER"], 'refresh');
		}
	
		$page_data['Orders'] = 1;
		$page_data['page_name']  = 'orders/OderDetails';
		$page_data['page_title'] = 'Orders Details';
		
		$this->load->view($this->adminTemplate, $page_data);
    }

    function AjaxappendTable()
	{
		$result = $this->orders_model->getOrders("","",$this->totalCount);	
		$page_data['resultData'] = $result["listing"];

		$newOrderQry = "select header_id from ord_order_headers where order_status='Booked'";
		$getNewOrders = $this->db->query($newOrderQry)->result_array();

		$bookedCount = isset($result["bookedCount"]) ? $result["bookedCount"] : array();
		$confirmedCount = isset($result["confirmedCount"]) ? $result["confirmedCount"] : array();
		$preparingCount = isset($result["preparingCount"]) ? $result["preparingCount"] : array();
		$shippedCount = isset($result["shippedCount"]) ? $result["shippedCount"] : array();
		$deliveredCount = isset($result["deliveredCount"]) ? $result["deliveredCount"] : array();
		$totalOrdersCount = isset($result["totalOrdersCount"]) ? $result["totalOrdersCount"] : array();
	
		$htmlData["bookedCount"] = isset($bookedCount[0]["bookedCount"]) ? $bookedCount[0]["bookedCount"] : 0;
		$htmlData["confirmedCount"] = isset($confirmedCount[0]["confirmedCount"]) ? $confirmedCount[0]["confirmedCount"] : 0;
		$htmlData["preparingCount"] = isset($preparingCount[0]["preparingCount"]) ? $preparingCount[0]["preparingCount"] : 0;
		$htmlData["shippedCount"] = isset($shippedCount[0]["shippedCount"]) ? $shippedCount[0]["shippedCount"] : 0;
		$htmlData["deliveredCount"] = isset($deliveredCount[0]["deliveredCount"]) ? $deliveredCount[0]["deliveredCount"] : 0;
		$htmlData["totalOrdersCount"] = isset($totalOrdersCount[0]["totalOrdersCount"]) ? $totalOrdersCount[0]["totalOrdersCount"] : 0;
		$htmlData["newOrdersCount"] = count($getNewOrders);

		$htmlData["newOrders"] = $this->load->view('backend/orders/newOrdersAutoRefresh',$page_data,true);
		echo json_encode($htmlData);
		exit;
	}

	function checkNewOrders()
	{
		if($this->user_id==1) #Admin
		{
			#order_closed_status = 0-Not closd, 1-closed
			$condition = " 1=1 and heder_tbl.order_status='Booked'";
			$joinQuery ="";
		}
		else
		{
			$condition = " 1=1 and heder_tbl.order_status='Booked'" ;
			$joinQuery ="";
		}
		
		$query = "select heder_tbl.header_id from ord_order_headers as heder_tbl
		$joinQuery
		
		where $condition
		";
		$result = $this->db->query($query)->result_array();
		echo count($result);exit;
	}

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

	public function kotPrint($id="")
	{
		if (empty($this->user_id))
        {
			redirect(base_url() . 'admin/adminLogin', 'refresh');
		}
		
		$page_data['id'] = $id;
		$page_data['page_title'] = 'KOT Print';
		$page_data['data']  = $this->orders_model->getOrderDetails($id);
		$page_data['LineData'] = $this->orders_model->getKOTOrderItems($id);
		$html = $this->load->view('backend/orders/kotPrint',$page_data,true);
		echo $html;exit;
	}

	public function completekotPrint($id="")
	{
		if (empty($this->user_id))
        {
			redirect(base_url() . 'admin/adminLogin', 'refresh');
		}
		
		$page_data['id'] = $id;
		$page_data['page_title'] = 'KOT Print';
		$page_data['data']  = $this->orders_model->getOrderDetails($id);
		$page_data['LineData'] = $this->orders_model->getKOTCompletedOrderItems($id);
		$html = $this->load->view('backend/orders/kotPrint',$page_data,true);
		echo $html;exit;
	}

	public function updateConfirmStatus()
	{
		$header_id = $_POST["header_id"];
		$order_status =  $_POST["status"];
		$data['order_status'] = $order_status;
		$data['accepted_date'] = $this->date_time;
		$data['last_updated_by'] = $this->user_id;
		$data['last_updated_date'] = $this->date_time;
		
		$line_data['accepted_date'] = $this->date_time;
		$line_data['line_status'] = $order_status;
		$line_data['last_updated_by'] = $this->user_id;
		$line_data['last_updated_date'] = $this->date_time;
		
		$orderQuery = "select 
			ord_order_headers.customer_id, 
			ord_order_headers.order_number,							
			customer.mobile_number

		from ord_order_headers 
		
		left join cus_customers as customer on 
			customer.customer_id = ord_order_headers.customer_id
		
		where 
			ord_order_headers.header_id='".$header_id."' ";
		$getOrderDetails = $this->db->query($orderQuery)->result_array();

		$order_number = isset($getOrderDetails[0]['order_number']) ? $getOrderDetails[0]['order_number'] : NULL;
		$mobile_number = isset($getOrderDetails[0]['mobile_number']) ? $getOrderDetails[0]['mobile_number'] : NULL;
		$otpMobileNumber = $mobile_number;

		$this->db->where('header_id', $header_id);
		$this->db->update('ord_order_headers', $data);

		$this->db->where('header_id', $header_id);
		$this->db->where('cancel_status','N');
		$this->db->where('line_status!=','Cancelled');
		$this->db->update('ord_order_lines', $line_data);

		if($otpMobileNumber != NULL)
		{
			$otpMessage = '#'. $order_number.' Your order has been confirmed. - Thank You.  '.strtoupper(SITE_NAME);
			$sendSMS = sendSMS($otpMobileNumber,$otpMessage);
		}
		echo '1';exit;
	}

	function cancelOrderItems($order_id='')
	{
		$elements = $opts = isset($_POST['checkbox']) ? array_filter($_POST['checkbox']) : NULL;
		#$header_id = $opts = isset($_POST['order_id']) ? $_POST['order_id'] : NULL;
		$header_id = $order_id;

		if( (count($elements) > 0) && ($elements != NULL) )
		{
			#Header Print header Update Status start
			$printUpdateData = array(
				"print_status" 	      => 'N',
				"last_updated_by"     => $this->user_id,
				"last_updated_date"   => $this->date_time,
			);
			
			$this->db->where('header_id', $header_id);
			$this->db->update('ord_order_headers', $printUpdateData);
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
				$this->db->update('ord_order_lines', $lineCancelData);
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
			echo 1;exit;
		}
		else
		{
			echo 2;exit;
		}
	}


	function AjaxappendCashierTable()
	{
		$result = $this->orders_model->getOrders("","",$this->totalCount);	
		$page_data["getOnlineOrders"] = $page_data['resultData'] = $result["listing"];

		$newOrderQry = "select header_id from ord_order_headers where order_status='Booked'";
		$getNewOrders = $this->db->query($newOrderQry)->result_array();

		$bookedCount = isset($result["bookedCount"]) ? $result["bookedCount"] : array();
		$confirmedCount = isset($result["confirmedCount"]) ? $result["confirmedCount"] : array();
		$preparingCount = isset($result["preparingCount"]) ? $result["preparingCount"] : array();
		$shippedCount = isset($result["shippedCount"]) ? $result["shippedCount"] : array();
		$deliveredCount = isset($result["deliveredCount"]) ? $result["deliveredCount"] : array();
		$totalOrdersCount = isset($result["totalOrdersCount"]) ? $result["totalOrdersCount"] : array();
	
		$htmlData["bookedCount"] = isset($bookedCount[0]["bookedCount"]) ? $bookedCount[0]["bookedCount"] : 0;
		$htmlData["confirmedCount"] = isset($confirmedCount[0]["confirmedCount"]) ? $confirmedCount[0]["confirmedCount"] : 0;
		$htmlData["preparingCount"] = isset($preparingCount[0]["preparingCount"]) ? $preparingCount[0]["preparingCount"] : 0;
		$htmlData["shippedCount"] = isset($shippedCount[0]["shippedCount"]) ? $shippedCount[0]["shippedCount"] : 0;
		$htmlData["deliveredCount"] = isset($deliveredCount[0]["deliveredCount"]) ? $deliveredCount[0]["deliveredCount"] : 0;
		$htmlData["totalOrdersCount"] = isset($totalOrdersCount[0]["totalOrdersCount"]) ? $totalOrdersCount[0]["totalOrdersCount"] : 0;
		$htmlData["newOrdersCount"] = count($getNewOrders);

		//$htmlData["newOrders"] = $this->load->view('backend/orders/newOrdersAutoRefresh',$page_data,true);

		$htmlData["newOrders"] = $this->load->view('themes/default/web_fine_dine/ajaxPages/onlineOrders',$page_data,true);
		
		echo json_encode($htmlData);
		exit;
	}
	

	
}
?>

				
