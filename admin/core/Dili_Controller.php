<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
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
 * DiliCMS 后台控制器基类
 *
 * @package     DiliCMS
 * @subpackage  core
 * @category    core
 * @author      Jeongee
 * @link        http://www.dilicms.com
 */		
abstract class Admin_Controller extends CI_Controller
{
	/**
     * _admin
     * 保存当前登录用户的信息
     *
     * @var object
     * @access  public
     **/
	public $_admin = NULL;

	/**
     * 构造函数
     *
     * @access  public
     * @return  void
     */
	public function __construct()
	{
		parent::__construct();
        $this->load->database();
		$this->load->library('session');
		$this->settings->load('backend');
		$this->load->switch_theme(setting('backend_theme'));
        $this->_check_http_auth();
		$this->_check_login();
		$this->load->library('acl');
		$this->load->library('plugin_manager');
	}

    // ------------------------------------------------------------------------

    /**
     * 检查http auth
     *
     * @access  protected
     * @return  void
     */
    protected function _check_http_auth()
    {
        if (setting('backend_http_auth_on'))
        {
            $user = $this->input->server('PHP_AUTH_USER');
            $passwword = $this->input->server('PHP_AUTH_PW');
            if (! $user or ! $passwword or $user != setting('backend_http_auth_user') or $passwword != setting('backend_http_auth_password')) {
                header('WWW-Authenticate: Basic realm="Welcome to this Private DiliCMS Realm!"');
                header('HTTP/1.0 401 Unauthorized');
                echo '您没有权限访问这里.';
                exit;
            }
        }
    }
		
	// ------------------------------------------------------------------------

    /**
     * 检查用户是否登录
     *
     * @access  protected
     * @return  void
     */
	protected function _check_login()
	{
		if ( ! $this->session->userdata('uid'))
		{   
			redirect(setting('backend_access_point') . '/login');
		}
		else
		{
			$this->_admin = $this->user_mdl->get_full_user_by_username($this->session->userdata('uid'), 'uid');
			if ($this->_admin->status != 1)
			{
				$this->session->set_flashdata('error', "此帐号已被冻结,请联系管理员!");
				redirect(setting('backend_access_point') . '/login');
			}
		}
	}
	
	// ------------------------------------------------------------------------

    /**
     * 加载视图
     *
     * @access  protected
     * @param   string
     * @param   array
     * @return  void
     */
	protected function _template($template, $data = array())
	{
		$data['tpl'] = $template;
		$this->load->view('sys_entry', $data);
	}
	
	// ------------------------------------------------------------------------

    /**
     * 检查权限
     *
     * @access  protected
     * @param string $action
     * @param string $folder
     * @return  void
     */
	protected function _check_permit($action = '', $folder = '')
	{
		if ( ! $this->acl->permit($action, $folder))
		{
			$this->_message('对不起，你没有访问这里的权限！', '', FALSE);
		}
	}
	
	// ------------------------------------------------------------------------

    /**
     * 信息提示
     *
     * @access  public
     * @param $msg
     * @param string $goto
     * @param bool $auto
     * @param string $fix
     * @param int $pause
     * @return  void
     */
	public function _message($msg, $goto = '', $auto = TRUE, $fix = '', $pause = 3000)
	{
		if($goto == '')
		{
			$goto = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : site_url();
		}
		else
		{
			$goto = strpos($goto, 'http') !== false ? $goto : backend_url($goto);	
		}
		$goto .= $fix;
		$this->_template('sys_message', array('msg' => $msg, 'goto' => $goto, 'auto' => $auto, 'pause' => $pause));
		echo $this->output->get_output();
		exit();
	}

	// ------------------------------------------------------------------------

}

/* End of file Dili_Controller.php */
/* Location: ./admin/core/Dili_Controller.php */
	