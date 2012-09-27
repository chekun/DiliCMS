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
 * DiliCMS 设置管理控制器
 *
 * @package     DiliCMS
 * @subpackage  Controllers
 * @category    Controllers
 * @author      Jeongee
 * @link        http://www.dilicms.com
 */
class Setting extends Admin_Controller
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
		$this->_check_permit();
	}
	
	// ------------------------------------------------------------------------
	
	/**
     * 站点设置表单页
     *
     * @access  public
     * @return  void
     */
	public function site()
	{
		$data['site'] = $this->db->get($this->db->dbprefix('site_settings'))->row();
		$this->_template('settings_site', $data);
	}
	
	// ------------------------------------------------------------------------
	
	/**
     * 站点设置处理函数
     *
     * @access  public
     * @return  void
     */
	public function _site_post()
	{
		$this->db->update($this->db->dbprefix('site_settings'), $this->input->post());
		update_cache('site');
		$this->_message("更新成功", 'setting/site', TRUE, ($this->input->get('tab') ? '?tab=' . $this->input->get('tab') : '' ));
	}
	
	// ------------------------------------------------------------------------
	
	/**
     * DiliCMS 设置表单页
     *
     * @access  public
     * @return  void
     */
	public function backend()
	{
		$data['backend'] = $this->db->get($this->db->dbprefix('backend_settings'))->row();
		$this->_template('settings_backend', $data);
	}
	
	// ------------------------------------------------------------------------
	
	/**
     * DiliCMS 设置处理函数
     *
     * @access  public
     * @return  void
     */
	public function _backend_post()
	{
		$this->db->update($this->db->dbprefix('backend_settings'), $this->input->post());
		update_cache('backend');
		$this->_message("更新成功", 'setting/backend', TRUE, ($this->input->get('tab') ? '?tab=' . $this->input->get('tab') : '' ));
	}
		
	// ------------------------------------------------------------------------
	
}

/* End of file setting.php */
/* Location: ./admin/controllers/setting.php */