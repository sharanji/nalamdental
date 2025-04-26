

<?php
	$getInvoiceStatus = $this->common_model->lov("INVOICE-STATUS");
	$invoiceMenu = accessMenu(invoice);
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
					<style>
						.hidden {
							display: none;
						}
						span.invoicenumber {
							font-size: 16px;
							position: relative;
							right: 17px;
						}
					</style>
					<form action="" --class="form-validate-jquery" enctype="multipart/form-data" method="post">				
						<div class="header-lines">
							<!-- Buttons start here -->
							<div class="row mb-3">
								<div class="col-md-4">
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
											<?php echo $page_title?>
											
										</b>
									</h3>
								</div>
								
								<div class="col-md-8 text-right">
									<?php 
										if($type == "edit")
										{
											?>
												<span class="invoicenumber"> Invoice Number : <?php echo isset($edit_data[0]['invoice_number']) ? $edit_data[0]['invoice_number'] : NULL;?></span>
											<?php 
										} 
									?>
									<?php 
										if($type == "add" || $type == "edit")
										{
											?>
											<button type="submit" name="save_btn" id="save_btn" onclick="return saveBtn('save_btn');" title="Save & Continue" class="btn btn-primary btn-sm">Save</button>
											
											<button type="submit" name="submit_btn" id="submit_btn" onclick="return saveBtn('submit_btn');" title="Submit" class="btn btn-primary btn-sm">Submit</button>
										
											<?php 
										} 
									?>
									
									<a href="<?php echo base_url(); ?>invoice/manageinvoice" class="btn btn-default btn-sm">Close</a>
									
								</div>
							</div>
							<!-- Buttons end here -->
							
							<fieldset <?php echo $fieldSetDisabled;?>>
								<!-- Header Section Start Here-->
								<div class="row">
									<div class="col-md-12 header-filters">
										<a href="javascript:void(0)" class="filter-icons first_sec_hide" onclick="sectionShow('FIRST_SECTION','SHOW');">
											<i class="fa fa-chevron-circle-down"></i>
										</a>
										<a href="javascript:void(0)" class="filter-icons first_sec_show" onclick="sectionShow('FIRST_SECTION','HIDE');" style="display:none;">
											<i class="fa fa-chevron-circle-right"></i>
										</a>
										<h4 class="pl-1"><b>Header</b></h4>
										
									</div>
								</div>
								<section class="header-section first_section">
									<div class="row">
										<div class="col-md-6">
											<div class="row">
												<label class="col-form-label col-md-4 text-right invoice_type"> <span class="text-danger">*</span> Invoice Type</label>
												<div class="form-group col-md-5">
													<?php 
														$getInvoieType = $this->common_model->lov('INVOICE-TYPE');
													?>
													
													<select name="invoice_type" id="invoice_type" class="form-control <?php echo $searchDropdown;?>" onchange="selectInvoiceType(this.value);">
														<option value="">- Select  -</option>
														<?php 
															foreach($getInvoieType as $itemcategory)
															{
																$selected="";
																if(isset($edit_data[0]['invoice_type']) && ($edit_data[0]['invoice_type'] == $itemcategory['list_code']) )
																{
																	$selected="selected='selected'";
																}
																?>
																<option value="<?php echo $itemcategory['list_code']; ?>" <?php echo $selected;?>><?php echo $itemcategory['list_value']; ?></option>
																<?php 
															} 
														?>
													</select>
												</div>
											</div>
											<div class="row">
												<label class="col-form-label col-md-4 text-right customer_id"><span class="text-danger">*</span> Customer</label>
												<div class="form-group col-md-5">
												<div class="input-wrapper">
													<input type="text" name="customer_name" autocomplete="off" id="customer_name" value="<?php echo isset($edit_data[0]['customer_name']) ? $edit_data[0]['customer_name'] : NULL; ?>" placeholder="Customer Name" class="form-control">
													<input type="hidden" name="customer_id" autocomplete="off" id="customer_id" value="<?php echo isset($edit_data[0]['customer_id']) ? $edit_data[0]['customer_id'] : NULL; ?>" >
													<div id="CustomerList"></div><!-- Clear icon start -->
													<?php 
														
														if(isset($edit_data[0]["customer_id"]) && !empty($edit_data[0]["customer_id"]))
														{
															$styleDisplay = "display:block";
														}
														else{
															$styleDisplay = "display:none";
														}
														if($type==='view'){
															
															$styleDisplay = "display:none";
															
														}
														?>
													<span class="customer_clear_icon" title="Clear" onclick="clearCustomerSearchKeyword();" style="<?php echo $styleDisplay;?>">
														<i class="fa fa-times" aria-hidden="true"></i>
													</span>

													<script>
														$(document).ready(function()
														{  
															$('#customer_name').keyup(function()
															{  
																var query = $(this).val();  

																if(query != '')  
																{  
																	$.ajax({  
																		url:"<?php echo base_url();?>customer/ajaxCustomerList",  
																		method:"POST",  
																		data:{query:query},  
																		success:function(data)  
																		{  
																			$('#CustomerList').fadeIn();  
																			$('#CustomerList').html(data);  
																		}  
																	});  
																}  
															});

															$(document).on('click', 'ul.list-unstyled-customer_id li', function()
															{  
																var value = $(this).text();
																
																if(value === "Sorry! Customer Not Found.")
																{
																	$('#CustomerList').fadeOut();
																}
																else
																{
																	$('#CustomerList').fadeOut();  
																}
															});
														});

														function getCustomerList(customer_id,customer_name)
														{
															$('.customer_clear_icon').show();
															if(customer_id == 0)	
															{
																$('#customer_id').val('0');
															}
															else
															{
																$('#customer_id').val(customer_id);
																$('#customer_name').val(customer_name);
															}
														}

														function clearCustomerSearchKeyword()
														{
															$(".customer_clear_icon").hide();
															$("#customer_id").val("");
															$("#customer_name").val("");
														}
													</script>

												</div>
												</div>
											</div>
											
											<div class="row">
												<label class="col-form-label col-md-4 text-right">Invoice Date</label>
												<div class="form-group col-md-3">
													<input type="text" name="invoice_date" id="invoice_date" readonly autocomplete="off" required class="form-control default_date" value="<?php echo isset($edit_data[0]['invoice_date']) ? date("d-M-Y",strtotime($edit_data[0]['invoice_date'])) : date("d-M-Y");?>" placeholder="">
												</div>
											</div>

											<div class="row">
												<label class="col-form-label col-md-4 text-right payment_term_id"><span class="text-danger">*</span> Payment Terms</label>
												<div class="form-group col-md-5">
													<?php 
														$paymentTerms = $this->common_model->getPaymentTerms();
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

											<div class="row">
												<label class="col-form-label col-md-4 text-right ">Invoice Due Date</label>
												<div class="form-group col-md-3">
													<input type="text" name="invoice_due_date" id="invoice_due_date"  autocomplete="off"  class="form-control default_date" readonly value="<?php echo isset($edit_data[0]['invoice_due_date']) ? date("d-M-Y",strtotime($edit_data[0]['invoice_due_date'])) : NULL;?>" placeholder="">
												</div>
											</div>

											<div class="row">
												<label class="col-form-label col-md-4 text-right">Description</label>
												<div class="form-group col-md-5">
													<textarea name="header_description" rows="1"id="description" <?php echo $fieldReadonly;?> autocomplete="off" class="form-control single_quotes" placeholder=""><?php echo isset($edit_data[0]['description']) ? $edit_data[0]['description'] : NULL;?></textarea>
												</div>
											</div>
										</div>
										
										<div class="col-md-6">
											<?php 
												if($type == "add")
												{
													$displayField ="display:none;";
												}
												else if($type == "edit" || $type == "view")
												{
													if($edit_data[0]['invoice_type'] == "WITH-GST")
													{
														$displayField ="display:block;";
													}
													else if($edit_data[0]['invoice_type'] == "WITH-OUT-GST")
													{
														$displayField ="display:none;";
													}
												}
												
											?> 
											<div class="gst-subtype" style="<?php echo $displayField ;?>">
												<div class="row">
													<label class="col-form-label col-md-4 text-right">PO Number </label>
													<div class="form-group col-md-5">
														<input type="text" name="po_number" id="po_number" autocomplete="off" class="form-control" value="<?php echo isset($edit_data[0]['po_number']) ? $edit_data[0]['po_number'] : NULL;?>" placeholder="">
													</div>
												</div>
											
												<div class="row">
													<label class="col-form-label col-md-4 text-right">PO Date</label>
													<div class="form-group col-md-5">
														<input type="text" name="po_date" id="po_date" autocomplete="off" class="form-control default_date" readonly value="<?php echo isset($edit_data[0]['po_date']) ? date("d-M-Y",strtotime($edit_data[0]['po_date'])) : NULL;?>" placeholder="">
													</div>
												</div>

												<div class="row">
													<label class="col-form-label col-md-4 text-right">DC Number</label>
													<div class="form-group col-md-5">
														<input type="text" name="dc_number" id="dc_number" autocomplete="off" class="form-control" value="<?php echo isset($edit_data[0]['dc_number']) ? $edit_data[0]['dc_number'] : NULL;?>" placeholder="">
													</div>
												</div>

												<div class="row">
													<label class="col-form-label col-md-4 text-right">DC Date </label>
													<div class="form-group col-md-5">
														<input type="text" name="dc_date" id="dc_date" readonly autocomplete="off" class="form-control default_date" value="<?php echo isset($edit_data[0]['dc_date']) ? date("d-M-Y",strtotime($edit_data[0]['dc_date'])) : NULL;?>" placeholder="">
													</div>
												</div>
											</div>
													
											<div class="row">
												<label class="col-form-label col-md-4 text-right">Invoice Amount </label>
												<div class="form-group col-md-5">
													<input type="text" name="header_order_amount" id="header_order_amount" readonly autocomplete="off" class="form-control no-outline" value="<?php echo isset($edit_data[0]['order_amount']) ? number_format($edit_data[0]['order_amount'],DECIMAL_VALUE,'.','') : "0.00";?>" placeholder="">
												</div>
											</div>

											<div class="row">
												<label class="col-form-label col-md-4 text-right">Discount</label>
												<div class="form-group col-md-5">
													<input type="text" name="discount_amount" id="discount_amount" readonly autocomplete="off" class="form-control no-outline" value="<?php echo isset($edit_data[0]['totalDiscount']) ? number_format($edit_data[0]['totalDiscount'],DECIMAL_VALUE,'.','') : "0.00";?>" placeholder="">
												</div>
											</div>

											<div class="row">
												<label class="col-form-label col-md-4 text-right">Tax</label>
												<div class="form-group col-md-5">
													<input type="text" name="header_tax" id="header_tax" readonly autocomplete="off" class="form-control no-outline" value="<?php echo isset($edit_data[0]['total_tax']) ? number_format($edit_data[0]['total_tax'],DECIMAL_VALUE,'.','') : "0.00";?>" placeholder="">
												</div>
											</div>

											<div class="row">
												<label class="col-form-label col-md-4 text-right">Total</label>
												<div class="form-group col-md-5">
													<input type="text" name="header_total" id="header_total" readonly autocomplete="off"  class="form-control no-outline" value="<?php echo isset($edit_data[0]['total']) ? number_format($edit_data[0]['total'],DECIMAL_VALUE,'.','') : "0.00";?>" placeholder="">
												</div>
											</div>
										</div>
									</div>
								</section>
								<!-- Header Section End Here-->
							

								<!-- Line level start here -->
								<div class="row">
									<div class="col-md-12 header-filters">
										<a href="javascript:void(0)" class="filter-icons thi_sec_hide" onclick="sectionShow('THIRD_SECTION','SHOW');">
											<i class="fa fa-chevron-circle-down"></i>
										</a>
										<a href="javascript:void(0)" class="filter-icons thi_sec_show" onclick="sectionShow('THIRD_SECTION','HIDE');" style="display:none;">
											<i class="fa fa-chevron-circle-right"></i>
										</a>
										<h4 class="pl-1"><b>Lines</b></h4>
									</div>
								</div>
								<section class="line-section mt-2">
									<?php 
										if($type == "add" || $type == "edit")
										{
											?>
											<div class="row mt-2 mb-3">
												<div class="col-md-6">
										
												</div>
											</div>
											<?php 
										} 
									?>
									<div class="line-section mt-2 thi_section">
									
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
														<th class="text-left tab-md-85">Line No <span class="text-danger">*</span></th>
														
														<th class="text-left tab-md-150">Description</th>
														<th class="text-left tab-md-150 totalTaxSection">HSN/SAC Code</th>
														<th class="text-left tab-md-100">Quantity <span class="text-danger">*</span></th>
														<th class="text-left tab-md-100">UOM <span class="text-danger">*</span></th>
														<th class="text-right tab-md-100">Base Price <span class="text-danger">*</span></th>
														<th class="text-right tab-md-100 totalTaxSection" >Tax</th>
														<th class="text-left tab-md-100">Discount Type</th>
														<th class="text-right tab-md-100">Discount</th>
														<th class="text-left tab-md-150">Discount Reason</th>
														<th class="text-right tab-md-100">Price</th>
														<th class="text-right tab-md-100">Line Value</th>
													    <th class="text-right tab-md-100 totalTaxSection">Total Tax</th>									
														<th class="text-right tab-md-100">Total</th>											
																							
													</tr>
												</thead>
												<tbody>
													<?php 
														if($type == "edit" || $type == "view")
														{
															$itemQuery = "select item_id,item_name,item_description from inv_sys_items where active_flag='Y'";
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
																					<a onclick="deleteRow('<?php echo $lineItems['line_id'];?>','<?php echo $counter;?>');">
																						<i class="fa fa-times-circle-o" style="color:#fb1b1b61;font-size:16px;"></i>
																					</a>
																					<input type="hidden" name="line_id[]" value="<?php echo $lineItems["line_id"];?>" id="line_id<?php echo $counter;?>">
																					<!-- <input type="hidden" name="text_product_id[]" value="<?php //echo $lineItems["item_id"];?>" id="text_product_id_<?php //echo $counter;?>"> -->
																					<input type="hidden" name="discount_amount[]" value="<?php echo $lineItems["total_discount"];?>" id="discount_line_amount_<?php echo $counter;?>">
																					<input type="hidden" name="counter" value="<?php echo $counter;?>">
																				</td>
																				<?php 
																			} 
																		?>

																		<td class="tab-md-85">
																			<input type="number" class="form-control mobile_vali" required name="line_num[]" id="line_num<?php echo $counter;?>" value="<?php echo $lineItems["line_num"];?>">
																		</td>
																		
																		<td class="tab-md-150">
																			<textarea class="form-control single_quotes" rows="1" readonly name="line_description[]" id="description<?php echo $counter;?>"><?php echo $lineItems["item_description"];?></textarea>
																		</td>
																		
																		<td class="tab-md-150 totalTaxSection">
																			<input type="text" class="form-control single_quotes" rows="1" name="hsn[]" id="description<?php echo $counter;?>" value="<?php echo $lineItems["hsn"];?>">
																		</td>
																		
																		<td class="tab-md-100">
																			<input type="number" class="form-control" min="1" required name="quantity[]" id="quantity<?php echo $counter;?>" value="<?php echo $lineItems["quantity"];?>">
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
																			<input type="number" class="form-control text-right" required name="base_price[]" id="base_price<?php echo $counter;?>" value="<?php echo number_format($lineItems['base_price'],DECIMAL_VALUE,'.','');?>">
																		</td>
																		<td class="tab-md-100 totalTaxSection">
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
																			<input type="number" class="form-control text-right" readonly name="discount[]" id="discount<?php echo $counter;?>" value="<?php echo number_format($lineItems['discount'],DECIMAL_VALUE,'.','');?>">
																		</td>
																		<td class="tab-md-150">
																			<textarea class="form-control single_quotes" rows="1" readonly name="discount_reason[]" id="discount_reason<?php echo $counter;?>"><?php echo $lineItems["discount_reason"];?></textarea>
																		</td>
																		<td class="tab-md-100">
																			<input type="number" class="form-control text-right" readonly name="price[]" id="price<?php echo $counter;?>" value="<?php echo number_format($lineItems['price'],DECIMAL_VALUE,'.','');?>">
																		</td>
																		<td class="tab-md-100">
																			<input type="number" class="form-control text-right" readonly name="line_value[]" id="line_value<?php echo $counter;?>" value="<?php echo number_format($lineItems['line_value'],DECIMAL_VALUE,'.','');?>">
																		</td>
																		<td class="tab-md-100 totalTaxSection">
																			<input type="number" class="form-control text-right" readonly name="total_tax[]" id="total_tax<?php echo $counter;?>" value="<?php echo number_format($lineItems['total_tax'],DECIMAL_VALUE,'.','');?>">
																		</td>
																		<td class="tab-md-100">
																			<input type="number" class="form-control text-right" readonly name="total[]" id="total<?php echo $counter;?>" value="<?php echo number_format($lineItems['total'],DECIMAL_VALUE,'.','');?>">
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
									</div>	
									<?php 
										if($type != "view")
										{
											?>
											<div class="add-btns">
												<div class="row">
													<div class="col-md-6">
														<a href="javascript:void(0);" onclick="addLine(1);" class="btn btn-primary btn-sm">Add</a>
													</div>
													<div class="col-md-6 text-right">
														<a href="javascript:void(0);" onclick="addLine(1);" class="btn btn-primary btn-sm">Add</a>
													</div>
												</div>
											</div>
											<?php
										}
									?>
									
								</section>
							</fieldset>
							<div class="col-md-12 mt-3 pr-0 text-right">
								<?php 
									if($type == "add" || $type == "edit")
									{
										?>
										<!-- <a href="javascript:void(0)" id="save_btn" onclick="return saveBtn('save_btn','save');" class="btn btn-primary btn-sm submit_btn_bottom">Save Bottom</a> -->
										<button type="submit" name="save_btn" id="save_btn" onclick="return saveBtn('save_btn');" title="Save & Continue" class="btn btn-primary btn-sm">Save</button>
										<button type="submit" name="submit_btn" id="submit_btn" onclick="return saveBtn('submit_btn');" title="Submit" class="btn btn-primary btn-sm">Submit</button>
										<?php 
									} 
								?>
								<a href="<?php echo base_url(); ?>invoice/manageinvoice" class="btn btn-default btn-sm">Close</a>
							</div>
							<script>
								function sectionShow(section_type,show_hide_type)
								{	
									if(section_type == 'FIRST_SECTION')
									{
										if(show_hide_type == 'SHOW')
										{
											$(".first_sec_hide").hide();
											$(".first_sec_show").show();

											$(".first_section").hide("slow");
										}
										else if(show_hide_type == 'HIDE')
										{
											$(".first_sec_hide").show();
											$(".first_sec_show").hide();

											$(".first_section").show("slow");
										}
									}
									else if(section_type == 'SECOND_SECTION')
									{
										if(show_hide_type == 'SHOW')
										{
											$(".sec_sec_hide").hide();
											$(".sec_sec_show").show();

											$(".sec_section").hide("slow");
										}
										else if(show_hide_type == 'HIDE')
										{
											$(".sec_sec_hide").show();
											$(".sec_sec_show").hide();

											$(".sec_section").show("slow");
										}
									}
									else if(section_type == 'THIRD_SECTION')
									{
										if(show_hide_type == 'SHOW')
										{
											$(".thi_sec_hide").hide();
											$(".thi_sec_show").show();

											$(".thi_section").hide("slow");
										}
										else if(show_hide_type == 'HIDE')
										{
											$(".thi_sec_hide").show();
											$(".thi_sec_show").hide();

											$(".thi_section").show("slow");
										}
									}
								}
							</script>
						</div>
					</form>
				   
					<script>

						function saveBtn(val) {
							var invoice_type = $("#invoice_type").val();
							var customer_id = $("#customer_id").val();
							var payment_term_id = $("#payment_term_id").val();


							if (!invoice_type || !customer_id || !payment_term_id) {
								if (!invoice_type)
								{
									$(".invoice_type").addClass('errorClass');
								}
								else
								{
									$(".invoice_type").removeClass('errorClass')
								}

								if (!customer_id)
								{
									$(".customer_id").addClass('errorClass');
								}
								else
								{
									$(".customer_id").removeClass('errorClass');
								}

								if (!payment_term_id) 
								{
									$(".payment_term_id").addClass('errorClass');
								}
								else 
								{
									$(".payment_term_id").removeClass('errorClass');
								}

								return false;
							}

							var table_row_count = $("table.line_items > tbody  > tr").length;
							if (table_row_count === 0) {
								Swal.fire({
									icon: 'error',
									title: 'Oops...',
									text: 'Please enter at least one row order quantity!',
								});
								return false;
							}

							return true;
						}

						function addLine(val)
						{
							var pay_terms = $("#payment_term_id").val();
							var invoice_type = $("#invoice_type").val();
							var invoice_number = $("#invoice_number").val();
							var invoice_date = $("#invoice_date").val();
							var customer = $("#customer_id").val();

							if(invoice_type && pay_terms && customer)
							{
								addInvoiceLines();
							}
							else
							{
								Swal.fire({
									icon: 'error',
									//title: 'Amount Mismatch...',
									text: 'Please fill all the required header values',
									//footer: '<a href="">Why do I have this issue?</a>'
								})
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

						function selectInvoiceType(val) 
						{
							if(val == "WITH-GST")
							{
								$('.totalTaxSection').show();
								$('.gst-subtype').show();
							}
							else if(val == "WITH-OUT-GST")
							{
								$('.totalTaxSection').hide();
								$('.gst-subtype').hide();
							}	
						}
						
						function addInvoiceLines()
						{
							$('.line-items-error').text('');

							var flag = 0;
							var invoice_type = $("#invoice_type").val();

							$.ajax({
								url: "<?php echo base_url('purchase_order/getPOLineDatas'); ?>",
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
										var quantity = +row.find('input[name^="quantity[]"]').val();
										var uom = +row.find('input[name^="uom_id[]"]').val();
										var base_price = +row.find('input[name^="base_price[]"]').val();

										if(quantity == "" || base_price == "" ) //|| uom == ""
										{
											flag = 1;
										}
									});
									
									if(flag == 0)
									{
										
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

										var newRow = $("<tr class='remove_tr tabRow"+counter+"'>");
										var cols = "";
										//cols += "<td class='tab-md-30'><input type='hidden' name='id' name='id' value="+i+"><input type='hidden' id='product_id"+counter+"' name='product_id' value=''><input type='hidden' name='counter' name='counter' value="+counter+"></td>";
										cols += "<td class='tab-md-30 text-center'><a class='deleteRow'><i class='fa fa-times-circle-o' style='color:#fb1b1b61;font-size:16px;'></i></a>"+
										"<input type='hidden' name='line_id[]' value='0' id='line_id"+counter+"'>"+
										"<input type='hidden' name='text_product_id[]' value='0' id='text_product_id_"+counter+"'>"+
										"<input type='hidden' name='uom_id[]' value='0' id='uom_id_"+counter+"'>"+
										"<input type='hidden' name='counter' value='"+counter+"'>"+
										"<input type='hidden' name='discount_amount[]' id='discount_line_amount_"+counter+"' value='0'></td>";
										
										cols += "<td class='tab-md-85'>" 
												+"<input type='number' class='form-control mobile_vali' required name='line_num[]' id='line_num"+ counter +"' value='"+counter
												+"'>"
											+"</td>";
										
										cols += "<td class='tab-md-150'>" 
												+"<textarea class='form-control single_quotes' rows='1' required name='line_description[]' id='description"+ counter +"'></textarea>"
											+"</td>";

										cols += "<td class='tab-md-150 totalTaxSection'>" 
												+"<input type='text' class='form-control single_quotes' rows='1' name='hsn[]' class='taxFields"+ counter +"' value=''>"
											+"</td>";	

										cols += "<td class='tab-md-100'>" 
												+"<input type='number' class='form-control' required min='1' name='quantity[]' id='quantity"+ counter +"' value=''>"
											+"</td>";

										cols += '<td class="tab-md-100">'+select_uom+'</td>';
										
										cols += "<td class='tab-md-100'>" 
												+"<input type='number' class='form-control' required name='base_price[]' id='base_price"+ counter +"' value=''>"
											+"</td>";

										cols += '<td class="tab-md-100 totalTaxSection">'+select_tax+'</td>';
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
										
										cols += "<td class='tab-md-100 totalTaxSection'>" 
												+"<input type='number' class='form-control text-right' readonly name='total_tax[]' id='total_tax"+ counter +"' value=''>"
											+"</td>";

										cols += "<td class='tab-md-100'>" 
												+"<input type='number' class='form-control text-right' readonly name='total[]' id='total"+ counter +"' value=''>"
											+"</td>";
				
										cols += "</tr>";
										
										newRow.html(cols);
										$("table.line_items").append(newRow);
										
										if(invoice_type == "WITH-GST")
										{
											$('.totalTaxSection').show();
										}
										else if(invoice_type == "WITH-OUT-GST")
										{
											$('.totalTaxSection').hide();
										}	
										
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

						function deleteRow(line_id,counter)
						{
							var confirmBox = confirm("Are you sure you want to delete the line?");

							if(confirmBox)
							{
								if(line_id)
								{
									$.ajax({
										type: "POST",
										url:"<?php echo base_url().'invoice/deleteLineItems';?>",
										data: { line_id: line_id }
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

								var discountPrice = parseFloat(quantity * discount).toFixed(2);
								$("#discount_line_amount_"+counter).val(discountPrice);

							}
							else if(discount_type =='Percentage') 
							{
								var discount_price = discount / 100 * base_price;

								var discountPrice = parseFloat(quantity * discount_price).toFixed(2);

								$("#discount_line_amount_"+counter).val(discountPrice);

								var price = parseFloat(base_price - discount_price).toFixed(2);
								$("#price"+counter).val(price);
							}
							else
							{
								$("#price"+counter).val(base_price);
								$("#discount_line_amount_"+counter).val(0);
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
							var totalDiscount = 0;

							$("table.line_items").find('input[name^="line_value[]"]').each(function () {
								totalOrderAmount += +$(this).val();
							});
							
							$("table.line_items").find('input[name^="total_tax[]"]').each(function () {
								totalTax += +$(this).val();
							});
							
							$("table.line_items").find('input[name^="total[]"]').each(function () {
								total += +$(this).val();
							});
							
							$("table.line_items").find('input[name^="discount_amount[]"]').each(function () {
								totalDiscount += +$(this).val();
							});

							$('#header_order_amount').val(totalOrderAmount.toFixed(2));
							$('#header_tax').val(totalTax.toFixed(2));
							$('#header_total').val(total.toFixed(2));
							$('#discount_amount').val(totalDiscount.toFixed(2));
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
								if($invoiceMenu['create_edit_only'] == 1 || $this->user_id == 1)
								{
									?>
									<a href="<?php echo base_url(); ?>invoice/manageinvoice/add" class="btn btn-info btn-sm">
										Create Invoice
									</a>
									<?php 
								} 
							?>
						</div>
					</div>
					<!-- Buttons end here -->
					
					<!-- Filters start here -->
					<form action="" class="form-validate-jquery" method="get">
						<div class="row mt-2">
							<div class="col-md-4">
								<div class="row">
									<label class="col-form-label col-md-4 text-right">Invoice Type</label>
									<div class="form-group col-md-7">
										<?php 
											$invoiceType = $this->common_model->lov('INVOICE-TYPE');
										?>
										<select name="invoice_type" id="invoice_type" onchange="getAjaxInvoice(this.value);"  class="form-control searchDropdown">
											<option value="">- Select  -</option>
											<?php 
												foreach($invoiceType as $invoice)
												{
													$selected="";
													if(isset($_GET['invoice_type']) && ($_GET['invoice_type'] == $invoice['list_code']) )
													{
														$selected="selected='selected'";
													}
													?>
													<option value="<?php echo $invoice['list_code']; ?>" <?php echo $selected;?>><?php echo $invoice['list_value']; ?></option>
													<?php 
												} 
											?>
										</select>
									</div>
								</div>
							</div>

							<script>
								function getAjaxInvoice(val)
								{
									if(val !='')
									{
										$.ajax({
											type : "POST",
											url  : "<?php echo base_url().'invoice/getAjaxInvoice';?>",
											data : { id: val }
										}).done(function( msg ) 
										{   
											$( "#header_id" ).html(msg);
										});
									}
									else 
									{ 
										$("#header_id").html('<option value="">- Select -</option>')
									}
								}
							</script>
							
							<div class="col-md-4">
								<div class="row">
									<label class="col-form-label col-md-4 text-right">Invoice #</label>
									<div class="form-group col-md-7">
										<div class="input-wrapper">
											<input type="text" name="invoice_number" autocomplete="off" id="invoice_number" value="<?php echo isset($_GET['invoice_number']) ? $_GET['invoice_number'] : NULL; ?>" placeholder="Invoice Number" class="form-control">
											<input type="hidden" name="invoice_id" autocomplete="off" id="invoice_id" value="<?php echo isset($_GET['invoice_id']) ? $_GET['invoice_id'] : NULL; ?>" >
											<div id="InvoiceList"></div><!-- Clear icon start -->
											<?php 
												if(isset($_GET["invoice_id"]) && !empty($_GET["invoice_id"]))
												{
													$styleDisplay = "display:block";
												}
												else{
													$styleDisplay = "display:none";
												}
												?>
											<span class="invoice_clear_icon" title="Clear" onclick="clearInvoiceSearchKeyword();" style="<?php echo $styleDisplay;?>">
												<i class="fa fa-times" aria-hidden="true"></i>
											</span>

											<script>
												$(document).ready(function()
												{  
													$('#invoice_number').keyup(function()
													{  
														var query = $(this).val();  

														if(query != '')  
														{  
															$.ajax({  
																url:"<?php echo base_url();?>invoice/ajaxInvoiceList",  
																method:"POST",  
																data:{query:query},  
																success:function(data)  
																{  
																	$('#InvoiceList').fadeIn();  
																	$('#InvoiceList').html(data);  
																}  
															});  
														}  
													});

													$(document).on('click', 'ul.list-unstyled-invoice_id li', function()
													{  
														var value = $(this).text();
														
														if(value === "Sorry! Invoice Number Not Found.")
														{
															$('#InvoiceList').fadeOut();
														}
														else
														{
															$('#InvoiceList').fadeOut();  
														}
													});
												});

												function getInvoiceList(invoice_id,invoice_number)
												{
													$('.invoice_clear_icon').show();
													if(invoice_id == 0)	
													{
														$('#invoice_id').val('0');
													}
													else
													{
														$('#invoice_id').val(invoice_id);
														$('#invoice_number').val(invoice_number);
													}
												}

												function clearInvoiceSearchKeyword()
												{
													$(".invoice_clear_icon").hide();
													$("#invoice_id").val("");
													$("#invoice_number").val("");
												}
											</script>

										</div>
									</div>
								</div>
							</div>

							<div class="col-md-4">
								<div class="row">
									<label class="col-form-label col-md-4 text-right">Customer</label>
									<div class="form-group col-md-7">
										<div class="input-wrapper">
											<input type="text" name="customer_name" autocomplete="off" id="customer_name" value="<?php echo isset($_GET['customer_name']) ? $_GET['customer_name'] : NULL; ?>" placeholder="Customer Name" class="form-control">
											<input type="hidden" name="customer_id" autocomplete="off" id="customer_id" value="<?php echo isset($_GET['customer_id']) ? $_GET['customer_id'] : NULL; ?>" >
											<div id="CustomerList"></div><!-- Clear icon start -->
											<?php 
												if(isset($_GET["customer_id"]) && !empty($_GET["customer_id"]))
												{
													$styleDisplay = "display:block";
												}else{
													$styleDisplay = "display:none";
												}
												?>
											<span class="customer_clear_icon" title="Clear" onclick="clearCustomerSearchKeyword();" style="<?php echo $styleDisplay;?>">
												<i class="fa fa-times" aria-hidden="true"></i>
											</span>

											<script>
												$(document).ready(function()
												{  
													$('#customer_name').keyup(function()
													{  
														var query = $(this).val();  

														if(query != '')  
														{  
															$.ajax({  
																url:"<?php echo base_url();?>customer/ajaxCustomerList",  
																method:"POST",  
																data:{query:query},  
																success:function(data)  
																{  
																	$('#CustomerList').fadeIn();  
																	$('#CustomerList').html(data);  
																}  
															});  
														}  
													});

													$(document).on('click', 'ul.list-unstyled-customer_id li', function()
													{  
														var value = $(this).text();
														
														if(value === "Sorry! Customer Not Found.")
														{
															$('#CustomerList').fadeOut();
														}
														else
														{
															$('#CustomerList').fadeOut();  
														}
													});
												});

												function getCustomerList(customer_id,customer_name)
												{
													$('.customer_clear_icon').show();
													if(customer_id == 0)	
													{
														$('#customer_id').val('0');
													}
													else
													{
														$('#customer_id').val(customer_id);
														$('#customer_name').val(customer_name);
													}
												}

												function clearCustomerSearchKeyword()
												{
													$(".customer_clear_icon").hide();
													$("#customer_id").val("");
													$("#customer_name").val("");
												}
											</script>

										</div>
									</div>
								</div>
							</div>
						</div>

						<div class="row mt-2">
							<div class="col-md-4">
								<div class="row">
									<label class="col-form-label col-md-4 text-right">From Date</label>
									<div class="form-group col-md-7">
										<input type="text" name="from_date" id="from_date" class="form-control" readonly value="<?php echo !empty($_GET['from_date']) ? $_GET['from_date'] : ""; ?>" placeholder="From Date">
									</div>
								</div>
							</div>

							<div class="col-md-4">
								<div class="row">
									<label class="col-form-label col-md-4 text-right">To Date</label>
									<div class="form-group col-md-7">
										<input type="text" name="to_date" id="to_date" class="form-control" readonly value="<?php echo !empty($_GET['to_date']) ? $_GET['to_date'] :""; ?>" placeholder="To Date">
									</div>
								</div>
							</div>

							<div class="col-md-2">
								<button type="submit" class="btn btn-info">Search <i class="fa fa-search" aria-hidden="true"></i></button>
								<a href="<?php echo base_url(); ?>invoice/manageinvoice" title="Clear" class="btn btn-default">Clear</a>
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
								<div class="col-md-8 mt-3">
									<?php 
										if( count($resultData) > 0 )
										{
											?>
											<a href="<?php echo base_url().$this->redirectURL;?>&export=export" class="btn btn-primary btn-sm">
												<i class="fa fa-download"></i> Download Excel
											</a>
											<?php 
										} 
									?>
								</div>
								<?php 
									$redirect_url = substr($_SERVER['REQUEST_URI'],'1');
								?>
								<div class="col-md-4 text-right">
									<input type="hidden" id="redirect_url" value="<?php echo $redirect_url; ?>"/>
															
									<div class="filter_page">
										<label>
											<span style="color:blue;">Currency : <?php echo CURRENCY_CODE;?></span> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
											
											<span>Show :</span> 
											<select name="filter" onchange="location.href='<?php echo base_url(); ?>admin/sort_itemper_page/'+$(this).val()+'?redirect=<?php echo $redirect_url; ?>'">
												<?php 
													$pageLimit = $_SESSION['PAGE'];
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
											<th class="text-center">Controls</th>
											<th class="tab-md-100">Invoice Type</th>
											<th class="tab-md-120">Invoice Number</th>
											<th class="tab-md-120">Invoice Status</th>
											<th class="tab-md-100">Invoice Date</th>
											<th class="tab-md-140">Customer Name</th>
											<th class="tab-md-100">Payment Term</th>
											<th class="tab-md-140">Invoice Due Date</th>
											<th class="tab-md-150">Description</th>
											<th class="tab-md-100">PO Number</th>
											<th class="tab-md-100">PO Date</th>
											<th class="tab-md-100">DC Number</th>
											<th class="tab-md-100">DC Date</th>
											<th class="tab-md-100 text-right">Amount</th>
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
																	if($invoiceMenu['create_edit_only'] == 1 || $invoiceMenu['read_only'] == 1 || $this->user_id == 1)
																	{ 
																		?>
																		
																		<?php
																			if($invoiceMenu['create_edit_only'] == 1 || $this->user_id == 1)
																			{
																				if($row['invoice_status'] == "DRAFT")
																				{
																					?>
																					<li>
																						<a href="<?php echo base_url(); ?>invoice/manageinvoice/edit/<?php echo $row['header_id'];?>">
																							<i class="fa fa-pencil"></i> Edit
																						</a>
																					</li>
																					<?php
																				}
																			}

																			if($invoiceMenu['read_only'] == 1 || $this->user_id == 1)
																			{
																				?>
																				<li>
																					<a href="<?php echo base_url(); ?>invoice/manageinvoice/view/<?php echo $row['header_id'];?>">
																						<i class="fa fa-eye"></i> View
																					</a>
																				</li>
																				<?php 
																			} 
																		?>
																				

																		<li>
																			<a target="_blank" href="<?php echo base_url(); ?>invoice/generatePDF/<?php echo $row['header_id'];?>">
																				<i class="fa fa-file-pdf-o"></i> Download PDF
																			</a>
																		</li>

																		<li>
																			<a target="_blank" href="<?php echo base_url(); ?>invoice/sendInvoice/<?php echo $row['header_id'];?>">
																				<i class="fa fa-whatsapp"></i> Send Invoice
																			</a>
																		</li>

																		<?php 
																			/* if($row['invoice_status'] != "PAID")
																			{

																				?>
																				<li>
																					<a title="Update Status" href="javascript:void(0);" data-toggle="modal" data-target="#exampleModal<?php echo $row['header_id'];?>">
																						<i class="fa fa-refresh" aria-hidden="true"></i> Update Status
																					</a>
																				</li>
																				<?php 
																			} */ 
																		?>

																		<?php 
																	} 
																?>
															</ul>
														</div>
														
														<?php /*
														<!-- Update status model dialog box start -->
														<div class="modal fade" id="exampleModal<?php echo $row['header_id'];?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel<?php echo $row['header_id'];?>" aria-hidden="true">
															<div class="modal-dialog" role="document" style="width:30%;">
																<form action="" method="POST">
																	<div class="modal-content">
																		<div class="modal-header">
																			<h5 class="modal-title" id="exampleModalLabel<?php echo $row['header_id'];?>">Update Invoice Status</h5>
																			<button type="button" class="close" data-dismiss="modal" aria-label="Close">
																				<span aria-hidden="true">&times;</span>
																			</button>
																		</div>

																		<div class="modal-body">
																			<div class="row">
																				<label class="col-form-label col-md-5 text-right">Invoice Number</label>
																				<div class="form-group col-md-7">
																					<input type="hidden" name="header_id" id="header_id" class="form-control" readonly value="<?php echo $row['header_id'];?>" >
																					<input type="text" name="invoice_number" id="invoice_number" class="form-control" readonly value="<?php echo $row['invoice_number'];?>" placeholder="Invoice Number">
																				</div>
																			</div>

																			<div class="row">
																				<label class="col-form-label col-md-5 text-right"><span class="text-danger">*</span> Invoice Status</label>
																				<div class="form-group col-md-7">
																					
																					<select name="invoice_status" id="invoice_status" style="width:150px;" required class="form-control searchDropdown">
																						<option value="">- Select -</option>
																						<?php 
																							foreach($getInvoiceStatus as $invoiceStatus)
																							{
																								$selected="";
																								if(isset($row['invoice_status']) && $row['invoice_status'] == $invoiceStatus["list_code"] )
																								{
																									$selected="selected='selected'";
																								}
																								?>
																								<option value="<?php echo $invoiceStatus["list_code"];?>" <?php echo $selected;?>><?php echo $invoiceStatus["list_value"];?></option>
																								<?php 
																							} 
																						?>
																					</select>
																							
																				</div>
																			</div>
																		</div>
																		
																		<div class="modal-footer">
																			<!-- <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button> -->
																			<button type="submit" class="btn btn-primary" name="update_status">Save</button>
																		</div>
																		
																	</div>
																</form>
															</div>
														</div>
														<!-- Update status model dialog box end --> */ ?>
													</td>

													<td><?php echo $row['invoiceType'];?> </td>
													<td><?php echo $row['invoice_number'];?></td>
													<td>
														<?php 
															foreach($getInvoiceStatus as $invoiceStatus)
															{
																if(isset($row['invoice_status']) && $row['invoice_status'] == $invoiceStatus["list_code"] )
																{
																	echo $invoiceStatus["list_value"];
																}
															} 
														?>
													</td>
												
													<td>
														<?php echo date(DATE_FORMAT,strtotime($row['invoice_date']));?>
													</td>
													<td><?php echo $row['customer_name'];?></td>	

													<td><?php echo $row['payment_term'];?></td>	
													<td>
														<?php 
															if($row['invoice_due_date'] !=NULL)
															echo date(DATE_FORMAT,strtotime($row['invoice_due_date']));
														?>	
													</td>	
																			
													<td><?php echo $row['description'];?></td>													
													<td><?php echo $row['po_number'];?></td>
													<td>													
														<?php 
															if($row['po_date'] !=NULL && !empty($row['po_date']))
																echo date(DATE_FORMAT,strtotime($row['po_date']));
														?>	
													</td>												
													<td><?php echo $row['dc_number'];?></td>

													<td>													
														<?php 
															if($row['dc_date'] !=NULL)
															echo date(DATE_FORMAT,strtotime($row['dc_date']));
														?>	
													</td>	

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

                                
								