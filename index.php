<?php
include "config.php";
include "function.php";
if (!isset($_SESSION)) session_start();

spl_autoload_register('loadClass');
$controller = (isset($_GET['controller'])?$_GET['controller']:'Home').'Controller';

//echo $controller;
$c = new $controller();

//$x = new ABC();
