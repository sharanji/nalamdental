<!-- Start div class col-md-9 for Right side -->
<div class="col-md-9 col-sm-9 col-xs-12 length-catgry">
	<form action="" method="get">
		<div class="row">
			<div class="col-md-8">
				<div class="row">
					<div class="col-md-6">	
						<input type="search" autocomplete="off" class="form-control" name="keywords" value="<?php echo !empty($_GET['keywords']) ? $_GET['keywords'] :""; ?>" placeholder="Search...">
						<p class="search-note">Note : Bank Name, Bank A/c, Branch Name, IFSC Code, MICR Code.</p>
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
			<table id="myTable" class="table table-bordered table-hover --table-striped dataTable">
				<thead>
					<tr>
						<th></th>
						<th onclick="sortTable(2)" class="text-center">A/c No.</th>
						<th onclick="sortTable(2)">A/c Holder Name</th>
						<th onclick="sortTable(0)" style="width:20%;">Bank Name</th>
						<th onclick="sortTable(1)">Branch Name</th>
						<th onclick="sortTable(3)" class="text-center">IFSC Code</th>
						<!-- <th onclick="sortTable(4)">MICR Code</th> -->
						<th onclick="sortTable(5)" class="text-center">Primary A/c</th>
					</tr>
				</thead>
				<tbody>
					<?php 
						foreach($resultData as $row) 
						{
							?>
							<tr class="assign_<?php echo $row['bank_id']; ?>">
								<td class="text-center">
									<a title="Edit" data-toggle="modal" data-target="#exampleModal<?php echo $row['bank_id']; ?>" href="javascript:void(0)">
										<i class="fa fa-pencil"></i>
									</a>
									<?php /*
									<a title="Delete" href="javascript:void(0)" onclick="Delete(<?php echo $row['bank_id']; ?>)">
										<i class="fa fa-trash"></i>
									</a> */ ?>
								</td>
								
								<!-- Model dialog Start -->
								<div class="modal fade" id="exampleModal<?php echo $row['bank_id']; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
									<div class="modal-dialog modal-dialog-centered" role="document">
										<div class="modal-content">
											<div class="modal-header">
												<h5 class="modal-title" id="exampleModalLabel">Edit Bank Details</h5>
												<button type="button" class="close" data-dismiss="modal" aria-label="Close">
													<span aria-hidden="true">&times;</span>
												</button>
											</div>
											<form method="post">
												<div class="modal-body">
													<input type="hidden" name="bank_id" value="<?php echo $row['bank_id'];?>">
													<div class="row">
														<div class="form-group col-md-6">
															<label class="col-form-label">A/c No <span class="text-danger">*</span></label>
															<input type="text" name="account_number" id="account_number" autocomplete="off" value="<?php echo $row['account_number'];?>" class="form-control" required="">
														</div>

														<div class="form-group col-md-6">
															<label class="col-form-label">A/c Holder Name <span class="text-danger">*</span></label>
															<input type="text" name="account_name" id="account_name" autocomplete="off" value="<?php echo $row['account_name'];?>" class="form-control" required="">
														</div>
													</div>
													
													<div class="row">
														<div class="form-group col-md-6">
															<label class="col-form-label">Bank Name <span class="text-danger">*</span></label>
															<input type="text" name="bank_name" id="bank_name" autocomplete="off" value="<?php echo $row['bank_name'];?>" class="form-control" required="">
														</div>
														<div class="form-group col-md-6">
															<label class="col-form-label">Bank Branch <span class="text-danger">*</span></label>
															<input type="text" name="branch_name" id="branch_name" autocomplete="off" value="<?php echo $row['branch_name'];?>" class="form-control" required="">
														</div>
													</div>
													
													<div class="row">
														<div class="form-group col-md-6">
															<label class="col-form-label">IFSC Code <span class="text-danger">*</span></label>
															<input type="text" name="ifsc_code" id="ifsc_code" autocomplete="off" value="<?php echo $row['ifsc_code'];?>" class="form-control" required="">
															<span class="small note-color">(Ex : IDIB000A114)</span>
														</div>
														<div class="form-group col-md-6">
															<label class="col-form-label">MICR Code</label>
															<input type="text" name="micr_code" id="micr_code" autocomplete="off" value="<?php echo $row['micr_code'];?>" class="form-control">
															<span class="small note-color">(Ex : 600019003)</span>
														</div>
													</div>
													
													<div class="row">
														<div class="form-group col-md-6">
															<label class="col-form-label">Address</label>
															<textarea name="address" id="address" autocomplete="off" class="form-control"><?php echo $row['address'];?></textarea>
														</div>
													</div>

												</div>
												<div class="modal-footer">
													<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
													<button type="submit" name="edit" class="btn btn-primary">Update</button>
												</div>
											</form>
										</div>
									</div>
								</div>
								<!-- Model dialog End -->


								<td class="text-center"><?php echo $row['account_number']; ?></td>
								<td><?php echo $row['account_name']; ?></td>
								<td><?php echo ucfirst($row['bank_name']); ?></td>
								<td><?php echo ucfirst($row['branch_name']); ?></td>
								
								<td class="text-center"><?php echo $row['ifsc_code']; ?></td>
								<?php /*<td><?php echo $row['micr_code']; ?></td> */ ?>
								
								<td class="text-center">
									<?php 
										if($row['primary_bank'] == 1)
										{
											$checked="checked='checked'";
										}else{
											$checked="";
										}
									?>
											
									<input <?php echo $checked;?> type="radio" id="primary_bank" name="primary_bank" value="<?php echo $row['bank_id']."_"."1"; ?>">
									<?php 
										if($row['primary_bank'] == 1)
										{
											?>
											<span class="btn btn-outline-success btn-block btn-sm" title="Primary Bank"><i class="fa fa-check"></i> Primary</span>
											<?php
										}
									?>
								</td>
							</tr>
							<?php
						}
						if( count($resultData)>0 )
						{
							?>
							<tr>
								<td colspan="6"></td>
								<td class="text-center">
									<button type="submit" name="primary_account_add" class="btn btn-primary btn-sm btn-block">Update</button>
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
	</form>
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
/* function Delete(assign_id="")
{
	confirm = confirm("Do you want to delete");

	if (confirm) 
	{
		$.ajax({
			url: '<?php echo base_url();?>employee/AjaxDeleteAssign',
			type: 'POST',
			data: {
				assign_id : assign_id,
			},
			success: function(response)
			{ 
				$('.assign_'+assign_id).remove();
				toastr.success(response);
			}
		});
	}
} */
</script>