<?php
	$employee = accessMenu(employee);
?>
<style>
	.disabled { cursor: not-allowed !important; }
	input[type=checkbox][disabled] {
	outline: 2px solid #339966;
	cursor: not-allowed;
	}
</style>
<!-- Page header start-->
<div class="page-header page-header-light">
	<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
		<div class="d-flex">
			<div class="breadcrumb">
				<a href="<?php echo base_url();?>admin/dashboard" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> <?php echo get_phrase('Home');?></a>
				<a href="<?php echo base_url();?>employee/ManageEmployee" class="breadcrumb-item">
					<?php
						echo $page_title;
					?>
				</a>
			</div>
		</div>
		
		<?php
			if(isset($type) && $type == "add" || $type == "edit")
			{ 
				
			}
			else
			{ 
				if($employee['create_edit_only'] == 1 || $this->user_id == 1)
				{
					?>
					<div class="new-import-btn" style="float:right;">
						<a title="Download CSV" href="<?php echo base_url(); ?>employee/ManageEmployeeBanks/export" class="btn btn-primary">
							<i class="fa fa-download"></i> Download CSV
						</a>
					</div>
					<?php 
				}
			}
		?>
	</div>
</div>
<!-- Page header end-->

	<div class="content"><!-- Content start-->
		<div class="card"><!-- Card start-->
			<!-- <div class="card-header- header-elements-inline">
				<h5 class="card-title"></h5>
			</div> -->
			<div class="card-body">
				<?php
					if(isset($type) && $type == "add" || $type == "edit")
					{
						$getCountry = $this->db->query("select country_id,country_name from country where country_status=1 order by country_name asc")->result_array();
						?>
						<!-- <label class="text-capitalize" style="font-size:18px;color: #13111188;font-weight: 600;"><?php echo $type ?> Employee :</label> -->
						<legend class="text-uppercase font-size-sm font-weight-bold">
							<?php echo $type; ?> Employee :
						</legend>

						<form action="" class="form-validate-jquery" --id="formValidation" enctype="multipart/form-data" method="post">
							<div class="row">
								<!-- left side start -->
								<div class="col-sm-12 col-md-12">
									<!-- Customer Details start -->
									<fieldset>
										<div class="row">
											<?php 
												$getRole = $this->db->query("select role_name, role_id from org_roles where role_status=1 order by role_name asc")->result_array();
											?>
											<div class="form-group col-md-3">
												<label class="col-form-label">Employee Role<span class="text-danger">*</span></label>
												<a class="quicklink" href="<?php echo base_url();?>roles/manageRoles" target="_blank" title="Employment Role">Add Emp Role</a>
												<select name="role_id" id="role_id" class="form-control searchDropdown" required>
													<option value="">- Select Employee Role -</option>
													<?php 
														foreach($getRole as $row)
														{ 
															$selected="";
															if( isset($edit_data[0]['role_id']) && $edit_data[0]['role_id'] == $row['role_id'])
															{
																$selected="selected='selected'";
															}
															?>
															<option value="<?php echo $row['role_id'];?>" <?php echo $selected;?>><?php echo ucfirst($row['role_name']);?></option>
															<?php 
														} 
													?>
												</select>
											</div>
											
											<?php
												#$random_user_id = otpNumber(6);
												$results = $this->db->query("select increment_id from users where register_type = 1 order by increment_id desc")->result_array();
												
												if( count($results) == 0 )
												{
													$incrementID = 1;
												}
												else
												{
													$incrementID = $results[0]['increment_id'] + 1;
												}
												#$random_user_id = '10000'.$incrementID;
												$random_user_id = 'EMP'.str_pad($incrementID, 5, "0", STR_PAD_LEFT);
											?>
											<div class="form-group col-md-3">
												<label class="col-form-label">Employee No <span class="text-danger">*</span></label>
												<input type="text" name="random_user_id" required class="form-control" value="<?php echo isset($edit_data[0]['random_user_id']) ? $edit_data[0]['random_user_id'] :$random_user_id;?>" placeholder="" autocomplete="off">
											</div>
											
											<div class="form-group col-md-3">
												<label class="col-form-label">Employee First Name<span class="text-danger">*</span></label>
												<input type="text" name="first_name" required  <?php #echo $this->validation; ?> class="form-control" value="<?php echo isset($edit_data[0]['first_name']) ? $edit_data[0]['first_name'] :"";?>" placeholder="" autocomplete="off">
											</div>
											
											<div class="form-group col-md-3">
												<label class="col-form-label">Employee Last Name<span class="text-danger">*</span></label>
												<input type="text" name="last_name" required autocomplete="off" <?php echo $this->validation; ?> class="form-control" value="<?php echo isset($edit_data[0]['last_name']) ? $edit_data[0]['last_name'] :"";?>" placeholder="" autocomplete="off">
											</div>
										</div>
										
										
										<div class="row">
											<div class="form-group col-md-3">
												<label class="col-form-label">Father's First Name</label>
												<input type="text" name="father_first_name"  id="father_first_name" <?php echo $this->validation; ?> class="form-control only_name" value="<?php echo isset($edit_data[0]['father_first_name']) ? $edit_data[0]['father_first_name'] :"";?>" placeholder="" autocomplete="off">
											</div>
											
											<div class="form-group col-md-3">
												<label class="col-form-label">Father's Last Name</label>
												<input type="text" name="father_last_name"  id="father_last_name" autocomplete="off" <?php echo $this->validation; ?> class="form-control only_name" value="<?php echo isset($edit_data[0]['father_last_name']) ? $edit_data[0]['father_last_name'] :"";?>" placeholder="">
											</div>
											
											<div class="form-group col-md-3">
												<label class="col-form-label">Date of Birth<span class="text-danger">*</span></label>
												<input type="text" name="date_of_birth" id="date_of_birth" required class="form-control default_date" autocomplete="off" value="<?php echo isset($edit_data[0]['date_of_birth']) ? $edit_data[0]['date_of_birth'] :"";?>" placeholder="">
											</div>
											
											<div class="form-group col-md-3">
												<label class="col-form-label">Date of Joining<span class="text-danger">*</span></label>
												<input type="text" name="date_of_joining" id="date_of_joining" required class="form-control default_date" autocomplete="off" value="<?php echo isset($edit_data[0]['date_of_joining']) ? $edit_data[0]['date_of_joining'] :"";?>" placeholder="">
											</div>
										</div>
											
											
										<div class="row">
											<div class="form-group col-md-3">
												<label class="col-form-label">Employment Type<span class="text-danger">*</span></label>
												<select name="employeement_type" id="employeement_type" class="form-control searchDropdown" required>
													<option value="">- Select Employment Type -</option>
													<?php 
														foreach($this->employeementType as $key => $value)
														{ 
															$selected="";
															if( isset($edit_data[0]['employeement_type']) && $edit_data[0]['employeement_type'] == $key)
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
											
											<?php 
												$getBranch = $this->db->query("select branch_name, branch_id from branch where branch_status=1 order by branch_name asc")->result_array();
											?>
											<div class="form-group col-md-3">
												<label class="col-form-label">Branch <span class="text-danger">*</span></label>
												<a class="quicklink" target="_blank" href="<?php echo base_url()?>admin/ManageBranches/add" title="Branch"> Add Branch </a>
												<select name="branch_id" id="branch_id" class="form-control searchDropdown"  required>
													<option value="">- Select Branch -</option>
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
											
											<div class="form-group col-md-3">
												<label class="col-form-label">Gender <span class="text-danger">*</span></label>
												<select name="gender" required id="gender" class="form-control searchDropdown">
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
											
											<?php 
												$bloodgroup = $this->db->query("select blood_group_name,blood_group_id from emp_blood_group where blood_group_status=1")->result_array();
											?>
											<div class="form-group col-md-3">
												<label class="col-form-label">Blood Group</label>
												<a class="quicklink" target="_blank" href="<?php echo base_url()?>employee/ManageBloodGroup/add" title="Add Blood Group"> Add Blood Group </a>
												<select name="blood_group_id" id="blood_group_id" class="form-control searchDropdown">
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
										
										<?php 
											$designation = $this->db->query("select designation_name,designation_id from emp_designations where designation_status=1")->result_array();
										?>
										<div class="row">
											<div class="form-group col-md-3">
												<label class="col-form-label">Designation<span class="text-danger">*</span></label>
												<a class="quicklink" target="_blank" href="<?php echo base_url()?>employee/ManageDesignation/add" title="Add Designation">Add Designation</a>
												<select name="designation_id" id="designation_id" class="form-control searchDropdown">
													<option value="">- Select Designation -</option>
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
											<?php 
												$getPositions = $this->db->query("select position_name,position_id from hr_positions where position_status=1")->result_array();
											?>
											<div class="form-group col-md-3">
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
											</div>
											
											<?php 
												$getDepartment = $this->db->query("select department_name,department_id from emp_departments where department_status=1")->result_array();
											?>
											<div class="form-group col-md-3">
												<label class="col-form-label">Department<span class="text-danger">*</span></label>
												<a class="quicklink" target="_blank" href="<?php echo base_url()?>employee/ManageDepartment/add" title="Add Department">Add Department</a>
												<select name="department_id" id="department_id" class="form-control searchDropdown">
													<option value="">- Select Department -</option>
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
											<div class="form-group col-md-3">
												<label class="col-form-label">Aadhar No</label>
												<input type="text" name="aadhaar_number" data-type="adhaar-number" maxlength="14" class="form-control" autocomplete="off" value="<?php echo isset($edit_data[0]['aadhaar_number']) ? $edit_data[0]['aadhaar_number'] :"";?>" placeholder="">
												<span class="small" id="aadhaar_number_val" style="color:red;">(Ex : 4891-1846-5046)</span>
											</div>
										</div>
											
										<div class="row">	
											<div class="form-group col-md-3">
												<label class="col-form-label">PAN Number</label>
												<input type="text" name="pan_number" maxlength="10" id="textPanNo" onblur="ValidatePAN(this);" autocomplete="off" <?php echo $this->validation; ?> class="form-control" value="<?php echo isset($edit_data[0]['pan_number']) ? $edit_data[0]['pan_number'] :"";?>" placeholder="">
												<span class="small" id="pan_number_val" style="color:red;">(Ex : ABCDE1234F)</span>
											</div>
											
											<div class="form-group col-md-3">
												<label class="col-form-label">Passport No</label>
												<input type="text" name="passport_number" id="passport_number" minlength="8" maxlength="10" data-type="passport-number" <?php echo $this->validation; ?> class="form-control" autocomplete="off" value="<?php echo isset($edit_data[0]['passport_number']) ? $edit_data[0]['passport_number'] :"";?>" placeholder="">
												<span id="passport_number_error" class="small" style="color:red;">(Ex : A1234567)</span>
											</div>
											
											<div class="form-group col-md-3">
												<label class="col-form-label">Driving Licence</label>
												<input type="text" name="driving_licence" id="driving_licence" --maxlength="17" --oninput="LicenceNumber(this)" class="form-control" autocomplete="off" value="<?php echo isset($edit_data[0]['driving_licence']) ? $edit_data[0]['driving_licence'] :"";?>" placeholder="">
												<span id="licence_number_val" class="small" style="color:red;">(Ex : TN-0619850034761 )</span>
											</div>
											
											<div class="form-group col-md-3">
												<label class="col-form-label">Voter ID</label>
												<input type="text" name="voter_id" id="voter_id" maxlength="10" onkeyup="voterId(this)" class="form-control" autocomplete="off" value="<?php echo isset($edit_data[0]['voter_id']) ? $edit_data[0]['voter_id'] :"";?>" placeholder="">
												<span class="small" id="voterid_number_val" style="color:red;">(Ex : SRD0676361)</span>
											</div>
										
										</div>
										
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

											});
										</script>
										
										<div class="row">
											<div class="form-group col-md-3">
												<div class="row">
													<div class="col-md-12">
														<label class="col-form-label">Mobile Number<span class="text-danger">*</span></label>
														<div class="row">
															<div class="col-md-3 pr-0">
																<input type="text" name="mobile_country_code" required maxlength="4" id="mobile_country_code" autocomplete="off" class="form-control code-num" value="<?php echo isset($edit_data[0]['mobile_country_code']) ? $edit_data[0]['mobile_country_code'] :"";?>" placeholder="+91">
															</div>
															<div class="col-md-9 pl-0">
																<input type="text" name="mobile_number" id="mobile_number" required autocomplete="off"  minlength="9" maxlength="12" class="form-control mobile_vali code-num1" value="<?php echo isset($edit_data[0]['mobile_number']) ? $edit_data[0]['mobile_number'] :"";?>" placeholder="9632587410">
																<span class="small mobile_number_exist" style="color:red;"></span>
															</div>
														</div>
													</div>
												</div>
											</div>
										
											<div class="form-group col-md-3">
												<div class="row">
													<div class="col-md-12">
														<label class="col-form-label">Alternate Mobile Number </label>
														<div class="row">
															<div class="col-md-3 pr-0">
																<input type="text" name="alter_mobile_country_code" maxlength="4" id="mobile_country_code" autocomplete="off" class="form-control code-num" value="<?php echo isset($edit_data[0]['alter_mobile_country_code']) ? $edit_data[0]['alter_mobile_country_code'] :"";?>" placeholder="+91">
															</div>
															<div class="col-md-9 pl-0">
																<input type="text" name="alternate_contact" id="alternate_contact" autocomplete="off" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" minlength="9" maxlength='12' class="form-control mobile_vali code-num1" value="<?php echo isset($edit_data[0]['alternate_contact']) ? $edit_data[0]['alternate_contact'] :"";?>" placeholder="9632587410">
																<span class="mobile_number_exist"></span>
															</div>
														</div>
													</div>
												</div>
											</div>
											
											<div class="form-group col-md-3">
												<label class="col-form-label">Email<span class="text-danger">*</span></label>
												<input type="email" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,63}$" name="email" id="email" required autocomplete="off" class="form-control" value="<?php echo isset($edit_data[0]['email']) ? $edit_data[0]['email'] :"";?>" placeholder="">
												<span class='small employee_email_exist_error' style="color:red;"></span> 
											</div>

											<div class="form-group col-md-3">
												<label class="col-form-label">Alternate E-Mail</label>
												<input type="email" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,63}$" name="alternate_email" --id="emp_email" autocomplete="off" class="form-control" value="<?php echo isset($edit_data[0]['alternate_email']) ? $edit_data[0]['alternate_email'] :"";?>" placeholder="">
												<?php /* <span class='employee_email_exist_error'></span> */?>
											</div>
										</div>
										
										<!-- start-->
										<div class="row">
											<?php
												$country = $this->db->get_where('country', array('country_status' => '1'))->result_array();
											?>
											
											<div class="form-group col-md-3">
												<label class="col-form-label">Country</label>
												<select name="country_id" onchange="selectState(this.value);" class="form-control searchDropdown"> <!--selectboxit-->
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
											
											<div class="form-group col-md-3">
												<label class="col-form-label">State </label>
												<select name="state_id" id="state_id" onchange="selectCityorTown(this.value);" class="form-control searchDropdown"> <!--selectboxit-->
													<option value="">- First Select Country -</option>
													<?php 
														if($type == "edit")
														{
															$state = $this->db->get_where('state', array('state_status' => '1','country_id' => $edit_data[0]['country_id']))->result_array();
											
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
											
											<div class="form-group col-md-3">
												<label class="col-form-label">City </label>
												<select name="city_id" id="city_id" --onchange="selectCityorTown(this.value);" class="form-control searchDropdown" > <!--selectboxit-->
													<option value="">- First Select State -</option>
													  <?php 
														if($type == "edit")
														{
															$city = $this->db->get_where('city', array('city_status' => '1','state_id' => $edit_data[0]['state_id']))->result_array();
											
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
											<div class="form-group col-md-3">
												<label class="col-form-label">Address</label>
												<textarea name="address" rows="1" id="address"  class="form-control" autocomplete="off"><?php echo isset($edit_data[0]['address']) ? $edit_data[0]['address'] :"";?></textarea>
											</div>
										</div>
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
													});
												}
												else 
												{ 
													alert("No city/town's under this district!");
												}
											}
										</script>
										<!-- end-->
										
										<div class="row">
											<div class="form-group col-md-3">
												<label class="col-form-label">Postal Code</label>
												<input type="text" name="postal_code" id="postal_code" autocomplete="off" class="form-control" value="<?php echo isset($edit_data[0]['postal_code']) ? $edit_data[0]['postal_code'] :"";?>" placeholder="">
											</div>
											
											<div class="form-group col-md-3">
												<label class="col-form-label">Date of Leaving</label>
												<input type="text" name="date_of_leaving" id="date_of_leaving" autocomplete="off" class="form-control default_date" value="<?php echo isset($edit_data[0]['date_of_leaving']) ? $edit_data[0]['date_of_leaving'] :"";?>" placeholder="">
											</div>
											
											<div class="form-group col-md-3">
												<label class="col-form-label">Year of experience</label>
												<input type="text" name="year_of_experience" id="year_of_experience" autocomplete="off" class="form-control" value="<?php echo isset($edit_data[0]['year_of_experience']) ? $edit_data[0]['year_of_experience'] :"";?>" placeholder="">
											</div>
											
											<div class="form-group col-md-3">
												<label class="col-form-label">Annual CTC</label>
												<input type="text" name="annual_ctc" id="annual_ctc" autocomplete="off" class="form-control" value="<?php echo isset($edit_data[0]['annual_ctc']) ? $edit_data[0]['annual_ctc'] :"";?>" placeholder="">
											</div>
										</div>
										
										<div class="row">
											<div class="form-group col-md-3">
												<label class="col-form-label">Pay frequency</label>
												<select name="pay_frequency" id="pay_frequency" class="form-control searchDropdown" > <!--selectboxit-->
													<option value="">- Select Pay Frequency -</option>
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
											
											<div class="form-group col-md-3">
												<label class="col-form-label">Profile Image</label>
												<input type="file" name="profile_image" id="profile_image"class="form-control" placeholder="">
												<?php
													if($type="edit")
													{
														if(file_exists("uploads/profile_image/".$id.'.png') )
														{
															
															?>
															<img class="img-responsive mt-2" alt="" style="border-radius:4px;width:75px;height:75px;" src="<?php echo base_url(); ?>uploads/profile_image/<?php echo $id.'.png';?>">
															<?php 
														}
													}
												?>
											</div>
											
											<div class="form-group col-md-3">
												<label class="col-form-label">PF No.</label>
												<input type="text" name="pf_number" id="pf_number" autocomplete="off" class="form-control" value="<?php echo isset($edit_data[0]['pf_number']) ? $edit_data[0]['pf_number'] :"";?>" placeholder="">
											</div>
											
											<div class="form-group col-md-3">
												<label class="col-form-label">ESI No.</label>
												<input type="text" name="esi_number" id="esi_number" autocomplete="off" class="form-control" value="<?php echo isset($edit_data[0]['esi_number']) ? $edit_data[0]['esi_number'] :"";?>" placeholder="">
											</div>
										</div>
									</fieldset>
									<?php /*
									<fieldset class="mb-3">
										<legend class="text-uppercase font-size-sm font-weight-bold">Bank Details</legend>
										<!-- <label class="text-capitalize" style="font-size:18px;color: #13111188;font-weight: 600;">bank details:</label> -->
										<div class="row">
											<div class="form-group col-md-3">
												<label class="col-form-label"> Bank A/c No </label>
												<input type="text" name="bank_account_no" --required autocomplete="off" class="form-control mobile_vali" maxlength="15" value="<?php echo isset($edit_data[0]['bank_account_no']) ? $edit_data[0]['bank_account_no'] :"";?>" placeholder="">
											</div>
											
											<div class="form-group col-md-3">
												<label class="col-form-label">Bank Name </label>
												<input type="text" name="bank_name" --required autocomplete="off" class="form-control only_name" value="<?php echo isset($edit_data[0]['bank_name']) ? $edit_data[0]['bank_name'] :"";?>" placeholder="">
											</div>
											
											<div class="form-group col-md-3">
												<label class="col-form-label">IFSC Code</label>
												<input type="text" name="ifsc_code" --required id="ifsc_code" autocomplete="off" class="form-control special_vali" value="<?php echo isset($edit_data[0]['ifsc_code']) ? $edit_data[0]['ifsc_code'] :"";?>" placeholder="">
												<span class="small note-color">(Ex : IDIB000A114)</span>
											</div>
										</div>
										
										<div class="row">
											<div class="form-group col-md-3">
												<label class="col-form-label">Branch</label>
												<input type="text" name="branch" --required class="form-control only_name" value="<?php echo isset($edit_data[0]['branch']) ? $edit_data[0]['branch'] :"";?>" placeholder=""  autocomplete="off">
											</div>
										</div>
									</fieldset>
									*/ ?>

									<fieldset> 
										<div class="row">
											<?php 
												/*$required ="";
												if ($type =="add") 
												{
													$required = 'required';
												}*/
											?>
											<div class="col-12">
												<fieldset class="mb-3">
													<!-- <label class="text-capitalize" style="font-size:18px;color: #13111188;font-weight: 600;">Upload Documents:</label> -->
													<!-- <legend class="text-uppercase font-size-sm font-weight-bold">
														Upload Documents:
													</legend> -->
													<div class="row">
														<?php /* <div class="form-group col-md-3">
															<label class="col-form-label">Identity Proof<span class="text-danger">*</span></label>
															<input type="file" name="identity_proof" id="identity_proof" accept="image/*" <?php echo $required ?>   onchange="return validateSingleFileExtension(this)" autocomplete="off" <?php echo $this->validation; ?> class="form-control singleImage" >
															<span class="note-class"><b>Note</b> : Proof upload size is 1 [MB] and image format is (png,gif,jpg,jpeg and bmp).</span><br>
															<span class="small" style="color:red;">(Ex : Voter Id)</span>
															<?php 
																if ($type == "edit" && file_exists('uploads/employee/identity_proof/'.$id.'.png')) 
																{
																	?>
																		<div class="mt-2">
																			<img src="<?php echo base_url()?>uploads/employee/identity_proof/<?php echo $id ?>.png" width="110px" height="110px" alt="Identity Proof">
																		</div>
																	<?php
																}
															?>
														</div>
														
														<div class="form-group col-md-3"> 
															<label class="col-form-label">Address Proof<span class="text-danger">*</span></label>
															<input type="file" name="address_proof" id="address_proof" accept="image/*" <?php echo $required ?>   autocomplete="off"  onchange="return validateSingleFileExtension(this)" <?php echo $this->validation; ?> class="form-control singleImage" autocomplete="off" >
															<span class="note-class"><b>Note</b> : Proof upload size is 1 [MB] and image format is (png,gif,jpg,jpeg and bmp).</span><br>
															<span class="small" style="color:red;">(Ex : Aadhar Card)</span>

															<?php 
																if ($type == "edit" && file_exists('uploads/employee/address_proof/'.$id.'.png')) 
																{
																	?>
																		<div class="mt-2">
																			<img src="<?php echo base_url()?>uploads/employee/address_proof/<?php echo $id?>.png"  width="110px" height="110px" alt="Address Proof">
																		</div>
																	<?php
																}
															?>
														</div>
														*/ ?>
														
														<!-- <div class="form-group col-md-3">
															<label class="col-form-label">Passport Size Photo<span class="text-danger">*</span></label>
															<input type="file" name="passport_photo" id="passport_photo" accept="image/*" <?php echo $required ?>  autocomplete="off" <?php echo $this->validation; ?>  onchange="return validateSingleFileExtension(this)" class="form-control singleImage" autocomplete="off"  >
															<span class="note-class"><b>Note</b> : Proof upload size is 1 [MB] and image format is (png,gif,jpg,jpeg and bmp).</span><br>

															<?php 
																if ($type == "edit" && file_exists('uploads/employee/passport_photo/'.$id.'.png')) 
																{
																	?>
																		<div class="mt-3 pt-3">
																			<img src="<?php echo base_url()?>uploads/employee/passport_photo/<?php echo $id?>.png" width="110px" height="110px" alt="Passport Size Photo">
																		</div>
																	<?php
																}
															?>
														</div> -->
														<script>
															/** Single Image Type & Size Validation **/
															function validateSingleFileExtension(fld) 
															{
																var fileUpload = fld;
																
																if (typeof (fileUpload.files) != "undefined")
																{
																	var size = parseFloat( fileUpload.files[0].size / 1024 ).toFixed(2);
																	var validSize = 1024 * 2; //1024 - 1Mb multiply 4mb
																	
																	if( size > validSize )
																	{
																		alert("Proof upload size is 1 MB");
																		$('.singleImage').val('');
																		$(this).val('');
																		var value = 1;
																		return false;
																	}
																	else if(!/(\.png|\.bmp|\.gif|\.jpg|\.jpeg)$/i.test(fld.value))
																	{
																		alert("Invalid Proof file type.");      
																		$('.singleImage').val('');
																		return false;   
																	}
																	
																	if(value != 1)	
																		return true; 
																}
															}
														</script>
													</div>
												</fieldset>
											</div>
										</div>
										
										<fieldset>
											<?php
												if($emp_type == "add")
												{
													?>
													<div class="createDiv">
														<legend class="text-uppercase font-size-sm font-weight-bold">Login Details</legend>
														<div class="row">
															<div class="form-group col-md-3">
																<label class="col-form-label">User Name<span class="text-danger">*</span></label>
																<div class="">
																	<input type="text" name="user_name" autocomplete="off" required id="user_name" class="form-control" value="<?php echo $random_user_id;?>" placeholder="">
																	<span class="user_name_exist_error error"></span>
																</div>
															</div>
															
															<div class="form-group col-md-3">
																<label class="col-form-label">Password <span class="text-danger">*</span></label>
																<input type="password" name="password" required class="form-control" value="" placeholder="">
															</div>
														</div>
													</div>
													<?php 
												}
												else if($emp_type == "edit")
												{
													?>
													<legend class="text-uppercase font-size-sm font-weight-bold">Login Details</legend>
													<div class="row">
														<div class="form-group col-md-3">
															<label class="col-form-label">User Name</label>
															<input type="text" name="user_name" id="user_name" readonly required class="form-control" value="<?php echo isset($edit_data[0]['user_name']) ? $edit_data[0]['user_name'] :$random_user_id;?>" placeholder="">
															<span class="user_name_exist_error error"></span>
														</div>
													</div>
													<?php 
												} 
											?>
										</fieldset>
									
										<?php /*
										<!-- Family details start-->
										<label>
											<b>Family Details</b>
										</label>
										<?php 
											if( $type =="add" )
											{
												?>
												<div class="row">
													<div class="form-group col-md-9">
														<div id="ActionItem">
															<div class="action_inputs">
																<div class="row">
																	<div class="col-md-4">
																		<label class="col-form-label">Name</label>
																		<input type="text" name="name[]" id="name_0" placeholder="Name" class="form-control">
																	</div>
																	<div class="col-md-4">
																		<label class="col-form-label">Address</label>
																		<textarea rows='1' name="nominee_address[]" id="address_0" placeholder="Address" class="form-control"></textarea>
																	</div>
																	<div class="col-md-4">
																		<label class="col-form-label">Relation</label>
																		<input type="text" name="relation[]" id="relation_0" placeholder="Relation" class="form-control">
																	</div>
																</div>
																
																<div class="row">
																	<div class="col-md-4">
																		<label class="col-form-label">Contact Number</label>
																		<input type="text" name="contact_number[]" id="contact_number_0" placeholder="Contact Number" class="form-control">
																	</div>
																	<div class="col-md-4">
																		<label class="col-form-label">DOB</label>
																		<input type="date" name="dob[]" id="dob_0" placeholder="DOB" class="form-control">
																	</div>
																	<div class="col-md-4">
																		<label class="col-form-label">Upload Doc</label>
																		<input type="file" name="nominee_upload_document[]" id="upload_document_0" class="form-control">
																	</div>
																</div>
															</div>
															
															<div class="add-remove-btn" style="float:right;padding: 20px 0px 0px 4px;">
																<input type="button" id="action_file_remove" class="btn btn-warning" value="Remove More">
																<input type="button" id="action_file_add" class="btn btn-info" value="Add More">
															</div>
														</div>
													</div>
												</div>
												<?php 
											}
											else if($type =="edit")
											{
												?>
												<div class="row">
													<div class="form-group col-md-9">
														<div id="ActionItem">
															<div class="action_inputs">
																<?php 
																	$empFamilyDetails = "select * from employee_family_details where user_id='".$id."' ";
																	$getFamilyDetails = $this->db->query($empFamilyDetails)->result_array();
																	if(count($getFamilyDetails) > 0)
																	{
																		$j=0;
																		foreach($getFamilyDetails as $nominee)
																		{
																			?>
																			<?php if($j !=0){?>
																			<div class="action_field file-right"> 
																			<?php } ?>
																			<div class="row mt-3">
																				<div class="col-md-4">
																					<?php if($j ==0){?><label class="col-form-label">Name</label><?php } ?>
																					<input type="text" name="name[]" id="name_<?php echo $j;?>" value="<?php echo ucfirst($nominee['name']);?>" placeholder="Name" class="form-control">
																				</div>
																				<div class="col-md-4">
																					<?php if($j ==0){?><label class="col-form-label">Address</label><?php } ?>
																					<textarea rows='1' name="nominee_address[]" id="address_<?php echo $j;?>" placeholder="Address" class="form-control"><?php echo ucfirst($nominee['address']);?></textarea>
																				</div>
																				<div class="col-md-4">
																					<?php if($j ==0){?><label class="col-form-label">Relation</label><?php } ?>
																					<input type="text" name="relation[]" id="relation_<?php echo $j;?>" value="<?php echo ucfirst($nominee['relation']);?>" placeholder="Relation" class="form-control">
																				</div>
																			</div>
																			
																			<div class="row mt-3 mb-5">
																				<div class="col-md-4">
																					<?php if($j ==0){?><label class="col-form-label">Contact Number</label><?php } ?>
																					<input type="text" name="contact_number[]" id="contact_number_<?php echo $j;?>" value="<?php echo ucfirst($nominee['contact_number']);?>" placeholder="Contact Number" class="form-control">
																				</div>
																				
																				<div class="col-md-4">
																					<?php if($j ==0){?><label class="col-form-label">DOB <?php #echo date("m/d/Y",$action['target_date']);?></label><?php } ?>
																					<input type="date" name="dob[]" id="dob_<?php echo $j;?>" value="<?php echo date("Y-m-d",$nominee['dob']);?>" placeholder="Dob" class="form-control">
																				</div>
																				
																				<div class="col-md-4">
																					<?php if($j ==0){?><label class="col-form-label">Upload Doc</label><?php } ?>
																					<input type="file" name="nominee_upload_document[]" id="upload_document_<?php echo $j;?>" class="form-control">
																					<input type="hidden" value="<?php echo $nominee['upload_document'];?>" name="upload_document_2[]" id="upload_document_2_<?php echo $j;?>" class="form-control">
																					<?php
																						
																						if(!empty($nominee['upload_document']) && file_exists("uploads/employee/family_documents/".$nominee['upload_document']) )
																						{
																							?>
																							<a href="<?php echo base_url();?>uploads/employee/family_documents/<?php echo $nominee['upload_document'];?>" download title="download">
																								Download <i class="fa fa-download"></i>
																							</a>
																							<?php
																						}
																					?>
																				</div>
																			</div>
																			<?php if($j !=0){?>
																			</div>
																			<?php } ?>
																			<?php 
																			$j++;
																		}
																	}
																	else
																	{ 
																		?>
																		<div class="row">
																			<div class="col-md-4">
																				<label class="col-form-label">Name</label>
																				<input type="text" name="name[]" id="name_0" placeholder="Name" class="form-control">
																			</div>
																			<div class="col-md-4">
																				<label class="col-form-label">Address</label>
																				<textarea rows='1' name="nominee_address[]" id="address_0" placeholder="Address" class="form-control"></textarea>
																			</div>
																			<div class="col-md-4">
																				<label class="col-form-label">Relation</label>
																				<input type="text" name="relation[]" id="relation_0" placeholder="Relation" class="form-control">
																			</div>
																		</div>
																		
																		<div class="row">
																			<div class="col-md-4">
																				<label class="col-form-label">Contact Number</label>
																				<input type="text" name="contact_number[]" id="contact_number_0" placeholder="Contact Number" class="form-control">
																			</div>
																			<div class="col-md-4">
																				<label class="col-form-label">DOB</label>
																				<input type="date" name="dob[]" id="dob_0" placeholder="DOB" class="form-control">
																			</div>
																			<div class="col-md-4">
																				<label class="col-form-label">Upload Doc</label>
																				<input type="file" name="nominee_upload_document[]" --onchange="return validateSinglePDFImageExtension(this)" id="upload_document_0" class="form-control">
																			</div>
																		</div>
																		<?php 
																	} 
																?>
															</div>
															
															<div class="add-remove-btn" style="float:right;padding: 20px 0px 0px 4px;">
																<input type="button" id="action_file_remove" class="btn btn-warning" value="Remove More">
																<input type="button" id="action_file_add" class="btn btn-info" value="Add More">
															</div>
														</div>
													</div>
												</div>
												<?php 
											}							
										?>
										<script>
											$(document).ready(function()
											{
												//Nominee start
												var type = '<?php echo $type;?>';
												
												if(type == 'edit')
												{
													var j = '<?php echo isset($getFamilyDetails) ? count($getFamilyDetails) : 0;?>';
													
													if( j > 1 )
													{
														$('#action_file_remove').show();
													}
													else
													{
														$('#action_file_remove').hide();
													}
												}
												else
												{
													$('#action_file_remove').hide();
													var j = 1;
												}
												
												$('#action_file_add').click(function() 
												{
													$('#action_file_remove').show();
													
													if(j != 5)  
													{	
														$('<div class="action_field file-right"> <div class="row mt-5"><div class="col-md-4"><input type="text" name="name[]" id="name_'+j+'" placeholder="Name" class="form-control"></div><div class="col-md-4"><textarea rows="1" name="nominee_address[]" id="address_'+j+'" placeholder="Address" class="form-control"></textarea></div><div class="col-md-4"><input type="text" name="relation[]" id="relation_'+j+'" placeholder="Relation" class="form-control"></div></div><div class="row mt-3"><div class="col-md-4"><input type="text" name="contact_number[]" id="contact_number_'+j+'" placeholder="Contact Number" class="form-control"></div><div class="col-md-4"><input type="date" name="dob[]" id="dob_'+j+'" placeholder="DOB" class="form-control"></div><div class="col-md-4"><input type="file" name="nominee_upload_document[]" id="upload_document_'+j+'" placeholder="Upload_document" class="form-control"></div></div></div>').fadeIn("slow").appendTo('.action_inputs');
														j++;
														if(j == 5)
														{
															$('#action_file_add').hide();
														}
													}
													else
													{ 
														$('#action_file_add').hide(); 
													} 
												});
												
												$('#action_file_remove').click(function() 
												{
													if(j > 1) 
													{
														$('#action_file_add').show();
														$('.action_field:last').remove();
														j--;
														
														if(j==1)
														{
															$('#action_file_remove').hide();
														}
													}
													else if(j == 1)
													{
														alert('No more to remove');
														j = 1;
														return false;
													}
												});
												//Nominee End
											});
										</script>
										<!-- Family details end-->
										*/ ?>
												
										<!-- Proof attached start here-->
										<?php /*
										<hr>
										<?php 
											$categories = $this->db->query('select category_id, category_name, required_type from user_document_categories where category_status=1 and category_type=3')->result_array();
										
											if(isset($type) && $type == 'add')
											{
												$required='required';
											}
											else if(isset($type) && $type == 'edit')
											{
												$user_id = isset($edit_data[0]['user_id']) ? $edit_data[0]['user_id'] : 0;
												$checkDocuments = $this->db->query("
													select 
														user_document_attachments.category_id,
														user_document_attachments.user_id,
														user_document_attachments.image_2,
														user_document_attachments.caption,
														user_document_attachments.document_type,
														user_document_categories.category_name

														from user_document_attachments 
													
													left join user_document_categories on
														user_document_categories.category_id =  user_document_attachments.category_id
													where
														user_document_attachments.user_id ='".$user_id."'
													")->result_array();
												if(count($checkDocuments) > 0)	
												{
													$required='';
												}
												else
												{
													$required='required';
												}
											}
										?>
										<div class="row form-group mt-4">
											<label class="col-form-label col-lg-2">Documents </label>
											<div class="col-lg-3">
												<select class="form-control searchDropdown" <?php //echo $required;?> id="documents" name="documents">
													<option value="">- Select Document -</option>					  
													<?php
														foreach($categories as $category) 
														{
															?>
															<option value="<?php echo $category['category_id'];?>"><?php echo ucfirst($category['category_name']);?></option>
															<?php
														}
													?>
												</select>
											</div>
										</div>
										
										<div class="row mt-4 mb-4">
											<div class="col-sm-12">
												<div class="form-group">
													<div style="overflow-y: auto;">
														<div id="err_product" style="color:red;margin: 0px 0px 10px 0px;"></div>
														<table class="table items --table-striped table-bordered table-condensed table-hover product_table" name="product_data" id="product_data">
															<thead>
																<tr>
																	<th colspan="13">
																		Attached Documents <span style="color:#969292;">( Upload Documents : png, bmp, gif, jpg, jpeg, pdf and Size is 4MB. )</span>
																	</th>
																</tr>
																<tr>
																	<th style="width:30px;"> </th>
																	<th>Document Name</th>
																	<th>Upload Document</th>
																	<td>Document Type</td>
																	<th>Description</th>
																</tr>
															</thead>
															<tbody id="product_table_body">
																<?php
																	if( isset($type) && $type == "edit" )
																	{
																		$user_id = isset($edit_data[0]['user_id']) ? $edit_data[0]['user_id'] : 0;
																		$checkDocuments = $this->db->query("
																			select 
																				user_document_attachments.attachement_id,
																				user_document_attachments.category_id,
																				user_document_attachments.user_id,
																				user_document_attachments.image_2,
																				user_document_attachments.caption,
																				user_document_attachments.description,
																				user_document_attachments.document_type,
																				user_document_categories.category_name

																				from user_document_attachments 
																			
																			left join user_document_categories on
																				user_document_categories.category_id =  user_document_attachments.category_id
																			where
																				user_document_attachments.user_id ='".$user_id."'
																			")->result_array();
																			
																		if( count($checkDocuments) > 0)
																		{
																			$i=0;
																			$counter=1;
																			foreach($checkDocuments as $documents)
																			{
																				?>
																				<tr class="dataRowVal<?php echo $documents['category_id']; ?>">
																					<td>
																						<a class='deleteRow1'> 
																							<i class="fa fa-trash"></i> 
																						</a>
																						<input type='hidden' name='image_2[]' value="<?php echo $documents['image_2']; ?>">
																						<input type='hidden' name='attachement_id[]' value="<?php echo $documents['attachement_id']; ?>">
																						<input type='hidden' name='id' name='id' value="<?php echo $i ?>">
																						<input type='hidden' name='category_id[]' value="<?php echo $documents['category_id']; ?>">
																					</td>
																					<td><?php echo $documents['category_name']; ?></td>
																					
																					<td>
																						<input type='file' class='form-control' name='upload_document[]' onchange="return validateFileExtension(this,<?php echo $counter;?>)" id='first_<?php echo $counter;?>' >
																						<?php
																							if(!empty($documents['image_2']) && file_exists("uploads/document_attachments/".$documents['image_2']) )
																							{
																								?>
																								<a href="<?php echo base_url()?>uploads/document_attachments/<?php echo $documents['image_2'];?>" download title="download">Download <i class="fa fa-download"></i></a>
																								<?php
																							}
																						?>
																					</td>
																					
																					<td>
																						<select class='form-control' id='document_type' name='document_type[]'>
																							<option value=''>- Select Document Type -</option>
																							<?php
																								foreach($this->document_type as $key => $value)
																								{
																									$selected="";
																									if($documents['document_type'] == $key)
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
																					
																					<td>
																						<textarea rows="1" class='form-control' name='description[]' id='description_<?php echo $counter;?>'><?php echo $documents['description']; ?></textarea>
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
													var counter1 = '<?php echo isset($checkDocuments) ? count($checkDocuments) : 1; ?>';
													
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
														var counter = '<?php echo isset($checkDocuments) ? count($checkDocuments) : 1; ?>';
													}
												}
												
												$('#documents').change(function()
												{
													var id = $(this).val();
													$('#err_product').text('');
													var flag = 0;
													
													if(id != "")
													{
														$.ajax({
															url: "<?php echo base_url('employee/getAttachedDocuments') ?>/"+id,
															type: "GET",
															data:{
																'<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
															},
															datatype: "JSON",
															success: function(d)
															{
																data = JSON.parse(d);
																$("table.product_table").find('input[name^="category_id"]').each(function () 
																{
																	if(data[0].category_id  == +$(this).val())
																	{
																		flag = 1;
																	}
																});
																
																if(flag == 0)
																{
																	var id = data[0].category_id;
																	var category_name = data[0].category_name;
																	var document_type = data['documentType'];
																	var newRow = $("<tr class='dataRowVal"+id+"'>");
																	var cols = "";
																	cols += "<td><a class='deleteRow'> <i class='fa fa-trash'></i> </a><input type='hidden' name='id' name='id' value="+i+"><input type='hidden' name='category_id[]' value="+id+"></td>";
																	cols += "<td class='tab-medium-width'>"+category_name+"</td>";
																	cols += "<td class='text-center'>"
																		+"<input type='file' required class='form-control' onchange='return validateFileExtension(this,"+ counter +")' name='upload_document[]' id='first_"+ counter +"' >"
																		+"</td>";
																		
																	cols += "<td class='tab-medium-width'>"+document_type+"</td>";
															
																	cols += "<td class='text-center'>"
																		+"<textarea rows='1' class='form-control' name='description[]' id='description_"+ counter +"'></textarea>"
																		+"</td>";
																		
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
																	$('#err_product').text('Document Already Exist!').animate({opacity: '0.0'}, 2000).animate({opacity: '0.0'}, 1000).animate({opacity: '1.0'}, 2000);
																}
															},
															error: function(xhr, status, error) 
															{
																$('#err_product').text('Select Document / Name!').animate({opacity: '0.0'}, 2000).animate({opacity: '0.0'}, 1000).animate({opacity: '1.0'}, 2000);
															}
														});
													}
												});
												
												$("table.product_table").on("click", "a.deleteRow,a.deleteRow1", function (event) 
												{
													$(this).closest("tr").remove();
												});
												
												//$("table.product_table").on("click", "a.deleteRow1", function (event) 
												//{
													//deleteRow1($(this).closest("tr"));
													//$(this).closest("tr").remove();
													//calculateGrandTotal();
												//});
											});
										</script>
											
										<!-- Proof attached end here -->
										*/ ?>
									</fieldset>
								</div>
								<!-- Customer Details end -->
							</div>
							
							<script type="text/javascript">    
								$(document).ready(function(){     
									$("#ifsc_code").change(function () {      
									var inputvalues = $(this).val();      
									  var reg = /[A-Z|a-z]{4}[a-zA-Z0-9]{7}$/;    
										if (inputvalues.match(reg)) {    
											return true;    
										}    
										else {    
												$("#ifsc_code").val("");    
											alert("You entered invalid IFSC code");    
											//document.getElementById("txtifsc").focus();    
											return false;    
										}    
									});      
								});    

								function LicenceNumber(fieldname)
								{				
									if (fieldname.value == "") 
									{
										$('#licence_number_val').html('(Ex : TN-0619850034761 )');
										$('.register-but').prop('disabled',false);
										return
									}					
									regsaid = /^(([A-Z]{2}[0-9]{2})( )|([A-Z]{2}-[0-9]{2}))((19|20)[0-9][0-9])[0-9]{7}$/;
									if(regsaid.test(fieldname.value) == false)
									{
										$('#licence_number_val').html('Licence Number Not Valid');
										$('.register-but').prop('disabled',true);
										return false;
									}
									else
									{
										$('#licence_number_val').html('(Ex : TN-0619850034761 )');
										$('.register-but').prop('disabled',false);
									}
								}

								function voterId(fieldname)
								{				
									if (fieldname.value == "") 
									{
										$('#voterid_number_val').html('(Ex : SRD0676361)');
										$('.register-but').prop('disabled',false);
										return
									}					
									regsaid = /^([a-zA-Z]){3}([0-9]){7}?$/;
									if(regsaid.test(fieldname.value) == false)
									{
										$('#voterid_number_val').html('Voter ID is Invalid!');
										$('.register-but').prop('disabled',true);
										return false;
									}
									else
									{
										$('#voterid_number_val').html('(Ex : SRD0676361)');
										$('.register-but').prop('disabled',false);
									}
								} 

								var $field1 = $("#mobile_number");
								var $field2 = $("#mobile_number_1");

								$field1.on("keydown",function()
								{
									setTimeout(checkValue,0); 
								});

								var v2 = $field2.val();
								var checkValue = function(){
									var v1 = $field1.val();
									if (v1 != v2){
										$field2.val(v1);
										v2 = v1;
									}
								};
							</script>
							
							<div class="d-flexad" style="text-align:right;">
								<a href="<?php echo base_url(); ?>employee/ManageEmployee" class="btn btn-light">Cancel  </a>
								<?php 
									if($type == "edit")
									{
										?>
										<button type="submit" class="btn btn-primary  register-but ml-3">Update</button>
										<?php 
									}
									else
									{
										?>
										<button type="submit" class="btn btn-primary ml-2 register-but">Submit </button>
										<?php 
									}
								?>
							</div>
						</form>
						<?php
					}
					else
					{ 
						?>
						<fieldset class="mt-2">
							<legend class="text-uppercase font-size-sm font-weight-bold">
								Manage Employee Banks
							</legend>
						</fieldset>
						<form action="" method="get">
							<div class="row mb-2">
								<div class="col-md-3">	
									<input type="search" autocomplete="off" class="form-control" name="keywords" value="<?php echo !empty($_GET['keywords']) ? $_GET['keywords'] :""; ?>" placeholder="Search...">
									<p class="search-note">Note : Employee Name, Bank Name, Branch, Bank A/C No., MICR code , IFSC Code.</p>
								</div>	
								
								<div class="col-md-3">
									<button type="submit" class="btn btn-info waves-effect">Search <i class="fa fa-search" aria-hidden="true"></i></button>
								</div>
							
							
								<div class="col-md-6 text-right">
									<?php 
										$redirect_url = substr($_SERVER['REQUEST_URI'],'1');
									?>
									<input type="hidden" id="redirect_url" value="<?php echo $redirect_url; ?>"/>
															
									<div class="filter_page">
										<label>
											<span>Show :</span> 
											<select name="filter" onchange="location.href='<?php echo base_url(); ?>admin/sort_itemper_page/'+$(this).val()+'?redirect=<?php echo $redirect_url; ?>'">
												<?php 
													$pageLimit = isset($_SESSION['PAGE']) ? $_SESSION['PAGE'] : 10;
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
						</form>
						<div class="table-design">
							<div class="table-design-new">
								<div class="new-scroller" id="style-3">
									<table id="myTable" class="sticky-col table-bordered sortable-table" --class="table table-bordered table-hover --table-striped dataTable">
										<thead>
											<tr>
												<th class="" class="">Employee Name</th>
												<th class="" class="">Employee No.</th>
												<th --onclick="sortTable(2)" class="">Bank Name</th>
												<th --onclick="sortTable(3)" class="">Branch</th>
												<th --onclick="sortTable(4)">Bank A/c No.</th>
												<th --onclick="sortTable(5)">IFSC Code</th>
												<th --onclick="sortTable(6)">MICR Code</th>
											</tr>
										</thead>
										<tbody>
                                            <?php 
                                                foreach ($resultData as $row) 
                                                {
                                                    ?>
                                                    <tr class="assign_<?php echo $row['bank_id']; ?>">
                                                        <td><?php echo ucfirst($row['first_name']." ".$row['last_name']); ?></td>
                                                        <td><?php echo ucfirst($row['random_user_id']); ?></td>
                                                        <td><?php echo ucfirst($row['bank_name']); ?></td>
                                                        <td><?php echo ucfirst($row['branch_name']); ?></td>
                                                        <td><?php echo $row['account_number']; ?></td>
                                                        <td><?php echo $row['ifsc_code']; ?></td>
                                                        <td><?php echo $row['micr_code']; ?></td>
                                                        
                                                    </tr>
                                                    <?php
                                                }
                                            ?>
                                        </tbody>
									</table>
									<?php 
										if(count($resultData) == 0)
										{
											?>
											<p class="admin-no-data">No data found.</p>
											<?php 
										} 
									?>
								</div>
							</div>
						</div>
						
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
		</div><!-- Card end-->
		<?php if(isset($type) && $type =='view'){?>
			<a href='<?php echo base_url();?>employee/ManageEmployee' class='btn btn-info' style="float:right;"><i class="icon-arrow-left16"></i> Back</a>
		<?php } ?>
	</div><!-- Content end-->
	
<!-- View Popup Image-->	
<link href="<?php echo base_url();?>assets/backend/view_gallery/jquery.magnify.css" rel="stylesheet">
<script src="<?php echo base_url();?>assets/backend/view_gallery/jquery.magnify.js"></script>
<script>
   /*  $('[data-magnify]').magnify({
      fixedContent: true
    }); */
</script>
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