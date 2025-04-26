<table>
	<thead>
		<tr>
			<th style="text-align: center;font-size:18px;">RECEIPT</th>
		</tr>
		
	</thead>
</table>
<table class="table-class">
	<thead>
		<tr>
			<td class="text-center" width="50%">
				<img src="<?php echo base_url();?>uploads/logo.png" style="height:50px;width:40%;">
			</td>
			<td class="text-center" width="50%" style="border-left:none ! important">
				<p style="width:100%;font-size:14px;font-weight:600;text-align:center"><b style="text-align:center"><?php echo COMPANY_NAME;?></b></p>
				<span><?php echo ADDRESS1;?></span><br>
			</td>
		</tr>
		<tr>
			<td style="border-top:none ! important;vertical-align:top;border-bottom:none!important">
				<span style="font-weight:bold">Receipt No&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:</span> &nbsp;&nbsp;&nbsp;<?php echo isset($edit_data[0]['receipt_number']) ? $edit_data[0]['receipt_number']:NULL;?>
			</td>
			<td class="text-center" style="border-bottom:none!important" width="50%">
				<span style="font-weight:bold">Bill No&nbsp;&nbsp;:</span> &nbsp;&nbsp;&nbsp;<?php echo isset($edit_data[0]['bill_number']) ? $edit_data[0]['bill_number']:NULL;?><br><br>
			</td>
		</tr>
		<tr>
			<td style="border-top:none ! important;vertical-align:top;">
				<span style="font-weight:bold">Receipt Date&nbsp;&nbsp;:</span> &nbsp;&nbsp;&nbsp;<?php echo date('d-M-Y',(isset($edit_data[0]['receipt_date']) ? strtotime($edit_data[0]['receipt_date']):NULL));?><br><br>
			</td>
			<td class="text-center" style="border-top:none!important" width="50%">
			</td>
		</tr>
	</thead>
</table>			
		
