<?php if ( ! defined('IN_DILICMS')) exit('No direct script access allowed');?>
<div class="headbar">
	<div class="position"><span>插件</span><span>></span><span>插件管理</span><span>></span><span>设计新插件</span></div>
</div>
<div class="content_box">
	<div class="content form_content">
        <?php echo form_open('plugin/add'); ?>
			<table class="form_table"  >
				<col width="150px" />
				<col />
				<tr>
					<th> 插件标识：</th>
					<td><?php $this->form->show('name','input',''); ?><label>*3-40位的仅包含字母数字以及下划线破折号的字符，必须唯一，将作为文件夹名。</label><?php echo form_error('name'); ?></td>
				</tr>
                <tr>
					<th> 插件名称：</th>
					<td><?php $this->form->show('title','input',''); ?><label>*有意义的名称，最大50个字符。</label><?php echo form_error('title'); ?></td>
				</tr>
                <tr>
					<th> 插件版本：</th>
					<td><?php $this->form->show('version','input','',1); ?>
					<label>*插件版本，最大5个字符。</label>
					<?php echo form_error('version'); ?></td>
				</tr>
                <tr>
					<th> 插件描述：</th>
					<td><?php $this->form->show('description','textarea',''); ?><label>插件描述，最大200个字符。</label><?php echo form_error('description'); ?></td>
				</tr>
                <tr>
					<th> 插件作者：</th>
					<td><?php $this->form->show('author','input',''); ?><label>*插件作者，最大20个字符。</label><?php echo form_error('author'); ?></td>
				</tr>
                <tr>
					<th> 插件网址：</th>
					<td><?php $this->form->show('link','input',''); ?><label>合法的URL地址。</label><?php echo form_error('link'); ?></td>
				</tr>
                <tr>
					<th> 插件版权：</th>
					<td><?php $this->form->show('copyrights','input',''); ?><label>最大100个字符。</label><?php echo form_error('copyright'); ?></td>
				</tr>
                <tr>
					<th> 是否仅root可用：</th>
					<td><?php $this->form->show('access','radio',array('1'=>'是','0'=>'否')); ?><label>*选中则仅root用户可用。</label><?php echo form_error('access'); ?></td>
				</tr>
				<tr>
					<th></th>
					<td>
						<button class="submit" type='submit'><span>保存插件信息</span></button>
					</td>
				</tr>
			</table>
		<?php echo form_close(); ?>
	</div>
</div>