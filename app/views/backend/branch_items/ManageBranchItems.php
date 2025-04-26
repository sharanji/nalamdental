
<style>
	.switch {
		position: relative;
		display: inline-block;
		width: 79px;
		height: 25px;
	}
	.switch input {display:none;}

	.slider {
		position: absolute;
		cursor: pointer;
		top: 0;
		left: 0;
		right: 0;
		bottom: 0;
		background-color: #f51658;
		-webkit-transition: .4s;
		transition: .4s;
		border-radius: 34px;
	}

	.slider:before {
		position: absolute;
		content: "";
		height: 15px;
		width: 15px;
		left: 5px;
		bottom: 5px;
		background-color: white;
		-webkit-transition: .4s;
		transition: .4s;
		border-radius: 50%;
	}

	input:checked + .slider {
		background-color: #2ab934;
	}

	input:focus + .slider {
		box-shadow: 0 0 1px #2196F3;
	}

	input:checked + .slider:before {
		-webkit-transform: translateX(26px);
		-ms-transform: translateX(26px);
		transform: translateX(55px);
	}

	/*------ ADDED CSS ---------*/
	.slider:after
	{
		content:'OFF';
		color: white;
		display: block;
		position: absolute;
		transform: translate(-50%,-50%);
		top: 50%;
		left: 50%;
		font-size: 8px;
		font-family: Verdana, sans-serif;
	}

	input:checked + .slider:after
	{  
		content:'ON';
	}
</style>

<?php
	$assignBranchItemsMenu = accessMenu(assign_branch_items);
?>	


