<div class="headbar">
	<div class="position"><span>插件</span><span>></span><span>插件管理</span><span>></span><span>设计新插件</span></div>
</div>
<div class="content_box">
	<div class="content form_content">
		<form action="<?php echo backend_url('plugin/import'); ?>"  method="post">
			<table class="form_table"  >
				<col width="150px" />
				<col />
				<tr>
					<th> 安装文件URL：</th>
					<td><input name="plugin" class="normal" /><label>*安装文件的XML文件地址!</label></td>
				</tr>
				<tr>
					<th></th>
					<td>
						<button class="submit" type='submit'><span>导入插件</span></button>
					</td>
				</tr>
			</table>
		</form>
        
	</div>
</div>