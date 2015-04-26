<?php
	define('IN_TG', true); 	
	define('SCRIPT', photo);
	session_start();
	//转换成硬路径速度更快
	require dirname(__FILE__).'/include/common.inc.php';

	if($_GET['action'] == 'del' && isset($_GET['id']))
	{
		$re = fetch_array("SELECT tg_id, tg_dir FROM tg_dir WHERE tg_id = '{$_GET['id']}'");		
		$re = html($re);
		if(isset($_SESSION['admin']))
		{
			if(!$rows = fetch_array("SELECT tg_uniqid FROM tg_user WHERE tg_username = '{$_COOKIE['username']}' LIMIT 1"))
				alert_back('您要修改的用户不合法!');		
			check_uni($rows['tg_uniqid'], $_COOKIE['uniqid']);			
			
			//删除磁盘中存放的图片
			if(removeDir($re['tg_dir']))
			{
				query("DELETE FROM tg_photo WHERE tg_sid = '{$_GET['id']}'");
				query("DELETE FROM tg_dir WHERE tg_id = '{$_GET['id']}'");
				close();
				_location("删除成功", 'photo.php');
			}
			else 
			{
				close();
				_location("删除失败", "photo.php");
			}
		}
		else 
			alert_back('非法操作');
	}
	//从数据库读取目录信息 以及 分页	
	$page_num = $sys['tg_photo'];
	global $page_start;
	page($page_num, query("SELECT tg_id FROM tg_dir"));
	//从数据库里提取数据
	$result = query("SELECT tg_id, tg_name, tg_type, tg_cover FROM tg_dir ORDER BY tg_time DESC LIMIT $page_start, $page_num");		
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv = "Content-Type" content = "text/html; charset = utf-8"/>
<title><?php echo $sys['tg_webname'];?>--相册列表</title>
<?php
	require ROOT_PASS.'include/title.inc.php'; 
?>
<!-- <script type="text/javascript" src = "js/blog.js"></script> -->
</head>
<body>
<?php		 
	require ROOT_PASS.'include/header.inc.php';
?> 
<div id = "photo">
	<h2>相册</h2>
	<div id = "p">
		<?php if(isset($_SESSION['admin']) && isset($_COOKIE['username'])) {?>
			<p><a href = "photo_add_dir.php">创建目录</a></p>
		<?php }?>
	</div>
	<div id = "img">
		<?php
			while(!!$rows = fetch_array_list($result))
			{ 
				html($rows);
				if(empty($rows['tg_type']))
					$rows['html'] = " (私密)";
				else
					$rows['html'] = " (公开)";
				if(empty($rows['tg_cover']))
				{
					$rows['cover'] = "";
				}
				else 
					$rows['cover'] = "<img src = '".$rows['tg_cover']."';/>";
				//统计相册中相片的数量
				$num = fetch_array("SELECT COUNT(*) AS count FROM tg_photo WHERE tg_sid = '{$rows['tg_id']}'");
		?>
		<dl>
			<dt><a href = "photo_show.php?id=<?php echo $rows['tg_id'];?>"><?php echo $rows['cover'];?></a></dt>
			<dd><a href = "photo_show.php?id=<?php echo $rows['tg_id'];?>"><p><?php echo $rows['tg_name']; echo ' 【'.$num['count'].'张】';?></p><p><?php echo $rows['html'];?></p></a></dd>			
			<?php if(isset($_SESSION['admin']) && isset($_COOKIE['username'])) {?>
				<dd><a href = "photo_dir_modify.php?id=<?php echo $rows['tg_id'];?>">[修改]</a> <a href = "photo.php?action=del&id=<?php echo $rows['tg_id'];?>">[删除]</a></dd>
			<?php }?>
		</dl>
		<?php 
		}?>
	</div>	
</div>
<?php
	require ROOT_PASS.'include/footer.inc.php';
?>
</body>
</html>
