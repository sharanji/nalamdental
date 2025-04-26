
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
											 Sub Inventory
										</b>
									</h5>
								</div>

							<div class="row">
								
								
								<div class="form-group col-md-3">
									<label class="col-form-label">Organization <span class="text-danger">*</span></label>
									<?php 
										$getorganization = $this->db->query("select organization_code, organization_name ,organization_id from  org_organizations where active_flag='Y'")->result_array();
									?>
									<select	id="organization_id" name="organization_id" required class="form-control searchDropdown">
										<option value="">- Select Organization -</option>
										<?php 
											foreach($getorganization as $key)
											{
												$selected="";
												if( isset($edit_data[0]['organization_id']) && $edit_data[0]['organization_id'] == $key["organization_id"])
												{
													$selected="selected='selected'";
												}
												?>
												<option value="<?php echo $key["organization_id"] ; ?>" <?php echo $selected;?>><?php echo $key['organization_code'];?> - <?php echo $key['organization_name'];?></option>
												<?php 
											} 
										?>
									</select>
								</div>				
								
								<div class="form-group col-md-3">
									<label class="col-form-label">Sub Inventory Code <span class="text-danger">*</span></label>
									<input type="text" name="inventory_code" id="inventory_code" maxlength="6" required class="form-control single_quotes" value="<?php echo isset($edit_data[0]['inventory_code']) ? $edit_data[0]['inventory_code'] :"";?>" placeholder="">
								</div>
								<div class="form-group col-md-3">
									<label class="col-form-label">Sub Inventory Name <span class="text-danger">*</span></label>
									<input type="text" name="inventory_name" id="inventory_name" required class="form-control single_quotes" value="<?php echo isset($edit_data[0]['inventory_name']) ? $edit_data[0]['inventory_name'] :"";?>" placeholder="">
								</div>
								<div class="col-lg-3 col-md-3 col-sm-3">
									<div class="check-box mb-3" style="margin:43px 0px 0px 0px;">
										<!-- <div class="form-group">
											<label for="val-email">Terms and Conditions</label>
											<textarea rows="1" class="form-control" name="terms_and_conditions" id="terms_and_conditions"> <?php echo isset($data[0]->terms_and_conditions) ? $data[0]->terms_and_conditions : "" ;?></textarea>
										</div> -->
										<?php 
											$checked_terms ="";
											if( isset($edit_data[0]['locator_availability']) && $edit_data[0]['locator_availability'] == 1 )
											{
												$checked_terms ="checked='checked'";
											} 
										?>
										<input type="checkbox" name="locator_availability" value='1' id="chk_terms" <?php echo $checked_terms;?>>&nbsp; &nbsp; <span style="color:#6673fd;font-size: 12px;">Locator Availability</span></span>
									</div>
								</div>
							</div>

							<div class="row">
								<div class="col-md-3">
									<label class="col-form-label"> Valid From </label>	
									<input type="text" name="valid_from_date" id="start_date" class="form-control" readonly value="<?php echo isset($edit_data[0]['valid_from_date']) ? $edit_data[0]['valid_from_date'] :"";?>" placeholder="From Date">
									<p style="font-size:12px;color:#888888;"><b></b></p>
								</div>
								
								<div class="col-lg-3">	
									<label class="col-form-label"> Valid To </label>
								     	<input type="text" name="valid_to_date" id="end_date" class="form-control" readonly value="<?php echo isset($edit_data[0]['valid_to_date']) ? $edit_data[0]['valid_to_date'] :"";?>" placeholder="To Date">
								</div>
								
								<div class="form-group col-md-3">
									<label class="col-form-label">Sub Inventory Description </label>
									<textarea name="inventory_description" rows='1' col='1' id="inventory_description" class="form-control single_quotes" placeholder=""><?php echo isset($edit_data[0]['inventory_description']) ? $edit_data[0]['inventory_description'] :"";?></textarea>
								</div>
							</div>
						</fieldset>
						
						<div class="d-flexad float-right">
							<a href="<?php echo base_url(); ?>locator/manageSubInventory" class="btn btn-default">Close</a>
							<?php 
								if($type == "edit")
								{
									?>
									<button type="submit" class="btn btn-info ml-1">Save</button>
									<?php 
								}
								else
								{
									?>
									<button type="submit" class="btn btn-info ml-1">Save</button>
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
						<div class="col-md-6"><h5><b><?php echo $page_title;?></b></h5></div>
						<div class="col-md-6 float-right text-right">
						    <a href="<?php echo base_url();?>setup/settings" class="btn btn-info btn-sm">
								<i class="icon-arrow-left16"></i> 
								Back
							</a>

							<a href="<?php echo base_url(); ?>locator/manageSubInventory/add" class="btn btn-info btn-sm">
								Create Sub Inventory
							</a>
						</div>
					</div>

					<form action="" method="get">
						<div class="row">
							<div class="col-md-12">
								<section class="trans-section-back-1">
									<div class="row">
										<div class="col-md-3">
											<div class="row">
												<label class="col-form-label col-md-4">Keywords</label>
												<div class="form-group col-md-8">
													<input type="search" autocomplete="off" class="form-control" name="keywords" value="<?php echo !empty($_GET['keywords']) ? $_GET['keywords'] :""; ?>" placeholder="Sub Inv Code / Name">
												</div>
											</div>
										</div>

										<div class="col-md-4">
											<div class="row">
												<label class="col-form-label col-md-4">Organization</label>
												<div class="form-group col-md-8">
													<?php 
														$getorganization = $this->db->query("select organization_code, organization_name ,organization_id from  org_organizations where active_flag='Y'")->result_array();
													?>
													<select	id="organization_id" name="organization_id" class="form-control searchDropdown">
														<option value="">- Select -</option>
														<?php 
															foreach($getorganization as $key)
															{
																$selected="";
																if( isset($_GET['organization_id']) && $_GET['organization_id'] == $key["organization_id"])
																{
																	$selected="selected='selected'";
																}
																?>
																<option value="<?php echo $key["organization_id"]; ?>" <?php echo $selected;?>><?php echo $key['organization_code'];?> - <?php echo $key['organization_name'];?> </option>
																<?php 
															} 
														?>
													</select>
												</div>
											</div>
										</div>

										<div class="col-md-2">
											<div class="row">
												<label class="col-form-label col-md-4">Status</label>
												<div class="form-group col-md-7">
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
											<a href="<?php echo base_url(); ?>locator/manageSubInventory" title="Clear" class="btn btn-default">Clear</a>
										</div>
									</div>
								</section>
							</div>
						</div>
					</form>
					<?php 
					 	if(isset($_GET) && !empty($_GET))
					 	{
							?>
							    <div class="row">
									<div class="col-md-8">
										
									</div>
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



								<form action="" method="post">
									<div class="new-scroller">
										<table id="myTable" class="table table-bordered table-hover  --table-striped dataTable">
											<thead>
												<tr>
													<th class="text-center">Controls</th>
													<th onclick="sortTable(1)">Organization Name</th>
													<th onclick="sortTable(2)" class="text-center">Sub Inventory Code</th>
													<th onclick="sortTable(3)">Sub Inventory Name</th>
													<th onclick="sortTable(4)" class="text-center">Locators</th>
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
																			<a href="<?php echo base_url(); ?>locator/manageSubInventory/edit/<?php echo $row['inventory_id'];?>">
																				<i class="fa fa-edit"></i> Edit
																			</a>
																		</li>
																		<li>											
																			<?php 
																				if($row['active_flag'] == 'Y')
																				{
																					?>
																					<a class="unblock" href="<?php echo base_url(); ?>locator/manageSubInventory/status/<?php echo $row['inventory_id'];?>/N" title="Block">
																						<i class="fa fa-ban"></i> Inactive
																					</a>
																					<?php 
																				} 
																				else
																				{  ?>
																					<a class="block" href="<?php echo base_url(); ?>locator/manageSubInventory/status/<?php echo $row['inventory_id'];?>/Y" title="Unblock">
																						<i class="fa fa-ban"></i> Active
																					</a>
																					<?php 
																				} 
																			?>
																		<li>
																			
																	</ul>
																</div>
															</td>
															<td><?php echo ucfirst($row['organization_name']);?></td>
															<td class="text-center"><?php echo $row['inventory_code'];?></td>
															<td><?php echo ucfirst($row['inventory_name']);?></td>
															<td class="text-center">
																<?php 
																	$locatorQry = "select locator_id from inv_item_locators where inventory_id='".$row['inventory_id']."' ";
																	$getLocators = $this->db->query($locatorQry)->result_array();
																	if($row['locator_availability'] && $row['locator_availability'] == 1)
																	{
																		if(count($getLocators) > 0)
																		{	
																			$btnClass="primary";
																		}else{
																			$btnClass="warning";
																		}
																		?>
																			<a href="<?php echo base_url();?>locator/manageLocator/locators/<?php echo $row['organization_id'];?>/<?php echo $row['inventory_id'];?>" class="btn btn-outline-<?php echo $btnClass; ?> btn-sm">Locators ( <?php echo count($getLocators);?> )</a>
																		<?php
																	}
																?>
															</td>
															<td class="text-center">
																<?php 
																	if($row['active_flag'] == 'Y')
																	{
																		?>
																		<span class="btn btn-outline-success btn-sm" title="Active"><i class="fa fa-check"></i> Active </span>
																		<?php 
																	} 
																	else
																	{  ?>
																		<span class="btn btn-outline-warning btn-sm" title="Inactive"><i class="fa fa-check"></i> Inactive </span>
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

