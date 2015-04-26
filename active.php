<?php
define('IN_TG', true);
define('SCRIPT', active);
//转换成硬路径速度更快
require dirname(__FILE__).'/include/common.inc.php';
//开始激活处理
if(!isset($_GET['active']))
{
	alert_back('非法操作!');
	exit();
}
if(isset($_GET['action']) && $_GET['action'] == 'ok')
{
	$active = mysql_string($_GET['active']);
	$sql = "SELECT tg_active FROM tg_user WHERE tg_active = '$active' LIMIT 1";
	if(fetch_array($sql))
	{//将tg_active设置为空
		$sql = "UPDATE tg_user SET tg_active = NULL WHERE tg_active = '$active' LIMIT 1";
		query($sql);
		if(affected_rows() == 1)
		{
			close();
			_location('账户激活成功', 'login.php');
		}
		else
		{
			close();
			_location('账户激活失败', 'register.pho');	
		}
	}
	else
	{
		alert_back('非法操作!');	
	}
}
?>
<!DOCTYPE html>
<html>
<head>
	<meta http-equiv = "Content-Type" content = "text/html; charset = utf-8"/>
	<title><?php echo $sys['tg_webname'];?>--注册界面</title>
</head>
<?php
	require ROOT_PASS.'include/title.inc.php'; 
?>
<script type="text/javascript" src = "js/register.js"></script>
<body>
<?php
	require ROOT_PASS.'include/header.inc.php';
?>
<div id = "active">
	<h2>激活账户</h2>
	<p>本页面模拟您的邮件系统，点击以下链接激活您的账户。</p>
	<p>
		<a href = "active.php?action=ok&amp;active=<?php echo $_GET['active']?>">
			<?php echo 'http://'.$_SERVER["HTTP_HOST"].$_SERVER['PHP_SELF']?>?action=ok&amp;active=<?php echo $_GET['active']?>
		</a>
	</p>
</div>
<?php
	require ROOT_PASS.'include/footer.inc.php';
?>
</body>
</html>