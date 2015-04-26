<?php
define('IN_TG', true);
define('SCRIPT', manage_mem);
//转换成硬路径速度更快
require dirname(__FILE__).'/include/common.inc.php';
session_start();
if(!isset($_COOKIE['username']) || !isset($_SESSION['admin']))
		alert_back('非法操作!');

//批删除
// if(isset($_GET['action']) && $_GET['action'] == 'delete' && $_POST['ids'])
// {
// 	//唯一标示符确认
// 	$rows = fetch_array("SELECT tg_uniqid FROM tg_user WHERE tg_username = '{$_COOKIE['username']}' LIMIT 1");
// 	check_uni($rows['tg_uniqid'], $_COOKIE['uniqid']);
// 	$array .= mysql_string(implode(',', $_POST['ids']));
// 	query("DELETE FROM tg_message WHERE tg_id IN ({$array})");
// 	if(affected_rows() == count($_POST['ids']))
// 	{
// 		//关闭数据库
// 		close();
// 	//	session_des();
// 		_location('删除成功', 'member_message_receive.php');
// 	}
// 	else
// 	{
// 		close();
// 	//	session_des();
// 		alert_back('删除失败');
// 	}
// 	exit();
// }
//分页模块
$page_num = 15;
global $page_start;
page($page_num, query("SELECT tg_id FROM tg_user"));//每页显示$page_num个//从数据库里提取数据

$result = query("SELECT tg_id, tg_username, tg_email, tg_reg_time FROM tg_user LIMIT $page_start, $page_num");	
?>
<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv = "Content-Type" content = "text/html; charset = utf-8"/>
		<title><?php echo $sys['tg_webname'];?>--会员列表</title>
		<?php
			require ROOT_PASS.'include/title.inc.php'; 
		?>
<!-- 		<script type="text/javascript" src = 'js/member_message.js'></script> -->
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
				<form method='post' action="?action=delete">
					<table cellspacing = '0'>		
					<tr><th>会员昵称</th><th>电子邮件</th><th>注册时间</th><th>操作</th></tr>
<!-- 					<th>状态</th> 		 -->
						<?php while(!!$cc = fetch_array_list($result)) {
							$cc = html($cc);
						?>
						<tr>
							<td><?php echo $cc['tg_username'];?></td>
							<td><<?php echo $cc['tg_email'];?></td>
							<td><?php echo $cc['tg_reg_time'];?></td>
							<td><a href = "###">删</a> <a href = "###">修</a></td>
						</tr>
						<?php 
							}
							free_result($result);
						?>
					</table>
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