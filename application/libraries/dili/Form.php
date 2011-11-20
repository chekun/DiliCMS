<?php  if ( ! defined('IN_DiliCMS')) exit('No direct script access allowed');

class Form {
	
	function __construct()
	{
		log_message('debug', "DiliForm Class Initialized");	
	}
	
	
	function display(& $field, $default = '' , $has_tip = true)
	{
		$this->_find_real_value( $field['name'] , $default);
		$type = '_'.$field['type']; 
		if($has_tip)
		{
			echo  $this->_add_tip($field['ruledescription'],$this->$type($field,$default));	
		}
		else
		{
			echo  $this->$type($field,$default);	
		}
	}
	
	function _find_real_value($name, & $default)
	{
		if(isset($_POST[$name]))
		{
			$default = 	$_POST[$name];
		}
	}
	
	function show_class( & $category , $name , $default )
	{
		$this->_find_real_value($name,$default);
		$return = '<select name="'. $name .'" id="'. $name .'">'.
                  '<option value="">请选择</option>';
	    foreach($category as $v)
		{
			$return .= 	'<option value="'.$v['class_id'].'" '.($default == $v['class_id'] ? 'selected="selected"' : '').'>';
			for($i=0 ; $i < $v['deep'] ; $i++){$return .= "&nbsp;&nbsp;";}
			$return .= $v['class_name'].'</option>';
		}
		$return .= '</select>';
		echo $return;
	}
	
	function show_hidden($name , $default = '' , $lock = false)
	{
		if($lock == true)
		{
			$this->_find_real_value($name,$default);
		}
		echo '<input type="hidden" name="'.$name.'" id="'.$name.'" value="'.$default.'" />';	
	}
	
	function show($name , $type ,$value = '' , $default = '')
	{
		$this->_find_real_value($name,$default);
		$type = '_'.$type;
		$field = array( 'name' => $name , 'values' => $value , 'width' => 0 , 'height' => 0 );
		echo $this->$type($field,$default);
	}
	
	function _int($field, $default)
	{
		return '<input class="normal" name="'.$field['name'].'" id="'.$field['name'].'" type="text" style="width:50px" autocomplete="off" value="'.$default.'" />';
	}
	
	function _float($field, $default)
	{
		return '<input class="normal" name="'.$field['name'].'" id="'.$field['name'].'" type="text" style="width:50px" autocomplete="off" value="'.$default.'" />';	
	}
	
	function _password($field, $default)
	{
		$field['width'] =  $field['width'] ? $field['width'] : 150;
		return '<input class="normal" name="'.$field['name'].'" id="'.$field['name'].'" type="password" style="width:'.$field['width'].'px" autocomplete="off" />';
	}
	
	function _input($field, $default)
	{
		$field['width'] =  $field['width'] ? $field['width'] : 150;
		return '<input class="normal" name="'.$field['name'].'" id="'.$field['name'].'" type="text" style="width:'.$field['width'].'px" autocomplete="off" value="'.$default.'" />';
	}
	
	function _textarea($field, $default)
	{
		if(! $field['width'] )
		{
			$field['width'] = 300;
		}
		if(! $field['height'] )
		{
			$field['height'] = 100;
		}
		return '<textarea class="hack_xheditor" id="'.$field['name'].'" name="'.$field['name'].'" style="width:'.$field['width'].'px;height:'.$field['height'].'px">'.$default.'</textarea>';
	}
	
	function _select($field, $default)
	{
		$return = '<select name="'. $field['name'] .'" id="'. $field['name'] .'">'.
                  '<option value="">请选择</option>';
	    foreach($field['values'] as $key=>$v)
		{
			$pre_fix = '';
			if(isset($field['levels'][$key]) && $field['levels'][$key] > 0)
			{
				for($i = 0 ;$i < $field['levels'][$key] ; $i ++)
				{
					$pre_fix .= '&nbsp;&nbsp;';
				}
			}
			$return .= 	'<option value="'.$key.'" '.($default == $key ? 'selected="selected"' : '').'>'.$pre_fix.$v.'</option>';
		}
		$return .= '</select>';
		return $return;
	}
	
	function _radio($field, $default)
	{
		$return = '<ul class="attr_list">';
	    foreach($field['values'] as $key=>$v)
		{
			$return .= '<li><lable class="attr"><input name="'.$field['name'].'" type="radio" value="'.$key.'" '.($default == $key ? 'checked="checked"' : '').' />'.$v.'</lable></li>';
		}
		$return .= '</ul>';
		return $return;
	}
	
	function _checkbox($field, $default)
	{
		$return = '<ul class="attr_list">';
		if(is_array($field['values']))
		{
			if(!is_array($default))
			{
				$default = ($default != '' ? explode(',',$default) : array());
			}
			foreach($field['values'] as $key=>$v)
			{
				$return .= 	'<li><lable class="attr"><input name="'.$field['name'].'[]" type="checkbox" value="'.$key.'" '.(in_array($key,$default) ? 'checked="checked"' : '').' />&nbsp;'.$v.'</lable></li>'; 
			}
		}
		else
		{
			$return .= 	'<li><lable class="attr"><input name="'.$field['name'].'" type="checkbox" value="1" '.($default == 1 ? 'checked="checked"' : '').' />'.$field['values'].'</lable></li>';
		}
		$return .= '</ul>';
		return $return;	
	}
	
