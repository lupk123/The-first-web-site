<?php
	define('IN_TG', true); 	
	define('SCRIPT', uping);
	//转换成硬路径速度更快
	require dirname(__FILE__).'/include/common.inc.php';
	//会员才能进入
	if(!isset($_COOKIE['username']))
		alert_back("非法登陆");
	
	//执行上传图片功能
	if($_GET['action'] == 'up')
	{
		if(!$rows = fetch_array("SELECT tg_uniqid FROM tg_user WHERE tg_username = '{$_COOKIE['username']}' LIMIT 1"))
			alert_back('您要修改的用户不合法!');
		check_uni($rows['tg_uniqid'], $_COOKIE['uniqid']);
		//设置上传图片图片类型
		$files = array('image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/pjpeg');
		//判断上传文件是否合法
		if(is_array($files)) //判断$files是否是一个数组
		{
			//print_r($_FILES['userfile']);
			//$_FILES为上传文件的超级全局变量
			if(!in_array($_FILES['userfile']['type'], $files))
			{
				alert_back("上传图片必须为jpg, gif, png格式");
			}			
		}
		//判断文件出错的错误类型
		if($_FILES['userfile']['error'] > 0)		
		{
			switch ($_FILES['userfile']['error'])
			{
				case 1:	alert_back("上传文件超过约定值1"); break;
				case 2:	alert_back("上传文件超过约定值2"); break;
				case 3:	alert_back("部分被上传"); break;
				case 4:	alert_back("没有文件被上传"); break;
			}
			exit;
		}
		//判断配置大小
		if($_FILES['userfile']['size'] > 1000000)
		{
			alert_back("上传的文件大小不能超过1M");
			exit;
		}
		//获取文件后缀名
		$back = explode('.', $_FILES['userfile']['name']);
		$name = $_POST['dir'].'/'.time().'.'.$back[1];
		//移动文件
		if(is_uploaded_file($_FILES['userfile']['tmp_name']))
		{
			if(!@move_uploaded_file($_FILES['userfile']['tmp_name'], $name))
				alert_back("移动文件错误");
			else 
			{
				echo "<script>alert('图片上传成功!'); window.opener.document.getElementById('address').value = '$name'; window.close();</script>";
			}
		}
		else 
		{
			alert_back("上传的临时文件不存在");
		}
	}
	if(!isset($_GET['dir']))
		alert_back("非法操作");
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset = "UTF-8"/>
	<title><?php echo $sys['tg_webname'];?>--上传图片</title>
<?php
	require ROOT_PASS.'include/title.inc.php'; 
?>
	<script type="text/javascript" src = "js/open.js"></script>
</head>
<body>
<div id = "up">
	<form enctype = "multipart/form-data" action="?action=up" method = "post">
		<input type = "hidden" name = "dir" value = "<?php echo $_GET['dir'];?>"/>
		<input type = "hidden" name = "MAX_FILE_SIZE" value = "1000000"/>
		选择图片: <input type = "file" class = "text" name = "userfile"/>
		<input class = "submit" type = "submit" value = "上传"/>
	</form> 
</div>
</body>
</html>