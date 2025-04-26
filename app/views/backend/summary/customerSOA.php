<div class="content"><!-- Content start-->
	<div class="card"><!-- Card start-->
		<div class="card-body">

			<div class="row">
				<div class="col-md-6">
					<h3><b><?php echo $page_title;?></b></h3>
				</div>

													
			</div>

			<!-- Filters start here -->
			<div class="search-form">
				<form action="" class="form-validate-jquery" method="get" >
					
					<div class="row mt-2 mb-3">
						<div class="col-md-3">
							<div class="row">
								<label class="col-form-label col-md-4 text-right">Customer</label>
								<div class="form-group col-md-8">
									<div class="input-wrapper">
										<input type="search" name="customer_name" autocomplete="off" id="customer_name" value="<?php echo isset($_GET['customer_name']) ? $_GET['customer_name'] : NULL; ?>" placeholder="Customer Name" class="form-control">
										<?php /*<!--<input type="hidden" name="customer_id" autocomplete="off" id="customer_id" value="<?php echo isset($_GET['customer_id']) ? $_GET['customer_id'] : NULL; ?>" >
										 <div id="CustomerList"></div> --><!-- Clear icon start -->
										
										<?php 
											if(isset($_GET["customer_id"]) && !empty($_GET["customer_id"]))
											{
												$styleDisplay = "display:block";
											}else{
												$styleDisplay = "display:none";
											}
											?>
										<span class="customer_clear_icon" title="Clear" onclick="clearCustomerSearchKeyword();" style="<?php echo $styleDisplay;?>">
											<i class="fa fa-times" aria-hidden="true"></i>
										</span>

										<script>
											$(document).ready(function()
											{  
												$('#customer_name').keyup(function()
												{  
													var query = $(this).val();  

													if(query != '')  
													{  
														$.ajax({  
															url:"<?php echo base_url();?>customer/ajaxCustomerList",  
															method:"POST",  
															data:{query:query},  
															success:function(data)  
															{  
																$('#CustomerList').fadeIn();  
																$('#CustomerList').html(data);  
															}  
														});  
													}  
												});

												$(document).on('click', 'ul.list-unstyled-customer_id li', function()
												{  
													var value = $(this).text();
													
													if(value === "Sorry! Customer Not Found.")
													{
														$('#CustomerList').fadeOut();
													}
													else
													{
														$('#CustomerList').fadeOut();  
													}
												});
											});

											function getCustomerList(customer_id,customer_name)
											{
												$('.customer_clear_icon').show();
												if(customer_id == 0)	
												{
													$('#customer_id').val('0');
												}
												else
												{
													$('#customer_id').val(customer_id);
													$('#customer_name').val(customer_name);
												}
											}

											function clearCustomerSearchKeyword()
											{
												$(".customer_clear_icon").hide();
												$("#customer_id").val("");
												$("#customer_name").val("");
											}
										</script> */ ?>

									</div>
								</div>
							</div>
						</div>

						<div class="col-md-3">
							<div class="row">
								<label class="col-form-label col-md-4 text-right">Invoice #</label>
								<div class="form-group col-md-8">
									<input type="search" name="invoice_number" id="invoice_number" placeholder="Invoice Number" autocomplete="off" value="<?php echo isset($_GET['invoice_number']) ? $_GET['invoice_number'] : ""; ?>" class="form-control">
								</div>
							</div>
						</div>

						<div class="col-md-3">
							<div class="row">
								<label class="col-form-label col-md-4 text-right">* From Date</label>
								<div class="form-group col-md-8">
									<input type="text" name="from_date" placeholder="From Date" required readonly id="from_date" autocomplete="off" value="<?php echo isset($_GET['from_date']) ? $_GET['from_date'] : ""; ?>" class="form-control">
								</div>
							</div>
						</div>
						
						<div class="col-md-3">
							<div class="row">
								<label class="col-form-label col-md-4 text-right">* To Date</label>
								<div class="form-group col-md-8">
									<input type="text" name="to_date" readonly required placeholder="To Date" id="to_date" autocomplete="off" value="<?php echo isset($_GET['to_date']) ? $_GET['to_date'] : "" ;?>" class="form-control">
								</div>
							</div>
						</div>
						
					</div>
					<div class="row mb-3">
						<div class="offset-9 col-md-3 text-right">
							<button type="submit" class="btn btn-info">Search <i class="fa fa-search" aria-hidden="true"></i></button>
							&nbsp;<a href="<?php echo base_url(); ?>summary/customerSOA" title="Clear" class="btn btn-default">Clear</a>
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
					<div class="row">
						<div class="col-md-8 mt-3">
							<?php 
								if(count($resultData) > 0)
								{
									?>
									<a href="<?php echo base_url().$this->redirectURL.'&download_excel=download_excel'; ?>" target="_blank" title="Download Excel" class="btn btn-sm btn-primary">
										Download to Excel
									</a>
									<?php /* <a href="<?php echo base_url().$this->redirectURL.'&download_pdf=download_pdf'; ?>" target="_blank" title="Download PDF" class="btn btn-warning">Download PDF</a>
									*/ ?>
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
									<span style="color:blue;">Currency : <?php echo CURRENCY_CODE;?></span> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
											
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
									<th class="tab-md-120">Invoice Source</th>
									<th class="tab-md-120">Customer Name</th>
									<th class="tab-md-120">Invoice #</th>
									<th class="tab-md-120">Invoice Date</th>
									<th class="tab-md-120 text-right">Invoice Amount</th>
									<th class="tab-md-120 text-right">Paid Amount</th>
									<th class="tab-md-120 text-right">Balance</th>
									<th>Age (days)</th>
								</tr>
							</thead>
							<tbody>
								<?php 
									foreach($resultData as $row)
									{
										?>
										<tr>
											<td>
												<?php echo $row['invoice_source'];?>
											</td>

											<td>
												<?php echo $row['customer_name'];?>
											</td>

											<td>
												<?php echo $row['invoice_number'];?>
											</td>

											<td>
												<?php echo date("d-M-Y",strtotime($row['invoice_date']));?>
											</td>

											<td class="text-right">
												<?php echo number_format($row['sales_total'],DECIMAL_VALUE,'.','');?>
											</td>

											<td class="text-right">
												<?php echo number_format($row['paid_amount'],DECIMAL_VALUE,'.','');?>
											</td>

											<td class="text-right">
												<?php echo number_format($row['balance_amount'],DECIMAL_VALUE,'.','');?>
											</td>

											<td>
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