	function _wysiwyg($field, $default)
	{
		$style = 'style="';
		$style .= 'width:'. ($field['width'] ? $field['width'] : '400') .'px;';
		$style .= 'height:'. ($field['height'] ? $field['height'] : '200')  .'px;';
		$style .= '"';
		return '<textarea name="'. $field['name'] .'" id="'. $field['name'] .'" '. $style .'  class="xheditor {tools:\'mfull\',skin:\'nostyle\'}">'.$default.'</textarea>';
	}
	
	function _wysiwyg_basic($field, $default)
	{
		$style = 'style="';
		$style .= 'width:'. ($field['width'] ? $field['width'] : '400') .'px;';
		$style .= 'height:'. ($field['height'] ? $field['height'] : '200')  .'px;';
		$style .= '"';
		return '<textarea name="'. $field['name'] .'" id="'. $field['name'] .'" '. $style .'  class="xheditor {tools:\'mini\',skin:\'nostyle\'}">'.$default.'</textarea>';    
	}
	
	function _datetime($field, $default)
	{
		return '<input class="Wdate" style="width:150px;" type="text" name="'. $field['name'] .'" id="'. $field['name'] .'" value="'.$default.'" onFocus="WdatePicker({isShowClear:false,readOnly:true,dateFmt:\'yyyy-MM-dd HH:mm:ss\'})"/>';	
	}
	
	function _colorpicker($field, $default)
	{
		if(! $field['width'] )
		{
			$field['width'] = 100;
		}
		return '<input class="field_colorpicker normal" name="'.$field['name'].'" id="'.$field['name'].'" type="text" style="width:'.$field['width'].'px" autocomplete="off" value="'.$default.'" />';
	}
	
	function _linked_menu($field , $default)
	{
		$html = '';
		if(!$field['values']) {return '请设置数据源';}
		if(count($options = explode('|',$field['values'])) != 4 ) {return '数据源格式不正确';}
		$ci = &get_instance();
		if(!$ci->platform->cache_exists(FCPATH.'settings/category/data_'.$options[0].EXT)){return '分类模型数据不存在!';}
		for($i = 1 ; $i <= $options[2] ; $i++)  
		{
			$html .= '<select class="linked_menu_'.$options[0].'"><option value="">请选择</option></select>';
		}
		$html .= '<input type="hidden" value="'.$default.'" name="'.$field['name'].'" id="'.$field['name'].'" />';
		$html .= '<button type="button" onclick="linked_menu_insert(\'linked_menu_'.$options[0].'\',\''.$field['name'].'\','.$options[3].');"  class="button"><span>添加</span></button>';
		$html .= '<div class="linked_menu"><ul id="linked_menu_'.$options[0].'_list">';
		if($default)
		{
			$ci->settings->load('category/data_'.$options[0]);
			$model_data =  & setting('category');
			$default = explode('|',$default);
			foreach($default as $v)
			{
				$v = str_replace(',','',$v);
				$k = explode('-',$v);
				foreach($k as &$kk)
				{
					$kk = isset($model_data[$options[0]][$kk][$options[1]]) ? $model_data[$options[0]][$kk][$options[1]] : 'undefined' ; 	
				}
				$html .= '<li><em class="value">'.$v.'</em><em>'.implode('-',$k).'</em><span onclick="linked_menu_delete(\'linked_menu_'.$options[0].'\',\''.$field['name'].'\',this);">移除</span></li>';	
			}
		}
		$html .= '</ul></div>';
		$html .= '<script language="javascript">$(".linked_menu_'.$options[0].'").ld({ajaxOptions : {"url" : "'.backend_url('ld/json/'.$options[0].'/'.$options[1]).'"},style : {"width" : 120},field:{region_id:"classid",region_name:"'.$options[1].'",parent_id:"parentid"}});</script>';
		return $html;
	}
	
	function _select_from_model($field , $default)
	{
		if(!$this->_get_data_from_model($field ,true))
		{
			return '获取数据源时出错了!';
		}
		return $this->_select($field , $default);
	}
	
	function _radio_from_model($field , $default)
	{
		if(!$this->_get_data_from_model($field))
		{
			return '获取数据源时出错了!';
		}
		return $this->_radio($field , $default);
	}
	
	
	function _checkbox_from_model($field , $default)
	{
		if(!$this->_get_data_from_model($field))
		{
			return '获取数据源时出错了!';
		}
		return $this->_checkbox($field , $default);
	}
	
	function _get_data_from_model( & $field , $need_level = false)
	{
		if(!$field['values']) {return false;}
		if(count($options = explode('|',$field['values'])) != 2 ) {return false;}
		$ci = &get_instance();
		if(!$ci->platform->cache_exists(FCPATH.'settings/category/data_'.$options[0].EXT)){return false;}
		$ci->settings->load('category/data_'.$options[0]);
		$model_data =  & setting('category');
		$field['values'] = array();
		foreach($model_data[$options[0]] as $v)
		{
			$field['values'][$v['classid']] = $v[$options[1]];
			$need_level && $field['levels'][$v['classid']] = $v['deep'];
		}
		return true;
	}
	
	function _add_tip(&$rules , & $html)
	{
		if($rules)
		{
			$html .= '<label>'.$rules.'</lable>';
		}
		return $html;
	}
}

