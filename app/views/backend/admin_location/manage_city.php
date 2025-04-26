


<!-- Import csv start -->
<div class="modal fade" id="importcityCSV" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabelcity" aria-hidden="true">
  <div class="modal-dialog" role="document">
	<div class="modal-content">
	  <div class="modal-header" style="background: #1a4363;color: #fff;">
		<h5 class="modal-title1" id="exampleModalLabelcity">Import State</h5>
		<button type="button" class="close" data-dismiss="modal" aria-label="Close">
		  <span aria-hidden="true">&times;</span>
		</button>
	  </div>
	   <form action="<?php echo base_url(); ?>admin_location/manage_city/import" enctype="multipart/form-data" method="post">
			<div class="modal-body">
				<div class="row">
					<div class="col-md-12">
						<div class="well well-small">
							<span class="text-danger-new-popup">NOTE : The first line in downloaded csv file should remain as it is. Please do not change the order of columns and Update valid data to CSV..</span>
							<a href="<?php echo base_url(); ?>assets/sample_city.csv" class="btn btn-info btn-flat pull-right" title="Download Sample File"><i class="fa fa-download"></i> Download</a>
							<span class="newline">You must follow this correct column order, like <span class="text-info">(Country Name, State Name, City Name)</span> </span>
						</div>
					</div>
				</div>
				<br>
				<div class="row">
					<div class="form-group col-md-10">
						<label class="col-form-label">Upload File</label>
						<input type="file" name="csv"  id="chooseFile" class="form-control singleDocument" onchange="return validateSingleDocumentExtension(this)" required />
						<span style="color:#a0a0a0;font-style: italic;">Note : Upload format CSV and upload size is 5 mb.</span>
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
				<button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
				<button type="submit" class="btn btn-primary ml-3">Import</button>
			</div>
		</form>
	</div>
  </div>
</div>
<!-- Import csv end -->

<!-- Import popup start here -->
	<script type="text/javascript">								
		$(document).keyup(function(e) 
		{
			if (e.keyCode == 27) 
			{
				$('.popup_show_user').hide();
			} 
		});		  
		$(document).ready(function()
		{
			$('a#show-panel').click(function()
			{ 
				$('#lightbox-panel').show();
			})

			$('#cancel').click(function()
			{ 
				$('#lightbox-panel').hide();
				$('#em').text(''); 
			})
		});
	</script>

	<div class="popup_show popup_show_user import-counry-popup" id="lightbox-panel" style="display:none;">
		<form action="" method="post" name="frmExcelImport" id="frmExcelImport" enctype="multipart/form-data">
			<div class="mail-backend-admin loc-pop-heading">
				<p class="mail-backend-admin-topic import-country-title">Import Cities</p>
			</div>
			<div class="mail-click-form location-pop-show">
				<div class="import-counry-file location-choose">
					<input type="file" name="file"id="import_file" required accept=".xls,.xlsx">
					<p class="note-xl">Note : Upload Excel File (.xls,.xlsx)</p>
				</div>
				<div class="admin-popup-btns export-country-buttons loc-pop-butt">
					<input type="submit" id="submit" name="import" class="btn btn-info pop-loc-button" value="Submit">
					<input class="btn btn-danger pop-loc-button" type="reset" id="cancel" value="Cancel" />
				</div>
				<div class="loc-pop-butt2">		
					<?php 
						$url = 'uploads/import/city_sample.xlsx';
						if( file_exists( $url ) )
						{
							?>
							<span class="sample-download-new">
								<a href="<?php echo base_url().$url;?>">Sample Download </a>
							</span>
							<?php 
						} 
					?>
				</div>	
			</div>		
		</form>
	</div>
	<!-- Import popup end here -->

