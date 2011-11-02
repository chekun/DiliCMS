<?php 

	class Cache_mdl extends CI_Model
	{
		
		function __construct()
		{
			parent::__construct();	
		}
		
		function _arrayeval($name, $array)
		{
			return '<?php '.PHP_EOL . '$' . $name . '=' . var_export($array,true).';'; 
		}
		
		function update_model_cache($target = NULL) 
		{
			$data = array();
			if( $target )
			{
				$target = is_array($target) ? $target : array($target);
				$this->db->where_in('name' , $target);	
			}
			$models = $this->db->get('dili_models')->result_array();
			foreach($models as $model)
			{
				$model['fields'] = array();
				$model['fields_org'] = $this->db->where('model',$model['id'])->order_by('`order`','ASC')->get('dili_model_fields')->result_array();
				$model['listable'] = array();
				$model['searchable'] = array();
				foreach($model['fields_org'] as $key=>&$v)
				{
					if($v['listable'] == 1)
					{
						array_push($model['listable'],$v['id']);
					}
					if($v['searchable'] == 1)
					{
						array_push($model['searchable'],$v['id']);
					}
					if(in_array($v['type'],array('select','checkbox','radio')))
					{
						if($v['values'] == '')
						{
							$v['values'] = array();
						}
						else
						{
							$value = array();
							foreach(explode('|',$v['values']) as $vt)
							{
								if( strpos($vt,'=') )
								{
									$vt = explode('=',$vt);
									$value[$vt[0]]= $vt[1];
								}
								else
								{
									$value[$vt]= $vt;
								}
							}
							$v['values'] = $value;
						}
					}
					$model['fields'][$v['id']] = $v;
				}
				unset($model['fields_org']);
				file_put_contents(FCPATH.'settings/model/'.$model['name'].EXT,$this->_arrayeval("setting['models']['".$model['name']."']",$model));
			}
		}
		
		function update_category_cache($target = NULL)
		{
			$this->load->model('dili/category_mdl');
			$data = array();
			if( $target )
			{
				$target = is_array($target) ? $target : array($target);
				$this->db->where_in('name' , $target);	
			}
			$models = $this->db->get('dili_cate_models')->result_array();
			foreach($models as $model)
			{
				$model['fields'] = array();
				$model['fields_org'] = $this->db->where('model',$model['id'])->order_by('`order`','ASC')->get('dili_cate_fields')->result_array();
				$model['listable'] = array();
				$model['searchable'] = array();
				foreach($model['fields_org'] as $key=>&$v)
				{
					if($v['listable'] == 1)
					{
						array_push($model['listable'],$v['id']);
					}
					if($v['searchable'] == 1)
					{
						array_push($model['searchable'],$v['id']);
					}
					if(in_array($v['type'],array('select','checkbox','radio')))
					{
						if($v['values'] == '')
						{
							$v['values'] = array();
						}
						else
						{
							$value = array();
							foreach(explode('|',$v['values']) as $vt)
							{
								if( strpos($vt,'=') )
								{
									$vt = explode('=',$vt);
									$value[$vt[0]]= $vt[1];
								}
								else
								{
									$value[$vt]= $vt;
								}
							}
							$v['values'] = $value;
						}
					}
					$model['fields'][$v['id']] = $v;
				}
				unset($model['fields_org']);
				file_put_contents(FCPATH.'settings/category/cate_'.$model['name'].EXT,$this->_arrayeval("setting['cate_models']['".$model['name']."']",$model));
				$category = array();
				$categories =  $this->category_mdl->get_category($model['name']);
				foreach($categories as $c)
				{
					$category[$c['classid']] = $c;	
				}
				file_put_contents(FCPATH.'settings/category/data_'.$model['name'].EXT,$this->_arrayeval("setting['category']['".$model['name']."']",$category));
				unset($categories,$category);
			}
		}
		
		function update_menu_cache()
		{
			$level_1_menus = $this->db->select('menu_id,class_name,method_name,menu_name')->where('menu_level',0)->where('menu_parent',0)->get('dili_menus')->result_array();
			foreach($level_1_menus as & $i)
			{
				$level_2_menus = $this->db->select('menu_id,class_name,method_name,menu_name')->where('menu_level',1)->where('menu_parent',$i['menu_id'])->get('dili_menus')->result_array();
				foreach($level_2_menus as & $j)
				{
					if($j['class_name'] == 'content')
					{
						$level_3_menus = $this->db->select(" 'content' AS class_name , 'view' AS 'method_name' , name AS extra , description AS menu_name",false)
												->get('dili_models')->result_array();
					}
					else if($j['class_name'] == 'category_content')
					{
						$level_3_menus = $this->db->select(" 'category_content' AS class_name , 'view' AS 'method_name' , name AS extra , description AS menu_name",false)
												->get('dili_cate_models')->result_array();
					}
					else
					{
						$level_3_menus = $this->db->select('menu_id,class_name,method_name,menu_name')->where('menu_level',2)->where('menu_parent',$j['menu_id'])->get('dili_menus')->result_array();
					}
					$j['sub_menus'] = $level_3_menus;
				}
				$i['sub_menus'] = $level_2_menus;
			}
			file_put_contents(FCPATH.'settings/menus'.EXT,$this->_arrayeval("setting['menus']",$level_1_menus));
		}
		
		function update_role_cache($target = NULL)
		{
			if( $target )
			{
				$target = is_array($target) ? $target : array($target);
				$this->db->where_in('id' , $target);
			}
			$roles = $this->db->get('dili_roles')->result_array();
			foreach($roles as &$role)
			{	
				$role['rights'] = explode(',',$role['rights']);
				$rights = $this->db->select('right_class,right_method,right_detail')->where_in('right_id',$role['rights'])->get('dili_rights')->result();
				$role['rights'] = array();
				foreach($rights as $right)
				{
					$role['rights'][] = $right->right_class.'@'.$right->right_method.($right->right_detail ? '@'.$right->right_detail :'' ); 	
				}
				$role['models'] = explode(',',$role['models']);
				$role['category_models'] = explode(',',$role['category_models']);
				$role['plugins'] = explode(',',$role['plugins']);
				file_put_contents(FCPATH.'settings/acl/role_'.$role['id'].EXT, $this->_arrayeval("setting['current_role']",$role));
			}
		}
		
		function update_site_cache()
		{
			$data = $this->db->get('dili_site_settings')->row_array();
			file_put_contents(FCPATH.'settings/site'.EXT, $this->_arrayeval("setting",$data));	
		}
		
		function update_backend_cache()
		{
			$data = $this->db->get('dili_backend_settings')->row_array();
			file_put_contents(FCPATH.'settings/backend'.EXT, $this->_arrayeval("setting",$data));	
		}
		
		
		function update_plugin_cache()
		{
			$cached_plugins = $model_plugins = $result_plugins = array();
			$plugins = $this->db->select('name,access')->where('active','1')->get('dili_plugins')->result_array();
			if($plugins)
			{
				foreach($plugins as $key => $plugin)
				{
					if(file_exists(FCPATH.'plugins/'.$plugin['name'].'/'.'plugin_'.$plugin['name'].EXT))
					{
						$result_plugins[$plugin['name']] = $plugin;
					}
					if(file_exists(FCPATH.'plugins/'.$plugin['name'].'/'.'plugin_model_'.$plugin['name'].EXT))
					{
						$model_plugins[$plugin['name']] = $plugin;
					}
				}
			}
			$cached_plugins['plugins'] = $result_plugins;
			$cached_plugins['model_plugins'] = $model_plugins;
			file_put_contents(FCPATH.'settings/plugins'.EXT, $this->_arrayeval("setting['active_plugins']",$cached_plugins));
		}
			
	}