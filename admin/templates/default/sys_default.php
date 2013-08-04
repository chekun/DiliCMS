<?php if ( ! defined('IN_DILICMS')) exit('No direct script access allowed');?>
<div class="content_box" style="border:none">
	<div class="content">
		<?php if(file_exists(BASEPATH.'../install')): ?>
        <div class="red_box"><img src="images/error.gif" />您的安装目录没有删除，为了系统安全，请尽快删除！</div>
		<?php endif; ?>
        <div class="red_box" id="lower_ie" style="display:none"><img src="images/error.gif" />系统检测到你使用的浏览器为IE8以下的版本(含IE核心的浏览器)，为了更好的体验，请使用IE8以上的或者其他主流的浏览器进行浏览！</div>
        <table width="48%" cellspacing="0" cellpadding="5" class="border_table_org" style="float:left">
			<thead>
				<tr>
			  <th>平台信息(<?php echo $this->platform->get_name(); ?>)</th></tr>
			</thead>
			<tbody>
				<tr>
					<td>
						<table class="list_table2" width="100%">
							<colgroup>
								<col width="100px">
								<col />
								<col width="" />
							</colgroup>
							<tr>
							  <th>网站名称</th>
							  <td><b class="f14 red3"><?php echo setting('site_name'); ?></b></td></tr>
							<tr>
							  <th>平台版本</th>
							  <td><b><?php echo DILICMS_VERSION; ?>(CI:<?php echo CI_VERSION; ?>)</b></td></tr>
							<tr>
							  <th>脚本语言</th>
							  <td><?php echo 'PHP'.PHP_VERSION; ?></td></tr>
							<tr>
							  <th>数据库</th>
							  <td><?php echo 'MySQL'.$this->db->version();  ?></td></tr>
						</table>
					</td>
				</tr>
			</tbody>
		</table>
	  <table width="48%" cellspacing="0" cellpadding="5" class="border_table_org" style="float:left">
		  <thead>
		    <tr>
		      <th>管理员</th>
	        </tr>
	      </thead>
		  <tbody>
		    <tr>
		      <td><table class="list_table2" width="100%">
		        <colgroup>
		          <col width="100px" />
		          <col />
	            </colgroup>
		        <tr>
		          <th>当前帐号</th>
		          <td colspan="2"><?php echo $this->_admin->username; ?></td>
	            </tr>
		        <tr>
		          <th>所属用户组</th>
		          <td><b class="f14 red3"><?php echo $this->_admin->name; ?></b></td>
	            </tr>
		        <tr>
		          <th>登录IP</th>
		          <td><?php echo $this->input->ip_address(); ?></td>
	            </tr>
		        <tr>
		          <th>&nbsp;</th>
		          <td><a href="<?php echo backend_url('system/password'); ?>">修改密码</a></td>
	            </tr>
		        </table></td>
	        </tr>
	      </tbody>
	  </table>
	  <table width="48%" cellspacing="0" cellpadding="5" class="border_table_org" style="float:left">
		  <thead>
		    <tr>
		      <th>网站</th>
	        </tr>
	      </thead>
		  <tbody>
		    <tr>
		      <td><table class="list_table2" width="100%">
		        <colgroup>
		          <col width="100px" />
		          <col />
	            </colgroup>
		        <tr>
		          <th>网站域名</th>
		          <td colspan="2"><?php echo $_SERVER['SERVER_NAME']; ?></td>
	            </tr>
		        <tr>
		          <th>网站IP</th>
		          <td><b class="f14 red3"><?php echo getHostByName(php_uname('n')).':'.$_SERVER['SERVER_PORT']; ?></b></td>
	            </tr>
		        <tr>
		          <th>当前编码</th>
		          <td>UTF-8</td>
	            </tr>
		        </table></td>
	        </tr>
	      </tbody>
	  </table>
		<table width="48%" cellspacing="0" cellpadding="5" class="border_table_org" style="float:left">
		  <thead>
		    <tr>
		      <th>服务器</th>
	        </tr>
	      </thead>
		  <tbody>
		    <tr>
		      <td><table class="list_table2" width="100%">
		        <colgroup>
		          <col width="100px" />
		          <col />
	            </colgroup>
		        <tr>
		          <th>当前时区</th>
		          <td colspan="2"><?php echo date_default_timezone_get(); ?></td>
	            </tr>
		        <tr>
		          <th>上传上限</th>
		          <td><b class="f14 red3"><?php echo @ini_get('upload_max_filesize'); ?></b></td>
	            </tr>
		        <tr>
		          <th>SimpleXMLElement支持</th>
		          <td><?php echo (class_exists('SimpleXMLElement')) ? '支持' : '不支持'; ?></td>
	            </tr>
		        </table></td>
	        </tr>
	      </tbody>
	  </table>
    </div>
</div>
<script language="javascript">
if ( $.browser.msie && parseInt($.browser.version) < 8 ) {
		$('#lower_ie').show();
	}
</script>
