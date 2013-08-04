<?php if ( ! defined('IN_DILICMS')) exit('No direct script access allowed');?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<base href="<?php echo base_url().'templates/'.setting('backend_theme').'/'; ?>" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo setting('backend_title');  ?>----Powered By DiliCMS</title>
<link rel="stylesheet" href="images/admin.css"  type="text/css" />
</head>
<body id="login">
	<div class="container">
		<div id="header">
			<div class="logo">
				<img src="<?php echo setting('backend_logo');  ?>" />
			</div>
		</div>
		<div id="wrapper" class="clearfix">
			<div class="login_box">
				<div class="login_title">管理登录</div>
				<div class="login_cont">
					<b style="color:red"><?php echo $this->session->flashdata('error'); ?></b>
                    <?php echo form_open('login/do'); ?>
						<table class="form_table">
							<col width="90px" />
							<col />
							<tr>
								<th>用户名：</th><td><input autocomplete="off" class="normal" type="text" name="username" alt="请填写用户名" /></td>
							</tr>
							<tr>
								<th>密码：</th><td><input class="normal" type="password" name="password" alt="请填写密码" /></td>
							</tr>
							<tr>
								<th></th><td><input class="submit" type="submit" value="登录" /><input class="submit" type="reset" value="取消" /></td>
							</tr>
						</table>
					<?php echo form_close(); ?>
				</div>
			</div>
		</div>
		<div id="footer">Power by <a href="http://www.dilicms.com/">DiliCMS</a> <b><?php echo DILICMS_VERSION; ?></b> Copyright &copy; 2011 - 2012
        </div>
	</div>
</body>
</html>
