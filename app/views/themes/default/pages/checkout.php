<style>
	.button-disabled-new {
		pointer-events: none;
	}
</style>
<body class="grocino-home home2 grocino-checkout">
	<form action="" id="formValidation" method="post" enctype="multipart/form-data">
		<div class="checkout-section mt-5">
			<div class="container">
				<div class="row">
					<div class="grocino-heading">
						<h1 class="heading_text"> Checkout </h1> 
						<div class="graph graph-sm">
							<img src="<?php echo base_url();?>assets/frontend/img/about/graphic.png" alt="Graph" title="Graph" class="img-fluid" />
						</div>
					</div>
				</div>
				<?php
					$getDeliveryAddress = $this->db->query('select cus_customer_address.*,
					customer.customer_name,
					customer.mobile_number 
					from cus_customer_address
					
					left join customer on customer.customer_id = cus_customer_address.customer_id

					where customer.customer_id="'.$this->UserID.'" order by customer_address_id desc')->result_array();
				?>
				<div class="row" >
					<div class="col-lg-5 col-md-5 col-sm-12 pr-lg-5">	
						<form action="" id="formValidation" method="post" enctype="multipart/form-data" novalidate="novalidate">
							<div class="row mb-4" style="margin-top:-15px;">
								
								<h2 class="col-lg-6 col-md-12 col-sm-12 mt-4 mb-lg-0 mb-md-2">Delivery Address </h2>
								<span class="address-field-new" style="margin-top:20px;position:relative;">
									<a href="javascript:void(0);" data-toggle="modal" data-target="#exampleModal" class="btn btn-cancel">
										<i class="fa fa-plus"></i> Add New Address
									</a>
								</span> 
								<div class="address-container">
								<?php 
									if(count($getDeliveryAddress) > 0)
									{
										$i=1;
										foreach($getDeliveryAddress as $row)
										{
											if($i == 1)
											{
												$checked="checked='checked'";
											}
											else
											{
												$checked="";
											}
											?>
											<!-- <div class="address-container"> -->
											<span class="address-field-new mt-3 ">
												<div class="col-md-12">
													<div class="row">
														<div class="col-md-10">	
															<div class="form-group address-deliver " style="text-align:left">
																<input type="radio" <?php echo $checked;?> name="delivery_address_id" value="<?php echo $row['customer_address_id'];?>">
																<?php if(!empty($row['door_number'])){?>
																	<span>
																		<?php echo $row['door_number'];?>, <?php echo $row['building_number'];?>
																	</span>
																<?php } ?> <br>
													
																<span> 
																	<?php echo ucfirst($row['address1']);?> 
																</span><br>
																
																<?php if(!empty($row['address2'])){?>
																	<span>
																		<?php echo ucfirst($row['address2']);?>
																	</span><br>
																<?php } ?>
																	
																<?php if(!empty($row['land_mark'])){?>
																	<span> 
																		<?php echo ucfirst($row['land_mark']);?>
																	</span> <br>
																<?php } ?> 
																
																<?php if(!empty($row['postal_code'])){?>
																<span>
																	<?php echo $row['postal_code'];?>
																	</span><br>
																<?php } ?> 
																	
																<span> 		
																	<?php if(!empty($row['mobile_number'])){?>
																		Mobile Number : <?php echo $row['mobile_number'];?>
																	<?php } ?>
																</span>
																
															</div>
														</div>
														<div class="col-md-2">
															<a href="javascript::void(0);" class="btn btn-cancel modal_dialog" data-toggle="modal" data-target="#exampleModal<?php echo $row['customer_address_id'];?>" >
																Edit
															</a>
														</div>
													</div>
												</div>
											</span>
											<!-- </div> -->
											<style>
											 .address-container {
												max-height: 400px; /* Set the desired height */
												overflow-y: auto;
												border: 1px solid #ccc; /* Optional: Add a border for visibility */
												padding: 10px; /* Optional: Add padding for spacing */
											}
											
											.address-field-new {
												display: block;
												margin-top: 10px;
											}
											</style>
										
												<!-- Modal item order -->
											<div class="modal fade" id="exampleModal<?php echo $row['customer_address_id'];?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel<?php echo $row['customer_address_id'];?>" aria-hidden="true">
												<div class="modal-dialog" role="document">
													<div class="modal-content">
														<div class="modal-header new-model-popup">
															<h5 class="modal-title" id="exampleModalLabel">Edit Delivery Address</h5>
															<button type="button" class="close new-close-popup" style="background-color:white;" data-dismiss="modal" aria-label="Close">
																<span aria-hidden="true" style="font-size:27px;">&times;</span>
															</button>
														</div>
														<input type="hidden" name="customer_address_id" value="<?php echo $row['customer_address_id'];?>"/>
														<form action="" method="post">
															<div class="modal-body">
																	
																<div class="row">
																	<div class="form-group col-md-6">
																		<label class="col-form-label">Door No / Flat No   <span class="text-danger">*</span></label>
																		<input type="text" name="door_number" value="<?php echo ucfirst($row['door_number']);?>" id="door_number" autocomplete="off" class="form-control" required />
																	</div>
																	<div class="form-group col-md-6">
																		<label class="col-form-label">Building No / Name  </label>
																		<input type="text" name="building_number" autocomplete="off" value="<?php echo ucfirst($row['building_number']);?>" id="building_number" class="form-control"  />
																	</div>
																</div>
																
																
																<div class="row">
																	<div class="form-group col-md-6">
																		<label class="col-form-label"> Address Line 1  <span class="text-danger">*</span></label>
																		<input type="text" name="address_line_1" value="<?php echo ucfirst($row['address1']);?>" id="address1" class="form-control" />
																	</div>
																	<div class="form-group col-md-6">
																		<label class="col-form-label">Address Line 2 </label>
																		<input type="text" name="address_line_2" value="<?php echo ucfirst($row['address2']);?>" id="address2" class="form-control" />
																	</div>
																	
																</div>
																<div class="row">
																	
																	<div class="form-group col-md-6">
																		<label class="col-form-label">Landmark (Optional)</label>
																		<input type="text" autocomplete="off" name="land_mark" placeholder="" id="land_mark" value="<?php echo $row['land_mark'];?>" class="form-control" />
																	</div>
																	<div class="form-group col-md-6">
																		<label class="col-form-label">Postal Code  <span class="text-danger">*</span></label>
																		<input type="text" autocomplete="off" required name="postal_code" placeholder="" id="postal_code" value="<?php echo $row['postal_code'];?>"  class="form-control mobile_vali" />
																	</div>
																</div>
																<div class="row">
																	<?php 
																		$UserDetails = "select customer_id,mobile_number,country_id,otp_status from customer where customer_id ='".$this->UserID."'";
																		$getUserDetails = $this->db->query($UserDetails)->result_array();
																		
																		$mobile_number = isset($getUserDetails[0]['mobile_number']) ? $getUserDetails[0]['mobile_number'] :"";
																	?>
																	<div class="form-group col-md-6">
																			
																		<?php 
																			$verifiedStatus = $this->db->query("select otp_status from  customer where customer_id = ".$this->UserID)->result_array();
																			
																			$disabledatttr	="";
																			$readonlyatttr	="";
																			if ($verifiedStatus[0]['otp_status'] == 1) 
																			{
																				
																				$disabledatttr = "disabled";
																				$readonlyatttr = "readonly";
																			}
																		?>
																		
																		<label class="col-form-label">Mobile Number <span class="text-danger">*</span></label>
																		<input type="text" <?php echo $readonlyatttr ?> required autocomplete="off" placeholder="2345 8734" name="alternative_number" value="<?php echo !empty($row['alternative_number']) ? $row['alternative_number'] : $mobile_number ;?>" id="alternative_number" class="form-control" />
																	</div>
																</div>
															</div>
															
															<div class="modal-footer mt-0 mb-0">
																<div class="col-md-12">
																	<div class="popup-command-btns">
																		<div class="col-md-4 float-right text-right pull-right">
																			<button type="submit" name="addressUpdate" class="btn btn-danger pull-right">Save </button>
																		</div>
																	</div>
																</div>
															</div>
														</form>
													</div>
												</div>
											</div>
											<!-- Modal item order -->
											<?php 
											$i++;
										}
									}
									else
									{
										/* ?>
										
										<span class="address-field-new">
											<a href="javascript:void(0);" data-toggle="modal" data-target="#exampleModal" class="btn btn-cancel">
												<i class="fa fa-plus"></i> Add New Address
											</a>
										</span>
										<?php */
									}								
								?>
								</div>
							
								<!-- Modal item order -->
								<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
									<div class="modal-dialog" role="document">
										<div class="modal-content">
											<div class="modal-header new-model-popup">
												<h5 class="modal-title" id="exampleModalLabel">Add Delivery Address</h5>
												<button type="button" class="close new-close-popup" data-dismiss="modal" aria-label="Close">
													<span aria-hidden="true">&times;</span>
												</button>
											</div>
											
											<form action="" method="post" id="addaddressFrom">
												<div class="modal-body">
													
													<!-- <input type="hidden" name="pin_code" value="" id="pin_code" class="form-control"/> -->
													<?php 
														if(isset($getDeliveryAddress[0]['customer_name']) && !empty($getDeliveryAddress[0]['customer_name']))
														{
															
														}
														else
														{
															?>
															<div class="row">
																<div class="form-group col-md-6">
																	<label class="col-form-label">First Name <span class="text-danger">*</span></label>
																	<input type="text" required name="customer_name" id="customer_name" autocomplete="off" class="form-control" />
																</div>
															</div>
															<?php
														}
													?>
													
													<div class="row">
														<div class="form-group col-md-6">
															<label class="col-form-label">Door No / Flat No <span class="text-danger">*</span> </label>
															<input type="text" name="door_number" id="door_number" autocomplete="off" class="form-control" required />
														</div>
														<div class="form-group col-md-6">
															<label class="col-form-label">Building No / Name </label>
															<input type="text" name="building_number" id="address" autocomplete="off" class="form-control" />
														</div>
													</div>
													<div class="row">
														<div class="form-group col-md-6">
															<label class="col-form-label"> Address Line 1  <span class="text-danger">*</span></label>
															<input type="text" name="address_line_1" id="address_line_1" autocomplete="off" class="form-control" required />
														</div>
														<div class="form-group col-md-6">
															<label class="col-form-label">Address Line 2</label>
															<input type="text" name="address_line_2" id="address_line_2" autocomplete="off" class="form-control" />
														</div>
													</div>
													<div class="row">
														<div class="form-group col-md-6">
															<label class="col-form-label">Landmark (Optional)</label>
															<input type="text" autocomplete="off" name="land_mark" placeholder="" id="land_mark" value="" class="form-control" />
														</div>
														<div class="form-group col-md-6">
															<label class="col-form-label">Postal Code <span class="text-danger">*</span></label>
															<input type="text" autocomplete="off" minlength="6" required maxlength="6" name="postal_code" placeholder="" id="postal_code" value="" class="form-control mobile_vali" />
														</div>
														<?php 
															$UserDetails = "select customer_id,mobile_number,otp_status from customer where customer_id ='".$this->UserID."'";
															$getUserDetails = $this->db->query($UserDetails)->result_array();
															
															$country_id = isset($getUserDetails[0]['country_id']) ? $getUserDetails[0]['country_id'] : 0;
															$mobile_number = isset($getUserDetails[0]['mobile_number']) ? $getUserDetails[0]['mobile_number'] :"";

															$disabledatttr	="";
															$readonlyatttr	="";
															if ($getUserDetails[0]['otp_status'] == 1) 
															{
																?>
																<input type="hidden" name="country_id" value="<?php echo isset($edit_data[0]['country_id']) ? $edit_data[0]['country_id'] : $country_id ?>">
																<?php
																$disabledatttr = "disabled";
																$readonlyatttr = "readonly";
															}
														?>
														<div class="form-group col-md-6" id="country_mobile_box">
															
																<label class="col-form-label">Mobile Number <span class="text-danger">*</span></label>
																<input type="text" <?php echo $readonlyatttr ?> required autocomplete="off" value="<?php echo $mobile_number;?>" placeholder="2345 8734" name="alternative_number" value="" id="alternative_number" class="form-control" />
															
														</div>
													</div>
												</div>
												
												<div class="modal-footer mt-0 mb-0">
													<div class="col-md-12">
														<div class="popup-command-btns">
															<div class="row">
																<div class="col-md-4 offset-4">
																	<!-- <a href="javascript:void(0)" class="btn btn-cancel mb-0" data-dismiss="modal">Close </a> -->
																</div>
																<div class="col-md-4">
																	<button type="submit" name="addressAdd" id="addressAdd" class="btn btn-danger pull-right">Save </button>
																</div>
															</div>
														</div>
													</div>
												</div>
											</form>
										</div>
									</div>
								</div>
								<!-- Modal item order -->
							
							</div>

							<div class="row">
								<div class="form-group col-xl-12 col-lg-12 col-md-12 col-sm-12">
									<label class="-d-none pb-2" for="input-msg"><h2 style="margin-top:10px;position:relative;"> Remarks </h2></label>
									<textarea id="input-msg" style="position:relative;margin-top:20px;" rows="1" class="form-control" name="remarks" placeholder="Remarks"></textarea>
								</div>
							</div>
 
							
						</form>
					</div>
					<style>
						@media only screen and (max-width: 976px) {
							#tecta {
								margin-top:20px;
							}
							}
					</style>
					<div class="col-lg-2 col-md-2 col-sm-0 ">
					</div>
					<div class="col-lg-5 col-md-5 col-sm-12 pl-lg-5 mb-2">
								<h2 class="col-lg-6 col-md-12 col-sm-12 mb-2 mb-lg-0 mb-md-2" id="tecta">Contact information </h2>
								<div class="customer-name-showing mt-2 mb-4">
									<?php 
										if(isset($this->UserID) && !empty($this->UserID))
										{
											$randomUserId = $this->db->query('select customer_id,customer_name, mobile_number from customer where customer_id="'.$this->UserID.'" and active_flag = "Y" ')->result_array();
											?>
												<span style="float:left;width:100%;padding-bottom:5px;">
													<i class="uil uil-user"></i><?php echo isset($randomUserId[0]['customer_name']) ? $randomUserId[0]['customer_name'] :"";?>
												</span>
												<span>
													<i class="uil uil-phone"></i><?php echo isset($randomUserId[0]['mobile_number']) ? $randomUserId[0]['mobile_number'] :"";?>
												</span>
											<?php 
										}
										else
										{
											?>
												
											<?php 
										}
									?>
								</div>
						
						<?php
							$subTotal = 0;
							$cartQty = 0;
							$orderAmount = $totorderAmount = 0;
							if(isset($_SESSION["cart_item"]))
							{
								foreach($_SESSION["cart_item"] as $code => $item)
								{
									$cartQty = $item["quantity"];
									$orderAmount = $item["quantity"] * $item["price"];

									$totorderAmount += $orderAmount;
									
									$product_id = $item["product_id"];
									$productDetails = $this->db->query("SELECT product_image,product_description,product_id FROM products WHERE product_id='" . $product_id . "'")->result_array();
									$product_image = $productDetails[0]['product_image'];
									
								}
							}
						?>
						
						<div class="cart-detail mt-2">
							<p style="padding:0px;font-weight:500;color:black;">Sub Total <span><?php echo CURRENCY_SYMBOL;?> <?php echo number_format($totorderAmount,DECIMAL_VALUE,'.','');?></span></p>
							<p style="padding:0px;font-weight:500;color:black;">Delivery Charge <span><?php echo CURRENCY_SYMBOL;?> <?php echo number_format($totorderAmount,DECIMAL_VALUE,'.','');?></span></p>

							<hr >
							<input type="hidden" name="payable_amount" id="payable_amount" value="<?php echo number_format($totorderAmount,DECIMAL_VALUE,'.','');?>">
							<p style="font-weight:500;color:black;">Total Price <span><?php echo CURRENCY_SYMBOL;?> <?php echo number_format($totorderAmount,DECIMAL_VALUE,'.','');?></span></p>
							<p class="read"><span style="font-weight:500;">Note:<span style="font-weight:400;"><span> The Listed Prices Are Tax-Inclusive For Your Convenience.<span></p>

							<h2>Select Payment Method</h2>
							<ul class="custom_radio">
								
								<?php 
									$PaymentModeQuery = "select * from expense_payment_type where payment_type_status = 1
										
											order by sequence_number asc";
									$getPaymentMode = $this->db->query($PaymentModeQuery)->result_array();
									
									if(count($getPaymentMode)>0)
									{
										foreach($getPaymentMode as $paymentMode)
										{	
											$checked = '';
											if($paymentMode['default_payment'] == 1)
											{
												$checked = 'checked';
												$checked_id = $paymentMode['payment_type_id'];
											}
											?>
											<li>
												
												<?php echo ucfirst($paymentMode['payment_type']);?>
												<input type="radio" required value="<?php echo $paymentMode['payment_type_id'];?>" class="checkpayment_method<?php echo $paymentMode['payment_type_id'];?>" name="payment_method" <?php echo $checked;?>>
												<span class="checkmark checkmark<?php echo $paymentMode['payment_type_id'];?>"></span>
												
											</li>
											<?php
										} 
									} 
								?>
							</ul>
							<?php 
					if($totorderAmount > 0)
					{
						$disable = "";
					}
					else
					{
						$disable = "button-disabled-new";
					}
				?>
				<div class="buttons">
					<input type="submit"  name="place_order" value="Place Order" class="btn btn-lg btn-orange <?php echo $disable;?>"/>
				</div>
						</div>
					</div>
					
				</div>
				
			</div>
		</div>
	</form>
</body>