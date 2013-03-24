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
 * DiliCMS 联动菜单数据提供控制器
 *
 * Note : 临时的解决方案
 *
 * @package     DiliCMS
 * @subpackage  Controllers
 * @category    Controllers
 * @author      Jeongee
 * @link        http://www.dilicms.com
 */
class Ld extends CI_Controller
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
        $this->load->database();
	}
	
	// ------------------------------------------------------------------------

    /**
     * 处理上传的POST请求
     *
     * @access  public
     * @param   string
     * @param   string
     * @return  void
     */
	public function json($model = '' , $name = '')
	{
		if ( ! $model OR ! $name)
		{
			return;
		}
		$parentid = $this->input->get('parentid');
		if ($parentid == '')
		{
			echo  '[]';
			return;
		}
		@eval('?>' . $this->platform->cache_read(DILICMS_SHARE_PATH . 'settings/category/data_' . $model . '.php'));
		$json_str = "[";
		$json = array();
		foreach ($setting['category'][$model] as $v)
		{
			if ($v['parentid'] == $parentid)
			{
				$json[] = json_encode(array('classid' => $v['classid'], $name => $v[$name]));
			}
		}
		$json_str .= implode(',', $json);
		$json_str .= "]";
		echo $json_str;	
	}
	
	// ------------------------------------------------------------------------

}

/* End of file ld.php */
/* Location: ./admin/controllers/ld.php */