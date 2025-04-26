

<div class="content"><!-- Content start-->
	<div class="card"><!-- Card start-->
		<?php
			$social_links_json = $this->frontend_model->get_frontend_general_settings('social_links');
			$links = json_decode($social_links_json);
		?>
		<div class="row">
			<div class="col-md-12">
				<div class="panel panel-primary" data-collapsed="0">
					<div class="card-body">
						<form action="" class="form-validate-jquery" enctype="multipart/form-data" method="post">					
							<fieldset class="mb-3">
								<legend class="text-uppercase font-size-sm font-weight-bold">Social Media Settings</legend>

									<div class="form-group row">
										<label class="col-sm-2 control-label">Facebook Page Url</label>
										<div class="col-lg-3">
											<input type="text" class="form-control" name="facebook" placeholder="" value="<?php echo $links[0]->facebook;?>">
										</div>
									</div>
									
									<div class="form-group row">
										<label class="col-sm-2 control-label">Twitter Page URL</label>
										<div class="col-lg-3">
											<input type="text" class="form-control" name="twitter" placeholder="" value="<?php echo $links[0]->twitter;?>">
										</div>
									</div>
									
									<div class="form-group row">
										<label class="col-sm-2 control-label">Linkedin Page URL</label>
										<div class="col-lg-3">
											<input type="text" class="form-control" name="linkedin" placeholder="" value="<?php echo $links[0]->linkedin;?>">
										</div>
									</div>
									
									<div class="form-group row">
										<label class="col-sm-2 control-label">Google Plus Page URL</label>
										<div class="col-lg-3">
											<input type="text" class="form-control" name="google" placeholder="" value="<?php echo $links[0]->google;?>">
										</div>
									</div>
									
									<div class="form-group row">
										<label class="col-sm-2 control-label">Youtube Page URL</label>
										<div class="col-lg-3">
											<input type="text" class="form-control" name="youtube" placeholder="" value="<?php echo $links[0]->youtube;?>">
										</div>
									</div>
										
									<div class="form-group row">
										<label class="col-sm-2 control-label">Instagram Page URL</label>
										<div class="col-lg-3">
											<input type="text" class="form-control" name="instagram" placeholder="" value="<?php echo $links[0]->instagram;?>">
										</div>
									</div>
									
									<div class="d-flex justify-content-end align-items-center">
										<a href="<?php echo base_url(); ?>setup/settings" class="btn btn-default mr-1">Close</a>
										<button type="submit" class="btn btn-primary">Save</button>
									</div>
									
							</fieldset>	
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<!--social links -->

