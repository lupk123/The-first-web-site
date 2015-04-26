window.onload = function()
{
	code();
	//表单验证，能用客户端验证的尽量用客户端，减少服务器的工作量
	var fm = document.getElementsByTagName('form')[0];
	fm.onsubmit = function()
	{		
		//密码验证
		if(fm.password.value.length > 0 && fm.password.value.length < 6 || fm.password.value.length > 40)
		{
				alert('密码不得小于6位或大于40位');
				fm.password.value = '';//清空
				fm.password.focus(); //focus()方法用于给予该元素焦点。这样用户不必点击它，就能编辑显示的文本了。blur()方法用于把键盘焦点从该元素上移开。
				return false;
		}
		//邮箱验证
		if(fm.email.value.length < 6 || fm.email.value.length > 40)
		{
			alert('邮箱长度不得小于6位或者大于40位');
			fm.email.value = '';
			fm.email.focus();
			return false;			
		}
		if(!/^[\w\-\.]+@[\w\-\.]+(\.\w+)+$/.test(fm.email.value))
		{
			alert('邮箱格式不正确!');
			fm.email.value = '';
			fm.email.focus();
			return false;
		}
		//qq验证
		if(fm.qq.value)
		{
			if(!/^[1-9]{1}[0-9]{4,14}$/.test(fm.qq.value))
			{
				alert('QQ格式不正确');
				fm.qq.value = '';
				fm.qq.focus();
				return false;
			}
		}
		//url验证
		if(fm.url.value && fm.url.value != 'http://')
		{
			if(!/^https?:\/\/(\w\.)?[\w\-\.]+(\.\w+)+$/.test(fm.url.value))
			{
				alert('网址格式不正确!');
				fm.url.value = 'http://';
				fm.url.focus();
				return false;
			}
		}
		//验证码长度验证
		if(fm.yzm.value.length != 4)
		{
			alert('验证码长度必须为4!');
			fm.yzm.value = '';
			fm.yzm.focus();
			return false;
		}
		return true;
	}
}