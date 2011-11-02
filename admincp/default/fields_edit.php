
<div class="headbar">
	<div class="position"><span>模型</span><span>></span><span>模型管理</span><span>></span><span><?php echo $model->description; ?></span><span>></span><span>编辑字段</span></div>
    
</div>
<div class="content_box">
	<div class="content form_content">
		<form action="<?php echo backend_url($this->uri->rsegment(1).'/edit_field/'.$field->id); ?>"  method="post">
			<table class="form_table" >
				<col width="150px" />
				<col />
				<tr>
					<th> 字段标识：</th>
					<td><?php $this->form->show('name','input','',$field->name); ?><label>*字段名称，3-20位字母。</label><?php echo form_error('name'); ?></td>
				</tr>
                <tr>
					<th> 字段名称：</th>
					<td><?php $this->form->show('description','input','',$field->description); ?><label>*有意义的名称,最多40个字符。</label><?php echo form_error('description'); ?></td>
				</tr>
                <tr>
					<th> 字段类型：</th>
					<td><?php $this->form->show('type','select',setting('fieldtypes'),$field->type); ?><label>*选择一个适当的字段类型。</label><?php echo form_error('type'); ?></td>
				</tr>
                <tr>
					<th> 字段长度：</th>
					<td><?php $this->form->show('length','input','',$field->length); ?><label>设置一个适当的字段长度,可以不填写，参看默认值.</label><?php echo form_error('length'); ?></td>
				</tr>
                
                <tr>
					<th> 数据源：</th>
					<td><?php $this->form->show('values','input','',$field->values); ?><label>数据源，使用方式见手册。</label><?php echo form_error('values'); ?></td>
				</tr>
                <tr>
					<th> 显示尺寸：</th>
					<td>
						宽：<?php $this->form->show('width','input','',$field->width); ?><label>*显示的宽度,单位为px</label><?php echo form_error('width'); ?><br  />
                    	高：<?php $this->form->show('height','input','',$field->height); ?><label>*显示的高度,单位为px</label><?php echo form_error('height'); ?>
                    </td>
				</tr>
                <tr>
					<th> 验证规则：</th>
					<td><?php $this->form->show('rules','checkbox',setting('validation'),$field->rules); ?><label></label><?php echo form_error('rules'); ?></td>
				</tr>
                <tr>
					<th> 规则说明：</th>
					<td><?php $this->form->show('ruledescription','input','',$field->ruledescription); ?><label></label><?php echo form_error('ruledescription'); ?></td>
				</tr>
                <tr>
					<th> 管理选项：</th>
					<td>
                    	<?php $this->form->show('searchable','checkbox','是否加入搜索',$field->searchable);?>
                		<?php $this->form->show('listable','checkbox','是否列表显示',$field->listable); ?>
                        <?php $this->form->show('editable','checkbox','是否允许编辑',$field->editable); ?>
                    </td>
				</tr>
                <tr>
					<th> 显示顺序：</th>
					<td><?php $this->form->show('order','input','',$field->order); ?><label></label><?php echo form_error('order'); ?></td>
				</tr>
				<tr>
					<th></th>
					<td>
						<button class="submit" type='submit'><span>修改字段</span></button>
					</td>
				</tr>
			</table>
		</form>
        
	</div>
</div>