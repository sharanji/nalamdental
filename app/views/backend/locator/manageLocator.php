	<div class="content"><!-- Content start-->
	<div class="card"><!-- Card start-->
		<div class="card-body">
			<?php
				if( isset($type) && $type == "add" || $type == "edit")
				{
					?>
					<form action="" class="form-validate-jquery" enctype="multipart/form-data" method="post">
						<fieldset>
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
										Locator
									</b>
								</h5>
							</div>
							<div class="row">
								<?php /* <div class="form-group col-md-3">
									<label class="col-form-label">Warehouse <span class="text-danger">*</span></label>
									<select id="warehouse_id" name="warehouse_id" required class="form-control searchDropdown">
										<option value="">- Select Warehouse -</option>
										<?php 
											foreach($warehouse as $key)
											{
												$selected="";
												if( isset($edit_data[0]['warehouse_id']) && $edit_data[0]['warehouse_id'] == $key->warehouse_id)
												{
													$selected="selected='selected'";
												}
												?>
												<option value="<?php echo $key->warehouse_id; ?>" <?php echo $selected ?>><?php echo ucfirst($key->warehouse_name); ?></option>
												<?php 
											} 
										?>
									</select>
								</div> */ ?>			
								
								<div class="form-group col-md-3">
									<label class="col-form-label">Locator No. <span class="text-danger">*</span></label>
									<input type="text" name="locator_no" id="locator_no" required class="form-control" value="<?php echo isset($edit_data[0]['locator_no']) ? $edit_data[0]['locator_no'] :"";?>" placeholder="">
								</div>
								<div class="form-group col-md-3">
									<label class="col-form-label">Locator Name <span class="text-danger">*</span></label>
									<input type="text" name="locator_name" id="locator_name" required class="form-control" value="<?php echo isset($edit_data[0]['locator_name']) ? $edit_data[0]['locator_name'] :"";?>" placeholder="">
								</div>
								<div class="form-group col-md-3">
									<label class="col-form-label">Row Name </label>
									<input type="text" name="row_name" id="row_name" class="form-control" value="<?php echo isset($edit_data[0]['row_name']) ? $edit_data[0]['row_name'] :"";?>" placeholder="">
								</div>
								
							</div>

							<div class="row">
								<div class="form-group col-md-3">
									<label class="col-form-label">Rack Code / Name </label>
									<input type="text" name="rack_code" id="rack_code" class="form-control" value="<?php echo isset($edit_data[0]['rack_code']) ? $edit_data[0]['rack_code'] :"";?>" placeholder="">
								</div>
								<div class="form-group col-md-3">
									<label class="col-form-label">Bin </label>
									<input type="text" name="bin_name" id="bin_name" class="form-control" value="<?php echo isset($edit_data[0]['bin_name']) ? $edit_data[0]['bin_name'] :"";?>" placeholder="">
								</div>
								<div class="form-group col-md-3">
									<label class="col-form-label">Locator Description </label>
									<textarea name="locator_description" rows="1" id="locator_description" class="form-control" placeholder=""><?php echo isset($edit_data[0]['locator_description']) ? $edit_data[0]['locator_description'] :"";?></textarea>
								</div>
							</div>
						</fieldset>
						
						<div class="d-flexad float-right">
							<?php 
								if($type == "edit")
								{
									?>
									<a href="<?php echo base_url(); ?>locator/manageLocator/locators/<?php echo $status1;?>/<?php echo $status2;?>" class="btn btn-default">Close</a>
									<button type="submit" class="btn btn-info">Save</button>
									<?php 
								}
								else
								{
									?>
									<a href="<?php echo base_url(); ?>locator/manageLocator/locators/<?php echo $id;?>/<?php echo $status;?>" class="btn btn-default">Close</a>
									<button type="submit" class="btn btn-primary">Save</button>
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
						
							<a href="<?php echo base_url();?>locator/manageSubInventory" class="btn btn-info btn-sm">
						    <i class="icon-arrow-left16"></i> 
						    	Back
					        </a>

							<a href="<?php echo base_url(); ?>locator/manageLocator/add/<?php echo $id;?>/<?php echo $status;?>" class="btn btn-info btn-sm">
								Create Locator
							</a>
						</div>
					</div>

					<form action=""class="form-validate-jquery" method="get">
						<?php 
							$redirect_url = substr($_SERVER['REQUEST_URI'],'1');
						?>
						<input type="hidden" id="redirect_url" value="<?php echo $redirect_url; ?>"/>
												
						<div class="row">
							<div class="col-md-9">
								<section class="trans-section-back-1">
									<div class="row">
										<div class="col-md-3">	
											<input type="search" class="form-control" autocomplete="off" name="keywords" value="<?php echo !empty($_GET['keywords']) ? $_GET['keywords'] :""; ?>" placeholder="Search...">
											<!-- <span class="small-1 text-muted">Note : Calendar</span> -->
										</div>	
										<div class="col-md-3">
											<div class="row">
											<label class="col-form-label col-md-4">Status</label>
												<div class="form-group col-md-8">
													<?php 
														$activeStatusQry = "select sm_list_type_values.list_code,sm_list_type_values.list_value,sm_list_type_values.list_type_value_id from sm_list_type_values 
														left join sm_list_types on sm_list_types.list_type_id = sm_list_type_values.list_type_id
														where 

														sm_list_types.active_flag='Y' and 
														coalesce(sm_list_types.start_date,'".$this->date."') <= '".$this->date."' and 
														coalesce(sm_list_types.end_date,'".$this->date."') >= '".$this->date."' and
														sm_list_types.deleted_flag='N' and


														sm_list_type_values.active_flag='Y' and 
														coalesce(sm_list_type_values.start_date,'".$this->date."') <= '".$this->date."' and 
														coalesce(sm_list_type_values.end_date,'".$this->date."') >= '".$this->date."' and
														sm_list_type_values.deleted_flag='N' and 

														sm_list_types.list_name = 'ACTIVESTATUS'
														order by sm_list_type_values.order_sequence asc";

														$activeStatus = $this->db->query($activeStatusQry)->result_array(); 
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
											<button type="submit" class="btn btn-info waves-effect">Search <i class="fa fa-search" aria-hidden="true"></i></button>
											<a href="<?php echo base_url(); ?>locator/manageLocator/locators/<?php echo $id;?>/<?php echo $status;?>" title="Clear" class="btn btn-default">Clear</a>
										</div>
									</div> 
								</section>
							</div>
							<div class="col-md-3	">
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

					<?php
						$condition = " 1=1 and inv_item_sub_inventory.inventory_id = '".$status."' ";
						$selectQuery = "select inv_item_sub_inventory.inventory_code,inv_item_sub_inventory.inventory_name,organization_name from inv_item_sub_inventory

						left join org_organizations on 
							org_organizations.organization_id = inv_item_sub_inventory.organization_id
						where $condition
						";
						
						$invDetails = $this->db->query($selectQuery)->result_array();
					?>

					<div class="row mb-2">
						<div class="col-md-3">
							<b>Sub Inventory Details :</b>
						</div>
					</div>

					<div class="row mb-2">
						<div class="col-md-2">Organization Name</div>
						<div class="col-md-1">:</div>
						<div class="col-md-3"><?php echo isset($invDetails[0]["organization_name"]) ? $invDetails[0]["organization_name"] : NULL; ?></div>
					</div>

					<div class="row mb-2">
						<div class="col-md-2">Sub Inventory Code</div>
						<div class="col-md-1">:</div>
						<div class="col-md-3"><?php echo isset($invDetails[0]["inventory_code"]) ? $invDetails[0]["inventory_code"] : NULL; ?></div>
					</div>

					<div class="row mb-2">
						<div class="col-md-2">Sub Inventory name</div>
						<div class="col-md-1">:</div>
						<div class="col-md-3"><?php echo isset($invDetails[0]["inventory_name"]) ? $invDetails[0]["inventory_name"] : NULL; ?></div>
					</div>
					   



					<?php 
					 	if(isset($_GET) && !empty($_GET))
					 	{
							?>
								<form action="" method="post">
									<div class="new-scroller">
										<table id="myTable" class="table table-bordered table-hover dataTable">
											<thead>
												<tr>
													<th class="text-center">Controls</th>
													<th class="text-center" onclick="sortTable(2)" class="text-center">Locator No.</th>
													<th class="text-center" onclick="sortTable(3)">Locator Name</th>
													<th class="text-center" onclick="sortTable(1)">Row Name</th>
													<th class="text-center" onclick="sortTable(1)">Rack Code / Name</th>
													<th class="text-center" onclick="sortTable(1)">Bin Name</th>
													<th class="text-center" onclick="sortTable(4)">Status</th>
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
															<?php /*<td style="text-align:center;"><?php echo $i + $firstItem;?></td>
															*/ ?>
															<td style="width: 12%;" class="text-center">
																<div class="dropdown" style="display: inline-block;padding-right: 10px!important;width:92px;">
																	<button type="button" class="btn btn-outline-primary gropdown-toggle waves-effect waves-light btn-sm" data-toggle="dropdown" aria-expanded="false">
																		Action <i class="fa fa-angle-down"></i>
																	</button>
																	<ul class="dropdown-menu dropdown-menu-right dropdown-menu-new">
																		<li>
																			<?php /* <a href="<?php echo base_url(); ?>locator/manageLocator/edit/<?php echo $row['locator_id'];?>">*/?>
																			
																			<a href="<?php echo base_url(); ?>locator/manageLocator/edit/<?php echo $row['locator_id'];?>/<?php echo $type;?>/<?php echo $id;?>/<?php echo $status;?>">
																				<i class="fa fa-edit"></i> Edit
																			</a>
																		</li>
																		<li>											
																			<?php 
																				if($row['active_flag'] == 'Y')
																				{
																					?>
																					<a class="unblock" href="<?php echo base_url(); ?>locator/manageLocator/status/<?php echo $row['locator_id'];?>/N" title="Block">
																						<i class="fa fa-ban"></i> Inactive
																					</a>
																					<?php 
																				} 
																				else
																				{  ?>
																					<a class="block" href="<?php echo base_url(); ?>locator/manageLocator/status/<?php echo $row['locator_id'];?>/Y" title="Unblock">
																						<i class="fa fa-ban"></i> Active
																					</a>
																					<?php 
																				}
																					
																			?>
																		<li>
																	</ul>
																</div>
															</td>
															<td class="text-center"><?php echo $row['locator_no'];?></td>
															<td><?php echo ucfirst($row['locator_name']);?></td>
															<td><?php echo isset($row['row_name']) ? $row['row_name']:"--";?></td>
															<td><?php echo isset($row['rack_code']) ? $row['rack_code']:"--";?></td>
															<td><?php echo isset($row['bin_name']) ? $row['bin_name']:"--";?></td>
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
																	<span class="btn btn-outline-warning btn-sm" title="Inactive"> Inactive </span>
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
													<img src="<?php echo base_url(); ?>uploads/nodata.png" class="text-center">
													<!-- <p class="admin-no-data">No data found.</p> -->
												</div>
												<?php 
											} 
										?>
									</div>
								</form>
								
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
		</div>
	</div><!-- Card end-->
	
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