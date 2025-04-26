<script src="<?php echo base_url();?>assets/backend/jspm/JSPrintManager.js"></script>
<script src="<?php echo base_url();?>assets/backend/jspm/zip-full.min.js"></script>

<script>
	var clientPrinters = null;
	var _this = this;
	
	JSPM.JSPrintManager.license_url = "<?php echo base_url();?>jspm/index.php";
	
	//WebSocket settings
	JSPM.JSPrintManager.auto_reconnect = true;
	JSPM.JSPrintManager.start();
	/*JSPM.JSPrintManager.WS.onStatusChanged = function () {
		if (jspmWSStatus()) 
		{
			//get client installed printers
			JSPM.JSPrintManager.getPrinters().then(function (printersList) {
				clientPrinters = printersList;
				var options = '';
				for (var i = 0; i < clientPrinters.length; i++) {
					options += '<option>' + clientPrinters[i] + '</option>';
				}
				$('#printerName').html(options);
			});
		}
	};*/
	
	/* if (orderCount > 0 && user_id != 1) 
	{ */
		//Check JSPM WebSocket status
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
	/* } */

	//Do printing...
	function billGenrate()
	{	
		kotPrint();
		cashierPrint();
		/* $.ajax({
			url      : '<?php echo base_url(); ?>billGenrator/getOrderID',
			type     : "POST",
			data     : {},
			datatype : JSON,
			success  : function(orderID)
			{		    
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
									
									if( printer_name !="" ) //&& printer_count > 0
									{
										if( print_type == "KOT" )
										{
											for(i=1; i<=printer_count; i++)
											{
												kotBillPrint(jspmWSStatus(),htmlKOTContent,orderID,printer_name);
											}
										}

										if( print_type == "CASHIER" )
										{
											for(i=1; i<=printer_count; i++)
											{
												orderBillPrint(jspmWSStatus(),htmlCashierContent,orderID,printer_name);
											}
										}
									}
								});
								//updateOrderStatus(orderID);
							}	
						}
					});
					
					updateOrderStatus(orderID);
    			}
			}
		}); */
	}

	function kotPrint()
	{
		$.ajax({
			url      : '<?php echo base_url(); ?>billGenrator/getOrderID',
			type     : "POST",
			data     : {},
			datatype : JSON,
			success  : function(orderID)
			{		    
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
									
									if( printer_name !="" ) //&& printer_count > 0
									{
										if( print_type == "KOT" )
										{
											for(i=1; i<=printer_count; i++)
											{
												kotBillPrint(jspmWSStatus(),htmlKOTContent,orderID,printer_name);
											}
										}

										/* if( print_type == "CASHIER" )
										{
											for(i=1; i<=printer_count; i++)
											{
												orderBillPrint(jspmWSStatus(),htmlCashierContent,orderID,printer_name);
											}
										} */
									}
								});
								updateOrderStatus(orderID);
							}	
						}
					});
					
					//updateOrderStatus(orderID);
    			}
			}
		});
	}

	function cashierPrint()
	{
		$.ajax({
			url      : '<?php echo base_url(); ?>billGenrator/getOrderID',
			type     : "POST",
			data     : {},
			datatype : JSON,
			success  : function(orderID)
			{		    
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
									
									if( printer_name !="" ) //&& printer_count > 0
									{
										/* if( print_type == "KOT" )
										{
											for(i=1; i<=printer_count; i++)
											{
												kotBillPrint(jspmWSStatus(),htmlKOTContent,orderID,printer_name);
											}
										} */

										if( print_type == "CASHIER" )
										{
											for(i=1; i<=printer_count; i++)
											{
												orderBillPrint(jspmWSStatus(),htmlCashierContent,orderID,printer_name);
											}
										}
									}
								});
								//updateOrderStatus(orderID);
							}	

							updateOrderStatus(orderID);
						}
					});
    			}
			}
		});
	}

	function kotBillPrint(printerStatus,htmlContent,orderID,printer_name)
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

			var printerName = printer_name;
			
			//var myPrinter = new JSPM.InstalledPrinter(printerPort,printerName); //9100 ,"192.168.1.215"
			var myPrinter = new JSPM.InstalledPrinter(printerName); //printer nam    
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

	function orderBillPrint(printerStatus,htmlContent,orderID,printer_name)
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

			var printerName = printer_name;
			
			//var myPrinter = new JSPM.InstalledPrinter(printerPort,printerName); //9100 ,"192.168.1.215"
			var myPrinter = new JSPM.InstalledPrinter(printerName); //printer nam    
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
	function updateOrderStatus(orderID)
	{
		$.ajax({
			url      : '<?php echo base_url(); ?>billGenrator/updateOrderStatus/'+orderID,
			type     : "POST",
			data     : {},
			datatype : JSON,
			success  : function(result)
			{
				//printer status updated success, printer_status=1
			}
		});
	}
</script>