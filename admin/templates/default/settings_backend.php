<?php if ( ! defined('IN_DILICMS')) exit('No direct script access allowed');?>
<script src="js/xheditor/xheditor-zh-cn.min.js" type="text/javascript"></script>
<div class="headbar">
	<div class="position"><span>系统</span><span>></span><span>系统设置</span><span>></span><span>后台设置</span></div>
</div>
<div class="content_box">
	<div class="content form_content">
        <?php echo form_open('setting/backend'); ?>
			<!--基本设置!-->
			<table class="form_table dili_tabs" id="site_basic" >
				<col width="150px" />
				<col />
				<tr>
					<th> 后台主题：</th>
					<td><input type='text' name="backend_theme" class='normal' value="<?php echo $backend->backend_theme; ?>" disabled="disabled" autocomplete="off" /><label>暂不开放</label></td>
				</tr>
                <tr>
					<th> 后台语言：</th>
					<td><input type='text' class='normal' name="backend_lang"  value="<?php echo $backend->backend_lang; ?>" disabled="disabled" autocomplete="off" /><label>暂不开放</label></td>
				</tr>
                <tr>
					<th> 后台入口：</th>
					<td><input type='text' class='normal' name="backend_access_point" value="<?php echo $backend->backend_access_point; ?>" disabled="disabled" autocomplete="off" /><label>若改变，请务必相应的地方如文件夹名称等.</label></td>
				</tr>
                <tr>
					<th> 后台网页标题：</th>
					<td><input type='text' class='normal' name="backend_title" value="<?php echo $backend->backend_title; ?>" autocomplete="off" /><label>用于显示网页标题</label></td>
				</tr>
                <tr>
					<th> 后台LOGO：</th>
					<td><input type='text' class='normal' name="backend_logo" value="<?php echo $backend->backend_logo; ?>" autocomplete="off" /><label>左上角LOGO自定义路径</label></td>
				</tr>
				<tr>
					<th>  插件开发模式：</th>
					<td>
					    <input type="radio" name="plugin_dev_mode" <?php echo $backend->plugin_dev_mode ? 'checked="checked"' :''; ?> value="1" >开启
                        <input type="radio" name="plugin_dev_mode" value="0" <?php echo !$backend->plugin_dev_mode ? 'checked="checked"' :''; ?> >关闭
					    <label> 开启该选项后每次请求都会自动刷新插件缓存，非插件开发时候请勿开启</label>
					</td>
				</tr>
                <tr>
					<th> 是否允许root用户登录：</th>
					<td>
                    	<input type="radio" name="backend_root_access" <?php echo $backend->backend_root_access ? 'checked="checked"' :''; ?> value="1" >开启
                        <input type="radio" name="backend_root_access" value="0" <?php echo !$backend->backend_root_access ? 'checked="checked"' :''; ?> >关闭
                    </td>
				</tr>
                <tr>
                    <th> HTTP BASIC AUTH：</th>
                    <td>
                        <input type="radio" name="backend_http_auth_on" <?php echo $backend->backend_http_auth_on ? 'checked="checked"' :''; ?> value="1" >开启
                        <input type="radio" name="backend_http_auth_on" value="0" <?php echo !$backend->backend_http_auth_on ? 'checked="checked"' :''; ?> >关闭
                    </td>
                </tr>
                <tr>
                    <th> BASIC AUTH 用户名：</th>
                    <td><input type='text' class='normal' name="backend_http_auth_user" value="<?php echo $backend->backend_http_auth_user; ?>" autocomplete="off" /></td>
                </tr>
                <tr>
                    <th> BASIC AUTH 密码：</th>
                    <td><input type='text' class='normal' name="backend_http_auth_password" value="<?php echo $backend->backend_http_auth_password; ?>" autocomplete="off" /></td>
                </tr>
				<tr>
					<th></th>
					<td>
						<button class="submit" type='submit'><span>保存后台设置</span></button>
					</td>
				</tr>
			</table>
		<?php echo form_close(); ?>
	</div>
</div>