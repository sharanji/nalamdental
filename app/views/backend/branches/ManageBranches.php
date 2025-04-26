<script>
$(document).ready(function()
{ 
	// Initialize select2
	$(".searchDropdown").select2();
	// Read selected option
   /* $('#but_read').click(function(){
		var username = $('#selUser option:selected').text();
		var userid = $('#selUser').val();
		$('#result').html("id : " + userid + ", name : " + username);
	}); */
	
	//$(".searchDropdown").empty();
	//$(".searchDropdown").select2("val", "");
});
</script>

<!-- Page header start-->
<div class="page-header page-header-light">
	<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
		<div class="d-flex">
			<div class="breadcrumb">
				<a href="<?php echo base_url();?>admin/dashboard" class="breadcrumb-item"><i class="icon-home2 mr-2"></i>Home</a>
				<a href="<?php echo base_url();?>branches/ManageBranches" class="breadcrumb-item"><?php echo $page_title;?></a>
			</div>
		</div>
		<?php
			if(isset($type) && $type == "add" || $type == "edit" || $type == "zone" || $type == "containsLocation")
			{ 
				
			}
			else if(isset($type) && $type == "view")
			{ 
				?>
				<div class="text-right new-import-btn">
					<a href="<?php echo base_url(); ?>branches/ManageBranches/edit/<?php echo $id;?>" class="btn btn-info">
						Edit Branch
					</a>
				</div>
				<?php
			}
			else
			{ 
				?>
				<div class="text-right new-import-btn">
					<a href="<?php echo base_url(); ?>audit_report/auditSummary/branchAudit" target="_blank" class="btn btn-default audit-records">
						Audit Records
					</a>
					<a href="<?php echo base_url(); ?>branches/ManageBranches/add" class="btn btn-info">
						Add Branch
					</a>
				</div>
				<?php 
			} 
		?>
	</div>
</div>
<!-- Page header end-->

