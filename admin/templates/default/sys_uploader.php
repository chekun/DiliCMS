<?php if ( ! defined('IN_DILICMS')) exit('No direct script access allowed');?>
<div id="dili_uploader">
    <div class="red_box" style="margin-bottom:2px;"><img src="images/error.gif" />上传完附件请注意一定要提交表单，以免附件丢失。</div>
    <div style="position:relative;text-align:left;">
        <p style="line-height:30px;"><a style="margin-left:5px;" id="uploaderSwitcher" onclick="toggleUploader();"  href="javascript:void(0)">打开上传控件</a>允许上传的格式:<b><?php echo setting('attachment_type'); ?></b>,大小限制:<b><?php echo number_format($maxsize = setting('attachment_maxupload')/1024/1024,2); ?>MB</b> </p>
        <div id="uploaderContainer" style="position:absolute;z-index:3000000;display:none;background:#ccc;">
            <object id="swfuploader" type="application/x-shockwave-flash" data="js/dili_utility//upload.swf?site=<?php echo backend_url('attachment'); ?>" width="470" height="268">
                <param name="movie" value="js/dili_utility/upload.swf?site=<?php echo backend_url('attachment'); ?>"/>
                <param name="allowFullScreen" value="false" />
                <?php if(!strpos($_SERVER['HTTP_USER_AGENT'],'MSIE')): ?>
                    <param name="wmode" value="transparent" />
                <?php endif; ?>
            </object>
        </div>
    </div>

    <ul id="attachList" >
        <li id="loading"></li>
        <?php if(isset($attachment)): ?>
            <?php foreach($attachment as $v): ?>
                <li id="attachment_<?php echo $v['aid']; ?>">
                    <span class="title"><input type="text" class="normal" value="<?php echo $v_url = $this->platform->file_url($v['folder'].'/'.$v['name'].'.'.$v['type']); ?>" /></span>
                    <?php if($v['image'] == 1): ?>
                        <a href="<?php echo $v_url; ?>" target="_blank">预览</a>
                    <?php endif;?>
                    <a href="javascript:void(0);" data-url="<?php echo $v_url; ?>" data-image="<?php echo $v['image']; ?>" data-type="<?php echo $v['type']; ?>" data-name="<?php echo $v['realname']; ?>" class="contextMenu" target="_blank">插入</a>
                    <a href="javascript:void(0);" onclick="if(confirm('是否要删除该附件?')){delete_attachment('<?php echo $v['aid']; ?>');}">删除</a>
                </li>
            <?php endforeach; ?>
        <?php endif; ?>
    </ul>

    <script>
        var base_url = '<?php echo base_url(); ?>' ,backend_url = '<?php echo backend_url().$this->uri->segment(1); ?>/',attachment_url = '<?php echo $this->platform->file_url(); ?>';
        var thumbDefaultSize = '<?php echo $thumb_default_size; ?>';

    </script>
    <link rel="stylesheet" href="js/contextMenu/jquery.contextMenu.css" />
    <script src="js/contextMenu/jquery.contextMenu.js"></script>
    <script src="js/contextMenu/jquery.ui.position.js"></script>
    <script src="js/dili_utility/upload.js"></script>
</div>
