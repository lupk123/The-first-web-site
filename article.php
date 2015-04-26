<?php
	define('IN_TG', true); 	
	define('SCRIPT', article);
	//转换成硬路径速度更快
	require dirname(__FILE__).'/include/common.inc.php';
	session_start();
	//创建全局变量 带参的分页
	global $id;
	$id = 'id='.$_GET['id'].'&';
	//设置精华帖
	if($_GET['action'] == 'nice' && isset($_GET['id']))
	{
		if(!$rows = fetch_array("SELECT tg_uniqid FROM tg_user WHERE tg_username = '{$_COOKIE['username']}' LIMIT 1"))
			alert_back('非法操作!');
		check_uni($rows['tg_uniqid'], $_COOKIE['uniqid']);//验证唯一标示符
		query("UPDATE tg_article SET tg_nice = 1 WHERE tg_id = '{$_GET['id']}'");
		if(affected_rows() == 1)
		{
			close();
			_location("设置精华帖成功", "article.php?id=".$_GET['id']);
		}
		else 
		{
			close();
			alert_back("设置失败");
		}
	}
	//取消精华帖
	if($_GET['action'] == 'delnice' && isset($_GET['id']))
	{
		if(!$rows = fetch_array("SELECT tg_uniqid FROM tg_user WHERE tg_username = '{$_COOKIE['username']}' LIMIT 1"))
			alert_back('非法操作!');
		check_uni($rows['tg_uniqid'], $_COOKIE['uniqid']);//验证唯一标示符
		query("UPDATE tg_article SET tg_nice = 0 WHERE tg_id = '{$_GET['id']}'");
		if(affected_rows() == 1)
		{
			close();
			_location("取消精华帖成功", "article.php?id=".$_GET['id']);
		}
		else
		{
			close();
			alert_back("设置失败");
		}
	}
	//接受处理回帖
	if($_GET['action'] == 'rearticle')
	{
		if(!$rows = fetch_array("SELECT tg_uniqid FROM tg_user WHERE tg_username = '{$_COOKIE['username']}' LIMIT 1"))
			alert_back('非法操作!');
		check_uni($rows['tg_uniqid'], $_COOKIE['uniqid']);//验证唯一标示符
		check_code($_POST['yzm'], $_SESSION['code']); //验证验证码是否正确
		if(isset($_COOKIE['article_time']))
			_timed(time(), $_COOKIE['article_time'],$sys['tg_rearticle'], '回帖时间间隔必须大于'.$sys['tg_rearticle'].'秒');
		include ROOT_PASS.'include\check.func.php';
		$clean = array();
		$clean['username'] = $_COOKIE['username'];
		$clean['type'] = $_POST['type'];
		$clean['reid'] = $_POST['reid'];
		$clean['title'] = check_title($_POST['title'], 2, 40);
		$clean['content'] = check_post_content($_POST['content'], 10);
		$clean = mysql_string(html($clean));
		query("
		INSERT INTO tg_article
		(
			tg_reid,
			tg_username,
			tg_type,
			tg_title,
			tg_content,
			tg_date
		)
		VALUES
		(
			'{$clean['reid']}',
			'{$clean['username']}',
			'{$clean['type']}',
			'{$clean['title']}',
			'{$clean['content']}',
			NOW()
			)
		");
		if(affected_rows() == 1)
		{
			query("UPDATE tg_article SET tg_commentcount = tg_commentcount + 1 WHERE tg_reid = 0 AND tg_id = '{$clean['reid']}'");
			setcookie('article_time', time());
			//关闭数据库
			close();
		//	session_des();
			_location('回复成功', 'article.php?id='.$clean['reid']);
		}
		else
		{
			close();
		//	session_des();
			alert_back('回复失败!');
		}
		exit();
	}

	if(!isset($_GET['id']))
		alert_back('非法操作!');
	if(!$rows = fetch_array("SELECT tg_id, tg_nice, tg_username, tg_type, tg_title, tg_content, tg_readcount, tg_commentcount, tg_date, tg_modifydate FROM tg_article where tg_reid = 0 and tg_id = '{$_GET['id']}'"))
		alert_back('不存在此主题!');
	query("UPDATE tg_article SET tg_readcount = tg_readcount + 1 WHERE tg_id = '{$_GET['id']}'");
	$html = array();
	$html['username'] = $rows['tg_username'];
	$html['nice'] = $rows['tg_nice'];
	$html['type'] = $rows['tg_type'];
	$html['title'] = $rows['tg_title'];
	$html['content'] = $rows['tg_content'];
	$html['readcount'] = $rows['tg_readcount'];
	$html['commentcount'] = $rows['tg_commentcount'];
	$html['date'] = $rows['tg_date'];
	$html['modifydate'] = $rows['tg_modifydate'];
	//to check if the user as the owner of the topic
	if($html['username'] == $_COOKIE['username'] || isset($_SESSION['admin']))
	{
		$html['topic_modify'] = '修改';
	}
	//从数据库中取出用户信息
	if(!$rows = fetch_array("SELECT tg_id, tg_email, tg_switch, tg_autograph, tg_qq, tg_url, tg_sex, tg_face FROM tg_user WHERE tg_username = '{$html['username']}'"))
	{
		//此用户已被删除；
	}
	$html['userId'] = $rows['tg_id'];
	$html['email'] = $rows['tg_email'];
	$html['url'] = $rows['tg_url'];
	$html['qq'] = $rows['tg_qq'];
	$html['sex'] = $rows['tg_sex'];
	$html['face'] = $rows['tg_face'];
	$html['switch'] = $rows['tg_switch'];
	$html['autograph'] = $rows['tg_autograph'];
	$html = html($html);
	//to check if has the necessary to display the autograph
	if($html['switch'] == 1)
	{
		$html['print_autograph'] = '<p class = "autograph">'.$html['autograph'].'<p>';
	}
	else 
	{
		$html['print_autograph'] = '';
	}
		
	//分页	
	$page_num = 10;
	global $page_start;
	page($page_num, query("SELECT tg_id FROM tg_article WHERE tg_reid = '{$_GET['id']}'"));//每页显示14个
	$result = query("SELECT tg_username, tg_title, tg_content, tg_date FROM tg_article where tg_reid = '{$_GET['id']}' ORDER BY tg_date LIMIT $page_start, $page_num");
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv = "Content-Type" content = "text/html; charset = utf-8"/>
<title><?php echo $sys['tg_webname'];?>--帖子详情</title>
<script type="text/javascript" src = 'js/blog.js'></script>
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
<div id = "article">
	<h2>帖子详情</h2>
	<?php if($html['nice'] == 1){?>
		<img src = "image/1/good.gif" alt = "精华帖" class = "nice"/>
	<?php } if($html['readcount'] >= 400 && $html['commentcount'] >= 20) {?>
		<img src = "image/1/4.gif" alt = "热帖" class = "hot"/>
	<?php }
		if($_GET['page'] <= 1){
	?>
 	<div id = "subject"> 	
		<dl class = "face">
			<dd class = "user"><?php echo $html['username']; echo '('.$html[sex].')--楼主';?></dd>
			<dt><img src = "<?php echo $html['face'];?>" alt = "<?php echo $html['face'];?>"/></dt>
			<dd class = "message"><a href = "javascript:;" name = "message" title = "<?php echo $html['userId'];?>">发消息</a></dd>
			<dd class = "friend"><a href = "javascript:;" name = "friend" title = "<?php echo $html['userId'];?>">加为好友</a></dd>
			<dd class = "note">留言</dd>
			<dd class = "flower"><a href = "javascript:;" name = "flower" title = "<?php echo $html['userId'];?>">为她送花</a></dd>
			<dd class = "email">邮件: <?php echo $html['email'];?></dd>
			<dd class = "url">主页: <a href = "<?php echo $html['url'];?>" target = "_blank"><?php echo $html['url'];?></a></dd>
		</dl>
		<div class = "content">
			<div class = "user">
				<span><a href = 'topic_modify.php?id=<?php echo $_GET['id']?>'><?php echo $html['topic_modify'].'　';?></a>
				<?php 
				if(isset($_SESSION['admin']) && $html['nice'] == 0)
					 echo '<a href = "?action=nice&id='.$_GET['id'].'">[设置精华帖]</a>　';	
				else if(isset($_SESSION['admin']) && $html['nice'] == 1)				
					echo '<a href = "?action=delnice&id='.$_GET['id'].'">[取消精华帖]</a>　';
				?>1#</span><?php echo $html['username'];?> | 发表于<?php echo $html['date'];?>
			</div> 
			<h3>主题: <?php echo $html['title'];?>  <img alt="icon" src="image/1/icon<?php echo $html['type'];?>.gif"/> <?php if(isset($_COOKIE['username'])){?><span><a href = "#re" name = "return" title = "回复楼主">　[回复]</a></span><?php }?></h3>
			<div class = "detail">
				<?php echo _ubb($html['content']);?>
				<?php echo $html['print_autograph'];?>
			</div>
			<div class = "read">
				<p>
					<?php //to judge if this topic has been modified
						if($html['modifydate'] != '0000-00-00 00:00:00')
						{
							echo '最后修改于'.$html['modifydate'];
						}
					?>
				</p>
			    阅读量: (<?php echo $html['readcount'];?>)  评论量: (<?php echo $html['commentcount'];?>)
			</div>
		</div>
	</div> 
	<p class = "line"></p>
	<?php 
		}
// 		else 
// 			$floor += $page_num + 1;	
	?>
	<div class = "re"> 	
		<?php	
			if(!$_GET['page'])
				$_GET['page'] = 1;
			$floor = ($_GET['page'] - 1) * $page_num + 2;			
			while(!!$cc = fetch_array_list($result)) 
			{					
				if(!$rows = fetch_array("SELECT tg_id, tg_username, tg_switch, tg_autograph, tg_email, tg_qq, tg_url, tg_sex, tg_face FROM tg_user WHERE tg_username = '{$cc['tg_username']}'"))
				{
					//该用户已被删除;
				}
				html($cc);
				html($rows);
			if($rows['tg_username'] == $$html['username'])
				$mark = 0;
			else 
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
			<form action="?action=rearticle" method = "post" id = "post">
				<input type = "hidden" name = "reid" value = "<?php echo $_GET['id'];?>"/>
				<input type = "hidden" name = "type" value = "<?php echo $html['type'];?>"/>
				<dl>
					<dd>标　 题:　<input type = "text" name = "title" class = "text" value = "RE: <?php echo $html['title'];?>"/>（*必填: 2-40位）</dd>
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
