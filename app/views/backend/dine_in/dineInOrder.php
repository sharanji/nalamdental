<div class="content" style="background:#fff!important;overflow: hidden!important">
    <form action="" id="order_items" method="post">
        <div class="container-fluid p-0 m-0">
            <div class="p-0 m-0" style="box-shadow:1px 1px 1px #fff;">
               
                <div class="row">
                    <?php
                        $ChildCategory1Qry = "select 
                        sm_list_type_values.list_code,
                        sm_list_type_values.list_value,
                        sm_list_type_values.list_type_value_id 
                        from sm_list_type_values 
                        left join sm_list_types on sm_list_types.list_type_id = sm_list_type_values.list_type_id
                        where 
                        sm_list_types.active_flag='Y' and 
                        coalesce(sm_list_types.start_date,$this->date) <= ".$this->date." and 
                        coalesce(sm_list_types.end_date,$this->date) >= ".$this->date." and
                        sm_list_types.deleted_flag='N' and


                        sm_list_type_values.active_flag='Y' and 
                        coalesce(sm_list_type_values.start_date,$this->date) <= ".$this->date." and 
                        coalesce(sm_list_type_values.end_date,$this->date) >= ".$this->date." and
                        sm_list_type_values.deleted_flag='N' and 

                        sm_list_types.list_name = '".$this->category_level1_name."'
                        order by order_sequence asc
                        ";

                        $ChildCategory1 = $this->db->query($ChildCategory1Qry)->result_array(); 
                    ?>
                    <div class="col-md-6 p-0">
                        <div class="scrollmenu">
                            <a class="main-categories main-category-0 --main-cat-active" onclick="loadSubCategory('All','0','All');" href="javascript:void(0);" title="All">
                                <img src="<?php echo base_url();?>uploads/no-image-mobile.png" title="All" style="width:90px;height:50px;" alt="..."> 
                                <br>All
                            </a>

                            <?php
                                $i = 1;
                                foreach($ChildCategory1 as $row)
                                {
                                    $categoryId = $row["list_type_value_id"];
                                    $categoryCode = $row["list_code"];
                                    /* if($i == 1)
                                    {
                                    $mainCatActive = "main-cat-active";
                                    $mainCatActiveValue = $i;
                                    ?>
                                    <input type="hidden" name="main_cat_active_counter" id="main_cat_active_counter" value="<?php echo $mainCatActiveValue;?>">
                                    <input type="hidden" name="main_cat_active_categoryCode" id="main_cat_active_categoryCode" value="<?php echo $categoryCode;?>">
                                    <input type="hidden" name="main_cat_active_categoryId" id="main_cat_active_categoryId" value="<?php echo $categoryId;?>">
                                        <?php
                                    }
                                    else
                                    {
                                        $mainCatActive = "";
                                        $mainCatActiveValue = "";
                                    } */
                                    ?>
                                    <a class="main-categories main-category-<?php echo $i; ?> <?php #echo $mainCatActive;?>" onclick="loadSubCategory('<?php echo $categoryCode;?>','<?php echo $i;?>','<?php echo $categoryId;?>');" href="javascript:void(0);" title="<?php echo ucfirst($row["list_value"]); ?>">
                                        <?php 
                                            $url = "uploads/lov_images/".$row['list_type_value_id'].".png";
                                            if(file_exists($url))
                                            {
                                                ?>
                                                <img src="<?php echo base_url().$url;?>" style="width:90px;height:50px;" alt="...">
                                                <?php 
                                            }
                                        ?>
                                        <br><?php echo $row["list_value"]; ?>
                                    </a>
                                    <?php
                                    $i++;
                                }
                            ?>

                            <input type="hidden" name="main_cat_active_counter" id="main_cat_active_counter" value="">
                            <input type="hidden" name="main_cat_active_categoryCode" id="main_cat_active_categoryCode" value="">
                            <input type="hidden" name="main_cat_active_categoryId" id="main_cat_active_categoryId" value="">
                        </div>
                    </div>
                    
                    <div class="col-md-6 float-right text-right">
                        <div class="header-date">
                            <div class="datefield">
                                <label class="col-form-label">
                                    <a href="<?php echo base_url();?>dine_in/dineInTables" title="Select Table">
                                        Table : <?php echo isset($table_data[0]["table_name"]) ? $table_data[0]["table_name"] : NULL;?>
                                    </a>
                                </label>
                                <label class="col-form-label">Date : <?php echo date("d/M/Y");?></label>
                                <input type="hidden" name="bill_date" id="bill_date" readonly value="<?php echo date("d/M/Y");?>" placeholder="">
                            </div>
                        </div>
                    </div>     
                </div> 

                <div class="row">
                    <!-- item list / sub categories start here -->
                    <div class="col-md-8 leftsection p-0">
                        <div class="card1">
                            <div class="card-body1">
                                <input type="hidden" name="sub_cat_active_counter" id="sub_cat_active_counter" value="">
                                <input type="hidden" name="sub_cat_active_categoryCode" id="sub_cat_active_categoryCode" value="">
                                <input type="hidden" name="sub_cat_active_categoryId" id="sub_cat_active_categoryId" value="">
                                <!-- sub categories start here -->
                                <div class="scrollmenu sub_categories" --style="display:none;">
                                
                            
                                </div>
                                <!-- sub categories end here -->

                                <!-- Item list start here -->
                                <div class="pricelist-left">
                                    <div class="row pos_items">
                                        <?php 
                                            $page_data = array();
                                            echo $this->load->view("backend/pos/posItems.php", $page_data, true);
                                        ?>
                                    </div>
                                </div>
                                <!-- Item list end here -->
                            </div>
                        </div>
                    </div>
                    <!-- item list / sub categories start here -->

                    <!-- cart tbl start -->
                    <div class="col-md-4 leftsection">
                        <table class="table">
                            <thead>
                                <tr class="order-tab-header">
                                    <th class="order-header-class tab-md-50"></th>
                                    <th class="order-header-class tab-md-150">Item Name</th>
                                    <th class="order-header-class tab-md-85">Qty</th>
                                    <!-- <th class="order-header-class">UOM</th>
                                    <th class="text-right order-header-class">Rate</th> -->
                                    <th class="text-right tab-md-100 order-header-class">Amount</th>
                                    <!-- <th class="text-right order-header-class">Tax %</th> -->
                                </tr>
                            </thead>                                        
                        </table>

                        <div class="table-overlay">       
                            <table class="table line_items" id="line_items">
                                <tbody>
                                
                                </tbody>                                            
                            </table>
                        </div> 
                    </div>
                    <!-- cart tbl end -->
                </div>
               
            </div>
        </div>
    </form>
