<script src="js/xheditor/xheditor-zh-cn.min.js" type="text/javascript"></script>
<?php $current_tab =  $this->input->get('tab') ? $this->input->get('tab') : 'site_basic' ; ?>
<div class="headbar">
	<div class="position"><span>系统</span><span>></span><span>系统设置</span><span>></span><span>站点设置</span></div>
    <ul class='tab' name='conf_menu'>
		<li <?php echo $current_tab == 'site_basic' ? 'class="selected"' : ''; ?>><a href="javascript:void(0);" onclick="select_tab('site_basic',this);">基本设置</a></li>
		<li <?php echo $current_tab == 'site_status' ? 'class="selected"' : ''; ?>><a href="javascript:void(0);" onclick="select_tab('site_status',this);">站点状态</a></li>
        <li <?php echo $current_tab == 'site_attachment' ? 'class="selected"' : ''; ?>><a href="javascript:void(0);" onclick="select_tab('site_attachment',this);">附件设置</a></li>
        <li <?php echo $current_tab == 'site_terms' ? 'class="selected"' : ''; ?>><a href="javascript:void(0);" onclick="select_tab('site_terms',this);">注册协议</a></li>
        <li <?php echo $current_tab == 'site_theme' ? 'class="selected"' : ''; ?>><a href="javascript:void(0);" onclick="select_tab('site_theme',this);">主题设置</a></li>
	</ul>
</div>
<div class="content_box">
	<div class="content form_content">
		<form action="<?php echo backend_url('setting/site').'?tab=site_basic'; ?>"  method="post">
			<!--基本设置!-->
			<table class="form_table dili_tabs" id="site_basic" style="<?php echo $current_tab == 'site_basic' ? '' : 'display:none'; ?>">
				<col width="150px" />
				<col />
				<tr>
					<th> 站点名称：</th>
					<td><input type='text' class='normal' name='site_name'  id="site_name" value="<?php echo $site->site_name; ?>" /></td>
				</tr>
				<tr>
					<th>站点网址：</th>
					<td>
						<input type='text' class='normal' name='site_domain'  id="site_domain" value="<?php echo $site->site_domain; ?>" /></td>
				</tr>
				<tr>
					<th> 站点logo：</th>
					<td><input type='text' class='normal' name='site_logo' id="site_logo" value="<?php echo $site->site_logo; ?>" /></td>
				</tr>
				<tr>
					<th>备案号：</th>
					<td><input type='text' class='normal' name='site_icp' id="site_icp" value="<?php echo $site->site_icp; ?>" /></td>
				</tr>
				<tr>
					<th>统计代码：</th>
					<td><textarea name='site_stats'  id="site_stats" class="noeditor"><?php echo $site->site_stats; ?></textarea></td>
				</tr>
				<tr>
					<th>站点底部：</th>
					<td><textarea name='site_footer'  id="site_footer" style="height:100px;width:100%" class="xheditor {skin:'nostyle'}"><?php echo $site->site_footer;?></textarea></td>
				</tr>
				<tr>
					<th>站点关键字：</th>
					<td><input type='text' class='normal'  name='site_keyword' id="site_keyword" value="<?php echo $site->site_keyword; ?>"  /></td>
				</tr>
				<tr>
					<th>站点描述：</th>
					<td><input type='text' class='normal'  name='site_description' id="site_description" value="<?php echo $site->site_description; ?>"  /></td>
				</tr>
				<tr>
					<th></th>
					<td>
						<button class="submit" type='submit'><span>保存基本设置</span></button>
					</td>
				</tr>
			</table>
		</form>
        
        <form action="<?php echo backend_url('setting/site').'?tab=site_status'; ?>"  method="post">
			<!--站点状态!-->
			<table class="form_table dili_tabs" id="site_status" style="<?php echo $current_tab == 'site_status' ? '' : 'display:none'; ?>">
				<col width="150px" />
				<col />
				<tr>
					<th>站点状态：</th>
					<td>
                    	<input type="radio" name="site_status" value="1" <?php echo $site->site_status == 1 ? 'checked="checked"' : ''; ?>>开启
                        <input type="radio" name="site_status" value="0" <?php echo $site->site_status == 0 ? 'checked="checked"' : ''; ?>>关闭
                    </td>
				</tr>
				<tr>
					<th>站点关闭原因：</th>
					<td><textarea  name='site_close_reason' style="height:200px;width:100%" class="xheditor {skin:'nostyle'}" ><?php echo $site->site_close_reason; ?></textarea></td>
				</tr>
				<tr>
					<th></th>
					<td>
						<button class="submit" type='submit'><span>保存站点状态</span></button>
					</td>
				</tr>
			</table>
		</form>
        
        <form action="<?php echo backend_url('setting/site').'?tab=site_attachment'; ?>"  method="post">
			<!--附件设置!-->
			<table class="form_table dili_tabs" id="site_attachment" style="<?php echo $current_tab == 'site_attachment' ? '' : 'display:none'; ?>">
				<col width="150px" />
				<col />
				<tr>
					<th>上传路径：</th>
					<td><input type='text' class='normal' name='attachment_dir'  id="attachment_dir" value="<?php echo $site->attachment_dir; ?>" /></td>
				</tr>
                <tr>
					<th>上传类型：</th>
					<td><input type='text' class='normal' name='attachment_type'  id="attachment_type" value="<?php echo $site->attachment_type; ?>" /></td>
				</tr>
                <tr>
					<th>上传大小限制：</th>
					<td><input type='text' class='small' name='attachment_maxupload'  id="attachment_maxupload" value="<?php echo $site->attachment_maxupload; ?>" />单位：字节</td>
				</tr>
				<tr>
					<th></th>
					<td>
						<button class="submit" type='submit'><span>保存附件设置</span></button>
					</td>
				</tr>
			</table>
		</form>
        
        <form action="<?php echo backend_url('setting/site').'?tab=site_terms'; ?>"  method="post">
			<!--注册协议!-->
			<table class="form_table dili_tabs" id="site_terms" style="<?php echo $current_tab == 'site_terms' ? '' : 'display:none'; ?>">
				<col width="150px" />
				<col />
				<tr>
					<th>注册协议：</th>
					<td><textarea name='site_terms'  id="site_terms" style="height:300px;width:100%" class="xheditor {skin:'nostyle'}"></textarea></td>
				</tr>
				<tr>
					<th></th>
					<td>
						<button class="submit" type='submit'><span>保存注册协议</span></button>
					</td>
				</tr>
			</table>
		</form>
        
        <form action="<?php echo backend_url('setting/site').'?tab=site_theme'; ?>"  method="post">
			<!--主题设置!-->
			<table class="form_table dili_tabs" id="site_theme" style="<?php echo $current_tab == 'site_theme' ? '' : 'display:none'; ?>">
				<col width="150px" />
				<col />
				<tr>
					<th> 主题名称：</th>
					<td><input type='text' class='normal' name='site_theme'  id="site_theme" value="default" /></td>
				</tr>
				<tr>
					<th></th>
					<td>
						<button class="submit" type='submit' ><span>保存主题设置</span></button>
					</td>
				</tr>
			</table>
		</form>
        
	</div>
</div>