<?php
	define('IN_TG', true); 	
	define('SCRIPT', face);
	//转换成硬路径速度更快
	require dirname(__FILE__).'/include/common.inc.php';
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset = "UTF-8"/>
	<title><?php echo $sys['tg_webname'];?>--头像选择</title>
<?php
	require ROOT_PASS.'include/title.inc.php'; 
?>
	<script type="text/javascript" src = "js/open.js"></script>
</head>
<body>
<div id = "face">
	<h3>选择头像</h3>
	<dl>
	<?php foreach (range(1, 30) as $num){?>
	<dd><img src = 'face/<?php echo $num?>.jpg' alt = 'face/<?php echo $num?>.jpg' title = '头像<?php echo $num?>'/></dd>
	<?php }?>
</dl>
</div>
</body>
</html>