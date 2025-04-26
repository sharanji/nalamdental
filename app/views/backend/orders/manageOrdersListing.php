<?php 
	$i=0;
	$firstItem = isset($first_item) ? $first_item : 0;
	$totalgrandTotal=0;
	foreach($resultData as $row)
	{
		$orderStatus = $row['order_status'];
		?>
		<tr>
			<td class="tab-md-100 text-center">
				<a target="_blank" href="<?php echo base_url();?>orders/viewOderDetails/<?php echo $row['header_id'];?>" title="View Order"><i class="fa fa-eye"></i></a>
				&nbsp;|&nbsp;
				<a target="_blank" href="<?php echo base_url();?>orders/printReceipt/<?php echo $row['header_id'];?>" title="Print Receipt">
					<i class="order_view_icon fa fa-print"></i>
				</a>
				&nbsp;|&nbsp;
				<a target="_blank" href="<?php echo base_url();?>orders/kotPrint/<?php echo $row['header_id'];?>" title="KOT Print">
					KOT
				</a>
			</td>

			<td class="tab-md-120 text-center">
				<?php echo $row['order_number'];?>
			</td>

			<td class="tab-md-170 text-center">
				<?php echo date(DATE_FORMAT." ".$this->time,strtotime($row['ordered_date']));?>
			</td>
			<td class="tab-md-120 text-center">
				<?php 
					if($row['cancel_status'] == 'Y')
					{
						?>
						<span class="text-warning"><?php echo $row['order_status']; ?></span>
						<?php
					}
					else
					{
						echo $row['order_status'];
					}
				?>
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
				<?php #echo $row['cancel_status'];?>
				<?php
					if($row['cancel_status'] == 'Y')
					{
						$bill_amount = 0;
					}
					else
					{
						#$totalTax += $lineItems['tax_value'];
						$bill_amount = round($row['linetotal'] + $row['tax_value']);
					}
				 
					#$bill_amount = round($row['linetotal'] + $row['tax_value']);
					echo number_format($bill_amount,DECIMAL_VALUE,'.','');
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