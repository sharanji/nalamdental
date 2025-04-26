<?php 
	if(isset($_SESSION["cart_item"]) && $_SESSION["cart_item"])
	{
		$todayInTime = date("H:i");
		$todayOutTime = date("H:i");
		$branch_id = isset($_SESSION["branch_id"]) ? $_SESSION["branch_id"] : 0;
		
		$BranchQuery = "select opening_time,closing_time,minimum_order_value from branch where branch_id='".$branch_id."' ";
		$getBranchDetails = $this->db->query($BranchQuery)->result_array();
		
		$openingTime = isset($getBranchDetails[0]['opening_time']) ? $getBranchDetails[0]['opening_time'] : 0;
		$closingTime = isset($getBranchDetails[0]['closing_time']) ? $getBranchDetails[0]['closing_time'] : 0;
		$minimumOrderValue = isset($getBranchDetails[0]['minimum_order_value']) ? $getBranchDetails[0]['minimum_order_value'] : 0;
		?>
		<ul class="cart-top-list">
			<?php
				$subTotal = 0;
				foreach ($_SESSION["cart_item"] as $code => $item)
				{
					$product_id = $item["product_id"];
					$productDetails = $this->db->query("SELECT product_image,product_id FROM products WHERE product_id='" . $product_id . "'")->result_array();
					$product_image = $productDetails[0]['product_image'];
					?>
					<li id="cart_item<?php echo $product_id?>">
						<figure>
							<?php
								$imgURL ="uploads/products/".$product_image; 
								if(!empty($product_image) && file_exists($imgURL) )
								{
									?>
									<img src="<?php echo base_url().$imgURL;?>" alt="<?php echo $item["product_name"]; ?>" class="lazy">
									<?php 
								}
								else
								{
									?>
									<img src="<?php echo base_url();?>uploads/no-image.png" alt="<?php echo $item["product_name"]; ?>" class="lazy">
									<?php 
								} 
							?>
						</figure>
						<strong>
							<span><?php echo $item["product_name"]; ?> x <?php echo $item["quantity"]; ?></span>
							<?php 
								echo CURRENCY_CODE;
								$price = isset($item["price"]) ? $item["price"] : 0;
							?> 
							<?php echo number_format($price * $item["quantity"],DECIMAL_VALUE,'.','');?>
						</strong>
						<a class="action" onclick="removeorders(<?php echo $product_id ?>,'<?php echo $code;?>')" href="javascript:void(0);" class="action">
							<!--<a --onclick="removeaddress('<?php echo $item['product_code'];?>','<?php echo $item["product_id"]; ?>')" href="<?php echo base_url();?>remove-cart-item/<?php echo $item["product_code"]; ?>/<?php echo $item["product_id"]; ?>.html" class="action">
							--><i class="icon_trash_alt"></i>
						</a>
					</li>
					<?php 
					$subTotal += $item["price"] * $item["quantity"];
				} 
			?>
		</ul>
		<div class="total_drop">
			<div class="clearfix add_bottom_15">
				<strong>Sub Total</strong>
				<span>
					<?php echo CURRENCY_CODE;?>  
					<?php echo number_format($subTotal,DECIMAL_VALUE,'.','');?>
				</span>
			</div>
			<?php /* <a href="order.html" class="btn outline">View Cart</a> */ ?>
			<?php
				if($openingTime <= $todayInTime && $closingTime >= $todayOutTime)
				{
					?>
					<div class="text-center minimum-order-value mb-3" style="display:none;">
						<small>
							Minimum Order Value is :  
							<?php echo CURRENCY_CODE; ?> 
							<?php echo number_format($minimumOrderValue,DECIMAL_VALUE,'.','');?>
						</small>
					</div>
					<?php 
						if($subTotal < $minimumOrderValue)
						{
							?>
							<a href="javascript:void(0);" disabled="disabled" class="btn_1 gradient full-width mb_5 order-disabled-class">
								CHECKOUT
							</a>
							<?php
						}
						else
						{
							?>
							<a href="<?php echo base_url();?>checkout.html" class="btn_2">
								CHECKOUT
							</a>
							<?php 
						} 
					?>
					<?php 
				} 
			?>
		</div>
		<?php 
	}
	else
	{
		?>
		<h3>Cart Empty</h3>
		<p style="color:#93959f;">
			Good food is always cooking! Go ahead, order some yummy items from the menu.
		</p>
		<?php
	} 
?>

<script>
	function removeorders(product_id,product_code)
	{
		$.ajax({
			url: '<?php echo base_url();?>web_items/ajaxRemoveCartItem',
			type: 'POST',
			data: {
				'product_id'   : product_id,
				'product_code' : product_code
			},
			success: function(result)
			{   
				data = JSON.parse(result);
				$(".ajaxOrderSummary").html(data['itemList']);
				$(".cartCount").html(data['cartCount']);
				$(".headerCartItem").html(data['headerCartItem']);
				toastr.success("Cart Item Removed successfully!");
			}
		});
	}
</script>