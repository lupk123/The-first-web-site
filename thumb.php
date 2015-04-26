<?php
	define('IN_TG', true);
	define('SCRIPT', thumb);
	session_start();
	//转换成硬路径速度更快
	require dirname(__FILE__).'/include/common.inc.php';
	
	if(isset($_GET['name']) && isset($_GET['width']) && isset($_GET['height']))
		thumb($_GET['name'], $_GET['width'], $_GET['height']);
	
?>