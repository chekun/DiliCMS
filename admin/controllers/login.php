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
 * DiliCMS 用户登录/退出控制器
 *
 * @package     DiliCMS
 * @subpackage  Controllers
 * @category    Controllers
 * @author      Jeongee
 * @link        http://www.dilicms.com
 */
class Login extends CI_Controller
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
		$this->load->library('session');
		$this->settings->load('backend');
		$this->load->switch_theme(setting('backend_theme'));
	}
	
	// ------------------------------------------------------------------------

    /**
     * 默认入口
     *
     * @access  public
     * @return  void
     */	
	public function index()
	{
		if ($this->session->userdata('uid'))
		{
			redirect(setting('backend_access_point') . '/system/home');
		}
		else
		{
			$this->load->view('sys_login');	
		}
	}
	
	// ------------------------------------------------------------------------

    /**
     * 退出
     *
     * @access  public
     * @return  void
     */	
	public function quit()
	{
		$this->session->sess_destroy();
		redirect(setting('backend_access_point') . '/login');
	}
	
	// ------------------------------------------------------------------------

    /**
     * 用户登录验证
     *
     * @access  public
     * @return  void
     */	
	public function _do_post()
	{
		$username = $this->input->post('username', TRUE);
		$password = $this->input->post('password', TRUE);
		
		if ($username AND $password)
		{
			$admin = $this->user_mdl->get_full_user_by_username($username);
			if ($admin)
			{
				if ($admin->password == md5($password))
				{
					if ($admin->role == 1 AND ! setting('backend_root_access'))
					{
						$this->session->set_flashdata('error', "系统限制了ROOT用户登录,请联系管理员!");
					}
					else
					{
						if ($admin->status == 1)
						{
							$this->session->set_userdata('uid', $admin->uid);
							redirect(setting('backend_access_point') . '/system/home');
						}
						else
						{
							$this->session->set_flashdata('error', "此帐号已被冻结,请联系管理员!");
						}
						
					}
				}
				else
				{
					$this->session->set_flashdata('error', "密码不正确!");
				}
			}
			else
			{
				$this->session->set_flashdata('error', '不存在的用户!');
			}
		}
		else
		{
			$this->session->set_flashdata('error', '用户名和密码不能为空!');
		}
		redirect(setting('backend_access_point') . '/login');
	}

	// ------------------------------------------------------------------------
	
}

/* End of file login.php */
/* Location: ./admin/controllers/login.php */