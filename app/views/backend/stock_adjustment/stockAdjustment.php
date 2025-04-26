<?php 
	$physical_stock_adjustment = accessMenu(physical_stock_adjustment);
?>
<div class="content"><!-- Content start-->
	<div class="card"><!-- Card start-->
		<div class="card-body">
			<?php
				if(isset($type) && $type == "add" || $type == "edit" || $type == "view")
				{
					if($type == "view")
					{
						$fieldSetDisabled = "disabled";
						$searchDropdown = "";
						$this->fieldDisabled = $fieldDisabled = "";
						$this->fieldReadonly = $fieldReadonly = "";
					}
					else
					{
						if($type == "add" || $type == "edit")
						{
							$this->fieldDisabled = $fieldDisabled = "";
							$this->fieldReadonly = $fieldReadonly = "";
						} 
						
						$fieldSetDisabled = "";
						$searchDropdown = "searchDropdown";
					}
					?>
					<form action="" --class="form-validate-jquery" enctype="multipart/form-data" method="post">				
						<div class="header-lines">
							<!-- Buttons start here -->
							<div class="row mb-3">
								<div class="col-md-6">
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
								<div class="col-md-6 text-right">
									<?php 
										if($type == "add" || $type == "edit")
										{
											?>
											<button type="submit" name="save_btn" id="save_btn" onclick="return saveBtn('save_btn');" title="Save & Continue" class="btn btn-primary btn-sm">Save</button>
											<button type="submit" name="submit_btn" id="submit_btn" onclick="return saveBtn('submit_btn');" title="Submit" class="btn btn-primary btn-sm">Submit</button>
											<?php 
										} 
									?>
									<a href="<?php echo base_url(); ?>stock_adjustment/stockAdjustment" class="btn btn-default btn-sm">Close</a>
									
								</div>
							</div>
							<!-- Buttons end here -->
							
							<fieldset <?php echo $fieldSetDisabled;?>>
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
								<!-- Header Section Start Here-->
								<section class="header-section first_section">
									<div class="row">
										<div class="col-md-6">
											<div class="row">
												<div class="col-md-3">
													<div class="form-group text-right">
														<label class="col-form-label">Adjustment ID</label>
													</div>				
												</div>
												<div class="col-md-4">
													<div class="form-group">
														<input type="text" name="adj_number" id="adj_number" readonly autocomplete="off" class="form-control no-outline" value="<?php echo isset($edit_data[0]['adj_number']) ? $edit_data[0]['adj_number'] : NULL;?>" placeholder="">
													</div>				
												</div>
											</div>
											
											<div class="row">
												<div class="col-md-3">
													<div class="form-group text-right">
														<label class="col-form-label">Remarks</label>
													</div>				
												</div>
												<div class="col-md-4">
													<div class="form-group">
														<textarea name="remarks" id="remarks" rows="1" autocomplete="off" class="form-control" placeholder="Remarks"><?php echo isset($edit_data[0]['remarks']) ? $edit_data[0]['remarks'] : NULL;?></textarea>
													</div>				
												</div>
											</div>
											
										</div>
										
										<div class="col-md-6">
											<div class="row">
												<div class="col-md-3">
													<div class="form-group text-right">
														<label class="col-form-label adj_date"><span class="text-danger">*</span> Adjustment Date</label>
													</div>				
												</div>
												<div class="col-md-4">
													<div class="form-group">
														
														<input type="text" name="adj_date" id="adj_date" required readonly autocomplete="off" class="form-control future_date" value="<?php echo isset($edit_data[0]['adj_date']) ? date("d-M-Y",strtotime($edit_data[0]['adj_date'])) : date("d-M-Y");?>" placeholder="Adjustment Date">
													</div>				
												</div>
											</div>
										</div>
										
										
									</div>	
									
									
								</section>
								<!-- Header Section End Here-->

								<div class="row mb-3">
									<div class="col-md-6 header-filters">
										<a href="javascript:void(0)" class="filter-icons sec_sec_hide" onclick="sectionShow('SECOND_SECTION','SHOW');">
											<i class="fa fa-chevron-circle-down"></i>
										</a>
										<a href="javascript:void(0)" class="filter-icons sec_sec_show" onclick="sectionShow('SECOND_SECTION','HIDE');" style="display:none;">
											<i class="fa fa-chevron-circle-right"></i>
										</a>
										<h4 class="pl-1"><b>Lines</b></h4>
									</div>
									<div class="col-md-6 text-right float-right">
										<span style="color:blue;">Currency : <?php echo CURRENCY_CODE;?></span>
									</div>
								</div>

								<!-- Line level start here -->
								<section class="line-section mt-2 sec_section">
									

									<?php /*
										if($type == "add" || $type == "edit")
										{
											?>
											<div class="row mt-2 mb-3">
												<div class="col-md-6">
													<a href="javascript:void(0);" onclick="saveBtn('add_line_item');" id="addLineItem" class="btn btn-primary btn-sm">Add</a>
												</div>
												<div class="col-md-6 text-right">
													<span style="color:blue;">Currency : <?php echo CURRENCY_CODE;?></span>
												</div>
											</div>
											<?php 
										} 
									*/ ?>
									
									<div class="line-section-overflow">
										<table class="table table-bordered table-hover line_items" id="line_items">
											<thead>
												<tr>
													<?php 
														if($type == "add" || $type == "edit")
														{
															?>
															<th class="action-row tab-md-30"></th>
															<?php 
														} 
													?>
													<th class="text-center tab-md-100">Line No <span class="text-danger">*</span></th>
													<th class="tab-md-100">Item <span class="text-danger">*</span></th>
													<th class="tab-md-100">UOM</th>	
													<?php 
														if($type!='view'){
															?>
																<th class="tab-md-100">Current OHQ</th>
															<?php
														}
														
													?>										
													<th class="tab-md-100">Quantity <span class="text-danger">*</span></th>											
													<th class="tab-md-150">Organization</th>											
													<th class="tab-md-150">Sub Inventory</th>											
													<th class="tab-md-150">Locator</th>											
													<th class="tab-md-120">Lot Number</th>
													<th class="tab-md-100">Serial No</th>											
													<th class="tab-md-150">Remark</th>											
												</tr>
											</thead>
											<tbody>
												<?php 
													if( isset($lineData) )
													{
														foreach($lineData as $lineResult)
														{
															?>
															<tr>
																<td class="tab-md-85">
																	<input type="number" class="form-control" value="<?php echo $lineResult["line_num"];?>">
																</td>

																<td class="tab-md-200">
																	<input type="text" class="form-control" value="<?php echo $lineResult["item_name"];?>">
																</td>
																
																<td class="tab-md-85">
																	<input type="text" class="form-control" value="<?php echo $lineResult["uom_code"];?>">
																</td>
																<td class="tab-md-85">
																	<input type="text" class="form-control" value="<?php echo $lineResult["quantity"];?>">
																</td>

																<td class="tab-md-100">
																	<input type="text" class="form-control" value="<?php echo $lineResult["organization_name"];?>">
																</td>
																<td class="tab-md-100">
																	<input type="text" class="form-control" value="<?php echo $lineResult["inventory_code"];?>">
																</td>

																<td class="tab-md-100">
																	<input type="text" class="form-control" value="<?php echo $lineResult["locator_no"];?>">
																</td>

																<td class="tab-md-100">
																	<input type="text" class="form-control" value="<?php echo $lineResult["lot_number"];?>">
																</td>
																
																
																<td class="tab-md-85">
																	<input type="text" class="form-control" value="<?php echo $lineResult["serial_number"];?>">
																</td>

																<td class="tab-md-200">
																	<textarea class="form-control" rows="1" name="reason[]"><?php echo $lineResult["reason"];?></textarea>
																</td>
															</tr>
															<?php
														}
													}
												?>
											</tbody>
										</table>
									</div>
									
									<div class="row mt-2 mb-2">
										<div class="col-md-12">
											<div class="line-items-error"></div>
										</div>
									</div>

									<?php 
										if($type != "view")
										{
											?>
											<div class="add-btns">
												<div class="row">
													<div class="col-md-6">
														<a href="javascript:void(0);" onclick="addLine('add_line_item');" id="addLineItem" class="btn btn-primary btn-sm">Add</a>

													</div>
													<div class="col-md-6 text-right">
														<a href="javascript:void(0);" onclick="addLine('add_line_item');" id="addLineItem" class="btn btn-primary btn-sm">Add</a>

													</div>
												</div>
											</div>
											<?php
										}
									?>
								</section>

								
								<!-- Line level end here -->
							</fieldset>
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
								<a href="<?php echo base_url(); ?>stock_adjustment/stockAdjustment" class="btn btn-default btn-sm">Close</a>
							</div>

							<span class="note_content" style="display:none;color:#959191;">
								Note : To increase qty value in +ve, to decrease qty value in -ve
							</span>
						</div>
					</form>

					<script>

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
						
					}
					
					function saveBtn(val)
					{
						var adj_date = $("#adj_date").val();

						if (!adj_date) {
														
							if (!adj_date) {
								$(".adj_date").addClass('errorClass');
							} else {
								$(".adj_date").removeClass('errorClass')
							}
							return false; 
						}
						
						if ($("#line_items tbody tr").length === 0) {
							
							Swal.fire({
								icon: 'error',
								title: 'Oops...',
								text: 'Please add at least one line level record.'
							});
							return false;
						}
						
					
						var itemIdEntered = false;
						$("input[name='item_id[]']").each(function() {
							
							if ($(this).val() === "" || $("input[name='quantity[]']").val()==='') {
								itemIdEntered = true;
								return false; 
							}
						});
						
						if (itemIdEntered) {
							Swal.fire({
								icon: 'error',
								title: 'Oops...',
								text: 'Please fill all required fields.'
							});
							return false;
						}
						return true;
												

					}

					function addLine(val)
					{
						var adj_date = $("#adj_date").val();

						if (adj_date) 
						{
							$(".adj_date").removeClass('errorClass');

							addSOLines();
						} 
						else 
						{
							$(".adj_date").addClass('errorClass');
						}
					}
					
						
					var counter = 1;

					var this_new_counter = 0;

					function getItemList(item_id, item_name, item_description,uom_id,transaction_id) 
					{
								
						$('.item_clear_new').show();
						var itemExists = false; 

						$('input[name="item_id[]"]').each(function() 
						{
							if ($(this).val() == item_id) {
								itemExists = true; 
								return false; 
							}
						});

						if (itemExists) 
						{
							
							Swal.fire(
							{
								icon: 'error',
								text: 'Item already added',
							}).then((result) => {
								if (result.isConfirmed) {
									$('#item_id'+this_new_counter).val('');
									$('#item_name'+this_new_counter).val('');
									$(".item_clear_new"+this_new_counter).hide();
									$('#ItemList'+this_new_counter).fadeOut();
								}
							});
							return false; 
						}



						if (item_id == 0) 
						{
							
							$('#item_id' + counter).val('0');
						} 
						else 
						{
							
							$('#item_id' + this_new_counter).val(item_id);
							$('#item_name' + this_new_counter).val(item_name);
							$('#ItemList'+ this_new_counter).fadeOut();
							
							
							$.ajax({
								type: "POST",
								url: "<?php echo base_url(); ?>stock_adjustment/ajaxUom",
								data: { uom_id},
								success: function (data) {
									
									var parts = data.split('@');
									var uom_id = parts[0];
									var uom_code = parts[1];
									$('#uom_id' + this_new_counter).val(uom_id);
									$('#uom_code' + this_new_counter).val(uom_code);
								}
							});

							$.ajax({
								type: "POST",
								url: "<?php echo base_url(); ?>stock_adjustment/ajaxTransQty",
								data:{item_id},
								success: function (data) {
									
									var parts = data.split('@');
									var transaction_id = parts[0];
									var transaction_qty = parts[1];
									if(transaction_qty==='')
									{
										$('#current_iaq' + this_new_counter).val('0');
									}
									else
									{
										$('#current_iaq' + this_new_counter).val(transaction_qty);
									}
									
								}
							});

							$.ajax({
								type: "POST",
								url: "<?php echo base_url(); ?>stock_adjustment/ajaxOrganization",
								success: function (data) {
									$("#organization_id" + this_new_counter).html(data);
								}
								
							});
						}
							
					}


					$(document).on('click', 'ul.list-unstyled-item_id li', function () {
						var value = $(this).text();
						if (value === "Sorry! Item Not Found.") {
							$('#ItemList'+this_new_counter).fadeOut();
						} else {
							$('#ItemList'+this_new_counter).fadeOut();
						}
					});

							

					function clearItemSearchKeyword(counter) {
						$("#item_clear_new" + counter).hide();
						$("#item_id" + counter).val("");
						$("#item_name" + counter).val("");
					}

					
					function addSOLines()
					{
						var flag = 0;
						var organization_id = null;
						$('.line-items-error').text('');							
						$(".note_content").show();

						$("table.line_items").find('input[name^="item_id[]"]').each(function () 
						{
							var row = $(this).closest("tr");
							var item_id = +row.find('input[name^="item_id[]"]').val();
							
							if(item_id == 0)
							{
								flag = 1;
							}
						});

						if(flag == 0)
						{
							var select_lot_numbers = "";
							select_lot_numbers += '<select class="form-control searchDropdown" onchange="selectLotDetails(this.value,'+counter+');" style="width:110px;" name="lot_number_id[]" id="lot_number_id'+counter+'">';
							select_lot_numbers += '<option value="">- Select -</option>';
							select_lot_numbers += '</select>';

							var newRow = $("<tr class='remove_tr tabRow"+counter+"'>");
							var cols = "";
							cols += "<td class='tab-md-30 text-center'>"+"<a class='deleteRow' id='deleteRow"+counter+"'><i class='fa fa-times-circle-o' style='color:#fb1b1b61;font-size:16px;'></i></a>" +
										"<input type='hidden' name='counter[]' id='counter"+counter+"' value='" + counter + "'>"+"</td>";
							
							cols += "<td class='tab-md-85'>" 
									+"<input type='number' class='form-control mobile_vali' required name='line_num[]' id='line_num"+ counter +"' value='"+counter
									+"'>"
								+"</td>";

							cols += "<td class='tab-md-200'><div class='input-wrapper'>" +
								"<input type='text' name='item_name[]' autocomplete='off' id='item_name"+counter+"'  placeholder='Item Name' class='form-control item_name"+counter+"'>" +
								"<input type='hidden' name='item_id[]' autocomplete='off' id='item_id"+counter+"' class='form-control item_id"+counter+"'>" +
								"<div id='ItemList"+counter+"'></div>" +
								"<span class='item_clear_new' id='item_clear_new"+counter+"' title='Clear' onclick='clearItemSearchKeyword("+counter+");' >" +
								"<i class='fa fa-times' aria-hidden='true'></i>" +
								"</span>" +
								"</div></td>";

							cols += "<td class='tab-md-85'>" 
									+"<input type='hidden' class='form-control' name='uom_id[]' id='uom_id"+ counter +"' value=''>"
									+"<input type='text' class='form-control' readonly name='uom_code[]' id='uom_code"+ counter +"' value=''>"
								+"</td>";
							
							cols += "<td class='tab-md-85'>" 
									+"<input type='text' class='form-control' readonly name='current_iaq[]' id='current_iaq"+ counter +"' value=''>"
								+"</td>";

							cols += "<td class='tab-md-85'>" 
									+"<input type='text' class='form-control' name='quantity[]' id='quantity"+ counter +"' value=''>"
								+"</td>";
							

							cols += "<td class='tab-md-100'>" 
									+"<select class='form-control searchDropdown'name='organization_id[]' id='organization_id"+ counter +"' ><option> - Select - </option></select>"
								+"</td>";

							cols += "<td class='tab-md-100'>" 
									+"<select class='form-control searchDropdown'name='sub_inventory_id[]' id='sub_inventory_id"+ counter +"' ><option> - Select - </option></select>"
								+"</td>";
							
							cols += "<td class='tab-md-100'>" 
									+"<select class='form-control searchDropdown' name='locator_id[]' id='locator_id"+ counter +"'><option> - Select -</option></select>"
								+"</td>";
							
								cols += "<td class='tab-md-100'>" 
									+"<input type='text' class='form-control' name='lot_no[]' id='lot_no"+ counter +"' value=''>"
								+"</td>";

							
							cols += "<td class='tab-md-85'>" 
								+"<input type='number' class='form-control' name='serial_no[]' id='serial_no"+ counter +"' value=''>"
							+"</td>";

							cols += "<td class='tab-md-200'>" 
								+"<textarea class='form-control' rows='1' name='reason[]' id='reason"+ counter +"'></textarea>"
							+"</td>";
							cols += "</tr>";
							
							newRow.html(cols);
							$("table.line_items").prepend(newRow);

							$(document).ready(function()
							{ 
								$(".searchDropdown").select2();
							});

							var new_counter = $('#counter'+counter).val();

						// 	$(document).ready(function() {
							
						// 	$('#quantity'+ counter ).on('input', function() {
						// 		var quantityValue = $(this).val();
						// 		var currentIaqField = $('#current_iaq'+ new_counter);

						// 		if (parseFloat(quantityValue) < 0) {
									
						// 			currentIaqField.prop('readonly', true);
								
						// 			currentIaqField.val(quantityValue);

						// 			if(quantityValue)
									
						// 			swal({
						// 				title: "Warning!",
						// 				text: "Negative quantity entered.",
						// 				icon: "warning",
						// 				button: "OK",
						// 			});
						// 		} 
						// 		else {
									
						// 			currentIaqField.prop('readonly', false);
						// 			// Clear the current_iaq value
						// 			currentIaqField.val('');
						// 		}
						// 	});
						// });


						$('#deleteRow'+counter).click(function() 
						{
							$('#deleteRow'+new_counter).closest('tr').remove();
						});
							
						$('#item_name' + counter).keyup(function () 
						{
							var query=$(this).val();
						
							$.ajax({
								type: "POST",
								url: "<?php echo base_url(); ?>stock_adjustment/ajaxItemList",
								data: { query:query,counter:new_counter}, 
								success: function (data) {
									
									$('#ItemList'+new_counter).fadeIn();
									$('#ItemList'+new_counter).html(data);

									this_new_counter =new_counter;
								}
							});
						})

						$('#organization_id'+ counter).change(function () { 
							var query=$(this).val()
							$.ajax({
								type: "POST",
								url: "<?php echo base_url(); ?>stock_adjustment/ajaxselectSubInventory",
								data: {query:query, counter:new_counter}, 
								success: function (data) {
									
									$("#sub_inventory_id" + new_counter).html(data);
									
								}
							});
						});
						$('#sub_inventory_id'+ counter).change(function () { 
							var query=$(this).val()
							
							$.ajax({
								type: "POST",
								url: "<?php echo base_url(); ?>stock_adjustment/ajaxSubInventoryLocators",
								data: {query:query, counter:new_counter}, 
								success: function (data) {
									
									$("#locator_id" + new_counter).html(data);
									
								}
							});
						});

						counter++;
					}
					else 
					{
						Swal.fire(
						{
							icon: 'error',
							text: 'Please fill the previous line level',
						})
					}
				}
				</script>
					<?php
				}
				else
				{ 
					?>
					<!-- Buttons start here -->
					<div class="row">
						<div class="col-md-6"><h3><b><?php echo $page_title;?></b></h3></div>
						<div class="col-md-6 float-right text-right">
							<?php
								if($physical_stock_adjustment['create_edit_only'] == 1 || $this->user_id == 1)
								{
									?>
									<a href="<?php echo base_url(); ?>stock_adjustment/stockAdjustment/add" class="btn btn-info btn-sm">
										Create Physical Stock Adjustment
									</a>
									<?php 
								} 
							?>
						</div>
					</div>
					<!-- Buttons end here -->
					
					<!-- Filters start here -->
					<form action="" class="form-validate-jquery" method="get">
						<div class="row mt-3">
							<div class="col-md-4">
								<div class="row">
									<label class="col-form-label col-md-4 text-right">Adjustment No</label>
									<div class="form-group col-md-7">
									
									<div class="input-wrapper">
											<input type="text" name="adj_number" autocomplete="off" id="adj_number" value="<?php echo isset($_GET['adj_number']) ? $_GET['adj_number'] : NULL; ?>" placeholder="Payment Number" class="form-control">
											<input type="hidden" name="adj_number_id" autocomplete="off" id="adj_number_id" value="<?php echo isset($_GET['adj_number_id']) ? $_GET['adj_number_id'] : NULL; ?>" >
											<div id="AdjustNumberList"></div><!-- Clear icon start -->
											<?php 
												if(isset($_GET["adj_number_id"]) && !empty($_GET["adj_number_id"]))
												{
													$styleDisplay = "display:block";
												}
												else{
													$styleDisplay = "display:none";
												}
												?>
											<span class="adj_number_clear_icon" title="Clear" onclick="clearAdjustmentNoSearchKeyword();" style="<?php echo $styleDisplay;?>">
												<i class="fa fa-times" aria-hidden="true"></i>
											</span>

											<script>
												$(document).ready(function()
												{  
													$('#adj_number').keyup(function()
													{  
														var query = $(this).val();  

														if(query != '')  
														{  
															$.ajax({  
																url:"<?php echo base_url();?>stock_adjustment/ajaxAdjustmentNumberList",  
																method:"POST",  
																data:{query:query},  
																success:function(data)  
																{  
																	$('#AdjustNumberList').fadeIn();  
																	$('#AdjustNumberList').html(data);  
																}  
															});  
														}  
													});

													$(document).on('click', 'ul.list-unstyled-adj_number_id li', function()
													{  
														var value = $(this).text();
														
														if(value === "Sorry! Adjustment Number Not Found.")
														{
															$('#AdjustNumberList').fadeOut();
														}
														else
														{
															$('#AdjustNumberList').fadeOut();  
														}
													});
												});

												function getAdjustNumberList(adj_number_id,adj_number)
												{
													$('.adj_number_clear_icon').show();
													if(adj_number_id == 0)	
													{
														$('#adj_number_id').val('');
													}
													else
													{
														
														$('#adj_number_id').val(adj_number_id);
														$('#adj_number').val(adj_number);
													}
												}

												function clearAdjustmentNoSearchKeyword()
												{
													$(".adj_number_clear_icon").hide();
													$("#adj_number_id").val("");
													$("#adj_number").val("");
												}
											</script>

										</div>
									</div>
								</div>
							</div>
						
							<div class="col-md-3">
								<div class="row">
									<label class="col-form-label col-md-4 text-right">From Date</label>
									<div class="form-group col-md-8">
										<input type="text" name="from_date" id="from_date" class="form-control" readonly value="<?php echo !empty($_GET['from_date']) ? $_GET['from_date'] : ""; ?>" placeholder="From Date">
									</div>
								</div>
							</div>

							<div class="col-md-3">
								<div class="row">
									<label class="col-form-label col-md-4 text-right">To Date</label>
									<div class="form-group col-md-8">
										<input type="text" name="to_date" id="to_date" class="form-control" readonly value="<?php echo !empty($_GET['to_date']) ? $_GET['to_date'] :""; ?>" placeholder="To Date">
									</div>
								</div>
							</div>

							<div class="col-md-2">
								<button type="submit" class="btn btn-info">Search <i class="fa fa-search"></i></button>
								<a href="<?php echo base_url(); ?>stock_adjustment/stockAdjustment" title="Clear" class="btn btn-default">Clear</a>
							</div>
						</div>
					</form>
					<!-- Filters end here -->
					
					<?php 
						if(isset($_GET) &&  !empty($_GET))
						{
							?>
							<!-- Page Item Show start -->
							<div class="row mt-3">
								<?php
									if( isset($resultData) && count($resultData) > 0 )
									{
										?>
											<div class="col-md-6">
												<a href="<?php echo base_url().$this->redirectURL;?>&export=export" class="btn btn-primary btn-sm">Download Excel</a>
											</div>

											<div class="col-md-6 float-right text-right">
												<?php 
													$redirect_url = substr($_SERVER['REQUEST_URI'],'1');
												?>
												<input type="hidden" id="redirect_url" value="<?php echo $redirect_url; ?>"/>
																		
												<div class="filter_page">
													<label>
														<span style="color:blue;">Currency : <?php echo CURRENCY_CODE;?></span>
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
										<?php
									}
								?>
							</div>
						    <!-- Page Item Show start -->

							<!-- Table start here -->
							<div class="new-scroller">
								<table id="myTable" class="table table-bordered table-hover">
									<thead>
										<tr>
											<th class="text-center">View</th>
											<th class="text-left">Organization</th>
											<th class="text-left">Adjustment Number</th>
											<th class="text-left">Adjustment Date</th>
											<th class="text-left">Remarks</th>
										</tr>
									</thead>
									<tbody>
										<?php 	
											$i=0;
											$firstItem = $first_item;
											$totalValue = 0;
											foreach($resultData as $row)
											{
												?>
												<tr>
													<td class='text-center'>
														<?php
															if($physical_stock_adjustment['read_only'] == 1 || $this->user_id == 1)
															{
																?>
																<a href="<?php echo base_url();?>stock_adjustment/stockAdjustment/view/<?php echo $row['header_id']; ?>"><i class='fa fa-eye'></i></a>
																<?php 
															} 
														?>
													</td>
													<td><?php echo $row['organization_name'];?></td>
													<td><?php echo $row['adj_number'];?></td>
													<td><?php echo date(DATE_FORMAT,strtotime($row['adj_date']));?></td>
													<td><?php echo $row['remarks'];?></td>								
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

							<!-- Pagination start here -->
							<?php 
								if (count($resultData) > 0) 
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











