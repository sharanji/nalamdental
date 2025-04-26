<!-- Page header start-->
<div class="page-header page-header-light">
	<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
		<div class="d-flex">
			<div class="breadcrumb">
				<a href="<?php echo base_url();?>admin/dashboard" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Home</a>
				<a href="<?php echo base_url(); ?>frontoffice/ManageFrontoffice" class="breadcrumb-item">
					<?php echo $page_title;?>
				</a>
			</div>
			<a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
		</div>
		
		<?php
			if(isset($type) && $type == "add" || $type == "edit" )
			{ 
				
			}
			else 
			{ 
				?>
				<a href="<?php echo base_url(); ?>users/ManageUsers/add" class="btn btn-info">
					Add User
				</a>
				<?php 
			}
		?>
	</div>
</div>
<!-- Page header end-->

<div class="content"><!-- Content start-->
	<div class="card"><!-- Card start-->
		<div class="card-body">
			<?php
				if(isset($type) && $type == "add" || $type == "edit")
				{
					?>
					<form action="" class="form-validate-jquery" enctype="multipart/form-data" method="post">
						<div class="row">
						
							<?php 
								$getRole = $this->db->query("select role_name, role_id from org_roles where role_status=1 order by role_name asc")->result_array();
							?>
							<div class="form-group col-md-3">
								<label class="col-form-label">User Code <span class="text-danger">*</span></label>
								<select name="role_id" id="role_id" class="form-control searchDropdown" required>
									<option value="">- Select User Role -</option>
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

							<div class="form-group col-md-3">
								<label class="col-form-label">First Name <span class="text-danger">*</span></label>
								<div class="">
									<input type="text" name="first_name" id="last_name" class="form-control" value="<?php echo isset($edit_data[0]['email']) ? $edit_data[0]['email'] :"";?>" placeholder="">
								</div>
							</div>

							<div class="form-group col-md-3">
								<label class="col-form-label">Last Name </label>
								<div class="">
									<input type="text" name="last_name" id="last_name" class="form-control" value="<?php echo isset($edit_data[0]['email']) ? $edit_data[0]['email'] :"";?>" placeholder="">
								</div>
							</div>
						</div>
						
						<div class="row">
							<div class="form-group col-md-3">
								<label class="col-form-label">Email </label>
								<div class="">
									<input type="text" name="email" class="form-control" value="<?php echo isset($edit_data[0]['email']) ? $edit_data[0]['email'] :"";?>" placeholder="">
								</div>
							</div>
							<div class="form-group col-md-3">
								<label class="col-form-label">Mobile Number </label>
								<div class="">
									<input type="text" name="phone_number" class="form-control" value="<?php echo isset($edit_data[0]['phone_number']) ? $edit_data[0]['phone_number'] :"";?>" placeholder="">
								</div>
							</div>
						</div>
						
						<!-- login details start here -->
						<fieldset class="">
							<?php
								if(isset($type) && $type == "add")
								{
									?>
									<legend class="text-uppercase font-size-sm font-weight-bold">Login Details</legend>
									
									<div class="row">
										<div class="form-group col-md-3">
											<label class="col-form-label">User Name <span class="text-danger">*</span></label>
											<div class="">
												<input type="text" name="user_name" id="user_name" required class="form-control" value="" placeholder="">
												<span class="user_name_exist_error error"></span>
											</div>
										</div>
										
										<div class="form-group col-md-3">
											<label class="col-form-label">Password <span class="text-danger">*</span></label>
											<input type="password" name="password" required class="form-control" value="" placeholder="">
										</div>
									</div>
									<?php 
								}
								else if(isset($type) && $type == "edit")
								{
									?>
									<legend class="text-uppercase font-size-sm font-weight-bold">Login Details</legend>
									
									<div class="row">
										<div class="form-group col-md-3">
											<label class="col-form-label">User Name <span class="text-danger">*</span></label>
											<input type="text" name="user_name" id="user_name" readonly required class="form-control" value="<?php echo isset($edit_data[0]['user_name']) ? $edit_data[0]['user_name'] :"";?>" placeholder="">
											<span class="user_name_exist_error error"></span>
										</div>
									</div>
									<?php 
								} 
							?>
						</fieldset>	
						<!-- login details end here -->

						<!-- Multple roles in single user start here -->
						<fieldset class="mb-5">
							<legend class="text-uppercase font-size-sm font-weight-bold">User Roles</legend>
								
							<div class="row">
								<?php 
									$getRole = $this->db->query("select role_name, role_id from org_roles where role_status=1 order by role_name asc")->result_array();
								?>
								<div class="form-group col-md-3">
									<label class="col-form-label">Roles <span class="text-danger">*</span></label>
									<select name="role_id" id="role_id" class="form-control searchDropdown" required>
										<option value="">- Select User Role -</option>
										<?php 
											foreach($getRole as $row)
											{ 
												?>
												<option value="<?php echo $row['role_id'];?>"><?php echo ucfirst($row['role_name']);?></option>
												<?php 
											} 
										?>
									</select>
								</div>
							</div>

							<table class="table table-bordered table-hover --table-striped dataTable mt-3">
								<thead>
									<tr>
										<th scope="col"></th>
										<th scope="col">Role Name</th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<th scope="row">1</th>
										<td>Admin</td>
									</tr>
									<tr>
										<th scope="row">1</th>
										<td>Cashier</td>
									</tr>
								</tbody>
								</table>
						</fieldset>	
						<!-- Multple roles in single user end here -->


						<div class="d-flexad mb-3" style="text-align:right;">
							<a href="<?php echo base_url(); ?>users/ManageUsers" class="btn btn-default">Cancel</a>
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
									<button type="submit" class="btn btn-primary ml-1 register-but register-but-1">Submit</button>
									<?php 
								}
							?>
						</div>
					</form>
					
					
					<script type="text/javascript">  
						$('document').ready(function()
						{
							$(".register-but").removeClass("disabled-class");
							
							var user_name_state = false;

							$('#user_name').on('input', function()
							{
								var user_name = $('#user_name').val();
								var register_type = 3; // Front Office Billing
								
								if (user_name == '') 
								{
									user_name_state = false;
									return;
								}
								else
								{
									$.ajax({
										url: '<?php echo base_url();?>admin/UserNameExist',
										type: 'post',
										data: {
											'user_name_check' : 1,
											'user_name' : user_name,
											'register_type' : 3,
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
												
												$(".exist_error").addClass("error");
												$(".exist_error").attr("id", "email-error");
												$(".exist_error").attr("style", "display: inline;");
												
												$(".register-but").attr("disabled", "disabled=disabled");
												$(".register-but").addClass("disabled-class");
												$('.exist_error').html('Sorry... Username already exist!');
												
												return false;
											}
											else if (response == 'not_taken') 
											{
												$(".exist_error").attr("style", "display: none;");
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
					<?php
				}
				else
				{ 
					?>
					<div class="row mb-2">
						<div class="col-md-6"><?php echo $page_title;?></div>
						<div class="col-md-6 float-right text-right">
							<?php
								$userQry = "select user_id from users where register_type=3";
								$getUsers = $this->db->query($userQry)->result_array();

								if(count($getUsers) >= 3)
								{
									?>
									<span class="text-warning">Note : Your maximum number of permitted users is 3</span>
									<a href="javascript:void(0);" style="background:#99accdb8;" disabled='disabled' class="btn btn-info btn-sm">
										Add User
									</a>
									<?php
								}
								else
								{
									?>
									<a href="<?php echo base_url(); ?>users/ManageUsers/add" class="btn btn-info btn-sm">
										Add User
									</a>
									<?php
								}
							?>
						</div>
					</div>

					
					<form action="" method="get">
						<?php 
							$redirect_url = substr($_SERVER['REQUEST_URI'],'1');
						?>
						<input type="hidden" id="redirect_url" value="<?php echo $redirect_url; ?>"/>
												
						<div class="row">
							<div class="col-md-8">
								<div class="row">
									<div class="col-md-4">	
										<input type="search" autocomplete="off" class="form-control" name="keywords" value="<?php echo !empty($_GET['keywords']) ? $_GET['keywords'] :""; ?>" placeholder="Search...">
										<span class="small-1 text-muted">Note : Full Name, Mobile No, Branch, Email</span>
									</div>
									
									<?php /*<div class="col-md-4">	
										<select name="register_type" class="form-control searchDropdown">
											<option value="">- Select Register Type -</option>
											<?php 
												foreach($this->register_type as $key => $value)
												{
													$selected="";
													if(isset($_GET['register_type']) && $_GET['register_type']== $key)
													{
														$selected="selected";
													}
													?>
													<option value="<?php echo $key; ?>" <?php echo $selected; ?>><?php echo $value;?></option>
													<?php 
												} 
											?>
										</select>
									</div> */ ?>	
									<div class="col-md-3">
										<button type="submit" class="btn btn-info">
											Search <i class="fa fa-search"></i>
										</button>
									</div>
								</div>
							</div>
							<div class="col-md-4">
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
					</form>
					
					<div class="new-scroller">
						<table id="myTable" class="table table-bordered table-hover --table-striped dataTable">
							<thead>
								<tr>
									<?php
									/* <th>
										<input type="checkbox" id="select_all">&nbsp;
										<button style="display:none;" class="deleteBtn" type="submit" name="delete" value="delete" title="Patient Multi Delete"><i class="fa fa-trash" style="font-size:16px;"></i></button>
									</th>
									<th onclick="sortTable(0)" style="width: 10%;">S.No</th> */ ?>
									<th class="text-center">Controls</th>
									<th onclick="sortTable(0)">User Role</th>
									<?php
									/*
									<th onclick="sortTable(1)">Register Type</th>
									*/ ?>
									<th onclick="sortTable(2)">Branch</th>
									<th onclick="sortTable(3)">Full Name</th>
									<!--<th onclick="sortTable(4)">User Name</th>-->
									<th class="text-center" onclick="sortTable(5)">Mobile Number</th>
									<th onclick="sortTable(6)">Gender</th>
									<th onclick="sortTable(7)" class="text-center">Created Date</th>
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
											<?php 
											/* <td>
												<input type="checkbox" name="checkbox[]" class="emp_checkbox" value="<?php echo $row['user_id']; ?>">
											</td> */ ?>
											
											<!--<td style="text-align:center;"><?php echo $i + $firstItem;?></td>
											-->
											<td style="width: 12%;" class="text-center">
												<div class="dropdown" style="display: inline-block;--padding-right: 10px!important;width:92px;">
													<button type="button" class="btn btn-outline-primary gropdown-toggle waves-effect waves-light" data-toggle="dropdown" aria-expanded="false">
														Action <i class="fa fa-angle-down"></i>
													</button>
													<ul class="dropdown-menu dropdown-menu-right">
														<li>
															<a href="<?php echo base_url(); ?>users/ManageUsers/edit/<?php echo $row['user_id'];?>">
																<i class="fa fa-edit"></i> Edit
															</a>
														</li>
														<?php 
														/* <li>
															<a href="<?php echo base_url();?>patient/ManagePatient/delete/<?php echo $row['user_id'];?>">
																<i class="fa fa-trash-o"></i> Delete
															</a>
														</li> */ ?>
														<li>
															<?php 
																if($row['user_status'] == 1)
																{
																	?>
																	<a class="unblock" href="<?php echo base_url(); ?>users/ManageUsers/status/<?php echo $row['user_id'];?>/0" title="Block">
																		<i class="fa fa-ban"></i> Inactive
																	</a>
																	<?php 
																} 
																else
																{  ?>
																	<a class="block" href="<?php echo base_url(); ?>users/ManageUsers/status/<?php echo $row['user_id'];?>/1" title="Unblock">
																		<i class="fa fa-ban"></i> Active
																	</a>
																	<?php 
																} 
															?>
														<li>
														<li>
															<a title="Change Password" href="#" data-toggle="modal" data-target="#exampleModal<?php echo $row['user_id'];?>">	
																<i class="fa fa-lock"></i> Change Password
															</a>
														</li>
													</ul>
												</div>
												
												<!-- Change Password Modal -->
												<div class="modal fade" id="exampleModal<?php echo $row['user_id'];?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel<?php echo $row['user_id'];?>" aria-hidden="true">
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
																			<input type="text" value="<?php echo $row['user_name'];?>" class="form-control" disabled />
																		</div>
																		<div class="form-group col-md-6">
																			<label class="col-form-label float-left">Current Password</label>
																			<input type="text" readonly name="password" id="password" value="<?php echo $row['original_password'];?>" class="form-control" />
																		</div>
																	</div>
																	
																	<div class="row">
																		<div class="form-group col-md-6">
																			<label class="col-form-label float-left">New Password <span class="text-danger">*</span></label>
																			<input type="password" autocomplete="off" name="new_password" id="new_password" class="form-control"required />
																		</div>
																	
																		<div class="form-group col-md-6">
																			<label class="col-form-label float-left">Confirm New Password <span class="text-danger">*</span></label>
																			<input type="password" autocomplete="off" name="confirm_new_password" id="confirm_new_password" class="form-control"required />
																		</div>
																	</div>
																
																</div>
																<div class="modal-footer">
																	<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
																	<button type="submit" class="btn btn-primary">Submit</button>
																</div>
															</form>
														</div>
													</div>
												</div>
												<!-- Change Password Modal End-->
											</td>
											
											
											<td class="tab-medium-width"><?php echo ucfirst($row['role_name']);?></td>
											<?php /* <td class="tab-medium-width">
												<?php 
													foreach($this->register_type as $key=>$val)
													{
														if($row['register_type'] == $key){
															echo $val;
														}
													}
												?>
											</td> */ ?>
											
											<td class="tab-medium-width"><?php echo ucfirst($row['branch_name']);?></td>
											<td class="tab-medium-width"><?php echo ucfirst($row['first_name'])." ".ucfirst($row['last_name']);?></td>
											<?php /*<td class="tab-medium-width"><?php echo $row['user_name'];?></td> */?>
											<td style="text-align:center;"><?php echo $row['phone_number'];?></td>
											
											<td>
												<?php
													foreach($this->gender as $key=>$value)
													{
														if($row['gender'] == $key)
														{
															echo $value;
														}
													}
												?>
											</td>
											
											<td class="tab-mobile-width text-center">
												<?php echo date(DATE_FORMAT,$row['joined_date']);?>
											</td>

											<td class="text-center">
												<?php 
													if($row['user_status'] == 1)
													{
														?>
														<span class="btn btn-outline-success" title="Active"><i class="fa fa-check"></i> Active</span>
														<?php 
													} 
													else
													{ 
														?>
														<span class="btn btn-outline-warning" title="Inactive"><i class="fa fa-close"></i> Inactive</span>
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
					<?php 
				} 
			?>
		</div><!-- Card end-->
		
		<?php 
			if(isset($type) && $type =='view')
			{
				?>
				<a href='<?php echo $_SERVER['HTTP_REFERER'];?>' class='btn btn-info' style="float:right;"><i class="icon-arrow-left16"></i> Back</a>
				<?php 
			} 
		?>
	</div><!-- Content end-->
</div><!-- Content end-->

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