</div>

<!-- footer section start -->
<form action="" id="footer_content" method="post">
    <div class="card mt-3 pos-footer">
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <div class="row">   
                        <div class="form-group col-md-6">
                            <input type="text" name="searchItem" id="searchItem" class="search-item form-control" autocomplete="off" value="" placeholder="Search Item...">
                            <div id="posItemList"></div>
                            <input type="hidden" name="search_item_id" id="search_item_id">
                        </div>
                        <div class="form-group col-md-6">
                            <input type="text" name="enter_qty" id="enter_qty" class="form-control mobile_vali" maxlength="4" autocomplete="off" value="" placeholder="Enter Qty">
                        </div> 
                    </div>       
                </div>

                <div class="col-md-1">
                </div>

                <div class="col-md-3">
                    <div class="row">   
                        <div class="form-group col-md-6">
                            <select  name="discount_type" id="discount_type" onchange="selectDiscount(this.value);" class="form-control">
                                <?php 
                                    foreach($this->discountType as $key=>$value)
                                    { 
                                        $selected="";
                                        if( isset($edit_data[0]['gender']) && $edit_data[0]['gender'] == $key)
                                        {
                                            $selected="selected='selected'";
                                        }

                                        ?>
                                        <option value="<?php echo $key;?>" <?php echo $selected; ?>><?php echo $value;?></option>
                                        <?php 
                                    } 
                                ?>
                            </select>
                        </div> 
                        <script>
                            function discountChange(input) {
                                if (input.value < 0) input.value = 0;
                                if (input.value > 100) input.value = 100;
                            }
                        </script>
                        <div class="form-group col-md-6">
                            <input type="text" name="discount" id="discount" class="form-control mobile_vali" max="100" onchange="discountChange(this);" autocomplete="off" value="" placeholder="Enter Percentage">
                        </div>  
                    </div>   
                </div>

                <div class="col-md-4">
                    <div class="text-right total-section">
                        <span class="total_label text-right">Total</span>
                        <input type="hidden" class="total_order_amount" id="total_order_amount">
                        <input type="hidden" class="payable_amount" id="payable_amount">
                        <span class="total_amount text-right" id="total_order_amount_text">0.00</span>
                    </div> 
                    <?php 
                        $taxQry = "select tax_id,tax_value from gen_tax where active_flag='Y' AND default_tax=1";
                        $getTax = $this->db->query($taxQry)->result_array();

                        $tax_value = isset($getTax[0]["tax_value"]) ? $getTax[0]["tax_value"] : NULL;
                    ?>
                    
                    <span class="inclusive-tax">Inclusive Tax @ <?php echo $tax_value;?> %</span>

                    <a href="javascript:void(0)" onclick="savePosOrder('order_interface_tbl');" class="proceedto-pay btn-disabled disabled-btn" title="Proceed to Pay" --data-toggle="modal" --data-target="#print_order">
                        <b>Proceed to Pay <?php echo CURRENCY_SYMBOL;?> <span id="payable_new">0.00</span></b>
                        <?php /* <b>Proceed to Pay <?php echo CURRENCY_SYMBOL;?> <span id="payable_amount_text">0.00</span></b> */ ?>
                    </a>
                </div>
             </div>
        </div>
    </div>
