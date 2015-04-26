<?php
	//防止恶意调用
	if(!defined("IN_TG"))
		exit('error access!');
?>
<div id = "member_guide">
	<h2>中心导航</h2>
	<dl>
		<dd>账号管理</dd>
		<dt><a href = "member.php">个人信息</a></dt>
		<dt><a href = "member_modify.php">修改资料</a></dt>	
	</dl>
	<dl>
		<dd>其他管理</dd>
		<dt><a href = "member_message_send.php">发送短信查询</a></dt>
		<dt><a href = "member_message_receive.php">接收短信查询</a></dt>
		<dt><a href = "member_friend.php">好友设置</a></dt>
		<dt><a href = "member_flower.php">查询花朵</a></dt>
		<dt><a href = "###">个人相册</a></dt>	
	</dl>
</div>