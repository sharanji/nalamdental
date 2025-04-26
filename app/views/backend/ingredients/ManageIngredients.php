
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
	$itemIngredientsMenu = accessMenu(item_ingredients);
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

						$title = "Assigned Ingredients";
					}
					else
					{
						if($type == "add")
						{
							
							$this->fieldDisabled = $fieldDisabled = "";
							$this->fieldReadonly = $fieldReadonly = "";
							$this->searchDropdown = $searchDropdown = "searchDropdown";
						} 
						else if($type == "edit")
						{
							$this->fieldDisabled = $fieldDisabled = "";
							$this->fieldReadonly = $fieldReadonly = "";
							$this->searchDropdown = $searchDropdown = "";
						}
						$fieldSetDisabled = "";
						
						$title = "Assign Item Ingredient";
					}
					
					?>
					<fieldset class="mt-2">
						<legend class="text-uppercase font-size-sm font-weight-bold">
							<?php echo isset($title) ? $title : ""; ?>
						</legend>
					</fieldset>

					<?php
						if($type == "edit" || $type == "view")
						{
							$itemShow = "display:block;";
							$requiredIems = "";
							$requiredStar = "";
							$requiredStar = '';

							$edit_readonly = "pointer-events:none !important";
						}
						else
						{
							$itemShow = "display:none;";
							$requiredIems = "required";
							$requiredStar = '<span class="text-danger">*</span>';
							$edit_readonly = "";
						}
						
					?>
					<form action="" --class="form-validate-jquery" enctype="multipart/form-data" method="post">
						<div class="row">
							<div class="form-group col-md-3">
								<label class="col-form-label">Branch <span class="text-danger">*</span></label>
								<?php $getbranch = $this->db->query("select branch_id,branch_name,branch_code from branch where active_flag='Y' order by branch_name asc")->result_array(); ?>
								<select name="branch_id" id="branch_id" style="<?php echo $edit_readonly;?>" <?php echo $this->fieldDisabled;?> onchange="assignBranchItems(this.value);" required class="form-control <?php echo $searchDropdown;?>">
									<option value="">- Select -</option>
									<?php 
										foreach($getbranch as $row)
										{
											$selected="";
											if(isset($editData[0]['branch_id']) && ($editData[0]['branch_id'] == $row['branch_id']) )
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

							<?php
								if($type == "add" || $type == "edit" || $type == "view")
								{
									?>
									<div class="form-group col-md-3 items" --style="<?php echo $itemShow;?>">
										<label class="col-form-label">Items <?php echo $requiredStar ;?></label>
										<select id="product" name="product" style="<?php echo $edit_readonly;?>" <?php echo $this->fieldDisabled;?> onchange="ajaxSelectItem(this.value);" style="width:390px;" <?php echo $requiredIems;?> class="form-control <?php echo $searchDropdown;?>">
											<option value="">- Select Item -</option>
											<?php 
												if($type == "edit" || $type == "view")
												{
													$branch_id = $editData[0]['branch_id'];

													$query = "select 
													inv_sys_items.item_id,
													inv_sys_items.item_name,
													inv_sys_items.item_description 
													
													from inv_item_branch_assign

													join inv_sys_items on 
														inv_sys_items.item_id = inv_item_branch_assign.item_id

													where 
													inv_item_branch_assign.branch_id='".$branch_id."'
													and inv_item_branch_assign.active_flag='Y' 
													order by item_description asc";
													
													$getItems =  $this->db->query($query)->result_array();

													foreach($getItems as $product)
													{
														$selected="";
														if(isset($editData[0]['item_id']) && ($editData[0]['item_id'] == $product['item_id']) )
														{
															$selected="selected='selected'";
														}

														?>
														<option value="<?php echo $product['item_id']; ?>" <?php echo $selected;?>><?php echo ucfirst($product['item_name']); ?> - <?php echo $product['item_description']; ?></option>
														<?php 
													}
												}
											?>
										</select>
									</div>
									<?php 
								} 
							?>
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

							function ajaxSelectItem()
							{
								$(".tbl_rows").remove();
							}

							function selectItems(val)
							{
								if(val !='')
								{
									$.ajax({
										type: "POST",
										url:"<?php echo base_url().'ingredients/ajaxSelectItems';?>",
										data: { id: val}
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

						<!-- Table start here-->
						<div class="row ingredients" style="<?php echo $itemShow;?>">
							
							<div class="col-md-12">
								<?php 
									if($type == "view")
									{
									}
									else
									{
										?>
										<a href="javascript:void();" class="btn btn-sm btn-primary" onclick="addItem();" title="Add">
											Add
										<a>
										<?php 
									}
								?>
						
								<div style="overflow-y: auto;">
									<div id="err_product" style="color:red;margin: 0px 0px 8px 0px;"></div>
									<table class="table items table-striped-- table-bordered table-condensed table-hover product_table" name="product_data" id="product_data">
										<thead>
											<tr>
												<th colspan="10"> Items</th>
											</tr>
											<tr>
												<?php 
													if($type == "add" || $type == "edit")
													{
														?><th> </th>
														<?php 
													} 
												?>
												<th class="text-center">Item Name</th>
												<th>Item Description</th>
												<th class="text-center">Ingredient Name</th>
                                                <th class="text-center">Ingredient Description</th>
												<th class="text-center">Ingredient Cost (₹)</th>
												<th class="text-center">Available Items</th> 													
											</tr>
										</thead>
										<tbody id="product_table_body">
											<?php 
												if($type == "edit" || $type == "view")
												{
													if(count($itemingredient) > 0)
													{
														$i=1;
														$counter=1;
														foreach($itemingredient as $items)
														{
															?>
															<tr>
																<?php 
																	if($type == "add" || $type == "edit")
																	{
																		?>
																		<td class='text-center tab-md-30'>
																			<input type='hidden' name='id' name='id' value="<?php echo $i; ?>">
																			<input type='hidden' name='counter' id='counter' value="<?php echo $counter; ?>">
																			<input type='hidden' name='ing_header_id[]' id='ing_header_id<?php echo $counter; ?>' value="<?php echo $items["ing_header_id"]; ?>">
																			<input type='hidden' name='ing_line_id[]' id='ing_line_id<?php echo $counter; ?>' value="<?php echo $items["ing_line_id"]; ?>">
																		</td>
																		<?php 
																	} 
																?>
																
																<td class='text-center tab-medium-width'><?php echo $items["item_name"]; ?></td>
																<td class='tab-medium-width'><?php echo $items["item_description"]; ?></td>

																<td class='text-center  tab-medium-width'>
																	<input type='text' name='ingredient_name[]' <?php echo $fieldReadonly; ?> class='form-control' id='ingredient_name<?php echo $counter;?>' value='<?php echo $items["ingredient_name"];?>'>
																</td>
																
																<td class='text-center tab-medium-width'>
																	<input type='text' name='ingredient_description[]' <?php echo $fieldReadonly; ?> class='form-control' id='ingredient_description' value='<?php echo $items["ingredient_description"]; ?>'>
																</td>
																
																<td class='tab-medium-width text-right'>
																	<input type='text' name='ingredient_cost[]' <?php echo $fieldReadonly; ?> class='form-control' id='ingredient_cost' value='<?php echo number_format($items["ingredient_cost"], 2); ?>'>
																</td>



																
																<td class='text-center  tab-medium-width'>
																	<?php 
																		if($items["active_flag"] == 'Y')
																		{
																			?>
																			<label class="switch">
																				<input class="item_status" name="item_status[]" type="checkbox" checked id="<?php echo $items["ing_line_id"];?>">
																				<div class="slider round"></div>
																			</label>
																			<?php 
																		} 
																		else
																		{ 
																			?>
																			<label class="switch">
																				<input class="item_status" name="item_status[]" type="checkbox" id="<?php echo $items["ing_line_id"];?>">
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
									<a href="<?php echo base_url(); ?>ingredients/ManageIngredients" class="btn btn-default">Close</a>
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
								$('input[type="checkbox"]').on('click',function () 
								{
									var id = $(this).attr("id");
									
									if($(this).is(':checked',true))
									{
										$.ajax({
											type: "get",
											url:"<?php echo base_url().'ingredients/ajaxAvailableIngredientsItems/status/';?>"+id+"/"+1,
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
											url:"<?php echo base_url().'ingredients/ajaxAvailableIngredientsItems/status/';?>"+id+"/"+2,
											data: { }
										}).done(function( msg ) 
										{   
											toastr.success(msg)
										});
									}
								})
							</script>
							<?php 
						} 
					?>

					<script> 
						var type = "<?php echo $type;?>";
						
						if(type == "add")
						{
							var i = 1;
							var counter = 1;
						}
						else if(type == "edit")
						{
							var i = <?php echo isset($itemingredient) ? count($itemingredient) + 1 : 1;?>;
							var counter =  <?php echo isset($itemingredient) ? count($itemingredient) + 1 : 1;?>;
						}

						var flag = 0;
						
						function addItem()
						{
							var branch_id = $("#branch_id").val();
							var id = $("#product").val();
							$('#err_product').text('');
							var flag = 0;
							
							if(id != "")
							{
								$.ajax({
									url: "<?php echo base_url('ingredients/getItems') ?>/"+id+"/"+branch_id,
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
												/* $("table.product_table").find('input[name^="item_id"]').each(function () 
												{
													if(item.item_id  == +$(this).val())
													{
														flag = 1;
													}
												}); */
												
												if(flag == 0)
												{
													var item_id = item.item_id;
													var code = item.item_name;
													var name = item.item_description;
													
													var ingredient_name = '';
													var ingredient_description = '';
													var ingredient_cost = '';

													var select_item_status = '';
													select_item_status += "<label class='switch'>";
													select_item_status += "<input type='checkbox' checked name='item_status[]' id='item_status"+ counter +"' class='item_status'>";
													select_item_status += "<div class='slider round'></div></label>";

													var newRow = $("<tr class='dataRowVal"+id+" tbl_rows'>");
													var cols = "";
													cols += "<td class='text-center tab-md-30'><a class='deleteRow'> <i style='color:#ed2025;' class='fa fa-trash'></i> </a><input type='hidden' name='id' name='id' value="+i+"><input type='hidden' name='counter' value="+counter+"><input type='hidden' name='item_id[]' id='item_id"+counter+"' value="+item_id+"><input type='hidden' name='ing_line_id[]' id='ing_line_id"+counter+"' value='0'></td>";
													cols += "<td class='tab-medium-width text-center'>"+code+"</td>";
													cols += "<td class='tab-medium-width '>"+name+"</td>";
													
													//Ingredient Name
													cols += "<td class='tab-medium-width'>" 
															+"<span id='price'>"
																+"<input type='text' class='form-control' name='ingredient_name[]' id='ingredient_name"+ counter +"' value='"+ingredient_name
															+"'></span>"
															+"</td>";

													//Ingredient Description
													cols += "<td class='tab-medium-width'>" 
															+"<input type='text' class='form-control' name='ingredient_description[]' id='ingredient_description"+ counter +"' value='"+ingredient_description
															+"'>"
															+"</td>";
													
													//Ingredient Cost
													cols += "<td class='tab-medium-width'>" 
																+"<input type='number' class='form-control' name='ingredient_cost[]' id='ingredient_cost"+ counter +"' value='"+ingredient_cost
															+"'>"
															+"</td>";
													

													cols += '<td class="text-center">'+select_item_status+'<input type="hidden" name="line_status[]" id="line_status'+counter+'" value="Y"></td>';
														
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
							else
							{
								$('#err_product').text('Please select item').animate({opacity: '0.0'}, 2000).animate({opacity: '0.0'}, 1000).animate({opacity: '1.0'}, 2000);
							}
						}
							
						$("table.product_table").on("click", "a.deleteRow", function (event) 
						{
							deleteRow($(this).closest("tr"));
							$(this).closest("tr").remove();
						});

						function deleteRow(row)
						{
							var id = +row.find('input[name^="id"]').val();
						}

						$("table.product_table").on("input keyup change", 'input[name^="price"], input[name^="ingredient_description"], input[name^="item_status"], input[name^="ingredient_cost"], input[name^="minimum_order_value"]', function (event) 
						{
							calculateRow($(this).closest("tr"));
						});
				
						function calculateRow(row) 
						{
							var key = +row.find('input[name^="id"]').val();
							var price = +row.find('input[name^="price"]').val();
							var counter = +row.find('input[name^="counter"]').val();

							//var ingredient_description = +row.find('input[name^="ingredient_description"]').val();
							//var ingredient_cost = +row.find('input[name^="ingredient_cost"]').val();
							//var item_status = +row.find('#item_status').val();
							if($("#item_status"+counter).is(':checked',true))
							{
								var item_status = 'Y';
							}else{
								var item_status = 'N';
							}
							$("#line_status"+counter).val(item_status);
							/* product_data[key].price = price.toFixed(2);
							product_data[key].item_status = item_status;
							product_data[key].ingredient_description = ingredient_description;
							product_data[key].ingredient_cost = ingredient_cost;
							var table_data = JSON.stringify(product_data);
							$('#table_data').val(table_data); */
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
								if($itemIngredientsMenu['create_edit_only'] == 1 || $this->user_id == 1)
								{
									?>
									<a href="<?php echo base_url(); ?>ingredients/ManageIngredients/add" class="btn btn-info btn-sm">
										Create Item Ingredient
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
							<div class="col-md-4 -float-right -text-right">
								<button type="submit" class="btn btn-info">Search <i class="fa fa-search" aria-hidden="true"></i></button>&nbsp;
								<a href="<?php echo base_url(); ?>ingredients/ManageIngredients" title="Clear" class="btn btn-default">Clear</a>
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
											<th class="text-center">Branch Name</th>
											<th class="text-center">Item Name</th>
											<th>Item Description</th>
											<th class="text-center">Ingredient</th>
											<th class="text-center">status</th>
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
																	if($itemIngredientsMenu['create_edit_only'] == 1 || $itemIngredientsMenu['read_only'] == 1 || $this->user_id == 1)
																	{ 
																		?>
																		<?php
																			if($itemIngredientsMenu['create_edit_only'] == 1 || $this->user_id == 1)
																			{
																				?>
																				<li>
																					<a href="<?php echo base_url(); ?>ingredients/ManageIngredients/edit/<?php echo $row['ing_header_id'];?>">
																						<i class="fa fa-edit"></i> Edit
																					</a>
																				</li>
																				<?php 
																			} 
																		?>

																		<?php
																			if($itemIngredientsMenu['read_only'] == 1 || $this->user_id == 1)
																			{
																				?>
																				<li>
																					<a href="<?php echo base_url(); ?>ingredients/ManageIngredients/view/<?php echo $row['ing_header_id'];?>">
																						<i class="fa fa-eye"></i> View
																					</a>
																				</li>
																				<?php 
																			} 
																		?>

																		<?php
																			if($itemIngredientsMenu['create_edit_only'] == 1 || $this->user_id == 1)
																			{
																				?>
																				<li>
																					<?php 
																						if($row['active_flag'] == $this->active_flag)
																						{
																							?>
																							<a class="unblock" href="<?php echo base_url(); ?>ingredients/ManageIngredients/status/<?php echo $row['ing_header_id'];?>/N" title="Active">
																								<i class="fa fa-ban"></i> Inactive
																							</a>
																							<?php 
																						} 
																						else
																						{  ?>
																							<a class="block" href="<?php echo base_url(); ?>ingredients/ManageIngredients/status/<?php echo $row['ing_header_id'];?>/Y" title="InActive">
																								<i class="fa fa-ban"></i> Active
																							</a>
																							<?php 
																						} 
																					?>
																				<li>
																				<?php 
																			} 
																		?>

																		<?php 
																	} 
																?>
															</ul>
														</div>
													</td>
													<td class="tab-medium-width text-center"><?php echo $row['branch_name'];?></td>
													<td class="tab-medium-width text-center"><?php echo $row['item_name'];?></td>
													<td class="tab-medium-width"><?php echo ucfirst($row['item_description']);?></td>
													<td class="tab-medium-width text-center">
														<?php
															$query = "select 
															ing_line.*,
															inv_sys_items.item_name,
															inv_sys_items.item_description
														
															from inv_item_ingredient_line as ing_line
														
															left join inv_sys_items on inv_sys_items.item_id = ing_line.item_id
															
															where ing_line.ing_header_id='".$row['ing_header_id']."' ";
															
															$lineResult = $this->db->query($query)->result_array();
														?>

								
														<a href="javascript:void(0);" data-toggle="modal" data-target="#exampleModal<?php echo $row['ing_header_id'];?>">Ingredient (<?php echo count($lineResult);?>)</a>
														
														<div class="modal fade" id="exampleModal<?php echo $row['ing_header_id'];?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
															<div class="modal-dialog" role="document">
																<div class="modal-content">
																<div class="modal-header">
																	<h5 class="modal-title" id="exampleModalLabel">Ingredient Details</h5>
																	<button type="button" class="close" data-dismiss="modal" aria-label="Close">
																	<span aria-hidden="true">&times;</span>
																	</button>
																</div>
																<div class="modal-body">
																	<table class="table">
																	<thead>
																		<tr>
																		<th>Item Name</th>
																		<th>Item Description</th>
																		<th>Ingredient Name</th>
																		<th>Ingredient Description</th>
																		<th>Ingredient Cost (₹)</th>
																		</tr>
																	</thead>
																	<tbody>
																		<?php foreach ($lineResult as $ingredient) { ?>
																		<tr>
																			<td><?php echo $ingredient['item_name']; ?></td>
																			<td><?php echo $ingredient['item_description']; ?></td>
																			<td><?php echo $ingredient['ingredient_name']; ?></td>
																			<td><?php echo $ingredient['ingredient_description']; ?></td>
																			<!-- <td><?php echo $ingredient['ingredient_cost']; ?></td> -->
																			<td class="float:right;">
																				<?php echo number_format($ingredient['ingredient_cost'],DECIMAL_VALUE,'.','');?>
																			</td>
																		</tr>
																		<?php } ?>
																	</tbody>
																	</table>
																</div>
																<div class="modal-footer">
																	<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
																</div>
																</div>
															</div>
														</div>

													</td>
													<td class="tab-mobile-width text-center">
														<?php 
															if($row['active_flag'] == $this->active_flag)
															{
																?>
																<span class="btn btn-outline-success btn-sm" title="Active">
																	Active 
																</span>
																<?php 
															} 
															else
															{  ?>
																<span class="btn btn-outline-warning btn-sm" title="Inactive">
																	Inactive 
																</span>
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