</form>
<!-- footer section start -->

<script type="text/javascript"> 
    $("body").click(function(e)
    {
        if(e.target.className !== "search-item form-control")
        { 
            $(".list-unstyled-pos").hide();
        }
    });
</script> 

<!-- Print Receipt Bill Modal -->
<form action="" id="popup_content" method="post">
    <div class="modal fade MyPopup" id="print_order" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        
        <div class="modal-dialog" style="max-width: 50%;" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title" id="exampleModalLabel"><b>Bill Receipt</b></h3>
                    <!--  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button> -->
                </div>
                
                <div class="modal-body">
                    <?php 
                        $taxQry = "select tax_id,tax_value from gen_tax where active_flag='Y' AND default_tax=1";
                        $getTax = $this->db->query($taxQry)->result_array();

                        $tax_value = isset($getTax[0]["tax_value"]) ? $getTax[0]["tax_value"] : NULL;
                    ?>
                    <!-- new seaction start here -->
                    <div class="row">
                        <!-- left div start here -->
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-5 text-right">
                                    <label class="col-form-label">Bill No.</label>
                                </div>
                                <div class="form-group col-md-6">
                                    <input type="text" name="bill_number" id="bill_number" readonly class="form-control" value="" placeholder="">
                                    <input type="hidden" name="interface_header_id" id="interface_header_id">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-5 text-right">
                                    <label class="col-form-label">Total</label>
                                </div>
                                <div class="form-group col-md-6">
                                    <input type="text" name="total_amount" id="total_amount" readonly class="form-control" value="" placeholder="">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-5 text-right">
                                    <label class="col-form-label">Disc Amount</label>
                                </div>
                                <div class="form-group col-md-6">
                                    <input type="text" name="discount_amount" id="discount_amount" readonly class="form-control" value="" placeholder="">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-5 text-right">
                                    <label class="col-form-label">Tax @ <?php echo $tax_value;?> %</label>
                                </div>
                                <div class="form-group col-md-6">
                                    <input type="hidden" name="tax_value" id="tax_value" readonly value="<?php echo $tax_value;?>">
                                    <input type="text" name="tax_amount" id="tax_amount" readonly class="form-control" value="" placeholder="">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-5 text-right">
                                    <label class="col-form-label">Net Pay</label>
                                </div>
                                <div class="form-group col-md-6">
                                    <input type="text" name="net_pay" id="net_pay" readonly class="form-control" value="" placeholder="">
                                </div>
                            </div>
                        </div>
                        <!-- left div end here -->

                        <!-- right div start here -->
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-5 text-right">
                                    <label class="col-form-label">Mobile No</label>
                                </div>
                                <div class="form-group col-md-7">
                                    <input type="text" name="mobile_number" id="mobile_number" minlength="10" maxlength="10" autocomplete="off" class="form-control mobile_vali" value="" placeholder="9999999999">
                                    <input type="hidden" name="customer_id" id="customer_id" value="0">
                                    <div id="customerList"></div>
								</div>
                            </div>
                            <div class="row">
                                <div class="col-md-5 text-right">
                                    <label class="col-form-label">Customer Name</label>
                                </div>
                                <div class="form-group col-md-7">
                                    <input type="text" name="customer_name" id="customer_name" autocomplete="off" class="form-control" value="" placeholder="">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-5 text-right">
                                    <label class="col-form-label">Customer Address</label>
                                </div>
                                <div class="form-group col-md-7">
                                    <textarea name="customer_address" id="customer_address" autocomplete="off" rows="1" class="form-control" placeholder=""></textarea>
                                </div>
                            </div>
                            <script>  
                                $(document).ready(function()
                                {  
                                    $('#mobile_number').keyup(function()
                                    {  
                                        var mobile_number = $("#mobile_number").val();
                                      
                                        if(mobile_number != '')  
                                        {  
                                            $.ajax({
                                                url:"<?php echo base_url();?>pos/ajaxSearchOnlinePOSCustomers",  
                                                method : "POST",  
                                                data:{
                                                    mobile_number:mobile_number
                                                },  
                                                success:function(data)  
                                                {  
                                                    if(data == "no_data")
                                                    {
                                                        $('.list-unstyled').hide();
                                                        getConsumerDetails(0,'',''); 
                                                    }
                                                    else
                                                    {
                                                        $('#customerList').fadeIn();  
                                                       $('#customerList').html(data);
                                                    }
                                                }  
                                            });  
                                        }
                                        else
                                        {
                                            $('.list-unstyled').hide();
                                            getConsumerDetails(0,'',''); 
                                        }  
                                    });

                                    $(document).on('click', 'ul.list-unstyled li', function()
                                    {  
                                        var value = $(this).text();
                                        
                                        if(value === "Sorry! Customer Not Found.")
                                        {
                                            $('#phone_number').val("");  
                                            $('#customerList').fadeOut();
                                        }
                                        else
                                        {
                                            $('#phone_number').val(value);  
                                            $('#customerList').fadeOut();  
                                        }
                                    });
                                });

                                function getConsumerDetails(customer_id,mobile_number,customer_name,address)
                                {
                                    if(customer_id > 0)
                                    {
                                        $('#customer_id').val(customer_id);
                                        $('#mobile_number').val(mobile_number);
                                        $('#customer_name').val(customer_name);
                                        $('#customer_address').val(address);

                                        $('#customer_name').attr("readonly",true);
                                        $('#customer_address').attr("readonly",true);  
                                    }
                                    else
                                    {
                                        $('#customer_id').val('0');
                                        $('#customer_name').val('');
                                        $('#customer_address').val('');
                                        $('#customer_name').removeAttr("readonly",true);
                                        $('#customer_address').removeAttr("readonly",true); 
                                    }
                                }
                            </script> 
                        </div>
                        <!-- right div end here -->   
                    </div>


                    <div class="row">
                        <div class="col-md-3 text-left">
                            <label class="col-form-label payment-types-label">Payment Type <span class="text-danger">*</span></label>
                        </div>
                        <div class="form-group text-left payment-types col-md-7">
                            <?php 
                                $paymentTypeQry = "select * from pay_payment_types 
                                where 
                                    1=1 
                                    and active_flag = 'Y' 
                                    and  payment_type_id IN (5,6,7)

                                    order by sequence_number asc
                                    
                                    ";
                                $getPaymentTypes = $this->db->query($paymentTypeQry)->result_array();  
                            
                                foreach($getPaymentTypes as $paymentType)
                                {
                                    ?>
                                    <input type="radio" id="payment_method" required name="payment_method" value="<?php echo $paymentType["payment_type_id"];?>">
                                    <label for="html"><?php echo ucfirst($paymentType["payment_type"]);?></label> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                    <?php 
                                } 
                            ?>
                        </div>
                    </div>
                    <!-- new seaction end here -->


                    <?php /*
                    <div class="row">
                        <div class="col-md-6">
                            <div class="row">
                                <label class="col-form-label col-md-5 text-right payment-type-label">Bill No.</label>
                                <div class="form-group col-md-7">
                                    <input type="text" name="bill_number" id="bill_number" readonly class="form-control" value="" placeholder="">
                                    <input type="hidden" name="interface_header_id" id="interface_header_id">
                                </div>
                            </div>
                        </div>
                        <!-- <div class="col-md-6">
                            <div class="row">
                                <label class="col-form-label col-md-5 text-right payment-type-label">Mobile No.</label>
                                <div class="form-group col-md-7">
                                    <input type="number" name="mobile_number" id="mobile_number" autocomplete="off" class="form-control" value="" placeholder="">
                                </div>
                            </div>
                        </div> -->
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="row">
                                <label class="col-form-label col-md-5 text-right payment-type-label">Total</label>
                                <div class="form-group col-md-7">
                                    <input type="text" name="total_amount" id="total_amount" readonly class="form-control" value="" placeholder="">
                                </div>
                            </div>
                        </div>
                       <!--  <div class="col-md-6">
                            <div class="row">
                                <label class="col-form-label col-md-5 text-right payment-type-label">Customer Name</label>
                                <div class="form-group col-md-7">
                                    <input type="text" name="customer_name" id="customer_name" autocomplete="off" class="form-control" value="" placeholder="">
                                </div>
                            </div>
                        </div> -->
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="row">
                                <label class="col-form-label col-md-5 text-right payment-type-label">Disc Amount</label>
                                <div class="form-group col-md-7">
                                    <input type="text" name="discount_amount" id="discount_amount" readonly class="form-control" value="" placeholder="">
                                </div>
                            </div>
                        </div>

                        <!-- <div class="col-md-6">
                            <div class="row">
                                <label class="col-form-label col-md-5 text-right payment-type-label">Customer Address</label>
                                <div class="form-group col-md-7">
                                    <textarea name="customer_address" id="customer_address" rows="1" autocomplete="off" class="form-control"></textarea>
                                </div>
                            </div>
                        </div> -->
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="row">
                                
                                
                                <label class="col-form-label col-md-5 text-right payment-type-label">Tax @ <?php echo $tax_value;?> %</label>
                                <div class="form-group col-md-7">
                                    <input type="hidden" name="tax_value" id="tax_value" readonly value="<?php echo $tax_value;?>">
                                    <input type="text" name="tax_amount" id="tax_amount" readonly class="form-control" value="" placeholder="">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="row">
                                <label class="col-form-label col-md-5 text-right payment-type-label">Net Pay</label>
                                <div class="form-group col-md-7">
                                    <input type="text" name="net_pay" id="net_pay" readonly class="form-control" value="" placeholder="">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-8">
                            <div class="row">
                                <label class="col-form-label col-md-4 text-right payment-type-label">Payment Type <span class="text-danger">*</span></label>
                                
                                <div class="form-group col-md-7">
                                    <?php 
                                        $paymentTypeQry = "select * from pay_payment_types 
                                        where 
                                            1=1 
                                            and active_flag = 'Y' 
                                            and  payment_type_id IN (5,6,7)

                                            order by sequence_number asc
                                            
                                            ";
                                        $getPaymentTypes = $this->db->query($paymentTypeQry)->result_array();  
                                    
                                        foreach($getPaymentTypes as $paymentType)
                                        {
                                            ?>
                                            <input type="radio" id="payment_method" required name="payment_method" value="<?php echo $paymentType["payment_type_id"];?>">
                                            <label for="html"><?php echo ucfirst($paymentType["payment_type"]);?></label> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                            <?php 
                                        } 
                                    ?>
                                    <!-- <input type="radio" id="card_payment_type" required name="payment_type" value="CARD">
                                    <label for="css">Card</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                    
                                    <input type="radio" id="upi_payment_type" required name="payment_type" value="UPI">
                                    <label for="javascript">UPI</label> -->
                                </div>
                            </div>
                        </div>
                    </div> */ ?>
                </div>

                <div class="row modal-footer mb-3">
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-3">
                            </div>
                            <div class="col-md-9">
                                <div class="row">
                                    <div class="col-md-6 text-right">
                                        <!-- <button type="submit" class="btn btn-primary btn-block btn-text"><i class="fa fa-print" aria-hidden="true"></i> Print</button>
                                        -->
                                        <a href="javascript:void(0)" onclick="savePosOrder('order_base_tbl');" class="btn btn-primary btn-block btn-text" title="Print">
                                            <i class="fa fa-print" aria-hidden="true"></i> Print
                                        </a>
                                    </div>
                                    <!-- <div class="col-md-6">
                                        <button type="button" class="btn btn-default btn-block btn-lg btn-text" id='btnClosePopup' data-dismiss="modal">Cancel</button>
                                    </div> -->
                                </div>
                            </div>
                            </div>
                    </div>
                </div>
                
            </div>
        </div>
    </div>
