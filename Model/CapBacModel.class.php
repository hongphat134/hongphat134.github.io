<?php
class CapBacModel extends DbModel
{
	 public function getAll(){
	 	return $this->getTable('loaicapbac');
	 }

	 public function getName($id){
	 	$sql = "SELECT `ten` FROM `loaicapbac`
	 			WHERE `loaicapbac`.`id` = $id";
	 	return $this->selectQuery($sql)[0]['ten'];
	 }
}