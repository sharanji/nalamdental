<!DOCTYPE html>
<html class="no-js" lang="en">
	<head>
		<meta charset="utf-8">
        <meta name="viewport" content="width=device-width,initial-scale=1.0" />
		
		
<!-- SEO -->
<?php 
	$pageURL =  (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

	$getSeoContent = $this->db->query("select * from seo_settings where page_url='".$pageURL."' and active_flag='Y'")->result_array();
	if( count($getSeoContent) > 0)
	{
		foreach($getSeoContent as $SeoContent)
		{
			if($SeoContent['page_url'] == $pageURL)
			{
				?>
				
				<title><?php echo $SeoContent['page_title']; ?></title>
			
				<meta name="page_title" content="<?php echo $SeoContent['page_title']; ?>"/>
				<meta name="Title" content="<?php echo $SeoContent['meta_title']; ?>"/>
				<meta name="description" content="<?php echo $SeoContent['meta_description']; ?>"/>
				<meta name="keywords" content="<?php echo $SeoContent['meta_keywords']; ?>"/>
				<meta name="author" content="Nalam Dental Care ">
				<meta name="robots" content="index, follow">
				<meta name="revisit-after" content="2 days">
				<link rel="canonical" href="<?php echo $SeoContent['page_url']; ?>" />
				<meta property="og:locale" content="en_US" />
				<meta property="og:type" content="website" />
				<meta property="og:title" content="Nalam Dental Care | Best Dental Clinic in Hosur">
				<meta property="og:description" content="Top-quality dental care including root canal, implants, braces, and teeth whitening at Nalam Dental Care in Hosur.">
				<meta property="og:url" content="https://nalamdentalcare.in">
				<meta property="og:type" content="website">
				<meta property="og:image" content="https://nalamdentalcare.in/assets/frontend/img/pic-4.png"> 
				<?php /*
					<meta name="subject" content="<?php echo $SeoContent['meta_subject']; ?>"/>
				*/ ?>
				<?php
			}
		}
	}
	else
	{ 
		?>
		<title><?php echo PAGE_TITLE; ?></title>
		<meta name="page_title" content="<?php echo PAGE_TITLE; ?>"/>
		<meta name="Title" content="<?php echo META_TITLE; ?>"/>
		<meta name="description" content="<?php echo META_DESCRIPTION; ?>"/>
		<meta name="keywords" content="<?php echo META_KEYWORDS; ?>"/>
		<meta name="author" content="Nalam Dental Care ">
		<meta name="robots" content="index, follow">
		<meta name="revisit-after" content="2 days">
		<link rel="canonical" href="<?php echo PAGE_URL; ?>" />
		<meta property="og:locale" content="en_US" />
		<meta property="og:type" content="website" />
		<meta name="og_title" content="<?php echo OG_TITLE; ?>"/>
		<meta name="og_description" content="<?php echo OG_DESCRIPTION; ?>"/>
		<meta name="og_url" content="<?php echo OG_URL; ?>"/>
		<meta name="og_sitename" content="<?php echo OG_SITENAME; ?>"/>
		<?php /*
			<meta name="subject" content="<?php echo META_SUBJECT; ?>"/> 
		*/ ?>
		<meta property="og:image" content="https://nalamdentalcare.in/assets/frontend/img/pic-4.png" />
		<?php 
	} 
?>
		
			<?php /*
			<meta name="author" content="<?php echo META_AUTHOR; ?>"/>
			<meta name="publisher" content="<?php echo META_PUBLISHER; ?>"/>
			<meta name="copyright" content="<?php echo META_COPYRIGHT; ?>"/>   
			<meta name="owner" content="<?php echo META_OWNER; ?>"/>
			
			<?php echo ANALYTICS_CODE ; */ ?>
		<!-- SEO -->
		<link rel="icon" type="image/png" href="<?php echo base_url();?>uploads/favicon.png" type="image/x-icon">
	
		<link rel="stylesheet" href="<?php echo base_url();?>assets/frontend/css/all.min.css">
		<link href="<?php echo base_url();?>assets/frontend/css/bootstrap.min.css" rel="stylesheet" />	
		<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js" integrity="sha384-vFJXuSJphROIrBnz7yo7oB41mKfc8JzQZiCq4NCceLEaO4IHwicKwpJf9c9IpFgh" crossorigin="anonymous"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/js/bootstrap.min.js" integrity="sha384-alpBpkh1PFOepccYVYDB4do5UnbKysX5WZXm3XxPqe5iKTfUKjNkCk9SaVuEZflJ" crossorigin="anonymous"></script>
		<link rel="stylesheet" href="<?php echo base_url();?>assets/frontend/assets/unicons/css/unicons.css" />
		
		<link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/devicons/devicon@v2.14.0/devicon.min.css">

		<script async defer src="https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY&callback=initMap"></script>

		<link rel="preconnect" href="https://fonts.googleapis.com" crossorigin>
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

		<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/frontend/css/settings.css">
        <link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/frontend/css/layers.css">
        <link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/frontend/css/navigation.css">
        <!-- style sheets and font icons  -->
        <link rel="stylesheet" href="<?php echo base_url();?>assets/frontend/css/vendors.min.css"/>
        <link rel="stylesheet" href="<?php echo base_url();?>assets/frontend/css/icon.min.css"/>
        <link rel="stylesheet" href="<?php echo base_url();?>assets/frontend/css/style.css"/>
        <link rel="stylesheet" href="<?php echo base_url();?>assets/frontend/css/responsive.css"/>
        <link rel="stylesheet" href="<?php echo base_url();?>assets/frontend/css/medical.css" />

		<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700;900&display=swap" rel="stylesheet">
		<link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
		<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
		<link href="https://fonts.googleapis.com/css2?family=Inter:wght@100;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">


        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
		<?php include 'assets/frontend/js/common_script.php';?>
		<script src="https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY&callback=initMap" 
		async defer></script>
		<!-- theme JS files -->
		<link href="<?php echo base_url();?>assets/backend/toastr/toastr.css" type="text/css"  rel="stylesheet" />
		<script src="<?php echo base_url();?>assets/backend/toastr/toastr.js"></script>
		<style>
			.content
			{
				padding: 1.25rem 1.25rem;
				-ms-flex-positive: 1;
				flex-grow: 1;
			}
			
			*, ::after, ::before {
				box-sizing: border-box;
			}

		
		</style>
	</head>
	<?php 
			$segment = $this->uri->segment(1);

			if ($segment == "verification-otp.html" || $segment == "sign-in.html" || $segment == "confirm.html" || $segment == "thankyou.html") {
				include THEME_NAME . "/" . $page_name . '.php';
			}
			else if ($segment == "sports.html" ||  $segment == "web-design-services.html" || $segment == "email-marketing-services.html" || $segment == "google-ads.html" || $segment == "social-media-management-service.html" || $segment =="seo.html" || $segment =="privacy-policy.html" || $segment == "terms-and-conditions.html" || $segment == "refund-policy.html" || $segment == "refund-policy.html" || $segment == "cancellation-policy.html"     ) 
			{
				include THEME_NAME . "/header1.php";
				include THEME_NAME . "/" . $page_name . '.php';
				include THEME_NAME . "/footer.php";
			}
			else if ($segment == "mobile-app-developement.html" || $segment == "website-developement.html" ||  $segment == "web-app-developement.html" || $segment == "job-details.html" || $segment == "blog-details.html"  ) 
			{
				include THEME_NAME . "/header2.php";
				include THEME_NAME . "/" . $page_name . '.php';
				include THEME_NAME . "/footer1.php";
			}
			else {
			
				include THEME_NAME . "/header.php"; 
				include THEME_NAME . "/" . $page_name . '.php';
				include THEME_NAME . "/footer.php"; 
				
			}
			?>
		
		
	</html>

	<link href="<?php echo base_url();?>assets/frontend/css/jquery-ui.css" rel="stylesheet">
	<script src="<?php echo base_url();?>assets/frontend/js/jquery-ui.js"></script>
	<script src="<?php echo base_url();?>assets/backend/toastr/sweetalert2@11.js"></script>
	<script src="https://www.google.com/recaptcha/api.js" async defer></script>
	  <!-- javascript libraries -->
	  <script type="text/javascript" src="<?php echo base_url();?>assets/frontend/js/jquery.js"></script>
        <script type="text/javascript" src="<?php echo base_url();?>assets/frontend/js/vendors.min.js"></script>

        <!-- slider revolution core javaScript files -->
        <script type="text/javascript" src="<?php echo base_url();?>assets/frontend/js/jquery.themepunch.tools.min.js"></script>
        <script type="text/javascript" src="<?php echo base_url();?>assets/frontend/js/jquery.themepunch.revolution.min.js"></script>

        <!-- slider revolution extension scripts. ONLY NEEDED FOR LOCAL TESTING -->
        <!-- <script type="text/javascript" src="revolution/js/extensions/revolution.extension.actions.min.js"></script>
        <script type="text/javascript" src="revolution/js/extensions/revolution.extension.carousel.min.js"></script>
        <script type="text/javascript" src="revolution/js/extensions/revolution.extension.kenburn.min.js"></script>
        <script type="text/javascript" src="revolution/js/extensions/revolution.extension.layeranimation.min.js"></script>
        <script type="text/javascript" src="revolution/js/extensions/revolution.extension.migration.min.js"></script>
        <script type="text/javascript" src="revolution/js/extensions/revolution.extension.navigation.min.js"></script>
        <script type="text/javascript" src="revolution/js/extensions/revolution.extension.parallax.min.js"></script>
        <script type="text/javascript" src="revolution/js/extensions/revolution.extension.slideanims.min.js"></script>
        <script type="text/javascript" src="revolution/js/extensions/revolution.extension.video.min.js"></script> -->
        <!-- Slider Revolution add on files -->
        <script type='text/javascript' src='<?php echo base_url();?>assets/frontend/js/revolution.addon.particles.min.js?ver=1.0.3'></script>
        <!-- Slider's main "init" script -->
        <script type="text/javascript">
            /* https://learn.jquery.com/using-jquery-core/document-ready/ */
            jQuery(document).ready(function () {
                /* initialize the slider based on the Slider's ID attribute from the wrapper above */
                jQuery('#pizza-parlor-slider').show().revolution({
                    sliderType: "standard",
                    /* sets the Slider's default timeline */
                    delay: 9000,
                    /* options are 'auto', 'fullwidth' or 'fullscreen' */
                    sliderLayout: 'fullwidth',
                    /* RESPECT ASPECT RATIO */
                    autoHeight: 'off',
                    /* options that disable autoplay */
                    stopLoop: "on",
                    stopAfterLoops: 0,
                    stopAtSlide: 1,
                    parallax: {
                        type: 'mouse+scroll',
                        origo: 'slidercenter',
                        speed: 400,
                        levels: [2, 4, 6, 8, 10, 12, 14, 16,
                            45, 46, 47, 48, 49, 50, 51, 55],
                        disable_onmobile: 'on'
                    },
                    /* Lazy Load options are "all", "smart", "single" and "none" */
                    lazyType: "smart",
                    spinner: "spinner0",
                    /* DISABLE FORCE FULL-WIDTH */
                    fullScreenAlignForce: 'off',
                    hideThumbsOnMobile: 'off',
                    hideSliderAtLimit: 0,
                    hideCaptionAtLimit: 0,
                    hideAllCaptionAtLilmit: 0,
                    /* [DESKTOP, LAPTOP, TABLET, SMARTPHONE] */
                    responsiveLevels: [1240, 1024, 778, 480],
                    /* [DESKTOP, LAPTOP, TABLET, SMARTPHONE] */
                    gridwidth: [1240, 1024, 778, 480],
                    /* [DESKTOP, LAPTOP, TABLET, SMARTPHONE] */
                    gridheight: [1276, 1000, 960, 720],
                    /* [DESKTOP, LAPTOP, TABLET, SMARTPHONE] */
                    visibilityLevels: [1240, 1024, 1024, 480],
                    fallbacks: {
                        simplifyAll: 'on',
                        nextSlideOnWindowFocus: 'off',
                        disableFocusListener: false
                    },
                });
            });
        </script>
        
        <script type="text/javascript" src="<?php echo base_url();?>assets/frontend/js/main.js"></script>


<!-- Tostr success & Error message start -->
<style>.swal-wide{
    width:850px !important;
}</style>
<script type="text/javascript">
	<?php
		$msg = $this->session->flashdata("success_message");
		$flash_message = $this->session->flashdata("flash_message");
		$error_message = $this->session->flashdata("error_message");
		if( $msg != "" || $flash_message !="")
		{
			if($msg !="")
			{
				$message = $msg;
			}
			else if($flash_message !="")
			{
				$message = $flash_message;
			}
			?>  
			//toastr.success('<?php //echo $message;?>');	
			Swal.fire({
				position: 'top',
				//position: 'top-end',
				icon: 'success',
				title: '<?php echo $message;?>',
				showConfirmButton: false,
				timer: 1500,
				width:'350px'
			});		
			<?php 
		}
		else if( $error_message != '')
		{
			?>  
			toastr.error('<?php echo $error_message;?>');			
			<?php 
		}
	?>
	//Scroll Top Starts
	var btn = $('#scroll-top');

	$(window).scroll(function() {
	if ($(window).scrollTop() > 300) {
		btn.addClass('show');
	} else {
		btn.removeClass('show');
	}
	});

	btn.on('click', function(e) {
	e.preventDefault();
	$('html, body').animate({scrollTop:0}, '300');
	});
	//Scroll Top End

	$(function()
	{
		$('.mobile_vali').keyup(function()
		{
			var yourInput = $(this).val();
			re = /[`~!@#$%^&*()_|+\-=?;:'",.<>\{\}\[\]\\\/a-zA-Z]/gi;
			var isSplChar = re.test(yourInput);
			if(isSplChar)
			{
				var no_spl_char = yourInput.replace(/[`~!@#$%^&*()_|+\-=?;:'",.<>\{\}\[\]\\\/a-zA-Z]/gi, '');
				$(this).val(no_spl_char);
			}
		});
	});
</script>


<!-- <a href="https://api.whatsapp.com/send?phone=919361226692&text=Thanks%20for%20contacting%20us.%20We%20will%20get%20back%20to%20you%20soon!" class="float" target="_blank">
  <i class="fa fa-whatsapp" style="margin-top:15px;"></i>
</a> -->




<style>
	.float{
	position:fixed;
	width:60px;
	height:60px;
	bottom:88px;
	right:23px;
	background-color:#25d366;
	color:#FFF;
	border-radius:50px;
	text-align:center;
  	font-size:30px;
	box-shadow: 2px 2px 3px #999;
    z-index:100;
}

.my-float{
	margin-top:16px;
}
</style>