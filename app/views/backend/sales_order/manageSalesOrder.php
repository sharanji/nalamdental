<?php 
	$salesOrderMenu = accessMenu(sales_order);
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
													echo ucfirst($type)." ";
												}
												else if($type == "view")
												{
													echo ucfirst($type)." ";
												}  

												echo ucfirst($page_title);
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
										else if($type == "view")
										{
											?>
												<a href="javascript:void(0)" onclick="generateSalesOrdersPDF('SO_PRINT','<?php echo $id;?>')" class="btn-sm ml-0 btn btn-primary --fine-dine-placeorder" title="Print Bill">
													<i class="fa fa-print"></i> Print 
												</a>
											<?php 
										}
									?>
									<script>
										function generateSalesOrdersPDF(button_type,sales_header_id)
										{
											//alert(button_type+'@'+sales_header_id);
											if(sales_header_id)
											{
												$.ajax({
													type: 'post',
													url: '<?php echo base_url();?>sales_order/generateSalesOrdersPDF/'+button_type+'/'+sales_header_id,
													data: {button_type:button_type,sales_header_id:sales_header_id},
													success: function (result) 
													{
														printSalesKOTPDF(button_type,sales_header_id); 
													}
												});
											}
										}

										function printSalesKOTPDF(button_type,interface_header_id)
										{
											var orderID = interface_header_id;
											
											if(orderID > 0 && orderID !="")
											{
												toastr.printing('Printing Sales KOT...');

												$.ajax({
													url      : '<?php echo base_url(); ?>sales_order/chkbill/'+orderID,
													type     : "POST",
													data     : {},
													datatype : JSON,
													success  : function(d)
													{
														response = JSON.parse(d);

														var htmlKOTContent = response["salesKOTPath"];
														var print_items = response["print_items"];

														var countKey = Object.keys(print_items).length;
														
														if( countKey > 0 )
														{
															$.each(print_items, function(i, item) 
															{
																var print_type = item.print_type; // #Cashier #KOT
																
																if(print_type == "STORE_KOT")
																{
																	var printer_name = item.printer_name;
																	var printer_count = item.printer_count;
																	
																	for(i=1; i<=printer_count; i++)
																	{
																		salesAutoPrint(jspmWSStatus(),htmlKOTContent,orderID,printer_name,button_type);
																	}
																}     
															});
														}     	
													}
												});   
											}
										}

										function salesAutoPrint(printerStatus,htmlContent,orderID,printer_name,button_type)
										{
											if (printerStatus && htmlContent !="") 
											{
												var cpj = new JSPM.ClientPrintJob();

												var printerName = printer_name;
												
												var myPrinter = new JSPM.InstalledPrinter(printerName); //printer name
												
												cpj.clientPrinter = myPrinter;
												
												var orderPDFPath = htmlContent;
												var currenttime = '<?php echo rand();?>';
												var my_file = new JSPM.PrintFilePDF(orderPDFPath, JSPM.FileSourceType.URL, 'MyFile_'+currenttime+'.pdf', 1);
												cpj.files.push(my_file);
												cpj.sendToClient();
											}
										}
									</script>
									<a href="<?php echo base_url(); ?>sales_order/manageSalesOrder" class="btn btn-default btn-sm">Close</a>
								</div>
							</div>
							<!-- Buttons end here -->
							
							<fieldset <?php echo $fieldSetDisabled;?>>
								<!-- Header Section Start Here-->
								<section class="header-section">
									<div class="row">
										<div class="col-md-4">
											<div class="row">
												<label class="col-form-label col-md-6 text-right">Order Number</label>
												<div class="form-group col-md-6">
													<input type="text" name="order_number" id="order_number" readonly autocomplete="off" class="form-control no-outline" value="<?php echo isset($edit_data[0]['order_number']) ? $edit_data[0]['order_number'] : NULL;?>" placeholder="">
												</div>
											</div>
										</div>
										<div class="col-md-4">
											<div class="row">
												<label class="col-form-label col-md-6 text-right"><span class="text-danger">*</span> Customer</label>
												<div class="form-group col-md-6">
													<?php 
														$customerQry = "select customer_id,customer_name,mobile_number from cus_customers where active_flag='Y'";
														$getCustomers = $this->db->query($customerQry)->result_array();
													?>
													<select name="customer_id" id="customer_id" onchange="selectCustomerDetails(this.value);" required class="form-control <?php echo $searchDropdown;?>">
														<option value="">- Select -</option>
														<?php 
															foreach($getCustomers as $row)
															{
																$selected="";
																if(isset($edit_data[0]['customer_id']) && $edit_data[0]['customer_id'] == $row["customer_id"] )
																{
																	$selected="selected='selected'";
																}
																?>
																<option value="<?php echo $row["customer_id"];?>" <?php echo $selected;?>><?php echo $row["customer_name"];?></option>
																<?php 
															} 
														?>
													</select>
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
													<select name="so_currency" id="so_currency" required class="form-control <?php echo $searchDropdown;?>">
														<option value="">- Select -</option>
														<?php 
															foreach($getCurrency as $row)
															{
																$selected="";
																if(isset($edit_data[0]['so_currency']) && $edit_data[0]['so_currency'] == $row["currency_id"] )
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

										<input type="hidden" name="so_currency" id="so_currency" class="form-control" value="" placeholder="">
											
										<script>
											function selectCustomerDetails(val)
											{
												getAjaxBillAndShiptoAddress(val,'BILL_TO');
												getAjaxBillAndShiptoAddress(val,'SHIP_TO');
												getAjaxCustomerDetails(val);

												$("#bill_to_complete_address").val("");
												$("#ship_to_complete_address").val("");
												
											}

											function getAjaxCustomerDetails(val)
											{
												if(val !='')
												{
													$.ajax({
														type: "POST",
														url:"<?php echo base_url().'sales_order/getAjaxCustomerDetails';?>",
														data: { id: val }
													}).done(function( msg ) 
													{   
														$( "#customer_contact" ).val(msg);
													});
												}
												else 
												{ 
													$( "#customer_contact" ).val('');
												}
											}

											function getAjaxBillAndShiptoAddress(val,site_type)
											{
												if(val !='')
												{
													$.ajax({
														type: "POST",
														url:"<?php echo base_url().'sales_order/getAjaxBillAndShiptoAddress';?>",
														data: { id: val,site_type: site_type, }
													}).done(function( msg ) 
													{   
														if(site_type == "BILL_TO")
														{
															$( "#bill_to_address" ).html(msg);
														}
														else if(site_type == "SHIP_TO")
														{
															$( "#ship_to_address" ).html(msg);
														}
													});
												}
												else 
												{ 
													if(site_type == "BILL_TO")
													{
														$( "#bill_to_address" ).html("<option value=''>- Select -</option>");
													}
													else if(site_type == "SHIP_TO")
													{
														$( "#ship_to_address" ).html("<option value=''>- Select -</option>");
													}
												}
											}
										</script>
									</div>
									
									<div class="row">
										<div class="col-md-4">
											<div class="row">
												<label class="col-form-label col-md-6 text-right">Order date</label>
												<div class="form-group col-md-6">
												<input type="text" name="order_date" id="order_date" readonly autocomplete="off" class="form-control no-outline -default_date" value="<?php echo isset($edit_data[0]['order_date']) ? date("d-M-Y",strtotime($edit_data[0]['order_date'])) : date("d-M-Y");?>" placeholder="">
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
										</div>

										<?php /*
										<div class="col-md-4">
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

										<input type="hidden" name="payment_term_id" id="payment_term_id" class="form-control" value="" placeholder="">
										

										<?php /* <div class="col-md-4">
											<div class="row">
												<label class="col-form-label col-md-6 text-right">Order Amount </label>
												<div class="form-group col-md-6">
													<input type="text" name="header_order_amount" id="header_order_amount" readonly autocomplete="off" required class="form-control no-outline" value="<?php echo isset($edit_data[0]['order_amount']) ? number_format($edit_data[0]['order_amount'],DECIMAL_VALUE,'.','') : "0.00";?>" placeholder="">
												</div>
											</div>
										</div> */ ?>
									</div>

									<div class="row">
										
										<!-- <div class="col-md-4">
											<div class="row">
												<label class="col-form-label col-md-6 text-right">Status</label>
												<div class="form-group col-md-6">
													<input type="text" name="header_status" id="header_status" readonly autocomplete="off" class="form-control no-outline" value="<?php echo isset($edit_data[0]['so_status']) ? $edit_data[0]['so_status'] : "Draft";?>" placeholder="">
												</div>
											</div>
										</div> -->

										<input type="hidden" name="header_status" id="header_status" readonly autocomplete="off" class="form-control no-outline" value="<?php echo isset($edit_data[0]['so_status']) ? $edit_data[0]['so_status'] : "Draft";?>" placeholder="">
												


										

										<div class="col-md-4">
											<div class="row">
												<label class="col-form-label col-md-6 text-right">Description </label>
												<div class="form-group col-md-6">
													<input type="text" name="header_description" id="header_description" <?php echo $fieldReadonly;?> autocomplete="off" class="form-control single_quotes" value="<?php echo isset($edit_data[0]['description']) ? $edit_data[0]['description'] : NULL;?>" placeholder="Description">
												</div>
											</div>
										</div>

										<div class="col-md-4">
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
										</div>
										
										
										<?php /* <div class="col-md-4">
											<div class="row">
												<label class="col-form-label col-md-6 text-right">Tax </label>
												<div class="form-group col-md-6">
													<input type="text" name="header_tax" id="header_tax" readonly autocomplete="off" required class="form-control no-outline" value="<?php echo isset($edit_data[0]['total_tax']) ? number_format($edit_data[0]['total_tax'],DECIMAL_VALUE,'.','') : "0.00";?>" placeholder="">
												</div>
											</div>
										</div> */ ?>
									</div>

									<div class="row">
										
										<?php /* <div class="col-md-4">
											<div class="row">
												<label class="col-form-label col-md-6 text-right">Customer PO </label>
												<div class="form-group col-md-6">
													<input type="text" name="customer_po" id="customer_po" <?php echo $fieldReadonly;?> autocomplete="off" class="form-control single_quotes" value="<?php echo isset($edit_data[0]['customer_po']) ? $edit_data[0]['customer_po'] : NULL;?>" placeholder="">
												</div>
											</div>
										</div>  */ ?>

										<input type="hidden" name="customer_po" id="customer_po" <?php echo $fieldReadonly;?> autocomplete="off" class="form-control single_quotes" value="<?php echo isset($edit_data[0]['customer_po']) ? $edit_data[0]['customer_po'] : NULL;?>" placeholder="">
												

										<div class="col-md-4">
											<div class="row">
												<label class="col-form-label col-md-6 text-right">Customer Contact</label>
												<div class="form-group col-md-6">
													<input type="text" name="customer_contact" id="customer_contact" <?php echo $fieldReadonly;?> autocomplete="off" class="form-control single_quotes" value="<?php echo isset($edit_data[0]['customer_contact']) ? $edit_data[0]['customer_contact'] : NULL;?>" placeholder="Customer Contact">
												</div>
											</div>
										</div>
						
										<?php /* <div class="col-md-4">
											<div class="row">
												<label class="col-form-label col-md-6 text-right">Total </label>
												<div class="form-group col-md-6">
													<input type="text" name="header_total" id="header_total" readonly autocomplete="off" required class="form-control no-outline" value="<?php echo isset($edit_data[0]['total']) ? number_format($edit_data[0]['total'],DECIMAL_VALUE,'.','') : "0.00";?>" placeholder="">
												</div>
											</div>
										</div> */ ?>
									</div>

									<input type="hidden" name="bill_to_address" id="bill_to_address" vlaue="<?php echo isset($edit_data[0]['bill_to_address']) ? $edit_data[0]['bill_to_address'] : NULL;?>">
									<input type="hidden" name="ship_to_address" id="ship_to_address" vlaue="<?php echo isset($edit_data[0]['ship_to_address']) ? $edit_data[0]['ship_to_address'] : NULL;?>">
									
									<div class="row">
										<?php /* <div class="col-md-4">
											<div class="row">
												<label class="col-form-label col-md-6 text-right"><span class="text-danger">*</span> Bill To Address </label>
												<div class="form-group col-md-6">
													<select name="bill_to_address" onchange="ajaxGetCompleteAddress(this.value,'BILL_TO');" id="bill_to_address" class="form-control <?php echo $searchDropdown;?>">
														<option value="">- Select -</option>
														<?php 
															if($type == 'edit' || $type == 'view')
															{
																$bill_to_address = isset($edit_data[0]['bill_to_address']) ? $edit_data[0]['bill_to_address'] : NULL;

																$billToAddressQry = "select customer_site_id,site_name from cus_customer_sites 
																where 
																site_type='BILL_TO'
																and customer_id='".$edit_data[0]['customer_id']."'
																";
																$getBillToAddress = $this->db->query($billToAddressQry)->result_array();



																foreach($getBillToAddress as $billToAddress)
																{
																	$selected="";
																	if(isset($edit_data[0]['bill_to_address']) && isset($edit_data[0]['bill_to_address']) == $billToAddress["customer_site_id"])
																	{
																		$selected="selected='selected'";
																	}
																	?>
																	<option value="<?php echo $billToAddress["customer_site_id"];?>" <?php echo $selected;?>><?php echo $billToAddress["site_name"];?></option>
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
												<label class="col-form-label col-md-6 text-right"><span class="text-danger">*</span> Ship To Address </label>
												<div class="form-group col-md-6">
													<select name="ship_to_address" id="ship_to_address" onchange="ajaxGetCompleteAddress(this.value,'SHIP_TO');" class="form-control <?php echo $searchDropdown;?>">
														<option value="">- Select -</option>
														<?php 
															if($type == 'edit' || $type == 'view')
															{
																$ship_to_address = isset($edit_data[0]['ship_to_address']) ? $edit_data[0]['ship_to_address'] : NULL;

																$shipToAddressQry = "select customer_site_id,site_name from cus_customer_sites 
																where 
																site_type='SHIP_TO'
																and customer_id='".$edit_data[0]['customer_id']."'
																";
																$getShipToAddress = $this->db->query($shipToAddressQry)->result_array();
																foreach($getShipToAddress as $shipToAddress)
																{
																	$selected="";
																	if(isset($edit_data[0]['ship_to_address']) && isset($edit_data[0]['ship_to_address']) == $shipToAddress["customer_site_id"])
																	{
																		$selected="selected='selected'";
																	}
																	?>
																	<option value="<?php echo $shipToAddress["customer_site_id"];?>" <?php echo $selected;?>><?php echo $shipToAddress["site_name"];?></option>
																	<?php
																}
															}
														?>
													</select>			
												</div>
											</div>
										</div> */ ?>
										
									</div>

									<div class="row">
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

											function ajaxGetCompleteAddress(val,site_type)
											{
												if(site_type =="BILL_TO")
												{
													if(val)
													{
														$(".bill_to_complete_address").show();

														$.ajax({
															type: "POST",
															url:"<?php echo base_url().'sales_order/getAjaxCompleteAddress';?>",
															data: { id: val,site_type: site_type, }
														}).done(function( msg ) 
														{   
															$( "#bill_to_complete_address" ).val(msg);
														});
													}
													else
													{
														$(".bill_to_complete_address").hide();
														$("#bill_to_complete_address").val("");
													}
												}
												else if(site_type =="SHIP_TO")
												{
													if(val)
													{
														$(".ship_to_complete_address").show();

														$.ajax({
															type: "POST",
															url:"<?php echo base_url().'sales_order/getAjaxCompleteAddress';?>",
															data: { id: val,site_type: site_type, }
														}).done(function( msg ) 
														{   
															$( "#ship_to_complete_address" ).val(msg);
														});
													}
													else
													{
														$(".ship_to_complete_address").hide();
														$("#ship_to_complete_address").val("");
													}	
												}
											}
										</script>
										<?php /* <div class="col-md-4 float-right text-right">
											<div class="row bill_to_complete_address" --style="display:none;">
												<div class="col-md-3"></div>
												<div class="form-group col-md-9">
													<?php
														if($type =="edit" || $type =="view")
														{
															$customer_site_id = isset($edit_data[0]['bill_to_address']) ? $edit_data[0]['bill_to_address'] : NULL;

															$site_type = "BILL_TO";

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
																and cus_site.customer_site_id='".$customer_site_id."'";


															$siteData =  $this->db->query($qry)->result_array();

															$address1 = !empty($siteData[0]["address1"]) ? $siteData[0]["address1"].", " :NULL;
															$address2 = !empty($siteData[0]["address2"]) ? $siteData[0]["address2"].", " :NULL;
															$address3 = !empty($siteData[0]["address3"]) ? $siteData[0]["address3"].", " :NULL;
															$city_name = !empty($siteData[0]["city_name"]) ? $siteData[0]["city_name"].", " :NULL;
															$state_name = !empty($siteData[0]["state_name"]) ? $siteData[0]["state_name"].", " :NULL;
															$country_name = !empty($siteData[0]["country_name"]) ? $siteData[0]["country_name"].", " :NULL;
															$postal_code = !empty($siteData[0]["postal_code"]) ? $siteData[0]["postal_code"]."." :NULL;
															
															$bill_to_complete_address = $address1.$address2.$address3.$city_name.$state_name.$country_name.$postal_code;
														}
														else{
															$bill_to_complete_address = '';
														}	
													?>
													<textarea name="bill_to_complete_address" rows="4" id="bill_to_complete_address" readonly rows="1" <?php echo $fieldReadonly;?> autocomplete="off" class="form-control" placeholder="Billing Address"><?php echo $bill_to_complete_address;?></textarea>
												</div>
											</div>
										</div> */ ?>

										<?php /* <div class="col-md-4 float-right text-right">
											<div class="row ship_to_complete_address" --style="display:none;">
												<div class="col-md-3"></div>
												<div class="form-group col-md-9">
													<?php
														if($type =="edit" || $type =="view")
														{
															$customer_site_id = isset($edit_data[0]['ship_to_address']) ? $edit_data[0]['ship_to_address'] : NULL;

															$site_type = "SHIP_TO";

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
																and cus_site.customer_site_id='".$customer_site_id."'";


															$siteData =  $this->db->query($qry)->result_array();

															$address1 = !empty($siteData[0]["address1"]) ? $siteData[0]["address1"].", " :NULL;
															$address2 = !empty($siteData[0]["address2"]) ? $siteData[0]["address2"].", " :NULL;
															$address3 = !empty($siteData[0]["address3"]) ? $siteData[0]["address3"].", " :NULL;
															$city_name = !empty($siteData[0]["city_name"]) ? $siteData[0]["city_name"].", " :NULL;
															$state_name = !empty($siteData[0]["state_name"]) ? $siteData[0]["state_name"].", " :NULL;
															$country_name = !empty($siteData[0]["country_name"]) ? $siteData[0]["country_name"].", " :NULL;
															$postal_code = !empty($siteData[0]["postal_code"]) ? $siteData[0]["postal_code"]."." :NULL;
															
															$ship_to_complete_address = $address1.$address2.$address3.$city_name.$state_name.$country_name.$postal_code;
														}
														else{
															$ship_to_complete_address = '';
														}	
													?>
													<textarea name="ship_to_complete_address" rows="4" id="ship_to_complete_address" readonly rows="1" <?php echo $fieldReadonly;?> autocomplete="off" class="form-control" placeholder="Shipping Address"><?php echo $ship_to_complete_address;?></textarea>
												</div>
											</div> 
										</div> */ ?>
										
										<!--div class="col-md-4 text-right float-right">
											<a href="javascript:void();" class="btn btn-primary btn-sm">Attachment</a>
										</div-->
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

									<?php 
										if($type == "add" || $type == "edit")
										{
											?>
											<div class="row mt-2 mb-3">
												<div class="col-md-6">
													<a href="javascript:void(0);" onclick="addLines(1);" class="btn btn-primary btn-sm">Add</a>
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
													<th class="tab-md-85">Line No <span class="text-danger">*</span></th>
													<th class="tab-md-130">Item <span class="text-danger">*</span></th>
													<th class="tab-md-150">Description</th>
													<th class="tab-md-150">Category</th>
													<!-- <th class="tab-md-150">Customer Item</th> -->
													<!-- <th class="tab-md-150">Status</th> -->
													<th class="tab-md-100">Quantity <span class="text-danger">*</span></th>
													<th class="tab-md-100">UOM <span class="text-danger">*</span></th>
													<!-- <th class="text-center tab-md-100">Unit Price <span class="text-danger">*</span></th>
													<th class="text-center tab-md-100">Tax</th>
													<th class="text-center tab-md-100">Discount Type</th>
													<th class="text-center tab-md-100">Discount</th>
													<th class="text-center tab-md-100">Discount Reason</th>
													<th class="text-center tab-md-150">Effective Price</th>
													<th class="text-center tab-md-100">Line Value</th>
													<th class="text-center tab-md-100">Total Tax</th>											
													<th class="text-center tab-md-100">Total</th> -->
													<!-- <th class="text-center tab-md-150">Delivery Date <span class="text-danger">*</span></th> -->									
													<!-- <th class="text-center tab-md-150">Organization</th>											 -->
													<!-- <th class="text-center tab-md-150">Sub Inventory</th>											
													<th class="text-center tab-md-150">Locator</th>											
													<th class="text-center tab-md-150">Lot Number</th>											
													<th class="text-center tab-md-150">Serial Number</th>											 -->
													<!-- <th class="text-center tab-md-150">Cancelled Reason</th> -->											
												</tr>
											</thead>
											<tbody>
												<?php 
													if($type == "edit" || $type == "view")
													{
														$itemQuery = " select
																transaction.transaction_id,
																sum(transaction.transaction_qty) as trans_qty,
																item.item_id,
																transaction.organization_id,
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
																where 
																transaction_type ='RCV'
																group by 
																transaction.item_id,
																transaction.organization_id,
																transaction.sub_inventory_id,
																transaction.locator_id,
																transaction.lot_number,
																transaction.serial_number";

																
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
															$dropdownReadonly = "style='pointer-events: none'";
															foreach($line_data as $lineItems)
															{
																?>
																<tr class="remove_tr tabRow<?php echo $counter;?>">
																	<?php 
																		if($type == "add" || $type == "edit")
																		{
																			?>
																			<td class="tab-md-30 text-center">
																				
																				<a onclick="deleteRow('<?php echo $lineItems['sales_line_id'];?>','<?php echo $counter;?>');">
																					<i class="fa fa-times-circle-o" style="color:#fb1b1b61;font-size:16px;"></i>
																				</a>
																				
																				<input type="hidden" name="sales_line_id[]" value="<?php echo $lineItems["sales_line_id"];?>" id="sales_line_id<?php echo $counter; ?>">
																				<input type="hidden" name="transaction_id[]" value="<?php echo $lineItems["attribute1"];?>" id="transaction_id<?php echo $counter; ?>">
																				<input type="hidden" name="text_product_id[]" value="<?php echo $lineItems["item_id"];?>" id="text_product_id_<?php echo $counter; ?>">
																				<!-- <input type="hidden" name="organization_id[]" value="<?php echo $lineItems["organization_id"];?>" id="organization_id<?php echo $counter; ?>"> -->
																				<input type="hidden" name="sub_inventory_id[]" value="<?php echo $lineItems["sub_inventory_id"];?>" id="sub_inventory_id<?php echo $counter; ?>">
																				<input type="hidden" name="locator_id[]" value="<?php echo $lineItems["locator_id"];?>" id="locator_id<?php echo $counter; ?>">
																				<input type="hidden" name="trans_qty[]" value="<?php echo $lineItems["quantity"];?>" id="trans_qty<?php echo $counter; ?>">
																				<input type="hidden" name="counter" value="<?php echo $counter; ?>">
																			</td>
																			<?php 
																		} 
																	?>
																	<td class="tab-md-85">
																		<input type="number" class="form-control mobile_vali" required name="line_num[]" id="line_num<?php echo $counter; ?>" value="<?php echo $lineItems["line_num"];?>">
																	</td>
																	<td class="tab-md-200">
																		<select class="form-control <?php #echo $searchDropdown; ?>" <?php echo $dropdownReadonly; ?> readonly onchange="selectItemDetails(this.value,<?php echo $counter; ?>);" name="transaction_ids[]" id="transaction_ids<?php echo $counter; ?>">
																			<option value="">- Select -</option>
																			<?php 
																				foreach($getItems as $items)
																				{
																					$selected="";
																					/* if($lineItems["attribute1"] == $items["transaction_id"]){
																						$selected="selected='selected'";
																					} */

																					if($lineItems["item_id"] == $items["item_id"]){
																						$selected="selected='selected'";
																					}

																					if($items["lot_number"] != null)
																					{
																						$lot_number = "- ".$items["lot_number"];
																					}
																					else 
																					{
																						$lot_number = "";
																					}

																					if($items["serial_number"] != null)
																					{
																						$serial_number = "- ".$items["serial_number"];
																					}
																					else 
																					{
																						$serial_number = "";
																					}
												
																					?>
																					<option value="<?php echo $items["transaction_id"];?>" <?php echo $selected;?>><?php echo $items["item_name"];?><?php echo $lot_number;?><?php echo $serial_number;?></option>
																					<?php 
																				} 
																			?>
																		</select>
																	</td>
																	<td class="tab-md-150"><textarea class="form-control" rows="1" readonly name="description[]" id="description<?php echo $counter; ?>"><?php echo $lineItems["item_description"];?></textarea></td>
																	<td class="tab-md-150"><input type="text" class="form-control" readonly readonlyname="category_name[]" id="category_name<?php echo $counter; ?>" value="<?php echo $lineItems["category_name"];?>"></td>
																	
																	<?php /* <td class="tab-md-150"><textarea class="form-control" rows="1" name="customer_item[]" id="customer_item<?php echo $counter; ?>"><?php echo $lineItems["customer_item"];?></textarea></td>
																	<td class="tab-md-100"><input type="text" class="form-control" readonly name="line_status[]" id="line_status<?php echo $counter; ?>" value="<?php echo $lineItems["line_status"];?>"></td>
																	*/ ?>
																	<td class="tab-md-100"><input type="number" class="form-control" min="1" required name="quantity[]" id="quantity<?php echo $counter; ?>" max="<?php echo $lineItems["trans_qty"];?>" value="<?php echo $lineItems["quantity"];?>"></td>
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
																	<?php /* <td class="tab-md-100"><input type="number" class="form-control" required name="unit_price[]" id="unit_price<?php echo $counter; ?>" value="<?php echo $lineItems["unit_price"];?>"></td>
																	<td class="tab-md-100">
																		<select class="form-control <?php echo $searchDropdown; ?>" onchange="selectTax(this.value,<?php echo $counter; ?>);" name="tax[]" id="tax<?php echo $counter; ?>">
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
																		<select class="form-control <?php echo $searchDropdown; ?>" onchange="selectDiscountTypes(this.value,<?php echo $counter; ?>);" name="discount_type[]" id="discount_type1">
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
																	<td class="tab-md-100"><input type="number" class="form-control" readonly="" name="discount[]" id="discount<?php echo $counter; ?>" value="<?php echo $lineItems["discount"];?>"></td> 
																	<td class="tab-md-150"><textarea class="form-control" rows="1" readonly="" name="discount_reason[]" id="discount_reason<?php echo $counter; ?>"><?php echo $lineItems["discount_reason"];?></textarea></td> 
																	<td class="tab-md-100"><input type="number" class="form-control text-right" readonly="" name="price[]" id="price<?php echo $counter; ?>" value="<?php echo $lineItems["effective_price"];?>"></td>
																	<td class="tab-md-100"><input type="number" class="form-control text-right" readonly="" name="line_value[]" id="line_value<?php echo $counter; ?>" value="<?php echo $lineItems["line_value"];?>"></td> 
																	<td class="tab-md-100"><input type="number" class="form-control text-right" readonly="" name="total_tax[]" id="total_tax<?php echo $counter; ?>" value="<?php echo $lineItems["total_tax"];?>"></td>
																	<td class="tab-md-100"><input type="number" class="form-control text-right" readonly="" name="total[]" id="total<?php echo $counter; ?>" value="<?php echo $lineItems["total"];?>"></td> */ ?>
																	<!-- <td class="tab-md-150"><input type="date" class="form-control" name="delivery_date[]" id="delivery_date<?php echo $counter; ?>" value="<?php echo $lineItems["delivery_date"];?>"></td>
																	 --><!-- <td class="tab-md-150"><input type="text" class="form-control" readonly name="organization_name[]" id="organization_name<?php echo $counter; ?>" value="<?php echo $lineItems["organization_name"];?>"></td> -->
																	<input type="hidden" class="form-control" readonly name="inventory_code[]" id="inventory_code<?php echo $counter; ?>" value="<?php echo $lineItems["inventory_code"];?>">
																	<input type="hidden" class="form-control" readonly name="locator_no[]" id="locator_no<?php echo $counter; ?>" value="<?php echo $lineItems["locator_no"];?>">
																	<input type="hidden" class="form-control" readonly name="lot_number[]" id="lot_number<?php echo $counter; ?>" value="<?php echo $lineItems["lot_number"];?>">
																	<input type="hidden" class="form-control" readonly name="serial_number[]" id="serial_number<?php echo $counter; ?>" value="<?php echo $lineItems["serial_number"];?>">
																	<input type="hidden" class="form-control" name="unit_price[]" id="unit_price<?php echo $counter; ?>" value="<?php echo $lineItems["unit_price"];?>">
																	<input type="hidden" class="form-control" name="tax[]" id="tax<?php echo $counter; ?>" value="<?php echo $lineItems["tax"];?>">
																	<input type="hidden" class="form-control" name="discount_type[]" id="discount_type<?php echo $counter; ?>" value="<?php echo $lineItems["discount_type"];?>">
																	<input type="hidden" class="form-control" readonly="" name="discount[]" id="discount<?php echo $counter; ?>" value="<?php echo $lineItems["discount"];?>">
																	<input type="hidden" class="form-control" readonly="" name="discount_reason[]" id="discount_reason<?php echo $counter; ?>" value="<?php echo $lineItems["discount_reason"];?>">
																	<input type="hidden" class="form-control" readonly="" name="price[]" id="price<?php echo $counter; ?>" value="<?php echo $lineItems["effective_price"];?>">
																	<input type="hidden" class="form-control" readonly="" name="line_value[]" id="line_value<?php echo $counter; ?>" value="<?php echo $lineItems["line_value"];?>">
																	<input type="hidden" class="form-control" readonly="" name="total_tax[]" id="total_tax<?php echo $counter; ?>" value="<?php echo $lineItems["total_tax"];?>">
																	<input type="hidden" class="form-control" readonly="" name="total[]" id="total<?php echo $counter; ?>" value="<?php echo $lineItems["total"];?>">
																	<input type="hidden" class="form-control" name="delivery_date[]" id="delivery_date<?php echo $counter; ?>" value="<?php echo $lineItems["delivery_date"];?>">
																	<input type="hidden" class="form-control" name="customer_item[]" id="customer_item<?php echo $counter; ?>" value="<?php echo $lineItems["customer_item"];?>">
																	<input type="hidden" class="form-control" readonly name="line_status[]" id="line_status<?php echo $counter; ?>" value="<?php echo $lineItems["line_status"];?>">
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
							</fieldset>
						</div>
					</form>
				   
					<script>
						$('#branch_id').change(function (e) { 
							
							$(".remove_tr").remove();
						});
						function addLines(val)
						{
							if(val == 1)
							{
								addSOLines();
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

						function addSOLines()
						{
							$('.line-items-error').text('');
							//$('.line-items-error').hide();
							var flag = 0;
							var organization_id	= $('#organization_id').val()
							var branch_id		= $('#branch_id').val()
							
							$.ajax({
								url: "<?php echo base_url('sales_order/getSOLineDatas'); ?>",
								type: "GET",
								data:{
									'<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>',
									organization_id:organization_id,
									branch_id :branch_id
								},
								datatype: "JSON",
								success: function(d)
								{
									data = JSON.parse(d);
									$("table.line_items").find('input[name^="transaction_id[]"]').each(function () 
									{
										var row = $(this).closest("tr");
										var transaction_id = +row.find('input[name^="transaction_id[]"]').val();
										var quantity = +row.find('input[name^="quantity[]"]').val();
										var uom = +row.find('input[name^="uom_id[]"]').val();
										var unit_price = +row.find('input[name^="unit_price[]"]').val();
 
										if(transaction_id == 0 || quantity == "" || unit_price == "" ) //|| uom == ""
										{
											flag = 1;
										}
									});
									
									if(flag == 0)
									{
										//Items
										var select_items = "";
										select_items += '<select class="form-control searchDropdown" onchange="selectItemDetails(this.value,'+counter+');" name="transaction_ids[]" id="transaction_ids'+counter+'">';
										select_items += '<option value="">- Select -</option>';
										for(a=0;a<data['items'].length;a++)
										{
											if(data['items'][a].lot_number != null)
											{
												var lot_number = "- "+ data['items'][a].lot_number;
											}else {
												var lot_number = "";
											}

											if(data['items'][a].serial_number != null)
											{
												var serial_number = "- "+ data['items'][a].serial_number;
											}else {
												var serial_number = "";
											}

											var item_value = data['items'][a].transaction_id+"@"+data['items'][a].item_id;

											select_items += '<option value="' +item_value+ '">' + data['items'][a].item_name+lot_number+serial_number+'</option>';
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
										var select_organization = "";
										select_organization += '<select class="form-control searchDropdown" onchange="selectSubInventory(this.value,'+counter+')" name="organization_id[]" id="organization_id'+counter+'">';
										select_organization += '<option value="">- Select -</option>';
										for(a=0;a<data['organization'].length;a++)
										{
											var selected='';

											/* if(organization_id == data['organization'][a].organization_id)
											{
												var selected='selected="selected"';
											} */
											select_organization += '<option value="' + data['organization'][a].organization_id + '" '+selected+'>' + data['organization'][a].organization_name+'</option>';
										}
										select_organization += '</select>';
										//selectSubInventory(organization_id,counter);

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

										var select_lot_numbers = "";
										select_lot_numbers += '<select class="form-control searchDropdown" name="lot_number_id[]" id="lot_number_id'+counter+'">';
										select_lot_numbers += '<option value="">- Select -</option>';
										select_lot_numbers += '</select>';

										var select_serial_numbers = "";
										select_serial_numbers += '<select class="form-control searchDropdown" name="serial_number_id[]" id="serial_number_id'+counter+'">';
										select_serial_numbers += '<option value="">- Select -</option>';
										select_serial_numbers += '</select>';
										
										var newRow = $("<tr class='remove_tr tabRow"+counter+"'>");
										var cols = "";
										//cols += "<td class='tab-md-30'><input type='hidden' name='id' name='id' value="+i+"><input type='hidden' id='product_id"+counter+"' name='product_id' value=''><input type='hidden' name='counter' name='counter' value="+counter+"></td>";
										cols += "<td class='tab-md-30 text-center'><a class='deleteRow'><i class='fa fa-times-circle-o' style='color:#fb1b1b61;font-size:16px;'></i></a>"+
										"<input type='hidden' name='sales_line_id[]' value='0' id='sales_line_id"+counter+"'>"+
										"<input type='hidden' name='transaction_id[]' value='0' id='transaction_id"+counter+"'>"+
										"<input type='hidden' name='text_product_id[]' value='0' id='text_product_id_"+counter+"'>"+
										// "<input type='hidden' name='organization_id[]' value='0' id='organization_id"+counter+"'>"+
										"<input type='hidden' name='sub_inventory_id[]' value='0' id='sub_inventory_id"+counter+"'>"+
										"<input type='hidden' name='locator_id[]' value='0' id='locator_id"+counter+"'>"+
										"<input type='hidden' name='trans_qty[]' value='0' id='trans_qty"+counter+"'>"+
										"<input type='hidden' name='counter' value='"+counter+"'></td>";
										
										
										cols += "<td class='tab-md-85'>" 
												+"<input type='number' class='form-control mobile_vali' required name='line_num[]' id='line_num"+ counter +"' value='"+counter
												+"'>"
											+"</td>";

										cols += '<td class="tab-md-200">'+select_items+'</td>';
										
										cols += "<td class='tab-md-150'>" 
												+"<textarea class='form-control' rows='1' readonly name='description[]' id='description"+ counter +"' value=''></textarea>"
											+"</td>";

										cols += "<td class='tab-md-150'>" 
												+"<input type='text' class='form-control' readonly name='category_name[]' id='category_name"+ counter +"' value=''>"
											+"</td>";

										/* cols += "<td class='tab-md-150'>" 
												+"<textarea class='form-control' rows='1' name='customer_item[]' id='customer_item"+ counter +"' value=''></textarea>"
											+"</td>"; */

										/* cols += "<td class='tab-md-100'>" 
												+"<input type='text' class='form-control' readonly name='line_status[]' id='line_status"+ counter +"' value='Draft'>"
											+"</td>"; */

										cols += "<td class='tab-md-100'>" 
												+"<input type='number' class='form-control' required min='1' name='quantity[]' id='quantity"+ counter +"' value='' placeholder='Quantity'>"
											+"</td>";

										cols += '<td class="tab-md-100">'+select_uom+'</td>';
										
										/* cols += "<td class='tab-md-100'>" 
												+"<input type='number' class='form-control' required name='unit_price[]' id='unit_price"+ counter +"' value=''>"
											+"</td>";

										cols += '<td class="tab-md-100">'+select_tax+'</td>';
										cols += '<td class="tab-md-100">'+select_discount_type+'</td>';
										
										cols += "<td class='tab-md-100'>" 
												+"<input type='number' class='form-control' readonly name='discount[]' id='discount"+ counter +"' value=''>"
											+"</td>";

										cols += "<td class='tab-md-150'>" 
												+"<textarea class='form-control' rows='1' readonly name='discount_reason[]' id='discount_reason"+ counter +"' value=''></textarea>"
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
											+"</td>";  */

										/* cols += "<td class='tab-md-150'>" 
												+"<input type='date' class='form-control' required name='delivery_date[]' id='delivery_date"+ counter +"' value=''>"
											+"</td>";
 										*/
										// cols += "<td class='tab-md-150'>" 
										// 		+"<input type='text' class='form-control' readonly name='organization_name[]' id='organization_name"+ counter +"' value=''>"
										// 	+"</td>";
										
										"<input type='hidden' class='form-control' readonly name='inventory_code[]' id='inventory_code"+ counter +"' value=''>";
										"<input type='hidden' class='form-control' readonly name='locator_no[]' id='locator_no"+ counter +"' value=''>";
										"<input type='hidden' class='form-control' readonly name='lot_number[]' id='lot_number"+ counter +"' value=''>";
										"<input type='hidden' class='form-control' readonly name='serial_number[]' id='serial_number"+ counter +"' value=''>";
										"<input type='hidden' class='form-control' required name='unit_price[]' id='unit_price"+ counter +"' value=''>"
										"<input type='hidden' class='form-control' readonly name='discount[]' id='discount"+ counter +"' value=''>"
										"<input type='hidden'  class='form-control' rows='1' readonly name='discount_reason[]' id='discount_reason"+ counter +"' value=''>"
										"<input type='hidden' class='form-control text-right' readonly name='price[]' id='price"+ counter +"' value=''>"
										"<input type='hidden' class='form-control text-right' readonly name='line_value[]' id='line_value"+ counter +"' value=''>"
										"<input type='hidden' class='form-control text-right' readonly name='total_tax[]' id='total_tax"+ counter +"' value=''>"
										"<input type='hidden' class='form-control text-right' readonly name='total[]' id='total"+ counter +"' value=''>"
										"<input type='hidden' class='form-control text-right' readonly name='delivery_date[]' id='delivery_date"+ counter +"' value=''>"
										"<input type='hidden' class='form-control text-right' readonly name='customer_item[]' id='customer_item"+ counter +"' value=''>"
										"<input type='hidden' class='form-control text-right' readonly name='line_status[]' id='line_status"+ counter +"' value='Draft'>"
										 /* cols += "<td class='tab-md-100'>" 
												+"<input type='text' class='form-control' readonly name='inventory_code[]' id='inventory_code"+ counter +"' value=''>"
											+"</td>";
										
										cols += "<td class='tab-md-100'>" 
												+"<input type='text' class='form-control' readonly name='locator_no[]' id='locator_no"+ counter +"' value=''>"
											+"</td>";
										
										cols += "<td class='tab-md-100'>" 
												+"<input type='text' class='form-control' readonly name='lot_number[]' id='lot_number"+ counter +"' value=''>"
											+"</td>";
										
										cols += "<td class='tab-md-100'>" 
												+"<input type='text' class='form-control' readonly name='serial_number[]' id='serial_number"+ counter +"' value=''>"
											+"</td>"; */ 
										cols += "</tr>";
										
										newRow.html(cols);
										$("table.line_items").append(newRow);

										$(document).ready(function()
										{ 
											$(".searchDropdown").select2();
										});
										
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

						function selectSubInventory(organization_id,counter)
						{
							if(organization_id)
							{
								$.ajax({
									type: "POST",
									url:"<?php echo base_url().'sales_order/selectSubInventory';?>",
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
									url:"<?php echo base_url().'sales_order/selectSubInventoryLocators';?>",
									data: { inventory_id: inventory_id }
								}).done(function( result ) 
								{   $("#locator_id"+counter).html(result);
								});
							}
						}

						function deleteRow(sales_line_id,counter)
						{
							var confirmBox = confirm("Are you sure you want to delete the line?");

							if(confirmBox)
							{
								if(sales_line_id)
								{
									$.ajax({
										type: "POST",
										url:"<?php echo base_url().'sales_order/deleteLineItems';?>",
										data: { sales_line_id: sales_line_id }
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

						function selectItemDetails(item_value,counter)
						{
							if(item_value)
							{
								var splitValue = item_value.split("@");

								var transaction_id = splitValue[0];
								var item_id = splitValue[1];

								var flag = 0;
					
								$("table.line_items").find('input[name^="transaction_id[]"]').each(function () 
								{
									var row = $(this).closest("tr");
									var transactionId = +row.find('input[name^="transaction_id[]"]').val();

									if(transactionId == transaction_id) 
									{
										flag = 1;
									}
								});

								if(flag == 0)
								{
									$('.line-items-error').text('');
									selectItemUom(transaction_id,counter,item_id);
									selectTransactionDetails(transaction_id,item_id,counter);

									if(item_id)
									{
										$.ajax({
											type: "POST",
											url:"<?php echo base_url().'sales_order/getLineItems';?>",
											data: { transaction_id: transaction_id,item_id: item_id }
										}).done(function( d ) 
										{   
											data = JSON.parse(d);
											
											var trans_qty = data[0].trans_qty;
											
											$("#quantity"+counter).prop('max',trans_qty);
											$("#trans_qty"+counter).val(data[0].trans_qty);

											/* $("#description"+counter).val(data[0].item_description);
											$("#category_name"+counter).val(data[0].category_name);
											$("#transaction_id"+counter ).val(data[0].transaction_id);
											$("#text_product_id_"+counter ).val(data[0].item_id);

											$("#organization_id"+counter ).val(data[0].organization_id);
											$("#sub_inventory_id"+counter ).val(data[0].sub_inventory_id);
											$("#locator_id"+counter ).val(data[0].locator_id);

											$("#organization_name"+counter ).val(data[0].organization_name);
											$("#inventory_code"+counter ).val(data[0].inventory_code);
											$("#locator_no"+counter ).val(data[0].locator_no);
											$("#lot_number"+counter ).val(data[0].lot_number);
											$("#serial_number"+counter ).val(data[0].serial_number);
											*/
										});
									}
								}
								else
								{
									$("#quantity"+counter).prop('max',0);
									$("#trans_qty"+counter).val('0');

									$("#description"+counter).val('');
									$("#category_name"+counter).val('');
									$("#transaction_id"+counter ).val('0');
									$("#text_product_id_"+counter ).val('0');

									$("#organization_id"+counter ).val('0');
									$("#sub_inventory_id"+counter ).val('0');
									$("#locator_id"+counter ).val('0');

									$("#organization_name"+counter ).val('');
									$("#inventory_code"+counter ).val('');
									$("#locator_no"+counter ).val('');
									$("#lot_number"+counter ).val('');
									$("#serial_number"+counter ).val('');

									$('.line-items-error').text('Item already exist.').animate({opacity: '0.0'}, 2000).animate({}, 1000).animate({opacity: '1.0'}, 2000);
								}
							}
							else
							{
								$("#quantity"+counter).prop('max',0);
									$("#trans_qty"+counter).val('0');

									$("#description"+counter).val('');
									$("#category_name"+counter).val('');
									$("#transaction_id"+counter ).val('0');
									$("#text_product_id_"+counter ).val('0');

									$("#organization_id"+counter ).val('0');
									$("#sub_inventory_id"+counter ).val('0');
									$("#locator_id"+counter ).val('0');

									$("#organization_name"+counter ).val('');
									$("#inventory_code"+counter ).val('');
									$("#locator_no"+counter ).val('');
									$("#lot_number"+counter ).val('');
									$("#serial_number"+counter ).val('');
							}
						}

						/* function ajaxSelectItems(counter)
						{
							$.ajax({
								url: "<?php echo base_url('sales_order/getSOLineDatas'); ?>",
								type: "GET",
								data:{
									'<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
								},
								datatype: "JSON",
								success: function(d)
								{
									data = JSON.parse(d);
									
									var select_items = "";
									select_items += '<option value="">- Select -</option>';
									for(a=0;a<data['items'].length;a++)
									{
										if(data['items'][a].lot_number != null)
										{
											var lot_number = "- "+ data['items'][a].lot_number;
										}else {
											var lot_number = "";
										}

										if(data['items'][a].serial_number != null)
										{
											var serial_number = "- "+ data['items'][a].serial_number;
										}else {
											var serial_number = "";
										}

										var item_value = data['items'][a].transaction_id+"@"+data['items'][a].item_id;

										select_items += '<option value="' +item_value+ '">' + data['items'][a].item_name+lot_number+serial_number+'</option>';
									}

									$("#transaction_ids"+counter).html(select_items);
								},
								error: function(xhr, status, error) {
									$('#err_product').text('Error!').animate({opacity: '0.0'}, 2000).animate({opacity: '0.0'}, 1000).animate({opacity: '1.0'}, 2000);
								}
							});
						} */

						function selectTransactionDetails(transaction_id,item_id,counter)
						{
							if(item_id)
							{
								$.ajax({
									type: "POST",
									url:"<?php echo base_url().'sales_order/getTransactionDetails';?>",
									data: { transaction_id: transaction_id,item_id: item_id }
								}).done(function( d ) 
								{   
									data = JSON.parse(d);
									
									//var trans_qty = data[0].trans_qty;
									//$("#quantity"+counter).prop('max',trans_qty);
									$("#description"+counter).val(data[0].item_description);
									$("#category_name"+counter).val(data[0].category_name);
									$("#transaction_id"+counter ).val(data[0].transaction_id);
									$("#text_product_id_"+counter ).val(data[0].item_id);

									$("#organization_id"+counter ).val(data[0].organization_id);
									$("#sub_inventory_id"+counter ).val(data[0].sub_inventory_id);
									$("#locator_id"+counter ).val(data[0].locator_id);

									$("#organization_name"+counter ).val(data[0].organization_name);
									$("#inventory_code"+counter ).val(data[0].inventory_code);
									$("#locator_no"+counter ).val(data[0].locator_no);
									$("#lot_number"+counter ).val(data[0].lot_number);
									$("#serial_number"+counter ).val(data[0].serial_number);
									//$("#trans_qty"+counter ).val(data[0].trans_qty);
								});
							}
						}

						function selectItemUom(transaction_id,counter,item_id)
						{
							if(transaction_id)
							{
								$.ajax({
									type: "POST",
									url:"<?php echo base_url().'sales_order/ajaxSelectItemUom';?>",
									data: { id: transaction_id,item_id: item_id }
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

						$("table.line_items").on("input keyup change", 'input[name^="unit_price[]"], input[name^="quantity[]"], input[name^="discount[]"]', function (event) 
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
							var unit_price = parseFloat($("#unit_price"+counter).val()).toFixed(2);
							
							var discount_type = $("#discount_type"+counter).val();
							var discount = $("#discount"+counter).val();

							if(discount_type =='Amount') 
							{
								var discount_price = parseFloat(unit_price - discount).toFixed(2);
								$("#price"+counter).val(discount_price);
							}
							else if(discount_type =='Percentage') 
							{
								var discount_price = discount / 100 * unit_price;
								var price = parseFloat(unit_price - discount_price).toFixed(2);
								$("#price"+counter).val(price);
							}
							else
							{
								$("#price"+counter).val(unit_price);
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
								if($salesOrderMenu['create_edit_only'] == 1 || $this->user_id == 1)
								{
									?>
									<a href="<?php echo base_url(); ?>sales_order/manageSalesOrder/add" class="btn btn-info btn-sm">
										Create Material Issue
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
									<label class="col-form-label col-md-5 text-right">Order No</label>
									<div class="form-group col-md-7">
										<input type="search" class="form-control" autocomplete="off" name="order_number" value="<?php echo !empty($_GET['order_number']) ? $_GET['order_number'] :""; ?>" placeholder="Order No">
									</div>
								</div>
							</div>
							<?php /* <div class="col-md-4">
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
							</div> */ ?>

							<div class="col-md-4">
								<div class="row">
									<label class="col-form-label col-md-5 branch_id"><span class="text-danger">*</span> Branch</label>
									<div class="form-group col-md-7">
										<select id="branch_id" name="branch_id" class="form-control searchDropdown" required>
											<option value="">- Select -</option>
											<?php
												$getBranches = $this->branches_model->getBranchAll();
												
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
											?>
										</select>
									</div>
								</div>
							</div>

							<input type="hidden" class="form-control" autocomplete="off" name="so_status" value="<?php echo !empty($_GET['so_status']) ? $_GET['so_status'] :""; ?>" placeholder="Order No">
									
							<?php /* <div class="col-md-4">
								<div class="row">
									<label class="col-form-label col-md-5 text-right">Status</label>
									<div class="form-group col-md-7">
										<?php 
											$soStatus = $this->common_model->lov('SOSTATUS'); 
										?>
										<select name="so_status" id="so_status" class="form-control searchDropdown">
											<option value="">- Select -</option>
											<?php 
												foreach($soStatus as $row)
												{
													$selected="";
													if(isset($_GET['so_status']) && $_GET['so_status'] == $row["list_value"] )
													{
														$selected="selected='selected'";
													}
													?>
													<option value="<?php echo $row["list_value"];?>" <?php echo $selected;?>><?php echo ucfirst($row["list_value"]);?></option>
													<?php 
												}
											?>
										</select>
									</div>
								</div>
							</div> */ ?>

							<div class="col-md-4">
								<div class="row">
									<label class="col-form-label col-md-5 text-right">Customer</label>
									<div class="form-group col-md-7">
										<?php
											$query1 = "select customer_name,customer_id from cus_customers
											where active_flag='Y' order by customer_name asc
											";
											$getCustomer = $this->db->query($query1)->result_array();
										?>
										
										<select id="customer_id" name="customer_id" --onchange="selectSupplierSite(this.value)" class="form-control searchDropdown">
											<option value="">- Select -</option>
											<?php 
												foreach($getCustomer as $customer)
												{
													$selected="";
													if(isset($_GET["customer_id"]) && $_GET["customer_id"] == $customer['customer_id'] )
													{
														$selected="selected='selected'";
													}
													?>
													<option value="<?php echo $customer['customer_id'];?>" <?php echo $selected;?>><?php echo ucfirst($customer['customer_name']);?></option>
													<?php 
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
									<label class="col-form-label col-md-5 text-right"><span class="text-danger">*</span> From Date</label>
									<div class="form-group col-md-7">
										<input type="text" name="from_date" id="from_date" class="form-control" readonly required value="<?php echo !empty($_GET['from_date']) ? $_GET['from_date'] : ""; ?>" placeholder="From Date">
									</div>
								</div>
							</div>

							<div class="col-md-4">
								<div class="row">
									<label class="col-form-label col-md-5 text-right"><span class="text-danger">*</span> To Date</label>
									<div class="form-group col-md-7">
										<input type="text" name="to_date" id="to_date" class="form-control" readonly required value="<?php echo !empty($_GET['to_date']) ? $_GET['to_date'] :""; ?>" placeholder="To Date">
									</div>
								</div>
							</div>	

							<div class="col-md-4 mt-1 text-right">
								<button type="submit" class="btn btn-info">Search <i class="fa fa-search" aria-hidden="true"></i></button>
								<a href="<?php echo base_url(); ?>sales_order/manageSalesOrder" title="Clear" class="btn btn-default">Clear</a>
							</div>
						</div>


						<!-- <div class="row my-3">
							<div class="col-md-12 text-right">
								<button type="submit" class="btn btn-info">Search <i class="fa fa-search" aria-hidden="true"></i></button>
								<a href="<?php echo base_url(); ?>sales_order/manageSalesOrder" title="Clear" class="btn btn-default">Clear</a>
							</div>

						</div> -->
					</form>
					<!-- Filters end here -->
					
					<?php 
						if(isset($_GET) &&  !empty($_GET))
						{
							?>
							<!-- Page Item Show start -->
							<div class="row">
								<div class="col-md-10">
									
								</div>

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
											<th class="text-center">Controls</th>
											<th class="tab-md-80 text-left">Organization</th>
											<th class="tab-md-80">Branch</th>
											<th class="text-left">Order #</th>
											<!-- <th class="text-left">Order Description</th> -->
											<th class="text-left">Status</th>
											<th class="text-left">Issue Date</th>
											<th class="text-left">Customer</th>
											<!-- <th class="text-right">Amount (<?php //echo CURRENCY_SYMBOL;?>)</th>
											<th class="text-left">Currency</th> -->
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
															if($salesOrderMenu['create_edit_only'] == 1 || $salesOrderMenu['read_only'] == 1 || $this->user_id == 1)
															{
																?>
																<div class="dropdown" style="width:90px;">
																	<button type="button" class="btn btn-outline-info gropdown-toggle btn-sm" data-toggle="dropdown" aria-expanded="false">
																		Action&nbsp;<i class="fa fa-chevron-down"></i>
																	</button>
																	<ul class="dropdown-menu dropdown-menu-right dropdown-menu-new">
																		
																		<?php
																			if($salesOrderMenu['create_edit_only'] == 1 || $this->user_id == 1)
																			{
																				?>
																				<li>
																					<a href="<?php echo base_url(); ?>sales_order/manageSalesOrder/edit/<?php echo $row['sales_header_id'];?>">
																						<i class="fa fa-pencil"></i> Edit
																					</a>
																				</li>
																				<?php 
																			} 
																		?>

																		<?php
																			if($salesOrderMenu['read_only'] == 1 || $this->user_id == 1)
																			{
																				?>
																				<li>
																					<a href="<?php echo base_url(); ?>sales_order/manageSalesOrder/view/<?php echo $row['sales_header_id'];?>">
																						<i class="fa fa-eye"></i> View
																					</a>
																				</li>

																				<li>
																					<a target="_blank" href="<?php echo base_url(); ?>sales_order/generatePDF/<?php echo $row['sales_header_id'];?>">
																						<i class="fa fa-file-pdf-o"></i> Download PDF
																					</a>
																				</li>
																				<?php 
																			} 
																		?>			
																	</ul>
																</div>
																<?php 
															}else{
																?>
																--
																<?php
															} 
														?>
													</td>

													<td><?php echo $row['organization_name'];?></td>
													<td><?php echo $row['branch_name'];?></td>
													<td><?php echo $row['order_number'];?></td>
													<?php /*  <td><?php echo $row['description'];?></td> */ ?>
													
													<td>
														<?php
															echo $row['so_status'];
														?> 
													</td>

													<td>
														<?php echo date(DATE_FORMAT,strtotime($row['order_date']));?>
													</td>

													<td><?php echo $row['customer_name'];?></td>										
																								
													<?php /* <td class="text-right">
														<?php 
															echo number_format($row['amount'],DECIMAL_VALUE,'.','');
														?>
													</td>
													<td>
														<?php echo $row['currency'];?>
													</td> */ ?>
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
