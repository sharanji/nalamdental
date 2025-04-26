<meta name="viewport" content="width=device-width, initial-scale=1.0">

<body class="grocino-home home2 grocino-cart">
<style>
                                                            /* Mobile View Styles */
                                                            @media (max-width: 767px) {
                                                                .mview {
                                                                    padding: 5px 15px;
                                                                    width: 100%;
                                                                    float: none;
                                                                }
                                                            }

                                                            /* Web View Styles */
                                                            @media (min-width: 768px) {
                                                                .mview{
                                                                    padding: 5px 15px;
                                                                    width: 70%;
                                                                    float: right;
                                                                }
                                                            }
                                                            /* Add this CSS to your existing stylesheet */
@media (max-width: 767px) {
    .table-responsive {
        max-height: none; /* Remove max-height to allow content to expand */
        overflow: hidden; /* Hide overflow content */
    }

    .pro1{position:relative;margin-left:-26px;}
    
    .product_table tbody {
        display: block; /* Enable vertical scrolling for the tbody */
        max-height: 300px; /* Set a fixed height for the table body */
        overflow-y: auto; /* Enable vertical scrolling for the tbody */
    }
    
    .product_table tr {
        display: flex; /* Use flexbox to handle table row layout */
        flex-wrap: wrap;
        justify-content: space-between;
        margin-bottom: 10px;
    }
    
    .product_table th, .product_table td {
        flex-basis: calc(50% - 10px);
    }
    
    /* Other styles for mobile view */
    .chk_btn, .content_btn {
        width: 100%;
        float: none;
        margin-top: 15px;
    }
    .product_table thead {
        display: flex;
        flex-wrap: wrap;
        justify-content: space-between;
        padding-bottom: 10px;
        border-bottom: 1px solid #ddd;
    }

    .product_table th {
        flex-basis: calc(33.33% - 10px);
        text-align: center;
    }
    /* Center-align header and data cells */
    .product_table th,
    .product_table td {
        text-align: center;
    }
    
    /* Reduce font size for mobile */
    .product_table th,
    .product_table td {
        font-size: 14px; /* Adjust the font size as needed */
    }

    /* Adjust column widths for proper alignment */
    .col-sm-3 {
        width: 25%; /* Each column takes 25% of the row's width */
    }

    

    
    /* Proper table cell layout for mobile */
    .product_table tr {
        display: block;
        margin-bottom: 20px;
        border-bottom: 1px solid #ddd;
        padding-bottom: 10px;
        
    }
    
    .product_table td {
        display: block;
        margin-bottom: 5px;
    }
    .cart_qty_new {
        width: 40px; /* Adjust the width as needed */
        text-align: center;
        padding: 2px 5px;
        font-size: 12px; /* Adjust the font size as needed */
    }
    .img-product {
        max-width: 20px; /* Adjust the max-width as needed */
        height: auto;
        margin-right: 0px;
    }
    
    /* Adjust product description font size */
    .media-body h2 {
        font-size: 10px; /* Adjust the font size as needed */
        position:relative;
        margin-left:-22px;
    }
 
    
    .btn-number {
        padding: 2px 5px; /* Adjust padding as needed */
    }


    /* You can adjust other styles as needed for the mobile view */
}
 /* Display table headers on the same line */


                                                        </style>
    
 <!-- cart section start here -->
