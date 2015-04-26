<?php
	define('IN_TG', true);
	define('SCRIPT', message);
	//转换成硬路径速度更快
	require dirname(__FILE__).'/include/common.inc.php';
	session_start();
	
	if($_GET['action'] == 'write')
	{
		if(!$rows = fetch_array("SELECT tg_uniqid FROM tg_user WHERE tg_username = '{$_COOKIE['username']}' LIMIT 1"))
			alert_back('非法操作!');		
		check_uni($rows['tg_uniqid'], $_COOKIE['uniqid']);//验证唯一标示符		
		check_code($_POST['yzm'], $_SESSION['code']); //验证验证码是否正确
		include ROOT_PASS.'include\check.func.php';
		$mess = array();
		$mess['touser'] = $_POST['touser'];
		$mess['fromuser'] = $_COOKIE['username'];
		$mess['content'] = check_content($_POST['content']);
		$mess = mysql_string($mess);
	//	print_r($mess);
		//写入数据库
		query("INSERT INTO tg_message
				(
					 tg_touser, 
					 tg_fromuser,
					 tg_content, 
					 tg_date)
				VALUES
				(
					'{$mess['touser']}', 
					'{$mess['fromuser']}', 
					'{$mess['content']}', 
					NOW()
				)
			 ");
		if(affected_rows() == 1)
		{
			//关闭数据库
			close();
		//	session_des();
			alert_close('发送成功!');
		}
		else
		{
			close();
		//	session_des();
			alert_back('发送失败!');
		}
		exit();
	}
	
	
	if(!isset($_COOKIE['username']))
		alert_close('请先登录!');
	if(!isset($_GET['id']))
		alert_back('非法操作');
	if(! $user = fetch_array("SELECT tg_username FROM tg_user WHERE tg_id = '{$_GET['id']}' LIMIT 1"))
		alert_close('不存在此用户!');	
	$html['touser'] = $user['tg_username'];
	$html = html($html);
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv = "Content-Type" content = "text/html; charset = utf-8"/>
<title><?php echo $sys['tg_webname'];?>--写短信</title>
<?php
	require ROOT_PASS.'include/title.inc.php'; 
?>
<script type="text/javascript" src = "js/code.js"></script>
<script type="text/javascript" src = "js/message.js"></script>
</head>
<body>
<div id = "message">
	<h3>写短信</h3>
	<form method="post" action = "?action=write">
		<input type = "hidden" name = "touser" value = "<?php echo $html['touser'];?>"/>
		<dl>	
			<dd>收件人:<input type = "text" class = "text" readonly = 'readonly' value = "To: <?php echo $html['touser'];?>"></dd>
			<dd>内容(不得为空且不得大于200位):<textarea name = "content" class = "content"></textarea></dd>
			<dd>验证码:<input type = "text" name = "yzm" class = "text yzm"/><img src = 'code.php' id = "code"/>
			<input type = "submit" value = "发送短信" class = "submit"/></dd>
		</dl>
	</form>
</div>
</body>
</html>