<?php if ( ! defined('IN_DILICMS')) exit('No direct script access allowed');?>
<div class="headbar">
	<div class="position"><span>插件</span><span>></span><span>插件管理</span><span>></span><span>设计新插件</span></div>
</div>
<div class="content_box">
	<div class="content form_content">
        <?php echo form_open('plugin/import'); ?>
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
		<?php echo form_close(); ?>
	</div>
</div>