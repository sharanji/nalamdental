
<div class="content"><!-- Content start-->
	<div class="card"><!-- Card start-->
	
		<div class="card-body">
			<?php
				if(isset($type) && $type == "add" || $type == "edit")
				{
					?>
					<form action="" class="form-validate-jquery" enctype="multipart/form-data" method="post">
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
										List of Value
									</b>
								</h3>
							</div>
							<div class="col-md-6 text-right">

							</div>
						</div>
						<fieldset class="mb-3">
							
							
							
							<div class="row">
								<div class="form-group col-md-3">
									<label class="col-form-label"><span class="text-danger">*</span> List Name </label>
									<input type="text" name="list_name" id="list_name" <?php echo $this->validation;?> class="form-control" required value="<?php echo isset($edit_data[0]['list_name'])?$edit_data[0]['list_name']:"";?>" placeholder="List Name">
								</div>
								<script>
									$(document).ready(function() {
										$('#list_name').on('input', function () {
											// Get the current value of the input
											let value = $(this).val();

											// Convert input to uppercase, remove leading spaces, and limit to single spaces between words
											value = value.toUpperCase().replace(/^\s+/, '').replace(/\s+/g, ' ');

											// Set the formatted value back to the input
											$(this).val(value);
										});
									});
								</script>

								<div class="form-group col-md-3">
									<label class="col-form-label">List Description </label>
									<textarea name="list_description"<?php echo $this->validation;?> class="form-control" rows='1' placeholder="List Description"><?php echo isset($edit_data[0]['list_description'])?$edit_data[0]['list_description']:"";?></textarea>
								</div>		
							</div>

							<div class="row">
								<div class="form-group col-md-3">
									<label class="col-form-label">Start Date </label>
									<input type="text" name="start_date" id="start_date" readonly class="form-control" value="<?php echo isset($edit_data[0]['start_date']) ? date("d-M-Y",strtotime($edit_data[0]['start_date'])) : NULL;?>" placeholder="Start Date">
								</div>

								<div class="form-group col-md-3">
									<label class="col-form-label">End Date</label>
									<input type="text" name="end_date" id="end_date" readonly class="form-control" value="<?php echo isset($edit_data[0]['end_date']) ? date("d-M-Y",strtotime($edit_data[0]['end_date'])) : NULL;?>" placeholder="End Date">
								</div>			
							</div>
						</fieldset>
						<div class="col-md-12 mt-3 pr-0 text-right">
							<?php 
								if($type == "add" || $type == "edit")
								{
									?>
									<!-- <a href="javascript:void(0)" id="save_btn" onclick="return saveBtn('save_btn','save');" class="btn btn-primary btn-sm submit_btn_bottom">Save Bottom</a> -->
									<button type="submit" name="save_btn" id="save_btn" onclick="return saveBtn('save_btn');" title="Save & Continue" class="btn btn-primary btn-sm form_submit_valid">Save</button>
									<button type="submit" name="submit_btn" id="submit_btn" onclick="return saveBtn('submit_btn');" title="Submit" class="btn btn-primary btn-sm form_submit_valid">Submit</button>
									<?php 
								} 
							?>
							<a href="<?php echo base_url(); ?>lov/manageLov" class="btn btn-default btn-sm">Close</a>
						</div>

						<script>
							
							$(document).ready(function() {
								var type = '<?php echo $type; ?>'
								$(".form_submit_valid").prop('disabled', true);

								function validateForm() {
									var list_name 			= $("#list_name").val();

									if (list_name.trim() !== "" ) {
										$(".form_submit_valid").prop('disabled', false); 
									} else {
										$(".form_submit_valid").prop('disabled', true); 
									}
								}

								// Add event listeners for input and select elements
								$("input").on('keyup change', function() { 
									validateForm();
								});

								validateForm(); // Initial check on page load

								function saveBtn(val) {
									
									$(".form_submit_valid").prop('disabled', true);

									var list_name 		= $("#list_name").val();
									
									if (list_name.trim() !== "") {
										$(".list_name").removeClass('errorClass');
										
										return true;
									} else {
										$(".form_submit_valid").prop('disabled', false); // Re-enable buttons

										if (list_name.trim() === "") {
											$(".list_name").addClass('errorClass');
										}
										
										
										return false;
									}
								}
							});
						</script>
					</form>
					<?php
				}
				else if(isset($type) && $type == "ManageListTypeValues" || $type == "viewListType" )
				{
					?>
					<!-- Add Member start-->
					<legend>
						<div class="row">
							<div class="col-md-6">
								<h3><b>List of Values</b></h3>
							</div>

							<div class="col-md-6 text-right">																
								<a href='<?php echo base_url();?>lov/manageLov' class='btn btn-outline-info btn-sm'>
									<i class="icon-arrow-left16"></i> Back
								</a>
								<a title="Edit" href="<?php echo base_url();?>lov/manageLov/edit/<?php echo $id;?>" class="btn btn-sm btn-primary">
									<i class="fa fa-pencil-square-o"></i>
								</a>	
							</div>
							
						</div>
					</legend>

					<style>
						section.list-type-details {
							border: 1px solid #d3d3d3;
							padding: 16px 11px 16px 9px;
						}
					</style>
					
					<section class="header-section">
						<div class="row">
							<div class="col-md-6 mb-1">
								<div class="row">
									<label class="col-md-3">List Name</label>
									<label class="col-md-1">:</label>
									<div class="col-md-6">
										<?php echo isset($edit_data[0]['list_name']) ? $edit_data[0]['list_name'] :"";?>
									</div>
								</div>
							</div>

							<div class="col-md-6 mb-1">
								<div class="row">
									<label class="col-md-3">Start Date</label>
									<label class="col-md-1">:</label>
									<div class="col-md-6">
										<?php 
											if(isset($edit_data[0]['start_date']) && !empty($edit_data[0]['start_date'])){
												$start_date = date(DATE_FORMAT,strtotime($edit_data[0]['start_date']));
											}else{$start_date = NULL;}
											echo $start_date;
										?>
									</div>
								</div>
							</div>
						</div>

						<div class="row">
							<div class="col-md-6">
								<div class="row">
									<label class="col-md-3">List Description</label>
									<label class="col-md-1">:</label>
									<div class="col-md-6">
										<?php echo isset($edit_data[0]['list_description']) ? $edit_data[0]['list_description'] :"";?>
									</div>
								</div>
							</div>

							<div class="col-md-6">
								<div class="row">
									<label class="col-md-3">End Date</label>
									<label class="col-md-1">:</label>
									<div class="col-md-6">
										<?php 
											if(isset($edit_data[0]['end_date']) && !empty($edit_data[0]['end_date'])){
												$end_date = date(DATE_FORMAT,strtotime($edit_data[0]['end_date']));
											}else{$end_date = NULL;}
										?>
										<?php echo $end_date;?>
									</div>
								</div>
							</div>
						</div>
					</section>

					
					<?php 
						if($type == "viewListType")
						{
							?>
							<br>
							<?php
						}
						else
						{
							?>
							<section class="header-section mt-3 mb-3">
								<form action="" --class="form-validate-jquery" enctype="multipart/form-data" method="post">
									<legend>
										<h5 class="page-titles">List Type Values</h5>
									</legend>
									
									<div class="row">
										<div class="form-group col-md-3">
											<label class="col-form-label float-left">List Code <span class="text-danger">*</span></label>
											<input type="text" name="list_code" id="list_code" autocomplete="off" required class="form-control" value="" placeholder="">
											<script>
												$(document).ready(function() {
													$('#list_code').on('input', function () 
													{
														let value = $(this).val();

														value = value.toUpperCase().replace(/^\s+/, '').replace(/\s+/g, ' ');

														$(this).val(value);
													});
												});
											</script>
										</div>
										
										<div class="form-group col-md-3">
											<label class="col-form-label float-left">List Value <span class="text-danger">*</span></label>
											<input type="text" name="list_value" id="list_value" autocomplete="off" required class="form-control" value="">
										</div>
									
										<div class="form-group col-md-3">
											<label class="col-form-label float-left">Order Sequence </label>
											<input type="number" name="order_sequence" id="order_sequence" autocomplete="off" class="form-control" value="" >
										</div>
										
										<div class="form-group col-md-3">
											<label class="col-form-label float-left">Short Description</label>
											<input type="text" name="short_description" id="short_description" autocomplete="off"  class="form-control" value="" placeholder="">
										</div>
									</div>

									<div class="row mb-3">
										<div class="form-group col-md-3">
											<label class="col-form-label float-left">Start Date </label>
											<?php 
												/* if(isset($edit_data[0]['start_date']) && !empty($edit_data[0]['start_date'])){
													$start_date = date(DATE_FORMAT,strtotime($edit_data[0]['start_date']));
												}else{$start_date = NULL;} */
												$start_date = NULL;
											?>
											<input type="text" name="start_date" id="start_date" readonly class="form-control" value="<?php echo $start_date;?>" placeholder="">
										</div>

										<div class="form-group col-md-3">
											<label class="col-form-label float-left">End Date</label>
											<?php 
												/* if(isset($edit_data[0]['end_date']) && !empty($edit_data[0]['end_date'])){
													$end_date = date(DATE_FORMAT,strtotime($edit_data[0]['end_date']));
												}else{$end_date = NULL;} */
												$end_date = NULL;
											?>
											<input type="text" name="end_date" id="end_date" readonly class="form-control" value="<?php echo $end_date;?>" placeholder="">
										</div>			
										
										<div class="form-group col-md-3">
											<label class="col-form-label float-left">Image </label>
											<input type="file" name="upload_image" id="upload_image" class="form-control" accept="image/*" onchange="return validateSingleFileExtension(this)" accept="image/*">
											<span class="note-class"><b>Note</b> : Upload size is 1 [MB] and image format is (png,gif,jpg,jpeg and bmp).</span>
											<script>
												function validateSingleFileExtension(fld) 
												{
													var fileUpload = fld;
													
													if (typeof (fileUpload.files) !== "undefined") 
													{
														if (!/(\.pdf)$/i.test(fileUpload.value)) 
														{
															alert("Invalid file type. Please select a PDF file.");
															fileUpload.value = '';
															return false;
														}
														return true;
													}
													return false;
												}
											</script>

										</div>

										<div class="form-group col-md-3 mt-4" style="padding: 10px 0px 0px 0px">
											<label class="col-form-label"></label>
											<input type="submit" name="Add" value="Add" class="btn btn-primary">
										</div>	
									</div>
								</form>

								<form action="" method="get">
									<section>
										<div class="row">
											<div class="col-md-6">
												<div class="row">
													<!-- <div class="col-md-6">	
														<input type="search" class="form-control" name="keywords" value="<?php echo !empty($_GET['keywords']) ? $_GET['keywords'] :""; ?>" placeholder="Search...">
													</div>	
													<div class="col-md-3">
														<button type="submit" class="btn btn-primary">Search <i class="fa fa-search" aria-hidden="true"></i></button>
													</div> -->
												</div>
											</div>
											
											<div class="col-md-6">
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
									</section>
								</form>
							</section>
							<?php 
						} 
					?>
				
					<form method="post" enctype="multipart/form-data">
						<div class="table-scroll">
							<table id="myTable" class="table table-bordered table-hover tbl_height">
								<thead>
									<tr>
										<?php 
											if($type == "viewListType")
											{
												
											}
											else
											{
												?>
												<th class="text-center">Controls</th>
												<?php 
											} 
										?>
										<th>List Code</th>
										<th>List Value</th>
										<th class="text-center">Order Sequence</th>
										<th class="text-center">Image</th>
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
												<?php 
													if($type == "viewListType")
													{
														
													}
													else
													{
														?>
														<td style="text-align:center;width: 15%;">
															<div class="dropdown" style="width: 128px;">
																<button type="button" class="btn btn-outline-info gropdown-toggle btn-sm" data-toggle="dropdown" aria-expanded="false">
																	Action <i class="fa fa-angle-down"></i>
																</button>
																<ul class="dropdown-menu dropdown-menu-right">
																	<li>
																		<!--<a href="<?php echo base_url(); ?>lov/manageLov/edit/<?php echo $row['list_type_value_id'];?>">
																		-->
																		<a title="Edit" href="javascript:void(0)" data-toggle="modal" data-target="#exampleModal<?php echo $row['list_type_value_id'];?>">
																			<i class="fa fa-edit"></i> Edit
																		</a>
																	</li>
																	
																	<li>
																		<?php 
																			if($row['active_flag'] == $this->active_flag)
																			{
																				?>
																				<a class="unblock" href="<?php echo base_url(); ?>lov/manageLov/TypeValuestatus/<?php echo $row['list_type_id'];?>/<?php echo $row['list_type_value_id'];?>/N" title="Inactive">
																					<i class="fa fa-ban"></i> Inactive
																				</a>
																				<?php 
																			} 
																			else
																			{  ?>
																				<a class="block" href="<?php echo base_url(); ?>lov/manageLov/TypeValuestatus/<?php echo $row['list_type_id'];?>/<?php echo $row['list_type_value_id'];?>/Y" title="Active">
																					<i class="fa fa-check"></i> Active
																				</a>
																				<?php 
																			} 
																		?>
																	<li>
																</ul>
															</div>

															<!-- Edit Model Dialog start -->
															<div class="modal fade" id="exampleModal<?php echo $row['list_type_value_id'];?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
																<div class="modal-dialog" role="document">
																	<div class="modal-content">
																		<div class="modal-header">
																			<h5 class="modal-title" id="exampleModalLabel">Edit</h5>
																			<button type="button" class="close" data-dismiss="modal" aria-label="Close">
																				<span aria-hidden="true">&times;</span>
																			</button>
																		</div>
																		
																		<form action="" class="form-validate-jquery" enctype="multipart/form-data" method="post">
																			
																			<div class="modal-body">
																				<!-- Items Start-->
																				<fieldset --class="mb-3">
																					<div class="row">
																						<div class="form-group col-md-6">
																							<label class="col-form-label float-left">List Code <span class="text-danger">*</span></label>
																							<input type="text" name="list_code" value="<?php echo $row['list_code'];?>" id="list_code" required class="form-control" oninput="upperValue(this)">
																							<input type="hidden" name="list_type_value_id" value="<?php echo $row['list_type_value_id'];?>">
																							<script>
																								function upperValue(inputElement) {
																									// Convert the input value to uppercase and trim unnecessary spaces
																									let value = inputElement.value;
																									value = value.toUpperCase().replace(/^\s+/, '').replace(/\s+/g, ' ');
																									inputElement.value = value;
																								}
																							</script>
																						</div>
																						<div class="form-group col-md-6">
																							<label class="col-form-label float-left">List Value <span class="text-danger">*</span></label>
																							<input type="text" name="list_value" value="<?php echo $row['list_value'];?>" <?php echo $this->validation;?> id="list_value" required class="form-control">
																						</div>
																					</div>
																					
																					<div class="row">
																						<div class="form-group col-md-6">
																							<label class="col-form-label float-left">Order Sequence </label>
																							<input type="number"  name="order_sequence" value="<?php echo $row['order_sequence'];?>" id="order_sequence" class="form-control" >
																						</div>
																						
																						<div class="form-group col-md-6">
																							<label class="col-form-label float-left">Short Description</label>
																							<input type="text" name="short_description" value="<?php echo $row['short_description'];?>" id="short_description" class="form-control">
																						</div>
																					</div>

																					<div class="row">
																						<div class="form-group col-md-6">
																							<label class="col-form-label float-left">Start Date </label>
																							<?php 
																								if(isset($row['start_date']) && !empty($row['start_date'])){
																									$start_date = date(DATE_FORMAT,strtotime($row['start_date']));
																								}else{$start_date = NULL;}
																							?>
																							<input type="text" name="start_date" id="start_date1" readonly class="form-control start_date" value="<?php echo $start_date;?>" placeholder="">
																						</div>
																						
																						<div class="form-group col-md-6">
																							<label class="col-form-label float-left">End Date</label>
																							<?php 
																								if(isset($row['end_date']) && !empty($row['end_date'])){
																									$end_date = date(DATE_FORMAT,strtotime($row['end_date']));
																								}else{$end_date = NULL;}
																							?>
																							<input type="text" name="end_date" id="end_date1" readonly class="form-control end_date" value="<?php echo $end_date;?>" placeholder="">
																						</div>
																					</div>

																					<div class="row">
																						<div class="form-group col-md-6 float-left">
																							<label class="col-form-label float-left">Image </label>
																							<input type="file" name="upload_image" id="upload_image" class="form-control singleImage" accept="image/*" onchange="validateSingleFileExtension(this)">
																							<span class="note-class"><b>Note</b> : Upload size is 1 [MB] and image format is (png,gif,jpg,jpeg and bmp).</span>
																							
																							<?php 
																								$url = "uploads/lov_images/".$row['list_type_value_id'].".png";
																								if(file_exists($url))
																								{
																									?><br>
																									<img src="<?php echo base_url().$url;?>" style="width:90px;height:50px;float:left;" alt="...">
																									<?php 
																								}
																							?>

																							<script>
																								/** Single Image Type & Size Validation **/
																								function validateSingleFileExtension(fld) {
																									var fileUpload = fld;

																									if (typeof (fileUpload.files) != "undefined") {
																									var size = parseFloat(fileUpload.files[0].size / 1024).toFixed(2); // size in KB
																									var validSize = 1024 * 1; // 1 MB size limit

																									// Check file size
																									if (size > validSize) {
																										alert("Upload size exceeds 1 MB");
																										fileUpload.value = ''; // Reset the input field
																										return false;
																									}

																									// Check file type
																									if (!/(\.png|\.bmp|\.gif|\.jpg|\.jpeg|\.webp)$/i.test(fileUpload.value)) {
																										alert("Invalid file type. Please select one of the following: .png, .bmp, .gif, .jpg, .jpeg, .webp");
																										fileUpload.value = ''; // Reset the input field
																										return false;
																									}

																									// If both checks pass
																									return true;
																									}
																									
																									return false; // Default return if no files selected
																								}
																							</script>
																						</div>
																					</div>
																				</fieldset>
																				<!-- Items Start-->
																			</div>

																			<div class="modal-footer">
																				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
																				<input type="submit" name="Update" value="Update" class="btn btn-primary">
																			</div>
																		</form>
																	</div>
																</div>
															</div>
															<!-- Edit Model Dialog end -->
														</td>
														<?php 
													} 
												?>
												
												<td><?php echo $row['list_code'];?></td>
												<td><?php echo ucfirst($row['list_value']);?></td>
												<td class="text-center"><?php echo $row['order_sequence'];?></td>
												<td class="text-center">
													<?php 
														$url = "uploads/lov_images/".$row['list_type_value_id'].".png";
														if(file_exists($url))
														{
															?>
															<img src="<?php echo base_url().$url;?>" style="width:90px;height:50px;" alt="...">
															<?php 
														}
													?>
												</td>
												
												<td class="text-center">
													<?php 
														if($row['active_flag'] == $this->active_flag)
														{
															?>
															<span class="btn btn-outline-success btn-sm" title="Active"> Active</span>
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
					</form>
					<?php 
				}
				else
				{
					?>
					<form action="" method="get">
						<section class="trans-section-back-1">
							
							<div class="row">
								<div class="col-md-6">	
									<h3><b>List of Values</b></h3>
								</div>
								<div class="col-md-6">	
									<?php
										if(isset($type) && $type == "add" || $type == "edit")
										{ 
										}
										else if(isset($type) && $type == "ManageListTypeValues")
										{ 
											
											?>
											<a href='<?php echo base_url();?>lov/manageLov' class='btn btn-info'>
												<i class="icon-arrow-left16"></i> Back
											</a>
											<?php
										}
										else
										{ 
											?>
											<div class="new-import-btn new-button-1">
												<a href="<?php echo base_url();?>setup/settings" class="btn btn-info">
													<i class="icon-arrow-left16"></i> 
													Back
												</a>
												<a href="<?php echo base_url(); ?>lov/manageLov/add" class="btn btn-info">
													Create List Name
												</a>
											</div>
											<?php 
										} 
									?>
								</div>
							</div>
						</section>
						
						<?php 
							$redirect_url = substr($_SERVER['REQUEST_URI'],'1');
						?>
						<input type="hidden" id="redirect_url" value="<?php echo $redirect_url; ?>"/>
						<div class="row">
							<div class="col-md-8">
								<div class="row">	
									<div class="col-md-4">	
										<input type="search" class="form-control" autocomplete="off" name="keywords" value="<?php echo !empty($_GET['keywords']) ? $_GET['keywords'] :""; ?>" placeholder="Search...">
									</div>

									<div class="col-md-4">
										<div class="row">
											<label class="col-form-label col-md-3">Status</label>
											<div class="form-group col-md-9">
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

									<div class="col-md-3">
										<button type="submit" class="btn btn-info --trans-saction-butt">Search <i class="fa fa-search" aria-hidden="true"></i></button>
										<a href="<?php echo base_url(); ?>lov/manageLov" title="Clear" class="btn btn-default">Clear</a>
									</div>
								</div>
							</div>
							
						</div>
					</form>

					<?php 
						if(isset($_GET) &&  !empty($_GET))
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
							<form method="post">
								<div class="table-scroll">
									<table --id="myTable" class="table table-bordered -sortable-table table-hover tbl_height --table-striped --dataTable">
										<thead>
											<tr>
												<th class="tab-md-100 text-center">Controls</th>
												<th class="tab-md-140">List Name</th>
												<th class="tab-md-140 text-center">List Type Values</th>
												<th class="tab-md-100 text-center">Status</th>
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
														<td style="text-align:center;width: 15%;">
															<?php /* <td align="center"><?php echo $i + $firstItem ;?></td> */ ?>
															<div class="dropdown" style="width: 128px;">
																<button type="button" class="btn btn-outline-info gropdown-toggle btn-sm" data-toggle="dropdown" aria-expanded="false">
																	Action <i class="fa fa-angle-down"></i>
																</button>
																<ul class="dropdown-menu dropdown-menu-right">
																	<li>
																		<a href="<?php echo base_url();?>lov/manageLov/viewListType/<?php echo $row['list_type_id'];?>" title="View">
																			<i class="fa fa-eye"></i> View
																		</a>
																	</li>
																	<li>
																		<!-- <a href="<?php #echo base_url(); ?>lov/manageLov/edit/<?php #echo $row['list_type_id'];?>"> -->
																		<a href="<?php echo base_url();?>lov/manageLov/ManageListTypeValues/<?php echo $row['list_type_id'];?>" title="Edit">
																			<i class="fa fa-edit"></i> Edit
																		</a>
																	</li>
																	
																	<li>
																		<?php 
																			if( $row['active_flag'] == $this->active_flag )
																			{
																				?>
																				<a class="unblock" href="<?php echo base_url(); ?>lov/manageLov/status/<?php echo $row['list_type_id'];?>/N" title="Inactive">
																					<i class="fa fa-ban"></i> Inactive
																				</a>
																				<?php 
																			} 
																			else
																			{  ?>
																				<a class="block" href="<?php echo base_url(); ?>lov/manageLov/status/<?php echo $row['list_type_id'];?>/Y" title="Active">
																					<i class="fa fa-check"></i> Active
																				</a>
																				<?php 
																			} 
																		?>
																	<li>
																</ul>
															</div>
														</td>
														
														<td><?php echo ucfirst($row['list_name']);?></td>
														
														<td class="tab-full-width" style="text-align:center;">
															<?php 
																$listTypeCount = $this->db->query("select list_type_value_id from sm_list_type_values where list_type_id ='".$row['list_type_id']."' ")->result_array();
																if(count($listTypeCount) > 0)
																{
																	$btn_class='success';
																}
																else
																{
																	$btn_class='warning';
																}
															?>
															<a href="<?php echo base_url();?>lov/manageLov/ManageListTypeValues/<?php echo $row['list_type_id'];?>" title="List Type Values">
																<span class="btn btn-outline-<?php echo $btn_class; ?>" style="width:40%;">List Type Values (<?php echo count($listTypeCount);?>)</span>
															</a>
															<a href="<?php echo base_url();?>lov/manageLov/ManageListTypeValues/<?php echo $row['list_type_id'];?>" title="List Type Values">
																<i class="fa fa-plus" aria-hidden="true"></i>
															</a>
														</td>
														
														
														<td style="text-align:center;">
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
									if(count($resultData) >  0)
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
								
							</form>
							
							<?php 
						} 
					?>
					<?php 
				} 
			?>
		</div><!-- Card end-->
	</div><!-- Card end-->
</div><!-- Content end-->