<div class="cart-section" style="wipx;">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="grocino-heading pt-5">
                    <h1 class="heading_text"> Your Cart </h1>
                    <div class="graph graph-sm">
                        <img src="<?php echo base_url();?>assets/frontend/img/about/graphic.png" alt="Graph" title="Graph" class="img-fluid" />
                    </div>
                </div>
                <?php
                    if(isset($_SESSION["cart_item"]) && count($_SESSION["cart_item"]) > 0)
                    {
                        ?>
                        <div class="row">
                            <div class="col-xl-9 col-lg-8 col-md-8 col-sm-12 ms-aut new-left-cart">
                                <div class="">
                                    <table class="table table-borderless product_table">
                                        <thead>
                                            <tr>
                                                <th style="text-align:center;">Product</th>
                                                <th style="text-align:center;">Cost</th>
                                                
                                                <th  style="text-align:center;">Quantity</th>

                                               
                                                <th style="text-align:right">Price</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                                $subTotal = 0;
                                                $cartQty = 0;
                                                $orderAmount = $totorderAmount = 0;
                                                foreach($_SESSION["cart_item"] as $code => $item)
                                                {
                                                    $cartQty = $item["quantity"];
                                                    $orderAmount = $item["quantity"] * $item["price"];

                                                    $totorderAmount += $orderAmount;
                                                    
                                                    $product_id = $item["product_id"];
                                                    $productDetails = $this->db->query("SELECT product_image,product_description,product_id FROM products WHERE product_id='" . $product_id . "'")->result_array();
                                                    $product_image = $productDetails[0]['product_image'];
                                                   
                                                    
                                                    ?>
                                                    <tr class="item-list-cartpage item_list<?php echo $item['product_id'];?>">
                                                        <td>
                                                            <div class="media">
                                                                <div class="img-product img-product-carpage" style="position:relative;margin-right:-2px;">
                                                                <?php 
                                                                   // $url = "uploads/products/".$product_image;
                                                                    $url = "uploads/products/".$item['product_id'].'.png';
                                                                    //if(file_exists($url) && !empty($product_image))
                                                                    if(file_exists($url))
                                                                    {
                                                                        ?>
                                                                        <img class="pro1"  src="<?php echo base_url().$url;?>" alt="<?php echo ucfirst($item['product_description']);?>" data-src="<?php echo base_url().$url;?>" alt="<?php echo ucfirst($item['product_description']);?>" class="lazy img-fluid" >
                                                                        <?php 
                                                                            $item['product_id'];
                                                                    }
                                                                    else
                                                                    { 
                                                                        ?>
                                                                        <img src="<?php echo base_url();?>uploads/no-image.png" alt="No Image" class="img-fluid">	
                                                                        <?php  
                                                                    }
                                                                ?>
                                                                </div>
                                                                <div class="media-body">
                                                                    <h2 style="font-weight:400;"><?php echo ucfirst($item["product_description"]); ?></h2>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td style="color:black;font-size:16px;font-weight:500;text-align:center;"><?php echo CURRENCY_SYMBOL;?><?php echo number_format($item["price"],DECIMAL_VALUE,'.',''); ?>
                                                        <input type="hidden" name="price" id="price<?php echo $item['product_id'];?>" value="<?php echo number_format($item["price"],DECIMAL_VALUE,'.','');?>"></td>
             
                                                        <td >
                                                            <div class="input-group qty mview" >

                                                                <span class="input-group-btn">
                                                                    <button type="button" class="btn btn-default btn-number bg-green" onclick="cartMinusNew('<?php echo $item['product_id'];?>');">
                                                                        <i class="uil uil-minus"></i>
                                                                    </button>
                                                                </span>

                                                                <input type="number" readonly name="cart_qty_new" class="input-number cart_qty_new<?php echo $item['product_id'];?>" id="<?php echo $item['product_id']."@".$item['price'];?>" value="<?php echo $cartQty;?>">
                                                                
                                                                <span class="input-group-btn">
                                                                    <button type="button" class="btn btn-default btn-number bg-green" onclick="cartPlusNew('<?php echo $item['product_id'];?>');">
                                                                        <i class="uil uil-plus"></i>
                                                                    </button>
                                                                </span>
                                                            </div>
                                                        </td>
                                                        <td style="color:black">
                                                            <div class="tprice-del cart-page-price">
                                                                <span class="total-p" ><?php echo CURRENCY_SYMBOL;?> <p style="color:black;font-size:16px;font-weight:500;" class="cart-prices-amount cart-prices<?php echo $item['product_id'];?>"><?php echo number_format($orderAmount,DECIMAL_VALUE,'.','');?></p></span>
                                                                <input type="hidden" name="linetotal_new" id="linetotal_new<?php echo $item['product_id'];?>" value="<?php echo $orderAmount;?>">
									                        </div>
                                                        </td>
                                                        <td>
                                                            <a href="#" onclick="removeordersNew(<?php echo $product_id ?>,'<?php echo $code;?>')"> <i class="uil uil-trash" aria-hidden="true"></i></a>
                                                        </td>
                                                    </tr>
                                                    <?php
                                                }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <?php 
                                if(isset($_SESSION['locationAreaNameNew']) && !empty($_SESSION['locationAreaNameNew']))
                                {
                                    $checkoutBtn = "display:block;";
                                    $content ="";

                                    $contentBtn = "display:none;";
                                }
                                else {
                                    $content = "<span style='font-weight:bold;'>* Please select your delivery Pincode to Place Order</span>";
                                    $checkoutBtn = "display:none;";
                                    $contentBtn = "display:block;";
                                }
                                
                                ?>
                                
                                <div class="col-xl-3 col-lg-4 col-md-4 col-sm-12 ms-auto chk_btn" style="<?php echo $checkoutBtn;?>">
                                    <div class="cart-detail pt-0">
                                        <h2 class="order-summary-heading" style="margin-bottom:22px;">Order Summary</h2>
                                        <p style="font-weight:550;color:black;">Total <span class="test_totalOrderAmount"><?php echo CURRENCY_SYMBOL;?> <?php echo number_format($totorderAmount,DECIMAL_VALUE,'.','');?></span></p>
                                        <hr>
                                        <p class="read"><span style="font-weight:500;">Note:<span style="font-weight:400;"><span> The listed prices are tax-inclusive for your convenience.<span></p>
                                        <!-- <p class="font-medium">Total Price <span>$175.00</span></p> -->
                                        <div class="buttons">
                                            <?php 
                                                if(isset($this->UserID) && !empty($this->UserID))
                                                {
                                                    ?>
                                                        <a class="btn w-100 btn-orange" href="<?php echo base_url();?>checkout.html">Proceed to Pay</a>
                                                    <?php
                                                }
                                                else
                                                {
                                                    ?>
                                                        <a class="btn w-100 btn-orange" href="<?php echo base_url();?>sign-in.html/2">Proceed to Pay</a>
                                                    <?php
                                                }
                                            ?>
                                        </div>
                                        <p class="read" style="font-weight:bold;">Check your products carefully before proceeding to the checkout</p>
                                    </div>
                                </div>
                                   
                               
                                <div class="col-xl-3 col-lg-4 col-md-4 col-sm-12 ms-auto content_btn" style="<?php echo $contentBtn;?>">
                                    <div class="cart-detail pt-0">
                                        <h2 class="order-summary-heading">Order Summary</h2>
                                        <p style="font-weight:500;">Total <span class="test_totalOrderAmount"><?php echo CURRENCY_SYMBOL;?> <?php echo number_format($totorderAmount,DECIMAL_VALUE,'.','');?></span></p>
                                        <hr class="divider">
                                        <!-- <p class="font-medium">Total Price <span>$175.00</span></p> -->
                                        <div class="buttons">
                                            <?php 
                                                if(isset($this->UserID) && !empty($this->UserID))
                                                {
                                                    ?>
                                                        
                                                    <?php
                                                }
                                                else
                                                {
                                                    ?>
                                                        
                                                    <?php
                                                }
                                            ?>
                                        </div>
                                        <p class="read"><span style="font-weight:500;">Note:<span style="font-weight:400;"><span> The listed prices are tax-inclusive for your convenience.<span></p>
                                        <p class="">Please select your delivery Pincode to Place Order</p>
                                        
                                    </div>
                                </div>
                                   
                                
                            
                        </div>
                        <?php
                    }
                ?>
            </div>
        </div>
    </div>
