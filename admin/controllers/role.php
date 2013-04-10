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
 * DiliCMS 用户组管理控制器
 *
 * @package     DiliCMS
 * @subpackage  Controllers
 * @category    Controllers
 * @author      Jeongee
 * @link        http://www.dilicms.com
 */
class Role extends Admin_Controller
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
		$this->load->model('role_mdl');
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
		$data['list'] = $this->role_mdl->get_roles();
		$this->_template('role_list',$data);
	}
	
	// ------------------------------------------------------------------------
	
	/**
     * 添加用户组表单页
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
     * 添加用户组表单生成/处理函数
     *
     * @access  public
     * @return  void
     */
	public function _add_post()
	{
		$data = $this->role_mdl->get_form_data();
		if ( ! $this->_validate_role_form())
		{
			$this->_template('role_add', $data);
		}
		else
		{
			$role_id = $this->role_mdl->add_role($this->_get_form_data());
			
			update_cache('role', $role_id);
			
			$this->_message('用户组添加成功!', 'role/view', TRUE);	
		}
	}
	
	// ------------------------------------------------------------------------
	
	/**
     * 修改用户组表单入口
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
     * 修改用户组表单生成/处理函数
     *
     * @access  public
     * @param   int
     * @return  void
     */
	public function _edit_post($id = 0)
	{
		$data = $this->role_mdl->get_form_data();
		$data['role'] = $this->role_mdl->get_role_by_id($id);
		if ( ! $data['role'])
		{
			$this->_message('不存在的用户组', '', FALSE);
		}
		if ( ! $this->_validate_role_form($data['role']->name))
		{
			$this->_template('role_edit', $data);
		}
		else
		{
			$this->role_mdl->edit_role($id, $this->_get_form_data());
			update_cache('role', $id);
			$this->_message('用户组修改成功!', 'role/edit/' . $id, TRUE);
		}
	}
	
	// ------------------------------------------------------------------------
	
	/**
     * 删除用户组
     *
     * @access  public
     * @param   int
     * @return  void
     */
	public function del($id = 0)
	{
		$role = $this->role_mdl->get_role_by_id($id);
		if ( ! $role)
		{
			$this->_message('不存在的用户组', '', FALSE);
		}
		if ($this->role_mdl->get_role_user_num($id) > 0)
		{
			$this->_message('该用户组下有用户不允许删除!', '', FALSE);
		}	
		$this->role_mdl->del_role($id);
		$this->_message('用户组删除成功!', '', FALSE); 
	}
	
	// ------------------------------------------------------------------------
	
	/**
     * 检查用户组名称是否存在
     *
     * @access  public
     * @param   string
     * @return  bool
     */
	public function _check_role_name($name = '')
	{
		if ($this->role_mdl->get_role_by_name($name))
		{
			$this->form_validation->set_message('_check_role_name', '已经存在的用户组名称！');
			return FALSE;		
		}
		return TRUE;
	}	
	
	// ------------------------------------------------------------------------
	
	/**
     * 检查表单数据合法性
     *
     * @access  private
     * @param   string
     * @return  bool
     */
	private function _validate_role_form($name = '')
	{
		$this->load->library('form_validation');
		$callback = '|callback__check_role_name';
		if ($name AND $name == trim($this->input->post('name', TRUE)))
		{
			$callback = '';
		}
		$this->form_validation->set_rules('name', '用户组名称', 'trim|required|min_length[3]|max_length[20]' . $callback);
		if ($this->form_validation->run() == FALSE)
		{
			$this->load->library('form');
			return FALSE;
		}
		else
		{
			return TRUE;
		}
	}
	
	// ------------------------------------------------------------------------
	
	/**
     * 数组转换成字符串
     *
     * @access  private
     * @param   array
     * @return  string
     */
	private function _array_to_string($array)
	{
		if ($array AND is_array($array))
		{
			return implode(',', $array);	
		}
		else
		{
			return '0';	
		}
	}
	
	// ------------------------------------------------------------------------
	
	/**
     * 获取表单数据
     *
     * @access  private
     * @param   int
     * @return  array
     */
	private function _get_form_data()
	{
		$data['name'] = $this->input->post('name', TRUE);
		$data['rights'] = $this->_array_to_string($this->input->post('right', TRUE));
		$data['models'] = $this->_array_to_string($this->input->post('model', TRUE));
		$data['category_models'] = $this->_array_to_string($this->input->post('category_model', TRUE));
		$data['plugins'] = $this->_array_to_string($this->input->post('plugin', TRUE));	
		return $data;
	}

	// ------------------------------------------------------------------------
	
}

/* End of file role.php */
/* Location: ./admin/controllers/role.php */
