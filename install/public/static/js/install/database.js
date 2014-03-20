define(["jquery", 'jquery-ui', "text!templates/database.html"], function($, ui, tpl) {
        var Database = function() {
            this.isShow = false;
            this.$container = $('#step-database');
            this.init();
        }
        //init html
        Database.prototype.init = function() {
            var $this = this;
            this.$container.html(tpl);
            this.$nextBtn = $(this.$container).find('.modal-footer > button');
            this.$form = this.$container.find('form');
            this.$server = $('#server');
            this.$db = $('#db');
            this.$prefix = $('#prefix');
            this.$user = $('#user');
            this.$password = $('#password');
            this.$console = $('#console');
            this.$nextBtn.click(function() {
                window.wizard.wizard('next');
            });
            this.$form.submit(function() {
                if ($this.platform == 'default')
                {
                    //do somecheck here
                    if ($this.$server.val() == '')
                    {
                        $this.$server.focus().parent().effect('shake');
                        return false;
                    }
                    if ($this.$db.val() == '')
                    {
                        $this.$db.focus().parent().effect('shake');
                        return false;
                    }
                    if ($this.$user.val() == '')
                    {
                        $this.$user.focus().parent().effect('shake');
                        return false;
                    }
                }
                $this.checkConnection();
                return false;
            });
        }
        //when show
        Database.prototype.show = function() {
            this.$nextBtn.addClass('disabled');
            this.$console.hide();
            this.platform = $('#step-environment').data('platform');
            if (this.platform == 'sae')
            {
                this.$server.attr('readonly', true);
                this.$db.attr('readonly', true);
                this.$prefix.attr('readonly', true);
                this.$user.attr('readonly', true);
                this.$password.attr('readonly', true);
                this.$nextBtn.before('<p class="alert alert-error pull-left"><strong>SAE平台无需填写表单，直接点击导入即可.</strong></p>');
                this.$nextBtn.prev().effect('shake');
            }
            this.isShow = true;
        }
        //on change, check 
        Database.prototype.change = function(e) {
            if ( ! this.isPassed())
            {
                this.$container.effect('shake');
                e.preventDefault();
            }
        }
        // changed
        Database.prototype.changed = function() {
            //do nothing
        }

        Database.prototype.isPassed = function() {
            return this.$console.find('span.label-info').length == 18;
        }

        Database.prototype.checkConnection = function() {
            var $this = this;
            var $logList = this.$console.find('ol').html('');
            this.$console.show();
            var fields = this.$form.serialize();
            var params = fields+'&step=check';
            $.ajax({
                url: 'index.php/install/database',
                async: true,
                type: "POST",
                dataType: 'json',
                data: params,
                beforeSend: function (xhr){
                    $this.$form.find('button').hide();
                }
            }).done(function (data) {
                 var css = 'label-info';
                if ( ! data.status)
                {
                    css = 'label-important';
                }
                $.each(data.messages, function (k, v) {
                    $logList.append('<li><span class="label '+ css +'">'+ v +'</span></li>');
                });
                if (data.status)
                {
                    $this.installDb();
                }
                else
                {
                    $logList.append('<li> \
                        <span class="label label-important">数据库连接失败，可能的原因：</span> \
                        <ul> \
                            <li>数据库不存在</li> \
                            <li>用户名或者密码错误</li> \
                        </ul> \
                    </li>');
                }
            }).always(function() {
                $this.$form.find('button').show();
            });
                
        }

        Database.prototype.installDb = function() {
            var steps = Array(
                'admins', 
                'attachments', 
                'backend_settings', 
                'cate_fields', 
                'cate_models',
                'fieldtypes',
                'menus',
                'model_fields',
                'models',
                'plugins',
                'rights',
                'roles',
                'sessions',
                'site_settings',
                'validations',
                'throttles'
            );
            var $this = this;
            var $logList = this.$console.find('ol');
            var fields = this.$form.serialize();
            for(var i = 0, len = steps.length; i < len; i++)
            {
                var params = fields+'&step='+steps[i];
                $.ajax({
                    url: 'index.php/install/database',
                    async: true,
                    type: "POST",
                    dataType: 'json',
                    data: params,
                    beforeSend: function (xhr){
                        $this.$form.find('button').hide();
                    }
                }).done(function (data) {
                    var css = 'label-info';
                    if ( ! data.status)
                    {
                        css = 'label-important';
                    }
                    $.each(data.messages, function (k, v) {
                        $logList.append('<li><span class="label '+ css +'">'+ v +'</span></li>');
                    })
                    $logList.parent().scrollTop(1000);
                    if ($this.isPassed())
                    {
                        $this.$form.find('button').hide();
                        $this.$nextBtn.removeClass('disabled');
                    }
                }).always(function() {
                    $this.$form.find('button').show();
                });
            }
                
        }

        var database = new Database();
        return database;
    }
);