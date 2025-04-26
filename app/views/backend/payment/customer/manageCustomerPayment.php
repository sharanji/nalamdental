<?php $paymentsMenu = $customer_paymentMenu = accessMenu(customer_payment); ?>
<div class="content"><!-- Content start-->
	<div class="card"><!-- Card start-->
		<div class="card-body">
			<?php
				if(isset($type) && $type == "add")
				{
					?>
					<form action="" --class="form-validate-jquery" enctype="multipart/form-data" method="post" autocomplete="off">
						<div class="header-lines">
							<!-- Buttons start here -->
							<div class="row mb-3">
								<div class="col-md-4">
									<h3>
										<b>
											<?php 
												if($type == "add")
												{
													?>
													Create
													<?php 
												}
												else if($type == "edit")
												{
													echo ucfirst($type);
												}
												else if($type == "view")
												{
													echo ucfirst($type);
												}  
											?>
											<?php echo $page_title; ?>
										</b>
									</h3>
								</div>
								
								<div class="col-md-8 text-right">
									<?php 
										if($type == "add" || $type == "edit")
										{
											?>
											<button type="submit" name="save_btn" id="save_btn" onclick="return saveBtn('save_btn');" title="Save & Continue" class="btn btn-primary btn-sm">Save</button>
											<button type="submit" name="submit_btn" id="submit_btn" onclick="return saveBtn('submit_btn');" title="Submit" class="btn btn-primary btn-sm">Submit</button>
											<?php 
										} 
									?>
									<a href="<?php echo base_url(); ?>payment/manageCustomerPayment" class="btn btn-default btn-sm">Close</a>
								</div>
							</div>
							<!-- Buttons end here -->
							<fieldset>
								<div class="row">
									<div class="col-md-12 header-filters">
										<a href="javascript:void(0)" class="filter-icons first_sec_hide" onclick="sectionShow('FIRST_SECTION','SHOW');">
											<i class="fa fa-chevron-circle-down"></i>
										</a>
										<a href="javascript:void(0)" class="filter-icons first_sec_show" onclick="sectionShow('FIRST_SECTION','HIDE');" style="display:none;">
											<i class="fa fa-chevron-circle-right"></i>
										</a>
										<h4 class="pl-1"><b>Header</b></h4>
									</div>
								</div>

								<section class="header-section first_section">
									<div class="row">
										<div class="col-md-6">
											<div class="row">
												<div class="col-md-3">
													<div class="form-group text-right">
														<label class="col-form-label">Payment ID</label>
													</div>				
												</div>
												<div class="col-md-4">
													<div class="form-group">
														<input type="text" name="payment_number" id="payment_number" readonly autocomplete="off" class="form-control no-outline" value="<?php echo isset($edit_data[0]['payment_number']) ? $edit_data[0]['payment_number'] : NULL;?>" placeholder="">
													</div>				
												</div>
											</div>
											
											<div class="row">
												<div class="col-md-3">
													<?php 
														$getCustomers = $this->invoice_model->getAjaxInvoiceCustomers();
													?>
													<div class="form-group text-right">
														<label class="col-form-label invoice_source">Invoice Source <span class="text-danger">*</span></label>
													</div>				
												</div>
												<div class="col-md-5">
													<?php 
														$getInvoiceSource = $this->common_model->lov('INVOICE-SOURCE'); 
													?>
													<select name="invoice_source" id="invoice_source" onchange="selectInvoiceSource(this.value);" required class="form-control searchDropdown">
														<option value="">-  Select -</option>
														<?php 
															foreach($getInvoiceSource as $invoice_source)
															{
																?>
																<option value="<?php echo $invoice_source["list_code"];?>"><?php echo $invoice_source["list_value"];?></option>
																<?php 
															} 
														?>
													</select>			
												</div>
											</div>

											<script>
												function selectInvoiceSource(val)
												{
													if(val == "ONLINE-ORDERS") 
													{
														$(".against_order_customer").show();
														$(".against_invoice_customer").hide();
														loadAgainstOrderCus();

														$('.party_orders').hide();
														$('.online_orders').show();	
													}
													else if(val == "PARTY-ORDERS")
													{
														$(".against_order_customer").hide();
														$(".against_invoice_customer").show();
														loadAgainstInvoiceCus();

														$('.party_orders').show();
														$('.online_orders').hide();
													}
													else
													{
														$(".against_order_customer").hide();
														$(".against_invoice_customer").hide();

														$('.party_orders').hide();
														$('.online_orders').hide();
													}
												}

												function loadAgainstOrderCus()
												{
													$.ajax({
														type: "POST",
														url:"<?php echo base_url().'payment/loadAgainstOrderCus';?>",
														data: { id : '' }
													}).done(function( msg ) 
													{   
														$( "#order_customer_id" ).html(msg);	
													});
												}

												function loadAgainstInvoiceCus()
												{
													$.ajax({
														type: "POST",
														url:"<?php echo base_url().'payment/loadAgainstInvoiceCus';?>",
														data: { id : '' }
													}).done(function( msg ) 
													{   
														$( "#customer_id" ).html(msg);	
													});
												}
											</script>
											
											<!-- Order Customer start here-->
											<div class="row against_order_customer" style="display:none;">
												<div class="col-md-3">
													<div class="form-group text-right">
														<label class="col-form-label order_customer_id">Customer <span class="text-danger">*</span></label>
													</div>				
												</div>
												<div class="col-md-5">
													<select name="order_customer_id" style='width:185px;' id="order_customer_id" class="form-control searchDropdown">
														<option value="">-  Select -</option>
													</select>			
												</div>
											</div>

											<div class="row against_order_customer" style="display:none;">
												<div class="col-md-3">
													<div class="form-group text-right">
														<label class="col-form-label from_date">From Date <span class="text-danger">*</span></label>
													</div>				
												</div>
												<div class="col-md-5">
													<input type="text" name="from_date" id="from_date" placeholder="From Date" readonly autocomplete="off" class="form-control future_date" value="">
												</div>
											</div>

											<div class="row against_order_customer" style="display:none;">
												<div class="col-md-3">
													<div class="form-group text-right">
														<label class="col-form-label to_date">To Date <span class="text-danger">*</span></label>
													</div>				
												</div>
												<div class="col-md-5">
													<input type="text" name="to_date" id="to_date" placeholder="To Date" readonly autocomplete="off" class="form-control future_date" value="">
													
													<a href="javascript:void(0);" onclick="getOnlinePaymentLines('ORDER');" class="btn btn-primary search_btn">Search</a>
													<style>
														a.search_btn {
															position: absolute;
															top: 2px;
															right: -55px;
														}
													</style>
												</div>
											</div>
											<!-- Order Customer end here-->

											<!-- Invoice Customer start here-->
											<div class="row against_invoice_customer" style="display:none;">
												<div class="col-md-3">
													<?php 
														#$getCustomers = $this->invoice_model->getAjaxInvoiceCustomers();
													?>
													<div class="form-group text-right">
														<label class="col-form-label customer_id">Customer <span class="text-danger">*</span></label>
													</div>				
												</div>
												<div class="col-md-5">
													<select name="customer_id" id="customer_id" style='width:185px;' onchange="getPaymentLines('INVOICE');" class="form-control searchDropdown">
														<option value="">-  Select -</option>
													</select>			
												</div>
											</div>
											<!-- Invoice Customer end here-->	
										</div>

										<div class="col-md-6">
											<div class="row">
												<div class="col-md-3">
													<div class="form-group text-right payment_date">
														<label class="col-form-label">Payment Date <span class="text-danger">*</span></label>
													</div>				
												</div>
												<div class="col-md-4">
													<div class="form-group">
														<input type="text" name="payment_date" placeholder='Payment Date' readonly autocomplete="off" class="form-control future_date" required id="payment_date" value="<?php echo date("d-M-Y");?>">
													</div>				
												</div>
											</div>

											<div class="row">
												<div class="col-md-3">
													<?php 
														if (isset($type) && $type == "add") 
														{
															$referenceIDDiv = 'style="display:none;"';
															$referenceLabel = 'style="display:block;"';
															$referenceCheckLabel = 'style="display:none;"';
														}
														else if (isset($type) && $type == "edit") 
														{
															if ( isset($edit_data[0]['payment_type_id']) && $edit_data[0]['payment_type_id'] == 1 )  # Cash
															{
																$referenceIDDiv = 'style="display:none;"';
																$referenceLabel = 'style="display:none;"';
																$referenceCheckLabel = 'style="display:none;"';
															}
															else
															{
																if( isset($edit_data[0]['payment_type_id']) && $edit_data[0]['payment_type_id'] == 7 ) # Checque
																{
																	$referenceLabel = 'style="display:none;"';
																	$referenceCheckLabel = 'style="display:block;"';
																}
																else
																{
																	$referenceLabel = 'style="display:block;"';
																	$referenceCheckLabel = 'style="display:none;"';
																}

																$referenceIDDiv = 'style="display:block;"';
															}
														}
													?>
													<?php 
														$pamentTypesQry = "select payment_type_id,payment_type from expense_payment_type where payment_type_status = 1";
														$getPamentTypes = $this->db->query($pamentTypesQry)->result_array();
													?>
													<div class="form-group text-right">
														<label class="col-form-label payment_method">Collection Mode <span class="text-danger">*</span></label>
													</div>	
												</div>
												<div class="col-md-5">
													<select name="payment_method" id="payment_method" onchange="selectPaymentSource(this.value);" class="form-control searchDropdown" required>
														<option value="">- Select -</option>
														<?php 
															foreach($getPamentTypes as $row)
															{
																$selected = "";
																if ( isset($pr_data[0]->payment_method) && $pr_data[0]->payment_method == $row["payment_type_id"] ) 
																{
																	$selected = "selected='selected'";
																}
																?>
																<option value="<?php echo $row["payment_type_id"];?>" <?php echo $selected;?>><?php echo ucfirst($row["payment_type"]);?></option>
																<?php 
															} 
														?>
													</select>		
												</div>
											</div>

											<div class="row">
												<div class="form-group col-md-3 reference_div text-right" <?php echo $referenceIDDiv;?>>
													<label class="col-form-label refernce_label text-right" <?php echo $referenceLabel;?>>Reference ID </label>
													<label class="col-form-label check_label text-right" <?php echo $referenceCheckLabel;?>>Check No.</label>
												</div>
												<div class="form-group col-md-5 reference_div" <?php echo $referenceIDDiv;?>>
													<input type="text" name="reference_id" placeholder='Reference ID' id="reference_id" value="<?php echo isset($edit_data[0]['reference_id']) ? $edit_data[0]['reference_id'] :"";?>" autocomplete="off" class="form-control" autocomplete="off" >
												</div>
												<script>
													function selectPaymentSource(val) 
													{
														if(val == 1)
														{
															$(".reference_div").hide();
															$(".check_label").hide();
														}
														else
														{
															if(val==7)
															{
																$(".refernce_label").hide();
																$(".check_label").show();
															}
															else
															{
																$(".refernce_label").show();
																$(".check_label").hide();
															}
															$(".reference_div").show();
														}
													}
												</script>
												
												<input type="hidden" name="amount" id="amount" autocomplete="off" class="form-control" value="0">
											</div>
											<div class="row">
												<div class="form-group col-md-3 check_label text-right" <?php echo $referenceCheckLabel;?>>
													<label class="col-form-label">Cheque Name </label>
												</div>
												<div class="form-group col-md-5 check_label" <?php echo $referenceCheckLabel;?>>
													<input type="text" name="check_name" placeholder='Cheque Name' id="check_name" value="<?php echo isset($edit_data[0]['check_name']) ? $edit_data[0]['check_name'] :"";?>" autocomplete="off" class="form-control" autocomplete="off" >
												</div>
											</div>

											<div class="row">
												<div class="col-md-3">
													<div class="form-group text-right">
														<label class="col-form-label">Description</label>
													</div>				
												</div>
												<div class="col-md-5">
													<div class="form-group">
														<textarea class="form-control" rows="1" placeholder='Description' autocomplete="off" name="description"><?php echo isset($edit_data[0]['description']) ? $edit_data[0]['description']:"";?></textarea>
													</div>				
												</div>
											</div>

											<div class="row">
												<div class="form-group col-md-3 check_label text-right" <?php echo $referenceCheckLabel;?>>
													<label class="col-form-label">Cheque Photo </label>
												</div>
												<div class="form-group col-md-5 check_label text-right" <?php echo $referenceCheckLabel;?>>
													<input type="file" name="cheque_photo" id="cheque_photo" class="form-control" autocomplete="off" >
													<?php 
														if (isset($type) && $type=="edit") 
														{
															if( !empty($edit_data[0]['cheque_photo']) && file_exists("uploads/checque/".$edit_data[0]['cheque_photo']) )
															{
																?>
																<br>
																<img src="<?php echo base_url(); ?>uploads/checque/<?php echo $edit_data[0]['cheque_photo']; ?>" width="75" height="75">
																<a download href="<?php echo base_url(); ?>uploads/checque/<?php echo $edit_data[0]['cheque_photo']; ?>"><i class="fa fa-download"></i></a>
																<?php
															}
														}
													?>
												</div>
											</div>
										</div>
									</div>
								</section>
								
								<!-- Line level start here -->
								<div class="row mb-3">
									<div class="col-md-6 header-filters">
										<a href="javascript:void(0)" class="filter-icons thi_sec_hide" onclick="sectionShow('THIRD_SECTION','SHOW');">
											<i class="fa fa-chevron-circle-down"></i>
										</a>
										<a href="javascript:void(0)" class="filter-icons thi_sec_show" onclick="sectionShow('THIRD_SECTION','HIDE');" style="display:none;">
											<i class="fa fa-chevron-circle-right"></i>
										</a>
										<h4 class="pl-1"><b>Lines</b></h4>
									</div>
									<div class="col-md-6 text-right float-right">
										<span style="color:blue;">Currency : <?php echo CURRENCY_CODE;?></span>
									</div>
								</div>
							</fieldset>
							
							<!-- party_orders - Line data start -->
							<section class="line_tbl thi_section party_orders" style='display:none;'>
								<div class="row">
									<div class="col-sm-12">
										<div class="form-group">
											<div style="overflow-y: auto;">
												<div id="err_product" style="color:red;margin: 0px 0px 10px 0px;"></div>
												<table class="table items --table-striped table-bordered table-condensed table-hover product_table" name="product_data" id="product_data">
													<thead>
														<tr>
															<th>Invoice #</th>
															<th>Invoice Date</th>
															<th>Payment Terms</th>
															<th class="text-right">Amount</th>
															<th class="text-right">Balance</th>
															<th class="text-right"><span class="text-danger">*</span> Payment Amount</th>
														</tr>
													</thead>
													<tbody id="product_table_body">
														
													</tbody>
												</table>
											</div>

											<div class="loader_image" style="text-align:center;display:none;">
												<img src="<?php echo base_url();?>uploads/search_loader.gif" style="width:180px;">
											</div>
											
											<table class="table table-bordered table-hover">
												<tr>
													<td colspan="8" style="width:201px;" class="text-right"><b>Total ( <?php echo CURRENCY_SYMBOL;?> ) </b></td>
													<td class="text-right">
														<span id="grandTotal">&nbsp;0.00</span>
														<input type="hidden" name="totalPayamount" id="totalPayamount">
													</td>
												</tr>	
											</table>
										</div>
									</div>
								</div>
							</section>
							<!-- party_orders - Line data end -->

							<!-- online_orders - Line data start -->
							<section class="online_orders" style='display:none;'>
								<div class="row">
									<div class="col-sm-12">
										<div class="form-group">
											<div style="overflow-y: auto;">
												<div id="err_product_online_orders" style="color:red;margin: 0px 0px 10px 0px;"></div>
												<table class="table items table-bordered table-condensed table-hover tbl_online_orders" id="tbl_online_orders">
													<thead>
														<tr>
															<th>Invoice #</th>
															<th>Invoice Date</th>
															<th class="text-right">Amount</th>
															<th class="text-right">Balance</th>
															<th class="text-right"><span class="text-danger">*</span> Payment Amount</th>
														</tr>
													</thead>
													<tbody id="tbl_body_online_orders">
														
													</tbody>
												</table>
											</div>

											<div class="loader_image_online_orders" style="text-align:center;display:none;">
												<img src="<?php echo base_url();?>uploads/search_loader.gif" style="width:180px;">
											</div>
											
											<table class="table table-bordered table-hover">
												<tr>
													<td colspan="7" style="width:201px;" class="text-right"><b>Total ( <?php echo CURRENCY_SYMBOL;?> ) </b></td>
													<td class="text-right">
														<span id="grandTotal_online_orders">&nbsp;0.00</span>
														<input type="hidden" name="totalPayamount" id="totalPayamount_online_orders">
													</td>
												</tr>	
											</table>
										</div>
									</div>
								</div>
							</section>
							<!-- online_orders - Line data end -->

							<div class="col-md-12 mt-3 pr-0 text-right">
								<?php 
									if($type == "add" || $type == "edit")
									{
										?>
										<!-- <a href="javascript:void(0)" id="save_btn" onclick="return saveBtn('save_btn','save');" class="btn btn-primary btn-sm submit_btn_bottom">Save Bottom</a> -->
										<button type="submit" name="save_btn" id="save_btn" onclick="return saveBtn('save_btn');" title="Save & Continue" class="btn btn-primary btn-sm">Save</button>
										<button type="submit" name="submit_btn" id="submit_btn" onclick="return saveBtn('submit_btn');" title="Submit" class="btn btn-primary btn-sm">Submit</button>
										<?php 
									} 
								?>
								<a href="<?php echo base_url(); ?>payment/manageCustomerPayment" class="btn btn-default btn-sm">Close</a>
							</div>
						</div>
					</form>

					<script>
						function saveBtn(val) 
						{
							var payment_method = $("#payment_method").val();
							var payment_date = $("#payment_date").val();
							var invoice_source = $("#invoice_source").val();

							var from_date = $("#from_date").val();
							var to_date = $("#to_date").val();
							
							if (payment_method && payment_date && invoice_source)
							{
								$(".payment_method").removeClass('errorClass');
								$(".payment_date").removeClass('errorClass');
								$(".invoice_source").removeClass('errorClass');

								if(invoice_source == "ONLINE-ORDERS") 
								{
									$(".order_customer_id").removeClass('errorClass');
									$(".from_date").removeClass('errorClass');
									$(".to_date").removeClass('errorClass');
								}
								else if(invoice_source == "PARTY-ORDERS")
								{
									$(".customer_id").removeClass('errorClass');
								}

								return true; 
							} 
							else 
							{
								if (invoice_source) {
									$(".invoice_source").removeClass('errorClass');
								} else {
									$(".invoice_source").addClass('errorClass');
								}
								
								if (payment_date) {
									$(".payment_date").removeClass('errorClass');
								} else {
									$(".payment_date").addClass('errorClass');
								}

								if (payment_method) {
									$(".payment_method").removeClass('errorClass');
								} else {
									$(".payment_method").addClass('errorClass');
								}
								
								if(invoice_source == "ONLINE-ORDERS") 
								{
									var order_customer_id = $("#order_customer_id").val();

									$('#customer_id').removeAttr('required');

									$('#order_customer_id').attr('required', 'required');
									$('#from_date').attr('required', 'required');
									$('#to_date').attr('required', 'required');

									if (order_customer_id) {
										$(".order_customer_id").removeClass('errorClass');
									} else {
										$(".order_customer_id").addClass('errorClass');
									}
									
									if (from_date) {
										$(".from_date").removeClass('errorClass');
									} else {
										$(".from_date").addClass('errorClass');
									}
									
									if (to_date) {
										$(".to_date").removeClass('errorClass');
									} else {
										$(".to_date").addClass('errorClass');
									}
								}
								else if(invoice_source == "PARTY-ORDERS")
								{
									$('#customer_id').attr('required');

									$('#order_customer_id').removeAttr('required', 'required');
									$('#from_date').removeAttr('required', 'required');
									$('#to_date').removeAttr('required', 'required');

									var customer_id = $("#customer_id").val();

									if (customer_id) {
										$(".customer_id").removeClass('errorClass');
									} else {
										$(".customer_id").addClass('errorClass');
									}
								}
								return false;
							}
						} 

						$("#payment_btn").click(function () 
						{
							var tot_amount = $("#total_amount").val();
							var paymentAmount = 0;

							$("table.product_table").find('input[name^="payment_amount[]"]').each(function () 
							{
								paymentAmount += +$(this).val();
							});

							if(tot_amount == paymentAmount)
							{
								return true;
							}
							else
							{
								Swal.fire({
									icon: 'error',
									title: 'Amount Mismatch...',
									text: 'Total Amount not matched with Line Total!',
									//footer: '<a href="">Why do I have this issue?</a>'
								})
								return false;
							}
						});

						
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
						}
						
						function validatePayment(input, balance, counter) {
						
							var paymentAmount = parseFloat(input.value);
							
							if (paymentAmount > balance) {
								input.value = balance;
								
								Swal.fire({
									icon: 'error',
									title: 'Oops...',
									text: 'Payment amount cannot exceed the balance amount.',
									showCancelButton: true,
									confirmButtonText: 'OK'
								}).then((result) => {
									if (result.isConfirmed) {
										// Zero out the payment amount column
										$('#payment_amount_' + counter).val(balance);
									}
								});
							}
						}
			
						function getPaymentLines()
						{
							$(".line_tbl").hide();
							$(".removeTableTr").remove();
							$("#total_amount").val("");
							$("#grandTotal").html("0.00");
							$("#totalPayamount").val("0.00");

							var customer_id = $("#customer_id").val();
							
							var payment_method = $("#payment_method").val();
							var total_amount = $("#total_amount").val();
							
							if(customer_id)
							{
								$(".customer_id").removeClass('errorClass');
								$(".payment_method").removeClass('errorClass');
								$(".total_amount").removeClass('errorClass');

								$(".line_tbl").show();
								var i = 0;
								var counter = 1;

								var id = 1;
								$('#err_product').text('');	
								$(".removeTableTr").remove();

								$.ajax({
									url: "<?php echo base_url('payment/selectCustomerSales') ?>/"+customer_id,
									type: "GET",
									data:{
										'<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
									},
									datatype: "JSON",
									beforeSend: function()
									{
										$('.loader_image').show();
									},
									complete: function()
									{
										$('.loader_image').hide();
									},
									success: function(d)
									{
										data = JSON.parse(d);
										var countKey = Object.keys(data['salesList']).length;
										
										
										if(countKey > 0)
										{
											$("#select_all").show();
											
											$(".show-checkbox").show();

											$.each(data['salesList'], function(i, item) 
											{
												$("table.product_table").find('input[name^="invoice_id"]').each(function () 
												{
													var row = $(this).closest("tr");
													var invoice_id = +row.find('input[name^="invoice_id"]').val();
												});

												if( item.header_id == null ){
													var id = 0;
												}else{
													var id = item.header_id;
												}

												var invoice_id = item.header_id;
												var reference_no = item.invoice_number;
												var date = item.date;
												var inv_total = parseFloat(item.inv_total).toFixed(2);

												var payment_days = item.payment_days;
												var balance_amount = parseFloat(item.balance_amount).toFixed(2);
												var timestamp = date;
												var formattedDate = moment(timestamp).format("DD-MMM-YYYY");
												var salesDate = formattedDate;
											
												var newRow = $("<tr class='removeTableTr'>");
												var cols = "";
												cols += '<td>'
												+"<input type='hidden' name='counter' name='counter' value="+counter+">"
												+"<input type='hidden' name='id' name='id' value="+i+">"
												+"<input type='hidden' name='invoice_id[]' id='invoice_id_"+counter+"' value="+invoice_id+">"
												+"<input type='hidden' name='inv_total[]' id='inv_total"+counter+"' value="+inv_total+">"
												+reference_no+'</td>';
												cols += '<td>'+salesDate+'</td>';
												cols += '<td>'+payment_days+' </td>';
												cols += '<td class="text-right">'+inv_total+'</td>';
												cols += '<td class="text-right">'+balance_amount
													+"<input type='hidden' name='balance_amount[]' id='balance_amount_"+counter+"' value='"+balance_amount+"'>" 
													+'</td>';
												cols += "<td class='tab-medium-width'><input type='number' name='payment_amount[]' id='payment_amount_" + counter + "' class='form-control text-right' oninput='validatePayment(this, " + balance_amount + " , "+ counter +")'></td>";
												cols += "</tr>";
												
												newRow.html(cols);
												$("table.product_table").append(newRow);
												i++;
												counter++;
											});
										}
									},
									error: function(xhr, status, error) 
									{
										$('#err_product').text('Error!').animate({opacity: '0.0'}, 2000).animate({opacity: '0.0'}, 1000).animate({opacity: '1.0'}, 2000);
									}

									
								});

							}
							else
							{
								if(customer_id)
								{
									$(".customer_id").removeClass('errorClass');
								}
								else 
								{
									$(".customer_id").addClass('errorClass');
								}

								if(account_id)
								{
									$(".account_id").removeClass('errorClass');
								}
								else 
								{
									$(".account_id").addClass('errorClass');
								}

								if(payment_method)
								{
									$(".payment_method").removeClass('errorClass');
								}
								else 
								{
									$(".payment_method").addClass('errorClass');
								}

								if(total_amount)
								{
									$(".total_amount").removeClass('errorClass');
								}
								else 
								{
									$(".total_amount").addClass('errorClass');
								}
							}
						}

						$("table.product_table").on("input keyup change", 'input[name^="payment_amount[]"]', function (event) 
						{
							calculateGrandTotal();
						});

						function calculateGrandTotal() 
						{
							var paymentAmount = 0;
							
							$("table.product_table").find('input[name^="payment_amount[]"]').each(function () 
							{
								paymentAmount += +$(this).val();
							});

							$('#totalPayamount').val(paymentAmount.toFixed(2));
							$('#grandTotal').text(paymentAmount.toFixed(2));
						}
					</script>

					<!-- Online Order Script Start -->
					<script>
						function getOnlinePaymentLines(val)
						{
							var order_customer_id = $("#order_customer_id").val();
							var from_date = $("#from_date").val();
							var to_date = $("#to_date").val();

							if(order_customer_id && from_date && to_date )
							{
								$(".order_customer_id").removeClass('errorClass');
								$(".from_date").removeClass('errorClass');
								$(".to_date").removeClass('errorClass');

								getAjaxloadPaymentLines(order_customer_id,from_date,to_date);
								
								return true; 
							} 
							else 
							{
								if (order_customer_id) {
									$(".order_customer_id").removeClass('errorClass');
								} else {
									$(".order_customer_id").addClass('errorClass');
								}
								
								if (from_date) {
									$(".from_date").removeClass('errorClass');
								} else {
									$(".from_date").addClass('errorClass');
								}
								
								if (to_date) {
									$(".to_date").removeClass('errorClass');
								} else {
									$(".to_date").addClass('errorClass');
								}
								return false;
							}
						}

						function getAjaxloadPaymentLines(order_customer_id,from_date,to_date)
						{
							var customer_id = order_customer_id;
							
							$(".online_orders").show();
							var i = 0;
							var counter = 1;

							var id = 1;
							$('#err_product_online_orders').text('');	
							$(".removeTableTr").remove();

							$.ajax({
								url: "<?php echo base_url('payment/selectCustomerOnlineSales') ?>/"+customer_id+"/"+from_date+"/"+to_date,
								type: "GET",
								data:{
									'<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
								},
								datatype: "JSON",
								beforeSend: function()
								{
									$('.loader_image_online_orders').show();
								},
								complete: function()
								{
									$('.loader_image_online_orders').hide();
								},
								success: function(d)
								{
									data = JSON.parse(d);
									var countKey = Object.keys(data['salesList']).length;
									
									if(countKey > 0)
									{
										$.each(data['salesList'], function(i, item) 
										{
											$("table.tbl_online_orders").find('input[name^="invoice_id"]').each(function () 
											{
												var row = $(this).closest("tr");
												var invoice_id = +row.find('input[name^="invoice_id"]').val();
											});

											if( item.header_id == null ){
												var id = 0;
											}else{
												var id = item.header_id;
											}

											var invoice_id = item.header_id;
											var reference_no = item.invoice_number;
											var date = item.date;
											var inv_total = parseFloat(item.payment_amount).toFixed(2);

											
											var balance_amount = parseFloat(item.balance_amount).toFixed(2);
											
											var timestamp = date;
											var formattedDate = moment(timestamp).format("DD-MMM-YYYY");
											var salesDate = formattedDate;
										
											var newRow = $("<tr class='removeTableTr'>");
											var cols = "";
											cols += '<td>'
											+"<input type='hidden' name='counter' name='counter' value="+counter+">"
											+"<input type='hidden' name='id' name='id' value="+i+">"
											+"<input type='hidden' name='invoice_id[]' id='invoice_id_"+counter+"' value="+invoice_id+">"
											+"<input type='hidden' name='inv_total[]' id='inv_total"+counter+"' value="+inv_total+">"
											+reference_no+'</td>';
											cols += '<td>'+salesDate+'</td>';
											cols += '<td class="text-right">'+inv_total+'</td>';
											cols += '<td class="text-right">'+balance_amount
												+"<input type='hidden' name='balance_amount[]' id='balance_amount_"+counter+"' value='"+balance_amount+"'>" 
												+'</td>';
											cols += "<td class='tab-medium-width'><input type='number' name='payment_amount_ord[]' id='payment_amount_ord_" + counter + "' class='form-control text-right' oninput='validatePayment(this, " + balance_amount + " , "+ counter +")'></td>";
											cols += "</tr>";
											
											newRow.html(cols);
											$("table.tbl_online_orders").append(newRow);
											i++;
											counter++;
										});
									}
								},
								error: function(xhr, status, error) 
								{
									$('#err_product_online_orders').text('Error!').animate({opacity: '0.0'}, 2000).animate({opacity: '0.0'}, 1000).animate({opacity: '1.0'}, 2000);
								}	
							});
						}

						$("table.tbl_online_orders").on("input keyup change", 'input[name^="payment_amount_ord[]"]', function (event) 
						{
							calcOrderGrandTotal();
						});

						function calcOrderGrandTotal() 
						{
							var paymentAmount = 0;
							
							$("table.tbl_online_orders").find('input[name^="payment_amount_ord[]"]').each(function () 
							{
								paymentAmount += +$(this).val();
							});

							$('#totalPayamount_online_orders').val(paymentAmount.toFixed(2));
							$('#grandTotal_online_orders').text(paymentAmount.toFixed(2));
						}

					</script>
					<!-- Online Order Script Start -->
					<?php
				}
				else
				{ 
					?>
					<div class="row mb-2">
						<div class="col-md-6"><h3><b><?php echo $page_title;?></b></h3></div>
						<div class="col-md-6 float-right text-right">
							<?php
								if($paymentsMenu['create_edit_only'] == 1 || $this->user_id == 1)
								{
									?>
									<a href="<?php echo base_url(); ?>payment/manageCustomerPayment/add" class="btn btn-sm btn-info">
										Create Customer Payment
									</a>
									<?php 
								} 
							?>
						</div>
					</div>
					
					<form action="" method="get">
						<div class="row mt-2">
							
							<div class="col-md-4">
								<div class="row">
									<label class="col-form-label col-md-4 text-right">Invoice Source</label>
									<div class="form-group col-md-7">
										<?php 
											$getInvoiceSource = $this->common_model->lov('INVOICE-SOURCE'); 
										?>
										<select name="invoice_source" id="invoice_source" --onchange="selectInvoiceSource(this.value);" class="form-control searchDropdown">
											<option value="">-  Select -</option>
											<?php 
												foreach($getInvoiceSource as $invoice_source)
												{
													if(isset($_GET["invoice_source"]) && $_GET["invoice_source"] == $invoice_source["list_code"]){
														$selected = "selected=selected";
													}else{
														$selected = "";
													}

													?>
													<option value="<?php echo $invoice_source["list_code"];?>" <?php echo $selected;?>><?php echo $invoice_source["list_value"];?></option>
													<?php 
												} 
											?>
										</select>
									</div>
								</div>
							</div>

							<div class="col-md-4">
								<div class="row">
									<label class="col-form-label col-md-4 text-right">Payment #</label>
									<div class="form-group col-md-7">
										<div class="input-wrapper">
											<input type="text" name="payment_number" autocomplete="off" id="payment_number" value="<?php echo isset($_GET['payment_number']) ? $_GET['payment_number'] : NULL; ?>" placeholder="Payment Number" class="form-control">
											<input type="hidden" name="payment_id" autocomplete="off" id="payment_id" value="<?php echo isset($_GET['payment_id']) ? $_GET['payment_id'] : NULL; ?>" >
											<div id="PaymentList"></div><!-- Clear icon start -->
											<?php 
												if(isset($_GET["payment_id"]) && !empty($_GET["payment_id"]))
												{
													$styleDisplay = "display:block";
												}
												else{
													$styleDisplay = "display:none";
												}
												?>
											<span class="payment_clear_icon" title="Clear" onclick="clearPaymentSearchKeyword();" style="<?php echo $styleDisplay;?>">
												<i class="fa fa-times" aria-hidden="true"></i>
											</span>

											<script>
												$(document).ready(function()
												{  
													$('#payment_number').keyup(function()
													{  
														var query = $(this).val();  

														if(query != '')  
														{  
															$.ajax({  
																url:"<?php echo base_url();?>payment/ajaxPaymentList",  
																method:"POST",  
																data:{query:query},  
																success:function(data)  
																{  
																	$('#PaymentList').fadeIn();  
																	$('#PaymentList').html(data);  
																}  
															});  
														}  
													});

													$(document).on('click', 'ul.list-unstyled-payment_id li', function()
													{  
														var value = $(this).text();
														
														if(value === "Sorry! Payment Number Not Found.")
														{
															$('#PaymentList').fadeOut();
														}
														else
														{
															$('#PaymentList').fadeOut();  
														}
													});
												});

												function getPaymentList(payment_id,payment_number)
												{
													$('.payment_clear_icon').show();
													if(payment_id == 0)	
													{
														$('#payment_id').val('0');
													}
													else
													{
														$('#payment_id').val(payment_id);
														$('#payment_number').val(payment_number);
													}
												}

												function clearPaymentSearchKeyword()
												{
													$(".payment_clear_icon").hide();
													$("#payment_id").val("");
													$("#payment_number").val("");
												}
											</script>

										</div>
									</div>
								</div>
							</div>

							<div class="col-md-4">
								<div class="row">
									<label class="col-form-label col-md-4 text-right">Customer</label>
									<div class="form-group col-md-7">
										<div class="input-wrapper">
											<input type="search" name="customer_name" autocomplete="off" id="customer_name" value="<?php echo isset($_GET['customer_name']) ? $_GET['customer_name'] : NULL; ?>" placeholder="Customer Name" class="form-control">
											
											<?php /*
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
											*/ ?>

											<!-- <script>
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
											</script> -->

										</div>
									</div>
								</div>
							</div>
							
						</div>

						<div class="row mt-2">
							<div class="col-md-4">
								<div class="row">
									<label class="col-form-label col-md-4 text-right">Collection Mode</label>
									<div class="form-group col-md-7">
										<?php 
											$pamentTypesQry = "select payment_type_id,payment_type from expense_payment_type where payment_type_status = 1";
											$getPamentTypes = $this->db->query($pamentTypesQry)->result_array();
										?>
										<select name="payment_method" id="payment_method" class="form-control searchDropdown">
											<option value="">- Select -</option>
											<?php 
												foreach($getPamentTypes as $row)
												{
													$selected = "";
													if ( isset($_GET['payment_method']) && $_GET['payment_method'] == $row["payment_type_id"] ) 
													{
														$selected = "selected='selected'";
													}
													?>
													<option value="<?php echo $row["payment_type_id"];?>" <?php echo $selected;?>><?php echo ucfirst($row["payment_type"]);?></option>
													<?php 
												} 
											?>
										</select>		
									</div>
								</div>
							</div>

							<div class="col-md-4">
								<div class="row">
									<label class="col-form-label col-md-4 text-right">From Date</label>
									<div class="form-group col-md-7">
										<input type="text" name="from_date" id="from_date" class="form-control" readonly value="<?php echo !empty($_GET['from_date']) ? $_GET['from_date'] : ""; ?>" placeholder="From Date">
									</div>
								</div>
							</div>

							<div class="col-md-4">
								<div class="row">
									<label class="col-form-label col-md-4 text-right">To Date</label>
									<div class="form-group col-md-7">
										<input type="text" name="to_date" id="to_date" class="form-control" readonly value="<?php echo !empty($_GET['to_date']) ? $_GET['to_date'] :""; ?>" placeholder="To Date">
									</div>
								</div>
							</div>
						</div>

						<div class="row mt-2">
							<div class="col-md-2 offset-10">
								<button type="submit" class="btn btn-info">Search <i class="fa fa-search" aria-hidden="true"></i></button>
								<a href="<?php echo base_url(); ?>payment/manageCustomerPayment" title="Clear" class="btn btn-default">Clear</a>
							</div>
						</div>
					</form>	
					
					<?php 
						if(isset($_GET) &&  !empty($_GET))
						{
							?>
							<div class="row mt-3">
								<div class="col-md-8 mt-2">
									<?php 
										if(count($resultData) > 0)
										{
											?>
											<a href="<?php echo base_url().$this->redirectURL.'&download_excel=download_excel'; ?>" target="_blank" title="Download to Excel" class="btn btn-sm btn-primary">
												Download to Excel
											</a>
											<?php
										} 
									?>
								</div>

								<?php 
									$redirect_url = substr($_SERVER['REQUEST_URI'],'1');
								?>
								<div class="col-md-4">
									<input type="hidden" id="redirect_url" value="<?php echo $redirect_url; ?>"/>
															
									<div class="filter_page">
										<label>
											<span style="color:blue;">Currency : <?php echo CURRENCY_CODE;?></span> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
											
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
								<table id="myTable" class="table table-bordered table-hover --table-striped dataTable" style="width:130%;">
									<thead>
										<tr>
											<th class="text-center tab-md-100">Controls</th>
											<th class="tab-md-100">Invoice Source</th>
											<th class="tab-md-100">Payment #</th>
											<th class="tab-md-100">Customer Name</th>
											<th class="tab-md-100">Collection Mode</th>
											<th class="tab-md-100">Payment Date</th>
											<th class="text-right tab-md-100">Amount</th>
										</tr>
									</thead>
									<tbody>
										<?php 	
											$firstItem = $first_item;
											$totalInvoiceAmount = 0;
											foreach($resultData as $row)
											{
												?>
												<tr>
													<td class="text-center">
														<?php
															if($paymentsMenu['read_only'] == 1 || $this->user_id == 1)
															{
																?>
																<a class="btn btn-primary btn-sm" href="<?php echo base_url(); ?>payment/viewCustomerPayment/<?php echo $row['header_id'];?>" title="View Customer Payment">
																	<i class="fa fa-eye"></i>
																</a>

																<a class="btn btn-primary btn-sm" href="<?php echo base_url();?>payment/customerPaymentPDF/<?php echo $row['header_id'];?>" title="Print Customer Payment" target="_blank">
																	<i class="fa fa-file-pdf-o"></i>
																</a>
																<?php 
															}
															else
															{
																?>--
																<?php
															} 
														?>
													</td>

													<td>
														<?php echo $row['invoice_source_name'];?>
													</td>

													<td>
														<?php echo $row['payment_number'];?>
													</td>

													<td>
														<?php
															if($row["invoice_source"] == "PARTY-ORDERS") 
															{
																echo $row['customer_name'];
															}
															else if($row["invoice_source"] == "ONLINE-ORDERS") 
															{
																echo $row['con_customer_name'];
															}
														?>
													</td>

													<td>
														<?php echo $row['payment_type'];?>
													</td>
													
													<td>
														<?php echo date(DATE_FORMAT,strtotime($row['payment_date']));?>
													</td>
													

													<td class="text-right">
														<?php echo number_format($row['amount'],DECIMAL_VALUE,'.','');?>
													</td>
												</tr>
												<?php
												$totalInvoiceAmount += $row['amount'];
											}
										?>

										<?php 
											if(count($resultData) > 0)
											{
												?>
												<tr>
													<td colspan="6" class="text-right">
														<b>Total Amount :</b>
													</td>
													<td class="text-right">
														<b>
															<?php 
																echo number_format($totalInvoiceAmount,DECIMAL_VALUE,'.','');
															?>
														</b>
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
		</div>
	</div><!-- Card end-->
</div><!-- Content end-->
