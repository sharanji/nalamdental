    <style>
    a.dining-proceedto-pay {
        font-size: 16px;
        position: relative;
        top: 10px;
        color: #000;
        background: #00a651;
        padding: 10px;
        color: #fff;
        float: right;
        border-radius: 5px;
        position: relative;
        right: 0px;
        margin: 0px 11px 0px 0px;
    }

    .order_type_btns{height:20px; width:20px;}
    .order_type_name{font-size:15px;position: relative;top: -5px;}
    .table-number {font-size: 14px; position: relative;top: -5px;margin: 6px 0px 0px 0px;}

    /* New Css Starts */
    span.cat-pro-name {
        text-align: center;
        float: left;
        width: 100%;
        color: #000;
    }

    .all-new {
        background: #fbedbaa6;
        padding: 10px;
        text-align: center;
        border: 1px solid #b3c783;
        border-radius: 7px!important;
        width: 100%;
        float: left;
        margin-bottom: 10px;
        color:#000;
        word-break: break-all;
    }

    .all-new:hover {
        background: #c9a10e;
        color: #fff!important;
        padding: 10px;
        text-align: center;
        border: 1px solid #c9a10e;
        border-radius: 7px!important;
    }

    a.main-categories.main-category-new.main-cat-active span {
        background: #c9a10e;
        color: #fff!important;
        border: 1px solid #c9a10e;
    }

    a.main-cat-active {
        background: #8d3b3b!important;
    }

    .main-cat-leftsection {
        max-height: 68vh;
        overflow-y: auto;
        overflow-x: hidden;
    }

    p.pos-item-name {
        min-height: 48px!important;
        max-height: 48px!important;
        float: left;
        width: 100%;
        padding: 4px;
        overflow-y: auto;    
        word-wrap: break-word;
        margin:0!important;
    }
    p.pos-item-name::-webkit-scrollbar {
    display: none;
    }
    /* 
        .all-new p {
            position: absolute;
            top: 28%;
            left: 10px;
            text-align: center;
            margin: 0 auto;
            width: 80%;
            font-size: 16px;
            border-radius: 27px!important;
        } 
    */
    /* New Css Ends */
</style>
<script src="<?php echo base_url();?>assets/backend/jspm/JSPrintManager.js"></script>
<!-- <script src="<?php echo base_url();?>assets/backend/jspm/zip-full.min.js"></script> -->

<script>
    var clientPrinters = null;
	var _this = this;
	
	JSPM.JSPrintManager.license_url = "<?php echo base_url();?>jspm/index.php";
	
	//WebSocket settings
	JSPM.JSPrintManager.auto_reconnect = true;
	JSPM.JSPrintManager.start();

    function jspmWSStatus() 
    {
        if (JSPM.JSPrintManager.websocket_status == JSPM.WSStatus.Open)
        {   
            return true;
        }
        else if (JSPM.JSPrintManager.websocket_status == JSPM.WSStatus.Closed) 
        {
            console.warn('JSPrintManager (JSPM) is not installed or not running! Download JSPM Client App from https://neodynamic.com/downloads/jspm');
            return false;
        }
        else if (JSPM.JSPrintManager.websocket_status == JSPM.WSStatus.Blocked) 
        {
            alert('JSPM has blocked this website!');
            return false;
        }
    }
</script>

<?php 
    $taxQry = "select tax_id,tax_value from gen_tax where active_flag='Y' AND default_tax=1";
    $getTax = $this->db->query($taxQry)->result_array();
    $tax_value = isset($getTax[0]["tax_value"]) ? $getTax[0]["tax_value"] : NULL;

    $pos_dine_in_type = isset($_SESSION["pos_dine_in_type"]) ? $_SESSION["pos_dine_in_type"] : NULL;
     
    if( (isset($table_id) && !empty($table_id)))
    {
        $takeAwayChecked = "";
        $dineInChecked = "checked='checked'";
        $displayTableBtn = 'style="display:block;"'; 
    }
    else if( (isset($pos_dine_in_type) && !empty($pos_dine_in_type)))
    {
        if( $pos_dine_in_type == 'DINE_IN' )
        {
            $takeAwayChecked = "";
            $homeDeliveryChecked = "";
            $dineInChecked = "checked='checked'";
            $displayTableBtn = 'style="display:block;"'; 
        }
        else if( $pos_dine_in_type == 'TAKEAWAY' )
        {
            $takeAwayChecked = "checked='checked'";
            $dineInChecked = "";
            $homeDeliveryChecked = "";
            $displayTableBtn = 'style="display:none;"';
        }
        else if( $pos_dine_in_type == 'HOME_DELIVERY' )
        {
            $homeDeliveryChecked = "checked='checked'";
            $dineInChecked = "";
            $takeAwayChecked = "";
            $displayTableBtn = 'style="display:none;"';
        }
    }
    else
    {
        $takeAwayChecked = "checked='checked'";
        $dineInChecked = "";
        $homeDeliveryChecked = "";
        $displayTableBtn = 'style="display:none;"';
    }
?>