</form>
<!-- Print Receipt Bill Modal End-->
    

<script>
    /** Order Interface Tbl**/
    function savePosOrder(type)
    {
        if( type == "order_interface_tbl" )
        {
           $.ajax({
                type: 'post',
                url: '<?php echo base_url();?>pos/insertPosOrderItems/'+type,
                data: $('form#order_items,form#footer_content,form#popup_content').serialize(),
                success: function (d) 
                {
                    data = JSON.parse(d);
                    var documentNumber = data['pos_items']["documentNumber"];
                    var interface_header_id = data['pos_items']["interface_header_id"];

                    $('#bill_number').val(documentNumber);
                    $('#interface_header_id').val(interface_header_id);

                    $('#print_order').modal();
                }
            });
        }
        else if( type == "order_base_tbl" )
        {
          /*   var payment_method = $("#payment_method").val();
            alert(payment_method); */

            var payment_method = $("input[type='radio'][name='payment_method']:checked").val();

            if(payment_method != undefined )
            {
                $.ajax({
                    type: 'post',
                    url: '<?php echo base_url();?>pos/insertPosOrderItems/'+type,
                    data: $('form#order_items,form#footer_content,form#popup_content').serialize(),
                    success: function (d) 
                    {
                        data = JSON.parse(d);
                        var status = data['pos_items']["status"];
                        var message = data['pos_items']["message"];

                        $(".remove_items").remove();

                        $('#total_order_amount').val('0.00');
                        $('#total_order_amount_text').html('0.00');
                        
                        $('#payable_amount').val('0.00');
                        $('#payable_amount_text').html('0.00');
                        $('#payable_new').html('0.00');

                        $("#total_amount").val('0.00');
                        $("#discount_amount").val("0.00");

                        $("#tax_amount").val("0.00");
                        $("#net_pay").val("0.00");
                        

                        $('#searchItem').val("");
                        $('#search_item_id').val(' ');
                        $("#discount").val("");

                        $('#mobile_number').val("");
                        $('#customer_name').val("");
                        $('#customer_id').val("0");
                        $("#customer_address").val("");

                        Swal.fire({
                            position: 'top',
                            //position: 'top-end',
                            icon: 'success',
                            title: message,
                            showConfirmButton: false,
                            timer: 1500,
                            width:'350px'
                        });

                        $('#print_order').modal('toggle'); 
                       //$('#print_order').modal().hide();
                    }
                });
            }
            else
            {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Please select payment type!',
                    //footer: '<a href="">Why do I have this issue?</a>'
                });


               /*  Swal.fire({
                    position: 'top',
                    //position: 'top-end',
                    icon: 'success',
                    title: 'Please select payment type!',
                    showConfirmButton: false,
                    timer: 1500,
                    width:'350px'
                });      */
            } 
        }
    }
