<?php
define('IN_TG', true);
define('SCRIPT', login);
//转换成硬路径速度更快
require dirname(__FILE__).'/include/common.inc.php';
login_state();
session_start();
if($_GET['action'] == 'login')
{
	//防止恶意注册与跨站攻击等
	check_code($_POST['yzm'], $_SESSION['code']);
	include ROOT_PASS.'include\login.func.php';
	//接收数据
	$clean = array();
	$clean['username'] = check_username($_POST['username'], 2, 20);
	$clean['pwd'] = check_pwd($_POST['password'], 6);
	$clean['time'] = check_time($_POST['time']);	
	$query = "SELECT tg_username, tg_uniqid, tg_level FROM tg_user WHERE tg_username = '{$clean['username']}' AND tg_password = '{$clean['pwd']}' AND tg_active = '' LIMIT 1";
	if(!$rows = fetch_array($query))
	{
		close();
	//	session_des();
		_location('用户名密码不正确或者用户未激活', 'login.php');
		exit(); 
	}
	//登陆成功后需记录登录信息
	query("UPDATE tg_user SET
				 tg_last_time = NOW(), 
				 tg_last_ip = '{$_SERVER["REMOTE_ADDR"]}', 
				 tg_login_count = tg_login_count + 1
		   WHERE
				 tg_username = '{$clean['username']}'");

	//session_des();
	if($rows['tg_level'] == 1)
		$_SESSION['admin'] = $rows['tg_username'];
	set_cookie($rows['tg_username'], $rows['tg_uniqid'], $clean['time']);
	close();
	_location(null, 'member.php');
}

?>

<!DOCTYPE html>
<html>
<head>
	<meta http-equiv = "Content-Type" content = "text/html; charset = utf-8"/>
	<title><?php echo $sys['tg_webname'];?>--登陆界面</title>
	<?php
		require ROOT_PASS.'include/title.inc.php'; 
	?>
	<script type="text/javascript" src = "js/code.js"></script>
	<script type="text/javascript" src = "js/login.js"></script>
</head>
<body>
<?php
	require ROOT_PASS.'include/header.inc.php';
?>
	<div id = "login">
		<h2>登陆</h2>
		<form method = "post" name = "login" action = "login.php?action=login">
			<dl>		
				<dd>用户名：<input type = "text" name = "username" class = "text"/></dd>
				<dd>密　码：<input type = "password" name = "password" class = "text"/></dd>
				<dd>保	留：	<input type = "radio" name = "time" value = "0" checked = "checked">不保留
							<input type = "radio" name = "time" value = "1">保留一天
							<input type = "radio" name = "time" value = "2">一周
							<input type = "radio" name = "time" value = "3">一月
				</dd>
				<?php if($sys['tg_code'] == 1){?>
				<dd>验 证 码  : <input type = "text" name = "yzm" class = "text yzm"/><img src = 'code.php' id = "code"/></dd>
				<?php }?>
				<dd><input type = "submit" value = "登陆" class = "button"/>
					<input type = "button" value = "注册" id = "location" class = "button location"/>
				</dd>
			</dl>
		</form>
	</div>
	<?php
		require ROOT_PASS.'include/footer.inc.php';
	?>
</body>
</html>
