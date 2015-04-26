window.onload = function()
{
	code();
	var ubb = document.getElementById('ubb');
	var ubbimg = ubb.getElementsByTagName('img');
	var fm = document.getElementsByTagName('form')[0];
	var font = document.getElementById('font');
	var color = document.getElementById('color');
	var html = document.getElementsByTagName('html')[0];
	var q = document.getElementById('q');
	var qa = q.getElementsByTagName('a');
	
	fm.onsubmit = function()
	{		
		//用户名验证
		if(fm.title.value.length < 2 || fm.title.value.length > 20)
		{
				alert('标题不得小于2位或大于20位');
				fm.title.value = '';//清空
				fm.title.focus(); //focus()方法用于给予该元素焦点。这样用户不必点击它，就能编辑显示的文本了。blur()方法用于把键盘焦点从该元素上移开。
				return false;
		}
		if(/[<>\'\"\\]/.test(fm.title.value)) //test() 方法用于检测一个字符串是否匹配某个模式.
		{
			alert('标题不得包含敏感字符!');
			fm.title.value = '';//清空
			fm.title.focus(); //focus()方法用于给予该元素焦点。这样用户不必点击它，就能编辑显示的文本了。blur()方法用于把键盘焦点从该元素上移开。
			return false;
		}
		//验证发帖内容
		if(fm.content.value.length < 10)
		{
				alert('内容不得小于10位');				
				fm.content.focus(); //focus()方法用于给予该元素焦点。这样用户不必点击它，就能编辑显示的文本了。blur()方法用于把键盘焦点从该元素上移开。
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
		return true;
	};
	
	qa[0].onclick = function()
	{
		window.open('q.php?num=99&path=gifdongtu/1/', 'q', 'width=600, height=400, scrollbars=1');
	};
	
	qa[1].onclick = function()
	{
		window.open('q.php?num=99&path=gifdongtu/2/', 'q', 'width=600, height=400, scrollbars=1');
	};
	
	qa[2].onclick = function()
	{
		window.open('q.php?num=99&path=gifdongtu/3/', 'q', 'width=600, height=400, scrollbars=1');
	};
	
	
	html.onmouseup = function()
	{
		font.style.display = 'none';
		color.style.display = 'none';
	}
	ubbimg[0].onclick = function()
	{
		font.style.display = 'block';
	}
	ubbimg[1].onclick = function()
	{
		content('[b] [/b]');
	}	
	ubbimg[2].onclick = function()
	{
		content('[i] [/i]');
	}
	ubbimg[3].onclick = function()
	{
		content('[u] [/u]');
	}
	ubbimg[4].onclick = function()
	{
		content('[s] [/s]');
	}
	ubbimg[5].onclick = function()
	{
		color.style.display = 'block';
		fm.t.focus();
	}
	
	ubbimg[7].onclick = function()
	{
		var url = prompt('请输入网址：', 'http://');
		if(url && url != 'http://')
			if(/^https?:\/\/(\w\.)?[\w\-\.]+(\.\w+)+$/.test(url))
				content('[url]'+url+'[/url]');
			else
				alert('不合法网址!');
	}

	ubbimg[8].onclick = function()
	{
		var img = prompt('请输入图片地址:', '');
		if(img)
			content('[img]'+img+'[/img]');
	}
	ubbimg[9].onclick = function()
	{
		alert("9");
	}
	ubbimg[12].onclick = function()
	{
		var email = prompt('请输入邮箱：', '@');
		if(email)
			if(/^[\w\-\.]+@[\w\-\.]+(\.\w+)+$/.test(email))
				content('[email]'+email+'[/email]');
			else
				alert('不合法邮箱!');
	}
	ubbimg[16].onclick = function()
	{
		fm.content.rows += 2;
	}
	ubbimg[17].onclick = function()
	{
		fm.content.rows -= 2;
	}
	function content(string)
	{
		fm.content.value += string;
	}
	fm.t.onclick = function()
	{
		showcolor(this.value);
	}	
};

function font(size)
{
	//content('[size = '+size+'] [/size]');
	document.getElementsByTagName('form')[0].content.value += '[size='+size+'] [/size]';
}
function showcolor(color)
{
	document.getElementsByTagName('form')[0].content.value += '[color='+color+'] [/color]';
}