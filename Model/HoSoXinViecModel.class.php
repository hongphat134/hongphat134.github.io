<?php
class HoSoXinViecModel extends DbModel
{
	private $table = 'hosoxinviec';
	// KT 1 người tìm việc chỉ nộp đơn xin việc vào 1 tin tuyển dụng 1 lần
	// 1 tin tuyển dụng sẽ có nhiều người tìm việc
	public function isCreated($email,$ttd_id){
		$sql = "SELECT * FROM `$this->table`
				WHERE `$this->table`.`ntv_id` LIKE '$email' AND `$this->table`.`tintuyendung_id` = $ttd_id";
		return empty($this->selectQuery($sql));
	}

	public function createProfile($id_ttd,$email,$ten,$nganhnghe,$kinhnghiem,$capbac,$bangcap,$tinhtp){
		if($this->isCreated($email,$id_ttd)){
			$ngaytao = date("Y-m-d H:i:s");
			$sql = "INSERT INTO `$this->table`(`hoten`, `dcthuongtru`, `nganhnghe`, `bangcap`, `capbac`, `sonamkinhnghiem`, `tintuyendung_id`, `ntv_id`, `capnhathoso`) 
				VALUES ('$ten','$tinhtp','$nganhnghe','$bangcap','$capbac','$kinhnghiem','$id_ttd','$email','$ngaytao')";
			return $this->updateQuery($sql);	
		}
		else return -1;
	}

	public function getListProfile($email){
		$sql = "SELECT `tintuyendung`.`id`
				FROM `tintuyendung` JOIN `$this->table` on `tintuyendung`.`id` = `$this->table`.`tintuyendung_id`
				WHERE `$this->table`.`ntv_id` LIKE '$email'";
		return $this->selectQuery($sql);
	}

	public function delete($ttd_id,$ntv_id){
		$sql = "DELETE FROM `$this->table`
				WHERE `$this->table`.`tintuyendung_id` = '$ttd_id' AND `$this->table`.ntv_id LIKE '$ntv_id'";
		return $this->updateQuery($sql);
	}

	public function getListAppliedFile($ttd_id){
		$sql = "SELECT * FROM `$this->table`
				WHERE `$this->table`.`tintuyendung_id` = $ttd_id";
		return $this->selectQuery($sql);
	}

	public function getProfile($ttd_id,$ntv_id){
		$sql = "SELECT * FROM `$this->table`
				WHERE `$this->table`.`tintuyendung_id` = $ttd_id AND `$this->table`.`ntv_id` LIKE '$ntv_id'";
		return empty($this->selectQuery($sql))?null: $this->selectQuery($sql)[0];
	}
}