<table>
	<thead>
		<tr>
			<th style="text-align: center;font-size:18px;">PURCHASE ORDER</th>
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
			
			<td class="text-center" style="border-bottom:none ! important;border-top:none ! important;" width="50%">
				<span style="font-weight:bold">PO Date&nbsp;&nbsp;:</span> &nbsp;&nbsp;&nbsp;<?php echo date('d-M-Y',strtotime($edit_data[0]['po_date']));?>
			</td>
			<td style="border-bottom:none ! important;border-left:none ! important;"><span style="font-weight:bold">PO Status&nbsp;&nbsp;:</span> &nbsp;&nbsp;&nbsp;<?php echo $edit_data[0]['po_status'];?></td>
		</tr>
		<tr style="border-top:none !important;">
			
			<td style="border-top:none ! important;">
				<span style="font-weight:bold">PO No&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:</span> &nbsp;&nbsp;&nbsp;<?php echo $edit_data[0]['po_number'];?>
			</td>
			<td style="border-top:none ! important;"></td>
		</tr>
	</thead>
</table>			
		
