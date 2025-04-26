<style>
	button.un-delete-btn 
	{
		background: #999999;
		cursor: not-allowed !important;
		pointer-events: none;
		width: 100%;
	}
</style>

<script src="<?php echo base_url();?>assets/backend/jspm/JSPrintManager.js"></script>
<?php /* <script src="<?php echo base_url();?>assets/backend/jspm/zip-full.min.js"></script>*/ ?>

<script>
    var clientPrinters = null;
	var _this = this;
	
	JSPM.JSPrintManager.license_url = "<?php echo base_url();?>jspm/index.php";
	
	//WebSocket settings
	JSPM.JSPrintManager.auto_reconnect = true;
	JSPM.JSPrintManager.start();

    function jspmWSStatus() 
    {
        if (JSPM.JSPrintManager.websocket_status == JSPM.WSStatus.Open)
        {   
            return true;
        }
        else if (JSPM.JSPrintManager.websocket_status == JSPM.WSStatus.Closed) 
        {
            console.warn('JSPrintManager (JSPM) is not installed or not running! Download JSPM Client App from https://neodynamic.com/downloads/jspm');
            return false;
        }
        else if (JSPM.JSPrintManager.websocket_status == JSPM.WSStatus.Blocked) 
        {
            alert('JSPM has blocked this website!');
            return false;
        }
    }
</script>


