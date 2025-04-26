<?php 
	$usersMenu = accessMenu(users);
?>

<div class="content"><!-- Content start-->
	<div class="card"><!-- Card start-->
		<div class="card-body">
			<?php
				if(isset($type) && $type == "add" || $type == "edit" || $type == "view")
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
					?>
					<div class="row mb-2">
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
								?>	
							User</h5>
					</div>
					
					<form action="" class="form-validate-jquery" enctype="multipart/form-data" method="post">
						<fieldset <?php echo $fieldSetDisabled;?>>
							<div class="row">
								<div class="col-md-6">
									<div class="row">
										<?php 
											$listTypeValuesQry = "select 
												sm_list_type_values.list_type_value_id,
												sm_list_type_values.list_code,
												sm_list_type_values.list_value	
													from sm_list_type_values

													left join sm_list_types on 
														sm_list_types.list_type_id = sm_list_type_values.list_type_id
															where 
																sm_list_type_values.active_flag = 'Y' and 
																sm_list_types.list_name = 'USERTYPE' "; 
											$getUserCategory = $this->db->query($listTypeValuesQry)->result_array();
										
											if($type == "edit" || $type == "view")
											{
												$displayEvent ="pointer-events: none;";
											}
											else if($type == "add")
											{
												$displayEvent ="display:block;";
											}
										?>
										<div class="form-group col-md-4">
											<label class="col-form-label">User Type <span class="text-danger">*</span></label>
										</div>
										<div class="form-group col-md-6">
											<div class="">
												<select name="reg_user_type" id="reg_user_type" onchange="selectUserType(this.value);" style="<?php echo $displayEvent;?>" style="pointer-events: none;" class="form-control -searchDropdown" required>
													<option value="">- Select -</option>
													<?php 
														foreach($getUserCategory as $list)
														{ 
															$selected="";
															if( isset($edit_data[0]['reg_user_type']) && $edit_data[0]['reg_user_type'] == $list['list_code'])
															{
																$selected="selected='selected'";
															}
															?>
															<option value="<?php echo $list['list_code'];?>" <?php echo $selected;?>><?php echo ucfirst($list['list_value']);?></option>
															<?php 
														} 
													?>
												</select>
											</div>
										</div>
									</div>
								</div>

								<?php 
									if ($type == "edit" || $type == "view")
									{
										if ($edit_data[0]["reg_user_type"] == "CUS")
										{
											?>
											<div class="col-md-6 customer_div">
												<div class="row">
													<?php 
														$customerQry = "select 
																customer.customer_id,
																customer.customer_name	
																
																from cus_customers as customer

																		where 
																		customer.active_flag = 'Y' and 
																		customer.deleted_flag = 'N'"; 
														$getCustomer = $this->db->query($customerQry)->result_array();
														
													?>
													<div class="form-group col-md-4">
														<label class="col-form-label">Customer</label>
													</div>
													<div class="form-group col-md-6">
														<div class="">
															<select name="customer_id" id="customer_id" style="pointer-events: none;" class="form-control -searchDropdown">
																<option value="">- Select -</option>
																<?php 
																	foreach($getCustomer as $row)
																	{ 
																		$selected="";
																		if( isset($edit_data[0]['customer_id']) && $edit_data[0]['customer_id'] == $row['customer_id'])
																		{
																			$selected="selected='selected'";
																		}
																		?>
																		<option value="<?php echo $row['customer_id'];?>" <?php echo $selected;?>><?php echo ucfirst($row['customer_name']);?></option>
																		<?php 
																	} 
																?>
															</select>
														</div>
													</div>
												</div>									
											</div>
											<?php
										}
										else if ($edit_data[0]["reg_user_type"] == "EMP")
										{
											?>
											<div class="col-md-6 employee_div">
												<div class="row">
													<?php 
														$employeeQry = "select 
																per_people_all.person_id,
																per_people_all.first_name	
																
																from per_people_all

																		where 
																		per_people_all.active_flag = 'Y' and 
																		per_people_all.deleted_flag = 'N'"; 
														$getEmployee = $this->db->query($employeeQry)->result_array();
														
													?>
													<div class="form-group col-md-4">
														<label class="col-form-label">Employee <span class="text-danger">*</span></label>
													</div>
													<div class="form-group col-md-6">
														<div class="">
															<select name="person_id" id="person_id" onchange="selectVendorPO(this.value);" style="pointer-events: none;" class="form-control emp_tab" required>
																<option value="">- Select -</option>
																<?php 
																	foreach($getEmployee as $row)
																	{ 
																		$selected="";
																		if( isset($edit_data[0]['person_id']) && $edit_data[0]['person_id'] == $row['person_id'])
																		{
																			$selected="selected='selected'";
																		}
																		?>
																		<option value="<?php echo $row['person_id'];?>" <?php echo $selected;?>><?php echo ucfirst($row['first_name']);?></option>
																		<?php 
																	} 
																?>
															</select>
														</div>
													</div>
												</div>
												
											</div>
											<?php
										}
										?>
										<?php
									}
									else
									{
										?>
											<div class="col-md-6 customer_div" style="display:none;">
												<div class="row">
													<?php 
														$customerQry = "select 
																customer.customer_id,
																customer.customer_name	
																
																from cus_customers as customer

																		where 
																		customer.active_flag = 'Y'"; 
														$getCustomer = $this->db->query($customerQry)->result_array();
														
													?>
													<div class="form-group col-md-4">
														<label class="col-form-label">Customer <span class="text-danger">*</span></label>
													</div>
													<div class="form-group col-md-6">
														<div class="">
															<select name="customer_id" id="customer_id" class="form-control -searchDropdown" required>
																<option value="">- Select -</option>
																<?php 
																	foreach($getCustomer as $row)
																	{ 
																		$selected="";
																		if( isset($edit_data[0]['customer_id']) && $edit_data[0]['customer_id'] == $row['customer_id'])
																		{
																			$selected="selected='selected'";
																		}
																		?>
																		<option value="<?php echo $row['customer_id'];?>" <?php echo $selected;?>><?php echo ucfirst($row['customer_name']);?></option>
																		<?php 
																	} 
																?>
															</select>
														</div>
													</div>
												</div>
												
											</div>	
											<div class="col-md-6 employee_div" style="display:none;">
												<div class="row">
													<?php 
														$employeeQry = "select 
																per_people_all.person_id,
																per_people_all.first_name	
																
																from per_people_all

																		where 
																		per_people_all.active_flag = 'Y' and 
																		per_people_all.deleted_flag = 'N'"; 
														$getEmployee = $this->db->query($employeeQry)->result_array();
														
													?>
													<div class="form-group col-md-4">
														<label class="col-form-label">Employee <span class="text-danger">*</span></label>
													</div>
													<div class="form-group col-md-6">
														<div class="">
															<select name="person_id" id="person_id" onchange="selectEmployee(this.value);" class="form-control emp_tab" required>
																<option value="">- Select -</option>
																<?php 
																	foreach($getEmployee as $row)
																	{ 
																		$selected="";
																		if( isset($edit_data[0]['person_id']) && $edit_data[0]['person_id'] == $row['person_id'])
																		{
																			$selected="selected='selected'";
																		}
																		?>
																		<option value="<?php echo $row['person_id'];?>" <?php echo $selected;?>><?php echo ucfirst($row['first_name']);?></option>
																		<?php 
																	} 
																?>
															</select>
														</div>
													</div>
												</div>
												
											</div>	
										<?php
									}
								?>
							</div>	
							
							<div class="row">	
								<?php 
									if(isset($type) && $type == "add")
									{		
										?>							
										<div class="col-md-6 ">								
											<div class="row">
												<div class="form-group col-md-4">
													<label class="col-form-label">User Name <span class="text-danger">*</span></label>
												</div>
												<?php
													$UserQry = "select 
													sm_list_type_values.list_type_value_id,
													sm_list_type_values.list_code,
													sm_list_type_values.list_value	
														from sm_list_type_values

														left join sm_list_types on 
															sm_list_types.list_type_id = sm_list_type_values.list_type_id
																where 
																	sm_list_type_values.active_flag = 'Y' and 
																	sm_list_types.list_name = 'USER_LOGIN_TYPE'";
													$getUser = $this->db->query($UserQry)->result_array();	
												?>
												<?php 
													/* $usrType = USER_LOGIN_TYPE;
													if(isset(USER_LOGIN_TYPE) &&  == $getUser[0]['list_type_value_id'])
													{
														?>
														<div class="form-group col-md-6">
															<div class="">											
																<input type="text" name="user_name" id="user_names" required class="form-control user_div" value="<?php echo isset($edit_data[0]['user_name']) ? $edit_data[0]['user_name'] :"";?>" placeholder="">
																<span class="user_name_exist_error error"></span>
															</div>
														</div>
														<?php
													}
													else
													{
														?>
														<div class="form-group col-md-6">
															<div class="">											
																<input type="text" name="user_name" id="user_name" required class="form-control user_div" value="<?php echo isset($edit_data[0]['user_name']) ? $edit_data[0]['user_name'] :"";?>" placeholder="">
																<span class="user_name_exist_error error"></span>
															</div>
														</div>
														<?php
													} */
												?>
												<div class="form-group col-md-6">
														<div class="">											
															<input type="text" name="user_name" id="user_name" required class="form-control single_quotes user_div" value="<?php echo isset($edit_data[0]['user_name']) ? $edit_data[0]['user_name'] :"";?>" placeholder="">
															<span class="user_name_exist_error error"></span>
														</div>
													</div>
											</div>	
										</div>	
										<div class="col-md-6">
											<div class="row">
												<div class="form-group col-md-4">
													<label class="col-form-label">Password <span class="text-danger">*</span></label>
												</div>
												<div class="form-group col-md-6">
													<div class="">
														<input type="password" name="password" required class="form-control" value="<?php echo isset($edit_data[0]['attribute1']) ? $edit_data[0]['attribute1'] :"";?>" placeholder="">
													</div>
												</div>
												
											</div>
										</div>
										<?php 
									}
									else if(isset($type) && $type == "edit" || $type == "view")
									{
										?>
										<div class="col-md-6 ">													
											<div class="row">
												<div class="form-group col-md-4">
													<label class="col-form-label">User Name</label>
												</div>
												
												<div class="form-group col-md-6">
													<div class="">											
														<input type="text" name="user_name" id="user_names" readonly class="form-control single_quotes user_div" value="<?php echo isset($edit_data[0]['user_name']) ? $edit_data[0]['user_name'] :"";?>" placeholder="">
														<span class="user_name_exist_error error"></span>
													</div>
												</div>
														
											</div>	
										</div>

										<div class="col-md-6">
											<div class="row">
												<div class="form-group col-md-4">
													<label class="col-form-label">Password</label>
												</div>
												<div class="form-group col-md-6">
													<div class="">
														<input type="text" name="password" readonly class="form-control" value="<?php echo isset($edit_data[0]['attribute1']) ? $edit_data[0]['attribute1'] :"";?>" placeholder="">
													</div>
												</div>
												
											</div>
										</div>
										<?php
									}
								?>
							</div>

							<div class="row">							
								<div class="col-md-6 ">								
									<div class="row">
										<div class="form-group col-md-4">
											<label class="col-form-label">Start Date</label>
										</div>
										<div class="form-group col-md-6">
											<div class="input-group">											
												<input type="text" name="start_date" id="start_date" readonly autocomplete="off" class="form-control default_date selectInvoiceDate" value="<?php echo isset($edit_data[0]['start_date']) ? $edit_data[0]['start_date'] :"";?>" placeholder="">
												<div class="input-group-append"><span class="input-group-text"><i class="fa fa-calendar"></i></span>
												</div>
											</div>
										</div>
									</div>	
								</div>	
								<div class="col-md-6">
									<div class="row">
										<div class="form-group col-md-4">
											<label class="col-form-label">End Date</label>
										</div>
										<div class="form-group col-md-6">
											<div class="input-group">											
												<input type="text" name="end_date" id="end_date" readonly class="form-control default_date selectInvoiceDate" autocomplete="off" value="<?php echo isset($edit_data[0]['end_date']) ? $edit_data[0]['end_date'] :"";?>" placeholder="">
												<div class="input-group-append"><span class="input-group-text"><i class="fa fa-calendar"></i></span>
												</div>
											</div>
										</div>
										
									</div>
								</div>
							</div>	
							
							<script>
								function selectUserType(val)
								{	
									if(val == "CUS")
									{
										$(".customer_div").show();
										$(".employee_div").hide();
										$('#project_name').attr('required',true);
										$('.user_div').removeAttr('readonly',false);
										$('.emp_tab').removeAttr('required',false);
									}
									else if(val == "EMP")
									{
										$(".customer_div").hide();
										$(".employee_div").show();
										$('#project_name').removeAttr('required',false);
										$('#customer_id').removeAttr('required',false);
										$('.emp_tab').removeAttr('required',false);
										$('.user_div').removeAttr('readonly',false);
									}
								}

								/* function selectEmployee(val)
								{
									if(val)
									{
										$.ajax({
											type: "POST",
											url:"<?php echo base_url().'users/ajaxSelectEmployee';?>",
											data: { id: val}
										}).done(function( msg ) 
										{   
											var email = msg;
											
											$("#user_name").val(email);
											//validateSubmitButton();
										});
									}
								} */

								function selectEmployee(user_id)
								{	
									$('#new_user_id').val(user_id);
									var id = user_id;
									
									//checkUserExist(user_id)

									if(id >= 0 )
									{
										$.ajax({
											url: "<?php echo base_url('users/ajaxSelectEmployee') ?>/"+id,
											type: "GET",
											data:{
												'<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
											},
											datatype: "JSON",
											success: function(d)
											{
												data = JSON.parse(d);
												var userDatas = data['empsData'];
												
												$("#user_names").val(userDatas[0]["email_address"]);		
												$("#user_name").val(userDatas[0]["first_name"]);								
											},
											error: function(xhr, status, error) 
											{
												$('#err_product').text('Enter Product Code / Name').animate({opacity: '0.0'}, 2000).animate({opacity: '0.0'}, 1000).animate({opacity: '1.0'}, 2000);
											}
										});
									}
								}	
							</script>
							
							<!-- Multple roles in single user start here -->
							<?php 
								$roles = $this->db->query("select role_id,role_name from org_roles where active_flag='Y'")->result_array();
							
								if($type == 'add')
								{
									$required='required';
								}
								else if($type == 'edit')
								{
									$user_id = $edit_data[0]['user_id'];

									$userRoles = $this->db->query("select user_role_id from per_user_roles 
												left join org_roles on
													org_roles.role_id =  per_user_roles.role_id
												where
													per_user_roles.user_id ='".$user_id."'
												")->result_array();

									if(count($userRoles) > 0)	
									{
										$required='';
									}
									else
									{
										$required='required';
									}
								}
							?>

							<?php
								if($type == "view")
								{
								}
								else
								{
									?>
									<div class="row form-group mt-2">
										<label class="col-form-label col-lg-2">Roles </label>
										<div class="col-lg-3">
											<select class="form-control searchDropdown" id="roles" name="roles">
												<option value="">- Select Role -</option>					  
												<?php
													foreach($roles as $role) 
													{
														?>
														<option value="<?php echo $role['role_id'];?>"><?php echo ucfirst($role['role_name']);?></option>
														<?php
													}
												?>
											</select>
										</div>
									</div>
									<?php 
								} 
							?>
							
							<div class="row mt-2">
								<div class="col-sm-12">
									<div class="form-group">
										<div style="overflow-y: auto;">
											<div id="err_product" style="color:red;margin: 0px 0px 10px 0px;"></div>
											<table class="table items --table-striped table-bordered table-condensed table-hover product_table" name="product_data" id="product_data">
												<thead>
													<tr>
														<th colspan="13">
															User Roles
														</th>
													</tr>
													<tr>
														<?php
															if($type == "view")
															{

															}
															else
															{ 
																?>
																<th style="width:30px;"> </th>
																<?php 
															} 
														?>
														<th>Role Name</th>
														<th>Status</th>
													</tr>
												</thead>
												<tbody id="product_table_body">
													<?php
														if( isset($type) && $type == "edit" || $type == "view" )
														{
															$user_id = $edit_data[0]['user_id'];
															$userRoles = $this->db->query("
																select * from per_user_roles 
																left join org_roles on
																	org_roles.role_id =  per_user_roles.role_id
																where
																per_user_roles.user_id ='".$user_id."'
																")->result_array();
																
															if( count($userRoles) > 0)
															{
																$i=0;
																$counter=1;
																foreach($userRoles as $documents)
																{
																	?>
																	<tr class="dataRowVal<?php echo $documents['role_id']; ?>">
																		<?php
																			if($type == "view")
																			{
																			}
																			else
																			{ 
																				?>
																				<td class="text-center">
																					<a class='deleteRow1'> 
																						<i class="fa fa-trash"></i> 
																					</a>
																					<input type='hidden' name='id' name='id' value="<?php echo $i ?>">
																					<input type='hidden' name='role_id[]' value="<?php echo $documents['role_id']; ?>">
																				</td>
																				<?php 
																			} 
																		?>
																		<td><?php echo ucfirst($documents['role_name']); ?></td>
																		
																		<td>
																			<select class='form-control' id='role_status' name='role_status[]'>
																				<?php
																					foreach($this->role_status as $key => $value)
																					{
																						$selected="";
																						if($documents['user_role_status'] == $key)
																						{
																							$selected="selected='selected'";
																						}
																						?>
																						<option value="<?php echo $key;?>" <?php echo $selected;?>><?php echo $value;?></option>
																						<?php 
																					} 
																				?>
																			</select>
																		</td>
																	</tr>
																	<?php 
																	$counter++;
																	$i++;
																} 
															} 
														} 
													?>
												</tbody>
											</table>
										</div>
									</div>
								</div>
							</div>
							
							<script>
								$(document).ready(function()
								{
									var type = '<?php echo $type;?>';
									
									if( type == 'add' )
									{
										var i = 0;
										var product_data = new Array();
										var counter = 1;
									}
									else
									{
										var counter1 = '<?php echo isset($userRoles) ? count($userRoles) : 1; ?>';
										
										if(counter1 == 0)
										{
											var i = 0;
											var product_data = new Array();
											var counter = 1;
										}
										else
										{
											var i = '<?php echo isset($i) ? $i++ : "0"; ?>';
											var product_data = new Array();
											var counter = '<?php echo isset($userRoles) ? count($userRoles) : 1; ?>';
										}
									}
									
									$('#roles').change(function()
									{
										var id = $(this).val();
										$('#err_product').text('');
										var flag = 0;
										
										if(id != "")
										{
											$.ajax({
												url: "<?php echo base_url('users/getRoles') ?>/"+id,
												type: "GET",
												data:{
													'<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
												},
												datatype: "JSON",
												success: function(d)
												{
													data = JSON.parse(d);
						
													$("table.product_table").find('input[name^="role_id[]"]').each(function () 
													{
														if(data[0].role_id  == +$(this).val())
														{
															flag = 1;
														}
													});
													
													if(flag == 0)
													{
														var id = data[0].role_id;
														var role_name = data[0].role_name;
														
														var roleStatus = data['roleStatus'];
														
														var newRow = $("<tr class='dataRowVal"+id+"'>");
														var cols = "";
														cols += "<td class='text-center'><a class='deleteRow'> <i class='fa fa-trash'></i> </a><input type='hidden' name='id' name='id' value="+i+"><input type='hidden' name='role_id[]' value="+id+"></td>";
														cols += "<td class='tab-medium-width'>"+role_name+"</td>";
							
														cols += "<td class='tab-medium-width'>"+roleStatus+"</td>";
														
														cols += "</tr>";
														counter++;

														newRow.html(cols);
														$("table.product_table").append(newRow);
														var table_data = JSON.stringify(product_data);
														$('#table_data').val(table_data);
														i++;
													}
													else
													{
														$('#err_product').text('Role already exist!').animate({opacity: '0.0'}, 2000).animate({opacity: '0.0'}, 1000).animate({opacity: '1.0'}, 2000);
													}
												},
												error: function(xhr, status, error) 
												{
													$('#err_product').text('Please select atleast one role!').animate({opacity: '0.0'}, 2000).animate({opacity: '0.0'}, 1000).animate({opacity: '1.0'}, 2000);
												}
											});
										}
									});
									
									$("table.product_table").on("click", "a.deleteRow,a.deleteRow1", function (event) 
									{
										$(this).closest("tr").remove();
									});
								});
							</script>
							<!-- Multple roles in single user end here -->
						</fieldset>

						<div class="d-flexad mb-3" style="text-align:right;">
							<a href="<?php echo base_url(); ?>users/ManageUsers" class="btn btn-default">Close</a>
							
							<?php
								if($type == "view")
								{

								}
								else
								{ 
									?>
									<button type="submit" class="btn btn-primary ml-1 register-but register-but-1">Save</button>
									<?php 
								} 
							?>
						</div>
					</form>
					
					<!-- Select User Code start-->
					<script>
						$(document).ready(function()
						{
							$('#role_user_id').change(function()
							{
								var role_user_id = $('#role_user_id').val();
								
								if(role_user_id != "")
								{
									$.ajax({
										url: "<?php echo base_url('users/getUserDetails') ?>/"+role_user_id,
										type: "GET",
										data:{
											'<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
										},
										datatype: "JSON",
										success: function(d)
										{
											data = JSON.parse(d);
				
											if(data[0].phone_number !=""){
												var phoneNumber = data[0].phone_number;
											}else if(data[0].mobile_number !=""){
												var phoneNumber = data[0].mobile_number;
											}

											$("#first_name").val(data[0].first_name);
											$("#last_name").val(data[0].last_name);
											$("#email").val(data[0].email);
											$("#phone_number").val(phoneNumber);

											$("#user_name").val(data[0].random_user_id);
										},
										error: function(xhr, status, error) 
										{
											$('#err_product').text('Please select user code!').animate({opacity: '0.0'}, 2000).animate({opacity: '0.0'}, 1000).animate({opacity: '1.0'}, 2000);
										}
									});
								}
							});
						});
					</script>
					<!-- Select User Code end-->
					<?php
				}
				else
				{ 
					?>
					<!-- buttons start here -->
					<div class="row mb-2">
						<div class="col-md-6"><h3><b><?php echo $page_title;?></b></h3></div>
						<div class="col-md-6 float-right text-right">
							<?php
								if($usersMenu['create_edit_only'] == 1 || $this->user_id == 1)
								{
									?>
									<a href="<?php echo base_url(); ?>users/ManageUsers/add" class="btn btn-info btn-sm">
										Create User
									</a>
									<?php
								} 
							?>
						</div>
					</div>
					<!-- buttons end here -->

					<!-- Filters start here -->
					<form action="" class="form-validate-jquery" method="get">
						<div class="row">
							<div class="col-md-3">
								<div class="row">
									<label class="col-form-label col-md-4 text-right">User Type</label>
									<div class="form-group col-md-8">
										<select name="user_type" id="user_type" class="form-control searchDropdown">
											<!-- <option value="">- Select -</option> -->
											<?php 
												foreach($this->user_type as $key => $value)
												{
													$selected="";
													if( isset($_GET['user_type']) && $_GET['user_type'] == $key )
													{
														$selected="selected='selected'";
													}
													?>
													<option value="<?php echo $key;?>" <?php echo $selected;?>><?php echo $value;?></option>
													<?php 
												} 
											?>
										</select>
									</div>
								</div>
							</div>

							<div class="col-md-3">
								<div class="row">
									<label class="col-form-label col-md-4 text-right">User Name <!-- <span class="text-danger">*</span> --></label>
									<div class="form-group col-md-8">
										<?php 
											$userQry = "select user_id,user_name from per_user 
														
														order by user_name asc";

											$getUsers = $this->db->query($userQry)->result_array();	
										?>
										<select name="user_id" id="user_id" --required class="form-control searchDropdown">
											<option value="">- Select -</option>
											<?php 
												foreach($getUsers as $row)
												{
													$selected="";
													if(isset($_GET['user_id']) && $_GET['user_id'] == $row["user_id"] )
													{
														$selected="selected='selected'";
													}
													?>
													<option value="<?php echo $row["user_id"];?>" <?php echo $selected;?>><?php echo ucfirst($row["user_name"]);?></option>
													<?php 
												} 
											?>
										</select>
									</div>
								</div>
							</div>
							
							<div class="col-md-3">
								<div class="row">
									<label class="col-form-label col-md-3 text-right">Status</label>
									<div class="form-group col-md-9">
										<?php 
											$activeStatus = $this->common_model->lov('ACTIVESTATUS'); 
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
							
							<div class="col-md-3">
								<button type="submit" class="btn btn-info">Search <i class="fa fa-search" aria-hidden="true"></i></button>
								<a href="<?php echo base_url(); ?>users/ManageUsers" title="Clear" class="btn btn-default">Clear</a>
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
								<table id="myTable" class="table table-bordered table-hover --table-striped dataTable">
									<thead>
										<tr>
											<th class="text-center">Controls</th>
											<th onclick="sortTable(3)">User Name</th>
											<th class="text-center" onclick="sortTable(5)">User Roles</th>
											<th onclick="sortTable(7)">Created Date</th>
											<th onclick="sortTable(7)" >Login Status</th>
											<th onclick="sortTable(7)">Last Login Date</th>
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
													<td style="width: 12%;" class="text-center">
														<div class="dropdown" style="display: inline-block;--padding-right: 10px!important;width:92px;">
															<button type="button" class="btn btn-outline-primary gropdown-toggle waves-effect waves-light btn-sm" data-toggle="dropdown" aria-expanded="false">
																Action <i class="fa fa-angle-down"></i>
															</button>
															<ul class="table-dropdown dropdown-menu dropdown-menu-right dropdown-menu-new">
																<?php
																	if((isset($usersMenu['create_edit_only']) && $usersMenu['create_edit_only'] == 1) || $this->user_id == 1)
																	{
																		?>
																		<?php
																			if($usersMenu['create_edit_only'] == 1 || $this->user_id == 1)
																			{
																				?>
																				<li>
																					<a href="<?php echo base_url(); ?>users/ManageUsers/edit/<?php echo $row['user_id'];?>">
																						<i class="fa fa-edit"></i> Edit
																					</a>
																				</li>
																				<?php 
																			} 
																		?>

																		<?php
																			if($usersMenu['read_only'] == 1 || $this->user_id == 1)
																			{
																				?>
																				<li>
																					<a title="View" href="<?php echo base_url(); ?>users/ManageUsers/view/<?php echo $row['user_id'];?>">
																						<i class="fa fa-eye"></i> View
																					</a>
																				</li>
																				<?php 
																			} 
																		?>
																		<?php
																			if($usersMenu['create_edit_only'] == 1 || $this->user_id == 1)
																			{
																				?>
																				<li>
																					<?php 
																						if($row['active_flag'] == "Y")
																						{
																							?>
																							<a class="unblock" href="<?php echo base_url(); ?>users/ManageUsers/status/<?php echo $row['user_id'];?>/N" title="Block">
																								<i class="fa fa-ban"></i> Inactive
																							</a>
																							<?php 
																						} 
																						else
																						{  ?>
																							<a class="block" href="<?php echo base_url(); ?>users/ManageUsers/status/<?php echo $row['user_id'];?>/Y" title="Unblock">
																								<i class="fa fa-check"></i> Active
																							</a>
																							<?php 
																						} 
																					?>
																				<li>

																				<li>
																					<?php 
																						if($row['active_flag'] == "Y")
																						{
																							?>
																							<a title="Change Password" href="javascript::void(0);" data-toggle="modal" data-target="#exampleModal<?php echo $row['user_id'];?>">	
																								<i class="fa fa-lock"></i> Change Password
																							</a>
																							<?php 
																						} 
																					?>
																				</li>

																				<li>
																					<?php 
																						if($row['last_login_status'] == "Y")
																						{
																							?>
																							<a title="Change Password" href="<?php echo base_url(); ?>users/ManageUsers/login_status/<?php echo $row['user_id'];?>/N" title="Unblock">	
																								<i class="fa fa-check"></i> Update Login
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
																<?php 
																	/* if((isset($users['read_only']) && $users['read_only'] == 1) || $this->user_id == 1)
																	{
																		?>
																		<!-- <li>
																			<a title="View" href="javascript:void(0);" data-toggle="modal" data-target="#ViewUserDetail<?php echo $row['user_id'];?>">	
																				<i class="fa fa-eye"></i> View
																			</a>
																		</li> -->
																		<?php 
																	}  */
																?>
															</ul>
														</div>
														
														<!-- Change Password Modal -->
														<div class="modal fade MyPopup" id="exampleModal<?php echo $row['user_id'];?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel<?php echo $row['user_id'];?>" aria-hidden="true">
															<div class="modal-dialog" role="document">
																<div class="modal-content">
																	<div class="modal-header" --style="background: #3f97e5;color: #fff;">
																		<h5 class="modal-title" id="exampleModalLabel">Change Password</h5>
																		<button type="button" class="close" data-dismiss="modal" aria-label="Close">
																			<span aria-hidden="true">&times;</span>
																		</button>
																	</div>
																	
																	<form action="<?php echo base_url();?>users/ManageUsers/change_password/<?php echo $row['user_id'];?>" method="post">
																		<div class="modal-body">
																			<div class="row">
																				<div class="form-group col-md-6">
																					<label class="col-form-label float-left">User Name</label>
																					<input type="hidden" name="user_id" id="user_id" value="<?php echo $row['user_id'];?>" class="form-control" />
																					<input type="text" value="<?php echo $row['user_name'];?>" class="form-control" disabled />
																				</div>
																				<div class="form-group col-md-6">
																					<label class="col-form-label float-left">Current Password</label>
																					<input type="text" readonly name="password" id="password" value="<?php echo $row['attribute1'];?>" class="form-control" />
																				</div>
																			</div>
																			
																			<div class="row">
																				<div class="form-group col-md-6">
																					<label class="col-form-label float-left">New Password <span class="text-danger">*</span></label>
																					<input type="password" autocomplete="off" name="new_password" id="new_password" class="form-control"required />
																					<span class="password_mismatched"></span>
																				</div>
																			
																				<div class="form-group col-md-6">
																					<label class="col-form-label float-left">Confirm New Password <span class="text-danger">*</span></label>
																					<input type="password" autocomplete="off" name="confirm_new_password" id="confirm_new_password" class="form-control"required />
																				</div>
																			</div>
																		
																		</div>
																		<div class="modal-footer">
																			<button type="button" class="btn btn-default" id='btnClosePopup' data-dismiss="modal">Close</button>
																			<button type="submit" class="btn btn-primary disabled-class channge_pwd_btn">Submit</button>
																		</div>
																	</form>
																</div>
															</div>
														</div>
														<!-- Change Password Modal End-->

														<!-- View User Detail Details start here -->
														<div class="modal fade" id="ViewUserDetail<?php echo $row['user_id'];?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
															<div class="modal-dialog modal-dialog-centered" role="document">
																<div class="modal-content">
																	<div class="modal-header">
																		<h5 class="modal-title" id="exampleModalLabel">User Details :</h5>
																		<button type="button" class="close" data-dismiss="modal" aria-label="Close">
																			<span aria-hidden="true">&times;</span>
																		</button>
																	</div>
																	
																	<div class="modal-body text-left">
																		<div class="row">
																			<div class="col-md-12">
																				<span class="mt-0">
																					<h4>
																						<?php echo ucfirst($row['user_name']);?> 
																						<?php
																							if((isset($users['create_edit_only']) && $users['create_edit_only'] == 1) || $this->user_id == 1)
																							{
																								?>
																								<a href="<?php echo base_url();?>users/ManageUsers/edit/<?php echo $row['user_id'];?>" class="btn btn-primary rounded-circle btn-sm"><i class="fa fa-pencil"></i></a>
																								<?php 
																							} 
																						?>	
																					</h4>
																				</span>
																			</div>
																		</div>

																		
																		<hr/>

																		<div class="row">
																			<div class="col-md-6">
																				<h4 class="sub_title">Login Information</h4> 
																				<div class="row col-md-12 p-0">
																					<div class="col-md-5">User Name</div>
																					<div class="col-md-1"> :</div>
																					<div class="col-md-6"> <?php echo ($row['user_name']);?></div>
																				</div>
																				<div class="row col-md-12 p-0 mt-2">
																					<div class="col-md-5">Password</div>
																					<div class="col-md-1"> :</div>
																					<div class="col-md-6"> <?php echo ($row['attribute1']);?></div>
																				</div>
																			</div>
																		</div>
								
																		

																		<!-- Roles start here-->
																		<?php
																			$userRoles = "select 
																				per_user_roles.*,
																				org_roles.* from per_user_roles 
																			left join org_roles on org_roles.role_id = per_user_roles.role_id
																			where per_user_roles.user_id='".$row['user_id']."' ";
																			$getUserRoles = $this->db->query($userRoles)->result_array();
																		?>
																		<table class="table mt-2 table-bordered">
																			<thead>
																				<tr>
																					<th colspan="3">User Roles</th>
																				</tr>
																				<tr>
																					<th scope="col" class="text-center">#</th>
																					<th scope="col">Role Name</th>
																					<th class="text-center">Role Status</th>
																				</tr>
																			</thead>
																			<tbody>
																				<?php 
																					$i=1;
																					foreach($getUserRoles as $roles)
																					{
																						?>
																						<tr>
																							<td scope="row" class="text-center"><?php echo $i;?></td>
																							<td><?php echo ucfirst($roles["role_name"]);?></td>
																							<td class="text-center">
																								<?php 
																									if($roles['active_flag'] == "Y")
																									{
																										?>
																										<span class="btn btn-outline-success btn-sm" title="Active"> Active</span>
																										<?php 
																									} 
																									else
																									{ 
																										?>
																										<span class="btn btn-outline-warning btn-sm" title="Inactive"> Inactive</span>
																										<?php 
																									} 
																								?>
																							</td>
																						</tr>
																						<?php 
																						$i++;
																					} 
																				?>
																				<?php if(count($getUserRoles) == 0){?>
																					<tr>
																						<td colspan="3">
																							<div class="text-center">
																								<img src="<?php echo base_url();?>uploads/nodata.png" style="height:150px;width:150px;">
																							</div>
																						</td>
																					</tr>
																				<?php } ?>
																			</tbody>
																		</table>
																		<!-- Roles end here-->
																		
																	</div>	
																</div>
																	
																<!--<div class="modal-footer">
																	<button type="button" class="btn btn-light" data-dismiss="modal">Close </button>
																	<button type="submit" class="btn btn-primary ml-3">Submit </button>
																</div>-->	
															</div>
														</div>
														<!-- View User Detail Details end here -->
													</td>
													
													<td class="tab-medium-width">
														<?php 
															echo $row['user_name'];
														?>		
													</td>
													
													<td class="text-center">
														<?php
															$roleQuery = "select user_role_id from per_user_roles where user_id='".$row['user_id']."' ";
															$getUserRoles = $this->db->query($roleQuery)->result_array();

															if(count($getUserRoles)> 0){
																$btnClass="primary";
															}else{
																$btnClass="warning";
															}
														?>
														<span class="btn btn-outline-<?php echo $btnClass;?> btn-sm"><?php echo count($getUserRoles);?></span>
													</td>
													
													<td >
														<?php echo date(DATE_FORMAT,strtotime($row['created_date']));?>
													</td>
													<td >
														<?php 
															if($row['last_login_status'] == "Y")
															{
																?>
																<span class="text-success">Yes</span>
																<?php
															}
															else if($row['last_login_status'] == "N")
															{
																?>
																<span class="text-primary">No</span>
																<?php
															}
														?>
													</td>
													
													<td >
														<?php 
															if($row['last_login_date'] != NULL)
															echo date("d-M-y h:s:i A",strtotime($row['last_login_date']));
														?>
													</td>

													<td class="text-center">
														<?php 
															if($row['active_flag'] == "Y")
															{
																?>
																<span class="btn btn-outline-success btn-sm" title="Active"> Active</span>
																<?php 
															} 
															else
															{ 
																?>
																<span class="btn btn-outline-warning btn-sm" title="Inactive"> Inactive</span>
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
					<?php 
				} 
			?>
		</div><!-- Card end-->
	</div><!-- Content end-->
</div><!-- Content end-->

<style>
	span.password_mismatched {
		float: left;
		color: red;
		padding: 5px 0px 0px 0px;
	}
</style>


<!-- User Name exist start here -->
<script type="text/javascript">  
 /* 	$(function () 
	{
        $("#btnClosePopup").click(function () 
		{
			// $("#MyPopup").modal().hide();
			 $('#modal').modal().hide();
        });
    }); */

	$('document').ready(function()
	{
		$(".password_mismatched").html('');

		/* $("#btnClosePopup").click(function(){
            $("#myModal").modal('hide');
        }); */

		$('#new_password,#confirm_new_password').on('input', function()
		{
			var user_id = $('#user_id').val();
			var current_password = $('#password').val();
			var new_password = $('#new_password').val();
			var confirm_new_password = $('#confirm_new_password').val();
			
			if( new_password && confirm_new_password )
			{
				$.ajax({
					url  : '<?php echo base_url();?>users/ajaxCheckUserPassword',
					type : 'post',
					data : {
						'user_id' : user_id,
						'current_password'     : current_password,
						'new_password'         : new_password,
						'confirm_new_password' : confirm_new_password
					},
					success: function(response)
					{
						if(response == 1)
						{
							$(".channge_pwd_btn").removeAttr("disabled", "disabled=disabled");
							$(".password_mismatched").html('');
						}
						else if(response == 2)
						{
							$(".password_mismatched").html('Password Mismatch!');
							$(".channge_pwd_btn").attr("disabled", "disabled=disabled");
						}
					}
				});
			}
			else
			{

			}
		});
	});
	//Customer E-mail End here
</script>
<!-- User Name exist end here -->

<?php
	if($type == "add")
	{
		$user_id = 0;
	}
	else if($type == "edit")
	{
		$user_id = $id;
	}
?>

<!-- User id exist start here -->
<script type="text/javascript">  
	$('document').ready(function()
	{
		//Customer Start here
		$(".register-but-1").removeClass("disabled-class");
		
		var user_name_state = false;

		$('#user_user_id').on('input', function()
		{
			var user_user_id = $('#user_user_id').val();
			
			if (user_user_id == '') 
			{
				user_id_state = false;
				return;
			}
			else
			{
				$.ajax({
					url: '<?php echo base_url();?>users/chkUserIdExist',
					type: 'post',
					data: {
						'user_id_check' : 1,
						'user_user_id'  : user_user_id,
						'type'          : '<?php echo $type?>',
						'user_id'       : '<?php echo $user_id?>',
					},
					success: function(response)
					{
						if (response == 'taken' ) 
						{
							user_id_state = false;
							
							/* $('.form-control.email').removeClass("valid");
							$('.form-control.email').addClass("error");
							
							$(".form-control.email").attr("aria-required", "true");
							$(".form-control.email").attr("aria-describedby", "email-error");
							$(".form-control.email").attr("aria-invalid", "true"); */
							
							$(".user_id_exist_error").addClass("error");
							$(".user_id_exist_error").attr("id", "user_name-error");
							$(".user_id_exist_error").attr("style", "display: inline;");
							
							$(".register-but-1").attr("disabled", "disabled=disabled");
							$(".register-but-1").addClass("disabled-class");
							$('.user_id_exist_error').html('Sorry... User ID already taken');
							
							return false;
						}
						else if (response == 'not_taken') 
						{
							$(".user_id_exist_error").attr("style", "display: none;");
							$(".register-but-1").removeAttr("disabled", "disabled=disabled");
							$(".register-but-1").removeClass("disabled-class");
							return true;
						}
					}
				});
			}
		});
		//Customer E-mail End here
	});
</script>
<!-- User id exist end here -->

<!-- User Name exist start here -->
<script type="text/javascript">  
	$('document').ready(function()
	{
		//Customer Start here
		$(".register-but-1").removeClass("disabled-class");
		
		var user_name_state = false;

		$('#user_name').on('input', function()
		{
			var user_name = $('#user_name').val();
			
			if (user_name == '') 
			{
				user_name_state = false;
				return;
			}
			else
			{
				$.ajax({
					url: '<?php echo base_url();?>users/UsernameExist',
					type: 'post',
					data: {
						'user_name_check' : 1,'user_name' : user_name,
					},
					success: function(response)
					{
						if (response == 'taken' ) 
						{
							user_name_state = false;
							
							/* $('.form-control.email').removeClass("valid");
							$('.form-control.email').addClass("error");
							
							$(".form-control.email").attr("aria-required", "true");
							$(".form-control.email").attr("aria-describedby", "email-error");
							$(".form-control.email").attr("aria-invalid", "true"); */
							
							$(".user_name_exist_error").addClass("error");
							$(".user_name_exist_error").attr("id", "user_name-error");
							$(".user_name_exist_error").attr("style", "display: inline;");
							
							$(".register-but-1").attr("disabled", "disabled=disabled");
							$(".register-but-1").addClass("disabled-class");
							$('.user_name_exist_error').html('Sorry... UserName already taken');
							
							return false;
						}
						else if (response == 'not_taken') 
						{
							$(".user_name_exist_error").attr("style", "display: none;");
							$(".register-but-1").removeAttr("disabled", "disabled=disabled");
							$(".register-but-1").removeClass("disabled-class");
							return true;
						}
					}
				});
			}
		});
		//Customer E-mail End here
	});
</script>
<!-- User Name exist end here -->