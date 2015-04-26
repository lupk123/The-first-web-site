<?php
	$fp = @fopen('new.xml', 'w');
	if(!$fp)
		exit('系统错误， 文件不存在');
	flock($fp, LOCK_EX);
	$string = "<?xml version = \"1.0\" encoding = \"utf-8\"?>\r\n";
	fwrite($fp, $string, strlen($string));
	$string = "<vip>\r\n";
	fwrite($fp, $string, strlen($string));
	$string = "\t<id>5</id>\r\n";
	fwrite($fp, $string, strlen($string));
	$string = "\t<username>zz</username>\r\n";
	fwrite($fp, $string, strlen($string));
	$string = "\t<sex>男</sex>\r\n";
	fwrite($fp, $string, strlen($string));
	$string = "\t<face>face/1.jpg</face>\r\n";
	fwrite($fp, $string, strlen($string));	
	$string = "\t<email>yc60@126.com</email>\r\n";
	fwrite($fp, $string, strlen($string));
	$string = "\t<url>http:www.baidu.com</url>\r\n";
	fwrite($fp, $string, strlen($string));
	$string = "</vip>\r\n";
	fwrite($fp, $string, strlen($string));
	flock($fp, LOCK_UN);
	fclose($fp);
?>
