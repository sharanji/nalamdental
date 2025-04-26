

<div class="content"><!-- Content start-->
	<div class="card"><!-- Card start-->
		<div class="card-body">
			<?php
				if( isset($type) && $type == "add" || $type == "edit" || $type == "view")
				{
					if($type == "view")
					{
						$fieldSetDisabled = "disabled";
						$searchDropdown = "";
						$editfieldSetDisabled = "";
						$editsearchDropdown = "";
						$searchDropdownEditHeader = "";
					}
					else if($type == "edit")
					{
						$fieldSetDisabled = "";
						$editfieldSetDisabled = "style='pointer:none;'";
						$searchDropdownEditHeader = "";
						$searchDropdown = "searchDropdown";
					}
					else
					{
						$fieldSetDisabled = "";
						$searchDropdown = "searchDropdown";

						$editfieldSetDisabled = "";
						$editsearchDropdown = "";
						$searchDropdownEditHeader = "";
					}

					?>
					<form action="" --class="form-validate-jquery" enctype="multipart/form-data" method="post">
						<fieldset <?php echo $fieldSetDisabled;?>>
							<div class="row mb-3">
								<div class="form-group col-md-3">
									<div>
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
												Approval Type
											</b>
										</h5>
									</div>
									<label class="col-form-label">Approval Type <span class="text-danger">*</span></label>
								
									<select name="approval_type" id="approval_type" required <?php echo $editfieldSetDisabled;?> class="form-control <?php if($type=="add"){echo $searchDropdown;}else if($type=="edit"){ echo $searchDropdownEditHeader;} ?>">
										<option value="">- Select-</option>
										<?php 
											foreach($this->approval_type as $key=>$value)
											{ 
												$selected="";
												if( isset($edit_data[0]['approval_type']) && $edit_data[0]['approval_type'] == $key )
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
							
							<?php
								$directQry = "select 
									per_user.user_id,
									per_people_all.employee_number,
									per_people_all.first_name,
									per_people_all.last_name
									from per_people_all 
									join per_user on per_user.person_id =per_people_all.person_id
									where 
										per_people_all.active_flag ='Y'
										AND per_user.active_flag ='Y'";
								$getDirector = $this->db->query($directQry)->result_array();
								
								$levelQry = "select level_name,level_id from  org_approval_levels where level_status = 1";
								$getLevel = $this->db->query($levelQry)->result_array();							
							?>
							<div class="add_new_row">
								<div class="row">
									<div class="col-md-3">
										<label class="col-form-label"><b>Approver <span class="text-danger">*</span></b></label>
									</div>
									<div class="col-md-2">
										<label class="col-form-label"><b>From Amount <span class="text-danger">*</span></b></label>
									</div>
									<div class="col-md-2">
										<label class="col-form-label"><b>To Amount <span class="text-danger">*</span></b></label>
									</div>
									<div class="col-md-3">
										<label class="col-form-label"><b>Level <span class="text-danger">*</span></b></label>
									</div>
								</div>

								<?php
									if(isset($type) && $type =='add')
									{
										?>
										<div class="row mb-3">
											<div class="col-md-3">
												<select name="user_id[]" id="user_id1" required class="form-control searchDropdown">
													<option value="">- Select -</option>
													<option value="-1">Auto Approval</option>
													<?php 
														foreach($getDirector as $row)
														{
															?>
															<option value="<?php echo $row["user_id"];?>"><?php echo $row["employee_number"];?> - <?php echo $row["first_name"];?> <?php echo $row["last_name"];?> </option>
															<?php 
														} 
													?>
												</select>
											</div>

											<div class="col-md-2">
												<input type="number" name="from_amount[]" autocomplete="off" required id="from_amount1" class="form-control single_quotes"/>
											</div>
											
											<div class="col-md-2">
												<input type="number" name="to_amount[]" autocomplete="off" required id="to_amount1" class="form-control single_quotes"/>
											</div>
											
											<div class="col-md-2">
												<select name="level_id[]" id="level_id1" required class="form-control searchDropdown">
													<option value="">- Select -</option>
													<?php
														foreach($getLevel as $row) 
														{
															?>
															<option value="<?php echo $row['level_id'];?>"><?php echo ucfirst($row['level_name']);?></option>
															<?php
														}
													?>
												</select>
											</div>	
										</div>
										<?php 
									} 
									else if(isset($type) && ($type =='edit' || $type =='view'))
									{
										$approvalQry = "select * from org_approval_line where header_id=".$id." ";
										$getapprovalQry = $this->db->query($approvalQry)->result_array();

										if(count($getapprovalQry) == 0 &&  $type !='view')
										{
											?>
											<div class="row mb-3">
												<div class="col-md-3">
													<select name="user_id[]" id="user_id1" required class="form-control <?php echo $searchDropdown;?>">
														<option value="">- Select -</option>
														<option value="-1">Auto Approval</option>
														<?php 
															foreach($getDirector as $row)
															{
																?>
																<option value="<?php echo $row["user_id"];?>"><?php echo $row["employee_number"];?> - <?php echo $row["first_name"];?> <?php echo $row["last_name"];?> </option>
																<?php 
															} 
														?>
													</select>
												</div>

												<div class="col-md-2">
													<input type="number" name="from_amount[]" autocomplete="off" required id="from_amount1" class="form-control"/>
												</div>
												
												<div class="col-md-2">
													<input type="number" name="to_amount[]" autocomplete="off" required id="to_amount1" class="form-control"/>
												</div>
												
												<div class="col-md-2">
													<select name="level_id[]" id="level_id1" required class="form-control <?php echo $searchDropdown;?>">
														<option value="">- Select -</option>
														<?php
															foreach($getLevel as $row) 
															{
																?>
																<option value="<?php echo $row['level_id'];?>"><?php echo ucfirst($row['level_name']);?></option>
																<?php
															}
														?>
													</select>
												</div>	
											</div>
											<?php 
										}
										else
										{
											$counter = 1;
											foreach($getapprovalQry as $editRow)
											{
												?>
												<div class="row mb-3 line_row<?php echo $counter;?>">
													<div class="col-md-3">
														<select name="user_id[]" id="user_id<?php echo $counter;?>" required class="form-control <?php echo $searchDropdown;?>">
															<option value="">- Select -</option>
															<?php if($counter == 1){?>
																<option value="-1" <?php if(isset($editRow['user_id']) && $editRow['user_id'] == '-1') { ?>selected <?php } ?>>Auto Approval</option>
															<?php } ?>
															<?php 
																foreach($getDirector as $row)
																{
																	$selected="";
																	if(isset($editRow['user_id']) && $editRow['user_id'] == $row['user_id'])
																	{
																		$selected="selected='selected'";
																	}
																	?>
																	<option value="<?php echo $row["user_id"];?>" <?php echo $selected;?>><?php echo $row["employee_number"];?> - <?php echo $row["first_name"];?> <?php echo $row["last_name"];?> </option>
																	<?php 
																} 
															?>
														</select>
														<input type="hidden" name="line_id[]" value="<?php echo isset($editRow['line_id']) ? $editRow['line_id'] :"";?>"/>
													</div>

													<div class="col-md-2">
														<input type="number" name="from_amount[]" autocomplete="off" required id="from_amount<?php echo $counter;?>" value="<?php echo isset($editRow['from_amount']) ? $editRow['from_amount'] : NULL;?>" class="form-control"/>
													</div>
													
													<div class="col-md-2">
														<input type="number" name="to_amount[]" autocomplete="off" required id="to_amount<?php echo $counter;?>" value="<?php echo isset($editRow['to_amount']) ? $editRow['to_amount'] : NULL;?>" class="form-control"/>
													</div>
													
													<div class="col-md-2">
														<select name="level_id[]" id="level_id<?php echo $counter;?>" required class="form-control <?php echo $searchDropdown;?>">
															<option value="">- Select -</option>
															<?php
																foreach($getLevel as $row) 
																{
																	$selected="";														
																	if(isset($editRow['level_id']) && $editRow['level_id'] == $row['level_id'])
																	{
																		$selected="selected='selected'";
																	}
																	?>
																	<option value="<?php echo $row['level_id'];?>" <?php echo $selected;?>><?php echo ucfirst($row['level_name']);?></option>
																	<?php
																}
															?>
														</select>
													</div>	
													<?php 
														if($type != "view")
														{
															?>
															<div class="col-md-1 mt-2">
																<a href="javascript:void(0);" onclick="editRemoveLine(<?php echo $editRow['line_id'];?>,<?php echo $counter;?>);" >
																	<i class="fa fa-trash"></i>
																</a>
															</div>
															<?php 
														} 
													?>
												</div>
												<?php
												$counter++;
											}
										} 	 
									} 
								?>
							</div>

							<script>
								function editRemoveLine(line_id,counter)
								{
									var x = confirm("Are you sure you want to delete?");
									if(x)
									{
										if(line_id)
										{
											$.ajax({
												type: "POST",
												url:"<?php echo base_url().'approval/ajaxDeleteApprover';?>",
												data: { line_id: line_id }
											}).done(function( result ) 
											{   
												if(result == 1)
												{
													$(".line_row"+counter).remove();
												}
											});
										}
									}
								}
							</script>

							<?php 
								if($type != "view")
								{
									?>
									<div class="row mt-3">
										<div class="col-md-7"></div>
										<div class="col-md-2 text-right"><a href="javascript:void(0);" onclick="addNewLine();" class="btn btn-info btn-sm">Add</a></div>
									</div>
									<?php 
								} 
							?>
							
							<script>
								var createType = '<?php echo $type; ?>';
								
								if( createType == "add" )
								{
									var counter = 2;
								}
								else if( createType == "edit")
								{
									var counter = '<?php echo isset($getapprovalQry) ? count($getapprovalQry) + 1 : 2;?>';
								}

								function addNewLine()
								{
									$('#remove_approval_level').show();
									
									var newRow = $('<div class="row mt-3 remove_line'+counter+'">');
									var cols = "";
									if(counter == 1){ //if Counter - 1 ( Auto Approval enabled )
										cols += '<div class="col-md-3"><select name="user_id[]" id="user_id'+counter+'" required class="form-control searchDropdown" ><option value="">- Select -</option> <option value="-1">Auto Approval</option><?php foreach($getDirector as $director){?><option value="<?php echo $director["user_id"];?>"><?php echo $director["employee_number"];?> - <?php echo ucfirst($director["first_name"]);?> <?php echo $director["last_name"];?></option><?php } ?></select></div>';
									}else{
										cols += '<div class="col-md-3"><select name="user_id[]" id="user_id'+counter+'" required class="form-control searchDropdown" ><option value="">- Select -</option><?php foreach($getDirector as $director){?><option value="<?php echo $director["user_id"];?>"><?php echo $director["employee_number"];?> - <?php echo ucfirst($director["first_name"]);?> <?php echo $director["last_name"];?></option><?php } ?></select></div>';
									}

									cols += '<div class="col-md-2"><input type="number" id="from_amount'+ counter+ '" required class="form-control" name="from_amount[]" autocomplete="off" value=""></div>';
									cols += '<div class="col-md-2"><input type="number" id="to_amount'+counter+ '" required class="form-control" name="to_amount[]" autocomplete="off"></div>';
									cols += '<div class="col-md-2"><select id="level_id'+counter+'" required class="form-control searchDropdown" name="level_id[]" ><option value="">- Select -</option><?php foreach($getLevel as $level){?><option value="<?php echo $level["level_id"];?>"><?php echo ucfirst($level["level_name"]);?> </option><?php } ?></select></div>';
									cols += '<div class="col-md-1 mt-2"><a href="javascript:void(0);" onclick="removeLine('+counter+');"><i class="fa fa-trash"></a></div>';
									cols += "</div>";

									$(document).ready(function()
									{ 
										$(".searchDropdown").select2();
									});
									newRow.html(cols);
									$(".add_new_row").append(newRow);
									counter++;
								}
								
								function removeLine(counter)
								{
									$(".remove_line"+counter).remove();
								}
							</script>
						</fieldset>
						
						<div class="d-flexad text-right">
							<a href="<?php echo base_url(); ?>approval/ManageApproval" class="btn btn-default btn-sm">Close</a>
							<?php 
								if($type != "view")
								{
									?>
									<button type="submit" class="btn btn-primary btn-sm">Save</button>
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
					<!-- buttons start here -->
					<div class="row mb-2">
						<div class="col-md-6"><h5><b><?php echo $page_title;?></b></h5></div>
						<div class="col-md-6 float-right text-right">	
							<a href="<?php echo base_url();?>setup/settings" class="btn btn-info btn-sm">
								<i class="icon-arrow-left16"></i> 
								Back
							</a>
							<a href="<?php echo base_url(); ?>approval/ManageApproval/add" class="btn btn-info btn-sm">
								Create Approval
							</a>
						</div>
					</div>
					<!-- buttons end here -->

					<!-- Filters start here -->
					<form action="" class="form-validate-jquery" method="get">
						<div class="row mt-3">
							<div class="col-md-4">
								<div class="row">
									<label class="col-form-label col-md-4">Approval Type</label>
									<div class="form-group col-md-8">
										<select name="approval_type" id="approval_type" class="form-control searchDropdown">
											<option value="">- Select -</option>
											<?php 
												foreach($this->approval_type as $key=>$value)
												{ 
													$selected="";
													if( isset($_GET['approval_type']) && $_GET['approval_type'] == $key )
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
							</div>

						
							<div class="col-md-4">
								<div class="row">
									<div class="col-md-3">
										<button type="submit" class="btn btn-info">Search <i class="fa fa-search" aria-hidden="true"></i></button>
									</div>
									<div class="col-md-3">
										<a href="<?php echo base_url(); ?>approval/manageApproval" title="Clear" class="btn btn-default">Clear</a>
									</div>
								</div>
							</div>
						</div>
					</form>
					<!-- Filters end here -->

					<?php 
						if(isset($_GET) &&  !empty($_GET))
						{
							?>
							<!-- Page Item Show start -->
							<div class="row mt-2">
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
								<table id="myTable" class="table table-bordered table-hover dataTable">
									<thead>
										<tr>
											<th class="text-center">Controls</th>
											<th>Approval Type</th>
											<th class="text-center">Created Date</th>
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
														<div class="dropdown" style="display: inline-block;-padding-right: 10px!important;width:92px;">
															<button type="button" class="btn btn-outline-primary gropdown-toggle waves-effect waves-light" data-toggle="dropdown" aria-expanded="false">
																Action <i class="fa fa-angle-down"></i>
															</button>
															<ul class="dropdown-menu dropdown-menu-right dropdown-menu-new">
																<li>
																	<a title="Edit" href="<?php echo base_url(); ?>approval/manageApproval/edit/<?php echo $row['header_id']; ?>">
																		<i class="fa fa-pencil"></i> Edit
																	</a>
																</li>

																<li>
																	<a title="Edit" href="<?php echo base_url(); ?>approval/manageApproval/view/<?php echo $row['header_id']; ?>">
																		<i class="fa fa-eye"></i> View
																	</a>
																</li>
															</ul>
														</div>
													</td>
													
													<td>
														<?php 
															foreach($this->approval_type as $key => $value)
															{ 
																if( $row['approval_type'] == $key)
																{
																	echo $value;
																}
															} 
														?>
													</td>
													
													<td class="text-center"><?php echo date(DATE_FORMAT,strtotime($row['created_date']));?></td>
													
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
	</div><!-- Content body end-->
</div><!-- Content end-->

