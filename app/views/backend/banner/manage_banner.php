<?php 
	$bannersMenu = accessMenu(banners);
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
									<h3><b>Banner</b></h3>
								</div>
								<div class="-card-body">
									<div class="">
										<form class="form-validate-jquery" action="#" method="post" enctype="multipart/form-data">
											<div class="form-group row">
												
												<label class="col-form-label col-lg-2 text-left">Banner Type <span class="text-danger">*</span></label>
												<div class="col-lg-3">
													<?php 
														$listTypeValuesQry = "select 
															sm_list_type_values.list_type_value_id,
															sm_list_type_values.list_code,
															sm_list_type_values.list_value	
																from sm_list_type_values

																left join sm_list_types on 
																	sm_list_types.list_type_id = sm_list_type_values.list_type_id
																		where 
																			sm_list_type_values.active_flag = 'Y' and 
																				sm_list_types.list_name = 'BANNER-TYPE'"; 
														$getBannerType = $this->db->query($listTypeValuesQry)->result_array();
													?>
													<select name="banner_type" id="banner_type" class="form-control searchDropdown">
														<option value="">- Select  -</option>
														<?php 
															foreach($getBannerType as $itemcategory)
															{
																$selected="";
																if(isset($edit_data[0]['banner_type']) && ($edit_data[0]['banner_type'] == $itemcategory['list_code']) )
																{
																	$selected="selected='selected'";
																}
																?>
																<option value="<?php echo $itemcategory['list_code']; ?>" <?php echo $selected;?>><?php echo $itemcategory['list_value']; ?></option>
																<?php 
															} 
														?>
													</select>
												</div>
											</div>
											<div class="form-group row">
												<label class="col-form-label col-lg-2 text-left">Banner Title <span class="text-danger">*</span></label>
												<div class="col-lg-3">
													<input type="text" name="banner_title" <?php echo $this->validation;?> class="form-control" required value="<?php echo isset($edit_data[0]['banner_title']) ? $edit_data[0]['banner_title'] :"";?>" placeholder="">
												</div>
											</div>

											<div class="form-group row">
												<label class="col-form-label col-lg-2 text-left">Banner URL</label>
												<div class="col-lg-3">
													<input type="text" name="banner_url" <?php echo $this->validation;?> class="form-control" value="<?php echo isset($edit_data[0]['banner_url']) ? $edit_data[0]['banner_url'] :"";?>" placeholder="">
												</div>
											</div>

											<div class="form-group row">
												<label class="col-form-label col-lg-2 text-left">Banner Description</label>
												<div class="col-lg-3">
													<textarea rows="1" name="banner_description"<?php echo $this->validation;?> autocomplete="off" class="form-control" value="" placeholder=""><?php echo isset($edit_data[0]['banner_description']) ? $edit_data[0]['banner_description'] :"";?></textarea>
												</div>
											</div>

											<div class="form-group row">
												<label class="col-form-label col-lg-2 text-left">Banner Image</label>
												<div class="col-lg-3">
													<?php
														if($type == "edit")
														{ 
															$required = "";
														}
														else 
														{ 
															$required = "required";
														}
													?>
													<input type="file" name="banner_image" onchange="return validateSingleFileExtension(this)" class="form-control singleImage p-1">
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
															if (file_exists('uploads/banner/'.$edit_data[0]['banner_id'].'.png'))
															{
																?><br>
																<img src="<?php echo base_url()."uploads/banner/".$edit_data[0]['banner_id'].'.png';?>" width="100" height="60" alt=""> 
																<?php
															}
														}
													?>
												</div>
											</div>
											<div class="form-group float-right">
												<a href="<?php echo base_url();?>banner/manage_banner" class="btn btn-light btn-sm">Close</a>
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
								if($bannersMenu['create_edit_only'] == 1 || $this->user_id == 1)
								{
									?>
									<a href="<?php echo base_url(); ?>banner/Manage_Banner/add" class="btn btn-info btn-sm">
										Create Banner
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
									<label class="col-form-label col-md-4">Banner Type</label>
									<div class="form-group col-md-7">
									<?php 
										$getBannerType = $this->common_model->lov('BANNER-TYPE');
									?>
									<select name="banner_type" id="banner_type" class="form-control searchDropdown">
										<option value="">- Select  -</option>
										<?php 
											foreach($getBannerType as $itemcategory)
											{
												$selected="";
												if(isset($_GET['banner_type']) && ($_GET['banner_type'] == $itemcategory['list_code']) )
												{
													$selected="selected='selected'";
												}
												?>
												<option value="<?php echo $itemcategory['list_code']; ?>" <?php echo $selected;?>><?php echo $itemcategory['list_value']; ?></option>
												<?php 
											} 
										?>
									</select>
									</div>
								</div>
							</div>

							<div class="col-md-4">
								<div class="row">
									<label class="col-form-label col-md-4 text-right">Banner Title</label>
									<div class="form-group col-md-7">
										<input type="search" name="banner_title" class="form-control" value="<?php echo !empty($_GET['banner_title']) ? $_GET['banner_title'] :""; ?>" placeholder="Banner Title" autocomplete="off">
									</div>
								</div>
							</div>

							<div class="col-md-4 -float-right -text-right">
								<button type="submit" class="btn btn-info">Search <i class="fa fa-search" aria-hidden="true"></i></button>&nbsp;
								<a href="<?php echo base_url(); ?>banner/Manage_Banner" title="Clear" class="btn btn-default">Clear</a>
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
												<th>Banner Type</th>
												<th>Banner Title</th>
												<th class="text-center">Banner Image</th>
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
																			if($bannersMenu['create_edit_only'] == 1 || $bannersMenu['read_only'] == 1 || $this->user_id == 1)
																			{ 
																				?>
																				<?php
																					if($bannersMenu['create_edit_only'] == 1 || $this->user_id == 1)
																					{
																						?>
																						<li>
																							<a href="<?php echo base_url();?>banner/manage_banner/edit/<?php echo $row['banner_id'];?>">
																								<i class="fa fa-edit"></i> Edit
																							</a>
																						</li>
																						<?php 
																					} 
																				?>
																				
																				<?php
																					if($bannersMenu['read_only'] == 1 || $this->user_id == 1)
																					{
																						?>
																						<li>											
																							<?php 
																								if($row['active_flag'] == $this->active_flag)
																								{
																									?>
																									<a class="unblock" href="<?php echo base_url(); ?>banner/manage_banner/status/<?php echo $row['banner_id'];?>/N" title="Block">
																										<i class="fa fa-ban"></i> In Active
																									</a>
																									<?php 
																								} 
																								else
																								{  ?>
																									<a class="block" href="<?php echo base_url(); ?>banner/manage_banner/status/<?php echo $row['banner_id'];?>/Y" title="Unblock">
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

															<td><?php echo ucfirst($row['banner_type_name']);?></td>
															<td><?php echo ucfirst($row['banner_title']);?></td>

															<td class="text-center">
																<?php
																	if (file_exists('uploads/banner/'.$row['banner_id'].'.png'))
																	{
																		?>
																		<img src="<?php echo base_url()."uploads/banner/".$row['banner_id'].'.png';?>" width="70" height="50" alt=""> 
																		<?php
																	}
																	else
																	{
																		?>
																		<img src="<?php echo base_url()."uploads/no-image.png";?>" width="100" height="90" alt=""> 
																		<?php 
																	} 
																?>
															</td>

															<td class="text-center">
																<?php
																	if($row['active_flag'] == $this->active_flag)
																	{
																		?>
																		<span class="btn btn-outline-success btn-sm" title="Active">Active</span>
																		<?php
																	} 
																	else 
																	{
																		?>
																		<span class="btn btn-outline-warning btn-sm" title="Inactive">Inactive</span>
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
															<img src="<?php echo base_url();?>uploads/nodata.png" style="width:200px;height:200px;"><br>
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
														<td colspan="5"></td>
														<td class="text-center">
															<button type="submit" name="default_submit" class="btn btn-outline-primary btn-sm updates">Update</button>
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
	
