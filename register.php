<?php	
	define('IN_TG', true);
	define('SCRIPT', register); 	
	//转换成硬路径速度更快
	require dirname(__FILE__).'/include/common.inc.php';
	login_state();
	session_start();	

	if($sys['tg_register'] == 0)
		_location('非法操作', 'index.php');
	if($_GET['action'] == 'register')
	{
 		//防止恶意注册与跨站攻击等
		check_code($_POST['yzm'], $_SESSION['code']);			
		//只有验证码正确才能接收数据
 		$clean = array();
 		include ROOT_PASS.'include\check.func.php';	
		$clean['uniqid'] = check_uniqid($_POST['uniqid'], $_SESSION['uniqid']);
		//active用来刚注册用户的激活处理
		$clean['active'] = sha1_uniqid();
 		$clean['username'] = check_username($_POST['username'], 2, 20);
		$clean['password'] = check_pwd($_POST['password'], $_POST['truepass'], 6);
		$clean['prompt'] = check_prompt($_POST['pass_prompt'], 4, 20);
		$clean['ans'] = check_ans($_POST['pass_prompt'], $_POST['pass_ans'], 2, 20);
		$clean['sex'] = check_sex($_POST['sex']);
		$clean['face'] = check_face($_POST['face']);
		$clean['email'] = check_email($_POST['email'], 6, 40);	
		$clean['qq'] = check_qq($_POST['qq']);
		$clean['url'] = check_url($_POST['url'], 40);	
		
 		//在新增之前要先判断用户名是否重复
		$query = "SELECT tg_username FROM tg_user WHERE tg_username = '{$clean['username']}' LIMIT 1";
		is_repeat($query, '对不起此用户名已被注册');
		
		//测试新增 在SQL语句中数组变量必须加上{}
		query(
			"INSERT INTO tg_user (
								tg_uniqid,
								tg_active,
								tg_username,
								tg_password,
								tg_prompt,
								tg_ans,
								tg_sex,
								tg_face,
								tg_email,
								tg_qq,
								tg_url,
								tg_reg_time,
								tg_last_time,
								tg_last_ip
 								) 
 						  VALUES (
 						  		'{$clean['uniqid']}',
 						  		'{$clean['active']}',
 								'{$clean['username']}',
 								'{$clean['password']}',
 								'{$clean['prompt']}',
 								'{$clean['ans']}',
 								'{$clean['sex']}',
 								'{$clean['face']}',
 								'{$clean['email']}',
 								'{$clean['qq']}',
 								'{$clean['url']}',
 								NOW(),
 								NOW(),
 								'{$_SERVER["REMOTE_ADDR"]}'
 								 )"
 		);
		if(affected_rows() == 1)
		{
			//获取上一步插入操作的ID
			$clean['id'] = insert_id();
			//关闭数据库
			close();
			session_des();
			//生成XML
			set_xml('new.xml', $clean);
			_location('恭喜你，注册成功', 'active.php?active='.$clean['active']);
		}
		else 
		{
			close();
			session_des();
			_location('很遗憾您注册失败, 请重新注册', 'register.php');
		}
	}
	//产生唯一标示符
	$uniqid = sha1_uniqid();
	$_SESSION['uniqid'] = $uniqid;
?>

<!DOCTYPE html>
<html>
<head>
	<meta http-equiv = "Content-Type" content = "text/html; charset = utf-8"/>
	<title><?php echo $sys['tg_webname'];?>--注册界面</title>
	<?php
	require ROOT_PASS.'include/title.inc.php'; 
	?>
	<script type="text/javascript" src = "js/code.js"></script>
	<script type="text/javascript" src = "js/register.js"></script>
</head>
<body>
<?php
	require ROOT_PASS.'include/header.inc.php';
?>
	<div id = "reg">
		<h2>会员注册</h2>
		<form method = "post" name = "reg" action = "register.php?action=register">
			<input type = "hidden" name = "uniqid" value = "<?php echo $uniqid;?>"/>
			<dl>
				<dt>请认真填写以下内容：</dt>
				<dd>用 户 名 : <input type = "text" name = "username" class = "text"/>（*必填：至少两位）</dd>
				<dd>密　码　:<input type = "password" name = "password" class = "text"/>（*必填：至少六位）</dd>
				<dd>密码确认:<input type = "password" name = "truepass" class = "text"/>（*必填：至少六位）</dd>
				<dd>密码提示:<input type = "text" name = "pass_prompt" class = "text"/>（*必填：至少两位）</dd>
				<dd>密码回答:<input type = "text" name = "pass_ans" class = "text"/>（*必填：至少两位）</dd>
				<dd>性	别 ：   <input type = "radio" name = "sex" value = "男" checked = "checked"/>男
						  <input type = "radio" name = "sex" value = "女"/>女	</dd>
				<dd class = "face"><input type = 'hidden' name = 'face' value = 'face/3.jpg' id = "face"/><img src = "face/3.jpg" alt = "头像选择" id = "face_img"/></dd>
				<dd>电子邮件:<input type = "text" name = "email" class = "text"/>（*必填）</dd>
				<dd>Ｑ　Ｑ　:<input type = "text" name = "qq" class = "text"/></dd>
				<dd>个人主页:<input type = "text" name = "url" class = "text" value = "http://"/></dd>
				<dd>验 证 码  : <input type = "text" name = "yzm" class = "text yzm"/><img src = 'code.php' id = "code"/></dd>
				<dd><input type = "submit" value = "注册" class = "submit"/></dd>
			</dl>
		</form>
	</div>
	
<?php
	require ROOT_PASS.'include/footer.inc.php';
?>
</body>
</html>