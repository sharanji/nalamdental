<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@500&display=swap" rel="stylesheet">
<style>
	* {
		/* //font-size: 12px; */
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
	
	
	table.header-printheaderdata tr th
	{
		font-size: 22px;
		text-align: left;
		padding-top: 10px;
	}
	table.header-printdata tr td
	{
		font-size: 11px;
		padding-bottom: 4px;
		border: none;
	}
	table.header-printdata
	{
		margin-bottom: 5px;
	}
	
	table.header-printheaderdata tr th
	{
		font-size:12px;
		width:25%;
		padding:5px;
	}
	table.header-printheaderdata tr th, td 
	{
		border:1px solid #000;
		border-collapse: collapse;
		padding:5px;
		margin-top: 10px;
	}
</style>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <link rel="stylesheet" href="style.css">
        <title>Denomination</title>
    </head>
    <body>
        <div class="ticket">
            <img src="<?php echo base_url();?>uploads/logo.png" alt="logo" style="margin-bottom:30px;width:100%;">	
					
            <?php 
				$first_name = isset($headerData[0]['first_name']) ? $headerData[0]['first_name'] : NULL;
				$created_date = isset($headerData[0]['created_date']) ? date("d-M-Y_h:i:s_A",strtotime($headerData[0]['created_date'])) : NULL;
				$createdDate = isset($headerData[0]['created_date']) ? date("d-M-Y h:i:s A",strtotime($headerData[0]['created_date'])) : NULL;
				$last_login_date = isset($headerData[0]['last_login_date']) ? date("d-M-Y h:i:s A",strtotime($headerData[0]['last_login_date'])) : NULL;
				$logout_date = isset($headerData[0]['logout_date']) ? date("d-M-Y h:i:s A",strtotime($headerData[0]['logout_date'])) : NULL;
				$cash_amount = isset($headerData[0]['cash_amount']) ? number_format($headerData[0]['cash_amount'],DECIMAL_VALUE,'.','') : NULL;
				$upi_amount = isset($headerData[0]['upi_amount']) ? number_format($headerData[0]['upi_amount'],DECIMAL_VALUE,'.','') : NULL;
				$card_amount = isset($headerData[0]['card_amount']) ? number_format($headerData[0]['card_amount'],DECIMAL_VALUE,'.','') : NULL;
				$total_amount = number_format($cash_amount + $upi_amount + $card_amount,DECIMAL_VALUE,'.','');			
			?>
			<table class="header-printdata">
				<tr>
					<td>Cashier&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: </td>
					<td><?php echo $first_name;?></td>
				</tr>
				<?php /* <tr>
					<td>Created Date&nbsp;&nbsp;&nbsp;&nbsp;: </td>
					<td><?php echo $createdDate;?></td>
				</tr> */ ?>
				<tr>
					<td>Login Date&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: </td>
					<td><?php echo $last_login_date;?></td>
				</tr>
				<tr>
					<td>Closing Date&nbsp;&nbsp;&nbsp;: </td>
					<td><?php echo $logout_date;?></td>
				</tr>
				<tr>
					<td>Cash&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: </td>
					<td><?php echo $cash_amount;?></td>
				</tr>
				<tr>
					<td>UPI&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: </td>
					<td><?php echo $upi_amount;?></td>
				</tr>
				<tr>
					<td>Card&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: </td>
					<td><?php echo $card_amount;?></td>
				</tr>
			</table>
			<table class="header-printheaderdata">
				<tr>
					<th style="text-align:center;">S.No</th>
					<th style="text-align:center;">Currency</th>
					<th style="text-align:center;">Tot.Count</th>
					<th style="text-align:center;">Amount</th>
				</tr>
				<?php 
					$counter = 1;
					$totalValue = 0;
					foreach($lineData as $lineRow)
					{
						$currency = $lineRow["currency"];
						$currency_count = $lineRow["currency_count"];
						$currency_total = number_format($currency * $currency_count,DECIMAL_VALUE,'.','');
						?>
							<tr>
								<td style="text-align:center;"><?php echo $counter;?></td>
								<td style="text-align:right;"><?php echo $currency;?></td>
								<td style="text-align:right;"><?php echo $currency_count;?></td>
								<td style="text-align:right;"><?php echo $currency_total;?></td>
							</tr>
						<?php	
						$totalValue += $currency_total;
						$counter++;
					}
				?>
				<tr>
					<td colspan="4" style="text-align:right;">
						<?php echo number_format($totalValue,DECIMAL_VALUE,'.','');;?>
					</td>
				</tr>
			</table>
        </div>
    </body>
</html>

<script type="text/javascript"> 
	window.print();
	setTimeout(window.close, 2000);
	//window.onload=function(){self.print();} 
	
</script> 