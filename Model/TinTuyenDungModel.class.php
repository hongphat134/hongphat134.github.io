<?php
class TinTuyenDungModel extends DbModel
{
	private $table = 'tintuyendung';
	public function getDataForSeeker($job = ''){
		// TH ko có ngành nghề
		if(!strcasecmp($job, '')){
			$sql = "SELECT * 
					FROM `tintuyendung`";
		}
		// TH có ngành nghề
		else{
			$sql = "SELECT * 
					FROM `tintuyendung` WHERE `nganhnghe` LIKE '%$job%'";
		}
		return $this->selectQuery($sql);
	}

	// lấy thông tin hiện chi tiết cho người tìm việc nộp hồ sơ
	public function getInfo($id){
		$sql = "SELECT * FROM `tintuyendung`
				WHERE `tintuyendung`.`id` = $id";
		return $this->selectQuery($sql)[0];
	}

	public function getReclist($ttd_id){
		$sql = "SELECT `id`,`nganhnghe`,`bangcap`,`kinhghiem`,`tinhthanhpho`,`mucluong`
				FROM `$this->table`
				WHERE `$this->table`.`ntd_id` LIKE '$ttd_id'";
		return $this->selectQuery($sql);
	}

	public function create($ntd_id,$cv,$knghiem,$bc,$tp,$luong){
		$sql = "INSERT INTO `$this->table`(`nganhnghe`, `bangcap`, `mucluong`, `kinhghiem`, `tinhthanhpho`, `ntd_id`) 
				VALUES ('$cv','$bc','$luong','$knghiem','$tp','$ntd_id')";
		return $this->updateQuery($sql);
	}

	public function update($ttd_id,$ntd_id,$cv,$knghiem,$bc,$tp,$luong){
		$sql = "UPDATE `tintuyendung` 
				SET `nganhnghe`= '$cv',`bangcap`= '$bc',`mucluong`= '$luong',`kinhghiem`='$knghiem',`tinhthanhpho`= '$tp' 
				WHERE `$this->table`.`ntd_id` LIKE '$ntd_id' AND `$this->table`.`id` = $ttd_id";
		return $this->updateQuery($sql);
	}
}