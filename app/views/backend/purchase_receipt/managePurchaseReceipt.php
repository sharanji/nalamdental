<?php 
	$purchaseReceiptMenu = accessMenu(purchase_receipt);
?>
<div class="content"><!-- Content start-->
	<div class="card"><!-- Card start-->
		<div class="card-body">
			<?php
				if(isset($type) && $type == "add" || $type == "edit" || $type == "view")
				{
					if($type == "view")
					{
						$fieldSetDisabled = "disabled";
						$searchDropdown = "";
						$this->fieldDisabled = $fieldDisabled = "";
						$this->fieldReadonly = $fieldReadonly = "";
					}
					else
					{
						if($type == "add" || $type == "edit")
						{
							$this->fieldDisabled = $fieldDisabled = "";
							$this->fieldReadonly = $fieldReadonly = "";
						} 
						
						$fieldSetDisabled = "";
						$searchDropdown = "searchDropdown";
					}
					?>
					<form action="" --class="form-validate-jquery" enctype="multipart/form-data" method="post">	
						<div class="header-lines">			
							<!-- Buttons start here -->
							<div class="row mb-3">
								<div class="col-md-6">
									<h3>
										<b>
											<?php 
												if($type == "add")
												{
													?>
													Create
													<?php 
												}
												else if($type == "edit")
												{
													echo ucfirst($type);
												}
												else if($type == "view")
												{
													echo ucfirst($type);
												}  
											?>
											<?php echo $page_title;?>
										</b>
									</h3>
								</div>
								<div class="col-md-6 text-right">
									<?php 
										if($type == "add" || $type == "edit")
										{
											?>
											<button type="submit" name="save_btn" class="btn btn-primary btn-sm">Save</button>
											<button type="submit" name="submit_btn" class="btn btn-primary btn-sm">Submit</button>
											<?php 
										} 
									?>
									<a href="<?php echo base_url(); ?>purchase_receipt/managePurchaseReceipt" class="btn btn-default btn-sm">Close</a>
								</div>
							</div>
							<!-- Buttons end here -->
							
							<fieldset <?php echo $fieldSetDisabled;?>>
								<!-- Header Section Start Here-->
								<section class="header-section">
									<div class="row">
										<div class="col-md-4">
											<div class="row">
												<label class="col-form-label col-md-6 text-right">PO Number</label>
												<div class="form-group col-md-6">
													<?php 
														if($type == "add" )
														{
															$poQry = "
																select s.po_header_id,s.po_number from 
																( 
																	SELECT
																	po_headers.po_header_id,
																	po_headers.po_number,
																	( po_lines.quantity ) - SUM(coalesce(rcv_line_tbl.received_qty, 0)) AS po_bal_qty
																	FROM po_headers
																	LEFT JOIN po_lines ON po_lines.po_header_id = po_headers.po_header_id
																	LEFT JOIN rcv_receipt_lines as rcv_line_tbl ON 
																	(   rcv_line_tbl.po_line_id = po_lines.po_line_id
																		AND rcv_line_tbl.po_header_id = po_headers.po_header_id 
																	)
																	where 
																	po_headers.po_status='Approved'
																	group by po_lines.po_line_id
																) s
																where s.po_bal_qty > 0 
																group by po_number
																order by po_number asc
																
															";

															//echo $poQry;
															$getPurchaseOrder = $this->db->query($poQry)->result_array();
														}
														else
														{
															$poQry = "select po_headers.po_header_id, po_headers.po_number from po_headers 
															left join rcv_receipt_headers on rcv_receipt_headers.po_header_id = po_headers.po_header_id
															where rcv_receipt_headers.receipt_header_id='".$id."'";
															$getPurchaseOrder = $this->db->query($poQry)->result_array();
														}
													?>
													<select name="header_po_header_id" id="header_po_header_id" onchange="selectPurchaseOrder(this.value);" required class="form-control <?php echo $searchDropdown;?>">
														<option value="">- Select -</option>
														<?php 
															foreach($getPurchaseOrder as $row)
															{
																$selected="";
																if(isset($edit_data[0]['po_header_id']) && $edit_data[0]['po_header_id'] == $row["po_header_id"] )
																{
																	$selected="selected='selected'";
																}
																?>
																<option value="<?php echo $row["po_header_id"];?>" <?php echo $selected;?>><?php echo ucfirst($row["po_number"]);?></option>
																<?php 
															} 
														?>
													</select>
												</div>
											</div>
										</div>
										
										<div class="col-md-4">
											<div class="row">
												<label class="col-form-label col-md-6 text-right">Supplier Invoice Num</label>
												<div class="form-group col-md-6">
													<input type="text" name="supplier_invoice_number" id="supplier_invoice_number" <?php echo $fieldReadonly;?> autocomplete="off" class="form-control single_quotes" value="<?php echo isset($edit_data[0]['supplier_invoice_number']) ? $edit_data[0]['supplier_invoice_number'] : NULL;?>" placeholder="">
												</div>
											</div>
										</div>

										<div class="col-md-4">
											<div class="row">
												<label class="col-form-label col-md-6 text-right">Receipt Number</label>
												<div class="form-group col-md-6">
													<input type="text" name="receipt_number" id="receipt_number" readonly autocomplete="off" required class="form-control no-outline -default_date" value="<?php echo isset($edit_data[0]['receipt_number']) ? $edit_data[0]['receipt_number'] : NULL;?>" placeholder="">
												</div>
											</div>
										</div>
									</div>
									
									<div class="row">
										<div class="col-md-4">
											<div class="row">
												<label class="col-form-label col-md-6 text-right">Note to Receiver</label>
												<div class="form-group col-md-6">
													<input type="text" name="header_note_to_receiver" readonly id="header_note_to_receiver" <?php echo $fieldReadonly;?> autocomplete="off" class="form-control single_quotes" value="<?php echo isset($edit_data[0]['note_to_receiver']) ? $edit_data[0]['note_to_receiver'] : NULL;?>" placeholder="">
												</div>
											</div>
										</div>

										<div class="col-md-4">
											<div class="row">
												<label class="col-form-label col-md-6 text-right">Supplier Invoice Date</label>
												<div class="form-group col-md-6">
													<input type="text" name="supplier_invoice_date" id="supplier_invoice_date" readonly <?php echo $fieldReadonly;?> autocomplete="off" class="form-control default_date" value="<?php echo isset($edit_data[0]['supplier_invoice_date']) ? date("d-M-Y",strtotime($edit_data[0]['supplier_invoice_date'])) : NULL;?>" placeholder="">
												</div>
											</div>
										</div>

										<div class="col-md-4">
											<div class="row">
												<label class="col-form-label col-md-6 text-right"><span class="text-danger">*</span> Receipt Date</label>
												<div class="form-group col-md-6">
													<input type="text" name="receipt_date" id="receipt_date" readonly autocomplete="off" required class="form-control default_date" value="<?php echo isset($edit_data[0]['receipt_date']) ? date("d-M-Y",strtotime($edit_data[0]['receipt_date'])) : date("d-M-Y");?>" placeholder="">
												</div>
											</div>
										</div>	
									</div>

									<div class="row">
										<div class="col-md-4">
											<div class="row">
												<label class="col-form-label col-md-6 text-right"><span class="text-danger">*</span> Organization</label>
												<div class="form-group col-md-6">
													
													<select name="organization_id" id="organization_id" class="form-control" required style="pointer-events:none" readonly>
														<option value="">- Select -</option>
														<?php 
															$getOrganization = $this->organization_model->getOrgAll();

															foreach($getOrganization as $organization)
															{
																$selected="";
																if(isset($edit_data[0]['organization_id']) && $edit_data[0]['organization_id'] == $organization["organization_id"] )
																{
																	$selected="selected='selected'";
																}
																?>
																<option value="<?php echo $organization["organization_id"];?>" <?php echo $selected;?>><?php echo ucfirst($organization["organization_name"]);?></option>
																<?php 
															}
														?>
													</select>
												</div>
											</div>
										</div>
										<div class="col-md-4">
											<div class="row">
												<label class="col-form-label col-md-6 text-right">Shipment Ref</label>
												<div class="form-group col-md-6">
													<input type="text" name="shipment_ref" id="shipment_ref" <?php echo $fieldReadonly;?> autocomplete="off" class="form-control single_quotes" value="<?php echo isset($edit_data[0]['shipment_ref']) ? $edit_data[0]['shipment_ref'] : NULL;?>" placeholder="">
												</div>
											</div>
										</div>
										
										<div class="col-md-4">
											<div class="row">
												<label class="col-form-label col-md-6 text-right">Description</label>
												<div class="form-group col-md-6">
													<textarea name="header_description" id="header_description" rows="1" <?php echo $fieldReadonly;?> autocomplete="off" class="form-control single_quotes" placeholder=""><?php echo isset($edit_data[0]['description']) ? $edit_data[0]['description'] : NULL;?></textarea>
												</div>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-md-4">
											<div class="row">
												<label class="col-form-label col-md-6 text-right"><span class="text-danger">*</span> Branch</label>
												<div class="form-group col-md-6">
													
													<select name="branch_id" id="branch_id" class="form-control" style="pointer-events:none ! important" required readonly>
														<option value="">- Select -</option>
														<?php 
															$getBranches = $this->branches_model->getBranchAll();
															
															foreach($getBranches as $branch)
															{
																$selected="";
																if(isset($edit_data[0]['branch_id']) && $edit_data[0]['branch_id'] == $branch["branch_id"] )
																{
																	$selected="selected='selected'";
																}
																?>
																<option value="<?php echo $branch["branch_id"];?>" <?php echo $selected;?>><?php echo ucfirst($branch["branch_name"]);?></option>
																<?php 
															}
														?>
													</select>
												</div>
											</div>
										</div>
									</div>
								</section>
								<!-- Header Section End Here-->

								<!-- Line level start here -->
								<section class="line-section mt-2">
									<div class="row mt-2 mb-2">
										<div class="col-md-12">
											<b>Lines</b>
										</div>
									</div>

									<div class="line-section-overflow">
										<table class="table table-bordered table-hover line_items" id="line_items">
											<thead>
												<tr>
													<?php 
														/* if($type == "add" || $type == "edit")
														{
															?>
															<th class="action-row tab-md-30"></th>
															<?php 
														}  */
													?>
													<th class="text-center tab-md-85">Line No</th>
													<th class="tab-md-150">Item</th>
													<th class="tab-md-150">Description</th>
													<th class="tab-md-150">Category</th>
													<th class="tab-md-100">Supplier Item</th>
													<?php
														if($type != "view")
														{
															?>
															<th class="text-center tab-md-100">PO Bal Qty</th>
															<?php 
														} 
													?>

													<th class="text-center tab-md-100">UOM </th>
													<th class="text-center tab-md-100">Received Qty</th>
													<th class="text-center tab-md-150">Note to Receiver</th>										
													<!-- <th class="text-center tab-md-150">Organization <span class="text-danger">*</span></th>											 -->
													<th class="text-center tab-md-150">Sub Inventory</th>											
													<th class="text-center tab-md-150">Locator</th>											
													<th class="text-center tab-md-150">Lot Number</th>											
													<th class="text-center tab-md-150">Serial Number</th>		
													<th class="text-center tab-md-150">Requested By</th>									
												</tr>
											</thead>
											<tbody>
												<?php 
													if($type == "edit" || $type == "view")
													{
														$organizationQry = "select organization_id,organization_name from org_organizations 
															where active_flag='Y'
															and coalesce(start_date,'".$this->date."') <= '".$this->date."'
															and coalesce(end_date,'".$this->date."') >= '".$this->date."'
															";
														$getOrganization = $this->db->query($organizationQry)->result_array();
														
														$requestedByQry = "select person_id,first_name,last_name from per_people_all 
															where active_flag='Y'
															";
														$getRequestedBy = $this->db->query($requestedByQry)->result_array();
														
														if( count($line_data) > 0)
														{
															$counter = 1;
															foreach($line_data as $lineItems)
															{
																$organization_id = $lineItems["rcv_organization_id"];
																
																$subInvQry = "select inventory_id,inventory_code,inventory_name from inv_item_sub_inventory 
																where active_flag='Y'
																and coalesce(start_date,'".$this->date."') <= '".$this->date."'
																and coalesce(end_date,'".$this->date."') >= '".$this->date."'
																and organization_id ='".$organization_id."'
																";
																$getSubInv = $this->db->query($subInvQry)->result_array();

																$inventory_id = $lineItems["rcv_sub_inventory_id"];
																$subInvQryLoc = "select locator_id,locator_no,locator_name from inv_item_locators 
																	where active_flag='Y'
																	and coalesce(start_date,'".$this->date."') <= '".$this->date."'
																	and coalesce(end_date,'".$this->date."') >= '".$this->date."'
																	and inventory_id ='".$inventory_id."'
																	";
																$getSubInvLocators = $this->db->query($subInvQryLoc)->result_array();
																
																?>
																<tr class="dataRowVal tbl_rows">
																	<td class="tab-md-30 text-center">
																		
																		<input type="hidden" name="receipt_line_id[]" value="<?php echo $lineItems["receipt_line_id"];?>" id="receipt_line_id<?php echo $counter;?>">
																		<input type="hidden" name="po_header_id[]" value="<?php echo $lineItems["po_header_id"];?>" id="po_header_id<?php echo $counter;?>">
																		<input type="hidden" name="po_line_id[]" value="<?php echo $lineItems["po_line_id"];?>" id="po_line_id<?php echo $counter;?>">
																		<input type="hidden" name="item_id[]" value="<?php echo $lineItems["item_id"];?>" id="item_id<?php echo $counter;?>">
																		<input type="hidden" name="category_id[]" value="<?php echo $lineItems["category_id"];?>" id="category_id<?php echo $counter;?>">
																		<input type="hidden" name="uom[]" value="<?php echo $lineItems["uom"];?>" id="uom<?php echo $counter;?>">
																		<input type="hidden" name="requested_by[]" value="<?php echo $lineItems["po_requested_by"];?>" id="requested_by<?php echo $counter;?>">
																		<input type="hidden" name="counter" value="<?php echo $counter;?>">
																		<input type="number" class="form-control" name="line_num[]" id="line_num<?php echo $counter;?>" value="<?php echo !empty($lineItems["line_num"]) ? $lineItems["line_num"] : $counter;?>">
																	</td>
																	<td class="tab-md-150">
																		<input type="text" class="form-control" readonly name="item_name[]" id="item_name<?php echo $counter;?>" value="<?php echo $lineItems["item_name"];?>">
																	</td>
																	<td class="tab-md-150">
																		<textarea class="form-control" rows="1" readonly name="description[]" id="description<?php echo $counter;?>"><?php echo $lineItems["item_description"];?></textarea>
																	</td>
																	<td class="tab-md-150">
																		<input type="text" class="form-control" readonly name="category_name[]" id="category_name<?php echo $counter;?>" value="<?php echo $lineItems["category_name"];?>">
																	</td>
																	<td class="tab-md-150">
																		<input type="text" class="form-control" name="supplier_item[]" id="supplier_item<?php echo $counter;?>" value="<?php echo $lineItems["supplier_item"];?>">
																	</td>
																	<?php
																		if($type != "view")
																		{
																			?>
																			<td class="tab-md-150">
																				<input type="number" class="form-control" readonly name="po_bal_qty[]" id="po_bal_qty<?php echo $counter;?>" value="<?php echo $lineItems["po_bal_qty"];?>">
																			</td>
																			<?php 
																		} 
																	?>
																	<td class="tab-md-100">
																		<input type="text" class="form-control" readonly name="uom_code[]" id="uom_code<?php echo $counter;?>" value="<?php echo $lineItems["uom_code"];?>">
																	</td>
																	<td class="tab-md-100">
																		<input type="number" class="form-control" name="received_qty[]" min='0' max="<?php echo $lineItems["po_bal_qty"];?>" id="received_qty<?php echo $counter;?>" value="<?php echo $lineItems["received_qty"];?>">
																	</td>
																	<td class="tab-md-150">
																		<input type="text" class="form-control" readonly name="note_to_receiver[]" id="note_to_receiver<?php echo $counter;?>" value="<?php echo $lineItems["note_to_receiver"];?>">
																	</td>
																	<?php /*
																		?>
																			<td class="tab-md-150">
																				<select class="form-control <?php #echo $searchDropdown; ?>" onchange="selectSubInventory(this.value,<?php echo $counter;?>)" name="organization_id[]" id="organization_id<?php echo $counter;?>">
																					<option value="">- Select -</option>
																					<?php 
																						foreach($getOrganization as $Organization)
																						{
																							$selected="";
																							if($lineItems["rcv_organization_id"] == $Organization["organization_id"])
																							{
																								$selected="selected='selected'";
																							}
																							?>
																							<option value="<?php echo $Organization["organization_id"];?>" <?php echo $selected;?>><?php echo $Organization["organization_name"];?></option>
																							<?php 
																						} 
																					?>
																				</select>
																			</td>
																		<?php
																	*/ ?>
																	
																	<td class="tab-md-150">
																		<select class="form-control <?php echo $searchDropdown; ?>" onchange="selectSubInventoryLocators(this.value,<?php echo $counter;?>)" name="sub_inventory_id[]" id="sub_inventory_id<?php echo $counter;?>">
																			<option value="">- Select -</option>
																			<?php 
																				foreach($getSubInv as $subInv)
																				{
																					$selected="";
																					if($lineItems["rcv_sub_inventory_id"] == $subInv["inventory_id"])
																					{
																						$selected="selected='selected'";
																					}
																					?>
																					<option value="<?php echo $subInv["inventory_id"];?>" <?php echo $selected;?>><?php echo $subInv["inventory_code"];?></option>
																					<?php 
																				} 
																			?>
																		</select>
																	</td>
																	<td class="tab-md-150">
																		<select class="form-control <?php echo $searchDropdown; ?>" name="locator_id[]" id="locator_id<?php echo $counter;?>">
																			<option value="">- Select -</option>
																			<?php 
																				foreach($getSubInvLocators as $subInvLoc)
																				{
																					$selected="";
																					if($lineItems["rcv_locator_id"] == $subInvLoc["locator_id"])
																					{
																						$selected="selected='selected'";
																					}
																					?>
																					<option value="<?php echo $subInvLoc["locator_id"];?>" <?php echo $selected;?>><?php echo $subInvLoc["locator_no"];?></option>
																					<?php 
																				} 
																			?>
																		</select>
																	</td>
																	<td class="tab-md-150">
																		<input type="text" class="form-control" name="lot_number[]" id="lot_number<?php echo $counter;?>" value="<?php echo $lineItems["rcv_lot_number"];?>">
																	</td>
																	<td class="tab-md-150">
																		<input type="text" class="form-control" name="serial_number[]" id="serial_number<?php echo $counter;?>" value="<?php echo $lineItems["rcv_serial_number"];?>">
																	</td>
																	<td class="tab-md-150">
																		<input type="text" class="form-control" readonly name="requested_by_name[]" id="requested_by_name<?php echo $counter;?>" value="<?php echo $lineItems["first_name"];?>">
																	</td>
																</tr>
																<?php
																$counter++;
															} 
														} 
													}
												?>
											</tbody>
										</table>
									</div>
									
									<div class="row mt-2 mb-2">
										<div class="col-md-12">
											<div class="line-items-error"></div>
										</div>
									</div>
								</section>
							</fieldset>
						</div>
					</form>
				   
					<script>
						var counter = 1;
						var i=1;

						function selectPurchaseOrder(val)
						{
							$('.line-items-error').text('');
							$('.tbl_rows').remove();

							var flag = 0;

							$.ajax({
								url: "<?php echo base_url('purchase_receipt/getReceiptLines') ?>/"+val,
								type: "GET",
								data:{
									'<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
								},
								datatype: "JSON",
								success: function(d)
								{
									data = JSON.parse(d);
									var countKey = Object.keys(data['items']).length;
									
									if(countKey > 0)
									{
										$.each(data['items'], function(i, item) 
										{
											/* $("table.line_items").find('input[name^="item_id"]').each(function () 
											{
												if(item.item_id  == +$(this).val())
												{
													flag = 1;
												}
											}); */

											if (item.po_organization_id) {
												$('#organization_id').val(item.po_organization_id); 
											}
											else{
												$('#organization_id').val('');
											}
											if (item.po_branch_id) {
												$('#branch_id').val(item.po_branch_id); 
											}
											else{
												$('#branch_id').val('');
											}
											
											
											if(flag == 0)
											{
												var po_organization_id=item.po_organization_id
												var po_header_id = item.po_header_id;
												var po_line_id = item.po_line_id;
												var item_id = item.item_id;
												var category_id = item.category_id;
												var uom = item.uom;
												var line_num = item.line_num;
												var requested_by_id = item.po_requested_by;

												var item_name = item.item_name;
												var item_description = item.item_description;
												var category_name = item.category_name;
												var supplier_item = item.supplier_item;
												var po_bal_qty = item.po_bal_qty;
												var uom_code = item.uom_code;
												var organization_id = item.organization_id;

												if(item.first_name != null)
												{
													var requested_by = item.first_name;
												}else{
													var requested_by = '';
												}

												if(item.note_to_receiver != null)
												{
													var note_to_receiver = item.note_to_receiver;
												}else{
													var note_to_receiver = "";	
												}

												// //Organization
												// var select_organization = "";
												// select_organization += '<select class="form-control searchDropdown" required onchange="selectSubInventory(this.value,'+counter+')" name="organization_id[]" id="organization_id'+counter+'">';
												// select_organization += '<option value="">- Select -</option>';
												// for(a=0;a<data['organization'].length;a++)
												// {
												// 	var selected='';

												// 	if(organization_id == data['organization'][a].organization_id)
												// 	{
												// 		var selected='selected="selected"';
												// 	}
												// 	select_organization += '<option value="' + data['organization'][a].organization_id + '" '+selected+'>' + data['organization'][a].organization_name+'</option>';
												// }
												// select_organization += '</select>';
												// selectSubInventory(organization_id,counter);
												selectSubInventory(po_organization_id,counter);

												var select_sub_inv = "";
												select_sub_inv += '<select class="form-control searchDropdown" onchange="selectSubInventoryLocators(this.value,'+counter+')" name="sub_inventory_id[]" id="sub_inventory_id'+counter+'">';
												select_sub_inv += '<option value="">- Select -</option>';
												for(a=0;a<data['subInvQry'].length;a++)
												{
													var selected='';
													select_sub_inv += '<option value="' + data['subInvQry'][a].inventory_id + '" '+selected+'>' + data['subInvQry'][a].inventory_code+'</option>';
												}
												select_sub_inv += '</select>';

												var select_inventory_locators = "";
												select_inventory_locators += '<select class="form-control searchDropdown" name="locator_id[]" id="locator_id'+counter+'">';
												select_inventory_locators += '<option value="">- Select -</option>';
												select_inventory_locators += '</select>';

												var newRow = $("<tr class='dataRowVal tbl_rows'> ");
												var cols = "";

												cols += "<td class='tab-md-30 text-center'>"+
													"<input type='hidden' name='receipt_line_id[]' value='0' id='receipt_line_id"+counter+"'>"+
													"<input type='hidden' name='po_header_id[]' value='"+po_header_id+"' id='po_header_id"+counter+"'>"+
													"<input type='hidden' name='po_line_id[]' value='"+po_line_id+"' id='po_line_id"+counter+"'>"+
													"<input type='hidden' name='item_id[]' value='"+item_id+"' id='item_id"+counter+"'>"+
													"<input type='hidden' name='category_id[]' value='"+category_id+"' id='category_id"+counter+"'>"+
													"<input type='hidden' name='uom[]' value='"+uom+"' id='uom"+counter+"'>"+
													"<input type='hidden' name='requested_by[]' value='"+requested_by_id+"' id='requested_by"+counter+"'>"+
													"<input type='hidden' name='counter' value='"+counter+"'>"+
													"<input type='number' class='form-control' readonly name='line_num[]' id='line_num"+ counter +"' value='"+line_num+"'>"+
													"</td>";

												cols += "<td class='tab-md-150'>" 
													+"<input type='text' class='form-control' readonly name='item_name[]' id='item_name"+ counter +"' value='"+item_name+"'>"
													+"</td>";

												cols += "<td class='tab-md-150'>" 
													+"<textarea class='form-control' rows='1' readonly name='description[]' id='description"+ counter +"'>"+item_description+"</textarea>"
													+"</td>";

												cols += "<td class='tab-md-150'>" 
													+"<input type='text' class='form-control' readonly name='category_name[]' id='category_name"+ counter +"' value='"+category_name+"'>"
													+"</td>";

												cols += "<td class='tab-md-150'>" 
													+"<input type='text' class='form-control' name='supplier_item[]' id='supplier_item"+ counter +"' value='"+supplier_item+"'>"
													+"</td>";

												cols += "<td class='tab-md-100'>" 
													+"<input type='number' class='form-control' readonly name='po_bal_qty[]' id='po_bal_qty"+ counter +"' value='"+po_bal_qty+"'>"
													+"</td>";

												cols += "<td class='tab-md-100'>" 
													+"<input type='text' class='form-control' readonly name='uom_code[]' id='uom_code"+counter+"' value='"+uom_code+"'>"
													+"</td>";
										
												cols += "<td class='tab-md-100'>" 
													+"<input type='number' class='form-control' name='received_qty[]' min='0' max='"+po_bal_qty+"' id='received_qty"+ counter +"' value=''>"
													+"</td>";

												cols += "<td class='tab-md-150'>" 
													+"<input type='text' class='form-control' readonly name='note_to_receiver[]' id='note_to_receiver"+ counter +"' value='"+note_to_receiver+"'>"
													+"</td>";

												// cols += '<td class="tab-md-150">'+select_organization+'</td>';

												cols += '<td class="tab-md-150">'+select_sub_inv+'</td>';
												cols += '<td class="tab-md-150">'+select_inventory_locators+'</td>';
						
												cols += "<td class='tab-md-150'>" 
													+"<input type='text' class='form-control' name='lot_number[]' id='lot_number"+ counter +"' value=''>"
													+"</td>";

												cols += "<td class='tab-md-150'>" 
													+"<input type='text' class='form-control' name='serial_number[]' id='serial_number"+ counter +"' value=''>"
													+"</td>";

												cols += "<td class='tab-md-150'>" 
													+"<input type='text' class='form-control' readonly name='requested_by_name[]' id='requested_by_name"+counter+"' value='"+requested_by+"'>"
													+"</td>";
												
												cols += "</tr>";
												counter++;
												
												$(document).ready(function()
												{ 
													$(".searchDropdown").select2();
												});

												newRow.html(cols);
												$("table.line_items").append(newRow);
												i++;
											}
											else
											{
												$('#err_product').text('Item already assigned!').animate({opacity: '0.0'}, 2000).animate({opacity: '0.0'}, 1000).animate({opacity: '1.0'}, 2000);
											}
										});
									}
									else
									{
										$('#err_product').text('No Data Found!').animate({opacity: '0.0'}, 2000).animate({opacity: '0.0'}, 1000).animate({opacity: '1.0'}, 2000);
									}
								},
								error: function(xhr, status, error) {
									$('#err_product').text('Enter Product Code / Name').animate({opacity: '0.0'}, 2000).animate({opacity: '0.0'}, 1000).animate({opacity: '1.0'}, 2000);
								}
							});
						}

						function selectSubInventory(organization_id,counter)
						{
							if(organization_id)
							{
								$.ajax({
									type: "POST",
									url:"<?php echo base_url().'purchase_receipt/selectSubInventory';?>",
									data: { organization_id: organization_id }
								}).done(function( result ) 
								{   $("#sub_inventory_id"+counter).html(result);
								});
							}
						}

						function selectSubInventoryLocators(inventory_id,counter)
						{
							if(inventory_id)
							{
								$.ajax({
									type: "POST",
									url:"<?php echo base_url().'purchase_receipt/selectSubInventoryLocators';?>",
									data: { inventory_id: inventory_id }
								}).done(function( result ) 
								{   $("#locator_id"+counter).html(result);
								});
							}
						}
				
						function deleteRow(po_line_id,counter)
						{
							var confirmBox = confirm("Are you sure you want to delete the line?");

							if(confirmBox)
							{
								if(po_line_id)
								{
									$.ajax({
										type: "POST",
										url:"<?php echo base_url().'purchase_order/deleteLineItems';?>",
										data: { po_line_id: po_line_id }
									}).done(function( result ) 
									{   
										if(result == 1)
										{
											$(".tabRow"+counter).remove();
											calculateLineRow(counter);
											calculateHeaderTotal();
										}
									});
								}
							}
						}
						
						$("table.line_items").on("click", "a.deleteRow,a.deleteRow1", function(event) 
						{
							$(this).closest("tr").remove();
						});

						

						function selectUom(uom_id,counter)
						{
							$( "#uom_id_"+counter).val(uom_id);
						}

						function selectDiscountTypes(discount_type,counter)
						{
							if(discount_type == "Amount" || discount_type == "Percentage")
							{
								$("#discount"+counter).removeAttr('readonly');
								$("#discount_reason"+counter).removeAttr('readonly');
							}
							else
							{
								$("#discount"+counter).attr('readonly',true);
								$("#discount_reason"+counter).attr('readonly',true);

								$("#discount"+counter).val('');
								$("#discount_reason"+counter).val('');
							}
							calculateLineRow(counter);
							calculateHeaderTotal();
						}

						function selectTax(val,counter)
						{
							calculateLineRow(counter);
							calculateHeaderTotal();
						}

						$("table.line_items").on("input keyup change", 'input[name^="base_price[]"], input[name^="quantity[]"], input[name^="discount[]"]', function (event) 
						{
							var row = $(this).closest("tr");
							var counter = +row.find('input[name^="counter"]').val();
							
							calculateLineRow(counter);
							calculateHeaderTotal();
						});

						function calculateLineRow(counter) 
						{
							var quantity = ($("#quantity"+counter).val()) ? $("#quantity"+counter).val() : 0;
							var tax = ($("#tax"+counter).val()) ? $("#tax"+counter).val() : 0;
							var base_price = parseFloat($("#base_price"+counter).val()).toFixed(2);
							
							var discount_type = $("#discount_type"+counter).val();
							var discount = $("#discount"+counter).val();

							if(discount_type =='Amount') 
							{
								var discount_price = parseFloat(base_price - discount).toFixed(2);
								$("#price"+counter).val(discount_price);
							}
							else if(discount_type =='Percentage') 
							{
								var discount_price = discount / 100 * base_price;
								var price = parseFloat(base_price - discount_price).toFixed(2);
								$("#price"+counter).val(price);
							}
							else
							{
								$("#price"+counter).val(base_price);
							}
							var price = $("#price"+counter).val();
							var line_value = parseFloat(quantity * price).toFixed(2);
							$("#line_value"+counter).val(line_value);

							var total_tax = parseFloat(tax / 100 * line_value).toFixed(2);
							$("#total_tax"+counter).val(total_tax);

							var total = parseFloat(line_value) + parseFloat(total_tax);
							
							$("#total"+counter).val(total);
						}

						function calculateHeaderTotal() 
						{
							var totalOrderAmount = 0;
							var totalTax = 0;
							var total = 0;

							$("table.line_items").find('input[name^="line_value[]"]').each(function () {
								totalOrderAmount += +$(this).val();
							});
							
							$("table.line_items").find('input[name^="total_tax[]"]').each(function () {
								totalTax += +$(this).val();
							});
							
							$("table.line_items").find('input[name^="total[]"]').each(function () {
								total += +$(this).val();
							});

							$('#header_order_amount').val(totalOrderAmount.toFixed(2));
							$('#header_tax').val(totalTax.toFixed(2));
							$('#header_total').val(total.toFixed(2));
						}	
					</script>
					
					<?php
				}
				else
				{ 
					?>
					
					<!-- Buttons start here -->
					<div class="row">
						<div class="col-md-6"><h3><b><?php echo $page_title;?></b></h3></div>
						<div class="col-md-6 float-right text-right">
							<?php
								if($purchaseReceiptMenu['create_edit_only'] == 1 || $this->user_id == 1)
								{
									?>
									<a href="<?php echo base_url(); ?>purchase_receipt/managePurchaseReceipt/add" class="btn btn-info btn-sm">
										Create Purchase Receipt
									</a>
									<?php 
								} 
							?>
						</div>
					</div>
					<!-- Buttons end here -->
					
					<!-- Filters start here -->
					<form action="" class="form-validate-jquery" method="get">
						<div class="row mt-3">
							<div class="col-md-4">
								<div class="row">
									<label class="col-form-label col-md-5 text-right">Receipt #</label>
									<div class="form-group col-md-7">
										<input type="search" class="form-control" autocomplete="off" name="receipt_number" value="<?php echo !empty($_GET['receipt_number']) ? $_GET['receipt_number'] :""; ?>" placeholder="Receipt #">
									</div>
								</div>
							</div>

							<div class="col-md-4">
								<div class="row">
									<label class="col-form-label col-md-5 organization_id"><span class="text-danger">*</span> Organization</label>
									<div class="form-group col-md-7">
										<?php
											$getOrganization = $this->organization_model->getOrgAll();
										?>
										
										<select id="organization_id" onchange="getBranches(this.value);" name="organization_id" class="form-control searchDropdown" required>
											<option value="">- Select -</option>
											<?php 
												foreach($getOrganization as $row)
												{
													$selected="";
													if(isset($_GET["organization_id"]) && $_GET["organization_id"] == $row['organization_id'] )
													{
														$selected="selected='selected'";
													}
													?>
													<option value="<?php echo $row['organization_id'];?>" <?php echo $selected;?>><?php echo $row['organization_code'];?> - <?php echo $row['organization_name'];?></option>
													<?php 
												} 
											?>
										</select>
									</div>
								</div>
							</div>

							<div class="col-md-4">
								<div class="row">
									<label class="col-form-label col-md-5 branch_id"><span class="text-danger">*</span> Branch</label>
									<div class="form-group col-md-7">
										
										
										<select id="branch_id" name="branch_id" class="form-control searchDropdown" required>
											<option value="">- Select -</option>
											<?php

												if(isset($_GET) &&  !empty($_GET)){
													$organization_id=$_GET['organization_id'];

													$getBranches = $this->branches_model->getOrgBranch($organization_id);
													
													foreach($getBranches as $branch)
													{
														$selected="";
														if(isset($_GET["branch_id"]) && $_GET["branch_id"] == $branch['branch_id'] )
														{
															$selected="selected='selected'";
														}
														?>
														<option value="<?php echo $branch['branch_id'];?>" <?php echo $selected;?>><?php echo $branch['branch_name'];?></option>
														<?php 
													} 
												}
												
											?>
										</select>
									</div>
								</div>
							</div>
							
							<script>
								function getBranches(organization_id)
								{	
									
									$.ajax({
										type: "POST",
										url: "<?php echo base_url().'branches/getOrgBranches';?>",
										data: {organization_id},
										success: function (data) {
											$('#branch_id').html(data);  
										}
									});

								}
							</script>
						
						</div>

						<div class="row mt-2">	
							<div class="col-md-4">
								<div class="row">
									<label class="col-form-label col-md-5 text-right">PO Number</label>
									<div class="form-group col-md-7">
										<?php
											$query1 = "select po_header_id,po_number from po_headers
											where po_status='Approved' order by po_number asc";
											$getPO = $this->db->query($query1)->result_array();
										?>
										
										<select id="po_header_id" name="po_header_id" class="form-control searchDropdown">
											<option value="">- Select -</option>
											<?php 
												foreach($getPO as $po)
												{
													$selected="";
													if(isset($_GET["po_header_id"]) && $_GET["po_header_id"] == $po['po_header_id'] )
													{
														$selected="selected='selected'";
													}
													?>
													<option value="<?php echo $po['po_header_id'];?>" <?php echo $selected;?>><?php echo ucfirst($po['po_number']);?></option>
													<?php 
												} 
											?>
										</select>
									</div>
								</div>
							</div>
							<div class="col-md-4">
								<div class="row">
									<label class="col-form-label col-md-5 text-right">From Date</label>
									<div class="form-group col-md-7">
										<input type="text" name="from_date" id="from_date" class="form-control" readonly value="<?php echo !empty($_GET['from_date']) ? $_GET['from_date'] : ""; ?>" placeholder="From Date">
									</div>
								</div>
							</div>

							<div class="col-md-4">
								<div class="row">
									<label class="col-form-label col-md-5 text-right">To Date</label>
									<div class="form-group col-md-7">
										<input type="text" name="to_date" id="to_date" class="form-control" readonly value="<?php echo !empty($_GET['to_date']) ? $_GET['to_date'] :""; ?>" placeholder="To Date">
									</div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12 text-right">
								<button type="submit" class="btn btn-info">Search <i class="fa fa-search" aria-hidden="true"></i></button>
								<a href="<?php echo base_url(); ?>purchase_receipt/managePurchaseReceipt" title="Clear" class="btn btn-default">Clear</a>
							</div>
						</div>
					</form>
					<!-- Filters end here -->
					
					<?php 
						if(isset($_GET) &&  !empty($_GET))
						{
							?>
							<!-- Page Item Show start -->
							<div class="row mt-3">
								<div class="col-md-10"></div>

								<div class="col-md-2 float-right text-right">
									<?php 
										$redirect_url = substr($_SERVER['REQUEST_URI'],'1');
									?>
									<input type="hidden" id="redirect_url" value="<?php echo $redirect_url; ?>"/>
															
									<div class="filter_page">
										<label>
											<span>Show :</span> 
											<select name="filter" onchange="location.href='<?php echo base_url(); ?>admin/sort_itemper_page/'+$(this).val()+'?redirect=<?php echo $redirect_url; ?>'">
												<?php 
													$pageLimit = isset($_SESSION['PAGE']) ? $_SESSION['PAGE'] : NULL;
													foreach($this->items_per_page as $key => $value)
													{
														$selected="";
														if($key == $pageLimit){
															$selected="selected=selected";
														}
														?>
														<option value="<?php echo $key; ?>" <?php echo $selected;?>><?php echo $value; ?></option>
														<?php 
													} 
												?>
											</select>
										</label>
									</div>
								</div>
							</div>
						    <!-- Page Item Show start -->

							<!-- Table start here -->
							<div class="new-scroller">
								<table id="myTable" class="table table-bordered table-hover">
									<thead>
										<tr>
											<th class="text-center">View</th>
											<th class="tab-md-140">Organization</th>
											<th class="tab-md-140">Branch</th>
											<th class="tab-md-120">Receipt #</th>
											<th class="tab-md-140">PO #</th>
											<th class="tab-md-120">Receipt Desc</th>
											<th class="tab-md-140">Receipt Date</th>
											<th class="tab-md-140">Note to Receiver</th>
											<!-- <th class="tab-md-140">Supplier Invoice Num</th>
											<th class="tab-md-140">Supplier Invoice Date</th>
											<th class="tab-md-140">Shipment Ref</th> -->
										</tr>
									</thead>
									<tbody>
										<?php 	
											$i=0;
											$firstItem = $first_item;
											$totalValue = 0;
											foreach($resultData as $row)
											{
												?>
												<tr>
													<td  class='text-center' style="width:90px;">
														<?php
															if($purchaseReceiptMenu['read_only'] == 1 || $this->user_id == 1)
															{
																?>
																<a href="<?php echo base_url(); ?>purchase_receipt/managePurchaseReceipt/view/<?php echo $row['receipt_header_id'];?>">
																	<i class="fa fa-eye"></i>
																</a>
																<?php 
															} 
														?>
													</td>
													<td><?php echo $row['organization_name'];?></td>
													<td><?php echo $row['branch_name'];?></td>
													<td><?php echo $row['receipt_number'];?></td>
													<td><?php echo $row['po_number'];?></td>
													<td><?php echo $row['description'];?></td>
													
													<td>
														<?php echo date(DATE_FORMAT,strtotime($row['receipt_date']));?>
													</td>

													<td><?php echo $row['note_to_receiver'];?></td>
													
													<?php /* <td><?php echo $row['supplier_invoice_number'];?></td>
													<td>
														<?php 
															if(!empty($row['supplier_invoice_date']) && $row['supplier_invoice_date'] !=NULL)
															{
																echo date(DATE_FORMAT,strtotime($row['supplier_invoice_date']));
															}
														?>
													</td>
													<td><?php echo $row['shipment_ref'];?></td> */ ?>
												</tr>
												<?php 
												$i++;
											}
										?>
									</tbody>
								</table>
								<?php 
									if(count($resultData) == 0)
									{
										?>
										<div class="text-center">
											<img src="<?php echo base_url();?>uploads/nodata.png">
										</div>
										<?php 
									} 
								?>
							</div>
							<!-- Table end here -->

							<!-- Pagination start here -->
							<?php 
								if (count($resultData) > 0) 
								{
									?>
									<div class="row">
										<div class="col-md-4 showing-count">
											Showing <?php echo $starting;?> to <?php echo $ending;?> of <?php echo $totalRows;?> entries
										</div>
										<!-- pagination start here -->
										<?php 
											if( isset($pagination) )
											{
												?>	
												<div class="col-md-8" class="admin_pagination" style="float:right;padding: 0px 20px 0px 0px;"><?php foreach ($pagination as $link){echo $link;} ?></div>
												<?php
											}
										?>
										<!-- pagination end here -->
										
									</div>
									<?php 
								} 
							?>
							<!-- Pagination end here -->
							<?php 
						} 
					?>
					<?php 
				} 
			?>
		</div>
	</div><!-- Card end-->
</div><!-- Content end-->
