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
 * DiliCMS 插件经理类
 *
 * @package     DiliCMS
 * @subpackage  Libraries
 * @category    Libraries
 * @author      Jeongee
 * @link        http://www.dilicms.com
 */	
class Plugin_manager
{
	/**
     * active_plugins
     * 已经激活模块插件的集合
     *
     * @var array
     * @access  public
     **/
	public $active_plugins = array();

	/**
     * 
     * 已经激活模型插件的集合
     *
     * @var array
     * @access  public
     **/
	public $active_model_plugins = array();
	
	/**
     * ci
     * CI超级类的句柄
     *
     * @var object
     * @access  private
     **/
	private $ci = NULL;
	
	/**
     * 构造函数
     *
     * @access  public
     * @return  void
     */
	public function __construct()
	{
		$this->ci = & get_instance();
		$this->_init();
	}

	// ------------------------------------------------------------------------

    /**
     * 初始化
     *
     * @access  private
     * @return  void
     */
	private function _init()
	{
		if ($this->ci->platform->cache_exists(DILICMS_SHARE_PATH . 'settings/plugins.php'))
		{
			eval('?>' . $this->ci->platform->cache_read(DILICMS_SHARE_PATH . 'settings/plugins.php'));
			if (isset($setting['active_plugins']))
			{
				$this->active_plugins = $setting['active_plugins']['plugins'];
				$this->active_model_plugins = $setting['active_plugins']['model_plugins'];
				$this->_load_plugins($this->active_plugins);
				unset($setting['active_plugins']);
			}
		}
		if ($this->ci->uri->rsegment(1) == 'category_content' || $this->ci->uri->rsegment(1) == 'content')
		{
			$this->_load_plugins($this->active_model_plugins, 'model_');
		}
	}
	
	// ------------------------------------------------------------------------

    /**
     * 加载插件
     *
     * @access  private
     * @param   array
     * @param   string
     * @return  void
     */
	private function _load_plugins( & $plugins, $name_fix = '')
	{
		foreach ($plugins as $key => & $plugin)
		{
			if ($plugin['access'] == 1 && $this->ci->_admin->role != 1)
			{
				unset($plugins[$key]);
				continue;	
			}
			if ($this->ci->_admin->role != 1 && ! in_array('module@run', $this->ci->acl->rights['rights']) )
			{
				unset($plugins[$key]);
				continue;	
			}
			if ($this->ci->_admin->role != 1 && ! in_array($plugin['name'], $this->ci->acl->rights['plugins']))
			{
				unset($plugins[$key]);
				continue;		
			}
			if ( ! file_exists(DILICMS_EXTENSION_PATH . 'plugins/' . $plugin['name'] . '/' . 'plugin_' . $name_fix . $plugin['name'] . '.php'))
			{
				unset($plugins[$key]);
			}
			else
			{
				$plugin_class = 'plugin_' . $name_fix . $plugin['name'];
				include DILICMS_EXTENSION_PATH . 'plugins/' . $plugin['name'] . '/' . 'plugin_' . $name_fix . $plugin['name'] . '.php';
				if (class_exists($plugin_class))
				{
					$plugin['instance'] = new $plugin_class($plugin['name']);
				}
				else
				{
					unset($plugins[$key]);
				}
			}
		}
	}
	
	// ------------------------------------------------------------------------

    /**
     * 导航触发钩子
     *
     * @access  public
     * @return  void
     */
	public function trigger_navigation()
	{
		foreach ($this->active_plugins as $plugin)
		{
			$plugin['instance']->register_navigation();
		}
	}
	
	// ------------------------------------------------------------------------

    /**
     * 菜单触发钩子
     *
     * @access  public
     * @return  void
     */
	public function trigger_left_menu()
	{
		$left_menus = array();
		foreach ($this->active_plugins as $plugin)
		{
			$left_menu = $plugin['instance']->register_left_menu();
			if( $left_menu )
			{
				$left_menus[] = $left_menu;
			}	
		}
		return $left_menus;	
	}
	
	// ------------------------------------------------------------------------

    /**
     * 附件处理钩子触发
     *
     * @access  public
     * @param   string
     * @return  void
     */
	public function trigger_attachment($file)
	{
		foreach ($this->active_plugins as $plugin)
		{
			$plugin['instance']->register_attachment($file);
		}	
	}
	
