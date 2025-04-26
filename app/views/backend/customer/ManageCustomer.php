<!-- Import csv start -->
<div class="modal fade" id="importcountryCSV" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header" --style="background: #1a4363;color: #fff;">
				<h5 class="modal-title" id="exampleModalLabel">Import</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<form action="<?php echo base_url(); ?>customer/ManageCustomer/import" enctype="multipart/form-data" method="post">
				<div class="modal-body">
					<div class="row">
						<!-- <div class="col-md-12 mb-3">
							<div class="well well-small">
								The correct column order is <span class="text-info-"> ( Item Name, Item Description & Item Cost  )</span>&nbsp; &amp; You must follow this.
							</div>
						</div> -->
						<div class="col-md-12 mb-3">
							<span class="text-danger-" style="font-size:12px !important;"><b>Note : </b> The first line in downloaded csv file should remain as it is. Please do not change the order of columns and Update valid data..</span><br>
						</div>
					</div>

					<div class="row">
						<div class="form-group col-md-9">
							<input type="file" name="csv"  id="chooseFile" class="form-control singleDocument" onchange="return validateSingleDocumentExtension(this)" required />
							<span style="color:#a0a0a0;">Note : Upload format CSV and upload size is 5 mb.</span>
						</div>
						<div class="col-md-3">
							<a href="<?php echo base_url(); ?>assets/sample_customers.csv" class="btn btn-info btn-flat btn-sm pull-right" title="Download Sample File">
								<i class="fa fa-download"></i> Download
							</a>
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
					<!-- <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button> -->
					<button type="submit" class="btn btn-primary btn-sm ml-1">Import</button>
				</div>
			</form>
		</div>
	</div>
</div>	
<!-- Import csv end -->
<?php 
	$manageCustomersMenu = accessMenu(manage_customers);
