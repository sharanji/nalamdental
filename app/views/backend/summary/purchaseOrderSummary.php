<div class="content"><!-- Content start-->
	<div class="card"><!-- Card start-->
		<div class="card-body">

			<div class="row">
				<div class="col-md-6">
					<h3><b><?php echo $page_title;?></b></h3>
				</div>

				<div class="col-md-6 text-right">
					<h5><b>Currency : <?php echo CURRENCY_CODE;?> </b></h5>
				</div>									
			</div>

			<!-- Filters start here -->
			<div class="search-form">
				<form action="" class="form-validate-jquery" method="get" >
					<div class="row">
						<div class="col-md-4">
							<div class="row pt-2">
								<label class="col-form-label col-md-5">PO Number</label>
								<div class="form-group col-md-7">
									<input type="search" class="form-control" autocomplete="off" name="po_number" value="<?php echo !empty($_GET['po_number']) ? $_GET['po_number'] :""; ?>" placeholder="PO Number">
								</div>
							</div>
						</div>
						
						<div class="col-md-4">
							<div class="row">
								<label class="col-form-label col-md-5 organization_id"><span class="text-danger">*</span> Organization</label>
								<div class="form-group col-md-7">
									<?php
										$getOrganization = $this->organization_model->getOrgAll();
									?>
									
									<select id="organization_id" onchange="getBranches(this.value);" name="organization_id" class="form-control searchDropdown" required>
										<option value="">- Select -</option>
										<?php 
											foreach($getOrganization as $row)
											{
												$selected="";
												if(isset($_GET["organization_id"]) && $_GET["organization_id"] == $row['organization_id'] )
												{
													$selected="selected='selected'";
												}
												?>
												<option value="<?php echo $row['organization_id'];?>" <?php echo $selected;?>><?php echo $row['organization_code'];?> - <?php echo $row['organization_name'];?></option>
												<?php 
											} 
										?>
									</select>
								</div>
							</div>
						</div>

						<div class="col-md-4">
							<div class="row">
								<label class="col-form-label col-md-5 branch_id"><span class="text-danger">*</span> Branch Name</label>
								<div class="form-group col-md-7">
									
									
									<select id="branch_id" name="branch_id" class="form-control searchDropdown" required>
										<option value="">- Select -</option>
										<?php

											if(isset($_GET) &&  !empty($_GET)){
												$organization_id=$_GET['organization_id'];

												$getBranches = $this->branches_model->getOrgBranch($organization_id);
												
												foreach($getBranches as $branch)
												{
													$selected="";
													if(isset($_GET["branch_id"]) && $_GET["branch_id"] == $branch['branch_id'] )
													{
														$selected="selected='selected'";
													}
													?>
													<option value="<?php echo $branch['branch_id'];?>" <?php echo $selected;?>><?php echo $branch['branch_name'];?></option>
													<?php 
												} 
											}
											
										?>
									</select>

									<script>
										function getBranches(organization_id)
										{	
											
											$.ajax({
												type: "POST",
												url: "<?php echo base_url().'branches/getOrgBranches';?>",
												data: {organization_id},
												success: function (data) {
													$('#branch_id').html(data);  
												}
											});

										}
									</script>
								</div>
							</div>
						</div>
					</div>
					<div class="row mt-2">
						<div class="col-md-4">
							<div class="row pt-2">
								<label class="col-form-label col-md-5">PO Status</label>
								<div class="form-group col-md-7">
									<?php 
										$poStatusQry = "select sm_list_type_values.list_code,sm_list_type_values.list_value,sm_list_type_values.list_type_value_id from sm_list_type_values 
										left join sm_list_types on sm_list_types.list_type_id = sm_list_type_values.list_type_id
										where 

										sm_list_types.active_flag='Y' and 
										coalesce(sm_list_types.start_date,'".$this->date."') <= '".$this->date."' and 
										coalesce(sm_list_types.end_date,'".$this->date."') >= '".$this->date."' and
										sm_list_types.deleted_flag='N' and


										sm_list_type_values.active_flag='Y' and 
										coalesce(sm_list_type_values.start_date,'".$this->date."') <= '".$this->date."' and 
										coalesce(sm_list_type_values.end_date,'".$this->date."') >= '".$this->date."' and
										sm_list_type_values.deleted_flag='N' and 

										sm_list_types.list_name = 'POSTATUS'
										order by sm_list_type_values.order_sequence asc";

										$poStatus = $this->db->query($poStatusQry)->result_array(); 
									?>
									
									<select name="po_status" id="po_status" class="form-control searchDropdown">
										<option value="">- Select -</option>
										<?php 
											foreach($poStatus as $row)
											{
												$selected="";
												if(isset($_GET['po_status']) && $_GET['po_status'] == $row["list_value"] )
												{
													$selected="selected='selected'";
												}
												?>
												<option value="<?php echo $row["list_value"];?>" <?php echo $selected;?>><?php echo ucfirst($row["list_value"]);?></option>
												<?php 
											}
										?>
									</select>
								</div>
							</div>
						</div>
						<div class="col-md-4">
							<div class="row">
								<label class="col-form-label col-md-5 text-right"><span class="text-danger">*</span> From Date</label>
								<div class="form-group col-md-7">
									<input type="text" name="from_date" placeholder="From Date" required readonly id="from_date" autocomplete="off" value="<?php echo isset($_GET['from_date']) ? $_GET['from_date'] : ""; ?>" class="form-control">
								</div>
							</div>
						</div>
						<div class="col-md-4">
							<div class="row">
								<label class="col-form-label col-md-5 text-right"><span class="text-danger">*</span> To Date</label>
								<div class="form-group col-md-7">
									<input type="text" name="to_date" placeholder="To Date" required readonly id="to_date" autocomplete="off" value="<?php echo isset($_GET['to_date']) ? $_GET['to_date'] : "" ;?>" class="form-control">
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12 text-right float-right">
							<button type="submit" class="btn btn-info">Search <i class="fa fa-search" aria-hidden="true"></i></button>
							&nbsp;<a href="<?php echo base_url(); ?>summary/purchaseOrderSummary" title="Clear" class="btn btn-default">Clear</a>
						</div>
					</div>
				</form>
			</div>
			<!-- Filters end here -->

			<?php 
				if(isset($_GET) && !empty($_GET))
				{
					$Total_Order_Amount = isset($cardResult[0]["Total_Order_Amount"]) ? $cardResult[0]["Total_Order_Amount"] : 0;
					$Total_Cancelled_Amount = isset($cardResult[0]["Total_Cancelled_Amount"]) ? $cardResult[0]["Total_Cancelled_Amount"] : 0;
					?>
					
					<!-- Page Item Show start -->
					<div class="row">
						<div class="col-md-8 mt-2">
							<?php 
								if(count($resultData) > 0)
								{
									?>
									<a href="<?php echo base_url().$this->redirectURL.'&download_excel=download_excel'; ?>" target="_blank" title="Download Excel" class="btn btn-primary btn-sm">Download Excel</a>
									<?php 
								} 
							?>
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
					<!-- Page Item end start -->
					
					<div class="new-scroller">
						<table class="table table-bordered table-hover">
							<thead>
								<tr>
									<th>PO #</th>
									<th class="tab-md-120">Organization</th>
									<th class="tab-md-150">Branch</th>
									<th class="tab-md-150">Item Name</th>
									<th class="tab-md-120">Item Desc</th>
									<th class="tab-md-120">Item Category</th>
									<th class="tab-md-120">Status</th>
									<th class="tab-md-120">PO Date</th>
									<th class="tab-md-50">UOM</th>
									<th class="tab-md-50">Qty</th>
									<th class="tab-md-80 text-right">Base Price</th>
									<th class="tab-md-80 text-right">Tax</th>
									<th class="tab-md-80 text-right">Discount</th>
									<th class="tab-md-80 text-right">Total</th>
								</tr>
							</thead>
							<tbody>
								<?php 
									foreach($resultData as $row)
									{
										?>
										<tr>
											<td>
												<?php echo $row['po_number'];?>
											</td>
											<td>
												<?php echo $row['organization_name'];?>
											</td>
											<td>
												<?php echo $row['branch_name'];?>
											</td>
											<td>
												<?php echo $row['item_name'];?>
											</td>
											<td>
												<?php echo $row['item_description'];?>
											</td>
											<td>
												<?php echo $row['category_name'];?>
											</td>
											<td class="tab-md-120" >
												<?php echo $row['line_status'];?>
											</td>
											<td>
												<?php echo date("d-M-Y",strtotime($row['created_date']));?>
											</td>
											<td>
												<?php echo $row['uom_code'];?>
											</td>
											<td>
												<?php echo $row['quantity'];?>
											</td>
											<td class="text-right">
												<?php echo number_format($row['base_price'],DECIMAL_VALUE,'.','');?>
											</td>
											<td class="text-right">
												<?php echo number_format($row['total_tax'],DECIMAL_VALUE,'.','');?>
											</td>
											<td class="text-right">
												<?php 
													if($row['discount_type'] == 'Percentage')
													{
														echo number_format($row['basetotal'],DECIMAL_VALUE,'.','');
													}
													else
													{
														$eachDisc = $row['base_price'] - $row['price'];
														echo number_format($eachDisc,DECIMAL_VALUE,'.','');
													}
												?>
											</td>
											<td class="text-right">
												<?php echo number_format($row['total'],DECIMAL_VALUE,'.','');?>
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
								<div class="text-center">
									<img src="<?php echo base_url();?>uploads/nodata.png">
								</div>
								<?php 
							} 
						?>
					</div>
					

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
