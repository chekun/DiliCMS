// 
	$(function(){
		var _temp_color = null;
		$('.field_colorpicker').ColorPicker({
												onSubmit : function(hsb, hex, rgb, el) {
																$(el).val('#' + hex);
																$(el).ColorPickerHide();
															},
												onBeforeShow: function () {
													_temp_color = this;
													$(this).ColorPickerSetColor(this.value);
												},
												onChange: function (hsb, hex, rgb) {
													$(_temp_color).val('#' + hex);
												}
		}).bind('keyup', function(){
				$(this).ColorPickerSetColor(this.value);
			});
	});
	
	function linked_menu_insert(container , target , num)
	{
		var valid =  true;
		linked_menus = $('.' + container);
		var selected = { key : Array() , text : Array() };
		$.each(linked_menus.children('option:selected'),function(index,value){
				value = $(this);
				if(!value.val()){valid = false ; return false;}
				selected.key.push(value.val());
				selected.text.push(value.text());
		});
		if(!valid){alert('请填写完整!');return false;}
		list = $('#' + container + '_list');
		if(linked_menu_real_val_num(list) + 1 > num)
		{
			alert('最多只能选择'+ num +'个!');return false;	
		}
		targetInput = $('#' + target);
		selected.key = selected.key.join('-');
		selected.text = selected.text.join('-');
		if(targetInput.val().indexOf(','+ selected.key + ',') == '-1' )
		{
			list.append('<li><em class="value">'+ selected.key +'</em><em>'+ selected.text +'</em><span onclick="linked_menu_delete(\''+ container +'\',\''+ target +'\',this)">删除</span></li>');
			targetInput.val(linked_menu_real_val(list));
		}
		else
		{
			alert('已经存在了');	
		}
	}
	
	function linked_menu_delete(container , target , me)
	{
		
		$(me).parent().remove();
		list = $('#' + container + '_list');
		target = $('#' + target);
		target.val(linked_menu_real_val(list));
	}
	
	function linked_menu_real_val_num(container)
	{
		return container.children('li').length;
	}
	
	function linked_menu_real_val(container)
	{
		var val = Array();
		$.each(container.find('em.value'),function(index,value)
		{
			val.push(','+ $(this).text() +',');
		}
		);
		return val.join('|');
	}