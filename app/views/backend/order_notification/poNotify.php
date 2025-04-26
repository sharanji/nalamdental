<?php 
	$orderQuery = "select
	header_tbl.header_id,
	header_tbl.order_number,
	header_tbl.ordered_date,
	header_tbl.order_status,
	header_tbl.payment_method,
	branch.branch_name,
	payment_type.payment_type,
	customer.customer_name,
	customer.mobile_number,
	country.country_code,
	customer_address.address_name,
	customer_address.address1,
	customer_address.address2,
	customer_address.address3,
	customer_address.land_mark,
	customer_address.address_type,
	customer_address.postal_code,
	line_tbl.cancel_status,
	sum(line_tbl.price) as price,
	sum(line_tbl.price * line_tbl.quantity) as bill_amount,

	round( sum((line_tbl.offer_percentage / 100) * (line_tbl.quantity * line_tbl.price)),2) as offer_amount,
	round( sum((line_tbl.quantity * line_tbl.price) - ((line_tbl.offer_percentage / 100) * (line_tbl.quantity * line_tbl.price))),2) as linetotal,
	round( sum( ((line_tbl.quantity * line_tbl.price) - ((line_tbl.offer_percentage / 100) * (line_tbl.quantity * line_tbl.price))) * (tax_percentage/100)),2) as tax_value


	from ord_order_headers as header_tbl

	left join ord_order_lines as line_tbl on line_tbl.header_id = header_tbl.header_id
	left join cus_customers as customer on customer.customer_id = header_tbl.customer_id
	left join pay_payment_types as payment_type on payment_type.payment_type_id = header_tbl.payment_method
	left join branch on branch.branch_id = header_tbl.branch_id

	left join cus_customer_address as customer_address on
	customer_address.customer_address_id = header_tbl.address_id

	left join geo_countries as country on
	country.country_id = customer.country_id

	WHERE 1=1 
	and header_tbl.order_status='Booked'
	and header_tbl.notification_read_status='N' 

	and (line_tbl.cancel_status = 'N' or header_tbl.cancel_status = 'Y')
	group by line_tbl.header_id,line_tbl.cancel_status
	order by header_tbl.header_id desc
				
	limit 10
	";

	$orderNotification = $this->db->query($orderQuery)->result_array();
	
	foreach ($orderNotification as $row) 
	{
		?>
		<li class="media">
			<div class="media-body">
					<div class="media-title">
						<span class="font-weight-semibold" style="color:Blue;"><a href="<?php echo base_url();?>orders/<?php echo $row['header_id'];?>"><?php echo $row["order_number"];?></a></span>
						<span class="text-muted font-size-sm float-right">
							<?php echo date(DATE_FORMAT." ".$this->time,strtotime($row["ordered_date"]));?>
						</span>
						<br>
						<span class="font-weight-semibold float-right" --style="width:90px;">
							<?php //echo $row["header_id"];?>
						</span>
						
						<span class="text-muted">
							<?php
								$bill_amount = round($row['linetotal'] + $row['tax_value']);
								echo number_format($bill_amount,DECIMAL_VALUE,'.','');
							?>
						</span>
					</div>	
				</a>
			</div>
		</li>
		<?php
	}
?>
	

	
	 
