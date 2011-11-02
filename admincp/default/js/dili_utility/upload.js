
					var uploaderSwitcher,uploadedfile,uploaded,uploaderContainer,attachContainer,loading,clipboardContainer,clipboard,clipboardStatus;
					
					$(function()
					{
						$.ajaxSetup({
						  cache: false
						});
						uploaderSwitcher = $('#uploaderSwitcher');
						uploadedfile = $('#uploadedfile');
						uploaderContainer = $('#uploaderContainer');
						attachContainer = $('#attachList');
						uploaded = uploadedfile.val();
						loading = $('#loading');
						loading.ajaxStart( function(){$(this).show();} );
						loading.ajaxError( function(){$(this).show().text("操作失败");} );
						loading.ajaxComplete( function(){$(this).hide();} );
						if(uploaded != '0' && uploaded != '' && uploaded)
						{
							loading.text("更新附件列表中......");
							$.get(backend_url + 'attachment/list?ids='+uploaded,function(data)
							{
								data = data.split(',');	
								for(var v  in data)
								{
									v = data[v].split('|');
									attachContainer.append(insert_new_attachment(v));
									toggleUploader(true);
								}	
							}
							);	
						}
						$(window).resize(function(){
							if(uploaderContainer.css('display') != 'none'){
								toggleUploader(true);	
							}
						});
						
					});
					
					function insert_new_attachment(v)
					{
						html = "<li id=\"attachment_" + v[0] + "\"><span class=\"title\"><input type=\"text\" class=\"normal\" value=\""+ attachment_dir + v[4] + '/' + v[2] + '.' + v[5] + "\" />(未保存)</span>";
						if(v[3] == 1)
						{
							html += "<a href=\"javascript:void(0);\" onclick=\"\" target=\"_blank\">预览</a>";	
						}
						html += "<a href=\"javascript:void(0);\" onclick=\"if(confirm('是否要删除该附件?')){delete_attachment('"+v[0]+"');}\">删除</a>";	
						return html;
					}
					
					function delete_attachment(id)
					{
						loading.text("删除操作进行中......");
						$.get(backend_url + 'attachment/del?id='+id,function(data)
							{
								if(data == 'ok')
								{
									attachContainer.find('#attachment_'+id).remove();
								}
								else
								{
									alert('删除失败!');
								}
							}
							);	
					}	
					
					function swfHandler(trigger,type,data)
					{
						if(trigger == 2)
						{
							if(data){
								data = data.substr(0,data.length-1);
								data = data.split(',');	
								for(var v  in data)
								{
									v = data[v].split('|');
									uploaded += ',' + v[0] ;
									attachContainer.append(insert_new_attachment(v));
									toggleUploader();
								}
								uploadedfile.val(uploaded);
							}
							
						}
						else
						{
							return ;	
						}
						
					}
					
					function toggleUploader(force)
					{
						
						if(uploaderContainer.css('display') == 'none' || force == true)
						{
							if(force != true){
								uploaderContainer.show();
								uploaderSwitcher.text("关闭上传控件");	
							}	
						}
						else
						{
							uploaderContainer.hide();
							uploaderSwitcher.text("打开上传控件");	
						}
					}
					