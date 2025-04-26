<!DOCTYPE html>
<html lang="en-US">
    <head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<title><?php echo isset($page_title) ? $page_title :"";?></title>
		<link rel="icon" type="image/png" sizes="16x16" href="<?php echo base_url();?>uploads/favicon.png">
		
		<link rel="stylesheet" href="<?php echo base_url();?>assets/backend/font-icons/entypo/css/entypo.css">
		<link rel="stylesheet" href="<?php echo base_url();?>assets/backend/font-icons/font-awesome/css/font-awesome.css">
		<link rel="stylesheet" href="<?php echo base_url();?>assets/backend/font-icons/font-awesome/css/poppins.css">
		
		
		<!-- <script src="https://cdn.ckeditor.com/4.10.1/standard/ckeditor.js"></script> -->
		<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet"/>

		<!-- <script src="https://cdn.ckeditor.com/4.10.1/standard/ckeditor.js"></script> -->
		<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet"/>
		
		<link href="https://fonts.googleapis.com/css?family=Roboto&display=swap" rel="stylesheet">
		
		<?php /*<script src="https://kit.fontawesome.com/3baa9791ff.js" crossorigin="anonymous"></script>
		
		<!-- Global stylesheets -->
		<link href="https://fonts.googleapis.com/css?family=Roboto:400,300,100,500,700,900" rel="stylesheet" type="text/css">
		<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
		<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm" crossorigin="anonymous"></script>
		*/ ?>

		<link href="<?php echo base_url();?>assets/backend/global_assets/css/icons/icomoon/styles.min.css" rel="stylesheet" type="text/css">
		<link href="<?php echo base_url();?>assets/backend/assets/css/bootstrap.min.css" rel="stylesheet" type="text/css">
		<link href="<?php echo base_url();?>assets/backend/assets/css/bootstrap_limitless.min.css" rel="stylesheet" type="text/css">
		<link href="<?php echo base_url();?>assets/backend/assets/css/components.min.css" rel="stylesheet" type="text/css">
		<link href="<?php echo base_url();?>assets/backend/assets/css/colors.min.css" rel="stylesheet" type="text/css">
		<link href="<?php echo base_url();?>assets/backend/assets/css/common.css" rel="stylesheet" type="text/css">
		<link href="<?php echo base_url();?>assets/backend/assets/css/pos_new.css" rel="stylesheet" type="text/css">
		
		<?php /* <link href="<?php echo base_url();?>assets/backend/assets/css/pos.css" rel="stylesheet" type="text/css">
		<link href="<?php echo base_url();?>assets/backend/assets/css/pos1.css" rel="stylesheet" type="text/css">*/ ?>
		
		<link href="<?php echo base_url();?>assets/backend/assets/css/text-colors.css" rel="stylesheet" type="text/css">	
		<link href="<?php echo base_url();?>assets/backend/assets/css/font-awesome.min.css" rel="stylesheet" type="text/css">	

		<link href="https://cdn.quilljs.com/1.3.7/quill.snow.css" rel="stylesheet">
		<!-- global stylesheets -->
		
		<!-- Themes Selection CSS Start -->
		<?php 
			if( THEME == 1 ) #Default
			{
				?>
				<link href="<?php echo base_url();?>assets/backend/assets/default/css/layout.min.css" rel="stylesheet" type="text/css">
				<link href="<?php echo base_url();?>assets/backend/assets/default/css/style.css" rel="stylesheet" type="text/css">
				<?php 
			}
			else if( THEME == 2 ) #Theme 2
			{
				?>
				<link href="<?php echo base_url();?>assets/backend/assets/theme_1/css/layout.min.css" rel="stylesheet" type="text/css">
				<link href="<?php echo base_url();?>assets/backend/assets/theme_1/css/style.css" rel="stylesheet" type="text/css">
				<?php 
			}
			else if( THEME == 3 ) #Theme 3
			{
				?>
				<link href="<?php echo base_url();?>assets/backend/assets/theme_2/css/layout.min.css" rel="stylesheet" type="text/css">
				<link href="<?php echo base_url();?>assets/backend/assets/theme_2/css/style.css" rel="stylesheet" type="text/css">
				<?php 
			}
			else if( THEME == 4 ) #Theme 4
			{
				?>
				<link href="<?php echo base_url();?>assets/backend/assets/theme_3/css/layout.min.css" rel="stylesheet" type="text/css">
				<link href="<?php echo base_url();?>assets/backend/assets/theme_3/css/style.css" rel="stylesheet" type="text/css">
				<?php 
			}
			else if( THEME == 5 ) #Theme 5
			{
				?>
				<link href="<?php echo base_url();?>assets/backend/assets/theme_4/css/layout.min.css" rel="stylesheet" type="text/css">
				<link href="<?php echo base_url();?>assets/backend/assets/theme_4/css/style.css" rel="stylesheet" type="text/css">
				<?php 
			}
		?>
		<!-- Themes Selection CSS end -->

		<!-- Core JS files -->
		<script src="<?php echo base_url();?>assets/backend/global_assets/js/main/jquery.min.js"></script> 
		<!--<script src="<?php echo base_url();?>assets/backend/global_assets/js/main/jquery.js"></script>
		-->
		<script src="<?php echo base_url();?>assets/backend/global_assets/js/main/bootstrap.bundle.min.js"></script>
		<script src="<?php echo base_url();?>assets/backend/global_assets/js/plugins/loaders/blockui.min.js"></script>
		<!-- core JS files -->
	
		
		<script src="<?php echo base_url();?>assets/backend/global_assets/js/plugins/tables/datatables/datatables.min.js"></script>
		<script src="<?php echo base_url();?>assets/backend/global_assets/js/plugins/tables/datatables/extensions/responsive.min.js"></script>
		<script src="<?php echo base_url();?>assets/backend/global_assets/js/plugins/forms/selects/select2.min.js"></script>
		<script src="<?php echo base_url();?>assets/backend/assets/js/app.js"></script>
		<script src="<?php echo base_url();?>assets/backend/global_assets/js/demo_pages/datatables_responsive.js"></script>

		
		<!-- Theme JS files -->
		<script src="<?php echo base_url();?>assets/backend/global_assets/js/plugins/visualization/d3/d3.min.js"></script>
		<script src="<?php echo base_url();?>assets/backend/global_assets/js/plugins/visualization/d3/d3_tooltip.js"></script>
		<script src="<?php echo base_url();?>assets/backend/global_assets/js/plugins/forms/styling/switchery.min.js"></script>
		<script src="<?php echo base_url();?>assets/backend/global_assets/js/plugins/forms/selects/bootstrap_multiselect.js"></script>
		<script src="<?php echo base_url();?>assets/backend/global_assets/js/plugins/ui/moment/moment.min.js"></script>
		<script src="<?php echo base_url();?>assets/backend/global_assets/js/plugins/pickers/daterangepicker.js"></script>

		
		<script src="<?php echo base_url();?>assets/backend/global_assets/js/demo_pages/dashboard.js"></script>
		
		<script src="<?php echo base_url();?>assets/backend/global_assets/js/plugins/forms/validation/validate.min.js"></script>
		<script src="<?php echo base_url();?>assets/backend/global_assets/js/plugins/forms/inputs/touchspin.min.js"></script>
		
		<script src="<?php echo base_url();?>assets/backend/global_assets/js/plugins/forms/styling/switch.min.js"></script>
		<script src="<?php echo base_url();?>assets/backend/global_assets/js/plugins/forms/styling/switchery.min.js"></script>
		<script src="<?php echo base_url();?>assets/backend/global_assets/js/plugins/forms/styling/uniform.min.js"></script>

		<script src="<?php echo base_url();?>assets/backend/global_assets/js/demo_pages/form_validation.js"></script>
		<script src="<?php echo base_url();?>assets/backend/assets/js/form_validation.js"></script>
	
		<!-- theme JS files -->
		<link href="<?php echo base_url();?>assets/backend/toastr/toastr.css" type="text/css"  rel="stylesheet" />
		<script src="<?php echo base_url();?>assets/backend/toastr/toastr.js"></script>
		
		<link href='<?php echo base_url();?>assets/backend/select2/css/select2.css' rel='stylesheet' type='text/css'>
		<script src='<?php echo base_url();?>assets/backend/select2/js/select2.min.js' type='text/javascript'></script>
		
		<link rel="stylesheet" href="<?php echo base_url();?>assets/backend/sorting/sortable-tables.min.css">
		<script src="<?php echo base_url();?>assets/backend/sorting/sortable-tables.min.js" type="text/javascript"></script>

		<link rel="stylesheet" href="<?php echo base_url();?>assets/backend/bootstrap-select/bootstrap-select.min.css">
    	<script src="<?php echo base_url();?>assets/backend/bootstrap-select/bootstrap-select.min.js"></script>

		<script src="https://cdn.quilljs.com/1.3.7/quill.min.js"></script>
    	<!-- <script src="<?php //echo base_url();?>assets/backend/assets/js/ckeditor.js"></script> -->

		<script>
			$(document).ready(function()
			{
				$('#reset').click(function(){
					//$(".searchDropdown").empty();
					$(".searchDropdown").val('').trigger('change')
				});
			});
				
			$(document).ready(function()
			{ 
				// Initialize select2
				$(".searchDropdown").select2();
				// Read selected option
			   /* $('#but_read').click(function(){
					var username = $('#selUser option:selected').text();
					var userid = $('#selUser').val();
					$('#result').html("id : " + userid + ", name : " + username);
				}); */
				
				//$(".searchDropdown").empty();
				//$(".searchDropdown").select2("val", "");
			});
		</script>
	</head>
	<?php $this->segment = $this->uri->segment(2); ?>
	<?php include 'assets/backend/assets/js/common_script.php';?>

    <body>
		<?php 
			if( $this->segment == 'posOrder' || $this->segment == 'dineInOrder' || $this->segment == 'dineInTables')
			{
				
			}
			else
			{
				include "header.php"; #Template Common Header
			}
		?>

		<div class="page-content">
			<?php 
				if($this->segment == 'home' || $this->segment == 'posOrder' || $this->segment == 'dineInOrder' || $this->segment == 'dineInTables')
				{

				}
				else
				{
					include "sidebar.php"; 
				}
			?>
			<div class="content-wrapper">
				<?php include $page_name.'.php'; ?>
				<?php 
					if($this->segment == 'dashboard')
					{
						include "footer.php";
					}  	
				?>
			</div>
		</div>
	</body>

