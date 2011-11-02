<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>DiliCMS安装向导(一)</title>
<link rel="stylesheet" href="css/install.css" />
</head>
<body>
<div class="container">
	<div class="head"><img src="images/logo.gif" width="354" height="53" alt="DiliCMS安装向导" /></div>
	<div class="ins_box clearfix">
		<div class="cont clearfix">
			<ul class="step">
				<li id="step_1" class="current"></li>
				<li id="step_2"></li>
				<li id="step_3"></li>
				<li id="step_4"></li>
			</ul>
			<div class="log_box">
				<h2><img src="images/guide_1.gif" width="203" height="15" /></h2>

				<div class="red_box" style='display:none' id='error_div'>
					<img src="images/error.gif" width="16" height="15" />
					请认真阅读并同意以下条款
			  </div>

				<div class="gray_box">
					<div class="box" style="height:314px; overflow-y:auto">
						<?php
						//动态获取license协议
						$licenseFile = dirname(__FILE__).'/../license.txt';
						if(file_exists($licenseFile))
						{
							foreach(file($licenseFile) as $key => $val)
							{
								echo $val.'<br />';
							}
						}
						else
						{
							echo "本地协议不存在，请访问： <a href='http://www.dilicms.com/license.html' target='_blank'>http://www.dilicms.com/license.html</a> 阅读相关协议!";
						}
						?>
					</div>
				</div>
				<p class="agree"><label><input type="checkbox" id='agree' /> 我同意上述条款和条件</label></p>
			</div>
			<p class="operate"><input class="next" type="button" onclick="check_license();" /></p>
		</div>
		<span class="l"></span><span class="r"></span><span class="b_l"></span><span class="b_r"></span>
	</div>
	<div class="foot"><a target="_blank" href="http://www.jooyea.net">安装模版版权归IWebShop所有.</a>|<a href="http://www.jooyea.net">DiliCMS官方网站</a></div>
</div>

<script type='text/javascript'>
	//检查协议阅读状态
	function check_license()
	{
		var is_agree = document.getElementById('agree').checked;
		if(is_agree == true)
		{
			window.location.href='index.php?act=install_2';
		}
		else
		{
			document.getElementById('error_div').style.display = '';
		}
	}
</script>
</body>
</html>
