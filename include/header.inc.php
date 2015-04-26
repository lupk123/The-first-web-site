<?php
	//防止恶意调用
	if(!defined("IN_TG"))
		exit('error access!');	
?>
<script type="text/javascript" src = 'js/skin.js'></script>
<div id = "header">
	<h1><a href = "index.php">THE FIRST WEBSITE</a></h1>
	<ul>
		<li><a href = 'index.php'>首页</a></li>		
		<?php
			if(isset($_COOKIE['username']))
			{
				echo "<li><a href = 'member.php'>".$_COOKIE['username'].".个人中心</a>".$GLOBALS['message']."</li>";
				echo "\n";
				if(isset($_SESSION['admin']))
				{
					echo "<li><a href = 'manage.php'>管理</a></li>";
					echo "\n";
				}
				echo "<li><a href = 'logout.php'>退出</a></li>";
				echo "\n";
			}	 
			else 
			{
				if($sys['tg_register'] == 1)
				{
					echo '<li><a href = "register.php">注册</a></li>';
					echo "\n";
				}				
				echo '<li><a href = "login.php">登陆</a></li>';
				echo "\n";
			}
			echo "<li class = 'skin' onmouseover = 'inskin()' onmouseout = 'outskin()'><a href = 'javascript:;'>风格</a>
					<dl id = 'skin'>
						<dd><a href = 'skin.php?id=1'>1　一号皮肤</a></dd>
						<dd><a href = 'skin.php?id=2'>2　二号皮肤</a></dd>
						<dd><a href = 'skin.php?id=3'>3　三号皮肤</a></dd>
					</dl>
					</li>";
			echo "\n";
			echo "<li><a href = 'blog.php'>博友</a></li>";
			echo "\n";
			echo "<li><a href = 'photo.php'> 相册</a></li>";
			echo "\n";				
		?>		
	</ul>
</div>