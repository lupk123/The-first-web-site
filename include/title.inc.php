<?php
	if(!defined("IN_TG"))
		exit('error access!');
	if(!defined('SCRIPT'))
		exit('script error!');
//	define('ROOT_PASS', substr(dirname(__FILE__), 0, -7));	
?>
<link rel = "shortcut icon" href = "cc.ico"/> 
<link rel = "stylesheet" type = "text/css" href = "style/<?php echo $sys['tg_skin'];?>/basic.css"/>
<link rel = "stylesheet" type = "text/css" href = "style/<?php echo $sys['tg_skin'];?>/<?php echo SCRIPT?>.css"/>
