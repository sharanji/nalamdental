<style>
	table tr, td{font-family: 'Helvetica',sans-serif !important;}
	.table-class {font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;border-collapse: collapse;width: 100%;font-size: 11px;}
	.table-total-sale{border-collapse: collapse;}
	.table-class td, .table-class th {border: 1px solid #000;padding: 6px;font-size: 11px;font-weight: 400;}
	table.pur-headers-tbl tbody tr td{border:none;}
	table.pur-headers-tbl{padding:0px;margin:0px;border:1px solid #000;width:100%;}
	table.table-products thead tr th, td{border:1px solid #000;padding:5px 10px!important;border-collapse: collapse !important;margin:0px!important;padding:5px;}
	table.table-products{border-collapse: collapse !important;}
	table.table-products thead tr th, td{border:1px solid #000;padding:0px!important;border-collapse: collapse !important;margin:0px!important;padding:5px;}
	.table-class tr:nth-child(even){background-color: #fff;}
	.table-class tr:hover {background-color: #ddd;}
	tr.th-background th{background:#ddd;border:1px solid #b7b7b7;font-size:11px;}
	tbody.fields-td-new tr td{font-size:11px;}
</style>

<table class="table-class">
	<thead>
		<tr>
			<td class="text-center" width="50%">
				<img src="<?php echo base_url();?>uploads/logo.png" style="height:55px;width:260px;">
			</td>
			<td class="text-center" width="50%">
				<p style="width:100%;font-size:18px;font-weight:600;text-align:center"><b style="text-align:center"><?php echo COMPANY_NAME;?></b></p>
				<span><?php echo ADDRESS1;?></span><br>
				<span>Mobile : <?php echo PHONE1;?></span><br>
				<span>Email  : <?php echo CONTACT_EMAIL;?></span>
			</td>
		</tr>
	</thead>
</table>

<div style="width:100%" style="margin-top:10px;">				
	<div class='new-scroller'>
		<table class="table-class">
			<thead>
				<tr style="border:none!">
					<td width="100%" style="text-align: center;font-size:14px;font-weight:bold;">
						Sales Summary Report
					</td>
				</tr>
			</thead>
		</table>
	</div>	
</div>	

<div style="width:100%" style="margin-top:10px;">				
	<div class='new-scroller'>
		<table class="table-class">
			<thead>
				<tr>
					<td class="text-center" width="50%">
						<span><b>From Date : </b><?php echo $_GET['from_date'];?></span> <span><b>To Date  : </b><?php echo $_GET['to_date'];?></span>
					</td>
					<td style="text-align: right;" width="50%">
						Currency : <?php echo CURRENCY_CODE;?>
					</td>
				</tr>
			</thead>
		</table>
	</div>	
</div>	
		
<div style="width:100%" style="margin-top:10px;">
	<div class='new-scroller'>
		<table class="table table-bordered table-products table-hover" width="100%" style="font-size:12px;color:#000;">
			<thead>
				<tr>
					<?php if($this->user_id ==1){?>
						<th style="text-align: left;font-size:10px;">Branch Name</th>
					<?php } ?>
					<th class="text-right" style="text-align: right; font-size:10px;">Total Order Amount</th>
					<th class="text-right" style="text-align: right; font-size:10px;">Total Cancelled Amount</th>
				</tr>
			</thead>
			<tbody>
				<?php
					if (count($resultData) > 0) 
					{	
						$Total_Order_Amount = isset($cardResult[0]["Total_Order_Amount"]) ? $cardResult[0]["Total_Order_Amount"] : 0;
						$Total_Cancelled_Amount = isset($cardResult[0]["Total_Cancelled_Amount"]) ? $cardResult[0]["Total_Cancelled_Amount"] : 0;
							
						foreach($resultData as $row)
						{
							?>
							<tr>
								<?php if($this->user_id ==1){?>
									<td class="tab-medium-width">
										<?php echo ucfirst($row['branch_name']);?>
									</td>
								<?php } ?>

								<td class="tab-medium-width text-right" style="text-align: right;">
									<?php echo number_format($row['Total_Order_Amount'],DECIMAL_VALUE,'.',''); ?>
								</td>
								
								<td class="tab-medium-width text-right" style="text-align: right;">
									<?php echo number_format($row['Total_Cancelled_Amount'],DECIMAL_VALUE,'.',''); ?>
								</td>	
							</tr>
							<?php
						}
						?>
						<tr>
							<td style="text-align:right;font-weight:bold;"> Total </td>
							<td style="text-align:right;font-weight:bold;"><?php echo number_format($Total_Order_Amount,DECIMAL_VALUE,'.',''); ?></td>
							<td style="text-align:right;font-weight:bold;"><?php echo number_format($Total_Cancelled_Amount,DECIMAL_VALUE,'.',''); ?></td>
						</tr>
						<?php
					}
				?>
			</tbody>
		</table>								
	</div>			
</div>
	