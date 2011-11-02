<?php

	class Setting extends Admin_Controller
	{
		function __construct()
		{
			parent::__construct();
			$this->_check_permit();
		}
		
		function site()
		{
			$data['site'] = $this->db->get('dili_site_settings')->row();
			$this->_template('settings_site',$data);
		}
		
		function _site_post()
		{
			$this->db->update('dili_site_settings',$this->input->post());
			update_cache('site');
			$this->_message("更新成功",'setting/site',true,($this->input->get('tab') ? '?tab='.$this->input->get('tab') : '' ));
		}
		
		function backend()
		{
			$data['backend'] = $this->db->get('dili_backend_settings')->row();
			$this->_template('settings_backend',$data);
		}
		
		function _backend_post()
		{
			$this->db->update('dili_backend_settings',$this->input->post());
			update_cache('backend');
			$this->_message("更新成功",'setting/backend',true,($this->input->get('tab') ? '?tab='.$this->input->get('tab') : '' ));
		}
			
	}