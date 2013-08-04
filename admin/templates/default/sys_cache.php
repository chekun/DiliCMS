<?php if ( ! defined('IN_DILICMS')) exit('No direct script access allowed');?>
<div class="headbar">
	<div class="position"><span>系统</span><span>></span><span>更新缓存</span></div>
	<div class="operating">
		<a href="javascript:void(0)" onclick="selectAll('cache[]');"><button class="operating_btn" type="button"><span class="sel_all">全选</span></button></a>
		<a href="javascript:;" onclick="$('#cache_form').submit();"><button class="operating_btn" type="button"><span class="remove">更新</span></button></a>
	</div>
	<div class="field">
		<table class="list_table">
			<col width="40px" />
			<col />
			<thead>
				<tr>
					<th>选择</th>
					<th>缓存名称</th>
				</tr>
			</thead>
		</table>
	</div>
</div>

<div class="content">
    <?php echo form_open('system/cache', array('id' => 'cache_form', 'name' => 'cache_form')); ?>
		<table id="list_table" class="list_table">
			<col width="40px" />
			<col />
			<tbody>
            	<tr>
                	<td><input type="checkbox" value="model" name="cache[]" /></td>
                    <td>内容模型缓存</td>
                </tr>
                <tr>
                	<td><input type="checkbox" value="category" name="cache[]" /></td>
                    <td>分类模型缓存</td>
                </tr>
                <tr>
                	<td><input type="checkbox" value="menu" name="cache[]" /></td>
                    <td>菜单缓存</td>
                </tr>
                <tr>
                	<td><input type="checkbox" value="role" name="cache[]" /></td>
                    <td>权限数据缓存</td>
                </tr>
                <tr>
                	<td><input type="checkbox" value="site" name="cache[]" /></td>
                    <td>站点设置缓存</td>
                </tr>
                <tr>
                	<td><input type="checkbox" value="backend" name="cache[]" /></td>
                    <td>后台设置缓存</td>
                </tr>
                <tr>
                	<td><input type="checkbox" value="plugin" name="cache[]" /></td>
                    <td>插件缓存</td>
                </tr>
                <tr>
                	<td><input type="checkbox" value="fieldtypes" name="cache[]" /></td>
                    <td>字段类型缓存</td>
                </tr>
                
			</tbody>
		</table>
	<?php echo form_close(); ?>
</div>
