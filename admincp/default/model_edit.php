
<div class="headbar">
	<div class="position"><span>模型</span><span>></span><span>模型管理</span><span>></span><span>编辑内容模型</span></div>
</div>
<div class="content_box">
	<div class="content form_content">
		<form action="<?php echo backend_url('model/edit/'.$model->id); ?>"  method="post">
			<table class="form_table dili_tabs" id="site_basic" >
				<col width="150px" />
				<col />
				<tr>
					<th> 内容模型标识：</th>
					<td><?php $this->form->show('name','input','',$model->name); ?><label>*3-20位的仅包含字母数字以及下划线破折号的字符，将用作数据库表名。</label><?php echo form_error('name'); ?></td>
				</tr>
                <tr>
					<th> 内容模型名称：</th>
					<td><?php $this->form->show('description','input','',$model->description); ?><label>*有意义的名称，最大40个字符。</label><?php echo form_error('description'); ?></td>
				</tr>
                <tr>
					<th> 每页显示条数：</th>
					<td><?php $this->form->show('perpage','input','',$model->perpage); ?><label>*每页列表的的显示数目。</label><?php echo form_error('perpage'); ?></td>
				</tr>
                <tr>
					<th> 是否使用上传控件：</th>
					<td>
                    	<?php $this->form->show('hasattach','radio',array('1'=>'是','0'=>'否'),$model->hasattach); ?>
                        <label>是否使用上传控件，根据实际需求选择。</label>
                    </td>
				</tr>
				<tr>
					<th></th>
					<td>
						<button class="submit" type='submit'><span>修改内容模型</span></button>
					</td>
				</tr>
			</table>
		</form>
        
	</div>
</div>