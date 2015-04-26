<?php
define('IN_TG', true);
//转换成硬路径速度更快
require dirname(__FILE__).'/include/common.inc.php';
session_start();
unsetcookie();
if(isset($_COOKIE['article_time']))
	setcookie('article_time', '', time()-1);
if(isset($_COOKIE['post_time']))
	setcookie('post_time', '', time()-1);
?>