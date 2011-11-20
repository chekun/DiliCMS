<div class="headbar">
	<div class="position"><span>模型</span><span>></span><span>模型管理</span><span>></span><span>内容模型管理</span></div>
	<div class="operating">
		<a class="hack_ie" href="<?php echo backend_url('model/add'); ?>"><button class="operating_btn" type="button"><span class="addition">添加新内容模型</span></button></a>
	</div>
	<div class="field">
		<table class="list_table">
			<col width="40px" />
			<col />
			<thead>
				<tr>
                	<th></th>
					<th>内容模型标识</th>
					<th>内容模型名称</th>
                    <th>操作选项</th>
				</tr>
			</thead>
		</table>
	</div>
</div>

<div class="content">
		<table id="list_table" class="list_table">
			<col width="40px" />
			<col />
			<tbody>
            <?php foreach($list as $v) : ?>
            	<tr>
                	<td></td>
                	<td><?php echo $v->name; ?></td>
                    <td><?php echo $v->description; ?></td>
                    <td>
                    	<a href="<?php echo backend_url('model/edit/'.$v->id); ?>"><img class="operator" src="images/icon_edit.gif" alt="修改" title="修改"></a>
                        <a class="confirm_delete" href="<?php echo backend_url('model/del/'.$v->id); ?>"><img class="operator" src="images/icon_del.gif" alt="删除" title="删除"></a>
                        <a href="<?php echo backend_url('model/fields/'.$v->id); ?>">字段管理</a>
                        <a href="<?php echo backend_url('content/view/','model='.$v->name); ?>">列表</a>
                    </td>
                </tr>
            <?php endforeach; ?>
			</tbody>
		</table>
</div>
<script language="javascript">
	$('a.confirm_delete').click(function(){
		return confirm('是否要删除所选内容模型？');	
	});
</script>