<div class="content" style="background:#fff!important;overflow: hidden!important">
    <form action="" id="order_items" method="post">

        <!-- POS Header start here -->
        <div class="row mb-1">
            <div class="col-md-6">
                <a href="<?php echo base_url();?>admin/home" >
                    <img src="<?php echo base_url();?>uploads/logo.png" style="width:210px;height:29px;">
                </a>
                &nbsp;&nbsp;&nbsp;
                <a id="full_screen" href="javascript:void(0);" class="header-icons">
                    <i class="fa fa-arrows-alt ic-colours" aria-hidden="true"></i>
                </a>

                <script>
                    $('#full_screen').on('click',function() 
                    {
                        if(document.fullscreenElement||document.webkitFullscreenElement||document.mozFullScreenElement||document.msFullscreenElement) { //in fullscreen, so exit it
                            //alert('exit fullscreen');
                            if(document.exitFullscreen) {
                                document.exitFullscreen();
                            } else if(document.msExitFullscreen) {
                                document.msExitFullscreen();
                            } else if(document.mozCancelFullScreen) {
                                document.mozCancelFullScreen();
                            } else if(document.webkitExitFullscreen) {
                                document.webkitExitFullscreen();
                            }
                        } else { //not fullscreen, so enter it
                            //alert('enter fullscreen');
                            if(document.documentElement.requestFullscreen) {
                                document.documentElement.requestFullscreen();
                            } else if(document.documentElement.webkitRequestFullscreen) {
                                document.documentElement.webkitRequestFullscreen();
                            } else if(document.documentElement.mozRequestFullScreen) {
                                document.documentElement.mozRequestFullScreen();
                            } else if(document.documentElement.msRequestFullscreen) {
                                document.documentElement.msRequestFullscreen();
                            }
                        }
                    });
                </script>
                &nbsp;&nbsp;&nbsp;
                <a href="<?php echo base_url();?>admin/home" title="Home" class="header-icons">
                    <i class="fa fa-home" style="font-size:20px;"></i>
                </a>
            </div>
            
            <div class="col-md-6">  
                <div class="row">
                    <!-- <div class="col-md-4"></div> -->
                    
                    <div class="col-md-8 pos_dining_type" style="float:right;text-align:right;">    
                        <input type="radio" id="takeaway" class="order_type_btns" name="pos_dine_in_type" value="TAKEAWAY" <?php echo $takeAwayChecked;?>/>
                        <label class="order_type_name">Takeaway</label> &nbsp;&nbsp;&nbsp;
                        
                        <input type="radio" id="dine_in" class="order_type_btns" name="pos_dine_in_type" value="DINE_IN" <?php echo $dineInChecked;?>>
                        <label class="order_type_name">Dine In</label> &nbsp;&nbsp;&nbsp;

                        <input type="radio" id="dine_in" class="order_type_btns" name="pos_dine_in_type" value="HOME_DELIVERY" <?php echo $homeDeliveryChecked;?>>
                        <label class="order_type_name">Home Delivery</label>
                    </div>

                    <div class="col-md-4 order_type_name">
                        <label class="col-form-label">Date : <?php echo date("d/M/Y");?> <span class='clock'></span></label>
                        <input type="hidden" name="bill_date" id="bill_date" readonly value="<?php echo date("d/M/Y");?>" placeholder="">
                    </div>
                </div>
            </div>
        </div>

        <script>
            $(".pos_dining_type input[name='pos_dine_in_type']").click(function()
            {
                var checkedVal = $('input:radio[name=pos_dine_in_type]:checked').val();
                
                if( checkedVal == "DINE_IN")
                {
                   //window.location = '<?php echo base_url();?>pos/posOrder/dine_in';
                    window.location = '<?php echo base_url();?>items.html/<?php echo $this->user_id;?>';
                    $(".dine_in_buttons").show();
                    $(".proceedto-pay").hide();
                    $(".table_btn").show(); 
                   
                    $(".pos_buttons").hide(); 
                    $(".btn-disabled").addClass("disabled-btn");

                    $(".remove_items").remove();
                                
                    $('#total_order_amount').val('0.00');
                    $('#total_order_amount_text').html('0.00');

                    $('#payable_amount').val('0.00');
                    $('#payable_amount_text').html('0.00');
                    $('.payable_new').html('0.00');

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

                }
                else if( checkedVal == "TAKEAWAY" || checkedVal == "HOME_DELIVERY")
                {
                    if(checkedVal == "TAKEAWAY")
                    {
                        window.location = '<?php echo base_url();?>pos/posOrder/takeaway';
                    }
                    else  if(checkedVal == "HOME_DELIVERY")
                    {
                        window.location = '<?php echo base_url();?>pos/posOrder/home_delivery';
                    }

                    $(".table_btn").hide();
                    $(".dine_in_buttons").hide();
                    $(".pos_buttons").show(); 
                    $(".disabled-btn").removeClass("disabled-btn",true);
                }
            });
        </script>

        <script type="text/javascript">
            // 24 hour clock  
            /* setInterval(function() 
            {
                var currentTime = new Date();
                var hours = currentTime.getHours();
                var minutes = currentTime.getMinutes();
                var seconds = currentTime.getSeconds();

                // Add leading zeros
                hours = (hours < 10 ? "0" : "") + hours;
                minutes = (minutes < 10 ? "0" : "") + minutes;
                seconds = (seconds < 10 ? "0" : "") + seconds;

                // Compose the string for display
                var currentTimeString = hours + ":" + minutes + ":" + seconds;
                $(".clock").html(currentTimeString);

            }, 1000); */
            $(document).ready(function()
            {
                setInterval('updateClock()', 0);
            });

            function updateClock()
            {
                var currentTime = new Date ( );
                var currentHours = currentTime.getHours ( );
                var currentMinutes = currentTime.getMinutes ( );
                var currentSeconds = currentTime.getSeconds ( );

                // Pad the minutes and seconds with leading zeros, if required
                currentMinutes = ( currentMinutes < 10 ? "0" : "" ) + currentMinutes;
                currentSeconds = ( currentSeconds < 10 ? "0" : "" ) + currentSeconds;

                // Choose either "AM" or "PM" as appropriate
                var timeOfDay = ( currentHours < 12 ) ? "AM" : "PM";

                // Convert the hours component to 12-hour format if needed
                currentHours = ( currentHours > 12 ) ? currentHours - 12 : currentHours;

                // Convert an hours component of "0" to "12"
                currentHours = ( currentHours == 0 ) ? 12 : currentHours;

                // Compose the string for display
                var currentTimeString = currentHours + ":" + currentMinutes + ":" + currentSeconds + " " + timeOfDay;
               
                $(".clock").html(currentTimeString);	  	
            }
        </script>
        <!-- POS Header end here -->

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
                    <div class="col-md-8 p-0">
                        <div class="leftsection row">
                            <div class="col-md-2 main-cat-leftsection" id="style-3">
                                <?php /* <a class="main-categories main-category-0 --main-cat-active" onclick="loadSubCategory('All','0','All');" href="javascript:void(0);" title="All">
                                    <img src="<?php echo base_url();?>uploads/allmenus.png" title="All" style="width: 80%;text-align: center;margin: 0 auto;position: relative;left: 20px;" alt="..."> 
                                </a> */ ?>
                                <a class="main-categories main-category-new main-category-0 main-cat-active" onclick="loadSubCategory('All','0','All');" href="javascript:void(0);" title="All">
                                    <span class="all-new">ALL</span>
                                    <?php /* <img src="<?php echo base_url();?>uploads/allmenus.png" title="All" style="visibility: hidden;width: 80%;text-align: center;margin: 0 auto;position: relative;left: 20px; height: 63px;" alt="...">  */ ?>
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
                                        <a class="main-categories main-category-new main-category-<?php echo $i; ?> <?php #echo $mainCatActive;?>" onclick="loadSubCategory('<?php echo $categoryCode;?>','<?php echo $i;?>','<?php echo $categoryId;?>');" href="javascript:void(0);" title="<?php echo ucfirst($row["list_value"]); ?>">
                                            <?php 
                                                $url = "uploads/lov_images/".$row['list_type_value_id'].".png";
                                                if(file_exists($url))
                                                {
                                                   /*  ?>
                                                    <img src="<?php echo base_url().$url;?>" style="visibility:hidden;width: 80%;text-align: center;margin: 0 auto;position: relative;left: 20px;" alt="...">
                                                    <?php  */
                                                }
                                            ?>
                                            <span class="all-new"><?php echo $row["list_value"]; ?></span>
                                        </a>
                                        <?php
                                        $i++;
                                    }
                                ?>
                                <input type="hidden" name="main_cat_active_counter" id="main_cat_active_counter" value="">
                                <input type="hidden" name="main_cat_active_categoryCode" id="main_cat_active_categoryCode" value="">
                                <input type="hidden" name="main_cat_active_categoryId" id="main_cat_active_categoryId" value="">
                            
                            </div>
                            <div class="col-md-10">
                                <div class="leftsection p-0">
                                    <div class="card1">
                                        <div class="card-body1">
                                            <input type="hidden" name="sub_cat_active_counter" id="sub_cat_active_counter" value="">
                                            <input type="hidden" name="sub_cat_active_categoryCode" id="sub_cat_active_categoryCode" value="">
                                            <input type="hidden" name="sub_cat_active_categoryId" id="sub_cat_active_categoryId" value="">
                                            <!-- sub categories start here -->
                                            <div class="scrollmenu scrollmenu sub_categories" id="style-3" --style="display:none;">
                                            
                                            </div>
                                            <!-- sub categories end here -->

                                            <!-- Item list start here -->
                                            <div class="pricelist-left pricelist-left-new">
                                                <div class="row pos_items pr-3">
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
                            </div>
                            <div class="scrollmenu">
                            <?php /* <a class="main-categories main-category-0 --main-cat-active" onclick="loadSubCategory('All','0','All');" href="javascript:void(0);" title="All">
                                    <img src="<?php echo base_url();?>uploads/no-image-mobile.png" title="All" style="width:90px;height:50px;" alt="..."> 
                                    <br>All
                                </a>  */ ?>

                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 leftsection">
                        <?php 
                            if(isset($order_type) && $order_type == "takeaway"){
                                $posCon = "and header_tbl.order_source = 'POS' ";
                            }else if(isset($order_type) && $order_type == "home_delivery"){
                                $posCon = "and header_tbl.order_source = 'HOME_DELIVERY' ";
                            }else{
                                $posCon = "and 1 = 1";
                            }

                            $posOrderQry = "select 
                                header_tbl.interface_header_id,
                                header_tbl.order_number,
                                header_tbl.order_status,
                                header_tbl.interface_status,
                                header_tbl.customer_id,
                                customer.customer_name,
                                customer.mobile_number,
                                header_tbl.order_source

                                from ord_order_interface_headers as header_tbl
                              
                                left join per_user as user on 
                                user.user_id = header_tbl.customer_id

                                left join cus_consumers as customer on 
                                customer.customer_id = user.reference_id

                                where 1=1
                                $posCon
                                and header_tbl.interface_status != 'Success'
                            ";
                            $posOrders = $this->db->query($posOrderQry)->result_array();
                       
                            if(isset($order_type) && ($order_type == "takeaway" || $order_type == "home_delivery") )
                            {
                                if(count($posOrders) > 0){
                                    $btnClasss="success";
                                }else
                                {
                                    $btnClasss="primary";
                                }
                                $checkOrders = "select 
                                header_tbl.interface_header_id,
                                header_tbl.customer_id,
                                customer.customer_name,
                                customer.mobile_number,
                                customer.address1

                                from ord_order_interface_headers as header_tbl

                                left join per_user as user on 
                                user.user_id = header_tbl.customer_id

                                left join cus_consumers as customer on 
                                customer.customer_id = user.reference_id
                                
                                where 1=1
                                
                                and header_tbl.interface_header_id = '".$interface_header_id."' ";
                                
                                $getOpenOrders = $this->db->query($checkOrders)->result_array();

                                $customer_id = isset($getOpenOrders[0]['customer_id']) ? $getOpenOrders[0]['customer_id'] : NULL;
                                $customer_name = isset($getOpenOrders[0]['customer_name']) ? $getOpenOrders[0]['customer_name'] : NULL;
                                $mobile_number = isset($getOpenOrders[0]['mobile_number']) ? $getOpenOrders[0]['mobile_number'] : NULL;
                                $address1 = isset($getOpenOrders[0]['address1']) ? $getOpenOrders[0]['address1'] : NULL;

                                ?>
                                <div class="row table_btn">
                                    <div class="col-md-12">
                                        <a href="javascript:void(0);" onclick="selectOpenOrders();" class="btn btn-<?php echo $btnClasss;?> table-number">
                                            Open Orders ( <?php echo count($posOrders);?> )
                                        </a>
                                        <a href="javascript:void(0);" onclick="addCustomer();" class="btn btn-warning table-number select_customer_name float-right" title="Customer">
                                            <?php 
                                                if($customer_name != NULL)
                                                {
                                                    echo $customer_name;
                                                }
                                                else
                                                { 
                                                    ?>
                                                    Select Customer
                                                    <?php 
                                                } 
                                            ?>
                                        </a>
                                        <input type="hidden" name="new_customer_id" id="new_customer_id" value="<?php echo $customer_id; ?>">
                                    </div>
                                </div>
                                <?php
                            }
                            else
                            { 
                                ?>
                                <div class="row table_btn" <?php echo isset($displayTableBtn) ? $displayTableBtn : "";?>>
                                    <div class="col-md-12">
                                        <a title="Table <?php if(isset($table_data[0]["sub_table_code"])) { ?>: <?php echo isset($table_data[0]["sub_table_code"]) ? $table_data[0]["sub_table_code"] : NULL;?><?php } ?>" href="<?php echo base_url();?>dine_in/dineInTables" class="btn btn-primary table-number">
                                            Table <?php if(isset($table_data[0]["sub_table_code"])) { ?>: <span class="table_code"><?php echo isset($table_data[0]["sub_table_code"]) ? $table_data[0]["sub_table_code"] : NULL;?></span><?php } ?>
                                        </a>
                                        <input type="hidden" name="table_id" value="<?php echo isset($table_id)? $table_id : NULL;?>">
                                        <?php 
                                            if(isset($table_id) && !empty($table_id))
                                            {
                                                $checkOrders = "select 
                                                header_tbl.interface_header_id,
                                                header_tbl.customer_id,
                                                customer.customer_name,
                                                customer.mobile_number,
                                                customer.address1

                                                from ord_order_interface_headers as header_tbl

                                                left join cus_consumers as customer on 
                                                    customer.customer_id = header_tbl.customer_id
                                                
                                                where 1=1
                                                and header_tbl.order_source = 'DINE_IN'
                                                and header_tbl.interface_status != 'Success'
                                                and header_tbl.table_id = '".$table_id."' ";
                                                
                                                $getOpenOrders = $this->db->query($checkOrders)->result_array();

                                                $customer_id = isset($table_data[0]['customer_id']) ? $table_data[0]['customer_id'] : NULL;
                                                $customer_name = isset($table_data[0]['customer_name']) ? $table_data[0]['customer_name'] : NULL;
                                                $mobile_number = isset($table_data[0]['mobile_number']) ? $table_data[0]['mobile_number'] : NULL;
                                                $address1 = isset($table_data[0]['address1']) ? $table_data[0]['address1'] : NULL;

                                                if( count($getOpenOrders) > 0 )
                                                {
                                                    ?>
                                                    <a href="javascript:void(0);" onclick="ajaxCheckOpenOrders('<?php echo $table_id; ?>');" class="btn btn-success table-number" title="New Order">
                                                        New Order
                                                    </a>
                                                    <?php 
                                                }
                                                ?>
                                                <?php 
                                                    if(($customer_id == NULL || $customer_id == 0) && $interface_status == "Printed")
                                                    {

                                                    }
                                                    else
                                                    {
                                                        ?>
                                                        <a href="javascript:void(0);" onclick="addCustomer();" class="btn btn-warning table-number select_customer_name float-right" title="Customer">
                                                            <?php 
                                                                if($customer_name != NULL)
                                                                {
                                                                    echo $customer_name;
                                                                }
                                                                else
                                                                { 
                                                                    ?>
                                                                    Select Customer
                                                                    <?php 
                                                                } 
                                                            ?>
                                                        </a>
                                                        <?php 
                                                    } 
                                                ?>

                                                <a href="javascript:void(0);" onclick="addCustomer();" class="btn btn-warning table-number select_new_customer_name float-right" style="display:none;" title="Customer">
                                                    <?php 
                                                        if($customer_name != NULL)
                                                        {
                                                            echo $customer_name;
                                                        }
                                                        else
                                                        { 
                                                            ?>
                                                            Select Customer
                                                            <?php 
                                                        } 
                                                    ?>
                                                </a>

                                                <input type="hidden" name="new_customer_id" id="new_customer_id" value="<?php #echo $customer_id; ?>">
                                                <?php
                                            }  
                                        ?>
                                        <input type="hidden" name="sub_table" readonly value="" id="sub_table" class="sub_table">
                                    </div>
                                </div>
                                <?php 
                            } 
                        ?>
                        
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
                                    <?php 
                                        $totalLineTotal = 0;
                                        if(isset($dineInOrders) && count($dineInOrders) > 0 )
                                        {
                                            $counter = 1;
                                            
                                            foreach($dineInOrders as $dineInOrders)
                                            { 
                                                ?>
                                                <tr class="remove_items">
                                                    <td class="tab-md-50 text-center">
                                                        <?php 
                                                            if($interface_status != 'Printed')
                                                            {
                                                                ?>
                                                                <a class="deleteRow" onclick="deleteLineItems('<?php echo $dineInOrders['interface_line_id'];?>');">
                                                                    <i class="fa fa-minus-square"></i>
                                                                </a>
                                                                <?php
                                                            }
                                                        ?>
                                                    </td>
                                                    <td class="order-item-list" style="width: 190px!important;">
                                                        <input type="hidden" name="interface_line_id[]" value="<?php echo $dineInOrders['interface_line_id'];?>" id="interface_line_id_<?php echo $counter;?>">
                                                        <input type="hidden" name="exist_quantity[]" value="<?php echo $dineInOrders["quantity"];?>" id="exist_quantity_<?php echo $counter;?>">
                                                        
                                                        <input type="hidden" name="text_item_id[]" value="<?php echo $dineInOrders['product_id'];?>" id="text_product_id_<?php echo $counter;?>">
                                                        <input type="hidden" name="uom_id[]" value="26" id="uom_id_<?php echo $counter;?>">
                                                        <input type="hidden" name="counter" value="<?php echo $counter;?>"><?php echo $dineInOrders["item_name"];?>
                                                    </td>
                                                    <td class="order-item-list qty-inc-dec tab-md-100">
                                                        <span class="inc-qty">
                                                            <input type="button" value="-" id="subs<?php echo $dineInOrders["product_id"];?>" onclick="qtyDec(<?php echo $dineInOrders['product_id'];?>);" class="btn btn-danger btn-sm">
                                                        </span>
                                                        <span class="qty-text">
                                                            <input type="number" min="1" name="quantity[]" id="quantity<?php echo $dineInOrders["product_id"];?>" class="onlyNumber mobile_vali enter-numb form-control" value="<?php echo $dineInOrders["quantity"];?>">
                                                        </span>
                                                        <span class="dec-qty">
                                                            <input type="button" value="+" id="adds<?php echo $dineInOrders["product_id"];?>" onclick="qtyInc(<?php echo $dineInOrders['product_id'];?>);" class="btn btn-success btn-sm">
                                                        </span>
                                                    </td>
                                                    <td class="text-right order-item-list tab-md-100">
                                                        <input type="hidden" name="rate[]" id="rate<?php echo $dineInOrders["product_id"];?>" value="<?php echo number_format($dineInOrders["price"],DECIMAL_VALUE,'.','');?>">
                                                        <input type="hidden" name="amount[]" id="amount<?php echo $dineInOrders["product_id"];?>" value="<?php echo number_format($dineInOrders["line_total"],DECIMAL_VALUE,'.','');?>">
                                                        <span class="line_total<?php echo $dineInOrders["product_id"];?>"><?php echo number_format($dineInOrders["line_total"],DECIMAL_VALUE,'.','');?></span>
                                                    </td>
                                                </tr>
                                                <?php
                                                $counter++;
                                                $totalLineTotal += $dineInOrders["line_total"];  
                                            }  
                                        } 
                                    ?>
                                </tbody>                                            
                            </table>
                        </div> 
                    </div>
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
                        <!-- <div class="form-group col-md-6">
                            <input type="text" name="enter_qty" id="enter_qty" class="form-control mobile_vali" maxlength="4" autocomplete="off" value="" placeholder="Enter Qty">
                        </div> --> 
                    </div>       
                </div>

                <div class="col-md-1">
                </div>

                <div class="col-md-3">
                    <div class="row">   
                        <div class="form-group col-md-6">
                            <select name="discount_type" id="discount_type" onchange="selectDiscount(this.value);" class="form-control">
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
                            <?php 
                                if(isset($interface_status) && $interface_status == "Printed")
                                {
                                    $offer_percentage = isset($table_data[0]["offer_percentage"]) ? $table_data[0]["offer_percentage"] : NULL;

                                    if($offer_percentage != NULL)
                                    {
                                        $offer_percentage_readonly = "readonly";
                                    }
                                    else
                                    {
                                        $offer_percentage_readonly = "";
                                    }
                                }
                                else
                                {
                                    $offer_percentage = isset($table_data[0]["offer_percentage"]) ? $table_data[0]["offer_percentage"] : NULL;
                                    $offer_percentage_readonly = "";
                                }
                            ?>
                            <?php
                                $getDiscount = $this->admin_model->getDiscount();
                            ?>
                            <select name="discount" id="discount" onchange="discountChange(this);" class="form-control">
                                <option value="">- Select -</option>
                                <?php 
                                    foreach($getDiscount as $discount)
                                    {
                                        $selected="";
                                        if( isset($table_data[0]["offer_percentage"]) && $table_data[0]["offer_percentage"] == $discount["discount_value"] )
                                        {
                                            $selected="selected='selected'";
                                        }
                                        ?>
                                        <option value="<?php echo $discount["discount_value"];?>" <?php echo $selected;?>><?php echo $discount["discount_value"];?></option>
                                        <?php 
                                    } 
                                ?>
                            </select>
                            
                            <!--<input type="text" name="discount" id="discount" <?php echo  $offer_percentage_readonly;?> class="form-control mobile_vali" max="100" onchange="discountChange(this);" autocomplete="off" value="<?php echo isset($table_data[0]["offer_percentage"]) ? $table_data[0]["offer_percentage"] : NULL;?>" placeholder="Enter Percentage">
                            -->
                        </div>  
                    </div>   

                    <span class="inclusive-tax float-right text-right">Inclusive Tax @ <?php echo $tax_value;?> %</span>
                </div>

                <?php 
                    if(isset ($order_type) && $order_type == 'dine_in')
                    {
                        $ButtonType  ='style="display:block;"';
                        $ButtonType1  ='style="display:none;"';
                    } 
                    else
                    {
                        $ButtonType  ='style="display:none;"';
                        $ButtonType1 ='style="display:block;"';
                    }
                ?>

                <div class="col-md-4">
                    <div class="text-right total-section">
                        <span class="total_label text-right">Total</span>
                        <input type="hidden" class="total_order_amount" id="total_order_amount" value="<?php echo isset($totalLineTotal) ? number_format($totalLineTotal,DECIMAL_VALUE,'.','') : '0.00';?>">
                        <input type="hidden" class="payable_amount" id="payable_amount" value="<?php echo isset($totalLineTotal) ? number_format($totalLineTotal,DECIMAL_VALUE,'.','') : '0.00';?>">
                        <span class="total_amount text-right" id="total_order_amount_text"><?php echo isset($totalLineTotal) ? number_format($totalLineTotal,DECIMAL_VALUE,'.','') : '0.00';?></span>
                    </div> 

                    <?php 
                        if(isset($table_id) && !empty($table_id))
                        {
                            $orderQry = "select 
                                header_tbl.interface_header_id,
                                sum(line_tbl.price * line_tbl.quantity) as bill_amount,

                                round( sum(( coalesce(line_tbl.offer_percentage,0) / 100) * (line_tbl.quantity * line_tbl.price)),2) as offer_amount,
                                round( sum((line_tbl.quantity * line_tbl.price) - (( coalesce(line_tbl.offer_percentage,0) / 100) * (line_tbl.quantity * line_tbl.price))),2) as linetotal,
                                round( sum( ((line_tbl.quantity * line_tbl.price) - (( coalesce(line_tbl.offer_percentage,0) / 100) * (line_tbl.quantity * line_tbl.price))) * (coalesce(tax_percentage,0)/100)),2) as tax_value
                                
                                from ord_order_interface_headers as header_tbl
                                
                                left join ord_order_interface_lines as line_tbl on line_tbl.reference_header_id = header_tbl.interface_header_id

                                where 1=1
                                and header_tbl.order_source = 'DINE_IN'
                                and header_tbl.interface_status = '".$interface_status."'
                                and header_tbl.table_id = '".$table_id."'
                                and header_tbl.interface_header_id = '".$interface_header_id."'
                                group by line_tbl.reference_header_id
                            "; 

                            $getDiningOrders = $this->db->query($orderQry)->result_array();


                            $linetotal = isset($getDiningOrders[0]["linetotal"]) ? $getDiningOrders[0]["linetotal"] : "0.00";
                            $order_tax_value = isset($getDiningOrders[0]["tax_value"]) ? $getDiningOrders[0]["tax_value"] : "0.00";
                             
                            $totalOrderAmount = number_format($linetotal + $order_tax_value,DECIMAL_VALUE,'.','');

                            ?>
                            <div class="dine_in_buttons">
                                <input type="hidden" name="dine_in_interface_header_id" id="interface_header_id" value="<?php echo $interface_header_id;?>">
                                
                                <?php 
                                    if($interface_status != NULL && $interface_status != "Printed")
                                    {
                                        ?>
                                        &nbsp;&nbsp;
                                        <a href="javascript:void(0)" class="dining-proceedto-pay btn-disabled disabled-btn" title="Pay">
                                            <b>Pay <?php echo CURRENCY_SYMBOL;?> <span class="payable_new"><?php echo $totalOrderAmount;?></span></b>
                                        </a> 
                                        <a href="javascript:void(0)" onclick="savePosOrder('order_interface_tbl','SAVE_PRINT');" class="dining-proceedto-pay" title="Save & Print">
                                            Save & Print
                                        </a> &nbsp;&nbsp;

                                        <a href="javascript:void(0)" onclick="savePosOrder('order_interface_tbl','SAVE');" class="dining-proceedto-pay" title="Save">
                                            Save
                                        </a>
                                        <?php
                                    }
                                    else
                                    {
                                        ?>

                                        <div class="dining_btn" style="display:none;">
                                            <a href="javascript:void(0)" class="dining-proceedto-pay btn-disabled disabled-btn" title="Pay">
                                                <b>Pay <?php echo CURRENCY_SYMBOL;?> <span class="payable_new"><?php echo $totalOrderAmount;?></span></b>
                                            </a> 

                                            <a href="javascript:void(0)" onclick="savePosOrder('order_interface_tbl','SAVE_PRINT');" class="dining-proceedto-pay" title="Save & Print">
                                                Save & Print
                                            </a> &nbsp;&nbsp;

                                            <a href="javascript:void(0)" onclick="savePosOrder('order_interface_tbl','SAVE');" class="dining-proceedto-pay" title="Save">
                                                Save
                                            </a>
                                        </div>


                                        <div class="pay_btn">
                                           <?php /*<span class="inclusive-tax">Inclusive Tax @ <?php echo $tax_value;?> %</span> */ ?>
                                           
                                            <a href="javascript:void(0)" class="dining-proceedto-pay" title="Pay" onclick="PayDineInOrder();">
                                                <b>Pay <?php echo CURRENCY_SYMBOL;?> <span class="payable_new"><?php echo $totalOrderAmount;?></span></b>
                                            </a> 

                                            <?php if( $interface_status == "Printed"){?>
                                                <a href="javascript:void(0)" onclick="updateModifyStatus('<?php echo $interface_header_id;?>','<?php echo $table_id;?>')" class="dining-proceedto-pay btn-modify">Modify</a>
                                            <?php } ?>

                                            <script>
                                                function updateModifyStatus(header_id,table_id) 
                                                {
                                                     Swal.fire({
                                                        title: 'Are you sure?',
                                                        text: "Do you want to modify?",
                                                        icon: 'warning',
                                                        showCancelButton: true,
                                                        confirmButtonColor: '#070d7d',
                                                        cancelButtonColor: '#d33',
                                                        confirmButtonText: 'Yes'
                                                    }).then((result) => 
                                                    {
                                                        if (result.isConfirmed) 
                                                        {
                                                            window.location = '<?php echo base_url()?>pos/updateModifyStatus/'+header_id+'/'+table_id;
                                                        }
                                                    });
                                                }
                                            </script>
                                            
                                        </div>
                                        <?php
                                    } 
                                ?>
                            </div>

                            <div class="pos_buttons" style="display:none;">
                                <span class="inclusive-tax">Inclusive Tax @ <?php echo $tax_value;?> %</span>
                                <a href="javascript:void(0)" onclick="savePosOrder('order_interface_tbl','SAVE');" class="proceedto-pay btn-disabled disabled-btn" title="Proceed to Pay" --data-toggle="modal" --data-target="#print_order">
                                    <b>Proceed to Pay <?php echo CURRENCY_SYMBOL;?> <span class="payable_new">0.00</span></b>
                                </a>
                            </div>
                           <?php
                        }
                        else
                        { 
                           $orderQry = "select 
                            header_tbl.interface_header_id,
                            sum(line_tbl.price * line_tbl.quantity) as bill_amount,

                            round( sum(( coalesce(line_tbl.offer_percentage,0) / 100) * (line_tbl.quantity * line_tbl.price)),2) as offer_amount,
                            round( sum((line_tbl.quantity * line_tbl.price) - (( coalesce(line_tbl.offer_percentage,0) / 100) * (line_tbl.quantity * line_tbl.price))),2) as linetotal,
                            round( sum( ((line_tbl.quantity * line_tbl.price) - (( coalesce(line_tbl.offer_percentage,0) / 100) * (line_tbl.quantity * line_tbl.price))) * (coalesce(tax_percentage,0)/100)),2) as tax_value
                            
                            from ord_order_interface_headers as header_tbl
                            
                            left join ord_order_interface_lines as line_tbl on line_tbl.reference_header_id = header_tbl.interface_header_id

                            where 1=1
                           
                            and header_tbl.interface_header_id = '".$interface_header_id."'
                            group by line_tbl.reference_header_id
                            "; 

                            $getDiningOrders = $this->db->query($orderQry)->result_array();


                            $linetotal = isset($getDiningOrders[0]["linetotal"]) ? $getDiningOrders[0]["linetotal"] : "0.00";
                            $order_tax_value = isset($getDiningOrders[0]["tax_value"]) ? $getDiningOrders[0]["tax_value"] : "0.00";
                            
                            $totalOrderAmount = number_format($linetotal + $order_tax_value,DECIMAL_VALUE,'.','');

                            if( (isset($order_type) && ($order_type == 'takeaway' || $order_type == 'home_delivery') ) 
                            && (isset($interface_status) && $interface_status == 'Created') )
                            {
                                ?>
                               <a href="javascript:void(0)" <?php echo $ButtonType1; ?> onclick="savePosOrder('order_interface_tbl','SAVE');" class="proceedto-pay btn-disabled disabled-btn" title="Proceed to Pay" --data-toggle="modal" --data-target="#print_order">
                                    <b>Pay <?php echo CURRENCY_SYMBOL;?> <span class="payable_new"><?php echo isset($totalLineTotal) ? number_format($totalOrderAmount,DECIMAL_VALUE,'.','') : '0.00';?></span></b>
                                </a>

                                <a href="javascript:void(0)" onclick="savePosOrder('order_interface_tbl','SAVE_PRINT');" class="dining-proceedto-pay" title=" Save & Print">
                                    Save & Print
                                </a> &nbsp;&nbsp;

                                <a href="javascript:void(0)" onclick="savePosOrder('order_interface_tbl','SAVE');" class="dining-proceedto-pay" title="Save">
                                    Save
                                </a>
                                <?php
                            }
                            else if( (isset($order_type) && ($order_type == 'takeaway' || $order_type == 'home_delivery')) 
                            && (isset($interface_status) && $interface_status == 'Printed') )
                            {
                                ?>
                                 <a href="javascript:void(0)" <?php echo $ButtonType1; ?> onclick="PayDineInOrder();" class="proceedto-pay" title="Pay">
                                    <b>Pay <?php echo CURRENCY_SYMBOL;?> <span class="payable_new"><?php echo isset($totalOrderAmount) ? number_format($totalOrderAmount,DECIMAL_VALUE,'.','') : '0.00';?></span></b>
                                </a>

                                <?php if( $interface_status == "Printed"){?>
                                    <a href="javascript:void(0)" onclick="updatePOSModifyStatus('<?php echo $interface_header_id;?>')" class="dining-proceedto-pay btn-modify">Modify</a>
                                <?php } ?>

                                <script>
                                    function updatePOSModifyStatus(header_id) 
                                    {
                                            Swal.fire({
                                            title: 'Are you sure?',
                                            text: "Do you want to modify?",
                                            icon: 'warning',
                                            showCancelButton: true,
                                            confirmButtonColor: '#070d7d',
                                            cancelButtonColor: '#d33',
                                            confirmButtonText: 'Yes'
                                        }).then((result) => 
                                        {
                                            if (result.isConfirmed) 
                                            {
                                                window.location = '<?php echo base_url()?>pos/updatePOSModifyStatus/'+header_id;
                                            }
                                        });
                                    }
                                </script>
                                <?php
                            }
                            else if(isset ($order_type) && ($order_type == 'takeaway' || $order_type == 'home_delivery'))
                            {
                                ?>
                                <a href="javascript:void(0)" <?php echo $ButtonType1; ?> --onclick="savePosOrder('order_interface_tbl','SAVE');" class="proceedto-pay btn-disabled disabled-btn" title="Proceed to Pay" --data-toggle="modal" --data-target="#print_order">
                                    <b>Pay <?php echo CURRENCY_SYMBOL;?> <span class="payable_new">0.00</span></b>
                                </a>

                                <a href="javascript:void(0)" onclick="savePosOrder('order_interface_tbl','SAVE_PRINT');" class="dining-proceedto-pay btn-disabled disabled-btn" title=" Save & Print">
                                    Save & Print
                                </a> &nbsp;&nbsp;

                                <a href="javascript:void(0)" onclick="savePosOrder('order_interface_tbl','SAVE');" class="dining-proceedto-pay btn-disabled disabled-btn" title="Save">
                                    Save
                                </a>
                                <?php
                            }
                        }
                    ?>

                    <div class="dine_in_buttons" <?php echo $ButtonType;?>>
                       <!--  <input type="hidden" name="dine_in_interface_header_id" id="interface_header_id" value="<?php echo isset($interface_header_id) ? $interface_header_id : 0;?>">
                        -->
                        <div class="dining_btn" --style="display:none;">
                            <a href="javascript:void(0)" class="dining-proceedto-pay btn-disabled disabled-btn" title="Pay">
                                <b>Pay <?php echo CURRENCY_SYMBOL;?> <span class="payable_new"><?php echo isset($totalOrderAmount) ? $totalOrderAmount : '0.00';?></span></b>
                            </a> 
                            &nbsp;&nbsp;
                            <a href="javascript:void(0)" onclick="savePosOrder('order_interface_tbl','SAVE_PRINT');" class="dining-proceedto-pay btn-disabled disabled-btn" title=" Save & Print">
                                Save & Print
                            </a> &nbsp;&nbsp;
                            <a href="javascript:void(0)" onclick="savePosOrder('order_interface_tbl','SAVE');" class="dining-proceedto-pay btn-disabled disabled-btn" title="Save">
                                Save
                            </a>
                        </div>
                    </div>
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
                        $interfaceHeaderId = isset($interface_header_id) ? $interface_header_id : NULL;

                        $billDetailsQry = "select * from ord_order_interface_headers where interface_header_id='".$interfaceHeaderId."' ";
                        $getBillDetails = $this->db->query($billDetailsQry)->result_array();
                        $bill_number = isset($getBillDetails[0]["order_number"]) ? $getBillDetails[0]["order_number"] : NULL;
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
                                    <input type="text" name="bill_number" id="bill_number" readonly class="form-control" value="<?php echo $bill_number;?>" placeholder="">
                                    <input type="hidden" name="interface_header_id" value="<?php echo $interfaceHeaderId;?>" id="interface_header_id">
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
                                <div class="col-md-5 text-right">customer
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
                                    <input type="text" name="mobile_number" id="mobile_number" minlength="10" maxlength="10" autocomplete="off" class="form-control mobile_vali" value="<?php echo isset($mobile_number) ? $mobile_number : NULL;?>" placeholder="9999999999">
                                    <input type="hidden" name="customer_id" id="customer_id" value="0<?php echo isset($customer_id) ? $customer_id : 0;?>">
                                    <div id="customerList"></div>
								</div>
                            </div>
                            <div class="row">
                                <div class="col-md-5 text-right">
                                    <label class="col-form-label">Customer Name</label>
                                </div>
                                <div class="form-group col-md-7">
                                    <input type="text" name="customer_name" id="customer_name" autocomplete="off" class="form-control" value="<?php echo isset($customer_name) ? $customer_name : NULL;?>" placeholder="">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-5 text-right">
                                    <label class="col-form-label">Customer Address</label>
                                </div>
                                <div class="form-group col-md-7">
                                    <textarea name="customer_address" id="customer_address" autocomplete="off" rows="1" class="form-control" placeholder=""><?php echo isset($address1) ? $address1 : NULL;?></textarea>
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
                                where 1=1 
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

                    <div id="myDiv">
                        <img id="loading-image" --width="130" --height="100" src="<?php echo base_url();?>uploads/loading.gif" style="display:none;"/>
                    </div>
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
                                        <a href="javascript:void(0)" onclick="savePosOrder('order_base_tbl');" class="btn btn-primary print_button btn-block btn-text" title="Print">
                                            <i class="fa fa-check" aria-hidden="true"></i>  Confirm
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

