<?php
function getIndex($index, $default='')
{
	return isset($_GET[$index])?$_GET[$index]:$default;
}
function postIndex($index, $default='')
{
	return isset($_POST[$index])?$_POST[$index]:$default;
}

function loadClass($c)  //$c= 'ABC'
{
	if (is_file("Library/$c.class.php"))
		include "Library/$c.class.php";

	else if(is_file("Controller/$c.class.php"))
		include "Controller/$c.class.php";

	else if(is_file("Model/$c.class.php"))
		include "Model/$c.class.php";
	
	else {
		echo "Kg co class:::: $c"; exit;
	}

}