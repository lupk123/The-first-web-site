 <?php
 	define('IN_TG', true); 	
	define('SCRIPT', photo_detail);	
	//转换成硬路径速度更快
	require dirname(__FILE__).'/include/common.inc.php';	
	session_start();	
	//接受处理评论
	if($_GET['action'] == 'rephoto')
	{
		if(!$rows = fetch_array("SELECT tg_uniqid FROM tg_user WHERE tg_username = '{$_COOKIE['username']}' LIMIT 1"))
			alert_back('非法操作!');
		check_uni($rows['tg_uniqid'], $_COOKIE['uniqid']);//验证唯一标示符
		check_code($_POST['yzm'], $_SESSION['code']); //验证验证码是否正确

		include ROOT_PASS.'include\check.func.php';
		$clean = array();
		$clean['username'] = $_COOKIE['username'];
		$clean['sid'] = $_POST['id'];
		$clean['title'] = check_title($_POST['title'], 2, 40);
		$clean['content'] = check_post_content($_POST['content'], 10);
		$clean = mysql_string(html($clean));
		query("
		INSERT INTO tg_photo_comment
		(
		tg_sid,
		tg_username,
		tg_title,
		tg_content,
		tg_date
		)
		VALUES
		(
		'{$clean['sid']}',
		'{$clean['username']}',
		'{$clean['title']}',
		'{$clean['content']}',
		NOW()
		)
		");
		if(affected_rows() == 1)
		{
			query("UPDATE tg_photo SET tg_comment = tg_comment + 1 WHERE tg_id = '{$clean['sid']}'");
			//关闭数据库
			close();
		//	session_des();
			_location('评论成功!', 'photo_detail.php?id='.$clean['sid']);
		}
		else
		{
			close();
		//	session_des();
			alert_back('评论失败!');
		}
		exit();
		}
	
	
	if($_GET['id'])
	{
		if(!$c = fetch_array("SELECT tg_id, tg_sid, tg_name, tg_url, tg_user, tg_content, tg_date, tg_read, tg_comment FROM tg_photo WHERE tg_id = '{$_GET['id']}' LIMIT 1"))
			alert_back("不存在此图片");

		$c['type'] = fetch_array("SELECT tg_type FROM tg_dir WHERE tg_id = '{$c['tg_sid']}'");			
		if($c['type']['tg_type'] == 0 && $_COOKIE['zz'] != $c['tg_sid'])
			alert_back('非法操作123');		
		query("UPDATE tg_photo SET tg_read = tg_read + 1 WHERE tg_id = '{$_GET['id']}'");
		$ans = array();
		$ans['id'] = $_GET['id'];
		$ans['name'] = $c['tg_name'];
		$ans['url'] = $c['tg_url'];
		$ans['sid'] = $c['tg_sid'];
		$ans['user'] = $c['tg_user'];
		$ans['read'] = $c['tg_read'];
		$ans['content'] = $c['tg_content'];
		$ans['date'] = $c['tg_date'];
		$ans['comment'] = $c['tg_comment'];
		html($ans);
	}
	else
		alert_back("非法操作");
	
	//创建全局变量 带参的分页
	global $id;
	$id = 'id='.$_GET['id'].'&';
	//从数据库读取目录信息 以及 分页	
	$page_num = 5;
	global $page_start;
	page($page_num, query("SELECT tg_id FROM tg_photo_comment WHERE tg_sid = '{$_GET['id']}'"));
	//从数据库里提取数据
 	$result = query("SELECT tg_id, tg_title, tg_sid, tg_content,tg_username, tg_date FROM tg_photo_comment WHERE tg_sid = '{$_GET['id']}' ORDER BY tg_date DESC LIMIT $page_start, $page_num");

 	//上一页 即取得ID比自己大的第一张图片
 	$ans['preid'] = fetch_array("SELECT min(tg_id) AS tg_id FROM tg_photo WHERE tg_id > '{$_GET['id']}' AND tg_sid = '{$ans['sid']}' LIMIT 1");
 	//下一页 即取得ID比自己小的第一张图片
 	$ans['backid'] = fetch_array("SELECT max(tg_id) AS tg_id FROM tg_photo WHERE tg_id < '{$_GET['id']}' AND tg_sid = '{$ans['sid']}' LIMIT 1");
 		
 	//print_r($ans['backid']);
 	if(!empty($ans['preid']['tg_id']))
 		$html['pre'] = '<a href = "photo_detail.php?id='.$ans['preid']['tg_id'].'">上一页</a>';
 	else 
 		$html['pre'] = "<a>没有了</a>";
 	
 	if(!empty($ans['backid']['tg_id']))
 		$html['back'] = '<a href = "photo_detail.php?id='.$ans['backid']['tg_id'].'">下一页</a>';
 	else
 		$html['back'] = "<a>没有了</a>";
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv = "Content-Type" content = "text/html; charset = utf-8"/>
<title><?php echo $sys['tg_webname'];?>--图片展示</title>
<?php
	require ROOT_PASS.'include/title.inc.php'; 
?>
<script type="text/javascript" src = "js/code.js"></script>
<script type="text/javascript" src = "js/article.js"></script>
</head>
<body>
<?php		 
	require ROOT_PASS.'include/header.inc.php';
?> 
<div id = "photo">
	<h2>图片详情--<?php echo $ans['name'];?></h2>
	<a href = "photo_show.php?id=<?php echo $ans['sid'];?>">返回列表</a>
	<dl class = "detail">
		<dd class = "name">图片名称: <?php echo $ans['name'];?></dd>
		<dt><?php echo $html['pre'];?><img src = "<?php echo $ans['url'];?>"/><?php echo $html['back'];?></dt>
		<dd>阅读(<strong><?php echo $ans['read'];?></strong>)　评论(<strong><?php echo $ans['comment'];?></strong>)　上传者: <?php echo $ans['user'];?>　</dd>
		<dd>上传时间: <?php echo $ans['date'];?></dd>
		<dd>简介:　<?php echo $ans['content'];?></dd>
	</dl>
	<p class = "line"></p>	
		<div class = "re"> 	
		<?php	
			if(!$_GET['page'])
				$_GET['page'] = 1;
			$floor = ($_GET['page'] - 1) * $page_num + 1;			
			while(!!$cc = fetch_array_list($result)) 
			{					
				if(!$rows = fetch_array("SELECT tg_id, tg_username, tg_switch, tg_autograph, tg_email, tg_qq, tg_url, tg_sex, tg_face FROM tg_user WHERE tg_username = '{$cc['tg_username']}'"))
				{
					//该用户已被删除;
				}
				$cc = html($cc);
				$rows = html($rows);
// 			if($rows['tg_username'] == $html['username'])
// 				$mark = 0;
// 			else 
				switch ($floor)
				{
					case 2:	$mark = 1; break;
					case 3: $mark = 2; break;
					case 4: $mark = 3; break;
					default: $mark = 4;
				}
				
		?>		
			<dl class = "face">
				<dd class = "user">
				<?php 
					echo $rows['tg_username']; echo '('.$rows[tg_sex].')';
					switch ($mark)
					{
						case 0: echo '--楼主'; break;
						case 1: echo '--沙发'; break;
						case 2: echo '--板凳'; break;
						case 3: echo '--地板'; break;
					}
				?>
				</dd>
				<dt><img src = "<?php echo $rows['tg_face'];?>" alt = "<?php echo $rows['tg_face'];?>"/></dt>
				<dd class = "message"><a href = "javascript:;" name = "message" title = "<?php echo $rows['tg_id'];?>">发消息</a></dd>
				<dd class = "friend"><a href = "javascript:;" name = "friend" title = "<?php echo $rows['tg_id'];?>">加为好友</a></dd>
				<dd class = "note">留言</dd>
				<dd class = "flower"><a href = "javascript:;" name = "flower" title = "<?php echo $rows['tg_id'];?>">为她送花</a></dd>
				<dd class = "email">邮件: <?php echo $rows['tg_email'];?></dd>
				<dd class = "url">主页: <a href = "<?php echo $rows['tg_url'];?>" target = "_blank"><?php echo $rows['tg_url'];?></a></dd>
			</dl>
			<div class = "content">
				<div class = "user">
					<span><?php echo $floor;?>#</span><?php echo $cc['tg_username'];?> | 发表于<?php echo $cc['tg_date'];?>
				</div> 
				<div class = "detail">
					<h3>主题: <?php echo $cc['tg_title'];?> <?php if(isset($_COOKIE['username'])){?><span><a href = "#re" name = "return" title = "回复<?php echo $floor;?>楼的<?php echo $rows['tg_username'];?>">[回复]</a></span><?php }?></h3>
					<?php
						echo _ubb($cc['tg_content']); 
					?>
					<?php
					 if($rows['tg_switch'] == 1)
					 	echo "<p class = 'autograph'>".$rows['tg_autograph']."</p>";
					?>
				</div>			
			</div>
			<p class = "line"></p>
		<?php
			$floor++; 
			}?>
		<?php 
			free_result($result);
		?>
		<?php
			//分页函数 1是数字形式 2是文字形式
			paging(2, '个回复'); 
		?>	
	</div>	

	<?php if(isset($_COOKIE['username'])) {?>		
		<div id = "re">
			<h2>回复</h2>
			<form action="?action=rephoto" method = "post" id = "post">
				<input type = "hidden" name = "id" value = "<?php echo $_GET['id'];?>"/>
				<dl>
					<dd>标　 题:　<input type = "text" name = "title" class = "text" readonly = "readonly" value = "RE: <?php echo $ans['name'];?>"/>（*必填: 2-40位）</dd>
					<dd id = 'q'>贴　 图:　<a href = "javascript:;">Q图系列(1)</a>	<a href = "javascript:;">Q图系列(2)</a>	<a href = "javascript:;">Q图系列(3)</a></dd>
						<dd class = "content">
							<?php
								include ROOT_PASS.'include/ubb.inc.php';
							?>
							<textarea name = "content" rows = '8'></textarea>
						</dd>
						<dd class = "code">验 证 码  : <input type = "text" name = "yzm" class = "text yzm"/><img src = 'code.php' id = "code"/></dd>
						<dd><input type = "submit" value = "回复" class = "submit"/></dd>
				</dl>
			</form>
		</div>
	<?php }?>	
</div>
<?php
	require ROOT_PASS.'include/footer.inc.php';
?>
</body>
</html>