<div class="content"><!-- Content start-->
	<div class="card"><!-- Card start-->
		<div class="card-body">
			<!-- buttons start here -->
			<div class="row mb-2">
				<div class="col-md-6"><h3><b>Open Orders</b></h3></div>
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
			<div class="search-form" <?php echo $displaySearch;?>>
				<form action="" class="form-validate-jquery" method="get" >
					<div class="row mt-3">
						<div class="col-md-4">
							<div class="row">
								<label class="col-form-label col-md-4 text-right">Order No.</label>
								<div class="form-group col-md-7">
									<input type="search" name="order_number" id="order_number" value="<?php echo isset($_GET['order_number']) ? $_GET['order_number'] : ""; ?>" class="form-control" placeholder="Order No.">
								</div>
							</div>
						</div>
						<div class="col-md-4">
							<div class="row">
								<label class="col-form-label col-md-4 text-right">Mobile Number</label>
								<div class="form-group col-md-7">
									<input type="search" name="mobile_number" id="mobile_number" placeholder="9999999999" value="<?php echo isset($_GET['mobile_number']) ? $_GET['mobile_number'] : ""; ?>" maxlength="10" class="form-control mobile_vali">
								</div>
							</div>
						</div>

						<div class="col-md-4">
							<div class="row">
								<label class="col-form-label col-md-4 text-right">Payment Type</label>
								<div class="form-group col-md-8">
									<?php 
										$paymentTypesQry = "select payment_type_id,payment_type from pay_payment_types 
													where active_flag='Y'
													order by pay_payment_types.payment_type asc";

										$paymentTypes = $this->db->query($paymentTypesQry)->result_array();	
									?>
									<select style="width:150px;" name="payment_type_id" id="payment_type_id" class="form-control searchDropdown">
										<option value="">- Select -</option>
										<?php 
											foreach($paymentTypes as $row)
											{
												$selected="";
												if(isset($_GET['payment_type_id']) && $_GET['payment_type_id'] == $row["payment_type_id"] )
												{
													$selected="selected='selected'";
												}
												?>
												<option value="<?php echo $row["payment_type_id"];?>" <?php echo $selected;?>><?php echo ucfirst($row["payment_type"]);?></option>
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
						<div class="col-md-4 float-right text-right">
							<a href="<?php echo base_url(); ?>orders/openOrders?order_number=&mobile_number=&payment_type_id=&from_date=&to_date=" title="Clear" class="btn btn-default">Clear</a>
							&nbsp;<button type="submit" class="btn btn-info">Search <i class="fa fa-search" aria-hidden="true"></i></button>
						</div>
					</div>
				</form>
			</div>
			<!-- Filters end here -->

			<!-- Card filter start here -->
			<?php 
				$bookedCount = $bookedCount[0]["bookedCount"];
				$confirmedCount = $confirmedCount[0]["confirmedCount"];
				$preparingCount = $preparingCount[0]["preparingCount"];
				$shippedCount = $shippedCount[0]["shippedCount"];
				$deliveredCount = $deliveredCount[0]["deliveredCount"];
				$totalOrdersCount = $totalOrdersCount[0]["totalOrdersCount"];
			?>
			<div class="row">
				<div class="col-md-2 ">
					<a title="Booked" href="<?php echo base_url(); ?>orders/openOrders?order_number=&mobile_number=&payment_type_id=&from_date=&to_date=&order_status=Booked">
						<div class="card card-border">
							<div class="card-body booked">
								<h4 class="text-center"><b>Booked</b></h4>
								<p class="bookedCount"><?php echo $bookedCount;?></p>
							</div>
						</div>
					</a>
				</div>
				<div class="col-md-2">
					<a title="Confirmed" href="<?php echo base_url(); ?>orders/openOrders?order_number=&mobile_number=&payment_type_id=&from_date=&to_date=&order_status=Confirmed">
						<div class="card card-border">
							<div class="card-body confirmed">
								<h4 class="text-center"><b>Confirmed</b></h4>
								<p class="confirmedCount"><?php echo $confirmedCount;?></p>
							</div>
						</div>
					</a>
				</div>
				<div class="col-md-2">
					<a title="Preparing" href="<?php echo base_url(); ?>orders/openOrders?order_number=&mobile_number=&payment_type_id=&from_date=&to_date=&order_status=Preparing">
						<div class="card card-border">
							<div class="card-body preparing">
								<h4 class="text-center"><b>Preparing</b></h4>
								<p class="preparingCount"><?php echo $preparingCount;?></p>
							</div>
						</div>
					</a>
				</div>

				<div class="col-md-2">
					<a title="Shipped" href="<?php echo base_url(); ?>orders/openOrders?order_number=&mobile_number=&payment_type_id=&from_date=&to_date=&order_status=Shipped">
						<div class="card card-border">
							<div class="card-body shipped">
								<h4 class="text-center"><b>Shipped</b></h4>
								<p class="shippedCount"><?php echo $shippedCount;?></p>
							</div>
						</div>
					</a>
				</div>
				<div class="col-md-2">
					<a title="Delivered" href="<?php echo base_url(); ?>orders/openOrders?order_number=&mobile_number=&payment_type_id=&from_date=&to_date=&order_status=Delivered">
						<div class="card card-border">
							<div class="card-body delivered">
								<h4 class="text-center"><b>Delivered</b></h4>
								<p class="deliveredCount"><?php echo $deliveredCount;?></p>
							</div>
						</div>
					</a>
				</div>
				<div class="col-md-2">
					<a title="Total Orders" href="<?php echo base_url(); ?>orders/openOrders?order_number=&mobile_number=&payment_type_id=&from_date=&to_date=&order_status=Total_Orders">
						<div class="card card-border">
							<div class="card-body">
								<h4 class="text-center"><b>Total Orders</b></h4>
								<p class="totalOrdersCount"><?php echo $totalOrdersCount;?></p>
							</div>
						</div>
					</a>
				</div>
			</div>
			<!-- Card filter end here -->

			<form action="" method="post">
				<?php
					if(isset($_GET["order_status"]) && !empty($_GET["order_status"]))
					{
						?>
				
						<?php 
							#Booked
							$bookedQuery = "select header_id from ord_order_headers 
								where 
									order_status = 'Booked'";
							$bookedResult = $this->db->query($bookedQuery)->result_array();
							$bookedStatus = count($bookedResult);

							#Confirmed
							$ConfirmedQuery = "select header_id from ord_order_headers 
								where 
									order_status = 'Confirmed'";
							$ConfirmedResult = $this->db->query($ConfirmedQuery)->result_array();
							$ConfirmedStatus = count($ConfirmedResult);
							
							#Preparing
							$PreparingQuery = "select header_id from ord_order_headers 
								where 
									order_status = 'Preparing'";
							$PreparingResult = $this->db->query($PreparingQuery)->result_array();
							$PreparingStatus = count($PreparingResult);
							
							#Shipped
							$ShippedQuery = "select header_id from ord_order_headers 
								where 
									order_status = 'Shipped'";
							$ShippedResult = $this->db->query($ShippedQuery)->result_array();
							$ShippedStatus = count($ShippedResult);

							#deliver
							$deliveredQuery = "select header_id from ord_order_headers 
								where 
									order_status = 'Delivered'
									and paid_status= 'Y'";
							$deliveredResult = $this->db->query($deliveredQuery)->result_array();
							$deliveredStatus = count($deliveredResult);
						?>
						
						<!-- multicheck start here-->
						<div class="action_buttons">
							<div class="row mt-3 mb-3 m-0">
								<?php
									if( $bookedStatus > 0 || $ConfirmedStatus > 0 || $PreparingStatus > 0 || $ShippedStatus > 0 || $deliveredStatus > 0)
									{
										?>
										<?php
											/* if($bookedStatus > 0 && $_GET["order_status"] == "Booked" || $_GET["order_status"] == "Total_Orders" )
											{
												?>
												<div class="cancel-item-new confirm_btn_old" style="cursor: not-allowed;">
													<button class="btn btn-danger un-delete-btn" style="" type="submit" name="confirmOrder" value="delete" onclick="return confirm('Are you sure you want to confirm this order?');">
														Confirm	
													</button>
												</div>&nbsp;&nbsp;
												<?php
											} */
										?>
										
										<?php
											if( $ConfirmedStatus  > 0 && $_GET["order_status"] == "Confirmed" )
											{
												?>
												<div class="cancel-item-new" style="cursor: not-allowed;">
													<button class="btn btn-danger un-delete-btn" style="" type="submit" name="preparingOrder" value="preparing" onclick="return confirm('Are you sure you want to preparing this order?');">
														Preparing
													</button>
												</div>&nbsp;&nbsp;
												<?php
											}

											if( $PreparingStatus  > 0 && $_GET["order_status"] == "Preparing")
											{
												?>
												<div class="cancel-item-new" style="cursor: not-allowed;">
													<button class="btn btn-danger un-delete-btn" style="" type="submit" name="shippedOrder" value="shipping" onclick="return confirm('Are you sure you want to ship this order?');">
														Shipped
													</button>
												</div>&nbsp;&nbsp;
												<?php
											}
										?>
										
										<?php
											if( $ShippedStatus > 0 && $_GET["order_status"] == "Shipped" )
											{
												?>
												<div class="cancel-item-new" style="cursor: not-allowed;">
													<button class="btn btn-danger un-delete-btn" type="submit" name="deliverOrder" value="deliver" onclick="return confirm('Are you sure you want to deliver this order?');">
														Deliver
													</button>
												</div>&nbsp;&nbsp;
												<?php
											}
										?>

										<?php
											if( $deliveredStatus > 0 && $_GET["order_status"] == "Delivered" )
											{
												?>
												<div class="cancel-item-new" style="cursor: not-allowed;">
													<button class="btn btn-danger un-delete-btn" type="submit" name="closedOrder" value="deliver" onclick="return confirm('Are you sure you want to close this order?');">
														Close
													</button>
												</div>&nbsp;&nbsp;
												<?php
											}
										?>
										
										<?php
											if($_GET["order_status"] != "Delivered")
											{
												?>
												<div class="cancel-item-new" style="cursor: not-allowed;">
													<button class="btn btn-danger un-delete-btn" type="submit" name="cancelOrder" value="Cancel" onclick="return confirm('Are you sure you want to cancel this order?');">
														Cancel
													</button>
												</div>
												<?php
											}	
										?>
										
										<?php
									}
								?>		
							</div>
						</div>
						<!-- multicheck end here-->	
						<?php 
					} 
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
				
				<div class="new-scroller mt-3">
					<table class="table table-bordered table-hover">
						<thead>
							<tr>
								<th style="width:40px !important;" class="text-center">
									<?php
										if(isset($_GET["order_status"]) && !empty($_GET["order_status"]))
										{
											if( $bookedStatus > 0 || $ConfirmedStatus > 0 || $PreparingStatus > 0 || $ShippedStatus > 0 || $deliveredStatus > 0)
											{
												?>
												&nbsp;<input type="checkbox" id="select_all" style=" margin: 7px 0px 2px 0px !important;"> &nbsp;
												<?php 
											}
											else
											{
												echo '-';
											} 
										}
										else
										{
											echo '-';
										}
									?>
								</th>
								
								<th class="text-center">Action</th>
								<th class="text-center">Next Action</th>
								<th class="text-center">Order Number</th>
								<th class="text-center">Order Date / Time</th>
								<th >Customer Name</th>
								<th class="text-center">Mobile Number</th>
								<th>Branch Name</th>
								<th class="text-right">
									Bill Amount <span style="font-size: 12px;"> (<?php echo CURRENCY_CODE;?>) </span>
								</th>
								<th class="text-center">Payment Type</th>
								
							</tr>
						</thead>
						
						<style>
							tr.new-orders {
								background: #dff0d8 !important;
							}
							
							.blink_me {
							  animation: blinker 2s linear infinite;
							}

							@keyframes blinker {
							  50% {
								opacity: 0;
							  }
							}
						</style>
						<tbody id="table_body">
							<?php 
								$page_data = array();
								echo $this->load->view('backend/orders/newOrdersAutoRefresh',$page_data,true);
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

		</div><!-- Card body end-->
	</div><!-- Card end-->
</div><!-- Content end-->

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

<script>
	function updateConfirmStatus(header_id,status)
	{
		if(header_id && status)
		{
			var user_id = '<?php echo $this->user_id;?>'; //Admin User Id

			$.ajax({
				type : 'post',
				url  : '<?php echo base_url();?>orders/updateConfirmStatus',
				data : {header_id:header_id,status:status},
				success: function (result) 
				{
					var autoPrintStatus = '<?php echo AUTO_PRINT_STATUS;?>';

					//alert(autoPrintStatus);return;
					
					if( autoPrintStatus == 'Y') //Not Admin
					{
						printKOTPDF(header_id);
						printOrderPDF(header_id);
					}
					else
					{
						Swal.fire({
							position: 'top',
							//position: 'top-end',
							icon: 'success',
							title: 'Order Confirmed successfully!',
							showConfirmButton: false,
							timer: 500,
							width:'350px'
						});
						location.reload();
					}	
				}
			});
		}
	}

	function printKOTPDF(interface_header_id)
    {
        var orderID = interface_header_id;
       
        if(orderID > 0 && orderID !="")
        {
            $.ajax({
                url      : '<?php echo base_url(); ?>billGenrator/chkbill/'+orderID,
                type     : "POST",
                data     : {},
                datatype : JSON,
                success  : function(d)
                {
                    response = JSON.parse(d);

                    var htmlCashierContent = response["orderPDFPath"];
                    var htmlKOTContent = response["orderKOTPath"];
                    var print_items = response["print_items"];

                    var countKey = Object.keys(print_items).length;
                    
                    if( countKey > 0 )
                    {
                        $.each(print_items, function(i, item) 
                        {
                            var print_type = item.print_type; // #Cashier #KOT

                            var printer_name = item.printer_name;
                            var printer_count = item.printer_count;

                            if( printer_name !="" && print_type == "KOT")
                            {
                                for(i=1; i<=printer_count; i++)
								{
									kotAutoPrint(jspmWSStatus(),htmlKOTContent,orderID,printer_name);
								}	
                            }
                        });
                    }  
                }
            });
		}
    }

	function printOrderPDF(interface_header_id)
    {
        var orderID = interface_header_id;
       
        if(orderID > 0 && orderID !="")
        {
            $.ajax({
                url      : '<?php echo base_url(); ?>billGenrator/chkbill/'+orderID,
                type     : "POST",
                data     : {},
                datatype : JSON,
                success  : function(d)
                {
                    response = JSON.parse(d);

                    var htmlCashierContent = response["orderPDFPath"];
                    var htmlKOTContent = response["orderKOTPath"];
                    var print_items = response["print_items"];

                    var countKey = Object.keys(print_items).length;

                    if( countKey > 0 )
                    {
                        $.each(print_items, function(i, item) 
                        {
                            var print_type = item.print_type; // #Cashier #KOT

                            var printer_name = item.printer_name;
                            var printer_count = item.printer_count;

                            if( printer_name !="" && print_type == "CASHIER")
                            {
                                for(i=1; i<=printer_count; i++)
								{
									orderAutoPrint(jspmWSStatus(),htmlCashierContent,orderID,printer_name);
								}
                            }
                        });
                    }

					updateOnlineOrderStatus(orderID);
                }
            }); 
        }
    }

    function kotAutoPrint(printerStatus,htmlContent,orderID,printer_name)
    {
        if (printerStatus && htmlContent !="") 
        {
            //Create a ClientPrintJob
            var cpj = new JSPM.ClientPrintJob();

            //Set Printer info
            //var myPrinter = new JSPM.InstalledPrinter($('#lstPrinters').val());
            //myPrinter.paperName = $('#lstPrinterPapers').val();
            //myPrinter.trayName = $('#lstPrinterTrays').val();
            //cpj.clientPrinter = myPrinter;
            
            //Cashier Printer
            //var printerPort = <?php #echo PRINTER_PORT;?>;
            //var printerName = '<?php #echo PRINTER_NAME;?>';

            //var printerPort = printer_ip;
            var printerName = printer_name;
            
            //alert(printerName);
            
            //var myPrinter = new JSPM.InstalledPrinter(printerPort,printerName); //9100 ,"192.168.1.215"
            var myPrinter = new JSPM.InstalledPrinter(printerName); //printer name
            
            //var myPrinter = new JSPM.DefaultPrinter(); //9100 ,"192.168.1.215"
            
            cpj.clientPrinter = myPrinter;
            
            //Set PDF file
            var orderPDFPath = htmlContent;
            var currenttime = '<?php echo rand();?>';
            var my_file = new JSPM.PrintFilePDF(orderPDFPath, JSPM.FileSourceType.URL, 'MyFile_'+currenttime+'.pdf', 1);
            
            //var my_file = new JSPM.PrintFile('<?php echo base_url();?>uploads/generate_pdf/251.jpg', JSPM.FileSourceType.URL, 'MyFile.jpg', 1);

            //var my_file = new JSPM.PrintFilePDF($('#txtPdfFile').val(), JSPM.FileSourceType.URL, 'MyFile.pdf', 1);
            //my_file.printRotation = JSPM.PrintRotation[$('#lstPrintRotation').val()];
            //my_file.printRange = $('#txtPagesRange').val();
            //my_file.printAnnotations = $('#chkPrintAnnotations').prop('checked');
            //my_file.printAsGrayscale = $('#chkPrintAsGrayscale').prop('checked');
            //my_file.printInReverseOrder = $('#chkPrintInReverseOrder').prop('checked');

            cpj.files.push(my_file);
            
            //Send print job to printer!
            cpj.sendToClient();
            //updateOrderStatus(orderID);
        }

		
    }

	function orderAutoPrint(printerStatus,htmlContent,orderID,printer_name)
    {
        if (printerStatus && htmlContent !="") 
        {
            //Create a ClientPrintJob
            var cpj = new JSPM.ClientPrintJob();

            //Set Printer info
            //var myPrinter = new JSPM.InstalledPrinter($('#lstPrinters').val());
            //myPrinter.paperName = $('#lstPrinterPapers').val();
            //myPrinter.trayName = $('#lstPrinterTrays').val();
            //cpj.clientPrinter = myPrinter;
            
            //Cashier Printer
            //var printerPort = <?php #echo PRINTER_PORT;?>;
            //var printerName = '<?php #echo PRINTER_NAME;?>';

            //var printerPort = printer_ip;
            var printerName = printer_name;
            
            //alert(printerName);
            
            //var myPrinter = new JSPM.InstalledPrinter(printerPort,printerName); //9100 ,"192.168.1.215"
            var myPrinter = new JSPM.InstalledPrinter(printerName); //printer name
            
            //var myPrinter = new JSPM.DefaultPrinter(); //9100 ,"192.168.1.215"
            
            cpj.clientPrinter = myPrinter;
            
            //Set PDF file
            var orderPDFPath = htmlContent;
            var currenttime = '<?php echo rand();?>';
            var my_file = new JSPM.PrintFilePDF(orderPDFPath, JSPM.FileSourceType.URL, 'MyFile_'+currenttime+'.pdf', 1);
            
            //var my_file = new JSPM.PrintFile('<?php echo base_url();?>uploads/generate_pdf/251.jpg', JSPM.FileSourceType.URL, 'MyFile.jpg', 1);

            //var my_file = new JSPM.PrintFilePDF($('#txtPdfFile').val(), JSPM.FileSourceType.URL, 'MyFile.pdf', 1);
            //my_file.printRotation = JSPM.PrintRotation[$('#lstPrintRotation').val()];
            //my_file.printRange = $('#txtPagesRange').val();
            //my_file.printAnnotations = $('#chkPrintAnnotations').prop('checked');
            //my_file.printAsGrayscale = $('#chkPrintAsGrayscale').prop('checked');
            //my_file.printInReverseOrder = $('#chkPrintInReverseOrder').prop('checked');

            cpj.files.push(my_file);
            
            //Send print job to printer!
            cpj.sendToClient();
            //updateOrderStatus(orderID);
        }
    }

    //Update Printer Order Status
    function updateOnlineOrderStatus(orderID)
    {
        if(orderID)
        {
            var interface_header_id = orderID;

            $.ajax({
                url      : '<?php echo base_url(); ?>billGenrator/updateKOTOrderStatus/'+orderID,
                type     : "POST",
                data     : {},
                datatype : JSON,
                success  : function(result)
                {
					Swal.fire({
                        position: 'top',
                        //position: 'top-end',
                        icon: 'success',
                        title: 'Order confirmed successfully!',
                        showConfirmButton: false,
                        timer: 500,
                        width:'350px'
                    });
                    window.location = '<?php //echo $_SERVER["HTTP_REFERER"];?>';
				}
            });
        }
    }
</script>


