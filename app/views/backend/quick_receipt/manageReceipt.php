<?php 
	$quick_receipt = accessMenu(quick_receipt);
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
												echo $page_title;
												
											?>
											
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
									<a href="<?php echo base_url(); ?>quick_receipt/manageReceipt" class="btn btn-default btn-sm">Close</a>
								</div>
							</div>
							<!-- Buttons end here -->
							
							<fieldset <?php echo $fieldSetDisabled;?>>
								<!-- Header Section Start Here-->
								<section class="header-section">
									<div class="row">
										<div class="col-md-4">
											<div class="row">
												<label class="col-form-label col-md-6 text-right">Receipt Number</label>
												<div class="form-group col-md-6">
													<input type="text" name="po_number" id="po_number" readonly autocomplete="off" class="form-control no-outline" value="<?php echo isset($edit_data[0]['receipt_number']) ? $edit_data[0]['receipt_number'] : NULL;?>" placeholder="">
												</div>
											</div>
											<div class="row">
												<label class="col-form-label col-md-6 text-right"><span class="text-danger">*</span> Receipt Date</label>
												<div class="form-group col-md-6">
													<input type="text" name="receipt_date" id="receipt_date" readonly autocomplete="off" required class="form-control -no-outline default_date" value="<?php echo isset($edit_data[0]['receipt_date']) ? date("d-M-Y",strtotime($edit_data[0]['receipt_date'])) : date("d-M-Y");?>" placeholder="">
												</div>
											</div>
											<div class="row">
												<label class="col-form-label col-md-6 text-right">Bill Number</label>
												<div class="form-group col-md-6">
													<input type="text" name="bill_number" id="bill_number" autocomplete="off" class="form-control" value="<?php echo isset($edit_data[0]['bill_number']) ? $edit_data[0]['bill_number'] : NULL;?>" placeholder="Bill Number">
												</div>
											</div>
											
											<div class="row">
												<label class="col-form-label col-md-6 text-right">Buyer</label>
												<div class="form-group col-md-6">
													<?php 
														$empQry = "select person_id,first_name,middle_name,last_name from per_people_all 
															where 
															active_flag='Y'
															order by per_people_all.first_name asc";

														$getEmployee = $this->db->query($empQry)->result_array();
													?>
													<select name="buyer_id" id="buyer_id" class="form-control <?php echo $searchDropdown;?>">
														<option value="">- Select -</option>
														<?php 
															foreach($getEmployee as $row)
															{
																$selected="";
																if(isset($edit_data[0]['buyer_id']) && $edit_data[0]['buyer_id'] == $row["person_id"] )
																{
																	$selected="selected='selected'";
																}
																?>
																<option value="<?php echo $row["person_id"];?>" <?php echo $selected;?>><?php echo ucfirst($row["first_name"]);?> <?php echo ucfirst($row["last_name"]);?></option>
																<?php 
															}
														?>
													</select>
												</div>
											</div>
										</div>

										<div class="col-md-4">
											<div class="row">
												<label class="col-form-label col-md-6 text-right"><span class="text-danger">*</span> Organization</label>
												<div class="form-group col-md-6">
													<select name="organization_id" id="organization_id" onchange="getBranches(this.value);" class="form-control <?php echo $searchDropdown;?>" required>
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
											<div class="row">
												<label class="col-form-label col-md-6 text-right"><span class="text-danger">*</span> Branch</label>
												<div class="form-group col-md-6">
													
													<select name="branch_id" id="branch_id" class="form-control <?php echo $searchDropdown;?>" required>
														<option value="">- Select -</option>
														<?php 
															$getBranches = $this->branches_model->getBranchAll();

															if($type == 'edit' || $type == 'view')
															{
															
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
															}
														?>
													</select>
												</div>
											</div>
											<div class="row">
												<label class="col-form-label col-md-6 text-right"><span class="text-danger">*</span> Supplier</label>
												<div class="form-group col-md-6">
													<?php 
														$supplierQry = "select supplier_id,supplier_name from sup_suppliers 
															where 
															active_flag='Y'
															order by sup_suppliers.supplier_name asc";

														$getSuppliers = $this->db->query($supplierQry)->result_array();	
													?>
													<select name="supplier_id" id="supplier_id" onchange="selectSupplierSite(this.value);" required class="form-control <?php echo $searchDropdown;?>">
														<option value="">- Select -</option>
														<?php 
															foreach($getSuppliers as $row)
															{
																$selected="";
																if(isset($edit_data[0]['supplier_id']) && $edit_data[0]['supplier_id'] == $row["supplier_id"] )
																{
																	$selected="selected='selected'";
																}
																?>
																<option value="<?php echo $row["supplier_id"];?>" <?php echo $selected;?>><?php echo ucfirst($row["supplier_name"]);?></option>
																<?php 
															} 
														?>
													</select>
													<script>
														function selectSupplierSite(val)
														{
															selectSupplier(val);
															
															if(val !='')
															{
																$.ajax({
																	type: "POST",
																	url:"<?php echo base_url().'quick_receipt/ajaxSelectSupplierSite';?>",
																	data: { id: val }
																}).done(function( msg ) {   
																	
																	$( "#supplier_site_id" ).html(msg);
																});
															}
															/* else 
															{ 
																alert("No State under this Country!");
															} */
														}

														function selectSupplier(val)
														{
															if(val !='')
															{
																$.ajax({
																	type: "POST",
																	url:"<?php echo base_url().'quick_receipt/getAjaxSupplierDetails';?>",
																	data: { id: val }
																}).done(function( msg ) 
																{   
																	$( "#supplier_contact" ).val(msg);
																});
															}
															else 
															{ 
																$( "#supplier_contact" ).val('');
															}
														}

														function getAjaxSupplierSiteDetails(val)
														{
															if(val !='')
															{
																var supplier_id = $("#supplier_id").val();

																$.ajax({
																	type: "POST",
																	url:"<?php echo base_url().'quick_receipt/getAjaxSupplierSiteDetails';?>",
																	data: { supplier_id:supplier_id,id: val}
																}).done(function( msg ) 
																{   
																	$( "#supplier_contact" ).val(msg);
																});
															}
															else 
															{ 
																$( "#supplier_contact" ).val('');
															}
														}

														
													</script>
												</div>
											</div>
											<div class="row">
												<label class="col-form-label col-md-6 text-right"><span class="text-danger">*</span> Supplier Site</label>
												<div class="form-group col-md-6">
													<select name="supplier_site_id" id="supplier_site_id" onchange="getAjaxSupplierSiteDetails(this.value);" required class="form-control <?php echo $searchDropdown;?>">
														<option value="">- Select -</option>
														<?php 
															if($type == "edit" || $type == "view")
															{
																$supplier_id = $edit_data[0]['supplier_id'];

																$getSupplierSite =  $this->db->query("select 
																sup_supplier_sites.supplier_site_id,
																sup_supplier_sites.site_name from sup_supplier_sites
																where 
																	sup_supplier_sites.supplier_id='".$supplier_id."' 
																	order by sup_supplier_sites.site_name asc
																")->result_array();

																foreach($getSupplierSite as $SupplierSite)
																{
																	$selected="";
																	if(isset($edit_data[0]['supplier_site_id']) && $edit_data[0]['supplier_site_id'] == $SupplierSite["supplier_site_id"] )
																	{
																		$selected="selected='selected'";
																	}
																	?>
																	<option value="<?php echo $SupplierSite["supplier_site_id"];?>" <?php echo $selected;?>><?php echo ucfirst($SupplierSite["site_name"]);?></option>
																	<?php 
																}
															}
														?>
													</select>
												</div>
											</div>
											
										</div>
										
										<div class="col-md-4">
											<div class="row">
												<label class="col-form-label col-md-6 text-right">Order Amount </label>
												<div class="form-group col-md-6">
													<input type="text" name="header_order_amount" id="header_order_amount" readonly autocomplete="off" required class="form-control no-outline" value="<?php echo isset($edit_data[0]['order_amount']) ? number_format($edit_data[0]['order_amount'],DECIMAL_VALUE,'.','') : "0.00";?>" placeholder="">
												</div>
											</div>
											<div class="row">
												<label class="col-form-label col-md-6 text-right">Tax</label>
												<div class="form-group col-md-6">
													<input type="text" name="header_tax" id="header_tax" readonly autocomplete="off" class="form-control no-outline" value="<?php echo isset($edit_data[0]['total_tax']) ? number_format($edit_data[0]['total_tax'],DECIMAL_VALUE,'.','') : "0.00";?>" placeholder="">
												</div>
											</div>
											<div class="row">
												<label class="col-form-label col-md-6 text-right">Total</label>
												<div class="form-group col-md-6">
													<input type="text" name="header_total" id="header_total" readonly autocomplete="off" class="form-control no-outline" value="<?php echo isset($edit_data[0]['total']) ? number_format($edit_data[0]['total'],DECIMAL_VALUE,'.','') : "0.00";?>" placeholder="">
												</div>
											</div>
											<div class="row">
												<label class="col-form-label col-md-6 text-right">Description</label>
												<div class="form-group col-md-6">
													<textarea name="header_description" id="header_description" rows="1" <?php echo $fieldReadonly;?> autocomplete="off" class="form-control single_quotes" placeholder="Description"><?php echo isset($edit_data[0]['description']) ? $edit_data[0]['description'] : NULL;?></textarea>
												</div>
											</div>
										</div>

										<?php /* <div class="col-md-4">
											<div class="row">
												<label class="col-form-label col-md-6 text-right">Currency <span class="text-danger">*</span></label>
												<div class="form-group col-md-5">
													<?php 
														$currencyQry = "select currency_id,currency from geo_currencies 
															where 
															active_flag='Y'
															order by geo_currencies.currency asc";
														$getCurrency = $this->db->query($currencyQry)->result_array();
													?>
													<select name="po_currency" id="po_currency" required class="form-control <?php echo $searchDropdown;?>">
														<option value="">- Select -</option>
														<?php 
															foreach($getCurrency as $row)
															{
																$selected="";
																if(isset($edit_data[0]['po_currency']) && $edit_data[0]['po_currency'] == $row["currency_id"] )
																{
																	$selected="selected='selected'";
																}
																?>
																<option value="<?php echo $row["currency_id"];?>" <?php echo $selected;?>><?php echo $row["currency"];?></option>
																<?php 
															} 
														?>
													</select>
												</div>
											</div>
										</div> */ ?>
									</div>
									
									

									<div class="row">
										<?php /* <div class="col-md-4">
											<div class="row">
												<label class="col-form-label col-md-6 text-right">Status</label>
												<div class="form-group col-md-6">
													<input type="text" name="header_status" id="header_status" readonly autocomplete="off" class="form-control no-outline" value="<?php echo isset($edit_data[0]['po_status']) ? $edit_data[0]['po_status'] : "Draft";?>" placeholder="">
												</div>
											</div>
										</div> */ ?>
										<input type="hidden" name="supplier_contact" id="supplier_contact" <?php echo $fieldReadonly;?> autocomplete="off" class="form-control single_quotes" value="<?php echo isset($edit_data[0]['supplier_contact']) ? $edit_data[0]['supplier_contact'] : NULL;?>" placeholder="">
										<input type="hidden" name="payment_term_id" id="payment_term_id" class="form-control" value="<?php echo isset($edit_data[0]['payment_term_id']) ? $edit_data[0]['payment_term_id'] : NULL;?>" placeholder="">
										<input type="hidden" name="header_note_to_receiver" id="header_note_to_receiver" class="form-control" value="<?php echo isset($edit_data[0]['header_note_to_receiver']) ? $edit_data[0]['header_note_to_receiver'] : NULL;?>" placeholder="">
										<input type="hidden" name="receipt_status" id="receipt_status" class="form-control" value="<?php echo isset($edit_data[0]['receipt_status']) ? $edit_data[0]['receipt_status'] : NULL;?>" placeholder="">
										<input type="hidden" name="po_currency" id="po_currency" class="form-control" value="<?php echo isset($edit_data[0]['po_currency']) ? $edit_data[0]['po_currency'] : NULL;?>" placeholder="">
										<?php /* <div class="col-md-4">
											<div class="row">
												<label class="col-form-label col-md-6 text-right">Supplier Contact</label>
												<div class="form-group col-md-6">
													<input type="text" name="supplier_contact" id="supplier_contact" <?php echo $fieldReadonly;?> autocomplete="off" class="form-control single_quotes" value="<?php echo isset($edit_data[0]['supplier_contact']) ? $edit_data[0]['supplier_contact'] : NULL;?>" placeholder="">
												</div>
											</div>
										</div> */ ?>
										
									</div>

									<div class="row">
										<?php /* <div class="col-md-4">
											<div class="row">
												<label class="col-form-label col-md-6 text-right"><span class="text-danger">*</span> Payment Terms</label>
												<div class="form-group col-md-6">
													<?php 
														$paymentTermQry = "select payment_term_id,payment_term from payment_terms 
															where 
															active_flag='Y'
															order by payment_terms.payment_term asc";
														$paymentTerms = $this->db->query($paymentTermQry)->result_array();
														
													?>
													<select name="payment_term_id" id="payment_term_id" <?php echo $fieldDisabled;?> required class="form-control resource_type <?php echo $searchDropdown;?>">
														<option value="">- Select -</option>
														<?php 
															foreach($paymentTerms as $row)
															{
																$selected="";
																if(isset($edit_data[0]['payment_term_id']) && $edit_data[0]['payment_term_id'] == $row['payment_term_id'] )
																{
																	$selected="selected='selected'";
																}
																?>
																<option value="<?php echo $row['payment_term_id']; ?>" <?php echo $selected;?>><?php echo $row['payment_term']; ?></option>
																<?php 
															} 
														?>
													</select>
												</div>
											</div>
										</div> */ ?>
									</div>


									<?php /* <div class="row">
										<div class="col-md-4">
											<div class="row">
												<label class="col-form-label col-md-6 text-right">Note to Supplier</label>
												<div class="form-group col-md-6">
													<textarea name="header_note_to_supplier" id="header_note_to_supplier" rows="1" <?php echo $fieldReadonly;?> autocomplete="off" class="form-control single_quotes" placeholder=""><?php echo isset($edit_data[0]['note_to_supplier']) ? $edit_data[0]['note_to_supplier'] : NULL;?></textarea>
												</div>
											</div>
										</div>

										
										<div class="col-md-4">
											<div class="row">
												<label class="col-form-label col-md-6 text-right">Note to Receiver</label>
												<div class="form-group col-md-6">
													<textarea name="header_note_to_receiver" id="header_note_to_receiver" rows="1" <?php echo $fieldReadonly;?> autocomplete="off" class="form-control single_quotes" placeholder=""><?php echo isset($edit_data[0]['note_to_receiver']) ? $edit_data[0]['note_to_receiver'] : NULL;?></textarea>
												</div>
											</div>
										</div>
									</div> */ ?>
								</section>
								<!-- Header Section End Here-->

								<!-- Line level start here -->
								<section class="line-section mt-2">
									<div class="row mt-2 mb-2">
										<div class="col-md-12">
											<b>Lines</b>
										</div>
									</div>

									<?php 
										if($type == "add" || $type == "edit")
										{
											?>
											<div class="row mt-2 mb-3">
												<div class="col-md-6">
													<a href="javascript:void(0);" onclick="addLine(1);" class="btn btn-primary btn-sm">Add</a>
												</div>
											</div>
											<?php 
										} 
									?>
									
									<div class="line-section-overflow">
										<table class="table table-bordered table-hover line_items" id="line_items">
											<thead>
												<tr>
													<?php 
														if($type == "add" || $type == "edit")
														{
															?>
															<th class="action-row tab-md-30"></th>
															<?php 
														} 
													?>
													<th class="tab-md-85"><span class="text-danger">*</span> Line No</th>
													<th class="tab-md-150"><span class="text-danger">*</span> Item</th>
													<th class="tab-md-150">Description</th>
													<th class="tab-md-150">Category</th>
													<!--  <th class="tab-md-150">Supplier Item</th>
													 <th class="tab-md-100">Status</th> -->
													<th class="tab-md-100"><span class="text-danger">*</span> Quantity</th>
													<th class="tab-md-100"><span class="text-danger">*</span> UOM</th>
													<th class="text-right tab-md-100"><span class="text-danger">*</span> Base Price</th>
													<th class="text-right tab-md-100">Tax</th>
													<th class="tab-md-100">Discount Type</th>
													<th class="text-right tab-md-100">Discount</th>
													<th class="tab-md-150">Discount Reason</th>
													<th class="text-right tab-md-100">Price</th>
													<th class="text-right tab-md-100">Line Value</th>
													<th class="text-right tab-md-100">Total Tax</th>											
													<th class="text-right tab-md-100">Total</th>											
													<!-- <th class="text-center tab-md-150">Organization</th>											 -->
														<!-- <th class="tab-md-150">Delivery Date</th>											
													<th class="tab-md-150">Requested By</th>											
													<th class="tab-md-150">Note to Supplier</th>											
													<th class="tab-md-150">Note to Receiver</th>											
												 <th class="text-center tab-md-150">Cancelled Reason</th> -->											
												</tr>
											</thead>
											<tbody>
												<?php 
													if($type == "edit" || $type == "view")
													{
														$itemQuery = "select item_id,item_name,item_description 
														from inv_sys_items 
														where 1=1
														and active_flag='Y'
														and item_type_id = 31
														";
														
														$getItems = $this->db->query($itemQuery)->result_array();

														$taxQry = "select tax_id,tax_name,tax_value from gen_tax 
															where active_flag='Y'
															and coalesce(start_date,'".$this->date."') <= '".$this->date."'
															and coalesce(end_date,'".$this->date."') >= '".$this->date."'
															";
														$getTax = $this->db->query($taxQry)->result_array();

														$uomQry = "select uom_id,uom_code,uom_description from uom 
															where active_flag='Y'
															and coalesce(start_date,'".$this->date."') <= '".$this->date."'
															and coalesce(end_date,'".$this->date."') >= '".$this->date."'
															";
														$getUom = $this->db->query($uomQry)->result_array();

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
																?>
																<tr class="remove_tr tabRow<?php echo $counter;?>">
																	<?php 
																		if($type == "add" || $type == "edit")
																		{
																			?>
																			<td class="tab-md-30 text-center">
																				<a onclick="deleteRow('<?php echo $lineItems['po_line_id'];?>','<?php echo $counter;?>');">
																					<i class="fa fa-times-circle-o" style="color:#fb1b1b61;font-size:16px;"></i>
																				</a>
																				<input type="hidden" name="po_line_id[]" value="<?php echo $lineItems["po_line_id"];?>" id="po_line_id<?php echo $counter;?>">
																				<input type="hidden" name="text_product_id[]" value="<?php echo $lineItems["item_id"];?>" id="text_product_id_<?php echo $counter;?>">
																				<input type="hidden" name="counter" value="<?php echo $counter;?>">
																			</td>
																			<?php 
																		} 
																	?>

																	<td class="tab-md-85">
																		<input type="number" class="form-control mobile_vali" required name="line_num[]" id="line_num<?php echo $counter;?>" value="<?php echo $lineItems["line_num"];?>">
																	</td>
																	<td class="tab-md-150">
																		<select class="form-control <?php echo $searchDropdown; ?>" onchange="selectItemDetails(this.value,<?php echo $counter?>);" name="item_id[]" id="item_id<?php echo $counter;?>">
																			<option value="">- Select -</option>
																			<?php 
																				foreach($getItems as $items)
																				{
																					$selected="";
																					if($lineItems["item_id"] == $items["item_id"]){
																						$selected="selected='selected'";
																					}
																					?>
																					<option value="<?php echo $items["item_id"];?>" <?php echo $selected;?>><?php echo $items["item_name"];?></option>
																					<?php 
																				} 
																			?>
																		</select>
																	</td>
																	<td class="tab-md-150">
																		<textarea class="form-control single_quotes" rows="1" readonly name="description[]" id="description<?php echo $counter;?>"><?php echo $lineItems["item_description"];?></textarea>
																	</td>
																	<td class="tab-md-150">
																		<input type="text" class="form-control single_quotes" readonly name="category_name[]" id="category_name<?php echo $counter;?>" value="<?php echo $lineItems["category_name"];?>">
																	</td>
																	<?php /*
																	<td class="tab-md-150">
																		<textarea class="form-control single_quotes" rows="1" name="supplier_item[]" id="supplier_item<?php echo $counter;?>"><?php echo $lineItems["supplier_item"];?></textarea>
																	</td>
																	
																	<td class="tab-md-100">
																		<input type="text" class="form-control single_quotes" readonly name="line_status[]" id="line_status<?php echo $counter;?>" value="<?php echo $lineItems["line_status"];?>">
																	</td> */ ?>
																	<td class="tab-md-100">
																		<input type="number" class="form-control" min="1" required name="quantity[]" id="quantity<?php echo $counter;?>" value="<?php echo $lineItems["received_qty"];?>">
																	</td>
																	<td class="tab-md-100">
																		<select class="form-control <?php echo $searchDropdown; ?>" required name="uom[]" id="uom<?php echo $counter;?>">
																			<option value="">- Select -</option>
																			<?php 
																				foreach( $getUom as $uom )
																				{
																					$selected="";
																					if($lineItems["uom"] == $uom["uom_id"]){
																						$selected="selected='selected'";
																					}
																					?>
																					<option value="<?php echo $uom["uom_id"];?>" <?php echo $selected;?>><?php echo $uom["uom_code"];?></option>
																					<?php 
																				} 
																			?>
																		</select>
																	</td>
																	<td class="tab-md-100">
																		<input type="number" class="form-control" required name="base_price[]" id="base_price<?php echo $counter;?>" value="<?php echo !empty($lineItems["base_price"]) ? $lineItems["base_price"] : 0;?>">
																	</td>
																	<td class="tab-md-100">
																		<select class="form-control <?php echo $searchDropdown; ?>" onchange="selectTax(this.value,<?php echo $counter?>);" name="tax[]" id="tax<?php echo $counter;?>">
																			<option value="">- Select -</option>
																			<?php 
																				foreach( $getTax as $tax )
																				{
																					$selected="";
																					if($lineItems["tax"] == $tax["tax_value"]){
																						$selected="selected='selected'";
																					}
																					?>
																					<option value="<?php echo $tax["tax_value"];?>" <?php echo $selected;?>><?php echo $tax["tax_name"];?></option>
																					<?php 
																				} 
																			?>
																		</select>
																	</td>
																	<td class="tab-md-100">
																		<select class="form-control <?php echo $searchDropdown; ?>" onchange="selectDiscountTypes(this.value,<?php echo $counter?>);" name="discount_type[]" id="discount_type<?php echo $counter;?>">
																			<option value="">- Select -</option>
																			<?php 
																				foreach($this->discount_type as $key => $value)
																				{
																					$selected="";
																					if($lineItems["discount_type"] == $value){
																						$selected="selected='selected'";
																					}
																					?>
																					<option value="<?php echo $value;?>" <?php echo $selected;?>><?php echo $value;?></option>
																					<?php 
																				} 
																			?>
																		</select>
																	</td>
																	<td class="tab-md-100">
																		<input type="number" class="form-control" readonly="" name="discount[]" id="discount<?php echo $counter;?>" value="<?php echo $lineItems["discount"];?>">
																	</td>
																	<td class="tab-md-150">
																		<textarea class="form-control single_quotes" rows="1" readonly="" name="discount_reason[]" id="discount_reason<?php echo $counter;?>"><?php echo $lineItems["discount_reason"];?></textarea>
																	</td>
																	<td class="tab-md-100">
																		<input type="number" class="form-control text-right" readonly="" name="price[]" id="price<?php echo $counter;?>" value="<?php echo $lineItems["price"];?>">
																	</td>
																	<td class="tab-md-100">
																		<input type="number" class="form-control text-right" readonly="" name="line_value[]" id="line_value<?php echo $counter;?>" value="<?php echo $lineItems["line_value"];?>">
																	</td>
																	<td class="tab-md-100">
																		<input type="number" class="form-control text-right" readonly="" name="total_tax[]" id="total_tax<?php echo $counter;?>" value="<?php echo $lineItems["total_tax"];?>">
																	</td>
																	<td class="tab-md-100">
																		<input type="number" class="form-control text-right" readonly="" name="total[]" id="total<?php echo $counter;?>" value="<?php echo $lineItems["total"];?>">
																	</td>
																	<?php /*
																		?>
																			<td class="tab-md-150">
																				<select class="form-control <?php echo $searchDropdown; ?>" name="organization_id[]" id="organization_id<?php echo $counter;?>">
																					<option value="">- Select -</option>
																					<?php 
																						foreach( $getOrganization as $organization)
																						{
																							$selected="";
																							if($lineItems["organization_id"] == $organization["organization_id"]){
																								$selected="selected='selected'";
																							}
																							?>
																							<option value="<?php echo $organization["organization_id"];?>" <?php echo $selected;?>><?php echo $organization["organization_name"];?></option>
																							<?php 
																						} 
																					?>
																				</select>
																			</td>
																		<?php
																	
																	<td class="tab-md-150">
																		<input type="date" class="form-control" name="delivery_date[]" id="delivery_date<?php echo $counter;?>" value="<?php echo $lineItems["delivery_date"];?>">
																	</td>
																	<td class="tab-md-150">
																		<select class="form-control <?php echo $searchDropdown; ?>" name="requested_by[]" id="requested_by<?php echo $counter;?>">
																			<option value="">- Select -</option>
																			<?php 
																				foreach( $getRequestedBy as $requestedBy)
																				{
																					$selected="";
																					if($lineItems["requested_by"] == $requestedBy["person_id"]){
																						$selected="selected='selected'";
																					}
																					?>
																					<option value="<?php echo $requestedBy["person_id"];?>" <?php echo $selected;?>><?php echo $requestedBy["first_name"];?></option>
																					<?php 
																				} 
																			?>
																		</select>
																	</td>
																	<td class="tab-md-150">
																		<textarea class="form-control single_quotes" rows="1" name="note_to_supplier[]" id="note_to_supplier<?php echo $counter;?>"><?php echo $lineItems["note_to_supplier"];?></textarea>
																	</td>
																	<td class="tab-md-150">
																		<textarea class="form-control single_quotes" rows="1" name="note_to_receiver[]" id="note_to_receiver<?php echo $counter;?>"><?php echo $lineItems["note_to_receiver"];?></textarea>
																	</td> */ ?>
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
								<!-- Line level end here -->
								<!-- <link href="<?php #echo base_url(); ?>assets/backend/assets/css/jquery-ui.css" rel="stylesheet">
								<script src="<?php #echo base_url(); ?>assets/backend/assets/js/jquery-ui.js"></script> -->
							</fieldset>
						</div>
					</form>
				   
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

						function addLine(val)
						{
							if(val == 1)
							{
								addPoLines();
							}
						}

						var type = '<?php echo $type;?>';

						if(type == 'add')
						{
							var counter = 1;
							var i=1;
						}
						else if(type == 'edit')
						{
							var counter = '<?php echo isset($line_data) ? count($line_data) + 1 : 1; ?>';
							var i = '<?php echo isset($line_data) ? count($line_data) + 1 : 1; ?>';
						}

						function addPoLines()
						{
							$('.line-items-error').text('');

							var flag = 0;

							$.ajax({
								url: "<?php echo base_url('quick_receipt/getLineData'); ?>",
								type: "GET",
								data:{
									'<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
								},
								datatype: "JSON",
								success: function(d)
								{
									data = JSON.parse(d);
									
									$("table.line_items").find('input[name^="text_product_id[]"]').each(function () 
									{
										var row = $(this).closest("tr");
										var text_product_id = +row.find('input[name^="text_product_id[]"]').val();
										var quantity = +row.find('input[name^="quantity[]"]').val();
										var uom = +row.find('input[name^="uom_id[]"]').val();
										var base_price = +row.find('input[name^="base_price[]"]').val();
										var delivery_date = +row.find('input[name^="delivery_date[]"]').val();

										if(text_product_id == 0 || quantity == "") //|| uom == "" || base_price == "" || delivery_date== ""
										{
											flag = 1;
										}
									});
									
									if(flag == 0)
									{
										//Items
										var select_items = "";
										select_items += '<select class="form-control searchDropdown" onchange="selectItemDetails(this.value,'+counter+');" name="item_id[]" id="item_id'+counter+'">';
										select_items += '<option value="">- Select -</option>';
										for(a=0;a<data['items'].length;a++)
										{
											select_items += '<option value="' + data['items'][a].item_id + '">' + data['items'][a].item_name+'</option>';
										}
										select_items += '</select>';

										//UOM
										var select_uom = "";
										select_uom += '<select class="form-control searchDropdown" onchange="selectUom(this.value,'+counter+');" required name="uom[]" id="uom'+counter+'">';
										select_uom += '<option value="">- Select -</option>';
										for(a=0;a<data['uom'].length;a++)
										{
											select_uom += '<option value="' + data['uom'][a].uom_id + '">' + data['uom'][a].uom_code+'</option>';
										}
										select_uom += '</select>';

										//discount_type
										var select_discount_type = "";
										select_discount_type += '<select class="form-control searchDropdown" onchange="selectDiscountTypes(this.value,'+counter+');" name="discount_type[]" id="discount_type'+counter+'">';
										select_discount_type += '<option value="">- Select -</option>';
										for(a=0;a<data['discount_type'].length;a++)
										{
											select_discount_type += '<option value="' + data['discount_type'][a].discount_type + '">' + data['discount_type'][a].discount_type+'</option>';
										}
										select_discount_type += '</select>';

										//TAX
										var select_tax = "";
										select_tax += '<select class="form-control searchDropdown" onchange="selectTax(this.value,'+counter+');" name="tax[]" id="tax'+counter+'">';
										select_tax += '<option value="">- Select -</option>';
										for(a=0;a<data['tax'].length;a++)
										{
											select_tax += '<option value="' + data['tax'][a].tax_value + '">' + data['tax'][a].tax_name+'</option>';
										}
										select_tax += '</select>';

										//Organization
										// var select_organization = "";
										// select_organization += '<select class="form-control searchDropdown" name="organization_id[]" id="organization_id'+counter+'">';
										// select_organization += '<option value="">- Select -</option>';
										// for(a=0;a<data['organization'].length;a++)
										// {
										// 	select_organization += '<option value="' + data['organization'][a].organization_id + '">' + data['organization'][a].organization_name+'</option>';
										// }
										// select_organization += '</select>';

										//requested_by
										/* var select_requested_by= "";
										select_requested_by += '<select class="form-control searchDropdown" name="requested_by[]" id="requested_by'+counter+'">';
										select_requested_by += '<option value="">- Select -</option>';
										for(a=0;a<data['requestedBy'].length;a++)
										{
											select_requested_by += '<option value="' + data['requestedBy'][a].person_id + '">' + data['requestedBy'][a].first_name+'</option>';
										}
										select_requested_by += '</select>'; */

										var newRow = $("<tr class='remove_tr tabRow"+counter+"'>");
										var cols = "";
										//cols += "<td class='tab-md-30'><input type='hidden' name='id' name='id' value="+i+"><input type='hidden' id='product_id"+counter+"' name='product_id' value=''><input type='hidden' name='counter' name='counter' value="+counter+"></td>";
										cols += "<td class='tab-md-30 text-center'><a class='deleteRow'><i class='fa fa-times-circle-o' style='color:#fb1b1b61;font-size:16px;'></i></a>"+
										"<input type='hidden' name='po_line_id[]' value='0' id='po_line_id"+counter+"'>"+
										"<input type='hidden' name='text_product_id[]' value='0' id='text_product_id_"+counter+"'>"+
										"<input type='hidden' name='uom_id[]' value='0' id='uom_id_"+counter+"'>"+
										"<input type='hidden' name='counter' value='"+counter+"'></td>";
										"<input type='hidden' class='form-control' readonly name='line_status[]' id='line_status"+ counter +"' value='Draft'>";
										"<input type='hidden' class='form-control' readonly name='line_status[]' id='line_status"+ counter +"' value='Draft'>";
										"<input type='hidden' class='form-control' readonly name='requested_by[]' id='requested_by"+ counter +"' value=''>";
										"<input type='hidden' class='form-control' readonly name='note_to_supplier[]' id='note_to_supplier"+ counter +"' value=''>";
										"<input type='hidden' class='form-control' readonly name='note_to_receiver[]' id='note_to_receiver"+ counter +"' value=''>";
											
										cols += "<td class='tab-md-85'>" 
												+"<input type='number' class='form-control mobile_vali' required name='line_num[]' id='line_num"+ counter +"' value='"+counter
												+"'>"
											+"</td>";

										cols += '<td class="tab-md-150">'+select_items+'</td>';
										
										cols += "<td class='tab-md-150'>" 
												+"<textarea class='form-control single_quotes' rows='1' readonly name='description[]' id='description"+ counter +"'></textarea>"
											+"</td>";

										cols += "<td class='tab-md-150'>" 
												+"<input type='text' class='form-control single_quotes' readonly name='category_name[]' id='category_name"+ counter +"' value=''>"
											+"</td>";

										/* cols += "<td class='tab-md-150'>" 
												+"<textarea class='form-control single_quotes single_quotes' rows='1' name='supplier_item[]' id='supplier_item"+ counter +"'></textarea>"
											+"</td>";

										 cols += "<td class='tab-md-100'>" 
												+"<input type='text' class='form-control single_quotes' readonly name='line_status[]' id='line_status"+ counter +"' value='Draft'>"
											+"</td>"; */ 

										cols += "<td class='tab-md-100'>" 
												+"<input type='number' class='form-control' required min='1' name='quantity[]' id='quantity"+ counter +"' value=''>"
											+"</td>";

										cols += '<td class="tab-md-100">'+select_uom+'</td>';
										
										cols += "<td class='tab-md-100'>" 
												+"<input type='number' class='form-control' name='base_price[]' id='base_price"+ counter +"' value='0'>"
											+"</td>";

										cols += '<td class="tab-md-100">'+select_tax+'</td>';
										cols += '<td class="tab-md-100">'+select_discount_type+'</td>';
										
										cols += "<td class='tab-md-100'>" 
												+"<input type='number' class='form-control mobile_vali' readonly name='discount[]' id='discount"+ counter +"' value=''>"
											+"</td>";

										cols += "<td class='tab-md-150'>" 
												+"<textarea class='form-control single_quotes' rows='1' readonly name='discount_reason[]' id='discount_reason"+ counter +"'></textarea>"
											+"</td>";

										cols += "<td class='tab-md-100'>" 
												+"<input type='number' class='form-control text-right' readonly name='price[]' id='price"+ counter +"' value=''>"
											+"</td>";

										cols += "<td class='tab-md-100'>" 
												+"<input type='number' class='form-control text-right' readonly name='line_value[]' id='line_value"+ counter +"' value=''>"
											+"</td>";
										
										cols += "<td class='tab-md-100'>" 
												+"<input type='number' class='form-control text-right' readonly name='total_tax[]' id='total_tax"+ counter +"' value=''>"
											+"</td>";

										cols += "<td class='tab-md-100'>" 
												+"<input type='number' class='form-control text-right' readonly name='total[]' id='total"+ counter +"' value=''>"
											+"</td>";

										// cols += '<td class="tab-md-150">'+select_organization+'</td>';	

										/* cols += "<td class='tab-md-150'>" 
												+"<input type='date' class='form-control' name='delivery_date[]' id='delivery_date"+ counter +"' value=''>"
											+"</td>";
										
										cols += '<td class="tab-md-150">'+select_requested_by+'</td>';
										
										cols += "<td class='tab-md-150'>" 
												+"<textarea class='form-control single_quotes' rows='1' name='note_to_supplier[]' id='note_to_supplier"+ counter +"'></textarea>"
											+"</td>";

										cols += "<td class='tab-md-150'>" 
												+"<textarea class='form-control single_quotes' rows='1' name='note_to_receiver[]' id='note_to_receiver"+ counter +"'></textarea>"
											+"</td>" */
											
										;
										
										cols += "</tr>";
										
										newRow.html(cols);
										$("table.line_items").append(newRow);
										/* $("#delivery_date"+counter).datepicker({
											changeMonth: true,
											changeYear: true,
											yearRange: "1950:<?php echo date('Y'); ?>",
											dateFormat: "dd-M-yy"
											//dateFormat: "yy-mm"											
											//dateFormat: "mm-yy"											
										}); */
										$(document).ready(function()
										{ 
											$(".searchDropdown").select2();
										});

										var lst = ["\"", "&", "'", "%"];
										
										i++;
										counter++;
									}
									else 
									{
										$('.line-items-error').text('Please fill the all required fields.').animate({opacity: '0.0'}, 2000).animate({}, 1000).animate({opacity: '1.0'}, 2000);
									}
								},
								error: function(xhr, status, error) {
									$('#err_product').text('Enter Product Code / Name').animate({opacity: '0.0'}, 2000).animate({opacity: '0.0'}, 1000).animate({opacity: '1.0'}, 2000);
								}
							});
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
										url:"<?php echo base_url().'quick_receipt/deleteLineItems';?>",
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

						function selectItemDetails(item_id,counter)
						{
							if(item_id)
							{
								selectItemUom(item_id,counter);

								$.ajax({
									type: "POST",
									url:"<?php echo base_url().'quick_receipt/getLineItems';?>",
									data: { item_id: item_id }
								}).done(function( d ) 
								{   
									data = JSON.parse(d);
									
									$("#description"+counter).val(data[0].item_description);
									$("#category_name"+counter).val(data[0].category_name);
									$( "#text_product_id_"+counter ).val(data[0].item_id);
								});
							}
						}

						function selectItemUom(item_id,counter)
						{
							if(item_id)
							{
								$.ajax({
									type: "POST",
									url:"<?php echo base_url().'quick_receipt/ajaxSelectItemUom';?>",
									data: { id: item_id }
								}).done(function( result ) 
								{   
									$("#uom"+counter).html(result);
								});
							}
						}

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
								if($quick_receipt['create_edit_only'] == 1 || $this->user_id == 1)
								{
									?>
									<a href="<?php echo base_url(); ?>quick_receipt/manageReceipt/add" class="btn btn-info btn-sm">
										Create Quick Receipt
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
									<label class="col-form-label col-md-5">Receipt Number</label>
									<div class="form-group col-md-7">
										<input type="search" class="form-control" autocomplete="off" name="receipt_number" value="<?php echo !empty($_GET['receipt_number']) ? $_GET['receipt_number'] :""; ?>" placeholder="Receipt Number">
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
						</div>

						<div class="row mt-2">
							<div class="col-md-4">
								<div class="row">
									<label class="col-form-label col-md-5">Supplier</label>
									<div class="form-group col-md-7">
										<?php
											$query1 = "select supplier_name,supplier_id from sup_suppliers
											where active_flag='Y' order by supplier_name asc
											";
											$getSupplier = $this->db->query($query1)->result_array();
										?>
										
										<select id="supplier_id" name="supplier_id" onchange="selectSupplierSite(this.value)" class="form-control searchDropdown">
											<option value="">- Select -</option>
											<?php 
												foreach($getSupplier as $Supplier)
												{
													$selected="";
													if(isset($_GET["supplier_id"]) && $_GET["supplier_id"] == $Supplier['supplier_id'] )
													{
														$selected="selected='selected'";
													}
													?>
													<option value="<?php echo $Supplier['supplier_id'];?>" <?php echo $selected;?>><?php echo ucfirst($Supplier['supplier_name']);?></option>
													<?php 
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
								function selectSupplierSite(val)
								{
									if(val !='')
									{
										$.ajax({
											type: "POST",
											url:"<?php echo base_url().'quick_receipt/ajaxSelectSupplierSite';?>",
											data: { id: val }
										}).done(function( msg ) {   
											$( "#supplier_site_id" ).html(msg);
										});
									}
									else 
									{ 
										$( "#supplier_site_id" ).html('<option value="">- Select -</option>');
									}
								}
							</script>

							<div class="col-md-4">
								<div class="row">
									<label class="col-form-label col-md-5">Supplier Site</label>
									<div class="form-group col-md-7">
										
										<select id="supplier_site_id" name="supplier_site_id" class="form-control searchDropdown">
											<option value="">- Select -</option>
											<?php 
												if(isset($_GET["supplier_id"]) && $_GET["supplier_id"] != "")
												{
													$supplier_id = $_GET["supplier_id"];

													$getSupplierSite =  $this->db->query("select 
													sup_supplier_sites.supplier_site_id,
													sup_supplier_sites.site_name from sup_supplier_sites
													where 
														sup_supplier_sites.supplier_id='".$supplier_id."' 
														order by sup_supplier_sites.site_name asc
													")->result_array();

													foreach($getSupplierSite as $SupplierSite)
													{
														$selected="";
														if(isset($_GET["supplier_site_id"]) && $_GET["supplier_site_id"] == $SupplierSite['supplier_site_id'] )
														{
															$selected="selected='selected'";
														}
														?>
														<option value="<?php echo $SupplierSite['supplier_site_id'];?>" <?php echo $selected;?>><?php echo ucfirst($SupplierSite['site_name']);?></option>
														<?php 
													} 
												}
											?>
										</select>
									</div>
								</div>
							</div>
						</div>

						<div class="row">
							<div class="col-md-4">
								<div class="row">
									<label class="col-form-label col-md-5">From Date</label>
									<div class="form-group col-md-7">
										<input type="text" name="from_date" id="from_date" class="form-control" readonly value="<?php echo !empty($_GET['from_date']) ? $_GET['from_date'] : ""; ?>" placeholder="From Date">
									</div>
								</div>
							</div>

							<div class="col-md-4">
								<div class="row">
									<label class="col-form-label col-md-5">To Date</label>
									<div class="form-group col-md-7">
										<input type="text" name="to_date" id="to_date" class="form-control" readonly value="<?php echo !empty($_GET['to_date']) ? $_GET['to_date'] :""; ?>" placeholder="To Date">
									</div>
								</div>
							</div>

							<div class="col-md-2">
								<button type="submit" class="btn btn-info">Search <i class="fa fa-search" aria-hidden="true"></i></button>
								<a href="<?php echo base_url(); ?>quick_receipt/manageReceipt" title="Clear" class="btn btn-default">Clear</a>
							</div>
						</div>
						<!-- <script>
							$('#submit_btn').click(function (e) 
							{ 
								var organization_id = $('#organization_id').val()
								var branch_id = $('#branch_id').val()
								if (organization_id && branch_id)
								{
									$(".organization_id").removeClass('errorClass');
									$(".branch_id").removeClass('errorClass');
									return true; 
								} 
								else 
								{
									if (organization_id) {
										$(".organization_id").removeClass('errorClass');
									} else {
										$(".organization_id").addClass('errorClass');
									}
									
									if (branch_id) {
										$(".branch_id").removeClass('errorClass');
									} else {
										$(".branch_id").addClass('errorClass');
									}
									return false;
								}
							});
						</script> -->

					</form>
					<!-- Filters end here -->
					
					<?php 
						if(isset($_GET) &&  !empty($_GET))
						{
							?>
							<!-- Page Item Show start -->
							<div class="row">
								<div class="col-md-10 mt-2">
									<?php 
										if(count($resultData) > 0)
										{
											?>
											<a href="<?php echo base_url().$this->redirectURL.'&download_excel=download_excel'; ?>" target="_blank" title="Download Excel" class="btn btn-primary btn-sm">Download Excel</a>
											<?php 
										} 
									?>
								</div>

								<div class="col-md-2 float-right text-right">
									<?php 
										$redirect_url = substr($_SERVER['REQUEST_URI'],'1');
									?>
									<input type="hidden" id="redirect_url" value="<?php echo $redirect_url; ?>"/>
									<div class="col-md-12">
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
							</div>
						    <!-- Page Item Show start -->

							<!-- Table start here -->
							<div class="new-scroller">
								<table id="myTable" class="table table-bordered table-hover">
									<thead>
										<tr>
											<th class="tab-md-100 text-center">Controls</th>
											<th class="tab-md-120 text-left">Organization</th>
											<th class="tab-md-150 text-left">Branch</th>
											<th class="tab-md-80 text-left">Receipt #</th>
											<th class="tab-md-120 text-left">Receipt Date</th>
											<th class="tab-md-120 text-left">Supplier</th>
											<th class="tab-md-120 text-left">Supplier Site</th>
											<th class="tab-md-120 text-right">Amount</th>
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
														<div class="dropdown" style="width:90px;">
															<button type="button" class="btn btn-outline-info gropdown-toggle btn-sm" data-toggle="dropdown" aria-expanded="false">
																Action&nbsp;<i class="fa fa-chevron-down"></i>
															</button>
															<ul class="dropdown-menu dropdown-menu-right dropdown-menu-new">
																<?php
																	if($quick_receipt['read_only'] == 1 || $this->user_id == 1)
																	{
																		?>
																		<li>
																			<a href="<?php echo base_url(); ?>quick_receipt/manageReceipt/view/<?php echo $row['receipt_header_id'];?>">
																				<i class="fa fa-eye"></i> View
																			</a>
																		</li>
																		<?php 
																	} 
																?>

																<li>
																	<a target="_blank" href="<?php echo base_url(); ?>quick_receipt/generatePDF/<?php echo $row['receipt_header_id'];?>">
																		<i class="fa fa-file-pdf-o"></i> Download PDF
																	</a>
																</li>
															</ul>
														</div>
													</td>
													<td><?php echo $row['organization_name'];?></td>
													<td><?php echo $row['branch_name'];?></td>
													<td><?php echo $row['receipt_number'];?> <?php #echo $row['po_status']; ?></td>
													<td>
														<?php echo date(DATE_FORMAT,strtotime($row['receipt_date']));?>
													</td>
													<td><?php echo ucfirst($row['supplier_name']);?></td>												
													<td><?php echo ucfirst($row['site_name']);?></td>
													
													<td class="text-right">
														<?php 
															echo number_format($row['amount'],DECIMAL_VALUE,'.','');
														?>
													</td>
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