</html>


<script>
	function logoutAlert() 
	{
		Swal.fire({
			title: 'Are you sure?',
			text: "You won't be able to revert this!",
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#070d7d',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Yes, Logout!'
		}).then((result) => 
		{
			if (result.isConfirmed) 
			{
				window.location = '<?php echo base_url()?>admin/logout';
			}
		});
	}
</script>

<script>
	function showFilter()
	{
		$(".search-form").toggle("slow");
	}
</script>

<link href="<?php echo base_url();?>assets/backend/assets/css/jquery-ui.css" rel="stylesheet">
<script src="<?php echo base_url();?>assets/backend/assets/js/jquery-ui.js"></script>

<script>
	//Order Auto Print Configure
	/* setInterval(
		function()
		{
			//getcount(); 
			//order_dashboard();
			<?php
				#$autoPrint_status = AUTO_PRINT_STATUS;
				/* if( (isset($this->user_id) && $this->user_id !=1) && $autoPrint_status == 'Y')
				{
					?>
					billGenrate();
					<?php 
				} */
			?>
		}, <?php #echo ORDER_AUTO_PRINT_TIMER; ?>
	); */
	//Order Auto Print Configure End

	setInterval(
		function()
		{
			//order_dashboard(); //notification Sound and add order counts
			//headerNotification();
			AjaxappendTable();
			checkNewOrders();
		}, <?php echo AUTO_REFRESH_SECONDS; ?>
	);

	function AjaxappendTable() 
	{
		$.ajax({
			url: '<?php echo base_url();?>orders/AjaxappendTable',
			type: 'GET',
			data: {},
			success: function(result)
			{   
				data = JSON.parse(result);

				$("#table_body").html(data['newOrders']);

				$(".bookedCount").html(data['bookedCount']);
				$(".confirmedCount").html(data['confirmedCount']);
				$(".preparingCount").html(data['preparingCount']);
				$(".shippedCount").html(data['shippedCount']);
				$(".deliveredCount").html(data['deliveredCount']);
				$(".totalOrdersCount").html(data['totalOrdersCount']);
				$(".new-order_notification").html(data['newOrdersCount']);
			}
		});	
	}

	function checkNewOrders() 
	{
		$.ajax({
			url: '<?php echo base_url();?>orders/checkNewOrders',
			type: 'GET',
			data: {},
			success: function(result)
			{   
				if(result > 0)
				{
					var audio = new Audio("<?php echo base_url();?>uploads/notification/boss.mp3");
				    audio.play();
				}
			}
		});	
	}

	/*
	function headerNotification()
	{
		$.ajax({
			url   : '<?php #echo base_url();?>orders/headerNotification',
			type  : 'POST',
			data  : {},
			success: function(response)
			{   
				var hederNotification = JSON.parse(response);
				$(".header_new_order_count").html(hederNotification.newNotificationCount);

				if(hederNotification.newNotificationCount > 0)
				{
					$(".clearAll").show();
				}else{
					$(".clearAll").hide();
				}

				$("#new_orders_list").html(hederNotification.headerOrderNotification);
			}
		});	
	} */
