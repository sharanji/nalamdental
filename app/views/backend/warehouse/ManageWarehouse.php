<style>
	.dropdown-menu.dropdown-menu.show{
		position: absolute;
		/* transform: translate3d(9px, -50px, -1px) !important;
		top: 0px;
		left: 0px;
		will-change: transform; */
	}
</style>
<div class="content">
	<div class="card">
		<div class="card-body">
			<?php
				if(isset($type) && $type == "add" || $type == "edit" || $type == "view")
				{
					$getCountry = $this->db->query("select country_id,country_name from geo_countries where active_flag='Y' order by country_name asc")->result_array();
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
						<div class="row">
							<div class="col-sm-12 col-md-12">
								<fieldset <?php echo $fieldSetDisabled;?> class="mb-3 fieldset-class company_details --new-design-1">
									<legend class="text-uppercase font-size-sm font-weight-bold">Warehouse</legend>
									<div class="row">
										<div class="form-group col-md-3">
											<label class="col-form-label">Warehouse Code <span class="text-danger">*</span></label>
											<input type="text" name="warehouse_code"<?php echo $this->validation;?> required id="warehouse_code" <?php echo $this->validation; ?> class="form-control" value="<?php echo isset($edit_data[0]['warehouse_code']) ? $edit_data[0]['warehouse_code'] :"";?>" placeholder="">
										</div>
										
										<div class="form-group col-md-3">
											<label class="col-form-label">Warehouse Name <span class="text-danger">*</span></label>
											<input type="text" name="warehouse_name"<?php echo $this->validation;?> required id="warehouse_id" <?php echo $this->validation; ?> class="form-control" value="<?php echo isset($edit_data[0]['warehouse_name']) ? $edit_data[0]['warehouse_name'] :"";?>" placeholder="">
										</div>
										
										<?php
											$getBranch = $this->db->query("select branch_id,branch_name from branch where active_flag='Y' order by branch_name asc")->result_array();
										?>
										<div class="form-group col-md-3">
											<label class="col-form-label">Branch <span class="text-danger">*</span></label>
											<select name="branch_id" id="branch_id" required class="form-control <?php echo $searchDropdown;?>">
												<option value="">- Select Branch -</option>
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
									
									<div class="row">
										<div class="form-group col-md-3">
											<label class="col-form-label">Mobile Number <span class="text-danger">*</span></label>
											<input type="text" name="mobile_number" id="mobile_number" required oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" minlength="10" maxlength='10' class="form-control mobile_vali" value="<?php echo isset($edit_data[0]['mobile_number']) ? $edit_data[0]['mobile_number'] :"";?>" placeholder="Ex.9632587410">
											<span class="mobile_number_exist"></span>
										</div>
										
										<div class="form-group col-md-3">
											<label class="col-form-label">Email </label>
											<input type="email" name="email" class="form-control" value="<?php echo isset($edit_data[0]['email']) ? $edit_data[0]['email'] :"";?>" placeholder="">
										</div>
									</div>

									<span class="text-uppercase font-size-sm font-weight-bold">Address</span>
									
									<div class="form-group row pt-2">
										<label class="col-form-label col-md-2">Country <span class="text-danger">*</span></label>
										<div class="col-md-3">
											<select name="country_id" id="country_id" onchange="selectState(this.value,'company');" required class="form-control <?php echo $searchDropdown;?>">
												<option value="">- Select -</option>
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
										<div class="col-md-3">
											<select name="state_id" id="state_id" onchange="selectCity(this.value,'company');" required class="form-control <?php echo $searchDropdown;?>">
												<option value="">- Select -</option>
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
										<div class="col-md-3">
											<select name="city_id" id="city_id" required class="form-control <?php echo $searchDropdown;?>">
												<option value="">- Select -</option>
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
										<label class="col-form-label col-md-2"> Address Line 1 <span class="text-danger">*</span></label>
										<div class="col-md-3">
											<textarea name="address_1" rows="1" <?php echo $this->validation;?>class="form-control" required placeholder=""><?php echo isset($edit_data[0]['address_1']) ? $edit_data[0]['address_1'] :"";?></textarea>
										</div>
									</div>

									<div class="form-group row">
										<label class="col-form-label col-md-2"> Address Line 2 </label>
										<div class="col-md-3">
											<textarea name="address_2"  rows="1" <?php echo $this->validation;?>class="form-control" placeholder=""><?php echo isset($edit_data[0]['address_2']) ? $edit_data[0]['address_2'] :"";?></textarea>
										</div>
									</div>

									<div class="form-group row">
										<label class="col-form-label col-md-2"> Address Line 3 </label>
										<div class="col-md-3">
											<textarea name="address_3" <?php echo $this->validation;?> rows="1" class="form-control" placeholder=""><?php echo isset($edit_data[0]['address_3']) ? $edit_data[0]['address_3'] :"";?></textarea>
										</div>
									</div>

									<div class="form-group row">
										<label class="col-form-label col-md-2">Postal Code <span class="text-danger">*</span></label>
										<div class="col-md-3">
											<input type="text" name="postal_code"<?php echo $this->validation;?> id="postal_code" required oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" minlength="3" maxlength='6' class="form-control" value="<?php echo isset($edit_data[0]['postal_code']) ? $edit_data[0]['postal_code'] :"";?>" placeholder="">
										</div>
									</div>
								</fieldset>
							</div>
						</div>

						<script>
							var $field1 = $("#mobile_number");
							var $field2 = $("#mobile_number_1");

							$field1.on("keydown",function()
							{
								setTimeout(checkValue,0); 
							});

							var v2 = $field2.val();
							var checkValue = function(){
								var v1 = $field1.val();
								if (v1 != v2){
									$field2.val(v1);
									v2 = v1;
								}
							};
						</script>
						<br>
						<div class="d-flexad" style="text-align:right;">
							<a href="<?php echo base_url(); ?>warehouse/ManageWarehouse" class="btn btn-default">Close</a>
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
					</form>
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
					<?php
				}
				else
				{ 
					?>
					<div class="row mb-2">
						<div class="col-md-6"><?php echo $page_title;?></div>
						<div class="col-md-6 float-right text-right">
							<!-- <a href="<?php echo base_url(); ?>admin/settings" class="btn btn-info btn-sm">
								<i class="icon-arrow-left16"></i> Back
							</a> -->
							<a href="<?php echo base_url();?>setup/settings" class="btn btn-info btn-sm">
								<i class="icon-arrow-left16"></i> 
								Back
							</a>
							<a href="<?php echo base_url(); ?>warehouse/ManageWarehouse/add" class="btn btn-info btn-sm">
								Create Warehouse
							</a>
						</div>
					</div>
					
					<form action="" method="get">
						<div class="row">
							<div class="col-md-8">
								<section class="trans-section-back-1">
									<div class="row">
										<div class="col-md-4">	
											<input type="search" autocomplete="off" class="form-control" name="keywords" value="<?php echo !empty($_GET['keywords']) ? $_GET['keywords'] :""; ?>" placeholder="Search...">
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
											<button type="submit" class="btn btn-info">Search <i class="fa fa-search" aria-hidden="true"></i></button>
											<a href="<?php echo base_url(); ?>warehouse/ManageWarehouse" title="Clear" class="btn btn-default">Clear</a>
										</div>
									</div>
								</section>
							</div>
						</div>
					</form>

					<?php 
					 	if(isset($_GET) && !empty($_GET))
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
								<table id="myTable" class="table table-bordered table-hover dataTable">
									<thead>
										<tr>
											<th style="width:15%; text-align:center;">Controls</th>
											<th onclick="sortTable(1)" class="text-center">Warehouse Code</th>
											<th onclick="sortTable(1)">Warehouse Name</th>
											<th onclick="sortTable(2)">Branch Name</th>
											<th onclick="sortTable(3)" class="text-center">Mobile Number</th>
											
											<!-- <th onclick="sortTable(5)" class="text-center">Created Date</th> -->
											<th onclick="sortTable(6)" style="text-align:center;">Status</th>
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
															<button type="button" class="btn btn-outline-primary gropdown-toggle waves-effect waves-light btn-sm" data-toggle="dropdown" aria-expanded="false">
																Action <i class="fa fa-angle-down"></i>
															</button>
															<ul class="dropdown-menu dropdown-menu-right">
																<li>
																	<a href="<?php echo base_url(); ?>warehouse/ManageWarehouse/edit/<?php echo $row['warehouse_id'];?>">
																		<i class="fa fa-edit"></i> Edit
																	</a>
																</li>
																<li>
																	<a href="<?php echo base_url(); ?>warehouse/ManageWarehouse/view/<?php echo $row['warehouse_id'];?>">
																		<i class="fa fa-eye"></i> View
																	</a>
																</li>
																<li>											
																	<?php 
																		if($row['active_flag'] == 'Y')
																		{
																			?>
																			<a class="unblock" href="<?php echo base_url(); ?>warehouse/ManageWarehouse/status/<?php echo $row['warehouse_id'];?>/N" title="Block">
																				<i class="fa fa-ban"></i> Inactive
																			</a>
																			<?php 
																		} 
																		else
																		{  ?>
																			<a class="block" href="<?php echo base_url(); ?>warehouse/ManageWarehouse/status/<?php echo $row['warehouse_id'];?>/Y" title="Unblock">
																				<i class="fa fa-check"></i> Active
																			</a>
																			<?php 
																		} 
																	?>
																<li>
															</ul>
														</div>
														
													</td>
													
													<td class="text-center"><?php echo ucfirst($row['warehouse_code']);?></td>
													<td><?php echo ucfirst($row['warehouse_name']);?></td>
													
													<td><?php echo ucfirst($row['branch_name']);?></td>
													<td class="text-center"><?php echo $row['mobile_number'];?></td>
													
													<!-- <td class="text-center">
														<?php #echo date(DATE_FORMAT,strtotime($row['created_date']));?>
													</td> -->
													
													<td style="text-align:center;">
														<?php 
															if($row['active_flag'] == 'Y')
															{
																?>
																<span class="btn btn-outline-success btn-sm" title="Active"> Active</span>
																<?php 
															} 
															else
															{ 
																?>
																<span class="btn btn-outline-warning btn-sm" title="Inactive"> Inactive</span>
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
								if(count($resultData)> 0)
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
	</div>
</div>
	