</div>
<!-- cart section end here -->
</body>


<!-- Cart Page Script Starts -->
<script>
	//Cart Plus
	function cartPlusNew(product_id,type)
	{
		var oldVal = $('.cart_qty_new'+product_id).val();
		var newVal = (parseInt(oldVal,10) + 1);
		
        $('.cart_qty_new'+product_id).val(newVal);
		var price = $('#price'+product_id).val();
      
		var quantity = newVal;
        
		var totalAmount = parseFloat(quantity * price).toFixed(2);
      
		$(".cart-prices"+product_id).text(totalAmount);
		$("#linetotal_new"+product_id).val(totalAmount);
       
		itemCalculateRowNew(product_id,quantity,price,type);
		itemCalculateGrandTotalNew();	
	}
	
	//Cart Minus
	function cartMinusNew(product_id,type)
	{
		var oldVal = $('.cart_qty_new'+product_id).val();
		
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
		$('.cart_qty_new'+product_id).val(newVal);
		
		var price = $('#price'+product_id).val();
		var quantity = newVal;
        
		var totalAmount = parseFloat(quantity * price).toFixed(2);

		$(".cart-prices"+product_id).text(totalAmount);
		$("#linetotal_new"+product_id).val(totalAmount);
	
		itemCalculateRowNew(product_id,quantity,price,type);
		itemCalculateGrandTotalNew();

        if(quantity == 0)
		{
			removeCartItemNew(product_id,quantity) //if zero
		}
	}
	
	//Cart enter Qty 
	$(".product_table").on("input keyup change", 'input[name^="cart_qty_new"]', function (event) 
	{
		
		var attribute = $(this).attr("id");
		var attr_arr = attribute.split('@');
		
		var product_id = attr_arr[0]; 
		var quantity = $(this).val();
		var price = attr_arr[1];

		itemCalculateRowNew(product_id,quantity,price);
		itemCalculateGrandTotalNew();
	});
	
	function itemCalculateRowNew(product_id,quantity,price,type) 
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

                    $(".ajaxOrderSummary").html(data['itemList']);
					$(".cartCount").html(data['cartCount']); //Cart Count
				}
			});
		}
		else
		{
			
		}
	}

	function removeordersNew(product_id,product_code)
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
				$(".cart_qty_new"+product_id).val("0");
				$(".cartCount").html(data['cartCount']);


				itemCalculateGrandTotalNew();
				toastr.success("Cart Item Removed successfully!");
				//location.reload();
			}
		});
	}

    function removeCartItemNew(product_id,quantity)
	{
		if(quantity == 0)
		{
			$.ajax({
				url: '<?php echo base_url();?>web_items/removeCartItemZeroNew',
				type: 'POST',
				data: {
					'product_id'   : product_id
				},
				success: function(result)
				{   
                    $(".item_list"+product_id).remove();
                    $(".cart_qty"+product_id).val("0");
					data = JSON.parse(result);
					//$(".ajaxOrderSummary").html(data['itemList']);
					$(".cartCount").html(data['cartCount']);
					itemCalculateGrandTotal();
					toastr.success("Cart Item Removed successfully!");
					//location.reload();
				}
			});
		}
	}

	function itemCalculateGrandTotalNew()
	{
		var grandTotal = 0;

		$(".product_table").find('input[name^="linetotal_new"]').each(function () 
		{
			grandTotal += +$(this).val();
		});

		var totalValue = parseFloat(grandTotal).toFixed(2);

        $(".test_totalOrderAmount").text(totalValue);
	}
	
</script>
<!-- Cart Page Script Ends -->