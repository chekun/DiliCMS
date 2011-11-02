<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title><?php echo setting('backend_title'); ?>----Powered By DiliCMS</title>
<base href="<?php echo base_url().'admincp/'.setting('backend_theme').'/'; ?>" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" href="images/admin.css" />
<script language="javascript" src="js/jquery.js"></script>
<script language="javascript" src="js/admin.js"></script>
</head>
<body>
<div class="container">
	<div id="header">
		<div class="logo">
			<img src="<?php echo setting('backend_logo');  ?>" />
		</div>
		<div id="menu">
			<ul name="menu">
                <?php $this->acl->show_top_menus(); ?>
            </ul>
		</div>
		<p>
        	<a href="<?php echo backend_url('login/quit'); ?>">退出管理</a>
            <a href="<?php echo backend_url('system/home'); ?>">后台首页</a>
            <a href="<?php echo base_url(); ?>" target='_blank'>站点首页</a>
            <span>您好 <label class='bold'><?php echo $this->_admin->username; ?></label>，
            当前身份 <label class='bold'><?php echo $this->_admin->name; ?></label></span>
        </p>
	</div>
	<div id="info_bar">
    <label class="navindex" >
    	<a href="http://www.dilicms.com/user_guide" target="_blank">使用手册</a>
    </label>
    <span class="nav_sec">    	
        <?php $this->plugin_manager->trigger_navigation(); ?>
	</span></div>
	<div id="admin_left">
		<ul class="submenu">
            <?php $this->acl->show_left_menus(); ?>
        </ul>
		<div id="copyright" style="display:block">
			<p>版本：DiliCMS <?php echo DiliCMS_VERSION; ?></p>
			<p><a target="_blank" href="http://www.jooyea.net/" >感谢iWebShop开源提供的UI</a></p>
		</div>
	</div>
	<div id="admin_right">
    	<?php if($this->uri->rsegment(1) != 'module'): ?>
    	<?php $this->load->view(isset($tpl) && $tpl ? $tpl : 'sys_default'); ?>
        <?php else: ?>
        <?php if(!isset($msg)){echo $content;}else{$this->load->view($tpl);} ?>
        <?php endif; ?>
	</div>
	<div id="separator"></div>
</div>
<script type='text/javascript'>
	//隔行换色
	$(".list_table tr::nth-child(even)").addClass('even');
	$(".list_table tr").hover(
		function () {
			$(this).addClass("sel");
		},
		function () {
			$(this).removeClass("sel");
		}
	);
</script>
</body>
</html>
