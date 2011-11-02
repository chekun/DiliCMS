<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>DiliCMS安装向导(三)</title>
<link rel="stylesheet" href="css/install.css" />
<script type='text/javascript' src='javascript/jquery-1.4.4.min.js'></script>
</head>
<body>
<div class="container">
	<div class="head"><img src="images/logo.gif" width="354" height="53" alt="DiliCMS安装向导" /></div>
	<div class="ins_box clearfix">
		<div class="cont clearfix">
			<ul class="step">
				<li id="step_1"></li>
				<li id="step_2"></li>
				<li id="step_3" class="current"></li>
				<li id="step_4"></li>
			</ul>

			<form action='index.php?act=install_sql' method='post' target="install_iframe" onsubmit="return check_form();">
				<div class="log_box">
					<h2><img src="images/guide_3.gif" width="82" height="15" /></h2>

					<div class="red_box" style='display:none' id='error_div'>
						<img src="images/error.gif" width="16" height="15" />
						安装发生错误：<label></label>
					</div>

					<div class="gray_box">
						<div class="box">
							<table class="form_table">
								<col width="100px" />
								<col />
								<tr>
									<th>数据库地址</th><td><input class="gray" type="text" name='db_address' value='localhost:3306' /><br /><label>MYSQL数据库的地址，本地默认：localhost:3306</label></td>
								</tr>
								<tr>
									<th>数据库名称</th><td><input class="gray" type="text" name='db_name' /><br /><label class="error" id='db_name_label' style='display:none'><img src="images/failed.gif" width="16" height="15" />数据库不能为空</label></td>
								</tr>
								<tr>
									<th>账户</th><td><input class="gray" type="text" name='db_user' /></td>
								</tr>
								<tr>
									<th>密码</th><td><input class="gray" type="password" name='db_pwd' /></td>
								</tr>
								<tr style="display:none">
									<th>数据库表前缀</th>
									<td><input class="gray" type="text" value='iweb_' name='db_pre' /><br /><label class="error" id='db_pre_label' style='display:none'><img src="images/failed.gif" width="16" height="15" />表前缀不能为空</label></td>
								</tr>
								<tr>
									<th></th><td><input class="check" type="button" onclick="check_mysql();" /></td>
								</tr>
							</table>

							<p id='right_p' style='display:none'><img src="images/right.gif" width="19" height="18" />数据库连接正确</p>
							<p id='error_p' style='display:none'><img src="images/failed.gif" width="16" height="16" />数据库连接不正确</p>
							<hr />

							<table class="form_table">
								<col width="100px" />
								<col />
								<tr>
									<th>管理员账户</th>
									<td>
										<input class="gray" type="text" name='admin_user' value='admin' /><br />
										<label class="error" id='admin_user_label' style='display:none'><img src="images/failed.gif" width="16" height="15" />密码格式不正确，字符在4-12个之间</label>
									</td>
								</tr>
								<tr>
									<th>密码</th>
									<td>
										<input class="gray" type="password" name='admin_pwd' /><br />
										<label class="error" id='admin_pwd_label' style='display:none'><img src="images/failed.gif" width="16" height="15" />密码格式不正确，字符在6-16个之间</label>
									</td>
								</tr>
								<tr>
									<th>再次确认</th>
									<td>
										<input class="gray" type="password" name='admin_repwd' /><br />
										<label class="error" id='admin_repwd_label' style='display:none'><img src="images/failed.gif" width="16" height="15" />二次密码输入的不一致</label>
									</td>
								</tr>
							</table>

							<hr />

							<strong style="display:none">安装选择</strong>
							<label for="" style="display:none"><input class="radio" type="radio" name='install_type' checked=checked value='simple' />简单安装：只安装系统程序</label>

							<hr />

							<div id='install_state' style='display:none'>
								<strong>安装进度</strong>
								<label>正在安装,请稍后...</label>
								<div class="loading"><span style="width:0px;"></span><img src="images/loading.gif" style='width:500px;height:20px' /></div>
							</div>

						</div>
					</div>
				</div>
				<p class="operate"><input class="return" type="button" onclick="window.location = 'index.php?act=install_2';" /><input class="next" type="submit" value='' /></p>
			</form>
		</div>
		<span class="l"></span><span class="r"></span><span class="b_l"></span><span class="b_r"></span>
	</div>
	<div class="foot"><a href="http://www.jooyea.net">安装模版版权归IWebShop.</a>|<a href="http://www.jooyea.net">DiliCMS官方网站</a></div>
</div>

<iframe name='install_iframe' style='width:0px;height:0px;display:none' src='#'></iframe>

</body>

<script type='text/javascript'>
	//更新进度条
	function update_progress(obj)
	{
		var whole       = $('#install_state img').css('width');
		var nowProgress = obj.percent ? parseInt(whole) * parseFloat(obj.percent) : 0;

		if(obj.isError == true)
		{
			$('#error_div').show();
			$('#error_div label').html(obj.message);
			$('#install_state label').addClass('red_box');
			$('.next').attr('disabled','');
		}
		else
		{
			$('#install_state label').removeClass('red_box');
		}

		$('#install_state label').html(obj.message);
		$('#install_state .loading span').css('width',nowProgress);

		if(obj.percent == 1)
		{
			window.location.href = 'index.php?act=install_4';
		}
	}

	//检查表单信息
	function check_form()
	{
		$('label.error').hide();
		var checkObj   =
		{
			db_pre    :/\w+/i,
			db_name   :/\w+/i,
			admin_user:/.{4,12}/i,
			admin_pwd :/.{6,16}/i
		};

		for(val in checkObj)
		{
			var matchResult = $.trim($('[name="'+val+'"]').val()).match(checkObj[val]);
			if(matchResult == null)
			{
				$('[name="'+val+'"]').focus();
				$('#'+val+'_label').show();
				return false;
			}
		}

		if($('[name="admin_repwd"]').val() != $('[name="admin_pwd"]').val())
		{
			$('#admin_repwd_label').show();
			return false;
		}

		$('#install_state').show();
		$('.next').attr('disabled','disabled');
		return true;
	}

	//检查mysql链接
	function check_mysql()
	{
		//获取ajax检查mysql链接的所需数据
		var sendData = {'db_address':'','db_user':'','db_pwd':''};
		for(val in sendData)
		{
			sendData[val] = $('[name="'+val+'"]').val();
		}

		$.get('index.php?act=check_mysql&'+Math.random(),sendData,function(content)
		{
			if(content == 1)
			{
				$('#right_p').show();
				$('#error_p').hide();
			}
			else
			{
				$('#right_p').hide();
				$('#error_p').show();
			}
		});
	}
</script>
</html>
