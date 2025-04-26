<style>
	.switch {
	  position: relative;
	  display: inline-block;
	  width: 90px;
	  height: 34px;
	}

	.switch input {display:none;}

	.slider {
	  position: absolute;
	  cursor: pointer;
	  top: 0;
	  left: 0;
	  right: 0;
	  bottom: 0;
	  background-color: #ca2222;
	  -webkit-transition: .4s;
	  transition: .4s;
	}

	.slider:before {
	  position: absolute;
	  content: "";
	  height: 26px;
	  width: 26px;
	  left: 4px;
	  bottom: 4px;
	  background-color: white;
	  -webkit-transition: .4s;
	  transition: .4s;
	}

	input:checked + .slider {
	  background-color: #2ab934;
	}

	input:focus + .slider {
	  box-shadow: 0 0 1px #2196F3;
	}

	input:checked + .slider:before {
	  -webkit-transform: translateX(55px);
	  -ms-transform: translateX(55px);
	  transform: translateX(55px);
	}

	/*------ ADDED CSS ---------*/
	.on
	{
	  display: none;
	}

	.on, .off
	{
	  color: white;
	  position: absolute;
	  transform: translate(-50%,-50%);
	  top: 50%;
	  left: 50%;
	  font-size: 10px;
	  font-family: Verdana, sans-serif;
	}

	input:checked+ .slider .on
	{display: block;}

	input:checked + .slider .off
	{display: none;}

	/*--------- END --------*/

	/* Rounded sliders */
	.slider.round {
	  border-radius: 34px;
	}

	.slider.round:before {
	  border-radius: 50%;}
