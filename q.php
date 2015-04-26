<?php
	define('IN_TG', true); 	
	define('SCRIPT', q);
	//转换成硬路径速度更快
	require dirname(__FILE__).'/include/common.inc.php';
	if(isset($_GET['num']) && isset($_GET['path']))
	{
		$count = $_GET['num'];
		if(!$path = $_GET['path'])
			alert_back('非法目录');
	}
	else 
		alert_back('非法操作');
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset = "UTF-8"/>
	<title><?php echo $sys['tg_webname'];?>--Q图选择</title>
<?php
	require ROOT_PASS.'include/title.inc.php'; 
?>
	<script type="text/javascript" src = "js/q.js"></script>
</head>
<body>
<div id = "face">
	<h3>选择Q图</h3>
	<dl>
	<?php foreach (range(0, $count) as $num){?>
	<dd><img src = '<?php echo $path.$num?>.gif' alt = '<?php echo $path.$num?>.gif' title = 'Q图<?php echo $num?>'/></dd>
	<?php }?>
</dl>
</div>
</body>
</html>