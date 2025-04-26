<?php
defined('BASEPATH') OR exit('No direct script access allowed');
include_once('vendor/autoload.php');
class Quick_receipt extends CI_Controller 
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
	
	function manageReceipt($type = '', $id = '', $status = '')
    {
		if (empty($this->user_id))
        {
			redirect(base_url() . 'admin/adminLogin', 'refresh');
		}
	
		$page_data['type'] = $type;
		$page_data['id'] = $id;
		
		$page_data['purchase'] = $page_data['manageReceipt'] = 1;
		$page_data['page_name']  = 'quick_receipt/manageReceipt';
		$page_data['page_title'] = 'Quick Receipt';
		
		switch(true)
		{
			case ($type == "add"):
				if($_POST)
				{
					$organization_id=$this->input->post('organization_id');
					$branch_id=$this->input->post('branch_id');
					$headerData = array(
						"supplier_id" 			 =>  $this->input->post('supplier_id'),
						"supplier_site_id" 		 =>  $this->input->post('supplier_site_id'),
						"po_currency" 	     	 =>  $this->input->post('po_currency'),
						"receipt_date" 	  		 =>  date('Y-m-d',strtotime($_POST["receipt_date"])),
						"receipt_status" 	  	 =>  $_POST["receipt_status"],
						"buyer_id" 	  		     =>  $_POST["buyer_id"],
						"organization_id"		 =>  $organization_id,
						"branch_id"				 =>  $branch_id,
						"note_to_receiver" 	  	 =>  $_POST["header_note_to_receiver"],
						"description" 	  		 =>  $_POST["header_description"],
						"bill_number" 	  		 =>  $_POST["bill_number"],
						"receipt_type" 	  		 =>  "QIK_REC",
						"created_by" 	  		 =>  $this->user_id,
						"created_date" 	  		 =>  $this->date_time,
						"last_updated_by" 	  	 =>  $this->user_id,
						"last_updated_date" 	 =>  $this->date_time
					);
					#Audit Trails Start here
					$tableName = table_rcv_receipt_headers;
					$menuName = quick_receipt;
					$description = "Receipt created successsfully!";
					auditTrails(array_filter($headerData),$tableName,$type,$menuName,"",$description);
					#Audit Trails end here
					
					$this->db->insert('rcv_receipt_headers', $headerData);
					$header_id = $this->db->insert_id();
					
					if($header_id)
					{
						#Document No Start here
						$documentQry = "select doc_num_id,prefix_name,suffix_name,next_number 
						from doc_document_numbering as dm
						left join sm_list_type_values ltv on 
							ltv.list_type_value_id = dm.doc_type
						where 
							ltv.list_code = 'QIK_REC' 
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
							"receipt_number" 	  	 =>  $documentNumber,
							"last_updated_by" 	  	 =>  $this->user_id,
							"last_updated_date" 	 =>  $this->date_time
						);
						$this->db->where('receipt_header_id', $header_id);
						$headerTbl1 = $this->db->update('rcv_receipt_headers', $updateDocNum);


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
						if(isset($_POST['item_id']))
						{
							$count = count(array_filter($_POST['item_id']));
							for($dp=0;$dp<$count;$dp++)
							{
								$item_id = $_POST['item_id'][$dp];

								$itemQry = "select category_id from inv_sys_items where item_id='".$item_id."'";
								$getCatid = $this->db->query($itemQry)->result_array();

								$category_id = isset($getCatid[0]["category_id"]) ? $getCatid[0]["category_id"] : 0;

								if(!empty($_POST['quantity'][$dp]))
								{
									$receivedQty = $_POST['quantity'][$dp];
								}else{
									$receivedQty = NULL;
								}

								$lineData = array(
									"receipt_header_id" 	 =>  $header_id,
									"line_num" 			 	 =>  !empty($_POST['line_num'][$dp]) ? $_POST['line_num'][$dp] : NULL,
									"item_id" 			 	 =>  $_POST['item_id'][$dp],
									"item_description" 		 =>  $_POST['description'][$dp],
									"category_id" 			 =>  $category_id,
									"received_qty" 			 =>  $receivedQty,
									"uom" 				 	 =>  $_POST['uom'][$dp],
									"base_price" 			 =>  $_POST['base_price'][$dp],
									"tax" 			 		 =>  !empty($_POST['tax'][$dp]) ? $_POST['tax'][$dp] : NULL,
									"discount_type" 		 =>  !empty($_POST['discount_type'][$dp]) ? $_POST['discount_type'][$dp] : NULL,
									"discount" 		 		 =>  !empty($_POST['discount'][$dp]) ? $_POST['discount'][$dp] : NULL,
									"discount_reason" 		 =>  !empty($_POST['discount_reason'][$dp]) ? $_POST['discount_reason'][$dp] : NULL,
									"price" 		 		 =>  !empty($_POST['price'][$dp]) ? $_POST['price'][$dp] : NULL,
									"line_value" 		 	 =>  !empty($_POST['line_value'][$dp]) ? $_POST['line_value'][$dp] : NULL,
									"total_tax" 		 	 =>  !empty($_POST['total_tax'][$dp]) ? $_POST['total_tax'][$dp] : NULL,
									"total" 		 	 	 =>  !empty($_POST['total'][$dp]) ? $_POST['total'][$dp] : NULL, 
									"organization_id"		 =>  $organization_id,
									"branch_id"				 =>  $branch_id,
									"requested_by" 			 =>  !empty($_POST['requested_by'][$dp]) ? $_POST['requested_by'][$dp] : NULL,
									"note_to_receiver" 		 =>  !empty($_POST['note_to_receiver'][$dp]) ? $_POST['note_to_receiver'][$dp] : NULL,
									"created_by" 	  		 =>  $this->user_id,
									"created_date" 	  		 =>  $this->date_time,
									"last_updated_by" 	  	 =>  $this->user_id,
									"last_updated_date" 	 =>  $this->date_time
								);

								$this->db->insert('rcv_receipt_lines', $lineData);
								$line_id = $this->db->insert_id();

								#Insert Transaction data start here
								$invTrnData = array(
									"transaction_type" 	 	 =>  "QIK_REC",
									"item_id" 			 	 =>  $_POST['item_id'][$dp],
									"organization_id"		 =>  $organization_id,
									"branch_id"				 =>  $branch_id,
									"sub_inventory_id" 		 =>  !empty($_POST['sub_inventory_id'][$dp]) ? $_POST['sub_inventory_id'][$dp] : NULL,
									"locator_id" 		 	 =>  !empty($_POST['locator_id'][$dp]) ? $_POST['locator_id'][$dp] : NULL,
									"lot_number" 		 	 =>  !empty($_POST['lot_number'][$dp]) ? $_POST['lot_number'][$dp] : NULL,
									"serial_number" 		 =>  !empty($_POST['serial_number'][$dp]) ? $_POST['serial_number'][$dp] : NULL,
									"transaction_date" 	  	 =>  $this->date_time,
									"transaction_qty" 		 =>  $receivedQty,
									"uom" 				 	 =>  $_POST['uom'][$dp],
									"receipt_header_id" 	 =>  $header_id,
									"receipt_line_id" 	 	 =>  $line_id,

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
							$this->session->set_flashdata('flash_message' , "Quick Receipt Saved Successfully!");
							redirect(base_url() . 'quick_receipt/manageReceipt/view/'.$header_id, 'refresh');
						}
						else if(isset($_POST["submit_btn"]))
						{
							$this->session->set_flashdata('flash_message' , "Quick Receipt Submitted Successfully!");
							redirect(base_url() . 'quick_receipt/manageReceipt', 'refresh');

						}
					}
				}
			break;
			
			case ($type =="edit" || $type =="view"): #edit
				$header_id = $id;
				
				$page_data['discount'] = $this->quick_receipt_model->getDiscount();
				$page_data['tax'] = $this->quick_receipt_model->getTax();

				$result = $this->quick_receipt_model->getViewData($id);
				$page_data['edit_data'] = $result['edit_data'];
				$page_data['line_data'] = $result['line_data'];

				/* if($_POST)
				{
					if(isset($_POST["submit_btn"]))
					{
						$count = isset($_POST['item_id']) ? count(array_filter($_POST['item_id'])) : 0;

						if($count == 0)
						{
							$this->session->set_flashdata('error_message' , "Atleast one item is required!");
							redirect($_SERVER["HTTP_REFERER"], 'refresh');
						}
					}

					$organization_id=$this->input->post('organization_id');
					$branch_id=$this->input->post('branch_id');

					$headerData = array(
						"supplier_id" 			 =>  $this->input->post('supplier_id'),
						"po_currency" 	     	 =>  $this->input->post('po_currency'),
						"po_date" 	  			 =>  date('Y-m-d',strtotime($_POST["po_date"])),
						"supplier_site_id" 	  	 =>  $_POST["supplier_site_id"],
						"po_status" 	  		 =>  $_POST["header_status"],
						"buyer_id" 	  		     =>  $_POST["buyer_id"],
						"organization_id"		 =>  $organization_id,
						"branch_id"				 =>  $branch_id,
						"note_to_receiver" 	  	 =>  $_POST["header_note_to_receiver"],
						"description" 	  		 =>  $_POST["header_description"],
						"last_updated_by" 	  	 =>  $this->user_id,
						"last_updated_date" 	 =>  $this->date_time
					);
					
					#Audit Trails Edit Start here
					$tableName = table_po_headers;
					$menuName = purchase_order;
					$description = "Purchase Order updated successsfully!";
					auditTrails(array_filter($headerData),$tableName,$type,$menuName,$page_data['edit_data'],$description);
					#Audit Trails Edit end here

					$this->db->where('po_header_id', $id);
					$result = $this->db->update('po_headers', $headerData);
					
					if($result)
					{
						#Line Data start
						if(isset($_POST['item_id']))
						{
							$count = count(array_filter($_POST['item_id']));
							for($dp=0;$dp<$count;$dp++)
							{
								$po_line_id = $_POST['po_line_id'][$dp];

								if($po_line_id == 0) #Insert
								{
									$item_id = $_POST['item_id'][$dp];
									$itemQry = "select category_id from inv_sys_items where item_id='".$item_id."'";
									$getCatid = $this->db->query($itemQry)->result_array();

									$category_id = isset($getCatid[0]["category_id"]) ? $getCatid[0]["category_id"] : 0;

									$lineData = array(
										"po_header_id" 			 =>  $header_id,
										"line_num" 			 	 =>  !empty($_POST['line_num'][$dp]) ? $_POST['line_num'][$dp] : NULL,
										"item_id" 			 	 =>  $_POST['item_id'][$dp],
										"item_description" 		 =>  $_POST['description'][$dp],
										"category_id" 			 =>  $category_id,
										"received_qty" 			 =>  $_POST['quantity'][$dp],
										"uom" 				 	 =>  $_POST['uom'][$dp],
										"base_price" 			 =>  $_POST['base_price'][$dp],
										"tax" 			 		 =>  !empty($_POST['tax'][$dp]) ? $_POST['tax'][$dp] : NULL,
										"discount_type" 		 =>  !empty($_POST['discount_type'][$dp]) ? $_POST['discount_type'][$dp] : NULL,
										"discount" 		 		 =>  !empty($_POST['discount'][$dp]) ? $_POST['discount'][$dp] : NULL,
										"discount_reason" 		 =>  !empty($_POST['discount_reason'][$dp]) ? $_POST['discount_reason'][$dp] : NULL,
										"price" 		 		 =>  !empty($_POST['price'][$dp]) ? $_POST['price'][$dp] : NULL,
										"line_value" 		 	 =>  !empty($_POST['line_value'][$dp]) ? $_POST['line_value'][$dp] : NULL,
										"total_tax" 		 	 =>  !empty($_POST['total_tax'][$dp]) ? $_POST['total_tax'][$dp] : NULL,
										"total" 		 	 	 =>  !empty($_POST['total'][$dp]) ? $_POST['total'][$dp] : NULL, 
										// "organization_id" 		 =>  !empty($_POST['organization_id'][$dp]) ? $_POST['organization_id'][$dp] : NULL,
										"organization_id"		 =>  $organization_id,
										"branch_id"				 =>  $branch_id,
										"requested_by" 			 =>  !empty($_POST['requested_by'][$dp]) ? $_POST['requested_by'][$dp] : NULL,
										"note_to_receiver" 		 =>  !empty($_POST['note_to_receiver'][$dp]) ? $_POST['note_to_receiver'][$dp] : NULL,
										"created_by" 	  		 =>  $this->user_id,
										"created_date" 	  		 =>  $this->date_time,
										"last_updated_by" 	  	 =>  $this->user_id,
										"last_updated_date" 	 =>  $this->date_time
									);

									$this->db->insert('po_lines', $lineData);
									$line_id = $this->db->insert_id();
								}
								else #Update
								{
									$item_id = $_POST['item_id'][$dp];
									
									$itemQry = "select category_id from inv_sys_items where item_id='".$item_id."'";
									$getCatid = $this->db->query($itemQry)->result_array();

									$category_id = isset($getCatid[0]["category_id"]) ? $getCatid[0]["category_id"] : 0;

									$lineData = array(
										"line_num" 			 	 =>  !empty($_POST['line_num'][$dp]) ? $_POST['line_num'][$dp] : NULL,
										"item_id" 			 	 =>  $_POST['item_id'][$dp],
										"item_description" 		 =>  $_POST['description'][$dp],
										"category_id" 			 =>  $category_id,
										"received_qty" 			 =>  $_POST['quantity'][$dp],
										"uom" 				 	 =>  $_POST['uom'][$dp],
										"base_price" 			 =>  $_POST['base_price'][$dp],
										"tax" 			 		 =>  !empty($_POST['tax'][$dp]) ? $_POST['tax'][$dp] : NULL,
										"discount_type" 		 =>  !empty($_POST['discount_type'][$dp]) ? $_POST['discount_type'][$dp] : NULL,
										"discount" 		 		 =>  !empty($_POST['discount'][$dp]) ? $_POST['discount'][$dp] : NULL,
										"discount_reason" 		 =>  !empty($_POST['discount_reason'][$dp]) ? $_POST['discount_reason'][$dp] : NULL,
										"price" 		 		 =>  !empty($_POST['price'][$dp]) ? $_POST['price'][$dp] : NULL,
										"line_value" 		 	 =>  !empty($_POST['line_value'][$dp]) ? $_POST['line_value'][$dp] : NULL,
										"total_tax" 		 	 =>  !empty($_POST['total_tax'][$dp]) ? $_POST['total_tax'][$dp] : NULL,
										"total" 		 	 	 =>  !empty($_POST['total'][$dp]) ? $_POST['total'][$dp] : NULL, 
										// "organization_id" 		 =>  !empty($_POST['organization_id'][$dp]) ? $_POST['organization_id'][$dp] : NULL,
										"organization_id"		 =>  $organization_id,
										"branch_id"				 =>  $branch_id,
										"requested_by" 			 =>  !empty($_POST['requested_by'][$dp]) ? $_POST['requested_by'][$dp] : NULL,
										"note_to_receiver" 		 =>  !empty($_POST['note_to_receiver'][$dp]) ? $_POST['note_to_receiver'][$dp] : NULL,
										"created_by" 	  		 =>  $this->user_id,
										"created_date" 	  		 =>  $this->date_time,
										"last_updated_by" 	  	 =>  $this->user_id,
										"last_updated_date" 	 =>  $this->date_time
									);

									$this->db->where('po_header_id', $id);
									$this->db->where('po_line_id', $po_line_id);
									$lineTbl = $this->db->update('po_lines', $lineData);
								}
								#Line Data end
							}
						}
						
						if(isset($_POST["save_btn"]))
						{
							$this->session->set_flashdata('flash_message' , "Purchase order saved successfully!");
							redirect(base_url() . 'purchase_order/managePurchaseOrder/edit/'.$id.'/'.$status, 'refresh');
						}
						else if(isset($_POST["submit_btn"]))
						{
							if(isset($_POST['item_id']))
							{
								$headerQry ="select 
									sum(po_lines.line_value) order_amount,
									sum(po_lines.total_tax) total_tax,
									sum(po_lines.total) total
									from po_headers
									left join po_lines on 
										po_lines.po_header_id = po_headers.po_header_id
									where po_headers.po_header_id='".$header_id."' 
									
									group by po_headers.po_header_id" ;
								$edit_data = $this->db->query($headerQry)->result_array();

								$poAmount = isset($edit_data[0]['total']) ? $edit_data[0]['total'] : "0.00";

								
								$approvalLevelqry = "select user_id,level_id from org_approval_line where $poAmount between from_amount and to_amount"; #group by org_approval_levels.level_id
								$getApprovalLevel = $this->db->query($approvalLevelqry)->result_array();
								
								$user_id = isset($getApprovalLevel[0]["user_id"]) ? $getApprovalLevel[0]["user_id"] : NULL;
								$level_id = isset($getApprovalLevel[0]["level_id"]) ? $getApprovalLevel[0]["level_id"] : NULL;


								$approvalLevQry = "select 
									org_approval_line.line_id,
									org_approval_line.user_id,
									org_approval_line.level_id,
									org_approval_line.from_amount,
									org_approval_line.to_amount,
									org_approval_levels.level_name,
									org_approval_levels.level_id,
									per_people_all.first_name,
									per_people_all.last_name
								from org_approval_line 

								left join org_approval_levels on 
									org_approval_levels.level_id = org_approval_line.level_id

								left join per_user on
									per_user.user_id = org_approval_line.user_id
								
								left join per_people_all on
									per_people_all.person_id = per_user.person_id

								where 
									org_approval_line.approver_type = 'PO'
									and org_approval_line.level_id <= (select level_id from org_approval_line 
									where org_approval_line.approver_type = 'PO' 
									and $poAmount between from_amount and to_amount)
								"; #group by org_approval_levels.level_id

								$getApprovalLevels = $this->db->query($approvalLevQry)->result_array();


								$chkPOExistQry = "select max(instances_id) as instances_id from org_approval_status 
										where reference_id= '".$header_id."' ";
								$chkPOExist = $this->db->query($chkPOExistQry)->result_array();

								if( count($chkPOExist) > 0 )
								{
									$instancesId = isset($chkPOExist[0]['instances_id']) ? $chkPOExist[0]['instances_id'] : NULL;

									if($instancesId !=NULL)
									{
										$instances_id = $instancesId + 1;
									}
									else
									{
										$instances_id = '10001';
									}
								}
								else
								{
									$instances_id = '10001';
								}

								foreach($getApprovalLevels as $levels)
								{
									$user_id = $levels['user_id'];
									$level_id = $levels['level_id'];

									if($user_id == '-1' && $level_id == '1'){
										$approval_status = 'Approved';
									}else{
										$approval_status = 'Pending';
									}

									$approvalsLevels = array(
										"reference_id" 		     => $header_id,
										"instances_id" 		     => $instances_id,
										"user_id" 	 		     => $user_id,
										"level_id" 	 		     => $level_id,
										"approval_status" 	     => $approval_status,
										"approval_type" 	     => "PO",
										"created_by" 	  		 => $this->user_id,
										"created_date" 	  		 => $this->date_time,
										"last_updated_by" 	  	 => $this->user_id,
										"last_updated_date" 	 => $this->date_time
									);
									
									$this->db->insert('org_approval_status', $approvalsLevels);
									$approvalId = $this->db->insert_id();
								}


								if($user_id == '-1' && $level_id == '1') #Auto Approval
								{
									$po_status = "Approved";

									#Header Tbl
									$headerUpdateData = array(
										"po_status" 	  	  =>  $po_status,
										"last_updated_by" 	  =>  $this->user_id,
										"last_updated_date"   =>  $this->date_time,
										"submission_date" 	  =>  $this->date_time,
									);
									$this->db->where('po_header_id', $header_id);
									$headerTbl = $this->db->update('po_headers', $headerUpdateData);
								}
								else
								{
									$po_status = "Pending Approval";

									#Header Tbl
									$headerUpdateData = array(
										"po_status" 	  	  =>  $po_status,
										"last_updated_by" 	  =>  $this->user_id,
										"last_updated_date"   =>  $this->date_time,
										"submission_date" 	  =>  $this->date_time,
									);
									$this->db->where('po_header_id', $header_id);
									$headerTbl = $this->db->update('po_headers', $headerUpdateData);
								}
							}
							
							$this->session->set_flashdata('flash_message' , "Purchase order submitted successfully!");
							redirect(base_url() . 'purchase_order/managePurchaseOrder', 'refresh');
						}
					
						$this->session->set_flashdata('flash_message' , "Purchase order updated successfully!");
						redirect(base_url() . 'purchase_order/managePurchaseOrder/'.$id, 'refresh');
					}
				} */
			break;
			
			default : #Manage
				$totalResult = $this->quick_receipt_model->getQuickReceipts("","",$this->totalCount);
				$page_data["totalRows"] = $totalRows = count($totalResult);

				if(!empty($_SESSION['PAGE']))
				{$limit = $_SESSION['PAGE'];
				}else{$limit = 10;}

				$receipt_number = isset($_GET['receipt_number']) ? $_GET['receipt_number'] :NULL;
				$organization_id = isset($_GET['organization_id']) ? $_GET['organization_id'] :NULL;
				$branch_id = isset($_GET['branch_id']) ? $_GET['branch_id'] :NULL;
				$supplier_id = isset($_GET['supplier_id']) ? $_GET['supplier_id'] :NULL;
				$supplier_site_id = isset($_GET['supplier_site_id']) ? $_GET['supplier_site_id'] :NULL;
				$from_date = isset($_GET['from_date']) ? $_GET['from_date'] : NULL;
				$to_date = isset($_GET['to_date']) ? $_GET['to_date'] : NULL;

				$this->redirectURL = $redirectURL = 'quick_receipt/manageReceipt?receipt_number='.$receipt_number.'&organization_id='.$organization_id.'&branch_id='.$branch_id.'&supplier_id='.$supplier_id.'&supplier_site_id='.$supplier_site_id.'&from_date='.$from_date.'&to_date='.$to_date;
				
				if ( $receipt_number || $organization_id || $branch_id || $supplier_id || $supplier_site_id || $from_date || $to_date  ) {
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
				
				$page_data['resultData']  = $result = $this->quick_receipt_model->getQuickReceipts($limit, $offset, $this->pageCount);
				
				if(isset($_GET['per_page']) && $_GET['per_page'] > 1 && count($result) == 0 )
				{
					redirect(base_url().$redirectURL, 'refresh');
				}

				$download_excel = isset($_GET['download_excel']) ? $_GET['download_excel']: NULL;
				if($download_excel != NULL) 
				{
					$result = $this->summary_model->getRMSalesSummary($limit,$offset,$this->pageCount);
					
					$page_data['resultData'] = isset($result["edit_data"]) ? $result["edit_data"] : array();
					
				
					$date = date('d_M_Y');
					header("Content-type: application/csv");
					header("Content-Disposition: attachment; filename=\"Receipt_Report_".$date.".csv\"");
					header("Pragma: no-cache");
					header("Expires: 0");

					$handle = fopen('php://output', 'w');
					$handle1 = fopen('php://output', 'w');
					fputcsv($handle, array(
						"S.No",
						"Organization Name",
						"Branch Name",
						"Receipt Number",
						"Receipt Date",
						"Bill Number",
						"Supplier Name",
						"Supplier Site Name",
						"Item Name",
						"Item Desc",
						"Item Category",
						"Qty",
						"Price",
						"Tax",
						"Total Tax",
						"Discount",
						"Total",
					));
					$cnt=1;
					$totalAmount = 0;
					foreach ($result as $row) 
					{
						$receipt_date = date("d-M-Y",strtotime($row['receipt_date']));
						
						$narray=array(
							$cnt,
							$row['organization_name'],
							$row['branch_name'],
							$row['receipt_number'],
							$receipt_date,
							$row['bill_number'],
							$row['supplier_name'],
							$row['site_name'],
							$row['item_name'],
							$row['item_description'],
							$row['category_name'],
							$row['received_qty'],
							number_format($row['base_price'],DECIMAL_VALUE,'.',''),
							$row['tax'],
							number_format($row['total_tax'],DECIMAL_VALUE,'.',''),
							number_format($row['total'],DECIMAL_VALUE,'.',''),
						);

						fputcsv($handle, $narray);
						$totalAmount += $row['total'];
						$cnt++;
						
					}

					$narray1=array("","","","","","","","","","","","","","Total :",$totalAmount,);
					fputcsv($handle1, $narray1);
					fclose($handle);
					exit;
				}
				#Download Excel end 
				
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

	public function getLineData()
	{
		$itemQuery = "select item_id,item_name,item_description 
		from inv_sys_items 
		where 1=1
		and active_flag='Y'
		and item_type_id = 31
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


	    echo json_encode($data);
		exit;
	}

	public function deleteLineItems()
	{
		$po_line_id = isset($_POST["po_line_id"]) ? $_POST["po_line_id"] : NULL;

		$this->db->where('po_line_id', $po_line_id);
		$this->db->delete('po_lines');

		/* $this->db->where('transaction_type', 'ORD');
		$this->db->where('order_line_id', $sales_line_id);
		$this->db->delete('inv_transactions'); */
		echo 1;exit;
	}

	public function getLineItems()
	{
		$item_id = isset($_POST["item_id"]) ? $_POST["item_id"] : NULL;

		if($item_id !=NULL)
		{
			$itemQuery = "select 
				inv_sys_items.item_id,
				inv_sys_items.item_name,
				inv_sys_items.item_description,
				categories.category_name from inv_sys_items 
				left join inv_categories categories on
				categories.category_id = inv_sys_items.category_id
				where 
					inv_sys_items.active_flag='Y'
					and inv_sys_items.item_id='".$item_id."' ";
			$data = $this->db->query($itemQuery)->result_array();

			echo json_encode($data);
			
		}exit;
	}

	#Ajax Select Supplier Site
	public function ajaxSelectSupplierSite() 
	{
        $id = $_POST["id"];	
		if($id)
		{			
			$data =  $this->db->query("select 
					sup_supplier_sites.supplier_site_id,
					sup_supplier_sites.site_name from sup_supplier_sites
					where 
						sup_supplier_sites.active_flag='Y' 
						and sup_supplier_sites.supplier_id='".$id."' 
						order by sup_supplier_sites.site_name asc

					")->result_array();
		
			if( count($data) > 0)
			{
				echo '<option value="">- Select -</option>';
				
				foreach($data as $val)
				{
					echo '<option value="'.$val['supplier_site_id'].'">'.ucfirst($val['site_name']).'</option>';
				}
			}
			else
			{
				echo '<option value="">No sites under this supplier!</option>';
			}
		}
		die;
    }
	
	function generatePDF($id="")
    {
		$page_data['id'] = $id;

		$result = $this->quick_receipt_model->getViewData($id);
		$page_data['edit_data'] = $result['edit_data'];
		$page_data['line_data'] = $result['line_data'];
		
		$date = date('d-M-Y');
		ob_start();
		$html = ob_get_clean();
		$html = utf8_encode($html);
		
		$pdf_name = "receipt_pdf_".$date;
		$mpdf = new \Mpdf\Mpdf([
			'setAutoTopMargin' => 'stretch'
		]);

		$mpdf->SetHTMLHeader($this->load->view('backend/quick_receipt/receipt_pdf_header',$page_data,true));
		$html = $this->load->view('backend/quick_receipt/receipt_pdf_content',$page_data,true);
		$mpdf->SetHTMLFooter($this->load->view('backend/quick_receipt/receipt_pdf_footer',$page_data,true));
		
        $mpdf->AddPage('P','','','','',7,7,7,7,7,7);
		$mpdf->WriteHTML($html);
		$mpdf->Output($pdf_name.'.pdf','I');
	}

	#Ajax Select Customer Site
	public function getAjaxSupplierDetails() 
	{
        $id = $_POST["id"];	
		if($id)
		{			
			$data = $this->db->query("select sup_supplier.contact_person from sup_suppliers as sup_supplier
					where 
					sup_supplier.active_flag='Y'
					and sup_supplier.supplier_id='".$id."'
					")->result_array();
		
			echo isset($data[0]["contact_person"]) ? $data[0]["contact_person"] : NULL;
		}
		die;
    }

	#Ajax Select Supplier Site
	public function getAjaxSupplierSiteDetails() 
	{
        $supplier_id = $_POST["supplier_id"];	
        $id = $_POST["id"];	
		
		if($supplier_id && $id)
		{			
			$data = $this->db->query("select 
					sup_supplier_sites.contact_person, 
					sup_suppliers.contact_person as sup_contact_person
					
					from sup_supplier_sites

					left join sup_suppliers on sup_suppliers.supplier_id = sup_supplier_sites.supplier_id
					where sup_supplier_sites.active_flag='Y' 
					and sup_supplier_sites.supplier_site_id='".$id."'
					and sup_supplier_sites.supplier_id='".$supplier_id."'
					")->result_array();
		
			$site_contact_person = !empty($data[0]["contact_person"]) ? $data[0]["contact_person"] : NULL;
			$sup_contact_person = !empty($data[0]["sup_contact_person"]) ? $data[0]["sup_contact_person"] : NULL;

			if($site_contact_person !=NULL){
				$contactPerson = $site_contact_person;
			}
			else if($sup_contact_person !=NULL){
				$contactPerson = $sup_contact_person;
			}else{
				$contactPerson = NULL;
			}
			echo $contactPerson;
		}
		die;
    }

	public function ajaxSelectItemUom() 
	{
        $id = $_POST["id"];	

		if($id)
		{		
			$getItemUomQry = "select uom from inv_sys_items where item_id='".$id."' ";
			$getItemUom = $this->db->query($getItemUomQry)->result_array();

			$uom = isset($getItemUom[0]["uom"]) ? $getItemUom[0]["uom"] : NULL;

			$getItemUOM = "select uom.uom_id,uom.uom_code,inv_sys_items.item_id from uom
			left join inv_sys_items on inv_sys_items.uom = uom.uom_id
			where uom.active_flag='Y' group by uom.uom_id";

			$data = $this->db->query($getItemUOM)->result_array();
		
			if( count($data) > 0)
			{
				echo '<option value="">- Select -</option>';
				
				foreach( $data as $val )
				{
					$selected="";
					if( $uom == $val['uom_id'] )
					{
						$selected="selected='selected'";
					}
					echo '<option value="'.$val['uom_id'].'" '.$selected.'>'.ucfirst($val['uom_code']).'</option>';
				}
			}
			else
			{
				echo '<option value="">- Select -</option>';
			}
		}
		die;
    }

	function ajaxPoList() 
	{
		if(isset($_POST["query"]))  
		{  
			$output = '';  
			
			$po_number = $_POST['query'];

			$result = $this->quick_receipt_model->getAjaxPoAll($po_number);
			
			$output = '<ul class="list-unstyled-po_header_id">';  
			
			if( count($result) > 0 )  
			{  
				foreach($result as $row)  
				{	
					$po_number = $row["po_number"];
					$po_header_id = $row["po_header_id"];
					$output .= '<a><li onclick="return getPoList(\'' .$po_header_id. '\',\'' .$po_number. '\');">'.$po_number.'</li></a>';  
				}  
			}  
			else  
			{  
				$po_number = "";
				$po_header_id = "";
				
				$output .= '<li onclick="return getPoList(\'' .$po_header_id. '\',\'' .$po_number. '\');">Sorry! PO Number Not Found.</li>';  
			}
			$output .= '</ul>';  
			echo $output;  
		}
	}

	function ajaxReceiptList() 
	{
		if(isset($_POST["query"]))  
		{  
			$output = '';  
			
			$receipt_number = $_POST['query'];

			$result = $this->quick_receipt_model->getAjaxreceiptAll($receipt_number);
			
			$output = '<ul class="list-unstyled-receipt_header_id">';  
			
			if( count($result) > 0 )  
			{  
				foreach($result as $row)  
				{	
					$receipt_number = $row["receipt_number"];
					$receipt_header_id = $row["receipt_header_id"];
					$output .= '<a><li onclick="return getReceiptList(\'' .$receipt_header_id. '\',\'' .$receipt_number. '\');">'.$receipt_number.'</li></a>';  
				}  
			}  
			else  
			{  
				$receipt_number = "";
				$receipt_header_id = "";
				
				$output .= '<li onclick="return getReceiptList(\'' .$receipt_header_id. '\',\'' .$receipt_number. '\');">Sorry! Reciept Number Not Found.</li>';  
			}
			$output .= '</ul>';  
			echo $output;  
		}
	}
}
?>
