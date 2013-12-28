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
 * DiliCMS 缓存操作模型
 *
 * @package     DiliCMS
 * @subpackage  Models
 * @category    Models
 * @author      Jeongee
 * @link        http://www.dilicms.com
 */
class Cache_mdl extends CI_Model
{
	/**
     * 构造函数
     *
     * @access  public
     * @return  void
     */
	public function __construct()
	{
		parent::__construct();	
	}

	// ------------------------------------------------------------------------

    /**
     * 判断是否存在对应文件夹，若不存在则创建
     *
     * 仅对本地环境有效
     *
     * @access  private
     * @param   string
     * @return  void
     */
    private function _create_folder($folder = '')
    {
    	if ($this->platform->get_type() == 'default')
    	{
    		if ( ! file_exists(DILICMS_SHARE_PATH . 'settings/' . $folder))
	    	{
	    		mkdir(DILICMS_SHARE_PATH . 'settings/' . $folder);
	    	}
    	}
    	
    }
	
	// ------------------------------------------------------------------------

    /**
     * 更新内容模型缓存
     *
     * @access  public
     * @param   string
     * @return  void
     */
	public function update_model_cache($target = '') 
	{
		$data = array();
		if ($target)
		{
			$target = is_array($target) ? $target : array($target);
			$this->db->where_in('name', $target);	
		}
		$models = $this->db->get($this->db->dbprefix('models'))->result_array();
		foreach ($models as $model)
		{
			$model['fields'] = array();
			$model['fields_org'] = $this->db->where('model', $model['id'])
											->order_by('`order`', 'ASC')
											->get($this->db->dbprefix('model_fields'))
											->result_array();
			$model['listable'] = array();
			$model['searchable'] = array();
			foreach ($model['fields_org'] as $key=> & $v)
			{
				if ($v['listable'] == 1)
				{
					array_push($model['listable'], $v['id']);
				}
				if ($v['searchable'] == 1)
				{
					array_push($model['searchable'], $v['id']);
				}
				if (in_array($v['type'], array('select', 'checkbox', 'radio')))
				{
					if ($v['values'] == '')
					{
						$v['values'] = array();
					}
					else
					{
						$value = array();
						foreach (explode('|', $v['values']) as $vt)
						{
							if (strpos($vt, '=') > -1)
							{
								$vt = explode('=', $vt);
								$value[$vt[0]] = $vt[1];
							}
							else
							{
								$value[$vt] = $vt;
							}
						}
						$v['values'] = $value;
					}
				}
				$model['fields'][$v['id']] = $v;
			}
			unset($model['fields_org']);
			$this->_create_folder('model');
			$this->platform->cache_write(DILICMS_SHARE_PATH . 'settings/model/'.$model['name'].'.php',
										 array_to_cache("setting['models']['" . $model['name'] . "']", $model)
										 );
		}
	}
	
	// ------------------------------------------------------------------------

    /**
     * 更新分类模型缓存
     *
     * @access  public
     * @param   string
     * @return  void
     */
	public function update_category_cache($target = '')
	{
		$this->load->model('category_mdl');
		$data = array();
		if ($target)
		{
			$target = is_array($target) ? $target : array($target);
			$this->db->where_in('name', $target);	
		}
		$models = $this->db->get($this->db->dbprefix('cate_models'))->result_array();
		foreach ($models as $model)
		{
			$model['fields'] = array();
			$model['fields_org'] = $this->db->where('model', $model['id'])
											->order_by('`order`', 'ASC')
											->get($this->db->dbprefix('cate_fields'))
											->result_array();
			$model['listable'] = array();
			$model['searchable'] = array();
			foreach ($model['fields_org'] as $key=> & $v)
			{
				if ($v['listable'] == 1)
				{
					array_push($model['listable'], $v['id']);
				}
				if ($v['searchable'] == 1)
				{
					array_push($model['searchable'], $v['id']);
				}
				if (in_array($v['type'], array('select', 'checkbox', 'radio')))
				{
					if ($v['values'] == '')
					{
						$v['values'] = array();
					}
					else
					{
						$value = array();
						foreach (explode('|', $v['values']) as $vt)
						{
							if (strpos($vt,'=') > -1)
							{
								$vt = explode('=', $vt);
								$value[$vt[0]] = $vt[1];
							}
							else
							{
								$value[$vt] = $vt;
							}
						}
						$v['values'] = $value;
					}
				}
				$model['fields'][$v['id']] = $v;
			}
			unset($model['fields_org']);
			$this->_create_folder('category');
			$this->platform->cache_write(DILICMS_SHARE_PATH . 'settings/category/cate_' . $model['name'] . '.php', 
										 array_to_cache("setting['cate_models']['" . $model['name'] . "']", $model));
			$category = array();
			$categories =  $this->category_mdl->get_category($model['name']);
			foreach($categories as $c)
			{
				$category[$c['classid']] = $c;	
			}
			$this->platform->cache_write(DILICMS_SHARE_PATH . 'settings/category/data_' . $model['name'] . '.php', 
										 array_to_cache("setting['category']['" . $model['name'] . "']", $category));
			unset($categories,$category);
		}
	}
	
