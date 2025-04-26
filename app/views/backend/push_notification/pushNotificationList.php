
<style>
	.new-notifications {
		/* background: #e9e3e3; */
		padding: 10px;
		border-radius: 10px;
		border: 1px solid #c7c6c6;
	}
	.new-notifications ul {
		padding: 0px;
	}
	.new-notifications ul li {
		list-style: none;
		background: #e32227;
		width: auto;
		font-size: 12px;
		padding: 10px;
		border-radius: 14px;
		margin-bottom: 10px;
		color: #fff;
		min-height:70px;
		max-height:70px;
	}
	span.date-time {
		color: #dfd9d9;
		font-size: 11px;
		position: relative;
		top: 2px;
		right: 10px;
		float:right;
	}
	span.msg-status {
		float: right;
		padding-right: 10px;
		color: #fff;
	}
	span.title {
    float: left;
    width: 100%;
    font-weight: 600;
}span.preview-img img {
    position: relative;
    /* top: -20px; */
    right: 0px;
    float: right;
}

</style>

<?php 
	$notificationQuery = "select 
		org_push_notifications.title,
		org_push_notifications.message,
		org_push_notifications.message_status,
		org_push_notifications.notification_str_date
		from org_push_notifications 
	join users on 
		users.user_id = org_push_notifications.user_id
	order by notification_id desc limit 0,10";
	$getNotification = $this->db->query($notificationQuery)->result_array();
?>

<div class="new-notifications">
	<ul>
		<?php 
			if(count($getNotification) > 0) 
			{ 
				foreach($getNotification as $notification) 
				{
					?>
					<li>
						<span class="title"><?php echo $notification["title"];?> </span>
						<span class="message"><?php echo $notification["message"];?></span>
						<span class="preview-img">
							<img src="<?php echo base_url();?>uploads/no-image-mobile.png" style="width:25px;height:25px;">
						</span>
						<span class="date-time"><?php echo date("d-M-Y h:i A",$notification["notification_str_date"]);?> </span>
						<?php 
							/* if($notification['message_status'] > 0)
							{
								?>
								<span class="msg-status"><i class="fa fa-thumbs-up"></i></span>
								<?php
							}
							else if($notification['message_status'] == 0)
							{
								?>
								<span class="msg-status"><i class="fa fa-thumbs-down"></i></span>
								<?php
							} */
						?>
					</li>
					<?php 
				} 
			}
			else
			{
				?>
				<span style="padding:0px 0px 0px 200px;">
					<img src="<?php echo base_url();?>uploads/no-data.png" style="width:120px;text-align:center;">
					<br><p style="text-align:center;padding:0px 0px 0px 45px;color: #afaeae !important;">No data found.</p>
				</span>
				<?php
			} 
		?>
	</ul>
</div>