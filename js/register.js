window.onload = function()
{
	var face_img = document.getElementById("face_img");
	face_img.onclick = function()
	{
		window.open('face.php', 'face', 'width = 400, height = 600, top = 0, left = 0, scrollbars = 1');
	};
	code();
	//表单验证，能用客户端验证的尽量用客户端，减少服务器的工作量
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
		if(/[<>\'\"\\]/.test(fm.username.value)) //test() 方法用于检测一个字符串是否匹配某个模式.
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
		if(fm.truepass.value != fm.password.value)
		{
			alert('密码和密码确认必须一致!');
			fm.truepass.value = '';
			fm.truepass.focus();
			return false;
		}
		//密码提示与回答
		if(fm.pass_prompt.value.length < 4 || fm.pass_prompt.value.length >　20)
		{
			alert('密码提示长度不得小于4位或者大于20位!');
			fm.pass_prompt.value = '';
			fm.pass_prompt.focus();
			return false;
		}
		if(fm.pass_ans.value.length < 2 || fm.pass_ans.value.length > 20)
		{
			alert('密码回答不得小于4位或者大于20位!');
			fm.pass_ans.value = '';
			fm.pass_ans.focus();
			return false;
		}
		if(fm.pass_ans.value == fm.pass_prompt.value)
		{
			alert('密码提示不得与密码回答一致!');
			fm.pass_ans.value = '';
			fm.pass_ans.focus();
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
};