window.onload = function()
{
	var message = document.getElementsByName("message");
	var friend = document.getElementsByName("friend");
	var flower = document.getElementsByName('flower');
	for(var i = 0; i < message.length; i++)
		message[i].onclick = function()
		{
			center_window('message.php?id='+this.title, 'message', 400, 300);
		};
	for(var i = 0; i < friend.length; i++)
		friend[i].onclick = function()
		{
			center_window('friend.php?id='+this.title, 'message', 400, 300);
		};
	for(var i = 0; i < friend.length; i++)
		flower[i].onclick = function()
		{
			center_window('flower.php?id='+this.title, 'message', 400, 300);
		};
	
};

function center_window(url, name, width, height)
{
	var left = (screen.width - width) / 2;
	var top = (screen.height - height) / 2;
	window.open(url, name, 'width='+width+', height='+height+', top = '+top+', left = '+left);
}