</script>

<style>
	.ui-priority-secondary{display:none !important;}
	.ui-datepicker-trigger {
		position: absolute;
		top: 32px !important;
		right: 5px;
		z-index: 4;
		padding: 7px 9px 5px 5px !important;
		cursor: pointer;
		width: 33px !important;
		height: 32px !important;
	}
</style>
<script>
	$( function() 
	{
		$(".default_date").datepicker({
			changeMonth: true,
			changeYear: true,
			yearRange: "1950:<?php echo date('Y') + 10; ?>",
			dateFormat: "dd-M-yy"	
		});

		//future_date not allowed
		$(".future_date").datepicker({
			changeMonth: true,
			changeYear: true,
			yearRange: "1950:<?php echo date('Y') + 10; ?>",
			dateFormat: "dd-M-yy",
			maxDate: 0
		});
		$(".previous_date").datepicker({
			changeMonth: true,
			changeYear: true,
			yearRange: "1950:<?php echo date('Y') + 10; ?>",
			dateFormat: "dd-M-yy",
			minDate: 0
		});
		
		$("#date_of_birth").datepicker({
			changeMonth: true,
			changeYear: true,
			yearRange: "1950:<?php echo date('Y'); ?>",
			dateFormat: "dd-M-yy"	
		});
		
		$("#invoice_date").datepicker({
			changeMonth: true,
			changeYear: true,
			yearRange: "1950:<?php echo date('Y'); ?>",
			//dateFormat: "dd-M-yy"
			dateFormat: "dd-M-yy"											
		});
		
		//var dateFormat = "dd-mm-yy",

		//Start Date and End Date (ID) Start here
		var dateFormat = "dd-M-yy",
		from = $("#start_date").datepicker({
			changeMonth: true,
			changeYear: true,
			yearRange: "1950:<?php echo date('Y'); ?>",
			//dateFormat: "dd-mm-yy"	
			dateFormat: "dd-M-yy"	
		}).on("change", function() {
			var toMinDate = getDate(this);
			toMinDate.setDate(toMinDate.getDate() + 1);
			to.datepicker("option", "minDate", toMinDate);
		}),
		  
		to = $("#end_date").datepicker({
			defaultDate: "+1w",
			changeMonth: true,
			changeYear: true,
			yearRange: "1950:<?php echo date('Y') + 10; ?>",
			dateFormat: dateFormat,
			minDate: new Date(),
		})
		
		.on("change", function() {
			var fromMaxDate = getDate(this);
			fromMaxDate.setDate(fromMaxDate.getDate() - 1);
			from.datepicker("option", "maxDate", fromMaxDate);
		});
		//Start Date and End Date (ID) Start here



		//Start Date and End Date (Class) Start here

		// Custom handler function
		var setCalsClearButton = function(year,month,elem)
		{
			var afterShow = function()
			{
				var d = new $.Deferred();
				var cnt = 0;
				setTimeout(function(){
					if(elem.dpDiv[0].style.display === "block")
					{
						d.resolve();
					}
					if(cnt >= 500){
						d.reject("datepicker show timeout");
					}
					cnt++;
				},10);
				return d.promise();
			}();

			afterShow.done(function()
			{
				$('.ui-datepicker').css('z-index', 2000);

				var buttonPane = $( elem ).datepicker( "widget" ).find( ".ui-datepicker-buttonpane" );

				var btn = $('<button class="ui-datepicker-current ui-state-default ui-priority-primary ui-corner-all" type="button">Clear</button>');
				btn.off("click").on("click", function () 
				{
					$.datepicker._clearDate( elem.input[0] );
				});
				btn.appendTo( buttonPane );
			});
		}

		var dateFormat = "dd-M-yy",
		from = $(".start_date").datepicker({
			changeMonth: true,
			changeYear: true,
			yearRange: "1950:<?php echo date('Y'); ?>",
			//dateFormat: "dd-mm-yy"	
			//showOn: "both",
			showOn: "focus",
			//showOn: "button",
			//buttonImage: "<?php //echo base_url();?>uploads/calendar.png",
			//buttonImageOnly: true,
			//buttonText: "Select date",
			dateFormat: "dd-M-yy",
			//showOn: 'focus',
			showButtonPanel: true,
			beforeShow : function(inst, elem)
			{
				setCalsClearButton(null, null, elem);
			},
			onChangeMonthYear: setCalsClearButton
		}).on("change", function() {
			var toMinDate = getDate(this);
			toMinDate.setDate(toMinDate.getDate() + 0);
			//toMinDate.setDate(toMinDate.getDate() + 1);
			to.datepicker("option", "minDate", toMinDate);
		}),
		  
		to = $(".end_date").datepicker({
			//defaultDate: "+1w",
			changeMonth: true,
			changeYear: true,
			//dateFormat: dateFormat,
			showOn: "focus",
			//showOn: "both",
			//showOn: "button",
			//buttonImage: "<?php //echo base_url();?>uploads/calendar.png",
			//buttonImageOnly: true,
			dateFormat: "dd-M-yy",
			minDate: new Date(),
			showButtonPanel: true,
			beforeShow : function(inst, elem)
			{
				setCalsClearButton(null, null, elem);
			},
			onChangeMonthYear: setCalsClearButton
		})
		
		.on("change", function() {
			var fromMaxDate = getDate(this);
			//fromMaxDate.setDate(fromMaxDate.getDate() - 1);
			fromMaxDate.setDate(fromMaxDate.getDate() - 0);
			from.datepicker("option", "maxDate", fromMaxDate);
		});
		//Start Date and End Date (Class) Start here


		//from_date & end_date
		//var dateFormat = "dd-mm-yy",
		var dateFormat = "dd-M-yy",
		from1 = $("#from_date").datepicker({
			changeMonth   : true,
			changeYear    : true,
			yearRange     : "1950:<?php echo date('Y') + 10; ?>",
			//dateFormat: "dd-mm-yy"	
			dateFormat    : "dd-M-yy",
			maxDate       : new Date(),			
		}).on("change", function() {
			var toMinDate = getDate(this);
			toMinDate.setDate(toMinDate.getDate() + 0);
			to1.datepicker("option", "minDate", toMinDate);
		}),
		  
		to1 = $("#to_date").datepicker({
			defaultDate  : "+1w",
			changeMonth  : true,
			dateFormat   : dateFormat,
			maxDate      : new Date(),
		})
		.on("change", function() 
		{
			/* var fromMaxDate = getDate(this);
			fromMaxDate.setDate(fromMaxDate.getDate() - 1);
			from1.datepicker("option", "maxDate", fromMaxDate); */
			//suresh new changes start here
			var fromMaxDate = getDate(this);
			var to_date = $("#to_date").val();
			fromMaxDate.setDate(to_date);
			from1.datepicker("option", "maxDate", fromMaxDate);
			//suresh new changes end here
		});
		
		function getDate(element) 
		{
			var date;
			try {
				date = $.datepicker.parseDate(dateFormat, element.value);
			} catch (error) {
				date = null;
			}
			return date;
		}
	});
