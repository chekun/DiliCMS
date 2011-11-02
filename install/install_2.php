<?php
$checkObj = new checkConfig;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>DiliCMS安装向导(二)</title>
<link rel="stylesheet" href="css/install.css" />
</head>
<body>
<div class="container">
	<div class="head"><img src="images/logo.gif" width="354" height="53" alt="DiliCMS安装向导" /></div>
	<div class="ins_box clearfix">
		<div class="cont clearfix">
			<ul class="step">
				<li id="step_1"></li>
				<li id="step_2" class="current"></li>
				<li id="step_3"></li>
				<li id="step_4"></li>
			</ul>
			<div class="log_box">
				<h2><img src="images/guide_2.gif" width="112" height="15" /></h2>

				<div class="green_box" style='display:none' id='right_div'>
					<img src="images/right.gif" width="19" height="18" />
					您的系统配置是有效的，单击下一步继续！
				</div>

				<div class="red_box" style='display:none' id='error_div'>
					<img src="images/error.gif" width="16" height="15" />
					您的系统配置不具备安装DiliCMS软件，有疑问可以访问：<a href='http://www.dilicms.com/' target='_blank'>http://tech.jooyea.net/bbs/</a>
				</div>

				<div class="gray_box">
					<div class="box">
						<strong>PHP版本及环境设置</strong>
						<?php //phpversion检查
						$phpVersion_pass = $checkObj->c_phpVersion();?>
						<p><img src="images/<?php echo $phpVersion_pass ? 'success' : 'failed';?>.gif" width="16" height="16" />PHP <?php echo $checkObj->getPHPVersion();?></p>

						<?php //phpini检查
						$phpiniArray = $checkObj->c_phpIni();
						foreach($phpiniArray as $key => $val)
						{
						?>
						<p><img src="images/<?php echo $val ? 'success' : 'failed';?>.gif" width="16" height="16" /><?php echo $key;?></p>
						<?php
						}
						?>

						<strong>必须扩展配置</strong>
						<?php //must_extension检查
						$mustExtensionArray = $checkObj->c_must_extension();
						foreach($mustExtensionArray as $key => $val)
						{
						?>
						<p><img src="images/<?php echo $val ? 'success' : 'failed';?>.gif" width="16" height="16" /><?php echo $key;?></p>
						<?php
						}
						?>

						<strong>建议扩展配置</strong>
						<?php //recom_extension检查
						$recomExtensionArray = $checkObj->c_recom_extension();
						foreach($recomExtensionArray as $key => $val)
						{
						?>
						<p><img src="images/<?php echo $val ? 'success' : 'failed';?>.gif" width="16" height="16" /><?php echo $key;?></p>
						<?php
						}
						?>

						<strong>文件可写权限</strong>
						<?php //writeable
						$writeableArray = $checkObj->c_writeableDir();
						foreach($writeableArray as $key => $val)
						{
						?>
						<p><img src="images/<?php echo $val ? 'success' : 'failed';?>.gif" width="16" height="16" /><?php echo $key;?></p>
						<?php
						}
						?>

						<strong>文件可读权限</strong>
						<?php //writeable
						$readableArray = $checkObj->c_readableDir();
						foreach($readableArray as $key => $val)
						{
						?>
						<p><img src="images/<?php echo $val ? 'success' : 'failed';?>.gif" width="16" height="16" /><?php echo $key;?></p>
						<?php
						}
						?>

					</div>
				</div>

			</div>
			<p class="operate">
				<input class="return" type="button" onclick="window.location.href = 'index.php?act=install';" />
				<input class="next" type="button" onclick="check_config();" />
			</p>
		</div>
		<span class="l"></span><span class="r"></span><span class="b_l"></span><span class="b_r"></span>
	</div>
	<div class="foot"><a href="http://www.jooyea.net">安装模版版权归IWebShop.</a>|<a href="http://www.jooyea.net">DiliCMS官方网站</a></div>
</div>

<script type='text/javascript'>
	ErrorNum = <?php echo $checkObj->getNpassMustNum();?>

	//检查配置信息
	function check_config()
	{
		var error_num = ErrorNum;
		if(error_num > 0)
		{
			alert('您的系统环境配置没有通过检查');
		}
		else
		{
			window.location.href = 'index.php?act=install_3';
		}
	}

	if(ErrorNum > 0)
	{
		document.getElementById('error_div').style.display = '';
	}
	else
	{
		document.getElementById('right_div').style.display = '';
	}
</script>
</body>
</html>
