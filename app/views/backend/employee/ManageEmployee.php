<?php
	$employeesMenu = accessMenu(employees);
?>

<div class="content"><!-- Content start-->
	<div class="card"><!-- Card start-->
		<div class="card-body">
			<legend class="text-uppercase font-size-sm font-weight-bold d-none">
				<?php echo $type; ?> Employee :
			</legend>
			<?php
				if(isset($type) && $type == "add") //$type == "edit"
				{
					$activeBasic = $activeCareer = 
					$activeID = $activeAddress = $activeBank =
					$activeLogin= '';
					if( isset($type) )
					{
						if( $id == 'basic-info' )
						{
							$activeBasic = 'active';
						}
						else if( $id == 'career-info' )
						{
							$activeCareer = 'active';
						}
						else if( $id == 'id-info' )
						{
							$activeID = 'active';
						}
						else if( $id == 'address-info' )
						{
							$activeAddress = 'active';
						}
						else if( $id == 'bank-info' )
						{
							$activeBank = 'active';
						}
						else if( $id == 'login-info' )
						{
							$activeLogin = 'active';
						}
					}
					?>
					
					<ul class="nav nav-tabs" role="tablist">
						<li class="nav-item <?php echo $activeBasic;?>">
							<a href="<?php echo base_url(); ?>employee/ManageEmployee/<?php echo $type;?>/basic-info/" class="nav-link">Basic Info</a>
						</li>
						
						<li class="nav-item <?php echo $activeCareer;?>">
							<?php 
								if(!empty($status))
								{
									?>
									<a href="<?php echo base_url(); ?>employee/ManageEmployee/<?php echo $type;?>/career-info/<?php echo $status;?>" class="nav-link">
										Employment Details
									</a>
									<?php 
								}
								else
								{
									?>
									<a href="javascript:void(0);" class="nav-link">
										Employment Details
									</a>
									<?php
								} 
							?>
						</li>
						
						<li class="nav-item <?php echo $activeID;?>">
							<?php 
								if(!empty($status))
								{
									?>
									<a href="<?php echo base_url(); ?>employee/ManageEmployee/<?php echo $type;?>/id-info/<?php echo $status;?>" class="nav-link">Identity</a>
									<?php 
								}
								else
								{
									?>
									<a href="javascript:void(0);" class="nav-link">
										Identity
									</a>
									<?php
								} 
							?>
						</li>
						
						<li class="nav-item <?php echo $activeAddress;?>">
							<?php 
								if(!empty($status))
								{
									?>
									<a href="<?php echo base_url(); ?>employee/ManageEmployee/<?php echo $type;?>/address-info/<?php echo $status;?>" class="nav-link">Address</a>
									<?php 
								}
								else
								{
									?>
									<a href="javascript:void(0);" class="nav-link">
										Address
									</a>
									<?php
								} 
							?>
						</li>
						
						<li class="nav-item <?php echo $activeBank;?>">
							<?php 
								if(!empty($status))
								{
									?>
									<a href="<?php echo base_url(); ?>employee/ManageEmployee/<?php echo $type;?>/bank-info/<?php echo $status;?>" class="nav-link">Bank Details</a>
									<?php 
								}
								else
								{
									?>
									<a href="javascript:void(0);" class="nav-link">
										Bank Details
									</a>
									<?php
								} 
							?>
						</li>

						<?php /*
						<li class="nav-item <?php echo $activeLogin;?>">
							<a href="<?php echo base_url(); ?>employee/ManageEmployee/<?php echo $type;?>/login-info" class="nav-link">Login</a>
						</li> */?>
					</ul>
				
					<?php	
						if(isset($id) && $id == 'basic-info')
						{
							?>
							<form action="" class="form-validate-jquery" --id="formValidation" enctype="multipart/form-data" method="post">
								<legend class="text-uppercase font-size-sm font-weight-bold">
									<div class="row">
										<div class="col-md-6">
											Basic Information
										</div>
										<div class="col-md-6 text-right">
											<input type="submit" name="save_close" value="Save & Close" class="btn btn-primary ml-1 btn-sm register-but">
										</div>
									</div>
								</legend>

								<?php 
									$listTypeValuesQry = "select 
										sm_list_type_values.list_type_value_id,
										sm_list_type_values.list_code,
										sm_list_type_values.list_value	
											from sm_list_type_values

											left join sm_list_types on 
												sm_list_types.list_type_id = sm_list_type_values.list_type_id
													where 
														sm_list_type_values.list_type_status = 1 and 
															sm_list_types.list_type_id = 1"; 
									$getEmploymentType = $this->db->query($listTypeValuesQry)->result_array();
									//print_r($getEmploymentType);exit;
								?>
								<div class="row">
									<div class="col-md-6">

										<div class="row">
											<div class="form-group col-md-4">
												<label class="col-form-label">Employment Type <span class="text-danger">*</span></label>
											</div>
											<div class="form-group col-md-6">
												<select name="employment_type" id="employment_type" class="form-control searchDropdown" required>
													<option value="">- Select -</option>
													<?php 
														foreach($getEmploymentType as $type)
														{ 
															$selected="";
															if( isset($edit_data[0]['employment_type']) && $edit_data[0]['employment_type'] == $type['list_type_value_id'])
															{
																$selected="selected='selected'";
															}
															?>
															<option value="<?php echo $type['list_type_value_id'];?>" <?php echo $selected;?>><?php echo ucfirst($type['list_value']);?></option>
															<?php 
														} 
													?>
												</select>
											</div>
										</div>
										
											
										<div class="row">	
											<div class="form-group col-md-4">
												<label class="col-form-label">First Name <span class="text-danger">*</span></label>
											</div>
											<div class="form-group col-md-6">
												<div class="">
													<input type="text" name="first_name" required class="form-control single_quotes" value="" autocomplete="off">
												</div>
											</div>							
										</div>

										<div class="row">	
											<div class="form-group col-md-4">
												<label class="col-form-label">Middle Name</label>
											</div>
											<div class="form-group col-md-6">
												<div class="">
													<input type="text" name="middle_name"  id="middle_name" class="form-control single_quotes" value="" placeholder="" autocomplete="off">
												</div>	
											</div>
										</div>
										<div class="row">
											<div class="form-group col-md-4">
												<label class="col-form-label">Last Name </label>
											</div>
											<div class="form-group col-md-6">
												<div class="">
													<input type="text" name="last_name" autocomplete="off" class="form-control single_quotes" value="" placeholder="" autocomplete="off">
												</div>
											</div>
										</div>
										<div class="row">
											<div class="form-group col-md-4">	
												<label class="col-form-label">Mobile Number <span class="text-danger">*</span></label>
											</div>
											<div class="form-group col-md-6">
												<div class="row">
													<div class="col-md-4 pr-0">
														<?php 
															
															$query = "select country.country_code,country.country_id
																	from geo_countries as country 
																	where 
																	active_flag='Y'
															";
															$getmobilenumber = $this->db->query($query)->result_array();

														?>
														<select name="mob_ctry_code" id="mobile_country_code" required class="form-control searchDropdown mobile_vali">
															<option value="">- Select -</option>
															<?php 
																foreach($getmobilenumber as $num)
																{
																	$selected="";
																	if(isset($_GET['country_id']) && $_GET['country_id'] == $num["country_code"] )
																	{
																		$selected="selected='selected'";
																	}
																	?>
																	<option value="<?php echo $num["country_id"];?>" <?php echo $selected; ?>><?php echo ucfirst($num["country_code"]);?></option>
																	<?php 
																}
															?>
														</select>
													</div>
													<div class="col-md-8 pl-0">
														<input type="text" name="mobile_number" id="mobile_number" required autocomplete="off"  minlength="10" maxlength="10" class="form-control mobile_vali code-num1" value="<?php //echo isset($edit_data[0]['mobile_number']) ? $edit_data[0]['mobile_number'] :"";?>" placeholder="9632587410">
														<span class="small mobile_number_exist" style="color:#a19f9f;"></span>
													</div>	
												</div>
											</div>	
										</div>
										<div class="row">
											<div class="form-group col-md-4">
												<label class="col-form-label">Alternate Mobile Number </label>
											</div>
											<div class="form-group col-md-6">
												<div class="row">
													<div class="col-md-4 pr-0">
														<?php 
															
															$query = "select country.country_code,country.country_id
																	from geo_countries as country 
																	where 
																	active_flag='Y'
															";
															$getaltmobilenumber = $this->db->query($query)->result_array();

														?>
														<?php /* <input type="text" name="alt_mobile_ctry_code" maxlength="4" id="mobile_country_code" autocomplete="off" class="form-control mobile_vali" value="<?php echo isset($edit_data[0]['alter_mobile_country_code']) ? $edit_data[0]['alter_mobile_country_code'] :"";?>" placeholder="91"> */ ?>
														<select name="alt_mob_ctry_code" id="mobile_country_code" class="form-control searchDropdown mobile_vali">
															<option value="">- Select -</option>
															<?php 
																foreach($getaltmobilenumber as $altnum)
																{
																	$selected="";
																	if(isset($_GET['country_id']) && $_GET['country_id'] == $altnum["country_id"] )
																	{
																		$selected="selected='selected'";
																	}
																	?>
																	<option value="<?php echo $altnum["country_id"];?>" <?php echo $selected; ?>><?php echo ucfirst($altnum["country_code"]);?></option>
																	<?php 
																}
															?>
														</select>
													</div>
													<div class="col-md-8 pl-0">
														<input type="text" name="alt_mob_number" id="alternate_contact" autocomplete="off"  minlength="10" maxlength='10' class="form-control mobile_vali --code-num1" value="<?php echo isset($edit_data[0]['alternate_contact']) ? $edit_data[0]['alternate_contact'] :"";?>" placeholder="9632587410">
														<span class="mobile_number_exist"></span>
													</div>
												</div>
											</div>	
										</div>
										<div class="row">
											<div class="form-group col-md-4">
												<label class="col-form-label">Email <span class="text-danger">*</span></label>
											</div>
											<div class="form-group col-md-6">
												<div class="">
													<input type="email" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,63}$" name="email_address" id="email" required autocomplete="off" class="form-control" value="<?php //echo isset($edit_data[0]['email']) ? $edit_data[0]['email'] :"";?>" placeholder="">
													<span class='small employee_email_exist_error' style="color:#a19f9f;"></span> 
											
												</div>
											</div>
										</div>
										<div class="row">	
											<div class="form-group col-md-4">
												<label class="col-form-label">Alternate E-Mail</label>
											</div>
											<div class="form-group col-md-6">
												<div class="">
													<input type="email" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,63}$" name="alt_email_address" --id="emp_email" autocomplete="off" class="form-control" value="<?php echo isset($edit_data[0]['alternate_email']) ? $edit_data[0]['alternate_email'] :"";?>" placeholder="">
												<?php /* <span class='employee_email_exist_error'></span> */?>
												</div>
											</div>
										</div>
									</div>

									<div class="col-md-6">

										<div class="row">
											<div class="form-group col-md-3">
												<label class="col-form-label">Father Name <span class="text-danger">*</span></label>
											</div>
											<div class="form-group col-md-6">
												<div class="">
													<input type="text" name="father_name" required id="father_name" class="form-control single_quotes" value="" placeholder="" autocomplete="off">
												</div>
											</div>
										</div>

										<div class="row">	
											<div class="form-group col-md-3">
												<label class="col-form-label">Mother Name <span class="text-danger">*</span></label>
											</div>
											<div class="form-group col-md-6">
												<div class="">
													<input type="text" name="mother_name" required id="mother_name" autocomplete="off" class="form-control single_quotes" value="<?php //echo isset($edit_data[0]['father_last_name']) ? $edit_data[0]['father_last_name'] :"";?>" placeholder="">
												</div>
											</div>
										</div> 

										<div class="row">	
											<div class="form-group col-md-3">
												<label class="col-form-label">Date of Birth <span class="text-danger">*</span></label>
											</div>
											<div class="form-group col-md-6">
												<input type="text" name="date_of_birth" id="date_of_birth" required readonly class="form-control default_date" autocomplete="off" value="<?php //echo isset($edit_data[0]['date_of_birth']) ? $edit_data[0]['date_of_birth'] :"";?>" placeholder="">
											</div>
										</div> 

										<div class="row">	
											<div class="form-group col-md-3">
												<label class="col-form-label">Gender <span class="text-danger">*</span></label>
											</div>
											<div class="form-group col-md-6">
												<select name="gender" required id="gender" class="form-control searchDropdown">
													<option value="">- Select -</option>
													<?php 
														foreach($this->gender as $key=>$value)
														{ 
															$selected="";
															if( isset($edit_data[0]['gender']) && $edit_data[0]['gender'] == $key)
															{
																$selected="selected='selected'";
															}

															?>
															<option value="<?php echo $key;?>" <?php echo $selected; ?>><?php echo $value;?></option>
															<?php 
														} 
													?>
												</select>
											</div>
										</div> 

										<div class="row">	
											<div class="form-group col-md-3">
												<label class="col-form-label">Blood Group</label>
											</div>
											<div class="form-group col-md-6">
												<?php 
													$bloodgroup = $this->db->query("select blood_group_name,blood_group_id from emp_blood_group where active_flag='Y'")->result_array();
												?>
												<select name="blood_group_id" id="blood_group_id" class="form-control searchDropdown">
													<option value="">- Select -</option>
													<?php 
														foreach($bloodgroup as $row)
														{
															$selected="";
															if(isset($edit_data[0]['blood_group_id']) && $edit_data[0]['blood_group_id'] == $row['blood_group_id']){
																$selected="selected='selected'";
															}
															?>
															<option value="<?php echo $row['blood_group_id'];?>" <?php echo $selected;?>><?php echo $row['blood_group_name'];?></option>
															<?php 
														} 
													?>
												</select>
											</div>
										</div> 

										<div class="row">	
											<div class="form-group col-md-3">
												<label class="col-form-label">Profile Image</label>
											</div>
											<div class="form-group col-md-6">
												<input type="file" name="profile_image" id="profile_image"class="form-control" placeholder="">
												<?php
													if($type=='edit')
													{
														if(file_exists("uploads/profile_image/".$id.'.png') )
														{
															?>
															<img class="img-responsive mt-2" alt="best dental hospial hosur" style="border-radius:4px;width:75px;height:75px;" src="<?php echo base_url(); ?>uploads/profile_image/<?php echo $id.'.png';?>">
															<?php 
														}
													}
												?>
											</div>
										</div>
									</div>
								</div>
								<div class="d-flexad" style="text-align:right;">
									<a href="<?php echo base_url(); ?>employee/ManageEmployee/grid_view" class="btn btn-light btn-sm">Close</a>
									<?php 
										if($type == "edit")
										{
											?>
											<button type="submit" name="save_only_update" class="btn btn-primary ml-1 btn-sm register-but">Save</button>
											<?php 
										}
										else
										{
											?>
											<input type="submit" name="save_only" value="Save" class="btn btn-primary ml-1 btn-sm register-but">
											<?php 
										}
									?>
								</div>
							</form>
							<?php 
						} 
						else if(isset($id) && $id == 'career-info')
						{
							?>
							<form action="" class="form-validate-jquery" --id="formValidation" enctype="multipart/form-data" method="post">
								<legend class="text-uppercase font-size-sm font-weight-bold">
									<div class="row">
										<div class="col-md-6">
											Career Information
										</div>
										<div class="col-md-6 text-right">
											<input type="submit" name="save_close" value="Save & Close" class="btn btn-primary ml-1 btn-sm register-but">
										</div>
									</div>
								</legend>
								<div class="row">
									<div class="col-md-6">
										<div class="row">
											<?php 
												$getOrganization = $this->db->query("select organization_name, organization_id from org_organizations where active_flag='Y' order by organization_name asc")->result_array();
											?>
											<div class="form-group col-md-3">
												<label class="col-form-label">Organization <span class="text-danger">*</span></label>
											</div>
											<div class="form-group col-md-6">
												<div class="">
													<a class="quicklink" target="_blank" href="<?php echo base_url()?>admin/ManageBranches/add"> Add Branch </a>
													<select name="organization_id" id="organization_id" required class="form-control searchDropdown">
														<option value="">- Select -</option>
														<?php 
															foreach($getOrganization as $row)
															{ 
																$selected="";
																if( isset($edit_data[0]['organization_id']) && $edit_data[0]['organization_id'] == $row['organization_id'])
																{
																	$selected="selected='selected'";
																}
																?>
																<option value="<?php echo $row['organization_id'];?>" <?php echo $selected;?>><?php echo ucfirst($row['organization_name']);?></option>
																<?php 
															} 
														?>
													</select>
												</div>
											</div>
										</div>
										
										<div class="row">
											<?php 
												$getBranch = $this->db->query("select branch_name, branch_id from branch where active_flag='Y' order by branch_name asc")->result_array();
											?>
											<div class="form-group col-md-3">
												<label class="col-form-label">Location <span class="text-danger">*</span></label>
											</div>
											<div class="form-group col-md-6">
												<div class="">
													<a class="quicklink" target="_blank" href="<?php echo base_url()?>admin/ManageBranches/add" title="Branch"> Add Branch </a>
													<select name="branch_id" id="branch_id" required class="form-control searchDropdown">
														<option value="">- Select -</option>
														<?php 
															foreach($getBranch as $row)
															{ 
																$selected="";
																if( isset($edit_data[0]['branch_id']) && $edit_data[0]['branch_id'] == $row['branch_id'])
																{
																	$selected="selected='selected'";
																}
																?>
																<option value="<?php echo $row['branch_id'];?>" <?php echo $selected;?>><?php echo ucfirst($row['branch_name']);?></option>
																<?php 
															} 
														?>
													</select>
												</div>
											</div>
										</div>
										<div class="row">
											<?php 
												$designation = $this->db->query("select designation_name,designation_id from emp_designations where designation_status=1")->result_array();
											?>				

											<div class="form-group col-md-3">
												<label class="col-form-label">Designation <span class="text-danger">*</span></label>
											</div>
											<div class="form-group col-md-6">
												<div class="">
													<a class="quicklink" target="_blank" href="<?php echo base_url()?>employee/ManageDesignation/add" title="Add Designation">Add Designation</a>
													<select name="designation_id" id="designation_id" required class="form-control searchDropdown">
														<option value="">- Select -</option>
														<?php 
															foreach($designation as $row)
															{
																$selected="";
																if(isset($edit_data[0]['designation_id']) && $edit_data[0]['designation_id'] == $row['designation_id']){
																	$selected="selected='selected'";
																}
																?>
																<option value="<?php echo $row['designation_id'];?>" <?php echo $selected;?>><?php echo $row['designation_name'];?></option>
																<?php 
															} 
														?>
													</select>
												</div>
											</div>
										</div>
										<div class="row">
											<?php 
												#$getPositions = $this->db->query("select position_name,position_id from hr_positions where position_status=1")->result_array();
											?>
											<?php /* <div class="form-group col-md-3">
												<label class="col-form-label">Position<span class="text-danger">*</span></label>
												<a class="quicklink" target="_blank" href="<?php echo base_url()?>employee/ManagePositions/add" title="Add Position">Add Position</a>
												<select name="position_id" id="position_id" class="form-control searchDropdown">
													<option value="">- Select Position -</option>
													<?php
														foreach($getPositions as $row)
														{
															$selected="";
															if(isset($edit_data[0]['position_id'])&& $edit_data[0]['position_id'] == $row['position_id'])
															{
																$selected="selected='selected'";
															}
															?>
																<option value="<?php echo $row['position_id'];?>" <?php echo $selected; ?>> <?php echo $row['position_name'];?></option>
															<?php
														}
													?>
												</select>
											</div> */ ?>
											
											<?php 
												$getDepartment = $this->db->query("select department_name,department_id from emp_departments where department_status=1")->result_array();
											?>
											<div class="form-group col-md-3">
												<label class="col-form-label">Department <span class="text-danger">*</span></label>
											</div>
											<div class="form-group col-md-6">
												<div class="">
													<a class="quicklink" target="_blank" href="<?php echo base_url()?>employee/ManageDepartment/add" title="Add Department">Add Department</a>
													<select name="department_id" id="department_id" required class="form-control searchDropdown">
														<option value="">- Select -</option>
														<?php
															foreach($getDepartment as $row)
															{
																$selected="";
																if(isset($edit_data[0]['department_id'])&& $edit_data[0]['department_id'] == $row['department_id'])
																{
																	$selected="selected='selected'";
																}
																?>
																	<option value="<?php echo $row['department_id'];?>" <?php echo $selected;?>> <?php echo $row['department_name'];?></option>
																<?php
															}
														?>
													</select>
												</div>
											</div>
										</div>
										<div class="row">
											<div class="form-group col-md-3">
												<label class="col-form-label">Date of Joining <span class="text-danger">*</span></label>
											</div>
											<div class="form-group col-md-6">	
												<input type="text" name="date_of_joining" id="date_of_joining" required class="form-control default_date" autocomplete="off" value="<?php echo isset($edit_data[0]['date_of_joining']) ? $edit_data[0]['date_of_joining'] :"";?>" placeholder="">
											</div>
										</div>
										<div class="row">
											<div class="form-group col-md-3">
												<label class="col-form-label">Date of Releaving</label>
											</div>
											<div class="form-group col-md-4">
												<div class="">
													<input type="text" name="date_of_releaving" id="date_of_leaving" autocomplete="off" class="form-control default_date" value="<?php echo isset($edit_data[0]['date_of_releaving']) ? $edit_data[0]['date_of_releaving'] :"";?>" placeholder="">
												</div>
											</div>
										</div>
										
									</div>

									<div class="col-md-6">
										
										<div class="row">
											<div class="form-group col-md-3">
												<label class="col-form-label">Previous Experience</label>
											</div>
											<div class="form-group col-md-6">	
											<input type="text" name="previous_experience" id="previous_experience" autocomplete="off" class="form-control mobile_vali" value="<?php echo isset($edit_data[0]['previous_experience']) ? $edit_data[0]['previous_experience'] :"";?>" placeholder="">
											</div>
										</div>
										<div class="row">
											<div class="form-group col-md-3">
												<label class="col-form-label">Rate Per Day</label>
											</div>
											<div class="form-group col-md-6">
												<div class="">
													<input type="text" name="rate_per_day" id="rate_per_day" autocomplete="off" class="form-control mobile_vali" value="<?php echo isset($edit_data[0]['rate_per_day']) ? $edit_data[0]['rate_per_day'] :"";?>" placeholder="">
												</div>
											</div>
										</div>
										<div class="row">
											<div class="form-group col-md-3">
												<label class="col-form-label">Rate Per Hour</label>
											</div>
											<div class="form-group col-md-6">
												<div class="">
													<input type="text" name="rate_per_day" id="rate_per_day" autocomplete="off" class="form-control mobile_vali" value="<?php echo isset($edit_data[0]['rate_per_day']) ? $edit_data[0]['rate_per_day'] :"";?>" placeholder="">
												</div>
											</div>
										</div>
										<div class="row">
											<div class="form-group col-md-3">
												<label class="col-form-label">Pay frequency</label>
											</div>
											<div class="form-group col-md-6">
												<select name="pay_frequency" id="pay_frequency" class="form-control searchDropdown" > <!--selectboxit-->
													<option value="">- Select -</option>
													<?php 
														foreach($this->pay_frequency as $key=>$value)
														{
															$selected = "";
															if( isset($edit_data[0]['pay_frequency']) && $edit_data[0]['pay_frequency'] == $key)
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
								</div>
								<div class="d-flexad" style="text-align:right;">
									<a href="<?php echo base_url(); ?>employee/ManageEmployee/<?php echo $type;?>/basic-info" class="btn btn-light btn-sm">Back</a>
									<?php 
										if($type == "edit")
										{
											?>
											<button type="submit" name="save_only_update" class="btn btn-primary ml-1 btn-sm register-but">Update</button>
											<?php 
										}
										else
										{
											?>
											<input type="submit" name="save_only" value="Save" class="btn btn-primary ml-1 btn-sm register-but">
											<?php 
										}
									?>
								</div>
								
								
							</form>
							<?php
						}
						else if(isset($id) && $id == 'id-info')
						{
							?>
							<form action="" class="form-validate-jquery" --id="formValidation" enctype="multipart/form-data" method="post">
								<legend class="text-uppercase font-size-sm font-weight-bold">
									<div class="row">
										<div class="col-md-6">
											ID Information
										</div>
										<div class="col-md-6 text-right">
											<input type="submit" name="save_close" value="Save & Close" class="btn btn-primary ml-1 btn-sm register-but">
										</div>
									</div>
								</legend>
								<div class="row">
									<div class="col-md-6">
										<div class="row">	
											<div class="form-group col-md-3">
												<label class="col-form-label">Aadhar No <span class="text-danger">*</span></label>
											</div>
											<div class="form-group col-md-6">
												<input type="text" name="aadhaar_number" minlength="12" maxlength="12" required class="form-control" autocomplete="off" value="<?php echo isset($edit_data[0]['aadhaar_number']) ? $edit_data[0]['aadhaar_number'] :"";?>" placeholder="">
												<span class="small" id="aadhaar_number_val" style="color:#a19f9f;float:left;width:100%;">(Ex : 489118465046)</span>
											</div>
										</div>
										<div class="row">
											<div class="form-group col-md-3">
												<label class="col-form-label">PAN Number <span class="text-danger">*</span></label>
											</div>
											<div class="form-group col-md-6">
												<input type="text" name="pan_number" maxlength="10" id="textPanNo" -onblur="ValidatePAN(this);" required autocomplete="off" <?php echo $this->validation; ?> class="form-control" value="<?php echo isset($edit_data[0]['pan_number']) ? $edit_data[0]['pan_number'] :"";?>" placeholder="">
												<span class="small" id="pan_number_val" style="color:#a19f9f;float:left;width:100%;">(Ex : ABCDE1234F)</span>
											</div>
										</div>		
										<div class="row">						
											<div class="form-group col-md-3">
												<label class="col-form-label">Driving Licence </label>
											</div>
											<div class="form-group col-md-6">
												<input type="text" name="driving_licence" id="driving_licence" --maxlength="17" --oninput="LicenceNumber(this)" class="form-control" autocomplete="off" value="<?php echo isset($edit_data[0]['driving_licence']) ? $edit_data[0]['driving_licence'] :"";?>" placeholder="">
												<span id="licence_number_val" class="small" style="color:#a19f9f;">(Ex : TN-0619850034761 )</span>
											</div>
										</div>
										<div class="row">						
											<div class="form-group col-md-3">
												<label class="col-form-label">Passport No </label>
											</div>
											<div class="form-group col-md-6">
												<input type="text" name="passport_number" id="passport_number" minlength="8" maxlength="10" data-type="passport-number" <?php echo $this->validation; ?> class="form-control" autocomplete="off" value="<?php echo isset($edit_data[0]['passport_number']) ? $edit_data[0]['passport_number'] :"";?>" placeholder="">
												<span id="passport_number_error" class="small" style="color:#a19f9f;">(Ex : A1234567)</span>
											</div>
										</div>
										<div class="row">						
											<div class="form-group col-md-3">
												<label class="col-form-label">Passport Issue Date</label>
											</div>
											<div class="form-group col-md-6">
												<input type="text" name="passport_issue_date" id="passport_issue_date" class="form-control default_date" autocomplete="off" value="" placeholder="">
											</div>
										</div>

									</div>

									<div class="col-md-6">	
										<div class="row">
											<div class="form-group col-md-3">
												<label class="col-form-label">Passport Expiry Date</label>
											</div>
											<div class="form-group col-md-6">
												<input type="text" name="passport_exp_date" id="passport_exp_date" class="form-control default_date" autocomplete="off" value="" placeholder="">
											</div>
										</div>
										<div class="row">											
											<div class="form-group col-md-3">
												<label class="col-form-label">PF No <span class="text-danger">*</span></label>
											</div>
											<div class="form-group col-md-6">
												<input type="text" name="pf_number" id="pf_number" required autocomplete="off" class="form-control single_quotes" value="<?php echo isset($edit_data[0]['pf_number']) ? $edit_data[0]['pf_number'] :"";?>" placeholder="">
											</div>
										</div>
										<div class="row">
											<div class="form-group col-md-3">
												<label class="col-form-label">ESI No.</label>
											</div>
											<div class="form-group col-md-6">
												<input type="text" name="esi_number" id="esi_number" autocomplete="off" class="form-control single_quotes" value="<?php echo isset($edit_data[0]['esi_number']) ? $edit_data[0]['esi_number'] :"";?>" placeholder="">
											</div>
										</div>
										<div class="row">
											<div class="form-group col-md-3">
												<label class="col-form-label">UAN No.</label>
											</div>
											<div class="form-group col-md-6">
												<input type="text" name="uan_number" id="uan_number" autocomplete="off" class="form-control single_quotes" value="<?php echo isset($edit_data[0]['uan_number']) ? $edit_data[0]['uan_number'] :"";?>" placeholder="">
											</div>
										</div>
									</div>
								
								</div>
								<div class="d-flexad" style="text-align:right;">
									<a href="<?php echo base_url(); ?>employee/ManageEmployee/<?php echo $type;?>/career-info/<?php echo $status;?>" class="btn btn-light btn-sm">Back</a>
									<?php 
										if($type == "edit")
										{
											?>
											<button type="submit" name="save_only_update" class="btn btn-primary ml-1 btn-sm register-but">Update</button>
											<?php 
										}
										else
										{
											?>
											<input type="submit" name="save_only" value="Save" class="btn btn-primary ml-1 btn-sm register-but">
											<?php 
										}
									?>
								</div>
							</form>
							<script type="text/javascript">
								$('[data-type="adhaar-number"]').keyup(function() 
								{
									var value = $(this).val();
									value = value.replace(/\D/g, "").split(/(?:([\d]{4}))/g).filter(s => s.length > 0).join("-");
									$(this).val(value);

									aadhaar_number_val = value;
									$.ajax({
										url: '<?php echo base_url();?>employee/aadhaarUnique',
										type: 'post',
										data: {
											'aadhaar_number' : aadhaar_number_val,
											'type'		 : '<?php echo $type ?>',
											<?php
												if ($type == "edit") 
												{
													?>
													'id' : '<?php echo $status; ?>'
													<?php
												}
											?>
										},
										success: function(response)
										{
											if (response == 'already_taken') 
											{
												$('#aadhaar_number_val').html('Aadhaar Number Alredy Taken');
												// Obj.focus();
												$('.register-but').prop('disabled',true);
												return false;
											}else
											{
												$('#aadhaar_number_val').html('(Ex : 4891-1846-5046)');
												$('.register-but').prop('disabled',false);
												return true;
											}
										}
									});
								});

								function ValidatePAN() 
								{ 
									var Obj = document.getElementById("textPanNo");
									if (Obj.value != "") 
									{
										ObjVal = Obj.value;
										var panPat = /^([a-zA-Z]{5})(\d{4})([a-zA-Z]{1})$/;
										if (ObjVal.search(panPat) == -1) 
										{
											alert("Please Enter Valid PAN NO.");
											$('#textPanNo').val('');
											//Obj.focus();
											return false;
										}
										else
										{
											pan_number = $('#textPanNo').val();
											$.ajax({
												url: '<?php echo base_url();?>employee/panUnique',
												type: 'post',
												data: {
													'pan_number' : pan_number,
													'type'		 : '<?php echo $type ?>',
													<?php
														if ($type == "edit") 
														{
															?>
															'id'  : '<?php echo $status; ?>'
															<?php
														}
													?>
												},
												success: function(response)
												{
													if (response == 'already_taken') 
													{
														$('#pan_number_val').html('PAN Number Alredy Taken');
														//Obj.focus();
														$('.register-but').prop('disabled',true);
														return false;
													}
													else
													{
														$('#pan_number_val').html('(Ex : ABCDE1234F)');
														$('.register-but').prop('disabled',false);
														return true;
													}
												}
											});

										}
									}
									else
									{
										$('#pan_number_val').html('(Ex : ABCDE1234F)');
									}
								} 
					
								$('#mobile_number').blur(function () 
								{
									mobile_number = $(this).val();

									$.ajax({
										url: '<?php echo base_url();?>vendor/MobileExist',
										type: 'post',
										data: {
											'mobile_number' : mobile_number,
											<?php
												if ($type == "edit") 
												{
													?>
														'id' : '<?php echo $id ?>'
													<?php
												}
											?>
										},
										success: function(response)
										{
											if (response == 'taken') 
											{
												$('.mobile_number_exist').html('Mobile Number Alredy Taken');
												$('.register-but').prop('disabled',true);
												return false;
											}else
											{
												$('.mobile_number_exist').html('');
												$('.register-but').prop('disabled',false);
												return true;
											}
										}
									});
								});

								$('#email').blur(function () 
								{
									email = $(this).val();

									$.ajax({
										url: '<?php echo base_url();?>employee/EmailExist',
										type: 'post',
										data: {
											'email' : email,
											<?php
												if ($type == "edit") 
												{
													?>
													'id': '<?php echo $id ?>'
													<?php
												}
											?>
										},
										success: function(response)
										{
											if (response == 'taken') 
											{
												$('.employee_email_exist_error').html('Email Alredy Taken');
												$('.register-but').prop('disabled',true);
												$(this).focus();
												return false;
											}else
											{
												$('.employee_email_exist_error').html('');
												$('.register-but').prop('disabled',false);
												return true;
											}
										}
									});
								});
								
								$('[data-type="passport-number"]').keyup(function() 
								{
									var passport = $(this).val();
									if (passport == "") {
										return;
									}
									var regsaid = /[a-zA-Z]{1}[0-9]{6}/;
	
									if(regsaid.test(passport) == false)
									{
										document.getElementById("passport_number_error").innerHTML = "Passport is valid.";
										$('.register-but').prop('disabled',true);
									}
									else
									{
										document.getElementById("passport_number_error").innerHTML = "";
										$('.register-but').prop('disabled',false);
									}

								});
							</script>
							<?php
						}
						else if(isset($id) && $id == 'address-info')
						{
							?>
							<form action="" class="form-validate-jquery" --id="formValidation" enctype="multipart/form-data" method="post">
								<legend class="text-uppercase font-size-sm font-weight-bold">
									<div class="row">
										<div class="col-md-6">
											Address Information
										</div>
										<div class="col-md-6 text-right">
											<input type="submit" name="save_close" value="Save & Close" class="btn btn-primary ml-1 btn-sm register-but">
										</div>
									</div>
								</legend>
								
									<?php
										$country = $this->db->get_where('country', array('country_status' => '1'))->result_array();
									?>
									<script type="text/javascript">  
										function selectState(val)
										{
											if(val !='')
											{
												$.ajax({
												type: "POST",
												url:"<?php echo base_url().'admin/ajaxSelectState';?>",
												data: { id: val }
												}).done(function( msg ) {   
													$("#state_id").html(msg);
													//$("#permenant_state_id").html(msg);
												});
											}
											else 
											{ 
												alert("No State under this Country!");
											}
										}
										
										function selectDistrict(val)
										{
											if(val !='')
											{
												$.ajax({
												type: "POST",
												url:"<?php echo base_url().'admin/ajaxSelectDistrict';?>",
												data: { id: val }
												}).done(function( msg ) {  
													$( "#district_id").html(msg);
													//$( "#permenant_district_id").html(msg);
												});
											}
											else 
											{ 
												alert("No districts under this state!");
											}
										}
										
										function selectCityorTown(val)
										{
											if(val !='')
											{
												$.ajax({
												type: "POST",
												url:"<?php echo base_url().'admin/ajaxSelectCity';?>",
												data: { id: val }
												}).done(function( msg ) {
													$( "#city_id").html(msg);
													//$( "#permenant_city_id").html(msg);
												});
											}
											else 
											{ 
												alert("No city/town's under this district!");
											}
										}

										function currentselectState(val)
										{
											if(val !='')
											{
												$.ajax({
												type: "POST",
												url:"<?php echo base_url().'admin/ajaxSelectState';?>",
												data: { id: val }
												}).done(function( msg ) {   
													//$("#state_id").html(msg);
													$("#permenant_state_id").html(msg);
												});
											}
											else 
											{ 
												alert("No State under this Country!");
											}
										}
										
										function currentselectDistrict(val)
										{
											if(val !='')
											{
												$.ajax({
												type: "POST",
												url:"<?php echo base_url().'admin/ajaxSelectDistrict';?>",
												data: { id: val }
												}).done(function( msg ) {  
													//$( "#district_id").html(msg);
													$( "#permenant_district_id").html(msg);
												});
											}
											else 
											{ 
												alert("No districts under this state!");
											}
										}
										
										function currentselectCityorTown(val)
										{
											if(val !='')
											{
												$.ajax({
												type: "POST",
												url:"<?php echo base_url().'admin/ajaxSelectCity';?>",
												data: { id: val }
												}).done(function( msg ) {
													//$( "#city_id").html(msg);
													$( "#permenant_city_id").html(msg);
												});
											}
											else 
											{ 
												alert("No city/town's under this district!");
											}
										}
									</script>
									
									<div class="row">
										<!-- Current Address start-->
										<div class="col-md-6">
											<span>Current Address</span>

											<div class="row mt-3">
												<div class="form-group col-md-3">
													<label class="col-form-label">Address 1 <span class="text-danger">*</span></label>
												</div>
												<div class="form-group col-md-6">
													<textarea name="address" rows="1" id="address1" class="form-control" autocomplete="off"><?php echo isset($edit_data[0]['address1']) ? $edit_data[0]['address1'] :"";?></textarea>
												</div>
											</div>

											<div class="row">
												<div class="form-group col-md-3">
													<label class="col-form-label">Address 2</label>
												</div>
												<div class="form-group col-md-6">
													<textarea name="address2" rows="1" id="address2" class="form-control" autocomplete="off"><?php echo isset($edit_data[0]['address2']) ? $edit_data[0]['address2'] :"";?></textarea>
												</div>
											</div>

											<div class="row">
												<div class="form-group col-md-3">
													<label class="col-form-label">Address 3</label>
												</div>
												<div class="form-group col-md-6">
													<textarea name="address3" rows="1" id="address3" class="form-control" autocomplete="off"><?php echo isset($edit_data[0]['address3']) ? $edit_data[0]['address'] :"";?></textarea>
												</div>
											</div>

											<div class="row">
												<div class="form-group col-md-3">
													<label class="col-form-label">Country <span class="text-danger">*</span></label>
												</div>
												<div class="form-group col-md-6">
													<select name="country_id" id="country_id" required onchange="selectState(this.value);" class="form-control searchDropdown"> <!--selectboxit-->
														<option value="">- Select Country -</option>
														<?php 
															foreach($country as $row)
															{
																$selected="";
																if(isset($edit_data[0]['country_id']) && $edit_data[0]['country_id'] == $row['country_id']){
																	$selected="selected='selected'";
																}
																?>
																<option value="<?php echo $row['country_id'];?>" <?php echo $selected;?>><?php echo $row['country_name'];?></option>
																<?php 
															} 
														?>
													</select>
												</div>
											</div>

											<div class="row">
												<div class="form-group col-md-3">
													<label class="col-form-label">State <span class="text-danger">*</span></label>
												</div>
												<div class="form-group col-md-6">
													<select name="state_id" id="state_id" required onchange="selectCityorTown(this.value);" class="form-control searchDropdown"> <!--selectboxit-->
														<option value="">- First Select Country -</option>
														<?php 
															if($type == "edit" || $type == "add"  )
															{
																$country_id = isset( $edit_data[0]['country_id'] ) ?  $edit_data[0]['country_id'] : 0;
																$state = $this->db->get_where('state', array('state_status' => '1','country_id' => $country_id))->result_array();
												
																foreach($state as $row)
																{
																	$selected="";
																	if(isset($edit_data[0]['state_id']) && $edit_data[0]['state_id'] == $row['state_id']){
																		$selected="selected='selected'";
																	}
																	?>
																	<option value="<?php echo $row['state_id'];?>" <?php echo $selected;?>><?php echo $row['state_name'];?></option>
																	<?php 
																}
															}
														?>
													</select>
												</div>
											</div>
											
											<div class="row">
												<div class="form-group col-md-3">
													<label class="col-form-label">City <span class="text-danger">*</span></label>
												</div>
												<div class="form-group col-md-6">
													<select name="city_id" id="city_id" required --onchange="selectCityorTown(this.value);" class="form-control searchDropdown" > <!--selectboxit-->
														<option value="">- First Select State -</option>
														<?php 
															if( $type == "edit" || $type == "add" )
															{
																$state_id = isset( $edit_data[0]['state_id'] ) ?  $edit_data[0]['state_id'] : 0;

																$city = $this->db->get_where('city', array('city_status' => '1','state_id' => $state_id))->result_array();
												
																foreach($city as $row)
																{
																	$selected="";
																	if( isset($edit_data[0]['city_id']) && $edit_data[0]['city_id'] == $row['city_id'])
																	{
																		$selected="selected='selected'";
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
													<label class="col-form-label">Postal Code <span class="text-danger">*</span></label>
												</div>
												<div class="form-group col-md-6">
													<input type="text" minlength="6" maxlength="6" name="postal_code" id="postal_code" required autocomplete="off" class="form-control mobile_vali" value="<?php echo isset($edit_data[0]['postal_code']) ? $edit_data[0]['postal_code'] :"";?>" placeholder="">
												</div>
											</div>
										</div>
										<!-- Current Address end -->

										<script>
											$(document).ready(function()
											{
												$('input[name="chk_shipping_address"]').click(function()
												{
													if( $(this).prop("checked") == true ) //checked
													{
														$('#permenant_address1').val( $('#address1').val() );
														$('#permenant_address2').val( $('#address2').val() );
														$('#permenant_address3').val( $('#address3').val() );
														$('#permenant_postal_code').val( $('#postal_code').val() );
														
														var company_country1 = $('select#country_id option:selected').sort().clone();
														$('select#permenant_country_id').append( company_country1 );
														
														var country_id = $('#country_id').val();
														var permenant_country_id = $('#permenant_country_id').val();
														
														var state_id = $('#state_id').val();
														var company_state_id = $('select#state_id option:selected').sort().clone();
														$('select#permenant_state_id').append( company_state_id );
														
														var district_id = $('#district_id').val();
														var company_district_id = $('select#district_id option:selected').sort().clone();
														$('select#permenant_district_id').append( company_district_id );

														var city_id = $('#city_id').val();
														var company_city_id = $('select#city_id option:selected').sort().clone();
														$('select#permenant_city_id').append( company_city_id );
														
														if(country_id == permenant_country_id);
														{
															$('select#permenant_country_id option[value='+country_id+']').attr('selected','selected');
														}
														
														if(state_id !='');
														{
															$('select#permenant_state_id option[value='+state_id+']').attr('selected','selected');
														}
														
														if(district_id !='');
														{
															$('select#permenant_district_id option[value='+district_id+']').attr('selected','selected');
														}
														if(city_id !='');
														{
															$('select#permenant_city_id option[value='+city_id+']').attr('selected','selected');
														}
													}
													else if( $(this).prop("checked") == false ) //Unchecked
													{
														var permenant_country_id = $('#permenant_country_id').val();
														$("select#permenant_country_id option[value='"+permenant_country_id+"']:last").remove();
														
														$( "#permenant_state_id").html('<option value="">- First Select Country -</option>');
														$( "#permenant_district_id").html('<option value="">- First Select District -</option>');
														$( "#permenant_city_id").html('<option value="">- First Select State -</option>');
														
														$('#permenant_address').val('');
														$('#permenant_postal_code').val('');
														$('#permenant_address1').val('');
														$('#permenant_address2').val('');
														$('#permenant_address3').val('');
													}
												});
											});
										</script>
									
										<!-- Permanent Address start-->
										<div class="col-md-6">
											<div --class="new-design-2">
												<span>Permanent Address
												<?php 
													$checked_shipping_address ="";
													$addressReadonly = '';
													if( isset($edit_data[0]['chk_shipping_address']) && $edit_data[0]['chk_shipping_address'] == 1 )
													{
														$checked_shipping_address ="checked='checked'";
														$addressReadonly = 'readonly';
													} 
												?>
												&nbsp; &nbsp; <input type="checkbox" name="chk_shipping_address" value='1' id="chk_shipping_address" <?php echo $checked_shipping_address;?>>&nbsp; &nbsp; <span style="color:#c7c7ce;font-size: 11px;">Copy Current Address</span></span>
											</div> 
											<div class="row mt-3">
												<div class="form-group col-md-3">
													<label class="col-form-label">Address 1</label>
												</div>
												<div class="form-group col-md-6">
													<textarea name="permenant_address1" <?php echo $addressReadonly;?> rows="1" id="permenant_address1" class="form-control" autocomplete="off"><?php echo isset($edit_data[0]['permenant_address']) ? $edit_data[0]['permenant_address'] :"";?></textarea>
												</div>
											</div>
											<div class="row">
												<div class="form-group col-md-3">
													<label class="col-form-label">Address 2</label>
												</div>
												<div class="form-group col-md-6">
													<textarea name="permenant_address2" rows="1" id="permenant_address2"  class="form-control" autocomplete="off"><?php echo isset($edit_data[0]['permenant_address']) ? $edit_data[0]['permenant_address'] :"";?></textarea>
												</div>
											</div>
											<div class="row">
												<div class="form-group col-md-3">
													<label class="col-form-label">Address 3</label>
												</div>
												<div class="form-group col-md-6">
													<textarea name="permenant_address3" rows="1" id="permenant_address3"  class="form-control" autocomplete="off"><?php echo isset($edit_data[0]['permenant_address']) ? $edit_data[0]['permenant_address'] :"";?></textarea>
												</div>
											</div>
											<div class="row">
												<div class="form-group col-md-3">
													<label class="col-form-label">Country </label>
												</div>
												<div class="form-group col-md-6">
													<select name="permenant_country_id" id="permenant_country_id" onchange="currentselectState(this.value);" class="form-control searchDropdown"> <!--selectboxit-->
														<option value="">- Select Country -</option>
														<?php 
															foreach($country as $row)
															{
																$selected="";
																if(isset($edit_data[0]['permenant_country_id']) && $edit_data[0]['permenant_country_id'] == $row['country_id']){
																	$selected="selected='selected'";
																}
																?>
																<option value="<?php echo $row['country_id'];?>" <?php echo $selected;?>><?php echo $row['country_name'];?></option>
																<?php 
															} 
														?>
													</select>
												</div>
											</div>
											<div class="row">
												<div class="form-group col-md-3">
													<label class="col-form-label">State </label>
												</div>
												<div class="form-group col-md-6">
													<select name="permenant_state_id" id="permenant_state_id" onchange="currentselectCityorTown(this.value);" class="form-control searchDropdown"> <!--selectboxit-->
														<option value="">- First Select Country -</option>
														<?php 
															if($type == "edit" || $type == "add")
															{
																$country_id = isset($edit_data[0]['country_id']) ? $edit_data[0]['country_id'] : 0;
																$state = $this->db->get_where('state', array('state_status' => '1','country_id' => $country_id))->result_array();
												
																foreach($state as $row)
																{
																	$selected="";
																	if(isset($edit_data[0]['permenant_state_id']) && $edit_data[0]['permenant_state_id'] == $row['state_id']){
																		$selected="selected='selected'";
																	}
																	?>
																	<option value="<?php echo $row['state_id'];?>" <?php echo $selected;?>><?php echo $row['state_name'];?></option>
																	<?php 
																}
															}
														?>
													</select>
												</div>
											</div>
											<?php /*
											<div class="row">
												<div class="form-group col-md-3">
													<label class="col-form-label">District </label>
												</div>
												<div class="form-group col-md-6">
													<select name="permenant_district_id" id="permenant_district_id" onchange="selectCityorTown(this.value);" class="form-control searchDropdown" > <!--selectboxit-->
														<option value="">- First Select District -</option>
														<?php 
															if($type == "add" || $type == "edit")
															{
																$state_id = isset($edit_data[0]['state_id']) ? $edit_data[0]['state_id'] : 0;
																$district = $this->db->get_where('district', array('district_status' => '1','state_id' => $state_id))->result_array();
																
																foreach($district as $row)
																{
																	$selected="";
																	if( isset($edit_data[0]['permenant_district_id']) && $edit_data[0]['permenant_district_id'] == $row['district_id'])
																	{
																		$selected="selected='selected'";
																	}
																	?>
																	<option value="<?php echo $row['district_id'];?>" <?php echo $selected;?>><?php echo ucfirst($row['district_name']);?></option>
																	<?php 
																}
															}
														?>
													</select>
												</div>
											</div>
											*/ ?>
											<div class="row">
												<div class="form-group col-md-3">
													<label class="col-form-label">City </label>
												</div>
												<div class="form-group col-md-6">
													<select name="permenant_city_id" id="permenant_city_id" --onchange="selectCityorTown(this.value);" class="form-control searchDropdown" > <!--selectboxit-->
														<option value="">- First Select State -</option>
														<?php 
															if($type == "add" || $type == "edit")
															{
																$state_id = isset($edit_data[0]['state_id']) ? $edit_data[0]['state_id'] : 0;
																
																$city = $this->db->get_where('city', array('city_status' => '1','state_id' => $state_id))->result_array();
												
																foreach($city as $row)
																{
																	$selected="";
																	if( isset($edit_data[0]['permenant_city_id']) && $edit_data[0]['permenant_city_id'] == $row['city_id'])
																	{
																		$selected="selected='selected'";
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
													<label class="col-form-label">Postal Code</label>
												</div>
												<div class="form-group col-md-6">
													<input type="text" minlength="6" maxlength="6" name="permenant_postal_code" id="permenant_postal_code" autocomplete="off" class="form-control mobile_vali" value="<?php echo isset($edit_data[0]['permenant_postal_code']) ? $edit_data[0]['permenant_postal_code'] :"";?>" placeholder="">
												</div>
											</div>
										</div>
										<!-- Permanent Address end-->
									</div>
									
									

									<input type="hidden" name="address1" id="address1" autocomplete="off" class="form-control mobile_vali" value="<?php echo isset($edit_data[0]['address1']) ? $edit_data[0]['address1'] :"";?>" placeholder="">
									<?php /* <div class="form-group col-md-3">
										<label class="col-form-label">Permenant Address</label>
										<textarea name="address1" rows="1" id="address1"  class="form-control" autocomplete="off"><?php echo isset($edit_data[0]['address1']) ? $edit_data[0]['address1'] :"";?></textarea>
									</div> */ ?>
									
								

								<div class="d-flexad" style="text-align:right;">
									<a href="<?php echo base_url(); ?>employee/ManageEmployee/<?php echo $type;?>/id-info/<?php echo $status;?>" class="btn btn-light btn-sm">Back</a>
									<?php 
										if($type == "edit")
										{
											?>
											<button type="submit" name="save_only_update" class="btn btn-primary ml-1 btn-sm register-but">Update</button>
											<?php 
										}
										else
										{
											?>
											<input type="submit" name="save_only" value="Save" class="btn btn-primary ml-1 btn-sm register-but">
											<?php 
										}
									?>
								</div>
							</form>
							<?php
						}
						else if(isset($id) && $id == 'bank-info')
						{
							?>
							<form action="" class="form-validate-jquery" --id="formValidation" enctype="multipart/form-data" method="post">
								<legend class="text-uppercase font-size-sm font-weight-bold">
									<div class="row">
										<div class="col-md-6">
											Add Bank Detail
										</div>
										<div class="col-md-6 text-right">
											<input type="submit" name="save_close" value="Save & Close" class="btn btn-primary ml-1 btn-sm register-but">
										</div>
									</div>
								</legend>
								<div class="row">
									<div class="col-md-6">
										<div class="row">	
											<div class="form-group col-md-4">
												<label class="col-form-label">Account No <span class="text-danger">*</span></label>
											</div>
											<div class="form-group col-md-6">
												<input type="text" name="account_number" data-type="account_number" --maxlength="14" class="form-control" required autocomplete="off" value="<?php echo isset($edit_data[0]['account_number']) ? $edit_data[0]['account_number'] :"";?>" placeholder="">
											</div>
										</div>	
										<div class="row">
											<div class="form-group col-md-4">
												<label class="col-form-label">Account Holder Name <span class="text-danger">*</span></label>
											</div>
											<div class="form-group col-md-6">
												<input type="text" name="account_name" data-type="account_name" --maxlength="14" class="form-control" required autocomplete="off" value="<?php echo isset($edit_data[0]['account_name']) ? $edit_data[0]['account_name'] :"";?>" placeholder="">
											</div>
										</div>
										<div class="row">
											<div class="form-group col-md-4">
												<label class="col-form-label">Bank Name <span class="text-danger">*</span></label>
											</div>
											<div class="form-group col-md-6">
												<input type="text" name="bank_name" id="bank_name" autocomplete="off" <?php echo $this->validation; ?> required class="form-control" value="<?php echo isset($edit_data[0]['bank_name']) ? $edit_data[0]['bank_name'] :"";?>" placeholder="">
											</div>
										</div>

										<div class="row">
											<div class="form-group col-md-4">
												<label class="col-form-label">Bank Branch </label>
											</div>
											<div class="form-group col-md-6">
												<input type="text" name="branch_name" id="branch_name" --data-type="passport-number" <?php echo $this->validation; ?> class="form-control" autocomplete="off" value="<?php echo isset($edit_data[0]['branch_name']) ? $edit_data[0]['branch_name'] :"";?>" placeholder="">
											</div>
										</div>

										<div class="row">
											<div class="form-group col-md-4">
												<label class="col-form-label">IFSC Code <span class="text-danger">*</span></label>
											</div>
											<div class="form-group col-md-6">
												<input type="text" name="ifsc_code" id="ifsc_code" minlength="11" maxlength="11" required class="form-control dev_name" autocomplete="off" value="<?php echo isset($edit_data[0]['ifsc_code']) ? $edit_data[0]['ifsc_code'] :"";?>" placeholder="">
												<span id="ifsc_code_val" class="small" style="color:#a19f9f;float:left;width:100%;">(Ex : IDIB000A114)</span>
											</div>
										</div>
										

										<div class="row">
											<div class="form-group col-md-4">
												<label class="col-form-label">MICR Code </label>
											</div>
											<div class="form-group col-md-6">
												<input type="text" name="micr_code" id="micr_code" maxlength="10" class="form-control" autocomplete="off" value="<?php echo isset($edit_data[0]['micr_code']) ? $edit_data[0]['micr_code'] :"";?>" placeholder="">
												<span class="small" id="micr_code_val" style="color:#a19f9f;">(Ex : 600019003)</span>
											</div>
										</div>

										<div class="row">
											<div class="form-group col-md-4">
												<label class="col-form-label">Address</label>
											</div>
											<div class="form-group col-md-6">
												<textarea name="address" id="address" class="form-control" value=""><?php echo isset($edit_data[0]['address']) ? $edit_data[0]['address'] :"";?></textarea>
											</div>
										</div>
									</div>
								</div>

								<div class="d-flexad" style="text-align:right;">
									<a href="<?php echo base_url(); ?>employee/ManageEmployee/grid_view" class="btn btn-light btn-sm">Cancel</a>
									<a href="<?php echo base_url(); ?>employee/ManageEmployee/<?php echo $type;?>/address-info/<?php echo $status;?>" class="btn btn-light btn-sm">Back</a>
									
									<?php 
										if($type == "edit")
										{
											?>
											<button type="submit" name="save_only_update" class="btn btn-primary ml-1 btn-sm register-but">Update</button>
											<?php 
										}
										else
										{
											?>
											<input type="submit" name="save_only" value="Save" class="btn btn-primary ml-1 btn-sm register-but">
											<?php 
										}
									?>
								</div>
							</form>
							<script type="text/javascript">
							/*
								$('[data-type="adhaar-number"]').keyup(function() 
								{
									var value = $(this).val();
									value = value.replace(/\D/g, "").split(/(?:([\d]{4}))/g).filter(s => s.length > 0).join("-");
									$(this).val(value);

									aadhaar_number_val = value;
									$.ajax({
										url: '<?php echo base_url();?>employee/aadhaarUnique',
										type: 'post',
										data: {
											'account_number' : account_number_val,
											'type'		 : '<?php echo $type ?>',
											<?php
												if ($type == "edit") 
												{
													?>
													'id' : '<?php echo $id ?>'
													<?php
												}
											?>
										},
										success: function(response)
										{
											if (response == 'already_taken') 
											{
												$('#aadhaar_number_val').html('Aadhaar Number Alredy Taken');
												// Obj.focus();
												$('.register-but').prop('disabled',true);
												return false;
											}else
											{
												$('#aadhaar_number_val').html('(Ex : 4891-1846-5046)');
												$('.register-but').prop('disabled',false);
												return true;
											}
										}
									});
								});

								function ValidatePAN() 
								{ 
									var Obj = document.getElementById("textPanNo");
									if (Obj.value != "") 
									{
										ObjVal = Obj.value;
										var panPat = /^([a-zA-Z]{5})(\d{4})([a-zA-Z]{1})$/;
										if (ObjVal.search(panPat) == -1) 
										{
											alert("Please Enter Valid PAN NO.");
											$('#textPanNo').val('');
											//Obj.focus();
											return false;
										}
										else
										{
											pan_number = $('#textPanNo').val();
											$.ajax({
												url: '<?php echo base_url();?>employee/panUnique',
												type: 'post',
												data: {
													'pan_number' : pan_number,
													'type'		 : '<?php echo $type ?>',
													<?php
														if ($type == "edit") 
														{
															?>
															'id'  : '<?php echo $id ?>'
															<?php
														}
													?>
												},
												success: function(response)
												{
													if (response == 'already_taken') 
													{
														$('#pan_number_val').html('PAN Number Alredy Taken');
														//Obj.focus();
														$('.register-but').prop('disabled',true);
														return false;
													}
													else
													{
														$('#pan_number_val').html('(Ex : ABCDE1234F)');
														$('.register-but').prop('disabled',false);
														return true;
													}
												}
											});

										}
									}
									else
									{
										$('#pan_number_val').html('(Ex : ABCDE1234F)');
									}
								} 
					
								$('#mobile_number').blur(function () 
								{
									mobile_number = $(this).val();

									$.ajax({
										url: '<?php echo base_url();?>vendor/MobileExist',
										type: 'post',
										data: {
											'mobile_number' : mobile_number,
											<?php
												if ($type == "edit") 
												{
													?>
														'id'		 : '<?php echo $id ?>'
													<?php
												}
											?>
										},
										success: function(response)
										{
											if (response == 'taken') 
											{
												$('.mobile_number_exist').html('Mobile Number Alredy Taken');
												$('.register-but').prop('disabled',true);
												return false;
											}else
											{
												$('.mobile_number_exist').html('');
												$('.register-but').prop('disabled',false);
												return true;
											}
										}
									});
								});

								$('#email').blur(function () 
								{
									email = $(this).val();

									$.ajax({
										url: '<?php echo base_url();?>employee/EmailExist',
										type: 'post',
										data: {
											'email' : email,
											<?php
												if ($type == "edit") 
												{
													?>
													'id': '<?php echo $id ?>'
													<?php
												}
											?>
										},
										success: function(response)
										{
											if (response == 'taken') 
											{
												$('.employee_email_exist_error').html('Email Alredy Taken');
												$('.register-but').prop('disabled',true);
												$(this).focus();
												return false;
											}else
											{
												$('.employee_email_exist_error').html('');
												$('.register-but').prop('disabled',false);
												return true;
											}
										}
									});
								});
								
								$('[data-type="passport-number"]').keyup(function() 
								{
									var passport = $(this).val();
									if (passport == "") {
										return;
									}
									var regsaid = /[a-zA-Z]{1}[0-9]{6}/;
	
									if(regsaid.test(passport) == false)
									{
										document.getElementById("passport_number_error").innerHTML = "Passport is valid.";
										$('.register-but').prop('disabled',true);
									}
									else
									{
										document.getElementById("passport_number_error").innerHTML = "";
										$('.register-but').prop('disabled',false);
									}

								}); */
							</script>
							<?php
						}
					?>
					<?php
				}
				else if(isset($type) && $type == "edit" || $type == "view")
				{
					$activeBasic = $activeCareer = 
					$activeID = $activeAddress = $activeBank =
					$activeLogin= '';
					if( isset($type) )
					{
						if( $id == 'basic-info' )
						{
							$activeBasic = 'active';
						}
						else if( $id == 'career-info' )
						{
							$activeCareer = 'active';
						}
						else if( $id == 'id-info' )
						{
							$activeID = 'active';
						}
						else if( $id == 'address-info' )
						{
							$activeAddress = 'active';
						}
						else if( $id == 'bank-info' )
						{
							$activeBank = 'active';
						}
					}

					if($type == "view"){
						$fieldSetDisabled = "disabled";
						#$dropdownDisabled = "style='pointer-events: none;'";
						$searchDropdown = "";
					}else{
						$fieldSetDisabled = "";
						#$dropdownDisabled = "";
						$searchDropdown = "searchDropdown";
					}
					?>
					
					<ul class="nav nav-tabs" role="tablist">
						<li class="nav-item <?php echo $activeBasic;?>">
							<a href="<?php echo base_url(); ?>employee/ManageEmployee/<?php echo $type;?>/basic-info/<?php echo $status;?>" class="nav-link">Basic Info</a>
						</li>
						
						<li class="nav-item <?php echo $activeCareer;?>">
							<a href="<?php echo base_url(); ?>employee/ManageEmployee/<?php echo $type;?>/career-info/<?php echo $status;?>" class="nav-link">Employee Details</a>
						</li>
						
						<li class="nav-item <?php echo $activeID;?>">
							<a href="<?php echo base_url(); ?>employee/ManageEmployee/<?php echo $type;?>/id-info/<?php echo $status;?>" class="nav-link">Identity</a>
						</li>
						
						<li class="nav-item <?php echo $activeAddress;?>">
							<a href="<?php echo base_url(); ?>employee/ManageEmployee/<?php echo $type;?>/address-info/<?php echo $status;?>" class="nav-link">Address</a>
						</li>
						
						<li class="nav-item <?php echo $activeBank;?>">
							<a href="<?php echo base_url(); ?>employee/ManageEmployee/<?php echo $type;?>/bank-info/<?php echo $status;?>" class="nav-link">Bank Details</a>
						</li>
					</ul>
				
					<?php	
						if(isset($id) && $id == 'basic-info')
						{
							?>
							<form action="" class="form-validate-jquery" --id="formValidation" enctype="multipart/form-data" method="post">
								<legend class="text-uppercase font-size-sm font-weight-bold">
									<div class="row">
										<div class="col-md-6">
											Basic Information 
										</div>
										<?php 
											if( $type == "view" )
											{

											}
											else
											{
												?>
												<div class="col-md-6 text-right">
													<input type="submit" name="save_close" value="Save & Close" class="btn btn-primary ml-1 btn-sm register-but">
												</div>
												<?php 
											} 
										?>
									</div>
								</legend>
								
								<fieldset <?php echo $fieldSetDisabled;?>>
									<div class="row">
										<div class="col-md-6">
							
											<?php 
												$listTypeValuesQry = "select 
													sm_list_type_values.list_type_value_id,
													sm_list_type_values.list_code,
													sm_list_type_values.list_value	
														from sm_list_type_values

														left join sm_list_types on 
															sm_list_types.list_type_id = sm_list_type_values.list_type_id
																where 
																	sm_list_type_values.list_type_status = 1 and 
																		sm_list_types.list_type_id = 1"; 
												$getEmploymentType = $this->db->query($listTypeValuesQry)->result_array();
											?>

											<div class="row">
												<div class="form-group col-md-3">
													<label class="col-form-label">Employment Type <span class="text-danger">*</span></label>
												</div>
												<div class="form-group col-md-6">
													<select name="employment_type" id="employment_type" class="form-control <?php echo $searchDropdown;?>" required>
														<option value="">- Select -</option>
														<?php 
															foreach($getEmploymentType as $type1)
															{ 
																$selected="";
																if( isset($edit_data[0]['employment_type']) && $edit_data[0]['employment_type'] == $type1['list_type_value_id'])
																{
																	$selected="selected='selected'";
																}
																?>
																<option value="<?php echo $type1['list_type_value_id'];?>" <?php echo $selected;?>><?php echo ucfirst($type1['list_value']);?></option>
																<?php 
															} 
														?>
													</select>
												</div>
											</div>
											<div class="row">
										
												<div class="form-group col-md-3">
													<label class="col-form-label">First Name <span class="text-danger">*</span></label>
												</div>
												<div class="form-group col-md-6">
													<input type="text" name="first_name" required  <?php #echo $this->validation; ?> class="form-control" value="<?php echo isset($edit_data[0]['first_name']) ? $edit_data[0]['first_name'] :"";?>" placeholder="" autocomplete="off">
												</div>
											</div>
											<div class="row">
												<div class="form-group col-md-3">
													<label class="col-form-label">Middle Name</label>
												</div>
												<div class="form-group col-md-6">
													<input type="text" name="middle_name"  id="middle_name" <?php echo $this->validation; ?> class="form-control only_name" value="<?php echo isset($edit_data[0]['middle_name']) ? $edit_data[0]['middle_name'] :"";?>" placeholder="" autocomplete="off">
												</div>
											</div>
											<div class="row">	
												<div class="form-group col-md-3">
													<label class="col-form-label">Last Name</label>
												</div>
												<div class="form-group col-md-6">
													<input type="text" name="last_name" autocomplete="off" <?php echo $this->validation; ?> class="form-control" value="<?php echo isset($edit_data[0]['last_name']) ? $edit_data[0]['last_name'] :"";?>" placeholder="" autocomplete="off">
												</div>
											</div>
											<div class="row">
												<div class="form-group col-md-3">
													<label class="col-form-label">Mobile Number <span class="text-danger">*</span></label>
												</div>
												<?php
													$query = "select country.country_code,country.country_id
															from geo_countries as country 
															where 
															active_flag='Y'
													";
													$countryCode = $this->db->query($query)->result_array();
												?>
												
												<div class="form-group col-md-6">
													<div class="row">
														<div class="col-md-3 pr-0">
															<select name="mob_ctry_code" id="mobile_country_code" required class="form-control <?php echo $searchDropdown;?> mobile_vali">
																<option value="">- Select -</option>																				
																	<?php 
																		foreach($countryCode as $row)
																		{
																			$selected="";
																			if(isset($edit_data[0]['mob_ctry_code']) && $edit_data[0]['mob_ctry_code'] == $row['country_id']){
																				$selected="selected='selected'";
																			}
																			?>
																			<option value="<?php echo $row['country_id'];?>" <?php echo $selected;?>><?php echo $row['country_code'];?></option>
																			<?php 
																		} 
																	?>
															</select>
														</div>
														<div class="col-md-9 pl-0">
															<input type="text" name="mobile_number" id="mobile_number" required autocomplete="off"  minlength="10" maxlength="10" class="form-control mobile_vali code-num1" value="<?php echo isset($edit_data[0]['mobile_number']) ? $edit_data[0]['mobile_number'] :"";?>" placeholder="9632587410">
															<span class="small mobile_number_exist" style="color:#a19f9f;"></span>
														</div>
													</div>	
												</div>
											</div>
											<div class="row">
												<div class="form-group col-md-3">
													<label class="col-form-label">Alternate Mobile Number </label>
												</div>
												<div class="form-group col-md-6">
													<div class="row">
														<div class="col-md-3 pr-0">
															<select name="alt_mob_ctry_code" id="mobile_country_code" class="form-control <?php echo $searchDropdown;?> mobile_vali">
																<option value="">- Select -</option>																				
																	<?php 
																		foreach($countryCode as $row)
																		{
																			$selected="";
																			if(isset($edit_data[0]['alt_mob_ctry_code']) && $edit_data[0]['alt_mob_ctry_code'] == $row['country_id']){
																				$selected="selected='selected'";
																			}
																			?>
																			<option value="<?php echo $row['country_id'];?>" <?php echo $selected;?>><?php echo $row['country_code'];?></option>
																			<?php 
																		} 
																	?>
															</select>
														</div>
														<div class="col-md-9 pl-0">
															<input type="text" name="alt_mob_number" minlength="10" maxlength="10" id="alternate_contact" autocomplete="off"  class="form-control mobile_vali code-num1" value="<?php echo isset($edit_data[0]['alt_mob_number']) ? $edit_data[0]['alt_mob_number'] :"";?>" placeholder="9632587410">
															<span class="mobile_number_exist"></span>
														</div>
													</div>
												</div>		
											</div>
											<div class="row">
												<div class="form-group col-md-3">
													<label class="col-form-label">Email <span class="text-danger">*</span></label>
												</div>
												<div class="form-group col-md-6">
													<input type="email" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,63}$" name="email_address" id="email" required autocomplete="off" class="form-control" value="<?php echo isset($edit_data[0]['email_address']) ? $edit_data[0]['email_address'] :"";?>" placeholder="">
													<span class='small employee_email_exist_error' style="color:#a19f9f;"></span> 
												</div>
											</div>
											<div class="row">	
												<div class="form-group col-md-3">
													<label class="col-form-label">Alternate E-Mail</label>
												</div>
												<div class="form-group col-md-6">
													<input type="email" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,63}$" name="alt_email_address" --id="emp_email" autocomplete="off" class="form-control" value="<?php echo isset($edit_data[0]['alt_email_address']) ? $edit_data[0]['alt_email_address'] :"";?>" placeholder="">
													<?php /* <span class='employee_email_exist_error'></span> */?>
												</div>
											</div>
										</div>
										<div class="col-md-6">
											<div class="row">	
												<div class="form-group col-md-3">
													<label class="col-form-label">Father Name</label>
												</div>
												<div class="form-group col-md-6">
													<input type="text" name="father_name"  id="father_name" <?php echo $this->validation; ?> class="form-control only_name" value="<?php echo isset($edit_data[0]['father_name']) ? $edit_data[0]['father_name'] :"";?>" placeholder="" autocomplete="off">
												</div>
											</div>
											<div class="row">
												<div class="form-group col-md-3">
													<label class="col-form-label">Mother Name</label>
												</div>
												<div class="form-group col-md-6">
													<input type="text" name="mother_name"  id="mother_name" autocomplete="off" <?php echo $this->validation; ?> class="form-control only_name" value="<?php echo isset($edit_data[0]['mother_name']) ? $edit_data[0]['mother_name'] :"";?>" placeholder="">
												</div> 
											</div>
											<div class="row">
												<div class="form-group col-md-3">
													<label class="col-form-label">Date of Birth <span class="text-danger">*</span></label>
												</div>
												<div class="form-group col-md-6">
													<input type="text" name="date_of_birth" readonly id="date_of_birth" required class="form-control default_date" autocomplete="off" value="<?php echo isset($edit_data[0]['date_of_birth']) ? date("d-M-Y",strtotime($edit_data[0]['date_of_birth'])) :"";?>" placeholder="">
												</div>
											</div>
											<div class="row">
												<div class="form-group col-md-3">
													<label class="col-form-label">Gender <span class="text-danger">*</span></label>
												</div>
												<div class="form-group col-md-6">	
													<select name="gender" required id="gender" class="form-control <?php echo $searchDropdown;?>">
														<option value="">- Select Gender -</option>
														<?php 
															foreach($this->gender as $key => $value)
															{ 
																$selected="";
																if( isset($edit_data[0]['gender']) && $edit_data[0]['gender'] == $key)
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
											<div class="row">
												<?php 
													$bloodgroup = $this->db->query("select blood_group_name,blood_group_id from emp_blood_group where active_flag='Y'")->result_array();
												?>
												<div class="form-group col-md-3">
													<label class="col-form-label">Blood Group</label>
												</div>
												<div class="form-group col-md-6">
													<a class="quicklink" target="_blank" href="<?php echo base_url()?>employee/ManageBloodGroup/add" title="Add Blood Group"> Add Blood Group </a>
													<select name="blood_group_id" id="blood_group_id" class="form-control <?php echo $searchDropdown;?>">
														<option value="">- Select Blood Group -</option>
														<?php 
															foreach($bloodgroup as $row)
															{
																$selected="";
																if(isset($edit_data[0]['blood_group_id']) && $edit_data[0]['blood_group_id'] == $row['blood_group_id']){
																	$selected="selected='selected'";
																}
																?>
																<option value="<?php echo $row['blood_group_id'];?>" <?php echo $selected;?>><?php echo $row['blood_group_name'];?></option>
																<?php 
															} 
														?>
													</select>
												</div>
											</div>
											<div class="row">
												<div class="form-group col-md-3">
													<label class="col-form-label">Profile Image</label>
												</div>
												<div class="form-group col-md-6">
													<input type="file" name="profile_image" id="profile_image"class="form-control" placeholder="">
													<?php
														if($type=='edit')
														{
															if(file_exists("uploads/profile_image/".$status.'.png') )
															{
																?>
																<img class="img-responsive mt-2" alt="best dental hospial hosur" style="border-radius:4px;width:75px;height:75px;" src="<?php echo base_url(); ?>uploads/profile_image/<?php echo $status.'.png';?>">
																<?php 
															}
														}
														else
														{

														}
													?>
												</div>
											</div>
										</div>
									</div>
								</fieldset>
								
								<div class="d-flexad" style="text-align:right;">
									<a href="<?php echo base_url(); ?>employee/ManageEmployee" class="btn btn-light btn-sm">Close</a>
									<?php 
										if($type == "view")
										{
											
										}
										else
										{
											?>
											<input type="submit" name="save_only" value="Save" class="btn btn-primary ml-1 btn-sm register-but">
											<?php 
										} 
									?>
								</div>
							</form>
							<?php 
						} 
						else if(isset($id) && $id == 'career-info')
						{
							?>
							<form action="" class="form-validate-jquery" --id="formValidation" enctype="multipart/form-data" method="post">
								<legend class="text-uppercase font-size-sm font-weight-bold">
									<div class="row">
										<div class="col-md-6">
											Career Information
										</div>

										<?php 
											if( $type == "view" )
											{
												
											}
											else
											{
												?>
												<div class="col-md-6 text-right">
													<input type="submit" name="save_close" value="Save & Close" class="btn btn-primary ml-1 btn-sm register-but">
												</div>
												<?php 
											} 
										?>
									</div>
								</legend>

								<fieldset <?php echo $fieldSetDisabled;?>>
									<div class="row">
										<div class="col-md-6">
											<div class="row">	
												<?php 
													$getOrganization = $this->db->query("select organization_id, organization_name from org_organizations where active_flag='Y' order by organization_name asc")->result_array();
												?>
												<div class="form-group col-md-3">
													<label class="col-form-label">Organization <span class="text-danger">*</span></label>
												</div>
												<div class="form-group col-md-6">
													<a class="quicklink" target="_blank" href="<?php echo base_url()?>admin/ManageBranches/add" title="organisation"> Add Branch </a>
													<select name="organization_id" required id="organization_id" class="form-control <?php echo $searchDropdown;?>">
														<option value="">- Select -</option>
														<?php 
															foreach($getOrganization as $row)
															{ 
																$selected="";
																if( isset($edit_data[0]['organization_id']) && $edit_data[0]['organization_id'] == $row['organization_id'])
																{
																	$selected="selected='selected'";
																}
																?>
																<option value="<?php echo $row['organization_id'];?>" <?php echo $selected;?>><?php echo ucfirst($row['organization_name']);?></option>
																<?php 
															} 
														?>
													</select>
												</div>		
											</div>
											<div class="row">	
												<?php 
													$getBranch = $this->db->query("select branch_name, branch_id from branch where active_flag='Y' order by branch_name asc")->result_array();
												?>
												<div class="form-group col-md-3">
													<label class="col-form-label">Location <span class="text-danger">*</span></label>
												</div>
												<div class="form-group col-md-6">
													<a class="quicklink" target="_blank" href="<?php echo base_url()?>admin/ManageBranches/add" title="Branch"> Add Branch </a>
													<select name="branch_id" id="branch_id" required class="form-control <?php echo $searchDropdown;?>">
														<option value="">- Select -</option>
														<?php 
															foreach($getBranch as $row)
															{ 
																$selected="";
																if( isset($edit_data[0]['branch_id']) && $edit_data[0]['branch_id'] == $row['branch_id'])
																{
																	$selected="selected='selected'";
																}
																?>
																<option value="<?php echo $row['branch_id'];?>" <?php echo $selected;?>><?php echo ucfirst($row['branch_name']);?></option>
																<?php 
															} 
														?>
													</select>
												</div>
											</div>		
											<div class="row">
												<?php 
													$designation = $this->db->query("select designation_name,designation_id from emp_designations where designation_status=1")->result_array();
												?>	
												<div class="form-group col-md-3">
													<label class="col-form-label">Designation <span class="text-danger">*</span></label>
												</div>
												<div class="form-group col-md-6">
													<a class="quicklink" target="_blank" href="<?php echo base_url()?>employee/ManageDesignation/add" title="Add Designation">Add Designation</a>
													<select name="designation_id" id="designation_id" required class="form-control <?php echo $searchDropdown;?>">
														<option value="">- Select -</option>
														<?php 
															foreach($designation as $row)
															{
																$selected="";
																if(isset($edit_data[0]['designation_id']) && $edit_data[0]['designation_id'] == $row['designation_id']){
																	$selected="selected='selected'";
																}
																?>
																<option value="<?php echo $row['designation_id'];?>" <?php echo $selected;?>><?php echo $row['designation_name'];?></option>
																<?php 
															} 
														?>
													</select>
												</div>
											</div>
											<div class="row">
												<?php 
													$getPositions = $this->db->query("select position_name,position_id from hr_positions where position_status=1")->result_array();
												?>
												<?php /* <div class="form-group col-md-3">
													<label class="col-form-label">Position<span class="text-danger">*</span></label>
													<a class="quicklink" target="_blank" href="<?php echo base_url()?>employee/ManagePositions/add" title="Add Position">Add Position</a>
													<select name="position_id" id="position_id" class="form-control searchDropdown">
														<option value="">- Select Position -</option>
														<?php
															foreach($getPositions as $row)
															{
																$selected="";
																if(isset($edit_data[0]['position_id'])&& $edit_data[0]['position_id'] == $row['position_id'])
																{
																	$selected="selected='selected'";
																}
																?>
																	<option value="<?php echo $row['position_id'];?>" <?php echo $selected; ?>> <?php echo $row['position_name'];?></option>
																<?php
															}
														?>
													</select>
												</div> */ ?>
												
												<?php 
													$getDepartment = $this->db->query("select department_name,department_id from emp_departments where department_status=1")->result_array();
												?>
												<div class="form-group col-md-3">
													<label class="col-form-label">Department <span class="text-danger">*</span></label>
												</div>
												<div class="form-group col-md-6">
													<a class="quicklink" target="_blank" href="<?php echo base_url()?>employee/ManageDepartment/add" title="Add Department">Add Department</a>
													<select name="department_id" id="department_id" required class="form-control <?php echo $searchDropdown;?>">
														<option value="">- Select -</option>
														<?php
															foreach($getDepartment as $row)
															{
																$selected="";
																if(isset($edit_data[0]['department_id'])&& $edit_data[0]['department_id'] == $row['department_id'])
																{
																	$selected="selected='selected'";
																}
																?>
																	<option value="<?php echo $row['department_id'];?>" <?php echo $selected;?>> <?php echo $row['department_name'];?></option>
																<?php
															}
														?>
													</select>
												</div>
											</div>
											<div class="row">
												<div class="form-group col-md-3">
													<label class="col-form-label">Date of Joining <span class="text-danger">*</span></label>
												</div>
												<div class="form-group col-md-6">
													<input type="text" name="date_of_joining" readonly id="date_of_joining" required class="form-control default_date" autocomplete="off" value="<?php echo isset($edit_data[0]['date_of_joining']) ? $edit_data[0]['date_of_joining'] :"";?>" placeholder="">
												</div>
											</div>
											<div class="row">
												<div class="form-group col-md-3">
													<label class="col-form-label">Date of Releaving</label>
												</div>
												<div class="form-group col-md-4">
													<input type="text" name="date_of_releaving" readonly id="date_of_releaving" autocomplete="off" class="form-control default_date" value="<?php echo isset($edit_data[0]['date_of_releaving']) ? $edit_data[0]['date_of_releaving'] :"";?>" placeholder="">
												</div>
											</div>	
										</div>
										<div class="col-md-6">
											<div class="row">	
												<div class="form-group col-md-3">
													<label class="col-form-label">Previous Experience</label>
												</div>
												<div class="form-group col-md-6">
													<input type="text" name="previous_experience" id="previous_experience" autocomplete="off" class="form-control mobile_vali" value="<?php echo isset($edit_data[0]['previous_experience']) ? $edit_data[0]['previous_experience'] :"";?>" placeholder="">
												</div>
											</div>	
											<div class="row">	
												<div class="form-group col-md-3">
													<label class="col-form-label">Rate Per Hour</label>
												</div>
												<div class="form-group col-md-6">
													<input type="text" name="rate_per_hour" id="rate_per_hour" autocomplete="off" class="form-control mobile_vali" value="<?php echo isset($edit_data[0]['rate_per_hour']) ? $edit_data[0]['rate_per_hour'] :"";?>" placeholder="">
												</div>
											</div>
											<div class="row">
												<div class="form-group col-md-3">
													<label class="col-form-label">Rate Per Day</label>
												</div>
												<div class="form-group col-md-6">
													<input type="text" name="rate_per_day" id="rate_per_day" autocomplete="off" class="form-control mobile_vali" value="<?php echo isset($edit_data[0]['rate_per_day']) ? $edit_data[0]['rate_per_day'] :"";?>" placeholder="">
												</div>

												<input type="hidden" name="annual_ctc" id="annual_ctc" autocomplete="off" class="form-control mobile_vali" value="<?php echo isset($edit_data[0]['annual_ctc']) ? $edit_data[0]['annual_ctc'] :"";?>" placeholder="">
												<input type="hidden" name="position_id" id="position_id" autocomplete="off" class="form-control mobile_vali" value="<?php echo isset($edit_data[0]['position_id']) ? $edit_data[0]['position_id'] :"";?>" placeholder="">
											</div>	
											<div class="row">	
												<div class="form-group col-md-3">
													<label class="col-form-label">Pay frequency</label>
												</div>	
												<div class="form-group col-md-6">
													<select name="pay_frequency" id="pay_frequency" class="form-control <?php echo $searchDropdown;?>" > <!--selectboxit-->
														<option value="">- Select -</option>
														<?php 
															foreach($this->pay_frequency as $key=>$value)
															{
																$selected = "";
																if( isset($edit_data[0]['pay_frequency']) && $edit_data[0]['pay_frequency'] == $key)
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
									</div>
								</fieldset>

								<div class="d-flexad" style="text-align:right;">
									<a href="<?php echo base_url(); ?>employee/ManageEmployee" class="btn btn-light">Close</a>
									<a href="<?php echo base_url(); ?>employee/ManageEmployee/<?php echo $type;?>/basic-info/<?php echo $status;?>" class="btn btn-light">Back</a>
									<?php 
										if( $type == "view" )
										{
											
										}
										else
										{
											?>
											<input type="submit" name="save_only" value="Save" class="btn btn-primary btn-sm register-but">
											<?php 
										} 
									?>
								</div>
							</form>
							<?php
						}
						else if(isset($id) && $id == 'id-info')
						{
							?>
							<form action="" class="form-validate-jquery" --id="formValidation" enctype="multipart/form-data" method="post">
								<legend class="text-uppercase font-size-sm font-weight-bold">
									<div class="row">
										<div class="col-md-6">
											ID Information
										</div>

										<?php 
											if( $type == "view" )
											{
												
											}
											else
											{
												?>
												<div class="col-md-6 text-right">
													<input type="submit" name="save_close" value="Save & Close" class="btn btn-primary ml-1 btn-sm register-but">
												</div>
												<?php  
											} 
										?>
									</div>
								</legend>

								<fieldset <?php echo $fieldSetDisabled;?>>
									<div class="row">
										<div class="col-md-6">
											<div class="row">	
												<div class="form-group col-md-3">
													<label class="col-form-label">Aadhar No <span class="text-danger">*</span></label>
												</div>
												<div class="form-group col-md-6">
													<input type="number" name="aadhaar_number" id="aadhaar_number" required minlength="12" maxlength="12" class="form-control mobile_vali" autocomplete="off" value="<?php echo isset($edit_data[0]['aadhaar_number']) ? $edit_data[0]['aadhaar_number'] :"";?>" placeholder="">
													<span class="small" id="aadhaar_number_val" style="color:#a19f9f;float:left;width:100%;">(Ex : 489118465046)</span>
												</div>
											</div>
											<div class="row">
												<div class="form-group col-md-3">
													<label class="col-form-label">PAN Number <span class="text-danger">*</span></label>
												</div>
												<div class="form-group col-md-6">
													<input type="text" name="pan_number" maxlength="10" id="textPanNo" required onblur="ValidatePAN(this);" autocomplete="off" <?php echo $this->validation; ?> class="form-control" value="<?php echo isset($edit_data[0]['pan_number']) ? $edit_data[0]['pan_number'] :"";?>" placeholder="">
													<span class="small" id="pan_number_val" style="color:#a19f9f;float:left;width:100%;">(Ex : ABCDE1234F)</span>
												</div>
											</div>	
											<div class="row">								
												<div class="form-group col-md-3">
													<label class="col-form-label">Driving Licence</label>
												</div>
												<div class="form-group col-md-6">
													<input type="text" name="driving_licence" id="driving_licence" --maxlength="17" --oninput="LicenceNumber(this)" class="form-control" autocomplete="off" value="<?php echo isset($edit_data[0]['driving_licence']) ? $edit_data[0]['driving_licence'] :"";?>" placeholder="">
													<span id="licence_number_val" class="small" style="color:#a19f9f;">(Ex : TN-0619850034761 )</span>
												</div>
											</div>
											<div class="row">
												<div class="form-group col-md-3">
													<label class="col-form-label">Passport No</label>
												</div>
												<div class="form-group col-md-6">
													<input type="text" name="passport_number" id="passport_number" minlength="8" maxlength="10" data-type="passport-number" <?php echo $this->validation; ?> class="form-control" autocomplete="off" value="<?php echo isset($edit_data[0]['passport_number']) ? $edit_data[0]['passport_number'] :"";?>" placeholder="">
													<span id="passport_number_error" class="small" style="color:#a19f9f;">(Ex : A1234567)</span>
												</div>
											</div>
											<div class="row">
												<div class="form-group col-md-3">
													<label class="col-form-label">Passport Issue Date</label>
												</div>
												<div class="form-group col-md-6">
													<input type="text" name="passport_issue_date" id="passport_issue_date" class="form-control default_date" autocomplete="off" value="<?php echo isset($edit_data[0]['passport_issue_date']) ? $edit_data[0]['passport_issue_date'] :"";?>" placeholder="">
												</div>
											</div>
											
										</div>
										<div class="col-md-6">
											<div class="row">
												<div class="form-group col-md-3">
													<label class="col-form-label">Passport Expiry Date</label>
												</div>
												<div class="form-group col-md-6">
													<input type="text" name="passport_exp_date" id="passport_exp_date" class="form-control default_date" autocomplete="off" value="<?php echo isset($edit_data[0]['passport_exp_date']) ? $edit_data[0]['passport_exp_date'] :"";?>" placeholder="">
												</div>
											</div>
											<div class="row">
												<div class="form-group col-md-3">
													<label class="col-form-label">PF No <span class="text-danger">*</span></label>
												</div>
												<div class="form-group col-md-6">
													<input type="text" name="pf_number" id="pf_number" autocomplete="off" required class="form-control dev_name" value="<?php echo isset($edit_data[0]['pf_number']) ? $edit_data[0]['pf_number'] :"";?>" placeholder="">
												</div>
											</div>
											<div class="row">
												<div class="form-group col-md-3">
													<label class="col-form-label">ESI No.</label>
												</div>
												<div class="form-group col-md-6">
													<input type="text" name="esi_number" id="esi_number" autocomplete="off" class="form-control dev_name" value="<?php echo isset($edit_data[0]['esi_number']) ? $edit_data[0]['esi_number'] :"";?>" placeholder="">
												</div>
											</div>
											<div class="row">
												<div class="form-group col-md-3">
													<label class="col-form-label">UAN No.</label>
												</div>
												<div class="form-group col-md-6">
													<input type="text" name="uan_number" id="uan_number" autocomplete="off" class="form-control dev_name" value="<?php echo isset($edit_data[0]['uan_number']) ? $edit_data[0]['uan_number'] :"";?>" placeholder="">
												</div>
											</div>
										</div>
									</div>
								</fieldset>

								<div class="d-flexad" style="text-align:right;">
									<a href="<?php echo base_url(); ?>employee/ManageEmployee" class="btn btn-light">Close</a>
									<a href="<?php echo base_url(); ?>employee/ManageEmployee/<?php echo $type;?>/career-info/<?php echo $status;?>" class="btn btn-light">Back</a>
									<?php 
										if( $type == "view" )
										{
											
										}
										else
										{
											?>
											<input type="submit" name="save_only" value="Save" class="btn btn-primary btn-sm register-but">
											<?php 
										} 
									?>
								</div>
							</form>
							
							<script type="text/javascript">
								/* $('[data-type="adhaar-number"]').keyup(function() 
								{
									var value = $(this).val();
									value = value.replace(/\D/g, "").split(/(?:([\d]{4}))/g).filter(s => s.length > 0).join("-");
									$(this).val(value);

									aadhaar_number_val = value;
									$.ajax({
										url: '<?php echo base_url();?>employee/aadhaarUnique',
										type: 'post',
										data: {
											'aadhaar_number' : aadhaar_number_val,
											'type'		 : '<?php echo $status ?>',
											<?php
												if ($type == "edit") 
												{
													?>
													'id' : '<?php echo $status; ?>'
													<?php
												}
											?>
										},
										success: function(response)
										{
											if (response == 'already_taken') 
											{
												$('#aadhaar_number_val').html('Aadhaar Number Alredy Taken');
												// Obj.focus();
												$('.register-but').prop('disabled',true);
												return false;
											}else
											{
												$('#aadhaar_number_val').html('(Ex : 4891-1846-5046)');
												$('.register-but').prop('disabled',false);
												return true;
											}
										}
									});
								}); */

								function ValidatePAN() 
								{ 
									var Obj = document.getElementById("textPanNo");
									if (Obj.value != "") 
									{
										ObjVal = Obj.value;
										var panPat = /^([a-zA-Z]{5})(\d{4})([a-zA-Z]{1})$/;
										if (ObjVal.search(panPat) == -1) 
										{
											//alert("Please Enter Valid PAN NO.");
											$('#textPanNo').val('');
											//Obj.focus();
											return false;
										}
										else
										{
											pan_number = $('#textPanNo').val();
											$.ajax({
												url: '<?php echo base_url();?>employee/panUnique',
												type: 'post',
												data: {
													'pan_number' : pan_number,
													'type'		 : '<?php echo $status; ?>',
													<?php
														if ($type == "edit") 
														{
															?>
															'id'  : '<?php echo $id ?>'
															<?php
														}
													?>
												},
												success: function(response)
												{
													if (response == 'already_taken') 
													{
														$('#pan_number_val').html('PAN Number Alredy Taken');
														//Obj.focus();
														$('.register-but').prop('disabled',true);
														return false;
													}
													else
													{
														$('#pan_number_val').html('(Ex : ABCDE1234F)');
														$('.register-but').prop('disabled',false);
														return true;
													}
												}
											});

										}
									}
									else
									{
										$('#pan_number_val').html('(Ex : ABCDE1234F)');
									}
								} 
					
								$('#mobile_number').blur(function () 
								{
									mobile_number = $(this).val();

									$.ajax({
										url: '<?php echo base_url();?>vendor/MobileExist',
										type: 'post',
										data: {
											'mobile_number' : mobile_number,
											<?php
												if ($type == "edit") 
												{
													?>
														'id'		 : '<?php echo $status ?>'
													<?php
												}
											?>
										},
										success: function(response)
										{
											if (response == 'taken') 
											{
												$('.mobile_number_exist').html('Mobile Number Alredy Taken');
												$('.register-but').prop('disabled',true);
												return false;
											}else
											{
												$('.mobile_number_exist').html('');
												$('.register-but').prop('disabled',false);
												return true;
											}
										}
									});
								});

								$('#email').blur(function () 
								{
									email = $(this).val();

									$.ajax({
										url: '<?php echo base_url();?>employee/EmailExist',
										type: 'post',
										data: {
											'email' : email,
											<?php
												if ($type == "edit") 
												{
													?>
													'id': '<?php echo $status ?>'
													<?php
												}
											?>
										},
										success: function(response)
										{
											if (response == 'taken') 
											{
												$('.employee_email_exist_error').html('Email Alredy Taken');
												$('.register-but').prop('disabled',true);
												$(this).focus();
												return false;
											}else
											{
												$('.employee_email_exist_error').html('');
												$('.register-but').prop('disabled',false);
												return true;
											}
										}
									});
								});
								
								$('[data-type="passport-number"]').keyup(function() 
								{
									var passport = $(this).val();
									if (passport == "") {
										return;
									}
									var regsaid = /[a-zA-Z]{1}[0-9]{6}/;
	
									if(regsaid.test(passport) == false)
									{
										document.getElementById("passport_number_error").innerHTML = "Passport is valid.";
										$('.register-but').prop('disabled',true);
									}
									else
									{
										document.getElementById("passport_number_error").innerHTML = "";
										$('.register-but').prop('disabled',false);
									}

								});
							</script>
							<?php
						}
						else if(isset($id) && $id == 'address-info')
						{
							?>
							<form action="" class="form-validate-jquery"  enctype="multipart/form-data" method="post">
								<legend class="text-uppercase font-size-sm font-weight-bold">
									<div class="row">
										<div class="col-md-6">
											Address Information
										</div>
										<?php 
											if( $type == "view" )
											{
												
											}
											else
											{
												?>
												<div class="col-md-6 text-right">
													<input type="submit" name="save_close" value="Save & Close" class="btn btn-primary ml-1 btn-sm register-but">
												</div>
												<?php 
											} 
										?>
									</div>
								</legend>

								<fieldset <?php echo $fieldSetDisabled;?>>
									<?php
										$country = $this->db->get_where('geo_countries', array('active_flag' => 'Y'))->result_array();
									?>
									<script type="text/javascript">  
										function selectState(val)
										{
											$('select#permenant_country_id option').removeAttr('selected',true);
											$('select#permenant_state_id option').removeAttr('selected',true);
											$('select#permenant_city_id option').removeAttr('selected',true);

											if(!$("#chk_shipping_address").is(":checked")) //Unchecked
											{
												
											}
											else //Checked
											{
												currentselectState(val);
												$('select#permenant_country_id option[value='+val+']').attr('selected','selected');
											}

											if(val !='')
											{
												$.ajax({
												type: "POST",
												url:"<?php echo base_url().'admin/ajaxSelectState';?>",
												data: { id: val }
												}).done(function( msg ) 
												{   
													if(msg == "no_date_found")
													{
														$("#state_id").html("<option value=''>- Select -</option>");
														$("#district_id").html("<option value=''>- Select -</option>");
														$("#city_id").html("<option value=''>- Select -</option>");
													}
													else
													{
													$("#state_id").html(msg);
													}
												});
											}
											else 
											{ 
												$("#state_id").html("<option value=''>- Select -</option>");
												$("#district_id").html("<option value=''>- Select -</option>");
												$("#city_id").html("<option value=''>- Select -</option>");
											}
										}
										
										/* function selectDistrict(val)
										{
											if(val !='')
											{
												$.ajax({
												type: "POST",
												url:"<?php echo base_url().'admin/ajaxSelectDistrict';?>",
												data: { id: val }
												}).done(function( msg ) {  
													$( "#district_id").html(msg);
												});
											}
											else 
											{ 
												$("#district_id").html("<option value=''>- Select -</option>");
												$("#city_id").html("<option value=''>- Select -</option>");
											}
										} */
										
										function selectCityorTown(val)
										{
											
											$('select#permenant_state_id option').removeAttr('selected',true);
											$('select#permenant_city_id option').removeAttr('selected',true);

											if(!$("#chk_shipping_address").is(":checked")) //Unchecked
											{
												
											}
											else //Checked
											{
												currentselectCityorTown(val);
												$('select#permenant_state_id option[value='+val+']').attr('selected','selected');
											}

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
														$("#city_id").html("<option value=''>- Select -</option>");
													}
													else
													{
														$( "#city_id").html(msg);
													}
												});
											}
											else 
											{ 
												$("#city_id").html("<option value=''>- Select -</option>");
											}
										}

										function selectCity(val)
										{
											$('select#permenant_city_id option').removeAttr('selected',true);

											if(!$("#chk_shipping_address").is(":checked")) //Unchecked
											{
												
											}
											else //Checked
											{
												///currentselectCityorTown(val);
												$('select#permenant_city_id option[value='+val+']').attr('selected','selected');
											}
										}

										function currentselectState(val)
										{
											if(val !='')
											{
												$.ajax({
												type: "POST",
												url:"<?php echo base_url().'admin/ajaxSelectState';?>",
												data: { id: val }
												}).done(function( msg ) 
												{   
													if(msg == "no_date_found")
													{
														$("#permenant_state_id").html("<option value=''>- Select -</option>");
														$("#permenant_city_id").html("<option value=''>- Select -</option>");
													}
													else
													{
														$("#permenant_state_id").html(msg);
													}
												});
											}
											else 
											{ 
												$("#permenant_state_id").html("<option value=''>- Select -</option>");
												$("#permenant_district_id").html("<option value=''>- Select -</option>");
												$("#permenant_city_id").html("<option value=''>- Select -</option>");
											}
										}
										
										/* function currentselectDistrict(val)
										{
											if(val !='')
											{
												$.ajax({
												type: "POST",
												url:"<?php echo base_url().'admin/ajaxSelectDistrict';?>",
												data: { id: val }
												}).done(function( msg ) {  
													$( "#permenant_district_id").html(msg);
												});
											}
											else 
											{ 
												$("#permenant_district_id").html("<option value=''>- Select -</option>");
												$("#permenant_city_id").html("<option value=''>- Select -</option>");
											}
										} */
										
										function currentselectCityorTown(val)
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
														$("#permenant_city_id").html("<option value=''>- Select -</option>");
													}
													else
													{
														$( "#permenant_city_id").html(msg);
													}
												});
											}
											else 
											{ 
												$("#permenant_city_id").html("<option value=''>- Select -</option>");
											}
										}
									</script>

									<div class="row">
										<!-- Current Address Start -->
										<div class="col-md-6 current_address">
											<div class="row">
												<div class="form-group col-md-3">
													<label class="col-form-label">Current Address:</label>
												</div>
											</div>

											<div class="row">
												<div class="form-group col-md-3">
													<label class="col-form-label">Address 1 <span class="text-danger">*</span></label>
												</div>
												<div class="form-group col-md-6">
													<textarea name="address" rows="1" required id="address1" required class="form-control single_quotes" autocomplete="off"><?php echo isset($edit_data[0]['address1']) ? $edit_data[0]['address1'] :"";?></textarea>
												</div>
											</div>

											<div class="row">
												<div class="form-group col-md-3">
													<label class="col-form-label">Address 2 <span class="text-danger">*</span></label>
												</div>
												<div class="form-group col-md-6">
													<textarea name="address2" rows="1" id="address2" required class="form-control single_quotes" autocomplete="off"><?php echo isset($edit_data[0]['address2']) ? $edit_data[0]['address2'] :"";?></textarea>
												</div>
											</div>

											<div class="row">
												<div class="form-group col-md-3">
													<label class="col-form-label">Address 3 <span class="text-danger">*</span></label>
												</div>
												<div class="form-group col-md-6">
													<textarea name="address3" rows="1" id="address3" required class="form-control single_quotes" autocomplete="off"><?php echo isset($edit_data[0]['address3']) ? $edit_data[0]['address3'] :"";?></textarea>
												</div>
											</div>
										
											<div class="row">
												<div class="form-group col-md-3">
													<label class="col-form-label">Country <span class="text-danger">*</span></label>
												</div>
												<div class="form-group col-md-6">
													<select name="country_id" id="country_id" required onchange="selectState(this.value);" class="form-control <?php echo $searchDropdown;?>"> <!--selectboxit-->
														<option value="">- Select -</option>
														<?php 
															foreach($country as $row)
															{
																$selected="";
																if(isset($edit_data[0]['country_id']) && $edit_data[0]['country_id'] == $row['country_id']){
																	$selected="selected='selected'";
																}
																?>
																<option value="<?php echo $row['country_id'];?>" <?php echo $selected;?>><?php echo $row['country_name'];?></option>
																<?php 
															} 
														?>
													</select>
												</div>
											</div>

											<div class="row">
												<div class="form-group col-md-3">
													<label class="col-form-label">State <span class="text-danger">*</span></label>
												</div>
												<div class="form-group col-md-6">
													<select name="state_id" id="state_id" required onchange="selectCityorTown(this.value);" class="form-control <?php echo $searchDropdown;?>"> <!--selectboxit-->
														<option value="">- Select -</option>
														<?php 
															if($type == "edit" || $type == "view")
															{
																$state = $this->db->get_where('geo_states', array('active_flag' => 'Y','country_id' => $edit_data[0]['country_id']))->result_array();
												
																foreach($state as $row)
																{
																	$selected="";
																	if(isset($edit_data[0]['state_id']) && $edit_data[0]['state_id'] == $row['state_id']){
																		$selected="selected='selected'";
																	}
																	?>
																	<option value="<?php echo $row['state_id'];?>" <?php echo $selected;?>><?php echo $row['state_name'];?></option>
																	<?php 
																}
															}
														?>
													</select>
												</div>
											</div>
										
											<div class="row">
												<div class="form-group col-md-3">
													<label class="col-form-label">City <span class="text-danger">*</span></label>
												</div>
												<div class="form-group col-md-6">
													<select name="city_id" id="city_id" onchange="selectCity(this.value);" required class="form-control <?php echo $searchDropdown;?>" > <!--selectboxit-->
														<option value="">- Select -</option>
														<?php 
															if($type == "edit" || $type == "view")
															{
																$city = $this->db->get_where('geo_cities', array('active_flag' => 'Y','state_id' => $edit_data[0]['state_id']))->result_array();
												
																foreach($city as $row)
																{
																	$selected="";
																	if( isset($edit_data[0]['city_id']) && $edit_data[0]['city_id'] == $row['city_id'])
																	{
																		$selected="selected='selected'";
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
													<label class="col-form-label">Postal Code <span class="text-danger">*</span></label>
												</div>
												<div class="form-group col-md-6">
													<input type="text" name="postal_code" id="postal_code" minlength="6" maxlength="6" required autocomplete="off" class="form-control mobile_vali" value="<?php echo isset($edit_data[0]['postal_code']) ? $edit_data[0]['postal_code'] :"";?>" placeholder="">
												</div>
											</div>
										</div>
										<!-- Current Address end -->

										<script>
											$("form .current_address").on("input keyup change", 'textarea[name^="address"], textarea[name^="address2"], textarea[name^="address3"],input[name^="postal_code"], select[name^="country_id"], select[name^="state_id"], select[name^="city_id"]', function (event) 
											{
												if(!$("#chk_shipping_address").is(":checked")) //Unchecked
												{
													
												}
												else //Checked
												{
													var address1 = $("#address1").val();
													$("#permenant_address1").val(address1);

													var address2 = $("#address2").val();
													$("#permenant_address2").val(address2);

													var address3 = $("#address3").val();
													$("#permenant_address3").val(address3);

													var postal_code = $("#postal_code").val();
													$("#permenant_postal_code").val(postal_code);

													/* var country_id = $('#country_id').val();
						
													if(country_id)
													{
														alert("1");
														currentselectState(country_id);
														$('select#permenant_country_id option[value='+country_id+']').attr('selected','selected');
													}

													var state_id = $('#state_id').val();		
													if(state_id)
													{alert("2");
														$('select#permenant_state_id option[value='+state_id+']').attr('selected','selected');
													}
													 */
												}	
											});


											$(document).ready(function()
											{
												$('input[name="chk_shipping_address"]').click(function()
												{
													if( $(this).prop("checked") == true ) //checked
													{
														$('#permenant_address1').attr('readonly',true);
														$('#permenant_address2').attr('readonly',true);
														$('#permenant_address3').attr('readonly',true);
														$('#permenant_postal_code').attr('readonly',true);
						
														$("#permenant_country_id").css({"pointer-events": "none","background-color": "#fafafa"});
														$("#permenant_state_id").css({"pointer-events": "none","background-color": "#fafafa"});
														$("#permenant_city_id").css({"pointer-events": "none","background-color": "#fafafa"});

														$('#permenant_address1').val( $('#address1').val() );
														$('#permenant_address2').val( $('#address2').val() );
														$('#permenant_address3').val( $('#address3').val() );
														$('#permenant_postal_code').val( $('#postal_code').val() );

														var country_id = $('#country_id').val();

														if(country_id)
														{
															var company_country1 = $('select#country_id option:selected').sort().clone();
															$('select#permenant_country_id').append( company_country1 );
														}
														else
														{
															selectAjaxCountry();
															//$('#permenant_country_id').html("<option value=''>- Select -</option>");
														}
														
														var permenant_country_id = $('#permenant_country_id').val();
														
														var state_id = $('#state_id').val();

														if(state_id)
														{
															var company_state_id = $('select#state_id option:selected').sort().clone();
															$('select#permenant_state_id').append( company_state_id );
														}
														else
														{
															$('#permenant_state_id').html("<option value=''>- Select -</option>");
														}
														
														var district_id = $('#district_id').val();
														var company_district_id = $('select#district_id option:selected').sort().clone();
														$('select#permenant_district_id').append( company_district_id );

														var city_id = $('#city_id').val();
														
														if(city_id)
														{
															var company_city_id = $('select#city_id option:selected').sort().clone();
															$('select#permenant_city_id').append( company_city_id );
														}
														else
														{
															$('#permenant_city_id').html("<option value=''>- Select -</option>");
														}
														
														if(country_id == permenant_country_id);
														{
															$('select#permenant_country_id option[value='+country_id+']').attr('selected','selected');
														}
														
														if(state_id !='')
														{
															$('select#permenant_state_id option[value='+state_id+']').attr('selected','selected');
														}
														else
														{
															$('#permenant_state_id').html("<option value=''>- Select -</option>");
														}

														/* if(district_id !='')
														{
															$('select#permenant_district_id option[value='+district_id+']').attr('selected','selected');
														}
														else
														{
															$('#permenant_district_id').html("<option value=''>- Select -</option>");
														} */

														if(city_id !='')
														{
															$('select#permenant_city_id option[value='+city_id+']').attr('selected','selected');
														}
														else
														{
															$('#permenant_city_id').html("<option value=''>- Select -</option>");
														}
													}
													else if( $(this).prop("checked") == false ) //Unchecked
													{
														$('#permenant_address1').removeAttr('readonly',true);
														$('#permenant_address2').removeAttr('readonly',true);
														$('#permenant_address3').removeAttr('readonly',true);
														$('#permenant_postal_code').removeAttr('readonly',true);

														$("#permenant_country_id").css({"pointer-events": "auto","background-color": "#fff"});
														$("#permenant_state_id").css({"pointer-events": "auto","background-color": "#fff"});
														$("#permenant_city_id").css({"pointer-events": "auto","background-color": "#fff"});

														/* var permenant_country_id = $('#permenant_country_id').val();
														$("select#permenant_country_id option[value='"+permenant_country_id+"']:last").remove();
															*/
														/* $( "#permenant_state_id").html('<option value="">- Select -</option>');
														$( "#permenant_district_id").html('<option value="">- Select -</option>');
														$( "#permenant_city_id").html('<option value="">- Select -</option>'); */
														
														/* $('#permenant_address').val('');
														$('#permenant_postal_code').val('');
														$('#permenant_address1').val('');
														$('#permenant_address2').val('');
														$('#permenant_address3').val(''); */
														
													}
												});

												function selectAjaxCountry()
												{
													$.ajax({
														type: "POST",
														url:"<?php echo base_url().'admin/ajaxSelectCountry';?>",
														data: { id: 1 }
													}).done(function( msg ) 
													{
														if(msg == "no_date_found")
														{
															$("#permenant_country_id").html("<option value=''>- Select -</option>");
														}
														else
														{
															$( "#permenant_country_id").html(msg);
														}
													});
												}

											});
										</script>

										<!-- Permanent Address Start -->
										<div class="col-md-6">
											<div class="new-design-2">
												<span>Permanent Address</span>
												<?php 
													$checked_shipping_address ="";
													$addressReadonly = "";
													$dropdownReadonly = "";
													if( isset($edit_data[0]['chk_shipping_address']) && $edit_data[0]['chk_shipping_address'] == 1 )
													{
														$checked_shipping_address ="checked='checked'";
														$addressReadonly = "readonly";
														$dropdownReadonly = "pointer-events: none";
													} 
												?>
												&nbsp; &nbsp; <input type="checkbox" name="chk_shipping_address" value='1' id="chk_shipping_address" <?php echo $checked_shipping_address;?>>&nbsp; &nbsp; <span style="color:#c7c7ce;font-size: 11px;">Copy Current Address</span></span>
											</div>

											<div class="row mt-3">
												<div class="form-group col-md-3">
													<label class="col-form-label">Address 1</label>
												</div>
												<div class="form-group col-md-6">
													<textarea name="permenant_address1" rows="1" <?php echo $addressReadonly; ?> id="permenant_address1"  class="form-control single_quotes" autocomplete="off"><?php echo isset($edit_data[0]['permenant_address1']) ? $edit_data[0]['permenant_address1'] :"";?></textarea>
												</div>
											</div>

											<div class="row">
												<div class="form-group col-md-3">
													<label class="col-form-label">Address 2</label>
												</div>
												<div class="form-group col-md-6">
													<textarea name="permenant_address2" rows="1" <?php echo $addressReadonly; ?> id="permenant_address2"  class="form-control single_quotes" autocomplete="off"><?php echo isset($edit_data[0]['permenant_address2']) ? $edit_data[0]['permenant_address2'] :"";?></textarea>
												</div>
											</div>

											<div class="row">
												<div class="form-group col-md-3">
													<label class="col-form-label">Address 3</label>
												</div>
												<div class="form-group col-md-6">
													<textarea name="permenant_address3" rows="1" <?php echo $addressReadonly; ?> id="permenant_address3"  class="form-control single_quotes" autocomplete="off"><?php echo isset($edit_data[0]['permenant_address3']) ? $edit_data[0]['permenant_address3'] :"";?></textarea>
												</div>
											</div>

											<div class="row">
												<div class="form-group col-md-3">
													<label class="col-form-label">Country</label>
												</div>
												<div class="form-group col-md-6">
													<select name="permenant_country_id" <?php echo $addressReadonly; ?> style='<?php echo $dropdownReadonly;?>' id="permenant_country_id" onchange="currentselectState(this.value);" class="form-control <?php #echo $searchDropdown;?>"> <!--selectboxit-->
														<option value="">- Select -</option>
														<?php 
															foreach($country as $row)
															{
																$selected="";
																if(isset($edit_data[0]['permenant_country_id']) && $edit_data[0]['permenant_country_id'] == $row['country_id']){
																	$selected="selected='selected'";
																}
																?>
																<option value="<?php echo $row['country_id'];?>" <?php echo $selected;?>><?php echo $row['country_name'];?></option>
																<?php 
															} 
														?>
													</select>
												</div>
											</div>

											<div class="row">
												<div class="form-group col-md-3">
													<label class="col-form-label">State </label>
												</div>
												<div class="form-group col-md-6">
													<select name="permenant_state_id" <?php echo $addressReadonly; ?> style='<?php echo $dropdownReadonly;?>' id="permenant_state_id" onchange="currentselectCityorTown(this.value);" class="form-control <?php #echo $searchDropdown;?>"> <!--selectboxit-->
														<option value="">- Select -</option>
														<?php 
															if($type == "edit" || $type == "view")
															{
																$state = $this->db->get_where('geo_states', array('active_flag' => 'Y','country_id' => $edit_data[0]['permenant_country_id']))->result_array();
																
																foreach($state as $row)
																{
																	$selected="";
																	if(isset($edit_data[0]['permenant_state_id']) && $edit_data[0]['permenant_state_id'] == $row['state_id']){
																		$selected="selected='selected'";
																	}
																	?>
																	<option value="<?php echo $row['state_id'];?>" <?php echo $selected;?>><?php echo $row['state_name'];?></option>
																	<?php 
																}
															}
														?>
													</select>
												</div>
											</div>
											
											<div class="row">
												<div class="form-group col-md-3">
													<label class="col-form-label">City </label>
												</div>
												<div class="form-group col-md-6">
													<select name="permenant_city_id" <?php echo $addressReadonly; ?> style='<?php echo $dropdownReadonly;?>' id="permenant_city_id" --onchange="selectCityorTown(this.value);" class="form-control <?php #echo $searchDropdown;?>" > <!--selectboxit-->
														<option value="">- Select -</option>
														<?php 
															if($type == "edit" || $type == "view")
															{
																$city = $this->db->get_where('geo_cities', array('active_flag' => 'Y','state_id' => $edit_data[0]['permenant_state_id']))->result_array();
																
																foreach($city as $row)
																{
																	$selected="";
																	if( isset($edit_data[0]['permenant_city_id']) && $edit_data[0]['permenant_city_id'] == $row['city_id'])
																	{
																		$selected="selected='selected'";
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
													<label class="col-form-label">Postal Code</label>
												</div>
												<div class="form-group col-md-6">
													<input type="text" name="permenant_postal_code" <?php echo $addressReadonly; ?> minlength="6" maxlength="6" id="permenant_postal_code" autocomplete="off" class="form-control mobile_vali" value="<?php echo isset($edit_data[0]['permenant_postal_code']) ? $edit_data[0]['permenant_postal_code'] :"";?>" placeholder="">
												</div>
											</div>
											
										</div>
										<!-- Permanent  Address end -->

									</div>
								</fieldset>

								<input type="hidden" name="address1" id="address1" autocomplete="off" class="form-control mobile_vali" value="<?php echo isset($edit_data[0]['address1']) ? $edit_data[0]['address1'] :"";?>" placeholder="">
							
								<div class="d-flexad pb-3 pr-3" style="text-align:right;">
									<a href="<?php echo base_url(); ?>employee/ManageEmployee" class="btn btn-light">Close</a>
									<a href="<?php echo base_url(); ?>employee/ManageEmployee/<?php echo $type;?>/id-info/<?php echo $status;?>" class="btn btn-light">Back</a>
									<?php 
										if( $type == "view" )
										{
											
										}
										else
										{
											?>
											<input type="submit" name="save_only" value="Save" class="btn btn-primary btn-sm register-but">
											<?php 
										} 
									?>
								</div>
							</form>
							<?php
						}
						else if(isset($id) && $id == 'bank-info')
						{
							?>
							<form action="" class="form-validate-jquery" --id="formValidation" enctype="multipart/form-data" method="post">
								<legend class="text-uppercase font-size-sm font-weight-bold">
									<div class="row">
										<div class="col-md-6">
											Add Bank Detail
										</div>
										<?php 
											if( $type == "view" )
											{
												
											}
											else
											{
												?>
												<div class="col-md-6 text-right">
													<input type="submit" name="save_close" value="Save & Close" class="btn btn-primary ml-1 btn-sm register-but">
												</div>
												<?php 
											} 
										?>
									</div>
								</legend>
								
								<fieldset <?php echo $fieldSetDisabled;?>>
									<div class="row">
										<div class="col-md-6">
											<div class="row">	
												<div class="form-group col-md-3">
													<label class="col-form-label">A/c No  <span class="text-danger">*</span> </label>
												</div>
												<div class="form-group col-md-6">
													<input type="text" name="account_number" id="account_number" required class="form-control mobile_vali single_quotes" autocomplete="off" value="<?php echo isset($edit_data[0]['account_number']) ? $edit_data[0]['account_number'] :"";?>" placeholder="">
												</div>
											</div>
											<!--<div class="row">
												<div class="form-group col-md-3">
													<label class="col-form-label">A/c Holder Name<span class="text-danger">*</span> </label>
												</div>
												<div class="form-group col-md-6">
													<input type="text" name="account_name" id="account_name" required <?php echo $this->validation; ?> class="form-control" autocomplete="off" value="<?php echo $edit_data[0]['account_name'];?>" placeholder="">
												</div>
											</div>-->
											<div class="row">
												<div class="form-group col-md-3">
													<label class="col-form-label">A/c Holder Name <span class="text-danger">*</span></label>
												</div>
												<div class="form-group col-md-6">
													<input type="text" name="account_name" id="account_name" required autocomplete="off" class="form-control single_quotes" value="<?php echo isset($edit_data[0]['account_name']) ? $edit_data[0]['account_name'] :"";?>" placeholder="">
												</div>
											</div>
											<div class="row">
												<div class="form-group col-md-3">
													<label class="col-form-label">Bank Name <span class="text-danger">*</span></label>
												</div>
												<div class="form-group col-md-6">
													<input type="text" name="bank_name" id="bank_name" required autocomplete="off" class="form-control single_quotes" value="<?php echo isset($edit_data[0]['bank_name']) ? $edit_data[0]['bank_name'] :"";?>" placeholder="">
												</div>
											</div>
											<div class="row">
												<div class="form-group col-md-3">
													<label class="col-form-label">Bank Branch </label>
												</div>
												<div class="form-group col-md-6">
													<input type="text" name="branch_name" id="branch_name" class="form-control single_quotes" autocomplete="off" value="<?php echo isset($edit_data[0]['branch_name']) ? $edit_data[0]['branch_name'] :"";?>" placeholder="">
												</div>
											</div>
											<div class="row">
												<div class="form-group col-md-3">
													<label class="col-form-label">IFSC Code <span class="text-danger">*</span> </label>
												</div>
												<div class="form-group col-md-6">
													<input type="text" name="ifsc_code" id="ifsc_code" minlength="11" maxlength="11" required class="form-control dev_name" autocomplete="off" value="<?php echo isset($edit_data[0]['ifsc_code']) ? $edit_data[0]['ifsc_code'] :"";?>" placeholder="">
													<span class="small" style="color:#a19f9f;float:left;width:100%;">(Ex : IDIB000A114)</span>
												</div>
												<div class="form-group col-md-3">
													<span class="ifsc_error"></span>
												</div>
											</div>

											<style>
												span.ifsc_error {
													color: red;
												}
											</style>
											<script type="text/javascript"> 
											/* if(response == 1)
											{
												$(".channge_pwd_btn").removeAttr("disabled", "disabled=disabled");
												$(".password_mismatched").html('');
											}
											else if(response == 2)
											{
												$(".password_mismatched").html('Password Mismatch!');
												$(".channge_pwd_btn").attr("disabled", "disabled=disabled");
											} */

												$(document).ready(function()
												{     
													$('#ifsc_code').on('input', function()
													{    
														var ifsc_code = $('#ifsc_code').val();  
														
														if(ifsc_code)
														{
															var ifsc_length = $('#ifsc_code').val().length;
															if(ifsc_length == 11)
															{
																var reg = "^[A-Z]{4}[0][A-Z0-9]{6}$";
																if (ifsc_code.match(reg)) 
																{    
																	$(".channge_pwd_btn").removeAttr("disabled", "disabled=disabled");
																	$(".ifsc_error").html("");
																	return true;
																}    
																else 
																{   
																	$(".channge_pwd_btn").attr("disabled", "disabled=disabled");
																	$(".ifsc_error").html("Invalid IFSC Code");
																	return false;    
																} 
															}
															else
															{
																$(".channge_pwd_btn").attr("disabled", "disabled=disabled");
																$(".ifsc_error").html("Invalid IFSC Code");
																return false;
															}
														}
														else
														{
															$(".ifsc_error").html("");
														}
													});      
												});
											</script>
											
											<div class="row">
												<div class="form-group col-md-3">
													<label class="col-form-label">MICR Code </label>
												</div>
												<div class="form-group col-md-6">
													<input type="text" name="micr_code" id="micr_code" class="form-control mobile_vali" autocomplete="off" value="<?php echo isset($edit_data[0]['micr_code']) ? $edit_data[0]['micr_code'] :"";?>" placeholder="">
													<span class="small" style="color:#a19f9f;float:left;width:100%;">(Ex : 600019003)</span>
												</div>
											</div>
											<div class="row">											
												<div class="form-group col-md-3">
													<label class="col-form-label">Address </label>
												</div>
												<div class="form-group col-md-6">
													<textarea name="address" id="address" class="form-control" value=""><?php echo isset($edit_data[0]['bank_address']) ? $edit_data[0]['bank_address'] :"";?></textarea>
												</div>
											</div>
										</div>
									</div>
								</fieldset>
								
								<div class="d-flexad text-right">
									<a href="<?php echo base_url(); ?>employee/ManageEmployee" class="btn btn-light">Cancel</a>
									<a href="<?php echo base_url(); ?>employee/ManageEmployee/<?php echo $type;?>/address-info/<?php echo $status;?>" class="btn btn-light">Back</a>
									<?php 
										if( $type == "view" )
										{
											
										}
										else
										{
											?>
											<input type="submit" name="save_only" value="Save" class="btn btn-primary btn-sm register-but disabled-class channge_pwd_btn">
											<?php 
										} 
									?>
								</div>
							</form>

							<script type="text/javascript">
								/* $('[data-type="adhaar-number"]').keyup(function() 
								{
									var value = $(this).val();
									value = value.replace(/\D/g, "").split(/(?:([\d]{4}))/g).filter(s => s.length > 0).join("-");
									$(this).val(value);

									aadhaar_number_val = value;
									$.ajax({
										url: '<?php echo base_url();?>employee/aadhaarUnique',
										type: 'post',
										data: {
											'aadhaar_number' : aadhaar_number_val,
											'type'		 : '<?php echo $type ?>',
											<?php
												if ($type == "edit") 
												{
													?>
													'id' : '<?php echo $status ?>'
													<?php
												}
											?>
										},
										success: function(response)
										{
											if (response == 'already_taken') 
											{
												$('#aadhaar_number_val').html('Aadhaar Number Alredy Taken');
												// Obj.focus();
												$('.register-but').prop('disabled',true);
												return false;
											}else
											{
												$('#aadhaar_number_val').html('(Ex : 4891-1846-5046)');
												$('.register-but').prop('disabled',false);
												return true;
											}
										}
									});
								}); */

								function ValidatePAN() 
								{ 
									var Obj = document.getElementById("textPanNo");
									if (Obj.value != "") 
									{
										ObjVal = Obj.value;
										var panPat = /^([a-zA-Z]{5})(\d{4})([a-zA-Z]{1})$/;
										if (ObjVal.search(panPat) == -1) 
										{
											alert("Please Enter Valid PAN NO.");
											$('#textPanNo').val('');
											//Obj.focus();
											return false;
										}
										else
										{
											pan_number = $('#textPanNo').val();
											$.ajax({
												url: '<?php echo base_url();?>employee/panUnique',
												type: 'post',
												data: {
													'pan_number' : pan_number,
													'type'		 : '<?php echo $type ?>',
													<?php
														if ($type == "edit") 
														{
															?>
															'id'  : '<?php echo $id ?>'
															<?php
														}
													?>
												},
												success: function(response)
												{
													if (response == 'already_taken') 
													{
														$('#pan_number_val').html('PAN Number Alredy Taken');
														//Obj.focus();
														$('.register-but').prop('disabled',true);
														return false;
													}
													else
													{
														$('#pan_number_val').html('(Ex : ABCDE1234F)');
														$('.register-but').prop('disabled',false);
														return true;
													}
												}
											});

										}
									}
									else
									{
										$('#pan_number_val').html('(Ex : ABCDE1234F)');
									}
								} 
					
								$('#mobile_number').blur(function () 
								{
									mobile_number = $(this).val();

									$.ajax({
										url: '<?php echo base_url();?>vendor/MobileExist',
										type: 'post',
										data: {
											'mobile_number' : mobile_number,
											<?php
												if ($type == "edit") 
												{
													?>
														'id'		 : '<?php echo $id ?>'
													<?php
												}
											?>
										},
										success: function(response)
										{
											if (response == 'taken') 
											{
												$('.mobile_number_exist').html('Mobile Number Alredy Taken');
												$('.register-but').prop('disabled',true);
												return false;
											}else
											{
												$('.mobile_number_exist').html('');
												$('.register-but').prop('disabled',false);
												return true;
											}
										}
									});
								});

								$('#email').blur(function () 
								{
									email = $(this).val();

									$.ajax({
										url: '<?php echo base_url();?>employee/EmailExist',
										type: 'post',
										data: {
											'email' : email,
											<?php
												if ($type == "edit") 
												{
													?>
													'id': '<?php echo $id ?>'
													<?php
												}
											?>
										},
										success: function(response)
										{
											if (response == 'taken') 
											{
												$('.employee_email_exist_error').html('Email Alredy Taken');
												$('.register-but').prop('disabled',true);
												$(this).focus();
												return false;
											}else
											{
												$('.employee_email_exist_error').html('');
												$('.register-but').prop('disabled',false);
												return true;
											}
										}
									});
								});
								
								$('[data-type="passport-number"]').keyup(function() 
								{
									var passport = $(this).val();
									if (passport == "") {
										return;
									}
									var regsaid = /[a-zA-Z]{1}[0-9]{6}/;
	
									if(regsaid.test(passport) == false)
									{
										document.getElementById("passport_number_error").innerHTML = "Passport is valid.";
										$('.register-but').prop('disabled',true);
									}
									else
									{
										document.getElementById("passport_number_error").innerHTML = "";
										$('.register-but').prop('disabled',false);
									}

								});
							</script>
							<?php
						}	
					?>
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
								if($employeesMenu['create_edit_only'] == 1 || $this->user_id == 1)
								{
									?>
									<a href="<?php echo base_url(); ?>employee/ManageEmployee/add/basic-info" class="btn btn-info btn-sm">
										Create Employee
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
							<div class="col-md-4">
								<div class="row">
									<label class="col-form-label col-md-4">Employee Name <!-- <span class="text-danger">*</span> --></label>
									<div class="form-group col-md-8">
										<?php 
											$empQry = "select person_id,employee_number,first_name,last_name from per_people_all 
														
														order by employee_number asc";

											$getEmployee = $this->db->query($empQry)->result_array();	
										?>
										<select name="person_id" id="person_id" --required class="form-control searchDropdown">
											<option value="">- Select -</option>
											<?php 
												foreach($getEmployee as $row)
												{
													$selected="";
													if(isset($_GET['person_id']) && $_GET['person_id'] == $row["person_id"] )
													{
														$selected="selected='selected'";
													}
													?>
													<option value="<?php echo $row["person_id"];?>" <?php echo $selected;?>><?php echo ucfirst($row["employee_number"]);?> | <?php echo ucfirst($row["first_name"]);?> <?php echo ucfirst($row["last_name"]);?></option>
													<?php 
												} 
											?>
										</select>
									</div>
								</div>
							</div>

							<div class="col-md-4">
								<div class="row">
									<label class="col-form-label col-md-4">Mobile Number</label>
									<div class="form-group col-md-7">
										<input type="search" name="mobile_number" maxlength="10" class="form-control mobile_vali" value="<?php echo !empty($_GET['mobile_number']) ? $_GET['mobile_number'] :""; ?>" placeholder="Mobile Number" autocomplete="off">
									</div>
								</div>
							</div>

							<div class="col-md-3">
								<div class="row">
									<label class="col-form-label col-md-3">Status</label>
									<div class="form-group col-md-9">
										<?php 
											$activeStatus = $this->common_model->lov('ACTIVESTATUS'); 
										?>
										
										<select name="active_flag" id="active_flag" class="form-control searchDropdown">
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

						<div class="row mt-2">
							<div class="col-md-8"></div>
							
							<div class="col-md-4 text-right">
								<button type="submit" class="btn btn-info">Search <i class="fa fa-search" aria-hidden="true"></i></button>&nbsp;
								<a href="<?php echo base_url(); ?>employee/ManageEmployee" title="Clear" class="btn btn-default">Clear</a>
							
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
								<table id="myTable" class="table table-bordered table-hover  dataTable --sortable-table">
									<thead>
										<tr>
											<th class="sticky-col-tab" style="text-align:center;width:12%;">Controls</th>
											<th>Employee Number</th>
											<th >Employee Name</th>
											<th>Date of Birth</th>
											<th>Date of Joining</th>
											<th>E-Mail</th>
											<th>Mobile Number</th>
											<th>Created Date</th>
											<th class="text-center" style="width:10%;">Status</th>
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
														<div class="dropdown controls-actions">
															<button type="button" class="btn btn-outline-info gropdown-toggle waves-effect waves-light btn-sm" data-toggle="dropdown" aria-expanded="false" style="width: 70px;">
																Action <i class="fa fa-angle-down"></i>
															</button>
															<ul class="table-dropdown dropdown-menu dropdown-menu-right dropdown-menu-new">
																<?php
																	if((isset($employeesMenu['create_edit_only']) && $employeesMenu['create_edit_only'] == 1) || $this->user_id == 1)
																	{
																		?>
																		<li>
																			<a title="Edit" href="<?php echo base_url(); ?>employee/ManageEmployee/edit/basic-info/<?php echo $row['person_id'];?>">
																				<i class="fa fa-pencil"></i> Edit
																			</a>
																		</li>
																		<?php 
																	} 
																?>

																<?php
																	if((isset($employeesMenu['read_only']) && $employeesMenu['read_only'] == 1) || $this->user_id == 1)
																	{
																		?>
																		<li>
																			<a title="View" href="<?php echo base_url(); ?>employee/ManageEmployee/view/basic-info/<?php echo $row['person_id'];?>">
																				<i class="fa fa-eye"></i> View
																			</a>
																		</li>
																		<?php 
																	} 
																?>
																
																<?php 
																	if((isset($employeesMenu['read_only']) && $employeesMenu['read_only'] == 1) || $this->user_id == 1)
																	{
																		?>
																		<li>
																			<?php 
																				if($row['active_flag'] == $this->active_flag)
																				{
																					?>
																					<a href="<?php echo base_url(); ?>employee/ManageEmployee/status/<?php echo $row['person_id'];?>/N" title="Active">
																						<i class="fa fa-ban"></i> Inactive
																					</a>
																					<?php 
																				} 
																				else
																				{  ?>
																					<a href="<?php echo base_url(); ?>employee/ManageEmployee/status/<?php echo $row['person_id'];?>/Y" title="InActive">
																						<i class="fa fa-check"></i> Active
																					</a>
																					<?php 
																				}
																			?>
																		</li>
																		<?php 
																	}
																?>
															</ul>
														</div>
														<!-- Modal End-->
													</td>
													
													<td>
														ORD-<?php echo rand().time();?></td>
													
													<td>
														<?php echo ucfirst($row['first_name'])." ".ucfirst($row['last_name']);?>
													</td>
													
													<td >
														<?php echo date(DATE_FORMAT,strtotime($row['date_of_birth']));?>
													</td>

													<td>
														<?php 
															if($row['date_of_joining'] != NULL && !empty($row['date_of_joining']))
															{
																echo date(DATE_FORMAT,strtotime($row['date_of_joining']));
															}
														?>
													</td>

													<td><?php echo $row['email_address'];?></td>

													<td>
														<?php 
															echo !empty($row['country_code']) ? $row['country_code']."-" : "";
															echo $row['mobile_number'];
														?>
													</td>
													
													<td>
														<?php echo date(DATE_FORMAT,strtotime($row['created_date']));?>
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
										<div class="col-md-12 float-left text-center"> 
											<img src="<?php echo base_url(); ?>uploads/nodata.png">
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
		</div>
	</div><!-- Card end-->
</div><!-- Content end-->

<!-- View Popup Image-->
<script type="text/javascript">  
	$('document').ready(function()
	{
		//Customer E-mail Start here
		$(".register-but").removeClass("disabled-class");
		
		var emp_email_state = false;

		$('#email').on('input', function()
		{
			var email = $('#email').val();
			
			if (email == '') 
			{
				emp_email_state = false;
				return;
			}
			else
			{
				var type = '<?php echo $type;?>';
				if(type == 'add')
				{
					var id = 0;
				}
				else
				{
					var id = '<?php echo $id; ?>';
				}
				
				$.ajax({
					url: '<?php echo base_url();?>employee/EmailExist',
					type: 'post',
					data: {
						'email_check' : 1,
						'email' : email,
						'id' : id,
					},
					success: function(response)
					{
						if (response == 'taken' ) 
						{
							emp_email_state = false;
							
							/* $('.form-control.email').removeClass("valid");
							$('.form-control.email').addClass("error");
							
							$(".form-control.email").attr("aria-required", "true");
							$(".form-control.email").attr("aria-describedby", "email-error");
							$(".form-control.email").attr("aria-invalid", "true"); */
							
							$(".employee_email_exist_error").addClass("error");
							$(".employee_email_exist_error").attr("id", "email-error");
							$(".employee_email_exist_error").attr("style", "display: inline;");
							
							$(".register-but").attr("disabled", "disabled=disabled");
							$(".register-but").addClass("disabled-class");
							$('.employee_email_exist_error').html('Sorry... Email already taken');
							
							return false;
						}
						else if (response == 'not_taken') 
						{
							$(".employee_email_exist_error").attr("style", "display: none;");
							$(".register-but").removeAttr("disabled", "disabled=disabled");
							$(".register-but").removeClass("disabled-class");
							return true;
						}
					}
				});
			}
		});
		//Customer E-mail End here
	});
</script>

<!--Employee Phone-->
<script type="text/javascript">  
	$('document').ready(function()
	{
		//Customer E-mail Start here
		$(".register-but").removeClass("disabled-class");
		
		var emp_email_state = false;

		$('#mobile_number').on('input', function()
		{
			var email = $('#mobile_number').val();
			
			if (email == '') 
			{
				emp_mob_state = false;
				return;
			}
			else
			{
				$.ajax({
					url: '<?php echo base_url();?>employee/MobileExist',
					type: 'post',
					data: {
						'mob_check' : 1,'email' : email,
					},
					success: function(response)
					{
						if (response == 'taken' ) 
						{
							emp_mob_state = false;
							
							/* $('.form-control.email').removeClass("valid");
							$('.form-control.email').addClass("error");
							
							$(".form-control.email").attr("aria-required", "true");
							$(".form-control.email").attr("aria-describedby", "email-error");
							$(".form-control.email").attr("aria-invalid", "true"); */
							
							$(".mobile_number_exist").addClass("error");
							$(".mobile_number_exist").attr("id", "email-error");
							$(".mobile_number_exist").attr("style", "display: inline;");
							
							$(".register-but").attr("disabled", "disabled=disabled");
							$(".register-but").addClass("disabled-class");
							$('.mobile_number_exist').html('Sorry... Mobile Number already taken');
							
							return false;
						}
						else if (response == 'not_taken') 
						{
							$(".mobile_number_exist").attr("style", "display: none;");
							$(".register-but").removeAttr("disabled", "disabled=disabled");
							$(".register-but").removeClass("disabled-class");
							return true;
						}
					}
				});
			}
		});
		//Customer E-mail End here
	});
</script>
<!--Employee Phone-->


<script type="text/javascript">  
	$('document').ready(function()
	{
		$(".register-but").removeClass("disabled-class");
		
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
					url: '<?php echo base_url();?>employee/UsernameExist',
					//url: '<?php echo base_url();?>admin/EmailExist',
					type: 'post',
					data: {
						'user_name_check' : 1,'user_name' : user_name,
					},
					success: function(response)
					{
						if (response == 'taken' ) 
						{
							user_name_state = false;
							
							$(".user_name_exist_error").addClass("error");
							$(".user_name_exist_error").attr("id", "email-error");
							$(".user_name_exist_error").attr("style", "display: inline;");
							
							$(".register-but").attr("disabled", "disabled=disabled");
							$(".register-but").addClass("disabled-class");
							$('.user_name_exist_error').html('Sorry... User Name already Exist!');
							
							return false;
						}
						else if (response == 'not_taken') 
						{
							
							$(".user_name_exist_error").attr("style", "display: none;");
							$(".register-but").removeAttr("disabled", "disabled=disabled");
							$(".register-but").removeClass("disabled-class");
							return true;
						}
					}
				});
			}
		});
	});
</script>

<script>
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
</script>