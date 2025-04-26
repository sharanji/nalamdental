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
		border: 1px solid #000 !important;
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
		border:1px solid #000 !important;
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
	tr.th-background th{background:#000;border:none !important;font-size:11px;}
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

<div class="content"><!-- Content start-->
	<div class="card"><!-- Card start-->
		<div class="card-body">
			<?php 
				#Project Details
				$sql = "select 
				header_tbl.*,
				supplier.supplier_name,
				
				expense_payment_type.payment_type
				from inv_supplier_payment_header as header_tbl

				left join inv_supplier_payment_line as line_tbl on
				line_tbl.header_id = header_tbl.header_id
					
				left join sup_suppliers as supplier on supplier.supplier_id = header_tbl.supplier_id

				left join expense_payment_type 
					on expense_payment_type.payment_type_id = header_tbl.payment_method

				where  1=1
				and header_tbl.header_id='".$id."'	

		";	
		$getInvoiceDetails = $this->db->query($sql,array($id))->result_array();

		$query = "SELECT
					line_tbl.*,
					po_headers.po_number,
					rcv_header.receipt_number,
					line_tbl.payment_amount,
					COALESCE(SUM(rcv_line.received_qty * po_lines.base_price), 0) as total
				FROM
					inv_supplier_payment_line as line_tbl
				LEFT JOIN
					rcv_receipt_headers as rcv_header on rcv_header.receipt_header_id = line_tbl.receipt_id
				LEFT JOIN
					rcv_receipt_lines as rcv_line on rcv_line.receipt_header_id = rcv_header.receipt_header_id
				LEFT JOIN
					po_lines on po_lines.po_line_id = rcv_line.po_line_id
				LEFT JOIN
					po_headers on po_headers.po_header_id = po_lines.po_header_id
				
				WHERE
					1=1
					AND line_tbl.header_id = '".$id."'
				GROUP BY
					line_tbl.receipt_id, rcv_header.receipt_number";



		$getInvoiceItems = $this->db->query($query)->result();

		?>
			
			<table class="table-class">
				<tbody width="100%">
					<tr  width="100%" style="">
						<td colspan="3" style="border:none!important;text-align:center;border-right:none !important;font-size:16px;margin-left:30px;">
							<b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;SUPPLIER PAYMENT<b>
						</td>
					</tr>
					<tr rowspan="2" width="100%" style="border:1px solid #000!important;">
						<td class="text-left" width="50%" colspan="2"  rowspan="2" style="font-size:13.5px;vertical-align: baseline;">
							<b style="font-size:16px;vertical-align: baseline;"><?php echo COMPANY_NAME;?></b><br>
							<?php echo nl2br(ADDRESS1);?><br>
							GST IN : <?php echo GST_NUMBER;?><br>

							Mob &nbsp;&nbsp;&nbsp;&nbsp;: <?php echo PHONE1;?><br>
							Email &nbsp;: <?php echo CONTACT_EMAIL;?>
						</td>
						<td style="border: none !important;font-size:13.5px;">Payment No<br><br><b><?php echo isset($getInvoiceDetails[0]['payment_number']) ? $getInvoiceDetails[0]['payment_number']:"";?></b></td>
						<td  style="font-size:13.5px;border-left: 1px solid #000!important;border-bottom: none !important;border-top: none !important;border-right: none !important;">
							<p style="font-size:13.5px;margin:0px;">Date<br><br>
								<b><?php echo isset($getInvoiceDetails[0]['payment_date']) ? date(DATE_FORMAT,strtotime(($getInvoiceDetails[0]['payment_date']))):"";?></b>
							</p>
						</td>
					</tr>

					<tr width="100%" style="border:1px solid #000!important;font-size:13.5px;;">
						<td style="border: none !important;font-size:13.5px;vertical-align:baseline;">Delivery Note No<br><?php //echo isset($getInvoiceDetails[0]['reference_no']) ? $getInvoiceDetails[0]['reference_no']:"";?></td>
						<td  style="font-size:13.5px;border-left: 1px solid #000!important;border-bottom: none !important;border-top: none !important;border-right: none !important;">
							<p style="font-size:13.5px;margin:0px;">Mode Of Payment<br><b><?php echo isset($getInvoiceDetails[0]['payment_type']) ? $getInvoiceDetails[0]['payment_type']:"";?></b></p><br><br>
						</td>
					</tr>
				</tbody>
			</table>
			
			<div class='new-scroller'>
				<div style="margin-top:0px;">
					<table class="table-products table-bordered table-hover" width="100%" style="font-size:12px;">
						<thead style="border-bottom:1px solid #000;">
							<tr style="border-top:none !important;border-bottom:1px solid #000;">
								<th style="border-right:none !important;border-top:none !important;border-bottom:1px solid #000;font-size:12px;">S.No</th>
								<th style="border-right:none !important;border-top:none !important;border-bottom:1px solid #000;font-size:12px;text-align:left">PO Number</th>
								<th style="border-right:none !important;border-top:none !important;border-bottom:1px solid #000;font-size:12px;text-align:left">Receipt Number</th>
								<!-- <th>Category</th> -->
								<!-- <th class="text-center">Lot No.</th> -->
								<th style="border-right:none !important;text-align:left;border-top:none !important;border-bottom:1px solid #000;font-size:12px;">Date</th>
								<th style="border-right:none !important;text-align:right;border-top:none !important;border-bottom:1px solid #000;font-size:12px;">Receipt Amount</th>
								<!-- <th style="border-right:none !important;border-top:none !important;text-align:center;border-bottom:1px solid #000;font-size:12px;">Payment Term</th> -->  
								<th style="border-right:1px solid #000;border-top:none !important;text-align:right;border-bottom:1px solid #000;font-size:12px;">Paid Amount</th>  
							</tr>
							
						</thead>
						<tbody>
							<?php
								$i=1;
								$tot = $Qtty = 0;
								
								foreach ($getInvoiceItems as  $key) 
								{
									?>
									<tr>
										<?php /* <td class="tab-medium-width text-center"><?php echo $key->product_code; ?></td> */ ?>
										<td style="text-aign:left;border-bottom:1px solid #000!important;border-right:none !important;"><?php echo ($i); ?></td>
										<td style="border-bottom:1px solid #000!important;border-right:none !important;"><?php echo ucfirst($key->po_number); ?></td>
										<td style="border-bottom:1px solid #000!important;border-right:none !important;"><?php echo ucfirst($key->receipt_number); ?></td>
										<td class="tab-medium-width text-center" style="border-bottom:1px solid #000!important;"><?php echo isset($getInvoiceDetails[0]['payment_date']) ? date(DATE_FORMAT,strtotime(($getInvoiceDetails[0]['payment_date']))):"";?></td>
										<td class="tab-medium-width text-right" style="border-bottom:1px solid #000!important;text-align:right"><?php echo $key->total; ?></td>
										<td class="tab-medium-width text-right" style="border-bottom:1px solid #000!important;text-align:right"><?php echo $key->payment_amount; ?></td>
										<!-- <td class="tab-medium-width text-center" style="border-bottom:1px solid #000!important;text-align:center;"><?php #echo $key->payment_term; ?></td>
										 -->
										
									</tr>
									<?php
									$i++;
									$tot += $key->payment_amount;
								}
							?>
						</tbody>
					</table>
					
					<table width="100%" height="100%" class="table tab-tot table-total-sale --table-striped table-bordered table-condensed table-hover" style="font-size:12px;border-top:none!important;border-bottom:none!important;">
						<tbody>
							<tr width="100%" style="border-bottom:none!important;border-top:1px solid #000;border-bottom:none !important;">
								<td style="border-bottom:none!important;border-right:none!important;border-left:1px solid #000!important;vertical-align:baseline;font-size:11px;text-align:right">
									
								</td>
								<td style="border-bottom:none!important;border-right:none!important;border-left:none!important;vertical-align:baseline;font-size:11px;text-align:right">
								
								</td>
								<td style="border-bottom:none!important;border-right:none!important;border-left:none!important;vertical-align:baseline;font-size:11px;text-align:right">
									Net Amount
								</td>
								<td style="border-bottom:none!important;border-left:none!important;text-align:right;vertical-align:baseline;font-size:11px;">
									<b><?php echo number_format(round($tot),DECIMAL_VALUE,'.','');?></b>
								</td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
			
			<table style="border:1px solid #000!important;text-align:right;border-collapse:collapse" width="100%">
				<tbody style="border:1px solid #000!important;">
					<tr width="100%" style="border:1px solid #000!important;">
						<td width="50%" style="font-size:11.5px;text-align:left;border-left:1px solid #000!important;border-bottom:1px solid #000!important;border-right:none!important;vertical-align: baseline;">
							<span>E & O.E Goods once sold cannot be taken back </span><br>
							<span>Subject to Coimbatore jurisdiction only</span>
						</td>
						<td rowspan="2" width="50%" style="font-size:11.5px;text-align:left;border-right:1px solid #000!important;border-bottom:1px solid #000!important;border-left:1px solid #000!important;vertical-align: baseline;">
							<span><b>Company's Bank Account Details</b></span><br>
							<span>Bank Name &nbsp; &nbsp; &nbsp; &nbsp; : HDFC Bank</b></span><br>
							<span>Account Number : 99987638763232</b></span><br>
							<span>Branch & IFSC &nbsp; &nbsp; : Saibaba Colony & HDFC0002569</b></span><br>
						</td>
					</tr>
					<tr width="100%" style="border:1px solid #000!important;">
						<td style="font-size:11.5px;text-align:left;border-left:1px solid #000!important;border-bottom:1px solid #000!important;border-right:none!important;vertical-align: baseline;">
							<span><u><b>Declaration </b></u>We Declare that the invoice shows the actual price of the goods described and that all particulars are true and correct. </span><br>
						</td>
						
					</tr>
					<tr width="100%" style="border:1px solid #000!important;">
						<td  width="50%"  style="font-size:11.5px;text-align:left;border-left:1px solid #000!important;border-bottom:1px solid #000!important;border-right:none!important;vertical-align: baseline;">
							<span>Customer's seal and signature</span><br>
						</td>
						<td  width="50%"  style="font-size:11.5px;text-align:right;border-right:1px solid #000!important;border-bottom:1px solid #000!important;border-left:1px solid #000!important;">
							<span> <b> For <?php echo COMPANY_NAME;?></b></span><br><br><br><br><br><br><br>
							<span>Authorised Signature</span>
						</td>
					</tr>
				</tbody>
			</table>
		</div>
	</div><!-- Card end-->
	
</div><!-- Content end-->


