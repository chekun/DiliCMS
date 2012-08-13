<?php if ( ! defined('IN_DILICMS')) exit('No direct script access allowed');?>
<div class="headbar">
	<div class="position"><span>系统</span><span>></span><span>权限管理</span><span>></span><span>用户管理</span></div>
	<div class="operating">
		<a class="hack_ie" href="<?php echo backend_url('user/add'); ?>"><button class="operating_btn" type="button"><span class="addition">添加新用户</span></button></a>
        <div class="search f_r">
		<form name="serachuser" action="<?php echo backend_url('user/view'); ?>" method="get">
			<select class="normal" style="width:auto" name="role" onchange="location='<?php echo backend_url('user/view'); ?>/'+this.value;">
				<option value="">选择用户组</option>
				<?php foreach($roles as $k=>$r): ?>
                <option <?php echo $role == $k ? 'selected="selected"' : '' ?> value="<?php echo $k; ?>"><?php echo $r; ?></option>
                <?php endforeach; ?>
			</select>
		</form>
		</div>
	</div>
	<div class="field">
		<table class="list_table">
			<col width="40px" />
			<col />
			<thead>
				<tr>
                	<th></th>
					<th>用户名称</th>
                    <th>用户EMAIL</th>
                    <th>用户组</th>
                    <th>帐号状态</th>
                    <th>操作选项</th>
				</tr>
			</thead>
		</table>
	</div>
</div>

<div class="content">
		<table id="list_table" class="list_table">
			<col width="40px" />
			<col />
			<tbody>
            <?php foreach($list as $v) : ?>
            	<tr>
                	<td></td>
                	<td><?php echo $v->username; ?></td>
                    <td><?php echo $v->email; ?></td>
                    <td><?php echo $v->name; ?></td>
                    <td><?php echo $v->status == 1 ? '正常' : '冻结'; ?></td>
                    <td>
                    	<a href="<?php echo backend_url('user/edit/'.$v->uid); ?>"><img class="operator" src="images/icon_edit.gif" alt="修改" title="修改"></a>
                        <a class="confirm_delete" href="<?php echo backend_url('user/del/'.$v->uid); ?>"><img class="operator" src="images/icon_del.gif" alt="删除" title="删除"></a>
                    </td>
                </tr>
            <?php endforeach; ?>
			</tbody>
		</table>
</div>
<div class="pages_bar pagination"><?php echo $pagination; ?></div>
<script language="javascript">
	$('a.confirm_delete').click(function(){
		return confirm('是否要删除所选用户？');	
	});
</script>