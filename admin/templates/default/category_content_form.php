<?php if ( ! defined('IN_DILICMS')) exit('No direct script access allowed');?>
<link rel="stylesheet" href="js/kindeditor/themes/default/default.css" />
<script charset="utf-8" src="js/kindeditor/kindeditor-all-min.js"></script>
<script charset="utf-8" src="js/kindeditor/lang/zh_CN.js"></script>
<script src="js/DatePicker/WdatePicker.js" type="text/javascript"></script>
<script src="js/colorPicker/colorpicker.js" type="text/javascript"></script>
<script src="js/dili_utility/jquery.ld.js" type="text/javascript"></script>
<link rel="stylesheet" media="screen" type="text/css" href="js/colorPicker/css/colorpicker.css" />
<script src="js/dili_utility/content_form.js" type="text/javascript"></script>
<div class="headbar">
	<div class="position"><?=$bread?></div>
	<?php if($model['hasattach']): ?>
        <div class="operating" style="overflow:visible">
            <div class="search f_r" style="position:relative">
                <button class="btn" type="button" hidefocus="true"  onclick="$('#dili_uploader').toggle('slow');"><span class="add">附件列表</span></button>
                <?php $this->load->view('sys_uploader'); ?>
            </div>
        </div>
    <?php endif; ?>
</div>
<div class="content_box">
	<div class="content form_content">
		<?php echo form_open_multipart('category_content/save?model='.$model['name'].'&id='.(isset($content['classid']) ? $content['classid'] : '')); ?>
				<table class="form_table" >
				<col width="150px" />
				<col />
				<?php foreach( $model['fields'] as $v) :  ?>
                <?php if($v['editable']): ?>
                <tr>
					<th> <?php echo $v['description'];?>：</th>
					<td>
						<?php $this->field_behavior->on_form($v , isset($content[$v['name']]) ? $content[$v['name']] : '', TRUE, $model['hasattach']); ?>
						<?php echo form_error($v['name']); ?>
                    </td>
				</tr>
                <?php endif; ?>
                <?php $this->plugin_manager->trigger('rendered', $content); ?>
                <?php endforeach; ?>
				<tr>
					<th></th>
					<td>
                    	<?php if($model['hasattach']): ?>
                        <?php $this->form->show_hidden('uploadedfile','0',true); ?>
                        <?php endif; ?>
						<?php $this->form->show_hidden('parentid', $parentid ,true); ?>
                    	<button class="submit" type='submit'><span><?php echo $button_name; ?></span></button>
					</td>
				</tr>
			</table>
		<?php echo form_close(); ?>
	</div>
</div>

