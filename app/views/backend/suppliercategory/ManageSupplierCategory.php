<div class="content"><!-- Content start-->
	<div class="card"><!-- Card start-->
		<div class="card-body">
			<?php
				if(isset($type) && $type == "add" || $type == "edit")
				{
					?>
					
					<form action="" class="form-validate-jquery" --id="formValidation" enctype="multipart/form-data" method="post">
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
										<button type="submit" name="save_btn" id="save_btn" onclick="return saveBtn('save_btn');" class="btn btn-primary btn-sm">Save</button>
										<!-- <button type="submit" name="submit_btn" class="btn btn-primary btn-sm">Submit</button> -->
										<!-- <button type="submit" name="submit_btn" id="submit_btn" onclick="return saveBtn('submit_btn');" title="Submit" class="btn btn-primary btn-sm">Submit</button> -->

										<?php 
									} 
								?>
								<a href="<?php echo base_url(); ?>supplierCategory/ManageSupplierCategory/" class="btn btn-default btn-sm">Close</a>
							</div>
						</div>
						<!-- Buttons end here -->

						<fieldset class="mb-3">
							<div class="row mt-3">
								<div class="col-sm-6 col-md-6">
									<div class="row">
										<div class="form-group col-md-3 text-right">
											<label class="col-form-label category_name"><span class="text-danger">*</span> Category Name</label>
										</div>
										<div class="form-group col-md-4">
											<input type="text" name="category_name" id="category_name" required autocomplete="off"  class="form-control" value="<?php echo isset($edit_data[0]['category_name']) ? $edit_data[0]['category_name'] :"";?>" placeholder="Category Name">
										</div>
										
									</div>
									<div class="row">
										
										<div class="form-group col-md-3 text-right">
											<label class="col-form-label">Description </label>
										</div>
										<div class="form-group col-md-4">
											<input type="text" name="category_description" id="category_description" autocomplete="off"  class="form-control" value="<?php echo isset($edit_data[0]['category_description']) ? $edit_data[0]['category_description'] :"";?>" placeholder="Description">
										</div>
										
									</div>
								</div>
							</div>
						</fieldset>
						
						
						<div class="row">
							<div class="col-md-4"></div>
							<div class="col-md-8 text-right">
								<?php 
									if($type == "add" || $type == "edit")
									{
										?>
											<button type="submit" name="save_btn" id="save_btn" onclick="return saveBtn('save_btn');" class="btn btn-primary btn-sm">Save</button>
											<!-- <button type="submit" name="submit_btn" id="submit_btn" onclick="return saveBtn('submit_btn');" title="Submit" class="btn btn-primary btn-sm">Submit</button> -->
										
										<?php 
									}
								?>
								<a href="<?php echo base_url(); ?>supplierCategory/ManageSupplierCategory/" class="btn btn-sm btn-default">Close</a>
								
							</div>
						</div>	
					</form>
					<script>
						function saveBtn(val)
						{
							
							var category_name 			= $("#category_name").val();
							
							if(category_name && category_description)
							{
								$(".category_name").removeClass('errorClass');
								

							}
							else
							{
								if(category_name) {
									$(".category_name").removeClass('errorClass');
								} else{
									$(".category_name").addClass('errorClass');
								}	

								
								return false;	
							}

						}
					</script>
					<?php
				}
				else
				{ 
					?>
					<div class="row mb-2">
						<div class="col-md-6"><h3><b><?php echo $page_title;?></b></h3></div>
						<div class="col-md-6 float-right text-right">
						
							<!-- <a href="<?php echo base_url();?>setup/settings" class="btn btn-info btn-sm">
								<i class="icon-arrow-left16"></i> 
								Back
							</a>  -->
							
							<a href="<?php echo base_url();?>setup/settings" class="btn btn-info btn-sm">
								<i class="icon-arrow-left16"></i> 
								Back
							</a>

							<a href="<?php echo base_url(); ?>supplierCategory/ManageSupplierCategory/add" class="btn btn-info btn-sm">
								Create Supplier Category
							</a>
						</div>
					</div>

					<form action="" method="get">
						<div class="row mt-3">
							<div class="col-md-4 text-right float-right">
								<div class="row">
									<label class="col-form-label col-md-4 ">Category Name</label>
									<div class="form-group col-md-6">
										<input type="search" autocomplete="off" class="form-control" name="keywords" value="<?php echo !empty($_GET['keywords']) ? $_GET['keywords'] :""; ?>" placeholder="Category Name">
									</div>
								</div>
							</div>	
							<div class="col-md-3 text-right float-right">
								<div class="row">
									<?php 
										$activeStatus = $this->common_model->lov('ACTIVESTATUS') 
									?>
									<label class="col-form-label col-md-3 text-right">Status</label>
									<div class="form-group col-md-8">
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
							
							<div class="col-md-4">
								<button type="submit" class="btn btn-info">Search <i class="fa fa-search" aria-hidden="true"></i></button>
								<a href="<?php echo base_url(); ?>supplierCategory/manageSupplierCategory" title="Clear" class="btn btn-default">Clear</a>
							</div>
							
						</div>
					</form>
					
					<?php 
					 	if(isset($_GET) && !empty($_GET))
					 	{
							?>
								<!-- Page Item Show start -->
								<div class="row">
									<div class="col-md-9 mt-3">
										<?php 
											if( isset($resultData) && count($resultData) > 0 )
											{
												?>
												<a href="<?php echo base_url().$this->redirectURL;?>&export=export" class="btn btn-primary btn-sm">Download Excel</a>
												<?php 
											} 
										?>
									</div>
									<div class="col-md-3 float-right text-right">
										<?php 
											$redirect_url = substr($_SERVER['REQUEST_URI'],'1');
										?>
										<input type="hidden" id="redirect_url" value="<?php echo $redirect_url; ?>"/>
																
										<div class="filter_page">
											<label>
												<!-- <span style="color:blue;">Currency : <?php echo CURRENCY_CODE;?></span> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
												 -->
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
								<div class="new-scroller">
									<table id="myTable" class="table table-bordered table-hover  dataTable">
										<thead>
											<tr>
												<th class="text-center">Controls</th>
												<th>Category Name</th>
												<th>Description</th>
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
														<td style="width:10% !important; text-align:center;">
															
															<div class="dropdown" style="display: inline-block;padding-right: 10px!important;">
																<button type="button" class="btn btn-outline-primary gropdown-toggle waves-effect waves-light" data-toggle="dropdown" aria-expanded="false">
																	Action <i class="fa fa-angle-down"></i>
																</button>
																<ul class="dropdown-menu dropdown-menu-right">
																	<li>
																		<a title="Edit" href="<?php echo base_url(); ?>supplierCategory/ManageSupplierCategory/edit/<?php echo $row['category_id'];?>">
																			<i class="fa fa-pencil"></i> Edit
																		</a>
																	</li>
																	
																	<li>
																		<?php 
																			if($row['active_flag'] == 'Y')
																			{
																				?>
																				<a href="<?php echo base_url(); ?>supplierCategory/ManageSupplierCategory/status/<?php echo $row['category_id'];?>/N" title="Block">
																					<i class="fa fa-ban"></i> Inactive
																				</a>
																				<?php 
																			} 
																			else
																			{  ?>
																				<a href="<?php echo base_url(); ?>supplierCategory/ManageSupplierCategory/status/<?php echo $row['category_id'];?>/Y" title="Unblock">
																					<i class="fa fa-check"></i> Active
																				</a>
																				<?php 
																			} 
																		?>
																	</li>
																</ul>
															</div>
														</td>
														
														<td><?php echo $row['category_name'];?></td>
														<td><?php echo $row['category_description'];?></td>

														<td style="text-align:center;width:10%;">
															<?php 
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
	