<!-- Customer Modal Start -->
<form action="" id="customer_details" method="post">
    <div class="modal fade MyPopup" id="add_customer" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        
        <div class="modal-dialog" style="max-width: 50%;" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title" id="exampleModalLabel"><b>Customer</b></h3>
                    <button type="button" class="close close-btn" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                
                <div class="modal-body">
                    <div class="row">
                        <!-- right div start here -->
                        <div class="col-md-8">
                            <div class="row">
                                <div class="col-md-5 text-right">
                                    <label class="col-form-label add_mobile_number"><span class="text-danger">*</span> Mobile No</label>
                                </div>
                                <div class="form-group col-md-6">
                                    <input type="text" name="add_mobile_number" required id="add_mobile_number" minlength="10" maxlength="10" autocomplete="off" class="form-control mobile_vali" value="<?php echo isset($mobile_number) ? $mobile_number : NULL;?>" placeholder="9999999999">
                                    <input type="hidden" name="add_customer_id" id="add_customer_id" value="<?php echo isset($customer_id) ? $customer_id : NULL;?>">
                                    <span class="error_mobile_number" style="color:red;"></span>
                                    <div id="addCustomerList"></div>
								</div>
                            </div>
                            <div class="row">
                                <div class="col-md-5 text-right">
                                    <label class="col-form-label add_customer_name"><span class="text-danger start_customer_name" style='display:none;'>*</span> Customer Name</label>
                                </div>
                                <div class="form-group col-md-6">
                                    <input type="text" name="add_customer_name" id="add_customer_name" autocomplete="off" class="form-control" value="<?php echo isset($customer_name) ? $customer_name : NULL;?>" placeholder="">
                                    <span class="error_add_customer_name" style="color:red;"></span>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-5 text-right">
                                    <label class="col-form-label add_customer_address"><span class="text-danger start_customer_address" style='display:none;'>*</span> Customer Address</label>
                                </div>
                                <div class="form-group col-md-7">
                                    <textarea name="add_customer_address" id="add_customer_address" autocomplete="off" rows="1" class="form-control" placeholder=""><?php echo isset($address1) ? $address1 : NULL;?></textarea>
                                    <span class="error_add_customer_address" style="color:red;"></span>
                                </div>
                            </div>
                            <script>  
                                $(document).ready(function()
                                {  
                                    $('#add_mobile_number').keyup(function()
                                    {  
                                        var mobile_number = $("#add_mobile_number").val();
                                        var length_mobile_number = $("#add_mobile_number").val().length;
                                       
                                        if(mobile_number != '' ) //&& length_mobile_number == 10 
                                        {  
                                            $(".error_mobile_number").html("");
                                            $("#add_customer_name").val("");
                                            $("#add_customer_address").val("");
                                            
                                            $.ajax({
                                                url:"<?php echo base_url();?>pos/ajaxSearchPOSDineInCustomers",  
                                                method : "POST",  
                                                data:{
                                                    mobile_number:mobile_number
                                                },  
                                                success:function(data)  
                                                {  
                                                   if(data == "no_data")
                                                    {
                                                        $('.list-unstyled-new').hide();
                                                        getNewConsumerDetails(0,'',''); 
                                                    }
                                                    else
                                                    {
                                                        $('#addCustomerList').fadeIn();  
                                                       $('#addCustomerList').html(data);
                                                    }
                                                }  
                                            });  
                                        }
                                        else
                                        {
                                            $("#add_customer_name").val("");
                                            $("#add_customer_address").val("");

                                            $(".error_mobile_number").html("Please enter valid mobile number!");
                                            $('.list-unstyled-new').hide();
                                            getNewConsumerDetails(0,'',''); 
                                        }  
                                    });

                                    $(document).on('click', 'ul.list-unstyled-new li', function()
                                    {  
                                        var value = $(this).text();
                                        
                                        if(value === "Sorry! Customer Not Found.")
                                        {
                                            $('#add_mobile_number').val("");  
                                            $('#addCustomerList').fadeOut();
                                        }
                                        else
                                        {
                                            $('#add_mobile_number').val(value);  
                                            $('#addCustomerList').fadeOut();  
                                        }
                                    });
                                });

                                function getNewConsumerDetails(customer_id,mobile_number,customer_name,address)
                                {
                                    if(customer_id > 0)
                                    {
                                        $('#add_customer_id').val(customer_id);
                                        $('#add_mobile_number').val(mobile_number);
                                        $('#add_customer_name').val(customer_name);
                                        $('#add_customer_address').val(address);

                                        $("#add_customer_name").removeAttr("required", true);
                                        $("#add_customer_address").removeAttr("required", true);

                                        $(".start_customer_name").hide();
                                        $(".start_customer_address").hide();

                                        $(".error_add_customer_name").html("");

                                        $(".error_add_customer_address").html("");

                                        if(mobile_number)
                                        {
                                            $(".add_mobile_mumber").removeClass('errorClass');
                                        }

                                        if(customer_name)
                                        {
                                            $('#add_customer_name').attr("readonly",true);
                                            $(".add_customer_name").removeClass('errorClass');
                                        }

                                        if(address)
                                        {
                                            $('#add_customer_address').attr("readonly",true);
                                            $(".add_customer_address").removeClass('errorClass');
                                        }
                                    }
                                    else
                                    {
                                        $('#add_customer_id').val('0');
                                        //$('#add_customer_name').val('');
                                       // $('#add_customer_address').val('');


                                        $('#add_customer_name').removeAttr("readonly",true);
                                        $('#add_customer_address').removeAttr("readonly",true); 

                                        $("#add_customer_name").attr("required", true);
                                        $("#add_customer_address").attr("required", true);

                                        $(".start_customer_name").show();
                                        $(".start_customer_address").show();

                                        if(mobile_number)
                                        {
                                            $(".add_mobile_mumber").removeClass('errorClass');
                                        }

                                        if(customer_name)
                                        {
                                            $(".add_customer_name").removeClass('errorClass');
                                        }

                                        if(address)
                                        {
                                            $(".add_customer_address").removeClass('errorClass');
                                        }
                                    }
                                }
                            </script> 
                        </div>
                        <!-- right div end here -->   
                    </div>

                    <div id="customerDiv">
                        <img id="customer-loading-image" src="<?php echo base_url();?>uploads/loading.gif" style="display:none;"/>
                    </div>

                </div>

                <div class="row modal-footer mb-3">
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-3">
                            </div>
                            <div class="col-md-4">
                                <div class="row">
                                    <div class="col-md-6 text-right">
                                        <!-- <button type="submit" class="btn btn-primary btn-block btn-text"><i class="fa fa-print" aria-hidden="true"></i> Print</button>
                                        -->
                                        <a href="javascript:void(0)" onclick="saveCustomer('add_customer');" class="btn btn-primary print_button btn-block btn-text" title="Print">
                                            Save
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
<!-- Customer Modal End -->


