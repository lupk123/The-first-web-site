<?php
	define('IN_TG', true); 	
	define('SCRIPT', photo_show);
	session_start();
	//转换成硬路径速度更快
	require dirname(__FILE__).'/include/common.inc.php';
		
	global $id;
	$id = 'id='.$_GET['id'].'&';
	//删除图片
	if($_GET['action'] == 'del' && isset($_GET['id']))
	{
		$re = fetch_array("SELECT tg_user, tg_sid, tg_url FROM tg_photo WHERE tg_id = '{$_GET['id']}'");		
		$re = html($re);
		if($_COOKIE['username'] == $re['tg_user'] || isset($_SESSION['admin']))
		{
			if(!$rows = fetch_array("SELECT tg_uniqid FROM tg_user WHERE tg_username = '{$_COOKIE['username']}' LIMIT 1"))
				alert_back('您要修改的用户不合法!');		
			check_uni($rows['tg_uniqid'], $_COOKIE['uniqid']);				
			query("DELETE FROM tg_photo WHERE tg_id = '{$_GET['id']}'");
			if(affected_rows() == 1)
			{
				//删除磁盘中存放的图片
				if(!file_exists($re['tg_url']))
					alert_back('图片不存在磁盘中!');
				unlink($re['tg_url']);
				close();
				_location("删除成功", 'photo_show.php?id='.$re['tg_sid']);
			}
		}
		else 
			alert_back("非法操作");
	}
	
	if($_GET['id'] && !$_POST['pwd'])
	{
		if(!$c = fetch_array("SELECT tg_id, tg_name, tg_type FROM tg_dir WHERE tg_id = '{$_GET['id']}' LIMIT 1"))
			alert_back("不存在此相册");
		$ans = array();
		$ans['id'] = $_GET['id'];
		$ans['name'] = $c['tg_name'];
		$ans['type'] = $c['tg_type'];
		html($ans);		
	}
	else if($_POST['pwd'] && $_GET['id'])
	{
		$pwd = sha1($_POST['pwd']);
		if(!$c = fetch_array("SELECT tg_id, tg_name, tg_type FROM tg_dir WHERE tg_id = '{$_GET['id']}' AND tg_pwd = '{$pwd}' LIMIT 1"))
			alert_back("不存在此相册");
		$ans = array();
		$ans['id'] = $_GET['id'];
		$ans['name'] = $c['tg_name'];
		$ans['type'] = 1;
		setcookie('zz', $_GET['id']);
		html($ans);
	}
	else 
		alert_back("非法操作");
	
	//从数据库读取目录信息 以及 分页	
	$page_num = $sys['tg_photo'];
	global $page_start;
	page($page_num, query("SELECT tg_id FROM tg_photo WHERE tg_sid = '{$_GET['id']}'"));
	//从数据库里提取数据
	$result = query("SELECT tg_id, tg_name, tg_url, tg_read, tg_comment, tg_content, tg_sid, tg_user, tg_date FROM tg_photo WHERE tg_sid = '{$_GET['id']}' ORDER BY tg_date DESC LIMIT $page_start, $page_num");		
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv = "Content-Type" content = "text/html; charset = utf-8"/>
<title><?php echo $sys['tg_webname'];?>--图片展示</title>
<?php
	require ROOT_PASS.'include/title.inc.php'; 
?>
</head>
<body>
<?php		 
	require ROOT_PASS.'include/header.inc.php';
?> 
<div id = "photo">
	<h2>图片展示--<?php echo $ans['name'];?></h2>
	<a href = "photo.php">返回相册列表</a>
	<?php 
		if($ans['type'] == 1 || isset($_SESSION['admin']) || $_COOKIE['zz'] == $_GET['id'])			
		{
			echo '<p><a href = "photo_add_img.php?id='.$ans['id'].'">添加图片</a></p>';
			while(!!$cc = fetch_array_list($result)) {
				$filename = $cc['tg_url'];
				$width = 150;
				$height = 150;
				html($cc);
	?>
			<dl>
				<dt><a href = "photo_detail.php?id=<?php echo $cc['tg_id'];?>"><img src = "thumb.php?name=<?php echo $filename;?>&width=<?php echo $width;?>&height=<?php echo $height?>" alt = "<?php echo $cc['tg_name'];?>"/></a></dt>
				<dd><a href = "photo_detail.php?id=<?php echo $cc['tg_id'];?>">名称: <?php echo $cc['tg_name'];?></a></dd>
				<dd>阅读量(<strong><?php echo $cc['tg_read'];?></strong>)　　　评论(<strong><?php echo $cc['tg_comment'];?></strong>)</dd>
				<dd>上传者: <?php echo $cc['tg_user'];?></dd>	
				<dd><?php if($_COOKIE['username'] == $cc['tg_user'] || isset($_SESSION['admin'])) 
					echo '<a href = "photo_show.php?action=del&id='.$cc['tg_id'].'"/>[删除]</a>';
					?>
				</dd>	
			</dl>
			<?php
			}		
		free_result($result);
	?>
	<?php
	//分页函数 1是数字形式 2是文字形式
		paging(1, '个相册');
	}
	else
	{
		echo "<form method = 'post' action = '?id=".$_GET['id']."'>";
		echo "请输入密码:　<input type = 'password' class = 'text' name = 'pwd'/>";
		echo "<input type = 'submit' value = '提交'/>";
		echo "</form>";
	}
	?>
</div>
<?php
	require ROOT_PASS.'include/footer.inc.php';
?>
</body>
</html>