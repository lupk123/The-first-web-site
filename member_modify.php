<?php
	define('IN_TG', true);
	define('SCRIPT', member_modify);	
	//转换成硬路径速度更快
	require dirname(__FILE__).'/include/common.inc.php';
	session_start();
	if(!isset($_COOKIE['username']))
		alert_back('非法操作!');	
	
	if(isset($_GET['action']) && $_GET['action'] == 'modify')
	{
		check_code($_POST['yzm'], $_SESSION['code']);
		if(!$rows = fetch_array("SELECT tg_uniqid FROM tg_user WHERE tg_username = '{$_COOKIE['username']}' LIMIT 1"))
			alert_back('您要修改的用户不合法!');
	
		check_uni($rows['tg_uniqid'], $_COOKIE['uniqid']);
		//只有验证码正确才能接收数据
		$clean = array();
		include ROOT_PASS.'include\check.func.php';		
		$clean['password'] = check_modify_pwd($_POST['password'], 6);
		$clean['sex'] = check_sex($_POST['sex']);
		$clean['face'] = check_face($_POST['face']);
		$clean['email'] = check_email($_POST['email'], 6, 40);
		$clean['qq'] = check_qq($_POST['qq']);
		$clean['url'] = check_url($_POST['url'], 40);	
		$clean['switch'] = check_switch($_POST['switch']);
		$clean['autograph'] = check_autograph($_POST['autograph'], 200);
		if(empty($clean['password']))
		{
			query("	UPDATE tg_user SET
						tg_sex = '{$clean['sex']}',
						tg_face = '{$clean['face']}',
						tg_email = '{$clean['email']}',
						tg_qq = '{$clean['qq']}',
						tg_url = '{$clean['url']}',
						tg_switch = '{$clean['switch']}',
						tg_autograph = '{$clean['autograph']}'
					WHERE
						tg_username = '{$_COOKIE['username']}'
				");
		}
		else
		{
			query("	UPDATE tg_user SET
						tg_password = '{$clean['password']}',
						tg_sex = '{$clean['sex']}',
						tg_face = '{$clean['face']}',
						tg_email = '{$clean['email']}',
						tg_qq = '{$clean['qq']}',
						tg_url = '{$clean['url']}',
						tg_switch = '{$clean['switch']}',
						tg_autograph = '{$clean['autograph']}'
					WHERE
						tg_username = '{$_COOKIE['username']}'
											");
		}
		if(affected_rows() == 1)
		{
			//关闭数据库
			close();
	//		session_des();
			_location('恭喜你，修改成功', 'member.php');
		}
		else
		{
			close();
	//		session_des();
			_location('您没有做任何修改!', 'member_modify.php');
		}
			
	}
	
	
	$rows = fetch_array("SELECT * FROM tg_user WHERE tg_username = '{$_COOKIE['username']}'");	
	if(!$rows)
		alert_back('此用户不存在!');
	$user = array();
	$user['username'] = $rows['tg_username'];
	$user['sex'] = $rows['tg_sex'];
	$user['face'] = $rows['tg_face'];
	$user['email'] = $rows['tg_email'];
	$user['qq'] = $rows['tg_qq'];
	$user['url'] = $rows['tg_url'];
	$user['switch'] = $rows['tg_switch'];
	$user['autograph'] = $rows['tg_autograph'];	

	$user = html($user);
	if($user['sex'] == '男')
		$user['sex_htm'] = "<input type = 'radio' name = 'sex' value = '男' checked = 'checked'/>男 <input type = 'radio' name = 'sex' value = '女'/>女";
	else if($user['sex'] == '女')
		$user['sex_htm'] = "<input type = 'radio' name = 'sex' value = '女' checked = 'checked'/>女<input type = 'radio' name = 'sex' value = '男'/>男";
	
	$user['face_htm'] = "<select name = 'face'>";
	
	foreach (range(1, 30) as $num)
	{		
		$str = 'face/'.$num.'.jpg';		
		if($str == $user['face'])
			$user['face_htm'] .= "<option value = 'face/".$num.".jpg' selected = 'selected'>face/".$num.".jpg</option>";
		else
			$user['face_htm'] .= "<option value = 'face/".$num.".jpg'>face/".$num.".jpg</option>";
	}
	$user['face_htm'] .= "</select>";
	//check the autograph if has been used and display in the screen
	if($user['switch'] == 1)
	{
		$user['switch_htm'] = "<input type = 'radio' name = 'switch' value = '0'/>禁用
		<input type = 'radio' name = 'switch' checked = 'checked' value = '1'/>启用";
	}
	else if($user['switch'] == 0)
	{
		$user['switch_htm'] = "<input type = 'radio' name = 'switch' checked = 'checked' value = '0'/>禁用
		<input type = 'radio' name = 'switch' value = '1'/>启用";
	}
?>
<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv = "Content-Type" content = "text/html; charset = utf-8"/>
		<title><?php echo $sys['tg_webname'];?>--个人中心</title>
		<?php
			require ROOT_PASS.'include/title.inc.php'; 
		?>
		<script type="text/javascript" src = "js/code.js"></script>
		<script type="text/javascript" src = "js/member_modify.js"></script>
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
				<form method = "post" name = "member_modify" action = "?action=modify">
					<h2>会员管理中心</h2>
					<dl>
						<dd>用 户 名 : <?php echo ' '.$user['username'];?></dd>
						<dd>密　　码: <input type = 'password' class = 'text' name = 'password'/>(留空则不修改)</dd>
						<dd>性　 别　: <?php echo $user['sex_htm'];?></dd>
						<dd>头　 像　: <?php echo $user['face_htm'];?></dd>
						<dd>电子邮件: <input type = 'text' class = 'text' name = 'email' value = "<?php echo $user['email'];?>"/></dd>
						<dd>Ｑ　Ｑ　: <input type = 'text' class = 'text' name = 'qq' value = "<?php echo $user['qq'];?>"/></dd>
						<dd>个人主页: <input type = 'text' class = 'text' name = 'url' value = "<?php echo $user['url'];?>"/></dd>
						<dd>个性签名: <?php echo $user['switch_htm'];?>
									<p><textarea name = 'autograph' rows="3" cols="35"><?php echo $user['autograph'];?></textarea></p>								
						</dd>						
						<dd>验  证  码 : <input type = "text" name = "yzm" class = "text yzm"/><img src = 'code.php' id = "code"/></dd>
						<dd><input type = 'submit' class = 'submit' value = '修改资料'/></dd>
					</dl>				
				</form>
			</div>
		</div>
		<?php
			require ROOT_PASS.'include/footer.inc.php';
		?>
	</body>
</html>