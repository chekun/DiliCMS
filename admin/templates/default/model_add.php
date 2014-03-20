<?php if ( ! defined('IN_DILICMS')) exit('No direct script access allowed');?>
<div class="headbar">
	<div class="position"><?=$bread?></div>
</div>
<div class="content_box">
	<div class="content form_content">
        <?php echo form_open('model/add'); ?>
			<table class="form_table"  >
				<col width="150px" />
				<col />
				<tr>
					<th> 内容模型标识：</th>
					<td><?php $this->form->show('name','input',''); ?><label>*3-20位的仅包含字母数字以及下划线破折号的字符，将用作数据库表名。</label><?php echo form_error('name'); ?></td>
				</tr>
                <tr>
					<th> 内容模型名称：</th>
					<td><?php $this->form->show('description','input',''); ?><label>*有意义的名称，最大40个字符。</label><?php echo form_error('description'); ?></td>
				</tr>
                <tr>
					<th> 每页显示条数：</th>
					<td><?php $this->form->show('perpage','input','20'); ?><label>*每页列表的的显示数目。</label><?php echo form_error('perpage'); ?></td>
				</tr>
                <tr>
					<th> 是否使用上传控件：</th>
					<td>
                    	<?php $this->form->show('hasattach','radio',array('1'=>'是','0'=>'否'),'0'); ?>
                        <label>是否使用上传控件，根据实际需求选择。</label>
                    </td>
				</tr>
                <tr id="thumb-preferences" class="thumb-control" style="display: none">
                    <th> 缩略图设置：</th>
                    <td>
                        <table style="width:300px;text-align: center">
                            <?php $preferences = json_decode(setting('thumbs_preferences')); ?>
                            <?php if ($preferences and is_array($preferences) and count($preferences) > 1): ?>
                                <tr>
                                    <td>启用</td>
                                    <td>尺寸</td>
                                    <td>默认</td>
                                </tr>
                                <?php foreach ($preferences as $pref): ?>
                                    <tr>
                                        <td>
                                            <input type="checkbox" name="thumbnail[<?php echo $pref->size; ?>]" value="1">
                                        </td>
                                        <td><?php echo $pref->size; ?></td>
                                        <td><input type="radio" value="<?php echo $pref->size; ?>" name="thumb_default"></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="3">
                                        还没有缩略图预设设置，<a href="<?php echo site_url('setting/site?tab=site_attachment'); ?>">立即去设置</a>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </table>
                    </td>
                </tr>
				<tr>
					<th></th>
					<td>
						<button class="submit" type='submit'><span>添加内容模型</span></button>
					</td>
				</tr>
			</table>
		<?php echo form_close(); ?>
	</div>
</div>