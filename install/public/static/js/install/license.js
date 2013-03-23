define(["jquery", 'jquery-ui', "text!templates/license.html"], function($, ui, tpl) {
        var container = $('#step-license');
        var License = function() {
            this.isShow = false;
            this.init();
            this.show();
        }
        //init html
        License.prototype.init = function() {
            container.html(tpl);
            container.find('.checkbox-custom').checkbox();
            container.find('.checkbox-custom').on('changed', function (e, data) {
                if (data.isChecked)
                {
                    container.find('.modal-footer button').removeClass('disabled');
                }
                else
                {
                    container.find('.modal-footer button').addClass('disabled');
                }
            });
            container.find('.modal-footer button').click(function(){
                window.wizard.wizard('next');
            });
        }
        //when show
        License.prototype.show = function() {
            if ( ! this.isShow)
            {
                container.find('.modal-body').html('DiliCMS是开源的，面向CodeIgniter开发者的，自由灵活的后台系统，并致力于为开发者提供最简单，易扩展，实用的后台系统。');
                this.isShow = true;
            }
        }
        //on change, check 
        License.prototype.change = function(e) {
            if ( ! container.find('.checkbox-custom').checkbox('isChecked'))
            {
                $(container).effect('shake');
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