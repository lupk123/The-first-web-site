<?php
define('IN_TG', true);
define('SCRIPT', member_flower);
//转换成硬路径速度更快
require dirname(__FILE__).'/include/common.inc.php';
if(!isset($_COOKIE['username']))
	_location('请先登录', 'login.php');
session_start();
//批删除
if(isset($_GET['action']) && $_GET['action'] == 'delete' && $_POST['ids'])
{	
	//唯一标示符确认
	$rows = fetch_array("SELECT tg_uniqid FROM tg_user WHERE tg_username = '{$_COOKIE['username']}' LIMIT 1");
	check_uni($rows['tg_uniqid'], $_COOKIE['uniqid']);	
	$array .= mysql_string(implode(',', $_POST['ids']));
	query("DELETE FROM tg_flower WHERE tg_id IN ({$array})");	
	if(affected_rows() == count($_POST['ids']))
	{
		//关闭数据库
		close();
//		session_des();
		_location('删除成功', 'member_flower.php');
	}
	else
	{
		close();
//		session_des();
		alert_back('删除失败');
	}
	exit();
}
//分页模块
$page_num = 15;
global $page_start;
page($page_num, query("SELECT tg_id FROM tg_flower WHERE tg_touser = '{$_COOKIE['username']}'"));//每页显示$page_num个//从数据库里提取数据

$result = query("SELECT tg_id, tg_fromuser, tg_content, tg_flower, tg_date FROM tg_flower WHERE tg_touser = '{$_COOKIE['username']}' ORDER BY tg_date DESC LIMIT $page_start, $page_num");
?>
<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv = "Content-Type" content = "text/html; charset = utf-8"/>
		<title><?php echo $sys['tg_webname'];?>--花朵查询</title>
		<?php
			require ROOT_PASS.'include/title.inc.php'; 
		?>
		<script type="text/javascript" src = 'js/member_message.js'></script>
	</head>
	<body>
		<?php		 
			require ROOT_PASS.'include/header.inc.php';
		?> 
		<div id = "member">
			<?php
				require ROOT_PASS.'include/member.inc.php'; 
			?>
			<div id = "member_main">
				<h2>花朵管理中心</h2>
				<form method='post' action="?action=delete">
					<table cellspacing = '0'>		
					<tr><th>送花人</th><th>送花感言</th><th>数量</th><th>送花时间</th><th>操作</th></tr>			
						<?php while(!!$cc = fetch_array_list($result)) {
							$cc = html($cc);
							$str = title($cc['tg_content'], 14);
							$count += $cc['tg_flower'];
						?>
						
							<tr>
								<td><?php echo $cc['tg_fromuser'];?></td>
								<td title = '<?php echo $cc['tg_content'];?>'><?php echo $str;?></td>
								<td><img src = 'image/1/4.png'/> * <?php echo $cc['tg_flower'];?>朵</td>
								<td><?php echo $cc['tg_date'];?></td>
								<td><input name = 'ids[]' value ='<?php echo $cc['tg_id'];?>' type = 'checkbox'/></td>
							</tr>
						<?php 
							}
							free_result($result);
						?>
						<tr id = "check_all">
							<td colspan='5'>
								共<strong><?php echo $count;?></strong>多花
							</td>
						</tr>
						<tr id = "check_all">
							<td colspan='5 '>
								<label for="all">全选</label>
								<input type="checkbox" id="all" name="check"/>
								<input type='submit' value='批删除' name='delete'/>
							</td>
						</tr>
					</table>
				</form>
				<?php
				//分页函数 1是数字形式 2是文字形式
					paging(2, '个好友送给你花'); 
				?>
			</div>			
		</div>		
		<?php
			require ROOT_PASS.'include/footer.inc.php';
		?>
	</body>
</html>