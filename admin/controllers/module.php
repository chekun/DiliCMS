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
 * DiliCMS 模块插件执行控制器
 *
 * @package     DiliCMS
 * @subpackage  Controllers
 * @category    Controllers
 * @author      Jeongee
 * @link        http://www.dilicms.com
 */
class Module extends Admin_Controller
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
		$this->acl->detect_plugin_menus();
	}
	
	// ------------------------------------------------------------------------

    /**
     * GET方式入口
     *
     * @access  public
     * @return  void
     */
	public function run()
	{
		$this->_run_post();
	}
	
	// ------------------------------------------------------------------------

    /**
     * POST方式入口
     *
     * @access  public
     * @return  void
     */
	public function _run_post()
	{
		$plugin = $this->input->get('plugin', TRUE);
		if ( ! $plugin AND $this->acl->_default_link)
		{
			redirect($this->acl->_default_link);
		}
		$this->_check_permit();
		$action = $this->input->get('action', TRUE);
		if ( $action
			AND
			isset($this->plugin_manager->active_plugins[$plugin]['instance']) 
			AND 
		    in_array(strtolower($action), array_map('strtolower', get_class_methods('plugin_' . $plugin)))
		)
		{
				$data['content'] = $this->plugin_manager->active_plugins[$plugin]['instance']->$action();
				$this->_template('', $data);
		}
		else
		{
			$this->_message('未定义的操作!', '', FALSE);	
		}
	}

	// ------------------------------------------------------------------------
	
}

/* End of file module.php */
/* Location: ./admin/controllers/module.php */