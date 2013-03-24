define(["jquery", 'jquery-ui', "text!templates/license.html"], function($, ui, tpl) {
        var License = function() {
            this.isShow = false;
            this.$container = $('#step-license');
            this.init();
            this.show();
        }
        //init html
        License.prototype.init = function() {
            var $this = this;
            this.$container.html(tpl);
            this.$checkBox = this.$container.find('.checkbox-custom');
            this.$nextBtn = this.$container.find('.modal-footer > button');
            this.$checkBox.checkbox();
            this.$checkBox.on('changed', function (e, data) {
                if (data.isChecked)
                {
                    $this.$nextBtn.removeClass('disabled');
                }
                else
                {
                    $this.$nextBtn.addClass('disabled');
                }
            });
            this.$nextBtn.click(function(){
                window.wizard.wizard('next');
            });
        }
        //when show
        License.prototype.show = function() {
            if ( ! this.isShow)
            {
                this.$container.find('.modal-body').html(' \
                    <p><a target="_blank" href="http://dilicms.com">DiliCMS</a>是开源的，面向<a target="_blank" href="http://codeigniter.org.cn">CodeIgniter</a>开发者的，自由灵活的后台系统，并致力于为开发者提供最简单，易扩展，实用的后台系统。</p> \
                    <p><a target="_blank" href="http://dilicms.com">DiliCMS</a>基于<a target="_blank" href="https://github.com/DiliCMS/DiliCMS#license">MIT协议</a>开源, 开发者可以自由修改使用, 除以下条款: </p> \
                    <p> \
                        <ol> \
                            <li>登陆页面希望不强制保留对DiliCMS官方网站的链接.</li> \
                            <li>FLASH上传控件版权归Discuz!所有.</li> \
                            <li>后台UI版权归iwebshop所有.</li> \
                        </ol> \
                        <span class="label label-warning">注: DiliCMS V3.0以后将采用开源上传组件和UI框架.</span> \
                    </p> \
                    <p> \
                        本安装程序使用以下开源框架实现: \
                        <ul> \
                            <li><a target="_blank" href="http://jquery.com">jQuery</a>, <a target="_blank" href="http://jqueryui.com">jQueryUI</a></li> \
                            <li><a target="_blank" href="http://requirejs.org">RequireJS</a></li> \
                            <li><a target="_blank" href="http://exacttarget.github.com/fuelux/">Bootstrap</a>, <a target="_blank" href="http://requirejs.org">Fuel UX</a></li> \
                        </ul> \
                    </p> \
                ');
                this.isShow = true;
            }
        }
        //on change, check 
        License.prototype.change = function(e) {
            if ( ! this.$checkBox.checkbox('isChecked'))
            {
                this.$container.effect('shake');
                e.preventDefault();
            }
        }
        // changed
        License.prototype.changed = function() {
            //do nothing
            
        }
        var license = new License();
        return license;
    }
);