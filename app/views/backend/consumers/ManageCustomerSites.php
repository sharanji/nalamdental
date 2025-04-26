<?php 
	$customer_site = accessMenu(customer_site);
?>
<div class="content"><!-- Content start-->
	<div class="card"><!-- Card start-->
		<?php
			if( isset($type) && $type == "add" || $type == "edit")
			{
				$getCountry = $this->db->query("select country_id,country_name from country where country_status=1 order by country_name asc")->result_array();
				#$getSupplier = $this->db->query("select user_id, first_name from users where user_status = 1 and register_type = 1 order by first_name asc")->result_array();
				?>
				<div class="card-body">
					<form action="" class="form-validate-jquery" enctype="multipart/form-data" method="post">
						<fieldset class="mb-3">
							<legend class="text-uppercase font-size-sm font-weight-bold">
								<?php echo $type;?> Customer Site
							</legend>
							
							<div class="row">
								<?php 
									$empCode = isset($edit_data[0]['customer_id']) ? $edit_data[0]['customer_id'] :"";
									$get_emp_code = $this->db->query("select first_name,email,random_user_id,phone_number from users where user_id='".$empCode."' and register_type = 1")->result_array();
									$employee_code =isset($get_emp_code[0]['random_user_id']) ? $get_emp_code[0]['random_user_id'].' - ' :"";
									$employee_name =isset($get_emp_code[0]['first_name']) ? $get_emp_code[0]['first_name'] :"";
								?>

								<div class="form-group col-md-3">
									<label class="col-form-label">Customer Name <span class="text-danger">*</span></label>
									<input type="text" name="user_code" id="user_code" autocomplete="off" <?php echo $this->validation;?> value="<?php echo $employee_code."".$employee_name;?>" required class="form-control">
									<div id="userList"></div>
									<span class='small text-warning employee_user_id_exist_error template_code_exist_error' --style="color:red;"></span> 
									<input type="hidden" name="new_user_id" id="new_user_id" value="<?php echo $empCode;?>" class="form-control">
								</div>

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

								<!-- <div class="form-group col-md-3">
									<label class="col-form-label"> Customer Name <span class="text-danger">*</span></label>
									<select name="customer_id" required class="form-control searchDropdown">
										<option value="">- Select Customer Name-</option>
										<?php
											/* foreach($getSupplier as $row)
											{
												$selected='';
												if (isset($edit_data[0]['customer_id']) && $edit_data[0]['customer_id'] == $row['user_id'])
												{
													$selected='selected="selected" ';
												}
												?>
												<option value="<?php echo $row['user_id'];?>" <?php echo $selected; ?>><?php echo $row['first_name']?></option>
												<?php
												
											} */
										?>
									</select>
								</div> -->
								
								<div class="form-group col-md-3">
									<label class="col-form-label"> Site Name <span class="text-danger">*</span></label>
									<input type="text" name="site_name" autocomplete="off" <?php echo $this->validation;?> id="company_name" required class="form-control" value="<?php echo isset($edit_data[0]['site_name']) ? $edit_data[0]['site_name'] :"";?>" placeholder="">
								</div>
								<div class="form-group col-md-3">
									<label class="col-form-label"> Mobile Number <span class="text-danger">*</span></label>
									<input type="text" name="phone_number" autocomplete="off" required <?php echo $this->validation;?> id="phone_number" class="form-control mobile_vali" minlength="10" maxlength='12'" value="<?php echo isset($edit_data[0]['phone_number']) ? $edit_data[0]['phone_number'] :"";?>" placeholder="">
								</div>
								
							</div>
							
							<div class="row">	
								<div class="form-group col-md-3">
									<label class="col-form-label">Email</label>
									<input type="text" name="email" autocomplete="off" id="email" class="form-control" value="<?php echo isset($edit_data[0]['email']) ? $edit_data[0]['email'] :"";?>" placeholder="">
								</div>
								<div class="form-group col-md-3">
									<label class="col-form-label"> GST Number</label>
									<input type="text" name="gst_number" id="gst_number" autocomplete="off" <?php echo $this->validation;?> id="address1" class="form-control" value="<?php echo isset($edit_data[0]['gst_number']) ? $edit_data[0]['gst_number'] :"";?>" placeholder="">
								</div>
								
								<div class="form-group col-md-3">
									<label class="col-form-label"> Contact Person  </label>
									<input type="text" name="contact_person" id="contact_person" autocomplete="off" <?php echo $this->validation;?> id="contact_person" class="form-control" value="<?php echo isset($edit_data[0]['contact_person']) ? $edit_data[0]['contact_person'] :"";?>" placeholder="">
								</div>
							</div>
							
							<?php /*
							<div class="row">	
								<div class="form-group col-md-3">
									<label class="col-form-label">Country</label>
									<select name="country_id" id="country_id" onchange="selectState(this.value);" class="form-control searchDropdown">
										<option value="">- Select Country -</option>
										<?php 
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
																
								<div class="form-group col-md-3">
									<label class="col-form-label">State</label>
									<select name="state_id" id="state_id" onchange="selectCity(this.value);" class="form-control searchDropdown">
										<option value="">- Select State -</option>
										<?php 
											if($edit_data[0]['state_id'] !=0 && $edit_data[0]['state_id'] !="")
											{
												$state = $this->db->query("select state.state_id,state.state_name from state
														where state_status=1 and state.country_id='".$edit_data[0]['country_id']."'")->result_array();
														
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
								
								<div class="form-group col-md-3">
									<label class="col-form-label">City</label>
									<select name="city_id" id="city_id" class="form-control searchDropdown">
										<option value="">- Select City -</option>
										<?php 
											if($edit_data[0]['city_id'] !=0 && $edit_data[0]['city_id'] !="")
											{
												$city= $this->db->query("select city.city_id,city.city_name from city
														where city_status=1 and city.state_id='".$edit_data[0]['state_id']."'")->result_array();
														
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
							<div class="row">
								<div class="form-group col-md-3">
									<label class="col-form-label"> Address</label>
									<input type="text" name="address" autocomplete="off" <?php echo $this->validation;?> id="address" class="form-control" value="<?php echo isset($edit_data[0]['address']) ? $edit_data[0]['address'] :"";?>" placeholder="">
								</div>
							</div> */ ?>


							<div class="row">
								<div class="col-md-12">
									<div class="row">
										<div class="col-md-6">
											<!-- Billing address start -->
											<?php 
												$checked ="";
												if( isset($edit_data[0]['chk_billing_address']) && $edit_data[0]['chk_billing_address'] == 1 )
												{
													$checked ="checked='checked'";
												} 
											?>
											<div --class="new-design-2">
												<p>
													<b>Billing Address</b>
													<!-- &nbsp; &nbsp; <input type="checkbox" name="chk_billing_address" value='1' id="chk_billing_address" <?php echo $checked;?>>
													&nbsp;
													<span style="color:#3e3e46;font-size: 11px;">
														Same as Customer Address
													</span> -->
												</p>
											</div>
											
											<fieldset class="mb-3 new-design-1--">
												<div class="row">
													<div class="form-group col-md-6">
														<label class="col-form-label">Country <span class="text-danger">*</span></label>
														<select name="billing_country_id" required id="billing_country_id" onchange="selectState(this.value,'billing');" class="form-control --searchDropdown">
															<option value="">- Select Country -</option>
															<?php 
																foreach($getCountry as $row)
																{
																	$selected="";
																	if(isset($edit_data[0]['billing_country_id']) && $edit_data[0]['billing_country_id']== $row['country_id'])
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
													
													<div class="form-group col-md-6">
														<label class="col-form-label">State <span class="text-danger">*</span></label>
														<select name="billing_state_id" required id="billing_state_id" onchange="selectCity(this.value,'billing');" class="form-control searchDropdown">
															<option value="">- Select State -</option>
															<?php 
																if($edit_data[0]['billing_state_id'] !=0 && $edit_data[0]['billing_state_id'] !="")
																{
																	$state = $this->db->query("select state.state_id,state.state_name from state
																			where state_status=1 and state.state_id='".$edit_data[0]['billing_state_id']."'")->result_array();
																			
																	foreach($state as $row)
																	{
																		$selected='';
																		if($edit_data[0]['billing_state_id'] == $row['state_id'])
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
												
												<div class="row">
													<div class="form-group col-md-6">
														<label class="col-form-label">City <span class="text-danger">*</span></label>
														<select name="billing_city_id" required id="billing_city_id" class="form-control searchDropdown">
															<option value="">- Select City -</option>
															<?php 
																if($edit_data[0]['billing_city_id'] !=0 && $edit_data[0]['billing_city_id'] !="")
																{
																	$city= $this->db->query("select city.city_id,city.city_name from city
																			where city_status=1 and city.city_id='".$edit_data[0]['billing_city_id']."'")->result_array();
																			
																	foreach($city as $row)
																	{
																		$selected='';
																		if($edit_data[0]['billing_city_id'] == $row['city_id'])
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
													
													<div class="form-group col-md-6">
														<label class="col-form-label">Zip Code <span class="text-danger">*</span></label>
														<input type="text" name="billing_postal_code" id="billing_postal_code" required minlength="6" maxlength="6" class="form-control mobile_vali" value="<?php echo isset($edit_data[0]['billing_zip_code']) ? $edit_data[0]['billing_zip_code'] :"";?>" placeholder="">
													</div>
												</div>
												
												<div class="row">
													<div class="form-group col-md-12">
														<label class="col-form-label">Address <span class="text-danger">*</span></label>
														<textarea name="billing_address" id="billing_address" required autocomplete="off" class="form-control"><?php echo isset($edit_data[0]['billing_address']) ? $edit_data[0]['billing_address'] :"";?></textarea>
													</div>
												</div>
											</fieldset>
											<!-- Billing address end -->
										</div>

										<div class="col-md-6">
											<!-- Shipping/Billing Details start -->
											<div class="new-design-2--">
												<p>
													<b>Shipping Address</b>
													<?php 
														$checked_shipping_address ="";
														if( isset($edit_data[0]['chk_shipping_address']) && $edit_data[0]['chk_shipping_address'] == 1 )
														{
															$checked_shipping_address ="checked='checked'";
														} 
													?>
													&nbsp; &nbsp; 
													<input type="checkbox" name="chk_shipping_address" value='1' id="chk_shipping_address" <?php echo $checked_shipping_address;?>>
													&nbsp;
													<span style="color:#3e3e46;font-size: 11px;">
														Copy Billing Address
													</span>
												</p>
											</div>
											<fieldset class="mb-3 fieldset-class  new-design-1--">
												<div class="row">
													<div class="form-group col-md-6">
														<label class="col-form-label">Country <span class="text-danger">*</span></label>
														<select name="shipping_country_id" required id="shipping_country_id" onchange="selectState(this.value,'shipping');" class="form-control --searchDropdown">
															<option value="">- Select Country -</option>
															<?php 
																foreach($getCountry as $row)
																{
																	$selected="";
																	if(isset($edit_data[0]['shipping_country_id']) && $edit_data[0]['shipping_country_id']== $row['country_id'])
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
													
													<div class="form-group col-md-6">
														<label class="col-form-label" style="float:left;width:100%;">State <span class="text-danger">*</span></label>
														<select name="shipping_state_id" required id="shipping_state_id" onchange="selectCity(this.value,'shipping');" class="form-control">
															<option value="">- Select State -</option>
															<?php 
																if($edit_data[0]['shipping_state_id'] !=0 && $edit_data[0]['shipping_state_id'] !="")
																{
																	$state = $this->db->query("select state.state_id,state.state_name from state
																			where state_status=1 and state.state_id='".$edit_data[0]['shipping_state_id']."'")->result_array();
																			
																	foreach($state as $row)
																	{
																		$selected='';
																		if($edit_data[0]['shipping_state_id'] == $row['state_id'])
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
												
												<div class="row">
													<div class="form-group col-md-6">
														<label class="col-form-label">City <span class="text-danger">*</span></label>
														<select name="shipping_city_id" required id="shipping_city_id" class="form-control">
															<option value="">- Select City -</option>
															<?php 
																if($edit_data[0]['shipping_city_id'] !=0 && $edit_data[0]['shipping_city_id'] !="")
																{
																	$city= $this->db->query("select city.city_id,city.city_name from city
																			where city_status=1 and city.city_id='".$edit_data[0]['shipping_city_id']."'")->result_array();
																			
																	foreach($city as $row)
																	{
																		$selected='';
																		if($edit_data[0]['shipping_city_id'] == $row['city_id'])
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
													
													<div class="form-group col-md-6">
														<label class="col-form-label">Zip Code <span class="text-danger">*</span></label>
														<input type="text" name="shipping_postal_code" id="shipping_postal_code" minlength="6" maxlength="6" required class="form-control mobile_vali" value="<?php echo isset($edit_data[0]['shipping_zip_code']) ? $edit_data[0]['shipping_zip_code'] :"";?>" placeholder="">
													</div>
												</div>
												
												<div class="row">
													<div class="form-group col-md-12">
														<label class="col-form-label">Address <span class="text-danger">*</span></label>
														<textarea name="shipping_address" id="shipping_address" required autocomplete="off" class="form-control"><?php echo isset($edit_data[0]['shipping_address']) ? $edit_data[0]['shipping_address'] :"";?></textarea>
													</div>
												</div>
											</fieldset>
											<!-- Shipping/Billing Details end -->
										</div>
									</div>
								</div>
							</div>
							
							<script>
								$(document).ready(function()
								{
									<!-- Billing Address start -->
									$('input[name="chk_billing_address"]').click(function()
									{
										if( $(this).prop("checked") == true ) //checked
										{
											$('#billing_address').val( $('#address').val() );
											$('#billing_postal_code').val( $('#postal_code').val() );
											
											var company_country = $('select#country_id option:selected').sort().clone();
											//$('select#billing_country_id').append( company_country );
											
											var country_id = $('#country_id').val();4
											
											var billing_country_id = $('#billing_country_id').val();
											
											var state_id = $('#state_id').val();
											var company_state_id = $('select#state_id option:selected').sort().clone();
											$('select#billing_state_id').append( company_state_id );
											
											var city_id = $('#city_id').val();
											var company_city_id = $('select#city_id option:selected').sort().clone();
											$('select#billing_city_id').append( company_city_id );
											
											if(country_id == billing_country_id);
											{
												$('select#billing_country_id option[value='+country_id+']').attr('selected','selected');
											}
											
											if(state_id !='');
											{
												$('select#billing_state_id option[value='+state_id+']').attr('selected','selected');
											}
											
											if(city_id !='');
											{
												$('select#billing_city_id option[value='+city_id+']').attr('selected','selected');
											}
										}
										else if( $(this).prop("checked") == false ) //Unchecked
										{
											$('#chk_shipping_address').prop('checked', false); // Unchecks it

											//Billing unselect
											var country_id = $('#country_id').val();
											var billing_country_id = $('#billing_country_id').val();

											var shipping_country_id = $('#shipping_country_id').val();
											
											//$("select#billing_country_id option[value='"+billing_country_id+"']:last").remove();

											if(country_id == billing_country_id);
											{
												$('select#billing_country_id option[value='+country_id+']').removeAttr('selected','selected');
											}

											if(country_id == billing_country_id);
											{
												$('select#shipping_country_id option[value='+country_id+']').removeAttr('selected','selected');
											}
											
											$( "#billing_state_id").html('<option value="">- First Select Country -</option>');
											$( "#billing_city_id").html('<option value="">- First Select State -</option>');
											
											$('#billing_address').val('');
											$('#billing_postal_code').val('');
											
											
											//Billing unselect ans shipping also undelect
											//var shipping_country_id = $('#shipping_country_id').val();
											
											//$("select#shipping_country_id option[value='"+shipping_country_id+"']:last").remove();
											
											$( "#shipping_state_id").html('<option value="">- First Select Country -</option>');
											$( "#shipping_city_id").html('<option value="">- First Select State -</option>');
											
											$('#shipping_address').val('');
											$('#shipping_postal_code').val('');
										}
									});
									<!-- Billing Address end -->
									
									<!-- Shipping Address start -->
									$('input[name="chk_shipping_address"]').click(function()
									{
										if( $(this).prop("checked") == true ) //checked
										{
											$('#shipping_address').val( $('#billing_address').val() );
											$('#shipping_postal_code').val( $('#billing_postal_code').val() );
											
											var company_country1 = $('select#billing_country_id option:selected').sort().clone();
											//$('select#shipping_country_id').append( company_country1 );
											
											var billing_country_id = $('#billing_country_id').val();
											var shipping_country_id = $('#shipping_country_id').val();
											
											var billing_state_id = $('#billing_state_id').val();
											var company_state_id = $('select#billing_state_id option:selected').sort().clone();
											$('select#shipping_state_id').append( company_state_id );
											
											var billing_city_id = $('#billing_city_id').val();
											var company_city_id = $('select#billing_city_id option:selected').sort().clone();
											$('select#shipping_city_id').append( company_city_id );
											
											if(billing_country_id == shipping_country_id);
											{
												$('select#shipping_country_id option[value='+billing_country_id+']').attr('selected','selected');
											}
											
											if(billing_state_id !='');
											{
												$('select#shipping_state_id option[value='+billing_state_id+']').attr('selected','selected');
											}
											
											if(billing_city_id !='');
											{
												$('select#shipping_city_id option[value='+billing_city_id+']').attr('selected','selected');
											}
										}
										else if( $(this).prop("checked") == false ) //Unchecked
										{
											var shipping_country_id = $('#shipping_country_id').val();
											var billing_country_id = $('#billing_country_id').val();
											//$("select#shipping_country_id option[value='"+shipping_country_id+"']:last").remove();

											if(billing_country_id == shipping_country_id);
											{
												$('select#shipping_country_id option[value='+billing_country_id+']').removeAttr('selected','selected');
											}
											
											$( "#shipping_state_id").html('<option value="">- First Select Country -</option>');
											$( "#shipping_city_id").html('<option value="">- First Select State -</option>');
											
											$('#shipping_address').val('');
											$('#shipping_postal_code').val('');
										}
									});
									<!-- Shipping Address end -->
								});
							</script>

							<script type="text/javascript">  
								function selectState(val,type)
								{
									if(val !='')
									{
										$.ajax({
											type: "POST",
											url:"<?php echo base_url().'admin/ajaxselectState';?>",
											data: { id: val }
										}).done(function( msg ) 
										{   
											if(type == 'company')
											{
												$( "#state_id").html(msg);
											
												if(msg=='<option value="">No states under this country!</option>'){
													$( "#state_id").html('<option value="">No states under this country!</option>');
												}/* else{
													$( "#state_id").html('<option value="">First Select Country</option>');
												} */
												
												if(msg=='<option value="">No states under this country!</option>'){
													$( "#city_id").html('<option value="">No cities under this state!</option>');
												}else{
													$( "#city_id").html('<option value="">First Select State</option>');
												}
											}
											else if(type == 'billing')
											{
												$( "#billing_state_id").html(msg);
											
												if(msg=='<option value="">No states under this country!</option>'){
													$( "#billing_state_id").html('<option value="">No states under this country!</option>');
												}/* else{
													$( "#state_id").html('<option value="">First Select Country</option>');
												} */
												
												if(msg=='<option value="">No states under this country!</option>'){
													$( "#billing_city_id").html('<option value="">No cities under this state!</option>');
												}else{
													$( "#billing_city_id").html('<option value="">First Select State</option>');
												}
											}
											else if(type == 'shipping')
											{
												$( "#shipping_state_id").html(msg);
											
												if(msg=='<option value="">No states under this country!</option>'){
													$( "#shipping_state_id").html('<option value="">No states under this country!</option>');
												}/* else{
													$( "#state_id").html('<option value="">First Select Country</option>');
												} */
												
												if(msg=='<option value="">No states under this country!</option>'){
													$( "#shipping_city_id").html('<option value="">No cities under this state!</option>');
												}else{
													$( "#shipping_city_id").html('<option value="">First Select State</option>');
												}
											}
										});
									}
									else 
									{ 
										alert("No states under this country!");
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
											if(type == 'company')
											{
												$( "#city_id").html(msg);
											}
											else if(type == 'billing')
											{
												$( "#billing_city_id").html(msg);
											}
											else if(type == 'shipping')
											{
												$( "#shipping_city_id").html(msg);
											}
										});
									}
									else 
									{ 
										alert("No cities under this state!");
									}
								}
							</script>

						</fieldset>
						
						<div class="d-flexad" style="text-align:right;">
							<a href="<?php echo base_url(); ?>customer/ManageCustomerSites" class="btn btn-default">Cancel</a>
							<?php 
								if($type == "edit")
								{
									?>
									<button type="submit" class="btn btn-primary ml-1">Update</button>
									<?php 
								}
								else
								{
									?>
									<button type="submit" class="btn btn-primary ml-1">Submit</button>
									<?php 
								}
							?>
						</div>
					</form>

					<?php /*
					<script type="text/javascript"> 
						function selectState(val)
						{
							if(val !='')
							{
								$.ajax({
									type: "POST",
									url:"<?php echo base_url().'admin_location/ajaxselectState';?>",
									data: { id: val }
								}).done(function( msg ) 
								{   
									$( "#state_id").html(msg);
									
									if(msg=='<option value="">No states under this country!</option>'){
										$( "#city_id").html('<option value="">No cities under this state!</option>');
									}else{
										$( "#city_id").html('<option value="">First Select State</option>');
									}
								});
							}
							else 
							{ 
								alert("No states under this country!");
							}
						}
						
						function selectCity(val,type)
						{
							if(val !='')
							{
								$.ajax({
									type: "POST",
									url:"<?php echo base_url().'admin_location/ajaxSelectStateCity';?>",
									data: { id: val }
								}).done(function( msg ) 
								{   
									$( "#city_id").html(msg);
								});
							}
							else 
							{ 
								alert("No cities under this state!");
							}
						}
					</script> */ ?>
				</div>
				<?php
			}
			else
			{ 
				?>
				<div class="card-body">
					<div class="row mb-2">
						<div class="col-md-6"><?php echo $page_title;?></div>
						<div class="col-md-6 float-right text-right">
							<?php
								if((isset($customer_site['create_edit_only']) && $customer_site['create_edit_only'] == 1) || $this->user_id == 1)
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

					<form action="" method="get">
						<section class="trans-section-back-1">
							<div class="row">
								<div class="col-md-8">
									<div class="row mt-1">
										<div class="col-md-5">	
											<input type="search" class="form-control" name="keywords" value="<?php echo !empty($_GET['keywords']) ? $_GET['keywords'] :""; ?>" placeholder="Search..." autocomplete="off">
											<p style="font-size:12px;color:#888888;"><span class="text-muted">Note : Customer Name, Site Name and Mobile Number</span>
										</div>	
										<div class="col-md-3">
											<button type="submit" class="btn btn-info">Search <i class="fa fa-search" aria-hidden="true"></i></button>
										</div>
										<a class="button" href="#">
										</a>
									</div>
								</div>
								<div class="col-md-4 text-right">
									<?php 
										$redirect_url = substr($_SERVER['REQUEST_URI'],'1');
									?>
									<input type="hidden" id="redirect_url" value="<?php echo $redirect_url; ?>"/>
															
									<div class="filter_page">
										<label>
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
						</section>
					</form>
						
					<form action="" method="post">
						<div class="new-scroller">
							<table id="myTable" class="table table-bordered table-hover --table-striped dataTable">
								<thead>
									<tr>
										<th class="text-center">Controls</th>
										<th onclick="sortTable(1)">Customer Name</th>
										<th onclick="sortTable(2)" style="width:245px!important;">Site Name</th>
										<!--<th onclick="sortTable(3)">Email</th>-->
										<th onclick="sortTable(4)" class="text-center">Mobile Number</th>
										<th onclick="sortTable(5)" class="text-center">Status</th>
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
														<button type="button" class="btn btn-outline-info gropdown-toggle btn-sm" data-toggle="dropdown" aria-expanded="false">
															Action <i class="fa fa-angle-down"></i>
														</button>
														<ul class="dropdown-menu dropdown-menu-right">
															<?php
																if((isset($customer_site['create_edit_only']) && $customer_site['create_edit_only'] == 1) || $this->user_id == 1)
																{
																	?>
																	<li>
																		<a title="Edit" href="<?php echo base_url(); ?>customer/ManageCustomerSites/edit/<?php echo $row['customer_site_id'];?>">
																			<i class="fa fa-pencil"></i> Edit
																		</a>
																	</li>
																	<li>
																		<?php 
																			if($row['site_status'] == 1)
																			{
																				?>
																				<a href="<?php echo base_url(); ?>customer/ManageCustomerSites/status/<?php echo $row['customer_site_id'];?>/0" title="Block">
																					<i class="fa fa-ban"></i> Inactive
																				</a>
																				<?php 
																			} 
																			else
																			{  ?>
																				<a href="<?php echo base_url(); ?>customer/ManageCustomerSites/status/<?php echo $row['customer_site_id'];?>/1" title="Unblock">
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
																if((isset($customer_site['read_only']) && $customer_site['read_only'] == 1) || $this->user_id == 1)
																{
																	?>
																	<li>
																		<a title="View" href="javascript:void(0);" data-toggle="modal" data-target="#ViewCustomerSiteDetail<?php echo $row['customer_site_id'];?>">	
																			<i class="fa fa-eye"></i> View
																		</a>
																	</li>
																	<?php 
																} 
															?>
														</ul>
													</div>

													<!-- Site Detail start here -->
													<div class="modal fade" id="ViewCustomerSiteDetail<?php echo $row['customer_site_id'];?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
														<div class="modal-dialog modal-dialog-centered" role="document">
															<div class="modal-content">
																<div class="modal-header">
																	<h5 class="modal-title" id="exampleModalLabel">Customer Site Details :</h5>
																	<button type="button" class="close" data-dismiss="modal" aria-label="Close">
																		<span aria-hidden="true">&times;</span>
																	</button>
																</div>
																
																<div class="modal-body text-left">
																	<div class="row">
																		<div class="col-md-3">Customer Name</div>
																		<div class="col-md-1"> :</div>
																		<div class="col-md-6"> <?php echo ucfirst($row['first_name']);?></div>
																	</div>

																	<div class="row mt-2">
																		<div class="col-md-3">Site Name</div>
																		<div class="col-md-1"> :</div>
																		<div class="col-md-6"> <?php echo $row['site_name'];?></div>
																	</div>

																	<div class="row mt-2">
																		<div class="col-md-3">Mobile Number</div>
																		<div class="col-md-1"> :</div>
																		<div class="col-md-6"> <?php echo $row['phone_number'];?></div>
																	</div>

																	<div class="row mt-2">
																		<div class="col-md-3">Email</div>
																		<div class="col-md-1"> :</div>
																		<div class="col-md-6"> <?php echo $row['email'];?></div>
																	</div>

																	<?php if(!empty($row['gst_number'])){?>
																	<div class="row mt-2">
																		<div class="col-md-3">GST Number</div>
																		<div class="col-md-1"> :</div>
																		<div class="col-md-6"> <?php echo $row['gst_number'];?></div>
																	</div>
																	<?php } ?>
																	
																	<?php if(!empty($row['contact_person'])){?>
																	<div class="row mt-2">
																		<div class="col-md-3">Contact Person</div>
																		<div class="col-md-1"> :</div>
																		<div class="col-md-6"> <?php echo ucfirst($row['contact_person']);?></div>
																	</div>
																	<?php } ?>
														
																	<div class="row mt-2">
																		<div class="col-md-6">
																			<h5 class="sub_title">Billing Address</h5> 
																			<div class="row col-md-12 p-0">
																				<div class="col-md-5">Country</div>
																				<div class="col-md-1"> :</div>
																				<div class="col-md-6"> <?php echo ucfirst($row['billing_country_name']);?></div>
																			</div>

																			<div class="row col-md-12 p-0 mt-2">
																				<div class="col-md-5">State</div>
																				<div class="col-md-1"> :</div>
																				<div class="col-md-6"> <?php echo ucfirst($row['billing_state_name']);?></div>
																			</div>

																			<div class="row col-md-12 p-0 mt-2">
																				<div class="col-md-5">City</div>
																				<div class="col-md-1"> :</div>
																				<div class="col-md-6"> <?php echo ucfirst($row['billing_city_name']);?></div>
																			</div>

																			<div class="row col-md-12 p-0 mt-2">
																				<div class="col-md-5">Zip Code</div>
																				<div class="col-md-1"> :</div>
																				<div class="col-md-6"> <?php echo $row['billing_zip_code'];?></div>
																			</div>

																			<div class="row col-md-12 p-0 mt-2">
																				<div class="col-md-5">Address</div>
																				<div class="col-md-1"> :</div>
																				<div class="col-md-6"> <?php echo ucfirst($row['billing_address']);?></div>
																			</div>
																		</div>
																		<div class="col-md-6">
																			<h5 class="sub_title">Shipping Address</h5> 
																			<div class="row col-md-12 p-0">
																				<div class="col-md-5">Country</div>
																				<div class="col-md-1"> :</div>
																				<div class="col-md-6"> <?php echo ucfirst($row['shipping_country_name']);?></div>
																			</div>

																			<div class="row col-md-12 p-0 mt-2">
																				<div class="col-md-5">State</div>
																				<div class="col-md-1"> :</div>
																				<div class="col-md-6"> <?php echo ucfirst($row['shipping_state_name']);?></div>
																			</div>

																			<div class="row col-md-12 p-0 mt-2">
																				<div class="col-md-5">City</div>
																				<div class="col-md-1"> :</div>
																				<div class="col-md-6"> <?php echo ucfirst($row['shipping_city_name']);?></div>
																			</div>

																			<div class="row col-md-12 p-0 mt-2">
																				<div class="col-md-5">Zip Code</div>
																				<div class="col-md-1"> :</div>
																				<div class="col-md-6"> <?php echo ucfirst($row['shipping_zip_code']);?></div>
																			</div>

																			<div class="row col-md-12 p-0 mt-2">
																				<div class="col-md-5">Address</div>
																				<div class="col-md-1"> :</div>
																				<div class="col-md-6"> <?php echo ucfirst($row['shipping_address']);?></div>
																			</div>
																		</div>
																	</div>
																</div>	
															</div>
																
															<!--<div class="modal-footer">
																<button type="button" class="btn btn-light" data-dismiss="modal">Close </button>
																<button type="submit" class="btn btn-primary ml-3">Submit </button>
															</div>-->	
														</div>
													</div>
													<!-- Site Detail end here -->
												</td>

												<td><?php echo ucfirst($row['first_name']);?></td>
												<td><?php echo ucfirst($row['site_name']);?></td>
												<?php /* <td><?php echo $row['email'];?></td> */?>
												<td class="text-center"><?php echo $row['phone_number'];?></td>
												<td class="text-center">
													<?php 
														if($row['site_status'] == 1)
														{
															?>
															<span class="btn btn-outline-success btn-sm" title="Active"><i class="fa fa-check"></i> Active</span>
															<?php 
														} 
														else
														{ 
															?>
															<span class="btn btn-outline-warning btn-sm" title="Inactive"><i class="fa fa-close"></i> Inactive</span>
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
										<img src="<?php echo base_url();?>uploads/no-data.png">
									</div>
									<?php 
								} 
							?>
						</div>
					</form>
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
				</div>
				<?php 
			} 
		?>
	</div><!-- Card end-->
	<?php if(isset($type) && $type =='view'){?>
		<a href='<?php echo $_SERVER['HTTP_REFERER'];?>' class='btn btn-info' style="float:right;"><i class="icon-arrow-left16"></i> Back</a>
	<?php } ?>
</div><!-- Content end-->


<script>
	// select all checkbox
	$('#select_all').on('click', function(e) 
	{
		if($(this).is(':checked',true)) {
			$(".emp_checkbox").prop('checked', true);
		}
		else {
			$(".emp_checkbox").prop('checked',false);
		}
		// set all checked checkbox count
		//$("#select_count").html($("input.emp_checkbox:checked").length+" Selected");
	});
	
	// set particular checked checkbox count
	/* $(".emp_checkbox").on('click', function(e) 
	{
		$("#select_count").html($("input.emp_checkbox:checked").length+" Selected");
	}); */
</script>