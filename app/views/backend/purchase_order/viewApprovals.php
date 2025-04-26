<style>
	.steps {
		padding: 0;
		margin: 0;
		list-style: none;
		display: flex;
		overflow-x: hidden;
	}

	.steps .step:first-child {
		margin-left: auto
	}

	.steps .step:last-child {
		margin-right: auto
	}

	.step:first-of-type .step-circle::before {
		display: none
	}

	.step:last-of-type .step-content {
		padding-right: 0
	}

	.step-content {
		box-sizing: content-box;
		display: flex;
		align-items: center;
		flex-direction: column;
		width: 13rem;
		min-width: 6rem;
		/* min-width: 6rem; */
		/* max-width: 5.5rem; */
		padding-top: .5rem;
		padding-right: 0.5rem;
	}

	.step-circle {
		position: relative;
		display: flex;
		justify-content: center;
		align-items: center;
		width: 2rem;
		height: 2rem;
		color: #adb5bd;
		border: 2px solid #adb5bd;
		border-radius: 100%;
		background-color: #fff;
	}

	.step-circle::before {
		content: "";
		display: block;
		position: absolute;
		top: 50%;
		left: -2px;
		width: calc(12rem + 1rem - 1.5rem);
		height: 2px;
		transform: translate(-100%, -50%);
		color: #adb5bd;
		background-color: currentColor;
	}

	.step-text {
		color: #adb5bd;
		word-break: break-all;
		margin-top: .25em;
	}

	.step-active .step-circle {
		color: #fff;
		background-color: #007bff;
		border-color: #007bff;
	}

	.step-active .step-circle::before {
		color: #007bff;
	}

	.step-active .step-text {
		color: #007bff;
	}

	

	.step-success .step-circle {
		color: #fff;
		background-color: #33c320;
		border-color: #33c320;
	}
	.step-success .step-circle::before {
		color: #33c320;
		font-weight: 500;
	}
	.step-success .step-text {
		color: #33c320;
		font-weight: 600;
	}

	.step-error .step-circle {
		color: #fff;
		background-color: #ff5722;
		border-color: #ff5722;
	}
	.step-error .step-circle::before {
		color:#ff5722;
	}
	.step-error .step-text {
		color: #ff5722;
	}
	.step-blue .step-circle {
		color: #fff; 
		background-color: blue;
		border-color: blue;
	}
	.step-blue .step-circle::before {
		color:blue;
	}
	.step-blue .step-text {
		color: blue;
	}

	.step-orange .step-circle {
		color: #fff;
		background-color: orange;
		border-color: orange;
	}
	.step-orange .step-circle::before {
		color:orange;
	}
	.step-orange .step-text {
		color: orange;
	}
</style>
<?php 
	$headerQry ="select 
		po_headers.*,
		sup_suppliers.supplier_name,
		geo_currencies.currency,

		sum(po_lines.line_value) order_amount,
		sum(po_lines.total_tax) total_tax,
		sum(po_lines.total) total,

		sup_supplier_sites.site_name
		from po_headers

		left join sup_suppliers on 
		sup_suppliers.supplier_id = po_headers.supplier_id

		left join sup_supplier_sites on 
		sup_supplier_sites.supplier_site_id = po_headers.supplier_site_id

		left join geo_currencies on 
			geo_currencies.currency_id = po_headers.po_currency
		
		left join po_lines on 
			po_lines.po_header_id = po_headers.po_header_id

		where po_headers.po_header_id='".$id."' 
		
		group by po_headers.po_header_id" ;
	$edit_data = $this->db->query($headerQry)->result_array();

	$poAmount = isset($edit_data[0]['total']) ? $edit_data[0]['total'] : "0.00";

	$approvalLevelqry = "select 
		org_approval_line.line_id,
		org_approval_line.user_id,
		org_approval_line.level_id,
		org_approval_line.from_amount,
		org_approval_line.to_amount,
		org_approval_levels.level_name,
		org_approval_levels.level_id,
		per_people_all.first_name,
		per_people_all.last_name
	from org_approval_line 

	left join org_approval_levels on 
		org_approval_levels.level_id = org_approval_line.level_id

	left join per_user on
		per_user.user_id = org_approval_line.user_id
	
	left join per_people_all on
		per_people_all.person_id = per_user.person_id

	where 
		org_approval_line.approver_type = 'PO' 
		and org_approval_line.level_id <= (select level_id from org_approval_line where 
		org_approval_line.approver_type = 'PO'
		and $poAmount between from_amount and to_amount)
	"; #group by org_approval_levels.level_id

	$getApprovalLevel = $this->db->query($approvalLevelqry)->result_array();	
