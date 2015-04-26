window.onload = function()
{
	var img = document.getElementsByTagName('img');
	for(var i = 0; i < img.length; i++)
	{
		img[i].onclick = function()
		{			
			 opensrc(this.src, this.alt);
		}
	};
};

function opensrc(src, alt)
{
	//opener表示父窗口
	opener.document.getElementById('face_img').src = src;	
	opener.document.reg.face.value = alt;	
}