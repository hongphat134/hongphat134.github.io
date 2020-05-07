<?php
class NhaTuyenDungModel extends DbModel
{
	private $table = 'nhatuyendung';
	public function getProfile($email){
		$sql = "SELECT * FROM `$this->table`
				WHERE `$this->table`.`tk_id` LIKE '$email'";
		return empty($this->selectQuery($sql))?null :$this->selectQuery($sql)[0];
	}

	public function createProfile($ntd_id,$tenntd,$diachi,$tp,$tenlh,$sdt,$quymods){
		$ngaytao = date("Y-m-d H:i:s");
		$sql = "INSERT INTO `$this->table`(`tk_id`, `ten`, `diachi`, `tennguoilienhe`, `sdt`, `tinhthanhpho`, `quymodansu`, `capnhathoso`) 
				VALUES ('$ntd_id','$tenntd','$diachi','$tenlh','$sdt','$tp','$quymods','$ngaytao')";
		return $this->updateQuery($sql);
	}

	public function updateProfile($ntd_id,$tenntd,$diachi,$tp,$tenlh,$sdt,$quymods){
		$ngaytao = date("Y-m-d H:i:s");
		$sql = "UPDATE `$this->table` 
				SET `ten`= '$tenntd',`diachi`= '$diachi',`tennguoilienhe`= '$tenlh',`sdt`='$sdt',`tinhthanhpho`='$tp',`quymodansu`='$quymods',`capnhathoso`='$ngaytao' 
				WHERE `tk_id` LIKE '$ntd_id'";
		return $this->updateQuery($sql);
	}
}