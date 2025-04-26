		

	<?php 
	
	/* if(isset($_SESSION["cart_item"]) && $_SESSION["cart_item"])
	{
		foreach ($_SESSION["cart_item"] as $code => $item)
		{
			$cartQty += $item["quantity"];
			
			$orderAmount += $item["quantity"] * $item["price"];
		}
	} */

	/* $subTotal = 0;
	foreach ($_SESSION["cart_item"] as $code => $item)
	{
		$product_id = $item["product_id"];
		$productDetails = $this->db->query("SELECT product_image,product_id FROM products WHERE product_id='" . $product_id . "'")->result_array();
		$product_image = $productDetails[0]['product_image'];
		?>
		<li id="cart_item<?php echo $product_id?>">
			<strong>  
				<span><?php echo $item["product_description"]; ?> x <?php echo $item["quantity"]; ?></span>
				<?php 
					echo CURRENCY_CODE; 
					$price = isset($item["price"]) ? $item["price"] : 0;
				?> 
				<?php echo number_format($price * $item["quantity"],DECIMAL_VALUE,'.','');?>
			</strong>
			<a class="action" onclick="removeorders(<?php echo $product_id ?>,'<?php echo $code;?>')" href="javascript:void(0);" class="action">
				<!--<a --onclick="removeaddress('<?php echo $item['product_code'];?>','<?php echo $item["product_id"]; ?>')" href="<?php echo base_url();?>remove-cart-item/<?php echo $item["product_code"]; ?>/<?php echo $item["product_id"]; ?>.html" class="action">
				-->
				<i class="icon_trash_alt"></i>
			</a>
			
			
		</li>
		<?php 
		$subTotal += $item["price"] * $item["quantity"];
	}  */