</script>
<!-- Calander end -->

<!-- Show tostr notification start-->
<script type="text/javascript">
	<?php
		$msg = $this->session->flashdata("success_message");
		$flash_message = $this->session->flashdata("flash_message");
		$error_message = $this->session->flashdata("error_message");
		
		if( $msg != "" || $flash_message !="")
		{
			if($msg !="")
			{
				$message = $msg;
			}else if($flash_message !="")
			{
				$message = $flash_message;
			}
			?>  
			//toastr.success('<?php #echo $message;?>');	
			Swal.fire({
				position: 'top',
				//position: 'top-end',
				icon: 'success',
				title: '<?php echo $message;?>',
				showConfirmButton: false,
				timer: 1500,
				width:'350px'
			});
			<?php 
		}
		
		if( $error_message != '')
		{
			?>  
			//toastr.error('<?php #echo $error_message;?>');	
			Swal.fire({
				position: 'top',
				//position: 'top-end',
				icon: 'error',
				title: '<?php echo $error_message;?>',
				showConfirmButton: false,
				timer: 1000
			});	
			<?php 
		}
	?>
</script>
<!-- Show tostr notification end-->

<style>
	table {
	  border-spacing: 0;
	  width: 100%;
	  border: 1px solid #ddd;
	}

	th {
	  cursor: pointer;
	}

	th, td {
	  text-align: left;
	  padding: 16px;
	}

	tr:nth-child(even) {
	  background-color: #f2f2f2
	}
	i.fa.fa-fw.fa-sort {
		color: #c7c6c6;
	}
