<style type="text/css">
	#printable { display: none; }

	@media print
	{
		#non-printable { display: none; }
		#printable { display: block; }
	}
	.content-address-lft{float:left;}
	.content-address-rgt{float:right;}
	p.lic_no {
	float: left;
	margin: 0px 0px 2px 30px;
	}
	.table-bordered>thead>tr>th, .table-bordered>tbody>tr>th, .table-bordered>tfoot>tr>th, .table-bordered>thead>tr>td, .table-bordered>tbody>tr>td, .table-bordered>tfoot>tr>td{    border: 1px solid #bfbebe!important;}
</style>

<script language="javascript">
	function printDiv(divName) 
	{ 
		var printContents = document.getElementById(divName).innerHTML; 
		var originalContents = document.body.innerHTML; 
		document.body.innerHTML = printContents; window.print(); 
		document.body.innerHTML = originalContents; 
	}
</script>
<div class="page-header page-header-light">
	<?php /* <div class="page-header-content header-elements-md-inline">
		<div class="page-title d-flex back-header-full">
			<h4>
				<i class="icon-arrow-left52 mr-2"></i> 
				<span class="font-weight-semibold"> 
					<?php
						if(isset($type) && $type == "view")
						{ 
							?>
							<?php echo ucfirst($type);?> Invoices
							<?php 
						}
						else
						{ 
							?>
							<?php echo $page_title;?>
							<?php 
						} 
					?>
				</span>
			</h4>
			<a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
		</div>
	</div> */ ?>

	<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
		
		<div class="d-flex">
			<div class="breadcrumb">
				<a href="<?php echo base_url();?>admin/dashboard" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> <?php echo get_phrase('Home');?></a>
				<a href="<?php echo base_url();?>customer/ManageCustomer" class="breadcrumb-item">
					<?php echo $page_title;?>
				</a>
			</div>
		</div>
		
		<div class="top-right" style="float:right;">
			<a title="Print" onclick="printDiv('printableArea')"  class="btn btn-warning" href="javascript:void(0);">
				<i class="fa fa-print"></i>
			</a>
			
			<a href="<?php echo base_url(); ?>customer/ManageCustomer/edit/<?php echo $id;?>" class="btn btn-info" title="Edit">
				<i class="fa fa-pencil" aria-hidden="true"></i>
			</a>
		</div>
		
	</div>

	<?php /* <div class="row" style="padding:10px 0px 10px 0px;">
		<div class="col-md-9"> </div>
		<div class="col-md-3">
		<?php 
			/* <a title="Save as PDF" class="btn btn-danger" target="_blank" href="<?php echo base_url(); ?>invoices/invoiceDetailsPDF/<?php echo $id;?>/<?php echo $appointment_id;?>">
				<i class="fa fa-file-pdf-o"></i>
			</a> 
			?>
			<a title="Print" onclick="printDiv('printableArea')"  class="btn btn-warning" href="javascript:void(0);">
				<i class="fa fa-print"></i>
			</a>
			
			<a href="<?php echo base_url(); ?>purchase/ManagePurchase/edit/<?php echo $id;?>" class="btn btn-info" title="Edit">
				<i class="fa fa-pencil" aria-hidden="true"></i>
			</a>
		 </div>
	</div> */ ?>
</div>

<?php 
	$querys = "select 
				user.user_id,
				user.random_user_id,
				user.first_name,
				user.last_name,
				user.address1,
				user.email,
				user.pin_code,
				user.billing_zip_code,
				user.shipping_zip_code,
				user.gst_number,
				user.pan_number,
				user.address2,
				user.phone_number,
				user.user_name,
				country.country_code,
				country.country_name,
				state.state_name,
				city.city_name,
				billing_country.country_name as billing_country_name,
				billing_state.state_name as billing_state_name,
				billing_city.city_name as billing_city_name,
				shipping_country.country_name as shipping_country_name,
				shipping_state.state_name as shipping_state_name,
				shipping_city.city_name as shipping_city_name
				
				from users as user
				
				left join country on
					country.country_id = user.country_id
				
				left join state on
					state.state_id = user.state_id
				
				left join city on
					city.city_id = user.city_id
				
				left join country as billing_country on	
					billing_country.country_id = user.billing_country_id
				
				left join state as billing_state on	
					billing_state.state_id = user.billing_state_id
				
				left join city as billing_city on	
					billing_city.city_id = user.billing_city_id
				
				left join country as shipping_country on	
					shipping_country.country_id = user.shipping_country_id
				
				left join state as shipping_state on	
					shipping_state.state_id = user.shipping_state_id
				
				left join city as shipping_city on	
					shipping_city.city_id = user.shipping_city_id
				
			where 
				user_type !=1 
				and register_type=1
				and user.user_id='".$id."'
			";
		$edit_data = $this->db->query($querys,array($id))->result_array();
		
?>

<div class="content"><!-- Content start-->
	<div class="card box"><!-- Card start-->
		<div id="printableArea">
			<div class="card-body">
				
				<div class="row">
	<?php  /* <div class="col-md-3">
						<div class="client-image">
							<?php 
								if(file_exists("uploads/profile_image/".$id.'.png') )
								{
									?>
									<img class="img-responsive" alt="" style="" src="<?php echo base_url(); ?>uploads/products/<?php echo $id.'.png';?>">
									<?php 
								}
								else
								{
									?>
									<img src="<?php echo base_url();?>uploads/no-image.png" style="max-width:180px !important; max-height:180px !important;" alt="...">
									<?php
								}  
							?>
						</div>
					</div> */ ?>
					<div class="col-md-7">
						<span class="patient-pro-header">
							<h2 class="patient-name-header mb-0"><?php  echo ucfirst($edit_data[0]['customer_name'])." ".ucfirst($edit_data[0]['last_name']); ?></h2>
							<h3 class="patient-id-header mb-0"><span><?php echo isset($edit_data[0]['random_user_id']) ? $edit_data[0]['random_user_id']:"--"; ?></span> </h3>
						</span>
					</div>
					<div class="col-md-5">
						<span class="patient-con-header">
							<h3 class="patient-ph-header mb-0"><i class="fa fa-google-wallet"></i> <?php  echo isset($edit_data[0]['gst_number']) ? $edit_data[0]['gst_number']:"--";  ?></h3>
							<h3 class="patient-ph-header mb-0"><i class="fa fa-id-card"></i> <?php  echo isset($edit_data[0]['pan_number']) ? $edit_data[0]['pan_number']:"--"; ?></h3>
							<h3 class="patient-ph-header mb-0"><i class="fa fa-envelope"></i> <?php  echo isset($edit_data[0]['email']) ? $edit_data[0]['email']:"--"; ?></h3>
							<h3 class="patient-ph-header mb-0"><i class="fa fa-phone"></i> <?php  echo $edit_data[0]['country_code'] ?> <?php echo isset($edit_data[0]['phone_number']) ? $edit_data[0]['phone_number']:"--";  ?></h3>
							<h3 class="patient-ph-header mb-0"><i class="fa fa-map-marker"></i> <?php echo isset($edit_data[0]['address1']) ? $edit_data[0]['address1']:"--";  ?></h3>
						</span>
					</div>
				</div>
			</div>  
		</div>
	</div>

	<?php /*
	<div class="content x_content1 detail-section-emp">
		<div class="card box">
			<div class="col-md-12 client-details-new mt-2">
				<div class="row">
					<div class="col-md-6">
						<h2 class="cli-other-info"><i class="fa fa-map"></i> Billing Address</h2>
						<div class="card">
							<div class="cli-card-new ">
									
								<div class="row client-details-row">
									<div class="col-md-4">
										<span class="view-label"> Country Name</span>
									</div>
									<div class="col-md-7">
										<?php echo !empty($edit_data[0]['billing_country_name']) ? $edit_data[0]['billing_country_name'] : "--";?>
									</div>
								</div>
								<div class="row client-details-row">
									<div class="col-md-4">
										<span class="view-label"> State Name</span>
									</div>
									<div class="col-md-7">
										<?php echo !empty($edit_data[0]['billing_state_name']) ? $edit_data[0]['billing_state_name'] : "--";?>
									</div>
								</div>
								<div class="row client-details-row">
									<div class="col-md-4">
										<span class="view-label"> City Name</span>
									</div>
									<div class="col-md-7">
										<?php echo !empty($edit_data[0]['billing_city_name']) ? $edit_data[0]['billing_city_name'] : "--";?>
									</div>
								</div>
								<div class="row client-details-row">
									<div class="col-md-4">
										<span class="view-label"> Postal Code</span>
									</div>
									<div class="col-md-7">
										<?php echo !empty($edit_data[0]['billing_zip_code']) ? $edit_data[0]['billing_zip_code'] : "--";?>
									</div>
								</div>
							</div>
						</div>
					</div>
				
					<div class="col-md-6">
						<h2 class="cli-other-info"><i class="fa fa-map"></i> Shipping Address</h2>
						<div class="card">
							<div class="cli-card-new ">
								
								<div class="row client-details-row">
									<div class="col-md-4">
										<span class="view-label"> Country Name</span>
									</div>
									<div class="col-md-7">
										<?php echo !empty($edit_data[0]['shipping_country_name']) ? $edit_data[0]['shipping_country_name'] : "--";?>
									</div>
								</div>
								<div class="row client-details-row">
									<div class="col-md-4">
										<span class="view-label"> State Name</span>
									</div>
									<div class="col-md-7">
										<?php echo !empty($edit_data[0]['shipping_state_name']) ? $edit_data[0]['shipping_state_name'] : "--";?>
									</div>
								</div>
								<div class="row client-details-row">
									<div class="col-md-4">
										<span class="view-label"> City Name</span>
									</div>
									<div class="col-md-7">
										<?php echo !empty($edit_data[0]['shipping_city_name']) ? $edit_data[0]['shipping_city_name'] : "--";?>
									</div>
								</div>
								<div class="row client-details-row">
									<div class="col-md-4">
										<span class="view-label"> Postal Code</span>
									</div>
									<div class="col-md-7">
										<?php echo !empty($edit_data[0]['shipping_zip_code']) ? $edit_data[0]['shipping_zip_code'] : "--";?>
									</div>
								</div>
							</div>
						</div>
							
					</div>
				</div>
			</div>
		</div>	
	</div>
	*/ ?>
		
	<div class="row">
		<div class="col-md-10">
		</div>
		<div class="col-md-2" style="text-align:right;">
			<a href="<?php echo base_url();?>customer/ManageCustomer" class="btn btn-outline-primary" title="Back">
				<i class="fa fa-arrow-left" aria-hidden="true"></i> Back
			</a>
		</div>
	</div>

</div>