	// ------------------------------------------------------------------------

    /**
     * 更新菜单缓存
     *
     * @access  public
     * @return  void
     */
	public function update_menu_cache()
	{
		$table_menu = $this->db->dbprefix('menus');
		$level_1_menus = $this->db->select('menu_id, class_name, method_name, menu_name')
								  ->where('menu_level', 0) 
								  ->where('menu_parent', 0)
								  ->get($table_menu)
								  ->result_array();
		foreach ($level_1_menus as & $i)
		{
			$level_2_menus = $this->db->select('menu_id, class_name, method_name, menu_name')
									  ->where('menu_level', 1)
									  ->where('menu_parent', $i['menu_id'])
									  ->get($table_menu)
									  ->result_array();
			foreach ($level_2_menus as & $j)
			{
				if ($j['class_name'] == 'content')
				{
					$level_3_menus = $this->db
										  ->select(" 'content' AS class_name, 'view' AS 'method_name', name AS extra, description AS menu_name", FALSE)
										  ->get($this->db->dbprefix('models'))
										  ->result_array();
				}
				else if ($j['class_name'] == 'category_content')
				{
					$level_3_menus = $this->db
					                      ->select(" 'category_content' AS class_name, 'view' AS 'method_name', name AS extra, description AS menu_name", FALSE)
										  ->get($this->db->dbprefix('cate_models'))
										  ->result_array();
				}
				else
				{
					$level_3_menus = $this->db->select('menu_id, class_name, method_name, menu_name')
											  ->where('menu_level', 2)
											  ->where('menu_parent', $j['menu_id'])
											  ->get($table_menu)
											  ->result_array();
				}
				$j['sub_menus'] = $level_3_menus;
			}
			$i['sub_menus'] = $level_2_menus;
		}
		$this->platform->cache_write(DILICMS_SHARE_PATH . 'settings/menus.php', 
									 array_to_cache("setting['menus']", $level_1_menus));
	}
	
	// ------------------------------------------------------------------------

    /**
     * 更新用户组缓存
     *
     * @access  public
     * @param   string
     * @return  void
     */
	public function update_role_cache($target = '')
	{
		if ($target)
		{
			$target = is_array($target) ? $target : array($target);
			$this->db->where_in('id', $target);
		}
		$roles = $this->db->get($this->db->dbprefix('roles'))->result_array();
		foreach ($roles as & $role)
		{	
			$role['rights'] = explode(',', $role['rights']);
			$rights = $this->db->select('right_class, right_method, right_detail')
							   ->where_in('right_id', $role['rights'])
							   ->get($this->db->dbprefix('rights'))
							   ->result();
			$role['rights'] = array();
			foreach ($rights as $right)
			{
				$role['rights'][] = $right->right_class . '@' . $right->right_method . ($right->right_detail ? '@' . $right->right_detail : ''); 	
			}
			$role['models'] = explode(',', $role['models']);
			$role['category_models'] = explode(',', $role['category_models']);
			$role['plugins'] = explode(',', $role['plugins']);
			$this->_create_folder('acl');
			$this->platform->cache_write(DILICMS_SHARE_PATH . 'settings/acl/role_' . $role['id'] . '.php', 
										 array_to_cache("setting['current_role']",$role));
		}
	}
	
	// ------------------------------------------------------------------------

