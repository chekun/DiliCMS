function initMenu(data,current,url)
{
	for(i in data)
	{
		if(data[i]['current'])
		{
			$('#menu ul').append('<li class="selected"><a href="#">'+data[i]['title']+'</a></li>');
            if(data[i]['current'])
            {
                var list = data[i]['list'];
                var item = '';
                for(j in list)
                {
                    item = '<li><span>'+j+'</span><ul name="menu">';
                    for(k in list[j])
                    {
                        if(k==current) item +='<li class="selected"><a href="'+url+k+'">'+list[j][k]+'</a></li>';
                        else item +='<li><a href="'+url+k+'">'+list[j][k]+'</a></li>';
                    }
                    $('.submenu').append(item+'</ul></li>');
                }
            }
		}
		else
		{
			$('#menu ul').append('<li><a href="'+url+data[i]['link']+'" hidefocus = "true">'+data[i]['title']+'</a></li>');
		}
	}
}
