<?php
	define('IN_TG', true); 	
	define('SCRIPT', index);
	session_start();
	//转换成硬路径速度更快
	require dirname(__FILE__).'/include/common.inc.php';
	
	//读取xml
	$clean = html(get_xml('new.xml'));
	//读取帖子列表
	$page_num = $sys['tg_article'];
	global $page_start;
	page($page_num, query("SELECT tg_id FROM tg_article WHERE tg_reid = 0"));//每页显示14个
	//从数据库里提取数据
	$result = query("SELECT tg_id, tg_type, tg_title, tg_readcount, tg_commentcount FROM tg_article WHERE tg_reid = 0 ORDER BY tg_date DESC LIMIT $page_start, $page_num");

	//最新图片
//	$photo = fetch_array("SELECT tg_id, tg_name, tg_url, tg_sid FROM tg_photo ORDER BY tg_date DESC LIMIT 1");
	$photo = fetch_array("SELECT tg_id, tg_name, tg_url, tg_sid FROM tg_photo 
							WHERE tg_sid IN
							(
								SELECT tg_id FROM tg_dir WHERE tg_type = 1
							)
							ORDER BY tg_date DESC LIMIT 1");
//	print_r($photo);
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title><?php echo $sys['tg_webname'];?>--首页</title>
<?php
	require ROOT_PASS.'include/title.inc.php'; 
?>
<script type="text/javascript" src = 'js/blog.js'></script>
</head>
<body>
<?php		 
	require ROOT_PASS.'include/header.inc.php';
?>

<div id = "list">
	<h2>帖子列表</h2>
	<a href = "post.php" class = "post">发表帖子</a>
	<ul class = "article">
		<?php while(!!$cc = fetch_array_list($result)) {
			html($cc);
		?>
			<li class = "icon<?php echo $cc['tg_type'];?>"><a href = "article.php?id=<?php echo $cc['tg_id'];?>"><?php echo title($cc['tg_title'], 20);?></a><em>阅读量(<strong><?php echo $cc['tg_readcount'];?></strong>) 评论(<strong><?php echo $cc['tg_commentcount'];?></strong>)</em></li>
		<?php }
			free_result($result);
		?>			
	</ul>
	<?php
		//分页函数 1是数字形式 2是文字形式
		paging(2, '个帖子'); 
	?>	
</div>

<div id = "user">
	<h2>新增用户</h2>
	<dl>
		<dd class = "user"><?php echo $clean['username'];?>(<?php echo $clean['sex'];?>)</dd>
		<dt><img src = "<?php echo $clean['face'];?>" alt = "<?php echo $clean['username'];?>"/></dt>
		<dd class = "message"><a href = "javascript:;" name = "message" title = "<?php echo $clean['id'];?>">发消息</a></dd>
		<dd class = "friend"><a href = "javascript:;" name = "friend" title = "<?php echo $clean['id'];?>">加为好友</a></dd>
		<dd class = "note">留言</dd>
		<dd class = "flower"><a href = "javascript:;" name = "flower" title = "<?php echo $clean['id'];?>">为她送花</a></dd>
		<dd class = "email">邮件: <a href ="mailto: <?php echo $clean['email'];?>"><?php echo $clean['email'];?></a></dd>
		<dd class = "url">主页: <a href = "<?php echo $clean['url'];?>" target = "_blank"><?php echo $clean['url'];?></a></dd>
	</dl>
</div>

<div id = "pic">
	<h2>图片</h2>
	<dl>
		<dt><a href = "photo_detail.php?id=<?php echo $photo['tg_id'];?>"><img src = "<?php echo $photo['tg_url'];?>" alt = '<?php echo $photo['tg_name']?>' width = '200' height = '200'/></a></dt>
	</dl>
</div>

<?php
	require ROOT_PASS.'include/footer.inc.php';
?>
</body>
</html>