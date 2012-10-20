<?php if ( ! defined('IN_DILICMS')) exit('No direct script access allowed');
/**
 * DiliCMS
 *
 * 一款基于并面向CodeIgniter开发者的开源轻型后端内容管理系统.
 *
 * @package     DiliCMS
 * @author      DiliCMS Team
 * @copyright   Copyright (c) 2011 - 2012, DiliCMS Team.
 * @license     http://www.dilicms.com/license
 * @link        http://www.dilicms.com
 * @since       Version 1.0
 * @filesource
 */

// ------------------------------------------------------------------------

/**
 * DiliCMS 表单控件类
 *
 * @package     DiliCMS
 * @subpackage  Libraries
 * @category    Libraries
 * @author      Jeongee
 * @link        http://www.dilicms.com
 */
class Form 
{
    /**
     * 构造函数
     *
     * @access  public
     * @return  void
     */
	public function __construct()
	{
		//nothing to do!
	}
	
	// ------------------------------------------------------------------------

    /**
     * 输出控件HTML
     *
     * @access  public
     * @param   array
     * @param   string
     * @param   bool
     * @return  void
     */
	public function display( & $field, $default = '', $has_tip = TRUE)
	{
		$this->_find_real_value($field['name'], $default);
		$type = '_'.$field['type']; 
		if ($has_tip)
		{
			echo  $this->_add_tip($field['ruledescription'], $this->$type($field, $default));	
		}
		else
		{
			echo  $this->$type($field, $default);	
		}
	}
	
	// ------------------------------------------------------------------------

    /**
     * 检测表单元素的真正的值
     *
     * @access  private
     * @param   string
     * @param   string
     * @return  void
     */
	private function _find_real_value($name, & $default)
	{
		if (isset($_POST[$name]))
		{
			$default = 	$_POST[$name];
		}
	}
	
	// ------------------------------------------------------------------------

    /**
     * 输出分类的HTML
     *
     * @access  public
     * @param   array
     * @param   string
     * @param   string
     * @return  void
     */
	public function show_class( & $category, $name, $default )
	{
		$this->_find_real_value($name, $default);
		$html = '<select name="' . $name . '" id="' . $name .'">'.
                  '<option value="">请选择</option>';
	    foreach ($category as $v)
		{
			$html .= 	'<option value="' . $v['class_id'] . '" ' . ($default == $v['class_id'] ? 'selected="selected"' : '') . '>';
			for ($i = 0 ; $i < $v['deep'] ; $i++)
			{
				$html .= "&nbsp;&nbsp;";
			}
			$html .= $v['class_name'] . '</option>';
		}
		$html .= '</select>';
		echo $html;
	}
	
	// ------------------------------------------------------------------------

    /**
     * 输出隐藏控件的HTML
     *
     * @access  public
     * @param   string
     * @param   string
     * @return  void
     */
	public function show_hidden($name, $default = '', $lock = FALSE)
	{
		if ($lock == true)
		{
			$this->_find_real_value($name, $default);
		}
		echo '<input type="hidden" name="' . $name . '" id="' . $name . '" value="' . $default . '" />';	
	}

	// ------------------------------------------------------------------------

    /**
     * 根据给定的类型输出控件的HTML
     *
     * @access  public
     * @param   string
     * @param   string
     * @param   string
     * @param   string
     * @return  void
     */
	public function show($name, $type, $value = '', $default = '')
	{
		$this->_find_real_value($name, $default);
		$type = '_' . $type;
		$field = array('name' => $name, 'values' => $value, 'width' => 0, 'height' => 0);
		echo $this->$type($field, $default);
	}
	
	// ------------------------------------------------------------------------

    /**
     * 生成INT类型控件HTML
     *
     * @access  private
     * @param   array
     * @param   string
     * @return  string
     */
	private function _int($field, $default)
	{
		return '<input class="normal" name="' . $field['name'] . '" id="' . $field['name'] . 
			   '" type="text" style="width:50px" autocomplete="off" value="' . $default . '" />';
	}
	
	// ------------------------------------------------------------------------

