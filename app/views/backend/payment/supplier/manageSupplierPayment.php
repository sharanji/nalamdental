<?php $supplier_paymentMenu = accessMenu(supplier_payment); ?>
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
											<?php echo $page_title ?>
											
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
									<a href="<?php echo base_url(); ?>payment/manageSupplierPayment" class="btn btn-default btn-sm">Close</a>
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
														<label class="col-form-label">Supplier ID</label>
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
													
													<div class="form-group text-right">
														<label class="col-form-label supplier_id"><span class="text-danger">*</span> Supplier </label>
													</div>				
												</div>
												<div class="col-md-5">
													<div class="input-wrapper">
														<input type="text" name="supplier_name" autocomplete="off" id="supplier_name" value="<?php echo isset($_GET['supplier_name']) ? $_GET['supplier_name'] : NULL; ?>" placeholder="Supplier Name" class="form-control">
														<input type="hidden" name="supplier_id" autocomplete="off" id="supplier_id" value="<?php echo isset($_GET['supplier_id']) ? $_GET['supplier_id'] : NULL; ?>" >
														<div id="SupplierList"></div><!-- Clear icon start -->
														<?php 
															if(isset($_GET["supplier_id"]) && !empty($_GET["supplier_id"]))
															{
																$styleDisplay = "display:block";
															}else{
																$styleDisplay = "display:none";
															}
															?>
														<span class="supplier_clear_icon" title="Clear" onclick="clearSupplierSearchKeyword();" style="<?php echo $styleDisplay;?>">
															<i class="fa fa-times" aria-hidden="true"></i>
														</span>

														<script>
															$(document).ready(function()
															{  
																$('#supplier_name').keyup(function()
																{  
																	var query = $(this).val();  
																
																	if(query != '')  
																	{  
																		$.ajax({  
																			url:"<?php echo base_url();?>suppliers/ajaxSupplierList",  
																			method:"POST",  
																			data:{query:query},  
																			success:function(data)  
																			{  
																				$('#SupplierList').fadeIn();  
																				$('#SupplierList').html(data);  
																			}  
																		});  
																	}  
																});

																$(document).on('click', 'ul.list-unstyled-supplier_id li', function()
																{  
																	var value = $(this).text();
																	
																	if(value === "Sorry! Supplier Not Found.")
																	{
																		$('#SupplierList').fadeOut();
																	}
																	else
																	{
																		$('#SupplierList').fadeOut();  
																	}
																});
															});

															function getSupplierList(supplier_id,supplier_name)
															{
																$('.supplier_clear_icon').show();
																if(supplier_id == 0)	
																{
																	$('#supplier_id').val('0');
																}
																else
																{
																	$('#supplier_id').val(supplier_id);
																	$('#supplier_name').val(supplier_name);

																	$.ajax({
																		url: "<?php echo base_url();?>suppliers/ajaxSupplierSiteList",  
																		method: "POST",  
																		data: { query: supplier_id },  
																		success: function(data) {
																			$("#supplier_site_id").html(data);
																		},
																		error: function(xhr, status, error) {
																			console.error(xhr.responseText);
																		}
																	});
																}
															}

															function clearSupplierSearchKeyword()
															{
																$(".supplier_clear_icon").hide();
																$("#supplier_id").val("");
																$("#supplier_name").val("");
															}
														</script>

													</div>	
												</div>
											</div>
											<div class="row">
												<div class="col-md-3">
													<?php 
													
														$getSupplierSite = $this->suppliers_model->getAjaxSupplierSites();
													?>
													<div class="form-group text-right">
														<label class="col-form-label supplier_site_id"><span class="text-danger">*</span> Supplier Site </label>
													</div>				
												</div>
												<div class="col-md-5">
												<select name="supplier_site_id" id="supplier_site_id" onchange="getPaymentLines();" class="form-control searchDropdown">
													<option value="">- Select -</option>
													<!-- Supplier site options will be appended here using AJAX -->
												</select>		
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
														<label class="col-form-label payment_method"><span class="text-danger">*</span> Payment Mode </label>
													</div>	
												</div>
												<div class="col-md-5">
													<select name="payment_method" id="payment_method" onchange="selectPaymentSource(this.value);" class="form-control searchDropdown">
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
											
											<?php /*
												?>
													<div class="row">
														<div class="col-md-3">
															<div class="form-group text-right">
																<label class="col-form-label total_amount">Total amount <span class="text-danger">*</span></label>
															</div>	
														</div>
														<div class="col-md-5">
															<input type="number" name="total_amount" id="total_amount" required value="<?php echo isset($edit_data[0]['tot_amount']) ? $edit_data[0]['tot_amount'] :"";?>" autocomplete="off" class="form-control" autocomplete="off" >
														</div>
													</div>
												<?php
											
											*/ ?>

										</div>
										<div class="col-md-6">
											

											<div class="row">
												<div class="col-md-3">
													<div class="form-group text-right payment_date">
														<label class="col-form-label payment_date"><span class="text-danger">*</span> Payment Date</label>
													</div>				
												</div>
												<div class="col-md-4">
													<div class="form-group">
														<input type="text" name="payment_date" id="payment_date" readonly autocomplete="off" class="form-control future_date" required id="payment_date" value="<?php echo date("d-M-Y");?>">
													</div>				
												</div>
											</div>

											<?php /*
												?>
													<div class="row">
														<div class="col-md-3">
															<div class="form-group text-right">
																<label class="col-form-label">Customer Bank</label>
															</div>
														</div>
														<div class="col-md-5">
															<div class="form-group">
																<textarea class="form-control" rows="1" autocomplete="off" name="customer_bank_account"><?php echo isset($edit_data[0]['customer_bank_account']) ? $edit_data[0]['customer_bank_account']:"";?></textarea>
															</div>
														</div>
													</div>
												<?php
											
											*/ ?>
											
											<div class="row">
												<div class="form-group col-md-3 reference_div text-right" <?php echo $referenceIDDiv;?>>
													<label class="col-form-label refernce_label text-right" <?php echo $referenceLabel;?>>Reference ID </label>
													<label class="col-form-label check_label text-right" <?php echo $referenceCheckLabel;?>>Check No.</label>
												</div>
												<div class="form-group col-md-5 reference_div" <?php echo $referenceIDDiv;?>>
													<input type="text" name="reference_id" id="reference_id" value="<?php echo isset($edit_data[0]['reference_id']) ? $edit_data[0]['reference_id'] :"";?>" autocomplete="off" class="form-control" autocomplete="off" >
												</div>
												<script>
													function saveBtn(val) {
														var supplier_id = $("#supplier_id").val();
														var supplier_site_id = $("#supplier_site_id").val();
														var payment_method = $("#payment_method").val();
														var payment_date = $("#payment_date").val();

														if (!supplier_id || !supplier_site_id || !payment_method || !payment_date) {
															
															if (!supplier_id) {
																$(".supplier_id").addClass('errorClass');
															} else {
																$(".supplier_id").removeClass('errorClass')
															}

															if (!supplier_site_id) {
																$(".supplier_site_id").addClass('errorClass');
															} else {
																$(".supplier_site_id").removeClass('errorClass');
															}

															if (!payment_method) {
																$(".payment_method").addClass('errorClass');
															} else {
																$(".payment_method").removeClass('errorClass');
															}
															if (!payment_date) {
																$(".payment_date").addClass('errorClass');
															} else {
																$(".payment_date").removeClass('errorClass');
															}

															return false; 
														}

														else {
															$(".supplier_id, .supplier_site_id, .payment_method, .payment_date").removeClass('errorClass');
														}
													
														if ($("#product_data tbody tr").length === 0) {
															Swal.fire({
																icon: 'error',
																title: 'Oops...',
																text: 'Please add at least one line level record.'
															});
															return false;
														}

													
														var paymentAmountEntered = false;
														$("input[name='payment_amount[]']").each(function() {
															if ($(this).val() !== "") {
																paymentAmountEntered = true;
																return false; 
															}
														});

														if (!paymentAmountEntered) {
															Swal.fire({
																icon: 'error',
																title: 'Oops...',
																text: 'Please enter payment amount for at least one line level record.'
															});
															return false;
														}
														return true;
													}

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
													<input type="text" name="check_name" id="check_name" value="<?php echo isset($edit_data[0]['check_name']) ? $edit_data[0]['check_name'] :"";?>" autocomplete="off" class="form-control" autocomplete="off" >
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
														<textarea class="form-control" rows="1" autocomplete="off" name="description"><?php echo isset($edit_data[0]['description']) ? $edit_data[0]['description']:"";?></textarea>
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
							
							<!-- Line data start -->
							<section class="line_tbl thi_section" >
								<div class="row">
									<div class="col-sm-12">
										<div class="form-group">
											<div style="overflow-y: auto;">
												<div id="err_product" style="color:red;margin: 0px 0px 10px 0px;"></div>
												<table class="table items --table-striped table-bordered table-condensed table-hover product_table" name="product_data" id="product_data">
													<thead>
														<tr>
															<th>PO #</th>
															<th>Receipt #</th>
															<th>Receipt Date</th>
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
							<!-- Line data end -->

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
								<a href="<?php echo base_url(); ?>payment/manageSupplierPayment" class="btn btn-default btn-sm">Close</a>
							</div>
						</div>
					</form>

					<script>

						// function saveBtn(val) 
						// {
						// 	var customer_id = $("#customer_id").val();
						// 	var supplier_id = $("#supplier_id").val();
						// 	var supplier_site_id = $("#supplier_site_id").val();
						// 	var payment_method = $("#payment_method").val();
						// 	var  payment_date = $("#payment_date").val();
							
						// 	if (customer_id && payment_method && payment_date && supplier_id && supplier_site_id)
						// 	{
						// 		$(".customer_id").removeClass('errorClass');
						// 		$(".payment_method").removeClass('errorClass');
						// 		$(".payment_date").removeClass('errorClass');
						// 		$(".supplier_id").removeClass('errorClass');
						// 		$(".supplier_site_id").removeClass('errorClass');
								
						// 		return true; 
						// 	} 
						// 	else 
						// 	{
						// 		if (customer_id) {
						// 			$(".customer_id").removeClass('errorClass');
						// 		} else {
						// 			$(".customer_id").addClass('errorClass');
						// 		}
								
						// 		if (payment_method) {
						// 			$(".payment_method").removeClass('errorClass');
						// 		} else {
						// 			$(".payment_method").addClass('errorClass');
						// 		}
								
						// 		if (payment_date) {
						// 			$(".payment_date").removeClass('errorClass');
						// 		} else {
						// 			$(".payment_date").addClass('errorClass');
						// 		}
						// 		if (supplier_id) {
						// 			$(".supplier_id").removeClass('errorClass');
						// 		} else {
						// 			$(".supplier_id").addClass('errorClass');
						// 		}
						// 		if (supplier_site_id) {
						// 			$(".supplier_site_id").removeClass('errorClass');
						// 		} else {
						// 			$(".supplier_site_id").addClass('errorClass');
						// 		}
						// 		return false;
						// 	}
						// } 

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
							
							var supplier_id = $("#supplier_id").val();
							
							if(supplier_id)
							{
								$(".supplier_id").removeClass('errorClass');
								
								$(".line_tbl").show();
								var i = 0;
								var counter = 1;

								var id = 1;
								$('#err_product').text('');	
								$(".removeTableTr").remove();

								$.ajax({
									url: "<?php echo base_url('payment/selectSupplierSales') ?>/"+supplier_id,
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
												
												$("table.product_table").find('input[name^="receipt_id"]').each(function () 
												{
													var row = $(this).closest("tr");
													var receipt_id = +row.find('input[name^="receipt_id"]').val();
												});

												if( item.header_id == null ){
													var id = 0;
												}else{
													var id = item.header_id;
												}

												var receipt_id = item.header_id;
												var po_no = item.po_number;
												var reference_no = item.receipt_number;
												var date = item.date;
												var inv_total = parseFloat(item.inv_total).toFixed(2);

												var balance_amount = parseFloat(item.balance_amount).toFixed(2);
												var timestamp = date;
												var formattedDate = moment(timestamp).format("DD-MMM-YYYY");
												var salesDate = formattedDate;
											
												var newRow = $("<tr class='removeTableTr'>");
												var cols = "";
												cols += '<td>'+po_no+'</td>'
												cols += '<td>'
												+"<input type='hidden' name='counter' name='counter' value="+counter+">"
												+"<input type='hidden' name='id' name='id' value="+i+">"
												+"<input type='hidden' name='receipt_id[]' id='receipt_id"+counter+"' value="+receipt_id+">"
												+"<input type='hidden' name='inv_total[]' id='inv_total"+counter+"' value="+inv_total+">"
												+reference_no+'</td>';
												cols += '<td>'+salesDate+'</td>';
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
					<?php
				}
				else
				{ 
					?>
					<div class="row mb-2">
						<div class="col-md-6"><h3><b><?php echo $page_title;?></b></h3></div>
						<div class="col-md-6 float-right text-right">
							<?php
								if($supplier_paymentMenu['create_edit_only'] == 1 || $this->user_id == 1)
								{
									?>
									<a href="<?php echo base_url(); ?>payment/manageSupplierPayment/add" class="btn btn-sm btn-info">
										Create Supplier Payment
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
									<label class="col-form-label col-md-4 text-right">PO #</label>
									<div class="form-group col-md-7">
										<div class="input-wrapper">
											<input type="text" name="po_number" autocomplete="off" id="po_number" value="<?php echo isset($_GET['po_number']) ? $_GET['po_number'] : NULL; ?>" placeholder="Payment Number" class="form-control">
											<input type="hidden" name="po_header_id" autocomplete="off" id="po_header_id" value="<?php echo isset($_GET['po_header_id']) ? $_GET['po_header_id'] : NULL; ?>" >
											<div id="PoList"></div><!-- Clear icon start -->
											<?php 
												if(isset($_GET["po_header_id"]) && !empty($_GET["po_header_id"]))
												{
													$styleDisplay = "display:block";
												}
												else{
													$styleDisplay = "display:none";
												}
												?>
											<span class="po_clear_icon" title="Clear" onclick="clearPoSearchKeyword();" style="<?php echo $styleDisplay;?>">
												<i class="fa fa-times" aria-hidden="true"></i>
											</span>

											<script>
												$(document).ready(function()
												{  
													$('#po_number').keyup(function()
													{  
														var query = $(this).val();  

														if(query != '')  
														{  
															$.ajax({  
																url:"<?php echo base_url();?>purchase_order/ajaxPoList",  
																method:"POST",  
																data:{query:query},  
																success:function(data)  
																{  
																	$('#PoList').fadeIn();  
																	$('#PoList').html(data);  
																}  
															});  
														}  
													});

													$(document).on('click', 'ul.list-unstyled-po_header_id li', function()
													{  
														var value = $(this).text();
														
														if(value === "Sorry! PO Number Not Found.")
														{
															$('#PoList').fadeOut();
														}
														else
														{
															$('#PoList').fadeOut();  
														}
													});
												});

												function getPoList(po_header_id,po_number)
												{
													
													$('.po_clear_icon').show();
													if(po_header_id == 0)	
													{
														$('#po_header_id').val('0');
													}
													else
													{
														$('#po_header_id').val(po_header_id);
														$('#po_number').val(po_number);
													}
												}

												function clearPoSearchKeyword()
												{
													$(".po_clear_icon").hide();
													$("#po_header_id").val("");
													$("#po_number").val("");
												}
											</script>

										</div>
									</div>
								</div>
							</div>

							<div class="col-md-4">
								<div class="row">
									<label class="col-form-label col-md-4 text-right">Receipt #</label>
									<div class="form-group col-md-7">
										<div class="input-wrapper">
											<input type="text" name="receipt_number" autocomplete="off" id="receipt_number" value="<?php echo isset($_GET['receipt_number']) ? $_GET['receipt_number'] : NULL; ?>" placeholder="Receipt Number" class="form-control">
											<input type="hidden" name="receipt_header_id" autocomplete="off" id="receipt_header_id" value="<?php echo isset($_GET['receipt_header_id']) ? $_GET['receipt_header_id'] : NULL; ?>" >
											<div id="ReceiptList"></div><!-- Clear icon start -->
											<?php 
												if(isset($_GET["receipt_header_id"]) && !empty($_GET["receipt_header_id"]))
												{
													$styleDisplay = "display:block";
												}
												else{
													$styleDisplay = "display:none";
												}
												?>
											<span class="receipt_clear_icon" title="Clear" onclick="clearReceiptSearchKeyword();" style="<?php echo $styleDisplay;?>">
												<i class="fa fa-times" aria-hidden="true"></i>
											</span>

											<script>
												$(document).ready(function()
												{  
													$('#receipt_number').keyup(function()
													{  
														var query = $(this).val();  

														if(query != '')  
														{  
															$.ajax({  
																url:"<?php echo base_url();?>purchase_order/ajaxReceiptList",  
																method:"POST",  
																data:{query:query},  
																success:function(data)  
																{  
																	$('#ReceiptList').fadeIn();  
																	$('#ReceiptList').html(data);  
																}  
															});  
														}  
													});

													$(document).on('click', 'ul.list-unstyled-receipt_header_id li', function()
													{  
														var value = $(this).text();
														
														if(value === "Sorry! Receipt Number Not Found.")
														{
															$('#ReceiptList').fadeOut();
														}
														else
														{
															$('#ReceiptList').fadeOut();  
														}
													});
												});

												function getReceiptList(receipt_header_id,receipt_number)
												{
													
													$('.receipt_clear_icon').show();
													if(receipt_header_id == 0)	
													{
														$('#receipt_header_id').val('0');
													}
													else
													{
														$('#receipt_header_id').val(receipt_header_id);
														$('#receipt_number').val(receipt_number);
													}
												}

												function clearReceiptSearchKeyword()
												{
													$(".receipt_clear_icon").hide();
													$("#receipt_header_id").val("");
													$("#receipt_number").val("");
												}
											</script>

										</div>
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
																url:"<?php echo base_url();?>payment/ajaxSupplierPaymentList",  
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
							
						</div>

						<div class="row mt-2">
							<div class="col-md-4">
								<div class="row">
									<label class="col-form-label col-md-4 text-right">Supplier</label>
									<div class="form-group col-md-7">
										<div class="input-wrapper">
											<input type="text" name="supplier_name" autocomplete="off" id="supplier_name" value="<?php echo isset($_GET['supplier_name']) ? $_GET['supplier_name'] : NULL; ?>" placeholder="Supplier Name" class="form-control">
											<input type="hidden" name="supplier_id" autocomplete="off" id="supplier_id" value="<?php echo isset($_GET['supplier_id']) ? $_GET['supplier_id'] : NULL; ?>" >
											<div id="SupplierList"></div><!-- Clear icon start -->
											<?php 
												if(isset($_GET["supplier_id"]) && !empty($_GET["supplier_id"]))
												{
													$styleDisplay = "display:block";
												}else{
													$styleDisplay = "display:none";
												}
												?>
											<span class="supplier_clear_icon" title="Clear" onclick="clearSupplierSearchKeyword();" style="<?php echo $styleDisplay;?>">
												<i class="fa fa-times" aria-hidden="true"></i>
											</span>

											<script>
												$(document).ready(function()
												{  
													$('#supplier_name').keyup(function()
													{  
														var query = $(this).val();  
													
														if(query != '')  
														{  
															$.ajax({  
																url:"<?php echo base_url();?>suppliers/ajaxSupplierList",  
																method:"POST",  
																data:{query:query},  
																success:function(data)  
																{  
																	$('#SupplierList').fadeIn();  
																	$('#SupplierList').html(data);  
																}  
															});  
														}  
													});

													$(document).on('click', 'ul.list-unstyled-supplier_id li', function()
													{  
														var value = $(this).text();
														
														if(value === "Sorry! Supplier Not Found.")
														{
															$('#SupplierList').fadeOut();
														}
														else
														{
															$('#SupplierList').fadeOut();  
														}
													});
												});

												function getSupplierList(supplier_id,supplier_name)
												{
													$('.supplier_clear_icon').show();
													if(supplier_id == 0)	
													{
														$('#supplier_id').val('0');
													}
													else
													{
														$('#supplier_id').val(supplier_id);
														$('#supplier_name').val(supplier_name);

														$.ajax({
															url: "<?php echo base_url();?>suppliers/ajaxSupplierSiteList",  
															method: "POST",  
															data: { query: supplier_id },  
															success: function(data) {
																$("#supplier_site_id").html(data);
															},
															error: function(xhr, status, error) {
																console.error(xhr.responseText);
															}
														});
													}
												}

												function clearSupplierSearchKeyword()
												{
													$(".supplier_clear_icon").hide();
													$("#supplier_id").val("");
													$("#supplier_name").val("");
												}
											</script>

										</div>
									</div>
								</div>
							</div>
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
													// if ( isset($pr_data[0]->payment_method) && $pr_data[0]->payment_method == $row["payment_type_id"] ) 
													// {
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

							<div class="col-md-2">
								<button type="submit" class="btn btn-info">Search <i class="fa fa-search" aria-hidden="true"></i></button>
								<a href="<?php echo base_url(); ?>payment/manageSupplierPayment" title="Clear" class="btn btn-default">Clear</a>
							</div>

						</div>
					</form>	
					
					<?php 
						if(isset($_GET) &&  !empty($_GET))
						{
							?>
							<?php 
								if(count($resultData) > 0)
								{
									?>
										<div class="row mt-3">
											<div class="col-md-8 mt-2">
													
												<a href="<?php echo base_url().$this->redirectURL.'&download_excel=download_excel'; ?>" title="Download to Excel" class="btn btn-sm btn-primary">
													Download to Excel
												</a>
															
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
									<?php
								} 
							?>
							<div class="new-scroller">
								<table id="myTable" class="table table-bordered table-hover --table-striped dataTable" style="width:130%;">
									<thead>
										<tr>
											<th class="text-center tab-md-100">Controls</th>
											<th class="tab-md-100">PO #</th>
											<th class="tab-md-100">Receipt #</th>
											<th class="tab-md-100">Payment #</th>
											<th class="tab-md-100">Supplier Name</th>
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
															if($supplier_paymentMenu['read_only'] == 1 || $this->user_id == 1)
															{
																?>
																<a class="btn btn-primary btn-sm" href="<?php echo base_url(); ?>payment/viewSupplierPayment/<?php echo $row['header_id'];?>" title="View Customer Payment">
																	<i class="fa fa-eye"></i>
																</a>

																<a class="btn btn-primary btn-sm" href="<?php echo base_url();?>payment/supplierPaymentPDF/<?php echo $row['header_id'];?>" title="Print Customer Payment" target="_blank">
																	<i class="fa fa-file-pdf-o"></i>
																</a>
																<?php 
															}
															else
															{
																?>
																--
																<?php
															} 
														?>	
													</td>

													<td>
														<?php echo $row['po_number'];?>
													</td>
													<td>
														<?php echo $row['receipt_number'];?>
													</td>
													<td>
														<?php echo $row['payment_number'];?>
													</td>

													<td>
														<?php echo $row['supplier_name'];?>
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
													<td colspan="7" class="text-right">
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