<!--Open Orders Modal Start -->
<form action="" id="customer_details" method="post">
    <div class="modal fade MyPopup" id="selectOpenOrders" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" style="max-width: 50%;" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title" id="exampleModalLabel"><b>Open Orders</b></h3>
                    <button type="button" class="close close-btn" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                
                <div class="modal-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th class="text-center">S.No</th>
                                <th scope="col">Customer Name</th>
                                <th scope="col">Mobile Number</th>
                                <th scope="col">Order Number</th>
                            </tr>
                        </thead>
                       
                        <tbody>
                            <?php 
                                if(count($posOrders)> 0)
                                {
                                    $j=1;
                                    foreach($posOrders as $openOrdersRow)
                                    {
                                        ?>
                                        <tr>
                                            <th class="text-center"><?php echo $j;?></th>
                                            <td><?php echo $openOrdersRow["customer_name"]; ?></td>
                                            <td><?php echo $openOrdersRow["mobile_number"]; ?></td>
                                            <td>
                                                <a href='<?php echo base_url();?>pos/posOrder/<?php echo $order_type;?>/<?php echo $openOrdersRow["interface_status"]; ?>/<?php echo $openOrdersRow["interface_header_id"]; ?>'>
                                                    <?php echo $openOrdersRow["order_number"]; ?>
                                                </a>
                                            </td>
                                        </tr>
                                        <?php 
                                        $j++;
                                    } 
                                }
                                else
                                {
                                    ?>
                                    <tr>
                                        <td colspan="4" class="text-center">
                                            <img src="<?php echo base_url();?>uploads/nodata.png">
                                        </td>
                                    </tr>
                                    <?php
                                }
                            ?>
                        </tbody>
                    </table>
                </div> 
            </div>
        </div>
    </div>
