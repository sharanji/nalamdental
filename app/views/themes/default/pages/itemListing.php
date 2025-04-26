<?php 
	if(count($menuData) > 0)
	{
		foreach($menuData as $products)
		{
			?>
			<!-- <div class="product-layout product-grid col-12 col-xl-3 col-lg-6 col-md-6 col-sm-6 search-pro-name"> -->
			<div class="product-layout product-grid col-4 col-xl-2 col-lg-3 col-md-3 col-sm-6 search-pro-name">
				<div class="product-holder product-thumb list-of-food">
					<div class="image">
						<a href="javascript:void(0);" title="<?php echo $products['product_description'];?>"> 
							<div class="img-product">
								<?php 
									$url = "uploads/products/".$products['product_id'].'.png';
									//if(file_exists($url) && !empty($products['product_image']))
									if(file_exists($url))
									{
										?>
										<img src="<?php echo base_url().$url;?>" alt="<?php echo ucfirst($products['product_description']);?>" data-src="<?php echo base_url().$url;?>" alt="<?php echo ucfirst($products['product_description']);?>" class="lazy img-fluid">
										<?php 
										$products['product_id'];
									}
									else
									{ 
										?>
										<img src="<?php echo base_url();?>uploads/no-image.png" alt="No Image" class="lazy img-fluid" >	
										<?php  
									}
								?>
							</div>
						</a>
					</div>
					<?php
						if( isset($_SESSION["cart_item"]) && !empty($_SESSION["cart_item"]) ) 
						{
							$SeQty = 0;
							foreach($_SESSION["cart_item"] as $k => $v)
							{
								if($products['product_code'] == $v['product_code'])
								{
									$SeQty += $v['quantity'];
								}
							}
						}
						else
						{
							$SeQty = 0;
						}
						
						if($SeQty == 0){
							$CartQty = 0;
						}else{
							$CartQty = $SeQty;
						}
					?>
						
					<input type="hidden" name="price" id="price<?php echo $products['product_id'];?>" value="<?php echo $products['selling_price'];?>">
					<div class="p-content caption">
						<div class="p-cate d-none"> <?php echo $products['product_description'];?></div>
						<div class="p-title p-title-new"> <a href="javascript:void(0);" title="<?php echo preg_replace('/\s+/', ' ',$products['product_description']);?>"> <?php echo $products['product_description'];?> </a></div>
						<div class="p-price">
							
							<?php 
								if($products['offertype_id'] == 117) #Amount
								{
                                	$offerAmount = !empty($products['offer_amount']) ? $products['offer_amount'] : 0;
									$sellingPrice =  $products['selling_price'] - $offerAmount;
								}
								else if($products['offertype_id'] == 118) #Percentage
								{
                                	$offerPercentage = !empty($products['offer_percentage']) ? $products['offer_percentage'] : 0;
									$sellingPrice1 =  $offerPercentage / 100 * $products['selling_price'];
                                	
                                	$sellingPrice =  $products['selling_price'] - $sellingPrice1;
                                
								}
								else 
								{
									$sellingPrice =  $products['selling_price'];
								}
							?>
							
							<span class="original_price">
								<?php echo CURRENCY_SYMBOL;?> <?php echo $products['original_price'];?>
							</span>
							<span class="selling_price">
								<?php echo CURRENCY_SYMBOL;?> <?php echo $sellingPrice;?>
							</span>
						</div>
						
						<div class="input-group qty new-input">
							<span class="input-group-btn">
								<button class="btn btn-default btn-number bg-green" onclick="cartMinus('<?php echo $products['product_id'];?>','list');" --data-type="minus" --data-field="quant[1]">
									<i class="uil uil-minus"></i>
								</button>
							</span>
							
							<input type="number" readonly name="cart_qty" value="<?php echo $CartQty;?>" id="<?php echo $products['product_id']."@".$products['selling_price'];?>"  class="text-center cart_qty<?php echo $products['product_id'];?>" --value="1" --min="1" --max="10" style="width:40px;border: 1px solid #bbb7b7;">
							
							<span class="input-group-btn">
								<button class="btn btn-default btn-number bg-green" onclick="cartPlus('<?php echo $products['product_id'];?>','list');" --data-type="plus" --data-field="quant[1]">
								<i class="uil uil-plus"></i>
								</button>
							</span>
						</div>
					</div>
				</div>
			</div>
			<?php 
		}
	}
	else
	{
		?>
			<img src="<?php echo base_url();?>uploads/noproducts.png" alt="No Products Found" class="norprod">
		<?php
	}
	
?>