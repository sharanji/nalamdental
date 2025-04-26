
<div class=" mb-3">
	<div class="">
		<?php
			$query = 'select cus_customers.customer_id,cus_customers.customer_name,cus_customers.email_address,cus_customers.mobile_number from cus_consumers as cus_customers where customer_id = '.$id ;
			$user_data = $this->db->query($query)->result_array();
		?>
		<div class="row">
			<?php /* <div class="col-md-3">
				<div class="client-image">
					<?php 
						if(!empty($user_data[0]['user_id']) && file_exists("uploads/profile_image/".$user_data[0]['user_id'].'.png') )
						{
							
							?>
							<img class="img-responsive" alt="Jesper Apps 2-image" --style="border:1px solid #ddd; border-radius:4px; padding:5px; width:100%;height:150px;" src="<?php echo base_url(); ?>uploads/profile_image/<?php echo $id .'.png';?>">
							<?php 
						}
						else
						{
							?>
								<img src="<?php echo base_url();?>assets/logo1.png" style="max-width:180px !important; max-height:180px !important;" alt="...">
							<?php
						}
					?>
				</div>5
			</div> */ ?>
			<div class="col-md-5">
				<span class="patient-pro-header">
				<div class="card p-3 mb-3 shadow">
					<h2 class="patient-name-header mb-0">
						<div class="row">
							<div class="col-md-3">
								Name 
							</div>
							<div class="col-md-7">
								: <?php echo !empty($user_data[0]['customer_name']) ? $user_data[0]['customer_name'] : "";?>
							</div>
						</div>
						<div class="row">
							<div class="col-md-3">
								Email  
							</div>
							<div class="col-md-7">
								: <?php echo !empty($user_data[0]['email_address']) ? $user_data[0]['email_address'] :"--";?>
							</div>
						</div>
						<div class="row">
							<div class="col-md-3">
								Mobile 
							</div>
							<div class="col-md-7">
								: <?php echo !empty($user_data[0]['mobile_number']) ? $user_data[0]['mobile_number'] :"--";?>
							</div>
						</div>
					</h2>
				</div>

					
				</span>
			</div>

			<div class="col-md-7">
				<?php 
					if($this->user_id==1)
					{
						?>
						<a href="<?php echo base_url(); ?>consumers/ManageCustomer" class="btn btn-default float-right btn-primary">Back</a>
						<?php
					}
					else
					{
						?>
						<?php
					}
				?>
				
			</div>
			<!-- <div class="col-md-4">
				<span class="patient-con-header">
					<h3 class="patient-ph-header mb-0"><i class="fa fa-envelope"></i> <?php echo !empty($user_data[0]['email_address']) ? $user_data[0]['email_address'] :"--";?></h3>
					<h3 class="patient-ph-header mb-0"><i class="fa fa-phone"></i> <?php echo !empty($user_data[0]['mobile_number']) ? $user_data[0]['mobile_number'] :"--";?></h3>
					<?php /* <h3 class="patient-ph-header mb-0"><i class="fa fa-map-marker"></i> <?php /* echo isset($profileData[0]['address1']) ? $profileData[0]['address1']:"--";  ?></h3> */ ?>
				</span>
			</div> -->
		</div>
	</div>
</div>

<style>
    .client-pro-header {
    background: #fff !important;
    padding: 20px !important;
    float: left !important;
    width: 100% !important;
}
.mb-3, .my-3 {
    margin-bottom: 1.25rem!important;
}
 </style>   