</script>


<!-- Discount Functionality start here -->
<script>
    function selectDiscount(val)
    {
        if(val == 1) //Percentage
        {
            $('#discount').prop('placeholder',"Enter Percentage");
        }
        else if(val == 2) //Amount
        {
            $('#discount').prop('placeholder',"Enter Amount");
        }
    }
    $(".pos-footer").on("input keyup change", 'select[name^="discount_type"],input[name^="discount"]', function (event) 
    {
        calculateGrandTotal();
    });
</script>
<!-- Discount Functionality end here -->

<!-- Main and Sub Category Functionality start here -->
<script>
    /*  $( document ).ready(function() 
    {
        var counter = $("#main_cat_active_counter").val();
        var category_code = $("#main_cat_active_categoryCode").val();
        var category_id = $("#main_cat_active_categoryId").val();

        loadSubCategory(category_code,counter,category_id);
    }); */

    function loadSubCategory(category_code,counter,category_id)
    {
        $(".main-categories").removeClass("main-cat-active");
        $(".main-category-"+counter).addClass("main-cat-active");

        $("#main_cat_active_counter").val(counter);
        $("#main_cat_active_categoryCode").val(category_code);
        $("#main_cat_active_categoryId").val(category_id);

        $("#sub_cat_active_counter").val('0');
        $("#sub_cat_active_categoryCode").val('All');
        $("#sub_cat_active_categoryId").val('All');
        
        if(category_id)
        {
            loadCategoryItems(category_code,counter,category_id,'main_cat');
            $.ajax({  
                url    : "<?php echo base_url();?>pos/getAjaxSubCategories",  
                method : "POST",  
                data   : {category_id:category_id,category_code:category_code},  
                success:function(data)  
                {  
                    //$('#posItemList').fadeIn();  
                    $('.sub_categories').html(data); 
                }  
            });  
        }
    }

    function loadCategoryItems(category_code,counter,category_id,type)
    {
        if(type == 'sub_cat')
        {
            $("#sub_cat_active_counter").val(counter);
            $("#sub_cat_active_categoryCode").val(category_code);
            $("#sub_cat_active_categoryId").val(category_id);
        }

        $(".sub-categories").removeClass("sub-cat-active");
        $(".sub-category-"+counter).addClass("sub-cat-active");

        var main_category_code = $("#main_cat_active_categoryCode").val();
        var main_category_id = $("#main_cat_active_categoryId").val();

        var sub_category_code = $("#sub_cat_active_categoryCode").val();
        var sub_category_id =  $("#sub_cat_active_categoryId").val();

        if(main_category_code)
        {
            $.ajax({  
                url    : "<?php echo base_url();?>pos/getAjaxCategoryItems",  
                method : "POST",  
                data   : {
                        main_category_code : main_category_code,
                        main_category_id   : main_category_id,
                        sub_category_code  : sub_category_code,
                        sub_category_id    : sub_category_id
                },  
                success:function(data)  
                {  
                    console.log(data);
                    $('.pos_items').html(data); 
                }  
            });
        } 
    }
