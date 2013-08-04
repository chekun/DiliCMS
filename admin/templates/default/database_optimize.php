<?php if ( ! defined('IN_DILICMS')) exit('No direct script access allowed');?>
<div class="headbar">
	<div class="position"><span>数据库管理</span><span>></span><span>数据库优化</span></div>
    <div class="operating">
        <a href="javascript:$('form').submit();"><button class="operating_btn" type="button"><span class="sel_all">立即优化</span></button></a>
    </div>
    <div class="red_box" style="margin-bottom: 10px"><b>说明：</b>数据表(MyISAM存储引擎)优化可以去除数据文件中的碎片，使记录排列紧密，提高读写速度。</div>
    <div class="field">
        <table class="list_table">
            <thead>
                <tr>
                    <th>数据表</th>
                    <th>类型</th>
                    <th>记录数</th>
                    <th>数据</th>
                    <th>索引</th>
                    <th>碎片</th>
                </tr>
            </thead>
        </table>
    </div>
</div>
<div class="content">
    <?php echo form_open('database/optimize'); ?>
    <table id="list_table" class="list_table">
        <?php foreach($tables as $table):?>
            <tr>
                <?php foreach($table as $v):?>
                    <td><?php echo $v;?></td>
                <?php endforeach;?>
            </tr>
        <?php endforeach;?>
        <tr>
            <td colspan="6">碎片数据： <?php echo $total_size;?> KB（~<?php echo round($total_size / 1024, 2);?> MB）</td>
        </tr>
    </table>
    <?php echo form_close(); ?>
</div>