?>
<div class="offcanvas offcanvas-end text-bg-dark " id="offcanvasNavbarDark" aria-labelledby="offcanvasNavbarDarkLabel">
	<div class="offcanvas-header">
		<h5 class="offcanvas-title" id="offcanvasNavbarDarkLabel">My Cart  
			<?php /* <span class="cartCount">
				<?php echo isset($_SESSION["cart_item"]) ? count($_SESSION["cart_item"]) :"";?>
			</span> */ ?>
		</h5>
		<button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Close"></button>
	</div>

	<div class="bs-canvas-body">
		<div class="side-cart-items cart-list text-center">
			<?php 
				$subTotal = 0;
				$cartQty = 0;
				$orderAmount = $totorderAmount = 0;

				if(isset($_SESSION["cart_item"]) && count($_SESSION["cart_item"]) > 0)
				{
					foreach ($_SESSION["cart_item"] as $code => $item)
					{
						$cartQty = $item["quantity"];
						$orderAmount = $item["quantity"] * $item["price"];

						$totorderAmount += $orderAmount;
						
						$product_id = $item["product_id"];
						$productDetails = $this->db->query("SELECT product_image,product_id FROM products WHERE product_id='" . $product_id . "'")->result_array();
						$product_image = $productDetails[0]['product_image'];
						?>
						<div class="cart-item product_table item_list<?php echo $item['product_id'];?>">
							<div class="cart-text">
								
								<h4 style="width: 90%;"> <?php echo $item["product_description"]; ?>  </h4>
								
								<div class="qty-group">
									
									<div class="quantity">

										
										
										<div class="input-group qty new-input">
											
											<span class="input-group-btn">
												<button class="btn btn-default btn-number bg-green" onclick="cartMinus('<?php echo $item['product_id'];?>');" --data-type="minus" --data-field="quant[1]">
													<i class="uil uil-minus"></i>
												</button>
											</span>
											
											<input type="number" readonly name="cart_qty" value="<?php echo $cartQty;?>" id="<?php echo $item['product_id']."@".$item['price'];?>"  class="text-center cart_qty<?php echo $item['product_id'];?>" --value="1" --min="1" --max="10" style="width:40px">
											<input type="hidden" name="cart_price" value="<?php echo $item['price'];?>"  class="cart_price<?php echo $item['product_id'];?>">
											
											<span class="input-group-btn">
												<button class="btn btn-default btn-number bg-green" onclick="cartPlus('<?php echo $item['product_id'];?>');" --data-type="plus" --data-field="quant[1]">
													<i class="uil uil-plus"></i>
												</button>
											</span>
											<input type="hidden" name="cart_price" value="<?php echo $item['price'];?>"  class="cart_price<?php echo $item['product_id'];?>">
										</div>
										<div class="item-price1">
											<?php echo CURRENCY_SYMBOL;?>
											<div style="float:left;margin-top:10px;" class="cart-prices-new <?php echo $item['product_id'];?>">
												<?php echo CURRENCY_SYMBOL;?><?php echo $item['price'];?>
											</div>
											<!-- <input type="hidden" name="cart_price" value="<?php echo $item['price'];?>" class="cart_price<?php echo $item['product_id'];?>"> -->
										</div>


										
										<?php /*  <div class="def-number-input number-input safari_only">
											<button onclick="cartMinus('<?php echo $products['product_id'];?>');" class="minus"></button>
											<input class="quantity cart_qty<?php echo $products['product_id'];?>" min="0" name="quantity" value="<?php echo $CartQty;?>" type="number">
											<button onclick="cartPlus('<?php echo $products['product_id'];?>');" class="plus"></button>
										</div> */ ?>
									</div>
									<div class="item-price">
										<?php echo CURRENCY_SYMBOL;?>
										<div class="cart-prices-new cart-prices<?php echo $item['product_id'];?>">
											<?php echo $orderAmount;?>
										</div>
										<input type="hidden" name="linetotal" id="linetotal<?php echo $item['product_id'];?>" value="<?php echo $orderAmount;?>">
									</div>
								
								</div>
								<!-- <input type="text" name="cart_price" value="<?php echo $item['price'];?>"  class="cart_price<?php echo $item['product_id'];?>"> -->
								<button type="button" class="cart-close-btn" onclick="removeorders(<?php echo $product_id ?>,'<?php echo $code;?>')"><i class="uil uil-trash"></i></button>
							</div>
						</div>
						<?php
					}
				}
				else
				{
					?>
					<img src="<?php echo base_url();?>uploads/nocart.png" alt="No Products Found" class="nocart">
					<?php
				}
				
			?>
			<!-- cart loop -->
		</div>
	</div>
	
	<?php
		if(isset($_SESSION["cart_item"]) && count($_SESSION["cart_item"]) > 0)
		{
			?>
				<div class="bs-canvas-footer">
					<div class="main-total-cart">
						<h2 style="font-weight:500;color:black;">Total</h2>
						<span class="totalOrderAmount" style="color:black;font-weight:500;"><?php echo CURRENCY_SYMBOL;?> <?php echo (number_format($totorderAmount,DECIMAL_VALUE,'.',''));?></span>
					</div>
					<div class="checkout-cart">
						<a href="checkout.html" class="promo-code d-none">Redeem Your Coupon?</a>
						<a href="<?php echo base_url();?>cart.html" class="cart-checkout-btn vej-btnhover">Proceed to Checkout</a>
					</div>
				</div>
			<?php
		}
	?>
	
</div>

