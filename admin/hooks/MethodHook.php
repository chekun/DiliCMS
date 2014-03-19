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
 * DiliCMS pre-controller Hook
 *
 * @package     DiliCMS
 * @subpackage  Hooks
 * @category    Hooks
 * @author      Jeongee
 * @link        http://www.dilicms.com
 */

class MethodHook 
{
	/**
     * 构造函数
     *
     * @access  public
     * @return  void
     */	
	public function __construct()
	{
		//nothing to do yet!	
	}
	
	// ------------------------------------------------------------------------

    /**
     * 将POST请求的方法method变成_method_post。
     *
     * @access  public
     * @return  void
     */	
	public function redirect()
	{
		global $method;
		if (isset($_SERVER['REQUEST_METHOD']) and $_SERVER['REQUEST_METHOD'] !== 'GET')
		{
			$method = '_' . $method . '_'. strtolower($_SERVER['REQUEST_METHOD']);
		}
	}
		
	// ------------------------------------------------------------------------

}

/* End of file MethodHook.php */
/* Location: ./admin/hooks/MethodHook.php */
