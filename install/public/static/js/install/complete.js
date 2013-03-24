define(["jquery", 'jquery-ui', "text!templates/complete.html"], function($, ui, tpl) {
        var Complete = function() {
            this.isShow = false;
            this.$container = $('#step-complete');
            this.baseUrl = window.location.href.replace(/(\/install\/public.*)/, '');
            this.init();
        }
        //init html
        Complete.prototype.init = function() {
            var $this = this;
            this.$container.html(tpl);
            this.$loading = $('#loading');
            this.$installSuccess = $('#installSuccess');
            $('#adminUrl').attr('href', this.baseUrl+'/admin');
        }
        //when show
        Complete.prototype.show = function() {
            if ( ! this.isShow)
            {
                this.createCache();
                this.isShow = true;
            }
        }
        //on change, check 
        Complete.prototype.change = function(e) {
            if ( ! this.isPassed)
            {
                this.$container.effect('shake');
                e.preventDefault();
            }
        }
        // changed
        Complete.prototype.changed = function() {
            //do nothing
        }

        Complete.prototype.createCache = function() {
            var $this = this;
            $.ajax({
                url: 'index.php/install/complete',
                type: "POST",
                beforeSend: function() {
                    $this.$loading.show();
                }
            }).done(function (data) {
                $this.$loading.hide();
                $this.$installSuccess.show();
                $this.$container.find('.modal-header h3').text('安装完成');
                $this.isPassed = true;
                window.wizard.wizard('next');
            });
        }
        var complete = new Complete();
        return complete;
    }
);