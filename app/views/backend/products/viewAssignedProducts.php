
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
<div class="content"><!-- Content start-->
	<div class="card"><!-- Card start-->
		<div class="card-body">
			<div class="row mb-2">
				<div class="col-md-6">Product Locators</div>
				<div class="col-md-6 float-right text-right">
					<a href="<?php echo base_url(); ?>products/assignProductLocator" class="btn btn-info btn-sm">
						Back
					</a>
				</div>
			</div>

			<?php
				$assignProductsHeader = "select  warehouse.warehouse_name from inv_assign_product_locator_header
								left join warehouse on 
									warehouse.warehouse_id = inv_assign_product_locator_header.warehouse_id
						where 
							inv_assign_product_locator_header.header_id = '".$id."' 
				";
				$getAssignProductsHeader = $this->db->query($assignProductsHeader)->result_array();
			?>
	
			<div class="row mb-2">
				<div class="col-md-2">Warehouse</div>
				<div class="col-md-1">:</div>
				<div class="col-md-5"><?php echo isset($getAssignProductsHeader[0]["warehouse_name"]) ? ucfirst($getAssignProductsHeader[0]["warehouse_name"]) :"";?></div>
			</div>
	
			<?php
				$assignProducts = "select 
									inv_assign_product_locator_line.*,
									products.product_name,
									products.product_code,
									inv_item_locators.locator_no,
									inv_item_locators.locator_name,
									inv_item_sub_inventory.inventory_code,
									inv_item_sub_inventory.inventory_name

									from inv_assign_product_locator_line 

								left join products on 
									products.product_id = inv_assign_product_locator_line.product_id

								left join inv_item_sub_inventory on 
									inv_item_sub_inventory.inventory_id = inv_assign_product_locator_line.inventory_id

								left join inv_item_locators on 
									inv_item_locators.locator_id = inv_assign_product_locator_line.locator_id
						where 
							inv_assign_product_locator_line.header_id = '".$id."' 
				";
				$getAssignProducts = $this->db->query($assignProducts)->result_array();

				
			?>
			<div class="row mb-3">
				<div class="col-md-2">Total Assigned Items</div>
				<div class="col-md-1">:</div>
				<div class="col-md-5"><?php echo count($getAssignProducts);?></div>
			</div>

			<div class="new-scroller">
				<table id="myTable" class="table table-bordered table-hover dataTable">
					<thead>
						<tr>
							<th class="text-center">Product Code</th>
							<th>Product Description</th>
							<th  class="text-center">Sub Inventory</th>
							<th  class="text-center">Locator</th>
							<th class="text-center">Status</th>
						</tr>
					</thead>
					<tbody>
						<?php 	
							foreach($getAssignProducts as $row)
							{
								?>
								<tr>
									<td class="tab-medium-width text-center"><?php echo ucfirst($row['product_code']);?></td>
									<td class="tab-medium-width"><?php echo ucfirst($row['product_name']);?></td>
									<td class="tab-medium-width text-center">
										<?php echo $row['inventory_code'];?> - <?php echo ucfirst($row['inventory_name']);?>
									</td>
									<td class="tab-medium-width text-center"><?php echo $row['locator_no'];?> - <?php echo ucfirst($row['locator_name']);?></td>
									<td class="tab-medium-width text-center">
										<?php 
											if($row['assign_line_status'] == 1)
											{
												?>
												<label class="switch">
													<input class="item_status" name="item_status" type="checkbox" checked id="<?php echo $row['line_id'];?>">
													<div class="slider round"></div>
												</label>
												<?php 
											} 
											else
											{ 
												?>
												<label class="switch">
													<input class="item_status" name="item_status" type="checkbox" id="<?php echo $row['line_id'];?>">
													<div class="slider round"></div>
												</label>
												<?php 
											} 
										?>
									</td>
								</tr>
								<?php 
							}
						?>
					</tbody>
				</table>
			</div>
		</div>
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
				url:"<?php echo base_url().'products/ajaxAssignProductLocator/status/';?>"+id+"/"+1,
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
				url:"<?php echo base_url().'products/ajaxAssignProductLocator/status/';?>"+id+"/"+2,
				data: { }
			}).done(function( msg ) 
			{   
				toastr.success(msg)
			});
		}
	})
</script>
			
	
