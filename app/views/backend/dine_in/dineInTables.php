<style>
    a.table-card {
        text-align: center;
        float: left;
        width: 100%;
        padding: 35px 0px;
        border: 1px dashed #6bd141;
        border-radius: 7px;
        margin-bottom: 10px;
        color: green;
    }
   
    .section-1 {
        border-bottom: 1px solid #edeaea;
        float: left;
        width: 100%;
    }
    .section-2 {
        
        float: left;
        width: 100%;
    }
    div.table-section {
        float: left;
        width: 100%;
    }
    .kot-btn input {
        position: relative;
        top: 3px;
        left: -1px;
    }
    section.din-in-section {
        min-height: 68vh;
        max-height: 80vh;
        overflow: scroll;
        float: left;
        width: 100%;
    }
    section.din-in-section::-webkit-scrollbar {
        display: none!important;;
    }
    .color-code {
       
        float:right;
        margin-right: 12px;
    }
    .available
    {
        height: 15px;
        width: 20px;
        /* background: #eaeff5; */
        float: left;
        width: 15px;
        border-radius: 2px;
        border:1px solid #6bd141;
        float:left;
        position: relative;
        top: 3px;
        right: 4px;
    }
    .occupied
    {
        height: 15px;
        width: 20px;
        background: #e3dddd;
        float: left;
        width: 15px;
        border-radius: 2px;
        float:left;
        position: relative;
        top: 3px;
        right: 4px;
        border: 1px solid #c3c3c3;
    }
   
    .tbl-occupied {
        background: #fbffbbe8;
        border: 1px dashed #35362a  !important;
    }
    
    .tbl-save-print {
        background: #83f183;
        border: 1px dashed #35362a !important;
    }
    /* a.table-card:hover {
        background: #b0ff8f;
        transition: 0.5s;
    } */
    a.view-order i {
        background: #fff;
        padding: 6px 8px;
        border: 1px solid #d2d5d1;
        border-radius: 3px;
        position: relative;
        top: -20%;
        background: #fbfbfb;
        color:#000;
    }

   /*  span.order-amount {
        font-weight: 600;
        position: absolute;
        top: 10px;
        transition: 10degree;
        transform: rotate(29deg);
    } */
    span.order-amount {
        font-weight: 600;
        position: absolute;
        top: 11px;
        right: 2px;
        font-size: 11px;
        transition: 10degree;
        transform: rotate(44deg);
        background: #ff0000;
        padding: 0px 6px!important;
        border-top-right-radius: 0px;
        border-top-left-radius: 0px;
        color: #ffffff;
        z-index: 999;
    }
    /* .triangle-5 {
        width: 15px;
        height: 15px;
        border-top: solid 9px rgb(255 0 0);
        border-left: solid 9px transparent;
        border-right: solid 9px transparent;
        position: absolute;
        top: 27px;
        right: -1px;
        transform: rotate(316deg);
    } */
</style>



