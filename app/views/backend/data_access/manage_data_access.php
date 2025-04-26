<?php 
	$physical_stock_adjustment = accessMenu(physical_stock_adjustment);

	$getOrganization=$this->organization_model->getOrgAll();
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
											?>
											<?php echo $page_title ?>
											
										</b>
									</h3>
								</div>
								<div class="col-md-6 text-right">
									<?php 
										if($type == "add" || $type == "edit")
										{
											?>
											<button type="submit" name="save_btn" id="save_btn" onclick="return saveBtn('save_btn');" title="Save" class="btn btn-primary btn-sm">Save</button>
											<button type="submit" name="submit_btn" id="submit_btn" onclick="return saveBtn('submit_btn');" title="Submit" class="btn btn-primary btn-sm">Submit</button>
											<?php 
										} 
									?>
									<a href="<?php echo base_url(); ?>data_access/manage_data_access" class="btn btn-default btn-sm">Close</a>
									
								</div>
							</div>
							<!-- Buttons end here -->
							
							<fieldset <?php echo $fieldSetDisabled;?>>
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
								<!-- Header Section Start Here-->
								<section class="header-section first_section">
									<div class="row">
										<div class="col-md-6">
											<div class="row">
												<div class="col-md-4">
													<div class="form-group text-right">
														<label class="col-form-label user_id"><span class="text-danger">*</span> User Name</label>
													</div>				
												</div>
												<div class="col-md-4">
													<div class="form-group">
														<div class="input-wrapper">
															<input type="text" name="user_name" autocomplete="off" id="user_name" value="<?php echo (isset($edit_data[0]['employee_number']) && isset($edit_data[0]['first_name'])) ? $edit_data[0]['employee_number'] . ' - ' . $edit_data[0]['first_name'] : NULL; ?>" placeholder="User Name" class="form-control">
															<input type="hidden" name="user_id" autocomplete="off" id="user_id" value="<?php echo isset($edit_data[0]['user_id']) ?$edit_data[0]['user_id'] : NULL; ?>" >
															<div id="UserNameList"></div><!-- Clear icon start -->
															<?php 
																if(isset($edit_data[0]["user_id"]) && !empty($edit_data[0]["user_id"]))
																{
																	$styleDisplay = "display:block";
																}
																else{
																	$styleDisplay = "display:none";
																}

																if($type!='view'){
																	?>
																		<span class="user_name_clear_icon" title="Clear" onclick="clearUserNameSearchKeyword();" style="<?php echo $styleDisplay;?>">
																			<i class="fa fa-times" aria-hidden="true"></i>
																		</span>
																	<?php
																}
															?>
															
															

															<script>
																$(document).ready(function()
																{  
																	$('#user_name').keyup(function()
																	{  
																		var query = $(this).val();  

																		if(query != '')  
																		{  
																			$.ajax({  
																				url:"<?php echo base_url();?>data_access/getUserAll",  
																				method:"POST",  
																				data:{query:query},  
																				success:function(data)  
																				{  
																					$('#UserNameList').fadeIn();  
																					$('#UserNameList').html(data);  
																				}  
																			});  
																		}  
																	});

																	$(document).on('click', 'ul.list-unstyled-user_id li', function()
																	{  
																		var value = $(this).text();
																		
																		if(value === "Sorry! User Name Not Found.")
																		{
																			$('#UserNameList').fadeOut();
																		}
																		else
																		{
																			$('#UserNameList').fadeOut();  
																		}
																	});
																});

																function getUserNameList(user_id,employee_number,user_name)
																{
																	$('.user_name_clear_icon').show();
																	if(user_id == 0)	
																	{
																		$('#user_id').val('0');
																	}
																	else
																	{
																		$('#user_id').val(user_id);
																		$('#user_name').val(employee_number +' '+ '-' + ' '+user_name);
																	}
																}

																function clearUserNameSearchKeyword()
																{
																	$(".user_name_clear_icon").hide();
																	$("#user_id").val("");
																	$("#user_name").val("");
																}
															</script>
														</div>
													</div>				
												</div>
											</div>

											<div class="row">
												<div class="col-md-4">
													<div class="form-group text-right">
														<label class="col-form-label organization_id"><span class="text-danger">*</span> Organization Name</label>
													</div>				
												</div>
												<div class="col-md-4">
													<div class="form-group">
														<?php
															if($type=='edit' || $type=='view'){
																$searchDropdown		='';
																$pointer_events		='none';
																$access				='readonly';
															}
															else{
																$searchDropdown	='searchDropdown';
																$pointer_events	='';
																$access			= '';
															}
														?>
														<select name="organization_id" id="organization_id" <?php echo $access?> onchange="getBranches(this.value);" style="pointer-events:<?php echo $pointer_events?>" required class="form-control <?php echo $searchDropdown;?>">
															<option value="">- Select -</option>
															<?php 
																foreach($getOrganization as $organization)
																{
																	$selected="";
																	if(isset($edit_data[0]['organization_id']) && $edit_data[0]['organization_id'] == $organization["organization_id"] )
																	{
																		$selected="selected='selected'";
																	}
																	?>
																	<option value="<?php echo $organization["organization_id"];?>" <?php echo $selected;?>><?php echo $organization["organization_name"];?></option>
																	<?php 
																} 
															?>
														</select>

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
															$('#organization_id').change(function (e) { 
																$(".remove_tr").remove();
															});
														</script>
													</div>				
												</div>
											</div>
										</div>
										
										<div class="col-md-6">
											<div class="row">
												<div class="col-md-3">
													<div class="form-group text-right">
														<label class="col-form-label">Description</label>
													</div>				
												</div>
												<div class="col-md-4">
													<div class="form-group">
														<textarea rows="1" name="description" id="description"autocomplete="off" class="form-control"placeholder="Description"><?php echo isset($edit_data[0]['description']) ? $edit_data[0]['description'] : NULL;?></textarea>
													</div>				
												</div>
											</div>
										</div>
									</div>	
									
									
								</section>
								<!-- Header Section End Here-->

								<div class="row mb-3">
									<div class="col-md-6 header-filters">
										<a href="javascript:void(0)" class="filter-icons sec_sec_hide" onclick="sectionShow('SECOND_SECTION','SHOW');">
											<i class="fa fa-chevron-circle-down"></i>
										</a>
										<a href="javascript:void(0)" class="filter-icons sec_sec_show" onclick="sectionShow('SECOND_SECTION','HIDE');" style="display:none;">
											<i class="fa fa-chevron-circle-right"></i>
										</a>
										<h4 class="pl-1"><b>Lines</b></h4>
									</div>
									<div class="col-md-6 text-right float-right">
										<!-- <span style="color:blue;">Currency : <?php //echo CURRENCY_CODE;?></span> -->
									</div>
								</div>

								<!-- Line level start here -->
								<section class="line-section mt-2 sec_section">
								
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
													
													<!-- <th class="tab-md-100"><span class="text-danger">*</span> Organization Name</th> -->
													<th class="tab-md-100"><span class="text-danger">*</span> Branch Name</th>	
													<th class="tab-md-100"><span class="text-danger">*</span> Status</th>	
												</tr>
											</thead>
											<tbody>
												<?php 
													if( isset($lineData) )
													{
														foreach($lineData as $lineResult)
														{
															?>
															<tr>
															<?php 
																if($type == "add" || $type == "edit")
																{
																	$searchDropdown='searchDropdown'
																	?>
																	
																	<td class="action-row tab-md-30"></td>
																	<?php 
																} 
																else{
																	$searchDropdown='';
																}
															?>
																<?php /*
																	?>
																		<td class="tab-md-200">
																			<select id="organization_id" name="organization_id[]" class="form-control <?php echo $searchDropdown?>">
																				<option value="">- Select -</option>
																				<?php 
																					foreach($getOrganization as $organization)
																					{
																						$selected="";
																						if(isset($lineResult["organization_id"]) && $lineResult["organization_id"] == $organization['organization_id'] )
																						{
																							$selected="selected='selected'";
																						}
																						?>
																						<option value="<?php echo $organization['organization_id'];?>" <?php echo $selected;?>><?php echo ucfirst($organization['organization_name']);?></option>
																						<?php 
																					} 
																				?>
																			</select>
																		</td>
																	<?php
																*/ ?>
																		
																
																<td class="tab-md-85">
																	<input type="hidden" name="line_id[]" id="line_id" class="form-control" value="<?php echo isset($lineResult["line_id"])? $lineResult["line_id"] : 0;?>">
																	<select id="branch_id" name="branch_id[]" class="form-control <?php echo $searchDropdown?>">
																		<option value="">- Select -</option>
																		<?php 
																			$organization_id=isset($edit_data[0]["organization_id"]) ? $edit_data[0]["organization_id"] : NULL;
																			$getBranches = $this->branches_model->getOrgBranch($organization_id);
											
																			foreach($getBranches as $branch)
																			{
																				$selected="";
																				if(isset($lineResult["branch_id"]) && $lineResult["branch_id"] == $branch['branch_id'] )
																				{
																					$selected="selected='selected'";
																				}
																				?>
																				<option value="<?php echo $branch['branch_id'];?>" <?php echo $selected;?>><?php echo ucfirst($branch['branch_name']);?></option>
																				<?php 
																			} 
																		?>
																	</select>
																</td>
																<td class="tab-md-85">
																	<script>
																		$('#organization_id').change(function (e) { 
																			$(".remove_tr").remove();
																		});
																	</script>
																	<select class='form-control <?php echo $searchDropdown?>' id='role_status' name='role_status[]'>
																		<?php
																			foreach($this->role_status as $key => $value)
																			{
																				$selected="";
																				if($lineResult["active_flag"] == $key)
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

									<?php 
										if($type != "view")
										{
											?>
											<div class="add-btns">
												<div class="row">
													<div class="col-md-6">
														<a href="javascript:void(0);" onclick="addLine('add_line_item');" id="addLineItem" class="btn btn-primary btn-sm">Add</a>

													</div>
													<div class="col-md-6 text-right">
														<a href="javascript:void(0);" onclick="addLine('add_line_item');" id="addLineItem" class="btn btn-primary btn-sm">Add</a>

													</div>
												</div>
											</div>
											<?php
										}
									?>
								</section>

								
								<!-- Line level end here -->
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
								<a href="<?php echo base_url(); ?>data_access/manage_data_access" class="btn btn-default btn-sm">Close</a>
							</div>

						</div>
					</form>

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
						
					}
					
					function saveBtn(val)
					{
						var user_id 		= $("#user_id").val();
						var organization_id = $("#organization_id").val();
											
						if (user_id && organization_id)
						{
							$(".user_id").removeClass('errorClass');
							$(".organization_id").removeClass('errorClass');

							if ($("#line_items tbody tr").length === 0) {
							
								Swal.fire({
									icon: 'error',
									title: 'Oops...',
									text: 'Please add at least one line level record.'
								});
								return false;
							}

							var lineLevelEntered = false;

							$("table.line_items").find('tr').each(function () 
							{
								var row = $(this);
								// var organization_id 	= row.find('select[name^="organization_id[]"]').val();
								var branch_id 			= row.find('select[name^="branch_id[]"]').val();
								var role_status 		= row.find('select[name^="role_status[]"]').val();
								
								if(branch_id === "" || role_status === "")
								{
									lineLevelEntered = true;
									return false; 
								}
							});
							
							if (lineLevelEntered) {
								Swal.fire({
									icon: 'error',
									title: 'Oops...',
									text: 'Please fill all required fields.'
								});
								return false;
							}
							return true; 
						} 
						else 
						{									
							if (user_id) {
								$(".user_id").removeClass('errorClass');
							} else {
								$(".user_id").addClass('errorClass');
							}
							if (organization_id) {
								$(".organization_id").removeClass('errorClass');
							} else {
								$(".organization_id").addClass('errorClass');
							}
							
							return false;
						}
								

					}

					function addLine(val)
					{
						var user_id 		= $("#user_id").val();
						var organization_id = $("#organization_id").val();

						if (user_id && organization_id) 
						{
							$(".user_id").removeClass('errorClass');
							$(".organization_id").removeClass('errorClass');

							addSOLines();
						} 
						else 
						{
							
							if(user_id){
								$(".user_id").removeClass('errorClass');
							}
							else{
								$(".user_id").addClass('errorClass');
							}
							$(".organization_id").addClass('errorClass');
						}
					}

						
					var counter = 1;
					var this_new_counter = 0;

					function addSOLines()
					{
						var flag = 0;
						duplicateFound=false;
						$('.line-items-error').text('');							
						$(".note_content").show();

						$("table.line_items").find('tr').each(function () 
						{
							var row = $(this);
							// var organization_id 	= row.find('select[name^="organization_id[]"]').val();
							var branch_id 			= row.find('select[name^="branch_id[]"]').val();
							var role_status 		= row.find('select[name^="role_status[]"]').val();
							
							if(branch_id === "" || role_status === "")
							{
								flag = 1;
							}
						});

						if(flag == 0)
						{
							// 	if ($("#line_items tbody tr").length > 0) {
							// 	var newOrganizationId = $("#organization_id" + (counter-1)).val();
							// 	var newBranchId = $("#branch_id" + (counter-1)).val();

							// 	alert("newOrganizationId" + newOrganizationId)
							// 	alert("newBranchId" + newBranchId)
							// 	alert("counter" + counter)

							// 	$("table.line_items tr").not(":first").each(function() {
							// 		var existingOrganizationId = $(this).find("select[name='organization_id[]']").val();
							// 		var existingBranchId = $(this).find("select[name='branch_id[]']").val();

							// 		if (newOrganizationId === existingOrganizationId && newBranchId === existingBranchId) {
							// 			duplicateFound = true;
							// 			return false; // Exit loop if duplicate is found
							// 		}
							// 	});

							// 	if (duplicateFound) {
							// 		Swal.fire({
							// 			icon: 'error',
							// 			title: 'Duplicate Entry',
							// 			text: 'The combination of Organization and Branch already exists.',
							// 			confirmButtonText: 'OK'
							// 		});
							// 		return; // Prevent adding a new row
							// 	}
							// }

							
							var newRow = $("<tr class='remove_tr tabRow"+counter+"'>");
							var cols = "";
							cols += "<td class='tab-md-30 text-center'>"+"<a class='deleteRow' id='deleteRow"+counter+"'><i class='fa fa-times-circle-o' style='color:#fb1b1b61;font-size:16px;'></i></a>" +
										"<input type='hidden' name='counter[]' id='counter"+counter+"' value='" + counter + "'>"+"</td>";
							
					
							// cols += "<td class='tab-md-100'><select class='form-control searchDropdown' name='organization_id[]' id='organization_id"+ counter +"'><option value=''>- Select -</option>";
							// 		<?php //foreach($getOrganization as $organization) { ?>
							// 		cols += "<option value='<?php //echo $organization['organization_id'];?>'><?php //echo $organization['organization_name']; ?></option>";
							// 		<?php //} ?>
							// cols += "</select></td>";

							cols += "<td class='tab-md-85'>" 
									+"<select class='form-control searchDropdown' name='branch_id[]' id='branch_id"+counter+"'><option value=''>- Select -</option></select>"
								+"</td>";

							cols += "<td class='tab-md-100'><select class='form-control searchDropdown' name='role_status[]' id='role_status"+ counter +"'><option value=''>- Select -</option>";
									<?php foreach($this->role_status as $key => $value) { ?>
									cols += "<option value='<?php echo $key;?>'><?php echo $value;?></option>";
									<?php } ?>
							cols += "</select></td>";
							
							
							cols += "</tr>";
							
							newRow.html(cols);
							
							
							$("table.line_items").prepend(newRow);

							$(document).ready(function()
							{ 
								$(".searchDropdown").select2();
							});

							var new_counter = $('#counter'+counter).val();

						$('#deleteRow'+counter).click(function() 
						{
							$('#deleteRow'+new_counter).closest('tr').remove();
						});

						 
						var organization_id=$('#organization_id').val()
						
						$.ajax({
							type: "POST",
							url: "<?php echo base_url(); ?>branches/getOrgBranches",
							data: {organization_id:organization_id, counter:new_counter},

							success: function (data) {

								$("#branch_id" + new_counter).html(data);
							}
						});
						
						
						counter++;
					}
					else 
					{
						Swal.fire(
						{
							icon: 'error',
							text: 'Please fill the previous line level',
						})
					}
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
								if($physical_stock_adjustment['create_edit_only'] == 1 || $this->user_id == 1)
								{
									?>

									<a href="<?php echo base_url();?>setup/settings" class="btn btn-info btn-sm">
										<i class="icon-arrow-left16"></i> 
										Back
									</a>
									<a href="<?php echo base_url(); ?>data_access/manage_data_access/add" class="btn btn-info btn-sm">
										Create Data Access Control
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
									<label class="col-form-label col-md-4 text-right">Employee Name</label>
									<div class="form-group col-md-7">
										<div class="input-wrapper">
											<input type="text" name="user_name" autocomplete="off" id="user_name" value="<?php echo isset($_GET['user_name']) ? $_GET['user_name'] : NULL; ?>" placeholder="User Name" class="form-control">
											<input type="hidden" name="user_id" autocomplete="off" id="user_id" value="<?php echo isset($_GET['user_id']) ?$_GET['user_id'] : NULL; ?>" >
											<div id="UserNameList"></div><!-- Clear icon start -->
											<?php 
												if(isset($_GET["user_id"]) && !empty($_GET["user_id"]))
												{
													$styleDisplay = "display:block";
												}
												else{
													$styleDisplay = "display:none";
												}

												if($type!='view'){
													?>
														<span class="user_name_clear_icon" title="Clear" onclick="clearUserNameSearchKeyword();" style="<?php echo $styleDisplay;?>">
															<i class="fa fa-times" aria-hidden="true"></i>
														</span>
													<?php
												}
											?>
											
											

											<script>
												$(document).ready(function()
												{  
													$('#user_name').keyup(function()
													{  
														var query = $(this).val();  

														if(query != '')  
														{  
															$.ajax({  
																url:"<?php echo base_url();?>data_access/getUserAll",  
																method:"POST",  
																data:{query:query},  
																success:function(data)  
																{  
																	$('#UserNameList').fadeIn();  
																	$('#UserNameList').html(data);  
																}  
															});  
														}  
													});

													$(document).on('click', 'ul.list-unstyled-user_id li', function()
													{  
														var value = $(this).text();
														
														if(value === "Sorry! User Name Not Found.")
														{
															$('#UserNameList').fadeOut();
														}
														else
														{
															$('#UserNameList').fadeOut();  
														}
													});
												});

												function getUserNameList(user_id,employee_number,user_name)
												{
													$('.user_name_clear_icon').show();
													if(user_id == 0)	
													{
														$('#user_id').val('0');
													}
													else
													{
														$('#user_id').val(user_id);
														$('#user_name').val(employee_number +' '+ '-' + ' '+user_name);
													}
												}

												function clearUserNameSearchKeyword()
												{
													$(".user_name_clear_icon1").hide();
													$("#user_id").val("");
													$("#user_name").val("");
												}
											</script>
										</div>
									</div>
								</div>
							</div>
						
							<div class="col-md-3">
								<div class="row">
									<label class="col-form-label col-md-4 text-right">Status</label>
									<div class="form-group col-md-8">
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

							<div class="col-md-2">
								<button type="submit" class="btn btn-info">Search <i class="fa fa-search"></i></button>
								<a href="<?php echo base_url(); ?>data_access/manage_data_access" title="Clear" class="btn btn-default">Clear</a>
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
								<?php
									if( isset($resultData) && count($resultData) > 0 )
									{
										?>
											<div class="col-md-6">
												<!-- <a href="<?php //echo base_url().$this->redirectURL;?>&export=export" class="btn btn-primary btn-sm">Download Excel</a> -->
											</div>

											<div class="col-md-6 float-right text-right">
												<?php 
													$redirect_url = substr($_SERVER['REQUEST_URI'],'1');
												?>
												<input type="hidden" id="redirect_url" value="<?php echo $redirect_url; ?>"/>
																		
												<div class="filter_page">
													<label>
														<!-- <span style="color:blue;">Currency : <?php echo CURRENCY_CODE;?></span> -->
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
										<?php
									}
								?>
							</div>
						    <!-- Page Item Show start -->

							<!-- Table start here -->
							<div class="new-scroller">
								<table id="myTable" class="table table-bordered table-hover">
									<thead>
										<tr>
											<th class="text-center">Controls</th>
											<th>Employee Code</th>
											<th>Employee Name</th>
											<th>Branch</th>
											<th class="tab-md-150">Assessable Branch</th>
											<th class="text-center">Status</th>
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
													<td class='text-center'>
														<?php
															if($physical_stock_adjustment['read_only'] == 1 || $this->user_id == 1)
															{
																?>
																	<div class="dropdown text-center">
																		<button type="button" class="btn btn-outline-info gropdown-toggle btn-sm" data-toggle="dropdown" aria-expanded="false">
																			Action&nbsp;<i class="fa fa-chevron-down"></i>
																		</button>
																		<ul class="dropdown-menu dropdown-menu-right">
																			<li>
																				<a title="View" href="<?php echo base_url(); ?>data_access/manage_data_access/view/<?php echo $row['header_id'];?>">
																					<i class="fa fa-eye"></i> View
																				</a>
																			</li>	
																			<li>
																				<a href="<?php echo base_url(); ?>data_access/manage_data_access/edit/<?php echo $row['header_id'];?>">
																					<i class="fa fa-edit"></i> Edit
																				</a>
																			</li>
																			
																			<li>											
																				<?php 
																					if($row['active_flag'] == 'Y')
																					{
																						?>
																						<a class="unblock" href="<?php echo base_url(); ?>data_access/manage_data_access/status/<?php echo $row['header_id'];?>/N" title="Block">
																							<i class="fa fa-ban"></i> Inactive
																						</a>
																						<?php 
																					} 
																					else
																					{  ?>
																						<a class="block" href="<?php echo base_url(); ?>data_access/manage_data_access/status/<?php echo $row['header_id'];?>/Y" title="Unblock">
																							<i class="fa fa-check"></i> Active
																						</a>
																						<?php 
																					} 
																				?>
																			<li>
																			
																		</ul>
																	</div>
																<?php 
															} 
														?>
													</td>
													<td>
														<?php 
															echo $row['employee_number'];
														?>
													</td>
													<td>
														<?php 
															echo $row['first_name']." ".$row['last_name'];
														?>
													</td>
													<td>
														<?php 
															echo $row['user_branch'];
														?>
													</td>
													<td>
														<?php 
															$getBranchCount = $this->data_access_model->branchCount($row['header_id']);

															if(count($getBranchCount['lineData']) > 0)
															{
																$btn_class='success';
															}
															else
															{
																$btn_class='warning';
															}
														?>
														<a href="<?php echo base_url();?>data_access/manage_data_access/view/<?php echo $row['header_id'];?>" title="Branch Count">
															<span class="btn btn-outline-<?php echo $btn_class; ?>" style="width:60%;">Branch Count (<?php echo count($getBranchCount['lineData']);?>)</span>
														</a>
													</td>
													<td class="text-center">
														<?php 
															if($row['active_flag'] == 'Y')
															{
																?>
																<span class="btn btn-outline-success btn-sm" title="Active"> Active </span>
																<?php 
															} 
															else
															{  ?>
																<span class="btn btn-outline-warning btn-sm" title="Inactive">Inactive </span>
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