</script>
<!-- Main and Sub Category Functionality end here -->

<!-- Search Item start here -->
<script>  
    $(document).ready(function()
    {  
        $('#searchItem').keyup(function()
        {  
            var query = $(this).val();  

            if(query != '')  
            {  
                $.ajax({  
                    url:"<?php echo base_url();?>pos/posItemSearch",  
                    method:"POST",  
                    data:{query:query},  
                    success:function(data)  
                    {  
                        $('#posItemList').fadeIn();  
                        $('#posItemList').html(data);  
                    }  
                });  
            }  
        });
        
        $(document).on('click', 'ul.list-unstyled-pos li', function()
        {  
            var value = $(this).text();
            
            if(value === "No Items")
            {
                $('#searchItem').val("");  
                $('#posItemList').fadeOut();
            }
            else
            {
                $('#searchItem').val(value);  
                $('#posItemList').fadeOut();  
            }
        });
    }); 

    function selectSearchPosItems(item_id)
    {	
        if(item_id == null)
        {
            $('#search_item_id').val(' ');
        }
        else
        {     
            $('#search_item_id').val(item_id);
        }
    }		
    
    $('#enter_qty').on('keypress',function(e) 
    {
        if(e.which == 13) 
        {
            var item_id = $("#search_item_id").val();
            var enter_qty = $("#enter_qty").val();

            if(item_id && enter_qty > 0)
            {
                selectPosItems(item_id);
            }
        }
    });
