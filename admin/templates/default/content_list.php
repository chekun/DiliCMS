<?php if ( ! defined('IN_DILICMS')) exit('No direct script access allowed');?>
<script src="js/DatePicker/WdatePicker.js" type="text/javascript"></script>
<script src="js/colorPicker/colorpicker.js" type="text/javascript"></script>
<script src="js/dili_utility/jquery.ld.js" type="text/javascript"></script>
<link rel="stylesheet" media="screen" type="text/css" href="js/colorPicker/css/colorpicker.css" />
<div class="headbar">
	<div class="position"><?=$bread?></div>
	<div class="operating" style="position:relative; overflow:visible ">
    	<a href="javascript:void(0)" onclick="selectAll('id[]');"><button class="operating_btn" type="button"><span class="sel_all">全选</span></button></a>
		<a class="hack_ie" href="<?php echo backend_url('content/form','model='.$model['name']); ?>"><button class="operating_btn" type="button"><span class="addition">添加</span></button></a>
        <a href="javascript:void(0)" onclick="multi_delete();"><button class="operating_btn" type="button"><span class="delete">批量删除</span></button></a>
        <?php if($model['searchable']) : ?>
            <a href="javascript:void(0)" onclick="$('#content_search_form').slideToggle('slow');" ><button class="operating_btn" type="button"><span class="remove">筛选</span></button></a>
            <div id="content_search_form">
                <?php echo form_open('content/view?model='.$model['name']); ?>
                    <table class="form_table">
                        <colgroup><col width="150px"><col></colgroup><tbody>
                        <?php foreach($model['searchable'] as $v): ?>
                        <tr>
                            <td><?php echo $model['fields'][$v]['description']; ?></td>
                            <td>
                                <?php $this->field_behavior->on_search($model['fields'][$v],(isset($provider['where'][$model['fields'][$v]['name']]) ? $provider['where'][$model['fields'][$v]['name']] : '' )); ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        <tr>
                            <td></td>
                            <td><button class="submit" type="submit"><span>搜索</span></button></td>
                        </tr>
                    </tbody></table>
                <?php echo form_close(); ?>
            </div>
        <?php endif; ?>
		<?php $this->plugin_manager->trigger('buttons'); ?>
	</div>
	<div class="field">
		<table class="list_table">
			<col width="40px" />
			<col />
			<thead>
				<tr>
                	<th></th>
                	<th>发布时间</th>
					<?php foreach($model['listable'] as $v): ?>
        			<th><?php echo $model['fields'][$v]['description']; ?></th>
    				<?php endforeach; ?>
                    <th>操作选项</th>
				</tr>
			</thead>
		</table>
	</div>
</div>

<div class="content">
    <?php echo form_open('content/del?model='.$model['name'], array('id' => 'content_list_form')); ?>
		<table id="list_table" class="list_table">
			<col width="40px" />
			<col />
			<tbody>
            <?php foreach($provider['list'] as $v) : ?>
            	<tr>
                	<td><input type="checkbox" name="id[]" value="<?php echo $v->id; ?>" /></td>
                	<td><?php echo date('Y-m-d', $v->create_time); ?></td>
					<?php foreach($model['listable'] as $vt): ?>
                    <td>
                    <?php $this->field_behavior->on_list($model['fields'][$vt],$v); ?>
                    </td>
                 <?php endforeach; ?>
                    <td>
                    	<a href="<?php echo backend_url('content/form/','model='.$model['name'].'&id='.$v->id); ?>"><img class="operator" src="images/icon_edit.gif" alt="修改" title="修改"></a>
                        <a class="confirm_delete" href="<?php echo backend_url('content/del','model='.$model['name'].'&id='.$v->id); ?>"><img class="operator" src="images/icon_del.gif" alt="删除" title="删除"></a>
                        <?php $this->plugin_manager->trigger('row_buttons', $v); ?>
                    </td>
                </tr>
            <?php endforeach; ?>
			</tbody>
		</table>
    <?php echo form_close(); ?>
</div>
<div class="pages_bar pagination"><?php echo $provider['pagination']; ?></div>
<script language="javascript">
	var confirm_str = '是否要删除所选信息？\n此操作还会删除附件等关联信息!';
	$('a.confirm_delete').click(function(){
		return confirm(confirm_str);	
	});
	function multi_delete()
	{
		if($(":checkbox[name='id[]']:checked").length  <= 0)
		{
				alert('请先选择要删除的信息!');
				return false;
		}
		else
		{
			if(confirm(confirm_str))
			{
				$('#content_list_form').submit();
			}
			else
			{
				return false;	
			}
		}
	}
</script>
<script src="js/dili_utility/content_form.js" type="text/javascript"></script>
<?php $this->plugin_manager->trigger('listed', $provider['list']); ?>
