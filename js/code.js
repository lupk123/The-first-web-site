function code()
{
	var code = document.getElementById("code");
	//点击刷新验证码
	if(code)
		code.onclick = function()
		{
			code.src = 'code.php?tm='+Math.random();
		};
}