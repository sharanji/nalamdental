<style type="text/css">
	table.table-products
	{
		border-collapse: collapse !important;
	}
	.table-class tr:nth-child(even){background-color: #fff;}
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
	table.totalamnts tr td{}
	.table-class td, .table-class th {
		border: 1px solid #ddd;
		padding: 6px!important;
		font-size: 11px;
		font-weight: 400;
		
	}
	.table-class th {
		padding-top: 6px;
		padding-bottom: 6px;
		text-align: left;
		background-color: #fff;
		color: #8b8b8b;
		text-align: center;
	}
	table.table-products thead tr th, td
	{
		border:1px solid #ddd;
		padding:5px 10px!important;
		border-collapse: collapse !important;
		margin:0px!important;
		padding:5px;
	}
	#printable { display: none; }

	@media print
	{
		#non-printable { display: none; }
		#printable { display: block; }
	}
	.content-address-lft{float:left;}
	.content-address-rgt{float:right;}
	p.lic_no {
	float: left;
	margin: 0px 0px 2px 30px;
	}
	.table-bordered>thead>tr>th, .table-bordered>tbody>tr>th, .table-bordered>tfoot>tr>th, .table-bordered>thead>tr>td, .table-bordered>tbody>tr>td, .table-bordered>tfoot>tr>td{    border: 1px solid #bfbebe!important;}
</style>
	<?php 
		#Project Details
		$sql = "select 
				header_tbl.*,
				customer.customer_name,
				
				expense_payment_type.payment_type
				from inv_invoice_payment_header as header_tbl

				left join inv_invoice_payment_line as line_tbl on
				line_tbl.header_id = header_tbl.header_id
					
				left join cus_customers as customer on customer.customer_id = header_tbl.customer_id

				left join expense_payment_type 
					on expense_payment_type.payment_type_id = header_tbl.payment_method

				where  1=1
				and header_tbl.header_id='".$id."'	
		
		";	
		$getInvoiceDetails = $this->db->query($sql,array($id))->result_array();
		$query = "select 
					line_tbl.*,
					header_tbl.invoice_number as reference_no,
					coalesce(sum(coalesce(line_tbl2.total,0)),0) as total,
					payment_terms.payment_term

					from inv_invoice_payment_line as line_tbl
					left join inv_invoice_headers as header_tbl on header_tbl.header_id = line_tbl.invoice_id
					left join payment_terms on payment_terms.payment_term_id = header_tbl.payment_term_id
					left join inv_invoice_lines as line_tbl2 on line_tbl2.header_id = header_tbl.header_id
					where 1=1
					and line_tbl.header_id = '".$id."'
					group by line_tbl.invoice_id";

		$getInvoiceItems = $this->db->query($query)->result();
		
	?>

	<?php /*
		?>
			<div class="page-headerd page-header-light">
				<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
					
					<div class="d-flex">
						<div class="breadcrumb">
							<a href="<?php echo base_url();?>admin/dashboard" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> <?php echo get_phrase('Home');?></a>
							<a href="<?php echo base_url();?>payment/manageCustomerPayment" class="breadcrumb-item">
								<?php echo $page_title;?>
							</a>
						</div>
					</div>
					
					<div class="top-right" style="float:right;">
						<!-- Dropdown button -->
						
						<!-- Dropdown button -->

						<a href="<?php echo base_url();?>payment/manageCustomerPayment" class="btn btn-outline-primary btn-sm mr-1" title="Back">
							<i class="fa fa-arrow-left" aria-hidden="true"></i> Back
						</a>
						
						<!-- <a title="Print" onclick="printDiv('printableArea')" class="btn btn-outline-primary btn-sm mr-1" href="javascript:void(0);">
							<i class="fa fa-print"></i>
						</a> -->
						
						<a title="Generate PDF" target="_blank" href="<?php echo base_url(); ?>payment/customerPaymentPDF/<?php echo $id;?>" class="btn btn-outline-primary mr-1 btn-sm" href="javascript:void(0);">
							<i class="fa fa-file-pdf-o"></i>
						</a>
						
						<!-- <a href="<?php echo base_url(); ?>pos/ManagePos/edit/<?php echo $id;?>" class="btn btn-info btn-sm mr-1 " title="Edit">
							<i class="fa fa-pencil" aria-hidden="true"></i>
						</a> -->
					</div>
					
				</div>
			</div>


		<?php
	
	*/ ?>

<div class="content" --id="printableArea"><!-- Content start-->
	<div class="card box"><!-- Card start-->
		<div class="card-body text-center">
			<div class="top-right" style="float:right;">
				<!-- Dropdown button -->
				
				<!-- Dropdown button -->

				<a href="<?php echo base_url();?>payment/manageCustomerPayment" class="btn btn-outline-primary btn-sm mr-1" title="Back">
					<i class="fa fa-arrow-left" aria-hidden="true"></i> Back
				</a>
				
				<!-- <a title="Print" onclick="printDiv('printableArea')" class="btn btn-outline-primary btn-sm mr-1" href="javascript:void(0);">
					<i class="fa fa-print"></i>
				</a> -->
				
				<a title="Generate PDF" target="_blank" href="<?php echo base_url(); ?>payment/customerPaymentPDF/<?php echo $id;?>" class="btn btn-outline-primary mr-1 btn-sm" href="javascript:void(0);">
					<i class="fa fa-file-pdf-o"></i>
				</a>
				
				<!-- <a href="<?php echo base_url(); ?>pos/ManagePos/edit/<?php echo $id;?>" class="btn btn-info btn-sm mr-1 " title="Edit">
					<i class="fa fa-pencil" aria-hidden="true"></i>
				</a> -->
			</div>
			<table class="table-class">
				<tbody width="100%">
					<tr  width="100%" style="border:none!important;border-top:1px solid #fff!important;border-right:1px solid #fff!important;border-left:1px solid #fff!important;">
						<td colspan="3" style="border:none!important;text-align:center;border-right:none !important;font-size:16px;margin-left:30px;">
							<b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;CUSTOMER PAYMENT<b>
						</td>
					</tr>
					<tr rowspan="2" width="100%" style="border:1px solid #ddd!important;">
						<td class="text-left" width="50%" colspan="2"  rowspan="2" style="font-size:13.5px;vertical-align: baseline;">
							<b style="font-size:16px;vertical-align: baseline;"><?php echo COMPANY_NAME;?></b><br>
							<?php echo nl2br(ADDRESS1);?><br>
							GST IN : <?php echo GST_NUMBER;?><br>

							Mob &nbsp;&nbsp;&nbsp;&nbsp;: <?php echo PHONE1;?><br>
							Email &nbsp;: <?php echo CONTACT_EMAIL;?>
						</td>
						<td style="border: none !important;font-size:13.5px;">Payment No<br><br><b><?php echo isset($getInvoiceDetails[0]['payment_number']) ? $getInvoiceDetails[0]['payment_number']:"";?></b></td>
						<td  style="font-size:13.5px;border-left: 1px solid #ddd!important;border-bottom: none !important;border-top: none !important;border-right: none !important;">
							<p style="font-size:13.5px;margin:0px;">Date<br><br>
								<b><?php echo isset($getInvoiceDetails[0]['payment_date']) ? date(DATE_FORMAT,strtotime(($getInvoiceDetails[0]['payment_date']))):"";?></b>
							</p>
						</td>
					</tr>

					

					<tr width="100%" style="border:1px solid #ddd!important;font-size:13.5px;;">
						<td style="border: none !important;font-size:13.5px;vertical-align:baseline;">Delivery Note No<br><?php //echo isset($getInvoiceDetails[0]['reference_no']) ? $getInvoiceDetails[0]['reference_no']:"";?></td>
						<td  style="font-size:13.5px;border-left: 1px solid #ddd!important;border-bottom: none !important;border-top: none !important;border-right: none !important;">
							<p style="font-size:13.5px;margin:0px;">Mode Of Payment<br><b><?php echo isset($getInvoiceDetails[0]['payment_type']) ? $getInvoiceDetails[0]['payment_type']:"";?></b></p><br><br>
						</td>
					</tr>
				</tbody>
			</table>
			
			<div class='new-scroller'>
				<div style="margin-top:0px;">
					<table class="table-products table-bordered table-hover" width="100%" style="font-size:12px;">
						<thead style="border-bottom:1px solid #ddd;">
							<tr style="border-top:none !important;border-bottom:1px solid #ddd;">
								<th style="text-align:center;border-right:none !important;border-top:none !important;border-bottom:1px solid #ddd;font-size:12px;">S.No</th>
								<th style="border-right:none !important;border-top:none !important;border-bottom:1px solid #ddd;font-size:12px;">Invoice No.</th>
								<!-- <th>Category</th> -->
								<!-- <th class="text-center">Lot No.</th> -->
								<th style="border-right:none !important;text-align:center;border-top:none !important;border-bottom:1px solid #000;font-size:12px;">Date</th>
								<th style="border-right:none !important;text-align:right;border-top:none !important;border-bottom:1px solid #000;font-size:12px;">Total Amount</th>
								<th style="border-right:none !important;border-top:none !important;text-align:center;border-bottom:1px solid #000;font-size:12px;">Payment Term</th>  
								<th style="border-right:none !important;border-top:none !important;text-align:right;border-bottom:1px solid #000;font-size:12px;">Amount</th>  
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
										<td style="text-align:center;border-bottom:none!important;border-right:none !important;">
											<?php echo ($i); ?>
										</td>
										<td style="border-bottom:none!important;border-right:none !important;"><?php echo ucfirst($key->reference_no); ?></td>
										<td class="tab-medium-width text-center"><?php echo isset($getInvoiceDetails[0]['payment_date']) ? date(DATE_FORMAT,strtotime(($getInvoiceDetails[0]['payment_date']))):"";?></td>
										<td class="tab-medium-width text-right"><?php echo $key->total; ?></td>
										<td class="tab-medium-width text-center"><?php echo $key->payment_term; ?></td>
										<td class="tab-medium-width text-right"><?php echo $key->payment_amount; ?></td>
									</tr>
									<?php
									$i++;
									$tot += !empty($key->payment_amount) ? $key->payment_amount : 0;
								}
							?>
						
									
						</tbody>
					</table>
					
					<table width="100%" height="100%" class="table tab-tot table-total-sale --table-striped table-bordered table-condensed table-hover" style="font-size:12px;border-top:none!important;border-bottom:none!important;">
						<tbody>
							<tr width="100%" style="border-bottom:none!important;border-top:1px solid #000;border-bottom:none !important;">
								<td style="border-bottom:none!important;border-right:none!important;border-left:none!important;vertical-align:baseline;font-size:11px;text-align:right">
									
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
					<table width="100%" height="100%" class="table tab-tot table-total-sale --table-striped table-bordered table-condensed table-hover" style="border:none!important;font-size:12px;">
						<tbody>
							<tr colspan="10" width="100%" style="width:100%!important;border-top:none!important;border-right:1px solid #000!important;border-left:1px solid #000!important;">
								<td  style="border-right:1px solid #c3bcbc!important;border-bottom:1px solid #c3bcbc!important;border-top:none!important;vertical-align:baseline;font-size:11px;">
									<b style="font-size:11px;display:inline-block;">
										<?php
											/* $totalAmountNew = number_format(round($row['total']),DECIMAL_VALUE,'.','');
											echo amountInWords($totalAmountNew); */
										?>
									</b>
								</td>
							</tr>
						</tbody>
					</table>
					<?php 
						/* $termsQry ="select * from payment_terms where payment_term_id = '".$row['payment_term_id']."'";
						$getTerms = $this->db->query($termsQry)->result_array(); */
					?>
				</div>
			</div>
			
			<table style="border:1px solid #ddd!important;text-align:right;border-collapse:collapse" width="100%">
				<tbody style="border:1px solid #ddd!important;">
					<tr width="100%" style="border:1px solid #ddd!important;">
						<td width="50%" style="font-size:11.5px;text-align:left;border-left:1px solid #ddd!important;border-bottom:1px solid #ddd!important;border-right:none!important;vertical-align: baseline;">
							<span>E & O.E Goods once sold cannot be taken back </span><br>
							<span>Subject to Coimbatore jurisdiction only</span>
						</td>
						<td rowspan="2" width="50%" style="font-size:11.5px;text-align:left;border-right:1px solid #ddd!important;border-bottom:1px solid #ddd!important;border-left:1px solid #ddd!important;vertical-align: baseline;">
							<span><b>Company's Bank Account Details</b></span><br>
							<span>Bank Name &nbsp; &nbsp; &nbsp; &nbsp; : HDFC Bank</b></span><br>
							<span>Account Number : 99987638763232</b></span><br>
							<span>Branch & IFSC &nbsp; &nbsp; : Saibaba Colony & HDFC0002569</b></span><br>
						</td>
					</tr>
					<tr width="100%" style="border:1px solid #ddd!important;">
						<td style="font-size:11.5px;text-align:left;border-left:1px solid #ddd!important;border-bottom:1px solid #ddd!important;border-right:none!important;vertical-align: baseline;">
							<span><u><b>Declaration </b></u>We Declare that the invoice shows the actual price of the goods described and that all particulars are true and correct. </span><br>
						</td>
						
					</tr>
					<tr width="100%" style="border:1px solid #ddd!important;">
						<td  width="50%"  style="font-size:11.5px;text-align:left;border-left:1px solid #ddd!important;border-bottom:1px solid #ddd!important;border-right:none!important;vertical-align: baseline;">
							<span>Customer's seal and signature</span><br>
						</td>
						<td  width="50%"  style="font-size:11.5px;text-align:right;border-right:1px solid #ddd!important;border-bottom:1px solid #ddd!important;border-left:1px solid #ddd!important;">
							<span> <b> For <?php echo COMPANY_NAME;?></b></span><br><br><br><br><br><br><br>
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

</div>