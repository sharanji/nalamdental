<style>
	.section-new-1 {
		padding: 17px 10px;
	}

	span.users-section.purchase {
		color: #007de9;
		font-size: 18px;
	}
	span.users-section.expenses {
		color: #ff7600;
		font-size: 18px;
	}	
	span.users-section-count.purchase{
		color: #007de9;
		font-family: roboto !important;
	}
	span.users-section-count.expenses{
		color: #ff7600;
	}
	.new-icons {
		/* background: #f58d17; */
		position: relative;
		left: 7px;
		top: 1px;
		border-radius: 6px;
		color: #fff;
		width: auto;
		text-align: center;
	}
	.count-name {
		position: relative;
		left: 10px;
	}
	i.products {
		position: relative;
		color: #fff;
		background: #df3434;
		font-size: 20px;
		padding: 10px;
		border-radius: 5px;
	}
	i.suppilers {
		position: relative;
		color: #fff;
		background: #77773c;
		font-size: 20px;
		padding: 10px;
		border-radius: 5px;
	}
	i.employee {
		color: #fff;
		position: relative;
		color: #fff;
		background: #ffa64d;
		font-size: 20px;
		padding: 10px;
		border-radius: 5px;
	}
	i.customer {
		color: #fff;
		position: relative;
		color: #fff;
		background: #29a329;
		font-size: 20px;
		padding: 10px;
		border-radius: 5px;
	}
	i.users
	{
		color: #fff;
		position: relative;
		color: #fff;
		background: #1ac6ff;
		font-size: 20px;
		padding: 10px;
		border-radius: 5px;
	}
	.chart {
		background: #fff;
		/* box-shadow: 0px 0px 8px 0px #cfcfcf; */
	}
	.section-new-1.mb-3.dashboard-gradients {
		/* box-shadow: 0px 0px 8px 0px #cfcfcf; */
	}
	span.sales-text {
		position: relative;
		top: 20px;
		left: 2px;
		font-weight: 700;
		border: 1px solid #409bdc;
		padding: 10px;
		border-radius: 50px;
		color: #409bdc;
		background-color: #e8f5ff;
	}
	@media (max-width: 667px){
	  .right-side-down {
		 padding-left:0px!important;
	  }}
	select.newselectList {
		padding: 6px 10px;
		border: 1px solid #d3caca;
		border-radius: 3px;
	}
	select.newselectList:focus {
		outline:none!important;
	}
	select.newselectList option.newselectListoption {
	
	}

.new-card {
    float: left;
    width: 100%;
    background: #fff;
    padding: 15px 10px;
    color: #000;
}

.new-card span.icon {
    color: #fff;
    padding: 8px 12px;
    border-radius: 6px;
    float: left;
}

.new-card span.card-count {
    font-size: 30px;
    font-weight: bold;
    position: relative;
    left: 30px;
    top: -5px;
}

span.card-count-category {
    position: relative;
    top: 16px;
    left: -12%;
}
span.icon.customers {
    background: #46bf87;
}

span.icon.users {
    background: #a146bf;
}

span.icon.suppliers {
    background: #4676bf!important;
    margin: 0;
}

span.icon.employees {
    background: #bf4678!important;
    margin: 0;
}
span.icon.purchase {
    background: #7b68c4!important;
    margin: 0;
}span.icon.sales {
    background: #4aacb8!important;
    margin: 0;
}
span.icon.products {
    background: #acbf46;
}
span.icon.invoice {
    background: #aa83de;
}
span.icon.consumer {
    background: #9aa1b6;
}

</style>
<script src="<?php echo base_url();?>assets/backend/assets/js/Chart.min.js"></script>	

<!-- Content area -->
	<div class="content">
		<!-- start page title -->
		<div class="row">
			<div class="col-12">
				<div class="page-title-box2">
					<?php
						$edit_data1  = $this->db->get_where('users', array('user_id' => $this->user_id))->result_array();
					?>
					<h4 class="page-title page-title-dashboard">Welcome <?php echo isset($edit_data1[0]['first_name']) ? ucfirst($edit_data1[0]['first_name']) :"";?> !</h4>
				</div>
			</div>
		</div>
		
	</div>

	
