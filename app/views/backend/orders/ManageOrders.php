<div class="content"><!-- Content start-->
	<div class="card"><!-- Card start-->
		<div class="card-body">


			<!-- buttons start here -->
			<div class="row mb-2">
				<div class="col-md-6"><h3><b>Manage Orders</b></h3></div>
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
						<div class="col-md-4">
							<div class="row">
								<label class="col-form-label col-md-4 text-right">Order No.</label>
								<div class="form-group col-md-7">
									<input type="search" name="order_number" placeholder="Order No." id="order_number" value="<?php echo isset($_GET['order_number']) ? $_GET['order_number'] : ""; ?>" class="form-control">
								</div>
							</div>
						</div>
						<div class="col-md-4">
							<div class="row">
								<label class="col-form-label col-md-4 text-right">Mobile Number</label>
								<div class="form-group col-md-7">
									<input type="search" name="mobile_number" placeholder="Mobile Number" id="mobile_number" value="<?php echo isset($_GET['mobile_number']) ? $_GET['mobile_number'] : ""; ?>" class="form-control form-control mobile_vali">
								</div>
							</div>
						</div>

						<input type="hidden" name="payment_type_id" id="payment_type_id" value="<?php echo isset($_GET['payment_type_id']) ? $_GET['payment_type_id'] : ""; ?>" class="form-control">
								
						
						<div class="col-md-4">
							<div class="row">
								<label class="col-form-label col-md-4 text-right">Order Status</label>
								<div class="form-group col-md-8">
									<?php 
										$listTypeValuesQry = "select 
										sm_list_type_values.list_type_value_id,
										sm_list_type_values.list_code,
										sm_list_type_values.list_value	
										from sm_list_type_values

										left join sm_list_types on 
										sm_list_types.list_type_id = sm_list_type_values.list_type_id
										where 
										sm_list_type_values.active_flag = 'Y' and 
										sm_list_types.list_name = 'ORDERSTATUS'"; 
										$orderStatus = $this->db->query($listTypeValuesQry)->result_array();
									?>
									<select style="width:150px;" name="order_status" id="order_status" class="form-control searchDropdown">
										<option value="All">All</option>
										<?php 
											foreach($orderStatus as $itemcategory)
											{
												$selected="";
												if(isset($_GET["order_status"]) && ($_GET["order_status"] == $itemcategory['list_value']) )
												{
													$selected="selected='selected'";
												}
												?>
												<option value="<?php echo $itemcategory['list_value']; ?>" <?php echo $selected;?>><?php echo $itemcategory['list_value']; ?></option>
												<?php 
											} 
										?>
									</select>
								</div>
							</div>
						</div>
					</div>

					<div class="row mt-2 mb-3">
						<div class="col-md-4">
							<div class="row">
								<label class="col-form-label col-md-4 text-right">From Date</label>
								<div class="form-group col-md-7">
									<input type="text" name="from_date" placeholder="From Date" readonly id="from_date" autocomplete="off" value="<?php echo isset($_GET['from_date']) ? $_GET['from_date'] : ""; ?>" class="form-control">
								</div>
							</div>
						</div>
						<div class="col-md-4">
							<div class="row">
								<label class="col-form-label col-md-4 text-right">To Date</label>
								<div class="form-group col-md-7">
									<input type="text" name="to_date" placeholder="To Date" readonly id="to_date" autocomplete="off" value="<?php echo isset($_GET['to_date']) ? $_GET['to_date'] : "" ;?>" class="form-control">
								</div>
							</div>
						</div>
						<div class="col-md-4 -float-right -text-right">
							<a href="<?php echo base_url(); ?>orders/manageOrders" title="Clear" class="btn btn-default">Clear</a>
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
										<th class="text-center">Action</th>
										<th class="text-center">Order Number</th>
										<th class="text-center">Order Date</th>
										<th class="text-center">Order Status</th>
										<th >Customer Name</th>
										<th class="text-center">Mobile Number</th>
										<th>Branch Name</th>
										<th class="text-right">
											Bill Amount <span style="font-size: 12px;"> (<?php echo CURRENCY_CODE;?>) </span>
										</th>
									</tr>
								</thead>
								<tbody --id="table_body">
									<?php 
										$page_data = array();
										echo $this->load->view('backend/orders/manageOrdersListing',$page_data,true);
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
