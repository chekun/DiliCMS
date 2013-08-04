<?php if ( ! defined('IN_DILICMS')) exit('No direct script access allowed');?>
<div class="headbar">
	<div class="position"><span>系统</span><span>></span><span>修改密码</span></div>
</div>
<div class="content_box">
	<div class="content form_content">
        <?php echo form_open('system/password'); ?>
			<input type='hidden' name='id' />
			<table class="form_table">
				<col width="150px" />
				<col />
				<tr>
					<th>旧密码：</th>
					<td>
						<input name='old_pass' type='password' class='normal' id="old_pass"  />
						<label>*<?php echo form_error('old_pass'); ?></label>
					</td>
				</tr>
		  		<tr>
					<th>新密码：</th>
					<td>
						<input name='new_pass' type='password' class='normal' id="new_pass" />
						<label>*<?php echo form_error('new_pass'); ?></label>
					</td>
				</tr>

				<tr>
					<th>确认新密码：</th>
					<td>
						<input name='new_pass_confirm' type='password' class='normal' id="new_pass_confirm"  />
						<label>*<?php echo form_error('new_pass_confirm'); ?></label>
					</td>
				</tr>
				<tr><td></td><td><button class="submit" type="submit"><span>保 存</span></button></td></tr>
			</table>
		<?php echo form_close(); ?>
	</div>
</div>
