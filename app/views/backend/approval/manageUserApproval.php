
<?php
	$approvals = accessMenu(approvals);
?>
<!-- Page header start-->
<div class="second-sub-header">
	<div class="page-header page-header-light">
		<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
			<div class="d-flex">
				<div class="breadcrumb">
					<a href="<?php echo base_url();?>admin/dashboard" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> <?php echo get_phrase('Home');?></a>
					<a href="<?php echo base_url();?>approval/manageUserApproval" class="breadcrumb-item"><?php echo $page_title;?></a>
				</div>
			</div>
			
			<?php
				if(isset($type) && $type == "add" || $type == "edit")
				{ 
					
				}
				else
				{ 
					?>
					<div class="text-right new-import-btn">
						<?php /* <a href="<?php echo base_url();?>admin/settings" class='btn btn-light'>Back</a> */?>
						<?php 
							if($approvals['create_edit_only'] == 1 || $this->user_id == 1)
							{
								?>
								<a href="<?php echo base_url(); ?>approval/manageUserApproval/add" class="btn btn-info">
									Add Approver
								</a>
								<?php
							}
						?>
					</div>
					<?php 
				} 
			?>
		</div>
	</div>
</div>
<!-- Page header end-->

<div class="content"><!-- Content start-->
	<div class="card"><!-- Card start-->
		<div class="card-body">
			<?php /*
			<fieldset>
				<legend class="text-uppercase font-size-sm font-weight-bold">
					<?php
						echo !empty($type) ? $type." Apporver" : $page_title;
					?>
				</legend>
			</fieldset>
			*/ ?>
			<?php
				if( isset($type) && $type == "add" || $type == "edit")
				{
					?>
					<form action="" class="form-validate-jquery" enctype="multipart/form-data" method="post">
						<div class="row">
							<?php 
								$query = "select 
										users.random_user_id,
										users.user_id,
										users.first_name
										
										from users
										where 
											users.user_status=1 and users.register_type = 1
													
										";
								$getEmpCode = $this->db->query($query)->result_array();
							?>
							<div class="form-group col-md-3">
								<label class="col-form-label">Employee Code <span class="text-danger">*</span></label>
								<?php 
									if($type == "add")
									{
										?>
										<select name="user_code[]" id="user_code" required multiple class="form-control searchDropdown">
										<?php 
									}
									else if($type == "edit")
									{
										?>
										<input type="hidden" name="user_code[]" id="user_code" multiple  value="<?php echo $edit_data[0]['user_id'];?>">
										<select name="user_code-1" disabled id="user_code" class="form-control searchDropdown">
										<?php
									}
								?>
								
									<option value="">- Select Employee Code -</option>
									<?php 
										foreach($getEmpCode as $row)
										{ 
											$selected="";
											if(isset($edit_data[0]['user_id']) && $edit_data[0]['user_id'] == $row['user_id'])
											{
												$selected="selected='selected'";
											}
											
											?>
											<option value="<?php echo $row['user_id'];?>" <?php echo $selected;?>><?php echo ucfirst($row['random_user_id']);?></option>
											<?php 
										} 
									?>
								</select>
							</div>
						</div>

						<div class="row">
							<div class="form-group col-md-3">
								<label class="col-form-label">Approver Rule <span class="text-danger">*</span></label>

								<?php 
									$ruleQuery = "select rule_name,header_id from org_approval_header 
										where rule_status = 1 order by rule_name asc";
									$getRule = $this->db->query($ruleQuery)->result_array();
								?>
								
								<select name="header_id" id="header_id" required class="form-control searchDropdown">
										
									<option value="">- Select Employee Code -</option>
									<?php 
										foreach($getRule as $row)
										{ 
											$selected="";
											if(isset($edit_data[0]['header_id']) && $edit_data[0]['header_id'] == $row['header_id'])
											{
												$selected="selected='selected'";
											}
											
											?>
											<option value="<?php echo $row['header_id'];?>" <?php echo $selected;?>><?php echo ucfirst($row['rule_name']);?></option>
											<?php 
										} 
									?>
								</select>
							</div>
						</div>
							
						<div class="d-flexad text-right">
							<a href="<?php echo base_url(); ?>approval/manageUserApproval" class="btn btn-default mr-1">Cancel  </a>
							<?php 
								if($type == "edit")
								{
									?>
									<button type="submit" class="btn btn-primary">Update</button>
									<?php 
								}
								else
								{
									?>
									<button type="submit" class="btn btn-primary">Create</button>
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
					<div class="row mb-2">
						<div class="col-md-6"><?php echo $page_title;?></div>
						<div class="col-md-6 float-right text-right">
							<?php 
								if($approvals['create_edit_only'] == 1 || $this->user_id == 1)
								{
									?>
									<a href="<?php echo base_url(); ?>approval/manageUserApproval/add" class="btn btn-info">
										Add Approver
									</a>
									<?php
								}
							?>
						</div>
					</div>
					
					<form action="" method="get">
						<div class="row">
							<div class="col-md-8">
								<div class="row">
									<div class="col-md-4">	
										<input type="search" autocomplete="off" class="form-control" name="keywords" value="<?php echo !empty($_GET['keywords']) ? $_GET['keywords'] :""; ?>" placeholder="Search...">
										<p class="search-note">Note : Rule Name, Approver Type.</p>
									</div>	
									<div class="col-md-3">
										<button type="submit" class="btn btn-info waves-effect">Search <i class="fa fa-search" aria-hidden="true"></i></button>
									</div>
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
										<select name="filter" class="search-dropdown-new" onchange="location.href='<?php echo base_url(); ?>admin/sort_itemper_page/'+$(this).val()+'?redirect=<?php echo $redirect_url; ?>'">
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
					
					<form action="" method="post">
						<div class="new-scroller">
							<table id="myTable" class="table table-bordered table-hover dataTable">
								<thead>
									<tr>
										<?php if(isset($this->user_id) && $this->user_id == 1){?>
										<th class="text-center">Controls</th>
										<?php } ?>

										<th -onclick="sortTable(2)" class="text-center">Rule Name</th>
										<th -onclick="sortTable(2)" class="text-center">Employee Code</th>
										<th -onclick="sortTable(2)">Employee Name</th>
										<th -onclick="sortTable(2)">Email</th>
										<!-- <th -onclick="sortTable(2)">Approvar (Team Lead)</th> -->
										<?php /*  <th -onclick="sortTable(2)">Approvar (Management)</th>
										<th -onclick="sortTable(3)" class="text-center">Status</th> */?>
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
												<!--<td style="text-align:center;"><?php echo $i + $firstItem;?></td>
												-->
												<?php 
													if(isset($this->user_id) && $this->user_id == 1)
													{
														?>
														<td style="width: 5%;">
															<?php
																if($approvals['create_edit_only'] == 1 || $this->user_id == 1)
																{
																	?>
																	<div class="dropdown" style="display: inline-block;-padding-right: 10px!important;width:92px;">
																		<button type="button" class="btn btn-outline-primary gropdown-toggle waves-effect waves-light" data-toggle="dropdown" aria-expanded="false">
																			Action <i class="fa fa-angle-down"></i>
																		</button>
																		<ul class="dropdown-menu dropdown-menu-right">
																			<li>
																				<a title="Edit" href="<?php echo base_url(); ?>approval/manageUserApproval/edit/<?php echo $row['user_approver_id'];?>">
																					<i class="fa fa-pencil"></i> Edit
																				</a>
																				<a title="View" href="#" class="viewmodal" data-toggle="modal" data-target="#viewModal<?php echo $row['user_approver_id'];?>">
																				<i class="fa fa-eye"></i> View
																				</a>
																			</li>
																			<?php /* <li>
																				<?php 
																					if($row['uom_status'] == 1)
																					{
																						?>
																						<a href="<?php echo base_url(); ?>uom/ManageUom/status/<?php echo $row['uom_id'];?>/0" title="Block">
																							<i class="fa fa-ban"></i> Inactive
																						</a>
																						<?php 
																					} 
																					else
																					{  ?>
																						<a href="<?php echo base_url(); ?>uom/ManageUom/status/<?php echo $row['uom_id'];?>/1" title="Unblock">
																							<i class="fa fa-ban"></i> Active
																						</a>
																						<?php 
																					} 
																				?>
																			</li> */?>
																		</ul>
																	</div>
																	<?php 
																}
																else
																{
																	?>
																	--
																	<?php 
																}
															?>	
															<!-- Modal Start-->
															<div class="modal fadebd-example-modal-xl" id="viewModal<?php echo $row['user_approver_id'];?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
																<div class="modal-dialog modal-dialog-centered" style="max-width:50%;" role="document">
																	<div class="modal-content">
																		<div class="modal-header">

																			<div class="row col-md-12 p-0">
																				<div class="col-md-6">
																					<h5 class="modal-title" id="exampleModalLabel">Approvals :</h5>
																				</div>
																				<div class="col-md-6 text-right">
																					<a title="Edit" href="<?php echo base_url(); ?>approval/manageUserApproval/user_approver_id/<?php echo $row['header_id'];?>">
																						<i class="fa fa-pencil"></i>
																					</a>
																				</div>
																			</div>
												
																			<button type="button" class="close" data-dismiss="modal" aria-label="Close">
																				<span aria-hidden="true">&times;</span>
																			</button>
																		</div>
																		
																		<div class="modal-body">
																			<div class="row">
																				<div class="col-md-12">
																					<span class="mt-2">
																						<h4><?php echo ucfirst($row['employee_first_name']);?> <?php echo ucfirst($row['employee_last_name']);?></h4>
																					</span>
																				</div>
																			</div>

																			
																			<div class="row">
																				<div class="col-md-3">
																					Employee Code 
																				</div>
																				<div class="col-md-1">:</div>
																				
																				<div class="col-md-6">
																					<?php echo $row['employee_code'];?>
																				</div>
																			</div>

																			<div class="row mt-2">
																				<div class="col-md-3">
																					Email
																				</div>
																				<div class="col-md-1">:</div>
																				
																				<div class="col-md-6">
																					<?php echo $row['email'];?>
																				</div>
																			</div>


																			<div class="row mt-2">
																				<div class="col-md-3">
																					Rule Name
																				</div>
																				<div class="col-md-1">:</div>
																				
																				<div class="col-md-6">
																					<?php echo ucfirst($row['rule_name']);?>
																				</div>
																			</div>

																			
																			
																		</div>	
																	</div>
																		
																	<div class="modal-footer">
																	<!--	<button type="button" class="btn btn-light" data-dismiss="modal">Close </button>
																		<button type="submit" class="btn btn-primary ml-3">Submit </button>
																	--></div>
																		
																</div>
															</div>
															<!-- Modal End-->	
														</td>
														<?php 
													} 
												?>
												
												<td class="text-center"><?php echo ucfirst($row['rule_name']);?></td>
												<td class="text-center"><?php echo $row['employee_code'];?></td>
												<td><?php echo ucfirst($row['employee_first_name'])." ".ucfirst($row['employee_last_name']);?></td>
												<td><?php echo $row['email'];?></td>
												

												<?php /*
													if(isset($row['reporting_manager_name']) && !empty($row['reporting_manager_name']))
													{
														?>
															<td><?php echo ucfirst($row['reporting_manager_name']);?> </td>
														<?php 
													}else
													{
														?>
															<td class="text-warning h6"> <?php echo "Not Assigned";?> </td>
														<?php 
													} */
												?>
												<?php /*
												<td>
													
												<?php
													if(	!empty($row['fyi_notification_id']) )
													{
														$explodeBrand = explode(",",$row['fyi_notification_id']);

														foreach($explodeBrand as $key => $notification_user_id)
														{
															$fyiUsers = "select users.first_name from $this->database2.users 
															where users.user_id = '".$notification_user_id."' " ;
															$getFyiUsersName = $this->db->query($fyiUsers)->result_array();

															if( isset($getFyiUsersName[0]["first_name"]) && !empty($getFyiUsersName[0]["first_name"]) )
															{
																echo ucfirst($getFyiUsersName[0]["first_name"]).", ";
															}
														}												
													}
													else
													{
														echo "-";
													}
												?>
												</td>
												*/ ?>

												<?php /* <td style="width: 12%;" style="text-center">
													<?php 
														if($row['uom_status'] == 1)
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
												</td> */?>
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
					</form>
					
					<div class="row">
						<div class="col-md-4 showing-count">
							Showing <?php echo $starting;?> to <?php echo $ending;?> of <?php echo $totalRows;?> entries
						</div>
						
						<!-- pagination start here -->
						<?php 
							if( isset($pagination) )
							{
								?>	
								<div class="col-md-8" class="admin_pagination"><?php foreach ($pagination as $link){echo $link;} ?></div>
								<?php
							}
						?>
						<!-- pagination end here -->
					</div>
					<?php 
				} 
			?>
		</div><!-- Card end-->
	</div><!-- Content body end-->
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
</script>