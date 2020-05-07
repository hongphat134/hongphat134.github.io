<?php
class NhaTuyenDungController extends HomeController
{
	function __construct(){
		// Check loại tài khoản
		if(isset($_SESSION['user'])){
			// Tài khoản là người tìm việc thì chuyển về home nhà tuyển dụng
			if($_SESSION['user']['loai_tk'] == 1){
				$redirect =  BASE_URL.'/?controller=NguoiTimViec&action=home';
				$this->redirectTo($redirect);
			}
		}
		parent::__construct();
	}

	function home($dshs = null,$cv = null,$kn = null, $bc = null, $tp = null){
		$model_bc = new BangCapModel();
		
		$data = file_get_contents(BASE_URL."/Assets/resources/cities.json");

		// city, province , area , population
		$city_list = json_decode($data);
		$degree_list = $model_bc->getAll();

		include 'View/NhaTuyenDung/home.php';
	}

	public function getEditFile(){
		$data = file_get_contents(BASE_URL."/Assets/resources/cities.json");
		$city_list = json_decode($data);

		$email = $_SESSION['user']['email'];
		$model_ntd = new NhaTuyenDungModel();
		$profile = $model_ntd->getProfile($email);

		include 'View/NhaTuyenDung/edit_file.php';
	}

	public function postEditFile(){
		$name = postIndex('ten');
		$city = postIndex('tinhthanhpho');
		$address = postIndex('diachi');
		$contact_name = postIndex('tennlh');
		$contact_phone = postIndex('sdt');
		$civil_scale = postIndex('quymods');

		echo $name.$city.$contact_phone.$contact_name.$civil_scale;
		$model_ntd = new NhaTuyenDungModel();

		$email = $_SESSION['user']['email'];
		$profile = $model_ntd->getProfile($email);

		if($profile == null){
			// Tạo mới hồ sơ
			$kq = $model_ntd->createProfile($email,$name,$address,$city,$contact_name,$contact_phone,$civil_scale);
		}
		else{
			// Cập nhật hồ sơ
			$kq = $model_ntd->updateProfile($email,$name,$address,$city,$contact_name,$contact_phone,$civil_scale);
		}

		if($kq == 1) $_SESSION['success'] = 'Chỉnh sửa hồ sơ thành công!';
		else $_SESSION['error'] = 'Chỉnh sửa hồ sơ thất bại!';

		$redirect = BASE_URL.'/?controller=NhaTuyenDung&action=getEditFile';
		$this->redirectTo($redirect);
	}

	function search(){
		$job = getIndex('nganhnghe','all');
		$exp = getIndex('kinhnghiem','all');
		$degree = getIndex('bangcap');
		$city = getIndex('tinhthanhpho');

		if($job == '') $job = 'all';
		if($exp == '') $exp = 'all';
		// tìm hồ sơ ở người tìm việc hay hồ sơ xin việc?

		// TH 1: Tìm ở người tìm việc
		$model_ntv = new NguoiTimViecModel();
		// ds tìm kiếm theo tiêu chí bao gồm:
		// những công việc tương tự
		// số năm kinh nghiệm từ $exp trở lên
		// bằng cấp và tỉnh thành phố theo yêu cầu
		$dshs = $model_ntv->search($job,$exp,$degree,$city);

		// var_dump($dshs);
		$this->home($dshs,$job,$exp,$degree,$city);
		// self::home($dshs);
		// echo $job.'-'.$exp.'-'.$degree.'-'.$city;

		// TH 2: Tìm ở hồ sơ xin việc
	}


	function managerRecruitment(){
		$model_ttd = new TinTuyenDungModel();
		// Needed: id nhà tuyển dụng

		$email = $_SESSION['user']['email'];
		// echo $email;
		$dsttd = $model_ttd->getReclist($email);
		// var_dump($dsttd);
		include 'View/NhaTuyenDung/manager_recruitment.php';
	}

	// function này bao gồm việc tạo và cập nhật tin tuyển dụng
	function getPostRec(){
		// Kiểm tra xem đã set up hồ sơ chưa?
		// Needed: id nhà tuyển dụng
		$email = $_SESSION['user']['email'];
		$model_ntd = new NhaTuyenDungModel();

		$hoso = $model_ntd->getProfile($email);

		if($hoso == null){
			$_SESSION['error'] = 'Bạn hãy điền hồ sơ trước khi đăng tin nhé!';
			$redirect = BASE_URL.'/?controller=NhaTuyenDung&action=getEditFile';
			$this->redirectTo($redirect);
		}

		// Nếu có id tức là dùng chức năng cập nhật. Ngc lại, dùng chức năng tạo tin tuyển dụng
		$model_ttd = new TinTuyenDungModel();
		$ttd_id = getIndex('id');
		if($ttd_id != '') $ttd = $model_ttd->getInfo($ttd_id);

		$model_bc = new BangCapModel();

		$data = file_get_contents(BASE_URL."/Assets/resources/cities.json");

		// city, province , area , population
		$city_list = json_decode($data);
		$degree_list = $model_bc->getAll();

		include 'View/NhaTuyenDung/post_recruitment.php';
	}

	function postPostRec(){
		$email = $_SESSION['user']['email'];
		$job = postIndex('nganhnghe');
		$exp = postIndex('sonamkn');
		$degree = postIndex('bangcap');
		$city = postIndex('tinhthanhpho');
		$salary = postIndex('mucluong');

		$id = postIndex('id');
		echo $id;
		$model_ttd = new TinTuyenDungModel();

		if($id == '') $result = $model_ttd->create($email,$job,$exp,$degree,$city,$salary);
		else $result = $model_ttd->update($id,$email,$job,$exp,$degree,$city,$salary);

		if($result == 1) $_SESSION['success'] = "Đăng tin thành công! Hãy kiểm tra lại trong mục quản lý tin tuyển dụng!";
		else $_SESSION['error'] = "Đăng tin thất bại! Đã xảy ra sự cố khi đăng tin tuyển dụng!";

		// $this->managerRecruitment();
		$redirect = BASE_URL.'/?controller=NhaTuyenDung&action=managerRecruitment';
		$this->redirectTo($redirect);
	}

	function viewProfiles(){
		// Lấy ds hồ sơ xin việc
		// Needed: id tin tuyển dụng
		$ttd_id = getIndex('id');
		$model_hsxv = new HoSoXinViecModel();
		$dshs = $model_hsxv->getListAppliedFile($ttd_id);
		// var_dump($dshs);
		include 'View/NhaTuyenDung/view_list_applied.php';
	}

	function getApplied(){
		$ttd_id = getIndex('id1');
		$ntv_id = getIndex('id2');

		$model_hsxv = new HoSoXinViecModel();

		$profile = $model_hsxv->getProfile($ttd_id,$ntv_id);

		// var_dump($profile);
		include 'View/NhaTuyenDung/view_applied.php';
	}

	// Này chính là chức năng ứng tuyển
	function postApplied(){
		echo "Đùi bầu";
	}
}