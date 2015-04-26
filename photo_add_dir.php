<?php
	define('IN_TG', true); 	
	define('SCRIPT', photo_add_dir);
	session_start();
	//转换成硬路径速度更快
	require dirname(__FILE__).'/include/common.inc.php';
	if(!isset($_COOKIE['username']) || !isset($_SESSION['admin']))
		_location('您没有权限查看此页面', 'index.php');
	if($_GET['action'] == 'add_dir')
	{
		if(!$rows = fetch_array("SELECT tg_uniqid FROM tg_user WHERE tg_username = '{$_COOKIE['username']}' LIMIT 1"))
			alert_back('非法操作!');
		check_uni($rows['tg_uniqid'], $_COOKIE['uniqid']);//验证唯一标示符
		$rows = array();
	//	include ROOT_PASS.'include\check.func.php';
		$rows['photo_name'] = $_POST['photo_name'];
		$rows['type'] = $_POST['type'];
		$rows['pwd'] = sha1($_POST['pwd']);
		$rows['content'] = $_POST['content'];
		$rows['dir'] = time();
		mysql_string($rows);
		//检查目录
		if(!is_dir('photo'))
			mkdir('photo', 0777);
		//在主目录下创建相册 以当前时间戳为目录名
		if(!is_dir('photo/'.$rows['dir']))
		{
			mkdir('photo/'.$rows['dir'], 0777);
		}
		//把当前的目录信息写入数据库
		if(!empty($rows['type']))
		{
			query(
			"INSERT INTO tg_dir (
								tg_name,
								tg_type,
								tg_content,
								tg_dir,
								tg_time
 								) 
 						  VALUES (
 						  		'{$rows['photo_name']}',
 						  		'{$rows['type']}',
 								'{$rows['content']}',
 								'photo/{$rows['dir']}',
 								NOW()
 								 )"
 					);
		}
		else 
		{
			query(
			"INSERT INTO tg_dir (
								tg_name,
								tg_type,
								tg_content,
								tg_dir,
								tg_time,
								tg_pwd
 								) 
 						  VALUES (
 						  		'{$rows['photo_name']}',
 						  		'{$rows['type']}',
 								'{$rows['content']}',
 								'photo/{$rows['dir']}',
 								NOW(),
 								'{$rows['pwd']}'
 								 )"
 					);
		}
		if(affected_rows() == 1)
		{
			//关闭数据库
			close();
			//session_des();
			_location('设置成功', 'photo.php');
		}
		else
		{
			close();
		//	session_des();
			alert_back('设置失败!');
		}		
	}
// 	$page_num = $sys['tg_blog'];
// 	global $page_start;
// 	page($page_num, query("SELECT tg_id FROM tg_user"));//每页显示6个
// 	//从数据库里提取数据

// 	$result = query("SELECT tg_id, tg_username, tg_face, tg_sex FROM tg_user ORDER BY tg_reg_time DESC LIMIT $page_start, $page_num");		
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv = "Content-Type" content = "text/html; charset = utf-8"/>
<title><?php echo $sys['tg_webname'];?>--相册</title>
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
	<h2>相册</h2>
	<form action="?action=add_dir" method = "post">
		<dl>
			<dd>相册名称: <input type = "text" class = "text" name = "photo_name"/></dd>
			<dd>相册类型: <input type = "radio" name = "type" value = "0"/>私密　<input type = "radio" name = "type" checked = "checked" value = "1"/>公开</dd>
			<dd id = "pwd">相册密码: <input type = "password" class = "text" name = "pwd"/></dd>
			<dd>相册描述: <textarea name = "content"></textarea></dd>
			<dd><input type = "submit" class = "submit" value = "创建相册"/>
		</dl>
	</form>
</div>
<?php
	require ROOT_PASS.'include/footer.inc.php';
?>
</body>
</html>
