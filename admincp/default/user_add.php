<div class="headbar">
	<div class="position"><span>系统</span><span>></span><span>用户管理</span><span>></span><span>添加用户</span></div>
</div>
<div class="content_box">
	<div class="content form_content">
		<form action="<?php echo backend_url('user/add'); ?>"  method="post">
			<table class="form_table">
				<col width="150px" />
				<col />
				<tr>
					<th> 用户名称：</th>
					<td><?php $this->form->show('username','input',''); ?><label>*3-16位用户名称.</label><?php echo form_error('username'); ?></td>
				</tr>
                <tr>
					<th> 用户密码：</th>
					<td><input class="normal" type="password" maxlength="16" name="password" /><label>*6-16位用户密码.</label><?php echo form_error('password'); ?></td>
				</tr>
                <tr>
					<th> 重复用户密码：</th>
					<td><input class="normal" type="password" maxlength="16" name="confirm_password" /><label>*6-16位用户密码.</label><?php echo form_error('confirm_password'); ?></td>
				</tr>
                <tr>
					<th> 用户EMAIL：</th>
					<td><?php $this->form->show('email','input',''); ?><label>*有效的EMAIL地址.</label><?php echo form_error('email'); ?></td>
				</tr>
                <tr>
					<th> 用户组：</th>
					<td><?php $this->form->show('role','select',$roles); ?><label>*设置用户组.</label><?php echo form_error('role'); ?></td>
				</tr>
				<tr>
					<th></th>
					<td>
						<button class="submit" type='submit'><span>添加用户</span></button>
					</td>
				</tr>
			</table>
		</form>
        
	</div>
</div>