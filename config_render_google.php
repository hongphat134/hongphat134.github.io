<?php 
	// echo __DIR__.'<hr>'.ROOT;
 	require_once 'vendor/autoload.php';

	$gClient = new Google_Client();
	$gClient->setClientId("555302701212-co7r4cvfqnkdu4iaslrn6uvm649t6iec.apps.googleusercontent.com");
	$gClient->setClientSecret("reqvnuzIVEEAcSxKl86YEEEL");
	$gClient->setApplicationName("CPI Login Tutorial");
	$gClient->setRedirectUri("http://localhost/TTTN/?controller=TaiKhoan&action=callback");
	$gClient->addScope("https://www.googleapis.com/auth/plus.login https://www.googleapis.com/auth/userinfo.email");
 ?>