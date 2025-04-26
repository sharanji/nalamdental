<?php 
	$i=0;
	$firstItem = isset($first_item) ? $first_item : 0;
	$totalgrandTotal=0;
	foreach($resultData as $row)
	{
		$orderStatus = $row['order_status'];
		?>
		<tr>
			<td class="tab-md-30 text-center">
				<?php
					if(isset($_GET["order_status"]) && !empty($_GET["order_status"]))
					{ 
						if($_GET["order_status"] == "Total_Orders" && $orderStatus == "Booked")
						{
							#Suresh changes
							?>
							<!-- <input type="checkbox" name="checkbox[]" class="emp_checkbox" value="<?php echo $row['header_id']; ?>"> -->
							<?php 
						}
						else if(
							(
								$_GET["order_status"] == "Booked" 
								|| $_GET["order_status"] == "Confirmed" 
								|| $_GET["order_status"] == "Preparing" 
								|| $_GET["order_status"] == "Shipped" 
								|| $_GET["order_status"] == "Delivered"
							) 
							&&
							(
								$orderStatus == "Booked" 
								|| $orderStatus == "Confirmed" 
								|| $orderStatus == "Preparing" 
								|| $orderStatus == "Shipped" 
								|| $orderStatus == "Delivered" 
							) 
							&&
							($_GET["order_status"] != "Total_Orders")
						)
						{
							if( ($orderStatus == "Shipped") && ($row['payment_method'] == 1 && $row['paid_status'] =='N') )
							{
								?>
								<input type="checkbox" name="checkbox[]" class="emp_checkbox" value="<?php echo $row['header_id']; ?>">
								<?php
							}
							else if( ($orderStatus == "Delivered") && ($row['payment_method'] == 1 && $row['paid_status'] == 'N') )
							{
								?>
								--
								<?php
							}
							else
							{
								?>
								<input type="checkbox" name="checkbox[]" class="emp_checkbox" value="<?php echo $row['header_id']; ?>">
								<?php 
							}
						}
						else
						{
							?>
							-
							<?php 
						}
					}
					else
					{
						if($orderStatus == "Booked" 
							|| $orderStatus == "Confirmed" 
							|| $orderStatus == "Preparing" 
							|| $orderStatus == "Shipped" 
							|| $orderStatus == "Delivered" 
						)
						{
							if( ($orderStatus == "Delivered") && ($row['payment_method'] == 1 && $row['paid_status'] == 'N') )
							{
								?>
								--
								<?php
							}
							else
							{
								if($orderStatus == "Booked")
								{
									?>
									<input type="checkbox" name="checkbox[]" class="emp_checkbox" value="<?php echo $row['header_id']; ?>">
									<?php 
								}
								else
								{
									?>
									--
									<?php
								}
							}
						}
						else
						{
							?>
							--
							<?php
						}
					}
				?>
			</td>
			
			<td class="tab-md-85 text-center">
				<a target="_blank" href="<?php echo base_url();?>orders/viewOderDetails/<?php echo $row['header_id'];?>" target="_blank" title="View Order"><i class="fa fa-eye"></i></a>
				&nbsp;| &nbsp;
				<a target="_blank"  href="<?php echo base_url();?>orders/printReceipt/<?php echo $row['header_id'];?>" target="_blank" title="Print Receipt">
					<i class="order_view_icon fa fa-print"></i>
				</a>
			</td>

			<td class="tab-md-120 text-center">
				<?php 
					if( $row['order_status'] == "Booked" )
					{
						?>
						<!-- <a href="<?php echo base_url();?>orders/openOrders/status/<?php echo $row['header_id'];?>/Confirmed" class="btn btn-outline-danger btn-block btn-sm">
							Confirm
						</a> -->

						<a onclick="updateConfirmStatus('<?php echo $row['header_id'];?>','Confirmed');" href="#" class="btn btn-outline-danger btn-block btn-sm">
							Confirm
						</a>
						
						<?php 
					} 
					else if( $row['order_status'] == "Confirmed" )
					{
						?>
						<a href="<?php echo base_url();?>orders/openOrders/status/<?php echo $row['header_id'];?>/Preparing" class="btn btn-outline-info btn-block btn-sm">
							Preparing
						</a>
						<?php 
					} 
					else if( $row['order_status'] == "Preparing" )
					{
						?>
						<a href="<?php echo base_url();?>orders/openOrders/status/<?php echo $row['header_id'];?>/Shipped" class="btn btn-outline-primary btn-block btn-sm">
							Shipping
						</a>
						<?php 
					} 
					else if($row['order_status'] == 'Shipped' )
					{
						/* if($row['payment_method'] == 1 && $row['paid_status'] =='N' )
						{
							?>
							<a title="Payment Pending" href="<?php echo base_url();?>orders/openOrders/paid_status/<?php echo $row['header_id'];?>" class="btn btn-warning btn-block btn-sm">
								Payment Pending
							</a>
							<?php
						}
						else
						{ */
							?>
							<a title="Deliver" href="<?php echo base_url();?>orders/openOrders/status/<?php echo $row['header_id'];?>/Delivered" class="btn btn-outline-warning btn-block btn-sm">
								Deliver 
							</a>
							<?php
						//}
						?>
						<?php 
					}
					else if($row['order_status'] == 'Delivered')
					{
						if($row['payment_method'] == 1 && $row['paid_status'] =='N' ) #Payment Method = 1 COD
						{
							?>
							<a title="Payment Pending" href="<?php echo base_url();?>orders/openOrders/paid_status/<?php echo $row['header_id'];?>" onclick="return confirm('Have you received the amount?');" class="btn btn-warning btn-block btn-sm">
								Payment Pending
							</a>
							<?php
						}
						else
						{
							?>
							<a title="Close" href="<?php echo base_url();?>orders/openOrders/status/<?php echo $row['header_id'];?>/Closed" class="btn btn-outline-success btn-block btn-sm">
								Close 
							</a>
							<?php 
						}
					}
				?>
			</td>

			<td class="tab-md-120 text-center">
				<span <?php if($row['order_status'] == 'Booked') {?>class="new-order-status blink_me"<?php } ?>>
					<?php echo $row['order_number'];?>
				</span>
			</td>

			<td class="tab-md-170 text-center">
				<?php #echo date(DATE_FORMAT,strtotime($row['ordered_date']));?> 
				<?php echo timeAgo(strtotime($row['ordered_date']));?> at
				<?php echo date($this->time,strtotime($row['ordered_date']));?>
			</td>

			<td class="tab-md-150">
				<?php echo ucfirst($row['customer_name']);?>
			</td>

			<td class="tab-md-120 text-center" >
				<?php echo $row['country_code'];?> - <?php echo $row['mobile_number'];?>
			</td>

			<td class="tab-md-120" >
				<?php echo $row['branch_name'];?>
			</td>
			
			<td class="tab-md-120 text-right">
				<?php #echo number_format($row['bill_amount'],DECIMAL_VALUE,'.','');?>
				<?php 
					$bill_amount = round($row['linetotal'] + $row['tax_value']);
					//echo $bill_amount;
					echo number_format($bill_amount,DECIMAL_VALUE,'.','');
				?>
			</td>
			
			<td class="tab-md-100 text-center">
				<?php 
					if($row['payment_method'] ==1)
					{
						echo '<span style="color:red; font-size:11px;">COD</span>';
					}
					else if($row['payment_method'] ==2)
					{
						echo '<span style="color:blue;font-size:11px;">Online Payment</span>';
					} 
					else if($row['payment_method'] ==3)
					{
						echo '<span style="color:blue;font-size:11px;">Wallet</span>';
					} 
				?>
			</td>
			
			
		</tr>

		<?php
		$i++;
	}
?>


<script type="text/javascript">  
	$('#select_all_1').on('click', function(e) 
	{
		if($(this).is(':checked',true)) 
		{
			$(".emp_checkbox_1").prop('checked', true);
		}
		else 
		{
			$(".emp_checkbox_1").prop('checked',false);
		}
		/* set all checked checkbox count
		$("#select_count").html($("input.emp_checkbox:checked").length+" Selected"); */
	});
	
	
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
		//alert("sd");
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
<script>
	function updateOrderStatus(header_id,status)
	{
		alert(header_id);
	}
</script>