    /**
     * 生成FLOAT类型控件HTML
     *
     * @access  private
     * @param   array
     * @param   string
     * @return  string
     */
	private function _float($field, $default)
	{
		return '<input class="normal" name="' . $field['name'] . '" id="' .$field['name'] . 
		       '" type="text" style="width:50px" autocomplete="off" value="' . $default . '" />';	
	}

	// ------------------------------------------------------------------------

    /**
     * 生成PASSWORD类型控件HTML
     *
     * @access  private
     * @param   array
     * @param   string
     * @return  string
     */
	private function _password($field, $default)
	{
		$field['width'] =  $field['width'] ? $field['width'] : 150;
		return '<input class="normal" name="' . $field['name'] . '" id="' . $field['name'] . 
		       '" type="password" style="width:' . $field['width'] . 'px" autocomplete="off" />';
	}
	
	// ------------------------------------------------------------------------

    /**
     * 生成INPUT类型控件HTML
     *
     * @access  private
     * @param   array
     * @param   string
     * @return  string
     */
	private function _input($field, $default)
	{
		$field['width'] =  $field['width'] ? $field['width'] : 150;
		return '<input class="normal" name="' . $field['name'] . '" id="' . $field['name'] . 
		       '" type="text" style="width:' . $field['width'] . 'px" autocomplete="off" value="' . $default . '" />';
	}
	
	// ------------------------------------------------------------------------

    /**
     * 生成TEXTAREA类型控件HTML
     *
     * @access  private
     * @param   array
     * @param   string
     * @return  string
     */
	private function _textarea($field, $default)
	{
		if ( ! $field['width'] )
		{
			$field['width'] = 300;
		}
		if ( ! $field['height'] )
		{
			$field['height'] = 100;
		}
		return '<textarea class="hack_xheditor" id="' . $field['name'] . '" name="' . $field['name'] . 
		       '" style="width:' . $field['width'] . 'px;height:' . $field['height'] . 'px">' . $default . '</textarea>';
	}
	
	// ------------------------------------------------------------------------

    /**
     * 生成SELECT类型控件HTML
     *
     * @access  private
     * @param   array
     * @param   string
     * @return  string
     */
	private function _select($field, $default)
	{
		$return = '<select name="' . $field['name'] . '" id="' . $field['name'] . '">'.
                  '<option value="">请选择</option>';
	    foreach ($field['values'] as $key=>$v)
		{
			$pre_fix = '';
			if (isset($field['levels'][$key]) AND $field['levels'][$key] > 0)
			{
				for ($i = 0 ; $i < $field['levels'][$key] ; $i ++)
				{
					$pre_fix .= '&nbsp;&nbsp;';
				}
			}
			$return .= 	'<option value="' . $key . '" ' . ($default == $key ? 'selected="selected"' : '') . '>' . $pre_fix . $v . '</option>';
		}
		$return .= '</select>';
		return $return;
	}
	
	// ------------------------------------------------------------------------

    /**
     * 生成RADIO类型控件HTML
     *
     * @access  private
     * @param   array
     * @param   string
     * @return  string
     */
	private function _radio($field, $default)
	{
		$return = '<ul class="attr_list">';
		$count = 1;
	    foreach ($field['values'] as $key=>$v)
		{
			$return .= '<li><input id="rad_' . $field['name'] . '_' . $count . '" name="' . $field['name'] . '" type="radio" value="' . 
			           $key . '" ' . ($default == $key ? 'checked="checked"' : '') . ' /><lable class="attr" for="rad_' . $field['name'] . '_' . $count . '">' . $v . '</lable></li>';
			$count ++;
		}
		$return .= '</ul>';
		return $return;
	}
	
	// ------------------------------------------------------------------------

