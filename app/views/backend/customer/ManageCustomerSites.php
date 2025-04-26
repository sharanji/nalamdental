
<?php
	$customerSitesMenu = accessMenu(customer_sites);
?>	
<div class="content"><!-- Content start-->
	<div class="card"><!-- Card start-->
		<?php
			if( isset($type) && $type == "add" || $type == "edit" || $type == "view")
			{
				if($type == "view"){
					$fieldSetDisabled = "disabled";
					$dropdownDisabled = "style='pointer-events: none;'";
					$searchDropdown = "";
				}else{
					$fieldSetDisabled = "";
					$dropdownDisabled = "";
					$searchDropdown = "searchDropdown";
				}
				$getCountry = $this->db->query("select country_id,country_name from geo_countries where active_flag ='Y' order by country_name asc")->result_array();
				?>
				<div class="card-body">
					<form action="" class="form-validate-jquery" enctype="multipart/form-data" method="post">
						<div class="row">
							<div class="col-md-6">									
								<h5 class="text-uppercase font-weight-bold"> 
									<?php 
										if($type == "add")
										{
											?>
											Create
											<?php
										}
										else if($type == "edit")
										{
											?>
											Edit
											<?php
										}
										else if($type == "view")
										{
											?>
											View
											<?php
										}
									?> Customer Site
								</h5>
							</div>
							
							<?php 
								if($type == "view")
								{
									?>
									<div class="col-md-6 text-right">
										<a class="btn btn-sm btn-primary edit-icon" href="<?php echo base_url(); ?>customer/ManageCustomerSites/edit/<?php echo $id;?>/<?php echo $status;?>" title="Edit">
											<i class="fa fa-edit"></i>
										</a>
									</div>
									<?php 
								} 
							?>
						</div>
							
						<fieldset class="mb-3" <?php echo $fieldSetDisabled;?>>
							<div class="form-group row">
								<?php 
									$getcustomer = $this->db->query("select customer_name, customer_number ,customer_id  from  cus_customers where active_flag='Y'")->result_array();
								?>
								<div class="col-md-6">													
									<div class="row">
										<div class="form-group col-md-4">
										  <label class="col-form-label"> Customer Name <span class="text-danger">*</span></label> 
										</div>
										
										<div class="form-group col-md-5">
											<select name="customer_id" id="customer_id" required class="form-control <?php echo $searchDropdown;?>">
												<option value="">- Select -</option>
													<?php 
														foreach($getcustomer as $key)
														{
															$selected="";
															if( isset($edit_data[0]['customer_id']) && $edit_data[0]['customer_id'] == $key["customer_id"])
															{
																$selected="selected";
															}
															?>
															<option value="<?php echo $key["customer_id"] ; ?>" <?php echo $selected;?>> <?php echo $key['customer_name'];?></option>
															<?php 
														} 
													?>
											</select>
										</div>
									</div>	
								</div>

								<div class="col-md-6">													
									<div class="row">
										<div class="form-group col-md-3">
										    <label class="col-form-label"> Site Name <span class="text-danger">*</span></label>										   									  
										</div>
										
										<div class="form-group col-md-4">
											<div class="">			
											  <input type="text" name="site_name" autocomplete="off" id="site_name" required class="form-control single_quotes" value="<?php echo isset($edit_data[0]['site_name']) ? $edit_data[0]['site_name'] :NULL;?>" placeholder="">																			
											</div>
										</div>	
									</div>								
								</div>
							</div>

							<div class="row mb-2">	
								<div class="col-md-6">													
									<div class="row">
										<div class="form-group col-md-4">
										    <label class="col-form-label"> Conact Person</label>										   									  
										</div>
										
										<div class="form-group col-md-5">
											<div class="">			
											  <input type="text" name="contact_person" autocomplete="off" id="contact_person" class="form-control single_quotes" value="<?php echo isset($edit_data[0]['contact_person']) ? $edit_data[0]['contact_person'] :NULL;?>" placeholder="">																			
											</div>
										</div>	
									</div>								
								</div>

								<div class="col-md-6">													
									<div class="row">
										<div class="form-group col-md-3">
										    <label class="col-form-label"> Mobile Number </label>										   									  
										</div>
										
										<div class="form-group col-md-4">
											<div class="">			
											  <input type="text" name="mobile_number" minlength="10" maxlength="10" autocomplete="off" id="mobile_number" class="form-control mobile_vali" value="<?php echo isset($edit_data[0]['mobile_number']) ? $edit_data[0]['mobile_number'] :NULL;?>" placeholder="">																			
											</div>
										</div>	
									</div>								
								</div>
							</div>

							<div class="row mb-2">	
								<div class="col-md-6">													
									<div class="row">
										<div class="form-group col-md-4">
										    <label class="col-form-label"> Email</label>										   									  
										</div>
										
										<div class="form-group col-md-5">
											<div class="">			
											  <input type="email" name="email_address" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,63}$" autocomplete="off" id="email_address" class="form-control single_quotes" value="<?php echo isset($edit_data[0]['email_address']) ? $edit_data[0]['email_address'] :NULL;?>" placeholder="">
											  <span class='small employee_email_exist_error' style="color:#a19f9f;"></span> 																			
											</div>
										</div>	
									</div>								
								</div>

								
							</div>

							<div class="row">
								<div class="col-md-12">
									<div class="row">
										<div class="col-md-12">
										      
											<div class="row">
												<legend class="col-md-2"><h5>Address <span class="text-danger">*</span></h5></legend>
												<?php 
													/* $site_type = isset($edit_data[0]['site_type']) ? $edit_data[0]['site_type'] :NULL;

													if($site_type == "BILL_TO")
													{
														$chk_bill_to = "checked='checked'";
													}else{
														$chk_bill_to = "";
													}

													if($site_type == "SHIP_TO")
													{
														$chk_ship_to = "checked='checked'";
													}else{
														$chk_ship_to = "";
													} */
												?>

												
												<?php
													if($type =="add")
													{
														?>
														<div class="col-md-2 mt-2">
															<label class="chk-label">Bill To </label>			
															<input type="checkbox" id="site_type" name="site_type[]" required value="BILL_TO" >
														</div> 
														
														<div class="col-md-2 mt-2">
															<label class="chk-label">Ship To </label>	
															<input type="checkbox" id="site_type" name="site_type[]" required value="SHIP_TO"> 
														</div>


														<?php 
													} 
													else if($type =="edit" || $type =="view")
													{
														$site_type = isset($edit_data['site_type']) ? $edit_data[0]['site_type'] : NULL;							
														$customer_id = isset($edit_data[0]['customer_id']) ? $edit_data[0]['customer_id'] : NULL;
														$site_name = isset($edit_data[0]['site_name']) ? $edit_data[0]['site_name'] : NULL;

														$siteQry = "select site_type,customer_site_id,active_flag from cus_customer_sites 
															where 
																customer_site_id='".$id."' 
																and customer_id='".$status."' 
															";

														#and upper(replace(site_name,' ',''))='".strtoupper(trim(RemoveWhiteSpace($site_name)))."' 
														$getSite = $this->db->query($siteQry)->result_array();
											

														$chk_bill_to ="";
														$chk_ship_to ="";
														$customer_bill_site_id = '';
														$customer_ship_site_id = '';

														$bill_active_flag = '';
														$ship_active_flag = '';
														$bill_readonly = "";
														$ship_readonly = "";
														foreach($getSite as $key => $value)
														{
															$customer_bill_site_id = $value["customer_site_id"];
															$customer_ship_site_id = $value["customer_site_id"];
															if (in_array("BILL_TO", $value))
															{
																$chk_bill_to .="checked='checked'"; 
																$bill_active_flag .= $value["active_flag"];
																$bill_readonly .= "readonly";
															}

															if (in_array("SHIP_TO", $value))
															{
																$chk_ship_to .="checked='checked'"; 
																$ship_active_flag .= $value["active_flag"];
																$ship_readonly .= "readonly";
															}
														}
														
														?>
														<div class="col-md-2 mt-2">
															<label class="chk-label">Bill To</label>
															<input type="checkbox" id="site_type" name="site_type[]" <?php echo $chk_bill_to;?> <?php echo $bill_readonly;?> required value="BILL_TO" >
															<br>
															<?php 
																if($bill_active_flag == 'N')
																{
																	?>
																	<span class="text-danger">Inactive</span>
																	<?php
																}
																else if($bill_active_flag == 'Y')
																{
																	?>
																	<span class="text-success">Active</span>
																	<?php
																}  
															?>		
														</div>

														<style>
															input[type="checkbox"][readonly] {
																pointer-events: none;
																accent-color:#ddd;
															}

															/* input[type="checkbox"][readonly]:nth-child(2) {
																opacity: 0.5;
																pointer-events: none;
															}
															input[type="checkbox"][readonly]:nth-child(3) {
																position: relative;
																pointer-events: none;
															}
															input[type="checkbox"][readonly]:nth-child(3):before {
																content: "";
																position: absolute;
																left: 0%;
																top: 0%;
																width: 100%;
																height: 100%;
																background-color: rgba(255, 255, 255, 0.5);
															} */
														</style>
														
														<div class="col-md-2 mt-2">
															<label class="chk-label">Ship To</label>
															<input type="checkbox" id="site_type" name="site_type[]" <?php echo $chk_ship_to;?> <?php echo $ship_readonly;?> required value="SHIP_TO"> 
															<br>
															<?php 
																if($ship_active_flag == 'N')
																{
																	?>
																	<span class="text-danger">Inactive</span>
																	<?php
																}
																else if($ship_active_flag == 'Y')
																{
																	?>
																	<span class="text-success">Active</span>
																	<?php
																}  
															?>		
														</div>

														<script>
															/* $('input#site_type').on('change', function() {
																$('input#site_type').not(this).prop('checked', false);  
															}); */
														</script>
														<?php 
													} 
												?>
												
											</div>
										
											<fieldset class="mt-2">
												<!-- Address -->
												<div class="form-group row">
													<label class="col-form-label col-md-2">Country <span class="text-danger">*</span></label>
													<div class="col-md-2">
														<select name="country_id" id="country_id" onchange="selectState(this.value);" required class="form-control <?php echo $searchDropdown;?>">
															<option value="">- Select -</option>
															<?php 
																$getCountry = $this->db->query("select country_id,country_name from geo_countries where active_flag='Y' order by country_name asc")->result_array();
										
																foreach($getCountry as $row)
																{
																	$selected="";
																	if(isset($edit_data[0]['country_id']) && $edit_data[0]['country_id']== $row['country_id'])
																	{
																		$selected="selected";
																	}
																	?>
																	<option value="<?php echo $row['country_id']; ?>" <?php echo $selected; ?>><?php echo ucfirst($row['country_name']);?></option>
																	<?php 
																} 
															?>
														</select>
													</div>
												</div>
												
												<div class="form-group row">
													<label class="col-form-label col-md-2">State <span class="text-danger">*</span></label>
													<div class="col-md-2">
														<select name="state_id" id="state_id" required onchange="selectCity(this.value);" class="form-control <?php echo $searchDropdown;?>">
															<option value="">- Select -</option>
															<?php 
																if($edit_data[0]['country_id'] !=0 && $edit_data[0]['country_id'] !="")
																{
																	$state = $this->db->query("select geo_states.state_id,geo_states.state_name from geo_states
																			where active_flag='Y' and geo_states.country_id='".$edit_data[0]['country_id']."'")->result_array();
																			
																	foreach($state as $row)
																	{
																		$selected='';
																		if($edit_data[0]['state_id'] == $row['state_id'])
																		{
																			$selected='selected="selected"';
																		}
																		?>
																		<option value="<?php echo $row['state_id'];?>" <?php echo $selected;?>><?php echo ucfirst($row['state_name']);?></option>
																		<?php 
																	} 
																} 
															?>
														</select>
													</div>
												</div>

												<div class="form-group row">
													<label class="col-form-label col-md-2">City <span class="text-danger">*</span></label>
													<div class="col-md-2">
														<select name="city_id" id="city_id" required class="form-control <?php echo $searchDropdown;?>">
															<option value="">- Select -</option>
															<?php 
																if($edit_data[0]['state_id'] !=0 && $edit_data[0]['state_id'] !="")
																{
																	$city= $this->db->query("select geo_cities.city_id,geo_cities.city_name from geo_cities
																			where active_flag='Y' and geo_cities.state_id='".$edit_data[0]['state_id']."'")->result_array();
																			
																	foreach($city as $row)
																	{
																		$selected='';
																		if($edit_data[0]['city_id'] == $row['city_id'])
																		{
																			$selected='selected="selected"';
																		}
																		?>
																		<option value="<?php echo $row['city_id'];?>" <?php echo $selected;?>><?php echo ucfirst($row['city_name']);?></option>
																		<?php 
																	} 
																} 
															?>
														</select>
													</div>
												</div>

												<div class="form-group row">
													<label class="col-form-label col-md-2">Address 1 <span class="text-danger">*</span></label>
													<div class="form-group col-md-3">
														<textarea name="address1" id="address1" rows="1" required autocomplete="off" class="form-control single_quotes" placeholder=""><?php echo isset($edit_data[0]['address1']) ? $edit_data[0]['address1'] : NULL;?></textarea>
													</div>
												</div>

												<div class="form-group row">
													<label class="col-form-label col-md-2">Address 2</label>
													<div class="form-group col-md-3">
														<textarea name="address2" id="address2" rows="1" autocomplete="off" class="form-control single_quotes" placeholder=""><?php echo isset($edit_data[0]['address2']) ? $edit_data[0]['address2'] : NULL;?></textarea>
													</div>
												</div>

												<div class="form-group row">
													<label class="col-form-label col-md-2">Address 3</label>
													<div class="form-group col-md-3">
														<textarea name="address3" id="address3" rows="1" autocomplete="off" class="form-control single_quotes" placeholder=""><?php echo isset($edit_data[0]['address3']) ? $edit_data[0]['address3'] : NULL;?></textarea>
													</div>
												</div>

												<div class="form-group row">
													<label class="col-form-label col-md-2">Postal Code <span class="text-danger">*</span></label>
													<div class="form-group col-md-2">
													<input type="text" name="postal_code" autocomplete="off" required <?php echo $this->validation;?> id="postal_code" class="form-control mobile_vali" minlength="6" maxlength='6' value="<?php echo isset($edit_data[0]['postal_code']) ? $edit_data[0]['postal_code'] :"";?>" placeholder="">
													</div>
												</div>
												
											</fieldset>
										</div>	
									</div>
								</div>
							</div>

							<script type="text/javascript">  
								function selectState(val,type)
								{
									if(val !='')
									{
										$( "#address1").val("");
										$( "#address2").val("");
										$( "#address3").val("");
										$( "#postal_code").val("");

										$.ajax({
											type: "POST",
											url:"<?php echo base_url().'admin/ajaxselectState';?>",
											data: { id: val }
										}).done(function( msg ) 
										{   
											if(msg == "no_date_found")
											{
												$( "#state_id").html('<option value="">- Select -</option>');
												$( "#city_id").html('<option value="">- Select -</option>');
											}
											else
											{
												$( "#state_id").html(msg);
												$( "#city_id").html('<option value="">- Select -</option>');
											}
										});
									}
									else 
									{ 
										$( "#state_id").html('<option value="">- Select -</option>');
										$( "#city_id").html('<option value="">- Select -</option>');
									}
								}
								
								function selectCity(val,type)
								{
									if(val !='')
									{
										$.ajax({
											type: "POST",
											url:"<?php echo base_url().'admin/ajaxSelectCity';?>",
											data: { id: val }
										}).done(function( msg ) 
										{   
											if(msg == "no_date_found")
											{

											}
											else
											{
												$( "#city_id").html(msg);
											}	
										});
									}
									else 
									{ 
										$( "#city_id").html('<option value="">- Select -</option>');
									}
								}
							</script>

							<?php /* 
							<script>
								$(document).ready(function()
								{  
									$('#user_code').keyup(function()
									{  
										var query = $(this).val();  
										if(query != '')  
										{  
											$.ajax({  
												url:"<?php echo base_url();?>customer/userAjaxSearch",  
												method:"POST",  
												data:{query:query},  
												success:function(data)  
												{  
													$('#userList').fadeIn();  
													$('#userList').html(data);  
												}  
											});  
										} 	
									});
									
									$(document).on('click', '.list-unstyled-new li', function()
									{  
										var value = $(this).text();
										
										if(value === "Sorry! No data found.")
										{
											$('#user_code').val("");  
											$('#userList').fadeOut();
										}
										else
										{
											$('#user_code').val(value);  
											$('#userList').fadeOut();  
										}
									});
								});

								function getuserId(user_id)
								{	
									$('#new_user_id').val(user_id);
									var id = user_id;
									
									if(id >= 0 )
									{
										$.ajax({
											url: "<?php echo base_url('users/usersList') ?>/"+id,
											type: "GET",
											data:{
												'<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
											},
											datatype: "JSON",
											success: function(d)
											{
												data = JSON.parse(d);
												var userData = data['empData'];

												if(userData[0].phone_number !=""){
													var phoneNumber = userData[0].phone_number;
												}else if(userData[0].mobile_number !=""){
													var phoneNumber = userData[0].mobile_number;
												}

												
												$("#first_name").val(userData[0]["first_name"]);
												$("#last_name").val(userData[0]["last_name"]);
												$("#email").val(userData[0]["email"]);
												$("#phone_number").val(userData[0]["phone_number"]);
												$("#user_name").val(userData[0]["random_user_id"]);
												$("#gst_number").val(userData[0]["gst_number"]);
												$("#contact_person").val(userData[0]["first_name"]);
											
											},
											error: function(xhr, status, error) 
											{
												$('#err_product').text('Enter Product Code / Name').animate({opacity: '0.0'}, 2000).animate({opacity: '0.0'}, 1000).animate({opacity: '1.0'}, 2000);
											}
										});
									}
								}	
							</script>
							*/ ?>
						
							
						</fieldset>
						
						<div class="d-flexad" style="text-align:right;">
							<a href="<?php echo base_url(); ?>customer/ManageCustomerSites" class="btn btn-default">Close</a>
							<?php 
								if($type == "view")
								{
								}
								else
								{
									?>
									<button type="submit" class="btn btn-primary ml-1">Save</button>
									<?php 
								} 
							?>		
						</div>
					</form>
				</div>
				<?php
			}
			else
			{ 
				?>
				<div class="card-body">
					<div class="row mb-2">
						<div class="col-md-6"><h3><b><?php echo $page_title;?></b></h3></div>
						<div class="col-md-6 float-right text-right">
							<?php
								if((isset($customerSitesMenu['create_edit_only']) && $customerSitesMenu['create_edit_only'] == 1) || $this->user_id == 1)
								{
									?>
									<a href="<?php echo base_url(); ?>customer/ManageCustomerSites/add" class="btn btn-info btn-sm">
										Create Customer Site
									</a>
									<?php 
								} 
							?>
						</div>
					</div>		

					<!-- Filters start here -->
					<form action="" class="form-validate-jquery" method="get">
						<div class="row">
							<div class="col-md-4">
								<div class="row">
									<label class="col-form-label col-md-4">Customer Name <!-- <span class="text-danger">*</span> --></label>
									<div class="form-group col-md-8">
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

							<div class="col-md-4">
								<div class="row">
									<label class="col-form-label col-md-4">Site Usage</label>
									<div class="form-group col-md-8">
										<select name="site_type" id="site_type" class="form-control searchDropdown">
											<!-- <option value="">- Select -</option> -->
											<?php 
												foreach($this->site_type as $key => $value)
												{
													$selected="";
													if(isset($_GET['site_type']) && $_GET['site_type'] == $key )
													{
														$selected="selected='selected'";
													}
													?>
													<option value="<?php echo $key;?>" <?php echo $selected;?>><?php echo ucfirst($value);?></option>
													<?php 
												} 
											?>
										</select>
									</div>
								</div>
							</div>

							<div class="col-md-3">
								<div class="row">
									<label class="col-form-label col-md-3">Status</label>
									<div class="form-group col-md-9">
										<?php 
											$activeStatus = $this->common_model->lov('ACTIVESTATUS') 
										?>
										
										<select name="active_flag" id="active_flag" class="form-control searchDropdown">
											<!-- <option value="">- Select -</option> -->
											<?php 
												foreach($activeStatus as $row)
												{
													$selected="";
													if(isset($_GET['active_flag']) && $_GET['active_flag'] == $row["list_code"] )
													{
														$selected="selected='selected'";
													}
													?>
													<option value="<?php echo $row["list_code"];?>" <?php echo $selected;?>><?php echo ucfirst($row["list_value"]);?></option>
													<?php 
												} 
											?>
										</select>
									</div>
								</div>
							</div>
						</div>

						<div class="row">
							<div class="col-md-3 offset-9 text-right float-right">
									
								<button type="submit" class="btn btn-info">Search <i class="fa fa-search" aria-hidden="true"></i></button>
								&nbsp;<a href="<?php echo base_url(); ?>customer/ManageCustomerSites" title="Clear" class="btn btn-default">Clear</a>
									
								
							</div>
						</div>
						
					</form>
					<!-- Filters end here -->

					<?php 
						if( isset($_GET) && !empty($_GET))
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
								<table id="myTable" class="table table-bordered table-hover --table-striped dataTable">
									<thead>
										<tr>
											<th class="text-center">Controls</th>
											<th onclick="sortTable(1)">Customer Name</th>
											<th onclick="sortTable(2)">Site Name</th>
											<th onclick="sortTable(2)">Site Usage</th>
											<th class="text-center">Status</th>
										</tr>
									</thead>
									<tbody>
										<?php 	
											$i=0;
											$firstItem = $first_item;
											foreach($resultData as $row)
											{
												?>
												<tr>
													<td class="text-center">
														<div class="dropdown" --style="width: 80px;">
															<button type="button" class="btn btn-outline-info gropdown-toggle" data-toggle="dropdown" aria-expanded="false">
																Action <i class="fa fa-angle-down"></i>
															</button>
															<ul class="dropdown-menu dropdown-menu-right dropdown-menu-new">
																<?php
																	if($customerSitesMenu['create_edit_only'] == 1 || $customerSitesMenu['read_only'] == 1 || $this->user_id == 1)
																	{ 
																		?>
																		<?php
																			if($customerSitesMenu['create_edit_only'] == 1 || $this->user_id == 1)
																			{
																				?>
																				<li>
																					<a title="Edit" href="<?php echo base_url(); ?>customer/ManageCustomerSites/edit/<?php echo $row['customer_site_id'];?>/<?php echo $row['customer_id'];?>">
																						<i class="fa fa-pencil"></i> Edit
																					</a>
																				</li>
																				<?php 
																			} 
																		?>

																		<?php
																			if($customerSitesMenu['read_only'] == 1 || $this->user_id == 1)
																			{
																				?>
																				<li>
																					<a title="Edit" href="<?php echo base_url(); ?>customer/ManageCustomerSites/view/<?php echo $row['customer_site_id'];?>/<?php echo $row['customer_id'];?>">
																						<i class="fa fa-eye"></i> View
																					</a>
																				</li>
																				<?php 
																			} 
																		?>

																		<?php
																			if($customerSitesMenu['create_edit_only'] == 1 || $this->user_id == 1)
																			{
																				?>
																				<li>
																					<?php 
																						if($row['active_flag'] == $this->active_flag)
																						{
																							?>
																							<a href="<?php echo base_url(); ?>customer/ManageCustomerSites/status/<?php echo $row['customer_site_account_id'];?>/<?php echo $row['customer_site_id'];?>/<?php echo $row['customer_id'];?>/N" title="Block">
																								<i class="fa fa-ban"></i> Inactive
																							</a>
																							<?php 
																						} 
																						else
																						{  ?>
																							<a href="<?php echo base_url(); ?>customer/ManageCustomerSites/status/<?php echo $row['customer_site_account_id'];?>/<?php echo $row['customer_site_id'];?>/<?php echo $row['customer_id'];?>/Y" title="Unblock">
																								<i class="fa fa-ban"></i> Active
																							</a>
																							<?php 
																						} 
																					?>
																				</li>
																				<?php 
																			} 
																		?>
																		
																		<?php 
																	} 
																?>
																
															</ul>
														</div>
													</td>
													<td><?php echo $row['customer_name'];?></td>
													
													<td><?php echo $row['site_name'];?></td>
													<td>
														<?php 
															if($row['site_type'] == "SHIP_TO")
															{
																echo "Ship To";
															}
															else if($row['site_type'] == "BILL_TO")
															{
																echo "Bill To";
															}
															else
															{

															}
														?>
													</td>
													<td class="text-center">
														<?php 
															if($row['active_flag'] == $this->active_flag)
															{
																?>
																<span class="btn btn-sm btn-outline-success" title="Active">Active</span>
																<?php 
															} 
															else
															{ 
																?>
																<span class="btn btn-sm btn-outline-warning" title="Inactive">Inactive</span>
																<?php 
															} 
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
								if( count($resultData) > 0 )
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
				</div>
				<?php 
			} 
		?>
	</div><!-- Card end-->
</div><!-- Content end-->