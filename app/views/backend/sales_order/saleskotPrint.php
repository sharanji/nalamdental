<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@500&display=swap" rel="stylesheet">
<style>
	* {
		font-size: 12px;
		font-family: 'Roboto';
	}

	td,th,tr,table {
		/* border-top: 1px solid black; */
		border-collapse: collapse;
		padding:1px; 0px;
	}
	
	tr.border-top{
		border-top:1px solid #000;
	}
	td.border-top{
		border-top:1px solid #000;
	}
	
	td.description, th.description {
		width: 150px;
		max-width: 150px;
	}

	td.quantity,th.quantity {
		width: 50px;
		max-width: 50px;
		word-break: break-all;
	}

	td.price,th.price {
		width: 65px;
		max-width: 65px;
		/* word-break: break-all; */
	}

	.centered {
		text-align: center;
		align-content: center;
	}
	.right {text-align: right;
		align-content: right;
	}
		
	.ticket {
		width: 250px;
		max-width: 250px;
	}

	img {
		max-width: inherit;
		width: inherit;
	}

    del{
		color: red;
		text-decoration: line-through;
		max-width: 100%;

	}
	


	@media print {
		.hidden-print,
		.hidden-print * {
			display: none !important;
		}
	}

	@page{
		margin-left: 0.10in !important;
	  	margin-right: 0.2in !important; 
	  	margin-top: 0.1in !important; 
		margin-bottom: 0in !important; 
	}


</style>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Order Receipt</title>
    </head>
    <body>
		<div class="ticket">
			<p class="centered" style="padding:0px 0px;margin:5px 0px 0px 0px;line-height:20px;font-size:13px;">
				<?php echo date(DATE_FORMAT,strtotime($data[0]['created_date']));?>  <?php echo date($this->time,strtotime($data[0]['created_date']));?>
			</p>
            <img src="<?php echo base_url();?>uploads/logo.png" alt="logo">			
            <?php /* <p class="centered" style="font-size:13px;padding:0px;margin:0px;line-height:18px;">
				<?php echo ADDRESS1; ?><?php if(!empty(ADDRESS2)){?>, <?php echo ADDRESS2; ?><?php } ?>
				<br>
			   Phone : <?php echo COUNTRY_CODE;?> - <?php echo PHONE1; ?>
			</p> */  ?>
			<p class="centered" style="border-bottom:1px solid #000;padding:0px 0px;margin:5px 0px 0px 0px;line-height:20px;font-size:13px;">
				Material Issue KOT
			</p>
			
			<table>
                <tbody>
                    <tr>
                        <td style="font-size:13px;padding-top:5px;">Order No&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style="margin-left:10px;">:</span> <?php echo isset($data[0]['order_number']) ? $data[0]['order_number']:NULL; ?></td>
                    </tr>
					<tr>
						<td style="font-size:13px;padding-top:5px;">Customer &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style="margin-left:10px;">:</span> <?php echo isset($data[0]['customer_name']) ? $data[0]['customer_name']:NULL; ?></td>
					</tr>
                    <tr>
                        <td style="font-size:13px;padding-top:5px;">Created By&nbsp;&nbsp;&nbsp;&nbsp;<span style="margin-left:10px;">:</span> 
							<?php 
								$Createdbyid =  isset($data[0]['Createdbyid']) ? $data[0]['Createdbyid']:NULL; 
								$Createdby =  isset($data[0]['Createdby']) ? $data[0]['Createdby']:NULL; 
								if($Createdbyid == '1')
								{
									$createdBy = "Admin";
								}
								else
								{
									$createdBy = $Createdby;
								}
								echo $createdBy;
							?>
						</td>
                    </tr>
					
					<tr>
						<td style="font-size:13px;padding-top:5px;">Created Date <span style="margin-left:10px;">:</span> 
						<?php 
							$created_date = isset($data[0]['created_date']) ? $data[0]['created_date']:NULL;
							if($created_date !=NULL)
							{
								echo date(DATE_FORMAT,strtotime($created_date));
							}
						 ?>
					</td>
					</tr>
                </tbody>
            </table>

            <table style="border-top:1px solid black;">
                <thead>
                    <tr class="border-top">
                        <th class="description" style="padding:5px 0px;font-size:13px;text-align:left;">Item</th>
						<th class="price" style="padding:5px 0px;font-size:13px;"></th>
                        <th class="quantity" style="padding:5px 0px;font-size:13px;text-align:right;">Qty</th>
					</tr>
                </thead>
                <tbody>
					<?php 
						$i=1;
						/* $totalTax = 0;
						$subTotal = 0; */
						foreach ($LineData as $lineItems) 
						{
							?>
							<tr class="border-top" style=" border-bottom:1px solid white;">
								<td class="description" style="font-size:13px;">
									<?php 
										echo ucfirst($lineItems['item_description']);
									?>
								</td>
								<td class="price right" style="font-size:13px;"></td>
								
								<td class="centered" style="font-size:13px;text-align:right;">
									<?php 
										echo $lineItems['quantity'];
									?>
								</td>
							</tr>
							<?php
							$i++;
						} 
					?>			
                </tbody>
            </table>
        </div>
    </body>
</html>


<script type="text/javascript"> 
	window.print();
	setTimeout(window.close, 2000);
	//window.onload=function(){self.print();} 
</script> 