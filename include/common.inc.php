<?php
	//防止恶意调用
	if(!defined("IN_TG"))
		exit('error access!'); 	
	define('ROOT_PASS', substr(dirname(__FILE__), 0, -7));
	if(PHP_VERSION < '4.1.0')
		exit('low php version');
	//设置页面编码
	header('Content-Type: text/html; charset=utf-8');		
	require ROOT_PASS.'include\global.func.php';
	require ROOT_PASS.'include\mysql.func.php';
	 	
	define('START_TIME', runtime());	
	//创建一个自动转义常量
	define('GPG', get_magic_quotes_gpc());

	//数据库连接
	define('DB_HOST', 'localhost');
	define('DB_USER', 'root');
	define('DB_PWD', 'zxj1121');	
	define('DB_NAME', 'testguest');	
	
	//初始化数据库
	mysql_conn();//连接数据库	
	select_db();//选择一款数据库
	query_charset(); //设置字符集
	
	//短信提醒 全站范围
	$mes = fetch_array("SELECT COUNT(tg_id) AS num FROM tg_message WHERE tg_state = 0 AND tg_touser = '{$_COOKIE['username']}'");
	
	if($mes['num'])
		$GLOBALS['message'] = '<strong>(<a href = "member_message_receive">'.$mes['num'].')</a></strong>';
	else 
		$GLOBALS['message'] = '<strong><a href = "member_message_receive">(0)</a></strong>';
	//网站设置初始化
	if(!!$sys = fetch_array("SELECT * FROM tg_system WHERE tg_id = 1 LIMIT 1"))
	{
		if(isset($_COOKIE['skin']))
			$sys['tg_skin'] = $_COOKIE['skin'];
		html($sys);
	}
	else
	{
		exit('系统表读取异常，请联系管理员修复');
	}
?>