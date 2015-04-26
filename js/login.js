window.onload = function()
{
	code();
	//登陆验证，能用客户端验证的尽量用客户端，减少服务器的工作量
	var fm = document.getElementsByTagName('form')[0];
	fm.onsubmit = function()
	{			
		//用户名验证
		if(fm.username.value.length < 2 || fm.username.value.length > 20)
		{
				alert('用户名不得小于2位或大于20位');
				fm.username.value = '';//清空
				fm.username.focus(); //focus()方法用于给予该元素焦点。这样用户不必点击它，就能编辑显示的文本了。blur()方法用于把键盘焦点从该元素上移开。
				return false;
		}
		if(/[<>\'\"\\ 	]/.test(fm.username.value)) //test() 方法用于检测一个字符串是否匹配某个模式.
		{
			alert('用户名不得包含敏感字符!');
			fm.username.value = '';//清空
			fm.username.focus(); //focus()方法用于给予该元素焦点。这样用户不必点击它，就能编辑显示的文本了。blur()方法用于把键盘焦点从该元素上移开。
			return false;
		}
		//密码验证
		if(fm.password.value.length < 6 || fm.password.value.length > 40)
		{
				alert('密码不得小于6位或大于40位');
				fm.password.value = '';//清空
				fm.password.focus(); //focus()方法用于给予该元素焦点。这样用户不必点击它，就能编辑显示的文本了。blur()方法用于把键盘焦点从该元素上移开。
				return false;
		}
		//验证码长度验证
		if(fm.yzm.value.length != 4)
		{
			alert('验证码长度必须为4!');
			fm.yzm.value = '';
			fm.yzm.focus();
			return false;
		}
	}
};