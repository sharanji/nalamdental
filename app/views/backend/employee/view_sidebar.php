<div class="col-md-3 length-catgry1--">
	<div class="x_panel">
		<div class="x_title">
			<h2 class="left-company-name">
				<?php echo !empty($edit_data[0]['first_name']) ? $edit_data[0]['first_name'] :"--";?> <?php echo !empty($edit_data[0]['middle_name']) ? $edit_data[0]['middle_name'] : "";?> <?php echo !empty($edit_data[0]['last_name']) ? $edit_data[0]['last_name'] : "";?>
			</h2>
			<div class="leftboxprofile">
				<?php 
					if(file_exists("uploads/profile_image/".$id.'.png') )
					{
						?>
						<img class="img-responsive" alt="" style="border:1px solid #ddd; border-radius:4px; padding:5px; width:100%; height:200px;" src="<?php echo base_url(); ?>uploads/profile_image/<?php echo $id.'.png';?>">
						<?php 
					}
					else
					{
						?>
						<img src="<?php echo base_url();?>uploads/no-image.png" style="max-width:100px !important; max-height:90px !important;" alt="...">
						<?php
					}
				?>
				<div class="empl-details">
					<h5><i class="fa fa-black-tie" aria-hidden="true"></i> &nbsp; <?php echo !empty($edit_data[0]['random_user_id']) ? $edit_data[0]['random_user_id'] :"--";?></h5>
					<h5><i class="fa fa-phone"></i> &nbsp; 
					<?php echo !empty($edit_data[0]['mobile_number']) ? $edit_data[0]['mobile_number'] : "--";?></h5>
				</div>
			</div>
			<div class="clearfix"></div>
		</div>
		<?php 
			$segment = $this->uri->segment(3);
			
			$activeProfile = 
			$EmployeeDocuments = 
			$paySlip =
			$bankDetails =  '';
			
			if( isset($segment) && $segment == "view" )
			{
				$activeProfile = 'active';
			}
			else if( isset($segment) && $segment == "EmployeeDocuments" )
			{
				$EmployeeDocuments = 'active';
			}else if( isset($segment) && $segment == "AssignProjects" )
			{
				$activeAssignProjects = 'active';
			}
			else if( isset($segment) && $segment == "payslip" )
			{
				$paySlip =  'active';
			}
			else if( isset($segment) && $segment == "bankDetails" )
			{
				$bankDetails =  'active';
			}
		?>
		<div class="x_content x_content2">
			<ul class="nav nav-tabs1 tabs-left1">
				<li class="sidebar-nav-item <?php echo $activeProfile;?>">
					<a href="<?php echo base_url();?>employee/ManageEmployee/view/<?php echo $id;?>">
						<i class="fa fa-black-tie"></i>
						<span class="sidebar-title">Profile</span>
					</a>
				</li>
				
				<?php /* <li class="sidebar-nav-item <?php echo $EmployeeDocuments;?>">
					<a href="<?php echo base_url();?>employee/ManageEmployee/EmployeeDocuments/<?php echo $id;?>">
						<i class="fa fa-file"></i>
						<span class="sidebar-title">Documents</span>
					</a>
				</li>
				
				<li class="sidebar-nav-item <?php echo $paySlip;?>">
					<a href="<?php echo base_url();?>employee/ManageEmployee/payslip/<?php echo $id;?>">
						<i class="fa fa-credit-card"></i>
						<span class="sidebar-title">Payslip Categories</span>
					</a>
				</li> */ ?>
				
				<li class="sidebar-nav-item <?php echo $bankDetails;?>">
					<a href="<?php echo base_url();?>employee/ManageEmployee/bankDetails/<?php echo $id;?>">
						<i class="fa fa-tasks"></i>
						<span class="sidebar-title">Bank Details</span>
					</a>
				</li>
			</ul>
			
		</div>
	</div>
</div>

<!-- End col-md-3 for left side menu -->
<?php /*
<link href="<?php echo base_url();?>assets/backend/view_gallery/jquery.magnify.css" rel="stylesheet">
<script src="<?php echo base_url();?>assets/backend/view_gallery/jquery.magnify.js"></script>
*/ ?>