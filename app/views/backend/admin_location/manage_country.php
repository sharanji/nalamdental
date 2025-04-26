<div class="content"><!-- Content start-->
	<div class="card"><!-- Card start-->
		<div class="card-body">
			
			<?php
				if(isset($type) && $type == "add" || $type == "edit")
				{
					?>
					<form action="" class="form-validate-jquery" enctype="multipart/form-data" method="post">
						<fieldset class="mb-3">
							<legend class="text-uppercase font-size-sm font-weight-bold">Country</legend>

							<div class="form-group row">
								<label class="col-form-label col-lg-2">Country Name <span class="text-danger">*</span></label>
								<div class="col-lg-3">
									<input type="text" name="country_name" <?php echo $this->validation; ?> class="form-control only_name" required value="<?php echo isset($edit_data[0]['country_name'])?$edit_data[0]['country_name']:"";?>" placeholder="">
								</div>
							</div>
							
							<div class="form-group row">
								<label class="col-form-label col-lg-2">Country Code <span class="text-danger">*</span></label>
								<div class="col-lg-3">
									<input type="text" name="country_code" class="form-control country_code" required value="<?php echo isset($edit_data[0]['country_code'])?$edit_data[0]['country_code']:"";?>" placeholder="">
									<span class="text-muted">Ex : +91,+92,+93, etc..</span>
								</div>
							</div>
							
							<div class="form-group row">
								<label class="col-form-label col-lg-2">Country Symbol <span class="text-danger">*</span></label>
								<div class="col-lg-3">
									<input type="text" name="currency_symbol" class="form-control" required value="<?php echo isset($edit_data[0]['currency_symbol'])?$edit_data[0]['currency_symbol']:"";?>"placeholder="">
									<span class="text-muted">Ex : $,AL,AX, etc..</span>
								</div>
							</div>
							
							<div class="form-group row">
								<label class="col-form-label col-lg-2">Currency Code <span class="text-danger">*</span></label>
								<div class="col-lg-3">
									<input type="text" name="currency_code" <?php echo $this->validation; ?> class="form-control only_name" required value="<?php echo isset($edit_data[0]['currency_code'])?$edit_data[0]['currency_code']:"";?>" placeholder="">
									<span class="text-muted">Ex : INR,USD,EUR,etc...</span>
								</div>
							</div>

							<div class="form-group row">
								<label class="col-form-label col-lg-2">Country Flag</label>
								<div class="col-lg-3">
									<input type="file" name="country_icon" onchange="return validateSingleFileExtension(this)" class="form-control singleImage">
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
										if(isset($type) && $type == "edit")
										{
											if(file_exists("uploads/country_icons/".$edit_data[0]['country_id'].'.png') )
											{
												$photo_url = base_url().'uploads/country_icons/'.$edit_data[0]['country_id'].'.png';
												?><br>
												<img src="<?php echo $photo_url;?>" style="border-radius:4px; padding:5px; height:50px; width:50px;" alt="">
												<?php 
											}
										}
									?>
								</div>
							</div>
						</fieldset>
						
						<div class="d-flex justify-content-end align-items-center">
							<a href="<?php echo base_url(); ?>admin_location/manage_country" class="btn btn-default">Close</a>
							<button type="submit" class="btn btn-primary ml-1">Save</button>
						</div>
					</form>
					<?php
				}
				else
				{
					?>
					<div class="row mb-3">
						<div class="col-md-6"><?php echo $page_title;?></div>
						<div class="col-md-6 float-right text-right">
							<a href="<?php echo base_url(); ?>setup/settings" class="btn btn-info btn-sm"><i class="icon-arrow-left16"></i> Back</a>
							
							<a href="<?php echo base_url(); ?>admin_location/manage_country/add" class="btn btn-info btn-sm"></i> 
								Create Country
							</a>
						</div>
					</div>

					<!-- Filters start here -->
					<form action="" class="form-validate-jquery" method="get">
						<div class="row mt-3">
							<div class="col-md-4">
								<div class="row">
									<label class="col-form-label col-md-4">Country Name <!-- <span class="text-danger">*</span> --></label>
									<div class="form-group col-md-8">
										<?php 
											$country_qry = "select country_id, country_name from geo_countries 
											order by country_name";

											$getCountry = $this->db->query($country_qry)->result_array(); 
										?>
										
										<select name="country_id" id="country_id" class="form-control searchDropdown">
											<option value="">- Select -</option>
											<?php 
												foreach($getCountry as $row)
												{
													$selected="";
													if(isset($_GET['country_id']) && $_GET['country_id'] == $row["country_id"] )
													{
														$selected="selected='selected'";
													}
													?>
													<option value="<?php echo $row["country_id"];?>" <?php echo $selected;?>><?php echo $row["country_name"];?></option>
													<?php 
												} 
											?>
										</select>
									</div>
								</div>
							</div>

							
							<div class="col-md-3">
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
	
											sm_list_types.list_name = 'ACTIVESTATUS'";
	
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
							
							<div class="col-md-4">
								<div class="row">
									<div class="col-md-3">
										<button type="submit" class="btn btn-info">Search <i class="fa fa-search" aria-hidden="true"></i></button>
									</div>
									<div class="col-md-3">
										<a href="<?php echo base_url(); ?>admin_location/manage_country" title="Clear" class="btn btn-default">Clear</a>
									</div>
								</div>
							</div>
						</div>

					</form>
					<!-- Filters end here -->
					
					<?php
						if(isset($_GET) && !empty($_GET))
						{
					 		?>
							<!-- Page Item Show start -->
							<div class="row">
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

							<!-- Table start here -->
							<form action="" method="post">
								<div --class="new-scroller">
									<table class="table table-bordered table-hover">
										<thead>
											<tr>
												<th class="text-center">Controls</th>
												<th>Country Name</th>
												<th>Country Code</th>
												<th>Country Symbol</th>
												<th>Currency Code</th>
												<th class="text-center">Status</th>
												<th class="text-center">Default Country</th>
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
															<div class="dropdown" --style="display: inline-block;padding-right: 10px!important;">
																<button type="button" class="btn btn-outline-primary gropdown-toggle waves-effect waves-light btn-sm" data-toggle="dropdown" aria-expanded="false">
																	Action <i class="fa fa-angle-down"></i>
																</button>
																<ul class="dropdown-menu dropdown-menu-right dropdown-menu-new">
																	<li>
																		<a title="Edit" href="<?php echo base_url(); ?>admin_location/manage_country/edit/<?php echo $row['country_id'];?>">
																			<i class="fa fa-pencil"></i> Edit
																		</a>
																	</li>
																	<li>
																		<?php
																			#if($row['active_flag'] == $this->active_flag)
																			if($row['active_flag'] == 'Y')
																			{
																				?>
																				<a href="<?php echo base_url(); ?>admin_location/manage_country/status/<?php echo $row['country_id'];?>/N" title="Block">
																					<i class="fa fa-ban"></i> Inactive
																				</a>
																				<?php 
																			} 
																			else
																			{  ?>
																				<a href="<?php echo base_url(); ?>admin_location/manage_country/status/<?php echo $row['country_id'];?>/Y" title="Unblock">
																					<i class="fa fa-ban"></i> Active
																				</a>
																				<?php 
																			} 
																		?>
																	</li>
																	
																</ul>
															</div>
														</td>

														<?php /* <td class="text-center">
															<?php 
																if(file_exists("uploads/country_icons/".$row['country_id'].'.png') )
																{
																	$photo_url = base_url().'uploads/country_icons/'.$row['country_id'].'.png';
																	$BranchName = ucfirst($row['country_name']);
																	?>
																	<img src="<?php echo $photo_url;?>" style="border-radius:4px; padding:5px; height:50px; width:50px;" alt="">
																	<?php 
																}
																else
																{
																	?>
																	<img src="<?php echo base_url();?>uploads/no-image.png" style="border-radius:4px; padding:5px; height:50px; width:50px;" alt="...">
																	<?php
																}
															?>
														</td> */ ?>

														<td><?php echo ucfirst($row['country_name']);?></td>
														<td><?php echo ucfirst($row['country_code']);?></td>
														<td><?php echo $row['currency_symbol'];?></td>
														<td><?php echo ucfirst($row['currency_code']);?></td>
														
														<td class="text-center">
															<?php 
																#if($row['active_flag'] == $this->active_flag)
																if($row['active_flag'] == 'Y')
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
																$btnChecked = "";
																if($row['default_country'] == 1){
																	$btnChecked = "checked=checked";
																}
																	?>
																<?php 
																if($row['active_flag'] == 'Y')
																{
																	?>
																	<input type="radio" class="default_country" <?php echo $btnChecked;?> id="default_country" name="default_country" value="<?php echo $row['country_id']; ?>">
																	<?php 
																} 
															?>
															<?php 
																/* if($row['default_country'] == 1)
																{
																	?>
																	<i class="fa fa-check" aria-hidden="true" style="color:#0bc50b;font-size: 12px;"></i>
																	<?php
																}
																else if($row['active_flag'] == 'Y')
																{
																	?>
																	<i class="fa fa-close" aria-hidden="true" style="color:red;font-size: 12px;"></i>
																	<?php 
																}  */
															?>
														</td>
														
													</tr>
													<?php 
													$i++;
												}
											?>
										</tbody>
										<?php 
											if (count($resultData) > 0 and (isset($_GET["active_flag"]) && $_GET["active_flag"] !="N")) 
											{
												?>
												<tr>
													<td colspan="6"></td>
													<td style="text-align:center;"><button type="submit"name="default_submit"class="btn btn-outline-primary btn-sm ml-1">Update</button></td>
												</tr>
												<?php 
											}
										?>
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
							<!-- Table end here -->
											
							<!-- Pagination start here -->
							<?php 
								if( count($resultData) > 0 )
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