    /**
     * 生成CHECKBOX类型控件HTML
     *
     * @access  private
     * @param   array
     * @param   string
     * @return  string
     */
	private function _checkbox($field, $default)
	{
		$return = '<ul class="attr_list">';
		if (is_array($field['values']))
		{
			if ( ! is_array($default))
			{
				$default = ($default != '' ? explode(',', $default) : array());
			}
			$count = 1;
			foreach ($field['values'] as $key => $v)
			{
				$return .= 	'<li><input id="chk_' . $field['name'] . '_' . $count . '" name="' . $field['name'] . '[]" type="checkbox" value="' . 
				            $key . '" ' . (in_array($key, $default) ? 'checked="checked"' : '') . ' /><lable class="attr" for="chk_' . $field['name'] . '_' . $count . '">' . $v . '</lable></li>'; 
				$count ++;
			}
		}
		else
		{
			$return .= 	'<li><input id="chk_' . $field['name'] . '" name="' . $field['name'] . '" type="checkbox" value="1" ' . 
			            ($default == 1 ? 'checked="checked"' : '') . ' /><lable class="attr" for="chk_' . $field['name'] . '">' . $field['values'] . '</lable></li>';
		}
		$return .= '</ul>';
		return $return;	
	}
	
	// ------------------------------------------------------------------------

    /**
     * 生成WYSISYG类型控件HTML
     *
     * @access  private
     * @param   array
     * @param   string
     * @param   bool
     * @return  string
     */
	private function _wysiwyg($field, $default, $basic = FALSE)
	{
		$style = 'style="';
		$style .= 'width:' . ($field['width'] ? $field['width'] : '400') . 'px;';
		$style .= 'height:' . ($field['height'] ? $field['height'] : '200')  . 'px;';
		$style .= '"';
		$upload_url = backend_url('attachment/save');
		$upload_config = ",html5Upload:false,upLinkUrl:'$upload_url',upImgUrl:'$upload_url',upFlashUrl:'$upload_url',upMediaUrl:'$upload_url',onUpload:after_editor_upload";
		return '<textarea name="' . $field['name'] . '" id="' . $field['name'] . '" ' . $style . 
		       '  class="xheditor {tools:\'' . ($basic ? 'mini' : 'mfull') . '\',skin:\'nostyle\''.$upload_config.'}">' . $default . '</textarea>';
	}
	
	// ------------------------------------------------------------------------

    /**
     * 生成WYSISWYG_BASIC类型控件HTML
     *
     * @access  private
     * @param   array
     * @param   string
     * @return  string
     */
	private function _wysiwyg_basic($field, $default)
	{
		return $this->_wysiwyg($field, $default, TRUE);   
	}
	
	// ------------------------------------------------------------------------

    /**
     * 生成DATETIME类型控件HTML
     *
     * @access  private
     * @param   array
     * @param   string
     * @return  string
     */
	private function _datetime($field, $default)
	{
		return '<input class="Wdate" style="width:150px;" type="text" name="' . $field['name'] . '" id="' . 
		       $field['name'] . '" value="' . $default . '" onFocus="WdatePicker({isShowClear:false,readOnly:true,dateFmt:\'yyyy-MM-dd HH:mm:ss\'})"/>';	
	}
	
	// ------------------------------------------------------------------------

    /**
     * 生成COLORPICKER类型控件HTML
     *
     * @access  private
     * @param   array
     * @param   string
     * @return  string
     */
	private function _colorpicker($field, $default)
	{
		if ( ! $field['width'] )
		{
			$field['width'] = 100;
		}
		return '<input class="field_colorpicker normal" name="' . $field['name'] . '" id="' . $field['name'] . 
			   '" type="text" style="width:' . $field['width'] . 'px" autocomplete="off" value="' . $default . '" />';
	}
	
	// ------------------------------------------------------------------------

