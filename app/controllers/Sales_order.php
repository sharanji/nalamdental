<?php
defined('BASEPATH') OR exit('No direct script access allowed');
include_once('vendor/autoload.php');
class Sales_order extends CI_Controller 
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
	
	#SalesOrder
	function manageSalesOrder($type = '', $id = '', $status = '')
    {
		if (empty($this->user_id))
        {
			redirect(base_url() . 'admin/adminLogin', 'refresh');
		}
	
		$page_data['type'] = $type;
		$page_data['id'] = $id;
		
		$page_data['sales'] = $page_data['manageSalesOrder'] = 1;

		$page_data['page_name']  = 'sales_order/manageSalesOrder';
		$page_data['page_title'] = 'Material Issue';
		
		switch(true)
		{
			case ($type == "add"):
				if($_POST)
				{
					$organization_id=$this->input->post('organization_id');
					$branch_id=$this->input->post('branch_id');

					$headerData = array(
						"customer_id" 			 =>  $this->input->post('customer_id'),
						"so_currency" 	     	 =>  $this->input->post('so_currency'),
						"order_date" 	  		 =>  date('Y-m-d',strtotime($_POST["order_date"])),
						"customer_contact" 	  	 =>  $_POST["customer_contact"],
						"organization_id"		 =>  $organization_id,
						"branch_id"				 =>  $branch_id,
						"so_status" 	  		 =>  $_POST["header_status"],
						"customer_po" 	  		 =>  $_POST["customer_po"],
						"payment_term_id" 	  	 =>  $_POST["payment_term_id"],
						"bill_to_address" 	  	 =>  $_POST["bill_to_address"],
						"ship_to_address" 	  	 =>  $_POST["ship_to_address"],
						"description" 	  		 =>  $_POST["header_description"],
						"created_by" 	  		 =>  $this->user_id,
						"created_date" 	  		 =>  $this->date_time,
						"last_updated_by" 	  	 =>  $this->user_id,
						"last_updated_date" 	 =>  $this->date_time
					);

					#Audit Trails Start here
					$tableName = table_ord_sale_headers;
					$menuName = sales_order;
					$description = "Material Issue created successsfully!";
					auditTrails(array_filter($headerData),$tableName,$type,$menuName,"",$description);
					#Audit Trails end here


					$this->db->insert('ord_sale_headers', $headerData);
					$header_id = $this->db->insert_id();
					
					if($header_id)
					{
						#Document No Start here
						$documentQry = "select doc_num_id,prefix_name,suffix_name,next_number 
						from doc_document_numbering as dm
						left join sm_list_type_values ltv on 
							ltv.list_type_value_id = dm.doc_type
						where 
							ltv.list_code = 'SLE_ORD' 
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
							"order_number" 	  		 =>  $documentNumber,
							"last_updated_by" 	  	 =>  $this->user_id,
							"last_updated_date" 	 =>  $this->date_time
						);
						$this->db->where('sales_header_id', $header_id);
						$headerTbl1 = $this->db->update('ord_sale_headers',$updateDocNum);


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

						#Line Data start
						if(isset($_POST['transaction_id']))
						{
							$count = count(array_filter($_POST['transaction_id']));

							for($dp=0;$dp<$count;$dp++)
							{
								if(isset($_POST['delivery_date'][$dp]) && !empty($_POST['delivery_date'][$dp]))
								{
									$deliveryDate = date("Y-m-d",strtotime($_POST['delivery_date'][$dp]));
								}else{
									$deliveryDate = NULL;
								}

								$item_id = $_POST['text_product_id'][$dp];

								$itemQry = "select category_id from inv_sys_items where item_id='".$item_id."'";
								$getCatid = $this->db->query($itemQry)->result_array();

								$category_id = isset($getCatid[0]["category_id"]) ? $getCatid[0]["category_id"] : 0;

								$lineData = array(
									"sales_header_id" 		 =>  $header_id,
									"line_num" 			 	 =>  !empty($_POST['line_num'][$dp]) ? $_POST['line_num'][$dp] : NULL,
									"item_id" 			 	 =>  $item_id,
									"item_description" 		 =>  $_POST['description'][$dp],
									"category_id" 			 =>  $category_id,
									"customer_item" 		 =>  !empty($_POST['customer_item'][$dp]) ? $_POST['customer_item'][$dp] : NULL,
									"line_status" 		 	 =>  !empty($_POST['line_status'][$dp]) ? $_POST['line_status'][$dp] : NULL,
									"quantity" 				 =>  $_POST['quantity'][$dp],
									"uom" 				 	 =>  $_POST['uom'][$dp],
									"unit_price" 			 =>  !empty($_POST['unit_price'][$dp]) ? $_POST['unit_price'][$dp] : NULL,
									"tax" 			 		 =>  !empty($_POST['tax'][$dp]) ? $_POST['tax'][$dp] : NULL,
									"discount_type" 		 =>  !empty($_POST['discount_type'][$dp]) ? $_POST['discount_type'][$dp] : NULL,
									"discount" 		 		 =>  !empty($_POST['discount'][$dp]) ? $_POST['discount'][$dp] : NULL,
									"discount_reason" 		 =>  !empty($_POST['discount_reason'][$dp]) ? $_POST['discount_reason'][$dp] : NULL,
									"effective_price" 		 =>  !empty($_POST['price'][$dp]) ? $_POST['price'][$dp] : NULL,
									"line_value" 		 	 =>  !empty($_POST['line_value'][$dp]) ? $_POST['line_value'][$dp] : NULL,
									"total_tax" 		 	 =>  !empty($_POST['total_tax'][$dp]) ? $_POST['total_tax'][$dp] : NULL,
									"total" 		 	 	 =>  !empty($_POST['total'][$dp]) ? $_POST['total'][$dp] : NULL, 
									"delivery_date" 		 =>  $deliveryDate,
									
									// "organization_id" 		 =>  !empty($_POST['organization_id'][$dp]) ? $_POST['organization_id'][$dp] : NULL,
									"organization_id"		 =>  $organization_id,
									"branch_id"				 =>  $branch_id,
									"sub_inventory_id" 		 =>  !empty($_POST['sub_inventory_id'][$dp]) ? $_POST['sub_inventory_id'][$dp] : NULL,
									"locator_id" 		     =>  !empty($_POST['locator_id'][$dp]) ? $_POST['locator_id'][$dp] : NULL,
									"lot_number" 		     =>  !empty($_POST['lot_number'][$dp]) ? $_POST['lot_number'][$dp] : NULL,
									"serial_number" 		 =>  !empty($_POST['serial_number'][$dp]) ? $_POST['serial_number'][$dp] : NULL,
									"attribute1" 		 	 =>  !empty($_POST['transaction_id'][$dp]) ? $_POST['transaction_id'][$dp] : NULL,
									
									"created_by" 	  		 =>  $this->user_id,
									"created_date" 	  		 =>  $this->date_time,
									"last_updated_by" 	  	 =>  $this->user_id,
									"last_updated_date" 	 =>  $this->date_time
								);

								$this->db->insert('ord_sale_lines', $lineData);
								$line_id = $this->db->insert_id();

								#Insert Transaction data start here
								$transaction_qty = '-'.$_POST['quantity'][$dp];
								$invTrnData = array(
									"transaction_type" 	 	 =>  "ORD",
									"item_id" 			 	 =>  $item_id,
									// "organization_id" 		 =>  !empty($_POST['organization_id'][$dp]) ? $_POST['organization_id'][$dp] : NULL,
									"organization_id"		 =>  $organization_id,
									"branch_id"				 =>  $branch_id,
									"sub_inventory_id" 		 =>  !empty($_POST['sub_inventory_id'][$dp]) ? $_POST['sub_inventory_id'][$dp] : NULL,
									"locator_id" 		 	 =>  !empty($_POST['locator_id'][$dp]) ? $_POST['locator_id'][$dp] : NULL,
									"lot_number" 		 	 =>  !empty($_POST['lot_number'][$dp]) ? $_POST['lot_number'][$dp] : NULL,
									"serial_number" 		 =>  !empty($_POST['serial_number'][$dp]) ? $_POST['serial_number'][$dp] : NULL,
									"transaction_date" 	  	 =>  $this->date_time,
									"transaction_qty" 		 =>  $transaction_qty,
									"uom" 				 	 =>  $_POST['uom'][$dp],

									"order_header_id" 	     =>  $header_id,
									"order_line_id" 	 	 =>  $line_id,

									"created_by" 	  		 =>  $this->user_id,
									"created_date" 	  		 =>  $this->date_time,
									"last_updated_by" 	  	 =>  $this->user_id,
									"last_updated_date" 	 =>  $this->date_time
								);
								$this->db->insert('inv_transactions', $invTrnData);
								$trnsId = $this->db->insert_id();
								#Insert Transaction data end here
							}
						}
						#Line Data end
						
						if(isset($_POST["save_btn"]))
						{
							$this->session->set_flashdata('flash_message' , "Material Issue saved successfully!");
							// redirect(base_url() . 'sales_order/manageSalesOrder/edit/'.$header_id, 'refresh');
							redirect(base_url() . 'sales_order/manageSalesOrder/view/'.$header_id, 'refresh');
						}
						else if(isset($_POST["submit_btn"]))
						{
							$so_status = "Booked";

							#Header Tbl
							$headerUpdateData = array(
								"so_status" 	  	  =>  $so_status,
								"last_updated_by" 	  =>  $this->user_id,
								"last_updated_date"   =>  $this->date_time,
								"submission_date" 	  =>  $this->date_time,
							);
							$this->db->where('sales_header_id', $header_id);
							$headerTbl = $this->db->update('ord_sale_headers', $headerUpdateData);

							#Line Tbl
							$lineUpdateData = array(
								"line_status" 	  	  =>  $so_status,
								"last_updated_by" 	  =>  $this->user_id,
								"last_updated_date"   =>  $this->date_time
							);
							$this->db->where('sales_header_id', $header_id);
							$lineTbl = $this->db->update('ord_sale_lines',$lineUpdateData);

							$this->session->set_flashdata('flash_message' , "Material Issue submitted successfully!");
							redirect(base_url() . 'sales_order/manageSalesOrder/view/'.$header_id, 'refresh');
							// redirect(base_url() . 'sales_order/manageSalesOrder', 'refresh');
						}
					}
				}
			break;
			
			case ($type =="edit" || $type =="view"): #Edit / View
				$header_id = $id;
				
				$result = $this->sales_order_model->getViewData($id);
				$page_data['edit_data'] = $result['edit_data'];
				$page_data['line_data'] = $result['line_data'];

				if($_POST)
				{
					if(isset($_POST["submit_btn"]))
					{
						$count = isset($_POST['transaction_id']) ? count(array_filter($_POST['transaction_id'])) : 0;

						if($count == 0)
						{
							$this->session->set_flashdata('error_message' , "Atleast one item is required!");
							redirect($_SERVER["HTTP_REFERER"], 'refresh');
						}
					}

					$organization_id=$this->input->post('organization_id');
					$branch_id=$this->input->post('branch_id');

					$headerData = array(
						"customer_id" 			 =>  $this->input->post('customer_id'),
						"so_currency" 	     	 =>  $this->input->post('so_currency'),
						#"order_date" 	  		 =>  date('Y-m-d',strtotime($_POST["order_date"])),
						"customer_contact" 	  	 =>  $_POST["customer_contact"],
						"so_status" 	  		 =>  $_POST["header_status"],
						"customer_po" 	  		 =>  $_POST["customer_po"],
						"payment_term_id" 	  	 =>  $_POST["payment_term_id"],
						"organization_id"		 =>  $organization_id,
						"branch_id"				 =>  $branch_id,
						"bill_to_address" 	  	 =>  $_POST["bill_to_address"],
						"ship_to_address" 	  	 =>  $_POST["ship_to_address"],
						"description" 	  		 =>  $_POST["header_description"],
						"last_updated_by" 	  	 =>  $this->user_id,
						"last_updated_date" 	 =>  $this->date_time
					);

					#Audit Trails Edit Start here
					$tableName = table_ord_sale_headers;
					$menuName = sales_order;
					$description = "Material Issue updated successsfully!";
					auditTrails(array_filter($headerData),$tableName,$type,$menuName,$page_data['edit_data'],$description);
					#Audit Trails Edit end here
					
					
					$this->db->where('sales_header_id', $id);
					$result = $this->db->update('ord_sale_headers', $headerData);
					
					if($result)
					{
						#Line Data start
						if(isset($_POST['transaction_id']))
						{
							$count = count(array_filter($_POST['transaction_id']));

							for($dp=0;$dp<$count;$dp++)
							{
								$sales_line_id = $_POST['sales_line_id'][$dp];

								if($sales_line_id == 0) #Insert
								{
									if(isset($_POST['delivery_date'][$dp]) && !empty($_POST['delivery_date'][$dp]))
									{
										$deliveryDate = date("Y-m-d",strtotime($_POST['delivery_date'][$dp]));
									}else{
										$deliveryDate = NULL;
									}

									$item_id = $_POST['text_product_id'][$dp];

									$itemQry = "select category_id from inv_sys_items where item_id='".$item_id."'";
									$getCatid = $this->db->query($itemQry)->result_array();

									$category_id = isset($getCatid[0]["category_id"]) ? $getCatid[0]["category_id"] : 0;

									$lineData = array(
										"sales_header_id" 		 =>  $header_id,
										"line_num" 			 	 =>  !empty($_POST['line_num'][$dp]) ? $_POST['line_num'][$dp] : NULL,
										"item_id" 			 	 =>  $item_id,
										"item_description" 		 =>  $_POST['description'][$dp],
										"category_id" 			 =>  $category_id,
										"customer_item" 		 =>  !empty($_POST['customer_item'][$dp]) ? $_POST['customer_item'][$dp] : NULL,
										"line_status" 		 	 =>  !empty($_POST['line_status'][$dp]) ? $_POST['line_status'][$dp] : NULL,
										"quantity" 				 =>  $_POST['quantity'][$dp],
										"uom" 				 	 =>  $_POST['uom'][$dp],
										"unit_price" 			 =>  !empty($_POST['unit_price'][$dp]) ? $_POST['unit_price'][$dp] : NULL,
										"tax" 			 		 =>  !empty($_POST['tax'][$dp]) ? $_POST['tax'][$dp] : NULL,
										"discount_type" 		 =>  !empty($_POST['discount_type'][$dp]) ? $_POST['discount_type'][$dp] : NULL,
										"discount" 		 		 =>  !empty($_POST['discount'][$dp]) ? $_POST['discount'][$dp] : NULL,
										"discount_reason" 		 =>  !empty($_POST['discount_reason'][$dp]) ? $_POST['discount_reason'][$dp] : NULL,
										"effective_price" 		 =>  !empty($_POST['price'][$dp]) ? $_POST['price'][$dp] : NULL,
										"line_value" 		 	 =>  !empty($_POST['line_value'][$dp]) ? $_POST['line_value'][$dp] : NULL,
										"total_tax" 		 	 =>  !empty($_POST['total_tax'][$dp]) ? $_POST['total_tax'][$dp] : NULL,
										"total" 		 	 	 =>  !empty($_POST['total'][$dp]) ? $_POST['total'][$dp] : NULL, 
										"delivery_date" 		 =>  $deliveryDate,
										
										// "organization_id" 		 =>  !empty($_POST['organization_id'][$dp]) ? $_POST['organization_id'][$dp] : NULL,
										"organization_id"		 =>  $organization_id,
										"branch_id"				 =>  $branch_id,
										"sub_inventory_id" 		 =>  !empty($_POST['sub_inventory_id'][$dp]) ? $_POST['sub_inventory_id'][$dp] : NULL,
										"locator_id" 		     =>  !empty($_POST['locator_id'][$dp]) ? $_POST['locator_id'][$dp] : NULL,
										"lot_number" 		     =>  !empty($_POST['lot_number'][$dp]) ? $_POST['lot_number'][$dp] : NULL,
										"serial_number" 		 =>  !empty($_POST['serial_number'][$dp]) ? $_POST['serial_number'][$dp] : NULL,
										"attribute1" 		 	 =>  !empty($_POST['transaction_id'][$dp]) ? $_POST['transaction_id'][$dp] : NULL,
										
										"created_by" 	  		 =>  $this->user_id,
										"created_date" 	  		 =>  $this->date_time,
										"last_updated_by" 	  	 =>  $this->user_id,
										"last_updated_date" 	 =>  $this->date_time
									);

									$this->db->insert('ord_sale_lines', $lineData);
									$line_id = $this->db->insert_id();

									#Insert Transaction data start here
									$transaction_qty = '-'.$_POST['quantity'][$dp];
									$invTrnData = array(
										"transaction_type" 	 	 =>  "ORD",
										"item_id" 			 	 =>  $item_id,
										// "organization_id" 		 =>  !empty($_POST['organization_id'][$dp]) ? $_POST['organization_id'][$dp] : NULL,
										"organization_id"		 =>  $organization_id,
										"branch_id"				 =>  $branch_id,
										"sub_inventory_id" 		 =>  !empty($_POST['sub_inventory_id'][$dp]) ? $_POST['sub_inventory_id'][$dp] : NULL,
										"locator_id" 		 	 =>  !empty($_POST['locator_id'][$dp]) ? $_POST['locator_id'][$dp] : NULL,
										"lot_number" 		 	 =>  !empty($_POST['lot_number'][$dp]) ? $_POST['lot_number'][$dp] : NULL,
										"serial_number" 		 =>  !empty($_POST['serial_number'][$dp]) ? $_POST['serial_number'][$dp] : NULL,
										"transaction_date" 	  	 =>  $this->date_time,
										"transaction_qty" 		 =>  $transaction_qty,
										"uom" 				 	 =>  $_POST['uom'][$dp],

										"order_header_id" 	     =>  $header_id,
										"order_line_id" 	 	 =>  $line_id,

										"created_by" 	  		 =>  $this->user_id,
										"created_date" 	  		 =>  $this->date_time,
										"last_updated_by" 	  	 =>  $this->user_id,
										"last_updated_date" 	 =>  $this->date_time
									);
									$this->db->insert('inv_transactions', $invTrnData);
									$trnsId = $this->db->insert_id();
									#Insert Transaction data end here
								}
								else #Update
								{
									if(isset($_POST['delivery_date'][$dp]) && !empty($_POST['delivery_date'][$dp]))
									{
										$deliveryDate = date("Y-m-d",strtotime($_POST['delivery_date'][$dp]));
									}else{
										$deliveryDate = NULL;
									}

									$item_id = $_POST['text_product_id'][$dp];

									$itemQry = "select category_id from inv_sys_items where item_id='".$item_id."'";
									$getCatid = $this->db->query($itemQry)->result_array();

									$category_id = isset($getCatid[0]["category_id"]) ? $getCatid[0]["category_id"] : 0;


									#Insert Transaction data start here
									$receiptQry = "select transaction_id,transaction_qty from inv_transactions 
									where 1=1
									and transaction_type = 'ORD'
									and order_header_id = '".$id."'
									and order_line_id = '".$sales_line_id."'
									";
									$chkReceipt = $this->db->query($receiptQry)->result_array();
									
									if( count($chkReceipt) > 0 )
									{
										$saleLineQry = "select quantity from ord_sale_lines 
										where 1=1
										and sales_line_id = '".$sales_line_id."'
										";
										$saleLines = $this->db->query($saleLineQry)->result_array();

										$quantity = $saleLines[0]['quantity'];
										$enteredQty = $_POST['quantity'][$dp];
										$orderQty = isset($chkReceipt[0]['transaction_qty']) ? $chkReceipt[0]['transaction_qty'] : NULL;

										$transaction_qty = '-'.$enteredQty;
								

										$invTrnData = array(
											#"transaction_type" 	 	 =>  "ORD",
											"item_id" 			 	 =>  $item_id,
											// "organization_id" 		 =>  !empty($_POST['organization_id'][$dp]) ? $_POST['organization_id'][$dp] : NULL,
											"organization_id"		 =>  $organization_id,
											"branch_id"				 =>  $branch_id,
											"sub_inventory_id" 		 =>  !empty($_POST['sub_inventory_id'][$dp]) ? $_POST['sub_inventory_id'][$dp] : NULL,
											"locator_id" 		 	 =>  !empty($_POST['locator_id'][$dp]) ? $_POST['locator_id'][$dp] : NULL,
											"lot_number" 		 	 =>  !empty($_POST['lot_number'][$dp]) ? $_POST['lot_number'][$dp] : NULL,
											"serial_number" 		 =>  !empty($_POST['serial_number'][$dp]) ? $_POST['serial_number'][$dp] : NULL,
											"transaction_date" 	  	 =>  $this->date_time,
											"transaction_qty" 		 =>  $transaction_qty,
											"uom" 				 	 =>  $_POST['uom'][$dp],
											#"order_header_id" 	     =>  $header_id,
											#"order_line_id" 	 	 =>  $line_id,
											"last_updated_by" 	  	 =>  $this->user_id,
											"last_updated_date" 	 =>  $this->date_time
										);
										
										$this->db->where('transaction_type','ORD');
										$this->db->where('order_header_id', $id);
										$this->db->where('order_line_id', $sales_line_id);
										$lineTbl = $this->db->update('inv_transactions', $invTrnData);
									}
									else
									{
										$transaction_qty = '-'.$_POST['quantity'][$dp];

										#Insert Transaction data start here
										$invTrnData = array(
											"transaction_type" 	 	 =>  "ORD",
											"item_id" 			 	 =>  $item_id,
											// "organization_id" 		 =>  !empty($_POST['organization_id'][$dp]) ? $_POST['organization_id'][$dp] : NULL,
											"organization_id"		 =>  $organization_id,
											"branch_id"				 =>  $branch_id,
											"sub_inventory_id" 		 =>  !empty($_POST['sub_inventory_id'][$dp]) ? $_POST['sub_inventory_id'][$dp] : NULL,
											"locator_id" 		 	 =>  !empty($_POST['locator_id'][$dp]) ? $_POST['locator_id'][$dp] : NULL,
											"lot_number" 		 	 =>  !empty($_POST['lot_number'][$dp]) ? $_POST['lot_number'][$dp] : NULL,
											"serial_number" 		 =>  !empty($_POST['serial_number'][$dp]) ? $_POST['serial_number'][$dp] : NULL,
											"transaction_date" 	  	 =>  $this->date_time,
											"transaction_qty" 		 =>  $transaction_qty,
											"uom" 				 	 =>  $_POST['uom'][$dp],

											"order_header_id" 	     =>  $header_id,
											"order_line_id" 	 	 =>  $sales_line_id,

											"created_by" 	  		 =>  $this->user_id,
											"created_date" 	  		 =>  $this->date_time,
											"last_updated_by" 	  	 =>  $this->user_id,
											"last_updated_date" 	 =>  $this->date_time
										);
										$this->db->insert('inv_transactions', $invTrnData);
										$trnsId = $this->db->insert_id();
										#Insert Transaction data end here
									}
									#Insert Transaction data start here


									$lineData = array(
										"line_num" 			 	 =>  !empty($_POST['line_num'][$dp]) ? $_POST['line_num'][$dp] : NULL,
										"item_id" 			 	 =>  $item_id,
										"item_description" 		 =>  $_POST['description'][$dp],
										"category_id" 			 =>  $category_id,
										"customer_item" 		 =>  !empty($_POST['customer_item'][$dp]) ? $_POST['customer_item'][$dp] : NULL,
										"line_status" 		 	 =>  !empty($_POST['line_status'][$dp]) ? $_POST['line_status'][$dp] : NULL,
										"quantity" 				 =>  $_POST['quantity'][$dp],
										"uom" 				 	 =>  $_POST['uom'][$dp],
										"unit_price" 			 =>  !empty($_POST['unit_price'][$dp]) ? $_POST['unit_price'][$dp] : NULL,
										"tax" 			 		 =>  !empty($_POST['tax'][$dp]) ? $_POST['tax'][$dp] : NULL,
										"discount_type" 		 =>  !empty($_POST['discount_type'][$dp]) ? $_POST['discount_type'][$dp] : NULL,
										"discount" 		 		 =>  !empty($_POST['discount'][$dp]) ? $_POST['discount'][$dp] : NULL,
										"discount_reason" 		 =>  !empty($_POST['discount_reason'][$dp]) ? $_POST['discount_reason'][$dp] : NULL,
										"effective_price" 		 =>  !empty($_POST['price'][$dp]) ? $_POST['price'][$dp] : NULL,
										"line_value" 		 	 =>  !empty($_POST['line_value'][$dp]) ? $_POST['line_value'][$dp] : NULL,
										"total_tax" 		 	 =>  !empty($_POST['total_tax'][$dp]) ? $_POST['total_tax'][$dp] : NULL,
										"total" 		 	 	 =>  !empty($_POST['total'][$dp]) ? $_POST['total'][$dp] : NULL, 
										"delivery_date" 		 =>  $deliveryDate,
										
										// "organization_id" 		 =>  !empty($_POST['organization_id'][$dp]) ? $_POST['organization_id'][$dp] : NULL,
										"organization_id"		 =>  $organization_id,
										"branch_id"				 =>  $branch_id,
										"sub_inventory_id" 		 =>  !empty($_POST['sub_inventory_id'][$dp]) ? $_POST['sub_inventory_id'][$dp] : NULL,
										"locator_id" 		     =>  !empty($_POST['locator_id'][$dp]) ? $_POST['locator_id'][$dp] : NULL,
										"lot_number" 		     =>  !empty($_POST['lot_number'][$dp]) ? $_POST['lot_number'][$dp] : NULL,
										"serial_number" 		 =>  !empty($_POST['serial_number'][$dp]) ? $_POST['serial_number'][$dp] : NULL,
										"attribute1" 		 	 =>  !empty($_POST['transaction_id'][$dp]) ? $_POST['transaction_id'][$dp] : NULL,
										
										"last_updated_by" 	  	 =>  $this->user_id,
										"last_updated_date" 	 =>  $this->date_time
									);

									$this->db->where('sales_header_id', $id);
									$this->db->where('sales_line_id', $sales_line_id);
									$lineTbl = $this->db->update('ord_sale_lines', $lineData);

									
								}
								#Line Data end
							}
						}
						

						if(isset($_POST["save_btn"]))
						{
							$this->session->set_flashdata('flash_message' , "Sale order saved successfully!");
							redirect(base_url() . 'sales_order/manageSalesOrder/edit/'.$id, 'refresh');
						}
						else if(isset($_POST["submit_btn"]))
						{
							$so_status = "Booked";

							#Header Tbl
							$headerUpdateData = array(
								"so_status" 	  	  =>  $so_status,
								"last_updated_by" 	  =>  $this->user_id,
								"last_updated_date"   =>  $this->date_time,
								"submission_date" 	  =>  $this->date_time,
							);
							$this->db->where('sales_header_id', $header_id);
							$headerTbl = $this->db->update('ord_sale_headers', $headerUpdateData);

							#Line Tbl
							$lineUpdateData = array(
								"line_status" 	  	  =>  $so_status,
								"last_updated_by" 	  =>  $this->user_id,
								"last_updated_date"   =>  $this->date_time
							);
							$this->db->where('sales_header_id', $header_id);
							$lineTbl = $this->db->update('ord_sale_lines',$lineUpdateData);

							$this->session->set_flashdata('flash_message' , "Sale order submitted successfully!");
							redirect(base_url() . 'sales_order/manageSalesOrder', 'refresh');
						}	
					}
				}
			break;
			
			default : #Manage
				$totalResult = $this->sales_order_model->getManageSalesOrder("","",$this->totalCount);
				$page_data["totalRows"] = $totalRows = count($totalResult);

				if(!empty($_SESSION['PAGE']))
				{$limit = $_SESSION['PAGE'];
				}else{$limit = 10;}

				$order_number = isset($_GET['order_number']) ? $_GET['order_number'] :NULL;
				$so_status = isset($_GET['so_status']) ? $_GET['so_status'] :NULL;
				$customer_id = isset($_GET['customer_id']) ? $_GET['customer_id'] :NULL;
				// $organization_id = isset($_GET['organization_id']) ? $_GET['organization_id'] :NULL;
				$branch_id = isset($_GET['branch_id']) ? $_GET['branch_id'] :NULL;
				$from_date = isset($_GET['from_date']) ? $_GET['from_date'] :NULL;
				$to_date = isset($_GET['to_date']) ? $_GET['to_date'] :NULL;

				$redirectURL = 'sales_order/manageSalesOrder?order_number='.$order_number.'&so_status='.$so_status.'&customer_id='.$customer_id.'&branch_id='.$branch_id.'&from_date='.$from_date.'&to_date='.$to_date;
				
				if ($order_number != NULL || $so_status != NULL || $customer_id != NULL || $branch_id !=NULL || $from_date != NULL || $to_date != NULL) {
					$base_url = base_url('sales_order/manageSalesOrder?order_number='.$order_number.'&so_status='.$so_status.'&customer_id='.$customer_id.'&branch_id='.$branch_id.'&from_date='.$from_date.'&to_date='.$to_date.'');
				} else {
					$base_url = base_url('sales_order/manageSalesOrder?order_number=&so_status=&customer_id=&branch_id=&from_date=&to_date=');
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
				
				$page_data['resultData']  = $result = $this->sales_order_model->getManageSalesOrder($limit, $offset, $this->pageCount);
				
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
	

	public function getSOLineDatas()
	{
		$organization_id 	= isset($_GET['organization_id']) ? $_GET['organization_id'] : 'null';
		$branch_id			= isset($_GET['branch_id']) ? $_GET['branch_id'] : 'null'; 

		
		$itemQuery = "select
			transaction.transaction_id,
			sum(transaction.transaction_qty) as trans_qty,
			item.item_id,
			transaction.organization_id,
			transaction.branch_id,
			transaction.sub_inventory_id,
			transaction.locator_id,
			transaction.lot_number,
			transaction.serial_number,
			item.item_name,
			item.item_description,
			category.category_name,
			sub_inventory.inventory_code,
			sub_inventory.inventory_name,
			item_locators.locator_no,
			item_locators.locator_name

			from inv_transactions as transaction
			left join inv_sys_items as item on item.item_id = transaction.item_id
			left join inv_categories as category on category.category_id = item.category_id
			left join inv_item_sub_inventory as sub_inventory on sub_inventory.inventory_id = transaction.sub_inventory_id
			left join inv_item_locators as item_locators on item_locators.locator_id = transaction.locator_id
			where 1=1
			and transaction.organization_id='".$organization_id."'
			and transaction.branch_id='".$branch_id."'

			group by 
			transaction.item_id,
			transaction.organization_id,
			transaction.sub_inventory_id,
			transaction.locator_id,
			transaction.lot_number,
			transaction.serial_number
			
			HAVING trans_qty > 0
			";


		$data['items'] = $this->db->query($itemQuery)->result_array();
		$data['discount'] = $this->db->query("select discount_id,discount_name from discount where active_flag='Y'")->result();
		
		$taxQry = "select tax_id,tax_name,tax_value from gen_tax 
			where active_flag='Y'
			and coalesce(start_date,'".$this->date."') <= '".$this->date."'
			and coalesce(end_date,'".$this->date."') >= '".$this->date."'
			";
		$data['tax'] = $this->db->query($taxQry)->result_array();
		
		$uomQry = "select uom_id,uom_code,uom_description from uom 
			where active_flag='Y'
			and coalesce(start_date,'".$this->date."') <= '".$this->date."'
			and coalesce(end_date,'".$this->date."') >= '".$this->date."'
			";
		$data['uom'] = $this->db->query($uomQry)->result_array();

		$discountType = [];

		foreach( $this->discount_type as $key => $value )
		{
			$discountType[] = array(
				'discount_type' =>  $value,
			);
		}
		$data['discount_type'] = $discountType;

		$organizationQry = "select organization_id,organization_name from org_organizations 
			where active_flag='Y'
			and coalesce(start_date,'".$this->date."') <= '".$this->date."'
			and coalesce(end_date,'".$this->date."') >= '".$this->date."'
			";
		$data['organization'] = $this->db->query($organizationQry)->result_array();
		
		$requestedByQry = "select person_id,first_name,last_name from per_people_all 
			where active_flag='Y'
			";
		$data['requestedBy'] = $this->db->query($requestedByQry)->result_array();

		$subInvQry = "select inventory_id,inventory_code,inventory_name from inv_item_sub_inventory 
			where active_flag='Y'
			and coalesce(start_date,'".$this->date."') <= '".$this->date."'
			and coalesce(end_date,'".$this->date."') >= '".$this->date."'
			";
		$data['subInvQry'] = $this->db->query($subInvQry)->result_array();


	    echo json_encode($data);
		exit;
	}

	public function getLineItems()
	{
		$transaction_id = isset($_POST["transaction_id"]) ? $_POST["transaction_id"] : NULL;
		$item_id = isset($_POST["item_id"]) ? $_POST["item_id"] : NULL;

		if($item_id !=NULL)
		{
			$itemQuery = "select
			transaction.transaction_id,
			sum(transaction.transaction_qty) as trans_qty,
			item.item_id,
			transaction.organization_id,
			transaction.sub_inventory_id,
			transaction.locator_id,
			transaction.uom,
			
			item.item_name,
			item.item_description,
			category.category_name,

			organization.organization_name,
			sub_inventory.inventory_code,
			sub_inventory.inventory_name,
			item_locators.locator_no,
			item_locators.locator_name,

			transaction.lot_number,
			transaction.serial_number
			
			from inv_transactions as transaction
			left join inv_sys_items as item on item.item_id = transaction.item_id
			left join inv_categories as category on category.category_id = item.category_id
			left join org_organizations as organization on organization.organization_id = transaction.organization_id
			left join inv_item_sub_inventory as sub_inventory on sub_inventory.inventory_id = transaction.sub_inventory_id
			left join inv_item_locators as item_locators on item_locators.locator_id = transaction.locator_id
			where 
				transaction.item_id = '".$item_id."'
				
				group by 
					transaction.item_id,
					transaction.organization_id,
					transaction.sub_inventory_id,
					transaction.locator_id,
					transaction.lot_number,
					transaction.serial_number
					
					HAVING transaction.transaction_id = '".$transaction_id."'
					";
			
			$data = $this->db->query($itemQuery)->result_array();
			echo json_encode($data);
		}exit;
	}

	public function getTransactionDetails()
	{
		$transaction_id = isset($_POST["transaction_id"]) ? $_POST["transaction_id"] : NULL;
		$item_id = isset($_POST["item_id"]) ? $_POST["item_id"] : NULL;

		if($transaction_id !=NULL)
		{
			$itemQuery = "select
			transaction.transaction_id,
			item.item_id,
			transaction.organization_id,
			transaction.sub_inventory_id,
			transaction.locator_id,
			transaction.uom,
			
			item.item_name,
			item.item_description,
			category.category_name,

			organization.organization_name,
			sub_inventory.inventory_code,
			sub_inventory.inventory_name,
			item_locators.locator_no,
			item_locators.locator_name,

			transaction.lot_number,
			transaction.serial_number
			
			from inv_transactions as transaction
			left join inv_sys_items as item on item.item_id = transaction.item_id
			left join inv_categories as category on category.category_id = item.category_id
			left join org_organizations as organization on organization.organization_id = transaction.organization_id
			left join inv_item_sub_inventory as sub_inventory on sub_inventory.inventory_id = transaction.sub_inventory_id
			left join inv_item_locators as item_locators on item_locators.locator_id = transaction.locator_id
			where 
			transaction.transaction_id = '".$transaction_id."' ";
			
			$data = $this->db->query($itemQuery)->result_array();
			echo json_encode($data);
		}exit;
	}

	public function deleteLineItems()
	{
		$sales_line_id = isset($_POST["sales_line_id"]) ? $_POST["sales_line_id"] : NULL;

		$this->db->where('sales_line_id', $sales_line_id);
		$this->db->delete('ord_sale_lines');

		$this->db->where('transaction_type', 'ORD');
		$this->db->where('order_line_id', $sales_line_id);
		$this->db->delete('inv_transactions');
		echo 1;exit;
	}
	
	function generatePDF($id="")
    {
		$page_data['id'] = $id;

		$result = $this->sales_order_model->getViewData($id);
		$page_data['edit_data'] = $result['edit_data'];
		$page_data['line_data'] = $result['line_data'];


		$date = date('d-M-Y');
		ob_start();
		$html = ob_get_clean();
		$html = utf8_encode($html);
		
		$pdf_name = "sales_order_".$date;
		$mpdf = new \Mpdf\Mpdf([
			'setAutoTopMargin' => 'stretch'
		]);

		$mpdf->SetHTMLHeader($this->load->view('backend/sales_order/so_pdf_header',$page_data,true));
		$html = $this->load->view('backend/sales_order/so_pdf_content',$page_data,true);
		$mpdf->SetHTMLFooter($this->load->view('backend/sales_order/so_pdf_footer',$page_data,true));
		
        $mpdf->AddPage('L','','','','',7,7,7,7,7,7);
		$mpdf->WriteHTML($html);
		$mpdf->Output($pdf_name.'.pdf','I');
	}


	#Receipt Lines
	/* public function getReceiptLines($po_header_id="")
	{
		$organizationQry = "select organization_id,organization_name from org_organizations 
			where active_flag='Y'
			and coalesce(start_date,'".$this->date."') <= '".$this->date."'
			and coalesce(end_date,'".$this->date."') >= '".$this->date."'
			";
		$data['organization'] = $this->db->query($organizationQry)->result_array();

		$subInvQry = "select inventory_id,inventory_code,inventory_name from inv_item_sub_inventory 
			where active_flag='Y'
			and coalesce(start_date,'".$this->date."') <= '".$this->date."'
			and coalesce(end_date,'".$this->date."') >= '".$this->date."'
			";
		$data['subInvQry'] = $this->db->query($subInvQry)->result_array();

		$query = "
			select 
				sp.po_header_id,
				sp.po_line_id,
				sp.item_id,
				sp.category_id,
				sp.uom,
				sp.item_name,
				sp.item_description,
				sp.po_qty,
				sp.po_bal_qty,
				sp.supplier_item,
				sp.note_to_receiver,
				sp.organization_id,
				sp.po_requested_by,
				sp.category_name,
				sp.person_id,
				sp.first_name,
				sp.last_name,
				sp.uom_code, 
                 sp.received_qty,
                        sp.rcv_supplier_item,
                        sp.rcv_organization_id,
                        sp.rcv_sub_inventory_id,
                        sp.rcv_locator_id,
                        sp.rcv_lot_number,
                        sp.rcv_serial_number
                from 
				(
					select 
						line_tbl.po_header_id,
						line_tbl.po_line_id,
						line_tbl.item_id,
						line_tbl.category_id,
						line_tbl.uom,
						items.item_name,
						line_tbl.item_description,
						line_tbl.quantity as po_qty,
						line_tbl.quantity - sum(coalesce(rcv_line_tbl.received_qty,0)) as po_bal_qty,
						line_tbl.supplier_item,
						line_tbl.note_to_receiver,
						line_tbl.organization_id,
						line_tbl.requested_by as po_requested_by,
						categories.category_name,
						per_people_all.person_id,
						per_people_all.first_name,
						per_people_all.last_name,
						uom.uom_code,
                        rcv_line_tbl.received_qty,
                        rcv_line_tbl.supplier_item as rcv_supplier_item,
                        rcv_line_tbl.organization_id as rcv_organization_id,
                        rcv_line_tbl.sub_inventory_id as rcv_sub_inventory_id,
                        rcv_line_tbl.locator_id as rcv_locator_id,
                        rcv_line_tbl.lot_number as rcv_lot_number,
                        rcv_line_tbl.serial_number as rcv_serial_number
						from po_lines as line_tbl
							left join po_headers as header_tbl on header_tbl.po_header_id = line_tbl.po_header_id
							left join inv_sys_items as items on items.item_id = line_tbl.item_id
							left join inv_categories as categories on categories.category_id = line_tbl.category_id
							left join uom on uom.uom_id = line_tbl.uom
							left join per_people_all on per_people_all.person_id = line_tbl.requested_by
							left join rcv_receipt_lines as rcv_line_tbl on rcv_line_tbl.po_line_id = line_tbl.po_line_id
					where 
						line_tbl.po_header_id= '".$po_header_id."'
						group by line_tbl.po_line_id
				) sp
			where sp.po_bal_qty > 0
		";

		$data['items'] = $this->db->query($query)->result();
	    echo json_encode($data);exit;
	} */

	#Ajax Select Sub Inv
	public function selectSubInventory() 
	{
        $organization_id = $_POST["organization_id"];	

		if($organization_id)
		{			
			$subInvQry = "select inventory_id,inventory_code,inventory_name from inv_item_sub_inventory 
			where active_flag='Y'
			and coalesce(start_date,'".$this->date."') <= '".$this->date."'
			and coalesce(end_date,'".$this->date."') >= '".$this->date."'
			and organization_id ='".$organization_id."'
			";
			$data = $this->db->query($subInvQry)->result_array();
		
			if( count($data) > 0)
			{
				echo '<option value="">- Select -</option>';
				
				foreach($data as $val)
				{
					echo '<option value="'.$val['inventory_id'].'">'.ucfirst($val['inventory_code']).'</option>';
				}
			}
			else
			{
				echo '<option value="">No sub inventory under this organization!</option>';
			}
		}
		die;
    }

	#Ajax Select Sub Inv Locators
	public function selectSubInventoryLocators() 
	{
        $inventory_id = $_POST["inventory_id"];	

		if($inventory_id)
		{			
			$subInvQry = "select locator_id,locator_no,locator_name from inv_item_locators 
			where active_flag='Y'
			and coalesce(start_date,'".$this->date."') <= '".$this->date."'
			and coalesce(end_date,'".$this->date."') >= '".$this->date."'
			and inventory_id ='".$inventory_id."'
			";
			$data = $this->db->query($subInvQry)->result_array();
		
			if( count($data) > 0)
			{
				echo '<option value="">- Select -</option>';
				
				foreach($data as $val)
				{
					echo '<option value="'.$val['locator_id'].'">'.ucfirst($val['locator_no']).'</option>';
				}
			}
			else
			{
				echo '<option value="">No locators under this sub inventory!</option>';
			}
		}
		die;
    }

	#Ajax Select Customer Site
	public function getAjaxCustomerDetails() 
	{
        $id = $_POST["id"];	
		if($id)
		{			
			$data =  $this->db->query("select cus_customer.contact_person from cus_customers as cus_customer
					where cus_customer.customer_id='".$id."'
					")->result_array();
		
			echo isset($data[0]["contact_person"]) ? $data[0]["contact_person"] : NULL;
		}
		die;
    }

	public function getAjaxBillAndShiptoAddress() 
	{
        $id = $_POST["id"];	
        $site_type = $_POST["site_type"];	
		if($id && $site_type)
		{			
			$data =  $this->db->query("select cus_sites.customer_site_id, cus_sites.site_name from cus_customer_sites as cus_sites
					where 
					cus_sites.customer_id='".$id."' 
					and cus_sites.active_flag='Y' 
					and cus_sites.site_type='".$site_type."' 
					order by cus_sites.site_name asc
					")->result_array();
		
			if( count($data) > 0)
			{
				echo '<option value="">- Select -</option>';
				
				foreach($data as $val)
				{
					echo '<option value="'.$val['customer_site_id'].'">'.ucfirst($val['site_name']).'</option>';
				}
			}
			else
			{
				echo '<option value="">- Select -</option>';
			}
		}
		die;
    }

	public function getAjaxCompleteAddress() 
	{
        $id = $_POST["id"];	
        $site_type = $_POST["site_type"];	

		if($id && $site_type)
		{	
			$qry = "select 
			cus_site.address1,
			cus_site.address2,
			cus_site.address3,
			city.city_name,
			state.state_name,
			country.country_name,
			cus_site.postal_code
			from cus_customer_sites as cus_site
			left join geo_countries as country on country.country_id = cus_site.country_id
			left join geo_states as state on state.state_id = cus_site.state_id
			left join geo_cities as city on city.city_id = cus_site.city_id
			where 
				cus_site.site_type='".$site_type."' 
				and cus_site.customer_site_id='".$id."'";


			$siteData =  $this->db->query($qry)->result_array();

			$address1 = !empty($siteData[0]["address1"]) ? $siteData[0]["address1"].", " :NULL;
			$address2 = !empty($siteData[0]["address2"]) ? $siteData[0]["address2"].", " :NULL;
			$address3 = !empty($siteData[0]["address3"]) ? $siteData[0]["address3"].", " :NULL;
			$city_name = !empty($siteData[0]["city_name"]) ? $siteData[0]["city_name"].", " :NULL;
			$state_name = !empty($siteData[0]["state_name"]) ? $siteData[0]["state_name"].", " :NULL;
			$country_name = !empty($siteData[0]["country_name"]) ? $siteData[0]["country_name"].", " :NULL;
			$postal_code = !empty($siteData[0]["postal_code"]) ? $siteData[0]["postal_code"]."." :NULL;
			
			$completeAddress = $address1.$address2.$address3.$city_name.$state_name.$country_name.$postal_code;
			
			echo $completeAddress;

		}
		die;
    }

	public function ajaxSelectItemUom() 
	{
        $id = $_POST["id"];
        $item_id = $_POST["item_id"];	

		if($id)
		{	
			$uomQry = "select uom from inv_transactions
			
			where transaction_id ='".$id."' ";

			$transUom =  $this->db->query($uomQry)->result_array();

			$uom_id = isset($transUom[0]["uom"]) ? $transUom[0]["uom"] : 0;

			$uomQry = "select uom.uom_id,uom.uom_code,inv_transactions.transaction_id from uom
			left join inv_transactions on inv_transactions.uom = uom.uom_id
			where uom.active_flag='Y' group by uom_id";

			$data =  $this->db->query($uomQry)->result_array();
		
			if( count($data) > 0)
			{
				echo '<option value="">- Select -</option>';
				
				foreach($data as $val)
				{
					$selected="";
					
					if($uom_id == $val['uom_id'])
					{
						$selected="selected='selected'";
					}
					echo '<option value="'.$val['uom_id'].'" '.$selected.'>'.ucfirst($val['uom_code']).'</option>';
				}
			}
			else
			{
				echo '<option value="">No uom under this item!</option>';
			}
		}
		die;
    }

	function generateSalesOrdersPDF($button_type="",$id="")
    {
		$page_data['id'] = $header_id = $id;
		$result = $this->sales_order_model->getViewData($id);#Header Qry
		$page_data['data'] = $result['edit_data'];
		$page_data['LineData'] = $result['line_data'];

		#Unlink PDF Start	
		if(file_exists("uploads/sales_order_pdf/".$header_id.".pdf"))
		{
			unlink("uploads/sales_order_pdf/".$header_id.".pdf");
		}
		
		$LineData = $page_data['line_data'] = $this->sales_order_model->getViewData($id);
		
		if( count($LineData) > 0 )
		{
			ob_start();

			#Print Receipt HTML Start
			$html = ob_get_clean();
			$html = utf8_encode($html);
			
			$html = $this->load->view('backend/sales_order/saleskotPrint',$page_data,true);
		
			$mpdf = new \Mpdf\Mpdf([
				'curlAllowUnsafeSslRequests' => true,
			]);

			$mpdf->WriteHTML($html);
			$mpdf->Output('uploads/sales_order_pdf/'.$id.'.pdf', 'F');
			#Print Receipt HTML End
		}
	}

	function chkbill($order_id="")
    {
		
		#KOT Print
		if(file_exists("uploads/sales_order_pdf/".$order_id.".pdf"))
		{
			$salesKOTPath = base_url()."uploads/sales_order_pdf/".$order_id.".pdf";
		}
		else
		{
			$salesKOTPath ='';
		}

		if(isset($this->branch_id) && $this->branch_id > 0){
			$branch_id = $this->branch_id;
		}else{
			$branch_id = 1;
		}


		$branchPrintersQuery = "select header_id,branch_id from org_print_count_header 
		where 
		branch_id='".$branch_id."' AND 
		active_flag = 'Y' ";
		$getBranchPrinters = $this->db->query($branchPrintersQuery)->result_array();

		$header_id = isset($getBranchPrinters[0]["header_id"]) ? $getBranchPrinters[0]["header_id"] : 0;

		$printerLineQry = "select 
		org_print_section_types.print_type,
		org_print_section_types.type_name,
		org_print_count_line.printer_name,
		org_print_count_line.printer_count
		
		from org_print_count_line

		left join org_print_count_header on 
		org_print_count_header.header_id = org_print_count_line.header_id

		left join org_print_section_types on 
		org_print_section_types.type_id = org_print_count_line.type_id

		where 1=1
		and org_print_count_line.header_id='".$header_id."'  
		and org_print_count_line.branch_id='".$branch_id."' 
		and org_print_count_line.active_flag = 'Y' 
		and org_print_count_header.active_flag = 'Y' 
		and org_print_section_types.active_flag = 'Y'
		and org_print_section_types.print_type = 'STORE_KOT'
		";
		$getPrinterLine = $this->db->query($printerLineQry)->result_array();

		$data['print_items'] = $getPrinterLine;

		$data['salesKOTPath'] = $salesKOTPath;
		
		echo json_encode($data);exit;
	}
}
?>
