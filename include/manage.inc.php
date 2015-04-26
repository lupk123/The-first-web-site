<?php
	//防止恶意调用
	if(!defined("IN_TG"))
		exit('error access!');
?>
<div id = "member_guide">
	<h2>管理导航</h2>
	<dl>
		<dd>系统管理</dd>
		<dt><a href = "manage.php">后台首页</a></dt>
		<dt><a href = "manage_set.php">系统设置</a></dt>	
	</dl>
	<dl>
		<dd>会员管理</dd>
		<dt><a href = "manage_mem.php">会员列表</a></dt>
		<dt><a href = "manage_job.php">职务设置</a></dt>	
	</dl>	
</div>