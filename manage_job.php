<?php
	define('IN_TG', true);
	define('SCRIPT', manage_job);
	//转换成硬路径速度更快
	require dirname(__FILE__).'/include/common.inc.php';
	session_start();
	if(!isset($_COOKIE['username']) || !isset($_SESSION['admin']))
			alert_back('非法操作!');
	if($_GET['action'] == 'del' && isset($_GET['id']))
	{
		$rows = array();
		$rows['id'] = $_GET['id'];
		mysql_string($rows);
		query("UPDATE tg_user SET tg_level = 0 WHERE tg_username = '{$_COOKIE['username']}' AND tg_id = '{$rows['id']}'");
		if(affected_rows() == 1)
		{
			//关闭数据库
			close();
			session_des();
			_location('设置成功', 'member.php');
		}
		else {
			close();
			//session_des();
			alert_back('设置失败!');
		}
	}
	if($_GET['action'] == 'add')
	{
		$rows = array();
		$rows['username'] = $_POST['manage'];
		mysql_string($rows);
		query("UPDATE tg_user SET tg_level = 1 WHERE tg_username = '{$rows['username']}'");
		if(affected_rows() == 1)
		{
			//关闭数据库
			close();
			//session_des();
			_location('设置成功', 'manage_job.php');
		}
		else {
			close();
			//	session_des();
			alert_back('设置失败或该会员已经是管理员!');
		}
	}
	//分页模块
	$page_num = 15;
	global $page_start;
	page($page_num, query("SELECT tg_id FROM tg_user WHERE tg_level = 1"));//每页显示$page_num个//从数据库里提取数据
	
	$result = query("SELECT tg_id, tg_username, tg_email, tg_reg_time FROM tg_user WHERE tg_level = 1 LIMIT $page_start, $page_num");	
?>



<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv = "Content-Type" content = "text/html; charset = utf-8"/>
		<title><?php echo $sys['tg_webname'];?>--会员列表</title>
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
				require ROOT_PASS.'include/manage.inc.php'; 
			?>
			<div id = "member_main">
				<h2>会员管理中心</h2>
				<table cellspacing = '0'>		
					<tr><th>会员昵称</th><th>电子邮件</th><th>注册时间</th><th>操作</th></tr>
					<!--	<th>状态</th> 		 --> 
					<?php while(!!$cc = fetch_array_list($result)) 
					{
						$cc = html($cc);				
						echo "<tr>";
						echo "<td>".$cc['tg_username']."</td>";
						echo "<td>".$cc['tg_email']."</td>";
						echo "<td>".$cc['tg_reg_time']."</td>";
								if($cc['tg_username'] == $_COOKIE['username'])
									echo "<td><a href = 'manage_job.php?action=del&id=".$cc['tg_id']."'>辞职</a></td>";
								else 
									echo "<td>无权操作</td>"; 
						echo "</tr>"; 
							}	
						//	free_result($result);
					?>
				</table>
				<form id = "form" action="?action=add" method = "post">
					<input name = "manage" type = "text"/>
					<input type = "submit" value = "添加为管理员"/>
				</form>
				<?php
				//分页函数 1是数字形式 2是文字形式
					paging(2, '条数据'); 
				?>
			</div>			
		</div>		
		<?php
			require ROOT_PASS.'include/footer.inc.php';
		?>
	</body>
</html>