?>
<div class="content"><!-- Content start-->
	<div class="card"><!-- Card start-->
		<div class="card-body"><!-- Card-body start-->
			<?php
				if(isset($type) && $type == "add" || $type == "edit" || $type == "view")
				{
					if($type == "view"){
						$fieldSetDisabled = "disabled";
						$dropdownDisabled = "style='pointer-events: none;'";
						$searchDropdown = "";
					}else{
						$fieldSetDisabled = "";
						$dropdownDisabled = "";
						$searchDropdown = "searchDropdown";
					}
					$getCountry = $this->db->query("select country_id,country_name from geo_countries where active_flag ='Y' order by country_name asc")->result_array();
					?>
					
					<form action="" id="form_submit" class="form-validate-jquery" enctype="multipart/form-data" method="post">
						
						<!-- <div class="row">
							<div class="col-md-6">
								<h5 class="text-uppercase font-weight-bold">
									<?php 
										if($type == "add")
										{
											?>
											Create
											<?php
										}
										else if($type == "edit")
										{
											?>
											Edit
											<?php
										}
										else if($type == "view")
										{
											?>
											View
											<?php
										}
									?>	
								Customer</h5>
							</div>
							
							<?php 
								if($type == "view")
								{
									?>
									<div class="col-md-6 text-right">
										<a class="btn btn-sm btn-primary edit-icon" href="<?php echo base_url(); ?>customer/ManageCustomerSites/edit/<?php echo $id;?>" title="Edit">
											<i class="fa fa-edit"></i>
										</a>
									</div>
									<?php 
								} 
							?>
						</div> -->
						
						<fieldset class="mb-3" <?php echo $fieldSetDisabled;?>>
							<div class="form-group row">
								<div class="col-md-6">													
									<div class="row">
										<div class="form-group col-md-4">
											<label class="col-form-label">Customer  Name <span class="text-danger">*</span></label>
										</div>
										
										<div class="form-group col-md-5">
											<div class="">											
												<input type="text" name="customer_name" autocomplete="off" id="customer_name" required class="form-control single_quotes" value="<?php echo isset($edit_data[0]['customer_name']) ? $edit_data[0]['customer_name'] :NULL;?>" placeholder="">					
											</div>
										</div>
												
									</div>	
								</div>
								<div class="col-md-6">													
									<div class="row">
										<div class="form-group col-md-4">
										   <label class="col-form-label"> Contact Person </label>										  
										</div>
										
										<div class="form-group col-md-5">
											<div class="">											 
											<input type="text" name="contact_person" autocomplete="off" id="contact_person" class="form-control single_quotes" value="<?php echo isset($edit_data[0]['contact_person']) ? $edit_data[0]['contact_person'] :NULL;?>" placeholder="">					
											</div>
										</div>	
									</div>	
								</div>
							</div>
							<div class="form-group row">
								<div class="col-md-6">													
									<div class="row">
										<div class="form-group col-md-4">
										  <label class="col-form-label">Mobile Number <span class="text-danger">*</span></label>
										</div>
										
										<div class="form-group col-md-5">
											<div class="">											
											  <input type="text" name="mobile_number" autocomplete="off" required <?php echo $this->validation;?> id="mobile_number" class="form-control mobile_vali" minlength="10" maxlength='10' value="<?php echo isset($edit_data[0]['mobile_number']) ? $edit_data[0]['mobile_number'] :NULL;?>" placeholder="">
											</div>
										</div>					
									</div>	
								</div>
								<div class="col-md-6">													
									<div class="row">
										<div class="form-group col-md-4">
										  <label class="col-form-label"> Alter Mobile Number</label>										  
										</div>
										
										<div class="form-group col-md-5">
											<div class="">											
											  <input type="text" name="alt_mobile_number" autocomplete="off" <?php echo $this->validation;?> id="alt_mobile_number" class="form-control mobile_vali"  minlength="10" maxlength='10' value="<?php echo isset($edit_data[0]['alt_mobile_number']) ? $edit_data[0]['alt_mobile_number'] :NULL;?>" placeholder="">				
											</div>
										</div>	
									</div>	
								</div>
							</div>
							<div class="form-group row">
								<div class="col-md-6">													
									<div class="row">
										<div class="form-group col-md-4">
										  <label class="col-form-label">Email</label>
										</div>
										
										<div class="form-group col-md-5">
											<div class="">											
											  <input type="email" name="email_address" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,63}$"autocomplete="off" id="email" class="form-control" value="<?php echo isset($edit_data[0]['email_address']) ? $edit_data[0]['email_address'] :NULL;?>" placeholder="">
											  <span class='small employee_email_exist_error' style="color:#a19f9f;"></span>
											</div>
										</div>					
									</div>	
								</div>
								<div class="col-md-6">													
									<div class="row">
										<div class="form-group col-md-4">
										  <label class="col-form-label"> GST Number</label>										  
										</div>
										
										<div class="form-group col-md-5">
											<div class="">											
											  <input type="text" name="gst_number" autocomplete="off" <?php echo $this->validation;?> id="gst_number" class="form-control" value="<?php echo isset($edit_data[0]['gst_number']) ? $edit_data[0]['gst_number'] :NULL;?>" placeholder="">				
											</div>
										</div>	
									</div>	
								</div>
							</div>
												
								<!-- Address -->
							
							<div class="form-group row">
								<label class="col-form-label col-md-2">Country <span class="text-danger">*</span></label>
								<div class="col-md-2">
									<select name="country_id" id="country_id" onchange="selectState(this.value);" required class="form-control <?php echo $searchDropdown;?>">
										<option value="">- Select -</option>
										<?php 
											$getCountry = $this->db->query("select country_id,country_name from geo_countries where active_flag='Y' order by country_name asc")->result_array();
					
											foreach($getCountry as $row)
											{
												$selected="";
												if(isset($edit_data[0]['country_id']) && $edit_data[0]['country_id'] == $row['country_id'])
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
									<select name="state_id" id="state_id" required onchange="selectCity(this.value);"class="form-control <?php echo $searchDropdown;?>">
										<option value="">- Select -</option>
										<?php 
											if($edit_data[0]['country_id'] !=0 && $edit_data[0]['country_id'] !="")
											{
												$state = $this->db->query("select state.state_id,state.state_name from geo_states as state
														where active_flag='Y' and state.country_id='".$edit_data[0]['country_id']."'")->result_array();
														
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
										<option value="">- Select -</option>
										<?php 
											if($edit_data[0]['state_id'] !=0 && $edit_data[0]['state_id'] !="")
											{
												$city= $this->db->query("select city.city_id,city.city_name from geo_cities as city
														where active_flag='Y' and city.state_id='".$edit_data[0]['state_id']."'")->result_array();
														
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
									<textarea name="address1" id="address1" rows="1" required autocomplete="off" class="form-control single_quotes" placeholder=""><?php echo isset($edit_data[0]['address1']) ? $edit_data[0]['address1'] : NULL;?></textarea>
								</div>
							</div>

							<div class="form-group row">
								<label class="col-form-label col-md-2">Address 2</label>
								<div class="form-group col-md-3">
									<textarea name="address2" id="address2" rows="1" autocomplete="off" class="form-control single_quotes" placeholder=""><?php echo isset($edit_data[0]['address2']) ? $edit_data[0]['address2'] : NULL;?></textarea>
								</div>
							</div>

							<div class="form-group row">
								<label class="col-form-label col-md-2">Address 3</label>
								<div class="form-group col-md-3">
									<textarea name="address3" id="address3" rows="1" autocomplete="off" class="form-control single_quotes" placeholder=""><?php echo isset($edit_data[0]['address3']) ? $edit_data[0]['address3'] : NULL;?></textarea>
								</div>
							</div>

							<div class="form-group row">
								<label class="col-form-label col-md-2">Postal Code <span class="text-danger">*</span></label>
								<div class="form-group col-md-2">
									<input type="text" name="postal_code" autocomplete="off" required <?php echo $this->validation;?> id="postal_code" class="form-control mobile_vali" minlength="6" maxlength='6' value="<?php echo isset($edit_data[0]['postal_code']) ? $edit_data[0]['postal_code'] :"";?>" placeholder="">
								</div>
							</div>
							<!-- Address -->
						</fieldset>
						
						<div class="d-flexad" style="text-align:right;">
							<a href="<?php echo base_url(); ?>customer/ManageCustomer" class="btn btn-default">Close</a>
							<?php 
								if($type == "view")
								{
								}
								else
								{
									?>
									<button type="submit" id="submit" class="btn btn-primary  ml-1">Save</button>
									<?php 
								} 
							?>	
						</div>
					</form>	

					<script type="text/javascript">  
						function selectState(val,type)
						{
							if(val !='')
							{
								$( "#address1").val("");
								$( "#address2").val("");
								$( "#address3").val("");
								$( "#postal_code").val("");

								$.ajax({
									type: "POST",
									url:"<?php echo base_url().'admin/ajaxselectState';?>",
									data: { id: val }
								}).done(function( msg ) 
								{   
									if(msg == "no_date_found")
									{
										$( "#state_id").html('<option value="">- Select -</option>');
										$( "#city_id").html('<option value="">- Select -</option>');
									}
									else
									{
										$( "#state_id").html(msg);
										$( "#city_id").html('<option value="">- Select -</option>');
									}
								});
							}
							else 
							{ 
								$( "#state_id").html('<option value="">- Select -</option>');
								$( "#city_id").html('<option value="">- Select -</option>');
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
									if(msg == "no_date_found")
									{

									}
									else
									{
										$( "#city_id").html(msg);
									}	
								});
							}
							else 
							{ 
								$( "#city_id").html('<option value="">- Select -</option>');
							}
						}
					</script>
					<?php
				}
				else
				{ 
					?>
					<!-- buttons start here -->
					<div class="row mb-2">
						<div class="col-md-6"><h3><b>Customers</b></h3></div>
						<div class="col-md-6 float-right text-right">
							
							<?php
								if($manageCustomersMenu['create_edit_only'] == 1 || $this->user_id == 1)
								{
									?>
									<a href="#" data-toggle="modal" data-target="#importcountryCSV" title="Import" class="btn btn-warning btn-sm">
										<i class="icon-import"></i> Import
									</a>
									<a href="<?php echo base_url(); ?>customer/ManageCustomer/add" class="btn btn-info btn-sm">
										Create Customer
									</a>
									<?php 
								} 
							?>
						</div>
					</div>
					<!-- buttons end here -->

					<!-- filters-->
					<form action="" class="form-validate-jquery" method="get">
						<div class="row">
							<div class="col-md-4">
								<div class="row">
									<label class="col-form-label col-md-4">Customer Name <!-- <span class="text-danger">*</span> --></label>
									<div class="form-group col-md-8">
										<div class="input-wrapper">
											<input type="text" name="customer_name" autocomplete="off" id="customer_name" value="<?php echo isset($_GET['customer_name']) ? $_GET['customer_name'] : NULL; ?>" placeholder="Customer Name" class="form-control">
											<input type="hidden" name="customer_id" autocomplete="off" id="customer_id" value="<?php echo isset($_GET['customer_id']) ? $_GET['customer_id'] : NULL; ?>" >
											<div id="CustomerList"></div><!-- Clear icon start -->
											<?php 
												if(isset($_GET["customer_id"]) && !empty($_GET["customer_id"]))
												{
													$styleDisplay = "display:block";
												}else{
													$styleDisplay = "display:none";
												}
												?>
											<span class="customer_clear_icon" title="Clear" onclick="clearCustomerSearchKeyword();" style="<?php echo $styleDisplay;?>">
												<i class="fa fa-times" aria-hidden="true"></i>
											</span>

											<script>
												$(document).ready(function()
												{  
													$('#customer_name').keyup(function()
													{  
														var query = $(this).val();  

														if(query != '')  
														{  
															$.ajax({  
																url:"<?php echo base_url();?>customer/ajaxCustomerList",  
																method:"POST",  
																data:{query:query},  
																success:function(data)  
																{  
																	$('#CustomerList').fadeIn();  
																	$('#CustomerList').html(data);  
																}  
															});  
														}  
													});

													$(document).on('click', 'ul.list-unstyled-customer_id li', function()
													{  
														var value = $(this).text();
														
														if(value === "Sorry! Customer Not Found.")
														{
															$('#CustomerList').fadeOut();
														}
														else
														{
															$('#CustomerList').fadeOut();  
														}
													});
												});

												function getCustomerList(customer_id,customer_name)
												{
													$('.customer_clear_icon').show();
													if(customer_id == 0)	
													{
														$('#customer_id').val('0');
													}
													else
													{
														$('#customer_id').val(customer_id);
														$('#customer_name').val(customer_name);
													}
												}

												function clearCustomerSearchKeyword()
												{
													$(".customer_clear_icon").hide();
													$("#customer_id").val("");
													$("#customer_name").val("");
												}
											</script>

										</div>
									</div>
								</div>
							</div>
							
							<div class="col-md-4">
								<div class="row">
									<label class="col-form-label col-md-4">Mobile Number</label>
									<div class="form-group col-md-7">
										<input type="search" name="mobile_number" minlength="10" maxlength='10' class="form-control mobile_vali" value="<?php echo !empty($_GET['mobile_number']) ? $_GET['mobile_number'] :""; ?>" placeholder="Mobile Number" autocomplete="off">
									</div>
								</div>
							</div>

							<div class="col-md-3">
								<div class="row">
									<label class="col-form-label col-md-3">Status</label>
									<div class="form-group col-md-9">

										<?php 
											$activeStatus = $this->common_model->lov('ACTIVESTATUS') 
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

						<div class="row ">
						
							<div class="col-md-4 offset-8 text-right float-right" >
							    
								<button type="submit" class="btn btn-info">Search <i class="fa fa-search" aria-hidden="true"></i></button>
								&nbsp;<a href="<?php echo base_url(); ?>customer/ManageCustomer" title="Clear" class="btn btn-default">Clear</a>
								
							</div>
						</div>
					</form>
					<!-- filters-->
												
					<?php 
						if( isset($_GET) && !empty($_GET))
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
								<table id="myTable" class="table table-bordered table-hover --table-striped dataTable">
									<thead>
										<tr>
											<th class="text-center">Controls</th>
											<th onclick="sortTable(1)">Customer Name</th>
											<!-- <th onclick="sortTable(4)">Customer Number</th> -->
											<th onclick="sortTable(5)">Contact Person</th>
											<th onclick="sortTable(5)">Mobile Number</th>
											<th onclick="sortTable(5)" style="text-align:center;">Status</th>
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
													<td class="text-center" style="width:90px;">
														<div class="dropdown" style="width:90px;">
															<button type="button" class="btn btn-outline-info gropdown-toggle" data-toggle="dropdown" aria-expanded="false">
																Action&nbsp;<i class="fa fa-chevron-down"></i>
															</button>
															<ul class="dropdown-menu dropdown-menu-right dropdown-menu-new">
																<?php
																	if($manageCustomersMenu['create_edit_only'] == 1 || $manageCustomersMenu['read_only'] == 1 || $this->user_id == 1)
																	{ 
																		?>
																		<?php
																			if($manageCustomersMenu['create_edit_only'] == 1 || $this->user_id == 1)
																			{
																				?>
																				<li>
																					<a title="Edit" href="<?php echo base_url(); ?>customer/ManageCustomer/edit/<?php echo $row['customer_id'];?>">
																						<i class="fa fa-pencil"></i> Edit
																					</a>
																				</li>
																				<?php 
																			} 
																		?>

																		<?php
																			if($manageCustomersMenu['read_only'] == 1 || $this->user_id == 1)
																			{
																				?>
																				<li>
																					<a title="View" href="<?php echo base_url(); ?>customer/ManageCustomer/view/<?php echo $row['customer_id'];?>">
																						<i class="fa fa-eye"></i> View
																					</a>
																				</li>
																				<?php 
																			} 
																		?>
																		<?php
																			if($manageCustomersMenu['create_edit_only'] == 1 || $this->user_id == 1)
																			{
																				?>
																				<li>
																					<?php 
																						if($row['active_flag'] == $this->active_flag)
																						{
																							?>
																							<a href="<?php echo base_url(); ?>customer/ManageCustomer/status/<?php echo $row['customer_id'];?>/N" title="Block">
																								<i class="fa fa-ban"></i> Inactive
																							</a>
																							<?php 
																						} 
																						else
																						{  ?>
																							<a href="<?php echo base_url(); ?>customer/ManageCustomer/status/<?php echo $row['customer_id'];?>/Y" title="Unblock">
																								<i class="fa fa-ban"></i> Active
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
													<td><?php echo ucfirst($row['customer_name']);?></td>
													<!-- <td><?php #echo $row['customer_number'];?></td> -->
													<td><?php echo $row['contact_person'];?></td>
													<td><?php echo $row['mobile_number'];?></td>
													<td class="text-center">
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
										<div class="text-center">
											<img src="<?php echo base_url();?>uploads/nodata.png">
										</div>
										<?php 
									} 
								?>
							</div>
							<!-- Table end here -->
					
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
		</div><!-- Card body end-->
	</div><!-- Card end-->
</div><!-- Content end-->

