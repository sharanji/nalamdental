<div class="content"><!-- Content start-->
	<div class="card"><!-- Card start-->
		<div class="card-body">
			<?php
				if(isset($type) && $type == "add" || $type == "edit")
				{
					?>
					<form action="" class="form-validate-jquery" enctype="multipart/form-data" method="post">
						<fieldset>
							<legend class="text-uppercase font-size-sm font-weight-bold">Branch</legend>
							
							<div class="row">
								<div class="form-group col-md-3">
									<label class="col-form-label">Branch Name <span class="text-danger">*</span></label>
									<div class="">
										<input type="text" name="branch_name" required  onkeypress="return event.charCode >= 48 && event.charCode <= 57 || event.charCode >= 97 && event.charCode <= 122 || event.charCode >= 65 && event.charCode <= 90 || event.charCode == 32" class="form-control" value="<?php echo isset($edit_data[0]['branch_name']) ? $edit_data[0]['branch_name'] :"";?>" placeholder="">
									</div>
								</div>
								<div class="form-group col-md-3">
									<label class="col-form-label">Branch Code <span class="text-danger">*</span></label>
									<div class="">
										<input type="text" name="branch_code" required onkeypress="return event.charCode >= 48 && event.charCode <= 57 || event.charCode >= 97 && event.charCode <= 122 || event.charCode >= 65 && event.charCode <= 90 || event.charCode == 32" class="form-control" value="<?php echo isset($edit_data[0]['branch_code']) ? $edit_data[0]['branch_code'] :"";?>" placeholder="">
									</div>
								</div>
							</div>
							
							<div class="row">
								<div class="form-group col-md-3">
									<label class="col-form-label">Address <span class="text-danger">*</span></label>
									<div class="">
										<textarea name="address" class="form-control" required><?php echo isset($edit_data[0]['address']) ? $edit_data[0]['address'] :"";?></textarea>
									</div>
								</div>
								
								<div class="form-group col-md-3">
									<label class="col-form-label">Description</label>
									<div class="">
										<textarea name="description" class="form-control"><?php echo isset($edit_data[0]['description']) ? $edit_data[0]['description'] :"";?></textarea>
									</div>
								</div>
							</div>
							
							<div class="row">
								<div class="form-group col-md-3">
									<label class="col-form-label">Mobile Number 1 <span class="text-danger">*</span></label>
									<div class="">
										<input type="number" name="phone_number" required oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" minlength="10" maxlength='12' class="form-control" value="<?php echo isset($edit_data[0]['phone_number']) ? $edit_data[0]['phone_number'] :"";?>" placeholder="">
									</div>
								</div>
								
								<div class="form-group col-md-3">
									<label class="col-form-label">Mobile Number 2</label>
									<div class="">
										<input type="number" name="phone_number_2" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" minlength="10" maxlength='12' class="form-control" value="<?php echo isset($edit_data[0]['phone_number_2']) ? $edit_data[0]['phone_number_2'] :"";?>" placeholder="">
									</div>
								</div>
							</div>
						</fieldset>
						
						<div class="d-flexad mb-3" style="text-align:right;">
							<a href="<?php echo base_url(); ?>admin/ManageBranches" class="btn btn-default">Close</a>
							<button type="submit" class="btn btn-primary ml-1">Save</button>
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
							<!-- <a href='<?php echo base_url();?>admin/settings' class='btn btn-outline-info btn-sm btn-info'>
								<i class="icon-arrow-left16"></i> Back
							</a> -->
							<a href="<?php echo base_url(); ?>admin/ManageBranches/add" class="btn btn-info btn-sm">
								Create Branch
							</a>
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
									</div>
										
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
					
					<form action="" method="post">
						<div class="new-scroller">
							<table id="myTable" class="table table-bordered table-hover  dataTable">
								<thead>
									<tr>
										<th class="text-center">Controls</th>
										<th onclick="sortTable(1)" class="text-center">Branch Code</th>
										<th onclick="sortTable(2)">Branch Name</th>
										<th onclick="sortTable(2)" class="text-center">Mobile Number</th>
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
												<td class="text-center">
													<div class="dropdown" style="display: inline-block;--padding-right: 10px!important;width:92px;">
														<button type="button" class="btn btn-outline-primary gropdown-toggle waves-effect waves-light btn-sm" data-toggle="dropdown" aria-expanded="false">
															Action <i class="fa fa-angle-down"></i>
														</button>
														<ul class="dropdown-menu dropdown-menu-right">
															<li>
																<a href="<?php echo base_url(); ?>admin/ManageBranches/edit/<?php echo $row['branch_id'];?>">
																	<i class="fa fa-edit"></i> Edit
																</a>
															</li>
															<li>
																<?php 
																	if($row['branch_status'] == 1)
																	{
																		?>
																		<a class="unblock" href="<?php echo base_url(); ?>admin/ManageBranches/status/<?php echo $row['branch_id'];?>/0" title="Block">
																			<i class="fa fa-ban"></i> Inactive
																		</a>
																		<?php 
																	} 
																	else
																	{  ?>
																		<a class="block" href="<?php echo base_url(); ?>admin/ManageBranches/status/<?php echo $row['branch_id'];?>/1" title="Unblock">
																			<i class="fa fa-ban"></i> Active
																		</a>
																		<?php 
																	} 
																?>
															<li>
														</ul>
													</div>
												</td>
												<td class="text-center"><?php echo $row['branch_code'];?></td>
												<td><?php echo ucfirst($row['branch_name']);?></td>
												<td class="text-center"><?php echo $row['phone_number'];?></td>
												<td class="tab-mobile-width text-center">
													<?php 
														if($row['branch_status'] == 1)
														{
															?>
															<span class="btn btn-outline-success btn-sm" title="Active"><i class="fa fa-check"></i> Active</span>
															<?php 
														} 
														else
														{ 
															?>
															<span class="btn btn-outline-warning btn-sm" title="Inactive"><i class="fa fa-close"></i> Inactive</span>
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
					</form>
					
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
		</div><!-- card-body-->
	</div><!-- Card end-->
</div><!-- Content end-->