</script>
<!-- Search Item end here -->

<!-- Item Onlclick Functionality start here -->
<script>
    var counter = 1;
	var i=1;
    function selectPosItems(item_id,qty)
    {
        if(item_id) 
        {
           var flag = 0;
            $.ajax({
                url: "<?php echo base_url('pos/getPOLineDatas'); ?>/"+item_id,
                type: "GET",
                data:{
                    '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
                },
                datatype: "JSON",
                success: function(d)
                {
                    data = JSON.parse(d);
					var countKey = Object.keys(data['items']).length;
                    
                    if(countKey > 0)
                    {
                        $.each(data['items'], function(i, item) 
                        {
                            $("table.line_items").find('input[name^="text_item_id[]"]').each(function () 
                            {
                                var row = $(this).closest("tr");
                                var exist_item_id = +row.find('input[name^="text_item_id[]"]').val();
                            
                                if(exist_item_id == item_id ) //|| uom == ""
                                {
                                    flag = 1;
                                }
                            });
                            
                            if(flag == 0)
                            {
                               var text_item_id = item.item_id;
                                var item_name = item.item_description;
                                var uom_id = item.uom_id;
                                var uom_code = item.uom_code;
                                var rate = parseFloat(item.item_price).toFixed(2);

                                var enter_qty = $("#enter_qty").val();
                                var search_item_id = $("#search_item_id").val();

                                if(enter_qty && search_item_id && (search_item_id == item_id))
                                {
                                    var quantity = enter_qty;
                                }
                                else
                                {
                                    var quantity = qty;
                                }

                                var amount = parseFloat(quantity * rate).toFixed(2);
                                var tax_perentage = 5;
                            
                                var newRow = $("<tr class='remove_items'>");
                                var cols = "";
                                var cols = "<td class='tab-md-50 text-center'><a class='deleteRow'><i class='fa fa-minus-square'></i></a></td>";
                                cols += "<td class='order-item-list tab-md-150-' style='width: 190px!important;'>"+
                                "<input type='hidden' name='text_item_id[]' value='"+text_item_id+"' id='text_product_id_"+counter+"'>"+
                                "<input type='hidden' name='uom_id[]' value='"+uom_id+"' id='uom_id_"+counter+"'>"+
                                "<input type='hidden' name='counter' value='"+counter+"'>"+
                                    item_name
                                +"</td>";

                                cols += '<td class="order-item-list qty-inc-dec tab-md-100">'+
                                '<span class="inc-qty"><input type="button" value="-" id="subs'+item_id+'" onclick="qtyDec('+item_id+');" class="btn btn-danger btn-sm"/></span>'+
                                '<span class="qty-text"><input type="number" min="1" name="quantity[]" id="quantity'+item_id+'" class="onlyNumber mobile_vali enter-numb form-control" max="4" value="'+quantity+'"  /></span>'+
                                '<span class="dec-qty"><input type="button" value="+" id="adds'+item_id+'" onclick="qtyInc('+item_id+');" class="btn btn-success btn-sm" /></span>'+
                                '</td>';
                                
                                // cols += '<td class="order-item-list">'+uom_code+'</td>';
                                // cols += '<td class="text-right order-item-list">'+rate+'</td>';
                                cols += '<td class="text-right order-item-list tab-md-100"><input type="hidden" name="rate[]" id="rate'+item_id+'" value="'+rate+'"><input type="hidden" name="amount[]" id="amount'+item_id+'" value="'+amount+'"><span class="line_total'+item_id+'">'+amount+'</span></td>';
                                // cols += '<td class="text-right order-item-list">'+tax_perentage+'</td>';
                                cols += "</tr>";
                                
                                newRow.html(cols);
                                $("table.line_items").append(newRow);
                                calculateLineRow(item_id);
                                calculateGrandTotal();
                                $("#searchItem").val("");
                                $("#search_item_id").val("");
                                $("#enter_qty").val("");
                                i++;
                                counter++;
                            }
                            else 
                            {
                                qtyInc(item_id);
                                calculateLineRow(item_id);
                                calculateGrandTotal();
                                $("#searchItem").val("");
                                $("#search_item_id").val("");
                                $("#enter_qty").val("");
                                //$('.line-items-error').text('Item already exist.').animate({opacity: '0.0'}, 2000).animate({}, 1000).animate({opacity: '1.0'}, 2000);
                            }
                        });
                    }
                    else
                    {
                        $('#err_product').text('No Data Found!').animate({opacity: '0.0'}, 2000).animate({opacity: '0.0'}, 1000).animate({opacity: '1.0'}, 2000);
                    }
                },
                error: function(xhr, status, error) {
                    $('#err_product').text('Enter Product Code / Name').animate({opacity: '0.0'}, 2000).animate({opacity: '0.0'}, 1000).animate({opacity: '1.0'}, 2000);
                }
            });
        }
    }

    //Quantity Increment
    function qtyInc(item_id)
    {
        var enter_qty = $("#enter_qty").val();
                                
        if(enter_qty)
        {
            var exist_qty = $("#quantity"+item_id).val();
            var quantity = parseInt(exist_qty) + parseInt(enter_qty);
            $("#quantity"+item_id).val(quantity);
        }
        else
        {
            var quantity = $("#quantity"+item_id).val();
            quantity++;
            $("#subs"+item_id).prop("disabled", !quantity);
            $("#quantity"+item_id).val(quantity);
        }
        
        calculateLineRow(item_id);
        calculateGrandTotal();
    }

    //Quantity Decrement
    function qtyDec(item_id)
    {
        var quantity = $("#quantity"+item_id).val();

        if(quantity == 1)
        {
            $("#quantity"+item_id).val(quantity);
        }
        else if (quantity >= 1) 
        {
            quantity--;
            $("#quantity"+item_id).val(quantity);
        }
        else 
        {
            $("#subs"+item_id).prop("disabled", true);
        }
        calculateLineRow(item_id);
        calculateGrandTotal();
    }

    $("table.line_items").on("input keyup change", 'input[name^="quantity[]"]', function (event) 
    {
        var row = $(this).closest("tr");
        var item_id = +row.find('input[name^="text_item_id[]"]').val();

        var quantity = $("#quantity"+item_id).val();

        if(quantity = "" || quantity == 0 )
        {
            $("#quantity"+item_id).val('1');
        }
        else
        {
            calculateLineRow(item_id);
            calculateGrandTotal();
        }
        
    });

    function calculateLineRow(item_id)
	{
        var rate = $("#rate"+item_id).val();
        var quantity = $("#quantity"+item_id).val();

        var amount = parseFloat(quantity * rate).toFixed(2);

        $("#amount"+item_id).val(amount);
        $(".line_total"+item_id).text(amount);
    }

    $("table.line_items").on("click", "a.deleteRow,a.deleteRow1", function(event) 
    {
        $(this).closest("tr").remove();
        calculateGrandTotal();
    });

    function calculateGrandTotal() 
    {
        var totalOrderAmount = 0;
       
        $("table.line_items").find('input[name^="amount[]"]').each(function () {
            totalOrderAmount += +$(this).val();
        });

        var discount_type = $("#discount_type").val();
        var discount = $("#discount").val();

        if(discount_type && discount != "" && discount > 0)
        {
            if(discount_type == 1) //Percentage
            {
                var discount_amount = discount / 100 * totalOrderAmount;
                var payableAmount = totalOrderAmount - discount_amount;
            }
            else if(discount_type == 2) //Amount
            {
                var discount_amount  = discount;
                var payableAmount = totalOrderAmount - discount;
            }

            $('#total_order_amount').val(totalOrderAmount.toFixed(2));
            $('#total_order_amount_text').html(totalOrderAmount.toFixed(2));

            $('#payable_amount').val(payableAmount.toFixed(2));
            $('#payable_amount_text').html(payableAmount.toFixed(2));

            $("#total_amount").val(totalOrderAmount.toFixed(2));
            $("#discount_amount").val(discount_amount.toFixed(2));

            var tax_value = $("#tax_value").val();

            var tax_amount = (tax_value / 100) * payableAmount;
            var paid_amount = payableAmount + tax_amount;
            //var paid_amountNew = payableAmount + tax_amount;

            $('#payable_new').html(paid_amount.toFixed(2));
            $("#tax_amount").val(tax_amount.toFixed(2));
            $("#net_pay").val(paid_amount.toFixed(2)); 
        }
        else
        {
            var  payableAmount = totalOrderAmount;

            $('#total_order_amount').val(payableAmount.toFixed(2));
            $('#total_order_amount_text').html(payableAmount.toFixed(2));
            
            $('#payable_amount').val(payableAmount.toFixed(2));
            $('#payable_amount_text').html(payableAmount.toFixed(2));
          

            $("#total_amount").val(payableAmount.toFixed(2));
            $("#discount_amount").val("0.00");

            var tax_value = $("#tax_value").val();

            var tax_amount = (tax_value / 100) * payableAmount;

            var paid_amount = payableAmount + tax_amount;
            //var paid_amountNew = payableAmount + tax_amount;

            $('#payable_new').html(paid_amount.toFixed(2));

            $("#tax_amount").val(tax_amount.toFixed(2));
            $("#net_pay").val(paid_amount.toFixed(2));
        }

        if(totalOrderAmount > 0)
        {
            $(".disabled-btn").removeClass("disabled-btn",true);
            
        }
        else if(totalOrderAmount == 0 || totalOrderAmount == "")
        {
            $(".proceedto-pay").addClass("disabled-btn");
        }
    }	
</script>
<!-- Item Onlclick Functionality end here -->
