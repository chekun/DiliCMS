<?php if ( ! defined('IN_DILICMS')) exit('No direct script access allowed');?>
<div class="headbar">
	<div class="position"><span>数据库管理</span><span>></span><span>数据库备份</span></div>
</div>
<div class="content_box">
    <div class="red_box"><b>说明：</b>数据备份功能根据您的选择备份数据表，导出的数据文件可用“数据恢复”功能或phpMyAdmin 导入。<br/><b>提示：</b><span style="color:red">如果您的数据库过大请使用其他专业数据库备份软件或者在服务器通过脚本备份</span>。</div>
	<div class="content form_content">
        <?php echo form_open('database/export', array('method' => 'get')); ?>
			<table class="form_table dili_tabs" id="site_basic" >
				<col width="150px" />
				<col />
				<tr>
					<th> 数据库备份类型：</th>
					<td>
                        <label><input type="radio" name="export_type" value="all" onclick="$('#customtable').hide();" checked> 备份全部表</label>
                        <label><input type="radio" name="export_type" value="custom" onclick="$('#customtable').show();"> 自定义备份</label>
					</td>
				</tr>
                <tr id="customtable" style="display: none">
                    <th> 数据库表：</th>
                    <td>
                        <?php if($tables):?>
                            <ul class="attr_list">
                            <?php foreach($tables as $table):?>
                                <li>
                                    <label class="attr">
                                        <input class="checkbox" type="checkbox" name="tables[]" value="<?php echo $table; ?>" >&nbsp;&nbsp;<?php echo $table; ?>
                                    </label>
                                </li>
                            <?php endforeach;?>
                            </ul>
                        <?php endif; ?>
                        <ul class="attr_list" style="clear:both">
                            <li>
                                <label>
                                    <input onclick="selectAll('tables[]');" type='checkbox' id='chkall' value='check' >&nbsp;&nbsp;全选/反选
                                </label>
                            </li>
                        </ul>
                    </td>
                </tr>
                <tr>
                    <th> 备份文件名：</th>
                    <td>
                        <input type="text" class="normal" name="filename" value="<?php echo date('YmdHis');?>">.sql
                    </td>
                </tr>
                <tr>
                    <th> 压缩备份文件：</th>
                    <td>
                        <label><input type="radio" name="is_compress" value="1"> zip压缩</label>
                        <label><input type="radio" name="is_compress" value="0" checked> 不压缩</label>
                    </td>
                </tr>
                <tr>
                    <th> 扩展方式插入：<br />(Extended Insert)</th>
                    <td>
                        <label><input type="radio" name="is_extend_insert" value="1"> 是</label>
                        <label><input type="radio" name="is_extend_insert" value="0" checked> 否</label>
                    </td>
                </tr>
                <tr>
                    <th> 分卷大小：</th>
                    <td>
                        <input type="text"class="small" name="volume_size" value="2048"> KB
                    </td>
                </tr>
				<tr>
					<th></th>
					<td>
						<button class="submit" type='submit'><span>确定备份</span></button>
					</td>
				</tr>
			</table>
		<?php echo form_close(); ?>
	</div>
</div>