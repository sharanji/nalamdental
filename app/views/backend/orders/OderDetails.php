
<script src="<?php echo base_url();?>assets/backend/jspm/JSPrintManager.js"></script>
<?php /* <script src="<?php echo base_url();?>assets/backend/jspm/zip-full.min.js"></script>*/ ?>

<script>
    var clientPrinters = null;
	var _this = this;
	
	JSPM.JSPrintManager.license_url = "<?php echo base_url();?>jspm/index.php";
	
	//WebSocket settings
	JSPM.JSPrintManager.auto_reconnect = true;
	JSPM.JSPrintManager.start();

    function jspmWSStatus() 
    {
        if (JSPM.JSPrintManager.websocket_status == JSPM.WSStatus.Open)
        {   
            return true;
        }
        else if (JSPM.JSPrintManager.websocket_status == JSPM.WSStatus.Closed) 
        {
            console.warn('JSPrintManager (JSPM) is not installed or not running! Download JSPM Client App from https://neodynamic.com/downloads/jspm');
            return false;
        }
        else if (JSPM.JSPrintManager.websocket_status == JSPM.WSStatus.Blocked) 
        {
            alert('JSPM has blocked this website!');
            return false;
        }
    }
</script>

<div class="content"><!-- Content start-->
	<div class="card"><!-- Card start-->
		<div class="card-body">
			<?php
				$query = "select 
					header_tbl.*,
					header_tbl.payment_type as header_payment_type,
					branch.branch_name,
					branch.email,
					branch.map_location,
					payment_type.payment_type as payment_method,
					customer.customer_name,
					customer.mobile_number,
					country.country_code,
					customer_address.address_name,
					customer_address.address1,
					customer_address.address2,
					customer_address.address3,
					customer_address.land_mark,
					customer_address.address_type,
					customer_address.postal_code,
					header_tbl.cancel_status,
					header_tbl.paid_status,
					header_tbl.card_number,
					
					sum(line_tbl.price) as price,
					sum(line_tbl.price * line_tbl.quantity) as bill_amount,

					customer.customer_name as pos_customer_name,
					customer.mobile_number as pos_mobile_number,
					customer.address1 as pos_address1,
					customer.address2 as pos_address2,
					customer.address3 as pos_address3,
					customer.postal_code as pos_postal_code,
					CONCAT(din_tbl.table_code,coalesce(header_tbl.sub_table,'')) as table_name,
					per_people_all.first_name as waiter_name
			
					from ord_order_headers as header_tbl
			
					left join ord_order_lines as line_tbl on line_tbl.header_id = header_tbl.header_id

					left join per_user on per_user.user_id = header_tbl.customer_id


					left join cus_consumers as customer on customer.customer_id = per_user.reference_id

					left join pay_payment_types as payment_type on payment_type.payment_type_id = header_tbl.payment_method
					left join branch on branch.branch_id = header_tbl.branch_id
					left join cus_customer_address as customer_address on customer_address.customer_address_id = header_tbl.address_id
					left join geo_countries as country on country.country_id = customer.country_id


					left join cus_consumers as pos_customer on pos_customer.customer_id = header_tbl.customer_id


					left join din_table_lines as din_tbl on din_tbl.line_id = header_tbl.table_id
					left join per_user as waiter on waiter.user_id = header_tbl.waiter_id
					left join per_people_all on per_people_all.person_id = waiter.person_id
					
					WHERE header_tbl.header_id = $id
					group by line_tbl.header_id
					order by header_tbl.header_id desc";
					
				$headerData = $this->db->query($query)->result_array();
				
				foreach($headerData as $row)
				{
					?>
					<div class="printview-order" id="printableArea">
						<h3 class="text-center" >
							Order Details
						</h3>
						 
						<hr>
						<div class="row">
							<div class="col-md-12">
								<div class="col text-right">
									
									<a href="javascript:void(0);" data-toggle="modal" data-target="#exampleModal<?php echo $row['header_id'];?>" title="Add Customer GST No" class="btn btn-success btn-sm">
										Add Customer GST No
									</a>

									<!-- Customer GST No Modal -->
									<div class="modal fade MyPopup" id="exampleModal<?php echo $row['header_id'];?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
										<div class="modal-dialog" role="document">
											<div class="modal-content">
												<div class="modal-header">
													<h5 class="modal-title" id="exampleModalLabel">Customer GST No</h5>
													<button type="button" class="close" data-dismiss="modal" aria-label="Close">
														<span aria-hidden="true">&times;</span>
													</button>
												</div>
												
												<form action="" method="post">
													<div class="modal-body">
														<div class="row">
															<div class="form-group col-md-6">
																<label class="col-form-label float-left">GST Number <span class="text-danger">*</span></label>
																<input type="hidden" name="header_id" id="header_id" autocomplete="off" value="<?php echo isset($row['header_id']) ? $row['header_id'] : NULL;?>" class="form-control" />
																<input type="text" name="gst_number" id="gst_number" minlength="15" maxlength="15" required autocomplete="off" value="<?php echo isset($row['attribute_1']) ? $row['attribute_1'] : NULL;?>" class="form-control" />
																<span class="text-muted text-left float-left">Sample : 22AAAAA0000A1Z5</span>
															</div>
														</div>
													</div>
													<div class="modal-footer">
														<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
														<button type="submit" class="btn btn-primary" name="add_gst_btn">Save</button>
													</div>
												</form>
											</div>
										</div>
									</div>
									<!-- Customer GST No Modal -->

									<a href="<?php echo base_url();?>orders/printReceipt/<?php echo $row['header_id'];?>" title="Print Receipt" target="_blank" class="btn btn-primary btn-sm">
										<i class="fa fa-print"></i> Print Receipt
									</a>

									<?php 
										if( isset($row['order_status']) && $row['order_status'] == 'Booked' )
										{
											?>
											<a href="<?php echo base_url();?>orders/kotPrint/<?php echo $row['header_id'];?>" title="KOT Print" target="_blank" class="btn btn-warning btn-sm">
												<i class="fa fa-print"></i> KOT
											</a>
											<?php 
										} 
									?>
								</div>

								<div class="row">
									<div class="col-md-6 mt-3">
										<!-- Customer details-->
										
										<div class="printview-order-inner">
											<h3 class="sub_title">Customer Details</h3>
											<div class="row">
												<div class="col-md-3">Name</div>
												<div class="col-md-1"> :</div>
												<div class="col-md-3"> <?php echo ucfirst($row['customer_name']);?></div>
											</div>
											<div class="row mt-2">
												<div class="col-md-3">Mobile Number</div>
												<div class="col-md-1"> :</div>
												<div class="col-md-3"> <?php echo ucfirst($row['mobile_number']);?></div>
											</div>
											
											<div class="row mt-2">
												<div class="col-md-3">Address</div>
												<div class="col-md-1"> :</div>
												<div class="col-md-7"> 
													<?php if($row['address1']){?>
													<?php echo ucfirst($row['address_name']);?>,
													<?php echo ucfirst($row['address1']);?>, <?php echo ucfirst($row['address1']);?>, <?php echo ucfirst($row['address2']);?>
													<br>
													<?php echo ucfirst($row['postal_code']);?>
													<?php } ?>

													<?php if($row['pos_address1']){?>
													<?php echo ucfirst($row['pos_address1']);?>, <?php echo ucfirst($row['pos_address2']);?>, <?php echo ucfirst($row['pos_address3']);?>
													<br>
													<?php echo ucfirst($row['pos_postal_code']);?>
													<?php } ?>
												</div>
											</div>

											<?php 
												if( $row['attribute_1'] != NULL )
												{
													?>
													<div class="row mt-2">
														<div class="col-md-3">Customer GST No.</div>
														<div class="col-md-1"> :</div>
														<div class="col-md-7"> 
															<?php echo $row['attribute_1'];?>
														</div>
													</div>
													<?php 
												} 
											?>
										</div>
										<!-- Customer details end-->
									</div>	
									<div class="col-md-6">	
										<div class="printview-order-inner mt-3">
											<h3 class="sub_title">Branch Details</h3> 
											<div class="row">
												<div class="col-md-3">Branch Name</div>
												<div class="col-md-1"> :</div>
												<div class="col-md-8"> <?php echo ucfirst($row['branch_name']);?></div>
											</div>
											<div class="row mt-2">
												<div class="col-md-3">Mobile Number</div>
												<div class="col-md-1"> :</div>
												<div class="col-md-8"> +91- <?php echo PHONE1; ?></div>
											</div>
											
											<div class="row mt-2">
												<div class="col-md-3">Email</div>
												<div class="col-md-1"> :</div>
												<div class="col-md-8"> <?php echo CONTACT_EMAIL;?></div>
											</div>
										
											<div class="row mt-2">
												<div class="col-md-3">Address</div>
												<div class="col-md-1"> :</div>
												<div class="col-md-8"> <?php echo ADDRESS1; ?>,
													<?php echo ADDRESS2; ?></div>
											</div>
										</div>
										<!-- Branch details end-->
									</div>
								</div>
							</div>
						</div>

						
						<hr>
						<!-- Order details-->
						<div class="printview-order-inner mt-3">
							<h3 class="sub_title">Order Details</h3> 
							
							<div class="row">
								<div class="col-md-12">
									<div class="row">
										<!--left start-->
										<div class="col-md-6">
											
											<div class="row mt-2">
												<div class="col-md-3">Order Number</div>
												<div class="col-md-1"> :</div>
												<div class="col-md-8"> <?php echo $row['order_number'];?></div>
											</div>
											
											<div class="row mt-2">
												<div class="col-md-3">Order Status</div>
												<div class="col-md-1"> :</div>
												<div class="col-md-8"> <?php echo $row['order_status'];?></div>
											</div>
										
											<div class="row mt-2">
												<div class="col-md-3">Payment Method</div>
												<div class="col-md-1"> :</div>
												<div class="col-md-8"> <?php echo $row['payment_method'];?></div>
											</div>
											
											<?php 
												if($row['header_payment_type'] != 1)
												{ 
													?>
													<!--div class="row mt-2">
														<div class="col-md-3">Payment Type</div>
														<div class="col-md-1"> :</div>
														<div class="col-md-8"> <3?php echo $row['payment_type'];?></div>
													</div-->
													<?php 
												} 
											?>

											<?php 
												if( !empty($row['paid_status']) && $row['paid_status'] != NULL)
												{ 
													?>
													<div class="row mt-2">
														<div class="col-md-3">Paid Status</div>
														<div class="col-md-1"> :</div>
														<div class="col-md-8"> 
															<?php 
																if($row['paid_status'] == 'N')
																{
																	echo 'No';
																}
																else
																{
																	echo 'Yes';
																}
															?>
														</div>
													</div>
													<?php 
												} 
											?>

											<?php 
												if( !empty($row['order_type']) && $row['order_type'] != NULL)
												{ 
													?>
													<div class="row mt-2">
														<div class="col-md-3">Order Type</div>
														<div class="col-md-1"> :</div>
														<div class="col-md-8"> 
															<?php 
																if($row['order_type'] == 1)
																{
																	echo 'Take Away';
																}
																else if($row['order_type'] == 2)
																{
																	echo 'Deliver';
																}
															?>
														</div>
													</div>
													<?php 
												} 
											?>

											<?php if(!empty($row['order_source'])){ ?>
												<div class="row mt-2">
													<div class="col-md-3">Order Source</div>
													<div class="col-md-1"> :</div>
													<div class="col-md-8"> 
														<?php 
															if($row['order_source'] == "DINE_IN")
															{
																?>
																Dine In
																<?php
															}
															else if($row['order_source'] == "HOME_DELIVERY")
															{
																?>
																Home Delivery
																<?php
															}
															else 
															{
																echo $row['order_source'];
															}
														?>
													</div>
												</div>
											<?php } ?>
											
											<?php 
												if( !empty($row['table_name']) && $row['table_name'] != NULL)
												{ 
													?>
													<div class="row mt-2">
														<div class="col-md-3">Table Name</div>
														<div class="col-md-1"> :</div>
														<div class="col-md-8"> 
															<?php 
																echo $row['table_name'];
															?>
														</div>
													</div>
													<?php 
												} 
											?>

											<?php 
												if( !empty($row['waiter_id']) && $row['waiter_id'] != NULL)
												{ 
													?>
													<div class="row mt-2">
														<div class="col-md-3">Waiter Name</div>
														<div class="col-md-1"> :</div>
														<div class="col-md-8"> 
															<?php 
																if($row['waiter_id'] == 1)
																{
																	?>
																	Admin
																	<?php
																}
																else
																{
																	?>
																	<?php 
																		echo $row['waiter_name'];
																	?>
																	<?php
																}
															?>
														</div>
													</div>
													<?php 
												} 
											?>

											<?php /* 
											<?php if(!empty($row['delivery_instructions'])){ ?>
												<div class="row mt-2">
													<div class="col-md-3">Delivery Instructions</div>
													<div class="col-md-1"> :</div>
													<div class="col-md-8"> <?php //echo ucfirst($row['delivery_instructions']);?></div>
												</div>
											<?php } ?>
											
											<?php if(!empty($row['packing_instructions'])){ ?>
												<div class="row mt-2">
													<div class="col-md-3">Packing Instructions</div>
													<div class="col-md-1"> :</div>
													<div class="col-md-8"> <?php //echo $row['packing_instructions'];?></div>
												</div>
											<?php } ?>
									
											<?php 
												if($row['cancel_status'] != NULL)
												{ 
													?>
													<div class="row mt-2">
														<div class="col-md-3">Cancel Status</div>
														<div class="col-md-1"> :</div>
														<div class="col-md-8"> 
															<?php
																if($row['cancel_status'] == 'N')
																{
																	echo 'No';
																}
																else
																{
																	echo 'Yes';
																}
														 ?>
														</div>
													</div>
													<?php 
												} 
											?>

											*/ ?>
										</div>
										<!--left End-->
										
										<!--Right start-->
										<div class="col-md-6">
											<div class="row mt-2">
												<div class="col-md-3">Order Date</div>
												<div class="col-md-1"> :</div>
												<div class="col-md-8"> <?php echo date(DATE_FORMAT." ".$this->time,strtotime($row['ordered_date']));?></div>
											</div>

											<div class="row mt-2">
												<div class="col-md-3">Payment Due</div>
												<div class="col-md-1"> :</div>
												<div class="col-md-8"> <?php echo $row['payment_due'];?></div>
											</div>

											<?php 
												if(!empty($row['accepted_date']) && $row['accepted_date'] !=NULL)
												{ 
													?>
													<div class="row mt-2">
														<div class="col-md-3">Confirmed Date</div>
														<div class="col-md-1"> :</div>
														<div class="col-md-8"> <?php echo date(DATE_FORMAT." ".$this->time,strtotime($row['accepted_date']));?></div>
													</div>
													<?php 
												} 
											?>

											<?php if(!empty($row['preparing_date'])&& $row['preparing_date'] !=NULL){ ?>
												<div class="row mt-2">
													<div class="col-md-3">Preparing Date</div>
													<div class="col-md-1"> :</div>
													<div class="col-md-8"> <?php echo date(DATE_FORMAT." ".$this->time,strtotime($row['preparing_date']));?></div>
												</div>
											<?php } ?>

											<?php if(!empty($row['out_for_delivery_date'])&& $row['out_for_delivery_date'] !=NULL){ ?>
												<div class="row mt-2">
													<div class="col-md-3">Out for Delvry Date</div>
													<div class="col-md-1"> :</div>
													<div class="col-md-8"> <?php echo date(DATE_FORMAT." ".$this->time,strtotime($row['out_for_delivery_date']));?></div>
												</div>
											<?php } ?>

											<?php if(!empty($row['delivered_date'])&& $row['delivered_date'] !=NULL){ ?>
												<div class="row mt-2">
													<div class="col-md-3">Delivery Date</div>
													<div class="col-md-1"> :</div>
													<div class="col-md-8"> <?php echo date(DATE_FORMAT." ".$this->time,strtotime($row['delivered_date']));?></div>
												</div>
											<?php } ?>


											<?php /* if(!empty($row['card_number'])){ ?>
												<div class="row mt-2">
													<div class="col-md-3">Card Number</div>
													<div class="col-md-1"> :</div>
													<div class="col-md-8"> <?php //echo $row['card_number'];?></div>
												</div>
											<?php } */ ?>

											<?php 
												if( $row['cancel_date'] !=NULL )
												{
													?>
													<div class="row mt-2">
														<div class="col-md-3">Cancel Date</div>
														<div class="col-md-1"> :</div>
														<div class="col-md-8"> <?php echo date(DATE_FORMAT." ".$this->time,strtotime($row['cancel_date']));?></div>
													</div>
													<?php 
												}
											?>

											<?php
												if($row['order_source'] == "DINE_IN")
												{
													?>
													<div class="row mt-2">
														<div class="col-md-3">Cashier Bill Print Status</div>
														<div class="col-md-1"> :</div>
														<div class="col-md-8"> <?php echo $row['bill_print_status'];?></div>
													</div>
													<div class="row mt-2">
														<div class="col-md-3">Cashier Bill Print Count</div>
														<div class="col-md-1"> :</div>
														<div class="col-md-8"> <?php echo $row['bill_print_count'];?></div>
													</div>
													<?php
												}
											?>
										</div>
										<!--Right End-->
									</div>
								</div>
							</div>

							<?php
								$LineQuery = "select 
									ord_order_lines.line_id,
									ord_order_lines.quantity,
									ord_order_lines.offer_percentage,
									
									ord_order_lines.tax_percentage,
									
									ord_order_lines.product_id,
									ord_order_lines.cancel_status,
									ord_order_lines.cancel_remarks,
									ord_order_headers.header_id,
									
									products.item_name,
									products.item_description,
									ord_order_lines.price,

									round(( coalesce(offer_percentage,0) / 100) * (ord_order_lines.quantity * ord_order_lines.price),2) as offer_amount, 
									round( (ord_order_lines.quantity * ord_order_lines.price) - ((coalesce(offer_percentage,0) / 100) * (ord_order_lines.quantity * ord_order_lines.price)),2) as linetotal, 
									round(((ord_order_lines.quantity * ord_order_lines.price) - ((coalesce(offer_percentage,0) / 100) * (ord_order_lines.quantity * ord_order_lines.price))) * (coalesce(tax_percentage,0)/100),2) as tax_value 
												
									from ord_order_lines
									
								left join ord_order_headers on 
									ord_order_headers.header_id = ord_order_lines.header_id
								
								left join inv_sys_items as products on 
									products.item_id = ord_order_lines.product_id

								where 
								ord_order_lines.header_id='".$row['header_id']."'";
								
								$LineData = $this->db->query($LineQuery)->result_array();
							?>
							
							<form name="form1" id="order_details" method="post" action="">

								<?php
									if(count($LineData) > 0)	
									{
										$chkCancelQuery = "select line_id from ord_order_lines
										where 
										ord_order_lines.header_id='".$row['header_id']."' and
										ord_order_lines.cancel_status ='N'
										";
										$chkCancelStatus = $this->db->query($chkCancelQuery)->result_array();
										
										/* if($row['payment_method'] == 1 && $row['paid_status'] == 0)
										{
											?>
											<div class="row mt-3">
												<div class="col-md-8">
													<span style="color:red;">COD - Payment Pending</span>
												</div>
											</div>
											<?php
										} */
										//if($row['paid_status'] == 'N' && $row['order_status'] != "Delivered")
										if($row['paid_status'] == 'Y' || $row['paid_status'] == 'N')
										{
											if($row['cancel_status'] == "Y")
											{
												?>
												<div class="row mt-3">
													<div class="col-md-8">
														<span style="color:red;">Order has been Cancelled!</span>
													</div>
												</div>
												<?php
											}
											else  if( count($chkCancelStatus) > 0 || $row['cancel_status'] == 'N' )
											{
												?>
												<!--<div class="row mt-3">
													<div class="col-md-8">
														<div class="showbtn">
															<a href="javascript::void(0);" onclick="showbtn();" class="showbtn btn btn-danger">
																Show Multi Cancel
															</a>
														</div>
													</div>
												</div>-->
												<?php 
													/* if($row['order_source'] == "IOS" || $row['order_source'] == "ANDROID")
													{ */
														?>
														<style>
															button.delete-btn{background: #ff587c;border-radius: 11px;}
															button.un-delete-btn {background: #999999;border-radius: 0px; cursor: not-allowed !important;pointer-events: none; }
														</style>
														<div class="row mt-3">
															<div class="col-md-1 cancel-item-new" style="cursor: not-allowed;">
																<a href="javascript:void(0)" class="btn btn-danger un-delete-btn" onclick="ajaxSelectMultipleValues();">
																	Cancel
																</a>
															</div>
														</div>
														<?php
													#}
													/* else
													{
														?>
														<!-- <style>
															button.delete-btn{background: #ff587c;border-radius: 11px;}
															button.un-delete-btn {background: #999999;border-radius: 0px; cursor: not-allowed !important;pointer-events: none; }
														</style>
														<div class="row mt-3">
															<div class="col-md-1 cancel-item-new" style="cursor: not-allowed;">
																<button class="btn btn-danger un-delete-btn" style="" type="submit" name="delete" value="delete" onclick="return confirm('Are you sure you want to cancel this item?');">
																	Cancel
																</button>
															</div>
														</div> -->
														<?php 
													}  */
												?>
												<?php 
											} 
										} 
									?>

								
								<input type="hidden" name="order_id" id="order_id" class="order_id" value="<?php echo $id;?>">	
								<input type="hidden" name="delete_items" id="delete_items" class="delete_items">	

								<div class="new-scroller mt-3">
									<table class="table table-bordered mt-2 line_items">
										<thead>
											<tr>
												<th colspan="15">
													Ordered Items
													<span class="text-right float-right">Currency : <?php echo CURRENCY_CODE;?></span>
												</th>
												
											</tr>	
											<tr>
												<th class="text-center tab-md-50">S.No.</th>
												<th class="text-center tab-md-50">
													<?php
														if($row['paid_status'] == 'N' && $row['order_status'] != "Delivered")
														//if($row['paid_status'] == 'Y' || $row['paid_status'] == 'N')
														{
															if($row['payment_method'] == 1 && $row['paid_status'] == 'N')
															{
																?>
																--
																<?php
															} 

															if( $row['cancel_status'] == 'Y')
															{
																?>
																<span style="color:red;">All Item Cancelled</span>
																<?php	
															}
															else
															{
																?>
																
																<?php
															}																	
														}
													?>
												</th>
												<th class="tab-md-70">Cancel Remarks</th>
												
												<th class="tab-md-150">Item Code</th>														
												<th class="tab-md-150">Item Name / Ingredients</th>
												<th class="text-center tab-md-50">Qty</th>
												<th class="text-right tab-md-50">Price</th>
												<th class="text-right tab-md-80">Offer Amount</th>
												<th class="text-right tab-md-50">Tax</th>
												<th class="text-right tab-md-50">Amount</th>
											</tr>
											<tbody>
												<?php 
													$counter=$i=1;
													$totalTax = 0;
													$subTotal = 0;
													$totalofferAmount = 0;
													foreach($LineData as $lineItems)
													{
														if($lineItems['cancel_status'] == 'Y')
														{
															$cancelStatus="color:red";
														}else{
															$cancelStatus="";
														}
														
														$ingredientsQry = "select 
																ord_ing_tbl.ingredient_amount,
																ing_line_tbl.ingredient_name,
																ing_line_tbl.ingredient_description from ord_order_lines_ingredients as ord_ing_tbl
															left join inv_item_ingredient_line as ing_line_tbl on 
																ing_line_tbl.ing_line_id = ord_ing_tbl.ingredient_id
															where 
															header_id='".$row['header_id']."'
															and line_id='".$lineItems['line_id']."'
															";
														$checkIngredients = $this->db->query($ingredientsQry)->result_array();

														$cancel_remarks = isset($lineItems['cancel_remarks']) ? $lineItems['cancel_remarks'] : NULL;
														?>
														<tr style="<?php echo $cancelStatus;?>">
															<td class="text-center">
																<?php echo $i;?>
																<input type='hidden' name='counter' name='counter' value="<?php echo $counter;?>">
																<input type='hidden' name='line_id[]' id='line_id<?php echo $counter;?>' value="<?php echo $lineItems['line_id'];?>">
															</td>

															<td class="text-center">
																<?php
																	#if($row['paid_status'] == 'N' && $row['order_status'] != "Delivered")
																	if($row['paid_status'] == 'N' || $row['paid_status'] == 'Y')
																	{
																		#cod = payment_method-1, paid_status=0 - not paid
																		/* if($row['payment_method'] == 1 && $row['paid_status'] == 'N')
																		{
																			?>
																			--
																			<?php
																		} */

																		if( $row['cancel_status'] == 'Y') #Cancelled
																		{
																			?>
																			<span style="color:red;">Item Cancelled</span>
																			<?php	
																		}
																		else
																		{
																			if( $lineItems['cancel_status'] == 'N' )
																			{
																				if(isset($row['order_status']) && $row['order_status'] !="Shipped")
																				{
																					?>
																					<?php
																					/* <a href="<?php echo base_url();?>orders/cancelItem/<?php echo $lineItems['order_id'];?>/<?php echo $lineItems['order_line_id'];?>" title="Cancel" onclick="return confirm('Are you sure you want to cancel this item?');">
																						Cancel
																					</a> */ ?>
																					<input type="checkbox" name="checkbox[]" class="emp_checkbox checkbox_items multiple_checkbox checkbox_counter<?php echo $counter;?>" id='checkbox_counter_<?php echo $counter;?>' onclick="chkOnclick('<?php echo $lineItems['line_id'];?>','<?php echo $counter;?>');" value="<?php echo $lineItems['line_id']; ?>_<?php echo $cancel_remarks;?>">
																					<?php 
																				}
																				else
																				{
																					?>
																					--
																					<?php
																				}
																			}
																			else
																			{
																				?>
																				<span style="color:red;" title="Cancelled">
																					Cancelled
																				</span>
																				<?php
																			}
																		}
																	}
																	else
																	{
																		?>
																		<span>--</span>
																		<?php
																	}
																?>
															</td>

															<td class="text-center">
																<textarea class="form-control" name="cancel_remarks[]" rows='1' readonly id="cancel_remarks<?php echo $counter;?>"  placeholder="Cancel Remarks"><?php echo $lineItems['cancel_remarks'];?></textarea>
															</td>
	
															<td>
																<?php echo ucfirst($lineItems['item_name']);?>
															</td>
															
															<td>
																<?php 
																	if(count($checkIngredients) > 0)
																	{
																		?>
																		<?php echo ucfirst($lineItems['item_description']);?> 

																		<a class='text-warning' href="javascript:void(0);" data-toggle="modal" data-target="#exampleModal<?php echo $lineItems['line_id'];?>">
																			(<?php echo count($checkIngredients);?>)
																		</a>

																		<div class="modal fade" id="exampleModal<?php echo $lineItems['line_id'];?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
																			<div class="modal-dialog" role="document">
																				<div class="modal-content">
																					<div class="modal-header">
																						<h5 class="modal-title" id="exampleModalLabel">Item Ingredients</h5>
																						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
																						<span aria-hidden="true">&times;</span>
																						</button>
																					</div>
																					<div class="modal-body">
																						<table class="table">
																							<thead>
																								<tr>
																									<th>Ingredient Name</th>
																									<th>Ingredient Description</th>
																									<th class="text-right">Ingredient Cost (<?php echo CURRENCY_SYMBOL;?>)</th>
																								</tr>
																							</thead>
																							<tbody>
																								<?php 
																									$totalIngAmount = 0;
																									foreach ($checkIngredients as $ingredient) 
																									{ 
																										$totalIngAmount += $ingredient['ingredient_amount'];
																										?>
																										<tr>
																											<td><?php echo $ingredient['ingredient_name']; ?></td>
																											<td><?php echo $ingredient['ingredient_description']; ?></td>
																											<td class="text-right">
																												<?php echo number_format($ingredient['ingredient_amount'],DECIMAL_VALUE,'.','');?>
																											</td>
																										</tr>
																										<?php 
																									} 
																								?>
																								<tr>
																									<td colspan="2" class="text-right"><b>Total</b></td>
																									<td class="text-right">
																										<?php echo number_format($totalIngAmount,DECIMAL_VALUE,'.','');?>
																									</td>
																								</tr>
																							</tbody>
																						</table>
																					</div>
																					<!-- <div class="modal-footer">
																						<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
																					</div> -->
																				</div>
																			</div>
																		</div>
																		<?php
																	}
																	else
																	{
																		?>
																		<?php echo ucfirst($lineItems['item_description']);?>
																		<?php
																	}
																?>
															</td>

															<td class="text-center"><?php echo $lineItems['quantity'];?></td>
															<td class="text-right"><?php echo $lineItems['price'];?></td>
															
															<td class="text-right"><?php echo $lineItems['offer_amount'];?></td>
															<td class="text-right"><?php echo $lineItems['tax_value'];?></td>
															<td class="text-right"><?php echo $lineItems['linetotal'];?></td>
														</tr>
														<?php
														$i++;
														$counter++;
														if($lineItems['cancel_status'] == 'Y')
														{
															$totalTax += 0;
															$subTotal += 0;
															$totalofferAmount += 0;
														}
														else
														{
															$totalTax += $lineItems['tax_value'];
															$totalofferAmount += $lineItems['offer_amount'];
															$subTotal += $lineItems['linetotal'];
														}
													} 
													$totalAmount = $subTotal + $totalTax;
													$roundedAmount = round($totalAmount);
													$roundedValue = $roundedAmount - $totalAmount ;
												?>
												
												<tr>
													<td class="text-right" colspan="9">Sub Total</td>
													<td class="text-right"><?php echo number_format($subTotal,DECIMAL_VALUE,'.','');?></td>
												</tr>

												<tr>
													<td class="text-right" colspan="9">CGST</td>
													<td class="text-right"><?php echo number_format($totalTax / 2,DECIMAL_VALUE,'.','');?></td>
												</tr>

												<tr>
													<td class="text-right" colspan="9">SGST</td>
													<td class="text-right"><?php echo number_format($totalTax / 2 ,DECIMAL_VALUE,'.','');?></td>
												</tr>

												<tr>
													<td class="text-left" colspan="7">
														<?php 
															if($row['discount_remarks'] !="")
															{
																?>
																	<b>Discount Remarks : </b> <?php echo $row['discount_remarks'];?>
																<?php
															}	
														?>
													</td>
													<td class="text-right" colspan="2">Discount Amount</td>
													<td class="text-right"><?php echo number_format($totalofferAmount ,DECIMAL_VALUE,'.','');?></td>
												</tr>

												<tr>
													<td class="text-right" colspan="9">Round Off</td>
													<td class="text-right"><?php echo number_format($roundedValue,DECIMAL_VALUE,'.','');?></td>
												</tr>

												<tr>
													<td class="text-right" colspan="9"><b>Payable Amount</b></td>
													<td class="text-right"><b><?php echo number_format($totalAmount + $roundedValue,DECIMAL_VALUE,'.','');?></b></td>
												</tr>
											</tbody>
										</thead>
									</table>
								</div>
							</form>	 	
						</div>
						<!-- Order details end -->
					</div>
					<?php 
				} 	 
			} 
		?>	
		</div><!-- Card body end-->
	</div><!-- Card end-->
</div><!-- Content end-->


<script>
	function chkOnclick(line_id,counter) 
	{
		var itemChecked = $('.checkbox_counter'+counter).is(":checked");
		
		if(itemChecked == true)
		{
			$("#cancel_remarks"+counter).attr("required",true);
			$("#cancel_remarks"+counter).removeAttr("readonly");
		}
		else if(itemChecked == false)
		{
			$("#cancel_remarks"+counter).removeAttr("required",false);
			$("#cancel_remarks"+counter).attr("readonly",true);
		}

		var opts = getMultipleCheckBoxFilter();
		$(".delete_items").val(opts);
	}

	$("table.line_items").on("input keyup change", 'textarea[name^="cancel_remarks[]"]', function (event) 
	{
		var row = $(this).closest("tr");
		var counter = +row.find('input[name^="counter"]').val();
		calculateRow(counter);
		//calculateGrandTotal();
	});

	function calculateRow(counter)
	{
		var cancel_remarks = $("#cancel_remarks"+counter).val();
		var line_id = $("#line_id"+counter).val();

		$("#checkbox_counter_"+counter).val(line_id+'_'+cancel_remarks);
		//chkOnclick(po_id,product_id,counter);
	}


	function getMultipleCheckBoxFilter()
	{
		var opts = [];
		
		$('.multiple_checkbox').each(function()
		{
			if(this.checked)
			{
				opts.push(this.id);
			}
		});
		return opts;
	}

	/* $('.multiple_checkbox').on('change', function(e)
	{
		var opts = getMultipleCheckBoxFilter();
		$(".delete_items").val(opts);
		//ajaxSelectMultipleValues(opts);
	});
 	*/

	function ajaxSelectMultipleValues()
	{
		var opts = $(".delete_items").val();
		var order_id = $(".order_id").val();

		if(opts)
		{
			$.ajax({
				type : "POST",
				url  : "<?php echo base_url().'orders/cancelOrderItems';?>/"+order_id,
				//data : {filterOpts: opts,order_id:order_id},
				data: $('form#order_details').serialize(),
			}).done(function( result ) 
			{   
				var button_type = 'SAVE';
				generateKOTPDF(button_type,order_id);
				//printKOTPDF(button_type,order_id);
				//location.reload();
				//var msg = result;
				/* var msg = result.substring(0, result.length - 2);

				if(msg)
				{
					$(".showCheckboxes").html("");
					$(".overSelect").html(" ("+msg+")");
					$("#formula").val("( "+msg+")");

					appendFormula();
				}
				else
				{
					$(".showCheckboxes").html("<option value=''>- Select - </option>");
					$(".overSelect").html(msg);
					$("#formula").val(msg);
				} */
			});
		}
		else
		{
			alert("Please select delete items!");
			/* $(".showCheckboxes").html("<option value=''>- Select - </option>");
			$(".overSelect").html('');
			$("#formula").val(''); */
		}
	}

	function generateKOTPDF(button_type,interface_header_id)
    {
		if(interface_header_id)
        {
            $.ajax({
                type: 'post',
                url: '<?php echo base_url();?>pos/generateOnlineKOTPDF/'+button_type+'/'+interface_header_id,
                data: {button_type:button_type,interface_header_id:interface_header_id},
                success: function (result) 
                {
                    printKOTPDF(button_type,interface_header_id);   
                }
            });
        }
    }

	function printKOTPDF(button_type,interface_header_id)
    {
        var orderID = interface_header_id;
       
        if(orderID > 0 && orderID !="")
        {
            $.ajax({
                url      : '<?php echo base_url(); ?>billGenrator/chkbill/'+orderID,
                type     : "POST",
                data     : {},
                datatype : JSON,
                success  : function(d)
                {
                    response = JSON.parse(d);

                    var htmlCashierContent = response["orderPDFPath"];
                    var htmlKOTContent = response["orderKOTPath"];
                    var print_items = response["print_items"];

                    var countKey = Object.keys(print_items).length;
                    
                    if( countKey > 0 )
                    {
                        $.each(print_items, function(i, item) 
                        {
                            var print_type = item.print_type; // #Cashier #KOT

                            var printer_name = item.printer_name;
                            var printer_count = item.printer_count;

                            if( printer_name !="" )
                            {
                                /* if( button_type == "SAVE_PRINT" )
                                {
                                    if(print_type == "CASHIER")
                                    {
                                        for(i=1; i<=printer_count; i++)
                                        {
                                            orderAutoPrint(jspmWSStatus(),htmlCashierContent,orderID,printer_name,button_type);
                                        }
                                    }

                                    if(print_type == "KOT")
                                    {
                                        for(i=1; i<=printer_count; i++)
                                        {
                                            kotAutoPrint(jspmWSStatus(),htmlCashierContent,orderID,printer_name,button_type);
                                        }
                                    }    
                                }
                                else if( print_type == "KOT" && button_type == "SAVE" )
                                {
                                    for(i=1; i<=printer_count; i++)
                                    {
                                        kotAutoPrint(jspmWSStatus(),htmlKOTContent,orderID,printer_name,button_type);
                                    }
                                } */

                                if( print_type == "KOT")
                                {
                                    for(i=1; i<=printer_count; i++)
                                    {
                                        kotAutoPrint(jspmWSStatus(),htmlKOTContent,orderID,printer_name,button_type);
                                    }
                                }
                            }
                        });
                    }
                    updateKOTOrderStatus(orderID,button_type);	
                }
            });   
        }
    }

    function kotAutoPrint(printerStatus,htmlContent,orderID,printer_name,button_type)
    {
        if (printerStatus && htmlContent !="") 
        {
            //Create a ClientPrintJob
            var cpj = new JSPM.ClientPrintJob();

            //Set Printer info
            //var myPrinter = new JSPM.InstalledPrinter($('#lstPrinters').val());
            //myPrinter.paperName = $('#lstPrinterPapers').val();
            //myPrinter.trayName = $('#lstPrinterTrays').val();
            //cpj.clientPrinter = myPrinter;
            
            //Cashier Printer
            //var printerPort = <?php #echo PRINTER_PORT;?>;
            //var printerName = '<?php #echo PRINTER_NAME;?>';

            //var printerPort = printer_ip;
            var printerName = printer_name;
            
            //alert(printerName);
            
            //var myPrinter = new JSPM.InstalledPrinter(printerPort,printerName); //9100 ,"192.168.1.215"
            var myPrinter = new JSPM.InstalledPrinter(printerName); //printer name
            
            //var myPrinter = new JSPM.DefaultPrinter(); //9100 ,"192.168.1.215"
            
            cpj.clientPrinter = myPrinter;
            
            //Set PDF file
            var orderPDFPath = htmlContent;
            var currenttime = '<?php echo rand();?>';
            var my_file = new JSPM.PrintFilePDF(orderPDFPath,JSPM.FileSourceType.URL, 'MyFile_'+currenttime+'.pdf', 1);
            
            //var my_file = new JSPM.PrintFile('<?php echo base_url();?>uploads/generate_pdf/251.jpg', JSPM.FileSourceType.URL, 'MyFile.jpg', 1);

            //var my_file = new JSPM.PrintFilePDF($('#txtPdfFile').val(), JSPM.FileSourceType.URL, 'MyFile.pdf', 1);
            //my_file.printRotation = JSPM.PrintRotation[$('#lstPrintRotation').val()];
            //my_file.printRange = $('#txtPagesRange').val();
            //my_file.printAnnotations = $('#chkPrintAnnotations').prop('checked');
            //my_file.printAsGrayscale = $('#chkPrintAsGrayscale').prop('checked');
            //my_file.printInReverseOrder = $('#chkPrintInReverseOrder').prop('checked');

            cpj.files.push(my_file);
            
            //Send print job to printer!
            cpj.sendToClient();
            //updateOrderStatus(orderID);
        }
    }

    //Update Printer Order Status
    function updateKOTOrderStatus(orderID,button_type)
    {
        if(orderID)
        {
            var interface_header_id = orderID;
            $.ajax({
                url      : '<?php echo base_url(); ?>billGenrator/updateKOTOrderStatus/'+orderID,
                type     : "POST",
                data     : {},
                datatype : JSON,
                success  : function(result)
                {
                    Swal.fire({
                        position: 'top',
                        //position: 'top-end',
                        icon: 'success',
                        title: 'Item cancelled successfully!',
                        showConfirmButton: false,
                        timer: 500,
                        width:'350px'
                    });  

					window.location = '<?php echo base_url();?>orders/viewOderDetails/'+orderID;
                }
            });
        }
    }
</script>

<script type="text/javascript">  
	//Select all checkbox
	$('#select_all').on('click', function(e) 
	{
		if($(this).is(':checked',true)) 
		{
			$(".un-delete-btn").addClass('delete-btn');
			$('.delete-btn').removeClass('un-delete-btn');
			
			$(".emp_checkbox").prop('checked', true);
		}
		else 
		{
			$('.delete-btn').addClass('un-delete-btn');
			$(".un-delete-btn").removeClass('delete-btn');
			
			$(".emp_checkbox").prop('checked',false);
		}
		/* set all checked checkbox count
		$("#select_count").html($("input.emp_checkbox:checked").length+" Selected"); */
	});
	
	$('.emp_checkbox').on('click', function(e) 
	{
		if($(this).is(':checked',true)) 
		{
			$(".un-delete-btn").addClass('delete-btn');
			$('.delete-btn').removeClass('un-delete-btn');
		}
		else 
		{
			$('.delete-btn').addClass('un-delete-btn');
			$(".un-delete-btn").removeClass('delete-btn');
		}
	});	
</script>


