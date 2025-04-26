<style>
	table {
		border-collapse: collapse;
		width: 100%;
		font-size: 14px;
	}
	th, td {
		border-collapse: collapse;
		border: 1px solid black;
		padding: 5px;
		text-align: left;
	}
	
</style>

<table>
	<tbody>
		<tr>
			<td style="width:50%;font-family: 'Times New Roman', Times, serif;padding : 0px; margin:0px"><span style="font-weight:bold;">SUPPLIER DETAILS</span></td>
			<td style="width:50%;font-family: 'Times New Roman', Times, serif;"><span style="font-weight:bold">DELIVER TO</span></td>
		</tr>
		<tr >
			<td style="width:50%;border-bottom: none !important;vertical-align:top;">
				<?php
					$supplier_name	=isset($edit_data[0]['supplier_name']) ? $edit_data[0]['supplier_name'] : NULL;
					$site_name		=isset($edit_data[0]['site_name']) ? $edit_data[0]['site_name'] : NULL;
					$address1		=isset($edit_data[0]['address1']) ? $edit_data[0]['address1'] : NULL;
					$address2		=isset($edit_data[0]['address2']) ? $edit_data[0]['address2'] : NULL;
					$address3		=isset($edit_data[0]['address3']) ? $edit_data[0]['address3'] : NULL;
					$city_name		=isset($edit_data[0]['city_name']) ? $edit_data[0]['city_name'] : NULL;
					$state_name		=isset($edit_data[0]['state_name']) ? $edit_data[0]['state_name'] : NULL;
					$gst_number		=isset($edit_data[0]['gst_number']) ? $edit_data[0]['gst_number'] : NULL;
					$cin_number		=isset($edit_data[0]['cin_number']) ? $edit_data[0]['cin_number'] : NULL;
					$email_address	=isset($edit_data[0]['email_address']) ? $edit_data[0]['email_address'] : NULL; 
					$contact_person	=isset($edit_data[0]['contact_person']) ? $edit_data[0]['contact_person'] : NULL; 
					$mobile_number	=isset($edit_data[0]['mobile_number']) ? $edit_data[0]['mobile_number'] : NULL; 
				
					if($supplier_name!=NULL)
					{
						echo $supplier_name;
						?><br>
						<?php
					}
					
					if($site_name!=NULL)
					{
						echo $site_name;
						?><br>
						<?php
					}
					
					if($address1!=NULL){
						echo $address1;
						?><br>
						<?php
					}
					if($address2!=NULL){
						echo $address2;
						?><br>
						<?php
					}
					if($address3!=NULL){
						echo $address3;
						?><br>
						<?php
					}
					if($city_name!=NULL){
						echo $city_name;
						?>,
						<?php
					}
					if($state_name!=NULL){
						echo $state_name;
						?><br>
						<?php
					}

					if($gst_number!=NULL){
						
						?><span>GST Number &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:- <?php echo $gst_number?></span><br>
						<?php
					}
					if($cin_number!=NULL){
						
						?><span>CIN Number &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:- <?php echo $cin_number?></span><br>
						<?php
					}
					if($email_address!=NULL){
						
						?><span>Email &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:- <?php echo $email_address?></span><br>
						<?php
					}

					if($contact_person !=NULL && $mobile_number !=NULL){
						?>
							<span>Contact Person &nbsp;&nbsp;&nbsp;:- <?php echo $contact_person ?> - <?php echo $mobile_number?></span><br>
						<?php
					}
					else{
						if($contact_person !=NULL){
							?>
								<span>Contact Person &nbsp;&nbsp;:- <?php echo $contact_person?>
							<?php
						}
						if($mobile_number !=NULL){
							?>
								<span>Mobile Number &nbsp;&nbsp;:- <?php echo $mobile_number ?>
							<?php
						}
					}
				?>

			</td>
			<td style="width:50%;border-left:none ! important;border-bottom: none !important;vertical-align:top;">
				<span><?php echo COMPANY_NAME;?><br></span>
				<?php echo ADDRESS1;?><br>
				<span>GST No :- <?php echo GST_NUMBER;?></span><br>
				<span>CIN No :- <?php echo CIN;?></span><br>
				<span>Email :- <?php echo CONTACT_EMAIL;?></span><br>
			</td>
		</tr>
	</tbody>
