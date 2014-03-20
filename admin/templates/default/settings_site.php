<?php if ( ! defined('IN_DILICMS')) exit('No direct script access allowed');?>
<link rel="stylesheet" href="js/kindeditor/themes/default/default.css" />
<script charset="utf-8" src="js/kindeditor/kindeditor-all-min.js"></script>
<script charset="utf-8" src="js/kindeditor/lang/zh_CN.js"></script>
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
        <?php echo form_open('setting/site?tab=site_basic'); ?>
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
					<td><textarea name='site_footer'  id="site_footer" style="height:300px;width:100%" data-editor="kindeditor" data-editor-mode="simple" data-upload="false"><?php echo $site->site_footer;?></textarea></td>
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
		<?php echo form_close(); ?>

        <?php echo form_open('setting/site?tab=site_status') ?>
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
					<td><textarea  name='site_close_reason' style="height:200px;width:100%" data-editor="kindeditor" data-editor-mode="simple" data-upload="false"><?php echo $site->site_close_reason; ?></textarea></td>
				</tr>
				<tr>
					<th></th>
					<td>
						<button class="submit" type='submit'><span>保存站点状态</span></button>
					</td>
				</tr>
			</table>
        <?php echo form_close(); ?>

        <?php echo form_open('setting/site?tab=site_attachment'); ?>
			<!--附件设置!-->
			<table class="form_table dili_tabs" id="site_attachment" style="<?php echo $current_tab == 'site_attachment' ? '' : 'display:none'; ?>">
				<col width="150px" />
				<col />
				<tr>
					<th>访问路径：</th>
					<td><input type='text' class='normal' name='attachment_url'  id="attachment_url" value="<?php echo $site->attachment_url; ?>" />附件访问前缀，末尾不包含/</td>
				</tr>
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
                    <th>缩略图尺寸预设：</th>
                    <td>
                        <ul id="thumbs-preferences" data-url="<?php echo site_url('setting/thumbs'); ?>"></ul>
                        <ul id="thumbs-preferences-form" style="display: none" data-enabled="<?php echo extension_loaded('imagick') ? 'true' : 'false' ?>">
                            <li style="padding:4px 0;">
                                <input type="text" class="small" value="" id="new-size">
                                <select id="new-rule">
                                    <option value="crop">Crop策略</option>
                                    <option value="fit">Fit策略</option>
                                    <option value="fill">Fill策略</option>
                                    <option value="fitWidth">FitWidth策略</option>
                                </select>
                                <button class="submit"  id="add-new-preference" type='button'><span>添加</span></button>
                            </li>
                        </ul>
                        <div class="red_box" style="display: none" id="thumb-warning"><img src="images/error.gif">对不起，必须启用<a href="http://www.php.net/manual/zh/book.imagick.php" target="_blank"><b>php-imagick</b></a>扩展方可使用本功能!</div>
                    </td>
                </tr>
				<tr>
					<th></th>
					<td>
						<button class="submit" type='submit'><span>保存附件设置</span></button>
					</td>
				</tr>
			</table>
        <?php echo form_close(); ?>

        <?php echo form_open('setting/site?tab=site_terms'); ?>
			<!--注册协议!-->
			<table class="form_table dili_tabs" id="site_terms" style="<?php echo $current_tab == 'site_terms' ? '' : 'display:none'; ?>">
				<col width="150px" />
				<col />
				<tr>
					<th>注册协议：</th>
					<td><textarea name='site_terms'  id="site_terms" style="height:300px;width:100%" data-editor="kindeditor" data-editor-mode="simple" data-upload="false"></textarea></td>
				</tr>
				<tr>
					<th></th>
					<td>
						<button class="submit" type='submit'><span>保存注册协议</span></button>
					</td>
				</tr>
			</table>
        <?php echo form_close(); ?>

        <?php echo form_open('setting/site?tab=site_theme'); ?>
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
        <?php echo form_close(); ?>
        
	</div>
