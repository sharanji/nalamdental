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
						<div class="col-md-3 text-right">
							<div class="row">
								<label class="col-form-label col-md-4">Supplier</label>
								<div class="form-group col-md-8">
									<?php
										$query1 = "select supplier_name,supplier_id from sup_suppliers
										where active_flag='Y' order by supplier_name asc
										";
										$getSupplier = $this->db->query($query1)->result_array();
									?>
									
									<select id="supplier_id" name="supplier_id" onchange="selectSupplierSite(this.value)" class="form-control searchDropdown">
										<option value="">- Select -</option>
										<?php 
											foreach($getSupplier as $Supplier)
											{
												$selected="";
												if(isset($_GET["supplier_id"]) && $_GET["supplier_id"] == $Supplier['supplier_id'] )
												{
													$selected="selected='selected'";
												}
												?>
												<option value="<?php echo $Supplier['supplier_id'];?>" <?php echo $selected;?>><?php echo ucfirst($Supplier['supplier_name']);?></option>
												<?php 
											} 
										?>
									</select>
								</div>
							</div>
						</div>

						<script>
							function selectSupplierSite(val)
							{
								if(val !='')
								{
									$.ajax({
										type: "POST",
										url:"<?php echo base_url().'purchase_order/ajaxSelectSupplierSite';?>",
										data: { id: val }
									}).done(function( msg ) {   
										$( "#supplier_site_id" ).html(msg);
									});
								}
								else 
								{ 
									$( "#supplier_site_id" ).html('<option value="">- Select -</option>');
								}
							}
						</script>

						<div class="col-md-3">
							<div class="row">
								<label class="col-form-label col-md-5 text-right">Supplier Site</label>
								<div class="form-group col-md-7">
									
									<select id="supplier_site_id" name="supplier_site_id" class="form-control searchDropdown">
										<option value="">- Select -</option>
										<?php 
											if(isset($_GET["supplier_id"]) && $_GET["supplier_id"] != "")
											{
												$supplier_id = $_GET["supplier_id"];

												$getSupplierSite =  $this->db->query("select 
												sup_supplier_sites.supplier_site_id,
												sup_supplier_sites.site_name from sup_supplier_sites
												where 
													sup_supplier_sites.supplier_id='".$supplier_id."' 
													order by sup_supplier_sites.site_name asc
												")->result_array();

												foreach($getSupplierSite as $SupplierSite)
												{
													$selected="";
													if(isset($_GET["supplier_site_id"]) && $_GET["supplier_site_id"] == $SupplierSite['supplier_site_id'] )
													{
														$selected="selected='selected'";
													}
													?>
													<option value="<?php echo $SupplierSite['supplier_site_id'];?>" <?php echo $selected;?>><?php echo ucfirst($SupplierSite['site_name']);?></option>
													<?php 
												} 
											}
										?>
									</select>
								</div>
							</div>
						</div>

						<div class="col-md-3">
							<div class="row">
								<label class="col-form-label col-md-4 text-right">* From Date</label>
								<div class="form-group col-md-8">
									<input type="text" name="from_date" required placeholder="From Date" readonly id="from_date" autocomplete="off" value="<?php echo isset($_GET['from_date']) ? $_GET['from_date'] : ""; ?>" class="form-control">
								</div>
							</div>
						</div>
						
						<div class="col-md-3">
							<div class="row">
								<label class="col-form-label col-md-4 text-right">* To Date</label>
								<div class="form-group col-md-8">
									<input type="text" name="to_date" required readonly placeholder="To Date" id="to_date" autocomplete="off" value="<?php echo isset($_GET['to_date']) ? $_GET['to_date'] : "" ;?>" class="form-control">
								</div>
							</div>
						</div>
					</div>

					<div class="row">
						<div class="col-md-9"></div>
						<div class="col-md-3 text-right">
							<button type="submit" class="btn btn-info">Search <i class="fa fa-search" aria-hidden="true"></i></button>
							&nbsp;
							<a href="<?php echo base_url(); ?>summary/supplierSOA" title="Clear" class="btn btn-default">Clear</a>
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
					<div class="row mt-2">
						<div class="col-md-8">
							<?php 
								if(count($resultData) > 0)
								{
									?>
									<a href="<?php echo base_url().$this->redirectURL.'&download_excel=download_excel'; ?>" target="_blank" title="Download Excel" class="btn btn-sm btn-primary">
										Download to Excel
									</a>
									<?php 
								} 
							?>
						</div>

						<div class="col-md-4 mb-3 text-right">
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
									<th class="tab-md-120">Supplier Name</th>
									<th class="tab-md-120">Site Name</th>
									<th class="tab-md-120">PO No</th>
									<th class="tab-md-120">Receipt No</th>
									<th class="tab-md-120">Receipt Date</th>
									<th class="tab-md-120 text-right">Receipt Amount</th>
									<th class="tab-md-120 text-right">Paid Amount</th>
									<th class="tab-md-120 text-right">Balance</th>
									<th class="tab-md-120">Age (days)</th>
								</tr>
							</thead>
							<tbody>
								<?php 
									foreach($resultData as $row)
									{
										?>
										<tr>
											<td class="tab-md-120" >
												<?php echo $row['supplier_name'];?>
											</td>
											<td class="tab-md-120" >
												<?php echo $row['site_name'];?>
											</td>

											<td class="tab-md-120" >
												<?php echo $row['po_number'];?>
											</td>

											<td class="tab-md-120" >
												<?php echo $row['receipt_number'];?>
											</td>

											<td class="tab-md-120" >
												<?php echo date("d-M-Y",strtotime($row['receipt_date']));?>
											</td>

											<td class="tab-md-120 text-right">
												<?php echo number_format($row['sales_total'],DECIMAL_VALUE,'.','');?>
											</td>

											<td class="tab-md-120 text-right">
												<?php echo number_format($row['paid_amount'],DECIMAL_VALUE,'.','');?>
											</td>

											<td class="tab-md-120 text-right">
												<?php echo number_format($row['balance_amount'],DECIMAL_VALUE,'.','');?>
											</td>

											<td class="tab-md-120">
												<?php echo $row['age'];?>
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
