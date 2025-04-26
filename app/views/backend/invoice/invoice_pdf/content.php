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
	table.table-products tbody tr td.hjghjghj
	{
		height:200px!important;
	}
	
	/* table.table-class123123 tr td:nth-child(2) {
		height: 200px;
	} */

	tr.table-class123
	{
		padding:0px!important;
		margin:0px!important;

	}
	.table-class td, .table-class th {
		border: 1px solid #000;
		padding: 6px;
		font-size: 11px;
		font-weight: 400;
		
	}
	table.table-products
	{
		border-collapse: collapse !important;
		border-bottom:none !important;
	}
	
	table.table-products thead tr th, td
	{
		border:1px solid #000;
		border-top:none;
		border-bottom:none;
		padding:0px!important;
		border-collapse: collapse !important;
		margin:0px!important;
		padding:5px;
		font-weight: 400;
		font-size: 11px;
	}
	.table-class tr:nth-child(even){background-color: #fff;}
	
	.table-class tr:hover {background-color: #000;}
	
	.table-class th {
		padding-top: 6px;
		padding-bottom: 6px;
		text-align: left;
		background-color: #fff;
		color: #000;
		text-align: center;
	}
	tr.th-background th{background:#000;border:none;font-size:11px;}
	tbody.fields-td-new tr td{font-size:11px;}
</style>

<!-- Page header start-->
<div class="page-header page-header-light">
	<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
		<div class="d-flex">
			
		</div>
	</div>
</div>
<!-- Page header end-->

<?php 
	$invoiceType = isset($edit_data[0]["invoice_type"]) ? $edit_data[0]["invoice_type"] : NULL;
	if($invoiceType == "WITH-GST")
	{
		$headerTitle = "TAX INVOICE";
	}
	else if($invoiceType == "WITH-OUT-GST")
	{
		$headerTitle = "INVOICE";
	}
?>

<div class="content"><!-- Content start-->
	<div class="card"><!-- Card start-->
		<div class="card-body">
			<table class="table-class" width="100%" style="border-collapse: collapse !important;border:none!important;">
				<thead width="100%" class="text-center" style="text-align:center;width:100%;">
					<!-- <b style="margin-left:10px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $headerTitle;?></b> -->
					<b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $headerTitle;?><b>
				</thead>
				<tbody width="100%">
					<tr  width="100%" style="border:none!important;">
						<td colspan="10" style="border:none!important;text-align:center;border-right:none !important;font-size:18px;">
						  <b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $headerTitle;?></b>

						</td>
						<td colspan="8" style="border:none!important;text-align:right;font-size:12px;">
							<span style="text-align:right;"></span>
						</td>
					</tr>
					<tr rowspan="2" width="100%" style="border:1px solid #000!important; text-align:center !important;">
						<td class="text-center"style="border:none!important;text-align:center!important;"    width="100%" height="30%" colspan="14">
							<b style="font-size:35px;"><?php echo COMPANY_NAME;?></b><br>
						</td>		
					</tr>
					<tr width="100%" rowspan="5" style="border:1px solid #000!important;">
					    <td colspan="2"  style="border-right:none !important;"></td>
						<td class="text-center" style="border-right:none !important;border-left:none !important; text-align:center;"  colspan="8" >
							<span style="font-size:18px;margin-top:5px;">
								<?php echo ADDRESS1 ?>.
								State:<?php echo STATE_NUMBER; ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
								MAILID:<?php echo CONTACT_EMAIL?><br>
								CIN:<?php echo CIN; ?><br>
								GSTIN:<?php echo GST_NUMBER;?>&nbsp;&nbsp;		
								PH NO:<?php echo PHONE1;?>
							</span>
						</td>

						<td colspan="5" style="border-left:none !important;">
						   <img src="<?php echo base_url(); ?>uploads/no-image-mobile.png" width="90" height="100" alt="No Image">
						</td>
					</tr>
					<tr width="100%" rowspan="6" style="border:1px solid #000!important;padding:0px;">
					    <td colspan="6" f>	
							<table  width="100%" style="margin:0px; padding:0px;">
							   
								<tr width="100%"style="margin:0px;">
									<td width="50%" style="padding:0px 0px 3px 0px;border:0px; font-size:12px;"><b> BILL TO </b></td>
								</tr>
								
								<tr width="100%"style="margin:0px;">
								    <td width="50%" style="padding:0px 0px 3px 0px;border:0px;font-size:11px;"> <b><?php echo isset($edit_data[0]['customer_name']) ? ucfirst($edit_data[0]['customer_name'])	:""?></b></td>
									
								</tr>
								<tr width="100%"style="margin:0px;">>
									<td width="50%" style="padding:0px 0px 3px 0px;border:0px;font-size:11px;">
										<b><?php 
											if($edit_data[0]['address1'])
											{?>
												<?php
												echo isset($edit_data[0]['address1']) ? ucfirst($edit_data[0]['address1']):"";
											}
										?></b>
								    </td>
								</tr>
								<tr width="100%"style="margin:0px;">
									<td width="50%" style="padding:0px 0px 3px 0px;border:0px;font-size:11px;"><b>GSTIN :</b>
								     	<b><?php echo GST_NUMBER;?></td></b>
								</tr>
								
							
							</table> 
						</td>
						<td colspan="9">

						    <table  width="100%" style="margin:0px;">
							<tr width="100%"style="margin:0px;">
								<tr width="100%"style="margin:0px;">
									<td width="50%" style="padding:0px 0px 3px 0px;border:0px;"><b>INVOICE  DATE </b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>:</b></td>
									<td width="50%" style="padding:0px 0px 3px 0px;border:0px;">
									 <b><?php echo isset($edit_data[0]['invoice_date']) ? date(DATE_FORMAT,strtotime($edit_data[0]['invoice_date'])):"";?></b>
								    </td>
								</tr>
								<tr width="100%"style="margin:0px;">
									<td width="50%" style="padding:0px 0px 3px 0px;border:0px;"> <b>INVOICE  NO </b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>:</b></td>
									<td width="50%" style="padding:0px 0px 3px 0px;border:0px;">
									  <b> <?php echo isset($edit_data[0]['invoice_number']) ? $edit_data[0]['invoice_number']:"";?></b>
								    </td>
								</tr>
								<tr width="100%"style="margin:0px;">
									<td width="50%" style="padding:0px 0px 3px 0px;border:0px;"> <b>PO ORDER DATE </b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>:</b></td>
									<td width="50%" style="padding:0px 0px 3px 0px;border:0px;">
									<b>
										<?php 
											if(isset($edit_data[0]['po_date']) && date(DATE_FORMAT, strtotime($edit_data[0]['po_date'])) != NULL)
											{
												?>
												<?php echo isset($edit_data[0]['po_date']) ? date(DATE_FORMAT, strtotime($edit_data[0]['po_date'])) : "";?>
												<?php 
											} 
										?>
									</b>

								     </td>
								</tr>
								<tr width="100%"style="margin:0px;">
									<td width="50%" style="padding:0px 0px 3px 0px;border:0px;"> <b> PO ORDER NO </b>  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>: </b></td>
									<td width="50%" style="padding:0px 0px 3px 0px;border:0px;">
									   <b> <?php echo isset($edit_data[0]['po_number']) ? $edit_data[0]['po_number']:"";?></b>	
								     </td>
								</tr>
							</table> 
						</td>
					</tr>	
				</tbody>
			</table>
			<?php $taxval = isset($line_data[0]["tax"]) ? $line_data[0]["tax"]:NULL;?>						
			<!-- line data start here -->
			<div class='new-scroller'>
				<div style="margin-top:0px;">
					<table class="table-products table-bordered table-hover" width="100%" style="font-size:12px;">
						<thead style="border-bottom:1px solid #000;">
							<tr style="border-top:none !important;border-bottom:1px solid #000;">
								<th style="border-right:none !important;border-top:none !important;border-bottom:1px solid #000;font-size:12px;">
								<b>No</b> 
								</th>
								<th style="text-align:left;border-right:none !important;border-top:none !important;border-bottom:1px solid #000;font-size:12px;">
									<b>DESCRIPTION</b>
								</th>
								<?php if($invoiceType == "WITH-GST"){ ?>
								<th style="border-right:none !important;text-align:left;border-top:none !important;border-bottom:1px solid #000;font-size:12px;">
									<b>HSN </b>
								</th>
								<?php } ?>

								<th style="border-right:none !important;text-align:center;border-top:none !important;border-bottom:1px solid #000;font-size:12px;">
									<b>QTY</b>
								</th>
								<th style="border-right:none !important;border-top:none !important;text-align:right;border-bottom:1px solid #000;font-size:12px;">
									<b>RATE</b>
								</th>
								<th style="border-top:none !important;text-align:right;border-bottom:1px solid #000;font-size:12px;">
								  <b>AMOUNT</b>
								</th>                      
							</tr>
						</thead>
						<tbody style="height:600px !important;">
							<?php
								$i=1;
								$tot = $totalQty = 0;
								$total = 0;
								$total_tax = 0;
								foreach ($line_data as $row) 
								{
									?> 
									<tr>
										<td style="text-align:center;"><?php echo $i;?></td>
										<td><?php echo $row["item_description"];?></td>
										<?php if($invoiceType == "WITH-GST"){ ?>
										<td><?php echo $row["hsn"];?></td>
										<?php } ?>
										<td style="text-align:center;">
											<?php echo $row["quantity"];?>
										</td>
										<td style="text-align:right;">
											<?php echo number_format($row["base_price"],DECIMAL_VALUE,'.','');?>
										</td>
										<td style="text-align:right;">
											<?php 
												if($row["total"] != NULL)
												{
													echo number_format($row["line_value"],DECIMAL_VALUE,'.','');
												}
											?>
										</td>
									</tr>
									<?php 
									$i++;
									$totalQty += $row["quantity"];
									$total += $row["line_value"];
									$total_tax += $row["total_tax"];

								}
								$netAmount = round($total);
								$actualValue = $total;
								$roundOff = $netAmount - $actualValue;

								$tax_value1 = $taxval / 100 * $total; 
								$grandTotalvalue = $tax_value1 + $total; 

								if($invoiceType == "WITH-GST")
								{
									
									
									//$taxable_value = $total + $total_tax; 

									$state_number = isset($edit_data[0]["state_number"]) ? $edit_data[0]["state_number"] : NULL;
									$sgst = $cgst = $tax_value1 / 2;
									?>
									<tr style="border-top:none!important;border-bottom:none!important;">
										<td colspan="" style="border-top:none!important;border-bottom:none!important;border:1px solid #000;height:200%!important;">
										</td>
										<td colspan="" width="199px" height="280px" style="border-top:none!important;border-bottom:none!important;vertical-align:baseline;border:1px solid #000;height:200%!important;text-align:right;">
										</td>	
										<td style="border-top:none!important;border-bottom:none!important;border:1px solid #000;height:200%!important;">
										</td>
										<td style="border-top:none!important;border-bottom:none!important;border:1px solid #000;height:200%!important;">
										</td>
										<td style="border-top:none!important;border-bottom:none!important;border:1px solid #000;height:200%!important;">
										</td>
										<td style="border-top:none!important;border-bottom:none!important;vertical-align:baseline;border:1px solid #000;height:200%!important;text-align:right;">
											
										</td>
									</tr> 
									<?php 
								}
								else
								{
									?> 
									<tr style="border-top:none!important;border-bottom:none!important; height:600px !important;" >
										<td colspan="" style="border-top:none!important;border-bottom:none!important;border:1px solid #000;height:200%!important;">
										</td>
										<td colspan="" width="199px" height="280px" style="border-top:none!important;border-bottom:none!important;vertical-align:baseline;border:1px solid #000;height:200%!important;text-align:right;">
											<br><br>
											<br><br>
											<br><br>
											<br><br>
										</td>
										<?php if($invoiceType == "WITH-GST"){ ?>
										<td style="border-top:none!important;border-bottom:none!important;border:1px solid #000;height:200%!important;">	
										</td>
										<?php } ?>
										
										<td style="border-top:none!important;border-bottom:none!important;border:1px solid #000;height:200%!important;">
										</td>
										<td style="border-top:none!important;border-bottom:none!important;border:1px solid #000;height:200%!important;">
										</td>
										<?php if($invoiceType == "WITH-GST"){ ?>
										<td style="border-top:none!important;border-bottom:none!important;border:1px solid #000;height:200%!important;">
											
										</td>
										<td style="border-top:none!important;border-bottom:none!important;border:1px solid #000;height:200%!important;">
											
										</td>
										<?php } ?>

										<td style="border-top:none!important;border-bottom:none!important;vertical-align:baseline;border:1px solid #000;height:200%!important;text-align:right;">
											
										</td>
									</tr> 
									<?php
								} 
							?>
						</tbody>
					</table>

					<table width="100%" height="100%" class="table tab-tot table-total-sale --table-striped table-bordered table-condensed table-hover" style="font-size:12px;border-top:none!important;border-bottom:none!important;">
						<tbody>
							<tr width="100%" style="border-bottom:none!important;border-top:1px solid #000;border-bottom:none !important;">
								<td  width="50%" colspan="1">
									<p style="padding-top:0px;margin:10px;border :1px solid #000;font-size:14px;width:50px;text-align:left;">
										<b style="font-size:16px;">Rupees in Words </b> <br><br>
									</p>
									<b style="font-size:18px;display:inline-block;width:50px;">
										<?php
											echo amountInWords($grandTotalvalue);
										?>
									</b>
								</td>
								<?php 
									if ($state_number == STATE_NUMBER) {
										?>
										<td width="25%" style="padding:20px 0px 20px 10px;border-right:1px;"colspan="1">
											<span style="font-size:14px;display:inline-block;width:80px;">
												<b>TOTAL AMOUNT</b>
											</span><br><br>
											<span style="font-size:13px;display:inline-block;width:80px; padding:10px 0px 2px 10px;">
												<b>CGST @</b>
											</span><br><br>
											<span style="font-size:13px;display:inline-block;width:80px; padding:10px 0px 2px 10px;">
												<b>SGST  @</b>
											</span><br><br>
											<span style="font-size:13px;display:inline-block;width:80px; padding:10px 0px 2px 10px;">
												<b>IGST&nbsp;&nbsp;@</b>
											</span><br><br>
											<span style="font-size:14px;display:inline-block;width:80px; padding:10px 0px 2px 10px;">
												<b>GRAND TOTAL</b>
											</span><br>
											
										</td>
									
										<td width="25%" style="padding:10px 0px 26px 110px;border-left:none; text-align:center;" colspan="1">
											<span style="font-size:10px;display:inline-block;width:80px;">
												
											</span><br>
											<span style="font-size:13px;display:inline-block;width:100px;">
												<b ><?php echo number_format($cgst, DECIMAL_VALUE, '.', ''); ?></b>
											</span><br><br>
											<span style="font-size:13px;display:inline-block;width:100px; padding:0px 0px 12px 10px;">
												<b><?php echo number_format($sgst, DECIMAL_VALUE, '.', ''); ?></b>
											</span><br><br><br>
											<span style="font-size:10px;display:inline-block;width:80px; padding:100px 0px 2px 10px;">
												
											</span><br>
										</td>
										<td width="12%" style="padding:0px 0px 0px 150px;border-left:1px solid #000; text-align:center">
											<span style="font-size:14px;display:inline-block;width:100px;">
								         	<b><?php echo number_format($total, DECIMAL_VALUE, '.', ''); ?></b>
											</span>
											<span style="font-size:13px;display:inline-block;width:100px;">
												
											</span><br><br>
											<span style="font-size:13px;display:inline-block;width:100px;">
												<b><?php echo number_format($cgst, DECIMAL_VALUE, '.', ''); ?></b>
											</span><br><br>
											<span style="font-size:13px;display:inline-block;width:100px;">
												<b><?php echo number_format($sgst, DECIMAL_VALUE, '.', ''); ?></b>
											</span><br><br><br><br>
											<span style="font-size:14px;display:inline-block;width:100px;">
											<b><?php echo number_format($grandTotalvalue, DECIMAL_VALUE, '.', ''); ?></b>
											</span><br><br>
										</td>
										<?php
									} 
									else 
									{
										?>
										<td width="23%" style="padding:20px 0px 20px 10px;border:1px;">
											
											<span style="font-size:14px;display:inline-block;width:80px; padding:100px 0px 2px 10px;">
												<b>TOTAL AMOUNT</b>
											</span><br>
											
										</td>
									
										<td width="25%" style="padding:10px 0px 20px 10px;border:1px;">
											<span style="font-size:10px;display:inline-block;width:80px;">
												
											</span><br>
										</td>
										<td width="26%" style="padding:0px 0px 0px 150px; text-align:center">
											<span style="font-size:14px;display:inline-block;width:100px;">
								         		<b><?php echo number_format($total, DECIMAL_VALUE, '.', ''); ?></b>
											</span>
											<br><br>
										</td>
										<?php
									}
								?>

							</tr>		
						</tbody>
					</table>
					<table style="border:1px solid #000!important;text-align:right;border-collapse:collapse" width="100%">
						<tbody style="border:1px solid #000!important;">
							<tr width="100%" style="border:1px solid #000!important;">
								<td rowspan="2" width="50%" style="font-size:11.5px;text-align:left;border-right:1px solid #000!important;border-bottom:1px solid #000!important;border-left:1px solid #000!important;vertical-align: baseline;">
									<span><b>Declaration</b> : We declare that this Invoice shows the actual cost of the services
described and that all particulars are true and correct.</span><br><br>
									<span><b>Company's Bank Account Details</b></span><br>
									<span><span><?php echo COMPANY_ACCOUNT;?></span><br><br>
								</td>
							</tr>
							<tr width="100%" >
							    <td rowspan="1" width="50%" style="font-size:11.5px;text-align:center;border-right:1px solid #000!important;border-bottom:1px solid #000!important;border-left:1px solid #000!important;vertical-align: baseline;">
								 	<span>
										<img src="<?php echo base_url();?>uploads/qr_code.png" style='width:150px;height:150px;text-align:center;'>
									</span>
								</td>
							</tr>
							<tr width="100%" style="border:1px solid #000!important;">
								<td  width="50%"  style="font-size:11.5px;text-align:left;border-left:1px solid #000!important;border-bottom:1px solid #000!important;border-right:none!important;vertical-align: baseline;">
									<br><br><br><br><br><br><br>
									<span>Receiver Signature</span>
								</td>
								<td  width="50%"  style="font-size:11.5px;text-align:right;border-right:1px solid #000!important;border-bottom:1px solid #000!important;border-left:none !important;">
									<span> <b> FOR <?php echo COMPANY_NAME;?></b></span><br><br><br><br><br><br><br>
									<span>Authorised Signature</span>
								</td>
							</tr>
						</tbody>
					</table>
					<br>
					<!-- <span style='margin:15px 0px;float:left;width:100%;padding-top:10px;'> <b>Amount In Words :</b> Rupees <?php //echo amountInWords($row['total']);?></span> -->
					<br>
				</div>
			</div>
			<!-- line data end here -->
		</div>
	</div><!-- Card end-->
	
</div><!-- Content end-->


