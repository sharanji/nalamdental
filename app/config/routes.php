<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$route['default_controller'] = 'welcome/index';
$route['login.html'] = 'admin/adminLogin';
$route['404_override'] ='welcome/error';
$route['translate_uri_dashes'] = FALSE;
$route['cms/(:any).html'] = "welcome/cms/$1";
$route['home.html'] = 'welcome/home';
$route['appointment.html'] = 'welcome/appointment';
$route['doctors.html'] = 'welcome/doctors';
$route['treatments.html'] = 'welcome/treatments';
$route['about-us.html'] = 'welcome/aboutus';
$route['thankyou.html'] = 'welcome/thankYou';
$route['blog-details.html/(:any)/(:any)'] = 'welcome/blogDetails/$1/$2';
$route['job-lists.html/(:any)/(:any)'] = 'welcome/jobLists/$1/$2';
$route['job-details.html/(:any)/(:any)'] = 'welcome/jobDetails/$1/$2';
$route['services.html'] = 'welcome/services';
$route['seo.html'] = 'welcome/seo';
$route['pp-tiles.html'] = 'welcome/pptiles';
$route['acrylic-system.html'] = 'welcome/acrylicsystem';
$route['football.html'] = 'welcome/football';
$route['basketball.html'] = 'welcome/basketball';
$route['indoor-sports-halls.html'] = 'welcome/indoorsportshalls';
$route['volley-ball.html'] = 'welcome/volleyball';
$route['badminton.html'] = 'welcome/badminton';
$route['multi-sport.html'] = 'welcome/multisport';
$route['hockey.html'] = 'welcome/hockey';
$route['artificial.html'] = 'welcome/artificial';
$route['bleachers.html'] = 'welcome/bleachers';
$route['pvc.html'] = 'welcome/pvc';
$route['redexim.html'] = 'welcome/redexim';
$route['sports.html'] = 'welcome/sports';
$route['stadium.html'] = 'welcome/stadium';
$route['tennis.html'] = 'welcome/tennis';




$route['digital-marketing.html'] = 'welcome/digitalmarketing';
$route['mobile-app-developement.html'] = 'welcome/mobileAppDevelopement';
$route['website-developement.html'] = 'welcome/websitedevelopement';
$route['web-app-developement.html'] = 'welcome/webappdevelopement';
$route['google-ads.html'] = 'welcome/googleads';
$route['web-design-services.html'] = 'welcome/webdesignservices';
$route['logo-design.html'] = 'welcome/logodesign';
$route['gallery.html'] = 'welcome/gallery';

$route['terms-and-conditions.html'] = 'welcome/termsandconditions';
$route['refund-policy.html'] = 'welcome/refundpolicy';
$route['cancellation-policy.html'] = 'welcome/cancellationpolicy';







$route['contact-us.html'] = 'welcome/contact';
$route['terms-and-conditions.html'] = 'welcome/termsandconditions';
$route['privacy-policy.html'] = 'welcome/privacy_policy';
$route['cookie-policy.html'] = 'welcome/cookiePolicy';


#Dine In Routes
$route['user-login.html'] = 'web_dine_in/login';
$route['user-logout.html'] = 'web_dine_in/logout';
$route['user-verification-otp.html'] = 'web_dine_in/verificationOtp';
$route['items.html'] = 'web_dine_in/items';
$route['items.html/(:any)'] = 'web_dine_in/items/$1';
$route['cancel-otp.html'] = 'web_dine_in/cancelOtp';
$route['vieworders.html'] = 'web_dine_in/vieworders';
$route['vieworders.html/(:any)'] = 'web_dine_in/vieworders/$1';

$route['fine-dine-items.html'] = 'web_fine_dine/fineDine';
$route['fine-dine-items.html/(:any)'] = 'web_fine_dine/fineDine/$1';


















