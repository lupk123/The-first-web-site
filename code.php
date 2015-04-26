<?php
	session_start();
	define('IN_TG', true);
	define('SCRIPT', face);
	//转换成硬路径速度更快
	require dirname(__FILE__).'/include/common.inc.php';
	//默认验证码长度是75 宽度25 个数是4, code的参数我们可以自己更改, 6个时推荐125*25 8个时推荐175*25 以此类推
	//flag表示是否要边框，要的话为1不要为0 默认是0
	$num = 4;
	$width = 75;
	$height = 25;
	$flag = 0;
	code($width, $height, $num, $flag);
?>