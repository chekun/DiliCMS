<?php if ( ! defined('IN_DiliCMS')) exit('No direct script access allowed');
	
	class Acl 
	{
		var $ci;
		var $top_menus = array();
		var $left_menus = array();
		var $_current_menu = -1;
		var $_default_link = '';
		var $_rights = array();
		function __construct()
		{
			$this->ci = &get_instance();
			$this->ci->settings->load('menus');//加载菜单数据
			$this->top_menus = & setting('menus');
			if($this->ci->_admin->role != 1)
			{
				$this->ci->settings->load('acl/role_'.$this->ci->_admin->role.EXT);//加载权限数据
				$this->top_menus = & setting('menus');
				$this->_rights = & setting('current_role');
			}
			$this->_filter_menus();
		}
		
		function show_top_menus()
		{
			foreach($this->top_menus as $key=>$v)    
			{
					echo '<li class="'. ($key == 0 ? 'first' : ($key == 3 ? 'last' : '')) .' '.($key == $this->_current_menu ? 'selected' : '').'">
					  <a href="'.backend_url($v['class_name'].'/'.$v['method_name']).'">'.$v['menu_name'].'</a>
					  </li>';
			}
		}
		
		function show_left_menus()
		{
			foreach($this->left_menus as $v)
			{
				if($v['sub_menus'])
				{
					echo '<li><span>'. $v['menu_name'] .'</span>
						     <ul name="menu">';
							 foreach($v['sub_menus'] as $j)
							 {
							   $extra = '';
							   $this->_current_menu ==  1 && $extra =  'model='.$j['extra'] ;
							   $this->_current_menu ==  2 && $extra = $j['extra'];
							   echo '<li class="'.(isset($j['current']) ? 'selected' : '').'"><a href="'.backend_url($j['class_name'].'/'.$j['method_name'],$extra).'">'.$j['menu_name'].'</a></li>';
							 }
					echo	 '</ul>
					      </li>';	
				}
			}
		}
		
		function _filter_menus()
		{
			$class_name = $this->ci->uri->rsegment(1);
			$method_name = $this->ci->uri->rsegment(2);
			switch($class_name)
			{
				case 'content' : 
				case 'category_content' : $this->_filter_content_menus($class_name,$method_name);break;
				case 'module' : $this->_filter_module_menus($class_name,$method_name);break;
				default : $this->_filter_normal_menus($class_name,$method_name);
			}
		}
		
		function _filter_normal_menus($class_name,$method_name)
		{//0
			$this->_current_menu = 0;
			$this->_default_link = backend_url('system/home');
			$this->left_menus = & $this->top_menus[$this->_current_menu]['sub_menus'];
			foreach($this->left_menus as $vkey => & $v)
			{
				foreach($v['sub_menus'] as $jkey => &$j)
				{
					if($j['class_name'] == $class_name && $j['method_name'] == $method_name)
					{
						$j['current'] = true;
					}
					if($this->ci->_admin->role == 1){continue;}
					$right = $j['class_name'].'@'.$j['method_name'];
					if(!in_array($right,$this->_rights['rights']) && $right !='system@home')
					{
						unset($this->left_menus[$vkey]['sub_menus'][$jkey]);	
					}
				} 
				if(!$v['sub_menus']){unset($this->left_menus[$vkey]);}  
			}
		}
		
		function _filter_content_menus($class_name,$method_name)
		{//1
			$this->_current_menu = 1;
			$this->left_menus = & $this->top_menus[$this->_current_menu]['sub_menus'];
			$extra = $this->ci->input->get('model');
			foreach($this->left_menus as $vkey => & $v)
			{
				foreach($v['sub_menus'] as $jkey => &$j)
				{
					if($j['class_name'] == $class_name && $j['method_name'] == $method_name && 
						( ($j['extra'] == $extra && $vkey == 0) || ($j['extra'] == $extra && $vkey == 1) ) )
					{
						$j['current'] = true;
					}
					
					if($this->ci->_admin->role == 1){continue;}
					$right = $j['class_name'].'@'.$j['method_name'];
					if(!in_array($right,$this->_rights['rights']) || 
					   (!in_array($j['extra'],$this->_rights['models']) && $vkey == 0) ||
					   (!in_array($j['extra'],$this->_rights['category_models']) && $vkey == 1) 		
					)
					{
						unset($this->left_menus[$vkey]['sub_menus'][$jkey]);
					}
				} 
				if(!$v['sub_menus']){unset($this->left_menus[$vkey]);}
			}
			//设定默认链接 
			if($_item = @reset($this->left_menus[0]['sub_menus']))
			{
			    if(!$this->_default_link)
				{
				    $this->_default_link = backend_url($_item['class_name'].'/view','model='.$_item['extra']);	
				}
			}
			
		}
		
		function _filter_module_menus($class_name,$method_name)
		{//2
			$this->_current_menu = 2;
		}
		
		function _detect_plugin_menus()
		{
			$this->top_menus[$this->_current_menu]['sub_menus'] = $this->ci->plugin_manager->trigger_left_menu();
			$this->left_menus = & $this->top_menus[$this->_current_menu]['sub_menus'];
			foreach($this->left_menus as $key=>&$v)
			{
				if(isset($v['sub_menus']) && $v['sub_menus'])
				{
					foreach($v['sub_menus'] as &$j)
					{
						$j['extra'] = 'plugin='.$j['class_name'].'&action='.$j['method_name'];
						if($j['class_name'] == $this->ci->input->get('plugin') && $j['method_name'] == $this->ci->input->get('action'))
						{
							$j['current'] = true;
						}
						$j['class_name'] = 'module';
						$j['method_name'] = 'run';
						if(!$this->_default_link)
						{
							$this->_default_link = backend_url('module/run',$j['extra']);
						}
						
					}
				}
				else
				{
					unset($this->left_menus[$key]);	
				}
			}
		}
		
		function permit($act = '')
		{
			if($this->ci->_admin->role == 1)
			{
				return true;	
			}
			$class_method = $this->ci->uri->rsegment(1).'@'.$this->ci->uri->rsegment(2).($act ? '@'.$act : '');
			if(!in_array($class_method,$this->_rights['rights']))
			{
				return false;	
			}
			if($this->ci->uri->rsegment(1) == 'content' )
			{
				if(!in_array($this->ci->input->get('model'),$this->_rights['models']))
				{
					return false; 
				}
			}
			else if($this->ci->uri->rsegment(1) == 'category_content')
			{
				if(!in_array($this->ci->input->get('model'),$this->_rights['category_models']))
				{
					return false; 
				}
			}
			else if($this->ci->uri->rsegment(1) == 'module')
			{
				if(!in_array($this->ci->input->get('plugin'),$this->_rights['plugins']))	
				{		
					return false;
				}
			}
			return true;
		}
		
	}
	
