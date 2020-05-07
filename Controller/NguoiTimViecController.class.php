<?php
class NguoiTimViecController extends HomeController
{
	function __construct(){
		// Check loại tài khoản
		if(isset($_SESSION['user'])){
			// Tài khoản là nhà tuyển dụng thì chuyển về home
			if($_SESSION['user']['loai_tk'] == 2){
				$redirect =  BASE_URL.'/?controller=NhaTuyenDung&action=home';
				$this->redirectTo($redirect);
			}
		}
		parent::__construct();
	}

	function home(){
		// lấy thông tin tài khoản từ email
		$email = $_SESSION['user']['email'];
		$model_ntv = new NguoiTimViecModel();
		$profile = $model_ntv->getProfile($email);

		// lấy ngành nghề từ hồ sơ
		if($profile == null) $search_job = '';
		else $search_job = $profile['nganhnghe'];
		// echo $search_job;

		$model_ttd = new TinTuyenDungModel();

		$list_tintuyendung = $model_ttd->getDataForSeeker($search_job);
		
		// var_dump($list_tintuyendung);
		include 'View/NguoiTimViec/home.php';
	}

	public function getEditFile(){
		// Lấy hồ sơ cũ cần email để truy xuất hồ sơ
		$email = $_SESSION['user']['email'];
		$model_ntv = new NguoiTimViecModel();
		$profile = $model_ntv->getProfile($email);

		// var_dump($profile);
		$model_bc = new BangCapModel();
		$model_cb = new CapbacModel();

		$data = file_get_contents(BASE_URL."/Assets/resources/cities.json");

		// city, province , area , population
		$city_list = json_decode($data);
		$degree_list = $model_bc->getAll();
		$rank_list = $model_cb->getAll();

		include 'View/NguoiTimViec/edit_file.php';
	}

	public function postEditFile(){
		$name = postIndex('ten') ;
		$job = postIndex('nganhnghe');
		$exp = postIndex('sonamkn');
		$rank = postIndex('capbac');
		$degree = postIndex('bangcap');
		$city = postIndex('tinhthanhpho');

		$email = $_SESSION['user']['email'];
		$model_ntv = new NguoiTimViecModel();
		$profile = $model_ntv->getProfile($email);

		if($profile == null){
			// Tạo mới hồ sơ
			$kq = $model_ntv->createProfile($email,$name,$degree,$rank,$exp,$job,$city);
		}
		else{
			// Cập nhật hồ sơ
			$kq = $model_ntv->updateProfile($email,$name,$degree,$rank,$exp,$job,$city);
		}

		if($kq == 1) $_SESSION['success'] = 'Chỉnh sửa hồ sơ thành công!';
		else $_SESSION['error'] = 'Chỉnh sửa hồ sơ thất bại!';

		$redirect = BASE_URL.'/?controller=NguoiTimViec&action=getEditFile';
		$this->redirectTo($redirect);
		// echo $name.$job.$exp.$degree.$city.$rank;
	}

	function view(){
		$id = getIndex('id');
		$model_ttd = new TinTuyenDungModel();
		$tintuyendung = $model_ttd->getInfo($id);
		// var_dump($tintuyendung);

		$model_ntd = new NhaTuyenDungModel();
		$nhatuyendung = $model_ntd->getProfile($tintuyendung['ntd_id']);
		// var_dump($nhatuyendung);
		include 'View/NguoiTimViec/view.php';
	}

	function getApply(){
		$email = $_SESSION['user']['email'];
		$model_ntv = new NguoiTimViecModel();
		$profile = $model_ntv->getProfile($email);

		$model_bc = new BangCapModel();
		$model_cb = new CapbacModel();

		$data = file_get_contents(BASE_URL."/Assets/resources/cities.json");

		$city_list = json_decode($data);
		$degree_list = $model_bc->getAll();
		$rank_list = $model_cb->getAll();
		include 'View/NguoiTimViec/apply.php';
	}

	function postApply(){
		$id_ttd = postIndex('id');
		$email = $_SESSION['user']['email'];
		$name = postIndex('ten') ;
		$job = postIndex('nganhnghe');
		$exp = postIndex('sonamkn');
		$rank_id = postIndex('capbac');
		$degree_id = postIndex('bangcap');
		$city = postIndex('tinhthanhpho');

		// Chuyển rank_id và degree_id thành chuỗi
		$model_cb = new CapbacModel();
		$rank = $model_cb->getName($rank_id);
		$model_bc = new BangCapModel();
		$degree = $model_bc->getName($degree_id);
		// Needed: id người tìm việc, id tin tuyển dụng
		$model_hsxv = new HoSoXinViecModel();
		$result = $model_hsxv->createProfile($id_ttd,$email,$name,$job,$exp,$rank,$degree,$city);

		if($result == 1) $_SESSION['success'] = "Nộp hồ sơ thành công! Hãy vào mục quản lý hồ sơ xin việc để kiểm tra!";
		else if($result == -1) $_SESSION['error'] = "Bạn đã nộp hồ sơ xin việc ở tin tuyển dụng này rồi! Xin kiểm tra lại mục quản lý hồ sơ xin việc";
		else $_SESSION['error'] = "Lỗi tạo hồ sơ xin việc!";

		$redirect = BASE_URL;
		$this->redirectTo($redirect);
		// echo $id_ttd.$name.$job.$exp.$degree.$city.$rank;

		// ***: Chưa xử lý việc thông báo cho nhà tuyển dụng
	}

	function managerProfile(){
		$model_hsxv = new HoSoXinViecModel();
		// Needed: id người tìm việc
		$email = $_SESSION['user']['email'];
		$ds_hoso = $model_hsxv->getListProfile($email);
		// var_dump($ds_hoso);
		include 'View/NguoiTimViec/manager_profile.php';

		// ***: Có nên thêm chức năng xoá, sửa , xem chí tiết profile ko?
	}

	function deleteProfile(){
		// Needed: id người tìm việc, id nhà tuyển dụng
		$email = $_SESSION['user']['email'];
		$id = getIndex('id');
		// echo $email.$id;
		$model_hsxv = new HoSoXinViecModel();
		$result = $model_hsxv->delete($id,$email);
		if($result == 1) $_SESSION['success'] = "Xoá hồ sơ xin việc của tin tuyển dụng $id rồi!";
		else $_SESSION['error'] = "Gặp sự cố không may rồi sư huynh ơi!";

		$redirect = BASE_URL.'/?controller=NguoiTimViec&action=getManagerProfile';
		$this->redirectTo($redirect);
	}
}