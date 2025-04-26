<style>
	table tr, td
	{
		font-family: 'Helvetica',sans-serif !important;
		
	}
	.table-class {
		font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
		border-collapse: collapse;
		width: 100%;
		font-size: 11px;
	}
	.table-total-sale
	{
		border-collapse: collapse;
	}
	.table-class td, .table-class th {
		border: 1px solid #ddd;
		padding: 6px;
		font-size: 11px;
		font-weight: 400;
		
	}
	table.pur-headers-tbl tbody tr td
	{
		border:none;
	}
	table.pur-headers-tbl
	{
		padding:0px;
		margin:0px;
		border:1px solid #ddd;
		width:100%;
	}
	table.table-products thead tr th, td
	{
		border:1px solid #ddd;
		padding:5px 10px!important;
		border-collapse: collapse !important;
		margin:0px!important;
		padding:5px;
	}
	table.table-products
	{
		border-collapse: collapse !important;
	}
	table.table-products thead tr th, td
	{
		border:1px solid #ddd;
		padding:0px!important;
		border-collapse: collapse !important;
		margin:0px!important;
		padding:5px;
	}
	.table-class tr:nth-child(even){background-color: #fff;}
	
	.table-class tr:hover {background-color: #ddd;}
	
	.table-class th {
		padding-top: 6px;
		padding-bottom: 6px;
		text-align: left;
		background-color: #fff;
		color: #000;
		text-align: center;
	}
	tr.th-background th{background:#ddd;border:1px solid #b7b7b7;font-size:11px;}
	tbody.fields-td-new tr td{font-size:11px;}
</style>

<table class="pur-headers-tbl">

<tbody>
		<tr>
			<td style="width:50%;">
				<?php 
					$customer_name		= isset($edit_data[0]['customer_name'])?$edit_data[0]['customer_name']: NULL;
					$order_number		= isset($edit_data[0]['order_number'])?$edit_data[0]['order_number']: NULL;
					$customer_contact	= isset($edit_data[0]['contact_person'])?$edit_data[0]['contact_person']: NULL;
					$order_date			= isset($edit_data[0]['order_date'])?$edit_data[0]['order_date']: NULL;
					$customer_po		= isset($edit_data[0]['customer_po'])?$edit_data[0]['customer_po']: NULL;
					$so_status			= isset($edit_data[0]['so_status'])?$edit_data[0]['so_status']: NULL;
					$organization_name	= isset($edit_data[0]['organization_name'])?$edit_data[0]['organization_name']: NULL;
					$branch_name		= isset($edit_data[0]['branch_name'])?$edit_data[0]['branch_name']: NULL;
					$payment_term		= isset($edit_data[0]['payment_term'])?$edit_data[0]['payment_term']: NULL;
					$currency			= isset($edit_data[0]['currency'])?$edit_data[0]['currency']: NULL;
					?>
						<span colspan="4" >Customer Name &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:&nbsp;&nbsp;<?php echo $customer_name;?></span><br><br>
					<?php

					if($customer_contact!=NULL){
						?>
							<span colspan="4" >Customer Contact &nbsp;&nbsp;&nbsp;:&nbsp;&nbsp;<?php echo $customer_contact;?></span><br><br>
						<?php
					}

					if($customer_po!=NULL){
						?>
							<span colspan="4">Customer PO &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:&nbsp;&nbsp;<?php echo $customer_po;?></span><br><br>
						<?php
					}
					
				?>
				<span colspan="4">Organization Name&nbsp;&nbsp;:&nbsp;&nbsp;<?php echo $organization_name;?></span><br><br>
				<span colspan="4">Branch Name &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:&nbsp;&nbsp;<?php echo $branch_name;?></span><br><br>
			</td>
			<td style="width:50%;">
				<span>Order Number &nbsp;&nbsp;&nbsp;:&nbsp;<?php echo $order_number;?></span><br><br>
				<span>Order Date &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:&nbsp;<?php echo date('d-M-Y',strtotime($order_date));?></span><br><br>
				<span>Status &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:&nbsp;<?php echo $so_status;?></span><br><br>
				<span>Payment Term &nbsp;&nbsp;&nbsp;:&nbsp;&nbsp;<?php echo $payment_term;?></span><br><br>
				<span>Currency &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:&nbsp;&nbsp;<?php echo $currency;?></span><br><br>
			</td>
		</tr>
	</tbody>

</table>
<br>

<table class="table table-bordered table-products table-hover" width="100%" style="font-size:12px;color:#000; text-align:center;">
	<thead>
		<tr>
			<th style="text-align:center;">S.No</th>
			<th class="text-center tab-md-150">Item</th>
			<th class="text-center tab-md-150">Description</th>
			<th class="text-center tab-md-150">Category</th>
			<th class="text-center tab-md-150">Customer Item</th>
			<th class="text-center tab-md-150">Status</th>
			<th class="text-center tab-md-100">Quantity</th>
			<th class="text-center tab-md-100">UOM </th>
			<th class="text-center tab-md-100">Delivery Date </th>
			<!-- <th class="text-center tab-md-100">Unit Price</th>
			<th class="text-center tab-md-100">Tax (%)</th>
			<th class="text-center tab-md-100">Discount Type</th>
			<th class="text-center tab-md-100">Discount</th>
			<th class="text-center tab-md-100">Price</th>
			<th class="text-center tab-md-100">Line Value</th>
			<th class="text-center tab-md-100">Total Tax</th>											
			<th class="text-center tab-md-100">Total</th>											 -->
		</tr>
	</thead>
	<tbody>
		<?php 
			if( count($line_data) > 0)
			{
				$counter = 1;
				foreach($line_data as $lineItems)
				{
					?>
					<tr>
						<td style="text-align:center;">
							<?php echo $counter;?>
						</td>
						<td class="tab-md-150">
							<?php echo $lineItems["item_name"];?>
						</td>
						<td class="tab-md-150">
							<?php echo $lineItems["item_description"];?>
						</td>
						<td class="tab-md-150">
							<?php echo $lineItems["category_name"];?>
						</td>
						<td class="tab-md-150">
							<?php echo $lineItems["customer_item"];?>
						</td>
						<td class="tab-md-150">
							<?php echo $lineItems["line_status"];?>
						</td>
						<td class="tab-md-150">
							<?php echo $lineItems["quantity"];?>
						</td>
						<td class="tab-md-100">
							<?php echo $lineItems["uom_code"];?>
						</td>
						<td class="tab-md-150">

							<?php 
								$delivery_date = date("d-M-Y",strtotime($lineItems['delivery_date']));
								echo $delivery_date;
							?>
						</td>
						<?php /*
							?>
								
								<td class="tab-md-100">
									<?php echo $lineItems["unit_price"];?>
								</td>
								<td class="tab-md-100">
									<?php echo $lineItems["tax"];?>
								</td>
								<td class="tab-md-100">
									<?php echo $lineItems["discount_type"];?>
								</td>
								<td class="tab-md-100">
									<?php echo $lineItems["discount"];?>
								</td>
								
								<td class="tab-md-100">
									<?php echo $lineItems["effective_price"];?>
								</td>
								<td class="tab-md-100">
									<?php echo $lineItems["line_value"];?>
								</td>
								<td class="tab-md-100">
									<?php echo $lineItems["total_tax"];?>
								</td>
								<td class="tab-md-100">
									<?php echo $lineItems["total"];?>
								</td>
							<?php
						*/ 	?>
						
					</tr>
					<?php
					$counter++;
				} 
			} 
		?>
	</tbody>
</table>