	// ------------------------------------------------------------------------

    /**
     * 模型插件系列钩子触发
     *
     * @access  public
     * @param   string
     * @return  void/false
     */
	public function trigger_model_action($name = '' , & $arg1 = '' , & $arg2 = '')
	{
		if ( ! $name)
		{
			return FALSE;
		}
		foreach ($this->active_model_plugins as $plugin)
		{
			call_user_func_array(array(& $plugin['instance'], $name), array(& $arg1, & $arg2));
		}
	}
			
}

// ------------------------------------------------------------------------

/**
 * DiliCMS 插件基类
 *
 * @package     DiliCMS
 * @subpackage  Libraries
 * @category    Libraries
 * @author      Jeongee
 * @link        http://www.dilicms.com
 */	
abstract class Dili_basic_plugin
{
	protected $_name = '';
	protected $_ci = NULL;
	protected $_path = '';
	
	public function __construct($name)
	{
		$this->_name = $name;
		$this->_ci = & get_instance();
		$this->_path = DILICMS_EXTENSION_PATH . 'plugins/' . $this->_name . '/';
	}
	
	protected function _url($action, $qs = '')
	{
		return backend_url('module/run','plugin='.$this->_name.'&action='.$action).$qs;	
	}
	
	protected function _check($type = '' , $model = '')
    {
        return $this->_ci->uri->rsegment(1) == $type && $model == $this->_ci->input->get('model');
    } 
	
	protected function _template($view , $data = array() , $output = true)
	{
		extract($data);
		ob_start();
		eval('?>' . file_get_contents($this->_path . $view . '.php'));
		$content = ob_get_contents();
		ob_end_clean();
		if ($output == TRUE)
		{
			echo $content;
		}	
		else
		{
			return $content;
		}
	}
		
}
	
// ------------------------------------------------------------------------

/**
 * DiliCMS 模型插件基类
 *
 * @package     DiliCMS
 * @subpackage  Libraries
 * @category    Libraries
 * @author      Jeongee
 * @link        http://www.dilicms.com
 */	
abstract class Dili_model_plugin extends Dili_basic_plugin
{	
	public function __construct($name)
	{
		parent::__construct($name);
	}
	//注册操作栏
	public function register_operation(){}
	//注册模型信息插入前操作
	public function register_before_insert(){}//& $data
	//注册模型信息插入后操作
	public function register_after_insert(){}//& $data ,$id
	//注册模型信息修改前操作
	public function register_before_update(){}//& $data ,$id		
	//注册模型信息修改后操作
	public function register_after_update(){}//& $data ,$id
	//注册模型信息删除前操作
	public function register_before_delete(){}//$ids
	//注册模型信息删除后操作
	public function register_after_delete(){}//$ids
	//注册模型信息添加修改页面视图
	public function register_view(){}//& $content
	//注册模型信息列表QUERY之前
	public function register_before_query(){}//&$where
	//注册模型信息列表数据二次处理
	public function register_before_list(){}//& $list
	//注册模型信息列表显示页面
	public function register_list_view(){}//& $list
	//注册模型信息列表操作栏
	public function register_list_operation_view(){}// &$data
	//注册模型信息进入列表信息动作
	public function register_on_reach_model_list(){}//
}

// ------------------------------------------------------------------------

/**
 * DiliCMS 模块插件基类
 *
 * @package     DiliCMS
 * @subpackage  Libraries
 * @category    Libraries
 * @author      Jeongee
 * @link        http://www.dilicms.com
 */	
abstract class Dili_plugin extends Dili_basic_plugin
{
	public function __construct($name)
	{
		parent::__construct($name);
	}
	//注册快速导航栏按钮
	public function register_navigation(){}
	//注册左边栏菜单
	public function register_left_menu(){}
	/*return   array( 'menu_name' => 'Hello World 插件',
						'sub_menus' => array(
											  0=>array('class_name'=>$this->_name,'method_name'=>'welcome','menu_name'=>'测试左菜单')
											)
					  );*/
	//注册快速导航栏按钮
	public function register_attachment(){}//参数为路径
}
	
/* End of file Plugin_manager.php */
/* Location: ./shared/libraries/Plugin_manager.php */	