    /**
     * 生成LINKED_MENU类型控件HTML
     *
     * @access  private
     * @param   array
     * @param   string
     * @return  string
     */
	private function _linked_menu($field , $default)
	{
		$html = '';
		if ( ! $field['values'])
		{
			return '请设置数据源';
		}
		if (count($options = explode('|', $field['values'])) != 4)
		{
			return '数据源格式不正确';
		}
		$ci = & get_instance();
		if ( ! $ci->platform->cache_exists(DILICMS_SHARE_PATH . 'settings/category/data_'.$options[0].'.php'))
		{
			return '分类模型数据不存在!';
		}
		for ($i = 1 ; $i <= $options[2] ; $i ++)  
		{
			$html .= '<select class="linked_menu_' . $options[0] . '"><option value="">请选择</option></select>';
		}
		$html .= '<input type="hidden" value="' . $default . '" name="'.$field['name'].'" id="'.$field['name'].'" />';
		$html .= '<button type="button" onclick="linked_menu_insert(\'linked_menu_' . $options[0] . '\',\'' . $field['name'] . '\',' . $options[3] . 
			     ');"  class="button"><span>添加</span></button>';
		$html .= '<div class="linked_menu"><ul id="linked_menu_' . $options[0] . '_list">';
		if ($default)
		{
			$ci->settings->load('category/data_' . $options[0]);
			$model_data =  & setting('category');
			$default = explode('|',$default);
			foreach ($default as $v)
			{
				$v = str_replace(',', '', $v);
				$k = explode('-', $v);
				foreach ($k as & $kk)
				{
					$kk = isset($model_data[$options[0]][$kk][$options[1]]) ? $model_data[$options[0]][$kk][$options[1]] : 'undefined' ; 	
				}
				$html .= '<li><em class="value">' . $v . '</em><em>' . implode('-',$k) . '</em><span onclick="linked_menu_delete(\'linked_menu_' . 
					     $options[0] . '\',\'' . $field['name'] . '\', this);">移除</span></li>';	
			}
		}
		$html .= '</ul></div>';
		$html .= '<script language="javascript">$(".linked_menu_' . $options[0] . '").ld({ajaxOptions : {"url" : "' . 
			     backend_url('ld/json/' . $options[0] . '/' . $options[1]) . '"},style : {"width" : 120},field:{region_id:"classid",region_name:"' . 
			     $options[1] . '",parent_id:"parentid"}});</script>';
		return $html;
	}
	
	// ------------------------------------------------------------------------

    /**
     * 生成SELECT_FROM_MODEL类型控件HTML
     *
     * @access  private
     * @param   array
     * @param   string
     * @return  string
     */
	private function _select_from_model($field, $default)
	{
		if ( ! $this->_get_data_from_model($field, TRUE))
		{
			return '获取数据源时出错了!';
		}
		return $this->_select($field, $default);
	}
	
	// ------------------------------------------------------------------------

    /**
     * 生成RADIO_FROM_MODEL类型控件HTML
     *
     * @access  private
     * @param   array
     * @param   string
     * @return  string
     */
	private function _radio_from_model($field, $default)
	{
		if ( ! $this->_get_data_from_model($field))
		{
			return '获取数据源时出错了!';
		}
		return $this->_radio($field, $default);
	}
	
	// ------------------------------------------------------------------------

    /**
     * 生成CHECKBOX_FROM_MODEL类型控件HTML
     *
     * @access  private
     * @param   array
     * @param   string
     * @return  string
     */
	private function _checkbox_from_model($field, $default)
	{
		if ( ! $this->_get_data_from_model($field))
		{
			return '获取数据源时出错了!';
		}
		return $this->_checkbox($field, $default);
	}
	
	// ------------------------------------------------------------------------

    /**
     * 获取缓存数据并处理，返回处理状态
     *
     * @access  private
     * @param   array
     * @param   bool
     * @return  bool
     */
	private function _get_data_from_model( & $field , $need_level = FALSE)
	{
		if ( ! $field['values'])
		{
			return FALSE;
		}
		if (count($options = explode('|', $field['values'])) != 2 )
		{
			return FALSE;
		}
		$ci = & get_instance();
		if ( ! $ci->platform->cache_exists(DILICMS_SHARE_PATH . 'settings/category/data_' . $options[0] . EXT))
		{
			return FALSE;
		}
		$ci->settings->load('category/data_' . $options[0]);
		$model_data =  & setting('category');
		$field['values'] = array();
		foreach ($model_data[$options[0]] as $v)
		{
			$field['values'][$v['classid']] = $v[$options[1]];
			$need_level AND $field['levels'][$v['classid']] = $v['deep'];
		}
		return TRUE;
	}
	
	// ------------------------------------------------------------------------

    /**
     * 生成控件的TIPS
     *
     * @access  private
     * @param   array
     * @param   string
     * @return  string
     */
	private function _add_tip( & $rules, & $html)
	{
		if ($rules)
		{
			$html .= '<label>'.$rules.'</lable>';
		}
		return $html;
	}
}

/* End of file Form.php */
/* Location: ./shared/libraries/Form.php */