</style>
<script>
function sortTable(n) 
{
  var table, rows, switching, i, x, y, shouldSwitch, dir, switchcount = 0;
  table = document.getElementById("myTable");
  switching = true;
  //Set the sorting direction to ascending:
  dir = "asc"; 
  /*Make a loop that will continue until
  no switching has been done:*/
  while (switching) {
    //start by saying: no switching is done:
    switching = false;
    rows = table.rows;
    /*Loop through all table rows (except the
    first, which contains table headers):*/
    for (i = 1; i < (rows.length - 1); i++) {
      //start by saying there should be no switching:
      shouldSwitch = false;
      /*Get the two elements you want to compare,
      one from current row and one from the next:*/
      x = rows[i].getElementsByTagName("TD")[n];
      y = rows[i + 1].getElementsByTagName("TD")[n];
      /*check if the two rows should switch place,
      based on the direction, asc or desc:*/
      if (dir == "asc") {
        if (x.innerHTML.toLowerCase() > y.innerHTML.toLowerCase()) {
          //if so, mark as a switch and break the loop:
          shouldSwitch= true;
          break;
        }
      } else if (dir == "desc") {
        if (x.innerHTML.toLowerCase() < y.innerHTML.toLowerCase()) {
          //if so, mark as a switch and break the loop:
          shouldSwitch = true;
          break;
        }
      }
    }
    if (shouldSwitch) {
      /*If a switch has been marked, make the switch
      and mark that a switch has been done:*/
      rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
      switching = true;
      //Each time a switch is done, increase this count by 1:
      switchcount ++;      
    } else {
      /*If no switching has been done AND the direction is "asc",
      set the direction to "desc" and run the while loop again.*/
      if (switchcount == 0 && dir == "asc") {
        dir = "desc";
        switching = true;
      }
    }
  }
}
</script>

