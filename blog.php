<?php
	define('IN_TG', true); 	
	define('SCRIPT', blog);
	session_start();
	//转换成硬路径速度更快
	require dirname(__FILE__).'/include/common.inc.php';
	$page_num = $sys['tg_blog'];
	global $page_start;
	page($page_num, query("SELECT tg_id FROM tg_user"));//每页显示6个
	//从数据库里提取数据
	$result = query("SELECT tg_id, tg_username, tg_face, tg_sex FROM tg_user ORDER BY tg_reg_time DESC LIMIT $page_start, $page_num");		
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv = "Content-Type" content = "text/html; charset = utf-8"/>
<title><?php echo $sys['tg_webname'];?>--博友</title>
<?php
	require ROOT_PASS.'include/title.inc.php'; 
?>
<script type="text/javascript" src = "js/blog.js"></script>
</head>
<body>
<?php		 
	require ROOT_PASS.'include/header.inc.php';
?> 
<div id = "blog">
	<h2>博友列表</h2>
	<?php while(!!$cc = fetch_array_list($result)) {
			html($cc);
	?>
	<dl>
		<dd class = "user"><?php echo $cc['tg_username']; echo '('.$cc['tg_sex'].')';?></dd>
		<dt><img src = "<?php echo $cc['tg_face']?>" alt = "头像"/></dt>
		<dd class = "message"><a href = "javascript:;" name = "message" title = "<?php echo $cc['tg_id'];?>">发消息</a></dd>
		<dd class = "friend"><a href = "javascript:;" name = "friend" title = "<?php echo $cc['tg_id'];?>">加为好友</a></dd>
		<dd class = "note">留言</dd>
		<dd class = "flower"><a href = "javascript:;" name = "flower" title = "<?php echo $cc['tg_id'];?>">为<?php	 if($cc['tg_sex'] == '男') echo '他'; else echo '她';?>送花</a></dd>
	</dl>
	<?php }
		free_result($result);
	?>
	<?php
	//分页函数 1是数字形式 2是文字形式
		paging(1, '个会员'); 
	?>
</div>
<?php
	require ROOT_PASS.'include/footer.inc.php';
?>
</body>
</html>
