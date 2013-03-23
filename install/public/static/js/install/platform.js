define(["jquery", 'jquery-ui', "text!templates/platform.html"], function($, ui, tpl) {
        var Platform = function() {
            this.isShow = false;
            this.$container = $('#step-platform');
            this.init();
        }
        //init html
        Platform.prototype.init = function() {
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
        Platform.prototype.show = function(refresh) {
            refresh = refresh || false;
            var $this = this;
            if ( ! this.isShow || refresh)
            {
                $.ajax({
                    url: "index.php/install/platform",
                    dataType: 'html',
                    cache: false,
                    beforeSend: function() {
                        if (refresh)
                        {
                            $this.$refreshBtn.addClass('disabled').text('检测中...');
                        }
                    }
                }).done(function (html) {
                    $this.$container.find('.modal-body').html(html);
                    $this.isShow = true;
                    if ($this.isPassed())
                    {
                        $this.$nextBtn.removeClass('disabled');
                        $this.$refreshBtn.hide();
                    }
                    else
                    {
                        $this.$nextBtn.addClass('disabled');
                        $this.$refreshBtn.show();
                    }
                }).always(function (){
                    if (refresh)
                    {
                        $this.$refreshBtn.removeClass('disabled').text('重新检测');
                    }
                });
            }
        }
        //on change, check 
        Platform.prototype.change = function(e) {
            if ( ! this.isPassed())
            {
                this.$container.effect('shake');
                e.preventDefault();
            }
        }
        // changed
        Platform.prototype.changed = function() {
            //do nothing
            
        }

        Platform.prototype.isPassed = function() {
            return ! this.$container.find('.alert-error').length;
        }

        var platform = new Platform();
        return platform;
    }
);