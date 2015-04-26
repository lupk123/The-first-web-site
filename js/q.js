window.onload = function()
{
	var img = document.getElementsByTagName('img');
	for(var i = 0; i < img.length; i++)
	{
		img[i].onclick = function()
		{			
			 opensrc(this.alt);
		}
	};
};

function opensrc(src)
{
	//opener表示父窗口
	opener.document.getElementsByTagName('form')[0].content.value += '[img]'+src+'[/img]';
	//opener.document.reg.face.value = alt;	
}