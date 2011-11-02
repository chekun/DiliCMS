<div class="headbar">
	<div class="position"><span>模型</span><span>></span><span>模型管理</span><span>></span><span><?php echo $model->description; ?></span><span>></span><span>字段管理</span></div>
	<div class="operating">
		<a class="hack_ie" href="<?php echo backend_url($this->uri->rsegment(1).'/add_field/'.$model->id); ?>"><button class="operating_btn" type="button"><span class="addition">添加新字段</span></button></a>
	</div>
	<div class="field">
		<table class="list_table">
			<col width="40px" />
			<col />
			<thead>
				<tr>
                	<th></th>
					<th>显示顺序</th>
					<th>字段标识</th>
                    <th>字段名称</th>
                    <th>字段类型</th>
                    <th>管理选项</th>
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
                	<td><?php echo $v->order; ?></td>
                    <td><?php echo $v->name; ?></td>
                    <td><?php echo $v->description; ?></td>
                    <td><?php $fieldtypes = & setting('fieldtypes');echo $fieldtypes[$v->type];?></td>
                    <td>
                    	<a href="<?php echo backend_url($this->uri->rsegment(1).'/edit_field/'.$v->id); ?>"><img class="operator" src="images/icon_edit.gif" alt="修改" title="修改"></a>
                        <a class="confirm_delete" href="<?php echo backend_url($this->uri->rsegment(1).'/del_field/'.$v->id); ?>"><img class="operator" src="images/icon_del.gif" alt="删除" title="删除"></a>
                    </td>
                </tr>
            <?php endforeach; ?>
			</tbody>
		</table>
</div>
<script language="javascript">
	$('a.confirm_delete').click(function(){
		return confirm('是否要删除所选字段？');	
	});
</script>