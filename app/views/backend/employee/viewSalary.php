<style type="text/css">
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
	/* .table-bordered>thead>tr>th, .table-bordered>tbody>tr>th, .table-bordered>tfoot>tr>th, .table-bordered>thead>tr>td, .table-bordered>tbody>tr>td, .table-bordered>tfoot>tr>td{    border: 1px solid #bfbebe!important;} */
	/* .dataTable table {
		border-collapse: collapse;
	} */
	/* .dataTable tr { border: none; } */
	.dataTable td {
		border-right: solid 1px #b3b3b3; 
		border-left: solid 1px #b3b3b3;
	}
	/* tr.top-row-one.top-row-two td {
		border-left: 0px!important;
		border-right: 0px!important;
	} */

</style>

<style>
	tr.top-row-one td {
		padding: 0px;
		border: 1px solid #b3b3b3;
		padding: 2px 0px 2px 10px;
	}
</style>

<script language="javascript">
	function printDiv(divName) 
	{ 
		var printContents = document.getElementById(divName).innerHTML; 
		var originalContents = document.body.innerHTML; 
		document.body.innerHTML = printContents; window.print(); 
		document.body.innerHTML = originalContents; 
	}
</script>
<script language="javascript">
	function printDiv(divName) 
	{ 
		var printContents = document.getElementById(divName).innerHTML; 
		var originalContents = document.body.innerHTML; 
		document.body.innerHTML = printContents; window.print(); 
		document.body.innerHTML = originalContents; 
	}
	
	function generatePDF() {
	  // Choose the element that our invoice is rendered in.
	  const element = $('#printableArea').html();
	  // Choose the element and save the PDF for our user.
    
		html2pdf()
		.set({ html2canvas: { scale: 3} , orientation :'landscape' })
		.from(element)
		.save('annexure');
	}
</script>
<script src="<?php echo base_url().'assets/backend/assets/js/html2pdf.bundle.min.js'?>"></script>

<!-- Page header start-->
	<?php $annexure = accessMenu(annexure); ?>

	<?php 
		#Project Details
		$sql = "select 
		emp_salary_structure_header.*
			from emp_salary_structure_header 
				where emp_salary_structure_header.header_id = ?";
		$getSalaryDetails = $this->db->query($sql,array($id))->result_array();
		//print_r($getSalaryDetails);exit;
		// $lineData = $this->db->query('select hr_payslip_line.*
		// 								from hr_payslip_line 
		// 								left join hr_payslip_categories on hr_payslip_categories.category_id = hr_payslip_line.category_id
		// 								where header_id = '.$getSalaryDetails[0]['header_id'].'')->result_array();
		$currentDate = date("Y-m-d",time());
		$userDetailsQuery = "select 
									emp_salary_structure_line.*,
									users.user_id,
									users.first_name, 
									users.random_user_id, 
									users.last_name, 
									users.gender,
									hr_payslip_categories.category_name
									from emp_salary_structure_line

									left join emp_salary_structure_header on emp_salary_structure_header.header_id = emp_salary_structure_line.header_id 
									left join users on users.user_id = emp_salary_structure_header.user_id 
									left join hr_payslip_categories on hr_payslip_categories.category_id = emp_salary_structure_line.element_id 

									where emp_salary_structure_line.header_id = ".$id."
									";

		$userDetails = $this->db->query($userDetailsQuery)->result_array();
		//print_r($userDetails);exit;
		// echo json_encode($userDetails);exit;
	?>

