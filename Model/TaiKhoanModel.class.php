<?php
class TaiKhoanModel extends DbModel
{
	public function encrypt($pwd){
		return md5("D".$pwd);
	}

	public function login($email,$pwd){
		$encode_pwd = $this->encrypt($pwd);
		$sql = "SELECT *
				FROM `taikhoan`
				WHERE `taikhoan`.`email` LIKE '$email' AND `taikhoan`.`password` LIKE '$encode_pwd'";
		return $this->selectQuery($sql);
	}

	public function register($name,$email,$pwd,$loai_tk = 1,$trangthai = "kích hoạt"){
		$ngaytao = date("Y-m-d H:i:s");
		$encode_pwd = $this->encrypt($pwd);

		// echo $email.$pwd.$loai_tk.$ngaytao.'<hr>';
		$sql = "INSERT INTO
				`taikhoan`(`email`,`password`,`ten`,`trangthai`,`loaitk_id`,`remember_token`,`created_at`,`updated_at`)
				VALUES ('$email','$encode_pwd','$name','$trangthai','$loai_tk','','$ngaytao','$ngaytao')";

		// INSERT INTO
		// `taikhoan`(`email`,`password`,`loaitk_id`,`remember_token`,`created_at`,`updated_at`)
		// VALUES ('tui@la.ai','245134145','1','','2015-01-01 15:14:13','2015-01-01 15:14:13')
		return $this->updateQuery($sql);
	}
}