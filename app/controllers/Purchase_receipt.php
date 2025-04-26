<?php
defined('BASEPATH') OR exit('No direct script access allowed');
include_once('vendor/autoload.php');
class Purchase_receipt extends CI_Controller 
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
	
	#Purchase Receipt
	function managePurchaseReceipt($type = '', $id = '', $status = '')
    {
		if (empty($this->user_id))
        {
			redirect(base_url() . 'admin/adminLogin', 'refresh');
		}
	
		$page_data['type'] = $type;
		$page_data['id'] = $id;
		
		$page_data['purchase'] = $page_data['managePurchaseReceipt'] = 1;
		$page_data['page_name']  = 'purchase_receipt/managePurchaseReceipt';
		$page_data['page_title'] = 'Purchase Receipt';
		
		switch(true)
		{
			case ($type == "add"):
				if($_POST)
				{
					$received_qty = count(array_filter($_POST['received_qty']));
					
					if($received_qty == 0)
					{
						$this->session->set_flashdata('error_message' , "Please enter atleast 1 item qty!");
						redirect(base_url() . 'purchase_receipt/managePurchaseReceipt/add', 'refresh');
					}

					$po_header_id = $this->input->post('header_po_header_id');

					$organization_id=$this->input->post('organization_id');
					$branch_id=$this->input->post('branch_id');
					$headerData = array(
						"po_header_id" 			 =>  $po_header_id,
						"note_to_receiver"		 =>  $this->input->post('header_note_to_receiver'),
						"supplier_invoice_number"=>  $this->input->post('supplier_invoice_number'),
						"organization_id"		 =>  $organization_id,
						"branch_id"				 =>  $branch_id,
						"supplier_invoice_date"  =>  !empty($_POST["supplier_invoice_date"]) ? date('Y-m-d',strtotime($_POST["supplier_invoice_date"])) : NULL,
						"shipment_ref" 	  	     =>  $_POST["shipment_ref"],
						"receipt_date"  		=>   date('Y-m-d',strtotime($_POST["receipt_date"])),
						"description" 	  		 =>  $_POST["header_description"],
						"created_by" 	  		 =>  $this->user_id,
						"created_date" 	  		 =>  $this->date_time,
						"last_updated_by" 	  	 =>  $this->user_id,
						"last_updated_date" 	 =>  $this->date_time
					);

					#Audit Trails Start here
					$tableName = table_rcv_receipt_headers;
					$menuName = purchase_receipt;
					$description = "Purchase Receipt created successsfully!";
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
							ltv.list_code = 'PUR_REC' 
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
							"receipt_number" 	  		 =>  $documentNumber,
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
						if(isset($_POST['received_qty']))
						{
							$count = count(array_filter($_POST['received_qty']));

							for($dp=0;$dp<$count;$dp++)
							{
								$item_id = $_POST['item_id'][$dp];

								$itemQry = "select category_id from inv_sys_items where item_id='".$item_id."'";
								$getCatid = $this->db->query($itemQry)->result_array();

								$category_id = isset($getCatid[0]["category_id"]) ? $getCatid[0]["category_id"] : NULL;

								if(!empty($_POST['received_qty'][$dp]))
								{
									$receivedQty = $_POST['received_qty'][$dp];
								}else{
									$receivedQty = NULL;
								}

								$lineData = array(
									"receipt_header_id" 	 =>  $header_id,
									"po_header_id" 			 =>  $_POST['po_header_id'][$dp],
									"po_line_id" 			 =>  $_POST['po_line_id'][$dp],
									"line_num" 			 	 =>  !empty($_POST['line_num'][$dp]) ? $_POST['line_num'][$dp] : NULL,
									"item_id" 			 	 =>  $_POST['item_id'][$dp],
									"item_description" 		 =>  $_POST['description'][$dp],
									"category_id" 			 =>  $category_id,
									"supplier_item" 		 =>  $_POST['supplier_item'][$dp],
									"received_qty" 			 =>  $receivedQty,
									"uom" 				 	 =>  $_POST['uom'][$dp],
									"note_to_receiver" 		 =>  $_POST['note_to_receiver'][$dp],
									
									// "organization_id" 		 =>  !empty($_POST['organization_id'][$dp]) ? $_POST['organization_id'][$dp] : NULL,
									"organization_id"		 =>  $organization_id,
									"branch_id"				 =>  $branch_id,
									"sub_inventory_id" 		 =>  !empty($_POST['sub_inventory_id'][$dp]) ? $_POST['sub_inventory_id'][$dp] : NULL,
									"locator_id" 		 	 =>  !empty($_POST['locator_id'][$dp]) ? $_POST['locator_id'][$dp] : NULL,
									"lot_number" 		 	 =>  !empty($_POST['lot_number'][$dp]) ? $_POST['lot_number'][$dp] : NULL,
									"serial_number" 		 =>  !empty($_POST['serial_number'][$dp]) ? $_POST['serial_number'][$dp] : NULL,
									
									"requested_by" 			 =>  !empty($_POST['requested_by'][$dp]) ? $_POST['requested_by'][$dp] : NULL,
									
									"created_by" 	  		 =>  $this->user_id,
									"created_date" 	  		 =>  $this->date_time,
									"last_updated_by" 	  	 =>  $this->user_id,
									"last_updated_date" 	 =>  $this->date_time
								);

								$this->db->insert('rcv_receipt_lines', $lineData);
								$line_id = $this->db->insert_id();

								#Insert Transaction data start here
								$invTrnData = array(
									"transaction_type" 	 	 =>  "RCV",
									"item_id" 			 	 =>  $_POST['item_id'][$dp],
									// "organization_id" 		 =>  !empty($_POST['organization_id'][$dp]) ? $_POST['organization_id'][$dp] : NULL,
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
							$this->session->set_flashdata('flash_message' , "Purchase receipt saved successfully!");
							redirect(base_url() . 'purchase_receipt/managePurchaseReceipt/edit/'.$header_id, 'refresh');
						}
						else if(isset($_POST["submit_btn"]))
						{
							$this->session->set_flashdata('flash_message' , "Purchase receipt submitted successfully!");
							redirect(base_url() . 'purchase_receipt/managePurchaseReceipt', 'refresh');
						}
					}
				}
			break;
			
			case ($type =="edit" || $type =="view"): #Edit / View
				$header_id = $id;
				$headerQry ="select rcv_receipt_headers.* from rcv_receipt_headers
				where rcv_receipt_headers.receipt_header_id='".$id."' ";
				$page_data['edit_data'] = $this->db->query($headerQry)->result_array();

				$po_header_id = $page_data['edit_data'][0]["po_header_id"];
				
				if($type =="edit")
				{
					$lineQry =" select 
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
					sp.rcv_serial_number,
					sp.receipt_line_id,
					sp.line_num
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
							(select
									po_lines.quantity - sum(coalesce(rcv_line_tbl.received_qty,0)) 
									from po_lines
									left join po_headers as header_tbl on header_tbl.po_header_id = po_lines.po_header_id
									left join rcv_receipt_lines as rcv_line_tbl on (rcv_line_tbl.po_line_id = po_lines.po_line_id and rcv_line_tbl.receipt_header_id != '".$id."') 
								where 
								po_lines.po_header_id = line_tbl.po_header_id
								and po_lines.po_line_id = line_tbl.po_line_id) as po_bal_qty,
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
							rcv_line_tbl.serial_number as rcv_serial_number,
							rcv_line_tbl.receipt_line_id,
							rcv_line_tbl.line_num
							from po_lines as line_tbl
								left join po_headers as header_tbl on header_tbl.po_header_id = line_tbl.po_header_id
								left join inv_sys_items as items on items.item_id = line_tbl.item_id
								left join inv_categories as categories on categories.category_id = line_tbl.category_id
								left join uom on uom.uom_id = line_tbl.uom
								left join per_people_all on per_people_all.person_id = line_tbl.requested_by
								left join rcv_receipt_lines as rcv_line_tbl on (rcv_line_tbl.po_line_id = line_tbl.po_line_id and rcv_line_tbl.receipt_header_id = '".$id."')
								left join rcv_receipt_headers on (rcv_receipt_headers.po_header_id = line_tbl.po_header_id and rcv_receipt_headers.receipt_header_id = rcv_line_tbl.receipt_header_id)
						where 
							line_tbl.po_header_id= '".$po_header_id."'
							
							group by line_tbl.po_line_id
					) sp
					where sp.po_bal_qty > 0";
				}
				else if($type =="view")
				{
					$lineQry ="select 
					line_tbl.sub_inventory_id as rcv_sub_inventory_id, 
					line_tbl.organization_id, 
					line_tbl.receipt_line_id, 
					line_tbl.po_header_id, 
					line_tbl.po_line_id, 
					line_tbl.item_id, 
					line_tbl.category_id, 
					line_tbl.uom, 
					line_tbl.requested_by as po_requested_by, 
					line_tbl.line_num, 
					line_tbl.supplier_item, 
					line_tbl.received_qty, 
					line_tbl.organization_id as rcv_organization_id, 
					line_tbl.locator_id as rcv_locator_id, 
					line_tbl.note_to_receiver, 
					line_tbl.lot_number as rcv_lot_number, 
					line_tbl.serial_number as rcv_serial_number, 
					line_tbl.item_description, 
					categories.category_name, 
					per_people_all.person_id, 
					per_people_all.first_name, 
					per_people_all.last_name, 
					items.item_name, 
					uom.uom_code ,
					(
						select po_lines.quantity - sum(coalesce(rcv_line_tbl.received_qty,0)) from po_lines
						left join po_headers as header_tbl on header_tbl.po_header_id = po_lines.po_header_id
						left join rcv_receipt_lines as rcv_line_tbl on (rcv_line_tbl.po_line_id = po_lines.po_line_id 
													and rcv_line_tbl.receipt_header_id != '".$id."'
					) 
					where 
						po_lines.po_header_id = line_tbl.po_header_id
						and po_lines.po_line_id = line_tbl.po_line_id) as po_bal_qty
					from rcv_receipt_lines as line_tbl 


						left join rcv_receipt_headers as header_tbl on header_tbl.receipt_header_id = line_tbl.receipt_header_id 
						left join inv_sys_items as items on items.item_id = line_tbl.item_id
						left join inv_categories as categories on categories.category_id = line_tbl.category_id 
						left join uom on uom.uom_id = line_tbl.uom 
						left join per_people_all on per_people_all.person_id = line_tbl.requested_by 
						where line_tbl.receipt_header_id = '".$id."'
						";
				}
				
				$page_data['line_data'] = $this->db->query($lineQry)->result_array();

				#print_r($page_data['line_data']);

				if($_POST)
				{
					$po_header_id = $this->input->post('header_po_header_id');
					
					$organization_id=$this->input->post('organization_id');
					$branch_id=$this->input->post('branch_id');
					
					$headerData = array(
						#"po_header_id" 			 =>  $po_header_id,
						"note_to_receiver"		 =>  $this->input->post('header_note_to_receiver'),
						"supplier_invoice_number"=>  $this->input->post('supplier_invoice_number'),
						"supplier_invoice_date"  =>  !empty($_POST["supplier_invoice_date"]) ? date('Y-m-d',strtotime($_POST["supplier_invoice_date"])) : NULL,
						"organization_id"		 =>  $organization_id,
						"branch_id"				 =>  $branch_id,
						"shipment_ref" 	  	     =>  $_POST["shipment_ref"],
						"receipt_date"  		=>   date('Y-m-d',strtotime($_POST["receipt_date"])),
						"description" 	  		 =>  $_POST["header_description"],
						"last_updated_by" 	  	 =>  $this->user_id,
						"last_updated_date" 	 =>  $this->date_time
					);
					
					$this->db->where('receipt_header_id', $id);
					$result = $this->db->update('rcv_receipt_headers', $headerData);
					
					if($result)
					{
						#Line Data start
						if(isset($_POST['received_qty']))
						{
							$count = count($_POST['received_qty']);

							for($dp=0;$dp<$count;$dp++)
							{
								$receipt_line_id = $_POST['receipt_line_id'][$dp];
								$received_qty = $_POST['received_qty'][$dp];

								if($receipt_line_id == 0 && $received_qty > 0 && !empty($received_qty)) #Insert
								{
									$item_id = $_POST['item_id'][$dp];

									$itemQry = "select category_id from inv_sys_items where item_id='".$item_id."'";
									$getCatid = $this->db->query($itemQry)->result_array();

									$category_id = isset($getCatid[0]["category_id"]) ? $getCatid[0]["category_id"] : 0;

									if(!empty($_POST['received_qty'][$dp]))
									{
										$receivedQty = $_POST['received_qty'][$dp];
									}else{
										$receivedQty = NULL;
									}
									
									$lineData = array(
										"receipt_header_id" 	 =>  $header_id,
										"po_header_id" 			 =>  $_POST['po_header_id'][$dp],
										"po_line_id" 			 =>  $_POST['po_line_id'][$dp],
										"line_num" 			 	 =>  !empty($_POST['line_num'][$dp]) ? $_POST['line_num'][$dp] : NULL,
										"item_id" 			 	 =>  $_POST['item_id'][$dp],
										"item_description" 		 =>  $_POST['description'][$dp],
										"category_id" 			 =>  $category_id,
										"supplier_item" 		 =>  $_POST['supplier_item'][$dp],
										"received_qty" 			 =>  $receivedQty,
										"uom" 				 	 =>  $_POST['uom'][$dp],
										"note_to_receiver" 		 =>  $_POST['note_to_receiver'][$dp],
										
										// "organization_id" 		 =>  !empty($_POST['organization_id'][$dp]) ? $_POST['organization_id'][$dp] : NULL,
										"organization_id"		 =>  $organization_id,
										"branch_id"				 =>  $branch_id,
										"sub_inventory_id" 		 =>  !empty($_POST['sub_inventory_id'][$dp]) ? $_POST['sub_inventory_id'][$dp] : NULL,
										"locator_id" 		 	 =>  !empty($_POST['locator_id'][$dp]) ? $_POST['locator_id'][$dp] : NULL,
										"lot_number" 		 	 =>  !empty($_POST['lot_number'][$dp]) ? $_POST['lot_number'][$dp] : NULL,
										"serial_number" 		 =>  !empty($_POST['serial_number'][$dp]) ? $_POST['serial_number'][$dp] : NULL,
										
										"requested_by" 			 =>  !empty($_POST['requested_by'][$dp]) ? $_POST['requested_by'][$dp] : NULL,
										
										"created_by" 	  		 =>  $this->user_id,
										"created_date" 	  		 =>  $this->date_time,
										"last_updated_by" 	  	 =>  $this->user_id,
										"last_updated_date" 	 =>  $this->date_time
									);

									$this->db->insert('rcv_receipt_lines', $lineData);
									$line_id = $this->db->insert_id();

									#Insert Transaction data start here
									$invTrnData = array(
										"transaction_type" 	 	 =>  "RCV",
										"item_id" 			 	 =>  $_POST['item_id'][$dp],
										// "organization_id" 		 =>  !empty($_POST['organization_id'][$dp]) ? $_POST['organization_id'][$dp] : NULL,
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
									#Insert Transaction data start here
								}
								else #Update
								{
									$chkExistQry = "select receipt_line_id from rcv_receipt_lines where receipt_line_id='".$receipt_line_id."' ";
									$chkExist = $this->db->query($chkExistQry)->result_array();

									if( ( count($chkExist) > 0) && $received_qty == 0 || $received_qty == "")
									{
										#Delete Receipt line Tbl
										$this->db->where('receipt_line_id', $receipt_line_id);
										$this->db->delete('rcv_receipt_lines');

										#Delete Trns Tbl
										$this->db->where('transaction_type', 'RCV');
										$this->db->where('receipt_header_id', $id);
										$this->db->where('receipt_line_id ', $receipt_line_id);
										$this->db->delete('inv_transactions');
									}

									$item_id = $_POST['item_id'][$dp];
									
									$itemQry = "select category_id from inv_sys_items where item_id='".$item_id."'";
									$getCatid = $this->db->query($itemQry)->result_array();

									$category_id = isset($getCatid[0]["category_id"]) ? $getCatid[0]["category_id"] : 0;

									$lineData = array(
										"receipt_header_id" 	 =>  $header_id,
										"po_header_id" 			 =>  $_POST['po_header_id'][$dp],
										"po_line_id" 			 =>  $_POST['po_line_id'][$dp],
										"line_num" 			 	 =>  !empty($_POST['line_num'][$dp]) ? $_POST['line_num'][$dp] : NULL,
										"item_id" 			 	 =>  $_POST['item_id'][$dp],
										"item_description" 		 =>  $_POST['description'][$dp],
										"category_id" 			 =>  $category_id,
										"supplier_item" 		 =>  $_POST['supplier_item'][$dp],
										"received_qty" 			 =>  $_POST['received_qty'][$dp],
										"uom" 				 	 =>  $_POST['uom'][$dp],
										"note_to_receiver" 		 =>  $_POST['note_to_receiver'][$dp],
										
										// "organization_id" 		 =>  !empty($_POST['organization_id'][$dp]) ? $_POST['organization_id'][$dp] : NULL,
										"organization_id"		 =>  $organization_id,
										"branch_id"				 =>  $branch_id,
										"sub_inventory_id" 		 =>  !empty($_POST['sub_inventory_id'][$dp]) ? $_POST['sub_inventory_id'][$dp] : NULL,
										"locator_id" 		 	 =>  !empty($_POST['locator_id'][$dp]) ? $_POST['locator_id'][$dp] : NULL,
										"lot_number" 		 	 =>  !empty($_POST['lot_number'][$dp]) ? $_POST['lot_number'][$dp] : NULL,
										"serial_number" 		 =>  !empty($_POST['serial_number'][$dp]) ? $_POST['serial_number'][$dp] : NULL,
										"requested_by" 			 =>  !empty($_POST['requested_by'][$dp]) ? $_POST['requested_by'][$dp] : NULL,
										"last_updated_by" 	  	 =>  $this->user_id,
										"last_updated_date" 	 =>  $this->date_time
									);

									$this->db->where('receipt_header_id', $id);
									$this->db->where('receipt_line_id', $receipt_line_id);
									$lineTbl = $this->db->update('rcv_receipt_lines', $lineData);


									#Insert Transaction data start here
									$receiptQry = "select transaction_id from inv_transactions 
									where 1=1
									and transaction_type = 'RCV'
									and receipt_header_id = '".$id."'
									and receipt_line_id = '".$receipt_line_id."'
									";
									$chkReceipt = $this->db->query($receiptQry)->result_array();

									if( count($chkReceipt) > 0 )
									{
										$invTrnData = array(
											#"transaction_type" 	 	 =>  "RCV",
											"item_id" 			 	 =>  $_POST['item_id'][$dp],
											// "organization_id" 		 =>  !empty($_POST['organization_id'][$dp]) ? $_POST['organization_id'][$dp] : NULL,
											"organization_id"		 =>  $organization_id,
											"branch_id"				 =>  $branch_id,
											"sub_inventory_id" 		 =>  !empty($_POST['sub_inventory_id'][$dp]) ? $_POST['sub_inventory_id'][$dp] : NULL,
											"locator_id" 		 	 =>  !empty($_POST['locator_id'][$dp]) ? $_POST['locator_id'][$dp] : NULL,
											"lot_number" 		 	 =>  !empty($_POST['lot_number'][$dp]) ? $_POST['lot_number'][$dp] : NULL,
											"serial_number" 		 =>  !empty($_POST['serial_number'][$dp]) ? $_POST['serial_number'][$dp] : NULL,
											"transaction_date" 	  	 =>  $this->date_time,
											"transaction_qty" 		 =>  $_POST['received_qty'][$dp],
											"uom" 				 	 =>  $_POST['uom'][$dp],
											#"receipt_header_id" 	 =>  $header_id,
											#"receipt_line_id" 	 	 =>  $line_id,

											"last_updated_by" 	  	 =>  $this->user_id,
											"last_updated_date" 	 =>  $this->date_time
										);
										
										$this->db->where('transaction_type', 'RCV');
										$this->db->where('receipt_header_id', $id);
										$this->db->where('receipt_line_id ', $receipt_line_id);
										$lineTbl = $this->db->update('inv_transactions', $invTrnData);
									}
									#Insert Transaction data start here
								}
							}
						}
						#Line Data end
						if(isset($_POST["save_btn"]))
						{
							$this->session->set_flashdata('flash_message' , "Purchase receipt saved successfully!");
							redirect(base_url() . 'purchase_receipt/managePurchaseReceipt/edit/'.$id, 'refresh');
						}
						else if(isset($_POST["submit_btn"]))
						{
							$this->session->set_flashdata('flash_message' , "Purchase receipt submitted successfully!");
							redirect(base_url() . 'purchase_receipt/managePurchaseReceipt', 'refresh');
						}	
					}
				}
			break;
			
			default : #Manage
				$totalResult = $this->purchase_receipt_model->getManagePurchaseReceipt("","",$this->totalCount);
				$page_data["totalRows"] = $totalRows = count($totalResult);

				if(!empty($_SESSION['PAGE']))
				{$limit = $_SESSION['PAGE'];
				}else{$limit = 10;}

				$receipt_number 	= isset($_GET['receipt_number']) ? $_GET['receipt_number'] :NULL;
				$po_header_id 		= isset($_GET['po_header_id']) ? $_GET['po_header_id'] :NULL;
				$organization_id 	= isset($_GET['organization_id']) ? $_GET['organization_id'] :NULL;
				$branch_id 			= isset($_GET['branch_id']) ? $_GET['branch_id'] :NULL;
				$from_date 			= isset($_GET['from_date']) ? $_GET['from_date'] :NULL;
				$to_date 			= isset($_GET['to_date']) ? $_GET['to_date'] :NULL;

				$redirectURL = 'purchase_receipt/managePurchaseReceipt?receipt_number='.$receipt_number.'&po_header_id='.$po_header_id.'&organization_id='.$organization_id.'&branch_id='.$branch_id.'&from_date='.$from_date.'&to_date='.$to_date;
				
				if ($receipt_number != NULL || $po_header_id != NULL || $organization_id!=NULL || $branch_id!=NULL || $from_date != NULL || $to_date != NULL) {
					$base_url = base_url('purchase_receipt/managePurchaseReceipt?receipt_number='.$_GET['receipt_number'].'&organization_id='.$_GET['organization_id'].'&branch_id='.$_GET['branch_id'].'&from_date='.$_GET['from_date'].'&to_date='.$_GET['to_date'].'');
				} else {
					$base_url = base_url('purchase_receipt/managePurchaseReceipt?receipt_number=&po_header_id=&organization_id=&branch_id=&from_date=&to_date=Y');
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
				
				$page_data['resultData']  = $result = $this->purchase_receipt_model->getManagePurchaseReceipt($limit, $offset, $this->pageCount);
				
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

	#Receipt Lines
	public function getReceiptLines($po_header_id="")
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
				sp.po_organization_id,
				sp.po_branch_id,
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
				sp.rcv_serial_number,
				sp.line_num

                from 
				(
					select 
						header_tbl.organization_id as po_organization_id,
						header_tbl.branch_id as po_branch_id,
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
						line_tbl.line_num,
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
	}

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
}
?>
