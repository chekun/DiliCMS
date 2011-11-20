<?php  if ( ! defined('IN_DiliCMS')) exit('No direct script access allowed');

class Field_behavior {
	
	private $_ci = NULL;
	private $_extra_fields  = array();
	
	public function __construct()
	{
		$this->_ci = &get_instance();
		$this->_ci->settings->load('fieldtypes');
		log_message('debug', "DiliCMS Field_behavior Class Initialized");	
	}
	
	private function _load_extra_field($type)
	{
		if(!in_array($type,array_keys($this->_extra_fields)))
		{
			$extra_class = 'extra_field_'.$type;
			if(file_exists(FCPATH.'extra/'.$extra_class.EXT))
			{
				include FCPATH.'extra/'.$extra_class.EXT;
				if(class_exists($extra_class))
				{
					$this->_extra_fields[$type] = new $extra_class();
				}
				else
				{
					$this->_ci->_message('自定义的字段类型类不存在','',false);
				}
			}
			else
			{
				$this->_ci->_message('自定义的字段类型类文件不存在','',false);
			}
		}
		
	}
	
	private function _is_extra($type = '')
	{
		return !in_array($type,array_keys(setting('fieldtypes')));
	}
	
	
	public function  on_info($data,$oldname = '')
	{
		if($this->_is_extra($data['type']))
		{
			$this->_load_extra_field($data['type']);
			$field = $this->_extra_fields[$data['type']]->on_info($data);
		}
		else
		{
			switch($data['type'])
				{
					case 'select_from_model' :
					case 'radio_from_model':
					case 'int'   	: $field = array(
													'type' => 'INT',
													 'constraint' => $data['length'] ? $data['length'] : 10 ,
													 'default' => 0
													) ;
									break;
					case 'float' : $field = array(
													'type' => 'FLOAT',
													 'constraint' => $data['length'] ? $data['length'] : 10,
													 'default' => 0
													) ;
									break;
					case 'input' : 
					case 'select':
					case 'radio' :
					case 'checkbox':
					case 'checkbox_from_model':
					case 'datetime':
					case 'colorpicker':
					case 'linked_menu':
					case 'textarea' : 
									$field = array(
													'type' => 'VARCHAR',
													 'constraint' => $data['length'] ? $data['length'] : 100 ,
													 'default' => ''
													) ;
									break;
					case 'wysiwyg' :
					case 'wysiwyg_basic':
									$field = array(
													'type' => 'TEXT',
													'default' => ''
													) ;
									break;
				}
			}
			if($oldname != '')
			{
				$field['name'] = $data['name'];
				return array($oldname => $field);
			}
			else
			{
				return array($data['name'] => $field);
			}
	}
	
	public function on_form( $field, $default = '' , $has_tip = true)
	{
		
		if($this->_is_extra($field['type']))
		{
			$this->_load_extra_field($field['type']);
			$this->_extra_fields[$field['type']]->on_form($field, $default, $has_tip);
		}
		else
		{
			//查看是否有指定默认值,以下字段类型支持
			$default_value_enabled = array('int','float','input','textarea','colorpicker','datetime');
			if(in_array($field['type'],$default_value_enabled) && $default == '' && $field['values'])
			{
				$default = $field['values'];
			}
			$this->_ci->form->display($field, $default, $has_tip);
		}
	}
	
	public function on_list( $field ,  $value)
	{
		if($this->_is_extra($field['type']))
		{
			$this->_load_extra_field($field['type']);
			$this->_extra_fields[$field['type']]->on_list($field,  $value);
		}
		else
		{
			switch($field['type'])
			{
				case 'radio' 	:
				case 'select'	:
							echo isset($field['values'][$value->$field['name']]) ?  $field['values'][$value->$field['name']] : 'undefined' ;
							break;
				case 'checkbox' :
							foreach(explode(',',$value->$field['name']) as $t)
							{
								echo isset($field['values'][$t]) ?  $field['values'][$t].'<br />' : 'undefined'.'<br />';
							}
							break;
				case 'radio_from_model':
				case 'select_from_model':
							$options = explode('|',$field['values']);
							$this->_ci->settings->load('category/data_'.$options[0]);
							$setting = &setting('category');
							echo isset($setting[$options[0]][$value->$field['name']][$options[1]]) ? $setting[$options[0]][$value->$field['name']][$options[1]] : 'undefined' ;
							break;
				case 'checkbox_from_model':
							$options = explode('|',$field['values']);
							$this->_ci->settings->load('category/data_'.$options[0]);
							$setting = &setting('category');
							$checkbox_values = explode(',',$value->$field['name']);
							foreach($checkbox_values as $checkbox)
							{
								echo isset($setting[$options[0]][$checkbox][$options[1]]) ? $setting[$options[0]][$checkbox][$options[1]].'<br />' : 'undefined<br />' ;
							}
							break;
				case 'linked_menu':
							$options = explode('|',$field['values']);
							$this->_ci->settings->load('category/data_'.$options[0]);
							$setting = &setting('category');
							$temp_out = explode('|',$value->$field['name']);
							foreach($temp_out as &$t)
							{
								$t = str_replace(',','',$t);
								$temp = explode('-',$t);
								foreach($temp as &$tt)
								{
									$tt = (isset($setting[$options[0]][$tt][$options[1]]) ? $setting[$options[0]][$tt][$options[1]] : 'undefined');
								}
								$t = implode('-',$temp);
							}
							echo implode(',',$temp_out);
							break;
				default :
							echo $value->$field['name'];	
			}
		}
	}
	
