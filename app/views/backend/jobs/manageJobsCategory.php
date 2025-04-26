<?php 
	$physical_stock_adjustment = accessMenu(physical_stock_adjustment);
?>
<div class="content"><!-- Content start-->
	<div class="card"><!-- Card start-->
		<div class="card-body">
			<?php
				if(isset($type) && $type == "add" || $type == "edit" || $type == "view")
				{
					if($type == "view")
					{
						$fieldSetDisabled = "disabled";
						$searchDropdown = "";
						$this->fieldDisabled = $fieldDisabled = "";
						$this->fieldReadonly = $fieldReadonly = "";
					}
					else
					{
						if($type == "add" || $type == "edit")
						{
							$this->fieldDisabled = $fieldDisabled = "";
							$this->fieldReadonly = $fieldReadonly = "";
						} 
						
						$fieldSetDisabled = "";
						$searchDropdown = "searchDropdown";
					}
					?>
					<form action="" --class="form-validate-jquery" enctype="multipart/form-data" method="post">				
						<div class="header-lines">
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
									
									
								</div>
							</div>
							<!-- Buttons end here -->
							
							<fieldset <?php echo $fieldSetDisabled;?>>
								
								<!-- Header Section Start Here-->
								<section class="header-section">
									<div class="row">
										<div class="col-md-6">
											<div class="row">
												<div class="col-md-3">
													<div class="form-group text-right">
														<label class="col-form-label job_name"><span class="text-danger">*</span> Job Name</label>
													</div>				
												</div>
												<div class="col-md-4">
													<div class="form-group">
														<input type="text" name="job_name" id="job_name" autocomplete="off" class="form-control" value="<?php echo isset($editData[0]['job_name']) ? $editData[0]['job_name'] : NULL;?>" placeholder="Job Name">
													</div>				
												</div>
											</div>
											
											<div class="row">
												<div class="col-md-3">
													<div class="form-group text-right">
														<label class="col-form-label job_category_image"><span class="text-danger">*</span> Job category Image</label>
													</div>				
												</div>
												<div class="col-md-4">
													<div class="form-group">
														<input type="file" name="job_category_image" id="job_category_image" onchange="return validateSingleFileExtension(this)" accept="image/*" class="form-control singleImage">
														<!-- <span class="text-muted" >Note : Upload image size less than 100kb and less than 100x100 pixels</span> -->
														<span class="exist_error text-warning"></span>
														<script>
															function validateSingleFileExtension(fld) 
															{
																var fileUpload = fld;
																
																if (typeof (fileUpload.files) != "undefined") {
																	var file = fileUpload.files[0];
																	var size = parseFloat(file.size / 1024).toFixed(2); // Convert to KB
																	

																	// Validate file type
																	if (!/(\.png|\.bmp|\.gif|\.jpg|\.jpeg)$/i.test(file.name)) {
																		alert("Invalid file type. Please upload an image file (PNG, BMP, GIF, JPG, JPEG).");
																		fileUpload.value = ''; // Clear the input field
																		return false;
																	}

																	// No need to validate dimensions
																	return true;
																}
															}
															/** Single Image Type, Size, and Dimension Validation **/
															// function validateSingleFileExtension(fld) {
															// 	var fileUpload = fld;

															// 	if (typeof (fileUpload.files) != "undefined") {
															// 		var file = fileUpload.files[0];
															// 		var size = parseFloat(file.size / 1024).toFixed(2);  // Size in KB
															// 		var validSize = 100; 

															// 		// Check file size
															// 		if (size > validSize) {
															// 			alert("Upload size should be" + validSize + "KB or less.");
															// 			fld.value = '';  // Clear the input value
															// 			return false;
															// 		}

															// 		// Check file type (only allow .png, .jpg, .jpeg)
															// 		if (!/(\.png|\.jpg|\.jpeg)$/i.test(file.name)) {
															// 			alert("Invalid file type. Only PNG, JPG, and JPEG formats are allowed.");
															// 			fld.value = '';  // Clear the input value
															// 			return false;
															// 		}

															// 		// Check image dimensions
															// 		var img = new Image();
															// 		img.onload = function() {
															// 			var width = img.width;
															// 			var height = img.height;
															// 			var maxDimension = 100; // Maximum dimension allowed (in pixels)

															// 			if (width > maxDimension || height > maxDimension) {
															// 				alert("Image dimensions should not exceed " +maxDimension +"x" + maxDimension + "pixels.");
															// 				fld.value = '';  // Clear the input value
															// 				return false;
															// 			}
															// 		};

															// 		var reader = new FileReader();
															// 		reader.onload = function(e) {
															// 			img.src = e.target.result; 
															// 		};
															// 		reader.readAsDataURL(file);
															// 	}
															// }
															</script>

														<?php 
															if( ($type == "edit" || $type == "view") && isset($id))
															{
																$url = "uploads/job_category_images/".$id.".png";
																if(file_exists($url))
																{
																	?><br>
																	<div class="form-group view-form row">
																		<div class="col-lg-8"><br>
																			<img src="<?php echo base_url().$url;?>" style="width:100px !important; height:75px !important;" alt="...">
																		</div>
																	</div>
																	<?php 
																}
															} 
														?>
													</div>				
												</div>
											</div>
										</div>
									</div>	
								</section>
								<!-- Header Section End Here-->
								
								<script>
									$(document).ready(function() {
										var type = '<?php echo $type; ?>';

										$(".form_submit_valid").prop('disabled', true);

										function validateForm() {
											var job_name = $("#job_name").val();
											var job_category_image = $("#job_category_image").val();

											// Enable the submit button if all required fields are filled
											if (job_name.trim() !== "" && (type !== 'add' || job_category_image)) {
												$(".form_submit_valid").prop('disabled', false); 
											} else {
												$(".form_submit_valid").prop('disabled', true); 
											}
										}

										// Trigger validation on input change or keyup
										$("input").on('keyup change', function() { 
											validateForm();
										});

										// Initial validation check
										validateForm();

										function saveBtn(val) {
											// Disable the button to prevent multiple clicks
											$(".form_submit_valid").prop('disabled', true);

											var job_name = $("#job_name").val();
											var job_category_image = $("#job_category_image").val();

											if (job_name.trim() !== "" && (type !== 'add' || job_category_image)) {
												$(".job_name").removeClass('errorClass');
												$(".job_category_image").removeClass('errorClass');
												return true;
											} else {
												$(".form_submit_valid").prop('disabled', false); // Re-enable the button if validation fails

												// Show error for missing job_name
												if (job_name.trim() === "") {
													$(".job_name").addClass('errorClass');
												}

												// Show error if image is required for 'add' and is missing
												if (type === 'add' && !job_category_image) {
													$(".job_category_image").addClass('errorClass');
												} else {
													$(".job_category_image").removeClass('errorClass');
												}

												return false;
											}
										}
									});
									</script>

							
								<!-- Line level end here -->
							</fieldset>
							<div class="col-md-12 mt-3 pr-0 text-right">
								<?php 
									if($type == "add" || $type == "edit")
									{
										?>
											<button type="submit" name="save_btn" id="save_btn" onclick="return saveBtn('save_btn');" title="Save & Continue" class="btn btn-primary btn-sm form_submit_valid">Save</button>
											<button type="submit" name="submit_btn" id="submit_btn" onclick="return saveBtn('submit_btn');" title="Submit" class="btn btn-primary btn-sm form_submit_valid">Submit</button>
										<?php 
									} 
								?>
								<a href="<?php echo base_url(); ?>jobs/manageJobsCategory" class="btn btn-default btn-sm">Close</a>
							</div>

						</div>
					</form>

					
					<?php
				}
				else
				{ 
					?>
					<!-- Buttons start here -->
					<div class="row">
						<div class="col-md-6"><h3><b><?php echo $page_title;?></b></h3></div>
						<div class="col-md-6 float-right text-right">
							<?php
								if($physical_stock_adjustment['create_edit_only'] == 1 || $this->user_id == 1)
								{
									?>
									<a href="<?php echo base_url(); ?>jobs/manageJobsCategory/add" class="btn btn-info btn-sm">
										Create Job Category
									</a>
									<?php 
								} 
							?>
						</div>
					</div>
					<!-- Buttons end here -->
					
					<!-- Filters start here -->
					<form action="" class="form-validate-jquery" method="get">
						<div class="row mt-3">
							
						
							<div class="col-md-3">
								<div class="row">
									<label class="col-form-label col-md-4 text-right">Job Category</label>
									<div class="form-group col-md-8">
										<?php
												$getJobCategory = $this->jobs_model->jobCategoryAll();
										?>
										
										<select name="job_category_id" id="job_category_id" class="form-control searchDropdown">
											<option value="">- Select -</option>
											<?php 
												foreach($getJobCategory as $jobCategory)
												{
													$selected="";
													if(isset($_GET["job_category_id"]) && $_GET["job_category_id"] == $jobCategory['job_category_id'] )
													{
														$selected="selected='selected'";
													}
													?>
													<option value="<?php echo $jobCategory['job_category_id'];?>" <?php echo $selected;?>><?php echo $jobCategory['job_name'];?></option>
													<?php 
												} 
											?>
										</select>
									</div>
								</div>
							</div>
							<div class="col-md-4">
								<div class="row">
									<label class="col-form-label col-md-4 text-right">Status</label>
									<div class="form-group col-md-8">
										<?php 
											$activeStatus = $this->common_model->lov('ACTIVESTATUS');
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


							<div class="col-md-2">
								<button type="submit" class="btn btn-info">Search <i class="fa fa-search"></i></button>
								<a href="<?php echo base_url(); ?>jobs/manageJobsCategory" title="Clear" class="btn btn-default">Clear</a>
							</div>
						</div>
					</form>
					<!-- Filters end here -->
					
					<?php 
						if(isset($_GET) &&  !empty($_GET))
						{
							?>
							<!-- Page Item Show start -->
							<div class="row mt-3">
								<?php
									if( isset($resultData) && count($resultData) > 0 )
									{
										?>
											<div class="col-md-6">
												<?php /*
														<a href="<?php echo base_url().$this->redirectURL;?>&export=export" class="btn btn-primary btn-sm">Download Excel</a>
													*/
												?>
											</div>

											<div class="col-md-6 float-right text-right">
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
										<?php
									}
								?>
							</div>
						    <!-- Page Item Show start -->

							<!-- Table start here -->
							<div class="new-scroller">
								<table id="myTable" class="table table-bordered table-hover tbl_height">
									<thead>
										<tr>
											<th class="text-center tab-md-100">control</th>
											<th class="text-left tab-md-140">Job Name</th>
											<th class="text-center tab-md-100">Job Image</th>
											<th class="text-center tab-md-100">Status</th>
										</tr>
									</thead>
									<tbody>
										<?php 	
											$i=0;
											$firstItem = $first_item;
											$totalValue = 0;
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
																<?php
																	if($physical_stock_adjustment['create_edit_only'] == 1 || $physical_stock_adjustment['read_only'] == 1 || $this->user_id == 1)
																	{ 
																		?>
																		<?php
																			if($physical_stock_adjustment['create_edit_only'] == 1 || $this->user_id == 1)
																			{
																				?>
																					<li>
																						<a title="Edit" href="<?php echo base_url(); ?>jobs/manageJobsCategory/edit/<?php echo $row['job_category_id'];?>/">
																							<i class="fa fa-edit"></i> Edit
																						</a>
																					</li>
																				<?php 
																			} 
																		?>

																		<?php
																			if($physical_stock_adjustment['read_only'] == 1 || $this->user_id == 1)
																			{
																				
																				?>
																				<li>
																					<a title="View" href="<?php echo base_url(); ?>jobs/manageJobsCategory/view/<?php echo $row['job_category_id'];?>/">
																						<i class="fa fa-eye"></i> View
																					</a>
																				</li>
																				<?php 
																			} 
																		?>

																		<?php
																			if($physical_stock_adjustment['create_edit_only'] == 1 || $this->user_id == 1)
																			{
																				?>
																				<li>
																					<?php 
																						if($row['active_flag'] == $this->active_flag)
																						{
																							?>
																							<a class="unblock" href="<?php echo base_url(); ?>jobs/manageJobsCategory/status/<?php echo $row['job_category_id'];?>/N" title="Inactive">
																								<i class="fa fa-ban"></i> Inactive
																							</a>
																							<?php 
																						} 
																						else
																						{  ?>
																							<a class="block" href="<?php echo base_url(); ?>jobs/manageJobsCategory/status/<?php echo $row['job_category_id'];?>/Y" title="Active">
																								<i class="fa fa-check"></i> Active
																							</a>
																							<?php 
																						} 
																					?>
																				<li>
																				<?php 
																			} 
																		?>
																		<?php 
																	} 
																?>
															</ul>
														</div>
													</td>
													<td><?php echo $row['job_name'];?></td>
													<td class="text-center">
														<?php 
															if(isset($row['job_category_id']))
															{
																$url = "uploads/job_category_images/".$row['job_category_id'].".png";
																if(file_exists($url))
																{
																	?>
																	<img src="<?php echo base_url().$url;?>" style="width:50px !important; height:40px !important;" alt="...">
																	<?php 
																}else{
																	?>
																	<img src="<?php echo base_url()?>uploads/no-image.png" style="width:50px !important; height:40px !important;" alt="...">
																	<?php 
																}
															} 
														?>
													</td>
													
													<td class="text-center">
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
							<!-- Pagination end here -->
							<?php 
						} 
					?>
					<?php 
				} 
			?>
		</div>
	</div><!-- Card end-->
</div><!-- Content end-->











