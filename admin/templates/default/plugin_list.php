<?php if ( ! defined('IN_DILICMS')) exit('No direct script access allowed');?>
<div class="headbar">
	<div class="position"><span>插件</span><span>></span><span>插件管理</span><span>></span><span>插件列表</span></div>
	<div class="operating">
    	<a class="hack_ie" href="javascript:void(0)" onclick="selectAll('id[]');"><button class="operating_btn" type="button"><span class="sel_all">全选</span></button></a>
		<a class="hack_ie" href="<?php echo backend_url('plugin/add'); ?>"><button class="operating_btn" type="button"><span class="addition">设计新插件</span></button></a>
        <a class="hack_ie" onclick="operate_plugins('<?php echo backend_url('plugin/active'); ?>');" href="javascript:void(0);"><button class="operating_btn" type="button"><span class="recover">启用所选插件</span></button></a>
        <a class="hack_ie" href="javascript:void(0);" onclick="operate_plugins('<?php echo backend_url('plugin/deactive'); ?>');"><button class="operating_btn" type="button"><span class="delete">禁用所选插件</span></button></a>
        <a class="hack_ie" onclick="operate_plugins('<?php echo backend_url('plugin/export'); ?>');" href="javascript:void(0);"><button class="operating_btn" type="button"><span class="download">导出插件</span></button></a>
        <a class="hack_ie" href="<?php echo backend_url('plugin/import'); ?>"><button class="operating_btn" type="button"><span class="import">导入插件</span></button></a>
	</div>
	<div class="field">
		<table class="list_table">
			<col width="40px" />
			<col />
			<thead>
				<tr>
                	<th></th>
					<th>插件标识</th>
					<th>插件名称</th>
                    <th>插件作者</th>
                    <th>是否启用</th>
                    <th>操作选项</th>
				</tr>
			</thead>
		</table>
	</div>
</div>

<div class="content">
        <?php echo form_open('', array('id' => 'plugin_list_form')); ?>
		<table id="list_table" class="list_table">
			<col width="40px" />
			<col />
			<tbody>
            <?php foreach($list as $v) : ?>
            	<tr>
                	<td><input type="checkbox" name="id[]" value="<?php echo $v->id;  ?>" /></td>
                	<td><?php echo $v->name; ?></td>
                    <td><?php echo $v->title; ?></td>
                    <td><?php echo $v->author; ?></td>
                    <td><?php echo $v->active ? '已启用' : '未启用' ; ?></td>
                    <td>
                    	<?php if(file_exists(FCPATH.'plugins/'.$v->name).'/plugin_'.$v->name.'_install.xml'): ?>
                    	<a href="<?php echo base_url().'plugins/'.$v->name.'/plugin_'.$v->name.'_install.xml'; ?>" target="_blank"><img class="operator" src="images/icon_down.gif" alt="下载XML文件" title="下载XML文件"></a>
                        <?php endif; ?>
                    	<a href="<?php echo backend_url('plugin/edit/'.$v->id); ?>"><img class="operator" src="images/icon_edit.gif" alt="修改" title="修改"></a>
                        <a class="confirm_delete" href="<?php echo backend_url('plugin/del').'?id='.$v->id; ?>"><img class="operator" src="images/icon_del.gif" alt="卸载" title="卸载"></a>
                    </td>
                </tr>
            <?php endforeach; ?>
			</tbody>
		</table>
        <?php echo form_close(); ?>
</div>
<script language="javascript">
	$('a.confirm_delete').click(function(){
		return confirm('是否要卸载所选插件？');	
	});
	
	function selected_plugins()
	{
		if($(":checkbox[name='id[]']:checked").length  <= 0)
		{
				alert('请先选择要操作的插件!');
				return false;
		}
		return true;
	}
	
	function operate_plugins(action)
	{
		if(selected_plugins())
		{
				$('#plugin_list_form').attr('action',action).submit();
		}
		return false;
	}
</script>