<script>
	//Cart Plus
	function cartPlus(product_id,type)
	{
		var oldVal = $('.cart_qty'+product_id).val();
		var newVal = (parseInt(oldVal,10) + 1);
		
		$('.cart_qty'+product_id).val(newVal);
		
		var p_price = $('#price'+product_id).val();
		
		if(p_price == undefined)
		{
			var price = $('.cart_price'+product_id).val();
		}
		else
		{
			var price = $('#price'+product_id).val();
		}

		var quantity = newVal;

		/* if(quantity > 0)
		{
			addCartItem(product_id);
		} */
		var totalAmount = parseFloat(quantity * price).toFixed(2);
		
		$(".cart-prices"+product_id).text(totalAmount);
		$("#linetotal"+product_id).val(totalAmount);

		itemCalculateRow(product_id,quantity,price,type);
		itemCalculateGrandTotal();	
	}
	
	//Cart Minus
	function cartMinus(product_id,type)
	{
		var oldVal = $('.cart_qty'+product_id).val();
		
		if (oldVal == 0)
		{
			var newVal = 0;
		}
		else
		{
			var newVal = (parseInt(oldVal,10) -1);
		}

		/* if (oldVal == 1)
		{
			var newVal = 1;
		}
		else
		{
			var newVal = (parseInt(oldVal,10) -1);
		} */
		$('.cart_qty'+product_id).val(newVal);
		
		var p_price = $('#price'+product_id).val();
		
		if(p_price == undefined)
		{
			var price = $('.cart_price'+product_id).val();
		}
		else
		{
			var price = $('#price'+product_id).val();
		}
		var quantity = newVal;
		
		var totalAmount = parseFloat(quantity * price).toFixed(2);

		$(".cart-prices"+product_id).text(totalAmount);
		$("#linetotal"+product_id).val(totalAmount);
		
		itemCalculateRow(product_id,quantity,price,type);
		itemCalculateGrandTotal();

		if(quantity == 0)
		{
			removeCartItem(product_id,quantity) //if zero
		}
	}
	
	//Cart enter Qty 
	$(".product_table").on("input keyup change", 'input[name^="cart_qty"]', function (event) 
	{
		
		var attribute = $(this).attr("id");
		var attr_arr = attribute.split('@');
		
		var product_id = attr_arr[0]; 
		var quantity = $(this).val();
		var price = attr_arr[1];

		itemCalculateRow(product_id,quantity,price);
		itemCalculateGrandTotal();
	});
	
	function itemCalculateRow(product_id,quantity,price,type) 
	{	
		if(product_id != "")
		{
			$.ajax({
				url: '<?php echo base_url(); ?>web_items/AjaxItemListCart/'+product_id+'/'+quantity+'/'+price,
				type: "POST",
				data:{
					'<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
				},
				success: function(result)
				{
					data = JSON.parse(result);
					
					if(data['cartCount'] == 0)
					{
						$(".ajaxOrderSummary").hide();
					}
					else
					{
						$(".ajaxOrderSummary").show();
					}

					if(type == "list") //List Page
					{
						$(".ajaxOrderSummary").html(data['itemList']);
					}

					$(".cartCount").html(data['cartCount']); //Cart Count
				}
			});
		}
		else
		{
			
		}
	}

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
				//$(".ajaxOrderSummary").html(data['itemList']);

				$(".item_list"+product_id).remove();
				$(".cart_qty"+product_id).val("0");
				$(".cartCount").html(data['cartCount']);


				itemCalculateGrandTotal();
				toastr.success("Cart Item Removed successfully!");
				//location.reload();
			}
		});
	}

	// Remove when quantity is 0

	function removeCartItem(product_id,quantity)
	{
		if(quantity == 0)
		{
			$.ajax({
				url: '<?php echo base_url();?>web_items/removeCartItemZero',
				type: 'POST',
				data: {
					'product_id'   : product_id
				},
				success: function(result)
				{   
					data = JSON.parse(result);
					//$(".ajaxOrderSummary").html(data['itemList']);
					
					$(".item_list"+product_id).remove();
					$(".cart_qty"+product_id).val("0");
					$(".cartCount").html(data['cartCount']);


					itemCalculateGrandTotal();
					toastr.success("Cart Item Removed successfully!");
					//location.reload();
				}
			});
		}
		
	}

	function itemCalculateGrandTotal()
	{
		var grandTotal = 0;

		$(".product_table").find('input[name^="linetotal"]').each(function () 
		{
			grandTotal += +$(this).val();
		});

		var totalValue = parseFloat(grandTotal).toFixed(2);
		$(".totalOrderAmount").text(totalValue);
	}

	/* function addCartItem(product_id) // For Interface Table
	{
		if(product_id)  
		{  
			$.ajax({  
				url:"<?php //echo base_url();?>web_items/addCartItem",  
				method:"POST",  
				data:{id:product_id},  
				success:function(data)  
				{  
					var proId = data;
				
				}  
			});  
		} 
		else
		{
			alert("No Location Found");
		}
	} */
	
	
	
</script>