<div class="content" style="background:#fff!important;overflow: hidden!important">
    <form action="" id="order_items" method="post">
        <div class="container-fluid">
            <div style="box-shadow:1px 1px 1px #fff;">
                <div class="section-1 row">
                    <div class="col-md-6">
                        <h2><b>Dine In Tables</b></h2>
                    </div>
                    <div class="col-md-6 float-right text-right">
                        <a href="<?php echo base_url();?>admin/home" title="Home" class="header-icons">
                            <i class="fa fa-home" style="font-size:20px;"></i>
                        </a>
                        <!-- <button type="btn" class="btn-sm btn btn-primary"> DELIVERY </button>
                        <button type="btn" class="btn-sm btn btn-primary"> PICKUP </button>
                        <button type="btn" class="btn-sm btn btn-primary"> + ADD TABLE </button> -->
                    </div>
                </div>
                <div class="section-2 row pt-3">
                    <div class="col-md-12 float-right text-right">
                        <span class="color-code"><h6 class="occupied"></h6> Occupied</span>
                        <span class="color-code mr-3"><h6 class="available"></h6> Available</span>
                    </div>
                </div>
                <section class="din-in-section">
                    <?php 
                        
                        if($this->branch_id){
                            $branch_id = $this->branch_id;
                            //$branch_id = 1;
                        }else{
                            $branch_id = 'NULL';
                        }

                        $getLocationQry ="
                        select 
                        ltv.list_value as table_location,
                        ltv.list_type_value_id,
                        din_table_headers.branch_id 
                        from sm_list_type_values as ltv
                        left join din_table_headers on din_table_headers.table_location_id = ltv.list_type_value_id
                        where 1=1 
                        and ltv.list_type_id = '20'
                        and din_table_headers.active_flag = 'Y'
                        and (
                            din_table_headers.branch_id = '".$branch_id."' 
                            or (din_table_headers.branch_id = (select a.branch_id from branch a where a.default_branch = 'Y') and ".$this->user_id." = 1)
                            )
                        ";
                        
                        $getLocation = $this->db->query($getLocationQry)->result_array();

                        foreach($getLocation as $location)
                        {
                            $checkCountQry ="
                                    select din_table_lines.line_id
                                    from din_table_lines
                                    left join din_table_headers on din_table_headers.header_id = din_table_lines.header_id
                                   
                                    left join din_table_waiters as waiters on waiters.table_line_id = din_table_lines.line_id
                                    
                                    where 1=1 
                                    and din_table_headers.table_location_id = '".$location['list_type_value_id']."'
                                    and din_table_lines.active_flag = 'Y'
                                    and waiters.user_id = '".$this->user_id."' 
                                    and (
                                        din_table_headers.branch_id = '".$branch_id."' 
                                        or (din_table_headers.branch_id = (select a.branch_id from branch a where a.default_branch = 'Y') and ".$this->user_id." = 1)
                                    )
                                "; 

                            $checkCount = $this->db->query($checkCountQry)->result_array();

                            if(count($checkCount) == 0)
                            {
                                $getTablesQry ="
                                    select din_table_lines.table_name,
                                    din_table_lines.line_id,
                                    din_table_lines.table_code,
                                    din_table_lines.table_no_of_persons
                                    from din_table_lines
                                    left join din_table_headers on din_table_headers.header_id = din_table_lines.header_id
                                    where 1=1 
                                    and din_table_headers.table_location_id = '".$location['list_type_value_id']."'
                                    and din_table_lines.active_flag = 'Y'

                                    and (
                                        din_table_headers.branch_id = '".$branch_id."' 
                                        or (din_table_headers.branch_id = (select a.branch_id from branch a where a.default_branch = 'Y') and ".$this->user_id." = 1)
                                    )
                                "; 
                            }
                            else
                            {
                                $getTablesQry ="
                                    select din_table_lines.table_name,
                                    din_table_lines.line_id,
                                    din_table_lines.table_code,
                                    din_table_lines.table_no_of_persons
                                    from din_table_lines
                                    left join din_table_headers on din_table_headers.header_id = din_table_lines.header_id
                                   
                                    left join din_table_waiters as waiters on waiters.table_line_id = din_table_lines.line_id
                                    
                                    where 1=1 
                                    and din_table_headers.table_location_id = '".$location['list_type_value_id']."'
                                    and din_table_lines.active_flag = 'Y'
                                    and waiters.user_id = '".$this->user_id."' 
                                    and (
                                        din_table_headers.branch_id = '".$branch_id."' 
                                         or (din_table_headers.branch_id = (select a.branch_id from branch a where a.default_branch = 'Y') and ".$this->user_id." = 1)
                                    )
                                "; 

                            }   

                            $getTables = $this->db->query($getTablesQry)->result_array(); 
                             
                            ?>
                            
                            <h4><b><?php echo $location['table_location'];?></b></h4>
                            <div class="table-section0">
                                <div class="col-md-12">
                                    <div class="row mb-3">
                                        <?php 
                                            foreach($getTables as $table)  
                                            {
                                                $table_id = $table['line_id'];

                                                $orderCreatedQry = "select 
                                                    header_tbl.interface_header_id,
                                                    header_tbl.interface_status,

                                                    (select count(cnt.interface_header_id) from ord_order_interface_headers cnt where cnt.interface_status != 'Success' and cnt.table_id = '".$table_id."') as sub_tbl_cnt,
                                                    
                                                    sum(line_tbl.price * line_tbl.quantity) as bill_amount,

                                                    round( sum(( coalesce(line_tbl.offer_percentage,0) / 100) * (line_tbl.quantity * line_tbl.price)),2) as offer_amount,
                                                    round( sum((line_tbl.quantity * line_tbl.price) - ((coalesce(line_tbl.offer_percentage,0) / 100) * (line_tbl.quantity * line_tbl.price))),2) as linetotal,
                                                    round( sum( ((line_tbl.quantity * line_tbl.price) - ((coalesce(line_tbl.offer_percentage,0) / 100) * (line_tbl.quantity * line_tbl.price))) * (coalesce(line_tbl.tax_percentage,0) /100)),2) as tax_value
                                                    
                                                    from ord_order_interface_headers as header_tbl
                                                    
                                                    left join ord_order_interface_lines as line_tbl on line_tbl.reference_header_id = header_tbl.interface_header_id

                                                    where 1=1
                                                    and header_tbl.order_source = 'DINE_IN'
                                                    and header_tbl.interface_status != 'Success'
                                                    and header_tbl.table_id = '".$table_id."'
                                                    group by header_tbl.table_id
                                                    ";
                                                   
                                                $getOrderCreated = $this->db->query($orderCreatedQry)->result_array();

                                                if(count($getOrderCreated) > 0)
                                                {
                                                    $interface_status = isset($getOrderCreated[0]["interface_status"]) ? $getOrderCreated[0]["interface_status"] : NULL;
                                                    $sub_tbl_cnt = isset($getOrderCreated[0]["sub_tbl_cnt"]) ? $getOrderCreated[0]["sub_tbl_cnt"] : NULL;
                                                   
                                                    if($sub_tbl_cnt > 1)
                                                    {
                                                        
                                                        $tableType = "Created";
                                                        $tbl_card_class = 'tbl-occupied-sub-tbl';
                                                        $linetotal = isset($getOrderCreated[0]["linetotal"]) ? $getOrderCreated[0]["linetotal"] : "0.00";
                                                        $tax_value = isset($getOrderCreated[0]["tax_value"]) ? $getOrderCreated[0]["tax_value"] : "0.00";
                                                        $interface_header_id = isset($getOrderCreated[0]["interface_header_id"]) ? $getOrderCreated[0]["interface_header_id"] : "0";
                                                        
                                                        $totalOrderAmount = number_format($linetotal + $tax_value,DECIMAL_VALUE,'.','');
                                                        $title="Multiple Orders";
                                                    }
                                                    else
                                                    {
                                                        if($interface_status == "Created")
                                                        {
                                                            $tableType = "Created";
                                                            $tbl_card_class = 'tbl-occupied';
                                                            $linetotal = isset($getOrderCreated[0]["linetotal"]) ? $getOrderCreated[0]["linetotal"] : "0.00";
                                                            $tax_value = isset($getOrderCreated[0]["tax_value"]) ? $getOrderCreated[0]["tax_value"] : "0.00";
                                                            $interface_header_id = isset($getOrderCreated[0]["interface_header_id"]) ? $getOrderCreated[0]["interface_header_id"] : "0";
                                                            
                                                            $totalOrderAmount = number_format($linetotal + $tax_value,DECIMAL_VALUE,'.','');
                                                            $title="Booked Tables";
                                                        }
                                                        else if($interface_status == "Printed")
                                                        {
                                                            $tableType = "Printed";
                                                            $tbl_card_class = 'tbl-save-print';
                                                            $linetotal = isset($getOrderCreated[0]["linetotal"]) ? $getOrderCreated[0]["linetotal"] : "0.00";
                                                            $tax_value = isset($getOrderCreated[0]["tax_value"]) ? $getOrderCreated[0]["tax_value"] : "0.00";
                                                            $interface_header_id = isset($getOrderCreated[0]["interface_header_id"]) ? $getOrderCreated[0]["interface_header_id"] : "0";
                                                            
                                                            $totalOrderAmount = number_format($linetotal + $tax_value,DECIMAL_VALUE,'.','');
                                                            $title="Save & Print Items";
                                                        } 
                                                    }   
                                                }
                                                else
                                                {
                                                    $tableType = "open_tables";
                                                    $tbl_card_class = '';
                                                    $totalOrderAmount = "";
                                                    $title="Open Tables";
                                                    $interface_header_id = 0;
                                                }


                                                $checkTblCount = "select 
                                                    header_tbl.interface_status,
                                                    header_tbl.interface_header_id,
                                                    header_tbl.sub_table,
                                                    tbl_line_tbl.table_code,
                                                    CONCAT(tbl_line_tbl.table_code,coalesce(header_tbl.sub_table,'')) as sub_table_code,
                                                    sum(line_tbl.price * line_tbl.quantity) as bill_amount,

                                                    round( sum(( coalesce(line_tbl.offer_percentage,0) / 100) * (line_tbl.quantity * line_tbl.price)),2) as offer_amount,
                                                    round( sum((line_tbl.quantity * line_tbl.price) - ((coalesce(line_tbl.offer_percentage,0) / 100) * (line_tbl.quantity * line_tbl.price))),2) as linetotal,
                                                    round( sum( ((line_tbl.quantity * line_tbl.price) - ((coalesce(line_tbl.offer_percentage,0) / 100) * (line_tbl.quantity * line_tbl.price))) * (coalesce(line_tbl.tax_percentage,0) /100)),2) as tax_value
                                                    
                                                    from ord_order_interface_headers as header_tbl
                                                    
                                                    left join ord_order_interface_lines as line_tbl on line_tbl.reference_header_id = header_tbl.interface_header_id
                                                    left join din_table_lines as tbl_line_tbl on tbl_line_tbl.line_id = header_tbl.table_id

                                                    where 1=1
                                                    and header_tbl.order_source = 'DINE_IN'
                                                    and header_tbl.interface_status != 'Success'
                                                    and header_tbl.table_id = '".$table_id."'

                                                    group by header_tbl.table_id,header_tbl.sub_table
                                                    ";
                                                   
                                                $getTablesCount = $this->db->query($checkTblCount)->result_array();
                                                
                                                ?>
                                                
                                                <div class="col-md-1 text-center dining-tbls" title="<?php echo $title;?>">

                                                    <?php 
                                                        if(count($getTablesCount) > 1)
                                                        {
                                                            ?>
                                                            <a class="table-card <?php echo $tbl_card_class;?>" href="javascript:void(0);" data-toggle="modal" data-target="#exampleModal<?php echo $table['line_id']; ?>">
                                                                <?php
                                                                    if( $tableType != "open_tables")
                                                                    {
                                                                        ?>
                                                                        <span class="order-amount"><?php echo $totalOrderAmount;?></span>
                                                                        <?php 
                                                                    } 
                                                                ?>
                                                                <?php echo $table['table_code'];?><br>
                                                            </a>
                                                    
                                                            <?php
                                                                if( $tableType != "open_tables")
                                                                {
                                                                    if($tableType == "Created")
                                                                    {
                                                                        ?>
                                                                        <a href="javascript:void(0);" class="view-order">
                                                                            <i class="fa fa-cutlery"></i>
                                                                        </a>
                                                                        <?php
                                                                    }
                                                                    else if($tableType == "Printed")
                                                                    {
                                                                        ?>
                                                                        <a href="javascript:void(0);" class="view-order">
                                                                            <i class="fa fa-inr"></i>
                                                                        </a>
                                                                        <?php
                                                                    }
                                                                }
                                                            ?>
                                                            <?php 
                                                        }
                                                        else
                                                        {
                                                            ?>
                                                            <a class="table-card <?php echo $tbl_card_class;?>" href="<?php echo base_url();?>dine_in/dineInOrder/<?php echo $table['line_id']; ?>/<?php echo $tableType;?>/<?php echo $interface_header_id;?>">
                                                                <?php
                                                                    if( $tableType != "open_tables")
                                                                    {
                                                                        ?>
                                                                        <span class="order-amount"><?php echo $totalOrderAmount;?></span>
                                                                        <?php 
                                                                    } 
                                                                ?>
                                                                <?php echo $table['table_code'];?><br>
                                                            </a>
                                                    
                                                            <?php
                                                                if( $tableType != "open_tables")
                                                                {
                                                                    if($tableType == "Created")
                                                                    {
                                                                        ?>
                                                                        <a href="<?php echo base_url();?>dine_in/dineInOrder/<?php echo $table['line_id']; ?>/<?php echo $tableType;?>/<?php echo $interface_header_id;?>" class="view-order">
                                                                            <i class="fa fa-cutlery"></i>
                                                                        </a>
                                                                        <?php
                                                                    }
                                                                    else if($tableType == "Printed")
                                                                    {
                                                                        ?>
                                                                        <a href="<?php echo base_url();?>dine_in/dineInOrder/<?php echo $table['line_id']; ?>/<?php echo $tableType;?>/<?php echo $interface_header_id;?>" class="view-order">
                                                                            <i class="fa fa-inr"></i>
                                                                        </a>
                                                                        <?php
                                                                    }
                                                                } 
                                                            ?>
                                                            <?php
                                                        } 
                                                    ?>
                                                </div>

                                                <!-- Modal -->
                                                <div class="modal fade" id="exampleModal<?php echo $table['line_id']; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h3 class="modal-title" id="exampleModalLabel"><b>Tables</b></h3>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <div class="row">
                                                                    <?php 
                                                                        foreach($getTablesCount as $subTblData)
                                                                        {
                                                                            $tableType = $subTblData["interface_status"];

                                                                            if($tableType == "Created"){
                                                                                $tbl_card_class = 'tbl-occupied';
                                                                            }else if($tableType == "Printed"){
                                                                                $tbl_card_class = 'tbl-save-print';
                                                                            }

                                                                            $linetotal1 = isset($subTblData["linetotal"]) ? $subTblData["linetotal"] : "0.00";
                                                                            $tax_value1 = isset($subTblData["tax_value"]) ? $subTblData["tax_value"] : "0.00";
                                                                            $interface_header_id = isset($subTblData["interface_header_id"]) ? $subTblData["interface_header_id"] : "0";
                                                                            
                                                                            $totalOrderAmount1 = number_format($linetotal1 + $tax_value1,DECIMAL_VALUE,'.','');
                                                                            $title="Booked Tables";
                                                                            
                                                                            ?>
                                                                            <div class="col-md-2 text-center dining-tbls" title="<?php echo $title;?>">
                                                                                <a class="table-card <?php echo $tbl_card_class;?>" href="<?php echo base_url();?>dine_in/dineInOrder/<?php echo $table['line_id']; ?>/<?php echo $tableType;?>/<?php echo $interface_header_id;?>">
                                                                                    <span class="order-amount"><?php echo $totalOrderAmount1;?></span>
                                                                                    <?php echo $subTblData['sub_table_code'];?><br>
                                                                                </a>
                                                                        
                                                                                <?php
                                                                                    if($tableType == "Created")
                                                                                    {
                                                                                        ?>
                                                                                        <a href="<?php echo base_url();?>dine_in/dineInOrder/<?php echo $table['line_id']; ?>/<?php echo $tableType;?>/<?php echo $interface_header_id;?>" class="view-order">
                                                                                            <i class="fa fa-cutlery"></i>
                                                                                        </a>
                                                                                        <?php
                                                                                    }
                                                                                    else if($tableType == "Printed")
                                                                                    {
                                                                                        ?>
                                                                                        <a href="<?php echo base_url();?>dine_in/dineInOrder/<?php echo $table['line_id']; ?>/<?php echo $tableType;?>/<?php echo $interface_header_id;?>" class="view-order">
                                                                                            <i class="fa fa-inr"></i>
                                                                                        </a>
                                                                                        <?php
                                                                                    }
                                                                                ?>
                                                                            </div>
                                                                            <?php 
                                                                        } 
                                                                    ?>
                                                                </div>
                                                            </div>
                                                            <!-- <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                                <button type="button" class="btn btn-primary">Save changes</button>
                                                            </div> -->
                                                        </div>
                                                    </div>
                                                </div>
                                               <?php
                                            }
                                        ?>
                                    </div>
                                </div>
                            </div>
                            <?php
                        }
                    ?>
                </section>
            </div>
        </div>
    </form>
</div>
