<?php if ( ! defined('IN_DILICMS')) exit('No direct script access allowed');?>
<div class="headbar">
	<div class="position"><?=$bread?></div>
</div>
<div class="content_box">
	<div class="content form_content">
        <?php echo form_open('category/add'); ?>
			<table class="form_table dili_tabs" id="site_basic" >
				<col width="150px" />
				<col />
				<tr>
					<th> 分类模型标识：</th>
					<td><?php $this->form->show('name','input',''); ?><label>*3-20位的仅包含字母数字以及下划线破折号的字符，将用作数据库表名。</label><?php echo form_error('name'); ?></td>
				</tr>
                <tr>
					<th> 分类模型名称：</th>
					<td><?php $this->form->show('description','input',''); ?><label>*有意义的名称，最大40个字符。</label><?php echo form_error('description'); ?></td>
				</tr>
                <tr>
					<th> 分类模型层级：</th>
					<td><?php $this->form->show('level','input',''); ?><label>*分类的层级。</label><?php echo form_error('level'); ?></td>
				</tr>
                <tr>
					<th> 每页显示条数：</th>
					<td><?php $this->form->show('perpage','input',''); ?><label>*每页列表的的显示数目。</label><?php echo form_error('perpage'); ?></td>
				</tr>
                <tr>
					<th> 是否使用上传控件：</th>
					<td>
                    	<?php $this->form->show('hasattach','radio',array('1'=>'是','0'=>'否'),'0'); ?>
                        <label>是否使用上传控件，根据实际需求选择。</label>
                    </td>
				</tr>
                <tr>
                    <th> 是否自动更新缓存：</th>
                    <td>
                        <?php $this->form->show('auto_update', 'radio', array('1'=>'是','0'=>'否'),'0'); ?>
                        <label>操作不频繁的分类模型建议开启此选项.</label>
                    </td>
                </tr>
				<tr>
					<th></th>
					<td>
						<button class="submit" type='submit'><span>添加新分类模型</span></button>
					</td>
				</tr>
			</table>
		<?php echo form_close(); ?>
	</div>
</div>