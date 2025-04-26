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
				KOT
			</p>
			
			<table>
                <tbody>
                    <tr>
                        <td style="font-size:13px;padding-top:5px;">Order No  <span style="margin-left:10px;">:</span>  <?php echo $data[0]['order_number']; ?></td>
                    </tr>

					<?php 
						if(isset($data[0]['order_source']) && $data[0]['order_source'] == "DINE_IN" )
						{
							?>
							<tr>
								<td style="font-size:13px;">Table<span style="margin-left:5px;">         :</span>  <?php echo isset($data[0]['table_name']) ? ($data[0]['table_name']) : NULL;?></td>
							</tr>
							<tr>
								<td style="font-size:13px;">Waiter<span style="margin-left:1px;">        :</span>  <?php echo !empty($data[0]['waiter_name']) ? $data[0]['waiter_name'] : "Admin";?></td>
							</tr>
							<?php 
						} 
					?>
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
							if($lineItems['cancel_status'] == 'Y')
							{
								$cancelStatus="color:red";
							}else{
								$cancelStatus="";
							}

							$ingredientsQry = "select 
									ord_ing_tbl.ingredient_amount,
									ing_line_tbl.ingredient_name,
									ing_line_tbl.ingredient_description from ord_order_lines_ingredients as ord_ing_tbl
								left join inv_item_ingredient_line as ing_line_tbl on 
									ing_line_tbl.ing_line_id = ord_ing_tbl.ingredient_id
								where 
								header_id='".$id."'
								and line_id='".$lineItems['line_id']."'
								";
							$checkIngredients = $this->db->query($ingredientsQry)->result_array();
							
							?>
							<tr class="border-top" style="<?php echo $cancelStatus;?> border-bottom:1px solid white;">
								<td class="description" style="font-size:13px;">
									<?php 
										if($lineItems['cancel_status'] == 'Y')
										{
											?>
											<del><?php echo ucfirst($lineItems['item_description']);?></del>
											<?php 
										}
										else
										{
											echo ucfirst($lineItems['item_description']);
											if(isset($lineItems['cooking_instructions']) && $lineItems['cooking_instructions'] !='')
											{
												?>
												<span style="float:left;width:100%;font-size:9px;">(<?php echo ucfirst($lineItems['cooking_instructions']);?>)</span>
												<?php
											}
										}
									?>
									<?php
										/* if(count($checkIngredients) > 0)
										{
											foreach ($checkIngredients as $ingredient) 
											{ 
												?>
												<span style="font-size:10px;font-style:italic;"><?php echo $ingredient['ingredient_name']; ?></span>
												<?php
											}
										} */
									?>
								</td>
								<td class="price right" style="font-size:13px;"></td>
								
								<td class="centered" style="font-size:13px;text-align:right;">
									<?php 
										if($lineItems['cancel_status'] == 'Y')
										{
											?>
											<del><?php echo ucfirst($lineItems['quantity']);?></del>
											<?php 
										}
										else
										{
											?><?php echo ucfirst($lineItems['quantity']);?>
											<?php
										} 
									?>
								</td>
								
								<?php /*
								<td class="price right" style="font-size:13px;"><?php echo number_format($values['price'],DECIMAL_VALUE,'.',''); ?></td>
								<td class="price right" style="font-size:13px;"><?php echo $lineItems['linetotal'];?></td> */ ?>
							</tr>
							
							<?php
								if(count($checkIngredients) > 0)
								{ 
									?>
									<tr class="border-top" style="border-bottom:1px solid white;<?php echo $cancelStatus;?> ">
										<td>
											<?php
												foreach ($checkIngredients as $ingredient) 
												{ 
													?>
													<span style="font-size:10px;font-style:italic;">
														<?php echo $ingredient['ingredient_name']; ?>
													</span>
													<?php
												}
											?>
										</td>
									</tr>
									<?php 
								}
							?>


							<?php
							$i++;
							/* if($lineItems['cancel_status'] == 'Y')
							{
								$totalTax += 0;
								$subTotal += 0;
							}
							else
							{
								$totalTax += $lineItems['tax_value'];
								$subTotal += $lineItems['linetotal'];
							} */
						} 
						/* $totalAmount = $subTotal + $totalTax;
						$roundedAmount = round($totalAmount);
						$roundedValue = $roundedAmount - $totalAmount ; */
					?>			
                </tbody>
            </table>

			<?php /*
			<table style="width:100%;border-top:1px solid black;border-bottom:1px solid black;">	
				<tr>
					<td>Sub Total</td>
					
					<td style="text-align:right;"><?php echo number_format($subTotal,DECIMAL_VALUE,'.','');?></td>
				</tr>
				
				<tr>
					<td>CGST</td>
					
					<td style="text-align:right;"><?php echo number_format($totalTax / 2,DECIMAL_VALUE,'.','');?></td>
				</tr>

				<tr>
					<td>SGST</td>
					
					<td style="text-align:right;"><?php echo number_format($totalTax / 2 ,DECIMAL_VALUE,'.','');?></td>
				</tr>

				<tr>
					<td>Round Off</td>
					
					<td style="text-align:right;"><?php echo number_format($roundedValue,DECIMAL_VALUE,'.','');?></td>
				</tr>

				<tr>
					<td style="font-size:18px;">Total</td>
					
					<td style="text-align:right;font-size:18px;"><?php echo number_format($totalAmount + $roundedValue,DECIMAL_VALUE,'.','');?></td>
				</tr>

			</table>

			<table style="width:100%;">	
				<tr>
					<td>GSTIN: <?php echo GST_NUMBER;?></td>
				</tr>
				
				<tr>
					<td>FSSAI NO: <?php echo FSSAI_NUMBER;?></td>
				</tr>
			</table>

            <p class="centered" style="font-size:13px;">
			   THANK YOU !!! VISIT AGAIN
               <br><?php echo CONTACT_EMAIL;?><br>
			   Ph: <?php echo COUNTRY_CODE;?> <?php echo PHONE1; ?><br>
			   Powered by Jesperapps
			</p> */ ?>
        </div>
    </body>
</html>


<script type="text/javascript"> 
	window.print();
	setTimeout(window.close, 2000);
	//window.onload=function(){self.print();} 
</script> 