</style>
<!-- Start div class col-md-9 for Right side -->
<div class="col-md-9 col-sm-9 col-xs-12 length-catgry">
	<form action="" method="get">
		<div class="row">
			<div class="col-md-8">
				<div class="row">
					<div class="col-md-6">	
						<input type="search" autocomplete="off" class="form-control" name="keywords" value="<?php echo !empty($_GET['keywords']) ? $_GET['keywords'] :""; ?>" placeholder="Search...">
						<p class="search-note">Note : Category Name.</p>
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
								$pageLimit = isset($_SESSION['PAGE']) ? $_SESSION['PAGE'] : "";
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

	<div -class="new-scroller">
		<table id="myTable" class="table table-bordered table-hover --table-striped dataTable">
			<thead>
				<tr>
					<th class="text-center" style="width:12%;">Controls</th>
					<th onclick="sortTable(0)" style="width:20%;">Element Name</th>
					<th onclick="sortTable(1)">Category Name</th>
					<th onclick="sortTable(2)" style="width:20%;">Deduction Status</th>
					<th onclick="sortTable(3)" class="text-center" style="width:10%;">Status</th>
				</tr>
			</thead>
			<tbody>
				<?php 
					foreach ($resultData as $row) 
					{
						?>
						<tr class="assign_<?php echo $row['emp_assign_id']; ?>">
							<td class="text-center">
								<a title="Edit" data-toggle="modal" data-target="#exampleModal<?php echo $row['emp_assign_id']; ?>" href="javascript:void(0)">
									<i class="fa fa-pencil"></i>
								</a>
								&nbsp;|&nbsp;
								<?php 
									if($row['assigned_status'] == 1)
									{
										?>
										<a href="<?php echo base_url(); ?>employee/ManageEmployee/payslip_status/<?php echo $id;?>/<?php echo $row['emp_assign_id'];?>/0" title="Inactive">
											<i class="fa fa-ban"></i> 
										</a>
										<?php 
									} 
									else
									{  ?>
										<a href="<?php echo base_url(); ?>employee/ManageEmployee/payslip_status/<?php echo $id;?>/<?php echo $row['emp_assign_id'];?>/1" title="Active">
											<i class="fa fa-check"></i> 
										</a>
										<?php 
									} 
								?>
								<?php /*
								<a title="Delete" href="javascript:void(0)" onclick="Delete(<?php echo $row['emp_assign_id']; ?>)">
									<i class="fa fa-trash"></i>
								</a> */ ?>
								<!-- Payslip popup Start -->
								<div class="modal fade" id="exampleModal<?php echo $row['emp_assign_id']; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
									<div class="modal-dialog modal-dialog-centered" role="document">
										<div class="modal-content">
										<div class="modal-header">
											<h5 class="modal-title" id="exampleModalLabel">Edit Pay Slips Category</h5>
											<button type="button" class="close" data-dismiss="modal" aria-label="Close">
												<span aria-hidden="true">&times;</span>
											</button>
										</div>
										<form method="post">
											<div class="modal-body">
												<div class="form-group">
													<div class="row">
														<div class="col-md-3">
															<label for="category_type">Category Type <span class="text-danger">*</span> </label>
														</div>
														<div class="col-md-6">
															<select name="category_type" id="category_type<?php echo $row['emp_assign_id'];?>" class="form-control searchDropdown-" required>
																<option value=""> - Select Category type - </option>
																<?php
																	foreach($this->paySlipCategoryType as $key => $category_type) 
																	{
																		$selected="";
																		if($row['category_type'] == $key)
																		{
																			$selected="selected='selected'";
																		}
																		?>
																		<option value="<?php echo $key;?>" <?php echo $selected; ?>><?php echo $category_type;?></option>
																		<?php
																	}
																?>
															</select>
														</div>
													</div>
												</div>
												<div class="form-group">
													<div class="row">
														<div class="col-md-3">
															<label for="category_id">Category <span class="text-danger">*</span> </label>
														</div>
														<div class="col-md-6">
															<?php
																$getQry = "select category_id,category_name from hr_payslip_categories where category_status=1 and category_type='".$row['category_type']."' ";
																$getPayslipCategories = $this->db->query($getQry)->result_array();
															?>
															<input type="hidden" name="emp_assign_id" value="<?php echo $row['emp_assign_id'];?>">
															<select name="category_id" class="form-control searchDropdown-" required id="category_id<?php echo $row['emp_assign_id'];?>">
																<option value=""> - Select Category - </option>
																<?php 
																	foreach($getPayslipCategories as $Categorie)
																	{
																		$selected="";
																		if($row['category_id'] == $Categorie["category_id"])
																		{
																			$selected="selected='selected'";
																		}
																		?>
																		<option value="<?php echo $Categorie["category_id"]; ?>" <?php echo $selected;?>><?php echo ucfirst($Categorie["category_name"]); ?></option>
																		<?php 
																	}
																?>
															</select>
														</div>
													</div>
												</div>
											</div>
											<div class="modal-footer">
												<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
												<button type="submit" name="edit" class="btn btn-primary">Assign</button>
											</div>
										</form>
										</div>
									</div>
								</div>
								
								<script>
									$('#category_type<?php echo $row["emp_assign_id"];?>').change(function () 
									{
										var categoryType = $('#category_type<?php echo $row["emp_assign_id"];?>').val();
										var category_id = $('#category_id<?php echo $row["emp_assign_id"];?>').val();
										
										$.ajax({
											url: '<?php echo base_url();?>employee/AjaxGetCategorys',
											type: 'POST',
											data: {
												category_type_id : categoryType,
											},
											success: function(result)
											{ 
												$('#category_id<?php echo $row["emp_assign_id"];?>').html(result);
											}
										});
									});
								</script>
								<!-- Payslip popup End -->
							</td>
							
							<td>
								<?php 
									foreach($this->paySlipCategoryType as $key => $value)
									{ 
										if( $row['category_type'] == $key)
										{
											if($row['category_type'] == 1)
											{
												?>
												<span class="btn btn-success"><?php echo $value;?></span>
												<?php
											}
											else if($row['category_type'] == 2)
											{
												?>
												<span class="btn btn-danger"><?php echo $value;?></span>
												<?php
											}
										}
									} 
								?>
							</td>
							<td>
								<?php 
									echo ucfirst($row['category_name']);
								?>	
							</td>
							<td class="text-center">
								<?php
									if ($row['category_type'] == 2 ) 
									{
										?>
										<label class="switch">
											<?php
												if ($row['deduction_status'] == 1 ) 
												{
													echo '<input name="deduction_status" data-id="'.$row['emp_assign_id'].'" type="checkbox" checked >';
												}
												else 
												{
													echo '<input name="deduction_status" data-id="'.$row['emp_assign_id'].'" type="checkbox">';
												}
											?>
											<div class="slider round">
												<span class="on">ON</span>
												<span class="off">OFF</span>
											</div>
										</label>
										<?php 
									}	
								?>
							</td>
							<td style="text-align:center;" class="tab-medium-width">
								<?php 
									if($row['assigned_status'] == 1)
									{
										?>
										<span class="btn btn-outline-success" title="Active"><i class="fa fa-check"></i> Active</span>
										<?php 
									} 
									else
									{  
										?>
										<span class="btn btn-outline-warning" title="Inactive"><i class="fa fa-close"></i> Inactive</span>
										<?php 
									} 
								?>
							</td>
						</tr>
						<?php
					}
				?>
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
	
	<div class="row">
		<div class="col-md-4 mt-2">
			Showing <?php echo $starting;?> to <?php echo $ending;?> of <?php echo $totalRows;?> entries
		</div>
		<!-- pagination start here -->
		<?php 
			if( isset($pagination) )
			{
				?>	
				<div class="col-md-8" class="admin_pagination" style="float:right;"><?php foreach ($pagination as $link){echo $link;} ?></div>
				<?php
			}
		?>
		<!-- pagination end here -->
	</div>
	<!-- End div class col-md-9 for Right side -->
</div>
	

<script>
$('[name="deduction_status"]').click(function () 
{
	var confirmBox = confirm('Are you sure you want to update this status?');

	if (confirmBox) 
	{
		var id = $(this).attr('data-id');
		
		$.ajax({
			url  : '<?php echo base_url();?>employee/ajaxDeductionPayslip',
			type : 'post',
			data : {
				id : id,
			},
			success: function(response)
			{   
				toastr.success('Employee deduction status updated successfully!');
			}
		});
	}
	else
	{
		location.reload();
	}
})
</script>
