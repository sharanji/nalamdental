<div class="content"><!-- Content start-->
	<div class="card"><!-- Card start-->
		<div class="card-body">
			

			<!-- buttons start here -->
			<div class="row mb-2">
				<div class="col-md-6"><h3><b>Dine In Orders</b></h3></div>
			</div>
			<!-- buttons end here -->

			<div class="row mt-1 mb-3">
				<div class="col-md-6" style="font-size:14px;">
					<a href="javascript:void(0);" onclick="showFilter();">
						<i class="fa fa-filter" aria-hidden="true"></i> <b>Search</b>
					</a>
				</div>
			</div>

			<?php
				if( isset($_GET) && !empty($_GET))
				{
					$displaySearch = 'style="display:block;"';
				}
				else
				{
					$displaySearch = 'style="display:none;"';
				}
			?>

			<!-- Filters start here -->
			<div class="search-form" <?php #echo $displaySearch;?>>
				<form action="" class="form-validate-jquery" method="get" >
					<div class="row mt-3">
						<div class="col-md-3">
							<div class="row">
								<label class="col-form-label col-md-3 text-right">Branch</label>
								<div class="form-group col-md-9">
									<select name="branch_id" id="branch_id" onchange="ajaxSelectBranchTblLocation(this.value);" class="form-control searchDropdown">
										<?php 
											if($this->user_id == 1)
											{
												$branchQry = "select branch_id,branch_name from branch 
												where 
													active_flag='Y'";
												$getBranch = $this->db->query($branchQry)->result_array();
												?>
												<option value="">-  Select -</option>
												<?php
											}
											else
											{
												$branchQry = "select branch_id,branch_name from branch 
													where 
													branch_id='".$this->branch_id."' 
													and active_flag='Y'";
												$getBranch = $this->db->query($branchQry)->result_array();
											}

											foreach($getBranch as $row)
											{
												$selected="";
												if(isset($_GET["branch_id"]) && ($_GET["branch_id"] == $row['branch_id']) )
												{
													$selected="selected='selected'";
												}
												?>
												<option value="<?php echo $row['branch_id']; ?>" <?php echo $selected;?>><?php echo $row['branch_name']; ?></option>
												<?php 
											} 
										?>
									</select>
								</div>
							</div>
						</div>

						<script>
							function ajaxSelectBranchTblLocation(branch_id)
							{
								if(branch_id !='')
								{
									$.ajax({
										type: "POST",
										url:"<?php echo base_url().'dine_in/ajaxSelectTableLocations';?>",
										data: { id: branch_id }
									}).done(function( msg ) {   
										$( "#table_location_id" ).html(msg);
									});
								}
								else 
								{ 
									$( "#table_location_id" ).html("<option value=''>- Select -</option>");
								}
							}
						</script>

						<div class="col-md-4">
							<div class="row">
								<label class="col-form-label col-md-4 text-right">Table Location</label>
								<div class="form-group col-md-7">
									<select id="table_location_id" name="table_location_id" onchange="ajaxSelectLocationTables(this.value);"  class="form-control searchDropdown">
										<option value="">- Select - </option>
										<?php
											if($_GET["branch_id"] && !empty($_GET["branch_id"]))
											{
												$tableLocations =  $this->db->query("select 
												sm_list_type_values.list_value,
												sm_list_type_values.list_type_value_id 
												from din_table_headers
												left join sm_list_type_values on
												sm_list_type_values.list_type_value_id = din_table_headers.table_location_id
								
												where din_table_headers.branch_id='".$_GET["branch_id"]."'
												")->result_array();

												foreach($tableLocations as $itemcategory)
												{
													$selected="";
													if(isset($_GET['table_location_id']) && ($_GET['table_location_id'] == $itemcategory['list_type_value_id']) )
													{
														$selected="selected='selected'";
													}
													?>
													<option value="<?php echo $itemcategory['list_type_value_id']; ?>" <?php echo $selected;?>><?php echo $itemcategory['list_value']; ?></option>
													<?php 
												} 
											} 
										?>
									</select>
								</div>
							</div>
						</div>

						<script>
							function ajaxSelectLocationTables(table_location_id)
							{
								var branch_id = $("#branch_id").val();

								if(branch_id !='' && table_location_id !='')
								{
									$.ajax({
										type: "POST",
										url:"<?php echo base_url().'dine_in/ajaxSelectLocationTables';?>",
										data: { branch_id : branch_id, table_location_id : table_location_id }
									}).done(function( msg ) {   
										$("#tables").html(msg);
									});
								}
								else 
								{ 
									$("#tables").html("<option value=''>- Select -</option>");
								}
							}
						</script>
						<div class="col-md-4">
							<div class="row">
								<label class="col-form-label col-md-4 text-right">Table <!-- <span class="text-danger">*</span> --></label>
								<div class="form-group col-md-7">
									<select id="tables" name="tables" class="form-control searchDropdown">
										<option value="">- Select -</option>
										<?php
											if( ($_GET["branch_id"] && !empty($_GET["branch_id"])) && ($_GET["table_location_id"] && !empty($_GET["table_location_id"])) )
											{
												$tables =  $this->db->query("select 
												line_tbl.line_id,
												line_tbl.table_name
												
												from din_table_lines as line_tbl
												
												left join din_table_headers as header_tbl on
													header_tbl.header_id = line_tbl.header_id
												
												where 1=1
												and header_tbl.branch_id='".$_GET["branch_id"]."'
												and header_tbl.table_location_id='".$_GET["table_location_id"]."'
												and line_tbl.active_flag='Y'
												")->result_array();
										
												foreach($tables as $itemcategory)
												{
													$selected="";
													if(isset($_GET['tables']) && ($_GET['tables'] == $itemcategory['line_id']) )
													{
														$selected="selected='selected'";
													}
													?>
													<option value="<?php echo $itemcategory['line_id']; ?>" <?php echo $selected;?>><?php echo $itemcategory['table_name']; ?></option>
													<?php 
												} 
											} 
										?>
									</select>
								</div>
							</div>
						</div>

					</div>
					
					<div class="row mt-2">
						<div class="col-md-3">
							<div class="row">
								<label class="col-form-label col-md-3 text-right">Order #</label>
								<div class="form-group col-md-9">
									<input type="search" autocomplete="off" placeholder="Order #" name="order_number" id="order_number" value="<?php echo isset($_GET['order_number']) ? $_GET['order_number'] : ""; ?>" class="form-control">
								</div>
							</div>
						</div>

						<div class="col-md-4">
							<div class="row">
								<label class="col-form-label col-md-4 text-right"><span class="text-danger">*</span> From Date</label>
								<div class="form-group col-md-7">
									<input type="text" name="from_date" placeholder="From Date" required readonly id="from_date" autocomplete="off" value="<?php echo isset($_GET['from_date']) ? $_GET['from_date'] : ""; ?>" class="form-control">
								</div>
							</div>
						</div>
						
						<div class="col-md-4">
							<div class="row">
								<label class="col-form-label col-md-4 text-right"><span class="text-danger">*</span> To Date</label>
								<div class="form-group col-md-7">
									<input type="text" name="to_date" placeholder="To Date" required readonly id="to_date" autocomplete="off" value="<?php echo isset($_GET['to_date']) ? $_GET['to_date'] : "" ;?>" class="form-control">
								</div>
							</div>
						</div>
					</div>

					<div class="row mt-2">
						<div class="col-md-10 text-right">
							<a href="<?php echo base_url(); ?>dine_in/manageDineInOrders" title="Clear" class="btn btn-default">Clear</a>
							&nbsp;<button type="submit" class="btn btn-info">Search <i class="fa fa-search" aria-hidden="true"></i></button>
						</div>
					</div>

				</form>
			</div>
			<!-- Filters end here -->

			<?php 
				if(isset($_GET) && !empty($_GET))
				{
					?>
					<!-- Page Item Show start -->
					<div class="row mt-3">
						<div class="col-md-10">
						</div>
						<div class="col-md-2 float-right text-right">
							<?php 
								$redirect_url = substr($_SERVER['REQUEST_URI'],'1');
							?>
							<input type="hidden" id="redirect_url" value="<?php echo $redirect_url; ?>"/>
													
							<div class="filter_page">
								<label>
									<span>Show :</span> 
									<select name="filter" onchange="location.href='<?php echo base_url(); ?>admin/sort_itemper_page/'+$(this).val()+'?redirect=<?php echo $redirect_url; ?>'">
										<?php 
											$pageLimit = isset($_SESSION['PAGE']) ? $_SESSION['PAGE'] : NULL;
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
					<!-- Page Item Show start -->
					<form action="" method="post">
						<div class="new-scroller mt-3">
							<table class="table table-bordered table-hover">
								<thead>
									<tr>
										<th class="tab-md-120 text-center">Action</th>
										<th class="tab-md-120" >Order Number</th>
										<th  class="tab-md-140" >Order Date</th>
										<th  class="tab-md-120" >Order Status</th>
										<th  class="tab-md-150" >Branch Name</th>
										<th  class="tab-md-150" >Table Location</th>
										<th class="tab-md-100">Table</th>
										<th  class="tab-md-120" >Waiter</th>
										<th class="tab-md-100">Payment Due</th>
										<th class="tab-md-120 text-right">
											Bill Amount
										</th>
									</tr>
								</thead>
								<tbody>
									<?php 
										$i=0;
										$firstItem = isset($first_item) ? $first_item : 0;
										$totalgrandTotal=0;
										foreach($resultData as $row)
										{
											$orderStatus = $row['order_status'];
											?>
											<tr>
												<td class="text-center">
													<a target="_blank" href="<?php echo base_url();?>orders/viewOderDetails/<?php echo $row['header_id'];?>" target="_blank" title="View Order"><i class="fa fa-eye"></i></a>
													&nbsp;| &nbsp;
													<a target="_blank"  href="<?php echo base_url();?>orders/printReceipt/<?php echo $row['header_id'];?>" target="_blank" title="Print Receipt">
														<i class="order_view_icon fa fa-print"></i>
													</a>
													&nbsp;| &nbsp;
													<a target="_blank" href="<?php echo base_url();?>orders/kotPrint/<?php echo $row['header_id'];?>" title="KOT Print">
														KOT
													</a>
													<?php
														/* if($row['payment_due'] == 'Unpaid')
														{
															?>
																&nbsp;|&nbsp;
																<a class="payment_update" href="<?php echo base_url();?>dine_in/manageDineInOrders/payment_update/<?php echo $row['header_id'];?>/Paid" title="Payment Update">
																	<i class="fa fa-money" style="color:red"></i>
																</a>
																<input type="hidden" id="header_id" value="<?php echo $row['header_id'];?>">

															<?php
														} */
													?>

													<script>
														$(document).ready(function () {
															$('.payment_update').on('click', function (event) {
																event.preventDefault();
																const headerId = $('#header_id').val(); // Corrected to access the value

																// Display confirmation dialog
																Swal.fire({
																	title : 'Do you want to update the payment status?',
																	showCancelButton: true,
																	confirmButtonColor: '#070d7d',
																	cancelButtonColor: '#d33',
																	confirmButtonText: 'Yes'
																}).then((result) => {
																	if (result.isConfirmed) {
																		
																		window.location.href = '<?php echo base_url(); ?>pos/manageposOrders/payment_update/' + headerId + '/Paid';
																	} 
																	else {
																		
																	}
																});
															});
														});
													</script>

												</td>
												<td>
													<?php echo $row['order_number'];?>
												</td>

												<td>
													<?php echo date(DATE_FORMAT." ".$this->time,strtotime($row['ordered_date']));?>
												</td>
												<td>
													<?php 
														echo $row['order_status'];
														/* if($row['cancel_status'] == 'Y')
														{
															?>
															<span class="text-warning"><?php echo $row['order_status']; ?></span>
															<?php
														}
														else
														{
															echo $row['order_status'];
														} */
													?>
												</td>
												
												<td>
													<?php echo $row['branch_name'];?>
												</td>

												<td>
													<?php echo $row['table_location_name'];?>
												</td>

												<td>
													<?php echo $row['table_name'];?>
												</td>

												<td>
													<?php 
														if($row['waiter_id'] == 1)
														{
															?>
															Admin
															<?php
														}
														else
														{
															echo $row['waiter_name'];
														}
													?>
												</td>

												<td>
													<?php echo $row['payment_due'];?>
												</td>
												
												<td class="text-right">
													<?php #echo number_format($row['bill_amount'],DECIMAL_VALUE,'.','');?>
													<?php #echo $row['cancel_status'];?>
													<?php
														/* if($row['cancel_status'] == 'Y')
														{
															$bill_amount = 0;
														}
														else
														{
															#$totalTax += $lineItems['tax_value'];
															$bill_amount = round($row['linetotal'] + $row['tax_value']);
														} */
													
														$bill_amount = round($row['linetotal'] + $row['tax_value']);
														echo number_format($bill_amount,DECIMAL_VALUE,'.','');
													?>
												</td>
											</tr>

											<?php
											$i++;
										}
									?>
								</tbody>
							</table>
							
							<?php 
								if(count($resultData) == 0)
								{
									?>
									<div class="text-center">
										<img src="<?php echo base_url();?>uploads/nodata.png">
									</div>
									<?php 
								} 
							?>
						</div>
					</form>

					<?php 
						if(count($resultData) > 0)
						{
							?>
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
							<?php 
						} 
					?>
					<?php 
				} 
			?>

		</div><!-- Card body end-->
	</div><!-- Card end-->
</div><!-- Content end-->

<script>
	function askConfirm(id,val) 
	{ 
		var status_array = {
			<?php 
				foreach ($this->order_status as $key => $status) 
				{ 
					?>
						"<?php echo $key?>":"<?php echo $status?>",
					<?php
				}
			?>
		};
		var confrim = confirm(`Do you Want to change the Status to ${status_array[val]} ?`);

		if (confrim) {
			$.ajax({
				url: '<?php echo base_url();?>orders/ManageOrders/status/'+id+'/'+val,
				type: 'GET',
				data: {},
				success: function(response)
				{   
					location.reload();
				}
			});	
		}
	}
	/*
	setInterval(function()
		{
			//order_dashboard();
			
			$.ajax({
				url: '<?php //  echo base_url();?>orders/checkNewOrders',
				type: 'GET',
				data: {},
				success: function(result)
				{   
                    if (result > 0) 
                    {
                        AjaxappendTable();
                    }
				}
			});
			
			getcount(); 
		},
	);
	*/

	function AjaxappendTable() 
	{
		$.ajax({
			url: '<?php echo base_url();?>orders/AjaxappendTable',
			type: 'GET',
			data: {},
			success: function(result)
			{   
				data = JSON.parse(result);
				
				//$("#table_body").prependTo(data['newOrders']);
				
				$("#table_body").html(data['newOrders']);

				$.ajax({
					url: '<?php echo base_url();?>orders/AjaxNotification',
					type: 'post',
					data: {},
					success: function(result)
					{
						
					}
				});
			}
		});	
	}
</script>


<script type="text/javascript">  
	$('#select_all_1').on('click', function(e) 
	{
		if($(this).is(':checked',true)) 
		{
			$(".emp_checkbox_1").prop('checked', true);
		}
		else 
		{
			$(".emp_checkbox_1").prop('checked',false);
		}
		/* set all checked checkbox count
		$("#select_count").html($("input.emp_checkbox:checked").length+" Selected"); */
	});
	
	
	//Select all checkbox
	$('#select_all').on('click', function(e) 
	{
		if($(this).is(':checked',true)) 
		{
			$(".un-delete-btn").addClass('delete-btn');
			$('.delete-btn').removeClass('un-delete-btn');
			
			$(".emp_checkbox").prop('checked', true);
		}
		else 
		{
			$('.delete-btn').addClass('un-delete-btn');
			$(".un-delete-btn").removeClass('delete-btn');
			
			$(".emp_checkbox").prop('checked',false);
		}
		/* set all checked checkbox count
		$("#select_count").html($("input.emp_checkbox:checked").length+" Selected"); */
	});
	
	$('.emp_checkbox').on('click', function(e) 
	{
		//alert("sd");
		if($(this).is(':checked',true)) 
		{
			$(".un-delete-btn").addClass('delete-btn');
			$('.delete-btn').removeClass('un-delete-btn');
		}
		else 
		{
			$('.delete-btn').addClass('un-delete-btn');
			$(".un-delete-btn").removeClass('delete-btn');
		}
	});	
</script>
