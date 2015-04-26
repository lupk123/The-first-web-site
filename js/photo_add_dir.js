window.onload = function()
{
	var fm = document.getElementsByTagName("form")[0];
	var pwd = document.getElementById("pwd");
	fm[1].onclick = function()
	{
		pwd.style.display = 'block';
	}
	fm[2].onclick = function()
	{
		pwd.style.display = 'none';
	}	
	fm.onsubmit = function()
	{
		if(fm.photo_name.value.length < 2 || fm.photo_name.value.length > 20)
		{
				alert('相册名称不得小于2位或大于20位');
				fm.photo_name.value = '';//清空
				fm.photo_name.focus(); //focus()方法用于给予该元素焦点。这样用户不必点击它，就能编辑显示的文本了。blur()方法用于把键盘焦点从该元素上移开。
				return false;
		}		
		//密码验证
		if(fm[1].checked)
		{
			if(fm.pwd.value.length < 6 || fm.pwd.value.length > 40)
			{
				alert('密码不得小于6位或大于40位');
				fm.pwd.value = '';//清空
				fm.pwd.focus(); //focus()方法用于给予该元素焦点。这样用户不必点击它，就能编辑显示的文本了。blur()方法用于把键盘焦点从该元素上移开。
				return false;
			}
		}
		return true;
	}
}