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
 * DiliCMS 插件操作模型
 *
 * @package     DiliCMS
 * @subpackage  Models
 * @category    Models
 * @author      Jeongee
 * @link        http://www.dilicms.com
 */
class Plugin_mdl extends CI_Model
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
     * 获取所有插件
     *
     * @access  public
     * @return  object
     */
	public function get_plugins()
	{
		return $this->db->select('id, name, title, author, link, active')
						->get($this->db->dbprefix('plugins'))
						->result();
	}
	
	// ------------------------------------------------------------------------

    /**
     * 检测插件名称是否可用
     *
     * @access  public
     * @param   string
     * @return  object
     */
	public function check_plugin_name($name)
	{
		return $this->db->select('id')->where('name', $name)->get($this->db->dbprefix('plugins'))->row();
	}
	
	// ------------------------------------------------------------------------

    /**
     * 根据插件ID获取插件信息
     *
     * @access  public
     * @param   int
     * @return  object
     */
	public function get_plugin_by_id($id)
	{
		return $this->db->where('id', $id)->get($this->db->dbprefix('plugins'))->row();	
	}
	
	// ------------------------------------------------------------------------

    /**
     * 添加新插件
     *
     * @access  public
     * @param   array
     * @return  bool
     */
	public function add_plugin($data)
	{
		return $this->db->insert($this->db->dbprefix('plugins'), $data);
	}
	
	// ------------------------------------------------------------------------

    /**
     * 根据插件ID修改插件
     *
     * @access  public
     * @param   int
     * @param   array
     * @return  bool
     */
	public function edit_plugin($plugin_id, $data)
	{
		return $this->db->where('id', $plugin_id)->update($this->db->dbprefix('plugins'), $data);	
	}
	
	// ------------------------------------------------------------------------

    /**
     * 激活/卸载插件
     *
     * @access  public
     * @param   int
     * @param   int
     * @return  bool
     */
	public function active_plugins($id, $status = 1)
	{
		return $this->db->where_in('id', $id)->set('active', $status)->update($this->db->dbprefix('plugins'));
	}
	
	// ------------------------------------------------------------------------

    /**
     * 删除插件
     *
     * @access  public
     * @param   array
     * @return  bool
     */
	public function del_plugin($ids)
	{
		return $this->db->where_in('id', $ids)->delete($this->db->dbprefix('plugins'));
	}	
	
	// ------------------------------------------------------------------------

    /**
     * 导出插件
     *
     * @access  public
     * @param   array
     * @return  void
     */
	public function export_plugin($ids)
	{
		$plugins = $this->db->where_in('id', $ids)->get($this->db->dbprefix('plugins'))->result();
		foreach ($plugins as $plugin)
		{
			$xml = '<?xml version="1.0" encoding="UTF-8"?>
						<root name="DiliCMS">
							<plugin>
								<name>' . $plugin->name . '</name>
								<title>' . $plugin->title . '</title>
								<version>' . $plugin->version . '</version>
								<description>' . $plugin->description . '</description>
								<author>' . $plugin->author . '</author>
								<link>' . $plugin->link . '</link>
								<copyrights>' . $plugin->copyrights . '</copyrights>
								<access>' . $plugin->access . '</access>
							</plugin>
						</root>';
			//当前仅支持在普通环境下导出插件安装XML文件
			if ($this->platform->get_type == 'default')
			{
				file_put_contents(DILICMS_SHARE_PATH . 'plugins/' . $plugin->name .'/plugin_' . $plugin->name . '_install.xml', $xml);
			}
		}
	}

	// ------------------------------------------------------------------------
	
}

/* End of file plugin_mdl.php */
/* Location: ./shared/models/plugin_mdl.php */