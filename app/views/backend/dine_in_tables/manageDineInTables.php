
<style>
	.switch {
		position: relative;
		display: inline-block;
		width: 79px;
		height: 25px;
	}
	.switch input {display:none;}

	.slider {
		position: absolute;
		cursor: pointer;
		top: 0;
		left: 0;
		right: 0;
		bottom: 0;
		background-color: #f51658;
		-webkit-transition: .4s;
		transition: .4s;
		border-radius: 34px;
	}

	.slider:before {
		position: absolute;
		content: "";
		height: 15px;
		width: 15px;
		left: 5px;
		bottom: 5px;
		background-color: white;
		-webkit-transition: .4s;
		transition: .4s;
		border-radius: 50%;
	}

	input:checked + .slider {
		background-color: #2ab934;
	}

	input:focus + .slider {
		box-shadow: 0 0 1px #2196F3;
	}

	input:checked + .slider:before {
		-webkit-transform: translateX(26px);
		-ms-transform: translateX(26px);
		transform: translateX(55px);
	}

	/*------ ADDED CSS ---------*/
	.slider:after
	{
		content:'No';
		color: white;
		display: block;
		position: absolute;
		transform: translate(-50%,-50%);
		top: 50%;
		left: 50%;
		font-size: 8px;
		font-family: Verdana, sans-serif;
	}

	input:checked + .slider:after
	{  
		content:'Yes';
	}
</style>


