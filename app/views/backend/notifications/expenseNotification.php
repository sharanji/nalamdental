<?php 
	if($this->user_id == 1)
	{
		$condition = "1=1 
			and org_approval_status.approval_type='EXP' 
			and header_tbl.expense_status='Pending Approval'
			and org_approval_status.approval_status='Pending'
			";
	}
	else
	{
		$condition = "org_approval_status.user_id = '".$this->user_id."' 
			and org_approval_status.approval_type='EXP'
			and header_tbl.expense_status='Pending Approval'
			and org_approval_status.approval_status='Pending'
		";
	}

	$headerQry = "select 
		header_tbl.header_id,
		header_tbl.expense_number,
		header_tbl.expense_date,
		header_tbl.expense_status,
		sum(line_tbl.expense_cost) as total_expense
		from org_approval_status

		left join expense_header as header_tbl on header_tbl.header_id = org_approval_status.reference_id

		left join expense_line as line_tbl on 
			line_tbl.header_id = header_tbl.header_id

		where 1=1
		and $condition
		group by header_tbl.header_id
		order by header_tbl.header_id asc";
 
	$getExpenseApproval = $this->db->query($headerQry)->result_array();
	
    if (count($getExpenseApproval) > 0) 
	{
        foreach ($getExpenseApproval as $row) 
		{
			?>
			<li class="media">			
				<div class="media-body">
					<?php if($this->user_id == 1 ){?>
						<a href="javascript:void(0);">
					<?php }else{ ?> 
						<a href="<?php echo base_url();?>expense/viewApprovals/<?php echo $row["header_id"];?>">
					<?php } ?>	

					<div class="media-title">
						<span class="font-weight-semibold">
							<?php echo $row["expense_number"];?>
						</span>
						<span class="text-muted font-size-sm float-right">
							<?php echo date(DATE_FORMAT,strtotime($row["expense_date"]));?>
						</span>
						<br>
						<span class="font-weight-semibold float-right" --style="width:90px;">
							<p style="color:#ff6420 !important;font-size:13px;"><?php echo $row["expense_status"];?></p>
						</span>
						
						<span class="text-muted">
							<?php echo CURRENCY_SYMBOL." ".number_format($row['total_expense'],DECIMAL_VALUE,'.','');?>
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