<?php

class HomeController
{
	public  $model;

	function __construct()
	{
		$action = getIndex('action','index');		

		// Check middleware User
		$except = array('callback','index','postLogin','getRegisterForSeeker','postRegisterForSeeker','postRegisterForRecruiter','getRegisterForRecruiter');

		//Xử lý ngoài hệ thống
		if(in_array($action, $except))	$this->middleware();
		// Xử lý trong hệ thống
		else{
			if(!isset($_SESSION['user'])){
				$redirect = BASE_URL;
				$this->redirectTo($redirect);
			}
			// Chưa xử lý xung đột trong hệ thống : NTV có thể wa trang NTD :) khi fix link
		}

		if (method_exists($this,$action))
			$this->$action();
		else {
			echo "Chưa xây dựng method $action "; exit;
		}
	}

	function middleware(){
		if(isset($_SESSION['user'])){
			if($_SESSION['user']['loai_tk'] == 1){
				$redirect =  BASE_URL.'/?controller=NguoiTimViec&action=home';
			} 
			else if($_SESSION['user']['loai_tk'] == 2){
				$redirect =  BASE_URL.'/?controller=NhaTuyenDung&action=home';	
			} 

			// Nếu tài khoản chưa kích hoạt thì ko cho đăng nhập vào hệ thống
			if(strcasecmp($_SESSION['user']['trangthai'],'kích hoạt')){
				$msg = "tài khoản ".$_SESSION['user']['trangthai'].' !';
				unset($_SESSION['user']);
				$_SESSION['error'] = $msg;
				$redirect = BASE_URL;
			}

			if(isset($redirect)) $this->redirectTo($redirect);
			else{
				echo "Lỗi chuyển trang!"; exit;
			}
		}
	}

	function redirectTo($redirect){
		// header("location:$redirect");
		echo "<script> location.href = '$redirect'</script>";
		exit;
	}

	function index(){
		include 'View/login.php';
	}
}