<body class="grocino-home home2 grocino-cart">
    
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
                                <div class="table-responsive">
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
                                                                        <img src="<?php echo base_url().$url;?>" alt="<?php echo ucfirst($item['product_description']);?>" data-src="<?php echo base_url().$url;?>" alt="<?php echo ucfirst($item['product_description']);?>" class="lazy img-fluid" >
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
                                                                <div class="media-body" >
                                                                    <h2 style="font-weight:400;" ><?php echo ucfirst($item["product_description"]); ?></h2>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td style="color:black;font-size:16px;font-weight:500;text-align:center;"><?php echo CURRENCY_SYMBOL;?><?php echo number_format($item["price"],DECIMAL_VALUE,'.',''); ?>
                                                        <input type="hidden" name="price" id="price<?php echo $item['product_id'];?>" value="<?php echo number_format($item["price"],DECIMAL_VALUE,'.','');?>"></td>
       
                                                        <td style="padding:0px 5px;">
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
                                                            <div class="tprice-del cart-page-price" id="res7">
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
                                        <p class="read"><span style="font-weight:500;">Note:<span style="font-weight:400;"><span> The Listed Prices Are Tax-Inclusive For Your Convenience.<span></p>
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
                                        <p style="font-weight:500;">Sub Total <span class="test_totalOrderAmount"><?php echo CURRENCY_SYMBOL;?> <?php echo number_format($totorderAmount,DECIMAL_VALUE,'.','');?></span></p>
                                        <p style="font-weight:500;">Delivery Charge <span class="test_totalOrderAmount"><?php echo CURRENCY_SYMBOL;?> <?php echo number_format($totorderAmount,DECIMAL_VALUE,'.','');?></span></p>
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
                                        <div class="searchbar input-container" style="background-color: #fbf9f9;border: 1px solid #ada5a5;">
                                            <input type="search" name="pincode" style="text-align:center;width:100%;" id="pincode_new" minlength="6" maxlength="6" class="search_input form-control-underlined" placeholder="Enter Your Pincode"  value="">
                                            <input type="hidden" name="del_location_id" id="del_location_id">
                                            <input type="hidden" name="area_name" id="area_name">
                                            <!-- <button class="search_icon" type="submit">
                                                <i class="fa fa-search"></i>
                                            </button> -->
                                            <div id="pincodeList_new" class="text-right"></div>
                                            <!-- <a href="#" class="search_icon"><i class="fa fa-search"></i></a> -->
                                        </div><br>
                                        <p class="read"><span style="font-weight:500;">Note:<span style="font-weight:400;"><span> The Listed Prices Are Tax-Inclusive For Your Convenience.<span></p>
                                        <p class="">Please enter your pincode to proceed to pay</p>
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

<script>  
    $(document).ready(function()
    {  
        $('#pincode_new').keyup(function()
        {  
            var query = $(this).val();  

            if(query != '')  
            {  
                $.ajax({  
                    url:"<?php echo base_url();?>welcome/ajaxPincodeSearch",  
                    method:"POST",  
                    data:{query:query},  
                    success:function(data)  
                    {  
                        $('#pincodeList_new').fadeIn();  
                        $('#pincodeList_new').html(data);  
                    }  
                });  
            }  
        });
        
        $(document).on('click', 'ul.list-unstyled-locations li', function()
        {  
            var value = $(this).text();
            
            if(value === "Sorry! Location Not Found.")
            {
                $('#pincodeList_new').fadeOut();
            }
            else
            {
                $('#pincodeList_new').fadeOut();  
            }
        });
    });

    function getLocationID(del_location_id,area_name,pincode)
    {
        $('.clear-icon').show();
        if(del_location_id == 0)
        {
            $('#pincode').val(pincode);
            $('#area_name').val(area_name);
            $('#del_location_id').val('0');
        }
        else
        {
            selectLocation(del_location_id);
            
            $('#del_location_id').val(del_location_id);
            $('#pincode').val(pincode);
            $('#area_name').val(area_name);
    
            //getAjaxSuppliers(supplier_id);
            //getAjaxSupplierGst(supplier_id);
        }
        $(".list-unstyled-location").hide();
    }

    function clearLocationSearchKeyword()
    {
        $(".clear-icon").hide();
        $("#pincode").val("");
        $("#del_location_id").val("");
        $(".list-unstyled-customer-name").hide();
    }


    function selectLocation(del_location_id)
    {
            
        if(del_location_id)  
        {  
            $.ajax({  
                url:"<?php echo base_url();?>welcome/locationSearchNew",  
                method:"POST",  
                data:{id:del_location_id},  
                success:function(data)  
                {  
                    var myArray = data.split("@");
                    var pinCode = myArray[0];
                    var areaName = myArray[1];
                        
                    $('.chk_btn').show();  
                    $('.content_btn').hide();  
                    $('#pincode').val(areaName);  
                }  
            });  
        } 
        else
        {
            alert("No Location Found");
        } 
    
    }
</script>
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