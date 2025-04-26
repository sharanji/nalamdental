<?php 
	//print_r($cart_item);
	/* $branch_id = isset($_SESSION['branch_id']) ? $_SESSION['branch_id'] : 0;
	
	$BranchZonesQry = "select latitude,longitude,zone_name from 
	vb_branch_zones 
		where 
			vb_branch_zones.branch_id=".$branch_id." and 
				vb_branch_zones.zone_status=1
	";
	$getBranchZones = $this->db->query($BranchZonesQry)->result_array();
	
	if( !empty($_SESSION['DELIVERY_LATITUDE']) && !empty($_SESSION['DELIVERY_LONGITUDE']) && isset($_SESSION['DELIVERY_LATITUDE']) && isset($_SESSION['DELIVERY_LONGITUDE']))
	{
		#echo "delivery";
		#echo $_SESSION['DELIVERY_LATITUDE']."".$_SESSION['DELIVERY_LONGITUDE'];
		
		$currentLat  = isset($_SESSION['DELIVERY_LATITUDE']) ? $_SESSION['DELIVERY_LATITUDE'] : "12.745458";
		$currentLon = isset($_SESSION['DELIVERY_LONGITUDE']) ? $_SESSION['DELIVERY_LONGITUDE'] : "77.81027069999999";
	
	}
	else
	{
		#echo "current";
		$currentLat = isset($_COOKIE['CURRENT_LATITUDE']) ? $_COOKIE['CURRENT_LATITUDE']:"";
		$currentLon = isset($_COOKIE['CURRENT_LONGITUDE']) ? $_COOKIE['CURRENT_LONGITUDE']:"";
	}
	
	$deliveryAddress = getDeliveyrAddress($currentLat,$currentLon);
	
	#echo $deliveryAddress;
	
	$deliveryAvailable = 0;
	if( count($getBranchZones) > 0)
	{
		foreach($getBranchZones as $zones)
		{
			$getZoneAddress = getDeliveyrAddress($zones['latitude'],$zones['longitude']);
			$ZoneAddress1 = $getZoneAddress.",";
			$zoneAddress1 = array_filter(explode(",",$ZoneAddress1));
			
			$zoneAddress = array_flip($zoneAddress1);
			
			#print_r($zoneAddress);
			
			if(is_array($zoneAddress))
			{
				if(array_key_exists($deliveryAddress, $zoneAddress) && in_array($deliveryAddress, $zoneAddress))
				{
					$deliveryAvailable = 1;
				}
			}
			else
			{
				$deliveryAvailable = 0;
			}
		}
	}
	else
	{
		$deliveryAvailable = 1;
	} */
	
	
	if( !empty($_SESSION['DELIVERY_LATITUDE']) && !empty($_SESSION['DELIVERY_LONGITUDE']) && isset($_SESSION['DELIVERY_LATITUDE']) && isset($_SESSION['DELIVERY_LONGITUDE']))
	{
		$lat  = isset($_SESSION['DELIVERY_LATITUDE']) ? $_SESSION['DELIVERY_LATITUDE'] : "12.745458";
		$lon = isset($_SESSION['DELIVERY_LONGITUDE']) ? $_SESSION['DELIVERY_LONGITUDE'] : "77.81027069999999";
		#echo $lat."==".$lon;
	}
	else
	{
		unset($_SESSION['DELIVERY_LATITUDE']); 
		unset($_SESSION['DELIVERY_LONGITUDE']); 
		unset($_SESSION['DELIVERY_ADDRESS']);
		
		//echo $_COOKIE['DELIVERY_LATITUDE'];
		$lat  = isset($_COOKIE['CURRENT_LATITUDE']) ? $_COOKIE['CURRENT_LATITUDE'] : "12.745458";
		$lon = isset($_COOKIE['CURRENT_LONGITUDE']) ? $_COOKIE['CURRENT_LONGITUDE'] : "77.81027069999999";
	}
	
	$deliveryAddress1 = getDeliveyrZoneBranches($lat,$lon);
	$FilerdeliveryAddress =  array_filter(explode("@",$deliveryAddress1));
	$deliveryAddress = isset($FilerdeliveryAddress[0]) ? $FilerdeliveryAddress[0]:"";
	$deliveryAddress_1 = isset($FilerdeliveryAddress[1]) ? $FilerdeliveryAddress[1] :"";
	
	# Single Long name | Neighbourhood only start
	if(!empty($deliveryAddress))
	{
		$condition = ' (branch.branch_status = 1 ) and ';
		
		if(!empty($deliveryAddress) && !empty($deliveryAddress_1) )
		{
			$condition .= '  
			(
				vb_branch_zones.zone_name like "%'.($deliveryAddress).'%" or
				vb_branch_zones.zone_name like "%'.($deliveryAddress_1).'%"
			)  and ';	
		}
		else
		{
			$condition .= '  
			(
				vb_branch_zones.zone_name like "%'.($deliveryAddress).'%"
			)  and ';
		}
	
		$condition .= ' vb_branch_zones.zone_status = 1 ';
			
		if(!empty($_POST["query"]))
		{
			$condition .= ' and (
							branch.branch_name like "%'.($_POST["query"]).'%" or 
							branch.branch_code like "%'.($_POST["query"]).'%" or
							branch.address like "%'.($_POST["query"]).'%"
						)
					';
		}
	}
	else
	{
		$condition = ' branch.branch_status = 1 and vb_branch_zones.zone_status = 1';
	} 
	# Single Long name | Neighbourhood only end
	
	
	$branchQuery = "select 
			branch.branch_id,
			branch.branch_name,
			branch.branch_code,
			branch.address,
			branch.phone_number,
			branch.delivery_distance

			from branch 
			
			left join vb_branch_zones on
				vb_branch_zones.branch_id = branch. branch_id
			
				where ".$condition." group by branch.branch_name
			ORDER BY branch.branch_name asc
	";
	
	$result = $this->db->query($branchQuery)->result_array();
	
	if( count($result) > 0 )
	{
		$deliveryAvailable = 1;
	}
	else
	{
		$deliveryAvailable = 0;
	}
	
	
	$todayInTime = date("H:i");
	$todayOutTime = date("H:i");
	$branch_id = isset($_SESSION["branch_id"]) ? $_SESSION["branch_id"] : 0;
								
	$BranchQuery = "select opening_time,closing_time,minimum_order_value from branch where branch_id='".$branch_id."' ";
	$getBranchDetails = $this->db->query($BranchQuery)->result_array();
	
	$openingTime = isset($getBranchDetails[0]['opening_time']) ? $getBranchDetails[0]['opening_time'] : 0;
	$closingTime = isset($getBranchDetails[0]['closing_time']) ? $getBranchDetails[0]['closing_time'] : 0;
	$minimumOrderValue = isset($getBranchDetails[0]['minimum_order_value']) ? $getBranchDetails[0]['minimum_order_value'] : 0;
									
	if(isset($cart_item) && $cart_item)
	{
		?>
		<ul class="order-summary-checkout clearfix">
			<?php
				$subTotal = 0;
				#$sessionCart = array_shift($_SESSION["cart_item"]);
				#print_r($_SESSION["cart_item"]);
				foreach ($cart_item as $code => $item)
				{
					$SessionCatID = isset($item["category_id"]) ? $item["category_id"] : 0;
					$product_id = $item["product_id"];
					$availableQty = isset($item["available_quantity"]) ? $item["available_quantity"] : 0;
					$minimum_order_quantity = isset($item["minimum_order_quantity"]) ? $item["minimum_order_quantity"] : 0;
					?>
					<div class="order-summ-items product_table cartproduct_<?php echo $product_id?>">
						<div class="row">
							<div class="col-md-7 pl-2">
								<li class="price-summary">
									<?php echo ucfirst($item["product_name"]);?>
								</li>
								<li class="price-peritem-summary">
									<?php echo CURRENCY_CODE;?> 
									<?php echo number_format($item["price"],DECIMAL_VALUE,'.','');?> |
									<a class="summary-delete" onclick="removeorders(<?php echo $product_id ?>,'<?php echo $code;?>')" href="javascript:void(0);" class="action">
										<!--<a class="summary-delete" onclick="removeorders(<?php echo $product_id ?>,'<?php echo $code;?>')" href="<?php echo base_url();?>remove-cart-item/<?php echo $item["product_code"]; ?>/<?php echo $item["product_id"]; ?>.html" class="action">-->
										Remove
									</a>
								</li>
							</div>
							<div class="col-md-5 pr-1">
								<div class="rightside-section-order">
									<div class="increment-decrement">
										<ul class="row">
											<li class="decrement-summary minus<?php echo $product_id;?>">-</li>
											<li class="count-summary li_quantity<?php echo $product_id;?>"><?php echo $item["quantity"];?></li>
											<li class="increment-summary plus<?php echo $product_id;?>">+</li>
										</ul>
									</div>
								</div>
							</div>
						</div>
						
						<div class="row">
							<!-- ingredients start -->
							<div class="col-md-9 pl-2">
								<?php 
									$ingredients = isset($item["ingredients"]) ? $item["ingredients"] :"";
									$ingredientAmount =0;
									if(!empty($ingredients))
									{
										?>
										<li class="price-peritem-summary">
											<?php 
												$unserializeIngredients = unserialize($ingredients);
												echo count($unserializeIngredients);
											?>
											ADD ONS : <br>
											<?php
												foreach($unserializeIngredients as $key => $value)
												{
													$IngredientID = $key;
													$IngredientPrice = $value;
													
													$IngredientsQuery = "select ingredient_name from 
														vb_product_ingredients
														where 
															ingredient_id='".$IngredientID."' and 
																product_id='".$product_id."'
														";
													$getIngredients = $this->db->query($IngredientsQuery)->result_array();
													?>
													<?php $Ingredients = $getIngredients[0]['ingredient_name'].' | '; ?>
													<?php echo $Ingredients; ?>
													<?php
													$ingredientAmount += $IngredientPrice;
												}
											?>
										</li>
										<?php 
									} 
								?>
							</div>
							<!-- ingredients end -->
							
							<div class="col-md-3">
								<div class="rightside-section-order">
									<div class="product-per-price">
										<?php echo CURRENCY_CODE;?> 
										<span class="itemTotal<?php echo $product_id;?>">
											<?php 
											$totalItemAmount = $item["price"] ;
											$totalAmount = ($totalItemAmount + $ingredientAmount) * $item["quantity"];
											echo number_format($totalAmount,DECIMAL_VALUE,'.','');
										?>
										</span>
										<input type="hidden" name="linetotal[]" id="linetotal<?php echo $product_id;?>" value="<?php echo number_format($totalAmount,DECIMAL_VALUE,'.','');?>">
										<input type="hidden" name="ingredients[]" id="ingredients<?php echo $product_id;?>" value='<?php echo $ingredients;?>'>
										<input type="hidden" name="ingredientAmount[]" id="ingredientAmount<?php echo $product_id;?>" value="<?php echo number_format($ingredientAmount,DECIMAL_VALUE,'.','');?>">
									</div>
								</div>
							</div>
						</div>
						
						<input type="hidden" name="category_id[]" id="category_id<?php echo $product_id;?>" value="<?php echo $SessionCatID;?>">
						<input type="hidden" name="product_id[]" id="product_id<?php echo $product_id;?>" value="<?php echo $product_id;?>">
						<input type="hidden" name="price[]" id="price<?php echo $product_id;?>" value="<?php echo $item["price"];?>">
						<input type="hidden" name="quantity[]" id="quantity<?php echo $product_id;?>" value="<?php echo $item["quantity"];?>">
						<input type="hidden" name="minimum_order_quantity[]" id="minimum_order_quantity<?php echo $product_id;?>" value="<?php echo isset($minimum_order_quantity) ? $minimum_order_quantity :0;?>">
					</div>
					
					<script>
						/** Qty Increment **/
						$('.plus<?php echo $product_id;?>').click(function()
						{
							var incQty = parseInt($('.li_quantity<?php echo $product_id;?>').html());
							var totalQty =  incQty;
							var minimum_order_quantity = $('#minimum_order_quantity<?php echo $product_id;?>').val();
							
							if(totalQty < minimum_order_quantity || minimum_order_quantity == 0)
							{	
								var availableQty = '<?php echo $availableQty;?>';
								var incQty = $('#quantity<?php echo $product_id;?>').val();
								if( incQty == availableQty )
								{
									alert("Available Qty is "+availableQty);
								}
								else
								{
									var incQty = parseInt($('.li_quantity<?php echo $product_id;?>').html());
									var totalQty =  incQty + 1;
									$('.li_quantity<?php echo $product_id;?>').html(totalQty);
									$('#quantity<?php echo $product_id;?>').val(totalQty);
									
									$('.cart_qty<?php echo $product_id;?>').text(totalQty);
									
									var quantity = $('#quantity<?php echo $product_id;?>').val();
									var price = $('#price<?php echo $product_id;?>').val();
									var ingredientAmount = $('#ingredientAmount<?php echo $product_id;?>').val();
									var product_id = '<?php echo $product_id;?>';
									cartCalculateRow(quantity,price,ingredientAmount,product_id,minimum_order_quantity,availableQty);
									cartCalculateGrandTotal();
								}
							}
							else if(totalQty >= minimum_order_quantity)
							{
								alert("Max Order Qty is "+minimum_order_quantity+" only.");
							}
						});
						
						/** Qty Decrement **/
						$('.minus<?php echo $product_id;?>').click(function()
						{
							var decQty = $('.li_quantity<?php echo $product_id;?>').html();
							
							if(decQty == 1)
							{
								$('.li_quantity<?php echo $product_id;?>' ).html(1);
								$('#quantity<?php echo $product_id;?>').val(1);
								$('.cart_qty<?php echo $product_id;?>').text(1);
							}
							else if(decQty >= 1)
							{
								var decQty = parseInt($('.li_quantity<?php echo $product_id;?>').html());
								var totalQty =  decQty - 1;
								$('.li_quantity<?php echo $product_id;?>' ).html(totalQty);
								$('#quantity<?php echo $product_id;?>').val(totalQty);
								$('.cart_qty<?php echo $product_id;?>').text(totalQty);
							}
							
							var quantity = $('#quantity<?php echo $product_id;?>').val();
							var price = $('#price<?php echo $product_id;?>').val();
							var ingredientAmount = $('#ingredientAmount<?php echo $product_id;?>').val();
							var product_id = '<?php echo $product_id;?>';
							var minimum_order_quantity = $('#minimum_order_quantity<?php echo $product_id;?>').val();
							var availableQty = '<?php echo $availableQty;?>';
							cartCalculateRow(quantity,price,ingredientAmount,product_id,minimum_order_quantity,availableQty);
							cartCalculateGrandTotal();
						});
						
						function cartCalculateRow(quantity,price,ingredientAmount,product_id,minimum_order_quantity,availableQty) 
						{	
							var totalAmount = parseFloat(price) + parseFloat(ingredientAmount);
							
							var itemTotal = (totalAmount * quantity).toFixed(2);
							$('.itemTotal'+product_id).text(itemTotal);
							$('#linetotal'+product_id).val(itemTotal);
							cartCalculateGrandTotal();
							
							if(product_id != "" && quantity != "")
							{
								$.ajax({
									url: '<?php echo base_url(); ?>web_items/AjaxItemSession/'+product_id+'/'+quantity+'/'+price,
									type: "POST",
									data:{
										'<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
									},
									datatype: JSON,
									success: function(result)
									{
										
										/* if(quantity == 0)
										{
											location.reload();
										} */
										data = JSON.parse(result);
										$(".ajaxOrderSummary").html(data['itemList']);
										$(".cartCount").html(data['cartCount']);
										$(".headerCartItem").html(data['headerCartItem']);
									}
								});
							}
							else
							{
								
							}
						}
						
						function cartCalculateGrandTotal() 
						{
							var subTotal = 0;
							$("div.product_table").find('input[name^="linetotal"]').each(function () 
							{
								subTotal += +$(this).val();
							});
							
							var totalPayableAmount = parseFloat(subTotal);
							var minimumOrderValue = '<?php echo isset($minimumOrderValue) ? $minimumOrderValue : 0; ?>';
							
							if(totalPayableAmount < minimumOrderValue)
							{
								$(".minimumOrderValue").show();
								$(".DefaultminimumOrderValue").hide();
								
								$(".place_order_submit").hide();
								$(".place_order_submit_1").hide();
								$(".place_order_disabled_class").hide();
								$(".place_order_disabled_class_1").show();
							}
							else
							{
								$(".minimumOrderValue").hide();
								$(".DefaultminimumOrderValue").hide();
								
								$(".place_order_submit").hide();
								$(".place_order_disabled_class").hide();
								$(".place_order_submit_1").show();
								$(".place_order_disabled_class_1").hide();
							}
							
							/* $('.subTotal').text(subTotal.toFixed(2));
							$('.totalAmount').text(subTotal.toFixed(2));
							
							$('#sub_total').val(subTotal.toFixed(2));
							$('#grand_total').val(subTotal.toFixed(2)); */
							
							$('.subTotal').text(subTotal.toFixed(2));
							$('#sub_total').val(subTotal.toFixed(2));
							
							$('.discount_percentage').html('-' + (subTotal/100*discount_percentage).toFixed(2));
							subTotal  = subTotal - (subTotal/100)*discount_percentage;

							$('.totalAmount').text(subTotal.toFixed(2));
							$('#grand_total').val(subTotal.toFixed(2));
						}	
					</script>
					<?php 
					$subTotal += $totalAmount;														
				} 
			?>
		</ul>

		<ul class="clearfix summary-total-subtotal">
			<li>
				<b>Sub Total</b>
				<div class="seprate-cost">
					<?php echo CURRENCY_CODE;?>
					<span class="subTotal">
						<?php echo number_format($subTotal,DECIMAL_VALUE,'.','');?>
					<span>
				</div>
			</li>
			<?php 
				// Offer calculation;
				$date = date('Y/m/d');
				$today = strtotime($date);
				$totalAmt = $subTotal;
				$dicount_precentage = 0;
				
				$offer_query = "select 
						discount_percentage,
						discount_description,
						upload_banner_mobile
						from vb_discounts 
				where 
					branch_id = ".$_SESSION['BRANCH_ID']." and 
						vb_discounts.from_date <= $today and 
							vb_discounts.to_date >= $today and 
								discount_status = 1";
				$offers = $this->db->query($offer_query)->result_array();	
				
				if (count($offers) > 0) 
				{
					$dicount_precentage = $subTotal / 100 * $offers[0]['discount_percentage']; 
					$totalAmt -= $dicount_precentage;
					?>
					<li class="pt-2">
						<b>Todays Offer %</b>
						<div class="seprate-cost">
							<input type="hidden" name="offer_percentage" id="offer_percentage" value="<?php echo $offers[0]['discount_percentage']; ?>">
							<span class="discount_percentage">
								- <?php echo number_format(($subTotal/100)*$offers[0]['discount_percentage'],DECIMAL_VALUE,'.','');?> 
							<span>
						</div>
					</li>
					<script >
						var discount_percentage = <?php echo $offers[0]['discount_percentage']; ?>;
					</script>
					<?php
				}
			?>
			<!--<li>Delivery fee<span>AED 10</span></li>-->
			<li>
				<b>Total</b>
				<div class="seprate-cost">
					<?php echo CURRENCY_CODE;?>
					<span class="totalAmount"> 
						<?php echo number_format($subTotal - $dicount_precentage,DECIMAL_VALUE,'.','');?>
					</span>
				</div>
			</li>
			<input type="hidden" name="sub_total" id="sub_total" value="<?php echo number_format($subTotal - $dicount_precentage,DECIMAL_VALUE,'.','');?>">
			<input type="hidden" name="grand_total" id="grand_total" value="<?php echo number_format($subTotal - $dicount_precentage,DECIMAL_VALUE,'.','');?>">
		</ul>
		
		<?php
			if($openingTime <= $todayInTime && $closingTime >= $todayOutTime)
			{
				if($deliveryAvailable == 1)
				{
					?>
					<div class="btn_1_mobile">
						<div class="text-center minimum-order-value mb-3 minimumOrderValue" style="display:none;">
							<small>
								Minimum Order Value is :  
								<?php echo CURRENCY_CODE; ?> 
								<?php echo number_format($minimumOrderValue,DECIMAL_VALUE,'.','');?>
							</small>
						</div>
						
						<a href="javascript:void(0);" disabled="disabled" class="btn_1 gradient full-width mb_5 order-disabled-class place_order_disabled_class_1" style="display:none;">
							Place Order
						</a>
						
						<a href="<?php echo base_url();?>checkout.html" class="place_order_submit_1 btn_1 gradient full-width mb_5" style="display:none">
							Place Order
						</a>
						
						<?php 
							if($subTotal < $minimumOrderValue)
							{
								?>
								<div class="text-center minimum-order-value mb-3 DefaultminimumOrderValue">
									<small>
										Minimum Order Value is :  
										<?php echo CURRENCY_CODE; ?> 
										<?php echo number_format($minimumOrderValue,DECIMAL_VALUE,'.','');?>
									</small>
								</div>
								<a href="javascript:void(0);" disabled="disabled" class="btn_1 gradient full-width mb_5 order-disabled-class place_order_disabled_class">
									Place Order
								</a>
								<?php
							}
							else
							{
								?>
								<a href="<?php echo base_url();?>checkout.html" class="place_order_submit btn_1 gradient full-width mb_5">
									Place Order
								</a>
								<?php 
							} 
						?>
						<div class="text-center"><small>No money charged on this steps</small></div>
					</div>
					<?php 
				}
				else
				{
					?>
					<div class="btn_1_mobile">
						<div class="text-center">
							<small style="color: #e54750;">
								Sorry! We could not deliver to  this location.
							</small>
						</div>
					</div>
					<?php
				}
			}
			else
			{ 
				?>
				<div class="btn_1_mobile">
					<div class="text-center">
						<small>Sorry! Your delivery time closed.</small>
					</div>
				</div>
				<?php 
			} 
		?>
		<?php 
	}
	else
	{
		?>
		<div class="noo-food">
			<div class="text-center">
				<img src="<?php echo base_url();?>uploads/no_food.png">
				<p>Good food is always cooking! Go ahead, select some yummy menu.</p>
			</div>
		</div>
		<?php
	} 
?>