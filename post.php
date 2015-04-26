<?php	
	define('IN_TG', true);
	define('SCRIPT', post); 	
	//转换成硬路径速度更快
	require dirname(__FILE__).'/include/common.inc.php';
	session_start();	
	if(!isset($_COOKIE['username']))
		_location('请先登录', 'login.php');
	//将帖子写入数据库
	if($_GET['action'] == 'post')
	{
		if(!$rows = fetch_array("SELECT tg_uniqid FROM tg_user WHERE tg_username = '{$_COOKIE['username']}' LIMIT 1"))
			alert_back('非法操作!');
		check_uni($rows['tg_uniqid'], $_COOKIE['uniqid']);//验证唯一标示符
		//check the time that if it out the time limit
		if(isset($_COOKIE['post_time']))
		{
			_timed(time(), $_COOKIE['post_time'], $sys['tg_post'],'发帖时间间隔必须大于'.$sys['tg_post'].'秒');
		}
		check_code($_POST['yzm'], $_SESSION['code']); //验证验证码是否正确
		include ROOT_PASS.'include\check.func.php';
		$clean = array();
		$clean['username'] = $_COOKIE['username'];
		$clean['type'] = $_POST['type'];
		$clean['title'] = check_title($_POST['title'], 2, 40);
		$clean['content'] = check_post_content($_POST['content'], 10);
		$clean = mysql_string(html($clean));
		query("
		INSERT INTO tg_article
		(
		tg_username,
		tg_type,
		tg_title,
		tg_content,
		tg_date
		)
		VALUES
		(
		'{$clean['username']}',
		'{$clean['type']}',
		'{$clean['title']}',
		'{$clean['content']}',
		NOW()
		)
				");
		if(affected_rows() == 1)
		{
		$clean['id'] = insert_id();
		setcookie('post_time', time());
		//关闭数据库
		close();
		//session_des();
		_location('发帖成功', 'article.php?id='.$clean['id']);
		}
		else
		{
			close();
		//	session_des();
			alert_back('发帖失败!');
		}
	}
	
?>

<!DOCTYPE html>
<html>
<head>
	<meta http-equiv = "Content-Type" content = "text/html; charset = utf-8"/>
	<title><?php echo $sys['tg_webname'];?>--发表帖子</title>
	<?php
	require ROOT_PASS.'include/title.inc.php'; 
	?>
	<script type="text/javascript" src = "js/code.js"></script>
	<script type="text/javascript" src = "js/post.js"></script>
</head>
<body>
<?php
	require ROOT_PASS.'include/header.inc.php';
?>
	<div id = "post">
		<h2>发表帖子</h2>
		<form method = "post" name = "post" action = "?action=post">
			<dl>
				<dt>请认真填写以下内容：</dt>
				<dd>类　 型:
					<?php
						foreach(range(1, 14) as $num)
						{
							if($num == 8)
								echo "<br/>　　　　";
							if($num == 1)
								echo "<label for='type".$num."'><input type = 'radio' name = 'type' id = 'type".$num."' value = '".$num."' checked = 'checked'/> ";
							else 
								echo "<label for='type".$num."'><input type = 'radio' name = 'type' id = 'type".$num."' value = '".$num."'/> ";
							echo " <img src = 'image/1/icon".$num.".gif'　alt = '类型'/></label>　";
						} 
					?>　
				</dd>
				<dd>标　 题:　<input type = "text" name = "title" class = "text"/>（*必填: 2-40位）</dd>				
				<dd id = 'q'>贴　 图:　<a href = "javascript:;">Q图系列(1)</a>	<a href = "javascript:;">Q图系列(2)</a>	<a href = "javascript:;">Q图系列(3)</a></dd>
				<dd class = "content">
					<?php
						include ROOT_PASS.'include/ubb.inc.php';
					?>
					<textarea name = "content" rows = '15'></textarea>
				</dd>
				<dd class = "code">验 证 码  : <input type = "text" name = "yzm" class = "text yzm"/><img src = 'code.php' id = "code"/></dd>
				<dd><input type = "submit" value = "发表" class = "submit"/></dd>
			</dl>
		</form>
	</div>	
	
<?php
	require ROOT_PASS.'include/footer.inc.php';
?>
</body>
</html>