<?php
	define('IN_TG', true);
	define('SCRIPT', manage);
	session_start();
	//转换成硬路径速度更快
	require dirname(__FILE__).'/include/common.inc.php';
	if(!isset($_COOKIE['username']) || !isset($_SESSION['admin']))
		_location('您没有权限查看此页面', 'index.php');
?>
<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv = "Content-Type" content = "text/html; charset = utf-8"/>
		<title><?php echo $sys['tg_webname'];?>--后台管理中心</title>
		<?php
			require ROOT_PASS.'include/title.inc.php'; 
		?>
	</head>
	<body>
		<?php		 
			require ROOT_PASS.'include/header.inc.php';
		?> 
		<div id = "member">
			<?php
				require ROOT_PASS.'include/manage.inc.php'; 
			?>
			<div id = "member_main">
				<h2>后台管理中心</h2>
				<dl>
					<dd>服务器主机名称:		<?php echo $_SERVER["SERVER_NAME"];?> </dd>
					<dd>服务器版本:	<?php echo $_ENV["OS"];?></dd>
					<dd>通信协议名称及版本:	<?php echo $_SERVER["SERVER_PROTOCOL"];?></dd>
					<dd>服务器IP:		<?php echo $_SERVER["SERVER_ADDR"];?></dd>
					<dd>客户端IP:	<?php echo $_SERVER["REMOTE_ADDR"];?></dd>
					<dd>服务器端口:		<?php echo $_SERVER["SERVER_PORT"];?></dd>
					<dd>客户端端口:		<?php echo $_SERVER["REMOTE_PORT"];?></dd>
					<dd>管理员邮箱:		<?php echo $_SERVER["SERVER_ADMIN"];?></dd>
					<dd>HOST头部内容:	<?php echo $_SERVER["HTTP_HOST"];?></dd>
					<dd>服务器主目录:	<?php echo $_SERVER["DOCUMENT_ROOT"];?></dd>
					<dd>服务器系统盘:	<?php echo $_ENV["SystemRoot"];?></dd>
					<dd>脚本执行的绝对路径:	<?php echo $_SERVER["SCRIPT_FILENAME"];?></dd>
					<dd>Apache及PHP版本:	<?php echo $_SERVER["SERVER_SOFTWARE"];?></dd>
				</dl>				
			</div>
		</div>
		<?php
			require ROOT_PASS.'include/footer.inc.php';
		?>
	</body>
</html>