	public function on_search($field , $default)
	{
		if($this->_is_extra($field['type']))
		{
			$this->_load_extra_field($field['type']);
			$this->_extra_fields[$field['type']]->on_search($field, $default);
		}
		else
		{
			switch($field['type'])
			{
				case 'select':
				case 'checkbox':
				case 'radio':
				case 'select_from_model':
				case 'radio_from_model':
				case 'checkbox_from_model':
				case 'linked_menu':
				case 'colorpicker':
						$this->_ci->form->display($field , $default , false);
						break;
				case 'datetime':
				case 'int':
				case 'float':
						$field_min = $field_max = $field;
						$field_min['name'] = $field_min['name'].'_min';
						$field_max['name'] = $field_max['name'].'_max';
						$this->_ci->form->display($field_min , $this->_ci->input->get_post($field['name'].'_min') ? $this->_ci->input->get_post($field['name'].'_min') : '' , false);
						echo ' ---- ';
						$this->_ci->form->display($field_max , $this->_ci->input->get_post($field['name'].'_max') ? $this->_ci->input->get_post($field['name'].'_max') : '' , false);
						break;
				default : 
						echo $this->_ci->form->_input($field ,$default);
			}
		}
	}
	
	public function on_do_search( $field , & $condition , & $where ,& $suffix )
	{
		if($this->_is_extra($field['type']))
		{
			$this->_load_extra_field($field['type']);
			$this->_extra_fields[$field['type']]->on_do_search($field,$condition,$where,$suffix);
		}
		else
		{
			switch($field['type'])
			{
				case 'select':
				case 'radio':
				case 'select_from_model':
				case 'radio_from_model':
				case 'colorpicker':
						if($keyword = $this->_ci->input->get_post($field['name']))
						{
							$condition[$field['name'].' ='] = $keyword;
							$where[$field['name']] = $keyword;
							$suffix .= '&'.$field['name'].'='.$keyword;
						}
						break;
				case 'datetime':
				case 'int':
				case 'float':
						if($keyword_min = $this->_ci->input->get_post($field['name'].'_min'))
						{
							$condition[$field['name'].' >='] = $keyword_min;
							$where[$field['name'].'_min'] = $keyword_min;
							$suffix .= '&'.$field['name'].'_min='.$keyword_min;
						}
						if($keyword_max = $this->_ci->input->get_post($field['name'].'_max'))
						{
							$condition[$field['name'].' <='] = $keyword_max;
							$where[$field['name'].'_max'] = $keyword_max;
							$suffix .= '&'.$field['name'].'_max='.$keyword_max;
						}
						break;
				case 'input':
				case 'textarea':
				case 'wysiwyg':
				case 'wysiwyg_basic':
						if($keyword = $this->_ci->input->get_post($field['name']))
						{
							$condition[$field['name'].' LIKE'] = $keyword;
							$where[$field['name']] = $keyword;
							$suffix .= '&'.$field['name'].'='.$keyword;
						}
						break;
				case 'checkbox':
				case 'checkbox_from_model':
				case 'linked_menu':
						if($keyword = $this->_ci->input->get_post($field['name']))
						{
							$where[$field['name']] = $keyword;
							$suffix .= '&'.$field['name'].'='.$keyword;
							$keyword = is_array($keyword) ? $keyword : explode(( $field['type'] == 'linked_menu' ? '|' : ','),$keyword);
							$real_condition = array();
							foreach($keyword as $k)
							{
								$real_condition[] = $field['name']." LIKE '%$k%' ";
							}
							if($real_condition)
							{
								$this->_ci->db->where(implode(' AND ',$real_condition),'',false);
							}
						}
						break;
				default :
						break;
			}
		}
	}
	
}

