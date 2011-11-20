<?php if ( ! defined('IN_DiliCMS')) exit('No direct script access allowed');

	class System extends Admin_Controller
	{
		function __construct()
		{
			parent::__construct();
		}
		
		function home()
		{
			$this->_template('sys_default');
		}
				
		function password()
		{
			$this->_check_permit();
			$this->_password_post();
		}
		
		function _password_post()
		{
			$this->_check_permit();
			$this->load->library('form_validation');
			$this->form_validation->set_rules('old_pass', "旧密码" , 'required');
			$this->form_validation->set_rules('new_pass', "新密码" , 'required|min_length[6]|max_length[16]|match[new_pass_confirm]');
			$this->form_validation->set_rules('new_pass_confirm', "确认新密码" , 'required|min_length[6]|max_length[16]');
			if ($this->form_validation->run() == FALSE)
  			{
   				$this->_template('sys_password');
  			}
			else
			{
				$this->load->model('dili/user_mdl');
				$old_pass = md5($this->input->post('old_pass'));
				$stored = $this->user_mdl->get_user_by_uid($this->session->userdata('uid'));
				if($stored && $old_pass == $stored->password)
				{
					$this->user_mdl->update_user_password();
					$this->_message("密码更新成功!",'',true);	
				}
				else
				{
					$this->_message("密码验证失败!",'',true);
				}
			}
		}
		
		function cache()
		{
			$this->_check_permit();
			$this->_template('sys_cache');
		}
		
		function _cache_post()
		{
			$this->_check_permit();
			$cache = $this->input->post('cache');
			if( $cache && is_array($cache)  )
			{
				update_cache($cache);
			}
			$this->_message("缓存更新成功！",'',true);	
		}
		
		
	}