<div class="content"><!-- Content start-->
	<div class="card"><!-- Card start-->
		<div class="card-body">
			
			<div class="row mb-2">
				<div class="col-md-6"><?php echo $page_title;?></div>
				<div class="col-md-6 float-right text-right">
						
					<button class="btn btn-warning btn-sm" onclick="generatePDF()" title="Download PDF">
						<i class="fa fa-file-pdf-o"></i>
					</button> 
					<?php
						if((isset($annexure['create_edit_only']) && $annexure['create_edit_only'] == 1) || $this->user_id == 1)
						{
							?>
							<a href="<?php echo base_url(); ?>employee/ManageSalary/edit/<?php echo $id;?>" title="Edit" class="btn btn-info btn-sm" title="Edit">
								<i class="fa fa-pencil" aria-hidden="true"></i>
							</a>
							<?php 
						} 
					?>
				</div>
			</div>

			<div id="printableArea">
				
					<form method="post">
						<div class="container" style="border:1px solid #b3b3b3;padding:0px">
						<span style="text-align:center;float:left;width:100%;padding:10px;font-size:22px;">SALARY ANNEXURE</span>
							<table style="border:0px solid #b3b3b3;">
								<tr width="100%" style="text-align:left;">
									<td width="50%" colspan="2">
										<img src="<?php echo base_url();?>uploads/logo.png" alt="logo" class="bottom-logo" style="float:left;width: 230px;">
									</td>
									<td colspan="3" style="font-size:16px;;">
										<span style="font-size:20px;"><b>Jesper Apps Software Services Private Limited</b></span><br>
										<?php echo ADDRESS1; ?> <br>
										<span class="d-none"><b>CONFIDENTIAL<?php //echo date('F Y',$getSalaryDetails[0]['string_from_date']) ?></b></span>
									</td>
								</tr>
								
								<tr class="top-row-one" style="border-right:0px!important;">
									<td colspan="1" style="width:30px;"><b>Emp Code</b></td>
									<td><?php echo $userDetails[0]['random_user_id'] ?></td>
									<td><b>Place</b></td>
									<td>
										<?php 
											echo $getSalaryDetails[0]['place'];
										?>
									</td>
								</tr>
								<tr class="top-row-one">
									<td colspan="1"><b>Emp Name</b></td>
									<td>
										<?php 
										if ($userDetails[0]['gender'] == 1) 
										{
											echo "Mr.".$userDetails[0]['first_name']." ".$userDetails[0]['last_name'];
										}
										else 
										{
											echo "Ms.".$userDetails[0]['first_name']." ".$userDetails[0]['last_name'];
										}
									?>
									</td>
									<td><b>Created Date</b></td>
									<td>
										<?php echo date('d-M-Y',$getSalaryDetails[0]['created_date']);?>
									</td>
								</tr>
								<tr class="top-row-one">
									<td colspan="1"><b>Living City</b></td>
									<td>
										<?php 
											foreach($this->living_city as $key=>$value)
											{
												if($getSalaryDetails[0]['living_city'] == $key)
												{
													echo $value;
												}
											}
										?>
									</td>
									<td><b>Effective Date</b></td>
									<td>
										<?php echo date('d-M-Y',$getSalaryDetails[0]['string_from_date']);?> 
										<?php 
											if ($getSalaryDetails[0]['string_to_date'] !=0 && $getSalaryDetails[0]['string_to_date'] !="") 
											{
												 ?>	
												- <?php echo date('d-M-Y', $getSalaryDetails[0]['string_to_date']);?>
												<?php 
											} 
										?>
									</td>
								</tr>
							</table>
							
							
								<span style="float:left;width:100%;text-align:center;padding:10px 0px; font-size:21px;color:#008cd1;"><b>Your annualized Compensation & Benefits statement</b></span>
								<span style="float:left;width:100%;text-align:center;padding:10px 0px; border-top:1px solid #b3b3b3; border-bottom:0px solid #000; font-size:14px;color:#000;">
									This is your personalized Compensation & Benefits statement. Incase you have any clarifications to seek, please contact your HR.Certain items have notional costs based on prevailing market practice.
								</span>
							<div class="row">
								<div class="col-md-12">
									<table class="dataTable">
										<tbody>
											<tr class="top-row-one top-row-two">
												<td width="50%"><b>Element Name</b></td>
												<td width="25%" class="text-center"><b>Monthly Pay (<?php echo CURRENCY_SYMBOL;?>)</b></td>
												<td width="25%" class="text-center"><b>Annual Pay (<?php echo CURRENCY_SYMBOL;?>)</b></td>
											</tr>
											<?php
												$total_ctc_earnings = 0;
												$earnings = 0;

												foreach($userDetails as $data)
												{
													?>
														<tr class="top-row-one top-row-two">
															<td ><?php echo $data['category_name']; ?></td>
															<td class="text-right px-2"><?php echo $data['per_month_inr']; ?></td>
															<td class="text-right px-2"><?php echo $data['per_annum_inr']; ?></td>
														</tr>
													<?php
												}
											?>
											<tr class="top-row-one top-row-two">
												<td class="text-left px-2"> Total </td>
												<td class="text-right px-2"><b>
													<?php
													$emp_month_amount = $getSalaryDetails[0]['emp_per_annum_ctc'] / 12;
													 echo number_format($emp_month_amount,DECIMAL_VALUE,'.','');
												  ?></b></td>
												<td class="text-right px-2"><b><?php echo number_format($getSalaryDetails[0]['emp_per_annum_ctc'],DECIMAL_VALUE,'.',''); ?></b></td>
											</tr>
											
										</tbody>
									</table>
									<span style="font-weight:600;padding-left:7px;padding-top:7px;padding-bottom:7px;float:left;width:100%;">
										<?php ?>Amount In Words : &nbsp;<?php
										$salaryDetailswords = $getSalaryDetails[0]['emp_per_annum_ctc'];
										echo amountInWords($salaryDetailswords);?>
									</span>
								</div>
							</div>
							<p style="padding-top:15px;border-top:1px solid #b3b3b3;padding-left:5px;font-size:18px;"><b>Other benefits not computed above:</b></p>
							<span style="padding-left:5px;float:left;padding-bottom:5px;width:100%;">Special Day Benefit</span>
							<?php 
								$benefits = "select 
								annual_benefits.*
									from annual_benefits where benefit_status = 1";
								$getgetBenefits = $this->db->query($benefits)->result_array();
							?>
							<ul class="benefits">
								<?php 
									foreach($getgetBenefits as $row)
									{
										?>
										<li><i class="fa fa-check"></i><?php echo ucfirst($row['benefit_name']);?></li>
										<?php
									}
								?>
								
							</ul>
							<span class="pl-4">
								*** These costs will vary depending upon individual circumstances.
							</span>
							<?php /* <p class="mt-3">
								This document contains confidential information. If you are not the intended recipient you are not authorized to use or disclose it in any form. <br>
								If you received this in error please destroy it along with any copies and notify the sender immediately.
							</p> */ ?>
						</div>
					</form>
				
			</div>
		</div>
		
		<div class="row mb-3 mr-3">
			<div class="col-md-10">
			</div>
			<div class="col-md-2 text-right">
				<a href="<?php echo base_url();?>employee/ManageSalary" class="btn btn-outline-primary" title="Back">
					<i class="fa fa-arrow-left" aria-hidden="true"></i> Back
				</a>
			</div>
		</div>

	</div>
</div>