</form>
<!--Open Orders Modal end -->
    
<script>
    function selectOpenOrders()
    {
        $("#selectOpenOrders").modal();
    }

    function addCustomer()
    {
        $("#add_customer").modal();
    }

    function saveCustomer()
    {
        var add_mobile_number = $("#add_mobile_number").val();
        var add_customer_id = $("#add_customer_id").val();

        var add_customer_name = $("#add_customer_name").val();
        var add_customer_address = $("#add_customer_address").val();

        var mobileNumberCount = $("#add_mobile_number").val().length;

        if(add_mobile_number && add_customer_name && add_customer_address)
        {
            $(".error_add_customer_name").html("");
            $(".error_add_customer_address").html("");

            if(mobileNumberCount == 10)
            {
                if(add_mobile_number)
                {
                    $(".add_mobile_mumber").removeClass('errorClass');
                    $(".add_customer_name").removeClass('errorClass');
                    $(".add_customer_address").removeClass('errorClass');

                    $.ajax({
                        type: 'post',
                        url: '<?php echo base_url();?>pos/ajaxSaveCustomer',
                        data: $('form#customer_details').serialize(),
                        beforeSend: function() {
                            $("#customer-loading-image").show();
                        },
                        success: function (result) 
                        {
                        /*  $("#add_mobile_number").val("");
                            $("#add_customer_id").val("0");
                            $("#add_customer_name").val("");
                            $("#add_customer_address").val(""); */

                            var split_data = result.split("@");
                            var new_customer_id = split_data[0];
                            var new_customer_name = split_data[1];

                            $("#new_customer_id").val(new_customer_id);
                            $(".select_customer_name").html(new_customer_name);

                            $("#customer-loading-image").hide();
                            $('#add_customer').modal('toggle');

                            Swal.fire({
                                position: 'top',
                                //position: 'top-end',
                                icon: 'success',
                                title: 'Customer saved successfully!',
                                showConfirmButton: false,
                                timer: 1500,
                                width:'350px'
                            });
                        }
                    });
                }
                else
                {
                
                    if(add_mobile_number) {
                        $(".add_mobile_number").removeClass('errorClass');
                    } else{
                        $(".add_mobile_number").addClass('errorClass');
                    }

                    if(add_customer_id == 0)
                    {
                        if(add_customer_name) {
                            $(".add_customer_name").removeClass('errorClass');
                        } else{
                            $(".add_customer_name").addClass('errorClass');
                        }

                        if(add_customer_address) {
                            $(".add_customer_address").removeClass('errorClass');
                        } else{
                            $(".add_customer_address").addClass('errorClass');
                        }
                    }
                }
            }
            else
            {
                $(".list-unstyled-new").hide();
                $(".error_mobile_number").html("Please enter valid mobile number!");
            }
        }
        else
        {
            if(add_mobile_number == "")
            {
                $(".error_mobile_number").html("Please enter mobile number!");
            }else{
                $(".error_mobile_number").html("");
            }

            if(add_customer_name == "")
            {
                $(".error_add_customer_name").html("Please enter customer name!");
            }else{
                $(".error_add_customer_name").html("");
            }

            if(add_customer_address == "")
            {
                $(".error_add_customer_address").html("Please enter customer address!");
            }else{
                $(".error_add_customer_address").html("");
            }
        }
    }

    function ajaxCheckOpenOrders(table_id)
    {
        if(table_id !='')
        {
            $(".dining_btn").show();
            $(".pay_btn").hide();

            $.ajax({
                type: "POST",
                url:"<?php echo base_url().'dine_in/ajaxCheckOpenOrders';?>",
                data: { table_id: table_id }
            }).done(function( d )
            {   
                data = JSON.parse(d);
                var countKey = Object.keys(data['getOpenOrders']).length;
               
                if(countKey > 0)
                {
                    $.each(data['getOpenOrders'], function(i, openOrder) 
                    {
                        var interface_header_id = openOrder.interface_header_id;
                        var sub_table = openOrder.sub_table;
                        //var table_name = openOrder.table_name;
                        var table_code = openOrder.table_code;
                        
                        if(sub_table == null)
                        {
                            var sub_table_code = 'A';
                            var sub_table_name = table_code+sub_table_code;
                        }
                        else
                        {
                            var sub_table_code = data['sub_table_code'];
                            var sub_table_name = data['next_sub_table'];
                        }

                        $(".sub_table").val(sub_table_code);
                        $(".table_code").text(sub_table_name);
                        $(".remove_items").remove();
                        $(".select_customer_name").html("Select Customer");

                        $("#add_mobile_number").val("");
                        $("#add_customer_id").val("0");
                        $("#add_customer_name").val("");
                        $("#add_customer_address").val("");
                        $(".select_new_customer_name").hide();
                        $(".select_customer_name").show();

                        $("#discount").val("");
                        $("#discount").removeAttr("readonly",true);
                        
                        calculateGrandTotal();
                    });
                }
            });
        }   
    }
