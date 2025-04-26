
<!DOCTYPE html>
<html>
	<head>
		<title>Invoice</title>
		<style>
		/* body{
				font-family: arial;
				font-size: 13px;
			}*/
			
		/* .table-class.table.prescription, .prescription th, .prescription td
		{
			border: 1px solid black;padding:2px;
		}
		
		.table-class.table.prescription td
		{
			border: 1px solid black;padding:2px;
		}
		
		.table-class.table.prescription 
		{
			width: 100%;
		}
		
		.table-class.table.prescription td
		{
		   border: 1px solid black;padding:2px;
		}*/
		
		.table-class {
		  font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
		  border-collapse: collapse;
		  width: 100%;
		}
		
		.table-class td, .table-class th {
		  border: 1px solid #ddd;
		  padding: 8px;
		}
		
		.table-class tr:nth-child(even){background-color: #f2f2f2;}
		
		.table-class tr:hover {background-color: #ddd;}
		
		.table-class th {
		  padding-top: 12px;
		  padding-bottom: 12px;
		  text-align: left;
		  background-color: #fff;
		  color: #000;
		}
	  </style>
	</head>
<body>
	
	<?php 
		#Invoice Details
		$sql = "select 
				emp_salaryslip.*,
				users.first_name,
				users.date_of_joining,
				users.uan_number,
				users.bank_account_no,
				emp_designations.designation_name
				
				from emp_salaryslip
				
			Left join users on users.user_id = emp_salaryslip.employee_id
			Left join emp_designations on emp_designations.designation_id = users.designation_id
		
		where emp_salaryslip.emp_salaryslip_id = ?";
		
		$getInvoiceDetails = $this->db->query($sql,array($id))->result_array();
		
	?>
		<?php 
			$Gross_earnings = $getInvoiceDetails[0]['other_benefits'] + $getInvoiceDetails[0]['allowance'] + $getInvoiceDetails[0]['basic_salary'];
		?>
		<?php 
			$totaldeductions =  $getInvoiceDetails[0]['esi_deduction'] + $getInvoiceDetails[0]['tds'] +  $getInvoiceDetails[0]['pf_deduction'];
		?>
		<?php
			$toalReimbursement =  $getInvoiceDetails[0]['reimbursement_1'] + $getInvoiceDetails[0]['reimbursement_2'];
		?>
		<?php
			$netpayable = $Gross_earnings - $totaldeductions + $toalReimbursement;
		?>
		
	<div style="padding-bottom:5px;margin-bottom:5px;">
		<table width="100%" cellpadding="0" valign="top" style="margin-top:20px;">
		
				
			<tr>
				<td style="padding-right:120px;">
					<div class='content-address-rgt' style="text-align:center;width:100%;margin: 0px 0px 0px 0px;padding-top: 0;">
						<img style="width:225px;height:75px;" src="<?php echo base_url(); ?>uploads/logo.png"/>
					</div>
					<div style="text-align:center;width:100%;">
						<?php echo ADDRESS1;?><br>
						<?php if(!empty(PHONE2)){?> <b>Tel : </b><?php echo PHONE2;} ?><?php if(!empty(PHONE1)){?>, <b> Mob : </b><?php echo PHONE1;?><?php } ?>&nbsp;&nbsp;	
						<b> Email : </b> <?php echo CONTACT_EMAIL;?><br>	
						<b> GSTN No : </b><?php echo GST_NUMBER;?>  &nbsp;			
						<b> PAN No : </b><?php echo PAN_NUMBER;?><br>
					</div>
				</td>
			</tr>
		</table>
		
		<br>
			<div style="border-bottom: 2px solid #0195b2;"></div>
		<br>
		
		<table width="100%" cellpadding="0" valign="top" style="margin-top:20px;">	
			<tr>
				<td style="width:50%;border:1px solid #000;line-height:27px;padding:0px 10px">
					<b>Employee Pay Summary	</b>
				</td>
				<td style="border:1px solid #000;text-align:center;" rowspan="3" >
					Employee Net Pay
				</td>
				
			</tr>
			<tr>
				<td style="border:1px solid #000;line-height:27px;padding:0px 10px">
					Employee Name : <?php echo ucfirst($getInvoiceDetails[0]['first_name']);?>
				</td>
			</tr>
			<tr>
				<td style="border:1px solid #000;line-height:27px;padding:0px 10px">
					Designation : <?php echo ucfirst($getInvoiceDetails[0]['designation_name']);?>
				</td>
			</tr>
			<tr>
				<td style="border:1px solid #000;line-height:27px;padding:0px 10px">
					Date of Joining : <?php echo $getInvoiceDetails[0]['date_of_joining'];?>
				</td>
				<td style="border:1px solid #000;text-align:center;" >
					<?php echo CURRENCY_SYMBOL; ?> <?php echo $netpayable; ?>
				</td>
			</tr>
			<tr>
				<td style="border:1px solid #000;line-height:27px;padding:0px 10px">
					Pay Peroid :  <?php echo $getInvoiceDetails[0]['pay_period'];?>
				</td>
				<td style="border:1px solid #000;text-align:center;">
					Paid Days:  <?php echo $getInvoiceDetails[0]['paid_days'];?> &nbsp;&nbsp;&nbsp;&nbsp; LOP Days :  <?php echo $getInvoiceDetails[0]['lop_days'];?>
				</td>
			</tr>
			<tr>
				<td style="border:1px solid #000;line-height:27px;padding:0px 10px">
					Pay Date :  <?php echo $getInvoiceDetails[0]['pay_date'];?>
				</td>
				<td rowspan="4" style="border:1px solid #000;text-align:center;">
					
				</td>
			</tr>
			<tr>
				<td style="border:1px solid #000;line-height:27px;padding:0px 10px">
					PF A/C Number : <?php echo $getInvoiceDetails[0]['bank_account_no'];?>
				</td>
			</tr>
			<tr>
				<td style="border:1px solid #000;line-height:27px;padding:0px 10px">
					ESI no : <?php echo $getInvoiceDetails[0]['esi_no'];?>
				</td>
			</tr>
			<tr>
				<td style="border:1px solid #000;line-height:27px;padding:0px 10px">
					UAN Number : <?php echo $getInvoiceDetails[0]['uan_number'];?>
				</td>
			</tr>
		</table>
		
		<table width="100%" colspan="0" valign="top">	
			<tr>
				<td style="border:1px solid #000;line-height:27px;padding:0px 10px">
					<b>EARNINGS	</b>
				</td>
				<td style="border:1px solid #000;text-align:center;">
					<b>AMOUNT</b>
				</td>
				<td style="border:1px solid #000;text-align:center;">
					<b>DEDUCTIONS</b>
				</td>
				<td colspan="3" style="border:1px solid #000;text-align:center;">
					<b>AMOUNT</b>
				</td>
			</tr>
			<tr>
				<td style="border:1px solid #000;line-height:27px;padding:0px 10px">
					Basic Salary 
				</td>
				<td style="border:1px solid #000;line-height:27px;padding:0px 10px;text-align:center;">
					<?php echo CURRENCY_SYMBOL; ?> <?php echo ucfirst($getInvoiceDetails[0]['basic_salary']);?>
				</td>
				<td style="border:1px solid #000;line-height:27px;padding:0px 10px">
					PF
				</td>
				<td colspan="3" style="border:1px solid #000;line-height:27px;padding:0px 10px;text-align:center;">
					<?php echo CURRENCY_SYMBOL; ?> <?php echo ucfirst($getInvoiceDetails[0]['pf_deduction']);?>
				</td>
				
			</tr>
			<tr>
				<td style="border:1px solid #000;line-height:27px;padding:0px 10px">
					Allowances 
				</td>
				<td style="border:1px solid #000;line-height:27px;padding:0px 10px;text-align:center;">
					<?php echo CURRENCY_SYMBOL; ?> <?php echo $getInvoiceDetails[0]['allowance'];?>
				</td>
				<td style="border:1px solid #000;line-height:27px;padding:0px 10px">
					TDS
				</td>
				<td colspan="3" style="border:1px solid #000;line-height:27px;padding:0px 10px;text-align:center;">
					<?php echo CURRENCY_SYMBOL; ?><?php echo $getInvoiceDetails[0]['tds'];?>
				</td>
			</tr>
			<tr>
				<td style="border:1px solid #000;line-height:27px;padding:0px 10px">
					Other Benefits
				</td>
				<td style="border:1px solid #000;text-align:center;" >
					<?php echo CURRENCY_SYMBOL; ?> <?php echo ucfirst($getInvoiceDetails[0]['other_benefits']);?>
				</td>
				<td style="border:1px solid #000;line-height:27px;padding:0px 10px">
					ESI
				</td>
				<td colspan="3" style="border:1px solid #000;text-align:center;" >
					<?php echo CURRENCY_SYMBOL; ?> <?php echo ucfirst($getInvoiceDetails[0]['esi_deduction']);?>
				</td>
			</tr>
			<tr>
				<td style="border:1px solid #000;line-height:27px;padding:0px 10px">
					<b>Gross Earnings</b>
				</td>
				<td style="border:1px solid #000;text-align:center;">
					<b><?php echo CURRENCY_SYMBOL; ?> <?php echo $Gross_earnings; ?></b>
				</td>
				<td style="border:1px solid #000;line-height:27px;padding:0px 10px">
					<b>Total Deductions</b>
				</td>
				<td colspan="3" style="border:1px solid #000;text-align:center;">
					<b><?php echo CURRENCY_SYMBOL; ?> <?php echo $totaldeductions; ?></b>
				</td>
			</tr>
			
			<tr>
				<th  colspan="2"  width="50%"style="border:1px solid #000;line-height:27px;padding:0px 10px">
					<b>REIMBURSEMENTS</b>
				</th>
				
				<td style="border:1px solid #000;text-align:center;">
					<b></b>
				</td>
				
				<td style="border:1px solid #000;text-align:center;">
					<b></b>
				</td>
				<td style="border:1px solid #000;text-align:center;">
					<b></b>
				</td>
			</tr>
			<tr>
				<th  colspan="1"  width="50%"style="border:1px solid #000;text-align:left;font-weight:400;line-height:27px;padding:0px 10px">
					Reimbursement  1
				</th>
				
				<td style="border:1px solid #000;text-align:center;">
					<?php echo CURRENCY_SYMBOL;?> <?php echo ucfirst($getInvoiceDetails[0]['reimbursement_1']);?>
				</td>
				
				<td style="border:1px solid #000;text-align:center;">
					<b></b>
				</td>
				<td style="border:1px solid #000;text-align:center;">
					<b></b>
				</td>
				<td style="border:1px solid #000;text-align:center;">
					<b></b>
				</td>
			</tr>
			<tr>
				<th  colspan="1"  width="50%"style="border:1px solid #000;text-align:left;font-weight:400;line-height:27px;padding:0px 10px">
					Reimbursement 2
				</th>
				
				<td style="border:1px solid #000;text-align:center;">
					<?php echo CURRENCY_SYMBOL;?> <?php echo ucfirst($getInvoiceDetails[0]['reimbursement_2']);?>
				</td>
				
				<td style="border:1px solid #000;text-align:center;">
					<b></b>
				</td>
				<td style="border:1px solid #000;text-align:center;">
					<b></b>
				</td>
				<td style="border:1px solid #000;text-align:center;">
					<b></b>
				</td>
			</tr>
			<tr>
				<th  colspan="1"  width="50%"style="border:1px solid #000;text-align:left;line-height:27px;padding:0px 10px">
					<b>Total Reimbursement </b>
				</th>
				
				<td style="border:1px solid #000;text-align:center;">
					<b><?php echo CURRENCY_SYMBOL; ?> <?php echo $toalReimbursement; ?></b>
				</td>
				
				<td style="border:1px solid #000;text-align:center;">
					<b></b>
				</td>
				<td style="border:1px solid #000;text-align:center;">
					<b></b>
				</td>
				<td style="border:1px solid #000;text-align:center;">
					<b></b>
				</td>
			</tr>
			
			<tr>
				<td colspan="3" style="border:1px solid #000;line-height:27px;padding:0px 10px">
					<b>NET PAY	</b>
				</td>
				<td colspan="3" style="border:1px solid #000;text-align:center;">
					<b>AMOUNT</b>
				</td>
				
			</tr>
			<tr>
				<td colspan="3" style="border:1px solid #000;line-height:27px;padding:0px 10px">
					Gross Earning
				</td>
				<td colspan="3" style="border:1px solid #000;text-align:center;">
					<?php echo CURRENCY_SYMBOL; ?> <?php echo $Gross_earnings; ?>
				</td>
			</tr>
			<tr>
				<td colspan="3" style="border:1px solid #000;line-height:27px;padding:0px 10px">
					Total Deductions
				</td>
				<td colspan="3" style="border:1px solid #000;text-align:center;">
					<?php echo CURRENCY_SYMBOL; ?> <?php echo $totaldeductions; ?>
				</td>
			</tr>
			<tr>
				<td colspan="3" style="border:1px solid #000;line-height:27px;padding:0px 10px">
					Total Reimbursements
				</td>
				<td colspan="3" style="border:1px solid #000;text-align:center;">
					<?php echo CURRENCY_SYMBOL; ?> <?php echo $toalReimbursement; ?>
				</td>
			</tr>
			<tr>
				<td colspan="3" style="border:1px solid #000;line-height:27px;padding:0px 10px;text-align:center;">
					<b>Total Net Payable</b>
				</td>
				<td colspan="3" style="border:1px solid #000;text-align:center;">
					<?php echo CURRENCY_SYMBOL; ?> <b><?php echo $netpayable; ?></b>
				</td>
			</tr>
			<tr>
				<td colspan="5"width="100% "style="border:1px solid #000;line-height:27px;padding:0px 10px;text-align:center;">
					<b>Total Net Payable ₹0.00 (<?php echo amountInWords($netpayable);?>)</b>
				</td>
			</tr>
			
		</table>
	</div>
	
	<?php 
	/* <table width="100%" cellpadding="0" valign="top" style="margin:10px;0px;10px;0px;">
		<tr>
			<td style="padding-right:1%;">
				
				Customer Name : <?php echo ucfirst($getInvoiceDetails[0]['first_name']);?><br>
				
			</td>
			<td style="padding-right:1%;"></td>
			<td style="padding-right:1%;"></td>
			<td style="padding-right:1%;float:right;">
				Date : <?php echo $getInvoiceDetails[0]['invoice_add_date'];?>
			</td>
		</tr>
	</table> */ ?>
	
</body>
</html>
<script>
  window.print();
</script>
	
	

