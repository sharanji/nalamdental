<style type="text/css">
	#printable { display: none; }
	@media print
	{
		#non-printable { display: none; }
		#printable { display: block; }
	}
	.content-address-lft{float:left;}
	.content-address-rgt{float:right;}
	p.lic_no {
		float: left;
		margin: 0px 0px 2px 30px;
	}
	.table-bordered>thead>tr>th, .table-bordered>tbody>tr>th, .table-bordered>tfoot>tr>th, .table-bordered>thead>tr>td, .table-bordered>tbody>tr>td, .table-bordered>tfoot>tr>td{    border: 1px solid #bfbebe!important;}
</style>
<style>

#myInput {
  background-position: 10px 10px;
  background-repeat: no-repeat;
  width: 100%;
  font-size: 16px;
  padding: 12px 20px 12px 40px;
  border: 1px solid #ddd;
  margin-bottom: 12px;
  border-radius:5px;
}

#myInput:focus {
  outline: none;
}

#myInput:hover {
   border: 1px solid #b1b0b0;
}

#myTable {
  border-collapse: collapse;
  width: 100%;
  border: 1px solid #ddd;
}

#myTable th, #myTable td {
  text-align: left;
  padding: 12px;
}

#myTable tr {
  border-bottom: 1px solid #ddd;
}

#myTable tr.header, #myTable tr:hover {
  background-color: #f1f1f1;
}

#myTable tr.header, #myTable tr:hover {
  background-color: #f1f1f1;
}

.filter-search {
    position: relative;
    top: 35px;
    padding: 0px 0px 0px 19px;
    color: #b7b5b5;
    font-size: 18px;
}
</style>

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

<script language="javascript">
	function printDiv(divName) 
	{ 
		var printContents = document.getElementById(divName).innerHTML; 
		var originalContents = document.body.innerHTML; 
		document.body.innerHTML = printContents; window.print(); 
		document.body.innerHTML = originalContents; 
	}
</script>
<div class="page-header page-header-light">
	<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
		
		<div class="d-flex">
			<div class="breadcrumb">
				<a href="<?php echo base_url();?>admin/dashboard" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> <?php echo get_phrase('Home');?></a>
				<a href="javascript:void(0);" class="breadcrumb-item">
					<?php echo $page_title;?>
				</a>
			</div>
		</div>
		
		<div class="top-right" style="float:right;">
			<a title="Print" onclick="printDiv('printableArea')"  class="btn btn-warning" href="javascript:void(0);">
				<i class="fa fa-print"></i> Print
			</a>
			
			<a href="<?php echo base_url(); ?>branch_items/ManageBranchItems/edit/<?php echo $id;?>/<?php echo $branch_id;?>" class="btn btn-info" title="Edit">
				<i class="fa fa-pencil" aria-hidden="true"></i> Edit
			</a>
		</div>
	</div>
</div>

<?php 
	$data = $this->branch_items_model->getRecord($id);
	$items = $this->branch_items_model->getBranchItems($id,$branch_id);