    /**
     * 更新站点信息缓存
     *
     * @access  public
     * @return  void
     */
	public function update_site_cache()
	{
		$data = $this->db->get($this->db->dbprefix('site_settings'))->row_array();
		$this->platform->cache_write(DILICMS_SHARE_PATH . 'settings/site.php', 
									 array_to_cache("setting", $data));	
	}
	
	// ------------------------------------------------------------------------

    /**
     * 更新后台设置缓存
     *
     * @access  public
     * @return  void
     */
	public function update_backend_cache()
	{
		$data = $this->db->get($this->db->dbprefix('backend_settings'))->row_array();
		$this->platform->cache_write(DILICMS_SHARE_PATH . 'settings/backend.php', 
									 array_to_cache("setting", $data));	
	}
	
	// ------------------------------------------------------------------------

    /**
     * 更新插件信息缓存
     *
     * @access  public
     * @return  void
     */
	public function update_plugin_cache()
	{
		$cached_plugins = array();
		$plugins = $this->db->select('name, access')
							->where('active', '1')
							->get($this->db->dbprefix('plugins'))
							->result_array();
		if ($plugins)
	    {
			foreach ($plugins as $key => $plugin)
			{
		        if (! isset($cached_plugins[$plugin['name']])) {
		            $cached_plugins[$plugin['name']] = array(
		                'classmap' => array(),
		                'menus' => array(),
		                'access' => $plugin['access']
		            );
		        }	    
			    
			    $this_plugin_path = DILICMS_EXTENSION_PATH.'plugins/'.$plugin['name'].'/';
			    
			    $this_hook_path = 'plugins/'.$plugin['name'].'/hooks/';
			    
			    if (file_exists($this_plugin_path.'hooks')) {
                    foreach (glob($this_plugin_path.'hooks/'.$plugin['name']."_hook_*.php") as $filename) {
                        $filename = basename($filename);
                        $model = str_replace(array('.php', $plugin['name']."_hook_"), array('', ''), $filename);
                        $cached_plugins[$plugin['name']]['classmap'][$model] =  $this_hook_path.$filename;
                    }
			    }
			    
			    if (file_exists($this_plugin_path.'menus.php')) {
			        $this_plugin_menus = include ($this_plugin_path.'menus.php');
			        $cached_plugins[$plugin['name']]['menus'] = $this_plugin_menus;
			    }
			    
			    
			}
		}

		$this->platform->cache_write(DILICMS_SHARE_PATH . 'settings/plugins.php', 
									 array_to_cache("setting['plugins']", $cached_plugins));
									 
		
	}
	
	// ------------------------------------------------------------------------

    /**
     * 更新字段类型缓存
     *
     * @access  public
     * @return  void
     */
	public function update_fieldtypes_cache()
	{
		$cached_fieldtypes = array();
		$cached_fieldtypes['fieldtypes'] = array();
		$cached_fieldtypes['extra_fieldtypes'] = array();
		$cached_fieldtypes['validation'] = array();
		$results = $this->db->get($this->db->dbprefix('fieldtypes'))->result_array();
		foreach ($results as $v)
		{
			$cached_fieldtypes['fieldtypes'][$v['k']] = $v['v'];
		}
		$results = $this->db->get($this->db->dbprefix('validations'))->result_array();
		foreach ($results as $v)
		{
			$cached_fieldtypes['validation'][$v['k']] = $v['v'];
		}
		$extra_path = DILICMS_EXTENSION_PATH . 'fields/';
		$this->load->helper('file');
		$extra_files = get_filenames($extra_path);
		foreach ($extra_files as $v)
		{
			if (preg_match("/^field_(.*?)\.php$/", $v))
			{
				include $extra_path . $v;
				if (class_exists($extra_class = str_replace('.php', '', $v)))
				{
					$tmp = new $extra_class();
					$cached_fieldtypes['extra_fieldtypes'][$tmp->k] = $tmp->v;
				}
			}
		}
		$this->platform->cache_write(DILICMS_SHARE_PATH . 'settings/fieldtypes.php', 
									 array_to_cache("setting",$cached_fieldtypes));
	}
	
	// ------------------------------------------------------------------------
	
}

/* End of file cache_mdl.php */
/* Location: ./shared/models/cache_mdl.php */