</script>

<script>
    /** Order Interface Tbl**/
    function savePosOrder(type,button_type)
    {
        if( type == "order_interface_tbl" )
        {
            var lineTotalCount = $("table.line_items > tbody  > tr").length;

            if(lineTotalCount == 0)
            {
                Swal.fire({
                    icon: 'error',
                    //title: 'Amount Mismatch...',
                    text: 'Atleast one item is required!',
                    //footer: '<a href="">Why do I have this issue?</a>'
                })
                return false;
            }
            else
            {
                $.ajax({
                    type: 'post',
                    url: '<?php echo base_url();?>pos/insertPosOrderItems/'+type+'/'+button_type,
                    data: $('form#order_items,form#footer_content,form#popup_content').serialize(),
                    beforeSend: function() {
                        swal.fire({
                            html: '<h5>Loading...</h5>',
                            showConfirmButton: false,
                            onRender: function() {
                                // there will only ever be one sweet alert open.
                                $('.swal2-content').prepend(sweet_loader);
                            }
                        });
                    },
                    success: function (d) 
                    {
                        if(d == 1)
                        {
                            window.location = '<?php echo base_url();?>pos/posOrder/dine_in'; 

                            /* if(button_type == 'SAVE_PRINT')
                            {
                                window.location = '<?php #echo base_url();?>dine_in/dineInOrder/<?php #echo $table_id;?>/Printed/<?php #echo $interface_header_id?>';
                            }  */ 
                        }
                        else
                        {
                            data = JSON.parse(d);
                            var documentNumber = data['pos_items']["documentNumber"];
                            var interface_header_id = data['pos_items']["interface_header_id"];

                            $('#bill_number').val(documentNumber);
                            $('#interface_header_id').val(interface_header_id);

                           var login_user_id = '<?php echo $this->user_id;?>';
                           var autoPrintStatus = '<?php echo AUTO_PRINT_STATUS;?>';

                            if( autoPrintStatus == 'Y' && login_user_id != 1 ) //Not Admin
                            {
                                if(button_type == "SAVE")
                                {
                                    generateKOTPDF(button_type,interface_header_id);
                                } 
                                else if(button_type == "SAVE_PRINT")
                                { 
                                    generateOrdersPDF(button_type,interface_header_id);
                                    generateKOTPDF(button_type,interface_header_id);    
                                }
                            }
                            else if(login_user_id == 1) //Admin
                            {
                                if(button_type == "SAVE")
                                {
                                    generateKOTPDF(button_type,interface_header_id);
                                } 
                                else if(button_type == "SAVE_PRINT")
                                { 
                                    generateOrdersPDF(button_type,interface_header_id);
                                    generateKOTPDF(button_type,interface_header_id);         
                                }
                            }
                            else
                            {
                                var checkedVal = $('input:radio[name=pos_dine_in_type]:checked').val();

                                if(checkedVal == "DINE_IN")
                                {
                                    // if(button_type == 'SAVE')
                                    // {
                                    //     window.location = '<?php #echo base_url();?>dine_in/dineInOrder/<?php #echo $table_id;?>/Created/'+interface_header_id;
                                    // }
                                    // else if(button_type == 'SAVE_PRINT')
                                    // {
                                    //     window.location = '<?php #echo base_url();?>dine_in/dineInOrder/<?php #echo $table_id;?>/Printed/'+interface_header_id;
                                    // }
                                    
                                    window.location = '<?php echo base_url();?>pos/posOrder/dine_in';
                                    
                                    $(".remove_items").remove();
                                    
                                    $('#total_order_amount').val('0.00');
                                    $('#total_order_amount_text').html('0.00');

                                    $('#payable_amount').val('0.00');
                                    $('#payable_amount_text').html('0.00');
                                    $('.payable_new').html('0.00');

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
                                }
                                else if(checkedVal == "TAKEAWAY" || checkedVal == "HOME_DELIVERY")
                                {
                                    // if(button_type == 'SAVE')
                                    // {
                                    //     window.location = '<?php #echo base_url();?>pos/posOrder/takeaway/Created/'+interface_header_id;
                                    // }
                                    // else if(button_type == 'SAVE_PRINT')
                                    // {
                                    //     window.location = '<?php #echo base_url();?>pos/posOrder/takeaway/Printed/'+interface_header_id;
                                    //     //window.location = '<?php #echo base_url();?>dine_in/dineInOrder/<?php #echo $table_id;?>/Printed/'+interface_header_id;
                                    // }
                                    
                                    if(checkedVal == "TAKEAWAY")
                                    {
                                        window.location = '<?php echo base_url();?>pos/posOrder/takeaway';
                                    }
                                    else if(checkedVal == "HOME_DELIVERY")
                                    {
                                        window.location = '<?php echo base_url();?>pos/posOrder/home_delivery';
                                    }


                                    Swal.fire({
                                        position: 'top',
                                        //position: 'top-end',
                                        icon: 'success',
                                        title: 'POS Saved successfully!',
                                        showConfirmButton: false,
                                        timer: 500,
                                        width:'350px'
                                    });

                                    //$('#print_order').modal();
                                }
                            }
                        }
                    }
                });
            }
        }
        else if( type == "order_base_tbl" )
        {
          /*   var payment_method = $("#payment_method").val();
            alert(payment_method); */

            var payment_method = $("input[type='radio'][name='payment_method']:checked").val();

            if(payment_method != undefined )
            {
                $(".print_button").addClass("print_button_disabled");
                $(".print_button").attr("disabled", true);
                
                $.ajax({
                    type: 'post',
                    url: '<?php echo base_url();?>pos/insertPosOrderItems/'+type,
                    data: $('form#order_items,form#footer_content,form#popup_content').serialize(),
                    beforeSend: function() {
                        $("#loading-image").show();
                    },
                    success: function (d) 
                    {
                        $("#loading-image").hide();
                        data = JSON.parse(d);
                        var status = data['pos_items']["status"];
                        var message = data['pos_items']["message"];
                        var pos_dine_in_type = data['pos_items']["pos_dine_in_type"];

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
                       
                        if(pos_dine_in_type == 'DINE_IN')
                        {
                            window.location = '<?php echo base_url();?>pos/posOrder/dine_in';
                        }
                        else if(pos_dine_in_type == 'TAKEAWAY')
                        {
                            window.location = '<?php echo base_url();?>pos/posOrder/takeaway';
                        }
                        else if(pos_dine_in_type == 'HOME_DELIVERY')
                        {
                            window.location = '<?php echo base_url();?>pos/posOrder/home_delivery';
                        }
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
            } 
        }
    }

    function generateOrdersPDF(button_type,interface_header_id)
    {
        if(interface_header_id)
        {
            $.ajax({
                type: 'post',
                url: '<?php echo base_url();?>pos/generateOpenOrdersPDF/'+button_type+'/'+interface_header_id,
                data: {button_type:button_type,interface_header_id:interface_header_id},
                success: function (result) 
                {
                    printOrderPDF(button_type,interface_header_id);
                }
            });
        }
    }

    function generateKOTPDF(button_type,interface_header_id)
    {
        if(interface_header_id)
        {
            $.ajax({
                type: 'post',
                url: '<?php echo base_url();?>pos/generateKOTPDF/'+button_type+'/'+interface_header_id,
                data: {button_type:button_type,interface_header_id:interface_header_id},
                success: function (result) 
                {
                    printKOTPDF(button_type,interface_header_id);   
                }
            });
        }
    }

    function printOrderPDF(button_type,interface_header_id)
    {
        var orderID = interface_header_id;

        if(orderID > 0 && orderID !="")
        {
            $.ajax({
                url      : '<?php echo base_url(); ?>billGenrator/chkbill/'+orderID,
                type     : "POST",
                data     : {},
                datatype : JSON,
                success  : function(d)
                {
                    response = JSON.parse(d);

                    var htmlCashierContent = response["orderPDFPath"];
                    var htmlKOTContent = response["orderKOTPath"];
                    var print_items = response["print_items"];

                    var countKey = Object.keys(print_items).length;
                    
                    if( countKey > 0 )
                    {
                        $.each(print_items, function(i, item) 
                        {
                            var print_type = item.print_type; // #Cashier #KOT

                            var printer_name = item.printer_name;
                            var printer_count = item.printer_count;

                            if( printer_name !="" )
                            {
                                /* if( button_type == "SAVE_PRINT" )
                                {
                                    if(print_type == "CASHIER")
                                    {
                                        for(i=1; i<=printer_count; i++)
                                        {
                                            orderAutoPrint(jspmWSStatus(),htmlCashierContent,orderID,printer_name,button_type);
                                        }
                                    }

                                    if(print_type == "KOT")
                                    {
                                        for(i=1; i<=printer_count; i++)
                                        {
                                            kotAutoPrint(jspmWSStatus(),htmlCashierContent,orderID,printer_name,button_type);
                                        }
                                    }    
                                }
                                else if( print_type == "KOT" && button_type == "SAVE" )
                                {
                                    for(i=1; i<=printer_count; i++)
                                    {
                                        kotAutoPrint(jspmWSStatus(),htmlKOTContent,orderID,printer_name,button_type);
                                    }
                                } */

                                if(print_type == "CASHIER")
                                {
                                    for(i=1; i<=printer_count; i++)
                                    {
                                        orderAutoPrint(jspmWSStatus(),htmlCashierContent,orderID,printer_name,button_type);
                                    }
                                } 
                            }
                        });
                    }
                   // updateKOTOrderStatus(orderID,button_type);	
                }
            });   
        }
    }

    function printKOTPDF(button_type,interface_header_id)
    {
        var orderID = interface_header_id;

        if(orderID > 0 && orderID !="")
        {
            $.ajax({
                url      : '<?php echo base_url(); ?>billGenrator/chkbill/'+orderID,
                type     : "POST",
                data     : {},
                datatype : JSON,
                success  : function(d)
                {
                    response = JSON.parse(d);

                    var htmlCashierContent = response["orderPDFPath"];
                    var htmlKOTContent = response["orderKOTPath"];
                    var print_items = response["print_items"];

                    var countKey = Object.keys(print_items).length;
                    
                    if( countKey > 0 )
                    {
                        $.each(print_items, function(i, item) 
                        {
                            var print_type = item.print_type; // #Cashier #KOT

                            var printer_name = item.printer_name;
                            var printer_count = item.printer_count;

                            if( printer_name !="" )
                            {
                                /* if( button_type == "SAVE_PRINT" )
                                {
                                    if(print_type == "CASHIER")
                                    {
                                        for(i=1; i<=printer_count; i++)
                                        {
                                            orderAutoPrint(jspmWSStatus(),htmlCashierContent,orderID,printer_name,button_type);
                                        }
                                    }

                                    if(print_type == "KOT")
                                    {
                                        for(i=1; i<=printer_count; i++)
                                        {
                                            kotAutoPrint(jspmWSStatus(),htmlCashierContent,orderID,printer_name,button_type);
                                        }
                                    }    
                                }
                                else if( print_type == "KOT" && button_type == "SAVE" )
                                {
                                    for(i=1; i<=printer_count; i++)
                                    {
                                        kotAutoPrint(jspmWSStatus(),htmlKOTContent,orderID,printer_name,button_type);
                                    }
                                } */

                                if( print_type == "KOT")
                                {
                                    for(i=1; i<=printer_count; i++)
                                    {
                                        kotAutoPrint(jspmWSStatus(),htmlKOTContent,orderID,printer_name,button_type);
                                    }
                                }
                            }
                        });
                    }
                    updateKOTOrderStatus(orderID,button_type);	
                }
            });   
        }
    }

    function orderAutoPrint(printerStatus,htmlContent,orderID,printer_name,button_type)
    {
        if (printerStatus && htmlContent !="") 
        {
            //Create a ClientPrintJob
            var cpj = new JSPM.ClientPrintJob();

            //Set Printer info
            //var myPrinter = new JSPM.InstalledPrinter($('#lstPrinters').val());
            //myPrinter.paperName = $('#lstPrinterPapers').val();
            //myPrinter.trayName = $('#lstPrinterTrays').val();
            //cpj.clientPrinter = myPrinter;
            
            //Cashier Printer
            //var printerPort = <?php #echo PRINTER_PORT;?>;
            //var printerName = '<?php #echo PRINTER_NAME;?>';

            //var printerPort = printer_ip;
            var printerName = printer_name;
            
            //alert(printerName);
            
            //var myPrinter = new JSPM.InstalledPrinter(printerPort,printerName); //9100 ,"192.168.1.215"
            var myPrinter = new JSPM.InstalledPrinter(printerName); //printer name
            
            //var myPrinter = new JSPM.DefaultPrinter(); //9100 ,"192.168.1.215"
            
            cpj.clientPrinter = myPrinter;
            
            //Set PDF file
            var orderPDFPath = htmlContent;
            var currenttime = '<?php echo rand();?>';
            var my_file = new JSPM.PrintFilePDF(orderPDFPath, JSPM.FileSourceType.URL, 'MyFile_'+currenttime+'.pdf', 1);
            
            //var my_file = new JSPM.PrintFile('<?php echo base_url();?>uploads/generate_pdf/251.jpg', JSPM.FileSourceType.URL, 'MyFile.jpg', 1);

            //var my_file = new JSPM.PrintFilePDF($('#txtPdfFile').val(), JSPM.FileSourceType.URL, 'MyFile.pdf', 1);
            //my_file.printRotation = JSPM.PrintRotation[$('#lstPrintRotation').val()];
            //my_file.printRange = $('#txtPagesRange').val();
            //my_file.printAnnotations = $('#chkPrintAnnotations').prop('checked');
            //my_file.printAsGrayscale = $('#chkPrintAsGrayscale').prop('checked');
            //my_file.printInReverseOrder = $('#chkPrintInReverseOrder').prop('checked');

            cpj.files.push(my_file);
            
            //Send print job to printer!
            cpj.sendToClient();
            //updateOrderStatus(orderID);
        }
    }

    function kotAutoPrint(printerStatus,htmlContent,orderID,printer_name,button_type)
    {
        if (printerStatus && htmlContent !="") 
        {
            //Create a ClientPrintJob
            var cpj = new JSPM.ClientPrintJob();

            //Set Printer info
            //var myPrinter = new JSPM.InstalledPrinter($('#lstPrinters').val());
            //myPrinter.paperName = $('#lstPrinterPapers').val();
            //myPrinter.trayName = $('#lstPrinterTrays').val();
            //cpj.clientPrinter = myPrinter;
            
            //Cashier Printer
            //var printerPort = <?php #echo PRINTER_PORT;?>;
            //var printerName = '<?php #echo PRINTER_NAME;?>';

            //var printerPort = printer_ip;
            var printerName = printer_name;
            
            //alert(printerName);
            
            //var myPrinter = new JSPM.InstalledPrinter(printerPort,printerName); //9100 ,"192.168.1.215"
            var myPrinter = new JSPM.InstalledPrinter(printerName); //printer name
            
            //var myPrinter = new JSPM.DefaultPrinter(); //9100 ,"192.168.1.215"
            
            cpj.clientPrinter = myPrinter;
            
            //Set PDF file
            var orderPDFPath = htmlContent;
            var currenttime = '<?php echo rand();?>';
            var my_file = new JSPM.PrintFilePDF(orderPDFPath, JSPM.FileSourceType.URL, 'MyFile_'+currenttime+'.pdf', 1);
            
            //var my_file = new JSPM.PrintFile('<?php echo base_url();?>uploads/generate_pdf/251.jpg', JSPM.FileSourceType.URL, 'MyFile.jpg', 1);

            //var my_file = new JSPM.PrintFilePDF($('#txtPdfFile').val(), JSPM.FileSourceType.URL, 'MyFile.pdf', 1);
            //my_file.printRotation = JSPM.PrintRotation[$('#lstPrintRotation').val()];
            //my_file.printRange = $('#txtPagesRange').val();
            //my_file.printAnnotations = $('#chkPrintAnnotations').prop('checked');
            //my_file.printAsGrayscale = $('#chkPrintAsGrayscale').prop('checked');
            //my_file.printInReverseOrder = $('#chkPrintInReverseOrder').prop('checked');

            cpj.files.push(my_file);
            
            //Send print job to printer!
            cpj.sendToClient();
            //updateOrderStatus(orderID);
        }
    }
    
    //Update Printer Order Status
    function updateKOTOrderStatus(orderID,button_type)
    {
        if(orderID)
        {
            var interface_header_id = orderID;
            $.ajax({
                url      : '<?php echo base_url(); ?>billGenrator/updateKOTOrderStatus/'+orderID,
                type     : "POST",
                data     : {},
                datatype : JSON,
                success  : function(result)
                {
                    var checkedVal = $('input:radio[name=pos_dine_in_type]:checked').val();

                    if(checkedVal == "DINE_IN")
                    {
                        // if(button_type == 'SAVE')
                        // {
                        //     window.location = '<?php #echo base_url();?>dine_in/dineInOrder/<?php #echo $table_id;?>/Created/'+interface_header_id;
                        // }
                        // else if(button_type == 'SAVE_PRINT')
                        // {
                        //     window.location = '<?php #echo base_url();?>dine_in/dineInOrder/<?php #echo $table_id;?>/Printed/'+interface_header_id;
                        // }
                        
                        window.location = '<?php echo base_url();?>pos/posOrder/dine_in';
                        
                        $(".remove_items").remove();
                        
                        $('#total_order_amount').val('0.00');
                        $('#total_order_amount_text').html('0.00');

                        $('#payable_amount').val('0.00');
                        $('#payable_amount_text').html('0.00');
                        $('.payable_new').html('0.00');

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
                    }
                    else if(checkedVal == "TAKEAWAY" || checkedVal == "HOME_DELIVERY")
                    {
                        // if(button_type == 'SAVE')
                        // {
                        //     window.location = '<?php echo base_url();?>pos/posOrder/takeaway/Created/'+interface_header_id;
                        // }
                        // else if(button_type == 'SAVE_PRINT')
                        // {
                        //     window.location = '<?php echo base_url();?>pos/posOrder/takeaway/Printed/'+interface_header_id;
                        //     //window.location = '<?php #echo base_url();?>dine_in/dineInOrder/<?php #echo $table_id;?>/Printed/'+interface_header_id;
                        // }

                        if(checkedVal == "TAKEAWAY")
                        {
                            window.location = '<?php echo base_url();?>pos/posOrder/takeaway';
                        }
                        else if(checkedVal == "HOME_DELIVERY")
                        {
                            window.location = '<?php echo base_url();?>pos/posOrder/home_delivery';
                        }

                        Swal.fire({
                            position: 'top',
                            //position: 'top-end',
                            icon: 'success',
                            title: 'Order saved successfully!',
                            showConfirmButton: false,
                            timer: 500,
                            width:'350px'
                        });

                        //$('#print_order').modal();
                    }
                    //printer status updated success, printer_status=1    
                }
            });
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
    $(".pos-footer").on("input keyup change", 'select[name^="discount_type"],select[name^="discount"]', function (event) 
    {
        calculateGrandTotal();
    });
</script>
<!-- Discount Functionality end here -->

<!-- Main and Sub Category Functionality start here -->
<script>
    $( document ).ready(function() 
    { 
        var counter = $("#main_cat_active_counter").val();
        var category_code = $("#main_cat_active_categoryCode").val();
        var category_id = $("#main_cat_active_categoryId").val();
        loadSubCategory("All",'0',"All");
    });

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
            var qty = 1;
            selectPosItems(item_id,qty);
        }
    }		
    
    /* $('#enter_qty').on('keypress',function(e) 
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
    }); */
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
                                var item_name = item.item_name;
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

    function PayDineInOrder()
    {
        $('#print_order').modal();
        calculateGrandTotal();
    }

    function deleteLineItems(interface_line_id)
    {
        if(interface_line_id)
        {
            calculateGrandTotal();

            $.ajax({
                type: 'post',
                url: '<?php echo base_url();?>pos/deleteLineItems/'+interface_line_id,
                data: {interface_line_id:interface_line_id},
                success: function (result) 
                {
                   
                }
            });
        }
    }

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

            $('.payable_new').html(paid_amount.toFixed(2));
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

            $('.payable_new').html(paid_amount.toFixed(2));

            $("#tax_amount").val(tax_amount.toFixed(2));
            $("#net_pay").val(paid_amount.toFixed(2));
        }

        var checkedVal = $('input:radio[name=pos_dine_in_type]:checked').val();

        if(checkedVal == "DINE_IN")
        {
            $(".btn-disabled").addClass("disabled-btn");
        }
        else if( checkedVal == "TAKEAWAY" || checkedVal == "HOME_DELIVERY" )
        {
            if(totalOrderAmount > 0)
            {
                $(".dining-proceedto-pay").removeClass("disabled-btn",true);
            }
            else if(totalOrderAmount == 0 || totalOrderAmount == "")
            {
                $(".dining-proceedto-pay").addClass("disabled-btn");
            }

           
            /* if(totalOrderAmount > 0)
            {
                $(".disabled-btn").removeClass("disabled-btn",true);
            }
            else if(totalOrderAmount == 0 || totalOrderAmount == "")
            {
                $(".proceedto-pay").addClass("disabled-btn");
            } */
        } 
    }	
</script>
<!-- Item Onlclick Functionality end here -->
