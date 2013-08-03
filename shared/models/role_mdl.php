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
 * DiliCMS 用户组操作模型
 *
 * @package     DiliCMS
 * @subpackage  Models
 * @category    Models
 * @author      Jeongee
 * @link        http://www.dilicms.com
 */
class Role_mdl extends CI_Model
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
	}

	// ------------------------------------------------------------------------

    /**
     * 获取出ROOT以外的所有用户组
     *
     * @access  public
     * @return  object
     */
	public function get_roles()
	{
		return $this->db->where('id <>', '1')->get($this->db->dbprefix('roles'))->result();	
	}
	
	// ------------------------------------------------------------------------

    /**
     * 根据用户组ID获取用户信息
     *
     * @access  public
     * @param   int
     * @return  object
     */
	public function get_role_by_id($id)
	{
		return $this->db->where('id', $id)->get($this->db->dbprefix('roles'))->row();	
	}
	
	// ------------------------------------------------------------------------

    /**
     * 根据用户组名称获取用户组信息
     *
     * @access  public
     * @param   string
     * @return  object
     */
	public function get_role_by_name($name)
	{
		return $this->db->where('name', $name)->get($this->db->dbprefix('roles'))->row();	
	}
	
	// ------------------------------------------------------------------------

    /**
     * 格式化数组成ASSOC方式
     *
     * @access  private
     * @param   array
     * @param   string
     * @param   string
     * @return  array
     */
	private function _re_parse_array($array, $key, $value)
	{
		$data = array();
		foreach ($array as $v)
		{
			$data[$v->$key] = $v->$value;	
		}
		return $data;
	}
	
	// ------------------------------------------------------------------------

    /**
     * 获取表单数据
     *
     * @access  public
     * @return  array
     */
	public function get_form_data()
	{
		$data['rights'] = $this->_re_parse_array($this->db->select('right_id,right_name')->get($this->db->dbprefix('rights'))->result(), 'right_id', 'right_name');	
		$data['models'] = $this->_re_parse_array($this->db->select('name,description')->get($this->db->dbprefix('models'))->result(), 'name', 'description');
		$data['category_models'] = $this->_re_parse_array($this->db->select('name,description')->get($this->db->dbprefix('cate_models'))->result(), 'name', 'description');
		$data['plugins'] = $this->_re_parse_array($this->db->select('name,title')->where('active',1)->get($this->db->dbprefix('plugins'))->result(), 'name', 'title');
		return $data;
	}
	
	// ------------------------------------------------------------------------

    /**
     * 添加用户组
     *
     * @access  public
     * @param   array
     * @return  int
     */
	public function add_role($data)
	{
		$this->db->insert($this->db->dbprefix('roles'), $data);
		return $this->db->insert_id();
	}
	
	// ------------------------------------------------------------------------

    /**
     * 修改用户组
     *
     * @access  public
     * @param   int
     * @param   array
     * @return  bool
     */
	public function edit_role($id, $data)
	{
		return $this->db->where('id', $id)->update($this->db->dbprefix('roles'), $data);
	}
	
	// ------------------------------------------------------------------------

    /**
     * 获取用户组下用户数目
     *
     * @access  public
     * @param   int
     * @return  int
     */
	public function get_role_user_num($id)
	{
		return $this->db->where('role', $id)->count_all_results($this->db->dbprefix('admins'));
	}
	
	// ------------------------------------------------------------------------

    /**
     * 删除用户组
     *
     * @access  public
     * @param   int
     * @return  void
     */
	public function del_role($id)
	{
		$this->db->where('id', $id)->delete($this->db->dbprefix('roles'));	
		$this->platform->cache_delete(DILICMS_SHARE_PATH . 'settings/acl/role_' . $id . '.php');	
	}

	// ------------------------------------------------------------------------

}

/* End of file role_mdl.php */
/* Location: ./shared/models/role_mdl.php */