<div class="content"><!-- Content start-->
	<div class="card"><!-- Card start-->
		<div class="card-body">
			<?php
				if(isset($type) && $type == "add" || $type == "edit")
				{
					?>
					<form action="" class="form-validate-jquery" enctype="multipart/form-data" method="post">
							<fieldset class="mb-3">
								<legend class="text-uppercase font-size-sm font-weight-bold">City</legend>
							</fieldset>					
							<?php
								$country = $this->db->get_where('geo_countries', array('active_flag' => 'Y'))->result_array();
							?>
							<div class="form-group row">
								<label class="col-sm-2 control-label">Country Name <span class="text-danger">*</span></label>
								<div class="col-sm-3">
									<select name="country_id" onchange="selectState(this.value);" class="form-control searchDropdown" required > <!--selectboxit-->
										<option value="">- Select -</option>
											<?php 
											foreach($country as $row)
											{
												$selected="";
												if(isset($edit_data[0]['country_id']) && $edit_data[0]['country_id'] == $row['country_id']){
													$selected="selected='selected'";
												}
												?>
												<option value="<?php echo $row['country_id'];?>" <?php echo $selected;?>><?php echo $row['country_name'];?></option>
												<?php 
											} 
											?>
									</select>
								</div>
							</div>
							<?php
								$state = $this->db->get_where('geo_states', array('active_flag' => 'Y'))->result_array();
							?>
							<div class="form-group row">
								<label class="col-sm-2 control-label">State Name <span class="text-danger">*</span></label>
								<div class="col-sm-3">
									<select name="state_id" id="state_id" onchange="selectDistrict(this.value);" class="form-control searchDropdown" required> <!--selectboxit-->
										<option value="">- Select -</option>
											<?php 
											if($type == "edit")
											{
												foreach($state as $row)
												{
													$selected="";
													if(isset($edit_data[0]['state_id']) && $edit_data[0]['state_id'] == $row['state_id']){
														$selected="selected='selected'";
													}
													?>
													<option value="<?php echo $row['state_id'];?>" <?php echo $selected;?>><?php echo $row['state_name'];?></option>
													<?php 
												}
											}
											?>
									</select>
								</div>
							</div>
							<?php 
							/* 
							<?php
								$district = $this->db->get_where('district', array('district_status' => '1'))->result_array();
							?>
							<div class="form-group row">
								<label class="col-sm-3 control-label"><?php echo get_phrase('district_name');?> <span class="text-danger">*</span></label>
								<div class="col-sm-9">
									<select name="district_id" id="district_id" onchange="selectCityorTown(this.value);" class="form-control select2" required> <!--selectboxit-->
										<option value=""><?php echo get_phrase('first_select_state');?></option>
											<?php 
											if($type == "edit")
											{
												foreach($district as $row)
												{
													$selected="";
													if(isset($edit_data[0]['district_id']) && $edit_data[0]['districte_id'] == $row['district_id']){
														$selected="selected='selected'";
													}
													?>
													<option value="<?php echo $row['district_id'];?>" <?php echo $selected;?>><?php echo $row['district_name'];?></option>
													<?php 
												}
											}
											?>
									</select>
								</div>
							</div>
								*/ ?>
							<script type="text/javascript">  
								function selectState(val)
								{
									if(val !='')
									{
										$.ajax({
											type: "POST",
											url:"<?php echo base_url().'welcome/ajaxSelectCity';?>",
											data: { id: val }
										}).done(function( msg ) {   
											$( "#state_id" ).html(msg);
										});
									}
									else 
									{ 
										alert("No State under this Country!");
									}
								}
								
								function selectDistrict(val)
								{
									if(val !='')
									{
										$.ajax({
											type: "POST",
											url:"<?php echo base_url().'welcome/ajaxSelectDistrict';?>",
											data: { id: val }
										}).done(function( msg ) {   
											$( "#district_id").html(msg);
										});
									}
									else 
									{ 
										alert("No districts under this state!");
									}
								}
							</script>
							
							<div class="form-group row">
								<label class="col-sm-2 control-label">City Name <span class="text-danger">*</span></label>
								<div class="col-sm-3">
									<input type="text" class="form-control" <?php echo $this->validation; ?> name="city_name" required value="<?php echo isset($edit_data[0]['city_name'])?$edit_data[0]['city_name']:"";?>">
								</div>
							</div>
							
							<div class="d-flex justify-content-end align-items-center">
								<a href="<?php echo base_url(); ?>admin_location/manage_city" class="btn btn-default">Close</a>
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
							<div class="new-import-btn report-export">
								<a href="<?php echo base_url(); ?>setup/settings" class="btn btn-primary btn-sm"><i class="icon-arrow-left16"></i> Back</a>
								<!-- <a title="Export" href="<?php echo base_url(); ?>admin_location/manage_city/export" class="btn btn-primary btn-sm">
									Export
								</a> -->
								<!-- <a href="#" data-toggle="modal" data-target="#importcityCSV" title="Import" class="btn btn-warning btn-sm">
									Import
								</a> -->
								<a href="<?php echo base_url(); ?>admin_location/manage_city/add" class="btn btn-info btn-sm">
									Create City
								</a>
							</div>
						</div>
					</div>
					
					<!-- Filters start here -->
					<form action="" class="form-validate-jquery" method="get">
						<div class="row mt-3">
							<div class="col-md-3">
								<div class="row">
									<label class="col-form-label col-md-5">Country Name <!-- <span class="text-danger">*</span> --></label>
									<div class="form-group col-md-7">
										<?php 
											$country_qry = "select country_id, country_name from geo_countries 
											order by country_name";

											$getCountry = $this->db->query($country_qry)->result_array(); 
										?>
										
										<select name="country_id" id="country_id" onchange="selectState(this.value);" class="form-control searchDropdown">
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
									<label class="col-form-label col-md-5">State Name</label>
									<div class="form-group col-md-7">
										<select name="state_id" id="state_id" onchange="selectCity(this.value);" class="form-control searchDropdown">
											<option value="">- Select -</option>
											<?php 
												if(isset($_GET['country_id']) && !empty($_GET['country_id']))
												{
													$state_qry = "select state_id, state_name from geo_states

													where country_id = '".$_GET['country_id']."'
													order by state_name";

													$getStates= $this->db->query($state_qry)->result_array(); 

													foreach($getStates as $row)
													{
														$selected="";
														if(isset($_GET['state_id']) && $_GET['state_id'] == $row["state_id"] )
														{
															$selected="selected='selected'";
														}
														?>
														<option value="<?php echo $row["state_id"];?>" <?php echo $selected;?>><?php echo $row["state_name"];?></option>
														<?php 
													} 
												} 
											?>
										</select>
									</div>
								</div>
							</div>

							<div class="col-md-3">
								<div class="row">
									<label class="col-form-label col-md-5">City Name</label>
									<div class="form-group col-md-7">
										<select name="city_id" id="city_id" class="form-control searchDropdown">
											<option value="">- Select -</option>
											<?php 
												if( (isset($_GET['country_id']) && !empty($_GET['country_id'])) && (isset($_GET['state_id']) && !empty($_GET['state_id'])))
												{
													$city_qry = "select city_id, city_name from geo_cities
													where 
														country_id = '".$_GET['country_id']."'
														and state_id = '".$_GET['state_id']."'
													order by geo_cities.city_name";

													$getCities= $this->db->query($city_qry)->result_array(); 

													foreach($getCities as $row)
													{
														$selected="";
														if(isset($_GET['city_id']) && $_GET['city_id'] == $row["city_id"] )
														{
															$selected="selected='selected'";
														}
														?>
														<option value="<?php echo $row["city_id"];?>" <?php echo $selected;?>><?php echo $row["city_name"];?></option>
														<?php 
													} 
												} 
											?>
										</select>
									</div>
								</div>
							</div>


							<script type="text/javascript">  
								function selectState(val)
								{
									if(val !='')
									{
										$.ajax({
											type: "POST",
											url:"<?php echo base_url().'welcome/ajaxSelectCity';?>",
											data: { id: val }
										}).done(function( msg ) {   
											$( "#state_id" ).html(msg);
										});
									}
									else 
									{ 
										$( "#state_id" ).html("<option value=''>- Select -</option>");
										$( "#city_id" ).html("<option value=''>- Select -</option>");
									}
								}
								function selectCity(val)
								{
									if(val !='')
									{
										$.ajax({
											type: "POST",
											url:"<?php echo base_url().'welcome/ajaxSelectCities';?>",
											data: { id: val }
										}).done(function( msg ) {   
											$( "#city_id" ).html(msg);
										});
									}
									else 
									{ 
										$( "#city_id" ).html("<option value=''>- Select -</option>");
									}
								}
							</script>

							<div class="col-md-3">
								<div class="row">
									<label class="col-form-label col-md-3">Status</label>
									<div class="form-group col-md-7">
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
						</div>

						<div class="row mt-3">
							<div class="col-md-9"></div>
							<div class="col-md-3">
								<div class="row">
									<label class="col-form-label col-md-3"></label>
									<div class="col-md-4">
										<button type="submit" class="btn btn-info">Search <i class="fa fa-search" aria-hidden="true"></i></button>
									</div>
									<div class="col-md-3">
										<a href="<?php echo base_url(); ?>admin_location/manage_city" title="Clear" class="btn btn-default">Clear</a>
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
								<div class="col-md-10"></div>

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
							<div class="new-scroller">
								<table class="table table-bordered table-hover  dataTable">
									<thead>
										<tr>
											<?php /* <th width="15"><?php echo get_phrase('s.No');?></th> */ ?>
											<th class="text-center" style="width:60px;">Controls</th>
											<th>Country Name</th>
											<th>State Name</th>
											<?php /* <th><?php echo get_phrase('district_name');?></th> */ ?>
											<th>City Name</th>
											<th class="text-center">Status</th>
										</tr>
									</thead>
									<tbody>
										<?php 	
											$i=0;
											$firstItem = $first_item;
											foreach($projects as $row)
											{
												?>
												<tr>
													<td class="text-center">
														<div class="dropdown" style="display: inline-block;padding-right: 10px!important;width:92px;">
															<button type="button" class="btn btn-outline-primary gropdown-toggle waves-effect waves-light btn-sm" data-toggle="dropdown" aria-expanded="false">
																Action <i class="fa fa-angle-down"></i>
															</button>
															<ul class="dropdown-menu dropdown-menu-right dropdown-menu-new">
																<li>
																	<a title="Edit" href="<?php echo base_url(); ?>admin_location/manage_city/edit/<?php echo $row['city_id'];?>">
																		<i class="fa fa-pencil"></i> Edit
																	</a>
																</li>
																<li>
																	<?php 
																		if($row['active_flag'] == $this->active_flag)
																		{
																			?>
																			<a href="<?php echo base_url(); ?>admin_location/manage_city/status/<?php echo $row['city_id'];?>/N" title="block">
																				<i class="fa fa-ban"></i> Inactive
																			</a>
																			<?php 
																		} 
																		else
																		{  ?>
																			<a href="<?php echo base_url(); ?>admin_location/manage_city/status/<?php echo $row['city_id'];?>/Y" title="Unblock">
																				<i class="fa fa-ban"></i> Active
																			</a>
																			<?php 
																		} 
																	?>
																</li>
															</ul>
														</div>
													</td>
													<td><?php echo ucfirst($row['country_name']);?></td>
													<td><?php echo ucfirst($row['state_name']);?></td>
													<?php /* <td><?php echo ucfirst($row['district_name']);?></td> */ ?>
													<td><?php echo ucfirst($row['city_name']);?></td>
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
												</tr>
												<?php 
												$i++;
											}
										?>
									</tbody>
								</table>
								<?php 
									if(count($projects) == 0)
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
								if( count($projects) > 0 )
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

