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
	 }

    //kindeditor 初始化绑定
    if (typeof KindEditor !== 'undefined') {
        KindEditor.ready(function(K) {
            $('textarea[data-editor="kindeditor"]').each(function(k, item) {
                var $this = $(item);
                var allowUpload = $this.data('upload');
                var uploadUrl = $this.data('url');
                var width = $this.data('editor-width');
                var height = $this.data('editor-height');
                if ($this.data('editor-mode') == 'simple') {
                    K.create(item, {
                        resizeType : 1,
                        allowPreviewEmoticons : false,
                        allowImageUpload : false,
                        width: width,
                        height: height,
                        items : [
                            'fontname', 'fontsize', '|', 'forecolor', 'hilitecolor', 'bold', 'italic', 'underline',
                            'removeformat', '|', 'justifyleft', 'justifycenter', 'justifyright', 'insertorderedlist',
                            'insertunorderedlist', '|', 'emoticons', 'image', 'link', '|', 'about']
                    });
                } else {
                    K.create(item, {
                        resizeType : 1,
                        allowImageUpload : allowUpload,
                        allowFlashUpload: allowUpload,
                        allowMediaUpload: allowUpload,
                        allowFileUpload: allowUpload,
                        uploadJson: uploadUrl,
                        afterUpload : function(url, data) {
                            after_editor_upload(data);
                        },
                        width: width,
                        height: height,
                        items: [
                            'source', '|', 'undo', 'redo', '|', 'preview', 'print', 'template', 'code', 'cut', 'copy', 'paste',
                            'plainpaste', 'wordpaste', '|', 'justifyleft', 'justifycenter', 'justifyright',
                            'justifyfull', 'insertorderedlist', 'insertunorderedlist', 'indent', 'outdent', 'subscript',
                            'superscript', 'clearhtml', 'quickformat', 'selectall', '|', 'fullscreen', '/',
                            'formatblock', 'fontname', 'fontsize', '|', 'forecolor', 'hilitecolor', 'bold',
                            'italic', 'underline', 'strikethrough', 'lineheight', 'removeformat', '|', 'image',
                            'flash', 'media', 'insertfile', 'table', 'hr', 'emoticons', 'baidumap', 'pagebreak',
                            'anchor', 'link', 'unlink', '|', 'about'
                        ]
                    });
                }
            });
        });
    }

    //缩略图设置
    if ($('.thumb-control').length == 1) {

        (function() {
            var thumbControl = $('#thumb-preferences');
            $('input[name="hasattach"]').change(function() {
                if ($(this).val() == 1) {
                    thumbControl.show();
                } else {
                    thumbControl.hide();
                }
            });
        })();

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