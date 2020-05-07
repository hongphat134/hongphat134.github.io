<?php
class NguoiTimViecModel extends DbModel
{	
	private $table = 'nguoitimviec';
	public function getProfile($email){
		$sql = "SELECT * FROM `$this->table`
				WHERE `$this->table`.`tk_id` LIKE '$email'";
		return empty($this->selectQuery($sql))?null :$this->selectQuery($sql)[0];
	}

	public function createProfile($email,$ten,$bangcap,$capbac,$sonamkn,$nganhnghe,$tinhtp){

		$ngaytao = date("Y-m-d H:i:s");
		// echo $email.$ten.$bangcap.$capbac.$sonamkn.$nganhnghe.$tinhtp.$ngaytao;
		$sql = "INSERT INTO `$this->table`(`tk_id`, `hoten`, `dcthuongtru`, `nganhnghe`, `sonamkinhnghiem`,`bangcap_id`, `capbac_id`, `capnhathoso`) 
				VALUES ('$email','$ten','$tinhtp','$nganhnghe','$sonamkn', '$bangcap', '$capbac','$ngaytao')";
		return $this->updateQuery($sql);
	}

	public function updateProfile($email,$ten,$bangcap,$capbac,$sonamkn,$nganhnghe,$tinhtp){
		$ngaytao = date("Y-m-d H:i:s");

		// echo $email.$ten.$bangcap.$capbac.$sonamkn.$nganhnghe.$tinhtp.$ngaytao;
		$sql = "UPDATE `$this->table` SET `hoten`= '$ten',`dcthuongtru`= '$tinhtp',`nganhnghe`= '$nganhnghe',`capbac_id`= '$capbac',`bangcap_id`= '$bangcap',`sonamkinhnghiem`= '$sonamkn',`capnhathoso`= '$ngaytao'
				WHERE `$this->table`.`tk_id` LIKE '$email'";
		// UPDATE `nguoitimviec` SET `hoten`= 'Hùng',`dcthuongtru`='Hà Nội',`nganhnghe`='Lập trình viên',`capbac_id`= '2',`bangcap_id`= '1',`sonamkinhnghiem`= '2',`capnhathoso`= '2015-04-05 12:12:12'
		// WHERE `nguoitimviec`.`tk_id` LIKE 'dh51603902@student.stu.edu.vn'
		return $this->updateQuery($sql);
	}

	public function search($congviec,$sonamkn,$bangcap,$tinhtp){
		$sql = "SELECT `nganhnghe`,`sonamkinhnghiem`,`dcthuongtru`,`loaibangcap`.`ten` 
				FROM `loaibangcap` JOIN `$this->table` on `loaibangcap`.`id` = `$this->table`.`bangcap_id`
				WHERE 1 ";
		if($congviec != 'all'){
			$sql .= "AND `$this->table`.`nganhnghe` LIKE '%$congviec%' ";
		}
		if($sonamkn != 'all'){
			$sql .= "AND `$this->table`.`sonamkinhnghiem` >= $sonamkn ";	
		}
		if($bangcap != 'all'){
			$sql .= "AND `$this->table`.`bangcap_id` = $bangcap ";
		}
		if($tinhtp != 'all'){
			$sql .= "AND `$this->table`.`dcthuongtru` LIKE '$tinhtp' ";
		}
		// echo $sql;
		return $this->selectQuery($sql);
	}			
}