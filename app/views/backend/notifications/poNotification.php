<?php 
	if($this->user_id == 1)
	{
		$condition  = "1=1 
			and org_approval_status.approval_type='PO' 
			and po_headers.po_status='Pending Approval'
			and org_approval_status.approval_status='Pending'
			";
	}
	else
	{
		$condition  = "org_approval_status.user_id = '".$this->user_id."' 
			and org_approval_status.approval_type='PO'
			and po_headers.po_status='Pending Approval'
			and org_approval_status.approval_status='Pending'
		";
	}

	$query = "select 
		po_headers.*,
		sup_suppliers.supplier_name,
		geo_currencies.currency,
		sum(po_lines.total) amount,
		sup_supplier_sites.site_name
		from org_approval_status
		
		left join po_headers on po_headers.po_header_id = org_approval_status.reference_id

		left join sup_suppliers on 
		sup_suppliers.supplier_id = po_headers.supplier_id

		left join sup_supplier_sites on 
		sup_supplier_sites.supplier_site_id = po_headers.supplier_site_id

		left join geo_currencies on 
			geo_currencies.currency_id = po_headers.po_currency
		
		left join po_lines on 
			po_lines.po_header_id = po_headers.po_header_id

		where 1=1
		and $condition
		group by po_headers.po_header_id
			order by po_headers.po_header_id asc";
	
	$getPO = $this->db->query($query)->result_array();

    if (count($getPO) > 0) 
	{
        foreach ($getPO as $row) 
		{
			?>
			<li class="media">			
				<div class="media-body">
					<?php if($this->user_id == 1 ){?>
						<a href="javascript:void(0);">
					<?php }else{ ?> 
						<a href="<?php echo base_url();?>purchase_order/viewApprovals/<?php echo $row["po_header_id"];?>">
					<?php } ?>	

						<div class="media-title">
							<span class="font-weight-semibold">
								<?php echo $row["po_number"];?>
							</span>
							<span class="text-muted font-size-sm float-right">
								<?php echo date(DATE_FORMAT,strtotime($row["po_date"]));?>
							</span>
							<br>
							<span class="font-weight-semibold float-right" --style="width:90px;">
								<p style="color:#ff6420 !important;font-size:13px;"><?php echo $row["po_status"];?></p>
							</span>
							
							<span class="text-muted">
								<?php echo CURRENCY_SYMBOL." ".number_format($row['amount'],DECIMAL_VALUE,'.','');?>
							</span>
						</div>
						
					</a>
				</div>
			</li>
			<?php
        }
    }
	else
	{
		?>
		<img class="notify-no-data text-center" style="margin:0px 0px 10px 60px;" src="<?php echo base_url();?>uploads/nodata.png">
		<?php
	} 
?>