?>
<div class="content"><!-- Content start-->
	<div class="card"><!-- Card start-->
		<div class="card-body">
			<div id="printableArea">
				<!--
				<fieldset class="mt-2">
					<legend class="text-center text-uppercase font-size-lg font-weight-bold">
						Branch Items
					</legend>
				</fieldset>
				
				<div style="border-bottom: 2px solid #0195b2;margin-bottom: 5px;"></div>
				-->
				
				<br/>
				<!-- Header Data start -->
				<div class="col-md-12">
					<div class="row">
						<div class="col-md-2">
							<label>Branch Name / Code</label>
						</div>
						<div class="col-md-1">:</div>
						<div class="col-md-4">
							<?php echo isset($data[0]['branch_name']) ? $data[0]['branch_name']:"";?> - <?php echo isset($data[0]['branch_code']) ? $data[0]['branch_code']:"";?>
						</div>
					</div>
					
					<div class="row mt-2">
						<div class="col-md-2">
							<label>Phone Number</label>
						</div>
						<div class="col-md-1">:</div>
						<div class="col-md-4">
							<?php echo $data[0]['phone_number'];?>
						</div>
					</div>
					
					<div class="row mt-2">
						<div class="col-md-2">
							<label>Address</label>
						</div>
						<div class="col-md-1">:</div>
						<div class="col-md-4">
							<?php echo ucfirst($data[0]['address']);?>
						</div>
					</div>
				</div>
				<div class="col-md-12">
				<br>
					<div style="border-bottom: 2px solid #0195b2;"></div>
				<br></div>
				<!-- Header Data end -->
				
				<!-- Line Data start -->
				<?php 
					foreach($data as $row)
					{ 
						?>	
						<div class='new-scroller'>
							<div class="col-md-12">
								
								<i class="fa fa-search filter-search"></i>
								
								<input type="text" id="myInput" onkeyup="myFunction()" placeholder="Search Keywords..." title="Type in a Item Code">
								<span>Total Items : <?php echo count($items);?></span>
								
								<table class="mt-2 table table-bordered table-hover" id="tableData">
									<thead>
										
										<tr>
											<th class="text-center">Item Code</th>
											<th class="text-center">Category Name</th>
											<th>Items</th>
											<th class="text-center">Available Qty</th>
											<th class="text-center">Max Order Qty</th>
											<!--<th class="text-center">Min Order Value</th>-->
											<th class="text-center">Price ( <?php echo CURRENCY_SYMBOL?> )</th>
											<th class="text-center">Available Items</th> 													
										</tr>
									</thead>
									<tbody id="myTable">
										<?php
											$i=1;
											foreach ($items as $key) 
											{
												?>
												<tr>
													<td class="tab-medium-width text-center"><?php echo $key->product_code; ?></td>
													
													<td class="tab-medium-width">
														<?php echo ucfirst($key->category_name); ?>
													</td>
													
													<td class='tab-medium-width'>
														<?php echo ucfirst($key->product_name); ?><br>
														<?php 
															$queryw = "select 
															vb_branch_item_ingredients.*,
															vb_product_ingredients.ingredient_name,
															vb_product_ingredients.ingredient_id 
															
															from vb_branch_item_ingredients

															left join vb_product_ingredients on 
																vb_product_ingredients.ingredient_id = vb_branch_item_ingredients.ingredient_id
																where vb_branch_item_ingredients.branch_id ='".$data[0]['branch_id']."' and
																vb_branch_item_ingredients.product_id='".$key->product_id."' ";


															$getBranchIngredien = $this->db->query($queryw)->result_array();
															
															
															
															if(count($getBranchIngredien) > 0){
																$fontColor = '';
															}else{$fontColor = 'red';}
														?>

														<a style="color:<?php echo  $fontColor;?>" href="javascript:void(0);" data-toggle="modal" data-target="#exampleModal<?php echo $key->branch_item_ing_line_id;?>">
															Ingredients (<?php echo count($getBranchIngredien);?>)
														</a>

														<!-- Modal -->
														<div class="modal fade" id="exampleModal<?php echo $key->branch_item_ing_line_id;?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
															<div class="modal-dialog" role="document">
																<div class="modal-content">
																	<div class="modal-header" style="background: #022646;color: #fff;">
																		<h5 class="modal-title" id="exampleModalLabel">Ingredients</h5>
																		<button type="button" class="close" data-dismiss="modal" aria-label="Close">
																			<span aria-hidden="true">&times;</span>
																		</button>
																	</div>
																	
																	<form action="" method="post">
																		<div class="modal-body">
																			
																			<div class="row">
																				<div class="col-md-3">Category Name</div>
																				<div class="col-md-1">:</div>
																				<div class="col-md-8">
																					<?php echo ucfirst($key->category_name); ?>
																				</div>
																			</div>

																			<div class="row mt-2">
																				<div class="col-md-3">Item Code</div>
																				<div class="col-md-1">:</div>
																				<div class="col-md-8"><?php echo $key->product_code; ?></div>
																			</div>

																			<div class="row mt-2">
																				<div class="col-md-3">Item Name</div>
																				<div class="col-md-1">:</div>
																				<div class="col-md-8"><?php echo ucfirst($key->product_name); ?></div>
																			</div>
																			<?php 
																			
																			if(count($getBranchIngredien) > 0)
																			{
																				?>
																				<div class="row mt-3">
																					<?php
																						if(count($getBranchIngredien) >0 )
																						{
																							?>
																							<table class="table table-bordered table-hover table-striped">
																								<thead>
																									<tr>
																										<th>Ingredient Name</th>
																										<th>Price (<?php echo CURRENCY_CODE;?>)</th>
																										<th>Status</th>
																									</tr>
																								</thead>
																								<?php
																									foreach($getBranchIngredien as $ingredients)
																									{
																										?>
																										<tr --style="display:block;">
																											<td>
																												<?php echo $ingredients['ingredient_name'];?>
																											</td>
																											<td>
																												<input type="hidden" name="ingredient_id[]" class="form-control" value="<?php echo $ingredients['ingredient_id'];?>">
																												
																												<input type="hidden" name="product_id[]" class="form-control" value="<?php echo $ingredients['product_id'];?>">
																												<input type="hidden" name="branch_id[]" class="form-control" value="<?php echo $data[0]['branch_id'];?>">
																												<input type="text" name="price[]" class="form-control" value="<?php echo number_format($ingredients['price'],DECIMAL_VALUE,'.','');?>">
																											</td>
																											<td>
																												<select name="ingredient_branch_status[]" class="form-control">
																													<?php 
																														foreach($this->product_branch_status as $key1=>$value1)
																														{
																															$selected="";
																															if($ingredients['ingredient_branch_status'] == $key1)
																															{
																																$selected="selected='selected'";	
																															}
																															?>
																															<option value="<?php echo $key1;?>" <?php echo $selected;?>><?php echo $value1;?></option>
																															<?php
																														}
																													?> 
																												</select>
																											</td>
																										</tr>
																										<?php
																									}
																								?>
																							</table>
																							<?php
																						}	
																					?>
																				</div>
																				<?php
																			}
																			else
																			{
																				?>
																				<?php 
																					$IngredientsQry = "select vb_product_ingredients.* from vb_product_ingredients
																					left join products on products.product_id = vb_product_ingredients.product_id
																					where 
																						vb_product_ingredients.status=1 and
																							products.product_status =1 and 
																							vb_product_ingredients.product_id='".$key->product_id."'	
																							";
																							
																					$getProductIngredients1 = $this->db->query($IngredientsQry)->result_array();
																					
																					
																				?>

																				<div class="row mt-3">
																					<?php
																						if(count($getProductIngredients1) >0 )
																						{
																							?>
																							<table class="table table-bordered table-hover table-striped">
																								<thead>
																									<tr>
																										<th>Ingredient Name</th>
																										<th>Price (<?php echo CURRENCY_CODE;?>)</th>
																										<th>Status</th>
																									</tr>
																								</thead>
																								<?php
																									foreach($getProductIngredients1 as $pr_ingredients)
																									{
																										?>
																										<tr --style="display:block;">
																											<td>
																												<?php echo $pr_ingredients['ingredient_name'];?>
																											</td>
																											<td>
																												<input type="hidden" name="ingredient_id[]" class="form-control" value="<?php echo $pr_ingredients['ingredient_id'];?>">
																												
																												<input type="hidden" name="product_id[]" class="form-control" value="<?php echo $pr_ingredients['product_id'];?>">
																												<input type="hidden" name="branch_id[]" class="form-control" value="<?php echo $data[0]['branch_id'];?>">
																												<input type="text" name="price[]" class="form-control" required value="0">
																											</td>
																											<td>
																												<select name="ingredient_branch_status[]" class="form-control">
																													<?php 
																														foreach($this->product_branch_status as $key2=>$value)
																														{
																															?>
																															<option value="<?php echo $key2;?>"><?php echo $value;?></option>
																															<?php
																														}
																													?> 
																												</select>
																											</td>
																										</tr>
																										<?php
																									}
																								?>
																							</table>
																							<?php
																						}
																						else
																						{
																							?>
																							<span class="text-center" style="color:red;padding: 0px 0px 0px 10px;font-style: italic;">No Ingredients.</span>
																							&nbsp;&nbsp;
																							<a target="_blank" href="<?php echo base_url();?>products/ManageProducts/ingredients/<?php echo $key->product_id;?>">
																								Add Ingredient
																							</a>
																							<?php
																						}	
																					?>
																				</div>
																				<?php 
																			} 
																		?>
																		</div>

																		<?php
																			if(isset($getBranchIngredien) || isset($getProductIngredients1) )
																			{
																				if(count($getBranchIngredien) > 0 || count($getProductIngredients1) > 0 )
																				{
																					?>
																					<div class="modal-footer">
																						<button type="button" class="btn btn-light" data-dismiss="modal">Close </button>
																						<button type="submit" name="add" class="btn btn-primary ml-3">Update</button>
																					</div>
																					<?php
																				}
																			}
																		?>
																	</form>
																</div>
															</div>
														</div>
														<!-- Modal end -->
													</td>
													
													<td class='tab-medium-width text-center'>
														<?php echo $key->available_quantity;?>
													</td>
													
													<td class='tab-medium-width text-center'>
														<?php 
															if($key->minimum_order_quantity > 0)
															{
																$orderQtyClass="";
															}
															else
															{
																$orderQtyClass="color:red;";
															}
														?>
														<span style="<?php echo $orderQtyClass;?>">
															<?php echo $key->minimum_order_quantity;?>
														</span>
													</td>
													
													<?php /*
													<td class='tab-medium-width text-center'>
														<?php 
															if($key->minimum_order_value > 0)
															{
																$orderValClass="";
															}
															else
															{
																$orderValClass="color:red;";
															}
														?>
														<span style="<?php echo $orderValClass;?>">
															<?php echo $key->minimum_order_value;?>
														</span>
													</td>
													*/ ?>
													
													<td class='tab-medium-width text-center'>
														<?php echo number_format($key->price,DECIMAL_VALUE,'.','');?>
													</td>
													
													<td class='tab-medium-width text-center'>
														<?php 	
															/* foreach($this->product_branch_status as $key1 => $value) 
															{
																if($key->item_status == $key1)
																{
																	if($key->item_status == 1)
																	{
																		echo '<span class="text-success">'.$value.'</span>';
																	}
																	else if($key->item_status == 2)
																	{
																		echo '<span class="text-warning">'.$value.'</span>';
																	}
																}
															} */ 
														?>
														
														<?php 
															if($key->item_status == 1)
															{
																?>
																<label class="switch">
																	<input class="item_status" name="item_status" type="checkbox" checked id="<?php echo $key->branch_item_ing_line_id;?>">
																	<div class="slider round"></div>
																</label>
																<?php 
															} 
															else
															{ 
																?>
																<label class="switch">
																	<input class="item_status" name="item_status" type="checkbox" id="<?php echo $key->branch_item_ing_line_id;?>">
																	<div class="slider round"></div>
																</label>
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
							</div>
						</div>
						<?php
					} 
				?>
				<!-- Line Data end -->
			</div>
		</div>
		
		<script>
			$('input[type="checkbox"]').on('click',function () 
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
						toastr.success(msg)
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
			})
		</script>
	
		<?php if($this->user_id ==1){?>
			<div class="row mt-3 mb-3 mr-3">
				<div class="col-md-10"> </div>
				<div class="col-md-2" style="text-align:right;">
					<a href="<?php echo base_url();?>branch_items/ManageBranchItems" class="btn btn-primary" title="Back">
						<i class="fa fa-arrow-left" aria-hidden="true"></i> Back
					</a>
				</div>
			</div>
		<?php } ?>
		
	</div>
</div>

<script>
    /* 	function myFunction() 
	{	
		var input, filter, table, tr, td, i, txtValue;

		input = document.getElementById("myInput");
		filter = input.value.toUpperCase();
		table = document.getElementById("myTable");
		tr = table.getElementsByTagName("tr");
		
		for (i = 0; i < tr.length; i++) 
		{
			var td1 = tr[i].getElementsByTagName("td")[0];
			var td2 = tr[i].getElementsByTagName("td")[1];
			var td3 = tr[i].getElementsByTagName("td")[2];
			
			if(td1!="")
			{
				var td = td1;
			}
			
			if(td2!="")
			{
				var td = td2;
			}
			
			if(td3!="")
			{
				var td = td3;
			}
			
			if (td) 
			{
				txtValue = td.textContent || td.innerText;
				
				if (txtValue.toUpperCase().indexOf(filter) > -1) 
				{
					tr[i].style.display = "";
				} 
				else 
				{
					tr[i].style.display = "none";
				}
			}       
		}
	} */
</script>
<script>
	$(document).ready(function()
	{
		$("#myInput").on("keyup", function() 
		{
			var value = $(this).val().toLowerCase();

			$("#myTable tr").filter(function() 
			{
				$(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
			});

			//No Data Found display start 
			if($('#tableData tbody tr:visible').length === 0) 
			{
				$('#tableData tbody').append('<tr data-no-results-found><td colspan="7" class="text-center">No data found.</td></tr>');
			}
			else 
			{
				$('#tableData tbody tr[data-no-results-found]').remove();
			}
			//No Data Found display end
		});
	});
</script>