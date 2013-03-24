define(["jquery", 'jquery-ui', "text!templates/account.html"], function($, ui, tpl) {
        var Account = function() {
            this.isShow = false;
            this.$container = $('#step-account');
            this.isPassed = false;
            this.init();
        }
        //init html
        Account.prototype.init = function() {
            var $this = this;
            this.$container.html(tpl);
            this.$form = this.$container.find('form');
            this.$nextBtn = this.$container.find('.modal-footer > button');
            this.$loading = this.$container.find('.modal-header > button');
            this.$username = $('#username');
            this.$password = $('#userpass');
            this.$password_confirm = $('#password_confirm');
            this.$form.submit(function () {
                if ( ! /.{3,16}/.test($this.$username.val()))
                {
                    $this.$username.focus().parent().effect('shake');
                    return false;
                }
                if ( ! /.{6,18}/.test($this.$password.val()))
                {
                    $this.$password.focus().parent().effect('shake');
                    return false;
                }
                if ($this.$password.val() != $this.$password_confirm.val())
                {
                    $this.$password_confirm.focus().parent().effect('shake');
                    return false;
                }
                $this.submit();
                return false;
            });
            this.$nextBtn.click(function(){
                window.wizard.wizard('next');
            });
        }
        //when show
        Account.prototype.show = function() {
            if ( ! this.isShow)
            {
                this.isShow = true;
            }
        }
        //on change, check 
        Account.prototype.change = function(e) {
            if ( ! this.isPassed)
            {
                this.$container.effect('shake');
                e.preventDefault();
            }
        }
        // changed
        Account.prototype.changed = function() {
            //do nothing
            
        }

        Account.prototype.submit = function() {
            var $this = this;
            var fields = this.$form.serialize();
            var params = fields+'&step=check';
            var submitBtn = $this.$form.find('button');
            $.ajax({
                url: 'index.php/install/account',
                type: "POST",
                dataType: 'json',
                data: params,
                beforeSend: function() {
                    submitBtn.hide();
                    $this.$loading.show();
                }
            }).done(function (data) {
                if (data.status)
                {
                    submitBtn.after('<span class="label label-info">帐号创建成功</span>');
                    $this.isPassed = true;
                    $this.$nextBtn.removeClass('disabled');
                }
                else
                {
                    submitBtn.after('<span class="label label-important">帐号创建失败</span>');
                    $this.isPassed = false;
                    submitBtn.show();
                }
                $this.$loading.hide();
            });
        }
        var account = new Account();
        return account;
    }
);