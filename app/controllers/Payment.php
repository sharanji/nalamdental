<?php
defined('BASEPATH') OR exit('No direct script access allowed');
include_once('vendor/autoload.php');
class Payment extends CI_Controller 
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
	
	function manageCustomerPayment($type = '', $id = '', $status = '')
    {
		if (empty($this->user_id))
        {
			redirect(base_url() . 'admin/adminLogin', 'refresh');
		}
	
		$page_data['type'] = $type;
		$page_data['id'] = $id;
		
		$page_data['ManageCustomerPayment'] = 1;

		$page_data['page_name']  = 'payment/customer/manageCustomerPayment';
		$page_data['page_title'] = 'Customer Payment';
		
		switch($type)
		{
			case "add": #Add
				if($_POST)
				{
					$payment_date = strtotime($this->input->post('payment_date'));

					$getDocumentData = $this->common_model->documentNumber("CUSTOMER-PAYMENT");
						
					
					$prefixName = isset($getDocumentData[0]['prefix_name']) ? $getDocumentData[0]['prefix_name'] : NULL;
					$startingNumber = isset($getDocumentData[0]['next_number']) ? $getDocumentData[0]['next_number'] : NULL;
					$suffixName = isset($getDocumentData[0]['suffix_name']) ? $getDocumentData[0]['suffix_name'] : NULL;
					$documentNumber = $prefixName.''.$startingNumber.''.$suffixName;

					$invoice_source = $this->input->post('invoice_source');
					


					if($invoice_source == "PARTY-ORDERS")
					{
						$payment_amount = isset($_POST['payment_amount']) ? count(array_filter($_POST['payment_amount'])) : 0;
					
						if($payment_amount == 0)
						{
							$this->session->set_flashdata('error_message' , "Atleast 1 payment is required!");
							redirect($_SERVER["HTTP_REFERER"], 'refresh');
						}
						
						$customer_id = $_POST["customer_id"];
						$order_customer_id = NULL;
					}
					else if($invoice_source == "ONLINE-ORDERS")
					{
						$payment_amount = isset($_POST['payment_amount_ord']) ? count(array_filter($_POST['payment_amount_ord'])) : 0;
					
						if($payment_amount == 0)
						{
							$this->session->set_flashdata('error_message' , "Atleast 1 payment is required!");
							redirect($_SERVER["HTTP_REFERER"], 'refresh');
						}

						$customer_id = NULL;
						$order_customer_id = $_POST["order_customer_id"]; 
					}
					else
					{
						$customer_id = NULL;
						$order_customer_id = NULL;
					}

					$postData=array(
						'payment_number'	=> $documentNumber,

						'customer_id'		=> $customer_id,
						'consumer_id'		=> $order_customer_id,

						'payment_method'	=> $this->input->post('payment_method'),
						'description'		=> $this->input->post('description'),
						'invoice_source'	=> $invoice_source,
						'payment_date'		=> date("Y-m-d",$payment_date),
						'reference_id' 		=> !empty($this->input->post('reference_id')) ? $this->input->post('reference_id') : NULL,
						'check_name' 		=> !empty($this->input->post('check_name')) ? $this->input->post('check_name') : NULL,
						'created_by'		=> $this->user_id,
						'created_date'		=> $this->date_time,
						'last_updated_by'	=> $this->user_id,
						'last_updated_date'	=> $this->date_time,
					);
					
					#Audit Trails Start here
					$tableName = table_inv_invoice_payment_header;
					$menuName = customer_payment;
					$description = "Customer payment created successsfully!";
					auditTrails(array_filter($postData),$tableName,$type,$menuName,"",$description);
					#Audit Trails end here

					$this->db->insert('inv_invoice_payment_header', $postData);
					$id = $this->db->insert_id();
					
					if($id !="")
					{
						#Update Next Val DOC Number tbl start
						$str_len = strlen($startingNumber);
						$nextValue1 = $startingNumber + 1;
						$nextValue = str_pad($nextValue1,$str_len,"0",STR_PAD_LEFT);

						$doc_num_id = isset($getDocumentData[0]['doc_num_id']) ? $getDocumentData[0]['doc_num_id']:"";
						
						$UpdateDat['next_number'] = $nextValue;
						$this->db->where('doc_num_id', $doc_num_id);
						$resultUpdateData = $this->db->update('doc_document_numbering', $UpdateDat);
						#Update Next Val DOC Number tbl end

						if (isset($postData['payment_method']) && $postData['payment_method'] == 7) #checque
						{
							if( !empty($_FILES['cheque_photo']['name']) )
							{  
								$data_1['cheque_photo'] = $chequeName = $_FILES['cheque_photo']['name'];
								move_uploaded_file($_FILES['cheque_photo']['tmp_name'], 'uploads/checque/'.$chequeName);
								$data_1['check_name'] = isset($_POST['check_name']) ? $_POST['check_name'] : "";
								$this->db->where('header_id', $id);
								$result = $this->db->update('inv_invoice_payment_header', $data_1);
							}
						}
						
						if($invoice_source == "PARTY-ORDERS") #Party Order Line
						{
							#Add and Remove multiple payment start here
							$payment_amount = isset($_POST['payment_amount']) ? count(array_filter($_POST['payment_amount'])) : 0;
							
							if( isset($_POST['payment_amount']) && $payment_amount > 0 )
							{
								$count = count($_POST['payment_amount']);

								for($dp=0;$dp<$count;$dp++)
								{
									if( isset($_POST['payment_amount'][$dp])  && !empty($_POST['payment_amount'][$dp]) )
									{
										$invoice_id = isset($_POST['invoice_id'][$dp]) ? $_POST['invoice_id'][$dp] : NULL;
										$payment_amount = isset($_POST['payment_amount'][$dp]) ? $_POST['payment_amount'][$dp] : NULL;
										$inv_total = isset($_POST['inv_total'][$dp]) ? $_POST['inv_total'][$dp] : NULL;

										$LineData = array(
											"header_id"        	=>  $id,
											"invoice_id"       	=>  $invoice_id,
											"payment_amount"   	=>  $payment_amount,
											
											"created_by"   	    =>  $this->user_id,
											"created_date"   	=>  $this->date_time,
											"last_updated_by"   =>  $this->user_id,
											"last_updated_date" =>  $this->date_time,
										);

										$this->db->insert('inv_invoice_payment_line', $LineData);
										$lineID = $this->db->insert_id();

										#Update Payment Status start here
										$paymentQry = "select 
											sum(line_tbl.payment_amount) as payment_amount
										from inv_invoice_payment_line as line_tbl
										left join inv_invoice_payment_header as header_tbl on header_tbl.header_id = line_tbl.header_id

										where 1=1
										and header_tbl.invoice_source = '".$invoice_source."' 
										and line_tbl.invoice_id = '".$invoice_id."' 
										";

										$getEqualAmount = $this->db->query($paymentQry)->result_array();

										if( count($getEqualAmount) > 0 )
										{
											$TotalAmount = $inv_total;

											$payment_amount = isset($getEqualAmount[0]['payment_amount']) ? $getEqualAmount[0]['payment_amount'] : 0;
											
											if($payment_amount >= $TotalAmount)
											{
												$UpdateData['invoice_status'] = 'PAID'; #Fully Paid
												$this->db->where('header_id', $invoice_id);
												$resultUpdateData = $this->db->update('inv_invoice_headers', $UpdateData);
											}
											else
											{
												$UpdateData['invoice_status'] = 'PENDING'; #Partial Paid
												$this->db->where('header_id', $invoice_id);
												$resultUpdateData = $this->db->update('inv_invoice_headers', $UpdateData);
											}
										}
										#Update Payment Status end here	
									}
								}
								
								if(isset($_POST["save_btn"]))
								{
									$this->session->set_flashdata('flash_message' , "Customer payment saved successfully!");
									redirect(base_url() . 'payment/viewCustomerPayment/'.$id, 'refresh');
								}
								else if(isset($_POST["submit_btn"]))
								{
									$this->session->set_flashdata('flash_message' , "Customer payment submitted successfully!");
									redirect(base_url() . 'payment/manageCustomerPayment', 'refresh');
								}
							}
							#Add and Remove multiple payment end here
						}
						else if($invoice_source == "ONLINE-ORDERS")#Online Order Line
						{
							#Add and Remove multiple payment start here
							$payment_amount = isset($_POST['payment_amount_ord']) ? count(array_filter($_POST['payment_amount_ord'])) : 0;
							
							if( isset($_POST['payment_amount_ord']) && $payment_amount > 0 )
							{
								$count = count($_POST['payment_amount_ord']);

								for($dp=0;$dp<$count;$dp++)
								{
									if( isset($_POST['payment_amount_ord'][$dp])  && !empty($_POST['payment_amount_ord'][$dp]) )
									{
										$invoice_id = isset($_POST['invoice_id'][$dp]) ? $_POST['invoice_id'][$dp] : NULL;
										$payment_amount = isset($_POST['payment_amount_ord'][$dp]) ? $_POST['payment_amount_ord'][$dp] : NULL;
										$inv_total = isset($_POST['inv_total'][$dp]) ? $_POST['inv_total'][$dp] : NULL;

										$LineData = array(
											"header_id"        	=>  $id,
											"invoice_id"       	=>  $invoice_id,
											"payment_amount"   	=>  $payment_amount,
											
											"created_by"   	    =>  $this->user_id,
											"created_date"   	=>  $this->date_time,
											"last_updated_by"   =>  $this->user_id,
											"last_updated_date" =>  $this->date_time,
										);

										$this->db->insert('inv_invoice_payment_line', $LineData);
										$lineID = $this->db->insert_id();

										#Update Payment Status start here
										$paymentQry = "select 
										sum(line_tbl.payment_amount) as payment_amount
										from inv_invoice_payment_line as line_tbl
										left join inv_invoice_payment_header as header_tbl on header_tbl.header_id = line_tbl.header_id
										where 1=1
										and header_tbl.invoice_source = '".$invoice_source."' 
										and line_tbl.invoice_id = '".$invoice_id."' 
										";

										$getEqualAmount = $this->db->query($paymentQry)->result_array();

										if( count($getEqualAmount) > 0 )
										{
											$TotalAmount = $inv_total;

											$payment_amount = isset($getEqualAmount[0]['payment_amount']) ? $getEqualAmount[0]['payment_amount'] : 0;
											
											if($payment_amount >= $TotalAmount)
											{
												$UpdateData['payment_due'] = 'Paid'; #Fully Paid
												$this->db->where('header_id', $invoice_id);
												$resultUpdateData = $this->db->update('ord_order_headers', $UpdateData);
											}
											else
											{
												$UpdateData['payment_due'] = 'Unpaid'; #Partial Paid
												$this->db->where('header_id', $invoice_id);
												$resultUpdateData = $this->db->update('ord_order_headers', $UpdateData);
											}
										}
										#Update Payment Status end here	
									}
								}
								
								if(isset($_POST["save_btn"]))
								{
									$this->session->set_flashdata('flash_message' , "Online order payment saved successfully!");
									redirect(base_url() . 'payment/viewCustomerPayment/'.$id, 'refresh');
								}
								else if(isset($_POST["submit_btn"]))
								{
									$this->session->set_flashdata('flash_message' , "Online order payment submitted successfully!");
									redirect(base_url() . 'payment/manageCustomerPayment', 'refresh');
								}
							}
							#Add and Remove multiple payment end here
						}	
					}
				}
			break;

			default : #Manage
				
				$totalResult = $this->payment_model->getManageCustomerPayment("","",$this->totalCount);
				$page_data["totalRows"] = $totalRows = count($totalResult);
	
				if(!empty($_SESSION['PAGE']))
				{$limit = $_SESSION['PAGE'];
				}else{$limit = 10;}
				
				$invoice_source = isset($_GET['invoice_source']) ? $_GET['invoice_source'] :"";
				$payment_id = isset($_GET['payment_id']) ? $_GET['payment_id'] :"";
				$customer_name = isset($_GET['customer_name']) ? $_GET['customer_name'] :"";

				$from_date = isset($_GET['from_date']) ? $_GET['from_date'] :"";
				$to_date = isset($_GET['to_date']) ? $_GET['to_date'] :"";
				
				$this->redirectURL = 'payment/manageCustomerPayment?invoice_source='.$invoice_source.'&payment_id='.$payment_id.'&customer_name='.$customer_name.'&from_date='.$from_date.'&to_date='.$to_date;
				
				if ($invoice_source != NULL || $payment_id != NULL || $customer_name != NULL || $from_date != NULL || $to_date != NULL ) {
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
				
				$page_data['resultData'] = $result = $this->payment_model->getManageCustomerPayment($limit, $offset, $this->pageCount);
				
				if(isset($_GET['per_page']) && $_GET['per_page'] > 1 && count($result) == 0 )
				{
					redirect(base_url().$this->redirectURL, 'refresh');
				}

				#Download Excel start
				$download_excel = isset($_GET['download_excel']) ? $_GET['download_excel']: NULL;
				if($download_excel != NULL) 
				{
							
					$date = date('d_M_Y');
					header("Content-type: application/csv");
					header("Content-Disposition: attachment; filename=\"customer_payment_".$date.".csv\"");
					header("Pragma: no-cache");
					header("Expires: 0");

					$handle = fopen('php://output', 'w');
					$handle1 = fopen('php://output', 'w');
					fputcsv($handle, array("S.No","Invoice Source","Payment No","Customer Name","Collection Mode","Payment Date","Amount"));
					$cnt=1;
					foreach ($result as $row) 
					{
						if($row["invoice_source"] == "PARTY-ORDERS") 
						{
							$customerName = $row['customer_name'];
						}
						else if($row["invoice_source"] == "ONLINE-ORDERS") 
						{
							$customerName = $row['con_customer_name'];
						}
						
						$narray=array(
							$cnt,
							$row['invoice_source_name'],
							$row['payment_number'],
							$customerName,
							$row['payment_type'],
							date(DATE_FORMAT,strtotime($row['payment_date'])),
							number_format($row['amount'],DECIMAL_VALUE,'.','')
						);

						fputcsv($handle, $narray);
						$cnt++;
					}
					
					fclose($handle);
					exit;
				}
				#Download Excel end 
				
				#show star2023-02-1t and ending Count
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
	
	public function selectCustomerSales($customer_id="")
	{
		if($customer_id)
		{
			
			$result = $this->payment_model->getAjaxCustomerPaymentList($customer_id);
			
			$data['salesList'] = $result;

			echo json_encode($data);
		}
		die;
	}

	public function selectSupplierSales($supplier_id="")
	{
		if($supplier_id)
		{
			
			$result = $this->payment_model->getAjaxSupplierPaymentList($supplier_id);
			
			$data['salesList'] = $result;

			echo json_encode($data);
		}
		die;
	}

	public function viewCustomerPayment($id="")
	{
		if (empty($this->user_id))
        {
			redirect(base_url() . 'admin/adminLogin', 'refresh');
		}
		$page_data['ManageReceivable'] = 1;
		$page_data['id'] = $id;
		$page_data['page_title'] = 'Customer Payment';

		$page_data['page_name']  = 'payment/customer/viewCustomerPayment';

		$this->load->view($this->adminTemplate, $page_data);
	}

	function customerPaymentPDF($header_id="",$status="")
    {
		$page_data['id'] = $id = $header_id;
		$page_data['status'] = $status = $status;
		
		$date = date('d-M-Y');

		ob_start();
		$html = ob_get_clean();
		$html = utf8_encode($html);
		
		$html = $this->load->view('backend/payment/customer/customerPaymentPDF',$page_data,true);
		 
		$pdf_name = $date;
		$mpdf = new \Mpdf\Mpdf();
        $mpdf->AddPage('P','','','','',10,10,10,10);
		$mpdf->WriteHTML($html);
		$mpdf->Output($pdf_name.'.pdf','I');	
	}

	public function viewSupplierPayment($id="")
	{
		if (empty($this->user_id))
        {
			redirect(base_url() . 'admin/adminLogin', 'refresh');
		}
		$page_data['ManageReceivable'] = 1;
		$page_data['id'] = $id;
		$page_data['page_title'] = 'Supplier Payment';

		$page_data['page_name']  = 'payment/supplier/viewSupplierPayment';

		$this->load->view($this->adminTemplate, $page_data);
	}

	function supplierPaymentPDF($header_id="",$status="")
    {
		$page_data['id'] = $id = $header_id;
		$page_data['status'] = $status = $status;
		
		$date = date('d-M-Y');

		ob_start();
		$html = ob_get_clean();
		$html = utf8_encode($html);
		
		$html = $this->load->view('backend/payment/supplier/supplierPaymentPDF',$page_data,true);
		 
		$pdf_name = $date;
		$mpdf = new \Mpdf\Mpdf();
        $mpdf->AddPage('P','','','','',10,10,10,10);
		$mpdf->WriteHTML($html);
		$mpdf->Output($pdf_name.'.pdf','I');
	}

	function manageSupplierPayment($type = '', $id = '', $status = '')
    {
		if (empty($this->user_id))
        {
			redirect(base_url() . 'admin/adminLogin', 'refresh');
		}
	
		$page_data['type'] = $type;
		$page_data['id'] = $id;
		
		$page_data['ManageSupplierPayment'] = 1;

		$page_data['page_name']  = 'payment/supplier/manageSupplierPayment';
		$page_data['page_title'] = 'Supplier Payment';
		
		switch($type)
		{
			case "add": #Add
				if($_POST)
				{
					$payment_amount = isset($_POST['payment_amount']) ? count(array_filter($_POST['payment_amount'])) : 0;
					
					if($payment_amount == 0)
					{
						$this->session->set_flashdata('error_message' , "Atleast 1 payment is required!");
						redirect($_SERVER["HTTP_REFERER"], 'refresh');
					}

					$payment_date = strtotime($this->input->post('payment_date'));

					$getDocumentData = $this->common_model->documentNumber("SUPPLIER-PAYMENT");
						
					
					$prefixName = isset($getDocumentData[0]['prefix_name']) ? $getDocumentData[0]['prefix_name'] : NULL;
					$startingNumber = isset($getDocumentData[0]['next_number']) ? $getDocumentData[0]['next_number'] : NULL;
					$suffixName = isset($getDocumentData[0]['suffix_name']) ? $getDocumentData[0]['suffix_name'] : NULL;
					$documentNumber = $prefixName.''.$startingNumber.''.$suffixName;

					$postData=array(
						'payment_number'	=> $documentNumber,
						'supplier_id'		=> $this->input->post('supplier_id'),
						'payment_method'	=> $this->input->post('payment_method'),
						'supplier_site_id'	=> $this->input->post('supplier_site_id'),
						'description'		=> $this->input->post('description'),
						'payment_date'		=> date("Y-m-d",$payment_date),
						'reference_id' 		=> !empty($this->input->post('reference_id')) ? $this->input->post('reference_id') : NULL,
						'check_name' 		=> !empty($this->input->post('check_name')) ? $this->input->post('check_name') : NULL,
						'created_by'		=> $this->user_id,
						'created_date'		=> $this->date_time,
						'last_updated_by'	=> $this->user_id,
						'last_updated_date'	=> $this->date_time,
					);
					
					$tableName = table_inv_supplier_payment_header;
					$menuName = supplier_payment;
					$description = "Supplier payment created successsfully!";
					auditTrails(array_filter($postData),$tableName,$type,$menuName,"",$description);
					#Audit Trails end here

					$this->db->insert('inv_supplier_payment_header', $postData);
					$id = $this->db->insert_id();
					
					if($id !="")
					{
						#Update Next Val DOC Number tbl start
						$str_len = strlen($startingNumber);
						$nextValue1 = $startingNumber + 1;
						$nextValue = str_pad($nextValue1,$str_len,"0",STR_PAD_LEFT);

						$doc_num_id = isset($getDocumentData[0]['doc_num_id']) ? $getDocumentData[0]['doc_num_id']:"";
						
						$UpdateDat['next_number'] = $nextValue;
						$this->db->where('doc_num_id', $doc_num_id);
						$resultUpdateData = $this->db->update('doc_document_numbering', $UpdateDat);
						#Update Next Val DOC Number tbl end

						if (isset($postData['payment_method']) && $postData['payment_method'] == 7) #checque
						{
							if( !empty($_FILES['cheque_photo']['name']) )
							{  
								$data_1['cheque_photo'] = $chequeName = $_FILES['cheque_photo']['name'];
								move_uploaded_file($_FILES['cheque_photo']['tmp_name'], 'uploads/checque/'.$chequeName);
								$data_1['check_name'] = isset($_POST['check_name']) ? $_POST['check_name'] : "";
								$this->db->where('header_id', $id);
								$result = $this->db->update('inv_supplier_payment_header', $data_1);
							}
						}
						
						#Add and Remove multiple payment start here
						$payment_amount = isset($_POST['payment_amount']) ? count(array_filter($_POST['payment_amount'])) : 0;
						
						if( isset($_POST['payment_amount']) && $payment_amount > 0 )
						{
							$count = count($_POST['payment_amount']);

							for($dp=0;$dp<$count;$dp++)
							{
								if( isset($_POST['payment_amount'][$dp])  && !empty($_POST['payment_amount'][$dp]) )
								{
									$receipt_id = isset($_POST['receipt_id'][$dp]) ? $_POST['receipt_id'][$dp] : NULL;
									$payment_amount = isset($_POST['payment_amount'][$dp]) ? $_POST['payment_amount'][$dp] : NULL;
									$inv_total = isset($_POST['inv_total'][$dp]) ? $_POST['inv_total'][$dp] : NULL;

									$LineData = array(
										"header_id"        	=>  $id,
										"receipt_id"       	=>  $receipt_id,
										"payment_amount"   	=>  $payment_amount,
										"created_by"   	    =>  $this->user_id,
										"created_date"   	=>  $this->date_time,
										"last_updated_by"   =>  $this->user_id,
										"last_updated_date" =>  $this->date_time,
									);

									$this->db->insert('inv_supplier_payment_line', $LineData);
									$lineID = $this->db->insert_id();

									#Update Payment Status start here
									$paymentQry = "select 
										sum(line_tbl.payment_amount) as payment_amount
									from inv_supplier_payment_line as line_tbl
									where line_tbl.receipt_id = '".$receipt_id."' ";

									$getEqualAmount = $this->db->query($paymentQry)->result_array();

									if( count($getEqualAmount) > 0 )
									{
										$TotalAmount = $inv_total;

										$payment_amount = isset($getEqualAmount[0]['payment_amount']) ? $getEqualAmount[0]['payment_amount'] : 0;

										if($payment_amount >= $TotalAmount)
										{
											$UpdateData['receipt_status'] = 'PAID'; #Fully Paid
											$this->db->where('receipt_header_id', $receipt_id);
											$resultUpdateData = $this->db->update('rcv_receipt_headers', $UpdateData);
										}
										else
										{
											$UpdateData['receipt_status'] = 'PENDING'; #Partial Paid
											$this->db->where('receipt_header_id', $receipt_id);
											$resultUpdateData = $this->db->update('rcv_receipt_headers', $UpdateData);
										}
									}
									#Update Payment Status end here	
								}
							}
							
							if(isset($_POST["save_btn"]))
							{
								$this->session->set_flashdata('flash_message' , "Supplier payment saved successfully!");
								redirect(base_url() . 'payment/viewSupplierPayment/'.$id, 'refresh');
							}
							else if(isset($_POST["submit_btn"]))
							{
								$this->session->set_flashdata('flash_message' , "Supplier payment submitted successfully!");
								redirect(base_url() . 'payment/manageSupplierPayment', 'refresh');
							}
						}
						#Add and Remove multiple payment end here
					}
				}
			break;

			default : #Manage
				
				$totalResult = $this->payment_model->getManageSupplierPaymentResult("","",$this->totalCount);
				$page_data["totalRows"] = $totalRows = count($totalResult);
	
				if(!empty($_SESSION['PAGE']))
				{$limit = $_SESSION['PAGE'];
				}else{$limit = 10;}
				
				$payment_id 	= isset($_GET['payment_id']) ? $_GET['payment_id'] :"";
				$supplier_id 	= isset($_GET['supplier_id']) ? $_GET['supplier_id'] :"";
				$payment_method = isset($_GET['payment_method']) ? $_GET['payment_method'] :"";
				$from_date 		= isset($_GET['from_date']) ? $_GET['from_date'] :"";
				$to_date 		= isset($_GET['to_date']) ? $_GET['to_date'] :"";
				
				$this->redirectURL = 'payment/manageSupplierPayment?payment_id='.$payment_id.'&supplier_id='.$supplier_id.'&payment_method='.$payment_method.'&from_date='.$from_date.'&to_date='.$to_date;
				
				
				if ($from_date != NULL || $to_date != NULL ) {
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
				
				$page_data['resultData']  = $result = $data = $this->payment_model->getManageSupplierPaymentResult($limit, $offset, $this->pageCount);
				
				if(isset($_GET['per_page']) && $_GET['per_page'] > 1 && count($result) == 0 )
				{
					redirect(base_url().$this->redirectURL, 'refresh');
				}


				#Download Excel start
				$download_excel = isset($_GET['download_excel']) ? $_GET['download_excel']: NULL;
				if($download_excel != NULL) 
				{
							
					$date = date('d_M_Y');
					header("Content-type: application/csv");
					header("Content-Disposition: attachment; filename=\"customer_payment_".$date.".csv\"");
					header("Pragma: no-cache");
					header("Expires: 0");

					$handle = fopen('php://output', 'w');
					$handle1 = fopen('php://output', 'w');
					fputcsv($handle, array("S.No","PO Number","Receipt Number","Payment Number","Supplier Name","Collection Mode","Payment Date","Amount"));
					$cnt=1;
					foreach ($result as $row) 
					{
						
						$narray=array(
							$cnt,
							$row['po_number'],
							$row['receipt_number'],
							$row['payment_number'],
							$row['supplier_name'],
							$row['payment_type'],
							date(DATE_FORMAT,strtotime($row['payment_date'])),
							number_format($row['amount'],DECIMAL_VALUE,'.','')
						);

						fputcsv($handle, $narray);
						$cnt++;
					}
					
					fclose($handle);
					exit;
				}
				#Download Excel end 
				
				#show star2023-02-1t and ending Count
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

	public function selectSuppplierSales($supplier_id="") 
	{
		if (isset($this->user_id) && $this->user_id == '')
            redirect(base_url() . 'admin/login', 'refresh');
			
		if($supplier_id)
		{	
			$condition = "(COALESCE(receipt_header.paid_status,0) = 0 || receipt_header.paid_status = 1) and purchases.supplier_id='".$supplier_id."' "; #0=>Pending, 1=> Partial Payment

			$data = $this->db->query("select 
				receipt_header.header_id,
				receipt_header.receipt_number,
				COALESCE(receipt_header.paid_amount,0) as paid_amount,
				COALESCE(receipt_header.balance_amount,0) as balance_amount,
				COALESCE(receipt_header.paid_status,0) as paid_status,

				receipt_header.receipt_date as date,
				receipt_header.amount as total,
				purchases.reference_no as po_reference_no,
				purchases.supplier_id
				from po_receipt_header as receipt_header
			
				left join purchases on 
					purchases.purchase_id = receipt_header.purchase_id

				where $condition 

			")->result_array();
			
			$data['salesList'] = $data;

			echo json_encode($data);
		}
		die;
    }

	public function ajaxSelectBankBalance($account_id = '') 
	{
		if($_POST["id"])
		{	
			$account_id = $_POST["id"];

			$condition = "acc_bank_details.account_id='".$account_id."' ";
			
			$data = $this->db->query("select bank_balance from acc_bank_details where $condition ")->result_array();
			
			$bank_balance = isset($data[0]["bank_balance"]) ? $data[0]["bank_balance"] : 0;

			echo number_format($bank_balance,DECIMAL_VALUE,'.','');
		}
		die;
    }

	function ajaxPaymentList() 
	{
		if(isset($_POST["query"]))  
		{  
			$output = '';  
			
			$payment_number = $_POST['query'];

			$result = $this->payment_model->getAjaxPaymentAll($payment_number);
			
			$output = '<ul class="list-unstyled-payment_id">';  
			
			if( count($result) > 0 )  
			{  
				foreach($result as $row)  
				{	
					$payment_number = $row["payment_number"];
					$payment_id = $row["header_id"];
					$output .= '<a><li onclick="return getPaymentList(\'' .$payment_id. '\',\'' .$payment_number. '\');">'.$payment_number.'</li></a>';  
				}  
			}  
			else  
			{  
				$payment_number = "";
				$payment_id = "";
				
				$output .= '<li onclick="return getPaymentList(\'' .$payment_id. '\',\'' .$payment_number. '\');">Sorry! Payment Number Not Found.</li>';  
			}
			$output .= '</ul>';  
			echo $output;  
		}
	}

	function ajaxSupplierPaymentList() 
	{
		if(isset($_POST["query"]))  
		{  
			$output = '';  
			
			$payment_number = $_POST['query'];

			$result = $this->payment_model->getAjaxSupplierPaymentAll($payment_number);
			
			$output = '<ul class="list-unstyled-payment_id">';  
			
			if( count($result) > 0 )  
			{  
				foreach($result as $row)  
				{	
					$payment_number = $row["payment_number"];
					$payment_id = $row["header_id"];
					$output .= '<a><li onclick="return getPaymentList(\'' .$payment_id. '\',\'' .$payment_number. '\');">'.$payment_number.'</li></a>';  
				}  
			}  
			else  
			{  
				$payment_number = "";
				$payment_id = "";
				
				$output .= '<li onclick="return getPaymentList(\'' .$payment_id. '\',\'' .$payment_number. '\');">Sorry! Payment Number Not Found.</li>';  
			}
			$output .= '</ul>';  
			echo $output;  
		}
	}

	# Ajax  Change
	public function loadAgainstOrderCus() 
	{    			
		$data =  $this->payment_model->loadAgainstOrderCus();

		if( count($data) > 0)
		{
			echo '<option value="">- Select -</option>';

			foreach($data as $val)
			{
				if($val['mobile_number'] != NULL && $val['customer_name'] != NULL)
				{
					$customer_name = $val['mobile_number'].'-'.$val['customer_name'];
				}
				else {
					$customer_name = $val['mobile_number'];
				}

				echo '<option value="'.$val['customer_id'].'">'.$customer_name.'</option>';
			}
		}
		else
		{
			echo '<option value="">- Select -</option>';
		}
		
		die;
    }

	# Ajax  Change
	public function loadAgainstInvoiceCus() 
	{    			
		$data =  $this->invoice_model->getAjaxInvoiceCustomers();

		if( count($data) > 0)
		{
			echo '<option value="">- Select -</option>';

			foreach($data as $val)
			{
				echo '<option value="'.$val['customer_id'].'">'.$val['customer_name'].'</option>';
			}
		}
		else
		{
			echo '<option value="">- Select -</option>';
		}
		
		die;
    }

	public function selectCustomerOnlineSales($customer_id="",$from_date="",$to_date="")
	{
		if($customer_id && $from_date && $to_date)
		{
			$result = $this->payment_model->getAjaxCustomerPaymentDueList($customer_id,$from_date,$to_date); #Online Order #POS, Take away, Home Delivery & Dine-In
			
			$data['salesList'] = $result;

			echo json_encode($data);
		}
		die;
	}
}
?>