<div class="content"><!-- Content start-->
	<div class="card"><!-- Card start-->
		<div class="card-body">
			<?php
				if(isset($type) && $type == "add" || $type == "edit" || $type == "view")
				{
					if($type == "view")
					{
						$this->fieldDisabled = $fieldSetDisabled = "disabled";
						#$dropdownDisabled = "style='pointer-events: none;'";
						$this->searchDropdown = $searchDropdown = "";

						$this->fieldDisabled = $fieldDisabled = "disabled";
						$this->fieldReadonly = $fieldReadonly = "readonly";
						$styleSearchBranch = "";
						$title = "Assigned Branch Items";
						
					}
					else
					{
						if($type == "add")
						{
							$this->fieldDisabled = $fieldDisabled = "";
							$this->fieldReadonly = $fieldReadonly = "";
							$this->searchDropdown = $searchDropdown = "searchDropdown";
							$styleSearchBranch = "";
						} 
						else if($type == "edit")
						{
							$this->fieldDisabled = $fieldDisabled = "";
							$this->fieldReadonly = $fieldReadonly = "";
							$this->searchDropdown = $searchDropdown = "";
							$styleSearchBranch = "pointer-events: none;background: #f5f3f3;";
							
						}
						$fieldSetDisabled = "";
						$title = "Assign Branch Items";
					}
					
					?>
					
					<h3><b><?php echo isset($title) ? $title : ""; ?></b></h3>
					
					<form action="" --class="form-validate-jquery" enctype="multipart/form-data" method="post">
						
						<div class="row">
							<div class="col-md-6">
								<div class="row">
								<label class="col-form-label col-md-2">Branch <span class="text-danger">*</span></label>
									<div class="form-group col-md-6">
									    <?php $getbranch = $this->db->query("select branch_id,branch_name,branch_code from branch where active_flag='Y' order by branch_name asc")->result_array(); ?>
										<select name="branch_id" id="branch_id" <?php echo $this->fieldDisabled;?> style="<?php echo $styleSearchBranch;?>" onchange="assignBranchItems(this.value);" required class="form-control <?php echo $searchDropdown;?>">
											<option value="">- Select -</option>
											<?php 
												foreach($getbranch as $row)
												{
													$selected="";
													if(isset($id) && ($id == $row['branch_id']) )
													{
														$selected="selected='selected'";
													}
													?>
													<option value="<?php echo $row['branch_id']; ?>" <?php echo $selected;?>><?php echo ucfirst($row['branch_name']); ?></option>
													<?php 
												} 
											?>
										</select>
									</div>
								</div>
							</div>
							
							
						</div>

						<script>
							function assignBranchItems(val)
							{
								$(".tbl_rows").remove();

								if(val)
								{
									selectItems(val);
									$(".items").show();
									$(".ingredients").show();
								}
								else
								{
									$(".items").hide();
									$(".ingredients").hide();
								}	
							}

							function selectItems(val)
							{
								if(val !='')
								{
									$.ajax({
										type: "POST",
										url:"<?php echo base_url().'branch_items/ajaxSelectItems';?>",
										data: { id: val }
									}).done(function( msg ) 
									{   
										$("#product").html(msg);
									});
								}
								else 
								{ 
									alert("No Items!");
								}
							}
						</script>


						<?php
							if($type == "edit" || $type == "view")
							{
								$itemShow = "display:block;";
								$requiredIems = "";
								$requiredStar = "";
								$requiredStar = '';
							}
							else
							{
								$itemShow = "display:none;";
								$requiredIems = "required";
								$requiredStar = '<span class="text-danger">*</span>';
							}
						?>
						
						<?php
							if($type == "add" || $type == "edit" || $type == "view")
							{
								?>
								
								<div class="row">
									<div class="col-md-6">
										<div class="row">
											<?php 
												if($type == "add" || $type == "edit")
												{
													?>
													<label class="col-form-label col-md-2">Items <?php echo $requiredStar ;?></label>
													<?php 
												}
											?>
										   <div class=""></div>
											<div class="form-group col-md-8 items" style="<?php echo $itemShow;?>">
												<?php if($type == "add" || $type == "edit"){ ?>
												
												<?php #$getProduct = $this->db->query("select product_id, product_name, product_code	from products where product_status=1 order by product_name asc")->result_array(); ?>
												<select id="product" name="product" style="width:290px;" <?php echo $requiredIems;?> class="form-control searchDropdown">
													<option value="">- Select Item -</option>
													<!-- <option value="0">All Items</option> -->
													<?php 
														if($type == "edit")
														{
															$getItems =  $this->db->query("select item_id,item_name,item_description 
															from inv_sys_items
															where 1=1
															and active_flag = 'Y'
															and item_type_id = '30'
															order by item_description asc")->result_array();
															foreach($getItems as $product)
															{
																?>
																<option value="<?php echo $product['item_id']; ?>"><?php echo ucfirst($product['item_name']); ?> - <?php echo $product['item_description']; ?></option>
																<?php 
															}
														}
													?>
												</select>
												<?php } ?>
									        </div>
										</div>
									</div>
									<div class="col-md-2 text-right"></div>
										
								    </div>
							
								<div class="row">
								    <!-- <div class="col-md-1"></div> -->
								    <?php 
										if($type == "view" || $type == "edit")
										{
											?>
												<div class="col-md-3">
													<input id="myInput" onkeyup="myFunction()" type="search" class="form-control" placeholder="Search Items">
												</div>
											<?php 
										}
									?>
								    <div class="col-md-9 text-right" style="<?php echo $itemShow;?>">
										<?php 
											if($type == "view" || $type == "edit")
											{
												?>
														Currency : <?php echo CURRENCY_SYMBOL?>
												<?php 
											}
										?>

										<a href="<?php echo base_url(); ?>branch_items/ManageBranchItems" class="btn btn-default">Close</a>
												
										<?php 
											if($type == "add" || $type == "edit")
											{
												?>
												<button type="submit" class="btn btn-info">Save</button>
												<?php 
											}
										?>
									</div>
								</div>
								<?php 
							} 
						?>

						<!-- Table start here-->
						<div class="row ingredients" style="<?php echo $itemShow;?>">
								
							<div class="col-md-12">
								<div style="overflow-y: auto;">

									<div id="err_product" style="color:red;margin: 0px 0px 8px 0px;"></div>
									
									<table class="table items table-striped-- table-bordered table-condensed table-hover product_table" name="product_data" id="product_data">
										<thead>
											<tr>
												<th colspan="10">Branch Items</th>
											</tr>
											<tr>
												<?php 
													if($type == "add" || $type == "edit")
													{
														?><th> </th>
														<?php 
													} 
												?>
												<th>Item Name</th>
												<th>Item Description</th>
												<th class="tab-md-120">Take Away /  Order Price</th>
												<th class="text-center">Dine In Price</th>
												<th class="text-center">Available Qty</th>
												<th class="text-center">Max Order Qty</th>

												<th class="text-center">Break Fast</th>
												<th class="text-center">Lunch</th>
												<th class="text-center">Dinner</th>
												<th class="text-center">Best Selling</th>

												<!-- <th class="text-center">From Time (AM)</th>
												<th class="text-center">To Time (AM)</th>
												<th class="text-center">From Time (PM)</th>
												<th class="text-center">To Time (PM)</th> -->
												<th class="text-center">Available Items</th> 													
											</tr>
										</thead>
										<tbody id="product_table_body">
											<?php 
												if($type == "edit" || $type == "view")
												{
													if($type == "view")
													{
														$disabledchk='disabled';
													}else{
														$disabledchk='';
													}

													if(count($assignedItems) > 0)
													{
														$i=1;
														$counter=1;
														foreach($assignedItems as $items)
														{
															?>
															<tr>
																<?php 
																	if($type == "add" || $type == "edit")
																	{
																		?>
																		<td class='text-center tab-md-30'>
																			<input type='hidden' name='id' name='id' value="<?php echo $i; ?>">
																			<input type='hidden' name='counter[]' id='counter' value="<?php echo $counter; ?>">
																			<input type='hidden' name='item_id[]' id='item_id<?php echo $counter; ?>' value="<?php echo $items["item_id"]; ?>">
																			<input type='hidden' name='assignment_id[]' id='assignment_id<?php echo $counter; ?>' value="<?php echo $items["assignment_id"];?>">
																		</td>
																		<?php 
																	} 
																?>
																
																<td class='tab-md-150'><?php echo $items["item_name"]; ?></td>
																<td class='tab-md-150'><?php echo $items["item_description"]; ?></td>

																<td class='text-center tab-md-150'>
																	<input type='text' name='item_price[]' <?php echo $fieldReadonly; ?> class='form-control' id='item_price<?php echo $counter;?>' value='<?php echo number_format($items["item_price"],DECIMAL_VALUE,'.','');?>'>
																</td>
																<td class='text-center tab-md-100'>
																	<input type='text' name='dine_in_price[]' <?php echo $fieldReadonly; ?> class='form-control' id='dine_in_price<?php echo $counter;?>' value='<?php echo number_format($items["dine_in_price"],DECIMAL_VALUE,'.','');?>'>
																</td>
																
																<td class='text-center tab-md-85'>
																	<input type='text' name='available_quantity[]' <?php echo $fieldReadonly; ?> class='form-control' id='available_quantity' value='<?php echo $items["available_quantity"]; ?>'>
																</td>
																
																<td class='text-center tab-md-100'>
																	<input type='text' name='minimum_order_quantity[]' <?php echo $fieldReadonly; ?> class='form-control' id='minimum_order_quantity' value='<?php echo $items["minimum_order_quantity"]; ?>'>
																</td>

																<td class='text-center tab-md-80'>
																	<?php 
																		if($items["breakfast_flag"] == "Y")
																		{
																			$breakfast_flagChecked = 'checked="checked"';
																		}
																		else if($items["breakfast_flag"] == "N")
																		{
																			$breakfast_flagChecked = '';
																		}	
																	?>
																	<input type="checkbox" name="breakfast_flag[]" class="breakfast_flag" <?php echo $disabledchk;?> id="<?php echo $items["assignment_id"];?>" <?php echo $breakfast_flagChecked;?> value="Y">
																</td>

																<td class='text-center tab-md-80'>
																	<?php 
																		if($items["lunch_flag"] == "Y")
																		{
																			$lunch_flagChecked = 'checked="checked"';
																		}
																		else if($items["lunch_flag"] == "N")
																		{
																			$lunch_flagChecked = '';
																		}
																	?>
																	<input type="checkbox" name="lunch_flag[]" class="lunch_flag" <?php echo $disabledchk;?> id="<?php echo $items["assignment_id"];?>" <?php echo $lunch_flagChecked;?> value="Y">
																</td>

																<td class='text-center tab-md-80'>
																	<?php 
																		if($items["dinner_flag"] == "Y")
																		{
																			$dinner_flagChecked = 'checked="checked"';
																		}
																		else if($items["dinner_flag"] == "N")
																		{
																			$dinner_flagChecked = '';
																		}
																	?>
																	<input type="checkbox" name="dinner_flag[]" class="dinner_flag" <?php echo $disabledchk;?> id="<?php echo $items["assignment_id"];?>" <?php echo $dinner_flagChecked;?> value="Y">
																</td>

																<td class='text-center tab-md-80'>
																	<?php 
																		if($items["best_selling"] == "Y")
																		{
																			$best_sellingChecked = 'checked="checked"';
																		}
																		else if($items["best_selling"] == "N")
																		{
																			$best_sellingChecked = '';
																		}
																		else
																		{
																			$best_sellingChecked = '';
																		}
																	?>
																	<input type="checkbox" name="best_selling[]" class="best_selling" <?php echo $disabledchk;?> id="<?php echo $items["assignment_id"];?>" <?php echo $best_sellingChecked;?> value="Y">
																</td>
																
																<?php /*
																<td class='text-center tab-medium-width'>
																	<input type='time' name='from_time_am[]' <?php echo $fieldReadonly; ?> class='form-control' id='from_time_am<?php echo $counter;?>' value='<?php echo $items["from_time_am"]; ?>'>
																</td>

																<td class='text-center tab-medium-width'>
																	<input type='time' name='to_time_am[]' <?php echo $fieldReadonly; ?> class='form-control' id='to_time_am<?php echo $counter;?>' value='<?php echo $items["to_time_am"]; ?>'>
																</td>

																<td class='text-center tab-medium-width'>
																	<input type='time' name='from_time_pm[]' <?php echo $fieldReadonly; ?> class='form-control' id='from_time_pm<?php echo $counter;?>' value='<?php echo $items["from_time_pm"]; ?>'>
																</td>

																<td class='text-center tab-medium-width'>
																	<input type='time' name='to_time_pm[]' <?php echo $fieldReadonly; ?> class='form-control' id='to_time_pm<?php echo $counter;?>' value='<?php echo $items["to_time_pm"]; ?>'>
																</td> */ ?>
																
																<td class='text-center tab-md-100'>
																	<?php 
																		if($items["active_flag"] == 'Y')
																		{
																			?>
																			<label class="switch">
																				<input class="item_status" name="item_status[]" type="checkbox" checked id="<?php echo $items["assignment_id"];?>">
																				<div class="slider round"></div>
																			</label>
																			<?php 
																		} 
																		else
																		{ 
																			?>
																			<label class="switch">
																				<input class="item_status" name="item_status[]" type="checkbox" id="<?php echo $items["assignment_id"];?>">
																				<div class="slider round"></div>
																			</label>
																			<?php 
																		} 
																	?>
																	<input type='hidden' name='line_status[]' class='form-control' id='line_status<?php echo $counter;?>' value='<?php echo $items["active_flag"]; ?>'>
																</td>
															</tr>
															<?php
															$i++;
															$counter++;
														}
													}
												}
											?>
										</tbody>
									</table>
								</div>
								<input type="hidden" name="table_data" id="table_data">
							</div>
						</div>
						<!-- Table start here-->
						
						<div class="d-flexad text-right mt-4">
							<?php 
								if($this->user_id==1)
								{
									?>
									<a href="<?php echo base_url(); ?>branch_items/ManageBranchItems" class="btn btn-default">Close</a>
									<?php
								}
								else
								{
									?>
									<?php
								}
							?>
							<?php
								if($type == "add" || $type == "edit")
								{
									?>
									<button type="submit" class="btn btn-info">Save</button>
									<?php 
								}
							?>
						</div>
					</form>
					
					<?php 
						if($type == "edit" || $type == "view")
						{
							?>
							<script>
								/* $('input[type="checkbox"]').on('click',function () 
								{
									var id = $(this).attr("id");
									
									if($(this).is(':checked',true))
									{
										$.ajax({
											type: "get",
											url:"<?php echo base_url().'branch_items/ajaxAvailableBranchItems/status/';?>"+id+"/"+1,
											data: { }
										}).done(function( msg ) 
										{   
											toastr.success(msg);
										});
									}
									else 
									{
										$.ajax({
											type: "get",
											url:"<?php echo base_url().'branch_items/ajaxAvailableBranchItems/status/';?>"+id+"/"+2,
											data: { }
										}).done(function( msg ) 
										{   
											toastr.success(msg)
										});
									}
								}) */

								$(function() 
								{
									$('.breakfast_flag').on('change', function(e) 
									{
										var id = $(this).attr("id");
									
										if($(this).is(':checked',true))
										{
											$.ajax({
												type: "get",
												url:"<?php echo base_url().'branch_items/ajaxBreakfastFlag/status/';?>"+id+"/Y",
												data: { }
											}).done(function( msg ) 
											{   
												//toastr.success(msg);
											});
										}
										else 
										{
											$.ajax({
												type: "get",
												url:"<?php echo base_url().'branch_items/ajaxBreakfastFlag/status/';?>"+id+"/N",
												data: { }
											}).done(function( msg ) 
											{   
												//toastr.success(msg)
											});
										}
									});

									$('.lunch_flag').on('change', function(e) 
									{
										var id = $(this).attr("id");
									
										if($(this).is(':checked',true))
										{
											$.ajax({
												type: "get",
												url:"<?php echo base_url().'branch_items/ajaxLunchFlag/status/';?>"+id+"/Y",
												data: { }
											}).done(function( msg ) 
											{   
												//toastr.success(msg);
											});
										}
										else 
										{
											$.ajax({
												type: "get",
												url:"<?php echo base_url().'branch_items/ajaxLunchFlag/status/';?>"+id+"/N",
												data: { }
											}).done(function( msg ) 
											{   
												//toastr.success(msg)
											});
										}
									});

									$('.dinner_flag').on('change', function(e) 
									{
										var id = $(this).attr("id");
									
										if($(this).is(':checked',true))
										{
											$.ajax({
												type: "get",
												url:"<?php echo base_url().'branch_items/ajaxDinnerFlag/status/';?>"+id+"/Y",
												data: { }
											}).done(function( msg ) 
											{   
												//toastr.success(msg);
											});
										}
										else 
										{
											$.ajax({
												type: "get",
												url:"<?php echo base_url().'branch_items/ajaxDinnerFlag/status/';?>"+id+"/N",
												data: { }
											}).done(function( msg ) 
											{   
												//toastr.success(msg)
											});
										}
									});

									$('.best_selling').on('change', function(e) 
									{
										var id = $(this).attr("id");
									
										if($(this).is(':checked',true))
										{
											$.ajax({
												type: "get",
												url:"<?php echo base_url().'branch_items/ajaxBestSelling/status/';?>"+id+"/Y",
												data: { }
											}).done(function( msg ) 
											{   
												//toastr.success(msg);
											});
										}
										else 
										{
											$.ajax({
												type: "get",
												url:"<?php echo base_url().'branch_items/ajaxBestSelling/status/';?>"+id+"/N",
												data: { }
											}).done(function( msg ) 
											{   
												//toastr.success(msg)
											});
										}
									});

									$('.item_status').on('change', function(e) 
									{
										var id = $(this).attr("id");
									
										if($(this).is(':checked',true))
										{
											$.ajax({
												type: "get",
												url:"<?php echo base_url().'branch_items/ajaxAvailableBranchItems/status/';?>"+id+"/"+1,
												data: { }
											}).done(function( msg ) 
											{   
												toastr.success(msg);
											});
										}
										else 
										{
											$.ajax({
												type: "get",
												url:"<?php echo base_url().'branch_items/ajaxAvailableBranchItems/status/';?>"+id+"/"+2,
												data: { }
											}).done(function( msg ) 
											{   
												toastr.success(msg)
											});
										}
									});
								});
							</script>
							<?php 
						} 
					?>

					<script> 
						$(document).ready(function()
						{
							var type = "<?php echo $type;?>";
							
							if(type == "add")
							{
								var i = 1;
								var counter = 1;
							}
							else if(type == "edit")
							{
								var i = <?php echo isset($assignedItems) ? count($assignedItems) + 1 : 1;?>;
								var counter =  <?php echo isset($assignedItems) ? count($assignedItems) + 1 : 1;?>;
							}

							var flag = 0;
							
							$('#product').change(function()
							{
								var id = $(this).val();
								var branch_id = $("#branch_id").val();
								var dssid = $(this).val();
								
								$('#err_product').text('');
								
								var flag = 0;
								if(id != "")
								{
									$.ajax({
										url: "<?php echo base_url('branch_items/getItems') ?>/"+id,
										type: "GET",
										data:{
											'<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
										},
										datatype: "JSON",
										success: function(d)
										{
											data = JSON.parse(d);
											var countKey = Object.keys(data['items']).length;

											if(countKey > 0)
											{
												$.each(data['items'], function(i, item) 
												{
													$("table.product_table").find('input[name^="item_id"]').each(function () 
													{
														if(item.item_id  == +$(this).val())
														{
															flag = 1;
														}
													});
													
													if(flag == 0)
													{
														var item_id = item.item_id;
														var code = item.item_name;
														var name = item.item_description;
														
														if( item.item_cost != undefined)
														{
															var item_cost = item.item_cost;
														}
														else
														{
															var item_cost = '';
														}

														var available_quantity = 0;
														var minimum_order_quantity = 0;

														var from_time_am = "";
														var to_time_am = "";

														var from_time_pm = "";
														var to_time_pm = "";
														
														var select_item_status = '';
														select_item_status += "<label class='switch'>";
														select_item_status += "<input type='checkbox' checked name='item_status[]' id='item_status"+ counter +"' class='item_status'>";
														select_item_status += "<div class='slider round'></div></label>";

														var newRow = $("<tr class='dataRowVal"+id+"'> tbl_rows");
														var cols = "";
														cols += "<td class='text-center tab-md-30'><a class='deleteRow'> <i style='color:#ed2025;' class='fa fa-trash'></i> </a><input type='hidden' name='id' name='id' value="+i+"><input type='hidden' name='counter[]' value="+counter+"><input type='hidden' name='item_id[]' id='item_id"+counter+"' value="+item_id+"><input type='hidden' name='assignment_id[]' id='assignment_id"+counter+"' value='0'></td>";
														cols += "<td class='tab-md-100'>"+code+"</td>";
														cols += "<td class='tab-md-150'>"+name+"</td>";
														
														//Price
														cols += "<td class='tab-md-85'>" 
																+"<span id='price'>"
																	+"<input type='text' class='form-control' name='item_price[]' id='item_price"+ counter +"' value='"+item_cost
																+"'></span>"
																+"</td>";

														//Price
														cols += "<td class='tab-md-85'>" 
															+"<span id='dine_in_price'>"
																+"<input type='text' class='form-control' name='dine_in_price[]' id='dine_in_price"+ counter +"' value='"+item_cost
															+"'></span>"
															+"</td>";		

														//Avalilable Qty
														cols += "<td class='tab-md-85'>" 
																+"<input type='number' class='form-control' name='available_quantity[]' id='available_quantity"+ counter +"' value='"+available_quantity
																+"'>"
																+"</td>";

														//Minimum Order Qty
														cols += "<td class='tab-md-85'>" 
																	+"<input type='number' class='form-control' name='minimum_order_quantity[]' id='minimum_order_quantity"+ counter +"' value='"+minimum_order_quantity
																+"'>"
																+"</td>";

														
														//Break Fast
														cols += "<td class='tab-md-80 text-center'>" 
																+"<input type='checkbox' name='breakfast_flag["+counter+"]' id='breakfast_flag"+ counter +"' value='Y'>"
																+"</td>";
														
														//Lunch
														cols += "<td class='tab-md-80 text-center'>" 
																+"<input type='checkbox' name='lunch_flag["+counter+"]' id='lunch_flag"+ counter +"' value='Y'>"
																+"</td>";
														
														//Dinner
														cols += "<td class='tab-md-80 text-center'>" 
																+"<input type='checkbox' name='dinner_flag["+counter+"]' id='dinner_flag"+ counter +"' value='Y'>"
																+"</td>";


														//best_selling
														cols += "<td class='tab-md-80 text-center'>" 
																+"<input type='checkbox' name='best_selling["+counter+"]' id='best_selling"+ counter +"' value='Y'>"
																+"</td>";
														
														/* //From Time (AM)
														cols += "<td class='tab-medium-width'>" 
																+"<input type='time' class='form-control' name='from_time_am[]' id='from_time_pm"+ counter +"' value=''>"
															+"</td>";

														//To Time (AM)
														cols += "<td class='tab-medium-width'>" 
																+"<input type='time' class='form-control' name='to_time_am[]' id='to_time_pm"+ counter +"' value=''>"
															+"</td>";
														
														//From Time (PM)	
														cols += "<td class='tab-medium-width'>" 
																+"<input type='time' class='form-control' name='from_time_pm[]' id='price"+ counter +"' value=''>"
															+"</td>";

														//To Time (PM)
														cols += "<td class='tab-medium-width'>" 
																+"<input type='time' class='form-control' name='to_time_pm[]' id='price"+ counter +"' value=''>"
															+"</td>"; */

														cols += '<td class="text-center tab-md-85">'+select_item_status+'<input type="hidden" name="line_status[]" id="line_status'+counter+'" value="Y"></td>';
															
														cols += "</tr>";
														counter++;

														newRow.html(cols);
														$("table.product_table").append(newRow);
														i++;
													}
													else
													{
														$('#err_product').text('Item already assigned!').animate({opacity: '0.0'}, 2000).animate({opacity: '0.0'}, 1000).animate({opacity: '1.0'}, 2000);
													}
												});
											}
											else
											{
												$('#err_product').text('No Data Found!').animate({opacity: '0.0'}, 2000).animate({opacity: '0.0'}, 1000).animate({opacity: '1.0'}, 2000);
											}
										},
										error: function(xhr, status, error) {
											$('#err_product').text('Enter Item Code / Name').animate({opacity: '0.0'}, 2000).animate({opacity: '0.0'}, 1000).animate({opacity: '1.0'}, 2000);
										}
									});
								}
							});

							$("table.product_table").on("click", "a.deleteRow", function (event) 
							{
								deleteRow($(this).closest("tr"));
								$(this).closest("tr").remove();
							});

							function deleteRow(row)
							{
								var id = +row.find('input[name^="id"]').val();
								//var array_id = product_data[id].product_id;
								//product_data[id] = null;
								//var table_data = JSON.stringify(product_data);
								//$('#table_data').val(table_data);
							}

							$("table.product_table").on("input keyup change", 'input[name^="price"], input[name^="available_quantity"], input[name^="item_status"], input[name^="minimum_order_quantity"], input[name^="minimum_order_value"]', function (event) 
							{
								calculateRow($(this).closest("tr"));
							});
					
							function calculateRow(row) 
							{
								var key = +row.find('input[name^="id"]').val();
								var price = +row.find('input[name^="price"]').val();
								var counter = +row.find('input[name^="counter"]').val();

								if($("#item_status"+counter).is(':checked',true))
								{
									var item_status = 'Y';
								}else{
									var item_status = 'N';
								}
								$("#line_status"+counter).val(item_status);	
							}
						});
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
								if($assignBranchItemsMenu['create_edit_only'] == 1 || $this->user_id == 1)
								{
									?>
									<a href="<?php echo base_url(); ?>branch_items/ManageBranchItems/add" class="btn btn-info btn-sm">
										Assign Branch Items
									</a>
									<?php 
								} 
							?>
						</div>
					</div>

					<!-- filters-->
					<form action="" class="form-validate-jquery" method="get">
						<div class="row">
							<div class="col-md-4">
								<div class="row">
									<label class="col-form-label col-md-3">Branch <!-- <span class="text-danger">*</span> --></label>
									<div class="form-group col-md-8">
										<?php 
											$branchQry = "select branch_id,branch_code,branch_name from branch where active_flag='Y' order by branch.branch_name asc";

											$getBranch = $this->db->query($branchQry)->result_array();
										?>
										<select name="branch_id" id="branch_id" --required class="form-control searchDropdown">
											<option value="">- Select -</option>
											<?php 
												foreach($getBranch as $row)
												{
													$selected="";
													if(isset($_GET['branch_id']) && $_GET['branch_id'] == $row["branch_id"] )
													{
														$selected="selected='selected'";
													}
													?>
													<option value="<?php echo $row["branch_id"];?>" <?php echo $selected;?>><?php echo ucfirst($row["branch_code"]);?> | <?php echo ucfirst($row["branch_name"]);?></option>
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
										<input type="search" name="mobile_number" id="mobile_number" minlength="10" maxlength='12' class="form-control mobile_vali" value="<?php echo !empty($_GET['mobile_number']) ? $_GET['mobile_number'] :""; ?>" placeholder="9999999999" autocomplete="off">
									</div>
								</div>
							</div>

							<div class="col-md-4">
								<div class="row">
									<div class="col-md-3">
										<button type="submit" class="btn btn-info">Search <i class="fa fa-search" aria-hidden="true"></i></button>
									</div>
									<div class="col-md-3">
										<a href="<?php echo base_url(); ?>branch_items/ManageBranchItems" title="Clear" class="btn btn-default">Clear</a>
									</div>
								</div>
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
								<table id="myTable" class="table table-bordered table-hover --table-striped --dataTable">
									<thead>
										<tr>
											<th class="text-center">Controls</th>
											<th>Branch Code</th>
											<th>Branch Name</th>
											<th>Mobile Number</th>
											<th class="text-center">Total Items</th>
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
															<button type="button" class="btn btn-outline-primary gropdown-toggle waves-effect waves-light" data-toggle="dropdown" aria-expanded="false">
																Action <i class="fa fa-angle-down"></i>
															</button>
															<ul class="dropdown-menu dropdown-menu-right">
																<?php
																	if($assignBranchItemsMenu['create_edit_only'] == 1 || $assignBranchItemsMenu['read_only'] == 1 || $this->user_id == 1)
																	{ 
																		?>
																		<?php
																			if($assignBranchItemsMenu['create_edit_only'] == 1 || $this->user_id == 1)
																			{
																				?>
																				<li>
																					<a href="<?php echo base_url(); ?>branch_items/ManageBranchItems/edit/<?php echo $row['branch_id'];?>">
																						<i class="fa fa-edit"></i> Edit
																					</a>
																				</li>
																				<?php 
																			} 
																		?>

																		<?php
																			if($assignBranchItemsMenu['read_only'] == 1 || $this->user_id == 1)
																			{
																				?>
																				<li>
																					<a href="<?php echo base_url(); ?>branch_items/ManageBranchItems/view/<?php echo $row['branch_id'];?>">
																						<i class="fa fa-eye"></i> View
																					</a>
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
													
													<td class="tab-medium-width"><?php echo $row['branch_code'];?></td>
													<td class="tab-medium-width"><?php echo ucfirst($row['branch_name']);?></td>
													<td class="tab-medium-width"><?php echo $row['mobile_number'];?></td>
													<td class="tab-medium-width text-center">
														<?php
															$Branch = "select assignment_id from inv_item_branch_assign where branch_id='".$row['branch_id']."' ";
															$getBranchItems = $this->db->query($Branch)->result_array();
														?>
														<a href="<?php echo base_url(); ?>branch_items/ManageBranchItems/view/<?php echo $row['branch_id'];?>">Branch Items (<?php echo count($getBranchItems);?>)</a>
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
	
</div><!-- Content end-->


<script>
	// select all checkbox
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
	
	// set particular checked checkbox count
	/* $(".emp_checkbox").on('click', function(e) 
	{
		$("#select_count").html($("input.emp_checkbox:checked").length+" Selected");
	}); */
</script>
<script>
	/* $(document).ready(function()
	{
		$("#myInput").on("keyup", function() 
		{
			var value = $(this).val().toLowerCase();

			$("#product_table_body tr").filter(function() 
			{
				$(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
			});
		});
	}); */

	$(document).ready(function()
	{
		$("#myInput").on("input keyup", function() 
		{
			var value = $(this).val().toLowerCase();

			$("#product_table_body tr").filter(function() 
			{
				$(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
			});

			//No Data Found display start 
			if($('#product_data tbody tr:visible').length === 0) 
			{
				$('#product_data tbody').append('<tr data-no-results-found><td colspan="7" class="text-center">No data found.</td></tr>');
			}
			else 
			{
				$('#product_data tbody tr[data-no-results-found]').remove();
			}
			//No Data Found display end
		});
	});
</script>
