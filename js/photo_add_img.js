window.onload = function()
{
	var up = document.getElementById("up");
	up.onclick = function()
	{
		center_window('uping.php?dir='+this.title, 'up', 450, 40);
	};
};
function center_window(url, name, width, height)
{
	var left = (screen.width - width) / 2;
	var top = (screen.height - height) / 2;
	window.open(url, name, 'width='+width+', height='+height+', top = '+top+', left = '+left);
}