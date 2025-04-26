<?php
//SET THE LICENSE INFO
#$license_owner = 'VASANTA BHAVAN VEGETARIAN RESTAURANT - 1 WebApp Lic - 1 WebServer Lic';
#$license_key = '282DDDA60D0D2E04AA62A99D36AD4929BCC53A37';
#$license_key = '99C49B9682E84C09E7BB61D0AC5D2580B3337827';

$license_owner = 'JESPER APPS SOFTWARE SERVICES PRIVATE LIMITED - 1 WebApp Lic - 1 WebServer Lic - (Basic Edition)';
#$license_key = '71b1c3473c58aff7c8f101c35be15ad958fa02114136e26cd9b7238e98be7e6f';
$license_key = '99C49B9682E84C09E7BB61D0AC5D2580B3337827';

//DO NOT MODIFY THE FOLLOWING CODE
$timestamp = $_GET['timestamp'];
$license_hash = hash('sha256', $license_key . $timestamp, false);
$resp = $license_owner . '|' . $license_hash;

ob_start();
ob_clean();
header('Content-type: text/plain');
echo $resp;
ob_end_flush();
exit();

