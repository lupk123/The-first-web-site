<?php
define('IN_TG', true);
define('SCRIPT', flower);
//转换成硬路径速度更快
require dirname(__FILE__).'/include/common.inc.php';
$skinurl = $_SERVER['HTTP_REFERER']; //获取上一页地址
if(empty($skinurl) || !isset($_GET['id']))
{
alert_back('非法操作');	
}
$arr = array();
$arr[0] = 1;
$arr[1] = 2;
$arr[2] = 3;
if(in_array($_GET['id'], $arr))
{
	setcookie('skin', $_GET['id']);
	header("Location: ".$skinurl);
}
?>