<?php
	//防止恶意调用
	if(!defined("IN_TG"))
		exit('error access!');
	mysql_close();
?>

<div id = "footer">
	<p>本程序执行耗时：<?php echo round(runtime() - START_TIME, 4);?></p>
	<p>版权所有 翻版必究</p>
	<p>本程序代码可以随意修改</p>
</div>