<div class="content"><!-- Content start-->
	<div class="card"><!-- Card start-->
		<div class="card-body">
			<?php
				if(isset($type) && $type == "add" || $type == "edit" || $type == "view")
				{
					if($type == "view"){
						$fieldSetDisabled = "disabled";
						#$dropdownDisabled = "style='pointer-events: none;'";
						$searchDropdown = "";
					}else{
						$fieldSetDisabled = "";
						#$dropdownDisabled = "";
						$searchDropdown = "searchDropdown";
					}
					?>
					<script>
						function initMap() 
						{
							var input = document.getElementById('autocomplete');
							var autocomplete = new google.maps.places.Autocomplete(input);

							autocomplete.addListener('place_changed', function() 
							{
								var place = autocomplete.getPlace();
								if (!place.geometry) {
									window.alert("Autocomplete's returned place contains no geometry");
									return;
								}

								var address = '';
								if (place.address_components) 
								{
									address = [
										(place.address_components[0] && place.address_components[0].short_name || ''),
										(place.address_components[1] && place.address_components[1].short_name || ''),
										(place.address_components[2] && place.address_components[2].short_name || '')
									].join(' ');
								}
							});
						}
					</script>
					<script async defer src="https://maps.googleapis.com/maps/api/js?key=<?php echo GOOGLE_MAP_API_KEY;?>&libraries=places&callback=initMap"></script>
						
					<form action="" --class="form-validate-jquery" enctype="multipart/form-data" method="post" autocomplete="off">
							
						<div class="row">
							<div class="col-md-6"><h3><b>Branch</b></h3></div>
							<div class="col-md-6 text-right">
								<a href="<?php echo base_url(); ?>branches/ManageBranches" class="btn btn-default btn-sm">Close</a>
								<?php 
									if($type == "view")
									{

									}
									else
									{
										?>
										<button type="submit" class="btn btn-primary btn-sm">Save</button>
										<?php 
									}
									?>
							</div>
						</div>
						
						<fieldset class="mb-3" <?php echo $fieldSetDisabled;?>>
							<!-- branch_info start -->
							<div class="branch_info">
								<div class="row">
									<div class="col-md-12 header-filters">
										<a href="javascript:void(0)" class="filter-icons first_sec_hide" onclick="sectionShow('FIRST_SECTION','SHOW');">
											<i class="fa fa-chevron-circle-down"></i>
										</a>
										<a href="javascript:void(0)" class="filter-icons first_sec_show" onclick="sectionShow('FIRST_SECTION','HIDE');" style="display:none;">
											<i class="fa fa-chevron-circle-right"></i>
										</a>
										<h5 class="pl-1"><b>Branch Info</b></h5>
									</div>
								</div>

								<section class="header-section first_section">

									<div class="row">
										<div class="col-md-6">
											<div class="row">
												<label class="col-form-label col-md-4"><span class="text-danger">*</span> Organization</label>
												<div class="form-group col-md-5">
													<select name="organization_id" id="organization_id" required  class="form-control <?php echo $searchDropdown;?>">
														<option value="">- Select -</option>
														<?php 
															$getOrganization = $this->organization_model->getOrgAll();
															
															foreach($getOrganization as $row)
															{
																$selected="";
																if(isset($edit_data[0]['organization_id']) && $edit_data[0]['organization_id'] == $row['organization_id'])
																{
																	$selected="selected";
																}
																?>
																<option value="<?php echo $row['organization_id']; ?>" <?php echo $selected; ?>><?php echo ucfirst($row['organization_name']);?></option>
																<?php 
															} 
														?>
													</select>
												</div>
											</div>
										</div>
										
										<div class="col-md-6">
											<div class="row">
												<label class="col-form-label col-md-4"><span class="text-danger">*</span> Branch Code</label>
												<div class="form-group col-md-3">
													<input type="text" name="branch_code" <?php echo $this->validation;?> required class="form-control" value="<?php echo isset($edit_data[0]['branch_code']) ? $edit_data[0]['branch_code'] :"";?>" placeholder="Branch Code">
												</div>
											</div>
										</div>
									</div>

									<div class="row">
										<div class="col-md-6">
											<div class="row">
												<label class="col-form-label col-md-4"><span class="text-danger">*</span> Branch Name</label>
												<div class="form-group col-md-5">
													<input type="text" name="branch_name" <?php echo $this->validation;?> required class="form-control" value="<?php echo isset($edit_data[0]['branch_name']) ? $edit_data[0]['branch_name'] :"";?>" placeholder="Branch Name">
												</div>
											</div>
										</div>

										<div class="col-md-6">
											<div class="row">
												<label class="col-form-label col-md-4"><span class="text-danger">*</span> Mobile Number</label>
												<div class="form-group col-md-5">
													<input type="text" name="mobile_number" maxlength="10" required class="form-control mobile_vali" value="<?php echo isset($edit_data[0]['mobile_number']) ? $edit_data[0]['mobile_number'] :"";?>" placeholder="9999999999">
												</div>
											</div>
										</div>
									</div>


									<div class="row">
										<div class="col-md-6">
											<div class="row">
												<label class="col-form-label col-md-4">Alter Mobile Number</label>
												<div class="form-group col-md-5">
													<input type="text" name="alter_mobile_number"  minlength="10" maxlength='10' class="form-control mobile_vali" value="<?php echo isset($edit_data[0]['alter_mobile_number']) ? $edit_data[0]['alter_mobile_number'] :"";?>" placeholder="Alter Mobile Number">
												</div>
											</div>
										</div>

										<div class="col-md-6">
											<div class="row">
												<label class="col-form-label col-md-4">Email</label>
												<div class="form-group col-md-5">
													<input type="email" name="email" id="email" class="form-control" value="<?php echo isset($edit_data[0]['email'])? $edit_data[0]['email']:"";?>" placeholder='Email'>
												</div>
											</div>
										</div>
									</div>


									<div class="row">
										<div class="col-md-6">
											<div class="row">
												<label class="col-form-label col-md-4"><span class="text-danger">*</span> Location</label>
												<div class="form-group col-md-5">
													<select name="location_id" id="location_id" required  class="form-control <?php echo $searchDropdown;?>">
														<option value="">- Select -</option>
														<?php 
															$condition = "location.active_flag ='Y'";
							
															$query = "select 
															location.location_id,
															location.location_name
															from loc_location_all as location
																	
															where $condition
																order by location.location_name desc
															";
															
															$getLocation = $this->db->query($query)->result_array();
															
															foreach($getLocation as $row)
															{
																$selected="";
																if(isset($edit_data[0]['location_id']) && $edit_data[0]['location_id'] == $row['location_id'])
																{
																	$selected="selected";
																}
																?>
																<option value="<?php echo $row['location_id']; ?>" <?php echo $selected; ?>><?php echo ucfirst($row['location_name']);?></option>
																<?php 
															} 
														?>
													</select>
												</div>
											</div>
										</div>

										<div class="col-md-6">
											<div class="row">
												<label class="col-form-label col-md-4"><span class="text-danger">*</span> Map Location</label>
												<div class="form-group col-md-6">
													<input type="text" name="map_location" required  class="form-control" id="autocomplete" value="<?php echo isset($edit_data[0]['map_location']) ? $edit_data[0]['map_location'] : NULL;?>" placeholder="Map Location">
												</div>
											</div>
										</div>
									</div>
								</section>
							</div>
							<!-- branch_info end -->

							<!-- branch settings start -->
							<div class="branch_settings">
								<div class="row">
									<div class="col-md-12 header-filters">
										<a href="javascript:void(0)" class="filter-icons sec_sec_hide" onclick="sectionShow('SECOND_SECTION','SHOW');">
											<i class="fa fa-chevron-circle-down"></i>
										</a>
										<a href="javascript:void(0)" class="filter-icons sec_sec_show" onclick="sectionShow('SECOND_SECTION','HIDE');" style="display:none;">
											<i class="fa fa-chevron-circle-right"></i>
										</a>
										<h5 class="pl-1"><b>Branch Settings</b></h5>
									</div>
								</div>

								<section class="header-section sec_section">
									<div class="row">
										<div class="col-md-6">
											<div class="row">
												<label class="col-form-label col-md-4"><span class="text-danger">*</span> Delivery Distance (KM)</label>
												<div class="form-group col-md-3">
													<input type="text" name="delivery_distance" inputmode="decimal" pattern="[0-9]*[.,]?[0-9]*" id="delivery_distance" class="form-control" required value="<?php echo isset($edit_data[0]['delivery_distance']) ? $edit_data[0]['delivery_distance'] : NULL;?>" placeholder="Delivery Distance">
													<span class='text-danger'>Note : Online order delivery distance</span>
												</div>
											</div>
										</div>

										<div class="col-md-6">
											<div class="row">
												<label class="col-form-label col-md-4"><span class="text-danger">*</span> Min Order Value</label>
												<div class="form-group col-md-3">
													<input type="text" name="minimum_order_value" class="form-control mobile_vali" required value="<?php echo isset($edit_data[0]['minimum_order_value']) ? $edit_data[0]['minimum_order_value'] : NULL;?>" placeholder="<?php echo CURRENCY_CODE;?> 100">
													<span class='text-danger'>Note : Online min order value</span>
												</div>
											</div>
										</div>
									</div>

									<div class="row">
										<div class="col-md-6">
											<div class="row">
												<label class="col-form-label col-md-4"><span class="text-danger">*</span> Auto Print Status</label>
												<div class="form-group col-md-5">
													<select type="text" name="auto_print_status" id="auto_print_status" required class="form-control <?php echo $searchDropdown;?>">
														<option value="">- Select -</option>
														<?php
															foreach($this->auto_print_status as $key => $value)
															{
																$selected="";
																if(isset($edit_data[0]['auto_print_status']) && $edit_data[0]['auto_print_status'] == $key)
																{
																	$selected="selected='selected'";
																} 
																?>
																<option value="<?php echo $key;?>" <?php echo $selected;?>><?php echo $value;?></option>
																<?php 
															} 
														?>
													</select>
													<span class='text-danger'>Note : Online, captain(Dine-In) order auto print status</span>
												</div>
											</div>
										</div>

										<div class="col-md-6">
											<div class="row">
												<label class="col-form-label col-md-4"><span class="text-danger">*</span> Confirm Print Status</label>
												<div class="form-group col-md-5">
													<select type="text" name="order_confirm_print_status" id="auto_print_status" required class="form-control <?php echo $searchDropdown;?>">
														<option value="">- Select -</option>
														<?php
															foreach($this->auto_print_status as $key => $value)
															{
																$selected="";
																if(isset($edit_data[0]['order_confirm_print_status']) && $edit_data[0]['order_confirm_print_status'] == $key)
																{
																	$selected="selected='selected'";
																} 
																?>
																<option value="<?php echo $key;?>" <?php echo $selected;?>><?php echo $value;?></option>
																<?php 
															} 
														?>
													</select>
													<span class='text-danger'>Note : Confirm print status (POS,Dine-In)</span>
												</div>
											</div>
										</div>
									</div>

									<div class="row">
										<div class="col-md-6">
											<div class="row">
												<label class="col-form-label col-md-4"><span class="text-danger">*</span> Captain Cancel Item Status</label>
												<div class="form-group col-md-5">
													<select type="text" name="captain_canel_item_status" id="captain_canel_item_status" required class="form-control <?php echo $searchDropdown;?>">
														<option value="">- Select -</option>
														<?php
															foreach($this->auto_print_status as $key => $value)
															{
																$selected="";
																if(isset($edit_data[0]['captain_canel_item_status']) && $edit_data[0]['captain_canel_item_status'] == $key)
																{
																	$selected="selected='selected'";
																} 
																?>
																<option value="<?php echo $key;?>" <?php echo $selected;?>><?php echo $value;?></option>
																<?php 
															} 
														?>
													</select>
													<span class='text-danger'>Note : Captain cancel item status (Dine-In)</span>
												</div>
											</div>
										</div>
									</div>
								</section>
							</div>
							<!-- branch settings end -->


							<!-- Branch Timings Start -->
							<div class="branch_timings">
								<div class="row">
									<div class="col-md-12 header-filters">
										<a href="javascript:void(0)" class="filter-icons thi_sec_hide" onclick="sectionShow('THIRD_SECTION','SHOW');">
											<i class="fa fa-chevron-circle-down"></i>
										</a>
										<a href="javascript:void(0)" class="filter-icons thi_sec_show" onclick="sectionShow('THIRD_SECTION','HIDE');" style="display:none;">
											<i class="fa fa-chevron-circle-right"></i>
										</a>
										<h5 class="pl-1"><b>Branch Timings</b></h5>
									</div>
								</div>
								<style>
									.branch-timings {
										border: 1px solid #ddd;
										padding: 10px;
										margin: 0px 0px 0px 9px;
									}
								</style>
								<section class="header-section thi_section">
									<div class="row">	
										<div class="col-md-3 branch-timings">
											<div class="row">
												<div class="col-md-12 mb-3"><b>Breakfast</b></div>
												<div class="col-md-12">
													<div class="row">
														<label class="col-form-label col-md-3"><span class="text-danger">*</span> From</label>
														<div class="form-group col-md-6">
															<input type="time" name="break_fast_from" id="break_fast_from" required class="form-control" value="<?php echo isset($edit_data[0]['break_fast_from']) ? $edit_data[0]['break_fast_from'] :"";?>" placeholder="">
														</div>
													</div>
												</div>

												<div class="col-md-12">
													<div class="row">
														<label class="col-form-label col-md-3"><span class="text-danger">*</span> To</label>
														<div class="form-group col-md-6">
															<input type="time" name="break_fast_to" to="break_fast_to" required class="form-control" value="<?php echo isset($edit_data[0]['break_fast_to']) ? $edit_data[0]['break_fast_to'] :"";?>" placeholder="">
														</div>
													</div>
												</div>
											</div>
										</div>

										<div class="col-md-3 branch-timings">
											<div class="row">
											<div class="col-md-12 mb-3"><b>Lunch</b></div>
												<div class="col-md-12">
													<div class="row">
														<label class="col-form-label col-md-3"><span class="text-danger">*</span> From</label>
														<div class="form-group col-md-6">
															<input type="time" name="lunch_from" id="lunch_from" required class="form-control" value="<?php echo isset($edit_data[0]['lunch_from']) ? $edit_data[0]['lunch_from'] :"";?>" placeholder="">
														</div>
													</div>
												</div>

												<div class="col-md-12">
													<div class="row">
														<label class="col-form-label col-md-3"><span class="text-danger">*</span> To</label>
														<div class="form-group col-md-6">
															<input type="time" name="lunch_to" id="lunch_to" required class="form-control" value="<?php echo isset($edit_data[0]['lunch_to']) ? $edit_data[0]['lunch_to'] :"";?>" placeholder="">
														</div>
													</div>
												</div>
											</div>
										</div>

										<div class="col-md-3 branch-timings">
											<div class="row">
												<div class="col-md-12 mb-3"><b>Dinner</b></div>
												<div class="col-md-12">
													<div class="row">
														<label class="col-form-label col-md-3"><span class="text-danger">*</span> From</label>
														<div class="form-group col-md-6">
															<input type="time" name="dinner_from" id="dinner_from" required class="form-control" value="<?php echo isset($edit_data[0]['dinner_from']) ? $edit_data[0]['dinner_from'] :"";?>" placeholder="">
														</div>
													</div>
												</div>

												<div class="col-md-12">
													<div class="row">
														<label class="col-form-label col-md-3"><span class="text-danger">*</span> To</label>
														<div class="form-group col-md-6">
															<input type="time" name="dinner_to" id="dinner_to" required class="form-control" value="<?php echo isset($edit_data[0]['dinner_to']) ? $edit_data[0]['dinner_to'] :"";?>" placeholder="">
														</div>
													</div>
												</div>
											</div>
										</div>

									</div>
								</section>
							</div>
							<!-- Branch Timings End -->


							<!-- Branch image start -->
							<div class="branch_image mt-3">
								<div class="row">
									<div class="col-md-12 header-filters">
										<a href="javascript:void(0)" class="filter-icons fou_sec_hide" onclick="sectionShow('FOURTH_SECTION','SHOW');">
											<i class="fa fa-chevron-circle-down"></i>
										</a>
										<a href="javascript:void(0)" class="filter-icons fou_sec_show" onclick="sectionShow('FOURTH_SECTION','HIDE');" style="display:none;">
											<i class="fa fa-chevron-circle-right"></i>
										</a>
										<h5 class="pl-1"><b>Branch Image</b></h5>
									</div>
								</div>
								<section class="header-section fou_section">
									<div class="row mt-3">
										<div class="col-md-6">
											<div class="row">
													<?php 
														if($type == "view")
														{
															?>
															<div class="form-group col-md-12">
															<?php 
														}else{
															?>
															<div class="form-group col-md-6">
															<?php
														}
													?>
													<!-- <h5>Branch Image</h5> -->
													<?php 
														if($type != "view")
														{
															?>
															<input type="file" name="branch_image" onchange="return validateSingleFileExtension(this)" class="form-control singleImage">
															<span class="text-muted"><b>Note</b> : Upload size is 1 [MB] and image format is (png,gif,jpg,jpeg and bmp).</span>
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
														} 
													?>
													<?php 
														if($type != "add")
														{
															if (file_exists('uploads/branches/'.$id.'.png'))
															{
																?>
																<img class="pro-page-pic" src="<?php echo base_url()."uploads/branches/".$id.'.png';?>" class="rounded-circle mt-2" style="height: 280px;width: 100%;" alt=""> 
																<?php
															}
															else
															{
																?>
																<img class="pro-page-pic" src="<?php echo base_url()."uploads/no-image.png";?>" class="rounded-circle mt-2" height="75" width="75" alt=""> 
																<?php
															}
														}
													?>
												</div>
											</div>
										</div>
										
										<?php 
											if($type == "view")
											{
												?>
												<div class="col-md-6">
													<div class="row">
														<div class="col-md-12">
															<h5>Location</h5>
															<style>
																#map {
																	height: 280px;
																	width: 100%;
																}
															</style>
															<?php 
																$latitude = isset($edit_data[0]['latitude']) ? $edit_data[0]['latitude'] : NULL;
																$longitude = isset($edit_data[0]['longitude']) ? $edit_data[0]['longitude'] : NULL;
															?>
															<script>
																function initMap() 
																{
																// The location of Uluru
																const uluru = { lat: <?php echo $latitude;?>, lng: <?php echo $longitude;?> };
																// The map, centered at Uluru
																const map = new google.maps.Map(document.getElementById("map"), {
																	zoom: 10,
																	center: uluru,
																});
																// The marker, positioned at Uluru
																const marker = new google.maps.Marker({
																	position: uluru,
																	map: map,
																});
																}
															</script>
															<div id="map" class="mt-2"></div>
															<script src="https://maps.googleapis.com/maps/api/js?key=<?php echo GOOGLE_MAP_API_KEY;?>&callback=initMap&libraries=&v=weekly&channel=2" async></script>
														</div>
													</div>
												</div>
												<?php 
											} 
										?>
									</div>
								</section>
							</div>
							<!-- Branch image end -->

						</fieldset>

						<div class="d-flexad text-right">
							<a href="<?php echo base_url(); ?>branches/ManageBranches" class="btn btn-default btn-sm">Close</a>
							<?php 
								if($type == "view")
								{

								}
								else
								{
									?>
									<button type="submit" class="btn btn-primary btn-sm">Save</button>
									<?php 
								}
								?>
						</div>
					</form>
					<script>
						function sectionShow(section_type,show_hide_type)
						{	
							if(section_type == 'FIRST_SECTION')
							{
								if(show_hide_type == 'SHOW')
								{
									$(".first_sec_hide").hide();
									$(".first_sec_show").show();

									$(".first_section").hide("slow");
								}
								else if(show_hide_type == 'HIDE')
								{
									$(".first_sec_hide").show();
									$(".first_sec_show").hide();

									$(".first_section").show("slow");
								}
							}
							else if(section_type == 'SECOND_SECTION')
							{
								if(show_hide_type == 'SHOW')
								{
									$(".sec_sec_hide").hide();
									$(".sec_sec_show").show();

									$(".sec_section").hide("slow");
								}
								else if(show_hide_type == 'HIDE')
								{
									$(".sec_sec_hide").show();
									$(".sec_sec_show").hide();

									$(".sec_section").show("slow");
								}
							}
							else if(section_type == 'THIRD_SECTION')
							{
								if(show_hide_type == 'SHOW')
								{
									$(".thi_sec_hide").hide();
									$(".thi_sec_show").show();

									$(".thi_section").hide("slow");
								}
								else if(show_hide_type == 'HIDE')
								{
									$(".thi_sec_hide").show();
									$(".thi_sec_show").hide();

									$(".thi_section").show("slow");
								}
							}
							else if(section_type == 'FOURTH_SECTION')
							{
								if(show_hide_type == 'SHOW')
								{
									$(".fou_sec_hide").hide();
									$(".fou_sec_show").show();

									$(".fou_section").hide("slow");
								}
								else if(show_hide_type == 'HIDE')
								{
									$(".fou_sec_hide").show();
									$(".fou_sec_show").hide();

									$(".fou_section").show("slow");
								}
							}
						}
					</script>
					<?php
				}
				else if( $type=="zone" )
				{ 
					$zoneQuery = "select branch_code,branch_name from branch where branch_id ='".$id."' ";
					$getBranchDetails = $this->db->query($zoneQuery)->result_array();
					?>
					<fieldset>
						<legend class="text-uppercase font-size-sm font-weight-bold">
							Branch Zones
						</legend>
					</fieldset>
					
					<div class="row">
						<div class="col-md-2">	
							Branch Code
						</div>	
						<div class="col-md-1">:</div>
						<div class="col-md-8"><?php echo $getBranchDetails[0]['branch_code'];?></div>
					</div>

					<div class="row mt-2">
						<div class="col-md-2">	
							Branch Name
						</div>	
						<div class="col-md-1">:</div>
						<div class="col-md-8"><?php echo ucfirst($getBranchDetails[0]['branch_name']);?></div>
					</div>
					
					<hr>

					<fieldset>
						<legend class="text-uppercase font-size-sm font-weight-bold">
							Add Zone
						</legend>
					</fieldset>
					
					<script>
						function initMap() 
						{
							var input = document.getElementById('autocomplete');
							var autocomplete = new google.maps.places.Autocomplete(input);

							autocomplete.addListener('place_changed', function() 
							{
								var place = autocomplete.getPlace();
								if (!place.geometry) {
									window.alert("Autocomplete's returned place contains no geometry");
									return;
								}

								var address = '';
								if (place.address_components) 
								{
									address = [
										(place.address_components[0] && place.address_components[0].short_name || ''),
										(place.address_components[1] && place.address_components[1].short_name || ''),
										(place.address_components[2] && place.address_components[2].short_name || '')
									].join(' ');
								}
							});
						}
					</script>
					<script async defer src="https://maps.googleapis.com/maps/api/js?key=<?php echo GOOGLE_MAP_API_KEY;?>&libraries=places&callback=initMap"></script>
					
					<form action="" class="form-validate-jquery" enctype="multipart/form-data" method="post">
						<div class="row">
							<div class="form-group col-md-3">
								<label class="col-form-label">Zone Name <span class="text-danger">*</span></label>
								<input type="text" name="zone_name" id="autocomplete" required class="form-control" value="" placeholder="">
							</div>
							
							<div class="col-md-3" style="margin-top:37px;">
								<input type="submit" name="add" class="btn btn-primary" value="Add">
							</div>
						</div>
					</form>
					
					<hr>

					<fieldset class="mt-2">
						<legend class="text-uppercase font-size-sm font-weight-bold">
							Zones
						</legend>
					</fieldset>
					<form action="" method="get">
						
						
						<div class="row">
							<div class="col-md-8">
								<div class="row">
									<div class="col-md-4">	
										<input type="search" autocomplete="off" class="form-control" name="keywords" value="<?php echo !empty($_GET['keywords']) ? $_GET['keywords'] :""; ?>" placeholder="Search...">
										<p class="search-note">Note : Zone Name</p>
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
					<?php /* <script>
						function initMap() 
						{
							var input = document.getElementById('autocomplete');
							var autocomplete = new google.maps.places.Autocomplete(input);

							autocomplete.addListener('place_changed', function() 
							{
								var place = autocomplete.getPlace();
								if (!place.geometry) {
									window.alert("Autocomplete's returned place contains no geometry");
									return;
								}

								var address = '';
								if (place.address_components) 
								{
									address = [
										(place.address_components[0] && place.address_components[0].short_name || ''),
										(place.address_components[1] && place.address_components[1].short_name || ''),
										(place.address_components[2] && place.address_components[2].short_name || '')
									].join(' ');
								}
							});
						}
					</script>
					<script async defer src="https://maps.googleapis.com/maps/api/js?key=<?php echo GOOGLE_MAP_API_KEY;?>&libraries=places&callback=initMap"></script>
					 */	?>
					<form action="" method="post">
						<div class="new-scroller">
							<table id="myTable" class="table table-bordered table-hover --table-striped --dataTable">
								<thead>
									<tr>
										<th class="text-center">Controls</th>
										<th onclick="sortTable(0)">Zone Name</th>
										<!-- <th onclick="sortTable(0)" class="text-center">Contains Location</th> -->
										<th onclick="sortTable(1)" class="text-center">Status</th>
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
												/*
												<td style="text-align:center;"><?php echo $i + $firstItem;?></td> */ ?>
												<td style="width: 8%;" class="text-center">
													<div class="dropdown" style="display: inline-block;padding-right: 10px!important;width:92px;">
														<button type="button" class="btn btn-outline-primary gropdown-toggle waves-effect waves-light" data-toggle="dropdown" aria-expanded="false">
															Action <i class="fa fa-angle-down"></i>
														</button>
														<ul class="dropdown-menu dropdown-menu-right">
														<?php
															/* <li>
																<a title="Delete" href="<?php echo base_url();?>branches/ManageBranches/zonedelete/<?php echo $row['zone_id'];?>" title="Delete" onclick="return confirm('Are you sure you want to delete?')">
																	<i class="fa fa-trash"></i>Delete
																</a>
															</li>
															<li>
																<a title="Edit" href="#" data-toggle="modal" data-target="#exampleModal<?php echo $row['zone_id'];?>">
																	<i class="fa fa-pencil"></i> Edit
																</a>
															</li> */ ?>
															<li>
																<?php 
																	if($row['active_flag'] == $this->active_flag)
																	{
																		?>
																		<a href="<?php echo base_url(); ?>branches/ManageBranches/active_flag/<?php echo $row['zone_id'];?>/N" title="Inactive">
																			<i class="fa fa-ban"></i> Inactive
																		</a>
																		<?php 
																	} 
																	else
																	{  ?>
																		<a href="<?php echo base_url(); ?>branches/ManageBranches/active_flag/<?php echo $row['zone_id'];?>/Y" title="Active">
																			<i class="fa fa-check"></i> Active
																		</a>
																		<?php 
																	} 
																?>
															</li>
															<?php
															/* <li>
																<a title="Delete" href="<?php echo base_url();?>admin/ManageBranches/delete/<?php echo $row['branch_id'];?>" title="Delete" onclick="return confirm('Are you sure you want to delete?')">
																	<i class="fa fa-trash"></i> Delete
																</a> 
															</li> */ ?>
														</ul>
													</div>
													
													<!-- Modal -->
													<div class="modal fade" id="exampleModal<?php echo $row['zone_id'];?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
														<div class="modal-dialog" role="document">
															<div class="modal-content">
																<div class="modal-header" style="background: #022646;color: #fff;">
																	<h5 class="modal-title" id="exampleModalLabel">Edit Zone</h5>
																	<button type="button" class="close" data-dismiss="modal" aria-label="Close">
																		<span aria-hidden="true">&times;</span>
																	</button>
																</div>
																											
																<form action="" method="post">
																	<div class="modal-body">
																		<div class="row">
																			<div class="form-group col-md-8">
																				<label class="col-form-label float-left">Zone Name <span class="text-danger">*</span></label>
																				<input type="text" name="zone_name" id="autocomplete" required class="form-control" value="<?php echo $row['zone_name'];?>" placeholder="">
																				<!-- <input type="hidden" name="zone_id" value="<?php echo $row['zone_id'];?>"/> -->
																			</div>
																		</div>
																	</div>
																	<div class="modal-footer">
																		<button type="button" class="btn btn-light" data-dismiss="modal">Close </button>
																		<button type="submit" name="update" class="btn btn-primary ml-3">Update</button>
																	</div>
																</form>
															</div>
														</div>
													</div>
												</td>

												<td class="tab-medium-width">
													<?php echo ucfirst($row['zone_name']);?>
												</td>
											    
												<?php /*
												<td class="text-center">
													<?php
														$containsLocQry = "select contains_location_id from vb_branch_zones_contains_location 
															where 
																zone_id='".$row['zone_id']."' and 
																	branch_id ='".$row['branch_id']."'";
														$getContainsLocation = $this->db->query($containsLocQry)->result_array();
														$locationCount = count($getContainsLocation);

														if($locationCount > 0){
															$btnClass = "primary";
														}else{
															$btnClass = "default";
														}
													?>	
													<a href="<?php echo base_url();?>branches/ManageBranches/containsLocation/<?php echo $row['zone_id'];?>/<?php echo $row['branch_id'];?>" class="btn btn-<?php echo $btnClass;?> btn-small">
														Contains Locations ( <?php echo $locationCount;?> )
													</a>
												</td>
												*/ ?>

												<td style="width: 10%;" class="text-center">
													<?php 
														if($row['active_flag'] == $this->active_flag)
														{
															?>
															<span class="btn btn-outline-success" title="Active">Active</span>
															<?php 
														} 
														else
														{  
															?>
															<span class="btn btn-outline-warning" title="Inactive">Inactive</span>
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
									<p class="admin-no-data">No data found.</p>
									<?php 
								} 
							?>
						</div>
					</form>
				
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
						<div class="col-md-12">
							<a href="<?php echo base_url();?>/branches/Managebranches" class='btn btn-info' style="float:right;"><i class="icon-arrow-left16"></i> Back</a>
						</div>
					</div>
					<?php 
				}
				else if( $type == "containsLocation" )
				{ 
					$zoneQuery = "select branch_code,branch_name from branch where branch_id ='".$status."' ";
					$getBranchDetails = $this->db->query($zoneQuery)->result_array();

					$zonesQuery = "select zone_name from vb_branch_zones where zone_id ='".$id."' ";
					$getZoneDetails = $this->db->query($zonesQuery)->result_array();
					?>
					<fieldset>
						<legend class="text-uppercase font-size-sm font-weight-bold">
							Branch Zone Details
						</legend>
					</fieldset>
					
					<div class="row">
						<div class="col-md-2">	
							Branch Code
						</div>	
						<div class="col-md-1">:</div>
						<div class="col-md-8"><?php echo isset($getBranchDetails[0]['branch_code']) ? $getBranchDetails[0]['branch_code'] :"";?></div>
					</div>

					<div class="row mt-2">
						<div class="col-md-2">	
							Branch Name
						</div>	
						<div class="col-md-1">:</div>
						<div class="col-md-8"><?php echo isset($getBranchDetails[0]['branch_name']) ? ucfirst($getBranchDetails[0]['branch_name']) :"";?></div>
					</div>

					<div class="row mt-2">
						<div class="col-md-2">	
							Zone Name
						</div>	
						<div class="col-md-1">:</div>
						<div class="col-md-8"><?php echo isset($getZoneDetails[0]["zone_name"]) ? $getZoneDetails[0]["zone_name"] :"";?></div>
					</div>
					
					<hr>

					<fieldset>
						<legend class="text-uppercase font-size-sm font-weight-bold">
							Add Contains Location
						</legend>
					</fieldset>
					
					<form action="" class="form-validate-jquery" enctype="multipart/form-data" method="post">
						<div class="row">
							<div class="form-group col-md-3">
								<label class="col-form-label">Latitude <span class="text-danger">*</span></label>
								<input type="text" name="latitude" id="latitude" required class="form-control" value="" placeholder="">
							</div>

							<div class="form-group col-md-3">
								<label class="col-form-label">Longitude <span class="text-danger">*</span></label>
								<input type="text" name="longitude" id="longitude" required class="form-control" value="" placeholder="">
							</div>
							
							<div class="col-md-3" style="margin-top:37px;">
								<input type="submit" name="add" class="btn btn-primary" value="Add">
							</div>
						</div>
					</form>
					
					<hr>

					<fieldset class="mt-2">
						<legend class="text-uppercase font-size-sm font-weight-bold">
							Manage Contains Locations
						</legend>
					</fieldset>
					
					<div class="row">
						
						<div class="col-md-8">
							<form action="" method="get">
								<div class="row">
									<div class="col-md-4">	
										<input type="search" autocomplete="off" class="form-control" name="keywords" value="<?php echo !empty($_GET['keywords']) ? $_GET['keywords'] :""; ?>" placeholder="Search...">
										<!-- <p class="search-note">Note : Ingredient Name, Description</p> -->
									</div>	
									
									<div class="col-md-3">
										<button type="submit" class="btn btn-info waves-effect">Search <i class="fa fa-search" aria-hidden="true"></i></button>
									</div>
								</div>
							</form>
						</div>
						
						<div class="col-md-2 text-right">
							<a href="#" data-toggle="modal" data-target="#ImportLocations" title="Import Contains Locations" class="btn btn-warning mt-2" style="margin:0px 0px 0px 0px;">
								<i class="fa fa-upload" aria-hidden="true"></i> Import Locations
							</a>
						</div>

						<!-- Import csv start -->
						<div class="modal fade" id="ImportLocations" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
							<div class="modal-dialog" role="document">
								<div class="modal-content">
									<div class="modal-header" --style="background: #1a4363;color: #fff;">
										<h5 class="modal-title" id="exampleModalLabel">Import Contains Locations</h5>
										<button type="button" class="close" data-dismiss="modal" aria-label="Close">
											<span aria-hidden="true">&times;</span>
										</button>
									</div>
									<form id="formValidation" action="<?php echo base_url(); ?>branches/ManageBranches/importContainsLocation/<?php echo $id; ?>/<?php echo $status; ?>" enctype="multipart/form-data" method="post">
										<div class="modal-body">
											<div class="row">
												<div class="col-md-3">	
													Branch Code
												</div>	
												<div class="col-md-1">:</div>
												<div class="col-md-8"><?php echo isset($getBranchDetails[0]['branch_code']) ? $getBranchDetails[0]['branch_code'] :"";?></div>
											</div>

											<div class="row mt-2">
												<div class="col-md-3">	
													Branch Name
												</div>	
												<div class="col-md-1">:</div>
												<div class="col-md-8"><?php echo isset($getBranchDetails[0]['branch_name']) ? ucfirst($getBranchDetails[0]['branch_name']) :"";?></div>
											</div>

											<div class="row mt-2">
												<div class="col-md-3">	
													Zone Name
												</div>	
												<div class="col-md-1">:</div>
												<div class="col-md-8"><?php echo isset($getZoneDetails[0]["zone_name"]) ? $getZoneDetails[0]["zone_name"] :"";?></div>
											</div>

											<div class="row mt-2">
												<div class="col-md-12">
													<div class="well well-small">
														<span style="color:red; font-size:14px;">Note : The first line in downloaded csv file should remain as it is. Please do not change the order of columns and Update valid data to CSV..</span>
														<p class="mt-2">The correct column order is <span class="text-info">(Latitude, Longitude.)</span> &amp; You must follow this.</p>
													</div>
												</div>
											</div>
											
											<div class="row">
												<div class="form-group col-md-10">
													<label class="col-form-label">Upload File <span class="text-danger">*</span></label>
													<input type="file" name="csv"  id="chooseFile" class="form-control singleDocument" onchange="return validateSingleDocumentExtension(this)" required />
													<span style="color:#737373;">
														Note : Upload format CSV and upload size is 5 mb.
														<a href="<?php echo base_url(); ?>assets/upload_contains_location.csv" title="Download Sample File">
															<i class="fa fa-download"></i> Download Sample
														</a>
													</span>
												</div>
											</div>
										
											<script>
												/** Single Document Type & Size Validation **/
												function validateSingleDocumentExtension(fld) 
												{
													var fileUpload = fld;
													
													if (typeof (fileUpload.files) != "undefined")
													{
														var size = parseFloat( fileUpload.files[0].size / 1024 ).toFixed(2);
														var validSize = 1024 * 5; //1024 - 1Mb multiply 4mb
														
														//var validSize = 500; 
														
														if( size > validSize )
														{
															//alert("Document upload size is 4 MB");
															alert("File size should not exceed 5 MB.");
															$('.singleDocument').val('');
															var value = 1;
															return false;
														}
														else if(!/(\.csv)$/i.test(fld.value))
														//else if(!/(\.pdf)$/i.test(fld.value))
														{
															alert("Invalid document file type.");      
															$('.singleDocument').val('');
															return false;   
														}
														
														if(value != 1)	
															return true; 
													}
												}
											</script>
										</div>
										<div class="modal-footer">
											<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
											<button type="submit" class="btn btn-primary">Import</button>
										</div>
									</form>
								</div>
							</div>
						</div>
						<!-- Import csv end -->

						<div class="col-md-2 text-right">
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
							<table id="myTable" class="table table-bordered table-hover --table-striped --dataTable">
								<thead>
									<tr>
										<th class="text-center">Controls</th>
										<th onclick="sortTable(0)" class="text-center">Latitude</th>
										<th onclick="sortTable(0)" class="text-center">Longitude</th>
										<th onclick="sortTable(1)" class="text-center">Status</th>
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
												/*
												<td style="text-align:center;"><?php echo $i + $firstItem;?></td> */ ?>
												<td style="width: 8%;" class="text-center">
													<div class="dropdown" style="display: inline-block;padding-right: 10px!important;width:92px;">
														<button type="button" class="btn btn-outline-primary gropdown-toggle waves-effect waves-light" data-toggle="dropdown" aria-expanded="false">
															Action <i class="fa fa-angle-down"></i>
														</button>
														<ul class="dropdown-menu dropdown-menu-right">
															<?php /* <li>
																<a title="Delete" href="<?php echo base_url();?>branches/ManageBranches/zoneDeleteContinsLocation/<?php echo $row['zone_id'];?>/<?php echo $row['branch_id'];?>/<?php echo $row['contains_location_id'];?>" title="Delete" onclick="return confirm('Are you sure you want to delete?')">
																	<i class="fa fa-trash"></i>Delete
																</a>
															</li> */ ?>

															<li>
																<a title="Edit" href="#" data-toggle="modal" data-target="#exampleModal<?php echo $row['contains_location_id'];?>">
																	<i class="fa fa-pencil"></i> Edit
																</a>
															</li>

															<li>
																<?php 
																	if($row['contains_location_status'] == 1)
																	{
																		?>
																		<a href="<?php echo base_url(); ?>branches/ManageBranches/zone_status_ContinsLocation/<?php echo $row['zone_id'];?>/<?php echo $row['branch_id'];?>/<?php echo $row['contains_location_id'];?>/0" title="Block">
																			<i class="fa fa-ban"></i> Inactive
																		</a>
																		<?php 
																	} 
																	else
																	{  ?>
																		<a href="<?php echo base_url(); ?>branches/ManageBranches/zone_status_ContinsLocation/<?php echo $row['zone_id'];?>/<?php echo $row['branch_id'];?>/<?php echo $row['contains_location_id'];?>/1" title="Unblock">
																			<i class="fa fa-check"></i> Active
																		</a>
																		<?php 
																	} 
																?>
															</li>
														</ul>
													</div>

													<!-- Modal -->
													<div class="modal fade" id="exampleModal<?php echo $row['contains_location_id'];?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
														<div class="modal-dialog" role="document">
															<div class="modal-content">
																<div class="modal-header" --style="background: #022646;color: #fff;">
																	<h5 class="modal-title" id="exampleModalLabel">EDIT CONTAINS LOCATIONS</h5>
																	<button type="button" class="close" data-dismiss="modal" aria-label="Close">
																		<span aria-hidden="true">&times;</span>
																	</button>
																</div>
																											
																<form action="" method="post">
																	<div class="modal-body">
																		<div class="row">
																			<input type="hidden" name="contains_location_id" value="<?php echo $row['contains_location_id'];?>"/>
																			<input type="hidden" name="zone_id" value="<?php echo $row['zone_id'];?>"/>
																			<input type="hidden" name="branch_id" value="<?php echo $row['branch_id'];?>"/>
																			<div class="form-group col-md-6">
																				<label class="col-form-label float-left">Latitude <span class="text-danger">*</span></label>
																				<input type="text" name="latitude" id="latitude" required class="form-control" value="<?php echo $row['latitude'];?>" placeholder="">
																			</div>
																			<div class="form-group col-md-6">
																				<label class="col-form-label float-left">Longitude <span class="text-danger">*</span></label>
																				<input type="text" name="longitude" id="longitude" required class="form-control" value="<?php echo $row['longitude'];?>" placeholder="">
																			</div>
																		</div>
																	</div>
																	<div class="modal-footer">
																		<button type="button" class="btn btn-light" data-dismiss="modal">Close</button>
																		<button type="submit" name="update" class="btn btn-primary">Update</button>
																	</div>
																</form>
															</div>
														</div>
													</div>
												</td>

												<td class="tab-medium-width text-center">
													<?php echo ucfirst($row['latitude']);?>
												</td>

												<td class="tab-medium-width text-center">
													<?php echo ucfirst($row['longitude']);?>
												</td>
												
												<td style="width: 10%;" class="text-center">
													<?php 
														if($row['contains_location_status'] == 1)
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
									<p class="admin-no-data">No data found.</p>
									<?php 
								} 
							?>
						</div>
					</form>
				
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
						<div class="col-md-12">
							<a href="<?php echo base_url();?>branches/ManageBranches/zone/<?php echo $status;?>" class='btn btn-info' style="float:right;"><i class="icon-arrow-left16"></i> Back</a>
						</div>
					</div>
					<?php 
				}
				else
				{ 
					?>
					<div class="row mb-2">
						<div class="col-md-6"><h3><b>Branches</b></h3></div>

						<div class="col-md-6 float-right text-right">
							<a href="<?php echo base_url();?>setup/settings" class="btn btn-info btn-sm">
								<i class="icon-arrow-left16"></i> 
								Back
							</a>
							<a href="<?php echo base_url(); ?>branches/ManageBranches/add" class="btn btn-info btn-sm">
								Create Branch
							</a>
						</div>
					</div>

					<form action="" method="get">
						<div class="row">
							<div class="col-md-12">
								<div class="row">
									<div class="col-md-3">
										<div class="row">
											<label class="col-form-label col-md-4 text-right">Keywords</label>
											<div class="form-group col-md-8">
												<input type="search" autocomplete="off" class="form-control" name="keywords" value="<?php echo !empty($_GET['keywords']) ? $_GET['keywords'] :""; ?>" placeholder="Search...">
												<span class="text-danger">Note : Branch Code / Name</span>
											</div>
										</div>
									</div> 

									<div class="col-md-3">
										<div class="row">
											<label class="col-form-label col-md-4 text-right">Status</label>
											<div class="form-group col-md-8">
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

									<div class="col-md-3">
										<button type="submit" class="btn btn-info waves-effect">Search <i class="fa fa-search" aria-hidden="true"></i></button>
										<a href="<?php echo base_url(); ?>branches/Managebranches" title="Clear" class="btn btn-default">Clear</a>
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
							<form action="" method="post">
								<div class="new-scroller">
									<table id="myTable" class="table table-bordered table-hover --table-striped --dataTable">
										<thead>
											<tr>
												<th class="text-center">Controls</th>
												<th>Branch Code</th>
												<th>Branch Name</th>
												<th>Mobile Number</th>
												<th class="text-right">Min Order Value <span class="text-muted" style="font-size:8px;">(<?php echo CURRENCY_CODE;?>)<span></th>
												<th>Location</th>
												<th class="text-center">Status</th>
												<th class="text-center">Default</th>
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
														<td style="width: 12%;" class="text-center">
															<div class="dropdown" style="display: inline-block;padding-right: 10px!important;width:92px;">
																<button type="button" class="btn btn-outline-primary btn-sm gropdown-toggle waves-effect waves-light" data-toggle="dropdown" aria-expanded="false">
																	Action <i class="fa fa-angle-down"></i>
																</button>
																<ul class="dropdown-menu dropdown-menu-right">
																	<li>
																		<a title="Edit" href="<?php echo base_url(); ?>branches/ManageBranches/edit/<?php echo $row['branch_id'];?>">
																			<i class="fa fa-pencil"></i> Edit
																		</a>
																	</li>

																	<li>
																		<a title="View" href="<?php echo base_url(); ?>branches/ManageBranches/view/<?php echo $row['branch_id'];?>">
																			<i class="fa fa-eye"></i> View
																		</a>
																	</li>

																	<li>
																		<?php 
																			if($row['active_flag'] == $this->active_flag)
																			{
																				?>
																				<a href="<?php echo base_url(); ?>branches/ManageBranches/status/<?php echo $row['branch_id'];?>/N" title="Block">
																					<i class="fa fa-ban"></i> Inactive
																				</a>
																				<?php 
																			} 
																			else
																			{  ?>
																				<a href="<?php echo base_url(); ?>branches/ManageBranches/status/<?php echo $row['branch_id'];?>/Y" title="Unblock">
																					<i class="fa fa-check"></i> Active
																				</a>
																				<?php 
																			} 
																		?>
																	</li>
																</ul>
															</div>
														</td>
														
														<td><?php echo $row['branch_code'];?></td>
														<td><?php echo ucfirst($row['branch_name']);?></td>
														<td><?php echo ucfirst($row['mobile_number']);?></td>
														<td class="text-right">
															<?php echo number_format($row['minimum_order_value'],DECIMAL_VALUE,'.','');?>
														</td>

														<td><?php echo ucfirst($row['location_name']);?></td>
						
														<td class="tab-medium-width text-center">
															<?php 
																if($row['active_flag'] == $this->active_flag)
																{
																	?>
																	<span class="btn btn-outline-success" title="Active">Active</span>
																	<?php 
																} 
																else
																{  
																	?>
																	<span class="btn btn-outline-warning" title="Inactive">Inactive</span>
																	<?php 
																} 
															?>
														</td>
														<td class="text-center">
														<?php 
															if($row['active_flag'] == 'Y')
															{
																?>
																<input type="radio" name="default_branch" <?php if($row['default_branch'] == 'Y'){?>checked<?php }?> value="<?php echo $row['branch_id']; ?>"/>
																<?php 
															} 
														?>
														</td>
													</tr>
													<?php 
													$i++;
												}
											?>
											<?php 
												if(count($resultData) > 0)
												{
													?>
													<tr>
														<td colspan="7"></td>
														<td class="text-center">
															<button type="submit" name="default_submit" class="btn btn-outline-primary btn-sm updates">Update</button>
														</td>
													</tr>
													<?php 
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
		</div><!-- Card end-->
	</div><!-- Card body end-->
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
</script>

<style>


.hero_in.detail_page {
  width: 100%;
  height: 230px;
  position: relative;
  overflow: hidden;
  color: #fff;
  text-align: left;
  background-position: center center;
  background-repeat: no-repeat;
  background-color: #ededed;
  -webkit-background-size: cover;
  -moz-background-size: cover;
  -o-background-size: cover;
  background-size: cover;
}
@media (max-width: 767px) {
  .hero_in.detail_page {
    height: 190px;
  }
}

.branch_cover_img{
	opacity: 0.8;
	border-radius:12px;
	border-color:white;
}
.text-danger-small{
	font-size:12px;
	color:#838383;
}
</style>