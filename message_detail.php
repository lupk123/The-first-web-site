<?php
define('IN_TG', true);
define('SCRIPT', message_detail);
//转换成硬路径速度更快
require dirname(__FILE__).'/include/common.inc.php';
session_start();
if(!isset($_COOKIE['username']))
	_location('请先登录', 'login.php');

if(!isset($_GET['id']))
	alert_back('非法操作!');
if(isset($_GET['action']))
{
	if(!query("SELECT tg_id FROM tg_message WHERE tg_id = '{$_GET['id']}' ORDER BY tg_date DESC LIMIT 1"))
		alert_back('你要删除的短信内容不存在!');
	//唯一标示符确认
	$rows = fetch_array("SELECT tg_uniqid FROM tg_user WHERE tg_username = '{$_COOKIE['username']}' LIMIT 1");	
	check_uni($rows['tg_uniqid'], $_COOKIE['uniqid']);
	query("DELETE FROM tg_message WHERE tg_id = '{$_GET['id']}' LIMIT 1");
	if(affected_rows() == 1)
	{
		//关闭数据库
		close();
	//	session_des();
		_location('删除成功', 'member_message.php');
	}
	else
	{
		close();
	//	session_des();
		alert_back('删除失败');
	}
}

		
$rows = fetch_array("SELECT tg_fromuser, tg_date, tg_content, tg_state FROM tg_message WHERE tg_id = '{$_GET['id']}' LIMIT 1");
if(!$rows)
	alert_back("本条短信内容不存在!");
$rows = html($rows);
if(!$rows['tg_state'])
{
	query("UPDATE tg_message SET tg_state = 1 WHERE tg_id = '{$_GET['id']}'");
	if(!affected_rows())
		alert_back('异常!');
}
$mes = fetch_array("SELECT COUNT(tg_id) AS num FROM tg_message WHERE tg_state = 0 AND tg_touser = '{$_COOKIE['username']}'");

if($mes['num'])
	$GLOBALS['message'] = '<strong>(<a href = "member_message_receive">'.$mes['num'].')</a></strong>';
else
	$GLOBALS['message'] = '<strong><a href = "member_message_receive">(0)</a></strong>';
?>
<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv = "Content-Type" content = "text/html; charset = utf-8"/>
		<title><?php echo $sys['tg_webname'];?>--短信列表</title>
		<?php
			require ROOT_PASS.'include/title.inc.php'; 
		?>
		<script type="text/javascript" src = "js/message_detail.js"></script>
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
				<h2>短信详情</h2>
				<dl>
					<dd>收 信 人: <?php echo $rows['tg_fromuser'];?></dd>						
					<dd>内　容 : <strong><?php echo $rows['tg_content'];?></strong></dd>
					<dd>发信时间: <?php echo $rows['tg_date'];?></dd>
					<dd class = "input"><input type = "button" value = "返回短信列表"  <?php if($_GET['state'] == 'receive') echo "id='return_receive'"; else echo "id='return_send'";?>/>
						<input type = "button" value = "删除短信" name = '<?php echo $_GET['id'];?>' id = "del"/>
					</dd>
				</dl>
			</div>
		</div>
		<?php
			require ROOT_PASS.'include/footer.inc.php';
		?>
	</body>
</html>

