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
 * DiliCMS 插件管理控制器
 *
 * @package     DiliCMS
 * @subpackage  Controllers
 * @category    Controllers
 * @author      Jeongee
 * @link        http://www.dilicms.com
 */
class Plugin extends Admin_Controller
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
		$this->load->model('plugin_mdl');
	}
	
	// ------------------------------------------------------------------------
	
	/**
     * 默认入口
     *
     * @access  public
     * @return  void
     */
	public function view()
	{
		$data['list'] = $this->plugin_mdl->get_plugins();
		$this->_template('plugin_list', $data);
	}
	
	// ------------------------------------------------------------------------
	
	/**
     * 添加表单入口
     *
     * @access  public
     * @return  void
     */
	public function add()
	{
		$this->_add_post();
	}
	
	// ------------------------------------------------------------------------
	
	/**
     * 添加表单呈现/处理函数
     *
     * @access  public
     * @return  void
     */
	public function _add_post()
	{	
		if ($this->_validate_model_form() == TRUE)
		{
			
			//新增分类模型
			$this->plugin_mdl->add_plugin($this->_get_post_data());
			//更新缓存
			update_cache('plugin');
			
			$this->_message('插件添加成功!', 'plugin/view', TRUE);
		}
		else
		{
			$this->_template('plugin_add');
		}
	}
	
	// ------------------------------------------------------------------------
	
	/**
     * 修改表单入口
     *
     * @access  public
     * @param   int
     * @return  void
     */
	public function edit($id = 0)
	{
		$this->_edit_post($id);
	}
	
	// ------------------------------------------------------------------------
	
	/**
     * 修改表单呈现/处理函数
     *
     * @access  public
     * @return  void
     */
	public function _edit_post($id = 0)
	{
		$target_plugin = $this->plugin_mdl->get_plugin_by_id($id);
		! $target_plugin AND $this->_message('不存在的插件!', '', FALSE);
		if ($this->_validate_model_form($target_plugin->name) == TRUE)
		{
			$this->plugin_mdl->edit_plugin($target_plugin->id, $this->_get_post_data());
			update_cache('plugin');
			$this->_message('插件修改成功!', 'plugin/edit/' . $target_plugin->id, TRUE);
		}
		else
		{
			$this->_template('plugin_edit', array('plugin' => $target_plugin));
		}
	}
	
	// ------------------------------------------------------------------------
	
	/**
     * 验证插件信息的合法性
     *
     * @access  private
     * @param   string
     * @param   bool
     * @return  bool
     */
	private function _validate_model_form($name = '', $load_form = TRUE)
	{
		$this->load->library('form_validation');
		$callback = '|callback__check_plugin_name';
		if ($name AND $name == trim($this->input->post('name', TRUE)) )
		{
			$callback = '';
		}
		$this->form_validation->set_rules('name', '插件标识', 'trim|required|alpha_dash|min_length[3]|max_length[20]' . $callback);
		$this->form_validation->set_rules('title', '插件名称', 'trim|required|max_length[50]');
		$this->form_validation->set_rules('version', '插件版本', 'trim|required|max_length[5]');
		$this->form_validation->set_rules('description', '插件描述', 'trim|max_length[200]');
		$this->form_validation->set_rules('author', '插件作者', 'trim|required|max_length[20]');
		$this->form_validation->set_rules('link', '插件网址', 'trim|max_length[100]');
		$this->form_validation->set_rules('copyrights', '插件版权', 'trim|max_length[100]');
		$this->form_validation->set_rules('access', '是否root可用', 'trim');

		if ($this->form_validation->run() == FALSE)
		{
			$load_form AND $this->load->library('form');
			return FALSE;
		}
		else
		{
			return TRUE;
		}
	}
	
	// ------------------------------------------------------------------------
	
	/**
     * 获取表单数据
     *
     * @access  private
     * @return  array
     */
	private function _get_post_data()
	{
		//获取表单数据
		$data['name'] = $this->input->post('name', TRUE);
		$data['title'] = $this->input->post('title', TRUE);
		$data['version'] = $this->input->post('version', TRUE);
		$data['description'] = $this->input->post('description', TRUE);
		$data['author'] = $this->input->post('author', TRUE);
		$data['link'] = $this->input->post('link', TRUE);
		$data['copyrights'] = $this->input->post('copyrights', TRUE);
		$data['access'] = $this->input->post('access', TRUE);
		return $data;
	}
	
	// ------------------------------------------------------------------------
	
	/**
     * 删除GET方式入口
     *
     * @access  public
     * @return  void
     */
	public function del()
	{
		$this->_del_post();
	}
	
	// ------------------------------------------------------------------------
	
	/**
     * 删除POST入口/处理函数
     *
     * @access  public
     * @return  void
     */
	public function _del_post()
	{
		$ids = $this->input->get_post('id', TRUE);
		if ( ! is_array($ids))
		{
			$ids = array($ids);	
		}
		$this->plugin_mdl->del_plugin($ids);
		update_cache('plugin');
		$this->_message('插件卸载成功!', 'plugin/view/', TRUE);
	}
	
	// ------------------------------------------------------------------------
	
	/**
     * 导出插件
     *
     * @access  public
     * @return  void
     */
	public function _export_post()
	{
		//当前仅支持在普通环境下导出插件安装XML文件
		if ($this->platform->get_type() != 'default')
		{
			$this->_message('当前环境不支持导出插件安装XML文件!', '', TRUE);	
		}
		else
		{
			$ids = $this->input->get_post('id', TRUE);
			if ( ! is_array($ids))
			{
				$ids = array($ids);	
			}
			$this->plugin_mdl->export_plugin($ids);
			$this->_message('插件导出成功!', 'plugin/view/', TRUE);	
		}
	}
	
	// ------------------------------------------------------------------------
	
	/**
     * 检查插件名称是否已经存在
     *
     * @access  public
     * @param   string
     * @return  bool
     */
	public function _check_plugin_name($name)
	{
		if ($this->plugin_mdl->check_plugin_name($name))
		{
			$this->form_validation->set_message('_check_plugin_name', '已经存在的插件标识！');	
			return FALSE;	
		}
		else
		{
			return TRUE;
		}
	}
	
	// ------------------------------------------------------------------------
	
	/**
     * 激活插件入口[GET]
     *
     * @access  public
     * @return  void
     */
	public function active()
	{
		$this->_active_post();
	}
	
	// ------------------------------------------------------------------------
	
	/**
     * 激活插件入口[POST]/处理函数
     *
     * @access  public
     * @return  void
     */
	public function _active_post()
	{
		$id = $this->input->get_post('id', TRUE);
		if ( ! is_array($id))
		{ 
			$id = array($id);
		}
		$this->plugin_mdl->active_plugins($id, 1);
		update_cache('plugin');
		$this->_message('插件启用成功!', 'plugin/view/', TRUE);
	}
	
	// ------------------------------------------------------------------------
	
	/**
     * 禁用插件入口[GET]
     *
     * @access  public
     * @return  void
     */
	public function deactive()
	{
		$this->_deactive_post();
	}
	
	// ------------------------------------------------------------------------
	
	/**
     * 禁用插件入口[POST]/处理函数
     *
     * @access  public
     * @return  void
     */
	public function _deactive_post()
	{
		$id = $this->input->get_post('id', TRUE);
		if ( ! is_array($id))
		{
			$id = array($id);
		}
		$this->plugin_mdl->active_plugins($id, 0);
		update_cache('plugin');
		$this->_message('插件禁用成功!', 'plugin/view/', TRUE);
	}
	
	// ------------------------------------------------------------------------

	/**
     * 导入插件表单页
     *
     * @access  public
     * @return  void
     */
	public function import()
	{
		$this->_template('plugin_import');	
	}
	
	// ------------------------------------------------------------------------

	/**
     * 导入插件处理函数
     *
     * @access  public
     * @return  void
     */
	public function _import_post()
	{
		$plugin = $this->input->post('plugin', TRUE);
		! class_exists('SimpleXMLElement') AND $this->_message('检测到服务器不支持SimpleXMLElement类，请开启后重试！', 'plugin/import/', TRUE);
		@libxml_use_internal_errors(TRUE); 
		try{ 
			$plugin_xml = new SimpleXMLElement($plugin, NULL, TRUE); 
			if ((string) $plugin_xml->attributes() === 'DiliCMS' AND ($plugin_info = (array) $plugin_xml->plugin[0]))
			{
				$_POST = $plugin_info;
				if ($this->_validate_model_form('', FALSE))
				{
					//新增分类模型
					$this->plugin_mdl->add_plugin($_POST);
					//更新缓存
					update_cache('plugin');
					$this->_message('插件安装成功!', 'plugin/view', TRUE);
				}
				else
				{
					$this->_message(validation_errors(), 'plugin/import/', TRUE);	
				}
			}
			else
			{
				$this->_message('不合法的安装XML文件!', 'plugin/import/', TRUE);	
			}
		} catch (Exception $e) { 
			$this->_message('XML文件读取失败，请检查地址!', 'plugin/import/', TRUE);
		} 
	}
			
	// ------------------------------------------------------------------------
	
}

/* End of file plugin.php */
/* Location: ./admin/controllers/plugin.php */