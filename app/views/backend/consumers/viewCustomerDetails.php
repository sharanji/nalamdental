<!-- Page header start-->
<div class="page-header page-header-light">
	<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
		<div class="d-flex">
			<div class="breadcrumb">
				<a href="<?php echo base_url();?>admin/dashboard" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Home</a>
				<a href="<?php echo base_url();?>customer/ManageCustomer" class="breadcrumb-item">
					<?php
						echo $page_title;
					?>
				</a>
			</div>
		</div>
        <a href="<?php echo base_url(); ?>customer/ManageCustomer/edit/<?php echo $id;?>" class="btn btn-info">
            Edit Customer
        </a>
	</div>
</div>
<!-- Page header end-->

<div class="content"><!-- Content start-->
	<div class="card"><!-- Card start-->
		<div class="card-body"><!-- Card-body start-->
			<fieldset class="menu-title">
				<legend class="text-uppercase font-size-sm font-weight-bold">
					<?php
						echo !empty($type) ? $type." Customer" : $page_title;
					?>
				</legend>
			</fieldset>
                    
            <div class="new-scroller">
                <table id="myTable" class="table table-bordered table-hover --table-striped --dataTable">
                    <thead>
                        <tr>
                            <th class="text-center">Controls</th>
                            <th onclick="sortTable(1)">Customer Name</th>
                            <!-- <th onclick="sortTable(2)">Email</th> -->
                            <th onclick="sortTable(3)">Mobile Number</th>
                            <th onclick="sortTable(4)" style="text-align:center; width:5%;">Mobile Verification</th>
                            <th onclick="sortTable(5)">Address</th>
                            <th onclick="sortTable(6)" class="text-center">Created Date</th>
                            <th onclick="sortTable(7)" style="text-align:center; width:12%;">Login Type</th>
                            <th onclick="sortTable(8)" style="text-align:center; width:12%;">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 	
                            $i=0;
                            $firstItem = $first_item;
                            foreach($order_history as $row)
                            {
                                ?>
                                <tr>
                                    <!--<td style="text-align:center;"><?php echo $i + $firstItem;?></td>
                                    -->
                                    <td class="text-center" style="width: 12%;">
                                        <div class="dropdown" style="display: inline-block;padding-right: 10px!important;width:92px;">
                                            <button type="button" class="btn btn-outline-primary gropdown-toggle waves-effect waves-light" data-toggle="dropdown" aria-expanded="false">
                                                Action <i class="fa fa-angle-down"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-right">
                                                <li>
                                                    <a title="Edit" href="<?php echo base_url(); ?>customer/ManageCustomer/edit/<?php echo $row['user_id'];?>">
                                                        <i class="fa fa-pencil"></i> Edit
                                                    </a>
                                                </li>
                                                
                                                <li>
                                                    <?php 
                                                        if($row['user_status'] == 1)
                                                        {
                                                            ?>
                                                            <a href="<?php echo base_url(); ?>customer/ManageCustomer/status/<?php echo $row['user_id'];?>/0" title="Block">
                                                                <i class="fa fa-ban"></i> Inactive
                                                            </a>
                                                            <?php 
                                                        } 
                                                        else
                                                        {  ?>
                                                            <a href="<?php echo base_url(); ?>customer/ManageCustomer/status/<?php echo $row['user_id'];?>/1" title="Unblock">
                                                                <i class="fa fa-check"></i> Active
                                                            </a>
                                                            <?php 
                                                        } 
                                                    ?>
                                                </li>
                                                <li>
                                                    <a title="Edit" href="<?php echo base_url(); ?>customer/ManageCustomer/view/<?php echo $row['user_id'];?>">
                                                        <i class="fa fa-eye"></i> Edit
                                                    </a>
                                                </li>
                                            </ul>
                                            <?php /* <a title="Change Password" href="#" data-toggle="modal" style="float: right;padding-top: 7px;position: absolute; margin-left: 8px; padding-right: 10px;" data-target="#exampleModal<?php echo $row['user_id'];?>">
                                                <i class="icon-lock"></i>
                                            </a> */ ?>
                                        </div>
                                    </td>
                                    <td class="tab-medium-width"><?php echo ucfirst($row['first_name']);?></td>
                                    <!-- <td class="tab-medium-width"><?php echo $row['email'];?></td> -->
                                    <?php 
                                        switch($row['otp_status'])
                                        {
                                            case 1:
                                                $verified = "Verified";
                                                $color	  = "success";
                                            break;
                                            
                                            default:
                                                $verified ="Not Verified";
                                                $color	  = "danger1";
                                            break;
                                        } 
                                    ?>
                                    <td class="tab-medium-width"><?php echo $row['mobile_number'];?></td>
                                    <td style="width: 10%;" style="text-center">
                                        <p class="text-<?php echo $color ?>"><?php echo $verified ;?></i></p>
                                    </td>
                                    <td class="tab-medium-width"><?php echo ucfirst($row['address1']);?> </td>
                                    <!-- <td class="tab-medium-width"><?php echo strtoupper($row['gst_number']);?></td>
                                    <td class="tab-medium-width"><?php echo strtoupper($row['pan_number']);?></td> -->
                                    <td class="tab-full-width text-center">
                                        <?php 
                                            if(!empty($row['joined_date']))
                                            {
                                                echo date('d-M-Y h:i:s a',$row['joined_date']);
                                            }
                                            else{echo '--';}
                                        ?>
                                    </td>
                                    <?php 
                                        switch($row['login_type'])
                                        {
                                            case 0:
                                                $login_type = "Website";
                                                $color	  = "success";
                                                $icon		= "globe";
                                            break;
                                            
                                            case 1:
                                                $login_type = "Facebook";
                                                $color	  = "info";
                                                $icon		= "facebook-f";
                                            break;

                                            case 2:
                                                $login_type = "Google";
                                                $color	  	= "warning";
                                                $icon		= "google";
                                            break;
                                        } 
                                    ?>
                                    <td style="width: 10%;" style="text-center">
                                        <span class="btn btn-<?php echo $color ?> rounded"><i class="fa fa-<?php echo $icon ?> mr-2"></i><?php echo $login_type ;?></i></span>
                                    </td>
                                    <td style="width: 5%;" style="text-center">
                                        <?php 
                                            if($row['user_status'] == 1)
                                            {
                                                ?>
                                                <span class="btn btn-outline-success" title="Active"><i class="fa fa-check"></i> Active</span>
                                                <?php 
                                            } 
                                            else
                                            {  
                                                ?>
                                                <span class="btn btn-outline-warning" title="Inactive"><i class="fa fa-close"></i> Inactive</span>
                                                <?php 
                                            } 
                                        ?>
                                    </td>
                                </tr>
                                <?php 
                                $i++;
                            }
                        ?>
                    </tbody>
                </table>
                <?php 
                    if(count($order_history) == 0)
                    {
                        ?>
                        <p class="admin-no-data"><?php echo NO_DATA_FOUND;?></p>
                        <?php 
                    } 
                ?>
            </div>
            
            <div class="row">
                <div class="col-md-4 showing-count">
                    Showing <?php echo $starting;?> to <?php echo $ending;?> of <?php echo $totalRows;?> entries
                </div>
                <!-- pagination start here -->
                <?php 
                    if( isset($pagination) )
                    {
                        ?>	
                        <div class="col-md-8" class="admin_pagination">
                            <?php foreach ($pagination as $link){echo $link;} ?>
                        </div>
                        <?php
                    }
                ?>
                <!-- pagination end here -->
            </div>
		</div><!-- Card body end-->
	</div><!-- Card end-->
	<?php if(isset($type) && $type =='view'){?>
		<div class="row">
			<div class="col-md-10">
			</div>
			<div class="col-md-2 text-right">
				<a href='<?php echo base_url();?>customer/ManageCustomer' class='btn btn-outline-danger'>
					<i class="fa fa-chevron-circle-left"></i> Back
				</a>
			</div>
		</div>
	<?php } ?>
</div><!-- Content end-->
