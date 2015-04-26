<?php
	define('IN_TG', true); 	
	define('SCRIPT', photo_add_dir);
	session_start();	
	//转换成硬路径速度更快
	require dirname(__FILE__).'/include/common.inc.php';
	if(!isset($_COOKIE['username']) || !isset($_SESSION['admin']))
		_location('您没有权限查看此页面', 'index.php');
	//修改目录
	if(isset($_GET['action']) == "modify_dir")
	{
		if(!$rows = fetch_array("SELECT tg_uniqid FROM tg_user WHERE tg_username = '{$_COOKIE['username']}' LIMIT 1"))
			alert_back('您要修改的用户不合法!');		
		check_uni($rows['tg_uniqid'], $_COOKIE['uniqid']);
		$ans = array();
		$ans['id'] = $_POST['id'];
		$ans['name'] = $_POST['photo_name'];
		$ans['type'] = $_POST['type'];
		$ans['pwd'] = sha1($_POST['pwd']);
		$ans['cover'] = $_POST['cover'];
		$ans['content'] = $_POST['content'];
		mysql_string($ans);
		query("UPDATE tg_dir SET
				tg_name = '{$ans['name']}',
				tg_type = '{$ans['type']}',
				tg_pwd = '{$ans['pwd']}',
				tg_cover = '{$ans['cover']}',
				tg_content = '{$ans['content']}' 
				WHERE
					tg_id = '{$ans['id']}'
				");
		if(affected_rows() == 1)
		{
			//关闭数据库
			close();
			_location('恭喜你，修改成功', 'photo.php');
		}
		else
		{
			close();
			_location('您没有做任何修改!', 'photo_dir_modify.php');
		}		
	}
	//读取数据
	if(isset($_GET['id']))
	{
		if(!!$result = fetch_array("SELECT tg_id, tg_name, tg_type, tg_pwd, tg_cover, tg_content FROM tg_dir WHERE tg_id = '{$_GET['id']}' LIMIT 1"))
		{
		//	print_r($result);
			html($result);
			if($result['tg_type'] == 0) //私密
			{
				$result['html'] = '<input type = "radio" name = "type" checked = "checked" value = "0"/>私密　<input type = "radio" name = "type" value = "1"/>公开</dd>';
			}
			else if($result['tg_type'] == 1) //公开
			{
				$result['html'] = '<input type = "radio" name = "type" value = "0"/>私密　<input type = "radio" name = "type" checked = "checked" value = "1"/>公开</dd>';
			}
		}
		else 
			alert_back("非法操作");
	}	
	else 
		alert_back('非法操作');
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv = "Content-Type" content = "text/html; charset = utf-8"/>
<title><?php echo $sys['tg_webname'];?>--修改相册</title>
<?php
	require ROOT_PASS.'include/title.inc.php'; 
?>
<script type="text/javascript" src = "js/photo_add_dir.js"></script>
</head>
<body>
<?php		 
	require ROOT_PASS.'include/header.inc.php';
?> 
<div id = "photo">
	<h2>修改相册目录</h2>
	<form action="?action=modify_dir" method = "post">
		<dl>
			<dd>相册名称: <input type = "text" class = "text" name = "photo_name" value = "<?php echo $result['tg_name'];?>"/></dd>
			<dd>相册类型:
			<?php echo $result['html'];?> 
<!-- 			<input type = "radio" name = "type" value = "0"/>私密　<input type = "radio" name = "type" checked = "checked" value = "1"/>公开 -->
			</dd>
			<dd id = "pwd" <?php if($result['tg_type'] == 0) echo 'style = "display: block"';?>>相册密码: <input type = "password" class = "text" name = "pwd"/></dd>
			<dd>相册封面: <input type = "text" class = "text" value = "<?php echo $result['tg_cover'];?>" name = "cover"/></textarea></dd>
			<dd>相册描述: <textarea name = "content"><?php echo $result['tg_content'];?></textarea></dd>
			<dd><input type = "submit" class = "submit" value = "修改目录"/>
		</dl>
		<input type = "hidden" value = "<?php echo $result['tg_id'];?>" name = "id"/>
	</form>
</div>
<?php
	require ROOT_PASS.'include/footer.inc.php';
?>
</body>
</html>
