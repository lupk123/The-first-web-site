<?php
	define('IN_TG', true);
	define('SCRIPT', manage_set);
	session_start();
	//转换成硬路径速度更快
	require dirname(__FILE__).'/include/common.inc.php';
	if(!isset($_COOKIE['username']) || !isset($_SESSION['admin']))
		alert_back('非法操作!');	
	
	//修改
	if($_GET['action'] == 'set')
	{
		if(!$rows = fetch_array("SELECT tg_uniqid FROM tg_user WHERE tg_username = '{$_COOKIE['username']}' LIMIT 1"))
			alert_back('您要修改的用户不合法!');		
		check_uni($rows['tg_uniqid'], $_COOKIE['uniqid']);
		$clean = array();
		include ROOT_PASS.'include\check.func.php';
		$clean['webname'] = $_POST['webname'];
		$clean['article'] = $_POST['article'];
		$clean['blog'] = $_POST['blog'];
		$clean['photo'] = $_POST['photo'];
		$clean['skin'] = $_POST['skin'];
		$clean['string'] = $_POST['string'];
		$clean['post'] = $_POST['post'];
		$clean['rearticle'] = $_POST['rearticle'];
		$clean['code'] = $_POST['code'];
		$clean['register'] = $_POST['register'];
		$clean = mysql_string($clean);
		
		query("	UPDATE tg_system SET
				tg_webname = '{$clean['webname']}',
				tg_article = '{$clean['article']}',
				tg_blog = '{$clean['blog']}',
				tg_photo = '{$clean['photo']}',
				tg_skin = '{$clean['skin']}',
				tg_string = '{$clean['string']}',
				tg_post = '{$clean['post']}',
				tg_rearticle = '{$clean['rearticle']}',
				tg_code = '{$clean['code']}',
				tg_register = '{$clean['register']}'
				WHERE
					tg_id = 1
					");
	
		if(affected_rows() == 1)
		{
			//关闭数据库
			close();
		//		session_des();
			_location('修改成功', 'manage.php');
		}
		else
		{
			close();
		//		session_des();
			_location('您没有做任何修改!', 'manage_set.php');
		}
	}
	
	if(!!$rows = fetch_array("SELECT * FROM tg_system WHERE tg_id = 1 LIMIT 1"))
	{
		//每页文章数量
		if($rows['tg_article'] == 14)
			$rows['artile_show'] = "<select name = 'article'>
										<option value = '14' select = 'selected'>每页14篇</option>
										<option value = '16'>每页16篇</option>
									</select>";
		else if($rows['tg_article'] == 16)
			$rows['artile_show'] = "<select name = 'article'>
										<option value = '16' select = 'selected'>每页16篇</option>
										<option value = '14'>每页14篇</option>
									</select>";
		//每页博友数量
		if($rows['tg_blog'] == 9)
			$rows['blog_show'] = "<select name = 'blog'>
										<option value = '9' select = 'selected'>每页9人</option>
										<option value = '12'>每页12个</option>
									</select>";
		else if($rows['tg_blog'] == 12)
			$rows['blog_show'] = "<select name = 'blog'>
										<option value = '12' select = 'selected'>每页12人</option>
										<option value = '9'>每页9个</option>
									</select>";
		//每页相册数量
		if($rows['tg_photo'] == 8)
			$rows['photo_show'] = "<select name = 'photo'>
										<option value = '8' select = 'selected'>每页8张</option>
										<option value = '12'>每页12个</option>
									</select>";
		else if($rows['tg_photo'] == 12)
			$rows['photo_show'] = "<select name = 'photo'>
										<option value = '12' select = 'selected'>每页12张</option>
										<option value = '8'>每页8个</option>
									</select>";
		//皮肤类型
		if($rows['tg_skin'] == 1)
			$rows['skin_show'] = "<select name = 'skin'>
										<option value = '1' select = 'selected'>一号皮肤</option>
										<option value = '2'>二号皮肤</option>
										<option value = '3'>三好皮肤</option>
									</select>";
		else if($rows['tg_skin'] == 2)
			$rows['skin_show'] = "<select name = 'skin'>
										<option value = '2' select = 'selected'>二号皮肤</option>
										<option value = '1'>一号皮肤</option>
										<option value = '3'>三号皮肤</option>
									</select>";
		else if($rows['tg_skin'] == 3)
			$rows['skin_show'] = "<select name = 'skin'>
										<option value = '3' select = 'selected'>三好皮肤</option>
										<option value = '1'>一号皮肤</option>
										<option value = '2'>二号皮肤</option>
									</select>";
		//发帖时间限制
		if($rows['tg_post'] == 0)
			$rows['post_show'] = "<input type = 'radio' name = 'post' value = '0' checked = 'checked'/> 0　
								<input type = 'radio' name = 'post' value = '30'/> 30秒　
								<input type = 'radio' name = 'post' value = '60'/> 60秒";
		if($rows['tg_post'] == 30)
			$rows['post_show'] = "<input type = 'radio' name = 'post' value = '0'/> 0　
								<input type = 'radio' name = 'post' value = '30' checked = 'checked'/> 30秒　
								<input type = 'radio' name = 'post' value = '60'/> 60秒";
		if($rows['tg_post'] == 60)
			$rows['post_show'] = "<input type = 'radio' name = 'post' value = '0'/> 0　
								<input type = 'radio' name = 'post' value = '30'/> 30秒　
								<input type = 'radio' name = 'post' value = '60' checked = 'checked'/> 60秒";
		//回帖时间限制
		if($rows['tg_rearticle'] == 0)
			$rows['rearticle_show'] = "<input type = 'radio' name = 'rearticle' value = '0' checked = 'checked'/> 0　
								<input type = 'radio' name = 'rearticle' value = '30'/> 30秒　
								<input type = 'radio' name = 'rearticle' value = '60'/> 60秒";
		if($rows['tg_rearticle'] == 30)
			$rows['rearticle_show'] = "<input type = 'radio' name = 'rearticle' value = '0'/> 0　
								<input type = 'radio' name = 'rearticle' value = '30' checked = 'checked'/> 30秒　
								<input type = 'radio' name = 'rearticle' value = '60'/> 60秒";
		if($rows['tg_rearticle'] == 60)
			$rows['rearticle_show'] = "<input type = 'radio' name = 'rearticle' value = '0'/> 0　
								<input type = 'radio' name = 'rearticle' value = '30'/> 30秒　
								<input type = 'radio' name = 'rearticle' value = '60' checked = 'checked'/> 60秒";
		//是否开启验证码
		if($rows['tg_code'] == 0)
			$rows['code_show'] = "<input type = 'radio' name = 'code' checked = 'checked' value = '0'/> 禁用　
								<input type = 'radio' name = 'code' value = '1'/> 启用";
		if($rows['tg_code'] == 1)
			$rows['code_show'] = "<input type = 'radio' name = 'code' value = '0'/> 禁用　
								<input type = 'radio' name = 'code' checked = 'checked' value = '1'/> 启用";
		//是否开启会员注册
		if($rows['tg_register'] == 0)
			$rows['register_show'] = "<input type = 'radio' name = 'register' checked = 'checked' value = '0'/> 禁用　
								<input type = 'radio' name = 'register' value = '1'/> 启用";
		if($rows['tg_register'] == 1)
			$rows['register_show'] = "<input type = 'radio' name = 'register' value = '0'/> 禁用　
								<input type = 'radio' name = 'register' checked = 'checked' value = '1'/> 启用";
		html($rows); 
	}
	else 
		alert_back('系统表读取错误');
?>
<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv = "Content-Type" content = "text/html; charset = utf-8"/>
		<title><?php echo $sys['tg_webname'];?>--后台管理中心</title>
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
				<h2>后台管理中心</h2>
				<form method = "post" action = "?action=set">					
					<dl>		
						<dd>网　站　名　称:　<input type = "text" name = "webname" class = "text" value = "<?php echo $rows['tg_webname'];?>"/></dd>
						<dd>文章每页列表数:　<?php echo $rows['artile_show'];?></dd>
						<dd>博客每页列表数:　<?php echo $rows['blog_show'];?></dd>
						<dd>相册每页列表数:　<?php echo $rows['photo_show'];?></dd>
						<dd>站点　默认皮肤:　<?php echo $rows['skin_show'];?></dd>
						<dd>非法　字符过滤:　<input type = "text" name = "string" class = "text" value = "<?php echo $rows['tg_string'];?>"/>　(*请用|隔开)</dd>
						<dd>发帖　时间限制:　<?php echo $rows['post_show'];?></dd>
						<dd>回帖　时间限制:　<?php echo $rows['rearticle_show'];?></dd>
						<dd>是否启用验证码:　<?php echo $rows['code_show'];?></dd>
						<dd>是否　开放注册:　<?php echo $rows['register_show'];?></dd>
						<dd><input type = "submit" id = "submit" value = "修改系统设置" name = "submint"/></dd>
					</dl>				
				</form>
			</div>
		</div>
		<?php
			require ROOT_PASS.'include/footer.inc.php';
		?>
	</body>
</html>