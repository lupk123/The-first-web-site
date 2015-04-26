<?php
	define('IN_TG', true);
	define('SCRIPT', member);
	session_start();
	//转换成硬路径速度更快
	require dirname(__FILE__).'/include/common.inc.php';
	if(!isset($_COOKIE['username']))
		alert_back('非法操作!');	
	$rows = fetch_array("SELECT tg_username, tg_sex, tg_face, tg_autograph, tg_email, tg_qq, tg_url, tg_reg_time, tg_level FROM tg_user WHERE tg_username = '{$_COOKIE['username']}' LIMIT 1");	
	if(!$rows)
		alert_back('此用户不存在!');
	$user = array();
	$user['username'] = $rows['tg_username'];
	$user['sex'] = $rows['tg_sex'];
	$user['face'] = $rows['tg_face'];
	$user['email'] = $rows['tg_email'];
	$user['qq'] = $rows['tg_qq'];
	$user['url'] = $rows['tg_url'];
	$user['reg_time'] = $rows['tg_reg_time'];
	$user['autograph'] = $rows['tg_autograph'];
	if($rows['tg_level'])
		$user['level'] = '管理员';
	else if($rows['tg_level'] == 0)
		$user['level'] = '普通会员';
	else 
		$user['level'] = '出错';
	$user = html($user);	
?>
<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv = "Content-Type" content = "text/html; charset = utf-8"/>
		<title><?php echo $sys['tg_webname'];?>--个人中心</title>
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
				require ROOT_PASS.'include/member.inc.php'; 
			?>
			<div id = "member_main">
				<h2>会员管理中心</h2>
				<dl>
					<dd>用 户 名:		<?php echo $user['username'];?> </dd>
					<dd>性　 别:	<?php echo $user['sex'];?></dd>
					<dd>头　 像:	<img src = '<?php echo $user['face'];?>'  alt = "头像"/></dd>
					<dd>电子邮件:		<?php echo $user['email'];?></dd>
					<dd>Ｑ　 Ｑ:	<?php echo $user['qq'];?></dd>
					<dd>个人主页:		<?php echo $user['url'];?></dd>
					<dd>个性签名:		<?php echo $user['autograph'];?></dd>
					<dd>注册时间:		<?php echo $user['reg_time'];?></dd>
					<dd>身　 份:	<?php echo $user['level']?></dd>
				</dl>				
			</div>
		</div>
		<?php
			require ROOT_PASS.'include/footer.inc.php';
		?>
	</body>
</html>