<script>
$(function()
	{
		$('.dev_num').keyup(function()
		{
			var yourInput = $(this).val();
			re = /[`~!@#$%^&*()_|+\=?;:'",.<>\{\}\[\]\\\/]/gi;
			var isSplChar = re.test(yourInput);
			if(isSplChar)
			{
				var no_spl_char = yourInput.replace(/[`~!@#$%^&*()_|+\=?;:'",.<>\{\}\[\]\\\/]/gi, '');
				$(this).val(no_spl_char);
			}
		});
	});
	/** Device Name Validation **/
	$(function()
	{
		$('.only_name').keyup(function()
		{
			var yourInput = $(this).val();
			re = /[`~!@#$%^&*()_|+\-=?;:'",.<>\{\}\[\]\\\/0-9]/gi;
			var isSplChar = re.test(yourInput);
			if(isSplChar)
			{
				var no_spl_char = yourInput.replace(/[`~!@#$%^&*()_|+\-=?;:'",.<>\{\}\[\]\\\/0-9]/gi, '');
				$(this).val(no_spl_char);
			}
		});
	});
	/** Device Type Name Validation - only accepts Alphabatics and Numeric values**/ 
	$(function()
	{
		$('.dev_name').keyup(function()
		{
			var yourInput = $(this).val();
			re = /[`~!@#$%^&*()_|+\-=?;:'",.<>\{\}\[\]\\\/]/gi;
			var isSplChar = re.test(yourInput);
			if(isSplChar)
			{
				var no_spl_char = yourInput.replace(/[`~!@#$%^&*()_|+\-=?;:'",.<>\{\}\[\]\\\/]/gi, '');
				$(this).val(no_spl_char);
			}
		});
	});
	/** Device IP Validation **/
	$(function()
	{
		$('.dev_ip').keyup(function()
		{
			var yourInput = $(this).val();
			re = /[`~!@#$%^&*()_|+\-=?;:'",<>\{\}\[\]\\\/a-zA-Z]/gi;
			var isSplChar = re.test(yourInput);
			if(isSplChar)
			{
				var no_spl_char = yourInput.replace(/[`~!@#$%^&*()_|+\-=?;:'",<>\{\}\[\]\\\/a-zA-Z]/gi, '');
				$(this).val(no_spl_char);
			}
		});
	});
	
	/** Country Code Validation **/
	$(function()
	{
		$('.country_code').keyup(function()
		{
			var yourInput = $(this).val();
			re = /[`~!@#$%^&*()_|+\-=?;:'",<>\{\}\[\]\\\/a-zA-Z]/gi;
			var isSplChar = re.test(yourInput);
			if(isSplChar)
			{
				var no_spl_char = yourInput.replace(/[`~!@#$%^&*_	\-=?;:'",<>\{\}\[\]\\\/a-zA-Z]/gi, '');
				$(this).val(no_spl_char);
			}
		});
	});
	
	/** Special Character Validation **/
	$(function()
	{
		$('.special_vali').keyup(function()
		{
			var yourInput = $(this).val();
			re = /[`~!@#$%^&*()_|+\-=?;:'",.<>\{\}\[\]\\\/]/gi;
			var isSplChar = re.test(yourInput);
			if(isSplChar)
			{
				var no_spl_char = yourInput.replace(/[`~!@#$%^&*()_|+\-=?;:'",.<>\{\}\[\]\\\/]/gi, '');
				$(this).val(no_spl_char);
			}
		});
	});
	
	/** Mobile Number Character Validation **/
	$(function()
	{
		$('.mobile_vali').keyup(function()
		{
			var yourInput = $(this).val();
			re = /[`~!@#$%^&*()_|+\-=?;:'",.<>\{\}\[\]\\\/a-zA-Z]/gi;
			var isSplChar = re.test(yourInput);
			if(isSplChar)
			{
				var no_spl_char = yourInput.replace(/[`~!@#$%^&*()_|+\-=?;:'",.<>\{\}\[\]\\\/a-zA-Z]/gi, '');
				$(this).val(no_spl_char);
			}
		});
	});
	
	$(document).ready(function()
	{
		/** Email Validation **/
		$('#email-error').hide();
		$('.email_vali').keyup(function()
		{
			var email = $(this).val();
			
			var regex = /^([a-zA-Z0-9_\.\-\+])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
			if(!regex.test(email) && email !="") 
			{
				//$('.email-error-btn').hide();
				$('.email_error').html('Please enter a valid email address.');
				$(".email-error-btn").attr("disabled", "disabled=disabled");
				return false;
			}
			else
			{
				$('.email_error').html('');
				$(".email-error-btn").removeAttr("disabled", "disabled=disabled");
				return true;
			}
		});
	});
</script>


<script src="<?php echo base_url();?>assets/backend/toastr/sweetalert2@11.js"></script>
<script>
	function logoutAlert() 
	{
		Swal.fire({
			title: 'Are you sure?',
			text: "You won't be able to revert this!",
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#070d7d',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Yes, Logout!'
		}).then((result) => 
		{
			if (result.isConfirmed) 
			{
				window.location = '<?php echo base_url()?>admin/logout';
			}
		});
	}
</script>

<!-- 
<script src="<?php echo base_url();?>assets/backend/toastr/sweetalert2@11.js"></script>
<script>
	function logoutAlert() 
	{
		Swal.fire({
			title: 'Are you sure?',
			text: "You won't be able to revert this!",
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#070d7d',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Yes, Logout!'
		}).then((result) => 
		{
			if (result.isConfirmed) 
			{
				window.location = '<?php #echo base_url()?>admin/logout';
			}
		});
	}
</script> -->

<?php
	#Auto Printers Configurations start 
	$page_data = array();
	echo $this->load->view('backend/printer_settings/auto_printers', $page_data, true); 
	#Auto Printers Configurations end
?>