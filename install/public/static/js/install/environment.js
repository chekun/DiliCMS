define(["jquery", 'jquery-ui', "text!templates/environment.html"], function($, ui, tpl) {
        var Environment = function() {
            this.isShow = false;
            this.$container = $('#step-environment');
            this.init();
        }
        //init html
        Environment.prototype.init = function() {
            var $this = this;
            this.$container.html(tpl);
            this.$refreshBtn = $(this.$container).find('.modal-header > button');
            this.$nextBtn = $(this.$container).find('.modal-footer > button');
            this.$nextBtn.click(function(){
                window.wizard.wizard('next');
            });
            this.$refreshBtn.click(function(){
                $this.show(true);
            });
        }
        //when show
        Environment.prototype.show = function(refresh) {
            refresh = refresh || false;
            var $this = this;
            if ( ! this.isShow || refresh)
            {
                $.ajax({
                    url: "index.php/install/environment",
                    dataType: 'html',
                    cache: false,
                    beforeSend: function() {
                        if (refresh)
                        {
                            $this.$refreshBtn.addClass('disabled').text('检测中...');
                        }
                    }
                }).done(function (html) {
                    if (html == 'pass')
                    {
                        $this.$container.find('.modal-body').html(' \
                            <div class="alert"> \
                                你使用的是<strong>SAE</strong>平台, 可以直接进行下一步。\
                            </div> \
                        ');
                        $this.$container.data('platform', 'sae');
                        $this.$nextBtn.removeClass('disabled');
                    }
                    else
                    {
                        $this.$container.find('.modal-body').html(html);
                        if ($this.isPassed())
                        {
                            $this.$nextBtn.removeClass('disabled');
                            $this.$refreshBtn.hide();
                            $this.$container.data('platform', 'default');
                        }
                        else
                        {
                            $this.$nextBtn.addClass('disabled');
                            $this.$refreshBtn.show();
                        }
                    }
                    $this.isShow = true;
                }).always(function (){
                    if (refresh)
                    {
                        $this.$refreshBtn.removeClass('disabled').text('重新检测');
                    }
                });
            }
        }
        //on change, check 
        Environment.prototype.change = function(e) {
            if ( ! this.isPassed())
            {
                this.$container.effect('shake');
                e.preventDefault();
            }
        }
        // changed
        Environment.prototype.changed = function() {
            //do nothing
            
        }

        Environment.prototype.isPassed = function() {
            return ! this.$container.find('.error').length;
        }

        var environment = new Environment();
        return environment;
    }
);