<div class="content"><!-- Content start-->
	<div class="card"><!-- Card start-->
		<div class="card-body">
			<?php 
				$getbranch = $this->db->query("select branch_id,branch_name,branch_code from branch where active_flag='Y' order by branch_name asc")->result_array(); 
				$listTypeValuesQry = "select 
				sm_list_type_values.list_type_value_id,
				sm_list_type_values.list_code,
				sm_list_type_values.list_value	
				from sm_list_type_values

				left join sm_list_types on 
				sm_list_types.list_type_id = sm_list_type_values.list_type_id
				where 
				sm_list_type_values.active_flag = 'Y' and 
				sm_list_types.list_name = 'TABLE-LOCATION'"; 
				$tableLocations = $this->db->query($listTypeValuesQry)->result_array();

				if(isset($type) && $type == "add" || $type == "edit" || $type == "view")
				{
					if($type == "view")
					{
						$this->fieldDisabled = $fieldSetDisabled = "disabled";
						#$dropdownDisabled = "style='pointer-events: none;'";
						$this->searchDropdown = $searchDropdown = "";

						$this->fieldDisabled = $fieldDisabled = "disabled";
						$this->fieldReadonly = $fieldReadonly = "readonly";

						$title = "Dine In Tables";
					}
					else
					{
						if($type == "add")
						{
							
							$this->fieldDisabled = $fieldDisabled = "";
							$this->fieldReadonly = $fieldReadonly = "";
							$this->searchDropdown = $searchDropdown = "searchDropdown";
						} 
						else if($type == "edit")
						{
							$this->fieldDisabled = $fieldDisabled = "";
							$this->fieldReadonly = $fieldReadonly = "";
							$this->searchDropdown = $searchDropdown = "";
						}
						$fieldSetDisabled = "";	
						$title = "Dine In Tables";
					}
					
					if($type == "edit" || $type == "view")
					{
						$requiredIems = "";
						$requiredStar = "";
						$edit_readonly = "pointer-events:none !important; background: #ededed;";
					}
					else
					{
						$requiredIems = "required";
						$requiredStar = '<span class="text-danger">*</span>';
						$edit_readonly = "";
					}	
					?>

					<form action="" --class="form-validate-jquery" enctype="multipart/form-data" method="post">
						<!-- Buttons start here -->
						<div class="row mb-3">
							<div class="col-md-6">
								<h5>
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
										Dine In Tables
									</b>
								</h5>
							</div>
							<div class="col-md-6 text-right">
								<?php 
									if($type == "add" || $type == "edit")
									{
										?>
										<button type="submit" name="save_btn" onclick="return saveBtn('save_btn');" class="btn btn-primary btn-sm">Save</button>
										<button type="submit" name="submit_btn" onclick="return saveBtn('save_btn');" class="btn btn-primary btn-sm">Submit</button>
										<?php 
									} 
								?>
								<a href="<?php echo base_url(); ?>dine_in_tables/manageDineInTables" class="btn btn-default btn-sm">Close</a>
							</div>
						</div>
						<!-- Buttons end here -->
					
						<fieldset <?php echo $fieldSetDisabled;?>>
							<!-- Header Section Start Here-->
							<section class="header-section">
								<div class="row">
									<div class="form-group col-md-3">
										<label class="col-form-label branch_id">Branch <span class="text-danger">*</span></label>
										<select name="branch_id" id="branch_id" onchange="tableLocations(2);" style="<?php echo $edit_readonly;?>" <?php echo $this->fieldDisabled;?> required class="form-control <?php echo $searchDropdown;?>">
											<option value="">- Select -</option>
											<?php 
												foreach($getbranch as $row)
												{
													$selected="";
													if(isset($editData[0]['branch_id']) && ($editData[0]['branch_id'] == $row['branch_id']) )
													{
														$selected="selected='selected'";
													}
													?>
													<option value="<?php echo $row['branch_id']; ?>" <?php echo $selected;?>><?php echo ucfirst($row['branch_name']); ?></option>
													<?php 
												} 
											?>
										</select>
									</div>

									<div class="form-group col-md-3">
										<label class="col-form-label table_location_id">Table Locations <span class="text-danger">*</span></label>
										<select id="table_location_id" name="table_location_id" onchange="tableLocations(2);" required style="<?php echo $edit_readonly;?>" <?php echo $this->fieldDisabled;?> class="form-control <?php echo $searchDropdown;?>">
											<option value="">- Select -</option>
											<?php 
												foreach($tableLocations as $itemcategory)
												{
													$selected="";
													if(isset($editData[0]['table_location_id']) && ($editData[0]['table_location_id'] == $itemcategory['list_type_value_id']) )
													{
														$selected="selected='selected'";
													}
													?>
													<option value="<?php echo $itemcategory['list_type_value_id']; ?>" <?php echo $selected;?>><?php echo $itemcategory['list_value']; ?></option>
													<?php 
												} 
											?>
										</select>
									</div>
								</div>

								<script>
									function tableLocations(val)
									{	
										$(".tbl_rows").remove();

										var branch_id = $("#branch_id").val();
										var table_location_id = $("#table_location_id").val();

										if(branch_id && table_location_id)
										{
											$.ajax({
												type : "POST",
												url  : "<?php echo base_url().'dine_in_tables/ajaxTableLocations';?>",
												data : { branch_id : branch_id, table_location_id : table_location_id }
											}).done(function( msg ) 
											{   
												var split_result = msg.split('@');
												var status = split_result[0];
												var header_id = split_result[1];

												if(status == 'exist')
												{
													$(".addLineItem").addClass("disabledAddButton");

													Swal.fire({
														title: 'Combination already exist!',
														text: "Do you want add new tables!",
														icon: 'warning',
														showCancelButton: true,
														confirmButtonColor: '#070d7d',
														cancelButtonColor: '#d33',
														confirmButtonText: 'Yes'
													}).then((result) => 
													{
														if (result.isConfirmed) 
														{
															window.location = '<?php echo base_url();?>dine_in_tables/manageDineInTables/edit/'+header_id;
														}
														else
														{

														}
													});
												}
												else
												{
													$(".addLineItem").removeClass("disabledAddButton");
												}
											});

										}
										else
										{
											/* Swal.fire({
												icon: 'error',
												//title: 'Amount Mismatch...',
												text: 'Please select Branch & Table Locations',
												//footer: '<a href="">Why do I have this issue?</a>'
											})
											return false; */
										}
									}
								</script>
							</section>
							<!-- Header Section end Here-->

							<!-- Line level start here -->
							<section class="line-section mt-2">
								<div class="row">
									<div class="col-md-12">
										<?php 
											if($type == "view")
											{

											}
											else
											{
												?>
												<a href="javascript:void(0);" onclick="saveBtn('add_line_item');" id="addLineItem" class="btn btn-primary btn-sm addLineItem">Add</a>
												<?php 
											}
										?>
									
										<table class="table table-bordered table-hover product_table mt-3">
											<thead>
												<tr>
													<th colspan="10"><b>Dine In Tables</b></th>
												</tr>
												<tr>
													<?php 
														if($type == "add" || $type == "edit")
														{
															?>
															<th class="text-center"></th>
															<?php 
														} 
													?>
													<th class="text-center">Table Name <span class="text-danger">*</span></th>
													<th class="text-center">Table Code <span class="text-danger">*</span></th>
													<th class="text-center">No of Persons</th>
													<th class="text-center">Assign Captain <span class="text-danger">*</span></th>
													<th class="text-center">Table Available</th> 													
												</tr>
											</thead>
											<tbody>
												<?php 
													if($type == "edit" || $type == "view")
													{
														if(count($dine_in_tables) > 0)
														{
															$i=1;
															$counter=1;
															foreach($dine_in_tables as $row)
															{
																?>
																<tr>
																	<?php 
																		if($type == "edit")
																		{
																			?>
																			<td class='text-center tab-md-30'>
																				<input type='hidden' name='id' name='id' value="<?php echo $i; ?>">
																				<input type='hidden' name='counter' id='counter' value="<?php echo $counter; ?>">
																				<input type='hidden' name='header_id[]' id='header_id<?php echo $counter; ?>' value="<?php echo $row["header_id"]; ?>">
																				<input type='hidden' name='line_id[]' id='line_id<?php echo $counter; ?>' value="<?php echo $row["line_id"]; ?>">
																			</td>
																			<?php 
																		} 
																	?>
																	
																	<td class='tab-medium-width text-right'>
																		<input type='text' name='table_name[]' <?php echo $fieldReadonly; ?> required class='form-control' id='table_name' value='<?php echo $row["table_name"]; ?>'>
																	</td>

																	<td class='tab-medium-width text-right'>
																		<input type='text' name='table_code[]' <?php echo $fieldReadonly; ?> required class='form-control' id='table_code' value='<?php echo $row["table_code"]; ?>'>
																	</td>

																	<td class='tab-medium-width text-right'>
																		<input type='number' name='table_no_of_persons[]' <?php echo $fieldReadonly; ?> class='form-control' id='table_no_of_persons' value='<?php echo $row["table_no_of_persons"]; ?>'>
																	</td>

																	<td class='tab-medium-width text-center'>
																		
																		<a href="javascript::void(0);" data-toggle="modal" data-target="#exampleModal<?php echo $row['line_id'];?>" class="">
																			<?php 
																				$waiterCount = "select * from din_table_waiters where table_header_id ='".$id."' and table_line_id ='".$row['line_id']."' ";
																				$getWaiterCount = $this->db->query($waiterCount)->result_array();

																				if($type == "view")
																				{
																					?>
																					Assigned Captain (<?php echo count($getWaiterCount);?>)
																					<?php
																				}
																				else
																				{
																					?>
																					Assign Captain (<?php echo count($getWaiterCount);?>)
																					<?php
																				}
																			?>
																		</a>

																		<!-- Assign Resource start Modal -->
																		<div class="modal fade" id="exampleModal<?php echo $row['line_id'];?>" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
																			<div class="modal-dialog" style="width:400px;" role="document">
																				<div class="modal-content">
																					<div class="modal-header">
																						<h5 class="modal-title" id="exampleModalLabel">Assign Captain / Waiter</h5>
																						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
																							<span aria-hidden="true">&times;</span>
																						</button>
																					</div>
																					
																					<form action="" method="POST">
																						<div class="modal-body table-scroll-y" id="style-3">
																							<table class="table items table-bordered table-condensed table-hover resource_table<?php echo $row['line_id'];?>">
																								<thead>
																									<tr>
																										<?php 
																											if($type == "view")
																											{
																											}
																											else
																											{
																												?>
																												<th style='width:10px;' class="text-center">Action</th>
																												<?php 
																											} 
																										?>
																										<th>Captain / Waiter</th>
																									</tr>
																								</thead>
																								
																								<tbody>
																									<?php 
																										$getResourceAssignedQry = "select 
																												table_header_id,
																												table_line_id,
																												user_id,
																												waiter_id 
																												from din_table_waiters 
																												where 
																												table_header_id='".$id."' and
																												table_line_id='".$row['line_id']."' ";

																												
																										$getResourceAssigned = $this->db->query($getResourceAssignedQry)->result_array();

																										if(count($getResourceAssigned) > 0)
																										{
																											
																											$empQry = "select 
																											per_people_all.person_id as user_id,
																											per_people_all.first_name as user_name from per_people_all
																											where 1=1 and per_people_all.active_flag='Y' order by per_people_all.first_name";

																											$getResources = $this->db->query($empQry)->result_array();

																											$counter=1;
																											foreach($getResourceAssigned as $resourceAssigned)
																											{
																												
																												?>
																												<tr class="waiter_line<?php echo $resourceAssigned['waiter_id'];?>">
																													<?php 
																														if($type == "view")
																														{
																														}
																														else
																														{
																															?>
																															<td class="text-center">
																																<a onclick="deleteWaiter(<?php echo $resourceAssigned['waiter_id'];?>);"><i class="fa fa-trash"></i> </a>
																																<input type="hidden" name="waiter_id[]" id="waiter_id<?php echo $counter;?>" value="<?php echo $resourceAssigned['waiter_id'];?>">
																																<input type="hidden" name="table_header_id[]" id="table_header_id<?php echo $counter;?>" value="<?php echo $resourceAssigned['table_header_id'];?>">
																																<input type="hidden" name="table_line_id[]" id="table_line_id<?php echo $counter;?>" value="<?php echo $resourceAssigned['table_line_id'];?>">
																																<input type="hidden" name="text_user_id[]" id="text_user_id<?php echo $counter;?>" value="<?php echo $resourceAssigned["user_id"];?>">
																															</td>
																															<?php 
																														} 
																													?>

																													<td>
																														<select class="form-control searchDropdown" required style="width:200px;" name="user_id[]" id="user_id<?php echo $counter;?>">
																															<option value="">- Select -</option>
																															<?php 
																																foreach($getResources as $resources)
																																{
																																	$selected="";
																																	if($resourceAssigned["user_id"] == $resources["user_id"])
																																	{
																																		$selected="selected='selected'";
																																	}
																																	?>
																																	<option value="<?php echo $resources["user_id"];?>" <?php echo $selected; ?>><?php echo $resources["user_name"];?></option>
																																	<?php
																																}
																															?>
																														</select>
																													</td>
																												</tr>
																												<?php
																												$counter++;
																											}
																										}
																									?>
																								</tbody>
																							</table>
																						</div>

																						<?php 
																							if($type == "view")
																							{
																								
																							}
																							else
																							{
																								?>
																								<div class="modal-footer p-0">
																									<div class="row col-md-12">
																										<div class="col-md-3 text-left">
																											<a href="javascript:void(0);" onclick="addNewWaiter('<?php echo $row['header_id'];?>','<?php echo $row['line_id'];?>');" title="Add Waiter" class="btn btn-outline-primary add_new_btn btn-sm mb-3">
																												Add
																											</a>
																										</div>
																										<div class="col-md-6 line-waiter-error text-left mt-2" style="color:red;">
																											
																										</div>
																										<div class="col-md-3 text-right">
																											<button type="submit" name="assignResourceBtn" class="btn btn-primary submit_btn">Save</button>
																										</div>
																									</div>
																									<!-- <button type="button" class="btn btn-default" data-dismiss="modal">Close</button> -->
																								</div>
																								<?php 
																							} 
																						?>
																					</form>
																					
																				</div>
																			</div>
																		</div>
																		
																		<style>
																			.add_new_btn {margin: 5px 4px 4px -7px;}
																			.submit_btn {margin: 0px 25px 12px 10px;}
																		</style>
																		<!-- Assign Resource end Modal -->
																	</td>
																	
																	<td class='text-center tab-medium-width'>
																		<?php 
																			if($row["active_flag"] == 'Y')
																			{
																				?>
																				<label class="switch">
																					<input class="active_flag" name="active_flag[]" type="checkbox" checked id="<?php echo $row["line_id"];?>">
																					<div class="slider round"></div>
																				</label>
																				<?php 
																			} 
																			else
																			{ 
																				?>
																				<label class="switch">
																					<input class="active_flag" name="active_flag[]" type="checkbox" id="<?php echo $row["line_id"];?>">
																					<div class="slider round"></div>
																				</label>
																				<?php 
																			} 
																		?>
																		<input type='hidden' name='line_status[]' class='form-control' id='line_status<?php echo $counter;?>' value='<?php echo $row["active_flag"]; ?>'>
																	</td>
																</tr>
																<?php
																$i++;
																$counter++;
															}
														}
													}
												?>
											</tbody>
										</table>
									</div>
								</div>
								<div class="row mt-2 mb-2">
									<div class="col-md-12">
										<div class="line-items-error"></div>
									</div>
								</div>
							</section>
							<!-- Line level end here -->
							
							<div class="d-flexad text-right mt-4">
								<?php 
									if($type == "add" || $type == "edit")
									{
										?>
										<button type="submit" name="save_btn" onclick="return saveBtn('save_btn');" class="btn btn-info btn-sm">Save</button>
										<button type="submit" name="submit_btn" onclick="return saveBtn('save_btn');" class="btn btn-info btn-sm">Submit</button>
										<?php 
									}
								?>
								<a href="<?php echo base_url(); ?>dine_in_tables/manageDineInTables" class="btn btn-default btn-sm">Close</a>	
							</div>
						</fieldset>
					</form>
					
					<?php 
						if($type == "edit" || $type == "view")
						{
							?>
							<script>
								$('input[type="checkbox"]').on('click',function () 
								{
									var id = $(this).attr("id");
									
									if($(this).is(':checked',true))
									{
										$.ajax({
											type: "get",
											url:"<?php echo base_url().'dine_in_tables/ajaxDiningTableStatus/status/';?>"+id+"/"+1,
											data: { }
										}).done(function( msg ) 
										{   
											toastr.success(msg);
										});
									}
									else 
									{
										$.ajax({
											type: "get",
											url:"<?php echo base_url().'dine_in_tables/ajaxDiningTableStatus/status/';?>"+id+"/"+2,
											data: { }
										}).done(function( msg ) 
										{   
											toastr.success(msg)
										});
									}
								})
							</script>
							<?php 
						} 
					?>

					<?php
						$usersQry = "select * from per_user";
						$getUsers = $this->db->query($usersQry)->result_array();
					?>

					<script> 
						function saveBtn(val)
						{
							var branch_id = $("#branch_id").val();
							var table_location_id = $("#table_location_id").val();

							if( branch_id && table_location_id )
							{
								$(".branch_id").removeClass('errorClass');
								$(".table_location_id").removeClass('errorClass');

								if(val == 'save_btn')
								{
									
									var lineTotalCount = $("table.product_table > tbody  > tr").length;

									if(lineTotalCount > 0)
									{
										return true;
									}
									else
									{

										Swal.fire({
											icon: 'error',
											//title: 'Amount Mismatch...',
											text: 'Atleast one line is required!',
											//footer: '<a href="">Why do I have this issue?</a>'
										})
										return false;
									}	
								}
								else if(val == 'add_line_item')
								{
									addTables();
								}
							}
							else
							{
								if(branch_id) {
									$(".branch_id").removeClass('errorClass');
								} else{
									$(".branch_id").addClass('errorClass');
								}

								if(table_location_id){
									$(".table_location_id").removeClass('errorClass');
								}else{
									$(".table_location_id").addClass('errorClass');
								}
							}
						}

						var type = "<?php echo $type;?>";
						
						if(type == "add")
						{
							var i = 1;
							var counter = 1;
						}
						else if(type == "edit")
						{
							var i = <?php echo isset($dine_in_tables) ? count($dine_in_tables) + 1 : 1;?>;
							var counter =  <?php echo isset($dine_in_tables) ? count($dine_in_tables) + 1 : 1;?>;
						}

						var flag = 0;
						
						function addTables()
						{
							var flag = 0;

							var branch_id = $("#branch_id").val();
							var table_location_id = $("#table_location_id").val();

							if(branch_id != "" && table_location_id != "")
							{
								$("table.product_table").find('input[name^="table_name[]"]').each(function () 
								{
									var table_name = +$(this).val();
									
									if( table_name == "" )
									{
										flag = 1;
									}
								});

								$("table.product_table").find('input[name^="table_code[]"]').each(function () 
								{
									var table_code = +$(this).val();
									
									if( table_code == "" )
									{
										flag = 2;
									}
								});


								if(flag == 0)
								{
									var select_item_status = '';
									select_item_status += "<label class='switch'>";
									select_item_status += "<input type='checkbox' checked name='item_status[]' id='item_status"+ counter +"' class='item_status'>";
									select_item_status += "<div class='slider round'></div></label>";

									var newRow = $("<tr class='dataRowVal tbl_rows'>");
									var cols = "";
									cols += "<td class='text-center tab-md-30'><a class='deleteRow'> <i style='color:#ed2025;' class='fa fa-trash'></i> </a><input type='hidden' name='id' name='id' value="+i+"><input type='hidden' name='counter' value="+counter+"></td>";
									
									cols += "<td class='tab-medium-width'><input type='text' class='form-control' required name='table_name[]' id='table_name"+ counter +"'></td>";
									cols += "<td class='tab-medium-width'><input type='text' class='form-control' required name='table_code[]' id='table_code"+ counter +"'></td>";
									cols += "<td class='tab-medium-width'><input type='number' class='form-control' name='table_no_of_persons[]' id='table_no_of_persons"+ counter +"' value=''></td>";
									cols += "<td class='tab-medium-width text-center'><span style='color:#ddd;'>Assign Captain</span></td>";
									
									cols += '<td class="text-center">'+select_item_status+'<input type="hidden" name="line_status[]" id="line_status'+counter+'" value="Y"></td>';
										
									cols += "</tr>";
									counter++;

									newRow.html(cols);
									$("table.product_table").append(newRow);
								}
								else 
								{
									$('.line-items-error').text('Please fill the all required fields.').animate({opacity: '0.0'}, 2000).animate({}, 1000).animate({opacity: '1.0'}, 2000);
								}
							}
							else
							{
								Swal.fire({
									icon: 'error',
									title: 'Oops...',
									text: 'Please select Branch and Table Location!',
									//footer: '<a href="">Why do I have this issue?</a>'
								})
								//$('#err_product').text('Please select Branch and Table Location').animate({opacity: '0.0'}, 2000).animate({opacity: '0.0'}, 1000).animate({opacity: '1.0'}, 2000);
							}
						}
							
						$("table.product_table").on("click", "a.deleteRow", function (event) 
						{
							$(this).closest("tr").remove();
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
							<a href="<?php echo base_url();?>setup/settings" class="btn btn-info btn-sm">
								<i class="icon-arrow-left16"></i> 
								Back
							</a>
							<a href="<?php echo base_url(); ?>dine_in_tables/manageDineInTables/add" class="btn btn-info btn-sm">
								Create Dine In Table
							</a>
						</div>
					</div>

					<!-- filters-->
					<form action="" class="form-validate-jquery mt-3" method="get">
						<div class="row">
							<div class="col-md-3">
								<div class="row">
									<label class="col-form-label col-md-4 text-right">Branch <!-- <span class="text-danger">*</span> --></label>
									<div class="form-group col-md-7">
										<?php 
											$branchQry = "select branch_id,branch_code,branch_name from branch where active_flag='Y' order by branch.branch_name asc";

											$getBranch = $this->db->query($branchQry)->result_array();
										?>
										<select name="branch_id" id="branch_id" --required class="form-control searchDropdown">
											<option value="">- Select -</option>
											<?php 
												foreach($getBranch as $row)
												{
													$selected="";
													if(isset($_GET['branch_id']) && $_GET['branch_id'] == $row["branch_id"] )
													{
														$selected="selected='selected'";
													}
													?>
													<option value="<?php echo $row["branch_id"];?>" <?php echo $selected;?>><?php echo ucfirst($row["branch_code"]);?> | <?php echo ucfirst($row["branch_name"]);?></option>
													<?php 
												} 
											?>
										</select>
									</div>
								</div>
							</div>

							<div class="col-md-4">
								<div class="row">
									<label class="col-form-label col-md-4 text-right">Table Location <!-- <span class="text-danger">*</span> --></label>
									<div class="form-group col-md-7">
										<select id="table_location_id" name="table_location_id" class="form-control searchDropdown">
											<option value="">- Select -</option>
											<?php 
												foreach($tableLocations as $itemcategory)
												{
													$selected="";
													if(isset($_GET['table_location_id']) && ($_GET['table_location_id'] == $itemcategory['list_type_value_id']) )
													{
														$selected="selected='selected'";
													}
													?>
													<option value="<?php echo $itemcategory['list_type_value_id']; ?>" <?php echo $selected;?>><?php echo $itemcategory['list_value']; ?></option>
													<?php 
												} 
											?>
										</select>
									</div>
								</div>
							</div>

							<div class="col-md-3">
								<div class="row">
									<label class="col-form-label col-md-4 text-right">Status</label>
									<div class="form-group col-md-7">
										<select name="active_flag" id="active_flag" class="form-control searchDropdown">
											<?php 
												foreach($this->activeStatus as $row)
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
								<button type="submit" class="btn btn-info">Search <i class="fa fa-search" aria-hidden="true"></i></button>&nbsp;
								<a href="<?php echo base_url(); ?>dine_in_tables/manageDineInTables" title="Clear" class="btn btn-default">Clear</a>
							</div>					
						</div>
					</form>
					<!-- filters-->
												
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
							
							<div class="new-scroller">
								<table id="myTable" class="table table-bordered table-hover --table-striped --dataTable">
									<thead>
										<tr>
											<th class="text-center">Controls</th>
											<th>Branch Name</th>
											<th>Table Location</th>
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
														<div class="dropdown" >
															<button type="button" class="btn btn-outline-info gropdown-toggle btn-sm"  data-toggle="dropdown" aria-expanded="false">
																Action&nbsp;<i class="fa fa-chevron-down"></i>
															</button>
															<ul class="table-dropdown dropdown-menu dropdown-menu-right">
																<li>
																	<a href="<?php echo base_url(); ?>dine_in_tables/manageDineInTables/edit/<?php echo $row['header_id'];?>">
																		<i class="fa fa-edit"></i> Edit
																	</a>
																</li>
																<li>
																	<a href="<?php echo base_url(); ?>dine_in_tables/manageDineInTables/view/<?php echo $row['header_id'];?>">
																		<i class="fa fa-eye"></i> View
																	</a>
																</li>
																<li>
																	<?php 
																		if($row['active_flag'] == $this->active_flag)
																		{
																			?>
																			<a class="unblock" href="<?php echo base_url(); ?>dine_in_tables/manageDineInTables/status/<?php echo $row['header_id'];?>/N" title="Active">
																				<i class="fa fa-ban"></i> Inactive
																			</a>
																			<?php 
																		} 
																		else
																		{  ?>
																			<a class="block" href="<?php echo base_url(); ?>dine_in_tables/manageDineInTables/status/<?php echo $row['header_id'];?>/Y" title="InActive">
																				<i class="fa fa-ban"></i> Active
																			</a>
																			<?php 
																		} 
																	?>
																<li>
															</ul>
														</div>
													</td>
													<td><?php echo $row['branch_name'];?></td>
													<td><?php echo $row['table_location'];?></td>
													
													<td class="tab-mobile-width text-center">
														<?php 
															if($row['active_flag'] == $this->active_flag)
															{
																?>
																<span class="btn btn-outline-success btn-sm" title="Active">
																	Active 
																</span>
																<?php 
															} 
															else
															{  ?>
																<span class="btn btn-outline-warning btn-sm" title="Inactive">
																	Inactive 
																</span>
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
											<img src="<?php echo base_url();?>uploads/nodata.png">
										</div>
										<?php 
									} 
								?>
							</div>
						
							<?php 
								if(count($resultData) > 0)
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
					<?php 
				} 
			?>
		</div><!-- Card body end-->
	</div><!-- Card end-->
</div><!-- Content end-->

<script>
	function deleteWaiter(waiter_id)
	{
		if(waiter_id)
		{
			$(".waiter_line"+waiter_id).remove();

			$.ajax({
				type:'POST',
				url:'<?php echo base_url();?>dine_in_tables/deleteWaiter/'+waiter_id,
				data:{'waiter_id':waiter_id},
				success: function(result)
				{
					if(result == 1) //Success
					{

					}	
				}
            });
		}
	}

	/** Add New Resource Start **/
	var r_i = 0;
	var r_counter = 1;
	function addNewWaiter(header_id, line_id)
	{
	
		var flag_new = 0;

		$.ajax({
			url: "<?php echo base_url();?>dine_in_tables/geDineInWaiters",
			type: "GET",
			data:{
				'<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
			},
			datatype: "JSON",
			success: function(d)
			{
				data = JSON.parse(d);
				
				$("table.resource_table"+line_id).find('select[name^="user_id"]').each(function () 
				{
					if( +$(this).val() == "" )
					{
						flag_new = 1;
					}
				}); 

				if(flag_new == 0)
				{	
					var select_waiter = "";
				
					select_waiter += '<select class="form-control searchDropdownNew" style="width:200px;" name="user_id[]" id="user_id'+r_counter+'">';
					select_waiter += '<option value="">- Select -</option>';
					for(a=0;a<data['waiters'].length;a++)
					{
						select_waiter += '<option value="' + data['waiters'][a].user_id + '">' + data['waiters'][a].user_name+ '</option>';
					}
					select_waiter += '</select>';

					var  r_newRow = $("<tr class='table_rows'>");
					var r_cols = "";
					r_cols += "<td class='text-center' style='width:10px;'><a class='deleteRow'> <i class='fa fa-trash'></i> </a><input type='hidden' name='id' name='id' value="+r_i+"><input type='hidden' name='counter' name='counter' value="+r_counter+"><input type='hidden' name='table_header_id[]' id='table_header_id"+counter+"' value='"+header_id+"'><input type='hidden' name='table_line_id[]' id='table_line_id"+counter+"' value='"+line_id+"'></td>";
					
					r_cols += "<td>"+select_waiter+"</td>";
					
					r_cols += "</tr>";

					r_newRow.html(r_cols);
					
					$("table.resource_table"+line_id).append(r_newRow);
					
					r_counter++;
					r_i++;

					$(document).ready(function()
					{
						$(".searchDropdownNew").select2();
					});
				}
				else 
				{
					$('.line-waiter-error').text('Please fill the all required fields.').animate({opacity: '0.0'}, 2000).animate({}, 1000).animate({opacity: '1.0'}, 2000);
				}			
			},
			error: function(xhr, status, error) 
			{
				$('#err_product').text('Enter Employee Code / Name').animate({opacity: '0.0'}, 2000).animate({opacity: '0.0'}, 1000).animate({opacity: '1.0'}, 2000);
			}
		});
	}
	/** Add New Resource End **/
</script>

