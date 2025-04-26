<!-- Page header start-->
<div class="page-header page-header-light">
	<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
		<div class="d-flex">
			<div class="breadcrumb">
				<a href="<?php echo base_url();?>admin/dashboard" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> <?php echo get_phrase('Home');?></a>
				<a href="<?php echo base_url();?>employee/ManageEmployee/grid_view" class="breadcrumb-item"><?php echo $page_title;?></a>
			</div>
		</div>


		<div class="new-import-btn report-export">
			<a href="<?php echo base_url(); ?>employee/ManageEmployee/grid_view" class="btn btn-info">
				Back
			</a>
		</div>
	</div>
</div>
<!-- Page header end-->

<div class="content"><!-- Content start-->

	<!-- employee Details start here -->
	<div class="card">
		<div class="card-body">
			<h3>Employee Details</h3>
			<div class="row">
				<div class="col-md-6">
					<?php /* <div class="row">
						<div class="col-md-5">Employee Role</div>
						<div class="col-md-1">:</div>
						<div class="col-md-6"><?php echo ucfirst($edit_data[0]['role_name']);?></div>
					</div> */?>

					<div class="row mt-2">
						<div class="col-md-5">Employee No</div>
						<div class="col-md-1">:</div>
						<div class="col-md-6"><?php echo isset($edit_data[0]['random_user_id']) ? $edit_data[0]['random_user_id']:"--";?></div>
					</div>

					<div class="row mt-2">
						<div class="col-md-5">Employee First Name</div>
						<div class="col-md-1">:</div>
						<div class="col-md-6"><?php echo ucfirst($edit_data[0]['first_name'])." ".ucfirst($edit_data[0]['last_name']);?></div>
					</div>

					<div class="row mt-2">
						<div class="col-md-5">Employee Last Name</div>
						<div class="col-md-1">:</div>
						<div class="col-md-6"><?php echo isset($edit_data[0]['last_name']) ? $edit_data[0]['last_name']:"--";?></div>
					</div>
				</div>

				<div class="col-md-6">
					<div class="row">
						<div class="col-md-5">Date of Birth</div>
						<div class="col-md-1">:</div>
						<div class="col-md-6"><?php echo isset($edit_data[0]['date_of_birth']) ? $edit_data[0]['date_of_birth']:"--";?></div>
					</div>

					<div class="row mt-2">
						<div class="col-md-5">Date of Joining</div>
						<div class="col-md-1">:</div>
						<div class="col-md-6"><?php echo isset($edit_data[0]['date_of_joining']) ? $edit_data[0]['date_of_joining']:"--";?></div>
					</div>

					<div class="row mt-2">
						<div class="col-md-5">Employeement Type</div>
						<div class="col-md-1">:</div>
						<div class="col-md-6">
							<?php 
								foreach($this->employeementType as $key => $value)
								{
									if( isset($edit_data[0]['employeement_type']) && $edit_data[0]['employeement_type'] == $key )
									{
										echo $value;
									}
								} 
							?>	
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- employee Details end here -->


	<div class="card"><!-- Card start-->
		<div class="card-body">
			
			<!-- <label class="text-capitalize" style="font-size:18px;color: #13111188;font-weight: 600;"><?php echo $type ?> Employee :</label> -->
			
			<form action="" method="get">
				<div class="row">
					
					<div class="col-md-8">
						<div class="row mb-2">
							<div class="col-md-4">	
								<input type="text" name="from_date" id="from_date" class="form-control" readonly value="<?php echo !empty($_GET['from_date']) ? $_GET['from_date'] :""; ?>" placeholder="Effective From Date">
							</div>

							<div class="col-md-4">	
								<input type="text" name="to_date" id="to_date" class="form-control" readonly value="<?php echo !empty($_GET['to_date']) ? $_GET['to_date'] :""; ?>" placeholder="Effective To Date">
							</div>

							<div class="col-md-3">
								<button type="submit" class="btn btn-info waves-effect">Search <i class="fa fa-search" aria-hidden="true"></i></button>
							</div>
						</div>
					</div>

					<div class="col-md-4 text-right">
						<?php 
							$redirect_url = substr($_SERVER['REQUEST_URI'],'1');
						?>
						<input type="hidden" id="redirect_url" value="<?php echo $redirect_url; ?>"/>
												
						<div class="filter_page">
							<label>
								<span>Show :</span> 
								<select name="filter" onchange="location.href='<?php echo base_url(); ?>admin/sort_itemper_page/'+$(this).val()+'?redirect=<?php echo $redirect_url; ?>'">
									<?php 
										$pageLimit = $_SESSION['PAGE'];
										foreach($this->items_per_page as $key => $value)
										{
											$selected="";
											if($key == $pageLimit){
												$selected="selected=selected";
											}
											?>
											<option value="<?php echo $key; ?>" <?php echo $selected;?>><?php echo $value; ?></option>
											<?php 
										} 
									?>
								</select>
							</label>
						</div>
					</div>
				</div>
			</form>	

			<form action="" method="post">
				<div class="new-scroller">
					<table id="myTable" class="table table-bordered table-hover dataTable">
						<thead>
							<tr>
								<th class="text-center"></th>
								<th class="text-center">Employee Code</th>
								<th>Employee Name</th>
								<th class="text-center">Effective From Date</th>
								<th class="text-center">Effective To Date</th>
								<th>Place</th>
								<th class="text-center">Created Date</th>
								<th class="text-center">Monthly Income</th>
								<th class="text-center">Total Compensation Per Annum</th>
							</tr>
						</thead>
						<tbody>
							<?php 	
								$i=0;
								$firstItem = $first_item;
								$empPerAnnumCtc = $totalCTC = $expiryCount = 0;
								foreach($resultData as $row)
								{
									$getLineQry = "select per_month_inr,per_annum_inr from  emp_salary_structure_line where header_id='".$row["header_id"]."' ";
									$getLineData = $this->db->query($getLineQry)->result_array();
								
									$per_month_inr = $per_annum_inr = 0;
									foreach($getLineData as $lineData)
									{
										$per_month_inr += $lineData["per_month_inr"];
										$per_annum_inr += $lineData["per_annum_inr"];
									}

									$currentDate = date("Y-m-d",time());

									$to_date = $row["to_date"];

									//echo $to_date."=".$currentDate;

									if($currentDate > $to_date)
									{
										$annexureExpired = 'style="background: #f74d4db3 !important;"';
										$expired = $type = 1; #none
									}
									else
									{
										$annexureExpired = '';
										$type = 2; #block
									}

									/* $condition = " org_projects_task_items.valid_from_date >= '".$actual_valid_from."' and 
								org_projects_task_items.valid_to_date <= '".$actual_valid_to."' "; */
									?>
									<tr <?php echo $annexureExpired; ?>>
										<td class="text-center">

											<?php 
												if($type == 2)
												{
													?>
													<?php 	
														$btnChecked = "";
														if($row['default_annexure'] == 1){
															$btnChecked = "checked=checked";
														}
														$expiry = 1;
													?>
													<input type="radio" class="default_annexure" <?php echo $btnChecked;?> id="default_annexure" name="default_annexure" value="<?php echo $row['header_id']; ?>">
													<?php 
												}
												else
												{
													$expiry = 0; #expired
													echo "--";
												}
											 ?>
											<?php 
												/* if($row['default_annexure'] == 1)
												{
													?>
													<i class="fa fa-check" aria-hidden="true" style="color:#0bc50b;font-size: 20px;"></i>
													<?php
												}
												else
												{
													?>
													<i class="fa fa-close" aria-hidden="true" style="color:red;font-size: 20px;"></i>
													<?php 
												}  */
											?>
										</td>

										<td class="tab-medium-width text-center"><?php echo $row['random_user_id'];?></td>
										<td class="tab-medium-width"><?php echo ucfirst($row['first_name']);?> <?php echo ucfirst($row['last_name']);?></td>
										
										<td class="tab-medium-width text-center">
												<?php echo date("d-M-Y",$row['string_from_date']);?>
										</td>

										<td class="tab-medium-width text-center">
											<?php 
												if ($row['string_to_date'] !=0 && $row['string_to_date'] !="") {
													echo date("d-M-Y", $row['string_to_date']);
												}
											?>
										</td>

										<td class="tab-medium-width">
											<?php echo ucfirst($row['place']);?>
										</td>

										<td class="tab-medium-width text-center"><?php echo date("d-M-Y",$row['created_date']);?></td>
										<td class="tab-medium-width text-right">
											<?php echo number_format($per_month_inr,DECIMAL_VALUE,'.','');?>
										</td>
										<td class="tab-medium-width text-right">
											<?php echo number_format($per_annum_inr,DECIMAL_VALUE,'.','');?>
										</td>
									</tr>
									<?php 
									$i++;
									$totalCTC += $per_month_inr;
									$empPerAnnumCtc += $per_annum_inr;
									$expiryCount   += $expiry;
								}
							?>
							<tr>
								<td>
									<?php 
									if($expiryCount > 0)
									{
										?>
											<button type="submit" name="default_submit" class="btn btn-primary btn-xs ml-3 float-left">Update</button>
										<?php
									}
									else
									{
										echo "";
									}
										
									?>
								</td>
								<td colspan="6" class="text-right">
									<b>Total :</b>
								</td>
								<td class="text-right">
									<?php echo number_format($totalCTC,DECIMAL_VALUE,'.','');?>
								</td>
								<td class="text-right">
									<?php echo number_format($empPerAnnumCtc,DECIMAL_VALUE,'.','');?>
								</td>
							</tr>
						</tbody>
					</table>
					<?php 
						if(count($resultData) == 0)
						{
							?>
							<p class="admin-no-data">No data found.</p>
							<?php 
						} 
					?>
				</div>
			</form>
			
			<div class="row">
				<div class="col-md-4 showing-count">
					Showing <?php echo $starting;?> to <?php echo $ending;?> of <?php echo $totalRows;?> entries
				</div>
				<!-- pagination start here -->
				<?php 
					if( isset($pagination) )
					{
						?>	
						<div class="col-md-8" class="admin_pagination" style="float:right;padding: 0px 20px 0px 0px;"><?php foreach ($pagination as $link){echo $link;} ?></div>
						<?php
					}
				?>
				<!-- pagination end here -->
			</div>	
		</div>
	</div><!-- Card end-->
</div><!-- Content end-->
	