</div>
<script type="text/template" id="thumb-template">
    <%= size%> - <%= window.thumbRules[rule] %>
    <a class="submit" style="padding:2px 4px" type='button'><span>x</span></a>
</script>
<script src="js/underscore-min.js"></script>
<script src="js/backbone-min.js"></script>
<script>
    (function() {

        "use strict"

        var thumbApp = {};

        window.thumbRules = thumbApp.rules = {
            fill: 'Fill策略',
            fit: 'Fit策略',
            fitWidth: 'FitWidth策略',
            crop: 'Crop策略'
        };

        thumbApp.url = $('#thumbs-preferences').data('url');

        thumbApp.Model = Backbone.Model.extend({
            defaults: {
                size: '',
                rule: ''
            },
            urlRoot: thumbApp.url+'/',
            validate: function(attrs) {
                if (! /^[1-9]\d*(x[1-9]\d*)?$/.test(attrs.size)) {
                    return '不合法的尺寸设置\n尺寸需要设置为形如100x100或者100的值.';
                }
                if (typeof thumbApp.rules[attrs.rule] == 'undefined') {
                    return '不合法的缩略策略参数';
                }
                if (/^\d+$/.test(attrs.size) && attrs.rule != 'fitWidth') {
                    return '该尺寸只能设置为FitWidth策略';
                }
                var isExisted = false;
                _.each(thumbApp.view.$el.children('li'), function(li) {
                    var $li = $(li);
                    if (attrs.size == $li.data('size')) {
                        isExisted = true;
                        return false;
                    }
                });
                if (isExisted) {
                    return '该尺寸的预设已经存在了';
                }
            }
        });

        thumbApp.Collection = Backbone.Collection.extend({
            model: thumbApp.Model,
            url : thumbApp.url,
            urlRoot : thumbApp.url
        });

        thumbApp.preferences = new thumbApp.Collection();

        thumbApp.thumbView = Backbone.View.extend({
            tagName: 'li',
            template: _.template($('#thumb-template').html()),
            events: {
                "click a.submit": "destroy"
            },
            initialize: function() {
                this.listenTo(this.model, 'destroy', this.remove);
            },
            render: function() {
                this.$el.css('padding', '4px 0')
                    .data('size', this.model.get('size'))
                    .data('rule', this.model.get('rule'));
                this.$el.html(this.template(this.model.toJSON()));
                return this;
            },
            destroy: function() {
                this.model.destroy();
            }
        });

        thumbApp.FormView = Backbone.View.extend({
            el: '#thumbs-preferences-form',
            events: {
                "click #add-new-preference": "addNew"
            },
            initialize: function() {
                this.$newSize = $('#new-size');
                if (this.$el.data('enabled') == true) {
                    this.$el.show();
                    $('#thumb-warning').hide();
                } else {
                    this.$el.hide();
                    $('#thumb-warning').show();
                }
            },
            addNew: function() {
                var newSize = this.$('#new-size').val();
                var newRule = this.$('#new-rule').val();

                var model = new thumbApp.Model({
                    size: newSize,
                    rule: newRule
                });
                if (! model.isValid()) {
                    alert(model.validationError);
                } else {
                    model.save({id: newSize});
                    thumbApp.view.addOne(model);
                }
            }
        });

        thumbApp.formView = new thumbApp.FormView();

        thumbApp.View = Backbone.View.extend({
            el: '#thumbs-preferences',
            initialize: function() {
                this.listenTo(thumbApp.preferences, 'sync', this.render);
                thumbApp.preferences.fetch({reset: true});
            },
            render: function() {
                this.$el.html('');
                if (thumbApp.preferences.length > 0) {
                    thumbApp.preferences.each(this.addOne, this);
                }
            },
            addOne: function(thumb) {
                var view = new thumbApp.thumbView({model: thumb});
                this.$el.append(view.render().el);
            }
        });

        thumbApp.view = new thumbApp.View();
    })();
</script>