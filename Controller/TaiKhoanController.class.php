<?php
/**
 * Class Home
 * index.php?controller=Home&action=index
 */
class TaiKhoanController extends HomeController
{
	function __construct(){
		$this->model = new TaiKhoanModel();
		parent::__construct();
	}

	function setUserSession($email,$ten,$loaitaikhoan,$trangthai){
		$_SESSION['user'] = array('email' => $email,
								'ten' => $ten,
								'loai_tk' => $loaitaikhoan,
								'trangthai' => $trangthai);
	}

	public function postLogin(){
		$email = postIndex('email');
		$pwd = postIndex('pwd');

		$result = $this->model->login($email,$pwd);

		if(!empty($result)){
			$user = $result[0];
			$this->setUserSession($user['email'],$user['ten'],$user['loaitk_id'],$user['trangthai']);
			$redirect = BASE_URL;
			$this->redirectTo($redirect);
		}
		else{
			$msg = 'Đăng nhập thất bại!';
			$_SESSION['error'] = $msg;
			$redirect =  BASE_URL;
			$this->redirectTo($redirect);
		} 
	}

	function getRegisterForSeeker(){
		include 'View/NguoiTimViec/register.php';
	}

	function postRegisterForSeeker(){
		$name = postIndex('name');
		$email = postIndex('email');
		$pwd = postIndex('pwd');

		// echo $email.$pwd.$loai_tk;
		$result = $this->model->register($name,$email,$pwd);

		// echo $result;
		if($result == 1){
			//1 là người tìm việc, 2 là nhà tuyển dụng
			$this->setUserSession($email,$name,1,'kích hoạt');

			//Chuyển trang sang index tương ứng
			$redirect =  BASE_URL;
			$this->redirectTo($redirect);
		}
		else{
			$msg = 'Đăng ký thất bại!';
			$_SESSION['error'] = $msg;
			$redirect =  BASE_URL.'/?controller=TaiKhoan&action=getRegister';
			$this->redirectTo($redirect);
		}
	}

	function getRegisterForRecruiter(){
		include 'View/NhaTuyenDung/register.php';
	}

	function postRegisterForRecruiter(){
		echo "Đã vào";
		$name = postIndex('name') ;
		$email = postIndex('email') ;
		$pwd = postIndex('pwd') ;
		echo $name.$email.$pwd;

		//default 1! 1 là người tìm việc, 2 là nhà tuyển dụng
		$result = $this->model->register($name,$email,$pwd,2,"chưa kích hoạt");

		// echo $result;
		if($result == 1){
			// Thông báo đăng ký thành công
			// => Thông báo ở đâu?
			$msg = "Bạn đã nộp đơn đăng ký nhà tuyển dụng thành công! Xin hãy đợi quản trị viên phê duyệt!";
			$_SESSION['success'] = $msg;
			$redirect =  BASE_URL;
			$this->redirectTo($redirect);
		}
		else{
			$msg = 'Đăng ký thất bại!';
			$_SESSION['error'] = $msg;
			$redirect =  BASE_URL.'/?controller=TaiKhoan&action=getRegister';
			$this->redirectTo($redirect);
		} 

	}

	function callback(){
		require_once ROOT."/config_render_google.php";

		// Đã từng login gg => BUG
		// if (isset($_SESSION['access_token'])){
		// 	// var_dump($_SESSION['access_token']);
		// 	$gClient->setAccessToken($_SESSION['access_token']);
		// }
		// Vừa thực hiện login gg
		if (isset($_GET['code'])) {		
			$token = $gClient->fetchAccessTokenWithAuthCode($_GET['code']);
			$_SESSION['access_token'] = $token;
		} 
		else {
			$redirect =  BASE_URL;
			$this->redirectTo($redirect);
			exit();
		}

		$oAuth = new Google_Service_Oauth2($gClient);
		$userData = $oAuth->userinfo_v2_me->get();

		$name = $userData['givenName'];
		$email = $userData['email'];
		$pwd = 'default';

		// Tạo tài khoản đẩy lên database
		$result = $this->model->register($name,$email,$pwd);

		$this->setUserSession($email,$name,1,'kích hoạt');
		// var_dump($userData);

		$redirect =  BASE_URL;
		$this->redirectTo($redirect);
		// header('Location: index.php');
	}

	function logout(){
		unset($_SESSION['user']);
		$redirect =  BASE_URL;
		$this->redirectTo($redirect);
	}
}