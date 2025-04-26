<?php
	$employee = accessMenu(relations);
?>
<!-- Page header start-->
<div class="page-header page-header-light">
	<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
		<div class="d-flex">
			<div class="breadcrumb">
				<a href="<?php echo base_url();?>admin/dashboard" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> <?php echo get_phrase('Home');?></a>
				<a href="<?php echo base_url();?>employee/ManageEmpRelations" class="breadcrumb-item">
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
			else if( isset($type) && $type == "view" )
			{ 
				if($employee['create_edit_only'] == 1 || $this->user_id == 1)
				{
					?>
					<a href="<?php echo base_url(); ?>employee/ManageEmpRelations/edit/<?php echo $id;?>" class="btn btn-info">
						Edit Employee Relations
					</a>
					<?php
				}
			}
			else
			{ 
				if($employee['create_edit_only'] == 1 || $this->user_id == 1)
				{
					?>
					<div class="new-import-btn" style="float:right;">
											
						<a href="<?php echo base_url(); ?>employee/ManageEmpRelations/add" class="btn btn-info">
							Create  Relation
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
						?>
						<!-- <label class="text-capitalize" style="font-size:18px;color: #13111188;font-weight: 600;"><?php echo $type ?> Employee :</label> -->
						<legend class="text-uppercase font-size-sm font-weight-bold">
							<?php echo $type ?> Relation :
						</legend>

						<form action="" class="form-validate-jquery" --id="formValidation" enctype="multipart/form-data" method="post">
							<div class="row">
								<!-- left side start -->
								<div class="col-sm-12 col-md-12">
									<!-- Customer Details start -->
									<fieldset class="mb-3">
										<div class="row">
											<div class="form-group col-md-3">
												<label class="col-form-label">Relation Name<span class="text-danger">*</span></label>
												<a class="quicklink" href="<?php echo base_url()?>role/ManageRole/add" title="Employment Type">Add </a>
												<input type="text" class="form-control" name="relationship_name"  <?php echo $this->validation; ?> id="relationship_name" required value="<?php echo isset($edit_data[0]['relationship_name']) ? $edit_data[0]['relationship_name'] :'';?>">
											</div>
										</div>
																				
										<link href="<?php echo base_url();?>assets/frontend/css/jquery-ui.css" rel="stylesheet">
										<script src="<?php echo base_url();?>assets/frontend/js/jquery-ui.js"></script>
										
									</fieldset>
									
								</div>
								<!-- Customer Details end -->
							</div>

							
							<div class="d-flexad" style="text-align:right;">
								<a href="<?php echo base_url(); ?>employee/ManageEmpRelations" class="btn btn-light">Cancel  </a>
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
								<?php echo $page_title ?>
							</legend>
						</fieldset>
						<form action="" method="get">
							<div class="row">
								<div class="col-md-3">	
									<input type="search" autocomplete="off" class="form-control" name="keywords" value="<?php echo !empty($_GET['keywords']) ? $_GET['keywords'] :""; ?>" placeholder="Search...">
									<p class="search-note">Note : Realation Name.</p>
								</div>	
								
								<div class="col-md-3">
									<button type="submit" class="btn btn-info waves-effect">Search <i class="fa fa-search" aria-hidden="true"></i></button>
								</div>
							</div>
							
							
							<div class="row">
								<div class="col-md-8"></div>
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
						</form>
						<div class="table-design">
							<div class="table-design-new">
								<div class="new-scroller" id="style-3">
									<table id="myTable" class="sticky-col table-bordered" --class="table table-bordered table-hover --table-striped dataTable">
										<thead>
											<tr>
												<th class="sticky-col-tab" style="text-align:center;width:12%;">Controls</th>
												<th class="sticky-col-tab-one" onclick="sortTable(2)"> Realation Name</th>
												<th onclick="sortTable(3)" class="text-center" style="width:10%;">Status</th>
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
														<!--<td style="text-align:center;"><?php echo $i + $firstItem;?></td>-->
														<td class="sticky-col-tab text-center">
															<?php
																if($employee['create_edit_only'] == 1 || $employee['read_only'] == 1 || $this->user_id == 1)
																{
																	?>
																	<?php
																		if($employee['create_edit_only'] == 1 || $this->user_id == 1)
																		{
																			?>
																			<a title="Edit" href="<?php echo base_url(); ?>employee/ManageEmpRelations/edit/<?php echo $row['relationship_id'];?>">
																				<i class="fa fa-pencil"></i> 
																			</a>
																			<?php 
																		} 
																	?>
																	<?php
																		if($employee['create_edit_only'] == 1 || $this->user_id == 1)
																		{
																			?>
																			&nbsp;|&nbsp;
																			<?php 
																				if($row['relationship_status'] == 1)
																				{
																					?>
																					<a href="<?php echo base_url(); ?>employee/ManageEmpRelations/status/<?php echo $row['relationship_id'];?>/0" title="Block">
																						<i class="fa fa-ban"></i> 
																					</a>
																					<?php 
																				} 
																				else
																				{  ?>
																					<a href="<?php echo base_url(); ?>employee/ManageEmpRelations/status/<?php echo $row['relationship_id'];?>/1" title="Unblock">
																						<i class="fa fa-check"></i> 
																					</a>
																					<?php 
																				} 
																			?>
																			<?php 
																		} 
																		?>
																	<?php 
																}
																else
																{ 
																	?>
																	-
																	<?php 
																} 
															?>

															<div class="dropdown" style="display: inline-block;padding-right: 10px!important;width:92px; text-align:center;">
																<!-- <button type="button" class="btn btn-outline-info gropdown-toggle waves-effect waves-light" data-toggle="dropdown" aria-expanded="false">
																	Action <i class="fa fa-angle-down"></i>
																</button>
																<ul class="dropdown-menu dropdown-menu-right">
																	<li>
																		<a title="Edit" href="<?php echo base_url(); ?>employee/ManageEmpRelations/edit/<?php echo $row['relationship_id'];?>">
																			<i class="fa fa-pencil"></i> Edit
																		</a>
																	</li>
																	<li>
																		<a title="View" href="<?php echo base_url(); ?>employee/ManageEmpRelations/view/<?php echo $row['relationship_id'];?>">
																			<i class="fa fa-eye"></i> View
																		</a>
																	</li> -->
																	
																	
																		<?php 
																			/* if($row['user_status'] == 1)
																			{
																				?>
																				<a href="<?php echo base_url(); ?>employee/ManageEmpRelations/status/<?php echo $row['relationship_id'];?>/0" title="Block">
																					<i class="fa fa-ban"></i> Inactive
																				</a>
																				<?php 
																			} 
																			else
																			{  ?>
																				<a href="<?php echo base_url(); ?>employee/ManageEmpRelations/status/<?php echo $row['relationship_id'];?>/1" title="Unblock">
																					<i class="fa fa-check"></i> Active
																				</a>
																				<?php 
																			} */
																		?>
																	
																	<!-- <li>
																		<a title="Change Password" href="#" data-toggle="modal" data-target="#exampleModal<?php echo $row['relationship_id'];?>">
																			
																			<i class="icon-lock"></i>&nbsp;&nbsp;Change Password
																		</a>
																	</li> -->
																	<?php
																	/* <li>
																		<a class="btn btn-light btn-class" title="Delete" href="<?php echo base_url();?>customers/ManageCustomers/delete/<?php echo $row['user_id'];?>" title="Delete" onclick="return confirm('Are you sure you want to delete?')">
																			<i class="icon-trash"></i>
																		</a> 
																	</li> */ ?>
																</ul>
																<?php /*  */ ?>
															</div>
															<!-- Modal End-->
														</td>
														
														<td class="sticky-col-tab-one">
															<?php echo ucfirst($row['relationship_name']);?>
														</td>
															
														<td style="text-align:center;" class="tab-medium-width">
															<?php 
																if($row['relationship_status'] == 1)
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
			<a href='<?php echo base_url();?>employee/ManageEmpRelations' class='btn btn-info' style="float:right;"><i class="icon-arrow-left16"></i> Back</a>
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
		//Customer E-mail Start here
		$(".register-but").removeClass("disabled-class");
		
		var emp_email_state = false;

		$('#emp_email').on('input', function()
		{
			var email = $('#emp_email').val();
			
			if (email == '') 
			{
				emp_email_state = false;
				return;
			}
			else
			{
				$.ajax({
					url: '<?php echo base_url();?>employee/EmailExist',
					type: 'post',
					data: {
						'email_check' : 1,'email' : email,
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