<?php 
	$customerBannerMenu = accessMenu(customer_banner);
?>

<div class="content"><!-- Content start-->
	<div class="card"><!-- Card start-->
		<div class="card-body">
			<?php
				if(isset($type) && ($type == "add" || $type == "edit"))
				{
					?>
					<div class="row">
						<div class="col-xl-12 col-xxl-12 col-lg-12">
							<div class="-card">
								<div class="-card-header">
									<h3><b>Category Banner Slider</b></h3>
								</div>
								<div class="-card-body">
									<div class="">
										<form --class="form-validate-jquery" action="#" method="post" enctype="multipart/form-data">
											<?php
												$getBranch = $this->db->query("select branch_id,branch_name from branch where active_flag='Y' order by branch_name asc")->result_array();
											?>
											<div class="form-group row">
												<label class="col-form-label col-lg-2 text-left">Branch <span class="text-danger">*</span></label>
													<div class="col-lg-3">
													<select name="branch_id" id="branch_id" required class="form-control searchDropdown">
														<option value="">- Select -</option>
														<?php 
															foreach($getBranch as $row)
															{
																$selected="";
																if(isset($edit_data[0]['branch_id']) && $edit_data[0]['branch_id']== $row['branch_id'])
																{
																	$selected="selected";
																}
																?>
																<option value="<?php echo $row['branch_id']; ?>" <?php echo $selected; ?>><?php echo ucfirst($row['branch_name']);?></option>
																<?php 
															} 
														?>
													</select>
												</div>
											</div>
											
											<?php
												$getCategory = $this->db->query("select category_id,category_name from inv_categories where active_flag='Y' order by category_name asc")->result_array();
											?>
											<div class="form-group row">
												<label class="col-form-label col-lg-2 text-left">Category <span class="text-danger">*</span></label>
													<div class="col-lg-3">
													<select name="category_id" id="category_id" required class="form-control searchDropdown">
														<option value="">- Select -</option>
														<?php 
															foreach($getCategory as $row)
															{
																$selected="";
																if(isset($edit_data[0]['category_id']) && $edit_data[0]['category_id']== $row['category_id'])
																{
																	$selected="selected";
																}
																?>
																<option value="<?php echo $row['category_id']; ?>" <?php echo $selected; ?>><?php echo ucfirst($row['category_name']);?></option>
																<?php 
															} 
														?>
													</select>
												</div>
											</div>
											<div class="form-group row">
												<label class="col-form-label col-lg-2 text-left"> Banner Title <span class="text-danger">*</span></label>
												<div class="col-lg-3">
													<input type="text" name="category_name" <?php echo $this->validation;?> class="form-control" required value="<?php echo isset($edit_data[0]['category_name']) ? $edit_data[0]['category_name'] :"";?>" placeholder="">
												</div>
											</div>

											<div class="form-group row">
												<label class="col-form-label col-lg-2 text-left">Start Date</label>
												<div class="col-lg-2">
													<input type="text" name="start_date" id="start_date" readonly class="form-control default_date selectInvoiceDate"autocomplete="off"value="<?php echo isset($edit_data[0]['start_date']) ? $edit_data[0]['start_date'] :"";?>"placeholder="">
												</div>
											</div>

											<div class="form-group row">
												<label class="col-form-label col-lg-2 text-left">End Date</label>
												<div class="col-lg-3">
													<input type="text" name="end_date" id="end_date" readonly autocomplete="off" class="form-control default_date selectInvoiceDate" value="<?php echo isset($edit_data[0]['end_date']) ? $edit_data[0]['end_date'] :"";?>"placeholder="">
												</div>
											</div>

											<div class="form-group row">
												<label class="col-form-label col-lg-2 text-left"> Banner Description</label>
												<div class="col-lg-3">
													<textarea rows="1" name="category_description"<?php echo $this->validation;?> autocomplete="off" class="form-control" value="" placeholder=""><?php echo isset($edit_data[0]['category_description']) ? $edit_data[0]['category_description'] :"";?></textarea>
												</div>
											</div>

											<div class="form-group row">
												<label class="col-form-label col-lg-2 text-left"> Banner Image </label>
												<div class="col-lg-3">
													<input type="file" name="banner_image" onchange="return validateSingleFileExtension(this)" class="form-control singleImage">
													<span class="note-class"><b>Note</b> : Upload size is 1 [MB] and image format is (png,gif,jpg,jpeg and bmp).</span>
													<script>
														/** Single Image Type & Size Validation **/
														function validateSingleFileExtension(fld) 
														{
															var fileUpload = fld;
															
															if (typeof (fileUpload.files) != "undefined")
															{
																var size = parseFloat( fileUpload.files[0].size / 1024 ).toFixed(2);
																var validSize = 1024 * 1; //1024 - 1Mb multiply 4mb
																
																if( size > validSize )
																{
																	alert("Upload size is 1 MB");
																	$('.singleImage').val('');
																	var value = 1;
																	return false;
																}
																else if(!/(\.png|\.bmp|\.gif|\.jpg|\.jpeg)$/i.test(fld.value))
																{
																	alert("Invalid file type.");      
																	$('.singleImage').val('');
																	return false;   
																}
																
																if(value != 1)	
																	return true; 
															}
														}
													</script>
													<?php 
														if($type == "edit")
														{
															if (file_exists('uploads/category_sliders/'.$edit_data[0]['banner_id'].'.png'))
															{
																?><br>
																<img src="<?php echo base_url()."uploads/category_sliders/".$edit_data[0]['banner_id'].'.png';?>" width="100" height="60" alt="best dental hospial hosur"> 
																<?php
															}
														}
													?>
												</div>
											</div>

											<div class="form-group float-right">
												<a href="<?php echo base_url();?>category_banner/ManageCategoryBanner" class="btn btn-light btn-sm">Close</a>
												<button type="submit" class="btn btn-primary text-right btn-sm">Save  </button>
											</div>
										</form>
									</div>
								</div>
							</div>
						</div>
					</div>
					<?php
				}
				else 
				{
					?>
					
					<div class="row mb-2">
						<div class="col-md-6"><h3><b><?php echo $page_title;?></b></h3></div>
						<div class="col-md-6 float-right text-right">
							<?php
								if($customerBannerMenu['create_edit_only'] == 1 || $this->user_id == 1)
								{
									?>
									<a href="<?php echo base_url(); ?>category_banner/ManageCategoryBanner/add" class="btn btn-info btn-sm">
										Create Category Banner Slider
									</a>
									<?php 
								} 
							?>
						</div>
					</div>

					<!-- filters-->
					<form action="" class="form-validate-jquery" method="get">
						<div class="row">
							<div class="col-md-4">
								<div class="row">
									<label class="col-form-label col-md-4 text-right">Banner Title</label>
									<div class="form-group col-md-7">
										<input type="search" <?php echo $this->validation;?> name="category_name" class="form-control" value="<?php echo !empty($_GET['category_name']) ? $_GET['category_name'] :""; ?>" placeholder="Banner Title" autocomplete="off">
									</div>
								</div>
							</div>

							<div class="col-md-3">
								<div class="row">
									<label class="col-form-label col-md-3 text-right">Status</label>
									<div class="form-group col-md-9">
										<?php 
											$activeStatus = $this->common_model->lov('ACTIVESTATUS'); 
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

							<div class="col-md-4">
								<button type="submit" class="btn btn-info">Search <i class="fa fa-search" aria-hidden="true"></i></button>&nbsp;
								<a href="<?php echo base_url(); ?>category_banner/ManageCategoryBanner" title="Clear" class="btn btn-default">Clear</a>
							</div>
						</div>
					</form>
					<!-- filters-->
						
					<?php 
						if(isset($_GET) &&  !empty($_GET))
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

							<form action="" method="post">
								<div class="new-scroller">
									<table id="myTable" class="table table table-bordered">
										<thead>
											<tr>
												<th class="text-center">Controls</th>
												<th>Category Image Title</th>
												<th class="text-center">Category Image</th>
												<th class="text-center">Status</th>
												<th class="text-center">Default</th>
											</tr>
										</thead>
										<tbody>
											<?php
												if (count($resultData) > 0) 
												{
													$i=0;
													$firstItem = $first_item;
													foreach($resultData as $row)
													{
														?>
														<tr>
															<td style="width: 12%;" class="text-center">
																<div class="dropdown" style="display: inline-block;padding-right: 10px!important;width:92px;">
																	<button type="button" class="btn btn-outline-primary gropdown-toggle waves-effect waves-light btn-sm" data-toggle="dropdown" aria-expanded="false">
																		Action <i class="fa fa-angle-down"></i>
																	</button>
																	<ul class="dropdown-menu dropdown-menu-right">
																		<?php
																			if($customerBannerMenu['create_edit_only'] == 1 || $customerBannerMenu['read_only'] == 1 || $this->user_id == 1)
																			{ 
																				?>
																				<?php
																					if($customerBannerMenu['create_edit_only'] == 1 || $this->user_id == 1)
																					{
																						?>
																						<li>
																							<a href="<?php echo base_url();?>category_banner/ManageCategoryBanner/edit/<?php echo $row['banner_id'];?>">
																								<i class="fa fa-edit"></i> Edit
																							</a>
																						</li>
																						<?php 
																					} 
																				?>

																				<?php
																					if($customerBannerMenu['read_only'] == 1 || $this->user_id == 1)
																					{
																						?>
																						<li>											
																							<?php 
																								if($row['active_flag'] == $this->active_flag)
																								{
																									?>
																									<a class="unblock" href="<?php echo base_url(); ?>category_banner/ManageCategoryBanner/status/<?php echo $row['banner_id'];?>/N" title="Block">
																										<i class="fa fa-ban"></i> In Active
																									</a>
																									<?php 
																								} 
																								else
																								{  ?>
																									<a class="block" href="<?php echo base_url(); ?>category_banner/ManageCategoryBanner/status/<?php echo $row['banner_id'];?>/Y" title="Unblock">
																										<i class="fa fa-check"></i> Active
																									</a>
																									<?php 
																								} 
																							?>
																						</li>
																						<?php 
																					} 
																				?>
																				<?php 
																			} 
																		?>
																	</ul>
																</div>																		
															</td>

															<td><?php echo ucfirst($row['category_name']);?></td>

															<td class="text-center">
																<?php
																	if (file_exists('uploads/category_sliders/'.$row['banner_id'].'.png'))
																	{
																		?>
																		<img src="<?php echo base_url()."uploads/category_sliders/".$row['banner_id'].'.png';?>" width="70" height="50" alt="best dental hospial hosur"> 
																		<?php
																	}
																	else
																	{
																		?>
																		<img src="<?php echo base_url()."uploads/no-image.png";?>" width="100" height="90" alt="best dental hospial hosur"> 
																		<?php 
																	} 
																?>
															</td>

															<td class="text-center">
																<?php
																	if($row['active_flag'] == $this->active_flag)
																	{
																		?>
																		<span class="btn btn-outline-success btn-xs" title="Active">Active</span>
																		<?php
																	} 
																	else 
																	{
																		?>
																		<span class="btn btn-outline-warning btn-xs" title="Inactive">Inactive</span>
																		<?php
																	} 
																?>
															</td>
															
															<td class="text-center">
																<?php 
																	if($row['active_flag'] == 'Y')
																	{
																		?>
																		<input type="radio" name="default_banner" <?php if($row['default_banner'] == 1){?>checked<?php }?> value="<?php echo $row['banner_id']; ?>"/>
																		<?php 
																	} 
																?>
															</td>
														</tr>
														<?php
														$i++;
													}											
												}
												else 
												{
													?>
													<tr>
														<td class="text-center" colspan="20">
															<img src="<?php echo base_url();?>uploads\nodata.png" style="width:200px;height:200px;"><br>
															<!--<p class="admin-no-data">No data found.</p>-->
														</td>
													</tr>
													<?php 
												} 
												
											?>
											<?php
												if (count($resultData) > 0) 
												{
													?>
													<tr>
														<td colspan="4"></td>
														<td class="text-center">
															<button type="submit" name="default_submit" class="btn btn-outline-primary ml-3 btn-xs updates">Update</button>
														</td>
													</tr>
													<?php 
												} 
											?>
										</tbody>
									</table>
								</div>
							</form>

						
							<?php
								if (count($resultData) > 0) 
								{
									?>
									<div class="row mt-3">
										<div class="col-md-6 showing-count">
											Showing <?php echo $starting;?> to <?php echo $ending;?> of <?php echo $totalRows;?> entries
										</div>
										<!-- pagination start here -->
										<?php 
											if( isset($pagination) )
											{
												?>	
												<div class="col-md-6">
													<?php foreach ($pagination as $link){echo $link;} ?>
												</div>
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
	</div>
</div>
	
