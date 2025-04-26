<style>
	.order-list-view{
		color:gray;
	}
	.total{
		color:black;
		font-size:15px;
		font-weight:bold;
	}
.noorers
{
	width:100%;
	text-align:center;
	float: left;
	padding-top: 0px;
}
.noorers img
{
	padding-top: 50px;
}
@media screen and (max-width: 625px) {

.noorers img
{
	padding-top: 0px;
}

}

</style>
<body class="grocino-home home2 grocino-contact-us">
<div class="account-setting">
	<div class="container-fluid">
		<div class="row">
			<!-- <div class="col-md-3 mb-3 pe-xl-5">
					
			</div> -->
			
			<div class="col-md-12">
				<div class="tab-content" id="settingTabContent">
					
					<div class="order_history" id="orderlist" role="tabpanel">
						<?php
							if(count($resultData) > 0)
							{
								?>
									<h2 class="text-center" style="margin-top:50px;">My Order List</h2>
								<?php
							}
						?>
						
                       
						<div class="row " style="margin-top:20px;">
							<?php 
								if(count($resultData) > 0)
								{
									foreach($resultData as $history)
									{
										?>
										<div class=" card"  style="margin-bottom:20px;">
											<div class="order-main p-1 mt-1" style="position:relative;margin-left:34px;">
												<div class="order-list ">
													<form action="">
														<fieldset class="">
															<div class="row">
																
																<div class="col-md-3">
																	<div class="row">
																		<div class="form-group col-md-4 mt-2">
																			<b>Order Number</b>
																		</div>
																		<div class="form-group col-md-5 mt-2" id="test-2" >
																			 <?php echo $history['order_number'];?>
																		</div>
																		
																	</div>
																	<!-- <div class="row">
																		<div class="form-group col-md-5">
																			<b>Order Date</b>
																		</div>
																		<div class="form-group col-md-5">
																			: <?php echo $history['order_date'];?>
																		</div>
																	</div> -->
																		
																</div>
																<div class="col-md-3 ">
																	

																	<div class="row">
																		<div class="form-group col-md-4 mt-2">
																			<b>Order Date</b>
																		</div>
																		<div class="form-group col-md-8 mt-2"  >
																			 <?php echo $history['order_date'];?>
																		</div>
																	</div>
																		
																</div>
																<div class="col-md-2">
																	
																	<div class="row">	
																		<ul>
																			<li class="center-order mt-2">
																				<p>
																					<strong>
																						<?php echo CURRENCY_SYMBOL;?> <?php echo number_format($history['total_amount'],DECIMAL_VALUE,'.','');?>
																					</strong>
																				</p>	
																			</li>   
																		</ul>
																	</div>
																</div>

																<style>
																	@media only screen and (max-width: 600px) {
																		#test1{
																			margin-top:20px;
																			margin-bottom:20px;
																		}
																		/* #test2{
																			margin-top:20px;
																			margin-left:200px;
																		} */
																		}
																</style>
																
																
																<div class="col-md-4">
																	<div class="row">	
																		<p > 
																			<span class="float-"> 
																				<a data-toggle="modal" style="width:180px;margin-right:15px;position:relative;" data-target="#myModal<?php echo $history['header_id'];?>" id="test1" title="View Details" href="javscript:void(0);" class="btn btn-pcart"> 
																					View Details
																				</a>	
																				<a href="<?php echo base_url();?>re-order.html/<?php echo $history['header_id'];?>" id="t" title="Reorder" style="width:180px;" class="btn  btn-outline-danger"> 
																					<i class="fa fa-repeat" aria-hidden="true"></i> Reorder
																				</a>																			
																			</span>
																		</p>
																		<!-- <p class="order-review"> 
																			<span class="float-"> 
																				<a href="<?php echo base_url();?>re-order.html/<?php echo $history['header_id'];?>" title="Reorder" class="btn btn-pcart btn-outline-danger"> 
																					<i class="fa fa-repeat" aria-hidden="true"></i> Reorder
																				</a>																			
																			</span>
																		</p> -->
																		
																	</div>
																	
																	<!-- Modal -->
																	<div class="modal fade" id="myModal<?php echo $history['header_id'];?>" style="background:none;" role="dialog">
																		<div class="modal-dialog">
																			<!-- Modal content-->
																			<div class="modal-content">
																				<!--modal header-->
																				<div class="modal-header p-4">
																					<h4 class="modal-title">ORDER DETAILS</h4>
																					<a href="#" class="close" data-dismiss="modal" style="font-size:30px;">&times;</a>
																				</div>
																				<!--modal body-->
																				<div class="modal-body">
																					
																					<?php 
																						$OrderQuery = "select 
																						products.product_description,
																						ord_order_lines.order_quantity,
																						ord_order_lines.selling_price
																						from ord_order_lines
																							
																						left join products on 
																							products.product_id = ord_order_lines.product_id
																						
																						where ord_order_lines.header_id='".$history['header_id']."' ";
																					
																						$OrderData = $this->db->query($OrderQuery)->result_array();
																					
																					?>
																					<div class="summary-grand-total-top p-3">
																						<div class="col-md-12">																								
																							<div class="row">
																								<style>
																									@media only screen and (max-width: 976px) {
																									#as {
																										POSITION: RELATIVE;
																										margin-left: 239px;
																										margin-top: -32px;
																									}
																									#asp {
																										POSITION: RELATIVE;
																										margin-left: 239px;
																										margin-top: -20px;
																									}
																									#aspp {
																										POSITION: RELATIVE;
																										margin-left: 239px;
																										margin-top: -20px;
																									}
																									}
																									
																								</style>


																								<div class="col-md-6 mb-3" >
																								<h4><b style="color:black;">YOUR ORDER #<?php echo $history['order_number'];?></b></h4> 
																								</div>
																								<div class="col-md-6">
																								<h4><span style="color:black;font-weight:500;"><b>ORDER DATE</b> <?php echo $history['order_date'];?></span></h4> 
																								</div>
																								<!-- <h4><b style="color:black;">YOUR ORDER #<?php echo $history['order_number'];?></b></h4> 
																								<h4><b style="color:black;">YOUR ORDER #<?php echo $history['order_number'];?></b></h4> -->
																								<?php 
																									foreach($OrderData as $ordHistory)
																									{
																										
																										?>
																										<div class="row" style="border-bottom: 1px solid #e0e0e0;float: left;width: 100%;padding-bottom: 10px;">
																											<p class="pt-3">
																												<i class="fa fa-dot-circle-o" aria-hidden="true" style="color:red;"></i>  
																												<?php echo ucfirst($ordHistory['product_description']);?>
																											</p>
																											<div class="col-md-10 total" style="">
																												<?php echo $ordHistory['order_quantity'];?> x <?php echo number_format($ordHistory['selling_price'],DECIMAL_VALUE,'.','');?>
																											</div>
																											
																											<div class="col-md-2 total-view" id="aspp" style="font-weight:600;color:black;">
																												<?php echo CURRENCY_SYMBOL;?>
																												<?php
																													$total_amount = $ordHistory['order_quantity'] * $ordHistory['selling_price'];
																													echo number_format($total_amount,DECIMAL_VALUE,'.','');
																												?>
																											</div>
																										</div>
																										
																										<?php
																									}
																								?>
																							</div>	
																						</div>
																					</div>

																					<div class="summary-grand-total-top pt-2 px-3 pb-1">
																						<div class="col-md-12">																								
																							<div class="row">
																								<style>
																									@media only screen and (max-width: 976px) {
																									#as {
																										POSITION: RELATIVE;
																										margin-left: 239px;
																										margin-top: -32px;
																									}
																									#asp {
																										POSITION: RELATIVE;
																										margin-left: 239px;
																										margin-top: -20px;
																									}
																									#aspp {
																										POSITION: RELATIVE;
																										margin-left: 239px;
																										margin-top: -20px;
																									}
																									}
																									
																								</style>


																								<!-- <div class="col-md-6 mb-3" >
																								<h4><b style="color:black;">YOUR ORDER #<?php echo $history['order_number'];?></b></h4> 
																								</div>
																								<div class="col-md-6">
																								<h4><span style="color:black;font-weight:500;"><b>ORDER DATE</b> <?php echo $history['order_date'];?></span></h4> 
																								</div> -->
																								<!-- <h4><b style="color:black;">YOUR ORDER #<?php echo $history['order_number'];?></b></h4> 
																								<h4><b style="color:black;">YOUR ORDER #<?php echo $history['order_number'];?></b></h4> -->
																								
																										<div class="row" style="float: left;width: 100%;padding-bottom: 2px;">
																											<!-- <p class="pt-3">
																												<i class="fa fa-dot-circle-o" aria-hidden="true" style="color:red;"></i>  
																												<?php echo ucfirst($ordHistory['product_description']);?>
																											</p> -->
																											<div class="col-md-10 total" style="">
																											<b>Sub Total</b>																											</div>
																											
																											<div class="col-md-2 total-view" id="aspp" style="font-weight:600;color:black;">
																												<?php echo CURRENCY_SYMBOL;?>
																												<?php echo number_format($history['total_amount'], 2, '.', '');?>
																											</div>
																										</div>
																										
																								
																							</div>	
																						</div>
																					</div>

																					<div class="summary-grand-total-top pt-3 px-3">
																						<div class="col-md-12">																								
																							<div class="row">
																								<style>
																									@media only screen and (max-width: 976px) {
																									#as {
																										POSITION: RELATIVE;
																										margin-left: 239px;
																										margin-top: -32px;
																									}
																									#asp {
																										POSITION: RELATIVE;
																										margin-left: 239px;
																										margin-top: -20px;
																									}
																									#aspp {
																										POSITION: RELATIVE;
																										margin-left: 239px;
																										margin-top: -20px;
																									}
																									}
																									
																								</style>


																								<!-- <div class="col-md-6 mb-3" >
																								<h4><b style="color:black;">YOUR ORDER #<?php echo $history['order_number'];?></b></h4> 
																								</div>
																								<div class="col-md-6">
																								<h4><span style="color:black;font-weight:500;"><b>ORDER DATE</b> <?php echo $history['order_date'];?></span></h4> 
																								</div> -->
																								<!-- <h4><b style="color:black;">YOUR ORDER #<?php echo $history['order_number'];?></b></h4> 
																								<h4><b style="color:black;">YOUR ORDER #<?php echo $history['order_number'];?></b></h4> -->
																								
																										<div class="row" style="float: left;width: 100%;padding-bottom: 0px;">
																											<!-- <p class="pt-3">
																												<i class="fa fa-dot-circle-o" aria-hidden="true" style="color:red;"></i>  
																												<?php echo ucfirst($ordHistory['product_description']);?>
																											</p> -->
																											<div class="col-md-10 total" style="">
																											<b>Grand Total</b>																											</div>
																											
																											<div class="col-md-2 total-view" id="aspp" style="font-weight:600;color:black;">
																												<?php echo CURRENCY_SYMBOL;?>
																												<?php echo number_format($history['total_amount'], 2, '.', '');?>
																											</div>
																										</div>
																										
																								
																							</div>	
																						</div>
																					</div>
																					
																					

																					
																					<!-- <hr style="margin:0;"> -->
																					<!-- <div class="summary-grand-total-top p-3">
																						<div class="col-md-12">
																							<div class="row">
																								<div class="col-md-10 total mb-3" >
																									<b>Sub Total</b>
																								</div>
																								<div class="col-md-2 total-view" style="font-weight:600;color:black;" id="as">
																									<?php echo CURRENCY_SYMBOL;?>
																									<?php echo number_format($history['total_amount'], 2, '.', '');?>

																								</div>
																							</div>
																							<div class="row">
																								<div class="col-md-10 total">
																									<b>Grand Total</b>
																								</div>
																								<div class="col-md-2 total-view" id="asp" style="font-weight:600;color:black;">
																									<?php echo CURRENCY_SYMBOL;?>
																									<?php echo number_format($history['total_amount'], 2, '.', '');?>

																								</div>
																							</div>
																						</div>
																					
																					</div> -->
																					<!-- <hr style="margin:0;"> -->
																					
																					<!-- <ul class="order-details-summary p-3 ">
																						<h4>Order Details</h4>
																						<li class="order-list-view mt-2"><b style="color:black;">ORDER # </b></li><span style="color:black;"><?php echo $history['order_number'];?></span>
																						<li class="order-list-view mt-2"><b style="color:black;">ORDER DATE </b></li><span style="color:black;"><?php echo $history['order_date'];?></span>
																						
																					</ul> -->
																				
																				</div>
																				<!--modal body end-->
																				<!--footer start-->
																				<!-- <div class="modal-footer">
																					<div class="favourite-food p-2" style="margin-right:35%;">
																						<?php /*
																							$favouritequery = "select header_id from ord_order_headers where header_id = ".$history['header_id'] ;
																							$favouritefood = $this->db->query($favouritequery)->result_array();
																							
																							if (count($favouritefood) == 0 ) 
																							{
																								?>
																								<a href="#" class="like-unlike <?php echo count($favouritefood) ?>"  id="<?php echo $history['header_id'];?>" title="Mark as favourite"><i class="fa fa-heart-o"></i> Mark as Favourite</a>
																								<?php
																							}
																							else
																							{
																								?>
																								<a href="#" class="like-unlike" id="<?php echo $history['header_id'];?>"  title="Mark as favourite"><i class="fa fa-heart added-favrt"></i>Added as Favourite</a>
																								<?php
																							}
																						*/?>
																					</div>
																				</div> -->
																				<!--footer end-->
																			</div>
																		
																		</div>
																	</div>
																	<!-- ModalEnd  -->
																</div>	
															</div>
														</fieldset>	
													</form>
												</div>
											</div>
										</div>
										
										<?php	
									}
								}
								else
								{
									?>
										<div class="col-md-12 text-center p-0 m-0">
											<span class="noorers"><img src="<?php echo base_url();?>uploads/noorders.png" alt=""></span>
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
</body>