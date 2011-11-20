<?php  if ( ! defined('IN_DiliCMS')) exit('No direct script access allowed');
	
	class Plugin_manager
	{
		var $active_plugins = array();
		var $active_model_plugins = array();
		var $ci = NULL;
		
		function __construct()
		{
			$this->ci = &get_instance();
			$this->_init();
		}
		
		private function _init()
		{
			if(file_exists(FCPATH.'settings/plugins'.EXT))
			{
				include_once(FCPATH.'settings/plugins'.EXT);
				if(isset($setting['active_plugins']))
				{
					$this->active_plugins = $setting['active_plugins']['plugins'];
					$this->active_model_plugins = $setting['active_plugins']['model_plugins'];
					$this->_load_plugins($this->active_plugins);
					unset($setting['active_plugins']);
				}
			}
			if($this->ci->uri->rsegment(1) == 'category_content' || $this->ci->uri->rsegment(1) == 'content')
			{
				$this->_load_plugins($this->active_model_plugins , 'model_');
			}
		}
		
		private function _load_plugins( & $plugins , $name_fix = '')
		{
			foreach($plugins as $key => &$plugin)
			{
				if($plugin['access'] == 1 && $this->ci->_admin->role != 1)
				{
					unset($plugins[$key]);
					continue;	
				}
				if($this->ci->_admin->role != 1 && !in_array('module@run',$this->ci->acl->_rights['rights']) )
				{
					unset($plugins[$key]);
					continue;	
				}
				if($this->ci->_admin->role != 1 && !in_array($plugin['name'],$this->ci->acl->_rights['plugins']))
				{
					unset($plugins[$key]);
					continue;		
				}
				if(!file_exists(FCPATH.'plugins/'.$plugin['name'].'/'.'plugin_'.$name_fix.$plugin['name'].EXT))
				{
					unset($plugins[$key]);	
				}
				else
				{
					$plugin_class = 'plugin_'.$name_fix.$plugin['name'];
					include FCPATH.'plugins/'.$plugin['name'].'/'.'plugin_'.$name_fix.$plugin['name'].EXT;
					if(class_exists($plugin_class))
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
				
		function trigger_navigation()
		{
			foreach($this->active_plugins as $plugin)
			{
				$plugin['instance']->register_navigation();
			}
		}
		
		function trigger_left_menu()
		{
			$left_menus = array();
			foreach($this->active_plugins as $plugin)
			{
				$left_menu = $plugin['instance']->register_left_menu();
				if( $left_menu )
				{
					$left_menus[] = $left_menu;
				}	
			}
			return $left_menus;	
		}
		
		function trigger_operation()
		{
			foreach($this->active_model_plugins as $plugin)
			{
				$plugin['instance']->register_operation();
			}	
		}
		
		function trigger_attachment($file)
		{
			foreach($this->active_plugins as $plugin)
			{
				$plugin['instance']->register_attachment($file);
			}	
		}
		
		function trigger_model_action($name = '' , & $arg1 = '' , & $arg2 = '')
		{
			if(!$name){return;}
			foreach($this->active_model_plugins as $plugin)
			{
				call_user_func_array(array(& $plugin['instance'], $name), array(& $arg1, & $arg2));
			}
		}
				
	}
	
	abstract class Dili_basic_plugin
	{
		var $_name = '';
		var $_ci = NULL;
		var $_path = '';
		
		function __construct($name)
		{
			$this->_name = $name;
			$this->_ci = &get_instance();
			$this->_path = FCPATH.'plugins/'.$this->_name.'/';
		}
		
		function _url($action , $qs = '')
		{
			return backend_url('module/run','plugin='.$this->_name.'&action='.$action).$qs;	
		}
		
		function _check($type = '' , $model = '')
        {
            return $this->_ci->uri->segment(2) == $type && $model == $this->_ci->input->get('model');
        } 
		
		function _template($view , $data = array() , $output = true)
		{
			extract($data);
			ob_start();
			eval('?>'.file_get_contents($this->_path.$view.EXT));
			$content = ob_get_contents();
			ob_end_clean();
			if($output == true)
			{
				echo $content;
			}	
			else
			{
				return $content;
			}
		}
			
	}
		
	//DiliCMS MODEL插件接口
	abstract class Dili_model_plugin extends Dili_basic_plugin
	{	
		function __construct($name){parent::__construct($name);}
		//注册操作栏
		function register_operation(){}
		//注册模型信息插入前操作
		function register_before_insert(){}//& $data
		//注册模型信息插入后操作
		function register_after_insert(){}//& $data ,$id
		//注册模型信息修改前操作
		function register_before_update(){}//& $data ,$id		
		//注册模型信息修改后操作
		function register_after_update(){}//& $data ,$id
		//注册模型信息删除前操作
		function register_before_delete(){}//$ids
		//注册模型信息删除后操作
		function register_after_delete(){}//$ids
		//注册模型信息添加修改页面视图
		function register_view(){}//& $content
		//注册模型信息列表QUERY之前
		function register_before_query(){}//&$where
		//注册模型信息列表数据二次处理
		function register_before_list(){}//& $list
		//注册模型信息列表显示页面
		function register_list_view(){}//& $list
		//注册模型信息列表操作栏
		function register_list_operation_view(){}// &$data
		//注册模型信息进入列表信息动作
		function register_on_reach_model_list(){}//
	}
	
	//DiliCMS EXTENDED 插件接口
	abstract class Dili_plugin extends Dili_basic_plugin
	{
		function __construct($name){parent::__construct($name);}
		//注册快速导航栏按钮
		function register_navigation(){}
		//注册左边栏菜单
		function register_left_menu(){}
		/*return   array( 'menu_name' => 'Hello World 插件',
							'sub_menus' => array(
												  0=>array('class_name'=>$this->_name,'method_name'=>'welcome','menu_name'=>'测试左菜单')
												)
						  );*/
		//注册快速导航栏按钮
		function register_attachment(){}//参数为路径
	}
	
	
	
