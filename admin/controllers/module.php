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
    
    protected $plugin = null;
    
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
		
		$this->initialize();
		
	}
	
	private function initialize()
	{
	    $plugin = $this->input->get('plugin', TRUE);
		if ( ! $plugin AND $this->acl->_default_link)
		{
			redirect($this->acl->_default_link);
		}
		$this->_check_permit();
		$controller = $this->input->get('c', true);
		$method = $this->input->get('m', TRUE);
		$path = DILICMS_EXTENSION_PATH.'plugins/'.$plugin.'/controllers/'.$plugin.'_'.$controller.'.php';
		if ( $controller
			and
			file_exists($path)
		)
		{
		    include $path;
		    $controller = ucfirst($plugin . '_' . $controller);
		    $this->plugin = new $controller($plugin);
		    $data['content'] = $this->plugin->$method();
            $this->_template('', $data);
            exit($this->output->get_output());
		}
		else
		{
			$this->_message('未找到处理程序!', '', FALSE);	
		}
	}
	
}

/* End of file module.php */
/* Location: ./admin/controllers/module.php */