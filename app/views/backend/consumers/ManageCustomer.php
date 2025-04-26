<style>
</style>

<?php
	$consumerListingMenu = accessMenu(consumer_listing);
?>	

<div class="content"><!-- Content start-->
	<div class="card"><!-- Card start-->
		<div class="card-body"><!-- Card-body start-->
			<?php
				if(isset($type) && $type == "add" || $type == "edit")
				{
					$getCountry = $this->db->query("select country_id,country_name from country where country_status=1 order by country_name asc")->result_array();
					?>
					<div class="card">
						<div class="card-body">
							<form action="" class="form-validate-jquery" enctype="multipart/form-data" method="post">
								<div class="row">	
									<div class="form-group col-md-3">
										<label class="col-form-label">Customer Name <span class="text-danger">*</span></label>
										<input type="text" name="first_name" required id="first_name" <?php echo $this->validation; ?> class="form-control only_name" value="<?php echo isset($edit_data[0]['first_name']) ? $edit_data[0]['first_name'] :"";?>" placeholder="">
									</div>
									
									<div class="form-group col-md-3">
										<label class="col-form-label">Email </label>
										<input type="email" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,63}$" name="email" --id="emp_email" class="form-control" value="<?php echo isset($edit_data[0]['email']) ? $edit_data[0]['email'] :"";?>" placeholder="">
										<?php /* <span class='employee_email_exist_error'></span> */?>
									</div>
								</div>
								
								<div class="row">
									<div class="form-group col-md-3">
										<label class="col-form-label">Mobile Number <span class="text-danger">*</span> </label>
										<input type="text" name="mobile_number" required id="mobile_number" minlength="10" maxlength='12' class="form-control mobile_vali" value="<?php echo isset($edit_data[0]['mobile_number']) ? $edit_data[0]['mobile_number'] :"";?>" placeholder="Ex.9632587410">
										<span class="mobile_number_exist"></span>
									</div>
									
									<div class="form-group col-md-3">
										<label class="col-form-label">Country <span class="text-danger">*</span></label>
										<select name="country_id" id="country_id" required onchange="selectState(this.value);"  class="form-control searchDropdown">
											<option value="">- Select Country -</option>
											<?php 
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
								<div class="row">
									<div class="form-group col-md-3">
										<label class="col-form-label">State <span class="text-danger">*</span> </label>
										<select name="state_id" id="state_id" required onchange="selectCity(this.value);" class="form-control searchDropdown">
											<option value="">- Select State -</option>
											<?php 
												if($edit_data[0]['state_id'] !=0 && $edit_data[0]['state_id'] !="")
												{
													$state = $this->db->query("select state.state_id,state.state_name from state
															where state_status=1 and state.country_id='".$edit_data[0]['country_id']."'")->result_array();
															
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
									
									<div class="form-group col-md-3">
										<label class="col-form-label">City</label>
										<select name="city_id" id="city_id" class="form-control searchDropdown">
											<option value="">- Select City -</option>
											<?php 
												if($edit_data[0]['city_id'] !=0 && $edit_data[0]['city_id'] !="")
												{
													$city= $this->db->query("select city.city_id,city.city_name from city
															where city_status=1 and city.state_id='".$edit_data[0]['state_id']."'")->result_array();
															
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
								
								<div class="row">
									<div class="form-group col-md-3">
										<label class="col-form-label">Address <span class="text-danger">*</span></label>
										<textarea name="address1" required id="address1" class="form-control" placeholder=""><?php echo isset($edit_data[0]['address1']) ? $edit_data[0]['address1'] :"";?></textarea>
									</div>
								</div>
								
								<div class="d-flexad text-right">
									<a href="<?php echo base_url(); ?>customer/ManageCustomer" class="btn btn-outline-dark waves-effect">Cancel</a>
									<?php 
										if($type == "edit")
										{
											?>
											<button type="submit" class="btn btn-info waves-effect ml-2">Update</button>
											<?php 
										}
										else
										{
											?>
											<button type="submit" class="btn btn-primary waves-effect ml-2 register-but">Create</button>
											<?php 
										}
									?>
								</div>
							</form>
							
							<script type="text/javascript"> 
								function selectState(val)
								{
									if(val !='')
									{
										$.ajax({
											type: "POST",
											url:"<?php echo base_url().'admin/ajaxselectState';?>",
											data: { id: val }
										}).done(function( msg ) 
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
											$( "#city_id").html(msg);
										});
									}
									else 
									{ 
										alert("No cities under this state!");
									}
								}
							</script>
					</div>
					</div>
					<?php
				}
				else if(isset($type) && $type == "view")
				{
					?>
					<?php 
						$page_data = array();
						echo $this->load->view("backend/consumers/profileHeader.php", $page_data, true);
					?>
					<div --class="row">
						<div class="col-md-12 content-view-cutomer p-0">
							<?php 
								echo $this->load->view("backend/consumers/profileTabs.php", $page_data, true);
							?>
							<div class="x_content x_content1 detail-section-emp p-0 testup" >
								<div class="" role="tabpanel" data-example-id="togglable-tabs">
									<div class="col-md-12 client-details-new mt-2">
										<div class="row">
											<div class="col-md-6">
												<h2 class="cli-other-infos"><i class="fa fa-info"></i> Profile Information</h2>
												<div class="card p-3">
													<div class="cli-card-new">
														<div class="row client-details-row">
															<div class="col-md-4">
																<span class="view-label"> Customer Name </span>
															</div>
															<div class="col-md-7">
																<?php echo !empty($edit_data[0]['customer_name']) ? ucfirst($edit_data[0]['customer_name']) : "--";?>
															</div>
														</div>
														<div class="row client-details-row">
															<div class="col-md-4">
																<span class="view-label"> Phone Number</span>
															</div>
															<div class="col-md-7">
																<?php echo !empty($edit_data[0]['mobile_number']) ? $edit_data[0]['mobile_number'] : "--";?>
															</div>
														</div>
														<div class="row client-details-row">
															<div class="col-md-4">
																<span class="view-label"> Email</span>
															</div>
															<div class="col-md-7">
																<?php echo !empty($edit_data[0]['email_address']) ? $edit_data[0]['email_address'] : "--";?>
															</div>
														</div>
														
													</div>
												</div>
											</div>
											
											<?php /* <div class="col-md-6">
												<h2 class="cli-other-info"><i class="fa fa-map"></i> Address Information</h2>
												<div class="card">
													<div class="cli-card-new ">
														<div class="row client-details-row">
															<div class="col-md-4">
																<span class="view-label">Address</span>
															</div>
															<div class="col-md-7">
																<?php echo !empty($edit_data[0]['address1']) ? $edit_data[0]['address1'] : "--";?>
															</div>
														</div>
														
														<div class="row client-details-row">
															<div class="col-md-4">
																<span class="view-label">Landmark</span>
															</div>
															<div class="col-md-7">
																<?php echo !empty($edit_data[0]['land_mark']) ? $edit_data[0]['land_mark'] : "--";?>
															</div>
														</div>
														
														<div class="row client-details-row">
															<div class="col-md-4">
																<span class="view-label"> Country Name</span>
															</div>
															<div class="col-md-7">
																<?php echo !empty($edit_data[0]['country_name']) ? $edit_data[0]['country_name'] : "--";?>
															</div>
														</div>
														<div class="row client-details-row">
															<div class="col-md-4">
																<span class="view-label"> State Name</span>
															</div>
															<div class="col-md-7">
																<?php echo !empty($edit_data[0]['state_name']) ? $edit_data[0]['state_name'] : "--";?>
															</div>
														</div>
														<div class="row client-details-row">
															<div class="col-md-4">
																<span class="view-label"> City Name</span>
															</div>
															<div class="col-md-7">
																<?php echo !empty($edit_data[0]['city_name']) ? $edit_data[0]['city_name'] : "--";?>
															</div>
														</div>
														<div class="row client-details-row">
															<div class="col-md-4">
																<span class="view-label"> Postal Code</span>
															</div>
															<div class="col-md-7">
																<?php echo !empty($edit_data[0]['pin_code']) ? $edit_data[0]['pin_code'] : "--";?>
															</div>
														</div>
													</div>
												</div>
											</div> */ ?>
										</div>
									</div>	
								</div>
							</div>
						</div>
					</div>
					<?php
				}
				else if(isset($type) && $type == "addresslist")
				{
					?>
					<?php 
						$page_data = array();
						echo $this->load->view("backend/consumers/profileHeader.php", $page_data, true);
					?>
					<div --class="row">
						<div class="col-md-12 content-view-cutomer p-0">
							<?php 
								echo $this->load->view("backend/consumers/profileTabs.php", $page_data, true);
							?>
							<div class="x_content x_content1 detail-section-emp p-0 testup1">
								<div class="" role="tabpanel" data-example-id="togglable-tabs">
									<div class="client-details-new mt-4 testup1">
										<div class="row">
											<?php 	
												$i=0;
												$firstItem = $first_item;
												foreach($edit_data as $row)
												{
													?>
													<div class="col-sm-6 col-md-6 col-lg-4">
														<div class="card bg-white p-3 shadow">
															<div class="d-flex justify-content-between">
															<div class="user-info mt-0 mb-2">
																<div class="user-info__basic">
																	<i class="fa fa-address-book"></i>
																	<h5 class="mb-1" style="font-weight:500;">
																		Customer Name: <?php echo !empty($row['customer_name']) ? ucfirst($row['customer_name']) : "--"; ?>
																	</h5>

																	<div class="address-details">
																		<p>
																			<strong class="mb-2">Address Name :</strong>
																			<span><?php echo !empty($row['address_name']) ? $row['address_name'] : "--"; ?></span>
																		</p>
																		<p>
																			<strong class="mb-2">Address Line 1 :</strong>
																			<span><?php echo !empty($row['address1']) ? $row['address1'] : "--"; ?></span>
																		</p>
																		<p>
																			<strong class="mb-2">Address Line 2 :</strong>
																			<span><?php echo !empty($row['address2']) ? $row['address2'] : "--"; ?></span>
																		</p>
																		<p>
																			<strong class="mb-2">Postal Code :</strong>
																			<span><?php echo !empty($row['postal_code']) ? $row['postal_code'] : "--"; ?></span>
																		</p>
																		<p>
																			<strong class="mb-2">Landmark :</strong>
																			<span><?php echo !empty($row['land_mark']) ? $row['land_mark'] : "--"; ?></span>
																		</p>
																	</div>
																</div>
															</div>

															</div>
															<!-- <div class="row">
																<div class="col-md-6 col-sm-6">
																	<h5 class="mb-0"><i class="fa fa-phone"></i> 
																		<?php echo !empty($row['alternative_number']) ? $row['alternative_number'] : "--";?>
																	</h5>
																	<h5>
																		<small>
																			<i class="fa fa-address-book"></i>
																			<?php echo !empty($row['address_name']) ? $row['address_name'] : "--";?>
																		</small>
																	</h5>
																</div>
																<div class="col-md-6 col-sm-6">
																	<h5 class="mb-0"><i class="fa fa-map-pin"></i> 
																		<?php echo !empty($row['pin_code']) ? $row['pin_code'] : "--";?>
																	</h5>
																	<h5>
																		<small>
																			<i class="fa fa-paper-plane"></i>
																			<?php echo !empty($row['locality']) ? $row['locality'] : "--";?>
																		</small>
																	</h5>
																	
																</div>
																<div class="col-md-6 col-sm-6">
																	<span class="text-success font-weight-bold">
																	<i class="fa fa-map-marker"></i>
																		<?php echo !empty($row['land_mark']) ? $row['land_mark'] : "--";?>
																	</span>
																</div>
															</div> -->
														</div>
													</div>
													<?php 
													$i++;
												}
											?>
											<?php 
												if(count($edit_data) == 0)
												{
													?>
													<div class="col-md-12 text-center">
														<img src="<?php echo base_url();?>assets/logo2.png" style="width:200px;height:200px;">
														<br><span style="font-size:14px;color:#979696;">No data found.</span><br><br>
													</div>
													
													<?php 
												} 
											?>			
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<?php
				}
				else if(isset($type) && $type == "wallet")
				{
					?>
					<div --class="content content-cli-profile">
						<?php 
							$page_data = array();
							echo $this->load->view("backend/consumers/profileHeader.php", $page_data, true);
						?>
						<div --class="row">
							<div class="col-md-12 content-view-cutomer p-0">
								
								<?php 
									echo $this->load->view("backend/consumers/profileTabs.php", $page_data, true);
								?>
								<div class="x_content x_content1 detail-section-emp p-0">
									<div class="" role="tabpanel" data-example-id="togglable-tabs">
										<div class="col-md-12 client-details-new mt-4">
											<div class="row">
												<div --class="col-sm-12 col-md-8 col-lg-4">
													<div class="card bg-white p-3 shadow">
														<div class="row">
															<div class="col-lg-12">
																<div class="title">
																	<h3>Wallet</h3>
																</div>
															</div>
														</div>
														<div class="main-wallet">
															<div class="row">
																<!-- <div class="col-md-2">
																	<img src="<?php echo base_url()?>/uploads/wallet.png" style="width:100px;height:auto;">
																</div> -->
																<!-- <div class="col-md-2">
																</div> -->
																<div class="">
																	<div class="card-body wallet-amount" --style="width:500px;">
																		<h2> <?php echo CURRENCY_CODE ?> <?php echo number_format($wallet_amount,DECIMAL_VALUE,'.','') ?></h2>
																		<p>Current Wallet Balance</p>
																	</div>
																	<div class="col-md-2">
																	</div> 
																</div>
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<?php
				}
				else if(isset($type) && $type == "bookmark")
				{
					?>
					<div --class="content content-cli-profile"><!-- Content start-->
						<?php 
							$page_data = array();
							echo $this->load->view("backend/customer/profileHeader.php", $page_data, true);
						?>
						<div --class="row">
							<div class="col-md-12 content-view-cutomer p-0">
								
								<?php
									echo $this->load->view("backend/customer/profileTabs.php", $page_data, true);
								?>
								<div class="x_content x_content1 detail-section-emp p-0 testup1">
									<div class="" role="tabpanel" data-example-id="togglable-tabs">
										<div class="col-md-12 client-details-new mt-4">
											<div class="row">
												<?php 	
													$firstItem = $first_item;
													foreach($edit_data as $row)
													{
													?>
														<div class="col-sm-6 col-md-6 col-lg-4">
															<div class="card bg-white p-3 shadow">
																<div class="col-md-12">
																	<div class="row client-details-row">
																		<div class="col-md-6">
																			<span class="view-label">Branch Name</span>
																		</div>
																		<div class="col-md-6">
																			<?php echo $row['branch_name'];?>
																		</div>
																	</div>
																	<div class="row client-details-row">
																		<div class="col-md-6">
																			<span class="view-label">Product Name</span>
																		</div>
																		<div class="col-md-6">
																			<?php echo $row['product_name'];?>
																		</div>
																	</div>
																</div>
																
															</div>
														</div>
														<?php 
													}
														
												?>
												<?php 
													if(count($edit_data) == 0)
													{
														?>
														<div class="col-md-12 text-center">
															<img src="<?php echo base_url();?>assets/logo2.png" style="width:200px;height:200px;">
															<br><span style="font-size:14px;color:#979696;">No data found.</span><br><br>
														</div>
														
														<?php 
													} 
												?>			
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<?php
				}
				else if(isset($type) && $type == "ordersHistory")
				{
					?>
					<div --class="content content-cli-profile"><!-- Content start-->
						<?php 
							$page_data = array();
							echo $this->load->view("backend/consumers/profileHeader.php", $page_data, true);
						?>	
						<div --class="row">
							<div class="col-md-12 content-view-cutomer p-0">
								<?php 
									echo $this->load->view("backend/consumers/profileTabs.php", $page_data, true);
								?>
								<div class="x_content x_content1 detail-section-emp p-0 testup2">
									<div class="" role="tabpanel" data-example-id="togglable-tabs">
										<div class="col-md-12 client-details-new mt-4">
											<div class="row">
												<?php 	
												
													$i=0;
													foreach($edit_data as $row)
													{
														?>
														<div class="col-sm-6 col-md-6 col-lg-4" style="margin-left:-14px;">
															<div class="card bg-white p-3 shadow">
																<div class="d-flex justify-content-between">
																	<div class="user-info mt-0 mb-2">
																		<div class="user-info__basic">
																			<h5 class="mb-0" style="font-weight:500;">
																			Order Number :
																				<?php echo $row['order_number'];?>
																			</h5>
																		</div>
																	</div>
																</div>
																<div class="row">
																	<div class="col-md-6 col-sm-6">
																		<h5 class="mb-0"><?php CURRENCY_CODE;?> 
																			<span style="font-size:12px;">Date : 
																			<?php 
																				echo date('d-M-Y',strtotime($row['order_date']));
																			?>
																		</span>
																		</h5>
																		<!-- <h5>
																			<small>
																				<i class="fa fa-calendar"></i>
																				<?php echo date('M d , Y',$row['created_date']);?> at <?php echo date('h:i:s',$row['created_date']);?>
																			</small>
																		</h5> -->
																		<?php /* <h5>	
																			<small>
																				<i class="fa fa-clock-o"></i>
																				<?php echo date('h:i:s',$row['created_date']);?>
																			</small>
																		</h5> */ ?>
																	</div>
																	<div class="col-md-6 col-sm-6 text-right">
																		<span class="text-success font-weight-bold float-right">
																			<?php 
																				if ($row['order_status'] == 1) 
																				{
																					echo "Pending";
																				}
																				else if ($row['order_status'] == 2) 
																				{
																					echo "Accepted";
																				}
																				else if ($row['order_status'] == 3) 
																				{
																					echo "Processing";
																				}
																				else if ($row['order_status'] == 4) 
																				{
																					echo "Shipping";
																				}
																				else if ($row['order_status'] == 5) 
																				{
																					echo "Delivered";
																				}
																			?>	
																		</span><br>
																		
																		<h5>
																			<a title="View Permits" href="javascript:void(0);" data-toggle="modal" data-target="#OrderHistory<?php echo $row['header_id'];?>" style="color: #ffffff;font-weight: 600;font-size: 12px;background: #0d56a6;padding: 8px 13px;position: relative;top: 13px;border-radius: 5px;">	
																				View
																			</a>
																		</h5>
																		<div class="modal fade" id="OrderHistory<?php echo $row['header_id'];?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
																			<div class="modal-dialog modal-dialog-centered" role="document">
																				<div class="modal-content">
																					<div class="modal-header">
																						<h5 class="modal-title" id="exampleModalLabel">Order Details</h5>
																						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
																							<span aria-hidden="true">&times;</span>
																						</button>
																					</div>
																					
																					<div class="modal-body text-left">
																						<?php
																							$getOrdersQry = "select ord_order_lines.*,products.product_description from ord_order_lines 
																							left join products on products.product_id = ord_order_lines.product_id
																							where ord_order_lines.header_id='".$row['header_id']."' ";
																							$getOrders = $this->db->query($getOrdersQry)->result_array();
																							
																						?>
																						<!-- Supplier Vehicle start here-->
																						<div>
																							<b>Order Number </b> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: <?php //echo CURRENCY_SYMBOL;?> <?php echo $row['order_number'];?>
																						</div>
																						<div>
																							<b>Payment Method </b>:
																							<?php 
																								if ($row['payment_method'] == 1) 
																								{
																									$paymentMethod ="COD";
																								}
																								else if ($row['payment_method'] == 2) 
																								{
																									$paymentMethod ="CARD";
																								}
																								else if ($row['payment_method'] == 3) 
																								{
																									$paymentMethod ="WALLET";
																								}
																								else
																								{
																									$paymentMethod = "";
																								}
																								echo $paymentMethod;
																							?>	
																						</div>
																						<table class="table mt-2 table-bordered">
																							<thead>
																								<tr>
																									<th colspan="4">Products List</th>
																								</tr>
																								<tr>
																									<th class="text-center">#</th>
																									<th class="text-center">Product Name</th>
																									<th class="text-center">Amount(<?php echo CURRENCY_SYMBOL;?>)</th>
																								</tr>
																							</thead>
																							<tbody>
																								<?php 
																									$i=1;
																									foreach($getOrders as $products)
																									{
																										?>
																										<tr>
																											<td scope="row" class="text-center"><?php echo $i;?></td>
																											<td class="text-left"><?php echo $products["product_description"];?></td>
																											<td class="text-center">
																												<?php 
																													$linetotal = $products["order_quantity"] * $products["selling_price"];
																													echo number_format($linetotal,DECIMAL_VALUE,'.','');
																												?>
																											</td>
																											
																										</tr>
																										<?php 
																										$i++;
																									} 
																								?>
																								
																							</tbody>
																						</table>
																						<!-- Supplier Vehicle start here-->
																						
																					</div>	
																				</div>
																					
																				<!--<div class="modal-footer">
																					<button type="button" class="btn btn-light" data-dismiss="modal">Close </button>
																					<button type="submit" class="btn btn-primary ml-3">Submit </button>
																				</div>-->	
																			</div>
																		</div>
																	</div>
																</div>
																
															</div>
														</div>
														<?php 
														$i++;
													}
												?>
												<?php 
													if(count($edit_data) == 0)
													{
														?>
														<div class="col-md-12 text-center">
															<img src="<?php echo base_url();?>uploads/nodata.png">
														</div>
														<?php 
													} 
												?>			
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<?php
				}
				else if(isset($type) && $type == "favourite")
				{
					?>
					<?php 
						$page_data = array();
						echo $this->load->view("backend/consumers/profileHeader.php", $page_data, true);
					?>
					<div --class="row">
						<div class="col-md-12 content-view-cutomer p-0">
							<?php 
								echo $this->load->view("backend/consumers/profileTabs.php", $page_data, true);
							?>
							<div class="x_content x_content1 detail-section-emp p-0 testup" >
								<div class="" role="tabpanel" data-example-id="togglable-tabs">
									<div class="col-md-12 client-details-new mt-2">
										<div class="row">
											<div class="col-md-8">	
												<div class="card p-3">
												<h3 style="color:darkblue;">Favourite Orders:</h3>
													<div class="cli-card-new">
														<?php
															$LineQuery = "select 
																ord_favourite_orders.*,
																ord_order_headers.header_id,
																ord_order_headers.customer_id,
																ord_order_headers.order_number,
																cus_customers.customer_name,
																cus_customers.mobile_number,
																ord_order_headers.ordered_date,
																(ord_order_lines.quantity * ord_order_lines.price) as linetotal,
																sum(ord_order_lines.price * ord_order_lines.quantity) as bill_amount
																
																from ord_favourite_orders
																
															left join ord_order_headers on 
																ord_order_headers.header_id = ord_favourite_orders.header_id
															
															left join ord_order_lines on 
																ord_order_lines.header_id = ord_favourite_orders.header_id
																
															left join cus_consumers as cus_customers on 
																cus_customers.customer_id = ord_favourite_orders.customer_id

															where 
															ord_favourite_orders.header_id='".$id."'	
															
															";
														
															$LineData = $this->db->query($LineQuery)->result_array();
															//print_r($LineData);
														?>
														<table class="table table-bordered table-hover --table-striped mt-3">
															<thead>
																	
																<tr>
																	<th width="10%" class="text-center">Action</th>
																	<th class="text-center">Order Number</th>														
																	<!--<th>Customer Name</th>-->
																	<th class="text-center">Order Date</th>
																	<!--<th class="text-center">Mobile Number</th>-->
																	<th class="text-right">Total (<?php echo CURRENCY_CODE;?>)</th>
																</tr>
																<tbody>
																	<?php 
																		$i=1;
																		$subCancelledTotal = $subNotCancelledTotal = $subTotal = 0;
																		foreach($LineData as $lineItems)
																		{
																			
																			?>
																			<tr class="<?php //echo $cancel_status_class;?>">
																				<!--<td width="10px" class="text-center">
																					<?php //echo $i;?>
																				</td>-->
																				<td class="text-center">
																					<a target="_blank" href="<?php echo base_url();?>orders/viewOderDetails/<?php echo $lineItems['header_id'];?>" target="_blank" title="View Order"><i class="fa fa-eye"></i></a>
																				</td>
																			
																				<td class="text-center">
																					<?php echo ucfirst($lineItems['order_number']);?>
																				</td>

																				<!--<td>
																					<?php //echo ucfirst($lineItems['customer_name']);?>
																				</td>-->
																				<td class="text-center">
																					<?php echo $lineItems['ordered_date'];?>
																				</td>
																				
																				<!--<td class="text-center">
																					<?php //echo $lineItems['mobile_number'];?>
																				</td>-->
																				
																				<td class="text-right">
																					<?php echo number_format($lineItems['bill_amount'],DECIMAL_VALUE,'.','');?>
																				</td>

																			</tr>
																			<?php
																			$i++;
																			$subTotal += $lineItems['linetotal'];
																		} 
																	?>	
																	
																</tbody>
															</thead>
														</table>
														
													</div>
												</div>
											</div>
										</div>
									</div>	
								</div>
							</div>
						</div>
					</div>
					<?php
				}
				else if(isset($type) && $type == "loginHistory")
				{
					?>
					<div --class="content content-cli-profile"><!-- Content start-->
						<?php 
							$page_data = array();
							echo $this->load->view("backend/customer/profileHeader.php", $page_data, true);
						?>	
						<div --class="row">
							<div class="col-md-12 content-view-cutomer p-0">
								<?php 
									echo $this->load->view("backend/customer/profileTabs.php", $page_data, true);
								?>
								
								<div class="" --role="tabpanel" --data-example-id="togglable-tabs">
									<div class="mt-4">
										<form action="" method="get">
											<?php /* <div class="col-md-12 row">
												<div class="col-md-3 mb-2 ml-0">
													<select name="device_type" id="device_type" class="form-control">
														<option value=""> - Select Application Type -</option>
														<?php 
															foreach($this->application_type_records as $application_type_key => $application_type_value) 
															{ 
																$selected="";
																if( isset($_GET['device_type']) && !empty($_GET['device_type']) && $_GET['device_type'] == $application_type_key )
																{
																	$selected ="selected='selected'";
																}
																?>
																<option value="<?php echo $application_type_key?>" <?php echo $selected;?>><?php echo ucfirst($application_type_value);?></option>
																<?php
															}
														?>
													</select>
												</div>
												<div class="col-md-3 mb-2 ml-0">
													<select name="os_type" id="os_type" class="form-control">
														<option value=""> - Select OS Type -</option>
														<?php 
															foreach($this->os_type as $key => $value) 
															{ 
																$selected="";
																if( isset($_GET['os_type']) && !empty($_GET['os_type']) && $_GET['os_type'] == $key )
																{
																	$selected ="selected='selected'";
																}
																?>
																<option value="<?php echo $key?>" <?php echo $selected;?>><?php echo ucfirst($value);?></option>
																<?php
															}
														?>
													</select>
												</div>
												<div class="col-md-3">
													<button type="submit" class="btn btn-info waves-effect">Search <i class="fa fa-search" aria-hidden="true"></i></button>
												</div>
											</div>*/?>
										</form>
										
										<div class="col-md-12 mt-3">
											<div class="new-scroller">
												<table id="myTable" class="table table-bordered table-hover dataTable">
													<thead>
														<tr>
															<th onclick="sortTable(2)">Device Type</th>
															<th onclick="sortTable(2)">OS Type</th>
															<th onclick="sortTable(2)">Login Date</th>
														</tr>
													</thead>
													<tbody>
														<?php 	
															$i=0;
															foreach($edit_data as $row)
															{
																?>
																<tr>
																	<td>
																		<?php 
																			foreach ($this->application_type_records as $key => $value) 
																			{
																				if ($row['device_type'] == $key) 
																				{
																					echo $value;
																				}
																			}
																		?>
																	</td>
																	<td>
																		<?php 
																		if(!empty($row['os_type']))
																		{	
																			foreach ($this->os_type as $key => $value) 
																			{
																				if ($row['os_type'] == $key) 
																				{
																					echo $value;
																				}
																			}
																		}
																		else
																		{
																			echo "--";
																		}
																		?>
																	</td>
																	<td><?php echo date('M d , Y',$row['login_date']);?> at <?php echo date('h:i:s, A',$row['login_date']);?></td>
																</tr>
																<!-- edit Contacts end -->
																<?php 
																$i++;
															}
														?>
													</tbody>
												</table>
												<?php 
													if(count($edit_data) == 0)
													{
														?>
														<p class="admin-no-data">No data found.</p>
														<?php 
													} 
												?>
											</div>
										</div>	
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
										
										<!--End right side div col-md-9-->
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
					</div>

					<!-- filters-->
					<form action="" class="form-validate-jquery" method="get">
						<div class="row">
							<div class="col-md-4">
								<div class="row">
									<label class="col-form-label col-md-4">Customer Name <!-- <span class="text-danger">*</span> --></label>
									<div class="form-group col-md-8">
										<?php 
											$customersQry = "select customer_id,customer_number,customer_name from cus_consumers as cus_customers 
														
														order by cus_customers.customer_number asc";

											$getCustomers = $this->db->query($customersQry)->result_array();	
										?>
										<select name="customer_id" id="customer_id" --required class="form-control searchDropdown">
											<option value="">- Select -</option>
											<?php 
												foreach($getCustomers as $row)
												{
													$selected="";
													if(isset($_GET['customer_id']) && $_GET['customer_id'] == $row["customer_id"] )
													{
														$selected="selected='selected'";
													}
													?>
													<option value="<?php echo $row["customer_id"];?>" <?php echo $selected;?>><?php echo ucfirst($row["customer_number"]);?> | <?php echo ucfirst($row["customer_name"]);?></option>
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
							
							<div class="col-md-4">
								<div class="row">
									<label class="col-form-label col-md-4">Mobile Number</label>
									<div class="form-group col-md-7">
										<input type="search" name="mobile_number" maxlength="10" class="form-control mobile_vali" value="<?php echo !empty($_GET['mobile_number']) ? $_GET['mobile_number'] :""; ?>" placeholder="Mobile Number" autocomplete="off">
									</div>
								</div>
							</div>
						</div>

						<div class="row mt-2">
							<div class="col-md-4">
								
							</div>
							
							<div class="col-md-4">
								
							</div>

							<div class="col-md-4" style="padding:0px 4px 2px 39px;">
							     <a href="<?php echo base_url(); ?>consumers/ManageCustomer" title="Clear" class="btn btn-default">Clear</a>&nbsp;
								<button type="submit" class="btn btn-info">Search <i class="fa fa-search" aria-hidden="true"></i></button>
								
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

							<div class="new-scroller">
								<table id="myTable" class="table table-bordered table-hover --table-striped dataTable">
									<thead>
										<tr>
											<th class="text-center">Controls</th>
											<th>Customer Number</th>
											<th>Customer Name</th>
											<th>Mobile Number</th>
											<th>Created Date</th>
											<th class="text-center">Status</th>
										</tr>
									</thead>
									<tbody>
										<?php 	
											$i=0;
											foreach($resultData as $row)
											{
												?>
												<tr>
													<td style="width: 12%;" class="text-center">
														<div class="dropdown" style="display: inline-block;--padding-right: 10px!important;width:92px;">
															<button type="button" class="btn btn-outline-primary gropdown-toggle waves-effect waves-light btn-sm" data-toggle="dropdown" aria-expanded="false">
																Action <i class="fa fa-angle-down"></i>
															</button>
															<ul class="dropdown-menu dropdown-menu-right">
																<?php
																	if($consumerListingMenu['create_edit_only'] == 1 || $consumerListingMenu['read_only'] == 1 || $this->user_id == 1)
																	{
																		?>
																		<?php /*
																		<li>
																			<a href="<?php echo base_url(); ?>customer/ManageCustomer/edit/<?php echo $row['customer_id'];?>">
																				<i class="fa fa-edit"></i> Edit
																			</a>
																		</li>
																		*/ ?>

																		<?php
																			if($consumerListingMenu['read_only'] == 1 || $this->user_id == 1)
																			{
																				?>
																				<li>
																					<a href="<?php echo base_url(); ?>consumers/ManageCustomer/view/<?php echo $row['customer_id'];?>">
																						<i class="fa fa-eye"></i> View 
																					</a>
																				</li>
																				<?php 
																			} 
																		?>

																		<?php
																			if($consumerListingMenu['create_edit_only'] == 1 || $this->user_id == 1)
																			{
																				?>
																				<li>
																					<?php 
																						if($row['active_flag'] == $this->active_flag)
																						{
																							?>
																							<a class="unblock" href="<?php echo base_url(); ?>consumers/ManageCustomer/status/<?php echo $row['customer_id'];?>/N" title="Inactive">
																								<i class="fa fa-ban"></i> Inactive
																							</a>
																							<?php 
																						} 
																						else
																						{  ?>
																							<a class="block" href="<?php echo base_url(); ?>consumers/ManageCustomer/status/<?php echo $row['customer_id'];?>/Y" title="Active">
																								<i class="fa fa-ban"></i> Active
																							</a>
																							<?php 
																						} 
																					?>
																				</li>
																				<?php 
																			} 
																		?>
																		<!-- <li>
																			<a href="<?php echo base_url(); ?>customer/ManageCustomer/addresslist/<?php echo $row['customer_id'];?>">
																				<i class="fa fa-eye"></i> Customer Address
																			</a>
																		</li>
																		<li>
																			<a href="<?php echo base_url(); ?>customer/ManageCustomer/wallet/<?php echo $row['customer_id'];?>">
																				<i class="fa fa-eye"></i> Customer Wallet
																			</a>
																		</li> -->
																		
																		<?php 
																	} 
																?>
															</ul>
														</div>
													</td>
													<td><?php echo $row['customer_number'];?></td>
													<td><?php echo ucfirst($row['customer_name']);?></td>
													<td><?php echo $row['mobile_number'];?></td>
													<td>
														<?php 
															echo date(DATE_FORMAT." ".$this->time,strtotime($row['created_date']));
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
										<div class="col-md-12 float-left text-center"> 
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
							<?php 
						} 
					?>
					<?php 
				} 
			?>
		</div><!-- Card body end-->
	</div><!-- Card end-->
	<?php /*
	<?php if(isset($type) && $type =='view'){?>
		<div class="row">
			<div class="col-md-10">
			</div>
			<div class="col-md-2 text-right">
				<a href='<?php echo base_url();?>customer/ManageCustomer' class='btn btn-outline-danger'>
					<i class="fa fa-chevron-circle-left"></i> Back
				</a>
			</div>
		</div>
	<?php } ?>
	*/ ?>
</div><!-- Content end-->


<!-- View Popup Image-->	
<link href="<?php echo base_url();?>assets/backend/view_gallery/jquery.magnify.css" rel="stylesheet">
<script src="<?php echo base_url();?>assets/backend/view_gallery/jquery.magnify.js"></script>
<script>
   /*  $('[data-magnify]').magnify({
      fixedContent: true
    }); */
</script>
<!-- View Popup Image-->

<!--Employee Phone-->
<script type="text/javascript">  
	$('document').ready(function()
	{
		//Customer E-mail Start here
		$(".register-but").removeClass("disabled-class");
		
		var emp_email_state = false;

		$('#mobile_number').on('input', function()
		{
			var email = $('#mobile_number').val();
			
			if (email == '') 
			{
				emp_mob_state = false;
				return;
			}
			else
			{
				$.ajax({
					url: '<?php echo base_url();?>employee/MobileExist',
					type: 'post',
					data: {
						'mob_check' : 1,'email' : email,
					},
					success: function(response)
					{
						if (response == 'taken' ) 
						{
							emp_mob_state = false;
							
							/* $('.form-control.email').removeClass("valid");
							$('.form-control.email').addClass("error");
							
							$(".form-control.email").attr("aria-required", "true");
							$(".form-control.email").attr("aria-describedby", "email-error");
							$(".form-control.email").attr("aria-invalid", "true"); */
							
							$(".mobile_number_exist").addClass("error");
							$(".mobile_number_exist").attr("id", "email-error");
							$(".mobile_number_exist").attr("style", "display: inline;");
							
							$(".register-but").attr("disabled", "disabled=disabled");
							$(".register-but").addClass("disabled-class");
							$('.mobile_number_exist').html('Sorry... Mobile Number already taken');
							
							return false;
						}
						else if (response == 'not_taken') 
						{
							$(".mobile_number_exist").attr("style", "display: none;");
							$(".register-but").removeAttr("disabled", "disabled=disabled");
							$(".register-but").removeClass("disabled-class");
							return true;
						}
					}
				});
			}
		});
		//Customer E-mail End here
	});
</script>
<!--Employee Phone-->
<script type="text/javascript">  
	$('document').ready(function()
	{
		//Customer E-mail Start here
		$(".register-but").removeClass("disabled-class");
		
		var emp_email_state = false;

		$('#emp_email').on('input', function()
		{
			var email = $('#emp_email').val();
			
			if (email == '') 
			{
				emp_email_state = false;
				return;
			}
			else
			{
				$.ajax({
					url: '<?php echo base_url();?>employee/EmailExist',
					type: 'post',
					data: {
						'email_check' : 1,'email' : email,
					},
					success: function(response)
					{
						if (response == 'taken' ) 
						{
							emp_email_state = false;
							
							/* $('.form-control.email').removeClass("valid");
							$('.form-control.email').addClass("error");
							
							$(".form-control.email").attr("aria-required", "true");
							$(".form-control.email").attr("aria-describedby", "email-error");
							$(".form-control.email").attr("aria-invalid", "true"); */
							
							$(".employee_email_exist_error").addClass("error");
							$(".employee_email_exist_error").attr("id", "email-error");
							$(".employee_email_exist_error").attr("style", "display: inline;");
							
							$(".register-but").attr("disabled", "disabled=disabled");
							$(".register-but").addClass("disabled-class");
							$('.employee_email_exist_error').html('Sorry... Email already taken');
							
							return false;
						}
						else if (response == 'not_taken') 
						{
							$(".employee_email_exist_error").attr("style", "display: none;");
							$(".register-but").removeAttr("disabled", "disabled=disabled");
							$(".register-but").removeClass("disabled-class");
							return true;
						}
					}
				});
			}
		});
		//Customer E-mail End here
	});
</script>

<script type="text/javascript">  
	$('document').ready(function()
	{
		$(".register-but").removeClass("disabled-class");
		
		var mobile_number_state = false;

		$('#mobile_number').on('input', function()
		{
			var mobile_number = $('#mobile_number').val();
			var staff_role = $('#staff_role').val();
			
			if (mobile_number == '') 
			{
				mobile_number_state = false;
				return;
			}
			else
			{
				$.ajax({
					url: '<?php echo base_url();?>admin/MobileNumberExist',
					//url: '<?php echo base_url();?>admin/EmailExist',
					type: 'post',
					data: {
						'mobile_number_check' : 1,'mobile_number' : mobile_number,'register_type' : 1,
					},
					success: function(response)
					{
						if (response == 'taken' ) 
						{
							mobile_number_state = false;
							
							/* $('.form-control.email').removeClass("valid");
							$('.form-control.email').addClass("error");
							
							$(".form-control.email").attr("aria-required", "true");
							$(".form-control.email").attr("aria-describedby", "email-error");
							$(".form-control.email").attr("aria-invalid", "true"); */
							
							$(".mobile_number_exist").addClass("error");
							$(".mobile_number_exist").attr("id", "email-error");
							$(".mobile_number_exist").attr("style", "display: inline;");
							
							$(".register-but").attr("disabled", "disabled=disabled");
							$(".register-but").addClass("disabled-class");
							$('.mobile_number_exist').html('Sorry... Mobile Number already Exist!');
							
							return false;
						}
						else if (response == 'not_taken') 
						{
							
							$(".mobile_number_exist").attr("style", "display: none;");
							$(".register-but").removeAttr("disabled", "disabled=disabled");
							$(".register-but").removeClass("disabled-class");
							return true;
						}
					}
				});
			}
		});
	});
</script>

<script>
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
	.testup {
  /* Default styles */
}

@media (min-width: 768px) {
  /* Styles for large screens and above */
  .testup {
    margin-left: -17px;
  }
}

@media (max-width: 768px) {
  /* Styles for large screens and above */
  .testup {
    margin-left: -17px;
  }
}

.testup1 {
  /* Default styles */
}

@media (min-width: 768px) {
  /* Styles for large screens and above */
  .testup1 {
    margin-left: -5px;
  }
}

.testup2 {
  /* Default styles */
}

@media (min-width: 768px) {
  /* Styles for large screens and above */
  .testup2 {
    margin-left: -14px;
  }
}

h2.cli-other-infos {
    font-size: 15px !important;
    color: #484848 !important;
    font-weight: 600 !important;
    background: #fbfbfb !important;
    margin: 0 !important;
    /* padding: 5px 5px !important; */
	padding:20px;
    border-top: 1px solid #ededf5 !important;
    border-right: 1px solid #ededf5 !important;
    border-left: 1px solid #ededf5 !important;
    border-top-right-radius: 5px !important;
    border-top-left-radius: 5px !important;
}
</style>
