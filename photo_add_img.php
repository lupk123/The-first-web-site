<?php
	define('IN_TG', true); 	
	define('SCRIPT', photo_add_img);
	session_start();
	//转换成硬路径速度更快
	require dirname(__FILE__).'/include/common.inc.php';
	if(!isset($_COOKIE['username']))
		_location('您没有权限查看此页面', 'index.php');
	//保存图片信息入表
	if($_GET['action'] == 'add_dir')
	{
		if(!$rows = fetch_array("SELECT tg_uniqid FROM tg_user WHERE tg_username = '{$_COOKIE['username']}' LIMIT 1"))
			alert_back('您要修改的用户不合法!');
		check_uni($rows['tg_uniqid'], $_COOKIE['uniqid']);
		
		$info = array();
		$info['name'] = $_POST['photo_name'];
		$info['url'] = $_POST['address'];
		$info['content'] = $_POST['content'];
		$info['sid'] = $_POST['id'];
		$info['user'] = $_COOKIE['username'];
		$info = mysql_string($info);
		
		query("INSERT INTO tg_photo (tg_name, tg_url, tg_content, tg_sid, tg_user, tg_date)
			VALUES ('{$info['name']}', '{$info['url']}', '{$info['content']}', '{$info['sid']}', '{$info['user']}', NOW())
				");
		if(affected_rows() == 1)
		{
			close();
			_location("上传成功", "photo_show.php?id=".$info['sid']);
		}
		else {
			close();
			alert_back("上传失败 ");
		}
	}
	if($_GET['id'])
	{
		if(!$rows = fetch_array("SELECT tg_id, tg_dir FROM tg_dir WHERE tg_id = '{$_GET['id']}' LIMIT 1"))
			alert_back("不存在此相册");
		$ans = array();
		$ans['id'] = $rows['tg_id'];
		$ans['dir'] = $rows['tg_dir'];
		html($ans);
	}
	else
		alert_back("非法操作");
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv = "Content-Type" content = "text/html; charset = utf-8"/>
<title><?php echo $sys['tg_webname'];?>--相册</title>
<?php
	require ROOT_PASS.'include/title.inc.php'; 
?>
<script type="text/javascript" src = "js/photo_add_img.js"></script>
</head>
<body>
<?php		 
	require ROOT_PASS.'include/header.inc.php';
?> 
<div id = "photo">
	<h2>上传图片</h2>
	<form action="?action=add_dir" method = "post">
		<input type = "hidden" name = "id" value = "<?php echo $_GET['id'];?>"/>
		<dl>
			<dd>图片名称: <input type = "text" class = "text" name = "photo_name"/></dd>
			<dd>图片地址: <input type = "text" id = "address" readonly = "readonly" name = "address" class = "text"/><a id = "up" title = "<?php echo $ans['dir'];?>" href = "javascript:;"> 上传</a></dd>
			<dd>图片描述: <textarea name = "content"></textarea></dd>
			<dd><input type = "submit" class = "submit" value = "上传图片"/>
		</dl>
	</form>
</div>
<?php
	require ROOT_PASS.'include/footer.inc.php';
?>
</body>
</html>
