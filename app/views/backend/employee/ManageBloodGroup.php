<div class="content"><!-- Content start-->
	<div class="card"><!-- Card start-->
		<div class="card-body">
			<?php
				if(isset($type) && $type == "add" || $type == "edit")
				{
					?>
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
							Blood Group
						</b>
					</h5>
					<form action="" class="form-validate-jquery" --id="formValidation" enctype="multipart/form-data" method="post">
						<div class="row">
							<div class="col-sm-12 col-md-12">
								<fieldset class="mb-3">
									<div class="row">
										<div class="form-group col-md-3">
											<label class="col-form-label">BloodGroup Name <span class="text-danger">*</span></label>
											<input type="text" name="blood_group_name" required autocomplete="off" <?php //echo $this->validation; ?> class="form-control" value="<?php echo isset($edit_data[0]['blood_group_name']) ? $edit_data[0]['blood_group_name'] :"";?>" placeholder="">
										</div>
										
									</div>
								</fieldset>
							</div>
						</div>
						
						<div class="d-flexad" style="text-align:right;">
							<a href="<?php echo base_url(); ?>employee/ManageBloodGroup" class="btn btn-default">Close </a>
							<button type="submit" class="btn btn-primary">Save</button>
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
							<a href="<?php echo base_url(); ?>employee/ManageBloodGroup/add" class="btn btn-info btn-sm">
								Create Blood Group
							</a>
						</div>
					</div>

					<form action="" method="get">
						<div class="row mt-3 mb-3">
							
							<div class="col-md-8">
								<div class="row">
									<div class="col-md-5">	
										<input type="search" autocomplete="off" class="form-control" name="keywords" value="<?php echo !empty($_GET['keywords']) ? $_GET['keywords'] :""; ?>" placeholder="Search...">
										<p class="search-note">Note :  	Blood Group Name</p>
									</div>
									<div class="col-md-4">
										<div class="row">
											<label class="col-form-label col-md-3">Status</label>
											<div class="form-group col-md-9">
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
					 	if(isset($_GET) && !empty($_GET))
					 	{
							?>
							<div class="new-scroller">
								<table id="myTable" class="table table-bordered table-hover  dataTable">
									<thead>
										<tr>
											<th class="text-center" style="width:5%;">Controls</th>
											<th onclick="sortTable(1);">Blood Group Name</th>
											<th onclick="sortTable(2);" style="text-align:center;width:10%;">Status</th>
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
													<td style="width:5% !important; text-align:center;">
														
														<div class="dropdown" style="display: inline-block;padding-right: 10px!important;">
															<button type="button" class="btn btn-outline-primary gropdown-toggle waves-effect waves-light" data-toggle="dropdown" aria-expanded="false">
																Action <i class="fa fa-angle-down"></i>
															</button>
															<ul class="dropdown-menu dropdown-menu-right">
																<li>
																	<a title="Edit" href="<?php echo base_url(); ?>employee/ManageBloodGroup/edit/<?php echo $row['blood_group_id'];?>">
																		<i class="fa fa-pencil"></i> Edit
																	</a>
																</li>
																
																<li>
																	<?php 
																		if($row['active_flag'] == 'Y')
																		{
																			?>
																			<a href="<?php echo base_url(); ?>employee/ManageBloodGroup/status/<?php echo $row['blood_group_id'];?>/N" title="Block">
																				<i class="fa fa-ban"></i> Inactive
																			</a>
																			<?php 
																		} 
																		else
																		{  ?>
																			<a href="<?php echo base_url(); ?>employee/ManageBloodGroup/status/<?php echo $row['blood_group_id'];?>/Y" title="Unblock">
																				<i class="fa fa-check"></i> Active
																			</a>
																			<?php 
																		} 
																	?>
																</li>
															</ul>
														</div>
													</td>
													
													<td style="width:10%;"><?php echo ucfirst($row['blood_group_name']);?></td>
													
													<td style="text-align:center;width:10%;">
														<?php 
															#if($row['blood_group_status'] == 1)
															if($row['active_flag'] == 'Y')
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
	