</table>
<table width="100%" style="font-size:12px;">
	<thead>
		<tr>
			<th style="text-align:center;">S.No</th>
			<th class="text-center tab-md-150">Item Name</th>
			<th class="text-center tab-md-100" style="text-align:center">UOM </th>
			<th class="text-center tab-md-100" style="text-align:right">Delivery Date</th>							
			<th class="text-center tab-md-100" style="text-align:center">Qty</th>
			<th class="text-center tab-md-100" style="text-align:right">Base Price</th>							
			<th class="text-center tab-md-100" style="text-align:right">Tax</th>							
			<th class="text-center tab-md-100">Discount Type</th>							
			<th class="text-center tab-md-100" style="text-align:right">Discount</th>							
			<th class="text-center tab-md-100" style="text-align:right">Line Total</th>											
		</tr>
	</thead>
	<tbody>
		<?php 
			if( count($line_data) > 0)
			{
				$counter = 1;
				$sub_total=0;
				$total_tax=0;
				$total_discount=0;
				foreach($line_data as $lineItems)
				{
					?>
					<tr>
						<td style="text-align:center;">
							<?php echo $counter;?>
						</td>
						<td class="tab-md-150">
							<?php echo $lineItems["item_name"];?>
						</td>
						
						<td class="tab-md-100" style="text-align:center">
							<?php echo $lineItems["uom_code"];?>
						</td>
						
						<td class="tab-md-100" style="text-align:right">
							<?php echo date(DATE_FORMAT,strtotime($lineItems['delivery_date']));?>
						</td>
						
						
						<td class="tab-md-150" style="text-align:center">
							<?php echo $lineItems["quantity"];?>
						</td>
						
						<td class="tab-md-100" style="text-align:right">
							<?php echo isset($lineItems["base_price"]) ? number_format($lineItems["base_price"], 2) : '0.00'; ?>
						</td>
						<td class="tab-md-100" style="text-align:right">
						<?php echo isset($lineItems["total_tax"]) ? number_format($lineItems["total_tax"], 2) : '0.00'; ?>
						</td>
						<td class="tab-md-100">
							<?php echo $lineItems["discount_type"];?>
						</td>
						
						<td class="tab-md-100" style="text-align:right">
							
							<?php 
								if($lineItems['discount_type']=='Amount'){

									echo isset($lineItems["discount"]) ? number_format($lineItems["discount"], 2) : '0.00'; 
								}
								else if($lineItems['discount_type']=='Percentage'){
	
									echo isset($lineItems["discount"]) ? $lineItems["discount"].'%': '0.00%'; 
								}
							?>
							
						</td>
						<td class="tab-md-100" style="text-align:right">
							
							<?php 
								$total=$lineItems["base_price"]*$lineItems["quantity"];
							
								echo number_format($total, 2); ?>
						</td>

						<?php
							$sub_total+=$total;
							$total_tax+=$lineItems["total_tax"];

							if($lineItems['discount_type']=='Amount'){

								$discount=$lineItems["discount"];
								$total_discount+=$discount*$lineItems["quantity"];
							}
							else if($lineItems['discount_type']=='Percentage'){

								$discount=$lineItems["base_price"]*($lineItems["discount"]/100);

								$total_discount+=$discount*$lineItems["quantity"];
							}
							else{
								$total_discount=0;
							}
							
							
						?>
					</tr>

					<?php
					$counter++;
				} 
			} 
		?>
		<tr style="border-top:none !important;width:100%">
			<td colspan="7" style="border-bottom:none ! important;border-top:none ! important"></td>
            <td colspan="2" style="font-weight:bold">Sub Total</td>
            <td colspan="1" style="font-weight:bold;text-align:right"><?php echo number_format($sub_total,2) ?></td>
		</tr>

		<tr style="border-top:none !important;width:100%">
			<td colspan="7" style="border-bottom:none ! important;border-top:none ! important"></td>
            <td colspan="2" style="font-weight:bold">Total Discount</td>
            <td colspan="1" style="font-weight:bold;text-align:right"><?php echo number_format($total_discount,2)?></td>
		</tr>
		
		<tr style="border-top:none !important;width:100%">
			<td colspan="7" style="border-bottom:none ! important;border-top:none ! important"></td>
            <td colspan="2" style="font-weight:bold">Total Tax</td>
            <td colspan="1" style="font-weight:bold;text-align:right"><?php echo number_format($total_tax,2) ?></td>
		</tr>
		
		<tr style="border-top:none !important;width:100%">
			<td colspan="7" style="border-bottom:none ! important;border-top:none ! important"></td>
            <td colspan="2" style="font-weight:bold;border-bottom: none ! important">Grand Total</td>
            <td colspan="1" style="font-weight:bold;text-align:right;border-bottom: none ! important"><?php echo number_format((($sub_total - $total_discount) + $total_tax) ,2)?></td>
		</tr>
		
	</tbody>
</table>

<table>
	<tr>
		<?php
			$final_value = ($sub_total - $total_discount) + $total_tax;
			$final_value_in_words = amountinwords($final_value);
		?>
		<td style="border-top: 1px solid #000;border-bottom:none ! important"><span style="font-weight:bold">Amount In Words&nbsp;:</span>&nbsp;<?php echo $final_value_in_words,'.'?></td>
	</tr>
</table>

<table>
	<tr>
		<td style="width:50%;vertical-align:top;">
			<p style="vertical-align:top;">This is computer generated, so no signature required.</p>
		</td>
		<td style="width:50%;border-left:none ! important;text-align:center">
			<span>For</span>

			<br>
			<br>
			<br>
			<br>

			<span>Signature</span>
		</td>
	</tr>
</table>

