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
<!-- fsdf -->
			<!-- Filters start here -->
			<div class="search-form">
				<form action="" class="form-validate-jquery" method="get" >
					<div class="row pt-3">
						<div class="col-md-3">
							<div class="row --pt-3">
								<label class="col-form-label col-md-4">SO Number</label>
								<div class="form-group col-md-8">
									<input type="search" class="form-control" autocomplete="off" name="order_number" value="<?php echo !empty($_GET['order_number']) ? $_GET['order_number'] :""; ?>" placeholder="PO Number">
								</div>
							</div>
						</div>
						<!-- <div class="row mt-2 mb-3"> -->
							<div class="col-md-3">
								<div class="row">
									<label class="col-form-label col-md-4 text-right"><span class="text-danger">*</span> From Date</label>
									<div class="form-group col-md-8">
										<input type="text" name="from_date" placeholder="From Date" required readonly id="from_date" autocomplete="off" value="<?php echo isset($_GET['from_date']) ? $_GET['from_date'] : ""; ?>" class="form-control">
									</div>
								</div>
							</div>
							<div class="col-md-3">
								<div class="row">
									<label class="col-form-label col-md-4 text-right"><span class="text-danger">*</span> To Date</label>
									<div class="form-group col-md-8">
										<input type="text" name="to_date" placeholder="To Date" required readonly id="to_date" autocomplete="off" value="<?php echo isset($_GET['to_date']) ? $_GET['to_date'] : "" ;?>" class="form-control">
									</div>
								</div>
							</div>
							<div class="col-md-3">
								<button type="submit" class="btn btn-info">Search <i class="fa fa-search" aria-hidden="true"></i></button>
								&nbsp;<a href="<?php echo base_url(); ?>report/rmSalesSummary" title="Clear" class="btn btn-default">Clear</a>
							</div>
						<!-- </div> -->
						
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
					</div>
					
						
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
