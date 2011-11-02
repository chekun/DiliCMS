<?php

	class Login extends Dili_Controller
	{
		function __construct()
		{
			parent::__construct();
			$this->settings->load('backend');
			$this->load->library('session');
			$this->_theme_switcher('on',setting('backend_theme'),'admincp/');
			$this->load->database();
			$this->load->model('dili/user_mdl');
		}
		
		function index()
		{
			if( $this->session->userdata('uid') )
			{
				redirect(setting('backend_access_point').'/system/home');
			}
			else
			{
				$this->load->view('sys_login');	
			}
		}
		
		function quit()
		{
			$this->session->sess_destroy();
			redirect(setting('backend_access_point').'/login');
		}
		
		function _do_post()
		{
			$username = $this->input->post('username',true);
			$password = $this->input->post('password',true);
			
			if($username && $password)
			{
				$admin = $this->user_mdl->get_full_user_by_username( $username );
				if($admin)
				{
					if( $admin->password == md5($password) )
					{
						if($admin->role == 1 && !setting('backend_root_access'))
						{
							$this->session->set_flashdata('error', "系统限制了ROOT用户登录,请联系管理员!");
							redirect(setting('backend_access_point').'/login');	
						}
						else
						{
							$this->session->set_userdata('uid',$admin->uid);
							redirect(setting('backend_access_point').'/system/home');
						}
					}
					else
					{
						$this->session->set_flashdata('error', "密码不正确!");
						redirect(setting('backend_access_point').'/login');
					}
				}
				else
				{
					$this->session->set_flashdata('error', '不存在的用户!');
					redirect(setting('backend_access_point').'/login');	
				}
			}
			else
			{
				$this->session->set_flashdata('error', '用户名和密码不能为空!');
				redirect(setting('backend_access_point').'/login');
			}
			
		}
		
	}