?>

<div class="content"><!-- Content start-->
	<div class="card"><!-- Card start-->
		<div class="card-body">
			
			<div class="row">
				<div class="col-md-6">
					<legend class="h3">
						<b><?php echo $page_title;?></b>
					</legend>
				</div>

				<div class="col-md-6 text-right" >
					<a class="btn btn-primary" href="<?php echo base_url();?>purchase_order/managePurchaseOrder/view/<?php echo $id;?>" title="View Purchase Order" target="_blank">
						View Purchase Order <i class="fa fa-chevron-circle-right"></i>
					</a>
				</div>
			</div>

		
			<!-- PO Details start here -->
			<div class="row">
				<div class="col-md-12">
					<h5><b>PO Details</b></h5>
					<div class="row">
						<div class="col-md-6">

							<div class="row mb-2">
								<div class="col-md-6">PO Number</div>
								<div class="col-md-1">:</div>
								<div class="col-md-5"><?php echo $edit_data[0]['po_number'];?></div>
							</div> 

							<div class="row mb-2">
								<div class="col-md-6">Supplier Name</div>
								<div class="col-md-1">:</div>
								<div class="col-md-5"><?php echo $edit_data[0]['supplier_name'];?></div>
							</div>

							<div class="row mb-2">
								<div class="col-md-6">Supplier Site</div>
								<div class="col-md-1">:</div>
								<div class="col-md-5"><?php echo $edit_data[0]['site_name'];?></div>
							</div>

							<div class="row mb-2">
								<div class="col-md-6">Amount</div>
								<div class="col-md-1">:</div>
								<div class="col-md-5">
									<?php echo CURRENCY_SYMBOL;?> 
									<?php echo number_format($poAmount,DECIMAL_VALUE,'.','');?>
								</div>
							</div>
						</div>
						<div class='col-md-1'></div>
						<div class='col-md-5'>
							<div class="row mb-2">
								<div class="col-md-6">Purchase Order Date</div>
								<div class="col-md-1">:</div>
								<div class="col-md-5"><?php echo date(DATE_FORMAT,strtotime($edit_data[0]['po_date']));?></div>
							</div>

							<?php 
								if(isset($edit_data[0]['submission_date']) && $edit_data[0]['submission_date'] != NULL)
								{
									?>
									<div class="row mb-2">
										<div class="col-md-6">Submission Date</div>
										<div class="col-md-1">:</div>
										<div class="col-md-5"><?php echo date(DATE_FORMAT." ". $this->time,strtotime($edit_data[0]['submission_date']));?></div>
									</div>
									<?php 
								} 
							?>

							<?php 
								if(isset($edit_data[0]['approved_date']) && $edit_data[0]['approved_date'] != NULL)
								{
									?>
									<div class="row mb-2">
										<div class="col-md-6">Approved Date</div>
										<div class="col-md-1">:</div>
										<div class="col-md-5"><?php echo date(DATE_FORMAT." ". $this->time,strtotime($edit_data[0]['approved_date']));?></div>
									</div>
									<?php 
								} 
							?>
						
							<div class="row mb-2">
								<div class="col-md-6">PO Status</div>
								<div class="col-md-1">:</div>
								<div class="col-md-5"><?php echo $edit_data[0]['po_status'];?></div>
							</div>
							
						</div>	
					</div>
				</div>
			</div>
		    <!-- PO Details end here -->

			<!-- Step design 1 end here -->                        
			<div class="container_1 mt-3">	
				<div class="my-12">
					<div class="mb-5">
						<ul class="steps">
							<?php 
								$i=1;
								foreach($getApprovalLevel as $row)
								{
									/* $userApprovalsQry = "select org_approval_status.* from org_approval_status
										where
											user_id='".$row["user_id"]."' 
											and reference_id='".$id."' 
											and approval_type= 'PO'
											and level_id = '".$row['level_id']."'
											ORDER BY MAX(instances_id)
											
									"; */

									$userApprovalsQry = "
									select org_approval_status.* from org_approval_status 
									where 
									user_id='".$row["user_id"]."' 
									and reference_id='".$id."' 
									and approval_type= 'PO'
									and level_id = '".$row['level_id']."'

									and instances_id in 
									(
										select max(instances_id) from org_approval_status 
										where 
										user_id='".$row["user_id"]."' 
										and reference_id='".$id."' 
										and approval_type= 'PO'
										and level_id = '".$row['level_id']."'
									)";
									
									//echo $userApprovalsQry;

									
									$getUserApprovals = $this->db->query($userApprovalsQry)->result_array();
								
									$level_id = isset($getUserApprovals[0]["level_id"]) ? $getUserApprovals[0]["level_id"] :NULL;
									$approval_date = isset($getUserApprovals[0]["action_date"]) ? strtotime($getUserApprovals[0]["action_date"]) :NULL;
									$approval_remarks = isset($getUserApprovals[0]["approval_remarks"]) ? $getUserApprovals[0]["approval_remarks"] : NULL;
										
									$appovedSuccess = "";
									if(isset($getUserApprovals) && count($getUserApprovals) > 0)
									{
										if($getUserApprovals[0]["approval_status"] == 'Approved')
										{
											$appovedSuccess = "step-success";
										}
										else if($getUserApprovals[0]["approval_status"] == 'Rejected')
										{
											$appovedSuccess = "step-error";
										}
										else if($getUserApprovals[0]["approval_status"] == 'Info Requested')
										{
											$appovedSuccess = "step-blue";
										}
										else if($getUserApprovals[0]["approval_status"] == 'Pending')
										{
											$appovedSuccess = "step-orange";
										}
										else if($getUserApprovals[0]["approval_status"] == 'Withdrawn')
										{
											$appovedSuccess = "step-blue";
										}
										else
										{
											$appovedSuccess = "step-error";
										}
									}
									?>
									<li class="step <?php echo $appovedSuccess;?>">
										<div class="step-content">
											<span class="step-circle"><i class="fa fa-check"></i></span>
											<span class="step-text"><?php echo ucfirst($row["level_name"]);?></span>
											<span class="text-default">
												<?php 
													if($row["user_id"] == "-1")
													{
														?>
														Auto Approval
														<?php
													}
													else
													{
														echo $row["first_name"]." ".$row["last_name"];
													}
												?>
											</span>

											

											<?php
												if( count($getUserApprovals) > 0 )
												{
													?>
													<span class="text-default" style="font-size: 12px;color:#adb5bd;">
														<?php 
															echo $getUserApprovals[0]["approval_status"]."";
														?>
													</span>
													<?php 
														if($row["user_id"]  > 1 && !empty($approval_remarks))
														{
															?>
															<span class="text-default" style="font-size: 12px;color:#adb5bd;">
																Remarks : <?php echo ucfirst($approval_remarks);?>
															</span>
															<?php 
														}
													?>

													<?php  
														if($approval_date !=NULL)
														{
															?>
															<span class="text-default" style="font-size: 12px;color:#adb5bd;">
																<?php echo date("d-M-Y h:i:s a",$approval_date) ; ?>
															</span>
															<?php 
														}
													?>
													<?php 
												}
												
											?>
										</div>
									</li>
									<?php 
								} 
							?>
						</ul>
					</div>
				</div>
			</div>
			<!-- Step design 1 end here -->

			<!-- Approval submit form -->
			<?php 
				$loginUserId = $this->user_id;
				$referenceId = $id;

				$approvalStatusQry = "select approval_status_id, level_id from org_approval_status 
					where user_id = '".$loginUserId."' 
					and reference_id = '".$referenceId."' 
					and approval_type = 'PO' 
					and approval_status = 'Pending'";

				$checkApprovalStatus = $this->db->query($approvalStatusQry)->result_array();

				$approvalStatusQry1 = "select approval_status_id, MIN(level_id) as level_id from org_approval_status 
					where 1=1
					and reference_id = '".$referenceId."' 
					and approval_type = 'PO' 
					and approval_status = 'Pending'";
				$approvalStatusQry1 = $this->db->query($approvalStatusQry1)->result_array();

				$approvalStatusMaxQry = "select approval_status_id, MAX(level_id) as level_id from org_approval_status 
					where 1=1
					and reference_id = '".$referenceId."' 
					and approval_type = 'PO' 
					and approval_status = 'Pending'";
				$approvalMaxStatusQry1 = $this->db->query($approvalStatusMaxQry)->result_array();

				$user_level_id = isset($checkApprovalStatus[0]['level_id']) ? $checkApprovalStatus[0]['level_id'] : NULL;
				$min_level_id = isset($approvalStatusQry1[0]['level_id']) ? $approvalStatusQry1[0]['level_id'] : NULL;
				$max_level_id = isset($approvalMaxStatusQry1[0]['level_id']) ? $approvalMaxStatusQry1[0]['level_id'] : NULL;
				
				//echo count($checkApprovalStatus);
				if(count($checkApprovalStatus) > 0 && $user_level_id == $min_level_id)	
				{
					$approval_status_id = $checkApprovalStatus[0]["approval_status_id"];
					?>
					<hr>
					<form action="" class="form-validate-jquery" method="post">
						<input type="hidden" name="approval_status_id" value="<?php echo $approval_status_id;?>">
						<input type="hidden" name="reference_id" value="<?php echo $referenceId;?>">
						<input type="hidden" name="user_id" value="<?php echo $loginUserId;?>">

						<input type="hidden" name="user_level_id" value="<?php echo $user_level_id;?>">
						<input type="hidden" name="max_level_id" value="<?php echo $max_level_id;?>">

						<div class="row">
							<div class="col-md-4">
								<div class="row">
									<label class="col-form-label col-md-3 text-right">Action <span class="text-danger">*</span></label>
									<div class="form-group col-md-8">
										<select required name="approval_status" onchange="approvalStatus(this.value)" id="approval_status" class="form-control searchDropdown" >
											<option value="">- Select Action -</option>
											<?php
												foreach ($this->approvalStatus as $key => $value) 
												{
													?>
													<option value="<?php echo $key; ?>"><?php echo $value; ?></option>
													<?php
												} 
											?>
										</select>
									</div>
								</div>
							</div>

							<script>
								function approvalStatus(val)
								{
									if(val == "Approved")
									{
										$(".approve_btn").html("Approve");
									}
									else if(val == "Rejected")
									{
										$(".approve_btn").html("Reject");
									}
									else if(val == "Info Requested")
									{
										$(".approve_btn").html("Info Request");
									}
								}
							</script>
							
							<div class="col-md-8">
								<div class="row">
									<label class="col-form-label col-md-6 text-right"></label>
									<div class="form-group col-md-6">
										<a href="<?php echo base_url(); ?>admin/dashboard" class="btn btn-default btn-sm">Close</a>
										<button type="submit" name="approval_status_btn" id="submit" class="btn btn-primary approve_btn ml-1 btn-sm">Approve</button>
									</div>
								</div>
							</div>
						</div>

						<div class="row">
							<div class="col-md-4">
								<div class="row">
									<label class="col-form-label col-md-3 text-right">Remarks <span class="text-danger">*</span></label>
									<div class="form-group col-md-8">
										<textarea class="form-control" rows="1" required id="approval_remarks" name="approval_remarks"></textarea>
									</div>
								</div>
							</div>
						</div>
					</form>
					<?php 
				} 
			?>
			<!-- Approval submit form -->
		</div>

	</div>
</div>
