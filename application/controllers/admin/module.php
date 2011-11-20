<?php if ( ! defined('IN_DiliCMS')) exit('No direct script access allowed');

	class Module extends Admin_Controller
	{
		function __construct()
		{
			parent::__construct();
			$this->acl->_detect_plugin_menus();
		}
		
		function run()
		{
			$this->_run_post();
		}
		
		function _run_post()
		{
			
			$plugin = $this->input->get('plugin');
			if(!$plugin && $this->acl->_default_link){redirect($this->acl->_default_link);}
			$this->_check_permit();
			$action = $this->input->get('action');
			if(isset($this->plugin_manager->active_plugins[$plugin]['instance']) && 
			   in_array(strtolower($action), array_map('strtolower', get_class_methods('plugin_'.$plugin)))
			 )
			{
					$data['content'] = $this->plugin_manager->active_plugins[$plugin]['instance']->$action();
					$this->_template('',$data);
			}
			else
			{
				$this->_message('未定义的操作!','',false);	
			}
		}

	}