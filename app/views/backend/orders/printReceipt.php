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
</style>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <link rel="stylesheet" href="style.css">
        <title>Order Receipt</title>
    </head>
    <body>
        <div class="ticket">
            <img src="<?php echo base_url();?>uploads/logo.png" alt="logo">	
			<P class="centered" style="font-size:13px;padding:0px;margin:0px;line-height:18px;">
			 <?php echo HEADER_VALUE; ?>	
		    </P>	
            <p class="centered" style="font-size:13px;padding:0px;margin:0px;line-height:18px;">
				<?php echo ADDRESS1; ?><?php if(!empty(ADDRESS2)){?>, <?php echo ADDRESS2; ?><?php } ?>
				<br>
				State:<?php echo STATE_NUMBER; ?>
				<br>
			   <!-- Phone : <?php #echo COUNTRY_CODE;?> - <?php #echo PHONE1; ?> -->
			   CIN:<?php echo CIN; ?>
			</p>
			<p class="centered" style="border-bottom:1px solid #000;padding:0px 0px;margin:5px 0px 0px 0px;line-height:20px;font-size:13px;">
				<?php #echo GST_NUMBER; ?>
			 	INVOICE
			</p>
			
			<?php
				/* if(isset($itemsprint[0]['cooking_instruction']) && !empty($itemsprint[0]['cooking_instruction']))
				{
					?>
					<span style="float:left;width:100%;padding-top:3px;font-size:14px;">
						<span style="font-size:14px;font-weight:600;">Instructions :</span>
						<?php 
							echo $itemsprint[0]['cooking_instruction'];
						?>
					</span>
					<?php 
				} */
			?>
			
			<?php 
				/* if(isset ($data[0]['options']) && !empty($data[0]['options']))
				{
					?>
					<span style="float:left;width:100%;padding-top:3px;font-size:14px;">
						<div class="row mt-2" style="border-bottom:1px solid #000;padding-bottom:5px;float:left;width:100%;font-size:14px;">
							<span style="font-size:14px;font-weight:600;">Delivery Instructions</span><br>
							<?php 
								$DeliveryInstruction =  unserialize($data[0]['options']);
								
								if(count($DeliveryInstruction))
								{
									foreach($DeliveryInstruction as $key=>$value)
									{
										if($value == 1) 
										{
											?>
											<span class="cooking-instruction" style="padding:2px 0px;float:left;width:100%;font-size:14px;">
												1. <?php echo "Contactless Delivery"; ?>
											</span>
											<br>
											<?php
										}

										if($value == 2)
										{
											?>
											<span class="cooking-instruction" style="padding:1px 0px;float:left;width:100%;font-size:14px;">
												2. <?php echo "Please Don't ring the bell"; ?>
											</span>
											<br>
											<?php
										}

										if($value == 3)
										{
											?>
											<span class="cooking-instruction" style="padding:1px 0px;float:left;width:100%;font-size:14px;">
												3. <?php echo "Don't send cutlery"; ?>
											</span>
											<?php
										}
									}
								}
							?>
						</div>
					</span>
					<?php 
				}  */
			?>
		
			
			<table>
                <tbody>
                    <tr>
                        <td style="font-size:13px;padding-top:5px;">Bill No  <span style="margin-left:25px;">:</span>  <?php echo $data[0]['order_number']; ?></td>
                    </tr>
					
					<?php 
						if(!empty($data[0]['customer_name']))
						{
							?>
							<tr>
								<td style="font-size:13px;">Customer <span style="margin-left:5px;">:</span>  <?php echo ucfirst($data[0]['customer_name']);?></td>
							</tr>
							<?php 
						} 
					?>
					<?php 
						if(!empty($data[0]['mobile_number']))
						{
							?>
							<tr>
								<td style="font-size:13px;">Mobile.No <span style="margin-left:1px;">:</span>  <?php echo COUNTRY_CODE;?> <?php echo ucfirst($data[0]['mobile_number']);?></td>
							</tr>
							<?php 
						} 
					?>
						
					<?php 
						if(isset($data[0]['order_source']) && $data[0]['order_source'] == "DINE_IN" )
						{
							?>
							<tr>
								<td style="font-size:13px;">Table<span style="margin-left:5px;">         :</span>  <?php echo ($data[0]['table_name']);?></td>
							</tr>
							<tr>
								<td style="font-size:13px;">Captain<span style="margin-left:1px;">      :</span>  <?php echo !empty($data[0]['waiter_name']) ? $data[0]['waiter_name'] : "Cashier";?></td>
							</tr>
							<?php 
						}
					?>

					<?php 
						if(isset($data[0]['order_source']) && ($data[0]['order_source'] != "IOS" || $data[0]['order_source'] != "ANDROID") )
						{
							$last_updated_by = isset($data[0]['last_updated_by']) ? $data[0]['last_updated_by'] : 0;
							$billed_by_query = "select per_people_all.first_name as billed_by from per_user as users 
							left join per_people_all on per_people_all.person_id = users.person_id
							where users.user_id = '".$last_updated_by."';
							";
					
							$getBilledBy = $this->db->query($billed_by_query)->result_array();

							$billedBy = isset($getBilledBy[0]["billed_by"]) ? $getBilledBy[0]["billed_by"] : NULL;
							
							if($billedBy)
							{
								?>
								<tr>
									<td style="font-size:13px;">Billed By<span style="margin-left:5px;">   :</span>  <?php echo $billedBy;?></td>
								</tr>

								<?php 
							}
						}
					?>

					
					<?php 
						if(isset($data[0]['attribute_1']) && $data[0]['attribute_1'] != NULL )
						{
							?>
							<tr>
								<td style="font-size:13px;">GST No.<span style="margin-left:18px;">:</span> <?php echo $data[0]['attribute_1'];?></td>
							</tr>
							<?php 
						} 
					?>


					<tr>
						<td style="font-size:13px;">Date / Time<span style="margin-left:10px;">:</span>  <?php echo date(DATE_FORMAT,strtotime($data[0]['created_date']));?> <?php echo date($this->time,strtotime($data[0]['created_date']));?></td>
					</tr>
					<?php /* 
					<tr>
						<td style="font-size:13px;">Date<span style="margin-left:39px;">:</span>  <?php echo date(DATE_FORMAT,strtotime($data[0]['created_date']));?></td>
					</tr>

					<tr>
                        <td style="font-size:13px;padding-bottom:5px;">Time<span style="margin-left:36px;">:</span>  <?php echo date($this->time,strtotime($data[0]['created_date']));?></td>
                    </tr> */ ?>
                </tbody>
            </table>
			
			<?php
				/* $LineQuery = "select 
					ord_order_lines.line_id,
					ord_order_lines.quantity,
					ord_order_lines.offer_percentage,
					ord_order_lines.tax_percentage,
					
					ord_order_lines.product_id,
					ord_order_lines.cancel_status,
					ord_order_headers.header_id,
					products.item_name,
					products.item_description,
					ord_order_lines.price,

					
					round((coalesce(offer_percentage,0) / 100) * (ord_order_lines.quantity * ord_order_lines.price),2) as offer_amount, 
					round( (ord_order_lines.quantity * ord_order_lines.price) - ((coalesce(offer_percentage,0) / 100) * (ord_order_lines.quantity * ord_order_lines.price)),2) as linetotal, 
					round(((ord_order_lines.quantity * ord_order_lines.price) - ((coalesce(offer_percentage,0) / 100) * (ord_order_lines.quantity * ord_order_lines.price))) * (coalesce(tax_percentage,0)/100),2) as tax_value 
					
					
					from ord_order_lines
					
				left join ord_order_headers on 
					ord_order_headers.header_id = ord_order_lines.header_id
				
				left join inv_sys_items as products on 
					products.item_id = ord_order_lines.product_id

				where  ord_order_lines.cancel_status ='N' and
					ord_order_lines.header_id='".$id."'";
			
				$LineData = $this->db->query($LineQuery)->result_array(); */
			?>
			
            <table style="border-top:1px solid black;">
                <thead>
                    <tr class="border-top">
                        <th class="description" style="padding:5px 0px;text-align:left;font-size:17px;">Item</th>
                        <th class="quantity" style="padding:5px 0px;font-size:17px;">Qty</th>
                        <th class="price" style="padding:5px 0px;"></th>
                        <th class="price" style="padding:5px 0px;text-align:right;font-size:17px;">Total</th>
                    </tr>
                </thead>
                <tbody>
					<?php 
						$i=1;
						$totalTax = $subTotal = $totalofferAmount = 0;
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
								<td class="description" style="font-size:17px;">
									<?php echo ucfirst($lineItems['item_description']);?>
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
								<td class="centered" style="font-size:17px;"><?php echo $lineItems['quantity'];?></td>
								<td class="price right" style="font-size:17px;"><?php #echo number_format($values['price'],DECIMAL_VALUE,'.',''); ?></td>
								<td class="price right" style="font-size:17px;">
								<?php 
									echo number_format($lineItems['price'] * $lineItems['quantity'],DECIMAL_VALUE,'.','');
									#echo $lineItems['linetotal'];
								?>
								</td>
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
							
								$i++;
								if($lineItems['cancel_status'] == 'Y')
								{
									$totalTax += 0;
									$subTotal += 0;
									$totalofferAmount += 0;
								}
								else
								{
									$totalTax += $lineItems['tax_value'];
									$subTotal += $lineItems['sub_total'];
									$totalofferAmount += $lineItems['offer_amount'];
								}
							} 
							$totalAmount = ($subTotal - $totalofferAmount) + $totalTax;
							$roundedAmount = round($totalAmount);
							$roundedValue = $roundedAmount - $totalAmount ;
					?>				
                </tbody>	
            </table>

			<table style="width:100%;border-top:1px solid black;border-bottom:1px solid black;">	
				
				<tr>
					<td style="text-align:left;font-size:15px;">Sub Total</td>
					<td style="text-align:right;font-size:15px;"><?php echo number_format($subTotal,DECIMAL_VALUE,'.','');?></td>
				</tr>
				
                <?php
                	if($totalofferAmount > 0)
                    {
                    	?>
                        	<tr>
								<td style="text-align:left;font-size:15px;">Discount</td>
								<td style="text-align:right;font-size:15px;"><?php echo number_format($totalofferAmount,DECIMAL_VALUE,'.','');?></td>
                			</tr>
                    	<?php
                    }
                ?>
                
				<tr>
					<td style="text-align:left;font-size:12px;">CGST</td>
					<td style="text-align:right;font-size:15px;"><?php echo number_format($totalTax / 2,DECIMAL_VALUE,'.','');?></td>
				</tr>

				<tr>
					<td style="text-align:left;font-size:12px;">SGST</td>
					<td style="text-align:right;font-size:15x;"><?php echo number_format($totalTax / 2 ,DECIMAL_VALUE,'.','');?></td>
				</tr>

				<?php /* 
				<tr>
					<td>Packing Charge</td>
					
					<td style="text-align:right;"><?php #echo number_format($subTotal ,DECIMAL_VALUE,'.','');?></td>
				</tr> */ ?>

				<tr>
					<td style="text-align:left;font-size:15px;">Round Off</td>	
					<td style="text-align:right;font-size:15px;"><?php echo number_format($roundedValue,DECIMAL_VALUE,'.','');?></td>
				</tr>

				<tr>
					<td style="text-align:left;font-size:18px;">Total</td>
					<td style="text-align:right;font-size:18px;"><?php echo number_format($totalAmount + $roundedValue,DECIMAL_VALUE,'.','');?></td>
				</tr>

				<?php
					if(isset($data[0]['order_source']) && 
						(
							$data[0]['order_source'] == "HOME_DELIVERY" 
							|| $data[0]['order_source'] == "POS"
						) 
					)
					{
						?>
						<tr>
							<td style="text-align:left;font-size:12px;">(Incl.packing charges)</td>
						</tr>
						<?php
					}
				?>
			</table>

			<table style="width:100%;">	
				<tr>
					<td>GSTIN:<?php echo GST_NUMBER;?></td>
				</tr>
				<tr>
					<td>FSSAI NO:<?php echo FSSAI_NUMBER;?></td>
				</tr>
			</table>
            <p class="centered" style="font-size:13px;">
			   <?php echo FOOTER_VALUE;?>
               <br><?php echo CONTACT_EMAIL;?><br>
			   Ph: <?php echo COUNTRY_CODE;?> <?php echo PHONE1; ?><br>
			   <!-- Powered by Jesperapps -->
			</p>
        </div>
    </body>
</html>

<script type="text/javascript"> 
	window.print();
	setTimeout(window.close, 2000);
	//window.onload=function(){self.print();} 
	
</script> 