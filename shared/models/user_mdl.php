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
 * DiliCMS 用户操作模型
 *
 * @package     DiliCMS
 * @subpackage  Models
 * @category    Models
 * @author      Jeongee
 * @link        http://www.dilicms.com
 */
class User_mdl extends CI_Model
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
     * 根据用户名或者用户UID称获取该用户完整的信息
     *
     * @access  public
     * @param   mixed
     * @return  object
     */
	public function get_full_user_by_username($username = '', $type = 'username')
	{
		if ($type == 'uid')
		{
			$this->db->where('dili_admins.uid', $username);
		}
		else
		{
			$this->db->where('dili_admins.username', $username);
		}
		return $this->db->select('dili_admins.uid, dili_admins.username, dili_admins.password, dili_admins.role, dili_roles.name, dili_admins.status')
							  ->from('dili_admins')
							  ->join('dili_roles', 'dili_roles.id = dili_admins.role')
							  ->get()
							  ->row();
	}
	
	// ------------------------------------------------------------------------

    /**
     * 根据用户ID获取用户信息
     *
     * @access  public
     * @param   int
     * @return  object
     */
	public function get_user_by_uid($uid = 0)
	{
		return $this->db->where('uid', $uid)->get('dili_admins')->row();
	}

	// ------------------------------------------------------------------------

    /**
     * 根据用户名获取用户信息
     *
     * @access  public
     * @param   string
     * @return  object
     */
	public function get_user_by_name($name)
	{
		return $this->db->where('username', $name)->get('dili_admins')->row();
	}
	
	// ------------------------------------------------------------------------

    /**
     * 用户自己密码
     *
     * @access  public
     * @return  bool
     */
	public function update_user_password()
	{
		$data['password'] = md5($this->input->post('new_pass'));
		return $this->db->where('uid', $this->session->userdata('uid'))->update('dili_admins', $data);		
	}

	// ------------------------------------------------------------------------

    /**
     * 获取用户组列表
     *
     * @access  public
     * @return  object
     */
	public function get_roles()
	{
		$roles = array();
		foreach ($this->db->select('id, name')->where('id <>', 1)->get('dili_roles')->result_array() as $v)
		{
			$roles[$v['id']] = $v['name'];	
		}
		return $roles;
	}

	// ------------------------------------------------------------------------

    /**
     * 获取用户数
     *
     * @access  public
     * @param   int
     * @return  int
     */
	public function get_users_num($role_id = 0)
	{
		$this->db->where('uid <>', 1);
		if ($role_id)
		{
			$this->db->where('role', $role_id);
		}
		return $this->db->count_all_results('dili_admins');
	}

	// ------------------------------------------------------------------------

    /**
     * 获取某个用户组下所有用户
     *
     * @access  public
     * @param   int
     * @param   int
     * @param   int
     * @return  object
     */
	public function get_users($role_id = 0, $limit = 0, $offset = 0)
	{
		$this->db->where('dili_admins.uid <>', 1);
		if ($role_id)
		{
			$this->db->where('dili_admins.role', $role_id);
		}
		if ($limit)
		{
			$this->db->limit($limit);
		}
		if ($offset)
		{
			$this->db->offset($offset);
		}
		return $this->db->from('dili_admins')
						->join('dili_roles', 'dili_roles.id = dili_admins.role')
						->get()
						->result();
	}
	
	// ------------------------------------------------------------------------

    /**
     * 添加用户
     *
     * @access  public
     * @param   array
     * @return  bool
     */
	public function add_user($data)
	{
		return $this->db->insert('dili_admins', $data);
	}
	
	// ------------------------------------------------------------------------

    /**
     * 修改用户
     *
     * @access  public
     * @param   int
     * @param   array
     * @return  bool
     */
	public function edit_user($uid, $data)
	{
		return $this->db->where('uid', $uid)->update('dili_admins', $data);	
	}
	
	// ------------------------------------------------------------------------

    /**
     * 删除用户
     *
     * @access  public
     * @param   uid
     * @return  bool
     */
	public function del_user($uid)
	{
		return $this->db->where('uid', $uid)->delete('dili_admins');
	}

	// ------------------------------------------------------------------------
	
}

/* End of file user_mdl.php */
/* Location: ./shared/models/user_mdl.php */