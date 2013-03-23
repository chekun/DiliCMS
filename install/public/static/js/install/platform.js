define(["jquery", 'jquery-ui', "text!templates/platform.html"], function($, ui, tpl) {
        var Platform = function() {
            this.isShow = false;
            this.$container = $('#step-platform');
            this.init();
            this.show();
        }
        //init html
        License.prototype.init = function() {
            var $this = this;
            this.$container.html(tpl);
            this.$container.find('.modal-footer button').click(function(){
                window.wizard.wizard('next');
            });
        }
        //when show
        License.prototype.show = function() {
            if ( ! this.isShow)
            {
                //this.$container.find('.modal-body').html();
                this.isShow = true;
            }
        }
        //on change, check 
        License.prototype.change = function(e) {
            //do nothing here
        }
        // changed
        License.prototype.changed = function() {
            //do nothing
            
        }
        var platform = new Platform();
        return platform;
    }
);