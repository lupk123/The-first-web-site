window.onload = function()
{
	code();
	var fm = document.getElementsByTagName('form')[0];
	fm.onsubmit = function()
	{
		//验证内容
		if(fm.content.value.length > 200 || fm.content.value.length == 0)
		{
			alert('短信内容不得大于200位!');			
			fm.content.focus();
			return false;
		}
		//验证码长度验证
		if(fm.yzm.value.length != 4)
		{
			alert('验证码长度必须为4!');			
			fm.yzm.focus();
			return false;
		}
		return true;
	}
};