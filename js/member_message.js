window.onload = function()
{
	var all = document.getElementById('all');
	var form = document.getElementsByTagName('form')[0];	
	all.onclick = function()
	{
		for(var i = 0; i < form.elements.length; i++)
		{
			//document.elements获取表单元素中的所有的input或者textarea
			if(form.elements[i].name != 'check')
				form.elements[i].checked = form.check.checked;
		} 
	} ;
	form.onsubmit = function()
	{
		if(confirm('确定要删除这批数据吗?'))
			return true;
		return false;
	}
};