<style>
	* {
		font-size: 12px;
		/* font-family: 'Times New Roman'; */
		font-family: 'Oswald';
		font-weight: '600';
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
		width: 90px;
		max-width: 90px;
	}

	td.quantity,th.quantity {
		width: 70px;
		max-width: 70px;
		word-break: break-all;
	}

	td.price,th.price {
		width: 55px;
		max-width: 55px;
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
		width: 230px;
		max-width: 230px;
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
        <title>Z - Report</title>
    </head>
    <body>
		<?php 
			if(isset($_SESSION['branch_id']) &&  $_SESSION['branch_id'] > 0){
            	$branch_id = isset($_SESSION['branch_id']) ? $_SESSION['branch_id'] : NULL;
            }else{
           		$branch_id = isset($_GET["branch_id"]) ? $_GET["branch_id"] : NULL;
            }

			$branchQry = "select 
			branch.branch_name,
			branch.mobile_number,
			location.address1,
			location.address2,
			location.address3,
			location.postal_code
			from branch 
			left join loc_location_all as location on location.location_id= branch.location_id
			left join geo_countries as country on country.country_id= location.country_id
			left join geo_states as state on state.state_id= location.state_id
			left join geo_cities as city on city.city_id= location.city_id
			where branch.branch_id = '".$branch_id."'";

			$getBranchDetails = $this->db->query($branchQry)->result_array();
			$branchName = isset($getBranchDetails[0]["branch_name"]) ? $getBranchDetails[0]["branch_name"] : NULL;
			$mobile_number = isset($getBranchDetails[0]["mobile_number"]) ? $getBranchDetails[0]["mobile_number"] : NULL;

			$address1 = !empty($getBranchDetails[0]["address1"]) ? $getBranchDetails[0]["address1"] : NULL;
			$address2 = !empty($getBranchDetails[0]["address2"]) ? ','.$getBranchDetails[0]["address2"] : NULL;
			$address3 = !empty($getBranchDetails[0]["address3"]) ? ','.$getBranchDetails[0]["address3"] : NULL;

			$postal_code = isset($getBranchDetails[0]["postal_code"]) ? $getBranchDetails[0]["postal_code"] : NULL;

			$branchAddress = $address1." ".$address2." ".$address3;
		?>

        <div class="ticket">
            <img src="<?php echo base_url();?>uploads/logo.png" alt="logo" style="padding:0px 15px;">			
            <p class="centered" style="font-size:16px;padding:0px;margin:0px;line-height:18px;">
				<?php echo isset($branchAddress) ? nl2br($branchAddress) : ""; ?>
				<br>
				<?php 
					if( isset($branchName) && !empty($branchName)  ) #&& $this->user_id !=1
					{
						?>
						Mobile Number - <?php echo isset($mobile_number) ? $mobile_number :""; ?>
						<?php 
					} 
				?>
			</p>
             
			
			<table>
                <tbody>
					<?php 
                		if(isset($branchName) && !empty($branchName) ) //&& $this->user_id !=1
                        {
                        	?>
							<tr>
								<td style="font-size:15px;padding-top:10px">
									<tr>
										<td width="40%" style="font-size:16px;padding-top:10px">
											<span style="width:5%;font-size:16px;"> Branch Name </span>
										</td>
										<td width="10%" style="font-size:16px;padding-top:10px">
											:
										</td>
										<td width="50%" style="font-size:16px;padding-top:10px">
											<span style="width:10%;font-size:16px;"><?php echo isset($branchName) ? $branchName :""; ?></span>
										</td>
									</tr>
								</td>
							</tr>
                            <?php  
                        }
						else
						{ 
							?>
							<tr>
								<td style="font-size:15px;padding-top:10px">
									<tr>
										<td width="40%" style="font-size:16px;padding-top:10px">
											<span style="width:5%;font-size:16px;"> </span>
										</td>
										<td width="10%" style="font-size:16px;padding-top:10px">
										
										</td>
										<td width="50%" style="font-size:16px;padding-top:10px">
										</td>
									</tr>
								</td>
							</tr>
                        	<?php
                        }
					?>
                                
					<tr>
                        <td style="font-size:16px;">
							<tr>
								<td width="40%" style="font-size:16px;">
									From Date
								</td>
								<td width="10%" style="font-size:16px;">
									:
								</td>
								<td width="50%" style="font-size:16px;">
									<?php echo $_GET['from_date']; ?>
								</td>
							</tr>
						</td>
                    </tr>
                                
                                
                                
					<tr>
						<td style="font-size:15px;">
							<tr>
								<td width="40%" style="font-size:16px;">
									To Date 
								</td>
								<td width="10%" style="font-size:16px;">
									:
								</td>
								<td width="50%" style="font-size:16px;">
									<?php echo $_GET['to_date']; ?>
								</td>
							</tr>
						</td>
                    </tr>
					<tr>
						<td style="font-size:16px;">
							<tr>
								<td width="40%" style="font-size:16px;">
									Print Date
								</td>
								<td width="10%" style="font-size:16px;">
									:
								</td>
								<td width="50%" style="font-size:16px;">
									<?php echo date("d-M-Y h:i a",time()); ?>
								</td>
							</tr>
						</td>
                     </tr>
				</tbody>
            </table>
			
			<?php
				foreach($resultData as $row)
				{
					#Total Sales		
					$total_cod_amount =  !empty($row['total_cod_amount']) ? $row['total_cod_amount'] : 0;
					$total_card_amount =  !empty($row['total_card_amount']) ? $row['total_card_amount'] : 0;
					$total_upi_amount =  !empty($row['total_upi_amount']) ? $row['total_upi_amount'] : 0;
					$total_cash_amount =  !empty($row['total_cash_amount']) ? $row['total_cash_amount'] : 0;
					$total_order_amount =  !empty($row['total_order_amount']) ? $row['total_order_amount'] : 0;
					//$total_order_amount =  $total_cod_amount + $total_card_amount + $total_upi_amount + $total_cash_amount;

					#Tax
					$tax_order_amount =  !empty($row['tax_order_amount']) ? $row['tax_order_amount'] : 0;
					$tax_cod_amount =  !empty($row['tax_cod_amount']) ? $row['tax_cod_amount'] : 0;
					$tax_card_amount =  !empty($row['tax_card_amount']) ? $row['tax_card_amount'] : 0;
					$tax_upi_amount =  !empty($row['tax_upi_amount']) ? $row['tax_upi_amount'] : 0;
					$tax_cash_amount =  !empty($row['tax_cash_amount']) ? $row['tax_cash_amount'] : 0;

					#POS
					$pos_order_amount =  !empty($row['pos_order_amount']) ? $row['pos_order_amount'] : 0;
					$pos_cod_amount =  !empty($row['pos_cod_amount']) ? $row['pos_cod_amount'] : 0;
					$pos_card_amount =  !empty($row['pos_card_amount']) ? $row['pos_card_amount'] : 0;
					$pos_upi_amount =  !empty($row['pos_upi_amount']) ? $row['pos_upi_amount'] : 0;
					$pos_cash_amount =  !empty($row['pos_cash_amount']) ? $row['pos_cash_amount'] : 0;

					#Dine In
					$din_order_amount =  !empty($row['din_order_amount']) ? $row['din_order_amount'] : 0;
					$din_cod_amount =  !empty($row['din_cod_amount']) ? $row['din_cod_amount'] : 0;
					$din_card_amount =  !empty($row['din_card_amount']) ? $row['din_card_amount'] : 0;
					$din_upi_amount =  !empty($row['din_upi_amount']) ? $row['din_upi_amount'] : 0;
					$din_cash_amount =  !empty($row['din_cash_amount']) ? $row['din_cash_amount'] : 0;


					#Online
					$onl_order_amount =  !empty($row['onl_order_amount']) ? $row['onl_order_amount'] : 0;
					$onl_cod_amount =  !empty($row['onl_cod_amount']) ? $row['onl_cod_amount'] : 0;
					$onl_card_amount =  !empty($row['onl_card_amount']) ? $row['onl_card_amount'] : 0;
					$onl_upi_amount =  !empty($row['onl_upi_amount']) ? $row['onl_upi_amount'] : 0;
					$onl_cash_amount =  !empty($row['onl_cash_amount']) ? $row['onl_cash_amount'] : 0;
					
					#Cancelled
					$total_cancelled_amount =  !empty($row['total_cancelled_amount']) ? $row['total_cancelled_amount'] : 0;
					$tax_cancelled_amount =  !empty($row['tax_cancelled_amount']) ? $row['tax_cancelled_amount'] : 0;
					$pos_cancelled_amount =  !empty($row['pos_cancelled_amount']) ? $row['pos_cancelled_amount'] : 0;
					$din_cancelled_amount =  !empty($row['din_cancelled_amount']) ? $row['din_cancelled_amount'] : 0;
					$onl_cancelled_amount =  !empty($row['onl_cancelled_amount']) ? $row['onl_cancelled_amount'] : 0;



					?>
					<!-- Sales start-->
					<p class="centered" style="padding:10px 0px;line-height:20px;font-size:18px;font-weight:600;border-top:1px dashed #444242;border-bottom:1px dashed #444242;">
						<b>Total Sales</b>
					</p>
					
					<table>
						<tbody>
							<tr>
								<td style="font-size:16px;">
									<tr>
										<td width="40%" style="font-size:16px;">COD Total</td>
										<td width="10%" style="font-size:16px;">:</td>
										<td width="50%" style="font-size:16px;">
											<?php echo number_format($total_cod_amount,DECIMAL_VALUE,'.',''); ?>
										</td>
									</tr>
								</td>
							</tr>
							
                            <tr>
								<td style="font-size:16px;">
									<tr>
										<td width="40%" style="font-size:16px;">Card Total</td>
										<td width="10%" style="font-size:16px;">:</td>
										<td width="50%" style="font-size:16px;">
											<?php  echo number_format($total_card_amount,DECIMAL_VALUE,'.','');?>
										</td>
									</tr>
								</td>
							</tr>
                                        
                            <tr>
								<td style="font-size:16px;">
									<tr>
										<td width="40%" style="font-size:16px;">UPI Total</td>
										<td width="10%" style="font-size:16px;">:</td>
										<td width="50%" style="font-size:16px;">
											<?php  echo number_format($total_upi_amount,DECIMAL_VALUE,'.','');?>
										</td>
									</tr>
								</td>
							</tr>
							
							<tr>
								<td style="font-size:16px;">
									<tr>
										<td width="40%" style="font-size:16px;">Cash Total</td>
										<td width="10%" style="font-size:16px;">:</td>
										<td width="50%" style="font-size:16px;">
											<?php  echo number_format($total_cash_amount,DECIMAL_VALUE,'.',''); ?>
										</td>
									</tr>
								</td>
							</tr>

							<tr>
								<td style="font-size:16px;">
									<tr>
										<td width="40%" style="font-size:16px;">Total</td>
										<td width="10%" style="font-size:16px;">:</td>
										<td width="50%" style="font-size:16px;"><?php echo number_format($total_order_amount,DECIMAL_VALUE,'.','');?></td>
									</tr>
								</td>
							</tr>
						</tbody>
					</table>
					<!-- Sales end-->


					<!-- POS With Tax start-->
					<p class="centered" style="padding:10px 0px;line-height:20px;font-size:18px;font-weight:600;border-top:1px dashed #444242;border-bottom:1px dashed #444242;">
						<b>POS Sales <span style='font-size:10px;'>(Takeaway,Home Delivery)</span></b>
					</p>
					
					<table>
						<tbody>
							<tr>
								<td style="font-size:16px;">
									<tr>
										<td width="40%" style="font-size:16px;">COD Total</td>
										<td width="10%" style="font-size:16px;">:</td>
										<td width="50%" style="font-size:16px;">
											<?php echo number_format($pos_cod_amount,DECIMAL_VALUE,'.',''); ?>
										</td>
									</tr>
								</td>
							</tr>
							
                            <tr>
								<td style="font-size:16px;">
									<tr>
										<td width="40%" style="font-size:16px;">Card Total</td>
										<td width="10%" style="font-size:16px;">:</td>
										<td width="50%" style="font-size:16px;">
											<?php  echo number_format($pos_card_amount,DECIMAL_VALUE,'.','');?>
										</td>
									</tr>
								</td>
							</tr>
                                        
                            <tr>
								<td style="font-size:16px;">
									<tr>
										<td width="40%" style="font-size:16px;">UPI Total</td>
										<td width="10%" style="font-size:16px;">:</td>
										<td width="50%" style="font-size:16px;">
											<?php  echo number_format($pos_upi_amount,DECIMAL_VALUE,'.','');?>
										</td>
									</tr>
								</td>
							</tr>
							
							<tr>
								<td style="font-size:16px;">
									<tr>
										<td width="40%" style="font-size:16px;">Cash Total</td>
										<td width="10%" style="font-size:16px;">:</td>
										<td width="50%" style="font-size:16px;">
											<?php  echo number_format($pos_cash_amount,DECIMAL_VALUE,'.',''); ?>
										</td>
									</tr>
								</td>
							</tr>

							<tr>
								<td style="font-size:16px;">
									<tr>
										<td width="40%" style="font-size:16px;">Total</td>
										<td width="10%" style="font-size:16px;">:</td>
										<td width="50%" style="font-size:16px;"><?php echo number_format($pos_order_amount,DECIMAL_VALUE,'.','');?></td>
									</tr>
								</td>
							</tr>
						</tbody>
					</table>
					<!-- POS end-->


					<!-- Dine In start-->
					<p class="centered" style="padding:10px 0px;line-height:20px;font-size:18px;font-weight:600;border-top:1px dashed #444242;border-bottom:1px dashed #444242;">
						<b>Dine In Sales</b>
					</p>
					
					<table>
						<tbody>
							<tr>
								<td style="font-size:16px;">
									<tr>
										<td width="40%" style="font-size:16px;">COD Total</td>
										<td width="10%" style="font-size:16px;">:</td>
										<td width="50%" style="font-size:16px;">
											<?php echo number_format($din_cod_amount,DECIMAL_VALUE,'.',''); ?>
										</td>
									</tr>
								</td>
							</tr>
							
                            <tr>
								<td style="font-size:16px;">
									<tr>
										<td width="40%" style="font-size:16px;">Card Total</td>
										<td width="10%" style="font-size:16px;">:</td>
										<td width="50%" style="font-size:16px;">
											<?php  echo number_format($din_card_amount,DECIMAL_VALUE,'.','');?>
										</td>
									</tr>
								</td>
							</tr>
                                        
                            <tr>
								<td style="font-size:16px;">
									<tr>
										<td width="40%" style="font-size:16px;">UPI Total</td>
										<td width="10%" style="font-size:16px;">:</td>
										<td width="50%" style="font-size:16px;">
											<?php  echo number_format($din_upi_amount,DECIMAL_VALUE,'.','');?>
										</td>
									</tr>
								</td>
							</tr>
							
							<tr>
								<td style="font-size:16px;">
									<tr>
										<td width="40%" style="font-size:16px;">Cash Total</td>
										<td width="10%" style="font-size:16px;">:</td>
										<td width="50%" style="font-size:16px;">
											<?php  echo number_format($din_cash_amount,DECIMAL_VALUE,'.',''); ?>
										</td>
									</tr>
								</td>
							</tr>

							<tr>
								<td style="font-size:16px;">
									<tr>
										<td width="40%" style="font-size:16px;">Total</td>
										<td width="10%" style="font-size:16px;">:</td>
										<td width="50%" style="font-size:16px;"><?php echo number_format($din_order_amount,DECIMAL_VALUE,'.','');?></td>
									</tr>
								</td>
							</tr>
						</tbody>
					</table>
					<!-- Dine In end-->

					<!-- Online Sales start-->
					<p class="centered" style="padding:10px 0px;line-height:20px;font-size:18px;font-weight:600;border-top:1px dashed #444242;border-bottom:1px dashed #444242;">
						<b>Online Sales</b>
					</p>
					
					<table>
						<tbody>
							<tr>
								<td style="font-size:16px;">
									<tr>
										<td width="40%" style="font-size:16px;">COD Total</td>
										<td width="10%" style="font-size:16px;">:</td>
										<td width="50%" style="font-size:16px;">
											<?php echo number_format($onl_cod_amount,DECIMAL_VALUE,'.',''); ?>
										</td>
									</tr>
								</td>
							</tr>
							
                            <tr>
								<td style="font-size:16px;">
									<tr>
										<td width="40%" style="font-size:16px;">Card Total</td>
										<td width="10%" style="font-size:16px;">:</td>
										<td width="50%" style="font-size:16px;">
											<?php  echo number_format($onl_card_amount,DECIMAL_VALUE,'.','');?>
										</td>
									</tr>
								</td>
							</tr>
                                        
                            <tr>
								<td style="font-size:16px;">
									<tr>
										<td width="40%" style="font-size:16px;">UPI Total</td>
										<td width="10%" style="font-size:16px;">:</td>
										<td width="50%" style="font-size:16px;">
											<?php  echo number_format($onl_upi_amount,DECIMAL_VALUE,'.','');?>
										</td>
									</tr>
								</td>
							</tr>
							
							<tr>
								<td style="font-size:16px;">
									<tr>
										<td width="40%" style="font-size:16px;">Cash Total</td>
										<td width="10%" style="font-size:16px;">:</td>
										<td width="50%" style="font-size:16px;">
											<?php  echo number_format($onl_cash_amount,DECIMAL_VALUE,'.',''); ?>
										</td>
									</tr>
								</td>
							</tr>

							<tr>
								<td style="font-size:16px;">
									<tr>
										<td width="40%" style="font-size:16px;">Total</td>
										<td width="10%" style="font-size:16px;">:</td>
										<td width="50%" style="font-size:16px;"><?php echo number_format($onl_order_amount,DECIMAL_VALUE,'.','');?></td>
									</tr>
								</td>
							</tr>
						</tbody>
					</table>
					<!-- Online Sales end-->

					<!-- Cancelled start-->
					<p class="centered" style="padding:10px 0px;line-height:20px;font-size:18px;font-weight:600;border-top:1px dashed #444242;border-bottom:1px dashed #444242;">
						<b>Total Cancelled</b>
					</p>
					
					<table>
						<tbody>
							<tr>
								<td style="font-size:16px;">
									<tr>
										<td width="40%" style="font-size:16px;">POS Cancelled</td>
										<td width="10%" style="font-size:16px;">:</td>
										<td width="50%" style="font-size:16px;">
											<?php echo number_format($pos_cancelled_amount,DECIMAL_VALUE,'.',''); ?>
										</td>
									</tr>
								</td>
							</tr>
							
                            <tr>
								<td style="font-size:16px;">
									<tr>
										<td width="40%" style="font-size:16px;">Dine In Cancelled</td>
										<td width="10%" style="font-size:16px;">:</td>
										<td width="50%" style="font-size:16px;">
											<?php  echo number_format($din_cancelled_amount,DECIMAL_VALUE,'.','');?>
										</td>
									</tr>
								</td>
							</tr>
                                        
                            <tr>
								<td style="font-size:16px;">
									<tr>
										<td width="40%" style="font-size:16px;">Online Cancelled</td>
										<td width="10%" style="font-size:16px;">:</td>
										<td width="50%" style="font-size:16px;">
											<?php  echo number_format($onl_cancelled_amount,DECIMAL_VALUE,'.','');?>
										</td>
									</tr>
								</td>
							</tr>
							
							<!-- <tr>
								<td style="font-size:16px;">
									<tr>
										<td width="40%" style="font-size:16px;">Cancelled Tax</td>
										<td width="10%" style="font-size:16px;">:</td>
										<td width="50%" style="font-size:16px;">
											<?php  #echo number_format($tax_cancelled_amount,DECIMAL_VALUE,'.',''); ?>
										</td>
									</tr>
								</td>
							</tr> -->

							<tr>
								<td style="font-size:16px;">
									<tr>
										<td width="40%" style="font-size:16px;">Total</td>
										<td width="10%" style="font-size:16px;">:</td>
										<td width="50%" style="font-size:16px;"><?php echo number_format($total_cancelled_amount,DECIMAL_VALUE,'.','');?></td>
									</tr>
								</td>
							</tr>
						</tbody>
					</table>
					<!-- Online Sales end-->

					<!-- Tax start-->
					<p class="centered" style="padding:10px 0px;line-height:20px;font-size:18px;font-weight:600;border-top:1px dashed #444242;border-bottom:1px dashed #444242;">
						<b>Total Tax</b>
					</p>

					<table>
						<tbody>
							<tr>
								<td style="font-size:16px;">
									<tr>
										<td width="40%" style="font-size:16px;">COD Tax</td>
										<td width="10%" style="font-size:16px;">:</td>
										<td width="50%" style="font-size:16px;">
											<?php echo number_format($tax_cod_amount,DECIMAL_VALUE,'.',''); ?>
										</td>
									</tr>
								</td>
							</tr>
							
                            <tr>
								<td style="font-size:16px;">
									<tr>
										<td width="40%" style="font-size:16px;">Card Tax</td>
										<td width="10%" style="font-size:16px;">:</td>
										<td width="50%" style="font-size:16px;">
											<?php  echo number_format($tax_card_amount,DECIMAL_VALUE,'.','');?>
										</td>
									</tr>
								</td>
							</tr>
                                        
                            <tr>
								<td style="font-size:16px;">
									<tr>
										<td width="40%" style="font-size:16px;">UPI Tax</td>
										<td width="10%" style="font-size:16px;">:</td>
										<td width="50%" style="font-size:16px;">
											<?php echo number_format($tax_upi_amount,DECIMAL_VALUE,'.','');?>
										</td>
									</tr>
								</td>
							</tr>

                            <tr>
								<td style="font-size:16px;">
									<tr>
										<td width="40%" style="font-size:16px;">Cash Tax</td>
										<td width="10%" style="font-size:16px;">:</td>
										<td width="50%" style="font-size:16px;">
											<?php echo number_format($tax_cash_amount,DECIMAL_VALUE,'.','');?>
										</td>
									</tr>
								</td>
							</tr>
							

							<tr>
								<td style="font-size:16px;">
									<tr>
										<td width="40%" style="font-size:16px;">Total</td>
										<td width="10%" style="font-size:16px;">:</td>
										<td width="50%" style="font-size:16px;"><?php echo number_format($tax_order_amount,DECIMAL_VALUE,'.','');?></td>
									</tr>
								</td>
							</tr>
						</tbody>
					</table>
					<!-- Tax end-->
			
					
					<?php 
				} 
			?>
        </div>
    </body>
</html>

<script type="text/javascript"> 
	window.print();
	setTimeout(window.close, 1000)
	//window.onload=function(){self.print();} 
</script> 