
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
					<form action="" class="form-validate-jquery" enctype="multipart/form-data" method="post">
						<fieldset class="mb-3" <?php echo $fieldSetDisabled;?>>
							<legend class="text-uppercase font-size-sm font-weight-bold"><?php echo $type;?> Location</legend>
							
							<div class="row">
								<?php /*
								<div class="form-group col-md-3">
									<label class="col-form-label">Location Code <span class="text-danger">*</span></label>
									<input type="text" name="location_code" id="location_code" autocomplete="off" <?php #echo $this->validation;?> required class="form-control" value="<?php echo isset($edit_data[0]['location_code']) ? $edit_data[0]['location_code'] : NULL;?>" placeholder="">
								</div> */ ?>
								<div class="form-group col-md-3">
									<label class="col-form-label">Location Name <span class="text-danger">*</span></label>
									<input type="text" name="location_name" id="organization_name" autocomplete="off" <?php echo $this->validation;?> required class="form-control" value="<?php echo isset($edit_data[0]['location_name']) ? $edit_data[0]['location_name'] : NULL;?>" placeholder="">
								</div>	
							
								<div class="form-group col-md-2">
									<label class="col-form-label">Start Date </label>
									<?php 
										if(isset($edit_data[0]['start_date']) && !empty($edit_data[0]['start_date'])){
											$start_date = date(DATE_FORMAT,strtotime($edit_data[0]['start_date']));
										}else{$start_date = NULL;}
									?>
									<input type="text" name="start_date" id="start_date_1" readonly class="form-control start_date" value="<?php echo $start_date;?>" placeholder="">
								</div>

								<div class="form-group col-md-2">
									<label class="col-form-label">End Date</label>
									<?php 
										if(isset($edit_data[0]['end_date']) && !empty($edit_data[0]['end_date'])){
											$end_date = date(DATE_FORMAT,strtotime($edit_data[0]['end_date']));
										}else{$end_date = NULL;}
									?>
									<input type="text" name="end_date" id="end_date_1" readonly class="form-control end_date" value="<?php echo $end_date;?>" placeholder="">
								</div>			
							</div>

							<legend><h5>Address</h5></legend>
							<div class="form-group row">
								<label class="col-form-label col-md-2">Country <span class="text-danger">*</span></label>
								<div class="col-md-2">
									<select name="country_id" id="country_id" onchange="selectState(this.value,'company');" required class="form-control <?php echo $searchDropdown;?>">
										<option value="">- Select Country -</option>
										<?php 
											$getCountry = $this->db->query("select country_id,country_name from geo_countries where active_flag='Y' order by country_name asc")->result_array();
					
											foreach($getCountry as $row)
											{
												$selected="";
												if(isset($edit_data[0]['country_id']) && $edit_data[0]['country_id']== $row['country_id'])
												{
													$selected="selected";
												}
												?>
												<option value="<?php echo $row['country_id']; ?>" <?php echo $selected; ?>><?php echo ucfirst($row['country_name']);?></option>
												<?php 
											} 
										?>
									</select>
								</div>
							</div>
							
							<div class="form-group row">
								<label class="col-form-label col-md-2">State <span class="text-danger">*</span></label>
								<div class="col-md-2">
									<select name="state_id" id="state_id" onchange="selectCity(this.value,'company');" required class="form-control <?php echo $searchDropdown;?>">
										<option value="">- Select State -</option>
										<?php 
											if($edit_data[0]['state_id'] !=0 && $edit_data[0]['state_id'] !="")
											{
												$state = $this->db->query("select geo_states.state_id,geo_states.state_name from geo_states
														where geo_states.active_flag='Y' and geo_states.country_id='".$edit_data[0]['country_id']."'")->result_array();
														
												foreach($state as $row)
												{
													$selected='';
													if($edit_data[0]['state_id'] == $row['state_id'])
													{
														$selected='selected="selected"';
													}
													?>
													<option value="<?php echo $row['state_id'];?>" <?php echo $selected;?>><?php echo ucfirst($row['state_name']);?></option>
													<?php 
												} 
											} 
										?>
									</select>
								</div>
							</div>

							<div class="form-group row">
								<label class="col-form-label col-md-2">City <span class="text-danger">*</span></label>
								<div class="col-md-2">
									<select name="city_id" id="city_id" required class="form-control <?php echo $searchDropdown;?>">
										<option value="">- Select City -</option>
										<?php 
											if($edit_data[0]['city_id'] !=0 && $edit_data[0]['city_id'] !="")
											{
												$city= $this->db->query("select geo_cities.city_id,geo_cities.city_name from geo_cities
														where geo_cities.active_flag='Y' and geo_cities.state_id='".$edit_data[0]['state_id']."'")->result_array();
														
												foreach($city as $row)
												{
													$selected='';
													if($edit_data[0]['city_id'] == $row['city_id'])
													{
														$selected='selected="selected"';
													}
													?>
													<option value="<?php echo $row['city_id'];?>" <?php echo $selected;?>><?php echo ucfirst($row['city_name']);?></option>
													<?php 
												} 
											} 
										?>
									</select>
								</div>
							</div>

							<div class="form-group row">
								<label class="col-form-label col-md-2">Address 1 <span class="text-danger">*</span></label>
								<div class="form-group col-md-3">
									<textarea name="address1" id="address1" rows="1" required autocomplete="off" class="form-control" placeholder=""><?php echo isset($edit_data[0]['address1']) ? $edit_data[0]['address1'] : NULL;?></textarea>
								</div>
							</div>

							<div class="form-group row">
								<label class="col-form-label col-md-2">Address 2</label>
								<div class="form-group col-md-3">
									<textarea name="address2" id="address2" rows="1" autocomplete="off" class="form-control" placeholder=""><?php echo isset($edit_data[0]['address2']) ? $edit_data[0]['address2'] : NULL;?></textarea>
								</div>
							</div>

							<div class="form-group row">
								<label class="col-form-label col-md-2">Address 3</label>
								<div class="form-group col-md-3">
									<textarea name="address3" id="address3" rows="1" autocomplete="off" class="form-control" placeholder=""><?php echo isset($edit_data[0]['address3']) ? $edit_data[0]['address3'] : NULL;?></textarea>
								</div>
							</div>

							<div class="form-group row">
								<label class="col-form-label col-md-2">Postal Code <span class="text-danger">*</span></label>
								<div class="form-group col-md-2">
									<input type="text" maxlength="6" name="postal_code" autocomplete="off" id="postal_code" <?php #echo $this->validation;?> required class="form-control mobile_vali" value="<?php echo isset($edit_data[0]['postal_code']) ? $edit_data[0]['postal_code'] : NULL;?>" placeholder="">
								</div>
							</div>
						</fieldset>
						
						<div class="row">
							<div class="col-md-4"></div>
							<div class="col-md-8 text-right">
								<a href="<?php echo base_url(); ?>locations/manageLocations" class="btn btn-default">Close</a>
										
								<?php 
									if($type == "view")
									{

									}
									else
									{
										if($type == "edit")
										{
											?>
											<button type="submit" class="btn btn-primary ml-1">Save</button>
											<?php 
										}
										else
										{
											?>
											<button type="submit" class="btn btn-primary ml-1">Save</button>
											<?php 
										}
									}
								?>
							</div>
						</div>
					</form>
					<?php
				}
				else
				{ 
					?>
					<style>
						.dropdown-menu.dropdown-menu.show{
							position: absolute;
							/* transform: translate3d(9px, -50px, -1px) !important;
							top: 0px;
							left: 0px;
							will-change: transform; */
						}
					</style>
					<div class="row mb-2">
						<div class="col-md-6"><?php echo $page_title;?></div>
						<div class="col-md-6 float-right text-right">
							<!-- <a href="#" data-toggle="modal" data-target="#importcountryCSV" title="Import" class="btn btn-outline-warning btn-sm">
								<i class="icon-import"></i> Import
							</a> -->
							<a href="<?php echo base_url();?>setup/settings" class="btn btn-info btn-sm">
								<i class="icon-arrow-left16"></i> 
								Back
							</a>
							<a href="<?php echo base_url(); ?>locations/manageLocations/add" class="btn btn-info btn-sm">
								Create Location
							</a>
						</div>
					</div>


					<form action="" method="get">
						<section class="trans-section-back-1">
							<div class="row">
								<div class="col-md-10">
									<div class="row mt-1">
										<div class="col-md-3">	
											<input type="search" class="form-control" name="keywords" value="<?php echo !empty($_GET['keywords']) ? $_GET['keywords'] :""; ?>" placeholder="Search..." autocomplete="off">
											<!--<span class="text-muted"style="font-size:10.5px;">Note : Location Name.</span>-->
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
				
														sm_list_types.list_name = 'ACTIVESTATUS'
														order by sm_list_type_values.order_sequence asc
														";
				
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
										<div class="col-md-2">
											<button type="submit" class="btn btn-info">Search <i class="fa fa-search" aria-hidden="true"></i></button>
											<a href="<?php echo base_url(); ?>locations/manageLocations" title="Clear" class="btn btn-default">Clear</a>
										</div>
									</div>
								</div>
								
							</div>
						</section>
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
									
								<div class="new-scroller">
									<table --id="myTable" class="table table-bordered -sortable-table table-hover --table-striped --dataTable">
										<thead>
											<tr>
												<th class="text-center">Controls</th>
												<th>Location Name</th>
												<th>Address 1</th>
												<th class="text-center">Status</th>
											</tr>
										</thead>
										<tbody>
											<?php 	
												$i=0;
												//$firstItem = $first_item;
												foreach($resultData as $row)
												{
													?>
													<tr>
														<td class="text-center">
															<div class="dropdown text-center">
																<button type="button" class="btn btn-outline-info gropdown-toggle btn-sm" data-toggle="dropdown" aria-expanded="false">
																	Action&nbsp;<i class="fa fa-chevron-down"></i>
																</button>
																<ul class="dropdown-menu dropdown-menu-right">
																	<li>
																		<a title="View" href="<?php echo base_url(); ?>locations/manageLocations/view/<?php echo $row['location_id'];?>">
																			<i class="fa fa-eye"></i> View
																		</a>
																	</li>	
																	<li>
																		<a href="<?php echo base_url(); ?>locations/manageLocations/edit/<?php echo $row['location_id'];?>">
																			<i class="fa fa-edit"></i> Edit
																		</a>
																	</li>
																	
																	<li>
																		<?php 
																			if($row['active_flag'] == $this->active_flag)
																			{
																				?>
																				<a class="unblock" href="<?php echo base_url(); ?>locations/manageLocations/status/<?php echo $row['location_id'];?>/N" title="Active">
																					<i class="fa fa-ban"></i> Inactive
																				</a>
																				<?php 
																			} 
																			else
																			{  ?>
																				<a class="block" href="<?php echo base_url(); ?>locations/manageLocations/status/<?php echo $row['location_id'];?>/Y" title="InActive">
																					<i class="fa fa-ban"></i> Active
																				</a>
																				<?php 
																			} 
																		?>	
																	<li>
																</ul>
															</div>
														</td>
														
														<td class="tab-full-width"><?php echo ucfirst($row['location_name']);?></td>
														<td class="tab-mobile-width "><?php echo $row['address1'];?></td> 
														<td class="tab-mobile-width" style="text-align:center;">
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

							<?php 
						} 
					?>


					<?php 
				} 
			?>
		</div>
	</div><!-- Card end-->
	<?php /* if(isset($type) && $type =='view'){?>
		<a href='<?php echo $_SERVER['HTTP_REFERER'];?>' class='btn btn-info' style="float:right;"><i class="icon-arrow-left16"></i> Back</a>
	<?php } */ ?>
</div><!-- Content end-->

<script type="text/javascript">  
	function selectState(val,type)
	{
		if(val !='')
		{
			$.ajax({
				type: "POST",
				url:"<?php echo base_url().'admin/ajaxselectState';?>",
				data: { id: val }
			}).done(function( msg ) 
			{   
				if(type == 'company')
				{
					$( "#state_id").html(msg);
				
					if(msg=='<option value="">No states under this country!</option>'){
						$( "#state_id").html('<option value="">No states under this country!</option>');
					}/* else{
						$( "#state_id").html('<option value="">First Select Country</option>');
					} */
					
					if(msg=='<option value="">No states under this country!</option>'){
						$( "#city_id").html('<option value="">No cities under this state!</option>');
					}else{
						$( "#city_id").html('<option value="">First Select State</option>');
					}
				}
				else if(type == 'billing')
				{
					$( "#billing_state_id").html(msg);
				
					if(msg=='<option value="">No states under this country!</option>'){
						$( "#billing_state_id").html('<option value="">No states under this country!</option>');
					}/* else{
						$( "#state_id").html('<option value="">First Select Country</option>');
					} */
					
					if(msg=='<option value="">No states under this country!</option>'){
						$( "#billing_city_id").html('<option value="">No cities under this state!</option>');
					}else{
						$( "#billing_city_id").html('<option value="">First Select State</option>');
					}
				}
				else if(type == 'shipping')
				{
					$( "#shipping_state_id").html(msg);
				
					if(msg=='<option value="">No states under this country!</option>'){
						$( "#shipping_state_id").html('<option value="">No states under this country!</option>');
					}/* else{
						$( "#state_id").html('<option value="">First Select Country</option>');
					} */
					
					if(msg=='<option value="">No states under this country!</option>'){
						$( "#shipping_city_id").html('<option value="">No cities under this state!</option>');
					}else{
						$( "#shipping_city_id").html('<option value="">First Select State</option>');
					}
				}
			});
		}
		else 
		{ 
			alert("No states under this country!");
		}
	}
	
	function selectCity(val,type)
	{
		if(val !='')
		{
			$.ajax({
				type: "POST",
				url:"<?php echo base_url().'admin/ajaxSelectCity';?>",
				data: { id: val }
			}).done(function( msg ) 
			{   
				if(type == 'company')
				{
					$( "#city_id").html(msg);
				}
				else if(type == 'billing')
				{
					$( "#billing_city_id").html(msg);
				}
				else if(type == 'shipping')
				{
					$( "#shipping_city_id").html(msg);
				}
			});
		}
		else 
		{ 
			alert("No cities under this state!");
		}
	}
</script>