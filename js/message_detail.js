window.onload = function()
{
	var del = document.getElementById('del');
	if(ret = document.getElementById('return_send'))
		ret.onclick = function()
		{
			history.back();
		}
	else if(ret = document.getElementById('return_receive'))
		ret.onclick = function()
		{
			window.location.href="member_message_receive.php";
		}
	del.onclick = function()
	{
		if(confirm('你确定要删除吗?'))
			window.location.href = "message_detail.php?action=delete&id="+this.name;	
	};
};