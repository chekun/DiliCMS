<?php if ( ! defined('IN_DILICMS')) exit('No direct script access allowed');?>
<div class="headbar">
	<div class="position"><?=$bread?></div>
</div>
<div class="content_box">
	<div class="content form_content">
        <?php echo form_open($this->uri->rsegment(1).'/add_field/'.$model->id); ?>
			<table class="form_table dili_tabs" id="site_basic" >
				<col width="150px" />
				<col />
				<tr>
					<th> 字段标识：</th>
					<td><?php $this->form->show('name','input',''); ?><label>*字段名称，3-20位字母。</label><?php echo form_error('name'); ?></td>
				</tr>
                <tr>
					<th> 字段名称：</th>
					<td><?php $this->form->show('description','input',''); ?><label>*有意义的名称,最多40个字符。</label><?php echo form_error('description'); ?></td>
				</tr>
                <tr>
					<th> 字段类型：</th>
					<td><?php $this->form->show('type','select',array_merge(setting('fieldtypes'),setting('extra_fieldtypes'))); ?><label>*选择一个适当的字段类型。</label><?php echo form_error('type'); ?></td>
				</tr>
                <tr>
					<th> 字段长度：</th>
					<td><?php $this->form->show('length','input',''); ?><label>设置一个适当的字段长度,可以不填写，参看默认值.</label><?php echo form_error('length'); ?></td>
				</tr>
                
                <tr>
					<th> 数据源：</th>
					<td><?php $this->form->show('values','input',''); ?><label>可以为某些字段类型提供数据源或者默认值，使用方式见手册。</label><?php echo form_error('values'); ?></td>
				</tr>
                <tr>
					<th> 显示尺寸：</th>
					<td>
						宽：<?php $this->form->show('width','input',''); ?><label>*表单控件的显示的宽度,单位为px</label><?php echo form_error('width'); ?><br  />
                    	高：<?php $this->form->show('height','input',''); ?><label>*表单控件的显示的高度,单位为px</label><?php echo form_error('height'); ?>
                    </td>
				</tr>
                <tr>
					<th> 验证规则：</th>
					<td><?php $this->form->show('rules','checkbox',setting('validation')); ?><label></label><?php echo form_error('rules'); ?></td>
				</tr>
                <tr>
					<th> 规则说明：</th>
					<td><?php $this->form->show('ruledescription','input',''); ?><label></label><?php echo form_error('ruledescription'); ?></td>
				</tr>
                <tr>
					<th> 管理选项：</th>
					<td>
                    	<?php $this->form->show('searchable','checkbox','是否加入搜索');?>
                		<?php $this->form->show('listable','checkbox','是否列表显示'); ?>
                        <?php $this->form->show('editable','checkbox','是否允许编辑'); ?>
                    </td>
				</tr>
                <tr>
					<th> 显示顺序：</th>
					<td><?php $this->form->show('order','input',''); ?><label></label><?php echo form_error('order'); ?></td>
				</tr>
				<tr>
					<th></th>
					<td>
						<button class="submit" type='submit'><span>添加新字段</span></button>
					</td>
				</tr>
				<tr>
					<th></th>
					<td>
						<div class="red_box">友情提示:1个模型中必须要有1个字段设置1个验证规则，否则无法添加数据。</div>
					</td>
				</tr>
			</table>
		<?php echo form_close(); ?>
	</div>
</div>