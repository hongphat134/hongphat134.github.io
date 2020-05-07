<?php
class BangCapModel extends DbModel
{
	 public function getAll(){
	 	return $this->getTable('loaibangcap');
	 }

	 public function getName($id){
	 	$sql = "SELECT `ten` FROM `loaibangcap`
	 			WHERE `loaibangcap`.`id` = $id";
	 	return $this->selectQuery($sql)[0]['ten'];
	 }
}