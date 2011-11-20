jQuery(function(){
	//高度自适应
	initLayout();   
	$(window).resize(function(){   
		initLayout();   
	});
	function initLayout() {   
		var h1 = document.documentElement.clientHeight - $("#header").outerHeight(true) - $("#info_bar").height();   
		var h2 = h1 - $(".headbar").height() - $(".pages_bar").height() - 25; 
		$('#admin_left').height(h1); 
		$('#admin_right .content').height(h2);
	}
	//一级菜单切换
	$("#menu ul li:first-child").addClass("first");
	$("#menu ul li:last-child").addClass("last");
	$(":[name='menu']>li").click(function(){
		$(this).siblings().removeClass("selected");
        $(this).addClass("selected");					   
	});	
	//二级菜单展示效果
	$("ul.submenu>li>span").toggle(
		function(){
			$(this).next().css("display","none");
			$(this).addClass("selected");
		},
		function(){
			$(this).next().css("display","");
			$(this).removeClass("selected");
		}
	);
	//文字滚动显示
	$("#tips a:not(:first)").css("display","none");
	var tips_l=$("#tips a:last");
	var tips_f=$("#tips a:first");
	setInterval(function(){
		if($("#tips").children().length	!= 1){			 
			if(tips_l.is(":visible")){
				tips_f.fadeIn(500);
				tips_l.hide()
			}else{
				$("#tips a:visible").addClass("now");
				$("#tips a.now").next().fadeIn(500);
				$("#tips a.now").hide().removeClass("now");
			}
		}
	},3000);
	
	//关闭侧边栏
	$("#separator").click(function(){
			document.body.className = (document.body.className == "folden") ? "":"folden";
		}
	);
	
	//烦人的IE
	 if ( $.browser.msie ) {
		//绑定事件，来支持IE下<a><button>a失效的问题
		$('a.hack_ie').click(function(){
			location = $(this).attr('href');	
		});
		//kill the fucking focus
		$('button').attr('hideFocus',true);
		//恼人的IE6,7相对路径导致xheditorcss加载失败的问题
		if(parseInt($.browser.version) < 8)
		{
			$('<link id="xheCSS_nostyle" rel="stylesheet" type="text/css" href="js/xheditor/xheditor_skin/nostyle/ui.css">').appendTo($('head'));
		}
	 }
	 
	 
	
});

//全选全不选
function selectAll(nameVal)
{
	//获取复选框的form对象
	var formObj = $("form:has(:checkbox[name='"+nameVal+"'])");
	//根据form缓存数据判断批量全选方式
	if(formObj.data('selectType')=='none' || formObj.data('selectType')==undefined)
	{
		$(":checkbox[name='"+nameVal+"']:not(:checked)").attr('checked','checked');
		formObj.data('selectType','all');
	}
	else
	{
		$(":checkbox[name='"+nameVal+"']").removeAttr("checked");;
		formObj.data('selectType','none');
	}
}

//tabs
function select_tab(id,target)
{
	$('table.dili_tabs').hide();
	$('table#'+id).show();
	var li = $(target).parent();
	li.parent().children('li').removeClass('selected');
	li.addClass('selected');
}